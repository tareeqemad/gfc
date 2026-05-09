-- ============================================================
-- Payment Accounts Module — DDL
-- ============================================================
-- الموديول يدير حسابات الصرف (بنكية + محافظ إلكترونية)
-- يدعم:
--   - موظف بحساب واحد أو أكثر (بنك + محفظة أو كلاهما)
--   - حسابات للمستفيدين (ورثة / زوجات / أبناء)
--   - توزيع المبلغ المعتمد على حسابات متعددة (نسبة / مبلغ / كامل)
--   - تطبيق الحساب حسب حالة الموظف (فعّال / موقوف / متوفى)
-- ============================================================


-- ============================================================
-- 1) PAYMENT_PROVIDERS_TB
-- مزوّدو الخدمة (بنوك + محافظ إلكترونية)
-- موحّدون في جدول واحد لتبسيط التعامل في جميع الأماكن
-- ============================================================
CREATE TABLE GFC.PAYMENT_PROVIDERS_TB (
    PROVIDER_ID     NUMBER              NOT NULL,
    PROVIDER_TYPE   NUMBER(1)           NOT NULL,   -- 1=بنك, 2=محفظة إلكترونية
    PROVIDER_NAME   VARCHAR2(100)       NOT NULL,   -- الاسم الكامل (بنك فلسطين)
    PROVIDER_CODE   VARCHAR2(20)        NOT NULL,   -- رمز قصير (BANK89, PALPAY, JAWWAL)
    LEGACY_BANK_NO  NUMBER,                         -- ربط مع DATA.BANKS.NO (للبنوك الخمسة القديمة)
    EXPORT_FORMAT   NUMBER(1),                      -- 1=بسيط, 2=هيدر, 3=PalPay, 4=JawwalPay (يُحدَّد لاحقاً)
    -- بيانات حساب الشركة في هذا البنك (مصدر التحويل)
    COMPANY_ACCOUNT_NO  VARCHAR2(50),               -- رقم حساب الشركة (MASTER_BANKS_EMAIL.ACCOUNT_NO)
    COMPANY_ACCOUNT_ID  VARCHAR2(50),               -- رقم مرجعي للحساب (MASTER_BANKS_EMAIL.ACCOUNT_ID)
    COMPANY_IBAN        VARCHAR2(50),               -- IBAN الحقيقي للتحويل (يُملأ يدوياً: PS31PALS...)
    -- متطلبات الحساب لهذا المزود
    REQUIRES_IBAN   NUMBER(1) DEFAULT 1,            -- هل يتطلب IBAN؟ (البنوك نعم، المحافظ لا)
    REQUIRES_ID     NUMBER(1) DEFAULT 1,            -- هل يتطلب رقم هوية؟
    REQUIRES_PHONE  NUMBER(1) DEFAULT 0,            -- هل يتطلب رقم جوال؟ (المحافظ نعم)
    -- Status
    IS_ACTIVE       NUMBER(1) DEFAULT 1,
    SORT_ORDER      NUMBER    DEFAULT 0,
    NOTES           VARCHAR2(500),
    -- Audit
    ENTRY_USER      NUMBER,
    ENTRY_DATE      DATE      DEFAULT SYSDATE,
    UPDATE_USER     NUMBER,
    UPDATE_DATE     DATE,
    CONSTRAINT PK_PAY_PROV     PRIMARY KEY (PROVIDER_ID),
    CONSTRAINT UK_PAY_PROV_CD  UNIQUE (PROVIDER_CODE),
    CONSTRAINT CK_PAY_PROV_TP  CHECK (PROVIDER_TYPE IN (1, 2))
);

CREATE SEQUENCE GFC_PAK.PAYMENT_PROVIDERS_SEQ START WITH 100 INCREMENT BY 1 NOCACHE;
CREATE OR REPLACE PUBLIC SYNONYM PAYMENT_PROVIDERS_TB  FOR GFC.PAYMENT_PROVIDERS_TB;
CREATE OR REPLACE PUBLIC SYNONYM PAYMENT_PROVIDERS_SEQ FOR GFC_PAK.PAYMENT_PROVIDERS_SEQ;


