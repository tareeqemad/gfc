-- ============================================================
-- FIX: إضافة MENU_FULL_CODE للصلاحيات الجديدة
-- ============================================================
-- الجدول SYSTEM_MENU_TB له عمودان:
--   MENU_CODE      = الجزء المدخل
--   MENU_FULL_CODE = الـ URL الكامل الذي يُستخدم في الروابط (href)
-- ============================================================

-- الخطوة 1: مزامنة MENU_FULL_CODE = MENU_CODE لكل الصلاحيات الجديدة
UPDATE GFC.SYSTEM_MENU_TB
   SET MENU_FULL_CODE = MENU_CODE
 WHERE MENU_CODE LIKE 'payment_accounts%'
   AND (MENU_FULL_CODE IS NULL OR MENU_FULL_CODE <> MENU_CODE);

COMMIT;


-- ============================================================
-- تحقق
-- ============================================================
SELECT MENU_NO, MENU_ADD, MENU_CODE, MENU_FULL_CODE, ID_SYSTEM
  FROM GFC.SYSTEM_MENU_TB
 WHERE MENU_CODE LIKE 'payment_accounts%'
    OR MENU_FULL_CODE LIKE 'payment_accounts%'
 ORDER BY SORT, MENU_NO;
