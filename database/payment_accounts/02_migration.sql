-- ============================================================
-- Payment Accounts Module — Migration (v2)
-- ============================================================
-- ينقل البيانات من الجداول الأصلية إلى الموديول الجديد:
--
--   DATA.MASTER_BANKS_EMAIL → GFC.PAYMENT_PROVIDERS_TB
--                             (البنوك الرئيسية + المحافظ المسجّلة)
--   + إضافة يدوية: Jawwal Pay (غير موجود في MASTER_BANKS_EMAIL)
--
--   DATA.BANKS               → GFC.PAYMENT_BANK_BRANCHES_TB
--                             (فروع البنوك مع محاولة ربط بالبنك الرئيسي)
--
--   DATA.EMPLOYEES           → GFC.PAYMENT_ACCOUNTS_TB
--                             (BANK + ACCOUNT + IBAN + MASTER_BANKS_EMAIL)
--
-- خصائص:
--   ✅ Idempotent — يمكن تشغيله أكثر من مرة بلا تكرار
--   ✅ استخدام LEGACY_BANK_NO كمفتاح ربط مع البيانات القديمة
--   ✅ تقرير مفصّل بعد كل قسم
-- ============================================================

SET SERVEROUTPUT ON SIZE UNLIMITED;
SET LINESIZE 200;


-- ============================================================
-- 0) فحص ما قبل Migration — شغّله أولاً
-- ============================================================

-- ① ملخص DATA.MASTER_BANKS_EMAIL
PROMPT === ① DATA.MASTER_BANKS_EMAIL (مصدر المزودين) ===
SELECT B_NO, B_NAME, ACCOUNT_NO, ACCOUNT_ID
  FROM DATA.MASTER_BANKS_EMAIL
 ORDER BY B_NO;

-- ② ملخص DATA.BANK_BRANCH
PROMPT === ② DATA.BANK_BRANCH (مصدر الفروع) ===
SELECT COUNT(*) AS TOTAL_BRANCHES FROM DATA.BANK_BRANCH;

-- فحص: هل كل الفروع B_NO مُعرَّفة في MASTER_BANKS_EMAIL
SELECT BB.B_NO,
       COUNT(*)                           AS BRANCHES_COUNT,
       CASE WHEN M.B_NO IS NULL
            THEN '❌ B_NO=' || BB.B_NO || ' غير موجود في MASTER_BANKS_EMAIL'
            ELSE '✅ OK (' || M.B_NAME || ')'
       END                                AS STATUS
  FROM DATA.BANK_BRANCH BB
  LEFT JOIN DATA.MASTER_BANKS_EMAIL M ON M.B_NO = BB.B_NO
 GROUP BY BB.B_NO, M.B_NO, M.B_NAME
 ORDER BY BB.B_NO;

-- ③ ملخص DATA.EMPLOYEES
PROMPT === ③ DATA.EMPLOYEES ===
SELECT
  COUNT(*)                                                     AS TOTAL_EMPLOYEES,
  COUNT(CASE WHEN BANK IS NOT NULL AND ACCOUNT IS NOT NULL
             THEN 1 END)                                       AS WITH_BANK,
  COUNT(CASE WHEN IBAN IS NOT NULL THEN 1 END)                 AS WITH_IBAN,
  COUNT(CASE WHEN BANK IS NULL OR ACCOUNT IS NULL THEN 1 END)  AS NO_BANK,
  COUNT(CASE WHEN MASTER_BANKS_EMAIL IS NOT NULL THEN 1 END)   AS WITH_MASTER_BANK
FROM DATA.EMPLOYEES;


-- ============================================================
-- 1) Migration: DATA.MASTER_BANKS_EMAIL → PAYMENT_PROVIDERS_TB
-- ============================================================
DECLARE
  V_INSERTED NUMBER := 0;
  V_SKIPPED  NUMBER := 0;
  V_EX       NUMBER;
  V_TYPE     NUMBER;
