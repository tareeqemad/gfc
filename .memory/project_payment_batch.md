---
name: موديول صرف الرواتب والمستحقات — الوضع الكامل
description: نظام payment_req — الهيكل والفلو والحالات والبروسيجرات والتصدير
type: project
originSessionId: c01166d0-2d59-4644-a17c-9c45ff7a6785
---
## الهيكل العام

- **Framework**: CodeIgniter 3 + Oracle (عبر OCI + procedures)
- **Schema**: GFC (جداول) + GFC_PAK (packages + procedures)
- **سياسة**: لا يوجد SELECT مباشر من Model — كل شي عبر Oracle procedures
- **الثوابت**: عبر SETTING_PKG.CONSTANT_DETAILS_TB_GET_NAME(TB_NO, CON_NO)

## الجداول

| الجدول | الوصف |
|--------|-------|
| PAYMENT_REQ_TB | ماستر الطلب |
| PAYMENT_REQ_DETAIL_TB | موظفين الطلب |
| PAYMENT_REQ_LOG_TB | سجل العمليات |
| PAYMENT_BATCH_TB | ماستر الدفعة |
| PAYMENT_BATCH_DETAIL_TB | تفاصيل الدفعة حسب الموظف |
| PAYMENT_BATCH_LINK_TB | ربط الدفعة بالـ detail |
| SALARY_DUES_TB | جدول المستحقات (عمود BATCH_ID مضاف) |
| SALARY_DUES_ARCHIVE_TB | أرشيف المستحقات المعكوسة |
| PAYMENT_BATCH_BANK_VW | View تصدير بيانات البنك (فيها PAY_DATE) |

## أنواع الطلبات (TB_NO=538)

| رقم | الاسم | التكرار | المبلغ |
|-----|-------|---------|--------|
| 1 | راتب كامل | ممنوع | تلقائي (كامل بند 323) — الشرط: رصيد 323 > 0 بغض النظر عن NET_SALARY |
| 2 | دفعة من الراتب | مسموح | **نسبة فقط** + الحدود (L/H) — لا يوجد مبلغ ثابت |
| 3 | دفعة من المستحقات | مسموح | نسبة أو مبلغ ثابت |
| 4 | مستحقات حسب الشهر | ممنوع | حسب الشهر |
| 5 | استحقاقات وإضافات (بدون تأثير على SALARY_DUES) | مسموح | إجباري يدوي |

## حالات الطلب (TB_NO=541)

| رقم | الاسم |
|-----|-------|
| 0 | مسودة |
| 1 | معتمد |
| 2 | منفّذ للصرف |
| 3 | معتمد جزئي |
| 4 | محتسب |
| 9 | ملغى |

## حالات الدفعة (TB_NO=542)

| رقم | الاسم |
|-----|-------|
| 0 | محتسب |
| 2 | منفّذ للصرف |
| 9 | ملغى |

## الفلو

```
مسودة (0) → معتمد (1) → محتسب (4) → منفّذ للصرف (2)
```

### الاحتساب والصرف
- BATCH_CONFIRM → Detail: معتمد(1) → محتسب(4)، إنشاء دفعة
- BATCH_PAY → ترحيل SALARY_DUES_TB (مع BATCH_ID)، Detail: محتسب(4) → منفّذ(2)
- شهر ملف البنك = PAY_DATE (تاريخ تنفيذ الصرف)

### الإلغاء
- عكس الصرف → أرشفة SALARY_DUES → حذف → Detail يرجع معتمد + PAY_DATE/PAY_USER يتمسح
- فك الاحتساب → Detail يرجع معتمد + الدفعة تنحذف
- إلغاء اعتماد → Detail يرجع مسودة (ممنوع لو محتسب بدفعة)
- إلغاء/حذف طلب → ممنوع لو محتسب بدفعة

## الحمايات

- تكرار الماستر: أنواع 1,4 ممنوع لنفس الشهر (Oracle procedure فقط — بدون فحص بالكنترولر)
- تكرار الموظف: نفس الموظف ما يتضاف بطلبين من نفس النوع/الشهر
- حذف/إلغاء محتسب: ممنوع — لازم فك الاحتساب أولاً (CANCEL + DELETE + UNAPPROVE)
- المحجوز: معتمد (1) + محتسب (4) = محجوز بـ GET_EMP_DUES_RESERVED
- CALC_MASTER_STATUS: بيحسب PARTIAL_PAID(4) = محتسب بدفعة، PAID(2) = منفّذ فعلي

