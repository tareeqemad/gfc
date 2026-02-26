<?php
/**
 * Payroll Data - Salary Dues (Create/Edit)
 */
$MODULE_NAME = 'payroll_data';
$TB_NAME     = 'dues';

$get_url       = base_url("$MODULE_NAME/$TB_NAME/get");
$back_url      = base_url("$MODULE_NAME/$TB_NAME");
$post_url      = $isCreate ? base_url("$MODULE_NAME/$TB_NAME/create") : base_url("$MODULE_NAME/$TB_NAME/edit");
$summary_url   = base_url("$MODULE_NAME/$TB_NAME/public_get_summary");
$import_url    = base_url("$MODULE_NAME/$TB_NAME/import_excel_smart");
$template_url  = base_url("$MODULE_NAME/$TB_NAME/download_template");

$HaveRs = isset($master_tb_data) && is_array($master_tb_data) && count($master_tb_data) > 0;
$rs     = $HaveRs ? $master_tb_data[0] : [];

$date_attr = " data-type='date' data-date-format='DD/MM/YYYY' data-val='true' data-val-regex-pattern='" . date_format_exp() . "' data-val-regex='Error' ";
$the_date_display = date('d/m/Y');
if (!$isCreate && $HaveRs && !empty($rs['THE_DATE'])) {
    $the_date_ts = @strtotime(str_replace('/', '-', $rs['THE_DATE']));
    if ($the_date_ts) {
        $the_date_display = date('d/m/Y', $the_date_ts);
    }
}

