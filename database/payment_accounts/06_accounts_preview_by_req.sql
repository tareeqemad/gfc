-- ============================================================
-- Migration: ACCOUNTS_PREVIEW_BY_REQ
-- ============================================================
-- معاينة inline للتوزيع المتوقّع على حسابات الموظفين في طلب صرف
-- يُعرض في شاشة "تفاصيل الطلب" تحت كل صف موظف مباشرة
-- (بدون أزرار — الكروت تظهر تلقائياً)
-- ============================================================
-- خطوات التطبيق:
-- 1) أعد تشغيل 03_pkg_spec.sql (يحتوي على الـ signature الجديد)
-- 2) أعد تشغيل 04_pkg_body.sql (يحتوي على implementation)
-- 3) لا حاجة لـ permissions جديدة — استدعاء داخلي من الـ controller
-- ============================================================

-- اختبار سريع للـ procedure (غيّر REQ_ID لرقم طلب موجود):
DECLARE
  V_CUR SYS_REFCURSOR;
  V_MSG VARCHAR2(500);
  V_EMP NUMBER; V_REQ NUMBER; V_CNT NUMBER;
  V_ACC NUMBER; V_TYPE NUMBER; V_VAL NUMBER; V_ORD NUMBER; V_DEF NUMBER;
  V_IBAN VARCHAR2(50); V_ACC_NO VARCHAR2(100); V_WLT VARCHAR2(50);
  V_OWNER VARCHAR2(200); V_ID VARCHAR2(50);
  V_BENF NUMBER; V_BENF_N VARCHAR2(200); V_BENF_T NUMBER; V_BENF_R VARCHAR2(50);
  V_PRV NUMBER; V_PRV_N VARCHAR2(200); V_PRV_T NUMBER; V_BR VARCHAR2(200);
  V_ALLOC NUMBER; V_SORT NUMBER;
  V_TOTAL NUMBER := 0; V_ROWS NUMBER := 0;
BEGIN
  GFC_PAK.PAYMENT_ACCOUNTS_PKG.ACCOUNTS_PREVIEW_BY_REQ(1, V_CUR, V_MSG);
  IF V_MSG <> '1' THEN
    DBMS_OUTPUT.PUT_LINE('FAILED: ' || V_MSG);
    RETURN;
  END IF;
  LOOP
    FETCH V_CUR INTO V_EMP, V_REQ, V_CNT, V_ACC, V_TYPE, V_VAL, V_ORD, V_DEF,
                     V_IBAN, V_ACC_NO, V_WLT, V_OWNER, V_ID,
                     V_BENF, V_BENF_N, V_BENF_T, V_BENF_R,
                     V_PRV, V_PRV_N, V_PRV_T, V_BR, V_ALLOC, V_SORT;
    EXIT WHEN V_CUR%NOTFOUND;
    V_TOTAL := V_TOTAL + V_ALLOC;
    V_ROWS  := V_ROWS + 1;
  END LOOP;
  CLOSE V_CUR;
  DBMS_OUTPUT.PUT_LINE('PREVIEW: ' || V_ROWS || ' rows, total alloc=' || V_TOTAL);
END;
/