## Oracle Packages + Functions

| Package | الوصف |
|---------|-------|
| DISBURSEMENT_PKG | CRUD + حساب المبالغ + رصيد المستحقات |
| DISBURSEMENT_BATCH_PKG | احتساب + صرف + عكس + سجل + تصدير بنك |

### Functions مضافة (2026-04-12)
| Function | الوصف |
|----------|-------|
| GET_DETAIL_BATCH_ID(DETAIL_ID) | BATCH_ID لصف موظف |
| GET_DETAIL_BATCH_NO(DETAIL_ID) | BATCH_NO لصف موظف |
| GET_REQ_BATCH_ID(REQ_ID) | BATCH_ID لطلب (آخر دفعة) |
| GET_REQ_BATCH_NO(REQ_ID) | BATCH_NO لطلب |
| GET_EMP_IS_ACTIVE(EMP_NO) | IS_ACTIVE من DATA.EMPLOYEES |

## تصدير ملف البنك

| بنك | رقم | النموذج |
|-----|-----|---------|
| الاستثمار | 76 | 1 (بسيط): ID_NO1, EMP_NAME, ACNT_NO, BRANCH_NO, NET_EARN, iban |
| الإسلامي الفلسطيني | 81 | 1 (بسيط) |
| بنك فلسطين | 89 | 2 (هيدر): اسم الشركة, ايبان, شهر الصرف, ترتيب, اسم, هوية, ايبان, مبلغ, ILS |
| القدس | 82 | 2 (هيدر) IBAN: PS50ALDN060200634000420010000 |
| الإسلامي العربي | 30 | 2 (هيدر) IBAN: PS31PALS045105274280991604000 |

- تصدير لبنك واحد: `export_bank_file?batch_id=X&master_bank_no=89`
- تصدير كل البنوك: `export_bank_file?batch_id=X` (sheet لكل بنك)
- COMPANY IBAN لبنك فلسطين: PS31PALS045105274280991604000

## الشاشات

| الشاشة | URL | الوصف |
|--------|-----|-------|
| القائمة الرئيسية | /payment_req | عرض الطلبات + فلاتر (حُذف modal الإنشاء الجماعي) |
| طلب جديد | /payment_req/create | wizard: نوع → إعدادات → اختيار طريقة → معاينة → إنشاء |
| تفاصيل الطلب | /payment_req/get/ID | بيانات + موظفين + إضافة يدوي/إكسل |
| احتساب الصرف | /payment_req/batch | اختيار طلبات معتمدة + معاينة بنوك |
| سجل الدفعات | /payment_req/batch_history | قائمة الدفعات + فلاتر + إجراءات |
| تفاصيل الدفعة | /payment_req/batch_detail/ID | ملخص + بنوك + موظفين + تصدير |
| كشف حساب موظف | /payment_req/emp_statement | كشف حساب فردي |

## wizard الإنشاء — الفلو (تم 2026-04-12)

```
اختيار النوع (5 بطاقات)
     ↓
إعدادات (شهر + نسبة + حدود + بند)
     ↓
اختيار طريقة الإضافة:
  ├─ 👥 كل الموظفين → معاينة الكل
  ├─ ➕ موظفين محددين → select2 + جدول مبالغ → معاينة
  └─ 📊 رفع Excel → قالب ذكي حسب النوع → معاينة
     ↓
معاينة (بحث + فلتر مقر + checkboxes + إجماليات + مستبعدين)
     ↓
إنشاء الطلب (كل = bulk_create | جزئي = create + detail_add)
```

- نوع 5: بدون "كل الموظفين" (ما فيه حساب تلقائي)
- نوع 1: حقل المبلغ مقفل (كامل المستحقات)
- المبلغ إجباري: نوع 5 + نوع 3 مبلغ ثابت
- قالب Excel ذكي: عمودين دايماً (رقم + مبلغ)، المبلغ إجباري أو اختياري حسب النوع
- الحدود الافتراضية: min=800, max=1800

## القائمة الرئيسية — أعمدة مضافة (2026-04-12)

