CREATE OR REPLACE PACKAGE GFC_PAK.DISBURSEMENT_CALC_PKG AS

  -- ============================================================
  -- DISBURSEMENT_CALC_PKG
  -- نسخة مطابقة لمنطق SALARYFORM.CAL_SALARY_RATE
  -- لكن تكتب على جداول مستقلة (PAYMENT_REQ_*_CALC_TB)
  -- بدون أي لمس للجداول الأصلية (SALARY, ADMIN, ADD_AND_DED, SALARY_TEST, ADMIN_TEST)
  -- ============================================================

  -- ============================================================
  -- Main Entry Point
  -- احتساب NET_SALARY لكل موظف ضمن النطاق مع تطبيق معادلة GET_VAL_ADD_DED
  -- النتيجة النهائية: PAYMENT_REQ_ADMIN_CALC_TB.NET_SALARY = قيمة الصرف (CAPPED)
  --                  PAYMENT_REQ_ADMIN_CALC_TB.ACCRUED_323 = قيمة 323 المحتسبة
  -- ============================================================
  PROCEDURE CAL_SALARY_RATE_PART (
      P_THE_MONTH NUMBER,
      P_NO_FROM   NUMBER,
      P_NO_TO     NUMBER,
      P_RATE      NUMBER,
      P_L_VALUE   NUMBER,
      P_H_VALUE   NUMBER,
      P_MSG_OUT   OUT VARCHAR2
  );

  -- ============================================================
  -- Accessors (للاستخدام من payment_req)
  -- ============================================================

  -- قيمة الصرف (CAPPED) بعد الاحتساب الكامل
  FUNCTION GET_DISBURSEMENT_AMOUNT (
      P_EMP_NO    NUMBER,
      P_THE_MONTH NUMBER
  ) RETURN NUMBER;

  -- NET_SALARY بعد الاحتساب (= CAPPED)
  FUNCTION GET_CALC_NET_SALARY (
      P_EMP_NO    NUMBER,
      P_THE_MONTH NUMBER
  ) RETURN NUMBER;

  -- قيمة 323 المحتسبة
  FUNCTION GET_CALC_323_VALUE (
      P_EMP_NO    NUMBER,
      P_THE_MONTH NUMBER
  ) RETURN NUMBER;

  -- هل تم الاحتساب لهذا الموظف والشهر؟
  FUNCTION HAS_CALC_DATA (
      P_EMP_NO    NUMBER,
      P_THE_MONTH NUMBER
  ) RETURN NUMBER;

  -- ============================================================
  -- Utilities
  -- ============================================================

  -- تنظيف بيانات الاحتساب لنطاق محدد
  PROCEDURE CLEAR_CALC_DATA (
      P_THE_MONTH NUMBER,
      P_NO_FROM   NUMBER DEFAULT NULL,
      P_NO_TO     NUMBER DEFAULT NULL
  );

END DISBURSEMENT_CALC_PKG;
/
