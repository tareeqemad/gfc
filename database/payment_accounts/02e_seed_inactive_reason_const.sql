-- ============================================================
-- Seed: ثابت INACTIVE_REASON (TB_NO=545) في GFC.CONSTANT_DETAILS_TB
-- ============================================================
-- الهدف: استخدام SETTING_PKG.CONSTANT_DETAILS_TB_GET_NAME(545, REASON)
--        بدل CASE statements المتكررة في كل procedure.
-- ============================================================

DECLARE
  V NUMBER;
  PROCEDURE upsert_const(p_no NUMBER, p_name VARCHAR2) IS
  BEGIN
    SELECT COUNT(1) INTO V FROM GFC.CONSTANT_DETAILS_TB
     WHERE TB_NO = 545 AND CON_NO = p_no;
    IF V = 0 THEN
      INSERT INTO GFC.CONSTANT_DETAILS_TB (TB_NO, CON_NO, CON_NAME)
      VALUES (545, p_no, p_name);
    ELSE
      UPDATE GFC.CONSTANT_DETAILS_TB SET CON_NAME = p_name
       WHERE TB_NO = 545 AND CON_NO = p_no;
    END IF;
  END;
BEGIN
  -- master ثابت (لو مش موجود)
  SELECT COUNT(1) INTO V FROM GFC.CONSTANT_TB WHERE TB_NO = 545;
  IF V = 0 THEN
    INSERT INTO GFC.CONSTANT_TB (TB_NO, TB_NAME) VALUES (545, 'سبب إيقاف حساب الصرف');
  END IF;

  -- التفاصيل
  upsert_const(1, 'تقاعد');
  upsert_const(2, 'وفاة');
  upsert_const(3, 'فصل');
  upsert_const(4, 'تجميد');
  upsert_const(5, 'تحويل');
  upsert_const(9, 'أخرى');
END;
/
COMMIT;

-- ============================================================
-- تحقق
-- ============================================================
SELECT TB_NO, CON_NO, CON_NAME
  FROM GFC.CONSTANT_DETAILS_TB
 WHERE TB_NO = 545
 ORDER BY CON_NO;