- **رقم الدفعة**: BATCH_NO كلينك لصفحة تفاصيل الدفعة (بنفسجي)
- **IS_ACTIVE**: تلوين الموظف غير الفعال (خلفية حمراء خفيفة + badge "غير فعال")

## الملفات

```
controllers/Payment_req.php
models/Payment_req_model.php
views/payment_req_index.php
views/payment_req_page.php
views/payment_req_show.php
views/payment_req_batch.php
views/payment_req_batch_history.php
views/payment_req_batch_detail.php
views/payment_req_import_modal.php
views/payment_req_emp_statement.php
database/payment_req/01_ddl.sql
database/payment_req/02_pkg_spec.sql
database/payment_req/03_pkg_body.sql
database/payment_req/04_batch_pkg_spec.sql
database/payment_req/04_batch_pkg_body.sql
database/payment_req/05_batch_ddl.sql
database/payment_req/06_bank_view.sql
```

## Validation — حسب النوع

| النوع | الحقول المطلوبة |
|-------|----------------|
| 1 | الشهر + بند المستحقات + الشهر محتسب |
| 2 | الشهر + بند المستحقات + طريقة الاحتساب + النسبة/المبلغ + الحد الأدنى + الأعلى + الشهر محتسب |
| 3 | الشهر + بند المستحقات + طريقة الاحتساب + النسبة/المبلغ |
| 4 | الشهر + بند المستحقات + الشهر محتسب |
| 5 | الشهر + بند الاستحقاق |

Validation على مستويين: JS (`_wizValidate`) + PHP (`_post_validation`)

## إصلاحات (2026-04-12)

- **BULK_PREVIEW نوع 1**: شُلت شرط `NET_SALARY > 0` من SKIP_REASON — الموظف يُدرج طالما عنده رصيد 323 > 0
- **pay_type لنوع 5**: إضافة option ديناميكي قبل serialize لأنه select ما فيه القيمة
- **صفحة التفاصيل**: placeholder المبلغ ذكي حسب النوع + المبلغ إجباري لنوع 5

## ملاحظات
- functions المقر (GET_EMP_BRANCH) + تنسيق ملف البنك + خيار الصرف بدون شهر + اسم "احتساب الصرف" — تم الاتفاق مع المحاسب إنها مش مطلوبة (2026-04-12)

## نسخ احتياطية (Backups)

### bak1404 — قبل تعديل احتساب نوع 2 لمطابقة النظام القديم (2026-04-14)
- **الملفات:**
  - `database/payment_req/02_pkg_spec_bak1404.sql`
  - `database/payment_req/03_pkg_body_bak1404.sql`
- **سبب النسخة:** المحاسب طلب مطابقة 100% للنظام القديم بحساب نوع 2 (دفعة من الراتب)

---

## Package الاحتساب المتوازي — DISBURSEMENT_CALC_PKG (2026-04-15)

### الفكرة
نسخ طبق الأصل من منطق `SALARYFORM.CAL_SALARY_RATE` بدون لمس الجداول الأصلية.
النتيجة تُخزَّن في جداول مخصصة لـ payment_req.

### الملفات الجديدة

| ملف | الوصف |
|-----|-------|
| `database/payment_req/07_calc_tables.sql` | DDL للجداول الثلاثة الجديدة |
| `database/payment_req/08_calc_pkg_spec.sql` | تعريف الباكج |
| `database/payment_req/09_calc_pkg_body.sql` | المنطق الكامل (نسخ من SALARYFORM) |

### الجداول الجديدة (schema: GFC)

| الجدول | مقابل |
|--------|-------|
| `PAYMENT_REQ_SALARY_CALC_TB` | `DATA.SALARY_TEST` |
| `PAYMENT_REQ_ADMIN_CALC_TB` | `DATA.ADMIN_TEST` + حقول CAPPED_VAL, ACCRUED_323, RATE_USED, L/H_VALUE_USED |
| `PAYMENT_REQ_323_CALC_TB` | بديل إدخال 323 في ADD_AND_DED |

### Package Functions/Procedures

