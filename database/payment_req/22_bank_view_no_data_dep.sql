-- ============================================================
-- 🔧 PAYMENT_BATCH_BANK_VW — مصدر واحد: GFC schema
-- ============================================================
-- إزالة كل اعتماد على:
--   ❌ DATA.BANKS               → استبدلت بـ PAYMENT_BANK_BRANCHES_TB
--   ❌ DATA.MASTER_BANKS_EMAIL  → استبدلت بـ PAYMENT_PROVIDERS_TB
--   ❌ DISBURSEMENT_PKG.GET_BANK_NAME (يقرأ من DATA.BANKS) — أُزيلت
--
-- يبقى الاعتماد على:
--   ✅ DATA.EMPLOYEES           → ضرورية (المصدر الوحيد لبيانات الموظف)
--
-- منطق الجسر (Bridge):
--   1) ACC_ID موجود → ربط مباشر مع PAYMENT_ACCOUNTS_TB → branch + provider
--   2) ACC_ID غير موجود (legacy) → نطابق BD.BANK_NO مع
--        أ) PAYMENT_BANK_BRANCHES_TB.LEGACY_BANK_NO  (للبنوك)
--        ب) PAYMENT_PROVIDERS_TB.LEGACY_BANK_NO     (للمحافظ — ما عندها فروع)
--
-- اسم الفرع:
--   - DATA.BANKS فيها الصيغة الكاملة "بنك فلسطين - فرع X"
--   - بعد الـ rebuild صار BRANCH_NAME عندنا بنفس الصيغة
--   - فالـ view يستخدم BRANCH_NAME مباشرة (بدون بناء/تركيب)
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
        CASE WHEN BD.ACC_ID IS NOT NULL THEN PA.IBAN ELSE E.IBAN END)              AS IBAN,
    NVL(BD.SNAP_ACCOUNT_NO,
        CASE WHEN BD.ACC_ID IS NOT NULL
             THEN NVL(PA.ACCOUNT_NO, PA.WALLET_NUMBER)
             ELSE E.ACCOUNT_BANK_EMAIL END)                                        AS BANK_ACCOUNT,
    PA.WALLET_NUMBER                        AS WALLET_NUMBER,
    PA.ACCOUNT_NO                           AS ACCOUNT_NO,

    BD.BANK_NO,

    -- ───────── اسم الفرع ─────────
    -- أولوية: SNAP → BR (عبر ACC_ID) → BR_LEG (عبر LEGACY_BANK_NO)
    -- لا fallback لـ DATA. لو ما في branch (محفظة) → NULL
    NVL(BD.SNAP_BRANCH_NAME,
        COALESCE(BR.BRANCH_NAME, BR_LEG.BRANCH_NAME))                              AS BANK_BRANCH_NAME,

    -- 🆕 BRANCH_PRINT_NO: الكود الرسمي للفرع المُستخدَم في كشف البنك
    --   الأولوية: PRINT_NO (الحقل المخصّص) → LEGACY_BANK_NO (fallback)
    COALESCE(BR.PRINT_NO, BR.LEGACY_BANK_NO,
             BR_LEG.PRINT_NO, BR_LEG.LEGACY_BANK_NO)                               AS BRANCH_PRINT_NO,

    -- ───────── المزود (بنك أو محفظة) ─────────
    -- أولوية: PA (عبر ACC_ID) → BR_LEG (legacy bank) → PP_DIRECT (legacy wallet)
    COALESCE(PA.PROVIDER_ID, BR_LEG.PROVIDER_ID, PP_DIRECT.PROVIDER_ID)             AS PROVIDER_ID,
    NVL(PP.PROVIDER_TYPE,
        NVL(PP_LEG.PROVIDER_TYPE,
            NVL(PP_DIRECT.PROVIDER_TYPE, 1)))                                       AS PROVIDER_TYPE,
    NVL(PP.PROVIDER_CODE,
        NVL(PP_LEG.PROVIDER_CODE,
            NVL(PP_DIRECT.PROVIDER_CODE, '')))                                      AS PROVIDER_CODE,
    NVL(PP.EXPORT_FORMAT,
        NVL(PP_LEG.EXPORT_FORMAT, PP_DIRECT.EXPORT_FORMAT))                         AS EXPORT_FORMAT,

    -- ───────── البنك الرئيسي ─────────
    -- = LEGACY_BANK_NO تبع المزود (هاي اللي تُستخدم في تصنيف ملف التصدير)
    COALESCE(PP.LEGACY_BANK_NO, PP_LEG.LEGACY_BANK_NO, PP_DIRECT.LEGACY_BANK_NO)    AS MASTER_BANK_NO,
    NVL(BD.SNAP_PROVIDER_NAME,
        COALESCE(PP.PROVIDER_NAME, PP_LEG.PROVIDER_NAME, PP_DIRECT.PROVIDER_NAME))  AS MASTER_BANK_NAME,

    -- ───────── حساب الشركة (المُحوِّل) ─────────
    COALESCE(PP.COMPANY_IBAN,       PP_LEG.COMPANY_IBAN,       PP_DIRECT.COMPANY_IBAN)        AS COMPANY_IBAN,
    COALESCE(PP.COMPANY_ACCOUNT_NO, PP_LEG.COMPANY_ACCOUNT_NO, PP_DIRECT.COMPANY_ACCOUNT_NO)  AS COMPANY_ACCOUNT_NO,
    COALESCE(PP.COMPANY_ACCOUNT_ID, PP_LEG.COMPANY_ACCOUNT_ID, PP_DIRECT.COMPANY_ACCOUNT_ID)  AS COMPANY_ACCOUNT_ID,

    -- ───────── صاحب الحساب ─────────
    NVL(BD.SNAP_OWNER_NAME,
        CASE WHEN BD.ACC_ID IS NOT NULL THEN PA.OWNER_NAME ELSE E.NAME END)        AS OWNER_NAME,
    NVL(BD.SNAP_OWNER_ID_NO,
        CASE WHEN BD.ACC_ID IS NOT NULL THEN PA.OWNER_ID_NO ELSE TO_CHAR(E.ID) END) AS OWNER_ID_NO,
    NVL(PA.OWNER_PHONE, TO_CHAR(E.TEL))                                            AS OWNER_PHONE,

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

    -- ───────── حالة الموظف المركّبة (للون الصف) ─────────
    DISBURSEMENT_PKG.GET_EMP_DISPLAY_STATUS(BD.EMP_NO) AS EMP_DISPLAY_STATUS,

    BD.TOTAL_AMOUNT
