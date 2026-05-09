CREATE OR REPLACE PACKAGE BODY GFC_PAK.PAYMENT_ACCOUNTS_PKG AS
-- ============================================================
-- Payment Accounts Package — Body
-- ============================================================


  -- ============================================================
  -- 1) Providers
  -- ============================================================

  PROCEDURE PROVIDERS_LIST (
      P_TYPE        NUMBER DEFAULT NULL,
      P_REF_CUR_OUT OUT SYS_REFCURSOR,
      P_MSG_OUT     OUT VARCHAR2
  ) IS
  BEGIN
    OPEN P_REF_CUR_OUT FOR
      SELECT PROVIDER_ID, PROVIDER_TYPE, PROVIDER_NAME, PROVIDER_CODE,
             LEGACY_BANK_NO, EXPORT_FORMAT,
             COMPANY_ACCOUNT_NO, COMPANY_ACCOUNT_ID, COMPANY_IBAN,
             REQUIRES_IBAN, REQUIRES_ID, REQUIRES_PHONE,
             IS_ACTIVE, SORT_ORDER, NOTES,
             (SELECT COUNT(*) FROM GFC.PAYMENT_BANK_BRANCHES_TB B
               WHERE B.PROVIDER_ID = P.PROVIDER_ID AND B.STATUS = 1) AS BRANCH_COUNT,
             (SELECT COUNT(*) FROM GFC.PAYMENT_ACCOUNTS_TB A
               WHERE A.PROVIDER_ID = P.PROVIDER_ID AND A.STATUS = 1) AS ACCOUNT_COUNT
        FROM GFC.PAYMENT_PROVIDERS_TB P
       WHERE (P_TYPE IS NULL OR PROVIDER_TYPE = P_TYPE)
       ORDER BY SORT_ORDER, PROVIDER_NAME;
    P_MSG_OUT := '1';
  EXCEPTION WHEN OTHERS THEN P_MSG_OUT := SQLERRM;
  END;

  PROCEDURE PROVIDER_GET (
      P_PROVIDER_ID NUMBER,
      P_REF_CUR_OUT OUT SYS_REFCURSOR,
      P_MSG_OUT     OUT VARCHAR2
  ) IS
  BEGIN
    OPEN P_REF_CUR_OUT FOR
      SELECT * FROM GFC.PAYMENT_PROVIDERS_TB WHERE PROVIDER_ID = P_PROVIDER_ID;
    P_MSG_OUT := '1';
  EXCEPTION WHEN OTHERS THEN P_MSG_OUT := SQLERRM;
  END;

  PROCEDURE PROVIDER_INSERT (
      P_TYPE           NUMBER, P_NAME VARCHAR2, P_CODE VARCHAR2,
      P_COMPANY_ACC_NO VARCHAR2, P_COMPANY_ACC_ID VARCHAR2, P_COMPANY_IBAN VARCHAR2,
      P_EXPORT_FORMAT  NUMBER,  P_SORT_ORDER NUMBER,
      P_MSG_OUT        OUT VARCHAR2
  ) IS
    V_ID NUMBER;
  BEGIN
    IF P_TYPE NOT IN (C_PROVIDER_TYPE_BANK, C_PROVIDER_TYPE_WALLET) THEN
      P_MSG_OUT := 'نوع المزود غير صحيح'; RETURN;
    END IF;
    IF P_NAME IS NULL OR LENGTH(TRIM(P_NAME)) = 0 THEN
      P_MSG_OUT := 'اسم المزود مطلوب'; RETURN;
    END IF;

    V_ID := GFC_PAK.PAYMENT_PROVIDERS_SEQ.NEXTVAL;
    INSERT INTO GFC.PAYMENT_PROVIDERS_TB (
      PROVIDER_ID, PROVIDER_TYPE, PROVIDER_NAME, PROVIDER_CODE,
      COMPANY_ACCOUNT_NO, COMPANY_ACCOUNT_ID, COMPANY_IBAN,
      EXPORT_FORMAT, SORT_ORDER,
      REQUIRES_IBAN, REQUIRES_ID, REQUIRES_PHONE,
      IS_ACTIVE, ENTRY_USER, ENTRY_DATE
    ) VALUES (
      V_ID, P_TYPE, P_NAME, P_CODE,
      P_COMPANY_ACC_NO, P_COMPANY_ACC_ID, P_COMPANY_IBAN,
      P_EXPORT_FORMAT, NVL(P_SORT_ORDER, 999),
      CASE P_TYPE WHEN 1 THEN 1 ELSE 0 END, 1,
      CASE P_TYPE WHEN 2 THEN 1 ELSE 0 END,
      1, USER_PKG.GET_USER_ID, SYSDATE
    );
    P_MSG_OUT := TO_CHAR(V_ID);
  EXCEPTION WHEN OTHERS THEN P_MSG_OUT := SQLERRM;
  END;

  PROCEDURE PROVIDER_UPDATE (
      P_PROVIDER_ID    NUMBER,
      P_NAME           VARCHAR2, P_CODE VARCHAR2,
      P_COMPANY_ACC_NO VARCHAR2, P_COMPANY_ACC_ID VARCHAR2, P_COMPANY_IBAN VARCHAR2,
      P_EXPORT_FORMAT  NUMBER,  P_SORT_ORDER NUMBER,
      P_IS_ACTIVE      NUMBER,
      P_MSG_OUT        OUT VARCHAR2
  ) IS
  BEGIN
    UPDATE GFC.PAYMENT_PROVIDERS_TB SET
      PROVIDER_NAME      = P_NAME,
      PROVIDER_CODE      = P_CODE,
      COMPANY_ACCOUNT_NO = P_COMPANY_ACC_NO,
      COMPANY_ACCOUNT_ID = P_COMPANY_ACC_ID,
      COMPANY_IBAN       = P_COMPANY_IBAN,
      EXPORT_FORMAT      = P_EXPORT_FORMAT,
      SORT_ORDER         = P_SORT_ORDER,
      IS_ACTIVE          = NVL(P_IS_ACTIVE, 1),
      UPDATE_USER        = USER_PKG.GET_USER_ID,
      UPDATE_DATE        = SYSDATE
     WHERE PROVIDER_ID = P_PROVIDER_ID;

    IF SQL%ROWCOUNT = 0 THEN P_MSG_OUT := 'المزود غير موجود'; RETURN; END IF;
    P_MSG_OUT := '1';
  EXCEPTION WHEN OTHERS THEN P_MSG_OUT := SQLERRM;
  END;

  -- حذف نهائي للمزود (يفشل لو في حسابات/فروع نشطة)
  PROCEDURE PROVIDER_DELETE (
      P_PROVIDER_ID NUMBER,
      P_MSG_OUT     OUT VARCHAR2
  ) IS
    V_ACC_CNT NUMBER;
    V_BR_CNT  NUMBER;
  BEGIN
    SELECT COUNT(*) INTO V_ACC_CNT
      FROM GFC.PAYMENT_ACCOUNTS_TB
     WHERE PROVIDER_ID = P_PROVIDER_ID AND STATUS = 1;
    IF V_ACC_CNT > 0 THEN
      P_MSG_OUT := 'لا يمكن الحذف: المزود مرتبط بـ ' || V_ACC_CNT || ' حساب موظف';
      RETURN;
    END IF;

    SELECT COUNT(*) INTO V_BR_CNT
      FROM GFC.PAYMENT_BANK_BRANCHES_TB
     WHERE PROVIDER_ID = P_PROVIDER_ID AND STATUS = 1;
    IF V_BR_CNT > 0 THEN
      P_MSG_OUT := 'لا يمكن الحذف: المزود عنده ' || V_BR_CNT || ' فرع، احذف الفروع أولاً';
      RETURN;
    END IF;

    DELETE FROM GFC.PAYMENT_PROVIDERS_TB WHERE PROVIDER_ID = P_PROVIDER_ID;
    IF SQL%ROWCOUNT = 0 THEN P_MSG_OUT := 'المزود غير موجود'; RETURN; END IF;
    COMMIT;
    P_MSG_OUT := '1';
  EXCEPTION WHEN OTHERS THEN P_MSG_OUT := SQLERRM;
  END;

  -- تفعيل/إيقاف المزود
  PROCEDURE PROVIDER_TOGGLE_ACTIVE (
      P_PROVIDER_ID NUMBER,
      P_IS_ACTIVE   NUMBER,
      P_MSG_OUT     OUT VARCHAR2
  ) IS
  BEGIN
    UPDATE GFC.PAYMENT_PROVIDERS_TB
       SET IS_ACTIVE   = P_IS_ACTIVE,
           UPDATE_DATE = SYSDATE
     WHERE PROVIDER_ID = P_PROVIDER_ID;
    IF SQL%ROWCOUNT = 0 THEN P_MSG_OUT := 'المزود غير موجود'; RETURN; END IF;
    COMMIT;
    P_MSG_OUT := '1';
  EXCEPTION WHEN OTHERS THEN P_MSG_OUT := SQLERRM;
  END;

  -- قائمة الموظفين الذين عندهم حساب عند هذا المزود
  PROCEDURE PROVIDER_ACCOUNTS_LIST (
      P_PROVIDER_ID NUMBER,
      P_ONLY_ACTIVE NUMBER DEFAULT 1,
      P_REF_CUR_OUT OUT SYS_REFCURSOR,
      P_MSG_OUT     OUT VARCHAR2
  ) IS
  BEGIN
    OPEN P_REF_CUR_OUT FOR
      SELECT A.ACC_ID,
             A.EMP_NO,
             E.NAME                            AS EMP_NAME,
             EMP_PKG.GET_EMP_BRANCH_NAME(E.NO) AS BRANCH_NAME,
             A.ACCOUNT_NO,
             A.IBAN,
             A.WALLET_NUMBER,
             A.OWNER_NAME,
             A.IS_DEFAULT,
             A.IS_ACTIVE,
             BR.BRANCH_NAME                    AS BANK_BRANCH_NAME
        FROM GFC.PAYMENT_ACCOUNTS_TB A
        LEFT JOIN DATA.EMPLOYEES E              ON E.NO = A.EMP_NO
        LEFT JOIN GFC.PAYMENT_BANK_BRANCHES_TB BR ON BR.BRANCH_ID = A.BRANCH_ID
       WHERE A.PROVIDER_ID = P_PROVIDER_ID
         AND A.STATUS = 1
         AND (NVL(P_ONLY_ACTIVE, 0) = 0 OR A.IS_ACTIVE = 1)
       ORDER BY A.IS_ACTIVE DESC, A.EMP_NO;
    P_MSG_OUT := '1';
  EXCEPTION WHEN OTHERS THEN P_MSG_OUT := SQLERRM;
  END;

  -- عمليات جماعية على المزودين
  PROCEDURE PROVIDERS_BULK_ACTION (
      P_IDS_CSV  VARCHAR2,
      P_ACTION   VARCHAR2,
      P_OK_OUT   OUT NUMBER,
      P_FAIL_OUT OUT NUMBER,
      P_MSG_OUT  OUT VARCHAR2
  ) IS
    V_OK     NUMBER := 0;
    V_FAIL   NUMBER := 0;
    V_MSG    VARCHAR2(4000);
    V_ID     NUMBER;
    V_ACTIVE NUMBER;
  BEGIN
    IF P_IDS_CSV IS NULL OR LENGTH(TRIM(P_IDS_CSV)) = 0 THEN
      P_OK_OUT := 0; P_FAIL_OUT := 0;
      P_MSG_OUT := 'لم يتم تحديد أي مزود';
      RETURN;
    END IF;

    IF P_ACTION NOT IN ('ACTIVATE', 'DEACTIVATE', 'DELETE') THEN
      P_OK_OUT := 0; P_FAIL_OUT := 0;
      P_MSG_OUT := 'إجراء غير صالح: ' || P_ACTION;
      RETURN;
    END IF;

    FOR R IN (
      SELECT TRIM(REGEXP_SUBSTR(P_IDS_CSV, '[^,]+', 1, LEVEL)) AS ID_VAL
        FROM DUAL
      CONNECT BY REGEXP_SUBSTR(P_IDS_CSV, '[^,]+', 1, LEVEL) IS NOT NULL
    ) LOOP
      BEGIN
        V_ID := TO_NUMBER(R.ID_VAL);

        IF P_ACTION = 'ACTIVATE' THEN
          PROVIDER_TOGGLE_ACTIVE(V_ID, 1, V_MSG);
        ELSIF P_ACTION = 'DEACTIVATE' THEN
          PROVIDER_TOGGLE_ACTIVE(V_ID, 0, V_MSG);
        ELSE
          PROVIDER_DELETE(V_ID, V_MSG);
        END IF;

        IF V_MSG = '1' THEN V_OK := V_OK + 1;
        ELSE                V_FAIL := V_FAIL + 1;
        END IF;
      EXCEPTION WHEN OTHERS THEN V_FAIL := V_FAIL + 1;
      END;
    END LOOP;

    P_OK_OUT   := V_OK;
    P_FAIL_OUT := V_FAIL;
    P_MSG_OUT  := '1';
  EXCEPTION WHEN OTHERS THEN
    P_OK_OUT := 0; P_FAIL_OUT := 0;
    P_MSG_OUT := SQLERRM;
  END;


  -- قائمة المزودين بـ pagination + filters
  PROCEDURE PROVIDERS_LIST_PAGINATED (
      P_TYPE        NUMBER   DEFAULT NULL,
      P_IS_ACTIVE   NUMBER   DEFAULT NULL,
      P_SEARCH      VARCHAR2 DEFAULT NULL,
      P_OFFSET      NUMBER   DEFAULT 0,
      P_LIMIT       NUMBER   DEFAULT 50,
      P_REF_CUR_OUT OUT SYS_REFCURSOR,
      P_MSG_OUT     OUT VARCHAR2
  ) IS
    V_OFFSET NUMBER := NVL(P_OFFSET, 0);
    V_LIMIT  NUMBER := NVL(P_LIMIT, 50);
    V_S      VARCHAR2(200) := NULLIF(TRIM(P_SEARCH), '');
  BEGIN
    OPEN P_REF_CUR_OUT FOR
      SELECT *
        FROM (
          SELECT P.PROVIDER_ID,
                 P.PROVIDER_TYPE,
                 P.PROVIDER_NAME,
                 P.PROVIDER_CODE,
                 P.LEGACY_BANK_NO,
                 P.EXPORT_FORMAT,
                 P.COMPANY_ACCOUNT_NO,
                 P.COMPANY_ACCOUNT_ID,
                 P.COMPANY_IBAN,
                 P.REQUIRES_IBAN,
                 P.REQUIRES_ID,
                 P.REQUIRES_PHONE,
                 P.IS_ACTIVE,
                 P.SORT_ORDER,
                 P.NOTES,
                 (SELECT COUNT(*) FROM GFC.PAYMENT_BANK_BRANCHES_TB B
                   WHERE B.PROVIDER_ID = P.PROVIDER_ID AND B.STATUS = 1) AS BRANCH_COUNT,
                 (SELECT COUNT(*) FROM GFC.PAYMENT_ACCOUNTS_TB A
                   WHERE A.PROVIDER_ID = P.PROVIDER_ID AND A.STATUS = 1) AS ACCOUNT_COUNT,
                 ROW_NUMBER() OVER (ORDER BY P.SORT_ORDER, P.PROVIDER_NAME) AS RN
            FROM GFC.PAYMENT_PROVIDERS_TB P
           WHERE (P_TYPE      IS NULL OR P.PROVIDER_TYPE = P_TYPE)
             AND (P_IS_ACTIVE IS NULL OR NVL(P.IS_ACTIVE, 0) = P_IS_ACTIVE)
             AND (V_S         IS NULL
                  OR UPPER(P.PROVIDER_NAME) LIKE '%' || UPPER(V_S) || '%'
                  OR UPPER(P.PROVIDER_CODE) LIKE '%' || UPPER(V_S) || '%')
        )
       WHERE RN BETWEEN V_OFFSET + 1 AND V_OFFSET + V_LIMIT
       ORDER BY RN;
    P_MSG_OUT := '1';
  EXCEPTION WHEN OTHERS THEN P_MSG_OUT := SQLERRM;
  END;

  PROCEDURE PROVIDERS_COUNT (
      P_TYPE        NUMBER   DEFAULT NULL,
      P_IS_ACTIVE   NUMBER   DEFAULT NULL,
      P_SEARCH      VARCHAR2 DEFAULT NULL,
      P_CNT_OUT     OUT NUMBER,
      P_MSG_OUT     OUT VARCHAR2
  ) IS
    V_S VARCHAR2(200) := NULLIF(TRIM(P_SEARCH), '');
  BEGIN
    SELECT COUNT(*) INTO P_CNT_OUT
      FROM GFC.PAYMENT_PROVIDERS_TB P
     WHERE (P_TYPE      IS NULL OR P.PROVIDER_TYPE = P_TYPE)
       AND (P_IS_ACTIVE IS NULL OR NVL(P.IS_ACTIVE, 0) = P_IS_ACTIVE)
       AND (V_S         IS NULL
            OR UPPER(P.PROVIDER_NAME) LIKE '%' || UPPER(V_S) || '%'
            OR UPPER(P.PROVIDER_CODE) LIKE '%' || UPPER(V_S) || '%');
    P_MSG_OUT := '1';
  EXCEPTION WHEN OTHERS THEN
    P_CNT_OUT := 0;
    P_MSG_OUT := SQLERRM;
  END;

  PROCEDURE PROVIDERS_TOTALS (
      P_TYPE        NUMBER   DEFAULT NULL,
      P_IS_ACTIVE   NUMBER   DEFAULT NULL,
      P_SEARCH      VARCHAR2 DEFAULT NULL,
      P_TOTAL_OUT     OUT NUMBER,
      P_BANKS_OUT     OUT NUMBER,
      P_WALLETS_OUT   OUT NUMBER,
      P_INACTIVE_OUT  OUT NUMBER,
      P_MSG_OUT       OUT VARCHAR2
  ) IS
    V_S VARCHAR2(200) := NULLIF(TRIM(P_SEARCH), '');
  BEGIN
    SELECT COUNT(*),
           SUM(CASE WHEN P.PROVIDER_TYPE = 1 THEN 1 ELSE 0 END),
           SUM(CASE WHEN P.PROVIDER_TYPE = 2 THEN 1 ELSE 0 END),
           SUM(CASE WHEN NVL(P.IS_ACTIVE, 0) = 0 THEN 1 ELSE 0 END)
      INTO P_TOTAL_OUT, P_BANKS_OUT, P_WALLETS_OUT, P_INACTIVE_OUT
      FROM GFC.PAYMENT_PROVIDERS_TB P
     WHERE (P_TYPE      IS NULL OR P.PROVIDER_TYPE = P_TYPE)
       AND (P_IS_ACTIVE IS NULL OR NVL(P.IS_ACTIVE, 0) = P_IS_ACTIVE)
       AND (V_S         IS NULL
            OR UPPER(P.PROVIDER_NAME) LIKE '%' || UPPER(V_S) || '%'
            OR UPPER(P.PROVIDER_CODE) LIKE '%' || UPPER(V_S) || '%');
    P_MSG_OUT := '1';
  EXCEPTION WHEN OTHERS THEN
    P_TOTAL_OUT := 0; P_BANKS_OUT := 0; P_WALLETS_OUT := 0; P_INACTIVE_OUT := 0;
    P_MSG_OUT := SQLERRM;
  END;


  -- ============================================================
  -- 2) Bank Branches
  -- ============================================================

  PROCEDURE BRANCHES_LIST (
      P_PROVIDER_ID NUMBER DEFAULT NULL,
      P_REF_CUR_OUT OUT SYS_REFCURSOR,
      P_MSG_OUT     OUT VARCHAR2
  ) IS
  BEGIN
    OPEN P_REF_CUR_OUT FOR
      SELECT B.BRANCH_ID, B.PROVIDER_ID, P.PROVIDER_NAME,
             B.BRANCH_NAME, B.PRINT_NO,
             B.ADDRESS, B.PHONE1, B.PHONE2, B.FAX,
             B.LEGACY_BANK_NO, B.LEGACY_MASTER,
             B.IS_ACTIVE, B.STATUS
        FROM GFC.PAYMENT_BANK_BRANCHES_TB B
        LEFT JOIN GFC.PAYMENT_PROVIDERS_TB P ON P.PROVIDER_ID = B.PROVIDER_ID
       WHERE B.STATUS = 1
         AND (P_PROVIDER_ID IS NULL OR B.PROVIDER_ID = P_PROVIDER_ID)
       ORDER BY P.PROVIDER_NAME, B.LEGACY_BANK_NO NULLS LAST, B.BRANCH_NAME;
    P_MSG_OUT := '1';
  EXCEPTION WHEN OTHERS THEN P_MSG_OUT := SQLERRM;
  END;

  PROCEDURE BRANCH_GET (
      P_BRANCH_ID   NUMBER,
      P_REF_CUR_OUT OUT SYS_REFCURSOR,
      P_MSG_OUT     OUT VARCHAR2
  ) IS
  BEGIN
    OPEN P_REF_CUR_OUT FOR
      SELECT * FROM GFC.PAYMENT_BANK_BRANCHES_TB WHERE BRANCH_ID = P_BRANCH_ID;
    P_MSG_OUT := '1';
  EXCEPTION WHEN OTHERS THEN P_MSG_OUT := SQLERRM;
  END;

  PROCEDURE BRANCH_INSERT (
      P_PROVIDER_ID NUMBER, P_NAME VARCHAR2, P_LEGACY_BANK_NO NUMBER, P_PRINT_NO NUMBER,
      P_ADDRESS VARCHAR2, P_PHONE1 VARCHAR2, P_PHONE2 VARCHAR2, P_FAX VARCHAR2,
      P_MSG_OUT OUT VARCHAR2
  ) IS
    V_ID    NUMBER;
    V_DUP   NUMBER;
  BEGIN
    IF P_NAME IS NULL OR LENGTH(TRIM(P_NAME)) = 0 THEN
      P_MSG_OUT := 'اسم الفرع مطلوب'; RETURN;
    END IF;
    IF P_LEGACY_BANK_NO IS NULL OR P_LEGACY_BANK_NO <= 0 THEN
      P_MSG_OUT := 'رقم الفرع مطلوب'; RETURN;
    END IF;

    -- فحص التكرار: LEGACY_BANK_NO عليه UK_PAY_BRANCH_LEG (unique عام)
    SELECT COUNT(*) INTO V_DUP
      FROM GFC.PAYMENT_BANK_BRANCHES_TB
     WHERE LEGACY_BANK_NO = P_LEGACY_BANK_NO;
    IF V_DUP > 0 THEN
      P_MSG_OUT := 'رقم الفرع ' || P_LEGACY_BANK_NO || ' مستخدم لفرع آخر';
      RETURN;
    END IF;

    V_ID := GFC_PAK.PAYMENT_BANK_BRANCHES_SEQ.NEXTVAL;
    INSERT INTO GFC.PAYMENT_BANK_BRANCHES_TB (
      BRANCH_ID, PROVIDER_ID, BRANCH_NAME, LEGACY_BANK_NO, PRINT_NO,
      ADDRESS, PHONE1, PHONE2, FAX,
      IS_ACTIVE, STATUS, ENTRY_USER, ENTRY_DATE
    ) VALUES (
      V_ID, P_PROVIDER_ID, P_NAME, P_LEGACY_BANK_NO, P_PRINT_NO,
      P_ADDRESS, P_PHONE1, P_PHONE2, P_FAX,
      1, 1, USER_PKG.GET_USER_ID, SYSDATE
    );
    P_MSG_OUT := TO_CHAR(V_ID);
  EXCEPTION WHEN OTHERS THEN P_MSG_OUT := SQLERRM;
  END;

  PROCEDURE BRANCH_UPDATE (
      P_BRANCH_ID   NUMBER,
      P_PROVIDER_ID NUMBER, P_NAME VARCHAR2, P_LEGACY_BANK_NO NUMBER, P_PRINT_NO NUMBER,
      P_ADDRESS VARCHAR2, P_PHONE1 VARCHAR2, P_PHONE2 VARCHAR2, P_FAX VARCHAR2,
      P_IS_ACTIVE   NUMBER,
      P_MSG_OUT     OUT VARCHAR2
  ) IS
    V_DUP NUMBER;
  BEGIN
    IF P_NAME IS NULL OR LENGTH(TRIM(P_NAME)) = 0 THEN
      P_MSG_OUT := 'اسم الفرع مطلوب'; RETURN;
    END IF;
    IF P_LEGACY_BANK_NO IS NULL OR P_LEGACY_BANK_NO <= 0 THEN
      P_MSG_OUT := 'رقم الفرع مطلوب'; RETURN;
    END IF;

    -- فحص التكرار: استثناء الفرع الحالي
    SELECT COUNT(*) INTO V_DUP
      FROM GFC.PAYMENT_BANK_BRANCHES_TB
     WHERE LEGACY_BANK_NO = P_LEGACY_BANK_NO
       AND BRANCH_ID     <> P_BRANCH_ID;
    IF V_DUP > 0 THEN
      P_MSG_OUT := 'رقم الفرع ' || P_LEGACY_BANK_NO || ' مستخدم لفرع آخر';
      RETURN;
    END IF;

    UPDATE GFC.PAYMENT_BANK_BRANCHES_TB SET
      PROVIDER_ID    = P_PROVIDER_ID,
      BRANCH_NAME    = P_NAME,
      LEGACY_BANK_NO = P_LEGACY_BANK_NO,
      PRINT_NO       = P_PRINT_NO,
      ADDRESS        = P_ADDRESS,
      PHONE1         = P_PHONE1,
      PHONE2         = P_PHONE2,
      FAX            = P_FAX,
      IS_ACTIVE      = NVL(P_IS_ACTIVE, 1),
      UPDATE_USER    = USER_PKG.GET_USER_ID,
      UPDATE_DATE    = SYSDATE
     WHERE BRANCH_ID = P_BRANCH_ID;

    IF SQL%ROWCOUNT = 0 THEN P_MSG_OUT := 'الفرع غير موجود'; RETURN; END IF;
    P_MSG_OUT := '1';
  EXCEPTION WHEN OTHERS THEN P_MSG_OUT := SQLERRM;
  END;

  PROCEDURE BRANCH_LINK_PROVIDER (
      P_BRANCH_ID   NUMBER, P_PROVIDER_ID NUMBER,
      P_MSG_OUT     OUT VARCHAR2
  ) IS
  BEGIN
    UPDATE GFC.PAYMENT_BANK_BRANCHES_TB SET
      PROVIDER_ID = P_PROVIDER_ID,
      UPDATE_USER = USER_PKG.GET_USER_ID,
      UPDATE_DATE = SYSDATE
     WHERE BRANCH_ID = P_BRANCH_ID;
    P_MSG_OUT := CASE WHEN SQL%ROWCOUNT = 0 THEN 'الفرع غير موجود' ELSE '1' END;
  EXCEPTION WHEN OTHERS THEN P_MSG_OUT := SQLERRM;
  END;


  -- ============================================================
  -- 3) Beneficiaries
  -- ============================================================

  PROCEDURE BENEF_LIST (
      P_EMP_NO      NUMBER,
      P_REF_CUR_OUT OUT SYS_REFCURSOR,
      P_MSG_OUT     OUT VARCHAR2
  ) IS
  BEGIN
    OPEN P_REF_CUR_OUT FOR
      SELECT B.BENEFICIARY_ID, B.EMP_NO, B.REL_TYPE,
             CASE B.REL_TYPE
               WHEN 1 THEN 'زوجة' WHEN 2 THEN 'ابن' WHEN 3 THEN 'بنت'
               WHEN 4 THEN 'أب'   WHEN 5 THEN 'أم'  WHEN 9 THEN 'وريث آخر'
               ELSE TO_CHAR(B.REL_TYPE) END AS REL_NAME,
             B.ID_NO, B.NAME, B.PHONE,
             B.PCT_SHARE, B.FROM_DATE, B.TO_DATE,
             B.STATUS, B.NOTES,
             (SELECT COUNT(*) FROM GFC.PAYMENT_ACCOUNTS_TB A
               WHERE A.BENEFICIARY_ID = B.BENEFICIARY_ID AND A.STATUS = 1) AS ACCOUNTS_COUNT
        FROM GFC.PAYMENT_BENEFICIARIES_TB B
       WHERE B.EMP_NO = P_EMP_NO AND B.STATUS <> C_STATUS_DELETED
       ORDER BY B.REL_TYPE, B.NAME;
    P_MSG_OUT := '1';
  EXCEPTION WHEN OTHERS THEN P_MSG_OUT := SQLERRM;
  END;

  PROCEDURE BENEF_GET (
      P_BENEF_ID    NUMBER,
      P_REF_CUR_OUT OUT SYS_REFCURSOR,
      P_MSG_OUT     OUT VARCHAR2
  ) IS
  BEGIN
    OPEN P_REF_CUR_OUT FOR
      SELECT * FROM GFC.PAYMENT_BENEFICIARIES_TB WHERE BENEFICIARY_ID = P_BENEF_ID;
    P_MSG_OUT := '1';
  EXCEPTION WHEN OTHERS THEN P_MSG_OUT := SQLERRM;
  END;

  PROCEDURE BENEF_INSERT (
      P_EMP_NO    NUMBER, P_REL_TYPE NUMBER,
      P_ID_NO     VARCHAR2, P_NAME VARCHAR2, P_PHONE VARCHAR2,
      P_PCT_SHARE NUMBER,
      P_FROM_DATE DATE,    P_TO_DATE DATE,
      P_NOTES     VARCHAR2,
      P_MSG_OUT   OUT VARCHAR2
  ) IS
    V_ID NUMBER;
  BEGIN
    IF P_EMP_NO IS NULL THEN P_MSG_OUT := 'رقم الموظف مطلوب'; RETURN; END IF;
    IF P_NAME IS NULL OR LENGTH(TRIM(P_NAME)) = 0 THEN
      P_MSG_OUT := 'اسم المستفيد مطلوب'; RETURN;
    END IF;
    IF P_ID_NO IS NULL THEN P_MSG_OUT := 'رقم الهوية مطلوب'; RETURN; END IF;

    V_ID := GFC_PAK.PAYMENT_BENEFICIARIES_SEQ.NEXTVAL;
    INSERT INTO GFC.PAYMENT_BENEFICIARIES_TB (
      BENEFICIARY_ID, EMP_NO, REL_TYPE, ID_NO, NAME, PHONE,
      PCT_SHARE, FROM_DATE, TO_DATE,
      STATUS, NOTES, ENTRY_USER, ENTRY_DATE
    ) VALUES (
      V_ID, P_EMP_NO, P_REL_TYPE, P_ID_NO, P_NAME, P_PHONE,
      P_PCT_SHARE, NVL(P_FROM_DATE, SYSDATE), P_TO_DATE,
      1, P_NOTES, USER_PKG.GET_USER_ID, SYSDATE
    );
    P_MSG_OUT := TO_CHAR(V_ID);
  EXCEPTION WHEN OTHERS THEN P_MSG_OUT := SQLERRM;
  END;

  PROCEDURE BENEF_UPDATE (
      P_BENEF_ID  NUMBER, P_REL_TYPE NUMBER,
      P_ID_NO     VARCHAR2, P_NAME VARCHAR2, P_PHONE VARCHAR2,
      P_PCT_SHARE NUMBER,
      P_FROM_DATE DATE,    P_TO_DATE DATE,
      P_STATUS    NUMBER,  P_NOTES VARCHAR2,
      P_MSG_OUT   OUT VARCHAR2
  ) IS
  BEGIN
    UPDATE GFC.PAYMENT_BENEFICIARIES_TB SET
      REL_TYPE    = P_REL_TYPE,
      ID_NO       = P_ID_NO,
      NAME        = P_NAME,
      PHONE       = P_PHONE,
      PCT_SHARE   = P_PCT_SHARE,
      FROM_DATE   = P_FROM_DATE,
      TO_DATE     = P_TO_DATE,
      STATUS      = NVL(P_STATUS, 1),
      NOTES       = P_NOTES,
      UPDATE_USER = USER_PKG.GET_USER_ID,
      UPDATE_DATE = SYSDATE
     WHERE BENEFICIARY_ID = P_BENEF_ID;

    IF SQL%ROWCOUNT = 0 THEN P_MSG_OUT := 'المستفيد غير موجود'; RETURN; END IF;
    P_MSG_OUT := '1';
  EXCEPTION WHEN OTHERS THEN P_MSG_OUT := SQLERRM;
  END;

  PROCEDURE BENEF_DELETE (
      P_BENEF_ID NUMBER,
      P_MSG_OUT  OUT VARCHAR2
  ) IS
    V_ACC_CNT NUMBER;
  BEGIN
    -- تحقق: لا تحذف مستفيد عنده حسابات نشطة
    SELECT COUNT(*) INTO V_ACC_CNT FROM GFC.PAYMENT_ACCOUNTS_TB
     WHERE BENEFICIARY_ID = P_BENEF_ID AND STATUS = 1;
    IF V_ACC_CNT > 0 THEN
      P_MSG_OUT := 'المستفيد عنده ' || V_ACC_CNT || ' حساب نشط — احذف الحسابات أولاً';
      RETURN;
    END IF;

    UPDATE GFC.PAYMENT_BENEFICIARIES_TB SET
      STATUS = C_STATUS_DELETED,
      UPDATE_USER = USER_PKG.GET_USER_ID,
      UPDATE_DATE = SYSDATE
     WHERE BENEFICIARY_ID = P_BENEF_ID;

    P_MSG_OUT := CASE WHEN SQL%ROWCOUNT = 0 THEN 'المستفيد غير موجود' ELSE '1' END;
  EXCEPTION WHEN OTHERS THEN P_MSG_OUT := SQLERRM;
  END;


  -- ============================================================
  -- 4) Accounts
  -- ============================================================

  PROCEDURE ACCOUNTS_LIST (
      P_EMP_NO          NUMBER,
      P_ONLY_ACTIVE     NUMBER DEFAULT 0,
      P_REF_CUR_OUT     OUT SYS_REFCURSOR,
      P_MSG_OUT         OUT VARCHAR2
  ) IS
  BEGIN
    OPEN P_REF_CUR_OUT FOR
      SELECT A.ACC_ID, A.EMP_NO, A.BENEFICIARY_ID,
             A.PROVIDER_ID, P.PROVIDER_NAME, P.PROVIDER_TYPE,
             P.REQUIRES_IBAN, P.REQUIRES_PHONE,
             A.BRANCH_ID, BR.BRANCH_NAME, BR.LEGACY_BANK_NO,
             A.ACCOUNT_NO, A.IBAN, A.WALLET_NUMBER,
             A.OWNER_ID_NO, A.OWNER_NAME, A.OWNER_PHONE,
             A.IS_DEFAULT, A.SPLIT_TYPE, A.SPLIT_VALUE, A.SPLIT_ORDER,
             A.IS_ACTIVE, A.INACTIVE_REASON, A.INACTIVE_FROM_MONTH,
             A.FROM_DATE, A.TO_DATE, A.NOTES,
             -- بيانات المستفيد إن وُجد
             B.NAME AS BENEFICIARY_NAME, B.REL_TYPE AS BENEFICIARY_REL
        FROM GFC.PAYMENT_ACCOUNTS_TB A
        JOIN GFC.PAYMENT_PROVIDERS_TB P ON P.PROVIDER_ID = A.PROVIDER_ID
        LEFT JOIN GFC.PAYMENT_BANK_BRANCHES_TB BR ON BR.BRANCH_ID = A.BRANCH_ID
        LEFT JOIN GFC.PAYMENT_BENEFICIARIES_TB B ON B.BENEFICIARY_ID = A.BENEFICIARY_ID
       WHERE A.EMP_NO = P_EMP_NO
         AND A.STATUS = 1
         AND (NVL(P_ONLY_ACTIVE, 0) = 0 OR A.IS_ACTIVE = 1)
       ORDER BY A.BENEFICIARY_ID NULLS FIRST, A.SPLIT_ORDER, A.ACC_ID;
    P_MSG_OUT := '1';
  EXCEPTION WHEN OTHERS THEN P_MSG_OUT := SQLERRM;
  END;


  -- ============================================================
  -- ACCOUNTS_PREVIEW_BY_REQ — معاينة توزيع كل موظفي طلب صرف
  -- يرجع لكل (موظف+حساب) سطر مع ALLOC_AMOUNT المتوقّع بناءً على REQ_AMOUNT
  --
  -- يشمل أيضاً (لكل صف):
  --   • حالة الموظف (EMP_IS_ACTIVE: 1=نشط، 0=متقاعد)
  --   • المستفيدين الكليين / المربوطين بحساب
  --   • الحسابات المجمدة (count + سبب)
  --   • سبب تجميد الحساب الحالي (لو الصف لحساب موقوف)
  --
  -- ⚡ Optimized: يفلتر EMP_NO مبكراً لتفادي scan كامل للجداول
  -- ============================================================
  PROCEDURE ACCOUNTS_PREVIEW_BY_REQ (
      P_REQ_ID          NUMBER,
      P_REF_CUR_OUT     OUT SYS_REFCURSOR,
      P_MSG_OUT         OUT VARCHAR2
  ) IS
    V_THE_MONTH NUMBER;     -- 🆕 شهر الطلب — لقراءة الحالة التاريخية للموظف
  BEGIN
    -- نقرأ شهر الطلب لاستخدامه في GET_EMP_DISPLAY_STATUS (تجنّباً لخطأ "متقاعد" للحالة الحالية)
    BEGIN
      SELECT THE_MONTH INTO V_THE_MONTH FROM GFC.PAYMENT_REQ_TB WHERE REQ_ID = P_REQ_ID;
    EXCEPTION WHEN NO_DATA_FOUND THEN V_THE_MONTH := NULL; END;

    OPEN P_REF_CUR_OUT FOR
      WITH EMP_AMT AS (
        -- موظفي الطلب فقط (هذا CTE هو الـ filter الأساسي للجداول الأخرى)
        SELECT /*+ MATERIALIZE */ D.EMP_NO, NVL(D.REQ_AMOUNT, 0) AS REQ_AMOUNT
          FROM GFC.PAYMENT_REQ_DETAIL_TB D
         WHERE D.REQ_ID = P_REQ_ID
           AND D.DETAIL_STATUS <> 9   -- ليس ملغى
      ),
      EMP_INFO AS (
        -- بيانات الموظف (IS_ACTIVE فقط) — مع dedup ضمني عبر MAX
        -- نفلتر بـ EMP_NO IN (الطلب) لتفادي قراءة كل DATA.EMPLOYEES
        SELECT E.NO, MAX(E.IS_ACTIVE) AS IS_ACTIVE
          FROM DATA.EMPLOYEES E
         WHERE E.NO IN (SELECT EMP_NO FROM EMP_AMT)
         GROUP BY E.NO
      ),
      ACC_AGG AS (
        -- aggregates للحسابات — نفلتر بـ EMP_NO أولاً
        SELECT A.EMP_NO,
               COUNT(CASE WHEN A.IS_ACTIVE = 1 THEN A.ACC_ID END)                  AS ACC_CNT,
               COUNT(CASE WHEN A.IS_ACTIVE = 0 THEN A.ACC_ID END)                  AS INACTIVE_ACC_CNT,
               NVL(SUM(CASE WHEN A.IS_ACTIVE=1 AND A.SPLIT_TYPE=2 THEN A.SPLIT_VALUE ELSE 0 END), 0) AS FIXED_SUM,
               NVL(SUM(CASE WHEN A.IS_ACTIVE=1 AND A.SPLIT_TYPE=1 THEN A.SPLIT_VALUE ELSE 0 END), 0) AS PCT_SUM,
               SUM(CASE WHEN A.IS_ACTIVE=1 AND A.SPLIT_TYPE=3 THEN 1 ELSE 0 END)                    AS REST_CNT
          FROM GFC.PAYMENT_ACCOUNTS_TB A
         WHERE A.STATUS = 1
           AND A.EMP_NO IN (SELECT EMP_NO FROM EMP_AMT)
         GROUP BY A.EMP_NO
      ),
      BENEF_AGG AS (
        SELECT B.EMP_NO,
               COUNT(DISTINCT B.BENEFICIARY_ID)                                     AS BENEF_TOTAL,
               COUNT(DISTINCT CASE WHEN A.ACC_ID IS NOT NULL THEN B.BENEFICIARY_ID END) AS BENEF_LINKED
          FROM GFC.PAYMENT_BENEFICIARIES_TB B
          LEFT JOIN GFC.PAYMENT_ACCOUNTS_TB A
                 ON A.BENEFICIARY_ID = B.BENEFICIARY_ID AND A.STATUS = 1 AND A.IS_ACTIVE = 1
         WHERE B.STATUS = 1
           AND B.EMP_NO IN (SELECT EMP_NO FROM EMP_AMT)
         GROUP BY B.EMP_NO
      )
      SELECT EA.EMP_NO,
             EA.REQ_AMOUNT,
             NVL(ED.IS_ACTIVE, 0)                          AS EMP_IS_ACTIVE,
             -- الحالة المركّبة (للعرض): 1=فعّال, 0=متقاعد, 2=متوفى, 4=حساب مغلق
             DISBURSEMENT_PKG.GET_EMP_DISPLAY_STATUS(EA.EMP_NO, V_THE_MONTH) AS EMP_DISPLAY_STATUS,
             AA.ACC_CNT,
             NVL(AA.INACTIVE_ACC_CNT, 0)                   AS INACTIVE_ACC_CNT,
             NVL(BA.BENEF_TOTAL, 0)                        AS BENEF_TOTAL,
             NVL(BA.BENEF_LINKED, 0)                       AS BENEF_LINKED,
             -- 🔒 كشف التجاوز: مطابق منطق BATCH_CONFIRM (سطر 163-167)
             -- لو 1: الموظف يُستثنى تلقائياً عند الاعتماد، لا يأخذ ولا حركة
             CASE
               WHEN NVL(AA.FIXED_SUM, 0) > EA.REQ_AMOUNT                                        THEN 1
               WHEN NVL(AA.PCT_SUM, 0)   > 100                                                  THEN 1
               WHEN (NVL(AA.FIXED_SUM, 0) + ROUND(EA.REQ_AMOUNT * NVL(AA.PCT_SUM, 0) / 100, 2))
                    > EA.REQ_AMOUNT                                                             THEN 1
               ELSE 0
             END                                           AS IS_OVERAGE,
             -- 🆕 تفاصيل التوزيع — للعرض في الـ UI لو فيه تجاوز
             NVL(AA.FIXED_SUM, 0)                          AS OVERAGE_FIXED_SUM,    -- مجموع المبالغ الثابتة
             NVL(AA.PCT_SUM, 0)                            AS OVERAGE_PCT_SUM,      -- مجموع النسب %
             NVL(AA.REST_CNT, 0)                           AS OVERAGE_REST_CNT,     -- عدد حسابات "كامل المتبقي"
             -- 🆕 السبب المحدد للتجاوز: 1=مبالغ ثابتة، 2=نسب>100، 3=الكل معاً
             CASE
               WHEN NVL(AA.FIXED_SUM, 0) > EA.REQ_AMOUNT THEN 1
               WHEN NVL(AA.PCT_SUM, 0)   > 100           THEN 2
               WHEN (NVL(AA.FIXED_SUM, 0) + ROUND(EA.REQ_AMOUNT * NVL(AA.PCT_SUM, 0) / 100, 2))
                    > EA.REQ_AMOUNT                      THEN 3
               ELSE 0
             END                                           AS OVERAGE_CAUSE,
             A.ACC_ID,
             NVL(A.IS_ACTIVE, 1)                           AS ACC_IS_ACTIVE,
             A.INACTIVE_REASON,
             SETTING_PKG.CONSTANT_DETAILS_TB_GET_NAME(545, A.INACTIVE_REASON) AS INACTIVE_REASON_NAME,
             A.SPLIT_TYPE,
             A.SPLIT_VALUE,
             A.SPLIT_ORDER,
             A.IS_DEFAULT,
             A.IBAN,
             NVL(A.ACCOUNT_NO, A.WALLET_NUMBER)            AS ACCOUNT_NO,
             A.WALLET_NUMBER,
             A.OWNER_NAME,
             A.OWNER_ID_NO,
             A.BENEFICIARY_ID,
             B.NAME                                        AS BENEF_NAME,
             B.REL_TYPE                                    AS BENEF_REL_TYPE,
             CASE B.REL_TYPE
               WHEN 1 THEN 'زوجة' WHEN 2 THEN 'ابن'  WHEN 3 THEN 'بنت'
               WHEN 4 THEN 'أب'   WHEN 5 THEN 'أم'   WHEN 9 THEN 'وريث آخر'
               ELSE NULL
             END                                           AS BENEF_REL_NAME,
             P.PROVIDER_ID,
             P.PROVIDER_NAME,
             NVL(P.PROVIDER_TYPE, 1)                       AS PROVIDER_TYPE,
             BR.BRANCH_NAME                                AS PROV_BRANCH_NAME,
             -- 💡 المبلغ المرغوب (ما تقوله إعدادات التوزيع) — للعرض في الـ chip
             CASE
               WHEN A.ACC_ID IS NULL OR NVL(A.IS_ACTIVE, 0) = 0 THEN 0
               WHEN A.SPLIT_TYPE = 2 THEN NVL(A.SPLIT_VALUE, 0)
               WHEN A.SPLIT_TYPE = 1 THEN ROUND(EA.REQ_AMOUNT * NVL(A.SPLIT_VALUE, 0) / 100, 2)
               WHEN A.SPLIT_TYPE = 3 AND AA.REST_CNT > 0
                 THEN ROUND(GREATEST(EA.REQ_AMOUNT
                       - NVL(AA.FIXED_SUM, 0)
                       - ROUND(EA.REQ_AMOUNT * NVL(AA.PCT_SUM, 0) / 100, 2), 0) / AA.REST_CNT, 2)
               ELSE 0
             END                                           AS DESIRED_AMT,
             -- 🔒 المبلغ الفعلي الذي سيُصرف (مطابق سلوك BATCH_CONFIRM):
             --   • لو الحساب موقوف              → 0
             --   • لو الموظف فيه overage      → 0 (يُستثنى بالكامل)
             --   • غير ذلك                      → DESIRED_AMT (مضمون ≤ REQ)
             CASE
               WHEN A.ACC_ID IS NULL OR NVL(A.IS_ACTIVE, 0) = 0 THEN 0
               WHEN NVL(AA.FIXED_SUM, 0) > EA.REQ_AMOUNT                                        THEN 0
               WHEN NVL(AA.PCT_SUM, 0)   > 100                                                  THEN 0
               WHEN (NVL(AA.FIXED_SUM, 0) + ROUND(EA.REQ_AMOUNT * NVL(AA.PCT_SUM, 0) / 100, 2))
                    > EA.REQ_AMOUNT                                                             THEN 0
               WHEN A.SPLIT_TYPE = 2 THEN NVL(A.SPLIT_VALUE, 0)
               WHEN A.SPLIT_TYPE = 1 THEN ROUND(EA.REQ_AMOUNT * NVL(A.SPLIT_VALUE, 0) / 100, 2)
               WHEN A.SPLIT_TYPE = 3 AND AA.REST_CNT > 0
                 THEN ROUND(GREATEST(EA.REQ_AMOUNT
                       - NVL(AA.FIXED_SUM, 0)
                       - ROUND(EA.REQ_AMOUNT * NVL(AA.PCT_SUM, 0) / 100, 2), 0) / AA.REST_CNT, 2)
               ELSE 0
             END                                           AS ALLOC_AMOUNT,
             -- ترتيب: نشط ثابت → نشط نسبة → نشط باقي → موقوف
             CASE
               WHEN NVL(A.IS_ACTIVE, 1) = 0 THEN 8
               WHEN A.SPLIT_TYPE = 2 THEN 1
               WHEN A.SPLIT_TYPE = 1 THEN 2
               WHEN A.SPLIT_TYPE = 3 THEN 3
               ELSE 9
             END                                           AS SORT_TYPE
        FROM EMP_AMT EA
        LEFT JOIN EMP_INFO ED ON ED.NO = EA.EMP_NO
        LEFT JOIN ACC_AGG AA ON AA.EMP_NO = EA.EMP_NO
        LEFT JOIN BENEF_AGG BA ON BA.EMP_NO = EA.EMP_NO
        LEFT JOIN GFC.PAYMENT_ACCOUNTS_TB A
               ON A.EMP_NO = EA.EMP_NO AND A.STATUS = 1
        LEFT JOIN GFC.PAYMENT_BENEFICIARIES_TB B ON B.BENEFICIARY_ID = A.BENEFICIARY_ID
        LEFT JOIN GFC.PAYMENT_PROVIDERS_TB P     ON P.PROVIDER_ID    = A.PROVIDER_ID
        LEFT JOIN GFC.PAYMENT_BANK_BRANCHES_TB BR ON BR.BRANCH_ID    = A.BRANCH_ID
       ORDER BY EA.EMP_NO, SORT_TYPE, A.SPLIT_ORDER NULLS LAST, A.ACC_ID NULLS LAST;
    P_MSG_OUT := '1';
  EXCEPTION WHEN OTHERS THEN P_MSG_OUT := SQLERRM;
  END;

  PROCEDURE ACCOUNT_GET (
      P_ACC_ID      NUMBER,
      P_REF_CUR_OUT OUT SYS_REFCURSOR,
      P_MSG_OUT     OUT VARCHAR2
  ) IS
  BEGIN
    OPEN P_REF_CUR_OUT FOR
      SELECT A.*,
             P.PROVIDER_NAME, P.PROVIDER_TYPE,
             BR.BRANCH_NAME,
             B.NAME AS BENEFICIARY_NAME
        FROM GFC.PAYMENT_ACCOUNTS_TB A
        JOIN GFC.PAYMENT_PROVIDERS_TB P ON P.PROVIDER_ID = A.PROVIDER_ID
        LEFT JOIN GFC.PAYMENT_BANK_BRANCHES_TB BR ON BR.BRANCH_ID = A.BRANCH_ID
        LEFT JOIN GFC.PAYMENT_BENEFICIARIES_TB B ON B.BENEFICIARY_ID = A.BENEFICIARY_ID
       WHERE A.ACC_ID = P_ACC_ID;
    P_MSG_OUT := '1';
  EXCEPTION WHEN OTHERS THEN P_MSG_OUT := SQLERRM;
  END;

  PROCEDURE ACCOUNT_INSERT (
      P_EMP_NO         NUMBER, P_BENEFICIARY_ID NUMBER,
      P_PROVIDER_ID    NUMBER, P_BRANCH_ID NUMBER,
      P_ACCOUNT_NO     VARCHAR2, P_IBAN VARCHAR2, P_WALLET_NUMBER VARCHAR2,
      P_OWNER_ID_NO    VARCHAR2, P_OWNER_NAME VARCHAR2, P_OWNER_PHONE VARCHAR2,
      P_IS_DEFAULT     NUMBER,
      P_SPLIT_TYPE     NUMBER, P_SPLIT_VALUE NUMBER, P_SPLIT_ORDER NUMBER,
      P_NOTES          VARCHAR2,
      P_MSG_OUT        OUT VARCHAR2
  ) IS
    V_ID NUMBER;
    V_TYPE NUMBER;
    V_DUP_ID  NUMBER;
    -- 🆕 قيم مُطبَّعة (نحسبها في PL/SQL ثم نستخدمها في SQL)
    V_NORM_WALLET  VARCHAR2(200);
    V_NORM_ACC     VARCHAR2(200);
    V_NORM_IBAN    VARCHAR2(200);
    -- 🆕 رقم المحفظة بصيغته النهائية (مع الصفر البادئ لو ناقص)
    V_FIXED_WALLET VARCHAR2(200);
    V_FIXED_PHONE  VARCHAR2(200);
  BEGIN
    IF P_EMP_NO IS NULL THEN P_MSG_OUT := 'رقم الموظف مطلوب'; RETURN; END IF;
    IF P_PROVIDER_ID IS NULL THEN P_MSG_OUT := 'المزود مطلوب'; RETURN; END IF;

    -- فحص نوع المزود لتحديد الحقول المطلوبة
    BEGIN
      SELECT PROVIDER_TYPE INTO V_TYPE FROM GFC.PAYMENT_PROVIDERS_TB
       WHERE PROVIDER_ID = P_PROVIDER_ID;
    EXCEPTION WHEN NO_DATA_FOUND THEN
      P_MSG_OUT := 'المزود غير موجود'; RETURN;
    END;

    -- بنك → يحتاج ACCOUNT_NO على الأقل
    IF V_TYPE = C_PROVIDER_TYPE_BANK AND P_ACCOUNT_NO IS NULL THEN
      P_MSG_OUT := 'رقم الحساب مطلوب للبنك'; RETURN;
    END IF;
    -- محفظة → تحتاج WALLET_NUMBER
    IF V_TYPE = C_PROVIDER_TYPE_WALLET AND P_WALLET_NUMBER IS NULL THEN
      P_MSG_OUT := 'رقم المحفظة مطلوب'; RETURN;
    END IF;

    -- 🆕 ضمان الصفر البادئ في رقم المحفظة (رقم جوال) ورقم جوال صاحب الحساب
    -- لو الرقم أرقام فقط ولا يبدأ بصفر، نضيف 0
    V_FIXED_WALLET := TRIM(P_WALLET_NUMBER);
    IF V_TYPE = C_PROVIDER_TYPE_WALLET AND V_FIXED_WALLET IS NOT NULL
       AND REGEXP_LIKE(V_FIXED_WALLET, '^[0-9]+$')
       AND SUBSTR(V_FIXED_WALLET, 1, 1) <> '0' THEN
      V_FIXED_WALLET := '0' || V_FIXED_WALLET;
    END IF;

    V_FIXED_PHONE := TRIM(P_OWNER_PHONE);
    IF V_FIXED_PHONE IS NOT NULL
       AND REGEXP_LIKE(V_FIXED_PHONE, '^[0-9]+$')
       AND SUBSTR(V_FIXED_PHONE, 1, 1) <> '0' THEN
      V_FIXED_PHONE := '0' || V_FIXED_PHONE;
    END IF;

    -- تحضير القيم المُطبَّعة للمقارنة (إزالة فراغات/شُرَط/صفر بادئ)
    IF V_FIXED_WALLET IS NOT NULL THEN
      V_NORM_WALLET := LTRIM(REGEXP_REPLACE(UPPER(V_FIXED_WALLET), '[[:space:]\-]+', ''), '0');
    END IF;
    IF P_ACCOUNT_NO IS NOT NULL THEN
      V_NORM_ACC := LTRIM(REGEXP_REPLACE(UPPER(TRIM(P_ACCOUNT_NO)), '[[:space:]\-]+', ''), '0');
    END IF;
    IF P_IBAN IS NOT NULL THEN
      V_NORM_IBAN := REGEXP_REPLACE(UPPER(TRIM(P_IBAN)), '[[:space:]\-]+', '');
    END IF;

    -- 🆕 فحص التكرار: نفس الموظف + نفس المزود + نفس الرقم (مُطبَّع)
    IF V_TYPE = C_PROVIDER_TYPE_WALLET THEN
      BEGIN
        SELECT ACC_ID INTO V_DUP_ID
          FROM GFC.PAYMENT_ACCOUNTS_TB
         WHERE EMP_NO      = P_EMP_NO
           AND PROVIDER_ID = P_PROVIDER_ID
           AND STATUS      = 1
           AND LTRIM(REGEXP_REPLACE(UPPER(TRIM(WALLET_NUMBER)), '[[:space:]\-]+', ''), '0')
             = V_NORM_WALLET
           AND ROWNUM = 1;
        P_MSG_OUT := 'حساب مكرر: محفظة بهذا الرقم موجودة مسبقاً للموظف (ACC_ID=' || V_DUP_ID || ')';
        RETURN;
      EXCEPTION WHEN NO_DATA_FOUND THEN NULL;
      END;
    ELSIF V_TYPE = C_PROVIDER_TYPE_BANK THEN
      BEGIN
        SELECT ACC_ID INTO V_DUP_ID
          FROM GFC.PAYMENT_ACCOUNTS_TB
         WHERE EMP_NO      = P_EMP_NO
           AND PROVIDER_ID = P_PROVIDER_ID
           AND STATUS      = 1
           AND (
                (V_NORM_ACC IS NOT NULL AND
                 LTRIM(REGEXP_REPLACE(UPPER(TRIM(ACCOUNT_NO)), '[[:space:]\-]+', ''), '0')
                   = V_NORM_ACC)
             OR (V_NORM_IBAN IS NOT NULL AND
                 REGEXP_REPLACE(UPPER(TRIM(IBAN)), '[[:space:]\-]+', '')
                   = V_NORM_IBAN)
               )
           AND ROWNUM = 1;
        P_MSG_OUT := 'حساب مكرر: نفس رقم الحساب/IBAN موجود مسبقاً للموظف (ACC_ID=' || V_DUP_ID || ')';
        RETURN;
      EXCEPTION WHEN NO_DATA_FOUND THEN NULL;
      END;
    END IF;

    -- لو IS_DEFAULT=1، ألغ الافتراضي من باقي حسابات الموظف
    IF NVL(P_IS_DEFAULT, 0) = 1 THEN
      UPDATE GFC.PAYMENT_ACCOUNTS_TB SET IS_DEFAULT = 0
       WHERE EMP_NO = P_EMP_NO AND STATUS = 1;
    END IF;

    V_ID := GFC_PAK.PAYMENT_ACCOUNTS_SEQ.NEXTVAL;
    INSERT INTO GFC.PAYMENT_ACCOUNTS_TB (
      ACC_ID, EMP_NO, BENEFICIARY_ID, PROVIDER_ID, BRANCH_ID,
      ACCOUNT_NO, IBAN, WALLET_NUMBER,
      OWNER_ID_NO, OWNER_NAME, OWNER_PHONE,
      IS_DEFAULT, SPLIT_TYPE, SPLIT_VALUE, SPLIT_ORDER,
      IS_ACTIVE, STATUS, NOTES,
      ENTRY_USER, ENTRY_DATE
    ) VALUES (
      V_ID, P_EMP_NO, P_BENEFICIARY_ID, P_PROVIDER_ID, P_BRANCH_ID,
      P_ACCOUNT_NO, P_IBAN, V_FIXED_WALLET,
      P_OWNER_ID_NO, P_OWNER_NAME, V_FIXED_PHONE,
      NVL(P_IS_DEFAULT, 0),
      NVL(P_SPLIT_TYPE, C_SPLIT_REMAINING),
      P_SPLIT_VALUE,
      NVL(P_SPLIT_ORDER, 1),
      1, 1, P_NOTES,
      USER_PKG.GET_USER_ID, SYSDATE
    );
    P_MSG_OUT := TO_CHAR(V_ID);
  EXCEPTION WHEN OTHERS THEN P_MSG_OUT := SQLERRM;
  END;

  PROCEDURE ACCOUNT_UPDATE (
      P_ACC_ID         NUMBER,
      P_BRANCH_ID      NUMBER,
      P_ACCOUNT_NO     VARCHAR2, P_IBAN VARCHAR2, P_WALLET_NUMBER VARCHAR2,
      P_OWNER_ID_NO    VARCHAR2, P_OWNER_NAME VARCHAR2, P_OWNER_PHONE VARCHAR2,
      P_IS_DEFAULT     NUMBER,
      P_SPLIT_TYPE     NUMBER, P_SPLIT_VALUE NUMBER, P_SPLIT_ORDER NUMBER,
      P_NOTES          VARCHAR2,
      P_MSG_OUT        OUT VARCHAR2
  ) IS
    V_EMP_NO NUMBER;
    V_TYPE   NUMBER;
    -- 🆕 رقم المحفظة بصيغته النهائية (مع الصفر البادئ لو ناقص)
    V_FIXED_WALLET VARCHAR2(200);
    V_FIXED_PHONE  VARCHAR2(200);
  BEGIN
    BEGIN
      SELECT A.EMP_NO, P.PROVIDER_TYPE INTO V_EMP_NO, V_TYPE
        FROM GFC.PAYMENT_ACCOUNTS_TB A
        JOIN GFC.PAYMENT_PROVIDERS_TB P ON P.PROVIDER_ID = A.PROVIDER_ID
       WHERE A.ACC_ID = P_ACC_ID;
    EXCEPTION WHEN NO_DATA_FOUND THEN
      P_MSG_OUT := 'الحساب غير موجود'; RETURN;
    END;

    -- 🆕 ضمان الصفر البادئ في رقم المحفظة (رقم جوال) ورقم جوال صاحب الحساب
    V_FIXED_WALLET := TRIM(P_WALLET_NUMBER);
    IF V_TYPE = C_PROVIDER_TYPE_WALLET AND V_FIXED_WALLET IS NOT NULL
       AND REGEXP_LIKE(V_FIXED_WALLET, '^[0-9]+$')
       AND SUBSTR(V_FIXED_WALLET, 1, 1) <> '0' THEN
      V_FIXED_WALLET := '0' || V_FIXED_WALLET;
    END IF;

    V_FIXED_PHONE := TRIM(P_OWNER_PHONE);
    IF V_FIXED_PHONE IS NOT NULL
       AND REGEXP_LIKE(V_FIXED_PHONE, '^[0-9]+$')
       AND SUBSTR(V_FIXED_PHONE, 1, 1) <> '0' THEN
      V_FIXED_PHONE := '0' || V_FIXED_PHONE;
    END IF;

    -- لو IS_DEFAULT=1، ألغ الافتراضي من باقي حسابات الموظف
    IF NVL(P_IS_DEFAULT, 0) = 1 THEN
      UPDATE GFC.PAYMENT_ACCOUNTS_TB SET IS_DEFAULT = 0
       WHERE EMP_NO = V_EMP_NO AND STATUS = 1 AND ACC_ID <> P_ACC_ID;
    END IF;

    UPDATE GFC.PAYMENT_ACCOUNTS_TB SET
      BRANCH_ID     = P_BRANCH_ID,
      ACCOUNT_NO    = P_ACCOUNT_NO,
      IBAN          = P_IBAN,
      WALLET_NUMBER = V_FIXED_WALLET,
      OWNER_ID_NO   = P_OWNER_ID_NO,
      OWNER_NAME    = P_OWNER_NAME,
      OWNER_PHONE   = V_FIXED_PHONE,
      IS_DEFAULT    = NVL(P_IS_DEFAULT, 0),
      SPLIT_TYPE    = NVL(P_SPLIT_TYPE, C_SPLIT_REMAINING),
      SPLIT_VALUE   = P_SPLIT_VALUE,
      SPLIT_ORDER   = NVL(P_SPLIT_ORDER, 1),
      NOTES         = P_NOTES,
      UPDATE_USER   = USER_PKG.GET_USER_ID,
      UPDATE_DATE   = SYSDATE
     WHERE ACC_ID = P_ACC_ID;
    P_MSG_OUT := '1';
  EXCEPTION WHEN OTHERS THEN P_MSG_OUT := SQLERRM;
  END;

  PROCEDURE ACCOUNT_DEACTIVATE (
      P_ACC_ID            NUMBER,
      P_REASON            NUMBER,
      P_NOTES             VARCHAR2,
      P_INACTIVE_MONTH    NUMBER DEFAULT NULL,    -- 🆕 YYYYMM: شهر بدء الإيقاف
      P_MSG_OUT       OUT VARCHAR2
  ) IS
    V_MONTH NUMBER;
  BEGIN
    -- لو ما حُدد شهر، نستخدم الشهر الحالي
    V_MONTH := NVL(P_INACTIVE_MONTH,
                   EXTRACT(YEAR FROM SYSDATE) * 100 + EXTRACT(MONTH FROM SYSDATE));

    UPDATE GFC.PAYMENT_ACCOUNTS_TB SET
      IS_ACTIVE            = 0,
      INACTIVE_REASON      = P_REASON,
      INACTIVE_FROM_MONTH  = V_MONTH,                -- 🆕
      IS_DEFAULT           = 0,
      TO_DATE              = SYSDATE,
      NOTES                = TRIM(NVL(NOTES,'') || CHR(10) ||
                                  TO_CHAR(SYSDATE, 'YYYY-MM-DD') || ' - إيقاف (' || V_MONTH || '): ' || NVL(P_NOTES,'')),
      UPDATE_USER          = USER_PKG.GET_USER_ID,
      UPDATE_DATE          = SYSDATE
     WHERE ACC_ID = P_ACC_ID;
    P_MSG_OUT := CASE WHEN SQL%ROWCOUNT = 0 THEN 'الحساب غير موجود' ELSE '1' END;
  EXCEPTION WHEN OTHERS THEN P_MSG_OUT := SQLERRM;
  END;

  PROCEDURE ACCOUNT_REACTIVATE (
      P_ACC_ID  NUMBER, P_NOTES VARCHAR2,
      P_MSG_OUT OUT VARCHAR2
  ) IS
  BEGIN
    UPDATE GFC.PAYMENT_ACCOUNTS_TB SET
      IS_ACTIVE            = 1,
      INACTIVE_REASON      = NULL,
      INACTIVE_FROM_MONTH  = NULL,                   -- 🆕
      TO_DATE              = NULL,
      NOTES                = TRIM(NVL(NOTES,'') || CHR(10) ||
                                  TO_CHAR(SYSDATE, 'YYYY-MM-DD') || ' - إعادة تفعيل: ' || NVL(P_NOTES,'')),
      UPDATE_USER          = USER_PKG.GET_USER_ID,
      UPDATE_DATE          = SYSDATE
     WHERE ACC_ID = P_ACC_ID;
    P_MSG_OUT := CASE WHEN SQL%ROWCOUNT = 0 THEN 'الحساب غير موجود' ELSE '1' END;
  EXCEPTION WHEN OTHERS THEN P_MSG_OUT := SQLERRM;
  END;

  PROCEDURE ACCOUNT_DELETE (
      P_ACC_ID  NUMBER,
      P_MSG_OUT OUT VARCHAR2
  ) IS
    V_SPLIT_CNT NUMBER;
  BEGIN
    -- تحقق: لا يوجد توزيع نشط يستخدم هذا الحساب
    SELECT COUNT(*) INTO V_SPLIT_CNT FROM GFC.PAYMENT_REQ_DETAIL_SPLIT_TB
     WHERE ACC_ID = P_ACC_ID;
    IF V_SPLIT_CNT > 0 THEN
      P_MSG_OUT := 'الحساب مستخدم في ' || V_SPLIT_CNT || ' توزيع — إيقافه بدلاً من الحذف';
      RETURN;
    END IF;

    UPDATE GFC.PAYMENT_ACCOUNTS_TB SET
      STATUS = C_STATUS_DELETED,
      IS_ACTIVE = 0,
      UPDATE_USER = USER_PKG.GET_USER_ID,
      UPDATE_DATE = SYSDATE
     WHERE ACC_ID = P_ACC_ID;
    P_MSG_OUT := CASE WHEN SQL%ROWCOUNT = 0 THEN 'الحساب غير موجود' ELSE '1' END;
  EXCEPTION WHEN OTHERS THEN P_MSG_OUT := SQLERRM;
  END;

  PROCEDURE ACCOUNT_SET_DEFAULT (
      P_ACC_ID  NUMBER,
      P_MSG_OUT OUT VARCHAR2
  ) IS
    V_EMP_NO NUMBER;
  BEGIN
    SELECT EMP_NO INTO V_EMP_NO FROM GFC.PAYMENT_ACCOUNTS_TB WHERE ACC_ID = P_ACC_ID;
    UPDATE GFC.PAYMENT_ACCOUNTS_TB SET IS_DEFAULT = 0
     WHERE EMP_NO = V_EMP_NO;
    UPDATE GFC.PAYMENT_ACCOUNTS_TB SET IS_DEFAULT = 1, UPDATE_USER = USER_PKG.GET_USER_ID, UPDATE_DATE = SYSDATE
     WHERE ACC_ID = P_ACC_ID;
    P_MSG_OUT := '1';
  EXCEPTION
    WHEN NO_DATA_FOUND THEN P_MSG_OUT := 'الحساب غير موجود';
    WHEN OTHERS THEN P_MSG_OUT := SQLERRM;
  END;


  -- ============================================================
  -- 5) Split Distribution
  -- ============================================================

  FUNCTION VALIDATE_SPLIT (P_EMP_NO NUMBER) RETURN VARCHAR2 IS
    V_ACT_CNT    NUMBER;
    V_SUM_PCT    NUMBER;
    V_HAS_REMAIN NUMBER;
  BEGIN
    -- عدد الحسابات النشطة
    SELECT COUNT(*) INTO V_ACT_CNT FROM GFC.PAYMENT_ACCOUNTS_TB
     WHERE EMP_NO = P_EMP_NO AND IS_ACTIVE = 1 AND STATUS = 1;

    IF V_ACT_CNT = 0 THEN
      RETURN 'لا يوجد حسابات نشطة للموظف';
    END IF;

    IF V_ACT_CNT = 1 THEN
      RETURN '1'; -- حساب واحد، لا حاجة للتحقق من التوزيع
    END IF;

    -- أكثر من حساب: يجب أن يوجد حساب واحد على الأقل بـ SPLIT_TYPE=3 (الباقي)
    SELECT COUNT(*) INTO V_HAS_REMAIN FROM GFC.PAYMENT_ACCOUNTS_TB
     WHERE EMP_NO = P_EMP_NO AND IS_ACTIVE = 1 AND STATUS = 1
       AND SPLIT_TYPE = C_SPLIT_REMAINING;
    IF V_HAS_REMAIN = 0 THEN
      RETURN 'يجب وجود حساب واحد على الأقل من نوع "الباقي" لضمان صرف كامل المبلغ';
    END IF;

    -- مجموع النسب يجب ألا يتجاوز 100
    SELECT NVL(SUM(SPLIT_VALUE), 0) INTO V_SUM_PCT
      FROM GFC.PAYMENT_ACCOUNTS_TB
     WHERE EMP_NO = P_EMP_NO AND IS_ACTIVE = 1 AND STATUS = 1
       AND SPLIT_TYPE = C_SPLIT_PERCENT;
    IF V_SUM_PCT > 100 THEN
      RETURN 'مجموع النسب يتجاوز 100% (' || V_SUM_PCT || '%)';
    END IF;

    RETURN '1';
  EXCEPTION WHEN OTHERS THEN RETURN SQLERRM;
  END;

  -- ملاحظة: CALCULATE_SPLIT (preview) حُذفت — استخدم AUTO_FILL_SPLIT + SPLIT_LIST

  PROCEDURE AUTO_FILL_SPLIT (
      P_DETAIL_ID NUMBER,
      P_MSG_OUT   OUT VARCHAR2
  ) IS
    V_EMP_NO  NUMBER;
    V_AMOUNT  NUMBER;
    V_REMAIN  NUMBER;
    V_AMT     NUMBER;
    V_VALID   VARCHAR2(500);
  BEGIN
    -- جلب EMP_NO + المبلغ من detail
    SELECT EMP_NO, REQ_AMOUNT INTO V_EMP_NO, V_AMOUNT
      FROM GFC.PAYMENT_REQ_DETAIL_TB WHERE DETAIL_ID = P_DETAIL_ID;

    -- تحقق من صحة التوزيع
    V_VALID := VALIDATE_SPLIT(V_EMP_NO);
    IF V_VALID <> '1' THEN P_MSG_OUT := V_VALID; RETURN; END IF;

    -- مسح أي توزيع سابق
    DELETE FROM GFC.PAYMENT_REQ_DETAIL_SPLIT_TB WHERE DETAIL_ID = P_DETAIL_ID;

    V_REMAIN := V_AMOUNT;

    -- مرحلة 1: النسب المئوية
    FOR R IN (
      SELECT ACC_ID, SPLIT_VALUE, SPLIT_ORDER
        FROM GFC.PAYMENT_ACCOUNTS_TB
       WHERE EMP_NO = V_EMP_NO AND IS_ACTIVE = 1 AND STATUS = 1
         AND SPLIT_TYPE = C_SPLIT_PERCENT
       ORDER BY SPLIT_ORDER, ACC_ID
    ) LOOP
      V_AMT := ROUND(V_AMOUNT * NVL(R.SPLIT_VALUE, 0) / 100, 2);
      IF V_AMT > V_REMAIN THEN V_AMT := V_REMAIN; END IF;
      IF V_AMT > 0 THEN
        INSERT INTO GFC.PAYMENT_REQ_DETAIL_SPLIT_TB (
          SPLIT_ID, DETAIL_ID, ACC_ID, AMOUNT, PCT_USED,
          SOURCE_TYPE, AUTO_AMOUNT, ENTRY_USER, ENTRY_DATE
        ) VALUES (
          GFC_PAK.PAYMENT_REQ_DETAIL_SPLIT_SEQ.NEXTVAL, P_DETAIL_ID, R.ACC_ID, V_AMT,
          R.SPLIT_VALUE, C_SPLIT_SOURCE_AUTO, V_AMT, USER_PKG.GET_USER_ID, SYSDATE
        );
        V_REMAIN := V_REMAIN - V_AMT;
      END IF;
    END LOOP;

    -- مرحلة 2: المبالغ الثابتة
    FOR R IN (
      SELECT ACC_ID, SPLIT_VALUE, SPLIT_ORDER
        FROM GFC.PAYMENT_ACCOUNTS_TB
       WHERE EMP_NO = V_EMP_NO AND IS_ACTIVE = 1 AND STATUS = 1
         AND SPLIT_TYPE = C_SPLIT_FIXED
       ORDER BY SPLIT_ORDER, ACC_ID
    ) LOOP
      V_AMT := LEAST(NVL(R.SPLIT_VALUE, 0), V_REMAIN);
      IF V_AMT > 0 THEN
        INSERT INTO GFC.PAYMENT_REQ_DETAIL_SPLIT_TB (
          SPLIT_ID, DETAIL_ID, ACC_ID, AMOUNT, PCT_USED,
          SOURCE_TYPE, AUTO_AMOUNT, ENTRY_USER, ENTRY_DATE
        ) VALUES (
          GFC_PAK.PAYMENT_REQ_DETAIL_SPLIT_SEQ.NEXTVAL, P_DETAIL_ID, R.ACC_ID, V_AMT,
          CASE WHEN V_AMOUNT > 0 THEN ROUND(V_AMT * 100 / V_AMOUNT, 2) ELSE 0 END,
          C_SPLIT_SOURCE_AUTO, V_AMT, USER_PKG.GET_USER_ID, SYSDATE
        );
        V_REMAIN := V_REMAIN - V_AMT;
      END IF;
    END LOOP;

    -- مرحلة 3: الباقي — يُقسم بالتساوي بين كل الحسابات بـ "كامل الباقي"
    -- (ليطابق سلوك الواجهة renderDistBar)
    DECLARE
      V_REM_CNT NUMBER;
      V_IDX     NUMBER := 0;
      V_PER     NUMBER;
      V_LAST_AMT NUMBER;
    BEGIN
      SELECT COUNT(*) INTO V_REM_CNT
        FROM GFC.PAYMENT_ACCOUNTS_TB
       WHERE EMP_NO = V_EMP_NO AND IS_ACTIVE = 1 AND STATUS = 1
         AND SPLIT_TYPE = C_SPLIT_REMAINING;

      IF V_REM_CNT > 0 AND V_REMAIN > 0 THEN
        V_PER := ROUND(V_REMAIN / V_REM_CNT, 2);

        FOR R IN (
          SELECT ACC_ID, SPLIT_ORDER
            FROM GFC.PAYMENT_ACCOUNTS_TB
           WHERE EMP_NO = V_EMP_NO AND IS_ACTIVE = 1 AND STATUS = 1
             AND SPLIT_TYPE = C_SPLIT_REMAINING
           ORDER BY SPLIT_ORDER, ACC_ID
        ) LOOP
          V_IDX := V_IDX + 1;
          -- آخر حساب يأخذ ما تبقى فعلياً (لتفادي أخطاء التقريب)
          IF V_IDX = V_REM_CNT THEN
            V_LAST_AMT := V_REMAIN;
          ELSE
            V_LAST_AMT := LEAST(V_PER, V_REMAIN);
          END IF;

          IF V_LAST_AMT > 0 THEN
            INSERT INTO GFC.PAYMENT_REQ_DETAIL_SPLIT_TB (
              SPLIT_ID, DETAIL_ID, ACC_ID, AMOUNT, PCT_USED,
              SOURCE_TYPE, AUTO_AMOUNT, ENTRY_USER, ENTRY_DATE
            ) VALUES (
              GFC_PAK.PAYMENT_REQ_DETAIL_SPLIT_SEQ.NEXTVAL, P_DETAIL_ID, R.ACC_ID, V_LAST_AMT,
              CASE WHEN V_AMOUNT > 0 THEN ROUND(V_LAST_AMT * 100 / V_AMOUNT, 2) ELSE 0 END,
              C_SPLIT_SOURCE_AUTO, V_LAST_AMT, USER_PKG.GET_USER_ID, SYSDATE
            );
            V_REMAIN := V_REMAIN - V_LAST_AMT;
          END IF;
        END LOOP;
      END IF;
    END;

    P_MSG_OUT := '1';
  EXCEPTION
    WHEN NO_DATA_FOUND THEN P_MSG_OUT := 'السجل غير موجود';
    WHEN OTHERS THEN P_MSG_OUT := SQLERRM;
  END;

  PROCEDURE UPDATE_SPLIT_MANUAL (
      P_DETAIL_ID   NUMBER,
      P_SPLITS_JSON CLOB,
      P_MSG_OUT     OUT VARCHAR2
  ) IS
    V_AMOUNT      NUMBER;
    V_SUM         NUMBER := 0;
    V_ACC_ID      NUMBER;
    V_AMT         NUMBER;
    V_JSON_AMT    VARCHAR2(50);
    V_JSON_ACC    VARCHAR2(50);
    V_POS         NUMBER := 1;
    V_ENTRY       VARCHAR2(500);
  BEGIN
    -- جلب المبلغ الإجمالي للـ detail
    SELECT REQ_AMOUNT INTO V_AMOUNT FROM GFC.PAYMENT_REQ_DETAIL_TB WHERE DETAIL_ID = P_DETAIL_ID;

    -- تحليل JSON بدائي (Oracle 11g قد لا يدعم JSON functions)
    -- الصيغة المتوقعة: [{"acc_id":101,"amount":700},{"acc_id":102,"amount":300}]
    -- للبساطة نستخدم REGEXP
    FOR R IN (
      SELECT REGEXP_SUBSTR(P_SPLITS_JSON, '\{[^}]*\}', 1, LEVEL) AS ENTRY_JSON
        FROM DUAL
     CONNECT BY REGEXP_SUBSTR(P_SPLITS_JSON, '\{[^}]*\}', 1, LEVEL) IS NOT NULL
    ) LOOP
      V_ENTRY := R.ENTRY_JSON;
      V_JSON_ACC := REGEXP_SUBSTR(V_ENTRY, '"acc_id"\s*:\s*(\d+)', 1, 1, NULL, 1);
      V_JSON_AMT := REGEXP_SUBSTR(V_ENTRY, '"amount"\s*:\s*([0-9.]+)', 1, 1, NULL, 1);
      V_ACC_ID := TO_NUMBER(V_JSON_ACC);
      V_AMT    := TO_NUMBER(V_JSON_AMT);
      V_SUM    := V_SUM + V_AMT;
    END LOOP;

    -- تحقق: المجموع يساوي REQ_AMOUNT
    IF ROUND(V_SUM, 2) <> ROUND(V_AMOUNT, 2) THEN
      P_MSG_OUT := 'مجموع التوزيع (' || V_SUM || ') لا يساوي المبلغ المعتمد (' || V_AMOUNT || ')';
      RETURN;
    END IF;

    -- تحديث: نحذف الحالي ونُدخل الجديد
    DELETE FROM GFC.PAYMENT_REQ_DETAIL_SPLIT_TB WHERE DETAIL_ID = P_DETAIL_ID;

    FOR R IN (
      SELECT REGEXP_SUBSTR(P_SPLITS_JSON, '\{[^}]*\}', 1, LEVEL) AS ENTRY_JSON
        FROM DUAL
     CONNECT BY REGEXP_SUBSTR(P_SPLITS_JSON, '\{[^}]*\}', 1, LEVEL) IS NOT NULL
    ) LOOP
      V_ENTRY := R.ENTRY_JSON;
      V_ACC_ID := TO_NUMBER(REGEXP_SUBSTR(V_ENTRY, '"acc_id"\s*:\s*(\d+)', 1, 1, NULL, 1));
      V_AMT    := TO_NUMBER(REGEXP_SUBSTR(V_ENTRY, '"amount"\s*:\s*([0-9.]+)', 1, 1, NULL, 1));

      INSERT INTO GFC.PAYMENT_REQ_DETAIL_SPLIT_TB (
        SPLIT_ID, DETAIL_ID, ACC_ID, AMOUNT, PCT_USED,
        SOURCE_TYPE, AUTO_AMOUNT, ENTRY_USER, ENTRY_DATE
      ) VALUES (
        GFC_PAK.PAYMENT_REQ_DETAIL_SPLIT_SEQ.NEXTVAL, P_DETAIL_ID, V_ACC_ID, V_AMT,
        CASE WHEN V_AMOUNT > 0 THEN ROUND(V_AMT * 100 / V_AMOUNT, 2) ELSE 0 END,
        C_SPLIT_SOURCE_MANUAL, NULL, USER_PKG.GET_USER_ID, SYSDATE
      );
    END LOOP;

    P_MSG_OUT := '1';
  EXCEPTION
    WHEN NO_DATA_FOUND THEN P_MSG_OUT := 'السجل غير موجود';
    WHEN OTHERS THEN P_MSG_OUT := SQLERRM;
  END;

  PROCEDURE RESET_SPLIT_TO_AUTO (
      P_DETAIL_ID NUMBER,
      P_MSG_OUT   OUT VARCHAR2
  ) IS
  BEGIN
    DELETE FROM GFC.PAYMENT_REQ_DETAIL_SPLIT_TB WHERE DETAIL_ID = P_DETAIL_ID;
    AUTO_FILL_SPLIT(P_DETAIL_ID, P_MSG_OUT);
  END;

  PROCEDURE SPLIT_LIST (
      P_DETAIL_ID   NUMBER,
      P_REF_CUR_OUT OUT SYS_REFCURSOR,
      P_MSG_OUT     OUT VARCHAR2
  ) IS
  BEGIN
    OPEN P_REF_CUR_OUT FOR
      SELECT S.SPLIT_ID, S.DETAIL_ID, S.ACC_ID,
             P.PROVIDER_NAME, P.PROVIDER_TYPE,
             A.ACCOUNT_NO, A.IBAN, A.WALLET_NUMBER,
             A.OWNER_NAME,
             S.AMOUNT, S.PCT_USED, S.AUTO_AMOUNT, S.SOURCE_TYPE,
             CASE S.SOURCE_TYPE WHEN 0 THEN 'تلقائي' WHEN 1 THEN 'يدوي' END AS SOURCE_NAME
        FROM GFC.PAYMENT_REQ_DETAIL_SPLIT_TB S
        JOIN GFC.PAYMENT_ACCOUNTS_TB A ON A.ACC_ID = S.ACC_ID
        JOIN GFC.PAYMENT_PROVIDERS_TB P ON P.PROVIDER_ID = A.PROVIDER_ID
       WHERE S.DETAIL_ID = P_DETAIL_ID
       ORDER BY S.SPLIT_ID;
    P_MSG_OUT := '1';
  EXCEPTION WHEN OTHERS THEN P_MSG_OUT := SQLERRM;
  END;


  -- ============================================================
  -- 6) Helpers
  -- ============================================================

  FUNCTION GET_DEFAULT_ACCOUNT (P_EMP_NO NUMBER) RETURN NUMBER IS
    V NUMBER;
  BEGIN
    SELECT ACC_ID INTO V FROM GFC.PAYMENT_ACCOUNTS_TB
     WHERE EMP_NO = P_EMP_NO AND IS_DEFAULT = 1 AND IS_ACTIVE = 1 AND STATUS = 1
       AND ROWNUM = 1;
    RETURN V;
  EXCEPTION WHEN OTHERS THEN RETURN NULL;
  END;

  FUNCTION COUNT_ACTIVE_ACCOUNTS (P_EMP_NO NUMBER) RETURN NUMBER IS
    V NUMBER;
  BEGIN
    SELECT COUNT(*) INTO V FROM GFC.PAYMENT_ACCOUNTS_TB
     WHERE EMP_NO = P_EMP_NO AND IS_ACTIVE = 1 AND STATUS = 1;
    RETURN V;
  END;

  FUNCTION HAS_WALLET (P_EMP_NO NUMBER) RETURN NUMBER IS
    V NUMBER;
  BEGIN
    SELECT COUNT(*) INTO V FROM GFC.PAYMENT_ACCOUNTS_TB A
      JOIN GFC.PAYMENT_PROVIDERS_TB P ON P.PROVIDER_ID = A.PROVIDER_ID
     WHERE A.EMP_NO = P_EMP_NO AND A.IS_ACTIVE = 1 AND A.STATUS = 1
       AND P.PROVIDER_TYPE = C_PROVIDER_TYPE_WALLET;
    RETURN CASE WHEN V > 0 THEN 1 ELSE 0 END;
  END;

  FUNCTION GET_PROVIDER_TYPE (P_PROVIDER_ID NUMBER) RETURN NUMBER IS
    V NUMBER;
  BEGIN
    SELECT PROVIDER_TYPE INTO V FROM GFC.PAYMENT_PROVIDERS_TB WHERE PROVIDER_ID = P_PROVIDER_ID;
    RETURN V;
  EXCEPTION WHEN OTHERS THEN RETURN NULL;
  END;

  FUNCTION GET_PROVIDER_NAME (P_PROVIDER_ID NUMBER) RETURN VARCHAR2 IS
    V VARCHAR2(200);
  BEGIN
    SELECT PROVIDER_NAME INTO V FROM GFC.PAYMENT_PROVIDERS_TB WHERE PROVIDER_ID = P_PROVIDER_ID;
    RETURN V;
  EXCEPTION WHEN OTHERS THEN RETURN NULL;
  END;

  FUNCTION GET_BRANCH_NAME (P_BRANCH_ID NUMBER) RETURN VARCHAR2 IS
    V VARCHAR2(200);
  BEGIN
    SELECT BRANCH_NAME INTO V FROM GFC.PAYMENT_BANK_BRANCHES_TB WHERE BRANCH_ID = P_BRANCH_ID;
    RETURN V;
  EXCEPTION WHEN OTHERS THEN RETURN NULL;
  END;


  -- ============================================================
  -- 7) Employees
  -- ============================================================

  PROCEDURE EMPLOYEES_LIST_PAGINATED (
      P_EMP_NO      NUMBER DEFAULT NULL,
      P_BRANCH_NO   NUMBER DEFAULT NULL,
      P_IS_ACTIVE   NUMBER DEFAULT NULL,
      P_HAS_ACC     NUMBER DEFAULT NULL,
      P_HAS_BENEF   NUMBER DEFAULT NULL,
      P_THE_MONTH   NUMBER DEFAULT NULL,   -- لو مُحدّد: حالة الموظف وقتها من DATA.EMPLOYEES_MONTH
      P_OFFSET      NUMBER DEFAULT 0,
      P_LIMIT       NUMBER DEFAULT 50,
      P_REF_CUR_OUT OUT SYS_REFCURSOR,
      P_MSG_OUT     OUT VARCHAR2
  ) IS
    V_OFFSET NUMBER := NVL(P_OFFSET, 0);
    V_LIMIT  NUMBER := NVL(P_LIMIT, 50);
  BEGIN
    OPEN P_REF_CUR_OUT FOR
      WITH ACC_AGG AS (
        -- aggregates للـ counts، تُحسب مرة واحدة لكل الجدول
        SELECT A.EMP_NO,
               COUNT(*)                                                            AS ACC_COUNT,
               SUM(CASE WHEN A.IS_ACTIVE = 1 THEN 1 ELSE 0 END)                    AS ACTIVE_COUNT,
               SUM(CASE WHEN A.IS_ACTIVE = 1 AND PP.PROVIDER_TYPE = 1 THEN 1 ELSE 0 END) AS BANK_COUNT,
               SUM(CASE WHEN A.IS_ACTIVE = 1 AND PP.PROVIDER_TYPE = 2 THEN 1 ELSE 0 END) AS WALLET_COUNT,
               MAX(CASE WHEN A.INACTIVE_REASON = 2 THEN 1 ELSE 0 END)              AS HAS_DECEASED,
               MAX(CASE WHEN A.INACTIVE_REASON = 4 THEN 1 ELSE 0 END)              AS HAS_FROZEN,
               -- 🆕 أقدم شهر إيقاف لحساب وفاة (لمعرفة متى توفى)
               MIN(CASE WHEN A.INACTIVE_REASON = 2 THEN A.INACTIVE_FROM_MONTH END) AS DECEASED_FROM_MONTH,
               -- 🆕 أقدم شهر إيقاف لحساب مغلق
               MIN(CASE WHEN A.INACTIVE_REASON = 4 THEN A.INACTIVE_FROM_MONTH END) AS FROZEN_FROM_MONTH
          FROM GFC.PAYMENT_ACCOUNTS_TB A
          LEFT JOIN GFC.PAYMENT_PROVIDERS_TB PP ON PP.PROVIDER_ID = A.PROVIDER_ID
         WHERE A.STATUS = 1
         GROUP BY A.EMP_NO
      ),
      -- 🆕 أسباب عدم النشاط — DISTINCT أولاً ثم LISTAGG (متوافق مع كل إصدارات Oracle)
      -- نستخدم SETTING_PKG.CONSTANT_DETAILS_TB_GET_NAME(545, REASON) بدل CASE الطويل
      ACC_REASONS_AGG AS (
        SELECT EMP_NO,
               LISTAGG(SETTING_PKG.CONSTANT_DETAILS_TB_GET_NAME(545, INACTIVE_REASON), '، ')
                 WITHIN GROUP (ORDER BY INACTIVE_REASON) AS INACTIVE_REASONS_TEXT
          FROM (
            SELECT DISTINCT EMP_NO, INACTIVE_REASON
              FROM GFC.PAYMENT_ACCOUNTS_TB
             WHERE STATUS = 1
               AND NVL(IS_ACTIVE, 0) <> 1
               AND INACTIVE_REASON IS NOT NULL
          )
         GROUP BY EMP_NO
      ),
      BNF_AGG AS (
        SELECT B.EMP_NO, COUNT(*) AS BENEF_COUNT
          FROM GFC.PAYMENT_BENEFICIARIES_TB B
         WHERE B.STATUS = 1
         GROUP BY B.EMP_NO
      ),
      DEF_ACC AS (
        -- الحساب الافتراضي لكل موظف (أولوية: IS_DEFAULT > SPLIT_ORDER > ACC_ID)
        SELECT EMP_NO, ACCOUNT_NO, WALLET_NUMBER, IBAN, OWNER_NAME,
               PROVIDER_NAME, PROVIDER_TYPE
          FROM (
              SELECT A.EMP_NO,
                     A.ACCOUNT_NO,
                     A.WALLET_NUMBER,
                     A.IBAN,
                     A.OWNER_NAME,
                     PP.PROVIDER_NAME,
                     PP.PROVIDER_TYPE,
                     ROW_NUMBER() OVER (PARTITION BY A.EMP_NO
                                        ORDER BY A.IS_DEFAULT DESC, A.SPLIT_ORDER, A.ACC_ID) AS DRN
                FROM GFC.PAYMENT_ACCOUNTS_TB A
                JOIN GFC.PAYMENT_PROVIDERS_TB PP ON PP.PROVIDER_ID = A.PROVIDER_ID
               WHERE A.STATUS = 1 AND A.IS_ACTIVE = 1
          )
         WHERE DRN = 1
      ),
      PAGED AS (
        -- نطبّق الفلاتر + الـ pagination قبل الـ joins (لتجنّب احتساب 1800 صف عند pagination)
        -- لو P_THE_MONTH مُحدّد: نستخدم EMPLOYEES_MONTH لاستعلام حالة الشهر التاريخية
        -- ملاحظة: EMPLOYEES_MONTH عادةً يحتوي النشطين فقط — فالغياب يعني "مش فعّال ذاك الشهر"
        SELECT E.NO                              AS EMP_NO,
               E.NAME                            AS EMP_NAME,
               E.ID                              AS ID_NO,
               -- IS_ACTIVE: لو شهر مُحدّد، نستخدم منطق ذكي:
               --   فيه سجل في EM → IS_ACTIVE من EM
               --   ما فيه سجل  → 0 (مش فعّال ذاك الشهر)
               -- وإلا (لا يوجد شهر) → E.IS_ACTIVE الحالي
               CASE
                 WHEN P_THE_MONTH IS NOT NULL THEN
                   CASE WHEN EM.NO IS NOT NULL THEN NVL(EM.IS_ACTIVE, 0) ELSE 0 END
                 ELSE NVL(E.IS_ACTIVE, 0)
               END                               AS IS_ACTIVE,
               EMP_PKG.GET_EMP_BRANCH_NAME(E.NO) AS BRANCH_NAME,
               ROW_NUMBER() OVER (ORDER BY E.NO) AS RN
          FROM DATA.EMPLOYEES E
          LEFT JOIN ACC_AGG AC ON AC.EMP_NO = E.NO
          LEFT JOIN BNF_AGG BN ON BN.EMP_NO = E.NO
          LEFT JOIN DATA.EMPLOYEES_MONTH EM ON EM.NO = E.NO AND EM.THE_MONTH = P_THE_MONTH
         WHERE (P_EMP_NO    IS NULL OR E.NO = P_EMP_NO)
           AND (P_BRANCH_NO IS NULL OR EMP_PKG.GET_EMP_BRANCH(E.NO) = P_BRANCH_NO)
           -- لو شهر مُحدّد: نقتصر على من عنده سجل في EMPLOYEES_MONTH (snapshot الشهر — strict)
           -- بدون شهر: كل الموظفين
           AND (P_THE_MONTH IS NULL OR EM.NO IS NOT NULL)
           -- P_IS_ACTIVE: 1=فعّال، 0=متقاعد، 2=متوفى، 4=حسابه مغلق
           -- ⚠️ مهم تاريخياً: لو P_THE_MONTH مُحدّد → الحالة من EMPLOYEES_MONTH
           --    ولا نفحص HAS_DECEASED/HAS_FROZEN (لأنها الحالة "الحالية" — قد يكون توفى بعد ذلك الشهر)
           --    لو P_THE_MONTH NULL (نظرة حالية) → نفحص الكل
           AND (P_IS_ACTIVE IS NULL
                OR (P_IS_ACTIVE = 1 AND
                    ((P_THE_MONTH IS NOT NULL AND NVL(EM.IS_ACTIVE, 0) = 1)
                     OR (P_THE_MONTH IS NULL AND NVL(E.IS_ACTIVE, 0) = 1
                                            AND NVL(AC.HAS_DECEASED, 0) = 0
                                            AND NVL(AC.HAS_FROZEN, 0)   = 0)))
                OR (P_IS_ACTIVE = 0 AND
                    ((P_THE_MONTH IS NOT NULL AND NVL(EM.IS_ACTIVE, 0) = 0)
                     OR (P_THE_MONTH IS NULL AND NVL(E.IS_ACTIVE, 0) = 0
                                            AND NVL(AC.HAS_DECEASED, 0) = 0
                                            AND NVL(AC.HAS_FROZEN, 0)   = 0)))
                OR (P_IS_ACTIVE = 2 AND NVL(AC.HAS_DECEASED, 0) = 1)
                OR (P_IS_ACTIVE = 4 AND NVL(AC.HAS_FROZEN, 0)   = 1))
           -- 🆕 P_HAS_ACC: 1=عنده حساب نشط، 0=بدون حساب نشط، 2=عنده محفظة، 3=عنده بنك
           AND (P_HAS_ACC   IS NULL
                OR (P_HAS_ACC = 1 AND NVL(AC.ACTIVE_COUNT, 0) > 0)
                OR (P_HAS_ACC = 0 AND NVL(AC.ACTIVE_COUNT, 0) = 0)
                OR (P_HAS_ACC = 2 AND NVL(AC.WALLET_COUNT, 0) > 0)
                OR (P_HAS_ACC = 3 AND NVL(AC.BANK_COUNT,   0) > 0))
           -- P_HAS_BENEF: 1=عنده مستفيد، 0=بدون مستفيد، NULL=الكل
           AND (P_HAS_BENEF IS NULL
                OR (P_HAS_BENEF = 1 AND NVL(BN.BENEF_COUNT, 0) > 0)
                OR (P_HAS_BENEF = 0 AND NVL(BN.BENEF_COUNT, 0) = 0))
      )
      SELECT P.EMP_NO,
             P.EMP_NAME,
             P.ID_NO,
             P.IS_ACTIVE,
             P.BRANCH_NAME,
             NVL(AC.ACC_COUNT, 0)               AS ACC_COUNT,
             NVL(AC.ACTIVE_COUNT, 0)            AS ACTIVE_COUNT,
             NVL(AC.BANK_COUNT, 0)              AS BANK_COUNT,
             NVL(AC.WALLET_COUNT, 0)            AS WALLET_COUNT,
             NVL(BN.BENEF_COUNT, 0)             AS BENEF_COUNT,
             NVL(AC.HAS_DECEASED, 0)            AS HAS_DECEASED,
             NVL(AC.HAS_FROZEN, 0)              AS HAS_FROZEN,
             AC.DECEASED_FROM_MONTH             AS DECEASED_FROM_MONTH,    -- 🆕 YYYYMM
             AC.FROZEN_FROM_MONTH               AS FROZEN_FROM_MONTH,      -- 🆕 YYYYMM
             AR.INACTIVE_REASONS_TEXT           AS INACTIVE_REASONS_TEXT,  -- 🆕 من ACC_REASONS_AGG
             D.PROVIDER_NAME                    AS DEF_PROVIDER_NAME,
             D.PROVIDER_TYPE                    AS DEF_PROVIDER_TYPE,
             NVL(D.ACCOUNT_NO, D.WALLET_NUMBER) AS DEF_ACCOUNT_NO,
             D.IBAN                             AS DEF_IBAN,
             D.OWNER_NAME                       AS DEF_OWNER_NAME
        FROM PAGED P
        LEFT JOIN ACC_AGG         AC ON AC.EMP_NO = P.EMP_NO
        LEFT JOIN ACC_REASONS_AGG AR ON AR.EMP_NO = P.EMP_NO   -- 🆕
        LEFT JOIN BNF_AGG         BN ON BN.EMP_NO = P.EMP_NO
        LEFT JOIN DEF_ACC         D  ON D.EMP_NO  = P.EMP_NO
       WHERE P.RN BETWEEN V_OFFSET + 1 AND V_OFFSET + V_LIMIT
       ORDER BY P.RN;
    P_MSG_OUT := '1';
  EXCEPTION WHEN OTHERS THEN P_MSG_OUT := SQLERRM;
  END;

  PROCEDURE EMPLOYEES_COUNT (
      P_EMP_NO      NUMBER DEFAULT NULL,
      P_BRANCH_NO   NUMBER DEFAULT NULL,
      P_IS_ACTIVE   NUMBER DEFAULT NULL,
      P_HAS_ACC     NUMBER DEFAULT NULL,
      P_HAS_BENEF   NUMBER DEFAULT NULL,
      P_THE_MONTH   NUMBER DEFAULT NULL,
      P_CNT_OUT     OUT NUMBER,
      P_MSG_OUT     OUT VARCHAR2
  ) IS
  BEGIN
    SELECT COUNT(*) INTO P_CNT_OUT
      FROM DATA.EMPLOYEES E
      LEFT JOIN (
          SELECT A.EMP_NO,
                 COUNT(*)                                                                  AS ACC_COUNT,
                 SUM(CASE WHEN A.IS_ACTIVE = 1 THEN 1 ELSE 0 END)                          AS ACTIVE_COUNT,
                 SUM(CASE WHEN A.IS_ACTIVE = 1 AND PP.PROVIDER_TYPE = 1 THEN 1 ELSE 0 END) AS BANK_COUNT,
                 SUM(CASE WHEN A.IS_ACTIVE = 1 AND PP.PROVIDER_TYPE = 2 THEN 1 ELSE 0 END) AS WALLET_COUNT,
                 MAX(CASE WHEN A.INACTIVE_REASON = 2 THEN 1 ELSE 0 END)                    AS HAS_DECEASED,
                 MAX(CASE WHEN A.INACTIVE_REASON = 4 THEN 1 ELSE 0 END)                    AS HAS_FROZEN
            FROM GFC.PAYMENT_ACCOUNTS_TB A
            LEFT JOIN GFC.PAYMENT_PROVIDERS_TB PP ON PP.PROVIDER_ID = A.PROVIDER_ID
           WHERE A.STATUS = 1
           GROUP BY A.EMP_NO
      ) AC ON AC.EMP_NO = E.NO
      LEFT JOIN (
          SELECT EMP_NO, COUNT(*) AS BENEF_COUNT
            FROM GFC.PAYMENT_BENEFICIARIES_TB
           WHERE STATUS = 1
           GROUP BY EMP_NO
      ) BN ON BN.EMP_NO = E.NO
      LEFT JOIN DATA.EMPLOYEES_MONTH EM ON EM.NO = E.NO AND EM.THE_MONTH = P_THE_MONTH
     WHERE (P_EMP_NO    IS NULL OR E.NO = P_EMP_NO)
       AND (P_BRANCH_NO IS NULL OR EMP_PKG.GET_EMP_BRANCH(E.NO) = P_BRANCH_NO)
       AND (P_THE_MONTH IS NULL OR EM.NO IS NOT NULL)
       -- ⚠️ نفس المنطق التاريخي: لو شهر مُحدّد → نتجاهل HAS_DECEASED/HAS_FROZEN (الحالة الحالية)
       AND (P_IS_ACTIVE IS NULL
            OR (P_IS_ACTIVE = 1 AND
                ((P_THE_MONTH IS NOT NULL AND NVL(EM.IS_ACTIVE, 0) = 1)
                 OR (P_THE_MONTH IS NULL AND NVL(E.IS_ACTIVE, 0) = 1
                                        AND NVL(AC.HAS_DECEASED, 0) = 0
                                        AND NVL(AC.HAS_FROZEN, 0)   = 0)))
            OR (P_IS_ACTIVE = 0 AND
                ((P_THE_MONTH IS NOT NULL AND NVL(EM.IS_ACTIVE, 0) = 0)
                 OR (P_THE_MONTH IS NULL AND NVL(E.IS_ACTIVE, 0) = 0
                                        AND NVL(AC.HAS_DECEASED, 0) = 0
                                        AND NVL(AC.HAS_FROZEN, 0)   = 0)))
            OR (P_IS_ACTIVE = 2 AND NVL(AC.HAS_DECEASED, 0) = 1)
            OR (P_IS_ACTIVE = 4 AND NVL(AC.HAS_FROZEN, 0)   = 1))
       -- 🆕 P_HAS_ACC: 1=نشط، 0=بدون، 2=محفظة، 3=بنك
       AND (P_HAS_ACC   IS NULL
            OR (P_HAS_ACC = 1 AND NVL(AC.ACTIVE_COUNT, 0) > 0)
            OR (P_HAS_ACC = 0 AND NVL(AC.ACTIVE_COUNT, 0) = 0)
            OR (P_HAS_ACC = 2 AND NVL(AC.WALLET_COUNT, 0) > 0)
            OR (P_HAS_ACC = 3 AND NVL(AC.BANK_COUNT,   0) > 0))
       AND (P_HAS_BENEF IS NULL
            OR (P_HAS_BENEF = 1 AND NVL(BN.BENEF_COUNT, 0) > 0)
            OR (P_HAS_BENEF = 0 AND NVL(BN.BENEF_COUNT, 0) = 0));
    P_MSG_OUT := '1';
  EXCEPTION WHEN OTHERS THEN
    P_CNT_OUT := 0;
    P_MSG_OUT := SQLERRM;
  END;

  PROCEDURE EMPLOYEES_TOTALS (
      P_EMP_NO       NUMBER DEFAULT NULL,
      P_BRANCH_NO    NUMBER DEFAULT NULL,
      P_IS_ACTIVE    NUMBER DEFAULT NULL,
      P_HAS_ACC      NUMBER DEFAULT NULL,
      P_HAS_BENEF    NUMBER DEFAULT NULL,
      P_THE_MONTH    NUMBER DEFAULT NULL,
      P_TOTAL_OUT        OUT NUMBER,
      P_BANK_OUT         OUT NUMBER,
      P_WALLET_OUT       OUT NUMBER,
      P_BENEF_OUT        OUT NUMBER,
      P_MSG_OUT          OUT VARCHAR2
  ) IS
  BEGIN
    WITH ACC_AGG AS (
      SELECT A.EMP_NO,
             COUNT(*) AS ACC_COUNT,
             SUM(CASE WHEN A.IS_ACTIVE = 1 THEN 1 ELSE 0 END) AS ACTIVE_COUNT,
             SUM(CASE WHEN A.IS_ACTIVE = 1 AND PP.PROVIDER_TYPE = 1 THEN 1 ELSE 0 END) AS BANK_COUNT,
             SUM(CASE WHEN A.IS_ACTIVE = 1 AND PP.PROVIDER_TYPE = 2 THEN 1 ELSE 0 END) AS WALLET_COUNT,
             MAX(CASE WHEN A.INACTIVE_REASON = 2 THEN 1 ELSE 0 END) AS HAS_DECEASED,
             MAX(CASE WHEN A.INACTIVE_REASON = 4 THEN 1 ELSE 0 END) AS HAS_FROZEN
        FROM GFC.PAYMENT_ACCOUNTS_TB A
        LEFT JOIN GFC.PAYMENT_PROVIDERS_TB PP ON PP.PROVIDER_ID = A.PROVIDER_ID
       WHERE A.STATUS = 1
       GROUP BY A.EMP_NO
    ),
    BNF_AGG AS (
      SELECT B.EMP_NO, COUNT(*) AS BENEF_CNT
        FROM GFC.PAYMENT_BENEFICIARIES_TB B
       WHERE B.STATUS = 1
       GROUP BY B.EMP_NO
    )
    SELECT COUNT(*),
           NVL(SUM(NVL(AC.BANK_COUNT, 0)), 0),
           NVL(SUM(NVL(AC.WALLET_COUNT, 0)), 0),
           NVL(SUM(NVL(BN.BENEF_CNT, 0)), 0)
      INTO P_TOTAL_OUT, P_BANK_OUT, P_WALLET_OUT, P_BENEF_OUT
      FROM DATA.EMPLOYEES E
      LEFT JOIN ACC_AGG AC ON AC.EMP_NO = E.NO
      LEFT JOIN BNF_AGG BN ON BN.EMP_NO = E.NO
      LEFT JOIN DATA.EMPLOYEES_MONTH EM ON EM.NO = E.NO AND EM.THE_MONTH = P_THE_MONTH
     WHERE (P_EMP_NO    IS NULL OR E.NO = P_EMP_NO)
       AND (P_BRANCH_NO IS NULL OR EMP_PKG.GET_EMP_BRANCH(E.NO) = P_BRANCH_NO)
       AND (P_THE_MONTH IS NULL OR EM.NO IS NOT NULL)
       -- ⚠️ نفس المنطق التاريخي: لو شهر مُحدّد → نتجاهل HAS_DECEASED/HAS_FROZEN (الحالة الحالية)
       AND (P_IS_ACTIVE IS NULL
            OR (P_IS_ACTIVE = 1 AND
                ((P_THE_MONTH IS NOT NULL AND NVL(EM.IS_ACTIVE, 0) = 1)
                 OR (P_THE_MONTH IS NULL AND NVL(E.IS_ACTIVE, 0) = 1
                                        AND NVL(AC.HAS_DECEASED, 0) = 0
                                        AND NVL(AC.HAS_FROZEN, 0)   = 0)))
            OR (P_IS_ACTIVE = 0 AND
                ((P_THE_MONTH IS NOT NULL AND NVL(EM.IS_ACTIVE, 0) = 0)
                 OR (P_THE_MONTH IS NULL AND NVL(E.IS_ACTIVE, 0) = 0
                                        AND NVL(AC.HAS_DECEASED, 0) = 0
                                        AND NVL(AC.HAS_FROZEN, 0)   = 0)))
            OR (P_IS_ACTIVE = 2 AND NVL(AC.HAS_DECEASED, 0) = 1)
            OR (P_IS_ACTIVE = 4 AND NVL(AC.HAS_FROZEN, 0)   = 1))
       -- 🆕 P_HAS_ACC: 1=نشط، 0=بدون، 2=محفظة، 3=بنك
       AND (P_HAS_ACC   IS NULL
            OR (P_HAS_ACC = 1 AND NVL(AC.ACTIVE_COUNT, 0) > 0)
            OR (P_HAS_ACC = 0 AND NVL(AC.ACTIVE_COUNT, 0) = 0)
            OR (P_HAS_ACC = 2 AND NVL(AC.WALLET_COUNT, 0) > 0)
            OR (P_HAS_ACC = 3 AND NVL(AC.BANK_COUNT,   0) > 0))
       AND (P_HAS_BENEF IS NULL
            OR (P_HAS_BENEF = 1 AND NVL(BN.BENEF_CNT, 0) > 0)
            OR (P_HAS_BENEF = 0 AND NVL(BN.BENEF_CNT, 0) = 0));
    P_MSG_OUT := '1';
  EXCEPTION WHEN OTHERS THEN
    P_TOTAL_OUT := 0; P_BANK_OUT := 0; P_WALLET_OUT := 0; P_BENEF_OUT := 0;
    P_MSG_OUT := SQLERRM;
  END;

  PROCEDURE EMPLOYEE_GET (
      P_EMP_NO      NUMBER,
      P_REF_CUR_OUT OUT SYS_REFCURSOR,
      P_MSG_OUT     OUT VARCHAR2
  ) IS
  BEGIN
    OPEN P_REF_CUR_OUT FOR
      SELECT E.NO                              AS EMP_NO,
             E.NAME                            AS EMP_NAME,
             E.ID                              AS ID_NO,
             E.TEL                             AS TEL,
             E.IS_ACTIVE                       AS IS_ACTIVE,
             E.BANK                            AS LEGACY_BANK,
             E.ACCOUNT                         AS LEGACY_ACCOUNT,
             E.IBAN                            AS LEGACY_IBAN,
             E.MASTER_BANKS_EMAIL              AS LEGACY_MASTER,
             EMP_PKG.GET_EMP_BRANCH_NAME(E.NO) AS BRANCH_NAME,
             EMP_PKG.GET_EMP_BRANCH(E.NO)      AS BRANCH_NO,
             (SELECT COUNT(*) FROM GFC.PAYMENT_ACCOUNTS_TB A
               WHERE A.EMP_NO = E.NO AND A.STATUS = 1)                AS ACC_COUNT,
             (SELECT COUNT(*) FROM GFC.PAYMENT_ACCOUNTS_TB A
               WHERE A.EMP_NO = E.NO AND A.STATUS = 1 AND A.IS_ACTIVE = 1) AS ACTIVE_COUNT,
             (SELECT COUNT(*) FROM GFC.PAYMENT_BENEFICIARIES_TB B
               WHERE B.EMP_NO = E.NO AND B.STATUS = 1)                AS BENEF_COUNT
        FROM DATA.EMPLOYEES E
       WHERE E.NO = P_EMP_NO;
    P_MSG_OUT := '1';
  EXCEPTION WHEN OTHERS THEN P_MSG_OUT := SQLERRM;
  END;


  -- ============================================================
  -- 8) Bulk Operations (نسخ أولية — ستتوسع مع الشاشات)
  -- ============================================================

  PROCEDURE BULK_APPLY (
      P_EMPS_CSV     VARCHAR2,
      P_ACTION       VARCHAR2,
      P_PARAMS_JSON  CLOB,
      P_MSG_OUT      OUT VARCHAR2
  ) IS
    V_OK   NUMBER := 0;
    V_ERR  NUMBER := 0;
    V_EMP  NUMBER;
  BEGIN
    -- loop على قائمة الموظفين
    FOR R IN (
      SELECT REGEXP_SUBSTR(P_EMPS_CSV, '[^,]+', 1, LEVEL) AS EMP
        FROM DUAL
     CONNECT BY REGEXP_SUBSTR(P_EMPS_CSV, '[^,]+', 1, LEVEL) IS NOT NULL
    ) LOOP
      BEGIN
        V_EMP := TO_NUMBER(TRIM(R.EMP));

        -- نفّذ الإجراء حسب نوعه
        CASE P_ACTION
          WHEN 'DEACTIVATE_ALL' THEN
            UPDATE GFC.PAYMENT_ACCOUNTS_TB
               SET IS_ACTIVE = 0, UPDATE_USER = USER_PKG.GET_USER_ID, UPDATE_DATE = SYSDATE
             WHERE EMP_NO = V_EMP AND STATUS = 1;

          -- TODO: إضافة المزيد من الإجراءات (ADD_WALLET, TRANSFER_BANK, ...)
          ELSE
            NULL;
        END CASE;

        V_OK := V_OK + 1;
      EXCEPTION WHEN OTHERS THEN V_ERR := V_ERR + 1;
      END;
    END LOOP;

    P_MSG_OUT := '1|تم تنفيذ ' || V_OK || ' — فشل ' || V_ERR;
  EXCEPTION WHEN OTHERS THEN P_MSG_OUT := SQLERRM;
  END;

  PROCEDURE BULK_FROM_JSON (
      P_ROWS_JSON CLOB,
      P_MSG_OUT   OUT VARCHAR2
  ) IS
  BEGIN
    -- TODO: تحليل JSON وإنشاء حسابات متعددة
    -- سيتم توسيعه عند بناء شاشة استيراد Excel
    P_MSG_OUT := 'BULK_FROM_JSON — قيد التطوير';
  END;

  -- ============================================================
  -- LINK_ACCOUNTS_TO_BENEF_AUTO
  -- يربط الحسابات الموجودة (بدون BENEFICIARY_ID) بمستفيدين الموظف
  -- المطابقة:
  --   1) أولوية: OWNER_ID_NO = BENEF.ID_NO (هوية صاحب الحساب)
  --   2) ثم: OWNER_NAME = BENEF.NAME (تطابق الاسم تماماً)
  -- يُحدّث الحساب فقط لو OWNER_NAME ≠ اسم الموظف نفسه
  -- ============================================================
  PROCEDURE LINK_ACCOUNTS_TO_BENEF_AUTO (
      P_EMP_NO       NUMBER,
      P_LINKED_OUT   OUT NUMBER,
      P_MSG_OUT      OUT VARCHAR2
  ) IS
    V_EMP_NAME VARCHAR2(200);
    V_LINKED   NUMBER := 0;
  BEGIN
    SELECT NAME INTO V_EMP_NAME FROM DATA.EMPLOYEES WHERE NO = P_EMP_NO;

    -- المرحلة 1: مطابقة بالهوية
    UPDATE GFC.PAYMENT_ACCOUNTS_TB A
       SET A.BENEFICIARY_ID = (
             SELECT MAX(B.BENEFICIARY_ID)
               FROM GFC.PAYMENT_BENEFICIARIES_TB B
              WHERE B.EMP_NO = A.EMP_NO
                AND B.STATUS = 1
                AND B.ID_NO = A.OWNER_ID_NO
                AND A.OWNER_ID_NO IS NOT NULL
           ),
           A.UPDATE_DATE = SYSDATE
     WHERE A.EMP_NO = P_EMP_NO
       AND A.STATUS = 1
       AND A.BENEFICIARY_ID IS NULL
       AND A.OWNER_ID_NO    IS NOT NULL
       AND EXISTS (
             SELECT 1 FROM GFC.PAYMENT_BENEFICIARIES_TB B
              WHERE B.EMP_NO = A.EMP_NO
                AND B.STATUS = 1
                AND B.ID_NO = A.OWNER_ID_NO
           );
    V_LINKED := V_LINKED + SQL%ROWCOUNT;

    -- المرحلة 2: مطابقة بالاسم (للمتبقّين)
    UPDATE GFC.PAYMENT_ACCOUNTS_TB A
       SET A.BENEFICIARY_ID = (
             SELECT MAX(B.BENEFICIARY_ID)
               FROM GFC.PAYMENT_BENEFICIARIES_TB B
              WHERE B.EMP_NO = A.EMP_NO
                AND B.STATUS = 1
                AND TRIM(B.NAME) = TRIM(A.OWNER_NAME)
           ),
           A.UPDATE_DATE = SYSDATE
     WHERE A.EMP_NO = P_EMP_NO
       AND A.STATUS = 1
       AND A.BENEFICIARY_ID IS NULL
       AND A.OWNER_NAME     IS NOT NULL
       AND TRIM(A.OWNER_NAME) <> TRIM(V_EMP_NAME)   -- استثناء حسابات الموظف نفسه
       AND EXISTS (
             SELECT 1 FROM GFC.PAYMENT_BENEFICIARIES_TB B
              WHERE B.EMP_NO = A.EMP_NO
                AND B.STATUS = 1
                AND TRIM(B.NAME) = TRIM(A.OWNER_NAME)
           );
    V_LINKED := V_LINKED + SQL%ROWCOUNT;

    COMMIT;
    P_LINKED_OUT := V_LINKED;
    P_MSG_OUT    := '1';
  EXCEPTION WHEN OTHERS THEN
    ROLLBACK;
    P_LINKED_OUT := 0;
    P_MSG_OUT    := SQLERRM;
  END;

  -- ============================================================
  -- LINK_ACCOUNTS_BULK_AUTO — ربط تلقائي لكل الموظفين دفعة واحدة
  -- ============================================================
  PROCEDURE LINK_ACCOUNTS_BULK_AUTO (
      P_LINKED_OUT       OUT NUMBER,
      P_EMPS_AFFECTED_OUT OUT NUMBER,
      P_MSG_OUT          OUT VARCHAR2
  ) IS
    V_TOTAL_LINKED NUMBER := 0;
    V_EMPS_CNT     NUMBER := 0;
    V_LINKED       NUMBER;
    V_MSG          VARCHAR2(500);
  BEGIN
    -- لكل موظف عنده مستفيدون + حسابات بدون ربط
    FOR R IN (
      SELECT DISTINCT B.EMP_NO
        FROM GFC.PAYMENT_BENEFICIARIES_TB B
       WHERE B.STATUS = 1
         AND EXISTS (SELECT 1 FROM GFC.PAYMENT_ACCOUNTS_TB A
                      WHERE A.EMP_NO = B.EMP_NO
                        AND A.STATUS = 1
                        AND A.BENEFICIARY_ID IS NULL)
    ) LOOP
      LINK_ACCOUNTS_TO_BENEF_AUTO(R.EMP_NO, V_LINKED, V_MSG);
      IF V_MSG = '1' AND V_LINKED > 0 THEN
        V_TOTAL_LINKED := V_TOTAL_LINKED + V_LINKED;
        V_EMPS_CNT     := V_EMPS_CNT + 1;
      END IF;
    END LOOP;

    P_LINKED_OUT       := V_TOTAL_LINKED;
    P_EMPS_AFFECTED_OUT := V_EMPS_CNT;
    P_MSG_OUT          := '1';
  EXCEPTION WHEN OTHERS THEN
    P_LINKED_OUT       := 0;
    P_EMPS_AFFECTED_OUT := 0;
    P_MSG_OUT          := SQLERRM;
  END;

  -- ============================================================
  -- HEALTH CHECK — فحص صحة البيانات
  -- ============================================================
  PROCEDURE HEALTH_OVERVIEW (
      P_EMP_NO_ACC_OUT          OUT NUMBER,
      P_ACC_NO_IBAN_OUT         OUT NUMBER,
      P_BENEF_UNLINKED_OUT      OUT NUMBER,
      P_ACC_INACTIVE_ONLY_OUT   OUT NUMBER,
      P_PROV_INCOMPLETE_OUT     OUT NUMBER,
      P_BENEF_EXPIRED_OUT       OUT NUMBER,
      P_EMP_DUP_DEFAULT_OUT     OUT NUMBER,
      P_TOTAL_EMPS_OUT          OUT NUMBER,
      P_TOTAL_ACCOUNTS_OUT      OUT NUMBER,
      P_TOTAL_BENEF_OUT         OUT NUMBER,
      P_MSG_OUT                 OUT VARCHAR2
  ) IS
  BEGIN
    -- موظفون بدون حسابات نشطة (يدخل فقط الفعّالون)
    SELECT COUNT(*) INTO P_EMP_NO_ACC_OUT
      FROM DATA.EMPLOYEES E
     WHERE NVL(E.IS_ACTIVE, 0) = 1
       AND NOT EXISTS (
          SELECT 1 FROM GFC.PAYMENT_ACCOUNTS_TB A
           WHERE A.EMP_NO = E.NO AND A.STATUS = 1 AND A.IS_ACTIVE = 1
       );

    -- حسابات بنكية بـ IBAN غير صحيح (طول < 20 أو فيه ?)
    SELECT COUNT(*) INTO P_ACC_NO_IBAN_OUT
      FROM GFC.PAYMENT_ACCOUNTS_TB A
      JOIN GFC.PAYMENT_PROVIDERS_TB P ON P.PROVIDER_ID = A.PROVIDER_ID
     WHERE A.STATUS = 1 AND A.IS_ACTIVE = 1
       AND P.PROVIDER_TYPE = 1   -- بنوك فقط (المحافظ ما تحتاج IBAN)
       AND (A.IBAN IS NULL OR LENGTH(TRIM(A.IBAN)) < 20 OR A.IBAN LIKE '%?%');

    -- موظفون عندهم مستفيدون لكن حساباتهم غير مربوطة بمستفيد
    SELECT COUNT(DISTINCT B.EMP_NO) INTO P_BENEF_UNLINKED_OUT
      FROM GFC.PAYMENT_BENEFICIARIES_TB B
     WHERE B.STATUS = 1
       AND EXISTS (SELECT 1 FROM GFC.PAYMENT_ACCOUNTS_TB A
                    WHERE A.EMP_NO = B.EMP_NO
                      AND A.STATUS = 1
                      AND A.BENEFICIARY_ID IS NULL
                      AND A.OWNER_NAME IS NOT NULL);

    -- موظفون بحسابات موقوفة فقط (ما عندهم نشطة)
    SELECT COUNT(*) INTO P_ACC_INACTIVE_ONLY_OUT
      FROM DATA.EMPLOYEES E
     WHERE EXISTS (SELECT 1 FROM GFC.PAYMENT_ACCOUNTS_TB A
                    WHERE A.EMP_NO = E.NO AND A.STATUS = 1 AND A.IS_ACTIVE = 0)
       AND NOT EXISTS (SELECT 1 FROM GFC.PAYMENT_ACCOUNTS_TB A
                        WHERE A.EMP_NO = E.NO AND A.STATUS = 1 AND A.IS_ACTIVE = 1);

    -- مزودون بدون IBAN الشركة (للبنوك) أو بدون EXPORT_FORMAT
    SELECT COUNT(*) INTO P_PROV_INCOMPLETE_OUT
      FROM GFC.PAYMENT_PROVIDERS_TB P
     WHERE P.IS_ACTIVE = 1
       AND ( (P.PROVIDER_TYPE = 1 AND (P.COMPANY_IBAN IS NULL OR LENGTH(TRIM(P.COMPANY_IBAN)) < 20 OR P.COMPANY_IBAN LIKE '%?%'))
          OR P.EXPORT_FORMAT IS NULL );

    -- مستفيدون منتهون (TO_DATE < اليوم) لكن لسه STATUS = 1
    SELECT COUNT(*) INTO P_BENEF_EXPIRED_OUT
      FROM GFC.PAYMENT_BENEFICIARIES_TB B
     WHERE B.STATUS = 1
       AND B.TO_DATE IS NOT NULL
       AND B.TO_DATE < TRUNC(SYSDATE);

    -- موظفون بأكثر من حساب افتراضي (IS_DEFAULT=1)
    SELECT COUNT(*) INTO P_EMP_DUP_DEFAULT_OUT
      FROM (SELECT EMP_NO FROM GFC.PAYMENT_ACCOUNTS_TB
             WHERE STATUS = 1 AND IS_ACTIVE = 1 AND IS_DEFAULT = 1
             GROUP BY EMP_NO HAVING COUNT(*) > 1);

    -- إجماليات
    SELECT COUNT(*) INTO P_TOTAL_EMPS_OUT FROM DATA.EMPLOYEES;
    SELECT COUNT(*) INTO P_TOTAL_ACCOUNTS_OUT FROM GFC.PAYMENT_ACCOUNTS_TB WHERE STATUS = 1;
    SELECT COUNT(*) INTO P_TOTAL_BENEF_OUT FROM GFC.PAYMENT_BENEFICIARIES_TB WHERE STATUS = 1;

    P_MSG_OUT := '1';
  EXCEPTION WHEN OTHERS THEN
    P_MSG_OUT := SQLERRM;
  END;

  PROCEDURE HEALTH_LIST (
      P_CATEGORY     VARCHAR2,
      P_BRANCH_NO    NUMBER DEFAULT NULL,
      P_IS_ACTIVE    NUMBER DEFAULT NULL,
      P_OFFSET       NUMBER DEFAULT 0,
      P_LIMIT        NUMBER DEFAULT 200,
      P_REF_CUR_OUT  OUT SYS_REFCURSOR,
      P_MSG_OUT      OUT VARCHAR2
  ) IS
    V_OFFSET NUMBER := NVL(P_OFFSET, 0);
    V_LIMIT  NUMBER := NVL(P_LIMIT, 200);
    V_CAT    VARCHAR2(50) := UPPER(TRIM(P_CATEGORY));
  BEGIN
    IF V_CAT = 'EMP_NO_ACC' THEN
      OPEN P_REF_CUR_OUT FOR
        SELECT * FROM (
          SELECT E.NO AS EMP_NO, E.NAME AS EMP_NAME,
                 EMP_PKG.GET_EMP_BRANCH_NAME(E.NO) AS BRANCH_NAME,
                 NVL(E.IS_ACTIVE, 0) AS IS_ACTIVE,
                 NULL AS ACC_ID, NULL AS DETAIL_INFO,
                 ROW_NUMBER() OVER (ORDER BY E.NO) AS RN
            FROM DATA.EMPLOYEES E
           WHERE NVL(E.IS_ACTIVE, 0) = 1
             AND (P_BRANCH_NO IS NULL OR EMP_PKG.GET_EMP_BRANCH(E.NO) = P_BRANCH_NO)
             AND NOT EXISTS (SELECT 1 FROM GFC.PAYMENT_ACCOUNTS_TB A
                              WHERE A.EMP_NO = E.NO AND A.STATUS = 1 AND A.IS_ACTIVE = 1)
        ) WHERE RN BETWEEN V_OFFSET + 1 AND V_OFFSET + V_LIMIT
          ORDER BY RN;

    ELSIF V_CAT = 'ACC_NO_IBAN' THEN
      OPEN P_REF_CUR_OUT FOR
        SELECT * FROM (
          SELECT A.EMP_NO, E.NAME AS EMP_NAME,
                 EMP_PKG.GET_EMP_BRANCH_NAME(A.EMP_NO) AS BRANCH_NAME,
                 NVL(E.IS_ACTIVE, 0) AS IS_ACTIVE,
                 A.ACC_ID,
                 P.PROVIDER_NAME || ' — حساب ' || NVL(A.ACCOUNT_NO, A.WALLET_NUMBER) ||
                   CASE WHEN A.IBAN IS NULL THEN ' (IBAN فارغ)'
                        WHEN A.IBAN LIKE '%?%' THEN ' (IBAN غير مكتمل)'
                        ELSE ' (IBAN قصير: ' || LENGTH(TRIM(A.IBAN)) || ')' END AS DETAIL_INFO,
                 ROW_NUMBER() OVER (ORDER BY A.EMP_NO) AS RN
            FROM GFC.PAYMENT_ACCOUNTS_TB A
            JOIN GFC.PAYMENT_PROVIDERS_TB P ON P.PROVIDER_ID = A.PROVIDER_ID
            LEFT JOIN DATA.EMPLOYEES E ON E.NO = A.EMP_NO
           WHERE A.STATUS = 1 AND A.IS_ACTIVE = 1
             AND P.PROVIDER_TYPE = 1
             AND (A.IBAN IS NULL OR LENGTH(TRIM(A.IBAN)) < 20 OR A.IBAN LIKE '%?%')
             AND (P_BRANCH_NO IS NULL OR EMP_PKG.GET_EMP_BRANCH(A.EMP_NO) = P_BRANCH_NO)
        ) WHERE RN BETWEEN V_OFFSET + 1 AND V_OFFSET + V_LIMIT;

    ELSIF V_CAT = 'BENEF_UNLINKED' THEN
      OPEN P_REF_CUR_OUT FOR
        SELECT * FROM (
          SELECT E.NO AS EMP_NO, E.NAME AS EMP_NAME,
                 EMP_PKG.GET_EMP_BRANCH_NAME(E.NO) AS BRANCH_NAME,
                 NVL(E.IS_ACTIVE, 0) AS IS_ACTIVE,
                 NULL AS ACC_ID,
                 (SELECT COUNT(*) FROM GFC.PAYMENT_BENEFICIARIES_TB B WHERE B.EMP_NO = E.NO AND B.STATUS = 1)
                 || ' مستفيد + ' ||
                 (SELECT COUNT(*) FROM GFC.PAYMENT_ACCOUNTS_TB A WHERE A.EMP_NO = E.NO AND A.STATUS = 1 AND A.BENEFICIARY_ID IS NULL AND A.OWNER_NAME IS NOT NULL)
                 || ' حساب غير مربوط' AS DETAIL_INFO,
                 ROW_NUMBER() OVER (ORDER BY E.NO) AS RN
            FROM DATA.EMPLOYEES E
           WHERE EXISTS (SELECT 1 FROM GFC.PAYMENT_BENEFICIARIES_TB B WHERE B.EMP_NO = E.NO AND B.STATUS = 1)
             AND EXISTS (SELECT 1 FROM GFC.PAYMENT_ACCOUNTS_TB A
                          WHERE A.EMP_NO = E.NO AND A.STATUS = 1
                            AND A.BENEFICIARY_ID IS NULL AND A.OWNER_NAME IS NOT NULL)
             AND (P_BRANCH_NO IS NULL OR EMP_PKG.GET_EMP_BRANCH(E.NO) = P_BRANCH_NO)
        ) WHERE RN BETWEEN V_OFFSET + 1 AND V_OFFSET + V_LIMIT;

    ELSIF V_CAT = 'ACC_INACTIVE_ONLY' THEN
      OPEN P_REF_CUR_OUT FOR
        SELECT * FROM (
          SELECT E.NO AS EMP_NO, E.NAME AS EMP_NAME,
                 EMP_PKG.GET_EMP_BRANCH_NAME(E.NO) AS BRANCH_NAME,
                 NVL(E.IS_ACTIVE, 0) AS IS_ACTIVE,
                 NULL AS ACC_ID,
                 (SELECT COUNT(*) FROM GFC.PAYMENT_ACCOUNTS_TB A
                   WHERE A.EMP_NO = E.NO AND A.STATUS = 1 AND A.IS_ACTIVE = 0) || ' حساب موقوف' AS DETAIL_INFO,
                 ROW_NUMBER() OVER (ORDER BY E.NO) AS RN
            FROM DATA.EMPLOYEES E
           WHERE EXISTS (SELECT 1 FROM GFC.PAYMENT_ACCOUNTS_TB A
                          WHERE A.EMP_NO = E.NO AND A.STATUS = 1 AND A.IS_ACTIVE = 0)
             AND NOT EXISTS (SELECT 1 FROM GFC.PAYMENT_ACCOUNTS_TB A
                              WHERE A.EMP_NO = E.NO AND A.STATUS = 1 AND A.IS_ACTIVE = 1)
             AND (P_BRANCH_NO IS NULL OR EMP_PKG.GET_EMP_BRANCH(E.NO) = P_BRANCH_NO)
        ) WHERE RN BETWEEN V_OFFSET + 1 AND V_OFFSET + V_LIMIT;

    ELSIF V_CAT = 'PROV_INCOMPLETE' THEN
      OPEN P_REF_CUR_OUT FOR
        SELECT NULL AS EMP_NO, P.PROVIDER_NAME AS EMP_NAME,
               '' AS BRANCH_NAME, P.IS_ACTIVE,
               P.PROVIDER_ID AS ACC_ID,
               CASE
                 WHEN P.PROVIDER_TYPE = 1 AND (P.COMPANY_IBAN IS NULL OR LENGTH(TRIM(P.COMPANY_IBAN)) < 20 OR P.COMPANY_IBAN LIKE '%?%')
                      AND P.EXPORT_FORMAT IS NULL THEN 'IBAN ناقص + EXPORT_FORMAT ناقص'
                 WHEN P.PROVIDER_TYPE = 1 AND (P.COMPANY_IBAN IS NULL OR LENGTH(TRIM(P.COMPANY_IBAN)) < 20 OR P.COMPANY_IBAN LIKE '%?%')
                      THEN 'IBAN الشركة ناقص'
                 WHEN P.EXPORT_FORMAT IS NULL THEN 'EXPORT_FORMAT ناقص (يلزم للتصدير)'
               END AS DETAIL_INFO
          FROM GFC.PAYMENT_PROVIDERS_TB P
         WHERE P.IS_ACTIVE = 1
           AND ( (P.PROVIDER_TYPE = 1 AND (P.COMPANY_IBAN IS NULL OR LENGTH(TRIM(P.COMPANY_IBAN)) < 20 OR P.COMPANY_IBAN LIKE '%?%'))
              OR P.EXPORT_FORMAT IS NULL );

    ELSIF V_CAT = 'BENEF_EXPIRED' THEN
      OPEN P_REF_CUR_OUT FOR
        SELECT B.EMP_NO, E.NAME AS EMP_NAME,
               EMP_PKG.GET_EMP_BRANCH_NAME(B.EMP_NO) AS BRANCH_NAME,
               NVL(E.IS_ACTIVE, 0) AS IS_ACTIVE,
               B.BENEFICIARY_ID AS ACC_ID,
               'انتهى في ' || TO_CHAR(B.TO_DATE, 'YYYY-MM-DD') || ' — ' || B.NAME AS DETAIL_INFO
          FROM GFC.PAYMENT_BENEFICIARIES_TB B
          LEFT JOIN DATA.EMPLOYEES E ON E.NO = B.EMP_NO
         WHERE B.STATUS = 1
           AND B.TO_DATE IS NOT NULL
           AND B.TO_DATE < TRUNC(SYSDATE)
           AND (P_BRANCH_NO IS NULL OR EMP_PKG.GET_EMP_BRANCH(B.EMP_NO) = P_BRANCH_NO)
         ORDER BY B.TO_DATE DESC;

    ELSIF V_CAT = 'EMP_DUP_DEFAULT' THEN
      OPEN P_REF_CUR_OUT FOR
        SELECT D.EMP_NO, E.NAME AS EMP_NAME,
               EMP_PKG.GET_EMP_BRANCH_NAME(D.EMP_NO) AS BRANCH_NAME,
               NVL(E.IS_ACTIVE, 0) AS IS_ACTIVE,
               NULL AS ACC_ID,
               D.CNT || ' حسابات مُحدّدة كافتراضي (المفروض واحد فقط)' AS DETAIL_INFO
          FROM (SELECT EMP_NO, COUNT(*) AS CNT
                  FROM GFC.PAYMENT_ACCOUNTS_TB
                 WHERE STATUS = 1 AND IS_ACTIVE = 1 AND IS_DEFAULT = 1
                 GROUP BY EMP_NO HAVING COUNT(*) > 1) D
          LEFT JOIN DATA.EMPLOYEES E ON E.NO = D.EMP_NO
         WHERE (P_BRANCH_NO IS NULL OR EMP_PKG.GET_EMP_BRANCH(D.EMP_NO) = P_BRANCH_NO);

    ELSE
      OPEN P_REF_CUR_OUT FOR SELECT NULL AS EMP_NO FROM DUAL WHERE 1 = 0;
    END IF;

    P_MSG_OUT := '1';
  EXCEPTION WHEN OTHERS THEN P_MSG_OUT := SQLERRM;
  END;


END PAYMENT_ACCOUNTS_PKG;
/
