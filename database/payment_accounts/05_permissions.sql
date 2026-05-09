-- ============================================================
-- Payment Accounts Module — Permissions (Menu + ACL)
-- ============================================================
-- الأعمدة الصحيحة في SYSTEM_MENU_TB:
--   MENU_NO, MENU_PARENT_NO, MENU_ADD,
--   MENU_CODE, MENU_FULL_CODE  (يجب أن يتساويا)
--   MAIN_MENU, VIEW_MENU, SORT, ICON, ID_SYSTEM, RELATED_OBJECT
--
-- RELATED_OBJECT: صلاحيات الجداول "I_TB;U_TB;D_TB"
--   I_ = Insert, U_ = Update, D_ = Delete
-- ============================================================


-- ============================================================
-- ⚠️ idempotent — يتخطّى أي MENU موجود مسبقاً (يمنع التكرار)
-- ============================================================

-- ============================================================
-- الخطوة 1: إضافة الصفحة الرئيسية (لو غير موجودة)
-- ============================================================
INSERT INTO GFC.SYSTEM_MENU_TB (
    MENU_NO, MENU_PARENT_NO, MENU_ADD,
    MENU_CODE, MENU_FULL_CODE,
    MAIN_MENU, VIEW_MENU, SORT, ICON, ID_SYSTEM, RELATED_OBJECT
)
SELECT SYSTEM_MENU_TB_SEQ.NEXTVAL,
       MENU_PARENT_NO,
       'إدارة حسابات الصرف',
       'payment_accounts/payment_accounts',
       'payment_accounts/payment_accounts',
       MAIN_MENU, VIEW_MENU,
       NVL(SORT, 0) + 10,
       'fa fa-credit-card',
       ID_SYSTEM,
       'I_PAYMENT_ACCOUNTS_TB;U_PAYMENT_ACCOUNTS_TB;D_PAYMENT_ACCOUNTS_TB;'
       || 'I_PAYMENT_BENEFICIARIES_TB;U_PAYMENT_BENEFICIARIES_TB;D_PAYMENT_BENEFICIARIES_TB;'
       || 'I_PAYMENT_PROVIDERS_TB;U_PAYMENT_PROVIDERS_TB;'
       || 'I_PAYMENT_BANK_BRANCHES_TB;U_PAYMENT_BANK_BRANCHES_TB;'
       || 'I_PAYMENT_REQ_DETAIL_SPLIT_TB;U_PAYMENT_REQ_DETAIL_SPLIT_TB;D_PAYMENT_REQ_DETAIL_SPLIT_TB'
  FROM GFC.SYSTEM_MENU_TB
 WHERE MENU_FULL_CODE = 'payment_req/payment_req'
   AND NOT EXISTS (
       SELECT 1 FROM GFC.SYSTEM_MENU_TB
        WHERE MENU_FULL_CODE = 'payment_accounts/payment_accounts'
   );

COMMIT;