BEGIN
  FOR R IN (
    SELECT B_NO, B_NAME, ACCOUNT_NO, ACCOUNT_ID
      FROM DATA.MASTER_BANKS_EMAIL
     ORDER BY B_NO
  ) LOOP
    -- idempotent: تخطي لو موجود
    SELECT COUNT(*) INTO V_EX FROM GFC.PAYMENT_PROVIDERS_TB
     WHERE LEGACY_BANK_NO = R.B_NO;
    IF V_EX > 0 THEN
      V_SKIPPED := V_SKIPPED + 1;
      CONTINUE;
    END IF;

    -- تحديد النوع: محفظة لو الاسم فيه "محفظة" أو "باي"/"pay"
    IF LOWER(R.B_NAME) LIKE '%محفظة%'
       OR LOWER(R.B_NAME) LIKE '%باي%'
       OR LOWER(R.B_NAME) LIKE '%pay%'
       OR LOWER(R.B_NAME) LIKE '%wallet%' THEN
      V_TYPE := 2;  -- محفظة
    ELSE
      V_TYPE := 1;  -- بنك
    END IF;

    INSERT INTO GFC.PAYMENT_PROVIDERS_TB (
      PROVIDER_ID, PROVIDER_TYPE, PROVIDER_NAME, PROVIDER_CODE,
      LEGACY_BANK_NO, EXPORT_FORMAT,
      COMPANY_ACCOUNT_NO, COMPANY_ACCOUNT_ID, COMPANY_IBAN,
      REQUIRES_IBAN, REQUIRES_ID, REQUIRES_PHONE,
      IS_ACTIVE, SORT_ORDER, NOTES, ENTRY_DATE
    ) VALUES (
      GFC_PAK.PAYMENT_PROVIDERS_SEQ.NEXTVAL,
      V_TYPE,
      R.B_NAME,
      CASE V_TYPE WHEN 2 THEN 'WALLET' ELSE 'BANK' END || R.B_NO,
      R.B_NO,
      NULL,                                      -- EXPORT_FORMAT يُضبط لاحقاً
      R.ACCOUNT_NO,                              -- رقم الحساب من MASTER_BANKS_EMAIL
      R.ACCOUNT_ID,                              -- رقم مرجعي من MASTER_BANKS_EMAIL
      NULL,                                      -- IBAN الحقيقي يُملأ من 03_seed_ibans.sql
      CASE V_TYPE WHEN 1 THEN 1 ELSE 0 END,      -- البنوك تحتاج IBAN، المحافظ لا
      1,                                         -- يحتاج هوية
      CASE V_TYPE WHEN 2 THEN 1 ELSE 0 END,      -- المحافظ تحتاج رقم جوال
      1, R.B_NO, 'Migrated from DATA.MASTER_BANKS_EMAIL', SYSDATE
    );
    V_INSERTED := V_INSERTED + 1;
  END LOOP;

  -- إضافة Jawwal Pay يدوياً (غير موجود في MASTER_BANKS_EMAIL)
  SELECT COUNT(*) INTO V_EX FROM GFC.PAYMENT_PROVIDERS_TB
   WHERE PROVIDER_CODE = 'JAWWAL';
  IF V_EX = 0 THEN
    INSERT INTO GFC.PAYMENT_PROVIDERS_TB (
      PROVIDER_ID, PROVIDER_TYPE, PROVIDER_NAME, PROVIDER_CODE,
      LEGACY_BANK_NO, EXPORT_FORMAT, COMPANY_IBAN,
      REQUIRES_IBAN, REQUIRES_ID, REQUIRES_PHONE,
      IS_ACTIVE, SORT_ORDER, NOTES, ENTRY_DATE
    ) VALUES (
      GFC_PAK.PAYMENT_PROVIDERS_SEQ.NEXTVAL, 2, 'Jawwal Pay', 'JAWWAL',
      NULL, NULL, NULL,
      0, 1, 1, 1, 9999,
      'Added manually — تنسيق التصدير لاحقاً', SYSDATE
    );
    V_INSERTED := V_INSERTED + 1;
  ELSE
    V_SKIPPED := V_SKIPPED + 1;
  END IF;

  COMMIT;

  DBMS_OUTPUT.PUT_LINE('=======================================================');
  DBMS_OUTPUT.PUT_LINE(' STEP 1: PAYMENT_PROVIDERS_TB Migration');
  DBMS_OUTPUT.PUT_LINE('-------------------------------------------------------');
  DBMS_OUTPUT.PUT_LINE('  ✅ Inserted: ' || LPAD(V_INSERTED, 6));
  DBMS_OUTPUT.PUT_LINE('  ⏭  Skipped:  ' || LPAD(V_SKIPPED,  6));
  DBMS_OUTPUT.PUT_LINE('=======================================================');
