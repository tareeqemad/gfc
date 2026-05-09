CREATE OR REPLACE PACKAGE GFC_PAK.DISBURSEMENT_BATCH_PKG AS

  -- ============================================================
  -- Constants (مكررة من DISBURSEMENT_PKG للاستخدام بالـ SQL)
  -- ============================================================
  C_REQ_STATUS_DRAFT             CONSTANT NUMBER := 0;
  C_REQ_STATUS_APPROVED          CONSTANT NUMBER := 1;
  C_REQ_STATUS_PAID              CONSTANT NUMBER := 2;
  C_REQ_STATUS_PARTIAL_APPROVED  CONSTANT NUMBER := 3;
  C_REQ_STATUS_PARTIAL_PAID      CONSTANT NUMBER := 4;
  C_REQ_STATUS_CANCELLED         CONSTANT NUMBER := 9;

  C_REQ_TYPE_BENEFITS            CONSTANT NUMBER := 5;

  -- ============================================================
  -- Batch Preview
  -- ============================================================
  PROCEDURE PAYMENT_REQ_BATCH_PREVIEW (
      P_REQ_IDS VARCHAR2,
      P_REF_CUR_OUT OUT SYS_REFCURSOR, P_MSG_OUT OUT VARCHAR2
  );

  -- ============================================================
  -- Batch Confirm + Pay
  -- ============================================================
  PROCEDURE PAYMENT_REQ_BATCH_CONFIRM (
      P_REQ_IDS VARCHAR2, P_EXCLUDE_DETAIL_IDS VARCHAR2 DEFAULT NULL,
      P_DISBURSE_METHOD NUMBER DEFAULT 1,
      P_MSG_OUT OUT VARCHAR2
  );

  PROCEDURE PAYMENT_REQ_BATCH_PAY (
      P_BATCH_ID NUMBER, P_MSG_OUT OUT VARCHAR2
  );

  -- ============================================================
  -- Batch Cancel + Reverse
  -- ============================================================
  PROCEDURE BATCH_CANCEL (
      P_BATCH_ID NUMBER, P_MSG_OUT OUT VARCHAR2
  );

  PROCEDURE BATCH_REVERSE_PAY (
      P_BATCH_ID NUMBER, P_MSG_OUT OUT VARCHAR2
  );

  -- ============================================================
  -- Batch History
  -- ============================================================
  PROCEDURE BATCH_HISTORY_LIST (
      P_REF_CUR_OUT OUT SYS_REFCURSOR, P_MSG_OUT OUT VARCHAR2
  );

  PROCEDURE BATCH_HISTORY_GET (
      P_BATCH_ID NUMBER,
      P_REF_CUR_OUT OUT SYS_REFCURSOR, P_MSG_OUT OUT VARCHAR2
  );

  -- تفاصيل توزيع الصرف لموظف داخل دفعة معينة:
  -- يعرض كل حسابات الموظف + المبلغ الفعلي المخصّص لكل حساب في هذه الدفعة
  PROCEDURE BATCH_EMP_ACCOUNTS_GET (
      P_BATCH_ID    NUMBER,
      P_EMP_NO      NUMBER,
      P_REF_CUR_OUT OUT SYS_REFCURSOR,
      P_MSG_OUT     OUT VARCHAR2
  );

  -- ملخص الدفعة حسب البنك/المحفظة الفعلية (snapshot-aware):
  -- يجمع TOTAL_AMOUNT و COUNT(DISTINCT EMP_NO) و COUNT(*) per provider
  -- يستخدم SNAP_PROVIDER_NAME للطريقة الجديدة، fallback للـ legacy
  PROCEDURE BATCH_BANK_SUMMARY_GET (
      P_BATCH_ID    NUMBER,
      P_REF_CUR_OUT OUT SYS_REFCURSOR,
      P_MSG_OUT     OUT VARCHAR2
  );

  -- إعادة احتساب التوزيع لدفعة محتسبة (قبل التنفيذ):
  -- يحذف PAYMENT_BATCH_DETAIL_TB ويعيد احتسابه بناءً على PAYMENT_ACCOUNTS_TB الحالي
  -- مفيد لو المحاسب عدّل حسابات بعد الاعتماد دون تغيير المبالغ
  PROCEDURE BATCH_REFRESH_SPLIT (
      P_BATCH_ID    NUMBER,
      P_CHANGED_OUT OUT NUMBER,    -- عدد سطور الـ batch_detail المُعاد احتسابها
      P_MSG_OUT     OUT VARCHAR2
  );

  -- معاينة التوزيع التفصيلي — يرجع لكل (موظف+حساب) سطر مع المبلغ المخصّص + STATUS
  -- بدون كتابة في DB — للعرض في شاشة الاحتساب قبل الاعتماد
  -- يستبدل modal الـ BATCH_PREVIEW القديم: المحاسب يشوف التوزيع الفعلي بدلاً من التحقق فقط
  PROCEDURE BATCH_COMPUTE_PREVIEW (
      P_REQ_IDS            IN  VARCHAR2,
      P_EXCLUDE_DETAIL_IDS IN  VARCHAR2 DEFAULT NULL,
      P_REF_CUR_OUT        OUT SYS_REFCURSOR,
      P_MSG_OUT            OUT VARCHAR2
  );

  -- معاينة الاحتساب (legacy) — يفحص توزيع الصرف لكل موظف قبل الاعتماد
  -- يرجع: لكل موظف STATUS (OK/WARN/ERR) + ISSUE (وصف المشكلة)
  -- (للتوافق فقط؛ استخدم BATCH_COMPUTE_PREVIEW الجديد)
  PROCEDURE BATCH_PREVIEW (
      P_REQ_IDS            IN  VARCHAR2,
      P_EXCLUDE_DETAIL_IDS IN  VARCHAR2 DEFAULT NULL,
      P_REF_CUR_OUT        OUT SYS_REFCURSOR,
      P_MSG_OUT            OUT VARCHAR2
  );

  -- الدفعات المحتسبة (غير منفّذة) لموظف معين — للتنبيه في شاشة الموظف
  PROCEDURE EMP_PENDING_BATCHES (
      P_EMP_NO       NUMBER,
      P_REF_CUR_OUT  OUT SYS_REFCURSOR,
      P_MSG_OUT      OUT VARCHAR2
  );

  -- ============================================================
  -- Bank Export — تصدير بيانات الدفعة للبنك
  -- ============================================================
  PROCEDURE BATCH_BANK_EXPORT (
      P_BATCH_ID NUMBER,
      P_MASTER_BANK_NO NUMBER DEFAULT NULL,
      P_REF_CUR_OUT OUT SYS_REFCURSOR, P_MSG_OUT OUT VARCHAR2
  );

  -- ============================================================
  -- Override Distribution — إعادة توزيع موظف معيّن في دفعة محتسبة
  -- ============================================================
  -- يحذف توزيع الموظف الحالي ويعيد احتسابه بناءً على override جديد:
  --   P_OVERRIDE_PROVIDER_TYPE: NULL=افتراضي, 1=بنك فقط, 2=محفظة فقط
  --   P_OVERRIDE_ACC_ID: حساب محدد (يطغى على PROVIDER_TYPE — 100% له)
  -- ⚠️ يعمل فقط لو الدفعة بحالة محتسبة (STATUS=0) + DISBURSE_METHOD=2
  PROCEDURE BATCH_DETAIL_REDIST (
      P_BATCH_ID                NUMBER,
      P_EMP_NO                  NUMBER,
      P_OVERRIDE_PROVIDER_TYPE  NUMBER,
      P_OVERRIDE_ACC_ID         NUMBER,
      P_MSG_OUT                 OUT VARCHAR2
  );

END DISBURSEMENT_BATCH_PKG;
/
