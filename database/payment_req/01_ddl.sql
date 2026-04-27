-- ============================================================
-- نظام طلبات صرف الرواتب والمستحقات — DDL
-- Schema: GFC
-- ============================================================

-- 1. تنظيف (تطوير فقط)
BEGIN EXECUTE IMMEDIATE 'DROP TABLE GFC.PAYMENT_REQ_LOG_TB'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
BEGIN EXECUTE IMMEDIATE 'DROP TABLE GFC.PAYMENT_REQ_DETAIL_TB CASCADE CONSTRAINTS'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
BEGIN EXECUTE IMMEDIATE 'DROP TABLE GFC.PAYMENT_REQ_TB CASCADE CONSTRAINTS'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
BEGIN EXECUTE IMMEDIATE 'DROP SEQUENCE GFC_PAK.PAYMENT_REQ_SEQ'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
BEGIN EXECUTE IMMEDIATE 'DROP SEQUENCE GFC_PAK.PAYMENT_REQ_DETAIL_SEQ'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
BEGIN EXECUTE IMMEDIATE 'DROP SEQUENCE GFC_PAK.PAYMENT_REQ_LOG_SEQ'; EXCEPTION WHEN OTHERS THEN NULL; END;
/

-- ============================================================
-- 2. Sequences
-- ============================================================
CREATE SEQUENCE GFC_PAK.PAYMENT_REQ_SEQ        START WITH 1 INCREMENT BY 1 NOCACHE;
CREATE SEQUENCE GFC_PAK.PAYMENT_REQ_DETAIL_SEQ START WITH 1 INCREMENT BY 1 NOCACHE;
CREATE SEQUENCE GFC_PAK.PAYMENT_REQ_LOG_SEQ    START WITH 1 INCREMENT BY 1 NOCACHE;

-- ============================================================
-- 3. جدول الماستر — طلب الصرف
-- ============================================================
CREATE TABLE GFC.PAYMENT_REQ_TB (
    -- === المعرّفات ===
    REQ_ID          NUMBER          NOT NULL,       -- PK — من PAYMENT_REQ_SEQ
    REQ_NO          VARCHAR2(20)    NOT NULL,       -- رقم الطلب المقروء (PR-00001)

    -- === بيانات الطلب ===
    THE_MONTH       NUMBER(6)       NOT NULL,       -- الشهر (YYYYMM)
    REQ_TYPE        NUMBER(1)       NOT NULL,       -- نوع الطلب: 1/2/3
    PAY_TYPE        NUMBER          NULL,           -- بند المستحقات (من شجرة SALARY_DUES_TYPES_TB)

    -- === احتساب (نوع 2 فقط) ===
    CALC_METHOD     NUMBER(1)       NULL,           -- 1=نسبة مئوية, 2=مبلغ ثابت
    PERCENT_VAL     NUMBER(5,2)     NULL,           -- النسبة المئوية
    L_VALUE         NUMBER(18,2)    NULL,           -- الحد الأدنى لقيمة الصرف
    H_VALUE         NUMBER(18,2)    NULL,           -- الحد الأعلى لقيمة الصرف

    -- === مالي ===
    REQ_AMOUNT      NUMBER(18,2)    DEFAULT 0,      -- إجمالي مبلغ الطلب (SUM من التفاصيل)

    -- === حالة الطلب ===
    STATUS          NUMBER(1)       DEFAULT 0 NOT NULL, -- 0=مسودة, 1=معتمد, 2=مدفوع, 3=معتمد جزئي, 4=مصروف جزئي, 9=ملغى

    -- === ملاحظات ===
    NOTE            VARCHAR2(500)   NULL,           -- ملاحظة الإنشاء
    CANCEL_NOTE     VARCHAR2(200)   NULL,           -- سبب الإلغاء

    -- === سجل العمليات ===
    ENTRY_USER      NUMBER          NULL,
    ENTRY_DATE      DATE            DEFAULT SYSDATE,
    UPDATE_USER     NUMBER          NULL,
    UPDATE_DATE     DATE            NULL,
    APPROVE_USER    NUMBER          NULL,
    APPROVE_DATE    DATE            NULL,
    PAY_USER        NUMBER          NULL,
    PAY_DATE        DATE            NULL,
    CANCEL_USER     NUMBER          NULL,
    CANCEL_DATE     DATE            NULL,

    CONSTRAINT PK_PAYMENT_REQ PRIMARY KEY (REQ_ID)
);

