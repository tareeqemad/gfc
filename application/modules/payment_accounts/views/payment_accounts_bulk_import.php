<?php
$MODULE_NAME = 'payment_accounts';
$TB_NAME     = 'payment_accounts';

$back_url     = base_url("$MODULE_NAME/$TB_NAME");
$template_url = base_url("$MODULE_NAME/$TB_NAME/bulk_import_template");
$preview_url  = base_url("$MODULE_NAME/$TB_NAME/bulk_import_preview");
$execute_url  = base_url("$MODULE_NAME/$TB_NAME/bulk_import_execute");

echo AntiForgeryToken();
?>

    <div class="page-header">
        <div><h1 class="page-title"><?= $title ?></h1></div>
        <div class="ms-auto pageheader-btn">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= $back_url ?>">إدارة حسابات الصرف</a></li>
                <li class="breadcrumb-item active">استيراد من Excel</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fa fa-upload me-2 text-primary"></i> استيراد حسابات من ملف Excel</h3>
                    <a href="<?= $back_url ?>" class="ms-auto btn btn-light btn-sm"><i class="fa fa-arrow-right"></i> رجوع</a>
                </div>
                <div class="card-body">

                    <!-- خطوة 1: تحميل القالب -->
                    <div class="bi-step" id="biStep1">
                        <div class="step-num">1</div>
                        <div class="step-body">
                            <h5>حمّل القالب</h5>
                            <p class="text-muted">
                                ابدأ بتحميل قالب Excel الجاهز، عبّيه ببيانات الحسابات اللي تريد استيرادها، ثم ارجع وارفعه.
                            </p>
                            <a href="<?= $template_url ?>" class="btn btn-success">
                                <i class="fa fa-download me-1"></i> تحميل قالب Excel
                            </a>
                            <div class="bi-hint mt-3">
                                <i class="fa fa-info-circle text-info"></i>
                                <strong>أعمدة القالب:</strong>
                                رقم الموظف*، رمز المزود*، رقم الحساب، IBAN، رقم المحفظة، هوية صاحب الحساب،
                                اسم صاحب الحساب، جوال، افتراضي، نوع التوزيع*، قيمة، ترتيب، ملاحظات
                            </div>
                        </div>
                    </div>

                    <!-- خطوة 2: رفع الملف -->
                    <div class="bi-step" id="biStep2">
                        <div class="step-num">2</div>
                        <div class="step-body">
                            <h5>ارفع الملف للمراجعة</h5>
                            <p class="text-muted">سنفحص كل صف ونعرض لك المعاينة قبل الاستيراد الفعلي.</p>
                            <div class="d-flex gap-2 align-items-center">
                                <input type="file" id="biFile" accept=".xlsx,.xls" class="form-control" style="max-width:400px">
                                <button class="btn btn-primary" onclick="javascript:uploadFile();">
                                    <i class="fa fa-search-plus"></i> فحص ومعاينة
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- خطوة 3: المعاينة -->
                    <div class="bi-step" id="biStep3" style="display:none">
                        <div class="step-num">3</div>
                        <div class="step-body">
                            <h5>راجع المعاينة</h5>
                            <div id="biPreviewSummary"></div>
                            <div class="table-responsive mt-2" style="max-height:50vh;overflow:auto">
                                <table class="table table-bordered table-sm" id="biPreviewTable" style="font-size:.78rem">
                                    <thead class="table-light"><tr>
                                        <th style="width:30px">#</th>
                                        <th>الموظف</th>
                                        <th>المزود</th>
                                        <th>رقم الحساب</th>
                                        <th>IBAN</th>
                                        <th>المحفظة</th>
                                        <th>صاحب الحساب</th>
                                        <th>التوزيع</th>
                                        <th>الحالة</th>
                                    </tr></thead>
                                    <tbody id="biPreviewBody"></tbody>
                                </table>
                            </div>
                            <div class="mt-3 d-flex gap-2">
                                <button class="btn btn-success" id="biExecuteBtn" onclick="javascript:executeImport();" disabled>
                                    <i class="fa fa-check"></i> استيراد الصفوف الصحيحة
                                </button>
                                <button class="btn btn-light" onclick="javascript:resetForm();">
                                    <i class="fa fa-refresh"></i> ابدأ من جديد
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- خطوة 4: النتيجة -->
                    <div class="bi-step" id="biStep4" style="display:none">
                        <div class="step-num">✓</div>
                        <div class="step-body">
                            <h5>اكتمل الاستيراد</h5>
                            <div id="biResult"></div>
                            <div class="mt-3 d-flex gap-2">
                                <a href="<?= $back_url ?>" class="btn btn-primary">
                                    <i class="fa fa-users"></i> الذهاب لقائمة الموظفين
                                </a>
                                <button class="btn btn-light" onclick="javascript:resetForm();">
                                    <i class="fa fa-plus"></i> استيراد ملف آخر
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <style>
        .bi-step{display:flex;gap:1rem;padding:1.25rem;border:1px solid #e2e8f0;border-radius:12px;margin-bottom:1rem;background:#fff;align-items:flex-start}
        .bi-step .step-num{flex:0 0 48px;height:48px;border-radius:50%;background:#1e293b;color:#fff;display:flex;align-items:center;justify-content:center;font-size:1.4rem;font-weight:800}
        #biStep4 .step-num{background:#16a34a}
        .bi-step .step-body{flex:1}
        .bi-step h5{margin:0 0 .35rem;font-weight:800;color:#1e293b}
        .bi-hint{background:#eff6ff;border:1px solid #bfdbfe;border-radius:8px;padding:.6rem .85rem;font-size:.78rem;color:#475569}
        .bi-hint i{margin-left:4px}

        .row-valid{background:#f0fdf4}
        .row-invalid{background:#fef2f2}
        .row-duplicate{background:#fffbeb}
        .err-list{font-size:.7rem;color:#991b1b;margin:0;padding-inline-start:.85rem}
        .dup-note{font-size:.7rem;color:#92400e;margin:0;padding-inline-start:.85rem}
    </style>

<?php
$scripts = <<<SCRIPT
<script type="text/javascript">

    var BI_PREVIEW_URL = "{$preview_url}";
    var BI_EXEC_URL    = "{$execute_url}";
    var _previewRows   = [];

    function uploadFile(){
        var file = document.getElementById('biFile').files[0];
        if(!file){ warning_msg('تنبيه', 'اختر ملف Excel أولاً'); return; }

        var fd = new FormData();
        fd.append('file', file);
        fd.append('__AntiForgeryToken', $('input[name="__AntiForgeryToken"]').val() || '');

        showLoading();
        $.ajax({
            url: BI_PREVIEW_URL,
            type: 'POST',
            data: fd,
            processData: false,
            contentType: false,
            success: function(resp){
                HideLoading();
                var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
                if(!j.ok){ danger_msg('خطأ', j.msg); return; }
                _previewRows = j.rows;
                renderPreview(j);
                $('#biStep3').show();
                $('html,body').animate({scrollTop: $('#biStep3').offset().top - 100}, 400);
            },
            error: function(){ HideLoading(); danger_msg('خطأ', 'فشل رفع الملف'); }
        });
    }

    function renderPreview(j){
        var dupCnt = j.duplicates || 0;
        var totalText = '<b>' + j.total + ' صف</b> · <span class="text-success">' + j.valid + ' صحيح</span>';
        if(dupCnt > 0)   totalText += ' · <span class="text-warning"><b>' + dupCnt + ' مكرر (سيتم تخطيه)</b></span>';
        if(j.errors > 0) totalText += ' · <span class="text-danger">' + j.errors + ' فيه أخطاء</span>';
        $('#biPreviewSummary').html('<div class="alert alert-light py-2">' + totalText + '</div>');

        var html = '';
        j.rows.forEach(function(r, i){
            var splitTxt = r.split_type === 1 ? r.split_value+'%' :
                           r.split_type === 2 ? 'مبلغ '+r.split_value :
                           'كامل الباقي';
            var rowCls = !r.valid ? 'row-invalid' : (r.duplicate ? 'row-duplicate' : 'row-valid');
            html += '<tr class="' + rowCls + '">';
            html += '<td>' + r.row + '</td>';
            html += '<td>' + escHtml(r.emp_no) + '</td>';
            html += '<td>' + escHtml(r.provider) + '</td>';
            html += '<td style="direction:ltr;font-family:monospace">' + escHtml(r.account_no) + '</td>';
            html += '<td style="direction:ltr;font-family:monospace;font-size:.7rem">' + escHtml(r.iban) + '</td>';
            html += '<td>' + escHtml(r.wallet) + '</td>';
            html += '<td><b>' + escHtml(r.owner_name) + '</b><br><small>' + escHtml(r.owner_id_no) + '</small></td>';
            html += '<td>' + splitTxt + '</td>';
            html += '<td>';
            if(!r.valid){
                html += '<span class="badge bg-danger">خطأ ✗</span>';
                html += '<ul class="err-list">';
                r.errors.forEach(function(e){ html += '<li>' + escHtml(e) + '</li>'; });
                html += '</ul>';
            } else if(r.duplicate){
                html += '<span class="badge bg-warning text-dark">مكرر ⚠</span>';
                html += '<ul class="dup-note"><li>' + escHtml(r.dup_reason || 'موجود مسبقاً') + '</li></ul>';
            } else {
                html += '<span class="badge bg-success">صحيح ✓</span>';
            }
            html += '</td>';
            html += '</tr>';
        });
        $('#biPreviewBody').html(html);
        var btnTxt = '<i class="fa fa-check"></i> استيراد ' + j.valid + ' صف صحيح';
        if(dupCnt > 0) btnTxt += ' (تخطّي ' + dupCnt + ' مكرر)';
        $('#biExecuteBtn').prop('disabled', j.valid === 0).html(btnTxt);
    }

    function executeImport(){
        if(_previewRows.length === 0) return;
        // الصفوف القابلة للاستيراد = صحيحة وغير مكررة
        var importable = _previewRows.filter(function(r){ return r.valid && !r.duplicate; });
        var dupRows    = _previewRows.filter(function(r){ return r.valid &&  r.duplicate; });
        if(importable.length === 0){ warning_msg('تنبيه', 'لا توجد صفوف للاستيراد (المكرر يُتخطى)'); return; }

        var nl = String.fromCharCode(10);
        var msg = 'استيراد ' + importable.length + ' حساب جديد؟';
        if(dupRows.length > 0) msg += nl + 'سيتم تخطّي ' + dupRows.length + ' صف مكرر.';
        msg += nl + nl + 'هذه العملية لا يمكن التراجع عنها بسهولة.';
        if(!confirm(msg)) return;

        $('#biExecuteBtn').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> جاري الاستيراد...');

        get_data(BI_EXEC_URL, {rows: JSON.stringify(_previewRows)}, function(resp){
            var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
            $('#biExecuteBtn').html('<i class="fa fa-check"></i> استيراد الصفوف الصحيحة').prop('disabled', false);
            if(!j.ok){ danger_msg('خطأ', j.msg || 'فشل الاستيراد'); return; }

            var html = '<div class="alert alert-success">';
            html += '<i class="fa fa-check-circle"></i> تم استيراد <b>' + j.inserted + '</b> حساب';
            if(j.skipped > 0) html += ' · <span class="text-warning"><b>تم تخطّي ' + j.skipped + ' مكرر</b></span>';
            if(j.failed  > 0) html += ' · <span class="text-danger">فشل ' + j.failed + '</span>';
            html += '</div>';

            if(j.errors && j.errors.length > 0){
                html += '<div class="alert alert-warning"><b>الأخطاء:</b><ul>';
                j.errors.forEach(function(e){ html += '<li>' + escHtml(e) + '</li>'; });
                html += '</ul></div>';
            }
            $('#biResult').html(html);
            $('#biStep3').hide();
            $('#biStep4').show();
            $('html,body').animate({scrollTop: $('#biStep4').offset().top - 100}, 400);
        }, 'json');
    }

    function resetForm(){
        _previewRows = [];
        document.getElementById('biFile').value = '';
        $('#biStep3,#biStep4').hide();
        $('#biPreviewBody').html('');
        $('html,body').animate({scrollTop: 0}, 400);
    }

    function escHtml(s){
        return String(s == null ? '' : s).replace(/[&<>"']/g, function(c){
            return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c];
        });
    }

</script>
SCRIPT;

sec_scripts($scripts);
?>