-- ============================================================
-- 2) PAYMENT_BENEFICIARIES_TB
-- المستفيدون من مستحقات الموظف (ورثة / زوجات / أبناء)
-- يُستخدم عند الوفاة أو وقف الراتب
-- ============================================================
CREATE TABLE GFC.PAYMENT_BENEFICIARIES_TB (
    BENEFICIARY_ID  NUMBER              NOT NULL,
    EMP_NO          NUMBER              NOT NULL,   -- الموظف الأصلي صاحب المستحقات
    REL_TYPE        NUMBER(2)           NOT NULL,   -- 1=زوجة, 2=ابن, 3=بنت, 4=أب, 5=أم, 9=وريث آخر
    ID_NO           VARCHAR2(20)        NOT NULL,   -- هوية المستفيد
    NAME            VARCHAR2(200)       NOT NULL,
    PHONE           VARCHAR2(20),
    PCT_SHARE       NUMBER(5,2),                    -- نسبة الإرث (اختيارية - للمعلومية)
    FROM_DATE       DATE      DEFAULT SYSDATE,      -- بداية الاستحقاق
    TO_DATE         DATE,                           -- نهاية الاستحقاق (NULL = مفتوح)
    STATUS          NUMBER(1) DEFAULT 1,            -- 1=نشط, 0=موقوف, 9=محذوف
    NOTES           VARCHAR2(500),
    -- Audit
    ENTRY_USER      NUMBER,
    ENTRY_DATE      DATE      DEFAULT SYSDATE,
    UPDATE_USER     NUMBER,
    UPDATE_DATE     DATE,
    CONSTRAINT PK_PAY_BENEF    PRIMARY KEY (BENEFICIARY_ID),
    CONSTRAINT CK_PAY_BENEF_ST CHECK (STATUS IN (0, 1, 9))
);

CREATE INDEX GFC.IDX_PAY_BENEF_EMP ON GFC.PAYMENT_BENEFICIARIES_TB (EMP_NO, STATUS);
CREATE SEQUENCE GFC_PAK.PAYMENT_BENEFICIARIES_SEQ START WITH 1 INCREMENT BY 1 NOCACHE;
CREATE OR REPLACE PUBLIC SYNONYM PAYMENT_BENEFICIARIES_TB  FOR GFC.PAYMENT_BENEFICIARIES_TB;
CREATE OR REPLACE PUBLIC SYNONYM PAYMENT_BENEFICIARIES_SEQ FOR GFC_PAK.PAYMENT_BENEFICIARIES_SEQ;


