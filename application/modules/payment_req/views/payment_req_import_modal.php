<?php
/**
 * payment_req_import_modal.php
 * Modal استيراد طلبات الصرف من Excel — Server-side batch processing
 * يُضمَّن في payment_req_index.php
 */
$import_preview_url = base_url("payment_req/payment_req/import_preview");
$import_exec_url    = base_url("payment_req/payment_req/import_excel");
$template_url       = base_url("payment_req/payment_req/download_template");
?>

<!-- ======================================================
     IMPORT MODAL
     ====================================================== -->
<div id="impExcelModal" style="
    display:none;position:fixed;inset:0;z-index:9999;
    background:rgba(15,23,42,.55);
    align-items:center;justify-content:center;padding:1rem">

    <div style="
      background:#fff;border-radius:16px;
      width:100%;max-width:860px;max-height:90vh;
      display:flex;flex-direction:column;
      box-shadow:0 24px 80px rgba(15,23,42,.2)">

        <!-- HEADER -->
        <div style="
        display:flex;align-items:center;justify-content:space-between;
        padding:.9rem 1.4rem;
        border-bottom:1px solid #e2e8f0;
        background:linear-gradient(135deg,#1e293b,#334155);
        border-radius:16px 16px 0 0;flex-shrink:0">
            <div style="display:flex;align-items:center;gap:.6rem;color:#fff">
                <div style="width:34px;height:34px;border-radius:10px;background:rgba(255,255,255,.15);
                    display:flex;align-items:center;justify-content:center;font-size:.95rem">
                    <i class="fa fa-upload"></i>
                </div>
                <div>
                    <div style="font-weight:700;font-size:.95rem">استيراد طلبات صرف من Excel</div>
                    <div style="font-size:.72rem;opacity:.7" id="imp-step-label">الخطوة 1 من 3 — إعداد ورفع الملف</div>
                </div>
            </div>
            <!-- STEP BAR -->
            <div style="display:flex;gap:4px;align-items:center">
                <div class="imp-step-num active" id="sn-1">1</div>
                <div class="imp-step-line" id="sl-1"></div>
                <div class="imp-step-num" id="sn-2">2</div>
                <div class="imp-step-line" id="sl-2"></div>
                <div class="imp-step-num" id="sn-3">3</div>
                <button onclick="closeImpExcelModal()"
                        style="margin-right:10px;width:30px;height:30px;border-radius:8px;border:none;
                   background:rgba(255,255,255,.15);color:#fff;cursor:pointer;font-size:1rem;
                   display:flex;align-items:center;justify-content:center">
                    ✕
                </button>
            </div>
        </div>

        <!-- BODY -->
        <div style="overflow-y:auto;flex:1;padding:1.25rem 1.4rem" id="imp-body">

            <!-- ====== STEP 1: الحقول + رفع الملف ====== -->
            <div id="imp-step-1">
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:1rem;margin-bottom:1rem">

                    <!-- الشهر -->
                    <div>
                        <label class="imp-lbl">الشهر <span style="color:#dc2626">*</span></label>
                        <input type="text" id="imp_month" class="imp-inp"
                               placeholder="YYYYMM" maxlength="6"
                               value="<?= date('Ym') ?>">
                        <div class="imp-hint">مثال: <?= date('Ym') ?></div>
                    </div>

                    <!-- نوع الطلب -->
                    <div>
                        <label class="imp-lbl">نوع الطلب <span style="color:#dc2626">*</span></label>
                        <select id="imp_req_type" class="imp-inp">
                            <option value="">— اختر —</option>
                            <?php foreach ($req_type_cons as $r): ?>
                                <option value="<?= $r['CON_NO'] ?>"><?= $r['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- بند المستحقات -->
                    <div id="imp_pay_type_wrap" style="display:none">
                        <label class="imp-lbl">بند المستحقات <span style="color:#dc2626">*</span></label>
                        <select id="imp_pay_type" class="imp-inp">
                            <option value="">— اختر —</option>
                        </select>
                        <div class="imp-hint">يجب أن يكون بند خصم فرعي</div>
                    </div>

                    <!-- ملاحظة مشتركة -->
                    <div style="grid-column:span 2">
                        <label class="imp-lbl">ملاحظة مشتركة</label>
                        <input type="text" id="imp_note" class="imp-inp"
                               placeholder="تطبق على كل الطلبات — اختياري" maxlength="200">
                    </div>

                </div>

                <!-- DROP ZONE -->
                <div id="imp-dropzone"
                     ondragover="event.preventDefault();this.style.borderColor='#2563eb'"
                     ondragleave="this.style.borderColor='#cbd5e1'"
                     ondrop="imp_onDrop(event)"
                     style="
               border:2px dashed #cbd5e1;border-radius:12px;
               padding:1.5rem;text-align:center;cursor:pointer;
               transition:border-color .2s;margin-bottom:1rem">
                    <i class="fa fa-cloud-upload" style="font-size:2rem;color:#94a3b8;display:block;margin-bottom:.5rem"></i>
                    <div style="font-weight:600;color:#334155;margin-bottom:.25rem">اسحب الملف هنا أو اضغط للاختيار</div>
                    <div style="font-size:.78rem;color:#94a3b8">xlsx, xls, csv — حد 10MB</div>
                    <input type="file" id="imp_file" accept=".xlsx,.xls,.csv" style="display:none">
                </div>

                <!-- تنسيق الملف + قالب -->
                <div style="
            background:#f8fafc;border:1px dashed #cbd5e1;border-radius:10px;
            padding:.85rem 1.1rem;display:flex;align-items:center;
            justify-content:space-between;flex-wrap:wrap;gap:.75rem">
                    <div style="font-size:.8rem;color:#475569" id="imp-format-hint">
                        <i class="fa fa-file-excel-o" style="color:#059669"></i>
                        <strong>تنسيق الملف:</strong>
                        العمود A = رقم الموظف &nbsp;|&nbsp; العمود B = المبلغ &nbsp;|&nbsp; العمود C = ملاحظة (اختياري)
                    </div>
                    <a href="<?= $template_url ?>" class="imp-btn-outline" style="font-size:.78rem">
                        <i class="fa fa-download"></i> تحميل القالب
                    </a>
                </div>
            </div>

            <!-- ====== STEP 2: Preview (server validation) ====== -->
            <div id="imp-step-2" style="display:none">

                <!-- Loading -->
                <div id="imp-loading" style="text-align:center;padding:2rem 0">
                    <i class="fa fa-spinner fa-spin fa-2x" style="color:#2563eb"></i>
                    <div style="margin-top:.75rem;font-size:.85rem;color:#475569">جاري رفع الملف والتحقق من البيانات...</div>
                </div>

                <!-- Preview content (hidden until loaded) -->
                <div id="imp-preview-content" style="display:none">
                    <!-- Stats -->
                    <div style="display:flex;gap:.75rem;flex-wrap:wrap;margin-bottom:.75rem">
                        <div class="imp-stat-card" style="border-color:#a7f3d0;background:#f0fdf4">
                            <div class="imp-stat-val" style="color:#059669" id="imp-cnt-ok">0</div>
                            <div class="imp-stat-lbl">صف صالح</div>
                        </div>
                        <div class="imp-stat-card" style="border-color:#fca5a5;background:#fef2f2">
                            <div class="imp-stat-val" style="color:#dc2626" id="imp-cnt-err">0</div>
                            <div class="imp-stat-lbl">صف خاطئ</div>
                        </div>
                        <div class="imp-stat-card" style="border-color:#93c5fd;background:#eff6ff">
                            <div class="imp-stat-val" style="color:#2563eb" id="imp-cnt-total">0</div>
                            <div class="imp-stat-lbl">إجمالي الصفوف</div>
                        </div>
                        <div class="imp-stat-card" style="border-color:#fde68a;background:#fffbeb">
                            <div class="imp-stat-val" style="color:#d97706" id="imp-sum-amount">0</div>
                            <div class="imp-stat-lbl">مجموع المبالغ</div>
                        </div>
                    </div>

                    <!-- Parse errors -->
                    <div id="imp-parse-errors" style="display:none;margin-bottom:.75rem;
                        background:#fef2f2;border:1px solid #fecaca;border-radius:10px;padding:.65rem 1rem">
                        <div style="font-size:.78rem;font-weight:600;color:#dc2626;margin-bottom:.3rem">
                            <i class="fa fa-exclamation-triangle"></i> أخطاء تنسيق في الملف:
                        </div>
                        <div id="imp-parse-errors-list" style="font-size:.75rem;color:#991b1b;max-height:100px;overflow-y:auto"></div>
                    </div>

                    <!-- Preview table -->
                    <div style="border:1px solid #e2e8f0;border-radius:10px;overflow:hidden">
                        <div style="
                display:flex;justify-content:space-between;align-items:center;
                padding:.5rem .85rem;background:#f8fafc;border-bottom:1px solid #e2e8f0">
                            <span style="font-size:.75rem;font-weight:600;color:#64748b">معاينة البيانات</span>
                        </div>
                        <div style="max-height:260px;overflow-y:auto">
                            <table style="width:100%;border-collapse:collapse;font-size:.8rem" id="imp-preview-table">
                                <thead style="position:sticky;top:0;background:#f8fafc">
                                <tr>
                                    <th class="imp-th">صف</th>
                                    <th class="imp-th">رقم الموظف</th>
                                    <th class="imp-th">المبلغ</th>
                                    <th class="imp-th">ملاحظة</th>
                                </tr>
                                </thead>
                                <tbody id="imp-preview-body"></tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>

            <!-- ====== STEP 3: النتيجة ====== -->
            <div id="imp-step-3" style="display:none">
                <!-- Progress -->
                <div id="imp-progress-wrap" style="text-align:center;padding:1.5rem 0">
                    <i class="fa fa-spinner fa-spin fa-2x" style="color:#2563eb"></i>
                    <div style="font-size:.9rem;color:#475569;margin-top:.75rem">جاري إنشاء الطلبات...</div>
                </div>

                <!-- Result -->
                <div id="imp-result" style="display:none">
                    <div style="display:flex;gap:.75rem;flex-wrap:wrap;margin-bottom:1rem">
                        <div class="imp-stat-card" style="border-color:#a7f3d0;background:#f0fdf4">
                            <div class="imp-stat-val" style="color:#059669" id="res-ok">0</div>
                            <div class="imp-stat-lbl">طلب أُنشئ</div>
                        </div>
                        <div class="imp-stat-card" style="border-color:#fca5a5;background:#fef2f2">
                            <div class="imp-stat-val" style="color:#dc2626" id="res-fail">0</div>
                            <div class="imp-stat-lbl">طلب فشل</div>
                        </div>
                    </div>

                    <!-- Insert errors -->
                    <div id="imp-insert-errors-wrap" style="display:none">
                        <div style="
                font-size:.78rem;font-weight:600;color:#dc2626;
                margin-bottom:.4rem">
                            <i class="fa fa-exclamation-triangle"></i> تفاصيل الأخطاء
                        </div>
                        <div style="border:1px solid #fecaca;border-radius:10px;overflow:hidden;max-height:200px;overflow-y:auto">
                            <table style="width:100%;border-collapse:collapse;font-size:.78rem" id="imp-errors-table">
                                <thead style="position:sticky;top:0;background:#fef2f2">
                                <tr>
                                    <th class="imp-th">صف</th>
                                    <th class="imp-th">رقم الموظف</th>
                                    <th class="imp-th">سبب الفشل</th>
                                </tr>
                                </thead>
                                <tbody id="imp-errors-body"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- FOOTER -->
        <div style="
        display:flex;justify-content:space-between;align-items:center;
        padding:.85rem 1.4rem;border-top:1px solid #e2e8f0;
        background:#f8fafc;border-radius:0 0 16px 16px;flex-shrink:0">
            <button id="imp-btn-back" onclick="imp_back()" style="display:none" class="imp-btn-outline">
                <i class="fa fa-arrow-right"></i> رجوع
            </button>
            <div style="flex:1"></div>
            <div style="display:flex;gap:.6rem">
                <button onclick="closeImpExcelModal()" class="imp-btn-outline">إغلاق</button>
                <button id="imp-btn-preview" onclick="imp_uploadPreview()" class="imp-btn-primary" disabled>
                    <i class="fa fa-eye"></i> معاينة
                </button>
                <button id="imp-btn-send" onclick="imp_execute()" class="imp-btn-primary"
                        style="display:none;background:linear-gradient(135deg,#059669,#10b981)">
                    <i class="fa fa-paper-plane"></i> <span id="imp-btn-send-text">إنشاء الطلبات</span>
                </button>
                <button id="imp-btn-done" onclick="imp_done()" class="imp-btn-primary"
                        style="display:none">
                    <i class="fa fa-check"></i> تم
                </button>
            </div>
        </div>

    </div>
</div>

<!-- ======================================================
     STYLES
     ====================================================== -->
<style>
    /* ---- Step bar (numbered) ---- */
    .imp-step-num {
        width: 26px; height: 26px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: .7rem; font-weight: 700; color: rgba(255,255,255,.4);
        background: rgba(255,255,255,.1); border: 2px solid rgba(255,255,255,.2);
        transition: all .3s ease;
    }
    .imp-step-num.active {
        color: #1e293b; background: #fff; border-color: #fff;
        box-shadow: 0 2px 8px rgba(255,255,255,.3);
    }
    .imp-step-num.done {
        color: #fff; background: #22c55e; border-color: #22c55e;
    }
    .imp-step-line {
        width: 22px; height: 2px; background: rgba(255,255,255,.15);
        border-radius: 2px; transition: background .3s ease;
    }
    .imp-step-line.done { background: #22c55e; }

    /* ---- Form ---- */
    .imp-lbl{display:block;font-size:.73rem;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:.4px;margin-bottom:.3rem}
    .imp-inp{width:100%;border:1px solid #e2e8f0;border-radius:9px;padding:.45rem .75rem;font-size:.85rem;
        box-sizing:border-box;transition:border-color .2s;background:#fff;color:#1e293b}
    .imp-inp:focus{outline:none;border-color:#2563eb;box-shadow:0 0 0 3px rgba(37,99,235,.1)}
    .imp-inp.err{border-color:#dc2626;background:#fef2f2}
    .imp-hint{font-size:.68rem;color:#94a3b8;margin-top:.2rem}

    /* ---- Buttons ---- */
    .imp-btn-primary{background:linear-gradient(135deg,#2563eb,#1d4ed8);color:#fff;border:none;
        border-radius:9px;padding:.45rem 1.1rem;font-weight:600;font-size:.83rem;cursor:pointer;transition:.2s}
    .imp-btn-primary:hover{opacity:.9;transform:translateY(-1px)}
    .imp-btn-primary:disabled{opacity:.4;cursor:default;transform:none}
    .imp-btn-outline{background:#fff;color:#475569;border:1px solid #e2e8f0;
        border-radius:9px;padding:.4rem .9rem;font-size:.8rem;cursor:pointer;transition:.15s;
        text-decoration:none;display:inline-flex;align-items:center;gap:.35rem}
    .imp-btn-outline:hover{background:#f1f5f9}

    /* ---- Stats ---- */
    .imp-stat-card{flex:1;min-width:90px;border:1px solid;border-radius:10px;padding:.6rem .85rem;text-align:center}
    .imp-stat-val{font-size:1.4rem;font-weight:800;line-height:1.2}
    .imp-stat-lbl{font-size:.68rem;color:#64748b;margin-top:.15rem}

    /* ---- Table ---- */
    .imp-th{padding:.4rem .65rem;text-align:right;font-size:.7rem;font-weight:600;color:#64748b;
        border-bottom:1px solid #e2e8f0;white-space:nowrap}
    .imp-td{padding:.4rem .65rem;border-bottom:1px solid #f1f5f9;vertical-align:middle}
</style>

<!-- ======================================================
     SCRIPT (no SheetJS dependency)
     ====================================================== -->
<script>
    function _initImportModal($) {
    /* =====================================================
       State
       ===================================================== */
    var IMP = window.IMP = {
        step      : 1,
        file      : null,
        fileToken : '',
        validCount: 0,
    };

    function _impFmtA(n){ return Number(n).toLocaleString('en-US',{minimumFractionDigits:2, maximumFractionDigits:2}); }

    /* =====================================================
       Open / Close
       ===================================================== */
    function openImpExcelModal() {
        imp_reset();
        $('#impExcelModal').css('display','flex');
    }
    function closeImpExcelModal() {
        $('#impExcelModal').hide();
    }

    /* =====================================================
       Step navigation
       ===================================================== */
    function imp_setStep(n) {
        IMP.step = n;
        for (var i = 1; i <= 3; i++) $('#imp-step-' + i).toggle(i === n);

        for (var i = 1; i <= 3; i++) {
            var $num = $('#sn-' + i), $line = $('#sl-' + i);
            $num.removeClass('active done');
            if (i < n)      $num.addClass('done').html('<i class="fa fa-check" style="font-size:.65rem"></i>');
            else if (i === n) $num.addClass('active').text(i);
            else              $num.text(i);
            if ($line.length) $line.toggleClass('done', i < n);
        }

        var labels = [
            'الخطوة 1 من 3 — إعداد ورفع الملف',
            'الخطوة 2 من 3 — معاينة وتحقق',
            'الخطوة 3 من 3 — نتيجة الإنشاء'
        ];
        $('#imp-step-label').text(labels[n - 1]);

        // Buttons
        $('#imp-btn-back').toggle(n === 2);
        $('#imp-btn-preview').toggle(n === 1);
        $('#imp-btn-send').toggle(n === 2 && IMP.validCount > 0);
        $('#imp-btn-done').toggle(n === 3);
    }

    function imp_back() {
        if (IMP.step === 2) imp_setStep(1);
    }
    function imp_done() {
        closeImpExcelModal();
        if (typeof ajax_pager === 'function') ajax_pager(values_search(0));
        else {
            if(typeof refreshDetails === 'function') refreshDetails();
            location.reload();
        }
    }

    /* =====================================================
       Step 1 — Validation
       ===================================================== */
    function imp_validateStep1() {
        var month  = $.trim($('#imp_month').val());
        var rtype  = $('#imp_req_type').val();
        var ptype  = $('#imp_pay_type').val();

        var ok = (month.length === 6 && /^\d{6}$/.test(month) && rtype && IMP.file);
        if ($('#imp_pay_type_wrap').is(':visible')) ok = ok && ptype;

        if (month && (month.length !== 6 || !/^\d{6}$/.test(month))) {
            $('#imp_month').addClass('err');
        } else {
            $('#imp_month').removeClass('err');
        }
        $('#imp-btn-preview').prop('disabled', !ok);
        return ok;
    }

    function imp_onReqTypeChange() {
        var rt = $('#imp_req_type').val();

        // بند المستحقات يظهر دائماً لكل الأنواع
        $('#imp_pay_type_wrap').toggle(!!rt);
        if (rt && $('#imp_pay_type option').length <= 1) imp_loadPayTypes();

        var icon = '<i class="fa fa-file-excel-o" style="color:#059669"></i> <strong>تنسيق الملف:</strong> ';
        if (rt === '1') {
            $('#imp-format-hint').html(icon + 'العمود A = رقم الموظف &nbsp;|&nbsp; العمود B = ملاحظة (اختياري) &nbsp; <span style="color:#059669;font-weight:600">— المبلغ يُحتسب تلقائياً من صافي الراتب</span>');
        } else {
            $('#imp-format-hint').html(icon + 'العمود A = رقم الموظف &nbsp;|&nbsp; العمود B = المبلغ &nbsp;|&nbsp; العمود C = ملاحظة (اختياري)');
        }
        imp_validateStep1();
    }

    function imp_loadPayTypes() {
        var url = "<?= base_url('payroll_data/salary_dues_types/public_get_tree_json') ?>";
        $.getJSON(url, function(data) {
            var $sel = $('#imp_pay_type');
            $sel.html('<option value="">— اختر بند —</option>');
            function _addImpPT(nodes) {
                $.each(nodes || [], function(i, node) {
                    if (node.attributes && node.attributes.isLeaf && node.attributes.lineType == 2) {
                        $sel.append($('<option>', { value: node.id, text: node.text }));
                    }
                    if (node.children) _addImpPT(node.children);
                });
            }
            _addImpPT(data);
        });
    }

    /* =====================================================
       File handling (no SheetJS — just store file reference)
       ===================================================== */
    function imp_onDrop(e) {
        e.preventDefault();
        $('#imp-dropzone').css('border-color', '#cbd5e1');
        var file = e.dataTransfer.files[0];
        if (file) imp_setFile(file);
    }

    function imp_setFile(file) {
        IMP.file = file;
        $('#imp-dropzone').html(
            '<i class="fa fa-file-excel-o" style="font-size:1.5rem;color:#059669;display:block;margin-bottom:.35rem"></i>' +
            '<div style="font-weight:600;color:#334155;font-size:.85rem">' + $('<span>').text(file.name).html() + '</div>' +
            '<div style="font-size:.72rem;color:#94a3b8;margin-top:.2rem">' +
            (file.size/1024).toFixed(1) + ' KB — اضغط لتغيير الملف</div>' +
            '<input type="file" id="imp_file" accept=".xlsx,.xls,.csv" style="display:none">'
        );
        imp_validateStep1();
    }

    /* =====================================================
       Step 2 — Upload & Preview (server-side)
       ===================================================== */
    function imp_uploadPreview() {
        if (!imp_validateStep1()) return;

        imp_setStep(2);
        $('#imp-loading').show();
        $('#imp-preview-content').hide();

        var fd = new FormData();
        fd.append('excel_file', IMP.file);
        fd.append('the_month',   $.trim($('#imp_month').val()));
        fd.append('req_type',    $('#imp_req_type').val());
        fd.append('pay_type',    $('#imp_pay_type').val() || '');
        fd.append('note',        $.trim($('#imp_note').val()));
        if(typeof reqId !== 'undefined' && reqId) fd.append('req_id', reqId);

        var $token = $('input[name="<?= $this->security->get_csrf_token_name() ?>"]');
        if ($token.length) fd.append($token.attr('name'), $token.val());

        $.ajax({
            url: '<?= $import_preview_url ?>',
            type: 'POST',
            data: fd,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(d) {
                $('#imp-loading').hide();
                if (!d.ok) {
                    danger_msg('خطأ', d.msg || 'حدث خطأ');
                    imp_setStep(1);
                    return;
                }
                imp_showPreview(d);
            },
            error: function() {
                $('#imp-loading').hide();
                danger_msg('خطأ', 'فشل الاتصال بالسيرفر');
                imp_setStep(1);
            }
        });
    }

    function imp_showPreview(d) {
        IMP.fileToken  = d.file_token || '';
        IMP.validCount = d.valid_count || 0;

        var isFullSalary = ($('#imp_req_type').val() === '1');

        $('#imp-cnt-ok').text(d.valid_count || 0);
        $('#imp-cnt-err').text(d.error_count || 0);
        $('#imp-cnt-total').text(d.total_rows || 0);

        // Update table header
        var thHtml = '<tr><th class="imp-th">صف</th><th class="imp-th">رقم الموظف</th>';
        if (isFullSalary) thHtml += '<th class="imp-th">اسم الموظف</th><th class="imp-th" style="text-align:end">صافي الراتب</th>';
        else thHtml += '<th class="imp-th" style="text-align:end">المبلغ</th>';
        thHtml += '<th class="imp-th">ملاحظة</th></tr>';
        $('#imp-preview-table thead').html(thHtml);

        var totalAmt = 0;
        var $tbody = $('#imp-preview-body').empty();
        $.each(d.items || [], function(i, it) {
            var amt = parseFloat(it.REQ_AMOUNT) || 0;
            totalAmt += amt;
            var $tr = $('<tr>').append(
                $('<td>', { class:'imp-td', css:{color:'#94a3b8'}, text: it.row }),
                $('<td>', { class:'imp-td', css:{fontWeight:600}, text: it.EMP_NO })
            );
            if (isFullSalary) {
                $tr.append($('<td>', { class:'imp-td', text: it.EMP_NAME || '—' }));
                $tr.append($('<td>', { class:'imp-td', css:{textAlign:'end'}, text: _impFmtA(amt) }));
            } else {
                $tr.append($('<td>', { class:'imp-td', css:{textAlign:'end'}, text: _impFmtA(amt) }));
            }
            $tr.append($('<td>', { class:'imp-td', css:{color:'#64748b',maxWidth:'180px',overflow:'hidden',textOverflow:'ellipsis'}, text: it.NOTE || '—' }));
            $tbody.append($tr);
        });
        $('#imp-sum-amount').text(_impFmtA(totalAmt));

        // Parse errors
        if (d.parse_errors && d.parse_errors.length > 0) {
            var html = '';
            $.each(d.parse_errors, function(i, e) { html += '<div>' + $('<span>').text(e).html() + '</div>'; });
            $('#imp-parse-errors-list').html(html);
            $('#imp-parse-errors').show();
        } else {
            $('#imp-parse-errors').hide();
        }

        $('#imp-preview-content').show();
        $('#imp-btn-send').toggle(IMP.validCount > 0);
        $('#imp-btn-send-text').text('إنشاء ' + IMP.validCount + ' طلب');
    }

    /* =====================================================
       Step 3 — Execute (server-side batch creation)
       ===================================================== */
    function imp_execute() {
        if (!IMP.fileToken || IMP.validCount === 0) return;
        if (!confirm('هل أنت متأكد من إنشاء ' + IMP.validCount + ' طلب؟')) return;

        imp_setStep(3);
        $('#imp-progress-wrap').show();
        $('#imp-result').hide();

        var fd = new FormData();
        fd.append('file_token',  IMP.fileToken);
        fd.append('the_month',   $.trim($('#imp_month').val()));
        fd.append('req_type',    $('#imp_req_type').val());
        fd.append('pay_type',    $('#imp_pay_type').val() || '');
        fd.append('note',        $.trim($('#imp_note').val()));
        if(typeof reqId !== 'undefined' && reqId) fd.append('req_id', reqId);

        var $token = $('input[name="<?= $this->security->get_csrf_token_name() ?>"]');
        if ($token.length) fd.append($token.attr('name'), $token.val());

        $.ajax({
            url: '<?= $import_exec_url ?>',
            type: 'POST',
            data: fd,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(d) {
                $('#imp-progress-wrap').hide();
                $('#imp-result').show();

                if (!d.ok) {
                    danger_msg('خطأ', d.msg || 'حدث خطأ');
                    return;
                }

                $('#res-ok').text(d.inserted || 0);
                $('#res-fail').text((d.insert_errors||[]).length + (d.parse_errors||[]).length);

                var errs = d.insert_errors || [];
                if (errs.length > 0) {
                    $('#imp-insert-errors-wrap').show();
                    var $tbody = $('#imp-errors-body').empty();
                    $.each(errs, function(i, e) {
                        $tbody.append(
                            $('<tr>').append(
                                $('<td>', { class:'imp-td', text: e.row }),
                                $('<td>', { class:'imp-td', css:{fontWeight:600}, text: e.EMP_NO }),
                                $('<td>', { class:'imp-td', css:{color:'#dc2626'}, text: e.msg })
                            )
                        );
                    });
                } else {
                    $('#imp-insert-errors-wrap').hide();
                }

                if ((d.inserted||0) > 0) success_msg('تم', 'تم إنشاء ' + d.inserted + ' طلب بنجاح');
            },
            error: function() {
                $('#imp-progress-wrap').hide();
                danger_msg('خطأ', 'فشل الاتصال بالسيرفر');
            }
        });
    }

    /* =====================================================
       Event bindings
       ===================================================== */
    $(function() {
        $('#imp_month').on('input', imp_validateStep1);
        $('#imp_req_type').on('change', imp_onReqTypeChange);
        $('#imp_pay_type').on('change', imp_validateStep1);

        $(document).on('click', '#imp-dropzone', function() {
            $('#imp_file').trigger('click');
        });
        $(document).on('change', '#imp_file', function() {
            if (this.files && this.files[0]) imp_setFile(this.files[0]);
        });
    });

    /* =====================================================
       Reset
       ===================================================== */
    window.imp_reset = imp_reset;
    window.openImpExcelModal = openImpExcelModal;
    window.closeImpExcelModal = closeImpExcelModal;
    window.imp_back = imp_back;
    window.imp_uploadPreview = imp_uploadPreview;
    window.imp_execute = imp_execute;
    window.imp_done = imp_done;
    window.imp_onDrop = imp_onDrop;
    window.imp_setFile = imp_setFile;

    function imp_reset() {
        IMP = window.IMP = { step:1, file:null, fileToken:'', validCount:0 };
        imp_setStep(1);
        $('#imp-btn-preview').prop('disabled', true);
        imp_validateStep1();
        $('#imp-preview-content').hide();
        $('#imp-parse-errors').hide();
        $('#imp-progress-wrap').show();
        $('#imp-result').hide();
        $('#imp-insert-errors-wrap').hide();
        $('#imp-preview-body').empty();
        $('#imp-errors-body').empty();

        // Reset dropzone
        $('#imp-dropzone').html(
            '<i class="fa fa-cloud-upload" style="font-size:2rem;color:#94a3b8;display:block;margin-bottom:.5rem"></i>' +
            '<div style="font-weight:600;color:#334155;margin-bottom:.25rem">اسحب الملف هنا أو اضغط للاختيار</div>' +
            '<div style="font-size:.78rem;color:#94a3b8">xlsx, xls, csv — حد 10MB</div>' +
            '<input type="file" id="imp_file" accept=".xlsx,.xls,.csv" style="display:none">'
        );
    }
    } // end _initImportModal

    // Initialize after page load (jQuery will be available by then)
    window.addEventListener('load', function() {
        _initImportModal(jQuery);
    });
</script>
