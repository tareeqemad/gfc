-- ============================================================
-- Override columns على PAYMENT_REQ_DETAIL_TB
-- للسماح بتجاوز توزيع الموظف الافتراضي لطلب معيّن
-- ============================================================
-- مثال:
--   موظف 1234 في الـ PAYMENT_ACCOUNTS_TB له:
--     - 25% محفظة (PalPay)
--     - 75% بنك (بنك فلسطين)
--   لكن طلب نوع 5 (استحقاق إضافي 1000) لازم يروح 100% للمحفظة
--   → نضع OVERRIDE_PROVIDER_TYPE=2 على هذا السطر فقط
-- ============================================================

ALTER TABLE GFC.PAYMENT_REQ_DETAIL_TB ADD (
    OVERRIDE_PROVIDER_TYPE  NUMBER(1)         NULL,  -- NULL=افتراضي, 1=بنك فقط, 2=محفظة فقط
    OVERRIDE_ACC_ID         NUMBER            NULL   -- حساب محدد (يطغى على PROVIDER_TYPE)
);

COMMENT ON COLUMN GFC.PAYMENT_REQ_DETAIL_TB.OVERRIDE_PROVIDER_TYPE IS
  'تجاوز نوع المزود لهذا السطر: NULL=افتراضي (split عادي), 1=بنك فقط, 2=محفظة فقط';
COMMENT ON COLUMN GFC.PAYMENT_REQ_DETAIL_TB.OVERRIDE_ACC_ID IS
  'حساب محدد للصرف الكامل (لو موجود يطغى على OVERRIDE_PROVIDER_TYPE) — FK إلى PAYMENT_ACCOUNTS_TB.ACC_ID';

-- Index للبحث السريع لما نحسب الـ batch
CREATE INDEX GFC.IDX_PR_DETAIL_OVERRIDE
  ON GFC.PAYMENT_REQ_DETAIL_TB (OVERRIDE_PROVIDER_TYPE, OVERRIDE_ACC_ID);

-- ============================================================
-- مماثل على PAYMENT_BATCH_DETAIL_TB — لتتبّع الـ override بعد الاحتساب
-- (للسماح بإعادة توزيع موظف معيّن من شاشة batch_detail دون فقد المعلومة)
-- ============================================================

ALTER TABLE GFC.PAYMENT_BATCH_DETAIL_TB ADD (
    OVERRIDE_PROVIDER_TYPE  NUMBER(1)         NULL,
    OVERRIDE_ACC_ID         NUMBER            NULL
);

COMMENT ON COLUMN GFC.PAYMENT_BATCH_DETAIL_TB.OVERRIDE_PROVIDER_TYPE IS
  'مكرّر من PAYMENT_REQ_DETAIL_TB لتسهيل الـ filtering بعد الاحتساب';
COMMENT ON COLUMN GFC.PAYMENT_BATCH_DETAIL_TB.OVERRIDE_ACC_ID IS
  'مكرّر من PAYMENT_REQ_DETAIL_TB';

/
