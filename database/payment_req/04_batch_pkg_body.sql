CREATE OR REPLACE PACKAGE BODY GFC_PAK.DISBURSEMENT_BATCH_PKG AS

  -- ============================================================
  -- Alias للـ DISBURSEMENT_PKG functions
  -- ============================================================


  -- ============================================================
  -- BATCH PREVIEW — معاينة الاحتساب حسب البنك
  -- ============================================================
  PROCEDURE PAYMENT_REQ_BATCH_PREVIEW (
      P_REQ_IDS      IN  VARCHAR2,
      P_REF_CUR_OUT  OUT SYS_REFCURSOR,
      P_MSG_OUT      OUT VARCHAR2
  ) IS
  BEGIN
    -- ⚠️ نُفلتر duplicates في DATA.EMPLOYEES (لو وُجدت) لتفادي تدبيل صفوف الـ details
    OPEN P_REF_CUR_OUT FOR
      WITH EMP_DEDUP AS (
        SELECT NO, BANK, ACCOUNT, IBAN, MASTER_BANKS_EMAIL,
               ROW_NUMBER() OVER (PARTITION BY NO ORDER BY ROWID) AS RN
          FROM DATA.EMPLOYEES
      )
      SELECT
          D.DETAIL_ID, D.REQ_ID, M.REQ_NO, D.EMP_NO,
          EMP_PKG.GET_EMP_NAME(D.EMP_NO)                AS EMP_NAME,
          EMP_PKG.GET_EMP_BRANCH(D.EMP_NO)              AS BRANCH_NO,
          EMP_PKG.GET_EMP_BRANCH_NAME(D.EMP_NO)         AS BRANCH_NAME,
          M.THE_MONTH, M.REQ_TYPE,
          SETTING_PKG.CONSTANT_DETAILS_TB_GET_NAME(538, M.REQ_TYPE) AS REQ_TYPE_NAME,
          D.PAY_TYPE, D.REQ_AMOUNT,
          NVL(E.BANK, 0)                                AS BANK_NO,
          DISBURSEMENT_PKG.GET_BANK_NAME(E.BANK)         AS BANK_NAME,
          E.ACCOUNT                                      AS BANK_ACCOUNT,
          E.IBAN,
          NVL(E.MASTER_BANKS_EMAIL, 0) AS MASTER_BANK_NO,
          DISBURSEMENT_PKG.GET_MASTER_BANK_NAME(D.EMP_NO)  AS MASTER_BANK_NAME,
          NVL(E.BANK, 0)                                 AS BANK_BRANCH_NO,
          E.ACCOUNT                                      AS ACCOUNT_BANK_EMAIL,
          CASE WHEN NVL(E.BANK, 0) = 0 THEN 1 ELSE 0 END AS NO_BANK_FLAG
      FROM GFC.PAYMENT_REQ_DETAIL_TB D
      JOIN GFC.PAYMENT_REQ_TB M  ON M.REQ_ID = D.REQ_ID
      LEFT JOIN EMP_DEDUP E      ON E.NO     = D.EMP_NO AND E.RN = 1
      WHERE INSTR(',' || P_REQ_IDS || ',', ',' || TO_CHAR(D.REQ_ID) || ',') > 0
        AND M.STATUS IN (C_REQ_STATUS_APPROVED, C_REQ_STATUS_PARTIAL_APPROVED, C_REQ_STATUS_PARTIAL_PAID)
        AND D.DETAIL_STATUS = C_REQ_STATUS_APPROVED
      ORDER BY D.EMP_NO, M.THE_MONTH;
    P_MSG_OUT := '1';
  EXCEPTION WHEN OTHERS THEN P_MSG_OUT := SQLERRM;
  END;


  -- ============================================================
  -- BATCH CONFIRM — اعتماد الاحتساب (إنشاء دفعة)
  -- ============================================================
  PROCEDURE PAYMENT_REQ_BATCH_CONFIRM (
      P_REQ_IDS             IN  VARCHAR2,
      P_EXCLUDE_DETAIL_IDS  IN  VARCHAR2 DEFAULT NULL,
      P_DISBURSE_METHOD     IN  NUMBER   DEFAULT 1,   -- 1=قديم، 2=جديد (split)
      P_MSG_OUT             OUT VARCHAR2
  ) IS
    V_USER       NUMBER := USER_PKG.GET_USER_ID;
    V_BATCH_ID   NUMBER;
    V_TOTAL_AMT  NUMBER := 0;
    V_EMP_CNT    NUMBER := 0;
    V_PREV_EMP   NUMBER := -1;          -- لعدّ الموظفين الفريدين (الـ cursor مرتب حسب EMP_NO)
    V_METHOD     NUMBER := NVL(P_DISBURSE_METHOD, 1);
    V_MISSING    VARCHAR2(2000) := '';   -- موظفين بدون حسابات في الطريقة الجديدة
  BEGIN
    -- التحقق من قيمة الطريقة
    IF V_METHOD NOT IN (1, 2) THEN V_METHOD := 1; END IF;

    V_BATCH_ID := PAYMENT_BATCH_SEQ.NEXTVAL;
    INSERT INTO GFC.PAYMENT_BATCH_TB (
        BATCH_ID, BATCH_NO, BATCH_DATE, TOTAL_AMOUNT, EMP_COUNT,
        REQ_IDS, STATUS, ENTRY_USER, ENTRY_DATE, DISBURSE_METHOD
    ) VALUES (
        V_BATCH_ID, 'PB-' || LPAD(V_BATCH_ID, 5, '0'), SYSDATE, 0, 0,
        SUBSTR(P_REQ_IDS, 1, 4000), 0, V_USER, SYSDATE, V_METHOD
    );

    INSERT INTO GFC.PAYMENT_BATCH_LINK_TB (BATCH_ID, DETAIL_ID)
      SELECT V_BATCH_ID, D.DETAIL_ID
        FROM GFC.PAYMENT_REQ_DETAIL_TB D
        JOIN GFC.PAYMENT_REQ_TB M ON M.REQ_ID = D.REQ_ID
       WHERE INSTR(',' || P_REQ_IDS || ',', ',' || TO_CHAR(M.REQ_ID) || ',') > 0
         AND D.DETAIL_STATUS = C_REQ_STATUS_APPROVED
         AND (P_EXCLUDE_DETAIL_IDS IS NULL
              OR INSTR(',' || P_EXCLUDE_DETAIL_IDS || ',', ',' || TO_CHAR(D.DETAIL_ID) || ',') = 0);

    -- =====================================================================
    -- التجميع: لكل (emp_no + override_signature) سطر واحد في EMP_AMT
    -- - الـ details بدون override → تُجمع معاً (override_provider_type=NULL, override_acc_id=NULL)
    -- - الـ details مع override يكون لكل (emp, override_provider_type, override_acc_id) جروب لحاله
    -- النتيجة: نفس الموظف ممكن يطلع له أكثر من سطر في الـ loop (لكل override جروب)
    -- لكن EMP_COUNT يُحسب distinct EMP_NO
    -- =====================================================================
    -- ⚠️ مهم: نُفلتر duplicates في DATA.EMPLOYEES (لو وُجدت) لتفادي تدبيل SUM(REQ_AMOUNT)
    FOR E IN (
      WITH EMP_DEDUP AS (
        SELECT EE.NO, EE.BANK, EE.MASTER_BANKS_EMAIL, EE.NAME, EE.ID, EE.IBAN, EE.ACCOUNT_BANK_EMAIL,
               ROW_NUMBER() OVER (PARTITION BY EE.NO ORDER BY ROWID) AS RN
          FROM DATA.EMPLOYEES EE
      ),
      EMP_AMT AS (
        SELECT D.EMP_NO,
               D.OVERRIDE_PROVIDER_TYPE,
               D.OVERRIDE_ACC_ID,
               SUM(D.REQ_AMOUNT) AS TOTAL_AMOUNT
          FROM GFC.PAYMENT_REQ_DETAIL_TB D
          JOIN GFC.PAYMENT_BATCH_LINK_TB L ON L.DETAIL_ID = D.DETAIL_ID AND L.BATCH_ID = V_BATCH_ID
          WHERE D.DETAIL_STATUS = C_REQ_STATUS_APPROVED
          GROUP BY D.EMP_NO, D.OVERRIDE_PROVIDER_TYPE, D.OVERRIDE_ACC_ID
      )
      SELECT EA.EMP_NO, EA.TOTAL_AMOUNT,
             EA.OVERRIDE_PROVIDER_TYPE,
             EA.OVERRIDE_ACC_ID,
             ED.BANK                AS BANK_NO,
             ED.MASTER_BANKS_EMAIL  AS MASTER_BANK_NO,
             ED.NAME                AS EMP_NAME,
             TO_CHAR(ED.ID)         AS EMP_ID_STR,
             ED.IBAN                AS EMP_IBAN,
             ED.ACCOUNT_BANK_EMAIL  AS EMP_ACCOUNT,
             (SELECT MB.B_NAME FROM DATA.MASTER_BANKS_EMAIL MB WHERE MB.B_NO = ED.MASTER_BANKS_EMAIL AND ROWNUM = 1) AS MASTER_BANK_NAME,
             DISBURSEMENT_PKG.GET_BANK_NAME(ED.BANK) AS BANK_BRANCH_NAME
        FROM EMP_AMT EA
        LEFT JOIN EMP_DEDUP ED ON ED.NO = EA.EMP_NO AND ED.RN = 1
        ORDER BY EA.EMP_NO,
                 CASE WHEN EA.OVERRIDE_PROVIDER_TYPE IS NULL AND EA.OVERRIDE_ACC_ID IS NULL THEN 0 ELSE 1 END,
                 EA.OVERRIDE_PROVIDER_TYPE NULLS FIRST,
                 EA.OVERRIDE_ACC_ID NULLS FIRST
    ) LOOP
      IF V_METHOD = 1 THEN
        -- ================== الطريقة القديمة ==================
        -- سطر واحد لكل موظف+override-group، ببنك EMPLOYEES + snapshot من EMPLOYEES
        -- (override نظرياً ما يطبّق على method=1 لأن مفيش ACC_ID — الـ groups بتنخلط)
        INSERT INTO GFC.PAYMENT_BATCH_DETAIL_TB (
            BATCH_DETAIL_ID, BATCH_ID, EMP_NO, BANK_NO, MASTER_BANK_NO, TOTAL_AMOUNT, ACC_ID,
            OVERRIDE_PROVIDER_TYPE, OVERRIDE_ACC_ID,
            SNAP_IBAN, SNAP_ACCOUNT_NO, SNAP_OWNER_NAME, SNAP_OWNER_ID_NO,
            SNAP_PROVIDER_NAME, SNAP_BRANCH_NAME
        ) VALUES (
            PAYMENT_BATCH_DETAIL_SEQ.NEXTVAL, V_BATCH_ID, E.EMP_NO,
            NVL(E.BANK_NO, 0), NVL(E.MASTER_BANK_NO, 0), E.TOTAL_AMOUNT, NULL,
            E.OVERRIDE_PROVIDER_TYPE, E.OVERRIDE_ACC_ID,
            E.EMP_IBAN, E.EMP_ACCOUNT, E.EMP_NAME, E.EMP_ID_STR,
            E.MASTER_BANK_NAME, E.BANK_BRANCH_NAME
        );
        V_TOTAL_AMT := V_TOTAL_AMT + E.TOTAL_AMOUNT;
        IF E.EMP_NO <> V_PREV_EMP THEN V_EMP_CNT := V_EMP_CNT + 1; V_PREV_EMP := E.EMP_NO; END IF;
      ELSE
        -- ================== الطريقة الجديدة ==================
        -- نقرأ حسابات الموظف النشطة من PAYMENT_ACCOUNTS_TB ونوزّع المبلغ حسب SPLIT
        -- مع snapshot لكل حساب (IBAN/owner/provider/branch) محنّط في BATCH_DETAIL
        -- 🆕 Override:
        --   - OVERRIDE_ACC_ID: 100% للحساب المحدد (تجاوز الـ split بالكامل)
        --   - OVERRIDE_PROVIDER_TYPE: split عادي بس مقتصر على نوع المزود ده (1=بنك, 2=محفظة)
        DECLARE
          V_REMAINING  NUMBER := E.TOTAL_AMOUNT;
          V_AMOUNT     NUMBER;
          V_ACC_COUNT  NUMBER := 0;
          V_HAS_REST   NUMBER := 0;
          V_FIXED_SUM  NUMBER := 0;
          V_PCT_SUM    NUMBER := 0;
        BEGIN
          -- 🎯 حالة 1: OVERRIDE_ACC_ID موجود → 100% لهذا الحساب
          IF E.OVERRIDE_ACC_ID IS NOT NULL THEN
            DECLARE V_FOUND NUMBER := 0; BEGIN
              FOR A IN (SELECT PA.ACC_ID, PA.IBAN,
                               NVL(PA.ACCOUNT_NO, PA.WALLET_NUMBER) AS SNAP_ACC,
                               PA.OWNER_NAME, PA.OWNER_ID_NO,
                               PP.PROVIDER_NAME, BR.BRANCH_NAME
                          FROM GFC.PAYMENT_ACCOUNTS_TB PA
                          LEFT JOIN GFC.PAYMENT_PROVIDERS_TB PP ON PP.PROVIDER_ID = PA.PROVIDER_ID
                          LEFT JOIN GFC.PAYMENT_BANK_BRANCHES_TB BR ON BR.BRANCH_ID = PA.BRANCH_ID
                         WHERE PA.ACC_ID = E.OVERRIDE_ACC_ID
                           AND PA.EMP_NO = E.EMP_NO
                           AND PA.STATUS = 1)
              LOOP
                INSERT INTO GFC.PAYMENT_BATCH_DETAIL_TB (
                    BATCH_DETAIL_ID, BATCH_ID, EMP_NO, BANK_NO, MASTER_BANK_NO, TOTAL_AMOUNT, ACC_ID,
                    OVERRIDE_PROVIDER_TYPE, OVERRIDE_ACC_ID,
                    SNAP_IBAN, SNAP_ACCOUNT_NO, SNAP_OWNER_NAME, SNAP_OWNER_ID_NO,
                    SNAP_PROVIDER_NAME, SNAP_BRANCH_NAME
                ) VALUES (
                    PAYMENT_BATCH_DETAIL_SEQ.NEXTVAL, V_BATCH_ID, E.EMP_NO,
                    NVL(E.BANK_NO, 0), NVL(E.MASTER_BANK_NO, 0), E.TOTAL_AMOUNT, A.ACC_ID,
                    E.OVERRIDE_PROVIDER_TYPE, E.OVERRIDE_ACC_ID,
                    A.IBAN, A.SNAP_ACC, A.OWNER_NAME, A.OWNER_ID_NO,
                    A.PROVIDER_NAME, A.BRANCH_NAME
                );
                V_FOUND := 1;
              END LOOP;
              IF V_FOUND = 0 THEN
                -- الحساب المحدد مش موجود/غير صالح → سجل الموظف كناقص
                V_MISSING := V_MISSING ||
                  CASE WHEN LENGTH(V_MISSING) > 0 THEN ',' ELSE '' END ||
                  TO_CHAR(E.EMP_NO);
                CONTINUE;
              END IF;
              V_TOTAL_AMT := V_TOTAL_AMT + E.TOTAL_AMOUNT;
              IF E.EMP_NO <> V_PREV_EMP THEN V_EMP_CNT := V_EMP_CNT + 1; V_PREV_EMP := E.EMP_NO; END IF;
              CONTINUE;  -- ننتقل للسطر التالي
            END;
          END IF;

          -- 🎯 حالة 2: split عادي (مع filter اختياري على PROVIDER_TYPE)
          SELECT COUNT(*),
                 SUM(CASE WHEN PA.SPLIT_TYPE = 3 THEN 1 ELSE 0 END),
                 NVL(SUM(CASE WHEN PA.SPLIT_TYPE = 2 THEN PA.SPLIT_VALUE ELSE 0 END), 0),
                 NVL(SUM(CASE WHEN PA.SPLIT_TYPE = 1 THEN PA.SPLIT_VALUE ELSE 0 END), 0)
            INTO V_ACC_COUNT, V_HAS_REST, V_FIXED_SUM, V_PCT_SUM
            FROM GFC.PAYMENT_ACCOUNTS_TB PA
            LEFT JOIN GFC.PAYMENT_PROVIDERS_TB PP ON PP.PROVIDER_ID = PA.PROVIDER_ID
           WHERE PA.EMP_NO = E.EMP_NO
             AND PA.STATUS = 1 AND PA.IS_ACTIVE = 1
             AND (E.OVERRIDE_PROVIDER_TYPE IS NULL OR PP.PROVIDER_TYPE = E.OVERRIDE_PROVIDER_TYPE);

          IF V_ACC_COUNT = 0 THEN
            V_MISSING := V_MISSING ||
              CASE WHEN LENGTH(V_MISSING) > 0 THEN ',' ELSE '' END ||
              TO_CHAR(E.EMP_NO);
            CONTINUE;
          END IF;

          -- ⚠️ منع التجاوز: لو مجموع المبالغ الثابتة + النسب أكبر من المستحق
          -- (يطبّق على المجموعة المفلترة فقط لو في override)
          IF V_FIXED_SUM > E.TOTAL_AMOUNT
             OR V_PCT_SUM > 100
             OR (V_FIXED_SUM + ROUND(E.TOTAL_AMOUNT * V_PCT_SUM / 100, 2)) > E.TOTAL_AMOUNT THEN
            V_MISSING := V_MISSING ||
              CASE WHEN LENGTH(V_MISSING) > 0 THEN ',' ELSE '' END ||
              TO_CHAR(E.EMP_NO);
            CONTINUE;
          END IF;

          -- المرحلة 1: مبلغ ثابت (SPLIT_TYPE=2)
          FOR A IN (SELECT PA.ACC_ID, PA.SPLIT_VALUE,
                           PA.IBAN,
                           NVL(PA.ACCOUNT_NO, PA.WALLET_NUMBER) AS SNAP_ACCOUNT_NO,
                           PA.OWNER_NAME, PA.OWNER_ID_NO,
                           PP.PROVIDER_NAME, BR.BRANCH_NAME
                      FROM GFC.PAYMENT_ACCOUNTS_TB PA
                      LEFT JOIN GFC.PAYMENT_PROVIDERS_TB PP ON PP.PROVIDER_ID = PA.PROVIDER_ID
                      LEFT JOIN GFC.PAYMENT_BANK_BRANCHES_TB BR ON BR.BRANCH_ID = PA.BRANCH_ID
                     WHERE PA.EMP_NO = E.EMP_NO AND PA.STATUS = 1 AND PA.IS_ACTIVE = 1
                       AND PA.SPLIT_TYPE = 2
                       AND (E.OVERRIDE_PROVIDER_TYPE IS NULL OR PP.PROVIDER_TYPE = E.OVERRIDE_PROVIDER_TYPE)
                     ORDER BY PA.SPLIT_ORDER, PA.ACC_ID)
          LOOP
            V_AMOUNT := LEAST(NVL(A.SPLIT_VALUE, 0), V_REMAINING);
            IF V_AMOUNT > 0 THEN
              INSERT INTO GFC.PAYMENT_BATCH_DETAIL_TB (
                  BATCH_DETAIL_ID, BATCH_ID, EMP_NO, BANK_NO, MASTER_BANK_NO, TOTAL_AMOUNT, ACC_ID,
                  OVERRIDE_PROVIDER_TYPE, OVERRIDE_ACC_ID,
                  SNAP_IBAN, SNAP_ACCOUNT_NO, SNAP_OWNER_NAME, SNAP_OWNER_ID_NO,
                  SNAP_PROVIDER_NAME, SNAP_BRANCH_NAME
              ) VALUES (
                  PAYMENT_BATCH_DETAIL_SEQ.NEXTVAL, V_BATCH_ID, E.EMP_NO,
                  NVL(E.BANK_NO, 0), NVL(E.MASTER_BANK_NO, 0), V_AMOUNT, A.ACC_ID,
                  E.OVERRIDE_PROVIDER_TYPE, E.OVERRIDE_ACC_ID,
                  A.IBAN, A.SNAP_ACCOUNT_NO, A.OWNER_NAME, A.OWNER_ID_NO,
                  A.PROVIDER_NAME, A.BRANCH_NAME
              );
              V_REMAINING := V_REMAINING - V_AMOUNT;
            END IF;
          END LOOP;

          -- المرحلة 2: نسبة مئوية (SPLIT_TYPE=1)
          FOR A IN (SELECT PA.ACC_ID, PA.SPLIT_VALUE,
                           PA.IBAN,
                           NVL(PA.ACCOUNT_NO, PA.WALLET_NUMBER) AS SNAP_ACCOUNT_NO,
                           PA.OWNER_NAME, PA.OWNER_ID_NO,
                           PP.PROVIDER_NAME, BR.BRANCH_NAME
                      FROM GFC.PAYMENT_ACCOUNTS_TB PA
                      LEFT JOIN GFC.PAYMENT_PROVIDERS_TB PP ON PP.PROVIDER_ID = PA.PROVIDER_ID
                      LEFT JOIN GFC.PAYMENT_BANK_BRANCHES_TB BR ON BR.BRANCH_ID = PA.BRANCH_ID
                     WHERE PA.EMP_NO = E.EMP_NO AND PA.STATUS = 1 AND PA.IS_ACTIVE = 1
                       AND PA.SPLIT_TYPE = 1
                       AND (E.OVERRIDE_PROVIDER_TYPE IS NULL OR PP.PROVIDER_TYPE = E.OVERRIDE_PROVIDER_TYPE)
                     ORDER BY PA.SPLIT_ORDER, PA.ACC_ID)
          LOOP
            V_AMOUNT := ROUND(E.TOTAL_AMOUNT * NVL(A.SPLIT_VALUE, 0) / 100, 2);
            V_AMOUNT := LEAST(V_AMOUNT, V_REMAINING);
            IF V_AMOUNT > 0 THEN
              INSERT INTO GFC.PAYMENT_BATCH_DETAIL_TB (
                  BATCH_DETAIL_ID, BATCH_ID, EMP_NO, BANK_NO, MASTER_BANK_NO, TOTAL_AMOUNT, ACC_ID,
                  OVERRIDE_PROVIDER_TYPE, OVERRIDE_ACC_ID,
                  SNAP_IBAN, SNAP_ACCOUNT_NO, SNAP_OWNER_NAME, SNAP_OWNER_ID_NO,
                  SNAP_PROVIDER_NAME, SNAP_BRANCH_NAME
              ) VALUES (
                  PAYMENT_BATCH_DETAIL_SEQ.NEXTVAL, V_BATCH_ID, E.EMP_NO,
                  NVL(E.BANK_NO, 0), NVL(E.MASTER_BANK_NO, 0), V_AMOUNT, A.ACC_ID,
                  E.OVERRIDE_PROVIDER_TYPE, E.OVERRIDE_ACC_ID,
                  A.IBAN, A.SNAP_ACCOUNT_NO, A.OWNER_NAME, A.OWNER_ID_NO,
                  A.PROVIDER_NAME, A.BRANCH_NAME
              );
              V_REMAINING := V_REMAINING - V_AMOUNT;
            END IF;
          END LOOP;

          -- المرحلة 3: كامل الباقي (SPLIT_TYPE=3)
          IF V_REMAINING > 0 THEN
            DECLARE
              V_REST_CNT NUMBER;
              V_IDX      NUMBER := 0;
              V_PER      NUMBER;
              V_AMT_R    NUMBER;
            BEGIN
              SELECT COUNT(*) INTO V_REST_CNT
                FROM GFC.PAYMENT_ACCOUNTS_TB PA
                LEFT JOIN GFC.PAYMENT_PROVIDERS_TB PP ON PP.PROVIDER_ID = PA.PROVIDER_ID
               WHERE PA.EMP_NO = E.EMP_NO AND PA.STATUS = 1 AND PA.IS_ACTIVE = 1
                 AND PA.SPLIT_TYPE = 3
                 AND (E.OVERRIDE_PROVIDER_TYPE IS NULL OR PP.PROVIDER_TYPE = E.OVERRIDE_PROVIDER_TYPE);

              IF V_REST_CNT > 0 THEN
                V_PER := ROUND(V_REMAINING / V_REST_CNT, 2);

                FOR A IN (SELECT PA.ACC_ID, PA.IBAN,
                                 NVL(PA.ACCOUNT_NO, PA.WALLET_NUMBER) AS SNAP_ACC,
                                 PA.OWNER_NAME, PA.OWNER_ID_NO,
                                 PP.PROVIDER_NAME, BR.BRANCH_NAME
                            FROM GFC.PAYMENT_ACCOUNTS_TB PA
                            LEFT JOIN GFC.PAYMENT_PROVIDERS_TB PP ON PP.PROVIDER_ID = PA.PROVIDER_ID
                            LEFT JOIN GFC.PAYMENT_BANK_BRANCHES_TB BR ON BR.BRANCH_ID = PA.BRANCH_ID
                           WHERE PA.EMP_NO = E.EMP_NO AND PA.STATUS = 1 AND PA.IS_ACTIVE = 1
                             AND PA.SPLIT_TYPE = 3
                             AND (E.OVERRIDE_PROVIDER_TYPE IS NULL OR PP.PROVIDER_TYPE = E.OVERRIDE_PROVIDER_TYPE)
                           ORDER BY PA.IS_DEFAULT DESC, PA.SPLIT_ORDER, PA.ACC_ID)
                LOOP
                  V_IDX := V_IDX + 1;
                  IF V_IDX = V_REST_CNT THEN V_AMT_R := V_REMAINING;
                  ELSE V_AMT_R := LEAST(V_PER, V_REMAINING); END IF;

                  IF V_AMT_R > 0 THEN
                    INSERT INTO GFC.PAYMENT_BATCH_DETAIL_TB (
                        BATCH_DETAIL_ID, BATCH_ID, EMP_NO, BANK_NO, MASTER_BANK_NO, TOTAL_AMOUNT, ACC_ID,
                        OVERRIDE_PROVIDER_TYPE, OVERRIDE_ACC_ID,
                        SNAP_IBAN, SNAP_ACCOUNT_NO, SNAP_OWNER_NAME, SNAP_OWNER_ID_NO,
                        SNAP_PROVIDER_NAME, SNAP_BRANCH_NAME
                    ) VALUES (
                        PAYMENT_BATCH_DETAIL_SEQ.NEXTVAL, V_BATCH_ID, E.EMP_NO,
                        NVL(E.BANK_NO, 0), NVL(E.MASTER_BANK_NO, 0), V_AMT_R, A.ACC_ID,
                        E.OVERRIDE_PROVIDER_TYPE, E.OVERRIDE_ACC_ID,
                        A.IBAN, A.SNAP_ACC, A.OWNER_NAME, A.OWNER_ID_NO,
                        A.PROVIDER_NAME, A.BRANCH_NAME
                    );
                    V_REMAINING := V_REMAINING - V_AMT_R;
                  END IF;
                END LOOP;
              ELSE
                -- لا يوجد حساب "كامل الباقي" → ضيف الباقي على آخر سطر للموظف+override
                UPDATE GFC.PAYMENT_BATCH_DETAIL_TB
                   SET TOTAL_AMOUNT = TOTAL_AMOUNT + V_REMAINING
                 WHERE BATCH_DETAIL_ID = (
                    SELECT MAX(BATCH_DETAIL_ID) FROM GFC.PAYMENT_BATCH_DETAIL_TB
                     WHERE BATCH_ID = V_BATCH_ID AND EMP_NO = E.EMP_NO
                       AND NVL(OVERRIDE_PROVIDER_TYPE, -1) = NVL(E.OVERRIDE_PROVIDER_TYPE, -1)
                       AND NVL(OVERRIDE_ACC_ID, -1) = NVL(E.OVERRIDE_ACC_ID, -1)
                 );
                V_REMAINING := 0;
              END IF;
            END;
          END IF;

          V_TOTAL_AMT := V_TOTAL_AMT + E.TOTAL_AMOUNT;
          IF E.EMP_NO <> V_PREV_EMP THEN V_EMP_CNT := V_EMP_CNT + 1; V_PREV_EMP := E.EMP_NO; END IF;
        END;
      END IF;
    END LOOP;

    UPDATE GFC.PAYMENT_BATCH_TB SET TOTAL_AMOUNT = V_TOTAL_AMT, EMP_COUNT = V_EMP_CNT
     WHERE BATCH_ID = V_BATCH_ID;

    IF V_EMP_CNT = 0 THEN
      DELETE FROM GFC.PAYMENT_BATCH_TB WHERE BATCH_ID = V_BATCH_ID;
      -- لو كانت الطريقة الجديدة وكل الموظفين بدون حسابات: رسالة واضحة
      IF V_METHOD = 2 AND LENGTH(V_MISSING) > 0 THEN
        P_MSG_OUT := '0|0|1|لا يوجد موظفين بحسابات نشطة في النظام الجديد. الموظفون الناقصون: ' ||
                     SUBSTR(V_MISSING, 1, 500);
      ELSE
        P_MSG_OUT := '0|0|0';
      END IF;
    ELSE
      -- نتخطّى الـ details الخاصة بالموظفين الناقصين (لو كانت الطريقة جديدة)
      DECLARE V_LINKED_DETAILS VARCHAR2(4000) := NULL; BEGIN
        IF V_METHOD = 2 AND LENGTH(V_MISSING) > 0 THEN
          -- نزيل link records للموظفين الناقصين
          DELETE FROM GFC.PAYMENT_BATCH_LINK_TB
           WHERE BATCH_ID = V_BATCH_ID
             AND DETAIL_ID IN (
                SELECT D.DETAIL_ID FROM GFC.PAYMENT_REQ_DETAIL_TB D
                 WHERE INSTR(',' || V_MISSING || ',', ',' || TO_CHAR(D.EMP_NO) || ',') > 0
             );
        END IF;
      END;

      -- تحديث حالة الـ details المربوطة بالدفعة → PARTIAL_PAID حتى ما تظهر مرة ثانية بالاحتساب
      UPDATE GFC.PAYMENT_REQ_DETAIL_TB SET DETAIL_STATUS = C_REQ_STATUS_PARTIAL_PAID
       WHERE DETAIL_ID IN (SELECT DETAIL_ID FROM GFC.PAYMENT_BATCH_LINK_TB WHERE BATCH_ID = V_BATCH_ID)
         AND DETAIL_STATUS = C_REQ_STATUS_APPROVED;
      -- تحديث حالة الماستر
      FOR R IN (SELECT DISTINCT M.REQ_ID FROM GFC.PAYMENT_REQ_DETAIL_TB D
                JOIN GFC.PAYMENT_REQ_TB M ON M.REQ_ID = D.REQ_ID
                JOIN GFC.PAYMENT_BATCH_LINK_TB L ON L.DETAIL_ID = D.DETAIL_ID AND L.BATCH_ID = V_BATCH_ID)
      LOOP
        UPDATE GFC.PAYMENT_REQ_TB SET STATUS = DISBURSEMENT_PKG.CALC_MASTER_STATUS(R.REQ_ID) WHERE REQ_ID = R.REQ_ID;
      END LOOP;

      -- الـ output: BATCH_ID|EMP_CNT|0|MISSING_LIST_OPTIONAL
      IF LENGTH(V_MISSING) > 0 THEN
        P_MSG_OUT := TO_CHAR(V_BATCH_ID) || '|' || TO_CHAR(V_EMP_CNT) || '|0|تم استثناء موظفين بدون حسابات: ' || SUBSTR(V_MISSING, 1, 200);
      ELSE
        P_MSG_OUT := TO_CHAR(V_BATCH_ID) || '|' || TO_CHAR(V_EMP_CNT) || '|0';
      END IF;
    END IF;
  EXCEPTION WHEN OTHERS THEN
    P_MSG_OUT := '0|0|1|' || SUBSTR(SQLERRM, 1, 200);
  END;


  -- ============================================================
  -- BATCH PAY — تنفيذ الصرف (ترحيل لجدول المستحقات)
  -- ============================================================
  PROCEDURE PAYMENT_REQ_BATCH_PAY (
      P_BATCH_ID IN  NUMBER,
      P_MSG_OUT  OUT VARCHAR2
  ) IS
    V_USER NUMBER := USER_PKG.GET_USER_ID;
    V_OK NUMBER := 0; V_ERR NUMBER := 0;
    V_SERIAL NUMBER; V_OLD_ST NUMBER; V_NEW_ST NUMBER;
    V_FAIL_LIST VARCHAR2(4000) := '';
    V_BATCH_ST NUMBER; V_REQ_IDS VARCHAR2(4000);
  BEGIN
    BEGIN
      SELECT STATUS, REQ_IDS INTO V_BATCH_ST, V_REQ_IDS
        FROM GFC.PAYMENT_BATCH_TB WHERE BATCH_ID = P_BATCH_ID;
    EXCEPTION WHEN NO_DATA_FOUND THEN
      P_MSG_OUT := '0|0|1|الدفعة غير موجودة'; RETURN;
    END;
    IF V_BATCH_ST = 2 THEN P_MSG_OUT := '0|0|1|الدفعة مصروفة مسبقاً'; RETURN; END IF;
    IF V_BATCH_ST = 9 THEN P_MSG_OUT := '0|0|1|الدفعة ملغاة — لا يمكن صرفها'; RETURN; END IF;

    FOR D IN (
      SELECT D.DETAIL_ID, D.EMP_NO, D.REQ_AMOUNT, D.PAY_TYPE, D.NOTE,
             M.THE_MONTH, M.REQ_ID, M.REQ_TYPE
      FROM GFC.PAYMENT_REQ_DETAIL_TB D
      JOIN GFC.PAYMENT_REQ_TB M ON M.REQ_ID = D.REQ_ID
      JOIN GFC.PAYMENT_BATCH_LINK_TB L ON L.DETAIL_ID = D.DETAIL_ID AND L.BATCH_ID = P_BATCH_ID
      WHERE D.DETAIL_STATUS IN (C_REQ_STATUS_APPROVED, C_REQ_STATUS_PARTIAL_PAID)
      ORDER BY D.EMP_NO, D.DETAIL_ID
    ) LOOP
      BEGIN
        IF D.REQ_TYPE <> C_REQ_TYPE_BENEFITS THEN
          V_SERIAL := SALARY_DUES_TB_SEQ.NEXTVAL;
          INSERT INTO GFC.SALARY_DUES_TB (
              SERIAL, EMP_NO, THE_MONTH, PAY_TYPE, PAY, THE_DATE, NOTE, ENTRY_USER, STATUS, BATCH_ID
          ) VALUES (
              V_SERIAL, D.EMP_NO, D.THE_MONTH, D.PAY_TYPE, D.REQ_AMOUNT, SYSDATE,
              SUBSTR('REQ=' || D.REQ_ID || ' ' || NVL(D.NOTE,''), 1, 200),
              V_USER, 1, P_BATCH_ID
          );
        ELSE
          V_SERIAL := NULL;
        END IF;

        UPDATE GFC.PAYMENT_REQ_DETAIL_TB SET
            DETAIL_STATUS = C_REQ_STATUS_PAID, POST_SERIAL_DUES = V_SERIAL,
            DUES_AMOUNT = CASE WHEN D.REQ_TYPE = C_REQ_TYPE_BENEFITS THEN 0 ELSE REQ_AMOUNT END
         WHERE DETAIL_ID = D.DETAIL_ID;

        UPDATE GFC.PAYMENT_BATCH_LINK_TB SET DUES_SERIAL = V_SERIAL
         WHERE BATCH_ID = P_BATCH_ID AND DETAIL_ID = D.DETAIL_ID;

        V_OK := V_OK + 1;
      EXCEPTION WHEN OTHERS THEN
        V_ERR := V_ERR + 1;
        IF LENGTH(V_FAIL_LIST) < 3500 THEN
          V_FAIL_LIST := V_FAIL_LIST || D.EMP_NO || ': ' || SQLERRM || CHR(10);
        END IF;
      END;
    END LOOP;

    UPDATE GFC.PAYMENT_BATCH_TB SET STATUS = 2, PAY_USER = V_USER, PAY_DATE = SYSDATE
     WHERE BATCH_ID = P_BATCH_ID;

    FOR MR IN (
      SELECT DISTINCT D.REQ_ID
      FROM GFC.PAYMENT_REQ_DETAIL_TB D
      JOIN GFC.PAYMENT_BATCH_LINK_TB L ON L.DETAIL_ID = D.DETAIL_ID AND L.BATCH_ID = P_BATCH_ID
    ) LOOP
      BEGIN
        SELECT STATUS INTO V_OLD_ST FROM GFC.PAYMENT_REQ_TB WHERE REQ_ID = MR.REQ_ID;
        V_NEW_ST := DISBURSEMENT_PKG.CALC_MASTER_STATUS(MR.REQ_ID);
        UPDATE GFC.PAYMENT_REQ_TB SET
            STATUS = V_NEW_ST,
            PAY_USER = CASE WHEN V_NEW_ST IN (C_REQ_STATUS_PAID, C_REQ_STATUS_PARTIAL_PAID) THEN V_USER ELSE PAY_USER END,
            PAY_DATE = CASE WHEN V_NEW_ST IN (C_REQ_STATUS_PAID, C_REQ_STATUS_PARTIAL_PAID) THEN SYSDATE ELSE PAY_DATE END,
            UPDATE_USER = V_USER, UPDATE_DATE = SYSDATE
         WHERE REQ_ID = MR.REQ_ID;
        DISBURSEMENT_PKG.SYNC_MASTER_AMOUNT(MR.REQ_ID);
        DISBURSEMENT_PKG.LOG_ACTION(MR.REQ_ID, 'BATCH_PAY', V_OLD_ST, V_NEW_ST, 'BATCH=' || P_BATCH_ID);
      EXCEPTION WHEN OTHERS THEN NULL;
      END;
    END LOOP;

    IF V_OK = 0 AND V_ERR = 0 THEN P_MSG_OUT := '0|0|0';
    ELSE
      P_MSG_OUT := TO_CHAR(P_BATCH_ID) || '|' || TO_CHAR(V_OK) || '|' || TO_CHAR(V_ERR);
      IF V_ERR > 0 THEN P_MSG_OUT := P_MSG_OUT || '|' || SUBSTR(V_FAIL_LIST, 1, 3000); END IF;
    END IF;
  EXCEPTION WHEN OTHERS THEN
    P_MSG_OUT := '0|0|1|' || SUBSTR(SQLERRM, 1, 200);
  END;


  -- ============================================================
  -- BATCH CANCEL — فك اعتماد الاحتساب
  -- ============================================================
  PROCEDURE BATCH_CANCEL (
      P_BATCH_ID NUMBER, P_MSG_OUT OUT VARCHAR2
  ) IS
    V_ST NUMBER; V_NO VARCHAR2(20); V_NEW_ST NUMBER;
    V_USER NUMBER;
  BEGIN
    SELECT STATUS, BATCH_NO INTO V_ST, V_NO
      FROM GFC.PAYMENT_BATCH_TB WHERE BATCH_ID = P_BATCH_ID FOR UPDATE NOWAIT;
    IF V_ST = 2 THEN P_MSG_OUT := 'لا يمكن فك دفعة مصروفة — استخدم عكس الصرف'; RETURN; END IF;
    IF V_ST = 9 THEN P_MSG_OUT := 'الدفعة ملغاة مسبقاً'; RETURN; END IF;

    V_USER := USER_PKG.GET_USER_ID;

    -- إرجاع حالة الـ details إلى APPROVED
    UPDATE GFC.PAYMENT_REQ_DETAIL_TB SET DETAIL_STATUS = C_REQ_STATUS_APPROVED
     WHERE DETAIL_ID IN (SELECT DETAIL_ID FROM GFC.PAYMENT_BATCH_LINK_TB WHERE BATCH_ID = P_BATCH_ID)
       AND DETAIL_STATUS = C_REQ_STATUS_PARTIAL_PAID;
    -- إعادة حساب حالة الماستر
    FOR R IN (SELECT DISTINCT D.REQ_ID FROM GFC.PAYMENT_REQ_DETAIL_TB D
              JOIN GFC.PAYMENT_BATCH_LINK_TB L ON L.DETAIL_ID = D.DETAIL_ID AND L.BATCH_ID = P_BATCH_ID)
    LOOP
      V_NEW_ST := DISBURSEMENT_PKG.CALC_MASTER_STATUS(R.REQ_ID);
      UPDATE GFC.PAYMENT_REQ_TB SET STATUS = V_NEW_ST WHERE REQ_ID = R.REQ_ID;
    END LOOP;

    -- 🆕 لا نحذف — نُعلِّم الدفعة ملغاة (STATUS=9) ونحتفظ بسجلاتها كاملة
    -- للحفاظ على تسلسل أرقام الدفعات والـ audit trail.
    -- نحذف فقط الـ LINK (لتحرير DETAIL_IDs لاحتساب جديد) وتفاصيل الدفعة (التوزيع البنكي)
    -- ونُبقي ماستر الدفعة + بياناتها الأساسية.
    DELETE FROM GFC.PAYMENT_BATCH_LINK_TB   WHERE BATCH_ID = P_BATCH_ID;
    DELETE FROM GFC.PAYMENT_BATCH_DETAIL_TB WHERE BATCH_ID = P_BATCH_ID;

    UPDATE GFC.PAYMENT_BATCH_TB
       SET STATUS   = 9,
           PAY_USER = V_USER,
           PAY_DATE = SYSDATE
     WHERE BATCH_ID = P_BATCH_ID;

    P_MSG_OUT := '1';
  EXCEPTION
    WHEN NO_DATA_FOUND THEN P_MSG_OUT := 'الدفعة غير موجودة';
    WHEN OTHERS THEN P_MSG_OUT := SQLERRM;
  END;


  -- ============================================================
  -- BATCH REVERSE PAY — عكس صرف دفعة
  -- ============================================================
  PROCEDURE BATCH_REVERSE_PAY (
      P_BATCH_ID NUMBER, P_MSG_OUT OUT VARCHAR2
  ) IS
    V_ST NUMBER; V_NO VARCHAR2(20); V_USER NUMBER;
    V_REVERSED NUMBER := 0; V_ERRORS NUMBER := 0; V_NEW_ST NUMBER;
  BEGIN
    SELECT STATUS, BATCH_NO INTO V_ST, V_NO
      FROM GFC.PAYMENT_BATCH_TB WHERE BATCH_ID = P_BATCH_ID FOR UPDATE NOWAIT;
    IF V_ST <> 2 THEN P_MSG_OUT := 'العكس متاح فقط للدفعات المصروفة'; RETURN; END IF;

    V_USER := USER_PKG.GET_USER_ID;

    -- أرشفة ثم حذف سجلات المستحقات المرتبطة بالدفعة
    INSERT INTO GFC.SALARY_DUES_ARCHIVE_TB
      SELECT T.*, V_USER, SYSDATE FROM GFC.SALARY_DUES_TB T
       WHERE T.BATCH_ID = P_BATCH_ID;

    DELETE FROM GFC.SALARY_DUES_TB WHERE BATCH_ID = P_BATCH_ID;

    FOR L IN (SELECT L.DETAIL_ID, L.DUES_SERIAL, D.EMP_NO, D.REQ_ID
                FROM GFC.PAYMENT_BATCH_LINK_TB L
                JOIN GFC.PAYMENT_REQ_DETAIL_TB D ON D.DETAIL_ID = L.DETAIL_ID
               WHERE L.BATCH_ID = P_BATCH_ID)
    LOOP
      BEGIN
        UPDATE GFC.PAYMENT_REQ_DETAIL_TB
           SET DETAIL_STATUS = C_REQ_STATUS_APPROVED, POST_SERIAL_DUES = NULL
         WHERE DETAIL_ID = L.DETAIL_ID;

        UPDATE GFC.PAYMENT_BATCH_LINK_TB SET DUES_SERIAL = NULL
         WHERE BATCH_ID = P_BATCH_ID AND DETAIL_ID = L.DETAIL_ID;

        V_REVERSED := V_REVERSED + 1;
      EXCEPTION WHEN OTHERS THEN V_ERRORS := V_ERRORS + 1;
      END;
    END LOOP;

    FOR R IN (SELECT DISTINCT D.REQ_ID
                FROM GFC.PAYMENT_BATCH_LINK_TB L
                JOIN GFC.PAYMENT_REQ_DETAIL_TB D ON D.DETAIL_ID = L.DETAIL_ID
               WHERE L.BATCH_ID = P_BATCH_ID)
    LOOP
      V_NEW_ST := DISBURSEMENT_PKG.CALC_MASTER_STATUS(R.REQ_ID);
      UPDATE GFC.PAYMENT_REQ_TB SET STATUS = V_NEW_ST,
          PAY_USER = NULL, PAY_DATE = NULL,
          UPDATE_USER = V_USER, UPDATE_DATE = SYSDATE
       WHERE REQ_ID = R.REQ_ID;
      DISBURSEMENT_PKG.SYNC_MASTER_AMOUNT(R.REQ_ID);
      DISBURSEMENT_PKG.LOG_ACTION(R.REQ_ID, 'BATCH_REVERSE', 2, V_NEW_ST, 'BATCH=' || V_NO);
    END LOOP;

    UPDATE GFC.PAYMENT_BATCH_TB SET STATUS = 9, PAY_USER = V_USER, PAY_DATE = SYSDATE
     WHERE BATCH_ID = P_BATCH_ID;

    IF V_ERRORS > 0 THEN
      P_MSG_OUT := '1|تم عكس ' || V_REVERSED || ' سجل — فشل ' || V_ERRORS;
    ELSE
      P_MSG_OUT := '1|تم عكس صرف الدفعة ' || V_NO || ' (' || V_REVERSED || ' موظف)';
    END IF;
  EXCEPTION
    WHEN NO_DATA_FOUND THEN P_MSG_OUT := 'الدفعة غير موجودة';
    WHEN OTHERS THEN P_MSG_OUT := SQLERRM;
  END;


  -- ============================================================
  -- BATCH HISTORY LIST
  -- ============================================================
  PROCEDURE BATCH_HISTORY_LIST (
      P_REF_CUR_OUT OUT SYS_REFCURSOR, P_MSG_OUT OUT VARCHAR2
  ) IS
  BEGIN
    OPEN P_REF_CUR_OUT FOR
      SELECT B.BATCH_ID, B.BATCH_NO, B.BATCH_DATE, B.TOTAL_AMOUNT, B.EMP_COUNT,
             -- 🆕 الحركات البنكية = صفوف PAYMENT_BATCH_DETAIL_TB (تحويل لكل موظف × حساب، يحتوي split)
             -- بدلاً من PAYMENT_BATCH_LINK_TB (= عدد سجلات الطلبات الأصلية فقط)
             -- بهذا يتطابق الرقم مع شاشة batch_detail
             (SELECT COUNT(1) FROM GFC.PAYMENT_BATCH_DETAIL_TB BD WHERE BD.BATCH_ID = B.BATCH_ID) AS MOVEMENT_COUNT,
             B.STATUS, B.REQ_IDS,
             SETTING_PKG.CONSTANT_DETAILS_TB_GET_NAME(542, B.STATUS) AS STATUS_NAME,
             USER_PKG.GET_USER_NAME(B.ENTRY_USER) AS ENTRY_USER_NAME,
             B.ENTRY_DATE,
             USER_PKG.GET_USER_NAME(B.PAY_USER) AS PAY_USER_NAME,
             B.PAY_DATE
        FROM GFC.PAYMENT_BATCH_TB B
       ORDER BY B.BATCH_ID DESC;
    P_MSG_OUT := '1';
  EXCEPTION WHEN OTHERS THEN P_MSG_OUT := SQLERRM;
  END;


  -- ============================================================
  -- BATCH HISTORY GET — تفاصيل دفعة
  -- ============================================================
  PROCEDURE BATCH_HISTORY_GET (
      P_BATCH_ID NUMBER,
      P_REF_CUR_OUT OUT SYS_REFCURSOR, P_MSG_OUT OUT VARCHAR2
  ) IS
  BEGIN
    -- ملاحظة: في الطريقة الجديدة (DISBURSE_METHOD=2)، يكون للموظف الواحد أكثر من سطر
    -- في PAYMENT_BATCH_DETAIL_TB (واحد لكل حساب). نجمعهم هنا في صف واحد للموظف.
    -- ⚠️ مهم: لا نعمل JOIN على DATA.EMPLOYEES — لأن إذا فيه أكثر من record لنفس الموظف
    -- (متقاعد + فعّال مثلاً)، الـ JOIN يضرب الصفوف ويدبّل SUM(TOTAL_AMOUNT).
    -- نستخدم EMP_PKG functions بدلاً من JOIN.
    OPEN P_REF_CUR_OUT FOR
      SELECT MIN(BD.BATCH_DETAIL_ID)                                  AS BATCH_DETAIL_ID,
             BD.BATCH_ID,
             BD.EMP_NO,
             EMP_PKG.GET_EMP_NAME(BD.EMP_NO)                          AS EMP_NAME,
             EMP_PKG.GET_EMP_BRANCH_NAME(BD.EMP_NO)                   AS BRANCH_NAME,
             MIN(BD.BANK_NO)                                          AS BANK_NO,
             -- اسم البنك: LISTAGG ثم إزالة التكرار ثم استبدال الـ separator
             REPLACE(
                REGEXP_REPLACE(
                   LISTAGG(NVL(BD.SNAP_PROVIDER_NAME,
                               DISBURSEMENT_PKG.GET_BANK_NAME(BD.BANK_NO)), '|')
                      WITHIN GROUP (ORDER BY NVL(BD.SNAP_PROVIDER_NAME,
                                                 DISBURSEMENT_PKG.GET_BANK_NAME(BD.BANK_NO))),
                   '([^|]+)(\|\1)+', '\1'
                ),
                '|', ' / '
             )                                                                          AS BANK_NAME,
             DISBURSEMENT_PKG.GET_MASTER_BANK_NAME(BD.EMP_NO)         AS MASTER_BANK_NAME,
             SUM(BD.TOTAL_AMOUNT)                                     AS TOTAL_AMOUNT,
             MIN(BD.MASTER_BANK_NO)                                   AS MASTER_BANK_NO,
             EMP_PKG.GET_EMP_BRANCH(BD.EMP_NO)                        AS EMP_BRANCH_NO,
             -- counts للتنبيه
             NVL((SELECT COUNT(*) FROM GFC.PAYMENT_BENEFICIARIES_TB B
                   WHERE B.EMP_NO = BD.EMP_NO AND B.STATUS = 1), 0)              AS BENEF_COUNT,
             NVL((SELECT COUNT(*) FROM GFC.PAYMENT_ACCOUNTS_TB A
                   WHERE A.EMP_NO = BD.EMP_NO AND A.STATUS = 1 AND A.IS_ACTIVE = 1), 0)  AS ACTIVE_ACC_COUNT,
             NVL((SELECT COUNT(*) FROM GFC.PAYMENT_ACCOUNTS_TB A
                   WHERE A.EMP_NO = BD.EMP_NO AND A.STATUS = 1 AND A.IS_ACTIVE = 0), 0)  AS INACTIVE_ACC_COUNT,
             -- عدد سطور التوزيع للموظف (يساوي عدد الحسابات المستلمة في هذه الدفعة)
             COUNT(CASE WHEN BD.ACC_ID IS NOT NULL THEN 1 END)                   AS SPLIT_COUNT,
             (SELECT LISTAGG(M.REQ_NO, ', ') WITHIN GROUP (ORDER BY M.REQ_ID)
                FROM GFC.PAYMENT_BATCH_LINK_TB L
                JOIN GFC.PAYMENT_REQ_DETAIL_TB D ON D.DETAIL_ID = L.DETAIL_ID
                JOIN GFC.PAYMENT_REQ_TB M ON M.REQ_ID = D.REQ_ID
               WHERE L.BATCH_ID = BD.BATCH_ID AND D.EMP_NO = BD.EMP_NO)          AS REQ_NOS,
             -- مبالغ حسب نوع الطلب
             NVL((SELECT SUM(D2.REQ_AMOUNT) FROM GFC.PAYMENT_BATCH_LINK_TB L2
                JOIN GFC.PAYMENT_REQ_DETAIL_TB D2 ON D2.DETAIL_ID = L2.DETAIL_ID
                JOIN GFC.PAYMENT_REQ_TB M2 ON M2.REQ_ID = D2.REQ_ID
               WHERE L2.BATCH_ID = BD.BATCH_ID AND D2.EMP_NO = BD.EMP_NO AND M2.REQ_TYPE = 1), 0)        AS AMT_TYPE1,
             NVL((SELECT SUM(D2.REQ_AMOUNT) FROM GFC.PAYMENT_BATCH_LINK_TB L2
                JOIN GFC.PAYMENT_REQ_DETAIL_TB D2 ON D2.DETAIL_ID = L2.DETAIL_ID
                JOIN GFC.PAYMENT_REQ_TB M2 ON M2.REQ_ID = D2.REQ_ID
               WHERE L2.BATCH_ID = BD.BATCH_ID AND D2.EMP_NO = BD.EMP_NO AND M2.REQ_TYPE = 2), 0)        AS AMT_TYPE2,
             NVL((SELECT SUM(D2.REQ_AMOUNT) FROM GFC.PAYMENT_BATCH_LINK_TB L2
                JOIN GFC.PAYMENT_REQ_DETAIL_TB D2 ON D2.DETAIL_ID = L2.DETAIL_ID
                JOIN GFC.PAYMENT_REQ_TB M2 ON M2.REQ_ID = D2.REQ_ID
               WHERE L2.BATCH_ID = BD.BATCH_ID AND D2.EMP_NO = BD.EMP_NO AND M2.REQ_TYPE IN (3,4)), 0)   AS AMT_TYPE3,
             NVL((SELECT SUM(D2.REQ_AMOUNT) FROM GFC.PAYMENT_BATCH_LINK_TB L2
                JOIN GFC.PAYMENT_REQ_DETAIL_TB D2 ON D2.DETAIL_ID = L2.DETAIL_ID
                JOIN GFC.PAYMENT_REQ_TB M2 ON M2.REQ_ID = D2.REQ_ID
               WHERE L2.BATCH_ID = BD.BATCH_ID AND D2.EMP_NO = BD.EMP_NO AND M2.REQ_TYPE = 5), 0)        AS AMT_TYPE4
        FROM GFC.PAYMENT_BATCH_DETAIL_TB BD
       WHERE BD.BATCH_ID = P_BATCH_ID
       GROUP BY BD.BATCH_ID, BD.EMP_NO
       ORDER BY BD.EMP_NO;
    P_MSG_OUT := '1';
  EXCEPTION WHEN OTHERS THEN P_MSG_OUT := SQLERRM;
  END;


  -- ============================================================
  -- BATCH EMP ACCOUNTS GET — تفاصيل التوزيع لموظف داخل دفعة
  -- ============================================================
  PROCEDURE BATCH_EMP_ACCOUNTS_GET (
      P_BATCH_ID    NUMBER,
      P_EMP_NO      NUMBER,
      P_REF_CUR_OUT OUT SYS_REFCURSOR,
      P_MSG_OUT     OUT VARCHAR2
  ) IS
  BEGIN
    OPEN P_REF_CUR_OUT FOR
      SELECT A.ACC_ID,
             A.ACCOUNT_NO,
             A.IBAN,
             A.WALLET_NUMBER,
             NVL(A.ACCOUNT_NO, A.WALLET_NUMBER)         AS ACCOUNT_DISP,
             PP.PROVIDER_ID,
             PP.PROVIDER_NAME,
             PP.PROVIDER_TYPE,
             BR.BRANCH_NAME                             AS BANK_BRANCH_NAME,
             A.OWNER_NAME,
             A.OWNER_ID_NO,
             A.OWNER_PHONE,
             A.IS_DEFAULT,
             A.IS_ACTIVE,
             A.SPLIT_TYPE,
             A.SPLIT_VALUE,
             A.SPLIT_ORDER,
             A.BENEFICIARY_ID,
             B.NAME                                     AS BENEF_NAME,
             B.ID_NO                                    AS BENEF_ID_NO,
             B.REL_TYPE,
             CASE B.REL_TYPE
                WHEN 1 THEN 'زوجة'
                WHEN 2 THEN 'ابن'
                WHEN 3 THEN 'بنت'
                WHEN 4 THEN 'أب'
                WHEN 5 THEN 'أم'
                WHEN 9 THEN 'وريث'
                ELSE NULL
             END                                        AS REL_NAME,
             B.PCT_SHARE,
             -- المبلغ الفعلي المُوزع على هذا الحساب في هذه الدفعة
             -- (من PAYMENT_BATCH_DETAIL_TB حيث يُحفظ التوزيع وقت BATCH_CONFIRM)
             NVL((SELECT SUM(BD.TOTAL_AMOUNT)
                    FROM GFC.PAYMENT_BATCH_DETAIL_TB BD
                   WHERE BD.BATCH_ID = P_BATCH_ID
                     AND BD.EMP_NO   = P_EMP_NO
                     AND BD.ACC_ID   = A.ACC_ID), 0)      AS BATCH_AMOUNT
        FROM GFC.PAYMENT_ACCOUNTS_TB A
        LEFT JOIN GFC.PAYMENT_PROVIDERS_TB     PP ON PP.PROVIDER_ID = A.PROVIDER_ID
        LEFT JOIN GFC.PAYMENT_BANK_BRANCHES_TB BR ON BR.BRANCH_ID  = A.BRANCH_ID
        LEFT JOIN GFC.PAYMENT_BENEFICIARIES_TB B  ON B.BENEFICIARY_ID = A.BENEFICIARY_ID
       WHERE A.EMP_NO = P_EMP_NO
         AND A.STATUS = 1
       ORDER BY A.IS_ACTIVE DESC, A.SPLIT_ORDER, A.ACC_ID;
    P_MSG_OUT := '1';
  EXCEPTION WHEN OTHERS THEN P_MSG_OUT := SQLERRM;
  END;


  -- ============================================================
  -- BATCH COMPUTE PREVIEW — معاينة التوزيع التفصيلي قبل الاعتماد
  -- يرجع لكل موظف+حساب: ALLOC_AMOUNT + بيانات الحساب + STATUS
  -- بدون أي كتابة في DB — للعرض فقط (read-only)
  -- ============================================================
  PROCEDURE BATCH_COMPUTE_PREVIEW (
      P_REQ_IDS            IN  VARCHAR2,
      P_EXCLUDE_DETAIL_IDS IN  VARCHAR2 DEFAULT NULL,
      P_REF_CUR_OUT        OUT SYS_REFCURSOR,
      P_MSG_OUT            OUT VARCHAR2
  ) IS
  BEGIN
    OPEN P_REF_CUR_OUT FOR
      WITH EMP_TOTAL AS (
        SELECT D.EMP_NO,
               SUM(D.REQ_AMOUNT) AS TOTAL_AMOUNT,
               COUNT(*)          AS REQ_COUNT,
               LISTAGG(TO_CHAR(D.DETAIL_ID), ',') WITHIN GROUP (ORDER BY D.DETAIL_ID) AS DETAIL_IDS
          FROM GFC.PAYMENT_REQ_DETAIL_TB D
          JOIN GFC.PAYMENT_REQ_TB        M ON M.REQ_ID = D.REQ_ID
         WHERE INSTR(',' || P_REQ_IDS || ',', ',' || TO_CHAR(M.REQ_ID) || ',') > 0
           AND D.DETAIL_STATUS = C_REQ_STATUS_APPROVED
           AND (P_EXCLUDE_DETAIL_IDS IS NULL
                OR INSTR(',' || P_EXCLUDE_DETAIL_IDS || ',', ',' || TO_CHAR(D.DETAIL_ID) || ',') = 0)
         GROUP BY D.EMP_NO
      ),
      ACC_AGG AS (
        SELECT EMP_NO,
               COUNT(*)                                                          AS ACC_CNT,
               NVL(SUM(CASE WHEN SPLIT_TYPE=2 THEN SPLIT_VALUE ELSE 0 END), 0)   AS FIXED_SUM,
               NVL(SUM(CASE WHEN SPLIT_TYPE=1 THEN SPLIT_VALUE ELSE 0 END), 0)   AS PCT_SUM,
               SUM(CASE WHEN SPLIT_TYPE=3 THEN 1 ELSE 0 END)                    AS REST_CNT,
               SUM(CASE WHEN BENEFICIARY_ID IS NOT NULL THEN 1 ELSE 0 END)       AS BENEF_LINKED
          FROM GFC.PAYMENT_ACCOUNTS_TB
         WHERE STATUS = 1 AND IS_ACTIVE = 1
         GROUP BY EMP_NO
      ),
      BENEF_AGG AS (
        SELECT EMP_NO, COUNT(*) AS BENEF_TOTAL
          FROM GFC.PAYMENT_BENEFICIARIES_TB
         WHERE STATUS = 1
         GROUP BY EMP_NO
      ),
      -- نُفلتر duplicates في DATA.EMPLOYEES (لو وُجدت) — صف واحد لكل NO
      EMP_DEDUP AS (
        SELECT NO, NAME, IS_ACTIVE,
               ROW_NUMBER() OVER (PARTITION BY NO ORDER BY ROWID) AS RN
          FROM DATA.EMPLOYEES
      ),
      EMP_BASE AS (
        SELECT ET.EMP_NO,
               E.NAME                                  AS EMP_NAME,
               EMP_PKG.GET_EMP_BRANCH_NAME(ET.EMP_NO)  AS BRANCH_NAME,
               NVL(E.IS_ACTIVE, 0)                     AS EMP_IS_ACTIVE,
               ET.TOTAL_AMOUNT,
               ET.REQ_COUNT,
               ET.DETAIL_IDS,
               NVL(AA.ACC_CNT,    0)                   AS ACC_CNT,
               NVL(AA.FIXED_SUM,  0)                   AS FIXED_SUM,
               NVL(AA.PCT_SUM,    0)                   AS PCT_SUM,
               NVL(AA.REST_CNT,   0)                   AS REST_CNT,
               NVL(AA.BENEF_LINKED, 0)                 AS BENEF_LINKED,
               NVL(BA.BENEF_TOTAL,  0)                 AS BENEF_TOTAL,
               -- المبلغ المتوقع توزيعه (ثابت + نسبة مئوية)
               NVL(AA.FIXED_SUM, 0)
                 + ROUND(ET.TOTAL_AMOUNT * NVL(AA.PCT_SUM, 0) / 100, 2) AS PRECOMMIT_DIST,
               -- حالة التوزيع
               CASE
                 WHEN NVL(AA.ACC_CNT, 0) = 0                                          THEN 'ERR'
                 WHEN AA.FIXED_SUM > ET.TOTAL_AMOUNT                                  THEN 'ERR'
                 WHEN AA.PCT_SUM   > 100                                              THEN 'ERR'
                 WHEN (NVL(AA.FIXED_SUM,0) + ROUND(ET.TOTAL_AMOUNT * NVL(AA.PCT_SUM,0)/100, 2)) > ET.TOTAL_AMOUNT
                                                                                      THEN 'ERR'
                 WHEN NVL(AA.REST_CNT,0) = 0
                      AND (NVL(AA.FIXED_SUM,0) + ROUND(ET.TOTAL_AMOUNT * NVL(AA.PCT_SUM,0)/100, 2)) < ET.TOTAL_AMOUNT
                                                                                      THEN 'WARN'
                 WHEN NVL(BA.BENEF_TOTAL,0) > 0 AND NVL(AA.BENEF_LINKED,0) < BA.BENEF_TOTAL
                                                                                      THEN 'WARN'
                 ELSE 'OK'
               END AS EMP_STATUS,
               CASE
                 WHEN NVL(AA.ACC_CNT, 0) = 0
                   THEN 'لا توجد حسابات نشطة للموظف'
                 WHEN AA.FIXED_SUM > ET.TOTAL_AMOUNT
                   THEN 'مجموع المبالغ الثابتة أكبر من المستحق'
                 WHEN AA.PCT_SUM > 100
                   THEN 'مجموع النسب أكبر من 100%'
                 WHEN (NVL(AA.FIXED_SUM,0) + ROUND(ET.TOTAL_AMOUNT * NVL(AA.PCT_SUM,0)/100, 2)) > ET.TOTAL_AMOUNT
                   THEN 'الإجمالي المتوقع أكبر من المستحق'
                 WHEN NVL(AA.REST_CNT,0) = 0
                      AND (NVL(AA.FIXED_SUM,0) + ROUND(ET.TOTAL_AMOUNT * NVL(AA.PCT_SUM,0)/100, 2)) < ET.TOTAL_AMOUNT
                   THEN 'متبقي ' ||
                        TO_CHAR(ET.TOTAL_AMOUNT - (NVL(AA.FIXED_SUM,0) + ROUND(ET.TOTAL_AMOUNT * NVL(AA.PCT_SUM,0)/100, 2)),
                                'FM999G999G990D00') ||
                        ' دون حساب "كامل الباقي" (سيُضاف لآخر سطر)'
                 WHEN NVL(BA.BENEF_TOTAL,0) > 0 AND NVL(AA.BENEF_LINKED,0) < BA.BENEF_TOTAL
                   THEN (BA.BENEF_TOTAL - NVL(AA.BENEF_LINKED, 0)) || ' مستفيد غير مربوط بحساب'
                 ELSE NULL
               END AS ISSUE
          FROM EMP_TOTAL ET
          LEFT JOIN EMP_DEDUP E ON E.NO = ET.EMP_NO AND E.RN = 1
          LEFT JOIN ACC_AGG   AA ON AA.EMP_NO = ET.EMP_NO
          LEFT JOIN BENEF_AGG BA ON BA.EMP_NO = ET.EMP_NO
      ),
      -- التوزيع التفصيلي: سطر لكل (موظف، حساب)
      -- للموظفين بدون حسابات: سطر واحد (ACC_ID=NULL) ليكون مرئياً للمحاسب
      ACC_SPLIT AS (
        SELECT
            EB.EMP_NO,
            EB.EMP_NAME,
            EB.BRANCH_NAME,
            EB.EMP_IS_ACTIVE,
            EB.TOTAL_AMOUNT,
            EB.REQ_COUNT,
            EB.DETAIL_IDS,
            EB.ACC_CNT,
            EB.EMP_STATUS,
            EB.ISSUE,
            PA.ACC_ID,
            PA.SPLIT_TYPE,
            PA.SPLIT_VALUE,
            PA.SPLIT_ORDER,
            PA.IS_DEFAULT,
            PA.IBAN,
            NVL(PA.ACCOUNT_NO, PA.WALLET_NUMBER)        AS ACCOUNT_NO,
            PA.WALLET_NUMBER,
            PA.OWNER_NAME,
            PA.OWNER_ID_NO,
            PA.OWNER_PHONE,
            PA.BENEFICIARY_ID,
            BNF.NAME                                    AS BENEF_NAME,
            BNF.REL_TYPE                                AS BENEF_REL_TYPE,
            CASE BNF.REL_TYPE
              WHEN 1 THEN 'زوجة' WHEN 2 THEN 'ابن'  WHEN 3 THEN 'بنت'
              WHEN 4 THEN 'أب'   WHEN 5 THEN 'أم'   WHEN 9 THEN 'وريث آخر'
              ELSE NULL
            END                                         AS BENEF_REL_NAME,
            PP.PROVIDER_ID,
            PP.PROVIDER_NAME,
            NVL(PP.PROVIDER_TYPE, 1)                    AS PROVIDER_TYPE,
            BR.BRANCH_NAME                              AS PROV_BRANCH_NAME,
            BR.LEGACY_BANK_NO                           AS PROV_LEGACY_NO,
            -- حساب المبلغ المخصّص لهذا الحساب
            CASE
              WHEN PA.ACC_ID IS NULL THEN 0
              WHEN PA.SPLIT_TYPE = 2
                THEN LEAST(NVL(PA.SPLIT_VALUE, 0), EB.TOTAL_AMOUNT)
              WHEN PA.SPLIT_TYPE = 1
                THEN ROUND(EB.TOTAL_AMOUNT * NVL(PA.SPLIT_VALUE, 0) / 100, 2)
              WHEN PA.SPLIT_TYPE = 3 AND EB.REST_CNT > 0
                THEN ROUND(GREATEST(EB.TOTAL_AMOUNT - EB.PRECOMMIT_DIST, 0) / EB.REST_CNT, 2)
              ELSE 0
            END                                         AS ALLOC_AMOUNT,
            -- ترتيب العرض: ثابت ثم نسبة ثم باقي ثم باقي الـ orders
            CASE PA.SPLIT_TYPE WHEN 2 THEN 1 WHEN 1 THEN 2 WHEN 3 THEN 3 ELSE 9 END AS SORT_TYPE
          FROM EMP_BASE EB
          LEFT JOIN GFC.PAYMENT_ACCOUNTS_TB PA
                 ON PA.EMP_NO = EB.EMP_NO AND PA.STATUS = 1 AND PA.IS_ACTIVE = 1
          LEFT JOIN GFC.PAYMENT_BENEFICIARIES_TB BNF
                 ON BNF.BENEFICIARY_ID = PA.BENEFICIARY_ID
          LEFT JOIN GFC.PAYMENT_PROVIDERS_TB PP
                 ON PP.PROVIDER_ID = PA.PROVIDER_ID
          LEFT JOIN GFC.PAYMENT_BANK_BRANCHES_TB BR
                 ON BR.BRANCH_ID = PA.BRANCH_ID
      )
      SELECT EMP_NO, EMP_NAME, BRANCH_NAME, EMP_IS_ACTIVE,
             TOTAL_AMOUNT, REQ_COUNT, DETAIL_IDS, ACC_CNT,
             EMP_STATUS, ISSUE,
             ACC_ID, SPLIT_TYPE, SPLIT_VALUE, SPLIT_ORDER, IS_DEFAULT,
             IBAN, ACCOUNT_NO, WALLET_NUMBER, OWNER_NAME, OWNER_ID_NO, OWNER_PHONE,
             BENEFICIARY_ID, BENEF_NAME, BENEF_REL_TYPE, BENEF_REL_NAME,
             PROVIDER_ID, PROVIDER_NAME, PROVIDER_TYPE, PROV_BRANCH_NAME, PROV_LEGACY_NO,
             ALLOC_AMOUNT
        FROM ACC_SPLIT
       ORDER BY
         CASE EMP_STATUS WHEN 'ERR' THEN 1 WHEN 'WARN' THEN 2 ELSE 3 END,
         EMP_NO,
         SORT_TYPE,
         SPLIT_ORDER NULLS LAST,
         ACC_ID NULLS LAST;
    P_MSG_OUT := '1';
  EXCEPTION WHEN OTHERS THEN P_MSG_OUT := SQLERRM;
  END;


  -- ============================================================
  -- BATCH PREVIEW — معاينة احتساب قبل الاعتماد (legacy: per-emp فقط)
  -- يرجع لكل موظف: STATUS (OK/WARN/ERR) + ISSUE
  -- المحاسب يقدر يرى المشاكل ويصلحها قبل المتابعة
  -- (للتوافق مع modal الاعتماد القديم — لا تُلغى)
  -- ============================================================
  PROCEDURE BATCH_PREVIEW (
      P_REQ_IDS            IN  VARCHAR2,
      P_EXCLUDE_DETAIL_IDS IN  VARCHAR2 DEFAULT NULL,
      P_REF_CUR_OUT        OUT SYS_REFCURSOR,
      P_MSG_OUT            OUT VARCHAR2
  ) IS
  BEGIN
    OPEN P_REF_CUR_OUT FOR
      WITH EMP_AMOUNTS AS (
        -- المبلغ الإجمالي لكل موظف من الطلبات المختارة
        SELECT D.EMP_NO,
               E.NAME              AS EMP_NAME,
               EMP_PKG.GET_EMP_BRANCH_NAME(D.EMP_NO) AS BRANCH_NAME,
               NVL(E.IS_ACTIVE, 0) AS EMP_IS_ACTIVE,
               SUM(D.REQ_AMOUNT)   AS TOTAL_AMOUNT,
               COUNT(*)            AS REQ_COUNT
          FROM GFC.PAYMENT_REQ_DETAIL_TB D
          JOIN GFC.PAYMENT_REQ_TB        M ON M.REQ_ID = D.REQ_ID
          LEFT JOIN DATA.EMPLOYEES        E ON E.NO = D.EMP_NO
         WHERE INSTR(',' || P_REQ_IDS || ',', ',' || TO_CHAR(M.REQ_ID) || ',') > 0
           AND D.DETAIL_STATUS = C_REQ_STATUS_APPROVED
           AND (P_EXCLUDE_DETAIL_IDS IS NULL
                OR INSTR(',' || P_EXCLUDE_DETAIL_IDS || ',', ',' || TO_CHAR(D.DETAIL_ID) || ',') = 0)
         GROUP BY D.EMP_NO, E.NAME, E.IS_ACTIVE
      ),
      ACC_AGG AS (
        -- aggregates لحسابات كل موظف
        SELECT EMP_NO,
               COUNT(*)                                                              AS ACC_COUNT,
               NVL(SUM(CASE WHEN SPLIT_TYPE = 2 THEN SPLIT_VALUE ELSE 0 END), 0)     AS FIXED_SUM,
               NVL(SUM(CASE WHEN SPLIT_TYPE = 1 THEN SPLIT_VALUE ELSE 0 END), 0)     AS PCT_SUM,
               SUM(CASE WHEN SPLIT_TYPE = 3 THEN 1 ELSE 0 END)                      AS REM_COUNT,
               SUM(CASE WHEN BENEFICIARY_ID IS NOT NULL THEN 1 ELSE 0 END)           AS BENEF_LINKED
          FROM GFC.PAYMENT_ACCOUNTS_TB
         WHERE STATUS = 1 AND IS_ACTIVE = 1
         GROUP BY EMP_NO
      ),
      BENEF_AGG AS (
        SELECT EMP_NO, COUNT(*) AS BENEF_TOTAL
          FROM GFC.PAYMENT_BENEFICIARIES_TB
         WHERE STATUS = 1
         GROUP BY EMP_NO
      ),
      COMBINED AS (
        SELECT EA.EMP_NO,
               EA.EMP_NAME,
               EA.BRANCH_NAME,
               EA.EMP_IS_ACTIVE,
               EA.TOTAL_AMOUNT,
               EA.REQ_COUNT,
               NVL(AA.ACC_COUNT,    0) AS ACC_COUNT,
               NVL(AA.FIXED_SUM,    0) AS FIXED_SUM,
               NVL(AA.PCT_SUM,      0) AS PCT_SUM,
               NVL(AA.REM_COUNT,    0) AS REM_COUNT,
               NVL(AA.BENEF_LINKED, 0) AS BENEF_LINKED,
               NVL(BA.BENEF_TOTAL,  0) AS BENEF_TOTAL,
               -- المبلغ المتوقع توزيعه: ثابت + (نسبة × المستحق)
               NVL(AA.FIXED_SUM, 0)
                   + ROUND(EA.TOTAL_AMOUNT * NVL(AA.PCT_SUM, 0) / 100, 2) AS EXPECTED_DIST
          FROM EMP_AMOUNTS EA
          LEFT JOIN ACC_AGG   AA ON AA.EMP_NO = EA.EMP_NO
          LEFT JOIN BENEF_AGG BA ON BA.EMP_NO = EA.EMP_NO
      )
      SELECT EMP_NO, EMP_NAME, BRANCH_NAME, EMP_IS_ACTIVE,
             TOTAL_AMOUNT, REQ_COUNT,
             ACC_COUNT, FIXED_SUM, PCT_SUM, REM_COUNT,
             BENEF_LINKED, BENEF_TOTAL, EXPECTED_DIST,
             -- حالة التوزيع
             CASE
               WHEN ACC_COUNT = 0                                               THEN 'ERR'
               WHEN FIXED_SUM > TOTAL_AMOUNT                                    THEN 'ERR'
               WHEN PCT_SUM > 100                                               THEN 'ERR'
               WHEN EXPECTED_DIST > TOTAL_AMOUNT                                THEN 'ERR'
               WHEN REM_COUNT = 0 AND EXPECTED_DIST < TOTAL_AMOUNT              THEN 'WARN'
               WHEN BENEF_TOTAL > 0 AND BENEF_LINKED < BENEF_TOTAL              THEN 'WARN'
               ELSE 'OK'
             END AS STATUS,
             -- وصف المشكلة (إن وُجدت)
             CASE
               WHEN ACC_COUNT = 0
                 THEN 'لا توجد حسابات نشطة للموظف'
               WHEN FIXED_SUM > TOTAL_AMOUNT
                 THEN 'مجموع المبالغ الثابتة (' || TO_CHAR(FIXED_SUM, 'FM999G999G990D00') ||
                      ') أكبر من المستحق (' || TO_CHAR(TOTAL_AMOUNT, 'FM999G999G990D00') || ')'
               WHEN PCT_SUM > 100
                 THEN 'مجموع النسب المئوية (' || PCT_SUM || '%) أكبر من 100%'
               WHEN EXPECTED_DIST > TOTAL_AMOUNT
                 THEN 'الإجمالي المتوقع (' || TO_CHAR(EXPECTED_DIST, 'FM999G999G990D00') ||
                      ') أكبر من المستحق'
               WHEN REM_COUNT = 0 AND EXPECTED_DIST < TOTAL_AMOUNT
                 THEN 'متبقي ' || TO_CHAR(TOTAL_AMOUNT - EXPECTED_DIST, 'FM999G999G990D00') ||
                      ' دون حساب "كامل الباقي" — سيُضاف لآخر سطر'
               WHEN BENEF_TOTAL > 0 AND BENEF_LINKED < BENEF_TOTAL
                 THEN (BENEF_TOTAL - BENEF_LINKED) || ' مستفيد غير مربوط بحساب'
               ELSE NULL
             END AS ISSUE
        FROM COMBINED
       ORDER BY
         CASE
           WHEN ACC_COUNT = 0 OR FIXED_SUM > TOTAL_AMOUNT OR PCT_SUM > 100
                OR EXPECTED_DIST > TOTAL_AMOUNT THEN 1
           WHEN (REM_COUNT = 0 AND EXPECTED_DIST < TOTAL_AMOUNT)
                OR (BENEF_TOTAL > 0 AND BENEF_LINKED < BENEF_TOTAL) THEN 2
           ELSE 3
         END,
         EMP_NO;
    P_MSG_OUT := '1';
  EXCEPTION WHEN OTHERS THEN P_MSG_OUT := SQLERRM;
  END;


  -- ============================================================
  -- EMP PENDING BATCHES — الدفعات المحتسبة (غير منفّذة) لموظف
  -- يستخدم في شاشة الموظف لتنبيه المحاسب لو فيه دفعة محتسبة
  -- ============================================================
  PROCEDURE EMP_PENDING_BATCHES (
      P_EMP_NO       NUMBER,
      P_REF_CUR_OUT  OUT SYS_REFCURSOR,
      P_MSG_OUT      OUT VARCHAR2
  ) IS
  BEGIN
    OPEN P_REF_CUR_OUT FOR
      SELECT DISTINCT B.BATCH_ID, B.BATCH_NO, B.BATCH_DATE, B.STATUS,
             SUM(BD.TOTAL_AMOUNT) AS EMP_AMOUNT,
             NVL(B.DISBURSE_METHOD, 1) AS DISBURSE_METHOD
        FROM GFC.PAYMENT_BATCH_TB B
        JOIN GFC.PAYMENT_BATCH_DETAIL_TB BD ON BD.BATCH_ID = B.BATCH_ID
       WHERE BD.EMP_NO = P_EMP_NO
         AND B.STATUS  = 0   -- محتسبة، غير منفّذة
       GROUP BY B.BATCH_ID, B.BATCH_NO, B.BATCH_DATE, B.STATUS, B.DISBURSE_METHOD
       ORDER BY B.BATCH_ID DESC;
    P_MSG_OUT := '1';
  EXCEPTION WHEN OTHERS THEN P_MSG_OUT := SQLERRM;
  END;


  -- ============================================================
  -- BATCH BANK SUMMARY GET — ملخص الدفعة حسب البنك/المحفظة الفعلية
  -- ============================================================
  PROCEDURE BATCH_BANK_SUMMARY_GET (
      P_BATCH_ID    NUMBER,
      P_REF_CUR_OUT OUT SYS_REFCURSOR,
      P_MSG_OUT     OUT VARCHAR2
  ) IS
  BEGIN
    OPEN P_REF_CUR_OUT FOR
      WITH BD_FLAT AS (
        -- صف لكل حركة (handle method=1 أيضاً عبر fallback للـ legacy bank name)
        SELECT BD.BATCH_ID,
               BD.EMP_NO,
               BD.ACC_ID,
               BD.TOTAL_AMOUNT,
               -- اسم البنك الفعلي: snapshot أولاً، ثم legacy
               NVL(BD.SNAP_PROVIDER_NAME,
                   DISBURSEMENT_PKG.GET_MASTER_BANK_NAME(BD.EMP_NO))    AS PROVIDER_NAME,
               -- اسم الفرع — الأولوية للـ GFC schema (المصدر الأصلي):
               --   1) snapshot
               --   2) method=2: بناء من PROVIDER_NAME + BRANCH_NAME (موحّد)
               --   3) Legacy GET_BANK_NAME(BD.BANK_NO) — fallback
               NVL(BD.SNAP_BRANCH_NAME,
                   CASE
                     WHEN NVL(B.DISBURSE_METHOD, 1) = 2 AND BD.ACC_ID IS NOT NULL AND BR.BRANCH_NAME IS NOT NULL THEN
                       PP.PROVIDER_NAME || ' - فرع ' ||
                       TRIM(REGEXP_REPLACE(BR.BRANCH_NAME,
                           '^(' || REGEXP_REPLACE(PP.PROVIDER_NAME, '^(بنك|البنك)\s+', '') || '|الاسلامي|فلسطين|الاستثمار|العربي|القدس)\s+', ''))
                     ELSE DISBURSEMENT_PKG.GET_BANK_NAME(BD.BANK_NO)
                   END)                                                AS BRANCH_NAME,
               -- نوع المزود (للتمييز بين بنك ومحفظة)
               NVL(PP.PROVIDER_TYPE, 1)                                  AS PROVIDER_TYPE,
               PP.LEGACY_BANK_NO,
               BD.MASTER_BANK_NO
          FROM GFC.PAYMENT_BATCH_DETAIL_TB BD
          JOIN GFC.PAYMENT_BATCH_TB            B  ON B.BATCH_ID    = BD.BATCH_ID
          LEFT JOIN GFC.PAYMENT_ACCOUNTS_TB    PA ON PA.ACC_ID     = BD.ACC_ID
          LEFT JOIN GFC.PAYMENT_PROVIDERS_TB   PP ON PP.PROVIDER_ID = PA.PROVIDER_ID
          LEFT JOIN GFC.PAYMENT_BANK_BRANCHES_TB BR ON BR.BRANCH_ID = PA.BRANCH_ID
         WHERE BD.BATCH_ID = P_BATCH_ID
      )
      SELECT PROVIDER_NAME                          AS BANK_NAME,
             PROVIDER_TYPE,
             COALESCE(MAX(LEGACY_BANK_NO),
                      MAX(MASTER_BANK_NO), 0)       AS MASTER_BANK_NO,
             COUNT(DISTINCT EMP_NO)                 AS EMP_COUNT,
             COUNT(*)                               AS TRANSACTION_COUNT,
             -- المحافظ ما إلها فروع → BRANCH_NAME للمحافظ بيرجع للبنك القديم في HR (غير مفيد)
             CASE WHEN PROVIDER_TYPE = 2 THEN 1
                  ELSE COUNT(DISTINCT BRANCH_NAME) END AS BRANCH_COUNT,
             SUM(TOTAL_AMOUNT)                      AS TOTAL_AMOUNT
        FROM BD_FLAT
       GROUP BY PROVIDER_NAME, PROVIDER_TYPE
       ORDER BY PROVIDER_TYPE, PROVIDER_NAME;
    P_MSG_OUT := '1';
  EXCEPTION WHEN OTHERS THEN P_MSG_OUT := SQLERRM;
  END;


  -- ============================================================
  -- BATCH REFRESH SPLIT — إعادة احتساب التوزيع لدفعة محتسبة
  -- ============================================================
  -- مفيد لو المحاسب عدّل حسابات الموظف بعد الاعتماد دون تغيير المبالغ
  -- (مثلاً تعديل IBAN، تغيير صاحب الحساب، إضافة حساب جديد)
  -- ⚠️ يعمل فقط لو الدفعة بحالة محتسبة (STATUS=0) وليست منفّذة
  -- ============================================================
  PROCEDURE BATCH_REFRESH_SPLIT (
      P_BATCH_ID    NUMBER,
      P_CHANGED_OUT OUT NUMBER,
      P_MSG_OUT     OUT VARCHAR2
  ) IS
    V_STATUS  NUMBER;
    V_METHOD  NUMBER;
    V_CHANGED NUMBER := 0;
    V_TOTAL   NUMBER := 0;
    V_MISSING VARCHAR2(2000) := '';
  BEGIN
    -- تحقق من الحالة والطريقة
    SELECT STATUS, NVL(DISBURSE_METHOD, 1)
      INTO V_STATUS, V_METHOD
      FROM GFC.PAYMENT_BATCH_TB WHERE BATCH_ID = P_BATCH_ID;

    IF V_STATUS <> 0 THEN
      P_MSG_OUT := 'لا يمكن إعادة الاحتساب — الدفعة ليست بحالة "محتسبة" (الحالة الحالية: ' || V_STATUS || ')';
      P_CHANGED_OUT := 0;
      RETURN;
    END IF;

    IF V_METHOD <> 2 THEN
      P_MSG_OUT := 'إعادة الاحتساب متاحة فقط للطريقة الجديدة (DISBURSE_METHOD=2)';
      P_CHANGED_OUT := 0;
      RETURN;
    END IF;

    -- جمّع المبالغ لكل (موظف + override_signature) من الـ batch_detail الحالي
    -- (الـ override يبقى محفوظ في BATCH_DETAIL_TB ونحترمه عند الـ refresh)
    FOR E IN (
      SELECT EMP_NO,
             OVERRIDE_PROVIDER_TYPE,
             OVERRIDE_ACC_ID,
             SUM(TOTAL_AMOUNT) AS TOTAL_AMOUNT,
             MIN(BANK_NO)      AS BANK_NO,
             MIN(MASTER_BANK_NO) AS MASTER_BANK_NO
        FROM GFC.PAYMENT_BATCH_DETAIL_TB
       WHERE BATCH_ID = P_BATCH_ID
       GROUP BY EMP_NO, OVERRIDE_PROVIDER_TYPE, OVERRIDE_ACC_ID
    ) LOOP
      -- احذف توزيع الموظف+override الحالي
      DELETE FROM GFC.PAYMENT_BATCH_DETAIL_TB
       WHERE BATCH_ID = P_BATCH_ID AND EMP_NO = E.EMP_NO
         AND NVL(OVERRIDE_PROVIDER_TYPE, -1) = NVL(E.OVERRIDE_PROVIDER_TYPE, -1)
         AND NVL(OVERRIDE_ACC_ID, -1)        = NVL(E.OVERRIDE_ACC_ID, -1);

      -- أعد احتساب التوزيع من PAYMENT_ACCOUNTS_TB (نفس logic BATCH_CONFIRM مع override)
      DECLARE
        V_REMAINING NUMBER := E.TOTAL_AMOUNT;
        V_AMOUNT    NUMBER;
        V_ACC_COUNT NUMBER := 0;
      BEGIN
        -- 🎯 حالة OVERRIDE_ACC_ID: 100% للحساب المحدد
        IF E.OVERRIDE_ACC_ID IS NOT NULL THEN
          DECLARE V_FOUND NUMBER := 0; BEGIN
            FOR A IN (SELECT PA.ACC_ID, PA.IBAN,
                             NVL(PA.ACCOUNT_NO, PA.WALLET_NUMBER) AS ACC_NO,
                             PA.OWNER_NAME, PA.OWNER_ID_NO,
                             PP.PROVIDER_NAME, BR.BRANCH_NAME
                        FROM GFC.PAYMENT_ACCOUNTS_TB PA
                        LEFT JOIN GFC.PAYMENT_PROVIDERS_TB     PP ON PP.PROVIDER_ID = PA.PROVIDER_ID
                        LEFT JOIN GFC.PAYMENT_BANK_BRANCHES_TB BR ON BR.BRANCH_ID  = PA.BRANCH_ID
                       WHERE PA.ACC_ID = E.OVERRIDE_ACC_ID
                         AND PA.EMP_NO = E.EMP_NO AND PA.STATUS = 1)
            LOOP
              INSERT INTO GFC.PAYMENT_BATCH_DETAIL_TB (
                  BATCH_DETAIL_ID, BATCH_ID, EMP_NO, BANK_NO, MASTER_BANK_NO, TOTAL_AMOUNT, ACC_ID,
                  OVERRIDE_PROVIDER_TYPE, OVERRIDE_ACC_ID,
                  SNAP_IBAN, SNAP_ACCOUNT_NO, SNAP_OWNER_NAME, SNAP_OWNER_ID_NO,
                  SNAP_PROVIDER_NAME, SNAP_BRANCH_NAME
              ) VALUES (
                  PAYMENT_BATCH_DETAIL_SEQ.NEXTVAL, P_BATCH_ID, E.EMP_NO,
                  NVL(E.BANK_NO, 0), NVL(E.MASTER_BANK_NO, 0), E.TOTAL_AMOUNT, A.ACC_ID,
                  E.OVERRIDE_PROVIDER_TYPE, E.OVERRIDE_ACC_ID,
                  A.IBAN, A.ACC_NO, A.OWNER_NAME, A.OWNER_ID_NO,
                  A.PROVIDER_NAME, A.BRANCH_NAME
              );
              V_FOUND := 1;
              V_CHANGED := V_CHANGED + 1;
            END LOOP;
            IF V_FOUND = 0 THEN
              V_MISSING := V_MISSING ||
                CASE WHEN LENGTH(V_MISSING) > 0 THEN ',' ELSE '' END || TO_CHAR(E.EMP_NO);
            END IF;
            V_TOTAL := V_TOTAL + E.TOTAL_AMOUNT;
            CONTINUE;
          END;
        END IF;

        -- 🎯 حالة split عادي (مع override_provider_type filter اختياري)
        SELECT COUNT(*) INTO V_ACC_COUNT
          FROM GFC.PAYMENT_ACCOUNTS_TB PA
          LEFT JOIN GFC.PAYMENT_PROVIDERS_TB PP ON PP.PROVIDER_ID = PA.PROVIDER_ID
         WHERE PA.EMP_NO = E.EMP_NO AND PA.STATUS = 1 AND PA.IS_ACTIVE = 1
           AND (E.OVERRIDE_PROVIDER_TYPE IS NULL OR PP.PROVIDER_TYPE = E.OVERRIDE_PROVIDER_TYPE);

        IF V_ACC_COUNT = 0 THEN
          V_MISSING := V_MISSING ||
            CASE WHEN LENGTH(V_MISSING) > 0 THEN ',' ELSE '' END ||
            TO_CHAR(E.EMP_NO);
          -- نعيد سطر الموظف الـ legacy لتجنب فقدان الصرف
          INSERT INTO GFC.PAYMENT_BATCH_DETAIL_TB (
              BATCH_DETAIL_ID, BATCH_ID, EMP_NO, BANK_NO, MASTER_BANK_NO, TOTAL_AMOUNT, ACC_ID,
              OVERRIDE_PROVIDER_TYPE, OVERRIDE_ACC_ID
          ) VALUES (
              PAYMENT_BATCH_DETAIL_SEQ.NEXTVAL, P_BATCH_ID, E.EMP_NO,
              NVL(E.BANK_NO, 0), NVL(E.MASTER_BANK_NO, 0), E.TOTAL_AMOUNT, NULL,
              E.OVERRIDE_PROVIDER_TYPE, E.OVERRIDE_ACC_ID
          );
          CONTINUE;
        END IF;

        -- مرحلة 1: مبلغ ثابت
        FOR A IN (SELECT PA.ACC_ID, PA.SPLIT_VALUE,
                         PA.IBAN, NVL(PA.ACCOUNT_NO, PA.WALLET_NUMBER) AS ACC_NO,
                         PA.OWNER_NAME, PA.OWNER_ID_NO,
                         PP.PROVIDER_NAME, BR.BRANCH_NAME
                    FROM GFC.PAYMENT_ACCOUNTS_TB PA
                    LEFT JOIN GFC.PAYMENT_PROVIDERS_TB     PP ON PP.PROVIDER_ID = PA.PROVIDER_ID
                    LEFT JOIN GFC.PAYMENT_BANK_BRANCHES_TB BR ON BR.BRANCH_ID  = PA.BRANCH_ID
                   WHERE PA.EMP_NO = E.EMP_NO AND PA.STATUS = 1 AND PA.IS_ACTIVE = 1
                     AND PA.SPLIT_TYPE = 2
                     AND (E.OVERRIDE_PROVIDER_TYPE IS NULL OR PP.PROVIDER_TYPE = E.OVERRIDE_PROVIDER_TYPE)
                   ORDER BY PA.SPLIT_ORDER, PA.ACC_ID)
        LOOP
          V_AMOUNT := LEAST(NVL(A.SPLIT_VALUE, 0), V_REMAINING);
          IF V_AMOUNT > 0 THEN
            INSERT INTO GFC.PAYMENT_BATCH_DETAIL_TB (
                BATCH_DETAIL_ID, BATCH_ID, EMP_NO, BANK_NO, MASTER_BANK_NO, TOTAL_AMOUNT, ACC_ID,
                OVERRIDE_PROVIDER_TYPE, OVERRIDE_ACC_ID,
                SNAP_IBAN, SNAP_ACCOUNT_NO, SNAP_OWNER_NAME, SNAP_OWNER_ID_NO,
                SNAP_PROVIDER_NAME, SNAP_BRANCH_NAME
            ) VALUES (
                PAYMENT_BATCH_DETAIL_SEQ.NEXTVAL, P_BATCH_ID, E.EMP_NO,
                NVL(E.BANK_NO, 0), NVL(E.MASTER_BANK_NO, 0), V_AMOUNT, A.ACC_ID,
                E.OVERRIDE_PROVIDER_TYPE, E.OVERRIDE_ACC_ID,
                A.IBAN, A.ACC_NO, A.OWNER_NAME, A.OWNER_ID_NO,
                A.PROVIDER_NAME, A.BRANCH_NAME
            );
            V_REMAINING := V_REMAINING - V_AMOUNT;
            V_CHANGED   := V_CHANGED + 1;
          END IF;
        END LOOP;

        -- مرحلة 2: نسبة
        FOR A IN (SELECT PA.ACC_ID, PA.SPLIT_VALUE,
                         PA.IBAN, NVL(PA.ACCOUNT_NO, PA.WALLET_NUMBER) AS ACC_NO,
                         PA.OWNER_NAME, PA.OWNER_ID_NO,
                         PP.PROVIDER_NAME, BR.BRANCH_NAME
                    FROM GFC.PAYMENT_ACCOUNTS_TB PA
                    LEFT JOIN GFC.PAYMENT_PROVIDERS_TB     PP ON PP.PROVIDER_ID = PA.PROVIDER_ID
                    LEFT JOIN GFC.PAYMENT_BANK_BRANCHES_TB BR ON BR.BRANCH_ID  = PA.BRANCH_ID
                   WHERE PA.EMP_NO = E.EMP_NO AND PA.STATUS = 1 AND PA.IS_ACTIVE = 1
                     AND PA.SPLIT_TYPE = 1
                     AND (E.OVERRIDE_PROVIDER_TYPE IS NULL OR PP.PROVIDER_TYPE = E.OVERRIDE_PROVIDER_TYPE)
                   ORDER BY PA.SPLIT_ORDER, PA.ACC_ID)
        LOOP
          V_AMOUNT := ROUND(E.TOTAL_AMOUNT * NVL(A.SPLIT_VALUE, 0) / 100, 2);
          V_AMOUNT := LEAST(V_AMOUNT, V_REMAINING);
          IF V_AMOUNT > 0 THEN
            INSERT INTO GFC.PAYMENT_BATCH_DETAIL_TB (
                BATCH_DETAIL_ID, BATCH_ID, EMP_NO, BANK_NO, MASTER_BANK_NO, TOTAL_AMOUNT, ACC_ID,
                OVERRIDE_PROVIDER_TYPE, OVERRIDE_ACC_ID,
                SNAP_IBAN, SNAP_ACCOUNT_NO, SNAP_OWNER_NAME, SNAP_OWNER_ID_NO,
                SNAP_PROVIDER_NAME, SNAP_BRANCH_NAME
            ) VALUES (
                PAYMENT_BATCH_DETAIL_SEQ.NEXTVAL, P_BATCH_ID, E.EMP_NO,
                NVL(E.BANK_NO, 0), NVL(E.MASTER_BANK_NO, 0), V_AMOUNT, A.ACC_ID,
                E.OVERRIDE_PROVIDER_TYPE, E.OVERRIDE_ACC_ID,
                A.IBAN, A.ACC_NO, A.OWNER_NAME, A.OWNER_ID_NO,
                A.PROVIDER_NAME, A.BRANCH_NAME
            );
            V_REMAINING := V_REMAINING - V_AMOUNT;
            V_CHANGED   := V_CHANGED + 1;
          END IF;
        END LOOP;

        -- مرحلة 3: كامل الباقي (تقسيم بالتساوي) — مع override filter
        IF V_REMAINING > 0 THEN
          DECLARE
            V_REM_CNT NUMBER;
            V_IDX     NUMBER := 0;
            V_PER     NUMBER;
            V_AMT_R   NUMBER;
          BEGIN
            SELECT COUNT(*) INTO V_REM_CNT
              FROM GFC.PAYMENT_ACCOUNTS_TB PA
              LEFT JOIN GFC.PAYMENT_PROVIDERS_TB PP ON PP.PROVIDER_ID = PA.PROVIDER_ID
             WHERE PA.EMP_NO = E.EMP_NO AND PA.STATUS = 1 AND PA.IS_ACTIVE = 1
               AND PA.SPLIT_TYPE = 3
               AND (E.OVERRIDE_PROVIDER_TYPE IS NULL OR PP.PROVIDER_TYPE = E.OVERRIDE_PROVIDER_TYPE);

            IF V_REM_CNT > 0 THEN
              V_PER := ROUND(V_REMAINING / V_REM_CNT, 2);
              FOR A IN (SELECT PA.ACC_ID, PA.IBAN,
                               NVL(PA.ACCOUNT_NO, PA.WALLET_NUMBER) AS ACC_NO,
                               PA.OWNER_NAME, PA.OWNER_ID_NO,
                               PP.PROVIDER_NAME, BR.BRANCH_NAME
                          FROM GFC.PAYMENT_ACCOUNTS_TB PA
                          LEFT JOIN GFC.PAYMENT_PROVIDERS_TB     PP ON PP.PROVIDER_ID = PA.PROVIDER_ID
                          LEFT JOIN GFC.PAYMENT_BANK_BRANCHES_TB BR ON BR.BRANCH_ID  = PA.BRANCH_ID
                         WHERE PA.EMP_NO = E.EMP_NO AND PA.STATUS = 1 AND PA.IS_ACTIVE = 1
                           AND PA.SPLIT_TYPE = 3
                           AND (E.OVERRIDE_PROVIDER_TYPE IS NULL OR PP.PROVIDER_TYPE = E.OVERRIDE_PROVIDER_TYPE)
                         ORDER BY PA.IS_DEFAULT DESC, PA.SPLIT_ORDER, PA.ACC_ID)
              LOOP
                V_IDX := V_IDX + 1;
                IF V_IDX = V_REM_CNT THEN V_AMT_R := V_REMAINING;
                ELSE                       V_AMT_R := LEAST(V_PER, V_REMAINING);
                END IF;

                IF V_AMT_R > 0 THEN
                  INSERT INTO GFC.PAYMENT_BATCH_DETAIL_TB (
                      BATCH_DETAIL_ID, BATCH_ID, EMP_NO, BANK_NO, MASTER_BANK_NO, TOTAL_AMOUNT, ACC_ID,
                      OVERRIDE_PROVIDER_TYPE, OVERRIDE_ACC_ID,
                      SNAP_IBAN, SNAP_ACCOUNT_NO, SNAP_OWNER_NAME, SNAP_OWNER_ID_NO,
                      SNAP_PROVIDER_NAME, SNAP_BRANCH_NAME
                  ) VALUES (
                      PAYMENT_BATCH_DETAIL_SEQ.NEXTVAL, P_BATCH_ID, E.EMP_NO,
                      NVL(E.BANK_NO, 0), NVL(E.MASTER_BANK_NO, 0), V_AMT_R, A.ACC_ID,
                      E.OVERRIDE_PROVIDER_TYPE, E.OVERRIDE_ACC_ID,
                      A.IBAN, A.ACC_NO, A.OWNER_NAME, A.OWNER_ID_NO,
                      A.PROVIDER_NAME, A.BRANCH_NAME
                  );
                  V_REMAINING := V_REMAINING - V_AMT_R;
                  V_CHANGED   := V_CHANGED + 1;
                END IF;
              END LOOP;
            END IF;
          END;
        END IF;

        V_TOTAL := V_TOTAL + E.TOTAL_AMOUNT;
      END;
    END LOOP;

    -- تحديث total_amount في PAYMENT_BATCH_TB (للتأكيد فقط — يجب أن يبقى ثابتاً)
    UPDATE GFC.PAYMENT_BATCH_TB SET TOTAL_AMOUNT = V_TOTAL
     WHERE BATCH_ID = P_BATCH_ID;

    COMMIT;
    P_CHANGED_OUT := V_CHANGED;
    P_MSG_OUT := CASE WHEN LENGTH(V_MISSING) > 0
                      THEN '1|' || V_MISSING
                      ELSE '1' END;
  EXCEPTION WHEN OTHERS THEN
    ROLLBACK;
    P_CHANGED_OUT := 0;
    P_MSG_OUT := SQLERRM;
  END;


  -- ============================================================
  -- BATCH BANK EXPORT — تصدير بيانات الدفعة للبنك
  -- ============================================================
  PROCEDURE BATCH_BANK_EXPORT (
      P_BATCH_ID NUMBER,
      P_MASTER_BANK_NO NUMBER DEFAULT NULL,
      P_REF_CUR_OUT OUT SYS_REFCURSOR, P_MSG_OUT OUT VARCHAR2
  ) IS
  BEGIN
    -- 🆕 الترتيب: البنك الرئيسي → الفرع → أبجدي بالاسم العربي → رقم الموظف
    -- NLSSORT(..., 'NLS_SORT=ARABIC') ضروري عشان الترتيب العربي يطلع صحيح
    -- (يطابق ا/أ/إ، ي/ى، ويرتّب الحروف بترتيبها الأبجدي الفعلي)
    OPEN P_REF_CUR_OUT FOR
      SELECT * FROM GFC.PAYMENT_BATCH_BANK_VW
       WHERE BATCH_ID = P_BATCH_ID
         AND (P_MASTER_BANK_NO <= 0 OR MASTER_BANK_NO = P_MASTER_BANK_NO)
       ORDER BY MASTER_BANK_NO,
                BANK_NO,
                NLSSORT(OWNER_NAME, 'NLS_SORT=ARABIC'),
                EMP_NO;
    P_MSG_OUT := '1';
  EXCEPTION WHEN OTHERS THEN P_MSG_OUT := SQLERRM;
  END;


  -- ============================================================
  -- BATCH DETAIL REDIST — إعادة توزيع موظف معيّن في دفعة محتسبة
  -- يُستخدم من شاشة batch_detail لتغيير وجهة صرف موظف بدون فك الاحتساب
  -- ============================================================
  --   P_OVERRIDE_PROVIDER_TYPE: NULL=افتراضي, 1=بنك, 2=محفظة
  --   P_OVERRIDE_ACC_ID: حساب محدد (يطغى على PROVIDER_TYPE)
  --   ⚠️ يعمل فقط لو الدفعة بحالة محتسبة (STATUS=0) + DISBURSE_METHOD=2
  -- ============================================================
  PROCEDURE BATCH_DETAIL_REDIST (
      P_BATCH_ID                NUMBER,
      P_EMP_NO                  NUMBER,
      P_OVERRIDE_PROVIDER_TYPE  NUMBER,   -- يقبل NULL
      P_OVERRIDE_ACC_ID         NUMBER,   -- يقبل NULL
      P_MSG_OUT                 OUT VARCHAR2
  ) IS
    V_STATUS    NUMBER;
    V_METHOD    NUMBER;
    V_TOTAL     NUMBER := 0;
    V_BANK_NO   NUMBER;
    V_MASTER    NUMBER;
    V_REMAINING NUMBER;
    V_AMOUNT    NUMBER;
    V_ACC_COUNT NUMBER := 0;
  BEGIN
    -- تحقق من الحالة
    SELECT STATUS, NVL(DISBURSE_METHOD, 1) INTO V_STATUS, V_METHOD
      FROM GFC.PAYMENT_BATCH_TB WHERE BATCH_ID = P_BATCH_ID;
    IF V_STATUS <> 0 THEN
      P_MSG_OUT := 'لا يمكن إعادة التوزيع — الدفعة ليست محتسبة';
      RETURN;
    END IF;
    IF V_METHOD <> 2 THEN
      P_MSG_OUT := 'إعادة التوزيع متاحة فقط للطريقة الجديدة (split)';
      RETURN;
    END IF;

    -- جمع المبلغ الإجمالي للموظف الحالي
    SELECT SUM(TOTAL_AMOUNT), MIN(BANK_NO), MIN(MASTER_BANK_NO)
      INTO V_TOTAL, V_BANK_NO, V_MASTER
      FROM GFC.PAYMENT_BATCH_DETAIL_TB
     WHERE BATCH_ID = P_BATCH_ID AND EMP_NO = P_EMP_NO;

    IF V_TOTAL IS NULL OR V_TOTAL <= 0 THEN
      P_MSG_OUT := 'لا يوجد توزيع للموظف ' || P_EMP_NO || ' في هذه الدفعة';
      RETURN;
    END IF;

    -- حذف التوزيع الحالي للموظف
    DELETE FROM GFC.PAYMENT_BATCH_DETAIL_TB
     WHERE BATCH_ID = P_BATCH_ID AND EMP_NO = P_EMP_NO;

    V_REMAINING := V_TOTAL;

    -- 🎯 حالة 1: حساب محدد → 100% له
    IF P_OVERRIDE_ACC_ID IS NOT NULL THEN
      DECLARE V_FOUND NUMBER := 0; BEGIN
        FOR A IN (SELECT PA.ACC_ID, PA.IBAN,
                         NVL(PA.ACCOUNT_NO, PA.WALLET_NUMBER) AS ACC_NO,
                         PA.OWNER_NAME, PA.OWNER_ID_NO,
                         PP.PROVIDER_NAME, BR.BRANCH_NAME
                    FROM GFC.PAYMENT_ACCOUNTS_TB PA
                    LEFT JOIN GFC.PAYMENT_PROVIDERS_TB     PP ON PP.PROVIDER_ID = PA.PROVIDER_ID
                    LEFT JOIN GFC.PAYMENT_BANK_BRANCHES_TB BR ON BR.BRANCH_ID  = PA.BRANCH_ID
                   WHERE PA.ACC_ID = P_OVERRIDE_ACC_ID
                     AND PA.EMP_NO = P_EMP_NO AND PA.STATUS = 1)
        LOOP
          INSERT INTO GFC.PAYMENT_BATCH_DETAIL_TB (
              BATCH_DETAIL_ID, BATCH_ID, EMP_NO, BANK_NO, MASTER_BANK_NO, TOTAL_AMOUNT, ACC_ID,
              OVERRIDE_PROVIDER_TYPE, OVERRIDE_ACC_ID,
              SNAP_IBAN, SNAP_ACCOUNT_NO, SNAP_OWNER_NAME, SNAP_OWNER_ID_NO,
              SNAP_PROVIDER_NAME, SNAP_BRANCH_NAME
          ) VALUES (
              PAYMENT_BATCH_DETAIL_SEQ.NEXTVAL, P_BATCH_ID, P_EMP_NO,
              NVL(V_BANK_NO, 0), NVL(V_MASTER, 0), V_TOTAL, A.ACC_ID,
              P_OVERRIDE_PROVIDER_TYPE, P_OVERRIDE_ACC_ID,
              A.IBAN, A.ACC_NO, A.OWNER_NAME, A.OWNER_ID_NO,
              A.PROVIDER_NAME, A.BRANCH_NAME
          );
          V_FOUND := 1;
        END LOOP;
        IF V_FOUND = 0 THEN
          ROLLBACK;
          P_MSG_OUT := 'الحساب ' || P_OVERRIDE_ACC_ID || ' غير موجود/غير صالح للموظف';
          RETURN;
        END IF;
      END;
    ELSE
      -- 🎯 حالة 2: split عادي (مع override_provider_type filter اختياري)
      SELECT COUNT(*) INTO V_ACC_COUNT
        FROM GFC.PAYMENT_ACCOUNTS_TB PA
        LEFT JOIN GFC.PAYMENT_PROVIDERS_TB PP ON PP.PROVIDER_ID = PA.PROVIDER_ID
       WHERE PA.EMP_NO = P_EMP_NO AND PA.STATUS = 1 AND PA.IS_ACTIVE = 1
         AND (P_OVERRIDE_PROVIDER_TYPE IS NULL OR PP.PROVIDER_TYPE = P_OVERRIDE_PROVIDER_TYPE);

      IF V_ACC_COUNT = 0 THEN
        ROLLBACK;
        P_MSG_OUT := 'لا يوجد حسابات نشطة للموظف ' || P_EMP_NO ||
                     CASE WHEN P_OVERRIDE_PROVIDER_TYPE = 1 THEN ' من نوع بنك'
                          WHEN P_OVERRIDE_PROVIDER_TYPE = 2 THEN ' من نوع محفظة'
                          ELSE '' END;
        RETURN;
      END IF;

      -- مرحلة 1: مبلغ ثابت
      FOR A IN (SELECT PA.ACC_ID, PA.SPLIT_VALUE, PA.IBAN,
                       NVL(PA.ACCOUNT_NO, PA.WALLET_NUMBER) AS ACC_NO,
                       PA.OWNER_NAME, PA.OWNER_ID_NO,
                       PP.PROVIDER_NAME, BR.BRANCH_NAME
                  FROM GFC.PAYMENT_ACCOUNTS_TB PA
                  LEFT JOIN GFC.PAYMENT_PROVIDERS_TB     PP ON PP.PROVIDER_ID = PA.PROVIDER_ID
                  LEFT JOIN GFC.PAYMENT_BANK_BRANCHES_TB BR ON BR.BRANCH_ID  = PA.BRANCH_ID
                 WHERE PA.EMP_NO = P_EMP_NO AND PA.STATUS = 1 AND PA.IS_ACTIVE = 1
                   AND PA.SPLIT_TYPE = 2
                   AND (P_OVERRIDE_PROVIDER_TYPE IS NULL OR PP.PROVIDER_TYPE = P_OVERRIDE_PROVIDER_TYPE)
                 ORDER BY PA.SPLIT_ORDER, PA.ACC_ID)
      LOOP
        V_AMOUNT := LEAST(NVL(A.SPLIT_VALUE, 0), V_REMAINING);
        IF V_AMOUNT > 0 THEN
          INSERT INTO GFC.PAYMENT_BATCH_DETAIL_TB (
              BATCH_DETAIL_ID, BATCH_ID, EMP_NO, BANK_NO, MASTER_BANK_NO, TOTAL_AMOUNT, ACC_ID,
              OVERRIDE_PROVIDER_TYPE, OVERRIDE_ACC_ID,
              SNAP_IBAN, SNAP_ACCOUNT_NO, SNAP_OWNER_NAME, SNAP_OWNER_ID_NO,
              SNAP_PROVIDER_NAME, SNAP_BRANCH_NAME
          ) VALUES (
              PAYMENT_BATCH_DETAIL_SEQ.NEXTVAL, P_BATCH_ID, P_EMP_NO,
              NVL(V_BANK_NO, 0), NVL(V_MASTER, 0), V_AMOUNT, A.ACC_ID,
              P_OVERRIDE_PROVIDER_TYPE, NULL,
              A.IBAN, A.ACC_NO, A.OWNER_NAME, A.OWNER_ID_NO,
              A.PROVIDER_NAME, A.BRANCH_NAME
          );
          V_REMAINING := V_REMAINING - V_AMOUNT;
        END IF;
      END LOOP;

      -- مرحلة 2: نسبة
      FOR A IN (SELECT PA.ACC_ID, PA.SPLIT_VALUE, PA.IBAN,
                       NVL(PA.ACCOUNT_NO, PA.WALLET_NUMBER) AS ACC_NO,
                       PA.OWNER_NAME, PA.OWNER_ID_NO,
                       PP.PROVIDER_NAME, BR.BRANCH_NAME
                  FROM GFC.PAYMENT_ACCOUNTS_TB PA
                  LEFT JOIN GFC.PAYMENT_PROVIDERS_TB     PP ON PP.PROVIDER_ID = PA.PROVIDER_ID
                  LEFT JOIN GFC.PAYMENT_BANK_BRANCHES_TB BR ON BR.BRANCH_ID  = PA.BRANCH_ID
                 WHERE PA.EMP_NO = P_EMP_NO AND PA.STATUS = 1 AND PA.IS_ACTIVE = 1
                   AND PA.SPLIT_TYPE = 1
                   AND (P_OVERRIDE_PROVIDER_TYPE IS NULL OR PP.PROVIDER_TYPE = P_OVERRIDE_PROVIDER_TYPE)
                 ORDER BY PA.SPLIT_ORDER, PA.ACC_ID)
      LOOP
        V_AMOUNT := ROUND(V_TOTAL * NVL(A.SPLIT_VALUE, 0) / 100, 2);
        V_AMOUNT := LEAST(V_AMOUNT, V_REMAINING);
        IF V_AMOUNT > 0 THEN
          INSERT INTO GFC.PAYMENT_BATCH_DETAIL_TB (
              BATCH_DETAIL_ID, BATCH_ID, EMP_NO, BANK_NO, MASTER_BANK_NO, TOTAL_AMOUNT, ACC_ID,
              OVERRIDE_PROVIDER_TYPE, OVERRIDE_ACC_ID,
              SNAP_IBAN, SNAP_ACCOUNT_NO, SNAP_OWNER_NAME, SNAP_OWNER_ID_NO,
              SNAP_PROVIDER_NAME, SNAP_BRANCH_NAME
          ) VALUES (
              PAYMENT_BATCH_DETAIL_SEQ.NEXTVAL, P_BATCH_ID, P_EMP_NO,
              NVL(V_BANK_NO, 0), NVL(V_MASTER, 0), V_AMOUNT, A.ACC_ID,
              P_OVERRIDE_PROVIDER_TYPE, NULL,
              A.IBAN, A.ACC_NO, A.OWNER_NAME, A.OWNER_ID_NO,
              A.PROVIDER_NAME, A.BRANCH_NAME
          );
          V_REMAINING := V_REMAINING - V_AMOUNT;
        END IF;
      END LOOP;

      -- مرحلة 3: كامل الباقي
      IF V_REMAINING > 0 THEN
        DECLARE
          V_REM_CNT NUMBER;
          V_IDX     NUMBER := 0;
          V_PER     NUMBER;
          V_AMT_R   NUMBER;
        BEGIN
          SELECT COUNT(*) INTO V_REM_CNT
            FROM GFC.PAYMENT_ACCOUNTS_TB PA
            LEFT JOIN GFC.PAYMENT_PROVIDERS_TB PP ON PP.PROVIDER_ID = PA.PROVIDER_ID
           WHERE PA.EMP_NO = P_EMP_NO AND PA.STATUS = 1 AND PA.IS_ACTIVE = 1
             AND PA.SPLIT_TYPE = 3
             AND (P_OVERRIDE_PROVIDER_TYPE IS NULL OR PP.PROVIDER_TYPE = P_OVERRIDE_PROVIDER_TYPE);

          IF V_REM_CNT > 0 THEN
            V_PER := ROUND(V_REMAINING / V_REM_CNT, 2);
            FOR A IN (SELECT PA.ACC_ID, PA.IBAN,
                             NVL(PA.ACCOUNT_NO, PA.WALLET_NUMBER) AS ACC_NO,
                             PA.OWNER_NAME, PA.OWNER_ID_NO,
                             PP.PROVIDER_NAME, BR.BRANCH_NAME
                        FROM GFC.PAYMENT_ACCOUNTS_TB PA
                        LEFT JOIN GFC.PAYMENT_PROVIDERS_TB     PP ON PP.PROVIDER_ID = PA.PROVIDER_ID
                        LEFT JOIN GFC.PAYMENT_BANK_BRANCHES_TB BR ON BR.BRANCH_ID  = PA.BRANCH_ID
                       WHERE PA.EMP_NO = P_EMP_NO AND PA.STATUS = 1 AND PA.IS_ACTIVE = 1
                         AND PA.SPLIT_TYPE = 3
                         AND (P_OVERRIDE_PROVIDER_TYPE IS NULL OR PP.PROVIDER_TYPE = P_OVERRIDE_PROVIDER_TYPE)
                       ORDER BY PA.IS_DEFAULT DESC, PA.SPLIT_ORDER, PA.ACC_ID)
            LOOP
              V_IDX := V_IDX + 1;
              IF V_IDX = V_REM_CNT THEN V_AMT_R := V_REMAINING;
              ELSE                       V_AMT_R := LEAST(V_PER, V_REMAINING); END IF;

              IF V_AMT_R > 0 THEN
                INSERT INTO GFC.PAYMENT_BATCH_DETAIL_TB (
                    BATCH_DETAIL_ID, BATCH_ID, EMP_NO, BANK_NO, MASTER_BANK_NO, TOTAL_AMOUNT, ACC_ID,
                    OVERRIDE_PROVIDER_TYPE, OVERRIDE_ACC_ID,
                    SNAP_IBAN, SNAP_ACCOUNT_NO, SNAP_OWNER_NAME, SNAP_OWNER_ID_NO,
                    SNAP_PROVIDER_NAME, SNAP_BRANCH_NAME
                ) VALUES (
                    PAYMENT_BATCH_DETAIL_SEQ.NEXTVAL, P_BATCH_ID, P_EMP_NO,
                    NVL(V_BANK_NO, 0), NVL(V_MASTER, 0), V_AMT_R, A.ACC_ID,
                    P_OVERRIDE_PROVIDER_TYPE, NULL,
                    A.IBAN, A.ACC_NO, A.OWNER_NAME, A.OWNER_ID_NO,
                    A.PROVIDER_NAME, A.BRANCH_NAME
                );
                V_REMAINING := V_REMAINING - V_AMT_R;
              END IF;
            END LOOP;
          END IF;
        END;
      END IF;
    END IF;

    COMMIT;
    P_MSG_OUT := '1';
  EXCEPTION WHEN OTHERS THEN
    ROLLBACK;
    P_MSG_OUT := SQLERRM;
  END;


END DISBURSEMENT_BATCH_PKG;
/
