-- ============================================================
-- إضافة طريقة الصرف للـ batch + تفاصيله
-- DISBURSE_METHOD: 1 = طريقة قديمة (DATA.EMPLOYEES.BANK)
--                  2 = طريقة جديدة (PAYMENT_ACCOUNTS_TB + split)
-- ============================================================

-- 1) عمود DISBURSE_METHOD على ماستر الـ batch
ALTER TABLE GFC.PAYMENT_BATCH_TB
  ADD (DISBURSE_METHOD NUMBER(1) DEFAULT 1);

COMMENT ON COLUMN GFC.PAYMENT_BATCH_TB.DISBURSE_METHOD IS
  'طريقة الصرف: 1=قديمة (DATA.EMPLOYEES)، 2=جديدة (PAYMENT_ACCOUNTS + split)';

-- جعل القيم الموجودة (NULL) = 1 (قديم) صراحة
UPDATE GFC.PAYMENT_BATCH_TB SET DISBURSE_METHOD = 1 WHERE DISBURSE_METHOD IS NULL;
COMMIT;

-- 2) عمود ACC_ID على تفاصيل الـ batch — يُستخدم في الطريقة الجديدة فقط
ALTER TABLE GFC.PAYMENT_BATCH_DETAIL_TB
  ADD (ACC_ID NUMBER NULL);

COMMENT ON COLUMN GFC.PAYMENT_BATCH_DETAIL_TB.ACC_ID IS
  'FK إلى PAYMENT_ACCOUNTS_TB.ACC_ID — يُملأ في الطريقة الجديدة (DISBURSE_METHOD=2)';

-- FK معطّل افتراضياً (لتجنب مشاكل الحذف) — استخدم index بدل
CREATE INDEX GFC.IX_PBD_ACC_ID ON GFC.PAYMENT_BATCH_DETAIL_TB (ACC_ID);

-- ============================================================
-- للتحقق:
-- ============================================================
-- SELECT BATCH_ID, BATCH_NO, DISBURSE_METHOD, EMP_COUNT FROM GFC.PAYMENT_BATCH_TB ORDER BY BATCH_ID DESC;
-- SELECT COLUMN_NAME, DATA_TYPE, NULLABLE FROM ALL_TAB_COLUMNS WHERE TABLE_NAME='PAYMENT_BATCH_DETAIL_TB' AND OWNER='GFC';
