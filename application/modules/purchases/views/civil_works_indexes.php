<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

$delete_url = base_url('purchases/Civil_works/delete');
$get_url = base_url('purchases/Civil_works/get_id');
$edit_url = base_url('purchases/Civil_works/edit');
$create_url = base_url('purchases/Civil_works/create');


?>
<?= AntiForgeryToken() ?>
<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title; ?></div>

        <ul>
            <?php if (HaveAccess($create_url)): ?>
                <li><a onclick="class_create();" href="javascript:"><i class="glyphicon glyphicon-plus"></i>جديد
                    </a></li> <?php endif; ?>
            <?php if (HaveAccess($get_url, $edit_url)): ?>
                <li><a onclick="class_get($.fn.tree.selected().attr('data-id'));" href="javascript:"><i
                                class="glyphicon glyphicon-edit"></i>تحرير</a></li> <?php endif; ?>
            <?php if (HaveAccess($delete_url)): ?>
                <li><a onclick="class_delete();" href="javascript:"><i
                                class="glyphicon glyphicon-remove"></i>حذف</a></li> <?php endif; ?>
            <li><a onclick="$.fn.tree.expandAll()" href="javascript:"><i
                            class="glyphicon  glyphicon-sort-by-attributes-alt"></i>توسيع</a></li>
            <li><a onclick="$.fn.tree.collapseAll()" href="javascript:"><i
                            class="glyphicon  glyphicon-sort-by-attributes"></i>طي</a></li>
            <li><a onclick="<?= $help ?>" href="javascript:" class="help"><i class="icon icon-question-circle"></i></a>
            </li>
        </ul>

    </div>

    <div class="form-body">

        <div id="msg_container"></div>
        <div class="form-group">
            <div class="input-group col-sm-3">
                <span class="input-group-addon">  <i class="icon icon-search"></i></span>

                <input type="text" id="search-tree" class="form-control" placeholder="بحث">
            </div>
        </div>
        <?= $tree ?>

    </div>

</div>


