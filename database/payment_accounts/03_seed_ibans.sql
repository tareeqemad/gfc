-- ============================================================
-- Payment Accounts Module — IBAN Seeding for Company Accounts
-- ============================================================
-- كل بنك رئيسي له حساب شركة باسم "شركة كهرباء غزة"
-- هذا IBAN يُستخدم كمصدر التحويل في ملفات البنوك
--
-- ملاحظة: الـ Migration عبّأ COMPANY_IBAN من ACCOUNT_ID وهو خطأ.
-- هذا الملف يُصلّح ذلك ويضع الـ IBAN الصحيح للبنوك المعروفة.
-- ============================================================

-- ========== الخطوة 1: تنظيف القيم الخاطئة من Migration ==========
UPDATE GFC.PAYMENT_PROVIDERS_TB
   SET COMPANY_IBAN = NULL
 WHERE LENGTH(COMPANY_IBAN) < 20;      -- IBAN لازم 28 حرف، أي شيء أقل خطأ

COMMIT;


-- ========== الخطوة 2: ملء IBAN الصحيح لكل بنك ==========
-- ⚠ استبدل القيم التالية بـ IBAN الصحيح عندك لكل بنك
--   (الـ IBAN هو حساب الشركة في ذلك البنك)

-- بنك فلسطين (89)
UPDATE GFC.PAYMENT_PROVIDERS_TB
   SET COMPANY_IBAN = 'PS31PALS045105274280991604000',
       UPDATE_DATE = SYSDATE
 WHERE LEGACY_BANK_NO = 89;

-- بنك القدس (82)
UPDATE GFC.PAYMENT_PROVIDERS_TB
   SET COMPANY_IBAN = 'PS50ALDN060200634000420010000',
       UPDATE_DATE = SYSDATE
 WHERE LEGACY_BANK_NO = 82;

-- البنك الإسلامي العربي (30)
UPDATE GFC.PAYMENT_PROVIDERS_TB
   SET COMPANY_IBAN = 'PS??AIBK??????????????????????',  -- ⚠ ضع IBAN الصحيح
       UPDATE_DATE = SYSDATE
 WHERE LEGACY_BANK_NO = 30;

-- بنك الاستثمار الفلسطيني (76)
UPDATE GFC.PAYMENT_PROVIDERS_TB
   SET COMPANY_IBAN = 'PS??PINV??????????????????????',  -- ⚠ ضع IBAN الصحيح
       UPDATE_DATE = SYSDATE
 WHERE LEGACY_BANK_NO = 76;

-- البنك الإسلامي الفلسطيني (81)
UPDATE GFC.PAYMENT_PROVIDERS_TB
   SET COMPANY_IBAN = 'PS??PIBC??????????????????????',  -- ⚠ ضع IBAN الصحيح
       UPDATE_DATE = SYSDATE
 WHERE LEGACY_BANK_NO = 81;

-- بنك الأردن (35)
UPDATE GFC.PAYMENT_PROVIDERS_TB
   SET COMPANY_IBAN = 'PS??BJOR??????????????????????',  -- ⚠ ضع IBAN الصحيح
       UPDATE_DATE = SYSDATE
 WHERE LEGACY_BANK_NO = 35;

-- بنك القاهرة عمان (50)
UPDATE GFC.PAYMENT_PROVIDERS_TB
   SET COMPANY_IBAN = 'PS??CAAB??????????????????????',  -- ⚠ ضع IBAN الصحيح
       UPDATE_DATE = SYSDATE
 WHERE LEGACY_BANK_NO = 50;

-- البنك العربي (70)
UPDATE GFC.PAYMENT_PROVIDERS_TB
   SET COMPANY_IBAN = 'PS??ARAB??????????????????????',  -- ⚠ ضع IBAN الصحيح
       UPDATE_DATE = SYSDATE
 WHERE LEGACY_BANK_NO = 70;

-- البنك الوطني الإسلامي (4444)
UPDATE GFC.PAYMENT_PROVIDERS_TB
   SET COMPANY_IBAN = 'PS??NISB??????????????????????',  -- ⚠ ضع IBAN الصحيح
       UPDATE_DATE = SYSDATE
 WHERE LEGACY_BANK_NO = 4444;

COMMIT;


-- ========== المحافظ لا تحتاج IBAN ==========
-- PalPay (36) + Jawwal Pay — COMPANY_IBAN يبقى NULL


-- ========== الخطوة 3: تحقق ==========
SELECT PROVIDER_ID,
       PROVIDER_CODE,
       PROVIDER_NAME,
       CASE PROVIDER_TYPE WHEN 1 THEN 'بنك' WHEN 2 THEN 'محفظة' END AS TYPE_NAME,
       LEGACY_BANK_NO,
       COMPANY_IBAN,
       CASE
         WHEN PROVIDER_TYPE = 2 THEN '✓ محفظة — لا تحتاج IBAN'
         WHEN COMPANY_IBAN IS NULL THEN '❌ يحتاج IBAN'
         WHEN LENGTH(COMPANY_IBAN) <> 29 THEN '⚠ طول IBAN غير صحيح (' || LENGTH(COMPANY_IBAN) || ')'
         WHEN COMPANY_IBAN LIKE '%?%' THEN '❌ IBAN فيه علامات استفهام — لم يُملأ'
         ELSE '✅ OK'
       END AS STATUS
  FROM GFC.PAYMENT_PROVIDERS_TB
 ORDER BY PROVIDER_ID;
