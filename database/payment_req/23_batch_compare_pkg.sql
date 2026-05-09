-- ════════════════════════════════════════════════════════════════════
-- 🆕 BATCH COMPARE PKG — مقارنة الدفعة الحالية مع دفعات سابقة
--    وتفصيل الفروقات على مستوى الموظف
-- ════════════════════════════════════════════════════════════════════

CREATE OR REPLACE PACKAGE GFC_PAK.BATCH_COMPARE_PKG AS

  -- ─── Trend: آخر N دفعات (الحالية + ما قبلها) مع المؤشّرات الرئيسية
  -- يُستخدم في بطاقة "مقارنة وتاريخ الدفعات" أعلى batch_detail
  PROCEDURE BATCH_TREND (
      P_BATCH_ID     NUMBER,
      P_LIMIT        NUMBER DEFAULT 6,    -- الحالية + 5 سابقات
      P_REF_CUR_OUT  OUT SYS_REFCURSOR,
      P_MSG_OUT      OUT VARCHAR2
  );

  -- ─── Diff: مقارنة موظف-بموظف بين دفعتين
  -- يرجّع 4 فئات: NEW (جديد) / LEFT (خرج) / CHANGED (تغيّر مبلغه) / SAME (لا تغيير)
  PROCEDURE BATCH_DIFF (
      P_CUR_BATCH_ID  NUMBER,
      P_PRV_BATCH_ID  NUMBER,
      P_REF_CUR_OUT   OUT SYS_REFCURSOR,
      P_MSG_OUT       OUT VARCHAR2
  );

  -- ─── Summary: إحصائيات سريعة للديف (counts + totals لكل فئة)
  PROCEDURE BATCH_DIFF_SUMMARY (
      P_CUR_BATCH_ID  NUMBER,
      P_PRV_BATCH_ID  NUMBER,
      P_REF_CUR_OUT   OUT SYS_REFCURSOR,
      P_MSG_OUT       OUT VARCHAR2
  );

  -- ─── حالة الموظف لكل شهر داخل الدفعة (snapshot تاريخي من EMPLOYEES_MONTH)
  -- ترجع: EMP_NO + THE_MONTH + EMP_STATUS (1=فعّال, 0=متقاعد, 2=متوفى, 4=مغلق)
  PROCEDURE BATCH_EMP_MONTH_STATUSES (
      P_BATCH_ID    NUMBER,
      P_REF_CUR_OUT OUT SYS_REFCURSOR,
      P_MSG_OUT     OUT VARCHAR2
  );

END BATCH_COMPARE_PKG;
/

-- ════════════════════════════════════════════════════════════════════