END;
/


-- ============================================================
-- 2) Migration: DATA.BANK_BRANCH → PAYMENT_BANK_BRANCHES_TB
-- مع ربط مباشر بالبنك الرئيسي عبر B_NO (لا حاجة لمطابقة بالاسم)
-- ============================================================
DECLARE
  V_INSERTED NUMBER := 0;
  V_SKIPPED  NUMBER := 0;
  V_NO_PROV  NUMBER := 0;
  V_EX       NUMBER;
BEGIN
  FOR R IN (
    SELECT BB.B_BR_NO,
           BB.B_BR_NAME,
           BB.B_NO,
           BB.B_NO_PRINT,
           P.PROVIDER_ID
      FROM DATA.BANK_BRANCH BB
      LEFT JOIN GFC.PAYMENT_PROVIDERS_TB P ON P.LEGACY_BANK_NO = BB.B_NO
     ORDER BY BB.B_BR_NO
  ) LOOP
    -- idempotent
    SELECT COUNT(*) INTO V_EX FROM GFC.PAYMENT_BANK_BRANCHES_TB
     WHERE LEGACY_BANK_NO = R.B_BR_NO;
    IF V_EX > 0 THEN
      V_SKIPPED := V_SKIPPED + 1;
      CONTINUE;
    END IF;

    -- تتبّع الفروع التي لم تُربط (لو B_NO غير موجود في MASTER_BANKS_EMAIL)
    IF R.PROVIDER_ID IS NULL THEN
      V_NO_PROV := V_NO_PROV + 1;
    END IF;

    INSERT INTO GFC.PAYMENT_BANK_BRANCHES_TB (
      BRANCH_ID, PROVIDER_ID, BRANCH_NAME, PRINT_NO,
      LEGACY_BANK_NO, LEGACY_MASTER,
      IS_ACTIVE, STATUS, NOTES, ENTRY_DATE
    ) VALUES (
      GFC_PAK.PAYMENT_BANK_BRANCHES_SEQ.NEXTVAL,
      R.PROVIDER_ID,                             -- ✅ ربط مباشر عبر B_NO
      R.B_BR_NAME,
      R.B_NO_PRINT,
      R.B_BR_NO, R.B_NO,
      1, 1, 'Migrated from DATA.BANK_BRANCH', SYSDATE
    );
    V_INSERTED := V_INSERTED + 1;
  END LOOP;

  COMMIT;

  DBMS_OUTPUT.PUT_LINE('=======================================================');
  DBMS_OUTPUT.PUT_LINE(' STEP 2: PAYMENT_BANK_BRANCHES_TB Migration');
  DBMS_OUTPUT.PUT_LINE('-------------------------------------------------------');
  DBMS_OUTPUT.PUT_LINE('  ✅ Inserted:         ' || LPAD(V_INSERTED, 6));
  DBMS_OUTPUT.PUT_LINE('  ⏭  Skipped:          ' || LPAD(V_SKIPPED,  6));
  DBMS_OUTPUT.PUT_LINE('  ⚠ Without Provider:  ' || LPAD(V_NO_PROV,  6) || ' (B_NO غير في MASTER_BANKS_EMAIL)');
  DBMS_OUTPUT.PUT_LINE('=======================================================');
END;
/

