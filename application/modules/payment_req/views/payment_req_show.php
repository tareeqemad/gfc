<?php
$MODULE_NAME = 'payment_req';
$TB_NAME     = 'payment_req';

$back_url       = base_url("$MODULE_NAME/$TB_NAME");
$post_url       = $isCreate ? base_url("$MODULE_NAME/$TB_NAME/create") : base_url("$MODULE_NAME/$TB_NAME/edit");
$approve_url    = base_url("$MODULE_NAME/$TB_NAME/approve");
$unapprove_url  = base_url("$MODULE_NAME/$TB_NAME/unapprove");
$pay_url        = base_url("$MODULE_NAME/$TB_NAME/do_pay");
$delete_url     = base_url("$MODULE_NAME/$TB_NAME/delete");
$detail_add_url = base_url("$MODULE_NAME/$TB_NAME/detail_add");
$detail_del_url = base_url("$MODULE_NAME/$TB_NAME/detail_delete");
$detail_lst_url = base_url("$MODULE_NAME/$TB_NAME/detail_list");

$HaveRs = isset($master_tb_data) && is_array($master_tb_data) && count($master_tb_data) > 0;
$rs     = $HaveRs ? $master_tb_data[0] : [];

$cur_status    = $HaveRs ? (int)($rs['STATUS'] ?? 0) : -1;
$is_draft      = ($cur_status == 0);
$is_approved   = ($cur_status == 1);
$is_paid       = ($cur_status == 2);
$is_cancelled  = ($cur_status == 9);
// زر الحفظ فقط في شاشة الإنشاء (الـ wizard form موجود فيها فقط).
// المسودة لا تحتاجه لأن حقول الماستر (الشهر/النوع/...) غير قابلة للتعديل بعد الإنشاء.
$can_edit_form = $isCreate;
$req_id_val    = $HaveRs ? ($rs['REQ_ID'] ?? '') : '';

$detail_rows = isset($detail_rows) && is_array($detail_rows) ? $detail_rows : [];
$emp_count   = count($detail_rows);
$total_amount = 0;
$total_accrued_323 = 0;
$total_net_calc = 0;
foreach ($detail_rows as $d) {
    $total_amount     += (float)($d['REQ_AMOUNT'] ?? 0);
    $total_accrued_323 += (float)($d['ACCRUED_323_CALC'] ?? 0);
    $total_net_calc   += (float)($d['NET_SALARY_CALC'] ?? 0);
}
// بعد الاعتماد (جزئي أو كلي) لا يُسمح بإضافة موظفين
$can_add = !$isCreate && $cur_status == 0;

echo AntiForgeryToken();
?>

