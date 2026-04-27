-- ============================================================
-- جداول دفعات الصرف البنكي — DDL
-- Schema: GFC
-- ============================================================

-- 1. Sequences
BEGIN EXECUTE IMMEDIATE 'DROP SEQUENCE GFC_PAK.PAYMENT_BATCH_SEQ'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
BEGIN EXECUTE IMMEDIATE 'DROP SEQUENCE GFC_PAK.PAYMENT_BATCH_DETAIL_SEQ'; EXCEPTION WHEN OTHERS THEN NULL; END;
/

CREATE SEQUENCE GFC_PAK.PAYMENT_BATCH_SEQ        START WITH 1 INCREMENT BY 1 NOCACHE;
CREATE SEQUENCE GFC_PAK.PAYMENT_BATCH_DETAIL_SEQ START WITH 1 INCREMENT BY 1 NOCACHE;

-- ============================================================
-- 2. جدول ماستر الدفعة — كل عملية تجهيز صرف = سجل واحد
-- ============================================================
CREATE TABLE GFC.PAYMENT_BATCH_TB (
    BATCH_ID        NUMBER          NOT NULL,
    BATCH_NO        VARCHAR2(20)    NOT NULL,           -- PB-00001
    BATCH_DATE      DATE            DEFAULT SYSDATE,
    TOTAL_AMOUNT    NUMBER(18,2)    DEFAULT 0,
    EMP_COUNT       NUMBER          DEFAULT 0,
    REQ_IDS         VARCHAR2(4000)  NULL,               -- الطلبات المشمولة
    -- removed DETAIL_IDS CLOB — الربط عبر PAYMENT_BATCH_LINK_TB
    STATUS          NUMBER(1)       DEFAULT 0 NOT NULL, -- 0=جديد, 2=مصروف
    ENTRY_USER      NUMBER          NULL,               -- من أنشأ (اعتماد التجهيز)
    ENTRY_DATE      DATE            DEFAULT SYSDATE,
    PAY_USER        NUMBER          NULL,               -- من نفّذ الصرف
    PAY_DATE        DATE            NULL,               -- متى تم الصرف

    CONSTRAINT PK_PAYMENT_BATCH PRIMARY KEY (BATCH_ID)
);

COMMENT ON TABLE  GFC.PAYMENT_BATCH_TB IS 'دفعات الصرف البنكي — ماستر';
COMMENT ON COLUMN GFC.PAYMENT_BATCH_TB.BATCH_ID     IS 'معرّف الدفعة';
COMMENT ON COLUMN GFC.PAYMENT_BATCH_TB.BATCH_NO     IS 'رقم الدفعة المقروء PB-00001';
COMMENT ON COLUMN GFC.PAYMENT_BATCH_TB.TOTAL_AMOUNT IS 'إجمالي مبلغ الدفعة للتحويل البنكي';
COMMENT ON COLUMN GFC.PAYMENT_BATCH_TB.EMP_COUNT    IS 'عدد الموظفين';
COMMENT ON COLUMN GFC.PAYMENT_BATCH_TB.REQ_IDS      IS 'أرقام الطلبات المشمولة (comma-separated)';

CREATE INDEX IDX_PB_DATE   ON GFC.PAYMENT_BATCH_TB(BATCH_DATE);
CREATE INDEX IDX_PB_STATUS ON GFC.PAYMENT_BATCH_TB(STATUS);

