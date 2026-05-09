-- ============================================================
-- 🧹 سكربت تنظيف الحسابات المكررة في PAYMENT_ACCOUNTS_TB
-- ============================================================
-- ⚠ يُشغَّل مرّة واحدة فقط لتنظيف ما تكرّر سابقاً.
-- منع التكرار الجديد محلول مسبقاً في ACCOUNT_INSERT + bulk_import_preview.
--
-- ما يفعله السكربت:
--   1) يُصلح الصفر البادئ في WALLET_NUMBER و OWNER_PHONE (599... → 0599...).
--   2) ياخذ نسخ احتياطية بطابع زمني.
--   3) يعرض المكررات (للسجل).
--   4) لكل مجموعة (نفس EMP_NO + PROVIDER_ID + رقم بعد التطبيع):
--        أ) يختار "KEEPER" حسب: IS_DEFAULT → الأكثر استخداماً → الأقدم.
--        ب) ينقل splits + overrides من الخاسرين إلى الـ KEEPER.
--        ج) يُعطّل الخاسرين (STATUS=0, IS_ACTIVE=0) مع ملاحظة.
--   5) لا يُمسّ PAYMENT_BATCH_DETAIL_TB (سجل تاريخي للصرفات الفعلية).
--
-- التطبيع لمقارنة المكررات: UPPER + إزالة الفراغات/الشُرَط + إزالة الصفر البادئ
--   (هذا اللي ربط 0599528200 و 599528200 كنفس الرقم)
--
-- الحفظ النهائي للمحافظ: يحتفظ بصيغة "0XXXXXXXXX" (10 خانات تبدأ بـ 0).
-- ============================================================

SET SERVEROUTPUT ON SIZE UNLIMITED;
SET LINESIZE 200;
SET PAGESIZE 200;

-- ============================================================
-- 1) إصلاح أرقام المحافظ/الجوّالات: إضافة الصفر البادئ المفقود
--    (الحوافظ في فلسطين أرقام جوّال 10 خانات تبدأ بـ 0)
-- ============================================================
PROMPT === إصلاح الصفر البادئ في أرقام المحافظ ===

-- نسخة احتياطية قبل التعديل (اسم مختصر — حد Oracle 30 حرف)
DECLARE
  V_TS VARCHAR2(20) := TO_CHAR(SYSDATE, 'YYYYMMDD_HH24MI');
BEGIN
  EXECUTE IMMEDIATE 'CREATE TABLE GFC.ACC_PRE_FIX_BAK_' || V_TS ||
                    ' AS SELECT * FROM GFC.PAYMENT_ACCOUNTS_TB';
  DBMS_OUTPUT.PUT_LINE('Pre-fix backup: ACC_PRE_FIX_BAK_' || V_TS);
END;
/

-- (أ) WALLET_NUMBER لمزودي المحافظ: أضف 0 لو الرقم numeric ولا يبدأ بـ 0
UPDATE GFC.PAYMENT_ACCOUNTS_TB
   SET WALLET_NUMBER = '0' || TRIM(WALLET_NUMBER),
       UPDATE_USER   = USER_PKG.GET_USER_ID,
       UPDATE_DATE   = SYSDATE,
       NOTES         = TRIM(NVL(NOTES,'') || CHR(10) ||
                            TO_CHAR(SYSDATE, 'YYYY-MM-DD') ||
                            ' - auto-fix: leading zero added to wallet')
 WHERE STATUS = 1
   AND PROVIDER_ID IN (SELECT PROVIDER_ID FROM GFC.PAYMENT_PROVIDERS_TB WHERE PROVIDER_TYPE = 2)
   AND WALLET_NUMBER IS NOT NULL
   AND REGEXP_LIKE(TRIM(WALLET_NUMBER), '^[1-9][0-9]+$');

DBMS_OUTPUT.PUT_LINE('Wallet numbers fixed: ' || SQL%ROWCOUNT);

-- (ب) OWNER_PHONE: نفس المعالجة (رقم جوال صاحب الحساب)
UPDATE GFC.PAYMENT_ACCOUNTS_TB
   SET OWNER_PHONE = '0' || TRIM(OWNER_PHONE),
       UPDATE_USER = USER_PKG.GET_USER_ID,
       UPDATE_DATE = SYSDATE
 WHERE STATUS = 1
   AND OWNER_PHONE IS NOT NULL
   AND REGEXP_LIKE(TRIM(OWNER_PHONE), '^[1-9][0-9]+$');

DBMS_OUTPUT.PUT_LINE('Owner phones fixed: ' || SQL%ROWCOUNT);
COMMIT;

