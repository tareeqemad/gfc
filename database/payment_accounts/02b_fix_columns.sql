-- ============================================================
-- Fix Script — إضافة أعمدة حساب الشركة + تنظيف COMPANY_IBAN
-- ============================================================
-- يُشغَّل مرة واحدة فقط على سيرفر سبق وشغّل 01_ddl.sql + 02_migration.sql
-- (قبل إضافة COMPANY_ACCOUNT_NO و COMPANY_ACCOUNT_ID)
-- ============================================================

-- الخطوة 1: إضافة العمودين الجديدين
ALTER TABLE GFC.PAYMENT_PROVIDERS_TB
  ADD (COMPANY_ACCOUNT_NO VARCHAR2(50),
       COMPANY_ACCOUNT_ID VARCHAR2(50));


-- الخطوة 2: نقل القيمة الخاطئة من COMPANY_IBAN إلى COMPANY_ACCOUNT_ID
-- (لأن Migration عبّأ ACCOUNT_ID في الـ IBAN خطأً)
UPDATE GFC.PAYMENT_PROVIDERS_TB
   SET COMPANY_ACCOUNT_ID = COMPANY_IBAN,
       COMPANY_IBAN        = NULL,
       UPDATE_DATE         = SYSDATE
 WHERE COMPANY_IBAN IS NOT NULL
   AND LENGTH(COMPANY_IBAN) < 20;     -- أي قيمة أقل من 20 حرف ليست IBAN


-- الخطوة 3: تعبئة COMPANY_ACCOUNT_NO من DATA.MASTER_BANKS_EMAIL
UPDATE GFC.PAYMENT_PROVIDERS_TB P
   SET P.COMPANY_ACCOUNT_NO = (
     SELECT M.ACCOUNT_NO
       FROM DATA.MASTER_BANKS_EMAIL M
      WHERE M.B_NO = P.LEGACY_BANK_NO
   ),
   P.UPDATE_DATE = SYSDATE
 WHERE P.LEGACY_BANK_NO IS NOT NULL
   AND P.COMPANY_ACCOUNT_NO IS NULL;

COMMIT;


-- ============================================================
-- تحقق
-- ============================================================
SELECT PROVIDER_ID,
       PROVIDER_NAME,
       LEGACY_BANK_NO               AS B_NO,
       COMPANY_ACCOUNT_NO           AS "رقم الحساب",
       COMPANY_ACCOUNT_ID           AS "رقم مرجعي",
       COMPANY_IBAN                 AS "IBAN (يحتاج تعبئة)"
  FROM GFC.PAYMENT_PROVIDERS_TB
 ORDER BY PROVIDER_ID;
