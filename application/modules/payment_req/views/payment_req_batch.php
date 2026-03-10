<?php
/**
 * Payment Request — Batch Create (Multi-Group, Server-Side)
 */
$MODULE_NAME = 'payment_req';
$TB_NAME     = 'payment_req';

$back_url    = base_url("$MODULE_NAME/$TB_NAME");
$batch_url   = base_url("$MODULE_NAME/$TB_NAME/batch_execute");

echo AntiForgeryToken();

$JS_CONSTANTS = json_encode([
    'REQ_FULL_SALARY' => 1, 'REQ_PART_SALARY' => 2, 'REQ_LUMP_SUM' => 3,
    'WALLET_SALARY' => 1, 'WALLET_DUES' => 2, 'WALLET_BOTH' => 3,
    'CALC_PERCENT' => 1, 'CALC_FIXED' => 2,
]);

if (!isset($req_type_cons))    $req_type_cons    = [];
if (!isset($wallet_type_cons)) $wallet_type_cons = [];
if (!isset($calc_method_cons)) $calc_method_cons = [];
if (!isset($emp_no_cons))      $emp_no_cons      = [];

$grp_tpl_req = '<option value="">— اختر —</option>';
foreach ($req_type_cons as $row) $grp_tpl_req .= '<option value="'.$row['CON_NO'].'">'.$row['CON_NAME'].'</option>';
$grp_tpl_wallet = '<option value="">— اختر —</option>';
foreach ($wallet_type_cons as $row) $grp_tpl_wallet .= '<option value="'.$row['CON_NO'].'">'.$row['CON_NAME'].'</option>';
$grp_tpl_calc = '<option value="">— اختر —</option>';
foreach ($calc_method_cons as $row) $grp_tpl_calc .= '<option value="'.$row['CON_NO'].'">'.$row['CON_NAME'].'</option>';
$grp_tpl_emp = '';
foreach ($emp_no_cons as $row) $grp_tpl_emp .= '<option value="'.$row['EMP_NO'].'">'.$row['EMP_NO'].' - '.$row['EMP_NAME'].'</option>';
?>