| البروسيجر/Function | الاستخدام |
|-------------------|---------|
| `CAL_SALARY_RATE_PART(month, from, to, rate, L, H, msg)` | **المدخل الرئيسي** — يقوم بالـ 5 خطوات كاملة |
| `GET_DISBURSEMENT_AMOUNT(emp, month)` | قيمة الصرف (CAPPED) |
| `GET_CALC_NET_SALARY(emp, month)` | NET_SALARY بعد الاحتساب |
| `GET_CALC_323_VALUE(emp, month)` | قيمة 323 المحتسبة |
| `HAS_CALC_DATA(emp, month)` | هل الاحتساب تم؟ |
| `CLEAR_CALC_DATA(month, from?, to?)` | تنظيف |

### التعديلات على منطق SALARYFORM الأصلي
1. **INSERTs**: `SALARY_TEST` → `PAYMENT_REQ_SALARY_CALC_TB`
2. **INSERTs**: `ADMIN_TEST` → `PAYMENT_REQ_ADMIN_CALC_TB`
3. **Cursor RET_ADD_DED**: يستثني 323 من `ADD_AND_DED` + UNION مع `PAYMENT_REQ_323_CALC_TB`
4. **Cursor SAL_VALUE**: يقرأ من `PAYMENT_REQ_SALARY_CALC_TB`
5. **TRANS_323_INTERNAL**: يكتب 323 على جدولنا بدلاً من `ADD_AND_DED`
6. **GET_VAL_ADD_DED_PART**: يقرأ من `PAYMENT_REQ_ADMIN_CALC_TB`
7. **UPDATE على DATA.EMPLOYEES** (حالة 200705): **محذوف** — لا نلمس الجداول

### Flow (مطابق لـ SALARYFORM.CAL_SALARY_RATE)

```
CAL_SALARY_RATE_PART:
  1. DELETE FROM PAYMENT_REQ_323_CALC_TB
  2. CAL_SALARY_INTERNAL (بدون 323) → PAYMENT_REQ_SALARY_CALC_TB + PAYMENT_REQ_ADMIN_CALC_TB
  3. DELETE FROM PAYMENT_REQ_323_CALC_TB (احتياط)
  4. TRANS_323_INTERNAL → PAYMENT_REQ_323_CALC_TB
  5. CAL_SALARY_INTERNAL (مع 323) → تحديث PAYMENT_REQ_ADMIN_CALC_TB
  6. UPDATE CAPPED_VAL, ACCRUED_323

النتيجة: PAYMENT_REQ_ADMIN_CALC_TB.NET_SALARY = قيمة الصرف (CAPPED)
```

### خطوات التفعيل (Oracle)

```sql
-- 1. إنشاء الجداول
@database/payment_req/07_calc_tables.sql

-- 2. إنشاء Package spec
@database/payment_req/08_calc_pkg_spec.sql

-- 3. إنشاء Package body
@database/payment_req/09_calc_pkg_body.sql

-- 4. اختبار
DECLARE V_MSG VARCHAR2(4000); BEGIN
  GFC_PAK.DISBURSEMENT_CALC_PKG.CAL_SALARY_RATE_PART(202509, 1040, 1040, 34, 800, 1800, V_MSG);
  DBMS_OUTPUT.PUT_LINE(V_MSG);
END;

SELECT EMP_NO, MONTH, NET_SALARY, CAPPED_VAL, ACCRUED_323
FROM GFC.PAYMENT_REQ_ADMIN_CALC_TB WHERE EMP_NO = 1040 AND MONTH = 202509;
-- المتوقع: NET_SALARY = 1031.70
```

### سلامة الجداول الأصلية (Zero Risk)
- ✅ **لا** INSERT على `DATA.SALARY`, `DATA.ADMIN`, `DATA.ADD_AND_DED`, `DATA.SALARY_TEST`, `DATA.ADMIN_TEST`
- ✅ **لا** UPDATE على `DATA.EMPLOYEES`
- ✅ **قراءة فقط** من كل الجداول الأصلية
- ✅ كل الكتابة على `GFC.PAYMENT_REQ_*_CALC_TB`

### التكامل في DISBURSEMENT_PKG (2026-04-15) ✅

#### 1. `CALC_PART_SALARY_LEGACY` - Safe Wrapper
- **قبل:** كان يعدّل ADD_AND_DED (Backup/Restore)
- **بعد:** wrapper آمن يستدعي `DISBURSEMENT_CALC_PKG.CAL_SALARY_RATE_PART`
- **Cache:** يفحص `RATE_USED`, `L_VALUE_USED`, `H_VALUE_USED` قبل الاحتساب