-- عرض الفروع غير المرتبطة (لو وُجدت — للفحص)
PROMPT === الفروع غير المرتبطة ببنك رئيسي (إن وُجدت) ===
SELECT BRANCH_ID, LEGACY_BANK_NO AS B_BR_NO, BRANCH_NAME, LEGACY_MASTER AS B_NO
  FROM GFC.PAYMENT_BANK_BRANCHES_TB
 WHERE PROVIDER_ID IS NULL
 ORDER BY BRANCH_ID;


-- ============================================================
-- 4) Migration: DATA.EMPLOYEES → PAYMENT_ACCOUNTS_TB
-- يستخدم E.ID (رقم الهوية) و E.TEL (رقم الهاتف/الجوال) تلقائياً
-- ============================================================
DECLARE
  V_INSERTED NUMBER := 0;
  V_SKIPPED  NUMBER := 0;
  V_NO_PROV  NUMBER := 0;
  V_NO_BANK  NUMBER := 0;
  V_NO_BR    NUMBER := 0;
  V_TOTAL    NUMBER := 0;
  V_EX       NUMBER;
BEGIN
  FOR R IN (
    SELECT E.NO                                AS EMP_NO,
           E.NAME                              AS EMP_NAME,
           E.ID                                AS EMP_ID,       -- رقم الهوية
           E.TEL                               AS EMP_TEL,      -- رقم الهاتف
           E.BANK                              AS LEGACY_BANK,
           E.ACCOUNT                           AS ACCOUNT_NO,
           E.IBAN                              AS IBAN,
           E.MASTER_BANKS_EMAIL                AS LEGACY_MASTER,
           E.IS_ACTIVE                         AS IS_ACTIVE,
           E.TO_SALARY                         AS TO_SALARY,
           P.PROVIDER_ID                       AS PROVIDER_ID,
           BR.BRANCH_ID                        AS BRANCH_ID
      FROM DATA.EMPLOYEES E
      LEFT JOIN GFC.PAYMENT_PROVIDERS_TB    P  ON P.LEGACY_BANK_NO  = E.MASTER_BANKS_EMAIL
      LEFT JOIN GFC.PAYMENT_BANK_BRANCHES_TB BR ON BR.LEGACY_BANK_NO = E.BANK
  ) LOOP
    V_TOTAL := V_TOTAL + 1;

    -- حالة ①: بدون بيانات بنك
    IF R.LEGACY_BANK IS NULL AND R.LEGACY_MASTER IS NULL THEN
      V_NO_BANK := V_NO_BANK + 1;
      CONTINUE;
    END IF;

    -- حالة ②: البنك الرئيسي غير معرَّف في PROVIDERS_TB
    IF R.PROVIDER_ID IS NULL THEN
      V_NO_PROV := V_NO_PROV + 1;
      CONTINUE;
    END IF;

    -- حالة ③: الفرع غير معرَّف (warning فقط، نستمر بلا BRANCH_ID)
    IF R.BRANCH_ID IS NULL AND R.LEGACY_BANK IS NOT NULL THEN
      V_NO_BR := V_NO_BR + 1;
    END IF;

    -- حالة ④: الحساب موجود مسبقاً (idempotent)
    V_EX := 0;
    SELECT COUNT(*) INTO V_EX
      FROM GFC.PAYMENT_ACCOUNTS_TB
     WHERE EMP_NO      = R.EMP_NO
       AND PROVIDER_ID = R.PROVIDER_ID
       AND STATUS      = 1;
    IF V_EX > 0 THEN
      V_SKIPPED := V_SKIPPED + 1;
      CONTINUE;
    END IF;

    INSERT INTO GFC.PAYMENT_ACCOUNTS_TB (
      ACC_ID, EMP_NO, BENEFICIARY_ID, PROVIDER_ID, BRANCH_ID,
      ACCOUNT_NO, IBAN,
      OWNER_ID_NO, OWNER_NAME, OWNER_PHONE,
      IS_DEFAULT, SPLIT_TYPE, SPLIT_VALUE, SPLIT_ORDER,
      IS_ACTIVE, STATUS, NOTES, ENTRY_DATE
    ) VALUES (
      GFC_PAK.PAYMENT_ACCOUNTS_SEQ.NEXTVAL, R.EMP_NO, NULL, R.PROVIDER_ID, R.BRANCH_ID,
      R.ACCOUNT_NO, R.IBAN,
      TO_CHAR(R.EMP_ID), R.EMP_NAME, TO_CHAR(R.EMP_TEL),
      1, 3, NULL, 1,
      -- الحساب نشط افتراضياً (المحاسبة تُوقفه لاحقاً عند الوفاة/الإيقاف)
      1, 1, 'Migration - ' || TO_CHAR(SYSDATE, 'YYYY-MM-DD'),
      SYSDATE
    );
    V_INSERTED := V_INSERTED + 1;
  END LOOP;

  COMMIT;

  DBMS_OUTPUT.PUT_LINE('=======================================================');
  DBMS_OUTPUT.PUT_LINE(' STEP 4: PAYMENT_ACCOUNTS_TB Migration');
  DBMS_OUTPUT.PUT_LINE('-------------------------------------------------------');
  DBMS_OUTPUT.PUT_LINE('  Total Employees:         ' || LPAD(V_TOTAL,    6));
  DBMS_OUTPUT.PUT_LINE('  ✅ Inserted:              ' || LPAD(V_INSERTED, 6));
  DBMS_OUTPUT.PUT_LINE('  ⏭  Skipped (duplicates):   ' || LPAD(V_SKIPPED,  6));
  DBMS_OUTPUT.PUT_LINE('  ⚠ No Provider (master):    ' || LPAD(V_NO_PROV,  6));
  DBMS_OUTPUT.PUT_LINE('  ⚠ No Bank Data:            ' || LPAD(V_NO_BANK,  6));
  DBMS_OUTPUT.PUT_LINE('  ℹ No Branch (inserted):   ' || LPAD(V_NO_BR,    6));
  DBMS_OUTPUT.PUT_LINE('=======================================================');