COMMENT ON TABLE  GFC.PAYMENT_REQ_TB IS 'طلبات صرف الرواتب والمستحقات — جدول رئيسي';

COMMENT ON COLUMN GFC.PAYMENT_REQ_TB.REQ_ID         IS 'معرّف الطلب — تلقائي من PAYMENT_REQ_SEQ';
COMMENT ON COLUMN GFC.PAYMENT_REQ_TB.REQ_NO         IS 'رقم الطلب المقروء — PR-00001';
COMMENT ON COLUMN GFC.PAYMENT_REQ_TB.THE_MONTH      IS 'شهر الراتب بصيغة YYYYMM مثل 202508';
COMMENT ON COLUMN GFC.PAYMENT_REQ_TB.REQ_TYPE       IS 'نوع الطلب: 1=راتب كامل (100% بند 323), 2=دفعة من الراتب (نسبة% من بند 323), 3=دفعة من المستحقات (مبلغ محدد من رصيد المستحقات)';
COMMENT ON COLUMN GFC.PAYMENT_REQ_TB.PAY_TYPE       IS 'بند المستحقات — يختاره المستخدم من شجرة SALARY_DUES_TYPES_TB. افتراضي: 12 لنوع 1و2, 8 لنوع 3';
COMMENT ON COLUMN GFC.PAYMENT_REQ_TB.CALC_METHOD    IS 'طريقة الاحتساب لنوع 2 فقط: 1=نسبة مئوية, 2=مبلغ ثابت. NULL لباقي الأنواع';
COMMENT ON COLUMN GFC.PAYMENT_REQ_TB.PERCENT_VAL    IS 'النسبة المئوية لنوع 2 + طريقة نسبة. مثال: 65.00 = 65%';
COMMENT ON COLUMN GFC.PAYMENT_REQ_TB.L_VALUE         IS 'الحد الأدنى لقيمة الصرف لنوع 2. مثال: 1400';
COMMENT ON COLUMN GFC.PAYMENT_REQ_TB.H_VALUE         IS 'الحد الأعلى لقيمة الصرف لنوع 2. مثال: 3400';
COMMENT ON COLUMN GFC.PAYMENT_REQ_TB.REQ_AMOUNT     IS 'إجمالي مبلغ الطلب — يُحسب تلقائياً من مجموع تفاصيل الموظفين';
COMMENT ON COLUMN GFC.PAYMENT_REQ_TB.STATUS         IS 'حالة الطلب: 0=مسودة, 1=معتمد, 2=مدفوع, 3=معتمد جزئي, 4=مصروف جزئي, 9=ملغى';
COMMENT ON COLUMN GFC.PAYMENT_REQ_TB.NOTE           IS 'ملاحظة عند الإنشاء';
COMMENT ON COLUMN GFC.PAYMENT_REQ_TB.CANCEL_NOTE    IS 'سبب الإلغاء';

CREATE INDEX IDX_PR_MONTH  ON GFC.PAYMENT_REQ_TB(THE_MONTH);
CREATE INDEX IDX_PR_STATUS ON GFC.PAYMENT_REQ_TB(STATUS);
CREATE INDEX IDX_PR_TYPE   ON GFC.PAYMENT_REQ_TB(REQ_TYPE);