```sql
-- Flow:
-- 1. SELECT COUNT(*) FROM PAYMENT_REQ_ADMIN_CALC_TB WHERE emp/month/rate/L/H match
-- 2. IF 0 -> CAL_SALARY_RATE_PART للموظف الواحد
-- 3. RETURN GET_DISBURSEMENT_AMOUNT(emp, month)
```

#### 2. `PAYMENT_REQ_BULK_PREVIEW` نوع 2
- احتساب جماعي قبل فتح الـ cursor: `CAL_SALARY_RATE_PART(month, 1, 999999, rate, L, H, msg)`
- الـ cursor يقرأ مباشرة من `GFC.PAYMENT_REQ_ADMIN_CALC_TB.CAPPED_VAL`
- `LEFT JOIN` مع `PAYMENT_REQ_ADMIN_CALC_TB` على `(EMP_NO, MONTH)`

#### 3. `PAYMENT_REQ_BULK_CREATE` نوع 2
- قبل حلقة `DETAIL_INSERT`: احتساب جماعي للنطاق الكامل
- `DETAIL_INSERT` ? `PREPARE_REQUEST_VALUES` ? `CALC_PART_SALARY_LEGACY` ? cache hit ? سريع

### حالات الاستخدام (معتمدة)

| السيناريو | الاستدعاء |
|-----------|-----------|
| كل الموظفين (BULK_PREVIEW/CREATE) | `CAL_SALARY_RATE_PART(month, 1, 999999, rate, L, H)` |
| موظفين محددين | `DETAIL_INSERT` لكل موظف (كل واحد يستدعي CALC للموظف الواحد) |
| موظف واحد (DETAIL_INSERT) | `CALC_PART_SALARY_LEGACY` ? CALC للموظف الواحد |
| شاشة المعاينة فقط | نفس أعلاه |

### ملفات التفعيل (بالترتيب)

```sql
-- مرة واحدة: إنشاء الجداول
@database/payment_req/07_calc_tables.sql

-- مرة واحدة: إنشاء الباكج الجديد
@database/payment_req/08_calc_pkg_spec.sql
@database/payment_req/09_calc_pkg_body.sql

-- مرة واحدة: Grants + Public Synonyms
@database/payment_req/10_calc_grants.sql

-- إعادة تكمبيل الباكج الرئيسي (فقط body — spec مش محتاج تغيير)
@database/payment_req/03_pkg_body.sql
```

### الخطوة التالية
- اختبار في الواجهة: `/payment_req/create` → نوع 2 → معاينة → إنشاء
- التحقق: القيم في الطلب = CAPPED_VAL من `PAYMENT_REQ_ADMIN_CALC_TB`

### إصلاح نوع 2 — نسبة فقط + الاحتساب الدقيق (2026-04-15)

**المشكلة 1:** `PREPARE_REQUEST_VALUES` نوع 2 كان يدعم طريقتين (PERCENT/FIXED) + يقيّد الناتج بقيمة 323 الأصلية:
```sql
V_AMOUNT := CALC_PART_SALARY_LEGACY(...);   -- صحيح (CAPPED_VAL)
V_AMOUNT := LEAST(V_AMOUNT, V_323);          -- ❌ V_323 من DATA.SALARY القديم
```
فالإضافة اليدوية تُقيّد الصرف بقيمة 323 القديمة بدلاً من CAPPED_VAL (المحتسب بالنسبة والحدود).

**المشكلة 2:** نوع 2 من حيث التصميم = نسبة فقط، لا يوجد "مبلغ ثابت" (هذا للنوع 3 فقط).

**الحل** (في 3 طبقات — database + controller + view):

1. **`03_pkg_body.sql`** — `PREPARE_REQUEST_VALUES` نوع 2:
   - حُذف شرط `P_CALC_METHOD NOT IN (...)` — النوع 2 = PERCENT فقط
   - حُذف فرع FIXED كلياً
   - حُذف `LEAST(V_AMOUNT, V_323)` — CAPPED_VAL هو القيمة النهائية

2. **`Payment_req.php::_post_validation`** نوع 2:
   - حُذف فحص `calc_method` و `req_amount`
   - `percent_val` + `l_value` + `h_value` فقط

