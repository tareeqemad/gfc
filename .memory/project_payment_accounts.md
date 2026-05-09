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

---

## تحسينات شاملة (2026-04-29)

### 1. فلتر "المستفيدون" + بطاقة قابلة للنقر
- إضافة `P_HAS_BENEF NUMBER` للـ procedures الـ 3 (LIST_PAGINATED + COUNT + TOTALS)
- View: dropdown جديد "المستفيدون: الكل / عنده مستفيد / بدون مستفيد"
- بطاقة "مستفيدون" صارت قابلة للنقر → تستدعي `filterByBenef()` التي تعدّل `dp_has_benef=1` + تشغّل البحث
- في `EMPLOYEES_LIST_PAGINATED` PAGED CTE: أضيف `LEFT JOIN BNF_AGG BN`

### 2. فلتر الشهر `P_THE_MONTH` (DATA.EMPLOYEES_MONTH)
- حقل جديد في الـ index: `<input id="txt_the_month" placeholder="YYYYMM">`
- المنطق (strict snapshot): لما الشهر مُحدّد، النظام يقتصر على من عنده سجل في `EMPLOYEES_MONTH`
- IS_ACTIVE يُقرأ من `EM.IS_ACTIVE` (تاريخي) بدل `E.IS_ACTIVE` (الحالي)
- ⚠️ الفلاتر يجب أن تكون متّسقة رياضياً: `الكل = فعّال + متقاعد` (مثلاً 804 = 799 + 5)
- كل فلاتر `is_active=1/0` تستخدم `EM.IS_ACTIVE` لما الشهر مُحدّد، أو `E.IS_ACTIVE` لما فاضي
- متوفى/مغلق (2/4): دائماً من `PAYMENT_ACCOUNTS_TB.INACTIVE_REASON` الحالي (مش تاريخي)

### 3. شاشة المزودين — modal "حسابات الموظفين"
- **توسيع لـ modal-xl** + sticky header
- **Toolbar جديد**: بحث محلي (debounce 120ms) + عدّاد ديناميكي (X/Y) + زر "تصدير Excel"
- **Endpoint جديد**: `provider_accounts_export_excel?provider_id=X&only_active=0`
- البحث client-side في: رقم الموظف، الاسم، رقم الحساب، IBAN، اسم الفرع، صاحب الحساب

### 4. رقم الفرع — استخدام `LEGACY_BANK_NO` (مش عمود جديد)
- `PAYMENT_BANK_BRANCHES_TB.LEGACY_BANK_NO` كان للـ migration → الآن صار **رقم الفرع الرسمي** (إجباري)
- `BRANCH_INSERT/UPDATE`: validation + duplicate check
- في الـ branches table: عمود "رقم الفرع" badge، في الـ modal حقل إجباري
- في dropdown الفرع بصفحة الموظف: التنسيق `100 — فرع الرمال` (LEGACY_BANK_NO + اسم)
- في كرت الحساب: badge `#100` بجانب اسم الفرع

### 5. modal "حساب جديد" — صاحب الحساب (تنظيف)
- ❌ المستفيدون لم يعودوا في dropdown صاحب الحساب (كان مربكاً)
- ✅ خياران فقط: `الموظف نفسه` / `شخص آخر`
- "الموظف نفسه": auto-fill من بيانات الموظف + `readonly`
- "شخص آخر": تفريغ + editable + auto-focus على الهوية
- عند تعديل حساب موجود: يكتشف النوع من `OWNER_ID_NO` و `OWNER_NAME` تلقائياً

### 6. modal إيقاف الحساب — بدل `prompt()`
- استبدال 2 `prompt()` بـ `#deactAccModal` كامل
- `<select>` لسبب الإيقاف (1=تقاعد، 2=وفاة، 3=فصل، 4=تجميد، 5=تحويل، 9=أخرى) — نص نظيف بدون إيموجي
- `<textarea>` لملاحظة (متعدد الأسطر)
- Validation: لازم يختار سبب قبل الحفظ
- زر "تأكيد" مع spinner أثناء الحفظ

### 7. إيقاف الفتح التلقائي للمرفقات
- `saveBenef()` ما عاد يفتح modal `_showReport` بعد إضافة مستفيد
- المرفقات تُرفع يدوياً من زر "المرفقات" بجانب المستفيد

### 8. Optgroup في dropdown المزود (بدل emojis)
- `<optgroup label="بنوك">` و `<optgroup label="محافظ إلكترونية">`
- استبدال 📱/🏦 بـ FA icons في الكروت (`fa-mobile-alt` للمحفظة، `fa-university` للبنك)
- داخل `<select>` لا يمكن استخدام FA icons، فاستخدمنا optgroup

