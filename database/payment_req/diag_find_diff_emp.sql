-- ============================================================
-- تشخيص: لماذا 798 vs 797؟ من هو الموظف في الفرق؟
-- ============================================================
-- الفرضية: في موظف محتسب لـ 202509 لكن إما:
--   • متقاعد (DATA.EMPLOYEES.IS_ACTIVE = 0)
--   • مش عنده حساب نشط (كل حساباته IS_ACTIVE != 1)
--   • متوفى أو حساب مغلق (PAYMENT_ACCOUNTS_TB.IS_ACTIVE in (2, 4))
-- ============================================================


-- ════════════ Query 1: الموظف الناقص من صفحة الحسابات ════════════
-- (موجود في calc table لـ 202509 + ما ينطبق عليه فلتر "فعّال + عنده حساب نشط")

SELECT C.EMP_NO,
       EMP_PKG.GET_EMP_NAME(C.EMP_NO) AS EMP_NAME,
       EMP_PKG.GET_EMP_BRANCH_NAME(C.EMP_NO) AS BRANCH_NAME,
       NVL(C.NET_SALARY, 0)    AS NET_SALARY,
       NVL(C.CAPPED_VAL, 0)    AS CAPPED_VAL,
       NVL(C.ACCRUED_323, 0)   AS ACCRUED_323,
       -- حالة الموظف من DATA.EMPLOYEES
       (SELECT IS_ACTIVE FROM DATA.EMPLOYEES WHERE NO = C.EMP_NO AND ROWNUM = 1) AS HR_IS_ACTIVE,
       -- عدد الحسابات بكل حالة
       (SELECT COUNT(*) FROM GFC.PAYMENT_ACCOUNTS_TB WHERE EMP_NO = C.EMP_NO)                                        AS TOTAL_ACCS,
       (SELECT COUNT(*) FROM GFC.PAYMENT_ACCOUNTS_TB WHERE EMP_NO = C.EMP_NO AND IS_ACTIVE = 1 AND STATUS = 1)       AS ACTIVE_ACCS,
       (SELECT COUNT(*) FROM GFC.PAYMENT_ACCOUNTS_TB WHERE EMP_NO = C.EMP_NO AND IS_ACTIVE = 0)                       AS RETIRED_ACCS,
       (SELECT COUNT(*) FROM GFC.PAYMENT_ACCOUNTS_TB WHERE EMP_NO = C.EMP_NO AND IS_ACTIVE = 2)                       AS DECEASED_ACCS,
       (SELECT COUNT(*) FROM GFC.PAYMENT_ACCOUNTS_TB WHERE EMP_NO = C.EMP_NO AND IS_ACTIVE = 4)                       AS CLOSED_ACCS,
       -- الحالة المركّبة
       DISBURSEMENT_PKG.GET_EMP_DISPLAY_STATUS(C.EMP_NO) AS DISPLAY_STATUS_CODE,
       CASE DISBURSEMENT_PKG.GET_EMP_DISPLAY_STATUS(C.EMP_NO)
         WHEN 1 THEN 'فعّال'
         WHEN 0 THEN 'متقاعد'
         WHEN 2 THEN 'متوفى'
         WHEN 4 THEN 'حساب مغلق'
         ELSE '—'
       END AS DISPLAY_STATUS_NAME
  FROM GFC.PAYMENT_REQ_ADMIN_CALC_TB C
 WHERE C.MONTH = 202509
   AND C.EMP_NO NOT IN (
     -- المستثنى: من ينطبق عليه فلتر صفحة الحسابات
     SELECT DISTINCT E.NO FROM DATA.EMPLOYEES E
      WHERE E.IS_ACTIVE = 1   -- فعّال
        AND EXISTS (SELECT 1 FROM GFC.PAYMENT_ACCOUNTS_TB PA
                     WHERE PA.EMP_NO = E.NO
                       AND PA.IS_ACTIVE = 1
                       AND PA.STATUS = 1)
   )
 ORDER BY C.EMP_NO;


