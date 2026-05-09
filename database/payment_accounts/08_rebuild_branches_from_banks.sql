-- ============================================================
-- 🔧 إعادة بناء PAYMENT_BANK_BRANCHES_TB من DATA.BANKS
-- ============================================================
-- المشكلة:
--   الـ migration الأصلي نقل من DATA.BANK_BRANCH (28 صف، نظام IDs مختلف)
--   بينما النظام فعلياً يستخدم DATA.BANKS (36 صف، النظام الصحيح).
--   النتيجة: BD.BANK_NO و DATA.EMPLOYEES.BANK ما بيلاقوا مطابق في PAYMENT_BANK_BRANCHES_TB.LEGACY_BANK_NO
--
-- الحل: rebuild نظيف
--   1) نسخ احتياطي لكل الجداول المتأثرة
--   2) تعطيل الـ 28 صف الموجودين (IS_ACTIVE=0) + إفراغ LEGACY_BANK_NO (لتجنب UK conflict)
--   3) إدراج 36 صف جديد من DATA.BANKS بالمطابقة الصحيحة
--   4) تحديث PAYMENT_ACCOUNTS_TB.BRANCH_ID → الفروع الجديدة (مطابقة بالاسم + المزود)
--   5) تقرير: الحسابات اللي ما لقيناها مطابق (للمعالجة اليدوية)
--   6) تحقق نهائي
--
-- بعد الـ rebuild: PAYMENT_BANK_BRANCHES_TB = الـ source of truth الوحيد للفروع.
-- ============================================================

SET SERVEROUTPUT ON SIZE UNLIMITED;
SET LINESIZE 200;

-- ============================================================
-- (1) نسخ احتياطي
-- ============================================================
PROMPT === Step 1: Backup ===

DECLARE
  V_TS VARCHAR2(20) := TO_CHAR(SYSDATE, 'YYYYMMDD_HH24MI');
BEGIN
  EXECUTE IMMEDIATE 'CREATE TABLE GFC.PBB_BAK_'  || V_TS ||
                    ' AS SELECT * FROM GFC.PAYMENT_BANK_BRANCHES_TB';
  EXECUTE IMMEDIATE 'CREATE TABLE GFC.PA_BAK_'   || V_TS ||
                    ' AS SELECT * FROM GFC.PAYMENT_ACCOUNTS_TB';
  DBMS_OUTPUT.PUT_LINE('Backups created with TS: ' || V_TS);
END;
/

-- ============================================================
-- (2) تعطيل الصفوف الـ 28 القديمة + إفراغ LEGACY_BANK_NO
--     (عشان نتجنب UK conflict عند الإدراج)
-- ============================================================
PROMPT === Step 2: Deactivate old 28 rows ===

UPDATE GFC.PAYMENT_BANK_BRANCHES_TB
   SET LEGACY_BANK_NO = NULL,
       IS_ACTIVE      = 0,
       NOTES          = TRIM(NVL(NOTES,'') || CHR(10) ||
                             TO_CHAR(SYSDATE, 'YYYY-MM-DD') ||
                             ' - Deprecated: replaced by DATA.BANKS-based rebuild'),
       UPDATE_DATE    = SYSDATE
 WHERE NOTES LIKE '%Migrated from DATA.BANK_BRANCH%'
    OR LEGACY_BANK_NO IS NOT NULL;  -- safety net

COMMIT;
PROMPT Old rows deactivated.

-- ============================================================
-- (3) إدراج 36 صف من DATA.BANKS كـ source of truth جديد
--     - LEGACY_BANK_NO = DATA.BANKS.NO  (الجسر مع EMPLOYEES.BANK و BD.BANK_NO)
--     - LEGACY_MASTER  = DATA.BANKS.MASTER_BANK
--     - PROVIDER_ID    = lookup عبر PAYMENT_PROVIDERS_TB.LEGACY_BANK_NO
--     - BRANCH_NAME, ADDRESS, TEL, FAX, CONTACTS = نسخ مباشر
-- ============================================================
PROMPT === Step 3: Insert 36 new branches from DATA.BANKS ===

DECLARE
  V_INSERTED NUMBER := 0;
  V_NO_PROV  NUMBER := 0;