-- ============================================================
-- الخطوة 2: إضافة الـ AJAX endpoints (sub routes)
-- ============================================================
INSERT ALL
  INTO GFC.SYSTEM_MENU_TB (MENU_NO, MENU_PARENT_NO, MENU_ADD,
                           MENU_CODE, MENU_FULL_CODE,
                           MAIN_MENU, VIEW_MENU, SORT, ICON, ID_SYSTEM, RELATED_OBJECT)
  VALUES (SYSTEM_MENU_TB_SEQ.NEXTVAL, P_ID, 'قائمة الصفحة',
          'payment_accounts/payment_accounts/get_page',           'payment_accounts/payment_accounts/get_page',
          M, 0,  1, '', S, NULL)

  INTO GFC.SYSTEM_MENU_TB (MENU_NO, MENU_PARENT_NO, MENU_ADD,
                           MENU_CODE, MENU_FULL_CODE,
                           MAIN_MENU, VIEW_MENU, SORT, ICON, ID_SYSTEM, RELATED_OBJECT)
  VALUES (SYSTEM_MENU_TB_SEQ.NEXTVAL, P_ID, 'تفاصيل موظف',
          'payment_accounts/payment_accounts/emp',                'payment_accounts/payment_accounts/emp',
          M, 0,  2, '', S, NULL)

  INTO GFC.SYSTEM_MENU_TB (MENU_NO, MENU_PARENT_NO, MENU_ADD,
                           MENU_CODE, MENU_FULL_CODE,
                           MAIN_MENU, VIEW_MENU, SORT, ICON, ID_SYSTEM, RELATED_OBJECT)
  VALUES (SYSTEM_MENU_TB_SEQ.NEXTVAL, P_ID, 'حفظ حساب',
          'payment_accounts/payment_accounts/account_save',       'payment_accounts/payment_accounts/account_save',
          M, 0,  3, '', S, 'I_PAYMENT_ACCOUNTS_TB;U_PAYMENT_ACCOUNTS_TB')

  INTO GFC.SYSTEM_MENU_TB (MENU_NO, MENU_PARENT_NO, MENU_ADD,
                           MENU_CODE, MENU_FULL_CODE,
                           MAIN_MENU, VIEW_MENU, SORT, ICON, ID_SYSTEM, RELATED_OBJECT)
  VALUES (SYSTEM_MENU_TB_SEQ.NEXTVAL, P_ID, 'حذف حساب',
          'payment_accounts/payment_accounts/account_delete',     'payment_accounts/payment_accounts/account_delete',
          M, 0,  4, '', S, 'D_PAYMENT_ACCOUNTS_TB')

  INTO GFC.SYSTEM_MENU_TB (MENU_NO, MENU_PARENT_NO, MENU_ADD,
                           MENU_CODE, MENU_FULL_CODE,
                           MAIN_MENU, VIEW_MENU, SORT, ICON, ID_SYSTEM, RELATED_OBJECT)
  VALUES (SYSTEM_MENU_TB_SEQ.NEXTVAL, P_ID, 'إيقاف حساب',
          'payment_accounts/payment_accounts/account_deactivate', 'payment_accounts/payment_accounts/account_deactivate',
          M, 0,  5, '', S, 'U_PAYMENT_ACCOUNTS_TB')

  INTO GFC.SYSTEM_MENU_TB (MENU_NO, MENU_PARENT_NO, MENU_ADD,
                           MENU_CODE, MENU_FULL_CODE,
                           MAIN_MENU, VIEW_MENU, SORT, ICON, ID_SYSTEM, RELATED_OBJECT)
  VALUES (SYSTEM_MENU_TB_SEQ.NEXTVAL, P_ID, 'إعادة تفعيل',
          'payment_accounts/payment_accounts/account_reactivate', 'payment_accounts/payment_accounts/account_reactivate',
          M, 0,  6, '', S, 'U_PAYMENT_ACCOUNTS_TB')

  INTO GFC.SYSTEM_MENU_TB (MENU_NO, MENU_PARENT_NO, MENU_ADD,
                           MENU_CODE, MENU_FULL_CODE,
                           MAIN_MENU, VIEW_MENU, SORT, ICON, ID_SYSTEM, RELATED_OBJECT)
  VALUES (SYSTEM_MENU_TB_SEQ.NEXTVAL, P_ID, 'تعيين افتراضي',
          'payment_accounts/payment_accounts/account_set_default','payment_accounts/payment_accounts/account_set_default',
          M, 0,  7, '', S, 'U_PAYMENT_ACCOUNTS_TB')

  INTO GFC.SYSTEM_MENU_TB (MENU_NO, MENU_PARENT_NO, MENU_ADD,
                           MENU_CODE, MENU_FULL_CODE,
                           MAIN_MENU, VIEW_MENU, SORT, ICON, ID_SYSTEM, RELATED_OBJECT)
  VALUES (SYSTEM_MENU_TB_SEQ.NEXTVAL, P_ID, 'حفظ مستفيد',
          'payment_accounts/payment_accounts/benef_save',         'payment_accounts/payment_accounts/benef_save',
          M, 0,  8, '', S, 'I_PAYMENT_BENEFICIARIES_TB;U_PAYMENT_BENEFICIARIES_TB')

  INTO GFC.SYSTEM_MENU_TB (MENU_NO, MENU_PARENT_NO, MENU_ADD,
                           MENU_CODE, MENU_FULL_CODE,
                           MAIN_MENU, VIEW_MENU, SORT, ICON, ID_SYSTEM, RELATED_OBJECT)
  VALUES (SYSTEM_MENU_TB_SEQ.NEXTVAL, P_ID, 'حذف مستفيد',
          'payment_accounts/payment_accounts/benef_delete',       'payment_accounts/payment_accounts/benef_delete',
          M, 0,  9, '', S, 'D_PAYMENT_BENEFICIARIES_TB')

  INTO GFC.SYSTEM_MENU_TB (MENU_NO, MENU_PARENT_NO, MENU_ADD,
                           MENU_CODE, MENU_FULL_CODE,
                           MAIN_MENU, VIEW_MENU, SORT, ICON, ID_SYSTEM, RELATED_OBJECT)
  VALUES (SYSTEM_MENU_TB_SEQ.NEXTVAL, P_ID, 'إدارة المزودين',
          'payment_accounts/payment_accounts/providers',          'payment_accounts/payment_accounts/providers',
          M, 0, 10, '', S, NULL)

  INTO GFC.SYSTEM_MENU_TB (MENU_NO, MENU_PARENT_NO, MENU_ADD,
                           MENU_CODE, MENU_FULL_CODE,
                           MAIN_MENU, VIEW_MENU, SORT, ICON, ID_SYSTEM, RELATED_OBJECT)
  VALUES (SYSTEM_MENU_TB_SEQ.NEXTVAL, P_ID, 'حفظ مزود',
          'payment_accounts/payment_accounts/provider_save',      'payment_accounts/payment_accounts/provider_save',
          M, 0, 11, '', S, 'I_PAYMENT_PROVIDERS_TB;U_PAYMENT_PROVIDERS_TB')

  INTO GFC.SYSTEM_MENU_TB (MENU_NO, MENU_PARENT_NO, MENU_ADD,
                           MENU_CODE, MENU_FULL_CODE,
                           MAIN_MENU, VIEW_MENU, SORT, ICON, ID_SYSTEM, RELATED_OBJECT)
  VALUES (SYSTEM_MENU_TB_SEQ.NEXTVAL, P_ID, 'إدارة الفروع',
          'payment_accounts/payment_accounts/branches',           'payment_accounts/payment_accounts/branches',
          M, 0, 12, '', S, NULL)

  INTO GFC.SYSTEM_MENU_TB (MENU_NO, MENU_PARENT_NO, MENU_ADD,
                           MENU_CODE, MENU_FULL_CODE,
                           MAIN_MENU, VIEW_MENU, SORT, ICON, ID_SYSTEM, RELATED_OBJECT)
  VALUES (SYSTEM_MENU_TB_SEQ.NEXTVAL, P_ID, 'حفظ فرع',
          'payment_accounts/payment_accounts/branch_save',        'payment_accounts/payment_accounts/branch_save',
          M, 0, 13, '', S, 'I_PAYMENT_BANK_BRANCHES_TB;U_PAYMENT_BANK_BRANCHES_TB')

  INTO GFC.SYSTEM_MENU_TB (MENU_NO, MENU_PARENT_NO, MENU_ADD,
                           MENU_CODE, MENU_FULL_CODE,
                           MAIN_MENU, VIEW_MENU, SORT, ICON, ID_SYSTEM, RELATED_OBJECT)
  VALUES (SYSTEM_MENU_TB_SEQ.NEXTVAL, P_ID, 'ربط فرع',
          'payment_accounts/payment_accounts/branch_link_provider','payment_accounts/payment_accounts/branch_link_provider',
          M, 0, 14, '', S, 'U_PAYMENT_BANK_BRANCHES_TB')

  INTO GFC.SYSTEM_MENU_TB (MENU_NO, MENU_PARENT_NO, MENU_ADD,
                           MENU_CODE, MENU_FULL_CODE,
                           MAIN_MENU, VIEW_MENU, SORT, ICON, ID_SYSTEM, RELATED_OBJECT)
  VALUES (SYSTEM_MENU_TB_SEQ.NEXTVAL, P_ID, 'توزيع تلقائي',
          'payment_accounts/payment_accounts/split_auto_fill',    'payment_accounts/payment_accounts/split_auto_fill',
          M, 0, 15, '', S, 'I_PAYMENT_REQ_DETAIL_SPLIT_TB')

  INTO GFC.SYSTEM_MENU_TB (MENU_NO, MENU_PARENT_NO, MENU_ADD,
                           MENU_CODE, MENU_FULL_CODE,
                           MAIN_MENU, VIEW_MENU, SORT, ICON, ID_SYSTEM, RELATED_OBJECT)
  VALUES (SYSTEM_MENU_TB_SEQ.NEXTVAL, P_ID, 'تعديل توزيع',
          'payment_accounts/payment_accounts/split_update_manual','payment_accounts/payment_accounts/split_update_manual',
          M, 0, 16, '', S, 'U_PAYMENT_REQ_DETAIL_SPLIT_TB')

  INTO GFC.SYSTEM_MENU_TB (MENU_NO, MENU_PARENT_NO, MENU_ADD,
                           MENU_CODE, MENU_FULL_CODE,
                           MAIN_MENU, VIEW_MENU, SORT, ICON, ID_SYSTEM, RELATED_OBJECT)
  VALUES (SYSTEM_MENU_TB_SEQ.NEXTVAL, P_ID, 'إرجاع للتلقائي',
          'payment_accounts/payment_accounts/split_reset_auto',   'payment_accounts/payment_accounts/split_reset_auto',
          M, 0, 17, '', S, 'U_PAYMENT_REQ_DETAIL_SPLIT_TB')

  -- ─── الشاشات الجديدة: لوحة تحكم + فحص صحة + استيراد + ربط تلقائي ───
  INTO GFC.SYSTEM_MENU_TB (MENU_NO, MENU_PARENT_NO, MENU_ADD,
                           MENU_CODE, MENU_FULL_CODE,
                           MAIN_MENU, VIEW_MENU, SORT, ICON, ID_SYSTEM, RELATED_OBJECT)
  VALUES (SYSTEM_MENU_TB_SEQ.NEXTVAL, P_ID, 'لوحة التحكم',
          'payment_accounts/payment_accounts/dashboard',          'payment_accounts/payment_accounts/dashboard',
          M, 1, 18, 'fa-tachometer', S, NULL)

  INTO GFC.SYSTEM_MENU_TB (MENU_NO, MENU_PARENT_NO, MENU_ADD,
                           MENU_CODE, MENU_FULL_CODE,
                           MAIN_MENU, VIEW_MENU, SORT, ICON, ID_SYSTEM, RELATED_OBJECT)
  VALUES (SYSTEM_MENU_TB_SEQ.NEXTVAL, P_ID, 'فحص الصحة',
          'payment_accounts/payment_accounts/health_check',       'payment_accounts/payment_accounts/health_check',
          M, 1, 19, 'fa-heartbeat', S, NULL)

  INTO GFC.SYSTEM_MENU_TB (MENU_NO, MENU_PARENT_NO, MENU_ADD,
                           MENU_CODE, MENU_FULL_CODE,
                           MAIN_MENU, VIEW_MENU, SORT, ICON, ID_SYSTEM, RELATED_OBJECT)
  VALUES (SYSTEM_MENU_TB_SEQ.NEXTVAL, P_ID, 'فحص الصحة (overview)',
          'payment_accounts/payment_accounts/health_overview_json','payment_accounts/payment_accounts/health_overview_json',
          M, 0, 20, '', S, NULL)

  INTO GFC.SYSTEM_MENU_TB (MENU_NO, MENU_PARENT_NO, MENU_ADD,
                           MENU_CODE, MENU_FULL_CODE,
                           MAIN_MENU, VIEW_MENU, SORT, ICON, ID_SYSTEM, RELATED_OBJECT)
  VALUES (SYSTEM_MENU_TB_SEQ.NEXTVAL, P_ID, 'فحص الصحة (list)',
          'payment_accounts/payment_accounts/health_list_json',   'payment_accounts/payment_accounts/health_list_json',
          M, 0, 21, '', S, NULL)

  INTO GFC.SYSTEM_MENU_TB (MENU_NO, MENU_PARENT_NO, MENU_ADD,
                           MENU_CODE, MENU_FULL_CODE,
                           MAIN_MENU, VIEW_MENU, SORT, ICON, ID_SYSTEM, RELATED_OBJECT)
  VALUES (SYSTEM_MENU_TB_SEQ.NEXTVAL, P_ID, 'استيراد حسابات Excel',
          'payment_accounts/payment_accounts/bulk_import',        'payment_accounts/payment_accounts/bulk_import',
          M, 1, 22, 'fa-upload', S, 'I_PAYMENT_ACCOUNTS_TB')

  INTO GFC.SYSTEM_MENU_TB (MENU_NO, MENU_PARENT_NO, MENU_ADD,
                           MENU_CODE, MENU_FULL_CODE,
                           MAIN_MENU, VIEW_MENU, SORT, ICON, ID_SYSTEM, RELATED_OBJECT)
  VALUES (SYSTEM_MENU_TB_SEQ.NEXTVAL, P_ID, 'تحميل قالب الاستيراد',
          'payment_accounts/payment_accounts/bulk_import_template','payment_accounts/payment_accounts/bulk_import_template',
          M, 0, 23, '', S, NULL)

  INTO GFC.SYSTEM_MENU_TB (MENU_NO, MENU_PARENT_NO, MENU_ADD,
                           MENU_CODE, MENU_FULL_CODE,
                           MAIN_MENU, VIEW_MENU, SORT, ICON, ID_SYSTEM, RELATED_OBJECT)
  VALUES (SYSTEM_MENU_TB_SEQ.NEXTVAL, P_ID, 'معاينة الاستيراد',
          'payment_accounts/payment_accounts/bulk_import_preview','payment_accounts/payment_accounts/bulk_import_preview',
          M, 0, 24, '', S, NULL)

  INTO GFC.SYSTEM_MENU_TB (MENU_NO, MENU_PARENT_NO, MENU_ADD,
                           MENU_CODE, MENU_FULL_CODE,
                           MAIN_MENU, VIEW_MENU, SORT, ICON, ID_SYSTEM, RELATED_OBJECT)
  VALUES (SYSTEM_MENU_TB_SEQ.NEXTVAL, P_ID, 'تنفيذ الاستيراد',
          'payment_accounts/payment_accounts/bulk_import_execute','payment_accounts/payment_accounts/bulk_import_execute',
          M, 0, 25, '', S, 'I_PAYMENT_ACCOUNTS_TB')

  INTO GFC.SYSTEM_MENU_TB (MENU_NO, MENU_PARENT_NO, MENU_ADD,
                           MENU_CODE, MENU_FULL_CODE,
                           MAIN_MENU, VIEW_MENU, SORT, ICON, ID_SYSTEM, RELATED_OBJECT)
  VALUES (SYSTEM_MENU_TB_SEQ.NEXTVAL, P_ID, 'ربط حسابات تلقائي (موظف)',
          'payment_accounts/payment_accounts/account_link_auto',  'payment_accounts/payment_accounts/account_link_auto',
          M, 0, 26, '', S, 'U_PAYMENT_ACCOUNTS_TB')

  INTO GFC.SYSTEM_MENU_TB (MENU_NO, MENU_PARENT_NO, MENU_ADD,
                           MENU_CODE, MENU_FULL_CODE,
                           MAIN_MENU, VIEW_MENU, SORT, ICON, ID_SYSTEM, RELATED_OBJECT)
  VALUES (SYSTEM_MENU_TB_SEQ.NEXTVAL, P_ID, 'ربط حسابات تلقائي (الكل)',
          'payment_accounts/payment_accounts/accounts_link_bulk_auto','payment_accounts/payment_accounts/accounts_link_bulk_auto',
          M, 0, 27, '', S, 'U_PAYMENT_ACCOUNTS_TB')

