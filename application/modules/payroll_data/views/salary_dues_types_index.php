<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

$MODULE_NAME = 'payroll_data';
$TB_NAME     = 'salary_dues_types';

$delete_url = base_url("$MODULE_NAME/$TB_NAME/delete");
$get_url    = base_url("$MODULE_NAME/$TB_NAME/get_id");
$edit_url   = base_url("$MODULE_NAME/$TB_NAME/edit");
$create_url = base_url("$MODULE_NAME/$TB_NAME/create");

$can_create = HaveAccess($create_url);
$can_edit   = HaveAccess($edit_url);
$can_delete = HaveAccess($delete_url);
$can_modify = $can_create || $can_edit;

echo AntiForgeryToken();
?>

<style>
    /* ===== Card Header ===== */
    .dues-card-header {
        background: linear-gradient(135deg, #1a73e8, #0d47a1) !important;
        padding: 12px 20px !important;
    }
    .dues-card-header .card-title {
        color: #fff !important;
        font-size: 16px;
    }
    .dues-card-header .card-options .btn {
        color: #fff;
        border-color: rgba(255,255,255,0.4);
        font-size: 12px;
    }
    .dues-card-header .card-options .btn:hover {
        background: rgba(255,255,255,0.2);
        border-color: #fff;
    }
    .dues-card-header .card-options .btn-success { background: #27ae60; border-color: #27ae60; }
    .dues-card-header .card-options .btn-primary { background: #2980b9; border-color: #2980b9; }
    .dues-card-header .card-options .btn-danger  { background: #c0392b; border-color: #c0392b; }

    /* ===== Tree Styling ===== */
    .tree-container {
        min-height: 300px;
        padding: 10px;
        background: #fafbfc;
        border: 1px solid #e9ecef;
        border-radius: 8px;
    }

    .tree li > span.tree-node {
        padding: 7px 14px;
        border-radius: 6px;
        cursor: pointer;
        display: inline-block;
        margin: 3px 0;
        transition: all 0.2s ease;
        font-size: 14px;
        font-weight: 500;
        color: #333;
        background: #fff;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
    }
    /* Hover & Selected - ADD (green) */
    .tree li > span.tree-node.lt-1:hover {
        background: #eafaf1;
        transform: translateX(-3px);
        box-shadow: 0 2px 8px rgba(39,174,96,0.15);
    }
    .tree li > span.tree-node.selected.lt-1 {
        background: #d4efdf !important;
        box-shadow: 0 2px 8px rgba(39,174,96,0.3);
        color: #1e8449 !important;
    }

    /* Hover & Selected - DED (red) */
    .tree li > span.tree-node.lt-2:hover {
        background: #fdedec;
        transform: translateX(-3px);
        box-shadow: 0 2px 8px rgba(192,57,43,0.15);
    }
    .tree li > span.tree-node.selected.lt-2 {
        background: #fadbd8 !important;
        box-shadow: 0 2px 8px rgba(192,57,43,0.3);
        color: #b03a2e !important;
    }

    /* ADD (green) */
    .tree li > span.lt-1 {
        border-right: 4px solid #2ecc71;
    }
    .tree li > span.lt-1 .tree-icon { color: #27ae60; }

    /* DED (red) */
    .tree li > span.lt-2 {
        border-right: 4px solid #e74c3c;
    }
    .tree li > span.lt-2 .tree-icon { color: #c0392b; }

    /* Badges */
    .badge-lt {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 700;
        margin-right: 6px;
        vertical-align: middle;
        letter-spacing: 0.5px;
    }
    .lt-badge-1 {
        background: #d4efdf;
        color: #1e8449;
        border: 1px solid #a9dfbf;
    }
    .lt-badge-2 {
        background: #fadbd8;
        color: #b03a2e;
        border: 1px solid #f5b7b1;
    }

    /* Tree icon */
    .tree-icon {
        font-size: 16px;
        margin-left: 5px;
        vertical-align: middle;
    }

    /* Legend & Counters */
    .tree-legend {
        display: flex;
        gap: 20px;
        padding: 8px 16px;
        background: #f0f4f8;
        border-radius: 8px;
        border: 1px solid #dee2e6;
        font-size: 13px;
    }
    .legend-item {
        display: flex;
        align-items: center;
        gap: 6px;
        font-weight: 500;
    }
    .legend-dot {
        width: 14px;
        height: 14px;
        border-radius: 50%;
        display: inline-block;
        border: 2px solid #fff;
        box-shadow: 0 1px 3px rgba(0,0,0,0.2);
    }
    .legend-dot.add { background: #2ecc71; }
    .legend-dot.ded { background: #e74c3c; }
    .legend-count {
        background: #fff;
        padding: 1px 8px;
        border-radius: 10px;
        font-size: 11px;
        font-weight: 700;
        border: 1px solid #dee2e6;
    }

    /* Delete confirm modal */
    #deleteConfirmModal .modal-header { background: #c0392b; }
    #deleteConfirmModal .modal-title { color: #fff; }
</style>

<!-- PAGE-HEADER -->
<div class="page-header">
    <div>
        <h1 class="page-title"><?= $title ?></h1>
    </div>
    <div class="ms-auto pageheader-btn">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">الرواتب</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= $title ?></li>
        </ol>
    </div>
</div>
<!-- PAGE-HEADER END -->

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header dues-card-header">
                <h3 class="card-title mb-0">
                    <i class="fa fa-sitemap"></i> <?= $title ?>
                </h3>
                <div class="card-options">
                    <?php if ($can_create): ?>
                        <a class="btn btn-success btn-sm me-1" onclick="event.stopPropagation(); dues_type_create(); return false;" href="javascript:void(0);" data-bs-toggle="tooltip" title="إضافة بند جديد">
                            <i class="fa fa-plus"></i> جديد
                        </a>
                    <?php endif; ?>
                    <?php if ($can_edit): ?>
                        <a class="btn btn-primary btn-sm me-1" onclick="event.stopPropagation(); dues_type_get($.fn.tree.selected().attr('data-id')); return false;" href="javascript:void(0);" data-bs-toggle="tooltip" title="تحرير البند المحدد">
                            <i class="fa fa-edit"></i> تحرير
                        </a>
                    <?php endif; ?>
                    <?php if ($can_delete): ?>
                        <a class="btn btn-danger btn-sm me-1" onclick="javascript:dues_type_delete();" href="javascript:;" data-bs-toggle="tooltip" title="تعطيل البند المحدد">
                            <i class="fa fa-trash"></i> حذف
                        </a>
                    <?php endif; ?>
                    <a class="btn btn-outline-light btn-sm me-1" onclick="$.fn.tree.expandAll()" href="javascript:;" data-bs-toggle="tooltip" title="توسيع الكل">
                        <i class="fa fa-expand"></i> توسيع
                    </a>
                    <a class="btn btn-outline-light btn-sm me-1" onclick="$.fn.tree.collapseAll()" href="javascript:;" data-bs-toggle="tooltip" title="طي الكل">
                        <i class="fa fa-compress"></i> طي
                    </a>
                    <a class="btn btn-outline-light btn-sm" onclick="location.reload();" href="javascript:;" data-bs-toggle="tooltip" title="تحديث الشجرة">
                        <i class="fa fa-refresh"></i>
                    </a>
                </div>
            </div>

            <div class="card-body">
                <div class="row mb-3 align-items-center">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa fa-search"></i></span>
                            <input type="text" id="search-tree" class="form-control" placeholder="بحث في الشجرة...">
                        </div>
                    </div>
                    <div class="col-md-8 text-end">
                        <div class="tree-legend d-inline-flex">
                            <div class="legend-item">
                                <span class="legend-dot add"></span>
                                <span>بنود الاضافة</span>
                                <span class="legend-count" id="count_add">0</span>
                            </div>
                            <div class="legend-item">
                                <span class="legend-dot ded"></span>
                                <span>بنود الخصم</span>
                                <span class="legend-count" id="count_ded">0</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tree-container">
                    <?= $tree ?>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal: بيانات البند -->
<div class="modal fade" id="duesTypeModal" data-bs-backdrop="static" data-bs-keyboard="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #1a73e8, #0d47a1); color: #fff;">
                <h5 class="modal-title" style="color:#fff;"><i class="fa fa-sitemap"></i> بيانات بند المستحقات</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="form-horizontal" id="dues_type_form" method="post" action="<?= $create_url ?>" role="form" novalidate="novalidate">
                <div class="modal-body">

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label fw-bold">
                            <i class="fa fa-level-up text-muted"></i> البند الأب
                        </label>
                        <div class="col-sm-3">
                            <input type="text" name="parent_id" readonly id="txt_parent_id" class="form-control ltr bg-light">
                        </div>
                        <div class="col-sm-6">
                            <input type="text" name="parent_name" readonly id="txt_parent_name" class="form-control bg-light">
                        </div>
                    </div>

                    <div class="row mb-3" id="grp_type_id" style="display:none;">
                        <label class="col-sm-3 col-form-label fw-bold">
                            <i class="fa fa-hashtag text-muted"></i> رقم البند
                        </label>
                        <div class="col-sm-3">
                            <input type="number" name="type_id" id="txt_type_id" class="form-control ltr bg-light" readonly>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label fw-bold">
                            <i class="fa fa-tag text-muted"></i> اسم البند
                        </label>
                        <div class="col-sm-8">
                            <input type="text" data-val="true" data-val-required="حقل مطلوب" name="type_name" id="txt_type_name" class="form-control" dir="rtl">
                            <span class="field-validation-valid text-danger" data-valmsg-for="type_name" data-valmsg-replace="true"></span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label fw-bold">
                            <i class="fa fa-exchange text-muted"></i> نوع البند
                        </label>
                        <div class="col-sm-3">
                            <select name="line_type" id="dp_line_type" class="form-control">
                                <option value="1">اضافة</option>
                                <option value="2">خصم</option>
                            </select>
                        </div>
                        <div class="col-sm-3" id="line_type_preview">
                            <span class="badge-lt lt-badge-1" id="lt_preview_badge">اضافة</span>
                        </div>
                    </div>

                    <div class="row mb-3" id="grp_is_active" style="display:none;">
                        <label class="col-sm-3 col-form-label fw-bold">
                            <i class="fa fa-toggle-on text-muted"></i> الحالة
                        </label>
                        <div class="col-sm-3">
                            <select name="is_active" id="dp_is_active" class="form-control">
                                <option value="1">فعال</option>
                                <option value="0">غير فعال</option>
                            </select>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <?php if ($can_modify): ?>
                        <button type="submit" data-action="submit" class="btn btn-primary"><i class="fa fa-save"></i> حفظ البيانات</button>
                    <?php endif; ?>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa fa-times"></i> إغلاق</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: تأكيد الحذف -->
<div class="modal fade" id="deleteConfirmModal">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-exclamation-triangle"></i> تأكيد التعطيل</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <p class="mb-1">هل تريد تعطيل البند:</p>
                <h5 id="delete_item_name" class="text-danger fw-bold"></h5>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-danger" id="btn_confirm_delete"><i class="fa fa-check"></i> نعم، تعطيل</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa fa-times"></i> إلغاء</button>
            </div>
        </div>
    </div>
</div>

<?php
// pass permissions to JS
$js_can_modify = $can_modify ? 'true' : 'false';

$scripts = <<<SCRIPT

<script>

    var canModify = {$js_can_modify};

    $(function () {
        $('#duesTypeModal').on('shown.bs.modal', function () {
            if(canModify) $('#txt_type_name').focus();
        });
        $('#dues_types_tree').tree();

        // update preview badge on line_type change
        $('#dp_line_type').change(function(){
            updateLineTypePreview($(this).val());
        });

        // count nodes
        updateCounters();
    });

    function updateCounters(){
        var addCount = $('.tree span[data-linetype="1"]').length;
        var dedCount = $('.tree span[data-linetype="2"]').length;
        $('#count_add').text(addCount);
        $('#count_ded').text(dedCount);
    }

    function updateLineTypePreview(val){
        if(val == '1'){
            $('#lt_preview_badge').attr('class','badge-lt lt-badge-1').text('اضافة');
        } else {
            $('#lt_preview_badge').attr('class','badge-lt lt-badge-2').text('خصم');
        }
    }

    function setFormReadonly(readonly){
        if(readonly){
            $('#txt_type_id, #txt_type_name').prop('readonly', true);
            $('#dp_line_type, #dp_is_active').prop('disabled', true);
        }
    }

    function validate_dues_type(){
        if ($('#txt_type_name').val() == ''){
            alert('يجب ادخال اسم البند');
            return true;
        }
        return false;
    }

    function dues_type_create(){

        clearForm($('#dues_type_form'));
        $('#grp_is_active').hide();
        $('#grp_type_id').hide();
        $('#dp_is_active').val('1');
        $('#txt_type_name').prop('readonly', false);
        $('#dp_line_type').prop('disabled', false);

        var selected = $(".tree span.selected");

        if(selected.length > 0){
            var parentId       = $.fn.tree.selected().attr('data-id');
            var parentLineType = $.fn.tree.selected().attr('data-linetype');
            var parentName     = $('.tree li > span.selected').text();

            $('#txt_parent_id').val(parentId);
            $('#txt_parent_name').val(parentName);
            $('#dp_line_type').val(parentLineType);
            $('#dp_line_type').prop('disabled', true);
        } else {
            $('#txt_parent_id').val('');
            $('#txt_parent_name').val('');
            $('#dp_line_type').val('1');
            $('#dp_line_type').prop('disabled', false);
        }

        updateLineTypePreview($('#dp_line_type').val());

        $('#dues_type_form').attr('action','{$create_url}');
        var modal = new bootstrap.Modal(document.getElementById('duesTypeModal'));
        modal.show();
    }

    function dues_type_delete(){

        if($(".tree span.selected").length <= 0){
            warning_msg('تحذير','يجب اختيار بند من الشجرة');
            return;
        }

        var itemName = $('.tree li > span.selected').text().trim();
        $('#delete_item_name').text(itemName);

        var modal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
        modal.show();
    }

    // confirm delete button
    $('#btn_confirm_delete').click(function(){
        var elem = $.fn.tree.selected();
        var id   = elem.attr('data-id');
        var url  = '{$delete_url}';

        ajax_delete(url, id, function(data){
            if(data == '1'){
                $.fn.tree.removeElem(elem);
                success_msg('رسالة','تم تعطيل البند بنجاح');
                updateCounters();
            } else {
                danger_msg('تحذير', data);
            }
        });

        bootstrap.Modal.getInstance(document.getElementById('deleteConfirmModal')).hide();
    });

    function dues_type_get(id){

        if(!id){
            warning_msg('تحذير','يجب اختيار بند من الشجرة');
            return;
        }

        get_data('{$get_url}', {id: id}, function(data){

            $.each(data, function(i, item){

                $('#txt_parent_id').val(item.PARENT_ID);
                $('#txt_parent_name').val(item.PARENT_NAME);
                $('#txt_type_id').val(item.TYPE_ID);
                $('#grp_type_id').show();
                $('#txt_type_name').val(item.TYPE_NAME);
                $('#txt_type_name').prop('readonly', false);
                $('#dp_line_type').val(item.LINE_TYPE);
                $('#dp_line_type').prop('disabled', false);
                $('#dp_is_active').val(item.IS_ACTIVE);
                $('#grp_is_active').show();

                updateLineTypePreview(item.LINE_TYPE);

                // readonly if no permission
                if(!canModify){
                    setFormReadonly(true);
                }

                $('#dues_type_form').attr('action','{$edit_url}');
                var modal = new bootstrap.Modal(document.getElementById('duesTypeModal'));
                modal.show();
            });
        });
    }

    $('button[data-action="submit"]').click(function(e){

        e.preventDefault();

        if(validate_dues_type())
            return;

        var form = $(this).closest('form');

        // enable line_type before submit (disabled fields don't submit)
        var lineTypeDisabled = $('#dp_line_type').prop('disabled');
        if(lineTypeDisabled) $('#dp_line_type').prop('disabled', false);

        var isCreate = form.attr('action').indexOf('create') >= 0;

        ajax_insert_update(form, function(data){

            if(isCreate){
                var obj = data;
                $.fn.tree.add(
                    form.find('input[name="type_name"]').val(),
                    obj.id,
                    "javascript:dues_type_get('" + obj.id + "');"
                );
                // apply color class to new node
                var newSpan = $(".tree span[data-id='" + obj.id + "']");
                newSpan.addClass('tree-node lt-' + obj.LINE_TYPE);
                newSpan.attr('data-linetype', obj.LINE_TYPE);

                // expand parent to show new node
                var parentLi = newSpan.closest('ul').closest('li.parent_li');
                parentLi.find(' > ul > li').show();
                parentLi.find(' > span > i').removeClass('fa-plus').addClass('fa-minus');
                parentLi.find(' > span').attr('title', 'طي هذا الفرع');
            } else {
                var obj = data;
                if(obj.msg == 1){
                    $.fn.tree.selected().attr('data-linetype', obj.LINE_TYPE);
                    $.fn.tree.selected().removeClass('lt-1 lt-2').addClass('lt-' + obj.LINE_TYPE);
                    $.fn.tree.update(
                        form.find('input[name="type_name"]').val()
                    );
                }
            }

            if(lineTypeDisabled) $('#dp_line_type').prop('disabled', true);

            bootstrap.Modal.getInstance(document.getElementById('duesTypeModal')).hide();
            success_msg('رسالة','تم حفظ البيانات بنجاح');
            updateCounters();

        }, "json");

        if(lineTypeDisabled) $('#dp_line_type').prop('disabled', true);
    });

</script>
SCRIPT;

sec_scripts($scripts);

?>