-- ════════════ Query 2: تفصيل حسابات الموظف الذي ظهر فوق ════════════
-- استبدل 999 برقم الموظف اللي طلع من Query 1

/*
SELECT PA.ACC_ID, PA.PROVIDER_ID, PP.PROVIDER_NAME,
       PA.ACCOUNT_NO, PA.WALLET_NUMBER, PA.IBAN,
       PA.IS_ACTIVE,
       CASE PA.IS_ACTIVE
         WHEN 1 THEN 'فعّال'
         WHEN 0 THEN 'متقاعد'
         WHEN 2 THEN 'متوفى'
         WHEN 4 THEN 'حساب مغلق'
         ELSE 'غير معروف'
       END AS IS_ACTIVE_NAME,
       PA.STATUS, PA.IS_DEFAULT, PA.SPLIT_TYPE, PA.SPLIT_VALUE,
       PA.OWNER_NAME, PA.BENEFICIARY_ID,
       PA.INACTIVE_REASON
  FROM GFC.PAYMENT_ACCOUNTS_TB PA
  LEFT JOIN GFC.PAYMENT_PROVIDERS_TB PP ON PP.PROVIDER_ID = PA.PROVIDER_ID
 WHERE PA.EMP_NO = 999     -- ← استبدل برقم الموظف
 ORDER BY PA.IS_ACTIVE DESC, PA.SPLIT_ORDER;
*/


-- ════════════ Query 3: ملخص تحقّقي — كم موظف في كل scenario ════════════

WITH CALC_EMPS AS (
    SELECT DISTINCT EMP_NO FROM GFC.PAYMENT_REQ_ADMIN_CALC_TB WHERE MONTH = 202509
)
SELECT
    -- العدد الإجمالي في calc
    (SELECT COUNT(*) FROM CALC_EMPS) AS TOTAL_CALC,
    -- العدد الذي ينطبق عليه فلتر صفحة الحسابات
    (SELECT COUNT(*) FROM CALC_EMPS C
      WHERE C.EMP_NO IN (
        SELECT DISTINCT E.NO FROM DATA.EMPLOYEES E
         WHERE E.IS_ACTIVE = 1
           AND EXISTS (SELECT 1 FROM GFC.PAYMENT_ACCOUNTS_TB PA
                        WHERE PA.EMP_NO = E.NO AND PA.IS_ACTIVE = 1 AND PA.STATUS = 1)
      )) AS MATCHES_FILTER,
    -- متقاعد (IS_ACTIVE = 0 في DATA.EMPLOYEES)
    (SELECT COUNT(*) FROM CALC_EMPS C
      WHERE EXISTS (SELECT 1 FROM DATA.EMPLOYEES E WHERE E.NO = C.EMP_NO AND NVL(E.IS_ACTIVE, 0) = 0)) AS HR_RETIRED,
    -- ما عنده حساب نشط
    (SELECT COUNT(*) FROM CALC_EMPS C
      WHERE NOT EXISTS (SELECT 1 FROM GFC.PAYMENT_ACCOUNTS_TB PA
                         WHERE PA.EMP_NO = C.EMP_NO AND PA.IS_ACTIVE = 1 AND PA.STATUS = 1)) AS NO_ACTIVE_ACC,
    -- له حساب متوفى
    (SELECT COUNT(*) FROM CALC_EMPS C
      WHERE EXISTS (SELECT 1 FROM GFC.PAYMENT_ACCOUNTS_TB PA WHERE PA.EMP_NO = C.EMP_NO AND PA.IS_ACTIVE = 2)) AS HAS_DECEASED,
    -- له حساب مغلق
    (SELECT COUNT(*) FROM CALC_EMPS C
      WHERE EXISTS (SELECT 1 FROM GFC.PAYMENT_ACCOUNTS_TB PA WHERE PA.EMP_NO = C.EMP_NO AND PA.IS_ACTIVE = 4)) AS HAS_CLOSED
FROM DUAL;
