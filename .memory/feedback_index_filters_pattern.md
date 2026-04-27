---
name: نمط شاشة الـ index والفلاتر — إجباري
description: قواعد كتابة index views والفلاتر والـ pagination — مرجع payment_req_index.php
type: feedback
originSessionId: de9d02d3-f232-41d9-8bb0-df68ed0272c7
---
## القاعدة
كل شاشة `*_index.php` يجب أن تتبع نفس نمط `application/modules/payment_req/views/payment_req_index.php` بالضبط — في بنية الـ HTML والفلاتر والـ JS والـ controller.

**Why:** المستخدم صرّح (2026-04-26) إن طريقة كتابة الـ index كانت مختلفة عن نمط المشروع. باقي الكود يعتمد على نفس الـ helpers و IDs وبنية الـ pagination، فالاختلاف يكسر التناسق ويصعّب الصيانة.

**How to apply:** عند إنشاء/تعديل أي `*_index.php`، التزم بالبنية التالية حرفياً:

---

### 1. بداية الملف — PHP setup
```php
<?php
$MODULE_NAME = 'module_name';
$TB_NAME     = 'tb_name';

$create_url   = base_url("$MODULE_NAME/$TB_NAME/create");
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$delete_url   = base_url("$MODULE_NAME/$TB_NAME/delete");
// ... باقي الـ URLs بنفس الأسلوب

echo AntiForgeryToken();
?>
```

### 2. PAGE-HEADER + breadcrumb
```php
<div class="page-header">
    <div>
        <h1 class="page-title"><?= $title ?></h1>
    </div>
    <div class="ms-auto pageheader-btn">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">القسم</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= $title ?></li>
        </ol>
    </div>
</div>
```

### 3. card + card-header + الأزرار
```php
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><?= $title ?></h3>
                <div class="ms-auto d-flex gap-1 flex-wrap align-items-center">
                    <?php if (HaveAccess($create_url)): ?>
                        <a class="btn btn-danger btn-sm" href="<?= $create_url ?>">
                            <i class="fa fa-plus"></i> جديد
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-body">
                <!-- الفلاتر هنا -->
            </div>
        </div>
    </div>
</div>
```

### 4. الفلاتر داخل form
```php
<form id="<?= $TB_NAME ?>_form" onsubmit="return false;">
    <div class="row">
        <!-- المقر — مع منطق branch == 1 -->
        <?php if ($this->user->branch == 1) { ?>
            <div class="form-group col-md-2">
                <label>المقر</label>
                <select name="branch_no" id="dp_branch_no" class="form-control">
                    <option value="">— الكل —</option>
                    <?php foreach ($branches as $row): ?>
                        <option value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        <?php } else { ?>
            <input type="hidden" name="branch_no" id="dp_branch_no" value="<?= $this->user->branch ?>">
        <?php } ?>

        <!-- الموظف — sel2 -->
        <div class="form-group col-md-3">
            <label>الموظف</label>
            <select name="emp_no" id="dp_emp_no" class="form-control sel2">
                <option value="">— الكل —</option>
                <?php foreach ($emp_no_cons as $row): ?>
                    <option value="<?= $row['EMP_NO'] ?>"><?= $row['EMP_NO'] . ' - ' . $row['EMP_NAME'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- الشهر -->
        <div class="form-group col-md-2">
            <label>الشهر</label>
            <input type="text" name="the_month" id="txt_the_month" class="form-control" placeholder="YYYYMM" maxlength="6">
        </div>

        <!-- باقي الفلاتر بنفس النمط -->
    </div>
    <hr>
    <div class="flex-shrink-0">
        <button type="button" onclick="javascript:search();" class="btn btn-primary">
            <i class="fa fa-search"></i> استعلام
        </button>
        <button type="button" onclick="javascript:clear_form();" class="btn btn-cyan-light">
            <i class="fa fa-eraser"></i> تفريغ الحقول
        </button>
    </div>
    <hr>
    <div id="container"></div>
</form>
```

### 5. أسماء الـ IDs موحدة (إجباري)
| الفلتر | name | id |
|--------|------|-----|
| المقر | `branch_no` | `dp_branch_no` |
| الموظف | `emp_no` | `dp_emp_no` |
| الشهر | `the_month` | `txt_the_month` |
| نوع | `xxx_type` | `dp_xxx_type` |
| الحالة | `status` | `dp_status` |
| الـ form | — | `<?= $TB_NAME ?>_form` |
| container النتائج | — | `container` |

### 6. JS — heredoc + SCRIPT (الدوال القياسية)
```php
<?php
$scripts = <<<SCRIPT
<script type="text/javascript">

    function reBind(){
        if(typeof initFunctions == 'function') initFunctions();
        initTooltips();
        if(typeof ajax_pager == 'function') ajax_pager(values_search(0));
    }

    function LoadingData(){
        ajax_pager_data('#page_tb > tbody', values_search(0));
    }

    function initTooltips(){
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (el) { return new bootstrap.Tooltip(el); });
    }

    $(function(){
        $('.sel2:not("[id^=\'s2\']")').select2();
        initTooltips();
        $('#txt_the_month').datetimepicker({format: 'YYYYMM', minViewMode: 'months', pickTime: false});
        $('#{$TB_NAME}_form').on('keydown', function(e){
            if(e.keyCode === 13){ e.preventDefault(); search(); }
        });
    });

    function values_search(add_page){
        var values = {
            page: 1,
            branch_no: $('#dp_branch_no').val() || '',
            emp_no:    $('#dp_emp_no').val()    || '',
            the_month: $('#txt_the_month').val() || ''
            // ... باقي الفلاتر
        };
        if(add_page == 0) delete values.page;
        return values;
    }

    function search(){
        var values = values_search(1);
        get_data('{$get_page_url}', values, function(data){
            $('#container').html(data);
            reBind();
        }, 'html');
    }

    function loadData(){
        var values = values_search(1);
        get_data('{$get_page_url}', values, function(data){
            $('#container').html(data);
            reBind();
        }, 'html');
    }

    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
        $('.sel2').select2('val', '');
        $('#txt_the_month').val('');
        loadData();
    }

</script>
SCRIPT;

sec_scripts($scripts);
?>
```

