-- ============================================================
-- Diagnostic: لماذا الموظف 1309 يظهر بـ "غير محدد" بدل "فرع دير البلح"؟
-- ============================================================
-- شغّل كل query على حدة وانظر النتيجة
-- ============================================================

-- 1) شو موجود في PAYMENT_BATCH_DETAIL_TB للموظف 1309؟
SELECT BATCH_ID, EMP_NO, ACC_ID, BANK_NO, MASTER_BANK_NO, TOTAL_AMOUNT,
       SNAP_BRANCH_NAME, SNAP_PROVIDER_NAME
  FROM GFC.PAYMENT_BATCH_DETAIL_TB
 WHERE EMP_NO = 1309 AND BATCH_ID = 1;
-- توقع: ACC_ID موجود (= 1328)، SNAP_BRANCH_NAME ربما NULL


-- 2) شو موجود في PAYMENT_ACCOUNTS_TB لحساب 1328؟
SELECT ACC_ID, EMP_NO, PROVIDER_ID, BRANCH_ID, ACCOUNT_NO, IBAN,
       OWNER_NAME, IS_ACTIVE, IS_DEFAULT
  FROM GFC.PAYMENT_ACCOUNTS_TB
 WHERE ACC_ID = 1328;
-- توقع: BRANCH_ID موجود (يشير لسجل في PAYMENT_BANK_BRANCHES_TB)


-- 3) شو موجود في PAYMENT_BANK_BRANCHES_TB للسجل المرتبط؟
SELECT BR.BRANCH_ID, BR.PROVIDER_ID, BR.BRANCH_NAME, BR.PRINT_NO,
       BR.LEGACY_BANK_NO, BR.LEGACY_MASTER, BR.IS_ACTIVE
  FROM GFC.PAYMENT_ACCOUNTS_TB PA
  JOIN GFC.PAYMENT_BANK_BRANCHES_TB BR ON BR.BRANCH_ID = PA.BRANCH_ID
 WHERE PA.ACC_ID = 1328;
-- ⚠️ السؤال الأهم: LEGACY_BANK_NO فاضي ولا فيه قيمة (= 460)؟
--   لو NULL → هاي المشكلة. لازم نعبّيها.


-- 4) شو بترجع GET_BANK_NAME(460)؟
SELECT 460 AS CODE, DISBURSEMENT_PKG.GET_BANK_NAME(460) AS LEGACY_NAME FROM DUAL;
-- توقع: "بنك فلسطين - فرع دير البلح"


-- 5) شو بترجع GET_BANK_NAME لـ BD.BANK_NO الموظف 1309؟
SELECT BD.BANK_NO,
       DISBURSEMENT_PKG.GET_BANK_NAME(BD.BANK_NO) AS HR_BANK_NAME
  FROM GFC.PAYMENT_BATCH_DETAIL_TB BD
 WHERE BD.EMP_NO = 1309 AND BD.BATCH_ID = 1;
-- توقع: "البنك الاسلامي الفلسطيني"


-- 6) الـ view الحالية شو ترجع؟ (لو الـ view محدّثة فعلاً)
SELECT EMP_NO, MASTER_BANK_NAME, BANK_BRANCH_NAME, BANK_NO, ACC_ID
  FROM GFC.PAYMENT_BATCH_BANK_VW
 WHERE EMP_NO = 1309 AND BATCH_ID = 1;


-- 7) متى آخر تحديث للـ view؟
SELECT OBJECT_NAME, OBJECT_TYPE, STATUS, LAST_DDL_TIME
  FROM ALL_OBJECTS
 WHERE OBJECT_NAME IN ('PAYMENT_BATCH_BANK_VW', 'DISBURSEMENT_BATCH_PKG')
   AND OWNER = 'GFC';
-- لازم LAST_DDL_TIME قريبة (آخر دقائق) — لو قديمة، الـ view ما اتحدّثت
-- وSTATUS = VALID


-- 8) كل الـ branches لـ بنك فلسطين عشان نعرف وين دير البلح
SELECT BR.BRANCH_ID, BR.BRANCH_NAME, BR.LEGACY_BANK_NO,
       DISBURSEMENT_PKG.GET_BANK_NAME(BR.LEGACY_BANK_NO) AS LEGACY_NAME,
       PP.PROVIDER_NAME
  FROM GFC.PAYMENT_BANK_BRANCHES_TB BR
  JOIN GFC.PAYMENT_PROVIDERS_TB PP ON PP.PROVIDER_ID = BR.PROVIDER_ID
 WHERE PP.PROVIDER_NAME LIKE '%فلسطين%'
   AND PP.PROVIDER_TYPE = 1   -- بنك (مش محفظة)
 ORDER BY BR.BRANCH_NAME;
-- لازم نشوف صف فيه BRANCH_NAME = "فلسطين دير البلح" مع LEGACY_BANK_NO = 460
-- لو LEGACY_BANK_NO فاضي → هاي المشكلة الجذرية


-- 9) كم فرع في PAYMENT_BANK_BRANCHES_TB ما عندو LEGACY_BANK_NO؟
SELECT COUNT(*) AS BRANCHES_WITHOUT_LEGACY_NO
  FROM GFC.PAYMENT_BANK_BRANCHES_TB
 WHERE LEGACY_BANK_NO IS NULL;
-- لو الرقم كبير → migration ناقص


-- 10) جدول المقابلة بين الـ branches والـ DATA.BANKS
SELECT BR.BRANCH_ID, BR.BRANCH_NAME AS NEW_NAME,
       BR.LEGACY_BANK_NO,
       (SELECT NAME FROM DATA.BANKS WHERE NO = BR.LEGACY_BANK_NO AND ROWNUM=1) AS LEGACY_NAME
  FROM GFC.PAYMENT_BANK_BRANCHES_TB BR
 WHERE BR.LEGACY_BANK_NO IS NOT NULL
 ORDER BY BR.LEGACY_BANK_NO;


-- ============================================================
-- الإصلاح المتوقع (لو LEGACY_BANK_NO ناقص):
-- ============================================================
-- لو شفت في query #3 إنه LEGACY_BANK_NO = NULL لحساب 1328:
--
-- البحث عن الكود الصحيح في DATA.BANKS:
SELECT NO, NAME FROM DATA.BANKS
 WHERE NAME LIKE '%دير البلح%' AND ROWNUM <= 5;
--
-- ثم تحديث:
-- UPDATE GFC.PAYMENT_BANK_BRANCHES_TB
--    SET LEGACY_BANK_NO = 460
--  WHERE BRANCH_ID = <BRANCH_ID من query #3>;
-- COMMIT;