FROM GFC.PAYMENT_BATCH_DETAIL_TB BD
JOIN GFC.PAYMENT_BATCH_TB B                  ON B.BATCH_ID = BD.BATCH_ID
-- DATA.EMPLOYEES — الوحيدة المتبقية من DATA (المصدر الوحيد لبيانات الموظف)
JOIN (
    SELECT NO, NAME, ID, PRICE_CODE, IBAN, ACCOUNT_BANK_EMAIL, TEL,
           ROW_NUMBER() OVER (PARTITION BY NO ORDER BY ROWID) AS RN
      FROM DATA.EMPLOYEES
) E ON E.NO = BD.EMP_NO AND E.RN = 1

-- ───────── المسار الأساسي (ACC_ID موجود) ─────────
LEFT JOIN GFC.PAYMENT_ACCOUNTS_TB PA          ON PA.ACC_ID    = BD.ACC_ID
LEFT JOIN GFC.PAYMENT_PROVIDERS_TB PP         ON PP.PROVIDER_ID = PA.PROVIDER_ID
LEFT JOIN GFC.PAYMENT_BANK_BRANCHES_TB BR     ON BR.BRANCH_ID = PA.BRANCH_ID

-- ───────── جسر legacy للبنوك (BD.BANK_NO → فرع GFC) ─────────
LEFT JOIN GFC.PAYMENT_BANK_BRANCHES_TB BR_LEG ON BR_LEG.LEGACY_BANK_NO = BD.BANK_NO
                                            AND BR_LEG.IS_ACTIVE = 1
LEFT JOIN GFC.PAYMENT_PROVIDERS_TB PP_LEG     ON PP_LEG.PROVIDER_ID = BR_LEG.PROVIDER_ID

-- ───────── جسر legacy للمحافظ (BD.BANK_NO → مزود مباشرة، ما إلها فروع) ─────────
LEFT JOIN GFC.PAYMENT_PROVIDERS_TB PP_DIRECT  ON PP_DIRECT.LEGACY_BANK_NO = BD.BANK_NO
                                            AND PP_DIRECT.PROVIDER_TYPE = 2
                                            AND BR_LEG.BRANCH_ID IS NULL  -- فقط لو ما لقينا فرع

-- ───────── المستفيد ─────────
LEFT JOIN GFC.PAYMENT_BENEFICIARIES_TB BNF    ON BNF.BENEFICIARY_ID = PA.BENEFICIARY_ID;

GRANT SELECT ON GFC.PAYMENT_BATCH_BANK_VW TO PUBLIC;
CREATE OR REPLACE SYNONYM GFC_PAK.PAYMENT_BATCH_BANK_VW FOR GFC.PAYMENT_BATCH_BANK_VW;

-- ============================================================
-- التحقق: عدّ الفروع الظاهرة في view لـ batch_id=1
-- ============================================================
SELECT BANK_BRANCH_NAME, COUNT(*) AS CNT, SUM(TOTAL_AMOUNT) AS TOTAL
  FROM GFC.PAYMENT_BATCH_BANK_VW
 WHERE BATCH_ID = 1
 GROUP BY BANK_BRANCH_NAME
 ORDER BY BANK_BRANCH_NAME;
