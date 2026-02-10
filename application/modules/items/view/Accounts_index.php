<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 8/13/14
 * Time: 12:22 PM
 */


$delete_url =base_url('items/accounts/delete');
$get_url =base_url('items/accounts/get_id');
$edit_url =base_url('items/accounts/edit');
$create_url =base_url('items/accounts/create');
$adapt_url =base_url('items/accounts/update_adapt');


?>
<?= AntiForgeryToken() ?>
<div class="row">
    <div class="toolbar">

        <div class="caption">شجرة السحابات</div>

        <ul>
            <?php if( HaveAccess($create_url)): ?> <li><a onclick="javascript:account_create();" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد </a> </li> <?php endif;?>
            <?php if( HaveAccess($get_url,$edit_url)): ?><li><a  onclick="javascript:account_get($.fn.tree.selected().attr('data-id'));" href="javascript:;"><i class="glyphicon glyphicon-edit"></i>تحرير</a> </li> <?php endif;?>
            <?php if( HaveAccess($adapt_url)): ?><li><a  onclick="javascript:account_adapt(0);" href="javascript:;"><i class="icon icon-check"></i>إعتماد الكل</a> </li> <?php endif;?>
            <?php if( HaveAccess($adapt_url)): ?><li><a  onclick="javascript:account_adapt(1);" href="javascript:;"><i ></i>إلغاء الإعتماد لكل </a> </li> <?php endif;?>
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


