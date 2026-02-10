<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 20/03/18
 * Time: 10:09 ص
 */
$MODULE_NAME= 'indicator';
$TB_NAME= 'tree_sectors';
$create_url = base_url("$MODULE_NAME/$TB_NAME/create");
$get_url = base_url("$MODULE_NAME/$TB_NAME/get_id");
$edit_url = base_url("$MODULE_NAME/$TB_NAME/edit");
$delete_url = base_url("$MODULE_NAME/$TB_NAME/delete");

?>
<?= AntiForgeryToken() ?>
<div class="row">
    <div class="toolbar">

        <div class="caption"><?=$title;?></div>

        <ul>
 <?php if (HaveAccess($create_url)): ?>
                <li><a onclick="javascript:system_menu_create();" href="javascript:;"><i
                            class="glyphicon glyphicon-plus"></i>جديد </a></li> <?php endif; ?>
            <?php if (HaveAccess($get_url, $edit_url)): ?>
                <li><a onclick="javascript:system_menu_get($.fn.tree.selected().attr('data-id'));"
                       href="javascript:;"><i class="glyphicon glyphicon-edit"></i>تحرير</a></li> <?php endif; ?>
            <?php if (HaveAccess($delete_url)): ?>
                <li><a onclick="javascript:system_menu_delete();" href="javascript:;"><i
                            class="glyphicon glyphicon-remove"></i>حذف</a></li> <?php endif; ?>
            <li><a  onclick="$.fn.tree.expandAll()" href="javascript:;"><i class="glyphicon  glyphicon-sort-by-attributes-alt"></i>توسيع</a> </li>
            <li><a  onclick="$.fn.tree.collapseAll()" href="javascript:;"><i class="glyphicon  glyphicon-sort-by-attributes"></i>طي</a> </li>
             <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
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
<div class="modal fade" id="system_menusModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                        class="sr-only">Close</span></button>
                <h4 class="modal-title">بيانات القائمة</h4>
            </div>
            <form class="form-horizontal" id="system_menu_from" method="post"
                  action="<?= base_url('indicator/tree_sectors/create') ?>" role="form" novalidate="novalidate">
                <div class="modal-body">



                    <div class="form-group">
                       <label class="col-sm-1 control-label">الأب</label>
                        <div class="col-sm-1">
						<input type="hidden" name="menu_no" readonly id="txt_menu_no"
                                                     class="form-control ltr">
                            <input type="text" name="menu_parent_no" readonly id="txt_menu_parent_no"
                                   class="form-control ltr" "="">
                        </div>
                        <div class="col-sm-6">
                         
                            <input type="text" name="menu_parent_no_name" readonly id="txt_menu_parent_no_name"
                                   class="form-control" "="">
                        </div>

                    </div>


                    <div class="form-group">
                        <label class="col-sm-1 control-label">القائمة </label>

                        <div class="col-sm-9">
                            <input type="text" data-val="true" data-val-required="حقل مطلوب" name="menu_add"
                                   id="txt_menu_add" class="form-control">
                            <span class="field-validation-valid" data-valmsg-for="menu_add"
                                  data-valmsg-replace="true"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-1 control-label">الفعالية</label>

                        <div class="col-sm-9">
                            <select name="status" data-val="true"  data-val-required="حقل مطلوب"  id="dp_status" class="form-control select2">
                                <option></option>
                                <?php foreach($status as $row) :?>
                                    <option value="<?= $row['CON_NO'] ?>">
                                        <?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <span class="field-validation-valid" data-valmsg-for="status" data-valmsg-replace="true"></span>

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
	$('.select2').select2();
        $('#plan_tree').tree();
        $.each( $('#plan_tree .is_active') , function(i,item){

            if( parseInt($(this).attr('data-is-active')) == 0 ){
              $(this).html('<i class="icon  icon-share">غير مفعل</i>');
            $(this).css("color", "#B8AFAF");
            }


    });
        $('.is_active').on('click',  function (e) {

      if (confirm('هل تريد تفعيل هذا المشروع؟؟')) {


 $(this).html('<i class="icon  icon-share">مفعل</i>');
 $(this).css("color", "#24A1F5");

}






    });

});
    function system_menu_create(){


        clearForm($('#system_menu_from'));
        if($(".tree span.selected").length <= 0 || $.fn.tree.level() >= 4){

            if($.fn.tree.level() >= 4){

                warning_msg('تحذير ! ','غير مسموح بإدراج تصنيف جديد ..');
            }else{
                warning_msg('تحذير ! ','يجب إختيار  الأب ..');
            }


            return;
        }

        var parentId =$.fn.tree.selected().attr('data-id');
         var parentName = $('.tree li > span.selected').text();
         $('#txt_menu_parent_no').val(parentId);
        $('#txt_menu_parent_no_name').val(parentName);


        $('#system_menu_from').attr('action','{$create_url}');
        $('#system_menusModal').modal();



    }
 function system_menu_delete(){

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
    function system_menu_get(id){

        get_data('{$get_url}',{id:id},function(data){

            $.each(data, function(i,item){
                $('#txt_menu_parent_no').val(item.ID_FATHER);
                $('#txt_menu_no').val( item.ID);
                $('#txt_menu_parent_no_name').val($.fn.tree.nodeText(item.ID_FATHER));
                $('#txt_menu_add').val(item.ID_NAME);
               $("#dp_status").select2("val",item.STATUS);



                $('#system_menu_from').attr('action','{$edit_url}');

                $('#system_menusModal').modal();

            });
        });
    }

     $('#system_menu_from button[data-action="submit"]').click(function(e){
        e.preventDefault();


         var form = $(this).closest('form');
        var isCreate = form.attr('action').indexOf('create') >= 0;

        ajax_insert_update(form,function(data){
         console.log(data);
         if(isCreate){

                var obj = jQuery.parseJSON(data);

                  $.fn.tree.add(form.find('input[name="menu_add"]').val(),obj.id,"javascript:system_menu_get('"+obj.id+"');");
            }else{
                if(data == '1'){
                   $.fn.tree.update(form.find('input[name="menu_add"]').val());
                }

            }


            $('#system_menusModal').modal('hide');

            success_msg('رسالة','تم حفظ البيانات بنجاح ..');

        },"json");
    });


</script>
SCRIPT;

sec_scripts($scripts);

?>
