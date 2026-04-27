<?php
$MODULE_NAME = 'payment_accounts';
$TB_NAME     = 'payment_accounts';

$back_url      = base_url("$MODULE_NAME/$TB_NAME");
$save_url      = base_url("$MODULE_NAME/$TB_NAME/branch_save");
$link_url      = base_url("$MODULE_NAME/$TB_NAME/branch_link_provider");
$providers_url = base_url("$MODULE_NAME/$TB_NAME/providers_list_json");

$branches_arr = is_array($branches) ? $branches : [];

// تجميع حسب البنك الرئيسي
$grouped = [];
$unlinked = [];
foreach ($branches_arr as $b) {
    $pid = $b['PROVIDER_ID'] ?? null;
    if (empty($pid)) { $unlinked[] = $b; continue; }
    if (!isset($grouped[$pid])) {
        $grouped[$pid] = [
            'name' => $b['PROVIDER_NAME'] ?? '—',
            'rows' => []
        ];
    }
    $grouped[$pid]['rows'][] = $b;
}
?>

<style>
.bank-group{border:1px solid #e2e8f0;border-radius:10px;margin-bottom:.75rem;background:#fff;overflow:hidden}
.bank-group .hdr{background:#f1f5f9;padding:.55rem 1rem;display:flex;align-items:center;gap:.5rem;cursor:pointer;font-weight:700;font-size:.88rem}
.bank-group .hdr .count{background:#1e293b;color:#fff;padding:.15em .55em;border-radius:10px;font-size:.7rem;font-weight:600}
.bank-group .body{padding:.5rem}
.branch-row{display:flex;align-items:center;gap:.5rem;padding:.45rem .75rem;border-bottom:1px solid #f1f5f9;font-size:.82rem}
.branch-row:last-child{border-bottom:0}
.branch-row:hover{background:#f8fafc}
.branch-row .b-num{background:#f1f5f9;color:#475569;padding:.15em .5em;border-radius:5px;font-size:.7rem;font-weight:700;min-width:45px;text-align:center}
.branch-row .b-print{color:#7c3aed;font-size:.7rem;direction:ltr}
.branch-row .b-name{flex:1;color:#1e293b}
.branch-row .actions{display:flex;gap:.25rem}
.unlinked-box{background:#fee2e2;border:2px dashed #fca5a5;border-radius:10px;padding:.75rem 1rem;margin-bottom:.75rem}
</style>

<div class="page-header">
    <div><h1 class="page-title"><?= $title ?></h1></div>
    <div class="ms-auto pageheader-btn">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= $back_url ?>">إدارة حسابات الصرف</a></li>
            <li class="breadcrumb-item active">الفروع</li>
        </ol>
    </div>
</div>

<div class="row"><div class="col-lg-12"><div class="card">
    <div class="card-header d-flex align-items-center flex-wrap gap-2">
        <h3 class="card-title mb-0"><i class="fa fa-building me-2"></i> فروع البنوك</h3>
        <span class="text-muted" style="font-size:.78rem">(<?= count($branches_arr) ?> فرع)</span>
        <div class="ms-auto d-flex gap-1">
            <input type="text" id="branchSearch" class="form-control form-control-sm" placeholder="🔍 بحث بالاسم..." style="width:220px">
            <button class="btn btn-primary btn-sm" onclick="openBranchModal()"><i class="fa fa-plus"></i> فرع جديد</button>
            <a href="<?= $back_url ?>" class="btn btn-light btn-sm"><i class="fa fa-arrow-right me-1"></i> رجوع</a>
        </div>
    </div>
    <div class="card-body">

        <?php if (count($unlinked) > 0): ?>
        <div class="unlinked-box">
            <div class="fw-bold" style="color:#991b1b;font-size:.88rem">
                <i class="fa fa-exclamation-triangle"></i>
                ⚠ <?= count($unlinked) ?> فرع غير مرتبط ببنك رئيسي
            </div>
            <div class="mt-2">
                <?php foreach ($unlinked as $b): ?>
                <div class="branch-row" style="background:#fff;border-radius:6px;margin-bottom:.3rem;border:0">
                    <span class="b-num"><?= $b['LEGACY_BANK_NO'] ?? '' ?></span>
                    <span class="b-name"><?= htmlspecialchars($b['BRANCH_NAME'] ?? '') ?></span>
                    <span class="text-muted" style="font-size:.7rem">B_NO: <?= $b['LEGACY_MASTER'] ?? '—' ?></span>
                    <div class="actions">
                        <button class="btn btn-sm btn-outline-primary" onclick='linkBranch(<?= $b['BRANCH_ID'] ?>)' title="ربط ببنك">
                            <i class="fa fa-link"></i> ربط
                        </button>
                        <button class="btn btn-sm btn-outline-warning" onclick='editBranch(<?= json_encode($b, JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT) ?>)' title="تعديل">
                            <i class="fa fa-pencil"></i>
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- الفروع المجمَّعة حسب البنك -->
        <?php if (count($grouped) === 0 && count($unlinked) === 0): ?>
            <div class="alert alert-light text-muted py-4 text-center">
                <i class="fa fa-inbox fa-2x d-block mb-2"></i>
                لا توجد فروع
            </div>
        <?php else: foreach ($grouped as $pid => $gr): ?>
        <div class="bank-group" data-pid="<?= $pid ?>">
            <div class="hdr" onclick="$(this).next().slideToggle()">
                <i class="fa fa-bank"></i>
                <span><?= htmlspecialchars($gr['name']) ?></span>
                <span class="count"><?= count($gr['rows']) ?></span>
                <span class="ms-auto"><i class="fa fa-chevron-down" style="font-size:.7rem"></i></span>
            </div>
            <div class="body">
                <?php foreach ($gr['rows'] as $b): ?>
                <div class="branch-row" data-branch-id="<?= $b['BRANCH_ID'] ?>">
                    <span class="b-num"><?= $b['LEGACY_BANK_NO'] ?? '' ?></span>
                    <span class="b-name"><?= htmlspecialchars($b['BRANCH_NAME'] ?? '') ?></span>
                    <?php if (!empty($b['PRINT_NO'])): ?>
                        <span class="b-print">طباعة: <?= $b['PRINT_NO'] ?></span>
                    <?php endif; ?>
                    <?php if ((int)($b['IS_ACTIVE'] ?? 1) === 0): ?>
                        <span class="badge bg-warning text-dark" style="font-size:.65rem">موقوف</span>
                    <?php endif; ?>
                    <div class="actions">
                        <button class="btn btn-sm btn-outline-primary" title="تعديل" onclick='editBranch(<?= json_encode($b, JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT) ?>)'>
                            <i class="fa fa-pencil"></i>
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endforeach; endif; ?>

    </div>
</div></div></div>


<!-- ══════════ Modal: إضافة/تعديل فرع ══════════ -->
<div class="modal fade" id="branchModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border:0;border-radius:12px;overflow:hidden">
            <div class="modal-header py-2" style="background:#1e293b">
                <h6 class="modal-title text-white fw-bold"><i class="fa fa-building me-1"></i> <span id="branchModalTitle">فرع جديد</span></h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="branchForm">
                    <input type="hidden" id="br_id" name="branch_id">

                    <div class="row g-2">
                        <div class="col-md-7">
                            <label class="fw-bold" style="font-size:.78rem">البنك الرئيسي <span class="text-danger">*</span></label>
                            <select id="br_provider_id" name="provider_id" class="form-select">
                                <option value="">— جارٍ التحميل —</option>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label class="fw-bold" style="font-size:.78rem">رقم الطباعة (PRINT_NO)</label>
                            <input type="number" id="br_print_no" name="print_no" class="form-control ltr" style="direction:ltr">
                        </div>
                    </div>

                    <div class="row g-2 mt-1">
                        <div class="col-md-12">
                            <label class="fw-bold" style="font-size:.78rem">اسم الفرع <span class="text-danger">*</span></label>
                            <input type="text" id="br_name" name="name" class="form-control" placeholder="بنك فلسطين - فرع الرمال">
                        </div>
                    </div>

                    <hr class="my-2">
                    <h6 class="fw-bold" style="font-size:.82rem;color:#64748b">بيانات تواصل (اختيارية)</h6>

                    <div class="row g-2">
                        <div class="col-md-12">
                            <label class="fw-bold" style="font-size:.78rem">العنوان</label>
                            <input type="text" id="br_address" name="address" class="form-control">
                        </div>
                    </div>

                    <div class="row g-2 mt-1">
                        <div class="col-md-4">
                            <label class="fw-bold" style="font-size:.78rem">هاتف 1</label>
                            <input type="text" id="br_phone1" name="phone1" class="form-control ltr" style="direction:ltr">
                        </div>
                        <div class="col-md-4">
                            <label class="fw-bold" style="font-size:.78rem">هاتف 2</label>
                            <input type="text" id="br_phone2" name="phone2" class="form-control ltr" style="direction:ltr">
                        </div>
                        <div class="col-md-4">
                            <label class="fw-bold" style="font-size:.78rem">فاكس</label>
                            <input type="text" id="br_fax" name="fax" class="form-control ltr" style="direction:ltr">
                        </div>
                    </div>

                    <div class="row g-2 mt-1" id="br_active_grp" style="display:none">
                        <div class="col-md-4">
                            <label class="fw-bold" style="font-size:.78rem">الحالة</label>
                            <select id="br_is_active" name="is_active" class="form-select">
                                <option value="1">نشط</option>
                                <option value="0">موقوف</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer py-2">
                <button class="btn btn-light btn-sm" data-bs-dismiss="modal">إلغاء</button>
                <button class="btn btn-primary btn-sm" onclick="saveBranch()"><i class="fa fa-save me-1"></i> حفظ</button>
            </div>
        </div>
    </div>
</div>


<!-- ══════════ Modal: ربط فرع ببنك ══════════ -->
<div class="modal fade" id="linkModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border:0;border-radius:12px">
            <div class="modal-header py-2" style="background:#0f172a">
                <h6 class="modal-title text-white fw-bold"><i class="fa fa-link me-1"></i> ربط الفرع بالبنك الرئيسي</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="link_branch_id">
                <p class="text-muted" style="font-size:.82rem">اختر البنك الرئيسي الذي ينتمي إليه هذا الفرع:</p>
                <select id="link_provider_id" class="form-select"></select>
            </div>
            <div class="modal-footer py-2">
                <button class="btn btn-light btn-sm" data-bs-dismiss="modal">إلغاء</button>
                <button class="btn btn-primary btn-sm" onclick="doLink()"><i class="fa fa-link me-1"></i> ربط</button>
            </div>
        </div>
    </div>
</div>


<?php echo AntiForgeryToken(); ?>

<script>
(function waitJQ(){
    if (typeof jQuery === 'undefined') { setTimeout(waitJQ, 50); return; }
    jQuery(function($){

var brSaveUrl      = "<?= $save_url ?>";
var brLinkUrl      = "<?= $link_url ?>";
var providersUrl   = "<?= $providers_url ?>";

var _providersCache = null;

function loadProviders(cb){
    if(_providersCache){ cb(_providersCache); return; }
    get_data(providersUrl, {type: 1}, function(resp){
        var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
        _providersCache = (j && j.data) ? j.data : [];
        cb(_providersCache);
    }, 'json');
}

window.openBranchModal = function(){
    $('#branchModalTitle').text('فرع جديد');
    $('#branchForm')[0].reset();
    $('#br_id').val('');
    $('#br_active_grp').hide();
    fillProvidersSelect('#br_provider_id');
    $('#branchModal').modal('show');
}

window.editBranch = function(b){
    $('#branchModalTitle').text('تعديل فرع');
    $('#br_id').val(b.BRANCH_ID);
    $('#br_name').val(b.BRANCH_NAME || '');
    $('#br_print_no').val(b.PRINT_NO || '');
    $('#br_address').val(b.ADDRESS || '');
    $('#br_phone1').val(b.PHONE1 || '');
    $('#br_phone2').val(b.PHONE2 || '');
    $('#br_fax').val(b.FAX || '');
    $('#br_is_active').val(b.IS_ACTIVE != null ? b.IS_ACTIVE : 1);
    $('#br_active_grp').show();
    fillProvidersSelect('#br_provider_id', function(){
        $('#br_provider_id').val(b.PROVIDER_ID || '');
    });
    $('#branchModal').modal('show');
}

function fillProvidersSelect(selector, cb){
    loadProviders(function(list){
        var html = '<option value="">— اختر البنك —</option>';
        list.forEach(function(p){
            html += '<option value="'+p.PROVIDER_ID+'">'+ (p.PROVIDER_NAME||'') +'</option>';
        });
        $(selector).html(html);
        if(typeof cb === 'function') cb();
    });
}

window.saveBranch = function(){
    if(!$('#br_name').val().trim()){ danger_msg('تنبيه','اسم الفرع مطلوب'); return; }
    if(!$('#br_provider_id').val()){ danger_msg('تنبيه','اختر البنك الرئيسي'); return; }
    var f = $('#branchForm').serialize();
    get_data(brSaveUrl, f, function(resp){
        var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
        if(j.ok){ success_msg('تم', j.msg); $('#branchModal').modal('hide'); reload_Page(); }
        else { danger_msg('خطأ', j.msg); }
    }, 'json');
}

window.linkBranch = function(bid){
    $('#link_branch_id').val(bid);
    fillProvidersSelect('#link_provider_id');
    $('#linkModal').modal('show');
}

window.doLink = function(){
    var bid = $('#link_branch_id').val();
    var pid = $('#link_provider_id').val();
    if(!pid){ danger_msg('تنبيه','اختر البنك'); return; }
    get_data(brLinkUrl, {branch_id: bid, provider_id: pid}, function(resp){
        var j = (typeof resp === 'string') ? JSON.parse(resp) : resp;
        if(j.ok){ success_msg('تم', j.msg); $('#linkModal').modal('hide'); reload_Page(); }
        else { danger_msg('خطأ', j.msg); }
    }, 'json');
}

// بحث سريع
$('#branchSearch').on('input', function(){
    var q = $(this).val().trim().toLowerCase();
    $('.branch-row').each(function(){
        var name = $(this).find('.b-name').text().toLowerCase();
        $(this).toggle(!q || name.indexOf(q) >= 0);
    });
    // إخفاء المجموعات الفارغة
    $('.bank-group').each(function(){
        var visible = $(this).find('.branch-row:visible').length;
        $(this).toggle(visible > 0);
    });
});

    });  // jQuery(function($){
})();    // waitJQ
</script>