<style>
:root{--c-blue:#2563eb;--c-purple:#7c3aed;--c-green:#059669;--c-red:#dc2626;--c-amber:#d97706;--c-slate:#64748b;--r:14px;--sh:0 4px 24px rgba(15,23,42,.07)}
.pr2-head{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;margin-top:1.25rem;margin-bottom:1.25rem}
.pr2-head h1{font-size:1.3rem;font-weight:800;color:#1e293b;margin:0;display:flex;align-items:center;gap:.6rem}
.pr2-head .hico{width:38px;height:38px;border-radius:11px;background:linear-gradient(135deg,var(--c-green),#10b981);color:#fff;display:flex;align-items:center;justify-content:center;font-size:.95rem}
.pr2-head .actions{display:flex;gap:.5rem;flex-wrap:wrap}
.pr2-head .actions .btn{border-radius:10px;font-weight:700;font-size:.82rem;padding:.4rem 1rem}
.pr-panel{background:#fff;border:1px solid #e2e8f0;border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;margin-bottom:1.25rem}
.pr-panel-hd{display:flex;align-items:center;justify-content:space-between;padding:.75rem 1.25rem;background:linear-gradient(135deg,#1e293b,#334155);color:#fff}
.pr-panel-hd .t{font-weight:700;font-size:.9rem;display:flex;align-items:center;gap:.4rem}
.pr-panel-bd{padding:1rem 1.25rem}
.pr-panel-bd label{font-weight:600;font-size:.75rem;color:#94a3b8;text-transform:uppercase;letter-spacing:.5px;margin-bottom:.3rem}
.pr-panel-bd .form-control,.pr-panel-bd .form-select{border-radius:10px;border:1px solid #e2e8f0}
.grp-panel{border:2px solid #e2e8f0;border-radius:var(--r);margin-bottom:1rem;overflow:hidden}
.grp-panel-hd{display:flex;align-items:center;justify-content:space-between;padding:.6rem 1rem;background:#f8fafc;border-bottom:1px solid #e2e8f0}
.grp-panel-hd .grp-title{font-weight:700;font-size:.85rem;color:#1e293b;display:flex;align-items:center;gap:.4rem}
.grp-panel-hd .grp-badge{display:inline-flex;align-items:center;gap:.25rem;font-size:.72rem;font-weight:700;padding:.15rem .5rem;border-radius:12px;background:var(--c-blue);color:#fff}
.grp-panel-bd{padding:1rem}
.grp-panel-bd label{font-weight:600;font-size:.75rem;color:#94a3b8;text-transform:uppercase;letter-spacing:.5px;margin-bottom:.3rem}
.grp-panel-bd .form-control,.grp-panel-bd .form-select{border-radius:10px;border:1px solid #e2e8f0}
.grp-remove{border:none;background:none;color:var(--c-red);font-size:.9rem;cursor:pointer;padding:.2rem .4rem;border-radius:6px}
.grp-remove:hover{background:#fee2e2}
.res-ok{color:var(--c-green);font-weight:700}.res-fail{color:var(--c-red);font-weight:700}
.res-partial{color:var(--c-amber);font-weight:700}
.tree-node{padding:.5rem .75rem;border-radius:8px;cursor:pointer;display:inline-flex;align-items:center;gap:.4rem;font-size:.85rem;font-weight:500;color:#334155;transition:.15s;margin:.15rem 0}
.tree-node:hover{background:#f1f5f9}
.pay-type-parent{color:#1e293b;font-weight:700;background:#f8fafc;border:1px solid #e2e8f0}
.pay-type-leaf{color:#334155;background:#fff;border:1px solid #e2e8f0}
.pay-type-leaf:hover{background:#dbeafe;color:#1e40af;border-color:#93c5fd}
.pay-type-leaf.leaf-disabled{opacity:.45;cursor:not-allowed;background:#f8fafc;color:#94a3b8}
.pay-type-leaf.leaf-disabled:hover{background:#f8fafc;color:#94a3b8;border-color:#e2e8f0}
.lt-1{border-right:3px solid var(--c-green)}.lt-2{border-right:3px solid var(--c-red)}
#pay_type_tree ul{margin-top:.3rem;padding-right:.75rem;border-right:2px solid #e2e8f0}
.tree-search-wrap{position:relative;margin-bottom:.75rem}
.tree-search-wrap input{border-radius:10px;border:1px solid #e2e8f0;padding:.5rem 1rem .5rem 2.2rem;font-size:.85rem;width:100%}
.tree-search-wrap .search-ico{position:absolute;left:.75rem;top:50%;transform:translateY(-50%);color:#94a3b8}
.select2-container--default .select2-selection--multiple{border-radius:10px!important;border:1px solid #e2e8f0!important;min-height:38px!important}
.select2-dropdown{border-radius:10px!important;box-shadow:0 8px 40px rgba(15,23,42,.12)!important}
.select2-results__option--highlighted{background:var(--c-blue)!important}
</style>

<div class="pr2-head">
    <h1><span class="hico"><i class="fa fa-users"></i></span> <?= $title ?></h1>
    <div class="actions">
        <button type="button" id="btnExecuteAll" onclick="javascript:executeAll(this);" class="btn btn-primary" disabled><i class="fa fa-rocket"></i> تنفيذ الكل</button>
        <a class="btn btn-secondary" href="<?= $back_url ?>"><i class="fa fa-backward"></i> رجوع</a>
    </div>
</div>

<div class="pr-panel">
    <div class="pr-panel-hd">
        <span class="t"><i class="fa fa-calendar"></i> الشهر المشترك</span>
        <button type="button" onclick="javascript:addGroup();" class="btn btn-sm" style="background:var(--c-green);color:#fff;border-radius:8px;font-weight:700;font-size:.78rem"><i class="fa fa-plus"></i> إضافة مجموعة</button>
    </div>
    <div class="pr-panel-bd">
        <div class="row g-3">
            <div class="form-group col-md-2">
                <label><i class="fa fa-calendar text-success me-1"></i> الشهر</label>
                <input type="text" id="bt_the_month" class="form-control" placeholder="YYYYMM" maxlength="6" value="<?= date('Ym') ?>">
            </div>
        </div>
    </div>
</div>

<div id="groupsContainer"></div>

<div class="pr-panel" id="summaryPanel" style="display:none;">
    <div class="pr-panel-hd"><span class="t"><i class="fa fa-list-alt"></i> ملخص المجموعات</span></div>
    <div class="pr-panel-bd">
        <div class="table-responsive">
            <table class="table mb-0" style="font-size:.82rem">
                <thead><tr style="background:#f8fafc">
                    <th style="font-size:.72rem;color:var(--c-slate);font-weight:700">#</th>
                    <th style="font-size:.72rem;color:var(--c-slate);font-weight:700">نوع الطلب</th>
                    <th style="font-size:.72rem;color:var(--c-slate);font-weight:700">المحفظة</th>
                    <th style="font-size:.72rem;color:var(--c-slate);font-weight:700">الموظفين</th>
                    <th style="font-size:.72rem;color:var(--c-slate);font-weight:700">الحالة</th>
                </tr></thead>
                <tbody id="summaryRows"></tbody>
            </table>
        </div>
    </div>
</div>

<div class="pr-panel" id="resultsPanel" style="display:none;">
    <div class="pr-panel-hd"><span class="t"><i class="fa fa-check-square"></i> نتائج التنفيذ</span></div>
    <div class="pr-panel-bd" id="resultsContent"></div>
</div>

<div class="modal fade" id="payTypeTreeModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius:16px;overflow:hidden;border:none;box-shadow:0 25px 60px rgba(15,23,42,.18)">
            <div class="modal-header py-3 px-4" style="background:linear-gradient(135deg,#1e293b,#334155);border:none">
                <h5 class="modal-title text-white" style="font-size:.95rem;font-weight:700"><i class="fa fa-sitemap me-2"></i>اختر بند المستحقات</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div id="pay_type_tree_loading" class="text-center py-5 text-muted">
                    <i class="fa fa-spinner fa-spin fa-2x"></i><p class="mt-3 mb-0">جاري تحميل البنود...</p>
                </div>
                <div id="pay_type_tree_wrap" style="display:none;">
                    <div class="tree-search-wrap">
                        <i class="fa fa-search search-ico"></i>
                        <input type="text" id="pay_type_tree_search" placeholder="ابحث عن بند...">
                    </div>
                    <div class="overflow-auto p-3" style="max-height:55vh;background:#fafbfc;border-radius:12px;border:1px solid #e2e8f0">
                        <ul id="pay_type_tree" class="tree list-unstyled mb-0"></ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer py-2 px-4" style="background:#f8fafc;border-top:1px solid #e2e8f0">
                <button type="button" class="btn btn-secondary btn-sm" style="border-radius:8px" data-bs-dismiss="modal"><i class="fa fa-times me-1"></i>إغلاق</button>
            </div>
        </div>
    </div>
</div>

<?php ob_start(); ?>
<script type="text/javascript">
    var batchUrl       = <?= json_encode($batch_url) ?>;
    var backUrl        = <?= json_encode($back_url) ?>;
    var payTypeTreeUrl = <?= isset($pay_type_tree_url) ? json_encode($pay_type_tree_url) : '""' ?>;
    var _C = <?= $JS_CONSTANTS ?>;
    var C_REQ_FULL_SALARY = String(_C.REQ_FULL_SALARY);
    var C_REQ_PART_SALARY = String(_C.REQ_PART_SALARY);
    var C_REQ_LUMP_SUM    = String(_C.REQ_LUMP_SUM);
    var C_WALLET_SALARY   = String(_C.WALLET_SALARY);
    var C_WALLET_DUES     = String(_C.WALLET_DUES);
    var C_WALLET_BOTH     = String(_C.WALLET_BOTH);
    var C_CALC_PERCENT    = String(_C.CALC_PERCENT);
    var C_CALC_FIXED      = String(_C.CALC_FIXED);
    var tplReqOpts    = <?= json_encode($grp_tpl_req, JSON_UNESCAPED_UNICODE) ?>;
    var tplWalletOpts = <?= json_encode($grp_tpl_wallet, JSON_UNESCAPED_UNICODE) ?>;
    var tplCalcOpts   = <?= json_encode($grp_tpl_calc, JSON_UNESCAPED_UNICODE) ?>;
    var tplEmpOpts    = <?= json_encode($grp_tpl_emp, JSON_UNESCAPED_UNICODE) ?>;
    var grpCounter    = 0;
    var activeTreeGrp = null;

    jQuery('#bt_the_month').datetimepicker({ format: 'YYYYMM', minViewMode: 'months', pickTime: false });

    function grpEl(idx){ return jQuery('.grp-panel[data-grp="' + idx + '"]'); }

    function buildGroupHtml(idx){
        var h = '';
        h += '<div class="grp-panel" data-grp="' + idx + '">';
        h += '<div class="grp-panel-hd"><span class="grp-title"><i class="fa fa-layer-group"></i> المجموعة <b>' + idx + '</b></span>';
        h += '<div style="display:flex;align-items:center;gap:.5rem"><span class="grp-badge" style="display:none"><i class="fa fa-user"></i> <span class="grp-emp-cnt">0</span></span>';
        h += '<button type="button" class="grp-remove" onclick="javascript:removeGroup(' + idx + ');"><i class="fa fa-trash"></i></button></div></div>';
        h += '<div class="grp-panel-bd"><div class="row g-3">';
        h += '<div class="form-group col-md-3"><label><i class="fa fa-list text-info me-1"></i> نوع الطلب</label><select class="form-select grp-req-type" onchange="grpRules(' + idx + ')">' + tplReqOpts + '</select></div>';
        h += '<div class="form-group col-md-2"><label><i class="fa fa-briefcase text-warning me-1"></i> المحفظة</label><select class="form-select grp-wallet-type" onchange="grpRules(' + idx + ')">' + tplWalletOpts + '</select></div>';
        h += '<div class="form-group col-md-2 grp-f-calc" style="display:none;"><label><i class="fa fa-calculator me-1" style="color:var(--c-purple)"></i> الاحتساب</label><select class="form-select grp-calc-method" onchange="grpRules(' + idx + ')">' + tplCalcOpts + '</select></div>';
        h += '<div class="form-group col-md-2 grp-f-percent" style="display:none;"><label><i class="fa fa-percent text-success me-1"></i> النسبة</label><div class="input-group"><input type="number" step="0.01" min="0" max="100" class="form-control grp-percent"><span class="input-group-text" style="border-radius:0 10px 10px 0">%</span></div></div>';
        h += '<div class="form-group col-md-2 grp-f-amount" style="display:none;"><label><i class="fa fa-money text-primary me-1"></i> المبلغ</label><input type="number" step="0.01" min="0" class="form-control grp-fixed-amount" placeholder="0.00"></div>';
        h += '<div class="form-group col-md-4 grp-f-paytype" style="display:none;"><label><i class="fa fa-sitemap text-info me-1"></i> بند المستحقات</label><div class="input-group"><input type="hidden" class="grp-pay-type"><input type="text" class="form-control bg-white grp-pay-type-display" readonly placeholder="اختر..."><button type="button" class="btn btn-outline-primary" onclick="javascript:openTree(' + idx + ');" style="border-radius:0 10px 10px 0"><i class="fa fa-sitemap"></i></button></div></div>';
        h += '<div class="form-group col-md-3"><label><i class="fa fa-sticky-note-o text-muted me-1"></i> ملاحظات</label><input type="text" class="form-control grp-note" placeholder="اختياري..."></div>';
        h += '</div><div class="row g-3" style="margin-top:.5rem"><div class="form-group col-md-12"><label><i class="fa fa-users text-primary me-1"></i> الموظفين</label>';
        h += '<select class="form-control grp-employees sel2init" multiple>' + tplEmpOpts + '</select>';
        h += '<div style="margin-top:.5rem;display:flex;gap:.4rem"><button type="button" class="btn btn-outline-secondary btn-sm" style="border-radius:8px;font-size:.75rem" onclick="javascript:grpSelectAll(' + idx + ')"><i class="fa fa-check-square-o"></i> تحديد الكل</button>';
        h += '<button type="button" class="btn btn-outline-secondary btn-sm" style="border-radius:8px;font-size:.75rem" onclick="javascript:grpDeselectAll(' + idx + ')"><i class="fa fa-square-o"></i> إلغاء</button></div>';
        h += '</div></div></div></div>';
        return h;
    }

    function addGroup(){
        grpCounter++;
        var idx = grpCounter;
        jQuery('#groupsContainer').append(buildGroupHtml(idx));
        grpEl(idx).find('.sel2init').select2();
        grpEl(idx).find('.sel2init').on('change', function(){ grpEmpChanged(idx); });
        updateSummary();
    }

    function removeGroup(idx){
        if(!confirm('حذف المجموعة؟')) return;
        grpEl(idx).remove();
        updateSummary();
    }

    function grpRules(idx){
        var p  = grpEl(idx);
        var rt = p.find('.grp-req-type').val();
        var wt = p.find('.grp-wallet-type').val();
        var cm = p.find('.grp-calc-method').val();
        p.find('.grp-f-calc, .grp-f-percent, .grp-f-amount, .grp-f-paytype').hide();
        if(rt == C_REQ_FULL_SALARY){ p.find('.grp-wallet-type').val(C_WALLET_SALARY); }
        else if(rt == C_REQ_PART_SALARY){
            p.find('.grp-wallet-type').val(C_WALLET_SALARY);
            p.find('.grp-f-calc').show();
            if(cm == C_CALC_PERCENT) p.find('.grp-f-percent').show();
            else if(cm == C_CALC_FIXED) p.find('.grp-f-amount').show();
        }
        else if(rt == C_REQ_LUMP_SUM){
            p.find('.grp-f-amount').show();
            wt = p.find('.grp-wallet-type').val();
            if(wt == C_WALLET_DUES || wt == C_WALLET_BOTH) p.find('.grp-f-paytype').show();
        }
        updateSummary();
    }

    function grpEmpChanged(idx){
        var p = grpEl(idx);
        var sel = p.find('.grp-employees').val();
        var c = sel ? sel.length : 0;
        if(c > 0){ p.find('.grp-badge').show(); p.find('.grp-emp-cnt').text(c); }
        else { p.find('.grp-badge').hide(); }
        updateSummary();
    }

    function grpSelectAll(idx){
        var p = grpEl(idx), all = [];
        p.find('.grp-employees option').each(function(){ all.push(jQuery(this).val()); });
        p.find('.grp-employees').val(all).trigger('change');
    }

    function grpDeselectAll(idx){
        grpEl(idx).find('.grp-employees').val([]).trigger('change');
    }

    function updateSummary(){
        var html = '', num = 0, hasValid = false;
        jQuery('.grp-panel').each(function(){
            num++;
            var p = jQuery(this);
            var rt = p.find('.grp-req-type').val();
            var wt = p.find('.grp-wallet-type').val();
            var emp = p.find('.grp-employees').val();
            var cnt = emp ? emp.length : 0;
            var valid = (rt && wt && cnt > 0);
            if(valid) hasValid = true;
            html += '<tr><td>' + num + '</td>';
            html += '<td>' + (rt ? p.find('.grp-req-type option:selected').text() : '—') + '</td>';
            html += '<td>' + (wt ? p.find('.grp-wallet-type option:selected').text() : '—') + '</td>';
            html += '<td><b>' + cnt + '</b></td>';
            html += '<td>' + (valid ? '<span class="res-ok"><i class="fa fa-check-circle"></i> جاهز</span>' : '<span style="color:#94a3b8"><i class="fa fa-clock-o"></i> غير مكتمل</span>') + '</td></tr>';
        });
        if(num > 0){ jQuery('#summaryRows').html(html); jQuery('#summaryPanel').show(); }
        else { jQuery('#summaryPanel').hide(); }
        jQuery('#btnExecuteAll').prop('disabled', !hasValid);
    }

    function executeAll(obj){
        if(isDoubleClicked(jQuery(obj))) return;
        var theMonth = jQuery('#bt_the_month').val();
        if(!theMonth){ danger_msg('تحذير','الشهر مطلوب'); return; }
        var batches = [];
        jQuery('.grp-panel').each(function(i){
            var p = jQuery(this);
            var rt = p.find('.grp-req-type').val();
            var wt = p.find('.grp-wallet-type').val();
            var emp = p.find('.grp-employees').val();
            if(!rt || !wt || !emp || emp.length === 0) return;
            batches.push({
                num: i+1, batch_type: 'by_list', the_month: theMonth,
                req_type: rt, wallet_type: wt,
                calc_method: p.find('.grp-calc-method').val() || '',
                percent_val: p.find('.grp-percent').val() || '',
                fixed_amount: p.find('.grp-fixed-amount').val() || '',
                pay_type: p.find('.grp-pay-type').val() || '',
                emp_list: emp.join(','),
                note: p.find('.grp-note').val() || 'دفعة جماعية',
                emp_count: emp.length
            });
        });
        if(batches.length === 0){ danger_msg('تحذير','لا توجد مجموعات جاهزة'); return; }
        if(!confirm('سيتم تنفيذ ' + batches.length + ' مجموعة. متابعة؟')) return;
        jQuery('#btnExecuteAll').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> جاري التنفيذ...');
        jQuery('#resultsPanel').show();
        jQuery('#resultsContent').html('<div class="text-center" style="padding:2rem"><i class="fa fa-spinner fa-spin fa-2x"></i><p style="margin-top:.5rem;color:#94a3b8">جاري تنفيذ المجموعات...</p></div>');
        var total = batches.length, done = 0, results = [];
        for(var i = 0; i < batches.length; i++){
            (function(batch){
                get_data(batchUrl, batch, function(resp){
                    var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
                    results.push({ num: batch.num, ok: j && j.ok, success: j?(j.success||0):0, fail: j?(j.fail||0):0, batch_id: j?(j.batch_id||''):'', msg: j?(j.msg||''):'فشل', emp_count: batch.emp_count });
                    done++;
                    if(done >= total) renderResults(results);
                });
            })(batches[i]);
        }
    }

    function renderResults(results){
        results.sort(function(a,b){ return a.num - b.num; });
        var html = '<div class="table-responsive"><table class="table mb-0" style="font-size:.82rem">';
        html += '<thead><tr style="background:#f8fafc"><th>#</th><th>Batch ID</th><th>الموظفين</th><th>نجح</th><th>فشل</th><th>الحالة</th></tr></thead><tbody>';
        var totalS = 0, totalF = 0;
        for(var i = 0; i < results.length; i++){
            var r = results[i]; totalS += r.success; totalF += r.fail;
            html += '<tr><td>' + r.num + '</td><td>' + (r.batch_id||'—') + '</td><td>' + r.emp_count + '</td>';
            html += '<td><span class="res-ok">' + r.success + '</span></td><td><span class="res-fail">' + r.fail + '</span></td><td>';
            if(r.ok && r.fail==0) html += '<span class="res-ok"><i class="fa fa-check-circle"></i> تم</span>';
            else if(r.ok) html += '<span class="res-partial"><i class="fa fa-exclamation-triangle"></i> جزئي</span>';
            else html += '<span class="res-fail"><i class="fa fa-times-circle"></i> ' + r.msg + '</span>';
            html += '</td></tr>';
        }
        html += '</tbody></table></div>';
        html += '<div style="margin-top:1rem;display:flex;gap:.75rem">';
        html += '<span style="background:#d1fae5;color:#065f46;padding:.4rem 1rem;border-radius:10px;font-weight:700"><i class="fa fa-check"></i> نجح: ' + totalS + '</span>';
        if(totalF > 0) html += '<span style="background:#fee2e2;color:#991b1b;padding:.4rem 1rem;border-radius:10px;font-weight:700"><i class="fa fa-times"></i> فشل: ' + totalF + '</span>';
        html += '</div>';
        jQuery('#resultsContent').html(html);
        jQuery('#btnExecuteAll').html('<i class="fa fa-check"></i> تم التنفيذ');
        if(totalS > 0) success_msg('رسالة', 'تم تنفيذ ' + totalS + ' طلب بنجاح');
        if(totalF > 0) danger_msg('تحذير', 'فشل ' + totalF + ' طلب');
    }

    var payTypeTreeData = null;

    function openTree(idx){
        activeTreeGrp = idx;
        loadPayTypeTree();
        jQuery('#payTypeTreeModal').modal('show');
        jQuery('#pay_type_tree_search').val('');
        setTimeout(function(){ jQuery('#pay_type_tree_search').focus(); }, 400);
    }

    function buildPayTypeTreeHtml(nodes){
        if(!nodes || nodes.length === 0) return '';
        var html = '';
        for(var i = 0; i < nodes.length; i++){
            var n = nodes[i], hasKids = n.children && n.children.length > 0;
            var lt = (n.attributes && n.attributes.lineType) ? n.attributes.lineType : 1;
            var cls = 'lt-' + lt;
            if(hasKids){
                html += '<li class="parent_li" data-id="' + n.id + '"><span class="tree-node ' + cls + ' pay-type-parent"><i class="fa fa-plus tree-icon"></i> ' + (n.text||'') + '</span>';
                html += '<ul class="list-unstyled" style="display:none;">' + buildPayTypeTreeHtml(n.children) + '</ul></li>';
            } else {
                html += '<li data-id="' + n.id + '"><span class="tree-node ' + cls + ' pay-type-leaf' + (lt==2?'':' leaf-disabled') + '"><i class="fa tree-icon" style="display:inline-block;width:16px;"></i> ' + (n.text||'') + '</span></li>';
            }
        }
        return html;
    }

    function loadPayTypeTree(){
        var ld = jQuery('#pay_type_tree_loading'), wr = jQuery('#pay_type_tree_wrap'), tr = jQuery('#pay_type_tree');
        if(payTypeTreeData){ ld.hide(); tr.html(buildPayTypeTreeHtml(payTypeTreeData)); wr.show(); bindTreeEvents(); return; }
        if(!payTypeTreeUrl) return;
        ld.show(); wr.hide(); tr.empty();
        jQuery.getJSON(payTypeTreeUrl).done(function(data){
            var p = data;
            if(typeof data==='string'){ try{p=JSON.parse(data);}catch(e){p=[];} }
            if(p && !Array.isArray(p)){ p = Array.isArray(p.data)?p.data : Array.isArray(p.children)?p.children : []; }
            payTypeTreeData = Array.isArray(p) ? p : [];
            ld.hide(); tr.html(buildPayTypeTreeHtml(payTypeTreeData)); wr.show(); bindTreeEvents();
        }).fail(function(){ ld.html('<span class="text-danger">فشل تحميل الشجرة</span>'); });
    }

    function bindTreeEvents(){
        jQuery('#pay_type_tree').off('click','.pay-type-parent').on('click','.pay-type-parent',function(e){
            e.stopPropagation();
            var ul=jQuery(this).closest('li').children('ul'), ic=jQuery(this).find('.tree-icon');
            if(ul.is(':visible')){ul.slideUp(200);ic.removeClass('fa-minus').addClass('fa-plus');}
            else{ul.slideDown(200);ic.removeClass('fa-plus').addClass('fa-minus');}
        });
        jQuery('#pay_type_tree').off('click','.pay-type-leaf').on('click','.pay-type-leaf',function(e){
            e.stopPropagation();
            if(jQuery(this).hasClass('leaf-disabled')) return;
            var li=jQuery(this).closest('li'), id=li.data('id'), text=jQuery(this).text().trim();
            if(id && activeTreeGrp){
                var p = grpEl(activeTreeGrp);
                p.find('.grp-pay-type').val(id);
                p.find('.grp-pay-type-display').val(text);
                jQuery('#payTypeTreeModal').modal('hide');
                updateSummary();
            }
        });
        jQuery('#pay_type_tree_search').off('input').on('input',function(){
            var q=jQuery(this).val().trim().toLowerCase();
            if(!q){ jQuery('#pay_type_tree li').show(); jQuery('#pay_type_tree ul').hide(); jQuery('#pay_type_tree .tree-icon').removeClass('fa-minus').addClass('fa-plus'); return; }
            jQuery('#pay_type_tree li').each(function(){
                var txt=jQuery(this).children('.tree-node').text().toLowerCase();
                var m = txt.indexOf(q)>-1;
                var cm = jQuery(this).find('.tree-node').filter(function(){return jQuery(this).text().toLowerCase().indexOf(q)>-1;}).length>0;
                if(m||cm){jQuery(this).show().parents('li').show().children('ul').show(); jQuery(this).parents('li').children('.pay-type-parent').find('.tree-icon').removeClass('fa-plus').addClass('fa-minus');}
                else{jQuery(this).hide();}
            });
        });
    }

    if(typeof initFunctions === 'function') initFunctions();
    addGroup();
</script>
<?php
$scripts = ob_get_clean();
sec_scripts($scripts);
?>