3. **`payment_req_show.php`**:
   - `wizSelectType(2)`: يُخفي dropdown طريقة الاحتساب (`#show_calc_method_grp.hide()`)
   - `_wizValidate` نوع 2: يتطلب `percent_val` فقط

**يؤثر على:** كل عمليات نوع 2 — إنشاء، اعتماد، إضافة يدوية، تعديل مبلغ، معاينة فردية.

**التفعيل:** إعادة تكمبيل `@database/payment_req/03_pkg_body.sql` + refresh الصفحات.

---

## تحسينات شاملة (2026-04-21)

### 1. مطابقة المعاينة مع الإنشاء — نوع 2 (PART_SALARY)

**المشكلة:** شاشة المعاينة تعرض 799 موظف، لكن الإنشاء يُنشئ 797 فقط (موظف 482 و 811 مُستبعدَان). السبب: `DETAIL_INSERT → PREPARE_REQUEST_VALUES` يتحقق من وجود بند 323 في `DATA.SALARY`، بينما المعاينة تقرأ من `PAYMENT_REQ_ADMIN_CALC_TB` (احتساب نظري).

**الإصلاح** — `PAYMENT_REQ_BULK_PREVIEW` نوع 2: توسيع `SKIP_REASON` ليشمل:
```sql
CASE
  WHEN NVL((SELECT SUM(S.VALUE) FROM DATA.SALARY S
            WHERE S.EMP_NO = C.EMP_NO AND S.CON_NO = C_SALARY_CON_DUES AND S.MONTH = V_M), 0) <= 0
       THEN 'لا يوجد مستحقات (بند 323) لهذا الشهر'
  WHEN NVL(C.CAPPED_VAL, 0) <= 0 THEN 'لا يوجد مبلغ للصرف'
  ELSE NULL
END AS SKIP_REASON
```

الـ JS في `_wizRenderPreview` كان أصلاً يفلتر `!r.SKIP_REASON` لحساب `eligible` — فالبطاقات تتحاذى تلقائياً بعد هذا الإصلاح.

### 2. تحسين PAYMENT_REQ_UNAPPROVE

- فحص إضافي: `V_PAID_CNT > 0` → يرفض لو أي detail بحالة مصروف (حماية ضد حالات جزئية)
- استخدام `CALC_MASTER_STATUS(P_REQ_ID)` بدل تعيين `DRAFT` ثابتاً
- `APPROVE_USER/DATE` يُحفظان لو الحالة الناتجة APPROVED/PARTIAL_APPROVED

### 3. تجميع BULK_ADD في PAYMENT_REQ_BULK_CREATE

**المشكلة:** BULK_CREATE لـ 1000 موظف كان يولّد 1000 سجل `ADD_EMP` في `PAYMENT_REQ_LOG_TB` — سجل ضخم مزعج للمحاسب.

**الحل:**
- متغير داخلي في الـ body: `G_SUPPRESS_LOG_ADD_EMP BOOLEAN := FALSE;`
- `BULK_CREATE` يُفعّله قبل الحلقة، يُعيده بعدها، ويكتب **سجل واحد** `BULK_ADD`:
  ```
  LOG_ACTION(V_MASTER_ID, 'BULK_ADD', NULL, NULL,
    'دفعة إضافة: OK=' || V_OK || ' SK=' || V_SK || ' ERR=' || V_ER);
  ```
- `DETAIL_INSERT` يفحص `IF NOT G_SUPPRESS_LOG_ADD_EMP THEN ...`
- label جديد في الـ view: `'BULK_ADD' => ['إضافة جماعية', 'fa-users', '#2563eb']`

### 4. فصل صلاحيتي "إلغاء الطلب" و "إلغاء الاعتماد"

**المشكلة:** زر "إلغاء الاعتماد" داخل النموذج كان ينادي `doCancel` → يُلغي الطلب بالكامل (CANCEL).

**الحل — صلاحيتان منفصلتان:**

| الصلاحية | URL | الإجراء | مكانها |
|---|---|---|---|
| اعتماد | `/approve` | APPROVE | داخل النموذج |
| **إلغاء الاعتماد (جديدة)** | `/unapprove` | UNAPPROVE (→ مسودة) | داخل النموذج |
| إلغاء الطلب | `/delete` | CANCEL (→ ملغى) | خارج النموذج (القائمة) |

