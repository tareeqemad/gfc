-- ============================================================
-- Payment Req — صلاحية endpoint جديد:
--   batch_compute_preview_json — التوزيع التفصيلي عند الاحتساب
-- ============================================================
-- شغّل بعد ما تحدّث 04_batch_pkg_spec.sql + 04_batch_pkg_body.sql
-- (BATCH_COMPUTE_PREVIEW procedure مضافة لـ DISBURSEMENT_BATCH_PKG)
-- ============================================================

-- إضافة آمنة (تتخطى الموجود)
DECLARE
  V_PARENT NUMBER;
  V_MAIN   NUMBER;
  V_SYS    NUMBER;
  V_EX     NUMBER;
BEGIN
  SELECT MENU_NO, MAIN_MENU, ID_SYSTEM
    INTO V_PARENT, V_MAIN, V_SYS
    FROM GFC.SYSTEM_MENU_TB
   WHERE MENU_FULL_CODE = 'payment_req/payment_req'
     AND ROWNUM = 1;

  SELECT COUNT(*) INTO V_EX FROM GFC.SYSTEM_MENU_TB
   WHERE MENU_FULL_CODE = 'payment_req/payment_req/batch_compute_preview_json';

  IF V_EX = 0 THEN
    INSERT INTO GFC.SYSTEM_MENU_TB (
        MENU_NO, MENU_PARENT_NO, MENU_ADD,
        MENU_CODE, MENU_FULL_CODE,
        MAIN_MENU, VIEW_MENU, SORT, ICON, ID_SYSTEM, RELATED_OBJECT
    ) VALUES (
        SYSTEM_MENU_TB_SEQ.NEXTVAL, V_PARENT, 'معاينة التوزيع التفصيلي',
        'payment_req/payment_req/batch_compute_preview_json',
        'payment_req/payment_req/batch_compute_preview_json',
        V_MAIN, 0, 93, '', V_SYS, NULL
    );
    DBMS_OUTPUT.PUT_LINE('✅ batch_compute_preview_json — أُضيف');
  ELSE
    DBMS_OUTPUT.PUT_LINE('⏭  batch_compute_preview_json — موجود مسبقاً');
  END IF;

  COMMIT;
END;
/

-- منح الصلاحية لكل من عنده payment_req/batch
MERGE INTO GFC.USER_MENUS_TB T
USING (
  SELECT DISTINCT UM.USER_NO, SM_NEW.MENU_NO AS NEW_MENU_NO
    FROM GFC.USER_MENUS_TB UM
    JOIN GFC.SYSTEM_MENU_TB SM_OLD ON SM_OLD.MENU_NO = UM.MENU_NO
    CROSS JOIN GFC.SYSTEM_MENU_TB SM_NEW
   WHERE SM_OLD.MENU_FULL_CODE = 'payment_req/payment_req/batch'
     AND SM_NEW.MENU_FULL_CODE = 'payment_req/payment_req/batch_compute_preview_json'
) S
ON (T.USER_NO = S.USER_NO AND T.MENU_NO = S.NEW_MENU_NO)
WHEN NOT MATCHED THEN INSERT (USER_NO, MENU_NO) VALUES (S.USER_NO, S.NEW_MENU_NO);

COMMIT;