BEGIN
  FOR R IN (
    SELECT DB.NO, DB.NAME, DB.MASTER_BANK, DB.MASTER_BANK_PRINT,
           DB.ADDRESS, DB.TEL1, DB.TEL2, DB.FAX, DB.CONTACTS,
           PP.PROVIDER_ID
      FROM DATA.BANKS DB
      LEFT JOIN GFC.PAYMENT_PROVIDERS_TB PP
        ON PP.LEGACY_BANK_NO = DB.MASTER_BANK
     ORDER BY DB.NO
  ) LOOP
    IF R.PROVIDER_ID IS NULL THEN
      V_NO_PROV := V_NO_PROV + 1;
      DBMS_OUTPUT.PUT_LINE('  Warning: No PROVIDER for DATA.BANKS.NO=' || R.NO ||
                           ' (MASTER_BANK=' || R.MASTER_BANK || ')');
    END IF;

    INSERT INTO GFC.PAYMENT_BANK_BRANCHES_TB (
        BRANCH_ID, PROVIDER_ID, BRANCH_NAME, PRINT_NO,
        ADDRESS, PHONE1, PHONE2, FAX, CONTACTS,
        LEGACY_BANK_NO, LEGACY_MASTER,
        IS_ACTIVE, STATUS, NOTES, ENTRY_DATE
    ) VALUES (
        GFC_PAK.PAYMENT_BANK_BRANCHES_SEQ.NEXTVAL,
        R.PROVIDER_ID,
        R.NAME,
        R.MASTER_BANK_PRINT,
        R.ADDRESS, R.TEL1, R.TEL2, R.FAX, R.CONTACTS,
        R.NO, R.MASTER_BANK,
        1, 1, 'Migrated from DATA.BANKS', SYSDATE
    );
    V_INSERTED := V_INSERTED + 1;
  END LOOP;

  COMMIT;
  DBMS_OUTPUT.PUT_LINE('Inserted: ' || V_INSERTED || ' rows. Without provider: ' || V_NO_PROV);
END;
/

-- ============================================================
-- (4) إعادة توجيه PAYMENT_ACCOUNTS_TB.BRANCH_ID → الفروع الجديدة
--     مطابقة بالـ:
--       (أ) PROVIDER_ID نفسه
--       (ب) أقرب BRANCH_NAME بعد التطبيع (إزالة "بنك", "البنك", "فرع", "الفرع", "-")
-- ============================================================
PROMPT === Step 4: Re-point PAYMENT_ACCOUNTS_TB.BRANCH_ID ===

DECLARE
  V_NEW_ID    NUMBER;
  V_UPDATED   NUMBER := 0;
  V_FAILED    NUMBER := 0;
  V_NORM_OLD  VARCHAR2(400);
  -- ملاحظة: لا نستخدم nested function في SQL (PLS-00231).
  -- نحسب التطبيع في متغير PL/SQL ونستخدم inline regex على عمود الجدول.
BEGIN
  FOR ACC IN (
    SELECT A.ACC_ID, A.BRANCH_ID AS OLD_BRANCH_ID, A.PROVIDER_ID,
           BR_OLD.BRANCH_NAME AS OLD_NAME
      FROM GFC.PAYMENT_ACCOUNTS_TB A
      JOIN GFC.PAYMENT_BANK_BRANCHES_TB BR_OLD ON BR_OLD.BRANCH_ID = A.BRANCH_ID
     WHERE A.BRANCH_ID IS NOT NULL
       AND A.STATUS = 1
       AND BR_OLD.IS_ACTIVE = 0  -- الصفوف القديمة المعطّلة
  ) LOOP
    -- تطبيع الاسم القديم في PL/SQL (dash → space أولاً، ثم باقي القواعد)
    V_NORM_OLD := TRIM(REGEXP_REPLACE(
                    REGEXP_REPLACE(
                      REGEXP_REPLACE(
                        REPLACE(ACC.OLD_NAME, '-', ' '),
                        '[إأآٱ]', 'ا'
                      ),
                      '(^|\s)(البنك|بنك|الفرع|فرع)\s+', ' '
                    ),
                    '\s+', ' '
                  ));

    BEGIN
      -- نبحث عن أقرب فرع جديد يطابق بالاسم (inline regex) + المزود
      SELECT BRANCH_ID INTO V_NEW_ID FROM (
        SELECT BR.BRANCH_ID
          FROM GFC.PAYMENT_BANK_BRANCHES_TB BR
         WHERE BR.IS_ACTIVE = 1
           AND BR.PROVIDER_ID = ACC.PROVIDER_ID
           AND TRIM(REGEXP_REPLACE(
                 REGEXP_REPLACE(
                   REGEXP_REPLACE(
                     REPLACE(BR.BRANCH_NAME, '-', ' '),
                     '[إأآٱ]', 'ا'
                   ),
                   '(^|\s)(البنك|بنك|الفرع|فرع)\s+', ' '
                 ),
                 '\s+', ' '
               )) = V_NORM_OLD
      ) WHERE ROWNUM = 1;

      UPDATE GFC.PAYMENT_ACCOUNTS_TB
         SET BRANCH_ID   = V_NEW_ID,
             UPDATE_USER = USER_PKG.GET_USER_ID,
             UPDATE_DATE = SYSDATE,
             NOTES       = TRIM(NVL(NOTES,'') || ' [BRANCH_ID re-pointed: ' ||
                                ACC.OLD_BRANCH_ID || ' -> ' || V_NEW_ID || ']')
       WHERE ACC_ID = ACC.ACC_ID;
      V_UPDATED := V_UPDATED + 1;

    EXCEPTION WHEN NO_DATA_FOUND THEN
      V_FAILED := V_FAILED + 1;
      DBMS_OUTPUT.PUT_LINE('  ACC_ID=' || ACC.ACC_ID ||
                           ' (PROVIDER_ID=' || ACC.PROVIDER_ID ||
                           ', old_branch="' || ACC.OLD_NAME || '") - no match found');
    END;
  END LOOP;

  COMMIT;
  DBMS_OUTPUT.PUT_LINE('Re-pointed: ' || V_UPDATED || ', Failed: ' || V_FAILED);