- في `payment_req_show.php`: زر "إلغاء الاعتماد" يستخدم `HaveAccess($unapprove_url)` ويستدعي `doUnapprove` (تنادي `/unapprove`)
- في `payment_req_page.php`: زر القائمة تحوّل لـ "إلغاء الطلب" (أحمر + `fa-ban`)
- Controller `unapprove` يفحص `HaveAccess('.../unapprove')` بدل `.../approve`

**SQL إضافة الصلاحية الجديدة:**
```sql
INSERT INTO GFC.SYSTEM_MENU_TB (MENU_NO, MENU_PARENT_NO, MENU_ADD, MENU_FULL_CODE, MAIN_MENU, VIEW_MENU, SORT, SYSTEM_ID)
SELECT SYSTEM_MENU_TB_SEQ.NEXTVAL, MENU_PARENT_NO, 'إلغاء اعتماد طلب صرف',
       'payment_req/payment_req/unapprove', MAIN_MENU, VIEW_MENU, NVL(SORT,0)+1, SYSTEM_ID
  FROM GFC.SYSTEM_MENU_TB WHERE MENU_FULL_CODE = 'payment_req/payment_req/approve';
COMMIT;

-- MERGE آمن (يتخطى التكرار): الـ UK1 على USER_MENUS_TB
MERGE INTO GFC.USER_MENUS_TB T USING (
    SELECT DISTINCT UM.USER_NO, (SELECT MENU_NO FROM GFC.SYSTEM_MENU_TB
         WHERE MENU_FULL_CODE = 'payment_req/payment_req/unapprove') AS NEW_MENU_NO
    FROM GFC.USER_MENUS_TB UM JOIN GFC.SYSTEM_MENU_TB SM ON SM.MENU_NO = UM.MENU_NO
    WHERE SM.MENU_FULL_CODE = 'payment_req/payment_req/approve'
) S ON (T.USER_NO=S.USER_NO AND T.MENU_NO=S.NEW_MENU_NO)
WHEN NOT MATCHED THEN INSERT (USER_NO, MENU_NO) VALUES (S.USER_NO, S.NEW_MENU_NO);
COMMIT;
```

### 5. UI — شاشة تفاصيل الطلب (payment_req_show.php)

#### `info-bar` بديل `info-row`
البطاقات الخمس الكبيرة (شهر/نوع/بند/موظفين/إجمالي) تحوّلت إلى شريط chips متراص + بطاقة واحدة بارزة للإجمالي — يمنع ظهور بطاقات فارغة.

#### سجل العمليات — فلتر افتراضي
العمليات الفردية (`ADD_EMP`, `REMOVE_EMP`, `UNAPPROVE_EMP`, `UNPAY_EMP`) مخفية افتراضياً. العنوان يعرض: `(N رئيسية + M فردية)` + checkbox "إظهار عمليات الموظفين الفرديين".

#### تقييد إمكانية الإضافة
```php
$can_add = !$isCreate && $cur_status == 0; // فقط مسودة (كان 0,3,4)
$can_edit_form = $isCreate;                // زر الحفظ فقط في الإنشاء
```

#### Hidden inputs لبيانات الماستر (مهم جداً)
الـ wizard form كان داخل `<?php if ($isCreate): ?>` فقط. في شاشة show كانت `$('#the_month').val()` ترجع `undefined`. الحل:
```php
<?php if (!$isCreate && $HaveRs): ?>
<input type="hidden" id="the_month"   value="<?= $rs['THE_MONTH']   ?? '' ?>">
<input type="hidden" id="req_type"    value="<?= $rs['REQ_TYPE']    ?? '' ?>">
<input type="hidden" id="calc_method" value="<?= $rs['CALC_METHOD'] ?? '' ?>">
<input type="hidden" id="percent_val" value="<?= $rs['PERCENT_VAL'] ?? '' ?>">
<input type="hidden" id="show_l_value" value="<?= $rs['L_VALUE']    ?? '' ?>">
<input type="hidden" id="show_h_value" value="<?= $rs['H_VALUE']    ?? '' ?>">
<?php endif; ?>
```
تستخدمها `addToQueue`, `detailPreviewUrl`, `_renderWalletSummary` — تعمل بدون DOM الـ wizard.

