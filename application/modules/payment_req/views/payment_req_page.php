<?php
$MODULE_NAME = 'payment_req';
$TB_NAME     = 'payment_req';
$count       = $offset;
$vm          = isset($view_mode) ? $view_mode : 'detail';

$get_url     = base_url("$MODULE_NAME/$TB_NAME/get");
$delete_url  = base_url("$MODULE_NAME/$TB_NAME/delete");

$status_map = [
    0 => ['label'=>'مسودة',        'icon'=>'fa-pencil',       'bg'=>'#fef3c7','color'=>'#92400e'],
    1 => ['label'=>'معتمد',        'icon'=>'fa-check',        'bg'=>'#dbeafe','color'=>'#1e40af'],
    2 => ['label'=>'منفّذ للصرف',   'icon'=>'fa-check-circle', 'bg'=>'#d1fae5','color'=>'#065f46'],
    3 => ['label'=>'معتمد جزئي',   'icon'=>'fa-check-circle', 'bg'=>'#e0e7ff','color'=>'#3730a3'],
    4 => ['label'=>'محتسب',         'icon'=>'fa-calculator',   'bg'=>'#ccfbf1','color'=>'#0f766e'],
    9 => ['label'=>'ملغى',         'icon'=>'fa-ban',          'bg'=>'#fee2e2','color'=>'#991b1b'],
];

$has_actions = HaveAccess($delete_url) || HaveAccess(base_url("$MODULE_NAME/$TB_NAME/get"));
$is_master   = ($vm === 'master');
$colspan = ($is_master ? 11 : 11) + ($has_actions ? 1 : 0);
?>

