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
.entitl-section-card { border-radius: 10px; border: none; box-shadow: 0 2px 12px rgba(0,0,0,.08); overflow: hidden; }
.entitl-section-card .card-header { font-weight: 700; font-size: 1rem; padding: 0.65rem 1rem; border-bottom: 1px solid rgba(0,0,0,.06); background: #f8fafc !important; }
.dues48-summary-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 1rem; padding: 1rem; }
.dues48-item { text-align: center; padding: 0.85rem 0.5rem; border-radius: 8px; background: #f8f9fa; }
.dues48-item .dues48-label { font-size: 0.8rem; color: #5a6c7d; margin-bottom: 0.35rem; }
.dues48-item .dues48-value { font-size: 1.15rem; font-weight: 700; }
.dues48-item.due-total { background: #e8f4fd; } .dues48-item.due-total .dues48-value { color: #0d6efd; }
.dues48-item.add { background: #e8f5e9; } .dues48-item.add .dues48-value { color: #198754; }
.dues48-item.ded { background: #ffebee; } .dues48-item.ded .dues48-value { color: #dc3545; }
.dues48-item.plus { background: #f3e5f5; } .dues48-item.plus .dues48-value { color: #6f42c1; }
.dues48-item.remain { background: #e3f2fd; } .dues48-item.remain .dues48-value { color: #0d6efd; font-size: 1.25rem; }
#page_tb_wrap.entitl-section-card .table { margin-bottom: 0; }
#page_tb_wrap.entitl-section-card thead th { font-weight: 700; font-size: 0.9rem; color: #374151; padding: 0.85rem 0.75rem; border-bottom: 2px solid #e5e7eb; background: #f8fafc; }
#page_tb_wrap.entitl-section-card tbody td { padding: 0.7rem 0.75rem; vertical-align: middle; }
#page_tb_wrap.entitl-section-card tbody tr { transition: background-color .15s ease; }
#page_tb_wrap.entitl-section-card tbody tr:hover { background-color: #f0f9ff !important; }
#page_tb_wrap.entitl-section-card tbody tr.entitl-total-row { background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%) !important; font-weight: 700; border-top: 2px solid #3b82f6; }
#page_tb_wrap.entitl-section-card tbody tr.entitl-total-row td { padding: 0.85rem 0.75rem; }
#page_tb_wrap.entitl-section-card .entitl-value-cell { font-weight: 600; color: #0d6efd; }
#page_tb_wrap.entitl-section-card .entitl-print-btn { cursor: pointer; color: #6b7280; padding: 4px 8px; border-radius: 6px; transition: color .15s, background .15s; }
#page_tb_wrap.entitl-section-card .entitl-print-btn:hover { color: #0d6efd; background: #eff6ff; }
.dues48-multi-hint { padding: 0.75rem 1rem; border-radius: 8px; background: #f0f9ff; border: 1px solid #bae6fd; color: #0369a1; }
/* جدول responsive - التمرير الأفقي يعمل داخل الحاوية */
#page_tb_wrap.entitl-section-card { max-width: 100%; min-width: 0; }
#page_tb_wrap.entitl-section-card .card-body { min-width: 0; max-width: 100%; }
#page_tb_wrap.entitl-section-card .entitl-table-scroll { display: block; width: 100%; min-width: 0; overflow-x: auto; overflow-y: visible; -webkit-overflow-scrolling: touch; }
#page_tb_wrap.entitl-section-card .entitl-table-scroll .table { min-width: 880px; width: 100%; table-layout: auto; margin-bottom: 0 !important; }
#page_tb_wrap.entitl-section-card thead th, #page_tb_wrap.entitl-section-card tbody td { white-space: nowrap; }
#page_tb_wrap.entitl-section-card td.entitl-notes-cell { white-space: normal; max-width: 140px; overflow: hidden; text-overflow: ellipsis; }
#page_tb_wrap.entitl-section-card tr.entitl-mov-add { background-color: #e8f5e9 !important; }
#page_tb_wrap.entitl-section-card tr.entitl-mov-add:hover { background-color: #c8e6c9 !important; }
#page_tb_wrap.entitl-section-card tr.entitl-mov-ded { background-color: #ffebee !important; }
#page_tb_wrap.entitl-section-card tr.entitl-mov-ded:hover { background-color: #ffcdd2 !important; }
@media (max-width: 991.98px) {
    #page_tb_wrap.entitl-section-card .entitl-table-scroll .table { min-width: 820px; }
    #page_tb_wrap.entitl-section-card thead th, #page_tb_wrap.entitl-section-card tbody td { padding: 0.5rem 0.4rem; font-size: 0.875rem; }
}
@media (max-width: 575.98px) {
    #page_tb_wrap.entitl-section-card .entitl-table-scroll .table { min-width: 760px; }
    #page_tb_wrap.entitl-section-card thead th, #page_tb_wrap.entitl-section-card tbody td { padding: 0.4rem 0.35rem; font-size: 0.8125rem; }
}
</style>
<?php if (!empty($dues48_multi_emp)): ?>
<div class="card entitl-section-card mb-4">
    <div class="card-body">
        <p class="mb-0 dues48-multi-hint">
            <i class="fa fa-info-circle me-2"></i>
            النتائج تعرض أكثر من موظف. ملخص «المسدد من المستحقات» يظهر عند اختيار موظف واحد فقط في الاستعلام.
        </p>
    </div>
</div>
<?php endif; ?>
<?php if (!empty($dues48_summary)): ?>
<?php
$s = $dues48_summary;
$due48   = (float)($s['DUE_48_TOTAL'] ?? $s['due_48_total'] ?? 0);
$add     = (float)($s['TOTAL_ADD'] ?? $s['total_add'] ?? 0);
$ded     = (float)($s['TOTAL_DED'] ?? $s['total_ded'] ?? 0);
$plusAdd = (float)($s['DUE_PLUS_ADD'] ?? $s['due_plus_add'] ?? 0);
$remain  = (float)($s['REMAINING'] ?? $s['remaining'] ?? 0);
?>
<div class="card entitl-section-card dues48-card mb-4">
    <div class="card-header bg-light d-flex align-items-center">
        <i class="fa fa-pie-chart text-primary me-2"></i>
        <span>المسدد من المستحقات</span>
    </div>
    <div class="dues48-summary-grid">
        <div class="dues48-item due-total">
            <div class="dues48-label">إجمالي المستحقات 48</div>
            <div class="dues48-value"><?= number_format($due48, 2) ?></div>
        </div>
        <div class="dues48-item add">
            <div class="dues48-label">إجمالي الإضافات</div>
            <div class="dues48-value"><?= number_format($add, 2) ?></div>
        </div>
        <div class="dues48-item ded">
            <div class="dues48-label">إجمالي الخصومات</div>
            <div class="dues48-value"><?= number_format($ded, 2) ?></div>
        </div>
        <div class="dues48-item plus">
            <div class="dues48-label">المستحق + الإضافات</div>
            <div class="dues48-value"><?= number_format($plusAdd, 2) ?></div>
        </div>
        <div class="dues48-item remain">
            <div class="dues48-label">المتبقي</div>
            <div class="dues48-value"><?= number_format($remain, 2) ?></div>
        </div>
    </div>
</div>
<?php endif; ?>
<div id="page_tb_wrap" class="card entitl-section-card">
    <div class="card-header bg-light d-flex align-items-center">
        <i class="fa fa-list-alt text-primary me-2"></i>
        <span>كشف البدلات</span>
    </div>
    <div class="card-body p-0">
        <div class="entitl-table-scroll">
            <table class="table table-bordered table-hover" id="page_tb" data-container="container">
                <thead>
                <tr>
                    <th class="text-center" style="width: 50px;">م</th>
                    <th>المقر</th>
                    <th>رقم</th>
                    <th>الموظف</th>
                    <th>البدل</th>
                    <th>البند</th>
                    <th class="text-center">القيمة</th>
                    <th>من شهر</th>
                    <th>الى شهر</th>
                    <th class="text-center">خاضع للضريبة</th>
                    <th>عن شهر</th>
                    <th>ملاحظات</th>
                    <th class="text-center" style="width: 70px;">خيارات</th>
                </tr>
                </thead>
                <tbody>
                <?php if ($page > 1): ?>
                    <tr>
                        <td colspan="13" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
                    </tr>
                <?php endif; ?>
                <?php
                $total_value = 0;
                $row_index = 0;
                $stripes = ['#ffffff', '#f8fafc'];
                foreach ($page_rows as $row) :
                    $total_value = $total_value + $row['VALUE'];
                    $bg = $stripes[$row_index % 2];
                    $row_index++;
                ?>
                <tr style="background-color: <?= $bg ?>;" ondblclick="javascript:show_row_details('<?= $row['EMP_NO'] ?>', '<?= $row['MONTH'] ?>');">
                    <td class="text-center"><?= $count++ ?></td>
                    <td><?= $row['BRAN_NAME'] ?? '' ?></td>
                    <td><?= $row['EMP_NO'] ?? '' ?></td>
                    <td><?= $row['EMP_NAME'] ?? '' ?></td>
                    <td style="<?= get_color_style($row['BADL_TYPE'] ?? 0) ?>"><?= $row['BADL_NAME'] ?? '' ?></td>
                    <td><?= $row['CON_NO_NAME'] ?? '' ?></td>
                    <td class="text-center entitl-value-cell"><?= isset($row['VALUE']) ? number_format($row['VALUE'], 2) : '' ?></td>
                    <td><?= $row['MONTH'] ?? '' ?></td>
                    <td><?= $row['MONTH'] ?? '' ?></td>
                    <td class="text-center"><?= !empty($row['IS_TAXED']) && $row['IS_TAXED'] == 1 ? 'نعم' : 'لا' ?></td>
                    <td><?= $row['MONTH'] ?? '' ?></td>
                    <td class="entitl-notes-cell" title="<?= htmlspecialchars($row['NOTES'] ?? '') ?>"><?= $row['NOTES'] ?? '' ?></td>
                    <td class="text-center">
                        <i class="glyphicon glyphicon-print entitl-print-btn" title="طباعة" onclick="javascript:print_report(<?= (int)($row['EMP_NO'] ?? 0) ?>, '<?= $row['MONTH'] ?? '' ?>', <?= (int)($row['STATUS'] ?? 0) . (int)($row['Q_NO'] ?? 0) . (int)($row['DEGREE'] ?? 0) ?>);"></i>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php
                $first_row = isset($page_rows[0]) ? $page_rows[0] : array();
                foreach ($entitl_mov_add as $r):
                    $count++;
                ?>
                <tr class="entitl-mov-add">
                    <td class="text-center"><?= $count ?></td>
                    <td><?= htmlspecialchars($first_row['BRAN_NAME'] ?? '') ?></td>
                    <td><?= htmlspecialchars($entitl_col($r, 'EMP_NO') ?: ($first_row['EMP_NO'] ?? '')) ?></td>
                    <td><?= htmlspecialchars($first_row['EMP_NAME'] ?? '') ?></td>
                    <td class="text-success fw-bold">إضافة</td>
                    <td><?= htmlspecialchars($entitl_col($r, 'PAY_TYPE_NAME')) ?></td>
                    <td class="text-center entitl-value-cell"><?= number_format((float)$entitl_col($r, 'PAY'), 2) ?></td>
                    <td><?= htmlspecialchars($entitl_col($r, 'THE_MONTH')) ?></td>
                    <td><?= htmlspecialchars($entitl_col($r, 'THE_MONTH')) ?></td>
                    <td class="text-center">—</td>
                    <td><?= htmlspecialchars($entitl_col($r, 'THE_DATE')) ?></td>
                    <td class="entitl-notes-cell"><?= htmlspecialchars($entitl_col($r, 'NOTE')) ?></td>
                    <td class="text-center">—</td>
                </tr>
                <?php endforeach; ?>
                <?php foreach ($entitl_mov_ded as $r):
                    $count++;
                ?>
                <tr class="entitl-mov-ded">
                    <td class="text-center"><?= $count ?></td>
                    <td><?= htmlspecialchars($first_row['BRAN_NAME'] ?? '') ?></td>
                    <td><?= htmlspecialchars($entitl_col($r, 'EMP_NO') ?: ($first_row['EMP_NO'] ?? '')) ?></td>
                    <td><?= htmlspecialchars($first_row['EMP_NAME'] ?? '') ?></td>
                    <td class="text-danger fw-bold">خصم</td>
                    <td><?= htmlspecialchars($entitl_col($r, 'PAY_TYPE_NAME')) ?></td>
                    <td class="text-center entitl-value-cell"><?= number_format((float)$entitl_col($r, 'PAY'), 2) ?></td>
                    <td><?= htmlspecialchars($entitl_col($r, 'THE_MONTH')) ?></td>
                    <td><?= htmlspecialchars($entitl_col($r, 'THE_MONTH')) ?></td>
                    <td class="text-center">—</td>
                    <td><?= htmlspecialchars($entitl_col($r, 'THE_DATE')) ?></td>
                    <td class="entitl-notes-cell"><?= htmlspecialchars($entitl_col($r, 'NOTE')) ?></td>
                    <td class="text-center">—</td>
                </tr>
                <?php endforeach; ?>
                <?php $grand_total = $total_value + $entitl_total_add - $entitl_total_ded; ?>
                <tr class="entitl-total-row">
                    <td colspan="6" class="text-end">الإجمالي</td>
                    <td class="text-center" style="font-size: 1.2rem;"><?= number_format($grand_total, 2) ?></td>
                    <td colspan="6"></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
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