---
name: PHP/Oracle Gotchas في المشروع — pitfalls شائعة
description: أخطاء صامتة تحدث في DBConn و excuteProcedures و heredoc و form scope
type: feedback
originSessionId: de9d02d3-f232-41d9-8bb0-df68ed0272c7
---
أربع pitfalls اكتُشفت في موديول `payment_accounts` (2026-04-26). كلها صامتة (لا تطلع errors واضحة) لكنها تكسر الـ data flow.

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

## ٦. الـ Procedure الكفؤة — CTEs بدل scalar subqueries

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
