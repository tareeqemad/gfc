---
name: موديول إدارة حسابات الصرف — payment_accounts
description: مزودون (بنوك+محافظ) + فروع + حسابات موظفين + مستفيدون — البنية والفلو والـ pitfalls
type: project
originSessionId: de9d02d3-f232-41d9-8bb0-df68ed0272c7
---
## نظرة عامة

موديول جديد يفصل بيانات حسابات الصرف عن `DATA.EMPLOYEES.BANK/ACCOUNT/IBAN`، ويُدير:
- المزودون (بنوك + محافظ إلكترونية)
- فروع البنوك
- حسابات الصرف للموظف (متعددة)
- المستفيدون (للمتوفين/الورثة)
- توزيع الصرف (split) بين عدة حسابات

## الجداول (schema GFC)

| الجدول | الوصف |
|--------|-------|
| `PAYMENT_PROVIDERS_TB` | البنوك والمحافظ — `PROVIDER_TYPE` 1=بنك 2=محفظة |
| `PAYMENT_BANK_BRANCHES_TB` | فروع البنوك |
| `PAYMENT_ACCOUNTS_TB` | حسابات الموظف (متعددة) |
| `PAYMENT_BENEFICIARIES_TB` | المستفيدون |
| `PAYMENT_REQ_DETAIL_SPLIT_TB` | توزيع الصرف على حسابات الموظف |

كل الجداول لها `STATUS` (1=نشط، 9=محذوف) و `IS_ACTIVE` (1=مفعّل، 0=موقوف). الـ Sequences في `GFC_PAK` و الـ Synonyms PUBLIC.

## Package: `GFC_PAK.PAYMENT_ACCOUNTS_PKG`

ملفات: [03_pkg_spec.sql](database/payment_accounts/03_pkg_spec.sql) + [04_pkg_body.sql](database/payment_accounts/04_pkg_body.sql)

### Procedures الرئيسية

| Procedure | الاستخدام |
|-----------|-----------|
| `PROVIDERS_LIST_PAGINATED` | مزودون + filters (type/active/search) + pagination + counts فرعية |
| `PROVIDERS_COUNT` / `PROVIDERS_TOTALS` | عدد + إحصائيات (total/banks/wallets/inactive) |
| `PROVIDER_INSERT/UPDATE/DELETE/TOGGLE_ACTIVE` | CRUD + DELETE يفشل لو في حسابات/فروع |
| `PROVIDER_ACCOUNTS_LIST` | حسابات الموظفين عند مزود معين |
| `PROVIDERS_BULK_ACTION` | bulk: ACTIVATE/DEACTIVATE/DELETE (CSV ids) |
| `EMPLOYEES_LIST_PAGINATED` | موظفين + counts + الحساب الافتراضي (CTEs محسّنة) |
| `EMPLOYEES_COUNT` / `EMPLOYEES_TOTALS` | عدد + إحصائيات (total/bank/wallet/benef) |
| `EMPLOYEE_GET` / `ACCOUNT_*` / `BENEF_*` / `BRANCH_*` | CRUD على الحسابات والمستفيدين والفروع |

### الـ List Procedures المحسّنة (CTEs Pattern)

`EMPLOYEES_LIST_PAGINATED` و `PROVIDERS_LIST_PAGINATED` تستخدم نمط CTE لتجنب N×scalar subqueries:

```sql
WITH ACC_AGG AS (SELECT EMP_NO, COUNT(*), SUM(CASE...) FROM ... GROUP BY EMP_NO),
     BNF_AGG AS (SELECT EMP_NO, COUNT(*) ...),
     DEF_ACC AS (SELECT ... ROW_NUMBER() OVER (PARTITION BY EMP_NO) AS DRN FROM ...),
     PAGED   AS (SELECT ... ROW_NUMBER() OVER (ORDER BY ...) AS RN
                   FROM EMPLOYEES E LEFT JOIN ACC_AGG ...
                  WHERE [filters])
SELECT P.*, AC.*, BN.*, D.*
  FROM PAGED P LEFT JOIN ACC_AGG AC ON ... LEFT JOIN BNF_AGG BN ... LEFT JOIN DEF_ACC D ...
 WHERE P.RN BETWEEN V_OFFSET+1 AND V_OFFSET+V_LIMIT
```

⚠️ **مهم:** Pagination قبل الـ joins، لتجنب احتساب counts لـ 1800 موظف عند offset=0.

## ⚠️ Pitfalls اكتُشفت (حلول مهمة)

