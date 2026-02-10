<?php
/**
 * Salary Dues - Index
 */
$MODULE_NAME = 'payroll_data';
$TB_NAME = 'dues';

$create_url   = base_url("$MODULE_NAME/$TB_NAME/create");
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$delete_url   = base_url("$MODULE_NAME/$TB_NAME/delete");

echo AntiForgeryToken();
?>

<!-- PAGE-HEADER -->
<div class="page-header">
    <div>
        <h1 class="page-title"><?= $title ?></h1>
    </div>
    <div class="ms-auto pageheader-btn">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">الرواتب</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= $title ?></li>
        </ol>
    </div>
</div>
<!-- PAGE-HEADER END -->

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title mb-0">
                    <i class="fa fa-search text-primary"></i> استعلام
                </h3>
                <div class="card-options">
                    <?php if (HaveAccess($create_url)): ?>
                        <a class="btn btn-success" href="<?= $create_url ?>" data-bs-toggle="tooltip" title="إضافة دفعة جديدة">
                            <i class="fa fa-plus"></i> جديد
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card-body">
                <form id="<?= $TB_NAME ?>_form">

                    <div class="row g-3">
                        <?php if ($this->user->branch == 1) { ?>
                            <div class="form-group col-md-2">
                                <label>
                                    <i class="fa fa-building text-info"></i> المقر
                                </label>
                                <select name="branch_no" id="dp_branch_no" class="form-control sel2">
                                    <option value="">_______</option>
                                    <?php foreach ($branches as $row) : ?>
                                        <option value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <?php } else { ?>
                            <input type="hidden" name="branch_no" id="dp_branch_no" value="<?= $this->user->branch ?>">
                        <?php } ?>

                        <div class="form-group col-md-3">
                            <label>
                                <i class="fa fa-user text-primary"></i> الموظف
                            </label>
                            <select name="emp_no" id="dp_emp_no" class="form-control sel2">
                                <option value="">_________</option>
                                <?php foreach ($emp_no_cons as $row) : ?>
                                    <option value="<?= $row['EMP_NO'] ?>"><?= $row['EMP_NO'] . ': ' . $row['EMP_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group col-md-2">
                            <label>
                                <i class="fa fa-calendar text-success"></i> الشهر
                                <i class="fa fa-question-circle text-muted" data-bs-toggle="tooltip" title="صيغة: YYYYMM (مثال: 202501)"></i>
                            </label>
                            <input type="text" 
                                   name="the_month" 
                                   id="txt_the_month" 
                                   class="form-control" 
                                   placeholder="YYYYMM"
                                   maxlength="6"
                                   pattern="[0-9]{6}">
                        </div>

                        <div class="form-group col-md-3">
                            <label>
                                <i class="fa fa-tag text-info"></i> نوع الدفع
                            </label>
                            <input type="text" name="pay_type" id="dp_pay_type"
                                   class="form-control easyui-combotree"
                                   data-options="url:'<?= $pay_type_tree_url ?>',method:'get',animate:true,lines:true"
                                   style="width:100%;">
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12 d-flex gap-2">
                            <button type="button" onclick="javascript:search();" class="btn btn-primary">
                                <i class="fa fa-search"></i> إستعلام
                            </button>
                            <button type="button" onclick="javascript:clear_form();" class="btn btn-outline-secondary">
                                <i class="fa fa-eraser"></i> مسح الحقول
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title mb-0">
                    <i class="fa fa-list text-primary"></i> النتائج
                </h3>
            </div>
            <div class="card-body" id="container">
                <div class="text-center text-muted py-5">
                    <i class="fa fa-spinner fa-spin fa-2x mb-3"></i>
                    <p>جاري التحميل...</p>
                </div>
            </div>
        </div>

    </div>
</div>

<?php
$scripts = <<<SCRIPT
<script>
    function reBind(){
        if(typeof initFunctions == 'function'){
            initFunctions();
        }
        
        // Initialize tooltips after content load
        initTooltips();
    }

    function initTooltips(){
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }

    $(function(){
        // Initialize select2
        $('.sel2').select2();
        
        // Initialize tooltips
        initTooltips();
        
        // Load initial data
        loadData();
        
        // Enter key to search
        $('#txt_the_month').on('keypress', function(e){
            if(e.which == 13){
                e.preventDefault();
                search();
            }
        });
    });

    function loadData(){
        var payTypeVal = '';
        try { payTypeVal = $('#dp_pay_type').combotree('getValue') || ''; } catch(e){}

        get_data('{$get_page_url}',{
            page: 1,
            branch_no: $('#dp_branch_no').val(),
            emp_no: $('#dp_emp_no').val(),
            the_month: $('#txt_the_month').val(),
            pay_type: payTypeVal
        }, function(data){
            $('#container').html(data);
            reBind();
        }, 'html');
    }

    function search(){
        loadData();
    }
    
    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
        $('.sel2').select2('val', '');
        $('#txt_the_month').val('');
        try { $('#dp_pay_type').combotree('clear'); } catch(e){}
        loadData();
    }

    // صار Cancel وليس Delete
    function delete_prototype(a,serial){
        if(confirm('هل متأكد من عملية إلغاء الدفعة؟')){
            get_data('{$delete_url}',{serial:serial},function(data){
                if(data == '1' || parseInt(data) > 0){
                    success_msg('رسالة','تم الإلغاء بنجاح.');
                    $(a).closest('tr').fadeOut(300, function(){
                        $(this).remove();
                    });
                    // Reload data to refresh totals
                    setTimeout(function(){
                        loadData();
                    }, 500);
                }else{
                    danger_msg('تحذير.',data);
                }
            },'html');
        }
    }
</script>
SCRIPT;

sec_scripts($scripts);
?>