### 9. الـ Pitfalls المُكتشفة في هذه الجلسة
- **`SQLT_INT` + null = 0**: `providers_list($type=null)` كان يطلع فاضي. الحل: `'type' => ''`
- **`length => -1` للـ MSG_OUT = buffer 3 chars**: ORA-06502 على Delete/Update. الحل: `'length' => 500`
- **GRANTs ضرورية للـ PUBLIC**: الويب يتصل كـ user الجلسة، مش GFC_PAK
- **Excel `setCellValueExplicit` + TYPE_STRING** للـ IDs و IBANs (يمنع scientific notation)

تفاصيل في [feedback_php_oracle_gotchas.md](feedback_php_oracle_gotchas.md).

### 10. الملفات المُعدَّلة (2026-04-29)

```
database/payment_accounts/
├── 03_pkg_spec.sql           ← P_HAS_BENEF + P_THE_MONTH للـ 3 procedures
├── 04_pkg_body.sql            ← LEFT JOIN EMPLOYEES_MONTH + LEFT JOIN BNF_AGG
│                                + LEGACY_BANK_NO required + duplicate check في BRANCH_INSERT/UPDATE
│                                + ACCOUNTS_LIST يرجع LEGACY_BANK_NO

application/modules/payment_accounts/
├── controllers/Payment_accounts.php
│   ├── + has_benef + the_month في filters
│   └── + provider_accounts_export_excel($pid, $only_active)
├── models/Payment_accounts_model.php
│   ├── 35 instance: 'length' => -1 → 500 (MSG_OUT buffer fix)
│   ├── providers_list/branches_list: 'type' => '' (null safety)
│   └── + has_benef + the_month في employees_list/count/totals
│   └── + legacy_bank_no في branch_insert/update
└── views/
    ├── payment_accounts_index.php   ← + dp_has_benef + txt_the_month + filterByBenef()
    ├── payment_accounts_emp.php     ← deactAccModal + self/other dropdown
    │                                 + LEGACY_BANK_NO في dropdown الفرع
    │                                 + saveBenef بدون auto-open مرفقات
    └── payment_accounts_providers.php ← modal toolbar + Excel export + sticky header
                                       + رقم الفرع في table + modal الفرع
```

---

## تحسينات (2026-05-03) — Split-aware Batch + Beneficiary Linking

### 1. `AUTO_FILL_SPLIT` — تقسيم "كامل الباقي" بالتساوي
**المشكلة:** الـ procedure كانت تعطي كل المتبقي لأول حساب بـ `SPLIT_TYPE=3` (`EXIT` بعد أول صف). الموظف عنده ٢ حسابات لورث → الأول يأخذ كل المبلغ، الثاني صفر.

**الحل:** `V_REM_CNT = COUNT(*)` ثم تقسيم بالتساوي. آخر حساب يأخذ ما تبقى لتفادي أخطاء التقريب.

### 2. `PAYMENT_REQ_BATCH_CONFIRM` — نفس الإصلاح
الـ procedure في `DISBURSEMENT_BATCH_PKG` كانت تستخدم `WHERE ROWNUM = 1` للـ "كامل الباقي" — نفس مشكلة `EXIT`. الإصلاح متطابق: تقسيم بالتساوي.

### 3. `BATCH_HISTORY_GET` — GROUP BY + counts
**المشكلة:** في `method=2` (الجديدة)، الموظف يولّد سطر لكل حساب في `PAYMENT_BATCH_DETAIL_TB` → الـ procedure القديمة كانت ترجع صفوف متكررة.

**الحل:**
- `GROUP BY BD.BATCH_ID, BD.EMP_NO`
- `SUM(TOTAL_AMOUNT)` للمجموع
- `LISTAGG(SNAP_PROVIDER_NAME)` للبنوك مع `REGEXP_REPLACE('([^|]+)(\|\1)+','\1')` لإزالة التكرار + `REPLACE` لتحويل الـ separator
- أعمدة جديدة: `BENEF_COUNT`, `ACTIVE_ACC_COUNT`, `INACTIVE_ACC_COUNT`, `SPLIT_COUNT`

⚠️ **Pitfall:** Oracle لا يدعم correlation داخل nested inline view بعمق 2. لازم alias صريح للجدول الداخلي (`BD3`) أو aggregate function عادية بدل subquery.

### 4. `BATCH_EMP_ACCOUNTS_GET` (جديد)
ترجع لكل موظف داخل دفعة:
- كل حساباته (نشطة/موقوفة) من `PAYMENT_ACCOUNTS_TB`
- بيانات المستفيد لو الحساب لورث (`B.NAME, REL_TYPE, REL_NAME, PCT_SHARE`)
- `BATCH_AMOUNT` = المبلغ المُوزع على هذا الحساب من `PAYMENT_BATCH_DETAIL_TB`