-- ============================================================
-- 4. جدول التفاصيل — موظفين الطلب
-- ============================================================
CREATE TABLE GFC.PAYMENT_REQ_DETAIL_TB (
    -- === المعرّفات ===
    DETAIL_ID         NUMBER          NOT NULL,     -- PK — من PAYMENT_REQ_DETAIL_SEQ
    REQ_ID            NUMBER          NOT NULL,     -- FK → PAYMENT_REQ_TB
    EMP_NO            NUMBER          NOT NULL,     -- رقم الموظف

    -- === المبالغ ===
    REQ_AMOUNT        NUMBER(18,2)    DEFAULT 0,    -- المبلغ المطلوب لهذا الموظف
    DUES_AMOUNT       NUMBER(18,2)    DEFAULT 0,    -- مبلغ المستحقات — يُسجل بدفتر المستحقات عند الصرف

    -- === بند الصرف ===
    PAY_TYPE          NUMBER          NULL,         -- بند المستحقات (يُحدد من PREPARE_REQUEST_VALUES)

    -- === تطبيق الحدود ===
    LIMIT_FLAG        VARCHAR2(3)     NULL,               -- NULL=عادي, MIN=رفع للأدنى, MAX=نزل للأعلى, CAP=أخذ كله

    -- === حالة الموظف (صرف جزئي) ===
    DETAIL_STATUS     NUMBER(1)       DEFAULT 0 NOT NULL, -- 0=مسودة, 1=معتمد, 2=مصروف, 9=ملغى

    -- === ربط بالصرف ===
    POST_SERIAL_DUES  NUMBER          NULL,         -- رقم السجل بـ SALARY_DUES_TB — يُملأ عند الصرف فقط

    -- === ملاحظات وسجل ===
    NOTE              VARCHAR2(500)   NULL,
    ENTRY_DATE        DATE            DEFAULT SYSDATE,
    ENTRY_USER        NUMBER          NULL,

    CONSTRAINT PK_PAYMENT_REQ_DETAIL PRIMARY KEY (DETAIL_ID),
    CONSTRAINT FK_PRD_MASTER FOREIGN KEY (REQ_ID) REFERENCES GFC.PAYMENT_REQ_TB(REQ_ID),
    CONSTRAINT UQ_PRD_EMP UNIQUE (REQ_ID, EMP_NO)
);

COMMENT ON TABLE  GFC.PAYMENT_REQ_DETAIL_TB IS 'تفاصيل طلب الصرف — صف لكل موظف';

COMMENT ON COLUMN GFC.PAYMENT_REQ_DETAIL_TB.DETAIL_ID        IS 'معرّف التفصيلة — تلقائي';
COMMENT ON COLUMN GFC.PAYMENT_REQ_DETAIL_TB.REQ_ID           IS 'معرّف الطلب الرئيسي';
COMMENT ON COLUMN GFC.PAYMENT_REQ_DETAIL_TB.EMP_NO           IS 'رقم الموظف';
COMMENT ON COLUMN GFC.PAYMENT_REQ_DETAIL_TB.REQ_AMOUNT       IS 'المبلغ المطلوب صرفه لهذا الموظف';
COMMENT ON COLUMN GFC.PAYMENT_REQ_DETAIL_TB.DUES_AMOUNT      IS 'مبلغ المستحقات — يُسجل بـ SALARY_DUES_TB عند الصرف (STATUS=2)';
COMMENT ON COLUMN GFC.PAYMENT_REQ_DETAIL_TB.PAY_TYPE         IS 'بند المستحقات — يُحدد تلقائياً من PREPARE_REQUEST_VALUES حسب نوع الطلب';
COMMENT ON COLUMN GFC.PAYMENT_REQ_DETAIL_TB.LIMIT_FLAG       IS 'تأثير الحدود: NULL=عادي, MIN=رفع للحد الأدنى, MAX=نزل للحد الأعلى, CAP=أخذ كل بند 323';
COMMENT ON COLUMN GFC.PAYMENT_REQ_DETAIL_TB.DETAIL_STATUS    IS 'حالة الموظف: 0=مسودة, 1=معتمد, 2=مصروف, 9=ملغى — يتيح الصرف الجزئي';
COMMENT ON COLUMN GFC.PAYMENT_REQ_DETAIL_TB.POST_SERIAL_DUES IS 'SERIAL بجدول SALARY_DUES_TB — يُملأ عند الصرف. يُستخدم لعكس العملية عند الإلغاء';

