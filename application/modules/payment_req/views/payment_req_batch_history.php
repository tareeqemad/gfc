<?php
$MODULE_NAME = 'payment_req';
$TB_NAME     = 'payment_req';
$index_url   = base_url("$MODULE_NAME/$TB_NAME");
$batch_url   = base_url("$MODULE_NAME/$TB_NAME/batch");
$detail_url  = base_url("$MODULE_NAME/$TB_NAME/batch_history_data");

$rows = isset($batch_rows) && is_array($batch_rows) ? $batch_rows : [];

$can_cancel  = HaveAccess(base_url("$MODULE_NAME/$TB_NAME/batch_cancel_action"));
$can_reverse = HaveAccess(base_url("$MODULE_NAME/$TB_NAME/batch_reverse_pay_action"));

echo AntiForgeryToken();
?>

<!-- PAGE-HEADER -->
<div class="page-header">
    <div><h1 class="page-title"><?= $title ?></h1></div>
    <div class="ms-auto pageheader-btn">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= $index_url ?>">صرف الرواتب والمستحقات</a></li>
            <li class="breadcrumb-item active"><?= $title ?></li>
        </ol>
    </div>
</div>

<style>
.bh-status{font-weight:600;font-size:.72rem;padding:.25em .55em;border-radius:5px}
.bh-status.s0{background:#dbeafe;color:#1e40af}
.bh-status.s2{background:#d1fae5;color:#065f46}
.bh-status.s9{background:#f1f5f9;color:#94a3b8}
.bh-tbl td,.bh-tbl th{vertical-align:middle;font-size:.82rem}
.bh-tbl tr{cursor:pointer}.bh-tbl tr:hover{background:#f1f5f9}
.amt{font-weight:700;color:#1e293b}
.pr-row{display:flex;gap:.5rem;margin-bottom:.75rem;flex-wrap:wrap}
.pr-card{flex:1;min-width:130px;text-align:center;padding:.6rem .5rem;border-radius:10px;border:1px solid #e2e8f0;background:#fff}
.pr-card .c-label{font-size:.65rem;color:#64748b;margin-bottom:.15rem}
.pr-card .c-val{font-size:1rem;font-weight:800;direction:ltr;display:inline-block}
.pr-card .c-cnt{font-size:.7rem;font-weight:600;color:#94a3b8;margin-top:.1rem}
.pr-card.c-total{background:#1e293b;border-color:#1e293b}.pr-card.c-total .c-label,.pr-card.c-total .c-val{color:#fff}
.pr-card.c-pending{background:#dbeafe;border-color:#93c5fd}.pr-card.c-pending .c-val{color:#1e40af}
.pr-card.c-paid{background:#d1fae5;border-color:#86efac}.pr-card.c-paid .c-val{color:#059669}
</style>

<?php
// حساب الملخص
$sum_total = 0; $sum_pending = 0; $sum_paid = 0; $cnt_total = 0; $cnt_pending = 0; $cnt_paid = 0;
$emp_pending = 0; $emp_paid = 0;
foreach ($rows as $r) {
    $s = (int)($r['STATUS'] ?? 0);
    $amt = (float)($r['TOTAL_AMOUNT'] ?? 0);
    $emp = (int)($r['EMP_COUNT'] ?? 0);
    if ($s == 9) continue;
    $sum_total += $amt; $cnt_total++;
    if ($s == 0) { $sum_pending += $amt; $cnt_pending++; $emp_pending += $emp; }
    if ($s == 2) { $sum_paid += $amt; $cnt_paid++; $emp_paid += $emp; }
}
$status_labels = [];
foreach ($batch_status_cons as $s) { $status_labels[(int)$s['CON_NO']] = $s['CON_NAME']; }
?>

<div class="row">
<div class="col-lg-12">
<div class="card">
    <div class="card-header d-flex align-items-center">
        <h3 class="card-title"><i class="fa fa-history me-2"></i><?= $title ?></h3>
        <div class="ms-auto d-flex gap-1">
            <a class="btn btn-success btn-sm" href="<?= $batch_url ?>"><i class="fa fa-calculator me-1"></i> احتساب الصرف</a>
            <a class="btn btn-light btn-sm" href="<?= $index_url ?>"><i class="fa fa-arrow-right me-1"></i> رجوع</a>
        </div>
    </div>
    <div class="card-body">

        <!-- فلاتر -->
        <form id="bh_filter_form" onsubmit="return false;">
            <div class="row">
                <div class="form-group col-md-2">
                    <label>الحالة</label>
                    <select id="bh_status" class="form-control">
                        <option value="">— الكل —</option>
                        <?php foreach ($batch_status_cons as $s): ?>
                            <option value="<?= $s['CON_NO'] ?>"><?= $s['CON_NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-2">
                    <label>من تاريخ</label>
                    <input type="text" id="bh_date_from" class="form-control fc-datepicker" placeholder="DD/MM/YYYY">
                </div>
                <div class="form-group col-md-2">
                    <label>إلى تاريخ</label>
                    <input type="text" id="bh_date_to" class="form-control fc-datepicker" placeholder="DD/MM/YYYY">
                </div>
                <div class="form-group col-md-2">
                    <label>رقم الدفعة</label>
                    <input type="text" id="bh_batch_no" class="form-control" placeholder="PB-00007">
                </div>
                <div class="form-group col-md-2">
                    <label>رقم الطلب</label>
                    <input type="text" id="bh_req_no" class="form-control" placeholder="PR-00014">
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-12">
                    <button type="button" class="btn btn-primary" onclick="bhFilter()">
                        <i class="fa fa-search"></i> استعلام
                    </button>
                    <button type="button" class="btn btn-cyan-light" onclick="bhClear()">
                        <i class="fa fa-eraser"></i> تفريغ الحقول
                    </button>
                </div>
            </div>
            <hr>
        </form>

        <?php if (count($rows) > 0): ?>
        <!-- ملخص -->
        <div class="pr-row">
            <div class="pr-card c-total">
                <div class="c-label"><i class="fa fa-list-alt"></i> إجمالي الدفعات</div>
                <div class="c-val"><?= n_format($sum_total) ?></div>
                <div class="c-cnt"><?= $cnt_total ?> دفعة</div>
            </div>
            <div class="pr-card c-pending">
                <div class="c-label"><i class="fa fa-clock-o"></i> <?= $status_labels[0] ?? 'محتسب' ?></div>
                <div class="c-val"><?= n_format($sum_pending) ?></div>
                <div class="c-cnt"><?= $cnt_pending ?> دفعة — <?= $emp_pending ?> موظف</div>
            </div>
            <div class="pr-card c-paid">
                <div class="c-label"><i class="fa fa-check-circle"></i> <?= $status_labels[2] ?? 'منفّذ للصرف' ?></div>
                <div class="c-val"><?= n_format($sum_paid) ?></div>
                <div class="c-cnt"><?= $cnt_paid ?> دفعة — <?= $emp_paid ?> موظف</div>
            </div>
        </div>
        <?php endif; ?>

        <?php if (count($rows) === 0): ?>
            <div class="alert alert-light text-center text-muted py-4">
                <i class="fa fa-inbox fa-2x d-block mb-2" style="opacity:.4"></i>
                لا توجد دفعات سابقة
            </div>
        <?php else: ?>
            <div class="table-responsive">
            <table class="table table-bordered table-sm bh-tbl" id="batchHistoryTable">
                <thead class="table-light">
                <tr>
                    <th style="width:30px">#</th>
                    <th>رقم الدفعة</th>
                    <th>تاريخ الإنشاء</th>
                    <th class="text-center">الموظفين</th>
                    <th class="text-center">الحركات</th>
                    <th class="text-end">المبلغ</th>
                    <th>الحالة</th>
                    <th>المنشئ</th>
                    <th>تاريخ الصرف</th>
                    <th>المنفّذ</th>
                    <th>الطلبات</th>
                    <th style="width:180px">الإجراءات</th>
                </tr>
                </thead>
                <tbody>
                <?php $n = 0; foreach ($rows as $r): $n++; $st = (int)($r['STATUS'] ?? 0); ?>
                <tr ondblclick="window.location='<?= base_url("$MODULE_NAME/$TB_NAME/batch_detail/" . $r['BATCH_ID']) ?>'">
                    <td class="text-muted"><?= $n ?></td>
                    <td class="fw-bold"><?= $r['BATCH_NO'] ?? '' ?></td>
                    <td><?= $r['ENTRY_DATE'] ?? '' ?></td>
                    <td class="text-center"><?= $r['EMP_COUNT'] ?? 0 ?></td>
                    <td class="text-center"><?= $r['MOVEMENT_COUNT'] ?? 0 ?></td>
                    <td class="text-end amt"><?= n_format((float)($r['TOTAL_AMOUNT'] ?? 0)) ?></td>
                    <td><span class="bh-status s<?= $st ?>"><?= $status_labels[$st] ?? '' ?></span></td>
                    <td style="font-size:.75rem"><?= $r['ENTRY_USER_NAME'] ?? '' ?></td>
                    <td><?= $r['PAY_DATE'] ?? '' ?></td>
                    <td style="font-size:.75rem"><?= $r['PAY_USER_NAME'] ?? '' ?></td>
                    <td style="font-size:.72rem;max-width:150px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap" title="<?= $r['REQ_IDS'] ?? '' ?>"><?= $r['REQ_IDS'] ?? '' ?></td>
                    <td class="text-center">
                        <div class="d-flex gap-1 justify-content-center flex-nowrap">
                        <a class="btn btn-sm btn-outline-success" href="<?= base_url("$MODULE_NAME/$TB_NAME/export_bank_file") ?>?batch_id=<?= $r['BATCH_ID'] ?>" onclick="event.stopPropagation()" title="تصدير ملف البنك"><i class="fa fa-file-excel-o"></i></a>
                        <a class="btn btn-sm btn-outline-primary" href="<?= base_url("$MODULE_NAME/$TB_NAME/batch_detail/" . $r['BATCH_ID']) ?>" onclick="event.stopPropagation()" title="عرض التفاصيل"><i class="fa fa-eye"></i></a>
                        <?php if ($st == 0 && $can_cancel): ?>
                            <button class="btn btn-sm btn-success" onclick="event.stopPropagation();payBatch(<?= $r['BATCH_ID'] ?>, '<?= $r['BATCH_NO'] ?>')" title="تنفيذ الصرف"><i class="fa fa-money"></i></button>
                            <button class="btn btn-sm btn-outline-danger" onclick="event.stopPropagation();cancelBatch(<?= $r['BATCH_ID'] ?>, '<?= $r['BATCH_NO'] ?>')" title="فك الاحتساب"><i class="fa fa-undo"></i></button>
                        <?php elseif ($st == 2 && $can_reverse): ?>
                            <button class="btn btn-sm btn-outline-warning" onclick="event.stopPropagation();reverseBatch(<?= $r['BATCH_ID'] ?>, '<?= $r['BATCH_NO'] ?>')" title="عكس الصرف"><i class="fa fa-reply-all"></i></button>
                        <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            </div>
        <?php endif; ?>
    </div>
</div>
</div>
</div>

<!-- مودال تفاصيل الدفعة -->
<div class="modal fade" id="batchDetailModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-xl">
        <div class="modal-content" style="border:0;border-radius:12px;overflow:hidden">
            <div class="modal-header py-2" style="background:#1e293b">
                <h6 class="modal-title text-white fw-bold"><i class="fa fa-bank me-2"></i> <span id="bdm_title"></span></h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="bdm_loading" class="text-center py-4"><i class="fa fa-spinner fa-spin fa-2x"></i></div>
                <div id="bdm_content" style="display:none">
                    <!-- جدول البنوك -->
                    <h6 class="fw-bold mb-2" style="font-size:.82rem"><i class="fa fa-bank me-1"></i> ملخص حسب البنك</h6>
                    <table class="table table-bordered table-sm mb-3" style="font-size:.82rem" id="bdm_bank_table">
                        <thead class="table-light"><tr><th>#</th><th>البنك</th><th class="text-center">موظفين</th><th class="text-end">المبلغ</th></tr></thead>
                        <tbody id="bdm_bank_body"></tbody>
                        <tfoot><tr style="background:#1e293b;color:#fff;font-weight:800"><td colspan="2">الإجمالي</td><td class="text-center" id="bdm_total_emp"></td><td class="text-end" id="bdm_total_amt"></td></tr></tfoot>
                    </table>
                    <!-- جدول الموظفين -->
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="fw-bold mb-0" style="font-size:.82rem"><i class="fa fa-users me-1"></i> الموظفين</h6>
                        <button class="btn btn-sm btn-info text-white" onclick="exportBatchHistoryExcel()"><i class="fa fa-file-excel-o me-1"></i> تصدير Excel</button>
                    </div>
                    <div class="table-responsive" style="max-height:400px;overflow-y:auto">
                    <table class="table table-bordered table-sm" style="font-size:.82rem" id="bdm_emp_table">
                        <thead class="table-light" style="position:sticky;top:0;z-index:1">
                        <tr><th style="width:30px">#</th><th>الموظف</th><th>المقر</th><th>البنك</th><th>الطلبات</th><th class="text-end">المبلغ</th></tr>
                        </thead>
                        <tbody id="bdm_emp_body"></tbody>
                    </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer py-2">
                <button class="btn btn-primary btn-sm" onclick="printBatchReport()"><i class="fa fa-print me-1"></i> طباعة التقرير</button>
                <button class="btn btn-light btn-sm" data-bs-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>

<!-- مودال تأكيد العمليات -->
<div class="modal fade" id="confirmActionModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border:0;border-radius:12px;overflow:hidden">
            <div class="modal-header py-2" id="cam_header" style="border-radius:12px 12px 0 0">
                <h6 class="modal-title text-white fw-bold"><i class="fa fa-exclamation-triangle me-2"></i> <span id="cam_title"></span></h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="cam_body"></div>
            </div>
            <div class="modal-footer py-2">
                <button class="btn btn-light btn-sm" data-bs-dismiss="modal">إلغاء</button>
                <button class="btn btn-sm" id="cam_btn" onclick="camExecute()"></button>
            </div>
        </div>
    </div>
</div>

<?php
$detail_url_js = $detail_url;
$scripts = <<<'SCRIPT'
<script type="text/javascript">

var _batchDetailData = [];
var _batchDetailNo   = '';

var _camAction = null, _camBatchId = null;

function _getRowInfo(batchId){
    var info = {no:'', emp:0, moves:0, amount:'0', status:''};
    $('#batchHistoryTable tbody tr').each(function(){
        var $tds = $(this).find('td');
        var bn = $tds.eq(1).text().trim();
        // match by batch_id from ondblclick or href
        var href = $(this).find('a[href*="batch_detail"]').attr('href') || $(this).attr('ondblclick') || '';
        if(href.indexOf('/'+batchId) > 0 || href.indexOf("'"+batchId+"'") > 0){
            info.no = bn;
            info.emp = $tds.eq(3).text().trim();
            info.moves = $tds.eq(4).text().trim();
            info.amount = $tds.eq(5).text().trim();
            info.status = $tds.eq(6).text().trim();
        }
    });
    return info;
}

function showConfirmModal(action, batchId){
    _camAction = action; _camBatchId = batchId;
    var info = _getRowInfo(batchId);
    var h = '';

    if(action === 'pay'){
        $('#cam_header').css('background','#059669');
        $('#cam_title').text('تنفيذ صرف الدفعة ' + info.no);
        $('#cam_btn').attr('class','btn btn-sm btn-success').html('<i class="fa fa-money me-1"></i> تأكيد التنفيذ');
        h = '<table class="table table-sm mb-2" style="font-size:.85rem">';
        h += '<tr><td class="text-muted">رقم الدفعة</td><td class="fw-bold">'+info.no+'</td></tr>';
        h += '<tr><td class="text-muted">عدد الموظفين</td><td class="fw-bold">'+info.emp+'</td></tr>';
        h += '<tr><td class="text-muted">عدد الحركات</td><td class="fw-bold">'+info.moves+'</td></tr>';
        h += '<tr><td class="text-muted">إجمالي المبلغ</td><td class="fw-bold" style="color:#059669">'+info.amount+'</td></tr>';
        h += '</table>';
        h += '<div class="alert alert-success py-2 mb-0" style="font-size:.82rem"><i class="fa fa-info-circle me-1"></i> سيتم ترحيل المبالغ لجدول المستحقات.</div>';
    }
    else if(action === 'reverse'){
        $('#cam_header').css('background','#dc2626');
        $('#cam_title').text('عكس صرف الدفعة ' + info.no);
        $('#cam_btn').attr('class','btn btn-sm btn-danger').html('<i class="fa fa-reply-all me-1"></i> تأكيد عكس الصرف');
        h = '<table class="table table-sm mb-2" style="font-size:.85rem">';
        h += '<tr><td class="text-muted">رقم الدفعة</td><td class="fw-bold">'+info.no+'</td></tr>';
        h += '<tr><td class="text-muted">عدد الموظفين</td><td class="fw-bold">'+info.emp+'</td></tr>';
        h += '<tr><td class="text-muted">عدد الحركات</td><td class="fw-bold">'+info.moves+'</td></tr>';
        h += '<tr><td class="text-muted">إجمالي المبلغ</td><td class="fw-bold" style="color:#dc2626">'+info.amount+'</td></tr>';
        h += '</table>';
        h += '<div class="alert alert-danger py-2 mb-0" style="font-size:.82rem">';
        h += '<i class="fa fa-exclamation-triangle me-1"></i> سيتم:<br>';
        h += '- حذف سجلات المستحقات<br>';
        h += '- إرجاع الموظفين لحالة معتمد<br>';
        h += '- إلغاء الدفعة</div>';
    }
    else if(action === 'cancel'){
        $('#cam_header').css('background','#ea580c');
        $('#cam_title').text('فك احتساب الدفعة ' + info.no);
        $('#cam_btn').attr('class','btn btn-sm btn-warning').html('<i class="fa fa-undo me-1"></i> تأكيد فك الاحتساب');
        h = '<table class="table table-sm mb-2" style="font-size:.85rem">';
        h += '<tr><td class="text-muted">رقم الدفعة</td><td class="fw-bold">'+info.no+'</td></tr>';
        h += '<tr><td class="text-muted">عدد الموظفين</td><td class="fw-bold">'+info.emp+'</td></tr>';
        h += '<tr><td class="text-muted">إجمالي المبلغ</td><td class="fw-bold">'+info.amount+'</td></tr>';
        h += '</table>';
        h += '<div class="alert alert-warning py-2 mb-0" style="font-size:.82rem">';
        h += '<i class="fa fa-info-circle me-1"></i> سيتم:<br>';
        h += '- إرجاع الموظفين لحالة "معتمد"<br>';
        h += '- وضع الدفعة بحالة "ملغاة" (تبقى ظاهرة في السجل للتسلسل)<br>';
        h += '- لا يمكن استرجاع الدفعة بعد الإلغاء — احتسب من جديد لو احتجت';
        h += '</div>';
    }

    $('#cam_body').html(h);
    $('#confirmActionModal').modal('show');
}

function camExecute(){
    $('#cam_btn').prop('disabled',true).html('<i class="fa fa-spinner fa-spin"></i>');
    var url = _camAction === 'pay' ? window._payUrl : _camAction === 'reverse' ? window._reverseUrl : window._cancelUrl;
    get_data(url, {batch_id: _camBatchId}, function(resp){
        var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
        $('#confirmActionModal').modal('hide');
        if(j.ok){ success_msg('تم', j.msg || 'تمت العملية بنجاح'); location.reload(); }
        else { danger_msg('خطأ', j.msg); }
    }, 'json');
}

function payBatch(batchId, batchNo){ showConfirmModal('pay', batchId); }
function reverseBatch(batchId, batchNo){ showConfirmModal('reverse', batchId); }
function cancelBatch(batchId, batchNo){ showConfirmModal('cancel', batchId); }

function showBatchDetail(batchId, batchNo){
    _batchDetailNo = batchNo;
    $('#bdm_title').text('تفاصيل الدفعة — ' + batchNo);
    $('#bdm_loading').show();
    $('#bdm_content').hide();
    $('#batchDetailModal').modal('show');

    get_data(window._detailUrl + '/' + batchId, {}, function(resp){
        $('#bdm_loading').hide();
        var resp = (typeof resp === 'string') ? JSON.parse(resp) : resp;
        if(!resp.ok){ danger_msg('خطأ', resp.msg || 'خطأ'); return; }
        _batchDetailData = resp.data || [];
        renderBatchDetail(_batchDetailData);
        $('#bdm_content').show();
    });
}

function nf(n){ return parseFloat(n||0).toLocaleString('en-US',{minimumFractionDigits:2,maximumFractionDigits:2}); }

function renderBatchDetail(rows){
    // تجميع حسب البنك
    var banks = {}, total = 0, empCount = 0;
    for(var i=0; i<rows.length; i++){
        var r = rows[i], bk = r.BANK_NAME || 'غير محدد', amt = parseFloat(r.TOTAL_AMOUNT||0);
        if(!banks[bk]) banks[bk] = {count:0, total:0};
        banks[bk].count++; banks[bk].total += amt;
        total += amt; empCount++;
    }
    var bankKeys = Object.keys(banks).sort(function(a,b){ return banks[b].total - banks[a].total; });
    var bhtml = '';
    for(var i=0; i<bankKeys.length; i++){
        var bk = bankKeys[i], bd = banks[bk];
        bhtml += '<tr><td>'+(i+1)+'</td><td class="fw-bold">'+bk+'</td><td class="text-center">'+bd.count+'</td><td class="text-end amt">'+nf(bd.total)+'</td></tr>';
    }
    $('#bdm_bank_body').html(bhtml);
    $('#bdm_total_emp').text(empCount);
    $('#bdm_total_amt').text(nf(total));

    // جدول الموظفين
    var ehtml = '';
    for(var i=0; i<rows.length; i++){
        var r = rows[i];
        ehtml += '<tr>';
        ehtml += '<td class="text-muted">'+(i+1)+'</td>';
        ehtml += '<td><strong>'+r.EMP_NO+'</strong> — '+(r.EMP_NAME||'')+'</td>';
        ehtml += '<td style="font-size:.75rem">'+(r.BRANCH_NAME||'')+'</td>';
        ehtml += '<td style="font-size:.75rem">'+(r.BANK_NAME||'')+'</td>';
        ehtml += '<td style="font-size:.72rem">'+(r.REQ_NOS||'')+'</td>';
        ehtml += '<td class="text-end amt">'+nf(parseFloat(r.TOTAL_AMOUNT||0))+'</td>';
        ehtml += '</tr>';
    }
    $('#bdm_emp_body').html(ehtml);
}

function exportBatchHistoryExcel(){
    if(!_batchDetailData.length) return;
    var rows = _batchDetailData;

    // ورقة 1: ملخص بنوك
    var banks = {};
    for(var i=0; i<rows.length; i++){
        var bk = rows[i].BANK_NAME || 'غير محدد', amt = parseFloat(rows[i].TOTAL_AMOUNT||0);
        if(!banks[bk]) banks[bk] = {count:0, total:0};
        banks[bk].count++; banks[bk].total += amt;
    }
    var s1 = [['#', 'البنك', 'عدد الموظفين', 'المبلغ']], n=0;
    var bankKeys = Object.keys(banks).sort(function(a,b){ return banks[b].total - banks[a].total; });
    for(var i=0; i<bankKeys.length; i++){ n++; s1.push([n, bankKeys[i], banks[bankKeys[i]].count, banks[bankKeys[i]].total]); }

    // ورقة 2: موظفين
    var s2 = [['#', 'رقم الموظف', 'اسم الموظف', 'المقر', 'البنك الرئيسي', 'فرع البنك', 'الطلبات', 'المبلغ']];
    for(var i=0; i<rows.length; i++){
        var r = rows[i];
        s2.push([i+1, parseInt(r.EMP_NO)||r.EMP_NO, r.EMP_NAME||'', r.BRANCH_NAME||'', r.MASTER_BANK_NAME||'', r.BANK_NAME||'', r.REQ_NOS||'', parseFloat(r.TOTAL_AMOUNT||0)]);
    }

    var wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, XLSX.utils.aoa_to_sheet(s1), 'ملخص البنوك');
    XLSX.utils.book_append_sheet(wb, XLSX.utils.aoa_to_sheet(s2), 'الموظفين');
    XLSX.writeFile(wb, 'دفعة_' + _batchDetailNo + '.xlsx');
}

// ===================== datepicker =====================
$('#bh_date_from, #bh_date_to').datetimepicker({ format: 'DD/MM/YYYY', pickTime: false });

// ===================== فلترة الجدول =====================
function _parseDate(str){
    if(!str) return null;
    var p = str.split('/');
    if(p.length === 3) return p[2]+p[1]+p[0]; // YYYYMMDD
    return null;
}

function bhFilter(){
    var st = $('#bh_status').val();
    var df = _parseDate($('#bh_date_from').val());
    var dt = _parseDate($('#bh_date_to').val());
    var bn = $('#bh_batch_no').val().trim().toUpperCase();
    var rn = $('#bh_req_no').val().trim().toUpperCase();

    $('#batchHistoryTable tbody tr').each(function(){
        var $r = $(this);
        var show = true;
        // حالة
        if(st !== ''){
            var rowSt = $r.find('.bh-status').attr('class') || '';
            if(st === '0' && rowSt.indexOf('s0') < 0) show = false;
            if(st === '2' && rowSt.indexOf('s2') < 0) show = false;
            if(st === '9' && rowSt.indexOf('s9') < 0) show = false;
        }
        // تاريخ
        if(show && (df || dt)){
            var rowDate = _parseDate($r.find('td:eq(2)').text().trim());
            if(rowDate){
                if(df && rowDate < df) show = false;
                if(dt && rowDate > dt) show = false;
            }
        }
        // رقم الدفعة
        if(show && bn){
            var rowBn = $r.find('td:eq(1)').text().trim().toUpperCase();
            if(rowBn.indexOf(bn) < 0) show = false;
        }
        // رقم الطلب
        if(show && rn){
            var rowReqs = $r.find('td:eq(10)').text().trim().toUpperCase();
            if(rowReqs.indexOf(rn) < 0) show = false;
        }
        $r.toggle(show);
    });
}

function bhClear(){
    $('#bh_status, #bh_date_from, #bh_date_to, #bh_batch_no, #bh_req_no').val('');
    $('#batchHistoryTable tbody tr').show();
}

var _bankDDLoaded = {};
function loadBankDD(btn, batchId){
    if(_bankDDLoaded[batchId]) return;
    get_data(window._bankListUrl, {batch_id: batchId}, function(resp){
        var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
        var $dd = $('#bankDD_'+batchId);
        if(!j.ok || !j.banks || j.banks.length === 0){ $dd.html('<li class="dropdown-item text-muted" style="font-size:.8rem">لا توجد بنوك</li>'); return; }
        var html = '';
        for(var i=0; i<j.banks.length; i++){
            var b = j.banks[i];
            html += '<li><a class="dropdown-item" style="font-size:.82rem" href="'+window._bankFileUrl+'?batch_id='+batchId+'&master_bank_no='+b.no+'">';
            html += '<i class="fa fa-file-excel-o me-2 text-success"></i>'+b.name+' <small class="text-muted">('+b.count+' — '+nf(b.total)+')</small></a></li>';
        }
        $dd.html(html);
        _bankDDLoaded[batchId] = true;
    }, 'json');
}

function printBatchReport(){
    if(!_batchDetailData.length) return;
    var rows = _batchDetailData;

    var banks = {}, gt = {total:0,t1:0,t2:0,t3:0,t4:0,empSet:{},types:{}};
    for(var i=0; i<rows.length; i++){
        var row = rows[i];
        var masterBk = row.MASTER_BANK_NAME || row.BANK_NAME || 'غير محدد';
        var branchBk = row.BANK_NAME || 'غير محدد';
        var amt = parseFloat(row.TOTAL_AMOUNT||0);
        var a1 = parseFloat(row.AMT_TYPE1||0), a2 = parseFloat(row.AMT_TYPE2||0);
        var a3 = parseFloat(row.AMT_TYPE3||0), a4 = parseFloat(row.AMT_TYPE4||0);

        if(!banks[masterBk]) banks[masterBk] = {branches:{}, total:0, t1:0, t2:0, t3:0, t4:0, empSet:{}};
        if(!banks[masterBk].branches[branchBk]) banks[masterBk].branches[branchBk] = {total:0, t1:0, t2:0, t3:0, t4:0, empSet:{}};
        var br = banks[masterBk].branches[branchBk];
        br.total += amt; br.t1 += a1; br.t2 += a2; br.t3 += a3; br.t4 += a4; br.empSet[row.EMP_NO] = 1;
        banks[masterBk].total += amt; banks[masterBk].t1 += a1; banks[masterBk].t2 += a2; banks[masterBk].t3 += a3; banks[masterBk].t4 += a4; banks[masterBk].empSet[row.EMP_NO] = 1;
        gt.total += amt; gt.t1 += a1; gt.t2 += a2; gt.t3 += a3; gt.t4 += a4; gt.empSet[row.EMP_NO] = 1;
    }
    var gtEmpCount = Object.keys(gt.empSet).length;
    var bankKeys = Object.keys(banks).sort(function(a,b){ return banks[b].total - banks[a].total; });
    var today = new Date(); var dateStr = today.getDate()+'/'+(today.getMonth()+1)+'/'+today.getFullYear();

    var w = window.open('', '_blank');
    var h = '<!DOCTYPE html><html dir="rtl" lang="ar"><head><meta charset="utf-8">';
    h += '<title>تقرير الدفعة ' + _batchDetailNo + '</title>';
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
    h += '<h2>تقرير الدفعة ' + _batchDetailNo + '</h2>';
    h += '<h4>بالشيكل الإسرائيلي</h4>';

    h += '<table class="info-tbl"><tr><td><strong>التاريخ:</strong> '+dateStr+'</td>';
    h += '<td><strong>عدد الموظفين:</strong> '+gtEmpCount+'</td>';
    h += '<td><strong>عدد الحركات:</strong> '+rows.length+'</td>';
    h += '<td><strong>عدد البنوك:</strong> '+bankKeys.length+'</td>';
    h += '<td><strong>الإجمالي:</strong> '+nf(gt.total)+'</td></tr></table>';

    h += '<table><thead><tr>';
    h += '<th style="width:25px">م</th><th>البنك</th><th>موظفين</th>';
    h += '<th>راتب كامل</th><th>راتب جزئي</th><th>مستحقات</th><th>أخرى</th><th>الإجمالي</th>';
    h += '</tr></thead><tbody>';

    var rowNum = 0;
    for(var b=0; b<bankKeys.length; b++){
        var bk = bankKeys[b], bdata = banks[bk];
        var brKeys = Object.keys(bdata.branches).sort(function(a,b2){ return bdata.branches[b2].total - bdata.branches[a].total; });
        var bEmpCount = Object.keys(bdata.empSet).length;

        if(brKeys.length > 1){
            h += '<tr class="master-hdr"><td colspan="8">'+bk+' ('+brKeys.length+' فرع)</td></tr>';
            for(var br=0; br<brKeys.length; br++){
                rowNum++;
                var brName = brKeys[br], brData = bdata.branches[brName];
                h += '<tr><td>'+rowNum+'</td><td style="text-align:right;padding-right:15px">'+brName+'</td>';
                h += '<td>'+Object.keys(brData.empSet).length+'</td>';
                h += '<td class="num">'+nf(brData.t1)+'</td><td class="num">'+nf(brData.t2)+'</td>';
                h += '<td class="num">'+nf(brData.t3)+'</td><td class="num">'+nf(brData.t4)+'</td>';
                h += '<td class="num">'+nf(brData.total)+'</td></tr>';
            }
            h += '<tr class="sub-total"><td colspan="2" style="text-align:right">إجمالي '+bk+'</td><td>'+bEmpCount+'</td>';
            h += '<td class="num">'+nf(bdata.t1)+'</td><td class="num">'+nf(bdata.t2)+'</td>';
            h += '<td class="num">'+nf(bdata.t3)+'</td><td class="num">'+nf(bdata.t4)+'</td>';
            h += '<td class="num">'+nf(bdata.total)+'</td></tr>';
        } else {
            rowNum++;
            var brData = bdata.branches[brKeys[0]];
            h += '<tr><td>'+rowNum+'</td><td style="text-align:right">'+brKeys[0]+'</td>';
            h += '<td>'+Object.keys(brData.empSet).length+'</td>';
            h += '<td class="num">'+nf(brData.t1)+'</td><td class="num">'+nf(brData.t2)+'</td>';
            h += '<td class="num">'+nf(brData.t3)+'</td><td class="num">'+nf(brData.t4)+'</td>';
            h += '<td class="num" style="font-weight:bold">'+nf(brData.total)+'</td></tr>';
        }
    }

    h += '<tr class="grand-total"><td colspan="2">الإجمالي الكلي</td><td>'+gtEmpCount+'</td>';
    h += '<td class="num">'+nf(gt.t1)+'</td><td class="num">'+nf(gt.t2)+'</td>';
    h += '<td class="num">'+nf(gt.t3)+'</td><td class="num">'+nf(gt.t4)+'</td>';
    h += '<td class="num">'+nf(gt.total)+'</td></tr>';
    h += '</tbody></table>';

    h += '</body></html>';
    w.document.write(h); w.document.close();
}

</script>
SCRIPT;

$cancel_url_js   = base_url("$MODULE_NAME/$TB_NAME/batch_cancel_action");
$reverse_url_js  = base_url("$MODULE_NAME/$TB_NAME/batch_reverse_pay_action");
$pay_url_js      = base_url("$MODULE_NAME/$TB_NAME/batch_pay_action");
$bank_list_url   = base_url("$MODULE_NAME/$TB_NAME/export_bank_list");
$bank_file_url   = base_url("$MODULE_NAME/$TB_NAME/export_bank_file");
$scripts = '<script>'
    . 'window._detailUrl="' . $detail_url_js . '";'
    . 'window._cancelUrl="' . $cancel_url_js . '";'
    . 'window._reverseUrl="' . $reverse_url_js . '";'
    . 'window._payUrl="' . $pay_url_js . '";'
    . 'window._bankListUrl="' . $bank_list_url . '";'
    . 'window._bankFileUrl="' . $bank_file_url . '";'
    . '</script>' . $scripts;
sec_scripts($scripts);
?>
