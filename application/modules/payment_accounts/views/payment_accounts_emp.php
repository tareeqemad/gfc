<?php
$MODULE_NAME = 'payment_accounts';
$TB_NAME     = 'payment_accounts';

$back_url           = base_url("$MODULE_NAME/$TB_NAME");
$acc_save_url       = base_url("$MODULE_NAME/$TB_NAME/account_save");
$acc_del_url        = base_url("$MODULE_NAME/$TB_NAME/account_delete");
$acc_deact_url      = base_url("$MODULE_NAME/$TB_NAME/account_deactivate");
$acc_react_url      = base_url("$MODULE_NAME/$TB_NAME/account_reactivate");
$acc_default_url    = base_url("$MODULE_NAME/$TB_NAME/account_set_default");
$benef_save_url     = base_url("$MODULE_NAME/$TB_NAME/benef_save");
$benef_del_url      = base_url("$MODULE_NAME/$TB_NAME/benef_delete");
$branches_json_url  = base_url("$MODULE_NAME/$TB_NAME/branches_list_json");

$e = $emp_data ?? [];
$emp_is_active = (int)($e['IS_ACTIVE'] ?? 0);

$accounts_arr      = is_array($accounts)       ? $accounts       : [];
$beneficiaries_arr = is_array($beneficiaries)  ? $beneficiaries  : [];
$providers_arr     = is_array($providers)      ? $providers      : [];
?>