-- ============================================================
-- 3) PAYMENT_ACCOUNTS_TB
-- حسابات الصرف (بنكية + محافظ) للموظف أو مستفيديه
-- ============================================================
CREATE TABLE GFC.PAYMENT_ACCOUNTS_TB (
    ACC_ID          NUMBER              NOT NULL,
    EMP_NO          NUMBER              NOT NULL,   -- الموظف صاحب المستحقات
    BENEFICIARY_ID  NUMBER,                         -- إن كان الحساب لمستفيد (NULL = الموظف نفسه)
    PROVIDER_ID     NUMBER              NOT NULL,   -- بنك أو محفظة من PAYMENT_PROVIDERS_TB
    -- بيانات البنك (لو PROVIDER_TYPE=1)
    BRANCH_ID       NUMBER,                         -- FK لـ PAYMENT_BANK_BRANCHES_TB
    ACCOUNT_NO      VARCHAR2(50),
    IBAN            VARCHAR2(50),
    -- بيانات المحفظة (لو PROVIDER_TYPE=2)
    WALLET_NUMBER   VARCHAR2(50),                   -- عادة = رقم الجوال
    -- صاحب الحساب (قد يختلف عن الموظف)
    OWNER_ID_NO     VARCHAR2(20),                   -- هوية صاحب الحساب
    OWNER_NAME      VARCHAR2(200),                  -- اسم صاحب الحساب
    OWNER_PHONE     VARCHAR2(20),
    -- منطق التوزيع
    IS_DEFAULT      NUMBER(1) DEFAULT 0,            -- 1=الحساب الافتراضي (لو لا يوجد split)
    SPLIT_TYPE      NUMBER(1) DEFAULT 3,            -- 1=نسبة مئوية, 2=مبلغ ثابت, 3=كامل المتبقي
    SPLIT_VALUE     NUMBER(14,2),                   -- قيمة النسبة/المبلغ حسب SPLIT_TYPE
    SPLIT_ORDER     NUMBER    DEFAULT 0,            -- ترتيب الصرف (1 الأول، 2 الثاني، ...)
    -- صلاحية الحساب:
    -- IS_ACTIVE=1 → الحساب يُستخدم للصرف
    -- IS_ACTIVE=0 → موقوف (مثلاً بعد وفاة الموظف، أو تجميد الحساب، أو انتقال لحساب آخر)
    -- المحاسبة تُوقف الحساب الشخصي للمتوفى، وتُفعّل حسابات الورثة (BENEFICIARY_ID)
    IS_ACTIVE       NUMBER(1) DEFAULT 1,            -- 0=موقوف
    -- سبب الإيقاف (اختياري — للمتابعة)
    INACTIVE_REASON NUMBER,                         -- 1=تقاعد, 2=وفاة, 3=فصل, 4=تجميد, 5=تحويل, 9=أخرى
    INACTIVE_FROM_MONTH NUMBER(6),                  -- 🆕 شهر بدء الإيقاف (YYYYMM) — مهم للوفاة/التجميد
    FROM_DATE       DATE      DEFAULT SYSDATE,
    TO_DATE         DATE,
    STATUS          NUMBER(1) DEFAULT 1,            -- 1=نشط, 9=محذوف
    NOTES           VARCHAR2(500),
    -- Audit
    ENTRY_USER      NUMBER,
    ENTRY_DATE      DATE      DEFAULT SYSDATE,
    UPDATE_USER     NUMBER,
    UPDATE_DATE     DATE,
    CONSTRAINT PK_PAY_ACC      PRIMARY KEY (ACC_ID),
    CONSTRAINT FK_PAY_ACC_PROV FOREIGN KEY (PROVIDER_ID)    REFERENCES GFC.PAYMENT_PROVIDERS_TB (PROVIDER_ID),
    CONSTRAINT FK_PAY_ACC_BENF FOREIGN KEY (BENEFICIARY_ID) REFERENCES GFC.PAYMENT_BENEFICIARIES_TB (BENEFICIARY_ID),
    -- FK_PAY_ACC_BR يُضاف لاحقاً عبر ALTER TABLE (بعد إنشاء PAYMENT_BANK_BRANCHES_TB)
    CONSTRAINT CK_PAY_ACC_SPLT CHECK (SPLIT_TYPE IN (1, 2, 3)),
    CONSTRAINT CK_PAY_ACC_ST   CHECK (STATUS IN (1, 9))
);

CREATE INDEX GFC.IDX_PAY_ACC_EMP   ON GFC.PAYMENT_ACCOUNTS_TB (EMP_NO, STATUS);
CREATE INDEX GFC.IDX_PAY_ACC_PROV  ON GFC.PAYMENT_ACCOUNTS_TB (PROVIDER_ID);
CREATE INDEX GFC.IDX_PAY_ACC_BENF  ON GFC.PAYMENT_ACCOUNTS_TB (BENEFICIARY_ID);
CREATE INDEX GFC.IDX_PAY_ACC_BR    ON GFC.PAYMENT_ACCOUNTS_TB (BRANCH_ID);
CREATE SEQUENCE GFC_PAK.PAYMENT_ACCOUNTS_SEQ START WITH 1 INCREMENT BY 1 NOCACHE;
CREATE OR REPLACE PUBLIC SYNONYM PAYMENT_ACCOUNTS_TB  FOR GFC.PAYMENT_ACCOUNTS_TB;
CREATE OR REPLACE PUBLIC SYNONYM PAYMENT_ACCOUNTS_SEQ FOR GFC_PAK.PAYMENT_ACCOUNTS_SEQ;


