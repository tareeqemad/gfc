CREATE OR REPLACE PACKAGE BODY GFC_PAK.DISBURSEMENT_CALC_PKG AS

  -- ============================================================
  -- Private Helpers (منسوخة من SALARYFORM - READ ONLY)
  -- ============================================================

  -- CAL_BASIC - منسوخة من SALARYFORM (احتساب الراتب الأساسي مع سنوات الخدمة)
  FUNCTION CAL_BASIC (BASIC_SAL NUMBER, PER_ALLOW NUMBER) RETURN NUMBER IS
    NEW_BASIC_SAL NUMBER(38,2);
  BEGIN
    NEW_BASIC_SAL := BASIC_SAL;
    FOR I IN 1 .. NVL(PER_ALLOW, 1) LOOP
      NEW_BASIC_SAL := NEW_BASIC_SAL + (1.25 * NEW_BASIC_SAL / 100);
    END LOOP;
    RETURN NEW_BASIC_SAL;
  END CAL_BASIC;

  -- TAX - منسوخة من SALARYFORM (احتساب الضريبة)
  FUNCTION TAX (NFTAX1 NUMBER, MY_MONTH NUMBER) RETURN NUMBER IS
    TAXAMOUNT  NUMBER(10,2);
    TAX_STEP1  NUMBER(10,2);
    TAX_STEP2  NUMBER(10,2);
    TAX_STEP3  NUMBER(10,2);
    NFTAX      NUMBER(10,2);
    GLOBAL_USA NUMBER;
  BEGIN
    SELECT VALUE INTO GLOBAL_USA FROM DATA.USA WHERE FOR_MONTH = MY_MONTH;

    TAX_STEP1 := 10000/12;
    TAX_STEP2 := 16000/12 - TAX_STEP1;
    TAX_STEP3 := 110000/12 - 16000/12;
    NFTAX := NFTAX1;
    TAXAMOUNT := 0;

    -- FIRST STAGE
    IF NFTAX > TAX_STEP1 THEN
      TAXAMOUNT := TAX_STEP1 * 0.08;
      NFTAX := NFTAX - TAX_STEP1;
    ELSE
      IF NFTAX > 0 THEN
        TAXAMOUNT := NFTAX * 0.08;
        RETURN NVL(TAXAMOUNT, 0);
      ELSE
        RETURN NVL(TAXAMOUNT, 0);
      END IF;
    END IF;

    -- SECOND STAGE
    IF NFTAX > TAX_STEP2 THEN
      TAXAMOUNT := TAXAMOUNT + TAX_STEP2 * 0.12;
      NFTAX := NFTAX - TAX_STEP2;
    ELSE
      TAXAMOUNT := TAXAMOUNT + NFTAX * 0.12;
      RETURN NVL(TAXAMOUNT, 0);
    END IF;

    -- THIRD STAGE
    IF NFTAX > TAX_STEP3 THEN
      TAXAMOUNT := TAXAMOUNT + TAX_STEP3 * 0.16;
      NFTAX := NFTAX - TAX_STEP3;
    ELSE
      TAXAMOUNT := TAXAMOUNT + NFTAX * 0.16;
      RETURN NVL(TAXAMOUNT, 0);
    END IF;

    RETURN NVL(TAXAMOUNT, 0);
  END TAX;


  -- ============================================================
  -- Accessors
  -- ============================================================

  FUNCTION GET_DISBURSEMENT_AMOUNT (P_EMP_NO NUMBER, P_THE_MONTH NUMBER) RETURN NUMBER IS
    V NUMBER := 0;
  BEGIN
    SELECT NVL(CAPPED_VAL, NVL(NET_SALARY, 0)) INTO V
      FROM GFC.PAYMENT_REQ_ADMIN_CALC_TB
     WHERE EMP_NO = P_EMP_NO AND MONTH = P_THE_MONTH;
    RETURN V;
  EXCEPTION WHEN NO_DATA_FOUND THEN RETURN 0;
  END;

  FUNCTION GET_CALC_NET_SALARY (P_EMP_NO NUMBER, P_THE_MONTH NUMBER) RETURN NUMBER IS
    V NUMBER := 0;
  BEGIN
    SELECT NVL(NET_SALARY, 0) INTO V
      FROM GFC.PAYMENT_REQ_ADMIN_CALC_TB
     WHERE EMP_NO = P_EMP_NO AND MONTH = P_THE_MONTH;
    RETURN V;
  EXCEPTION WHEN NO_DATA_FOUND THEN RETURN 0;
  END;

  FUNCTION GET_CALC_323_VALUE (P_EMP_NO NUMBER, P_THE_MONTH NUMBER) RETURN NUMBER IS
    V NUMBER := 0;
  BEGIN
    SELECT NVL(VALUE, 0) INTO V
      FROM GFC.PAYMENT_REQ_323_CALC_TB
     WHERE EMP_NO = P_EMP_NO AND THE_MONTH = P_THE_MONTH;
    RETURN V;
  EXCEPTION WHEN NO_DATA_FOUND THEN RETURN 0;
  END;

  FUNCTION HAS_CALC_DATA (P_EMP_NO NUMBER, P_THE_MONTH NUMBER) RETURN NUMBER IS
    V NUMBER := 0;
  BEGIN
    SELECT COUNT(1) INTO V
      FROM GFC.PAYMENT_REQ_ADMIN_CALC_TB
     WHERE EMP_NO = P_EMP_NO AND MONTH = P_THE_MONTH;
    RETURN CASE WHEN V > 0 THEN 1 ELSE 0 END;
  END;


  -- ============================================================
  -- CLEAR_CALC_DATA
  -- ============================================================
  PROCEDURE CLEAR_CALC_DATA (
      P_THE_MONTH NUMBER,
      P_NO_FROM   NUMBER DEFAULT NULL,
      P_NO_TO     NUMBER DEFAULT NULL
  ) IS
  BEGIN
    IF P_NO_FROM IS NOT NULL AND P_NO_TO IS NOT NULL THEN
      DELETE FROM GFC.PAYMENT_REQ_SALARY_CALC_TB
       WHERE MONTH = P_THE_MONTH AND EMP_NO BETWEEN P_NO_FROM AND P_NO_TO;
      DELETE FROM GFC.PAYMENT_REQ_ADMIN_CALC_TB
       WHERE MONTH = P_THE_MONTH AND EMP_NO BETWEEN P_NO_FROM AND P_NO_TO;
      DELETE FROM GFC.PAYMENT_REQ_323_CALC_TB
       WHERE THE_MONTH = P_THE_MONTH AND EMP_NO BETWEEN P_NO_FROM AND P_NO_TO;
    ELSE
      DELETE FROM GFC.PAYMENT_REQ_SALARY_CALC_TB WHERE MONTH = P_THE_MONTH;
      DELETE FROM GFC.PAYMENT_REQ_ADMIN_CALC_TB  WHERE MONTH = P_THE_MONTH;
      DELETE FROM GFC.PAYMENT_REQ_323_CALC_TB    WHERE THE_MONTH = P_THE_MONTH;
    END IF;
    COMMIT;
  END CLEAR_CALC_DATA;


  -- ============================================================
  -- GET_VAL_ADD_DED_PART
  -- نسخة من SALARYFORM.GET_VAL_ADD_DED
  -- تقرأ NET_SALARY من جدولنا (PAYMENT_REQ_ADMIN_CALC_TB) بدلاً من ADMIN_TEST
  -- ترجع THE_VAL = SALARY - RET (قيمة الـ 323 المحتسبة = الفرق بين NET و CAPPED)
  -- ============================================================
  FUNCTION GET_VAL_ADD_DED_PART (
      P_EMP_NO    NUMBER,
      P_THE_MONTH NUMBER,
      P_RATE      NUMBER,
      P_L_VALUE   NUMBER,
      P_H_VALUE   NUMBER
  ) RETURN NUMBER IS
    RET          NUMBER;
    SALARY       NUMBER;
    THE_VAL      NUMBER;
    RATE_PERCENT NUMBER;
  BEGIN
    RATE_PERCENT := P_RATE / 100;

    SELECT NET_SALARY,
           CASE
             WHEN NET_SALARY - SALARYFORM.CON_238_SUM(P_THE_MONTH, EMP_NO) < P_L_VALUE
                  AND NET_SALARY < P_L_VALUE
             THEN LEAST(NET_SALARY + SALARYFORM.CON_238_SUM(P_THE_MONTH, EMP_NO), NET_SALARY)

             WHEN NET_SALARY - SALARYFORM.CON_238_SUM(P_THE_MONTH, EMP_NO) < P_L_VALUE
                  AND NET_SALARY > P_L_VALUE
             THEN LEAST(P_L_VALUE + SALARYFORM.CON_238_SUM(P_THE_MONTH, EMP_NO), NET_SALARY)

             WHEN NET_SALARY - SALARYFORM.CON_238_SUM(P_THE_MONTH, EMP_NO) >= P_L_VALUE
                  AND ((NET_SALARY - SALARYFORM.CON_238_SUM(P_THE_MONTH, EMP_NO)) * RATE_PERCENT) <= P_L_VALUE
             THEN P_L_VALUE + SALARYFORM.CON_238_SUM(P_THE_MONTH, EMP_NO)

             WHEN ((NET_SALARY - SALARYFORM.CON_238_SUM(P_THE_MONTH, EMP_NO)) * RATE_PERCENT) BETWEEN P_L_VALUE AND P_H_VALUE
             THEN ((NET_SALARY - SALARYFORM.CON_238_SUM(P_THE_MONTH, EMP_NO)) * RATE_PERCENT) + SALARYFORM.CON_238_SUM(P_THE_MONTH, EMP_NO)

             WHEN ((NET_SALARY - SALARYFORM.CON_238_SUM(P_THE_MONTH, EMP_NO)) * RATE_PERCENT) > P_H_VALUE
             THEN P_H_VALUE + SALARYFORM.CON_238_SUM(P_THE_MONTH, EMP_NO)
           END AS RES
      INTO SALARY, RET
      FROM GFC.PAYMENT_REQ_ADMIN_CALC_TB
     WHERE MONTH = P_THE_MONTH AND EMP_NO = P_EMP_NO;

    THE_VAL := SALARY - RET;
    RETURN THE_VAL;
  EXCEPTION
    WHEN NO_DATA_FOUND THEN RETURN 0;
    WHEN OTHERS THEN RETURN 0;
  END GET_VAL_ADD_DED_PART;


  -- ============================================================
  -- TRANS_323_INTERNAL
  -- نسخة من SALARYFORM.TRANS_SALARY_ADD
  -- تكتب على PAYMENT_REQ_323_CALC_TB بدلاً من ADD_AND_DED
  -- ============================================================
  PROCEDURE TRANS_323_INTERNAL (
      P_THE_MONTH NUMBER,
      P_RATE      NUMBER,
      P_L_VALUE   NUMBER,
      P_H_VALUE   NUMBER,
      P_NO_FROM   NUMBER,
      P_NO_TO     NUMBER
  ) IS
    V_VALUE NUMBER;
  BEGIN
    -- مسح أي قيم سابقة في النطاق
    DELETE FROM GFC.PAYMENT_REQ_323_CALC_TB
     WHERE THE_MONTH = P_THE_MONTH
       AND EMP_NO BETWEEN P_NO_FROM AND P_NO_TO;

    -- لكل موظف: احسب قيمة 323 وأدرجه إذا > 0
    FOR R IN (SELECT EMP_NO, MONTH
                FROM GFC.PAYMENT_REQ_ADMIN_CALC_TB
               WHERE MONTH = P_THE_MONTH
                 AND EMP_NO BETWEEN P_NO_FROM AND P_NO_TO)
    LOOP
      V_VALUE := GET_VAL_ADD_DED_PART(R.EMP_NO, R.MONTH, P_RATE, P_L_VALUE, P_H_VALUE);

      IF V_VALUE > 0 THEN
        INSERT INTO GFC.PAYMENT_REQ_323_CALC_TB (
            EMP_NO, THE_MONTH, CON_NO, VALUE, IS_ADD, IS_TAXED,
            FROM_MONTH, TO_MONTH, CALC_DATE, NOTES
        ) VALUES (
            R.EMP_NO,
            R.MONTH,
            323,
            V_VALUE,
            0,
            1,
            R.MONTH,
            R.MONTH,
            SYSDATE,
            TO_CHAR(R.MONTH) || ' payment_req calc'
        );
      END IF;
    END LOOP;

    COMMIT;
  END TRANS_323_INTERNAL;


  -- ============================================================
  -- CAL_SALARY_INTERNAL
  -- نسخة من SALARYFORM.CAL_SALARY_NEW_RATE
  -- التعديلات:
  --   1. كل INSERT على SALARY_TEST/ADMIN_TEST ? PAYMENT_REQ_SALARY_CALC_TB/PAYMENT_REQ_ADMIN_CALC_TB
  --   2. SAL_VALUE cursor يقرأ من جدولنا
  --   3. RET_ADD_DED يستثني 323 من ADD_AND_DED ويوحّد مع PAYMENT_REQ_323_CALC_TB
  --   4. UPDATE على DATA.EMPLOYEES (حالة 200705) تم حذفها — لا نلمس الجداول
  -- ============================================================
  PROCEDURE CAL_SALARY_INTERNAL (
      P_MY_MONTH NUMBER,
      P_NO_FROM  NUMBER,
      P_NO_TO    NUMBER
  ) IS
    BS                NUMBER(10,2) := 0;
    GRD               NUMBER(10,2) := 0;
    DAY_BS            NUMBER(10,2) := 0;
    LIFE_VALUE        NUMBER(10,2) := 0;
    W_DAY             NUMBER(3);
    JOB_ALLOWNCE      NUMBER(10,2) := 0;
    C_VALUE           NUMBER(10,2) := 0;
    C_ADD             NUMBER(10,2) := 0;
    NETFORTAX         NUMBER(10,2) := 0;
    BAD_VAL           NUMBER(10,2) := 0;
    SP                NUMBER(2);
    ADD_O             NUMBER(2);
    IS_UPDATE         NUMBER(2);
    CONST             NUMBER(2);
    CONST_VAL         NUMBER(10,2) := 0;
    RET_P_VAL         NUMBER(10,2) := 0;
    SALARY_VAL        NUMBER(10,2) := 0;
    SALARY_MAIN       NUMBER(10,2) := 0;
    T1                NUMBER(10,2) := 0;
    T2                NUMBER(10,2) := 0;
    RET_INSUR         NUMBER(10,2) := 0;
    RET_INSUR_07      NUMBER(10,2) := 0;
    RET_INSUR_03      NUMBER(10,2) := 0;
    RET_INSUR_CO_09   NUMBER(10,2) := 0;
    RET_INSUR_CO_03   NUMBER(10,2) := 0;
    INSUR             NUMBER(10,2) := 0;
    PRM_REC           DATA.PARAMETERSN%ROWTYPE;
    RET_P_REC         DATA.P_ALLOWNCE%ROWTYPE;
    GRD_REC           DATA.GRADESN%ROWTYPE;
    JOB_REC           DATA.WORKN%ROWTYPE;
    LAST_MAIN_REC     DATA.LAST_MAIN%ROWTYPE;
    ADMIN_REC_U       DATA.ADMIN%ROWTYPE;
    WIFEALW           NUMBER(10,2) := 0;
    CHILDALW          NUMBER(10,2) := 0;
    UNEVERSITYCHILTAX NUMBER(10,2) := 0;
    PARENTFREEOFTAX   NUMBER(10,2) := 0;
    RENT              NUMBER(10,2) := 0;
    PARENT_COUNT      NUMBER(3) := 0;
    UNIV_COUNT        NUMBER(3) := 0;
    UNEVERSITY        NUMBER(10,2) := 0;
    RC                NUMBER(3) := 0;
    I                 NUMBER(3) := 0;
    CHILDCOUNTER      NUMBER(3) := 0;
    PRATHERCOUNTER    NUMBER(3) := 0;
    PRATHERACADC      NUMBER(3) := 0;
    TAXES             NUMBER(10,2) := 0;
    TAX_DED           NUMBER(10,2) := 0;
    OVERTIME_VAL      NUMBER(10,2) := 0;
    DEALYEMP_VAL      NUMBER(10,2) := 0;
    SER               NUMBER(3) := 0;
    DO_V              NUMBER(1) := 0;
    IS_FOUND          NUMBER(1) := 0;
    ADDITION          NUMBER(10,2) := 0;
    ADDITION_LAST     NUMBER(10,2) := 0;
    ADD8              NUMBER(10,2) := 0;
    ADD_DED8          NUMBER(10,2) := 0;
    BS_JOB            NUMBER(10,2) := 0;
    SP_ALLOWNCE       NUMBER(10,2) := 0;
    BAD_VALUE_ABS     NUMBER(10,2) := 0;
    DEDUCTION         NUMBER(10,2) := 0;
    ABS_DAY           NUMBER(7,2) := 0;
    ABS_VAL           NUMBER(10,2) := 0;
    ABS_DAY305        NUMBER(7,2) := 0;
    ABS_VAL305        NUMBER(10,2) := 0;
    DED_LOW_VALUE     NUMBER(10,2) := 0;
    GLOBAL_USA        NUMBER;
    V_CALC_USER       VARCHAR2(30);

    -- ============================================================
    -- Cursors (كل الـ cursors منسوخة من SALARYFORM.CAL_SALARY_NEW_RATE)
    -- مع تعديل RET_ADD_DED و SAL_VALUE
    -- ============================================================

    CURSOR PRM IS
      SELECT * FROM DATA.PARAMETERSN;

    CURSOR ESQL (NO1 NUMBER, NO2 NUMBER) IS
      SELECT * FROM DATA.EMPLOYEES_MONTH
       WHERE (NO BETWEEN NO1 AND NO2)
         AND THE_MONTH = P_MY_MONTH
         AND EMP_TYPE <> 5
         AND IS_ACTIVE = 1;

    CURSOR DEG (DEG1 NUMBER) IS
      SELECT * FROM DATA.GRADESN WHERE NO = DEG1;

    CURSOR JOB (JOB1 NUMBER) IS
      SELECT * FROM DATA.WORKN WHERE W_NO = JOB1;

    CURSOR LAST_MAIN (E_NO NUMBER) IS
      SELECT * FROM DATA.LAST_MAIN WHERE EMP_NO = E_NO;

    CURSOR WDYS (E_NO NUMBER, MON NUMBER) IS
      SELECT WORK_DAYS FROM DATA.EMPLOYEES_DAILY_WORK_DAYS
       WHERE EMP_NO = E_NO AND MONTH = MON;

    CURSOR RET_SP_ADD (NUM NUMBER) IS
      SELECT IS_SPECIAL, IS_ADD, IS_CONSTANT, VAL, IS_UPDATE
        FROM DATA.CONSTANT
       WHERE NO = NUM;

    -- *** تعديل مهم: RET_ADD_DED يستثني 323 ويوحّد مع جدولنا ***
    CURSOR RET_ADD_DED (E_NO NUMBER, MON NUMBER) IS
      SELECT CON_NO, VALUE, IS_TAXED, NOTES
        FROM DATA.ADD_AND_DED
       WHERE EMP_NO = E_NO
         AND CON_NO NOT IN (334, 235, 323)
         AND MON BETWEEN FROM_MONTH AND TO_MONTH
      UNION ALL
      SELECT CON_NO, VALUE, IS_TAXED, NOTES
        FROM GFC.PAYMENT_REQ_323_CALC_TB
       WHERE EMP_NO = E_NO
         AND THE_MONTH = MON
      ORDER BY CON_NO;

    CURSOR RET_ADD_ABS (E_NO NUMBER, MON NUMBER) IS
      SELECT EMP_NO, CON_NO, VALUE, NOTES FROM DATA.ADD_AND_DED
       WHERE EMP_NO = E_NO
         AND (MON BETWEEN FROM_MONTH AND TO_MONTH)
         AND IS_ADD = 1
         AND CON_NO IN (SELECT NO FROM DATA.CONSTANT WHERE IS_ADD = 1 AND IS_ABS = 0)
       ORDER BY CON_NO;

    CURSOR RET_P (E_NO NUMBER, MON NUMBER) IS
      SELECT * FROM DATA.P_ALLOWNCE
       WHERE EMP_NO = E_NO
         AND (MON BETWEEN FROM_MONTH AND TO_MONTH);

    -- *** تعديل مهم: SAL_VALUE يقرأ من جدولنا ***
    CURSOR SAL_VALUE (E_NO NUMBER, MON NUMBER, C_NO NUMBER) IS
      SELECT VALUE FROM GFC.PAYMENT_REQ_SALARY_CALC_TB
       WHERE EMP_NO = E_NO AND MONTH = MON AND CON_NO = C_NO;

    CURSOR GET_BAD_NO (C_NO NUMBER) IS
      SELECT CON_NO, CALCULATE_STATUS FROM DATA.CON_SPECIAL
       WHERE NO = C_NO
       ORDER BY SER;

    CURSOR RELATIVE (E_NO NUMBER, MON NUMBER) IS
      SELECT * FROM DATA.RELATIVES
       WHERE EMP_NO = E_NO AND IS_ACTIVE = 20
         AND (MON BETWEEN FROM_MONTH AND UP_TO_MONTH);

    CURSOR OVT (E_NO NUMBER, MON NUMBER) IS
      SELECT * FROM DATA.OVERTIME
       WHERE EMP_NO = E_NO AND MONTH = MON AND IS_ACTIVE = 1;

    CURSOR DEALYEMP (E_NO NUMBER, MON NUMBER) IS
      SELECT * FROM DATA.DEALYEMP
       WHERE EMP_NO = E_NO AND MONTH = MON;

    CURSOR ABS_CUR (E_NO NUMBER, MON NUMBER) IS
      SELECT SUM(DAY_NO) FROM DATA.ABSENCE
       WHERE EMP_NO = E_NO AND ON_MONTH = MON AND TO_SALARY = 1 AND TYPE = 306;

    CURSOR ABS305 (E_NO NUMBER, MON NUMBER) IS
      SELECT SUM(DAY_NO) FROM DATA.ABSENCE
       WHERE EMP_NO = E_NO AND ON_MONTH = MON AND TO_SALARY = 1 AND TYPE = 305;

  BEGIN
    -- مستخدم الاحتساب
    BEGIN
      V_CALC_USER := TO_CHAR(USER_PKG.GET_USER_ID);
    EXCEPTION WHEN OTHERS THEN V_CALC_USER := 'SYS';
    END;

    -- مسح البيانات السابقة لهذا النطاق (بدون COMMIT حتى نهاية الدالة)
    DELETE FROM GFC.PAYMENT_REQ_SALARY_CALC_TB
     WHERE MONTH = P_MY_MONTH AND EMP_NO BETWEEN P_NO_FROM AND P_NO_TO;

    DELETE FROM GFC.PAYMENT_REQ_ADMIN_CALC_TB
     WHERE MONTH = P_MY_MONTH AND EMP_NO BETWEEN P_NO_FROM AND P_NO_TO;

    OPEN PRM;
    FETCH PRM INTO PRM_REC;

    FOR E_REC IN ESQL(P_NO_FROM, P_NO_TO) LOOP
      -- تهيئة المتغيرات لكل موظف
      ABS_DAY := 0;
      ABS_VAL305 := 0;
      NETFORTAX := 0;
      SER := 0;
      ADDITION := 0;
      ADDITION_LAST := 0;
      DEDUCTION := 0;
      ABS_VAL := 0;
      BAD_VALUE_ABS := 0;
      SALARY_MAIN := 0;
      T1 := 0;
      T2 := 0;
      BS_JOB := 0;
      SP_ALLOWNCE := 0;
      DAY_BS := 0.00;
      W_DAY := 0.00;
      BS := 0;
      GRD := 0;
      UNEVERSITY := 0;
      RET_P_VAL := 0;
      DO_V := 1;

      -- الدرجة والوظيفة
      OPEN DEG(E_REC.DEGREE);
      FETCH DEG INTO GRD_REC;
      IF DEG%NOTFOUND THEN DO_V := 0; END IF;
      CLOSE DEG;

      OPEN JOB(E_REC.W_NO);
      FETCH JOB INTO JOB_REC;
      IF JOB%NOTFOUND THEN DO_V := 0; END IF;
      CLOSE JOB;

      IF DO_V = 1 THEN

        -- ==========================================
        -- BASIC SALARY
        -- ==========================================
        BS := GRD_REC.BASIC_SALARY;
        IF (E_REC.EMP_TYPE IN (1,2,10,11)) THEN
          BS := CAL_BASIC(BS, NVL(E_REC.HIRE_YEARS, 0));
        ELSIF E_REC.EMP_TYPE IN (6,7,8,9) THEN
          BS := BS;
        ELSIF E_REC.EMP_TYPE = 3 THEN
          OPEN WDYS(E_REC.NO, P_MY_MONTH);
          FETCH WDYS INTO W_DAY;
          IF WDYS%NOTFOUND THEN
            BS := 0;
          ELSE
            DAY_BS := BS;
            BS := BS * W_DAY;
          END IF;
          CLOSE WDYS;
        END IF;

        IF BS > 0 THEN
          SER := SER + 1;
          INSERT INTO GFC.PAYMENT_REQ_SALARY_CALC_TB
            VALUES (E_REC.NO, 1, P_MY_MONTH, BS, 1, E_REC.IS_TAXED, SER, '');
          ADDITION := ADDITION + BS;
          IF (E_REC.EMP_TYPE IN (1,2,10,11)) THEN
            ADDITION_LAST := ADDITION_LAST + BS;
          ELSE
            ADDITION_LAST := 0;
          END IF;
        END IF;

        -- ==========================================
        -- JOB_ALLOWNCE
        -- ==========================================
        JOB_ALLOWNCE := 0;
        IF (E_REC.EMP_TYPE IN (1,2)) THEN
          JOB_ALLOWNCE := BS * NVL(JOB_REC.ALLOWNCE, 0) / 100;
        ELSIF (E_REC.EMP_TYPE IN (10,11)) THEN
          JOB_ALLOWNCE := NVL(JOB_REC.ALLOWNCE, 0);
        ELSE
          JOB_ALLOWNCE := 0;
        END IF;

        IF JOB_ALLOWNCE > 0 THEN
          SER := SER + 1;
          INSERT INTO GFC.PAYMENT_REQ_SALARY_CALC_TB
            VALUES (E_REC.NO, 2, P_MY_MONTH, JOB_ALLOWNCE, 1, E_REC.IS_TAXED, SER, '');
          ADDITION := ADDITION + JOB_ALLOWNCE;
          ADDITION_LAST := ADDITION_LAST + JOB_ALLOWNCE;
        END IF;

        BS_JOB := BS + JOB_ALLOWNCE + NVL(E_REC.JOB_ALLOWNCE_PCT, 0);

        -- ==========================================
        -- T1 / T2 (200705 special case)
        -- *** حذف UPDATE على DATA.EMPLOYEES للحفاظ على سلامة الجداول الأصلية ***
        -- ==========================================
        IF (E_REC.EMP_TYPE < 3) THEN
          IF P_MY_MONTH = 200705 THEN
            OPEN LAST_MAIN(E_REC.NO);
            FETCH LAST_MAIN INTO LAST_MAIN_REC;
            IF LAST_MAIN%NOTFOUND THEN NULL; END IF;
            CLOSE LAST_MAIN;

            IF BS_JOB < LAST_MAIN_REC.S200704 THEN
              SALARY_MAIN := ROUND(NVL(BS_JOB - LAST_MAIN_REC.S200704, 0) * -1, 2);
              SER := SER + 1;
              INSERT INTO GFC.PAYMENT_REQ_SALARY_CALC_TB
                VALUES (E_REC.NO, 12, P_MY_MONTH, SALARY_MAIN, 1, 1, SER, '');
              T1 := NVL(SALARY_MAIN, 0);
              -- ملاحظة: لا UPDATE على DATA.EMPLOYEES
              ADDITION := ADDITION + T1;
              ADDITION_LAST := ADDITION_LAST + T1;

              SALARY_MAIN := 0;
              SALARY_MAIN := ROUND(LAST_MAIN_REC.S200704 * 0.08, 2);
              SER := SER + 1;
              INSERT INTO GFC.PAYMENT_REQ_SALARY_CALC_TB
                VALUES (E_REC.NO, 13, P_MY_MONTH, SALARY_MAIN, 1, 1, SER, '');
              T2 := NVL(SALARY_MAIN, 0);
              -- ملاحظة: لا UPDATE على DATA.EMPLOYEES
              ADDITION := ADDITION + T2;
              ADDITION_LAST := ADDITION_LAST + T2;
              SALARY_MAIN := 0;
            ELSE
              ADD_DED8 := ROUND((BS_JOB - LAST_MAIN_REC.S200704), 2);
              ADD8 := ROUND(0.08 * LAST_MAIN_REC.S200704, 2);
              IF ADD_DED8 < ADD8 THEN
                SALARY_MAIN := ROUND((ADD_DED8 - ADD8) * -1, 2);
              END IF;
              IF SALARY_MAIN > 0 THEN
                SER := SER + 1;
                INSERT INTO GFC.PAYMENT_REQ_SALARY_CALC_TB
                  VALUES (E_REC.NO, 13, P_MY_MONTH, SALARY_MAIN, 1, 1, SER, '');
                T2 := NVL(SALARY_MAIN, 0);
                -- ملاحظة: لا UPDATE على DATA.EMPLOYEES
                ADDITION := ADDITION + T2;
                ADDITION_LAST := ADDITION_LAST + T2;
              END IF;
            END IF;
          ELSE
            T1 := NVL(E_REC.JOB_ALLOWNCE_PCT_EXTRA, 0);
            ADDITION := ADDITION + T1;
            ADDITION_LAST := ADDITION_LAST + T1;
            IF T1 > 0 THEN
              SER := SER + 1;
              INSERT INTO GFC.PAYMENT_REQ_SALARY_CALC_TB
                VALUES (E_REC.NO, 12, P_MY_MONTH, T1, 1, 1, SER, '');
            END IF;

            T2 := NVL(E_REC.COMOANY_ALTERNATIVE, 0);
            ADDITION := ADDITION + T2;
            ADDITION_LAST := ADDITION_LAST + T2;
            IF T2 > 0 THEN
              SER := SER + 1;
              INSERT INTO GFC.PAYMENT_REQ_SALARY_CALC_TB
                VALUES (E_REC.NO, 13, P_MY_MONTH, T2, 1, 1, SER, '');
            END IF;
          END IF;
        END IF;

        -- ==========================================
        -- SPECIAL ALLOWNCE
        -- ==========================================
        IF E_REC.JOB_ALLOWNCE_PCT > 0 THEN
          SP_ALLOWNCE := E_REC.JOB_ALLOWNCE_PCT;
          SER := SER + 1;
          INSERT INTO GFC.PAYMENT_REQ_SALARY_CALC_TB
            VALUES (E_REC.NO, 21, P_MY_MONTH, SP_ALLOWNCE, 1, 1, SER, '');
          ADDITION := ADDITION + SP_ALLOWNCE;
          ADDITION_LAST := ADDITION_LAST + SP_ALLOWNCE;
        END IF;

        -- ==========================================
        -- PROMOTION ALLOWNCE
        -- ==========================================
        OPEN RET_P(E_REC.NO, P_MY_MONTH);
        LOOP
          FETCH RET_P INTO RET_P_REC;
          EXIT WHEN RET_P%NOTFOUND;
          RET_P_VAL := RET_P_VAL + RET_P_REC.VALUE;
        END LOOP;
        CLOSE RET_P;

        IF RET_P_VAL > 0 THEN
          SER := SER + 1;
          INSERT INTO GFC.PAYMENT_REQ_SALARY_CALC_TB
            VALUES (E_REC.NO, 430, P_MY_MONTH, RET_P_VAL, 1, 1, SER, '');
          ADDITION := ADDITION + RET_P_VAL;
          ADDITION_LAST := ADDITION_LAST + RET_P_VAL;
        END IF;

        -- ==========================================
        -- LIFE_VALUE + RETIREMENT_INSURANCE
        -- ==========================================
        LIFE_VALUE := 0;
        RET_INSUR := 0;
        RET_INSUR_07 := 0;
        RET_INSUR_03 := 0;
        RET_INSUR_CO_09 := 0;
        RET_INSUR_CO_03 := 0;

        IF (E_REC.EMP_TYPE IN (1,10,11)) THEN
          LIFE_VALUE := ROUND(BS * 0.1652, 2);
          INSERT INTO GFC.PAYMENT_REQ_SALARY_CALC_TB
            VALUES (E_REC.NO, 611, P_MY_MONTH, LIFE_VALUE, 1, 1, SER, '');
        END IF;

        ADDITION := ADDITION + LIFE_VALUE;

        IF (E_REC.EMP_TYPE = 1) THEN
          RET_INSUR_07 := (BS + JOB_ALLOWNCE + T1 + T2 + SP_ALLOWNCE + RET_P_VAL + LIFE_VALUE) * 0.07;
          RET_INSUR_03 := (BS + JOB_ALLOWNCE + T1 + T2 + SP_ALLOWNCE + RET_P_VAL + LIFE_VALUE) * 0.03;

          IF (RET_INSUR_07 > 0) THEN
            SER := SER + 1;
            INSERT INTO GFC.PAYMENT_REQ_SALARY_CALC_TB
              VALUES (E_REC.NO, 23, P_MY_MONTH, RET_INSUR_07, 0, 0, SER, '');
            DEDUCTION := DEDUCTION + RET_INSUR_07;
          END IF;

          IF (RET_INSUR_03 > 0) THEN
            SER := SER + 1;
            INSERT INTO GFC.PAYMENT_REQ_SALARY_CALC_TB
              VALUES (E_REC.NO, 24, P_MY_MONTH, RET_INSUR_03, 0, 0, SER, '');
            DEDUCTION := DEDUCTION + RET_INSUR_03;
          END IF;

          RET_INSUR_CO_09 := (BS + JOB_ALLOWNCE + T1 + T2 + SP_ALLOWNCE + RET_P_VAL + LIFE_VALUE) * 0.09;
          RET_INSUR_CO_03 := (BS + JOB_ALLOWNCE + T1 + T2 + SP_ALLOWNCE + RET_P_VAL + LIFE_VALUE) * 0.03;

          IF (RET_INSUR_CO_09 > 0) THEN
            SER := SER + 1;
            INSERT INTO GFC.PAYMENT_REQ_SALARY_CALC_TB
              VALUES (E_REC.NO, 25, P_MY_MONTH, RET_INSUR_CO_09, 2, E_REC.IS_TAXED, SER, '');
          END IF;

          IF (RET_INSUR_CO_03 > 0) THEN
            SER := SER + 1;
            INSERT INTO GFC.PAYMENT_REQ_SALARY_CALC_TB
              VALUES (E_REC.NO, 26, P_MY_MONTH, RET_INSUR_CO_03, 2, E_REC.IS_TAXED, SER, '');
          END IF;
        END IF;

        -- ==========================================
        -- INSURANCE (طبي)
        -- ==========================================
        INSUR := 0;
        IF (E_REC.EMP_TYPE IN (1,9,10,11)) THEN
          IF E_REC.INSURANCED = 1 THEN
            INSUR := BS * NVL(PRM_REC.INSURANCE_RATIO, 0);
            IF INSUR < NVL(PRM_REC.MIN_VALUE_OF_INSURANCE, 0) THEN
              INSUR := NVL(PRM_REC.MIN_VALUE_OF_INSURANCE, 0);
            END IF;
            IF INSUR > NVL(PRM_REC.MAX_VALUE_OF_INSURANCE, 0) THEN
              INSUR := NVL(PRM_REC.MAX_VALUE_OF_INSURANCE, 0);
            END IF;
            IF (INSUR > 0) THEN
              SER := SER + 1;
              INSERT INTO GFC.PAYMENT_REQ_SALARY_CALC_TB
                VALUES (E_REC.NO, 5, P_MY_MONTH, INSUR, 0, 0, SER, '');
              DEDUCTION := DEDUCTION + INSUR;
            END IF;
          END IF;
        END IF;

        -- ==========================================
        -- FAMILY ALLOWNCES
        -- ==========================================
        PARENT_COUNT := 0;
        PRATHERACADC := 0;
        UNIV_COUNT := 0;
        RC := 0;
        CHILDCOUNTER := 0;
        PRATHERCOUNTER := 0;
        RENT := 0;
        CHILDALW := 0;
        UNEVERSITYCHILTAX := 0;
        PARENTFREEOFTAX := 0;
        WIFEALW := 0;

        FOR CHD_REC IN RELATIVE(E_REC.NO, P_MY_MONTH) LOOP
          IF CHD_REC.RELATION = 1 THEN
            RC := RC + 1;
          ELSIF ((CHD_REC.RELATION = 2) OR (CHD_REC.RELATION = 3)) THEN
            CHILDCOUNTER := CHILDCOUNTER + 1;
          ELSIF (CHD_REC.RELATION = 6) OR (CHD_REC.RELATION = 7) THEN
            PRATHERCOUNTER := PRATHERCOUNTER + 1;
          ELSIF (CHD_REC.RELATION = 4) OR (CHD_REC.RELATION = 5) OR (CHD_REC.RELATION = 8) THEN
            PARENT_COUNT := PARENT_COUNT + 1;
            PARENTFREEOFTAX := PARENTFREEOFTAX + NVL((PRM_REC.FREE_OF_TAX_PER_PARENT / 12), 0);
          ELSIF (CHD_REC.RELATION = 10) THEN
            RENT := 1;
          END IF;

          IF CHD_REC.IS_ACADEMIC = 1 THEN
            UNIV_COUNT := UNIV_COUNT + 1;
            UNEVERSITYCHILTAX := UNEVERSITYCHILTAX + NVL(ROUND((PRM_REC.FREE_OF_TAX_PER_ACADEMIC_C / 12), 2), 0);
          END IF;

          IF CHD_REC.IS_ACADEMIC = 1 AND ((CHD_REC.RELATION = 6) OR (CHD_REC.RELATION = 7)) THEN
            PRATHERACADC := PRATHERACADC + 1;
          END IF;
        END LOOP;

        IF (E_REC.EMP_TYPE IN (1,10,11)) THEN
          CHILDCOUNTER := CHILDCOUNTER;

          IF CHILDCOUNTER > 0 THEN
            CHILDALW := CHILDALW + (CHILDCOUNTER * NVL(PRM_REC.CHILD_ALLOWNCE, 0));
          END IF;

          IF RC > 0 THEN
            WIFEALW := NVL(PRM_REC.FIRST_WIFE_ALLOWNCE, 0);
          ELSE
            WIFEALW := 0;
          END IF;

          IF RC > 1 THEN
            I := 2;
            WHILE I <= RC LOOP
              WIFEALW := WIFEALW + NVL(PRM_REC.OTHER_WIFES_ALLOWNCE, 0);
              I := I + 1;
            END LOOP;
          END IF;

          IF (CHILDALW + WIFEALW) > 0 THEN
            SER := SER + 1;
            INSERT INTO GFC.PAYMENT_REQ_SALARY_CALC_TB
              VALUES (E_REC.NO, 9, P_MY_MONTH, CHILDALW + WIFEALW, 1, E_REC.IS_TAXED, SER, '');
            ADDITION := ADDITION + CHILDALW + WIFEALW;
          END IF;
        END IF;

        -- ==========================================
        -- BADALS (من ADD_AND_DED + جدولنا 323)
        -- ==========================================
        FOR GET_BAD_REC_ABS IN RET_ADD_ABS(E_REC.NO, P_MY_MONTH) LOOP
          BAD_VALUE_ABS := BAD_VALUE_ABS + NVL(GET_BAD_REC_ABS.VALUE, 0);
        END LOOP;

        FOR ADD_REC IN RET_ADD_DED(E_REC.NO, P_MY_MONTH) LOOP
          IS_FOUND := 1;
          BAD_VAL := 0;
          ADD_O := 0;
          IS_UPDATE := 0;

          OPEN RET_SP_ADD(ADD_REC.CON_NO);
          FETCH RET_SP_ADD INTO SP, ADD_O, CONST, CONST_VAL, IS_UPDATE;
          IF RET_SP_ADD%NOTFOUND THEN IS_FOUND := 0; END IF;
          CLOSE RET_SP_ADD;

          IF IS_FOUND = 1 THEN
            IF SP = 0 THEN
              IF CONST = 1 THEN
                BAD_VAL := CONST_VAL;
              ELSE
                BAD_VAL := ADD_REC.VALUE;
              END IF;
            ELSE
              FOR GET_BAD_REC IN GET_BAD_NO(ADD_REC.CON_NO) LOOP
                SALARY_VAL := 0;
                OPEN SAL_VALUE(E_REC.NO, P_MY_MONTH, GET_BAD_REC.CON_NO);
                FETCH SAL_VALUE INTO SALARY_VAL;
                IF SAL_VALUE%NOTFOUND THEN SALARY_VAL := 0; END IF;
                CLOSE SAL_VALUE;

                IF GET_BAD_REC.CALCULATE_STATUS = 1 THEN
                  BAD_VAL := BAD_VAL + SALARY_VAL;
                ELSIF GET_BAD_REC.CALCULATE_STATUS = 2 THEN
                  BAD_VAL := BAD_VAL - SALARY_VAL;
                ELSIF GET_BAD_REC.CALCULATE_STATUS = 3 THEN
                  BAD_VAL := BAD_VAL * SALARY_VAL;
                ELSIF GET_BAD_REC.CALCULATE_STATUS = 4 THEN
                  BAD_VAL := BAD_VAL / SALARY_VAL;
                END IF;
              END LOOP;
              BAD_VAL := BAD_VAL * ADD_REC.VALUE;
            END IF;

            IF NVL(E_REC.IS_TAXED, 0) = 1 THEN
              IF ADD_O = 0 THEN
                IF ADD_REC.IS_TAXED = 0 THEN
                  NETFORTAX := NETFORTAX - BAD_VAL;
                END IF;
              ELSE
                IF ADD_REC.IS_TAXED = 1 THEN
                  NETFORTAX := NETFORTAX + BAD_VAL;
                END IF;
              END IF;
            ELSE
              NETFORTAX := 0;
            END IF;

            IF BAD_VAL > 0 THEN
              SER := SER + 1;
              INSERT INTO GFC.PAYMENT_REQ_SALARY_CALC_TB
                VALUES (E_REC.NO, ADD_REC.CON_NO, P_MY_MONTH, BAD_VAL, ADD_O, ADD_REC.IS_TAXED, SER, ADD_REC.NOTES);

              IF ADD_O = 1 THEN
                IF IS_UPDATE = 1 THEN
                  ADDITION_LAST := ADDITION_LAST + BAD_VAL;
                END IF;
              END IF;

              IF ADD_O = 1 THEN
                ADDITION := ADDITION + BAD_VAL;
                ABS_VAL := ABS_VAL + BAD_VAL;
              ELSE
                DEDUCTION := DEDUCTION + BAD_VAL;
              END IF;
            END IF;
          END IF;
        END LOOP;

        -- ==========================================
        -- ABSENCE 306
        -- ==========================================
        ABS_DAY := 0;
        OPEN ABS_CUR(E_REC.NO, P_MY_MONTH);
        FETCH ABS_CUR INTO ABS_DAY;
        IF ABS_CUR%NOTFOUND THEN ABS_DAY := 0; END IF;
        CLOSE ABS_CUR;

        IF ABS_DAY > 0 THEN
          ABS_VAL := NVL(ABS_VAL, 0) + NVL(BS, 0) + NVL(GRD, 0) + NVL(JOB_ALLOWNCE, 0)
                   + NVL(C_VALUE, 0) + NVL(C_ADD, 0) + NVL(T1, 0) + NVL(T2, 0)
                   + NVL(SP_ALLOWNCE, 0) + NVL(CHILDALW, 0) + NVL(WIFEALW, 0)
                   - NVL(BAD_VALUE_ABS, 0) - NVL(DED_LOW_VALUE, 0)
                   + NVL(RET_P_VAL, 0) + NVL(LIFE_VALUE, 0);

          ABS_VAL := (ABS_VAL * ABS_DAY) / 30;

          IF ABS_VAL > 0 THEN
            SER := SER + 1;
            INSERT INTO GFC.PAYMENT_REQ_SALARY_CALC_TB
              VALUES (E_REC.NO, 306, P_MY_MONTH, ABS_VAL, 0, 1, SER, '');
            DEDUCTION := DEDUCTION + ABS_VAL;
          END IF;
        END IF;

        -- ==========================================
        -- ABSENCE 305
        -- ==========================================
        ABS_DAY305 := 0;
        OPEN ABS305(E_REC.NO, P_MY_MONTH);
        FETCH ABS305 INTO ABS_DAY305;
        IF ABS305%NOTFOUND THEN ABS_DAY305 := 0; END IF;
        CLOSE ABS305;

        IF ABS_DAY305 > 0 THEN
          ABS_VAL305 := ABS_VAL305 + BS;
          ABS_VAL305 := (ABS_VAL305 * ABS_DAY305) / 30;
          IF ABS_VAL305 > 0 THEN
            SER := SER + 1;
            INSERT INTO GFC.PAYMENT_REQ_SALARY_CALC_TB
              VALUES (E_REC.NO, 305, P_MY_MONTH, ABS_VAL305, 0, 1, SER, '');
            DEDUCTION := DEDUCTION + ABS_VAL305;
          END IF;
        END IF;

        -- ==========================================
        -- OVERTIME
        -- ==========================================
        FOR OVT_REC IN OVT(E_REC.NO, P_MY_MONTH) LOOP
          OVERTIME_VAL := 0;
          IF NVL(OVT_REC.HOUR_RATE, 0) > 0 THEN
            OVERTIME_VAL := NVL(OVT_REC.CALCULATED_HOURS, 0) * OVT_REC.HOUR_RATE;
          ELSE
            IF E_REC.EMP_TYPE = 3 THEN
              OVERTIME_VAL := NVL(OVT_REC.CALCULATED_HOURS, 0) * (DAY_BS / 6);
            ELSE
              OVERTIME_VAL := NVL(OVT_REC.CALCULATED_HOURS, 0) * (BS / 6 / 30);
            END IF;
          END IF;

          IF NVL(OVT_REC.DAY, 0) > 0 THEN
            OVERTIME_VAL := OVERTIME_VAL + ((BS / 30) * OVT_REC.DAY);
          END IF;

          IF OVT_REC.IS_TAXED = 1 THEN
            NETFORTAX := NETFORTAX + OVERTIME_VAL;
          END IF;

          IF (OVERTIME_VAL > 0) THEN
            SER := SER + 1;
            INSERT INTO GFC.PAYMENT_REQ_SALARY_CALC_TB
              VALUES (E_REC.NO, 10, P_MY_MONTH, OVERTIME_VAL, 1, OVT_REC.IS_TAXED, SER, '');
            ADDITION := ADDITION + OVERTIME_VAL;
          END IF;
        END LOOP;

        -- ==========================================
        -- DEALYEMP (التأخير)
        -- ==========================================
        FOR DEALYEMP_REC IN DEALYEMP(E_REC.NO, P_MY_MONTH) LOOP
          DEALYEMP_VAL := 0;
          IF NVL(DEALYEMP_REC.HOUR_RATE, 0) > 0 THEN
            DEALYEMP_VAL := NVL(DEALYEMP_REC.CALCULATED_HOURS, 0) * DEALYEMP_REC.HOUR_RATE;
          ELSE
            IF E_REC.EMP_TYPE = 3 THEN
              DEALYEMP_VAL := NVL(DEALYEMP_REC.CALCULATED_HOURS, 0) * (DAY_BS / 6);
            ELSE
              DEALYEMP_VAL := NVL(DEALYEMP_REC.CALCULATED_HOURS, 0) * (BS / 6 / 30);
            END IF;
          END IF;

          IF NVL(DEALYEMP_REC.DAY, 0) > 0 THEN
            DEALYEMP_VAL := DEALYEMP_VAL + ((BS / 30) * DEALYEMP_REC.DAY);
          END IF;

          IF (DEALYEMP_VAL > 0) THEN
            SER := SER + 1;
            INSERT INTO GFC.PAYMENT_REQ_SALARY_CALC_TB
              VALUES (E_REC.NO, 427, P_MY_MONTH, DEALYEMP_VAL, 0, DEALYEMP_REC.IS_TAXED, SER, DEALYEMP_REC.NOTES);
            DEDUCTION := DEDUCTION + DEALYEMP_VAL;
          END IF;
        END LOOP;

        -- ==========================================
        -- TAXES
        -- ==========================================
        TAXES := 0;
        TAX_DED := 0;
        IF E_REC.IS_TAXED = 1 THEN
          NETFORTAX := NETFORTAX + BS + JOB_ALLOWNCE + T1 + T2 + RET_P_VAL + SP_ALLOWNCE
                     + C_VALUE + C_ADD + CHILDALW + WIFEALW + GRD
                     - NVL(RET_INSUR, 0) - NVL(RET_INSUR_03, 0) - NVL(RET_INSUR_07, 0)
                     - INSUR + NVL(LIFE_VALUE, 0);

          SELECT VALUE INTO GLOBAL_USA FROM DATA.USA WHERE FOR_MONTH = P_MY_MONTH;

          IF E_REC.NO <> 1365 THEN
            NETFORTAX := (NETFORTAX / GLOBAL_USA);
          ELSE
            NETFORTAX := NETFORTAX;
          END IF;

          IF NETFORTAX > 0 THEN
            SER := SER + 1;
            INSERT INTO GFC.PAYMENT_REQ_SALARY_CALC_TB
              VALUES (E_REC.NO, 11, P_MY_MONTH, (NETFORTAX * GLOBAL_USA), 2, 2, SER, '');
          END IF;

          IF (E_REC.STATUS = 1) THEN
            TAX_DED := (3000/12) + PARENTFREEOFTAX + (PRATHERCOUNTER) * (500/12) + UNEVERSITYCHILTAX + (RENT * 2000/12);
            NETFORTAX := NETFORTAX - TAX_DED;
          ELSE
            IF (E_REC.STATUS = 2) OR (E_REC.STATUS = 3) OR (E_REC.STATUS = 4) THEN
              UNEVERSITY := NVL(ADMIN_REC_U.ACAD_COUNT, 0) * NVL(ROUND((PRM_REC.FREE_OF_TAX_PER_ACADEMIC_C / 12), 2), 0);

              IF WIFEALW > 60 THEN WIFEALW := 60; END IF;

              IF E_REC.EMP_TYPE = 4 THEN
                TAX_DED := (3000/12) + ((500/12) * RC) + ((500/12) * (CHILDCOUNTER))
                         + UNEVERSITYCHILTAX + PARENTFREEOFTAX + (PRATHERCOUNTER) * (500/12);
              END IF;

              IF E_REC.EMP_TYPE IN (1,6,7,8,10,11) THEN
                TAX_DED := (3000/12) + ((500/12) * (WIFEALW/60)) + ((500/12) * (CHILDCOUNTER))
                         + UNEVERSITYCHILTAX + PARENTFREEOFTAX + (PRATHERCOUNTER) * (500/12)
                         + (RENT * 2000/12);
              END IF;

              IF E_REC.EMP_TYPE IN (9) THEN
                IF RC = 0 THEN RC := 0; ELSE RC := 1; END IF;
                TAX_DED := (3000/12) + ((500/12) * (RC)) + ((500/12) * (CHILDCOUNTER))
                         + UNEVERSITYCHILTAX + PARENTFREEOFTAX + (PRATHERCOUNTER) * (500/12)
                         + (RENT * 2000/12);
              END IF;

              IF TAX_DED > 1000 THEN TAX_DED := 1000; END IF;
              NETFORTAX := NETFORTAX - TAX_DED;
            END IF;
          END IF;

          TAXES := TAX(NETFORTAX, P_MY_MONTH);
          TAXES := ROUND(TAXES, 2);

          IF (TAXES > 0) THEN
            SER := SER + 1;
            IF E_REC.PRICE_CODE = 1 THEN
              IF E_REC.NO IN (1493) THEN TAXES := 0; END IF;
              INSERT INTO GFC.PAYMENT_REQ_SALARY_CALC_TB
                VALUES (E_REC.NO, 8, P_MY_MONTH, (TAXES * GLOBAL_USA), 0, 2, SER, '');
              IF E_REC.NO NOT IN (1493) THEN
                DEDUCTION := DEDUCTION + TAXES * GLOBAL_USA;
              END IF;
            ELSE
              IF E_REC.NO IN (1493) THEN TAXES := 0; END IF;
              INSERT INTO GFC.PAYMENT_REQ_SALARY_CALC_TB
                VALUES (E_REC.NO, 8, P_MY_MONTH, (TAXES), 0, 2, SER, '');
              IF E_REC.NO <> 1365 THEN
                DEDUCTION := DEDUCTION + TAXES;
              END IF;
            END IF;
          END IF;
        END IF;

        -- ==========================================
        -- ADMIN (ملخص الموظف)
        -- ==========================================
        INSERT INTO GFC.PAYMENT_REQ_ADMIN_CALC_TB (
            EMP_NO, MONTH, EMP_TYPE, BRAN, BRANCH, DEPARTMENT,
            STATUS, DEGREE, HIRE_YEARS, BANK, ACCOUNT,
            INSURANCED, INSURANCE_NO, W_NO, NET_SALARY,
            CHILD_COUNT, WIFE_COUNT, PARENT_COUNT, UNIV_COUNT,
            JOB_ALLOWNCE_PCT, JOB_ALLOWNCE_PCT_EXTRA, COMOANY_ALTERNATIVE,
            W_NO_ADMIN, SP_NO, Q_NO, PRICE_CODE, NOTES,
            CALC_DATE, CALC_USER
        ) VALUES (
            E_REC.NO, P_MY_MONTH, E_REC.EMP_TYPE, E_REC.BRAN, E_REC.BRANCH, E_REC.DEPARTMENT,
            E_REC.STATUS, E_REC.DEGREE, E_REC.HIRE_YEARS, E_REC.BANK, E_REC.ACCOUNT,
            E_REC.INSURANCED, E_REC.INSURANCE_NO, E_REC.W_NO, ADDITION - DEDUCTION,
            CHILDCOUNTER, RC, PARENT_COUNT, UNIV_COUNT,
            E_REC.JOB_ALLOWNCE_PCT, E_REC.JOB_ALLOWNCE_PCT_EXTRA, E_REC.COMOANY_ALTERNATIVE,
            E_REC.W_NO_ADMIN, E_REC.SP_NO, E_REC.Q_NO, E_REC.PRICE_CODE, '  ',
            SYSDATE, V_CALC_USER
        );

      END IF;
    END LOOP;

    CLOSE PRM;
    COMMIT;
  EXCEPTION
    WHEN OTHERS THEN
      IF PRM%ISOPEN THEN CLOSE PRM; END IF;
      ROLLBACK;
      RAISE;
  END CAL_SALARY_INTERNAL;


  -- ============================================================
  -- CAL_SALARY_RATE_PART (Wrapper — مطابق لـ SALARYFORM.CAL_SALARY_RATE)
  -- التسلسل الكامل:
  --   1. مسح جدول 323 للنطاق
  --   2. احتساب أولي (بدون 323)
  --   3. مسح جدول 323 (احتياط)
  --   4. احتساب 323 لكل موظف بناءً على الاحتساب الأولي
  --   5. احتساب نهائي (مع 323)
  -- ============================================================
  PROCEDURE CAL_SALARY_RATE_PART (
      P_THE_MONTH NUMBER,
      P_NO_FROM   NUMBER,
      P_NO_TO     NUMBER,
      P_RATE      NUMBER,
      P_L_VALUE   NUMBER,
      P_H_VALUE   NUMBER,
      P_MSG_OUT   OUT VARCHAR2
  ) IS
  BEGIN
    -- الخطوة 1: مسح جدول 323 للنطاق
    DELETE FROM GFC.PAYMENT_REQ_323_CALC_TB
     WHERE THE_MONTH = P_THE_MONTH
       AND EMP_NO BETWEEN P_NO_FROM AND P_NO_TO;
    COMMIT;

    -- الخطوة 2: احتساب أولي (بدون 323)
    CAL_SALARY_INTERNAL(P_THE_MONTH, P_NO_FROM, P_NO_TO);

    -- الخطوة 3: مسح جدول 323 مرة أخرى (احتياط لتأكيد حالة نظيفة)
    DELETE FROM GFC.PAYMENT_REQ_323_CALC_TB
     WHERE THE_MONTH = P_THE_MONTH
       AND EMP_NO BETWEEN P_NO_FROM AND P_NO_TO;
    COMMIT;

    -- الخطوة 4: احتساب 323 لكل موظف وإدخاله في جدولنا
    TRANS_323_INTERNAL(P_THE_MONTH, P_RATE, P_L_VALUE, P_H_VALUE, P_NO_FROM, P_NO_TO);

    -- الخطوة 5: احتساب نهائي (مع 323 من جدولنا)
    CAL_SALARY_INTERNAL(P_THE_MONTH, P_NO_FROM, P_NO_TO);

    -- تحديث الحقول الإضافية
    UPDATE GFC.PAYMENT_REQ_ADMIN_CALC_TB A
       SET A.CAPPED_VAL = A.NET_SALARY,
           A.ACCRUED_323 = NVL((SELECT VALUE FROM GFC.PAYMENT_REQ_323_CALC_TB
                                 WHERE EMP_NO = A.EMP_NO AND THE_MONTH = A.MONTH), 0),
           A.RATE_USED = P_RATE,
           A.L_VALUE_USED = P_L_VALUE,
           A.H_VALUE_USED = P_H_VALUE
     WHERE A.MONTH = P_THE_MONTH
       AND A.EMP_NO BETWEEN P_NO_FROM AND P_NO_TO;

    COMMIT;
    P_MSG_OUT := '1';
  EXCEPTION
    WHEN OTHERS THEN
      ROLLBACK;
      P_MSG_OUT := SQLERRM;
  END CAL_SALARY_RATE_PART;


END DISBURSEMENT_CALC_PKG;
/
