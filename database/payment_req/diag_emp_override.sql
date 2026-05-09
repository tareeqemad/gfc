-- ============================================================
-- تشخيص شامل لتتبّع override جيهان (1047) عبر النظام
-- ============================================================

-- 🔍 1) آخر طلبات تخص جيهان (مع override)
SELECT D.REQ_ID,
       D.DETAIL_ID,
       M.REQ_NO,
       M.STATUS         AS REQ_STATUS,
       D.DETAIL_STATUS,
       D.REQ_AMOUNT,
       D.OVERRIDE_PROVIDER_TYPE,
       D.OVERRIDE_ACC_ID,
       M.THE_MONTH,
       M.REQ_TYPE
  FROM GFC.PAYMENT_REQ_DETAIL_TB D
  JOIN GFC.PAYMENT_REQ_TB M ON M.REQ_ID = D.REQ_ID
 WHERE D.EMP_NO = 1047
 ORDER BY D.REQ_ID DESC
 FETCH FIRST 10 ROWS ONLY;

-- 🔍 2) Batches الموجودة (آخر 5)
SELECT BATCH_ID, BATCH_NO, BATCH_DATE, STATUS, TOTAL_AMOUNT, EMP_COUNT
  FROM GFC.PAYMENT_BATCH_TB
 ORDER BY BATCH_ID DESC
 FETCH FIRST 5 ROWS ONLY;

-- 🔍 3) هل جيهان في أي batch؟
SELECT BD.BATCH_ID,
       B.BATCH_NO,
       B.STATUS         AS BATCH_STATUS,
       BD.EMP_NO,
       BD.TOTAL_AMOUNT,
       BD.OVERRIDE_PROVIDER_TYPE,
       BD.OVERRIDE_ACC_ID,
       BD.ACC_ID,
       BD.SNAP_PROVIDER_NAME,
       BD.SNAP_ACCOUNT_NO
  FROM GFC.PAYMENT_BATCH_DETAIL_TB BD
  JOIN GFC.PAYMENT_BATCH_TB B ON B.BATCH_ID = BD.BATCH_ID
 WHERE BD.EMP_NO = 1047
 ORDER BY BD.BATCH_ID DESC, BD.OVERRIDE_PROVIDER_TYPE NULLS LAST;

-- 🔍 4) ربط batch ⇄ details (يكشف لو الـ details مربوطة بالـ batch فعلاً)
SELECT BL.BATCH_ID,
       BL.DETAIL_ID,
       D.EMP_NO,
       D.REQ_AMOUNT,
       D.OVERRIDE_PROVIDER_TYPE,
       D.OVERRIDE_ACC_ID
  FROM GFC.PAYMENT_BATCH_LINK_TB BL
  JOIN GFC.PAYMENT_REQ_DETAIL_TB D ON D.DETAIL_ID = BL.DETAIL_ID
 WHERE D.EMP_NO = 1047
 ORDER BY BL.BATCH_ID DESC;
