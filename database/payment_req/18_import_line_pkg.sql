-- ============================================================
-- Procedures لإدارة بنود الاستيراد الأصلية
-- ============================================================
-- يُضاف على باكج DISBURSEMENT_PKG. التعريفات هنا standalone مع
-- إعادة كمبايل مستقلة لتسهيل النشر دون لمس الـ pkg الكبير.
-- ============================================================

-- إضافة بند جديد لتفصيلة موظف
-- نستخدم الـ synonym العام (PAYMENT_REQ_IMP_LINE_TB) بدل GFC.PAYMENT_REQ_IMP_LINE_TB
-- لتفادي مشاكل الصلاحيات عبر roles في DEFINER procedures
CREATE OR REPLACE PROCEDURE GFC_PAK.PAYMENT_REQ_IMP_LINE_ADD (
    P_DETAIL_ID       NUMBER,
    P_LINE_NO         NUMBER,
    P_EXCEL_ROW_NUM   NUMBER,
    P_AMOUNT          NUMBER,
    P_ORIGINAL_NOTE   VARCHAR2,
    P_ATTACHMENT_ID   NUMBER,
    P_ENTRY_USER      NUMBER,
    P_NEW_ID_OUT  OUT NUMBER,
    P_MSG_OUT     OUT VARCHAR2
) AS
BEGIN
    -- استخدم sequence مباشرة (Oracle 11g R2+) — أبسط وأسرع من SELECT FROM DUAL
    P_NEW_ID_OUT := GFC_PAK.PAYMENT_REQ_IMP_LINE_SEQ.NEXTVAL;

    INSERT INTO GFC.PAYMENT_REQ_IMP_LINE_TB (
        IMP_LINE_ID, DETAIL_ID, LINE_NO, EXCEL_ROW_NUM,
        AMOUNT, ORIGINAL_NOTE, ATTACHMENT_ID, IMPORT_DATE, ENTRY_USER
    ) VALUES (
        P_NEW_ID_OUT, P_DETAIL_ID, P_LINE_NO, P_EXCEL_ROW_NUM,
        P_AMOUNT, P_ORIGINAL_NOTE, P_ATTACHMENT_ID, SYSDATE, P_ENTRY_USER
    );

    P_MSG_OUT := '1';
EXCEPTION WHEN OTHERS THEN
    P_NEW_ID_OUT := 0;
    P_MSG_OUT    := SQLERRM;
END;
/
GRANT EXECUTE ON GFC_PAK.PAYMENT_REQ_IMP_LINE_ADD TO PUBLIC;
CREATE OR REPLACE PUBLIC SYNONYM PAYMENT_REQ_IMP_LINE_ADD FOR GFC_PAK.PAYMENT_REQ_IMP_LINE_ADD;


-- ============================================================
-- 🆕 Procedure تحلّ DETAIL_ID من (REQ_ID + EMP_NO) داخلياً ثم تُدخل البند.
-- يستخدمها الـ wizard لما يحفظ السطور بعد إنشاء الطلب.
-- ============================================================
CREATE OR REPLACE PROCEDURE GFC_PAK.PAYMENT_REQ_IMP_LN_ADD_BY_EMP (
    P_REQ_ID          NUMBER,
    P_EMP_NO          NUMBER,
    P_LINE_NO         NUMBER,
    P_EXCEL_ROW_NUM   NUMBER,
    P_AMOUNT          NUMBER,
    P_ORIGINAL_NOTE   VARCHAR2,
    P_ATTACHMENT_ID   NUMBER,
    P_ENTRY_USER      NUMBER,
    P_NEW_ID_OUT  OUT NUMBER,
    P_MSG_OUT     OUT VARCHAR2
) AS
    V_DETAIL_ID NUMBER;
BEGIN
    -- نلاقي DETAIL_ID للموظف في هذا الطلب
    BEGIN
        SELECT DETAIL_ID INTO V_DETAIL_ID
          FROM GFC.PAYMENT_REQ_DETAIL_TB
         WHERE REQ_ID = P_REQ_ID AND EMP_NO = P_EMP_NO AND ROWNUM = 1;
    EXCEPTION WHEN NO_DATA_FOUND THEN
        P_NEW_ID_OUT := 0;
        P_MSG_OUT    := 'الموظف ' || P_EMP_NO || ' غير موجود في الطلب ' || P_REQ_ID;
        RETURN;
    END;

    P_NEW_ID_OUT := GFC_PAK.PAYMENT_REQ_IMP_LINE_SEQ.NEXTVAL;

    INSERT INTO GFC.PAYMENT_REQ_IMP_LINE_TB (
        IMP_LINE_ID, DETAIL_ID, LINE_NO, EXCEL_ROW_NUM,
        AMOUNT, ORIGINAL_NOTE, ATTACHMENT_ID, IMPORT_DATE, ENTRY_USER
    ) VALUES (
        P_NEW_ID_OUT, V_DETAIL_ID, P_LINE_NO, P_EXCEL_ROW_NUM,
        P_AMOUNT, P_ORIGINAL_NOTE, P_ATTACHMENT_ID, SYSDATE, P_ENTRY_USER
    );

    P_MSG_OUT := '1';
