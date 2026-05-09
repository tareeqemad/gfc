<?php
$MODULE_NAME = 'payment_accounts';
$TB_NAME     = 'payment_accounts';

$back_url           = base_url("$MODULE_NAME/$TB_NAME");
$get_page_url       = base_url("$MODULE_NAME/$TB_NAME/providers_get_page");
$save_url           = base_url("$MODULE_NAME/$TB_NAME/provider_save");
$delete_url         = base_url("$MODULE_NAME/$TB_NAME/provider_delete");
$toggle_url         = base_url("$MODULE_NAME/$TB_NAME/provider_toggle_active");
$export_url         = base_url("$MODULE_NAME/$TB_NAME/providers_export_excel");
$accounts_json_url  = base_url("$MODULE_NAME/$TB_NAME/provider_accounts_json");
$accounts_xlsx_url  = base_url("$MODULE_NAME/$TB_NAME/provider_accounts_export_excel");
$branches_json_url  = base_url("$MODULE_NAME/$TB_NAME/provider_branches_json");
$branches_all_url   = base_url("$MODULE_NAME/$TB_NAME/branches_list_json");
$providers_json_url = base_url("$MODULE_NAME/$TB_NAME/providers_list_json");
$branch_save_url    = base_url("$MODULE_NAME/$TB_NAME/branch_save");
$branch_link_url    = base_url("$MODULE_NAME/$TB_NAME/branch_link_provider");
$bulk_url           = base_url("$MODULE_NAME/$TB_NAME/providers_bulk_action");