-- ============================================================
-- 2) جدول مؤقت يحمل DUP_KEY محسوب لكل حساب نشط (يبسّط الباقي)
-- ============================================================
PROMPT === تجهيز جدول مؤقت بالمفاتيح المُطبَّعة ===

-- نحذفه لو موجود من تشغيل سابق
BEGIN EXECUTE IMMEDIATE 'DROP TABLE GFC.TMP_ACC_DUPKEY PURGE'; EXCEPTION WHEN OTHERS THEN NULL; END;
/

CREATE TABLE GFC.TMP_ACC_DUPKEY AS
SELECT
  A.ACC_ID,
  A.EMP_NO,
  A.PROVIDER_ID,
  A.IS_DEFAULT,
  A.WALLET_NUMBER,
  A.ACCOUNT_NO,
  A.IBAN,
  CASE
    WHEN LTRIM(REGEXP_REPLACE(UPPER(TRIM(A.WALLET_NUMBER)), '[[:space:]\-]+', ''), '0') IS NOT NULL
     AND LTRIM(REGEXP_REPLACE(UPPER(TRIM(A.WALLET_NUMBER)), '[[:space:]\-]+', ''), '0') <> ''
    THEN 'W:' || LTRIM(REGEXP_REPLACE(UPPER(TRIM(A.WALLET_NUMBER)), '[[:space:]\-]+', ''), '0')
    WHEN LTRIM(REGEXP_REPLACE(UPPER(TRIM(A.ACCOUNT_NO)),    '[[:space:]\-]+', ''), '0') IS NOT NULL
     AND LTRIM(REGEXP_REPLACE(UPPER(TRIM(A.ACCOUNT_NO)),    '[[:space:]\-]+', ''), '0') <> ''
    THEN 'A:' || LTRIM(REGEXP_REPLACE(UPPER(TRIM(A.ACCOUNT_NO)),    '[[:space:]\-]+', ''), '0')
    WHEN REGEXP_REPLACE(UPPER(TRIM(A.IBAN)), '[[:space:]\-]+', '') IS NOT NULL
     AND REGEXP_REPLACE(UPPER(TRIM(A.IBAN)), '[[:space:]\-]+', '') <> ''
    THEN 'I:' || REGEXP_REPLACE(UPPER(TRIM(A.IBAN)), '[[:space:]\-]+', '')
  END AS DUP_KEY
FROM GFC.PAYMENT_ACCOUNTS_TB A
WHERE A.STATUS = 1;

CREATE INDEX GFC.IX_TMP_DUPKEY ON GFC.TMP_ACC_DUPKEY (EMP_NO, PROVIDER_ID, DUP_KEY);

-- ============================================================
-- 3) نسخة احتياطية إضافية للجداول المتأثرة بالدمج
-- ============================================================
DECLARE
  V_TS VARCHAR2(20) := TO_CHAR(SYSDATE, 'YYYYMMDD_HH24MI');
BEGIN
  EXECUTE IMMEDIATE 'CREATE TABLE GFC.PAY_ACC_BAK_'    || V_TS || ' AS SELECT * FROM GFC.PAYMENT_ACCOUNTS_TB';
  EXECUTE IMMEDIATE 'CREATE TABLE GFC.PAY_SPLIT_BAK_'  || V_TS || ' AS SELECT * FROM GFC.PAYMENT_REQ_DETAIL_SPLIT_TB';
  EXECUTE IMMEDIATE 'CREATE TABLE GFC.PAY_DTL_OVR_BAK_'|| V_TS ||
                    ' AS SELECT DETAIL_ID, OVERRIDE_PROVIDER_TYPE, OVERRIDE_ACC_ID FROM GFC.PAYMENT_REQ_DETAIL_TB';
  DBMS_OUTPUT.PUT_LINE('Pre-merge backup: PAY_ACC_BAK_' || V_TS);
END;
/

-- ============================================================
-- 4) عرض المكررات قبل التنظيف (للسجل)
-- ============================================================
PROMPT
PROMPT === المكررات الحالية ===
PROMPT

SELECT
  T.EMP_NO,
  EMP_PKG.GET_EMP_NAME(T.EMP_NO)  AS EMP_NAME,
  (SELECT P.PROVIDER_NAME FROM GFC.PAYMENT_PROVIDERS_TB P WHERE P.PROVIDER_ID = T.PROVIDER_ID) AS PROVIDER_NAME,
  COUNT(*)                        AS DUP_COUNT,
  LISTAGG(T.ACC_ID, ',') WITHIN GROUP (ORDER BY T.ACC_ID) AS ACC_IDS,
  LISTAGG(NVL(T.WALLET_NUMBER, NVL(T.ACCOUNT_NO, T.IBAN)), ' | ')
    WITHIN GROUP (ORDER BY T.ACC_ID) AS NUMBERS
