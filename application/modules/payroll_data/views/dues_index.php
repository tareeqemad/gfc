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

<style>
    #payTypeTreeModalIndex .modal-body > div {
        border-radius: 6px !important;
        background: transparent !important;
    }
    #pay_type_tree_loading_index .fa-spinner { font-size: 2rem !important; }
    #pay_type_tree_wrap_index #pay_type_tree_index,
    #pay_type_tree_wrap_index #pay_type_tree_index ul,
    #pay_type_tree_wrap_index #pay_type_tree_index li {
        list-style: none !important;
        padding-inline-start: 0;
        margin-inline-start: 0;
    }
    #pay_type_tree_wrap_index #pay_type_tree_index ul {
        padding-inline-start: 1.2rem;
    }
    #pay_type_tree_index .tree-node { padding: 6px 12px; border-radius: 6px; display: inline-block; margin: 2px 0; }
    #pay_type_tree_index .tree-node:hover { background: #e7f3ff; }
    #pay_type_tree_index .tree-icon { margin-left: 6px; }
    #pay_type_tree_index .lt-1 { border-right: 3px solid #2ecc71; }
    #pay_type_tree_index .lt-2 { border-right: 3px solid #e74c3c; }
</style>
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
                            <div class="input-group">
                                <input type="hidden" name="pay_type" id="dp_pay_type" value="">
                                <input type="text" id="dp_pay_type_display" class="form-control bg-white" readonly
                                       placeholder="الكل أو اختر من الشجرة..."
                                       value="">
                                <button type="button" class="btn btn-outline-primary" id="btn_dp_pay_type_tree" title="فتح شجرة أنواع الدفع">
                                    <i class="fa fa-sitemap"></i> اختر من الشجرة
                                </button>
                            </div>
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

<!-- مودال اختيار نوع الدفع من الشجرة (للبحث) -->
<div class="modal fade" id="payTypeTreeModalIndex" tabindex="-1" aria-labelledby="payTypeTreeModalIndexLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h5 class="modal-title" id="payTypeTreeModalIndexLabel">
                    <i class="fa fa-sitemap text-primary me-2"></i> اختر نوع الدفع للبحث
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <div class="modal-body p-3 position-relative" style="min-height: 220px;">
                <div id="pay_type_tree_loading_index" class="text-center py-4 text-muted" style="display:none;">
                    <i class="fa fa-spinner fa-spin fa-2x d-block mb-2"></i>
                    <p class="mb-0">جاري تحميل الشجرة...</p>
                </div>
                <div id="pay_type_tree_wrap_index" class="overflow-auto border rounded bg-light p-3" style="display:none; max-height: 65vh; min-height: 200px;">
                    <ul id="pay_type_tree_index" class="tree list-unstyled mb-0"></ul>
                </div>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-outline-secondary" id="btn_clear_pay_type_index"><i class="fa fa-times"></i> إزالة الفلتر</button>
            </div>
        </div>
    </div>
</div>