EXCEPTION
  WHEN OTHERS THEN
    ROLLBACK;
    DBMS_OUTPUT.PUT_LINE('❌ ERROR: ' || SQLERRM);
    RAISE;
END;
/


-- ============================================================
-- 5) تحقق ما بعد Migration
-- ============================================================

-- ① إجماليات
PROMPT === ① إجماليات الجداول ===
SELECT
  (SELECT COUNT(*) FROM GFC.PAYMENT_PROVIDERS_TB)        AS TOTAL_PROVIDERS,
  (SELECT COUNT(*) FROM GFC.PAYMENT_PROVIDERS_TB WHERE PROVIDER_TYPE=1) AS BANKS,
  (SELECT COUNT(*) FROM GFC.PAYMENT_PROVIDERS_TB WHERE PROVIDER_TYPE=2) AS WALLETS,
  (SELECT COUNT(*) FROM GFC.PAYMENT_BANK_BRANCHES_TB)    AS TOTAL_BRANCHES,
  (SELECT COUNT(*) FROM GFC.PAYMENT_BANK_BRANCHES_TB WHERE PROVIDER_ID IS NOT NULL) AS BRANCHES_LINKED,
  (SELECT COUNT(*) FROM GFC.PAYMENT_ACCOUNTS_TB)         AS TOTAL_ACCOUNTS
FROM DUAL;

-- ② توزيع الحسابات حسب المزود
PROMPT === ② توزيع الحسابات حسب المزود ===
SELECT P.PROVIDER_ID,
       CASE P.PROVIDER_TYPE WHEN 1 THEN 'بنك' ELSE 'محفظة' END AS TYPE_NAME,
       P.PROVIDER_NAME,
       P.PROVIDER_CODE,
       P.LEGACY_BANK_NO,
       (SELECT COUNT(*) FROM GFC.PAYMENT_BANK_BRANCHES_TB B WHERE B.PROVIDER_ID = P.PROVIDER_ID) AS BRANCHES,
       (SELECT COUNT(*) FROM GFC.PAYMENT_ACCOUNTS_TB A WHERE A.PROVIDER_ID = P.PROVIDER_ID) AS ACCOUNTS
  FROM GFC.PAYMENT_PROVIDERS_TB P
 ORDER BY ACCOUNTS DESC, P.PROVIDER_ID;