-- ============================================================
-- 3. جدول تفاصيل الدفعة — صف لكل موظف بالمبلغ الإجمالي للبنك
-- ============================================================
CREATE TABLE GFC.PAYMENT_BATCH_DETAIL_TB (
    BATCH_DETAIL_ID NUMBER          NOT NULL,
    BATCH_ID        NUMBER          NOT NULL,
    EMP_NO          NUMBER          NOT NULL,
    BANK_NO         NUMBER          NULL,               -- بنك الموظف (DATA.EMPLOYEES.BANK)
    MASTER_BANK_NO  NUMBER          NULL,               -- بنك التحويل (DATA.EMPLOYEES.MASTER_BANKS_EMAIL)
    TOTAL_AMOUNT    NUMBER(18,2)    DEFAULT 0,          -- الإجمالي للتحويل البنكي

    CONSTRAINT PK_PAYMENT_BATCH_DETAIL PRIMARY KEY (BATCH_DETAIL_ID),
    CONSTRAINT FK_PBD_MASTER FOREIGN KEY (BATCH_ID) REFERENCES GFC.PAYMENT_BATCH_TB(BATCH_ID)
);

COMMENT ON TABLE  GFC.PAYMENT_BATCH_DETAIL_TB IS 'تفاصيل دفعة الصرف — صف لكل موظف بالمبلغ الإجمالي';
COMMENT ON COLUMN GFC.PAYMENT_BATCH_DETAIL_TB.TOTAL_AMOUNT IS 'مجموع كل أنواع الطلبات لهذا الموظف — المبلغ اللي يتحول للبنك';

CREATE INDEX IDX_PBD_BATCH ON GFC.PAYMENT_BATCH_DETAIL_TB(BATCH_ID);
CREATE INDEX IDX_PBD_EMP   ON GFC.PAYMENT_BATCH_DETAIL_TB(EMP_NO);

-- ============================================================
-- 4. جدول ربط الدفعة بالديتيل — صف لكل detail محدد من المحاسب
-- ============================================================
CREATE TABLE GFC.PAYMENT_BATCH_LINK_TB (
    BATCH_ID          NUMBER          NOT NULL,
    DETAIL_ID         NUMBER          NOT NULL,
    DUES_SERIAL       NUMBER          NULL,           -- SALARY_DUES_TB.SERIAL — يُملأ عند الصرف

    CONSTRAINT PK_PBL PRIMARY KEY (BATCH_ID, DETAIL_ID),
    CONSTRAINT FK_PBL_BATCH FOREIGN KEY (BATCH_ID) REFERENCES GFC.PAYMENT_BATCH_TB(BATCH_ID),
    CONSTRAINT FK_PBL_DETAIL FOREIGN KEY (DETAIL_ID) REFERENCES GFC.PAYMENT_REQ_DETAIL_TB(DETAIL_ID)
);

CREATE INDEX IDX_PBL_BATCH  ON GFC.PAYMENT_BATCH_LINK_TB(BATCH_ID);
CREATE INDEX IDX_PBL_DETAIL ON GFC.PAYMENT_BATCH_LINK_TB(DETAIL_ID);

-- ============================================================
-- 5. Grants + Synonyms
-- ============================================================
GRANT SELECT, INSERT, UPDATE, DELETE ON GFC.PAYMENT_BATCH_TB        TO GFC_PAK;
GRANT SELECT, INSERT, UPDATE, DELETE ON GFC.PAYMENT_BATCH_DETAIL_TB TO GFC_PAK;
GRANT SELECT, INSERT, UPDATE, DELETE ON GFC.PAYMENT_BATCH_LINK_TB   TO GFC_PAK;
GRANT SELECT ON GFC.PAYMENT_BATCH_TB        TO PUBLIC;
GRANT SELECT ON GFC.PAYMENT_BATCH_DETAIL_TB TO PUBLIC;
GRANT SELECT ON GFC.PAYMENT_BATCH_LINK_TB   TO PUBLIC;

CREATE OR REPLACE SYNONYM GFC_PAK.PAYMENT_BATCH_TB        FOR GFC.PAYMENT_BATCH_TB;
CREATE OR REPLACE SYNONYM GFC_PAK.PAYMENT_BATCH_DETAIL_TB FOR GFC.PAYMENT_BATCH_DETAIL_TB;
CREATE OR REPLACE SYNONYM GFC_PAK.PAYMENT_BATCH_LINK_TB   FOR GFC.PAYMENT_BATCH_LINK_TB;

COMMIT;
