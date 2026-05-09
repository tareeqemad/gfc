---
name: PHP/Oracle Gotchas في المشروع — pitfalls شائعة
description: أخطاء صامتة تحدث في DBConn و excuteProcedures و heredoc و form scope
type: feedback
originSessionId: de9d02d3-f232-41d9-8bb0-df68ed0272c7
---
**pitfalls اكتُشفت في موديولي `payment_accounts` و `payment_req` (2026-04-26 → 2026-04-29). كلها صامتة (لا تطلع errors واضحة) لكنها تكسر الـ data flow.**

## ١. `excuteProcedures` يستخدم `value` كمفتاح للـ output

في `application/libraries/DBConn.php`:
```php
$this->output[$param['value']] = $param['value'];
$bind = oci_bind_by_name($stmt, $param['name'], $this->output[$param['value']], ...);
```

الـ `name` (مثلاً `:P_MSG_OUT`) فقط للـ Oracle binding. الـ output يُخزن بمفتاح = `value`.

```php
// param
array('name' => ':P_MSG_OUT', 'value' => 'MSG', 'type' => SQLT_CHR, 'length' => -1)

// قراءة
return $result['MSG'];     // ✅ صحيح
return $result['P_MSG_OUT']; // ❌ يرجع null دائماً
```

نفس الشي لكل OUT params:
- `'value' => 'CNT'` → `$result['CNT']`
- `'value' => 'TOT'` → `$result['TOT']`
- `'value' => 'CUR_RES'` → `$result['CUR_RES']` (الـ cursor data)

⚠️ **مهم:** payment_req يستخدم `'value' => 'MSG_OUT'` فيتطابق مع `$result['MSG_OUT']`. أي قيمة تختارها، اقرأ بنفسها.

## ٢. GRANT SELECT TO PUBLIC إجباري على الجداول

**العَرَض:** الـ procedure ترجع 12 صف من PL/SQL Developer، لكن من PHP ترجع `array(0)` بدون أي error.

**السبب:**
- الـ pkg بـ definer rights — تنفذ بـ `GFC_PAK` privileges
- لكن عند الـ fetch من cursor في PHP، الـ session هو user آخر (مثلاً `telbawab`)
- لو user الـ session ما عنده SELECT على الجداول، الـ `oci_execute($refcur, OCI_DEFAULT)` يفشل
- في `excuteCursor`، الفشل يُبتلع بـ `@` ويوضع `$this->output[(int)$param['value']] = array();`
- النتيجة: array فاضي بدون error في الـ logs

**الحل:** GRANT SELECT TO PUBLIC على كل الجداول التي يقرأها الـ cursor:
```sql
GRANT SELECT ON GFC.PAYMENT_PROVIDERS_TB TO PUBLIC;
-- إلخ لكل جدول
```

وضّح هذا في DDL مع: `GRANT SELECT, INSERT, UPDATE, DELETE TO GFC_PAK;` + `GRANT SELECT TO PUBLIC;` (مثل payment_req DDL).

## ٣. `<input name="search">` يخفي `function search()`

داخل `<form>`، الـ `<input name="X">` يُنشئ named property على الـ form object. عند استدعاء `search()` من `onclick` لزر داخل الـ form:

scope chain:
1. button → no `search`
2. **form → `form.search` = الـ input element** ← يُستخدم!
3. document
4. window → فيه `function search` لكن ما يصل

النتيجة: `Uncaught TypeError: search is not a function`.

**الحل:** غيّر الـ input name لاسم لا يتعارض (مثل `q` بدل `search`):
```html
<!-- قبل -->
<input type="text" name="search" id="txt_search">

<!-- بعد -->
<input type="text" name="q" id="txt_search">
```

و في الـ JS payload:
```js
var values = { ..., q: $('#txt_search').val() };
```

والـ controller يقرأ من `$q` ويمررها كـ `search` للـ procedure.

⚠️ نفس المشكلة قد تحدث مع أي اسم function عام: `submit`, `reset`, `elements`, `length`, `name`, `action`, `method`. تجنبها كأسماء inputs.

## ٤. heredoc — escape rules

