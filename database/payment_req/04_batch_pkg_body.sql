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
    OPEN P_REF_CUR_OUT FOR
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
      JOIN DATA.EMPLOYEES E      ON E.NO     = D.EMP_NO
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
    -- يجمع كل المبالغ للموظف (بصرف النظر عن IS_ACTIVE — متقاعد/فعّال كلاهما يصرف)
    -- مع snapshot ثابت من DATA.EMPLOYEES (للطريقة القديمة، و للـ fallback)
    -- =====================================================================
    FOR E IN (
      SELECT D.EMP_NO, SUM(D.REQ_AMOUNT) AS TOTAL_AMOUNT,
             EE.BANK                AS BANK_NO,
             EE.MASTER_BANKS_EMAIL  AS MASTER_BANK_NO,
             EE.NAME                AS EMP_NAME,
             TO_CHAR(EE.ID)         AS EMP_ID_STR,
             EE.IBAN                AS EMP_IBAN,
             EE.ACCOUNT_BANK_EMAIL  AS EMP_ACCOUNT,
             (SELECT MB.B_NAME FROM DATA.MASTER_BANKS_EMAIL MB WHERE MB.B_NO = EE.MASTER_BANKS_EMAIL) AS MASTER_BANK_NAME,
             DISBURSEMENT_PKG.GET_BANK_NAME(EE.BANK) AS BANK_BRANCH_NAME
        FROM GFC.PAYMENT_REQ_DETAIL_TB D
        JOIN GFC.PAYMENT_BATCH_LINK_TB L ON L.DETAIL_ID = D.DETAIL_ID AND L.BATCH_ID = V_BATCH_ID
        LEFT JOIN DATA.EMPLOYEES EE ON EE.NO = D.EMP_NO
        WHERE D.DETAIL_STATUS = C_REQ_STATUS_APPROVED
        GROUP BY D.EMP_NO, EE.BANK, EE.MASTER_BANKS_EMAIL, EE.NAME, EE.ID, EE.IBAN, EE.ACCOUNT_BANK_EMAIL
    ) LOOP
      IF V_METHOD = 1 THEN
        -- ================== الطريقة القديمة ==================
        -- سطر واحد لكل موظف، ببنك EMPLOYEES + snapshot من EMPLOYEES
        INSERT INTO GFC.PAYMENT_BATCH_DETAIL_TB (
            BATCH_DETAIL_ID, BATCH_ID, EMP_NO, BANK_NO, MASTER_BANK_NO, TOTAL_AMOUNT, ACC_ID,
            SNAP_IBAN, SNAP_ACCOUNT_NO, SNAP_OWNER_NAME, SNAP_OWNER_ID_NO,
            SNAP_PROVIDER_NAME, SNAP_BRANCH_NAME
        ) VALUES (
            PAYMENT_BATCH_DETAIL_SEQ.NEXTVAL, V_BATCH_ID, E.EMP_NO,
            NVL(E.BANK_NO, 0), NVL(E.MASTER_BANK_NO, 0), E.TOTAL_AMOUNT, NULL,
            E.EMP_IBAN, E.EMP_ACCOUNT, E.EMP_NAME, E.EMP_ID_STR,
            E.MASTER_BANK_NAME, E.BANK_BRANCH_NAME
        );
        V_TOTAL_AMT := V_TOTAL_AMT + E.TOTAL_AMOUNT;
        V_EMP_CNT := V_EMP_CNT + 1;
      ELSE
        -- ================== الطريقة الجديدة ==================
        -- نقرأ حسابات الموظف النشطة من PAYMENT_ACCOUNTS_TB ونوزّع المبلغ حسب SPLIT
        -- مع snapshot لكل حساب (IBAN/owner/provider/branch) محنّط في BATCH_DETAIL
        DECLARE
          V_REMAINING  NUMBER := E.TOTAL_AMOUNT;
          V_AMOUNT     NUMBER;
          V_ACC_COUNT  NUMBER := 0;
          V_HAS_REST   NUMBER := 0;
        BEGIN
          SELECT COUNT(*),
                 SUM(CASE WHEN SPLIT_TYPE = 3 THEN 1 ELSE 0 END)
            INTO V_ACC_COUNT, V_HAS_REST
            FROM GFC.PAYMENT_ACCOUNTS_TB
           WHERE EMP_NO = E.EMP_NO
             AND STATUS = 1 AND IS_ACTIVE = 1;

          IF V_ACC_COUNT = 0 THEN
            V_MISSING := V_MISSING ||
              CASE WHEN LENGTH(V_MISSING) > 0 THEN ',' ELSE '' END ||
              TO_CHAR(E.EMP_NO);
            CONTINUE;
          END IF;

          -- المرحلة 1: مبلغ ثابت (SPLIT_TYPE=2) — مع snapshot كامل من PA + Provider + Branch
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
                     ORDER BY PA.SPLIT_ORDER, PA.ACC_ID)
          LOOP
            V_AMOUNT := LEAST(NVL(A.SPLIT_VALUE, 0), V_REMAINING);
            IF V_AMOUNT > 0 THEN
              INSERT INTO GFC.PAYMENT_BATCH_DETAIL_TB (
                  BATCH_DETAIL_ID, BATCH_ID, EMP_NO, BANK_NO, MASTER_BANK_NO, TOTAL_AMOUNT, ACC_ID,
                  SNAP_IBAN, SNAP_ACCOUNT_NO, SNAP_OWNER_NAME, SNAP_OWNER_ID_NO,
                  SNAP_PROVIDER_NAME, SNAP_BRANCH_NAME
              ) VALUES (
                  PAYMENT_BATCH_DETAIL_SEQ.NEXTVAL, V_BATCH_ID, E.EMP_NO,
                  NVL(E.BANK_NO, 0), NVL(E.MASTER_BANK_NO, 0), V_AMOUNT, A.ACC_ID,
                  A.IBAN, A.SNAP_ACCOUNT_NO, A.OWNER_NAME, A.OWNER_ID_NO,
                  A.PROVIDER_NAME, A.BRANCH_NAME
              );
              V_REMAINING := V_REMAINING - V_AMOUNT;
            END IF;
          END LOOP;

          -- المرحلة 2: نسبة مئوية (SPLIT_TYPE=1) — مع snapshot كامل
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
                     ORDER BY PA.SPLIT_ORDER, PA.ACC_ID)
          LOOP
            V_AMOUNT := ROUND(E.TOTAL_AMOUNT * NVL(A.SPLIT_VALUE, 0) / 100, 2);
            V_AMOUNT := LEAST(V_AMOUNT, V_REMAINING);
            IF V_AMOUNT > 0 THEN
              INSERT INTO GFC.PAYMENT_BATCH_DETAIL_TB (
                  BATCH_DETAIL_ID, BATCH_ID, EMP_NO, BANK_NO, MASTER_BANK_NO, TOTAL_AMOUNT, ACC_ID,
                  SNAP_IBAN, SNAP_ACCOUNT_NO, SNAP_OWNER_NAME, SNAP_OWNER_ID_NO,
                  SNAP_PROVIDER_NAME, SNAP_BRANCH_NAME
              ) VALUES (
                  PAYMENT_BATCH_DETAIL_SEQ.NEXTVAL, V_BATCH_ID, E.EMP_NO,
                  NVL(E.BANK_NO, 0), NVL(E.MASTER_BANK_NO, 0), V_AMOUNT, A.ACC_ID,
                  A.IBAN, A.SNAP_ACCOUNT_NO, A.OWNER_NAME, A.OWNER_ID_NO,
                  A.PROVIDER_NAME, A.BRANCH_NAME
              );
              V_REMAINING := V_REMAINING - V_AMOUNT;
            END IF;
          END LOOP;

          -- المرحلة 3: كامل الباقي (SPLIT_TYPE=3) — يُقسم بالتساوي على كل الحسابات
          IF V_REMAINING > 0 THEN
            DECLARE
              V_REST_CNT NUMBER;
              V_IDX      NUMBER := 0;
              V_PER      NUMBER;
              V_AMT_R    NUMBER;
            BEGIN
              SELECT COUNT(*) INTO V_REST_CNT
                FROM GFC.PAYMENT_ACCOUNTS_TB PA
               WHERE PA.EMP_NO = E.EMP_NO AND PA.STATUS = 1 AND PA.IS_ACTIVE = 1
                 AND PA.SPLIT_TYPE = 3;

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
                           ORDER BY PA.IS_DEFAULT DESC, PA.SPLIT_ORDER, PA.ACC_ID)
                LOOP
                  V_IDX := V_IDX + 1;
                  -- آخر حساب يأخذ ما تبقى (لتفادي أخطاء التقريب)
                  IF V_IDX = V_REST_CNT THEN
                    V_AMT_R := V_REMAINING;
                  ELSE
                    V_AMT_R := LEAST(V_PER, V_REMAINING);
                  END IF;

                  IF V_AMT_R > 0 THEN
                    INSERT INTO GFC.PAYMENT_BATCH_DETAIL_TB (
                        BATCH_DETAIL_ID, BATCH_ID, EMP_NO, BANK_NO, MASTER_BANK_NO, TOTAL_AMOUNT, ACC_ID,
                        SNAP_IBAN, SNAP_ACCOUNT_NO, SNAP_OWNER_NAME, SNAP_OWNER_ID_NO,
                        SNAP_PROVIDER_NAME, SNAP_BRANCH_NAME
                    ) VALUES (
                        PAYMENT_BATCH_DETAIL_SEQ.NEXTVAL, V_BATCH_ID, E.EMP_NO,
                        NVL(E.BANK_NO, 0), NVL(E.MASTER_BANK_NO, 0), V_AMT_R, A.ACC_ID,
                        A.IBAN, A.SNAP_ACC, A.OWNER_NAME, A.OWNER_ID_NO,
                        A.PROVIDER_NAME, A.BRANCH_NAME
                    );
                    V_REMAINING := V_REMAINING - V_AMT_R;
                  END IF;
                END LOOP;
              ELSE
                -- لا يوجد حساب "كامل الباقي" → ضيف الباقي على آخر سطر
                UPDATE GFC.PAYMENT_BATCH_DETAIL_TB
                   SET TOTAL_AMOUNT = TOTAL_AMOUNT + V_REMAINING
                 WHERE BATCH_DETAIL_ID = (
                    SELECT MAX(BATCH_DETAIL_ID) FROM GFC.PAYMENT_BATCH_DETAIL_TB
                     WHERE BATCH_ID = V_BATCH_ID AND EMP_NO = E.EMP_NO
                 );
                V_REMAINING := 0;
              END IF;
            END;
          END IF;

          V_TOTAL_AMT := V_TOTAL_AMT + E.TOTAL_AMOUNT;
          V_EMP_CNT := V_EMP_CNT + 1;
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
  BEGIN
    SELECT STATUS, BATCH_NO INTO V_ST, V_NO
      FROM GFC.PAYMENT_BATCH_TB WHERE BATCH_ID = P_BATCH_ID FOR UPDATE NOWAIT;
    IF V_ST = 2 THEN P_MSG_OUT := 'لا يمكن فك دفعة مصروفة — استخدم عكس الصرف'; RETURN; END IF;

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

    DELETE FROM GFC.PAYMENT_BATCH_LINK_TB   WHERE BATCH_ID = P_BATCH_ID;
    DELETE FROM GFC.PAYMENT_BATCH_DETAIL_TB WHERE BATCH_ID = P_BATCH_ID;
    DELETE FROM GFC.PAYMENT_BATCH_TB        WHERE BATCH_ID = P_BATCH_ID;
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
             (SELECT COUNT(1) FROM GFC.PAYMENT_BATCH_LINK_TB L WHERE L.BATCH_ID = B.BATCH_ID) AS MOVEMENT_COUNT,
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
    OPEN P_REF_CUR_OUT FOR
      SELECT MIN(BD.BATCH_DETAIL_ID)                                  AS BATCH_DETAIL_ID,
             BD.BATCH_ID,
             BD.EMP_NO,
             MAX(E.NAME)                                              AS EMP_NAME,
             MAX(EMP_PKG.GET_EMP_BRANCH_NAME(BD.EMP_NO))              AS BRANCH_NAME,
             MIN(BD.BANK_NO)                                          AS BANK_NO,
             -- اسم البنك: لو في أكثر من حساب، نعرض كلهم مفصولين بـ "/"
             (SELECT LISTAGG(BANK_LBL, ' / ') WITHIN GROUP (ORDER BY BANK_LBL)
                FROM (SELECT DISTINCT NVL(SNAP_PROVIDER_NAME,
                                          DISBURSEMENT_PKG.GET_BANK_NAME(BANK_NO)) AS BANK_LBL
                        FROM GFC.PAYMENT_BATCH_DETAIL_TB
                       WHERE BATCH_ID = BD.BATCH_ID AND EMP_NO = BD.EMP_NO))      AS BANK_NAME,
             MAX(DISBURSEMENT_PKG.GET_MASTER_BANK_NAME(BD.EMP_NO))    AS MASTER_BANK_NAME,
             SUM(BD.TOTAL_AMOUNT)                                     AS TOTAL_AMOUNT,
             MIN(BD.MASTER_BANK_NO)                                   AS MASTER_BANK_NO,
             MAX(EMP_PKG.GET_EMP_BRANCH(BD.EMP_NO))                   AS EMP_BRANCH_NO,
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
        JOIN DATA.EMPLOYEES E ON E.NO = BD.EMP_NO
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
  -- BATCH BANK EXPORT — تصدير بيانات الدفعة للبنك
  -- ============================================================
  PROCEDURE BATCH_BANK_EXPORT (
      P_BATCH_ID NUMBER,
      P_MASTER_BANK_NO NUMBER DEFAULT NULL,
      P_REF_CUR_OUT OUT SYS_REFCURSOR, P_MSG_OUT OUT VARCHAR2
  ) IS
  BEGIN
    OPEN P_REF_CUR_OUT FOR
      SELECT * FROM GFC.PAYMENT_BATCH_BANK_VW
       WHERE BATCH_ID = P_BATCH_ID
         AND (P_MASTER_BANK_NO <= 0 OR MASTER_BANK_NO = P_MASTER_BANK_NO)
       ORDER BY MASTER_BANK_NO, BANK_NO, EMP_NO;
    P_MSG_OUT := '1';
  EXCEPTION WHEN OTHERS THEN P_MSG_OUT := SQLERRM;
  END;


END DISBURSEMENT_BATCH_PKG;
/
