-- ============================================================
-- View تصدير بيانات الدفعة للبنوك
-- بديل عن BANKVIEW القديمة — تستخدم جداول الدفعات بدل ADMIN
-- ============================================================

CREATE OR REPLACE VIEW GFC.PAYMENT_BATCH_BANK_VW AS
SELECT
    BD.BATCH_ID,
    B.BATCH_NO,
    B.BATCH_DATE,
    B.PAY_DATE,
    B.STATUS                            AS BATCH_STATUS,
    BD.EMP_NO,
    E.NAME                              AS EMP_NAME,
    E.ID                                AS EMP_ID,
    E.IBAN,
    E.ACCOUNT_BANK_EMAIL                AS BANK_ACCOUNT,
    E.PRICE_CODE,
    BD.BANK_NO,
    DISBURSEMENT_PKG.GET_BANK_NAME(BD.BANK_NO) AS BANK_BRANCH_NAME,
    BD.MASTER_BANK_NO,
    MB.B_NAME                           AS MASTER_BANK_NAME,
    BD.TOTAL_AMOUNT
FROM GFC.PAYMENT_BATCH_DETAIL_TB BD
JOIN GFC.PAYMENT_BATCH_TB B            ON B.BATCH_ID = BD.BATCH_ID
JOIN DATA.EMPLOYEES E                   ON E.NO = BD.EMP_NO
LEFT JOIN DATA.MASTER_BANKS_EMAIL MB    ON MB.B_NO = BD.MASTER_BANK_NO;

GRANT SELECT ON GFC.PAYMENT_BATCH_BANK_VW TO PUBLIC;
CREATE OR REPLACE SYNONYM GFC_PAK.PAYMENT_BATCH_BANK_VW FOR GFC.PAYMENT_BATCH_BANK_VW;
