-- ============================================================
-- تشخيص: لماذا الكروت تطلّع مبلغ أكبر من الجدول؟
-- (الكروت 769,011.80 vs الجدول 748,797.80 → فرق 20,214)
-- ============================================================
-- 💡 غيّر BATCH_ID لرقم الدفعة الحقيقي (مثلاً PB-00006 → 6)

-- ⬇️⬇️⬇️ BATCH_ID هنا
DEFINE B_ID = 6;

-- ============================================================
-- 1) المبلغ الحقيقي للدفعة من جدول الـ master
-- ============================================================
SELECT 'BATCH MASTER (truth)' AS LABEL,
       BATCH_ID, BATCH_NO,
       EMP_COUNT, TOTAL_AMOUNT
  FROM GFC.PAYMENT_BATCH_TB
 WHERE BATCH_ID = &B_ID;


-- ============================================================
-- 2) المبلغ الفعلي من PAYMENT_BATCH_DETAIL_TB (مجموع كل الصفوف)
-- ============================================================
SELECT 'BD ALL ROWS SUM' AS LABEL,
       COUNT(*)                AS BD_ROWS,
       COUNT(DISTINCT EMP_NO)  AS UNIQUE_EMPS,
       SUM(TOTAL_AMOUNT)       AS BD_SUM
  FROM GFC.PAYMENT_BATCH_DETAIL_TB
 WHERE BATCH_ID = &B_ID;


-- ============================================================
-- 3) محاكاة الـ "جدول العلوي" (BATCH_BANK_SUMMARY_GET)
-- ============================================================
WITH BD_FLAT AS (
    SELECT BD.EMP_NO, BD.ACC_ID, BD.TOTAL_AMOUNT,
           NVL(BD.SNAP_PROVIDER_NAME, DISBURSEMENT_PKG.GET_MASTER_BANK_NAME(BD.EMP_NO)) AS PROVIDER_NAME
      FROM GFC.PAYMENT_BATCH_DETAIL_TB BD
      LEFT JOIN GFC.PAYMENT_ACCOUNTS_TB    PA ON PA.ACC_ID      = BD.ACC_ID
      LEFT JOIN GFC.PAYMENT_PROVIDERS_TB   PP ON PP.PROVIDER_ID = PA.PROVIDER_ID
     WHERE BD.BATCH_ID = &B_ID
)
SELECT 'TOP TABLE GROUP-BY-DEST' AS LABEL,
       PROVIDER_NAME,
       COUNT(DISTINCT EMP_NO)  AS EMPS,
       COUNT(*)                AS TRX,
       SUM(TOTAL_AMOUNT)       AS TOTAL
  FROM BD_FLAT
 GROUP BY PROVIDER_NAME
 ORDER BY PROVIDER_NAME;


-- ============================================================
-- 4) محاكاة الـ "كروت" (BATCH_HISTORY_GET بعد PHP grouping)
-- ============================================================
SELECT 'CARDS GROUP-BY-MASTER' AS LABEL,
       NVL(DISBURSEMENT_PKG.GET_MASTER_BANK_NAME(BD.EMP_NO), '— غير محدد —') AS MASTER_BANK,
       COUNT(DISTINCT BD.EMP_NO) AS EMPS,
       SUM(BD.TOTAL_AMOUNT)      AS TOTAL
  FROM GFC.PAYMENT_BATCH_DETAIL_TB BD
 WHERE BD.BATCH_ID = &B_ID
 GROUP BY NVL(DISBURSEMENT_PKG.GET_MASTER_BANK_NAME(BD.EMP_NO), '— غير محدد —')
 ORDER BY MASTER_BANK;


-- ============================================================
-- 5) BATCH_HISTORY_GET نفسها (شو ترجع للـ PHP)
-- نُنفّذها ونفلتر العمود اللي يهمنا
-- ============================================================
DECLARE
  V_CUR SYS_REFCURSOR;
  V_MSG VARCHAR2(4000);
  V_BATCH_ID NUMBER;
  V_EMP_NO NUMBER;
  V_EMP_NAME VARCHAR2(200);
  V_MASTER VARCHAR2(200);
  V_TOTAL NUMBER;
  V_BANK_NAME VARCHAR2(500);
  V_BANK_NO NUMBER;
  V_MASTER_NO NUMBER;
  V_BENEF NUMBER; V_ACTIVE NUMBER; V_INACTIVE NUMBER; V_SPLIT NUMBER;
  V_REQS VARCHAR2(500);
  V_T1 NUMBER; V_T2 NUMBER; V_T3 NUMBER; V_T4 NUMBER;
  V_BRANCH VARCHAR2(200); V_DETAIL_ID NUMBER; V_BR NUMBER;
  V_GRAND_TOTAL NUMBER := 0;
  V_GRAND_CNT NUMBER := 0;
