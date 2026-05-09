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
    -- رقم المحفظة منفصلاً (للمحافظ — PalPay/Jawwal Pay)
    PA.WALLET_NUMBER                        AS WALLET_NUMBER,
    -- رقم الحساب البنكي منفصلاً
    PA.ACCOUNT_NO                           AS ACCOUNT_NO,

    BD.BANK_NO,
    -- اسم الفرع — الأولوية للـ GFC schema (المصدر الأصلي)، DATA.BANKS كـ fallback فقط:
    --   1) SNAP_BRANCH_NAME (snapshot وقت الاحتساب — audit-safe)
    --   2) method=2 + ACC_ID: نبني الاسم من PAYMENT_BANK_BRANCHES_TB + PAYMENT_PROVIDERS_TB
    --      الصيغة الموحّدة: "<اسم البنك> - فرع <اسم الفرع بدون prefix البنك>"
    --   3) Legacy GET_BANK_NAME (للموظفين القدامى method=1 أو بدون ACC_ID)
    NVL(BD.SNAP_BRANCH_NAME,
        CASE
          WHEN NVL(B.DISBURSE_METHOD, 1) = 2 AND BD.ACC_ID IS NOT NULL AND BR.BRANCH_NAME IS NOT NULL THEN
            -- بناء اسم موحّد من المصادر الجديدة:
            -- مثال: PROVIDER_NAME='بنك فلسطين' + BRANCH_NAME='فلسطين دير البلح'
            --        → 'بنك فلسطين - فرع دير البلح'
            -- نشيل أول كلمة من BRANCH_NAME لو متطابقة مع آخر كلمة في PROVIDER_NAME
            PP.PROVIDER_NAME || ' - فرع ' ||
            TRIM(REGEXP_REPLACE(BR.BRANCH_NAME,
                '^(' || REGEXP_REPLACE(PP.PROVIDER_NAME, '^(بنك|البنك)\s+', '') || '|الاسلامي|فلسطين|الاستثمار|العربي|القدس)\s+', ''))
          ELSE DISBURSEMENT_PKG.GET_BANK_NAME(BD.BANK_NO)
        END)                                AS BANK_BRANCH_NAME,
    -- LEGACY_BANK_NO من جدول الفرع (لو موجود)
    BR.LEGACY_BANK_NO                       AS BRANCH_PRINT_NO,

    -- ───────── المزود (بنك أو محفظة) ─────────
    PA.PROVIDER_ID                          AS PROVIDER_ID,
    NVL(PP.PROVIDER_TYPE, 1)                AS PROVIDER_TYPE,    -- 1=بنك, 2=محفظة
    NVL(PP.PROVIDER_CODE, '')               AS PROVIDER_CODE,
    PP.EXPORT_FORMAT                        AS EXPORT_FORMAT,    -- لتمييز شكل ملف التصدير

    -- البنك الرئيسي (للتصنيف عند التصدير)
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

    -- ───────── حساب الشركة (المُحوِّل) ─────────
    PP.COMPANY_IBAN                         AS COMPANY_IBAN,
    PP.COMPANY_ACCOUNT_NO                   AS COMPANY_ACCOUNT_NO,
    PP.COMPANY_ACCOUNT_ID                   AS COMPANY_ACCOUNT_ID,

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
    -- جوال صاحب الحساب (مطلوب للمحافظ)
    NVL(PA.OWNER_PHONE, TO_CHAR(E.TEL))     AS OWNER_PHONE,

    -- ───────── المستفيد (لو الحساب لورث) ─────────
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

    -- ───────── Override info (للعرض في batch_detail) ─────────
    BD.OVERRIDE_PROVIDER_TYPE,
    BD.OVERRIDE_ACC_ID,

    -- ───────── حالة الموظف المركّبة (للون الصف) ─────────
    -- 1=فعّال, 0=متقاعد, 2=متوفى, 4=حساب مغلق
    DISBURSEMENT_PKG.GET_EMP_DISPLAY_STATUS(BD.EMP_NO) AS EMP_DISPLAY_STATUS,

    BD.TOTAL_AMOUNT
FROM GFC.PAYMENT_BATCH_DETAIL_TB BD
JOIN GFC.PAYMENT_BATCH_TB B                  ON B.BATCH_ID = BD.BATCH_ID
-- ⚠️ JOIN على EMPLOYEES بعد deduplicate (لو فيه أكثر من record لنفس NO يدبّل الصفوف)
JOIN (
    SELECT NO, NAME, ID, PRICE_CODE, IBAN, ACCOUNT_BANK_EMAIL, TEL,
           ROW_NUMBER() OVER (PARTITION BY NO ORDER BY ROWID) AS RN
      FROM DATA.EMPLOYEES
) E ON E.NO = BD.EMP_NO AND E.RN = 1
LEFT JOIN DATA.MASTER_BANKS_EMAIL MB         ON MB.B_NO = BD.MASTER_BANK_NO
LEFT JOIN GFC.PAYMENT_ACCOUNTS_TB PA         ON PA.ACC_ID = BD.ACC_ID
LEFT JOIN GFC.PAYMENT_PROVIDERS_TB PP        ON PP.PROVIDER_ID = PA.PROVIDER_ID
LEFT JOIN GFC.PAYMENT_BANK_BRANCHES_TB BR    ON BR.BRANCH_ID = PA.BRANCH_ID
LEFT JOIN GFC.PAYMENT_BENEFICIARIES_TB BNF   ON BNF.BENEFICIARY_ID = PA.BENEFICIARY_ID;

GRANT SELECT ON GFC.PAYMENT_BATCH_BANK_VW TO PUBLIC;
CREATE OR REPLACE SYNONYM GFC_PAK.PAYMENT_BATCH_BANK_VW FOR GFC.PAYMENT_BATCH_BANK_VW;
