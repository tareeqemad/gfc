<?php
$MODULE_NAME = 'payment_accounts';
$TB_NAME     = 'payment_accounts';

$back_url        = base_url("$MODULE_NAME/$TB_NAME");
$emp_url_base    = base_url("$MODULE_NAME/$TB_NAME/emp");
$providers_url   = base_url("$MODULE_NAME/$TB_NAME/providers");
$overview_url    = base_url("$MODULE_NAME/$TB_NAME/health_overview_json");
$list_url        = base_url("$MODULE_NAME/$TB_NAME/health_list_json");
$bulk_link_url   = base_url("$MODULE_NAME/$TB_NAME/accounts_link_bulk_auto");

echo AntiForgeryToken();
?>

    <div class="page-header">
        <div><h1 class="page-title"><?= $title ?></h1></div>
        <div class="ms-auto pageheader-btn">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= $back_url ?>">إدارة حسابات الصرف</a></li>
                <li class="breadcrumb-item active" aria-current="page">فحص الصحة</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fa fa-heartbeat me-2 text-danger"></i> فحص صحة بيانات الصرف
                    </h3>
                    <div class="ms-auto d-flex gap-1 flex-wrap align-items-center">
                        <button type="button" class="btn btn-primary btn-sm" onclick="javascript:loadOverview();">
                            <i class="fa fa-refresh"></i> إعادة الفحص
                        </button>
                        <a href="<?= $back_url ?>" class="btn btn-light btn-sm">
                            <i class="fa fa-arrow-right me-1"></i> رجوع
                        </a>
                    </div>
                </div>
                <div class="card-body">

                    <!-- نظرة عامة -->
                    <div id="hcLoading" class="text-center py-5">
                        <i class="fa fa-spinner fa-spin fa-3x text-muted"></i>
                        <div class="mt-2 text-muted">جاري الفحص...</div>
                    </div>

                    <div id="hcContent" style="display:none">

                        <!-- الإجماليات -->
                        <div class="hc-totals mb-3">
                            <div class="hc-tot"><div class="lbl"><i class="fa fa-users text-primary"></i> الموظفون</div><div class="val" id="hcTotEmp">—</div></div>
                            <div class="hc-tot"><div class="lbl"><i class="fa fa-credit-card text-info"></i> الحسابات</div><div class="val" id="hcTotAcc">—</div></div>
                            <div class="hc-tot"><div class="lbl"><i class="fa fa-user-circle-o text-warning"></i> المستفيدون</div><div class="val" id="hcTotBnf">—</div></div>
                        </div>

                        <!-- ملخص الحالة -->
                        <div class="hc-summary mb-3" id="hcSummary"></div>

                        <!-- أقسام المشاكل -->
                        <div id="hcSections"></div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- ══════════ Modal: قائمة تفصيلية لفئة ══════════ -->
    <div class="modal fade" id="hcDetailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
            <div class="modal-content" style="border:0;border-radius:12px">
                <div class="modal-header py-2" id="hcDetailHeader" style="border-radius:12px 12px 0 0">
                    <h6 class="modal-title text-white fw-bold">
                        <i id="hcDetailIcon" class="fa fa-list me-1"></i> <span id="hcDetailTitle"></span>
                        <span id="hcDetailCount" class="badge bg-light text-dark ms-2" style="display:none"></span>
                    </h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="hcDetailBody">
                    <div class="text-center py-4 text-muted"><i class="fa fa-spinner fa-spin"></i> جاري التحميل...</div>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-success btn-sm" id="hcDetailExportBtn" style="display:none">
                        <i class="fa fa-file-excel-o"></i> تصدير Excel
                    </button>
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">إغلاق</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .hc-totals{display:flex;gap:.5rem;flex-wrap:wrap}
        .hc-tot{flex:1;min-width:160px;text-align:center;padding:.75rem .5rem;border-radius:10px;border:1px solid #e2e8f0;background:#fff}
        .hc-tot .lbl{font-size:.72rem;color:#64748b;margin-bottom:.2rem}
        .hc-tot .val{font-size:1.5rem;font-weight:800;color:#1e293b}

        .hc-summary{background:#fff;border:1px solid #e2e8f0;border-radius:10px;padding:1rem;display:flex;align-items:center;gap:1rem}
        .hc-summary.s-clean{background:linear-gradient(135deg,#f0fdf4,#fff);border-color:#bbf7d0}
        .hc-summary.s-issues{background:linear-gradient(135deg,#fef2f2,#fff);border-color:#fecaca}
        .hc-summary .icon{font-size:2.5rem}
        .hc-summary.s-clean .icon{color:#16a34a}
        .hc-summary.s-issues .icon{color:#dc2626}
        .hc-summary .text h5{margin:0;font-size:1.05rem;font-weight:800}
        .hc-summary .text small{font-size:.85rem;color:#64748b}

        .hc-section{border:1px solid #e2e8f0;border-radius:10px;background:#fff;margin-bottom:.65rem;overflow:hidden}
        .hc-section .head{padding:.7rem 1rem;display:flex;align-items:center;gap:.6rem;cursor:default}
        .hc-section.sev-err  .head{background:linear-gradient(90deg,#fef2f2,#fff);border-bottom:1px solid #fecaca}
        .hc-section.sev-warn .head{background:linear-gradient(90deg,#fffbeb,#fff);border-bottom:1px solid #fde68a}
        .hc-section.sev-info .head{background:linear-gradient(90deg,#eff6ff,#fff);border-bottom:1px solid #bfdbfe}
        .hc-section .head .icon{font-size:1.4rem;width:38px;text-align:center}
        .hc-section.sev-err  .icon{color:#dc2626}
        .hc-section.sev-warn .icon{color:#d97706}
        .hc-section.sev-info .icon{color:#2563eb}
        .hc-section .head .info{flex:1}
        .hc-section .head .info h6{margin:0;font-size:.95rem;font-weight:700;color:#1e293b}
        .hc-section .head .info small{font-size:.78rem;color:#64748b}
        .hc-section .head .count-badge{font-size:1.1rem;font-weight:800;padding:.2em .65em;border-radius:8px;direction:ltr}
        .hc-section.sev-err  .count-badge{background:#fee2e2;color:#991b1b}
        .hc-section.sev-warn .count-badge{background:#fef3c7;color:#92400e}
        .hc-section.sev-info .count-badge{background:#dbeafe;color:#1e40af}
        .hc-section .actions{padding:.5rem 1rem;background:#f8fafc;display:flex;gap:.4rem;justify-content:flex-end;border-top:1px solid #f1f5f9}

        .hc-list-table{font-size:.78rem;width:100%;border-collapse:collapse}
        .hc-list-table thead th{position:sticky;top:0;z-index:5;background:#f8fafc;box-shadow:inset 0 -2px 0 #e2e8f0}
        .hc-list-table th{font-size:.7rem;color:#64748b;padding:.55rem .65rem;font-weight:700}
        .hc-list-table td{padding:.45rem .65rem;border-bottom:1px solid #f1f5f9;vertical-align:middle}
        .hc-list-table tbody tr:hover{background:#fafbfc}
        .hc-list-table .emp-link{color:#1e40af;text-decoration:none;font-weight:700}
        .hc-list-table .emp-link:hover{text-decoration:underline}
        .hc-status-badge{font-size:.65rem;padding:.1em .45em;border-radius:4px;font-weight:700}
        .hc-status-active{background:#d1fae5;color:#065f46}
        .hc-status-retired{background:#f1f5f9;color:#475569}
    </style>

<?php
$scripts = <<<SCRIPT
<script type="text/javascript">

    var HC_OVERVIEW_URL = "{$overview_url}";
    var HC_LIST_URL     = "{$list_url}";
    var HC_BULK_LINK    = "{$bulk_link_url}";
    var HC_EMP_URL      = "{$emp_url_base}";
    var HC_PROV_URL     = "{$providers_url}";

    // تعريفات الفئات (icon, color, title, severity, action button)
    var CATEGORIES = [
        {key:'EMP_NO_ACC',       sev:'err',  icon:'fa-user-times',     title:'موظفون فعّالون بدون حسابات نشطة',
         desc:'هؤلاء الموظفون لن يتمكنوا من الصرف بالطريقة الجديدة', action:null},
        {key:'ACC_NO_IBAN',      sev:'err',  icon:'fa-warning',        title:'حسابات بنكية بـ IBAN غير صحيح',
         desc:'IBAN فارغ أو طوله أقل من 20 أو يحتوي على ?', action:null},
        {key:'PROV_INCOMPLETE',  sev:'err',  icon:'fa-bank',           title:'مزودون بإعدادات ناقصة',
         desc:'IBAN الشركة أو EXPORT_FORMAT ناقص — يلزم لتصدير ملفات البنوك', action:'goto_providers'},
        {key:'BENEF_UNLINKED',   sev:'warn', icon:'fa-link',           title:'موظفون بحسابات غير مربوطة بمستفيدين',
         desc:'مستفيدون موجودون لكن الحسابات على OWNER_NAME فقط دون BENEFICIARY_ID', action:'bulk_link'},
        {key:'ACC_INACTIVE_ONLY',sev:'warn', icon:'fa-pause-circle',   title:'موظفون بحسابات موقوفة فقط',
         desc:'موظفون عندهم حسابات قديمة موقوفة وما عندهم نشطة', action:null},
        {key:'BENEF_EXPIRED',    sev:'warn', icon:'fa-clock-o',        title:'مستفيدون انتهت مدة استحقاقهم',
         desc:'TO_DATE قديم لكن لا تزال STATUS = نشط — قد يلزم إيقافهم', action:null},
        {key:'EMP_DUP_DEFAULT',  sev:'info', icon:'fa-exclamation-circle', title:'موظفون بأكثر من حساب افتراضي',
         desc:'المفروض حساب واحد فقط يكون افتراضياً', action:null}
    ];

    var COUNT_KEY_MAP = {
        EMP_NO_ACC:        'emp_no_acc',
        ACC_NO_IBAN:       'acc_no_iban',
        BENEF_UNLINKED:    'benef_unlinked',
        ACC_INACTIVE_ONLY: 'acc_inactive_only',
        PROV_INCOMPLETE:   'prov_incomplete',
        BENEF_EXPIRED:     'benef_expired',
        EMP_DUP_DEFAULT:   'emp_dup_default'
    };

    function loadOverview(){
        $('#hcLoading').show();
        $('#hcContent').hide();
        get_data(HC_OVERVIEW_URL, {}, function(resp){
            var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
            $('#hcLoading').hide();
            if(!j.ok){ danger_msg('خطأ', 'فشل الفحص'); return; }
            renderOverview(j.data);
        }, 'json');
    }

    function fmt(n){ return (parseInt(n)||0).toLocaleString('en-US'); }

    function renderOverview(d){
        $('#hcTotEmp').text(fmt(d.total_emps));
        $('#hcTotAcc').text(fmt(d.total_accounts));
        $('#hcTotBnf').text(fmt(d.total_benef));

        // ملخص الحالة
        var totalIssues = (d.emp_no_acc||0) + (d.acc_no_iban||0) + (d.prov_incomplete||0)
                       + (d.benef_unlinked||0) + (d.acc_inactive_only||0) + (d.benef_expired||0)
                       + (d.emp_dup_default||0);
        var totalErrs = (d.emp_no_acc||0) + (d.acc_no_iban||0) + (d.prov_incomplete||0);
        var totalWarns = (d.benef_unlinked||0) + (d.acc_inactive_only||0) + (d.benef_expired||0);

        var summaryHtml;
        if(totalIssues === 0){
            summaryHtml =
                '<div class="hc-summary s-clean">' +
                  '<i class="fa fa-check-circle icon"></i>' +
                  '<div class="text"><h5>كل البيانات سليمة</h5>' +
                  '<small>النظام جاهز للصرف بدون أي مشاكل</small></div>' +
                '</div>';
        } else {
            summaryHtml =
                '<div class="hc-summary s-issues">' +
                  '<i class="fa fa-exclamation-triangle icon"></i>' +
                  '<div class="text"><h5>' + totalIssues + ' مشكلة تحتاج مراجعة</h5>' +
                  '<small>' +
                    '<b style="color:#dc2626">' + totalErrs + ' حرجة</b> · ' +
                    '<b style="color:#d97706">' + totalWarns + ' تحذيرات</b> · ' +
                    '<b style="color:#2563eb">' + (d.emp_dup_default||0) + ' معلوماتية</b>' +
                  '</small></div>' +
                '</div>';
        }
        $('#hcSummary').html(summaryHtml);

        // الأقسام
        var html = '';
        CATEGORIES.forEach(function(cat){
            var n = d[COUNT_KEY_MAP[cat.key]] || 0;
            if(n === 0) return;
            html += '<div class="hc-section sev-' + cat.sev + '">';
            html += '<div class="head">';
            html += '<i class="fa ' + cat.icon + ' icon"></i>';
            html += '<div class="info"><h6>' + cat.title + '</h6><small>' + cat.desc + '</small></div>';
            html += '<span class="count-badge">' + fmt(n) + '</span>';
            html += '</div>';
            html += '<div class="actions">';
            if(cat.action === 'bulk_link'){
                html += '<button class="btn btn-sm btn-success" onclick="javascript:bulkLinkBenef();">' +
                        '<i class="fa fa-magic"></i> ربط تلقائي للكل</button>';
            }
            if(cat.action === 'goto_providers'){
                html += '<a class="btn btn-sm btn-info text-white" href="' + HC_PROV_URL + '">' +
                        '<i class="fa fa-external-link"></i> فتح شاشة المزودين</a>';
            }
            html += '<button class="btn btn-sm btn-outline-secondary" onclick="javascript:showDetail(\'' + cat.key + '\',\'' + cat.title.replace(/'/g, "\\'") + '\',\'' + cat.icon + '\',\'' + cat.sev + '\');">';
            html += '<i class="fa fa-list"></i> عرض القائمة</button>';
            html += '</div>';
            html += '</div>';
        });

        if(html === ''){
            html = '<div class="text-center text-muted py-3"><i class="fa fa-thumbs-up fa-2x text-success"></i><div class="mt-2">لا توجد مشاكل</div></div>';
        }
        $('#hcSections').html(html);
        $('#hcContent').show();
    }

    // 🆕 cache آخر category + title عشان زر التصدير يبعتهم
    var _hcLastCat   = '';
    var _hcLastTitle = '';

    function showDetail(category, title, icon, sev){
        var headerColor = sev === 'err' ? '#991b1b' : (sev === 'warn' ? '#92400e' : '#1e40af');
        $('#hcDetailHeader').css('background', headerColor);
        $('#hcDetailTitle').text(title);
        $('#hcDetailIcon').attr('class', 'fa ' + icon + ' me-1');
        $('#hcDetailCount').hide().text('');
        $('#hcDetailExportBtn').hide();
        $('#hcDetailBody').html('<div class="text-center py-4 text-muted"><i class="fa fa-spinner fa-spin"></i> جاري التحميل...</div>');
        bootstrap.Modal.getOrCreateInstance(document.getElementById('hcDetailModal')).show();

        _hcLastCat   = category;
        _hcLastTitle = title;

        get_data(HC_LIST_URL, {category: category, offset: 0, limit: 5000}, function(resp){
            var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
            var rows = (j && j.data) ? j.data : [];
            if(rows.length === 0){
                $('#hcDetailBody').html('<div class="alert alert-success text-center py-3"><i class="fa fa-check-circle"></i> لا توجد عناصر — المشكلة تم حلها</div>');
                return;
            }

            // عدّاد + إظهار زر التصدير
            $('#hcDetailCount').text(rows.length).show();
            $('#hcDetailExportBtn').show();

            var html = '<table class="hc-list-table"><thead><tr>';
            html += '<th style="width:40px">#</th>';
            if(category === 'PROV_INCOMPLETE'){
                html += '<th>المزود</th><th>الحالة</th><th>التفاصيل</th><th style="width:100px"></th>';
            } else {
                html += '<th>الموظف</th><th>المقر</th><th>الحالة</th><th>التفاصيل</th><th style="width:90px"></th>';
            }
            html += '</tr></thead><tbody>';

            rows.forEach(function(r, i){
                html += '<tr>';
                html += '<td class="text-muted">' + (i+1) + '</td>';

                if(category === 'PROV_INCOMPLETE'){
                    html += '<td><b>' + escHtml(r.EMP_NAME||'') + '</b></td>';
                    html += '<td><span class="hc-status-badge ' + (r.IS_ACTIVE==1?'hc-status-active':'hc-status-retired') + '">' + (r.IS_ACTIVE==1?'نشط':'موقوف') + '</span></td>';
                    html += '<td style="color:#dc2626">' + escHtml(r.DETAIL_INFO||'') + '</td>';
                    html += '<td><a href="' + HC_PROV_URL + '" class="btn btn-sm btn-outline-info"><i class="fa fa-pencil"></i> تعديل</a></td>';
                } else {
                    html += '<td><a class="emp-link" href="' + HC_EMP_URL + '/' + r.EMP_NO + '" target="_blank"><b>' + r.EMP_NO + '</b><br><small>' + escHtml(r.EMP_NAME||'') + '</small></a></td>';
                    html += '<td>' + escHtml(r.BRANCH_NAME||'') + '</td>';
                    html += '<td><span class="hc-status-badge ' + (r.IS_ACTIVE==1?'hc-status-active':'hc-status-retired') + '">' + (r.IS_ACTIVE==1?'فعّال':'متقاعد') + '</span></td>';
                    html += '<td>' + escHtml(r.DETAIL_INFO||'') + '</td>';
                    html += '<td><a href="' + HC_EMP_URL + '/' + r.EMP_NO + '" target="_blank" class="btn btn-sm btn-outline-primary"><i class="fa fa-external-link"></i> فتح</a></td>';
                }
                html += '</tr>';
            });
            html += '</tbody></table>';
            $('#hcDetailBody').html(html);
        }, 'json');
    }

    // 🆕 تصدير Excel — يفتح URL في tab جديد لتنزيل الملف
    $(document).on('click', '#hcDetailExportBtn', function(){
        if(!_hcLastCat) return;
        var url = HC_LIST_URL + '?category=' + encodeURIComponent(_hcLastCat) +
                  '&export=1&title=' + encodeURIComponent(_hcLastTitle);
        window.location.href = url;
    });

    function bulkLinkBenef(){
        var nl = String.fromCharCode(10);
        if(!confirm('سيُحاول النظام ربط حسابات كل الموظفين بمستفيديهم تلقائياً.' + nl +
                    '• مطابقة بهوية صاحب الحساب' + nl +
                    '• ثم مطابقة بالاسم' + nl + nl +
                    'متابعة؟')) return;

        get_data(HC_BULK_LINK, {}, function(resp){
            var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
            if(j.ok){
                success_msg('تم', j.msg);
                setTimeout(loadOverview, 800);
            } else {
                danger_msg('خطأ', j.msg);
            }
        }, 'json');
    }

    function escHtml(s){
        return String(s == null ? '' : s).replace(/[&<>"']/g, function(c){
            return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c];
        });
    }

    $(function(){
        loadOverview();
    });

</script>
SCRIPT;

sec_scripts($scripts);
?>
