-- ============================================================
-- Grants & Synonyms لـ DISBURSEMENT_CALC_PKG
-- ============================================================
-- نفّذ هذا الملف بعد إنشاء الجداول (07) والباكج (08, 09)
-- ============================================================

-- ============================================================
-- 1) EXECUTE على الباكج
-- (نفّذ من user GFC_PAK أو SYS)
-- ============================================================
GRANT EXECUTE ON GFC_PAK.DISBURSEMENT_CALC_PKG TO GFC;
GRANT EXECUTE ON GFC_PAK.DISBURSEMENT_CALC_PKG TO PUBLIC;


-- ============================================================
-- 2) Public Synonym للباكج
-- (يسمح باستدعاء الباكج بـ DISBURSEMENT_CALC_PKG بدل GFC_PAK.DISBURSEMENT_CALC_PKG)
-- ============================================================
CREATE OR REPLACE PUBLIC SYNONYM DISBURSEMENT_CALC_PKG FOR GFC_PAK.DISBURSEMENT_CALC_PKG;


-- ============================================================
-- 3) GRANT SELECT على جداول DATA (لـ GFC_PAK)
-- (نفّذ من user DATA أو SYS)
-- غالباً معطاة أصلاً — نفّذ فقط إذا ظهر خطأ ORA-00942 عند تكمبيل الباكج
-- ============================================================
GRANT SELECT ON DATA.PARAMETERSN                TO GFC_PAK;
GRANT SELECT ON DATA.EMPLOYEES_MONTH            TO GFC_PAK;
GRANT SELECT ON DATA.GRADESN                    TO GFC_PAK;
GRANT SELECT ON DATA.WORKN                      TO GFC_PAK;
GRANT SELECT ON DATA.LAST_MAIN                  TO GFC_PAK;
GRANT SELECT ON DATA.EMPLOYEES_DAILY_WORK_DAYS  TO GFC_PAK;
GRANT SELECT ON DATA.CONSTANT                   TO GFC_PAK;
GRANT SELECT ON DATA.ADD_AND_DED                TO GFC_PAK;
GRANT SELECT ON DATA.P_ALLOWNCE                 TO GFC_PAK;
GRANT SELECT ON DATA.CON_SPECIAL                TO GFC_PAK;
GRANT SELECT ON DATA.RELATIVES                  TO GFC_PAK;
GRANT SELECT ON DATA.OVERTIME                   TO GFC_PAK;
GRANT SELECT ON DATA.DEALYEMP                   TO GFC_PAK;
GRANT SELECT ON DATA.ABSENCE                    TO GFC_PAK;
GRANT SELECT ON DATA.USA                        TO GFC_PAK;
GRANT SELECT ON DATA.ADMIN                      TO GFC_PAK;
GRANT SELECT ON DATA.EMPLOYEES                  TO GFC_PAK;


-- ============================================================
-- 4) EXECUTE على الباكجات المستدعاة
-- (نفّذ من user SALARYFORM أو SYS)
-- غالباً معطاة أصلاً
-- ============================================================
GRANT EXECUTE ON SALARYFORM TO GFC_PAK;
GRANT EXECUTE ON USER_PKG   TO GFC_PAK;


-- ============================================================
-- 5) تحقق من نجاح كل شي
-- ============================================================

-- تأكد من وجود الباكج وحالته VALID
-- SELECT OBJECT_NAME, OBJECT_TYPE, STATUS
--   FROM ALL_OBJECTS
--  WHERE OWNER = 'GFC_PAK'
--    AND OBJECT_NAME = 'DISBURSEMENT_CALC_PKG';

-- تأكد من الـ synonyms
-- SELECT SYNONYM_NAME, TABLE_OWNER, TABLE_NAME
--   FROM ALL_SYNONYMS
--  WHERE SYNONYM_NAME LIKE 'PAYMENT_REQ_%CALC_TB'
--     OR SYNONYM_NAME = 'DISBURSEMENT_CALC_PKG';

-- اختبار استدعاء الباكج
-- DECLARE V_MSG VARCHAR2(4000);
-- BEGIN
--   DISBURSEMENT_CALC_PKG.CAL_SALARY_RATE_PART(
--     P_THE_MONTH => 202509,
--     P_NO_FROM   => 1040,
--     P_NO_TO     => 1040,
--     P_RATE      => 34,
--     P_L_VALUE   => 800,
--     P_H_VALUE   => 1800,
--     P_MSG_OUT   => V_MSG
--   );
--   DBMS_OUTPUT.PUT_LINE('Result: ' || V_MSG);
-- END;
-- /

/
