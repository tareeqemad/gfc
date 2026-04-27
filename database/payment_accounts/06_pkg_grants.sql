-- ============================================================
-- Package Grants + Public Synonym
-- ============================================================
-- لازم يتنفّذ بعد 03_pkg_spec.sql + 04_pkg_body.sql
-- يسمح لكل المستخدمين (PHP app user) باستدعاء الباكج
-- ============================================================

-- Grant execute على الباكج
GRANT EXECUTE ON GFC_PAK.PAYMENT_ACCOUNTS_PKG TO PUBLIC;

-- Public Synonym (يسمح بالاستدعاء بـ PAYMENT_ACCOUNTS_PKG بدل GFC_PAK.PAYMENT_ACCOUNTS_PKG)
CREATE OR REPLACE PUBLIC SYNONYM PAYMENT_ACCOUNTS_PKG FOR GFC_PAK.PAYMENT_ACCOUNTS_PKG;

-- ============================================================
-- GRANT SELECT TO PUBLIC على الجداول
-- لازم لأن PHP يتصل بـ user مختلف (telbawab وأمثاله)
-- وعند fetch من cursor (حتى مع definer rights للـ pkg) يحتاج SELECT على الجداول
-- ============================================================
GRANT SELECT ON GFC.PAYMENT_PROVIDERS_TB        TO PUBLIC;
GRANT SELECT ON GFC.PAYMENT_BENEFICIARIES_TB    TO PUBLIC;
GRANT SELECT ON GFC.PAYMENT_ACCOUNTS_TB         TO PUBLIC;
GRANT SELECT ON GFC.PAYMENT_BANK_BRANCHES_TB    TO PUBLIC;
GRANT SELECT ON GFC.PAYMENT_REQ_DETAIL_SPLIT_TB TO PUBLIC;


-- ============================================================
-- تحقق
-- ============================================================
-- هل الـ EXECUTE مُمنوح؟
SELECT GRANTEE, PRIVILEGE
  FROM ALL_TAB_PRIVS
 WHERE TABLE_NAME = 'PAYMENT_ACCOUNTS_PKG'
   AND OWNER      = 'GFC_PAK';

-- هل الـ Synonym موجود؟
SELECT SYNONYM_NAME, TABLE_OWNER, TABLE_NAME
  FROM ALL_SYNONYMS
 WHERE SYNONYM_NAME = 'PAYMENT_ACCOUNTS_PKG'
   AND OWNER        = 'PUBLIC';