### 1. `excuteProcedures` يستخدم `value` كمفتاح للـ output
```php
'value' => 'MSG'    // المفتاح في $result
'name'  => ':P_MSG_OUT'   // فقط للـ binding
return $result['MSG'];    // ✅ صحيح
return $result['P_MSG_OUT']; // ❌ خطأ — يرجع null
```
نفس الشي للـ `value' => 'CNT'` → `$result['CNT']`.

### 2. GRANT SELECT TO PUBLIC على الجداول إجباري
الـ pkg بـ definer rights. لكن عند fetch من cursor في PHP بـ user مختلف (مثلاً `telbawab`)، الـ session يحتاج SELECT على الجداول الفعلية. بدونه: `excuteCursor` يبتلع الخطأ ويرجع array(0).

```sql
GRANT SELECT ON GFC.PAYMENT_PROVIDERS_TB        TO PUBLIC;
GRANT SELECT ON GFC.PAYMENT_BENEFICIARIES_TB    TO PUBLIC;
GRANT SELECT ON GFC.PAYMENT_ACCOUNTS_TB         TO PUBLIC;
GRANT SELECT ON GFC.PAYMENT_BANK_BRANCHES_TB    TO PUBLIC;
GRANT SELECT ON GFC.PAYMENT_REQ_DETAIL_SPLIT_TB TO PUBLIC;
```

موجودة في [06_pkg_grants.sql](database/payment_accounts/06_pkg_grants.sql).

### 3. `<input name="search">` يخفي `function search()`
داخل `<form>`، الـ named inputs تتجاوز global functions في scope chain للـ `onclick`. الحل: استخدم `name="q"` (أو أي اسم آخر) و map إلى `search` في الـ controller.

### 4. heredoc في PHP
- `$('#id')` آمن (الـ `(` يكسر تفسير المتغير)
- `{$php_var}` يُفسَّر متعمداً
- `\$variable` لازم escape لو `variable` بدأ بحرف صالح
- استخدم `String.fromCharCode(10)` بدل `\n` لتفادي تفسير `\n`

### 5. الـ Migration حالياً نقل ١٣١٧ حساب
كلهم لموظفين متقاعدين (`E.IS_ACTIVE=0`). الفلتر الافتراضي `is_active=1` يخفيهم. الـ Migration وضع كل الحسابات `IS_ACTIVE=1` بصرف النظر عن حالة الموظف.

## الواجهة

### الشاشات

| URL | الـ View | الوصف |
|-----|---------|-------|
| `/payment_accounts` | payment_accounts_index | قائمة الموظفين + الحساب الافتراضي + إحصائيات + تصدير Excel |
| `/payment_accounts/emp/<EMP_NO>` | payment_accounts_emp | تفاصيل موظف + إدارة حساباته ومستفيديه |
| `/payment_accounts/providers` | payment_accounts_providers + _page | المزودون (نمط فلاتر+استعلام+جدول+bulk) |
| `/payment_accounts/branches` | payment_accounts_branches | الفروع |

### نمط الـ index (موحّد)

كل index يتبع نمط `payment_req_index` — مرجع feedback_index_filters_pattern.md:
- PAGE-HEADER + breadcrumb
- card-header + أزرار `btn-*-sm`
- `<form id="..._form" onsubmit="return false;">` بـ filters
- IDs قياسية: `dp_*` للـ select، `txt_*` للـ text، `dp_branch_no` لو الـ user admin
- زر "استعلام" + "تفريغ الحقول" بـ `btn-primary` و `btn-cyan-light`
- بطاقات إحصائية مخفية حتى الاستعلام
- `<div id="container">` للنتائج
- JS: `$scripts = <<<SCRIPT ... SCRIPT;` + `sec_scripts($scripts);`
- دوال قياسية: `reBind`, `LoadingData`, `values_search`, `search`, `loadData`, `clear_form`
- AJAX: `get_data` + `success_msg/danger_msg/warning_msg`

### Page view

- `<table id="page_tb" data-container="container">`
- `page-sector` row للصفحات > 1 (لـ scroll-to-page mechanism في function.js)
- `<?= $this->pagination->create_links() ?>` في الأسفل
- `<script>if (typeof reBind === 'function') reBind();</script>` في النهاية
- Hidden div لـ totals تُقرأ في الـ index لتحديث البطاقات الإحصائية

### نمط `payment_req` للحالة الافتراضية
الفلتر `is_active = 1 (فعّال)` افتراضياً — لأن المحاسب نادراً يحتاج المتقاعدين.

## واجهة الموظف الواحد (`payment_accounts_emp.php`)

