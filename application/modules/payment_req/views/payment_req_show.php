<?php
/**
 * Payment Request — Show v2 (Create / Edit / View)
 * - Multi-employee support on create
 * - Wallet cards with visual design
 * - Select2 integration
 */
$MODULE_NAME = 'payment_req';
$TB_NAME     = 'payment_req';

$back_url    = base_url("$MODULE_NAME/$TB_NAME");
$post_url    = $isCreate ? base_url("$MODULE_NAME/$TB_NAME/create") : base_url("$MODULE_NAME/$TB_NAME/edit");
$summary_url = base_url("$MODULE_NAME/$TB_NAME/public_get_summary");
$approve_url = base_url("$MODULE_NAME/$TB_NAME/approve");
$pay_url     = base_url("$MODULE_NAME/$TB_NAME/do_pay");
$delete_url  = base_url("$MODULE_NAME/$TB_NAME/delete");

$HaveRs = isset($master_tb_data) && is_array($master_tb_data) && count($master_tb_data) > 0;
$rs     = $HaveRs ? $master_tb_data[0] : [];

$cur_status    = $HaveRs ? (int)($rs['STATUS'] ?? 1) : -1;
$is_draft      = ($cur_status == 1);
$is_approved   = ($cur_status == 2);
$is_paid       = ($cur_status == 3);
$is_cancelled  = ($cur_status == 0);
$can_edit_form = $isCreate || $is_draft;

$INIT_SALARY_EXISTS_JS = 'null';
if ($HaveRs && isset($rs['SALARY_EXISTS'])) {
    $INIT_SALARY_EXISTS_JS = (int)$rs['SALARY_EXISTS'] === 1 ? '1' : '0';
}

echo AntiForgeryToken();
?>

