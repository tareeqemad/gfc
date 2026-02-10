<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 8/13/14
 * Time: 12:22 PM
 */


$delete_url =base_url('settings/gcc_structure/delete');
$get_url =base_url('settings/gcc_structure/get_id');
$edit_url =base_url('settings/gcc_structure/edit');
$create_url =base_url('settings/gcc_structure/create');

?>
<?= AntiForgeryToken() ?>
<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?> </div>

        <ul>
            <?php if( HaveAccess($create_url)): ?><li><a onclick="javascript:gcc_structure_create();" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد </a> </li> <?php endif;?>
            <?php if( HaveAccess($get_url,$edit_url)): ?><li><a  onclick="javascript:gcc_structure_get($.fn.tree.selected().attr('data-id'));" href="javascript:;"><i class="glyphicon glyphicon-edit"></i>تحرير</a> </li> <?php endif;?>
            <?php if( HaveAccess($delete_url)): ?><li><a  onclick="javascript:gcc_structure_delete();" href="javascript:;"><i class="glyphicon glyphicon-remove"></i>إلغاء</a> </li> <?php endif;?>
            <li><a  onclick="$.fn.tree.expandAll()" href="javascript:;"><i class="glyphicon  glyphicon-sort-by-attributes-alt"></i>توسيع</a> </li>
            <li><a  onclick="$.fn.tree.collapseAll()" href="javascript:;"><i class="glyphicon  glyphicon-sort-by-attributes"></i>طي</a> </li>
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


<div class="modal fade" id="gcc_structureModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"> بيانات مركز المسئولية</h4>
            </div>
            <form class="form-horizontal" id="gcc_structure_from" method="post" action="<?=base_url('settings/gcc_structure/create')?>" role="form" novalidate="novalidate">
                <div class="modal-body">

                    <div class="form-group">
                        <label class="col-sm-3 control-label">المركز الأب </label>
                        <div class="col-sm-4">
                            <input type="text"  name="st_parent_id" readonly id="txt_st_parent_id" class="form-control ltr" "="">
                        </div>
                        <div class="col-sm-5">
                            <input type="text"  name="st_parent_id_name" readonly id="txt_st_parent_id_name" class="form-control" "="">
                        </div>

                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">رقم المركز </label>
                        <div class="col-sm-4">
                            <input type="text" name="st_id" readonly id="txt_st_id" class="form-control ltr">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label"> اسم المركز</label>
                        <div class="col-sm-9">
                            <input type="text" data-val="true"  data-val-required="حقل مطلوب" name="st_name" id="txt_st_name" class="form-control">
                            <span class="field-validation-valid" data-valmsg-for="st_name" data-valmsg-replace="true"></span>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-sm-3 control-label"> نوع المركز</label>
                        <div class="col-sm-4">
                            <select name="type" id="dp_type" class="form-control">
                                <option></option>
                                <?php foreach($st_types as $row) :?>
                                    <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>

                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php


$scripts = <<<SCRIPT
<script>
    $(function () {

        $('#gcc_structureModal').on('shown.bs.modal', function () {
            $('#txt_st_name').focus();
        })

        $('#gcc_structure').tree();
    });



    function gcc_structure_create(){


        clearForm($('#gcc_structure_from'));


        var parentId =$.fn.tree.selected().attr('data-id');
        var productionId= $.fn.tree.lastElem().attr('data-id');
        var parentName = $('.tree li > span.selected').text();

        $('#txt_st_parent_id').val(parentId);
        $('#txt_st_id').val(structure_id(productionId,parentId));
        $('#txt_st_parent_id_name').val(parentName);



        $('#gcc_structure_from').attr('action','{$create_url}');
        $('#gcc_structureModal').modal();

    }

    function gcc_structure_delete(){

        if(confirm('هل تريد حذف الحساب ؟!!!')){
            var elem =$.fn.tree.selected();
            var id = elem.attr('data-id');
            var url = '{$delete_url}';

            ajax_delete(url,id,function(data){
                if(data == '1'){

                    $.fn.tree.removeElem(elem);
success_msg('رسالة','تم حذف السجل بنجاح ..');
                }else        danger_msg( 'تحذير','فشل في العملية');
            });
        }

    }

    function gcc_structure_get(id){

        get_data('{$get_url}',{id:id},function(data){

            $.each(data, function(i,item){
                $('#txt_st_parent_id').val(item.ST_PARENT_ID);
                $('#txt_st_id').val( item.ST_ID);
                $('#txt_st_parent_id_name').val($.fn.tree.nodeText(item.ST_PARENT_ID));
                $('#txt_st_name').val(item.ST_NAME);
                  $('#dp_type').val(item.TYPE);

                $('#gcc_structure_from').attr('action','{$edit_url}');

                $('#gcc_structureModal').modal();

            });
        });
    }


    $('button[data-action="submit"]').click(function(e){

        e.preventDefault();


        var form = $(this).closest('form');

        var isCreate = form.attr('action').indexOf('create') >= 0;

        ajax_insert_update(form,function(data){


            if(isCreate){

                var obj = jQuery.parseJSON(data);

                $.fn.tree.add(form.find('input[name="st_name"]').val(),obj.id,"javascript:gcc_structure_get('"+obj.id+"');");


            }else{ if(data == '1'){
                $.fn.tree.update(form.find('input[name="st_name"]').val());
            }}


            $('#gcc_structureModal').modal('hide');
success_msg('رسالة','تم حفظ البيانات بنجاح ..');

        },"json");

    });

</script>
SCRIPT;

sec_scripts($scripts);



?>