<div class="modal fade" id="accountsModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">بيانات الحساب</h4>
            </div>
            <form class="form-horizontal" id="account_from" method="post" action="<?=base_url('financial/accounts/create')?>" role="form" novalidate="novalidate">
                <div class="modal-body">

                    <div class="form-group">
                        <label class="col-sm-3 control-label">حساب الأب</label>
                        <div class="col-sm-4">
                            <input type="text"  name="acount_parent_id" readonly id="txt_acount_parent_id" class="form-control ltr" "="">
                        </div>
                        <div class="col-sm-5">
                            <input type="text"  name="acount_parent_id_name" readonly id="txt_acount_parent_id_name" class="form-control" "="">
                        </div>

                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">رقم الحساب</label>
                        <div class="col-sm-4">
                            <input type="text" name="acount_id" readonly id="txt_acount_id" class="form-control ltr">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">اسم الحساب</label>
                        <div class="col-sm-9">
                            <input type="text" data-val="true"  data-val-required="حقل مطلوب" name="acount_name" id="txt_acount_name" class="form-control">
                            <span class="field-validation-valid" data-valmsg-for="acount_name" data-valmsg-replace="true"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">نوع الحساب </label>
                        <div class="col-sm-9">
                            <div class="radio">
                                <label> <input type="radio" name="acount_type" id="rd_acount_type_1" value="1">
                                    رئيسي
                                </label>

                                <label>
                                    <input type="radio" name="acount_type" id="rd_acount_type_2" value="2">
                                    فرعي
                                </label>


                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">العملة</label>
                        <div class="col-sm-4">
                            <select name="curr_id" id="dp_curr_id" class="form-control">
                                <option></option>
                                <?php foreach($currency as $row) :?>
                                    <option value="<?= $row['CURR_ID'] ?>"><?= $row['CURR_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">تبعية الحساب</label>
                        <div class="col-sm-4">
                            <select name="acount_follow" id="db_acount_follow" class="form-control">
                                <option></option>
                                <?php foreach($follow as $row) :?>
                                    <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">التدفق النقدي</label>
                        <div class="col-sm-4">
                            <select name="acount_cash_flow" id="dp_acount_cash_flow" class="form-control">
                                <option></option>
                                <?php foreach($cash_flow as $row) :?>
                                    <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label"> رقم الموازنة</label>
                        <div class="col-sm-9">
                            <input type="text"  name="budget_account" id="txt_budget_account" class="form-control easyui-combotree" data-options="url:'<?= base_url('budget/budget/public_get_budget_json')?>',method:'get',animate:true,lines:true,required:false">

                        </div>
                    </div>



                </div>
                <div class="modal-footer">
                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>

                    <?php if( HaveAccess($adapt_url)): ?><button type="button" id="btn_adapt"  name="adapt" class="btn btn-success">إعتماد</button><?php endif; ?>

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

        $('#accountsModal').on('shown.bs.modal', function () {
            $('#txt_acount_name').focus();
        })

        $('#accounts').tree();
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

        $('#txt_acount_parent_id').val(parentId);
        $('#txt_acount_id').val(account_id($.fn.tree.level(),productionId,parentId));
        $('#txt_acount_parent_id_name').val(parentName);
        $('button[name="adapt"]').val(0);

        if(parentId.length >= 6)
            $('#rd_acount_type_2').prop('checked',true);
        else
            $('#rd_acount_type_1').prop('checked',true);

        $('#account_from').attr('action','{$create_url}');
        $('#accountsModal').modal();

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
                }
            });
        }

    }

    function account_get(id){

        get_data('{$get_url}',{id:id},function(data){

            $.each(data, function(i,item){
                $('#txt_acount_parent_id').val(item.ACOUNT_PARENT_ID);
                $('#txt_acount_id').val( item.ACOUNT_ID);
                $('#txt_acount_parent_id_name').val($.fn.tree.nodeText(item.ACOUNT_PARENT_ID));
                $('#txt_acount_name').val(item.ACOUNT_NAME);
                $('#dp_curr_id').val(item.CURR_ID);
                $('#db_acount_follow').val(item.ACOUNT_FOLLOW);
                $('#dp_acount_cash_flow').val(item.ACOUNT_CASH_FLOW);
                      $('#txt_budget_account').val(item.BUDGET_ACCOUNT);


                            $('#txt_budget_account').combotree('setValue', item.BUDGET_ACCOUNT);
                         $('#txt_budget_account').combotree('setText', $('.tree-title[data-id="'+item.BUDGET_ACCOUNT+'"]').text());


                $('button[name="adapt"]').val(item.ADOPT);
                if(item.ADOPT == 0){
                    $('button[name="adapt"]').removeClass('btn-danger');
                    $('button[name="adapt"]').addClass('btn-success');
                    $('button[name="adapt"]').text('إعتماد');
                }else{
                    $('button[name="adapt"]').removeClass('btn-success');
                    $('button[name="adapt"]').addClass('btn-danger');
                    $('button[name="adapt"]').text('إلغاء الإعتماد');
                }


                if(item.ACOUNT_TYPE == 2)
                    $('#rd_acount_type_2').prop('checked',true);
                else
                    $('#rd_acount_type_1').prop('checked',true);

                $('#account_from').attr('action','{$edit_url}');

                $('#accountsModal').modal();

            });
        });
    }

    $('button#btn_adapt').click(function(){

        var adapt_val = $(this).val();

        account_update_adapt($('#txt_acount_id').val(),adapt_val);
    });

    function account_update_adapt(id,adapt_val,all){
        get_data('{$adapt_url}',{acount_id:id ,adapt:adapt_val},function(data){


            if(parseInt(data) == 1){
                $('#accountsModal').modal('hide');
                if(adapt_val == 0){
                    success_msg('رسالة','تم إعتماد الحساب بنجاح ..');
                    if(all)
                        $('span.adapt_0').removeClass('adapt_0');
                    else
                        $.fn.tree.selected().removeClass('adapt_0');
                }
                else {success_msg('رسالة','تم إلغاء الإعتماد الحساب بنجاح ..');
                    if(all)
                        $(' li > span[data-id]',$('#accounts')).addClass('adapt_0');
                    else
                        $.fn.tree.selected().addClass('adapt_0');
                }
            }else{
                danger_msg('رسالة','فشل في تحديث إعتماد الحساب ..');
            }

        });

    }
    function account_adapt(adapt_val){

var message = adapt_val == 0 ?'هل تريد إعتماد الكل ؟!!!':'هل تريد إلغاء الإعتماد للكل ';
         if(confirm(message)){
        account_update_adapt(-1,adapt_val,true);

}
    }

    $('button[data-action="submit"]').click(function(e){

        e.preventDefault();


        var form = $(this).closest('form');

        var isCreate = form.attr('action').indexOf('create') >= 0;

        ajax_insert_update(form,function(data){


            if(isCreate){

                var obj = jQuery.parseJSON(data);

                $.fn.tree.add(form.find('input[name="acount_name"]').val(),obj.id,"javascript:account_get('"+obj.id+"');");


            }else{ if(data == '1'){
                $.fn.tree.update(form.find('input[name="acount_name"]').val());
            }}


            $('#accountsModal').modal('hide');

            success_msg('رسالة','تم حفظ البيانات بنجاح ..');
        },"json");

    });

</script>
SCRIPT;

sec_scripts($scripts);



?>