<style>
/* ═══════════ TOKENS ═══════════ */
:root{--c-blue:#2563eb;--c-purple:#7c3aed;--c-green:#059669;--c-red:#dc2626;--c-amber:#d97706;--c-slate:#64748b;--r:14px;--sh:0 4px 24px rgba(15,23,42,.07)}

/* ═══════════ HEAD ═══════════ */
.pr2-head{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;margin-bottom:1.25rem}
.pr2-head h1{font-size:1.3rem;font-weight:800;color:#1e293b;margin:0;display:flex;align-items:center;gap:.6rem}
.pr2-head .hico{width:38px;height:38px;border-radius:11px;background:linear-gradient(135deg,var(--c-blue),var(--c-purple));color:#fff;display:flex;align-items:center;justify-content:center;font-size:.95rem}
.pr2-head .actions{display:flex;gap:.5rem;flex-wrap:wrap}
.pr2-head .actions .btn{border-radius:10px;font-weight:700;font-size:.82rem;padding:.4rem 1rem}

/* ═══════════ STATUS BAR ═══════════ */
.pr2-status{display:flex;align-items:center;gap:.75rem;padding:.65rem 1.2rem;border-radius:12px;font-weight:700;font-size:.85rem;margin-bottom:1rem}
.pr2-status.s0{background:#fee2e2;color:#991b1b}.pr2-status.s1{background:#fef3c7;color:#92400e}
.pr2-status.s2{background:#dbeafe;color:#1e40af}.pr2-status.s3{background:#d1fae5;color:#065f46}

/* ═══════════ SECTION CARD ═══════════ */
.pr2-card{background:#fff;border:1px solid #e2e8f0;border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;margin-bottom:1.25rem}
.pr2-card-hd{display:flex;align-items:center;gap:.6rem;padding:.75rem 1.25rem;background:#f8fafc;border-bottom:1px solid #e2e8f0;font-weight:700;font-size:.9rem;color:#1e293b}
.pr2-card-hd .ic{width:32px;height:32px;border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:.85rem;color:#fff}
.pr2-card-bd{padding:1.25rem}
.pr2-card label{font-weight:600;font-size:.78rem;color:var(--c-slate);margin-bottom:.3rem;display:block}
.pr2-card .form-control,.pr2-card .form-select{border-radius:10px;border:1px solid #e2e8f0}
.pr2-card .form-control:focus,.pr2-card .form-select:focus{border-color:var(--c-blue);box-shadow:0 0 0 3px rgba(37,99,235,.1)}

/* ═══════════ WALLET CARDS ═══════════ */
.wallet-grid{display:grid;grid-template-columns:1fr 1fr;gap:1rem}
@media(max-width:768px){.wallet-grid{grid-template-columns:1fr}}
.wallet-card{border-radius:16px;padding:1.25rem;position:relative;overflow:hidden;min-height:160px;color:#fff}
.wallet-card::before{content:'';position:absolute;top:-30px;right:-30px;width:120px;height:120px;border-radius:50%;background:rgba(255,255,255,.08)}
.wallet-card::after{content:'';position:absolute;bottom:-20px;left:-20px;width:80px;height:80px;border-radius:50%;background:rgba(255,255,255,.05)}
.wallet-card.w-salary{background:linear-gradient(135deg,#1e40af 0%,#3b82f6 50%,#60a5fa 100%)}
.wallet-card.w-dues{background:linear-gradient(135deg,#5b21b6 0%,#7c3aed 50%,#a78bfa 100%)}
.wallet-card .wc-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;position:relative;z-index:1}
.wallet-card .wc-head .wc-title{font-size:.85rem;font-weight:600;opacity:.9;display:flex;align-items:center;gap:.4rem}
.wallet-card .wc-head .wc-icon{width:36px;height:36px;border-radius:10px;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;font-size:1rem}
.wallet-card .wc-balance{position:relative;z-index:1;margin-bottom:.75rem}
.wallet-card .wc-balance .lbl{font-size:.7rem;opacity:.7;text-transform:uppercase;letter-spacing:.5px}
.wallet-card .wc-balance .val{font-size:1.6rem;font-weight:900;font-feature-settings:'tnum';line-height:1.2}
.wallet-card .wc-row{display:flex;gap:.75rem;position:relative;z-index:1;flex-wrap:wrap}
.wallet-card .wc-item{flex:1;min-width:70px;background:rgba(255,255,255,.12);border-radius:10px;padding:.45rem .6rem;text-align:center}
.wallet-card .wc-item .lbl{font-size:.62rem;opacity:.75;margin-bottom:.15rem}
.wallet-card .wc-item .val{font-size:.85rem;font-weight:800}

/* ═══════════ SALARY BADGE ═══════════ */
.salary-badge{display:inline-flex;align-items:center;gap:.35rem;padding:.35rem .85rem;border-radius:10px;font-size:.78rem;font-weight:700}
.salary-badge.ok{background:#d1fae5;color:#065f46}
.salary-badge.no{background:#fee2e2;color:#991b1b}

/* ═══════════ BANNER ═══════════ */
.pr2-banner{display:flex;align-items:flex-start;gap:.75rem;padding:1rem 1.25rem;border-radius:12px;margin-bottom:1rem;font-size:.85rem}
.pr2-banner.warn{background:linear-gradient(135deg,#fef2f2,#fee2e2);border:1px solid #fecaca;color:#991b1b}
.pr2-banner .bi{font-size:1.3rem;margin-top:.1rem}

/* ═══════════ MULTI EMP BADGE ═══════════ */
.emp-count-badge{display:inline-flex;align-items:center;gap:.3rem;background:var(--c-blue);color:#fff;border-radius:20px;padding:.2rem .7rem;font-size:.75rem;font-weight:700}

/* ═══════════ FORM FOOTER ═══════════ */
.pr2-footer{background:#f8fafc;border-top:1px solid #e2e8f0;padding:1rem 1.25rem;display:flex;justify-content:flex-end;gap:.5rem}
.pr2-footer .btn{border-radius:10px;font-weight:700;padding:.45rem 1.2rem;font-size:.85rem}

/* ═══════════ TREE ═══════════ */
.tree-node{padding:.3rem .5rem;border-radius:6px;cursor:pointer;display:inline-block;font-size:.82rem}
.tree-node:hover{background:#f1f5f9}
.pay-type-leaf{cursor:pointer}.pay-type-leaf:hover{background:#dbeafe;color:#1e40af}
.lt-1{border-right:3px solid var(--c-green)}.lt-2{border-right:3px solid var(--c-red)}

/* ═══════════ SELECT2 ═══════════ */
.select2-container--default .select2-selection--single{border-radius:10px!important;border:1px solid #e2e8f0!important;height:38px!important}
.select2-container--default .select2-selection--multiple{border-radius:10px!important;border:1px solid #e2e8f0!important;min-height:38px!important}
.select2-container--default .select2-selection--multiple .select2-selection__choice{border-radius:6px!important;background:#eff6ff!important;border:1px solid #bfdbfe!important;color:#1e40af;font-size:.78rem;font-weight:600}
.select2-container--default .select2-selection--single .select2-selection__arrow{top:5px!important}
.select2-dropdown{border-radius:10px!important;box-shadow:0 8px 40px rgba(15,23,42,.12)!important}
.select2-results__option--highlighted{background:var(--c-blue)!important}
</style>

<!-- ═══════════ HEAD ═══════════ -->
<div class="pr2-head">
    <h1>
        <span class="hico"><i class="fa fa-<?= $isCreate ? 'plus' : 'file-text-o' ?>"></i></span>
        <?= $title ?>
        <?php if ($HaveRs): ?><small style="font-weight:400;color:var(--c-slate);font-size:.85rem"> — <?= $rs['REQ_NO'] ?? '' ?></small><?php endif; ?>
    </h1>
    <div class="actions">
        <?php if ($can_edit_form): ?>
            <button type="button" id="btnSave" onclick="save()" class="btn btn-primary" disabled><i class="fa fa-save"></i> حفظ</button>
        <?php endif; ?>
        <?php if (!$isCreate && $is_draft && HaveAccess($approve_url)): ?>
            <button type="button" onclick="doApprove()" class="btn btn-info text-white"><i class="fa fa-check-circle"></i> اعتماد</button>
        <?php endif; ?>
        <?php if (!$isCreate && $is_approved && HaveAccess($pay_url)): ?>
            <button type="button" onclick="doPay()" class="btn btn-success"><i class="fa fa-money"></i> صرف</button>
        <?php endif; ?>
        <?php if (!$isCreate && !$is_cancelled && HaveAccess($delete_url)): ?>
            <button type="button" onclick="doCancel()" class="btn btn-danger"><i class="fa fa-ban"></i> إلغاء</button>
        <?php endif; ?>
        <a class="btn btn-secondary" href="<?= $back_url ?>"><i class="fa fa-backward"></i> رجوع</a>
    </div>
</div>

<?php if (!$isCreate && $HaveRs): ?>
<div class="pr2-status s<?= $cur_status ?>">
    <i class="fa fa-info-circle"></i>
    حالة الطلب: <?= $rs['STATUS_NAME'] ?? '' ?>
    <?php if ($is_paid && !empty($rs['PAY_DATE'])): ?>
        <small class="ms-3" style="font-weight:400">— تاريخ الصرف: <?= $rs['PAY_DATE'] ?></small>
    <?php endif; ?>
    <?php if ($is_cancelled && !empty($rs['CANCEL_NOTE'])): ?>
        <small class="ms-3" style="font-weight:400">— سبب الإلغاء: <?= $rs['CANCEL_NOTE'] ?></small>
    <?php endif; ?>
</div>
<?php endif; ?>

<!-- ═══════════ BANNER: SALARY NOT COMPUTED ═══════════ -->
<div id="salaryNotComputedBanner" class="pr2-banner warn" style="display:none">
    <i class="fa fa-exclamation-triangle bi"></i>
    <div>
        <strong>لم يتم احتساب الراتب لهذا الشهر</strong>
        <div style="font-size:.78rem;margin-top:.2rem;opacity:.8">يمكنك فقط الصرف من محفظة مستحقات 48 (دفعة مقطوعة)</div>
    </div>
</div>

<!-- ═══════════ WALLET CARDS ═══════════ -->
<div id="summaryWrap" style="display:none">
    <!-- Salary Status -->
    <div id="salaryStatusBar" class="mb-2" style="display:none">
        <span id="salaryStatusOk" class="salary-badge ok" style="display:none"><i class="fa fa-check-circle"></i> الراتب محتسب</span>
        <span id="salaryStatusNo" class="salary-badge no" style="display:none"><i class="fa fa-times-circle"></i> الراتب غير محتسب</span>
    </div>

    <div class="wallet-grid mb-3">
        <!-- SALARY WALLET -->
        <div id="summarySalary" class="wallet-card w-salary" style="display:none">
            <div class="wc-head">
                <span class="wc-title"><i class="fa fa-money"></i> محفظة الراتب الشهري</span>
                <span class="wc-icon"><i class="fa fa-credit-card"></i></span>
            </div>
            <div class="wc-balance">
                <div class="lbl">المتبقي للصرف</div>
                <div class="val" id="sumSalaryRem">0.00</div>
            </div>
            <div class="wc-row">
                <div class="wc-item"><div class="lbl">صافي الراتب</div><div class="val" id="sumNetSalary">0</div></div>
                <div class="wc-item"><div class="lbl">المصروف</div><div class="val" id="sumSalaryPaid">0</div></div>
            </div>
        </div>

        <!-- DUES 48 WALLET -->
        <div id="summaryDues" class="wallet-card w-dues" style="display:none">
            <div class="wc-head">
                <span class="wc-title"><i class="fa fa-archive"></i> محفظة مستحقات 48</span>
                <span class="wc-icon"><i class="fa fa-university"></i></span>
            </div>
            <div class="wc-balance">
                <div class="lbl">المتبقي للصرف</div>
                <div class="val" id="sumDuesRem">0.00</div>
            </div>
            <div class="wc-row">
                <div class="wc-item"><div class="lbl">الأساسي</div><div class="val" id="sumDuesBase">0</div></div>
                <div class="wc-item"><div class="lbl">الإضافات</div><div class="val" id="sumDuesAdd">0</div></div>
                <div class="wc-item"><div class="lbl">الخصومات</div><div class="val" id="sumDuesDed">0</div></div>
            </div>
        </div>
    </div>
</div>

<!-- ═══════════ FORM ═══════════ -->
<form id="<?= $TB_NAME ?>_form">
    <?php if (!$isCreate && $HaveRs): ?>
        <input type="hidden" name="req_id" id="req_id" value="<?= $rs['REQ_ID'] ?>">
    <?php endif; ?>

    <div class="pr2-card">
        <div class="pr2-card-hd">
            <span class="ic" style="background:linear-gradient(135deg,var(--c-blue),var(--c-purple))"><i class="fa fa-edit"></i></span>
            بيانات الطلب
            <?php if ($isCreate): ?>
                <span id="empCountBadge" class="emp-count-badge ms-auto" style="display:none"><i class="fa fa-users"></i> <span id="empCountNum">0</span> موظف</span>
            <?php endif; ?>
        </div>
        <div class="pr2-card-bd">
            <div class="row g-3">
                <!-- الموظف -->
                <div class="form-group col-md-<?= $isCreate ? '6' : '3' ?>">
                    <label><i class="fa fa-user text-primary me-1"></i> الموظف <?= $isCreate ? '<small class="text-info">(يمكن اختيار أكثر من موظف)</small>' : '' ?></label>
                    <?php if ($isCreate): ?>
                        <select name="emp_no[]" id="emp_no" class="form-control sel2-multi" multiple <?= !$can_edit_form?'disabled':'' ?>>
                            <?php foreach ($emp_no_cons as $row): ?>
                                <option value="<?= $row['EMP_NO'] ?>"><?= $row['EMP_NO'].' - '.$row['EMP_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    <?php else: ?>
                        <select name="emp_no" id="emp_no" class="form-control sel2" <?= !$can_edit_form?'disabled':'' ?>>
                            <option value="">—</option>
                            <?php foreach ($emp_no_cons as $row): $sel=($HaveRs&&$rs['EMP_NO']==$row['EMP_NO'])?'selected':''; ?>
                                <option <?=$sel?> value="<?= $row['EMP_NO'] ?>"><?= $row['EMP_NO'].' - '.$row['EMP_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    <?php endif; ?>
                </div>

                <!-- الشهر -->
                <div class="form-group col-sm-6 col-md-2">
                    <label><i class="fa fa-calendar text-success me-1"></i> الشهر</label>
                    <input type="text" name="the_month" id="the_month" class="form-control" placeholder="YYYYMM"
                           value="<?= (!$isCreate&&$HaveRs)?($rs['THE_MONTH']??''):date('Ym') ?>"
                           <?= !$can_edit_form?'readonly':'' ?>>
                </div>

                <!-- نوع الطلب -->
                <div class="form-group col-sm-6 col-md-2">
                    <label><i class="fa fa-list text-info me-1"></i> نوع الطلب</label>
                    <select name="req_type" id="req_type" class="form-select" <?= !$can_edit_form?'disabled':'' ?>>
                        <option value="">— اختر —</option>
                        <option value="1" <?=(!$isCreate&&$HaveRs&&$rs['REQ_TYPE']==1)?'selected':''?>>راتب كامل</option>
                        <option value="2" <?=(!$isCreate&&$HaveRs&&$rs['REQ_TYPE']==2)?'selected':''?>>دفعة من الراتب</option>
                        <option value="3" <?=(!$isCreate&&$HaveRs&&$rs['REQ_TYPE']==3)?'selected':''?>>دفعة مقطوعة</option>
                    </select>
                </div>

                <!-- المحفظة -->
                <div class="form-group col-sm-6 col-md-2">
                    <label><i class="fa fa-briefcase text-warning me-1"></i> المحفظة</label>
                    <select name="wallet_type" id="wallet_type" class="form-select" <?= !$can_edit_form?'disabled':'' ?>>
                        <option value="">— اختر —</option>
                        <option value="1" <?=(!$isCreate&&$HaveRs&&$rs['WALLET_TYPE']==1)?'selected':''?>>راتب شهري</option>
                        <option value="2" <?=(!$isCreate&&$HaveRs&&$rs['WALLET_TYPE']==2)?'selected':''?>>مستحقات 48</option>
                    </select>
                </div>

                <!-- طريقة الاحتساب -->
                <div class="form-group col-sm-6 col-md-2" id="grp_calc_method" style="display:none">
                    <label><i class="fa fa-calculator me-1" style="color:var(--c-purple)"></i> طريقة الاحتساب</label>
                    <select name="calc_method" id="calc_method" class="form-select" <?= !$can_edit_form?'disabled':'' ?>>
                        <option value="">— اختر —</option>
                        <option value="1" <?=(!$isCreate&&$HaveRs&&$rs['CALC_METHOD']==1)?'selected':''?>>نسبة</option>
                        <option value="2" <?=(!$isCreate&&$HaveRs&&$rs['CALC_METHOD']==2)?'selected':''?>>مبلغ</option>
                    </select>
                </div>

                <!-- النسبة -->
                <div class="form-group col-sm-6 col-md-2" id="grp_percent" style="display:none">
                    <label><i class="fa fa-percent text-success me-1"></i> النسبة</label>
                    <div class="input-group">
                        <input type="number" step="0.01" min="0" max="100" name="percent_val" id="percent_val" class="form-control"
                               value="<?=(!$isCreate&&$HaveRs)?($rs['PERCENT_VAL']??''):''?>" <?=!$can_edit_form?'readonly':''?>>
                        <span class="input-group-text" style="border-radius:0 10px 10px 0">%</span>
                    </div>
                </div>

                <!-- المبلغ -->
                <div class="form-group col-sm-6 col-md-3" id="grp_amount" style="display:none">
                    <label><i class="fa fa-money text-primary me-1"></i> المبلغ</label>
                    <input type="number" step="0.01" min="0" name="req_amount" id="req_amount" class="form-control" placeholder="0.00"
                           value="<?=(!$isCreate&&$HaveRs)?($rs['REQ_AMOUNT']??''):''?>" <?=!$can_edit_form?'readonly':''?>>
                    <div id="amountWarn" class="small text-danger mt-1" style="display:none">
                        <i class="fa fa-exclamation-triangle"></i> <span id="amountWarnMsg"></span>
                    </div>
                </div>

                <!-- بند المستحقات -->
                <div class="form-group col-md-4" id="grp_pay_type" style="display:none">
                    <label><i class="fa fa-sitemap text-info me-1"></i> بند المستحقات</label>
                    <div class="input-group">
                        <input type="hidden" name="pay_type" id="pay_type" value="<?=(!$isCreate&&$HaveRs)?($rs['PAY_TYPE']??''):''?>">
                        <input type="text" id="pay_type_display" class="form-control bg-white" readonly placeholder="اختر من الشجرة..."
                               value="<?=(!$isCreate&&$HaveRs)?($rs['PAY_TYPE_NAME']??''):''?>">
                        <?php if ($can_edit_form): ?>
                        <button type="button" class="btn btn-outline-primary" id="btn_pay_type_tree" style="border-radius:0 10px 10px 0">
                            <i class="fa fa-sitemap"></i>
                        </button>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- ملاحظات -->
                <div class="form-group col-12">
                    <label><i class="fa fa-sticky-note-o text-muted me-1"></i> ملاحظات</label>
                    <input type="text" name="note" id="note" class="form-control" placeholder="اختياري..."
                           value="<?=(!$isCreate&&$HaveRs)?($rs['NOTE']??''):''?>" <?=!$can_edit_form?'readonly':''?>>
                </div>
            </div>
        </div>

        <?php if ($can_edit_form): ?>
        <div class="pr2-footer">
            <a class="btn btn-light" href="<?= $back_url ?>"><i class="fa fa-times"></i> إلغاء</a>
            <button type="button" id="btnSaveBottom" disabled onclick="save()" class="btn btn-primary"><i class="fa fa-save"></i> حفظ</button>
        </div>
        <?php endif; ?>
    </div>
</form>

<!-- ═══════════ TREE MODAL ═══════════ -->
<div class="modal fade" id="payTypeTreeModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius:16px;overflow:hidden">
            <div class="modal-header py-2" style="background:#f8fafc;border-bottom:1px solid #e2e8f0">
                <h5 class="modal-title" style="font-size:.9rem;font-weight:700"><i class="fa fa-sitemap text-primary me-2"></i>اختر بند المستحقات</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-3">
                <div id="pay_type_tree_loading" class="text-center py-4 text-muted">
                    <i class="fa fa-spinner fa-spin fa-2x"></i><p class="mt-2 mb-0">جاري التحميل...</p>
                </div>
                <div id="pay_type_tree_wrap" class="overflow-auto border rounded bg-light p-3" style="display:none;max-height:65vh">
                    <ul id="pay_type_tree" class="tree list-unstyled mb-0"></ul>
                </div>
            </div>
            <div class="modal-footer py-2"><button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">إغلاق</button></div>
        </div>
    </div>
</div>

<?php
$SUMMARY_URL_JS = json_encode($summary_url);
$POST_URL_JS    = json_encode($post_url);
$BACK_URL_JS    = json_encode($back_url);
$APPROVE_URL_JS = json_encode($approve_url);
$PAY_URL_JS     = json_encode($pay_url);
$DELETE_URL_JS  = json_encode($delete_url);
$FORM_ID_JS     = json_encode($TB_NAME . '_form');
$CAN_EDIT_JS    = $can_edit_form ? 'true' : 'false';
$IS_CREATE_JS   = $isCreate ? 'true' : 'false';
$PAY_TYPE_TREE_URL_JS = isset($pay_type_tree_url) ? json_encode($pay_type_tree_url) : '""';
?>

<script>
(function(){
    var summaryUrl=<?= $SUMMARY_URL_JS ?>, postUrl=<?= $POST_URL_JS ?>, backUrl=<?= $BACK_URL_JS ?>;
    var approveUrl=<?= $APPROVE_URL_JS ?>, payUrl=<?= $PAY_URL_JS ?>, deleteUrl=<?= $DELETE_URL_JS ?>;
    var formId=<?= $FORM_ID_JS ?>, canEdit=<?= $CAN_EDIT_JS ?>, isCreate=<?= $IS_CREATE_JS ?>;
    var payTypeTreeUrl=<?= $PAY_TYPE_TREE_URL_JS ?>;
    var summaryData=null, salaryExists=<?= $INIT_SALARY_EXISTS_JS ?>;

    function nF(x){var n=parseFloat(x);return isNaN(n)?'0.00':n.toLocaleString('en',{minimumFractionDigits:2,maximumFractionDigits:2})}
    function setSave(f){$('#btnSave,#btnSaveBottom').prop('disabled',!f)}

    $('.sel2:not("[id^=\'s2\']")').select2({width:'100%',dir:'rtl',placeholder:'— اختر —',allowClear:true});
    $('.sel2-multi').select2({width:'100%',dir:'rtl',placeholder:'اختر الموظفين...',allowClear:true,closeOnSelect:false});
    $('#the_month').datetimepicker({format:'YYYYMM',minViewMode:'months',pickTime:false});

    function updateEmpCount(){
        if(!isCreate)return;
        var sel=$('#emp_no').val();
        var c=sel?sel.length:0;
        if(c>0){$('#empCountBadge').show();$('#empCountNum').text(c)}
        else{$('#empCountBadge').hide()}
    }

    function applyFieldRules(){
        var rt=$('#req_type').val(),wt=$('#wallet_type').val(),cm=$('#calc_method').val();
        var noSal=(salaryExists===0);
        $('#grp_calc_method,#grp_percent,#grp_amount,#grp_pay_type').hide();

        if(noSal){$('#salaryNotComputedBanner').show()}else{$('#salaryNotComputedBanner').hide()}

        if(salaryExists===1){$('#salaryStatusBar').show();$('#salaryStatusOk').show();$('#salaryStatusNo').hide()}
        else if(salaryExists===0){$('#salaryStatusBar').show();$('#salaryStatusOk').hide();$('#salaryStatusNo').show()}
        else{$('#salaryStatusBar').hide()}

        $('#req_type option[value="1"],#req_type option[value="2"]').prop('disabled',noSal);
        $('#wallet_type option[value="1"]').prop('disabled',noSal);
        if(noSal){
            if(rt=='1'||rt=='2'){$('#req_type').val('');rt=''}
            if(wt=='1'){$('#wallet_type').val('');wt=''}
        }

        if(rt=='1'){$('#wallet_type').val('1');wt='1';$('#req_amount').val('').prop('readonly',true)}
        else if(rt=='2'){
            $('#wallet_type').val('1');wt='1';$('#grp_calc_method').show();
            if(cm=='1'){$('#grp_percent,#grp_amount').show();$('#req_amount').prop('readonly',true)}
            else if(cm=='2'){$('#grp_amount').show();$('#req_amount').prop('readonly',!canEdit);$('#percent_val').val('')}
        }
        else if(rt=='3'){
            $('#grp_amount').show();$('#req_amount').prop('readonly',!canEdit);
            if(wt=='2')$('#grp_pay_type').show();
        }

        if(wt=='1'&&!noSal){$('#summarySalary').show();$('#summaryDues').hide()}
        else if(wt=='2'){$('#summarySalary').hide();$('#summaryDues').show()}
        else{
            if(noSal){$('#summarySalary').hide();$('#summaryDues').show()}
            else{$('#summarySalary,#summaryDues').hide()}
        }
        calcAmount();validateForm();
    }

    function calcAmount(){
        var rt=$('#req_type').val(),cm=$('#calc_method').val();
        if(!summaryData)return;
        if(rt=='1'){var r=parseFloat(summaryData.SALARY_REMAINING||0);$('#req_amount').val(r>0?r.toFixed(2):'')}
        else if(rt=='2'&&cm=='1'){
            var p=parseFloat($('#percent_val').val()||0),n=parseFloat(summaryData.NET_SALARY||0);
            $('#req_amount').val((p>0&&n>0)?(Math.round(n*p/100*100)/100).toFixed(2):'')
        }
    }

    function validateForm(){
        if(!canEdit){setSave(false);return}
        var empVal=$('#emp_no').val();
        var hasEmp=isCreate?(empVal&&empVal.length>0):(empVal&&empVal!='');
        var rt=$('#req_type').val(),wt=$('#wallet_type').val(),amt=parseFloat($('#req_amount').val()||0);
        var noSal=(salaryExists===0);

        if(!hasEmp||!rt||!wt){setSave(false);return}
        if(!summaryData&&!isCreate){setSave(false);return}
        if(noSal&&(wt=='1'||rt=='1'||rt=='2')){setSave(false);return}

        var ok=true;$('#amountWarn').hide();
        if(rt=='1'){if(summaryData&&parseFloat(summaryData.SALARY_REMAINING||0)<=0)ok=false}
        else if(rt=='2'){
            var cm2=$('#calc_method').val();
            if(!cm2)ok=false;
            else if(cm2=='1'&&(!$('#percent_val').val()||parseFloat($('#percent_val').val())<=0))ok=false;
            else if(cm2=='2'&&amt<=0)ok=false;
            if(summaryData){var sr=parseFloat(summaryData.SALARY_REMAINING||0);if(amt>sr&&amt>0){ok=false;$('#amountWarnMsg').text('المبلغ يتجاوز المتبقي ('+nF(sr)+')');$('#amountWarn').show()}}
        }
        else if(rt=='3'){
            if(amt<=0)ok=false;
            if(summaryData){
                if(wt=='1'){var sr2=parseFloat(summaryData.SALARY_REMAINING||0);if(amt>sr2){ok=false;$('#amountWarnMsg').text('يتجاوز المتبقي ('+nF(sr2)+')');$('#amountWarn').show()}}
                else if(wt=='2'){var dr=parseFloat(summaryData.DUES48_REMAINING||0);if(amt>dr){ok=false;$('#amountWarnMsg').text('يتجاوز المتبقي ('+nF(dr)+')');$('#amountWarn').show()}}
            }
        }
        if(isCreate&&hasEmp&&rt&&wt&&amt>0)ok=true;
        setSave(ok);
    }

    function loadSummary(){
        var empVal=$('#emp_no').val();
        var empNo=isCreate?(Array.isArray(empVal)&&empVal.length>0?empVal[0]:null):empVal;
        var theMonth=$('#the_month').val();
        updateEmpCount();

        if(!empNo){$('#summaryWrap').hide();$('#salaryNotComputedBanner').hide();summaryData=null;salaryExists=null;validateForm();return}
        $('#summaryWrap').show();

        $.post(summaryUrl,{emp_no:empNo,the_month:theMonth||''},function(resp){
            try{
                var j=(typeof resp==='string')?JSON.parse(resp):resp;
                if(!j||!j.ok){summaryData=null;salaryExists=null;validateForm();return}
                var rows=j.data||[];
                var r=Array.isArray(rows)&&rows.length>0?rows[0]:(rows||{});
                summaryData=r;
                salaryExists=(r.SALARY_EXISTS!==undefined)?parseInt(r.SALARY_EXISTS):1;
                $('#sumNetSalary').text(nF(r.NET_SALARY||0));
                $('#sumSalaryPaid').text(nF(r.SALARY_PAID||0));
                $('#sumSalaryRem').text(nF(r.SALARY_REMAINING||0));
                $('#sumDuesBase').text(nF(r.DUES48_BASE||0));
                $('#sumDuesAdd').text(nF(r.DUES48_ADD||0));
                $('#sumDuesDed').text(nF(r.DUES48_DED||0));
                $('#sumDuesRem').text(nF(r.DUES48_REMAINING||0));
                applyFieldRules();
            }catch(e){summaryData=null;salaryExists=null;validateForm()}
        },'json').fail(function(){summaryData=null;salaryExists=null;validateForm()});
    }

    window.save=function(){
        validateForm();
        if($('#btnSave').prop('disabled')&&$('#btnSaveBottom').prop('disabled'))return;
        setSave(false);
        $('#btnSave,#btnSaveBottom').html('<i class="fa fa-spinner fa-spin"></i> جاري الحفظ...');

        if(isCreate){
            var empArr=$('#emp_no').val();
            if(!empArr||empArr.length===0){danger_msg('تحذير','اختر موظف واحد على الأقل');resetSaveBtn();return}
            var total=empArr.length,done=0,errors=[];
            empArr.forEach(function(eno){
                var fd=$('#'+formId).serializeArray().filter(function(f){return f.name!=='emp_no[]'});
                fd.push({name:'emp_no',value:eno});
                $.ajax({url:postUrl,type:'POST',data:fd,dataType:'html'}).done(function(d){
                    if(!(d=='1'||(String(d).match(/^\d+$/)&&parseInt(d)>0)))errors.push(eno+': '+d);
                }).fail(function(){errors.push(eno+': فشل الاتصال')}).always(function(){
                    done++;
                    if(done>=total){
                        if(errors.length===0){success_msg('رسالة','تم حفظ '+total+' طلب بنجاح');window.location.href=backUrl}
                        else if(errors.length<total){success_msg('رسالة','تم حفظ '+(total-errors.length)+' طلب');danger_msg('تحذير',errors.join('<br>'));setTimeout(function(){window.location.href=backUrl},2000)}
                        else{danger_msg('تحذير',errors.join('<br>'));resetSaveBtn()}
                    }
                });
            });
        } else {
            $.ajax({url:postUrl,type:'POST',data:$('#'+formId).serialize(),dataType:'html'}).done(function(d){
                if(d=='1'||(String(d).match(/^\d+$/)&&parseInt(d)>0)){success_msg('رسالة','تم الحفظ بنجاح');window.location.href=backUrl}
                else{danger_msg('تحذير',d);resetSaveBtn()}
            }).fail(function(xhr){danger_msg('تحذير',xhr.responseText||'فشل الاتصال');resetSaveBtn()});
        }
    };
    function resetSaveBtn(){setSave(true);$('#btnSave,#btnSaveBottom').html('<i class="fa fa-save"></i> حفظ')}

    window.doApprove=function(){
        var id=$('#req_id').val();if(!id)return;
        if(!confirm('هل تريد اعتماد هذا الطلب؟'))return;
        get_data(approveUrl,{req_id:id},function(d){if(d=='1'){success_msg('رسالة','تم الاعتماد');location.reload()}else danger_msg('تحذير',d)},'html');
    };
    window.doPay=function(){
        var id=$('#req_id').val();if(!id)return;
        if(!confirm('هل تريد صرف هذا الطلب؟'))return;
        get_data(payUrl,{req_id:id},function(d){if(d=='1'){success_msg('رسالة','تم الصرف');location.reload()}else danger_msg('تحذير',d)},'html');
    };
    window.doCancel=function(){
        var id=$('#req_id').val();if(!id)return;
        var n=prompt('سبب الإلغاء:','إلغاء');if(n===null)return;
        get_data(deleteUrl,{req_id:id,cancel_note:n||'إلغاء'},function(d){if(d=='1'){success_msg('رسالة','تم الإلغاء');location.reload()}else danger_msg('تحذير',d)},'html');
    };

    var payTypeTreeData=null;
    function buildTree(nodes){
        if(!nodes||!nodes.length)return '';var h='';
        for(var i=0;i<nodes.length;i++){
            var nd=nodes[i],hc=nd.children&&nd.children.length>0;
            var lt=(nd.attributes&&nd.attributes.lineType)?nd.attributes.lineType:1;
            if(hc){h+='<li class="parent_li" data-id="'+nd.id+'"><span class="tree-node lt-'+lt+' pay-type-parent"><i class="fa fa-plus tree-icon"></i> '+(nd.text||'')+'</span><ul class="list-unstyled ms-3" style="display:none">'+buildTree(nd.children)+'</ul></li>'}
            else{h+='<li data-id="'+nd.id+'"><span class="tree-node lt-'+lt+' pay-type-leaf"><i class="fa tree-icon" style="width:16px;display:inline-block"></i> '+(nd.text||'')+'</span></li>'}
        }return h;
    }
    function loadTree(){
        var ld=$('#pay_type_tree_loading'),w=$('#pay_type_tree_wrap'),t=$('#pay_type_tree');
        if(payTypeTreeData){ld.hide();t.html(buildTree(payTypeTreeData));w.show();bindTree();return}
        if(!payTypeTreeUrl)return;ld.show();w.hide();t.empty();
        $.get(payTypeTreeUrl).done(function(d){payTypeTreeData=Array.isArray(d)?d:[];ld.hide();t.html(buildTree(payTypeTreeData));w.show();bindTree()}).fail(function(){ld.html('<span class="text-danger">فشل تحميل الشجرة</span>')});
    }
    function bindTree(){
        $('#pay_type_tree').off('click','.pay-type-parent').on('click','.pay-type-parent',function(e){
            e.stopPropagation();var ul=$(this).closest('li').children('ul'),ic=$(this).find('.tree-icon');
            if(ul.is(':visible')){ul.slideUp(200);ic.removeClass('fa-minus').addClass('fa-plus')}
            else{ul.slideDown(200);ic.removeClass('fa-plus').addClass('fa-minus')}
        });
        $('#pay_type_tree').off('click','.pay-type-leaf').on('click','.pay-type-leaf',function(e){
            e.stopPropagation();var li=$(this).closest('li'),id=li.data('id'),txt=li.find('.tree-node').text().trim();
            if(id){$('#pay_type').val(id);$('#pay_type_display').val(txt);var m=bootstrap.Modal.getInstance(document.getElementById('payTypeTreeModal'));if(m)m.hide();validateForm()}
        });
    }

    $('#req_type,#wallet_type,#calc_method').on('change',applyFieldRules);
    $('#percent_val').on('keyup change',function(){calcAmount();validateForm()});
    $('#req_amount').on('keyup change',validateForm);
    $('#emp_no').on('change',function(){loadSummary();updateEmpCount()});
    $('#the_month').on('change dp.change',loadSummary);
    $('#btn_pay_type_tree').on('click',function(e){e.preventDefault();loadTree();bootstrap.Modal.getOrCreateInstance(document.getElementById('payTypeTreeModal')).show()});
    $(document).on('keydown',function(e){if(e.ctrlKey&&e.key==='s'){e.preventDefault();if(!$('#btnSave').prop('disabled'))save()}if(e.key==='Escape'&&!$('.modal').hasClass('show'))window.location.href=backUrl});

    loadSummary();
    setTimeout(applyFieldRules,200);
})();
</script>