-- ============================================================
-- 3b) PAYMENT_BANK_BRANCHES_TB
-- فروع البنوك (يُنقل من DATA.BANKS عبر migration)
-- المحافظ الإلكترونية لا تحتاج فروع
-- ============================================================
CREATE TABLE GFC.PAYMENT_BANK_BRANCHES_TB (
    BRANCH_ID       NUMBER              NOT NULL,
    PROVIDER_ID     NUMBER,                         -- FK للبنك الرئيسي (NULL = لم يتم الربط)
    BRANCH_NAME     VARCHAR2(200)       NOT NULL,
    PRINT_NO        NUMBER,                         -- رقم للطباعة في ملف البنك (من B_NO_PRINT)
    -- بيانات تواصل إضافية (اختيارية)
    ADDRESS         VARCHAR2(500),
    PHONE1          VARCHAR2(50),
    PHONE2          VARCHAR2(50),
    FAX             VARCHAR2(50),
    CONTACTS        VARCHAR2(200),
    -- ربط مع الجدول الأصلي
    LEGACY_BANK_NO  NUMBER,                         -- DATA.BANK_BRANCH.B_BR_NO
    LEGACY_MASTER   NUMBER,                         -- DATA.BANK_BRANCH.B_NO (البنك الرئيسي)
    IS_ACTIVE       NUMBER(1) DEFAULT 1,
    STATUS          NUMBER(1) DEFAULT 1,
    NOTES           VARCHAR2(500),
    ENTRY_USER      NUMBER,
    ENTRY_DATE      DATE      DEFAULT SYSDATE,
    UPDATE_USER     NUMBER,
    UPDATE_DATE     DATE,
    CONSTRAINT PK_PAY_BRANCH      PRIMARY KEY (BRANCH_ID),
    CONSTRAINT FK_PAY_BRANCH_PROV FOREIGN KEY (PROVIDER_ID) REFERENCES GFC.PAYMENT_PROVIDERS_TB (PROVIDER_ID),
    CONSTRAINT UK_PAY_BRANCH_LEG  UNIQUE (LEGACY_BANK_NO)
);

CREATE INDEX GFC.IDX_PAY_BRANCH_PROV ON GFC.PAYMENT_BANK_BRANCHES_TB (PROVIDER_ID);
CREATE SEQUENCE GFC_PAK.PAYMENT_BANK_BRANCHES_SEQ START WITH 1 INCREMENT BY 1 NOCACHE;
CREATE OR REPLACE PUBLIC SYNONYM PAYMENT_BANK_BRANCHES_TB  FOR GFC.PAYMENT_BANK_BRANCHES_TB;
CREATE OR REPLACE PUBLIC SYNONYM PAYMENT_BANK_BRANCHES_SEQ FOR GFC_PAK.PAYMENT_BANK_BRANCHES_SEQ;


-- ============================================================
-- 4) PAYMENT_REQ_DETAIL_SPLIT_TB
-- توزيع المبلغ المعتمد للموظف على عدة حسابات
-- ============================================================
-- ملاحظة:
--   - لو الموظف عنده حساب واحد: لا نحتاج دخول هنا، يُستخدم IS_DEFAULT من PAYMENT_ACCOUNTS_TB
--   - لو الموظف عنده حساب واحد نشط: يُوزَّع المبلغ كاملاً عليه
--   - لو الموظف عنده أكثر من حساب: يُستخدم هذا الجدول لتحديد التوزيع بالضبط
CREATE TABLE GFC.PAYMENT_REQ_DETAIL_SPLIT_TB (
    SPLIT_ID        NUMBER              NOT NULL,
    DETAIL_ID       NUMBER              NOT NULL,   -- FK لـ PAYMENT_REQ_DETAIL_TB
    ACC_ID          NUMBER              NOT NULL,   -- FK لـ PAYMENT_ACCOUNTS_TB
    AMOUNT          NUMBER(14,2)        NOT NULL,   -- المبلغ المخصّص لهذا الحساب
    PCT_USED        NUMBER(5,2),                    -- النسبة المستخدمة (للمعلومية)
    -- تتبّع مصدر التوزيع
    SOURCE_TYPE     NUMBER(1) DEFAULT 0,            -- 0=auto (من إعداد الموظف), 1=manual (تعديل المحاسب)
    AUTO_AMOUNT     NUMBER(14,2),                   -- القيمة التلقائية الأصلية (للمقارنة)
    NOTES           VARCHAR2(200),
    -- Audit
    ENTRY_USER      NUMBER,
    ENTRY_DATE      DATE      DEFAULT SYSDATE,
    UPDATE_USER     NUMBER,
    UPDATE_DATE     DATE,
    CONSTRAINT PK_PAY_SPLIT     PRIMARY KEY (SPLIT_ID),
    CONSTRAINT FK_PAY_SPLIT_DTL FOREIGN KEY (DETAIL_ID) REFERENCES GFC.PAYMENT_REQ_DETAIL_TB (DETAIL_ID) ON DELETE CASCADE,
    CONSTRAINT FK_PAY_SPLIT_ACC FOREIGN KEY (ACC_ID)    REFERENCES GFC.PAYMENT_ACCOUNTS_TB (ACC_ID),
    CONSTRAINT UK_PAY_SPLIT     UNIQUE (DETAIL_ID, ACC_ID),
    CONSTRAINT CK_PAY_SPLIT_SRC CHECK (SOURCE_TYPE IN (0, 1))
);