EXCEPTION WHEN OTHERS THEN
    P_NEW_ID_OUT := 0;
    P_MSG_OUT    := SQLERRM;
END;
/
GRANT EXECUTE ON GFC_PAK.PAYMENT_REQ_IMP_LN_ADD_BY_EMP TO PUBLIC;
CREATE OR REPLACE PUBLIC SYNONYM PAYMENT_REQ_IMP_LN_ADD_BY_EMP FOR GFC_PAK.PAYMENT_REQ_IMP_LN_ADD_BY_EMP;


-- استرجاع بنود تفصيلة موظف معين (مفلترة بـ DETAIL_ID)
-- ملاحظة: ATTACHMENT_ID = ID في GFC_ATTACHMENT_TB
-- الـ PHP يجلب اسم الملف/المسار من attachment_model (PHP له صلاحيات كاملة)
CREATE OR REPLACE PROCEDURE GFC_PAK.PAYMENT_REQ_IMP_LINES_GET (
    P_DETAIL_ID       NUMBER,
    P_REF_CUR_OUT OUT SYS_REFCURSOR,
    P_MSG_OUT     OUT VARCHAR2
) AS
BEGIN
    OPEN P_REF_CUR_OUT FOR
        SELECT IL.IMP_LINE_ID,
               IL.DETAIL_ID,
               IL.LINE_NO,
               IL.EXCEL_ROW_NUM,
               IL.AMOUNT,
               IL.ORIGINAL_NOTE,
               IL.ATTACHMENT_ID,
               IL.IMPORT_DATE
          FROM GFC.PAYMENT_REQ_IMP_LINE_TB IL
         WHERE IL.DETAIL_ID = P_DETAIL_ID
         ORDER BY IL.LINE_NO, IL.IMP_LINE_ID;

    P_MSG_OUT := '1';
EXCEPTION WHEN OTHERS THEN
    P_MSG_OUT := SQLERRM;
END;
/
GRANT EXECUTE ON GFC_PAK.PAYMENT_REQ_IMP_LINES_GET TO PUBLIC;
CREATE OR REPLACE PUBLIC SYNONYM PAYMENT_REQ_IMP_LINES_GET FOR GFC_PAK.PAYMENT_REQ_IMP_LINES_GET;


-- ============================================================
-- helper: عدد البنود الأصلية لكل DETAIL_ID في طلب
-- يُستخدم في DETAIL_LIST لإظهار "[📋 11 بند]" بجنب الموظف
-- ============================================================
CREATE OR REPLACE FUNCTION GFC_PAK.PAYMENT_REQ_IMP_LINES_COUNT (
    P_DETAIL_ID NUMBER
) RETURN NUMBER AS
    V_CNT NUMBER;
BEGIN
    SELECT COUNT(*) INTO V_CNT
      FROM GFC.PAYMENT_REQ_IMP_LINE_TB
     WHERE DETAIL_ID = P_DETAIL_ID;
    RETURN V_CNT;
EXCEPTION WHEN OTHERS THEN RETURN 0;
END;
/
GRANT EXECUTE ON GFC_PAK.PAYMENT_REQ_IMP_LINES_COUNT TO PUBLIC;
CREATE OR REPLACE PUBLIC SYNONYM PAYMENT_REQ_IMP_LINES_COUNT FOR GFC_PAK.PAYMENT_REQ_IMP_LINES_COUNT;


-- ============================================================
-- إعادة كمبايل (لو شي بقي INVALID)
-- ============================================================
ALTER PROCEDURE GFC_PAK.PAYMENT_REQ_IMP_LINE_ADD          COMPILE;
ALTER PROCEDURE GFC_PAK.PAYMENT_REQ_IMP_LN_ADD_BY_EMP   COMPILE;
ALTER PROCEDURE GFC_PAK.PAYMENT_REQ_IMP_LINES_GET         COMPILE;
ALTER FUNCTION  GFC_PAK.PAYMENT_REQ_IMP_LINES_COUNT       COMPILE;

-- ============================================================
-- تحقق
-- ============================================================
SELECT OBJECT_NAME, OBJECT_TYPE, STATUS
  FROM ALL_OBJECTS
 WHERE OBJECT_NAME IN (
   'PAYMENT_REQ_IMP_LINE_ADD',
   'PAYMENT_REQ_IMP_LINES_GET',
   'PAYMENT_REQ_IMP_LINES_COUNT'
 )
 ORDER BY OBJECT_NAME;

-- ============================================================
-- لو فيه INVALID، شغّل هذا للحصول على رسالة الخطأ بالضبط:
-- ============================================================
/*
SELECT NAME, TYPE, LINE, POSITION, TEXT
  FROM ALL_ERRORS
 WHERE OWNER = 'GFC_PAK'
   AND NAME IN ('PAYMENT_REQ_IMP_LINE_ADD',
                'PAYMENT_REQ_IMP_LINES_GET',
                'PAYMENT_REQ_IMP_LINES_COUNT')
 ORDER BY NAME, SEQUENCE;
*/
