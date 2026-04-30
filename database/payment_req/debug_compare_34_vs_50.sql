-- ================================================================
-- مقارنة الاحتساب: 34% vs 50%
-- يعرض actual (من PAYMENT_REQ_ADMIN_CALC_TB) مقابل expected (الفورمولا اليدوية)
-- شغّله في SQL Developer / Toad على schema GFC_PAK
-- ================================================================
SET SERVEROUTPUT ON SIZE UNLIMITED FORMAT WRAPPED
SET LINESIZE 200

DECLARE
  V_MONTH    NUMBER := 202509;   -- ←← غيّر الشهر هنا
  V_L        NUMBER := 800;
  V_H        NUMBER := 1800;
  V_SAMPLE   NUMBER := 8;        -- عدد الموظفين للمقارنة
  V_MSG      VARCHAR2(4000);

  TYPE T_EMP IS RECORD (
      EMP_NO   NUMBER, NET_ORIG NUMBER, C238 NUMBER, D NUMBER
  );
  TYPE T_EMPS IS TABLE OF T_EMP INDEX BY PLS_INTEGER;
  V_EMPS T_EMPS;

  V_FROM   NUMBER;
  V_TO     NUMBER;

  -- ----- الفورمولا اليدوية (مطابقة لـ GET_VAL_ADD_DED_PART) -----
  PROCEDURE EXPECTED (
      P_NET    IN  NUMBER,
      P_C      IN  NUMBER,
      P_RATE   IN  NUMBER,
      P_BRANCH OUT NUMBER,
      P_RET    OUT NUMBER,
      P_DED    OUT NUMBER
  ) IS
      D  NUMBER := P_NET - P_C;
      R  NUMBER := P_RATE / 100;
      DR NUMBER := (P_NET - P_C) * (P_RATE / 100);
  BEGIN
      IF    D < V_L AND P_NET < V_L         THEN P_BRANCH := 1; P_RET := LEAST(P_NET + P_C, P_NET);
      ELSIF D < V_L AND P_NET > V_L         THEN P_BRANCH := 2; P_RET := LEAST(V_L + P_C, P_NET);
      ELSIF D >= V_L AND DR <= V_L          THEN P_BRANCH := 3; P_RET := V_L + P_C;
      ELSIF DR >= V_L AND DR <= V_H         THEN P_BRANCH := 4; P_RET := DR + P_C;
      ELSIF DR > V_H                         THEN P_BRANCH := 5; P_RET := V_H + P_C;
      ELSE                                       P_BRANCH := 0; P_RET := NULL;
      END IF;
      P_DED := CASE WHEN P_RET IS NULL THEN NULL ELSE P_NET - P_RET END;
  END;

  PROCEDURE PRINT_RESULTS (P_RATE NUMBER) IS
      V_NET NUMBER; V_CAP NUMBER; V_323 NUMBER;
      V_BR  NUMBER; V_RET NUMBER; V_DED NUMBER;
  BEGIN
      DBMS_OUTPUT.PUT_LINE(CHR(10) || '╔═══ rate=' || P_RATE || '% ═══════════════════════════════════════════════');
      DBMS_OUTPUT.PUT_LINE(RPAD('EMP', 8) || RPAD('NET_orig', 12) || RPAD('C238', 10) ||
                           RPAD('D', 10) || RPAD('br', 4) ||
                           RPAD('exp.RET', 12) || RPAD('act.CAP', 12) ||
                           RPAD('diff', 10) || RPAD('exp.323', 10) || RPAD('act.323', 10) || 'OK');
      FOR I IN 1 .. V_EMPS.COUNT LOOP
          BEGIN
              SELECT NET_SALARY, CAPPED_VAL, ACCRUED_323
                INTO V_NET, V_CAP, V_323
                FROM GFC.PAYMENT_REQ_ADMIN_CALC_TB
               WHERE EMP_NO = V_EMPS(I).EMP_NO AND MONTH = V_MONTH;
          EXCEPTION WHEN NO_DATA_FOUND THEN V_NET := NULL; V_CAP := NULL; V_323 := NULL;
          END;
          EXPECTED(V_EMPS(I).NET_ORIG, V_EMPS(I).C238, P_RATE, V_BR, V_RET, V_DED);
          DBMS_OUTPUT.PUT_LINE(
              RPAD(V_EMPS(I).EMP_NO, 8) ||
              RPAD(TO_CHAR(V_EMPS(I).NET_ORIG, 'FM99990.00'), 12) ||
              RPAD(TO_CHAR(V_EMPS(I).C238,    'FM99990.00'), 10) ||
              RPAD(TO_CHAR(V_EMPS(I).D,       'FM99990.00'), 10) ||
              RPAD(V_BR, 4) ||
              RPAD(NVL(TO_CHAR(V_RET, 'FM99990.00'), '-'), 12) ||
              RPAD(NVL(TO_CHAR(V_CAP, 'FM99990.00'), '-'), 12) ||
              RPAD(CASE WHEN V_CAP IS NULL OR V_RET IS NULL THEN '-'
                        ELSE TO_CHAR(V_CAP - V_RET, 'FM99990.00') END, 10) ||
              RPAD(NVL(TO_CHAR(V_DED, 'FM99990.00'), '-'), 10) ||
              RPAD(NVL(TO_CHAR(V_323, 'FM99990.00'), '-'), 10) ||
              CASE WHEN V_CAP IS NULL OR V_RET IS NULL THEN '?'
                   WHEN ABS(NVL(V_CAP - V_RET, 99)) < 0.01 AND ABS(NVL(V_323 - V_DED, 99)) < 0.01
                        THEN 'YES' ELSE '*** NO ***' END);
      END LOOP;
  END;

