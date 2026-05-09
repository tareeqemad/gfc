-- ============================================================
-- اكتشاف: أين يوجد كود 460 (فرع دير البلح لبنك فلسطين)؟
-- ============================================================

-- 1) في DATA.BANK_BRANCH (مكان LEGACY_BANK_NO الأصلي)
SELECT * FROM DATA.BANK_BRANCH WHERE B_BR_NO = 460;
-- توقع: نشوف صف مع B_NO (master) و B_BR_NAME (اسم الفرع)


-- 2) شو الأعمدة الموجودة في DATA.BANK_BRANCH؟
SELECT COLUMN_NAME, DATA_TYPE, DATA_LENGTH
  FROM ALL_TAB_COLUMNS
 WHERE OWNER = 'DATA' AND TABLE_NAME = 'BANK_BRANCH'
 ORDER BY COLUMN_ID;


-- 3) كل فروع بنك فلسطين في DATA.BANK_BRANCH
SELECT * FROM DATA.BANK_BRANCH
 WHERE B_NO = 81   -- master = بنك فلسطين
 ORDER BY B_BR_NO;


-- 4) كيف يقرأ النظام القديم اسم الفرع؟
-- BD.BANK_NO يقرأ من DATA.BANKS — لكن BANK_NO = 9 لموظف 1309 = master
-- شو BD.BANK_NO للـ 61 موظف اللي عندهم فرع دير البلح من بنك فلسطين legacy؟
SELECT DISTINCT BD.BANK_NO,
       DISBURSEMENT_PKG.GET_BANK_NAME(BD.BANK_NO) AS NAME
  FROM GFC.PAYMENT_BATCH_DETAIL_TB BD
 WHERE BD.BATCH_ID = 1
   AND BD.MASTER_BANK_NO = 81
   AND BD.ACC_ID IS NULL
 ORDER BY BD.BANK_NO;
-- مهم: لو شفت رقم زي 9460 أو 81460 → هذا الكود اللي تستخدم DATA.BANKS


-- 5) DATA.BANKS — كل الصفوف اللي فيها "دير البلح"
SELECT NO, NAME FROM DATA.BANKS
 WHERE NAME LIKE '%دير البلح%'
 ORDER BY NO;


-- 6) مهم: شو علاقة DATA.BANK_BRANCH بـ DATA.BANKS؟
-- ربما B_BR_NO + B_NO يعملوا key مركّب
SELECT BB.B_NO, BB.B_BR_NO, BB.B_BR_NAME,
       (SELECT NAME FROM DATA.BANKS WHERE NO = BB.B_NO AND ROWNUM=1) AS MASTER_NAME
  FROM DATA.BANK_BRANCH BB
 WHERE BB.B_NO = 81
 ORDER BY BB.B_BR_NO;