<style>
.pr2-status{display:flex;align-items:center;gap:.75rem;padding:.65rem 1.2rem;border-radius:10px;font-weight:700;font-size:.85rem;margin-bottom:1rem}
.pr2-status.s0{background:#fef3c7;color:#92400e}.pr2-status.s1{background:#dbeafe;color:#1e40af}
.pr2-status.s2{background:#d1fae5;color:#065f46}.pr2-status.s3{background:#e0e7ff;color:#3730a3}
.pr2-status.s4{background:#ccfbf1;color:#0f766e}.pr2-status.s9{background:#fee2e2;color:#991b1b}
.card-body .form-group label{font-weight:600;font-size:.78rem;color:#64748b;margin-bottom:.3rem;display:block}
.detail-tbl td,.detail-tbl th{vertical-align:middle;font-size:.82rem}
.detail-tbl .amt{font-weight:700;color:#1e293b}
.detail-total{background:#f0fdf4;font-weight:800}
/* Badges على صف الموظف */
.b-tag{font-size:.62rem;padding:.1em .42em;border-radius:4px;font-weight:700;margin-inline-start:.3rem;display:inline-block;vertical-align:middle}
.b-tag i{margin-left:2px}
.b-tag.b-multi{background:#e0f2fe;color:#0369a1;border:1px solid #7dd3fc}
.b-tag.b-benef{background:#f5f3ff;color:#6d28d9;border:1px solid #c4b5fd}
.b-tag.b-warn{background:#fee2e2;color:#991b1b;border:1px solid #fca5a5}
.b-tag.b-retired{background:#f1f5f9;color:#475569;border:1px solid #cbd5e1}
.b-tag.b-pause{background:#f5f3ff;color:#6d28d9;border:1px solid #c4b5fd}
.b-tag.b-blocked{background:#7f1d1d;color:#fff;border:1px solid #991b1b;animation:b-pulse 1.5s ease-in-out infinite}
@keyframes b-pulse{0%,100%{opacity:1}50%{opacity:.65}}

/* Legend ألوان حالات الموظفين */
.emp-legend{display:flex;flex-wrap:wrap;align-items:center;gap:1rem;padding:.55rem .9rem;background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;font-size:.78rem}
.emp-legend-title{font-weight:700;color:#475569}
.emp-legend-item{display:inline-flex;align-items:center;gap:.4rem}
.emp-legend-item small{color:#64748b;font-size:.7rem}
.emp-legend-swatch{display:inline-block;width:18px;height:14px;border-radius:3px;border:1px solid rgba(0,0,0,.05)}
/* صف التوزيع المدمج */
.distrib-row td{border-top:0!important;border-bottom:1px solid #e2e8f0}
.distrib-row.distrib-single td{padding:.15rem .75rem!important}
.distrib-warns{display:flex;flex-wrap:wrap;gap:.4rem;margin-bottom:.3rem;font-size:.7rem}
.distrib-warns .d-warn{background:#fef2f2;color:#991b1b;border:1px solid #fca5a5;padding:.15em .5em;border-radius:4px;font-weight:600}
.distrib-warns .d-info{background:#f5f3ff;color:#6d28d9;border:1px solid #c4b5fd;padding:.15em .5em;border-radius:4px;font-weight:600}
.distrib-warns .d-block{background:#7f1d1d;color:#fff;border:1px solid #991b1b;padding:.3em .6em;border-radius:4px;font-weight:600;display:block;width:100%;font-size:.74rem}
.distrib-warns .d-block i{margin-left:4px}
.distrib-warns i{margin-left:3px}

/* Tabs styling */
#detailTabs .nav-link{font-size:.85rem;font-weight:600;color:#64748b;padding:.5rem 1.1rem}
#detailTabs .nav-link.active{color:#1e40af;background:#eff6ff;border-color:#bfdbfe #bfdbfe #fff}
#detailTabs .nav-link .badge{font-size:.7rem;font-weight:700}

/* 🆕 أزرار override toggle مدمجة — تضمن تظهر كلها في صف واحد */
.ovr-toggle{display:inline-flex;flex-wrap:nowrap;vertical-align:middle}
.ovr-toggle .btn,.ovr-tog{padding:.18rem .38rem !important;font-size:.7rem !important;line-height:1.1;min-width:auto}
.ovr-toggle .btn i,.ovr-tog i{font-size:.78rem;margin:0}
/* العمود نفسه: لا wrap + لا overflow مزعج */
td.actions-cell{white-space:nowrap;min-width:170px}

/* Tab 2: Recipients */
.rcp-stats{display:flex;flex-wrap:wrap;gap:.4rem;margin-bottom:.6rem}
.rcp-stat{flex:1;min-width:130px;text-align:center;padding:.5rem .6rem;background:#fff;border:1px solid #e2e8f0;border-radius:8px}
.rcp-stat .rs-lbl{font-size:.65rem;color:#64748b;font-weight:600;margin-bottom:.15rem}
.rcp-stat .rs-lbl i{margin-left:3px}
.rcp-stat .rs-val{font-size:1.05rem;font-weight:800;color:#1e293b;direction:ltr}
.rcp-stat.c-self{background:#f0fdf4;border-color:#bbf7d0}
.rcp-stat.c-self .rs-val{color:#15803d}
.rcp-stat.c-benef{background:#faf5ff;border-color:#c4b5fd}
.rcp-stat.c-benef .rs-val{color:#6d28d9}
.rcp-stat.c-owner{background:#fff7ed;border-color:#fed7aa}
.rcp-stat.c-owner .rs-val{color:#9a3412}
.rcp-stat.c-amt{background:#1e293b;border-color:#1e293b}
.rcp-stat.c-amt .rs-lbl{color:#cbd5e1}
.rcp-stat.c-amt .rs-val{color:#fff}
.rcp-stat.c-warn{background:#fef2f2;border-color:#fca5a5}
.rcp-stat.c-warn .rs-val{color:#991b1b}
.rcp-stat.c-split{background:#e0f2fe;border-color:#7dd3fc}
.rcp-stat.c-split .rs-val{color:#0369a1}
.rcp-stat.c-dues{background:#faf5ff;border-color:#c4b5fd}
.rcp-stat.c-dues .rs-val{color:#6b21a8}
.rcp-stat.c-net{background:#f0fdf4;border-color:#bbf7d0}
.rcp-stat.c-net .rs-val{color:#15803d}
.rcp-stat .rs-sub{font-size:.62rem;color:#64748b;font-weight:600;margin-top:.1rem}
.rcp-split-pill{display:inline-block;font-size:.65rem;padding:.15em .5em;border-radius:4px;font-weight:700;white-space:nowrap}
.rcp-split-tag{display:inline-block;font-size:.6rem;padding:.05em .35em;border-radius:3px;background:#e0f2fe;color:#0369a1;font-weight:700;margin-inline-start:.3rem}
.rcp-split-tag i{margin-left:2px}

/* 🏦 زر تصدير الملف البنكي — مميّز ومحترف */
.btn-bank-csv{
    display:inline-flex;
    align-items:center;
    gap:.55rem;
    padding:.4rem .9rem;
    background:linear-gradient(135deg,#0f766e 0%,#115e59 100%);
    color:#fff!important;
    border:0;
    border-radius:8px;
    font-size:.78rem;
    font-weight:700;
    text-decoration:none!important;
    box-shadow:0 2px 4px rgba(15,118,110,.25), 0 1px 2px rgba(0,0,0,.08);
    transition:all .15s ease;
    position:relative;
    overflow:hidden;
}
.btn-bank-csv:hover{
    background:linear-gradient(135deg,#0d9488 0%,#0f766e 100%);
    transform:translateY(-1px);
    box-shadow:0 4px 8px rgba(15,118,110,.35), 0 2px 4px rgba(0,0,0,.1);
    color:#fff!important;
}
.btn-bank-csv:active{transform:translateY(0)}
.btn-bank-csv .bcsv-icon{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    width:28px;height:28px;
    background:rgba(255,255,255,.18);
    border-radius:6px;
    font-size:.95rem;
}
.btn-bank-csv .bcsv-text{
    display:flex;
    flex-direction:column;
    line-height:1.15;
    text-align:start;
}
.btn-bank-csv .bcsv-title{font-weight:800;font-size:.82rem}
.btn-bank-csv .bcsv-sub{font-size:.62rem;opacity:.85;font-weight:500}
.btn-bank-csv .bcsv-dl{
    font-size:.75rem;
    margin-inline-start:.2rem;
    opacity:.75;
}
.btn-bank-csv:hover .bcsv-dl{
    opacity:1;
    animation:bcsv-bounce .6s ease infinite;
}
@keyframes bcsv-bounce{
    0%,100%{transform:translateY(0)}
    50%{transform:translateY(2px)}
}
.rcp-toolbar{display:flex;align-items:center;gap:.5rem;margin-bottom:.6rem;flex-wrap:wrap}
.rcp-tbl{font-size:.82rem}
.rcp-tbl th{background:#f8fafc;font-size:.75rem;color:#475569;font-weight:700}
.rcp-tbl tr.row-hidden{display:none}
.rcp-tbl tr:hover{background:#fffbeb}
.rcp-type{display:inline-block;font-size:.65rem;padding:.15em .5em;border-radius:4px;font-weight:700;white-space:nowrap}
.rcp-type i{margin-left:3px}
.rcp-def{font-size:.6rem;background:#fef3c7;color:#92400e;padding:.05em .35em;border-radius:3px;font-weight:600;margin-inline-start:.3rem}

/* Pills مدمجة (تصميم جديد) */
.dist-mini{display:flex;flex-wrap:wrap;gap:.35rem;align-items:center}
.dist-pill{display:inline-flex;align-items:center;gap:.35rem;padding:.18em .55em;background:#fff;border:1px solid #e2e8f0;border-radius:14px;font-size:.7rem;line-height:1.4;color:#475569;white-space:nowrap}
.dist-pill.compact{background:transparent;border:0;padding:.1em .25em;color:#64748b;font-size:.72rem}
.dist-pill i{font-size:.78rem;color:#1e40af}
.dist-pill .dp-prov{font-weight:700;color:#1e293b}
.dist-pill .dp-branch{color:#64748b;font-size:.68rem}
.dist-pill .dp-st{font-size:.62rem;color:#475569;background:#f1f5f9;padding:.05em .35em;border-radius:3px;font-weight:600}
.dist-pill .dp-amt{font-weight:800;color:#059669;direction:ltr;font-size:.78rem;margin-inline-start:.15rem}
.dist-pill .dp-amt.blocked{color:#991b1b;text-decoration:line-through}
.dist-pill .dp-def{font-size:.6rem;background:#fef3c7;color:#92400e;padding:.05em .3em;border-radius:3px;font-weight:600}
.dist-pill .dp-benef{background:#f5f3ff;color:#6d28d9;padding:.05em .35em;border-radius:3px;font-weight:600}
.dist-pill .dp-benef small{color:#7c3aed;margin-inline-start:.15em;font-size:.95em}
.dist-pill .dp-owner{background:#fff7ed;color:#9a3412;padding:.05em .35em;border-radius:3px;font-weight:600}
.dist-pill.is-benef{background:#faf5ff;border-color:#c4b5fd}
.dist-pill.is-inactive{opacity:.6;background:#f1f5f9;border-style:dashed}
.dist-pill.is-inactive .dp-prov{text-decoration:line-through;color:#64748b}
.dist-pill.is-inactive i{color:#94a3b8}
.dist-pill.is-blocked{background:#fef2f2;border-color:#fca5a5}
.info-row{display:flex;gap:1rem;flex-wrap:wrap;margin-bottom:.75rem}
.info-item{flex:1;min-width:140px;padding:.5rem .75rem;border-radius:8px;border:1px solid #e2e8f0;background:#f8fafc}
.info-item .lbl{font-size:.65rem;color:#94a3b8;margin-bottom:.1rem}.info-item .val{font-size:.9rem;font-weight:700;color:#1e293b}
.info-item.highlight{background:#eff6ff;border-color:#bfdbfe}.info-item.highlight .val{color:#1e40af;font-size:1rem}
.info-bar{display:flex;align-items:center;gap:.75rem;flex-wrap:wrap;padding:.55rem .85rem;border-radius:10px;background:#f8fafc;border:1px solid #e2e8f0;margin-bottom:.75rem}
.info-chips{display:flex;align-items:center;gap:.55rem;flex-wrap:wrap;flex:1}
.info-chips .chip{display:inline-flex;align-items:center;gap:.35rem;font-size:.78rem;color:#475569;padding:.22rem .55rem;background:#fff;border:1px solid #e2e8f0;border-radius:6px;white-space:nowrap}
.info-chips .chip b{color:#1e293b;font-weight:700}
.info-chips .chip i{color:#94a3b8;font-size:.75rem}
.info-chips .chip.pct b,.info-chips .chip.pct{color:#1e40af}
.info-chips .chip.pay b{color:#7c3aed}
.info-total{display:inline-flex;align-items:center;gap:.6rem;padding:.5rem 1rem;background:#ecfdf5;border:1px solid #86efac;border-radius:8px;white-space:nowrap;line-height:1.2}
.info-total .lbl{font-size:.72rem;color:#065f46;font-weight:600;line-height:1;display:inline-flex;align-items:center}
.info-total .val{font-size:1.1rem;font-weight:800;color:#059669;direction:ltr;line-height:1;display:inline-flex;align-items:center}
.info-total .sub{font-size:.7rem;color:#16a34a;font-weight:600;line-height:1}
.audit-row{font-size:.72rem;color:#94a3b8;margin-top:.5rem}
.audit-row span{margin-left:1rem}
/* Wallet summary cards */
.pr-row{display:flex;gap:.5rem;margin-bottom:.5rem;flex-wrap:wrap}
.pr-card{flex:1;min-width:120px;text-align:center;padding:.6rem .5rem;border-radius:10px;border:1px solid #e2e8f0;background:#fff}
.pr-card .c-label{font-size:.65rem;color:#64748b;margin-bottom:.15rem}
.pr-card .c-label i{margin-left:3px}
.pr-card .c-val{font-size:1rem;font-weight:800;direction:ltr;display:inline-block}
.pr-card .c-cnt{font-size:.7rem;font-weight:600;color:#94a3b8;margin-top:.1rem}
.pr-card.c-total{background:#1e293b;border-color:#1e293b}.pr-card.c-total .c-label{color:#94a3b8}.pr-card.c-total .c-val{color:#fff;font-size:1.1rem}
.pr-card.c-active{background:#eff6ff;border-color:#bfdbfe}.pr-card.c-active .c-val{color:#1e40af}
.pr-card.c-net{background:#f0fdf4;border-color:#bbf7d0}.pr-card.c-net .c-val{color:#059669;font-size:1.1rem}
/* Wizard type cards */
.type-cards-row{display:flex;gap:1rem;flex-wrap:wrap}
.type-card{flex:1;min-width:180px;padding:1.2rem 1rem;border:2px solid #e2e8f0;border-radius:12px;cursor:pointer;text-align:center;transition:all .2s;background:#fff}
.type-card:hover{border-color:#93c5fd;background:#eff6ff;transform:translateY(-2px);box-shadow:0 4px 12px rgba(0,0,0,.08)}
.type-card.selected{border-color:#2563eb;background:#eff6ff;box-shadow:0 0 0 3px rgba(37,99,235,.15)}
.type-card .tc-icon{font-size:1.8rem;color:#94a3b8;margin-bottom:.5rem}
.type-card.selected .tc-icon{color:#2563eb}
.type-card .tc-title{font-size:.92rem;font-weight:700;color:#1e293b;margin-bottom:.25rem}
.type-card .tc-desc{font-size:.73rem;color:#94a3b8;line-height:1.4}
.type-card.selected .tc-title{color:#1e40af}
</style>

<div class="page-header">
    <div><h1 class="page-title"><?= $title ?><?php if ($HaveRs): ?> <small style="font-weight:400;color:var(--c-slate);font-size:.85rem">- <?= $rs['REQ_NO'] ?? '' ?></small><?php endif; ?></h1></div>
    <div class="ms-auto pageheader-btn">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= $back_url ?>">صرف الرواتب والمستحقات</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= $title ?></li>
        </ol>
    </div>
</div>

<div class="row">
<div class="col-lg-12">
<div class="card">
    <div class="card-header d-flex align-items-center">
        <h3 class="card-title"><?= $title ?></h3>
        <div class="ms-auto d-flex gap-1 flex-wrap align-items-center">
            <a class="btn btn-light btn-sm" href="<?= $back_url ?>"><i class="fa fa-arrow-right me-1"></i> رجوع</a>
            <?php if (!$isCreate && $HaveRs && $emp_count > 0): ?>
                <button type="button" onclick="exportDetailExcel();" class="btn btn-info btn-sm text-white"><i class="fa fa-file-excel-o me-1"></i> Excel</button>
            <?php endif; ?>
            <?php
            // 🆕 ملفات الإكسل الأصلية المستوردة (في حال الطلب أُنشئ عبر استيراد)
            $import_files = $import_files ?? [];
            if (!$isCreate && !empty($import_files)):
                $req_id_dl = (int)($rs['REQ_ID'] ?? 0);
            ?>
                <?php if (count($import_files) === 1):
                    $f = $import_files[0];
                    $fp = $f['FILE_PATH'] ?? '';
                ?>
                    <a class="btn btn-outline-secondary btn-sm"
                       href="<?= base_url("$MODULE_NAME/$TB_NAME/public_download_import_file/{$req_id_dl}/" . rawurlencode($fp)) ?>"
                       title="تنزيل ملف الإكسل الأصلي المستورد (قبل دمج المكررين)">
                        <i class="fa fa-download me-1"></i> Excel الأصلي
                    </a>
                <?php else: ?>
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                title="ملفات الإكسل الأصلية المستوردة">
                            <i class="fa fa-download me-1"></i> Excel الأصلي (<?= count($import_files) ?>)
                        </button>
                        <ul class="dropdown-menu" style="font-size:.82rem">
                            <?php foreach ($import_files as $f): $fp = $f['FILE_PATH'] ?? ''; ?>
                                <li>
                                    <a class="dropdown-item" href="<?= base_url("$MODULE_NAME/$TB_NAME/public_download_import_file/{$req_id_dl}/" . rawurlencode($fp)) ?>">
                                        <i class="fa fa-file-excel-o text-success me-1"></i>
                                        <?= htmlspecialchars($f['FILE_NAME'] ?? $fp) ?>
                                        <?php if (!empty($f['NOTE'])): ?>
                                            <small class="d-block text-muted" style="font-size:.7rem"><?= htmlspecialchars($f['NOTE']) ?></small>
                                        <?php endif; ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
            <?php if ($can_edit_form): ?>
                <button type="button" id="btnSave" onclick="saveMaster(this);" class="btn btn-primary btn-sm"><i class="fa fa-save me-1"></i> حفظ</button>
            <?php endif; ?>
            <?php if (!$isCreate && ($is_draft || $cur_status == 3) && HaveAccess($approve_url)): ?>
                <button type="button" onclick="doApprove(this);" class="btn btn-indigo btn-sm text-white"><i class="fa fa-check-circle me-1"></i> اعتماد</button>
            <?php endif; ?>
            <?php if (!$isCreate && ($is_approved || $cur_status == 3 || $cur_status == 4)): ?>
                <a class="btn btn-success btn-sm" href="<?= base_url("$MODULE_NAME/$TB_NAME/batch") ?>"><i class="fa fa-calculator me-1"></i> احتساب الصرف</a>
            <?php endif; ?>
            <?php if (!$isCreate && !$is_cancelled): ?>
                <?php if ($is_draft && HaveAccess($delete_url)): ?>
                    <div class="vr mx-1"></div>
                    <button type="button" onclick="doDelete(this);" class="btn btn-outline-danger btn-sm"><i class="fa fa-trash me-1"></i> حذف</button>
                <?php endif; ?>
                <?php if (in_array($cur_status, [1, 3]) && HaveAccess($unapprove_url)): ?>
                    <div class="vr mx-1"></div>
                    <button type="button" onclick="doUnapprove(this);" class="btn btn-outline-warning btn-sm"><i class="fa fa-undo me-1"></i> إلغاء الاعتماد</button>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
    <div class="card-body">

<?php
// 🆕 نُعرّف $req_type_val بقيمة افتراضية (0) عشان يكون متاح في أي مكان
//     (مثل JS heredoc اللي يُولَّد دائماً، حتى في وضع الإنشاء)
$req_type_val = (!$isCreate && $HaveRs) ? (int)($rs['REQ_TYPE'] ?? 0) : 0;
?>
<?php if (!$isCreate && $HaveRs): ?>

<!-- STATUS BAR -->
<div class="pr2-status s<?= $cur_status ?>" id="masterStatusBar">
    <i class="fa fa-info-circle"></i>
    <span id="masterStatusName"><?= $rs['STATUS_NAME'] ?? '' ?></span>
    <?php if ($is_approved): ?>
        — <a href="<?= base_url("$MODULE_NAME/$TB_NAME/batch") ?>" class="fw-bold" style="color:inherit;text-decoration:underline">احتساب الصرف</a>
    <?php endif; ?>
    <?php if ($is_paid && !empty($rs['PAY_DATE'])): ?>
        — صُرف: <?= $rs['PAY_DATE'] ?>
    <?php endif; ?>
    <?php if ($is_cancelled && !empty($rs['CANCEL_NOTE'])): ?>
        — <?= $rs['CANCEL_NOTE'] ?>
    <?php endif; ?>
</div>

<!-- بيانات الطلب — شريط متراص -->
<div class="info-bar">
    <div class="info-chips">
        <?php if ($req_type_val != 3): ?>
            <span class="chip"><i class="fa fa-calendar"></i> الشهر: <b><?php $thm=$rs['THE_MONTH']??''; echo strlen($thm)==6 ? substr($thm,4,2).'/'.substr($thm,0,4) : $thm; ?></b></span>
        <?php endif; ?>
        <span class="chip"><i class="fa fa-tag"></i> نوع الطلب: <b><?= $rs['REQ_TYPE_NAME'] ?? '' ?></b></span>
        <?php if (in_array($req_type_val, [2, 3]) && !empty($rs['PERCENT_VAL'])): ?>
            <span class="chip pct"><i class="fa fa-percent"></i> النسبة: <b><?= $rs['PERCENT_VAL'] ?>%</b></span>
        <?php endif; ?>
        <?php if ($req_type_val == 2 && ($rs['L_VALUE'] !== null || $rs['H_VALUE'] !== null)): ?>
            <span class="chip"><i class="fa fa-arrows-h"></i> الحدود: <b><?= n_format((float)($rs['L_VALUE'] ?? 0)) ?> — <?= n_format((float)($rs['H_VALUE'] ?? 0)) ?></b></span>
        <?php endif; ?>
        <?php if (in_array($req_type_val, [3, 4, 5]) && !empty($rs['PAY_TYPE_NAME'])): ?>
            <span class="chip pay"><i class="fa fa-bookmark-o"></i> <?= $req_type_val == 5 ? 'بند الاستحقاق' : 'بند التسديد' ?>: <b><?= $rs['PAY_TYPE_NAME'] ?></b></span>
        <?php endif; ?>
        <?php if ($req_type_val == 3 && !empty($rs['FILTER_MONTH'])):
            $fm = $rs['FILTER_MONTH'];
            $fm_disp = strlen($fm) == 6 ? substr($fm, 4, 2) . '/' . substr($fm, 0, 4) : $fm;
        ?>
            <span class="chip" style="background:#eff6ff;border-color:#bfdbfe;color:#1e40af" title="فلتر اختياري — جلب الموظفين المحتسبين في هذا الشهر فقط (بدون أي ربط مالي)">
                <i class="fa fa-filter"></i> فلتر شهر: <b><?= $fm_disp ?></b>
            </span>
        <?php endif; ?>
        <span class="chip"><i class="fa fa-users"></i> <b id="empCountVal"><?= $emp_count ?></b> موظف</span>
    </div>
    <div class="info-total">
        <span class="lbl"><i class="fa fa-money me-1"></i> الإجمالي</span>
        <span class="val" id="totalAmountVal"><?= n_format($total_amount) ?></span>
    </div>
</div>

<?php if (!empty($rs['NOTE'])): ?>
<div style="font-size:.78rem;color:#64748b;margin-bottom:.5rem"><i class="fa fa-comment-o me-1"></i> <?= $rs['NOTE'] ?></div>
<?php endif; ?>

<!-- AUDIT TRAIL -->
<div class="audit-row">
    <?php if (!empty($rs['ENTRY_DATE'])): ?>
        <span><i class="fa fa-plus-circle"></i> إنشاء: <?= $rs['ENTRY_DATE'] ?><?php if (!empty($rs['ENTRY_USER_NAME'])): ?> — <?= $rs['ENTRY_USER_NAME'] ?><?php endif; ?></span>
    <?php endif; ?>
    <?php if (!empty($rs['APPROVE_DATE'])): ?>
        <span><i class="fa fa-check-circle"></i> اعتماد: <?= $rs['APPROVE_DATE'] ?><?php if (!empty($rs['APPROVE_USER_NAME'])): ?> — <?= $rs['APPROVE_USER_NAME'] ?><?php endif; ?></span>
    <?php endif; ?>
    <?php if (!empty($rs['PAY_DATE'])): ?>
        <span><i class="fa fa-money"></i> صرف: <?= $rs['PAY_DATE'] ?></span>
    <?php endif; ?>
    <?php if (!empty($rs['CANCEL_DATE'])): ?>
        <span><i class="fa fa-ban"></i> إلغاء: <?= $rs['CANCEL_DATE'] ?></span>
    <?php endif; ?>
</div>
<?php endif; ?>

<?php if ($isCreate): ?>
<!-- CREATE FORM — WIZARD -->
<form id="master_form">
    <input type="hidden" name="req_type" id="req_type" value="">
    <input type="hidden" name="calc_method" id="calc_method" value="">
    <input type="hidden" name="percent_val" id="percent_val" value="">

    <!-- ═══ اختر نوع الطلب ═══ -->
    <div class="mb-3" id="wiz_type_section">
        <label class="fw-bold d-block mb-2" style="font-size:.88rem"><i class="fa fa-list-ul me-1"></i> اختر نوع الطلب <span class="text-danger">*</span></label>
        <div class="type-cards-row">
            <div class="type-card" data-type="1" onclick="wizSelectType(1)">
                <div class="tc-icon"><i class="fa fa-money"></i></div>
                <div class="tc-title">راتب كامل</div>
                <div class="tc-desc">صرف كامل الراتب المحوّل كمستحقات</div>
            </div>
            <div class="type-card" data-type="2" onclick="wizSelectType(2)">
                <div class="tc-icon"><i class="fa fa-percent"></i></div>
                <div class="tc-title">دفعة من الراتب</div>
                <div class="tc-desc">نسبة من الراتب أو مبلغ ثابت لكل موظف</div>
            </div>
            <div class="type-card" data-type="3" onclick="wizSelectType(3)">
                <div class="tc-icon"><i class="fa fa-credit-card"></i></div>
                <div class="tc-title">دفعة من المستحقات</div>
                <div class="tc-desc">مبلغ محدد يُخصم من رصيد المستحقات العام</div>
            </div>
            <div class="type-card" data-type="4" onclick="wizSelectType(4)">
                <div class="tc-icon"><i class="fa fa-calendar-check-o"></i></div>
                <div class="tc-title">مستحقات حسب الشهر</div>
                <div class="tc-desc">المتبقي من بند 323 لشهر محدد</div>
            </div>
            <div class="type-card" data-type="5" onclick="wizSelectType(5)">
                <div class="tc-icon"><i class="fa fa-gift"></i></div>
                <div class="tc-title">استحقاقات وإضافات</div>
                <div class="tc-desc">صرف من بنود الاستحقاقات — بدون تأثير على المستحقات</div>
            </div>
        </div>
    </div>

    <!-- ═══ إعدادات الطلب (تظهر بعد اختيار النوع) ═══ -->
    <div id="wiz_settings" style="display:none">
    <div id="wiz_type_hint" class="alert alert-light py-2 mb-3" style="font-size:.82rem;border-radius:8px">
        <i class="fa fa-info-circle text-primary"></i> <span id="wiz_type_hint_text"></span>
    </div>
    <div class="row g-3">
        <div class="form-group col-sm-6 col-md-3">
            <label>الشهر <span class="text-danger">*</span></label>
            <input type="text" name="the_month" id="the_month" class="form-control" placeholder="YYYYMM" value="<?= date('Ym') ?>">
        </div>

        <!-- بند المستحقات -->
        <div class="form-group col-sm-6 col-md-3" id="show_pay_type_grp" style="display:none">
            <label>بند المستحقات <span class="text-danger">*</span></label>
            <select name="pay_type" id="pay_type" class="form-select"></select>
        </div>

        <!-- طريقة الاحتساب — نوع 2 -->
        <div class="form-group col-sm-6 col-md-2" id="show_calc_method_grp" style="display:none">
            <label>طريقة الاحتساب <span class="text-danger">*</span></label>
            <select name="calc_method" id="show_calc_method_sel" class="form-select" onchange="$('#calc_method').val(this.value); showOnCalcMethodChange();">
                <option value="">— اختر —</option>
                <?php foreach ($calc_method_cons as $r): ?>
                    <option value="<?= $r['CON_NO'] ?>"><?= $r['CON_NAME'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- النسبة % — نوع 2 + نسبة مئوية -->
        <div class="form-group col-sm-6 col-md-2" id="show_percent_grp" style="display:none">
            <label>النسبة % <span class="text-danger">*</span></label>
            <div class="input-group">
                <select id="show_percent_sel" class="form-select" onchange="$('#percent_val').val(this.value); $('#show_percent_input').val(this.value);">
                    <option value="">اختر</option>
                    <option value="100">100%</option>
                    <option value="65">65%</option>
                    <option value="50">50%</option>
                    <option value="30">30%</option>
                </select>
                <input type="number" id="show_percent_input" class="form-control" min="1" max="100" placeholder="يدوي" onchange="$('#percent_val').val(this.value);">
            </div>
        </div>

        <!-- المبلغ — نوع 3,4,5 -->
        <div class="form-group col-sm-6 col-md-2" id="show_amount_grp" style="display:none">
            <label>المبلغ لكل موظف <span class="text-danger">*</span></label>
            <input type="number" name="req_amount" id="show_req_amount" class="form-control" min="1" placeholder="0.00" step="0.01">
        </div>

        <!-- بند الاستحقاق — نوع 5 -->
        <div class="form-group col-sm-6 col-md-3" id="show_benefit_grp" style="display:none">
            <label>بند الاستحقاق <span class="text-danger">*</span></label>
            <select name="benefit_con" id="show_benefit_con" class="form-control sel2" onchange="$('#pay_type').val(this.value)"></select>
        </div>

        <!-- طريقة المبلغ — أنواع 3 (مبلغ ثابت)، 4، 5 -->
        <div class="form-group col-sm-6 col-md-3" id="show_amount_mode_grp" style="display:none">
            <label>طريقة المبلغ <span class="text-danger">*</span></label>
            <select id="show_amount_mode" name="amount_mode" class="form-select" onchange="showOnAmountModeChange()">
                <option value="same">نفس المبلغ لكل الموظفين</option>
                <option value="diff">مبلغ مختلف لكل موظف</option>
            </select>
        </div>
    </div>

    <!-- ═══ شهر فلتر اختياري — نوع 3 فقط ═══ -->
    <div class="row g-3 mt-2" id="show_filter_month_grp" style="display:none">
        <div class="col-12">
            <div class="alert alert-info py-2 mb-1" style="border-radius:8px;font-size:.78rem">
                <i class="fa fa-info-circle me-1"></i>
                <b>شهر الاحتساب (اختياري):</b> لجلب الموظفين المحتسبين في شهر معيّن فقط — <b>بدون أي ربط مالي</b>.
                المبلغ يُخصم من رصيد المستحقات العام.
            </div>
        </div>
        <div class="col-md-3">
            <label class="fw-bold mb-1" style="font-size:.78rem">
                <i class="fa fa-filter text-info"></i> شهر الاحتساب
            </label>
            <div class="input-group">
                <input type="text" name="filter_month" id="filter_month" class="form-control" placeholder="YYYYMM" autocomplete="off">
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="$('#filter_month').val('')" title="مسح الفلتر">
                    <i class="fa fa-times"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- حدود الاحتساب — نوع 2 -->
    <div class="row g-3 mt-2" id="show_limits_grp" style="display:none">
        <div class="col-12"><small class="text-muted fw-bold"><i class="fa fa-sliders"></i> حدود الاحتساب</small></div>
        <div class="col-md-3">
            <label class="fw-bold mb-1" style="font-size:.78rem">الحد الأدنى للقيمة <span class="text-danger">*</span></label>
            <div class="input-group">
                <input type="number" id="show_l_value" class="form-control" min="0" value="800">
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="$('#show_l_value').val(0)">صفر</button>
            </div>
        </div>
        <div class="col-md-3">
            <label class="fw-bold mb-1" style="font-size:.78rem">الحد الأعلى للقيمة <span class="text-danger">*</span></label>
            <div class="input-group">
                <input type="number" id="show_h_value" class="form-control" min="0" value="1800">
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="$('#show_h_value').val(10000)">10000</button>
            </div>
        </div>
    </div>

    <!-- ═══ قسم REQ_TYPE=2: حالة الشهر + احتساب ═══ -->
    <div id="show_type2_section" style="display:none" class="mt-3">
        <div id="show_month_status"></div>
        <div id="show_calc_section" style="display:none">
            <div class="alert alert-danger py-3 mt-2" style="border-radius:8px">
                <i class="fa fa-exclamation-circle me-1"></i>
                <strong>الشهر غير محتسب</strong> — يجب احتساب الرواتب أولاً من شاشة
                <a href="<?= base_url('payflow/Salarycalculation') ?>" class="fw-bold">احتساب الرواتب</a>
                ثم العودة لإنشاء طلب الصرف.
            </div>
        </div>
    </div>

    <input type="hidden" id="wiz_branch_no" value="">

    <div class="row g-3 mt-2">
        <div class="form-group col-sm-6 col-md-2">
            <label>تاريخ الطلب</label>
            <input type="text" name="entry_date" id="entry_date" class="form-control" placeholder="<?= date('d/m/Y') ?>">
        </div>
        <div class="form-group col-sm-6 col-md-10">
            <label>ملاحظات</label>
            <input type="text" name="note" id="note" class="form-control" placeholder="اختياري...">
        </div>
    </div>
    <!-- ═══ اختيار طريقة الإضافة ═══ -->
    <div id="wiz_choice" style="display:none" class="mt-3">
        <label class="fw-bold d-block mb-2" style="font-size:.85rem"><i class="fa fa-question-circle text-primary me-1"></i> كيف تريد إضافة الموظفين؟</label>
        <div class="row g-3">
            <div class="col-sm-4" id="wiz_choice_all_card">
                <div class="type-card" onclick="wizChooseAll();" style="cursor:pointer;padding:1.5rem">
                    <div class="tc-icon" style="color:#2563eb"><i class="fa fa-users"></i></div>
                    <div class="tc-title">كل الموظفين</div>
                    <div class="tc-desc">معاينة كل الموظفين المؤهلين ثم إنشاء الطلب</div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="type-card" onclick="wizChooseSpecific();" style="cursor:pointer;padding:1.5rem">
                    <div class="tc-icon" style="color:#059669"><i class="fa fa-user-plus"></i></div>
                    <div class="tc-title">موظفين محددين</div>
                    <div class="tc-desc">اختيار موظفين من القائمة ومعاينة المبالغ</div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="type-card" onclick="wizChooseExcel();" style="cursor:pointer;padding:1.5rem">
                    <div class="tc-icon" style="color:#d97706"><i class="fa fa-file-excel-o"></i></div>
                    <div class="tc-title">رفع كشف Excel</div>
                    <div class="tc-desc">رفع ملف Excel بأرقام الموظفين ومعاينة المبالغ</div>
                </div>
            </div>
        </div>
        <div class="mt-2">
            <a class="btn btn-light btn-sm" href="<?= $back_url ?>"><i class="fa fa-times"></i> إلغاء</a>
        </div>
    </div>

    <!-- ═══ اختيار موظفين محددين ═══ -->
    <div id="wiz_pick_emps" style="display:none" class="mt-3">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <label class="fw-bold mb-0" style="font-size:.85rem"><i class="fa fa-user-plus text-success me-1"></i> اختر الموظفين</label>
            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="wizBackToChoice()"><i class="fa fa-arrow-right me-1"></i> رجوع</button>
        </div>
        <select id="wiz_emp_select" class="form-control" multiple style="width:100%">
            <?php foreach ($emp_no_cons as $row): ?>
                <option value="<?= $row['EMP_NO'] ?>"><?= $row['EMP_NO'] . ' - ' . $row['EMP_NAME'] ?></option>
            <?php endforeach; ?>
        </select>
        <!-- جدول المبالغ — يظهر بعد اختيار الموظفين -->
        <div id="wiz_pick_amounts" style="display:none" class="mt-2" style="max-width:700px">
            <small class="text-muted fw-bold"><i class="fa fa-money"></i> المبالغ <span class="text-muted fw-normal">(فاضي = تلقائي)</span></small>
            <div class="table-responsive mt-1" style="max-height:250px;overflow-y:auto;max-width:700px">
                <table class="table table-sm table-bordered mb-0" style="font-size:.78rem">
                    <thead class="table-light"><tr><th style="width:30px">#</th><th style="width:350px">الموظف</th><th style="width:130px">المبلغ</th><th style="width:30px"></th></tr></thead>
                    <tbody id="wiz_pick_amounts_body"></tbody>
                </table>
            </div>
        </div>
        <div class="text-end mt-2">
            <button type="button" class="btn btn-indigo text-white" onclick="wizPreviewSelected();"><i class="fa fa-eye me-1"></i> معاينة</button>
        </div>
    </div>

    <!-- ═══ رفع كشف Excel ═══ -->
    <div id="wiz_pick_excel" style="display:none" class="mt-3">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <label class="fw-bold mb-0" style="font-size:.85rem"><i class="fa fa-file-excel-o text-warning me-1"></i> رفع كشف Excel</label>
            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="wizBackToChoice()"><i class="fa fa-arrow-right me-1"></i> رجوع</button>
        </div>
        <div id="wiz_excel_hint" style="font-size:.78rem;color:#64748b;margin-bottom:.5rem"></div>
        <div class="d-flex gap-2 align-items-center">
            <button type="button" class="btn btn-outline-success btn-sm" onclick="wizDownloadTemplate()"><i class="fa fa-download me-1"></i> تحميل القالب</button>
            <input type="file" id="wiz_excel_file" accept=".xlsx,.xls,.csv" class="form-control" style="max-width:400px" onchange="wizParseExcel(this)">
        </div>
        <div id="wiz_excel_result" class="mt-2" style="display:none"></div>
        <div id="wiz_excel_actions" class="text-end mt-2" style="display:none">
            <button type="button" class="btn btn-indigo text-white" onclick="wizPreviewExcelEmps();"><i class="fa fa-eye me-1"></i> معاينة</button>
        </div>
    </div>
    </div><!-- /wiz_settings -->

    <!-- ═══ خطوة 3: معاينة الموظفين ═══ -->
    <div id="wiz_step3" style="display:none" class="mt-3">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="fw-bold mb-0"><i class="fa fa-users text-primary me-1"></i> معاينة الموظفين</h6>
            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="wizBackToSettings()"><i class="fa fa-arrow-right me-1"></i> رجوع للإعدادات</button>
        </div>
        <div id="wiz_preview_content"></div>
        <div id="wiz_preview_loading" class="text-center py-4" style="display:none">
            <i class="fa fa-spinner fa-spin fa-2x text-indigo"></i>
            <p class="text-muted mt-2 mb-0">جاري تحميل قائمة الموظفين...</p>
        </div>
        <div class="text-end mt-3" id="wiz_step3_buttons" style="display:none">
            <button type="button" class="btn btn-outline-secondary" onclick="wizBackToSettings()"><i class="fa fa-arrow-right"></i> رجوع</button>
            <button type="button" class="btn btn-outline-success" onclick="wizExportPreview()"><i class="fa fa-file-excel-o"></i> تصدير Excel</button>
            <button type="button" onclick="wizBulkCreate(this);" class="btn btn-indigo text-white" id="btnWizBulkCreate"><i class="fa fa-check-circle"></i> إنشاء الطلب وإضافة الموظفين</button>
        </div>
    </div>

</form>
<?php endif; ?>

<!-- ═══ ملخص ملخص الراتب (نوع 2 فقط) ═══ -->
<?php if (!$isCreate && $HaveRs && (int)($rs['REQ_TYPE'] ?? 0) == 2): ?>
<div id="showWalletSummary" class="mt-3"></div>
<?php endif; ?>

<?php
$detail_approve_url = base_url("$MODULE_NAME/$TB_NAME/detail_approve");
$import_url         = base_url("$MODULE_NAME/$TB_NAME/import_excel");
$import_preview_url = base_url("$MODULE_NAME/$TB_NAME/import_preview");
$detail_pay_url     = base_url("$MODULE_NAME/$TB_NAME/detail_pay");
$bank_csv_url       = base_url("$MODULE_NAME/$TB_NAME/export_bank_csv");
$can_partial = !$isCreate && !$is_cancelled;
$status_badges = [0 => ['مسودة','#fef3c7','#92400e'], 1 => ['معتمد','#dbeafe','#1e40af'], 2 => ['منفّذ للصرف','#d1fae5','#065f46'], 3 => ['معتمد','#e0e7ff','#3730a3'], 4 => ['محتسب','#ccfbf1','#0f766e'], 9 => ['ملغى','#fee2e2','#991b1b']];
?>

<!-- DETAIL TABLE -->
<?php if (!$isCreate && $HaveRs): ?>
<!-- Hidden inputs لبيانات الماستر — يستخدمها JS في addToQueue/detail_preview/submitQueue -->
<input type="hidden" id="the_month"    value="<?= htmlspecialchars($rs['THE_MONTH']    ?? '') ?>">
<input type="hidden" id="req_type"     value="<?= htmlspecialchars($rs['REQ_TYPE']     ?? '') ?>">
<input type="hidden" id="calc_method"  value="<?= htmlspecialchars($rs['CALC_METHOD']  ?? '') ?>">
<input type="hidden" id="percent_val"  value="<?= htmlspecialchars($rs['PERCENT_VAL']  ?? '') ?>">
<input type="hidden" id="show_l_value" value="<?= htmlspecialchars($rs['L_VALUE']     ?? '') ?>">
<input type="hidden" id="show_h_value" value="<?= htmlspecialchars($rs['H_VALUE']     ?? '') ?>">
<input type="hidden" id="filter_month" value="<?= htmlspecialchars($rs['FILTER_MONTH'] ?? '') ?>">
<div class="card mt-3" id="detailCard">
    <div class="card-header d-flex align-items-center">
        <h3 class="card-title"><i class="fa fa-users me-2"></i> الموظفين</h3>
        <?php if ($can_partial && $emp_count > 0): ?>
        <div class="ms-auto d-flex gap-2 flex-wrap align-items-center">
            <?php if ($can_add): ?>
                <!-- 🆕 Bulk override للمحدّدين — بارز وواضح -->
                <div class="bulk-ovr-bar d-flex align-items-center gap-1"
                     style="background:#f1f5f9;border:1px solid #cbd5e1;border-radius:8px;padding:.25rem .5rem">
                    <span style="font-size:.72rem;color:#475569;font-weight:700;white-space:nowrap">
                        <i class="fa fa-magic"></i> تطبيق على المحدّدين:
                    </span>
                    <button type="button" class="btn btn-sm btn-secondary fw-bold"
                            onclick="bulkSetOverride('default')"
                            title="إرجاع للتوزيع الافتراضي">
                        <i class="fa fa-cog me-1"></i> افتراضي
                    </button>
                    <button type="button" class="btn btn-sm btn-primary fw-bold"
                            onclick="bulkSetOverride('bank')"
                            title="تحويل المحدّدين للبنك فقط">
                        <i class="fa fa-bank me-1"></i> بنك
                    </button>
                    <button type="button" class="btn btn-sm btn-info text-white fw-bold"
                            onclick="bulkSetOverride('wallet')"
                            title="تحويل المحدّدين للمحفظة فقط">
                        <i class="fa fa-mobile me-1"></i> محفظة
                    </button>
                </div>
            <?php endif; ?>
            <?php if (($is_draft || $cur_status == 3) && HaveAccess($approve_url)): ?>
                <button type="button" class="btn btn-info btn-sm text-white" onclick="doPartialApprove()"><i class="fa fa-check-circle"></i> اعتماد المحددين</button>
            <?php endif; ?>
            <?php if ($is_paid || $cur_status == 4): ?>
                <a class="btn-bank-csv" href="<?= $bank_csv_url ?>?req_id=<?= $req_id_val ?>" title="تصدير ملف الحوالات البنكية الجاهز للإرسال للبنك">
                    <span class="bcsv-icon"><i class="fa fa-bank"></i></span>
                    <span class="bcsv-text">
                        <span class="bcsv-title">ملف بنكي</span>
                        <span class="bcsv-sub">CSV — جاهز للإرسال</span>
                    </span>
                    <i class="fa fa-cloud-download bcsv-dl"></i>
                </a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
    <div class="card-body p-2">
        <?php if ($can_add): ?>
        <div id="addEmpSection" class="mb-2 p-2" style="background:#f8fafc;border-radius:8px">
            <!-- تبويبات طرق الإضافة -->
            <ul class="nav nav-tabs nav-tabs-sm mb-2" style="font-size:.8rem">
                <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#tab_add_manual"><i class="fa fa-keyboard-o me-1"></i> إضافة يدوي</a></li>
                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab_add_upload"><i class="fa fa-upload me-1"></i> رفع ملف Excel</a></li>
            </ul>
            <div class="tab-content">
                <!-- تبويب 1: إضافة يدوي برقم الموظف -->
                <div class="tab-pane fade show active" id="tab_add_manual">
                    <div class="row g-2 align-items-end">
                        <div class="col-md-3" id="add_emp_select_grp" style="display:none">
                            <label style="font-size:.72rem;color:#64748b;font-weight:600">الموظف</label>
                            <select id="add_emp_select" class="form-control">
                                <option value="">- اختر موظف -</option>
                                <?php foreach ($emp_no_cons as $row): ?>
                                    <option value="<?= $row['EMP_NO'] ?>"><?= $row['EMP_NO'].' - '.$row['EMP_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2" id="add_emp_no_grp">
                            <label style="font-size:.72rem;color:#64748b;font-weight:600">رقم الموظف</label>
                            <input type="number" id="add_emp_no" class="form-control" placeholder="أدخل الرقم" min="1">
                        </div>
                        <div class="col-md-3" id="add_emp_name_grp">
                            <label style="font-size:.72rem;color:#64748b;font-weight:600">اسم الموظف</label>
                            <input type="text" id="add_emp_name" class="form-control" readonly disabled placeholder="—" style="background:#fff">
                        </div>
<?php
    // منطق الحقل حسب النوع:
    // 1 راتب كامل       → مقفل (كامل المستحقات)
    // 2 دفعة راتب       → مقفل (حسب النسبة والحدود)
    // 3 نسبة            → مقفل (حسب النسبة)
    // 3 مبلغ ثابت       → معبّى بمبلغ الماستر + قابل للتعديل
    // 4 مستحقات شهر     → مقفل (حسب الشهر)
    // 5 نفس المبلغ      → معبّى بمبلغ الماستر + قابل للتعديل
    // 5 مبلغ مختلف      → فاضي + إجباري
    //
    // ⚠️ مهم: master.REQ_AMOUNT = إجمالي الطلب (SUM of details) — مش المبلغ الموحد!
    // فمجرد ما يصير عندنا تفصيلة واحدة، الـ REQ_AMOUNT يصير مجموع كل المبالغ.
    // الحلّ: نستخدمه كـ default فقط لما الطلب فاضي (ما فيه details).
    $has_details_rows = !empty($detail_rows);
    $master_amt = $has_details_rows ? 0 : (float)($rs['REQ_AMOUNT'] ?? 0);
    $calc_method_val = (int)($rs['CALC_METHOD'] ?? 0);

    $is_locked = false;
    $is_required = false;
    $default_amt_val = '';
    $placeholder_text = '';

    if ($req_type_val == 1) {
        $is_locked = true; $placeholder_text = 'كامل المستحقات';
    } elseif ($req_type_val == 2) {
        $is_locked = true; $placeholder_text = 'حسب النسبة والحدود';
    } elseif ($req_type_val == 3) {
        if ($calc_method_val == 1) { // نسبة
            $is_locked = true; $placeholder_text = 'حسب النسبة';
        } else { // مبلغ ثابت
            $default_amt_val = $master_amt > 0 ? $master_amt : '';
            $placeholder_text = $master_amt > 0 ? 'افتراضي: ' . n_format($master_amt) : 'أدخل المبلغ';
        }
    } elseif ($req_type_val == 4) {
        $is_locked = true; $placeholder_text = 'حسب الشهر';
    } elseif ($req_type_val == 5) {
        if ($master_amt > 0) {
            // نفس المبلغ
            $default_amt_val = $master_amt;
            $placeholder_text = 'افتراضي: ' . n_format($master_amt);
        } else {
            // مبلغ مختلف
            $is_required = true; $placeholder_text = 'أدخل المبلغ';
        }
    }
?>
                        <div class="col-md-2">
                            <label style="font-size:.72rem;color:#64748b;font-weight:600">المبلغ<?php if ($is_required): ?> <span class="text-danger">*</span><?php endif; ?></label>
                            <input type="number" id="add_amount" class="form-control" value="<?= $default_amt_val ?>" placeholder="<?= $placeholder_text ?>" step="0.01" min="0" <?= $is_locked ? 'disabled style="background:#f1f5f9;cursor:not-allowed"' : '' ?>>
                        </div>
                        <div class="col-md-2">
                            <label style="font-size:.72rem;color:#64748b;font-weight:600">ملاحظة</label>
                            <input type="text" id="add_note" class="form-control" placeholder="اختياري...">
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-outline-primary w-100" onclick="addToQueue()" title="أضف للقائمة"><i class="fa fa-plus"></i></button>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-primary w-100" onclick="submitQueue()" id="btnSubmitQueue" disabled><i class="fa fa-save"></i> حفظ <span id="queueBadge" class="badge bg-white text-primary ms-1" style="display:none">0</span></button>
                        </div>
                    </div>
                    <div id="add_preview_info" style="display:none;margin-top:.5rem">
                        <div class="row g-2">
                            <div class="col-md-6">
                                <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;padding:.5rem .75rem">
                                    <div style="font-size:.68rem;color:#64748b"><i class="fa fa-wallet text-success"></i> رصيد المستحقات المتاح</div>
                                    <div id="add_preview_dues" style="font-size:.95rem;font-weight:800;color:#059669;direction:ltr">0.00</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div style="background:#fef3c7;border:1px solid #fde68a;border-radius:8px;padding:.5rem .75rem">
                                    <div style="font-size:.68rem;color:#64748b"><i class="fa fa-calculator text-warning"></i> المبلغ الذي سيُصرف <span id="add_preview_flag" style="font-size:.62rem"></span></div>
                                    <div id="add_preview_calc" style="font-size:1rem;font-weight:800;color:#92400e;direction:ltr">0.00</div>
                                </div>
                            </div>
                        </div>
                        <div id="add_preview_error" class="text-danger mt-1" style="display:none;font-size:.78rem"></div>
                    </div>
                </div>


                <!-- تبويب 3: رفع ملف Excel -->
                <div class="tab-pane fade" id="tab_add_upload">
                    <div class="mb-2" style="font-size:.78rem;color:#64748b">
                        <i class="fa fa-info-circle text-primary"></i>
                        ارفع ملف Excel بتنسيق: العمود A = رقم الموظف | العمود B = المبلغ | العمود C = ملاحظة (اختياري)
                    </div>
                    <div class="d-flex gap-2 align-items-center">
                        <button type="button" class="btn btn-success btn-sm" onclick="openImpExcelModal()"><i class="fa fa-upload me-1"></i> رفع ملف Excel</button>
                        <a href="<?= base_url("$MODULE_NAME/$TB_NAME/download_template") ?>" class="btn btn-outline-secondary btn-sm"><i class="fa fa-download me-1"></i> تحميل القالب</a>
                    </div>
                </div>

            </div>
            <!-- قائمة الانتظار -->
            <div id="queueSection" style="display:none" class="mt-2">
                <table class="table table-sm table-bordered mb-1" style="font-size:.8rem">
                    <thead class="table-light"><tr><th style="width:30px">#</th><th>الموظف</th><th class="text-end" style="width:100px">المبلغ</th><?php if ($req_type_val != 5): ?><th class="text-end" style="width:100px">المتاح</th><?php endif; ?><th style="width:150px">ملاحظة</th><th style="width:36px"></th></tr></thead>
                    <tbody id="queueBody"></tbody>
                </table>
            </div>
            <!-- شريط التقدم -->
            <div id="addProgress" style="display:none" class="mt-2">
                <div class="progress" style="height:20px;border-radius:8px">
                    <div id="addProgressBar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width:0%"></div>
                </div>
                <small id="addProgressText" class="text-muted"></small>
            </div>
            <div id="addResults" style="display:none" class="mt-2"></div>
        </div>
        <?php endif; ?>

        <?php
        $is_type_2 = $HaveRs && (int)($rs['REQ_TYPE'] ?? 0) == 2;

        // ═══════════════════════════════════════════════════════════
        // بناء قائمة المستفيدين (recipients) لـ Tab 2 — بيانات غنية للمحاسب
        // ═══════════════════════════════════════════════════════════
        $recipients = [];
        $stat_self = 0;       // الموظف يقبض لنفسه
        $stat_benef = 0;      // وريث/مستفيد
        $stat_other_owner = 0;// صاحب حساب مختلف
        $stat_total_alloc = 0;
        $stat_total_dues  = 0; // إجمالي ACCRUED_323 (سيُرحّل للمستحقات — لا يدخل في التحويلات البنكية)
        $stat_blocked = 0;    // موظفون بـ overage (لن يُصرف)
        $stat_no_acc = 0;     // موظفون بدون حسابات
        $stat_split_emps = 0; // موظفون مبلغهم مقسّم على أكثر من حساب
        $stat_wallet_count = 0; // عدد الحركات للمحافظ
        $stat_bank_count = 0;   // عدد الحركات للبنوك
        $accounts_map_for_tab2 = isset($accounts_map) && is_array($accounts_map) ? $accounts_map : [];
        $emp_summary_for_tab2  = isset($emp_summary)  && is_array($emp_summary)  ? $emp_summary  : [];

        // الـ pass الأول: نحسب emp_total_cash لكل موظف (قبل بناء الصفوف)
        $emp_total_cash = []; // emp_no => sum of allocs across his accounts
        foreach ($detail_rows as $d) {
            $eno = (int)($d['EMP_NO'] ?? 0);
            if (!$eno) continue;
            $emp_accs = $accounts_map_for_tab2[$eno] ?? [];
            $tot = 0;
            foreach ($emp_accs as $a) {
                if (empty($a['ACC_ID'])) continue;
                if ((int)($a['ACC_IS_ACTIVE'] ?? 1) === 0) continue;
                $tot += (float)($a['ALLOC_AMOUNT'] ?? 0);
            }
            $emp_total_cash[$eno] = $tot;
        }
        // إجمالي ACCRUED_323 (DUES) — للستات
        if (isset($total_accrued_323)) $stat_total_dues = (float)$total_accrued_323;

        foreach ($detail_rows as $d) {
            $eno = (int)($d['EMP_NO'] ?? 0);
            $emp_name = $d['EMP_NAME'] ?? '';
            $branch_name = $d['BRANCH_NAME'] ?? '';
            $emp_req_amount = (float)($d['REQ_AMOUNT'] ?? 0);
            $emp_net_total  = (float)($d['NET_SALARY_CALC'] ?? 0);
            $emp_dues       = (float)($d['ACCRUED_323_CALC'] ?? 0);
            $emp_accs = $accounts_map_for_tab2[$eno] ?? [];
            $es = $emp_summary_for_tab2[$eno] ?? null;
            $emp_overage = $es ? (int)$es['is_overage'] : 0;
            $emp_active_accs = array_filter($emp_accs, function($a){
                return !empty($a['ACC_ID']) && (int)($a['ACC_IS_ACTIVE'] ?? 1) === 1;
            });
            $emp_acc_count = count($emp_active_accs);
            $emp_is_split = $emp_acc_count > 1;

            if ($emp_overage)         { $stat_blocked++; continue; }
            if ($emp_acc_count === 0) { $stat_no_acc++; continue; }
            if ($emp_is_split) $stat_split_emps++;

            $emp_cash_total = $emp_total_cash[$eno] ?? 0;

            foreach ($emp_active_accs as $a) {
                $alloc = (float)($a['ALLOC_AMOUNT'] ?? 0);
                if ($alloc <= 0) continue;

                // تحديد نوع المستلم
                $type = 'self';
                $recipient_name = $emp_name;
                $rel_label = 'الموظف نفسه';
                $type_color = '#059669';
                $type_bg = '#dcfce7';
                $owner = trim($a['OWNER_NAME'] ?? '');
                $emp_clean = trim($emp_name);

                if (!empty($a['BENEFICIARY_ID'])) {
                    $type = 'benef';
                    $recipient_name = $a['BENEF_NAME'] ?? $owner;
                    $rel_label = 'وريث - ' . ($a['BENEF_REL_NAME'] ?? '');
                    $type_color = '#6d28d9';
                    $type_bg = '#f5f3ff';
                    $stat_benef++;
                } elseif ($owner && $owner !== $emp_clean) {
                    $type = 'other_owner';
                    $recipient_name = $owner;
                    $rel_label = 'صاحب حساب';
                    $type_color = '#9a3412';
                    $type_bg = '#fff7ed';
                    $stat_other_owner++;
                } else {
                    $stat_self++;
                }

                // 🆕 طريقة التوزيع لهذا الحساب
                $st = (int)($a['SPLIT_TYPE'] ?? 0);
                $sv = (float)($a['SPLIT_VALUE'] ?? 0);
                if (!$emp_is_split) {
                    // حساب واحد → كامل
                    $split_label = 'كامل';
                    $split_color = '#059669';
                    $split_bg    = '#f0fdf4';
                } elseif ($st === 1) {
                    $split_label = 'نسبة ' . rtrim(rtrim(number_format($sv, 2, '.', ''), '0'), '.') . '%';
                    $split_color = '#0369a1';
                    $split_bg    = '#eff6ff';
                } elseif ($st === 2) {
                    $split_label = 'مبلغ ثابت';
                    $split_color = '#92400e';
                    $split_bg    = '#fef3c7';
                } elseif ($st === 3) {
                    $split_label = 'كامل الباقي';
                    $split_color = '#15803d';
                    $split_bg    = '#dcfce7';
                } else {
                    $split_label = '—';
                    $split_color = '#64748b';
                    $split_bg    = '#f1f5f9';
                }

                // 🆕 النسبة من إجمالي صرف الموظف (للموظفين بـ split)
                $share_pct = ($emp_cash_total > 0 && $emp_is_split)
                    ? round(($alloc / $emp_cash_total) * 100, 1)
                    : null;

                // عد الحركات للبنوك/المحافظ
                if ((int)($a['PROVIDER_TYPE'] ?? 1) === 2) $stat_wallet_count++;
                else $stat_bank_count++;

                $recipients[] = [
                    'emp_no'        => $eno,
                    'emp_name'      => $emp_name,
                    'emp_branch'    => $branch_name,
                    'emp_req_amount'=> $emp_req_amount,    // 🆕 إجمالي صرف الموظف للطلب
                    'emp_net_total' => $emp_net_total,     // 🆕 الراتب الصافي للموظف
                    'emp_dues'      => $emp_dues,          // 🆕 ACCRUED_323 للموظف
                    'emp_acc_count' => $emp_acc_count,     // 🆕 عدد حسابات الموظف
                    'emp_is_split'  => $emp_is_split ? 1 : 0,
                    'recipient'     => $recipient_name,
                    'rel_label'     => $rel_label,
                    'type'          => $type,
                    'type_color'    => $type_color,
                    'type_bg'       => $type_bg,
                    'provider'      => $a['PROVIDER_NAME'] ?? '',
                    'provider_type' => (int)($a['PROVIDER_TYPE'] ?? 1),
                    'prov_branch'   => $a['PROV_BRANCH_NAME'] ?? '',
                    'iban'          => $a['IBAN'] ?? '',
                    'account_no'    => $a['ACCOUNT_NO'] ?? '',
                    'is_default'    => (int)($a['IS_DEFAULT'] ?? 0),
                    'amount'        => $alloc,
                    // 🆕 طريقة التوزيع
                    'split_type'    => $st,
                    'split_value'   => $sv,
                    'split_label'   => $split_label,
                    'split_color'   => $split_color,
                    'split_bg'      => $split_bg,
                    // 🆕 النسبة من إجمالي صرف الموظف
                    'share_pct'     => $share_pct,
                ];
                $stat_total_alloc += $alloc;
            }
        }
        // ترتيب: الموظفون أولاً (الورثة معهم)، ثم رقم الموظف
        usort($recipients, function($a, $b){
            if ($a['emp_no'] !== $b['emp_no']) return $a['emp_no'] <=> $b['emp_no'];
            $order = ['self' => 1, 'other_owner' => 2, 'benef' => 3];
            return ($order[$a['type']] ?? 9) <=> ($order[$b['type']] ?? 9);
        });
        ?>

        <!-- 🆕 شريط ملفات الإكسل المستوردة — يظهر فوق tabs الموظفين -->
        <?php if (!empty($import_files)):
            $req_id_imp = (int)($rs['REQ_ID'] ?? 0);
        ?>
            <div class="alert alert-info py-2 mb-2 d-flex flex-wrap align-items-center justify-content-between gap-2"
                 style="background:linear-gradient(135deg,#eff6ff,#fff);border:1px solid #bfdbfe;font-size:.82rem">
                <div>
                    <i class="fa fa-paperclip text-primary me-1"></i>
                    <b>هذا الطلب أُنشئ عبر استيراد Excel.</b>
                    <span class="text-muted">عدد الملفات: <b><?= count($import_files) ?></b></span>
                </div>
                <div class="d-flex flex-wrap gap-1">
                    <?php foreach ($import_files as $f):
                        $fp   = $f['FILE_PATH'] ?? '';
                        $fn   = $f['FILE_NAME'] ?? $fp;
                        $note = $f['NOTE']      ?? '';
                    ?>
                        <a class="btn btn-sm btn-outline-primary"
                           href="<?= base_url("$MODULE_NAME/$TB_NAME/public_download_import_file/{$req_id_imp}/" . rawurlencode($fp)) ?>"
                           title="<?= htmlspecialchars($note) ?>">
                            <i class="fa fa-download me-1"></i>
                            <span style="max-width:280px;display:inline-block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;vertical-align:bottom">
                                <?= htmlspecialchars($fn) ?>
                            </span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- ═══════════════════ Tabs ═══════════════════ -->
        <ul class="nav nav-tabs mb-2" id="detailTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="tab-emps-btn" data-bs-toggle="tab" data-bs-target="#tab-emps" type="button">
                    <i class="fa fa-users me-1"></i> الموظفون
                    <span class="badge bg-primary ms-1"><?= count($detail_rows) ?></span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-recipients-btn" data-bs-toggle="tab" data-bs-target="#tab-recipients" type="button">
                    <i class="fa fa-money me-1"></i> المستفيدون
                    <span class="badge bg-success ms-1"><?= count($recipients) ?></span>
                </button>
            </li>
        </ul>

        <div class="tab-content" id="detailTabsContent">
        <!-- ─────── Tab 1: الموظفون ─────── -->
        <div class="tab-pane fade show active" id="tab-emps" role="tabpanel">

        <?php
        // عدّ الحالات (4 حالات: 1=فعّال, 0=متقاعد, 2=متوفى, 4=حساب مغلق)
        $emp_st_counts = [1=>0, 0=>0, 2=>0, 4=>0];
        foreach ($detail_rows as $_d) {
            $_eno = (int)($_d['EMP_NO'] ?? 0);
            $_es  = $emp_summary_local[$_eno] ?? null;
            $_st  = $_es ? (int)($_es['display_status'] ?? 1) : 1;
            if (isset($emp_st_counts[$_st])) $emp_st_counts[$_st]++;
        }
        ?>
        <!-- Legend: مفتاح ألوان حالات الموظفين -->
        <div class="emp-legend mb-2">
            <span class="emp-legend-title"><i class="fa fa-paint-brush me-1"></i> دلالة الألوان:</span>
            <span class="emp-legend-item">
                <span class="emp-legend-swatch" style="background:#fff;border:1px solid #cbd5e1"></span>
                <span style="color:#15803d;font-weight:700"><i class="fa fa-check-circle"></i> فعّال</span>
                <small>(<?= $emp_st_counts[1] ?>)</small>
            </span>
            <span class="emp-legend-item">
                <span class="emp-legend-swatch" style="background:#ffedd5"></span>
                <span style="color:#9a3412;font-weight:700"><i class="fa fa-clock-o"></i> متقاعد</span>
                <small>(<?= $emp_st_counts[0] ?>)</small>
            </span>
            <span class="emp-legend-item">
                <span class="emp-legend-swatch" style="background:#fee2e2"></span>
                <span style="color:#991b1b;font-weight:700"><i class="fa fa-times-circle"></i> متوفى</span>
                <small>(<?= $emp_st_counts[2] ?>)</small>
            </span>
            <span class="emp-legend-item">
                <span class="emp-legend-swatch" style="background:#fef3c7"></span>
                <span style="color:#92400e;font-weight:700"><i class="fa fa-ban"></i> حساب مغلق</span>
                <small>(<?= $emp_st_counts[4] ?>)</small>
            </span>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-sm detail-tbl" id="detailTable">
                <thead class="table-light">
                <tr>
                    <?php if ($can_partial): ?><th style="width:36px"><input type="checkbox" id="chkAll" onchange="toggleAllChecks(this)"></th><?php endif; ?>
                    <th style="width:36px">#</th>
                    <th>الموظف</th>
                    <th>المقر</th>
                    <?php if ($is_type_2): ?>
                    <th class="text-end" title="الراتب الإجمالي للشهر" style="background:#f8fafc">الصافي</th>
                    <?php endif; ?>
                    <th class="text-end"<?= $is_type_2 ? ' title="المبلغ المدفوع فعلياً" style="background:#eff6ff"' : '' ?>>مبلغ الصرف</th>
                    <?php if ($is_type_2): ?>
                    <th class="text-end" title="المبلغ الذي سيُرحّل إلى بند المستحقات (323)" style="background:#faf5ff">سيُرحّل مستحقات</th>
                    <?php endif; ?>
                    <th style="width:80px">الحالة</th>
                    <th>ملاحظة</th>
                    <?php if ($can_add || $can_partial): ?><th style="width:50px"></th><?php endif; ?>
                </tr>
                </thead>
                <tbody id="detailBody">
                <?php
                $dtl_count = 1;
                $colspan_distrib = ($can_partial ? 1 : 0) + 3 + ($is_type_2 ? 2 : 0) + 1 + ($is_type_2 ? 1 : 0) + 2 + (($can_add || $can_partial) ? 1 : 0);
                $accounts_map_local = isset($accounts_map) && is_array($accounts_map) ? $accounts_map : [];
                $emp_summary_local  = isset($emp_summary)  && is_array($emp_summary)  ? $emp_summary  : [];
                foreach ($detail_rows as $d):
                    $ds = (int)($d['DETAIL_STATUS'] ?? 0);
                    $dsb = $status_badges[$ds] ?? ['—','#f1f5f9','#475569'];
                    $eno = (int)($d['EMP_NO'] ?? 0);
                    $emp_accs_all = $accounts_map_local[$eno] ?? [];
                    // فصل الحسابات: نشطة (للتوزيع) + موقوفة (للتنبيه فقط)
                    $emp_accs_active = array_filter($emp_accs_all, function($a) {
                        return !empty($a['ACC_ID']) && (int)($a['ACC_IS_ACTIVE'] ?? 1) === 1;
                    });
                    $emp_accs_inactive = array_filter($emp_accs_all, function($a) {
                        return !empty($a['ACC_ID']) && (int)($a['ACC_IS_ACTIVE'] ?? 1) === 0;
                    });
                    // ملخص الموظف (من emp_summary)
                    $es = $emp_summary_local[$eno] ?? null;
                    $emp_is_active     = $es ? (int)$es['is_active'] : 1;
                    $emp_disp_status   = $es ? (int)($es['display_status'] ?? 1) : 1;
                    // 1=فعّال, 0=متقاعد, 2=متوفى, 4=حساب مغلق
                    $req_amount        = $es ? (float)$es['req_amount'] : (float)($d['REQ_AMOUNT'] ?? 0);
                    $alloc_total       = $es ? (float)$es['alloc_total'] : 0;
                    $desired_total     = $es ? (float)$es['desired_total'] : 0;
                    $is_overage        = $es ? (int)$es['is_overage'] : 0;
                    // 🆕 تفاصيل التجاوز
                    $overage_cause     = $es ? (int)($es['overage_cause']     ?? 0) : 0;
                    $overage_fixed_sum = $es ? (float)($es['overage_fixed_sum'] ?? 0) : 0;
                    $overage_pct_sum   = $es ? (float)($es['overage_pct_sum']   ?? 0) : 0;
                    $inactive_acc_cnt  = $es ? (int)$es['inactive_acc_cnt'] : count($emp_accs_inactive);
                    $benef_total       = $es ? (int)$es['benef_total'] : 0;
                    $benef_linked      = $es ? (int)$es['benef_linked'] : 0;
                    $inactive_reasons  = $es ? $es['inactive_reasons'] : [];

                    $has_split    = count($emp_accs_active) > 1;
                    $has_no_acc   = count($emp_accs_active) === 0;
                    $has_benef    = false;
                    foreach ($emp_accs_active as $a) { if (!empty($a['BENEFICIARY_ID'])) { $has_benef = true; break; } }

                    // ⚠️ التحذيرات (للسطر تحت الموظف)
                    $warnings = [];
                    if ($is_overage) {
                        // 🔒 BATCH_CONFIRM يستثني الموظف بالكامل لو في تجاوز
                        $warnings[] = '<span class="d-block"><i class="fa fa-lock"></i> <b>التوزيع تجاوز المستحق</b> (' . n_format($desired_total) . ' من ' . n_format($req_amount) . ') — الموظف <b>سيُستثنى تلقائياً</b> ولن يُصرف له ولا حساباته (ولا الورثة) أي مبلغ. أصلح التوزيع قبل الاعتماد.</span>';
                    } else {
                        $alloc_diff = round($req_amount - $alloc_total, 2);
                        if (!$has_no_acc && $alloc_diff > 0.01) {
                            $warnings[] = '<span class="d-warn"><i class="fa fa-exclamation-triangle"></i> متبقي <b>' . n_format($alloc_diff) . '</b> غير موزّع — أضف حساب "كامل الباقي"</span>';
                        }
                    }
                    if ($benef_total > 0 && $benef_linked < $benef_total) {
                        $missing = $benef_total - $benef_linked;
                        $warnings[] = '<span class="d-warn"><i class="fa fa-user-times"></i> <b>' . $missing . '</b> من ' . $benef_total . ' وريث غير مربوط بحساب — لن يستلم نصيبه</span>';
                    }
                    if ($inactive_acc_cnt > 0) {
                        $reason_str = !empty($inactive_reasons) ? ' (' . implode(', ', $inactive_reasons) . ')' : '';
                        $warnings[] = '<span class="d-info"><i class="fa fa-pause-circle"></i> <b>' . $inactive_acc_cnt . '</b> حساب موقوف' . $reason_str . ' — لن يُصرف عليه</span>';
                    }

                    // لون الصف: حسب حالة الموظف (4 حالات) ثم تحذيرات إضافية
                    // الأولوية: متوفى > حساب مغلق > overage/no-acc > متقاعد > تحذيرات > عادي
                    $row_bg = '';
                    if ($emp_disp_status === 2)                        $row_bg = 'background:#fee2e2'; // متوفى — أحمر فاتح
                    elseif ($emp_disp_status === 4)                    $row_bg = 'background:#fef3c7'; // حساب مغلق — أصفر داكن
                    elseif ($has_no_acc || $is_overage)                $row_bg = 'background:#fef2f2'; // overage — أحمر باهت
                    elseif ($emp_disp_status === 0)                    $row_bg = 'background:#ffedd5'; // متقاعد — برتقالي فاتح
                    elseif (count($warnings) > 0 || $has_benef)        $row_bg = 'background:#fefce8'; // تحذيرات — أصفر فاتح

                    // عدّاد للـ legend
                    if (!isset($emp_status_count)) $emp_status_count = [1=>0, 0=>0, 2=>0, 4=>0];
                    if (isset($emp_status_count[$emp_disp_status])) $emp_status_count[$emp_disp_status]++;
                ?>
                <tr<?= $row_bg ? ' style="' . $row_bg . '"' : '' ?>>
                    <?php if ($can_partial): ?><td class="text-center"><input type="checkbox" class="dtl-chk" value="<?= $d['DETAIL_ID'] ?>" data-status="<?= $ds ?>"></td><?php endif; ?>
                    <td class="text-center text-muted"><?= $dtl_count++ ?></td>
                    <td><span class="fw-bold"><?= $d['EMP_NO'] ?? '' ?></span> <span class="text-muted">- <?= $d['EMP_NAME'] ?? '' ?></span>
                        <?php if ($is_overage): ?>
                            <span class="b-tag b-blocked" title="التوزيع تجاوز المستحق — سيُستثنى تلقائياً عند الاعتماد"><i class="fa fa-lock"></i> محظور</span>
                        <?php endif; ?>
                        <?php
                        // Badge حالة الموظف — 4 حالات بألوان مميزة
                        if ($emp_disp_status === 2): ?>
                            <span class="b-tag" style="background:#fee2e2;color:#991b1b;font-weight:700" title="الموظف متوفى — حساب لمستفيد/وريث">
                                <i class="fa fa-times-circle"></i> متوفى
                            </span>
                        <?php elseif ($emp_disp_status === 4): ?>
                            <span class="b-tag" style="background:#fef3c7;color:#92400e;font-weight:700" title="حسابات الموظف مغلقة من البنك">
                                <i class="fa fa-ban"></i> حساب مغلق
                            </span>
                        <?php elseif ($emp_disp_status === 0): ?>
                            <span class="b-tag" style="background:#ffedd5;color:#9a3412;font-weight:700" title="موظف متقاعد">
                                <i class="fa fa-clock-o"></i> متقاعد
                            </span>
                        <?php endif; ?>
                        <?php if ($has_split): ?>
                            <span class="b-tag b-multi" title="مبلغ هذا الموظف موزّع على أكثر من حساب"><i class="fa fa-sitemap"></i> <?= count($emp_accs_active) ?> حسابات</span>
                        <?php endif; ?>
                        <?php if ($has_benef): ?>
                            <span class="b-tag b-benef" title="جزء من المبلغ يصرف لمستفيد/وريث"><i class="fa fa-users"></i> ورث</span>
                        <?php endif; ?>
                        <?php if ($has_no_acc): ?>
                            <span class="b-tag b-warn" title="لا يوجد حسابات نشطة لهذا الموظف"><i class="fa fa-exclamation-triangle"></i> بدون حسابات</span>
                        <?php endif; ?>
                        <?php if ($inactive_acc_cnt > 0): ?>
                            <span class="b-tag b-pause" title="<?= $inactive_acc_cnt ?> حساب موقوف"><i class="fa fa-pause"></i> <?= $inactive_acc_cnt ?> موقوف</span>
                        <?php endif; ?>
                        <?php
                        // 🆕 زر "📋 X بند" — يفتح modal بالبنود الأصلية (لو الموظف جاي من استيراد إكسل)
                        $imp_lines_cnt = (int)($d['IMPORT_LINES_CNT'] ?? 0);
                        if ($imp_lines_cnt > 1):
                        ?>
                            <button type="button" class="b-tag" style="background:#eef2ff;color:#3730a3;font-weight:700;border:0;cursor:pointer"
                                    onclick="showImportLines(<?= (int)$d['DETAIL_ID'] ?>, '<?= htmlspecialchars(addslashes($d['EMP_NAME'] ?? ''), ENT_QUOTES) ?>', <?= (int)$imp_lines_cnt ?>)"
                                    title="عرض البنود الأصلية المستوردة من Excel قبل الدمج">
                                <i class="fa fa-list-ol"></i> <?= $imp_lines_cnt ?> بند مدموج
                            </button>
                        <?php elseif ($imp_lines_cnt == 1): ?>
                            <span class="b-tag" style="background:#f1f5f9;color:#475569;font-size:.62rem"
                                  title="مستورد من Excel (سطر واحد)">
                                <i class="fa fa-file-excel-o"></i>
                            </span>
                        <?php endif; ?>
                        <?php
                        // 🆕 Override badge — يعرض إذا كان فيه override على هذا السطر
                        $ovr_pt    = isset($d['OVERRIDE_PROVIDER_TYPE']) ? $d['OVERRIDE_PROVIDER_TYPE'] : null;
                        $ovr_acc   = isset($d['OVERRIDE_ACC_ID']) && $d['OVERRIDE_ACC_ID'] ? (int)$d['OVERRIDE_ACC_ID'] : null;
                        $ovr_label = $d['OVERRIDE_LABEL'] ?? '';
                        $has_override = ($ovr_pt !== null && $ovr_pt !== '') || $ovr_acc;
                        if ($has_override):
                            $bg = $ovr_acc ? '#fef3c7' : ((int)$ovr_pt === 2 ? '#f5f3ff' : '#dbeafe');
                            $fg = $ovr_acc ? '#92400e' : ((int)$ovr_pt === 2 ? '#6d28d9' : '#1e40af');
                            $ic = $ovr_acc ? 'fa-bullseye' : ((int)$ovr_pt === 2 ? 'fa-mobile' : 'fa-bank');
                        ?>
                            <span class="b-tag" title="<?= htmlspecialchars($ovr_label) ?>"
                                  style="background:<?= $bg ?>;color:<?= $fg ?>;font-weight:700">
                                <i class="fa <?= $ic ?>"></i> <?= htmlspecialchars($ovr_label) ?>
                            </span>
                        <?php else: ?>
                            <!-- 🆕 badge "افتراضي" يظهر دائماً لما ما فيه override — عشان المحاسب ما ينسى -->
                            <span class="b-tag" title="التوزيع الافتراضي حسب حسابات الموظف"
                                  style="background:#f1f5f9;color:#475569;font-weight:700;border:1px dashed #cbd5e1">
                                <i class="fa fa-cog"></i> افتراضي
                            </span>
                        <?php endif; ?>
                    </td>
                    <td><?= $d['BRANCH_NAME'] ?? '' ?></td>
                    <?php if ($is_type_2): ?>
                    <td class="text-end" style="color:#475569"><?= n_format((float)($d['NET_SALARY_CALC'] ?? 0)) ?></td>
                    <?php endif; ?>
                    <td class="text-end amt"<?= $is_type_2 ? ' style="color:#1e40af;font-weight:700"' : '' ?>><?= n_format((float)($d['REQ_AMOUNT'] ?? 0)) ?><?php
                        $lf = $d['LIMIT_FLAG'] ?? '';
                        if ($lf === 'MIN') echo ' <span style="background:#fef3c7;color:#92400e;padding:1px 5px;border-radius:4px;font-size:.65rem;font-weight:600">حد أدنى</span>';
                        elseif ($lf === 'MAX') echo ' <span style="background:#fee2e2;color:#991b1b;padding:1px 5px;border-radius:4px;font-size:.65rem;font-weight:600">حد أعلى</span>';
                        elseif ($lf === 'CAP') echo ' <span style="background:#dbeafe;color:#1e40af;padding:1px 5px;border-radius:4px;font-size:.65rem;font-weight:600">كامل</span>';
                    ?></td>
                    <?php if ($is_type_2): ?>
                    <td class="text-end" style="color:#6b21a8"><?= n_format((float)($d['ACCRUED_323_CALC'] ?? 0)) ?></td>
                    <?php endif; ?>
                    <td><span style="background:<?= $dsb[1] ?>;color:<?= $dsb[2] ?>;padding:2px 8px;border-radius:6px;font-size:.72rem;font-weight:600"><?= $dsb[0] ?></span></td>
                    <td class="text-muted" style="font-size:.75rem"><?= $d['NOTE'] ?? '' ?></td>
                    <?php if ($can_add || $can_partial): ?>
                    <td class="text-center actions-cell">
                        <?php if ($ds != 9): ?>
                            <?php if ($ds == 0 || $ds == 1):
                                // 🆕 Inline toggle group — تبديل سريع بدون modal
                                $is_default  = !$has_override;
                                $is_bank     = ($ovr_pt == 1 && !$ovr_acc);
                                $is_wallet   = ($ovr_pt == 2 && !$ovr_acc);
                                $is_specific = !empty($ovr_acc);
                                $emp_name_js = htmlspecialchars(addslashes($d['EMP_NAME'] ?? ''), ENT_QUOTES);
                                $pt_arg      = $ovr_pt !== null && $ovr_pt !== '' ? (int)$ovr_pt : 'null';
                                $acc_arg     = $ovr_acc ? (int)$ovr_acc : 'null';
                            ?>
                                <div class="ovr-toggle btn-group btn-group-sm me-1" role="group" data-detail-id="<?= $d['DETAIL_ID'] ?>">
                                    <button type="button" class="btn btn-sm <?= $is_default  ? 'btn-secondary' : 'btn-outline-secondary' ?> ovr-tog"
                                            onclick="quickSetOverride(<?= $d['DETAIL_ID'] ?>, 'default')"
                                            title="افتراضي — توزيع تلقائي حسب حسابات الموظف">
                                        <i class="fa fa-cog"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm <?= $is_bank ? 'btn-primary' : 'btn-outline-primary' ?> ovr-tog"
                                            onclick="quickSetOverride(<?= $d['DETAIL_ID'] ?>, 'bank')"
                                            title="بنك فقط — كل المبلغ على حسابات البنوك">
                                        <i class="fa fa-bank"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm <?= $is_wallet ? 'btn-info text-white' : 'btn-outline-info' ?> ovr-tog"
                                            onclick="quickSetOverride(<?= $d['DETAIL_ID'] ?>, 'wallet')"
                                            title="محفظة فقط — كل المبلغ على المحافظ">
                                        <i class="fa fa-mobile"></i>
                                    </button>
                                </div>
                                <!-- 🆕 زر "تعديل التوزيع" واضح — يفتح المودال (للحالات المعقدة + اختيار حساب محدد) -->
                                <button type="button" class="btn btn-sm <?= $is_specific ? 'btn-warning' : 'btn-outline-warning' ?> me-1"
                                        onclick="openOverrideModal(<?= $d['DETAIL_ID'] ?>, <?= $eno ?>, '<?= $emp_name_js ?>', <?= (float)($d['REQ_AMOUNT'] ?? 0) ?>, <?= $pt_arg ?>, <?= $acc_arg ?>)"
                                        title="تعديل التوزيع — افتح نافذة الخيارات الكاملة">
                                    <i class="fa fa-random"></i> تعديل
                                </button>
                            <?php endif; ?>
                            <button class="btn btn-sm btn-outline-danger" onclick="deleteDetail(<?= $d['DETAIL_ID'] ?>)" title="<?= $ds == 0 ? 'حذف' : ($ds == 1 ? 'إلغاء اعتماد' : 'عكس صرف') ?>"><i class="fa fa-<?= $ds == 0 ? 'trash' : ($ds == 1 ? 'undo' : 'reply') ?>"></i></button>
                        <?php endif; ?>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php
                // التحذيرات والـ pills نُقلت إلى Tab 2 (المستفيدون) — نظافة جدول Tab 1
                // فقط نُظهر تحذيرات حرجة inline (overage / no-acc) كـ صف تنبيه صغير
                if ($is_overage || $has_no_acc):
                ?>
                <tr class="emp-warning-row">
                    <td colspan="<?= $colspan_distrib ?>" style="background:#fef2f2;padding:.4rem .75rem;border-top:0;font-size:.78rem;color:#991b1b">
                        <?php if ($is_overage):
                            // 🛠 توضيح السبب المحدد للتجاوز + زر إصلاح
                            $cause_msg = '';
                            switch ($overage_cause) {
                                case 1:
                                    $cause_msg = 'مجموع <b>المبالغ الثابتة</b> ' . n_format($overage_fixed_sum)
                                               . ' أكبر من المستحق ' . n_format($req_amount)
                                               . ' — قلّل قيمة حساب أو أكثر';
                                    break;
                                case 2:
                                    $cause_msg = 'مجموع <b>النسب المئوية</b> ' . round($overage_pct_sum, 2)
                                               . '% أكبر من 100% — قلّل النسب';
                                    break;
                                case 3:
                                    $pct_amt   = round($req_amount * $overage_pct_sum / 100, 2);
                                    $cause_msg = '<b>المبالغ الثابتة + النسب</b> ('
                                               . n_format($overage_fixed_sum) . ' + ' . n_format($pct_amt) . ' = '
                                               . n_format($overage_fixed_sum + $pct_amt) . ') أكبر من المستحق '
                                               . n_format($req_amount);
                                    break;
                                default:
                                    $cause_msg = 'التوزيع غير صحيح';
                            }
                        ?>
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                <div>
                                    <i class="fa fa-lock me-1"></i>
                                    <b>التوزيع تجاوز المستحق</b> — <?= $cause_msg ?>
                                    <div style="font-size:.7rem;color:#7f1d1d;margin-top:.15rem">
                                        <i class="fa fa-info-circle"></i>
                                        الموظف <b>سيُستثنى تلقائياً</b> عند الاعتماد ولن يُصرف له (ولا للورثة) أي مبلغ.
                                    </div>
                                </div>
                                <a href="<?= base_url('payment_accounts/payment_accounts/emp/' . (int)$eno) ?>"
                                   target="_blank" rel="noopener"
                                   class="btn btn-sm btn-danger" style="white-space:nowrap">
                                    <i class="fa fa-wrench"></i> إصلاح التوزيع
                                </a>
                            </div>
                        <?php else: ?>
                            <i class="fa fa-exclamation-triangle me-1"></i>
                            <b>لا يوجد حسابات نشطة</b> — لن يُصرف لهذا الموظف.
                            <a href="<?= base_url('payment_accounts/payment_accounts/emp/' . (int)$eno) ?>"
                               target="_blank" rel="noopener"
                               class="btn btn-sm btn-outline-danger ms-2">
                                <i class="fa fa-external-link"></i> إضافة حساب
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endif; ?>
                <?php endforeach; ?>
                </tbody>
                <tfoot>
                <tr class="detail-total">
                    <?php
                        // حساب colspan للإجمالي حسب النوع
                        $before_amount_cols = ($can_partial ? 1 : 0) + 3; // chk + # + emp + branch
                        if ($is_type_2) $before_amount_cols += 1; // + الصافي
                        $after_amount_cols = ($is_type_2 ? 1 : 0) + 2; // سيُرحّل + الحالة + ملاحظة
                        if ($can_add || $can_partial) $after_amount_cols += 1; // actions
                    ?>
                    <?php if ($is_type_2): ?>
                    <td colspan="<?= $before_amount_cols - 1 ?>" class="text-end">الإجمالي</td>
                    <td class="text-end" style="color:#475569"><?= n_format($total_net_calc) ?></td>
                    <td class="text-end" id="detailFootTotal" style="color:#1e40af;font-weight:700"><?= n_format($total_amount) ?></td>
                    <td class="text-end" style="color:#6b21a8"><?= n_format($total_accrued_323) ?></td>
                    <td colspan="<?= ($can_add || $can_partial) ? 3 : 2 ?>"></td>
                    <?php else: ?>
                    <td colspan="<?= $before_amount_cols ?>" class="text-end">الإجمالي</td>
                    <td class="text-end" id="detailFootTotal"><?= n_format($total_amount) ?></td>
                    <td colspan="<?= ($can_add || $can_partial) ? 3 : 2 ?>"></td>
                    <?php endif; ?>
                </tr>
                </tfoot>
            </table>
        </div>
        <div id="detailLimitSummary"></div>
        </div><!-- /tab-emps -->

        <!-- ─────── Tab 2: المستفيدون ─────── -->
        <div class="tab-pane fade" id="tab-recipients" role="tabpanel">
            <!-- إحصائيات سريعة (الصف الأول: العدد) -->
            <div class="rcp-stats mb-2">
                <div class="rcp-stat">
                    <div class="rs-lbl"><i class="fa fa-list-ol"></i> إجمالي الحركات</div>
                    <div class="rs-val"><?= count($recipients) ?></div>
                    <div class="rs-sub"><?= $stat_bank_count ?> بنك<?php if ($stat_wallet_count > 0): ?> + <?= $stat_wallet_count ?> محفظة<?php endif; ?></div>
                </div>
                <div class="rcp-stat c-self">
                    <div class="rs-lbl"><i class="fa fa-user"></i> الموظف يقبض لنفسه</div>
                    <div class="rs-val"><?= $stat_self ?></div>
                </div>
                <div class="rcp-stat c-benef">
                    <div class="rs-lbl"><i class="fa fa-users"></i> ورثة / مستفيدون</div>
                    <div class="rs-val"><?= $stat_benef ?></div>
                </div>
                <?php if ($stat_other_owner > 0): ?>
                <div class="rcp-stat c-owner">
                    <div class="rs-lbl"><i class="fa fa-id-card"></i> صاحب حساب مختلف</div>
                    <div class="rs-val"><?= $stat_other_owner ?></div>
                </div>
                <?php endif; ?>
                <?php if ($stat_split_emps > 0): ?>
                <div class="rcp-stat c-split" title="موظفون مبلغهم موزّع على أكثر من حساب">
                    <div class="rs-lbl"><i class="fa fa-sitemap"></i> موظفون بتوزيع</div>
                    <div class="rs-val"><?= $stat_split_emps ?></div>
                </div>
                <?php endif; ?>
                <?php if ($stat_blocked > 0 || $stat_no_acc > 0): ?>
                <div class="rcp-stat c-warn" title="موظفون لن يُصرف لهم — راجع تبويب الموظفين">
                    <div class="rs-lbl"><i class="fa fa-exclamation-triangle"></i> مستثنون</div>
                    <div class="rs-val"><?= $stat_blocked + $stat_no_acc ?></div>
                </div>
                <?php endif; ?>
            </div>

            <!-- إحصائيات سريعة (الصف الثاني: المبالغ) -->
            <div class="rcp-stats">
                <div class="rcp-stat c-amt">
                    <div class="rs-lbl"><i class="fa fa-money"></i> إجمالي المبلغ المُصرَف للبنوك</div>
                    <div class="rs-val"><?= n_format($stat_total_alloc) ?></div>
                    <div class="rs-sub" style="color:#cbd5e1">يظهر في الجدول أدناه</div>
                </div>
                <?php
                // 🆕 "سيُرحَّل كمستحقات" + "إجمالي صافي الشهر" — مفاهيم خاصة بنوع 2
                // (دفعة من الراتب) فقط — حيث الجزء غير المصروف يُرحَّل كمستحقات.
                // لباقي الأنواع: لا معنى لها (نوع 5 = إضافات/استحقاقات، نوع 3 = من المستحقات…).
                $show_dues_split = ($req_type_val == 2) && ($stat_total_dues > 0);
                if ($show_dues_split):
                ?>
                <div class="rcp-stat c-dues">
                    <div class="rs-lbl"><i class="fa fa-archive"></i> سيُرحَّل كمستحقات</div>
                    <div class="rs-val"><?= n_format($stat_total_dues) ?></div>
                    <div class="rs-sub">يُضاف لرصيد الموظف — لا تحويل بنكي</div>
                </div>
                <div class="rcp-stat c-net">
                    <div class="rs-lbl"><i class="fa fa-check-circle"></i> إجمالي صافي الشهر</div>
                    <div class="rs-val"><?= n_format($stat_total_alloc + $stat_total_dues) ?></div>
                    <div class="rs-sub">نقد + مستحقات = صافي</div>
                </div>
                <?php endif; ?>
            </div>

            <!-- شريط البحث والتصدير -->
            <div class="rcp-toolbar">
                <div class="input-group input-group-sm" style="max-width:380px">
                    <span class="input-group-text"><i class="fa fa-search"></i></span>
                    <input type="text" id="rcpSearch" class="form-control" placeholder="بحث: موظف، مستلم، بنك، حساب، IBAN...">
                    <button class="btn btn-outline-secondary" type="button" id="rcpSearchClear" title="مسح"><i class="fa fa-times"></i></button>
                </div>
                <div class="ms-auto d-flex gap-1 flex-wrap">
                    <select id="rcpFilterType" class="form-select form-select-sm" style="width:auto">
                        <option value="">— كل الأنواع —</option>
                        <option value="self">الموظف نفسه</option>
                        <option value="benef">ورثة فقط</option>
                        <option value="other_owner">صاحب حساب مختلف</option>
                    </select>
                    <select id="rcpFilterSplit" class="form-select form-select-sm" style="width:auto" title="فلترة حسب طريقة التوزيع">
                        <option value="">— كل طرق التوزيع —</option>
                        <option value="0">كامل (حساب واحد)</option>
                        <option value="2">مبلغ ثابت</option>
                        <option value="1">نسبة مئوية</option>
                        <option value="3">كامل الباقي</option>
                    </select>
                    <button type="button" class="btn btn-success btn-sm" id="rcpExportBtn" title="تصدير Excel">
                        <i class="fa fa-file-excel-o me-1"></i> تصدير Excel
                    </button>
                </div>
            </div>

            <!-- جدول المستفيدين -->
            <div class="table-responsive">
            <table class="table table-bordered table-sm rcp-tbl mb-0" id="recipientsTable">
                <thead class="table-light">
                    <tr>
                        <th style="width:40px">#</th>
                        <th>الموظف الأصلي</th>
                        <th>المقر</th>
                        <th style="width:90px">النوع</th>
                        <th>المستلِم</th>
                        <th>البنك / المحفظة</th>
                        <th>الفرع</th>
                        <th>الحساب / IBAN</th>
                        <th style="width:120px">طريقة التوزيع</th>
                        <th class="text-end" style="width:140px">المبلغ</th>
                    </tr>
                </thead>
                <tbody id="recipientsBody">
                    <!-- ⚡ Lazy load: الصفوف تُبنى عبر JS عند فتح Tab 2 لأول مرة (تسريع تحميل الصفحة) -->
                    <tr id="rcpLoadingRow"><td colspan="10" class="text-center py-4 text-muted">
                        <i class="fa fa-info-circle"></i> اضغط تبويب "المستفيدون" لعرض التفاصيل
                    </td></tr>
                </tbody>
                <tfoot>
                <tr style="background:#1e293b;color:#fff;font-weight:800">
                    <td colspan="9" class="text-end">الإجمالي</td>
                    <td class="text-end" id="rcpTotalCell" style="direction:ltr"><?= n_format($stat_total_alloc) ?></td>
                </tr>
                </tfoot>
            </table>
            </div>
            <div id="rcpFilterInfo" class="mt-2 text-muted text-center" style="font-size:.78rem;display:none"></div>
        </div><!-- /tab-recipients -->

        </div><!-- /tab-content -->

        <!-- ⚡ بيانات Tab 2 كـ JSON (lazy build في JS عند فتح التبويب) -->
        <script type="application/json" id="rcpData"><?= json_encode($recipients, JSON_UNESCAPED_UNICODE) ?></script>

    </div>
</div>
<?php endif; ?>

<?php
// ═══════════════════════════════════════════════════════════
// 🆕 قسم المرفقات — يظهر بعد إنشاء الطلب فقط
// ═══════════════════════════════════════════════════════════
if (!$isCreate && $HaveRs):
    $att_req_id    = (int)($rs['REQ_ID'] ?? 0);
    $att_category  = 'payment_req';
    $att_can_upload = ($rs['STATUS'] ?? 0) == 0 ? 1 : 0; // upload فقط للمسودات
    // قائمة المرفقات الحالية
    $this->load->model('attachments/attachment_model');
    $att_rows = $this->attachment_model->get_list($att_req_id, $att_category);
    if (!is_array($att_rows)) $att_rows = [];
?>
<div class="row mt-3">
<div class="col-lg-12">
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fa fa-paperclip me-2 text-primary"></i> المرفقات
            <span class="badge bg-info ms-2"><?= count($att_rows) ?></span>
        </h3>
        <?php if ($att_can_upload): ?>
            <button class="btn btn-success btn-sm ms-auto" onclick="openAttachUpload()">
                <i class="fa fa-upload me-1"></i> رفع مرفق جديد
            </button>
        <?php endif; ?>
    </div>
    <div class="card-body">
        <?php if (count($att_rows) === 0): ?>
            <div class="alert alert-light text-center text-muted py-3">
                <i class="fa fa-inbox fa-2x d-block mb-2" style="opacity:.4"></i>
                لا توجد مرفقات بعد
            </div>
        <?php else: ?>
            <div class="table-responsive">
            <table class="table table-bordered table-sm" style="font-size:.82rem">
                <thead class="table-light">
                <tr>
                    <th style="width:40px">#</th>
                    <th>اسم الملف</th>
                    <th style="width:170px">رفعه</th>
                    <th style="width:140px">التاريخ</th>
                    <th style="width:120px;text-align:center">إجراءات</th>
                </tr>
                </thead>
                <tbody>
                <?php $cur_user_id = method_exists($this, 'IsAuthorized') ? get_curr_user()->id : 0; ?>
                <?php foreach ($att_rows as $i => $att): ?>
                    <tr id="att_row_<?= $att['ID'] ?>">
                        <td><?= $i + 1 ?></td>
                        <td>
                            <a href="<?= base_url("archive/download/" . $att['FILE_PATH'] . "/" . clear_url($att['FILE_NAME'])) ?>"
                               target="_blank">
                                <i class="fa fa-file me-1 text-primary"></i>
                                <?= htmlspecialchars($att['FILE_NAME']) ?>
                            </a>
                        </td>
                        <td><?= htmlspecialchars(get_user_name($att['ENTRY_USER'])) ?></td>
                        <td><?= htmlspecialchars($att['ENTRY_DATE']) ?></td>
                        <td class="text-center">
                            <a href="<?= base_url("archive/download/" . $att['FILE_PATH'] . "/" . clear_url($att['FILE_NAME'])) ?>"
                               target="_blank" class="btn btn-sm btn-outline-primary" title="تحميل">
                                <i class="fa fa-download"></i>
                            </a>
                            <?php if ($att_can_upload && (int)$att['ENTRY_USER'] === (int)$cur_user_id): ?>
                                <button class="btn btn-sm btn-outline-danger" onclick="deleteAttachment(<?= $att['ID'] ?>)" title="حذف">
                                    <i class="fa fa-trash"></i>
                                </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            </div>
        <?php endif; ?>

        <?php if (!$att_can_upload): ?>
            <div class="alert alert-warning py-2 mt-2 mb-0" style="font-size:.78rem">
                <i class="fa fa-info-circle"></i>
                المرفقات قابلة للرفع/الحذف فقط في حالة "مسودة".
            </div>
        <?php endif; ?>
    </div>
</div>
</div>
</div>

<!-- مودال رفع مرفق -->
<?php if ($att_can_upload): ?>
<div class="modal fade" id="attachUploadModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h6 class="modal-title fw-bold"><i class="fa fa-upload me-2"></i> رفع مرفق جديد</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="attachUploadForm" enctype="multipart/form-data">
                    <input type="hidden" name="identity" value="<?= $att_req_id ?>">
                    <input type="hidden" name="category" value="<?= $att_category ?>">
                    <input type="hidden" name="upload_type" value="attachment">
                    <div class="mb-2">
                        <label class="form-label fw-bold">اختر الملف</label>
                        <input type="file" name="file" class="form-control" required>
                    </div>
                    <div class="alert alert-light py-2" style="font-size:.78rem">
                        <i class="fa fa-info-circle text-info"></i>
                        المسموح: PDF, Word, Excel, صور, ZIP — حتى 150MB
                    </div>
                    <div id="attachUploadProgress" style="display:none">
                        <div class="progress"><div class="progress-bar progress-bar-striped progress-bar-animated" style="width:100%">جاري الرفع...</div></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer py-2">
                <button class="btn btn-light btn-sm" data-bs-dismiss="modal">إلغاء</button>
                <button class="btn btn-success btn-sm" onclick="submitAttachUpload()">
                    <i class="fa fa-upload me-1"></i> رفع
                </button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<?php endif; // attachments section ?>

<?php
$log_rows = isset($log_rows) && is_array($log_rows) ? $log_rows : [];
if (!$isCreate && $HaveRs && count($log_rows) > 0):
$action_labels = [
    'CREATE' => ['إنشاء الطلب', 'fa-plus-circle', '#059669'],
    'UPDATE' => ['تعديل', 'fa-pencil', '#d97706'],
    'UPDATE_TYPE_CHANGE' => ['تغيير النوع', 'fa-exchange', '#dc2626'],
    'ADD_EMP' => ['إضافة موظف', 'fa-user-plus', '#2563eb'],
    'BULK_ADD' => ['إضافة جماعية', 'fa-users', '#2563eb'],
    'REMOVE_EMP' => ['حذف موظف', 'fa-user-times', '#dc2626'],
    'APPROVE' => ['اعتماد', 'fa-check-circle', '#059669'],
    'APPROVE_ALL' => ['اعتماد الكل', 'fa-check-circle', '#059669'],
    'UNAPPROVE' => ['إلغاء اعتماد', 'fa-undo', '#d97706'],
    'UNAPPROVE_EMP' => ['إلغاء اعتماد موظف', 'fa-undo', '#d97706'],
    'PAY' => ['صرف', 'fa-money', '#059669'],
    'UNPAY_EMP' => ['عكس صرف موظف', 'fa-reply', '#dc2626'],
    'CANCEL' => ['إلغاء', 'fa-ban', '#991b1b'],
    'DELETE' => ['حذف', 'fa-trash', '#991b1b'],
];
?>
<?php
    // تصنيف العمليات: رئيسية (مستوى الطلب) أو فردية (مستوى الموظف)
    $per_emp_actions = ['ADD_EMP','REMOVE_EMP','UNAPPROVE_EMP','UNPAY_EMP'];
    $main_count = 0; $emp_count_log = 0;
    foreach ($log_rows as $lg) {
        if (in_array($lg['ACTION'] ?? '', $per_emp_actions)) $emp_count_log++; else $main_count++;
    }
?>
<div class="card mt-3" id="logCard">
    <div class="card-header d-flex align-items-center gap-2 flex-wrap">
        <h3 class="card-title mb-0"><i class="fa fa-history me-2"></i> سجل العمليات</h3>
        <span style="font-size:.72rem;font-weight:500;color:#cbd5e1">(<span id="logMainCount"><?= $main_count ?></span> رئيسية<span id="logEmpCountWrap"<?= $emp_count_log > 0 ? '' : ' style="display:none"' ?>> + <span id="logEmpCount"><?= $emp_count_log ?></span> فردية</span>)</span>
        <div class="ms-auto" id="logEmpRowsControl"<?= $emp_count_log > 0 ? '' : ' style="display:none"' ?>>
            <label style="font-size:.78rem;cursor:pointer;user-select:none;color:#e2e8f0;display:inline-flex;align-items:center;gap:.35rem">
                <input type="checkbox" id="logShowEmpRows" onchange="toggleEmpLogRows(this)"> إظهار عمليات الموظفين الفرديين
            </label>
        </div>
    </div>
    <div class="card-body p-2">
        <div class="table-responsive">
            <table class="table table-sm table-bordered mb-0" style="font-size:.8rem">
                <thead class="table-light">
                <tr>
                    <th style="width:36px">#</th>
                    <th>العملية</th>
                    <th>من</th>
                    <th>إلى</th>
                    <th>المستخدم</th>
                    <th>التاريخ</th>
                    <th>موظفين</th>
                    <th class="text-end">المبلغ</th>
                    <th>ملاحظة</th>
                </tr>
                </thead>
                <tbody id="logTableBody">
                <?php $lc = 1; foreach ($log_rows as $lg):
                    $act = $lg['ACTION'] ?? '';
                    $al = $action_labels[$act] ?? [$act, 'fa-circle-o', '#64748b'];
                    $is_emp_row = in_array($act, $per_emp_actions);
                ?>
                <tr class="<?= $is_emp_row ? 'log-emp-row' : 'log-main-row' ?>"<?= $is_emp_row ? ' style="display:none"' : '' ?>>
                    <td class="text-center text-muted"><?= $lc++ ?></td>
                    <td><span style="color:<?= $al[2] ?>;font-weight:600"><i class="fa <?= $al[1] ?> me-1"></i><?= $al[0] ?></span></td>
                    <td><?= $lg['OLD_STATUS_NAME'] ?? '—' ?></td>
                    <td><?= $lg['NEW_STATUS_NAME'] ?? '—' ?></td>
                    <td><?= $lg['ACTION_USER_NAME'] ?? '' ?></td>
                    <td style="font-size:.75rem;direction:ltr"><?= $lg['ACTION_DATE'] ?? '' ?></td>
                    <td class="text-center"><?= $lg['EMP_COUNT'] ?? '' ?></td>
                    <td class="text-end"><?= isset($lg['TOTAL_AMOUNT']) ? n_format((float)$lg['TOTAL_AMOUNT']) : '' ?></td>
                    <td class="text-muted" style="font-size:.72rem;max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap" title="<?= htmlspecialchars($lg['NOTE'] ?? '') ?>"><?= $lg['NOTE'] ?? '' ?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
function toggleEmpLogRows(cb){
    document.querySelectorAll('.log-emp-row').forEach(function(tr){
        tr.style.display = cb.checked ? '' : 'none';
    });
}
</script>
<?php endif; ?>

    </div>
</div>
</div>
</div>

<?php
$POST_URL_JS    = json_encode($post_url);
$BACK_URL_JS    = json_encode($back_url);
$APPROVE_URL_JS = json_encode($approve_url);
$PAY_URL_JS     = json_encode($pay_url);
$DELETE_URL_JS  = json_encode($delete_url);
$DETAIL_ADD_JS  = json_encode($detail_add_url);
$DETAIL_DEL_JS  = json_encode($detail_del_url);
$DETAIL_LST_JS  = json_encode($detail_lst_url);
$IS_CREATE_JS   = $isCreate ? 'true' : 'false';
$REQ_ID_JS      = $req_id_val ? $req_id_val : 'null';
?>

<script type="text/javascript">
    var postUrl    = <?= $POST_URL_JS ?>;
    var backUrl    = <?= $BACK_URL_JS ?>;
    var approveUrl = <?= $APPROVE_URL_JS ?>;
    var payUrl     = <?= $PAY_URL_JS ?>;
    var deleteUrl  = <?= $DELETE_URL_JS ?>;
    var detailAddUrl = <?= $DETAIL_ADD_JS ?>;
    var detailDelUrl = <?= $DETAIL_DEL_JS ?>;
    var detailLstUrl = <?= $DETAIL_LST_JS ?>;
    var isCreate   = <?= $IS_CREATE_JS ?>;
    var detailPreviewUrl = "<?= base_url('payment_req/payment_req/detail_preview_single') ?>";
    var bulkPreviewUrl = "<?= base_url('payment_req/payment_req/bulk_preview') ?>";
    var bulkCreateUrl  = "<?= base_url('payment_req/payment_req/bulk_create') ?>";
    var reqId      = <?= $REQ_ID_JS ?>;
    var reqType    = <?= $HaveRs ? (int)($rs['REQ_TYPE'] ?? 0) : 0 ?>;
</script>

<?php if (!$isCreate && $can_add && HaveAccess($import_url)): ?>
    <?php $this->load->view('payment_req_import_modal'); ?>
<?php endif; ?>

<!-- ═════════ 🆕 Modal: عرض البنود الأصلية المستوردة من Excel ═════════ -->
<div class="modal fade" id="impLinesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content" style="border:0;border-radius:12px">
            <div class="modal-header py-2" style="background:linear-gradient(135deg,#4338ca,#6366f1);border-radius:12px 12px 0 0">
                <h6 class="modal-title text-white fw-bold">
                    <i class="fa fa-list-ol me-1"></i>
                    البنود الأصلية المستوردة <span id="impLinesEmpName"></span>
                    <span class="badge bg-light text-dark ms-2" id="impLinesCount">0</span>
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="impLinesBody">
                <div class="text-center py-4 text-muted"><i class="fa fa-spinner fa-spin"></i> جاري التحميل...</div>
            </div>
            <div class="modal-footer py-2">
                <a href="#" id="impLinesDownloadBtn" class="btn btn-success btn-sm" style="display:none">
                    <i class="fa fa-download me-1"></i> تنزيل ملف Excel
                </a>
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        var IMP_LINES_URL = '<?= base_url($MODULE_NAME . "/" . $TB_NAME . "/public_get_import_lines_json") ?>';
        var DOWNLOAD_BASE = '<?= base_url($MODULE_NAME . "/" . $TB_NAME . "/public_download_import_file") ?>';
        var REQ_ID_FOR_DL = <?= (int)($rs['REQ_ID'] ?? 0) ?>;

        // helper تنسيق رقم
        function _fmtAmt(n) {
            n = parseFloat(n) || 0;
            return n.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        }
        function _esc(s) { return $('<div>').text(s == null ? '' : s).html(); }

        window.showImportLines = function (detailId, empName, expectedCount) {
            $('#impLinesEmpName').text(empName ? '— ' + empName : '');
            $('#impLinesCount').text(expectedCount || 0);
            $('#impLinesBody').html('<div class="text-center py-4 text-muted"><i class="fa fa-spinner fa-spin"></i> جاري التحميل...</div>');
            $('#impLinesDownloadBtn').hide();
            bootstrap.Modal.getOrCreateInstance(document.getElementById('impLinesModal')).show();

            $.ajax({
                url: IMP_LINES_URL, method: 'GET', data: { detail_id: detailId }, dataType: 'json'
            }).done(function (resp) {
                if (!resp || !resp.ok) {
                    $('#impLinesBody').html('<div class="alert alert-danger">' + _esc(resp && resp.msg ? resp.msg : 'فشل التحميل') + '</div>');
                    return;
                }
                var rows = resp.data || [];
                if (rows.length === 0) {
                    $('#impLinesBody').html('<div class="alert alert-light text-center py-3"><i class="fa fa-inbox fa-2x d-block mb-2 text-muted"></i>لا يوجد بنود مستوردة لهذا الموظف</div>');
                    return;
                }
                $('#impLinesCount').text(rows.length);

                // ملف الإكسل (لو موجود — موحّد بين كل البنود عادةً)
                var firstFilePath = rows[0].FILE_PATH || '';
                var firstFileName = rows[0].FILE_NAME || '';
                var importDate    = rows[0].IMPORT_DATE || '';
                if (firstFilePath && REQ_ID_FOR_DL) {
                    $('#impLinesDownloadBtn').attr('href', DOWNLOAD_BASE + '/' + REQ_ID_FOR_DL + '/' + encodeURIComponent(firstFilePath)).show();
                }

                // بناء جدول البنود
                var html = '';
                if (firstFileName || importDate) {
                    html += '<div class="alert alert-light py-2 mb-2" style="font-size:.78rem">';
                    if (firstFileName) html += '<div><i class="fa fa-file-excel-o text-success"></i> <b>الملف:</b> ' + _esc(firstFileName) + '</div>';
                    if (importDate)    html += '<div><i class="fa fa-clock-o text-muted"></i> <b>تاريخ الاستيراد:</b> ' + _esc(importDate) + '</div>';
                    html += '</div>';
                }

                html += '<table class="table table-sm table-bordered" style="font-size:.82rem">';
                html += '<thead style="background:#f8fafc"><tr>';
                html += '<th style="width:50px" class="text-center">#</th>';
                html += '<th style="width:90px" class="text-center">صف Excel</th>';
                html += '<th class="text-end" style="width:130px">المبلغ</th>';
                html += '<th>ملاحظة الصف الأصلي</th>';
                html += '</tr></thead><tbody>';

                var total = 0;
                rows.forEach(function (r, i) {
                    var amt = parseFloat(r.AMOUNT) || 0;
                    total += amt;
                    html += '<tr>';
                    html += '<td class="text-center text-muted">' + (i + 1) + '</td>';
                    html += '<td class="text-center"><span class="badge bg-light text-dark" style="font-family:monospace">' + (r.EXCEL_ROW_NUM || '—') + '</span></td>';
                    html += '<td class="text-end" style="font-family:monospace;font-weight:700;color:#059669">' + _fmtAmt(amt) + '</td>';
                    html += '<td>' + (r.ORIGINAL_NOTE ? _esc(r.ORIGINAL_NOTE) : '<span class="text-muted">—</span>') + '</td>';
                    html += '</tr>';
                });
                html += '</tbody>';
                html += '<tfoot><tr style="background:#eef2ff"><th colspan="2" class="text-end">الإجمالي:</th>';
                html += '<th class="text-end" style="font-family:monospace;color:#3730a3">' + _fmtAmt(total) + '</th>';
                html += '<th></th></tr></tfoot>';
                html += '</table>';
                $('#impLinesBody').html(html);
            }).fail(function () {
                $('#impLinesBody').html('<div class="alert alert-danger">فشل الاتصال بالسيرفر</div>');
            });
        };
    })();
</script>

<!-- ═════════ Modal: تعديل توزيع الصرف لسطر معين ═════════ -->
<div class="modal fade" id="overrideModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header" style="background:#f8fafc;border-bottom:2px solid #e2e8f0">
        <h5 class="modal-title" style="font-size:1rem;font-weight:700">
          <i class="fa fa-random text-primary me-1"></i> تعديل توزيع الصرف
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="ovr_detail_id">
        <input type="hidden" id="ovr_emp_no">

        <div class="mb-3" style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:8px;padding:.6rem .9rem">
          <div style="font-size:.78rem;color:#1e40af;font-weight:600">الموظف:</div>
          <div id="ovr_emp_label" style="font-weight:700;font-size:.9rem"></div>
          <div style="font-size:.78rem;color:#475569;margin-top:.2rem">
            <i class="fa fa-money me-1"></i> المبلغ: <b id="ovr_amount_label" style="color:#1e40af"></b>
          </div>
        </div>

        <label class="fw-bold mb-2" style="font-size:.85rem">طريقة التوزيع:</label>
        <div class="ovr-options">
          <label class="ovr-opt">
            <input type="radio" name="ovr_mode" value="default" checked>
            <span class="ovr-opt-card">
              <i class="fa fa-cogs ovr-opt-icon" style="color:#64748b"></i>
              <div>
                <div class="ovr-opt-title">افتراضي</div>
                <div class="ovr-opt-desc">حسب توزيع الموظف الثابت في حساباته</div>
              </div>
            </span>
          </label>
          <label class="ovr-opt">
            <input type="radio" name="ovr_mode" value="bank">
            <span class="ovr-opt-card">
              <i class="fa fa-bank ovr-opt-icon" style="color:#1e40af"></i>
              <div>
                <div class="ovr-opt-title">بنك فقط</div>
                <div class="ovr-opt-desc">صرف كامل المبلغ على حسابات البنك (تجاهل المحافظ)</div>
              </div>
            </span>
          </label>
          <label class="ovr-opt">
            <input type="radio" name="ovr_mode" value="wallet">
            <span class="ovr-opt-card">
              <i class="fa fa-mobile ovr-opt-icon" style="color:#6d28d9"></i>
              <div>
                <div class="ovr-opt-title">محفظة فقط</div>
                <div class="ovr-opt-desc">صرف كامل المبلغ على حسابات المحفظة (تجاهل البنوك)</div>
              </div>
            </span>
          </label>
          <label class="ovr-opt">
            <input type="radio" name="ovr_mode" value="specific">
            <span class="ovr-opt-card">
              <i class="fa fa-bullseye ovr-opt-icon" style="color:#9a3412"></i>
              <div>
                <div class="ovr-opt-title">حساب محدد</div>
                <div class="ovr-opt-desc">100% للحساب المختار من القائمة</div>
              </div>
            </span>
          </label>
        </div>

        <div id="ovr_specific_box" class="mt-3" style="display:none">
          <label class="fw-bold mb-1" style="font-size:.8rem">اختر الحساب:</label>
          <div id="ovr_acc_loading" class="text-center text-muted py-3" style="font-size:.8rem">
            <i class="fa fa-spinner fa-spin"></i> جاري تحميل الحسابات...
          </div>
          <div id="ovr_acc_list" class="ovr-acc-list" style="display:none"></div>
          <div id="ovr_acc_empty" class="text-center text-muted py-3" style="font-size:.8rem;display:none">
            <i class="fa fa-info-circle"></i> لا يوجد حسابات نشطة لهذا الموظف
          </div>
        </div>
      </div>
      <div class="modal-footer" style="background:#f8fafc">
        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">إلغاء</button>
        <button type="button" class="btn btn-primary btn-sm" id="ovrSaveBtn"><i class="fa fa-save me-1"></i> حفظ التوزيع</button>
      </div>
    </div>
  </div>
</div>

<style>
.ovr-options{display:flex;flex-direction:column;gap:.5rem}
.ovr-opt{display:block;cursor:pointer;margin:0}
.ovr-opt input{position:absolute;opacity:0;pointer-events:none}
.ovr-opt-card{display:flex;align-items:center;gap:.7rem;padding:.6rem .9rem;border:2px solid #e2e8f0;border-radius:8px;background:#fff;transition:all .15s}
.ovr-opt:hover .ovr-opt-card{border-color:#cbd5e1;background:#f8fafc}
.ovr-opt input:checked + .ovr-opt-card{border-color:#1e40af;background:#eff6ff;box-shadow:0 0 0 2px rgba(30,64,175,.1)}
.ovr-opt-icon{font-size:1.2rem;width:24px;text-align:center}
.ovr-opt-title{font-weight:700;font-size:.85rem;color:#1e293b}
.ovr-opt-desc{font-size:.72rem;color:#64748b;margin-top:.1rem}

.ovr-acc-list{display:flex;flex-direction:column;gap:.4rem;max-height:300px;overflow-y:auto}
.ovr-acc-item{display:flex;align-items:center;gap:.6rem;padding:.5rem .75rem;border:1.5px solid #e2e8f0;border-radius:6px;background:#fff;cursor:pointer;transition:all .15s}
.ovr-acc-item:hover{border-color:#93c5fd;background:#f8fafc}
.ovr-acc-item.selected{border-color:#059669;background:#f0fdf4}
.ovr-acc-item .ai-icon{font-size:1rem;width:22px;text-align:center}
.ovr-acc-item .ai-info{flex:1;font-size:.78rem}
.ovr-acc-item .ai-prov{font-weight:700;color:#1e293b}
.ovr-acc-item .ai-line{font-size:.7rem;color:#64748b;font-family:monospace;margin-top:.1rem}
.ovr-acc-item .ai-owner{font-size:.7rem;color:#7c3aed;margin-top:.1rem}
.ovr-acc-item .ai-tag{font-size:.65rem;padding:.1em .4em;border-radius:4px;font-weight:700}
.ovr-acc-item .ai-tag.tag-default{background:#fef3c7;color:#92400e}
</style>

<?php ob_start(); ?>
<script type="text/javascript">

    var salCheckUrl    = "<?= base_url('payment_req/payment_req/check_month_status') ?>";
    var _showMonthCalc = null;

    $(function(){
        $('.sel2:not("[id^=\'s2\']")').select2();
        $('#the_month').datetimepicker({ format: 'YYYYMM', minViewMode: 'months', pickTime: false });
        // 🆕 شهر فلتر اختياري (للنوع 3) — useCurrent:false عشان ما يتعبّى تلقائياً بالشهر الحالي
        if (document.getElementById('filter_month')) {
            $('#filter_month').datetimepicker({
                format: 'YYYYMM',
                minViewMode: 'months',
                pickTime: false,
                useCurrent: false   // ⭐ يبقى فاضي حتى المحاسب يختار يدوياً
            });
        }

        // فتح مودال Excel تلقائياً لو جاي من إنشاء + Excel
        if(window.location.search.indexOf('open_excel=1') > -1 && typeof openImpExcelModal === 'function'){
            setTimeout(function(){ openImpExcelModal(); }, 500);
        }

        // عرض Select2 لاختيار الموظفين (كل الأنواع)
        if($('#add_emp_select option').length > 1){
            $('#add_emp_select_grp').show();
            $('#add_emp_no_grp').hide();
            $('#add_emp_name_grp').hide();
            $('#add_emp_select').select2({placeholder: '- اختر موظف -', allowClear: true, width: '100%'});
            $('#add_emp_select').on('change', function(){
                var v = $(this).val();
                $('#add_emp_no').val(v);
                if(v) $('#add_emp_no').trigger('input');
            });
        }

        // البحث عن الموظف بالرقم + معاينة المبلغ
        var _empLookupTimer = null;
        $('#add_emp_no').on('input change', function(){
            var emp = $(this).val();
            $('#add_emp_name').val('');
            $('#add_preview_info').hide();
            if(!emp || emp.length < 1 || !reqId) return;
            clearTimeout(_empLookupTimer);
            _empLookupTimer = setTimeout(function(){
                get_data(detailPreviewUrl, {req_id: reqId, emp_no: emp, req_amount: $('#add_amount').val() || 0}, function(resp){
                    var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
                    if(!j.ok){
                        $('#add_emp_name').val(j.msg || 'غير موجود');
                        $('#add_preview_info').show();
                        $('#add_preview_error').text(j.msg || 'خطأ').show();
                        $('#add_preview_dues, #add_preview_calc').text('—');
                        return;
                    }
                    $('#add_preview_error').hide();
                    if(j.emp_name) $('#add_emp_name').val(j.emp_name);
                    if(reqType == 5){
                        $('#add_preview_info').hide();
                    } else {
                        $('#add_preview_dues').text(nf(j.dues_avail || 0));
                        $('#add_preview_calc').text(nf(j.calc_amount || 0));
                        if(j.limit_flag){
                            var lbl = j.limit_flag==='MIN'?'(حد أدنى)':j.limit_flag==='MAX'?'(حد أعلى)':'(كامل)';
                            $('#add_preview_flag').text(lbl);
                        } else {
                            $('#add_preview_flag').text('');
                        }
                        $('#add_preview_info').show();
                    }
                }, 'json');
            }, 400);
        });

        // Enter = إضافة
        $('#add_emp_no, #add_amount, #add_note').on('keydown', function(e){
            if(e.key === 'Enter'){ e.preventDefault(); addToQueue(); }
        });
        $('#entry_date').datetimepicker({ format: 'DD/MM/YYYY', pickTime: false });

        $('#the_month').on('change blur', function(){
            var rt = $('#req_type').val();
            if(rt == '1' || rt == '2' || rt == '4') showCheckMonth();
        });
    });

    // ═══ اختيار نوع الطلب بالبطاقات ═══
    function wizSelectType(rt){
        $('.type-card').removeClass('selected');
        $('.type-card[data-type="'+rt+'"]').addClass('selected');
        $('#req_type').val(rt);
        $('#wiz_settings').slideDown(200);
        $('#wiz_step3').hide();

        // إخفاء كل الحقول الديناميكية
        $('#show_type2_section, #show_calc_section, #show_percent_grp, #show_amount_grp').hide();
        $('#show_calc_method_grp, #show_limits_grp, #show_pay_type_grp, #show_benefit_grp').hide();
        $('#show_amount_mode_grp, #show_filter_month_grp').hide();
        $('#calc_method, #percent_val').val('');
        $('#show_amount_mode').val('same');
        // 🆕 الشهر العلوي — نخفيه للنوع 3 (لا معنى مالي له)، ونُظهره لباقي الأنواع
        $('#the_month').closest('.form-group').toggle(rt != 3);

        // بطاقات الاختيار لكل الأنواع — نوع 5 بدون "كل الموظفين"
        $('#wiz_pick_emps, #wiz_pick_excel').hide();
        $('#wiz_choice').show();
        $('#wiz_choice_all_card').toggle(rt != 5);

        if(rt == 1) {
            showLoadPayTypes('12');
            $('#show_type2_section').show();
            showCheckMonth();
            $('#wiz_type_hint_text').text('المبلغ يُحتسب تلقائياً من الراتب المحوّل مستحقات — لا حاجة لإدخاله يدوياً');
        }
        else if(rt == 2) {
            showLoadPayTypes('12');
            // نوع 2 = نسبة فقط (لا يوجد مبلغ ثابت) — نخفي dropdown الطريقة ونثبّتها PERCENT
            $('#calc_method').val('1');
            $('#show_calc_method_sel').val('1');
            $('#show_calc_method_grp').hide();
            showOnCalcMethodChange();
            $('#show_limits_grp').show();
            $('#show_type2_section').show();
            showCheckMonth();
            $('#wiz_type_hint_text').text('المبلغ = نسبة % من الراتب — يُحتسب تلقائياً لكل موظف حسب النسبة والحدود');
        }
        else if(rt == 3) {
            showLoadPayTypes('8');
            $('#calc_method').val('2');
            $('#show_calc_method_grp').show();
            $('#show_calc_method_sel').val('2');
            showOnCalcMethodChange();
            // 🆕 شهر الفلتر الاختياري — للنوع 3 فقط
            $('#show_filter_month_grp').show();
            $('#wiz_type_hint_text').text('يُخصم من رصيد المستحقات العام — اختر نسبة أو مبلغ ثابت. يمكن فلترة الموظفين بشهر احتساب معيّن.');
        }
        else if(rt == 4) {
            showLoadPayTypes('12');
            $('#show_amount_mode_grp').show();
            $('#show_amount_mode').val('same');
            showOnAmountModeChange();
            $('#show_type2_section').show();
            showCheckMonth();
            $('#wiz_type_hint_text').text('المتبقي من بند 323 للشهر — اختر "نفس المبلغ لكل الموظفين" أو "مبلغ مختلف لكل موظف"');
        }
        else if(rt == 5) {
            showLoadBenefitItems();
            $('#show_benefit_grp').show();
            $('#show_amount_mode_grp').show();
            $('#show_amount_mode').val('same');
            showOnAmountModeChange();
            $('#wiz_type_hint_text').text('صرف من بنود الاستحقاقات — بدون تأثير على جدول المستحقات.');
        }
    }

    // تحميل بنود الاستحقاقات لنوع 5
    var _benefitLoaded = false;
    function showLoadBenefitItems(){
        if(_benefitLoaded) return;
        var _s = $('#show_benefit_con');
        _s.html('<option value="">— جاري التحميل —</option>');
        get_data('<?= base_url("payment_req/payment_req/get_benefit_items_json") ?>', {}, function(data){
            var items = (typeof data === 'string') ? JSON.parse(data) : data;
            _s.html('<option value="">— اختر البند —</option>');
            for(var i=0; i<(items||[]).length; i++){
                _s.append('<option value="'+items[i].NO+'">'+items[i].NO+' - '+items[i].NAME+'</option>');
            }
            _benefitLoaded = true;
            $('#show_benefit_con').select2({placeholder: '— اختر البند —', allowClear: true, width: '100%'});
        }, 'json');
    }

    // ═══ معاينة الموظفين — bulk_preview ═══
    function nf(n){ return parseFloat(n||0).toLocaleString('en-US',{minimumFractionDigits:2,maximumFractionDigits:2}); }

    function showOnCalcMethodChange(){
        var cm = $('#show_calc_method_sel').val() || $('#calc_method').val();
        var rt = $('#req_type').val();
        $('#show_percent_grp').toggle(cm == '1');
        $('#calc_method').val(cm);
        // طريقة المبلغ تظهر لنوع 3 فقط لما cm=2 (مبلغ ثابت)
        if(rt == '3'){
            if(cm == '2'){
                $('#show_amount_mode_grp').show();
                if(!$('#show_amount_mode').val()) $('#show_amount_mode').val('same');
                showOnAmountModeChange();
            } else {
                $('#show_amount_mode_grp').hide();
                $('#show_amount_grp').toggle(false);
                $('#show_req_amount').val('').prop('required', false);
            }
        } else {
            $('#show_amount_grp').toggle(cm == '2');
        }
        _wizRebuildAmountsIfOpen();
    }

    function _wizRebuildAmountsIfOpen(){
        if($('#wiz_pick_amounts').is(':visible')){
            _wizPickedAmounts = {};
            wizBuildAmountsTable();
        }
    }

    // أنواع 3 (cm=2)، 4، 5: نفس المبلغ أو مبلغ مختلف
    function showOnAmountModeChange(){
        var mode = $('#show_amount_mode').val();
        var rt = $('#req_type').val();
        var cm = $('#calc_method').val();
        // الـ master amount (المبلغ الموحد) يظهر فقط في mode=same للأنواع المعنية
        var masterApplies = (rt == '3' && cm == '2') || rt == '4' || rt == '5';
        if(masterApplies && mode === 'same'){
            $('#show_amount_grp').show();
            $('#show_req_amount').prop('required', true);
        } else if(masterApplies && mode === 'diff'){
            $('#show_amount_grp').hide();
            $('#show_req_amount').val('').prop('required', false);
        }
        // "كل الموظفين" مخفية في diff (يدوي per-emp) ولنوع 5 دايماً
        var hideAllCard = (rt == '5') || (mode === 'diff' && (rt == '3' || rt == '4'));
        $('#wiz_choice_all_card').toggle(!hideAllCard);
        _wizRebuildAmountsIfOpen();
    }

    // أي تغيير بالمبلغ/النسبة → إعادة بناء الجدول
    $(document).on('change input', '#show_req_amount, #show_percent_input, #show_percent_sel', function(){
        _wizRebuildAmountsIfOpen();
    });

    function saveMaster(btn){
        if(!_wizValidate()) return;
        // نسخ pay_type — لنوع 5 من بند الاستحقاق
        if($('#req_type').val() == '5'){
            var bc = $('#show_benefit_con').val();
            // نضيف option لو مش موجود بالـ select
            if(!$('#pay_type option[value="'+bc+'"]').length){
                $('#pay_type').append('<option value="'+bc+'">'+bc+'</option>');
            }
            $('#pay_type').val(bc);
        }

        $(btn).prop('disabled',true).html('<i class="fa fa-spinner fa-spin"></i>');
        get_data(postUrl, $('#master_form').serialize(), function(data){
            $(btn).prop('disabled',false).html('<i class="fa fa-save"></i> حفظ');
            var str = String(data);
            if(str.match(/^\d+$/) && parseInt(str) > 0){
                success_msg('تم', 'تم إنشاء الطلب #' + str);
                get_to_link('<?= base_url("$MODULE_NAME/$TB_NAME/get/") ?>' + str);
            } else if(str === '1'){
                success_msg('تم','تم التحديث'); reload_Page();
            } else if(str.indexOf('1|') === 0){
                // تم التعديل مع تنبيه
                success_msg('تم', str.split('|')[1]);
                reload_Page();
            } else if(str.indexOf('2|') === 0){
                // تغيير النوع — الموظفين محذوفين
                danger_msg('تنبيه', str.split('|')[1]);
                reload_Page();
            } else {
                danger_msg('خطأ', str);
            }
        }, 'html');
    }

    function formatErrorList(msg) {
        if (!msg || msg == '1') return msg;
        var lines = msg.split('\n').filter(function(l){ return l.trim() !== ''; });
        if (lines.length <= 1) return msg;
        var html = '<div style="text-align:right;font-size:.85rem">';
        html += '<p class="fw-bold mb-2">' + lines[0] + '</p>';
        html += '<table class="table table-sm table-bordered mb-0" style="font-size:.8rem"><thead><tr><th>الموظف</th><th>السبب</th></tr></thead><tbody>';
        for (var i = 1; i < lines.length; i++) {
            var parts = lines[i].split(':');
            var emp = parts[0] ? parts[0].trim() : '';
            var reason = parts.slice(1).join(':').trim();
            if (emp) html += '<tr><td class="fw-bold">' + emp + '</td><td class="text-danger">' + reason + '</td></tr>';
        }
        html += '</tbody></table></div>';
        return html;
    }

    function doApprove(btn){
        if(isDoubleClicked($(btn))) return;
        if(!confirm('هل تريد اعتماد هذا الطلب؟')) return;
        get_data(approveUrl, {req_id: reqId}, function(data){
            if(data == '1'){ success_msg('تم','تم الاعتماد'); reload_Page(); }
            else { danger_msg('فشل الاعتماد', formatErrorList(data)); }
        },'html');
    }

    function doPay(btn){
        if(isDoubleClicked($(btn))) return;
        if(!confirm('هل تريد صرف هذا الطلب؟')) return;
        get_data(payUrl, {req_id: reqId}, function(data){
            if(data == '1'){ success_msg('تم','تم الصرف'); reload_Page(); }
            else { danger_msg('فشل الصرف', formatErrorList(data)); }
        },'html');
    }

    function exportDetailLimitFlag(){
        var rows = window._detailLimitRows || [];
        if(rows.length === 0){ danger_msg('تحذير','لا يوجد موظفين متأثرين بالحدود'); return; }
        var flagNames = {'MIN':'حد أدنى', 'MAX':'حد أعلى', 'CAP':'كامل المستحقات'};
        var data = [['#', 'رقم الموظف', 'اسم الموظف', 'المقر', 'مبلغ الصرف', 'الحالة']];
        var n = 0;
        rows.forEach(function(r){
            n++;
            data.push([
                n,
                parseInt(r.EMP_NO)||r.EMP_NO,
                r.EMP_NAME||'',
                r.BRANCH_NAME||'',
                parseFloat(r.REQ_AMOUNT||0),
                flagNames[r.LIMIT_FLAG] || ''
            ]);
        });
        var ws = XLSX.utils.aoa_to_sheet(data);
        var wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, 'متأثرين بالحدود');
        XLSX.writeFile(wb, 'متأثرين_بالحدود_طلب_' + (reqId||'') + '.xlsx');
    }

    function exportDetailExcel(){
        var rows = [];
        $('#detailTable tbody tr').each(function(){
            var tds = $(this).find('td');
            if(tds.length < 4) return;
            var empText = $(tds[2]).text().trim();
            var parts = empText.split(' - ');
            var empNo = parts[0] ? parts[0].trim() : '';
            var empName = parts.slice(1).join(' - ').trim();
            rows.push({
                n: $(tds[1]).text().trim(),
                emp_no: empNo,
                emp_name: empName,
                branch: $(tds[3]).text().trim(),
                amount: parseFloat($(tds[4]).text().replace(/,/g,'')) || 0,
                status: $(tds[5]).text().trim(),
                note: $(tds[6]) ? $(tds[6]).text().trim() : ''
            });
        });
        if(rows.length === 0){ danger_msg('تحذير','لا يوجد بيانات'); return; }
        var data = [['#', 'رقم الموظف', 'اسم الموظف', 'المقر', 'المبلغ', 'الحالة', 'ملاحظة']];
        for(var i=0; i<rows.length; i++){
            var r = rows[i];
            data.push([parseInt(r.n)||i+1, parseInt(r.emp_no)||r.emp_no, r.emp_name, r.branch, r.amount, r.status, r.note]);
        }
        var total = rows.reduce(function(s,r){ return s + r.amount; }, 0);
        data.push(['', '', '', 'الإجمالي', total, '', '']);
        var wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, XLSX.utils.aoa_to_sheet(data), 'الموظفين');
        XLSX.writeFile(wb, 'طلب_صرف_<?= $req_id_val ?>_موظفين.xlsx');
    }

    function doDelete(btn){
        if(isDoubleClicked($(btn))) return;
        if(!confirm('هل تريد حذف الطلب نهائياً مع كل الموظفين؟\n\nلا يمكن التراجع عن هذا الإجراء.')) return;
        get_data('<?= base_url("$MODULE_NAME/$TB_NAME/delete_request") ?>', {req_id: reqId}, function(resp){
            try {
                var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
                if(j.ok){ success_msg('تم', j.msg); get_to_link(backUrl); }
                else { danger_msg('خطأ', j.msg); }
            } catch(e){ danger_msg('خطأ', resp); }
        }, 'json');
    }

    function doUnapprove(btn){
        if(isDoubleClicked($(btn))) return;
        if(!confirm('سيتم إلغاء الاعتماد وإرجاع الطلب لحالة مسودة. هل تريد المتابعة؟')) return;
        var note = prompt('ملاحظة (اختياري):', '');
        if(note === null) return;
        get_data('<?= base_url("$MODULE_NAME/$TB_NAME/unapprove") ?>', {req_id: reqId, note: note || ''}, function(resp){
            try {
                var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
                if(j.ok){ success_msg('تم', j.msg); reload_Page(); }
                else { danger_msg('خطأ', j.msg); }
            } catch(e){ danger_msg('خطأ', resp); }
        }, 'json');
    }

    // ═══ قائمة الانتظار — إضافة موظفين ═══
    var _queue = [];
    var _defaultAddAmount = <?= isset($default_amt_val) && $default_amt_val !== '' ? $default_amt_val : "''" ?>;
    var _amountRequired = <?= isset($is_required) && $is_required ? 'true' : 'false' ?>;

    var _summaryUrl = '<?= base_url("$MODULE_NAME/$TB_NAME/public_get_summary") ?>';

    function _renderQueue(){
        var html = '';
        for(var i=0; i<_queue.length; i++){
            var q = _queue[i];
            html += '<tr>';
            html += '<td class="text-center text-muted">'+(i+1)+'</td>';
            html += '<td><strong>'+q.emp+'</strong> <span class="text-muted">- '+q.name+'</span></td>';
            var dispAmt = q.amount > 0 ? nf(q.amount) : (q.calcAmount ? nf(q.calcAmount) + ' <small class="text-muted">(تلقائي)</small>' : '<span class="text-muted">تلقائي</span>');
            html += '<td class="text-end">'+dispAmt+'</td>';
            <?php if ($req_type_val != 5): ?>
            html += '<td class="text-end" style="font-size:.78rem">';
            if(q.avail !== undefined){
                var clr = q.avail > 0 ? '#059669' : '#dc2626';
                html += '<span style="color:'+clr+';font-weight:700">'+nf(q.avail)+'</span>';
            } else {
                html += '<span class="text-muted">...</span>';
            }
            html += '</td>';
            <?php endif; ?>
            html += '<td class="text-muted">'+q.note+'</td>';
            html += '<td class="text-center"><button class="btn btn-sm btn-outline-danger p-0 px-1" onclick="removeFromQueue('+i+')"><i class="fa fa-times"></i></button></td>';
            html += '</tr>';
        }
        $('#queueBody').html(html);
        $('#queueSection').toggle(_queue.length > 0);
        $('#btnSubmitQueue').prop('disabled', _queue.length === 0);
        $('#queueBadge').text(_queue.length).toggle(_queue.length > 0);
    }

    function addToQueue(){
        var emp = $('#add_emp_no').val();
        if(!emp){ danger_msg('تحذير','أدخل رقم الموظف'); return; }

        if(_existingEmps[emp]){ danger_msg('تحذير','الموظف مضاف بالطلب'); return; }
        for(var i=0; i<_queue.length; i++){
            if(_queue[i].emp == emp){ danger_msg('تحذير','الموظف موجود بالقائمة'); return; }
        }
        // المبلغ إجباري حسب النوع
        if(_amountRequired && (!$('#add_amount').val() || parseFloat($('#add_amount').val()) <= 0)){
            danger_msg('تحذير','أدخل المبلغ — نوع الطلب يتطلب مبلغ محدد'); return;
        }

        var name = $('#add_emp_name').val() || '';
        var idx = _queue.length;
        _queue.push({
            emp: emp,
            name: name,
            amount: parseFloat($('#add_amount').val()) || 0,
            note: $('#add_note').val() || '',
            avail: undefined
        });
        $('#add_emp_no').val('');
        $('#add_emp_name').val('');
        $('#add_amount').val(_defaultAddAmount);
        $('#add_note').val('');
        $('#add_preview_info').hide();
        $('#add_emp_no').focus();
        _renderQueue();

        // جلب المبلغ المحتسب والمتاح حسب نوع الطلب
        get_data(detailPreviewUrl, {req_id: reqId, emp_no: emp, req_amount: _queue[idx].amount}, function(resp){
            try {
                var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
                if(j.ok){
                    _queue[idx].avail = parseFloat(j.dues_avail || 0);
                    if(_queue[idx].amount === 0) _queue[idx].calcAmount = parseFloat(j.calc_amount || 0);
                    if(j.emp_name && !_queue[idx].name) _queue[idx].name = j.emp_name;
                    _renderQueue();
                }
            } catch(e){}
        }, 'json');
    }

    // قائمة الموظفين المضافين (للفحص السريع)
    var _existingEmps = {};
    function _buildExistingEmps(){
        _existingEmps = {};
        $('#detailBody tr .fw-bold').each(function(){
            var t = $(this).text().trim();
            if(t) _existingEmps[t] = true;
        });
    }
    _buildExistingEmps();

    function removeFromQueue(idx){
        _queue.splice(idx, 1);
        _renderQueue();
    }

    function submitQueue(){
        if(_queue.length === 0) return;
        $('#btnSubmitQueue').prop('disabled',true).html('<i class="fa fa-spinner fa-spin"></i> جاري...');
        $('#addProgress').show();
        $('#addResults').hide();

        var total = _queue.length, done = 0, ok = 0, fails = [];

        function _next(){
            if(done >= total){
                $('#btnSubmitQueue').prop('disabled',false).html('<i class="fa fa-save"></i> حفظ الكل <span class="badge bg-white text-primary ms-1" style="display:none">0</span>');
                $('#addProgress').hide();
                var html = '<div class="alert py-2 mb-0 '+(fails.length > 0 ? 'alert-warning' : 'alert-success')+'" style="font-size:.82rem;border-radius:8px">';
                html += '<strong>تم إضافة '+ok+' من '+total+' موظف</strong>';
                if(fails.length > 0){
                    html += '<div class="mt-1" style="font-size:.78rem">';
                    for(var i=0; i<fails.length; i++) html += '<div class="text-danger">'+fails[i].emp+': '+fails[i].msg+'</div>';
                    html += '</div>';
                }
                html += '</div>';
                $('#addResults').html(html).show();
                setTimeout(function(){ $('#addResults').fadeOut(); }, fails.length > 0 ? 10000 : 3000);
                _queue = [];
                _renderQueue();
                // 🆕 soft refresh لتحديث جدول الموظفين + tab المستفيدين + الإحصائيات
                if (success > 0) softRefreshAll();
                return;
            }
            var q = _queue[done];
            var pct = Math.round(((done+1)/total)*100);
            $('#addProgressBar').css('width', pct+'%').text(pct+'%');
            $('#addProgressText').text('جاري إضافة '+q.emp+' ('+( done+1)+'/'+total+')');

            get_data(detailAddUrl, {req_id: reqId, emp_no: q.emp, req_amount: q.amount, note: q.note}, function(resp){
                try {
                    var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
                    if(j.ok) ok++;
                    else fails.push({emp: q.emp+' - '+q.name, msg: j.msg});
                } catch(e){ fails.push({emp: q.emp, msg: 'خطأ'}); }
                done++;
                _next();
            }, 'json');
        }
        _next();
    }


    function deleteDetail(detailId){
        if(!confirm('حذف/إلغاء اعتماد هذا الموظف من الطلب؟')) return;
        get_data(detailDelUrl, {detail_id: detailId}, function(resp){
            try {
                var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
                if(j.ok){
                    success_msg('تم','تم التحديث');
                    refreshDetails(); // تحديث ديناميكي للجدول + البطاقات + الحالة + السجل
                }
                else { danger_msg('خطأ', j.msg); }
            } catch(e){ danger_msg('خطأ', e.message); }
        }, 'json');
    }

    // ════════════════════════════════════════════════════════════
    // OVERRIDE MODAL — تعديل توزيع الصرف لسطر معين
    // ════════════════════════════════════════════════════════════
    var ovrSetUrl   = "<?= base_url('payment_req/payment_req/public_detail_set_override') ?>";
    var ovrAccUrl   = "<?= base_url('payment_req/payment_req/public_emp_accounts_json') ?>";
    var _ovrAccCache = {};
    var _ovrSelectedAcc = null;
    // 🆕 flag — يصير true بعد أي تعديل inline سريع، ويسبّب refresh عند الانتقال لتبويب المستفيدين
    var _recipientsStale = false;

    // 🆕 تحديث ذكي بدون reload — يجلب الـ HTML الجديد للصفحة ويستبدل tab المستفيدين + جدول الموظفين
    // المستخدم لن يلاحظ شيء (لا وميض ولا فقدان scroll)
    function softRefreshAll(callback){
        $.get(window.location.href, function(html){
            try {
                var $doc = $('<div>').html(html);

                // استبدال tab المستفيدين بكل ما فيه (cards + table)
                var $newRecipients = $doc.find('#tab-recipients');
                if ($newRecipients.length) {
                    $('#tab-recipients').html($newRecipients.html());
                }

                // 🆕 استبدال tab الموظفين أيضاً (فيه stats cards علوية + الجدول)
                var $newEmps = $doc.find('#tab-emps');
                if ($newEmps.length) {
                    // نحتفظ بالـ scroll الحالي
                    var sc = $(window).scrollTop();
                    $('#tab-emps').html($newEmps.html());
                    $(window).scrollTop(sc);
                }

                // re-init tooltips
                if (typeof initTooltips === 'function') initTooltips();
                _recipientsStale = false;
                if (typeof callback === 'function') callback();
            } catch(e){
                console.error('softRefreshAll error:', e);
                // fallback
                if (typeof callback === 'function') callback();
            }
        }).fail(function(){
            console.error('softRefreshAll: AJAX failed');
        });
    }

    // 🆕 تبديل سريع للـ override بدون modal (default / bank / wallet)
    function quickSetOverride(detailId, mode){
        if (!detailId) return;
        var pt = '', acc = '';
        if (mode === 'bank')        { pt = 1; }
        else if (mode === 'wallet') { pt = 2; }
        // 'default' → كلاهما فاضي (NULL)

        // نستخدم $.ajax مباشرة عشان نعالج الـ HTTP errors بدقة
        $.ajax({
            url: ovrSetUrl,
            method: 'POST',
            data: { detail_id: detailId, provider_type: pt, acc_id: acc },
            dataType: 'json'
        }).done(function(j){
            if (j && j.ok) {
                refreshDetails();
                _recipientsStale = true;
            } else {
                danger_msg('خطأ', (j && j.msg) ? j.msg : 'فشل التحديث');
            }
        }).fail(function(xhr){
            var msg = 'فشل الاتصال بالسيرفر';
            if (xhr && xhr.status === 404) msg = 'الـ URL غير موجود — تحقق من الصلاحيات';
            else if (xhr && xhr.status === 403) msg = 'ليس لديك صلاحية';
            else if (xhr && xhr.status >= 500)  msg = 'خطأ في السيرفر (' + xhr.status + ')';
            danger_msg('خطأ', msg);
        });
    }

    // 🆕 لما يضغط المستخدم على تبويب المستفيدين بعد تعديلات inline → soft refresh
    $(document).on('show.bs.tab', '#tab-recipients-btn', function(){
        if (_recipientsStale) {
            softRefreshAll();
        }
    });

    // 🆕 Delegated event handlers للأزرار المُولّدة من JS (data-attributes بدل inline onclick)
    $(document).on('click', '[data-action="quick-ovr"]', function(){
        var did  = parseInt($(this).data('detail'));
        var mode = $(this).data('mode');
        if (did && mode) quickSetOverride(did, mode);
    });
    $(document).on('click', '[data-action="open-ovr"]', function(){
        var $b   = $(this);
        var did  = parseInt($b.data('detail'));
        var emp  = parseInt($b.data('emp'));
        var name = String($b.data('name') || '');
        var amt  = parseFloat($b.data('amt')) || 0;
        var ptR  = $b.data('pt');
        var acR  = $b.data('acc');
        var pt   = (ptR === '' || ptR === null || ptR === undefined) ? null : parseInt(ptR);
        var ac   = (acR === '' || acR === null || acR === undefined) ? null : parseInt(acR);
        if (did) openOverrideModal(did, emp, name, amt, pt, ac);
    });
    $(document).on('click', '[data-action="del-detail"]', function(){
        var did = parseInt($(this).data('detail'));
        if (did && typeof deleteDetail === 'function') deleteDetail(did);
    });

    // 🆕 تطبيق override جماعي على المحدّدين بـ checkbox
    function bulkSetOverride(mode){
        var ids = [];
        $('.dtl-chk:checked').each(function(){
            var ds = parseInt($(this).data('status'));
            if (ds === 0 || ds === 1) ids.push($(this).val());
        });
        if (ids.length === 0) {
            warning_msg('تنبيه', 'حدد موظفاً واحداً على الأقل (مسودة أو معتمد)');
            return;
        }
        var modeLabel = mode === 'default' ? 'افتراضي' : (mode === 'bank' ? 'بنك فقط' : 'محفظة فقط');
        if (!confirm('تطبيق "' + modeLabel + '" على ' + ids.length + ' موظف؟')) return;

        var pt = '', acc = '';
        if (mode === 'bank')        { pt = 1; }
        else if (mode === 'wallet') { pt = 2; }

        var done = 0, fails = 0;
        var doNext = function(){
            if (done + fails >= ids.length) {
                var msg = 'تم تحديث ' + done + ' موظف' + (fails > 0 ? ' (فشل ' + fails + ')' : '');
                (done > 0 ? success_msg : danger_msg)('انتهى', msg);
                // 🆕 soft refresh لتحديث جدول الموظفين + tab المستفيدين + الإحصائيات
                if (done > 0) softRefreshAll();
                return;
            }
            var id = ids[done + fails];
            get_data(ovrSetUrl, {detail_id: id, provider_type: pt, acc_id: acc}, function(resp){
                try {
                    var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
                    if (j.ok) done++; else fails++;
                } catch(e){ fails++; }
                doNext();
            }, 'json');
        };
        doNext();
    }

    function openOverrideModal(detailId, empNo, empName, amount, currentPt, currentAcc){
        // 🆕 reset كامل لحالة الـ modal قبل العرض (تفادي backdrop عالق + selection قديم)
        var modalEl = document.getElementById('overrideModal');
        // dispose أي instance سابق + تنظيف backdrop عالق
        var prev = bootstrap.Modal.getInstance(modalEl);
        if (prev) { try { prev.dispose(); } catch(e){} }
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open').css('padding-right', '').css('overflow', '');

        $('#ovr_detail_id').val(detailId);
        $('#ovr_emp_no').val(empNo);
        $('#ovr_emp_label').text(empNo + ' — ' + (empName || ''));
        $('#ovr_amount_label').text(parseFloat(amount || 0).toLocaleString('en-US',{minimumFractionDigits:2,maximumFractionDigits:2}));
        _ovrSelectedAcc = currentAcc || null;

        // إخفاء قسم الحسابات حتى المستخدم يختار "specific"
        $('#ovr_specific_box').hide();

        // اختر الـ radio حسب الحالة الحالية
        var mode = 'default';
        if (currentAcc)              mode = 'specific';
        else if (currentPt === 1)    mode = 'bank';
        else if (currentPt === 2)    mode = 'wallet';
        $('input[name="ovr_mode"][value="' + mode + '"]').prop('checked', true).trigger('change');

        var modal = new bootstrap.Modal(modalEl);
        modal.show();
    }

    $(document).on('change', 'input[name="ovr_mode"]', function(){
        var mode = $(this).val();
        if (mode === 'specific') {
            $('#ovr_specific_box').show();
            _ovrLoadAccounts();
        } else {
            $('#ovr_specific_box').hide();
        }
    });

    function _ovrLoadAccounts(){
        var emp = $('#ovr_emp_no').val();
        if (!emp) return;

        // عرض الـ list من الـ cache لو موجود
        if (_ovrAccCache[emp]) { _ovrRenderAccs(_ovrAccCache[emp]); return; }

        $('#ovr_acc_loading').show();
        $('#ovr_acc_list').hide();
        $('#ovr_acc_empty').hide();

        get_data(ovrAccUrl, {emp_no: emp}, function(resp){
            try {
                var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
                var rows = (j && j.data) ? j.data : [];
                _ovrAccCache[emp] = rows;
                _ovrRenderAccs(rows);
            } catch(e){
                $('#ovr_acc_loading').hide();
                $('#ovr_acc_empty').show().text('خطأ في التحميل: ' + e.message);
            }
        }, 'json');
    }

    function _ovrRenderAccs(rows){
        $('#ovr_acc_loading').hide();
        if (!rows || rows.length === 0) {
            $('#ovr_acc_empty').show();
            $('#ovr_acc_list').hide();
            return;
        }
        var html = '';
        rows.forEach(function(a){
            var isWallet = parseInt(a.PROVIDER_TYPE) === 2;
            var isDef    = parseInt(a.IS_DEFAULT) === 1;
            var icon     = isWallet ? 'fa-mobile' : 'fa-bank';
            var iconCol  = isWallet ? '#6d28d9' : '#1e40af';
            var accNum   = a.ACCOUNT_NO || a.WALLET_NUMBER || '—';
            var sel = (_ovrSelectedAcc && parseInt(_ovrSelectedAcc) === parseInt(a.ACC_ID)) ? ' selected' : '';

            html += '<div class="ovr-acc-item' + sel + '" data-acc="' + a.ACC_ID + '">';
            html += '<i class="fa ' + icon + ' ai-icon" style="color:' + iconCol + '"></i>';
            html += '<div class="ai-info">';
            html += '<div class="ai-prov">' + (a.PROVIDER_NAME || '') +
                    (isDef ? ' <span class="ai-tag tag-default">⭐ افتراضي</span>' : '') + '</div>';
            html += '<div class="ai-line">#' + accNum + (a.BANK_BRANCH_NAME ? ' — ' + a.BANK_BRANCH_NAME : '') + '</div>';
            if (a.OWNER_NAME) {
                html += '<div class="ai-owner"><i class="fa fa-user-o"></i> ' + a.OWNER_NAME +
                        (a.REL_NAME ? ' (' + a.REL_NAME + ')' : '') + '</div>';
            }
            html += '</div>';
            html += '</div>';
        });
        $('#ovr_acc_list').html(html).show();
        $('#ovr_acc_empty').hide();
    }

    $(document).on('click', '.ovr-acc-item', function(){
        $('.ovr-acc-item').removeClass('selected');
        $(this).addClass('selected');
        _ovrSelectedAcc = $(this).data('acc');
    });

    $(document).on('click', '#ovrSaveBtn', function(){
        var detailId = $('#ovr_detail_id').val();
        if (!detailId) return;
        var mode = $('input[name="ovr_mode"]:checked').val();
        var pt = '', acc = '';
        if (mode === 'bank')          { pt = 1; }
        else if (mode === 'wallet')   { pt = 2; }
        else if (mode === 'specific') {
            if (!_ovrSelectedAcc) { danger_msg('خطأ','اختر حساب من القائمة أولاً'); return; }
            acc = _ovrSelectedAcc;
        }

        get_data(ovrSetUrl, {detail_id: detailId, provider_type: pt, acc_id: acc}, function(resp){
            try {
                var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
                if (j.ok) {
                    success_msg('تم','تم تحديث التوزيع');
                    // 🆕 إغلاق + dispose + تنظيف backdrop عشان modal تاني يفتح
                    var modalEl = document.getElementById('overrideModal');
                    var inst = bootstrap.Modal.getInstance(modalEl);
                    if (inst) {
                        inst.hide();
                        // بعد ما الـ animation تخلص، dispose
                        setTimeout(function(){
                            try { inst.dispose(); } catch(e){}
                            $('.modal-backdrop').remove();
                            $('body').removeClass('modal-open').css('padding-right', '').css('overflow', '');
                        }, 300);
                    }
                    // reset internal state
                    _ovrSelectedAcc = null;
                    // 🆕 soft refresh — بدون reload للصفحة
                    softRefreshAll();
                } else {
                    danger_msg('خطأ', j.msg);
                }
            } catch(e){ danger_msg('خطأ', e.message); }
        }, 'json');
    });

    // خرائط مستخدمة في تحديث الحالة والسجل ديناميكياً
    var _statusBarColors = {
        0: {bg:'#fef3c7', color:'#92400e'},
        1: {bg:'#dbeafe', color:'#1e40af'},
        2: {bg:'#d1fae5', color:'#065f46'},
        3: {bg:'#e0e7ff', color:'#3730a3'},
        4: {bg:'#ccfbf1', color:'#0f766e'},
        9: {bg:'#fee2e2', color:'#991b1b'}
    };
    var _actionLabels = {
        'CREATE':['إنشاء الطلب','fa-plus-circle','#059669'],
        'UPDATE':['تعديل','fa-pencil','#d97706'],
        'UPDATE_TYPE_CHANGE':['تغيير النوع','fa-exchange','#dc2626'],
        'ADD_EMP':['إضافة موظف','fa-user-plus','#2563eb'],
        'BULK_ADD':['إضافة جماعية','fa-users','#2563eb'],
        'REMOVE_EMP':['حذف موظف','fa-user-times','#dc2626'],
        'APPROVE':['اعتماد','fa-check-circle','#059669'],
        'APPROVE_ALL':['اعتماد الكل','fa-check-circle','#059669'],
        'UNAPPROVE':['إلغاء اعتماد','fa-undo','#d97706'],
        'UNAPPROVE_EMP':['إلغاء اعتماد موظف','fa-undo','#d97706'],
        'PAY':['صرف','fa-money','#059669'],
        'UNPAY_EMP':['عكس صرف موظف','fa-reply','#dc2626'],
        'CANCEL':['إلغاء','fa-ban','#991b1b'],
        'DELETE':['حذف','fa-trash','#991b1b']
    };
    var _perEmpActions = ['ADD_EMP','REMOVE_EMP','UNAPPROVE_EMP','UNPAY_EMP'];

    function _updateMasterStatus(master){
        if(!master) return;
        var st = parseInt(master.STATUS || 0);
        var name = master.STATUS_NAME || '';
        var bar = document.getElementById('masterStatusBar');
        if(bar){
            bar.className = 'pr2-status s' + st;
            var nameEl = document.getElementById('masterStatusName');
            if(nameEl) nameEl.textContent = name;
        }
    }

    function _renderLogRows(logs){
        if(!logs) return;
        var showEmp = $('#logShowEmpRows').is(':checked');
        var mainCount = 0, empCount = 0, html = '';
        for(var i=0; i<logs.length; i++){
            var lg = logs[i];
            var act = lg.ACTION || '';
            var al = _actionLabels[act] || [act, 'fa-circle-o', '#64748b'];
            var isEmpRow = _perEmpActions.indexOf(act) >= 0;
            if(isEmpRow) empCount++; else mainCount++;
            var rowClass = isEmpRow ? 'log-emp-row' : 'log-main-row';
            var rowStyle = (isEmpRow && !showEmp) ? ' style="display:none"' : '';
            var noteEsc = (lg.NOTE || '').replace(/"/g,'&quot;').replace(/</g,'&lt;');
            html += '<tr class="'+rowClass+'"'+rowStyle+'>';
            html += '<td class="text-center text-muted">'+(i+1)+'</td>';
            html += '<td><span style="color:'+al[2]+';font-weight:600"><i class="fa '+al[1]+' me-1"></i>'+al[0]+'</span></td>';
            html += '<td>'+(lg.OLD_STATUS_NAME || '—')+'</td>';
            html += '<td>'+(lg.NEW_STATUS_NAME || '—')+'</td>';
            html += '<td>'+(lg.ACTION_USER_NAME || '')+'</td>';
            html += '<td style="font-size:.75rem;direction:ltr">'+(lg.ACTION_DATE || '')+'</td>';
            html += '<td class="text-center">'+(lg.EMP_COUNT || '')+'</td>';
            html += '<td class="text-end">'+(lg.TOTAL_AMOUNT ? nf(parseFloat(lg.TOTAL_AMOUNT)) : '')+'</td>';
            html += '<td class="text-muted" style="font-size:.72rem;max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap" title="'+noteEsc+'">'+(lg.NOTE || '')+'</td>';
            html += '</tr>';
        }
        $('#logTableBody').html(html);
        $('#logMainCount').text(mainCount);
        $('#logEmpCount').text(empCount);
        if(empCount > 0){ $('#logEmpRowsControl').show(); $('#logEmpCountWrap').show(); }
        else { $('#logEmpRowsControl').hide(); $('#logEmpCountWrap').hide(); }
    }

    var _statusBadges = {0:['مسودة','#fef3c7','#92400e'],1:['معتمد','#dbeafe','#1e40af'],2:['منفّذ للصرف','#d1fae5','#065f46'],9:['ملغى','#fee2e2','#991b1b']};
    function _stBadge(st){ var b=_statusBadges[st]||['—','#f1f5f9','#475569']; return '<span style="background:'+b[1]+';color:'+b[2]+';padding:2px 8px;border-radius:6px;font-size:.72rem;font-weight:600">'+b[0]+'</span>'; }

    var _detailIsType2 = <?= $is_type_2 ? 'true' : 'false' ?>;
    var _detailCanPartial = <?= $can_partial ? 'true' : 'false' ?>;
    var _detailCanAct = <?= ($can_add || $can_partial) ? 'true' : 'false' ?>;

    function refreshDetails(){
        get_data(detailLstUrl, {req_id: reqId}, function(resp){
            try {
                var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
                if(!j.ok) return;
                var rows = j.data || [];
                var html = ''; var total = 0;
                for(var i=0; i<rows.length; i++){
                    var d = rows[i];
                    var amt = parseFloat(d.REQ_AMOUNT||0);
                    var ds = parseInt(d.DETAIL_STATUS||0);
                    total += amt;
                    // خريطة زر الإجراء حسب الحالة (نفس منطق PHP)
                    var actIcon = '', actTitle = '';
                    if(ds === 0){ actIcon = 'fa-trash'; actTitle = 'حذف'; }
                    else if(ds === 1){ actIcon = 'fa-undo'; actTitle = 'إلغاء اعتماد'; }
                    else if(ds === 2){ actIcon = 'fa-reply'; actTitle = 'عكس صرف'; }

                    // 🆕 Override badge — يظهر دائماً (override أو افتراضي)
                    var ovrPt = d.OVERRIDE_PROVIDER_TYPE;
                    var ovrAcc = d.OVERRIDE_ACC_ID;
                    var ovrLabel = d.OVERRIDE_LABEL || '';
                    var hasOvr = (ovrPt !== null && ovrPt !== undefined && ovrPt !== '') || ovrAcc;
                    var ovrBadge = '';
                    if (hasOvr) {
                        var ovrBg = ovrAcc ? '#fef3c7' : (parseInt(ovrPt) === 2 ? '#f5f3ff' : '#dbeafe');
                        var ovrFg = ovrAcc ? '#92400e' : (parseInt(ovrPt) === 2 ? '#6d28d9' : '#1e40af');
                        var ovrIc = ovrAcc ? 'fa-bullseye' : (parseInt(ovrPt) === 2 ? 'fa-mobile' : 'fa-bank');
                        ovrBadge = ' <span class="b-tag" title="' + (ovrLabel||'').replace(/"/g,'&quot;') +
                                   '" style="background:' + ovrBg + ';color:' + ovrFg + ';font-weight:700">' +
                                   '<i class="fa ' + ovrIc + '"></i> ' + (ovrLabel||'') + '</span>';
                    } else {
                        // 🆕 badge "افتراضي" دائم لما ما فيه override
                        ovrBadge = ' <span class="b-tag" title="التوزيع الافتراضي حسب حسابات الموظف" ' +
                                   'style="background:#f1f5f9;color:#475569;font-weight:700;border:1px dashed #cbd5e1">' +
                                   '<i class="fa fa-cog"></i> افتراضي</span>';
                    }

                    // 🆕 Status badge + row background
                    var dispSt = parseInt(d.EMP_DISPLAY_STATUS == null ? 1 : d.EMP_DISPLAY_STATUS);
                    var stBadge = ''; var rowBg = '';
                    if (dispSt === 2) {
                        stBadge = ' <span class="b-tag" style="background:#fee2e2;color:#991b1b;font-weight:700" title="متوفى"><i class="fa fa-times-circle"></i> متوفى</span>';
                        rowBg = ' style="background:#fee2e2"';
                    } else if (dispSt === 4) {
                        stBadge = ' <span class="b-tag" style="background:#fef3c7;color:#92400e;font-weight:700" title="حساب مغلق"><i class="fa fa-ban"></i> حساب مغلق</span>';
                        rowBg = ' style="background:#fef3c7"';
                    } else if (dispSt === 0) {
                        stBadge = ' <span class="b-tag" style="background:#ffedd5;color:#9a3412;font-weight:700" title="متقاعد"><i class="fa fa-clock-o"></i> متقاعد</span>';
                        rowBg = ' style="background:#ffedd5"';
                    }

                    html += '<tr' + rowBg + '>';
                    if(_detailCanPartial) html += '<td class="text-center"><input type="checkbox" class="dtl-chk" value="'+d.DETAIL_ID+'" data-status="'+ds+'"></td>';
                    html += '<td class="text-center text-muted">' + (i+1) + '</td>';
                    html += '<td><span class="fw-bold">' + d.EMP_NO + '</span> <span class="text-muted">- ' + (d.EMP_NAME||'') + '</span>' + stBadge + ovrBadge + '</td>';
                    html += '<td>' + (d.BRANCH_NAME||'') + '</td>';
                    if(_detailIsType2) html += '<td class="text-end" style="color:#475569">' + nf(parseFloat(d.NET_SALARY_CALC||0)) + '</td>';
                    var lf = d.LIMIT_FLAG || '';
                    var lfBadge = '';
                    if(lf === 'MIN') lfBadge = ' <span style="background:#fef3c7;color:#92400e;padding:1px 5px;border-radius:4px;font-size:.65rem;font-weight:600">حد أدنى</span>';
                    else if(lf === 'MAX') lfBadge = ' <span style="background:#fee2e2;color:#991b1b;padding:1px 5px;border-radius:4px;font-size:.65rem;font-weight:600">حد أعلى</span>';
                    else if(lf === 'CAP') lfBadge = ' <span style="background:#dbeafe;color:#1e40af;padding:1px 5px;border-radius:4px;font-size:.65rem;font-weight:600">كامل</span>';
                    html += '<td class="text-end amt"' + (_detailIsType2 ? ' style="color:#1e40af;font-weight:700"' : '') + '>' + nf(amt) + lfBadge + '</td>';
                    if(_detailIsType2) html += '<td class="text-end" style="color:#6b21a8">' + nf(parseFloat(d.ACCRUED_323_CALC||0)) + '</td>';
                    html += '<td>' + _stBadge(ds) + '</td>';
                    html += '<td class="text-muted" style="font-size:.75rem">' + (d.NOTE||'') + '</td>';
                    if(_detailCanAct){
                        if(ds !== 9 && actIcon){
                            var actHtml = '<td class="text-center actions-cell">';
                            if (ds === 0 || ds === 1) {
                                // 🆕 Inline toggle group + data-attributes (delegated events)
                                // نتجنّب inline onclick مع JSON عشان لا يكسر الـ HTML
                                var ptArg = (ovrPt !== null && ovrPt !== undefined && ovrPt !== '') ? parseInt(ovrPt) : '';
                                var accArg = ovrAcc ? parseInt(ovrAcc) : '';
                                var isDef = !hasOvr;
                                var isBnk = (parseInt(ovrPt) === 1 && !ovrAcc);
                                var isWlt = (parseInt(ovrPt) === 2 && !ovrAcc);
                                var isSpc = !!ovrAcc;
                                var nameEsc = String(d.EMP_NAME || '').replace(/"/g, '&quot;').replace(/'/g, '&#39;');
                                actHtml += '<div class="ovr-toggle btn-group btn-group-sm me-1" role="group" data-detail-id="' + d.DETAIL_ID + '">';
                                actHtml += '<button type="button" class="btn btn-sm ' + (isDef ? 'btn-secondary' : 'btn-outline-secondary') + ' ovr-tog" data-action="quick-ovr" data-detail="' + d.DETAIL_ID + '" data-mode="default" title="افتراضي"><i class="fa fa-cog"></i></button>';
                                actHtml += '<button type="button" class="btn btn-sm ' + (isBnk ? 'btn-primary'   : 'btn-outline-primary')   + ' ovr-tog" data-action="quick-ovr" data-detail="' + d.DETAIL_ID + '" data-mode="bank" title="بنك فقط"><i class="fa fa-bank"></i></button>';
                                actHtml += '<button type="button" class="btn btn-sm ' + (isWlt ? 'btn-info text-white' : 'btn-outline-info') + ' ovr-tog" data-action="quick-ovr" data-detail="' + d.DETAIL_ID + '" data-mode="wallet" title="محفظة فقط"><i class="fa fa-mobile"></i></button>';
                                actHtml += '</div>';
                                // 🆕 زر "تعديل التوزيع" مفصول — يفتح المودال
                                actHtml += '<button type="button" class="btn btn-sm ' + (isSpc ? 'btn-warning' : 'btn-outline-warning') + ' me-1" data-action="open-ovr" data-detail="' + d.DETAIL_ID + '" data-emp="' + d.EMP_NO + '" data-name="' + nameEsc + '" data-amt="' + amt + '" data-pt="' + ptArg + '" data-acc="' + accArg + '" title="تعديل التوزيع — افتح نافذة الخيارات الكاملة"><i class="fa fa-random"></i> تعديل</button>';
                            }
                            actHtml += '<button class="btn btn-sm btn-outline-danger" data-action="del-detail" data-detail="' + d.DETAIL_ID + '" title="' + actTitle + '"><i class="fa ' + actIcon + '"></i></button>';
                            actHtml += '</td>';
                            html += actHtml;
                        } else {
                            html += '<td class="text-center"></td>';
                        }
                    }
                    html += '</tr>';
                }
                $('#detailBody').html(html);
                $('#detailFootTotal').text(nf(total));
                // تفضيل قيم السيرفر لو متوفرة (تستبعد الملغيين)
                var srvCnt = (j.master && j.master.EMP_COUNT != null) ? j.master.EMP_COUNT : rows.length;
                var srvTot = (j.master && j.master.TOTAL_AMOUNT != null) ? j.master.TOTAL_AMOUNT : total;
                $('#totalAmountVal').text(nf(srvTot));
                $('#empCountVal').text(srvCnt);

                // تحديث شريط حالة الماستر + سجل العمليات (بدون reload)
                _updateMasterStatus(j.master);
                _renderLogRows(j.logs);

                // تحديث بطاقات "ملخص الرواتب للشهر" (لنوع 2 — مبلغ الصرف + سيُرحّل كمستحقات + المتبقي)
                var totalAccrued323 = 0;
                for(var ai=0; ai<rows.length; ai++){
                    if(parseInt(rows[ai].DETAIL_STATUS||0) !== 9){
                        totalAccrued323 += parseFloat(rows[ai].ACCRUED_323_CALC || 0);
                    }
                }
                if(typeof _renderWalletSummary === 'function'){
                    _renderWalletSummary(srvTot, totalAccrued323);
                }

                // ملخص الحدود
                var cntMin=0, cntMax=0, cntCap=0, cntNormal=0;
                window._detailLimitRows = [];
                for(var j=0; j<rows.length; j++){
                    var lf = rows[j].LIMIT_FLAG || '';
                    if(lf === 'MIN') cntMin++;
                    else if(lf === 'MAX') cntMax++;
                    else if(lf === 'CAP') cntCap++;
                    else cntNormal++;
                    if(lf) window._detailLimitRows.push(rows[j]);
                }
                if(cntMin > 0 || cntMax > 0 || cntCap > 0){
                    var sh = '<div style="background:#f8fafc;border-radius:8px;padding:.5rem .75rem;margin-top:.5rem;font-size:.78rem">';
                    sh += '<strong><i class="fa fa-bar-chart"></i></strong> ';
                    if(cntNormal > 0) sh += '<span style="color:#059669">'+cntNormal+' ضمن الحدود</span> &nbsp; ';
                    if(cntMin > 0) sh += '<span style="background:#fef3c7;color:#92400e;padding:1px 5px;border-radius:4px">'+cntMin+' حد أدنى</span> &nbsp; ';
                    if(cntMax > 0) sh += '<span style="background:#fee2e2;color:#991b1b;padding:1px 5px;border-radius:4px">'+cntMax+' حد أعلى</span> &nbsp; ';
                    if(cntCap > 0) sh += '<span style="background:#dbeafe;color:#1e40af;padding:1px 5px;border-radius:4px">'+cntCap+' كامل</span> &nbsp; ';
                    sh += '<button class="btn btn-outline-secondary btn-sm" onclick="exportDetailLimitFlag()" style="font-size:.68rem"><i class="fa fa-file-excel-o"></i> تصدير المتأثرين</button>';
                    sh += '</div>';
                    $('#detailLimitSummary').html(sh);
                } else {
                    $('#detailLimitSummary').html('');
                }
                // تحديث قائمة الموظفين المضافين
                _buildExistingEmps();
            } catch(e){}
        }, 'json');
    }

    function toggleAllChecks(el){ $('.dtl-chk').prop('checked', el.checked); }

    function _getSelectedIds(filterStatus){
        var ids = [];
        $('.dtl-chk:checked').each(function(){
            if(filterStatus === undefined || parseInt($(this).data('status')) === filterStatus) ids.push($(this).val());
        });
        return ids;
    }

    function doPartialApprove(){
        var ids = _getSelectedIds(0);
        if(ids.length === 0){ danger_msg('تحذير','حدد موظفين بحالة مسودة للاعتماد'); return; }
        if(!confirm('اعتماد ' + ids.length + ' موظف؟')) return;
        get_data('<?= $detail_approve_url ?>', {detail_ids: ids.join(',')}, function(resp){
            var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
            if(j.ok){ success_msg('تم', j.msg || 'تم الاعتماد'); reload_Page(); }
            else { danger_msg('فشل', formatErrorList(j.msg)); }
        }, 'json');
    }

    function doPartialPay(){
        var ids = _getSelectedIds(1);
        if(ids.length === 0){ danger_msg('تحذير','حدد موظفين معتمدين للصرف'); return; }
        if(!confirm('صرف ' + ids.length + ' موظف؟')) return;
        get_data('<?= $detail_pay_url ?>', {detail_ids: ids.join(',')}, function(resp){
            var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
            if(j.ok){ success_msg('تم', j.msg || 'تم الصرف'); reload_Page(); }
            else { danger_msg('فشل', formatErrorList(j.msg)); }
        }, 'json');
    }

    // ═══ ملخص الرواتب لنوع 2 (صفحة العرض) — دالة قابلة لإعادة الاستدعاء ═══
    <?php if (!$isCreate && $HaveRs && (int)($rs['REQ_TYPE'] ?? 0) == 2): ?>
    var _walletMonth = '<?= $rs['THE_MONTH'] ?? '' ?>';

    // يُحتسب مجموع ACCRUED_323 للطلب ذاته — يتحدّث مع كل refreshDetails
    function _renderWalletSummary(reqAmount, totalAccrued323, directBase){
        if (!_walletMonth) return;
        jQuery.post(salCheckUrl, { month: _walletMonth }, function(resp) {
            var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
            if (!j.calculated) return;
            // ✅ base = الصافي من صفوف التفاصيل مباشرة (= مجموع عمود "الصافي" في الجدول)
            // directBase تأتي من PHP محسوبة من $detail_rows.NET_SALARY_CALC — مصدر موثوق
            // (لا تعتمد على procedure recompile)
            var base = parseFloat(directBase) || parseFloat(j.total_323) || parseFloat(j.total_net) || 0;
            // المُستهلك من الصافي = مبلغ الصرف (نقد) + سيُرحّل (مستحقات) لهذا الطلب
            var consumed = parseFloat(reqAmount) + parseFloat(totalAccrued323);
            var remaining = Math.max(base - consumed, 0);
            var pct = base > 0 ? ((reqAmount / base) * 100).toFixed(1) : 0;
            if (pct > 999) pct = '>100';
            var html = '<div style="font-size:.8rem;font-weight:700;color:#64748b;margin-bottom:.4rem"><i class="fa fa-pie-chart me-1"></i> ملخص الرواتب للشهر</div>';
            html += '<div class="pr-row">';
            html += '<div class="pr-card c-total"><div class="c-label"><i class="fa fa-money"></i> إجمالي الصافي للشهر</div><div class="c-val">' + nf(base) + '</div><div class="c-cnt" title="مجموع عمود الصافي في التفاصيل">' + j.emp_count + ' موظف <small style="opacity:.7">(الشهر كله)</small></div></div>';
            html += '<div class="pr-card" style="border-color:#fde68a;background:#fefce8"><div class="c-label"><i class="fa fa-pencil"></i> مسودة (كل الطلبات)</div><div class="c-val" style="color:#92400e">' + nf(j.total_draft) + '</div></div>';
            html += '<div class="pr-card" style="border-color:#bfdbfe;background:#dbeafe"><div class="c-label"><i class="fa fa-check"></i> معتمد</div><div class="c-val" style="color:#1e40af">' + nf(j.total_approved) + '</div></div>';
            html += '<div class="pr-card" style="border-color:#bbf7d0;background:#f0fdf4"><div class="c-label"><i class="fa fa-check-circle"></i> منفّذ للصرف</div><div class="c-val" style="color:#059669">' + nf(j.total_paid) + '</div></div>';
            html += '<div class="pr-card c-net"><div class="c-label"><i class="fa fa-file-text-o"></i> مبلغ الصرف (هذا الطلب)</div><div class="c-val">' + nf(reqAmount) + '</div><div class="c-cnt">' + pct + '% من الصافي</div></div>';
            if (totalAccrued323 > 0) {
                html += '<div class="pr-card" style="border-color:#e9d5ff;background:#faf5ff"><div class="c-label" style="color:#6b21a8"><i class="fa fa-archive"></i> سيُرحّل كمستحقات</div><div class="c-val" style="color:#6b21a8">' + nf(totalAccrued323) + '</div><div class="c-cnt">من الاحتساب</div></div>';
            }
            html += '<div class="pr-card c-active"><div class="c-label"><i class="fa fa-arrow-left"></i> المتبقي للصرف</div><div class="c-val">' + nf(remaining) + '</div></div>';
            html += '</div>';
            $('#showWalletSummary').html(html);
        });
    }

    // التهيئة الأولى عند تحميل الصفحة
    _renderWalletSummary(<?= $total_amount ?>, <?= $total_accrued_323 ?>, <?= $total_net_calc ?>);
    <?php else: ?>
    // stub — لا شيء لتحديثه لأن النوع ليس 2
    function _renderWalletSummary(){}
    <?php endif; ?>

    // ═══ REQ_TYPE=2: فحص حالة الشهر ═══
    var _showPTLoaded = false;
    function showLoadPayTypes(defaultVal) {
        var _s = $('#pay_type');
        $('#show_pay_type_grp').show();
        if (!_showPTLoaded) {
            _s.html('<option value="">— جاري التحميل —</option>');
            $.getJSON('<?= base_url("payroll_data/salary_dues_types/public_get_tree_json") ?>', function(data) {
                _s.html('<option value="">— اختر بند —</option>');
                function _addPT(nodes) {
                    $.each(nodes || [], function(i, node) {
                        if (node.attributes && node.attributes.isLeaf && node.attributes.lineType == 2) _s.append($('<option>', { value: node.id, text: node.text }));
                        if (node.children) _addPT(node.children);
                    });
                }
                _addPT(data);
                _showPTLoaded = true;
                if (defaultVal) _s.val(defaultVal);
            });
        } else {
            if (defaultVal) _s.val(defaultVal);
        }
    }

    function showCheckMonth() {
        var month = $('#the_month').val();
        if (!month || month.length !== 6) { $('#show_month_status').html(''); return; }

        jQuery.post(salCheckUrl, { month: month }, function(resp) {
            var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
            _showMonthCalc = j;

            if (j.calculated) {
                var _h = '<div class="alert alert-success py-2 mb-0 mt-2" style="font-size:.82rem;border-radius:8px">';
                _h += '<i class="fa fa-check-circle me-1"></i> <strong>الشهر محتسب</strong>';
                if(j.source === 'calc') _h += ' <span class="badge bg-success">من جدول الاحتساب</span>';
                _h += ' — ' + j.emp_count + ' موظف';
                _h += ' | إجمالي الصافي: <strong>' + nf(j.total_net) + '</strong>';
                _h += '</div>';
                $('#show_month_status').html(_h);
                $('#show_calc_section').hide();
                $('#btnSaveMaster').show();
            } else {
                $('#show_month_status').html(
                    '<div class="alert alert-warning py-2 mb-0 mt-2" style="font-size:.82rem;border-radius:8px">' +
                    '<i class="fa fa-exclamation-triangle me-1"></i> <strong>الشهر غير محتسب</strong> — ' +
                    'اختر طريقة الاحتساب أدناه</div>'
                );
                $('#show_calc_section').show();
                $('#btnSaveMaster').hide();
            }
        });
    }

    // ═══ اختيار الطريقة ═══
    var _wizMode = 'all'; // 'all' أو 'selected'

    function wizChooseAll(){
        _wizMode = 'all';
        wizPreviewEmployees();
    }

    function wizChooseSpecific(){
        _wizMode = 'selected';
        $('#wiz_choice').hide();
        $('#wiz_pick_emps').slideDown(200);
        if(!$('#wiz_emp_select').data('select2')){
            $('#wiz_emp_select').select2({placeholder: '— اختر موظفين —', allowClear: true, width: '100%'});
            $('#wiz_emp_select').on('change', wizBuildAmountsTable);
        }
    }

    var _wizPickedAmounts = {}; // {emp_no: amount}

    function wizBuildAmountsTable(){
        var sel = $('#wiz_emp_select').val() || [];
        if(sel.length === 0){ $('#wiz_pick_amounts').hide(); return; }
        var cfg = _wizExcelCols();
        var rt = $('#req_type').val();
        var cm = $('#calc_method').val();
        var am = $('#show_amount_mode').val();
        // المبلغ مقفل (مش قابل للتعديل):
        // - نوع 1، 2، 3 نسبة (حساب تلقائي)
        // - نوع 3 مبلغ ثابت + same، نوع 4 + same، نوع 5 + same (مبلغ موحد)
        // مفتوح للتعديل (per-emp):
        // - نوع 3 مبلغ ثابت + diff، نوع 4 + diff، نوع 5 + diff
        var isLockedAll = (rt == '1') || (rt == '2') || (rt == '3' && cm == '1')
                       || (rt == '3' && cm == '2' && am === 'same')
                       || (rt == '4' && am === 'same')
                       || (rt == '5' && am === 'same');
        var placeholder = cfg.amtRequired ? 'أدخل المبلغ *' : cfg.amtHint;
        var amtLabel;
        if(isLockedAll) amtLabel = 'المبالغ <span class="text-muted fw-normal">(' + cfg.amtHint + ' — موحد للكل)</span>';
        else if(cfg.amtRequired) amtLabel = 'المبالغ <span class="text-danger">(إجباري لكل موظف)</span>';
        else amtLabel = 'المبالغ <span class="text-muted fw-normal">(فاضي = '+cfg.amtHint+')</span>';
        // المبلغ الافتراضي من الإعدادات (مبلغ ثابت بالماستر)
        var defaultAmt = '';
        if(rt == '3' && cm == '2' && am === 'same') defaultAmt = $('#show_req_amount').val() || '';
        else if(rt == '4' && am === 'same') defaultAmt = $('#show_req_amount').val() || '';
        else if(rt == '5' && am === 'same') defaultAmt = $('#show_req_amount').val() || '';
        var html = '';
        for(var i=0; i<sel.length; i++){
            var empNo = sel[i];
            var empName = $('#wiz_emp_select option[value="'+empNo+'"]').text();
            var prevAmt = _wizPickedAmounts[empNo] || defaultAmt || '';
            // حفظ المبلغ الافتراضي بالذاكرة
            if(defaultAmt && !_wizPickedAmounts[empNo]) _wizPickedAmounts[String(empNo)] = parseFloat(defaultAmt);
            html += '<tr>';
            html += '<td class="text-center text-muted">'+(i+1)+'</td>';
            html += '<td style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:350px" title="'+empName+'">'+empName+'</td>';
            // لو مقفل مع مبلغ موحد (3 ثابت+same، 4+same، 5+same) نعرض المبلغ
            var showLockedAmt = (rt == '3' && cm == '2' && am === 'same')
                             || (rt == '4' && am === 'same')
                             || (rt == '5' && am === 'same');
            var inputValue = isLockedAll ? (showLockedAmt ? (defaultAmt || prevAmt || '') : '') : prevAmt;
            html += '<td><input type="number" class="form-control form-control-sm wiz-pick-amt" data-emp="'+empNo+'" value="'+inputValue+'" min="0" step="0.01" placeholder="'+placeholder+'"'+(isLockedAll ? ' disabled' : '')+' onchange="wizSavePickAmt(this)" style="'+(isLockedAll ? 'background:#f1f5f9;cursor:not-allowed' : '')+'"></td>';
            html += '<td class="text-center"><button class="btn btn-sm btn-outline-danger p-0 px-1" onclick="wizRemovePickedEmp(\''+empNo+'\')"><i class="fa fa-times"></i></button></td>';
            html += '</tr>';
        }
        $('#wiz_pick_amounts_body').html(html);
        $('#wiz_pick_amounts .text-muted.fw-bold').html('<i class="fa fa-money"></i> '+amtLabel);
        $('#wiz_pick_amounts').show();
    }

    function wizSavePickAmt(el){
        var emp = $(el).data('emp');
        var val = parseFloat($(el).val()) || 0;
        if(val > 0) _wizPickedAmounts[String(emp)] = val;
        else delete _wizPickedAmounts[String(emp)];
    }

    function wizRemovePickedEmp(empNo){
        var sel = ($('#wiz_emp_select').val() || []).filter(function(v){ return v !== empNo; });
        $('#wiz_emp_select').val(sel).trigger('change');
        delete _wizPickedAmounts[empNo];
    }

    function wizBackToChoice(){
        $('#wiz_pick_emps, #wiz_pick_excel').hide();
        $('#wiz_choice').show();
    }

    function wizPreviewSelected(){
        var sel = $('#wiz_emp_select').val();
        if(!sel || sel.length === 0){ danger_msg('تحذير','اختر موظف واحد على الأقل'); return; }
        // حفظ المبالغ من الجدول
        $('.wiz-pick-amt').each(function(){ wizSavePickAmt(this); });
        // فحص المبالغ الإجبارية
        var cfg = _wizExcelCols();
        if(cfg.amtRequired){
            var missing = [];
            for(var i=0; i<sel.length; i++){
                if(!_wizPickedAmounts[sel[i]] || _wizPickedAmounts[sel[i]] <= 0){
                    var name = $('#wiz_emp_select option[value="'+sel[i]+'"]').text();
                    missing.push(name);
                }
            }
            if(missing.length > 0){
                danger_msg('تحذير','المبلغ مطلوب لكل موظف. ناقص: '+missing.length+' موظف');
                return;
            }
        }
        wizPreviewEmployees();
    }

    // ═══ Excel ═══
    var _wizExcelEmps = []; // [{emp_no, amount}]

    function _wizExcelCols(){
        var rt = $('#req_type').val();
        var cm = $('#calc_method').val();
        var am = $('#show_amount_mode').val();
        var sameAmt = $('#show_req_amount').val();
        // المبلغ إجباري: كل الأنواع ماعدا نوع 1 (كامل المستحقات)
        var amtRequired = false;
        var amtHint = 'كامل المستحقات';
        if(rt == '1'){ amtRequired = false; amtHint = 'كامل المستحقات'; }
        else if(rt == '2'){ amtRequired = false; amtHint = 'حسب النسبة والحدود'; }
        else if(rt == '3' && cm == '1'){ amtRequired = false; amtHint = 'حسب النسبة'; }
        else if(rt == '3' && cm == '2'){
            if(am === 'same' && parseFloat(sameAmt) > 0){
                amtRequired = false; amtHint = 'المبلغ الموحد: ' + sameAmt;
            } else {
                amtRequired = true; amtHint = 'أدخل المبلغ';
            }
        }
        else if(rt == '4'){
            if(am === 'same' && parseFloat(sameAmt) > 0){
                amtRequired = false; amtHint = 'المبلغ الموحد: ' + sameAmt;
            } else if(am === 'diff'){
                amtRequired = true; amtHint = 'أدخل المبلغ';
            } else {
                amtRequired = false; amtHint = 'حسب الشهر';
            }
        }
        else if(rt == '5'){
            if(am === 'same' && parseFloat(sameAmt) > 0){
                amtRequired = false; amtHint = 'المبلغ الموحد: ' + sameAmt;
            } else {
                amtRequired = true; amtHint = 'أدخل المبلغ';
            }
        }
        return {cols: ['رقم الموظف', 'المبلغ'], amtRequired: amtRequired, amtHint: amtHint};
    }

    function wizChooseExcel(){
        _wizMode = 'selected';
        _wizExcelEmps = [];
        $('#wiz_choice').hide();
        $('#wiz_pick_excel').slideDown(200);
        $('#wiz_excel_file').val('');
        $('#wiz_excel_result, #wiz_excel_actions').hide();

        // تعليمات ذكية حسب النوع
        var cfg = _wizExcelCols();
        var hint = '<i class="fa fa-info-circle text-primary"></i> ';
        hint += 'العمود A = رقم الموظف (إجباري)';
        hint += ' | العمود B = المبلغ' + (cfg.amtRequired ? ' <strong class="text-danger">(إجباري)</strong>' : ' (اختياري — فاضي = '+cfg.amtHint+')');
        hint += ' | الصف الأول عنوان يتم تجاهله';
        $('#wiz_excel_hint').html(hint);
    }

    function wizDownloadTemplate(){
        var cfg = _wizExcelCols();
        var data = [cfg.cols, ['', ''], ['', '']];
        var wb = XLSX.utils.book_new();
        var ws = XLSX.utils.aoa_to_sheet(data);
        ws['!cols'] = [{wch:15},{wch:15}];
        XLSX.utils.book_append_sheet(wb, ws, 'قالب');
        var typeName = {1:'راتب_كامل',2:'دفعة_راتب',3:'دفعة_مستحقات',4:'مستحقات_شهر',5:'استحقاقات'}[$('#req_type').val()] || 'طلب';
        XLSX.writeFile(wb, 'قالب_'+typeName+'.xlsx');
    }

    // 🆕 نحفظ الـ File object نفسه لرفعه للسيرفر بعد إنشاء الطلب
    var _wizUploadedFile = null;
    // 🆕 السطور الخام من الإكسل (لكل سطر: row, emp_no, amount, note) — لحفظها في PAYMENT_REQ_IMP_LINE_TB
    var _wizExcelRawLines = [];

    function wizParseExcel(input){
        if(!input.files || !input.files[0]) return;
        _wizUploadedFile = input.files[0];   // 🆕 احتفظ بالملف
        var reader = new FileReader();
        reader.onload = function(e){
            try {
                var wb = XLSX.read(e.target.result, {type:'binary'});
                var ws = wb.Sheets[wb.SheetNames[0]];
                var data = XLSX.utils.sheet_to_json(ws, {header:1});
                var cfg = _wizExcelCols();
                _wizExcelEmps = [];
                _wizExcelRawLines = [];   // 🆕 reset
                var errors = [];
                // 🆕 تجميع الصفوف لكل موظف (دمج التكرار + الصف الواحد لكل موظف)
                var byEmp = {};       // emp_no → { amount: total, rows: [excel rows] }
                var mergedEmps = 0;   // عدد الموظفين الذين عندهم > سطر
                var mergedRows = 0;   // إجمالي الصفوف المدموجة

                for(var i=1; i<data.length; i++){
                    var row = data[i];
                    var empNo = row[0];
                    if(!empNo) continue;
                    empNo = String(parseInt(empNo));
                    if(!empNo || empNo === 'NaN') continue;
                    var amt = parseFloat(row[1]) || 0;
                    var noteVal = (row[2] != null) ? String(row[2]).trim() : '';
                    if(cfg.amtRequired && amt <= 0){
                        errors.push('صف '+(i+1)+': الموظف '+empNo+' بدون مبلغ');
                        continue;
                    }
                    if (!byEmp[empNo]) {
                        byEmp[empNo] = { amount: 0, rows: [] };
                    }
                    byEmp[empNo].amount += amt;
                    byEmp[empNo].rows.push(i+1);
                    // 🆕 احفظ السطر الخام للحفظ لاحقاً في الجدول الفرعي
                    _wizExcelRawLines.push({
                        row: i+1, emp_no: empNo, amount: amt, note: noteVal
                    });
                }

                // تحويل الـ map إلى array + احتساب الـ merged stats
                Object.keys(byEmp).forEach(function(eno){
                    var info = byEmp[eno];
                    if (info.rows.length > 1) {
                        mergedEmps++;
                        mergedRows += info.rows.length;
                    }
                    _wizExcelEmps.push({
                        emp_no: eno,
                        amount: Math.round(info.amount * 100) / 100,   // 2 decimals
                        merged_count: info.rows.length,
                        merged_rows:  info.rows
                    });
                });
                if(_wizExcelEmps.length === 0){
                    var msg = errors.length > 0 ? errors.join('<br>') : 'لم يتم العثور على أرقام موظفين بالملف';
                    $('#wiz_excel_result').html('<div class="alert alert-warning py-2" style="font-size:.82rem"><i class="fa fa-exclamation-triangle me-1"></i> '+msg+'</div>').show();
                    $('#wiz_excel_actions').hide();
                    return;
                }
                var html = '<div class="alert alert-success py-2" style="font-size:.82rem">';
                html += '<i class="fa fa-check-circle me-1"></i> تم قراءة <strong>'+_wizExcelEmps.length+'</strong> موظف من الملف';
                var withAmt = _wizExcelEmps.filter(function(r){ return r.amount > 0; }).length;
                var totalAmt = _wizExcelEmps.reduce(function(s,r){ return s + r.amount; }, 0);
                if(withAmt > 0) html += ' — '+withAmt+' بمبلغ محدد، إجمالي: '+Number(totalAmt).toLocaleString('en-US',{minimumFractionDigits:2,maximumFractionDigits:2});
                var noAmt = _wizExcelEmps.length - withAmt;
                if(noAmt > 0 && !cfg.amtRequired) html += ' | '+noAmt+' بدون مبلغ (سيُحتسب تلقائياً)';
                if(errors.length > 0) html += '<br><span class="text-warning">'+errors.length+' صف تم تجاهله (بدون مبلغ)</span>';
                // 🆕 ملاحظة الدمج (لو فيه)
                if(mergedEmps > 0){
                    html += '<br><span style="color:#3730a3"><i class="fa fa-magic"></i> تم دمج <strong>'+mergedRows+'</strong> صف من Excel ضمن <strong>'+mergedEmps+'</strong> موظف (مكرر — تم جمع المبالغ)</span>';
                }
                html += '</div>';
                $('#wiz_excel_result').html(html).show();
                $('#wiz_excel_actions').show();

                // 🆕 لما الإكسل يحوي مبالغ → نُجبر "طريقة المبلغ" = مختلف لكل موظف
                // ونُخفي حقل "المبلغ لكل موظف" (لأنه جاي من الإكسل)
                if (withAmt > 0) {
                    if ($('#show_amount_mode_grp').is(':visible') || $('#show_amount_mode').length) {
                        $('#show_amount_mode').val('diff');
                        if (typeof showOnAmountModeChange === 'function') showOnAmountModeChange();
                    }
                    // نضمن إخفاء حقل المبلغ + إزالة required
                    $('#show_amount_grp').hide();
                    $('#show_req_amount').val('').prop('required', false);
                }

                var empNos = _wizExcelEmps.map(function(r){ return r.emp_no; });
                $('#wiz_emp_select').val(empNos).trigger('change');
            } catch(ex){
                $('#wiz_excel_result').html('<div class="alert alert-danger py-2" style="font-size:.82rem"><i class="fa fa-times-circle me-1"></i> خطأ في قراءة الملف: '+ex.message+'</div>').show();
                $('#wiz_excel_actions').hide();
            }
        };
        reader.readAsBinaryString(input.files[0]);
    }

    function wizPreviewExcelEmps(){
        if(_wizExcelEmps.length === 0){ danger_msg('تحذير','ارفع ملف Excel أولاً'); return; }
        wizPreviewEmployees();
    }

    // ═══════════════════════════════════════════════
    // ═══ خطوة 3: معاينة الموظفين + الإنشاء الجماعي ═══
    // ═══════════════════════════════════════════════

    var _wizEligible = [];
    var _wizEligibleCount = 0;
    var _wizTotalAmt = 0;
    var _wizCalcDesc = '';

    function _wizParams(){
        var rt = $('#req_type').val();
        var pctVal = '';
        if(rt == '2'){
            // نوع 2: نسبة الصرف من حقل percent
            pctVal = $('#show_percent_input').val() || $('#show_percent_sel').val() || '';
        } else if(rt == '3'){
            // نوع 3: calc_method=1 → نسبة | calc_method=2 → مبلغ
            var cm = $('#calc_method').val();
            if(cm == '1') pctVal = $('#show_percent_input').val() || $('#show_percent_sel').val() || '';
        }
        return {
            the_month:    $('#the_month').val(),
            req_type:     rt,
            calc_method:  $('#calc_method').val() || '',
            percent_val:  pctVal,
            req_amount:   $('#show_req_amount').val() || '',
            pay_type:     $('#pay_type').val() || '',
            sal_from:     '',
            sal_to:       '',
            branch_no:    $('#wiz_branch_no').val() || '',
            l_value:      $('#show_l_value').val() || '1400',
            h_value:      $('#show_h_value').val() || '3400',
            entry_date:   $('#entry_date').val() || '',
            note:         $('#note').val() || 'إنشاء من الويزارد',
            // 🆕 شهر فلتر اختياري — للنوع 3 فقط (موجود في DOM فقط لما الـ wizard يظهر)
            filter_month: $('#filter_month').val() || ''
        };
    }

    function _wizValidate(){
        var p = _wizParams();
        var rt = p.req_type;
        if(!rt){ danger_msg('تحذير','اختر نوع الطلب'); return false; }
        // 🆕 الشهر مطلوب لكل الأنواع ما عدا 3 (دفعة من المستحقات — لا معنى مالي للشهر)
        if(rt != '3' && (!p.the_month || p.the_month.length !== 6)){
            danger_msg('تحذير','أدخل الشهر بصيغة YYYYMM'); return false;
        }

        // الشهر محتسب — أنواع 1,2,4
        if(rt == '1' || rt == '2' || rt == '4'){
            if(!_showMonthCalc || !_showMonthCalc.calculated){
                danger_msg('تحذير','الشهر غير محتسب — يجب احتساب الرواتب أولاً'); return false;
            }
        }

        // نوع 1: راتب كامل
        if(rt == '1'){
            if(!p.pay_type){ danger_msg('تحذير','اختر بند المستحقات'); return false; }
        }

        // نوع 2: دفعة من الراتب — نسبة فقط
        if(rt == '2'){
            if(!p.pay_type){ danger_msg('تحذير','اختر بند المستحقات'); return false; }
            if(!p.percent_val || parseFloat(p.percent_val) <= 0){
                danger_msg('تحذير','أدخل النسبة %'); return false;
            }
            if(p.l_value === '' || p.l_value === undefined){ danger_msg('تحذير','أدخل الحد الأدنى'); return false; }
            if(p.h_value === '' || p.h_value === undefined){ danger_msg('تحذير','أدخل الحد الأعلى'); return false; }
            if(parseFloat(p.l_value) > parseFloat(p.h_value)){ danger_msg('تحذير','الحد الأدنى أكبر من الأعلى'); return false; }
        }

        // نوع 3: دفعة من المستحقات
        if(rt == '3'){
            if(!p.pay_type){ danger_msg('تحذير','اختر بند المستحقات'); return false; }
            if(!p.calc_method){ danger_msg('تحذير','اختر طريقة الاحتساب'); return false; }
            if(p.calc_method == '1' && (!p.percent_val || parseFloat(p.percent_val) <= 0)){
                danger_msg('تحذير','أدخل النسبة %'); return false;
            }
            if(p.calc_method == '2'){
                var am3 = $('#show_amount_mode').val() || 'same';
                if(am3 === 'same' && (!p.req_amount || parseFloat(p.req_amount) <= 0)){
                    danger_msg('تحذير','أدخل المبلغ الموحد'); return false;
                }
                // diff: المبلغ يُتحقق منه per-emp في wizPreviewSelected
            }
        }

        // نوع 4: مستحقات حسب الشهر
        if(rt == '4'){
            if(!p.pay_type){ danger_msg('تحذير','اختر بند المستحقات'); return false; }
            var am4 = $('#show_amount_mode').val() || 'same';
            if(am4 === 'same' && p.req_amount && parseFloat(p.req_amount) <= 0){
                danger_msg('تحذير','المبلغ الموحد يجب أن يكون أكبر من صفر'); return false;
            }
            // same بدون مبلغ → fallback لاحتساب المتبقي تلقائياً (sentinel)
            // diff: المبلغ يُتحقق منه per-emp
        }

        // نوع 5: استحقاقات وإضافات
        if(rt == '5'){
            var bc = $('#show_benefit_con').val();
            if(!bc){ danger_msg('تحذير','اختر بند الاستحقاق'); return false; }
            var am = $('#show_amount_mode').val();
            if(am === 'same' && (!p.req_amount || parseFloat(p.req_amount) <= 0)){
                danger_msg('تحذير','أدخل المبلغ الموحد'); return false;
            }
        }

        return true;
    }

    function _wizShowStep3(){
        $('#wiz_type_section').slideUp(200);
        $('#wiz_settings').slideUp(200);
        $('#wiz_choice').hide();
        $('#wiz_pick_emps, #wiz_pick_excel').hide();
        $('#wiz_step3').slideDown(200);
        $('#wiz_preview_loading').show();
        $('#wiz_preview_content').html('');
        $('#wiz_step3_buttons').hide();
    }

    function _wizGetManualAmounts(){
        // تجميع المبالغ اليدوية (من الجدول + الإكسل)
        var amts = {};
        // من الإكسل
        for(var i=0; i<_wizExcelEmps.length; i++){
            if(_wizExcelEmps[i].amount > 0) amts[_wizExcelEmps[i].emp_no] = _wizExcelEmps[i].amount;
        }
        // من الجدول (يتفوق على الإكسل)
        for(var k in _wizPickedAmounts){
            if(_wizPickedAmounts[k] > 0) amts[k] = _wizPickedAmounts[k];
        }
        return amts;
    }

    function _wizBuildManualRows(){
        // بناء صفوف المعاينة من بيانات اليوزر مباشرة (بدون bulk_preview)
        var sel = ($('#wiz_emp_select').val() || []).map(String);
        var amts = _wizGetManualAmounts();
        // نوع 5 + نفس المبلغ: طبق المبلغ الموحد لكل موظف ما عنده مبلغ خاص
        var sameAmt = 0;
        if($('#req_type').val() == '5' && $('#show_amount_mode').val() === 'same'){
            sameAmt = parseFloat($('#show_req_amount').val()) || 0;
        }
        var rows = [];
        for(var i=0; i<sel.length; i++){
            var empNo = sel[i];
            var empName = ($('#wiz_emp_select option[value="'+empNo+'"]').text() || '').replace(empNo+' - ', '');
            var amt = amts[empNo] || sameAmt || 0;
            rows.push({
                EMP_NO: empNo,
                EMP_NAME: empName,
                BRANCH_NAME: '',
                CALC_AMOUNT: amt,
                CON_323_AMOUNT: 0,
                HAS_EXISTING: 0,
                SKIP_REASON: (amt <= 0) ? 'بدون مبلغ' : null
            });
        }
        return rows;
    }

    function wizPreviewEmployees(){
        if(!_wizValidate()) return;
        var p = _wizParams();
        var rt = p.req_type;
        _wizShowStep3();

        // نوع 5: ما فيه bulk_preview — نبني المعاينة من بيانات اليوزر
        if(rt == '5'){
            $('#wiz_preview_loading').hide();
            var rows = _wizBuildManualRows();
            if(rows.length === 0){ danger_msg('تحذير','لا يوجد موظفين'); wizBackToSettings(); return; }
            _wizRenderPreview(rows, p);
            return;
        }

        // أنواع 1-4: نستخدم bulk_preview
        get_data(bulkPreviewUrl, p, function(resp){
            $('#wiz_preview_loading').hide();
            try {
                var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
                if(!j.ok){ danger_msg('خطأ', j.msg || 'خطأ'); wizBackToSettings(); return; }
                var rows = j.data || [];

                // نوع 4 + same + master amount > 0 → طبّق المبلغ الموحد على كل الموظفين
                // (الـ procedure يستخدم LEAST(master, المتبقي) — نعكس نفس المنطق في المعاينة)
                var amHere = $('#show_amount_mode').val();
                var masterAmt = parseFloat($('#show_req_amount').val()) || 0;
                if(rt == '4' && amHere === 'same' && masterAmt > 0){
                    for(var i=0; i<rows.length; i++){
                        if(rows[i].HAS_EXISTING == 0){
                            var origCalc = parseFloat(rows[i].CALC_AMOUNT) || 0;
                            // المبلغ الفعلي = الأقل بين الموحد والمتبقي للموظف
                            rows[i].CALC_AMOUNT = (origCalc > 0) ? Math.min(masterAmt, origCalc) : 0;
                            // لو فيه متبقي > 0، اقبله رغم أن SKIP_REASON قد يكون موجود
                            if(origCalc > 0) rows[i].SKIP_REASON = null;
                        }
                    }
                }

                if(_wizMode === 'selected'){
                    var selEmps = ($('#wiz_emp_select').val() || []).map(String);
                    if(selEmps.length > 0){
                        rows = rows.filter(function(r){ return selEmps.indexOf(String(r.EMP_NO)) > -1; });
                    }
                    // استبدال المبالغ المحتسبة بالمبالغ اليدوية — تتجاوز SKIP_REASON لو اليوزر حدد مبلغ
                    var manualAmts = _wizGetManualAmounts();
                    for(var i=0; i<rows.length; i++){
                        var ma = manualAmts[String(rows[i].EMP_NO)];
                        if(ma && ma > 0){
                            rows[i].CALC_AMOUNT = ma;
                            rows[i].SKIP_REASON = null; // اليوزر حدد مبلغ — نقبل
                        }
                    }
                }
                _wizRenderPreview(rows, p);
            } catch(e){ danger_msg('خطأ', e.message); wizBackToSettings(); }
        }, 'json');
    }

    function _wizRenderPreview(rows, p){
        var eligible, skipped, noAmt, totalAmt;
        var fmtN = function(n){ return Number(n).toLocaleString('en-US'); };
        var fmtA = function(n){ return Number(n).toLocaleString('en-US',{minimumFractionDigits:2,maximumFractionDigits:2}); };
        var mn = p.the_month ? p.the_month.substring(4,6)+'/'+p.the_month.substring(0,4) : '';

        if(p.req_type == '2'){
            eligible = rows.filter(function(r){ return parseFloat(r.CALC_AMOUNT||0) > 0 && !r.SKIP_REASON; });
            skipped  = rows.filter(function(r){ return r.HAS_EXISTING > 0; });
            noAmt    = rows.filter(function(r){ return (parseFloat(r.CALC_AMOUNT||0) <= 0 || r.SKIP_REASON) && r.HAS_EXISTING == 0; });
        } else {
            eligible = rows.filter(function(r){ return r.HAS_EXISTING == 0 && parseFloat(r.CALC_AMOUNT) > 0 && !r.SKIP_REASON; });
            skipped  = rows.filter(function(r){ return r.HAS_EXISTING > 0; });
            noAmt    = rows.filter(function(r){ return r.HAS_EXISTING == 0 && (parseFloat(r.CALC_AMOUNT) <= 0 || r.SKIP_REASON); });
        }
        totalAmt = eligible.reduce(function(s,r){ return s + parseFloat(r.CALC_AMOUNT||0); }, 0);

        // حفظ البيانات
        _wizEligible = eligible;
        _wizEligibleCount = eligible.length;
        _wizTotalAmt = totalAmt;

        var calcDesc = '';
        var _am = $('#show_amount_mode').val();
        if(p.req_type == '1') calcDesc = 'كامل المستحقات';
        else if(p.req_type == '2') calcDesc = p.percent_val + '% من المستحقات';
        else if(p.req_type == '3'){
            if(p.calc_method == '1') calcDesc = p.percent_val + '% من الرصيد';
            else if(_am === 'diff') calcDesc = 'مبلغ مختلف لكل موظف';
            else calcDesc = fmtA(p.req_amount)+' لكل موظف';
        }
        else if(p.req_type == '4'){
            if(_am === 'diff') calcDesc = 'مبلغ مختلف لكل موظف';
            else if(_am === 'same' && parseFloat(p.req_amount) > 0) calcDesc = fmtA(p.req_amount)+' لكل موظف';
            else calcDesc = 'المتبقي من مستحقات بند 323 للشهر';
        }
        else if(p.req_type == '5') calcDesc = (_am === 'same' ? fmtA(p.req_amount)+' لكل موظف' : 'مبلغ لكل موظف');
        _wizCalcDesc = calcDesc;

        var noAmtLabel = (p.req_type == '2') ? 'مرحّل مسبقاً' : 'لا يوجد رصيد';

        var html = '<div class="pr-row mb-3">';
        html += '<div class="pr-card c-net"><div class="c-label"><i class="fa fa-check-circle"></i> سيتم إنشاء</div><div class="c-val">'+fmtN(eligible.length)+'</div></div>';
        html += '<div class="pr-card" style="border-color:#fde68a;background:#fefce8"><div class="c-label"><i class="fa fa-exclamation-circle"></i> موجود مسبقاً</div><div class="c-val" style="color:#d97706">'+fmtN(skipped.length)+'</div></div>';
        html += '<div class="pr-card c-cancel"><div class="c-label"><i class="fa fa-times-circle"></i> '+noAmtLabel+'</div><div class="c-val">'+fmtN(noAmt.length)+'</div></div>';
        html += '<div class="pr-card c-active"><div class="c-label"><i class="fa fa-money"></i> إجمالي الصرف</div><div class="c-val" style="font-size:.8rem">'+fmtA(totalAmt)+'</div></div>';
        html += '<div class="pr-card" style="background:#f8fafc"><div class="c-label"><i class="fa fa-calculator"></i> الاحتساب</div><div class="c-val" style="color:#475569;font-size:.8rem">'+calcDesc+'</div></div>';
        html += '<div class="pr-card c-total"><div class="c-label"><i class="fa fa-calendar"></i> الشهر</div><div class="c-val">'+mn+'</div></div>';
        html += '</div>';

        // ملخص المحفظة لنوع 2
        if(p.req_type == '2' && _showMonthCalc){
            var ms = _showMonthCalc;
            var prevDisb = (ms.total_draft||0) + (ms.total_approved||0) + (ms.total_paid||0);
            // المصدر الأساسي: إجمالي 323 المتراكم (من DATA.SALARY)
            var prevBase = ms.total_323 > 0 ? ms.total_323 : (ms.total_net||0);
            var prevBaseLabel = ms.total_323 > 0 ? 'إجمالي المستحقات (323)' : 'إجمالي المحتسب';
            // الباقي = الرصيد - (المصروف سابقاً + الدفعة الحالية)
            var afterThis = Math.max(prevBase - prevDisb - totalAmt, 0);

            // إجمالي سيُرحّل كمستحقات (من PAYMENT_REQ_ADMIN_CALC_TB إن كان المعاينة تحتوي عليه)
            var _totAccrued = eligible.reduce(function(s,r){ return s + parseFloat(r.CON_323_AMOUNT||0); }, 0);

            html += '<div class="pr-row mb-2">';
            html += '<div class="pr-card c-total"><div class="c-label"><i class="fa fa-money"></i> '+prevBaseLabel+'</div><div class="c-val">'+fmtA(prevBase||0)+'</div></div>';
            html += '<div class="pr-card" style="border-color:#fde68a;background:#fefce8"><div class="c-label"><i class="fa fa-pencil"></i> مسودة</div><div class="c-val" style="color:#92400e">'+fmtA(ms.total_draft||0)+'</div></div>';
            html += '<div class="pr-card" style="border-color:#bfdbfe;background:#dbeafe"><div class="c-label"><i class="fa fa-check"></i> معتمد</div><div class="c-val" style="color:#1e40af">'+fmtA(ms.total_approved||0)+'</div></div>';
            html += '<div class="pr-card" style="border-color:#bbf7d0;background:#f0fdf4"><div class="c-label"><i class="fa fa-check-circle"></i> منفّذ للصرف</div><div class="c-val" style="color:#059669">'+fmtA(ms.total_paid||0)+'</div></div>';
            html += '<div class="pr-card c-net"><div class="c-label"><i class="fa fa-plus-circle"></i> الدفعة الحالية ('+p.percent_val+'%)</div><div class="c-val">'+fmtA(totalAmt)+'</div></div>';
            html += '<div class="pr-card" style="border-color:#e9d5ff;background:#faf5ff"><div class="c-label" style="color:#6b21a8"><i class="fa fa-archive"></i> سيُرحّل كمستحقات</div><div class="c-val" style="color:#6b21a8">'+fmtA(_totAccrued)+'</div></div>';
            html += '<div class="pr-card c-active"><div class="c-label"><i class="fa fa-arrow-left"></i> المتبقي بعد الدفعة</div><div class="c-val">'+fmtA(afterThis)+'</div></div>';
            html += '</div>';
        }

        // جدول الموظفين مع checkboxes + بحث + فلتر
        if(eligible.length > 0){
            // جمع المقرات الموجودة
            var _branches = {};
            eligible.forEach(function(r){ if(r.BRANCH_NAME) _branches[r.BRANCH_NAME] = true; });
            var brList = Object.keys(_branches).sort();

            html += '<div class="d-flex justify-content-between align-items-center mb-1 flex-wrap gap-2">';
            html += '<div class="d-flex align-items-center gap-2">';
            html += '<div><input type="checkbox" id="wizChkAll" checked onchange="wizToggleAll(this)"> <label for="wizChkAll" style="font-size:.78rem;cursor:pointer">تحديد / إلغاء الكل</label></div>';
            html += '<small class="text-muted" id="wizSelectedCount">'+eligible.length+' / '+eligible.length+' محدد</small>';
            html += '</div>';
            html += '<div class="d-flex gap-2">';
            // بحث
            html += '<input type="text" id="wizSearchEmp" class="form-control form-control-sm" placeholder="بحث بالرقم أو الاسم..." style="width:200px;font-size:.78rem" oninput="wizFilterTable()">';
            // فلتر المقر
            if(brList.length > 1){
                html += '<select id="wizFilterBranch" class="form-control form-control-sm" style="width:150px;font-size:.78rem" onchange="wizFilterTable()">';
                html += '<option value="">كل المقرات</option>';
                for(var b=0; b<brList.length; b++) html += '<option value="'+brList[b]+'">'+brList[b]+'</option>';
                html += '</select>';
            }
            html += '</div>';
            html += '</div>';
            html += '<div class="table-responsive" style="max-height:400px;overflow-y:auto">';
            html += '<table class="table table-bordered table-sm" id="wizPreviewTable">';
            var chkCol = '<th style="width:30px"><input type="checkbox" checked onchange="wizToggleAll(this)"></th>';
            if(p.req_type == '1'){
                html += '<thead><tr>'+chkCol+'<th>#</th><th>الموظف</th><th>المقر</th><th class="text-end">الراتب المحوّل مستحقات</th><th class="text-end">مبلغ الصرف</th></tr></thead><tbody>';
                eligible.forEach(function(r,i){
                    html += '<tr class="wiz-emp-row" data-branch="'+(r.BRANCH_NAME||'')+'" data-emp="'+r.EMP_NO+' '+(r.EMP_NAME||'').toLowerCase()+'">';
                    html += '<td class="text-center"><input type="checkbox" class="wiz-emp-chk" value="'+r.EMP_NO+'" data-amt="'+(r.CALC_AMOUNT||0)+'" checked onchange="wizUpdateSelected()"></td>';
                    html += '<td>'+(i+1)+'</td><td>'+r.EMP_NO+' - '+(r.EMP_NAME||'')+'</td>';
                    html += '<td>'+(r.BRANCH_NAME||'')+'</td>';
                    html += '<td class="text-end">'+fmtA(r.CON_323_AMOUNT||0)+'</td>';
                    html += '<td class="text-end fw-bold text-primary">'+fmtA(r.CALC_AMOUNT||0)+'</td></tr>';
                });
            } else if(p.req_type == '2'){
                // شرح توضيحي للأعمدة
                html += '<div class="alert alert-info py-2 mb-2" style="font-size:.78rem;background:#eff6ff;border-color:#bfdbfe;color:#1e40af">';
                html += '<i class="fa fa-info-circle"></i> <b>توضيح الأعمدة:</b>';
                html += ' <span class="badge" style="background:#e0e7ff;color:#3730a3">الصافي</span> الراتب الإجمالي للشهر';
                html += ' <span class="badge" style="background:#fef3c7;color:#92400e">'+p.percent_val+'%</span> ناتج ضرب الصافي × النسبة';
                html += ' <span class="badge" style="background:#dbeafe;color:#1e40af">مبلغ الصرف</span> المبلغ الفعلي المدفوع (بعد تطبيق الحدود)';
                html += ' <span class="badge" style="background:#f3e8ff;color:#6b21a8">سيُرحّل كمستحقات</span> المتبقي من الراتب الذي سيُرحّل إلى بند المستحقات (323)';
                html += '</div>';
                html += '<thead><tr>'+chkCol+'<th>#</th><th>الموظف</th><th>المقر</th>';
                html += '<th class="text-end" title="الراتب الإجمالي للشهر">الصافي</th>';
                html += '<th class="text-end" title="الصافي × '+p.percent_val+'% (قبل تطبيق الحدود)" style="background:#fef3c7">'+p.percent_val+'% من الصافي</th>';
                html += '<th class="text-end" title="المبلغ الفعلي المدفوع بعد تطبيق الحدود (الحد الأدنى/الأعلى)" style="background:#dbeafe">مبلغ الصرف</th>';
                html += '<th class="text-end" title="المبلغ الذي سيُرحّل إلى بند المستحقات (323)" style="background:#f3e8ff">سيُرحّل مستحقات</th>';
                html += '</tr></thead><tbody>';
                eligible.forEach(function(r,i){
                    var netSalary = parseFloat(r.NET_SALARY||0);       // الصافي الإجمالي
                    var accrued323 = parseFloat(r.CON_323_AMOUNT||0);  // المحجوز (ACCRUED_323)
                    var calc = parseFloat(r.CALC_AMOUNT||0);           // المبلغ المصروف (CAPPED)
                    var rawPct = Math.round(netSalary*parseFloat(p.percent_val||0)/100*100)/100;  // قيمة النسبة الخام
                    var lfBadge = '';
                    if(r.LIMIT_FLAG === 'MIN') lfBadge = ' <span style="background:#fef3c7;color:#92400e;padding:1px 5px;border-radius:4px;font-size:.65rem;font-weight:600" title="ارتفع للحد الأدنى">حد أدنى</span>';
                    else if(r.LIMIT_FLAG === 'MAX') lfBadge = ' <span style="background:#fee2e2;color:#991b1b;padding:1px 5px;border-radius:4px;font-size:.65rem;font-weight:600" title="انخفض للحد الأعلى">حد أعلى</span>';
                    else if(r.LIMIT_FLAG === 'CAP') lfBadge = ' <span style="background:#dbeafe;color:#1e40af;padding:1px 5px;border-radius:4px;font-size:.65rem;font-weight:600">كامل</span>';
                    html += '<tr><td class="text-center"><input type="checkbox" class="wiz-emp-chk" value="'+r.EMP_NO+'" data-amt="'+calc+'" checked onchange="wizUpdateSelected()"></td>';
                    html += '<td>'+(i+1)+'</td><td>'+r.EMP_NO+' - '+(r.EMP_NAME||'')+'</td>';
                    html += '<td>'+(r.BRANCH_NAME||'')+'</td>';
                    html += '<td class="text-end">'+fmtA(netSalary)+'</td>';
                    html += '<td class="text-end" style="color:#92400e;background:#fffbeb">'+fmtA(rawPct)+'</td>';
                    html += '<td class="text-end fw-bold" style="color:#1e40af;background:#eff6ff">'+fmtA(calc)+lfBadge+'</td>';
                    html += '<td class="text-end" style="color:#6b21a8;background:#faf5ff">'+fmtA(accrued323)+'</td></tr>';
                });
            } else if(p.req_type == '3'){
                html += '<thead><tr>'+chkCol+'<th>#</th><th>الموظف</th><th>المقر</th><th class="text-end">الرصيد المتاح</th><th class="text-end">مبلغ الصرف</th><th class="text-end">المتبقي بعد الصرف</th></tr></thead><tbody>';
                eligible.forEach(function(r,i){
                    var avail = parseFloat(r.SALARY_AVAILABLE||r.CON_323_AMOUNT||0);
                    var calc = parseFloat(r.CALC_AMOUNT||0);
                    html += '<tr class="wiz-emp-row" data-branch="'+(r.BRANCH_NAME||'')+'" data-emp="'+r.EMP_NO+' '+(r.EMP_NAME||'').toLowerCase()+'">';
                    html += '<td class="text-center"><input type="checkbox" class="wiz-emp-chk" value="'+r.EMP_NO+'" data-amt="'+calc+'" checked onchange="wizUpdateSelected()"></td>';
                    html += '<td>'+(i+1)+'</td><td>'+r.EMP_NO+' - '+(r.EMP_NAME||'')+'</td>';
                    html += '<td>'+(r.BRANCH_NAME||'')+'</td>';
                    html += '<td class="text-end" style="color:#059669">'+fmtA(avail)+'</td>';
                    html += '<td class="text-end fw-bold text-primary">'+fmtA(calc)+'</td>';
                    html += '<td class="text-end" style="color:#94a3b8">'+fmtA(avail - calc)+'</td></tr>';
                });
            } else if(p.req_type == '4'){
                html += '<thead><tr>'+chkCol+'<th>#</th><th>الموظف</th><th>المقر</th><th class="text-end">مستحقات بند 323 للشهر</th><th class="text-end">مسجّل بطلبات أخرى</th><th class="text-end">المتبقي للصرف</th></tr></thead><tbody>';
                eligible.forEach(function(r,i){
                    var total323 = parseFloat(r.CON_323_AMOUNT||0);
                    var remaining = parseFloat(r.CALC_AMOUNT||0);
                    var alreadyPaid = total323 - remaining;
                    html += '<tr class="wiz-emp-row" data-branch="'+(r.BRANCH_NAME||'')+'" data-emp="'+r.EMP_NO+' '+(r.EMP_NAME||'').toLowerCase()+'">';
                    html += '<td class="text-center"><input type="checkbox" class="wiz-emp-chk" value="'+r.EMP_NO+'" data-amt="'+remaining+'" checked onchange="wizUpdateSelected()"></td>';
                    html += '<td>'+(i+1)+'</td><td>'+r.EMP_NO+' - '+(r.EMP_NAME||'')+'</td>';
                    html += '<td>'+(r.BRANCH_NAME||'')+'</td>';
                    html += '<td class="text-end">'+fmtA(total323)+'</td>';
                    html += '<td class="text-end" style="color:#94a3b8">'+fmtA(alreadyPaid)+'</td>';
                    html += '<td class="text-end fw-bold text-primary">'+fmtA(remaining)+'</td></tr>';
                });
            } else if(p.req_type == '5'){
                html += '<thead><tr>'+chkCol+'<th>#</th><th>الموظف</th><th class="text-end">المبلغ</th></tr></thead><tbody>';
                eligible.forEach(function(r,i){
                    html += '<tr class="wiz-emp-row" data-branch="" data-emp="'+r.EMP_NO+' '+(r.EMP_NAME||'').toLowerCase()+'">';
                    html += '<td class="text-center"><input type="checkbox" class="wiz-emp-chk" value="'+r.EMP_NO+'" data-amt="'+(r.CALC_AMOUNT||0)+'" checked onchange="wizUpdateSelected()"></td>';
                    html += '<td>'+(i+1)+'</td><td>'+r.EMP_NO+' - '+(r.EMP_NAME||'')+'</td>';
                    html += '<td class="text-end fw-bold text-primary">'+fmtA(r.CALC_AMOUNT||0)+'</td></tr>';
                });
            }
            // صف الإجماليات — أعمدة حسب النوع لمطابقة الـ thead
            var _totCalc = eligible.reduce(function(s,r){ return s + parseFloat(r.CALC_AMOUNT||0); }, 0);
            var _totBase = eligible.reduce(function(s,r){ return s + parseFloat(r.CON_323_AMOUNT||0); }, 0);
            var _totAvail = eligible.reduce(function(s,r){ return s + parseFloat(r.SALARY_AVAILABLE||0); }, 0);
            html += '</tbody><tfoot><tr class="table-light fw-bold">';
            if(p.req_type == '1'){
                // chk | # | الموظف | المقر | الراتب | مبلغ
                html += '<td></td><td></td><td>الإجمالي ('+eligible.length+' موظف)</td><td></td>';
                html += '<td class="text-end">'+fmtA(_totBase)+'</td>';
                html += '<td class="text-end" style="color:#1e40af" id="wizFooterTotal">'+fmtA(_totCalc)+'</td>';
            } else if(p.req_type == '2'){
                // chk | # | الموظف | المقر | الصافي | % من الصافي | مبلغ الصرف | سيُرحّل مستحقات
                var _totNet = eligible.reduce(function(s,r){ return s + parseFloat(r.NET_SALARY||0); }, 0);
                var _totRawPct = Math.round(_totNet * parseFloat(p.percent_val||0) / 100 * 100) / 100;
                html += '<td></td><td></td><td>الإجمالي ('+eligible.length+' موظف)</td><td></td>';
                html += '<td class="text-end">'+fmtA(_totNet)+'</td>';
                html += '<td class="text-end" style="color:#92400e;background:#fffbeb">'+fmtA(_totRawPct)+'</td>';
                html += '<td class="text-end fw-bold" style="color:#1e40af;background:#eff6ff" id="wizFooterTotal">'+fmtA(_totCalc)+'</td>';
                html += '<td class="text-end" style="color:#6b21a8;background:#faf5ff">'+fmtA(_totBase)+'</td>';
            } else if(p.req_type == '3'){
                // chk | # | الموظف | المقر | الرصيد المتاح | مبلغ | المتبقي
                var _totAvail3 = eligible.reduce(function(s,r){ return s + parseFloat(r.SALARY_AVAILABLE||r.CON_323_AMOUNT||0); }, 0);
                html += '<td></td><td></td><td>الإجمالي ('+eligible.length+' موظف)</td><td></td>';
                html += '<td class="text-end" style="color:#059669">'+fmtA(_totAvail3)+'</td>';
                html += '<td class="text-end" style="color:#1e40af" id="wizFooterTotal">'+fmtA(_totCalc)+'</td>';
                html += '<td class="text-end">'+fmtA(_totAvail3 - _totCalc)+'</td>';
            } else if(p.req_type == '4'){
                // chk | # | الموظف | المقر | بند 323 | مصروف مسبقاً | المتبقي للصرف
                var _totPaid4 = _totBase - _totCalc;
                html += '<td></td><td></td><td>الإجمالي ('+eligible.length+' موظف)</td><td></td>';
                html += '<td class="text-end">'+fmtA(_totBase)+'</td>';
                html += '<td class="text-end" style="color:#94a3b8">'+fmtA(_totPaid4)+'</td>';
                html += '<td class="text-end" style="color:#1e40af" id="wizFooterTotal">'+fmtA(_totCalc)+'</td>';
            } else if(p.req_type == '5'){
                // chk | # | الموظف | مبلغ
                html += '<td></td><td></td><td>الإجمالي ('+eligible.length+' موظف)</td>';
                html += '<td class="text-end" style="color:#1e40af" id="wizFooterTotal">'+fmtA(_totCalc)+'</td>';
            }
            html += '</tr></tfoot>';
            html += '</table></div>';

            // ملخص الحدود (يظهر فقط لو فيه موظفين متأثرين بالحدود)
            var cntMin=0, cntMax=0, cntCap=0, cntNormal=0;
            eligible.forEach(function(r){
                if(r.LIMIT_FLAG === 'MIN') cntMin++;
                else if(r.LIMIT_FLAG === 'MAX') cntMax++;
                else if(r.LIMIT_FLAG === 'CAP') cntCap++;
                else cntNormal++;
            });
            if(cntMin > 0 || cntMax > 0 || cntCap > 0){
                html += '<div style="background:#f8fafc;border-radius:8px;padding:.6rem 1rem;margin-top:.5rem;font-size:.82rem">';
                html += '<strong><i class="fa fa-sliders"></i> تأثير الحدود:</strong> ';
                if(cntNormal > 0) html += '<span style="color:#059669">'+fmtN(cntNormal)+' ضمن الحدود</span> &nbsp; ';
                if(cntMin > 0) html += '<span style="background:#fef3c7;color:#92400e;padding:1px 6px;border-radius:4px">'+fmtN(cntMin)+' حد أدنى</span> &nbsp; ';
                if(cntMax > 0) html += '<span style="background:#fee2e2;color:#991b1b;padding:1px 6px;border-radius:4px">'+fmtN(cntMax)+' حد أعلى</span> &nbsp; ';
                if(cntCap > 0) html += '<span style="background:#dbeafe;color:#1e40af;padding:1px 6px;border-radius:4px">'+fmtN(cntCap)+' كامل</span>';
                html += ' &nbsp; <button class="btn btn-outline-secondary btn-sm" onclick="wizExportLimitFlag()" style="font-size:.72rem"><i class="fa fa-file-excel-o"></i> تصدير المتأثرين</button>';
                window._wizLimitRows = eligible.filter(function(r){ return r.LIMIT_FLAG; });
                html += '</div>';
            }
        }

        // المستبعدين
        if(noAmt.length > 0){
            html += '<details class="mt-3"><summary class="text-danger" style="cursor:pointer;font-size:.85rem">';
            html += '<i class="fa fa-exclamation-triangle me-1"></i> <strong>'+fmtN(noAmt.length)+' موظف مستبعد</strong>';
            html += '</summary>';
            html += '<div class="table-responsive mt-2" style="max-height:200px;overflow-y:auto">';
            html += '<table class="table table-bordered table-sm table-striped">';
            html += '<thead><tr><th>#</th><th>الموظف</th><th>المقر</th><th class="text-end">صافي الراتب</th><th>السبب</th></tr></thead><tbody>';
            noAmt.forEach(function(r,i){
                html += '<tr class="table-warning"><td>'+(i+1)+'</td>';
                html += '<td>'+r.EMP_NO+' - '+(r.EMP_NAME||'')+'</td>';
                html += '<td>'+(r.BRANCH_NAME||'')+'</td>';
                html += '<td class="text-end">'+fmtA(r.NET_SALARY||0)+'</td>';
                html += '<td class="text-danger">'+(r.SKIP_REASON||'لا يوجد رصيد')+'</td></tr>';
            });
            html += '</tbody></table></div></details>';
        }

        // الموجود مسبقاً (مرحّل)
        if(skipped.length > 0){
            html += '<details class="mt-2"><summary class="text-warning" style="cursor:pointer;font-size:.85rem">';
            html += '<i class="fa fa-exclamation-circle me-1"></i> <strong>'+fmtN(skipped.length)+' موظف موجود مسبقاً</strong> — اضغط لعرض التفاصيل';
            html += '</summary>';
            html += '<div class="table-responsive mt-2" style="max-height:200px;overflow-y:auto">';
            html += '<table class="table table-bordered table-sm table-striped">';
            html += '<thead><tr><th>#</th><th>الموظف</th><th>المقر</th></tr></thead><tbody>';
            skipped.forEach(function(r,i){
                html += '<tr><td>'+(i+1)+'</td>';
                html += '<td>'+r.EMP_NO+' - '+(r.EMP_NAME||'')+'</td>';
                html += '<td>'+(r.BRANCH_NAME||'')+'</td></tr>';
            });
            html += '</tbody></table></div></details>';
        }

        $('#wiz_preview_content').html(html);
        if(eligible.length > 0){
            $('#wiz_step3_buttons').show();
            _wizSelectedCount = eligible.length;
            _wizSelectedAmt = totalAmt;
        }
    }

    function wizBackToSettings(){
        $('#wiz_step3').slideUp(200);
        $('#wiz_type_section').slideDown(200);
        $('#wiz_settings').slideDown(200);
        $('#wiz_choice').show();
        $('#wiz_pick_emps, #wiz_pick_excel').hide();

        $('#wiz_preview_content').html('');
        $('#wiz_step3_buttons').hide();
    }

    // ═══ تحديد / إلغاء الكل ═══
    function wizToggleAll(el){
        var checked = el.checked;
        $('.wiz-emp-chk').prop('checked', checked);
        $('#wizChkAll').prop('checked', checked);
        // sync header checkbox
        $('#wizPreviewTable thead input[type=checkbox]').prop('checked', checked);
        wizUpdateSelected();
    }

    function wizUpdateSelected(){
        var total = $('.wiz-emp-chk:visible').length;
        var checked = $('.wiz-emp-chk:visible:checked').length;
        var totalAll = $('.wiz-emp-chk').length;
        var checkedAll = $('.wiz-emp-chk:checked').length;
        var sum = 0;
        $('.wiz-emp-chk:checked').each(function(){ sum += parseFloat($(this).data('amt')||0); });
        $('#wizSelectedCount').text(checkedAll + ' / ' + totalAll + ' محدد');
        $('#wizChkAll').prop('checked', checkedAll === totalAll);
        _wizSelectedCount = checkedAll;
        _wizSelectedAmt = sum;
        // تحديث إجمالي الصرف بالكروت والفوتر
        var fmtA = function(n){ return Number(n).toLocaleString('en-US',{minimumFractionDigits:2,maximumFractionDigits:2}); };
        if($('#wizFooterTotal').length) $('#wizFooterTotal').text(fmtA(sum));
    }
    var _wizSelectedCount = 0;
    var _wizSelectedAmt = 0;

    function wizFilterTable(){
        var search = ($('#wizSearchEmp').val() || '').toLowerCase();
        var branch = ($('#wizFilterBranch').val() || '');
        $('#wizPreviewTable tbody tr.wiz-emp-row').each(function(){
            var empText = ($(this).data('emp') || '').toLowerCase();
            var rowBranch = $(this).data('branch') || '';
            var matchSearch = !search || empText.indexOf(search) > -1;
            var matchBranch = !branch || rowBranch === branch;
            $(this).toggle(matchSearch && matchBranch);
        });
    }

    function wizExportLimitFlag(){
        var rows = window._wizLimitRows || [];
        if(rows.length === 0){ danger_msg('تحذير','لا يوجد متأثرين بالحدود'); return; }
        var flagNames = {'MIN':'حد أدنى','MAX':'حد أعلى','CAP':'كامل المستحقات'};
        var data = [['رقم الموظف','اسم الموظف','المقر','المستحقات','مبلغ الصرف','المتبقي','الحالة']];
        rows.forEach(function(r){
            var con323 = parseFloat(r.CON_323_AMOUNT||0);
            var calc = parseFloat(r.CALC_AMOUNT||0);
            data.push([parseInt(r.EMP_NO)||r.EMP_NO, r.EMP_NAME||'', r.BRANCH_NAME||'', con323, calc, con323-calc, flagNames[r.LIMIT_FLAG]||'']);
        });
        var wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, XLSX.utils.aoa_to_sheet(data), 'متأثرين بالحدود');
        XLSX.writeFile(wb, 'متأثرين_بالحدود.xlsx');
    }

    // ═══ إنشاء الطلب ═══
    function wizBulkCreate(btn){
        // حساب الموظفين المحددين
        var selectedEmps = [];
        $('.wiz-emp-chk:checked').each(function(){ selectedEmps.push($(this).val()); });
        if(selectedEmps.length === 0){ danger_msg('تحذير','حدد موظف واحد على الأقل'); return; }

        var allSelected = (selectedEmps.length === _wizEligibleCount);
        var selAmt = 0;
        $('.wiz-emp-chk:checked').each(function(){ selAmt += parseFloat($(this).data('amt')||0); });
        var _fmt = Number(selAmt).toLocaleString('en-US',{minimumFractionDigits:2,maximumFractionDigits:2});
        var nl = String.fromCharCode(10);
        var msg = 'تأكيد إنشاء طلب صرف:'+nl+nl+'عدد الموظفين: '+selectedEmps.length+(allSelected ? ' (الكل)' : ' (محدد)')+nl+'إجمالي المبلغ: '+_fmt+nl+'النوع: '+_wizCalcDesc+nl+nl+'هل تريد المتابعة؟';
        if(!confirm(msg)) return;

        $(btn).prop('disabled',true).html('<i class="fa fa-spinner fa-spin"></i> جاري الإنشاء...');
        var p = _wizParams();

        // bulk_create فقط لو اليوزر اختار "كل الموظفين" من البطاقات (_wizMode === 'all')
        // لو اختار "موظفين محددين" أو "Excel" → دايماً create + detail_add (عشان نضمن نضيف الموظفين اللي حدّدهم)
        // نوع 5 دايماً يمر بالمسار اليدوي
        if(_wizMode === 'all' && allSelected && p.req_type != '5'){
            // كل الموظفين — استخدام bulk_create (إنشاء ماستر + كل الموظفين بضربة)
            get_data(bulkCreateUrl, p, function(resp){
                $(btn).prop('disabled',false).html('<i class="fa fa-check-circle"></i> إنشاء الطلب وإضافة الموظفين');
                try {
                    var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
                    if(!j.ok){ danger_msg('خطأ', j.msg); return; }
                    _wizShowSuccess(j);
                } catch(e){ danger_msg('خطأ', e.message); }
            }, 'json');
        } else {
            // موظفين محددين — إنشاء ماستر أولاً ثم إضافة كل موظف
            // تأكد pay_type منسوخ لنوع 5
            if(p.req_type == '5'){
                var bc = $('#show_benefit_con').val();
                if(!$('#pay_type option[value="'+bc+'"]').length) $('#pay_type').append('<option value="'+bc+'">'+bc+'</option>');
                $('#pay_type').val(bc);
            }
            get_data(postUrl, $('#master_form').serialize(), function(data){
                var str = String(data);
                if(!str.match(/^\d+$/) || parseInt(str) <= 0){
                    $(btn).prop('disabled',false).html('<i class="fa fa-check-circle"></i> إنشاء الطلب وإضافة الموظفين');
                    danger_msg('خطأ في إنشاء الطلب', str);
                    return;
                }
                var newReqId = parseInt(str);
                // إضافة الموظفين المحددين واحد واحد
                _wizAddSelectedEmps(btn, newReqId, selectedEmps, 0, 0, []);
            }, 'html');
        }
    }

    function _wizAddSelectedEmps(btn, reqId, emps, idx, ok, fails){
        if(idx >= emps.length){
            // 🆕 لو فيه ملف إكسل مرفوع، نحفظه كـ attachment على الطلب
            // (يحدث قبل ما نعرض النجاح ونوجه المستخدم)
            var afterAttach = function(){
                $(btn).prop('disabled',false).html('<i class="fa fa-check-circle"></i> إنشاء الطلب وإضافة الموظفين');
                if(fails.length > 0){
                    var failsHtml = '<div class="text-start"><strong>فشل إضافة '+fails.length+' من '+emps.length+' موظف:</strong><ul style="font-size:.82rem;margin:0;padding-inline-start:1.2rem">';
                    for(var f=0; f<Math.min(fails.length, 10); f++){
                        failsHtml += '<li>'+fails[f].emp+': '+fails[f].msg+'</li>';
                    }
                    if(fails.length > 10) failsHtml += '<li>... و'+(fails.length-10)+' أخرى</li>';
                    failsHtml += '</ul></div>';
                    danger_msg(ok > 0 ? 'تم جزئياً' : 'فشل الإضافة', failsHtml);
                }
                if(ok > 0){
                    var j = {ok: true, req_id: reqId, msg: 'تم إنشاء الطلب #'+reqId+' مع '+ok+' موظف'};
                    _wizShowSuccess(j);
                } else {
                    $('#wiz_step3_buttons').show();
                }
            };

            // 🆕 الترتيب: أولاً نرفع الملف ⮕ نحصل على attachment_id ⮕ نحفظ البنود الخام
            //          ⮕ نُكمل بـ afterAttach() اللي يعرض النجاح
            var saveLinesAndDone = function(att_id){
                if(_wizExcelRawLines.length === 0 || reqId <= 0){
                    afterAttach(); return;
                }
                $.ajax({
                    url: '<?= base_url("$MODULE_NAME/$TB_NAME/public_save_import_lines_for_req") ?>',
                    method: 'POST',
                    data: { req_id: reqId, lines: JSON.stringify(_wizExcelRawLines), attachment_id: att_id || '' },
                    dataType: 'json'
                }).always(function(){
                    _wizExcelRawLines = [];   // امسحه
                    afterAttach();
                });
            };

            if(_wizUploadedFile && reqId > 0){
                var fd = new FormData();
                fd.append('req_id', reqId);
                fd.append('excel_file', _wizUploadedFile);
                $.ajax({
                    url: '<?= base_url("$MODULE_NAME/$TB_NAME/public_save_import_file_for_req") ?>',
                    method: 'POST', data: fd, processData: false, contentType: false,
                    dataType: 'json'
                }).done(function(resp){
                    var att = (resp && resp.attachment_id) ? parseInt(resp.attachment_id) : 0;
                    _wizUploadedFile = null;
                    saveLinesAndDone(att);
                }).fail(function(){
                    _wizUploadedFile = null;
                    saveLinesAndDone(0);   // كمل حفظ البنود حتى لو فشل رفع الملف
                });
            } else {
                saveLinesAndDone(0);
            }
            return;
        }
        var empNo = emps[idx];
        // أولوية المبلغ: يدوي (من الجدول/إكسل) → محتسب (من المعاينة)
        var amt = 0;
        if(_wizPickedAmounts[String(empNo)]) amt = _wizPickedAmounts[String(empNo)];
        else {
            var excelRow = _wizExcelEmps.filter(function(r){ return String(r.emp_no) === String(empNo); })[0];
            if(excelRow && excelRow.amount > 0) amt = excelRow.amount;
            else {
                var empData = _wizEligible.filter(function(r){ return String(r.EMP_NO) === String(empNo); })[0];
                if(empData) amt = parseFloat(empData.CALC_AMOUNT||0);
            }
        }

        get_data(detailAddUrl, {req_id: reqId, emp_no: empNo, req_amount: amt, note: ''}, function(resp){
            try {
                var jr = (typeof resp === 'string') ? JSON.parse(resp) : resp;
                if(jr.ok) ok++;
                else fails.push({emp: empNo, msg: jr.msg});
            } catch(e){ fails.push({emp: empNo, msg: 'خطأ'}); }
            _wizAddSelectedEmps(btn, reqId, emps, idx+1, ok, fails);
        }, 'json');
    }

    function _wizShowSuccess(j){
        if(j.req_id){
            var viewUrl = postUrl.replace('/create', '/get/') + j.req_id;
            success_msg('تم', j.msg || ('تم إنشاء الطلب #' + j.req_id));
            // توجيه مباشر لصفحة التفاصيل
            setTimeout(function(){ get_to_link(viewUrl); }, 500);
        } else {
            danger_msg('خطأ', j.msg || 'لم يتم إنشاء الطلب');
            $('#wiz_step3_buttons').show();
        }
    }

    function wizExportPreview(){
        if(!_wizEligible || _wizEligible.length === 0){ danger_msg('تحذير','لا يوجد بيانات'); return; }
        var p = _wizParams();
        var data = [];
        if(p.req_type == '1'){
            data.push(['#','رقم الموظف','اسم الموظف','المقر','الراتب المحوّل مستحقات','مبلغ الصرف']);
            _wizEligible.forEach(function(r,i){
                data.push([i+1, parseInt(r.EMP_NO)||r.EMP_NO, r.EMP_NAME||'', r.BRANCH_NAME||'',
                    parseFloat(r.CON_323_AMOUNT||0), parseFloat(r.CALC_AMOUNT||0)]);
            });
        } else if(p.req_type == '2'){
            data.push(['#','رقم الموظف','اسم الموظف','المقر','الصافي',p.percent_val+'% من الصافي','مبلغ الصرف','سيُرحّل مستحقات (323)','الحالة']);
            _wizEligible.forEach(function(r,i){
                var netSalary = parseFloat(r.NET_SALARY||0);
                var accrued323 = parseFloat(r.CON_323_AMOUNT||0);
                var calc = parseFloat(r.CALC_AMOUNT||0);
                var rawPct = Math.round(netSalary*parseFloat(p.percent_val||0)/100*100)/100;
                data.push([i+1, parseInt(r.EMP_NO)||r.EMP_NO, r.EMP_NAME||'', r.BRANCH_NAME||'',
                    netSalary, rawPct, calc, accrued323,
                    r.LIMIT_FLAG||'']);
            });
        } else if(p.req_type == '3'){
            data.push(['#','رقم الموظف','اسم الموظف','المقر','رصيد المستحقات','المتاح للصرف','مبلغ الصرف']);
            _wizEligible.forEach(function(r,i){
                data.push([i+1, parseInt(r.EMP_NO)||r.EMP_NO, r.EMP_NAME||'', r.BRANCH_NAME||'',
                    parseFloat(r.CON_323_AMOUNT||0), parseFloat(r.SALARY_AVAILABLE||0), parseFloat(r.CALC_AMOUNT||0)]);
            });
        } else {
            data.push(['#','رقم الموظف','اسم الموظف','المقر','مبلغ الصرف']);
            _wizEligible.forEach(function(r,i){
                data.push([i+1, parseInt(r.EMP_NO)||r.EMP_NO, r.EMP_NAME||'', r.BRANCH_NAME||'',
                    parseFloat(r.CALC_AMOUNT||0)]);
            });
        }
        var total = _wizEligible.reduce(function(s,r){ return s + parseFloat(r.CALC_AMOUNT||0); }, 0);
        data.push(['','','','الإجمالي', total]);
        var wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, XLSX.utils.aoa_to_sheet(data), 'معاينة');
        XLSX.writeFile(wb, 'معاينة_صرف_'+p.the_month+'.xlsx');
    }

    // ═══════════════════════════════════════════════════════════
    // Tab 2: المستفيدون — Lazy Build + البحث + الفلترة + التصدير
    // ═══════════════════════════════════════════════════════════
    var _rcpSearchTimer;
    var _rcpBuilt = false;
    var _rcpData = null;

    // قراءة بيانات Tab 2 من JSON (مرة واحدة)
    function _rcpLoadData(){
        if (_rcpData !== null) return _rcpData;
        var el = document.getElementById('rcpData');
        if (!el) return [];
        try {
            _rcpData = JSON.parse(el.textContent || el.innerText || '[]');
        } catch(e) {
            _rcpData = [];
        }
        return _rcpData;
    }

    // بناء الـ rows دفعة واحدة (HTML string concatenation = أسرع طريقة)
    function _rcpBuildRows(){
        if (_rcpBuilt) return;
        var data = _rcpLoadData();
        var $body = $('#recipientsBody');
        if (!data || data.length === 0) {
            $body.html('<tr><td colspan="10" class="text-center py-4 text-muted">' +
                '<i class="fa fa-info-circle"></i> لا يوجد حركات صرف للعرض</td></tr>');
            _rcpBuilt = true;
            return;
        }

        // إظهار رسالة بناء (لكميات كبيرة)
        if (data.length > 200) {
            $body.html('<tr><td colspan="10" class="text-center py-4 text-muted">' +
                '<i class="fa fa-spinner fa-spin"></i> جاري بناء ' + data.length + ' حركة...</td></tr>');
        }

        // نبني HTML في setTimeout لتفادي تجميد الـ UI
        setTimeout(function(){
            var html = '';
            for (var i = 0; i < data.length; i++) {
                var r = data[i];
                var idx = i + 1;
                var isWallet = r.provider_type === 2;
                var ibanShort = r.iban ? (r.iban.substring(0, 8) + '…' + r.iban.substring(r.iban.length - 4)) : '';
                var search = ((r.emp_no||'') + ' ' + (r.emp_name||'') + ' ' + (r.recipient||'') + ' ' +
                              (r.rel_label||'') + ' ' + (r.provider||'') + ' ' + (r.prov_branch||'') + ' ' +
                              (r.account_no||'') + ' ' + (r.iban||'') + ' ' + (r.split_label||'')).toLowerCase();

                var typeIcon, typeText;
                if (r.type === 'self')      { typeIcon = 'user';       typeText = 'الموظف'; }
                else if (r.type === 'benef'){ typeIcon = 'users';      typeText = 'وريث'; }
                else                        { typeIcon = 'id-card';    typeText = 'صاحب حساب'; }

                html += '<tr class="rcp-row"' +
                    ' data-search="' + _rcpEscape(search) + '"' +
                    ' data-type="' + r.type + '"' +
                    ' data-split="' + r.split_type + '"' +
                    ' data-emp="' + r.emp_no + '">';
                html += '<td class="text-center text-muted">' + idx + '</td>';
                html += '<td><span class="fw-bold">' + r.emp_no + '</span>';
                if (r.emp_is_split) {
                    html += ' <span class="rcp-split-tag" title="' + r.emp_acc_count + ' حسابات لهذا الموظف"><i class="fa fa-sitemap"></i> ' + r.emp_acc_count + '</span>';
                }
                html += '<br><small class="text-muted">' + _rcpEscape(r.emp_name) + '</small></td>';
                html += '<td style="font-size:.78rem">' + _rcpEscape(r.emp_branch || '') + '</td>';
                html += '<td><span class="rcp-type" style="background:' + r.type_bg + ';color:' + r.type_color + '">' +
                        '<i class="fa fa-' + typeIcon + '"></i> ' + typeText + '</span></td>';
                html += '<td><b>' + _rcpEscape(r.recipient) + '</b>';
                if (r.type !== 'self') html += '<br><small class="text-muted">' + _rcpEscape(r.rel_label) + '</small>';
                if (r.is_default)      html += ' <span class="rcp-def">افتراضي</span>';
                html += '</td>';
                html += '<td><i class="fa fa-' + (isWallet ? 'mobile' : 'bank') + '" style="color:' + (isWallet ? '#6d28d9' : '#1e40af') + '"></i> ' + _rcpEscape(r.provider) + '</td>';
                html += '<td style="font-size:.78rem">' + _rcpEscape(r.prov_branch || '—') + '</td>';
                html += '<td style="font-family:monospace;direction:ltr;font-size:.74rem">';
                if (r.account_no) html += '<div>#' + _rcpEscape(r.account_no) + '</div>';
                if (r.iban)       html += '<div title="' + _rcpEscape(r.iban) + '" class="text-muted">' + ibanShort + '</div>';
                html += '</td>';
                html += '<td><span class="rcp-split-pill" style="background:' + r.split_bg + ';color:' + r.split_color + '">' + _rcpEscape(r.split_label) + '</span>';
                if (r.split_type === 2 && r.split_value > 0) {
                    html += '<small class="d-block text-muted" style="font-size:.65rem;direction:ltr">' + _rcpFmt(r.split_value) + '</small>';
                }
                html += '</td>';
                html += '<td class="text-end" style="direction:ltr">';
                html += '<span class="fw-bold" style="color:#059669;font-size:.92rem">' + _rcpFmt(r.amount) + '</span>';
                if (r.emp_is_split && r.share_pct !== null && r.share_pct !== undefined) {
                    html += '<small class="d-block text-muted" style="font-size:.65rem;line-height:1.2;direction:rtl">' +
                            r.share_pct + '% من ' + _rcpFmt(r.emp_req_amount) + '</small>';
                } else if (r.emp_dues > 0) {
                    html += '<small class="d-block text-muted" style="font-size:.62rem;line-height:1.2;direction:rtl" title="مبلغ سيُرحَّل للمستحقات">' +
                            '+ ' + _rcpFmt(r.emp_dues) + ' مستحقات</small>';
                }
                html += '</td>';
                html += '</tr>';
            }
            $body.html(html);
            _rcpBuilt = true;
        }, 30); // setTimeout قصير لإفلات الـ UI thread
    }

    function _rcpEscape(s){
        if (s === null || s === undefined) return '';
        return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    // بناء عند فتح Tab 2 لأول مرة
    $(document).on('shown.bs.tab', '#tab-recipients-btn', function(){
        if (!_rcpBuilt) _rcpBuildRows();
    });

    function _rcpFilter(){
        var q = ($('#rcpSearch').val() || '').trim().toLowerCase();
        var typeFilter = $('#rcpFilterType').val() || '';
        var splitFilter = $('#rcpFilterSplit').val() || '';
        var $rows = $('#recipientsBody tr.rcp-row');
        var visible = 0;
        var totalAmt = 0;

        $rows.each(function(){
            var $r = $(this);
            var search = $r.attr('data-search') || '';
            var type = $r.attr('data-type') || '';
            var split = $r.attr('data-split') || '';
            var matchSearch = !q || search.indexOf(q) !== -1;
            var matchType = !typeFilter || type === typeFilter;
            var matchSplit = !splitFilter || split === splitFilter;
            if (matchSearch && matchType && matchSplit) {
                $r.removeClass('row-hidden');
                visible++;
                // قراءة المبلغ من السطر الأول للعمود الأخير (span.fw-bold)
                var amtTxt = $r.find('td:last .fw-bold').first().text().replace(/[^\d.-]/g, '');
                totalAmt += parseFloat(amtTxt) || 0;
            } else {
                $r.addClass('row-hidden');
            }
        });

        // تحديث إعادة ترقيم
        var idx = 0;
        $rows.not('.row-hidden').each(function(){
            idx++;
            $(this).find('td:first').text(idx);
        });

        // تحديث إجمالي
        $('#rcpTotalCell').text(_rcpFmt(totalAmt));

        // معلومة الفلترة
        if (q || typeFilter || splitFilter) {
            $('#rcpFilterInfo').show().html(
                '<i class="fa fa-filter"></i> عرض <b>' + visible + '</b> من <b>' + $rows.length + '</b> حركة' +
                (q ? ' — البحث: <b>"' + q + '"</b>' : '') +
                ' — مجموع المعروض: <b>' + _rcpFmt(totalAmt) + '</b>'
            );
        } else {
            $('#rcpFilterInfo').hide();
        }
    }

    function _rcpFmt(n){
        return Number(n).toLocaleString('en-US', {minimumFractionDigits:2, maximumFractionDigits:2});
    }

    $(document).on('input', '#rcpSearch', function(){
        clearTimeout(_rcpSearchTimer);
        _rcpSearchTimer = setTimeout(_rcpFilter, 150);
    });
    $(document).on('change', '#rcpFilterType, #rcpFilterSplit', _rcpFilter);
    $(document).on('click', '#rcpSearchClear', function(){
        $('#rcpSearch').val('').trigger('input').focus();
    });

    // تصدير Tab 2 إلى Excel — كل البيانات المفيدة للمحاسب
    $(document).on('click', '#rcpExportBtn', function(){
        if (typeof XLSX === 'undefined') { danger_msg('خطأ', 'مكتبة Excel غير محمّلة'); return; }
        var data = [[
            '#','رقم الموظف','اسم الموظف','المقر',
            'نوع المستلم','المستلم','العلاقة',
            'البنك/المحفظة','فرع البنك','رقم الحساب','IBAN',
            'طريقة التوزيع','حصة من إجمالي صرف الموظف %','إجمالي صرف الموظف',
            'سيُرحَّل للمستحقات','المبلغ المُحوَّل (هذه الحركة)'
        ]];
        var idx = 0; var totalAmt = 0;
        $('#recipientsBody tr.rcp-row').not('.row-hidden').each(function(){
            idx++;
            var $r = $(this);
            var cells = $r.find('td');
            var empNo   = $r.find('td:eq(1) .fw-bold').text().trim();
            var empName = $r.find('td:eq(1) small').text().trim();
            var branch  = cells.eq(2).text().trim();
            var typeText= $r.find('td:eq(3) .rcp-type').text().trim();
            var rcpName = $r.find('td:eq(4) b').text().trim();
            var rcpRel  = $r.find('td:eq(4) small').text().trim();
            var bank    = $r.find('td:eq(5)').text().trim();
            var prov_br = cells.eq(6).text().trim();
            var accDivs = $r.find('td:eq(7) div');
            var accNum  = accDivs.eq(0).text().replace('#','').trim();
            var ibanTxt = accDivs.length > 1 ? (accDivs.eq(1).attr('title') || accDivs.eq(1).text().trim()) : '';
            var splitLabel = $r.find('td:eq(8) .rcp-split-pill').text().trim();
            // المبلغ من span.fw-bold (السطر الأول)
            var amtTxt  = $r.find('td:last .fw-bold').first().text().replace(/[^\d.-]/g, '');
            var amt = parseFloat(amtTxt) || 0;
            // الحصة و الإجمالي و المستحقات (من الـ small text)
            var subTxt  = $r.find('td:last small').text().trim();
            var sharePct = '';
            var empTotal = '';
            var empDues  = '';
            var sharePctMatch = subTxt.match(/(\d+\.?\d*)\s*%/);
            var empTotalMatch = subTxt.match(/من\s+([\d,]+\.?\d*)/);
            var empDuesMatch  = subTxt.match(/\+\s*([\d,]+\.?\d*)\s*مستحقات/);
            if (sharePctMatch) sharePct = sharePctMatch[1] + '%';
            if (empTotalMatch) empTotal = empTotalMatch[1].replace(/,/g,'');
            if (empDuesMatch)  empDues  = empDuesMatch[1].replace(/,/g,'');
            totalAmt += amt;
            data.push([
                idx, empNo, empName, branch,
                typeText, rcpName, rcpRel,
                bank, prov_br, accNum, ibanTxt,
                splitLabel,
                sharePct,
                empTotal ? parseFloat(empTotal) : '',
                empDues  ? parseFloat(empDues)  : '',
                amt
            ]);
        });
        // صف الإجمالي
        var totalRow = new Array(15).fill('');
        totalRow[14] = 'الإجمالي';
        totalRow[15] = totalAmt;
        data.push(totalRow);
        var wb = XLSX.utils.book_new();
        var ws = XLSX.utils.aoa_to_sheet(data);
        // RTL
        if (!ws['!views']) ws['!views'] = [{RTL:true}];
        // عرض الأعمدة
        ws['!cols'] = [
            {wch:5},{wch:10},{wch:28},{wch:14},
            {wch:10},{wch:24},{wch:14},
            {wch:18},{wch:14},{wch:14},{wch:24},
            {wch:14},{wch:10},{wch:14},
            {wch:14},{wch:14}
        ];
        XLSX.utils.book_append_sheet(wb, ws, 'المستفيدون');
        XLSX.writeFile(wb, 'مستفيدون_<?= $rs['REQ_NO'] ?? 'طلب' ?>.xlsx');
    });

// ════════════════════════════════════════════════════════════
// 🆕 المرفقات: رفع + حذف
// ════════════════════════════════════════════════════════════
function openAttachUpload(){
    var m = document.getElementById('attachUploadModal');
    if (!m) return;
    var inst = bootstrap.Modal.getOrCreateInstance(m);
    document.getElementById('attachUploadForm').reset();
    document.getElementById('attachUploadProgress').style.display = 'none';
    inst.show();
}

function submitAttachUpload(){
    var form = document.getElementById('attachUploadForm');
    if (!form.file.files.length) {
        warning_msg('تنبيه', 'اختر ملفاً أولاً');
        return;
    }
    var fd = new FormData(form);
    document.getElementById('attachUploadProgress').style.display = 'block';

    $.ajax({
        url: '<?= base_url("archive/archive/upload_file") ?>',
        type: 'POST',
        data: fd,
        processData: false,
        contentType: false,
        success: function(resp){
            document.getElementById('attachUploadProgress').style.display = 'none';
            var msg = String(resp).trim();
            // الـ archive controller يرجع رقم ID على النجاح، أو نص خطأ على الفشل
            if (/^\d+$/.test(msg) && parseInt(msg) > 0) {
                success_msg('تم', 'تم رفع المرفق');
                setTimeout(function(){ location.reload(); }, 800);
            } else if (msg.indexOf('بنجاح') >= 0) {
                success_msg('تم', 'تم رفع المرفق');
                setTimeout(function(){ location.reload(); }, 800);
            } else {
                danger_msg('خطأ', msg || 'فشل الرفع');
            }
        },
        error: function(){
            document.getElementById('attachUploadProgress').style.display = 'none';
            danger_msg('خطأ', 'فشل في الاتصال بالخادم');
        }
    });
}

function deleteAttachment(id){
    if (!confirm('هل أنت متأكد من حذف هذا المرفق؟')) return;
    get_data('<?= base_url("archive/archive/delete_file") ?>', {id: id}, function(resp){
        var msg = String(resp).trim();
        if (msg === '1') {
            var row = document.getElementById('att_row_' + id);
            if (row) row.remove();
            success_msg('تم', 'تم حذف المرفق');
            setTimeout(function(){ location.reload(); }, 600);
        } else {
            danger_msg('خطأ', msg || 'فشل الحذف');
        }
    }, 'html');
}

</script>
<?php sec_scripts(ob_get_clean()); ?>