<style>
    .table td, .table th { vertical-align: middle; }
    .b-status { font-weight:600; font-size:0.72rem; padding:0.25em 0.55em; border-radius:5px; }
    .b-req-type { background:#e0f2fe; color:#075985; font-size:0.73rem; padding:0.25em 0.55em; border-radius:5px; }
    .amt { font-weight:700; color:#1e293b; }
    tr.cancelled td { opacity: 0.5; text-decoration: line-through; text-decoration-color: #cbd5e1; }
    tr.cancelled td:last-child { text-decoration: none; }
</style>

<?php if (!is_array($page_rows) || count($page_rows) === 0): ?>
    <div class="alert alert-light text-center text-muted py-4"><i class="fa fa-inbox fa-2x d-block mb-2" style="opacity:.4"></i>لا توجد طلبات</div>
<?php else: ?>

    <div class="d-flex justify-content-between align-items-center mb-2">
        <small class="text-muted"><?= isset($total_rows) ? $total_rows : count($page_rows) ?> نتيجة</small>
        <div class="btn-group btn-group-sm">
            <button type="button" class="btn btn-<?= $is_master ? 'primary' : 'outline-secondary' ?>" onclick="switchView('master')" title="عرض الطلبات"><i class="fa fa-list"></i> طلبات</button>
            <button type="button" class="btn btn-<?= !$is_master ? 'primary' : 'outline-secondary' ?>" onclick="switchView('detail')" title="عرض الموظفين"><i class="fa fa-users"></i> موظفين</button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered" id="page_tb" data-container="container">
            <thead class="table-light">
            <tr>
                <th style="width:36px">م</th>
                <th>رقم الطلب</th>
                <?php if ($is_master): ?>
                    <th class="text-center">عدد الموظفين</th>
                <?php else: ?>
                    <th>الموظف</th>
                    <th>المقر</th>
                <?php endif; ?>
                <th>الشهر</th>
                <th>نوع الطلب</th>
                <th class="text-end">المبلغ</th>
                <th>الحالة</th>
                <th>المنشئ</th>
                <th>تاريخ الإنشاء</th>
                <th>رقم الدفعة</th>
                <th>تاريخ الصرف</th>
                <?php if ($has_actions): ?>
                    <th class="text-center" style="width:100px"></th>
                <?php endif; ?>
            </tr>
            </thead>
            <tbody>
            <?php if ($page > 1): ?>
                <tr><td colspan="<?= $colspan ?>" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td></tr>
            <?php endif; ?>
            <?php foreach ($page_rows as $row):
                $st  = (int)($row['STATUS'] ?? 0);
                $stm = $status_map[$st] ?? $status_map[0];
                $rid = $row['REQ_ID'] ?? '';

                $thm = $row['THE_MONTH'] ?? '';
                if (strlen($thm)==6) { $mn=(int)substr($thm,4,2); $thm=months($mn).' '.substr($thm,0,4); }

                $amt = isset($row['REQ_AMOUNT']) ? n_format((float)$row['REQ_AMOUNT']) : '0';
                $is_cancelled = ($st == 9);
                $is_active = (int)($row['IS_ACTIVE'] ?? 1);
                $row_class = $is_cancelled ? 'cancelled' : '';
                $emp_no   = $row['EMP_NO'] ?? '';
                $emp_name = $row['EMP_NAME'] ?? '';
                $branch   = $row['BRANCH_NAME'] ?? '';
                $row_bg   = '';
                if (!$is_master && !$is_cancelled && $is_active != 1) {
                    $row_bg = 'background:#fef2f2;'; // أحمر خفيف — غير فعال
                } elseif (!$is_master && !$is_cancelled && $is_active == 1) {
                    $row_bg = ''; // فعال — بدون لون (أبيض عادي)
                }
                ?>
                <tr class="<?= $row_class ?>" style="<?= $row_bg ?>"
                    ondblclick="javascript:get_to_link('<?= base_url("$MODULE_NAME/$TB_NAME/get/$rid") ?>');">
                    <td class="text-center text-muted"><?= $count ?></td>
                    <td><span style="font-weight:600;color:#64748b"><?= $row['REQ_NO'] ?? '' ?></span></td>
                    <?php if ($is_master): ?>
                        <td class="text-center fw-bold"><?= $row['EMP_COUNT'] ?? 0 ?></td>
                    <?php else: ?>
                        <td>
                            <span class="fw-bold"><?= $emp_no ?></span>
                            <span class="text-muted d-block" style="font-size:.78rem"><?= $emp_name ?><?php if ($is_active != 1): ?> <span style="background:#fee2e2;color:#991b1b;padding:1px 5px;border-radius:4px;font-size:.6rem;font-weight:600">غير فعال</span><?php endif; ?></span>
                        </td>
                        <td style="font-size:.78rem"><?= $branch ?></td>
                    <?php endif; ?>
                    <td><?= $thm ?></td>
                    <td><span class="b-req-type"><?= $row['REQ_TYPE_NAME'] ?? '' ?></span></td>
                    <td class="text-end"><span class="amt"><?= $is_master ? n_format((float)($row['TOTAL_AMOUNT'] ?? 0)) : $amt ?></span></td>
                    <td class="text-center">
                        <span class="b-status" style="background:<?= $stm['bg'] ?>;color:<?= $stm['color'] ?>">
                            <i class="fa <?= $stm['icon'] ?>"></i> <?= $row['STATUS_NAME'] ?? $stm['label'] ?>
                        </span>
                    </td>
                    <td style="font-size:.75rem;color:#64748b"><?= $row['ENTRY_USER_NAME'] ?? '' ?></td>
                    <td style="font-size:.75rem;color:#64748b"><?= $row['ENTRY_DATE'] ?? '' ?></td>
                    <td class="text-center"><?php
                        $batch_id = $row['BATCH_ID'] ?? '';
                        $batch_no = $row['BATCH_NO'] ?? '';
                        if ($batch_no):
                    ?><a href="<?= base_url("$MODULE_NAME/$TB_NAME/batch_detail/$batch_id") ?>" onclick="event.stopPropagation()" class="fw-bold" style="color:#7c3aed;font-size:.78rem;text-decoration:none" title="عرض الدفعة"><?= $batch_no ?></a><?php else: ?><span class="text-muted" style="font-size:.72rem">—</span><?php endif; ?></td>
                    <td style="font-size:.75rem;color:#059669"><?= $row['PAY_DATE'] ?? '' ?></td>
                    <?php if ($has_actions): ?>
                        <td class="text-center">
                            <div class="d-inline-flex gap-1">
                                <?php if ($st == 0 && HaveAccess($delete_url)): ?>
                                    <button class="btn btn-sm btn-outline-danger" title="حذف الطلب" onclick="event.stopPropagation();delete_req(this,<?=$rid?>)"><i class="fa fa-trash"></i></button>
                                <?php endif; ?>
                                <?php if (in_array($st, [1, 3]) && HaveAccess($delete_url)): ?>
                                    <button class="btn btn-sm btn-outline-danger" title="إلغاء الطلب" onclick="event.stopPropagation();cancel_req(this,<?=$rid?>)"><i class="fa fa-ban"></i></button>
                                <?php endif; ?>
                                <a class="btn btn-sm btn-outline-secondary" title="عرض" href="<?= base_url("$MODULE_NAME/$TB_NAME/get/$rid") ?>" onclick="event.stopPropagation()"><i class="fa fa-eye"></i></a>
                            </div>
                        </td>
                    <?php endif; ?>
                </tr>
                <?php $count++; endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php echo $this->pagination->create_links(); ?>

<script>
    if (typeof reBind === 'function') reBind();
</script>
