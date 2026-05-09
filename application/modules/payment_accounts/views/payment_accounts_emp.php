<?php
$MODULE_NAME = 'payment_accounts';
$TB_NAME     = 'payment_accounts';

$urls = [
    'back'        => base_url("$MODULE_NAME/$TB_NAME"),
    'acc_save'    => base_url("$MODULE_NAME/$TB_NAME/account_save"),
    'acc_del'     => base_url("$MODULE_NAME/$TB_NAME/account_delete"),
    'acc_deact'   => base_url("$MODULE_NAME/$TB_NAME/account_deactivate"),
    'acc_react'   => base_url("$MODULE_NAME/$TB_NAME/account_reactivate"),
    'acc_default' => base_url("$MODULE_NAME/$TB_NAME/account_set_default"),
    'benef_save'  => base_url("$MODULE_NAME/$TB_NAME/benef_save"),
    'benef_del'   => base_url("$MODULE_NAME/$TB_NAME/benef_delete"),
    'branches'    => base_url("$MODULE_NAME/$TB_NAME/branches_list_json"),
    'attach'      => base_url('attachments/attachment/public_upload'),
    'auto_fix'    => base_url("$MODULE_NAME/$TB_NAME/auto_fix_splits"),
    'link_auto'   => base_url("$MODULE_NAME/$TB_NAME/account_link_auto"),
];

// تصنيف المرفقات الخاص بالمستفيدين (يتعرّف عليه نظام المرفقات)
$ATTACH_CATEGORY_BENEF = 'payment_benef';

$e = $emp_data ?? [];
$emp_is_active = (int)($e['IS_ACTIVE'] ?? 0);

$accounts_arr      = is_array($accounts ?? null)      ? $accounts      : [];
$beneficiaries_arr = is_array($beneficiaries ?? null) ? $beneficiaries : [];
$providers_arr     = is_array($providers ?? null)     ? $providers     : [];

// كل بيانات الصفحة الديناميكية تذهب لـ <script type="application/json">
// بدل ما تُحقن في inline onclick — يلغي مخاطر XSS وكسر الـ HTML attributes
$page_data = [
    'urls'   => $urls,
    'emp_no' => (int)$emp_no,
    'attach_category_benef' => $ATTACH_CATEGORY_BENEF,
    'employee' => [
        'NO'        => $e['EMP_NO']    ?? null,
        'NAME'      => $e['EMP_NAME']  ?? '',
        'ID_NO'     => $e['ID_NO']     ?? '',
        'TEL'       => $e['TEL']       ?? '',
        'IS_ACTIVE' => $emp_is_active,
    ],
    'accounts'      => $accounts_arr,
    'beneficiaries' => $beneficiaries_arr,
    'providers'     => array_map(function($p){
        return [
            'PROVIDER_ID'   => (int)$p['PROVIDER_ID'],
            'PROVIDER_NAME' => $p['PROVIDER_NAME'] ?? '',
            'PROVIDER_TYPE' => (int)($p['PROVIDER_TYPE'] ?? 1),
        ];
    }, $providers_arr),
];

$rel_options = [
    1 => '👰 زوجة', 2 => '👦 ابن', 3 => '👧 بنت',
    4 => '👨‍🦳 أب',  5 => '👩‍🦳 أم',  9 => '🎭 وريث آخر',
];
?>