**⚠️ مهم:** استخدم `sec_scripts($scripts);` **وليس** `echo $scripts;`.

السبب: الـ `template1.php` يحمّل jQuery في السطر 340 ثم في السطر 381 يستدعي `put_headers('js')` اللي يطبع الـ scripts المحفوظة عن طريق `sec_scripts`. لو استخدمت `echo` بدل `sec_scripts`، الـ JS رح يُطبع في مكان الـ view (داخل `content`) قبل تحميل jQuery → خطأ `$ is not defined`.

**ملاحظة عن الـ escaping داخل heredoc:**
- `$('#id')`, `$('.cls')`, `$.ajax(...)` ← **لا تحتاج escape** لأن `(` و `.` ليست أحرف صحيحة في اسم متغير PHP، فالـ PHP تترك `$` كنص.
- `$variable_name`, `$abc` ← **تحتاج escape بـ `\$`** لأن PHP ستحاول تفسيرها كمتغير.
- `{$php_var}` ← يُفسَّر متعمداً لتمرير قيم PHP.

### 7. Controller — الدوال القياسية
```php
// index — يحمل template مع الـ view الرئيسية
function index($page = 1, $branch_no = -1, $the_month = -1, $emp_no = -1, $status = -1) {
    $data['title']     = 'العنوان';
    $data['content']   = '<tb_name>_index';
    $data['page']      = $page;
    $data['branch_no'] = $branch_no;
    // ...
    $this->_lookup($data, 'list'); // جلب الـ dropdowns
    $this->load->view('template/template1', $data);
}

// get_page — يبني الـ where ويحمل page view
function get_page($page = 1, $branch_no = -1, $the_month = -1, $emp_no = -1, $status = -1) {
    $this->load->library('pagination');

    $branch_no = $this->check_vars($branch_no, 'branch_no');
    $emp_no    = $this->check_vars($emp_no, 'emp_no');
    $the_month = $this->check_vars($the_month, 'the_month');
    $status    = $this->check_vars($status, 'status');

    $where_sql = '';
    if ($this->user->branch == 1) {
        $where_sql .= ($branch_no != null) ? " AND BRANCH_NO='{$branch_no}' " : '';
    } else {
        $where_sql .= " AND BRANCH_NO='{$this->user->branch}' ";
    }
    $where_sql .= ($emp_no != null)    ? " AND EMP_NO='{$emp_no}' "       : '';
    $where_sql .= ($the_month != null) ? " AND THE_MONTH='{$the_month}' " : '';

    // pagination config — نمط ثابت
    $config['base_url']         = base_url($this->PAGE_URL);
    $config['use_page_numbers'] = TRUE;
    $config['total_rows']       = $total_rows;
    $config['per_page']         = $this->page_size;
    $config['num_links']        = 20;
    // ... (باقي الـ tags كما في payment_req)

    $this->pagination->initialize($config);

    $offset = ($page - 1) * $config['per_page'];
    $data['page_rows']  = $this->{$this->MODEL_NAME}->get_list($where_sql, $offset, $row);
    $data['offset']     = $offset + 1;
    $data['page']       = $page;
    $data['total_rows'] = $total_rows;

    $this->load->view('<tb_name>_page', $data);
}
```

### 8. صفحة الـ page (`*_page.php`)
- جدول `<table id="page_tb" data-container="container">` — `id="page_tb"` و `data-container="container"` إجباريان للـ ajax_pager.
- Pagination في الأسفل: `<?php echo $this->pagination->create_links(); ?>`
- في النهاية: `<script>if (typeof reBind === 'function') reBind();</script>`
- صف الـ page-sector للصفحات بعد الأولى:
  ```php
  <?php if ($page > 1): ?>
      <tr><td colspan="<?= $colspan ?>" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td></tr>
  <?php endif; ?>
  ```
- الصفوف ondblclick تفتح صفحة العرض:
  ```php
  ondblclick="javascript:get_to_link('<?= base_url("$MODULE_NAME/$TB_NAME/get/$id") ?>');"
  ```

### 9. ملخّص الفروقات الشائعة (لا تعمل بهذه الطرق)
- ❌ لا تستخدم `<?= base_url(...) ?>` داخل onclick attributes — استخدم متغير في heredoc.
- ❌ لا تستخدم Bootstrap modal بـ `data-bs-toggle="modal"` للـ search — استخدم function `search()`.
- ❌ لا تستخدم infinite scroll إلا إذا كان هذا نمط الموديول كله (مثل dues_index).
- ❌ لا تستخدم `<button type="submit">` في فورم الفلاتر — `type="button"` + `onclick="search()"` لأن `onsubmit="return false;"`.
- ❌ لا تنسى `name="..."` على الـ inputs — `clearForm` يعتمد عليها.
