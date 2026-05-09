-- ============================================================
-- 🏷️ تحديث PRINT_NO على فروع البنوك (الكود الرسمي للبنك في كشوف التصدير)
-- ============================================================
-- بناءً على نموذج كشف البنك الإسلامي الفلسطيني، فروعه تستخدم أكواد:
--   831, 835, 837, 841, 845, 847, 852  (3 خانات)
-- وكشف الاستثمار يستخدم 0 لكل الموظفين.
-- بقية البنوك (Format A) تعتمد على IBAN ولا تحتاج كود فرع.
--
-- نضع الكود الرسمي في BR.PRINT_NO وبيظهر في عمود BRANCH_NO في كشف التصدير.
-- ============================================================

-- ===================================================
-- (1) البنك الإسلامي الفلسطيني (PROVIDER 106)
-- ===================================================
-- استنباط الأكواد من نموذج البنك:
--   831 → الفرع الرئيسي / فرع غزة (أعلى عدد موظفين)
--   835 → فرع النصيرات (لو موجود) — يُترك NULL لو ما عنا فرع
--   837 → فرع جباليا
--   841 → فرع رفح
--   845 → فرع النصر
--   847 → فرع دير البلح
--   852 → فرع خانيونس
-- ===================================================

-- الفرع الرئيسي / فرع غزة → 831
UPDATE GFC.PAYMENT_BANK_BRANCHES_TB
   SET PRINT_NO = 831, UPDATE_DATE = SYSDATE
 WHERE PROVIDER_ID = 106
   AND IS_ACTIVE = 1
   AND (BRANCH_NAME = 'البنك الاسلامي الفلسطيني'
     OR BRANCH_NAME LIKE '%فرع غزة%');

-- فرع النصر → 845
UPDATE GFC.PAYMENT_BANK_BRANCHES_TB
   SET PRINT_NO = 845, UPDATE_DATE = SYSDATE
 WHERE PROVIDER_ID = 106
   AND IS_ACTIVE = 1
   AND BRANCH_NAME LIKE '%فرع النصر%';

-- فرع خانيونس → 852
UPDATE GFC.PAYMENT_BANK_BRANCHES_TB
   SET PRINT_NO = 852, UPDATE_DATE = SYSDATE
 WHERE PROVIDER_ID = 106
   AND IS_ACTIVE = 1
   AND BRANCH_NAME LIKE '%فرع خانيونس%';

-- فرع دير البلح → 847
UPDATE GFC.PAYMENT_BANK_BRANCHES_TB
   SET PRINT_NO = 847, UPDATE_DATE = SYSDATE
 WHERE PROVIDER_ID = 106
   AND IS_ACTIVE = 1
   AND BRANCH_NAME LIKE '%فرع دير البلح%';

-- ===================================================
-- (2) بنك الاستثمار (PROVIDER 105) — كل الفروع PRINT_NO=0
-- ===================================================
UPDATE GFC.PAYMENT_BANK_BRANCHES_TB
   SET PRINT_NO = 0, UPDATE_DATE = SYSDATE
 WHERE PROVIDER_ID = 105 AND IS_ACTIVE = 1;

-- ===================================================
-- (3) بقية البنوك (Format A — IBAN) — PRINT_NO=NULL
-- ===================================================
-- لا حاجة لها (الـ IBAN يكفي)، نخليها NULL.
UPDATE GFC.PAYMENT_BANK_BRANCHES_TB
   SET PRINT_NO = NULL, UPDATE_DATE = SYSDATE
 WHERE PROVIDER_ID IN (108, 107, 100, 101, 103, 104, 109)
   AND IS_ACTIVE = 1;

COMMIT;

-- ===================================================
-- (4) تحقق
-- ===================================================
PROMPT
PROMPT === الفروع وأكواد BRANCH_NO الرسمية ===
PROMPT

SELECT PP.PROVIDER_NAME,
       BR.BRANCH_NAME,
       BR.PRINT_NO    AS BANK_BRANCH_CODE,
       BR.LEGACY_BANK_NO AS GFC_INTERNAL_NO
  FROM GFC.PAYMENT_BANK_BRANCHES_TB BR
  JOIN GFC.PAYMENT_PROVIDERS_TB PP ON PP.PROVIDER_ID = BR.PROVIDER_ID
 WHERE BR.IS_ACTIVE = 1
 ORDER BY PP.PROVIDER_ID, BR.BRANCH_NAME;