FROM GFC.TMP_ACC_DUPKEY T
WHERE T.DUP_KEY IS NOT NULL
GROUP BY T.EMP_NO, T.PROVIDER_ID, T.DUP_KEY
HAVING COUNT(*) > 1
ORDER BY T.EMP_NO, T.PROVIDER_ID;

-- ============================================================
-- 5) تنفيذ التنظيف الفعلي
-- ============================================================
DECLARE
  V_KEEPER_ID  NUMBER;
  V_KEEP_CNT   NUMBER := 0;
  V_LOSE_CNT   NUMBER := 0;
  V_SPLIT_CNT  NUMBER := 0;
  V_OVR_CNT    NUMBER := 0;
BEGIN
  FOR G IN (
    SELECT EMP_NO, PROVIDER_ID, DUP_KEY
      FROM GFC.TMP_ACC_DUPKEY
     WHERE DUP_KEY IS NOT NULL
     GROUP BY EMP_NO, PROVIDER_ID, DUP_KEY
    HAVING COUNT(*) > 1
  ) LOOP
    -- اختيار الـ KEEPER
    BEGIN
      SELECT ACC_ID INTO V_KEEPER_ID FROM (
        SELECT T.ACC_ID,
               T.IS_DEFAULT,
               (SELECT COUNT(*) FROM GFC.PAYMENT_REQ_DETAIL_SPLIT_TB S WHERE S.ACC_ID = T.ACC_ID) AS USAGE_CNT
          FROM GFC.TMP_ACC_DUPKEY T
         WHERE T.EMP_NO      = G.EMP_NO
           AND T.PROVIDER_ID = G.PROVIDER_ID
           AND T.DUP_KEY     = G.DUP_KEY
         ORDER BY T.IS_DEFAULT DESC, USAGE_CNT DESC, T.ACC_ID ASC
      ) WHERE ROWNUM = 1;
    EXCEPTION WHEN NO_DATA_FOUND THEN CONTINUE;
    END;

    V_KEEP_CNT := V_KEEP_CNT + 1;
    DBMS_OUTPUT.PUT_LINE('EMP=' || G.EMP_NO || ' PROV=' || G.PROVIDER_ID ||
                         ' KEY=' || G.DUP_KEY || ' -> KEEPER=' || V_KEEPER_ID);

    -- معالجة الخاسرين
    FOR L IN (
      SELECT T.ACC_ID
        FROM GFC.TMP_ACC_DUPKEY T
       WHERE T.EMP_NO      = G.EMP_NO
         AND T.PROVIDER_ID = G.PROVIDER_ID
         AND T.DUP_KEY     = G.DUP_KEY
         AND T.ACC_ID     <> V_KEEPER_ID
    ) LOOP
      V_LOSE_CNT := V_LOSE_CNT + 1;

      -- (أ) splits: لو الـ KEEPER عنده split بنفس DETAIL_ID نحذف صف الخاسر (UK violation)
      DELETE FROM GFC.PAYMENT_REQ_DETAIL_SPLIT_TB
       WHERE ACC_ID = L.ACC_ID
         AND DETAIL_ID IN (
           SELECT DETAIL_ID FROM GFC.PAYMENT_REQ_DETAIL_SPLIT_TB WHERE ACC_ID = V_KEEPER_ID
         );
      V_SPLIT_CNT := V_SPLIT_CNT + SQL%ROWCOUNT;

      -- ثم نحوّل الباقي للـ KEEPER
      UPDATE GFC.PAYMENT_REQ_DETAIL_SPLIT_TB
         SET ACC_ID      = V_KEEPER_ID,
             UPDATE_USER = USER_PKG.GET_USER_ID,
             UPDATE_DATE = SYSDATE,
             NOTES       = TRIM(NVL(NOTES,'') || ' [merged from ACC_ID=' || L.ACC_ID || ']')
       WHERE ACC_ID = L.ACC_ID;
      V_SPLIT_CNT := V_SPLIT_CNT + SQL%ROWCOUNT;

      -- (ب) override في PAYMENT_REQ_DETAIL_TB
      UPDATE GFC.PAYMENT_REQ_DETAIL_TB
         SET OVERRIDE_ACC_ID = V_KEEPER_ID
       WHERE OVERRIDE_ACC_ID = L.ACC_ID;
      V_OVR_CNT := V_OVR_CNT + SQL%ROWCOUNT;

      -- (ج) تعطيل الخاسر
      UPDATE GFC.PAYMENT_ACCOUNTS_TB
         SET STATUS      = 0,
             IS_ACTIVE   = 0,
             IS_DEFAULT  = 0,
             TO_DATE     = SYSDATE,
             NOTES       = TRIM(NVL(NOTES,'') || CHR(10) ||
                                TO_CHAR(SYSDATE, 'YYYY-MM-DD') ||
                                ' - merge duplicate -> ACC_ID=' || V_KEEPER_ID),
             UPDATE_USER = USER_PKG.GET_USER_ID,
             UPDATE_DATE = SYSDATE
       WHERE ACC_ID = L.ACC_ID;
    END LOOP;
  END LOOP;

  COMMIT;

  DBMS_OUTPUT.PUT_LINE('==============================================');
  DBMS_OUTPUT.PUT_LINE('Cleanup completed:');
  DBMS_OUTPUT.PUT_LINE('  Groups merged   : ' || V_KEEP_CNT);
  DBMS_OUTPUT.PUT_LINE('  Accounts deact. : ' || V_LOSE_CNT);
  DBMS_OUTPUT.PUT_LINE('  Splits moved    : ' || V_SPLIT_CNT);
  DBMS_OUTPUT.PUT_LINE('  Overrides moved : ' || V_OVR_CNT);
  DBMS_OUTPUT.PUT_LINE('==============================================');
