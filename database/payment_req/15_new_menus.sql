-- ============================================================
-- صلاحيات endpoints جديدة (Override Distribution + Redist + Emp Accounts JSON)
-- ============================================================
-- آمن للتشغيل أكثر من مرة (idempotent):
--   - INSERT يفحص NOT EXISTS قبل ما يضيف
--   - MERGE يتخطى السجلات الموجودة
-- ============================================================

-- ============================================================
-- الخطوة 1: إضافة الـ menus الجديدة لـ SYSTEM_MENU_TB
-- (نُسخ من menu موجود — نأخذ MENU_PARENT_NO و MAIN_MENU و SYSTEM_ID منها)
-- ============================================================

-- 1.1) detail_set_override — تعديل توزيع سطر صرف (قبل الاحتساب)
INSERT INTO GFC.SYSTEM_MENU_TB (MENU_NO, MENU_PARENT_NO, MENU_ADD, MENU_FULL_CODE, MAIN_MENU, VIEW_MENU, SORT, SYSTEM_ID)
SELECT SYSTEM_MENU_TB_SEQ.NEXTVAL, MENU_PARENT_NO, 'تعديل توزيع سطر صرف',
       'payment_req/payment_req/detail_set_override', MAIN_MENU, VIEW_MENU, NVL(SORT,0)+1, SYSTEM_ID
  FROM GFC.SYSTEM_MENU_TB
 WHERE MENU_FULL_CODE = 'payment_req/payment_req/approve'
   AND ROWNUM = 1
   AND NOT EXISTS (
     SELECT 1 FROM GFC.SYSTEM_MENU_TB
      WHERE MENU_FULL_CODE = 'payment_req/payment_req/detail_set_override'
   );

-- 1.2) batch_detail_redist_action — إعادة توزيع موظف في دفعة محتسبة
INSERT INTO GFC.SYSTEM_MENU_TB (MENU_NO, MENU_PARENT_NO, MENU_ADD, MENU_FULL_CODE, MAIN_MENU, VIEW_MENU, SORT, SYSTEM_ID)
SELECT SYSTEM_MENU_TB_SEQ.NEXTVAL, MENU_PARENT_NO, 'إعادة توزيع موظف في الدفعة',
       'payment_req/payment_req/batch_detail_redist_action', MAIN_MENU, VIEW_MENU, NVL(SORT,0)+1, SYSTEM_ID
  FROM GFC.SYSTEM_MENU_TB
 WHERE MENU_FULL_CODE = 'payment_req/payment_req/batch_refresh_split_action'
   AND ROWNUM = 1
   AND NOT EXISTS (
     SELECT 1 FROM GFC.SYSTEM_MENU_TB
      WHERE MENU_FULL_CODE = 'payment_req/payment_req/batch_detail_redist_action'
   );

-- 1.3) emp_accounts_json — جلب حسابات الموظف (قراءة فقط — للـ override modal)
INSERT INTO GFC.SYSTEM_MENU_TB (MENU_NO, MENU_PARENT_NO, MENU_ADD, MENU_FULL_CODE, MAIN_MENU, VIEW_MENU, SORT, SYSTEM_ID)
SELECT SYSTEM_MENU_TB_SEQ.NEXTVAL, MENU_PARENT_NO, 'حسابات الموظف (JSON)',
       'payment_req/payment_req/emp_accounts_json', MAIN_MENU, VIEW_MENU, NVL(SORT,0)+1, SYSTEM_ID
  FROM GFC.SYSTEM_MENU_TB
 WHERE MENU_FULL_CODE = 'payment_req/payment_req/approve'
   AND ROWNUM = 1
   AND NOT EXISTS (
     SELECT 1 FROM GFC.SYSTEM_MENU_TB
      WHERE MENU_FULL_CODE = 'payment_req/payment_req/emp_accounts_json'
   );

COMMIT;


-- ============================================================
-- الخطوة 2: منح الصلاحيات للمستخدمين الموجودين (MERGE — آمن)
-- ============================================================
-- المنطق:
--   - أي مستخدم عنده صلاحية approve  → يحصل على detail_set_override + emp_accounts_json
--   - أي مستخدم عنده batch_refresh    → يحصل على batch_detail_redist_action
-- ============================================================