<style>
.emp-hdr{padding:.6rem 1rem;background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;margin-bottom:.75rem}
.emp-hdr .name{font-size:1.05rem;font-weight:800;color:#1e293b}
.emp-hdr .meta{font-size:.78rem;color:#64748b;margin-top:.25rem}
.emp-hdr .meta span{margin-inline-end:1rem}
.status-pill{display:inline-block;padding:.15em .7em;border-radius:6px;font-size:.72rem;font-weight:700}
.status-pill.active{background:#d1fae5;color:#065f46}
.status-pill.retired{background:#f1f5f9;color:#64748b}

.acc-card{border:1px solid #e2e8f0;border-radius:10px;padding:.75rem 1rem;margin-bottom:.6rem;background:#fff;position:relative}
.acc-card.inactive{opacity:.7;background:#fafafa}
.acc-card.wallet{background:#faf5ff;border-color:#e9d5ff}
.acc-card.beneficiary{background:#fffbeb;border-color:#fde68a}
.acc-card .acc-head{display:flex;align-items:center;gap:.5rem;margin-bottom:.45rem;flex-wrap:wrap}
.acc-card .acc-type{font-size:.9rem;font-weight:700}
.acc-card .acc-flag{font-size:.68rem;padding:.1em .55em;border-radius:5px;font-weight:600}
.acc-card .acc-flag.default{background:#22c55e;color:#fff}
.acc-card .acc-flag.deactivated{background:#fee2e2;color:#991b1b}
.acc-card .acc-flag.benef{background:#fde68a;color:#92400e}
.acc-card .acc-body{font-size:.82rem;color:#475569;line-height:1.8}
.acc-card .acc-body b{color:#1e293b;direction:ltr;display:inline-block}
.acc-card .acc-actions{position:absolute;left:.75rem;top:.75rem;display:flex;gap:.25rem}
.split-info{background:#f8fafc;padding:.35rem .75rem;border-radius:6px;font-size:.78rem;margin-top:.35rem;display:inline-block}
.split-info b{color:#7c3aed;font-weight:700}

.sum-line{display:flex;gap:.5rem;flex-wrap:wrap;padding:.55rem .85rem;background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;margin-top:.5rem;font-size:.82rem}
.sum-line .ok  {color:#059669;font-weight:600}
.sum-line .bad {color:#dc2626;font-weight:600}

.benef-row{display:flex;align-items:center;gap:.5rem;padding:.55rem .75rem;border:1px solid #e2e8f0;border-radius:8px;margin-bottom:.4rem;background:#fff;font-size:.82rem}
.benef-row .rel-badge{background:#fef3c7;color:#92400e;padding:.15em .55em;border-radius:5px;font-size:.7rem;font-weight:700}
</style>

<div class="page-header">
    <div><h1 class="page-title"><?= $title ?></h1></div>
    <div class="ms-auto pageheader-btn">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= $back_url ?>">إدارة حسابات الصرف</a></li>
            <li class="breadcrumb-item active"><?= $e['EMP_NAME'] ?? $emp_no ?></li>
        </ol>
    </div>
</div>

<div class="row"><div class="col-lg-12"><div class="card">
    <div class="card-header d-flex align-items-center flex-wrap gap-2">
        <h3 class="card-title mb-0"><i class="fa fa-user me-2"></i> <?= $e['EMP_NAME'] ?? $emp_no ?></h3>
        <div class="ms-auto">
            <a href="<?= $back_url ?>" class="btn btn-light btn-sm"><i class="fa fa-arrow-right me-1"></i> رجوع</a>
        </div>
    </div>
    <div class="card-body">

    <!-- بطاقة بيانات الموظف -->
    <div class="emp-hdr">
        <div class="name">
            <?= $e['EMP_NO'] ?? '' ?> — <?= $e['EMP_NAME'] ?? '' ?>
            <?php if ($emp_is_active == 1): ?>
                <span class="status-pill active">فعّال</span>
            <?php else: ?>
                <span class="status-pill retired">متقاعد</span>
            <?php endif; ?>
        </div>
        <div class="meta">
            <span><i class="fa fa-id-card"></i> <b><?= $e['ID_NO'] ?? '—' ?></b></span>
            <span><i class="fa fa-phone"></i> <b style="direction:ltr"><?= $e['TEL'] ?? '—' ?></b></span>
            <span><i class="fa fa-building"></i> <?= $e['BRANCH_NAME'] ?? '—' ?></span>
        </div>
    </div>

    <div class="row g-3">
        <!-- ═══ القسم 1: حسابات الصرف ═══ -->
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="mb-0" style="font-size:.95rem"><i class="fa fa-credit-card me-1"></i> حسابات الصرف</h5>
                <div class="d-flex gap-1">
                    <button class="btn btn-primary btn-sm" onclick="openAccountModal()"><i class="fa fa-plus"></i> حساب جديد</button>
                </div>
            </div>

            <div id="accountsList">
                <?php if (count($accounts_arr) === 0): ?>
                    <div class="alert alert-warning py-2 mb-0" style="font-size:.85rem">
                        <i class="fa fa-exclamation-triangle me-1"></i> لا يوجد حسابات مسجّلة لهذا الموظف — أضف حساباً جديداً
                    </div>
                <?php else: foreach ($accounts_arr as $a):
                    $is_wallet = ((int)($a['PROVIDER_TYPE'] ?? 0) == 2);
                    $is_benef  = !empty($a['BENEFICIARY_ID']);
                    $is_active = ((int)($a['IS_ACTIVE'] ?? 0) == 1);
                    $is_default= ((int)($a['IS_DEFAULT'] ?? 0) == 1);
                    $split_type= (int)($a['SPLIT_TYPE'] ?? 3);
                    $split_val = $a['SPLIT_VALUE'] ?? '';
                    $split_label = $split_type == 1 ? $split_val.'%' : ($split_type == 2 ? n_format((float)$split_val) : 'الباقي');

                    $cls = 'acc-card';
                    if (!$is_active) $cls .= ' inactive';
                    if ($is_wallet)  $cls .= ' wallet';
                    if ($is_benef)   $cls .= ' beneficiary';
                ?>
                <div class="<?= $cls ?>" data-acc-id="<?= $a['ACC_ID'] ?>">
                    <div class="acc-actions">
                        <?php if ($is_active): ?>
                            <?php if (!$is_default): ?>
                                <button class="btn btn-sm btn-outline-success" title="تعيين كافتراضي" onclick='setDefault(<?= $a['ACC_ID'] ?>)'><i class="fa fa-star"></i></button>
                            <?php endif; ?>
                            <button class="btn btn-sm btn-outline-primary" title="تعديل" onclick='editAccount(<?= json_encode($a, JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT) ?>)'><i class="fa fa-pencil"></i></button>
                            <button class="btn btn-sm btn-outline-warning" title="إيقاف" onclick='deactivateAccount(<?= $a['ACC_ID'] ?>)'><i class="fa fa-pause"></i></button>
                        <?php else: ?>
                            <button class="btn btn-sm btn-outline-success" title="إعادة تفعيل" onclick='reactivateAccount(<?= $a['ACC_ID'] ?>)'><i class="fa fa-play"></i></button>
                        <?php endif; ?>
                        <button class="btn btn-sm btn-outline-danger" title="حذف" onclick='deleteAccount(<?= $a['ACC_ID'] ?>)'><i class="fa fa-trash"></i></button>
                    </div>

                    <div class="acc-head">
                        <span class="acc-type">
                            <?= $is_wallet ? '📱' : '🏦' ?>
                            <?= htmlspecialchars($a['PROVIDER_NAME'] ?? '') ?>
                            <?php if (!empty($a['BRANCH_NAME'])): ?>
                                <small class="text-muted">— <?= htmlspecialchars($a['BRANCH_NAME']) ?></small>
                            <?php endif; ?>
                        </span>
                        <?php if ($is_default): ?>
                            <span class="acc-flag default">⭐ افتراضي</span>
                        <?php endif; ?>
                        <?php if ($is_benef): ?>
                            <span class="acc-flag benef">🎭 مستفيد: <?= htmlspecialchars($a['BENEFICIARY_NAME'] ?? '') ?></span>
                        <?php endif; ?>
                        <?php if (!$is_active): ?>
                            <span class="acc-flag deactivated">⛔ موقوف</span>
                        <?php endif; ?>
                    </div>

                    <div class="acc-body">
                        <?php if ($is_wallet): ?>
                            📱 <b><?= htmlspecialchars($a['WALLET_NUMBER'] ?? '—') ?></b>
                        <?php else: ?>
                            حساب: <b><?= htmlspecialchars($a['ACCOUNT_NO'] ?? '—') ?></b>
                            <?php if (!empty($a['IBAN'])): ?>
                                &nbsp;•&nbsp; IBAN: <b><?= htmlspecialchars($a['IBAN']) ?></b>
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php if (!empty($a['OWNER_NAME']) && $a['OWNER_NAME'] != ($e['EMP_NAME'] ?? '')): ?>
                            <br>صاحب الحساب: <b><?= htmlspecialchars($a['OWNER_NAME']) ?></b>
                            <?php if (!empty($a['OWNER_ID_NO'])): ?> (<?= $a['OWNER_ID_NO'] ?>)<?php endif; ?>
                        <?php endif; ?>
                    </div>

                    <div class="split-info">
                        <i class="fa fa-sitemap"></i> التوزيع: <b><?= $split_label ?></b>
                        <span class="text-muted">&nbsp;•&nbsp;ترتيب: <?= (int)($a['SPLIT_ORDER'] ?? 1) ?></span>
                    </div>
                </div>
                <?php endforeach; endif; ?>
            </div>

            <!-- ملخص التوزيع -->
            <div class="sum-line" id="splitSumLine">
                <span>🧮 ملخص التوزيع:</span>
                <span id="splitSumText">جارٍ الحساب...</span>
            </div>
        </div>

        <!-- ═══ القسم 2: المستفيدون ═══ -->
        <div class="col-lg-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="mb-0" style="font-size:.95rem"><i class="fa fa-users me-1"></i> المستفيدون (ورثة)</h5>
                <button class="btn btn-warning btn-sm" onclick="openBenefModal()"><i class="fa fa-plus"></i> مستفيد</button>
            </div>

            <div id="benefList">
                <?php if (count($beneficiaries_arr) === 0): ?>
                    <div class="alert alert-light text-muted py-2 mb-0" style="font-size:.82rem;text-align:center">
                        لا يوجد مستفيدون
                    </div>
                <?php else: foreach ($beneficiaries_arr as $b): ?>
                <div class="benef-row" data-benef-id="<?= $b['BENEFICIARY_ID'] ?>">
                    <span class="rel-badge"><?= htmlspecialchars($b['REL_NAME'] ?? '') ?></span>
                    <div style="flex:1">
                        <div class="fw-bold" style="font-size:.82rem"><?= htmlspecialchars($b['NAME'] ?? '') ?></div>
                        <div class="text-muted" style="font-size:.7rem">
                            <?= $b['ID_NO'] ?? '' ?>
                            <?php if (!empty($b['PCT_SHARE'])): ?> · <?= $b['PCT_SHARE'] ?>%<?php endif; ?>
                            <?php if (!empty($b['ACCOUNTS_COUNT'])): ?> · <?= $b['ACCOUNTS_COUNT'] ?> حساب<?php endif; ?>
                        </div>
                    </div>
                    <div class="d-flex gap-1">
                        <button class="btn btn-sm btn-outline-primary" title="تعديل" onclick='editBenef(<?= json_encode($b, JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT) ?>)'><i class="fa fa-pencil"></i></button>
                        <button class="btn btn-sm btn-outline-danger" title="حذف" onclick="deleteBenef(<?= $b['BENEFICIARY_ID'] ?>)"><i class="fa fa-trash"></i></button>
                    </div>
                </div>
                <?php endforeach; endif; ?>
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
                <form id="accForm">
                    <input type="hidden" id="acc_id" name="acc_id">
                    <input type="hidden" name="emp_no" value="<?= $emp_no ?>">

                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="fw-bold" style="font-size:.78rem">المزود <span class="text-danger">*</span></label>
                            <select name="provider_id" id="acc_provider_id" class="form-select" onchange="onProviderChange()">
                                <option value="">— اختر —</option>
                                <?php foreach ($providers_arr as $p): ?>
                                    <option value="<?= $p['PROVIDER_ID'] ?>" data-type="<?= $p['PROVIDER_TYPE'] ?>">
                                        <?= ($p['PROVIDER_TYPE'] == 2 ? '📱 ' : '🏦 ') . htmlspecialchars($p['PROVIDER_NAME']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6" id="branch_grp">
                            <label class="fw-bold" style="font-size:.78rem">الفرع</label>
                            <select name="branch_id" id="acc_branch_id" class="form-select">
                                <option value="">— اختر الفرع —</option>
                            </select>
                        </div>
                    </div>

                    <!-- حقول البنك -->
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

                    <!-- حقول المحفظة -->
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
                            <select id="acc_owner_kind" class="form-select" onchange="onOwnerKindChange()">
                                <option value="self">الموظف نفسه</option>
                                <?php foreach ($beneficiaries_arr as $b): ?>
                                    <option value="<?= $b['BENEFICIARY_ID'] ?>" data-name="<?= htmlspecialchars($b['NAME']) ?>" data-id="<?= $b['ID_NO'] ?>" data-phone="<?= $b['PHONE'] ?>">
                                        <?= htmlspecialchars($b['REL_NAME'] . ' — ' . $b['NAME']) ?>
                                    </option>
                                <?php endforeach; ?>
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
                            <select name="split_type" id="acc_split_type" class="form-select" onchange="onSplitTypeChange()">
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
                <button class="btn btn-primary btn-sm" onclick="saveAccount()"><i class="fa fa-save me-1"></i> حفظ</button>
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
                <form id="benefForm">
                    <input type="hidden" id="benef_id" name="benef_id">
                    <input type="hidden" name="emp_no" value="<?= $emp_no ?>">

                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="fw-bold" style="font-size:.78rem">نوع القرابة <span class="text-danger">*</span></label>
                            <select name="rel_type" id="benef_rel_type" class="form-select">
                                <option value="1">👰 زوجة</option>
                                <option value="2">👦 ابن</option>
                                <option value="3">👧 بنت</option>
                                <option value="4">👨‍🦳 أب</option>
                                <option value="5">👩‍🦳 أم</option>
                                <option value="9">🎭 وريث آخر</option>
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
                <button class="btn btn-warning btn-sm" onclick="saveBenef()"><i class="fa fa-save me-1"></i> حفظ</button>
            </div>
        </div>
    </div>
</div>


<?php echo AntiForgeryToken(); ?>

<script>
(function waitJQ(){
    if (typeof jQuery === 'undefined') { setTimeout(waitJQ, 50); return; }
    jQuery(function($){

var empNo = <?= (int)$emp_no ?>;
var accSaveUrl    = "<?= $acc_save_url ?>";
var accDelUrl     = "<?= $acc_del_url ?>";
var accDeactUrl   = "<?= $acc_deact_url ?>";
var accReactUrl   = "<?= $acc_react_url ?>";
var accDefaultUrl = "<?= $acc_default_url ?>";
var benefSaveUrl  = "<?= $benef_save_url ?>";
var benefDelUrl   = "<?= $benef_del_url ?>";
var branchesUrl   = "<?= $branches_json_url ?>";

var _providers = <?= json_encode(array_map(function($p){
    return [
        'PROVIDER_ID'   => $p['PROVIDER_ID'],
        'PROVIDER_NAME' => $p['PROVIDER_NAME'],
        'PROVIDER_TYPE' => (int)$p['PROVIDER_TYPE'],
    ];
}, $providers_arr)) ?>;

// ═══════════════ ACCOUNT ═══════════════

window.openAccountModal = function(){
    $('#accModalTitle').text('حساب جديد');
    $('#accForm')[0].reset();
    $('#acc_id').val('');
    $('#acc_provider_id').val('');
    $('#acc_branch_id').html('<option value="">— اختر الفرع —</option>');
    $('#acc_owner_kind').val('self');
    onOwnerKindChange();
    onSplitTypeChange();
    onProviderChange();
    $('#accModal').modal('show');
}

window.editAccount = function(a){
    $('#accModalTitle').text('تعديل حساب #' + a.ACC_ID);
    $('#acc_id').val(a.ACC_ID);
    $('#acc_provider_id').val(a.PROVIDER_ID);
    $('#acc_account_no').val(a.ACCOUNT_NO || '');
    $('#acc_iban').val(a.IBAN || '');
    $('#acc_wallet_number').val(a.WALLET_NUMBER || '');
    $('#acc_owner_id_no').val(a.OWNER_ID_NO || '');
    $('#acc_owner_name').val(a.OWNER_NAME || '');
    $('#acc_owner_phone').val(a.OWNER_PHONE || '');
    $('#acc_is_default').prop('checked', parseInt(a.IS_DEFAULT||0) === 1);
    $('#acc_split_type').val(a.SPLIT_TYPE || 3);
    $('#acc_split_value').val(a.SPLIT_VALUE || '');
    $('#acc_split_order').val(a.SPLIT_ORDER || 1);
    $('#acc_notes').val(a.NOTES || '');
    $('#acc_owner_kind').val(a.BENEFICIARY_ID ? a.BENEFICIARY_ID : 'self');
    $('#acc_beneficiary_id').val(a.BENEFICIARY_ID || '');
    onProviderChange(function(){ $('#acc_branch_id').val(a.BRANCH_ID || ''); });
    onSplitTypeChange();
    $('#accModal').modal('show');
}

window.onProviderChange = function(afterLoadCb){
    var opt = $('#acc_provider_id').find('option:selected');
    var type = parseInt(opt.data('type') || 0);
    if(type === 2){
        $('#bank_fields').hide();
        $('#wallet_fields').show();
        $('#branch_grp').hide();
        if(typeof afterLoadCb === 'function') afterLoadCb();
    } else if(type === 1){
        $('#bank_fields').show();
        $('#wallet_fields').hide();
        $('#branch_grp').show();
        // جلب الفروع للبنك المحدد
        var pid = $('#acc_provider_id').val();
        if(pid){
            get_data(branchesUrl, {provider_id: pid}, function(resp){
                var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
                if(!j.ok) return;
                var html = '<option value="">— اختر الفرع —</option>';
                (j.data||[]).forEach(function(b){
                    html += '<option value="'+b.BRANCH_ID+'">'+ (b.BRANCH_NAME||'') +'</option>';
                });
                $('#acc_branch_id').html(html);
                if(typeof afterLoadCb === 'function') afterLoadCb();
            }, 'json');
        } else {
            if(typeof afterLoadCb === 'function') afterLoadCb();
        }
    } else {
        $('#bank_fields').show();
        $('#wallet_fields').hide();
        $('#branch_grp').show();
        if(typeof afterLoadCb === 'function') afterLoadCb();
    }
}

window.onOwnerKindChange = function(){
    var k = $('#acc_owner_kind').val();
    if(k === 'self'){
        $('#acc_beneficiary_id').val('');
        $('#acc_owner_id_no').val('<?= $e['ID_NO'] ?? '' ?>');
        $('#acc_owner_name').val('<?= addslashes($e['EMP_NAME'] ?? '') ?>');
        $('#acc_owner_phone').val('<?= $e['TEL'] ?? '' ?>');
    } else {
        var opt = $('#acc_owner_kind option:selected');
        $('#acc_beneficiary_id').val(k);
        $('#acc_owner_id_no').val(opt.data('id') || '');
        $('#acc_owner_name').val(opt.data('name') || '');
        $('#acc_owner_phone').val(opt.data('phone') || '');
    }
}

window.onSplitTypeChange = function(){
    var t = $('#acc_split_type').val();
    if(t == '3'){
        $('#split_value_grp').hide();
    } else {
        $('#split_value_grp').show();
    }
}

window.saveAccount = function(){
    var f = $('#accForm').serialize();
    if(!$('#acc_provider_id').val()){ danger_msg('تنبيه','اختر المزود'); return; }
    get_data(accSaveUrl, f, function(resp){
        var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
        if(j.ok){
            success_msg('تم', j.msg);
            $('#accModal').modal('hide');
            reload_Page();
        } else { danger_msg('خطأ', j.msg); }
    }, 'json');
}

window.deleteAccount = function(id){
    if(!confirm('حذف هذا الحساب؟')) return;
    get_data(accDelUrl, {acc_id: id}, function(resp){
        var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
        if(j.ok){ success_msg('تم', j.msg); reload_Page(); }
        else { danger_msg('خطأ', j.msg); }
    }, 'json');
}

window.deactivateAccount = function(id){
    var reason = prompt('سبب الإيقاف:\n1 = تقاعد\n2 = وفاة\n3 = فصل\n4 = تجميد\n5 = تحويل\n9 = أخرى', '4');
    if(reason === null) return;
    var notes = prompt('ملاحظة (اختياري):', '') || '';
    get_data(accDeactUrl, {acc_id: id, reason: reason, notes: notes}, function(resp){
        var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
        if(j.ok){ success_msg('تم', j.msg); reload_Page(); }
        else { danger_msg('خطأ', j.msg); }
    }, 'json');
}

window.reactivateAccount = function(id){
    if(!confirm('إعادة تفعيل هذا الحساب؟')) return;
    get_data(accReactUrl, {acc_id: id, notes: 'reactivate'}, function(resp){
        var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
        if(j.ok){ success_msg('تم', j.msg); reload_Page(); }
        else { danger_msg('خطأ', j.msg); }
    }, 'json');
}

window.setDefault = function(id){
    get_data(accDefaultUrl, {acc_id: id}, function(resp){
        var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
        if(j.ok){ success_msg('تم', j.msg); reload_Page(); }
        else { danger_msg('خطأ', j.msg); }
    }, 'json');
}

// ═══════════════ BENEFICIARY ═══════════════

window.openBenefModal = function(){
    $('#benefModalTitle').text('مستفيد جديد');
    $('#benefForm')[0].reset();
    $('#benef_id').val('');
    $('#benefModal').modal('show');
}

window.editBenef = function(b){
    $('#benefModalTitle').text('تعديل مستفيد');
    $('#benef_id').val(b.BENEFICIARY_ID);
    $('#benef_rel_type').val(b.REL_TYPE);
    $('#benef_id_no').val(b.ID_NO || '');
    $('#benef_name').val(b.NAME || '');
    $('#benef_phone').val(b.PHONE || '');
    $('#benef_pct_share').val(b.PCT_SHARE || '');
    $('#benef_notes').val(b.NOTES || '');
    $('#benefModal').modal('show');
}

window.saveBenef = function(){
    var f = $('#benefForm').serialize();
    if(!$('#benef_name').val().trim()){ danger_msg('تنبيه','الاسم مطلوب'); return; }
    if(!$('#benef_id_no').val().trim()){ danger_msg('تنبيه','الهوية مطلوبة'); return; }
    get_data(benefSaveUrl, f, function(resp){
        var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
        if(j.ok){ success_msg('تم', j.msg); $('#benefModal').modal('hide'); reload_Page(); }
        else { danger_msg('خطأ', j.msg); }
    }, 'json');
}

window.deleteBenef = function(id){
    if(!confirm('حذف هذا المستفيد؟\n(لا يمكن الحذف لو عنده حسابات نشطة)')) return;
    get_data(benefDelUrl, {benef_id: id}, function(resp){
        var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
        if(j.ok){ success_msg('تم', j.msg); reload_Page(); }
        else { danger_msg('خطأ', j.msg); }
    }, 'json');
}

// ═══════════════ ملخص التوزيع ═══════════════
(function(){
    var accs = <?= json_encode(array_values(array_filter($accounts_arr, function($a){ return (int)($a['IS_ACTIVE']??0) === 1; })), JSON_UNESCAPED_UNICODE) ?>;
    if(accs.length === 0){
        $('#splitSumText').html('<span class="bad">⚠ لا توجد حسابات نشطة</span>');
        return;
    }
    if(accs.length === 1){
        $('#splitSumText').html('<span class="ok">✅ حساب واحد نشط — يُصرف كامل المبلغ له</span>');
        return;
    }
    var sumPct = 0, hasRemain = false;
    accs.forEach(function(a){
        var st = parseInt(a.SPLIT_TYPE || 3);
        if(st === 1) sumPct += parseFloat(a.SPLIT_VALUE || 0);
        if(st === 3) hasRemain = true;
    });
    var msgs = [];
    if(sumPct > 100) msgs.push('<span class="bad">❌ مجموع النسب يتجاوز 100% ('+sumPct.toFixed(1)+'%)</span>');
    else msgs.push('<span class="ok">نسبة: '+sumPct.toFixed(1)+'%</span>');
    if(!hasRemain) msgs.push('<span class="bad">❌ يجب أن يكون هناك حساب من نوع "الباقي"</span>');
    else msgs.push('<span class="ok">✅ يوجد حساب للباقي</span>');
    $('#splitSumText').html(msgs.join(' · '));
})();

    });  // jQuery(function($){
})();    // waitJQ
</script>
