<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 9/1/14
 * Time: 1:25 PM
 */

$delete_url = base_url('settings/sysmenus/delete');
$get_url = base_url('settings/sysmenus/get_id');
$edit_url = base_url('settings/sysmenus/edit');
$create_url = base_url('settings/sysmenus/create');
$sort_url = base_url('settings/sysmenus/sort');

?>
<?= AntiForgeryToken() ?>
<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?> </div>

        <ul>
            <?php if (HaveAccess($create_url)): ?>
                <li><a onclick="javascript:system_menu_create();" href="javascript:;"><i
                            class="glyphicon glyphicon-plus"></i>جديد </a></li> <?php endif; ?>
            <?php if (HaveAccess($get_url, $edit_url)): ?>
                <li><a onclick="javascript:system_menu_get($.fn.tree.selected().attr('data-id'));"
                       href="javascript:;"><i class="glyphicon glyphicon-edit"></i>تحرير</a></li> <?php endif; ?>
            <?php if (HaveAccess($sort_url)): ?>
                <li><a onclick="javascript:system_menu_save_order();" href="javascript:;"><i class="icon icon-save"></i>حفظ
                        الترتيب</a></li> <?php endif; ?>
            <?php if (HaveAccess($delete_url)): ?>
                <li><a onclick="javascript:system_menu_delete();" href="javascript:;"><i
                            class="glyphicon glyphicon-remove"></i>حذف</a></li> <?php endif; ?>
            <li><a onclick="$.fn.tree.expandAll()" href="javascript:;"><i
                        class="glyphicon  glyphicon-sort-by-attributes-alt"></i>توسيع</a></li>
            <li><a onclick="$.fn.tree.collapseAll()" href="javascript:;"><i
                        class="glyphicon  glyphicon-sort-by-attributes"></i>طي</a></li>
            <!--     <li><a href="#">بحث</a> </li>-->
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
                  action="<?= base_url('settings/system_menus/create') ?>" role="form" novalidate="novalidate">
                <div class="modal-body">

                    <div class="form-group">
                        <label class="col-sm-3 control-label"> النظام </label>

                        <div class="col-sm-4">
                            <select type="text" name="id_system" id="dp_id_system" class="form-control">
                                <option></option>
                                <?php foreach ($systems as $row) : ?>
                                    <option value="<?= $row['ID'] ?>"><?= $row['NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>

                        </div>


                    </div>


                    <div class="form-group">
                        <label class="col-sm-3 control-label">الفرع الأب </label>

                        <div class="col-sm-4"><input type="hidden" name="menu_no" readonly id="txt_menu_no"
                                                     class="form-control ltr">
                            <input type="text" name="menu_parent_no" readonly id="txt_menu_parent_no"
                                   class="form-control ltr" "="">
                        </div>
                        <div class="col-sm-5">
                            <input type="hidden" name="menu_full_code_in" id="txt_menu_full_code_in"
                                   class="form-control" "="">
                            <input type="text" name="menu_parent_no_name" readonly id="txt_menu_parent_no_name"
                                   class="form-control" "="">
                        </div>

                    </div>


                    <div class="form-group">
                        <label class="col-sm-3 control-label">القائمة </label>

                        <div class="col-sm-9">
                            <input type="text" data-val="true" data-val-required="حقل مطلوب" name="menu_add"
                                   id="txt_menu_add" class="form-control">
                            <span class="field-validation-valid" data-valmsg-for="menu_add"
                                  data-valmsg-replace="true"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">كود الصفحة </label>

                        <div class="col-sm-9">
                            <input type="text" data-val="true" data-val-required="حقل مطلوب" name="menu_code"
                                   id="txt_menu_code" class="form-control ltr">
                            <span class="field-validation-valid" data-valmsg-for="menu_code"
                                  data-valmsg-replace="true"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">العناصر المرتبطة </label>

                        <div class="col-sm-9">
                            <input type="text" name="related_object" id="txt_related_object" class="form-control ltr">

                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">قائمة رئيسية </label>

                        <div class="col-sm-9">
                            <div class="radio">
                                <label> <input type="radio" name="main_menu" id="rd_main_menu_1" value="1">
                                    نعم
                                </label>

                                <label>
                                    <input type="radio" name="main_menu" id="rd_main_menu_2" value="2">
                                    لا
                                </label>


                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">عرض كقائمة </label>

                        <div class="col-sm-9">
                            <div class="radio">
                                <label> <input type="radio" name="view_menu" id="rd_view_menu_1" value="1">
                                    نعم
                                </label>

                                <label>
                                    <input type="radio" name="view_menu" id="rd_view_menu_2" value="2">
                                    لا
                                </label>


                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label"> الأيقونة </label>

                        <div class="col-sm-4">
                            <input type="text" name="icon" id="txt_icon" class="form-control ltr">

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

        $('#system_menusModal').on('shown.bs.modal', function () {
            $('#txt_menu_add').focus();
        })

        $('#system_menus').tree();

        var ns = $('#system_menus').nestedSortable({
                disableParentChange:true,
				forcePlaceholderSize: true,
				listType:'ul',
				handle: 'span',
				helper:	'clone',
				items: 'li',
				opacity: .6,
				placeholder: 'placeholder',
				revert: 250,
				tabSize: 25,
				tolerance: 'pointer',
				//toleranceElement: '> div',
				maxLevels: 4,
				isTree: true,
				expandOnHover: 700,
				startCollapsed: false,
				change: function(){
					console.log('Relocated item');
				}
			});

    });



    function system_menu_create(){

        clearForm($('#system_menu_from'));
        if($(".tree span.selected").length <= 0 || $.fn.tree.level() >= 6){

            if($.fn.tree.level() >= 6){

                warning_msg('تحذير ! ','غير مسموح بإدراج حساب جديد ..');
            }else{
                warning_msg('تحذير ! ','يجب إختيار الحساب الأب ..');
            }


            return;
        }

        var parentId =$.fn.tree.selected().attr('data-id');
        var productionId= $.fn.tree.lastElem().attr('data-id');
        var parentName = $('.tree li > span.selected').text();

        $('#txt_menu_parent_no').val(parentId);
        $('#txt_menu_no').val(account_id($.fn.tree.level(),productionId,parentId));
        $('#txt_menu_parent_no_name').val(parentName);

        if(parentId.length >= 6)
            $('#rd_main_menu_2').prop('checked',true);
        else
            $('#rd_main_menu_1').prop('checked',true);

        $('#system_menu_from').attr('action','{$create_url}');
        $('#system_menusModal').modal();

    }

    function system_menu_delete(){
	
		warning_msg('تنويه', 'الخاصية معطلة على الرسمي');
		return false;

        if(confirm('هل تريد حذف الحساب ؟!!!')){
            var elem =$.fn.tree.selected();
            var id = elem.attr('data-id');
            var url = '{$delete_url}';

            ajax_delete(url,id,function(data){
                if(data == '1'){

                    $.fn.tree.removeElem(elem);

                }
            });
        }

    }

    function system_menu_get(id){
	
        get_data('{$get_url}',{id:id},function(data){

            $.each(data, function(i,item){
                $('#txt_menu_parent_no').val(item.MENU_PARENT_NO);
                $('#txt_menu_no').val( item.MENU_NO);
                $('#txt_menu_parent_no_name').val($.fn.tree.nodeText(item.MENU_PARENT_NO));
                $('#txt_menu_add').val(item.MENU_ADD);
                $('#txt_menu_code').val(item.MENU_CODE);
                $('#txt_related_object').val(item.RELATED_OBJECT);
                       $('#txt_icon').val(item.ICON);
                $('#dp_id_system').val(item.ID_SYSTEM);
           

                if(item.MAIN_MENU == 2)
                    $('#rd_main_menu_2').prop('checked',true);
                else
                    $('#rd_main_menu_1').prop('checked',true);

                      if(item.VIEW_MENU == 2)
                    $('#rd_view_menu_2').prop('checked',true);
                else
                    $('#rd_view_menu_1').prop('checked',true);

                $('#system_menu_from').attr('action','{$edit_url}');

                $('#system_menusModal').modal();

            });
        });
    }


function system_menu_save_order(){

	warning_msg('تنويه', 'الخاصية معطلة على الرسمي');
	return false;

 get_data('{$sort_url}',{'id[]':$('#system_menus').nestedSortable('serialize',{attribute: 'data-id',key:'id'}),__AntiForgeryToken:$('input[name="__AntiForgeryToken"]').val()},function(data){

});

}
      $('button[data-action="submit"]').click(function(e){
	  
		warning_msg('تنويه', 'الخاصية معطلة على الرسمي');
		return false;

        e.preventDefault();


        var form = $(this).closest('form');

        var isCreate = form.attr('action').indexOf('create') >= 0;

        ajax_insert_update(form,function(data){


            if(isCreate){

                var obj = jQuery.parseJSON(data);

                $.fn.tree.add(form.find('input[name="menu_add"]').val(),obj.id,"javascript:system_menu_get('"+obj.id+"');");


            }else{ if(data == '1'){
                $.fn.tree.update(form.find('input[name="menu_add"]').val());
            }}


            $('#system_menusModal').modal('hide');
success_msg('رسالة','تم حفظ البيانات بنجاح ..');

        },"json");

    });

</script>
SCRIPT;

sec_scripts($scripts);



?>
