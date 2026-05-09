-- ============================================================
-- إصلاح PAYMENT_BATCH_BANK_VW: توحيد أسماء الفروع
-- ============================================================
-- المشكلة: الموظف القديم (method=1، بدون ACC_ID) كان يظهر بصيغة legacy
--   "فلسطين دير البلح" — بينما الجديد (method=2 + ACC_ID) يظهر:
--   "بنك فلسطين - فرع دير البلح".
--   النتيجة: نفس الفرع يظهر بسطرين منفصلين في batch_detail.
--
-- الحل: استخدام LEGACY_BANK_NO كجسر — أي PAYMENT_BANK_BRANCHES_TB
--   عنده LEGACY_BANK_NO = BD.BANK_NO نستخدم اسمه الموحّد، ولا نوقع على
--   DATA.BANKS إلا لو ما لقيناش ربط GFC.
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
    E.PRICE_CODE,
    BD.ACC_ID,

    -- ───────── بيانات الحساب (snapshot أولاً، fallback للـ live) ─────────
    NVL(BD.SNAP_IBAN,
        CASE
          WHEN NVL(B.DISBURSE_METHOD, 1) = 2 AND BD.ACC_ID IS NOT NULL THEN PA.IBAN
          ELSE E.IBAN
        END)                                AS IBAN,
    NVL(BD.SNAP_ACCOUNT_NO,
        CASE
          WHEN NVL(B.DISBURSE_METHOD, 1) = 2 AND BD.ACC_ID IS NOT NULL
            THEN NVL(PA.ACCOUNT_NO, PA.WALLET_NUMBER)
          ELSE E.ACCOUNT_BANK_EMAIL
        END)                                AS BANK_ACCOUNT,
    PA.WALLET_NUMBER                        AS WALLET_NUMBER,
    PA.ACCOUNT_NO                           AS ACCOUNT_NO,

    BD.BANK_NO,

    -- ───────── اسم الفرع — أولوية صارمة:
    --   1) SNAP_BRANCH_NAME (snapshot وقت الاحتساب)
    --   2) PAYMENT_BANK_BRANCHES_TB عبر BRANCH_ID (لو في ACC_ID)
    --   3) PAYMENT_BANK_BRANCHES_TB عبر LEGACY_BANK_NO = BD.BANK_NO  ← الجسر الجديد
    --   4) DATA.BANKS عبر GET_BANK_NAME (fallback أخير لو ما في ربط GFC)
    NVL(BD.SNAP_BRANCH_NAME,
        COALESCE(
          -- (2) ربط جديد عبر BRANCH_ID
          CASE WHEN BR.BRANCH_NAME IS NOT NULL THEN
            PP.PROVIDER_NAME || ' - فرع ' ||
            TRIM(REGEXP_REPLACE(BR.BRANCH_NAME,
                '^(' || REGEXP_REPLACE(PP.PROVIDER_NAME, '^(بنك|البنك)\s+', '') || '|الاسلامي|فلسطين|الاستثمار|العربي|القدس)\s+', ''))
          END,
          -- (3) ربط legacy عبر LEGACY_BANK_NO ← الإصلاح الجديد
          CASE WHEN BR_LEG.BRANCH_NAME IS NOT NULL THEN
            PP_LEG.PROVIDER_NAME || ' - فرع ' ||
            TRIM(REGEXP_REPLACE(BR_LEG.BRANCH_NAME,
                '^(' || REGEXP_REPLACE(PP_LEG.PROVIDER_NAME, '^(بنك|البنك)\s+', '') || '|الاسلامي|فلسطين|الاستثمار|العربي|القدس)\s+', ''))
          END,
          -- (4) fallback نهائي
          DISBURSEMENT_PKG.GET_BANK_NAME(BD.BANK_NO)
        ))                                  AS BANK_BRANCH_NAME,

    -- LEGACY_BANK_NO من جدول الفرع (نقدّم الجديد ثم الـ legacy bridge)
    NVL(BR.LEGACY_BANK_NO, BR_LEG.LEGACY_BANK_NO) AS BRANCH_PRINT_NO,

    -- ───────── المزود (بنك أو محفظة) ─────────
    NVL(PA.PROVIDER_ID, PP_LEG.PROVIDER_ID)         AS PROVIDER_ID,
    NVL(PP.PROVIDER_TYPE, NVL(PP_LEG.PROVIDER_TYPE, 1))   AS PROVIDER_TYPE,
    NVL(PP.PROVIDER_CODE, NVL(PP_LEG.PROVIDER_CODE, '')) AS PROVIDER_CODE,
    NVL(PP.EXPORT_FORMAT, PP_LEG.EXPORT_FORMAT)     AS EXPORT_FORMAT,

    -- ───────── البنك الرئيسي (للتصنيف عند التصدير) ─────────
    -- نفس الأولوية: GFC أولاً (بـ BRANCH_ID أو LEGACY_BANK_NO)، ثم MASTER_BANK_NO الـ legacy
    COALESCE(
      CASE WHEN PA.PROVIDER_ID IS NOT NULL THEN PP.LEGACY_BANK_NO END,
      PP_LEG.LEGACY_BANK_NO,
      BD.MASTER_BANK_NO
    )                                       AS MASTER_BANK_NO,

    NVL(BD.SNAP_PROVIDER_NAME,
        COALESCE(
          CASE WHEN PA.PROVIDER_ID IS NOT NULL THEN PP.PROVIDER_NAME END,
          PP_LEG.PROVIDER_NAME,
          MB.B_NAME
        ))                                  AS MASTER_BANK_NAME,

    -- ───────── حساب الشركة (المُحوِّل) ─────────
    NVL(PP.COMPANY_IBAN,       PP_LEG.COMPANY_IBAN)       AS COMPANY_IBAN,
    NVL(PP.COMPANY_ACCOUNT_NO, PP_LEG.COMPANY_ACCOUNT_NO) AS COMPANY_ACCOUNT_NO,
    NVL(PP.COMPANY_ACCOUNT_ID, PP_LEG.COMPANY_ACCOUNT_ID) AS COMPANY_ACCOUNT_ID,

    -- ───────── صاحب الحساب ─────────
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
    NVL(PA.OWNER_PHONE, TO_CHAR(E.TEL))     AS OWNER_PHONE,

    -- ───────── المستفيد ─────────
    PA.BENEFICIARY_ID                       AS BENEFICIARY_ID,
    BNF.NAME                                AS BENEF_NAME,
    BNF.ID_NO                               AS BENEF_ID_NO,
    BNF.REL_TYPE                            AS BENEF_REL_TYPE,
    CASE BNF.REL_TYPE
       WHEN 1 THEN 'زوجة' WHEN 2 THEN 'ابن'  WHEN 3 THEN 'بنت'
       WHEN 4 THEN 'أب'   WHEN 5 THEN 'أم'   WHEN 9 THEN 'وريث آخر'
       ELSE NULL
    END                                     AS BENEF_REL_NAME,
    BNF.PCT_SHARE                           AS BENEF_PCT_SHARE,

    -- ───────── Override info ─────────
    BD.OVERRIDE_PROVIDER_TYPE,
    BD.OVERRIDE_ACC_ID,

    -- ───────── حالة الموظف ─────────
    DISBURSEMENT_PKG.GET_EMP_DISPLAY_STATUS(BD.EMP_NO) AS EMP_DISPLAY_STATUS,

    BD.TOTAL_AMOUNT