تُستدعى من `payment_req_batch_detail` عند الضغط على زر `[+]`.

### 5. `LINK_ACCOUNTS_TO_BENEF_AUTO` (جديد)
يربط الحسابات الموجودة (بدون `BENEFICIARY_ID`) بالمستفيدين عبر:
- مرحلة ١: مطابقة `OWNER_ID_NO` = `BENEFICIARIES.ID_NO` (الأدق)
- مرحلة ٢: مطابقة `OWNER_NAME` = `BENEFICIARIES.NAME` (للمتبقّين)

يستثني الحسابات على اسم الموظف نفسه. يُستدعى من زر "ربط تلقائي" في رأس قسم الحسابات.

### 6. `payment_req_batch_detail.php` — Badges + Expand
- **badges على الصف:** `🧑‍🤝‍🧑 N ورث` / `🏦 N حسابات` / `⏸ N موقوف`
- **عمود "حالة الحساب":** `⚠ على الحساب القديم` (أحمر) أو `✓ توزيع (N)` (أخضر)
- **صف أصفر** لو في حالة تستدعي انتباه
- **زر `[+]`** يفتح صف فرعي بـ AJAX → بطاقات لكل حساب فيها: اسم البنك، المبلغ، رقم الحساب، IBAN، صاحب الحساب + صلة القرابة + الهوية
- **ملخص رأس البنك:** `12 بورث · 5 بحسابات متعددة · ⚠ 3 بدون توزيع`

### 7. `PAYMENT_BATCH_BANK_VW` — snapshot-aware (موجود مسبقاً)
الـ view تقرأ `SNAP_*` fields من `PAYMENT_BATCH_DETAIL_TB` أولاً، ثم fallback للـ live joins. هذا يضمن:
- التصدير للبنك يحترم الـ split (موظف ١٠٩٠ → سطرين: حنين ٧٥٠ + منار ٧٥٠)
- IBAN/OWNER من بيانات المستفيد، مش الموظف
- Audit-safe — أي تعديل على الحسابات بعد الـ batch لا يغيّر الـ snapshot

### 8. `Disburse_Method` — اختيار الطريقة
عند `BATCH_CONFIRM`، modal يسأل المحاسب:
- **١ — قديمة:** صف واحد لكل موظف، بنك من `DATA.EMPLOYEES`
- **٢ — جديدة:** صف لكل حساب من `PAYMENT_ACCOUNTS_TB` مع snapshot

### 9. الـ Pitfall الجديد — nested correlation
**ORA-00904:** `("BD"."EMP_NO": invalid identifier)` عند subquery داخل LISTAGG داخل SELECT.

**السبب:** Oracle لا يحل الـ correlation للـ outer alias من inline view على عمق ٢.

**الحل:** إما alias صريح للـ inner table (`BD3`) أو استخدام aggregate function (`LISTAGG` مع `GROUP BY`) بدل subquery.

### 10. الملفات المُعدَّلة (2026-05-03)

```
database/payment_req/
├── 04_batch_pkg_spec.sql       ← + BATCH_EMP_ACCOUNTS_GET
└── 04_batch_pkg_body.sql        ← + BATCH_EMP_ACCOUNTS_GET
                                  + BATCH_HISTORY_GET (GROUP BY + counts + LISTAGG)
                                  + BATCH_CONFIRM (equal-share for SPLIT_TYPE=3)

database/payment_accounts/
├── 03_pkg_spec.sql              ← + LINK_ACCOUNTS_TO_BENEF_AUTO
└── 04_pkg_body.sql              ← + LINK_ACCOUNTS_TO_BENEF_AUTO
                                   + AUTO_FILL_SPLIT (equal-share fix)

application/modules/payment_req/
├── controllers/Payment_req.php  ← + batch_emp_accounts_json
├── models/Payment_req_model.php ← + batch_emp_accounts
└── views/payment_req_batch_detail.php ← badges + expand AJAX

application/modules/payment_accounts/
├── controllers/Payment_accounts.php ← + account_link_auto
├── models/Payment_accounts_model.php ← + link_accounts_to_benef_auto
└── views/payment_accounts_emp.php   ← + زر "ربط تلقائي" + linkAccountsAuto()
```

### 11. الفلو الكامل للصرف (موظف ١٠٩٠ مع ٢ ورث)

