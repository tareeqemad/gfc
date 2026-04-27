-- ============================================================
-- DDL: جداول احتساب الرواتب الخاصة بـ payment_req (نوع 2)
-- منفصلة تماماً عن جداول الرواتب القديمة (SALARY, ADMIN, ADD_AND_DED, SALARY_TEST, ADMIN_TEST)
-- الهدف: احتساب NET_SALARY بنفس منطق SALARYFORM بدون لمس الجداول الأصلية
-- ============================================================

-- ============================================================
-- 1) PAYMENT_REQ_SALARY_CALC_TB — نسخة من SALARY_TEST (تفاصيل كل بند)
-- ============================================================
CREATE TABLE GFC.PAYMENT_REQ_SALARY_CALC_TB (
    EMP_NO      NUMBER,
    CON_NO      NUMBER,
    MONTH       NUMBER,
    VALUE       NUMBER(14,2),
    IS_ADD      NUMBER(1),
    IS_TAXED    NUMBER(1),
    SER         NUMBER,
    NOTES       VARCHAR2(200)
);

CREATE INDEX GFC.IDX_PR_SALARY_CALC_EMP_MON ON GFC.PAYMENT_REQ_SALARY_CALC_TB (EMP_NO, MONTH);
CREATE INDEX GFC.IDX_PR_SALARY_CALC_CON ON GFC.PAYMENT_REQ_SALARY_CALC_TB (EMP_NO, MONTH, CON_NO);


-- ============================================================
-- 2) PAYMENT_REQ_ADMIN_CALC_TB — نسخة من ADMIN_TEST (ملخص كل موظف)
-- ============================================================
CREATE TABLE GFC.PAYMENT_REQ_ADMIN_CALC_TB (
    EMP_NO                 NUMBER NOT NULL,
    MONTH                  NUMBER NOT NULL,
    EMP_TYPE               NUMBER,
    BRAN                   NUMBER,
    BRANCH                 NUMBER,
    DEPARTMENT             NUMBER,
    STATUS                 NUMBER,
    DEGREE                 NUMBER,
    HIRE_YEARS             NUMBER,
    BANK                   NUMBER,
    ACCOUNT                VARCHAR2(50),
    INSURANCED             NUMBER,
    INSURANCE_NO           VARCHAR2(50),
    W_NO                   NUMBER,
    NET_SALARY             NUMBER(14,2),
    CHILD_COUNT            NUMBER,
    WIFE_COUNT             NUMBER,
    PARENT_COUNT           NUMBER,
    UNIV_COUNT             NUMBER,
    JOB_ALLOWNCE_PCT       NUMBER,
    JOB_ALLOWNCE_PCT_EXTRA NUMBER,
    COMOANY_ALTERNATIVE    NUMBER,
    W_NO_ADMIN             NUMBER,
    SP_NO                  NUMBER,
    Q_NO                   NUMBER,
    PRICE_CODE             NUMBER,
    NOTES                  VARCHAR2(200),
    CALC_DATE              DATE DEFAULT SYSDATE,
    CALC_USER              VARCHAR2(30),
    -- حقول مخصصة لـ payment_req
    CAPPED_VAL             NUMBER(14,2),  -- قيمة الصرف (CAPPED)
    ACCRUED_323            NUMBER(14,2),  -- قيمة 323 المحتسبة
    RATE_USED              NUMBER,
    L_VALUE_USED           NUMBER,
    H_VALUE_USED           NUMBER,
    CONSTRAINT PK_PR_ADMIN_CALC PRIMARY KEY (EMP_NO, MONTH)
);

CREATE INDEX GFC.IDX_PR_ADMIN_CALC_MON ON GFC.PAYMENT_REQ_ADMIN_CALC_TB (MONTH);


-- ============================================================
-- 3) PAYMENT_REQ_323_CALC_TB — بديل إدخال 323 في ADD_AND_DED
-- يتم استخدامه في الخطوة 5 (CAL_SALARY_INTERNAL الثاني) بدلاً من ADD_AND_DED
-- ============================================================
CREATE TABLE GFC.PAYMENT_REQ_323_CALC_TB (
    EMP_NO       NUMBER NOT NULL,
    THE_MONTH    NUMBER NOT NULL,
    CON_NO       NUMBER DEFAULT 323,
    VALUE        NUMBER(14,2),
    IS_ADD       NUMBER(1) DEFAULT 0,
    IS_TAXED     NUMBER(1) DEFAULT 1,
    FROM_MONTH   NUMBER,
    TO_MONTH     NUMBER,
    CALC_DATE    DATE DEFAULT SYSDATE,
    CALC_USER    VARCHAR2(30),
    NOTES        VARCHAR2(200),
    CONSTRAINT PK_PR_323_CALC PRIMARY KEY (EMP_NO, THE_MONTH)
);

CREATE INDEX GFC.IDX_PR_323_CALC_MON ON GFC.PAYMENT_REQ_323_CALC_TB (THE_MONTH);


-- ============================================================
-- Grants
-- ============================================================
GRANT SELECT, INSERT, UPDATE, DELETE ON GFC.PAYMENT_REQ_SALARY_CALC_TB TO GFC_PAK;
GRANT SELECT, INSERT, UPDATE, DELETE ON GFC.PAYMENT_REQ_ADMIN_CALC_TB TO GFC_PAK;
GRANT SELECT, INSERT, UPDATE, DELETE ON GFC.PAYMENT_REQ_323_CALC_TB TO GFC_PAK;

-- لـ SALARYFORM (يحتاج قراءة من PAYMENT_REQ_ADMIN_CALC_TB عند التكامل - غير مطلوب حالياً)
-- GRANT SELECT ON GFC.PAYMENT_REQ_ADMIN_CALC_TB TO SALARYFORM;


-- ============================================================
-- Public Synonyms
-- للسماح بالوصول من أي schema بدون كتابة GFC. قبل الاسم
-- ============================================================
CREATE OR REPLACE PUBLIC SYNONYM PAYMENT_REQ_SALARY_CALC_TB FOR GFC.PAYMENT_REQ_SALARY_CALC_TB;
CREATE OR REPLACE PUBLIC SYNONYM PAYMENT_REQ_ADMIN_CALC_TB  FOR GFC.PAYMENT_REQ_ADMIN_CALC_TB;
CREATE OR REPLACE PUBLIC SYNONYM PAYMENT_REQ_323_CALC_TB    FOR GFC.PAYMENT_REQ_323_CALC_TB;

/
