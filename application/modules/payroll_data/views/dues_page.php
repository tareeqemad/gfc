<?php
/**
 * Salary Dues - Page
 * - ملخص كل موظف في كارد (نفس فكرة Entitl_deduct_page)
 * - جدول التفاصيل بأعمدة واضحة فقط (بدون خلايا فارغة للإجماليات)
 */

$MODULE_NAME = 'payroll_data';
$TB_NAME     = 'dues';
$count       = $offset;

$delete_url  = base_url("$MODULE_NAME/$TB_NAME/delete");
?>

<style>
    .table td, .table th { vertical-align: middle; }
    .badge { font-weight: 600; }
    /* كارد ملخص الموظف - ترتيب واضح صف واحد */
    .dues-page-summary-card { border-radius: 10px; border: 1px solid #e2e8f0; box-shadow: 0 2px 8px rgba(0,0,0,.06); overflow: hidden; margin: 0 0.5rem 0.5rem; }
    .dues-page-summary-card .card-header { font-weight: 700; font-size: 0.9rem; padding: 0.55rem 1rem; border-bottom: 1px solid #e2e8f0; background: #f8fafc !important; }
    .dues-page-summary-card .card-header .emp-title { font-weight: 700; color: #1e293b; }
    .dues-page-summary-grid { display: grid; grid-template-columns: repeat(5, minmax(0, 1fr)); gap: 0.6rem; padding: 0.75rem 1rem; }
    @media (max-width: 768px) { .dues-page-summary-grid { grid-template-columns: repeat(2, 1fr); } .dues-page-summary-item.pct { grid-column: span 2; } }
    .dues-page-summary-item { text-align: center; padding: 0.5rem 0.35rem; border-radius: 8px; min-height: 52px; display: flex; flex-direction: column; justify-content: center; }
    .dues-page-summary-item .label { font-size: 0.7rem; color: #64748b; margin-bottom: 0.2rem; line-height: 1.2; }
    .dues-page-summary-item .value { font-size: 0.95rem; font-weight: 700; }
    .dues-page-summary-item.due-total { background: #eff6ff; } .dues-page-summary-item.due-total .value { color: #0d6efd; }
    .dues-page-summary-item.add { background: #ecfdf5; } .dues-page-summary-item.add .value { color: #198754; }
    .dues-page-summary-item.ded { background: #fef2f2; } .dues-page-summary-item.ded .value { color: #dc3545; }
    .dues-page-summary-item.remain { background: #eff6ff; } .dues-page-summary-item.remain .value { color: #0d6efd; font-size: 1.05rem; }
    .dues-page-summary-item.pct { background: #f5f3ff; } .dues-page-summary-item.pct .value { color: #6f42c1; }
    .dues-page-summary-item.pct .progress { height: 4px; border-radius: 4px; margin-top: 0.2rem; background: #e9e5ff; }
    /* صفوف التفاصيل داخل الأكورديون */
    .dues-detail-table tbody tr.detail-row { background: #fafbfc; }
    .dues-detail-table tbody tr.detail-row:hover { background-color: #f0f9ff !important; }
    .dues-detail-table tbody tr.cancelled { background-color: #ffe4e8 !important; }
    /* أكورديون تفاصيل الحركات */
    .dues-details-accordion .accordion-button { font-weight: 600; font-size: 0.9rem; }
    .dues-details-accordion .accordion-button:not(.collapsed) { background: #f0f9ff; color: #0d6efd; }
    .dues-details-accordion .accordion-body { max-height: 400px; overflow-y: auto; }
</style>

<?php
$colspan = HaveAccess($delete_url) ? 9 : 8;

// Group by EMP_NO
$grouped_by_emp = [];
$current_emp = null;
$emp_rows = [];

if (is_array($page_rows)) {
    foreach ($page_rows as $r) {
        $emp_no = $r['EMP_NO'] ?? null;
        if ($emp_no === null) continue;

        if ($current_emp !== null && $current_emp != $emp_no) {
            $grouped_by_emp[] = ['emp' => $current_emp, 'rows' => $emp_rows];
            $emp_rows = [];
        }
        $current_emp = $emp_no;
        $emp_rows[] = $r;
    }
}
if (!empty($emp_rows)) {
    $grouped_by_emp[] = ['emp' => $current_emp, 'rows' => $emp_rows];
}
?>

<?php if (empty($grouped_by_emp)): ?>
<div class="alert alert-light text-center text-muted py-4">لا توجد بيانات</div>
<?php else: ?>
<?php if ($page > 1): ?>
<div id="page-<?= $page ?>" class="page-sector mb-3" data-page="<?= $page ?>"></div>
<?php endif; ?>

<div id="page_tb_wrap" data-container="container">
<?php foreach ($grouped_by_emp as $emp_group): ?>
    <?php
    $first_row = $emp_group['rows'][0];
    $emp_no = $first_row['EMP_NO'] ?? '';
    $emp_name = $first_row['EMP_NO_NAME'] ?? '';
    $emp_branch = $first_row['BRANCH_NAME'] ?? '';
    $emp_base_due  = (float)($first_row['TOTAL_BASE_DUE'] ?? $first_row['total_base_due'] ?? 0);
    $emp_total_add = (float)($first_row['TOTAL_ADD'] ?? $first_row['total_add'] ?? 0);
    $emp_total_ded = (float)($first_row['TOTAL_DED'] ?? $first_row['total_ded'] ?? 0);
    if (($emp_total_add == 0 && $emp_total_ded == 0) && count($emp_group['rows']) > 0) {
        foreach ($emp_group['rows'] as $r) {
            $amt = (float)($r['PAY'] ?? $r['pay'] ?? 0);
            $lt  = $r['LINE_TYPE_NAME'] ?? $r['line_type_name'] ?? '';
            if ($lt === 'ADD') $emp_total_add += $amt; else $emp_total_ded += $amt;
        }
    }
    $emp_total_entitled = $emp_base_due + $emp_total_add;
    $emp_total_balance  = $emp_total_entitled - $emp_total_ded;
    $pct = 0;
    if ($emp_total_entitled > 0) {
        $pct = round(($emp_total_ded / $emp_total_entitled) * 100, 1);
        $pct = max(0, min(100, $pct));
    }
    $accordion_id = 'dues_accordion_' . preg_replace('/[^0-9]/', '_', $emp_no) . '_' . substr(md5(serialize($emp_group['rows'][0])), 0, 8);
    $num_movements = count($emp_group['rows']);
    ?>
    <div class="dues-emp-block mb-4">
        <!-- كارد الموظف: اسم الموظف + ملخص المستحقات -->
        <div class="card dues-page-summary-card">
            <div class="card-header bg-light d-flex align-items-center flex-wrap gap-2">
                <span class="text-muted small">اسم الموظف:</span>
                <strong class="emp-title"><?= $emp_no ?> - <?= $emp_name ?></strong>
                <span class="text-muted small">الفرع: <?= $emp_branch ?></span>
            </div>
            <div class="card-body pt-2 pb-2">
                <div class="dues-page-summary-grid">
                    <div class="dues-page-summary-item due-total">
                        <div class="label">المستحقات الأساسية</div>
                        <div class="value"><?= n_format($emp_base_due) ?></div>
                    </div>
                    <div class="dues-page-summary-item add">
                        <div class="label">إجمالي الإضافات</div>
                        <div class="value"><?= n_format($emp_total_add) ?></div>
                    </div>
                    <div class="dues-page-summary-item ded">
                        <div class="label">إجمالي الخصومات</div>
                        <div class="value"><?= n_format($emp_total_ded) ?></div>
                    </div>
                    <div class="dues-page-summary-item remain">
                        <div class="label">المتبقي</div>
                        <div class="value"><?= n_format($emp_total_balance) ?></div>
                    </div>
                    <div class="dues-page-summary-item pct">
                        <div class="label">نسبة التسديد</div>
                        <div class="value"><?= $pct ?>%</div>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width:<?= $pct ?>%;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- تفاصيل الحركات جوا الكارد -->
            <div class="accordion dues-details-accordion border-0" id="accordion_<?= $emp_no ?>">
                <div class="accordion-item border-0 border-top rounded-0">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed py-2 shadow-none bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#<?= $accordion_id ?>" aria-expanded="false" aria-controls="<?= $accordion_id ?>">
                            <i class="fa fa-list me-2 text-primary"></i>
                            تفاصيل الحركات
                            <span class="badge bg-secondary ms-2"><?= $num_movements ?></span>
                        </button>
                    </h2>
                    <div id="<?= $accordion_id ?>" class="accordion-collapse collapse" data-bs-parent="#accordion_<?= $emp_no ?>">
                        <div class="accordion-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm mb-0 dues-detail-table">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" style="width:42px;">#</th>
                                        <th>الشهر</th>
                                        <th style="width:100px;">تاريخ الحركة</th>
                                        <th>نوع الدفع</th>
                                        <th class="text-center">إضافة/خصم</th>
                                        <th class="text-end" title="مبلغ الدفعة لهذا السطر">المبلغ</th>
                                        <th class="text-center">الحالة</th>
                                        <?php if (HaveAccess($delete_url)): ?>
                                            <th class="text-center" style="width:85px;">الإجراءات</th>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php $row_index = 0; $total_detail_rows = count($emp_group['rows']); ?>
                                <?php foreach ($emp_group['rows'] as $row): ?>
                                    <?php $row_index++; $is_last_row = ($row_index === $total_detail_rows); ?>
                                    <?php
                                    $is_active = (!isset($row['STATUS']) || $row['STATUS'] == 1);
                                    $status_badge = $is_active
                                        ? '<span class="badge bg-success"><i class="fa fa-check-circle"></i> فعال</span>'
                                        : '<span class="badge bg-secondary"><i class="fa fa-ban"></i> ملغي</span>';
                                    $line_type_name = $row['LINE_TYPE_NAME'] ?? '';
                                    $lt_badge = ($line_type_name == 'ADD')
                                        ? '<span class="badge bg-success"><i class="fa fa-plus-circle"></i> إضافة</span>'
                                        : '<span class="badge bg-danger"><i class="fa fa-minus-circle"></i> خصم</span>';
                                    $the_month_display = '';
                                    if (!empty($row['THE_MONTH']) && strlen($row['THE_MONTH']) == 6) {
                                        $month_num = (int)substr($row['THE_MONTH'], 4, 2);
                                        $the_month_display = months($month_num) . ' ' . substr($row['THE_MONTH'], 0, 4);
                                    } else {
                                        $the_month_display = $row['THE_MONTH'] ?? '';
                                    }
                                    $the_date_display = '';
                                    if (!empty($row['THE_DATE'])) {
                                        $ts = @strtotime(str_replace('/', '-', $row['THE_DATE']));
                                        $the_date_display = $ts ? date('d/m/Y', $ts) : $row['THE_DATE'];
                                    } else {
                                        $the_date_display = '—';
                                    }
                                    $pay = (isset($row['PAY']) && $row['PAY'] !== '') ? n_format((float)$row['PAY']) : '';
                                    $row_class = $is_active ? '' : 'cancelled';
                                    ?>
                                    <tr class="detail-row <?= $row_class ?>" id="tr_<?= $row['SERIAL'] ?>"
                                        ondblclick="javascript:get_to_link('<?= base_url("$MODULE_NAME/$TB_NAME/get/{$row['SERIAL']}") ?>');"
                                        title="نقرتان للتعديل" style="cursor:pointer;">
                                        <td class="text-center"><?= $count ?></td>
                                        <td><i class="fa fa-calendar text-success me-1"></i><?= $the_month_display ?></td>
                                        <td><i class="fa fa-calendar-check-o text-muted me-1"></i><?= $the_date_display ?></td>
                                        <td><span class="badge bg-info"><i class="fa fa-tag"></i> <?= $row['PAY_TYPE_NAME'] ?? '' ?></span></td>
                                        <td class="text-center"><?= $lt_badge ?></td>
                                        <td class="text-end"><strong class="text-primary"><?= $pay ?></strong></td>
                                        <td class="text-center"><?= $status_badge ?></td>
                                        <?php if (HaveAccess($delete_url)): ?>
                                        <td class="text-center">
                                            <?php if ($is_active): ?>
                                            <a href="javascript:;" class="btn btn-sm btn-outline-danger" title="إلغاء الدفعة"
                                               onclick="javascript:delete_prototype(this,<?= $row['SERIAL'] ?>);" data-bs-toggle="tooltip">
                                                <i class="fa fa-ban me-1"></i><span class="d-none d-md-inline">إلغاء</span>
                                            </a>
                                            <?php else: ?><span class="text-muted">—</span><?php endif; ?>
                                        </td>
                                        <?php endif; ?>
                                    </tr>
                                    <?php $count++; ?>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>
</div>
<?php endif; ?>

<?php echo $this->pagination->create_links(); ?>

<script>
    if (typeof initFunctions === 'function') initFunctions();
    if (typeof ajax_pager === 'function') ajax_pager();

    function initTooltips() {
        if (typeof bootstrap === 'undefined') return;
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }

    $(function () {
        initTooltips();
    });
</script>