```php
$scripts = <<<SCRIPT
<script>
    $('#id')           // ✅ آمن — `(` يكسر تفسير المتغير
    $.ajax(...)        // ✅ آمن — `.` يكسر التفسير
    {$php_var}         // ✅ يُفسَّر متعمداً
    \$variable_name    // ✅ escape مطلوب — لو الحرف بعد $ صالح
    "string\\nwith"    // ⚠️ \n يصبح newline فعلي في output
</script>
SCRIPT;
```

**قاعدة:** استخدم `String.fromCharCode(10)` بدل `'\n'` لتفادي تفسير PHP لـ `\n`.

## ٥. `echo $scripts;` vs `sec_scripts($scripts);`

❌ `echo $scripts;` → يطبع الـ JS داخل الـ view content، **قبل** تحميل jQuery → خطأ `$ is not defined`

✅ `sec_scripts($scripts);` → يخزن في config، يُطبع عبر `put_headers('js')` في `template1.php:381`، **بعد** تحميل jQuery

## ٦. `SQLT_INT` مع value=null → يتحوّل لـ 0 (مش NULL)

**العَرَض:** procedure ترجع 0 صف في PHP بينما SQL Developer ترجع البيانات كاملة.

**السبب:** عند binding `null` كـ `SQLT_INT`، الـ OCI8 driver يحوّله لـ `0` بدل NULL. لو الـ procedure فيها شرط `WHERE (P_TYPE IS NULL OR PROVIDER_TYPE = P_TYPE)`:
- متوقع: `WHERE (NULL IS NULL OR ...)` = TRUE → كل الصفوف
- فعلي: `WHERE (0 IS NULL OR PROVIDER_TYPE = 0)` = FALSE (ما في صفوف بـ TYPE=0)

**الحل:** استخدم `'type' => ''` بدل `SQLT_INT` للـ params اللي قد تكون NULL:
```php
// ❌ غلط
array('name' => ':P_TYPE', 'value' => $type, 'type' => SQLT_INT, 'length' => -1)

// ✅ صحيح — الـ '' يخلي OCI8 يستنتج النوع، فـ null تنتقل كـ NULL
array('name' => ':P_TYPE', 'value' => $type, 'type' => '', 'length' => -1)
```

⚠️ هذا الـ pattern أصاب `providers_list($type=null)` — الـ dropdown كان فاضي مع إن في 11 مزود في DB.

## ٧. `length => -1` للـ MSG_OUT (OUT VARCHAR2) → buffer 3 chars فقط

**العَرَض:** `ORA-06502: PL/SQL: numeric or value error: character string buffer too small` ثم `msg:"MSG"` في الاستجابة.

**السبب:**
```php
$this->output[$param['value']] = $param['value'];  // = 'MSG' (3 chars)
oci_bind_by_name($stmt, ':P_MSG_OUT', $this->output['MSG'], -1, SQLT_CHR);
```
- `length => -1` يجعل الـ buffer = طول القيمة الابتدائية = 3 chars (طول "MSG")
- لما الـ procedure يحاول يكتب `'لا يمكن الحذف: المزود عنده 7 فرع، احذف الفروع أولاً'` (طويل) → buffer overflow → ORA-06502
- الـ exception handler يحاول يكتب `SQLERRM` (نفس الخطأ) → cascade
- الـ buffer يضل بقيمته الأصلية `'MSG'` فيظهر للمستخدم

**الحل:** خصّص buffer واسع للـ OUT VARCHAR2:
```php
array('name' => ':P_MSG_OUT', 'value' => 'MSG', 'type' => SQLT_CHR, 'length' => 500)
```

⚠️ الـ pattern `length => -1` كان يصيب 35 instance في `Payment_accounts_model.php` — كل عمليات Insert/Update/Delete/Toggle. استبدلت كلها بـ 500.

📌 ملاحظة: الـ `general_get` في `New_rmodel.php` يُعيد كتابة الـ MSG_OUT بـ `length => 500` تلقائياً. المشكلة فقط في الـ functions اللي تستدعي `excuteProcedures` مباشرة.

## ٨. الويب يتصل بـ Oracle باسم الـ user الموقّع (مش GFC_PAK)

في `application/libraries/DBConn.php:32-39`:
```php
if($this->CI->session->userdata('user_data')){
    $current_user = $this->CI->session->userdata('user_data');
    $db_pass = 'A'.substr(md5($current_user->db_pwd),3);
    $this->conn = oci_new_connect($current_user->username, $db_pass, ...);
}
```

→ كل user مسجّل دخول له **حساب Oracle خاص فيه**. PL/SQL Developer يتصل بـ GFC_PAK (الـ schema) لكن الويب يتصل بـ user الجلسة.

**النتيجة المهمة:** GRANTs لـ PUBLIC (point #2) ضرورية لكل الجداول والـ packages. بدونها، الـ procedures ترجع فاضية بدون error.

```sql
GRANT EXECUTE ON GFC_PAK.<PKG_NAME> TO PUBLIC;
GRANT SELECT ON GFC.<TABLE_NAME> TO PUBLIC;
CREATE OR REPLACE PUBLIC SYNONYM <PKG_NAME> FOR GFC_PAK.<PKG_NAME>;
```

## ٩. Excel: استخدم `setCellValueExplicit` للـ IDs و IBANs

عند تصدير Excel (PhpSpreadsheet)، الـ `setCellValue` العادي يحوّل الأرقام الطويلة لـ scientific notation أو يحذف leading zeros:
- `924921596` → `9.24921596E+08` ❌
- `0599xxxx` → `599xxxx` ❌
- `PS37PALS...` → OK (string)

```php
// ❌ غلط
$sheet->setCellValue('C' . $row, $r['EMP_ID']);

// ✅ صحيح — يحفظ القيمة كـ STRING ويمنع تحويل Excel
$sheet->setCellValueExplicit('C' . $row, (string)$r['EMP_ID'],
    \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
```

استخدمها لكل: رقم الهوية، IBAN، رقم الحساب البنكي، رقم المحفظة.

## ١٠. الـ Procedure الكفؤة — CTEs بدل scalar subqueries

عند الحاجة لـ counts متعددة لكل صف في paginated list:

❌ سيء (لـ 1800 موظف = 9000 query operation):
```sql
SELECT E.NO,
       (SELECT COUNT(*) FROM ACC WHERE EMP_NO = E.NO) AS C1,
       (SELECT COUNT(*) FROM ACC WHERE EMP_NO = E.NO AND TYPE=1) AS C2,
       ...
  FROM EMPLOYEES E
 WHERE [filters]
```

✅ كفؤ:
```sql
WITH ACC_AGG AS (SELECT EMP_NO, COUNT(*) C1, SUM(CASE WHEN TYPE=1 THEN 1 ELSE 0 END) C2
                   FROM ACC GROUP BY EMP_NO),
     PAGED AS (SELECT E.NO, ROW_NUMBER() OVER (ORDER BY E.NO) AS RN
                 FROM EMPLOYEES E
                WHERE [filters])
SELECT P.*, AC.*
  FROM PAGED P LEFT JOIN ACC_AGG AC ON ...
 WHERE P.RN BETWEEN V_OFFSET+1 AND V_OFFSET+V_LIMIT
```

الفرق: الـ joins تعمل على 200 صف فقط (الصفحة)، مش 1800.

## ١١. Oracle nested correlation — `ORA-00904: invalid identifier`

**العَرَض:** عند subquery داخل LISTAGG/SELECT داخل الـ outer SELECT، الـ outer alias لا يتم حله:

```sql
SELECT BD.EMP_NO,
       (SELECT LISTAGG(BANK_LBL, '/') WITHIN GROUP (ORDER BY BANK_LBL)
          FROM (SELECT DISTINCT NVL(SNAP_PROVIDER_NAME, ...) AS BANK_LBL
                  FROM PAYMENT_BATCH_DETAIL_TB
                 WHERE BATCH_ID = BD.BATCH_ID    -- ❌ ORA-00904: "BD"."BATCH_ID"
                   AND EMP_NO   = BD.EMP_NO))    AS BANK_NAME
  FROM PAYMENT_BATCH_DETAIL_TB BD
 GROUP BY BD.BATCH_ID, BD.EMP_NO
```

**السبب:** Oracle (12c-19c) لا يدعم correlation عبر nested inline view على عمق ٢. الـ inline view الداخلية (`SELECT DISTINCT ...`) لا ترى `BD` من الـ outer query.

**حلول:**

### حل ١ — alias صريح للجدول الداخلي
```sql
(SELECT LISTAGG(...) FROM (
    SELECT DISTINCT BD3.EMP_NO, BD3.BATCH_ID, ...
      FROM PAYMENT_BATCH_DETAIL_TB BD3   -- ← alias مهم
     WHERE BD3.BATCH_ID = BD.BATCH_ID
       AND BD3.EMP_NO   = BD.EMP_NO
))
```

### حل ٢ — استخدام aggregate function (الأفضل)
بدل الـ subquery، نستخدم `LISTAGG` كـ aggregate في الـ outer query مع `GROUP BY`:

```sql
SELECT BD.EMP_NO,
       LISTAGG(NVL(BD.SNAP_PROVIDER_NAME, ...), ' / ')
          WITHIN GROUP (ORDER BY ...) AS BANK_NAME
  FROM PAYMENT_BATCH_DETAIL_TB BD
 GROUP BY BD.BATCH_ID, BD.EMP_NO
```

ولإزالة التكرار (لو مش Oracle 19c+ حيث `LISTAGG(DISTINCT ...)`):
```sql
REPLACE(
  REGEXP_REPLACE(
    LISTAGG(name, '|') WITHIN GROUP (ORDER BY name),
    '([^|]+)(\|\1)+', '\1'    -- يزيل التكرار: A|A|B → A|B
  ),
  '|', ' / '
)
```
