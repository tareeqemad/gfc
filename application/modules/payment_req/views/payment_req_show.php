<?php
/**
 * Payment Request — Show v2 (Create / Edit / View)
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

$cur_status    = $HaveRs ? (int)($rs['STATUS'] ?? 0) : -1;
$is_draft      = ($cur_status == 0);
$is_approved   = ($cur_status == 1);
$is_paid       = ($cur_status == 2);
$is_cancelled  = ($cur_status == 9);
$can_edit_form = $isCreate || $is_draft;

echo AntiForgeryToken();
?>

<style>
:root{--c-blue:#2563eb;--c-purple:#7c3aed;--c-green:#059669;--c-red:#dc2626;--c-amber:#d97706;--c-slate:#64748b;--r:14px;--sh:0 4px 24px rgba(15,23,42,.07)}
.pr2-head{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;margin-top:1.25rem;margin-bottom:1.25rem}
.pr2-head h1{font-size:1.3rem;font-weight:800;color:#1e293b;margin:0;display:flex;align-items:center;gap:.6rem}
.pr2-head .hico{width:38px;height:38px;border-radius:11px;background:linear-gradient(135deg,var(--c-blue),var(--c-purple));color:#fff;display:flex;align-items:center;justify-content:center;font-size:.95rem}
.pr2-head-left{display:flex;flex-direction:column;gap:.35rem}
.pr2-head .actions{display:flex;gap:.5rem;flex-wrap:wrap}
.pr2-head .actions .btn{border-radius:10px;font-weight:700;font-size:.82rem;padding:.4rem 1rem}
/* unified panel like index */
.pr-panel{background:#fff;border:1px solid #e2e8f0;border-radius:var(--r);box-shadow:var(--sh);overflow:hidden;margin-bottom:1.25rem}
.pr-panel-hd{display:flex;align-items:center;justify-content:space-between;padding:.75rem 1.25rem;background:linear-gradient(135deg,#1e293b,#334155);color:#fff}
.pr-panel-hd .t{font-weight:700;font-size:.9rem;display:flex;align-items:center;gap:.4rem}
.pr-panel-bd{padding:1rem 1.25rem}
.pr-panel-bd label{font-weight:600;font-size:.75rem;color:#94a3b8;text-transform:uppercase;letter-spacing:.5px;margin-bottom:.3rem}
.pr-panel-ft{display:flex;gap:.5rem;padding:0 1.25rem 1rem}
.pr-panel-ft .btn{border-radius:10px;font-weight:600;padding:.4rem 1.1rem;font-size:.82rem}
.pr2-status{display:flex;align-items:center;gap:.75rem;padding:.65rem 1.2rem;border-radius:12px;font-weight:700;font-size:.85rem;margin-bottom:1rem}
.pr2-status.s0{background:#fef3c7;color:#92400e}.pr2-status.s1{background:#dbeafe;color:#1e40af}
.pr2-status.s2{background:#d1fae5;color:#065f46}.pr2-status.s9{background:#fee2e2;color:#991b1b}
.pr2-card-bd label,.pr-panel-bd .form-group label{font-weight:600;font-size:.78rem;color:var(--c-slate);margin-bottom:.3rem;display:block}
.pr-panel-bd .form-control,.pr-panel-bd .form-select{border-radius:10px;border:1px solid #e2e8f0}
.pr-panel-bd .form-control:focus,.pr-panel-bd .form-select:focus{border-color:var(--c-blue);box-shadow:0 0 0 3px rgba(37,99,235,.1)}
.wallet-grid{display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;margin-top:.5rem}
@media(max-width:768px){.wallet-grid{grid-template-columns:1fr}}
.wallet-card{border-radius:12px;padding:1.25rem;position:relative;overflow:hidden;min-height:140px;color:#fff;border:none;box-shadow:0 2px 12px rgba(0,0,0,.08)}
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
.salary-badge{display:inline-flex;align-items:center;gap:.35rem;padding:.35rem .85rem;border-radius:10px;font-size:.78rem;font-weight:700}
.salary-badge.ok{background:#d1fae5;color:#065f46}
.salary-badge.no{background:#fee2e2;color:#991b1b}
.pr2-banner{display:flex;align-items:flex-start;gap:.75rem;padding:1rem 1.25rem;border-radius:12px;margin-bottom:1rem;font-size:.85rem}
.pr2-banner.warn{background:linear-gradient(135deg,#fef2f2,#fee2e2);border:1px solid #fecaca;color:#991b1b}
.pr2-banner .bi{font-size:1.3rem;margin-top:.1rem}
.emp-count-badge{display:inline-flex;align-items:center;gap:.3rem;background:var(--c-blue);color:#fff;border-radius:20px;padding:.2rem .7rem;font-size:.75rem;font-weight:700}
.pr2-footer{background:#f8fafc;border-top:1px solid #e2e8f0;padding:1rem 1.25rem;display:flex;justify-content:flex-end;gap:.5rem}
.pr2-footer .btn{border-radius:10px;font-weight:700;padding:.45rem 1.2rem;font-size:.85rem}
.tree-node{padding:.5rem .75rem;border-radius:8px;cursor:pointer;display:inline-flex;align-items:center;gap:.4rem;font-size:.85rem;font-weight:500;color:#334155;transition:.15s;margin:.15rem 0;line-height:1.4}
.tree-node:hover{background:#f1f5f9}
.pay-type-parent{color:#1e293b;font-weight:700;background:#f8fafc;border:1px solid #e2e8f0}
.pay-type-parent:hover{background:#e2e8f0;border-color:#cbd5e1}
.pay-type-parent .tree-icon{color:var(--c-blue);font-size:.75rem;width:18px;text-align:center;transition:transform .2s}
.pay-type-leaf{color:#334155;background:#fff;border:1px solid #e2e8f0;padding-right:1rem;padding-left:1rem}
.pay-type-leaf:hover{background:#dbeafe;color:#1e40af;border-color:#93c5fd;transform:translateX(-2px)}
.pay-type-leaf.leaf-disabled{opacity:.45;cursor:not-allowed;background:#f8fafc;border-color:#e2e8f0;color:#94a3b8}
.pay-type-leaf.leaf-disabled:hover{background:#f8fafc;color:#94a3b8;border-color:#e2e8f0;transform:none}
.pay-type-leaf.leaf-disabled::after{content:'إضافة';font-size:.6rem;background:#e2e8f0;color:#94a3b8;padding:.1rem .35rem;border-radius:4px;margin-right:.4rem}
.pay-type-leaf .tree-icon{display:none!important}
.pay-type-leaf::before{content:'\f111';font-family:FontAwesome;font-size:.4rem;color:#94a3b8;margin-left:.3rem}
.pay-type-leaf:hover::before{color:#2563eb}
.lt-1{border-right:3px solid var(--c-green)}.lt-2{border-right:3px solid var(--c-red)}
#pay_type_tree>li{margin-bottom:.3rem}
#pay_type_tree ul{margin-top:.3rem;padding-right:.75rem;border-right:2px solid #e2e8f0}
.tree-search-wrap{position:relative;margin-bottom:.75rem}
.tree-search-wrap input{border-radius:10px;border:1px solid #e2e8f0;padding:.5rem 1rem .5rem 2.2rem;font-size:.85rem;width:100%}
.tree-search-wrap input:focus{outline:none;border-color:var(--c-blue);box-shadow:0 0 0 3px rgba(37,99,235,.1)}
.tree-search-wrap .search-ico{position:absolute;left:.75rem;top:50%;transform:translateY(-50%);color:#94a3b8;font-size:.82rem}
.select2-container--default .select2-selection--single{border-radius:10px!important;border:1px solid #e2e8f0!important;height:38px!important}
.select2-container--default .select2-selection--multiple{border-radius:10px!important;border:1px solid #e2e8f0!important;min-height:38px!important}
.select2-container--default .select2-selection--single .select2-selection__arrow{top:5px!important}
.select2-dropdown{border-radius:10px!important;box-shadow:0 8px 40px rgba(15,23,42,.12)!important}
.select2-results__option--highlighted{background:var(--c-blue)!important}
</style>

<!-- HEAD -->
<div class="pr2-head">
    <div class="pr2-head-left">
        <h1>
            <span class="hico"><i class="fa fa-<?= $isCreate ? 'plus' : 'file-text-o' ?>"></i></span>
            <?= $title ?>
            <?php if ($HaveRs): ?><small style="font-weight:400;color:var(--c-slate);font-size:.85rem"> — <?= $rs['REQ_NO'] ?? '' ?></small><?php endif; ?>
        </h1>
        <ol class="breadcrumb mb-0" style="font-size:.8rem">
            <li class="breadcrumb-item"><a href="<?= $back_url ?>">صرف الرواتب والمستحقات</a></li>
            <li class="breadcrumb-item active"><?= $title ?></li>
        </ol>
    </div>
    <div class="actions">
        <?php if ($can_edit_form): ?>
            <button type="button" id="btnSave" onclick="javascript:save(this);" class="btn btn-primary" disabled><i class="fa fa-save"></i> حفظ</button>
        <?php endif; ?>
        <?php if (!$isCreate && $is_draft && HaveAccess($approve_url)): ?>
            <button type="button" onclick="javascript:doApprove(this);" class="btn btn-info text-white"><i class="fa fa-check-circle"></i> اعتماد</button>
        <?php endif; ?>
        <?php if (!$isCreate && $is_approved && HaveAccess($pay_url)): ?>
            <button type="button" onclick="javascript:doPay(this);" class="btn btn-success"><i class="fa fa-money"></i> صرف</button>
        <?php endif; ?>
        <?php if (!$isCreate && !$is_cancelled && HaveAccess($delete_url)): ?>
            <button type="button" onclick="javascript:doCancel(this);" class="btn btn-danger"><i class="fa fa-ban"></i> إلغاء</button>
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

<!-- BANNER: SALARY NOT COMPUTED -->
<div id="salaryNotComputedBanner" class="pr2-banner warn" style="display:none;">
    <i class="fa fa-exclamation-triangle bi"></i>
    <div>
        <strong>لم يتم احتساب الراتب لهذا الشهر</strong>
        <div style="font-size:.78rem;margin-top:.2rem;opacity:.8">يمكنك فقط الصرف من محفظة مستحقات (دفعة مقطوعة)</div>
    </div>
</div>

<!-- WALLET CARDS (unified panel like index) -->
<div id="summaryWrap" class="pr-panel" style="display:none;">
    <div class="pr-panel-hd">
        <span class="t"><i class="fa fa-credit-card"></i> ملخص المحافظ</span>
    </div>
    <div class="pr-panel-bd">
        <div id="salaryStatusBar" class="mb-2" style="display:none;">
            <span id="salaryStatusOk" class="salary-badge ok" style="display:none;"><i class="fa fa-check-circle"></i> الراتب محتسب</span>
            <span id="salaryStatusNo" class="salary-badge no" style="display:none;"><i class="fa fa-times-circle"></i> الراتب غير محتسب</span>
        </div>
        <div class="wallet-grid">
            <div id="summarySalary" class="wallet-card w-salary" style="display:none;">
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
            <div id="summaryDues" class="wallet-card w-dues" style="display:none;">
                <div class="wc-head">
                    <span class="wc-title"><i class="fa fa-archive"></i> محفظة مستحقات</span>
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
</div>

<!-- FORM -->
<form id="<?= $TB_NAME ?>_form">
    <?php if (!$isCreate && $HaveRs): ?>
        <input type="hidden" name="req_id" id="req_id" value="<?= $rs['REQ_ID'] ?>">
    <?php endif; ?>

    <div class="pr-panel">
        <div class="pr-panel-hd">
            <span class="t"><i class="fa fa-edit"></i> بيانات الطلب</span>
        </div>
        <div class="pr-panel-bd">
            <div class="row g-3">
                <div class="form-group col-md-3">
                    <label><i class="fa fa-user text-primary me-1"></i> الموظف</label>
                    <select name="emp_no" id="emp_no" class="form-control sel2" <?= !$can_edit_form?'disabled':'' ?>>
                        <option value="">—</option>
                        <?php foreach ($emp_no_cons as $row): $sel=(!$isCreate&&$HaveRs&&$rs['EMP_NO']==$row['EMP_NO'])?'selected':''; ?>
                            <option <?=$sel?> value="<?= $row['EMP_NO'] ?>"><?= $row['EMP_NO'].' - '.$row['EMP_NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-sm-6 col-md-2">
                    <label><i class="fa fa-calendar text-success me-1"></i> الشهر</label>
                    <input type="text" name="the_month" id="the_month" class="form-control" placeholder="YYYYMM"
                           value="<?= (!$isCreate&&$HaveRs)?($rs['THE_MONTH']??''):date('Ym') ?>"
                           <?= !$can_edit_form?'readonly':'' ?>>
                </div>
                <div class="form-group col-sm-6 col-md-2">
                    <label><i class="fa fa-list text-info me-1"></i> نوع الطلب</label>
                    <select name="req_type" id="req_type" class="form-select" <?= !$can_edit_form?'disabled':'' ?>>
                        <option value="">— اختر —</option>
                        <?php foreach ($req_type_cons as $row): $sel=(!$isCreate&&$HaveRs&&$rs['REQ_TYPE']==$row['CON_NO'])?'selected':''; ?>
                            <option <?=$sel?> value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-sm-6 col-md-2">
                    <label><i class="fa fa-briefcase text-warning me-1"></i> المحفظة</label>
                    <select name="wallet_type" id="wallet_type" class="form-select" <?= !$can_edit_form?'disabled':'' ?>>
                        <option value="">— اختر —</option>
                        <?php foreach ($wallet_type_cons as $row): $sel=(!$isCreate&&$HaveRs&&$rs['WALLET_TYPE']==$row['CON_NO'])?'selected':''; ?>
                            <option <?=$sel?> value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-sm-6 col-md-2" id="grp_calc_method" style="display:none;">
                    <label><i class="fa fa-calculator me-1" style="color:var(--c-purple)"></i> طريقة الاحتساب</label>
                    <select name="calc_method" id="calc_method" class="form-select" <?= !$can_edit_form?'disabled':'' ?>>
                        <option value="">— اختر —</option>
                        <?php foreach ($calc_method_cons as $row): $sel=(!$isCreate&&$HaveRs&&$rs['CALC_METHOD']==$row['CON_NO'])?'selected':''; ?>
                            <option <?=$sel?> value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-sm-6 col-md-2" id="grp_percent" style="display:none;">
                    <label><i class="fa fa-percent text-success me-1"></i> النسبة</label>
                    <div class="input-group">
                        <input type="number" step="0.01" min="0" max="100" name="percent_val" id="percent_val" class="form-control"
                               value="<?=(!$isCreate&&$HaveRs)?($rs['PERCENT_VAL']??''):''?>" <?=!$can_edit_form?'readonly':''?>>
                        <span class="input-group-text" style="border-radius:0 10px 10px 0">%</span>
                    </div>
                </div>
                <div class="form-group col-sm-6 col-md-3" id="grp_amount" style="display:none;">
                    <label><i class="fa fa-money text-primary me-1"></i> المبلغ</label>
                    <input type="number" step="0.01" min="0" name="req_amount" id="req_amount" class="form-control" placeholder="0.00"
                           value="<?=(!$isCreate&&$HaveRs)?($rs['REQ_AMOUNT']??''):''?>" <?=!$can_edit_form?'readonly':''?>>
                    <div id="amountWarn" class="small text-danger mt-1" style="display:none;">
                        <i class="fa fa-exclamation-triangle"></i> <span id="amountWarnMsg"></span>
                    </div>
                </div>
                <div class="form-group col-sm-6 col-md-3" id="grp_salary_amount" style="display:none;">
                    <label><i class="fa fa-money text-info me-1"></i> جزء الراتب</label>
                    <input type="number" step="0.01" min="0" name="salary_amount" id="salary_amount" class="form-control" placeholder="0.00"
                           value="<?=(!$isCreate&&$HaveRs)?($rs['SALARY_AMOUNT']??''):''?>" <?=!$can_edit_form?'readonly':''?>>
                    <div id="salaryAmountWarn" class="small text-danger mt-1" style="display:none;">
                        <i class="fa fa-exclamation-triangle"></i> <span id="salaryAmountWarnMsg"></span>
                    </div>
                </div>
                <div class="form-group col-sm-6 col-md-3" id="grp_dues_amount" style="display:none;">
                    <label><i class="fa fa-archive text-purple me-1"></i> جزء المستحقات</label>
                    <input type="number" step="0.01" min="0" name="dues_amount" id="dues_amount" class="form-control" placeholder="0.00"
                           value="<?=(!$isCreate&&$HaveRs)?($rs['DUES_AMOUNT']??''):''?>" <?=!$can_edit_form?'readonly':''?>>
                    <div id="duesAmountWarn" class="small text-danger mt-1" style="display:none;">
                        <i class="fa fa-exclamation-triangle"></i> <span id="duesAmountWarnMsg"></span>
                    </div>
                </div>
                <div class="form-group col-md-4" id="grp_pay_type" style="display:none;">
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
                <div class="form-group col-12">
                    <label><i class="fa fa-sticky-note-o text-muted me-1"></i> ملاحظات</label>
                    <input type="text" name="note" id="note" class="form-control" placeholder="اختياري..."
                           value="<?=(!$isCreate&&$HaveRs)?($rs['NOTE']??''):''?>" <?=!$can_edit_form?'readonly':''?>>
                </div>
            </div>
        </div>
        <?php if ($can_edit_form): ?>
        <div class="pr-panel-ft">
            <a class="btn btn-light" href="<?= $back_url ?>"><i class="fa fa-times"></i> إلغاء</a>
            <button type="button" id="btnSaveBottom" disabled onclick="javascript:save(this);" class="btn btn-primary"><i class="fa fa-save"></i> حفظ</button>
        </div>
        <?php endif; ?>
    </div>
</form>

<!-- TREE MODAL -->
<div class="modal fade" id="payTypeTreeModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius:16px;overflow:hidden;border:none;box-shadow:0 25px 60px rgba(15,23,42,.18)">
            <div class="modal-header py-3 px-4" style="background:linear-gradient(135deg,#1e293b,#334155);border:none">
                <h5 class="modal-title text-white" style="font-size:.95rem;font-weight:700"><i class="fa fa-sitemap me-2" style="opacity:.7"></i>اختر بند المستحقات</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div id="pay_type_tree_loading" class="text-center py-5 text-muted">
                    <i class="fa fa-spinner fa-spin fa-2x"></i><p class="mt-3 mb-0" style="font-size:.85rem">جاري تحميل البنود...</p>
                </div>
                <div id="pay_type_tree_wrap" style="display:none;">
                    <div class="tree-search-wrap">
                        <i class="fa fa-search search-ico"></i>
                        <input type="text" id="pay_type_tree_search" placeholder="ابحث عن بند...">
                    </div>
                    <div class="overflow-auto p-3" style="max-height:55vh;background:#fafbfc;border-radius:12px;border:1px solid #e2e8f0">
                        <ul id="pay_type_tree" class="tree list-unstyled mb-0"></ul>
                    </div>
                    <div class="mt-2 text-muted" style="font-size:.72rem"><i class="fa fa-info-circle me-1"></i> اضغط على العنصر الفرعي لاختياره. العناصر ذات اللون الأخضر إضافات والأحمر خصومات.</div>
                </div>
            </div>
            <div class="modal-footer py-2 px-4" style="background:#f8fafc;border-top:1px solid #e2e8f0">
                <button type="button" class="btn btn-secondary btn-sm" style="border-radius:8px" data-bs-dismiss="modal"><i class="fa fa-times me-1"></i>إغلاق</button>
            </div>
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

$INIT_SALARY_EXISTS_JS = 'null';
if ($HaveRs && isset($rs['SALARY_EXISTS'])) {
    $INIT_SALARY_EXISTS_JS = (int)$rs['SALARY_EXISTS'] === 1 ? '1' : '0';
}

/*
 * ثوابت تطابق PL/SQL Package:
 * C_REQ_FULL_SALARY=1, C_REQ_PART_SALARY=2, C_REQ_LUMP_SUM=3
 * C_WALLET_SALARY=1, C_WALLET_DUES=2, C_WALLET_BOTH=3
 * C_CALC_PERCENT=1, C_CALC_FIXED=2
 */
$JS_CONSTANTS = json_encode([
    'REQ_FULL_SALARY' => 1,
    'REQ_PART_SALARY' => 2,
    'REQ_LUMP_SUM'    => 3,
    'WALLET_SALARY'   => 1,
    'WALLET_DUES'     => 2,
    'WALLET_BOTH'     => 3,
    'CALC_PERCENT'    => 1,
    'CALC_FIXED'      => 2,
]);

$scripts = <<<SCRIPT
<script type="text/javascript">

    $('.sel2:not("[id^=\'s2\']")').select2();

    $('#the_month').datetimepicker({
        format: 'YYYYMM',
        minViewMode: 'months',
        pickTime: false
    });

    var summaryUrl    = {$SUMMARY_URL_JS};
    var postUrl       = {$POST_URL_JS};
    var backUrl       = {$BACK_URL_JS};
    var approveUrl    = {$APPROVE_URL_JS};
    var payUrl        = {$PAY_URL_JS};
    var deleteUrl     = {$DELETE_URL_JS};
    var formId        = {$FORM_ID_JS};
    var canEdit       = {$CAN_EDIT_JS};
    var isCreate      = {$IS_CREATE_JS};
    var payTypeTreeUrl = {$PAY_TYPE_TREE_URL_JS};

    /* ========== Constants (from PL/SQL PAYMENT_REQ_PKG via PHP) ========== */
    var _C = {$JS_CONSTANTS};
    var C_REQ_FULL_SALARY = String(_C.REQ_FULL_SALARY);
    var C_REQ_PART_SALARY = String(_C.REQ_PART_SALARY);
    var C_REQ_LUMP_SUM    = String(_C.REQ_LUMP_SUM);

    var C_WALLET_SALARY = String(_C.WALLET_SALARY);
    var C_WALLET_DUES   = String(_C.WALLET_DUES);
    var C_WALLET_BOTH   = String(_C.WALLET_BOTH);

    var C_CALC_PERCENT = String(_C.CALC_PERCENT);
    var C_CALC_FIXED   = String(_C.CALC_FIXED);

    var summaryData   = null;
    var salaryExists  = {$INIT_SALARY_EXISTS_JS};

    function nFormat(x){
        try {
            var n = parseFloat(x);
            if(isNaN(n)) return '0.00';
            return n.toFixed(2);
        } catch(e){
            return '0.00';
        }
    }

    function setSaveEnabled(flag){
        $('#btnSave, #btnSaveBottom').prop('disabled', !flag);
    }

    setSaveEnabled(false);

    /* ========== Field Rules ========== */
    function applyFieldRules(){
        var rt = $('#req_type').val();
        var wt = $('#wallet_type').val();
        var cm = $('#calc_method').val();
        var noSal = (salaryExists === 0);

        $('#grp_calc_method, #grp_percent, #grp_amount, #grp_pay_type, #grp_salary_amount, #grp_dues_amount').hide();

        if(noSal){ $('#salaryNotComputedBanner').show(); }
        else { $('#salaryNotComputedBanner').hide(); }

        if(salaryExists === 1){
            $('#salaryStatusBar').show();
            $('#salaryStatusOk').show(); $('#salaryStatusNo').hide();
        } else if(salaryExists === 0){
            $('#salaryStatusBar').show();
            $('#salaryStatusOk').hide(); $('#salaryStatusNo').show();
        } else {
            $('#salaryStatusBar').hide();
        }

        $('#req_type option[value="'+C_REQ_FULL_SALARY+'"], #req_type option[value="'+C_REQ_PART_SALARY+'"]').prop('disabled', noSal);
        $('#wallet_type option[value="'+C_WALLET_SALARY+'"], #wallet_type option[value="'+C_WALLET_BOTH+'"]').prop('disabled', noSal);
        if(noSal){
            if(rt == C_REQ_FULL_SALARY || rt == C_REQ_PART_SALARY){ $('#req_type').val(''); rt = ''; }
            if(wt == C_WALLET_SALARY || wt == C_WALLET_BOTH){ $('#wallet_type').val(''); wt = ''; }
        }

        if(rt == C_REQ_FULL_SALARY){
            $('#wallet_type').val(C_WALLET_SALARY); wt = C_WALLET_SALARY;
            $('#req_amount').val('').prop('readonly', true);
        }
        else if(rt == C_REQ_PART_SALARY){
            $('#wallet_type').val(C_WALLET_SALARY); wt = C_WALLET_SALARY;
            $('#grp_calc_method').show();
            if(cm == C_CALC_PERCENT){
                $('#grp_percent, #grp_amount').show();
                $('#req_amount').prop('readonly', true);
            } else if(cm == C_CALC_FIXED){
                $('#grp_amount').show();
                $('#req_amount').prop('readonly', !canEdit);
                $('#percent_val').val('');
            }
        }
        else if(rt == C_REQ_LUMP_SUM){
            $('#grp_amount').show();
            $('#req_amount').prop('readonly', !canEdit);
            if(wt == C_WALLET_DUES || wt == C_WALLET_BOTH) $('#grp_pay_type').show();
            if(wt == C_WALLET_BOTH){
                $('#grp_salary_amount, #grp_dues_amount').show();
                $('#req_amount').prop('readonly', true); /* auto-calc from parts */
            }
        }

        /* wallet cards visibility */
        if(wt == C_WALLET_SALARY && !noSal){ $('#summarySalary').show(); $('#summaryDues').hide(); }
        else if(wt == C_WALLET_DUES){ $('#summarySalary').hide(); $('#summaryDues').show(); }
        else if(wt == C_WALLET_BOTH && !noSal){ $('#summarySalary').show(); $('#summaryDues').show(); }
        else {
            if(noSal){ $('#summarySalary').hide(); $('#summaryDues').show(); }
            else { $('#summarySalary, #summaryDues').hide(); }
        }

        calcAmount();
        validateForm();
    }

    /* ========== Calc Amount ========== */
    function calcAmount(){
        var rt = $('#req_type').val();
        var wt = $('#wallet_type').val();
        var cm = $('#calc_method').val();
        if(!summaryData) return;

        if(rt == C_REQ_FULL_SALARY){
            var rem = parseFloat(summaryData.SALARY_REMAINING || 0);
            $('#req_amount').val(rem > 0 ? rem.toFixed(2) : '');
        }
        else if(rt == C_REQ_PART_SALARY && cm == C_CALC_PERCENT){
            var pct = parseFloat($('#percent_val').val() || 0);
            var net = parseFloat(summaryData.NET_SALARY || 0);
            if(pct > 0 && net > 0){
                var amt = Math.round(net * pct / 100 * 100) / 100;
                $('#req_amount').val(amt.toFixed(2));
            } else {
                $('#req_amount').val('');
            }
        }
        else if(rt == C_REQ_LUMP_SUM && wt == C_WALLET_BOTH){
            /* كلاهما: المبلغ = جزء الراتب + جزء المستحقات */
            var salPart = parseFloat($('#salary_amount').val() || 0);
            var duePart = parseFloat($('#dues_amount').val() || 0);
            var total = salPart + duePart;
            $('#req_amount').val(total > 0 ? total.toFixed(2) : '');
        }
    }

    /* ========== Validate ========== */
    function validateForm(){
        if(!canEdit){ setSaveEnabled(false); return; }

        var empVal = $('#emp_no').val();
        var hasEmp = (empVal && empVal != '');
        var rt     = $('#req_type').val();
        var wt     = $('#wallet_type').val();
        var amt    = parseFloat($('#req_amount').val() || 0);
        var noSal  = (salaryExists === 0);

        if(!hasEmp || !rt || !wt){ setSaveEnabled(false); return; }
        if(!summaryData && !isCreate){ setSaveEnabled(false); return; }
        if(noSal && (wt == C_WALLET_SALARY || wt == C_WALLET_BOTH || rt == C_REQ_FULL_SALARY || rt == C_REQ_PART_SALARY)){ setSaveEnabled(false); return; }

        var ok = true;
        $('#amountWarn, #salaryAmountWarn, #duesAmountWarn').hide();

        if(rt == C_REQ_FULL_SALARY){
            if(summaryData && parseFloat(summaryData.SALARY_REMAINING || 0) <= 0) ok = false;
        }
        else if(rt == C_REQ_PART_SALARY){
            var cm2 = $('#calc_method').val();
            if(!cm2) ok = false;
            else if(cm2 == C_CALC_PERCENT && (!$('#percent_val').val() || parseFloat($('#percent_val').val()) <= 0)) ok = false;
            else if(cm2 == C_CALC_FIXED && amt <= 0) ok = false;

            if(summaryData && amt > 0){
                var sr = parseFloat(summaryData.SALARY_REMAINING || 0);
                if(amt > sr){
                    ok = false;
                    $('#amountWarnMsg').text('المبلغ يتجاوز المتبقي (' + nFormat(sr) + ')');
                    $('#amountWarn').show();
                }
            }
        }
        else if(rt == C_REQ_LUMP_SUM){
            if(amt <= 0) ok = false;
            if(summaryData && amt > 0){
                if(wt == C_WALLET_SALARY){
                    var sr2 = parseFloat(summaryData.SALARY_REMAINING || 0);
                    if(amt > sr2){ ok = false; $('#amountWarnMsg').text('يتجاوز المتبقي (' + nFormat(sr2) + ')'); $('#amountWarn').show(); }
                } else if(wt == C_WALLET_DUES){
                    var dr = parseFloat(summaryData.DUES_REMAINING || 0);
                    if(amt > dr){ ok = false; $('#amountWarnMsg').text('يتجاوز المتبقي (' + nFormat(dr) + ')'); $('#amountWarn').show(); }
                } else if(wt == C_WALLET_BOTH){
                    var salPart = parseFloat($('#salary_amount').val() || 0);
                    var duePart = parseFloat($('#dues_amount').val() || 0);
                    var sr3 = parseFloat(summaryData.SALARY_REMAINING || 0);
                    var dr3 = parseFloat(summaryData.DUES_REMAINING || 0);

                    if(salPart <= 0 || duePart <= 0){
                        ok = false;
                        $('#amountWarnMsg').text('عند اختيار المحفظتين معاً، يجب أن يكون كلا المبلغين أكبر من صفر');
                        $('#amountWarn').show();
                    }
                    else if(Math.abs((salPart + duePart) - amt) > 0.01){
                        ok = false;
                        $('#amountWarnMsg').text('مجموع التوزيع (' + nFormat(salPart + duePart) + ') لا يساوي المبلغ (' + nFormat(amt) + ')');
                        $('#amountWarn').show();
                    } else {
                        if(salPart > sr3){
                            ok = false;
                            $('#salaryAmountWarnMsg').text('يتجاوز المتبقي (' + nFormat(sr3) + ')');
                            $('#salaryAmountWarn').show();
                        }
                        if(duePart > dr3){
                            ok = false;
                            $('#duesAmountWarnMsg').text('يتجاوز المتبقي (' + nFormat(dr3) + ')');
                            $('#duesAmountWarn').show();
                        }
                    }

                    if(!$('#pay_type').val()) ok = false;
                }
            }

            /* wallet dues needs pay_type */
            if(wt == C_WALLET_DUES && !$('#pay_type').val()) ok = false;
        }

        setSaveEnabled(ok);
    }

    /* ========== Load Summary ========== */
    function loadSummary(){
        var empNo    = $('#emp_no').val();
        var theMonth = $('#the_month').val();

        if(!empNo){
            $('#summaryWrap').hide();
            $('#salaryNotComputedBanner').hide();
            summaryData = null; salaryExists = null;
            validateForm();
            return;
        }

        $('#summaryWrap').show();

        get_data(summaryUrl, {emp_no: empNo, the_month: theMonth || ''}, function(resp){
            try {
                var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
                if(!j || !j.ok){ summaryData = null; salaryExists = null; validateForm(); return; }

                var rows = j.data || [];
                var r = Array.isArray(rows) && rows.length > 0 ? rows[0] : (rows || {});
                summaryData = r;
                salaryExists = (r.SALARY_EXISTS !== undefined) ? parseInt(r.SALARY_EXISTS) : 1;

                $('#sumNetSalary').text(nFormat(r.NET_SALARY || 0));
                $('#sumSalaryPaid').text(nFormat(r.SALARY_PAID || 0));
                $('#sumSalaryRem').text(nFormat(r.SALARY_REMAINING || 0));

                $('#sumDuesBase').text(nFormat(r.DUES_BASE || 0));
                $('#sumDuesAdd').text(nFormat(r.DUES_ADD || 0));
                $('#sumDuesDed').text(nFormat(r.DUES_DED || 0));
                $('#sumDuesRem').text(nFormat(r.DUES_REMAINING || 0));

                applyFieldRules();
            } catch(e){
                summaryData = null; salaryExists = null; validateForm();
            }
        });
    }

    /* ========== Save ========== */
    function save(obj){
        validateForm();
        if($('#btnSave').prop('disabled') && $('#btnSaveBottom').prop('disabled')) return;

        setSaveEnabled(false);
        $('#btnSave, #btnSaveBottom').html('<i class="fa fa-spinner fa-spin"></i> جاري الحفظ...');

        get_data(postUrl, $('#' + formId).serialize(), function(data){
            if(data == '1' || (String(data).match(/^\d+$/) && parseInt(data) > 0)){
                success_msg('رسالة', 'تم الحفظ بنجاح');
                get_to_link(backUrl);
            } else {
                resetSaveBtn();
                danger_msg('تحذير', data);
            }
        }, 'html');
    }

    function resetSaveBtn(){
        $('#btnSave, #btnSaveBottom').html('<i class="fa fa-save"></i> حفظ');
        validateForm();
    }

    /* ========== Approve ========== */
    function doApprove(obj){
        if(isDoubleClicked($(obj))) return;
        var reqId = $('#req_id').val();
        if(!reqId) return;
        if(!confirm('هل تريد اعتماد هذا الطلب؟')) return;
        get_data(approveUrl, {req_id: reqId}, function(data){
            if(data == '1'){ success_msg('رسالة','تم الاعتماد بنجاح'); reload_Page(); }
            else { danger_msg('تحذير', data); }
        },'html');
    }

    /* ========== Pay ========== */
    function doPay(obj){
        if(isDoubleClicked($(obj))) return;
        var reqId = $('#req_id').val();
        if(!reqId) return;
        if(!confirm('هل تريد صرف هذا الطلب؟')) return;
        get_data(payUrl, {req_id: reqId}, function(data){
            if(data == '1'){ success_msg('رسالة','تم الصرف بنجاح'); reload_Page(); }
            else { danger_msg('تحذير', data); }
        },'html');
    }

    /* ========== Cancel ========== */
    function doCancel(obj){
        if(isDoubleClicked($(obj))) return;
        var reqId = $('#req_id').val();
        if(!reqId) return;
        var note = prompt('سبب الإلغاء:', 'إلغاء');
        if(note === null) return;
        get_data(deleteUrl, {req_id: reqId, cancel_note: note || 'إلغاء'}, function(data){
            if(data == '1'){ success_msg('رسالة','تم الإلغاء'); reload_Page(); }
            else { danger_msg('تحذير', data); }
        },'html');
    }

    /* ========== Tree ========== */
    var payTypeTreeData = null;

    function buildPayTypeTreeHtml(nodes){
        if(!nodes || nodes.length === 0) return '';
        var html = '';
        for(var i = 0; i < nodes.length; i++){
            var n = nodes[i];
            var hasChildren = n.children && n.children.length > 0;
            var lineType = (n.attributes && n.attributes.lineType) ? n.attributes.lineType : 1;
            var ltClass = 'lt-' + lineType;
            if(hasChildren){
                html += '<li class="parent_li" data-id="' + n.id + '">';
                html += '<span class="tree-node ' + ltClass + ' pay-type-parent"><i class="fa fa-plus tree-icon"></i> ' + (n.text || '') + '</span>';
                html += '<ul class="list-unstyled ms-3" style="display:none;">' + buildPayTypeTreeHtml(n.children) + '</ul>';
                html += '</li>';
            } else {
                var isDeduction = (lineType == 2);
                var disabledClass = isDeduction ? '' : ' leaf-disabled';
                html += '<li data-id="' + n.id + '" data-line-type="' + lineType + '">';
                html += '<span class="tree-node ' + ltClass + ' pay-type-leaf' + disabledClass + '"><i class="fa tree-icon" style="display:inline-block;width:16px;"></i> ' + (n.text || '') + '</span>';
                html += '</li>';
            }
        }
        return html;
    }

    function loadPayTypeTree(){
        var loading = $('#pay_type_tree_loading');
        var wrap = $('#pay_type_tree_wrap');
        var tree = $('#pay_type_tree');

        if(payTypeTreeData){
            loading.hide(); tree.html(buildPayTypeTreeHtml(payTypeTreeData));
            wrap.show(); bindPayTypeTreeEvents(); return;
        }
        if(!payTypeTreeUrl) return;
        loading.show(); wrap.hide(); tree.empty();

        jQuery.getJSON(payTypeTreeUrl).done(function(data){
            var parsed = data;
            if(typeof data === 'string'){ try { parsed = JSON.parse(data); } catch(e){ parsed = []; } }
            /* handle both plain array and object-wrapped e.g. {data:[...]} or {children:[...]} */
            if(parsed && !Array.isArray(parsed)){
                if(Array.isArray(parsed.data)) parsed = parsed.data;
                else if(Array.isArray(parsed.children)) parsed = parsed.children;
                else parsed = [];
            }
            payTypeTreeData = Array.isArray(parsed) ? parsed : [];
            loading.hide(); tree.html(buildPayTypeTreeHtml(payTypeTreeData));
            wrap.show(); bindPayTypeTreeEvents();
            if(payTypeTreeData.length === 0){
                tree.html('<div class="text-muted text-center py-3"><i class="fa fa-info-circle"></i> لا توجد بنود</div>');
            }
        }).fail(function(xhr, status, err){
            loading.html('<span class="text-danger"><i class="fa fa-exclamation-triangle"></i> فشل تحميل الشجرة: ' + (err || status) + '</span>');
        });
    }

    function bindPayTypeTreeEvents(){
        $('#pay_type_tree').off('click', '.pay-type-parent').on('click', '.pay-type-parent', function(e){
            e.stopPropagation();
            var ul = $(this).closest('li').children('ul');
            var icon = $(this).find('.tree-icon');
            if(ul.is(':visible')){ ul.slideUp(200); icon.removeClass('fa-minus').addClass('fa-plus'); }
            else { ul.slideDown(200); icon.removeClass('fa-plus').addClass('fa-minus'); }
        });

        $('#pay_type_tree').off('click', '.pay-type-leaf').on('click', '.pay-type-leaf', function(e){
            e.stopPropagation();
            if($(this).hasClass('leaf-disabled')) return;
            $('#pay_type_tree .pay-type-leaf').css({'background':'','color':'','border-color':''});
            $(this).css({'background':'#2563eb','color':'#fff','border-color':'#2563eb'});
            var li = $(this).closest('li');
            var id = li.data('id');
            var text = $(this).text().trim();
            if(id){
                $('#pay_type').val(id);
                $('#pay_type_display').val(text);
                setTimeout(function(){
                    $('#payTypeTreeModal').modal('hide');
                }, 300);
                validateForm();
            }
        });

        /* Tree search */
        $('#pay_type_tree_search').off('input').on('input', function(){
            var q = $(this).val().trim().toLowerCase();
            if(!q){
                $('#pay_type_tree li').show();
                $('#pay_type_tree ul').hide();
                $('#pay_type_tree .tree-icon').removeClass('fa-minus').addClass('fa-plus');
                return;
            }
            $('#pay_type_tree li').each(function(){
                var txt = $(this).children('.tree-node').text().toLowerCase();
                var hasMatch = txt.indexOf(q) > -1;
                var childMatch = $(this).find('.tree-node').filter(function(){
                    return $(this).text().toLowerCase().indexOf(q) > -1;
                }).length > 0;
                if(hasMatch || childMatch){
                    $(this).show();
                    $(this).parents('li').show();
                    $(this).parents('li').children('ul').show();
                    $(this).parents('li').children('.pay-type-parent').find('.tree-icon').removeClass('fa-plus').addClass('fa-minus');
                } else {
                    $(this).hide();
                }
            });
        });
    }

    /* ========== Init & Events ========== */
    if(typeof initFunctions === 'function') initFunctions();

    $('#req_type, #wallet_type, #calc_method').on('change', applyFieldRules);
    $('#percent_val').on('keyup change', function(){ calcAmount(); validateForm(); });
    $('#req_amount').on('keyup change', validateForm);
    $('#salary_amount, #dues_amount').on('keyup change', function(){ calcAmount(); validateForm(); });
    $('#emp_no').on('change', function(){ loadSummary(); });
    $('#the_month').on('change dp.change', loadSummary);

    $('#btn_pay_type_tree').on('click', function(e){
        e.preventDefault();
        loadPayTypeTree();
        $('#payTypeTreeModal').modal('show');
        $('#pay_type_tree_search').val('');
        setTimeout(function(){ $('#pay_type_tree_search').focus(); }, 400);
    });

    loadSummary();
    setTimeout(applyFieldRules, 200);

</script>
SCRIPT;

sec_scripts($scripts);
?>
