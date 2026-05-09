-- ============================================================
-- تشخيص ربط LEGACY_BANK_NO — مع تسمية واضحة لكل جدول/عمود
-- ============================================================
-- الجداول المعنية:
--   GFC.PAYMENT_PROVIDERS_TB        (الماستر — البنك الرئيسي)
--     • PROVIDER_ID, PROVIDER_NAME, LEGACY_BANK_NO (← DATA.MASTER_BANKS_EMAIL.B_NO)
--   GFC.PAYMENT_BANK_BRANCHES_TB    (الديتيل — الفرع)
--     • BRANCH_ID, BRANCH_NAME, PROVIDER_ID, LEGACY_BANK_NO (← DATA.BANK_BRANCH.B_BR_NO)
--   DATA.BANK_BRANCH                (legacy — الفروع)
--     • B_BR_NO, B_BR_NAME, B_NO (= البنك الرئيسي)
--   DATA.MASTER_BANKS_EMAIL         (legacy — البنوك الرئيسية)
--     • B_NO, B_NAME
-- ============================================================

SET LINESIZE 250
SET PAGESIZE 200
ALTER SESSION SET NLS_LANGUAGE = 'ARABIC';

-- ============================================================
-- (1) ملخص الفروع في GFC: كم منها مربوط بـ LEGACY_BANK_NO؟
-- ============================================================
PROMPT
PROMPT === (1) حالة الربط في PAYMENT_BANK_BRANCHES_TB ===
PROMPT

SELECT
  PP.PROVIDER_NAME                                                         AS PROVIDER_NAME,
  COUNT(*)                                                                 AS TOTAL_BRANCHES,
  SUM(CASE WHEN BR.LEGACY_BANK_NO IS NOT NULL THEN 1 ELSE 0 END)           AS LINKED,
  SUM(CASE WHEN BR.LEGACY_BANK_NO IS NULL THEN 1 ELSE 0 END)               AS UNLINKED
FROM GFC.PAYMENT_BANK_BRANCHES_TB BR
JOIN GFC.PAYMENT_PROVIDERS_TB PP ON PP.PROVIDER_ID = BR.PROVIDER_ID
GROUP BY PP.PROVIDER_NAME
ORDER BY UNLINKED DESC, PP.PROVIDER_NAME;

-- ============================================================
-- (2) الفروع في GFC بدون LEGACY_BANK_NO
-- ============================================================
PROMPT
PROMPT === (2) فروع GFC بدون LEGACY_BANK_NO ===
PROMPT

SELECT
  BR.BRANCH_ID,
  PP.PROVIDER_NAME,
  BR.BRANCH_NAME,
  BR.LEGACY_BANK_NO,
  BR.LEGACY_MASTER
FROM GFC.PAYMENT_BANK_BRANCHES_TB BR
JOIN GFC.PAYMENT_PROVIDERS_TB PP ON PP.PROVIDER_ID = BR.PROVIDER_ID
WHERE BR.LEGACY_BANK_NO IS NULL
ORDER BY PP.PROVIDER_NAME, BR.BRANCH_NAME;

-- ============================================================
-- (3) موظفو الدفعة 1 (PB-00001) اللي طلعت بصيغة legacy
--     نستخرج BANK_NO الفعلية المُستخدمة في الدفعة
-- ============================================================
PROMPT
PROMPT === (3) BANK_NO المستخدمة في الدفعة 1 وما لقيناهاش في PAYMENT_BANK_BRANCHES_TB ===
PROMPT

SELECT
  BD.BANK_NO,
  DISBURSEMENT_PKG.GET_BANK_NAME(BD.BANK_NO)        AS LEGACY_BRANCH_NAME,
  COUNT(DISTINCT BD.EMP_NO)                         AS EMP_COUNT,
  SUM(BD.TOTAL_AMOUNT)                              AS TOTAL_AMOUNT
FROM GFC.PAYMENT_BATCH_DETAIL_TB BD
WHERE BD.BATCH_ID = 1
  AND BD.BANK_NO IS NOT NULL
  AND NOT EXISTS (
    SELECT 1 FROM GFC.PAYMENT_BANK_BRANCHES_TB BR
     WHERE BR.LEGACY_BANK_NO = BD.BANK_NO
  )
GROUP BY BD.BANK_NO
ORDER BY EMP_COUNT DESC;

-- ============================================================
-- (4) مطابقة بين BANK_NO في الدفعات و DATA.BANK_BRANCH
--     (نشوف هل في الـ legacy أصلاً)
-- ============================================================
PROMPT
PROMPT === (4) BANK_NO مع تفاصيلها من DATA.BANK_BRANCH ===
PROMPT (هاي البيانات اللي ما زلنا ما ربطناهاش في GFC)
PROMPT