BEGIN
  -- 1) اختر عينة موظفين
  FOR R IN (SELECT * FROM (
              SELECT A.EMP_NO, A.NET_SALARY,
                     SALARYFORM.CON_238_SUM(V_MONTH, A.EMP_NO) AS C238,
                     A.NET_SALARY - SALARYFORM.CON_238_SUM(V_MONTH, A.EMP_NO) AS D
                FROM DATA.ADMIN A
               WHERE A.MONTH = V_MONTH AND A.NET_SALARY > 0
                 AND EXISTS (SELECT 1 FROM DATA.SALARY S
                              WHERE S.EMP_NO = A.EMP_NO AND S.MONTH = V_MONTH AND S.CON_NO = 323 AND S.VALUE > 0)
               ORDER BY DBMS_RANDOM.VALUE)
            WHERE ROWNUM <= V_SAMPLE) LOOP
      V_EMPS(V_EMPS.COUNT + 1).EMP_NO := R.EMP_NO;
      V_EMPS(V_EMPS.COUNT).NET_ORIG := R.NET_SALARY;
      V_EMPS(V_EMPS.COUNT).C238 := R.C238;
      V_EMPS(V_EMPS.COUNT).D := R.D;
  END LOOP;

  IF V_EMPS.COUNT = 0 THEN
      DBMS_OUTPUT.PUT_LINE('لا يوجد موظفين مطابقين لشهر ' || V_MONTH);
      RETURN;
  END IF;

  -- نطاق الموظفين
  V_FROM := V_EMPS(1).EMP_NO; V_TO := V_EMPS(1).EMP_NO;
  FOR I IN 1 .. V_EMPS.COUNT LOOP
      IF V_EMPS(I).EMP_NO < V_FROM THEN V_FROM := V_EMPS(I).EMP_NO; END IF;
      IF V_EMPS(I).EMP_NO > V_TO   THEN V_TO   := V_EMPS(I).EMP_NO; END IF;
  END LOOP;

  DBMS_OUTPUT.PUT_LINE('Month=' || V_MONTH || '  L=' || V_L || '  H=' || V_H ||
                       '  Sample=' || V_EMPS.COUNT || '  Range=[' || V_FROM || '..' || V_TO || ']');

  -- 2) شغّل على 34%
  GFC_PAK.DISBURSEMENT_CALC_PKG.CAL_SALARY_RATE_PART(
      V_MONTH, V_FROM, V_TO, 34, V_L, V_H, V_MSG);
  DBMS_OUTPUT.PUT_LINE('CAL @ 34% msg=' || V_MSG);
  PRINT_RESULTS(34);

  -- 3) شغّل على 50%
  GFC_PAK.DISBURSEMENT_CALC_PKG.CAL_SALARY_RATE_PART(
      V_MONTH, V_FROM, V_TO, 50, V_L, V_H, V_MSG);
  DBMS_OUTPUT.PUT_LINE('CAL @ 50% msg=' || V_MSG);
  PRINT_RESULTS(50);

END;
/
