-- ============================================================
-- ثوابت حالات الدفعات — TB_NO = 542
-- ============================================================
-- 0 = محتسبة     (جاهزة للصرف، تقبل تعديل/تحديث/فك)
-- 2 = مصروفة     (تم تنفيذ الصرف، مغلقة للتعديل)
-- 9 = ملغاة      (تم فك احتسابها، ظاهرة للتسلسل والـ audit)
-- ============================================================

-- إنشاء/تحديث ماستر الجدول (CONSTANT_TB)
DECLARE V NUMBER;
BEGIN
  SELECT COUNT(1) INTO V FROM GFC.CONSTANT_TB WHERE TB_NO = 542;
  IF V = 0 THEN
    INSERT INTO GFC.CONSTANT_TB (TB_NO, TB_NAME) VALUES (542, 'حالات دفعات الصرف البنكي');
  ELSE
    UPDATE GFC.CONSTANT_TB SET TB_NAME = 'حالات دفعات الصرف البنكي' WHERE TB_NO = 542;
  END IF;
END;
/

-- بذر/تحديث القيم (CONSTANT_DETAILS_TB)
DECLARE V NUMBER;
BEGIN
  -- 0 = محتسبة
  SELECT COUNT(1) INTO V FROM GFC.CONSTANT_DETAILS_TB WHERE TB_NO = 542 AND CON_NO = 0;
  IF V = 0 THEN
    INSERT INTO GFC.CONSTANT_DETAILS_TB (TB_NO, CON_NO, CON_NAME) VALUES (542, 0, 'محتسبة');
  ELSE
    UPDATE GFC.CONSTANT_DETAILS_TB SET CON_NAME = 'محتسبة' WHERE TB_NO = 542 AND CON_NO = 0;
  END IF;

  -- 2 = مصروفة
  SELECT COUNT(1) INTO V FROM GFC.CONSTANT_DETAILS_TB WHERE TB_NO = 542 AND CON_NO = 2;
  IF V = 0 THEN
    INSERT INTO GFC.CONSTANT_DETAILS_TB (TB_NO, CON_NO, CON_NAME) VALUES (542, 2, 'مصروفة');
  ELSE
    UPDATE GFC.CONSTANT_DETAILS_TB SET CON_NAME = 'مصروفة' WHERE TB_NO = 542 AND CON_NO = 2;
  END IF;

  -- 9 = ملغاة
  SELECT COUNT(1) INTO V FROM GFC.CONSTANT_DETAILS_TB WHERE TB_NO = 542 AND CON_NO = 9;
  IF V = 0 THEN
    INSERT INTO GFC.CONSTANT_DETAILS_TB (TB_NO, CON_NO, CON_NAME) VALUES (542, 9, 'ملغاة');
  ELSE
    UPDATE GFC.CONSTANT_DETAILS_TB SET CON_NAME = 'ملغاة' WHERE TB_NO = 542 AND CON_NO = 9;
  END IF;
END;
/
COMMIT;

-- تحقق
SELECT D.TB_NO, D.CON_NO, D.CON_NAME
  FROM GFC.CONSTANT_DETAILS_TB D
 WHERE D.TB_NO = 542
 ORDER BY D.CON_NO;
