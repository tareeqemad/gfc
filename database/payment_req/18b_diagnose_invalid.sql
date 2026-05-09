-- ============================================================
-- تشخيص الـ procedures الـ INVALID
-- ============================================================
-- شغّل هذا في PL/SQL Developer لمعرفة سبب فشل الـ compilation
-- ============================================================

-- 1. هل الـ sequence موجود؟
SELECT SEQUENCE_OWNER, SEQUENCE_NAME, LAST_NUMBER
  FROM ALL_SEQUENCES
 WHERE SEQUENCE_NAME = 'PAYMENT_REQ_IMP_LINE_SEQ';

-- 2. هل الجدول موجود وفيه الأعمدة المتوقعة؟
SELECT OWNER, TABLE_NAME
  FROM ALL_TABLES
 WHERE TABLE_NAME = 'PAYMENT_REQ_IMP_LINE_TB';

SELECT COLUMN_NAME, DATA_TYPE, NULLABLE
  FROM ALL_TAB_COLUMNS
 WHERE TABLE_NAME = 'PAYMENT_REQ_IMP_LINE_TB'
 ORDER BY COLUMN_ID;

-- 3. هل GFC_PAK عنده صلاحية INSERT على الجدول؟
SELECT GRANTEE, PRIVILEGE
  FROM ALL_TAB_PRIVS
 WHERE TABLE_NAME = 'PAYMENT_REQ_IMP_LINE_TB'
   AND GRANTEE = 'GFC_PAK';

-- 4. ⭐ السبب الدقيق للـ INVALID:
SELECT NAME, TYPE, LINE, POSITION, TEXT
  FROM ALL_ERRORS
 WHERE OWNER = 'GFC_PAK'
   AND NAME = 'PAYMENT_REQ_IMP_LINE_ADD'
 ORDER BY SEQUENCE;

-- 5. حاول إعادة compile يدوياً
ALTER PROCEDURE GFC_PAK.PAYMENT_REQ_IMP_LINE_ADD COMPILE;

-- 6. تحقق إن الكود الفعلي موجود
SELECT TEXT
  FROM ALL_SOURCE
 WHERE OWNER = 'GFC_PAK'
   AND NAME = 'PAYMENT_REQ_IMP_LINE_ADD'
 ORDER BY LINE;
