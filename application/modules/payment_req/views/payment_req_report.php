<?php
$MODULE_NAME = 'payment_req';
$TB_NAME     = 'payment_req';
$data_url    = base_url("$MODULE_NAME/$TB_NAME/report_monthly_data");
$export_url  = base_url("$MODULE_NAME/$TB_NAME/report_monthly_export");
echo AntiForgeryToken();
?>
<style>
.rpt-card{padding:.75rem 1rem;border-radius:10px;border:1px solid #e2e8f0;text-align:center;min-width:120px}
.rpt-card .lbl{font-size:.65rem;color:#94a3b8;margin-bottom:.2rem}.rpt-card .val{font-size:1.1rem;font-weight:800}
.rpt-tbl td,.rpt-tbl th{vertical-align:middle;font-size:.82rem}
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
        <!-- فلاتر -->
        <div class="row g-2 mb-3">
            <div class="col-md-2">
                <label class="fw-bold" style="font-size:.78rem">من شهر</label>
                <input type="text" id="rpt_month_from" class="form-control" placeholder="YYYYMM" value="<?= date('Ym') ?>">
            </div>
            <div class="col-md-2">
                <label class="fw-bold" style="font-size:.78rem">إلى شهر</label>
                <input type="text" id="rpt_month_to" class="form-control" placeholder="YYYYMM">
            </div>
            <?php if ($this->user->branch == 1): ?>
            <div class="col-md-2">
                <label class="fw-bold" style="font-size:.78rem">المقر</label>
                <select id="rpt_branch" class="form-select">
                    <option value="">الكل</option>
                    <?php foreach ($branches as $b): ?><option value="<?= $b['BRANCH_NO'] ?>"><?= $b['BRANCH_NAME'] ?></option><?php endforeach; ?>
                </select>
            </div>
            <?php endif; ?>
            <div class="col-md-2">
                <label class="fw-bold" style="font-size:.78rem">نوع الطلب</label>
                <select id="rpt_req_type" class="form-select">
                    <option value="">الكل</option>
                    <?php foreach ($req_type_cons as $r): ?><option value="<?= $r['CON_NO'] ?>"><?= $r['CON_NAME'] ?></option><?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="fw-bold" style="font-size:.78rem">الحالة</label>
                <select id="rpt_status" class="form-select">
                    <option value="">الكل</option>
                    <?php foreach ($status_cons as $s): ?><option value="<?= $s['CON_NO'] ?>"><?= $s['CON_NAME'] ?></option><?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end gap-1">
                <button class="btn btn-primary" onclick="rptLoad()"><i class="fa fa-search"></i> عرض</button>
                <button class="btn btn-outline-success" onclick="rptExport()"><i class="fa fa-file-excel-o"></i></button>
            </div>
        </div>

        <!-- ملخص -->
        <div id="rpt_summary" class="d-flex gap-2 flex-wrap mb-3" style="display:none!important"></div>

        <!-- الجدول -->
        <div class="table-responsive">
            <table class="table table-bordered table-sm rpt-tbl" id="rptTable" style="display:none">
                <thead class="table-dark">
                <tr><th>#</th><th>رقم الطلب</th><th>الشهر</th><th>نوع الطلب</th><th>رقم الموظف</th><th>اسم الموظف</th><th>المقر</th><th class="text-end">المبلغ</th><th>حالة</th></tr>
                </thead>
                <tbody id="rptBody"></tbody>
                <tfoot><tr class="table-light fw-bold"><td colspan="7" class="text-end">الإجمالي</td><td class="text-end" id="rptTotal">0</td><td></td></tr></tfoot>
            </table>
        </div>
        <div id="rpt_loading" style="display:none" class="text-center py-4"><i class="fa fa-spinner fa-spin fa-2x"></i></div>
        <div id="rpt_empty" style="display:none" class="text-center py-4 text-muted">لا توجد بيانات</div>
    </div>
</div></div></div>

<script>
function nf(n){ return parseFloat(n||0).toLocaleString('en-US',{minimumFractionDigits:2,maximumFractionDigits:2}); }
var _statusBadges = {0:['مسودة','#fef3c7','#92400e'],1:['معتمد','#dbeafe','#1e40af'],2:['منفّذ للصرف','#d1fae5','#065f46'],3:['معتمد','#e0e7ff','#3730a3'],4:['محتسب','#ccfbf1','#0f766e'],9:['ملغى','#fee2e2','#991b1b']};
function _stBadge(st){ var b=_statusBadges[st]||['—','#f1f5f9','#475569']; return '<span style="background:'+b[1]+';color:'+b[2]+';padding:2px 8px;border-radius:6px;font-size:.72rem;font-weight:600">'+b[0]+'</span>'; }

function _rptParams(){
    return {
        month_from: $('#rpt_month_from').val(),
        month_to:   $('#rpt_month_to').val() || $('#rpt_month_from').val(),
        branch_no:  $('#rpt_branch').val() || '',
        req_type:   $('#rpt_req_type').val() || '',
        status:     $('#rpt_status').val()
    };
}

function rptLoad(){
    var p = _rptParams();
    if(!p.month_from){ danger_msg('تحذير','أدخل الشهر'); return; }
    $('#rpt_loading').show(); $('#rptTable,#rpt_empty').hide(); $('#rpt_summary').hide();

    jQuery.post('<?= $data_url ?>', p, function(resp){
        $('#rpt_loading').hide();
        var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
        if(!j.ok || !j.data || j.data.length === 0){ $('#rpt_empty').show(); return; }

        var rows = j.data, html = '', total = 0;
        var sumByStatus = {};
        for(var i=0; i<rows.length; i++){
            var d = rows[i], amt = parseFloat(d.REQ_AMOUNT||0), st = parseInt(d.STATUS||0);
            total += amt;
            sumByStatus[st] = (sumByStatus[st]||0) + amt;
            var thm = (d.THE_MONTH||'').toString(); if(thm.length==6) thm = thm.substr(4,2)+'/'+thm.substr(0,4);
            html += '<tr><td>'+(i+1)+'</td><td>'+d.REQ_NO+'</td><td>'+thm+'</td><td>'+(d.REQ_TYPE_NAME||'')+'</td>';
            html += '<td>'+d.EMP_NO+'</td><td>'+(d.EMP_NAME||'')+'</td><td>'+(d.BRANCH_NAME||'')+'</td>';
            html += '<td class="text-end fw-bold">'+nf(amt)+'</td><td>'+_stBadge(parseInt(d.DETAIL_STATUS||0))+'</td></tr>';
        }
        $('#rptBody').html(html); $('#rptTotal').text(nf(total)); $('#rptTable').show();

        var sh = '<div class="rpt-card" style="background:#f8fafc"><div class="lbl">الإجمالي</div><div class="val">'+nf(total)+'</div><div class="lbl">'+rows.length+' سجل</div></div>';
        for(var s in sumByStatus){
            var b = _statusBadges[s]||['—','#f1f5f9','#475569'];
            sh += '<div class="rpt-card" style="background:'+b[1]+'"><div class="lbl" style="color:'+b[2]+'">'+b[0]+'</div><div class="val" style="color:'+b[2]+'">'+nf(sumByStatus[s])+'</div></div>';
        }
        $('#rpt_summary').html(sh).show();
    }, 'json');
}

function rptExport(){
    var p = _rptParams();
    if(!p.month_from){ danger_msg('تحذير','أدخل الشهر'); return; }
    var qs = $.param(p);
    window.location = '<?= $export_url ?>?' + qs;
}
</script>
