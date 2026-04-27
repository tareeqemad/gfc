<?php
$MODULE_NAME = 'payment_req';
$TB_NAME     = 'payment_req';

$create_url        = base_url("$MODULE_NAME/$TB_NAME/create");
$get_page_url      = base_url("$MODULE_NAME/$TB_NAME/get_page");
$approve_url       = base_url("$MODULE_NAME/$TB_NAME/approve");
$pay_url           = base_url("$MODULE_NAME/$TB_NAME/do_pay");
$delete_url        = base_url("$MODULE_NAME/$TB_NAME/delete");
$approve_batch_url = base_url("$MODULE_NAME/$TB_NAME/approve_batch");
$pay_batch_url     = base_url("$MODULE_NAME/$TB_NAME/pay_batch");
$totals_url        = base_url("$MODULE_NAME/$TB_NAME/get_totals");
$import_url        = base_url("$MODULE_NAME/$TB_NAME/import_excel");
$unapprove_url     = base_url("$MODULE_NAME/$TB_NAME/unapprove");

echo AntiForgeryToken();
?>

    <!-- PAGE-HEADER -->
    <div class="page-header">
        <div>
            <h1 class="page-title"><?= $title ?></h1>
        </div>
        <div class="ms-auto pageheader-btn">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0);">الرواتب والمستحقات</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= $title ?></li>
            </ol>
        </div>
    </div>
    <!-- PAGE-HEADER END -->

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?= $title ?></h3>
                    <div class="ms-auto d-flex gap-1 flex-wrap align-items-center">
                        <?php if (HaveAccess($create_url)): ?>
                            <a class="btn btn-danger btn-sm" href="<?= $create_url ?>">
                                <i class="fa fa-plus"></i> طلب جديد
                            </a>
                            <div class="vr mx-1 d-none d-md-block"></div>
                            <a class="btn btn-success btn-sm" href="<?= base_url("$MODULE_NAME/$TB_NAME/batch") ?>">
                                <i class="fa fa-calculator"></i> احتساب الصرف
                            </a>
                            <a class="btn btn-dark btn-sm" href="<?= base_url("$MODULE_NAME/$TB_NAME/batch_history") ?>">
                                <i class="fa fa-history"></i> سجل الدفعات
                            </a>
                            <a class="btn btn-info btn-sm text-white" href="<?= base_url("$MODULE_NAME/$TB_NAME/emp_statement") ?>">
                                <i class="fa fa-id-card-o"></i> كشف حساب
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-body">
                    <form id="<?= $TB_NAME ?>_form" onsubmit="return false;">
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

                            <div class="form-group col-md-3">
                                <label>الموظف</label>
                                <select name="emp_no" id="dp_emp_no" class="form-control sel2">
                                    <option value="">— الكل —</option>
                                    <?php foreach ((isset($emp_no_cons) && is_array($emp_no_cons) ? $emp_no_cons : []) as $row): ?>
                                        <option value="<?= $row['EMP_NO'] ?>"><?= $row['EMP_NO'] . ' - ' . $row['EMP_NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group col-md-2">
                                <label>الشهر</label>
                                <input type="text" name="the_month" id="txt_the_month"
                                       class="form-control" placeholder="YYYYMM" maxlength="6">
                            </div>

                            <div class="form-group col-md-2">
                                <label>نوع الطلب</label>
                                <select name="req_type" id="dp_req_type" class="form-control">
                                    <option value="">— الكل —</option>
                                    <?php foreach ($req_type_cons as $row): ?>
                                        <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group col-md-2">
                                <label>الحالة</label>
                                <select name="status" id="dp_status" class="form-control">
                                    <option value="">— الكل —</option>
                                    <?php foreach ($status_cons as $row): ?>
                                        <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
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
                        <div id="salaryWalletSummary"></div>
                        <div id="container"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .pr-row{display:flex;gap:.5rem;margin-bottom:.5rem;flex-wrap:wrap}
        .pr-card{flex:1;min-width:120px;text-align:center;padding:.6rem .5rem;border-radius:10px;border:1px solid #e2e8f0;background:#fff}
        .pr-card .c-label{font-size:.65rem;color:#64748b;margin-bottom:.15rem}
        .pr-card .c-label i{margin-left:3px}
        .pr-card .c-val{font-size:1rem;font-weight:800;direction:ltr;display:inline-block}
        .pr-card .c-cnt{font-size:.7rem;font-weight:600;color:#94a3b8;margin-top:.1rem}
        .pr-card.c-total{background:#1e293b;border-color:#1e293b}.pr-card.c-total .c-label{color:#94a3b8}.pr-card.c-total .c-val{color:#fff;font-size:1.1rem}
        .pr-card.c-active{background:#eff6ff;border-color:#bfdbfe}.pr-card.c-active .c-val{color:#1e40af}
        .pr-card.c-cancel{background:#fef2f2;border-color:#fecaca}.pr-card.c-cancel .c-val{color:#dc2626}
        .pr-card.c-net{background:#f0fdf4;border-color:#bbf7d0}.pr-card.c-net .c-val{color:#059669;font-size:1.1rem}
    </style>



<?php
$req_get_url_js      = base_url('payment_req/payment_req/get/');
$sal_check_month_js   = base_url('payment_req/payment_req/check_month_status');
$current_ym = date('Ym');

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

        $('#txt_the_month').datetimepicker({
            format: 'YYYYMM',
            minViewMode: 'months',
            pickTime: false
        });

        $('#{$TB_NAME}_form').on('keydown', function(e){
            if(e.keyCode === 13){ e.preventDefault(); search(); }
        });

        
    });

    var _currentViewMode = 'master';

    function values_search(add_page){
        var values = {
            page: 1,
            branch_no: $('#dp_branch_no').val() || '',
            emp_no:    $('#dp_emp_no').val()     || '',
            the_month: $('#txt_the_month').val() || '',
            req_type:  $('#dp_req_type').val()   || '',
            status:    $('#dp_status').val()     || '',
            view_mode: _currentViewMode
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
        // ملخص ملخص الراتب لو فلتر نوع 2 + شهر
        var rt = $('#dp_req_type').val(), mo = $('#txt_the_month').val();
        if (rt == '2' && mo && mo.length == 6) {
            jQuery.post(salCheckMonthUrl, { month: mo }, function(resp) {
                var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
                if (!j.calculated) { $('#salaryWalletSummary').html(''); return; }
                var html = '<div class="card mb-2" style="border-radius:10px;border:1px solid #bfdbfe;background:#eff6ff"><div class="card-body py-2 px-3">';
                html += '<small class="fw-bold text-primary"><i class="fa fa-bar-chart me-1"></i> ملخص الراتب — شهر ' + mo.substring(4,6) + '/' + mo.substring(0,4) + '</small>';
                html += _buildWalletCards(j);
                html += '</div></div>';
                $('#salaryWalletSummary').html(html);
            });
        } else {
            $('#salaryWalletSummary').html('');
        }
    }

    function switchView(mode){
        _currentViewMode = mode;
        search();
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
        $('#dp_req_type').val('');
        $('#dp_status').val('');
        loadData();
    }

    var deleteReqUrl = "{$req_get_url_js}".replace('/get/','/delete_request');

    function delete_req(a, req_id){
        if(!confirm('هل تريد حذف الطلب نهائياً مع كل الموظفين؟\\n\\nلا يمكن التراجع عن هذا الإجراء.')) return;
        get_data(deleteReqUrl, {req_id: req_id}, function(resp){
            try {
                var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
                if(j.ok){
                    success_msg('تم', j.msg);
                    search();
                } else {
                    danger_msg('خطأ', j.msg);
                }
            } catch(e){ danger_msg('خطأ', resp); }
        }, 'json');
    }

    function cancel_req(a, req_id){
        var nl = String.fromCharCode(10);
        if(!confirm('إلغاء اعتماد الطلب؟' + nl + nl + 'سيرجع الطلب لحالة مسودة.' + nl + 'هل تريد المتابعة؟')) return;
        get_data('{$unapprove_url}', {req_id: req_id, note: 'إلغاء اعتماد من الشاشة الرئيسية'}, function(resp){
            var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
            if(j.ok){
                success_msg('تم', j.msg);
                loadData();
            } else {
                danger_msg('خطأ', j.msg);
            }
        }, 'json');
    }

    // URLs لاحتساب الرواتب (REQ_TYPE=2)
    var salCheckMonthUrl = "{$sal_check_month_js}";


    function _buildWalletCards(j) {
        var base = j.total_323 > 0 ? j.total_323 : j.total_net;
        var baseLabel = j.total_323 > 0 ? 'إجمالي المستحقات (الراتب المحوّل مستحقات)' : 'إجمالي المحتسب';
        var h = '<div class="pr-row mt-2">';
        h += '<div class="pr-card c-total"><div class="c-label"><i class="fa fa-money"></i> ' + baseLabel + '</div><div class="c-val">' + _nf(base) + '</div><div class="c-cnt">' + j.emp_count + ' موظف</div></div>';
        h += '<div class="pr-card" style="border-color:#fde68a;background:#fefce8"><div class="c-label"><i class="fa fa-pencil"></i> مسودة</div><div class="c-val" style="color:#92400e">' + _nf(j.total_draft) + '</div></div>';
        h += '<div class="pr-card" style="border-color:#bfdbfe;background:#dbeafe"><div class="c-label"><i class="fa fa-check"></i> معتمد</div><div class="c-val" style="color:#1e40af">' + _nf(j.total_approved) + '</div></div>';
        h += '<div class="pr-card" style="border-color:#bbf7d0;background:#f0fdf4"><div class="c-label"><i class="fa fa-check-circle"></i> منفّذ للصرف</div><div class="c-val" style="color:#059669">' + _nf(j.total_paid) + '</div></div>';
        h += '<div class="pr-card c-active"><div class="c-label"><i class="fa fa-arrow-left"></i> المتبقي</div><div class="c-val">' + _nf(j.total_available) + '</div></div>';
        if (j.req_count > 0) h += '<div class="pr-card"><div class="c-label"><i class="fa fa-file-text-o"></i> طلبات</div><div class="c-val">' + j.req_count + '</div></div>';
        h += '</div>';
        return h;
    }

    function _nf(v) {
        return parseFloat(v||0).toLocaleString('en-US', {minimumFractionDigits:2, maximumFractionDigits:2});
    }


</script>
SCRIPT;

sec_scripts($scripts);
?>