<div class="modal fade" id="Civil_works_Modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                            class="sr-only">Close</span></button>
                <h4 class="modal-title">بيانات الخدمات اللوجستية</h4>
            </div>
            <form class="form-horizontal" id="Civil_works_from" method="post"
                  action="<?= base_url('purchases/Civil_works/create') ?>" role="form" novalidate="novalidate">
                <div class="modal-body">


                    <div class="form-group">
                        <label class="col-sm-1 control-label">الأب</label>
                        <div class="col-sm-1">
                            <input type="hidden" name="class_id" readonly id="txt_class_id"
                                   class="form-control ltr">
                            <input type="hidden" name="level" readonly id="txt_level"
                                   class="form-control ltr">
                            <input type="text" name="class_parent_id" readonly id="txt_class_parent_id"
                                   class="form-control ltr" "="">
                        </div>
                        <div class="col-sm-6">

                            <input type="text" name="class_parent_id_name" readonly id="txt_class_parent_id_name"
                                   class="form-control" "="">
                        </div>

                    </div>

                    <div class="form-group">
                        <label class="col-sm-1 control-label">اسم البند باللغة العربية</label>

                        <div class="col-sm-9">
                            <textarea data-val="true" data-val-required="حقل مطلوب" name="class_name"
                                      id="txt_class_name" class="form-control" rows="10"></textarea>

                            <span class="field-validation-valid" data-valmsg-for="menu_add"
                                  data-valmsg-replace="true"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-1 control-label">اسم البند باللغة الانجليزية</label>

                        <div class="col-sm-9">
                            <textarea data-val="false" data-val-required="حقل مطلوب" name="class_name_en"
                                      id="txt_class_name_en" class="form-control" rows="10"></textarea>

                            <span class="field-validation-valid" data-valmsg-for="class_name_en"
                                  data-valmsg-replace="true"></span>
                        </div>
                    </div>

                    <div class="form-group">

                            <label  class="col-sm-1 control-label">وصف البند</label>
                        <div class="col-md-9">
                            <input type="text" data-val="false"  data-val-required="حقل مطلوب" name="class_description" id="txt_class_description" class="form-control">
                            <span class="field-validation-valid" data-valmsg-for="class_description" data-valmsg-replace="true"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-1 control-label">الوحدة</label>
                        <div class="col-sm-3">
                            <select name="class_unit" data-val="false" data-val-required="حقل مطلوب" id="dp_class_unit"
                                    class="form-control select2">
                                <option></option>
                                <?php foreach ($class_unit as $row) : ?>
                                    <option value="<?= $row['CON_NO'] ?>">
                                        <?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <span class="field-validation-valid" data-valmsg-for="class_unit"
                                  data-valmsg-replace="true"></span>
                        </div>

                        <!-- <label class="col-sm-1 control-label">الكمية</label>
                        <div class="col-sm-3">

                            <input type="text" name="class_quantity" id="txt_class_quantity" data-val="true"
                                   data-val-required="حقل مطلوب"
                                   class="form-control ltr" "="">
                            <span class="field-validation-valid" data-valmsg-for="class_quantity"
                                  data-valmsg-replace="true"></span>
                        </div>

                    </div>
                    <div class="form-group hidden">
                        <label class="col-sm-1 control-label">سعر الوحدة</label>
                        <div class="col-sm-3">
                            <input type="text" name="class_price" id="txt_class_price" value="1" data-val="true"
                                   data-val-required="حقل مطلوب"
                                   class="form-control ltr" "="">
                            <span class="field-validation-valid" data-valmsg-for="class_price"
                                  data-valmsg-replace="true"></span>
                        </div>
                        -->
                        <label class="col-sm-1 control-label">العملة</label>
                        <div class="col-sm-3">

                            <select name="curr_id" data-val="false" data-val-required="حقل مطلوب" id="dp_curr_id"
                                    class="form-control select2">
                                <option></option>
                                <?php foreach ($currency as $row) : ?>
                                    <option value="<?= $row['CURR_ID'] ?>">
                                        <?= $row['CURR_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <span class="field-validation-valid" data-valmsg-for="curr_id"
                                  data-valmsg-replace="true"></span>
                        </div>

                    </div>
                    <div class="form-group">








                        <label class="col-sm-1 control-label">نوع البند</label>
                        <div class="col-sm-3">

                            <select name="class_type" data-val="false" data-val-required="حقل مطلوب" id="dp_class_type"
                                    class="form-control select2">
                                <option></option>
                                <?php foreach ($class_type_con as $row) : ?>
                                    <option value="<?= $row['CON_NO'] ?>">
                                        <?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <span class="field-validation-valid" data-valmsg-for="class_type"
                                  data-valmsg-replace="true"></span>


                    </div>
                        <label class="col-sm-1 control-label">الفعالية</label>

                        <div class="col-sm-3">
                            <select name="status" data-val="true" data-val-required="حقل مطلوب" id="dp_status"
                                    class="form-control select2">

                                <?php foreach ($status as $row) : ?>
                                    <option value="<?= $row['CON_NO'] ?>">
                                        <?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <span class="field-validation-valid" data-valmsg-for="status"
                                  data-valmsg-replace="true"></span>

                        </div>
                    </div>
                    <div class="form-group">








                        <label class="col-sm-1 control-label">التصنيف</label>
                        <div class="col-sm-3">

                            <select name="class_tree" data-val="false" data-val-required="حقل مطلوب" id="dp_class_tree"
                                    class="form-control select2">
                                <?php foreach ($class_tree_con as $row) : ?>
                                    <option value="<?= $row['CON_NO'] ?>">
                                        <?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <span class="field-validation-valid" data-valmsg-for="class_tree"
                                  data-valmsg-replace="true"></span>


                        </div>

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>

                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php


$scripts = <<<SCRIPT


<script>

    $(function () {

        $('#classModal').on('shown.bs.modal', function () {

            $('#txt_class_name_en').focus();
        })

        $('#class').tree();
    });

     function class_create(){

        clearForm($('#Civil_works_from'));

        if($(".tree span.selected").length <= 0 || $.fn.tree.level() >= 4){

            if(($.fn.tree.level() >= 4) ) {
            warning_msg('تحذير','غير مسموح بإدراج صنف جديد');

            }else{
                warning_msg('تحذير','غير مسموح بإدراج صنف جديد');
            }

           return;
        }

        var parentId =$.fn.tree.selected().attr('data-id');
        var parentName =$.fn.tree.selected().attr('data-name');
        $('#txt_class_parent_id').val(parentId);
        $('#txt_class_parent_id_name').val(parentName); 
        $('#txt_level').val($.fn.tree.level());        
        $('#Civil_works_from').attr('action','{$create_url}');
        $('#Civil_works_Modal').modal();

    }

    function class_delete(){

        var elem =$.fn.tree.selected();
        var id = elem.attr('data-id');
        var name = elem.text();

          if(confirm('هل تريد بالتأكيد حذف '+name)){
                    if (id==0)
                        {
                            danger_msg('تحذير','لا يمكن حذف الشجرة  !!');
                        }
                    else
                        {                  
        
                            ajax_delete('{$delete_url}', id ,function(data){
                
                                if(data == '1'){
                                    $.fn.tree.removeElem(elem);
                                    success_msg('رسالة','تم الحذف بنجاح ..');
                                }else {
                                    danger_msg('تحذير','لم يتم الحذف !!<br>'+data);
                                }
                            });
                        }
            }}


    function class_get(id){
        if(id!=0)
            {
                get_data('{$get_url}',{id:id},function(data){
                    $.each(data, function(i,item){
        
                          $('#txt_class_parent_id').val(item.CLASS_PARENT_ID);
                          $('#txt_class_id').val( item.CLASS_ID);
                          $('#txt_class_parent_id_name').val($.fn.tree.nodeText(item.CLASS_PARENT_ID));
                          $('#txt_class_name').val(item.CLASS_NAME_AR);
                          $('#txt_class_name_en').val(item.CLASS_NAME_EN);
                          $('#dp_class_unit').val(item.CLASS_UNIT);
                          $('#txt_class_quantity').val(item.CLASS_QUANTITY);
                          $('#txt_class_description').val(item.CLASS_DESCRIPTION);
                          $('#txt_class_price').val(item.CLASS_PRICE);
                          $('#dp_curr_id').val(item.CURR_ID);
                          $('#dp_status').val(item.CLASS_STATUS);
                          $('#dp_class_type').val(item.CLASS_TYPE);
                          $('#txt_level').val(item.TREE_LEVEL);
                          $('#dp_class_tree').val(item.CLASS_TREE); 
                          $('#Civil_works_from').attr('action','{$edit_url}');
                          $('#Civil_works_Modal').modal();
        
                    });
                });
            }
}

    $('#Civil_works_from button[data-action="submit"]').click(function(e){
        e.preventDefault();
        var form = $(this).closest('form');
        var isCreate = form.attr('action').indexOf('create') >= 0;
        ajax_insert_update(form,function(data){
          if(isCreate){
                var obj = jQuery.parseJSON(data);
                $.fn.tree.add(obj+' : '+$('#txt_class_name').val(),obj,"javascript:class_get('"+obj+"');");
            }else{
                if(!isNaN(data)){
                    var pname =$.fn.tree.selected().attr('data-name');
                    var pid =$.fn.tree.selected().attr('data-id');
                     $.fn.tree.selected().attr('data-name',pname);
                     $.fn.tree.selected().attr('data-id',pid);
                     $.fn.tree.update(data+' : '+$('#txt_class_name').val());
                }

            }
         $('#Civil_works_Modal').modal('hide');
            success_msg('رسالة','تم حفظ البيانات بنجاح ..');
        },"json");
    });

</script>
SCRIPT;

sec_scripts($scripts);


?>

