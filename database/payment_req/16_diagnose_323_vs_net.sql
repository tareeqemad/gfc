-- ============================================================
-- تشخيص: لماذا إجمالي بند 323 ≠ إجمالي NET_SALARY؟
-- (الفرق ~ 81,990.42 ر.س للشهر 09/2025)
-- ============================================================
-- 💡 غيّر V_M لرقم الشهر المطلوب (مثلاً 202509)

DEFINE V_M = 202509;

-- ============================================================
-- 1) المقارنة العامة بين المصدرين
-- ============================================================
SELECT 'COMPARISON' AS LABEL,
       (SELECT COUNT(*) FROM DATA.ADMIN WHERE MONTH = &V_M) AS ADMIN_EMP_CNT,
       (SELECT NVL(SUM(NET_SALARY), 0) FROM DATA.ADMIN WHERE MONTH = &V_M) AS ADMIN_NET_TOTAL,
       (SELECT COUNT(DISTINCT EMP_NO) FROM DATA.SALARY WHERE CON_NO = 323 AND MONTH = &V_M) AS SAL_323_EMP_CNT,
       (SELECT NVL(SUM(VALUE), 0) FROM DATA.SALARY WHERE CON_NO = 323 AND MONTH = &V_M) AS SAL_323_TOTAL,
       (SELECT NVL(SUM(VALUE), 0) FROM DATA.SALARY WHERE CON_NO = 323 AND MONTH = &V_M)
       - (SELECT NVL(SUM(NET_SALARY), 0) FROM DATA.ADMIN WHERE MONTH = &V_M) AS DIFF
  FROM DUAL;


-- ============================================================
-- 2) موظفون موجودون في 323 لكن مش في ADMIN (سبب محتمل للفرق)
-- ============================================================
SELECT 'IN 323 BUT NOT IN ADMIN' AS LABEL,
       S.EMP_NO,
       EMP_PKG.GET_EMP_NAME(S.EMP_NO) AS EMP_NAME,
       SUM(S.VALUE) AS S_323
  FROM DATA.SALARY S
 WHERE S.CON_NO = 323 AND S.MONTH = &V_M
   AND NOT EXISTS (SELECT 1 FROM DATA.ADMIN A WHERE A.EMP_NO = S.EMP_NO AND A.MONTH = &V_M)
 GROUP BY S.EMP_NO
 ORDER BY S_323 DESC;


-- ============================================================
-- 3) موظفون عندهم أكثر من سطر 323 (إضافات إضافية)
-- ============================================================
SELECT 'MULTIPLE 323 ENTRIES' AS LABEL,
       EMP_NO,
       EMP_PKG.GET_EMP_NAME(EMP_NO) AS EMP_NAME,
       COUNT(*) AS ENTRY_CNT,
       SUM(VALUE) AS TOTAL_323
  FROM DATA.SALARY
 WHERE CON_NO = 323 AND MONTH = &V_M
 GROUP BY EMP_NO
HAVING COUNT(*) > 1
 ORDER BY ENTRY_CNT DESC, TOTAL_323 DESC;


-- ============================================================
-- 4) موظفون عندهم 323 ≠ NET_SALARY (الفرق per-emp)
-- ============================================================
SELECT 'PER-EMP DIFF' AS LABEL,
       A.EMP_NO,
       EMP_PKG.GET_EMP_NAME(A.EMP_NO) AS EMP_NAME,
       A.NET_SALARY AS ADMIN_NET,
       NVL((SELECT SUM(S.VALUE) FROM DATA.SALARY S
             WHERE S.EMP_NO = A.EMP_NO AND S.CON_NO = 323 AND S.MONTH = &V_M), 0) AS S_323,
       NVL((SELECT SUM(S.VALUE) FROM DATA.SALARY S
             WHERE S.EMP_NO = A.EMP_NO AND S.CON_NO = 323 AND S.MONTH = &V_M), 0) - A.NET_SALARY AS DIFF
  FROM DATA.ADMIN A
 WHERE A.MONTH = &V_M
   AND ABS(NVL((SELECT SUM(S.VALUE) FROM DATA.SALARY S
                 WHERE S.EMP_NO = A.EMP_NO AND S.CON_NO = 323 AND S.MONTH = &V_M), 0) - A.NET_SALARY) > 0.01
 ORDER BY ABS(NVL((SELECT SUM(S.VALUE) FROM DATA.SALARY S
                    WHERE S.EMP_NO = A.EMP_NO AND S.CON_NO = 323 AND S.MONTH = &V_M), 0) - A.NET_SALARY) DESC;
-- ⚠️ هذي الموظفون يفسّرون الفرق 81,990.42

-- ============================================================
-- 5) أي بنود (constants) أخرى مرتبطة بـ 323 في DATA.SALARY؟
-- لربما هناك أكثر من سطر لنفس الموظف من جداول مختلفة
-- ============================================================
SELECT S.EMP_NO,
       S.CON_NO,
       C.NAME AS CON_NAME,
       S.VALUE,
       S.MONTH
  FROM DATA.SALARY S
  LEFT JOIN DATA.CONSTANT C ON C.NO = S.CON_NO
 WHERE S.MONTH = &V_M
   AND S.CON_NO = 323
   AND S.EMP_NO IN (
     SELECT EMP_NO FROM DATA.SALARY
      WHERE MONTH = &V_M AND CON_NO = 323
      GROUP BY EMP_NO HAVING COUNT(*) > 1
   )
 ORDER BY S.EMP_NO, S.VALUE DESC;