<?php
$pay_type_tree_url_js = isset($pay_type_tree_url) ? json_encode($pay_type_tree_url) : '""';
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
       $('.sel2:not("[id^=\'s2\']")').select2();
        
        // Initialize tooltips
        initTooltips();
        
        // Load initial data
        loadData();


         $('#txt_the_month').datetimepicker({
            format: 'YYYYMM',
            minViewMode: 'months',
            pickTime: false,
        });
    });

    var payTypeTreeUrlIndex = {$pay_type_tree_url_js};
    var payTypeTreeDataIndex = null;

    function buildPayTypeTreeHtmlIndex(nodes) {
        if (!nodes || nodes.length === 0) return '';
        var html = '';
        for (var i = 0; i < nodes.length; i++) {
            var n = nodes[i];
            var hasChildren = n.children && n.children.length > 0;
            var lineType = (n.attributes && n.attributes.lineType) ? n.attributes.lineType : 1;
            var ltClass = 'lt-' + lineType;
            if (hasChildren) {
                html += '<li class="parent_li" data-id="' + n.id + '">';
                html += '<span class="tree-node ' + ltClass + ' pay-type-parent" style="cursor:pointer;"><i class="fa fa-plus tree-icon"></i> ' + (n.text || '') + '</span>';
                html += '<ul class="list-unstyled ms-3" style="display:none;">' + buildPayTypeTreeHtmlIndex(n.children) + '</ul>';
                html += '</li>';
            } else {
                html += '<li data-id="' + n.id + '">';
                html += '<span class="tree-node ' + ltClass + ' pay-type-leaf" style="cursor:pointer;"><i class="fa tree-icon" style="display:inline-block;width:16px;"></i> ' + (n.text || '') + '</span>';
                html += '</li>';
            }
        }
        return html;
    }

    function loadPayTypeTreeIndex() {
        var loading = jQuery('#pay_type_tree_loading_index');
        var wrap = jQuery('#pay_type_tree_wrap_index');
        var tree = jQuery('#pay_type_tree_index');
        wrap.hide();
        loading.show();
        if (payTypeTreeDataIndex && payTypeTreeDataIndex.length > 0) {
            loading.hide();
            tree.html(buildPayTypeTreeHtmlIndex(payTypeTreeDataIndex));
            wrap.show();
            bindPayTypeTreeEventsIndex();
            return;
        }
        if (!payTypeTreeUrlIndex) {
            loading.hide();
            tree.html('<li class="text-muted">لا يوجد مصدر للشجرة.</li>');
            wrap.show();
            return;
        }
        tree.empty();
        jQuery.get(payTypeTreeUrlIndex).done(function(data) {
            payTypeTreeDataIndex = Array.isArray(data) ? data : [];
            loading.hide();
            tree.html(buildPayTypeTreeHtmlIndex(payTypeTreeDataIndex));
            wrap.show();
            bindPayTypeTreeEventsIndex();
        }).fail(function() {
            loading.hide();
            tree.html('<li class="text-danger">فشل تحميل الشجرة.</li>');
            wrap.show();
        });
    }

    function bindPayTypeTreeEventsIndex() {
        jQuery('#pay_type_tree_index').off('click', '.pay-type-parent').on('click', '.pay-type-parent', function(e) {
            e.stopPropagation();
            var ul = jQuery(this).closest('li').children('ul');
            var icon = jQuery(this).find('.tree-icon');
            if (ul.is(':visible')) {
                ul.slideUp(200);
                icon.removeClass('fa-minus').addClass('fa-plus');
            } else {
                ul.slideDown(200);
                icon.removeClass('fa-plus').addClass('fa-minus');
            }
        });
        jQuery('#pay_type_tree_index').off('click', '.pay-type-leaf').on('click', '.pay-type-leaf', function(e) {
            e.stopPropagation();
            var li = jQuery(this).closest('li');
            var id = li.data('id');
            var text = li.find('.tree-node').text().trim();
            if (id) {
                jQuery('#dp_pay_type').val(id);
                jQuery('#dp_pay_type_display').val(text);
                var modal = bootstrap.Modal.getInstance(document.getElementById('payTypeTreeModalIndex'));
                if (modal) modal.hide();
            }
        });
    }

    jQuery(function() {
        jQuery('#btn_dp_pay_type_tree').on('click', function(e) {
            e.preventDefault();
            loadPayTypeTreeIndex();
            var modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('payTypeTreeModalIndex'));
            modal.show();
        });
        jQuery('#btn_clear_pay_type_index').on('click', function() {
            jQuery('#dp_pay_type').val('');
            jQuery('#dp_pay_type_display').val('');
            var modal = bootstrap.Modal.getInstance(document.getElementById('payTypeTreeModalIndex'));
            if (modal) modal.hide();
        });
    });

    function loadData(){
        var payTypeVal = ($('#dp_pay_type').val() || '').trim();

        get_data('{$get_page_url}',{
            page: 1,
            branch_no: $('#dp_branch_no').val(),
            emp_no: $('#dp_emp_no').val(),
            the_month: $('#txt_the_month').val(),
            pay_type: payTypeVal || null
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
        $('#dp_pay_type').val('');
        $('#dp_pay_type_display').val('');
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