CREATE INDEX GFC.IDX_PAY_SPLIT_DTL ON GFC.PAYMENT_REQ_DETAIL_SPLIT_TB (DETAIL_ID);
CREATE SEQUENCE GFC_PAK.PAYMENT_REQ_DETAIL_SPLIT_SEQ START WITH 1 INCREMENT BY 1 NOCACHE;
CREATE OR REPLACE PUBLIC SYNONYM PAYMENT_REQ_DETAIL_SPLIT_TB  FOR GFC.PAYMENT_REQ_DETAIL_SPLIT_TB;
CREATE OR REPLACE PUBLIC SYNONYM PAYMENT_REQ_DETAIL_SPLIT_SEQ FOR GFC_PAK.PAYMENT_REQ_DETAIL_SPLIT_SEQ;


-- ============================================================
-- FK متأخر: ربط PAYMENT_ACCOUNTS_TB.BRANCH_ID بعد إنشاء BRANCHES
-- ============================================================
ALTER TABLE GFC.PAYMENT_ACCOUNTS_TB
  ADD CONSTRAINT FK_PAY_ACC_BR
      FOREIGN KEY (BRANCH_ID) REFERENCES GFC.PAYMENT_BANK_BRANCHES_TB (BRANCH_ID);


-- ============================================================
-- GRANTS — GFC tables to GFC_PAK (sequences مملوكة لـ GFC_PAK فلا تحتاج GRANT)
-- ============================================================
GRANT SELECT, INSERT, UPDATE, DELETE ON GFC.PAYMENT_PROVIDERS_TB         TO GFC_PAK;
GRANT SELECT, INSERT, UPDATE, DELETE ON GFC.PAYMENT_BENEFICIARIES_TB     TO GFC_PAK;
GRANT SELECT, INSERT, UPDATE, DELETE ON GFC.PAYMENT_ACCOUNTS_TB          TO GFC_PAK;
GRANT SELECT, INSERT, UPDATE, DELETE ON GFC.PAYMENT_BANK_BRANCHES_TB     TO GFC_PAK;
GRANT SELECT, INSERT, UPDATE, DELETE ON GFC.PAYMENT_REQ_DETAIL_SPLIT_TB  TO GFC_PAK;

-- الوصول لجداول DATA لـ migration (اقرأ فقط)
GRANT SELECT ON DATA.MASTER_BANKS_EMAIL TO GFC_PAK;
GRANT SELECT ON DATA.BANK_BRANCH        TO GFC_PAK;


-- ============================================================
-- ملاحظة: لا يوجد SEED هنا — البيانات تأتي من Migration:
--   - PAYMENT_PROVIDERS_TB      ← من DATA.MASTER_BANKS_EMAIL + إضافة Jawwal Pay يدوياً
--   - PAYMENT_BANK_BRANCHES_TB  ← من DATA.BANK_BRANCH (ربط مباشر عبر B_NO)
--   - PAYMENT_ACCOUNTS_TB       ← من DATA.EMPLOYEES (BANK=B_BR_NO, MASTER_BANKS_EMAIL=B_NO)
-- الملف: 02_migration.sql
-- ============================================================
