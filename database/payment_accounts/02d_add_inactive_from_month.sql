-- ============================================================
-- Migration: إضافة عمود INACTIVE_FROM_MONTH لـ PAYMENT_ACCOUNTS_TB
-- ============================================================
-- الهدف: تتبع الشهر (YYYYMM) الذي تم فيه إيقاف الحساب —
--        خاصة لحسابات الوفاة/التجميد، عشان نقدر نحدد:
--        • متى توقف الموظف عن استلام راتبه فعلياً
--        • هل كان الموظف فعالاً في شهر صرف معين أم لا
--        • تنبيه المحاسب لو حاول صرف لشهر بعد شهر الوفاة
-- ============================================================
-- ⚠️ يُشغَّل مرة واحدة فقط على سيرفر سبق وشغّل 01_ddl.sql
-- ============================================================

-- الخطوة 1: إضافة العمود الجديد
ALTER TABLE GFC.PAYMENT_ACCOUNTS_TB
  ADD (INACTIVE_FROM_MONTH NUMBER(6));   -- YYYYMM (مثل 202509)

COMMENT ON COLUMN GFC.PAYMENT_ACCOUNTS_TB.INACTIVE_FROM_MONTH IS
  'الشهر (YYYYMM) الذي توقف فيه هذا الحساب — للوفاة/التجميد/التقاعد. NULL = الحساب نشط أو لا يهم الشهر';

-- الخطوة 2: backfill — للحسابات الموقوفة حالياً، نضع شهر TO_DATE
-- (تقريبي — يساعد للبيانات التاريخية)
UPDATE GFC.PAYMENT_ACCOUNTS_TB
   SET INACTIVE_FROM_MONTH = EXTRACT(YEAR FROM TO_DATE) * 100 + EXTRACT(MONTH FROM TO_DATE)
 WHERE IS_ACTIVE = 0
   AND TO_DATE IS NOT NULL
   AND INACTIVE_FROM_MONTH IS NULL;

COMMIT;

-- الخطوة 3: index للاستعلامات السريعة
CREATE INDEX GFC.IDX_PAY_ACC_INACTIVE_MONTH
   ON GFC.PAYMENT_ACCOUNTS_TB (INACTIVE_FROM_MONTH)
   WHERE INACTIVE_FROM_MONTH IS NOT NULL;

-- ============================================================
-- ⚠️ إشعار: بعد تشغيل هذا، شغّل الباقي بالترتيب:
--   1) 03_pkg_spec.sql — لتحديث specs (لو فيه تغيير)
--   2) 04_pkg_body.sql — للنسخة الجديدة من ACCOUNT_DEACTIVATE
-- ============================================================