EXCEPTION WHEN OTHERS THEN
  ROLLBACK;
  DBMS_OUTPUT.PUT_LINE('ERROR: ' || SQLERRM);
  RAISE;
END;
/

-- ============================================================
-- 6) تحقّق نهائي + تنظيف الجدول المؤقت
-- ============================================================
PROMPT
PROMPT === التحقق بعد التنظيف (يجب أن يكون 0 صف) ===
PROMPT

-- نُعيد بناء الجدول المؤقت بعد التغييرات
TRUNCATE TABLE GFC.TMP_ACC_DUPKEY;

INSERT INTO GFC.TMP_ACC_DUPKEY (ACC_ID, EMP_NO, PROVIDER_ID, IS_DEFAULT, WALLET_NUMBER, ACCOUNT_NO, IBAN, DUP_KEY)
SELECT
  A.ACC_ID, A.EMP_NO, A.PROVIDER_ID, A.IS_DEFAULT, A.WALLET_NUMBER, A.ACCOUNT_NO, A.IBAN,
  CASE
    WHEN LTRIM(REGEXP_REPLACE(UPPER(TRIM(A.WALLET_NUMBER)), '[[:space:]\-]+', ''), '0') IS NOT NULL
     AND LTRIM(REGEXP_REPLACE(UPPER(TRIM(A.WALLET_NUMBER)), '[[:space:]\-]+', ''), '0') <> ''
    THEN 'W:' || LTRIM(REGEXP_REPLACE(UPPER(TRIM(A.WALLET_NUMBER)), '[[:space:]\-]+', ''), '0')
    WHEN LTRIM(REGEXP_REPLACE(UPPER(TRIM(A.ACCOUNT_NO)),    '[[:space:]\-]+', ''), '0') IS NOT NULL
     AND LTRIM(REGEXP_REPLACE(UPPER(TRIM(A.ACCOUNT_NO)),    '[[:space:]\-]+', ''), '0') <> ''
    THEN 'A:' || LTRIM(REGEXP_REPLACE(UPPER(TRIM(A.ACCOUNT_NO)),    '[[:space:]\-]+', ''), '0')
    WHEN REGEXP_REPLACE(UPPER(TRIM(A.IBAN)), '[[:space:]\-]+', '') IS NOT NULL
     AND REGEXP_REPLACE(UPPER(TRIM(A.IBAN)), '[[:space:]\-]+', '') <> ''
    THEN 'I:' || REGEXP_REPLACE(UPPER(TRIM(A.IBAN)), '[[:space:]\-]+', '')
  END
FROM GFC.PAYMENT_ACCOUNTS_TB A
WHERE A.STATUS = 1;
COMMIT;

SELECT EMP_NO, PROVIDER_ID, DUP_KEY, COUNT(*) AS REMAINING_DUPS
  FROM GFC.TMP_ACC_DUPKEY
 WHERE DUP_KEY IS NOT NULL
 GROUP BY EMP_NO, PROVIDER_ID, DUP_KEY
HAVING COUNT(*) > 1;

-- نظّف الجدول المؤقت
DROP TABLE GFC.TMP_ACC_DUPKEY PURGE;

PROMPT
PROMPT === انتهى السكربت — لو الجدول أعلاه فاضي، ما في مكررات نشطة. ===
