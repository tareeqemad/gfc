-- ============================================================
-- فحص حالة الكائنات قبل/بعد إعادة التشغيل
-- ============================================================
-- الترتيب الصحيح للتنفيذ:
--   1. شغّل هذا الـ check (سيظهر شو موجود وشو ناقص)
--   2. لو السيكوينس/الجدول مفقود: شغّل 17_import_line_tb.sql مرة ثانية
--   3. شغّل 18_import_line_pkg.sql
--   4. أعد كمبايل 03_pkg_body.sql
-- ============================================================

-- 1. فحص الجدول والـ sequence
SELECT OWNER, OBJECT_NAME, OBJECT_TYPE, STATUS
  FROM ALL_OBJECTS
 WHERE OBJECT_NAME IN (
   'PAYMENT_REQ_IMP_LINE_TB',
   'PAYMENT_REQ_IMP_LINE_SEQ',
   'PAYMENT_REQ_IMP_LINE_ADD',
   'PAYMENT_REQ_IMP_LINES_GET',
   'PAYMENT_REQ_IMP_LINES_COUNT'
 )
 ORDER BY OBJECT_TYPE, OBJECT_NAME;

-- 2. الأخطاء التفصيلية للـ procedures الـ INVALID
SELECT NAME, TYPE, LINE, POSITION, TEXT
  FROM ALL_ERRORS
 WHERE OWNER = 'GFC_PAK'
   AND NAME IN (
     'PAYMENT_REQ_IMP_LINE_ADD',
     'PAYMENT_REQ_IMP_LINES_GET',
     'PAYMENT_REQ_IMP_LINES_COUNT'
   )
 ORDER BY NAME, SEQUENCE;

-- 3. صلاحيات GFC_PAK على الجدول
SELECT GRANTEE, OWNER, TABLE_NAME, PRIVILEGE
  FROM ALL_TAB_PRIVS
 WHERE TABLE_NAME = 'PAYMENT_REQ_IMP_LINE_TB';
