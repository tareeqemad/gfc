---
name: نمط كتابة JavaScript في المشروع — إجباري
description: قواعد JS للالتزام بنمط مشروع GFC (CodeIgniter 3) — لا fetch، لا $.ajax مباشر، استخدم helpers
type: feedback
originSessionId: de9d02d3-f232-41d9-8bb0-df68ed0272c7
---
## القاعدة
الالتزام بنمط الـ JS الموجود في المشروع كاملاً — موديول `payment_req` هو المرجع. لا تكتب JS بطريقة مختلفة عن باقي المشروع.

**Why:** المستخدم صرّح صراحة (2026-04-26) إن طريقة كتابة JS كانت مختلفة عن المشروع وطلب الالتزام الكامل بنمط المشروع. باقي الكود يعتمد على helpers موحّدة (get_data, success_msg, …) واختلاف النمط يكسر التناسق.

**How to apply:** عند تعديل/إضافة أي view أو JS في هذا المشروع، تأكد إنك:

### 1. تعليق الـ scripts بالـ view
طريقتان مقبولتان (موجودتان في الموديول):

**(أ) heredoc + متغيرات PHP داخل JS:**
```php
<?php
$scripts = <<<SCRIPT
<script type="text/javascript">
    function search(){
        get_data('{$get_page_url}', values, function(data){
            $('#container').html(data);
            reBind();
        }, 'html');
    }
</script>
SCRIPT;
echo $scripts; // أو يُمرّر لـ template
?>
```

**(ب) ob_start + sec_scripts** (للـ JS الطويل اللي ما يحتاج interpolation كثير):
```php
<?php ob_start(); ?>
<script type="text/javascript">
    var someUrl = "<?= base_url('module/ctrl/action') ?>";
    // ...
</script>
<?php sec_scripts(ob_get_clean()); ?>
```

### 2. AJAX — استخدم helper `get_data` لا `$.ajax` ولا `fetch`
```js
get_data(url, dataObj, function(resp){
    var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
    if (j.ok) { success_msg('تم', j.msg); }
    else { danger_msg('خطأ', j.msg); }
}, 'json'); // أو 'html'
```
- `get_data(url, data, callback, type)` معرّف في `assets-n/js/function.js:1306` — يعمل POST + showLoading/HideLoading + معالجة 401/500.
- بديل بدون loading: `get_dataWithOutLoading`
- التصفّح بالصفحات: `ajax_pager(values)` و `ajax_pager_data('#tbody', values)`

### 3. الرسائل — استخدم helpers لا alert/console
```js
success_msg('تم', 'رسالة النجاح');
danger_msg('خطأ', 'رسالة الفشل');
warning_msg('تحذير', '...');
info_msg('معلومة', '...');
```
معرّفة في `assets-n/js/function.js` (سطور 220-237).

### 4. CSRF
في الـ view: `<?php echo AntiForgeryToken(); ?>` — يضع hidden input ويعالج الـ token تلقائياً مع `get_data`.

### 5. النماذج
```js
clearForm($('#form_id'));         // مسح كل الحقول
$('.sel2').select2('val', '');    // مسح select2
```

### 6. jQuery هو المعيار
- `$('#id').val()`, `.html()`, `.on('change', ...)` — لا تستخدم vanilla JS مع `document.getElementById` إلا للـ tooltips.
- `$(function(){...})` لـ DOM ready.
- Select2 للـ dropdowns: `$('.sel2').select2()`.
- التواريخ: `$('#field').datetimepicker({format: 'YYYYMM', minViewMode: 'months', pickTime: false})`.

### 7. أسماء الـ functions القياسية في الـ index views
- `reBind()` — يُنادى بعد كل تحميل ajax_pager
- `LoadingData()` — `ajax_pager_data('#page_tb > tbody', values_search(0))`
- `values_search(add_page)` — يجمع قيم الفلاتر
- `search()` — البحث الرئيسي
- `loadData()` — تحميل الصفحة
- `clear_form()` — مسح وإعادة تحميل

### 8. نصوص متعدّدة الأسطر في confirm
استخدم `String.fromCharCode(10)` بدل `\n` (تجنّب escaping مع heredoc):
```js
var nl = String.fromCharCode(10);
if(!confirm('سؤال؟' + nl + nl + 'تفاصيل')) return;
```

### 9. URLs ديناميكية
- داخل heredoc: `'{$url_var}'`
- داخل ob_start script: `"<?= $url_var ?>"` أو `<?= json_encode($url) ?>` (آمن للـ quotes)

## مرجع
- `application/modules/payment_req/views/payment_req_index.php` — مرجع heredoc/SCRIPT
- `application/modules/payment_req/views/payment_req_show.php` — مرجع ob_start/sec_scripts
- `assets-n/js/function.js` — كل الـ helpers