CREATE INDEX IDX_PRD_REQ    ON GFC.PAYMENT_REQ_DETAIL_TB(REQ_ID);
CREATE INDEX IDX_PRD_EMP    ON GFC.PAYMENT_REQ_DETAIL_TB(EMP_NO);
CREATE INDEX IDX_PRD_STATUS ON GFC.PAYMENT_REQ_DETAIL_TB(DETAIL_STATUS);

-- ============================================================
-- 5. جدول تتبع العمليات — سجل كل حركة على الطلب
-- ============================================================
CREATE TABLE GFC.PAYMENT_REQ_LOG_TB (
    LOG_ID          NUMBER          NOT NULL,       -- PK — من PAYMENT_REQ_LOG_SEQ
    REQ_ID          NUMBER          NOT NULL,       -- FK → PAYMENT_REQ_TB
    ACTION          VARCHAR2(30)    NOT NULL,       -- نوع العملية
    OLD_STATUS      NUMBER(1)       NULL,           -- الحالة قبل العملية
    NEW_STATUS      NUMBER(1)       NULL,           -- الحالة بعد العملية
    ACTION_USER     NUMBER          NULL,           -- من نفّذ العملية
    ACTION_DATE     DATE            DEFAULT SYSDATE,-- متى
    NOTE            VARCHAR2(500)   NULL,           -- تفاصيل إضافية
    EMP_COUNT       NUMBER          NULL,           -- عدد الموظفين المتأثرين
    TOTAL_AMOUNT    NUMBER(18,2)    NULL,           -- المبلغ الإجمالي وقت العملية

    CONSTRAINT PK_PAYMENT_REQ_LOG PRIMARY KEY (LOG_ID),
    CONSTRAINT FK_PRL_MASTER FOREIGN KEY (REQ_ID) REFERENCES GFC.PAYMENT_REQ_TB(REQ_ID)
);

COMMENT ON TABLE  GFC.PAYMENT_REQ_LOG_TB IS 'سجل تتبع عمليات طلبات الصرف — كل حركة تُسجل هنا';

COMMENT ON COLUMN GFC.PAYMENT_REQ_LOG_TB.LOG_ID       IS 'معرّف السجل — تلقائي';
COMMENT ON COLUMN GFC.PAYMENT_REQ_LOG_TB.REQ_ID       IS 'الطلب المعني';
COMMENT ON COLUMN GFC.PAYMENT_REQ_LOG_TB.ACTION       IS 'نوع العملية: CREATE, ADD_EMP, REMOVE_EMP, APPROVE, UNAPPROVE, PAY, CANCEL, DELETE';
COMMENT ON COLUMN GFC.PAYMENT_REQ_LOG_TB.OLD_STATUS   IS 'حالة الطلب قبل العملية';
COMMENT ON COLUMN GFC.PAYMENT_REQ_LOG_TB.NEW_STATUS   IS 'حالة الطلب بعد العملية';
COMMENT ON COLUMN GFC.PAYMENT_REQ_LOG_TB.ACTION_USER  IS 'المستخدم الذي نفّذ العملية';
COMMENT ON COLUMN GFC.PAYMENT_REQ_LOG_TB.ACTION_DATE  IS 'تاريخ ووقت العملية';
COMMENT ON COLUMN GFC.PAYMENT_REQ_LOG_TB.NOTE         IS 'تفاصيل: سبب الإلغاء, عدد الموظفين المضافين, رسالة خطأ...';
COMMENT ON COLUMN GFC.PAYMENT_REQ_LOG_TB.EMP_COUNT    IS 'عدد الموظفين وقت العملية';
COMMENT ON COLUMN GFC.PAYMENT_REQ_LOG_TB.TOTAL_AMOUNT IS 'إجمالي المبلغ وقت العملية';

