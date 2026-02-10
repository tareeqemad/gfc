<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 8/13/14
 * Time: 12:22 PM
 */


$get_url =base_url('financial/BankFinCenter/get_id');

?>
<?= AntiForgeryToken() ?>
<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
            <?php if( HaveAccess($create_url)): ?> <li><a onclick="javascript:account_create();" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد </a> </li> <?php endif;?>
            <?php if( HaveAccess($get_url,$edit_url)): ?><li><a  onclick="javascript:account_get($.fn.tree.selected().attr('data-id'));" href="javascript:;"><i class="glyphicon glyphicon-edit"></i>تحرير</a> </li> <?php endif;?>
            <?php if( HaveAccess($delete_url)): ?><li><a  onclick="javascript:account_delete();" href="javascript:;"><i class="glyphicon glyphicon-remove"></i>حذف</a> </li> <?php endif;?>
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


<div class="modal fade" id="bank_fin_centerModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">بيانات  الحساب</h4>
            </div>
            <form class="form-horizontal" id="account_from" method="post" action="<?=base_url('financial/bank_fin_center/create')?>" role="form" novalidate="novalidate">
                <div class="modal-body">

                    <div class="form-group">
                        <label class="col-sm-3 control-label"> الأب</label>
                        <div class="col-sm-4">
                            <input type="text"  name="parent_id" readonly id="txt_parent_id" class="form-control ltr" "="">
                        </div>
                        <div class="col-sm-5">
                            <input type="text"  name="parent_id_name" readonly id="txt_parent_id_name" class="form-control" "="">
                        </div>

                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">الرقم </label>
                        <div class="col-sm-4">
                            <input type="text" name="id" readonly id="txt_id" class="form-control ltr">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">الحساب</label>
                        <div class="col-sm-5">
                            <input type="text" data-val="true"  data-val-required="حقل مطلوب" name="title" id="txt_title" class="form-control">
                            <span class="field-validation-valid" data-valmsg-for="title" data-valmsg-replace="true"></span>
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

        $('#bank_fin_centerModal').on('shown.bs.modal', function () {
            $('#txt_title').focus();
        })

        $('#bank_fin_center').tree();
    });



    function account_create(){


        clearForm($('#account_from'));
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

        $('#txt_parent_id').val(parentId);
        $('#txt_id').val(account_withRoot_id($.fn.tree.level(),productionId,parentId));
        $('#txt_parent_id_name').val(parentName);




        $('button[name="adapt"]').val(0);

        $('#account_from').attr('action','{$create_url}');
        $('#bank_fin_centerModal').modal();

    }

    function account_delete(){

        if(confirm('هل تريد حذف الحساب ؟!!!')){
            var elem =$.fn.tree.selected();
            var id = elem.attr('data-id');
            var url = '{$delete_url}';

            ajax_delete(url,id,function(data){
                if(data == '1'){

                    $.fn.tree.removeElem(elem);
                    success_msg('رسالة','تم حذف السجلات بنجاح ..');
                }else {
      danger_msg('تحذير','فشل في حفظ الحساب , قد يكون عليه حركات ..');
                }
            });
        }

    }

    function account_get(id){

        get_data('{$get_url}',{id:id},function(data){

            $.each(data, function(i,item){
                $('#txt_parent_id').val(item.PARENT_ID);
                $('#txt_id').val( item.ID);
                $('#txt_parent_id_name').val($.fn.tree.nodeText(item.PARENT_ID));
                $('#txt_title').val(item.TITLE);
 

                $('#account_from').attr('action','{$edit_url}');

                $('#bank_fin_centerModal').modal();

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

                $.fn.tree.add(obj.id+':'+(form.find('input[name="title"]').val()),obj.id,"javascript:account_get('"+obj.id+"');");


            }else{ if(data == '1'){
                $.fn.tree.update(form.find('input[name="title"]').val());
            }}


            $('#bank_fin_centerModal').modal('hide');

            success_msg('رسالة','تم حفظ البيانات بنجاح ..');
        },"json");

    });

</script>
SCRIPT;

sec_scripts($scripts);



?>

