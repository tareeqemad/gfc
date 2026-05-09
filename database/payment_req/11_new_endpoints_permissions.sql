-- ============================================================
-- Payment Req — صلاحيات الـ endpoints الجديدة
-- (batch_emp_accounts_json, batch_preview_validation_json,
--  batch_refresh_split_action)
-- ============================================================
-- شغّله مرة واحدة بعد تحديث الـ pkg
-- ============================================================

INSERT ALL
  INTO GFC.SYSTEM_MENU_TB (MENU_NO, MENU_PARENT_NO, MENU_ADD,
                           MENU_CODE, MENU_FULL_CODE,
                           MAIN_MENU, VIEW_MENU, SORT, ICON, ID_SYSTEM, RELATED_OBJECT)
  VALUES (SYSTEM_MENU_TB_SEQ.NEXTVAL, P_ID, 'تفاصيل توزيع الموظف',
          'payment_req/payment_req/batch_emp_accounts_json',
          'payment_req/payment_req/batch_emp_accounts_json',
          M, 0, 90, '', S, NULL)

  INTO GFC.SYSTEM_MENU_TB (MENU_NO, MENU_PARENT_NO, MENU_ADD,
                           MENU_CODE, MENU_FULL_CODE,
                           MAIN_MENU, VIEW_MENU, SORT, ICON, ID_SYSTEM, RELATED_OBJECT)
  VALUES (SYSTEM_MENU_TB_SEQ.NEXTVAL, P_ID, 'معاينة الاحتساب',
          'payment_req/payment_req/batch_preview_validation_json',
          'payment_req/payment_req/batch_preview_validation_json',
          M, 0, 91, '', S, NULL)

  INTO GFC.SYSTEM_MENU_TB (MENU_NO, MENU_PARENT_NO, MENU_ADD,
                           MENU_CODE, MENU_FULL_CODE,
                           MAIN_MENU, VIEW_MENU, SORT, ICON, ID_SYSTEM, RELATED_OBJECT)
  VALUES (SYSTEM_MENU_TB_SEQ.NEXTVAL, P_ID, 'تحديث توزيع الدفعة',
          'payment_req/payment_req/batch_refresh_split_action',
          'payment_req/payment_req/batch_refresh_split_action',
          M, 0, 92, '', S, 'U_PAYMENT_BATCH_DETAIL_TB')

SELECT MENU_NO AS P_ID, MAIN_MENU AS M, ID_SYSTEM AS S
  FROM GFC.SYSTEM_MENU_TB
 WHERE MENU_FULL_CODE = 'payment_req/payment_req';

COMMIT;


-- منح الصلاحية لكل من عنده payment_req/batch
MERGE INTO GFC.USER_MENUS_TB T
USING (
  SELECT DISTINCT UM.USER_NO, SM_NEW.MENU_NO AS NEW_MENU_NO
    FROM GFC.USER_MENUS_TB UM
    JOIN GFC.SYSTEM_MENU_TB SM_OLD ON SM_OLD.MENU_NO = UM.MENU_NO
    CROSS JOIN GFC.SYSTEM_MENU_TB SM_NEW
   WHERE SM_OLD.MENU_FULL_CODE = 'payment_req/payment_req/batch'
     AND SM_NEW.MENU_FULL_CODE IN (
        'payment_req/payment_req/batch_emp_accounts_json',
        'payment_req/payment_req/batch_preview_validation_json',
        'payment_req/payment_req/batch_refresh_split_action'
     )
) S
ON (T.USER_NO = S.USER_NO AND T.MENU_NO = S.NEW_MENU_NO)
WHEN NOT MATCHED THEN INSERT (USER_NO, MENU_NO) VALUES (S.USER_NO, S.NEW_MENU_NO);

COMMIT;
