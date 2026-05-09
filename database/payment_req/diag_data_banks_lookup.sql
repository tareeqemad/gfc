-- ============================================================
-- اكتشاف: ما هي أكواد DATA.BANKS لكل فرع في PAYMENT_BANK_BRANCHES_TB؟
-- ============================================================

-- 1) ما الموجود في DATA.BANKS لـ بنك فلسطين؟
SELECT NO, NAME FROM DATA.BANKS
 WHERE NAME LIKE '%فلسطين%'
   AND NAME NOT LIKE '%الاسلامي%'
   AND NAME NOT LIKE '%الاستثمار%'
   AND NAME NOT LIKE '%العربي%'
   AND NAME NOT LIKE '%القدس%'
 ORDER BY NO;
-- توقع نشوف صفوف مثل:
-- 81  بنك فلسطين
-- ??? بنك فلسطين - الفرع الرئيسي
-- ??? بنك فلسطين - فرع دير البلح
-- ??? بنك فلسطين - فرع جباليا
-- ...


-- 2) خاصة دير البلح
SELECT NO, NAME FROM DATA.BANKS
 WHERE NAME LIKE '%دير البلح%';


-- 3) جدول مقابلة (لكل فرع جديد، نلاقي الكود في DATA.BANKS)
SELECT BR.BRANCH_ID,
       BR.BRANCH_NAME           AS NEW_NAME,
       BR.LEGACY_BANK_NO        AS CURRENT_LEGACY_NO,
       BR.LEGACY_MASTER         AS CURRENT_LEGACY_MASTER,
       PP.PROVIDER_NAME,
       (SELECT MIN(B.NO) FROM DATA.BANKS B
         WHERE B.NAME LIKE '%' || PP.PROVIDER_NAME || '%'
           AND B.NAME LIKE '%' || REGEXP_REPLACE(BR.BRANCH_NAME, '^[^ ]+\s+', '') || '%')
                                AS PROPER_DATA_BANKS_NO,
       (SELECT MIN(B.NAME) FROM DATA.BANKS B
         WHERE B.NAME LIKE '%' || PP.PROVIDER_NAME || '%'
           AND B.NAME LIKE '%' || REGEXP_REPLACE(BR.BRANCH_NAME, '^[^ ]+\s+', '') || '%')
                                AS PROPER_DATA_BANKS_NAME
  FROM GFC.PAYMENT_BANK_BRANCHES_TB BR
  JOIN GFC.PAYMENT_PROVIDERS_TB PP ON PP.PROVIDER_ID = BR.PROVIDER_ID
 WHERE PP.PROVIDER_TYPE = 1   -- بنوك فقط
 ORDER BY PP.PROVIDER_NAME, BR.BRANCH_NAME;
-- النتائج:
-- لو PROPER_DATA_BANKS_NO معبّى = الكود الصح اللي لازم نحط في LEGACY_BANK_NO
-- لو NULL = الفرع مش موجود في DATA.BANKS بنفس الاسم (نحتاج logic مختلف)