END;
/

-- ============================================================
-- (5) تقرير الحسابات اللي ما لقيناها مطابق (للمعالجة اليدوية)
-- ============================================================
PROMPT
PROMPT === Step 5: Accounts still pointing to deactivated branches ===

SELECT A.ACC_ID,
       A.EMP_NO,
       PP.PROVIDER_NAME,
       BR.BRANCH_NAME       AS OLD_BRANCH_NAME,
       A.BRANCH_ID          AS OLD_BRANCH_ID
  FROM GFC.PAYMENT_ACCOUNTS_TB A
  JOIN GFC.PAYMENT_BANK_BRANCHES_TB BR ON BR.BRANCH_ID = A.BRANCH_ID
  LEFT JOIN GFC.PAYMENT_PROVIDERS_TB PP ON PP.PROVIDER_ID = A.PROVIDER_ID
 WHERE A.STATUS = 1
   AND BR.IS_ACTIVE = 0
 ORDER BY A.PROVIDER_ID, A.EMP_NO;

-- ============================================================
-- (6) التحقق النهائي: BD.BANK_NO الآن يلاقي مطابق في GFC؟
-- ============================================================
PROMPT
PROMPT === Step 6: Final verification ===

SELECT
  COUNT(DISTINCT BD.BANK_NO)                                        AS DISTINCT_BANK_NOS_USED,
  COUNT(DISTINCT CASE WHEN EXISTS (
    SELECT 1 FROM GFC.PAYMENT_BANK_BRANCHES_TB BR
     WHERE BR.LEGACY_BANK_NO = BD.BANK_NO AND BR.IS_ACTIVE = 1
  ) THEN BD.BANK_NO END)                                            AS LINKED_TO_GFC,
  COUNT(DISTINCT CASE WHEN NOT EXISTS (
    SELECT 1 FROM GFC.PAYMENT_BANK_BRANCHES_TB BR
     WHERE BR.LEGACY_BANK_NO = BD.BANK_NO AND BR.IS_ACTIVE = 1
  ) THEN BD.BANK_NO END)                                            AS UNLINKED
FROM GFC.PAYMENT_BATCH_DETAIL_TB BD
WHERE BD.BANK_NO IS NOT NULL;

-- ============================================================
-- (7) إحصائيات نهائية للجدول
-- ============================================================
PROMPT
PROMPT === Step 7: Final stats ===

SELECT
  IS_ACTIVE,
  COUNT(*) AS COUNT
FROM GFC.PAYMENT_BANK_BRANCHES_TB
GROUP BY IS_ACTIVE
ORDER BY IS_ACTIVE DESC;

PROMPT
PROMPT ============================================================
PROMPT الخلاصة:
PROMPT - الصفوف الجديدة (IS_ACTIVE=1) = 36 (من DATA.BANKS)
PROMPT - الصفوف القديمة (IS_ACTIVE=0) = 28 (للسجل التاريخي)
PROMPT - PAYMENT_ACCOUNTS_TB.BRANCH_ID موجّه على الفروع الجديدة
PROMPT
PROMPT بعد التحقق من النتائج، نقدر نشيل الاعتماد على DATA من view + packages.
PROMPT ============================================================
