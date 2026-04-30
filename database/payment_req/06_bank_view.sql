-- ============================================================
-- View تصدير بيانات الدفعة للبنوك
-- يقرأ SNAP_* المخزّنة وقت الاحتساب أولاً (audit-safe)،
-- ولو NULL (batches قديمة جداً قبل migration) يفولّ على الـ live joins.
-- بهذه الطريقة، أي تعديل لاحق على PAYMENT_ACCOUNTS_TB أو DATA.EMPLOYEES
-- لا يُغيّر بيانات batches موجودة.
-- ============================================================

CREATE OR REPLACE VIEW GFC.PAYMENT_BATCH_BANK_VW AS
SELECT
    BD.BATCH_ID,
    B.BATCH_NO,
    B.BATCH_DATE,
    B.PAY_DATE,
    B.STATUS                                AS BATCH_STATUS,
    NVL(B.DISBURSE_METHOD, 1)               AS DISBURSE_METHOD,
    BD.EMP_NO,
    E.NAME                                  AS EMP_NAME,
    TO_CHAR(E.ID)                           AS EMP_ID,
    BD.ACC_ID,
    -- IBAN: snapshot أولاً، fallback للـ live
    NVL(BD.SNAP_IBAN,
        CASE
          WHEN NVL(B.DISBURSE_METHOD, 1) = 2 AND BD.ACC_ID IS NOT NULL THEN PA.IBAN
          ELSE E.IBAN
        END)                                AS IBAN,
    -- رقم الحساب
    NVL(BD.SNAP_ACCOUNT_NO,
        CASE
          WHEN NVL(B.DISBURSE_METHOD, 1) = 2 AND BD.ACC_ID IS NOT NULL
            THEN NVL(PA.ACCOUNT_NO, PA.WALLET_NUMBER)
          ELSE E.ACCOUNT_BANK_EMAIL
        END)                                AS BANK_ACCOUNT,
    E.PRICE_CODE,
    BD.BANK_NO,
    NVL(BD.SNAP_BRANCH_NAME,
        DISBURSEMENT_PKG.GET_BANK_NAME(BD.BANK_NO)) AS BANK_BRANCH_NAME,
    -- البنك الرئيسي
    CASE
      WHEN NVL(B.DISBURSE_METHOD, 1) = 2 AND PA.PROVIDER_ID IS NOT NULL
        THEN PP.LEGACY_BANK_NO
      ELSE BD.MASTER_BANK_NO
    END                                     AS MASTER_BANK_NO,
    NVL(BD.SNAP_PROVIDER_NAME,
        CASE
          WHEN NVL(B.DISBURSE_METHOD, 1) = 2 AND PA.PROVIDER_ID IS NOT NULL
            THEN PP.PROVIDER_NAME
          ELSE MB.B_NAME
        END)                                AS MASTER_BANK_NAME,
    -- اسم صاحب الحساب
    NVL(BD.SNAP_OWNER_NAME,
        CASE
          WHEN NVL(B.DISBURSE_METHOD, 1) = 2 AND BD.ACC_ID IS NOT NULL THEN PA.OWNER_NAME
          ELSE E.NAME
        END)                                AS OWNER_NAME,
    NVL(BD.SNAP_OWNER_ID_NO,
        CASE
          WHEN NVL(B.DISBURSE_METHOD, 1) = 2 AND BD.ACC_ID IS NOT NULL THEN PA.OWNER_ID_NO
          ELSE TO_CHAR(E.ID)
        END)                                AS OWNER_ID_NO,
    BD.TOTAL_AMOUNT
FROM GFC.PAYMENT_BATCH_DETAIL_TB BD
JOIN GFC.PAYMENT_BATCH_TB B                 ON B.BATCH_ID = BD.BATCH_ID
JOIN DATA.EMPLOYEES E                       ON E.NO = BD.EMP_NO
LEFT JOIN DATA.MASTER_BANKS_EMAIL MB        ON MB.B_NO = BD.MASTER_BANK_NO
LEFT JOIN GFC.PAYMENT_ACCOUNTS_TB PA        ON PA.ACC_ID = BD.ACC_ID
LEFT JOIN GFC.PAYMENT_PROVIDERS_TB PP       ON PP.PROVIDER_ID = PA.PROVIDER_ID;

GRANT SELECT ON GFC.PAYMENT_BATCH_BANK_VW TO PUBLIC;
CREATE OR REPLACE SYNONYM GFC_PAK.PAYMENT_BATCH_BANK_VW FOR GFC.PAYMENT_BATCH_BANK_VW;