-- ③ الموظفون بدون حساب (يحتاجون إدخال يدوي)
PROMPT === ③ الموظفون بدون حساب ===
SELECT COUNT(*) AS EMPS_WITHOUT_ACCOUNT
  FROM DATA.EMPLOYEES E
 WHERE NOT EXISTS (
   SELECT 1 FROM GFC.PAYMENT_ACCOUNTS_TB A WHERE A.EMP_NO = E.NO
 );

-- ④ تفاصيل الموظفين بدون حساب (أول 30)
PROMPT === ④ تفاصيل الموظفين بدون حساب (أول 30) ===
SELECT *
  FROM (
    SELECT E.NO        AS EMP_NO,
           E.NAME      AS EMP_NAME,
           E.BANK      AS LEGACY_BANK,
           E.MASTER_BANKS_EMAIL AS LEGACY_MASTER,
           E.ACCOUNT,
           CASE
             WHEN E.BANK IS NULL AND E.MASTER_BANKS_EMAIL IS NULL
                  THEN '❌ لا توجد بيانات بنك'
             WHEN NOT EXISTS (SELECT 1 FROM GFC.PAYMENT_PROVIDERS_TB P
                               WHERE P.LEGACY_BANK_NO = E.MASTER_BANKS_EMAIL)
                  THEN '❌ البنك الرئيسي ' || E.MASTER_BANKS_EMAIL || ' غير معرَّف'
             ELSE '⚠ حالة أخرى'
           END AS REASON
      FROM DATA.EMPLOYEES E
     WHERE NOT EXISTS (
       SELECT 1 FROM GFC.PAYMENT_ACCOUNTS_TB A WHERE A.EMP_NO = E.NO
     )
     ORDER BY E.NO
  )
 WHERE ROWNUM <= 30;

-- ⑤ عينة من الحسابات المُدخلة (تأكيد)
PROMPT === ⑤ عينة من الحسابات المُدخلة ===
SELECT * FROM (
    SELECT A.ACC_ID,
           A.EMP_NO,
           A.OWNER_NAME,
           P.PROVIDER_NAME,
           BR.BRANCH_NAME,
           A.ACCOUNT_NO,
           A.IBAN,
           A.IS_DEFAULT,
           CASE A.SPLIT_TYPE WHEN 1 THEN 'نسبة' WHEN 2 THEN 'مبلغ' WHEN 3 THEN 'كامل' END AS SPLIT
      FROM GFC.PAYMENT_ACCOUNTS_TB A
      JOIN GFC.PAYMENT_PROVIDERS_TB    P  ON P.PROVIDER_ID  = A.PROVIDER_ID
      LEFT JOIN GFC.PAYMENT_BANK_BRANCHES_TB BR ON BR.BRANCH_ID = A.BRANCH_ID
     ORDER BY A.ACC_ID DESC
  )
 WHERE ROWNUM <= 15;


-- ============================================================
-- 6) (تم الدمج) بيانات ID و TEL مُضمَّنة تلقائياً في القسم 4
-- ============================================================
-- E.ID  → PAYMENT_ACCOUNTS_TB.OWNER_ID_NO
-- E.TEL → PAYMENT_ACCOUNTS_TB.OWNER_PHONE
-- E.NAME → PAYMENT_ACCOUNTS_TB.OWNER_NAME


-- ============================================================
-- 7) Rollback (طوارئ فقط!)
-- ============================================================
-- DELETE FROM GFC.PAYMENT_ACCOUNTS_TB       WHERE NOTES LIKE 'Migration%';
-- DELETE FROM GFC.PAYMENT_BANK_BRANCHES_TB  WHERE NOTES LIKE 'Migrated%';
-- DELETE FROM GFC.PAYMENT_PROVIDERS_TB      WHERE NOTES LIKE 'Migrated%'
--                                              OR NOTES LIKE 'Added manually%';
-- COMMIT;
