<?php
$count = $offset;
function return_color($id){
    if ($id == 3) {
        return 'danger';
    }elseif ($id == 2){
        return  'success';
    }elseif($id == 1){
        return  'primary';
    }
	$report_url = base_url("JsperReport/showreport?sys=hr/salaries");
}

function get_color_style($id) {
    $colors = [
        1 => 'color: #007bff',  // primary
        2 => 'color: #28a745',  // success
        3 => 'color: #dc3545',  // danger
    ];
    return $colors[$id] ?? 'color: #000000'; // default black
}

$report_url = base_url("JsperReport/showreport?sys=hr/salaries");
// تحضير حركات المستحقات للعرض داخل كشف البدلات (عند موظف واحد)
$entitl_movements = isset($dues48_movements) && is_array($dues48_movements) ? $dues48_movements : array();
$entitl_mov_add = array_filter($entitl_movements, function ($r) {
    $t = isset($r['LINE_TYPE_NAME']) ? $r['LINE_TYPE_NAME'] : (isset($r['line_type_name']) ? $r['line_type_name'] : '');
    return strtoupper((string)$t) === 'ADD';
});
$entitl_mov_ded = array_filter($entitl_movements, function ($r) {
    $t = isset($r['LINE_TYPE_NAME']) ? $r['LINE_TYPE_NAME'] : (isset($r['line_type_name']) ? $r['line_type_name'] : '');
    return strtoupper((string)$t) === 'DED';
});
$entitl_col = function ($row, $key) {
    $v = $row[$key] ?? $row[strtolower($key)] ?? '';
    return $v !== null && $v !== '' ? $v : '';
};
$entitl_total_add = 0;
foreach ($entitl_mov_add as $r) { $entitl_total_add += (float)$entitl_col($r, 'PAY'); }
$entitl_total_ded = 0;
foreach ($entitl_mov_ded as $r) { $entitl_total_ded += (float)$entitl_col($r, 'PAY'); }
?>
<style>
/* ---- Wallet card (محفظة المستحقات) ---- */
.dues48-wallet { border-radius:12px; overflow:hidden; border:1px solid #e2e8f0; background:#fff; margin-bottom:1rem; box-shadow:0 2px 12px rgba(0,0,0,.06); }
.dues48-wallet-header { display:flex; align-items:center; gap:.6rem; padding:.75rem 1.1rem; background:linear-gradient(135deg,#1e293b,#334155); color:#fff; }
.dues48-wallet-header .w-icon { width:36px; height:36px; border-radius:10px; background:rgba(255,255,255,.15); display:flex; align-items:center; justify-content:center; font-size:1rem; }
.dues48-wallet-header .w-title { font-weight:700; font-size:.95rem; }
.dues48-wallet-header .w-sub { font-size:.72rem; opacity:.7; }
.dues48-totals-bar { display:flex; gap:0; border-top:1px solid #e2e8f0; }
.dues48-totals-bar .dt-item { flex:1; text-align:center; padding:.65rem .4rem; border-left:1px solid #e2e8f0; }
.dues48-totals-bar .dt-item:last-child { border-left:none; }
.dues48-totals-bar .dt-label { font-size:.68rem; color:#64748b; margin-bottom:.15rem; }
.dues48-totals-bar .dt-label i { margin-left:3px; }
.dues48-totals-bar .dt-value { font-size:.95rem; font-weight:800; direction:ltr; display:inline-block; }
.dues48-totals-bar .dt-due48 { background:#eff6ff; } .dues48-totals-bar .dt-due48 .dt-value { color:#1e40af; }
.dues48-totals-bar .dt-add { background:#f0fdf4; } .dues48-totals-bar .dt-add .dt-value { color:#16a34a; }
.dues48-totals-bar .dt-ded { background:#fef2f2; } .dues48-totals-bar .dt-ded .dt-value { color:#dc2626; }
.dues48-totals-bar .dt-plus { background:#fffbeb; } .dues48-totals-bar .dt-plus .dt-value { color:#d97706; }
.dues48-totals-bar .dt-remain { background:#1e293b; } .dues48-totals-bar .dt-remain .dt-label { color:#94a3b8; } .dues48-totals-bar .dt-remain .dt-value { color:#fff; font-size:1.05rem; }
@media(max-width:576px) { .dues48-totals-bar { flex-wrap:wrap; } .dues48-totals-bar .dt-item { flex:none; width:50%; } .dues48-totals-bar .dt-remain { width:100%; } }
.dues48-multi-hint { padding: 0.75rem 1rem; border-radius: 8px; background: #f0f9ff; border: 1px solid #bae6fd; color: #0369a1; }

/* ---- Table cards ---- */
.entitl-card { border-radius:10px; overflow:hidden; border:1px solid #e2e8f0; background:#fff; margin-bottom:1rem; box-shadow:0 1px 6px rgba(0,0,0,.04); }
.entitl-card-header { display:flex; align-items:center; justify-content:space-between; padding:.6rem 1rem; border-bottom:1px solid #e2e8f0; }
.entitl-card-header .ch-title { font-weight:700; font-size:.95rem; color:#1e293b; display:flex; align-items:center; gap:.4rem; }
.entitl-card-header .ch-title i { font-size:.85rem; }
.entitl-card-header .ch-count { font-size:.8rem; color:#64748b; background:#f1f5f9; padding:.2em .6em; border-radius:10px; }
.entitl-card .table { margin-bottom:0; }
.entitl-card .table thead th { background:#f8fafc; color:#475569; font-size:.85rem; font-weight:600; border-bottom:2px solid #e2e8f0; white-space:nowrap; padding:.55rem .7rem; }
.entitl-card .table tbody td { font-size:.9rem; padding:.5rem .7rem; }
.entitl-card .table tbody tr:hover { background:#f8fafc; }
.entitl-card-footer { display:flex; justify-content:flex-end; gap:1.2rem; padding:.55rem 1rem; background:#f8fafc; border-top:1px solid #e2e8f0; font-size:.88rem; font-weight:700; }
.entitl-card-footer .cf-add { color:#16a34a; } .entitl-card-footer .cf-ded { color:#dc2626; } .entitl-card-footer .cf-total { color:#1e293b; }

/* ---- Badges ---- */
.table td, .table th { vertical-align: middle; }
.b-add { background:#dcfce7; color:#166534; font-weight:600; font-size:0.82rem; padding:0.3em 0.6em; border-radius:5px; }
.b-ded { background:#fee2e2; color:#991b1b; font-weight:600; font-size:0.82rem; padding:0.3em 0.6em; border-radius:5px; }
.b-type { background:#e0f2fe; color:#075985; font-size:0.82rem; padding:0.3em 0.6em; border-radius:5px; }
.b-tax-y { background:#dcfce7; color:#166534; font-size:0.78rem; padding:0.25em 0.5em; border-radius:4px; display:inline-flex; align-items:center; gap:4px; white-space:nowrap; }
.b-tax-n { background:#f3f4f6; color:#9ca3af; font-size:0.78rem; padding:0.25em 0.5em; border-radius:4px; display:inline-flex; align-items:center; gap:4px; white-space:nowrap; }
.amt { font-weight:700; }
.amt.add { color:#16a34a; } .amt.ded { color:#dc2626; }
.emp-link { color: #1e40af; font-weight: 700; cursor: pointer; text-decoration: none; }
.emp-link:hover { color: #2563eb; text-decoration: underline; }
.dues48-totals-bar .dt-rows .dt-value { color:#1e293b; }
tr.entitl-mov-add { background-color: #f0fdf4 !important; }
tr.entitl-mov-add:hover { background-color: #dcfce7 !important; }
tr.entitl-mov-ded { background-color: #fef2f2 !important; }
tr.entitl-mov-ded:hover { background-color: #fee2e2 !important; }
.entitl-notes-cell { white-space: normal; max-width: 180px; overflow: hidden; text-overflow: ellipsis; }
.entitl-print-btn { cursor: pointer; color: #6b7280; padding: 4px 8px; border-radius: 6px; transition: color .15s, background .15s; }
.entitl-print-btn:hover { color: #0d6efd; background: #eff6ff; }
</style>
<?php
$total_value = 0;
foreach ($page_rows as $row) { $total_value += (float)($row['VALUE'] ?? 0); }
$grand_total = $total_value + $entitl_total_add - $entitl_total_ded;
$first_row = isset($page_rows[0]) ? $page_rows[0] : array();

$has_wallet = !empty($dues48_summary);
$is_multi   = !empty($dues48_multi_emp);
if ($has_wallet) {
    $s = $dues48_summary;
    $due48   = (float)($s['DUE_48_TOTAL'] ?? $s['due_48_total'] ?? 0);
    $add     = (float)($s['TOTAL_ADD'] ?? $s['total_add'] ?? 0);
    $ded     = (float)($s['TOTAL_DED'] ?? $s['total_ded'] ?? 0);
    $plusAdd = (float)($s['DUE_PLUS_ADD'] ?? $s['due_plus_add'] ?? 0);
    $remain  = (float)($s['REMAINING'] ?? $s['remaining'] ?? 0);
}
?>

<div class="dues48-wallet">
    <div class="dues48-wallet-header">
        <div class="w-icon"><i class="fa fa-archive"></i></div>
        <div>
            <div class="w-title">ملخص البدلات والمستحقات</div>
            <div class="w-sub"><?= count($page_rows) ?> سجل — إجمالي البدلات <?= number_format($total_value, 2) ?></div>
        </div>
    </div>
    <?php if ($has_wallet): ?>
    <!-- موظف واحد: صف واحد شامل بدون تكرار -->
    <div class="dues48-totals-bar">
        <div class="dt-item dt-rows">
            <div class="dt-label"><i class="fa fa-list"></i> السجلات</div>
            <div class="dt-value"><?= count($page_rows) ?></div>
        </div>
        <div class="dt-item dt-due48">
            <div class="dt-label"><i class="fa fa-database"></i> إجمالي المستحقات</div>
            <div class="dt-value"><?= number_format($due48, 2) ?></div>
        </div>
        <div class="dt-item dt-add">
            <div class="dt-label"><i class="fa fa-plus-circle"></i> الإضافات</div>
            <div class="dt-value">+ <?= number_format($add, 2) ?></div>
        </div>
        <div class="dt-item dt-ded">
            <div class="dt-label"><i class="fa fa-minus-circle"></i> المسدد</div>
            <div class="dt-value">- <?= number_format($ded, 2) ?></div>
        </div>
        <div class="dt-item dt-remain">
            <div class="dt-label"><i class="fa fa-calculator"></i> المتبقي</div>
            <div class="dt-value"><?= number_format($remain, 2) ?></div>
        </div>
    </div>
    <?php else: ?>
    <!-- أكثر من موظف أو بدون محفظة: إجماليات الجدول فقط -->
    <div class="dues48-totals-bar">
        <div class="dt-item dt-rows">
            <div class="dt-label"><i class="fa fa-list"></i> السجلات</div>
            <div class="dt-value"><?= count($page_rows) ?></div>
        </div>
        <div class="dt-item dt-add">
            <div class="dt-label"><i class="fa fa-plus-circle"></i> البدلات</div>
            <div class="dt-value"><?= number_format($total_value, 2) ?></div>
        </div>
        <?php if ($entitl_total_add > 0): ?>
        <div class="dt-item dt-add">
            <div class="dt-label"><i class="fa fa-arrow-up"></i> حركات إضافة</div>
            <div class="dt-value">+ <?= number_format($entitl_total_add, 2) ?></div>
        </div>
        <?php endif; ?>
        <?php if ($entitl_total_ded > 0): ?>
        <div class="dt-item dt-ded">
            <div class="dt-label"><i class="fa fa-arrow-down"></i> حركات خصم</div>
            <div class="dt-value">- <?= number_format($entitl_total_ded, 2) ?></div>
        </div>
        <?php endif; ?>
        <div class="dt-item dt-remain">
            <div class="dt-label"><i class="fa fa-calculator"></i> الإجمالي</div>
            <div class="dt-value"><?= number_format($grand_total, 2) ?></div>
        </div>
    </div>
    <?php if ($is_multi): ?>
    <div style="padding:.5rem 1rem;background:#f8fafc;border-top:1px solid #e2e8f0;font-size:.75rem;color:#64748b">
        <i class="fa fa-info-circle"></i> ملخص المستحقات يظهر عند اختيار موظف واحد فقط
    </div>
    <?php endif; ?>
    <?php endif; ?>
</div>

<!-- جدول 1: البدلات الأساسية -->
<div class="entitl-card">
    <div class="entitl-card-header">
        <div class="ch-title"><i class="fa fa-table" style="color:#3b82f6"></i> كشف البدلات</div>
        <span class="ch-count"><?= count($page_rows) ?> سجل</span>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered" id="page_tb" data-container="container">
            <thead>
            <tr>
                <th style="width:36px">م</th>
                <th>الموظف</th>
                <th>المقر</th>
                <th>الشهر</th>
                <th>البند</th>
                <th>المبلغ</th>
                <th style="width:60px">الضريبة</th>
                <th>ملاحظات</th>
                <th style="width:40px"></th>
            </tr>
            </thead>
            <tbody>
            <?php if ($page > 1): ?>
                <tr><td colspan="9" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td></tr>
            <?php endif; ?>
            <?php foreach ($page_rows as $row):
                $badl_type = (int)($row['BADL_TYPE'] ?? 0);
                $badl_badge = ($badl_type == 1) ? 'b-add' : (($badl_type == 3) ? 'b-ded' : 'b-type');
                $val = isset($row['VALUE']) ? (float)$row['VALUE'] : 0;
                $val_class = ($badl_type == 3) ? 'ded' : 'add';
                $tax_badge = (!empty($row['IS_TAXED']) && $row['IS_TAXED'] == 1)
                    ? '<span class="b-tax-y"><i class="fa fa-check"></i> نعم</span>'
                    : '<span class="b-tax-n"><i class="fa fa-ban"></i> لا</span>';
                $month_display = $row['MONTH'] ?? '';
                if (strlen($month_display) == 6) {
                    $mn = (int)substr($month_display, 4, 2);
                    $month_display = months($mn) . ' ' . substr($month_display, 0, 4);
                }
            ?>
            <tr ondblclick="javascript:show_row_details('<?= $row['EMP_NO'] ?>', '<?= $row['MONTH'] ?>');" style="cursor:pointer">
                <td class="text-center text-muted"><?= $count ?></td>
                <td>
                    <a class="emp-link" onclick="show_row_details('<?= $row['EMP_NO'] ?>','<?= $row['MONTH'] ?>')">
                        <?= $row['EMP_NO'] ?? '' ?>
                    </a>
                    <span class="text-muted d-block" style="font-size:.82rem"><?= $row['EMP_NAME'] ?? '' ?></span>
                </td>
                <td><span style="font-size:.78rem;color:#64748b"><?= $row['BRAN_NAME'] ?? '' ?></span></td>
                <td><?= $month_display ?></td>
                <td><span class="b-type"><?= $row['CON_NO_NAME'] ?? '' ?></span></td>
                <td><span class="amt <?= $val_class ?>"><?= number_format($val, 2) ?></span></td>
                <td class="text-center"><?= $tax_badge ?></td>
                <td class="entitl-notes-cell" title="<?= htmlspecialchars($row['NOTES'] ?? '') ?>"><?= $row['NOTES'] ?? '' ?></td>
                <td class="text-center">
                    <i class="glyphicon glyphicon-print entitl-print-btn" title="طباعة" onclick="event.stopPropagation();print_report(<?= (int)($row['EMP_NO'] ?? 0) ?>, '<?= $row['MONTH'] ?? '' ?>', <?= (int)($row['STATUS'] ?? 0) . (int)($row['Q_NO'] ?? 0) . (int)($row['DEGREE'] ?? 0) ?>);"></i>
                </td>
            </tr>
            <?php $count++; endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="entitl-card-footer">
        <span class="cf-total"><i class="fa fa-calculator"></i> الإجمالي: <?= number_format($total_value, 2) ?></span>
    </div>
</div>

<!-- جدول 2: حركات على المستحقات -->
<?php if (count($entitl_mov_add) > 0 || count($entitl_mov_ded) > 0): ?>
<?php $mov_total_count = count($entitl_mov_add) + count($entitl_mov_ded); ?>
<div class="entitl-card">
    <div class="entitl-card-header">
        <div class="ch-title"><i class="fa fa-exchange" style="color:#8b5cf6"></i> حركات على المستحقات</div>
        <span class="ch-count"><?= $mov_total_count ?> حركة</span>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered" id="entitl_mov_tb">
            <thead>
            <tr>
                <th style="width:36px">م</th>
                <th>الموظف</th>
                <th>الشهر</th>
                <th>التاريخ</th>
                <th>نوع الحركة</th>
                <th>نوع الدفع</th>
                <th>المبلغ</th>
                <th>ملاحظات</th>
            </tr>
            </thead>
            <tbody>
            <?php $mov_count = 1; ?>
            <?php foreach ($entitl_mov_add as $r):
                $mov_month = $entitl_col($r, 'THE_MONTH');
                if (strlen($mov_month) == 6) { $mn = (int)substr($mov_month, 4, 2); $mov_month = months($mn) . ' ' . substr($mov_month, 0, 4); }
                $mov_date = '—';
                if ($entitl_col($r, 'THE_DATE') !== '') { $ts = @strtotime(str_replace('/', '-', $entitl_col($r, 'THE_DATE'))); $mov_date = $ts ? date('d/m/Y', $ts) : $entitl_col($r, 'THE_DATE'); }
            ?>
            <tr class="entitl-mov-add">
                <td class="text-center text-muted"><?= $mov_count ?></td>
                <td>
                    <span class="fw-bold"><?= htmlspecialchars($entitl_col($r, 'EMP_NO') ?: ($first_row['EMP_NO'] ?? '')) ?></span>
                    <span class="text-muted d-block" style="font-size:.82rem"><?= htmlspecialchars($first_row['EMP_NAME'] ?? '') ?></span>
                </td>
                <td><?= $mov_month ?></td>
                <td style="color:#94a3b8;font-size:.75rem"><?= $mov_date ?></td>
                <td><span class="b-add"><i class="fa fa-plus"></i> إضافة</span></td>
                <td><span class="b-type"><?= htmlspecialchars($entitl_col($r, 'PAY_TYPE_NAME')) ?></span></td>
                <td><span class="amt add"><?= number_format((float)$entitl_col($r, 'PAY'), 2) ?></span></td>
                <td class="entitl-notes-cell"><?= htmlspecialchars($entitl_col($r, 'NOTE')) ?></td>
            </tr>
            <?php $mov_count++; endforeach; ?>
            <?php foreach ($entitl_mov_ded as $r):
                $mov_month = $entitl_col($r, 'THE_MONTH');
                if (strlen($mov_month) == 6) { $mn = (int)substr($mov_month, 4, 2); $mov_month = months($mn) . ' ' . substr($mov_month, 0, 4); }
                $mov_date = '—';
                if ($entitl_col($r, 'THE_DATE') !== '') { $ts = @strtotime(str_replace('/', '-', $entitl_col($r, 'THE_DATE'))); $mov_date = $ts ? date('d/m/Y', $ts) : $entitl_col($r, 'THE_DATE'); }
            ?>
            <tr class="entitl-mov-ded">
                <td class="text-center text-muted"><?= $mov_count ?></td>
                <td>
                    <span class="fw-bold"><?= htmlspecialchars($entitl_col($r, 'EMP_NO') ?: ($first_row['EMP_NO'] ?? '')) ?></span>
                    <span class="text-muted d-block" style="font-size:.82rem"><?= htmlspecialchars($first_row['EMP_NAME'] ?? '') ?></span>
                </td>
                <td><?= $mov_month ?></td>
                <td style="color:#94a3b8;font-size:.75rem"><?= $mov_date ?></td>
                <td><span class="b-ded"><i class="fa fa-minus"></i> خصم</span></td>
                <td><span class="b-type"><?= htmlspecialchars($entitl_col($r, 'PAY_TYPE_NAME')) ?></span></td>
                <td><span class="amt ded"><?= number_format((float)$entitl_col($r, 'PAY'), 2) ?></span></td>
                <td class="entitl-notes-cell"><?= htmlspecialchars($entitl_col($r, 'NOTE')) ?></td>
            </tr>
            <?php $mov_count++; endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="entitl-card-footer">
        <?php if ($entitl_total_add > 0): ?>
        <span class="cf-add"><i class="fa fa-plus-circle"></i> إضافات: <?= number_format($entitl_total_add, 2) ?></span>
        <?php endif; ?>
        <?php if ($entitl_total_ded > 0): ?>
        <span class="cf-ded"><i class="fa fa-minus-circle"></i> مسدد من المستحقات: <?= number_format($entitl_total_ded, 2) ?></span>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>
<?php echo $this->pagination->create_links(); ?>
<script>
    if (typeof initFunctions == 'function') {
        initFunctions();
    }
    if (typeof ajax_pager == 'function') {
        ajax_pager();
    }
	
	function print_report(emp_, month_, prv_){
        var rep_url = '<?=$report_url?>&report_type=pdf'+'&report=salary_form&p_emp_id='+emp_+'&p_salary_month='+month_+'&p_prv='+prv_;
        _showReport(rep_url);
    }
</script>