### 6. تحديث ديناميكي بالكامل بعد الحذف (بدون reload)

**Controller** — توسيع `detail_list` ليرجع في نداء واحد:
```json
{ "ok": true, "data": [...rows], "master": {STATUS, STATUS_NAME, EMP_COUNT, TOTAL_AMOUNT}, "logs": [...] }
```
`EMP_COUNT` و `TOTAL_AMOUNT` تستبعد الصفوف الملغية (DETAIL_STATUS != 9).

**JS** — `deleteDetail` تستدعي `refreshDetails()` التي تُحدّث:
- جدول الموظفين (نفس أعمدة PHP: checkbox/#/الموظف/المقر/الصافي/مبلغ الصرف/سيُرحّل مستحقات/الحالة/ملاحظة/إجراء)
- `#empCountVal` + `#totalAmountVal` (من `j.master`)
- شارة الماستر (`_updateMasterStatus`) — تبدّل `className` من `s1` إلى `s3`
- جدول السجل (`_renderLogRows`) + عدّادات رئيسية/فردية
- بطاقات "ملخص الرواتب للشهر" (`_renderWalletSummary(reqAmount, totalAccrued323)`) — تنادي `salCheckUrl` + تعيد بناء البطاقات السبع
- `totalAccrued323` يُحسب في JS من `rows.reduce` على `ACCRUED_323_CALC` للصفوف غير الملغية

**زر الإجراء في الجدول — أيقونة ديناميكية حسب الحالة:**
| DETAIL_STATUS | الأيقونة | العنوان |
|---|---|---|
| 0 (مسودة) | fa-trash | حذف |
| 1 (معتمد) | fa-undo | إلغاء اعتماد |
| 2 (مصروف) | fa-reply | عكس صرف |

### 7. UI — شاشة احتساب الصرف (payment_req_batch.php)

`renderPreview` توسّعت لتعرض نفس تفاصيل `batch_detail.php`:
- **بطاقات علوية:** الموظفين (+عدد الحركات) / البنوك / المقرات / إجمالي الصرف
- **جدول "حسب المقر":** موظفين + مبلغ لكل مقر + إجمالي
- **جدول البنوك موسّع** بـ 9 أعمدة (بدل 4): #/البنك/الفروع/الموظفين/**راتب كامل**/**راتب جزئي**/**مستحقات**/**إضافات**/الإجمالي
  - خريطة الأنواع: `1→T1`, `2→T2`, `3+4→T3`, `5→T4`
  - يتم جمعها في نفس حلقة `rows`

### 8. الملفات المعدّلة

```
database/payment_req/03_pkg_body.sql                      ← BULK_PREVIEW + UNAPPROVE + BULK_ADD
application/modules/payment_req/controllers/Payment_req.php  ← unapprove perm + detail_list توسيع
application/modules/payment_req/views/payment_req_show.php   ← info-bar + سجل + hidden inputs + refreshDetails ديناميكي
application/modules/payment_req/views/payment_req_page.php   ← زر "إلغاء الطلب" (أحمر)
application/modules/payment_req/views/payment_req_batch.php  ← بطاقات + جدول مقر + بنوك بأعمدة أنواع
```

### 9. سيناريوهات حذف الموظف — ما يتغيّر

| العملية (DETAIL_STATUS) | الجدول | الإجمالي | عدد الموظفين | حالة الماستر |
|---|---|---|---|---|
| حذف مسودة (0) | يختفي الصف | ⬇️ يقلّ | ⬇️ يقلّ | قد تبقى مسودة |
| إلغاء اعتماد (1→0) | الصف يبقى | ↔ ثابت | ↔ ثابت | 1 → 3 (جزئي) |
| عكس صرف (2→1) | الصف يبقى | ↔ ثابت | ↔ ثابت | 2 → 3 أو 1 |

### 10. ملاحظة مهمة عن بطاقة "إجمالي بند 323"

البطاقة تعرض `N موظف (الشهر كله)` — هذا **على مستوى الشهر** (من `DATA.ADMIN`) وليس الطلب. حذف موظف من الطلب **لا** يغيّر هذا الرقم (الموظف ما زال موجود في الشهر). أضيف نص صغير `<small>(الشهر كله)</small>` + tooltip للتوضيح.
