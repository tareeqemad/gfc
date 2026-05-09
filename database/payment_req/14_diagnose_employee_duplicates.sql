-- ============================================================
-- تشخيص: هل DATA.EMPLOYEES فيها duplicates؟
-- ============================================================
-- العَرَض: في شاشة تفاصيل الدفعة، الكروت تطلّع مبالغ أكبر من الجدول
-- السبب المحتمل: JOIN على EMPLOYEES بدون deduplication يدبّل SUM
-- ============================================================

-- 1) فحص: كم موظف عنده أكثر من record في EMPLOYEES؟
SELECT NO, COUNT(*) AS DUP_COUNT
  FROM DATA.EMPLOYEES
 GROUP BY NO
HAVING COUNT(*) > 1
 ORDER BY DUP_COUNT DESC, NO;

-- 2) فحص: لو فيه duplicates، شو الاختلافات بينهم؟
SELECT NO, NAME, ID, BANK, ACCOUNT_BANK_EMAIL, IBAN, MASTER_BANKS_EMAIL, IS_ACTIVE
  FROM DATA.EMPLOYEES
 WHERE NO IN (
    SELECT NO FROM DATA.EMPLOYEES GROUP BY NO HAVING COUNT(*) > 1
 )
 ORDER BY NO;

-- 3) فحص: الموظفون المدبّلون اللي عندهم batch detail مفتوحة (تأثير على الدفعات)
SELECT BD.BATCH_ID, BD.EMP_NO,
       EMP_PKG.GET_EMP_NAME(BD.EMP_NO) AS EMP_NAME,
       (SELECT COUNT(*) FROM DATA.EMPLOYEES E WHERE E.NO = BD.EMP_NO) AS EMP_DUP_COUNT,
       SUM(BD.TOTAL_AMOUNT) AS BD_SUM,
       COUNT(*) AS BD_ROWS
  FROM GFC.PAYMENT_BATCH_DETAIL_TB BD
 WHERE BD.EMP_NO IN (SELECT NO FROM DATA.EMPLOYEES GROUP BY NO HAVING COUNT(*) > 1)
 GROUP BY BD.BATCH_ID, BD.EMP_NO
 ORDER BY BD.BATCH_ID DESC, BD.EMP_NO;

-- 4) فحص: لو الكود القديم اشتغل وأدخل صفوف مدبّلة في BATCH_DETAIL_TB
-- (قبل الإصلاح) — يمكن نشوف duplicate rows لنفس (BATCH_ID, EMP_NO, ACC_ID)
SELECT BATCH_ID, EMP_NO, NVL(ACC_ID, -1) AS ACC_ID, COUNT(*) AS DUP_ROWS, SUM(TOTAL_AMOUNT) AS SUM_AMT
  FROM GFC.PAYMENT_BATCH_DETAIL_TB
 GROUP BY BATCH_ID, EMP_NO, NVL(ACC_ID, -1)
HAVING COUNT(*) > 1
 ORDER BY BATCH_ID DESC, EMP_NO;