FROM GFC.PAYMENT_BATCH_DETAIL_TB BD
JOIN GFC.PAYMENT_BATCH_TB B                  ON B.BATCH_ID = BD.BATCH_ID
JOIN (
    SELECT NO, NAME, ID, PRICE_CODE, IBAN, ACCOUNT_BANK_EMAIL, TEL,
           ROW_NUMBER() OVER (PARTITION BY NO ORDER BY ROWID) AS RN
      FROM DATA.EMPLOYEES
) E ON E.NO = BD.EMP_NO AND E.RN = 1
LEFT JOIN DATA.MASTER_BANKS_EMAIL MB         ON MB.B_NO = BD.MASTER_BANK_NO
-- المسار الجديد (ACC_ID + BRANCH_ID)
LEFT JOIN GFC.PAYMENT_ACCOUNTS_TB PA         ON PA.ACC_ID    = BD.ACC_ID
LEFT JOIN GFC.PAYMENT_PROVIDERS_TB PP        ON PP.PROVIDER_ID = PA.PROVIDER_ID
LEFT JOIN GFC.PAYMENT_BANK_BRANCHES_TB BR    ON BR.BRANCH_ID = PA.BRANCH_ID
-- 🆕 جسر الـ legacy: نطابق BD.BANK_NO مع LEGACY_BANK_NO في الفرع GFC
LEFT JOIN GFC.PAYMENT_BANK_BRANCHES_TB BR_LEG ON BR_LEG.LEGACY_BANK_NO = BD.BANK_NO
LEFT JOIN GFC.PAYMENT_PROVIDERS_TB PP_LEG     ON PP_LEG.PROVIDER_ID    = BR_LEG.PROVIDER_ID
-- المستفيد
LEFT JOIN GFC.PAYMENT_BENEFICIARIES_TB BNF   ON BNF.BENEFICIARY_ID = PA.BENEFICIARY_ID;

GRANT SELECT ON GFC.PAYMENT_BATCH_BANK_VW TO PUBLIC;
CREATE OR REPLACE SYNONYM GFC_PAK.PAYMENT_BATCH_BANK_VW FOR GFC.PAYMENT_BATCH_BANK_VW;

-- ============================================================
-- تحقق سريع: لا يجب أن يبقى أي branch بـ format legacy ("فلسطين X")
-- لو فيه ربط في PAYMENT_BANK_BRANCHES_TB.LEGACY_BANK_NO
-- ============================================================
SELECT BANK_BRANCH_NAME, COUNT(*) AS CNT, SUM(TOTAL_AMOUNT) AS TOTAL
  FROM GFC.PAYMENT_BATCH_BANK_VW
 GROUP BY BANK_BRANCH_NAME
 ORDER BY BANK_BRANCH_NAME;
