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

  -- ============================================================
  -- Bank Export — تصدير بيانات الدفعة للبنك
  -- ============================================================
  PROCEDURE BATCH_BANK_EXPORT (
      P_BATCH_ID NUMBER,
      P_MASTER_BANK_NO NUMBER DEFAULT NULL,
      P_REF_CUR_OUT OUT SYS_REFCURSOR, P_MSG_OUT OUT VARCHAR2
  );

END DISBURSEMENT_BATCH_PKG;
/
