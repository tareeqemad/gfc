-- ============================================================
-- Migration: PAYMENT_REQ_IMP_LINE_TB (سابقاً PAYMENT_REQ_DETAIL_IMPORT_LINE_TB)
-- ============================================================
-- ⚠️ تم اختصار الاسم لأن Oracle 11g/12c يحدّ identifiers بـ 30 character.
-- الاسم القديم كان 33 char → ORA-00972.
--
-- الهدف: حفظ البنود الأصلية من ملف الإكسل قبل دمج التكرار.
--
-- السياق:
-- لما المحاسب يرفع إكسل فيه نفس الموظف عدة مرات (كل سطر بند خصم/إضافة)،
-- ندمج السطور في PAYMENT_REQ_DETAIL_TB (سطر واحد لكل موظف بالإجمالي)
-- لكن نحفظ التفاصيل الأصلية هنا — للمراجعة والتدقيق لاحقاً.
--
-- مثال:
--   موظف 1023 (جهاد بهلول) عنده 11 سطر في الإكسل بمبالغ مختلفة
--   PAYMENT_REQ_DETAIL_TB ⮕ سطر واحد بـ REQ_AMOUNT = 6,591.47 (الإجمالي)
--   PAYMENT_REQ_IMP_LINE_TB ⮕ 11 سطر فيها كل التفاصيل
-- ============================================================
-- ⚠️ يُشغَّل مرة واحدة فقط
-- ============================================================

-- 1. تنظيف (للتطوير)
BEGIN EXECUTE IMMEDIATE 'DROP TABLE GFC.PAYMENT_REQ_IMP_LINE_TB CASCADE CONSTRAINTS'; EXCEPTION WHEN OTHERS THEN NULL; END;
/
BEGIN EXECUTE IMMEDIATE 'DROP SEQUENCE GFC_PAK.PAYMENT_REQ_IMP_LINE_SEQ'; EXCEPTION WHEN OTHERS THEN NULL; END;
/

-- 2. الجدول
CREATE TABLE GFC.PAYMENT_REQ_IMP_LINE_TB (
    IMP_LINE_ID    NUMBER         NOT NULL,
    DETAIL_ID      NUMBER         NOT NULL,           -- FK → PAYMENT_REQ_DETAIL_TB
    LINE_NO        NUMBER         NOT NULL,           -- ترتيب السطر داخل الموظف (1, 2, 3...)
    EXCEL_ROW_NUM  NUMBER,                            -- رقم الصف في الإكسل الأصلي
    AMOUNT         NUMBER(14,2)   DEFAULT 0,
    ORIGINAL_NOTE  VARCHAR2(500),                     -- ملاحظة هذا السطر من العمود C في الإكسل
    ATTACHMENT_ID  NUMBER,                            -- يربط بـ GFC_ATTACHMENT_TB (الملف الأصلي)
    IMPORT_DATE    DATE           DEFAULT SYSDATE,
    ENTRY_USER     NUMBER,

    CONSTRAINT PK_PRIL          PRIMARY KEY (IMP_LINE_ID),
    CONSTRAINT FK_PRIL_DETAIL   FOREIGN KEY (DETAIL_ID)
        REFERENCES GFC.PAYMENT_REQ_DETAIL_TB(DETAIL_ID) ON DELETE CASCADE
);

COMMENT ON TABLE  GFC.PAYMENT_REQ_IMP_LINE_TB IS 'البنود الأصلية المستوردة من الإكسل قبل دمج التكرار — للمراجعة والتدقيق';
COMMENT ON COLUMN GFC.PAYMENT_REQ_IMP_LINE_TB.IMP_LINE_ID    IS 'PK';
COMMENT ON COLUMN GFC.PAYMENT_REQ_IMP_LINE_TB.DETAIL_ID      IS 'FK لـ PAYMENT_REQ_DETAIL_TB — كل بند ينتمي لتفصيلة موظف';
COMMENT ON COLUMN GFC.PAYMENT_REQ_IMP_LINE_TB.LINE_NO        IS 'ترتيب البند داخل الموظف (1, 2, 3...)';
COMMENT ON COLUMN GFC.PAYMENT_REQ_IMP_LINE_TB.EXCEL_ROW_NUM  IS 'رقم الصف في الإكسل الأصلي — للرجوع للملف';
COMMENT ON COLUMN GFC.PAYMENT_REQ_IMP_LINE_TB.AMOUNT         IS 'مبلغ هذا البند فقط (مش الإجمالي)';
COMMENT ON COLUMN GFC.PAYMENT_REQ_IMP_LINE_TB.ORIGINAL_NOTE  IS 'الملاحظة من العمود C في الإكسل';
COMMENT ON COLUMN GFC.PAYMENT_REQ_IMP_LINE_TB.ATTACHMENT_ID  IS 'يربط بسجل الـ attachment للملف الأصلي';

-- 3. Indexes
CREATE INDEX GFC.IDX_PRIL_DETAIL ON GFC.PAYMENT_REQ_IMP_LINE_TB(DETAIL_ID);
CREATE INDEX GFC.IDX_PRIL_ATT    ON GFC.PAYMENT_REQ_IMP_LINE_TB(ATTACHMENT_ID);

-- 4. Sequence
CREATE SEQUENCE GFC_PAK.PAYMENT_REQ_IMP_LINE_SEQ START WITH 1 INCREMENT BY 1 NOCACHE;

-- 5. Grants + Synonyms
GRANT SELECT, INSERT, UPDATE, DELETE ON GFC.PAYMENT_REQ_IMP_LINE_TB TO GFC_PAK;
GRANT SELECT                          ON GFC.PAYMENT_REQ_IMP_LINE_TB TO PUBLIC;
GRANT SELECT                          ON GFC_PAK.PAYMENT_REQ_IMP_LINE_SEQ TO PUBLIC;

CREATE OR REPLACE PUBLIC SYNONYM PAYMENT_REQ_IMP_LINE_TB  FOR GFC.PAYMENT_REQ_IMP_LINE_TB;
CREATE OR REPLACE PUBLIC SYNONYM PAYMENT_REQ_IMP_LINE_SEQ FOR GFC_PAK.PAYMENT_REQ_IMP_LINE_SEQ;

-- 6. تحقق
SELECT OBJECT_NAME, OBJECT_TYPE, STATUS
  FROM ALL_OBJECTS
 WHERE OBJECT_NAME IN ('PAYMENT_REQ_IMP_LINE_TB', 'PAYMENT_REQ_IMP_LINE_SEQ')
 ORDER BY OBJECT_TYPE;
