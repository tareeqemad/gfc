<?php
$MODULE_NAME = 'payment_accounts';
$TB_NAME     = 'payment_accounts';
$count       = $offset;
$emp_url     = base_url("$MODULE_NAME/$TB_NAME/emp");
$colspan     = 9;
$tot         = $totals ?? ['total'=>0,'bank'=>0,'wallet'=>0,'benef'=>0];
?>

<div id="paTotals"
     data-total="<?= (int)$tot['total'] ?>"
     data-bank="<?= (int)$tot['bank'] ?>"
     data-wallet="<?= (int)$tot['wallet'] ?>"
     data-benef="<?= (int)$tot['benef'] ?>"
     style="display:none"></div>

<style>
    .pa-tbl td, .pa-tbl th { vertical-align: middle; font-size:.82rem }
    .pa-tbl .emp-cell { min-width: 200px }
    .pa-tbl .prov-cell { min-width: 140px }
    .pa-tbl .acc-no-cell { min-width: 130px }
    .pa-tbl .iban-cell { min-width: 240px }
    .b-acc { display:inline-block;padding:1px 7px;border-radius:5px;font-size:.68rem;font-weight:600;margin-left:2px }
    .b-acc.bank   { background:#dbeafe;color:#1e40af }
    .b-acc.wallet { background:#f5f3ff;color:#6d28d9 }
    .b-acc.benef  { background:#fef3c7;color:#92400e }
    .b-acc.more   { background:#fef3c7;color:#92400e }
    .b-acc.none   { background:#fee2e2;color:#991b1b }
    .b-status { font-weight:600;font-size:.7rem;padding:.2em .55em;border-radius:5px }
    .s-active  { background:#d1fae5;color:#065f46 }
    .s-retired { background:#f1f5f9;color:#64748b }
    .prov-name { font-weight:700;font-size:.82rem }
    .prov-name.pv-bank   { color:#1e40af }
    .prov-name.pv-wallet { color:#6d28d9 }
    .prov-name i { margin-left:4px }
    .acc-no   { font-family:monospace;font-size:.85rem;color:#1e293b;direction:ltr;text-align:left;font-weight:600 }
    .acc-iban { font-family:monospace;font-size:.78rem;color:#475569;direction:ltr;text-align:left;letter-spacing:.5px }
    .acc-owner{ font-size:.7rem;color:#94a3b8;display:block;margin-top:.15rem }
    tr.emp-row { cursor:pointer }
    tr.emp-row:hover td { background:#f8fafc }
</style>

<?php if (!is_array($page_rows) || count($page_rows) === 0): ?>
    <div class="alert alert-light text-center text-muted py-4">
        <i class="fa fa-inbox fa-2x d-block mb-2" style="opacity:.4"></i>
        لا توجد بيانات مطابقة للفلاتر
    </div>
<?php else: ?>

    <div class="d-flex justify-content-between align-items-center mb-2">
        <small class="text-muted"><?= isset($total_rows) ? $total_rows : count($page_rows) ?> موظف</small>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered pa-tbl" id="page_tb" data-container="container">
            <thead class="table-light">
            <tr>
                <th style="width:40px">#</th>
                <th class="emp-cell">الموظف</th>
                <th>المقر</th>
                <th class="text-center" style="width:90px">التوظيف</th>
                <th class="prov-cell">البنك / المحفظة</th>
                <th class="acc-no-cell">رقم الحساب</th>
                <th class="iban-cell">IBAN</th>
                <th class="text-center" style="width:80px">مستفيدون</th>
                <th class="text-center" style="width:70px"></th>
            </tr>
            </thead>
            <tbody>
            <?php if ($page > 1): ?>
                <tr><td colspan="<?= $colspan ?>" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td></tr>
            <?php endif; ?>
            <?php foreach ($page_rows as $row):
                $emp_no     = $row['EMP_NO'] ?? '';
                $emp_name   = $row['EMP_NAME'] ?? '';
                $branch     = $row['BRANCH_NAME'] ?? '';
                $is_active  = (int)($row['IS_ACTIVE'] ?? 0);
                $acc_cnt    = (int)($row['ACC_COUNT'] ?? 0);
                $active_cnt = (int)($row['ACTIVE_COUNT'] ?? 0);
                $benef_cnt  = (int)($row['BENEF_COUNT'] ?? 0);
                $def_prov   = $row['DEF_PROVIDER_NAME'] ?? '';
                $def_type   = (int)($row['DEF_PROVIDER_TYPE'] ?? 0);
                $def_acc    = $row['DEF_ACCOUNT_NO'] ?? '';
                $def_iban   = $row['DEF_IBAN'] ?? '';
                $def_owner  = $row['DEF_OWNER_NAME'] ?? '';
                $more_cnt   = max(0, $active_cnt - 1);
                $count++;
            ?>
            <tr class="emp-row"
                data-emp-no="<?= $emp_no ?>"
                ondblclick="javascript:get_to_link('<?= $emp_url ?>/<?= $emp_no ?>')">
                <td class="text-center text-muted"><?= $count ?></td>
                <td class="emp-cell">
                    <span class="fw-bold"><?= $emp_no ?></span>
                    <span class="text-muted d-block" style="font-size:.78rem"><?= $emp_name ?></span>
                </td>
                <td><?= $branch ?></td>
                <td class="text-center">
                    <?php if ($is_active == 1): ?>
                        <span class="b-status s-active">فعّال</span>
                    <?php else: ?>
                        <span class="b-status s-retired">متقاعد</span>
                    <?php endif; ?>
                </td>
                <?php if ($active_cnt == 0): ?>
                    <td colspan="3" class="text-center">
                        <span class="b-acc none"><i class="fa fa-exclamation-triangle"></i> لا يوجد حساب نشط</span>
                    </td>
                <?php elseif (!$def_prov): ?>
                    <td colspan="3" class="text-center">
                        <span class="b-acc none"><i class="fa fa-question-circle"></i> بيانات غير مكتملة</span>
                    </td>
                <?php else: ?>
                    <td class="prov-cell">
                        <?php if ($def_type == 1): ?>
                            <span class="prov-name pv-bank"><i class="fa fa-bank"></i><?= $def_prov ?></span>
                        <?php else: ?>
                            <span class="prov-name pv-wallet"><i class="fa fa-mobile"></i><?= $def_prov ?></span>
                        <?php endif; ?>
                        <?php if ($more_cnt > 0): ?>
                            <span class="b-acc more" title="حسابات إضافية للموظف">+<?= $more_cnt ?> آخر</span>
                        <?php endif; ?>
                    </td>
                    <td class="acc-no-cell">
                        <?php if ($def_acc): ?>
                            <div class="acc-no"><?= $def_acc ?></div>
                            <?php if ($def_owner && $def_owner != $emp_name): ?>
                                <span class="acc-owner"><i class="fa fa-user-o"></i> <?= $def_owner ?></span>
                            <?php endif; ?>
                        <?php else: ?>
                            <span class="text-muted" style="font-size:.75rem">—</span>
                        <?php endif; ?>
                    </td>
                    <td class="iban-cell">
                        <?php if ($def_iban && $def_type == 1): ?>
                            <div class="acc-iban"><?= $def_iban ?></div>
                        <?php else: ?>
                            <span class="text-muted" style="font-size:.75rem">—</span>
                        <?php endif; ?>
                    </td>
                <?php endif; ?>
                <td class="text-center">
                    <?php if ($benef_cnt > 0): ?>
                        <span class="b-acc benef"><i class="fa fa-user-circle-o"></i> <?= $benef_cnt ?></span>
                    <?php else: ?>
                        <span class="text-muted" style="font-size:.75rem">—</span>
                    <?php endif; ?>
                </td>
                <td class="text-center">
                    <a href="<?= $emp_url ?>/<?= $emp_no ?>" class="btn btn-sm btn-outline-primary" onclick="event.stopPropagation()" title="إدارة الحسابات">
                        <i class="fa fa-pencil"></i>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php echo $this->pagination->create_links(); ?>

<script>
    if (typeof reBind === 'function') reBind();
</script>
