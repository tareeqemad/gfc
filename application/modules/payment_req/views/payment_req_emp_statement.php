<?php
$MODULE_NAME = 'payment_req';
$TB_NAME     = 'payment_req';
$data_url    = base_url("$MODULE_NAME/$TB_NAME/emp_statement");
$export_url  = base_url("$MODULE_NAME/$TB_NAME/emp_statement_export");
echo AntiForgeryToken();
?>
<style>
.stmt-card{padding:.65rem 1rem;border-radius:10px;border:1px solid #e2e8f0;text-align:center;flex:1;min-width:110px}
.stmt-card .lbl{font-size:.62rem;color:#94a3b8;margin-bottom:.15rem}.stmt-card .val{font-size:1rem;font-weight:800}
.stmt-card.c-avail{background:#f0fdf4;border-color:#bbf7d0}.stmt-card.c-avail .val{color:#059669;font-size:1.2rem}
.stmt-tbl td,.stmt-tbl th{vertical-align:middle;font-size:.82rem}
</style>

<div class="page-header">
    <div><h1 class="page-title"><?= $title ?></h1></div>
    <div class="ms-auto pageheader-btn">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url("$MODULE_NAME/$TB_NAME") ?>">صرف الرواتب</a></li>
            <li class="breadcrumb-item active"><?= $title ?></li>
        </ol>
    </div>
</div>

<div class="row"><div class="col-lg-12"><div class="card">
    <div class="card-header"><h3 class="card-title"><?= $title ?></h3></div>
    <div class="card-body">
        <div class="row g-2 mb-3">
            <div class="col-md-3">
                <label class="fw-bold" style="font-size:.78rem">الموظف</label>
                <select id="stmt_emp" class="form-control sel2">
                    <option value="">- اختر -</option>
                    <?php foreach ($emp_no_cons as $row): ?>
                        <option value="<?= $row['EMP_NO'] ?>" <?= (isset($emp_no) && $emp_no == $row['EMP_NO']) ? 'selected' : '' ?>><?= $row['EMP_NO'].' - '.$row['EMP_NAME'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="fw-bold" style="font-size:.78rem">من شهر</label>
                <input type="text" id="stmt_from" class="form-control" placeholder="YYYYMM">
            </div>
            <div class="col-md-2">
                <label class="fw-bold" style="font-size:.78rem">إلى شهر</label>
                <input type="text" id="stmt_to" class="form-control" placeholder="YYYYMM">
            </div>
            <div class="col-md-3 d-flex align-items-end gap-1">
                <button class="btn btn-primary" onclick="stmtLoad()"><i class="fa fa-search"></i> عرض</button>
                <button class="btn btn-outline-success" onclick="stmtExport()"><i class="fa fa-file-excel-o"></i> Excel</button>
                <button class="btn btn-outline-dark" onclick="window.print()"><i class="fa fa-print"></i></button>
            </div>
        </div>

        <!-- ملخص الأرصدة -->
        <div id="stmt_summary" class="d-flex gap-2 flex-wrap mb-3" style="display:none"></div>

        <!-- الجدول -->
        <div class="table-responsive">
            <table class="table table-bordered table-sm stmt-tbl" id="stmtTable" style="display:none">
                <thead class="table-dark">
                <tr><th>#</th><th>رقم الطلب</th><th>الشهر</th><th>نوع الطلب</th><th class="text-end">المبلغ</th><th>بند الصرف</th><th>حالة</th><th>تاريخ الصرف</th><th>ملاحظة</th></tr>
                </thead>
                <tbody id="stmtBody"></tbody>
                <tfoot><tr class="table-light fw-bold"><td colspan="4" class="text-end">الإجمالي</td><td class="text-end" id="stmtTotal">0</td><td colspan="4"></td></tr></tfoot>
            </table>
        </div>
        <div id="stmt_loading" style="display:none" class="text-center py-4"><i class="fa fa-spinner fa-spin fa-2x"></i></div>
        <div id="stmt_empty" style="display:none" class="text-center py-4 text-muted">لا توجد بيانات</div>
    </div>
</div></div></div>

<script>
$(function(){ $('.sel2:not("[id^=\'s2\']")').select2(); <?php if (!empty($emp_no)): ?>stmtLoad();<?php endif; ?> });
function nf(n){ return parseFloat(n||0).toLocaleString('en-US',{minimumFractionDigits:2,maximumFractionDigits:2}); }
var _statusBadges = {0:['مسودة','#fef3c7','#92400e'],1:['معتمد','#dbeafe','#1e40af'],2:['منفّذ للصرف','#d1fae5','#065f46'],9:['ملغى','#fee2e2','#991b1b']};
function _stBadge(st){ var b=_statusBadges[st]||['—','#f1f5f9','#475569']; return '<span style="background:'+b[1]+';color:'+b[2]+';padding:2px 8px;border-radius:6px;font-size:.72rem;font-weight:600">'+b[0]+'</span>'; }

function stmtLoad(){
    var emp = $('#stmt_emp').val();
    if(!emp){ danger_msg('تحذير','اختر الموظف'); return; }
    $('#stmt_loading').show(); $('#stmtTable,#stmt_empty,#stmt_summary').hide();

    jQuery.post('<?= $data_url ?>', {
        emp_no: emp, month_from: $('#stmt_from').val()||'', month_to: $('#stmt_to').val()||''
    }, function(resp){
        $('#stmt_loading').hide();
        var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
        if(!j.ok){ danger_msg('خطأ', j.msg); return; }

        // ملخص الأرصدة
        var s = j.summary || {};
        var sh = '';
        sh += '<div class="stmt-card"><div class="lbl">أساسي</div><div class="val">'+nf(s.DUES_BASE)+'</div></div>';
        sh += '<div class="stmt-card" style="border-color:#bbf7d0"><div class="lbl">+ إضافات</div><div class="val" style="color:#059669">'+nf(s.DUES_ADD)+'</div></div>';
        sh += '<div class="stmt-card" style="border-color:#fecaca"><div class="lbl">- خصومات</div><div class="val" style="color:#dc2626">'+nf(s.DUES_DED)+'</div></div>';
        sh += '<div class="stmt-card" style="border-color:#fde68a"><div class="lbl">محجوز</div><div class="val" style="color:#92400e">'+nf(s.DUES_RESERVED)+'</div></div>';
        sh += '<div class="stmt-card" style="border-color:#bfdbfe"><div class="lbl">مدفوع</div><div class="val" style="color:#1e40af">'+nf(s.DUES_PAID)+'</div></div>';
        sh += '<div class="stmt-card c-avail"><div class="lbl">المتاح</div><div class="val">'+nf(s.DUES_AVAILABLE)+'</div></div>';
        $('#stmt_summary').html(sh).show();

        // الجدول
        var rows = j.data || [];
        if(rows.length === 0){ $('#stmt_empty').show(); return; }

        var html = '', total = 0;
        for(var i=0; i<rows.length; i++){
            var d = rows[i], amt = parseFloat(d.REQ_AMOUNT||0);
            total += amt;
            var thm = (d.THE_MONTH||'').toString(); if(thm.length==6) thm = thm.substr(4,2)+'/'+thm.substr(0,4);
            html += '<tr><td>'+(i+1)+'</td><td><a href="<?= base_url("$MODULE_NAME/$TB_NAME/get/") ?>'+d.REQ_ID+'">'+d.REQ_NO+'</a></td>';
            html += '<td>'+thm+'</td><td>'+(d.REQ_TYPE_NAME||'')+'</td>';
            html += '<td class="text-end fw-bold">'+nf(amt)+'</td>';
            html += '<td>'+(d.PAY_TYPE_NAME||'')+'</td>';
            html += '<td>'+_stBadge(parseInt(d.DETAIL_STATUS||0))+'</td>';
            html += '<td>'+(d.PAY_DATE||'')+'</td>';
            html += '<td class="text-muted" style="font-size:.75rem">'+(d.NOTE||'')+'</td></tr>';
        }
        $('#stmtBody').html(html); $('#stmtTotal').text(nf(total)); $('#stmtTable').show();
    }, 'json');
}

function stmtExport(){
    var emp = $('#stmt_emp').val();
    if(!emp){ danger_msg('تحذير','اختر الموظف'); return; }
    window.location = '<?= $export_url ?>?emp_no=' + emp + '&month_from=' + ($('#stmt_from').val()||'') + '&month_to=' + ($('#stmt_to').val()||'');
}
</script>