BEGIN
  GFC_PAK.DISBURSEMENT_BATCH_PKG.BATCH_HISTORY_GET(&B_ID, V_CUR, V_MSG);
  LOOP
    FETCH V_CUR INTO V_DETAIL_ID, V_BATCH_ID, V_EMP_NO, V_EMP_NAME, V_BRANCH, V_BANK_NO,
                     V_BANK_NAME, V_MASTER, V_TOTAL, V_MASTER_NO, V_BR,
                     V_BENEF, V_ACTIVE, V_INACTIVE, V_SPLIT, V_REQS,
                     V_T1, V_T2, V_T3, V_T4;
    EXIT WHEN V_CUR%NOTFOUND;
    V_GRAND_TOTAL := V_GRAND_TOTAL + V_TOTAL;
    V_GRAND_CNT := V_GRAND_CNT + 1;
  END LOOP;
  CLOSE V_CUR;
  DBMS_OUTPUT.PUT_LINE('BATCH_HISTORY_GET → emps=' || V_GRAND_CNT || ' total=' || V_GRAND_TOTAL);
END;
/


-- ============================================================
-- 6) أهم استعلام: هل في موظف واحد عنده TOTAL_AMOUNT أكبر من الـ REQ_AMOUNT الفعلي؟
-- (يكشف لو الـ split خلق حركات مدبّلة)
-- ============================================================
WITH EMP_BD AS (
    SELECT BD.EMP_NO, SUM(BD.TOTAL_AMOUNT) AS BD_TOTAL
      FROM GFC.PAYMENT_BATCH_DETAIL_TB BD
     WHERE BD.BATCH_ID = &B_ID
     GROUP BY BD.EMP_NO
),
EMP_REQ AS (
    SELECT D.EMP_NO, SUM(D.REQ_AMOUNT) AS REQ_TOTAL
      FROM GFC.PAYMENT_REQ_DETAIL_TB D
      JOIN GFC.PAYMENT_BATCH_LINK_TB L ON L.DETAIL_ID = D.DETAIL_ID AND L.BATCH_ID = &B_ID
     GROUP BY D.EMP_NO
)
SELECT 'DETECT BD_TOTAL > REQ_TOTAL' AS LABEL,
       EB.EMP_NO,
       EMP_PKG.GET_EMP_NAME(EB.EMP_NO) AS EMP_NAME,
       EB.BD_TOTAL,
       ER.REQ_TOTAL,
       (EB.BD_TOTAL - ER.REQ_TOTAL) AS DIFF
  FROM EMP_BD EB
  LEFT JOIN EMP_REQ ER ON ER.EMP_NO = EB.EMP_NO
 WHERE ABS(EB.BD_TOTAL - NVL(ER.REQ_TOTAL, 0)) > 0.01
 ORDER BY DIFF DESC, EB.EMP_NO;
-- ✅ متوقع: 0 صفوف (يعني BD_total = REQ_total لكل موظف)
-- ❌ لو طلع صفوف: التوزيع كتب مبلغ مختلف عن الـ REQ_AMOUNT الأصلي


-- ============================================================
-- 7) صفوف BD مشبوهة (ACC_ID = NULL أو TOTAL_AMOUNT = 0)
-- ============================================================
SELECT 'SUSPECT BD ROWS' AS LABEL,
       BATCH_DETAIL_ID, EMP_NO, ACC_ID, BANK_NO, MASTER_BANK_NO, TOTAL_AMOUNT,
       SNAP_PROVIDER_NAME, SNAP_BRANCH_NAME, SNAP_OWNER_NAME
  FROM GFC.PAYMENT_BATCH_DETAIL_TB
 WHERE BATCH_ID = &B_ID
   AND (TOTAL_AMOUNT <= 0 OR ACC_ID IS NULL)
 ORDER BY EMP_NO;
