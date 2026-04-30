CREATE OR REPLACE PACKAGE GFC_PAK.PAYMENT_ACCOUNTS_PKG AS
-- ============================================================
-- Payment Accounts Package — Specification
-- ============================================================
-- إدارة حسابات الصرف (بنكية + محافظ) + المستفيدين + التوزيع
-- التبعيات:
--   - GFC.PAYMENT_PROVIDERS_TB
--   - GFC.PAYMENT_BANK_BRANCHES_TB
--   - GFC.PAYMENT_BENEFICIARIES_TB
--   - GFC.PAYMENT_ACCOUNTS_TB
--   - GFC.PAYMENT_REQ_DETAIL_SPLIT_TB
--   - DATA.EMPLOYEES (قراءة فقط)
-- ============================================================

  -- ============================================================
  -- Constants
  -- ============================================================
  C_PROVIDER_TYPE_BANK    CONSTANT NUMBER := 1;
  C_PROVIDER_TYPE_WALLET  CONSTANT NUMBER := 2;

  C_SPLIT_PERCENT         CONSTANT NUMBER := 1;
  C_SPLIT_FIXED           CONSTANT NUMBER := 2;
  C_SPLIT_REMAINING       CONSTANT NUMBER := 3;

  C_SPLIT_SOURCE_AUTO     CONSTANT NUMBER := 0;
  C_SPLIT_SOURCE_MANUAL   CONSTANT NUMBER := 1;

  C_STATUS_ACTIVE         CONSTANT NUMBER := 1;
  C_STATUS_DELETED        CONSTANT NUMBER := 9;

  -- أسباب إيقاف الحساب
  C_INACTIVE_RETIRED      CONSTANT NUMBER := 1;   -- تقاعد
  C_INACTIVE_DECEASED     CONSTANT NUMBER := 2;   -- وفاة
  C_INACTIVE_TERMINATED   CONSTANT NUMBER := 3;   -- فصل
  C_INACTIVE_FROZEN       CONSTANT NUMBER := 4;   -- تجميد
  C_INACTIVE_TRANSFERRED  CONSTANT NUMBER := 5;   -- تحويل
  C_INACTIVE_OTHER        CONSTANT NUMBER := 9;   -- أخرى

  -- أنواع القرابة للمستفيدين
  C_REL_WIFE              CONSTANT NUMBER := 1;
  C_REL_SON               CONSTANT NUMBER := 2;
  C_REL_DAUGHTER          CONSTANT NUMBER := 3;
  C_REL_FATHER            CONSTANT NUMBER := 4;
  C_REL_MOTHER            CONSTANT NUMBER := 5;
  C_REL_OTHER             CONSTANT NUMBER := 9;


  -- ============================================================
  -- 1) Providers — المزودون (بنوك + محافظ)
  -- ============================================================
  PROCEDURE PROVIDERS_LIST (
      P_TYPE        NUMBER DEFAULT NULL,   -- NULL=الكل, 1=بنك, 2=محفظة
      P_REF_CUR_OUT OUT SYS_REFCURSOR,
      P_MSG_OUT     OUT VARCHAR2
  );

  PROCEDURE PROVIDER_GET (
      P_PROVIDER_ID NUMBER,
      P_REF_CUR_OUT OUT SYS_REFCURSOR,
      P_MSG_OUT     OUT VARCHAR2
  );

  PROCEDURE PROVIDER_INSERT (
      P_TYPE           NUMBER, P_NAME VARCHAR2, P_CODE VARCHAR2,
      P_COMPANY_ACC_NO VARCHAR2, P_COMPANY_ACC_ID VARCHAR2, P_COMPANY_IBAN VARCHAR2,
      P_EXPORT_FORMAT  NUMBER,  P_SORT_ORDER NUMBER,
      P_MSG_OUT        OUT VARCHAR2
  );

  PROCEDURE PROVIDER_UPDATE (
      P_PROVIDER_ID    NUMBER,
      P_NAME           VARCHAR2, P_CODE VARCHAR2,
      P_COMPANY_ACC_NO VARCHAR2, P_COMPANY_ACC_ID VARCHAR2, P_COMPANY_IBAN VARCHAR2,
      P_EXPORT_FORMAT  NUMBER,  P_SORT_ORDER NUMBER,
      P_IS_ACTIVE      NUMBER,
      P_MSG_OUT        OUT VARCHAR2
  );

  -- حذف نهائي للمزود (يفشل لو في حسابات/فروع نشطة)
  PROCEDURE PROVIDER_DELETE (
      P_PROVIDER_ID NUMBER,
      P_MSG_OUT     OUT VARCHAR2
  );

  -- تفعيل/إيقاف المزود
  PROCEDURE PROVIDER_TOGGLE_ACTIVE (
      P_PROVIDER_ID NUMBER,
      P_IS_ACTIVE   NUMBER,
      P_MSG_OUT     OUT VARCHAR2
  );

  -- قائمة الموظفين الذين عندهم حساب عند هذا المزود
  PROCEDURE PROVIDER_ACCOUNTS_LIST (
      P_PROVIDER_ID NUMBER,
      P_ONLY_ACTIVE NUMBER DEFAULT 1,
      P_REF_CUR_OUT OUT SYS_REFCURSOR,
      P_MSG_OUT     OUT VARCHAR2
  );

  -- عمليات جماعية على المزودين (تفعيل/إيقاف/حذف)
  PROCEDURE PROVIDERS_BULK_ACTION (
      P_IDS_CSV  VARCHAR2,    -- "100,101,102"
      P_ACTION   VARCHAR2,    -- 'ACTIVATE' | 'DEACTIVATE' | 'DELETE'
      P_OK_OUT   OUT NUMBER,
      P_FAIL_OUT OUT NUMBER,
      P_MSG_OUT  OUT VARCHAR2
  );


  -- قائمة المزودين بـ pagination + filters (للـ index الجديد)
  PROCEDURE PROVIDERS_LIST_PAGINATED (
      P_TYPE        NUMBER   DEFAULT NULL,   -- 1=بنك / 2=محفظة / NULL=الكل
      P_IS_ACTIVE   NUMBER   DEFAULT NULL,   -- 1=نشط / 0=موقوف / NULL=الكل
      P_SEARCH      VARCHAR2 DEFAULT NULL,   -- بحث في الاسم/الرمز
      P_OFFSET      NUMBER   DEFAULT 0,
      P_LIMIT       NUMBER   DEFAULT 50,
      P_REF_CUR_OUT OUT SYS_REFCURSOR,
      P_MSG_OUT     OUT VARCHAR2
  );

  -- عدد المزودين بنفس الفلاتر
  PROCEDURE PROVIDERS_COUNT (
      P_TYPE        NUMBER   DEFAULT NULL,
      P_IS_ACTIVE   NUMBER   DEFAULT NULL,
      P_SEARCH      VARCHAR2 DEFAULT NULL,
      P_CNT_OUT     OUT NUMBER,
      P_MSG_OUT     OUT VARCHAR2
  );

  -- إحصائيات المزودين (لبطاقات الواجهة)
  PROCEDURE PROVIDERS_TOTALS (
      P_TYPE        NUMBER   DEFAULT NULL,
      P_IS_ACTIVE   NUMBER   DEFAULT NULL,
      P_SEARCH      VARCHAR2 DEFAULT NULL,
      P_TOTAL_OUT     OUT NUMBER,
      P_BANKS_OUT     OUT NUMBER,
      P_WALLETS_OUT   OUT NUMBER,
      P_INACTIVE_OUT  OUT NUMBER,
      P_MSG_OUT       OUT VARCHAR2
  );


  -- ============================================================
  -- 2) Bank Branches — فروع البنوك
  -- ============================================================
  PROCEDURE BRANCHES_LIST (
      P_PROVIDER_ID NUMBER DEFAULT NULL,   -- NULL=كل الفروع
      P_REF_CUR_OUT OUT SYS_REFCURSOR,
      P_MSG_OUT     OUT VARCHAR2
  );

  PROCEDURE BRANCH_GET (
      P_BRANCH_ID   NUMBER,
      P_REF_CUR_OUT OUT SYS_REFCURSOR,
      P_MSG_OUT     OUT VARCHAR2
  );

  PROCEDURE BRANCH_INSERT (
      P_PROVIDER_ID NUMBER, P_NAME VARCHAR2, P_LEGACY_BANK_NO NUMBER, P_PRINT_NO NUMBER,
      P_ADDRESS VARCHAR2, P_PHONE1 VARCHAR2, P_PHONE2 VARCHAR2, P_FAX VARCHAR2,
      P_MSG_OUT OUT VARCHAR2
  );

  PROCEDURE BRANCH_UPDATE (
      P_BRANCH_ID   NUMBER,
      P_PROVIDER_ID NUMBER, P_NAME VARCHAR2, P_LEGACY_BANK_NO NUMBER, P_PRINT_NO NUMBER,
      P_ADDRESS VARCHAR2, P_PHONE1 VARCHAR2, P_PHONE2 VARCHAR2, P_FAX VARCHAR2,
      P_IS_ACTIVE   NUMBER,
      P_MSG_OUT     OUT VARCHAR2
  );

  -- ربط فرع غير مرتبط ببنك رئيسي
  PROCEDURE BRANCH_LINK_PROVIDER (
      P_BRANCH_ID   NUMBER, P_PROVIDER_ID NUMBER,
      P_MSG_OUT     OUT VARCHAR2
  );


  -- ============================================================
  -- 3) Beneficiaries — المستفيدون
  -- ============================================================
  PROCEDURE BENEF_LIST (
      P_EMP_NO      NUMBER,
      P_REF_CUR_OUT OUT SYS_REFCURSOR,
      P_MSG_OUT     OUT VARCHAR2
  );

  PROCEDURE BENEF_GET (
      P_BENEF_ID    NUMBER,
      P_REF_CUR_OUT OUT SYS_REFCURSOR,
      P_MSG_OUT     OUT VARCHAR2
  );

  PROCEDURE BENEF_INSERT (
      P_EMP_NO    NUMBER, P_REL_TYPE NUMBER,
      P_ID_NO     VARCHAR2, P_NAME VARCHAR2, P_PHONE VARCHAR2,
      P_PCT_SHARE NUMBER,
      P_FROM_DATE DATE,    P_TO_DATE DATE,
      P_NOTES     VARCHAR2,
      P_MSG_OUT   OUT VARCHAR2
  );

  PROCEDURE BENEF_UPDATE (
      P_BENEF_ID  NUMBER, P_REL_TYPE NUMBER,
      P_ID_NO     VARCHAR2, P_NAME VARCHAR2, P_PHONE VARCHAR2,
      P_PCT_SHARE NUMBER,
      P_FROM_DATE DATE,    P_TO_DATE DATE,
      P_STATUS    NUMBER,  P_NOTES VARCHAR2,
      P_MSG_OUT   OUT VARCHAR2
  );

  PROCEDURE BENEF_DELETE (
      P_BENEF_ID NUMBER,
      P_MSG_OUT  OUT VARCHAR2
  );


  -- ============================================================
  -- 4) Accounts — حسابات الصرف (الأساس)
  -- ============================================================
  PROCEDURE ACCOUNTS_LIST (
      P_EMP_NO          NUMBER,
      P_ONLY_ACTIVE     NUMBER DEFAULT 0,    -- 1=نشطة فقط
      P_REF_CUR_OUT     OUT SYS_REFCURSOR,
      P_MSG_OUT         OUT VARCHAR2
  );

  PROCEDURE ACCOUNT_GET (
      P_ACC_ID      NUMBER,
      P_REF_CUR_OUT OUT SYS_REFCURSOR,
      P_MSG_OUT     OUT VARCHAR2
  );

  PROCEDURE ACCOUNT_INSERT (
      P_EMP_NO         NUMBER, P_BENEFICIARY_ID NUMBER,
      P_PROVIDER_ID    NUMBER, P_BRANCH_ID NUMBER,
      P_ACCOUNT_NO     VARCHAR2, P_IBAN VARCHAR2, P_WALLET_NUMBER VARCHAR2,
      P_OWNER_ID_NO    VARCHAR2, P_OWNER_NAME VARCHAR2, P_OWNER_PHONE VARCHAR2,
      P_IS_DEFAULT     NUMBER,
      P_SPLIT_TYPE     NUMBER, P_SPLIT_VALUE NUMBER, P_SPLIT_ORDER NUMBER,
      P_NOTES          VARCHAR2,
      P_MSG_OUT        OUT VARCHAR2
  );

  PROCEDURE ACCOUNT_UPDATE (
      P_ACC_ID         NUMBER,
      P_BRANCH_ID      NUMBER,
      P_ACCOUNT_NO     VARCHAR2, P_IBAN VARCHAR2, P_WALLET_NUMBER VARCHAR2,
      P_OWNER_ID_NO    VARCHAR2, P_OWNER_NAME VARCHAR2, P_OWNER_PHONE VARCHAR2,
      P_IS_DEFAULT     NUMBER,
      P_SPLIT_TYPE     NUMBER, P_SPLIT_VALUE NUMBER, P_SPLIT_ORDER NUMBER,
      P_NOTES          VARCHAR2,
      P_MSG_OUT        OUT VARCHAR2
  );

  -- إيقاف حساب مع سبب
  PROCEDURE ACCOUNT_DEACTIVATE (
      P_ACC_ID  NUMBER, P_REASON NUMBER, P_NOTES VARCHAR2,
      P_MSG_OUT OUT VARCHAR2
  );

  -- إعادة تفعيل حساب
  PROCEDURE ACCOUNT_REACTIVATE (
      P_ACC_ID  NUMBER, P_NOTES VARCHAR2,
      P_MSG_OUT OUT VARCHAR2
  );

  -- حذف soft delete
  PROCEDURE ACCOUNT_DELETE (
      P_ACC_ID  NUMBER,
      P_MSG_OUT OUT VARCHAR2
  );

  -- تعيين حساب واحد كافتراضي (يُلغى من الباقي)
  PROCEDURE ACCOUNT_SET_DEFAULT (
      P_ACC_ID  NUMBER,
      P_MSG_OUT OUT VARCHAR2
  );


  -- ============================================================
  -- 5) Split Distribution — التوزيع
  -- ============================================================

  -- تحقق من صحة التوزيع للموظف
  -- يُرجع: '1' = صحيح, أو رسالة خطأ بالعربية
  FUNCTION VALIDATE_SPLIT (
      P_EMP_NO NUMBER
  ) RETURN VARCHAR2;

  -- ملاحظة: CALCULATE_SPLIT (معاينة قبل الحفظ) حُذفت.
  -- استخدم AUTO_FILL_SPLIT (يكتب مباشرة) + SPLIT_LIST (يقرأ).

  -- تعبئة تلقائية في PAYMENT_REQ_DETAIL_SPLIT_TB عند إنشاء detail
  PROCEDURE AUTO_FILL_SPLIT (
      P_DETAIL_ID NUMBER,
      P_MSG_OUT   OUT VARCHAR2
  );

  -- تعديل يدوي للتوزيع (من شاشة تفاصيل الطلب)
  -- P_SPLITS_JSON: [{"acc_id":101,"amount":700.00},{"acc_id":102,"amount":300.00}]
  PROCEDURE UPDATE_SPLIT_MANUAL (
      P_DETAIL_ID   NUMBER,
      P_SPLITS_JSON CLOB,
      P_MSG_OUT     OUT VARCHAR2
  );

  -- إرجاع التوزيع للتلقائي (إلغاء التعديل اليدوي)
  PROCEDURE RESET_SPLIT_TO_AUTO (
      P_DETAIL_ID NUMBER,
      P_MSG_OUT   OUT VARCHAR2
  );

  -- عرض توزيع detail معين
  PROCEDURE SPLIT_LIST (
      P_DETAIL_ID   NUMBER,
      P_REF_CUR_OUT OUT SYS_REFCURSOR,
      P_MSG_OUT     OUT VARCHAR2
  );


  -- ============================================================
  -- 6) Helpers (دوال مساعدة سريعة)
  -- ============================================================

  -- الحساب الافتراضي للموظف (IS_DEFAULT=1 & IS_ACTIVE=1)
  FUNCTION GET_DEFAULT_ACCOUNT (P_EMP_NO NUMBER) RETURN NUMBER;

  -- عدد الحسابات النشطة للموظف
  FUNCTION COUNT_ACTIVE_ACCOUNTS (P_EMP_NO NUMBER) RETURN NUMBER;

  -- هل الموظف عنده محفظة إلكترونية نشطة؟
  FUNCTION HAS_WALLET (P_EMP_NO NUMBER) RETURN NUMBER;

  -- نوع المزود (1=بنك, 2=محفظة)
  FUNCTION GET_PROVIDER_TYPE (P_PROVIDER_ID NUMBER) RETURN NUMBER;

  -- اسم المزود
  FUNCTION GET_PROVIDER_NAME (P_PROVIDER_ID NUMBER) RETURN VARCHAR2;

  -- اسم الفرع
  FUNCTION GET_BRANCH_NAME (P_BRANCH_ID NUMBER) RETURN VARCHAR2;


  -- ============================================================
  -- 7) Employees — قائمة الموظفين (للشاشة الرئيسية + pagination)
  -- ============================================================

  -- قائمة الموظفين مع عدد حساباتهم + الفلاتر
  PROCEDURE EMPLOYEES_LIST_PAGINATED (
      P_EMP_NO      NUMBER DEFAULT NULL,   -- فلتر: موظف محدد
      P_BRANCH_NO   NUMBER DEFAULT NULL,   -- فلتر: مقر
      P_IS_ACTIVE   NUMBER DEFAULT NULL,   -- فلتر حالة التوظيف:
                                           --   1=فعّال، 0=متقاعد، 2=متوفى، 4=حسابه مغلق من البنك
                                           --   (متوفى/مغلق مشتقّان من PAYMENT_ACCOUNTS_TB.INACTIVE_REASON)
      P_HAS_ACC     NUMBER DEFAULT NULL,   -- فلتر: 1=عنده حساب نشط / 0=بدون حساب نشط
      P_HAS_BENEF   NUMBER DEFAULT NULL,   -- فلتر: 1=عنده مستفيد / 0=بدون مستفيد
      P_THE_MONTH   NUMBER DEFAULT NULL,   -- لو مُحدّد (YYYYMM): يفلتر على EMPLOYEES_MONTH ويعرض حالة الشهر التاريخية
      P_OFFSET      NUMBER DEFAULT 0,
      P_LIMIT       NUMBER DEFAULT 50,
      P_REF_CUR_OUT OUT SYS_REFCURSOR,
      P_MSG_OUT     OUT VARCHAR2
  );

  -- عدد الموظفين بنفس الفلاتر (للـ pagination)
  PROCEDURE EMPLOYEES_COUNT (
      P_EMP_NO      NUMBER DEFAULT NULL,
      P_BRANCH_NO   NUMBER DEFAULT NULL,
      P_IS_ACTIVE   NUMBER DEFAULT NULL,   -- 1=فعّال، 0=متقاعد، 2=متوفى، 4=حسابه مغلق من البنك
      P_HAS_ACC     NUMBER DEFAULT NULL,   -- 1=عنده حساب نشط / 0=بدون حساب نشط
      P_HAS_BENEF   NUMBER DEFAULT NULL,   -- 1=عنده مستفيد / 0=بدون مستفيد
      P_THE_MONTH   NUMBER DEFAULT NULL,   -- YYYYMM
      P_CNT_OUT     OUT NUMBER,
      P_MSG_OUT     OUT VARCHAR2
  );

  -- إجمالي الإحصائيات بنفس الفلاتر (لبطاقات الواجهة)
  PROCEDURE EMPLOYEES_TOTALS (
      P_EMP_NO       NUMBER DEFAULT NULL,
      P_BRANCH_NO    NUMBER DEFAULT NULL,
      P_IS_ACTIVE    NUMBER DEFAULT NULL,   -- 1=فعّال، 0=متقاعد، 2=متوفى، 4=حسابه مغلق من البنك
      P_HAS_ACC      NUMBER DEFAULT NULL,   -- 1=عنده حساب نشط / 0=بدون حساب نشط
      P_HAS_BENEF    NUMBER DEFAULT NULL,   -- 1=عنده مستفيد / 0=بدون مستفيد
      P_THE_MONTH    NUMBER DEFAULT NULL,   -- YYYYMM
      P_TOTAL_OUT        OUT NUMBER,
      P_BANK_OUT         OUT NUMBER,
      P_WALLET_OUT       OUT NUMBER,
      P_BENEF_OUT        OUT NUMBER,
      P_MSG_OUT          OUT VARCHAR2
  );

  -- بيانات موظف واحد (الاسم، الهوية، البنك الحالي، إلخ)
  PROCEDURE EMPLOYEE_GET (
      P_EMP_NO      NUMBER,
      P_REF_CUR_OUT OUT SYS_REFCURSOR,
      P_MSG_OUT     OUT VARCHAR2
  );


  -- ============================================================
  -- 8) Bulk Operations — تعديل جماعي
  -- ============================================================

  -- تطبيق تعديل على مجموعة موظفين
  -- P_EMPS_CSV: "1008,1009,1010"
  -- P_ACTION: 'ADD_WALLET' | 'TRANSFER_BANK' | 'DEACTIVATE_ALL' | 'SET_DEFAULT'
  PROCEDURE BULK_APPLY (
      P_EMPS_CSV     VARCHAR2,
      P_ACTION       VARCHAR2,
      P_PARAMS_JSON  CLOB,      -- معاملات خاصة بكل action
      P_MSG_OUT      OUT VARCHAR2
  );

  -- استيراد من Excel (بعد تحليل الملف في PHP)
  -- P_ROWS_JSON: [{"emp_no":1008,"provider_id":3,"iban":"PS...","split_pct":60}, ...]
  PROCEDURE BULK_FROM_JSON (
      P_ROWS_JSON CLOB,
      P_MSG_OUT   OUT VARCHAR2
  );

END PAYMENT_ACCOUNTS_PKG;
/