CREATE OR REPLACE PACKAGE BODY GFC_PAK.BATCH_COMPARE_PKG AS

  -- ─────────────────────────────────────────────────────────
  -- BATCH TREND
  -- ─────────────────────────────────────────────────────────
  PROCEDURE BATCH_TREND (
      P_BATCH_ID     NUMBER,
      P_LIMIT        NUMBER DEFAULT 6,
      P_REF_CUR_OUT  OUT SYS_REFCURSOR,
      P_MSG_OUT      OUT VARCHAR2
  ) IS
  BEGIN
    -- ملاحظة: Oracle 11g ما بيدعم FETCH FIRST n ROWS ONLY → نستخدم ROWNUM داخل subquery
    OPEN P_REF_CUR_OUT FOR
      SELECT *
        FROM (
          SELECT
              B.BATCH_ID,
              B.BATCH_NO,
              TO_CHAR(B.ENTRY_DATE, 'DD/MM/YYYY') AS ENTRY_DATE_STR,
              TO_CHAR(B.PAY_DATE,   'DD/MM/YYYY') AS PAY_DATE_STR,
              B.STATUS,
              SETTING_PKG.CONSTANT_DETAILS_TB_GET_NAME(542, B.STATUS) AS STATUS_NAME,
              USER_PKG.GET_USER_NAME(B.ENTRY_USER) AS ENTRY_USER_NAME,
              USER_PKG.GET_USER_NAME(B.PAY_USER)   AS PAY_USER_NAME,
              (SELECT COUNT(DISTINCT BD.EMP_NO) FROM GFC.PAYMENT_BATCH_DETAIL_TB BD
                WHERE BD.BATCH_ID = B.BATCH_ID) AS EMP_COUNT,
              (SELECT NVL(SUM(BD.TOTAL_AMOUNT), 0) FROM GFC.PAYMENT_BATCH_DETAIL_TB BD
                WHERE BD.BATCH_ID = B.BATCH_ID) AS TOTAL_AMOUNT,
              (SELECT COUNT(*) FROM GFC.PAYMENT_BATCH_DETAIL_TB BD
                WHERE BD.BATCH_ID = B.BATCH_ID) AS MOVEMENT_COUNT,
              B.REQ_IDS,
              -- الشهور المغطّاة (مفيد للسياق المحاسبي)
              -- ملاحظة: Oracle 11g ما بيدعم correlated reference عميق مستويين
              -- → نستخدم REGEXP_REPLACE لإزالة التكرار من LISTAGG بدون nesting
              -- (الـ ORDER BY يضمن تجمّع التكرارات → REGEXP يشيلها)
              (SELECT REPLACE(
                        REGEXP_REPLACE(
                          LISTAGG(M.THE_MONTH, ',') WITHIN GROUP (ORDER BY M.THE_MONTH),
                          '([^,]+)(,\1)+', '\1'),
                        ',', ', ')
                 FROM GFC.PAYMENT_BATCH_LINK_TB L
                 JOIN GFC.PAYMENT_REQ_DETAIL_TB D ON D.DETAIL_ID = L.DETAIL_ID
                 JOIN GFC.PAYMENT_REQ_TB M ON M.REQ_ID = D.REQ_ID
                WHERE L.BATCH_ID = B.BATCH_ID) AS MONTHS_COVERED,
              CASE WHEN B.BATCH_ID = P_BATCH_ID THEN 1 ELSE 0 END AS IS_CURRENT
            FROM GFC.PAYMENT_BATCH_TB B
           WHERE B.STATUS != 9                  -- استبعد الملغية
             AND B.BATCH_ID <= P_BATCH_ID       -- الحالية + ما قبلها فقط
           ORDER BY B.BATCH_ID DESC
        )
       WHERE ROWNUM <= P_LIMIT;
    P_MSG_OUT := '1';
  EXCEPTION WHEN OTHERS THEN P_MSG_OUT := SQLERRM;
  END;


  -- ─────────────────────────────────────────────────────────
  -- BATCH DIFF — موظف بموظف
  -- ─────────────────────────────────────────────────────────
  PROCEDURE BATCH_DIFF (
      P_CUR_BATCH_ID  NUMBER,
      P_PRV_BATCH_ID  NUMBER,
      P_REF_CUR_OUT   OUT SYS_REFCURSOR,
      P_MSG_OUT       OUT VARCHAR2
  ) IS
  BEGIN
    OPEN P_REF_CUR_OUT FOR
    WITH CUR AS (
        SELECT EMP_NO, SUM(TOTAL_AMOUNT) AS AMT, COUNT(*) AS RCNT
          FROM GFC.PAYMENT_BATCH_DETAIL_TB
         WHERE BATCH_ID = P_CUR_BATCH_ID
         GROUP BY EMP_NO
    ),
    PRV AS (
        SELECT EMP_NO, SUM(TOTAL_AMOUNT) AS AMT, COUNT(*) AS RCNT
          FROM GFC.PAYMENT_BATCH_DETAIL_TB
         WHERE BATCH_ID = P_PRV_BATCH_ID
         GROUP BY EMP_NO
    )
    SELECT
        COALESCE(C.EMP_NO, P.EMP_NO)                     AS EMP_NO,
        EMP_PKG.GET_EMP_NAME(COALESCE(C.EMP_NO, P.EMP_NO))         AS EMP_NAME,
        EMP_PKG.GET_EMP_BRANCH_NAME(COALESCE(C.EMP_NO, P.EMP_NO))  AS BRANCH_NAME,
        NVL(C.AMT, 0)                                    AS CURRENT_AMOUNT,
        NVL(P.AMT, 0)                                    AS PREVIOUS_AMOUNT,
        NVL(C.AMT, 0) - NVL(P.AMT, 0)                    AS DIFF,
        CASE
          WHEN NVL(P.AMT, 0) = 0 THEN NULL
          ELSE ROUND(((NVL(C.AMT,0) - NVL(P.AMT,0)) / NVL(P.AMT,0)) * 100, 2)
        END                                              AS DIFF_PCT,
        CASE
          WHEN P.EMP_NO IS NULL                                   THEN 'NEW'
          WHEN C.EMP_NO IS NULL                                   THEN 'LEFT'
          WHEN ABS(NVL(C.AMT,0) - NVL(P.AMT,0)) > 0.01            THEN 'CHANGED'
          ELSE 'SAME'
        END                                              AS CHANGE_TYPE
    FROM CUR C
    FULL OUTER JOIN PRV P ON C.EMP_NO = P.EMP_NO
    ORDER BY
       -- ترتيب: NEW أولاً، ثم LEFT، ثم CHANGED بترتيب الفرق المطلق تنازلياً، ثم SAME
       CASE
         WHEN P.EMP_NO IS NULL THEN 1
         WHEN C.EMP_NO IS NULL THEN 2
         WHEN ABS(NVL(C.AMT,0) - NVL(P.AMT,0)) > 0.01 THEN 3
         ELSE 4
       END,
       ABS(NVL(C.AMT,0) - NVL(P.AMT,0)) DESC,
       COALESCE(C.EMP_NO, P.EMP_NO);
    P_MSG_OUT := '1';
  EXCEPTION WHEN OTHERS THEN P_MSG_OUT := SQLERRM;
  END;


  -- ─────────────────────────────────────────────────────────
  -- BATCH DIFF SUMMARY — إحصائيات سريعة (للعرض في الـ modal header)
  -- ─────────────────────────────────────────────────────────
  PROCEDURE BATCH_DIFF_SUMMARY (
      P_CUR_BATCH_ID  NUMBER,
      P_PRV_BATCH_ID  NUMBER,
      P_REF_CUR_OUT   OUT SYS_REFCURSOR,
      P_MSG_OUT       OUT VARCHAR2
  ) IS
  BEGIN
    OPEN P_REF_CUR_OUT FOR
    WITH CUR AS (
        SELECT EMP_NO, SUM(TOTAL_AMOUNT) AMT
          FROM GFC.PAYMENT_BATCH_DETAIL_TB
         WHERE BATCH_ID = P_CUR_BATCH_ID
         GROUP BY EMP_NO
    ),
    PRV AS (
        SELECT EMP_NO, SUM(TOTAL_AMOUNT) AMT
          FROM GFC.PAYMENT_BATCH_DETAIL_TB
         WHERE BATCH_ID = P_PRV_BATCH_ID
         GROUP BY EMP_NO
    ),
    DIFF AS (
        SELECT
            CASE
              WHEN P.EMP_NO IS NULL                                   THEN 'NEW'
              WHEN C.EMP_NO IS NULL                                   THEN 'LEFT'
              WHEN ABS(NVL(C.AMT,0) - NVL(P.AMT,0)) > 0.01            THEN 'CHANGED'
              ELSE 'SAME'
            END AS CHANGE_TYPE,
            NVL(C.AMT, 0) CURRENT_AMOUNT,
            NVL(P.AMT, 0) PREVIOUS_AMOUNT,
            NVL(C.AMT, 0) - NVL(P.AMT, 0) DIFF
        FROM CUR C
        FULL OUTER JOIN PRV P ON C.EMP_NO = P.EMP_NO
    )
    SELECT
        COUNT(CASE WHEN CHANGE_TYPE = 'NEW'     THEN 1 END) AS CNT_NEW,
        COUNT(CASE WHEN CHANGE_TYPE = 'LEFT'    THEN 1 END) AS CNT_LEFT,
        COUNT(CASE WHEN CHANGE_TYPE = 'CHANGED' THEN 1 END) AS CNT_CHANGED,
        COUNT(CASE WHEN CHANGE_TYPE = 'SAME'    THEN 1 END) AS CNT_SAME,
        NVL(SUM(CASE WHEN CHANGE_TYPE = 'NEW'  THEN CURRENT_AMOUNT END), 0) AS AMT_NEW,
        NVL(SUM(CASE WHEN CHANGE_TYPE = 'LEFT' THEN PREVIOUS_AMOUNT END), 0) AS AMT_LEFT,
        NVL(SUM(CASE WHEN CHANGE_TYPE = 'CHANGED' THEN DIFF END), 0)  AS AMT_CHANGED_NET,
        NVL(SUM(CASE WHEN CHANGE_TYPE = 'CHANGED' AND DIFF > 0 THEN DIFF END), 0) AS AMT_INCREASED,
        NVL(SUM(CASE WHEN CHANGE_TYPE = 'CHANGED' AND DIFF < 0 THEN ABS(DIFF) END), 0) AS AMT_DECREASED
    FROM DIFF;
    P_MSG_OUT := '1';
  EXCEPTION WHEN OTHERS THEN P_MSG_OUT := SQLERRM;
  END;

  -- ─────────────────────────────────────────────────────────
  -- BATCH EMP MONTH STATUSES — حالة كل (موظف+شهر) في الدفعة
  -- يستخدم DISBURSEMENT_PKG.GET_EMP_DISPLAY_STATUS(emp, month)
  -- لقراءة الحالة التاريخية من DATA.EMPLOYEES_MONTH
  -- ─────────────────────────────────────────────────────────
  PROCEDURE BATCH_EMP_MONTH_STATUSES (
      P_BATCH_ID    NUMBER,
      P_REF_CUR_OUT OUT SYS_REFCURSOR,
      P_MSG_OUT     OUT VARCHAR2
  ) IS
  BEGIN
    OPEN P_REF_CUR_OUT FOR
      -- لكل (موظف, شهر) موجود في الدفعة → نحسب الحالة في ذاك الشهر
      SELECT EMP_NO,
             THE_MONTH,
             DISBURSEMENT_PKG.GET_EMP_DISPLAY_STATUS(EMP_NO, THE_MONTH) AS EMP_STATUS
        FROM (
          SELECT DISTINCT D.EMP_NO, M.THE_MONTH
            FROM GFC.PAYMENT_BATCH_LINK_TB L
            JOIN GFC.PAYMENT_REQ_DETAIL_TB D ON D.DETAIL_ID = L.DETAIL_ID
            JOIN GFC.PAYMENT_REQ_TB        M ON M.REQ_ID    = D.REQ_ID
           WHERE L.BATCH_ID = P_BATCH_ID
        );
    P_MSG_OUT := '1';
  EXCEPTION WHEN OTHERS THEN P_MSG_OUT := SQLERRM;
  END;

END BATCH_COMPARE_PKG;
/

-- صلاحية التنفيذ + Public Synonym (يسمح بالاستدعاء بـ BATCH_COMPARE_PKG بدل GFC_PAK.BATCH_COMPARE_PKG)
GRANT EXECUTE ON GFC_PAK.BATCH_COMPARE_PKG TO PUBLIC;
/
CREATE OR REPLACE PUBLIC SYNONYM BATCH_COMPARE_PKG FOR GFC_PAK.BATCH_COMPARE_PKG;
/

-- ════════════════════════════════════════════════════════════════════
-- اختبار سريع (اختياري — احذف بعد التأكيد):
-- ════════════════════════════════════════════════════════════════════
-- DECLARE
--   v_cur SYS_REFCURSOR; v_msg VARCHAR2(200);
-- BEGIN
--   GFC_PAK.BATCH_COMPARE_PKG.BATCH_TREND(P_BATCH_ID => 1, P_LIMIT => 6, P_REF_CUR_OUT => v_cur, P_MSG_OUT => v_msg);
--   DBMS_OUTPUT.PUT_LINE('TREND msg=' || v_msg);
-- END;
-- /
