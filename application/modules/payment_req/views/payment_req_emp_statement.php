<?php
$MODULE_NAME = 'payment_req';
$TB_NAME     = 'payment_req';
$data_url    = base_url("$MODULE_NAME/$TB_NAME/emp_statement");
$export_url  = base_url("$MODULE_NAME/$TB_NAME/emp_statement_export");
echo AntiForgeryToken();
?>
<style>
/* ═══ Header / Filter ═══ */
.es-filter{display:flex;align-items:end;gap:.5rem;flex-wrap:wrap;padding:.6rem .85rem;background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;margin-bottom:.75rem}
.es-filter .form-group{margin-bottom:0}
.es-filter label{font-size:.72rem;font-weight:700;color:#475569;display:block;margin-bottom:.2rem}

/* ═══ Emp Header Card ═══ */
.es-empcard{display:flex;align-items:center;gap:1rem;padding:.85rem 1.1rem;background:#fff;border:1px solid #e2e8f0;border-radius:12px;margin-bottom:.75rem;box-shadow:0 1px 2px rgba(0,0,0,.04)}
.es-empcard .es-avatar{width:60px;height:60px;border-radius:50%;background:#1e40af;color:#fff;display:flex;align-items:center;justify-content:center;font-size:1.5rem;font-weight:800;flex-shrink:0}
.es-empcard.s-2 .es-avatar{background:#991b1b}
.es-empcard.s-0 .es-avatar{background:#9a3412}
.es-empcard.s-4 .es-avatar{background:#92400e}
.es-empcard .es-info{flex:1;min-width:200px}
.es-empcard .es-name{font-size:1.05rem;font-weight:800;color:#1e293b;margin-bottom:.15rem}
.es-empcard .es-meta{display:flex;gap:.7rem;flex-wrap:wrap;font-size:.78rem;color:#64748b}
.es-empcard .es-meta b{color:#1e293b}
.es-empcard .es-meta i{color:#94a3b8;margin-left:3px}
.es-empcard .es-status{font-size:.75rem;padding:.3em .7em;border-radius:6px;font-weight:700}
.es-empcard .es-status.s1{background:#dcfce7;color:#166534}
.es-empcard .es-status.s0{background:#ffedd5;color:#9a3412}
.es-empcard .es-status.s2{background:#fee2e2;color:#991b1b}
.es-empcard .es-status.s4{background:#fef3c7;color:#92400e}

/* ═══ Section Card ═══ */
.es-sec{background:#fff;border:1px solid #e2e8f0;border-radius:10px;padding:.65rem .85rem;margin-bottom:.75rem}
.es-sec h6{margin:0 0 .55rem 0;font-size:.85rem;font-weight:700;color:#1e293b;display:flex;align-items:center;gap:.4rem}
.es-sec h6 i{color:#3b82f6}
.es-sec h6 .es-cnt{font-size:.7rem;font-weight:600;color:#64748b;background:#f1f5f9;padding:.15em .55em;border-radius:5px}

/* ═══ Balance Cards ═══ */
.es-bal{display:flex;gap:.5rem;flex-wrap:wrap}
.es-bal-card{flex:1;min-width:115px;text-align:center;padding:.55rem;border-radius:8px;border:1px solid #e2e8f0;background:#fafafa}
.es-bal-card .lbl{font-size:.66rem;color:#64748b;font-weight:600;margin-bottom:.2rem}
.es-bal-card .val{font-size:1rem;font-weight:800;color:#1e293b;direction:ltr}
.es-bal-card.c-add{border-color:#bbf7d0;background:#f0fdf4}.es-bal-card.c-add .val{color:#15803d}
.es-bal-card.c-ded{border-color:#fecaca;background:#fef2f2}.es-bal-card.c-ded .val{color:#991b1b}
.es-bal-card.c-rsv{border-color:#fde68a;background:#fffbeb}.es-bal-card.c-rsv .val{color:#92400e}
.es-bal-card.c-paid{border-color:#bfdbfe;background:#eff6ff}.es-bal-card.c-paid .val{color:#1e40af}
.es-bal-card.c-avl{border-color:#86efac;background:#dcfce7}.es-bal-card.c-avl .val{color:#059669;font-size:1.15rem}

/* ═══ Accounts Grid ═══ */
.es-accs{display:flex;gap:.5rem;flex-wrap:wrap}
.es-acc{flex:1;min-width:280px;border:1.5px solid #e2e8f0;border-radius:8px;padding:.55rem .75rem;background:#fff;font-size:.78rem}
.es-acc.is-default{border-color:#fbbf24;background:#fffbeb}
.es-acc.is-inactive{opacity:.6;background:#f1f5f9}
.es-acc .ac-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:.25rem}
.es-acc .ac-prov{font-weight:700;color:#1e293b;display:inline-flex;align-items:center;gap:.3rem}
.es-acc .ac-prov i{font-size:.95rem}
.es-acc .ac-prov.bank i{color:#1e40af}
.es-acc .ac-prov.wallet i{color:#6d28d9}
.es-acc .ac-split{font-size:.7rem;padding:.15em .5em;border-radius:4px;font-weight:700}
.es-acc .ac-split.t1{background:#dbeafe;color:#1e40af}
.es-acc .ac-split.t2{background:#fef3c7;color:#92400e}
.es-acc .ac-split.t3{background:#dcfce7;color:#166534}
.es-acc .ac-line{font-size:.7rem;color:#475569;font-family:monospace;direction:ltr;margin-top:.2rem;letter-spacing:.3px}
.es-acc .ac-owner{font-size:.7rem;color:#7c3aed;margin-top:.15rem}
.es-acc .ac-owner i{margin-left:2px}
.es-acc .ac-tag{font-size:.62rem;padding:.1em .42em;border-radius:4px;font-weight:700;margin-inline-start:.3rem}
.es-acc .ac-tag.t-default{background:#fef3c7;color:#92400e}
.es-acc .ac-tag.t-inact{background:#fee2e2;color:#991b1b}

/* ═══ Pending Batches ═══ */
.es-pend{display:flex;flex-direction:column;gap:.4rem}
.es-pend-row{display:flex;justify-content:space-between;align-items:center;padding:.4rem .65rem;background:#fef3c7;border:1px solid #fde68a;border-radius:6px;font-size:.8rem}
.es-pend-row .pn-no{font-weight:800;color:#92400e}
.es-pend-row .pn-amt{font-weight:700;direction:ltr}

/* ═══ Requests Table ═══ */
.stmt-tbl{font-size:.8rem}
.stmt-tbl td,.stmt-tbl th{vertical-align:middle}
.stmt-tbl th{background:#f8fafc;font-size:.72rem;color:#475569}
.stmt-tbl tr:hover{background:#f8fafc}
.amt{font-weight:700;color:#1e293b;direction:ltr}

/* ═══ Print ═══ */
@media print {
    body * { visibility: hidden; }
    #printArea, #printArea * { visibility: visible; }
    #printArea { position:absolute; top:0; left:0; right:0; }
    .es-filter, .no-print, .breadcrumb { display:none !important; }
    .es-sec, .es-empcard { box-shadow:none; border:1px solid #ccc; page-break-inside:avoid; }
}
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
    <div class="card-body">

        <!-- ═══ شريط الفلتر ═══ -->
        <div class="es-filter no-print">
            <div class="form-group" style="min-width:280px;flex:1">
                <label><i class="fa fa-user me-1"></i> الموظف</label>
                <select id="stmt_emp" class="form-control sel2">
                    <option value="">— اختر موظف —</option>
                    <?php foreach ($emp_no_cons as $row): ?>
                        <option value="<?= $row['EMP_NO'] ?>" <?= (isset($emp_no) && $emp_no == $row['EMP_NO']) ? 'selected' : '' ?>><?= $row['EMP_NO'].' - '.$row['EMP_NAME'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group" style="width:130px">
                <label><i class="fa fa-calendar me-1"></i> من شهر</label>
                <input type="text" id="stmt_from" class="form-control" placeholder="YYYYMM">
            </div>
            <div class="form-group" style="width:130px">
                <label><i class="fa fa-calendar me-1"></i> إلى شهر</label>
                <input type="text" id="stmt_to" class="form-control" placeholder="YYYYMM">
            </div>
            <div class="d-flex gap-1">
                <button class="btn btn-primary btn-sm" onclick="stmtLoad()"><i class="fa fa-search"></i> عرض</button>
                <button class="btn btn-outline-success btn-sm" onclick="stmtExport()"><i class="fa fa-file-excel-o"></i> Excel</button>
                <button class="btn btn-outline-dark btn-sm" onclick="window.print()"><i class="fa fa-print"></i> طباعة</button>
            </div>
        </div>

        <!-- ═══ منطقة العرض (للطباعة) ═══ -->
        <div id="printArea">

            <!-- بطاقة معلومات الموظف -->
            <div id="es_empcard_wrap" style="display:none"></div>

            <!-- ملخص الأرصدة -->
            <div id="es_balance_wrap" style="display:none">
                <div class="es-sec">
                    <h6><i class="fa fa-balance-scale"></i> ملخص الأرصدة <span class="es-cnt">رصيد المستحقات</span></h6>
                    <div class="es-bal" id="es_balance"></div>
                </div>
            </div>

            <!-- الحسابات -->
            <div id="es_accounts_wrap" style="display:none">
                <div class="es-sec">
                    <h6><i class="fa fa-credit-card"></i> حسابات الصرف <span class="es-cnt" id="es_acc_cnt">0</span></h6>
                    <div class="es-accs" id="es_accounts"></div>
                </div>
            </div>

            <!-- الدفعات المحتسبة (محجوز) -->
            <div id="es_pending_wrap" style="display:none">
                <div class="es-sec" style="background:#fffbeb;border-color:#fde68a">
                    <h6><i class="fa fa-clock-o" style="color:#d97706"></i> دفعات محتسبة لم تُنفذ بعد <span class="es-cnt" id="es_pen_cnt">0</span></h6>
                    <div class="es-pend" id="es_pending"></div>
                </div>
            </div>

            <!-- جدول الطلبات -->
            <div id="es_requests_wrap" style="display:none">
                <div class="es-sec">
                    <h6><i class="fa fa-list-alt"></i> سجل الطلبات والدفعات <span class="es-cnt" id="es_req_cnt">0</span></h6>
                    <div class="table-responsive">
                    <table class="table table-bordered table-sm stmt-tbl mb-0" id="stmtTable">
                        <thead>
                        <tr>
                            <th style="width:30px">#</th>
                            <th style="width:90px">رقم الطلب</th>
                            <th style="width:75px">الشهر</th>
                            <th>نوع الطلب</th>
                            <th class="text-end" style="width:100px">المبلغ</th>
                            <th>بند الصرف</th>
                            <th style="width:90px">الحالة</th>
                            <th style="width:90px">تاريخ الصرف</th>
                            <th>ملاحظة</th>
                        </tr>
                        </thead>
                        <tbody id="stmtBody"></tbody>
                        <tfoot>
                        <tr style="background:#1e293b;color:#fff;font-weight:800">
                            <td colspan="4" class="text-end">الإجمالي</td>
                            <td class="text-end" id="stmtTotal" style="direction:ltr">0</td>
                            <td colspan="4"></td>
                        </tr>
                        </tfoot>
                    </table>
                    </div>
                </div>
            </div>

        </div><!-- /printArea -->

        <div id="stmt_loading" class="text-center py-4" style="display:none">
            <i class="fa fa-spinner fa-spin fa-2x text-primary"></i>
            <div style="margin-top:.5rem;color:#64748b;font-size:.85rem">جاري تحميل بيانات الموظف...</div>
        </div>
        <div id="stmt_empty_init" class="text-center py-5 text-muted">
            <i class="fa fa-user-circle fa-3x" style="opacity:.3"></i>
            <div style="margin-top:.5rem">اختر موظف لعرض كشف حسابه</div>
        </div>
        <div id="stmt_empty" class="text-center py-4 text-muted" style="display:none">
            <i class="fa fa-inbox fa-2x" style="opacity:.4"></i>
            <div style="margin-top:.5rem">لا توجد طلبات لهذا الموظف</div>
        </div>

    </div>
</div></div></div>

<?php ob_start(); ?>
<script type="text/javascript">
$(function(){
    $('.sel2:not("[id^=\'s2\']")').select2();
    <?php if (!empty($emp_no)): ?>stmtLoad();<?php endif; ?>
});

function nf(n){ return parseFloat(n||0).toLocaleString('en-US',{minimumFractionDigits:2,maximumFractionDigits:2}); }
function escHtml(s){ return String(s == null ? '' : s).replace(/[&<>"']/g, function(c){
    return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]; }); }

var _statusBadges = {
    0:['مسودة','#fef3c7','#92400e'],
    1:['معتمد','#dbeafe','#1e40af'],
    2:['منفّذ','#d1fae5','#065f46'],
    3:['معتمد جزئي','#e0e7ff','#3730a3'],
    4:['محتسب','#ccfbf1','#0f766e'],
    9:['ملغى','#fee2e2','#991b1b']
};
function _stBadge(st){
    var b = _statusBadges[st] || ['—','#f1f5f9','#475569'];
    return '<span style="background:'+b[1]+';color:'+b[2]+';padding:2px 8px;border-radius:5px;font-size:.7rem;font-weight:700">'+b[0]+'</span>';
}

// خرائط حالة الموظف (4 حالات)
var _empStatusMap = {
    1: {label:'فعّال',     icon:'fa-check-circle', cls:'s1'},
    0: {label:'متقاعد',    icon:'fa-clock-o',      cls:'s0'},
    2: {label:'متوفى',     icon:'fa-times-circle', cls:'s2'},
    4: {label:'حساب مغلق', icon:'fa-ban',          cls:'s4'}
};

function stmtLoad(){
    var emp = $('#stmt_emp').val();
    if(!emp){ danger_msg('تحذير','اختر الموظف'); return; }

    $('#stmt_loading').show();
    $('#stmt_empty_init,#stmt_empty').hide();
    $('#es_empcard_wrap,#es_balance_wrap,#es_accounts_wrap,#es_pending_wrap,#es_requests_wrap').hide();

    jQuery.post('<?= $data_url ?>', {
        emp_no: emp,
        month_from: $('#stmt_from').val()||'',
        month_to:   $('#stmt_to').val()||''
    }, function(resp){
        $('#stmt_loading').hide();
        var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
        if(!j.ok){ danger_msg('خطأ', j.msg); return; }

        // 1) بطاقة الموظف
        _renderEmpCard(j.emp_info || {});

        // 2) ملخص الأرصدة
        _renderBalance(j.summary || {});

        // 3) الحسابات
        _renderAccounts(j.accounts || []);

        // 4) الدفعات المحتسبة
        _renderPending(j.pending || []);

        // 5) الطلبات
        _renderRequests(j.data || []);
    }, 'json');
}

function _renderEmpCard(info){
    if(!info || !info.EMP_NO){ return; }
    var st = parseInt(info.DISPLAY_STATUS || 1);
    var stMeta = _empStatusMap[st] || _empStatusMap[1];
    var initial = String(info.EMP_NAME || '?').trim().charAt(0);
    var html = '<div class="es-empcard s-' + st + '">' +
        '<div class="es-avatar">' + escHtml(initial) + '</div>' +
        '<div class="es-info">' +
            '<div class="es-name">' +
                '<span class="es-status ' + stMeta.cls + '"><i class="fa ' + stMeta.icon + '"></i> ' + stMeta.label + '</span> ' +
                escHtml(info.EMP_NAME || '') +
                ' <span style="color:#94a3b8;font-size:.85rem">— رقم ' + info.EMP_NO + '</span>' +
            '</div>' +
            '<div class="es-meta">' +
                (info.NAT_ID    ? '<span><i class="fa fa-id-card"></i><b>' + escHtml(info.NAT_ID) + '</b></span>' : '') +
                (info.BRANCH_NAME ? '<span><i class="fa fa-building-o"></i> المقر: <b>' + escHtml(info.BRANCH_NAME) + '</b></span>' : '') +
                (info.HR_BANK_NAME ? '<span><i class="fa fa-bank"></i> بنك HR: <b>' + escHtml(info.HR_BANK_NAME) + '</b></span>' : '') +
                (info.TEL       ? '<span><i class="fa fa-phone"></i> <b>' + escHtml(info.TEL) + '</b></span>' : '') +
            '</div>' +
        '</div></div>';
    $('#es_empcard_wrap').html(html).show();
}

function _renderBalance(s){
    var html = '';
    html += '<div class="es-bal-card"><div class="lbl">الأساسي</div><div class="val">' + nf(s.DUES_BASE) + '</div></div>';
    html += '<div class="es-bal-card c-add"><div class="lbl">+ إضافات</div><div class="val">' + nf(s.DUES_ADD) + '</div></div>';
    html += '<div class="es-bal-card c-ded"><div class="lbl">− خصومات</div><div class="val">' + nf(s.DUES_DED) + '</div></div>';
    html += '<div class="es-bal-card c-rsv"><div class="lbl">محجوز</div><div class="val">' + nf(s.DUES_RESERVED) + '</div></div>';
    html += '<div class="es-bal-card c-paid"><div class="lbl">مصروف سابقاً</div><div class="val">' + nf(s.DUES_PAID) + '</div></div>';
    html += '<div class="es-bal-card c-avl"><div class="lbl">المتاح للصرف</div><div class="val">' + nf(s.DUES_AVAILABLE) + '</div></div>';
    $('#es_balance').html(html);
    $('#es_balance_wrap').show();
}

function _renderAccounts(accs){
    if(!accs || accs.length === 0){
        $('#es_accounts').html('<div class="text-muted text-center w-100 py-2" style="font-size:.8rem"><i class="fa fa-info-circle me-1"></i> لا توجد حسابات صرف مسجلة لهذا الموظف</div>');
        $('#es_acc_cnt').text(0);
        $('#es_accounts_wrap').show();
        return;
    }
    var html = '';
    var splitTypes = {
        1: {label:function(v){return 'نسبة ' + (parseFloat(v)||0) + '%';}, cls:'t1'},
        2: {label:function(v){return 'مبلغ ثابت ' + nf(v);},               cls:'t2'},
        3: {label:function(v){return 'كامل الباقي';},                       cls:'t3'}
    };
    accs.forEach(function(a){
        var isWallet  = parseInt(a.PROVIDER_TYPE) === 2;
        var isDef     = parseInt(a.IS_DEFAULT) === 1;
        var isInact   = parseInt(a.IS_ACTIVE) !== 1;
        var st        = parseInt(a.SPLIT_TYPE) || 3;
        var stMeta    = splitTypes[st] || splitTypes[3];
        var cls       = 'es-acc';
        if(isInact) cls += ' is-inactive';
        else if(isDef) cls += ' is-default';

        html += '<div class="' + cls + '">';
        html += '<div class="ac-head">';
        html += '<span class="ac-prov ' + (isWallet ? 'wallet' : 'bank') + '">' +
                '<i class="fa fa-' + (isWallet ? 'mobile' : 'bank') + '"></i> ' + escHtml(a.PROVIDER_NAME || '') +
                (isDef && !isInact ? ' <span class="ac-tag t-default">⭐ افتراضي</span>' : '') +
                (isInact ? ' <span class="ac-tag t-inact">موقوف</span>' : '') +
                '</span>';
        html += '<span class="ac-split ' + stMeta.cls + '">' + stMeta.label(a.SPLIT_VALUE) + '</span>';
        html += '</div>';

        var accNum = a.ACCOUNT_NO || a.WALLET_NUMBER || '—';
        html += '<div class="ac-line"><i class="fa fa-hashtag"></i> ' + escHtml(accNum);
        if(a.BANK_BRANCH_NAME) html += ' — ' + escHtml(a.BANK_BRANCH_NAME);
        html += '</div>';

        if(a.IBAN && !isWallet) html += '<div class="ac-line">' + escHtml(a.IBAN) + '</div>';

        if(a.OWNER_NAME){
            var ownerLine = a.OWNER_NAME;
            if(a.REL_NAME)    ownerLine += ' (' + escHtml(a.REL_NAME) + ')';
            if(a.OWNER_ID_NO) ownerLine += ' — هوية ' + escHtml(a.OWNER_ID_NO);
            html += '<div class="ac-owner"><i class="fa fa-user-o"></i> ' + ownerLine + '</div>';
        }

        html += '</div>';
    });
    $('#es_accounts').html(html);
    $('#es_acc_cnt').text(accs.length);
    $('#es_accounts_wrap').show();
}

function _renderPending(rows){
    if(!rows || rows.length === 0){ return; }
    var html = '';
    rows.forEach(function(b){
        var bid = b.BATCH_ID;
        var bno = b.BATCH_NO;
        var amt = parseFloat(b.EMP_AMOUNT || 0);
        html += '<div class="es-pend-row">';
        html += '<div><i class="fa fa-warning text-warning me-1"></i> ' +
                '<a href="<?= base_url($MODULE_NAME.'/'.$TB_NAME.'/batch_detail/') ?>' + bid + '" target="_blank" class="pn-no">' + escHtml(bno) + '</a>' +
                ' <span class="text-muted" style="font-size:.72rem">— ' + escHtml(b.BATCH_DATE || '') + '</span></div>';
        html += '<div class="pn-amt">' + nf(amt) + ' ₪</div>';
        html += '</div>';
    });
    $('#es_pending').html(html);
    $('#es_pen_cnt').text(rows.length);
    $('#es_pending_wrap').show();
}

function _renderRequests(rows){
    if(!rows || rows.length === 0){
        $('#stmt_empty').show();
        $('#es_requests_wrap').hide();
        return;
    }
    var html = '', total = 0;
    rows.forEach(function(d, i){
        var amt = parseFloat(d.REQ_AMOUNT || 0);
        total += amt;
        var thm = (d.THE_MONTH || '').toString();
        if(thm.length == 6) thm = thm.substr(4,2) + '/' + thm.substr(0,4);

        html += '<tr>';
        html += '<td class="text-center text-muted">' + (i+1) + '</td>';
        html += '<td><a href="<?= base_url($MODULE_NAME.'/'.$TB_NAME.'/get/') ?>' + d.REQ_ID + '" target="_blank" class="fw-bold" style="color:#1e40af">' + escHtml(d.REQ_NO || '') + '</a></td>';
        html += '<td>' + escHtml(thm) + '</td>';
        html += '<td>' + escHtml(d.REQ_TYPE_NAME || '') + '</td>';
        html += '<td class="text-end amt">' + nf(amt) + '</td>';
        html += '<td style="font-size:.74rem">' + escHtml(d.PAY_TYPE_NAME || '') + '</td>';
        html += '<td>' + _stBadge(parseInt(d.DETAIL_STATUS || 0)) + '</td>';
        html += '<td style="font-size:.74rem">' + escHtml(d.PAY_DATE || '') + '</td>';
        html += '<td class="text-muted" style="font-size:.74rem">' + escHtml(d.NOTE || '') + '</td>';
        html += '</tr>';
    });
    $('#stmtBody').html(html);
    $('#stmtTotal').text(nf(total));
    $('#es_req_cnt').text(rows.length);
    $('#es_requests_wrap').show();
}

function stmtExport(){
    var emp = $('#stmt_emp').val();
    if(!emp){ danger_msg('تحذير','اختر الموظف'); return; }
    window.location = '<?= $export_url ?>?emp_no=' + emp +
                      '&month_from=' + ($('#stmt_from').val()||'') +
                      '&month_to=' + ($('#stmt_to').val()||'');
}
</script>
<?php sec_scripts(ob_get_clean()); ?>
