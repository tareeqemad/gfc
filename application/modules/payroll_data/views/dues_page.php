<?php
/**
 * Salary Dues - Page (Improved UI)
 * - Employee summary row (once per employee)
 * - Progress bar + human hint
 * - No repeated totals in detail rows
 * - Detail "employee" cell becomes "payment + serial" (no name repetition)
 * - Balance coloring
 * - Cancelled row soft pink background
 */

$MODULE_NAME = 'payroll_data';
$TB_NAME     = 'dues';
$count       = $offset;

$delete_url  = base_url("$MODULE_NAME/$TB_NAME/delete");
?>

<style>
    .table td, .table th { vertical-align: middle; }
    .table-info td { padding-top: .55rem; padding-bottom: .55rem; }
    .badge { font-weight: 600; }
    .summary-name { font-weight: 700; }
    /* المتبقي: لون واضح بدون أصفر قوي */
    .balance-remaining { font-weight: 700; color: #0d6efd !important; }
    .balance-remaining.text-success { color: #198754 !important; }
    .balance-remaining.text-danger { color: #dc3545 !important; }
</style>

<div class="table-responsive">
    <table class="table table-bordered" id="page_tb" data-container="container">
        <thead class="table-light">
        <tr>
            <th class="text-center" style="width:50px;">#</th>
            <th>الموظف</th>
            <th>الفرع</th>
            <th>الشهر</th>
            <th>نوع الدفع</th>
            <th class="text-center">إضافة/خصم</th>
            <th class="text-end">المبلغ</th>
            <th class="text-end">إجمالي الإضافات</th>
            <th class="text-end">إجمالي الخصومات</th>
            <th class="text-end">صافي الحركات</th>
            <th class="text-end">إجمالي الحركات</th>
            <th class="text-end">المتبقي</th>
            <th class="text-center">الحالة</th>
            <?php if (HaveAccess($delete_url)): ?>
                <th class="text-center" style="width:80px;">الإجراءات</th>
            <?php endif; ?>
        </tr>
        </thead>

        <tbody>
        <?php $colspan = HaveAccess($delete_url) ? 14 : 13; ?>

        <?php if ($page > 1): ?>
            <tr>
                <td colspan="<?= $colspan ?>" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>

        <?php
        // Group by EMP_NO (IMPORTANT: data should be ordered by EMP_NO)
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
            <tr>
                <td colspan="<?= $colspan ?>" class="text-center text-muted p-4">لا توجد بيانات</td>
            </tr>
        <?php endif; ?>

        <?php foreach ($grouped_by_emp as $emp_group): ?>
            <?php
            $first_row = $emp_group['rows'][0];
            // قراءة القيم (مع دعم اختلاف حالة المفاتيح من Oracle)
            $emp_base_due  = (float)($first_row['TOTAL_BASE_DUE'] ?? $first_row['total_base_due'] ?? 0);
            $emp_total_add = (float)($first_row['TOTAL_ADD'] ?? $first_row['total_add'] ?? 0);
            $emp_total_ded = (float)($first_row['TOTAL_DED'] ?? $first_row['total_ded'] ?? 0);
            // إذا الإجراء لم يُرجع الإضافات/الخصومات في الصف الأول، نحسب من تفاصيل الصفوف
            if (($emp_total_add == 0 && $emp_total_ded == 0) && count($emp_group['rows']) > 0) {
                foreach ($emp_group['rows'] as $r) {
                    $amt = (float)($r['PAY'] ?? $r['pay'] ?? 0);
                    $lt  = $r['LINE_TYPE_NAME'] ?? $r['line_type_name'] ?? '';
                    if ($lt === 'ADD') $emp_total_add += $amt; else $emp_total_ded += $amt;
                }
            }
            $emp_net_movements   = (float)($first_row['TOTAL_NET_MOVEMENTS'] ?? $first_row['total_net_movements'] ?? ($emp_total_add - $emp_total_ded));
            $emp_gross_movements = (float)($first_row['TOTAL_GROSS_MOVEMENTS'] ?? $first_row['total_gross_movements'] ?? ($emp_total_add + $emp_total_ded));
            // تجاهل TOTAL_BALANCE من الباكند — نحسب محلياً فقط:
            $emp_total_entitled = $emp_base_due + $emp_total_add;   // "من …" في نص تم سداد
            $emp_total_balance  = $emp_total_entitled - $emp_total_ded; // المتبقي

            // Balance display + color (واضح بدون أصفر مبالغ فيه)
            $emp_balance_display = n_format(abs($emp_total_balance));
            if ($emp_total_balance > 0) {
                $emp_balance_class = 'balance-remaining'; /* أزرق بدل أصفر */
                $emp_balance_icon  = '<i class="fa fa-arrow-up"></i>';
                $emp_balance_title = 'متبقي';
            } elseif ($emp_total_balance < 0) {
                $emp_balance_class = 'balance-remaining text-danger';
                $emp_balance_icon  = '<i class="fa fa-arrow-down"></i>';
                $emp_balance_title = 'زيادة دفع';
            } else {
                $emp_balance_class = 'balance-remaining text-success';
                $emp_balance_icon  = '<i class="fa fa-check-circle"></i>';
                $emp_balance_title = 'مغلق';
            }

            // Progress percent
            $pct = 0;
            if ($emp_total_entitled > 0) {
                $pct = round(($emp_total_ded / $emp_total_entitled) * 100, 1);
                if ($pct > 100) $pct = 100;
                if ($pct < 0) $pct = 0;
            }

            $hint = ($emp_total_entitled > 0)
                    ? 'تم سداد <strong>' . $pct . '%</strong> من المستحقات'
                    : 'لا يوجد مستحقات';
            ?>

            <!-- Summary Row -->
            <tr class="table-info" style="background-color:#e7f3ff; --bs-table-bg:#e7f3ff;">
                <td colspan="7">
                    <div class="d-flex align-items-start justify-content-between gap-2">
                        <div>
                            <div class="summary-name">
                                <i class="fa fa-user text-primary me-1"></i>
                                <?= $first_row['EMP_NO'] ?> - <?= $first_row['EMP_NO_NAME'] ?>
                                <small class="text-muted ms-2"><?= $first_row['BRANCH_NAME'] ?? '' ?></small>
                            </div>

                            <div class="progress mt-1" style="height:6px; opacity:.85;">
                                <div class="progress-bar" role="progressbar" style="width:<?= $pct ?>%"></div>
                            </div>

                            <small class="text-muted d-block mt-1">
                                <?= $hint ?> — <?= n_format($emp_total_ded) ?> من <?= n_format($emp_total_entitled) ?>
                            </small>
                        </div>
                    </div>
                </td>

                <td class="text-end">
            <span class="text-success">
              <i class="fa fa-plus-circle"></i> <strong><?= n_format($emp_total_add) ?></strong>
            </span>
                </td>

                <td class="text-end">
            <span class="text-danger">
              <i class="fa fa-minus-circle"></i> <strong><?= n_format($emp_total_ded) ?></strong>
            </span>
                </td>

                <td class="text-end">
            <?php
            $netClass = ($emp_net_movements >= 0) ? 'text-success' : 'text-danger';
            $netIcon  = ($emp_net_movements >= 0) ? '<i class="fa fa-arrow-up"></i>' : '<i class="fa fa-arrow-down"></i>';
            ?>
            <span class="<?= $netClass ?>">
              <?= $netIcon ?> <strong><?= n_format($emp_net_movements) ?></strong>
            </span>
                </td>

                <td class="text-end">
            <span class="text-muted">
              <i class="fa fa-exchange"></i> <strong><?= n_format($emp_gross_movements) ?></strong>
            </span>
                </td>

                <td class="text-end" title="<?= $emp_balance_title ?>">
            <span class="<?= $emp_balance_class ?>">
              <?= $emp_balance_icon ?> <strong><?= $emp_balance_display ?></strong>
            </span>
                </td>

                <td class="text-center"> </td>
                <?php if (HaveAccess($delete_url)): ?>
                    <td class="text-center"> </td>
                <?php endif; ?>
            </tr>

            <!-- Detail Rows -->
            <?php foreach ($emp_group['rows'] as $row): ?>
                <?php
                $is_active = (!isset($row['STATUS']) || $row['STATUS'] == 1);

                $status_badge = $is_active
                        ? '<span class="badge bg-success"><i class="fa fa-check-circle"></i> فعال</span>'
                        : '<span class="badge bg-secondary"><i class="fa fa-ban"></i> ملغي</span>';

                // Line type badge (ADD / DED)
                $line_type_name = $row['LINE_TYPE_NAME'] ?? '';
                if ($line_type_name == 'ADD') {
                    $lt_badge = '<span class="badge bg-success"><i class="fa fa-plus-circle"></i> إضافة</span>';
                } else {
                    $lt_badge = '<span class="badge bg-danger"><i class="fa fa-minus-circle"></i> خصم</span>';
                }

                // Month display from YYYYMM
                $the_month_display = '';
                if (!empty($row['THE_MONTH']) && strlen($row['THE_MONTH']) == 6) {
                    $year = substr($row['THE_MONTH'], 0, 4);
                    $month_num = (int)substr($row['THE_MONTH'], 4, 2);
                    $the_month_display = months($month_num) . ' ' . $year;
                } else {
                    $the_month_display = $row['THE_MONTH'] ?? '';
                }

                $pay = (isset($row['PAY']) && $row['PAY'] !== '') ? n_format((float)$row['PAY']) : '';

                $rowStyle = $is_active ? '' : 'background-color:#ffe1e6;';
                ?>

                <tr id="tr_<?= $row['SERIAL'] ?>"
                    ondblclick="javascript:get_to_link('<?= base_url("$MODULE_NAME/$TB_NAME/get/{$row['SERIAL']}") ?>');"
                    title="<?= $row['EMP_NO_NAME'] ?? '' ?>"
                    style="cursor:pointer; <?= $rowStyle ?>">

                    <td class="text-center"><?= $count ?></td>

                    <!-- بدل تكرار اسم الموظف: دفعة + رقمها -->
                    <td>
                        <div class="d-flex align-items-center">
                            <i class="fa fa-receipt text-secondary me-2"></i>
                            <div>
                                <strong class="text-secondary">دفعة</strong><br>
                                <small class="text-muted">#<?= $row['SERIAL'] ?></small>
                            </div>
                        </div>
                    </td>

                    <td><i class="fa fa-building text-info me-1"></i><?= $row['BRANCH_NAME'] ?? '' ?></td>

                    <td>
                        <i class="fa fa-calendar text-success me-1"></i>
                        <span title="<?= $row['THE_MONTH'] ?>"><?= $the_month_display ?></span>
                    </td>

                    <td>
              <span class="badge bg-info">
                <i class="fa fa-tag"></i> <?= $row['PAY_TYPE_NAME'] ?? '' ?>
              </span>
                    </td>

                    <td class="text-center"><?= $lt_badge ?></td>

                    <td class="text-end"><strong class="text-primary"><?= $pay ?></strong></td>

                    <!-- No repeated totals -->
                    <td class="text-end"></td>
                    <td class="text-end"></td>
                    <td class="text-end"></td>
                    <td class="text-end"></td>
                    <td class="text-end"></td>

                    <td class="text-center"><?= $status_badge ?></td>

                    <?php if (HaveAccess($delete_url)): ?>
                        <td class="text-center">
                            <?php if ($is_active): ?>
                                <a href="javascript:;"
                                   class="btn btn-sm btn-outline-danger"
                                   title="إلغاء الدفعة"
                                   onclick="javascript:delete_prototype(this,<?= $row['SERIAL'] ?>);"
                                   data-bs-toggle="tooltip">
                                    <i class="fa fa-ban"></i>
                                </a>
                            <?php else: ?>
                                <span class="text-muted"> </span>
                            <?php endif; ?>
                        </td>
                    <?php endif; ?>

                    <?php $count++; ?>
                </tr>
            <?php endforeach; ?>

        <?php endforeach; ?>
        </tbody>
    </table>
</div>

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