echo AntiForgeryToken();
?>

    <!-- PAGE-HEADER -->
    <div class="page-header">
        <div>
            <h1 class="page-title"><?= $title ?></h1>
        </div>
        <div class="ms-auto pageheader-btn">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= $back_url ?>">إدارة حسابات الصرف</a></li>
                <li class="breadcrumb-item active" aria-current="page">المزودون</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fa fa-bank me-2"></i> مزودو الصرف (بنوك + محافظ)</h3>
                    <div class="ms-auto d-flex gap-1 flex-wrap align-items-center">
                        <button type="button" class="btn btn-danger btn-sm" onclick="javascript:openProviderModal();">
                            <i class="fa fa-plus"></i> مزود جديد
                        </button>
                        <a href="<?= $back_url ?>" class="btn btn-light btn-sm">
                            <i class="fa fa-arrow-right me-1"></i> رجوع
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form id="<?= $TB_NAME ?>_prov_form" onsubmit="return false;">
                        <div class="row">
                            <div class="form-group col-md-2">
                                <label>النوع</label>
                                <select name="type" id="dp_type" class="form-control">
                                    <option value="">— الكل —</option>
                                    <option value="1">بنك</option>
                                    <option value="2">محفظة</option>
                                </select>
                            </div>
                            <div class="form-group col-md-2">
                                <label>الحالة</label>
                                <select name="is_active" id="dp_is_active" class="form-control">
                                    <option value="">— الكل —</option>
                                    <option value="1">نشط</option>
                                    <option value="0">موقوف</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label>بحث (الاسم/الرمز)</label>
                                <input type="text" name="q" id="txt_search" class="form-control" placeholder="مثال: فلسطين، BANK89...">
                            </div>
                        </div>
                        <hr>
                        <div class="flex-shrink-0">
                            <button type="button" onclick="javascript:search();" class="btn btn-primary">
                                <i class="fa fa-search"></i> استعلام
                            </button>
                            <button type="button" onclick="javascript:clear_form();" class="btn btn-cyan-light">
                                <i class="fa fa-eraser"></i> تفريغ الحقول
                            </button>
                            <button type="button" onclick="javascript:exportProvidersExcel();" class="btn btn-success">
                                <i class="fa fa-file-excel-o"></i> تصدير Excel
                            </button>
                        </div>
                        <hr>

                        <!-- بطاقات الإحصائيات -->
                        <div class="prov-stats mb-3" id="provStats" style="display:none">
                            <div class="prov-stat"><div class="ps-lbl"><i class="fa fa-list"></i> إجمالي المزودين</div><div class="ps-val" id="psTotal">—</div></div>
                            <div class="prov-stat c-bank"><div class="ps-lbl"><i class="fa fa-bank"></i> بنوك</div><div class="ps-val" id="psBanks">—</div></div>
                            <div class="prov-stat c-wallet"><div class="ps-lbl"><i class="fa fa-mobile"></i> محافظ</div><div class="ps-val" id="psWallets">—</div></div>
                            <div class="prov-stat c-off"><div class="ps-lbl"><i class="fa fa-ban"></i> موقوفة</div><div class="ps-val" id="psInactive">—</div></div>
                        </div>

                        <!-- بنر الفروع غير المرتبطة -->
                        <div id="unlinkedBanner" class="d-none alert mb-3 py-2" style="background:#fee2e2;border:1px solid #fca5a5;font-size:.85rem">
                            <i class="fa fa-exclamation-triangle text-danger"></i>
                            <b style="color:#991b1b"><span id="unlinkedCount">0</span></b>
                            فرع غير مرتبط ببنك رئيسي
                            <a href="javascript:void(0)" onclick="showUnlinkedBranches()" class="ms-2 alert-link">
                                <i class="fa fa-link"></i> عرض / ربط
                            </a>
                        </div>

                        <!-- شريط Bulk -->
                        <div id="bulkBar" class="d-none align-items-center gap-2 mb-3" style="background:#fef3c7;border:1px solid #fde68a;border-radius:8px;padding:.4rem .85rem">
                            <span style="font-size:.82rem;font-weight:700;color:#92400e">
                                <i class="fa fa-check-square"></i> <span id="bulkCount">0</span> محدّد
                            </span>
                            <button type="button" class="btn btn-sm btn-success" onclick="javascript:bulkAction('ACTIVATE');">
                                <i class="fa fa-check"></i> تفعيل
                            </button>
                            <button type="button" class="btn btn-sm btn-warning" onclick="javascript:bulkAction('DEACTIVATE');">
                                <i class="fa fa-pause"></i> إيقاف
                            </button>
                            <button type="button" class="btn btn-sm btn-danger" onclick="javascript:bulkAction('DELETE');">
                                <i class="fa fa-trash"></i> حذف
                            </button>
                            <button type="button" class="btn btn-sm btn-light" onclick="javascript:clearBulk();">
                                <i class="fa fa-times"></i> إلغاء
                            </button>
                        </div>

                        <div id="container">
                            <div class="alert alert-light text-center text-muted py-4">
                                <i class="fa fa-search fa-2x d-block mb-2" style="opacity:.4"></i>
                                اضغط <b>استعلام</b> لعرض المزودين
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- ══════════ Modal: إضافة/تعديل ══════════ -->
    <div class="modal fade" id="provModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border:0;border-radius:12px;overflow:hidden">
                <div class="modal-header py-2" style="background:#1e293b">
                    <h6 class="modal-title text-white fw-bold">
                        <i class="fa fa-bank me-1"></i> <span id="provModalTitle">مزود جديد</span>
                    </h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="provForm" onsubmit="return false;">
                        <input type="hidden" id="prov_id" name="provider_id">
                        <div class="row g-2">
                            <div class="form-group col-md-4">
                                <label>النوع <span class="text-danger">*</span></label>
                                <select id="prov_type" name="type" class="form-control">
                                    <option value="1">بنك</option>
                                    <option value="2">محفظة إلكترونية</option>
                                </select>
                            </div>
                            <div class="form-group col-md-5">
                                <label>اسم المزود <span class="text-danger">*</span></label>
                                <input type="text" id="prov_name" name="name" class="form-control" placeholder="بنك فلسطين">
                            </div>
                            <div class="form-group col-md-3">
                                <label>الرمز (Code)</label>
                                <input type="text" id="prov_code" name="code" class="form-control" placeholder="BANK89" style="direction:ltr">
                            </div>
                        </div>
                        <hr class="my-2">
                        <h6 class="fw-bold mb-2" style="font-size:.82rem;color:#64748b">بيانات حساب الشركة</h6>
                        <div class="row g-2">
                            <div class="form-group col-md-4">
                                <label>رقم الحساب</label>
                                <input type="text" id="prov_company_acc_no" name="company_acc_no" class="form-control" style="direction:ltr">
                            </div>
                            <div class="form-group col-md-4">
                                <label>رقم مرجعي</label>
                                <input type="text" id="prov_company_acc_id" name="company_acc_id" class="form-control" style="direction:ltr">
                            </div>
                            <div class="form-group col-md-4">
                                <label>IBAN الشركة</label>
                                <input type="text" id="prov_company_iban" name="company_iban" class="form-control" style="direction:ltr" placeholder="PS...">
                            </div>
                        </div>
                        <hr class="my-2">
                        <div class="row g-2">
                            <div class="form-group col-md-4">
                                <label>تنسيق التصدير</label>
                                <select id="prov_export_format" name="export_format" class="form-control">
                                    <option value="">— بدون —</option>
                                    <option value="1">1 — بسيط</option>
                                    <option value="2">2 — مع هيدر</option>
                                    <option value="3">3 — PalPay</option>
                                    <option value="4">4 — Jawwal Pay</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label>الترتيب</label>
                                <input type="number" id="prov_sort_order" name="sort_order" class="form-control" value="999" min="1">
                            </div>
                            <div class="form-group col-md-4" id="prov_active_grp" style="display:none">
                                <label>الحالة</label>
                                <select id="prov_is_active" name="is_active" class="form-control">
                                    <option value="1">نشط</option>
                                    <option value="0">موقوف</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">
                        <i class="fa fa-times"></i> إلغاء
                    </button>
                    <button type="button" class="btn btn-primary btn-sm" onclick="javascript:saveProvider();">
                        <i class="fa fa-save me-1"></i> حفظ
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ══════════ Modal: تفاصيل تقنية ══════════ -->
    <div class="modal fade" id="provDetailsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border:0;border-radius:12px;overflow:hidden">
                <div class="modal-header py-2" style="background:#0f172a">
                    <h6 class="modal-title text-white fw-bold">
                        <i class="fa fa-cogs me-1"></i> تفاصيل تقنية
                    </h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="provDetailsBody"></div>
            </div>
        </div>
    </div>

    <!-- ══════════ Modal: حسابات/فروع ══════════ -->
    <div class="modal fade" id="provListModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
            <div class="modal-content" style="border:0;border-radius:12px;overflow:hidden">
                <div class="modal-header py-2" style="background:#1e293b">
                    <h6 class="modal-title text-white fw-bold">
                        <i id="provListIcon" class="fa fa-users me-1"></i> <span id="provListTitle"></span>
                    </h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <!-- Toolbar: بحث + عدّاد + تصدير -->
                <div id="provListToolbar" class="d-none px-3 py-2" style="background:#f8fafc;border-bottom:1px solid #e2e8f0;position:sticky;top:0;z-index:5">
                    <div class="row g-2 align-items-center">
                        <div class="col-md-6">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text"><i class="fa fa-search"></i></span>
                                <input type="text" id="provListSearch" class="form-control" placeholder="بحث برقم الموظف، الاسم، رقم الحساب، IBAN...">
                                <button class="btn btn-outline-secondary" type="button" id="provListSearchClear" title="مسح"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <span class="badge bg-secondary" style="font-size:.78rem">
                                <i class="fa fa-list-ol me-1"></i>
                                <span id="provListCountVisible">0</span> / <span id="provListCountTotal">0</span> موظف
                            </span>
                        </div>
                        <div class="col-md-3 text-end">
                            <button type="button" id="provListExportBtn" class="btn btn-success btn-sm">
                                <i class="fa fa-file-excel me-1"></i> تصدير Excel
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-body" id="provListBody" style="max-height:70vh">
                    <div class="text-center py-4 text-muted"><i class="fa fa-spinner fa-spin"></i> جاري التحميل...</div>
                </div>
            </div>
        </div>
    </div>
    <style>
        #provListBody table.acc-list-tbl thead th {position:sticky;top:0;background:#f1f5f9;z-index:3;box-shadow:0 1px 0 #e2e8f0}
        #provListBody table.acc-list-tbl tbody tr.row-hidden {display:none}
        #provListBody table.acc-list-tbl tbody tr:hover {background:#fffbeb}
    </style>

    <!-- ══════════ Modal: إضافة/تعديل فرع ══════════ -->
    <div class="modal fade" id="branchEditModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border:0;border-radius:12px;overflow:hidden">
                <div class="modal-header py-2" style="background:#1e293b">
                    <h6 class="modal-title text-white fw-bold"><i class="fa fa-building me-1"></i> <span id="brEditModalTitle">فرع جديد</span></h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="branchEditForm" onsubmit="return false;">
                        <input type="hidden" id="br_id" name="branch_id">
                        <input type="hidden" id="br_provider_id" name="provider_id">

                        <div class="row g-2">
                            <div class="form-group col-md-12">
                                <label class="fw-bold" style="font-size:.78rem">البنك الرئيسي</label>
                                <input type="text" id="br_provider_name" class="form-control" readonly style="background:#f1f5f9;font-weight:700">
                            </div>
                        </div>

                        <div class="row g-2 mt-1">
                            <div class="form-group col-md-3">
                                <label class="fw-bold" style="font-size:.78rem">رقم الفرع <span class="text-danger">*</span></label>
                                <input type="number" id="br_legacy_bank_no" name="legacy_bank_no" class="form-control" style="direction:ltr" min="1" placeholder="مثال: 100">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="fw-bold" style="font-size:.78rem">اسم الفرع <span class="text-danger">*</span></label>
                                <input type="text" id="br_name" name="name" class="form-control" placeholder="بنك فلسطين - فرع الرمال">
                            </div>
                            <div class="form-group col-md-3">
                                <label class="fw-bold" style="font-size:.78rem">رقم الطباعة</label>
                                <input type="number" id="br_print_no" name="print_no" class="form-control" style="direction:ltr">
                            </div>
                        </div>

                        <hr class="my-2">
                        <h6 class="fw-bold" style="font-size:.82rem;color:#64748b">بيانات تواصل (اختيارية)</h6>

                        <div class="row g-2">
                            <div class="form-group col-md-12">
                                <label class="fw-bold" style="font-size:.78rem">العنوان</label>
                                <input type="text" id="br_address" name="address" class="form-control">
                            </div>
                        </div>

                        <div class="row g-2 mt-1">
                            <div class="form-group col-md-4">
                                <label class="fw-bold" style="font-size:.78rem">هاتف 1</label>
                                <input type="text" id="br_phone1" name="phone1" class="form-control" style="direction:ltr">
                            </div>
                            <div class="form-group col-md-4">
                                <label class="fw-bold" style="font-size:.78rem">هاتف 2</label>
                                <input type="text" id="br_phone2" name="phone2" class="form-control" style="direction:ltr">
                            </div>
                            <div class="form-group col-md-4">
                                <label class="fw-bold" style="font-size:.78rem">فاكس</label>
                                <input type="text" id="br_fax" name="fax" class="form-control" style="direction:ltr">
                            </div>
                        </div>

                        <div class="row g-2 mt-1" id="br_active_grp" style="display:none">
                            <div class="form-group col-md-4">
                                <label class="fw-bold" style="font-size:.78rem">الحالة</label>
                                <select id="br_is_active" name="is_active" class="form-control">
                                    <option value="1">نشط</option>
                                    <option value="0">موقوف</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal"><i class="fa fa-times"></i> إلغاء</button>
                    <button type="button" class="btn btn-primary btn-sm" onclick="javascript:saveBranch();"><i class="fa fa-save me-1"></i> حفظ</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ══════════ Modal: الفروع غير المرتبطة ══════════ -->
    <div class="modal fade" id="unlinkedBranchesModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
            <div class="modal-content" style="border:0;border-radius:12px;overflow:hidden">
                <div class="modal-header py-2" style="background:#991b1b">
                    <h6 class="modal-title text-white fw-bold">
                        <i class="fa fa-unlink me-1"></i> فروع غير مرتبطة ببنك رئيسي
                    </h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="unlinkedBranchesBody">
                    <div class="text-center py-4 text-muted"><i class="fa fa-spinner fa-spin"></i> جاري التحميل...</div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .prov-stats { display:flex; gap:.5rem; flex-wrap:wrap; margin-bottom:.5rem; }
        .prov-stat { flex:1; min-width:140px; text-align:center; padding:.6rem .5rem; border-radius:10px; border:1px solid #e2e8f0; background:#fff; }
        .prov-stat .ps-lbl { font-size:.7rem; color:#64748b; margin-bottom:.15rem; }
        .prov-stat .ps-lbl i { margin-left:3px; }
        .prov-stat .ps-val { font-size:1.25rem; font-weight:800; color:#1e293b; }
        .prov-stat.c-bank { background:#eff6ff; border-color:#bfdbfe; }
        .prov-stat.c-bank .ps-val { color:#1e40af; }
        .prov-stat.c-wallet { background:#f5f3ff; border-color:#c4b5fd; }
        .prov-stat.c-wallet .ps-val { color:#6d28d9; }
        .prov-stat.c-off { background:#fef2f2; border-color:#fecaca; }
        .prov-stat.c-off .ps-val { color:#b91c1c; }

        .det-grid { display:grid; grid-template-columns:1fr 1fr; gap:.75rem; }
        .det-cell { padding:.5rem .65rem; background:#f8fafc; border-radius:6px; font-size:.82rem; }
        .det-cell .lbl { color:#64748b; font-size:.7rem; margin-bottom:.2rem; font-weight:600; }
        .det-cell .val { color:#1e293b; font-weight:700; }
        .req-badge { font-size:.62rem; padding:.1em .4em; border-radius:4px; font-weight:700; }
        .req-badge.ok { background:#dcfce7; color:#166534; }
        .req-badge.none { background:#f1f5f9; color:#94a3b8; font-style:italic; font-weight:500; }
    </style>

<?php
$scripts = <<<SCRIPT
<script type="text/javascript">
    console.log('[PROVIDERS] JS file loaded');

    var provGetPageUrl  = "{$get_page_url}";
    var provExportUrl   = "{$export_url}";
    var provSaveUrl     = "{$save_url}";
    var provDeleteUrl   = "{$delete_url}";
    var provToggleUrl   = "{$toggle_url}";
    var provAccountsUrl = "{$accounts_json_url}";
    var provAccountsXlsx= "{$accounts_xlsx_url}";
    var provBranchesUrl = "{$branches_json_url}";
    var branchesAllUrl  = "{$branches_all_url}";
    var providersJsonUrl= "{$providers_json_url}";
    var branchSaveUrl   = "{$branch_save_url}";
    var branchLinkUrl   = "{$branch_link_url}";
    var provBulkUrl     = "{$bulk_url}";

    // سياق صفحة الفروع المفتوحة حالياً
    var _curBranchProv = { id: null, name: '' };

    function reBind(){
        if(typeof initFunctions == 'function') initFunctions();
        initTooltips();
        if(typeof ajax_pager == 'function') ajax_pager(values_search(0));
    }

    function LoadingData(){
        if(!$('#page_tb').length) return;
        ajax_pager_data('#page_tb > tbody', values_search(0));
    }

    function initTooltips(){
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (el) { return new bootstrap.Tooltip(el); });
    }

    $(function(){
        initTooltips();
        loadUnlinkedCount();
        $('#{$TB_NAME}_prov_form').on('keydown', function(e){
            if(e.keyCode === 13){ e.preventDefault(); search(); }
        });

        // checkbox
        $(document).on('change', '.prov-cb', function(e){
            e.stopPropagation();
            var tr = $(this).closest('tr');
            if(this.checked) tr.addClass('selected'); else tr.removeClass('selected');
            _refreshBulkBar();
        });

        // أزرار في الجدول
        $(document).on('click', '.prov-edit-btn', function(e){
            e.stopPropagation();
            var tr = $(this).closest('tr');
            try { editProvider(JSON.parse(tr.attr('data-provider'))); }
            catch(err){ danger_msg('خطأ', 'فشل قراءة بيانات المزود'); }
        });
        $(document).on('click', '.prov-detail-btn', function(e){
            e.stopPropagation();
            var tr = $(this).closest('tr');
            try { showProviderDetails(JSON.parse(tr.attr('data-provider'))); }
            catch(err){ danger_msg('خطأ', 'فشل قراءة البيانات'); }
        });
    });

    function values_search(add_page){
        var values = {
            page: 1,
            type:      $('#dp_type').val()      || '',
            is_active: $('#dp_is_active').val() || '',
            q:         $('#txt_search').val()   || ''
        };
        if(add_page == 0) delete values.page;
        return values;
    }

    function search(){
        var values = values_search(1);
        get_data('{$get_page_url}', values, function(data){
            $('#container').html(data);
            $('#provStats').show();
            reBind();
        }, 'html');
    }

    function loadData(){ search(); }

    function exportProvidersExcel(){
        var values = values_search(0);
        var token = $('input[name="__AntiForgeryToken"]').val() || '';
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = provExportUrl;
        form.style.display = 'none';
        if(token){
            var ti = document.createElement('input');
            ti.type = 'hidden'; ti.name = '__AntiForgeryToken'; ti.value = token;
            form.appendChild(ti);
        }
        for(var k in values){
            if(values[k] !== '' && values[k] !== null && values[k] !== undefined){
                var input = document.createElement('input');
                input.type = 'hidden'; input.name = k; input.value = values[k];
                form.appendChild(input);
            }
        }
        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form);
    }

    function clear_form(){
        clearForm($('#{$TB_NAME}_prov_form'));
        $('#dp_type').val('');
        $('#dp_is_active').val('');
        $('#txt_search').val('');
        search();
    }

    function refreshTotals(t){
        if(!t) return;
        $('#psTotal').text((parseInt(t.total)||0).toLocaleString('en-US'));
        $('#psBanks').text((parseInt(t.banks)||0).toLocaleString('en-US'));
        $('#psWallets').text((parseInt(t.wallets)||0).toLocaleString('en-US'));
        $('#psInactive').text((parseInt(t.inactive)||0).toLocaleString('en-US'));
    }

    // ══════════ Modal: إضافة/تعديل ══════════
    function openProviderModal(){
        $('#provModalTitle').text('مزود جديد');
        $('#provForm')[0].reset();
        $('#prov_id').val('');
        $('#prov_sort_order').val(999);
        $('#prov_active_grp').hide();
        bootstrap.Modal.getOrCreateInstance(document.getElementById('provModal')).show();
    }

    function editProvider(p){
        $('#provModalTitle').text('تعديل: ' + (p.PROVIDER_NAME || ''));
        $('#prov_id').val(p.PROVIDER_ID || '');
        $('#prov_type').val(p.PROVIDER_TYPE || 1);
        $('#prov_name').val(p.PROVIDER_NAME || '');
        $('#prov_code').val(p.PROVIDER_CODE || '');
        $('#prov_company_acc_no').val(p.COMPANY_ACCOUNT_NO || '');
        $('#prov_company_acc_id').val(p.COMPANY_ACCOUNT_ID || '');
        $('#prov_company_iban').val(p.COMPANY_IBAN || '');
        $('#prov_export_format').val(p.EXPORT_FORMAT || '');
        $('#prov_sort_order').val(p.SORT_ORDER || 999);
        $('#prov_is_active').val(p.IS_ACTIVE != null ? p.IS_ACTIVE : 1);
        $('#prov_active_grp').show();
        bootstrap.Modal.getOrCreateInstance(document.getElementById('provModal')).show();
    }

    function saveProvider(){
        if(!$('#prov_name').val().trim()){ warning_msg('تنبيه', 'اسم المزود مطلوب'); return; }
        var f = $('#provForm').serialize();
        get_data(provSaveUrl, f, function(resp){
            var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
            if(j.ok){
                success_msg('تم', j.msg);
                bootstrap.Modal.getOrCreateInstance(document.getElementById('provModal')).hide();
                search();
            } else { danger_msg('خطأ', j.msg); }
        }, 'json');
    }

    function toggleProvider(id, active){
        var verb = active ? 'تفعيل' : 'إيقاف';
        if(!confirm('هل تريد ' + verb + ' هذا المزود؟')) return;
        get_data(provToggleUrl, { provider_id: id, is_active: active }, function(resp){
            var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
            if(j.ok){ success_msg('تم', j.msg); search(); }
            else    { danger_msg('خطأ', j.msg); }
        }, 'json');
    }

    function deleteProvider(id, name){
        var nl = String.fromCharCode(10);
        if(!confirm('حذف المزود نهائياً: ' + name + nl + nl + 'سيفشل لو في حسابات/فروع. متابعة؟')) return;
        get_data(provDeleteUrl, { provider_id: id }, function(resp){
            var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
            if(j.ok){ success_msg('تم', j.msg); search(); }
            else    { danger_msg('خطأ', j.msg); }
        }, 'json');
    }

    function showProviderDetails(p){
        var html = '<div class="det-grid">';
        html += '<div class="det-cell"><div class="lbl">الرمز (Code)</div><div class="val">' + (p.PROVIDER_CODE || '—') + '</div></div>';
        html += '<div class="det-cell"><div class="lbl">رقم بنك قديم</div><div class="val">' + (p.LEGACY_BANK_NO || '—') + '</div></div>';
        html += '<div class="det-cell"><div class="lbl">تنسيق التصدير</div><div class="val">' + (p.EXPORT_FORMAT || '—') + '</div></div>';
        html += '<div class="det-cell"><div class="lbl">الترتيب</div><div class="val">' + (p.SORT_ORDER || '—') + '</div></div>';
        html += '<div class="det-cell"><div class="lbl">رقم مرجعي</div><div class="val">' + (p.COMPANY_ACCOUNT_ID || '—') + '</div></div>';
        html += '<div class="det-cell"><div class="lbl">الحالة</div><div class="val">' + (parseInt(p.IS_ACTIVE) ? 'نشط' : 'موقوف') + '</div></div>';
        html += '</div>';
        html += '<hr class="my-3"><div style="font-size:.78rem;color:#64748b;margin-bottom:.4rem"><b>المتطلبات عند إضافة حساب موظف:</b></div>';
        html += '<div class="d-flex gap-2 flex-wrap">';
        html += '<span class="req-badge ' + (parseInt(p.REQUIRES_IBAN)  ? 'ok' : 'none') + '">' + (parseInt(p.REQUIRES_IBAN)  ? '✔ IBAN مطلوب' : 'IBAN غير مطلوب') + '</span>';
        html += '<span class="req-badge ' + (parseInt(p.REQUIRES_ID)    ? 'ok' : 'none') + '">' + (parseInt(p.REQUIRES_ID)    ? '✔ هوية مطلوبة' : 'الهوية غير مطلوبة') + '</span>';
        html += '<span class="req-badge ' + (parseInt(p.REQUIRES_PHONE) ? 'ok' : 'none') + '">' + (parseInt(p.REQUIRES_PHONE) ? '✔ جوال مطلوب' : 'الجوال غير مطلوب') + '</span>';
        html += '</div>';
        if (p.NOTES) html += '<hr class="my-3"><div style="font-size:.82rem">' + p.NOTES + '</div>';
        $('#provDetailsBody').html(html);
        bootstrap.Modal.getOrCreateInstance(document.getElementById('provDetailsModal')).show();
    }

    var _provListCurrent = { id: null, name: '' };

    function showAccounts(provId, provName){
        _provListCurrent = { id: provId, name: provName };
        $('#provListIcon').attr('class', 'fa fa-users me-1');
        $('#provListTitle').text('حسابات الموظفين — ' + provName);
        $('#provListBody').html('<div class="text-center py-4 text-muted"><i class="fa fa-spinner fa-spin"></i> جاري التحميل...</div>');
        $('#provListToolbar').addClass('d-none');
        $('#provListSearch').val('');
        bootstrap.Modal.getOrCreateInstance(document.getElementById('provListModal')).show();
        get_data(provAccountsUrl, { provider_id: provId, only_active: 0 }, function(resp){
            var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
            var rows = (j && j.data) ? j.data : [];
            if (rows.length === 0) {
                $('#provListBody').html('<div class="alert alert-light text-center py-3">لا توجد حسابات</div>');
                return;
            }
            var html = '<table class="table table-bordered table-sm acc-list-tbl mb-0" style="font-size:.78rem"><thead><tr>';
            html += '<th style="width:40px">#</th><th>الموظف</th><th>المقر</th><th>رقم الحساب</th><th>IBAN</th><th>الفرع</th><th style="width:80px">افتراضي</th><th style="width:75px">الحالة</th>';
            html += '</tr></thead><tbody>';
            for (var i = 0; i < rows.length; i++) {
                var r = rows[i];
                var acc = r.ACCOUNT_NO || r.WALLET_NUMBER || '—';
                // searchable text on the row (lowercase, all key fields combined)
                var search = (
                    (r.EMP_NO||'') + ' ' + (r.EMP_NAME||'') + ' ' + (r.BRANCH_NAME||'') + ' ' +
                    (r.ACCOUNT_NO||'') + ' ' + (r.WALLET_NUMBER||'') + ' ' + (r.IBAN||'') + ' ' +
                    (r.BANK_BRANCH_NAME||'') + ' ' + (r.OWNER_NAME||'')
                ).toString().toLowerCase();
                html += '<tr data-search="' + search.replace(/"/g, '&quot;') + '">';
                html += '<td class="row-num">' + (i+1) + '</td>';
                html += '<td><b>' + (r.EMP_NO||'') + '</b><br><small>' + (r.EMP_NAME||'') + '</small></td>';
                html += '<td>' + (r.BRANCH_NAME||'—') + '</td>';
                html += '<td style="direction:ltr;font-family:monospace">' + acc + '</td>';
                html += '<td style="direction:ltr;font-family:monospace;font-size:.7rem">' + (r.IBAN||'—') + '</td>';
                html += '<td>' + (r.BANK_BRANCH_NAME||'—') + '</td>';
                html += '<td>' + (parseInt(r.IS_DEFAULT) ? '<span class="req-badge ok">افتراضي</span>' : '—') + '</td>';
                html += '<td>' + (parseInt(r.IS_ACTIVE) ? '<span class="req-badge ok">نشط</span>' : '<span class="req-badge none">موقوف</span>') + '</td>';
                html += '</tr>';
            }
            html += '</tbody></table>';
            $('#provListBody').html(html);

            // فعّل الـ toolbar وحدّث العدّادات
            $('#provListCountTotal').text(rows.length);
            $('#provListCountVisible').text(rows.length);
            $('#provListToolbar').removeClass('d-none');
        }, 'json');
    }

    // البحث المحلي: يفلتر الصفوف بدون round-trip للسيرفر
    function _filterProvList(){
        var q = ($('#provListSearch').val() || '').trim().toLowerCase();
        var rows = $('#provListBody table.acc-list-tbl tbody tr');
        if (!q) {
            rows.removeClass('row-hidden');
            // أعد ترقيم الصفوف 1..N
            rows.each(function(i){ $(this).find('.row-num').text(i+1); });
            $('#provListCountVisible').text(rows.length);
            return;
        }
        var visible = 0;
        rows.each(function(){
            var match = ($(this).attr('data-search') || '').indexOf(q) !== -1;
            if (match) {
                $(this).removeClass('row-hidden');
                visible++;
                $(this).find('.row-num').text(visible);
            } else {
                $(this).addClass('row-hidden');
            }
        });
        $('#provListCountVisible').text(visible);
    }

    // البحث: input بـ debounce بسيط
    var _provListSearchTimer;
    $(document).on('input', '#provListSearch', function(){
        clearTimeout(_provListSearchTimer);
        _provListSearchTimer = setTimeout(_filterProvList, 120);
    });
    $(document).on('click', '#provListSearchClear', function(){
        $('#provListSearch').val('').trigger('input').focus();
    });

    // تصدير Excel: يفتح URL مباشر بـ provider_id
    $(document).on('click', '#provListExportBtn', function(){
        if (!_provListCurrent.id) return;
        window.location.href = provAccountsXlsx + '?provider_id=' + encodeURIComponent(_provListCurrent.id) + '&only_active=0';
    });

    function showBranches(provId, provName){
        _curBranchProv = { id: provId, name: provName };
        $('#provListIcon').attr('class', 'fa fa-building me-1');
        $('#provListTitle').text('الفروع — ' + provName);
        renderBranchesList();
        bootstrap.Modal.getOrCreateInstance(document.getElementById('provListModal')).show();
    }

    function renderBranchesList(){
        if(!_curBranchProv.id) return;
        $('#provListBody').html('<div class="text-center py-4 text-muted"><i class="fa fa-spinner fa-spin"></i> جاري التحميل...</div>');
        get_data(provBranchesUrl, { provider_id: _curBranchProv.id }, function(resp){
            var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
            var rows = (j && j.data) ? j.data : [];
            var addBtn = '<div class="d-flex justify-content-end mb-2">' +
                '<button class="btn btn-primary btn-sm" onclick="openBranchAdd()"><i class="fa fa-plus"></i> فرع جديد</button>' +
                '</div>';
            if (rows.length === 0) {
                $('#provListBody').html(addBtn + '<div class="alert alert-light text-center py-3">لا توجد فروع لهذا البنك</div>');
                return;
            }
            var html = addBtn;
            html += '<table class="table table-bordered table-sm" style="font-size:.78rem"><thead class="table-light"><tr>';
            html += '<th style="width:35px">#</th><th style="width:80px">رقم الفرع</th><th>اسم الفرع</th><th style="width:90px">رقم الطباعة</th><th>العنوان</th><th style="width:110px">الهاتف</th><th style="width:75px">الحالة</th><th style="width:95px">إجراءات</th>';
            html += '</tr></thead><tbody>';
            for (var i = 0; i < rows.length; i++) {
                var r = rows[i];
                var brJson = JSON.stringify(r).replace(/"/g, '&quot;');
                var isActive = parseInt(r.IS_ACTIVE) === 1;
                var brNoBadge = r.LEGACY_BANK_NO ? '<span class="badge bg-primary" style="font-size:.78rem;direction:ltr">' + r.LEGACY_BANK_NO + '</span>' : '<span class="text-muted">—</span>';
                html += '<tr>';
                html += '<td>' + (i+1) + '</td>';
                html += '<td>' + brNoBadge + '</td>';
                html += '<td><b>' + (r.BRANCH_NAME||'') + '</b></td>';
                html += '<td>' + (r.PRINT_NO||'—') + '</td>';
                html += '<td>' + (r.ADDRESS||'—') + '</td>';
                html += '<td style="direction:ltr">' + (r.PHONE1||'—') + '</td>';
                html += '<td>' + (isActive ? '<span class="req-badge ok">نشط</span>' : '<span class="req-badge none">موقوف</span>') + '</td>';
                html += '<td class="text-center">';
                html += '<button class="btn btn-sm btn-outline-primary" title="تعديل" onclick="editBranchModalFromAttr(this)" data-branch="' + brJson + '"><i class="fa fa-pencil"></i></button> ';
                html += '<button class="btn btn-sm ' + (isActive ? 'btn-outline-warning' : 'btn-outline-success') + '" title="' + (isActive ? 'إيقاف' : 'تفعيل') + '" onclick="toggleBranchActiveFromAttr(this)" data-branch="' + brJson + '"><i class="fa ' + (isActive ? 'fa-pause' : 'fa-play') + '"></i></button>';
                html += '</td>';
                html += '</tr>';
            }
            html += '</tbody></table>';
            $('#provListBody').html(html);
        }, 'json');
    }

    function editBranchModalFromAttr(btn){
        try { editBranchModal(JSON.parse(btn.getAttribute('data-branch'))); }
        catch(e){ danger_msg('خطأ', 'فشل قراءة بيانات الفرع'); }
    }
    function toggleBranchActiveFromAttr(btn){
        try { toggleBranchActive(JSON.parse(btn.getAttribute('data-branch'))); }
        catch(e){ danger_msg('خطأ', 'فشل قراءة بيانات الفرع'); }
    }

    // ══════════ Branch CRUD ══════════
    function openBranchAdd(){
        if(!_curBranchProv.id){ danger_msg('تنبيه', 'اختر بنكاً أولاً'); return; }
        $('#brEditModalTitle').text('فرع جديد — ' + _curBranchProv.name);
        $('#branchEditForm')[0].reset();
        $('#br_id').val('');
        $('#br_provider_id').val(_curBranchProv.id);
        $('#br_provider_name').val(_curBranchProv.name);
        $('#br_active_grp').hide();
        bootstrap.Modal.getOrCreateInstance(document.getElementById('branchEditModal')).show();
    }

    function editBranchModal(b){
        $('#brEditModalTitle').text('تعديل: ' + (b.BRANCH_NAME || ''));
        $('#br_id').val(b.BRANCH_ID || '');
        $('#br_provider_id').val(b.PROVIDER_ID || _curBranchProv.id || '');
        $('#br_provider_name').val(b.PROVIDER_NAME || _curBranchProv.name || '');
        $('#br_name').val(b.BRANCH_NAME || '');
        $('#br_legacy_bank_no').val(b.LEGACY_BANK_NO || '');
        $('#br_print_no').val(b.PRINT_NO || '');
        $('#br_address').val(b.ADDRESS || '');
        $('#br_phone1').val(b.PHONE1 || '');
        $('#br_phone2').val(b.PHONE2 || '');
        $('#br_fax').val(b.FAX || '');
        $('#br_is_active').val(b.IS_ACTIVE != null ? b.IS_ACTIVE : 1);
        $('#br_active_grp').show();
        bootstrap.Modal.getOrCreateInstance(document.getElementById('branchEditModal')).show();
    }

    function saveBranch(){
        var brNo = $('#br_legacy_bank_no').val();
        if(!brNo || parseInt(brNo) <= 0){ warning_msg('تنبيه', 'رقم الفرع مطلوب'); return; }
        if(!$('#br_name').val().trim()){ warning_msg('تنبيه', 'اسم الفرع مطلوب'); return; }
        if(!$('#br_provider_id').val()){ warning_msg('تنبيه', 'البنك الرئيسي مفقود'); return; }
        var f = $('#branchEditForm').serialize();
        get_data(branchSaveUrl, f, function(resp){
            var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
            if(j.ok){
                success_msg('تم', j.msg);
                bootstrap.Modal.getOrCreateInstance(document.getElementById('branchEditModal')).hide();
                renderBranchesList();
                loadUnlinkedCount();
            } else { danger_msg('خطأ', j.msg); }
        }, 'json');
    }

    function toggleBranchActive(b){
        var newActive = parseInt(b.IS_ACTIVE) === 1 ? 0 : 1;
        var verb = newActive ? 'تفعيل' : 'إيقاف';
        if(!confirm(verb + ' الفرع: ' + (b.BRANCH_NAME || ''))) return;
        var data = {
            branch_id:      b.BRANCH_ID,
            provider_id:    b.PROVIDER_ID || _curBranchProv.id || '',
            name:           b.BRANCH_NAME || '',
            legacy_bank_no: b.LEGACY_BANK_NO || '',
            print_no:       b.PRINT_NO || '',
            address:        b.ADDRESS || '',
            phone1:         b.PHONE1 || '',
            phone2:         b.PHONE2 || '',
            fax:            b.FAX || '',
            is_active:      newActive
        };
        get_data(branchSaveUrl, data, function(resp){
            var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
            if(j.ok){ success_msg('تم', verb + ' الفرع'); renderBranchesList(); }
            else    { danger_msg('خطأ', j.msg); }
        }, 'json');
    }

    // ══════════ Unlinked Branches ══════════
    function loadUnlinkedCount(){
        get_data(branchesAllUrl, {}, function(resp){
            var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
            var rows = (j && j.data) ? j.data : [];
            var unlinked = rows.filter(function(r){ return !r.PROVIDER_ID; });
            if(unlinked.length > 0){
                $('#unlinkedCount').text(unlinked.length);
                $('#unlinkedBanner').removeClass('d-none');
            } else {
                $('#unlinkedBanner').addClass('d-none');
            }
        }, 'json');
    }

    function showUnlinkedBranches(){
        $('#unlinkedBranchesBody').html('<div class="text-center py-4 text-muted"><i class="fa fa-spinner fa-spin"></i> جاري التحميل...</div>');
        bootstrap.Modal.getOrCreateInstance(document.getElementById('unlinkedBranchesModal')).show();
        // نحضّر قائمة البنوك للربط من جديد لضمان أحدث بيانات
        get_data(branchesAllUrl, {}, function(resp){
            var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
            var rows = (j && j.data) ? j.data : [];
            var unlinked = rows.filter(function(r){ return !r.PROVIDER_ID; });
            if(unlinked.length === 0){
                $('#unlinkedBranchesBody').html('<div class="alert alert-success text-center py-3"><i class="fa fa-check-circle"></i> ممتاز — لا توجد فروع غير مرتبطة</div>');
                loadUnlinkedCount();
                return;
            }
            var optsHtml = '<option value="">— اختر البنك —</option>';
            _fetchActiveBanks(function(banks){
                banks.forEach(function(p){
                    optsHtml += '<option value="'+p.PROVIDER_ID+'">'+ (p.PROVIDER_NAME||'') +'</option>';
                });
                var html = '<table class="table table-bordered table-sm" style="font-size:.8rem"><thead class="table-light"><tr>';
                html += '<th style="width:35px">#</th><th>اسم الفرع</th><th style="width:90px">رقم قديم</th><th>اربط بـ:</th><th style="width:90px">إجراء</th>';
                html += '</tr></thead><tbody>';
                for (var i = 0; i < unlinked.length; i++) {
                    var r = unlinked[i];
                    html += '<tr data-bid="' + r.BRANCH_ID + '">';
                    html += '<td>' + (i+1) + '</td>';
                    html += '<td><b>' + (r.BRANCH_NAME||'') + '</b></td>';
                    html += '<td>' + (r.LEGACY_BANK_NO||'—') + '</td>';
                    html += '<td><select class="form-select form-select-sm unlinked-select">' + optsHtml + '</select></td>';
                    html += '<td><button class="btn btn-sm btn-success" onclick="linkUnlinkedRow(this,' + r.BRANCH_ID + ')"><i class="fa fa-link"></i> ربط</button></td>';
                    html += '</tr>';
                }
                html += '</tbody></table>';
                $('#unlinkedBranchesBody').html(html);
            });
        }, 'json');
    }

    var _activeBanksCache = null;
    function _fetchActiveBanks(cb){
        if(_activeBanksCache){ cb(_activeBanksCache); return; }
        get_data(providersJsonUrl, {type: 1}, function(resp){
            var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
            _activeBanksCache = (j && j.data) ? j.data : [];
            cb(_activeBanksCache);
        }, 'json');
    }

    function linkUnlinkedRow(btn, bid){
        var tr = $(btn).closest('tr');
        var pid = tr.find('.unlinked-select').val();
        if(!pid){ warning_msg('تنبيه', 'اختر البنك'); return; }
        get_data(branchLinkUrl, { branch_id: bid, provider_id: pid }, function(resp){
            var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
            if(j.ok){
                success_msg('تم', j.msg);
                tr.fadeOut(200, function(){ $(this).remove(); showUnlinkedBranches(); });
            } else { danger_msg('خطأ', j.msg); }
        }, 'json');
    }

    // ══════════ Bulk ══════════
    function _selectedIds(){
        var ids = [];
        $('.prov-cb:checked').each(function(){ ids.push($(this).data('id')); });
        return ids;
    }

    function _refreshBulkBar(){
        var ids = _selectedIds();
        if(ids.length === 0){ $('#bulkBar').addClass('d-none').removeClass('d-flex'); }
        else { $('#bulkBar').removeClass('d-none').addClass('d-flex'); $('#bulkCount').text(ids.length); }
    }

    function clearBulk(){
        $('.prov-cb').prop('checked', false);
        $('tr.selected').removeClass('selected');
        _refreshBulkBar();
    }

    function bulkAction(action){
        var ids = _selectedIds();
        if(ids.length === 0){ warning_msg('تنبيه', 'اختر مزوداً واحداً على الأقل'); return; }
        var label = action === 'ACTIVATE' ? 'تفعيل' : action === 'DEACTIVATE' ? 'إيقاف' : 'حذف';
        var nl = String.fromCharCode(10);
        if(!confirm(label + ' ' + ids.length + ' مزود؟' + nl + nl + (action === 'DELETE' ? 'سيفشل لمن عنده حسابات/فروع.' : ''))) return;
        get_data(provBulkUrl, { ids: ids.join(','), action: action }, function(resp){
            var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
            if(j.ok){ success_msg('تم', j.msg); search(); }
            else    { danger_msg('خطأ', j.msg); }
        }, 'json');
    }

</script>
SCRIPT;

sec_scripts($scripts);
?>
