-- ============================================================
-- تشخيص: ليش موظف 1309 ما يطلع في صفحة payment_accounts؟
-- (مع فلتر "فعّال" + "الشهر 202509")
-- ============================================================


-- ════════════ 1) تحقق إن الـ package اتكمبل ════════════
SELECT OBJECT_NAME, OBJECT_TYPE, STATUS, LAST_DDL_TIME
  FROM ALL_OBJECTS
 WHERE OBJECT_NAME = 'PAYMENT_ACCOUNTS_PKG'
 ORDER BY OBJECT_TYPE;
-- لازم STATUS = VALID للـ PACKAGE و PACKAGE BODY


-- ════════════ 2) محاكاة الـ filter يدوياً للـ emp 1309 ════════════
WITH ACC_AGG AS (
    SELECT EMP_NO,
           COUNT(*)                                       AS ACC_COUNT,
           SUM(CASE WHEN IS_ACTIVE = 1 THEN 1 ELSE 0 END) AS ACTIVE_COUNT,
           SUM(CASE WHEN IS_ACTIVE = 2 THEN 1 ELSE 0 END) AS HAS_DECEASED,
           SUM(CASE WHEN IS_ACTIVE = 4 THEN 1 ELSE 0 END) AS HAS_FROZEN
      FROM GFC.PAYMENT_ACCOUNTS_TB
     WHERE EMP_NO = 1309 AND STATUS = 1
     GROUP BY EMP_NO
)
SELECT E.NO,
       E.NAME,
       NVL(E.IS_ACTIVE, 0)         AS HR_CURRENT_ACTIVE,
       NVL(EM.IS_ACTIVE, 0)        AS EM_202509_ACTIVE,
       CASE WHEN EM.NO IS NULL THEN 'NO ROW IN EMPLOYEES_MONTH' ELSE 'HAS ROW' END AS EM_STATUS,
       NVL(AC.ACTIVE_COUNT, 0)     AS ACTIVE_ACCS,
       NVL(AC.HAS_DECEASED, 0)     AS HAS_DECEASED,
       NVL(AC.HAS_FROZEN, 0)       AS HAS_FROZEN,
       -- محاكاة الـ filter "فعّال" (P_IS_ACTIVE=1 + P_THE_MONTH=202509):
       CASE WHEN NVL(AC.HAS_DECEASED, 0) = 0
             AND NVL(AC.HAS_FROZEN, 0)   = 0
             AND NVL(EM.IS_ACTIVE, 0)    = 1
            THEN 'PASS — يجب أن يظهر' ELSE 'FAIL — مستثنى' END AS FILTER_RESULT
  FROM DATA.EMPLOYEES E
  LEFT JOIN DATA.EMPLOYEES_MONTH EM ON EM.NO = E.NO AND EM.THE_MONTH = 202509
  LEFT JOIN ACC_AGG AC               ON AC.EMP_NO = E.NO
 WHERE E.NO = 1309;


-- ════════════ 3) اختبر الـ procedure مباشرة ════════════
SET SERVEROUTPUT ON;
DECLARE
    V_CUR SYS_REFCURSOR;
    V_MSG VARCHAR2(4000);
    V_TOT NUMBER;
    V_NO  NUMBER; V_NAME VARCHAR2(200); V_ID VARCHAR2(50);
    V_IS_ACT NUMBER; V_BR VARCHAR2(200);
    V_AC_CNT NUMBER; V_AC_ACT NUMBER; V_BK_CNT NUMBER; V_WL_CNT NUMBER;
    V_BNF_CNT NUMBER; V_DEFLT VARCHAR2(200); V_LAST DATE;
    V_FOUND NUMBER := 0;
BEGIN
    PAYMENT_ACCOUNTS_PKG.EMP_LIST_FILTERED(
        P_EMP_NO       => 1309,
        P_BRANCH_NO    => NULL,
        P_IS_ACTIVE    => 1,        -- فعّال
        P_HAS_ACC      => NULL,
        P_HAS_BENEF    => NULL,
        P_THE_MONTH    => 202509,   -- ⭐ الشهر
        P_OFFSET       => 0,
        P_PAGE_SIZE    => 100,
        P_REF_CUR_OUT  => V_CUR,
        P_TOTAL_OUT    => V_TOT,
        P_MSG_OUT      => V_MSG
    );

    DBMS_OUTPUT.PUT_LINE('Total returned: ' || V_TOT);
    DBMS_OUTPUT.PUT_LINE('MSG: ' || V_MSG);

    LOOP
        FETCH V_CUR INTO V_NO, V_NAME, V_ID, V_IS_ACT, V_BR,
                         V_AC_CNT, V_AC_ACT, V_BK_CNT, V_WL_CNT,
                         V_BNF_CNT, V_DEFLT, V_LAST;
        EXIT WHEN V_CUR%NOTFOUND;
        V_FOUND := V_FOUND + 1;
        DBMS_OUTPUT.PUT_LINE(V_NO || ' - ' || V_NAME || ' | active=' || V_IS_ACT || ' | acc_active=' || V_AC_ACT);
    END LOOP;
    CLOSE V_CUR;
    DBMS_OUTPUT.PUT_LINE('Records fetched: ' || V_FOUND);
END;
/
