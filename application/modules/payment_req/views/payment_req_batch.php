<?php
$MODULE_NAME = 'payment_req';
$TB_NAME     = 'payment_req';

$get_page_url     = base_url("$MODULE_NAME/$TB_NAME/get_page");
$batch_data_url    = base_url("$MODULE_NAME/$TB_NAME/batch_data");
$batch_confirm_url = base_url("$MODULE_NAME/$TB_NAME/batch_confirm_action");
$batch_pay_url     = base_url("$MODULE_NAME/$TB_NAME/batch_pay_action");
$batch_csv_url     = base_url("$MODULE_NAME/$TB_NAME/batch_export_csv");
$index_url        = base_url("$MODULE_NAME/$TB_NAME");
$pay_url          = base_url("$MODULE_NAME/$TB_NAME/do_pay");

$can_pay = HaveAccess($pay_url);

echo AntiForgeryToken();
?>

<!-- PAGE-HEADER -->
<div class="page-header">
    <div>
        <h1 class="page-title"><?= $title ?></h1>
    </div>
    <div class="ms-auto pageheader-btn">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= $index_url ?>">صرف الرواتب والمستحقات</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= $title ?></li>
        </ol>
    </div>
</div>
<!-- PAGE-HEADER END -->

<style>
.pr-row{display:flex;gap:.5rem;margin-bottom:.5rem;flex-wrap:wrap}
.pr-card{flex:1;min-width:120px;text-align:center;padding:.6rem .5rem;border-radius:10px;border:1px solid #e2e8f0;background:#fff}
.pr-card .c-label{font-size:.65rem;color:#64748b;margin-bottom:.15rem}
.pr-card .c-val{font-size:1rem;font-weight:800;direction:ltr;display:inline-block}
.pr-card.c-total{background:#1e293b;border-color:#1e293b}.pr-card.c-total .c-val,.pr-card.c-total .c-label{color:#fff}
.pr-card.c-active{background:#dbeafe;border-color:#93c5fd}
.b-status{font-weight:600;font-size:0.72rem;padding:0.25em 0.55em;border-radius:5px}
.amt{font-weight:700;color:#1e293b}
.bank-section{margin-bottom:1rem;border:1px solid #e2e8f0;border-radius:10px;overflow:hidden}
.bank-header{background:#f8fafc;padding:.6rem 1rem;cursor:pointer;display:flex;justify-content:space-between;align-items:center;font-weight:600;font-size:.85rem}
.bank-header:hover{background:#f1f5f9}
.bank-body{padding:0}
.bank-body table{margin:0;font-size:.8rem}
.bank-body table th{background:#f8fafc;position:sticky;top:0;font-size:.72rem;color:#64748b}
tr.selected-row{background:#eff6ff !important}
.chk-all-wrap{padding:.3rem .6rem;background:#f8fafc;border-bottom:1px solid #e2e8f0;font-size:.78rem}
</style>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fa fa-calculator me-2"></i><?= $title ?></h3>
                <div class="ms-auto d-flex gap-1 flex-wrap align-items-center">
                    <a class="btn btn-dark btn-sm" href="<?= base_url("$MODULE_NAME/$TB_NAME/batch_history") ?>">
                        <i class="fa fa-history"></i> سجل الدفعات
                    </a>
                    <a class="btn btn-light btn-sm" href="<?= base_url("$MODULE_NAME/$TB_NAME") ?>">
                        <i class="fa fa-arrow-right"></i> رجوع
                    </a>
                    <div class="d-flex gap-1 flex-wrap align-items-center" id="batchActions" style="display:none!important">
                        <div class="vr mx-1"></div>
                        <button class="btn btn-sm btn-primary" onclick="batchPrintReport()" id="btnPrint" style="display:none">
                            <i class="fa fa-print"></i> تقرير للطباعة
                        </button>
                        <button class="btn btn-sm btn-info text-white" onclick="batchExport()" id="btnExport" style="display:none">
                            <i class="fa fa-file-excel-o"></i> تصدير CSV
                        </button>
                        <?php if ($can_pay): ?>
                        <button class="btn btn-sm btn-indigo text-white" onclick="batchConfirm()" id="btnConfirm" style="display:none">
                            <i class="fa fa-check-circle"></i> اعتماد الاحتساب
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="batchPay()" id="btnPay" style="display:none">
                            <i class="fa fa-money"></i> تنفيذ الصرف
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="card-body">

                <!-- FILTERS -->
                <form id="batch_form" onsubmit="return false;">
                    <div class="row">
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

                        <div class="form-group col-md-2">
                            <label>الشهر</label>
                            <input type="text" class="form-control" id="txt_the_month" placeholder="YYYYMM">
                        </div>

                        <div class="form-group col-md-2">
                            <label>النوع</label>
                            <select id="dp_req_type" class="form-control">
                                <option value="">— الكل —</option>
                                <?php foreach ($req_type_cons as $row): ?>
                                    <option value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-primary" onclick="loadRequests()">
                                <i class="fa fa-search"></i> استعلام
                            </button>
                            <button type="button" class="btn btn-cyan-light" onclick="clearFilters()">
                                <i class="fa fa-eraser"></i> تفريغ الحقول
                            </button>
                        </div>
                    </div>
                    <hr>
                </form>

                <!-- REQUESTS LIST -->
                <div id="requests_section">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="fw-bold mb-0" style="font-size:.85rem"><i class="fa fa-list-alt me-1"></i> الطلبات المعتمدة</h6>
                        <div class="d-flex gap-1 align-items-center">
                            <span class="text-muted" style="font-size:.75rem" id="selectedCount"></span>
                            <button class="btn btn-sm btn-success" onclick="loadPreview()" id="btnPreview" style="display:none">
                                <i class="fa fa-calculator"></i> احتساب المحدد
                            </button>
                        </div>
                    </div>
                    <div class="chk-all-wrap" id="chkAllWrap" style="display:none">
                        <label><input type="checkbox" id="chkAll" onchange="toggleAll(this)"> تحديد الكل</label>
                    </div>
                    <div id="requests_container">
                        <div class="alert alert-light text-center text-muted py-4">
                            <i class="fa fa-hand-pointer-o fa-2x d-block mb-2" style="opacity:.4"></i>
                            اختر الفلاتر واضغط بحث لعرض الطلبات المعتمدة
                        </div>
                    </div>
                </div>

                <hr class="my-3" id="previewDivider" style="display:none">

                <!-- PREVIEW SECTION -->
                <div id="preview_section" style="display:none">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0" style="font-size:.85rem"><i class="fa fa-bank me-1"></i> معاينة الاحتساب حسب البنك</h6>
                        <div class="d-flex gap-1">
                            <button class="btn btn-info btn-sm text-white" onclick="exportBankExcel()"><i class="fa fa-file-excel-o me-1"></i> تصدير Excel</button>
                            <button class="btn btn-light btn-sm" onclick="backToRequests()"><i class="fa fa-arrow-right me-1"></i> رجوع للطلبات</button>
                        </div>
                    </div>
                    <div id="batchSummary" class="mb-2"></div>
                    <div id="batchBanks"></div>
                </div>

                <!-- LOADING -->
                <div id="batchLoading" style="display:none" class="text-center py-5">
                    <div style="display:inline-block;padding:2rem 3rem;background:#f8fafc;border-radius:16px;border:1px solid #e2e8f0">
                        <div class="mb-3"><i class="fa fa-calculator fa-3x" style="color:#2563eb;animation:pulse 1.5s infinite"></i></div>
                        <div class="fw-bold mb-1" style="font-size:1rem;color:#1e293b" id="loadingTitle">جاري احتساب الصرف...</div>
                        <div class="text-muted" style="font-size:.82rem" id="loadingDetail">تجميع بيانات الموظفين والبنوك</div>
                        <div class="mt-3">
                            <div class="progress" style="height:4px;width:200px;margin:0 auto;border-radius:4px;background:#e2e8f0">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" style="width:100%;background:#2563eb"></div>
                            </div>
                        </div>
                    </div>
                    <style>@keyframes pulse{0%,100%{opacity:1}50%{opacity:.4}}</style>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- مودال تفاصيل الموظف -->
<div class="modal fade" id="empDetailModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border:0;border-radius:14px;overflow:hidden">
            <div class="modal-header py-2" style="background:linear-gradient(135deg,#1e293b,#334155)">
                <h6 class="modal-title text-white fw-bold"><i class="fa fa-user me-2"></i> <span id="edm_title"></span></h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- بيانات الموظف -->
                <div class="row g-2 mb-3" id="edm_info"></div>
                <!-- جدول الطلبات -->
                <h6 class="fw-bold mb-2" style="font-size:.8rem"><i class="fa fa-list-alt me-1"></i> الطلبات</h6>
                <table class="table table-bordered table-sm mb-0" style="font-size:.82rem">
                    <thead class="table-light">
                        <tr><th style="width:30px">#</th><th>رقم الطلب</th><th>نوع الطلب</th><th>الشهر</th><th class="text-end">المبلغ</th></tr>
                    </thead>
                    <tbody id="edm_body"></tbody>
                    <tfoot>
                        <tr style="background:#1e293b;color:#fff;font-weight:800">
                            <td colspan="4" class="text-end">إجمالي الصرف</td>
                            <td class="text-end" id="edm_total"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ══════════ Modal: اختيار طريقة الصرف ══════════ -->
<div class="modal fade" id="disburseMethodModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border:0;border-radius:12px;overflow:hidden">
            <div class="modal-header py-2" style="background:#1e293b">
                <h6 class="modal-title text-white fw-bold">
                    <i class="fa fa-route me-1"></i> اختر طريقة الصرف لـ <span id="disburseMethodCount">0</span> سجل
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted mb-3" style="font-size:.85rem">
                    <i class="fa fa-info-circle me-1"></i>
                    الطريقة المختارة بتحدد كيف بتنوّزع المبالغ على حسابات الموظفين.
                </p>
                <div class="row g-3">
                    <!-- الطريقة القديمة -->
                    <div class="col-md-6">
                        <div class="card method-card h-100" onclick="_doConfirmWithMethod(1)" style="cursor:pointer;border:2px solid #cbd5e1;transition:all .15s">
                            <div class="card-body text-center">
                                <i class="fa fa-bank fa-3x mb-2" style="color:#3b82f6"></i>
                                <h6 class="fw-bold mb-2">الطريقة القديمة</h6>
                                <p class="text-muted mb-2" style="font-size:.78rem;line-height:1.5">
                                    صرف على بنك الموظف المسجّل في<br>
                                    <code style="font-size:.7rem">DATA.EMPLOYEES.BANK</code>
                                </p>
                                <ul class="text-start text-muted mb-0" style="font-size:.75rem;padding-right:1rem">
                                    <li>سطر واحد لكل موظف</li>
                                    <li>بدون split — كل المبلغ على الحساب الرئيسي</li>
                                    <li>متوافق مع البيانات القائمة</li>
                                </ul>
                            </div>
                            <div class="card-footer text-center bg-light py-2">
                                <button type="button" class="btn btn-primary btn-sm w-100" onclick="event.stopPropagation();_doConfirmWithMethod(1)">
                                    <i class="fa fa-check"></i> اعتماد بالطريقة القديمة
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- الطريقة الجديدة -->
                    <div class="col-md-6">
                        <div class="card method-card h-100" onclick="_doConfirmWithMethod(2)" style="cursor:pointer;border:2px solid #cbd5e1;transition:all .15s">
                            <div class="card-body text-center">
                                <i class="fa fa-sitemap fa-3x mb-2" style="color:#10b981"></i>
                                <h6 class="fw-bold mb-2">الطريقة الجديدة <small class="badge bg-success">جديد</small></h6>
                                <p class="text-muted mb-2" style="font-size:.78rem;line-height:1.5">
                                    صرف حسب التوزيع المضبوط في<br>
                                    <code style="font-size:.7rem">PAYMENT_ACCOUNTS_TB</code>
                                </p>
                                <ul class="text-start text-muted mb-0" style="font-size:.75rem;padding-right:1rem">
                                    <li>قد يولّد عدة أسطر للموظف الواحد (split)</li>
                                    <li>يحترم نسبة/مبلغ ثابت/كامل الباقي</li>
                                    <li>الموظفين بدون حسابات نشطة يُستثنوا</li>
                                </ul>
                            </div>
                            <div class="card-footer text-center bg-light py-2">
                                <button type="button" class="btn btn-success btn-sm w-100" onclick="event.stopPropagation();_doConfirmWithMethod(2)">
                                    <i class="fa fa-check"></i> اعتماد بالطريقة الجديدة
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>
<style>
    .method-card:hover{transform:translateY(-2px);box-shadow:0 6px 16px rgba(0,0,0,.1)}
    .method-card:hover[onclick*="1"]{border-color:#3b82f6!important}
    .method-card:hover[onclick*="2"]{border-color:#10b981!important}
</style>

<?php
$batch_history_url  = base_url("$MODULE_NAME/$TB_NAME/batch_history_data");
$batch_cancel_url_b = base_url("$MODULE_NAME/$TB_NAME/batch_cancel_action");
$batch_reverse_url_b = base_url("$MODULE_NAME/$TB_NAME/batch_reverse_pay_action");
$_vars_js = '<script type="text/javascript">'
    . 'var getPageUrl="' . $get_page_url . '";'
    . 'var batchDataUrl="' . $batch_data_url . '";'
    . 'var batchConfirmUrl="' . $batch_confirm_url . '";'
    . 'var batchPayUrl="' . $batch_pay_url . '";'
    . 'var batchCsvUrl="' . $batch_csv_url . '";'
    . 'var batchHistoryDataUrl="' . $batch_history_url . '";'
    . 'var batchCancelUrlB="' . $batch_cancel_url_b . '";'
    . 'var batchReverseUrlB="' . $batch_reverse_url_b . '";'
    . 'var canPay=' . ($can_pay ? 'true' : 'false') . ';'
    . 'var batchHistoryJsonUrl="' . base_url("$MODULE_NAME/$TB_NAME/batch_history_json") . '";'
    . 'var bankFileUrl="' . base_url("$MODULE_NAME/$TB_NAME/export_bank_file") . '";'
    . 'var bankListUrl="' . base_url("$MODULE_NAME/$TB_NAME/export_bank_list") . '";'
    . '</script>';

$scripts = $_vars_js . <<<'SCRIPT'
<script type="text/javascript">
var _selectedReqIds = [];
var _previewData    = [];
var _previewReqIds  = '';
var _batchId        = 0;
var _actionInProgress = false;

// ===================== INIT =====================
$(function(){
    $('#txt_the_month').datetimepicker({format:'YYYYMM', minViewMode:'months', pickTime:false});
    $('#batch_form').on('keydown', function(e){ if(e.keyCode===13){e.preventDefault();loadRequests();} });
    // منع gs-loading من حجب الصفحة
    $(document).ajaxStart(function(){ $('.gs-loading').css({'pointer-events':'none','opacity':'0'}); });
    $(document).ajaxStop(function(){ $('.gs-loading').css({'pointer-events':'none','opacity':'0'}); });
});

// ===================== LOAD REQUESTS =====================
function loadRequests(){
    var params = {
        page: 1,
        branch_no: $('#dp_branch_no').val() || '',
        the_month: $('#txt_the_month').val() || '',
        req_type:  $('#dp_req_type').val() || '',
        status:    '1,3',
        view_mode: 'master'
    };

    // Hide preview
    $('#preview_section, #previewDivider, #btnExport, #btnPay').hide();
    $('#batchActions').css('display','none');
    _selectedReqIds = [];
    updateSelectedCount();

    get_data(getPageUrl, params, function(html){
        $('#requests_container').html(html);
        injectCheckboxes();
        $('#chkAllWrap').show();
        $('#chkAll').prop('checked', false);
    }, 'html');
}

// ===================== INJECT CHECKBOXES =====================
function injectCheckboxes(){
    // Add checkbox column to header
    var $thead = $('#requests_container').find('table thead tr');
    if($thead.length) {
        $thead.prepend('<th style="width:36px;text-align:center"><i class="fa fa-check-square-o"></i></th>');
    }

    // Add checkbox to each row
    $('#requests_container').find('table tbody tr').each(function(){
        var $row = $(this);
        // Extract REQ_ID from the link in the row
        var $link = $row.find('a[href*="/get/"]');
        var reqId = '';
        if($link.length){
            var href = $link.attr('href');
            var match = href.match(/\/get\/(\d+)/);
            if(match) reqId = match[1];
        }
        if(!reqId){
            // Try from ondblclick
            var dbl = $row.attr('ondblclick') || '';
            var m2 = dbl.match(/\/get\/(\d+)/);
            if(m2) reqId = m2[1];
        }

        if(reqId){
            $row.prepend('<td style="text-align:center"><input type="checkbox" class="req-chk" value="'+reqId+'" onchange="onReqCheck()"></td>');
        } else {
            $row.prepend('<td></td>');
        }
    });

    // Hide view mode switcher and action column (not needed here)
    // إخفاء أزرار الحذف/الإلغاء + مبدّل العرض (مش مطلوبين بشاشة الاحتساب)
    $('#requests_container').find('.btn-group-sm').has('[onclick*="switchView"]').hide();
    $('#requests_container').find('[onclick*="delete_req"], [onclick*="cancel_req"]').hide();

    // Re-bind pagination to work with our filters
    $('#requests_container').find('a[data-ci-pagination-page]').off('click').on('click', function(e){
        e.preventDefault();
        var pg = $(this).attr('data-ci-pagination-page');
        var params = {
            page: pg,
            branch_no: $('#dp_branch_no').val() || '',
            the_month: $('#txt_the_month').val() || '',
            req_type:  $('#dp_req_type').val() || '',
            status:    '1',
            view_mode: 'master'
        };
        get_data(getPageUrl, params, function(html){
            $('#requests_container').html(html);
            injectCheckboxes();
            // Re-check previously selected
            $('#requests_container').find('.req-chk').each(function(){
                if(_selectedReqIds.indexOf($(this).val()) >= 0){
                    $(this).prop('checked', true);
                    $(this).closest('tr').addClass('selected-row');
                }
            });
        }, 'html');
    });
}

// ===================== CHECKBOX HANDLING =====================
function onReqCheck(){
    _selectedReqIds = [];
    $('#requests_container').find('.req-chk:checked').each(function(){
        _selectedReqIds.push($(this).val());
        $(this).closest('tr').addClass('selected-row');
    });
    $('#requests_container').find('.req-chk:not(:checked)').each(function(){
        $(this).closest('tr').removeClass('selected-row');
    });
    updateSelectedCount();
}

function toggleAll(el){
    var checked = $(el).prop('checked');
    $('#requests_container').find('.req-chk').prop('checked', checked).trigger('change');
    onReqCheck();
}

function updateSelectedCount(){
    var n = _selectedReqIds.length;
    $('#selectedCount').text(n > 0 ? n + ' طلب محدد' : '');
    if(n > 0) $('#btnPreview').show(); else $('#btnPreview').hide();
}

// ===================== LOAD PREVIEW =====================
function backToRequests(){
    $('#preview_section, #previewDivider').hide();
    $('#batchActions').css('display','none');
    $('#requests_section, #batch_form, #batchCardHeader').show();
    $('html, body').animate({scrollTop: 0}, 200);
}

function loadPreview(){
    if(_selectedReqIds.length === 0){ danger_msg('تحذير','يجب تحديد طلب واحد على الأقل'); return; }

    _previewReqIds = _selectedReqIds.join(',');
    // إخفاء الفلاتر والطلبات — عرض المعاينة فقط
    var selCount = _selectedReqIds.length;
    $('#loadingTitle').text('جاري احتساب الصرف...');
    $('#loadingDetail').text('تجميع ' + selCount + ' طلب — حساب المبالغ وبيانات البنوك');
    $('#batch_form, #requests_section, #batchCardHeader').hide();
    $('#batchLoading').show();
    $('#preview_section').hide();

    get_data(batchDataUrl, {req_ids: _previewReqIds}, function(resp){
        $('#batchLoading').hide();
        var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
        if(!j.ok){ danger_msg('خطأ', j.msg || 'فشل تحميل البيانات'); return; }
        _previewData = j.rows || [];
        if(_previewData.length === 0){ danger_msg('تحذير','لا توجد بيانات معتمدة للصرف في الطلبات المحددة — قد تكون محتسبة مسبقاً (راجع سجل الدفعات)'); return; }
        renderPreview(_previewData);
        $('#preview_section, #previewDivider, #btnExport, #btnPrint').show();
        $('#batchActions').css('display','flex');
        if(canPay) { $('#btnConfirm').show(); $('#btnPay').hide(); }
        _batchId = 0;

        // تحذير موظفين بدون بنك
        var noBankCount = 0;
        for(var i=0; i<_previewData.length; i++){
            if(parseInt(_previewData[i].NO_BANK_FLAG||0)) noBankCount++;
        }
        if(noBankCount > 0){
            danger_msg('تنبيه', 'يوجد '+noBankCount+' موظف بدون بيانات بنك — تم تمييزهم باللون الأحمر');
        }

        // تحذير طلبات فيها موظفين غير معتمدين
        var warnings = j.warnings || [];
        if(warnings.length > 0){
            var whtml = '<div class="alert alert-warning py-2 px-3 mb-2" style="font-size:.8rem">';
            whtml += '<i class="fa fa-exclamation-triangle me-1"></i> <strong>تنبيه:</strong> الطلبات التالية فيها موظفين غير معتمدين (لم يتم شملهم بالدفعة):<br>';
            for(var w=0; w<warnings.length; w++){
                var wr = warnings[w];
                whtml += '<span class="me-3">'+wr.REQ_NO+': <strong>'+wr.DRAFT_COUNT+'</strong> مسودة من أصل '+wr.TOTAL_COUNT+'</span>';
            }
            whtml += '</div>';
            $('#batchSummary').prepend(whtml);
        }
    }, 'json');
}

// ===================== RENDER PREVIEW =====================
// تجميع حسب الموظف — كل موظف صف واحد مع إجمالي + تفاصيل قابلة للتوسيع
function renderPreview(rows){
    // تجميع حسب الموظف + البنك (فرع + رئيسي) + المقر + نوع الطلب
    var emps = {}, empOrder = [], grandTotal = 0, grandCount = 0;
    var branches = {}, masterBanks = {};
    var byBranch = {};                                  // تجميع حسب مقر العمل
    var sumT1 = 0, sumT2 = 0, sumT3 = 0, sumT4 = 0;      // إجماليات أنواع الطلبات

    for(var i=0; i<rows.length; i++){
        var r = rows[i], eno = r.EMP_NO, amt = parseFloat(r.REQ_AMOUNT||0);
        var rt = parseInt(r.REQ_TYPE||0);
        var branchBk = r.BANK_NAME || '---';
        var masterBk = r.MASTER_BANK_NAME;
        if(!masterBk || masterBk === '---'){
            // fallback: أول جزء من اسم الفرع قبل " - "
            var p = branchBk.split(' - ');
            masterBk = p[0].replace(/\s*-\s*$/,'').trim();
        }

        if(!emps[eno]){
            emps[eno] = {emp_no: eno, name: r.EMP_NAME||'', branch: r.BRANCH_NAME||'', bank: branchBk, masterBank: masterBk, iban: r.IBAN||'', no_bank: parseInt(r.NO_BANK_FLAG||0), details: [], total: 0};
            empOrder.push(eno);
        }
        emps[eno].details.push(r);
        emps[eno].total += amt;

        // فروع
        if(!branches[branchBk]) branches[branchBk] = {total:0, empSet:{}, master: masterBk};
        branches[branchBk].total += amt; branches[branchBk].empSet[eno] = 1;

        // بنوك رئيسية + أعمدة الأنواع لكل بنك
        if(!masterBanks[masterBk]) masterBanks[masterBk] = {branches:{}, total:0, empSet:{}, t1:0, t2:0, t3:0, t4:0};
        if(!masterBanks[masterBk].branches[branchBk]) masterBanks[masterBk].branches[branchBk] = {total:0, empSet:{}};
        masterBanks[masterBk].branches[branchBk].total += amt;
        masterBanks[masterBk].branches[branchBk].empSet[eno] = 1;
        masterBanks[masterBk].total += amt;
        masterBanks[masterBk].empSet[eno] = 1;

        // توزيع المبلغ على نوع الطلب (للبنك والإجمالي)
        if(rt === 1)                  { masterBanks[masterBk].t1 += amt; sumT1 += amt; }
        else if(rt === 2)             { masterBanks[masterBk].t2 += amt; sumT2 += amt; }
        else if(rt === 3 || rt === 4) { masterBanks[masterBk].t3 += amt; sumT3 += amt; }
        else if(rt === 5)             { masterBanks[masterBk].t4 += amt; sumT4 += amt; }

        // تجميع حسب مقر العمل
        var empBr = r.BRANCH_NAME || 'غير محدد';
        if(!byBranch[empBr]) byBranch[empBr] = {empSet:{}, total:0};
        byBranch[empBr].empSet[eno] = 1;
        byBranch[empBr].total += amt;

        grandTotal += amt; grandCount++;
    }

    // ═══ جدول ملخص البنوك (رئيسي → فروع) ═══
    var mbKeys = Object.keys(masterBanks).sort(function(a,b){ return masterBanks[b].total - masterBanks[a].total; });
    _bankSummary = {masterBanks: masterBanks, mbKeys: mbKeys, grandTotal: grandTotal, grandCount: grandCount, empCount: empOrder.length};

    // ═══ البطاقات العلوية ═══
    var html = '<div class="pr-row mb-2">';
    html += '<div class="pr-card c-total"><div class="c-label"><i class="fa fa-users"></i> الموظفين</div><div class="c-val">'+empOrder.length+'</div><div class="c-cnt" style="color:#cbd5e1">'+grandCount+' حركة</div></div>';
    html += '<div class="pr-card"><div class="c-label"><i class="fa fa-bank"></i> البنوك</div><div class="c-val">'+mbKeys.length+'</div></div>';
    html += '<div class="pr-card"><div class="c-label"><i class="fa fa-building"></i> المقرات</div><div class="c-val">'+Object.keys(byBranch).length+'</div></div>';
    html += '<div class="pr-card" style="background:#f0fdf4;border-color:#86efac"><div class="c-label"><i class="fa fa-money"></i> إجمالي الصرف</div><div class="c-val" style="color:#059669">'+nf(grandTotal)+'</div></div>';
    html += '</div>';

    // ═══ تفصيل حسب المقر ═══
    var brKeys2 = Object.keys(byBranch).sort(function(a,b){ return byBranch[b].total - byBranch[a].total; });
    html += '<div class="row mb-3"><div class="col-md-6">';
    html += '<table class="table table-bordered table-sm mb-0" style="font-size:.8rem">';
    html += '<thead class="table-light"><tr><th><i class="fa fa-building me-1"></i> المقر</th><th class="text-center" style="width:80px">موظفين</th><th class="text-end" style="width:120px">المبلغ</th></tr></thead>';
    html += '<tbody>';
    for(var b=0; b<brKeys2.length; b++){
        var brn = brKeys2[b], brd = byBranch[brn];
        html += '<tr><td>'+brn+'</td><td class="text-center">'+Object.keys(brd.empSet).length+'</td><td class="text-end amt">'+nf(brd.total)+'</td></tr>';
    }
    html += '<tr style="background:#1e293b;color:#fff;font-weight:800"><td>الإجمالي</td><td class="text-center">'+empOrder.length+'</td><td class="text-end">'+nf(grandTotal)+'</td></tr>';
    html += '</tbody></table></div></div>';

    // ═══ ملخص البنوك مع تفصيل أنواع الطلبات ═══
    html += '<h6 class="fw-bold mb-2" style="font-size:.82rem"><i class="fa fa-bank me-1"></i> ملخص حسب البنك</h6>';
    html += '<div class="table-responsive mb-3">';
    html += '<table class="table table-bordered table-sm mb-0" style="font-size:.8rem" id="bankSummaryTable">';
    html += '<thead class="table-light"><tr><th style="width:30px">#</th><th>البنك</th><th class="text-center" style="width:60px">الفروع</th><th class="text-center" style="width:70px">الموظفين</th>';
    html += '<th class="text-end">راتب كامل</th><th class="text-end">راتب جزئي</th><th class="text-end">مستحقات</th><th class="text-end">إضافات</th>';
    html += '<th class="text-end">الإجمالي</th></tr></thead><tbody>';
    var rn = 0;
    for(var m=0; m<mbKeys.length; m++){
        var mk = mbKeys[m], md = masterBanks[mk];
        var brKeys = Object.keys(md.branches).sort(function(a,b){ return md.branches[b].total - md.branches[a].total; });
        var masterEmpCount = Object.keys(md.empSet).length;
        rn++;
        if(brKeys.length === 1){
            html += '<tr><td class="text-muted">'+rn+'</td><td>'+brKeys[0]+'</td><td class="text-center">1</td><td class="text-center">'+masterEmpCount+'</td>';
            html += '<td class="text-end">'+(md.t1>0?nf(md.t1):'-')+'</td>';
            html += '<td class="text-end">'+(md.t2>0?nf(md.t2):'-')+'</td>';
            html += '<td class="text-end">'+(md.t3>0?nf(md.t3):'-')+'</td>';
            html += '<td class="text-end">'+(md.t4>0?nf(md.t4):'-')+'</td>';
            html += '<td class="text-end amt">'+nf(md.total)+'</td></tr>';
        } else {
            html += '<tr style="background:#f1f5f9;cursor:pointer" onclick="$(\'.sub-'+m+'\').toggle();$(this).find(\'.fa-chevron-down,.fa-chevron-up\').toggleClass(\'fa-chevron-down fa-chevron-up\')">';
            html += '<td class="text-muted">'+rn+'</td><td class="fw-bold">'+mk+' <i class="fa fa-chevron-down" style="font-size:.55rem;color:#94a3b8"></i></td>';
            html += '<td class="text-center fw-bold">'+brKeys.length+'</td><td class="text-center fw-bold">'+masterEmpCount+'</td>';
            html += '<td class="text-end fw-bold">'+(md.t1>0?nf(md.t1):'-')+'</td>';
            html += '<td class="text-end fw-bold">'+(md.t2>0?nf(md.t2):'-')+'</td>';
            html += '<td class="text-end fw-bold">'+(md.t3>0?nf(md.t3):'-')+'</td>';
            html += '<td class="text-end fw-bold">'+(md.t4>0?nf(md.t4):'-')+'</td>';
            html += '<td class="text-end amt fw-bold">'+nf(md.total)+'</td></tr>';
            for(var br=0; br<brKeys.length; br++){
                var brd = md.branches[brKeys[br]];
                html += '<tr class="sub-'+m+'" style="display:none;font-size:.72rem;color:#64748b">';
                html += '<td></td><td style="padding-right:1.5rem">↳ '+brKeys[br]+'</td>';
                html += '<td></td><td class="text-center">'+Object.keys(brd.empSet).length+'</td>';
                html += '<td colspan="4"></td>';
                html += '<td class="text-end">'+nf(brd.total)+'</td></tr>';
            }
        }
    }
    html += '<tr style="background:#1e293b;color:#fff;font-weight:800"><td colspan="3">الإجمالي</td><td class="text-center">'+empOrder.length+'</td>';
    html += '<td class="text-end">'+(sumT1>0?nf(sumT1):'-')+'</td>';
    html += '<td class="text-end">'+(sumT2>0?nf(sumT2):'-')+'</td>';
    html += '<td class="text-end">'+(sumT3>0?nf(sumT3):'-')+'</td>';
    html += '<td class="text-end">'+(sumT4>0?nf(sumT4):'-')+'</td>';
    html += '<td class="text-end">'+nf(grandTotal)+'</td></tr>';
    html += '</tbody></table></div>';

    // ═══ القسم 2: تفصيل حسب النوع ═══
    var typeBreakdown = {};
    for(var i=0; i<rows.length; i++){
        var r = rows[i], rt = r.REQ_TYPE_NAME || '---', amt = parseFloat(r.REQ_AMOUNT||0);
        if(!typeBreakdown[rt]) typeBreakdown[rt] = {count:0, total:0, empSet:{}};
        typeBreakdown[rt].count++; typeBreakdown[rt].total += amt; typeBreakdown[rt].empSet[r.EMP_NO] = 1;
    }
    var tbKeys = Object.keys(typeBreakdown);
    if(tbKeys.length > 1){
        html += '<h6 class="fw-bold mb-2 mt-3" style="font-size:.82rem"><i class="fa fa-pie-chart me-1"></i> حسب النوع</h6>';
        html += '<table class="table table-bordered table-sm mb-0" style="font-size:.82rem">';
        html += '<thead class="table-light"><tr><th>النوع</th><th class="text-center">حركات</th><th class="text-center">موظفين</th><th class="text-end">المبلغ</th></tr></thead><tbody>';
        for(var t=0; t<tbKeys.length; t++){
            var tk = tbKeys[t], td = typeBreakdown[tk];
            html += '<tr><td>'+tk+'</td><td class="text-center">'+td.count+'</td><td class="text-center">'+Object.keys(td.empSet).length+'</td><td class="text-end amt">'+nf(td.total)+'</td></tr>';
        }
        html += '</tbody><tfoot><tr style="background:#1e293b;color:#fff;font-weight:800"><td>الإجمالي</td><td class="text-center">'+grandCount+'</td><td class="text-center">'+empOrder.length+'</td><td class="text-end">'+nf(grandTotal)+'</td></tr></tfoot></table>';
    }

    // ═══ القسم 3: تحليل التداخل ═══
    var multiCount = 0, singleCount = 0;
    for(var idx=0; idx<empOrder.length; idx++){
        if(emps[empOrder[idx]].details.length > 1) multiCount++; else singleCount++;
    }
    if(singleCount > 0 && multiCount > 0){
        html += '<div class="mt-3 p-2" style="background:#f0f9ff;border:1px solid #bae6fd;border-radius:8px;font-size:.78rem">';
        html += '<div class="fw-bold mb-1"><i class="fa fa-link me-1"></i> '+empOrder.length+' موظف فريد | '+multiCount+' مشترك | '+singleCount+' في طلب واحد</div>';

        var singleByType = {};
        for(var idx=0; idx<empOrder.length; idx++){
            var e = emps[empOrder[idx]];
            if(e.details.length === 1){
                var rt = e.details[0].REQ_TYPE_NAME || '---';
                if(!singleByType[rt]) singleByType[rt] = [];
                singleByType[rt].push(e);
            }
        }
        html += '<table class="table table-sm mb-0" style="font-size:.75rem;background:transparent"><tbody>';
        for(var rt in singleByType){
            var list = singleByType[rt];
            html += '<tr><td class="fw-bold" style="width:200px;border:0">'+rt+' فقط ('+list.length+')</td><td style="border:0">';
            for(var s=0; s<list.length; s++){
                html += '<span class="badge bg-warning text-dark me-1 mb-1" style="cursor:pointer;font-weight:500" ondblclick="showEmpModal(\''+list[s].emp_no+'\')">'+list[s].emp_no+' — '+list[s].name+'</span>';
            }
            html += '</td></tr>';
        }
        html += '</tbody></table></div>';
    }

    // ═══ القسم 4: شريط التحكم ═══
    html += '<div class="d-flex justify-content-between align-items-center mt-3 mb-2 p-2" style="background:#f8fafc;border-radius:8px">';
    html += '<span style="font-size:.78rem"><i class="fa fa-check-circle text-success me-1"></i> <span id="summaryDetailCount">'+grandCount+'</span> سجل معتمد — <strong id="summaryGrandTotal">'+nf(grandTotal)+'</strong></span>';
    html += '<button class="btn btn-sm btn-info text-white" onclick="exportEmpExcel()"><i class="fa fa-file-excel-o me-1"></i> تصدير Excel</button>';
    html += '</div>';
    $('#batchSummary').html(html);

    // جدول الموظفين
    var thtml = '<table class="table table-bordered table-sm mb-0" style="font-size:.82rem">';
    thtml += '<style>.chk-col{display:none}</style>';
    thtml += '<thead class="table-light"><tr>';
    thtml += '<th class="chk-col"><input type="checkbox" checked onchange="toggleAllDetails(this)"></th>';
    thtml += '<th style="width:30px">#</th><th>الموظف</th><th>المقر</th><th>البنك</th>';
    thtml += '<th class="text-center" style="width:50px">طلبات</th><th class="text-end" style="width:100px">الإجمالي</th>';
    thtml += '</tr></thead><tbody>';

    for(var idx=0; idx<empOrder.length; idx++){
        var e = emps[empOrder[idx]], hasMulti = e.details.length > 1;
        var rowId = 'emp_' + e.emp_no;

        if(!hasMulti){
            // ═══ طلب واحد — صف واحد فقط ═══
            var det = e.details[0];
            thtml += '<tr ondblclick="showEmpModal(\''+e.emp_no+'\')" style="cursor:pointer">';
            thtml += '<td class="text-center chk-col" ondblclick="event.stopPropagation()"><input type="checkbox" class="detail-chk" data-emp="'+e.emp_no+'" value="'+det.DETAIL_ID+'" data-amt="'+det.REQ_AMOUNT+'" checked onchange="onDetailCheck()"></td>';
            thtml += '<td class="text-muted">'+(idx+1)+'</td>';
            thtml += '<td><strong>'+e.emp_no+'</strong> — '+e.name+(e.no_bank?' <i class="fa fa-exclamation-triangle" style="color:#dc2626" title="بدون بنك"></i>':'')+'</td>';
            thtml += '<td style="font-size:.75rem">'+e.branch+'</td>';
            thtml += '<td style="font-size:.75rem">'+e.bank+'</td>';
            thtml += '<td class="text-center">1</td>';
            thtml += '<td class="text-end amt">'+nf(e.total)+'</td>';
            thtml += '</tr>';
        } else {
            // ═══ أكثر من طلب — صف رئيسي + تفاصيل قابلة للتوسيع ═══
            thtml += '<tr style="cursor:pointer" onclick="$(\'.\'+\''+rowId+'\').toggle();$(this).find(\'.fa-chevron-down,.fa-chevron-up\').toggleClass(\'fa-chevron-down fa-chevron-up\')" ondblclick="event.stopPropagation();showEmpModal(\''+e.emp_no+'\')">';
            thtml += '<td class="text-center chk-col" onclick="event.stopPropagation()"><input type="checkbox" class="emp-chk" data-emp="'+e.emp_no+'" checked onchange="toggleEmpDetails(this)"></td>';
            thtml += '<td class="text-muted">'+(idx+1)+'</td>';
            thtml += '<td><strong>'+e.emp_no+'</strong> — '+e.name+(e.no_bank?' <i class="fa fa-exclamation-triangle" style="color:#dc2626" title="بدون بنك"></i>':'')+' <i class="fa fa-chevron-down" style="font-size:.6rem;color:#94a3b8"></i></td>';
            thtml += '<td style="font-size:.75rem">'+e.branch+'</td>';
            thtml += '<td style="font-size:.75rem">'+e.bank+'</td>';
            thtml += '<td class="text-center"><span class="badge bg-info text-white">'+e.details.length+'</span></td>';
            thtml += '<td class="text-end amt">'+nf(e.total)+'</td>';
            thtml += '</tr>';
            for(var d=0; d<e.details.length; d++){
                var det = e.details[d];
                thtml += '<tr class="'+rowId+'" style="background:#f8fafc;font-size:.75rem;display:none">';
                thtml += '<td class="text-center chk-col"><input type="checkbox" class="detail-chk" data-emp="'+e.emp_no+'" value="'+det.DETAIL_ID+'" data-amt="'+det.REQ_AMOUNT+'" checked onchange="onDetailCheck()"></td>';
                thtml += '<td></td>';
                thtml += '<td style="padding-right:2rem"><span class="text-muted">'+(det.REQ_NO||'')+'</span></td>';
                thtml += '<td>'+(det.REQ_TYPE_NAME||'')+'</td>';
                thtml += '<td>'+(det.THE_MONTH||'')+'</td>';
                thtml += '<td></td>';
                thtml += '<td class="text-end">'+nf(parseFloat(det.REQ_AMOUNT||0))+'</td>';
                thtml += '</tr>';
            }
        }
    }
    thtml += '</tbody></table>';
    $('#batchBanks').html(thtml);
    onDetailCheck();
}

function toggleEmpDetails(el){
    var emp = $(el).data('emp'), checked = $(el).prop('checked');
    $('.detail-chk[data-emp="'+emp+'"]').prop('checked', checked);
    onDetailCheck();
}

function showEmpModal(empNo){
    var details = [];
    for(var i=0; i<_previewData.length; i++){
        if(String(_previewData[i].EMP_NO) === String(empNo)) details.push(_previewData[i]);
    }
    if(details.length === 0) return;
    var d0 = details[0], total = 0;
    $('#edm_title').text(d0.EMP_NO + ' — ' + (d0.EMP_NAME||''));

    var c = function(label, val, col, extra){
        return '<div class="'+col+'"><div style="padding:.5rem .6rem;border-radius:8px;border:1px solid #e2e8f0;background:#f8fafc;'+(extra||'')+'">'
             + '<div style="font-size:.6rem;color:#94a3b8;margin-bottom:.15rem">'+label+'</div>'
             + '<div style="font-size:.85rem;font-weight:700;color:#1e293b">'+val+'</div></div></div>';
    };
    var info = '';
    info += c('رقم الموظف', d0.EMP_NO, 'col-md-2');
    info += c('المقر', d0.BRANCH_NAME||'—', 'col-md-3');
    info += c('البنك', (d0.MASTER_BANK_NAME||d0.BANK_NAME||'—'), 'col-md-3');
    info += c('فرع البنك', d0.BANK_NAME||'—', 'col-md-4');
    info += c('IBAN', '<span style="direction:ltr;font-size:.72rem">'+(d0.IBAN||'—')+'</span>', 'col-md-5');
    info += c('الحساب', '<span style="direction:ltr;font-size:.72rem">'+(d0.BANK_ACCOUNT||d0.ACCOUNT_BANK_EMAIL||'—')+'</span>', 'col-md-3');
    info += c('عدد الطلبات', details.length, 'col-md-2');
    info += c('إجمالي الصرف', '<span id="edm_total_card" style="color:#1e40af"></span>', 'col-md-2', 'background:#eff6ff;border-color:#bfdbfe');
    $('#edm_info').html(info);

    var html = '';
    for(var i=0; i<details.length; i++){
        var r = details[i], amt = parseFloat(r.REQ_AMOUNT||0);
        total += amt;
        html += '<tr>';
        html += '<td class="text-muted">'+(i+1)+'</td>';
        html += '<td class="fw-bold">'+(r.REQ_NO||'')+'</td>';
        html += '<td>'+(r.REQ_TYPE_NAME||'')+'</td>';
        html += '<td class="text-center" style="direction:ltr">'+(r.THE_MONTH||'')+'</td>';
        html += '<td class="text-end amt">'+nf(amt)+'</td>';
        html += '</tr>';
    }
    $('#edm_body').html(html);
    $('#edm_total').text(nf(total));
    $('#edm_total_card').text(nf(total));
    $('#empDetailModal').modal('show');
}

// ===================== DETAIL CHECKBOX HANDLING =====================
function onDetailCheck(){
    var total = 0, cnt = 0;
    $('.detail-chk:checked').each(function(){ total += parseFloat($(this).data('amt')||0); cnt++; });
    var allCnt = $('.detail-chk').length;
    $('#detailSelectedCount').text(cnt + '/' + allCnt + ' محدد — ' + nf(total));
    $('#summaryGrandTotal').text(nf(total));
    $('#summaryDetailCount').text(cnt);
}

function toggleAllDetails(el){
    $('.detail-chk').prop('checked', $(el).prop('checked'));
    onDetailCheck();
}

function toggleBankDetails(el){
    $(el).closest('table').find('.detail-chk').prop('checked', $(el).prop('checked'));
    onDetailCheck();
}

function getSelectedDetailIds(){
    var ids = [];
    $('.detail-chk:checked').each(function(){ ids.push($(this).val()); });
    return ids.join(',');
}

var _bankGrouped = {};
var _grandTotals = {};

// ===================== PRINT REPORT =====================
function batchPrintReport(){
    if(!_previewData || _previewData.length===0){ danger_msg('تحذير','يجب احتساب المعاينة أولاً'); return; }

    // تجميع البيانات من _previewData
    var banks = {}, gt = {total:0,t1:0,t2:0,t3:0,t4:0,empSet:{},types:{}};
    for(var i=0; i<_previewData.length; i++){
        var row = _previewData[i];
        var masterBk = row.MASTER_BANK_NAME || row.BANK_NAME || '---';
        var branchBk = row.BANK_NAME || '---';
        var amt = parseFloat(row.REQ_AMOUNT||0);
        var rt = parseInt(row.REQ_TYPE||0);
        var rtName = row.REQ_TYPE_NAME || '---';

        if(!banks[masterBk]) banks[masterBk] = {branches:{}, total:0, t1:0, t2:0, t3:0, t4:0, empSet:{}};
        if(!banks[masterBk].branches[branchBk]) banks[masterBk].branches[branchBk] = {total:0, t1:0, t2:0, t3:0, t4:0, empSet:{}};
        banks[masterBk].branches[branchBk].total += amt;
        banks[masterBk].branches[branchBk].empSet[row.EMP_NO] = 1;
        if(rt==1) banks[masterBk].branches[branchBk].t1 += amt;
        else if(rt==2) banks[masterBk].branches[branchBk].t2 += amt;
        else if(rt==3||rt==4) banks[masterBk].branches[branchBk].t3 += amt;
        else banks[masterBk].branches[branchBk].t4 += amt;
        banks[masterBk].total += amt; banks[masterBk].empSet[row.EMP_NO] = 1;
        if(rt==1) banks[masterBk].t1+=amt; else if(rt==2) banks[masterBk].t2+=amt;
        else if(rt==3||rt==4) banks[masterBk].t3+=amt; else banks[masterBk].t4+=amt;

        gt.total += amt; gt.empSet[row.EMP_NO] = 1;
        if(rt==1) gt.t1+=amt; else if(rt==2) gt.t2+=amt; else if(rt==3||rt==4) gt.t3+=amt; else gt.t4+=amt;
        if(!gt.types[rtName]) gt.types[rtName] = {total:0, empSet:{}};
        gt.types[rtName].total += amt; gt.types[rtName].empSet[row.EMP_NO] = 1;
    }
    var gtEmpCount = Object.keys(gt.empSet).length;
    var bankKeys = Object.keys(banks).sort(function(a,b){ return banks[b].total - banks[a].total; });
    var today = new Date(); var dateStr = today.getDate()+'/'+(today.getMonth()+1)+'/'+today.getFullYear();

    var w = window.open('', '_blank');
    var h = '<!DOCTYPE html><html dir="rtl" lang="ar"><head><meta charset="utf-8">';
    h += '<title>كشف إجمالي احتساب الصرف</title>';
    h += '<style>';
    h += 'body{font-family:Arial,sans-serif;font-size:11px;margin:20px;direction:rtl}';
    h += 'h2{text-align:center;margin-bottom:5px;font-size:15px}';
    h += 'h4{text-align:center;margin:3px 0;font-size:12px;color:#555}';
    h += 'table{width:100%;border-collapse:collapse;margin-top:10px}';
    h += 'th,td{border:1px solid #333;padding:4px 6px;text-align:center;font-size:10px}';
    h += 'th{background:#d0e4f5;font-weight:bold}';
    h += '.sub-total{background:#e8f0fe;font-weight:bold}';
    h += '.grand-total{background:#b8d4f0;font-weight:bold;font-size:11px}';
    h += '.master-hdr{background:#1e293b;color:#fff;font-weight:bold;text-align:right;font-size:11px}';
    h += '.num{direction:ltr;text-align:center}';
    h += '.info-tbl{width:auto;margin:5px auto 10px;border:0} .info-tbl td{border:0;padding:2px 10px;font-size:10px;text-align:right}';
    h += '@media print{body{margin:10px}button{display:none!important}}';
    h += '</style></head><body>';
    h += '<button onclick="window.print()" style="margin-bottom:10px;padding:5px 20px;cursor:pointer">طباعة</button>';
    h += '<h2>كشف بإجمالي احتساب الصرف</h2>';
    h += '<h4>بالشيكل الإسرائيلي</h4>';

    // معلومات عامة
    h += '<table class="info-tbl"><tr><td><strong>التاريخ:</strong> '+dateStr+'</td>';
    h += '<td><strong>عدد الموظفين:</strong> '+gtEmpCount+'</td>';
    h += '<td><strong>عدد الحركات:</strong> '+_previewData.length+'</td>';
    h += '<td><strong>عدد البنوك:</strong> '+bankKeys.length+'</td>';
    h += '<td><strong>الإجمالي:</strong> '+nf(gt.total)+'</td></tr></table>';

    // جدول البنوك
    h += '<table><thead><tr>';
    h += '<th style="width:25px">م</th>';
    h += '<th>البنك</th>';
    h += '<th>موظفين</th>';
    h += '<th>راتب كامل</th>';
    h += '<th>راتب جزئي</th>';
    h += '<th>مستحقات</th>';
    h += '<th>أخرى</th>';
    h += '<th>الإجمالي</th>';
    h += '</tr></thead><tbody>';

    var rowNum = 0;
    for(var b=0; b<bankKeys.length; b++){
        var bk = bankKeys[b], bdata = banks[bk];
        var brKeys = Object.keys(bdata.branches).sort(function(a,b2){ return bdata.branches[b2].total - bdata.branches[a].total; });
        var bEmpCount = Object.keys(bdata.empSet).length;

        if(brKeys.length > 1){
            // عنوان البنك الرئيسي
            h += '<tr class="master-hdr"><td colspan="8">'+bk+' ('+brKeys.length+' فرع)</td></tr>';
            for(var br=0; br<brKeys.length; br++){
                rowNum++;
                var brName = brKeys[br], brData = bdata.branches[brName];
                h += '<tr>';
                h += '<td>'+rowNum+'</td>';
                h += '<td style="text-align:right;padding-right:15px">'+brName+'</td>';
                h += '<td>'+Object.keys(brData.empSet).length+'</td>';
                h += '<td class="num">'+nf(brData.t1)+'</td>';
                h += '<td class="num">'+nf(brData.t2)+'</td>';
                h += '<td class="num">'+nf(brData.t3)+'</td>';
                h += '<td class="num">'+nf(brData.t4)+'</td>';
                h += '<td class="num">'+nf(brData.total)+'</td>';
                h += '</tr>';
            }
            h += '<tr class="sub-total"><td colspan="2" style="text-align:right">إجمالي '+bk+'</td><td>'+bEmpCount+'</td>';
            h += '<td class="num">'+nf(bdata.t1)+'</td><td class="num">'+nf(bdata.t2)+'</td><td class="num">'+nf(bdata.t3)+'</td><td class="num">'+nf(bdata.t4)+'</td><td class="num">'+nf(bdata.total)+'</td></tr>';
        } else {
            rowNum++;
            var brData = bdata.branches[brKeys[0]];
            h += '<tr>';
            h += '<td>'+rowNum+'</td>';
            h += '<td style="text-align:right">'+brKeys[0]+'</td>';
            h += '<td>'+Object.keys(brData.empSet).length+'</td>';
            h += '<td class="num">'+nf(brData.t1)+'</td>';
            h += '<td class="num">'+nf(brData.t2)+'</td>';
            h += '<td class="num">'+nf(brData.t3)+'</td>';
            h += '<td class="num">'+nf(brData.t4)+'</td>';
            h += '<td class="num" style="font-weight:bold">'+nf(brData.total)+'</td>';
            h += '</tr>';
        }
    }

    h += '<tr class="grand-total"><td colspan="2">الإجمالي الكلي</td><td>'+gtEmpCount+'</td>';
    h += '<td class="num">'+nf(gt.t1)+'</td><td class="num">'+nf(gt.t2)+'</td><td class="num">'+nf(gt.t3)+'</td><td class="num">'+nf(gt.t4)+'</td><td class="num">'+nf(gt.total)+'</td></tr>';
    h += '</tbody></table>';

    // ملخص حسب النوع
    var typeKeys = Object.keys(gt.types);
    if(typeKeys.length > 1){
        h += '<table style="margin-top:15px"><thead><tr><th>نوع الطلب</th><th>موظفين</th><th>المبلغ</th></tr></thead><tbody>';
        for(var t=0; t<typeKeys.length; t++){
            var tk = typeKeys[t], td = gt.types[tk];
            h += '<tr><td style="text-align:right">'+tk+'</td><td>'+Object.keys(td.empSet).length+'</td><td class="num">'+nf(td.total)+'</td></tr>';
        }
        h += '<tr class="grand-total"><td style="text-align:right">الإجمالي</td><td>'+gtEmpCount+'</td><td class="num">'+nf(gt.total)+'</td></tr>';
        h += '</tbody></table>';
    }

    h += '</body></html>';
    w.document.write(h);
    w.document.close();
}

// ===================== EXPORT CSV =====================
function batchExport(){
    if(!_previewReqIds){ danger_msg('تحذير','يجب احتساب المعاينة أولاً'); return; }
    window.location.href = batchCsvUrl + '?req_ids=' + encodeURIComponent(_previewReqIds);
}

// ===================== CONFIRM (اعتماد الاحتساب) =====================
// state داخلي لمعالجة الـ confirm flow
var _pendingConfirm = null;  // {selectedIds, excludeIds, cnt}

function batchConfirm(){
    var selectedIds = getSelectedDetailIds();
    if(!selectedIds){ danger_msg('تحذير','يجب تحديد موظف واحد على الأقل'); return; }
    if(_actionInProgress){ danger_msg('تحذير','العملية قيد التنفيذ بالفعل'); return; }

    // نبعث REQ_IDS (قصير) + المستبعدين فقط (unchecked)
    var allIds = [];
    $('.detail-chk').each(function(){ allIds.push($(this).val()); });
    var selectedSet = {};
    selectedIds.split(',').forEach(function(id){ selectedSet[id] = true; });
    var excludeIds = allIds.filter(function(id){ return !selectedSet[id]; }).join(',');
    var cnt = selectedIds.split(',').length;

    _pendingConfirm = {selectedIds: selectedIds, excludeIds: excludeIds, cnt: cnt};

    // افتح modal اختيار الطريقة
    $('#disburseMethodCount').text(cnt);
    bootstrap.Modal.getOrCreateInstance(document.getElementById('disburseMethodModal')).show();
}

// يُستدعى من زر داخل الـ modal بعد اختيار الطريقة
function _doConfirmWithMethod(method){
    if(!_pendingConfirm) return;
    bootstrap.Modal.getOrCreateInstance(document.getElementById('disburseMethodModal')).hide();

    _actionInProgress = true;
    $('#btnConfirm').prop('disabled',true).html('<i class="fa fa-spinner fa-spin"></i> جاري الاعتماد...');

    var payload = {
        req_ids: _previewReqIds,
        exclude_detail_ids: _pendingConfirm.excludeIds,
        disburse_method: method
    };

    get_data(batchConfirmUrl, payload, function(resp){
        var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
        _actionInProgress = false;
        _pendingConfirm = null;
        $('#btnConfirm').prop('disabled',false).html('<i class="fa fa-check-circle"></i> اعتماد الاحتساب');
        if(j.ok){
            _batchId = j.batch_id;
            success_msg('تم الاعتماد', j.msg);
            $('#btnConfirm').hide();
            $('#btnPay').show().html('<i class="fa fa-money"></i> تنفيذ الصرف (PB-'+String(_batchId).padStart(5,'0')+')');
        } else {
            danger_msg('خطأ', j.msg || 'فشل الاعتماد');
        }
    }, 'json');
}

// ===================== PAY (تنفيذ الصرف) =====================
function batchPay(){
    if(!_batchId){ danger_msg('تحذير','يجب اعتماد الاحتساب أولاً'); return; }
    if(_actionInProgress){ danger_msg('تحذير','العملية قيد التنفيذ بالفعل'); return; }
    if(!confirm('تنفيذ الصرف للدفعة PB-'+String(_batchId).padStart(5,'0')+'؟\n\nسيتم التسديد في جدول المستحقات.\nهذه العملية لا يمكن التراجع عنها.')) return;
    _actionInProgress = true;

    $('#btnPay').prop('disabled',true).html('<i class="fa fa-spinner fa-spin"></i> جاري الصرف...');

    get_data(batchPayUrl, {batch_id: _batchId}, function(resp){
        var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
        _actionInProgress = false;
        $('#btnPay').prop('disabled',false).html('<i class="fa fa-money"></i> تنفيذ الصرف');
        if(j.ok){
            success_msg('تم الصرف', j.msg);
            _previewReqIds = ''; _previewData = []; _batchId = 0;
            backToRequests();
            loadRequests();
        } else {
            danger_msg('خطأ', j.msg || 'فشل الصرف');
        }
    }, 'json');
}

function clearFilters(){
    $('#dp_branch_no').val('');
    $('#txt_the_month').val('');
    $('#dp_req_type').val('');
    $('#requests_container').html('<div class="alert alert-light text-center text-muted py-4"><i class="fa fa-hand-pointer-o fa-2x d-block mb-2" style="opacity:.4"></i>اختر الفلاتر واضغط بحث</div>');
    $('#chkAllWrap, #preview_section, #previewDivider, #btnExport, #btnPay, #btnPrint, #btnConfirm, #btnPreview').hide();
    $('#batchActions').css('display','none');
    _selectedReqIds = [];
    _previewReqIds = '';
    _previewData = [];
    updateSelectedCount();
}

// ===================== EXPORT EXCEL (بنوك + موظفين) =====================
var _bankSummary = {};
function exportBankExcel(){
    if(!_previewData || _previewData.length === 0){ return; }
    var bs = _bankSummary;

    var rows = _previewData;

    // ورقة 1: ملخص البنوك (رئيسي + فروع)
    var s1 = [['#', 'البنك الرئيسي', 'الفرع', 'عدد الموظفين', 'المبلغ']];
    var rn = 0;
    for(var m=0; m<bs.mbKeys.length; m++){
        var mk = bs.mbKeys[m], md = bs.masterBanks[mk];
        var brKeys = Object.keys(md.branches);
        for(var br=0; br<brKeys.length; br++){
            rn++; var brd = md.branches[brKeys[br]];
            s1.push([rn, mk, brKeys[br], Object.keys(brd.empSet).length, brd.total]);
        }
        if(brKeys.length > 1) s1.push(['', mk + ' — إجمالي', '', Object.keys(md.empSet).length, md.total]);
    }
    s1.push(['', 'الإجمالي الكلي', '', bs.empCount, bs.grandTotal]);

    // ورقة 2: تفصيل حسب النوع
    var s2 = [['نوع الطلب', 'عدد الحركات', 'عدد الموظفين', 'المبلغ']];
    for(var i=0; i<rows.length; i++){
        var rt = rows[i].REQ_TYPE_NAME||''; if(!_s2map) var _s2map = {};
        if(!_s2map[rt]) _s2map[rt] = {c:0,t:0,es:{}};
        _s2map[rt].c++; _s2map[rt].t += parseFloat(rows[i].REQ_AMOUNT||0); _s2map[rt].es[rows[i].EMP_NO]=1;
    }
    for(var tk in _s2map) s2.push([tk, _s2map[tk].c, Object.keys(_s2map[tk].es).length, _s2map[tk].t]);
    s2.push(['الإجمالي النهائي', bs.grandCount, bs.empCount, bs.grandTotal]);

    // ورقة 3: تفاصيل الموظفين
    var s3 = [['#', 'رقم الموظف', 'اسم الموظف', 'المقر', 'البنك الرئيسي', 'فرع البنك', 'IBAN', 'رقم الحساب', 'رقم الطلب', 'نوع الطلب', 'الشهر', 'المبلغ']];
    for(var i=0; i<rows.length; i++){
        var r = rows[i];
        s3.push([i+1, parseInt(r.EMP_NO)||r.EMP_NO, r.EMP_NAME||'', r.BRANCH_NAME||'', r.MASTER_BANK_NAME||'', r.BANK_NAME||'', r.IBAN||'', r.BANK_ACCOUNT||'', r.REQ_NO||'', r.REQ_TYPE_NAME||'', parseInt(r.THE_MONTH)||'', parseFloat(r.REQ_AMOUNT||0)]);
    }

    var wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, XLSX.utils.aoa_to_sheet(s1), 'ملخص البنوك');
    XLSX.utils.book_append_sheet(wb, XLSX.utils.aoa_to_sheet(s2), 'تفصيل حسب النوع');
    XLSX.utils.book_append_sheet(wb, XLSX.utils.aoa_to_sheet(s3), 'تفاصيل الموظفين');
    XLSX.writeFile(wb, 'احتساب_الصرف.xlsx');
}

function exportEmpExcel(){
    if(!_previewData || _previewData.length === 0) return;
    // تجميع حسب الموظف
    var emps = {}, order = [];
    for(var i=0; i<_previewData.length; i++){
        var r = _previewData[i], e = r.EMP_NO;
        if(!emps[e]){ emps[e] = {name: r.EMP_NAME||'', branch: r.BRANCH_NAME||'', bank: r.BANK_NAME||'', iban: r.IBAN||'', total: 0, details: []}; order.push(e); }
        emps[e].total += parseFloat(r.REQ_AMOUNT||0);
        emps[e].details.push(r);
    }
    var rows = [['#', 'رقم الموظف', 'اسم الموظف', 'المقر', 'البنك الرئيسي', 'فرع البنك', 'IBAN', 'رقم الطلب', 'نوع الطلب', 'الشهر', 'المبلغ', 'إجمالي الموظف']];
    var n = 0;
    for(var i=0; i<order.length; i++){
        var emp = emps[order[i]], first = true;
        for(var d=0; d<emp.details.length; d++){
            var r = emp.details[d]; n++;
            rows.push([n, parseInt(r.EMP_NO)||r.EMP_NO, first?emp.name:'', first?emp.branch:'', first?(r.MASTER_BANK_NAME||''):'', first?emp.bank:'', first?emp.iban:'', r.REQ_NO||'', r.REQ_TYPE_NAME||'', parseInt(r.THE_MONTH)||'', parseFloat(r.REQ_AMOUNT||0), first?emp.total:'']);
            first = false;
        }
    }
    var wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, XLSX.utils.aoa_to_sheet(rows), 'الموظفين');
    XLSX.writeFile(wb, 'موظفين_احتساب_الصرف.xlsx');
}

function nf(n){ return parseFloat(n||0).toLocaleString('en-US',{minimumFractionDigits:2,maximumFractionDigits:2}); }
function nfz(n){ var v=parseFloat(n||0); return v===0 ? '—' : nf(v); }
</script>
SCRIPT;
sec_scripts($scripts);
?>