<style>
/* ═══ Profile Card ═══ */
.emp-profile{display:flex;align-items:center;gap:1rem;padding:1rem 1.25rem;background:linear-gradient(135deg,#1e293b 0%,#334155 100%);color:#fff;border-radius:12px;margin-bottom:.75rem;box-shadow:0 2px 8px rgba(0,0,0,.08)}
.emp-avatar{width:56px;height:56px;border-radius:50%;background:#fff;color:#1e293b;display:flex;align-items:center;justify-content:center;font-size:1.4rem;font-weight:800;flex-shrink:0;box-shadow:0 2px 6px rgba(0,0,0,.15)}
.emp-info{flex:1;min-width:0}
.emp-info .name{font-size:1.1rem;font-weight:800;color:#fff;display:flex;align-items:center;gap:.55rem;flex-wrap:wrap}
.emp-info .name .num{background:rgba(255,255,255,.15);padding:.05em .55em;border-radius:5px;font-size:.78rem;font-weight:600}
.emp-info .meta{display:flex;flex-wrap:wrap;gap:1rem;font-size:.78rem;color:#cbd5e1;margin-top:.4rem}
.emp-info .meta span{display:inline-flex;align-items:center;gap:.3rem}
.emp-info .meta b{color:#fff;font-weight:700;direction:ltr}
.status-pill{display:inline-block;padding:.18em .75em;border-radius:6px;font-size:.7rem;font-weight:700}
.status-pill.active {background:#10b981;color:#fff}
.status-pill.retired{background:#94a3b8;color:#fff}

/* ═══ Stats Row ═══ */
.pa-stats{display:flex;gap:.5rem;flex-wrap:wrap;margin-bottom:.85rem}
.pa-stat{flex:1;min-width:120px;padding:.65rem .75rem;border-radius:10px;border:1px solid #e2e8f0;background:#fff;display:flex;align-items:center;gap:.6rem}
.pa-stat .ps-icon{width:36px;height:36px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0}
.pa-stat .ps-body{flex:1;min-width:0}
.pa-stat .ps-label{font-size:.7rem;color:#64748b;line-height:1.1}
.pa-stat .ps-val  {font-size:1.05rem;font-weight:800;color:#1e293b;line-height:1.2}
.pa-stat.bank   .ps-icon{background:#dbeafe;color:#1e40af}
.pa-stat.wallet .ps-icon{background:#f5f3ff;color:#6d28d9}
.pa-stat.benef  .ps-icon{background:#fef3c7;color:#92400e}
.pa-stat.split  .ps-icon{background:#dcfce7;color:#166534}
.pa-stat.split.bad .ps-icon{background:#fee2e2;color:#991b1b}

/* ═══ Section Headers ═══ */
.sec-hdr{display:flex;align-items:center;gap:.5rem;padding:.5rem .75rem;background:#f8fafc;border-radius:8px;margin-bottom:.6rem;border:1px solid #e2e8f0}
.sec-hdr h5{margin:0;font-size:.92rem;font-weight:700;color:#1e293b;flex:1}
.sec-hdr .count{background:#1e293b;color:#fff;padding:.1em .5em;border-radius:5px;font-size:.7rem;font-weight:700}

/* ═══ Account Cards ═══ */
.acc-card{border:1px solid #e2e8f0;border-radius:10px;padding:.85rem 1rem;margin-bottom:.6rem;background:#fff;transition:box-shadow .15s,transform .15s}
.acc-card:hover{box-shadow:0 3px 10px rgba(0,0,0,.06)}
.acc-card.inactive{opacity:.65;background:#fafafa;border-style:dashed}
.acc-card.wallet{background:#faf5ff;border-color:#e9d5ff}
.acc-card.beneficiary{background:#fffbeb;border-color:#fde68a}
.acc-card .acc-head{display:flex;align-items:center;gap:.5rem;margin-bottom:.55rem;flex-wrap:wrap}
.acc-card .acc-type{font-size:.95rem;font-weight:800;color:#1e293b;flex:1}
.acc-card .acc-type small{font-weight:500;color:#64748b}
.acc-card .acc-flag{font-size:.66rem;padding:.15em .55em;border-radius:5px;font-weight:700;letter-spacing:.5px}
.acc-card .acc-flag.default{background:#22c55e;color:#fff;box-shadow:0 1px 3px rgba(34,197,94,.35)}
.acc-card .acc-flag.deactivated{background:#fee2e2;color:#991b1b}
.acc-card .acc-flag.benef{background:#fde68a;color:#92400e}
.acc-card .acc-body{font-size:.82rem;color:#475569;line-height:1.75;padding:.4rem .55rem;background:rgba(255,255,255,.5);border-radius:6px;border:1px dashed #e2e8f0}
.acc-card .acc-body b{color:#1e293b;direction:ltr;display:inline-block}
.acc-card .acc-foot{display:flex;align-items:center;gap:.5rem;margin-top:.5rem;flex-wrap:wrap}
.acc-card .acc-foot .split-info{flex:1;font-size:.76rem;color:#475569;background:transparent;padding:0;display:inline-flex;align-items:center;gap:.35rem}
.acc-card .acc-foot .split-info b{color:#7c3aed;font-weight:700}
.acc-card .acc-foot .acc-actions{display:flex;gap:.25rem}
.acc-card .acc-foot .acc-actions .btn{padding:.2rem .5rem;font-size:.78rem}

.sum-line{display:flex;gap:.5rem;flex-wrap:wrap;align-items:center;padding:.55rem .85rem;background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;margin-top:.5rem;font-size:.82rem}
.sum-line .ok {color:#059669;font-weight:600}
.sum-line .bad{color:#dc2626;font-weight:600}
.sum-line .ms-auto-btn{margin-inline-start:auto}

/* شريط التوزيع البصري */
.dist-wrap{margin-top:.5rem}
.dist-bar{display:flex;height:28px;border-radius:6px;overflow:hidden;border:1px solid #e2e8f0;background:#f1f5f9}
.dist-seg{display:flex;align-items:center;justify-content:center;color:#fff;font-size:.7rem;font-weight:700;min-width:24px;text-shadow:0 1px 1px rgba(0,0,0,.25);transition:filter .15s}
.dist-seg:hover{filter:brightness(1.08)}
.dist-seg.bank      {background:#3b82f6}
.dist-seg.wallet    {background:#a855f7}
.dist-seg.benef     {background:#f59e0b}
.dist-seg.bank.is-rem  {background:repeating-linear-gradient(45deg,#1e40af,#1e40af 6px,#3b82f6 6px,#3b82f6 12px)}
.dist-seg.wallet.is-rem{background:repeating-linear-gradient(45deg,#6d28d9,#6d28d9 6px,#a855f7 6px,#a855f7 12px)}
.dist-seg.benef.is-rem {background:repeating-linear-gradient(45deg,#92400e,#92400e 6px,#f59e0b 6px,#f59e0b 12px)}
.dist-fixed{display:flex;flex-wrap:wrap;gap:.3rem;margin-top:.4rem}
.dist-chip{display:inline-flex;align-items:center;gap:.3rem;padding:.18rem .55rem;background:#fef3c7;color:#92400e;border:1px solid #fcd34d;border-radius:5px;font-size:.7rem;font-weight:700}
.dist-legend{display:flex;flex-wrap:wrap;gap:.4rem;margin-top:.35rem;font-size:.7rem;color:#64748b}
.dist-legend .lg-item{display:inline-flex;align-items:center;gap:.25rem}
.dist-legend .lg-dot{width:10px;height:10px;border-radius:2px;display:inline-block}

.benef-row{display:flex;align-items:center;gap:.5rem;padding:.6rem .75rem;border:1px solid #e2e8f0;border-radius:8px;margin-bottom:.4rem;background:#fff;font-size:.82rem;transition:box-shadow .15s}
.benef-row:hover{box-shadow:0 2px 6px rgba(0,0,0,.05)}
.benef-row.no-attach{border-color:#fca5a5;background:#fff1f2}
.benef-row .rel-badge{background:#fef3c7;color:#92400e;padding:.18em .55em;border-radius:5px;font-size:.68rem;font-weight:700;letter-spacing:.3px}
.benef-empty{padding:1.4rem 1rem;text-align:center;color:#94a3b8;background:#f8fafc;border:2px dashed #e2e8f0;border-radius:10px}
.benef-empty i{font-size:2rem;display:block;margin-bottom:.5rem;color:#cbd5e1}
.benef-empty .lbl{font-size:.82rem;font-weight:600;color:#64748b}
.benef-row .att-chip{display:inline-flex;align-items:center;gap:.25rem;padding:.15em .5em;border-radius:5px;font-size:.68rem;font-weight:700;cursor:pointer;border:1px solid transparent;text-decoration:none}
.benef-row .att-chip.has   {background:#dcfce7;color:#166534;border-color:#86efac}
.benef-row .att-chip.has:hover{background:#bbf7d0}
.benef-row .att-chip.miss  {background:#fee2e2;color:#991b1b;border-color:#fca5a5;animation:att-pulse 2s infinite}
.benef-row .att-chip.miss:hover{background:#fecaca}
@keyframes att-pulse{0%,100%{opacity:1}50%{opacity:.65}}
</style>

<div class="page-header">
    <div><h1 class="page-title"><?= htmlspecialchars($title ?? '') ?></h1></div>
    <div class="ms-auto pageheader-btn">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= $urls['back'] ?>">إدارة حسابات الصرف</a></li>
            <li class="breadcrumb-item active"><?= htmlspecialchars($e['EMP_NAME'] ?? $emp_no) ?></li>
        </ol>
    </div>
</div>

<?php $pending_batches_arr = is_array($pending_batches ?? null) ? $pending_batches : []; if (!empty($pending_batches_arr)): ?>
<div class="alert mb-3" style="background:#fef3c7;border:1px solid #fde68a;border-radius:10px;padding:.75rem 1rem">
    <div class="d-flex align-items-center gap-3">
        <i class="fa fa-exclamation-triangle" style="font-size:1.7rem;color:#d97706"></i>
        <div style="flex:1">
            <div style="font-weight:700;color:#92400e;font-size:.95rem">
                ⚠️ يوجد <?= count($pending_batches_arr) ?> دفعة محتسبة (لم تُنفّذ بعد) لهذا الموظف
            </div>
            <div style="font-size:.78rem;color:#78350f;margin-top:.2rem">
                أي تعديل على الحسابات يستلزم <b>"تحديث التوزيع"</b> من شاشة الدفعة قبل التنفيذ، وإلا سيُنفّذ بالتوزيع القديم.
            </div>
            <div style="margin-top:.4rem;display:flex;gap:.4rem;flex-wrap:wrap">
                <?php foreach ($pending_batches_arr as $pb):
                    $bid   = (int)($pb['BATCH_ID'] ?? 0);
                    $bno   = $pb['BATCH_NO'] ?? '';
                    $amt   = (float)($pb['EMP_AMOUNT'] ?? 0);
                    $bdate = $pb['BATCH_DATE'] ?? '';
                ?>
                    <a href="<?= base_url('payment_req/payment_req/batch_detail/' . $bid) ?>" target="_blank"
                       style="background:#fff;border:1px solid #fbbf24;color:#92400e;padding:.25rem .65rem;border-radius:6px;font-size:.78rem;text-decoration:none;font-weight:700">
                        <i class="fa fa-money"></i> <?= htmlspecialchars($bno) ?>
                        <small style="font-weight:400;opacity:.85"> · <?= n_format($amt) ?> ₪</small>
                        <i class="fa fa-external-link" style="font-size:.7rem;margin-inline-start:3px;opacity:.6"></i>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="row"><div class="col-lg-12"><div class="card">
    <div class="card-body">

    <?php
        // الأحرف الأولى من الاسم للـ avatar
        $emp_initials = '';
        if (!empty($e['EMP_NAME'])) {
            $parts = preg_split('/\s+/u', trim($e['EMP_NAME']));
            $emp_initials = mb_substr($parts[0] ?? '', 0, 1, 'UTF-8');
            if (count($parts) > 1) $emp_initials .= mb_substr($parts[1], 0, 1, 'UTF-8');
        }

        // إحصائيات سريعة
        $bank_cnt   = 0; $wallet_cnt = 0; $active_cnt = 0; $has_default = false;
        $sum_pct = 0; $has_remainder = false;
        foreach ($accounts_arr as $a) {
            if ((int)($a['IS_ACTIVE'] ?? 0) !== 1) continue;
            $active_cnt++;
            if ((int)($a['PROVIDER_TYPE'] ?? 1) === 2) $wallet_cnt++; else $bank_cnt++;
            if ((int)($a['IS_DEFAULT']    ?? 0) === 1) $has_default = true;
            $st = (int)($a['SPLIT_TYPE'] ?? 3);
            if ($st === 1) $sum_pct += (float)($a['SPLIT_VALUE'] ?? 0);
            if ($st === 3) $has_remainder = true;
        }
        $benef_cnt = count($beneficiaries_arr);
        $split_ok = ($active_cnt <= 1) || ($has_remainder && $sum_pct <= 100);
    ?>

    <!-- بطاقة بروفايل الموظف -->
    <div class="emp-profile">
        <div class="emp-avatar"><?= htmlspecialchars($emp_initials ?: '?') ?></div>
        <div class="emp-info">
            <div class="name">
                <?= htmlspecialchars($e['EMP_NAME'] ?? '') ?>
                <span class="num">#<?= htmlspecialchars($e['EMP_NO'] ?? '') ?></span>
                <?php if ($emp_is_active == 1): ?>
                    <span class="status-pill active">● فعّال</span>
                <?php else: ?>
                    <span class="status-pill retired">● متقاعد</span>
                <?php endif; ?>
            </div>
            <div class="meta">
                <span><i class="fa fa-id-card"></i> <b><?= htmlspecialchars($e['ID_NO'] ?? '—') ?></b></span>
                <span><i class="fa fa-phone"></i> <b><?= htmlspecialchars($e['TEL'] ?? '—') ?></b></span>
                <span><i class="fa fa-building"></i> <?= htmlspecialchars($e['BRANCH_NAME'] ?? '—') ?></span>
            </div>
        </div>
        <a href="<?= $urls['back'] ?>" class="btn btn-light btn-sm" title="رجوع للقائمة">
            <i class="fa fa-arrow-right"></i> رجوع
        </a>
    </div>

    <!-- بطاقات إحصائية -->
    <div class="pa-stats">
        <div class="pa-stat bank">
            <div class="ps-icon"><i class="fa fa-bank"></i></div>
            <div class="ps-body"><div class="ps-label">حسابات بنكية</div><div class="ps-val"><?= $bank_cnt ?></div></div>
        </div>
        <div class="pa-stat wallet">
            <div class="ps-icon"><i class="fa fa-mobile"></i></div>
            <div class="ps-body"><div class="ps-label">محافظ نشطة</div><div class="ps-val"><?= $wallet_cnt ?></div></div>
        </div>
        <div class="pa-stat benef">
            <div class="ps-icon"><i class="fa fa-users"></i></div>
            <div class="ps-body"><div class="ps-label">مستفيدون (ورثة)</div><div class="ps-val"><?= $benef_cnt ?></div></div>
        </div>
        <div class="pa-stat split <?= $split_ok ? '' : 'bad' ?>">
            <div class="ps-icon"><i class="fa fa-<?= $split_ok ? 'check-circle' : 'exclamation-triangle' ?>"></i></div>
            <div class="ps-body"><div class="ps-label">حالة التوزيع</div><div class="ps-val"><?= $split_ok ? 'سليم' : 'يحتاج مراجعة' ?></div></div>
        </div>
    </div>

    <div class="row g-3">
        <!-- ═══ القسم 1: حسابات الصرف ═══ -->
        <div class="col-lg-8">
            <div class="sec-hdr">
                <i class="fa fa-credit-card text-primary"></i>
                <h5>حسابات الصرف</h5>
                <span class="count"><?= $active_cnt ?>/<?= count($accounts_arr) ?></span>
                <?php if (!empty($beneficiaries_arr)): ?>
                    <button class="btn btn-sm btn-outline-secondary" data-action="link-auto"
                            title="ربط الحسابات الموجودة بالمستفيدين تلقائياً (مطابقة بالهوية أو الاسم)">
                        <i class="fa fa-link"></i> ربط تلقائي
                    </button>
                <?php endif; ?>
                <button class="btn btn-primary btn-sm" data-action="acc-new"><i class="fa fa-plus"></i> حساب جديد</button>
            </div>
            <div id="accountsList"></div>
        </div>

        <!-- ═══ القسم 2: المستفيدون ═══ -->
        <div class="col-lg-4">
            <div class="sec-hdr">
                <i class="fa fa-users text-warning"></i>
                <h5>المستفيدون (ورثة)</h5>
                <span class="count"><?= $benef_cnt ?></span>
                <button class="btn btn-warning btn-sm" data-action="benef-new"><i class="fa fa-plus"></i> مستفيد</button>
            </div>
            <div id="benefList"></div>
        </div>
    </div>

    <!-- ═══ لوحة التوزيع (تظهر فقط إذا أكثر من حساب نشط) ═══ -->
    <div class="row g-3 mt-1" id="distPanelRow" style="display:none">
        <div class="col-12">
            <div class="sec-hdr">
                <i class="fa fa-sitemap text-success"></i>
                <h5>ملخص توزيع المبلغ</h5>
                <button class="btn btn-sm btn-outline-primary" data-action="auto-fix" title="إصلاح تلقائي للتوزيع">
                    <i class="fa fa-magic"></i> إصلاح تلقائي
                </button>
            </div>
            <div class="sum-line" id="splitSumLine">
                <span id="splitSumText">جارٍ الحساب...</span>
            </div>
            <div class="dist-wrap" id="distVisualWrap" style="display:none">
                <div class="dist-bar" id="distBar"></div>
                <div class="dist-fixed" id="distFixed"></div>
                <div class="dist-legend">
                    <span class="lg-item"><span class="lg-dot" style="background:#3b82f6"></span>بنك</span>
                    <span class="lg-item"><span class="lg-dot" style="background:#a855f7"></span>محفظة</span>
                    <span class="lg-item"><span class="lg-dot" style="background:#f59e0b"></span>مستفيد</span>
                    <span class="lg-item"><span class="lg-dot" style="background:repeating-linear-gradient(45deg,#1e293b,#1e293b 4px,#475569 4px,#475569 8px)"></span>كامل الباقي (مخطّط)</span>
                </div>
            </div>
        </div>
    </div>

    </div>
</div></div></div>


<!-- ══════════ MODAL: إضافة/تعديل حساب ══════════ -->
<div class="modal fade" id="accModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border:0;border-radius:12px;overflow:hidden">
            <div class="modal-header py-2" style="background:#1e293b">
                <h6 class="modal-title text-white fw-bold"><i class="fa fa-credit-card me-1"></i> <span id="accModalTitle">حساب جديد</span></h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="accForm" onsubmit="return false;">
                    <input type="hidden" id="acc_id" name="acc_id">
                    <input type="hidden" name="emp_no" value="<?= (int)$emp_no ?>">

                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="fw-bold" style="font-size:.78rem">المزود <span class="text-danger">*</span></label>
                            <select name="provider_id" id="acc_provider_id" class="form-select">
                                <option value="">— اختر —</option>
                                <?php
                                $banks   = array_filter($providers_arr, fn($p) => (int)$p['PROVIDER_TYPE'] === 1);
                                $wallets = array_filter($providers_arr, fn($p) => (int)$p['PROVIDER_TYPE'] === 2);
                                ?>
                                <?php if ($banks): ?>
                                    <optgroup label="بنوك">
                                        <?php foreach ($banks as $p): ?>
                                            <option value="<?= (int)$p['PROVIDER_ID'] ?>" data-type="1"><?= htmlspecialchars($p['PROVIDER_NAME']) ?></option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                <?php endif; ?>
                                <?php if ($wallets): ?>
                                    <optgroup label="محافظ إلكترونية">
                                        <?php foreach ($wallets as $p): ?>
                                            <option value="<?= (int)$p['PROVIDER_ID'] ?>" data-type="2"><?= htmlspecialchars($p['PROVIDER_NAME']) ?></option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-6" id="branch_grp">
                            <label class="fw-bold" style="font-size:.78rem">الفرع</label>
                            <select name="branch_id" id="acc_branch_id" class="form-select">
                                <option value="">— اختر الفرع —</option>
                            </select>
                        </div>
                    </div>

                    <div id="bank_fields" class="row g-2 mt-1">
                        <div class="col-md-6">
                            <label class="fw-bold" style="font-size:.78rem">رقم الحساب <span class="text-danger">*</span></label>
                            <input type="text" name="account_no" id="acc_account_no" class="form-control" style="direction:ltr">
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold" style="font-size:.78rem">IBAN</label>
                            <input type="text" name="iban" id="acc_iban" class="form-control" style="direction:ltr" placeholder="PS...">
                        </div>
                    </div>

                    <div id="wallet_fields" class="row g-2 mt-1" style="display:none">
                        <div class="col-md-6">
                            <label class="fw-bold" style="font-size:.78rem">رقم المحفظة (جوال) <span class="text-danger">*</span></label>
                            <input type="text" name="wallet_number" id="acc_wallet_number" class="form-control" style="direction:ltr" placeholder="05...">
                        </div>
                    </div>

                    <hr class="my-2">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <label class="fw-bold" style="font-size:.78rem">صاحب الحساب</label>
                            <select id="acc_owner_kind" class="form-select">
                                <option value="self">الموظف نفسه</option>
                                <option value="other">شخص آخر</option>
                                <?php if (!empty($beneficiaries_arr)): ?>
                                    <optgroup label="المستفيدون (الورثة)">
                                        <?php foreach ($beneficiaries_arr as $b): ?>
                                            <option value="benef_<?= (int)$b['BENEFICIARY_ID'] ?>"
                                                    data-bid="<?= (int)$b['BENEFICIARY_ID'] ?>"
                                                    data-name="<?= htmlspecialchars($b['NAME'] ?? '') ?>"
                                                    data-id="<?= htmlspecialchars($b['ID_NO'] ?? '') ?>"
                                                    data-phone="<?= htmlspecialchars($b['PHONE'] ?? '') ?>">
                                                <?= htmlspecialchars(($b['REL_NAME'] ?? '') . ' — ' . ($b['NAME'] ?? '')) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                <?php endif; ?>
                            </select>
                            <input type="hidden" name="beneficiary_id" id="acc_beneficiary_id">
                        </div>
                        <div class="col-md-4">
                            <label class="fw-bold" style="font-size:.78rem">هوية صاحب الحساب</label>
                            <input type="text" name="owner_id_no" id="acc_owner_id_no" class="form-control" style="direction:ltr">
                        </div>
                        <div class="col-md-4">
                            <label class="fw-bold" style="font-size:.78rem">اسم صاحب الحساب</label>
                            <input type="text" name="owner_name" id="acc_owner_name" class="form-control">
                        </div>
                    </div>
                    <div class="row g-2 mt-1">
                        <div class="col-md-4">
                            <label class="fw-bold" style="font-size:.78rem">جوال صاحب الحساب</label>
                            <input type="text" name="owner_phone" id="acc_owner_phone" class="form-control" style="direction:ltr">
                        </div>
                    </div>

                    <hr class="my-2">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <label class="fw-bold" style="font-size:.78rem">نوع التوزيع</label>
                            <select name="split_type" id="acc_split_type" class="form-select">
                                <option value="3">كامل الباقي</option>
                                <option value="1">نسبة %</option>
                                <option value="2">مبلغ ثابت</option>
                            </select>
                        </div>
                        <div class="col-md-4" id="split_value_grp" style="display:none">
                            <label class="fw-bold" style="font-size:.78rem">القيمة</label>
                            <input type="number" name="split_value" id="acc_split_value" class="form-control" step="0.01" min="0">
                        </div>
                        <div class="col-md-2">
                            <label class="fw-bold" style="font-size:.78rem">الترتيب</label>
                            <input type="number" name="split_order" id="acc_split_order" class="form-control" value="1" min="1">
                        </div>
                        <div class="col-md-2">
                            <label class="fw-bold" style="font-size:.78rem">افتراضي</label>
                            <div class="form-check" style="padding-top:.4rem">
                                <input type="checkbox" id="acc_is_default" name="is_default" value="1" class="form-check-input">
                                <label class="form-check-label" for="acc_is_default">نعم</label>
                            </div>
                        </div>
                    </div>
                    <div class="row g-2 mt-1">
                        <div class="col-md-12">
                            <label class="fw-bold" style="font-size:.78rem">ملاحظات</label>
                            <input type="text" name="notes" id="acc_notes" class="form-control" placeholder="اختياري">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer py-2">
                <button class="btn btn-light btn-sm" data-bs-dismiss="modal">إلغاء</button>
                <button class="btn btn-primary btn-sm" data-action="acc-save"><i class="fa fa-save me-1"></i> حفظ</button>
            </div>
        </div>
    </div>
</div>


<!-- ══════════ MODAL: إيقاف حساب ══════════ -->
<div class="modal fade" id="deactAccModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border:0;border-radius:12px;overflow:hidden">
            <div class="modal-header py-2" style="background:#b91c1c">
                <h6 class="modal-title text-white fw-bold">
                    <i class="fa fa-pause-circle me-1"></i> إيقاف حساب
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="deact_acc_id">
                <div class="alert alert-warning py-2 mb-3" style="font-size:.82rem">
                    <i class="fa fa-info-circle me-1"></i>
                    إيقاف الحساب يمنع استخدامه في الصرف، لكن يبقى محفوظاً للسجل التاريخي.
                </div>
                <div class="row g-2">
                    <div class="col-md-7">
                        <label class="fw-bold" style="font-size:.82rem">سبب الإيقاف <span class="text-danger">*</span></label>
                        <select id="deact_reason" class="form-select">
                            <option value="">— اختر السبب —</option>
                            <option value="1">تقاعد</option>
                            <option value="2">وفاة</option>
                            <option value="3">فصل</option>
                            <option value="4">تجميد الحساب</option>
                            <option value="5">تحويل لحساب آخر</option>
                            <option value="9">أخرى</option>
                        </select>
                    </div>
                    <div class="col-md-5">
                        <label class="fw-bold" style="font-size:.82rem">
                            شهر بدء الإيقاف
                            <small class="text-muted" style="font-weight:400">(اختياري)</small>
                        </label>
                        <input type="month" id="deact_month" class="form-control"
                               title="مهم خاصة في حالات الوفاة — اتركه فارغاً للشهر الحالي">
                    </div>
                    <div class="col-md-12 mt-2">
                        <label class="fw-bold" style="font-size:.82rem">ملاحظة (اختياري)</label>
                        <textarea id="deact_notes" class="form-control" rows="2" placeholder="مثال: حسب تعميم البنك بتاريخ ..."></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-danger btn-sm" id="deactAccConfirm">
                    <i class="fa fa-pause me-1"></i> تأكيد الإيقاف
                </button>
            </div>
        </div>
    </div>
</div>


<!-- ══════════ MODAL: إضافة/تعديل مستفيد ══════════ -->
<div class="modal fade" id="benefModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border:0;border-radius:12px;overflow:hidden">
            <div class="modal-header py-2" style="background:#92400e">
                <h6 class="modal-title text-white fw-bold"><i class="fa fa-user-plus me-1"></i> <span id="benefModalTitle">مستفيد جديد</span></h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="benefForm" onsubmit="return false;">
                    <input type="hidden" id="benef_id" name="benef_id">
                    <input type="hidden" name="emp_no" value="<?= (int)$emp_no ?>">

                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="fw-bold" style="font-size:.78rem">نوع القرابة <span class="text-danger">*</span></label>
                            <select name="rel_type" id="benef_rel_type" class="form-select">
                                <?php foreach ($rel_options as $code => $label): ?>
                                    <option value="<?= $code ?>"><?= $label ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold" style="font-size:.78rem">رقم الهوية <span class="text-danger">*</span></label>
                            <input type="text" name="id_no" id="benef_id_no" class="form-control" style="direction:ltr">
                        </div>
                    </div>

                    <div class="row g-2 mt-1">
                        <div class="col-md-12">
                            <label class="fw-bold" style="font-size:.78rem">الاسم الكامل <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="benef_name" class="form-control">
                        </div>
                    </div>

                    <div class="row g-2 mt-1">
                        <div class="col-md-6">
                            <label class="fw-bold" style="font-size:.78rem">رقم الجوال</label>
                            <input type="text" name="phone" id="benef_phone" class="form-control" style="direction:ltr">
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold" style="font-size:.78rem">نسبة الإرث %</label>
                            <input type="number" name="pct_share" id="benef_pct_share" class="form-control" step="0.01" min="0" max="100">
                        </div>
                    </div>

                    <div class="row g-2 mt-1">
                        <div class="col-md-12">
                            <label class="fw-bold" style="font-size:.78rem">ملاحظات</label>
                            <input type="text" name="notes" id="benef_notes" class="form-control">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer py-2">
                <button class="btn btn-light btn-sm" data-bs-dismiss="modal">إلغاء</button>
                <button class="btn btn-warning btn-sm" data-action="benef-save"><i class="fa fa-save me-1"></i> حفظ</button>
            </div>
        </div>
    </div>
</div>


<?php echo AntiForgeryToken(); ?>

<!-- بيانات الصفحة كـ JSON خام (آمن من XSS — لا يُنفَّذ كـ JS) -->
<script type="application/json" id="paPageData"><?= json_encode($page_data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?></script>

<script>
(function waitJQ(){
    if (typeof jQuery === 'undefined') { setTimeout(waitJQ, 50); return; }
    jQuery(function($){

    // === قراءة البيانات من الـ JSON tag (آمن) ===
    var DATA;
    try {
        DATA = JSON.parse(document.getElementById('paPageData').textContent);
    } catch(err){
        console.error('paPageData parse failed', err);
        return;
    }
    var URLS      = DATA.urls       || {};
    var EMP       = DATA.employee   || {};
    var ACCOUNTS  = DATA.accounts   || [];
    var BENEFS    = DATA.beneficiaries || [];
    var ATT_CAT   = DATA.attach_category_benef || 'payment_benef';

    // === حماية ضد النقر السريع/المزدوج على عمليات الحذف/الإيقاف ===
    var _busy = {};
    function _guard(key){ if(_busy[key]) return false; _busy[key] = true; return true; }
    function _release(key){ setTimeout(function(){ _busy[key] = false; }, 800); }

    // === Helpers ===
    function escHtml(s){
        if(s === null || s === undefined) return '';
        return String(s)
            .replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')
            .replace(/"/g,'&quot;').replace(/'/g,'&#39;');
    }
    function findAccount(id){ return ACCOUNTS.find(function(a){ return String(a.ACC_ID) === String(id); }); }
    function findBenef(id)  { return BENEFS.find(function(b){ return String(b.BENEFICIARY_ID) === String(id); }); }

    // === Render: قائمة الحسابات ===
    function renderAccounts(){
        var $list = $('#accountsList').empty();
        if(ACCOUNTS.length === 0){
            $list.html('<div class="benef-empty"><i class="fa fa-credit-card-alt"></i><div class="lbl">لا يوجد حسابات مسجّلة لهذا الموظف</div><div class="text-muted" style="font-size:.78rem;margin-top:.4rem">اضغط على زر "حساب جديد" أعلاه لإضافة أول حساب</div></div>');
            return;
        }
        ACCOUNTS.forEach(function(a){
            var isWallet  = parseInt(a.PROVIDER_TYPE) === 2;
            var isBenef   = !!a.BENEFICIARY_ID;
            var isActive  = parseInt(a.IS_ACTIVE) === 1;
            var isDefault = parseInt(a.IS_DEFAULT) === 1;
            var splitType = parseInt(a.SPLIT_TYPE) || 3;
            var splitVal  = a.SPLIT_VALUE || '';
            var splitLabel = splitType === 1 ? splitVal+'%' : (splitType === 2 ? splitVal+' ج' : 'كامل الباقي');

            var cls = 'acc-card';
            if(!isActive) cls += ' inactive';
            if(isWallet)  cls += ' wallet';
            if(isBenef)   cls += ' beneficiary';

            // Head: provider name + flags
            var icon = isWallet ? '<i class="fa fa-mobile-alt text-success"></i>' : '<i class="fa fa-university text-primary"></i>';
            var head = '<div class="acc-head"><span class="acc-type">' + icon + ' ' + escHtml(a.PROVIDER_NAME);
            if(a.BRANCH_NAME){
                var brBadge = a.LEGACY_BANK_NO ? ' <span class="badge bg-light text-dark" style="font-size:.7rem;direction:ltr">#' + a.LEGACY_BANK_NO + '</span>' : '';
                head += ' <small>— ' + escHtml(a.BRANCH_NAME) + '</small>' + brBadge;
            }
            head += '</span>';
            if(isDefault) head += '<span class="acc-flag default">⭐ افتراضي</span>';
            if(isBenef)   head += '<span class="acc-flag benef">🎭 ' + escHtml(a.BENEFICIARY_NAME || '') + '</span>';
            if(!isActive) {
                // 🆕 سبب الإيقاف + شهر الإيقاف
                var reason = parseInt(a.INACTIVE_REASON) || 0;
                var rTxt = ({1:'تقاعد',2:'وفاة',3:'فصل',4:'تجميد',5:'تحويل',9:'أخرى'})[reason] || '';
                var im   = (a.INACTIVE_FROM_MONTH || '').toString();
                var imLabel = (im.length === 6) ? (im.substr(4,2) + '/' + im.substr(0,4)) : '';
                var label = '⛔ موقوف';
                if(rTxt)    label += ' · ' + rTxt;
                if(imLabel) label += ' (' + imLabel + ')';
                head += '<span class="acc-flag deactivated" title="سبب الإيقاف' + (imLabel ? ' منذ ' + imLabel : '') + '">' + label + '</span>';
            }
            head += '</div>';

            // Body: account details
            var body = '<div class="acc-body">';
            if(isWallet){
                body += '<i class="fa fa-mobile"></i> <b>' + escHtml(a.WALLET_NUMBER || '—') + '</b>';
            } else {
                body += 'حساب: <b>' + escHtml(a.ACCOUNT_NO || '—') + '</b>';
                if(a.IBAN) body += ' &nbsp;•&nbsp; IBAN: <b>' + escHtml(a.IBAN) + '</b>';
            }
            if(a.OWNER_NAME && a.OWNER_NAME !== EMP.NAME){
                body += '<br><small><i class="fa fa-user-o"></i> صاحب الحساب: <b>' + escHtml(a.OWNER_NAME) + '</b>';
                if(a.OWNER_ID_NO) body += ' (' + escHtml(a.OWNER_ID_NO) + ')';
                body += '</small>';
            }
            body += '</div>';

            // Foot: split-info + actions inline
            var foot = '<div class="acc-foot">';
            foot += '<span class="split-info"><i class="fa fa-sitemap"></i> التوزيع: <b>' + escHtml(splitLabel) + '</b>';
            foot += '<span class="text-muted">&nbsp;•&nbsp;ترتيب: ' + (parseInt(a.SPLIT_ORDER) || 1) + '</span></span>';
            foot += '<div class="acc-actions">';
            if(isActive){
                if(!isDefault){
                    foot += '<button class="btn btn-outline-success" title="تعيين كافتراضي" data-action="acc-set-default" data-id="'+a.ACC_ID+'"><i class="fa fa-star"></i></button>';
                }
                foot += '<button class="btn btn-outline-primary" title="تعديل" data-action="acc-edit" data-id="'+a.ACC_ID+'"><i class="fa fa-pencil"></i></button>';
                foot += '<button class="btn btn-outline-warning" title="إيقاف" data-action="acc-deactivate" data-id="'+a.ACC_ID+'"><i class="fa fa-pause"></i></button>';
            } else {
                foot += '<button class="btn btn-outline-success" title="إعادة تفعيل" data-action="acc-reactivate" data-id="'+a.ACC_ID+'"><i class="fa fa-play"></i></button>';
            }
            foot += '<button class="btn btn-outline-danger" title="حذف" data-action="acc-delete" data-id="'+a.ACC_ID+'"><i class="fa fa-trash"></i></button>';
            foot += '</div></div>';

            $list.append('<div class="' + cls + '" data-acc-id="' + a.ACC_ID + '">' + head + body + foot + '</div>');
        });
    }

    // === Render: قائمة المستفيدين ===
    function renderBenefs(){
        var $list = $('#benefList').empty();
        if(BENEFS.length === 0){
            $list.html('<div class="benef-empty"><i class="fa fa-user-plus"></i><div class="lbl">لا يوجد مستفيدون</div><div class="text-muted" style="font-size:.74rem;margin-top:.3rem">للموظفين المتوفّين أو الورثة فقط</div></div>');
            return;
        }
        BENEFS.forEach(function(b){
            var attCount = parseInt(b.ATTACH_COUNT) || 0;
            var rowCls = 'benef-row' + (attCount === 0 ? ' no-attach' : '');
            var html = '<div class="' + rowCls + '" data-benef-id="' + b.BENEFICIARY_ID + '">';
            html += '<span class="rel-badge">' + escHtml(b.REL_NAME || '') + '</span>';
            html += '<div style="flex:1">';
            html += '<div class="fw-bold" style="font-size:.82rem">' + escHtml(b.NAME || '') + '</div>';
            html += '<div class="text-muted" style="font-size:.7rem">' + escHtml(b.ID_NO || '');
            if(b.PCT_SHARE)      html += ' · ' + escHtml(b.PCT_SHARE) + '%';
            if(b.ACCOUNTS_COUNT) html += ' · ' + escHtml(b.ACCOUNTS_COUNT) + ' حساب';
            html += '</div>';
            // مرفقات (إجباري على الأقل واحد)
            var attCls   = attCount > 0 ? 'att-chip has' : 'att-chip miss';
            var attTitle = attCount > 0
                ? attCount + ' مرفق — اضغط للإدارة'
                : '⚠ لا يوجد مرفق — اضغط لرفع المستندات (مطلوب)';
            var attLabel = attCount > 0
                ? '<i class="fa fa-paperclip"></i> ' + attCount
                : '<i class="fa fa-exclamation-triangle"></i> مرفق';
            html += '<a href="javascript:void(0)" class="' + attCls + '" data-action="benef-attach" data-id="' + b.BENEFICIARY_ID + '" title="' + attTitle + '">' + attLabel + '</a>';
            html += '</div>';
            html += '<div class="d-flex gap-1">';
            html += '<button class="btn btn-sm btn-outline-primary" title="تعديل" data-action="benef-edit" data-id="' + b.BENEFICIARY_ID + '"><i class="fa fa-pencil"></i></button>';
            html += '<button class="btn btn-sm btn-outline-danger" title="حذف" data-action="benef-delete" data-id="' + b.BENEFICIARY_ID + '"><i class="fa fa-trash"></i></button>';
            html += '</div></div>';
            $list.append(html);
        });
    }

    // === إصلاح تلقائي للتوزيع ===
    function autoFixSplits(){
        if(!_guard('auto-fix')) return;
        if(!confirm('سيتم إصلاح التوزيع تلقائياً:\n• ضمان وجود حساب "كامل الباقي"\n• ضمان وجود حساب افتراضي\n• إعادة ترتيب الحسابات\n\nمتابعة؟')){
            _release('auto-fix'); return;
        }
        get_data(URLS.auto_fix, {emp_no: DATA.emp_no}, function(resp){
            var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
            _release('auto-fix');
            if(!j.ok){ danger_msg('خطأ', j.msg); return; }
            success_msg('تم', j.msg);
            if((j.fixed||0) > 0){ reload_Page(); }
        }, 'json');
    }

    // === ربط الحسابات الموجودة بالمستفيدين تلقائياً ===
    function linkAccountsAuto(){
        if(!_guard('link-auto')) return;
        var nl = String.fromCharCode(10);
        if(!confirm('سيُحاول النظام ربط الحسابات الموجودة بالمستفيدين:' + nl +
                    '• مطابقة بهوية صاحب الحساب' + nl +
                    '• ثم مطابقة بالاسم' + nl + nl +
                    'الحسابات على اسم الموظف نفسه لن تتأثر.' + nl +
                    'متابعة؟')){
            _release('link-auto'); return;
        }
        get_data(URLS.link_auto, {emp_no: DATA.emp_no}, function(resp){
            var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
            _release('link-auto');
            if(!j.ok){ danger_msg('خطأ', j.msg); return; }
            success_msg('تم', j.msg);
            if((j.linked||0) > 0){ reload_Page(); }
        }, 'json');
    }

    // === فتح نافذة المرفقات لمستفيد ===
    function openBenefAttach(benefId){
        if(!benefId) return;
        var url = URLS.attach + '/' + encodeURIComponent(benefId) + '/' + encodeURIComponent(ATT_CAT) + '/1';
        if(typeof _showReport === 'function'){
            _showReport(url);
        } else {
            window.open(url, '_blank');
        }
    }

    // === Render: ملخص التوزيع + الشريط البصري ===
    function renderSplitSummary(){
        var actives = ACCOUNTS.filter(function(a){ return parseInt(a.IS_ACTIVE) === 1; });
        var $panel = $('#distPanelRow');
        var $txt   = $('#splitSumText');
        var $wrap  = $('#distVisualWrap');

        // اللوحة كاملة تظهر فقط لو في توزيع فعلي (أكثر من حساب نشط)
        if(actives.length <= 1){
            $panel.hide();
            return;
        }
        $panel.show();
        var sumPct = 0, hasRemain = false;
        actives.forEach(function(a){
            var st = parseInt(a.SPLIT_TYPE) || 3;
            if(st === 1) sumPct   += parseFloat(a.SPLIT_VALUE || 0);
            if(st === 3) hasRemain = true;
        });
        var msgs = [];
        msgs.push(sumPct > 100
            ? '<span class="bad">❌ مجموع النسب يتجاوز 100% (' + sumPct.toFixed(1) + '%)</span>'
            : '<span class="ok">نسبة: ' + sumPct.toFixed(1) + '%</span>');
        msgs.push(hasRemain
            ? '<span class="ok">✅ يوجد حساب للباقي</span>'
            : '<span class="bad">❌ يجب أن يكون هناك حساب من نوع "الباقي"</span>');
        $txt.html(msgs.join(' · '));

        // === الشريط البصري ===
        renderDistBar(actives, sumPct);
        $wrap.show();
    }

    function renderDistBar(actives, sumPct){
        var pctAccs = [], fixedAccs = [], remainAccs = [];
        actives.forEach(function(a){
            var st = parseInt(a.SPLIT_TYPE) || 3;
            if(st === 1) pctAccs.push(a);
            else if(st === 2) fixedAccs.push(a);
            else remainAccs.push(a);
        });
        var remainShare = Math.max(0, 100 - sumPct);
        var remainEach  = remainAccs.length > 0 ? remainShare / remainAccs.length : 0;

        function colorFor(a){
            if(a.BENEFICIARY_ID) return 'benef';
            if(parseInt(a.PROVIDER_TYPE) === 2) return 'wallet';
            return 'bank';
        }
        function labelFor(a){
            var name = a.PROVIDER_NAME || '';
            if(a.OWNER_NAME && a.OWNER_NAME !== EMP.NAME) name += ' — ' + a.OWNER_NAME;
            return name;
        }

        var bar = '';
        pctAccs.forEach(function(a){
            var pct = parseFloat(a.SPLIT_VALUE) || 0;
            if(pct <= 0) return;
            bar += '<div class="dist-seg ' + colorFor(a) + '" style="width:' + pct + '%" title="' +
                escHtml(labelFor(a)) + ': ' + pct + '%">' + (pct >= 8 ? pct + '%' : '') + '</div>';
        });
        remainAccs.forEach(function(a){
            if(remainEach <= 0.01) return;
            bar += '<div class="dist-seg is-rem ' + colorFor(a) + '" style="width:' + remainEach + '%" title="' +
                escHtml(labelFor(a)) + ': الباقي (~' + remainEach.toFixed(1) + '%)">' + (remainEach >= 8 ? '⭐' : '') + '</div>';
        });
        // تحذير لو في نسبة "ضائعة" بدون حساب باقي
        var leftover = 100 - sumPct;
        if(remainAccs.length === 0 && leftover > 0.5){
            bar += '<div class="dist-seg" style="width:' + leftover + '%;background:#fee2e2;color:#991b1b;text-shadow:none" title="غير موزّع: ' + leftover.toFixed(1) + '%">⚠ ' + leftover.toFixed(1) + '%</div>';
        }
        $('#distBar').html(bar);

        // المبالغ الثابتة (لا تظهر في الشريط — تُعرض كـ chips)
        var fixed = '';
        fixedAccs.forEach(function(a){
            fixed += '<span class="dist-chip" title="' + escHtml(labelFor(a)) + '">' +
                escHtml(a.PROVIDER_NAME || '') + ': <b>' + escHtml(a.SPLIT_VALUE) + '</b> (مبلغ ثابت)</span>';
        });
        $('#distFixed').html(fixed);
    }

    // === فتح/تعديل modal الحساب ===
    function openAccountModal(acc){
        var isEdit = !!acc;
        $('#accModalTitle').text(isEdit ? 'تعديل حساب #' + acc.ACC_ID : 'حساب جديد');
        $('#accForm')[0].reset();
        $('#acc_id').val(isEdit ? acc.ACC_ID : '');
        if(isEdit){
            $('#acc_provider_id').val(acc.PROVIDER_ID);
            $('#acc_account_no').val(acc.ACCOUNT_NO || '');
            $('#acc_iban').val(acc.IBAN || '');
            $('#acc_wallet_number').val(acc.WALLET_NUMBER || '');
            $('#acc_is_default').prop('checked', parseInt(acc.IS_DEFAULT||0) === 1);
            $('#acc_split_type').val(acc.SPLIT_TYPE || 3);
            $('#acc_split_value').val(acc.SPLIT_VALUE || '');
            $('#acc_split_order').val(acc.SPLIT_ORDER || 1);
            $('#acc_notes').val(acc.NOTES || '');

            // تحديد النوع — 3 حالات:
            //   1) acc.BENEFICIARY_ID موجود → benef_<ID>  (مستفيد محدد)
            //   2) لا → owner_id أو name = بيانات الموظف → self
            //   3) لا → other (شخص آخر يدوي)
            // 🆕 matching مرن — يتجاوز اختلاف whitespace/spaces
            var _norm = function(s){ return String(s || '').replace(/\s+/g, ' ').trim(); };
            var accId   = _norm(acc.OWNER_ID_NO);
            var accName = _norm(acc.OWNER_NAME);
            var empId   = _norm(EMP.ID_NO);
            var empName = _norm(EMP.NAME);
            var ownIdSame   = (accId !== '' && accId === empId);
            var ownNameSame = (accName !== '' && accName === empName);
            var isBenef = !!acc.BENEFICIARY_ID;
            // self = ID matches OR (no ID and name matches) OR (both empty — افتراضي)
            var isSelf  = !isBenef && (
                ownIdSame ||
                (accId === '' && ownNameSame) ||
                (accId === '' && accName === '')
            );
            var kind    = isBenef ? ('benef_' + acc.BENEFICIARY_ID) : (isSelf ? 'self' : 'other');

            $('#acc_owner_kind').val(kind);
            $('#acc_beneficiary_id').val(acc.BENEFICIARY_ID || '');

            if (isBenef || isSelf){
                $('#acc_owner_id_no').val(acc.OWNER_ID_NO || '').prop('readonly', true);
                $('#acc_owner_name').val(acc.OWNER_NAME  || '').prop('readonly', true);
                $('#acc_owner_phone').val(acc.OWNER_PHONE || '').prop('readonly', true);
            } else {
                $('#acc_owner_id_no').val(acc.OWNER_ID_NO || '').prop('readonly', false);
                $('#acc_owner_name').val(acc.OWNER_NAME  || '').prop('readonly', false);
                $('#acc_owner_phone').val(acc.OWNER_PHONE || '').prop('readonly', false);
            }

            applyProviderType(function(){ $('#acc_branch_id').val(acc.BRANCH_ID || ''); });
        } else {
            $('#acc_owner_kind').val('self');
            applyOwnerKind();
            applyProviderType();
        }
        applySplitType();
        $('#accModal').modal('show');
    }

    function applyProviderType(afterCb){
        var opt = $('#acc_provider_id').find('option:selected');
        var type = parseInt(opt.data('type') || 0);
        if(type === 2){
            $('#bank_fields').hide();
            $('#wallet_fields').show();
            $('#branch_grp').hide();
            if(typeof afterCb === 'function') afterCb();
        } else if(type === 1){
            $('#bank_fields').show();
            $('#wallet_fields').hide();
            $('#branch_grp').show();
            var pid = $('#acc_provider_id').val();
            if(pid){
                get_data(URLS.branches, {provider_id: pid}, function(resp){
                    var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
                    if(!j || !j.ok){ if(typeof afterCb === 'function') afterCb(); return; }
                    var html = '<option value="">— اختر الفرع —</option>';
                    (j.data || []).forEach(function(b){
                        var prefix = b.LEGACY_BANK_NO ? (b.LEGACY_BANK_NO + ' — ') : '';
                        html += '<option value="' + b.BRANCH_ID + '">' + escHtml(prefix + (b.BRANCH_NAME || '')) + '</option>';
                    });
                    $('#acc_branch_id').html(html);
                    if(typeof afterCb === 'function') afterCb();
                }, 'json');
            } else if(typeof afterCb === 'function') afterCb();
        } else {
            $('#bank_fields').show();
            $('#wallet_fields').hide();
            $('#branch_grp').show();
            if(typeof afterCb === 'function') afterCb();
        }
    }

    function applyOwnerKind(){
        var k = $('#acc_owner_kind').val();
        if(k === 'self'){
            // الموظف نفسه: تعبئة تلقائية + readonly
            $('#acc_beneficiary_id').val('');
            $('#acc_owner_id_no').val(EMP.ID_NO || '').prop('readonly', true);
            $('#acc_owner_name').val(EMP.NAME  || '').prop('readonly', true);
            $('#acc_owner_phone').val(EMP.TEL  || '').prop('readonly', true);
        } else if(k === 'other'){
            // شخص آخر: تفريغ + editable للإدخال اليدوي
            $('#acc_beneficiary_id').val('');
            $('#acc_owner_id_no').val('').prop('readonly', false).focus();
            $('#acc_owner_name').val('').prop('readonly', false);
            $('#acc_owner_phone').val('').prop('readonly', false);
        } else if(String(k).indexOf('benef_') === 0){
            // مستفيد: تعبئة من بيانات المستفيد + readonly + ربط beneficiary_id
            var opt = $('#acc_owner_kind option:selected');
            $('#acc_beneficiary_id').val(opt.data('bid') || '');
            $('#acc_owner_id_no').val(opt.data('id')    || '').prop('readonly', true);
            $('#acc_owner_name').val(opt.data('name')   || '').prop('readonly', true);
            $('#acc_owner_phone').val(opt.data('phone') || '').prop('readonly', true);
        }
    }

    function applySplitType(){
        $('#split_value_grp').toggle($('#acc_split_type').val() !== '3');
    }

    // === فتح/تعديل modal المستفيد ===
    function openBenefModal(b){
        var isEdit = !!b;
        $('#benefModalTitle').text(isEdit ? 'تعديل مستفيد' : 'مستفيد جديد');
        $('#benefForm')[0].reset();
        $('#benef_id').val(isEdit ? b.BENEFICIARY_ID : '');
        if(isEdit){
            $('#benef_rel_type').val(b.REL_TYPE);
            $('#benef_id_no').val(b.ID_NO || '');
            $('#benef_name').val(b.NAME || '');
            $('#benef_phone').val(b.PHONE || '');
            $('#benef_pct_share').val(b.PCT_SHARE || '');
            $('#benef_notes').val(b.NOTES || '');
        }
        $('#benefModal').modal('show');
    }

    // === عمليات الحفظ/الحذف (مع _guard على كل العمليات الحرجة) ===
    function saveAccount(){
        if(!$('#acc_provider_id').val()){ danger_msg('تنبيه','اختر المزود'); return; }
        if(!_guard('acc-save')) return;
        get_data(URLS.acc_save, $('#accForm').serialize(), function(resp){
            var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
            _release('acc-save');
            if(j.ok){ success_msg('تم', j.msg); $('#accModal').modal('hide'); reload_Page(); }
            else    { danger_msg('خطأ', j.msg); }
        }, 'json');
    }

    function deleteAccount(id){
        if(!_guard('acc-del-'+id)) return;
        if(!confirm('حذف هذا الحساب؟')){ _release('acc-del-'+id); return; }
        get_data(URLS.acc_del, {acc_id: id}, function(resp){
            var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
            _release('acc-del-'+id);
            if(j.ok){ success_msg('تم', j.msg); reload_Page(); }
            else    { danger_msg('خطأ', j.msg); }
        }, 'json');
    }

    function deactivateAccount(id){
        // افتح modal الإيقاف بدل prompt() المتعدّد
        $('#deact_acc_id').val(id);
        $('#deact_reason').val('');           // فاضي → يجبر المستخدم يختار بوعي
        $('#deact_notes').val('');
        $('#deact_month').val('');            // 🆕
        bootstrap.Modal.getOrCreateInstance(document.getElementById('deactAccModal')).show();
    }

    // confirm handler للـ modal
    $(document).on('click', '#deactAccConfirm', function(){
        var id     = $('#deact_acc_id').val();
        var reason = $('#deact_reason').val();
        var notes  = $('#deact_notes').val() || '';
        // 🆕 شهر الإيقاف: input type="month" يرجع "YYYY-MM" → نحوّله لـ "YYYYMM"
        var raw_month   = $('#deact_month').val() || '';
        var inact_month = raw_month ? raw_month.replace('-', '') : '';

        if(!reason){ warning_msg('تنبيه', 'اختر سبب الإيقاف'); return; }
        if(!_guard('acc-deact-'+id)) return;

        var $btn = $('#deactAccConfirm');
        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i> جاري الإيقاف...');

        get_data(URLS.acc_deact, {
            acc_id: id, reason: reason, notes: notes, inact_month: inact_month
        }, function(resp){
            var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
            _release('acc-deact-'+id);
            $btn.prop('disabled', false).html('<i class="fa fa-pause me-1"></i> تأكيد الإيقاف');
            if(j.ok){
                bootstrap.Modal.getOrCreateInstance(document.getElementById('deactAccModal')).hide();
                success_msg('تم', j.msg);
                reload_Page();
            } else {
                danger_msg('خطأ', j.msg);
            }
        }, 'json');
    });

    function reactivateAccount(id){
        if(!_guard('acc-react-'+id)) return;
        if(!confirm('إعادة تفعيل هذا الحساب؟')){ _release('acc-react-'+id); return; }
        get_data(URLS.acc_react, {acc_id: id, notes: 'reactivate'}, function(resp){
            var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
            _release('acc-react-'+id);
            if(j.ok){ success_msg('تم', j.msg); reload_Page(); }
            else    { danger_msg('خطأ', j.msg); }
        }, 'json');
    }

    function setDefault(id){
        if(!_guard('acc-def-'+id)) return;
        get_data(URLS.acc_default, {acc_id: id}, function(resp){
            var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
            _release('acc-def-'+id);
            if(j.ok){ success_msg('تم', j.msg); reload_Page(); }
            else    { danger_msg('خطأ', j.msg); }
        }, 'json');
    }

    function saveBenef(){
        if(!$('#benef_name').val().trim()){ danger_msg('تنبيه','الاسم مطلوب'); return; }
        if(!$('#benef_id_no').val().trim()){ danger_msg('تنبيه','الهوية مطلوبة'); return; }
        if(!_guard('benef-save')) return;
        get_data(URLS.benef_save, $('#benefForm').serialize(), function(resp){
            var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
            _release('benef-save');
            if(!j.ok){ danger_msg('خطأ', j.msg); return; }
            success_msg('تم', j.msg);
            $('#benefModal').modal('hide');
            // ملاحظة: المرفقات تُرفع يدوياً من زر "المرفقات" بجانب المستفيد
            // (سابقاً كانت تُفتح تلقائياً — أُلغي بناءً على طلب المحاسب)
            reload_Page();
        }, 'json');
    }

    function deleteBenef(id){
        if(!_guard('benef-del-'+id)) return;
        if(!confirm('حذف هذا المستفيد؟\n(لا يمكن الحذف لو عنده حسابات نشطة)')){ _release('benef-del-'+id); return; }
        get_data(URLS.benef_del, {benef_id: id}, function(resp){
            var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
            _release('benef-del-'+id);
            if(j.ok){ success_msg('تم', j.msg); reload_Page(); }
            else    { danger_msg('خطأ', j.msg); }
        }, 'json');
    }

    // === Event delegation: كل الأزرار تمر من هنا (لا inline onclick) ===
    $(document).on('click', '[data-action]', function(e){
        var $btn = $(this);
        var action = $btn.data('action');
        var id = $btn.data('id');
        switch(action){
            case 'acc-new':         openAccountModal();                   break;
            case 'acc-edit':        openAccountModal(findAccount(id));    break;
            case 'acc-delete':      deleteAccount(id);                    break;
            case 'acc-deactivate':  deactivateAccount(id);                break;
            case 'acc-reactivate':  reactivateAccount(id);                break;
            case 'acc-set-default': setDefault(id);                       break;
            case 'acc-save':        saveAccount();                        break;
            case 'benef-new':       openBenefModal();                     break;
            case 'benef-edit':      openBenefModal(findBenef(id));        break;
            case 'benef-delete':    deleteBenef(id);                      break;
            case 'benef-save':      saveBenef();                          break;
            case 'benef-attach':    openBenefAttach(id);                  break;
            case 'auto-fix':        autoFixSplits();                      break;
            case 'link-auto':       linkAccountsAuto();                   break;
        }
    });

    // === Modal-internal listeners ===
    $('#acc_provider_id').on('change', function(){ applyProviderType(); });
    $('#acc_owner_kind').on('change',  function(){ applyOwnerKind();    });
    $('#acc_split_type').on('change',  function(){ applySplitType();    });

    // === Initial render ===
    renderAccounts();
    renderBenefs();
    renderSplitSummary();

    });  // jQuery(function($){
})();    // waitJQ
</script>
