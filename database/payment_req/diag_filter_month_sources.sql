-- ============================================================
-- تشخيص: من أي جدول نجيب الموظفين عند فلترة شهر للنوع 3؟
-- ============================================================
-- الهدف: نعرف أي جدول فيه بيانات حقيقية حتى نقرر مع المحاسبة
-- ============================================================


-- ════════════ القسم 1: ما هو حجم البيانات في كل جدول؟ ════════════

-- 1.1) DATA.ADMIN — الجدول الرئيسي للرواتب (Legacy)
SELECT MONTH, COUNT(DISTINCT EMP_NO) AS EMP_COUNT
  FROM DATA.ADMIN
 WHERE MONTH BETWEEN 202501 AND 202612
 GROUP BY MONTH
 ORDER BY MONTH DESC;
-- ⭐ توقع: كل شهر يكون فيه ~800 موظف (الكل اللي اتحسب لهم الراتب)


-- 1.2) PAYMENT_REQ_ADMIN_CALC_TB — جدولنا (احتساب نوع 2 فقط)
SELECT MONTH, COUNT(DISTINCT EMP_NO) AS EMP_COUNT
  FROM GFC.PAYMENT_REQ_ADMIN_CALC_TB
 GROUP BY MONTH
 ORDER BY MONTH DESC;
-- ⭐ توقع: محدود — فقط الأشهر اللي شغّلنا فيها نوع 2


-- 1.3) DATA.SALARY (بند 323 = المستحقات)
SELECT MONTH, COUNT(DISTINCT EMP_NO) AS EMP_COUNT,
       SUM(VALUE)                   AS TOTAL_323
  FROM DATA.SALARY
 WHERE CON_NO = 323
   AND MONTH BETWEEN 202501 AND 202612
 GROUP BY MONTH
 ORDER BY MONTH DESC;
-- ⭐ توقع: الموظفين اللي عندهم مستحقات مرحّلة لذلك الشهر


-- ════════════ القسم 2: شو يطلع من كل جدول لشهر 202509؟ ════════════

-- 2.1) من DATA.ADMIN
SELECT COUNT(DISTINCT EMP_NO) AS EMP_FROM_ADMIN
  FROM DATA.ADMIN
 WHERE MONTH = 202509;

-- 2.2) من PAYMENT_REQ_ADMIN_CALC_TB
SELECT COUNT(DISTINCT EMP_NO) AS EMP_FROM_OUR_TBL
  FROM GFC.PAYMENT_REQ_ADMIN_CALC_TB
 WHERE MONTH = 202509;

-- 2.3) من DATA.SALARY بند 323
SELECT COUNT(DISTINCT EMP_NO) AS EMP_FROM_SALARY_323
  FROM DATA.SALARY
 WHERE CON_NO = 323 AND MONTH = 202509;


-- ════════════ القسم 3: التقاطع — مع الموظفين عندهم رصيد > 0 ════════════

-- المهم: الفلتر بيشتغل على موظفين عندهم رصيد مستحقات > 0
-- نشوف كم منهم في كل جدول لشهر 202509

WITH DUES_EMPS AS (
  SELECT NO FROM DATA.EMPLOYEES
   WHERE IS_ACTIVE = 1
     AND (DISBURSEMENT_PKG.GET_EMP_DUES_BASE(NO)
          + DISBURSEMENT_PKG.GET_EMP_DUES_ADD(NO)
          - DISBURSEMENT_PKG.GET_EMP_DUES_DED(NO)) > 0
)
SELECT
  (SELECT COUNT(*) FROM DUES_EMPS)                                                      AS TOTAL_DUES_EMPS,
  (SELECT COUNT(*) FROM DUES_EMPS D WHERE EXISTS (SELECT 1 FROM DATA.ADMIN A
        WHERE A.EMP_NO = D.NO AND A.MONTH = 202509))                                     AS WITH_ADMIN_FILTER,
  (SELECT COUNT(*) FROM DUES_EMPS D WHERE EXISTS (SELECT 1 FROM GFC.PAYMENT_REQ_ADMIN_CALC_TB C
        WHERE C.EMP_NO = D.NO AND C.MONTH = 202509))                                     AS WITH_OUR_TBL_FILTER,
  (SELECT COUNT(*) FROM DUES_EMPS D WHERE EXISTS (SELECT 1 FROM DATA.SALARY S
        WHERE S.EMP_NO = D.NO AND S.CON_NO = 323 AND S.MONTH = 202509))                  AS WITH_SALARY_323_FILTER
FROM DUAL;


-- ════════════ القسم 4: تأكد ان الـ procedure متعرّفة ════════════

-- آخر تحديث للـ package body
SELECT OBJECT_NAME, STATUS, LAST_DDL_TIME
  FROM ALL_OBJECTS
 WHERE OBJECT_NAME = 'DISBURSEMENT_PKG' AND OBJECT_TYPE = 'PACKAGE BODY';
-- ⭐ تأكد: STATUS = VALID + LAST_DDL_TIME قريبة (آخر دقائق)


-- ════════════ القسم 5: اختبر الـ procedure مباشرة ════════════

-- استبدل 202509 لو حابب تجرّب شهر تاني
DECLARE
  V_CUR SYS_REFCURSOR;
  V_MSG VARCHAR2(4000);
  V_EMP_NO     NUMBER;
  V_EMP_NAME   VARCHAR2(200);
  V_BRANCH     VARCHAR2(200);
  V_NET        NUMBER;
  V_323        NUMBER;
  V_CALC       NUMBER;
  V_HAS        NUMBER;
  V_FLAG       VARCHAR2(3);
  V_POSTED     NUMBER;
  V_AVAIL      NUMBER;
  V_CALC_FLAG  NUMBER;
  V_SKIP       VARCHAR2(500);
  V_COUNT      NUMBER := 0;
BEGIN
  GFC_PAK.DISBURSEMENT_PKG.PAYMENT_REQ_BULK_PREVIEW(
    P_THE_MONTH    => 202605,    -- الشهر الحالي (لا يهم للنوع 3)
    P_REQ_TYPE     => 3,          -- LUMP_SUM
    P_CALC_METHOD  => 2,          -- مبلغ ثابت
    P_PERCENT_VAL  => -2,
    P_REQ_AMOUNT   => 100,
    P_PAY_TYPE     => 8,
    P_SAL_FROM     => -5,
    P_SAL_TO       => -6,
    P_BRANCH_NO    => -7,
    P_L_VALUE      => -8,
    P_H_VALUE      => -9,
    P_REF_CUR_OUT  => V_CUR,
    P_MSG_OUT      => V_MSG,
    P_FILTER_MONTH => 202509       -- 🆕 شهر الفلتر
  );

  DBMS_OUTPUT.PUT_LINE('MSG: ' || V_MSG);

  LOOP
    FETCH V_CUR INTO V_EMP_NO, V_EMP_NAME, V_BRANCH, V_NET, V_323, V_CALC,
                     V_HAS, V_FLAG, V_POSTED, V_AVAIL, V_CALC_FLAG, V_SKIP;
    EXIT WHEN V_CUR%NOTFOUND;
    V_COUNT := V_COUNT + 1;
    IF V_COUNT <= 5 THEN
      DBMS_OUTPUT.PUT_LINE(V_EMP_NO || ' - ' || V_EMP_NAME || ' = ' || V_CALC);
    END IF;
  END LOOP;

  CLOSE V_CUR;
  DBMS_OUTPUT.PUT_LINE('TOTAL ROWS: ' || V_COUNT);
END;
/