echo AntiForgeryToken();
?>

    <!-- PAGE-HEADER -->
    <div class="page-header">
        <div>
            <h1 class="page-title"><?= $title ?><?= ($HaveRs ? ' - ' . ($rs['EMP_NO_NAME'] ?? '') : '') ?></h1>
        </div>
        <div class="ms-auto pageheader-btn">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0);">Payroll Data</a></li>
                <li class="breadcrumb-item"><a href="<?= $back_url ?>">تسديد مستحقات الموظفين</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= $title ?></li>
            </ol>
        </div>
    </div>
    <!-- PAGE-HEADER END -->

    <div class="row">
        <div class="col-lg-12">
            <div class="card">

                <!-- Header: زر حفظ بمكان واضح -->
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h3 class="card-title mb-0"><?= $title ?></h3>

                    <div class="card-options d-flex gap-2">
                        <button type="button" class="btn btn-success" data-bs-toggle="collapse" data-bs-target="#excelImportSection" aria-expanded="false">
                            <i class="fa fa-file-excel-o"></i> استيراد من Excel
                        </button>

                        <button type="button" id="btnSaveTop" disabled onclick="javascript:save();" class="btn btn-primary">
                            <i class="fa fa-save"></i> حفظ
                        </button>

                        <a class="btn btn-secondary" href="<?= $back_url ?>">
                            <i class="fa fa-backward"></i> رجوع
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <style>
                        /* تنسيق شجرة نوع البند داخل المودال */
                        .dues-pay-type-tree-wrap { min-height: 200px; max-height: 70vh; overflow-y: auto; padding: 8px; background: #fafbfc; border: 1px solid #e9ecef; border-radius: 8px; }
                        .dues-pay-type-tree-wrap .tree li { margin: 2px 0; }
                        .dues-pay-type-tree-wrap .tree li > span.tree-node { padding: 7px 14px; border-radius: 6px; cursor: pointer; display: inline-block; margin: 3px 0; transition: all 0.2s ease; font-size: 14px; font-weight: 500; color: #333; background: #fff; box-shadow: 0 1px 3px rgba(0,0,0,0.06); }
                        .dues-pay-type-tree-wrap .tree li > span.tree-node.lt-1:hover { background: #eafaf1; box-shadow: 0 2px 8px rgba(39,174,96,0.15); }
                        .dues-pay-type-tree-wrap .tree li > span.tree-node.lt-2:hover { background: #fdedec; box-shadow: 0 2px 8px rgba(192,57,43,0.15); }
                        .dues-pay-type-tree-wrap .tree li > span.lt-1 { border-right: 4px solid #2ecc71; }
                        .dues-pay-type-tree-wrap .tree li > span.lt-1 .tree-icon { color: #27ae60; }
                        .dues-pay-type-tree-wrap .tree li > span.lt-2 { border-right: 4px solid #e74c3c; }
                        .dues-pay-type-tree-wrap .tree li > span.lt-2 .tree-icon { color: #c0392b; }
                        .dues-pay-type-tree-wrap .tree .tree-icon { font-size: 16px; margin-left: 5px; vertical-align: middle; cursor: pointer; }
                        #excelDropZone.excel-dropzone-disabled { background: #e9ecef !important; cursor: not-allowed !important; opacity: 0.85; pointer-events: none; }
                        #excelDropZone.excel-dropzone-enabled { pointer-events: auto; }
                        /* استيراد Excel - إعادة تصميم */
                        .excel-import-card { border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 2px 8px rgba(0,0,0,.06); overflow: hidden; }
                        .excel-import-card .card-header { background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%); border-bottom: 1px solid #bbf7d0; padding: 0.85rem 1.25rem; }
                        .excel-import-step { display: flex; align-items: flex-start; gap: 0.75rem; padding: 0.85rem 1rem; background: #f8fafc; border-radius: 10px; border: 1px solid #e2e8f0; margin-bottom: 1rem; }
                        .excel-import-step-num { width: 28px; height: 28px; border-radius: 50%; background: #0d6efd; color: #fff; font-weight: 700; font-size: 0.85rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
                        .excel-import-dropzone { border: 2px dashed #94a3b8; border-radius: 12px; padding: 2rem 1.5rem; text-align: center; transition: border-color .2s, background .2s; }
                        .excel-import-dropzone:hover { border-color: #64748b; background: #f8fafc; }
                        #excelDropZone.excel-dropzone-enabled .excel-import-dropzone { border-color: #22c55e; background: #f0fdf4; }
                        .excel-import-format-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 1rem; }
                        .excel-import-format-card .table { font-size: 0.875rem; margin-bottom: 0; }
                        .excel-import-format-card .table th, .excel-import-format-card .table td { padding: 0.4rem 0.6rem; }
                        /* بيانات التسديد — لمسة فنية */
                        .dues-form-section { background: #fff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 0; margin-bottom: 1.25rem; overflow: hidden; box-shadow: 0 4px 20px rgba(15, 23, 42, 0.06); }
                        .dues-form-section-title { font-size: 1rem; font-weight: 700; color: #1e293b; margin: 0; padding: 1rem 1.5rem; background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; gap: 0.75rem; }
                        .dues-form-section-title .title-icon { width: 36px; height: 36px; border-radius: 10px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1rem; box-shadow: 0 2px 8px rgba(59, 130, 246, 0.35); }
                        .dues-form-section .form-body { padding: 1.5rem 1.75rem; }
                        .dues-form-section .form-group { margin-bottom: 0; }
                        .dues-form-section .form-group label { font-weight: 600; color: #334155; font-size: 0.8125rem; margin-bottom: 0.4rem; letter-spacing: 0.01em; }
                        .dues-form-section .form-control { border-radius: 10px; border: 1px solid #e2e8f0; padding: 0.55rem 0.85rem; transition: border-color .2s, box-shadow .2s; }
                        .dues-form-section .form-control:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.12); }
                        .dues-form-section .form-control:hover { border-color: #cbd5e1; }
                        .dues-form-section .input-group .form-control { border-radius: 10px 0 0 10px; }
                        .dues-form-section .input-group .btn { border-radius: 0 10px 10px 0; font-weight: 600; }
                        .dues-form-section .pay-row { background: linear-gradient(135deg, #fffbeb 0%, #fefce8 100%); border: 1px solid #fef08a; border-radius: 12px; padding: 1rem; margin-bottom: 0.5rem; }
                        .dues-form-section .pay-row .form-control { background: #fff; }
                        .dues-form-section .note-row .form-control { border-radius: 10px; background: #f8fafc; }
                        .dues-form-actions { background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%); border-top: 1px solid #e2e8f0; border-radius: 0 0 16px 16px; padding: 1.25rem 1.75rem; margin-top: 0; display: flex; justify-content: flex-end; gap: 0.75rem; flex-wrap: wrap; }
                        .dues-form-actions .btn { min-width: 110px; font-weight: 600; border-radius: 10px; padding: 0.5rem 1rem; }
                        .dues-form-actions .btn-primary { box-shadow: 0 2px 10px rgba(59, 130, 246, 0.3); }
                        /* ملخص المستحقات - نفس فكرة Entitl_deduct_page */
                        .card.dues-summary-card { border-radius: 10px; border: none; box-shadow: 0 2px 12px rgba(0,0,0,.08); overflow: hidden; }
                        .card.dues-summary-card .card-header { font-weight: 700; font-size: 1rem; padding: 0.65rem 1rem; border-bottom: 1px solid rgba(0,0,0,.06); background: #f8fafc !important; }
                        .dues-summary-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 1rem; padding: 1rem; }
                        .dues-summary-item { text-align: center; padding: 0.85rem 0.5rem; border-radius: 8px; background: #f8f9fa; }
                        .dues-summary-item .dues-summary-label { font-size: 0.8rem; color: #5a6c7d; margin-bottom: 0.35rem; }
                        .dues-summary-item .dues-summary-value { font-size: 1.15rem; font-weight: 700; }
                        .dues-summary-item.due-total { background: #e8f4fd; } .dues-summary-item.due-total .dues-summary-value { color: #0d6efd; }
                        .dues-summary-item.add { background: #e8f5e9; } .dues-summary-item.add .dues-summary-value { color: #198754; }
                        .dues-summary-item.ded { background: #ffebee; } .dues-summary-item.ded .dues-summary-value { color: #dc3545; }
                        .dues-summary-item.remain { background: #e3f2fd; } .dues-summary-item.remain .dues-summary-value { color: #0d6efd; font-size: 1.25rem; }
                        .dues-summary-item.pct { background: #f3e5f5; } .dues-summary-item.pct .dues-summary-value { color: #6f42c1; }
                        .dues-summary-item.pct .progress { height: 6px; border-radius: 6px; margin-top: 0.35rem; }
                    </style>
                    <!-- ===== Summary Panel (نفس فكرة Entitl_deduct_page) ===== -->
                    <div id="duesSummaryWrap" class="mb-4" style="display:none;">
                        <div class="card dues-summary-card">
                            <div class="card-header bg-light d-flex align-items-center">
                                <i class="fa fa-pie-chart text-primary me-2"></i>
                                <span>ملخص المستحقات والمسدد</span>
                            </div>
                            <div class="dues-summary-grid">
                                <div class="dues-summary-item due-total">
                                    <div class="dues-summary-label">المستحقات الأساسية</div>
                                    <div class="dues-summary-value" id="sumTotalDue">0.00</div>
                                </div>
                                <div class="dues-summary-item add">
                                    <div class="dues-summary-label">إجمالي الإضافات</div>
                                    <div class="dues-summary-value" id="sumTotalAdd">0.00</div>
                                </div>
                                <div class="dues-summary-item ded">
                                    <div class="dues-summary-label">إجمالي الخصومات</div>
                                    <div class="dues-summary-value" id="sumTotalDed">0.00</div>
                                </div>
                                <div class="dues-summary-item remain">
                                    <div class="dues-summary-label">المتبقي</div>
                                    <div class="dues-summary-value" id="sumBalance">0.00</div>
                                    <div id="zeroBalanceMsg" class="small text-danger mt-1" style="display:none;">لا يوجد رصيد متبقّي</div>
                                </div>
                                <div class="dues-summary-item pct">
                                    <div class="dues-summary-label">نسبة التسديد</div>
                                    <div class="dues-summary-value" id="paidPct">0%</div>
                                    <div class="progress">
                                        <div id="paidProgress" class="progress-bar" role="progressbar" style="width:0%;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- ===== End Summary Panel ===== -->

                    <!-- ===== Excel Import Section (إعادة تصميم) ===== -->
                    <div class="collapse mb-4" id="excelImportSection">
                        <div class="card excel-import-card">
                            <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                                <h6 class="mb-0 d-flex align-items-center gap-2">
                                    <i class="fa fa-file-excel-o text-success"></i>
                                    <span>استيراد دفعات من ملف Excel</span>
                                </h6>
                                <a href="<?= $template_url ?>" class="btn btn-success btn-sm" target="_blank">
                                    <i class="fa fa-download me-1"></i> تحميل القالب
                                </a>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-lg-7">
                                        <div class="excel-import-step">
                                            <span class="excel-import-step-num">1</span>
                                            <div class="flex-grow-1">
                                                <label class="form-label mb-1 fw-600 small text-muted">نوع البند (يُطبّق على كل الصفوف)</label>
                                                <div class="input-group">
                                                    <input type="hidden" name="excel_pay_type" id="excel_pay_type" value="">
                                                    <input type="text" id="excel_pay_type_display" class="form-control bg-white" readonly placeholder="اختر من الشجرة أولاً..."
                                                           value="">
                                                    <button type="button" class="btn btn-primary" id="btn_excel_pay_type_tree" title="فتح شجرة البنود">
                                                        <i class="fa fa-sitemap me-1"></i> اختر من الشجرة
                                                    </button>
                                                </div>
                                                <small id="excelPayTypeChangeHint" class="d-none text-success mt-1"><i class="fa fa-info-circle"></i> يمكنك تغيير النوع في أي وقت.</small>
                                            </div>
                                        </div>
                                        <div class="excel-import-step">
                                            <span class="excel-import-step-num">2</span>
                                            <div class="flex-grow-1 w-100">
                                                <label class="form-label mb-1 fw-600 small text-muted">رفع الملف</label>
                                                <div id="excelDropZone" class="excel-dropzone-disabled" style="cursor: pointer;">
                                                    <input type="file" name="excel_file" id="excel_file" accept=".xlsx,.xls,.csv" class="d-none">
                                                    <div class="excel-import-dropzone" id="excelDropZoneContent">
                                                        <i class="fa fa-cloud-upload fa-3x text-muted mb-2 d-block" style="opacity: 0.8;"></i>
                                                        <p id="excelDropZonePrompt" class="mb-0 text-muted">اختر نوع البند أولاً ثم اسحب الملف هنا أو انقر للاختيار</p>
                                                        <p id="excelFileName" class="mb-0 small text-success fw-bold mt-2" style="display:none;"></p>
                                                    </div>
                                                    <div id="excelUploadProgress" class="d-none text-center py-3">
                                                        <div class="spinner-border text-success mb-2" role="status"></div>
                                                        <p class="mb-0 small text-muted">جاري المعالجة...</p>
                                                    </div>
                                                </div>
                                                <div class="d-flex gap-2 mt-2">
                                                    <button type="button" id="btnDoImport" class="btn btn-success d-none flex-grow-1">
                                                        <i class="fa fa-upload me-1"></i> بدء الاستيراد
                                                    </button>
                                                    <button type="button" id="btnClearFile" class="btn btn-outline-secondary d-none">
                                                        إلغاء
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-5">
                                        <div class="excel-import-format-card h-100">
                                            <p class="fw-600 small text-muted mb-2"><i class="fa fa-table me-1"></i> صيغة الملف</p>
                                            <table class="table table-sm table-bordered">
                                                <thead class="table-light">
                                                    <tr><th>العمود</th><th>الوصف</th><th>مثال</th></tr>
                                                </thead>
                                                <tbody>
                                                    <tr><td>A</td><td>رقم الموظف</td><td>12345</td></tr>
                                                    <tr><td>B</td><td>الشهر (YYYYMM)</td><td><?= date('Ym') ?></td></tr>
                                                    <tr><td>C</td><td>المبلغ</td><td>500.00</td></tr>
                                                    <tr><td>D</td><td>ملاحظات</td><td>—</td></tr>
                                                </tbody>
                                            </table>
                                            <p class="small text-muted mb-1"><i class="fa fa-info-circle me-1"></i> نوع الدفع من الشاشة فقط (لا عمود في الملف).</p>
                                            <p class="small text-muted mb-0">السطر الأول قد يكون عنواناً. حد أقصى 2000 سجل.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <form id="excelImportForm" class="d-none" enctype="multipart/form-data"><?= AntiForgeryToken(); ?></form>
                    <!-- ===== End Excel Import Section ===== -->

                    <form id="<?= $TB_NAME ?>_form" class="manual-input-form">

                        <?php if (!$isCreate && $HaveRs): ?>
                            <input type="hidden" name="serial" id="serial" value="<?= $rs['SERIAL'] ?>">
                        <?php endif; ?>

                        <div class="dues-form-section" id="manualInputFields">
                            <div class="dues-form-section-title">
                                <span class="title-icon"><i class="fa fa-edit"></i></span>
                                <span>بيانات التسديد</span>
                            </div>
                            <div class="form-body">
                                <div class="row g-3">
                                    <div class="form-group col-sm-12 col-md-6 col-lg-3">
                                        <label for="emp_no">
                                            <i class="fa fa-user text-primary me-1" style="font-size:0.75em;"></i> الموظف
                                            <i class="fa fa-question-circle text-muted" data-bs-toggle="tooltip" data-bs-placement="top" title="اختر الموظف من القائمة. يمكنك البحث بالاسم أو الرقم"></i>
                                        </label>
                                        <select name="emp_no" id="emp_no" class="form-control sel2">
                                            <option value="">_________</option>
                                            <?php foreach ($emp_no_cons as $row) : ?>
                                                <?php $sel = (!$isCreate && $HaveRs && ($rs['EMP_NO'] == $row['EMP_NO'])) ? 'selected' : ''; ?>
                                                <option <?= $sel ?> value="<?= $row['EMP_NO'] ?>"><?= $row['EMP_NO'] . ': ' . $row['EMP_NAME'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-sm-6 col-md-4 col-lg-2">
                                        <label for="the_month">
                                            <i class="fa fa-calendar text-primary me-1" style="font-size:0.75em;"></i> الشهر
                                            <i class="fa fa-question-circle text-muted" data-bs-toggle="tooltip" data-bs-placement="top" title="صيغة الشهر: YYYYMM (مثال: 202412)"></i>
                                        </label>
                                        <input type="text" name="the_month" id="the_month" class="form-control" placeholder="YYYYMM"
                                               value="<?= (!$isCreate && $HaveRs) ? ($rs['THE_MONTH'] ?? '') : date('Ym') ?>">
                                    </div>
                                    <div class="form-group col-sm-12 col-md-6 col-lg-4">
                                        <label>
                                            <i class="fa fa-sitemap text-primary me-1" style="font-size:0.75em;"></i> نوع البند
                                            <i class="fa fa-question-circle text-muted" data-bs-toggle="tooltip" data-bs-placement="top" title="حدد نوع البند من الشجرة (إضافة أو خصم)"></i>
                                        </label>
                                        <div class="input-group">
                                            <input type="hidden" name="pay_type" id="pay_type" value="<?= (!$isCreate && $HaveRs) ? ($rs['PAY_TYPE'] ?? '') : '' ?>">
                                            <input type="text" id="pay_type_display" class="form-control bg-white" readonly placeholder="اختر من الشجرة..."
                                                   value="<?= (!$isCreate && $HaveRs) ? (isset($rs['PAY_TYPE_NAME']) ? $rs['PAY_TYPE_NAME'] : '') : '' ?>">
                                            <button type="button" class="btn btn-outline-primary" id="btn_pay_type_tree" title="فتح شجرة البنود">
                                                <i class="fa fa-sitemap"></i> اختر
                                            </button>
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-6 col-md-4 col-lg-2">
                                        <label for="the_date">
                                            <i class="fa fa-calendar-check-o text-primary me-1" style="font-size:0.75em;"></i> تاريخ الحركة
                                        </label>
                                        <input type="text" <?= $date_attr ?> name="the_date" id="the_date" class="form-control"
                                               value="<?= $the_date_display ?>"
                                               title="تاريخ الحركة (dd/mm/yyyy)">
                                    </div>
                                    <div class="form-group col-sm-12 col-md-6 col-lg-4 pay-row">
                                        <label for="pay">
                                            <i class="fa fa-money text-primary me-1" style="font-size:0.75em;"></i> المبلغ
                                            <span class="text-muted fw-normal small ms-1" id="balanceHintWrapper">(متبقي: <span id="balanceHint" class="fw-bold text-warning">-</span>)</span>
                                            <i class="fa fa-question-circle text-muted" data-bs-toggle="tooltip" data-bs-placement="top" title="أدخل المبلغ. يجب ألا يتجاوز المتبقي"></i>
                                        </label>
                                        <div class="input-group">
                                            <input type="number" step="0.01" name="pay" id="pay" class="form-control" placeholder="0.00"
                                                   value="<?= (!$isCreate && $HaveRs) ? ($rs['PAY'] ?? '') : '' ?>">
                                            <button type="button" id="btnFillBalance" class="btn btn-outline-warning" data-bs-toggle="tooltip" title="ملء المبلغ بالمتبقي">
                                                <i class="fa fa-fill"></i> المتبقي
                                            </button>
                                        </div>
                                        <div id="payWarn" class="small text-danger mt-1" style="display:none;">
                                            <i class="fa fa-exclamation-triangle"></i> المبلغ أكبر من المتبقي.
                                        </div>
                                    </div>
                                    <div class="form-group col-12 note-row">
                                        <label for="note">
                                            <i class="fa fa-sticky-note-o text-primary me-1" style="font-size:0.75em;"></i> ملاحظات
                                        </label>
                                        <input type="text" name="note" id="note" class="form-control" placeholder="اختياري..."
                                               value="<?= (!$isCreate && $HaveRs) ? ($rs['NOTE'] ?? '') : '' ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="dues-form-actions">
                                <a class="btn btn-light" href="<?= $back_url ?>">
                                    <i class="fa fa-times"></i> إلغاء
                                </a>
                                <button type="button" id="btnSaveBottom" disabled onclick="javascript:save();" class="btn btn-primary">
                                    <i class="fa fa-save"></i> حفظ
                                </button>
                            </div>
                        </div>

                    </form>

                </div>
            </div>

            <!-- مودال اختيار نوع البند من الشجرة -->
            <div class="modal fade" id="payTypeTreeModal" tabindex="-1" aria-labelledby="payTypeTreeModalLabel" aria-hidden="true" data-bs-backdrop="static">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header py-2">
                            <h5 class="modal-title" id="payTypeTreeModalLabel">
                                <i class="fa fa-sitemap text-primary me-2"></i>اختر نوع البند المراد تسديده
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                        </div>
                        <div class="modal-body p-3">
                            <div id="pay_type_tree_loading" class="text-center py-4 text-muted"><i class="fa fa-spinner fa-spin fa-2x"></i><p class="mt-2 mb-0">جاري تحميل الشجرة...</p></div>
                            <div id="pay_type_tree_wrap" class="dues-pay-type-tree-wrap" style="display:none;">
                                <ul id="pay_type_tree" class="tree list-unstyled mb-0"></ul>
                            </div>
                        </div>
                        <div class="modal-footer py-2">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="importResultModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fa fa-file-excel-o"></i> نتيجة الاستيراد
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div id="importResultBody"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                            <a href="<?= $back_url ?>" class="btn btn-primary" id="btnGoToList">
                                <i class="fa fa-list"></i> الذهاب للقائمة
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
$SUMMARY_URL_JS    = json_encode($summary_url);
$POST_URL_JS       = json_encode($post_url);
$BACK_URL_JS       = json_encode($back_url);
$FORM_ID_JS        = json_encode($TB_NAME . '_form');
$IMPORT_URL_JS     = json_encode($import_url);
$TEMPLATE_URL_JS   = json_encode($template_url);
$edit_pay_type_js  = (!$isCreate && $HaveRs) ? ($rs['PAY_TYPE'] ?? '') : '';
$PAY_TYPE_TREE_URL_JS = isset($pay_type_tree_url) ? json_encode($pay_type_tree_url) : '""';

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
    var formId        = {$FORM_ID_JS};
    var importUrl     = {$IMPORT_URL_JS};
    var templateUrl   = {$TEMPLATE_URL_JS};
    var payTypeTreeUrl = {$PAY_TYPE_TREE_URL_JS};

    var currentBalance = null;
    var selectedExcelFile = null;
    var lastFormData = null; // لتخزين آخر إدخال
    var selectPayTypeForExcel = false; // عند فتح الشجرة من قسم الاستيراد نملأ excel_pay_type
    
    function initTooltips(){
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
    
    function nFormat(x){
        try{
            var n = parseFloat(x);
            if (isNaN(n)) return '0.00';
            return n.toFixed(2);
        }catch(e){
            return '0.00';
        }
    }

    function setSaveEnabled(flag){
        $('#btnSaveTop, #btnSaveBottom').prop('disabled', !flag);
    }

    setSaveEnabled(false);
    
    function showSummaryLoading(){
        $('#duesSummaryWrap').show();
        $('#sumTotalDue,#sumTotalAdd,#sumTotalDed,#sumBalance,#balanceHint').text('...');
        $('#paidProgress').css('width', '0%');
        $('#paidPct').text('0%');
        $('#zeroBalanceMsg').hide();
    }

    // بناء وعرض شجرة نوع البند (بدون EasyUI)
    var payTypeTreeData = null;

    function buildPayTypeTreeHtml(nodes) {
        if (!nodes || nodes.length === 0) return '';
        var html = '';
        for (var i = 0; i < nodes.length; i++) {
            var n = nodes[i];
            var isLeaf = !n.children || n.children.length === 0;
            var hasChildren = n.children && n.children.length > 0;
            var lineType = (n.attributes && n.attributes.lineType) ? n.attributes.lineType : 1;
            var ltClass = 'lt-' + lineType;
            var textEsc = (n.text || '').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
            if (hasChildren) {
                html += '<li class="parent_li" data-id="' + n.id + '" data-text="' + textEsc + '">';
                html += '<span class="tree-node ' + ltClass + ' pay-type-parent" title="توسيع هذا الفرع">';
                html += '<i class="fa fa-plus tree-icon"></i> ' + (n.text || '') + '</span>';
                html += '<ul class="list-unstyled ms-3" style="display:none;">' + buildPayTypeTreeHtml(n.children) + '</ul>';
                html += '</li>';
            } else {
                html += '<li data-id="' + n.id + '" data-text="' + textEsc + '">';
                html += '<span class="tree-node ' + ltClass + ' pay-type-leaf"><i class="fa tree-icon" style="display:inline-block;width:16px;"></i> ' + (n.text || '') + '</span>';
                html += '</li>';
            }
        }
        return html;
    }

    function loadPayTypeTree() {
        var \$loading = $('#pay_type_tree_loading');
        var \$wrap = $('#pay_type_tree_wrap');
        var \$tree = $('#pay_type_tree');
        if (payTypeTreeData) {
            \$loading.hide();
            \$tree.html(buildPayTypeTreeHtml(payTypeTreeData));
            \$wrap.show();
            bindPayTypeTreeEvents();
            return;
        }
        if (!payTypeTreeUrl) return;
        \$loading.show();
        \$wrap.hide();
        \$tree.empty();
        $.get(payTypeTreeUrl).done(function(data) {
            payTypeTreeData = Array.isArray(data) ? data : [];
            \$loading.hide();
            \$tree.html(buildPayTypeTreeHtml(payTypeTreeData));
            \$wrap.show();
            bindPayTypeTreeEvents();
        }).fail(function() {
            \$loading.html('<span class="text-danger">فشل تحميل الشجرة</span>');
        });
    }

    function bindPayTypeTreeEvents() {
        $('#pay_type_tree').off('click', '.pay-type-parent').on('click', '.pay-type-parent', function(e) {
            e.stopPropagation();
            var \$span = $(this);
            var \$li = \$span.closest('li.parent_li');
            var \$ul = \$li.children('ul');
            var \$icon = \$span.find('.tree-icon');
            if (\$ul.is(':visible')) {
                \$ul.slideUp(200);
                \$icon.removeClass('fa-minus').addClass('fa-plus');
                \$span.attr('title', 'توسيع هذا الفرع');
            } else {
                \$ul.slideDown(200);
                \$icon.removeClass('fa-plus').addClass('fa-minus');
                \$span.attr('title', 'طي هذا الفرع');
            }
        });
        $('#pay_type_tree').off('click', '.pay-type-leaf').on('click', '.pay-type-leaf', function(e) {
            e.stopPropagation();
            var \$li = $(this).closest('li');
            var id = \$li.data('id');
            var text = \$li.find('.tree-node').text().trim();
            if (id) selectPayTypeLeaf(id, text);
        });
    }

    function selectPayTypeLeaf(id, text) {
        if (selectPayTypeForExcel) {
            $('#excel_pay_type').val(id);
            $('#excel_pay_type_display').val(text);
            selectPayTypeForExcel = false;
            updateExcelDropZoneState();
        } else {
            $('#pay_type').val(id);
            $('#pay_type_display').val(text);
            validatePay();
        }
        var modal = bootstrap.Modal.getInstance(document.getElementById('payTypeTreeModal'));
        if (modal) modal.hide();
    }

    function validatePay(){
        var empNo = $('#emp_no').val();

        // no employee selected => no save
        if(!empNo){
            $('#pay').removeClass('is-invalid');
            $('#payWarn').hide();
            setSaveEnabled(false);
            return;
        }

        var v = parseFloat($('#pay').val() || '0');
        var bal = (currentBalance === null) ? null : parseFloat(currentBalance);

        // summary not loaded yet
        if (bal === null || isNaN(bal)){
            $('#pay').removeClass('is-invalid');
            $('#payWarn').hide();
            setSaveEnabled(false);
            return;
        }

        if (v > bal){
            $('#pay').addClass('is-invalid');
            $('#payWarn').show();
            setSaveEnabled(false);
        }else{
            $('#pay').removeClass('is-invalid');
            $('#payWarn').hide();
            setSaveEnabled(parseFloat(bal) > 0);
        }
    }

    function applySummaryRow(row){
        var baseDue  = (row && row.TOTAL_BASE_DUE !== undefined) ? row.TOTAL_BASE_DUE : 0;
        var totalAdd = (row && row.TOTAL_ADD !== undefined) ? row.TOTAL_ADD : 0;
        var totalDed = (row && row.TOTAL_DED !== undefined) ? row.TOTAL_DED : 0;
        var bal      = (row && row.TOTAL_BALANCE !== undefined && row.TOTAL_BALANCE !== null)
                     ? row.TOTAL_BALANCE
                     : (parseFloat(baseDue) + parseFloat(totalAdd) - parseFloat(totalDed));

        currentBalance = bal;

        var totalEntitled = parseFloat(baseDue) + parseFloat(totalAdd);

        $('#duesSummaryWrap').show();
        $('#sumTotalDue').text(nFormat(baseDue));
        $('#sumTotalAdd').text(nFormat(totalAdd));
        $('#sumTotalDed').text(nFormat(totalDed));
        $('#sumBalance').text(nFormat(bal));
        $('#balanceHint').text(nFormat(bal));

        var pct = 0;
        if (totalEntitled > 0){
            pct = Math.min(100, Math.max(0, (parseFloat(totalDed) / totalEntitled) * 100));
        }
        $('#paidProgress').css('width', pct.toFixed(1) + '%');
        $('#paidPct').text(pct.toFixed(1) + '%');

        if (parseFloat(bal) <= 0){
            $('#zeroBalanceMsg').show();
            $('#pay').prop('disabled', true);
            setSaveEnabled(false);
        } else {
            $('#zeroBalanceMsg').hide();
            $('#pay').prop('disabled', false);
            setSaveEnabled(true);
        }

        validatePay();
    }

    function loadSummary(){
        var empNo = $('#emp_no').val();

        if(!empNo){
            $('#duesSummaryWrap').hide();
            $('#balanceHint').text('-');
            currentBalance = null;
            return;
        }

        showSummaryLoading();

        $.post(summaryUrl, { emp_no: empNo }, function(resp){
            try{
                var j = resp;
                if(!j || j.ok !== true){
                    applySummaryRow({TOTAL_BASE_DUE:0, TOTAL_ADD:0, TOTAL_DED:0, TOTAL_BALANCE:0});
                    return;
                }

                var rows = j.data || [];
                var r = null;

                if (Array.isArray(rows) && rows.length > 0){
                    r = rows[0];
                } else if (rows && typeof rows === 'object'){
                    r = rows;
                }

                applySummaryRow(r || {TOTAL_BASE_DUE:0, TOTAL_ADD:0, TOTAL_DED:0, TOTAL_BALANCE:0});

                setTimeout(function(){
                    if (!$('#pay').prop('disabled')) $('#pay').focus();
                }, 60);

            }catch(e){
                applySummaryRow({TOTAL_BASE_DUE:0, TOTAL_ADD:0, TOTAL_DED:0, TOTAL_BALANCE:0});
            }
        }, 'json').fail(function(){
            applySummaryRow({TOTAL_BASE_DUE:0, TOTAL_ADD:0, TOTAL_DED:0, TOTAL_BALANCE:0});
        });
    }

    function lockSaveButtons(lock){
        var btns = $('#btnSaveTop, #btnSaveBottom');

        if(lock){
            btns.each(function(){
                var btn = $(this);
                if(!btn.data('old-html')) btn.data('old-html', btn.html());
            });

            btns.prop('disabled', true).addClass('disabled');
            btns.html('<i class="fa fa-spinner fa-spin"></i> جاري الحفظ...');
        }else{
            btns.each(function(){
                var btn = $(this);
                var old = btn.data('old-html');
                if(old) btn.html(old);
            });

            btns.removeClass('disabled');
            validatePay();
        }
    }

    window.save = function(){
        var payTypeVal = $('#pay_type').val() || '';
        if(!payTypeVal){
            if(typeof warning_msg === 'function') warning_msg('تحذير','يجب اختيار نوع البند المراد تسديده');
            return;
        }

        validatePay();
        if ($('#btnSaveTop').prop('disabled')) return;

        lockSaveButtons(true);

        var payload = $('#' + formId).serialize();

        function handleResult(data){
            if (data == '1' || (String(data).match(/^\d+$/) && parseInt(data) > 0)) {
                // حفظ آخر إدخال للنسخ لاحقاً
                lastFormData = {
                    emp_no: $('#emp_no').val(),
                    the_month: $('#the_month').val(),
                    pay_type: $('#pay_type').val(),
                    pay: $('#pay').val(),
                    note: $('#note').val(),
                    the_date: $('#the_date').val()
                };
                
                if (typeof success_msg === 'function') success_msg('رسالة', 'تم الحفظ بنجاح');
                window.location.href = backUrl;
            } else {
                lockSaveButtons(false);
                if (typeof danger_msg === 'function') danger_msg('تحذير', data);
                else alert(data);
            }
        }

        function handleFail(xhr, status, err){
            lockSaveButtons(false);
            var msg = 'فشل الاتصال بالخادم';
            if (xhr && xhr.responseText) msg = xhr.responseText;
            if (typeof danger_msg === 'function') danger_msg('تحذير', msg);
            else alert(msg);
        }

        $.ajax({
            url: postUrl,
            type: 'POST',
            data: payload,
            dataType: 'html'
        }).done(function(data){
            handleResult(data);
        }).fail(function(xhr, status, err){
            handleFail(xhr, status, err);
        });
    };


    function showImportResultModal(resp){
        var inserted = resp.inserted || 0;
        var totalRowsInFile = resp.total_rows_in_file || 0;
        var groupedCount = resp.grouped_count || 0;
        var groupedRecords = resp.grouped_records || [];
        var failed = resp.failed_employees || [];
        var parseErrors = resp.parse_errors || [];
        var insertErrors = resp.insert_errors || [];

        var html = '';
        
        // ملخص سريع
        var totalErrors = parseErrors.length + insertErrors.length + failed.length;
        var successRate = totalRowsInFile > 0 ? ((inserted / totalRowsInFile) * 100).toFixed(1) : 0;
        
        html += '<div class="row mb-3">';
        html += '<div class="col-md-4"><div class="card border-success"><div class="card-body text-center py-2">';
        html += '<h5 class="mb-0 text-success">' + inserted + '</h5><small>سجل تم إدخاله</small>';
        html += '</div></div></div>';
        html += '<div class="col-md-4"><div class="card border-info"><div class="card-body text-center py-2">';
        html += '<h5 class="mb-0 text-info">' + totalRowsInFile + '</h5><small>إجمالي السجلات</small>';
        html += '</div></div></div>';
        html += '<div class="col-md-4"><div class="card border-' + (totalErrors > 0 ? 'danger' : 'secondary') + '"><div class="card-body text-center py-2">';
        html += '<h5 class="mb-0 text-' + (totalErrors > 0 ? 'danger' : 'secondary') + '">' + totalErrors + '</h5><small>أخطاء</small>';
        html += '</div></div></div>';
        html += '</div>';

        if (inserted > 0){
            html += '<div class="alert alert-success mb-3">';
            html += '<i class="fa fa-check-circle"></i> <b>تم إدخال ' + inserted + ' سجل بنجاح</b>';
            if (totalRowsInFile > 0) {
                html += '<br><small>عدد السجلات في الملف: ' + totalRowsInFile + ' | تم تجميع ' + groupedCount + ' مجموعة | نسبة النجاح: ' + successRate + '%</small>';
            }
            html += '</div>';

            // عرض السجلات المجمعة
            if (groupedRecords.length > 0){
                html += '<div class="alert alert-info mb-3">';
                html += '<b><i class="fa fa-info-circle"></i> السجلات التالية تم تجميعها (نفس الموظف + الشهر + نوع الدفع):</b>';
                html += '<div class="table-responsive mt-2"><table class="table table-sm table-bordered">';
                html += '<thead class="table-light"><tr><th>الموظف</th><th>الشهر</th><th>نوع الدفع</th><th>عدد الصفوف</th><th>المبلغ المجمع</th><th>أرقام الصفوف</th></tr></thead><tbody>';
                groupedRecords.forEach(function(gr){
                    html += '<tr>';
                    html += '<td>' + gr.EMP_NO + '</td>';
                    html += '<td>' + gr.THE_MONTH + '</td>';
                    html += '<td>' + gr.PAY_TYPE + '</td>';
                    html += '<td><span class="badge bg-warning">' + gr.ORIGINAL_COUNT + '</span></td>';
                    html += '<td>' + parseFloat(gr.TOTAL_PAY).toFixed(2) + '</td>';
                    html += '<td><small>' + (gr.ORIGINAL_ROWS ? gr.ORIGINAL_ROWS.join(', ') : '') + '</small></td>';
                    html += '</tr>';
                });
                html += '</tbody></table></div>';
                html += '</div>';
            }
        } else if (failed.length || parseErrors.length || insertErrors.length){
            html += '<div class="alert alert-info mb-3"><i class="fa fa-info-circle"></i> لم يتم إدخال أي سجلات. راجع الأخطاء أدناه.</div>';
        }

        if (failed.length){
            html += '<div class="alert alert-warning"><i class="fa fa-exclamation-triangle"></i> الموظفون التاليون لم يُضافوا لأن <b>مجموع الملف يتجاوز المتبقي</b></div>';
            html += '<div class="table-responsive mb-3"><table class="table table-bordered table-sm">';
            html += '<thead class="table-light"><tr><th>الموظف</th><th>إجمالي الملف</th><th>المتبقي</th><th>الصفوف</th></tr></thead><tbody>';
            failed.forEach(function(x){
                var ft = (parseFloat(x.FILE_TOTAL || 0)).toFixed(2);
                var bal = (parseFloat(x.BALANCE || 0)).toFixed(2);
                var rows = (x.ROWS && x.ROWS.length) ? x.ROWS.join(', ') : '-';
                html += '<tr><td>'+x.EMP_NO+'</td><td>'+ft+'</td><td>'+bal+'</td><td>'+rows+'</td></tr>';
            });
            html += '</tbody></table></div>';
        }

        if (parseErrors.length){
            html += '<div class="alert alert-danger mb-3">';
            html += '<b><i class="fa fa-times-circle"></i> أخطاء بالملف (تم تجاهل ' + parseErrors.length + ' خطأ):</b>';
            html += '<div class="table-responsive mt-2" style="max-height:200px; overflow-y:auto;">';
            html += '<table class="table table-sm table-bordered mb-0">';
            html += '<thead class="table-light"><tr><th style="width:20%;">#</th><th>الخطأ</th></tr></thead><tbody>';
            parseErrors.forEach(function(e, idx){
                html += '<tr><td>' + (idx + 1) + '</td><td><small>' + e + '</small></td></tr>';
            });
            html += '</tbody></table></div>';
            html += '</div>';
        }

        if (insertErrors.length){
            html += '<div class="alert alert-danger mb-3">';
            html += '<b><i class="fa fa-times-circle"></i> أخطاء أثناء الإدخال (' + insertErrors.length + ' خطأ):</b>';
            html += '<div class="table-responsive mt-2" style="max-height:200px; overflow-y:auto;">';
            html += '<table class="table table-sm table-bordered mb-0">';
            html += '<thead class="table-light"><tr><th style="width:20%;">#</th><th>الموظف</th><th>الخطأ</th></tr></thead><tbody>';
            insertErrors.forEach(function(e, idx){
                html += '<tr><td>' + (idx + 1) + '</td><td>' + (e.EMP_NO||'') + '</td><td><small>' + (e.MSG||'') + '</small></td></tr>';
            });
            html += '</tbody></table></div>';
            html += '</div>';
        }

        $('#importResultBody').html(html);

        var mEl = document.getElementById('importResultModal');
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal){
            var m = bootstrap.Modal.getOrCreateInstance(mEl);
            m.show();
        } else {
            $(mEl).modal('show');
        }
    }

    function setExcelDropZoneState(loading){
        if (loading){
            $('#excelDropZoneContent').addClass('d-none');
            $('#excelUploadProgress').removeClass('d-none');
        } else {
            $('#excelDropZoneContent').removeClass('d-none');
            $('#excelUploadProgress').addClass('d-none');
        }
    }

    function doExcelImport(file){
        if (!file) return;

        var excelPayType = ($('#excel_pay_type').val() || '').trim();
        if (!excelPayType) {
            if (typeof warning_msg === 'function') {
                warning_msg('تنبيه', 'يجب اختيار نوع البند المراد تسديده من الشجرة أولاً (قسم الاستيراد من Excel)');
            } else {
                alert('يجب اختيار نوع البند المراد تسديده من الشجرة أولاً.');
            }
            return;
        }
        
        // التحقق من نوع الملف
        var fileName = file.name.toLowerCase();
        var validExtensions = ['.xlsx', '.xls', '.csv'];
        var hasValidExtension = validExtensions.some(function(ext){
            return fileName.endsWith(ext);
        });
        
        if (!hasValidExtension){
            if (typeof danger_msg === 'function') {
                danger_msg('تحذير', 'نوع الملف غير مدعوم. يجب أن يكون .xlsx أو .xls أو .csv');
            } else {
                alert('نوع الملف غير مدعوم. يجب أن يكون .xlsx أو .xls أو .csv');
            }
            return;
        }
        
        // التحقق من حجم الملف (10MB)
        var maxSize = 10 * 1024 * 1024; // 10MB
        if (file.size > maxSize){
            if (typeof danger_msg === 'function') {
                danger_msg('تحذير', 'حجم الملف كبير جداً. الحد الأقصى 10MB');
            } else {
                alert('حجم الملف كبير جداً. الحد الأقصى 10MB');
            }
            return;
        }
        
        var fd = new FormData();
        fd.append('excel_file', file);
        fd.append('excel_pay_type', excelPayType);

        var token = $('#excelImportForm').find('input[type=hidden]').first();
        if (token && token.length) fd.append(token.attr('name'), token.val());

        setExcelDropZoneState(true);
        $('#btnDoImport, #btnClearFile').addClass('d-none');

        $.ajax({
            url: importUrl,
            type: 'POST',
            data: fd,
            processData: false,
            contentType: false,
            dataType: 'json'
        }).done(function(resp){
            setExcelDropZoneState(false);
            clearExcelSelection();

            if(!resp || resp.ok !== true){
                var msg = (resp && resp.msg) ? resp.msg : 'فشل الاستيراد';
                if (typeof danger_msg === 'function') danger_msg('تحذير', msg);
                else alert(msg);
                return;
            }

            var inserted = resp.inserted || 0;
            if (inserted > 0){
                if (typeof success_msg === 'function') success_msg('رسالة', 'تم إدخال ' + inserted + ' سجل بنجاح');
                else alert('تم إدخال: ' + inserted);
            }

            if (inserted > 0 || (resp.failed_employees && resp.failed_employees.length) || (resp.parse_errors && resp.parse_errors.length) || (resp.insert_errors && resp.insert_errors.length)){
                showImportResultModal(resp);
            }

            loadSummary();
        }).fail(function(){
            setExcelDropZoneState(false);
            clearExcelSelection();
            if (typeof danger_msg === 'function') danger_msg('تحذير', 'فشل الاتصال بالخادم');
            else alert('فشل الاتصال بالخادم');
        });
    }

    function clearExcelSelection(){
        selectedExcelFile = null;
        $('#excel_file').val('');
        $('#excelFileName').hide().text('');
        $('#btnDoImport, #btnClearFile').addClass('d-none');
        $('#excelPayTypeChangeHint').addClass('d-none');
    }

    function onExcelFileSelected(file){
        if (!file || !file.name) return;
        selectedExcelFile = file;
        $('#excelFileName').text(file.name).show();
        $('#btnDoImport').removeClass('d-none');
        $('#btnClearFile').removeClass('d-none');
        $('#excelPayTypeChangeHint').removeClass('d-none');
    }

    function updateExcelDropZoneState(){
        var hasType = ($('#excel_pay_type').val() || '').trim() !== '';
        var \$zone = $('#excelDropZone');
        var \$prompt = $('#excelDropZonePrompt');
        if (hasType) {
            \$zone.removeClass('excel-dropzone-disabled').addClass('excel-dropzone-enabled');
            \$prompt.html('اسحب الملف هنا أو <span class="text-primary fw-bold">انقر للاختيار</span>');
        } else {
            \$zone.addClass('excel-dropzone-disabled').removeClass('excel-dropzone-enabled');
            \$prompt.html('اختر <strong>نوع البند</strong> أعلاه أولاً ثم اسحب الملف هنا أو انقر للاختيار');
        }
    }

    $(function(){
        if (typeof initFunctions === 'function') initFunctions();

        // Initialize tooltips
        initTooltips();

        setSaveEnabled(false);

        // شجرة نوع البند: فتح المودال (إدخال يدوي)
        $('#btn_pay_type_tree').on('click', function(e) {
            e.preventDefault();
            selectPayTypeForExcel = false;
            loadPayTypeTree();
            var modalEl = document.getElementById('payTypeTreeModal');
            var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();
        });

        // شجرة نوع البند: فتح المودال (للاستيراد من Excel)
        $('#btn_excel_pay_type_tree').on('click', function(e) {
            e.preventDefault();
            selectPayTypeForExcel = true;
            loadPayTypeTree();
            var modalEl = document.getElementById('payTypeTreeModal');
            var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();
        });

        // Excel import - Drop zone
        var dropZone = $('#excelDropZone');
        var fileInput = $('#excel_file');

        dropZone.on('click', function(){ fileInput[0].click(); });

        dropZone.on('dragover', function(e){ e.preventDefault(); e.stopPropagation(); $(this).addClass('border-primary'); });
        dropZone.on('dragleave', function(e){ e.preventDefault(); $(this).removeClass('border-primary'); });
        dropZone.on('drop', function(e){
            e.preventDefault();
            e.stopPropagation();
            $(this).removeClass('border-primary');
            var files = e.originalEvent.dataTransfer && e.originalEvent.dataTransfer.files;
            if (files && files[0]) onExcelFileSelected(files[0]);
        });

        fileInput.on('change', function(){
            if (this.files && this.files[0]) onExcelFileSelected(this.files[0]);
        });

        $('#btnDoImport').on('click', function(){
            if (selectedExcelFile) doExcelImport(selectedExcelFile);
        });

        $('#btnClearFile').on('click', function(){
            clearExcelSelection();
        });

        $('#emp_no').on('change', loadSummary);
        $('#pay').on('keyup change', validatePay);

        $('#btnFillBalance').on('click', function(){
            if (currentBalance === null) return;
            if (parseFloat(currentBalance) <= 0) return;
            $('#pay').val(nFormat(currentBalance));
            validatePay();
            $('#pay').focus();
        });

        // إخفاء/إظهار حقول الإدخال عند فتح/إغلاق قسم Excel
        $('#excelImportSection').on('show.bs.collapse', function () {
            $('#manualInputFields').slideUp(300);
            $('#btnClearForm, #btnSaveBottom').addClass('d-none');
            updateExcelDropZoneState();
        });

        $('#excelImportSection').on('hide.bs.collapse', function () {
            $('#manualInputFields').slideDown(300);
            $('#btnSaveBottom').removeClass('d-none');
        });

        updateExcelDropZoneState();

        // Keyboard shortcuts
        $(document).on('keydown', function(e){
            // Ctrl+S للحفظ
            if (e.ctrlKey && e.key === 's') {
                e.preventDefault();
                if (!$('#btnSaveTop').prop('disabled')) {
                    save();
                }
            }
            // Esc للإلغاء
            if (e.key === 'Escape' && !$('.modal').hasClass('show')) {
                window.location.href = backUrl;
            }
        });

        loadSummary();
    });

</script>
SCRIPT;

sec_scripts($scripts);
?>