SELECT MENU_NO AS P_ID, MAIN_MENU AS M, ID_SYSTEM AS S
  FROM GFC.SYSTEM_MENU_TB
 WHERE MENU_FULL_CODE = 'payment_accounts/payment_accounts';

COMMIT;


-- ============================================================
-- الخطوة 3: منح الصلاحية للمستخدمين (من عنده payment_req)
-- ============================================================
MERGE INTO GFC.USER_MENUS_TB T
USING (
  SELECT DISTINCT UM.USER_NO, SM_NEW.MENU_NO AS NEW_MENU_NO
    FROM GFC.USER_MENUS_TB UM
    JOIN GFC.SYSTEM_MENU_TB SM_OLD ON SM_OLD.MENU_NO = UM.MENU_NO
    CROSS JOIN GFC.SYSTEM_MENU_TB SM_NEW
   WHERE SM_OLD.MENU_FULL_CODE = 'payment_req/payment_req'
     AND SM_NEW.MENU_FULL_CODE LIKE 'payment_accounts/payment_accounts%'
) S
ON (T.USER_NO = S.USER_NO AND T.MENU_NO = S.NEW_MENU_NO)
WHEN NOT MATCHED THEN
  INSERT (USER_NO, MENU_NO) VALUES (S.USER_NO, S.NEW_MENU_NO);

