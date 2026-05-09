-- ============================================================
-- إضافة آمنة (idempotent) للـ menus الجديدة في payment_accounts
-- ⚠️ يتخطّى الـ menus الموجودة مسبقاً → يمنع التكرار
-- شغّله بدلاً من تشغيل 05_permissions.sql مرة ثانية
-- ============================================================

DECLARE
  V_PARENT_ID NUMBER;
  V_MAIN_MENU NUMBER;
  V_ID_SYSTEM NUMBER;

  -- Helper: يضيف menu فقط إذا غير موجود
  PROCEDURE ADD_MENU_IF_MISSING(
      P_LABEL    VARCHAR2,
      P_FULLCODE VARCHAR2,
      P_VIEW     NUMBER DEFAULT 0,
      P_SORT     NUMBER DEFAULT 99,
      P_ICON     VARCHAR2 DEFAULT '',
      P_RELATED  VARCHAR2 DEFAULT NULL
  ) IS
    V_EX NUMBER;
  BEGIN
    SELECT COUNT(*) INTO V_EX FROM GFC.SYSTEM_MENU_TB WHERE MENU_FULL_CODE = P_FULLCODE;
    IF V_EX > 0 THEN
      DBMS_OUTPUT.PUT_LINE('⏭  ' || P_FULLCODE || ' — موجود مسبقاً');
      RETURN;
    END IF;

    INSERT INTO GFC.SYSTEM_MENU_TB (
        MENU_NO, MENU_PARENT_NO, MENU_ADD,
        MENU_CODE, MENU_FULL_CODE,
        MAIN_MENU, VIEW_MENU, SORT, ICON, ID_SYSTEM, RELATED_OBJECT
    ) VALUES (
        SYSTEM_MENU_TB_SEQ.NEXTVAL, V_PARENT_ID, P_LABEL,
        P_FULLCODE, P_FULLCODE,
        V_MAIN_MENU, P_VIEW, P_SORT, P_ICON, V_ID_SYSTEM, P_RELATED
    );
    DBMS_OUTPUT.PUT_LINE('✅ ' || P_FULLCODE || ' — أُضيف');
  END;

BEGIN
  -- جلب parent (payment_accounts/payment_accounts)
  SELECT MENU_NO, MAIN_MENU, ID_SYSTEM
    INTO V_PARENT_ID, V_MAIN_MENU, V_ID_SYSTEM
    FROM GFC.SYSTEM_MENU_TB
   WHERE MENU_FULL_CODE = 'payment_accounts/payment_accounts'
     AND ROWNUM = 1;

  -- الشاشات الجديدة
  ADD_MENU_IF_MISSING('لوحة التحكم',                  'payment_accounts/payment_accounts/dashboard',                1, 18, 'fa-tachometer');
  ADD_MENU_IF_MISSING('فحص الصحة',                    'payment_accounts/payment_accounts/health_check',             1, 19, 'fa-heartbeat');
  ADD_MENU_IF_MISSING('فحص الصحة (overview)',         'payment_accounts/payment_accounts/health_overview_json',     0, 20);
  ADD_MENU_IF_MISSING('فحص الصحة (list)',             'payment_accounts/payment_accounts/health_list_json',         0, 21);
  ADD_MENU_IF_MISSING('استيراد حسابات Excel',         'payment_accounts/payment_accounts/bulk_import',              1, 22, 'fa-upload', 'I_PAYMENT_ACCOUNTS_TB');
  ADD_MENU_IF_MISSING('تحميل قالب الاستيراد',          'payment_accounts/payment_accounts/bulk_import_template',     0, 23);
  ADD_MENU_IF_MISSING('معاينة الاستيراد',              'payment_accounts/payment_accounts/bulk_import_preview',      0, 24);
  ADD_MENU_IF_MISSING('تنفيذ الاستيراد',               'payment_accounts/payment_accounts/bulk_import_execute',      0, 25, '', 'I_PAYMENT_ACCOUNTS_TB');
  ADD_MENU_IF_MISSING('ربط حسابات تلقائي (موظف)',     'payment_accounts/payment_accounts/account_link_auto',        0, 26, '', 'U_PAYMENT_ACCOUNTS_TB');
  ADD_MENU_IF_MISSING('ربط حسابات تلقائي (الكل)',     'payment_accounts/payment_accounts/accounts_link_bulk_auto',  0, 27, '', 'U_PAYMENT_ACCOUNTS_TB');

  COMMIT;
END;
/

-- منح الصلاحيات الجديدة لمن عنده الـ parent
MERGE INTO GFC.USER_MENUS_TB T
USING (
  SELECT DISTINCT UM.USER_NO, NEW_MENU.MENU_NO AS NEW_MENU_NO
    FROM GFC.USER_MENUS_TB UM
    JOIN GFC.SYSTEM_MENU_TB SM ON SM.MENU_NO = UM.MENU_NO
    CROSS JOIN GFC.SYSTEM_MENU_TB NEW_MENU
   WHERE SM.MENU_FULL_CODE = 'payment_accounts/payment_accounts'
     AND NEW_MENU.MENU_FULL_CODE IN (
       'payment_accounts/payment_accounts/dashboard',
       'payment_accounts/payment_accounts/health_check',
       'payment_accounts/payment_accounts/health_overview_json',
       'payment_accounts/payment_accounts/health_list_json',
       'payment_accounts/payment_accounts/bulk_import',
       'payment_accounts/payment_accounts/bulk_import_template',
       'payment_accounts/payment_accounts/bulk_import_preview',
       'payment_accounts/payment_accounts/bulk_import_execute',
       'payment_accounts/payment_accounts/account_link_auto',
       'payment_accounts/payment_accounts/accounts_link_bulk_auto'
     )
) S
ON (T.USER_NO = S.USER_NO AND T.MENU_NO = S.NEW_MENU_NO)
WHEN NOT MATCHED THEN INSERT (USER_NO, MENU_NO) VALUES (S.USER_NO, S.NEW_MENU_NO);

COMMIT;

-- تحقق
SELECT MENU_FULL_CODE, COUNT(*) AS CNT
  FROM GFC.SYSTEM_MENU_TB
 WHERE MENU_FULL_CODE LIKE 'payment_accounts%'
 GROUP BY MENU_FULL_CODE
HAVING COUNT(*) > 1;
-- ✅ لازم يطلع 0 صفوف (no duplicates)
