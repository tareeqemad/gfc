-- ============================================================
-- استكشاف بنية DATA.BANKS (الجدول الوحيد)
-- ============================================================

-- 1) الأعمدة الموجودة في DATA.BANKS
SELECT COLUMN_NAME, DATA_TYPE, DATA_LENGTH
  FROM ALL_TAB_COLUMNS
 WHERE OWNER = 'DATA' AND TABLE_NAME = 'BANKS'
 ORDER BY COLUMN_ID;


-- 2) هل 460 موجود في DATA.BANKS؟ مع 847 و 835 (للمقارنة)
SELECT NO, NAME FROM DATA.BANKS WHERE NO IN (460, 847, 835, 845, 9, 81);
-- توقع: 9 و 81 (masters) + 847، 835، 845 موجودين
-- 460 ربما مش موجود


-- 3) كل صفوف DATA.BANKS اللي اسمها يحتوي "فلسطين"
SELECT NO, NAME FROM DATA.BANKS
 WHERE NAME LIKE '%فلسطين%'
 ORDER BY NO;
-- ⚠️ مهم: هل في صفوف لـ فروع بنك فلسطين؟ (مش بس master)


-- 4) أرقام الـ BANK_NO اللي يستخدمها النظام القديم لموظفي بنك فلسطين (master=81)
-- (لو فيه 61 موظف في فرع دير البلح بـ legacy method)
SELECT DISTINCT BD.BANK_NO,
       DISBURSEMENT_PKG.GET_BANK_NAME(BD.BANK_NO) AS LEGACY_NAME,
       COUNT(*) AS EMP_COUNT
  FROM GFC.PAYMENT_BATCH_DETAIL_TB BD
 WHERE BD.BATCH_ID = 1
   AND BD.MASTER_BANK_NO = 81
   AND BD.ACC_ID IS NULL
 GROUP BY BD.BANK_NO, DISBURSEMENT_PKG.GET_BANK_NAME(BD.BANK_NO)
 ORDER BY EMP_COUNT DESC;
-- هذا الـ query يعرض الأرقام الفعلية المستخدمة للموظفين القدامى


-- 5) كل الجداول في schema DATA اللي ممكن تكون مرتبطة بالبنوك
SELECT TABLE_NAME FROM ALL_TABLES
 WHERE OWNER = 'DATA' AND (TABLE_NAME LIKE '%BANK%' OR TABLE_NAME LIKE '%BR%')
 ORDER BY TABLE_NAME;


-- 6) شو موجود في DATA.EMPLOYEES.BANK_NO لموظف 1309؟
SELECT NO, NAME, BANK_NO,
       DISBURSEMENT_PKG.GET_BANK_NAME(BANK_NO) AS BANK_NAME_FROM_HR
  FROM DATA.EMPLOYEES
 WHERE NO = 1309 AND ROWNUM <= 3;