COMMIT;


-- ============================================================
-- تحقق
-- ============================================================
PROMPT === الصلاحيات الجديدة ===
SELECT MENU_NO, MENU_ADD, MENU_CODE, MENU_FULL_CODE
  FROM GFC.SYSTEM_MENU_TB
 WHERE MENU_FULL_CODE LIKE 'payment_accounts%'
 ORDER BY SORT, MENU_NO;

PROMPT === عدد المستخدمين الذين حصلوا على الصلاحية ===
SELECT COUNT(DISTINCT UM.USER_NO) AS USERS_COUNT
  FROM GFC.USER_MENUS_TB UM
  JOIN GFC.SYSTEM_MENU_TB SM ON SM.MENU_NO = UM.MENU_NO
 WHERE SM.MENU_FULL_CODE = 'payment_accounts/payment_accounts';


-- ============================================================
-- للتراجع
-- ============================================================
-- DELETE FROM GFC.USER_MENUS_TB
--  WHERE MENU_NO IN (SELECT MENU_NO FROM GFC.SYSTEM_MENU_TB
--                     WHERE MENU_FULL_CODE LIKE 'payment_accounts%');
-- DELETE FROM GFC.SYSTEM_MENU_TB
--  WHERE MENU_FULL_CODE LIKE 'payment_accounts%';
-- COMMIT;
