<?php
/**
 * Payroll Data - Salary Dues (Create/Edit)
 */
$MODULE_NAME = 'payroll_data';
$TB_NAME     = 'dues';

$get_url       = base_url("$MODULE_NAME/$TB_NAME/get");
$back_url      = base_url("$MODULE_NAME/$TB_NAME");
$post_url      = $isCreate ? base_url("$MODULE_NAME/$TB_NAME/create") : base_url("$MODULE_NAME/$TB_NAME/edit");
$summary_url   = base_url("$MODULE_NAME/$TB_NAME/public_get_summary");
$import_url    = base_url("$MODULE_NAME/$TB_NAME/import_excel_smart");
$template_url  = base_url("$MODULE_NAME/$TB_NAME/download_template");

$HaveRs = isset($master_tb_data) && is_array($master_tb_data) && count($master_tb_data) > 0;
$rs     = $HaveRs ? $master_tb_data[0] : [];

echo AntiForgeryToken();
?>

    <!-- PAGE-HEADER -->
    <div class="page-header">
        <div>
            <h1 class="page-title"><?= $title ?><?= ($HaveRs ? ' - ' . ($rs['EMP_NO_NAME'] ?? '') : '') ?></h1>
        </div>
        <div class="ms-auto pageheader-btn">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0);">Payroll Data</a></li>
                <li class="breadcrumb-item"><a href="<?= $back_url ?>">تسديد مستحقات الموظفين</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= $title ?></li>
            </ol>
        </div>
    </div>
    <!-- PAGE-HEADER END -->

    <div class="row">
        <div class="col-lg-12">
            <div class="card">

                <!-- Header: زر حفظ بمكان واضح -->
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h3 class="card-title mb-0"><?= $title ?></h3>

                    <div class="card-options d-flex gap-2">
                        <button type="button" class="btn btn-success" data-bs-toggle="collapse" data-bs-target="#excelImportSection" aria-expanded="false">
                            <i class="fa fa-file-excel-o"></i> استيراد من Excel
                        </button>

                        <button type="button" id="btnSaveTop" disabled onclick="javascript:save();" class="btn btn-primary">
                            <i class="fa fa-save"></i> حفظ
                        </button>

                        <a class="btn btn-secondary" href="<?= $back_url ?>">
                            <i class="fa fa-backward"></i> رجوع
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <style>
                        /* تنسيق شجرة نوع البند داخل المودال - نفس شجرة أنواع المستحقات */
                        .dues-pay-type-tree-wrap { min-height: 200px; max-height: 70vh; overflow-y: auto; padding: 8px; background: #fafbfc; border: 1px solid #e9ecef; border-radius: 8px; }
                        .dues-pay-type-tree-wrap .tree li { margin: 2px 0; }
                        .dues-pay-type-tree-wrap .tree li > span.tree-node { padding: 7px 14px; border-radius: 6px; cursor: pointer; display: inline-block; margin: 3px 0; transition: all 0.2s ease; font-size: 14px; font-weight: 500; color: #333; background: #fff; box-shadow: 0 1px 3px rgba(0,0,0,0.06); }
                        .dues-pay-type-tree-wrap .tree li > span.tree-node.lt-1:hover { background: #eafaf1; box-shadow: 0 2px 8px rgba(39,174,96,0.15); }
                        .dues-pay-type-tree-wrap .tree li > span.tree-node.lt-2:hover { background: #fdedec; box-shadow: 0 2px 8px rgba(192,57,43,0.15); }
                        .dues-pay-type-tree-wrap .tree li > span.lt-1 { border-right: 4px solid #2ecc71; }
                        .dues-pay-type-tree-wrap .tree li > span.lt-1 .tree-icon { color: #27ae60; }
                        .dues-pay-type-tree-wrap .tree li > span.lt-2 { border-right: 4px solid #e74c3c; }
                        .dues-pay-type-tree-wrap .tree li > span.lt-2 .tree-icon { color: #c0392b; }
                        .dues-pay-type-tree-wrap .tree .tree-icon { font-size: 16px; margin-left: 5px; vertical-align: middle; cursor: pointer; }
                        #excelDropZone.excel-dropzone-disabled { background: #e9ecef !important; cursor: not-allowed !important; opacity: 0.85; pointer-events: none; }
                        #excelDropZone.excel-dropzone-enabled { pointer-events: auto; }
                    </style>
                    <!-- ===== Summary Panel (always on top) ===== -->
                    <div id="duesSummaryWrap" style="display:none;margin-bottom:10px;">
                        <div class="alert alert-light py-2" style="border:1px solid #eee; margin-bottom:0;">
                            <div class="row g-2 align-items-center">
                                <div class="col-md-2">
                                    <small class="text-muted d-block mb-1"><i class="fa fa-file-invoice text-info"></i> المستحقات الأساسية</small>
                                    <b class="text-info" id="sumTotalDue">0.00</b>
                                </div>
                                <div class="col-md-2">
                                    <small class="text-muted d-block mb-1"><i class="fa fa-plus-circle text-success"></i> إجمالي الإضافات</small>
                                    <b class="text-success" id="sumTotalAdd">0.00</b>
                                </div>
                                <div class="col-md-2">
                                    <small class="text-muted d-block mb-1"><i class="fa fa-minus-circle text-danger"></i> إجمالي الخصومات</small>
                                    <b class="text-danger" id="sumTotalDed">0.00</b>
                                </div>
                                <div class="col-md-2">
                                    <small class="text-muted d-block mb-1"><i class="fa fa-wallet text-warning"></i> المتبقي</small>
                                    <b class="text-warning" id="sumBalance">0.00</b>
                                    <div id="zeroBalanceMsg" style="display:none;margin-top:4px;">
                                        <small class="text-danger">لا يوجد رصيد متبقّي</small>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <small class="text-muted d-block mb-1">نسبة التسديد</small>
                                    <b id="paidPct">0%</b>
                                    <div class="progress mt-1" style="height:6px;">
                                        <div id="paidProgress" class="progress-bar" role="progressbar" style="width:0%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- ===== End Summary Panel ===== -->

                    <!-- ===== Excel Import Section ===== -->
                    <div class="collapse mb-3" id="excelImportSection">
                        <div class="card border-success">
                            <div class="card-header py-2 d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 text-success">
                                    <i class="fa fa-file-excel-o"></i> استيراد دفعات من ملف Excel
                                </h6>
                                <a href="<?= $template_url ?>" class="btn btn-sm btn-outline-success" target="_blank">
                                    <i class="fa fa-download"></i> تحميل القالب
                                </a>
                            </div>
                            <div class="card-body py-2">
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <div id="excelPayTypeWrap" class="rounded p-3" style="background: #f8f9fa; border: 1px solid #dee2e6;">
                                            <label class="form-label mb-2 d-block" style="font-size: 1.05rem; font-weight: 600; color: #495057;">
                                                <i class="fa fa-sitemap me-1 text-primary"></i> نوع البند المراد تسديده (لجميع صفوف الملف)
                                            </label>
                                            <div class="input-group input-group-lg">
                                                <input type="hidden" name="excel_pay_type" id="excel_pay_type" value="">
                                                <input type="text" id="excel_pay_type_display" class="form-control bg-white form-control-lg" readonly
                                                       placeholder="اختر نوع البند من الشجرة أولاً..."
                                                       value="" style="font-size: 1rem;">
                                                <button type="button" class="btn btn-primary px-4" id="btn_excel_pay_type_tree" title="فتح شجرة البنود للاستيراد" style="font-weight: 600;">
                                                    <i class="fa fa-sitemap me-1"></i> اختر من الشجرة
                                                </button>
                                            </div>
                                            <small class="d-block mt-2 text-muted">يُطبّق هذا النوع على جميع صفوف ملف Excel.</small>
                                            <small id="excelPayTypeChangeHint" class="d-none text-success mt-1"><i class="fa fa-info-circle"></i> يمكنك تغيير النوع من الشجرة في أي وقت.</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-5">
                                        <div id="excelDropZone" class="border rounded p-3 text-center excel-dropzone-disabled" style="border-style: dashed !important; border-width: 2px !important; background: #f8fff8; cursor: pointer;">
                                            <input type="file" name="excel_file" id="excel_file" accept=".xlsx,.xls,.csv" class="d-none">
                                            <div id="excelDropZoneContent">
                                                <i class="fa fa-cloud-upload fa-2x text-muted mb-1"></i>
                                                <p id="excelDropZonePrompt" class="mb-0 small text-muted">اختر <strong>نوع البند</strong> أعلاه أولاً ثم اسحب الملف هنا أو انقر للاختيار</p>
                                                <p id="excelFileName" class="mb-0 small text-success fw-bold mt-1" style="display:none;"></p>
                                            </div>
                                            <div id="excelUploadProgress" class="d-none">
                                                <div class="spinner-border spinner-border-sm text-success mb-1" role="status"></div>
                                                <p class="mb-0 small">جاري المعالجة...</p>
                                            </div>
                                        </div>
                                        <button type="button" id="btnDoImport" class="btn btn-success btn-sm w-100 mt-2 d-none">
                                            <i class="fa fa-upload"></i> بدء الاستيراد
                                        </button>
                                        <button type="button" id="btnClearFile" class="btn btn-outline-secondary btn-sm w-100 mt-1 d-none">
                                            إلغاء
                                        </button>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="small">
                                            <p class="fw-bold mb-1">صيغة الملف:</p>
                                            <table class="table table-sm table-bordered mb-2">
                                                <thead class="table-light">
                                                    <tr><th>العمود</th><th>الوصف</th><th>مثال</th></tr>
                                                </thead>
                                                <tbody>
                                                    <tr><td>A</td><td>رقم الموظف</td><td>12345</td></tr>
                                                    <tr><td>B</td><td>الشهر (YYYYMM)</td><td><?= date('Ym') ?></td></tr>
                                                    <tr><td>C</td><td>المبلغ</td><td>500.00</td></tr>
                                                    <tr><td>D</td><td>ملاحظات</td><td>-</td></tr>
                                                </tbody>
                                            </table>
                                            
                                            <div class="alert alert-info py-2 mb-2" style="font-size:11px;">
                                                <p class="fw-bold mb-1"><i class="fa fa-info-circle"></i> نوع الدفع يُحدد من الشاشة أعلاه فقط — لا يوجد عمود نوع دفع في الملف.</p>
                                            </div>
                                            
                                            <p class="text-muted mb-0 small">
                                                <i class="fa fa-info-circle"></i> السطر الأول قد يكون عنواناً. المجموع المسدد لا يتجاوز المتبقي. الحد الأقصى: 2000 سجل.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <form id="excelImportForm" class="d-none" enctype="multipart/form-data"><?= AntiForgeryToken(); ?></form>
                    <!-- ===== End Excel Import Section ===== -->

                    <form id="<?= $TB_NAME ?>_form" class="manual-input-form">

                        <?php if (!$isCreate && $HaveRs): ?>
                            <input type="hidden" name="serial" id="serial" value="<?= $rs['SERIAL'] ?>">
                        <?php endif; ?>

                        <div class="row g-3" id="manualInputFields">

                            <div class="form-group col-md-2">
                                <label>
                                    الموظف
                                    <i class="fa fa-question-circle text-muted" data-bs-toggle="tooltip" data-bs-placement="top" title="اختر الموظف من القائمة. يمكنك البحث بالاسم أو الرقم"></i>
                                </label>
                                <select name="emp_no" id="emp_no" class="form-control sel2">
                                    <option value="">_________</option>
                                    <?php foreach ($emp_no_cons as $row) : ?>
                                        <?php $sel = (!$isCreate && $HaveRs && ($rs['EMP_NO'] == $row['EMP_NO'])) ? 'selected' : ''; ?>
                                        <option <?= $sel ?> value="<?= $row['EMP_NO'] ?>"><?= $row['EMP_NO'] . ': ' . $row['EMP_NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group col-md-2">
                                <label>
                                    الشهر
                                    <i class="fa fa-question-circle text-muted" data-bs-toggle="tooltip" data-bs-placement="top" title="صيغة الشهر: YYYYMM (مثال: 202412 لشهر ديسمبر 2024)"></i>
                                </label>
                                <input type="text"
                                       name="the_month"
                                       id="the_month"
                                       class="form-control"
                                       placeholder="YYYYMM"
                                       value="<?= (!$isCreate && $HaveRs) ? ($rs['THE_MONTH'] ?? '') : date('Ym') ?>">
                            </div>

                            <div class="form-group col-md-3">
                                <label>
                                    نوع البند المراد تسديده
                                    <i class="fa fa-question-circle text-muted" data-bs-toggle="tooltip" data-bs-placement="top" title="حدد نوع البند الذي تريد تسديده من الشجرة (إضافة أو خصم). يُختار البند الفرعي فقط"></i>
                                </label>
                                <div class="input-group">
                                    <input type="hidden" name="pay_type" id="pay_type" value="<?= (!$isCreate && $HaveRs) ? ($rs['PAY_TYPE'] ?? '') : '' ?>">
                                    <input type="text" id="pay_type_display" class="form-control bg-white" readonly
                                           placeholder="اختر من الشجرة..."
                                           value="<?= (!$isCreate && $HaveRs) ? (isset($rs['PAY_TYPE_NAME']) ? $rs['PAY_TYPE_NAME'] : '') : '' ?>">
                                    <button type="button" class="btn btn-outline-primary" id="btn_pay_type_tree" title="فتح شجرة البنود">
                                        <i class="fa fa-sitemap"></i> اختر من الشجرة
                                    </button>
                                </div>
                            </div>

                            <div class="form-group col-md-2">
                                <label>
                                    المبلغ
                                    <i class="fa fa-question-circle text-muted" data-bs-toggle="tooltip" data-bs-placement="top" title="أدخل المبلغ المراد دفعه. يجب ألا يتجاوز المتبقي"></i>
                                    <span style="font-size:12px;opacity:.8;" id="balanceHintWrapper">
                                        (<i class="fa fa-wallet text-warning"></i> متبقي: <span id="balanceHint" class="fw-bold text-warning">-</span>)
                                    </span>
                                </label>

                                <div class="d-flex gap-2">
                                    <input type="number" step="0.01" name="pay" id="pay" class="form-control"
                                           placeholder="0.00"
                                           value="<?= (!$isCreate && $HaveRs) ? ($rs['PAY'] ?? '') : '' ?>">
                                    <button type="button" id="btnFillBalance" class="btn btn-outline-warning" style="white-space:nowrap; font-size:13px; padding:6px 15px; min-width:100px;" data-bs-toggle="tooltip" title="ملء المبلغ بالمتبقي تلقائياً">
                                        <i class="fa fa-fill"></i> المتبقي
                                    </button>
                                </div>

                                <div id="payWarn" style="display:none;color:#dc3545;font-size:12px;margin-top:6px;">
                                    <i class="fa fa-exclamation-triangle"></i> المبلغ أكبر من المتبقي.
                                </div>
                            </div>

                            <div class="form-group col-md-3">
                                <label>ملاحظات</label>
                                <input type="text" name="note" id="note" class="form-control"
                                       value="<?= (!$isCreate && $HaveRs) ? ($rs['NOTE'] ?? '') : '' ?>">
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12 d-flex justify-content-end gap-2">
                                <a class="btn btn-light" href="<?= $back_url ?>">
                                    <i class="fa fa-times"></i> إلغاء
                                </a>
                                <button type="button" id="btnSaveBottom" disabled onclick="javascript:save();" class="btn btn-primary">
                                    <i class="fa fa-save"></i> حفظ
                                </button>
                            </div>
                        </div>

                    </form>

                </div>
            </div>

            <!-- مودال اختيار نوع البند من الشجرة -->
            <div class="modal fade" id="payTypeTreeModal" tabindex="-1" aria-labelledby="payTypeTreeModalLabel" aria-hidden="true" data-bs-backdrop="static">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header py-2">
                            <h5 class="modal-title" id="payTypeTreeModalLabel">
                                <i class="fa fa-sitemap text-primary me-2"></i>اختر نوع البند المراد تسديده
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                        </div>
                        <div class="modal-body p-3">
                            <div id="pay_type_tree_loading" class="text-center py-4 text-muted"><i class="fa fa-spinner fa-spin fa-2x"></i><p class="mt-2 mb-0">جاري تحميل الشجرة...</p></div>
                            <div id="pay_type_tree_wrap" class="dues-pay-type-tree-wrap" style="display:none;">
                                <ul id="pay_type_tree" class="tree list-unstyled mb-0"></ul>
                            </div>
                        </div>
                        <div class="modal-footer py-2">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="importResultModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fa fa-file-excel-o"></i> نتيجة الاستيراد
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div id="importResultBody"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                            <a href="<?= $back_url ?>" class="btn btn-primary" id="btnGoToList">
                                <i class="fa fa-list"></i> الذهاب للقائمة
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
$SUMMARY_URL_JS    = json_encode($summary_url);
$POST_URL_JS       = json_encode($post_url);
$BACK_URL_JS       = json_encode($back_url);
$FORM_ID_JS        = json_encode($TB_NAME . '_form');
$IMPORT_URL_JS     = json_encode($import_url);
$TEMPLATE_URL_JS   = json_encode($template_url);
$edit_pay_type_js  = (!$isCreate && $HaveRs) ? ($rs['PAY_TYPE'] ?? '') : '';
$PAY_TYPE_TREE_URL_JS = isset($pay_type_tree_url) ? json_encode($pay_type_tree_url) : '""';

$scripts = <<<SCRIPT
<script type="text/javascript">

    $('.sel2:not("[id^=\'s2\']")').select2();

    $('#the_month').datetimepicker({
        format: 'YYYYMM',
        minViewMode: 'months',
        pickTime: false
    });
    
    var summaryUrl    = {$SUMMARY_URL_JS};
    var postUrl       = {$POST_URL_JS};
    var backUrl       = {$BACK_URL_JS};
    var formId        = {$FORM_ID_JS};
    var importUrl     = {$IMPORT_URL_JS};
    var templateUrl   = {$TEMPLATE_URL_JS};
    var payTypeTreeUrl = {$PAY_TYPE_TREE_URL_JS};

    var currentBalance = null;
    var selectedExcelFile = null;
    var lastFormData = null; // لتخزين آخر إدخال
    var selectPayTypeForExcel = false; // عند فتح الشجرة من قسم الاستيراد نملأ excel_pay_type
    
    function initTooltips(){
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
    
    function nFormat(x){
        try{
            var n = parseFloat(x);
            if (isNaN(n)) return '0.00';
            return n.toFixed(2);
        }catch(e){
            return '0.00';
        }
    }

    function setSaveEnabled(flag){
        $('#btnSaveTop, #btnSaveBottom').prop('disabled', !flag);
    }

    setSaveEnabled(false);
    
    function showSummaryLoading(){
        $('#duesSummaryWrap').show();
        $('#sumTotalDue,#sumTotalAdd,#sumTotalDed,#sumBalance,#balanceHint').text('...');
        $('#paidProgress').css('width', '0%');
        $('#paidPct').text('0%');
        $('#zeroBalanceMsg').hide();
    }

    // بناء وعرض شجرة نوع البند (بدون EasyUI)
    var payTypeTreeData = null;

    function buildPayTypeTreeHtml(nodes) {
        if (!nodes || nodes.length === 0) return '';
        var html = '';
        for (var i = 0; i < nodes.length; i++) {
            var n = nodes[i];
            var isLeaf = !n.children || n.children.length === 0;
            var hasChildren = n.children && n.children.length > 0;
            var lineType = (n.attributes && n.attributes.lineType) ? n.attributes.lineType : 1;
            var ltClass = 'lt-' + lineType;
            var textEsc = (n.text || '').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
            if (hasChildren) {
                html += '<li class="parent_li" data-id="' + n.id + '" data-text="' + textEsc + '">';
                html += '<span class="tree-node ' + ltClass + ' pay-type-parent" title="توسيع هذا الفرع">';
                html += '<i class="fa fa-plus tree-icon"></i> ' + (n.text || '') + '</span>';
                html += '<ul class="list-unstyled ms-3" style="display:none;">' + buildPayTypeTreeHtml(n.children) + '</ul>';
                html += '</li>';
            } else {
                html += '<li data-id="' + n.id + '" data-text="' + textEsc + '">';
                html += '<span class="tree-node ' + ltClass + ' pay-type-leaf"><i class="fa tree-icon" style="display:inline-block;width:16px;"></i> ' + (n.text || '') + '</span>';
                html += '</li>';
            }
        }
        return html;
    }

    function loadPayTypeTree() {
        var \$loading = $('#pay_type_tree_loading');
        var \$wrap = $('#pay_type_tree_wrap');
        var \$tree = $('#pay_type_tree');
        if (payTypeTreeData) {
            \$loading.hide();
            \$tree.html(buildPayTypeTreeHtml(payTypeTreeData));
            \$wrap.show();
            bindPayTypeTreeEvents();
            return;
        }
        if (!payTypeTreeUrl) return;
        \$loading.show();
        \$wrap.hide();
        \$tree.empty();
        $.get(payTypeTreeUrl).done(function(data) {
            payTypeTreeData = Array.isArray(data) ? data : [];
            \$loading.hide();
            \$tree.html(buildPayTypeTreeHtml(payTypeTreeData));
            \$wrap.show();
            bindPayTypeTreeEvents();
        }).fail(function() {
            \$loading.html('<span class="text-danger">فشل تحميل الشجرة</span>');
        });
    }

    function bindPayTypeTreeEvents() {
        $('#pay_type_tree').off('click', '.pay-type-parent').on('click', '.pay-type-parent', function(e) {
            e.stopPropagation();
            var \$span = $(this);
            var \$li = \$span.closest('li.parent_li');
            var \$ul = \$li.children('ul');
            var \$icon = \$span.find('.tree-icon');
            if (\$ul.is(':visible')) {
                \$ul.slideUp(200);
                \$icon.removeClass('fa-minus').addClass('fa-plus');
                \$span.attr('title', 'توسيع هذا الفرع');
            } else {
                \$ul.slideDown(200);
                \$icon.removeClass('fa-plus').addClass('fa-minus');
                \$span.attr('title', 'طي هذا الفرع');
            }
        });
        $('#pay_type_tree').off('click', '.pay-type-leaf').on('click', '.pay-type-leaf', function(e) {
            e.stopPropagation();
            var \$li = $(this).closest('li');
            var id = \$li.data('id');
            var text = \$li.find('.tree-node').text().trim();
            if (id) selectPayTypeLeaf(id, text);
        });
    }

    function selectPayTypeLeaf(id, text) {
        if (selectPayTypeForExcel) {
            $('#excel_pay_type').val(id);
            $('#excel_pay_type_display').val(text);
            selectPayTypeForExcel = false;
            updateExcelDropZoneState();
        } else {
            $('#pay_type').val(id);
            $('#pay_type_display').val(text);
            validatePay();
        }
        var modal = bootstrap.Modal.getInstance(document.getElementById('payTypeTreeModal'));
        if (modal) modal.hide();
    }

    function validatePay(){
        var empNo = $('#emp_no').val();

        // no employee selected => no save
        if(!empNo){
            $('#pay').removeClass('is-invalid');
            $('#payWarn').hide();
            setSaveEnabled(false);
            return;
        }

        var v = parseFloat($('#pay').val() || '0');
        var bal = (currentBalance === null) ? null : parseFloat(currentBalance);

        // summary not loaded yet
        if (bal === null || isNaN(bal)){
            $('#pay').removeClass('is-invalid');
            $('#payWarn').hide();
            setSaveEnabled(false);
            return;
        }

        if (v > bal){
            $('#pay').addClass('is-invalid');
            $('#payWarn').show();
            setSaveEnabled(false);
        }else{
            $('#pay').removeClass('is-invalid');
            $('#payWarn').hide();
            setSaveEnabled(parseFloat(bal) > 0);
        }
    }

    function applySummaryRow(row){
        var baseDue  = (row && row.TOTAL_BASE_DUE !== undefined) ? row.TOTAL_BASE_DUE : 0;
        var totalAdd = (row && row.TOTAL_ADD !== undefined) ? row.TOTAL_ADD : 0;
        var totalDed = (row && row.TOTAL_DED !== undefined) ? row.TOTAL_DED : 0;
        var bal      = (row && row.TOTAL_BALANCE !== undefined && row.TOTAL_BALANCE !== null)
                     ? row.TOTAL_BALANCE
                     : (parseFloat(baseDue) + parseFloat(totalAdd) - parseFloat(totalDed));

        currentBalance = bal;

        var totalEntitled = parseFloat(baseDue) + parseFloat(totalAdd);

        $('#duesSummaryWrap').show();
        $('#sumTotalDue').text(nFormat(baseDue));
        $('#sumTotalAdd').text(nFormat(totalAdd));
        $('#sumTotalDed').text(nFormat(totalDed));
        $('#sumBalance').text(nFormat(bal));
        $('#balanceHint').text(nFormat(bal));

        var pct = 0;
        if (totalEntitled > 0){
            pct = Math.min(100, Math.max(0, (parseFloat(totalDed) / totalEntitled) * 100));
        }
        $('#paidProgress').css('width', pct.toFixed(1) + '%');
        $('#paidPct').text(pct.toFixed(1) + '%');

        if (parseFloat(bal) <= 0){
            $('#zeroBalanceMsg').show();
            $('#pay').prop('disabled', true);
            setSaveEnabled(false);
        } else {
            $('#zeroBalanceMsg').hide();
            $('#pay').prop('disabled', false);
            setSaveEnabled(true);
        }

        validatePay();
    }

    function loadSummary(){
        var empNo = $('#emp_no').val();

        if(!empNo){
            $('#duesSummaryWrap').hide();
            $('#balanceHint').text('-');
            currentBalance = null;
            return;
        }

        showSummaryLoading();

        $.post(summaryUrl, { emp_no: empNo }, function(resp){
            try{
                var j = resp;
                if(!j || j.ok !== true){
                    applySummaryRow({TOTAL_BASE_DUE:0, TOTAL_ADD:0, TOTAL_DED:0, TOTAL_BALANCE:0});
                    return;
                }

                var rows = j.data || [];
                var r = null;

                if (Array.isArray(rows) && rows.length > 0){
                    r = rows[0];
                } else if (rows && typeof rows === 'object'){
                    r = rows;
                }

                applySummaryRow(r || {TOTAL_BASE_DUE:0, TOTAL_ADD:0, TOTAL_DED:0, TOTAL_BALANCE:0});

                setTimeout(function(){
                    if (!$('#pay').prop('disabled')) $('#pay').focus();
                }, 60);

            }catch(e){
                applySummaryRow({TOTAL_BASE_DUE:0, TOTAL_ADD:0, TOTAL_DED:0, TOTAL_BALANCE:0});
            }
        }, 'json').fail(function(){
            applySummaryRow({TOTAL_BASE_DUE:0, TOTAL_ADD:0, TOTAL_DED:0, TOTAL_BALANCE:0});
        });
    }

    function lockSaveButtons(lock){
        var btns = $('#btnSaveTop, #btnSaveBottom');

        if(lock){
            btns.each(function(){
                var btn = $(this);
                if(!btn.data('old-html')) btn.data('old-html', btn.html());
            });

            btns.prop('disabled', true).addClass('disabled');
            btns.html('<i class="fa fa-spinner fa-spin"></i> جاري الحفظ...');
        }else{
            btns.each(function(){
                var btn = $(this);
                var old = btn.data('old-html');
                if(old) btn.html(old);
            });

            btns.removeClass('disabled');
            validatePay();
        }
    }

    window.save = function(){
        var payTypeVal = $('#pay_type').val() || '';
        if(!payTypeVal){
            if(typeof warning_msg === 'function') warning_msg('تحذير','يجب اختيار نوع البند المراد تسديده');
            return;
        }

        validatePay();
        if ($('#btnSaveTop').prop('disabled')) return;

        lockSaveButtons(true);

        var payload = $('#' + formId).serialize();

        function handleResult(data){
            if (data == '1' || (String(data).match(/^\d+$/) && parseInt(data) > 0)) {
                // حفظ آخر إدخال للنسخ لاحقاً
                lastFormData = {
                    emp_no: $('#emp_no').val(),
                    the_month: $('#the_month').val(),
                    pay_type: $('#pay_type').val(),
                    pay: $('#pay').val(),
                    note: $('#note').val()
                };
                
                if (typeof success_msg === 'function') success_msg('رسالة', 'تم الحفظ بنجاح');
                window.location.href = backUrl;
            } else {
                lockSaveButtons(false);
                if (typeof danger_msg === 'function') danger_msg('تحذير', data);
                else alert(data);
            }
        }

        function handleFail(xhr, status, err){
            lockSaveButtons(false);
            var msg = 'فشل الاتصال بالخادم';
            if (xhr && xhr.responseText) msg = xhr.responseText;
            if (typeof danger_msg === 'function') danger_msg('تحذير', msg);
            else alert(msg);
        }

        $.ajax({
            url: postUrl,
            type: 'POST',
            data: payload,
            dataType: 'html'
        }).done(function(data){
            handleResult(data);
        }).fail(function(xhr, status, err){
            handleFail(xhr, status, err);
        });
    };


    function showImportResultModal(resp){
        var inserted = resp.inserted || 0;
        var totalRowsInFile = resp.total_rows_in_file || 0;
        var groupedCount = resp.grouped_count || 0;
        var groupedRecords = resp.grouped_records || [];
        var failed = resp.failed_employees || [];
        var parseErrors = resp.parse_errors || [];
        var insertErrors = resp.insert_errors || [];

        var html = '';
        
        // ملخص سريع
        var totalErrors = parseErrors.length + insertErrors.length + failed.length;
        var successRate = totalRowsInFile > 0 ? ((inserted / totalRowsInFile) * 100).toFixed(1) : 0;
        
        html += '<div class="row mb-3">';
        html += '<div class="col-md-4"><div class="card border-success"><div class="card-body text-center py-2">';
        html += '<h5 class="mb-0 text-success">' + inserted + '</h5><small>سجل تم إدخاله</small>';
        html += '</div></div></div>';
        html += '<div class="col-md-4"><div class="card border-info"><div class="card-body text-center py-2">';
        html += '<h5 class="mb-0 text-info">' + totalRowsInFile + '</h5><small>إجمالي السجلات</small>';
        html += '</div></div></div>';
        html += '<div class="col-md-4"><div class="card border-' + (totalErrors > 0 ? 'danger' : 'secondary') + '"><div class="card-body text-center py-2">';
        html += '<h5 class="mb-0 text-' + (totalErrors > 0 ? 'danger' : 'secondary') + '">' + totalErrors + '</h5><small>أخطاء</small>';
        html += '</div></div></div>';
        html += '</div>';

        if (inserted > 0){
            html += '<div class="alert alert-success mb-3">';
            html += '<i class="fa fa-check-circle"></i> <b>تم إدخال ' + inserted + ' سجل بنجاح</b>';
            if (totalRowsInFile > 0) {
                html += '<br><small>عدد السجلات في الملف: ' + totalRowsInFile + ' | تم تجميع ' + groupedCount + ' مجموعة | نسبة النجاح: ' + successRate + '%</small>';
            }
            html += '</div>';

            // عرض السجلات المجمعة
            if (groupedRecords.length > 0){
                html += '<div class="alert alert-info mb-3">';
                html += '<b><i class="fa fa-info-circle"></i> السجلات التالية تم تجميعها (نفس الموظف + الشهر + نوع الدفع):</b>';
                html += '<div class="table-responsive mt-2"><table class="table table-sm table-bordered">';
                html += '<thead class="table-light"><tr><th>الموظف</th><th>الشهر</th><th>نوع الدفع</th><th>عدد الصفوف</th><th>المبلغ المجمع</th><th>أرقام الصفوف</th></tr></thead><tbody>';
                groupedRecords.forEach(function(gr){
                    html += '<tr>';
                    html += '<td>' + gr.EMP_NO + '</td>';
                    html += '<td>' + gr.THE_MONTH + '</td>';
                    html += '<td>' + gr.PAY_TYPE + '</td>';
                    html += '<td><span class="badge bg-warning">' + gr.ORIGINAL_COUNT + '</span></td>';
                    html += '<td>' + parseFloat(gr.TOTAL_PAY).toFixed(2) + '</td>';
                    html += '<td><small>' + (gr.ORIGINAL_ROWS ? gr.ORIGINAL_ROWS.join(', ') : '') + '</small></td>';
                    html += '</tr>';
                });
                html += '</tbody></table></div>';
                html += '</div>';
            }
        } else if (failed.length || parseErrors.length || insertErrors.length){
            html += '<div class="alert alert-info mb-3"><i class="fa fa-info-circle"></i> لم يتم إدخال أي سجلات. راجع الأخطاء أدناه.</div>';
        }

        if (failed.length){
            html += '<div class="alert alert-warning"><i class="fa fa-exclamation-triangle"></i> الموظفون التاليون لم يُضافوا لأن <b>مجموع الملف يتجاوز المتبقي</b></div>';
            html += '<div class="table-responsive mb-3"><table class="table table-bordered table-sm">';
            html += '<thead class="table-light"><tr><th>الموظف</th><th>إجمالي الملف</th><th>المتبقي</th><th>الصفوف</th></tr></thead><tbody>';
            failed.forEach(function(x){
                var ft = (parseFloat(x.FILE_TOTAL || 0)).toFixed(2);
                var bal = (parseFloat(x.BALANCE || 0)).toFixed(2);
                var rows = (x.ROWS && x.ROWS.length) ? x.ROWS.join(', ') : '-';
                html += '<tr><td>'+x.EMP_NO+'</td><td>'+ft+'</td><td>'+bal+'</td><td>'+rows+'</td></tr>';
            });
            html += '</tbody></table></div>';
        }

        if (parseErrors.length){
            html += '<div class="alert alert-danger mb-3">';
            html += '<b><i class="fa fa-times-circle"></i> أخطاء بالملف (تم تجاهل ' + parseErrors.length + ' خطأ):</b>';
            html += '<div class="table-responsive mt-2" style="max-height:200px; overflow-y:auto;">';
            html += '<table class="table table-sm table-bordered mb-0">';
            html += '<thead class="table-light"><tr><th style="width:20%;">#</th><th>الخطأ</th></tr></thead><tbody>';
            parseErrors.forEach(function(e, idx){
                html += '<tr><td>' + (idx + 1) + '</td><td><small>' + e + '</small></td></tr>';
            });
            html += '</tbody></table></div>';
            html += '</div>';
        }

        if (insertErrors.length){
            html += '<div class="alert alert-danger mb-3">';
            html += '<b><i class="fa fa-times-circle"></i> أخطاء أثناء الإدخال (' + insertErrors.length + ' خطأ):</b>';
            html += '<div class="table-responsive mt-2" style="max-height:200px; overflow-y:auto;">';
            html += '<table class="table table-sm table-bordered mb-0">';
            html += '<thead class="table-light"><tr><th style="width:20%;">#</th><th>الموظف</th><th>الخطأ</th></tr></thead><tbody>';
            insertErrors.forEach(function(e, idx){
                html += '<tr><td>' + (idx + 1) + '</td><td>' + (e.EMP_NO||'') + '</td><td><small>' + (e.MSG||'') + '</small></td></tr>';
            });
            html += '</tbody></table></div>';
            html += '</div>';
        }

        $('#importResultBody').html(html);

        var mEl = document.getElementById('importResultModal');
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal){
            var m = bootstrap.Modal.getOrCreateInstance(mEl);
            m.show();
        } else {
            $(mEl).modal('show');
        }
    }

    function setExcelDropZoneState(loading){
        if (loading){
            $('#excelDropZoneContent').addClass('d-none');
            $('#excelUploadProgress').removeClass('d-none');
        } else {
            $('#excelDropZoneContent').removeClass('d-none');
            $('#excelUploadProgress').addClass('d-none');
        }
    }

    function doExcelImport(file){
        if (!file) return;

        var excelPayType = ($('#excel_pay_type').val() || '').trim();
        if (!excelPayType) {
            if (typeof warning_msg === 'function') {
                warning_msg('تنبيه', 'يجب اختيار نوع البند المراد تسديده من الشجرة أولاً (قسم الاستيراد من Excel)');
            } else {
                alert('يجب اختيار نوع البند المراد تسديده من الشجرة أولاً.');
            }
            return;
        }
        
        // التحقق من نوع الملف
        var fileName = file.name.toLowerCase();
        var validExtensions = ['.xlsx', '.xls', '.csv'];
        var hasValidExtension = validExtensions.some(function(ext){
            return fileName.endsWith(ext);
        });
        
        if (!hasValidExtension){
            if (typeof danger_msg === 'function') {
                danger_msg('تحذير', 'نوع الملف غير مدعوم. يجب أن يكون .xlsx أو .xls أو .csv');
            } else {
                alert('نوع الملف غير مدعوم. يجب أن يكون .xlsx أو .xls أو .csv');
            }
            return;
        }
        
        // التحقق من حجم الملف (10MB)
        var maxSize = 10 * 1024 * 1024; // 10MB
        if (file.size > maxSize){
            if (typeof danger_msg === 'function') {
                danger_msg('تحذير', 'حجم الملف كبير جداً. الحد الأقصى 10MB');
            } else {
                alert('حجم الملف كبير جداً. الحد الأقصى 10MB');
            }
            return;
        }
        
        var fd = new FormData();
        fd.append('excel_file', file);
        fd.append('excel_pay_type', excelPayType);

        var token = $('#excelImportForm').find('input[type=hidden]').first();
        if (token && token.length) fd.append(token.attr('name'), token.val());

        setExcelDropZoneState(true);
        $('#btnDoImport, #btnClearFile').addClass('d-none');

        $.ajax({
            url: importUrl,
            type: 'POST',
            data: fd,
            processData: false,
            contentType: false,
            dataType: 'json'
        }).done(function(resp){
            setExcelDropZoneState(false);
            clearExcelSelection();

            if(!resp || resp.ok !== true){
                var msg = (resp && resp.msg) ? resp.msg : 'فشل الاستيراد';
                if (typeof danger_msg === 'function') danger_msg('تحذير', msg);
                else alert(msg);
                return;
            }

            var inserted = resp.inserted || 0;
            if (inserted > 0){
                if (typeof success_msg === 'function') success_msg('رسالة', 'تم إدخال ' + inserted + ' سجل بنجاح');
                else alert('تم إدخال: ' + inserted);
            }

            if (inserted > 0 || (resp.failed_employees && resp.failed_employees.length) || (resp.parse_errors && resp.parse_errors.length) || (resp.insert_errors && resp.insert_errors.length)){
                showImportResultModal(resp);
            }

            loadSummary();
        }).fail(function(){
            setExcelDropZoneState(false);
            clearExcelSelection();
            if (typeof danger_msg === 'function') danger_msg('تحذير', 'فشل الاتصال بالخادم');
            else alert('فشل الاتصال بالخادم');
        });
    }

    function clearExcelSelection(){
        selectedExcelFile = null;
        $('#excel_file').val('');
        $('#excelFileName').hide().text('');
        $('#btnDoImport, #btnClearFile').addClass('d-none');
        $('#excelPayTypeChangeHint').addClass('d-none');
    }

    function onExcelFileSelected(file){
        if (!file || !file.name) return;
        selectedExcelFile = file;
        $('#excelFileName').text(file.name).show();
        $('#btnDoImport').removeClass('d-none');
        $('#btnClearFile').removeClass('d-none');
        $('#excelPayTypeChangeHint').removeClass('d-none');
    }

    function updateExcelDropZoneState(){
        var hasType = ($('#excel_pay_type').val() || '').trim() !== '';
        var \$zone = $('#excelDropZone');
        var \$prompt = $('#excelDropZonePrompt');
        if (hasType) {
            \$zone.removeClass('excel-dropzone-disabled').addClass('excel-dropzone-enabled');
            \$prompt.html('اسحب الملف هنا أو <span class="text-primary fw-bold">انقر للاختيار</span>');
        } else {
            \$zone.addClass('excel-dropzone-disabled').removeClass('excel-dropzone-enabled');
            \$prompt.html('اختر <strong>نوع البند</strong> أعلاه أولاً ثم اسحب الملف هنا أو انقر للاختيار');
        }
    }

    $(function(){
        if (typeof initFunctions === 'function') initFunctions();

        // Initialize tooltips
        initTooltips();

        setSaveEnabled(false);

        // شجرة نوع البند: فتح المودال (إدخال يدوي)
        $('#btn_pay_type_tree').on('click', function(e) {
            e.preventDefault();
            selectPayTypeForExcel = false;
            loadPayTypeTree();
            var modalEl = document.getElementById('payTypeTreeModal');
            var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();
        });

        // شجرة نوع البند: فتح المودال (للاستيراد من Excel)
        $('#btn_excel_pay_type_tree').on('click', function(e) {
            e.preventDefault();
            selectPayTypeForExcel = true;
            loadPayTypeTree();
            var modalEl = document.getElementById('payTypeTreeModal');
            var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();
        });

        // Excel import - Drop zone
        var dropZone = $('#excelDropZone');
        var fileInput = $('#excel_file');

        dropZone.on('click', function(){ fileInput[0].click(); });

        dropZone.on('dragover', function(e){ e.preventDefault(); e.stopPropagation(); $(this).addClass('border-primary'); });
        dropZone.on('dragleave', function(e){ e.preventDefault(); $(this).removeClass('border-primary'); });
        dropZone.on('drop', function(e){
            e.preventDefault();
            e.stopPropagation();
            $(this).removeClass('border-primary');
            var files = e.originalEvent.dataTransfer && e.originalEvent.dataTransfer.files;
            if (files && files[0]) onExcelFileSelected(files[0]);
        });

        fileInput.on('change', function(){
            if (this.files && this.files[0]) onExcelFileSelected(this.files[0]);
        });

        $('#btnDoImport').on('click', function(){
            if (selectedExcelFile) doExcelImport(selectedExcelFile);
        });

        $('#btnClearFile').on('click', function(){
            clearExcelSelection();
        });

        $('#emp_no').on('change', loadSummary);
        $('#pay').on('keyup change', validatePay);

        $('#btnFillBalance').on('click', function(){
            if (currentBalance === null) return;
            if (parseFloat(currentBalance) <= 0) return;
            $('#pay').val(nFormat(currentBalance));
            validatePay();
            $('#pay').focus();
        });

        // إخفاء/إظهار حقول الإدخال عند فتح/إغلاق قسم Excel
        $('#excelImportSection').on('show.bs.collapse', function () {
            $('#manualInputFields').slideUp(300);
            $('#btnClearForm, #btnSaveBottom').addClass('d-none');
            updateExcelDropZoneState();
        });

        $('#excelImportSection').on('hide.bs.collapse', function () {
            $('#manualInputFields').slideDown(300);
            $('#btnSaveBottom').removeClass('d-none');
        });

        updateExcelDropZoneState();

        // Keyboard shortcuts
        $(document).on('keydown', function(e){
            // Ctrl+S للحفظ
            if (e.ctrlKey && e.key === 's') {
                e.preventDefault();
                if (!$('#btnSaveTop').prop('disabled')) {
                    save();
                }
            }
            // Esc للإلغاء
            if (e.key === 'Escape' && !$('.modal').hasClass('show')) {
                window.location.href = backUrl;
            }
        });

        loadSummary();
    });

</script>
SCRIPT;

sec_scripts($scripts);
?>