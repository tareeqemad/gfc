<?php
/**
 * Salary Dues - Page (AJAX loaded into #container)
 * جدول مسطّح + شريط إجماليات
 * الـ Modal + showEmpModal موجودين بالـ dues_index.php
 */
$MODULE_NAME = 'payroll_data';
$TB_NAME     = 'dues';
$count       = $offset;
$delete_url  = base_url("$MODULE_NAME/$TB_NAME/delete");
$colspan = HaveAccess($delete_url) ? 10 : 9;
?>

<style>
    .table td, .table th { vertical-align: middle; }
    /* Badges */
    .b-add { background:#dcfce7; color:#166534; font-weight:600; font-size:0.72rem; padding:0.25em 0.55em; border-radius:5px; }
    .b-ded { background:#fee2e2; color:#991b1b; font-weight:600; font-size:0.72rem; padding:0.25em 0.55em; border-radius:5px; }
    .b-type { background:#e0f2fe; color:#075985; font-size:0.73rem; padding:0.25em 0.55em; border-radius:5px; }
    .b-ok { background:#dcfce7; color:#166534; font-size:0.68rem; padding:0.2em 0.4em; border-radius:4px; }
    .b-no { background:#f3f4f6; color:#9ca3af; font-size:0.68rem; padding:0.2em 0.4em; border-radius:4px; }
    .amt { font-weight:700; }
    .amt.add { color:#16a34a; } .amt.ded { color:#dc2626; }
    .emp-link { color: #1e40af; font-weight: 700; cursor: pointer; text-decoration: none; }
    .emp-link:hover { color: #2563eb; text-decoration: underline; }
    tr.cancelled td { opacity: 0.5; text-decoration: line-through; text-decoration-color: #cbd5e1; }
    tr.cancelled td:last-child, tr.cancelled td:nth-last-child(2) { text-decoration: none; }
    /* شريط الإجماليات */
    .totals-bar { display:flex; gap:0; margin-bottom:0.6rem; border-radius:10px; overflow:hidden; border:1px solid #e2e8f0; background:#fff; }
    .totals-item { flex:1; text-align:center; padding:0.6rem 0.4rem; border-left:1px solid #e2e8f0; }
    .totals-item:last-child { border-left:none; }
    .totals-item .t-label { font-size:0.68rem; color:#64748b; margin-bottom:0.15rem; }
    .totals-item .t-label i { margin-left:3px; }
    .totals-item .t-value { font-size:0.95rem; font-weight:800; }
    .totals-item.t-emps .t-value { color:#1e293b; } .totals-item.t-moves .t-value { color:#475569; }
    .totals-item.t-add { background:#f0fdf4; } .totals-item.t-add .t-value { color:#16a34a; }
    .totals-item.t-ded { background:#fef2f2; } .totals-item.t-ded .t-value { color:#dc2626; }
    .totals-item.t-net { background:#1e293b; } .totals-item.t-net .t-label { color:#94a3b8; } .totals-item.t-net .t-value { color:#fff; font-size:1.05rem; }
    @media(max-width:576px) { .totals-bar { flex-wrap:wrap; } .totals-item { flex:none; width:50%; } .totals-item.t-net { width:100%; } }
</style>

<?php if (!is_array($page_rows) || count($page_rows) === 0): ?>
<div class="alert alert-light text-center text-muted py-4">لا توجد بيانات</div>
<?php else: ?>

<?php
$t = (isset($totals) && is_array($totals) && count($totals) > 0) ? $totals[0] : [];
$t_emp_count      = (int)($t['EMP_COUNT'] ?? 0);
$t_active_count   = (int)($t['ACTIVE_COUNT'] ?? 0);
$t_canceled_count = (int)($t['CANCELED_COUNT'] ?? 0);
$t_total_add      = (float)($t['TOTAL_ADD'] ?? 0);
$t_total_ded      = (float)($t['TOTAL_DED'] ?? 0);
$t_net            = (float)($t['NET_TOTAL'] ?? 0);
?>

<div class="totals-bar">
    <div class="totals-item t-emps">
        <div class="t-label"><i class="fa fa-users"></i> الموظفين</div>
        <div class="t-value"><?= $t_emp_count ?></div>
    </div>
    <div class="totals-item t-moves">
        <div class="t-label"><i class="fa fa-exchange"></i> الحركات</div>
        <div class="t-value"><?= $t_active_count ?><?php if ($t_canceled_count > 0): ?> <small class="text-muted" style="font-size:0.65rem">(+<?= $t_canceled_count ?> ملغاة)</small><?php endif; ?></div>
    </div>
    <div class="totals-item t-add">
        <div class="t-label"><i class="fa fa-plus-circle"></i> الإضافات</div>
        <div class="t-value">+ <?= n_format($t_total_add) ?></div>
    </div>
    <div class="totals-item t-ded">
        <div class="t-label"><i class="fa fa-minus-circle"></i> الخصومات</div>
        <div class="t-value">- <?= n_format($t_total_ded) ?></div>
    </div>
    <div class="totals-item t-net">
        <div class="t-label"><i class="fa fa-calculator"></i> الصافي</div>
        <div class="t-value"><?= n_format($t_net) ?></div>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-bordered" id="page_tb">
            <thead class="table-light">
                <tr>
                    <th>م</th>
                    <th>الموظف</th>
                    <th>الفرع</th>
                    <th>الشهر</th>
                    <th>التاريخ</th>
                    <th>نوع الدفع</th>
                    <th>النوع</th>
                    <th>المبلغ</th>
                    <th>الحالة</th>
                    <?php if (HaveAccess($delete_url)): ?>
                    <th></th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
            <?php if ($page > 1): ?>
                <tr><td colspan="<?= $colspan ?>" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td></tr>
            <?php endif; ?>
            <?php foreach ($page_rows as $row): ?>
                <?php
                $is_active = (!isset($row['STATUS']) || $row['STATUS'] == 1);
                $line_type = $row['LINE_TYPE_NAME'] ?? '';
                $lt_badge = ($line_type == 'ADD')
                    ? '<span class="b-add"><i class="fa fa-plus"></i> إضافة</span>'
                    : '<span class="b-ded"><i class="fa fa-minus"></i> خصم</span>';
                $st_badge = $is_active
                    ? '<span class="b-ok"><i class="fa fa-check"></i></span>'
                    : '<span class="b-no"><i class="fa fa-ban"></i></span>';
                $the_month_display = '';
                if (!empty($row['THE_MONTH']) && strlen($row['THE_MONTH']) == 6) {
                    $mn = (int)substr($row['THE_MONTH'], 4, 2);
                    $the_month_display = months($mn) . ' ' . substr($row['THE_MONTH'], 0, 4);
                } else {
                    $the_month_display = $row['THE_MONTH'] ?? '';
                }
                $the_date_display = '—';
                if (!empty($row['THE_DATE'])) {
                    $ts = @strtotime(str_replace('/', '-', $row['THE_DATE']));
                    $the_date_display = $ts ? date('d/m/Y', $ts) : $row['THE_DATE'];
                }
                $pay = (isset($row['PAY']) && $row['PAY'] !== '') ? n_format((float)$row['PAY']) : '';
                $pay_class = ($line_type == 'ADD') ? 'add' : 'ded';
                $row_class = $is_active ? '' : 'cancelled';
                $emp_no   = $row['EMP_NO'] ?? '';
                $emp_name = $row['EMP_NO_NAME'] ?? '';
                $branch   = $row['BRANCH_NAME'] ?? '';
                ?>
                <tr class="<?= $row_class ?>"
                    ondblclick="javascript:get_to_link('<?= base_url("$MODULE_NAME/$TB_NAME/get/{$row['SERIAL']}") ?>');">
                    <td><?= $count ?></td>
                    <td>
                        <a class="emp-link" data-emp="<?= $emp_no ?>"
                           onclick="showEmpModal(<?= $emp_no ?>, '<?= addslashes($emp_name) ?>')">
                            <?= $emp_no ?> - <?= $emp_name ?>
                        </a>
                    </td>
                    <td><?= $branch ?></td>
                    <td><?= $the_month_display ?></td>
                    <td><?= $the_date_display ?></td>
                    <td><span class="b-type"><?= $row['PAY_TYPE_NAME'] ?? '' ?></span></td>
                    <td><?= $lt_badge ?></td>
                    <td><span class="amt <?= $pay_class ?>"><?= $pay ?></span></td>
                    <td><?= $st_badge ?></td>
                    <?php if (HaveAccess($delete_url)): ?>
                    <td>
                        <?php if ($is_active): ?>
                        <a href="javascript:;" title="إلغاء"
                           onclick="javascript:delete_prototype(this,<?= $row['SERIAL'] ?>);">
                            <i class="glyphicon glyphicon-trash" style="color: #a43540"></i>
                        </a>
                        <?php endif; ?>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php $count++; ?>
            <?php endforeach; ?>
            </tbody>
        </table>
</div>
<?php endif; ?>

<!-- data for infinite scroll -->
<div id="scroll-meta" data-page="<?= $page ?>" data-has-more="<?= $has_more ?? 0 ?>" data-total="<?= $total_rows ?? 0 ?>" style="display:none;"></div>

<!-- loading indicator -->
<div id="scroll-loading" class="text-center py-3" style="display:none;">
    <i class="fa fa-spinner fa-spin fa-lg text-muted"></i>
    <span class="text-muted ms-2">جاري تحميل المزيد...</span>
</div>

<script>
    if (typeof initFunctions === 'function') initFunctions();
</script>
