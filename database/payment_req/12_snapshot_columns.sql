-- ============================================================
-- Snapshot Columns على PAYMENT_BATCH_DETAIL_TB
-- الفكرة: نخزّن صورة لحظية للبيانات الحساسة وقت الاحتساب
-- بحيث لو أي شي تغيّر في PAYMENT_ACCOUNTS_TB أو DATA.EMPLOYEES بعدها،
-- الـ batch القديم يبقى يطلع بنفس البيانات اللي صُرف عليها فعلاً.
-- ============================================================

-- 1) إضافة الأعمدة
ALTER TABLE GFC.PAYMENT_BATCH_DETAIL_TB ADD (
    SNAP_IBAN          VARCHAR2(50),
    SNAP_ACCOUNT_NO    VARCHAR2(50),     -- رقم الحساب أو رقم المحفظة
    SNAP_OWNER_NAME    VARCHAR2(200),    -- صاحب الحساب الفعلي (موظف/زوجة/...)
    SNAP_OWNER_ID_NO   VARCHAR2(20),     -- هوية صاحب الحساب
    SNAP_PROVIDER_NAME VARCHAR2(200),    -- اسم البنك/المحفظة (بنك فلسطين، PalPay...)
    SNAP_BRANCH_NAME   VARCHAR2(200)     -- اسم الفرع
);

COMMENT ON COLUMN GFC.PAYMENT_BATCH_DETAIL_TB.SNAP_IBAN IS
    'IBAN وقت الاحتساب — صورة محنّطة ما تتأثر بأي تعديل لاحق على PAYMENT_ACCOUNTS_TB';
COMMENT ON COLUMN GFC.PAYMENT_BATCH_DETAIL_TB.SNAP_OWNER_NAME IS
    'اسم صاحب الحساب وقت الاحتساب — للحفاظ على audit trail';

-- 2) Backfill — للـ batches الموجودة (قبل هذا التحديث)
--    نملأ snapshots من البيانات الحالية (أفضل من ترك NULL)
--    لو ACC_ID موجود → خذ من PA، وإلا → خذ من EMPLOYEES
UPDATE GFC.PAYMENT_BATCH_DETAIL_TB BD SET
    SNAP_IBAN = NVL(
        (SELECT PA.IBAN FROM GFC.PAYMENT_ACCOUNTS_TB PA WHERE PA.ACC_ID = BD.ACC_ID),
        (SELECT E.IBAN  FROM DATA.EMPLOYEES E         WHERE E.NO     = BD.EMP_NO)
    ),
    SNAP_ACCOUNT_NO = NVL(
        (SELECT NVL(PA.ACCOUNT_NO, PA.WALLET_NUMBER) FROM GFC.PAYMENT_ACCOUNTS_TB PA WHERE PA.ACC_ID = BD.ACC_ID),
        (SELECT E.ACCOUNT_BANK_EMAIL                 FROM DATA.EMPLOYEES E         WHERE E.NO     = BD.EMP_NO)
    ),
    SNAP_OWNER_NAME = NVL(
        (SELECT PA.OWNER_NAME FROM GFC.PAYMENT_ACCOUNTS_TB PA WHERE PA.ACC_ID = BD.ACC_ID),
        (SELECT E.NAME        FROM DATA.EMPLOYEES E         WHERE E.NO     = BD.EMP_NO)
    ),
    SNAP_OWNER_ID_NO = NVL(
        (SELECT PA.OWNER_ID_NO FROM GFC.PAYMENT_ACCOUNTS_TB PA WHERE PA.ACC_ID = BD.ACC_ID),
        (SELECT TO_CHAR(E.ID)  FROM DATA.EMPLOYEES E         WHERE E.NO     = BD.EMP_NO)
    ),
    SNAP_PROVIDER_NAME = NVL(
        (SELECT PP.PROVIDER_NAME
           FROM GFC.PAYMENT_ACCOUNTS_TB PA
           JOIN GFC.PAYMENT_PROVIDERS_TB PP ON PP.PROVIDER_ID = PA.PROVIDER_ID
          WHERE PA.ACC_ID = BD.ACC_ID),
        (SELECT MB.B_NAME FROM DATA.MASTER_BANKS_EMAIL MB WHERE MB.B_NO = BD.MASTER_BANK_NO)
    ),
    SNAP_BRANCH_NAME = (
        SELECT BR.BRANCH_NAME
          FROM GFC.PAYMENT_ACCOUNTS_TB PA
          JOIN GFC.PAYMENT_BANK_BRANCHES_TB BR ON BR.BRANCH_ID = PA.BRANCH_ID
         WHERE PA.ACC_ID = BD.ACC_ID
    )
 WHERE SNAP_IBAN IS NULL;       -- فقط السجلات اللي ما عندها snapshot

COMMIT;

-- ============================================================
-- التحقق:
-- ============================================================
-- SELECT BATCH_DETAIL_ID, EMP_NO, ACC_ID,
--        SNAP_IBAN, SNAP_OWNER_NAME, SNAP_OWNER_ID_NO,
--        SNAP_PROVIDER_NAME, SNAP_BRANCH_NAME, TOTAL_AMOUNT
--   FROM GFC.PAYMENT_BATCH_DETAIL_TB
--   WHERE BATCH_ID = (SELECT MAX(BATCH_ID) FROM GFC.PAYMENT_BATCH_TB);
