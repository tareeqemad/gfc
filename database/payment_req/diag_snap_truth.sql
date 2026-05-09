-- ============================================================
-- نظرية: SNAP_BRANCH_NAME هو اللي يحرك العرض للـ 577 موظف
-- ولـ موظف 1309 ربما NULL أو خاطئ
-- ============================================================

-- 1) شو SNAP_BRANCH_NAME لموظف 1309 بالظبط؟
SELECT EMP_NO, ACC_ID, SNAP_PROVIDER_NAME, SNAP_BRANCH_NAME,
       LENGTH(SNAP_BRANCH_NAME) AS LEN
  FROM GFC.PAYMENT_BATCH_DETAIL_TB
 WHERE BATCH_ID = 1 AND EMP_NO = 1309;
-- لو LEN = 0 أو NULL → هاي المشكلة


-- 2) كم موظف SNAP_BRANCH_NAME تبعهم NULL/فاضي في الدفعة؟
SELECT COUNT(*) AS NULL_SNAP,
       COUNT(SNAP_BRANCH_NAME) AS NOT_NULL_SNAP
  FROM GFC.PAYMENT_BATCH_DETAIL_TB
 WHERE BATCH_ID = 1;


-- 3) عينة من SNAP_BRANCH_NAME لموظفين عاديين في بنك فلسطين
SELECT EMP_NO, SNAP_PROVIDER_NAME, SNAP_BRANCH_NAME, BANK_NO, ACC_ID
  FROM GFC.PAYMENT_BATCH_DETAIL_TB
 WHERE BATCH_ID = 1 AND SNAP_PROVIDER_NAME LIKE '%فلسطين%'
   AND ROWNUM <= 10;
-- نشوف شو شكل SNAP_BRANCH_NAME للحالات الصحيحة


-- 4) الحقيقة الكبيرة: شو يطلع من الـ view لموظف 1309 الآن؟
SELECT EMP_NO, MASTER_BANK_NAME, BANK_BRANCH_NAME, MASTER_BANK_NO
  FROM GFC.PAYMENT_BATCH_BANK_VW
 WHERE BATCH_ID = 1 AND EMP_NO = 1309;


-- 5) عدد الموظفين بكل فرع بنك فلسطين حسب SNAP_BRANCH_NAME (مش BR.BRANCH_NAME)
SELECT SNAP_BRANCH_NAME, COUNT(*) AS EMP_COUNT
  FROM GFC.PAYMENT_BATCH_DETAIL_TB
 WHERE BATCH_ID = 1 AND SNAP_PROVIDER_NAME LIKE '%فلسطين%'
 GROUP BY SNAP_BRANCH_NAME
 ORDER BY EMP_COUNT DESC;
-- هاي الـ query تكشف توزيع الفروع الفعلي