-- 2.1) detail_set_override → لمن يملك approve
MERGE INTO GFC.USER_MENUS_TB T USING (
    SELECT DISTINCT UM.USER_NO,
           (SELECT MENU_NO FROM GFC.SYSTEM_MENU_TB
             WHERE MENU_FULL_CODE = 'payment_req/payment_req/detail_set_override' AND ROWNUM=1) AS NEW_MENU_NO
      FROM GFC.USER_MENUS_TB UM
      JOIN GFC.SYSTEM_MENU_TB SM ON SM.MENU_NO = UM.MENU_NO
     WHERE SM.MENU_FULL_CODE = 'payment_req/payment_req/approve'
) S ON (T.USER_NO = S.USER_NO AND T.MENU_NO = S.NEW_MENU_NO)
WHEN NOT MATCHED THEN INSERT (USER_NO, MENU_NO) VALUES (S.USER_NO, S.NEW_MENU_NO);

-- 2.2) batch_detail_redist_action → لمن يملك batch_refresh_split_action
MERGE INTO GFC.USER_MENUS_TB T USING (
    SELECT DISTINCT UM.USER_NO,
           (SELECT MENU_NO FROM GFC.SYSTEM_MENU_TB
             WHERE MENU_FULL_CODE = 'payment_req/payment_req/batch_detail_redist_action' AND ROWNUM=1) AS NEW_MENU_NO
      FROM GFC.USER_MENUS_TB UM
      JOIN GFC.SYSTEM_MENU_TB SM ON SM.MENU_NO = UM.MENU_NO
     WHERE SM.MENU_FULL_CODE = 'payment_req/payment_req/batch_refresh_split_action'
) S ON (T.USER_NO = S.USER_NO AND T.MENU_NO = S.NEW_MENU_NO)
WHEN NOT MATCHED THEN INSERT (USER_NO, MENU_NO) VALUES (S.USER_NO, S.NEW_MENU_NO);

-- 2.3) emp_accounts_json → لمن يملك approve
MERGE INTO GFC.USER_MENUS_TB T USING (
    SELECT DISTINCT UM.USER_NO,
           (SELECT MENU_NO FROM GFC.SYSTEM_MENU_TB
             WHERE MENU_FULL_CODE = 'payment_req/payment_req/emp_accounts_json' AND ROWNUM=1) AS NEW_MENU_NO
      FROM GFC.USER_MENUS_TB UM
      JOIN GFC.SYSTEM_MENU_TB SM ON SM.MENU_NO = UM.MENU_NO
     WHERE SM.MENU_FULL_CODE = 'payment_req/payment_req/approve'
) S ON (T.USER_NO = S.USER_NO AND T.MENU_NO = S.NEW_MENU_NO)
WHEN NOT MATCHED THEN INSERT (USER_NO, MENU_NO) VALUES (S.USER_NO, S.NEW_MENU_NO);

COMMIT;


-- ============================================================
-- الخطوة 3 (اختياري — للتحقق): اعرض النتيجة
-- ============================================================
SELECT MENU_NO, MENU_FULL_CODE, MENU_ADD
  FROM GFC.SYSTEM_MENU_TB
 WHERE MENU_FULL_CODE IN (
   'payment_req/payment_req/detail_set_override',
   'payment_req/payment_req/batch_detail_redist_action',
   'payment_req/payment_req/emp_accounts_json'
 );

-- كم مستخدم حصل على كل صلاحية؟
SELECT SM.MENU_FULL_CODE, COUNT(UM.USER_NO) AS USERS_GRANTED
  FROM GFC.SYSTEM_MENU_TB SM
  LEFT JOIN GFC.USER_MENUS_TB UM ON UM.MENU_NO = SM.MENU_NO
 WHERE SM.MENU_FULL_CODE IN (
   'payment_req/payment_req/detail_set_override',
   'payment_req/payment_req/batch_detail_redist_action',
   'payment_req/payment_req/emp_accounts_json'
 )
 GROUP BY SM.MENU_FULL_CODE;