- بيانات أساسية + حساب legacy من `DATA.EMPLOYEES`
- جدول حسابات نشطة + موقوفة
- إضافة/تعديل/حذف/إيقاف/تفعيل حساب
- إعداد افتراضي + توزيع split (نسبة/مبلغ/كامل المتبقي)
- المستفيدون (relations: زوجة/ابن/بنت/أب/أم/وريث آخر) + من-إلى date

## شاشة المزودون (الجديدة)

نمط فلاتر+استعلام كاملة:
- **filters:** النوع (بنك/محفظة/الكل) + الحالة (نشط/موقوف/الكل) + بحث (الاسم/الرمز)
- **بطاقات:** إجمالي / بنوك / محافظ / موقوفة
- **جدول:** المزود + النوع + رقم الحساب + IBAN + عدد الموظفين + عدد الفروع + الحالة + 4 أزرار (تفاصيل/تعديل/إيقاف-تفعيل/حذف)
- **روابط counts:** clickable → modal يعرض حسابات/فروع المزود
- **Bulk:** checkboxes + شريط أصفر (تفعيل/إيقاف/حذف)
- **3 modals:** إضافة/تعديل + تفاصيل تقنية + قائمة حسابات/فروع

## ترتيب تشغيل DB Scripts على dev

```sql
@01_ddl.sql                  -- Tables, sequences, indexes, synonyms
@02_migration.sql            -- نقل DATA.MASTER_BANKS_EMAIL/BANK_BRANCH/EMPLOYEES
@02b_fix_columns.sql         -- تصحيحات (إن وُجدت)
@03_pkg_spec.sql             -- Package specification
@04_pkg_body.sql             -- Package implementation
@03_seed_ibans.sql           -- ⚠️ عدّل قيم IBAN قبل التشغيل (placeholders ?? موجودة)
@05_permissions.sql          -- Menu permissions
@05b_fix_menu_full_code.sql  -- تصحيح menu codes
@06_pkg_grants.sql           -- ⚠️ مهم — GRANT EXECUTE + SELECT TO PUBLIC
```

## الملفات

```
application/modules/payment_accounts/
├── controllers/Payment_accounts.php      # CRUD + endpoints + export_excel
├── models/Payment_accounts_model.php     # كل الـ general_get + bulk
└── views/
    ├── payment_accounts_index.php        # قائمة الموظفين
    ├── payment_accounts_page.php         # AJAX page (الموظفين)
    ├── payment_accounts_emp.php          # تفاصيل موظف
    ├── payment_accounts_providers.php    # المزودون (فلاتر+استعلام)
    ├── payment_accounts_providers_page.php  # AJAX page (المزودون)
    └── payment_accounts_branches.php     # الفروع

database/payment_accounts/
├── 01_ddl.sql + 02_migration.sql + 02b_fix_columns.sql
├── 03_pkg_spec.sql + 03_seed_ibans.sql
├── 04_pkg_body.sql
├── 05_permissions.sql + 05b_fix_menu_full_code.sql
└── 06_pkg_grants.sql
```

## Sanity Check بعد الرفع

```sql
-- pkg valid
SELECT OBJECT_NAME, OBJECT_TYPE, STATUS FROM USER_OBJECTS WHERE OBJECT_NAME='PAYMENT_ACCOUNTS_PKG';

-- counts
SELECT (SELECT COUNT(*) FROM GFC.PAYMENT_PROVIDERS_TB)     AS providers,
       (SELECT COUNT(*) FROM GFC.PAYMENT_BANK_BRANCHES_TB) AS branches,
       (SELECT COUNT(*) FROM GFC.PAYMENT_ACCOUNTS_TB)      AS accounts
  FROM DUAL;

-- grants
SELECT GRANTEE, TABLE_NAME, PRIVILEGE FROM ALL_TAB_PRIVS
 WHERE TABLE_NAME LIKE 'PAYMENT\_%' ESCAPE '\' AND GRANTEE='PUBLIC';
```

## Notes
- النوع 1 = بنك / 2 = محفظة (`PROVIDER_TYPE`)
- المحافظ ما تحتاج IBAN، البنوك تحتاج
- IS_DEFAULT = الحساب الذي يستلم المتبقي (default split)
- SPLIT_TYPE: 1=نسبة 2=مبلغ ثابت 3=كامل المتبقي
- Migration نقل بيانات لـ 1317 موظف (المتقاعدون)
- 12 مزود (9 بنك + 3 محفظة): الاستثمار(76)، الإسلامي الفلسطيني(81)، فلسطين(89)، القدس(82)، الإسلامي العربي(30)، الأردن(35)، القاهرة عمان(50)، العربي(70)، الوطني الإسلامي(4444)، PalPay(36)، Jawwal Pay
