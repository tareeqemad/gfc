-- ============================================================
-- تشخيص: هل ملفات الإكسل المستوردة محفوظة في GFC_ATTACHMENT_TB؟
-- ============================================================

-- 1. هل الجدول موجود وفيه CATEGORY column؟
SELECT COLUMN_NAME, DATA_TYPE, DATA_LENGTH, NULLABLE
  FROM ALL_TAB_COLUMNS
 WHERE TABLE_NAME = 'GFC_ATTACHMENT_TB'
 ORDER BY COLUMN_ID;

-- 2. كم ملف مستورد محفوظ؟
SELECT COUNT(*) AS TOTAL_IMPORTED
  FROM GFC_ATTACHMENT_TB
 WHERE CATEGORY = 'payment_req_import';

-- 3. تفاصيل آخر 10 ملفات مستوردة (لكل الطلبات)
SELECT ATTACHMENT_ID, IDENTITY AS REQ_ID, CATEGORY, FILE_NAME, FILE_PATH, NOTE, ENTRY_USER
  FROM GFC_ATTACHMENT_TB
 WHERE CATEGORY = 'payment_req_import'
 ORDER BY ATTACHMENT_ID DESC
 FETCH FIRST 10 ROWS ONLY;

-- 4. تحديداً للطلب رقم 5 (PR-00005)
SELECT ATTACHMENT_ID, IDENTITY, CATEGORY, FILE_NAME, FILE_PATH
  FROM GFC_ATTACHMENT_TB
 WHERE IDENTITY = 5
   AND CATEGORY = 'payment_req_import';

-- 5. لو الـ get_list يفلتر بالـ CATEGORY بطريقة خاصة، نتحقق من القيم الموجودة
SELECT DISTINCT CATEGORY, COUNT(*) CNT
  FROM GFC_ATTACHMENT_TB
 WHERE CATEGORY LIKE 'payment%'
 GROUP BY CATEGORY;