CREATE INDEX IDX_PRL_REQ  ON GFC.PAYMENT_REQ_LOG_TB(REQ_ID);
CREATE INDEX IDX_PRL_DATE ON GFC.PAYMENT_REQ_LOG_TB(ACTION_DATE);

-- ============================================================
-- 6. Grants + Synonyms
-- ============================================================
-- GFC_PAK: كامل الصلاحيات (الباكج يقرأ ويكتب)
GRANT SELECT, INSERT, UPDATE, DELETE ON GFC.PAYMENT_REQ_TB        TO GFC_PAK;
GRANT SELECT, INSERT, UPDATE, DELETE ON GFC.PAYMENT_REQ_DETAIL_TB TO GFC_PAK;
GRANT SELECT, INSERT, UPDATE, DELETE ON GFC.PAYMENT_REQ_LOG_TB    TO GFC_PAK;

-- PUBLIC: قراءة فقط (للتقارير والاستعلامات)
GRANT SELECT ON GFC.PAYMENT_REQ_TB        TO PUBLIC;
GRANT SELECT ON GFC.PAYMENT_REQ_DETAIL_TB TO PUBLIC;
GRANT SELECT ON GFC.PAYMENT_REQ_LOG_TB    TO PUBLIC;

-- Synonyms
CREATE OR REPLACE SYNONYM GFC_PAK.PAYMENT_REQ_TB        FOR GFC.PAYMENT_REQ_TB;
CREATE OR REPLACE SYNONYM GFC_PAK.PAYMENT_REQ_DETAIL_TB FOR GFC.PAYMENT_REQ_DETAIL_TB;
CREATE OR REPLACE SYNONYM GFC_PAK.PAYMENT_REQ_LOG_TB    FOR GFC.PAYMENT_REQ_LOG_TB;

-- ============================================================
-- 7. الثوابت — تنظيف + إضافة الحالات الجديدة
-- ============================================================
-- حذف محافظ الصرف + ثوابت غير مستخدمة
DELETE FROM GFC.CONSTANT_DETAILS_TB WHERE TB_NO IN (539, 542, 543, 544);
DELETE FROM GFC.CONSTANT_TB WHERE TB_NO IN (539, 542, 543, 544);

-- إضافة حالات الصرف الجزئي (لو مش موجودين)
DECLARE V NUMBER;
BEGIN
  SELECT COUNT(1) INTO V FROM GFC.CONSTANT_DETAILS_TB WHERE TB_NO = 541 AND CON_NO = 3;
  IF V = 0 THEN INSERT INTO GFC.CONSTANT_DETAILS_TB (TB_NO, CON_NO, CON_NAME) VALUES (541, 3, 'معتمد جزئي'); END IF;
  SELECT COUNT(1) INTO V FROM GFC.CONSTANT_DETAILS_TB WHERE TB_NO = 541 AND CON_NO = 4;
  IF V = 0 THEN INSERT INTO GFC.CONSTANT_DETAILS_TB (TB_NO, CON_NO, CON_NAME) VALUES (541, 4, 'مصروف جزئي'); END IF;
END;
/
COMMIT;

-- ============================================================
-- 8. كمبايل الباكج
-- ============================================================
@02_pkg_spec.sql
@03_pkg_body.sql

-- ============================================================
-- 9. تحقق
-- ============================================================
SELECT OBJECT_NAME, OBJECT_TYPE, STATUS
  FROM ALL_OBJECTS
 WHERE OWNER = 'GFC_PAK' AND OBJECT_NAME = 'DISBURSEMENT_PKG'
 ORDER BY OBJECT_TYPE;