```
1. المحاسب يحتسب دفعة بـ DISBURSE_METHOD=2
   ↓
2. BATCH_CONFIRM يقرأ PAYMENT_ACCOUNTS_TB:
   - يتجاهل الموقوف
   - حنين: SPLIT_TYPE=3 → 750 ₪
   - منار: SPLIT_TYPE=3 → 750 ₪ (تقسيم بالتساوي)
   ↓
3. PAYMENT_BATCH_DETAIL_TB يُكتب فيه ٢ سجل (سنابشوت IBAN/OWNER لكل حساب)
   ↓
4. payment_req_batch_detail يعرض الموظف بصف واحد:
   1090 محمد رفيق صافي  [٢ ورث] [٢ حسابات]  1,500.00  [+]
   عند الضغط [+]: AJAX → بطاقتين (حنين 750 + منار 750)
   ↓
5. التصدير للبنك (PAYMENT_BATCH_BANK_VW):
   صف لحنين بـ IBAN حنين، صف لمنار بـ IBAN منار
```

---

## تحسينات شاملة (2026-05-03 ج٢) — Health Check + Dashboard + Bulk Import + Smart Alerts

### Procedures الجديدة في `PAYMENT_ACCOUNTS_PKG`
- `LINK_ACCOUNTS_BULK_AUTO` — ربط جماعي تلقائي لكل الموظفين بمستفيديهم
- `HEALTH_OVERVIEW` — counts ل ٧ فئات مشاكل في النظام كله
- `HEALTH_LIST(category)` — قائمة تفصيلية لفئة محددة (paginated)

### Procedures الجديدة في `DISBURSEMENT_BATCH_PKG`
- `BATCH_BANK_SUMMARY_GET` — ملخص الدفعة حسب الـ snapshot (يعكس البنوك الفعلية)
- `BATCH_REFRESH_SPLIT` — تحديث توزيع دفعة محتسبة بدون فك الاحتساب
- `BATCH_PREVIEW` — معاينة قبل الاحتساب مع `STATUS=OK/WARN/ERR` لكل موظف
- `EMP_PENDING_BATCHES` — الدفعات المحتسبة (غير منفّذة) لموظف معين

### الشاشات الجديدة

| الشاشة | URL | الاستخدام |
|--------|-----|-----------|
| 📊 **Dashboard** | `/payment_accounts/dashboard` | إحصائيات + حالة + آخر دفعات + روابط سريعة |
| 🩺 **Health Check** | `/payment_accounts/health_check` | فحص شامل لـ ٧ فئات مشاكل + actions مخصصة |
| 📥 **Bulk Import** | `/payment_accounts/bulk_import` | استيراد حسابات من Excel (قالب + معاينة + تنفيذ) |

### الفئات في Health Check
- 🔴 ERR: `EMP_NO_ACC`, `ACC_NO_IBAN`, `PROV_INCOMPLETE`
- 🟡 WARN: `BENEF_UNLINKED`, `ACC_INACTIVE_ONLY`, `BENEF_EXPIRED`
- 🔵 INFO: `EMP_DUP_DEFAULT`

### تنبيه ذكي
في `payment_accounts_emp.php` — banner أصفر علوي يظهر تلقائياً لو فيه دفعات محتسبة للموظف، مع روابط لـ batch_detail.

### حماية BATCH_CONFIRM
يرفض الموظفين بحالات:
- لا توجد حسابات نشطة
- مجموع المبالغ الثابتة > المستحق
- مجموع النسب > 100%
- (الثابت + النسب × المستحق) > المستحق

### Modal Preview قبل الاحتساب
- زر "اعتماد الاحتساب" → Modal فيه إجماليات (OK/WARN/ERR) + روابط للإصلاح
- زر مُعطّل لو في أخطاء، checkbox "أؤكد المتابعة" للتحذيرات

### Permissions جديدة
أُضيفت في `05_permissions.sql`: dashboard, health_check, bulk_import (+ AJAX endpoints).
ولـ payment_req: ملف منفصل `11_new_endpoints_permissions.sql`.

### الـ Pitfall المُكتشف
**Oracle nested correlation depth 2** — `ORA-00904: invalid identifier`. الحل: alias صريح للجدول الداخلي أو استخدام aggregate function في outer query.
موثق في [feedback_php_oracle_gotchas.md](feedback_php_oracle_gotchas.md) النقطة 11.

### الفلو المتكامل للمحاسب
```
1. صباح يوم العمل → /payment_accounts/dashboard
   ↓ يشوف "5 مشاكل تحتاج مراجعة"
2. /payment_accounts/health_check
   ↓ "ربط تلقائي للكل" يحل البلك
3. يفتح موظفين بمشاكل، يصلح يدوياً
4. /payment_req/batch → اختيار طلبات
5. "اعتماد الاحتساب" → Modal Preview
   ↓ كل OK → يضغط اعتماد
6. /payment_req/batch_detail/N → مراجعة التوزيع
   ↓ تعديل بسيط على حساب → "تحديث التوزيع"
7. "تنفيذ الصرف" → ترحيل
8. تصدير ملف البنك (PAYMENT_BATCH_BANK_VW snapshot-aware)
```