SELECT
  BD.BANK_NO,
  DBR.B_BR_NAME                                    AS LEGACY_BRANCH_NAME,
  DBR.B_NO                                         AS LEGACY_MASTER_NO,
  MBE.B_NAME                                       AS LEGACY_MASTER_NAME,
  COUNT(DISTINCT BD.EMP_NO)                        AS EMP_COUNT
FROM GFC.PAYMENT_BATCH_DETAIL_TB BD
LEFT JOIN DATA.BANK_BRANCH        DBR ON DBR.B_BR_NO = BD.BANK_NO
LEFT JOIN DATA.MASTER_BANKS_EMAIL MBE ON MBE.B_NO    = DBR.B_NO
WHERE BD.BATCH_ID = 1
  AND BD.BANK_NO IS NOT NULL
  AND NOT EXISTS (
    SELECT 1 FROM GFC.PAYMENT_BANK_BRANCHES_TB BR
     WHERE BR.LEGACY_BANK_NO = BD.BANK_NO
  )
GROUP BY BD.BANK_NO, DBR.B_BR_NAME, DBR.B_NO, MBE.B_NAME
ORDER BY EMP_COUNT DESC;

-- ============================================================
-- (5) اقتراح ربط: مطابقة GFC.PAYMENT_BANK_BRANCHES_TB.BRANCH_NAME
--     مع DATA.BANK_BRANCH.B_BR_NAME (عشان نولّد UPDATEs)
-- ============================================================
PROMPT
PROMPT === (5) اقتراحات UPDATE — راجعها قبل التنفيذ ===
PROMPT

SELECT
  BR.BRANCH_ID,
  PP.PROVIDER_NAME                                  AS PROVIDER,
  BR.BRANCH_NAME                                    AS GFC_BRANCH_NAME,
  DBR.B_BR_NO                                       AS SUGGESTED_LEGACY_NO,
  DBR.B_BR_NAME                                     AS DATA_BRANCH_NAME,
  'UPDATE GFC.PAYMENT_BANK_BRANCHES_TB SET LEGACY_BANK_NO = ' || DBR.B_BR_NO ||
    ', LEGACY_MASTER = ' || DBR.B_NO ||
    ' WHERE BRANCH_ID = ' || BR.BRANCH_ID || ';'    AS UPDATE_SQL
FROM GFC.PAYMENT_BANK_BRANCHES_TB BR
JOIN GFC.PAYMENT_PROVIDERS_TB PP ON PP.PROVIDER_ID = BR.PROVIDER_ID
JOIN DATA.BANK_BRANCH DBR
  ON UPPER(TRIM(REGEXP_REPLACE(DBR.B_BR_NAME, '[[:space:]]+', ' ')))
   = UPPER(TRIM(REGEXP_REPLACE(BR.BRANCH_NAME, '[[:space:]]+', ' ')))
WHERE BR.LEGACY_BANK_NO IS NULL
ORDER BY PP.PROVIDER_NAME, BR.BRANCH_NAME;

-- ============================================================
-- (6) لو في BANK_NO مش موجود حتى في DATA.BANK_BRANCH
--     (نحتاج INSERT جديد في GFC.PAYMENT_BANK_BRANCHES_TB)
-- ============================================================
PROMPT
PROMPT === (6) BANK_NO مش موجود حتى في DATA.BANK_BRANCH (نحتاج INSERT جديد) ===
PROMPT

SELECT
  BD.BANK_NO,
  DISBURSEMENT_PKG.GET_BANK_NAME(BD.BANK_NO)       AS LEGACY_NAME,
  COUNT(DISTINCT BD.EMP_NO)                        AS EMP_COUNT,
  SUM(BD.TOTAL_AMOUNT)                             AS TOTAL_AMOUNT
FROM GFC.PAYMENT_BATCH_DETAIL_TB BD
WHERE BD.BATCH_ID = 1
  AND BD.BANK_NO IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM DATA.BANK_BRANCH WHERE B_BR_NO = BD.BANK_NO)
GROUP BY BD.BANK_NO
ORDER BY EMP_COUNT DESC;

PROMPT
PROMPT ============================================================
PROMPT الخلاصة:
PROMPT - القسم 5 = UPDATEs جاهزة لربط فروع GFC الموجودة
PROMPT - القسم 6 = فروع جديدة لازم تتضاف في GFC.PAYMENT_BANK_BRANCHES_TB
PROMPT ============================================================
