<?php
$count   = $offset;
$colspan = 9;
$tot     = $totals ?? ['total'=>0,'banks'=>0,'wallets'=>0,'inactive'=>0];
?>

<div id="provTotals"
     data-total="<?= (int)$tot['total'] ?>"
     data-banks="<?= (int)$tot['banks'] ?>"
     data-wallets="<?= (int)$tot['wallets'] ?>"
     data-inactive="<?= (int)$tot['inactive'] ?>"
     style="display:none"></div>

<style>
    .prov-tbl td, .prov-tbl th { vertical-align: middle; font-size:.82rem }
    .prov-tbl .name-cell { min-width: 200px }
    .prov-tbl .iban-cell { min-width: 240px }
    .prov-tbl tr.selected { background:#fffbeb !important; box-shadow:inset 3px 0 0 #f59e0b }
    .pv-bank   { color:#1e40af }
    .pv-wallet { color:#6d28d9 }
    .type-badge { font-size:.65rem; padding:.15em .55em; border-radius:5px; font-weight:700 }
    .type-badge.bank   { background:#dbeafe; color:#1e40af }
    .type-badge.wallet { background:#f5f3ff; color:#6d28d9 }
    .b-status { font-weight:600; font-size:.7rem; padding:.2em .55em; border-radius:5px }
    .s-active   { background:#d1fae5; color:#065f46 }
    .s-inactive { background:#fee2e2; color:#991b1b }
    .iban-ok      { color:#059669; direction:ltr; font-family:monospace; letter-spacing:.5px; font-weight:700 }
    .iban-missing { color:#991b1b; background:#fee2e2; padding:.1em .4em; border-radius:4px; font-size:.7rem }
    .iban-na      { color:#94a3b8; font-style:italic }
    .acc-num      { direction:ltr; font-family:monospace; font-weight:700; color:#1e293b }
    .meta-link    { cursor:pointer; padding:.1em .4em; border-radius:4px }
    .meta-link:hover { background:#e0f2fe; color:#0369a1 }
</style>

<?php if (!is_array($page_rows) || count($page_rows) === 0): ?>
    <div class="alert alert-light text-center text-muted py-4">
        <i class="fa fa-inbox fa-2x d-block mb-2" style="opacity:.4"></i>
        لا يوجد مزودون مطابقون للفلاتر
    </div>
<?php else: ?>

    <div class="d-flex justify-content-between align-items-center mb-2">
        <small class="text-muted"><?= isset($total_rows) ? $total_rows : count($page_rows) ?> مزود</small>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered prov-tbl" id="page_tb" data-container="container">
            <thead class="table-light">
                <tr>
                    <th style="width:35px"></th>
                    <th style="width:35px">#</th>
                    <th class="name-cell">المزود</th>
                    <th class="text-center" style="width:70px">النوع</th>
                    <th>رقم الحساب</th>
                    <th class="iban-cell">IBAN</th>
                    <th class="text-center" style="width:80px">الموظفين</th>
                    <th class="text-center" style="width:75px">الفروع</th>
                    <th class="text-center" style="width:80px">الحالة</th>
                    <th class="text-center" style="width:140px">إجراءات</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($page > 1): ?>
                    <tr><td colspan="<?= $colspan + 1 ?>" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td></tr>
                <?php endif; ?>
                <?php foreach ($page_rows as $row):
                    $pid       = (int)($row['PROVIDER_ID']   ?? 0);
                    $is_wallet = ((int)($row['PROVIDER_TYPE'] ?? 1)) == 2;
                    $name      = $row['PROVIDER_NAME']    ?? '';
                    $code      = $row['PROVIDER_CODE']    ?? '';
                    $iban      = trim($row['COMPANY_IBAN']?? '');
                    $iban_valid= (strlen($iban) >= 20);
                    $acc_no    = $row['COMPANY_ACCOUNT_NO']?? '';
                    $is_active = (int)($row['IS_ACTIVE']  ?? 1);
                    $br_cnt    = (int)($row['BRANCH_COUNT']  ?? 0);
                    $acc_cnt   = (int)($row['ACCOUNT_COUNT'] ?? 0);
                    $count++;
                ?>
                <tr data-id="<?= $pid ?>"
                    data-provider='<?= htmlspecialchars(json_encode($row, JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8') ?>'>
                    <td class="text-center">
                        <input type="checkbox" class="prov-cb" data-id="<?= $pid ?>">
                    </td>
                    <td class="text-center text-muted"><?= $count ?></td>
                    <td class="name-cell">
                        <?php if ($is_wallet): ?>
                            <i class="fa fa-mobile pv-wallet"></i>
                        <?php else: ?>
                            <i class="fa fa-bank pv-bank"></i>
                        <?php endif; ?>
                        <span class="fw-bold"><?= htmlspecialchars($name) ?></span>
                        <?php if ($code): ?>
                            <small class="text-muted d-block" style="direction:ltr;font-size:.7rem">[<?= htmlspecialchars($code) ?>]</small>
                        <?php endif; ?>
                    </td>
                    <td class="text-center">
                        <span class="type-badge <?= $is_wallet ? 'wallet' : 'bank' ?>">
                            <?= $is_wallet ? 'محفظة' : 'بنك' ?>
                        </span>
                    </td>
                    <td>
                        <?php if ($acc_no): ?>
                            <span class="acc-num"><?= htmlspecialchars($acc_no) ?></span>
                        <?php else: ?>
                            <span class="text-muted">—</span>
                        <?php endif; ?>
                    </td>
                    <td class="iban-cell">
                        <?php if ($iban_valid): ?>
                            <span class="iban-ok"><?= htmlspecialchars($iban) ?></span>
                        <?php elseif ($is_wallet): ?>
                            <span class="iban-na">— غير مطلوب</span>
                        <?php else: ?>
                            <span class="iban-missing"><i class="fa fa-exclamation-triangle"></i> يحتاج تعبئة</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-center">
                        <?php if ($acc_cnt > 0): ?>
                            <span class="meta-link" onclick="javascript:showAccounts(<?= $pid ?>,'<?= htmlspecialchars(addslashes($name), ENT_QUOTES) ?>');">
                                <i class="fa fa-users"></i> <b><?= $acc_cnt ?></b>
                            </span>
                        <?php else: ?>
                            <span class="text-muted">—</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-center">
                        <?php if (!$is_wallet): ?>
                            <span class="meta-link" onclick="javascript:showBranches(<?= $pid ?>,'<?= htmlspecialchars(addslashes($name), ENT_QUOTES) ?>');">
                                <i class="fa fa-building"></i> <b><?= $br_cnt ?></b>
                            </span>
                        <?php else: ?>
                            <span class="text-muted">—</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-center">
                        <?php if ($is_active): ?>
                            <span class="b-status s-active">نشط</span>
                        <?php else: ?>
                            <span class="b-status s-inactive">موقوف</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-center">
                        <div class="d-inline-flex gap-1">
                            <button type="button" class="btn btn-sm btn-outline-info prov-detail-btn" title="تفاصيل">
                                <i class="fa fa-info-circle"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-primary prov-edit-btn" title="تعديل">
                                <i class="fa fa-pencil"></i>
                            </button>
                            <?php if ($is_active): ?>
                                <button type="button" class="btn btn-sm btn-outline-warning"
                                        title="إيقاف" onclick="javascript:toggleProvider(<?= $pid ?>, 0);">
                                    <i class="fa fa-pause"></i>
                                </button>
                            <?php else: ?>
                                <button type="button" class="btn btn-sm btn-outline-success"
                                        title="تفعيل" onclick="javascript:toggleProvider(<?= $pid ?>, 1);">
                                    <i class="fa fa-play"></i>
                                </button>
                            <?php endif; ?>
                            <button type="button" class="btn btn-sm btn-outline-danger" title="حذف"
                                    onclick="javascript:deleteProvider(<?= $pid ?>,'<?= htmlspecialchars(addslashes($name), ENT_QUOTES) ?>');">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
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
    if (typeof refreshTotals === 'function') {
        var t = $('#provTotals');
        refreshTotals({
            total:    t.data('total'),
            banks:    t.data('banks'),
            wallets:  t.data('wallets'),
            inactive: t.data('inactive')
        });
    }
</script>
