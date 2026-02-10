<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 8/13/14
 * Time: 12:22 PM
 */


$delete_url =base_url('financial/financial_center/delete');
$get_url =base_url('financial/financial_center/get_id');
$edit_url =base_url('financial/financial_center/edit');
$create_url =base_url('financial/financial_center/create');
$adapt_url =base_url('financial/financial_center/update_adapt');
$report_url = base_url("JsperReport/showreport?sys=financial");


?>
<?= AntiForgeryToken() ?>
<div class="row">
    <div class="toolbar">

        <div class="caption">شجرة المركز المالي</div>

        <ul>
            <?php if( HaveAccess($create_url)): ?> <li><a onclick="javascript:account_create();" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد </a> </li> <?php endif;?>
            <?php if( HaveAccess($get_url,$edit_url)): ?><li><a  onclick="javascript:account_get($.fn.tree.selected().attr('data-id'));" href="javascript:;"><i class="glyphicon glyphicon-edit"></i>تحرير</a> </li> <?php endif;?>
            <?php if( HaveAccess($adapt_url)): ?><li><a  onclick="javascript:account_adapt(1);" href="javascript:;"><i class="icon icon-check"></i>إعتماد الكل</a> </li> <?php endif;?>
            <?php if( HaveAccess($adapt_url)): ?><li><a  onclick="javascript:account_adapt(0);" href="javascript:;"><i ></i>إلغاء الإعتماد لكل </a> </li> <?php endif;?>
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


<div class="modal fade" id="financial_centerModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">بيانات المركز المالي</h4>
            </div>
            <form class="form-horizontal" id="account_from" method="post" action="<?=base_url('financial/financial_center/create')?>" role="form" novalidate="novalidate">
                <div class="modal-body">

                    <div class="form-group">
                        <label class="col-sm-3 control-label"> الأب</label>
                        <div class="col-sm-4">
                            <input type="text"  name="account_parent_id" readonly id="txt_account_parent_id" class="form-control ltr" "="">
                        </div>
                        <div class="col-sm-5">
                            <input type="text"  name="account_parent_id_name" readonly id="txt_account_parent_id_name" class="form-control" "="">
                        </div>

                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">الرقم </label>
                        <div class="col-sm-4">
                            <input type="text" name="account_id" readonly id="txt_account_id" class="form-control ltr">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">المركز المالي </label>
                        <div class="col-sm-5">
                            <input type="text" data-val="true"  data-val-required="حقل مطلوب" name="financia_postion" id="txt_financia_postion" class="form-control">
                            <span class="field-validation-valid" data-valmsg-for="financia_postion" data-valmsg-replace="true"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label"> التدفق النقدي </label>
                        <div class="col-sm-5">
                            <input type="text"  name="financial_cash_flow" id="txt_financial_cash_flow" class="form-control" >

                        </div>
                    </div>


                    <div class="form-group">
                        <label class="control-label col-sm-3"> المعامل</label>
                        <div  class="col-sm-5">
                            <select type="text"   name="financial_center_type" id="dp_financial_center_type" class="form-control" >
                                <option value="1">جمع</option>
                                <option value="-1">طرح</option>
                            </select>

                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-3"> تجميع عليه</label>
                        <div  class="col-sm-5">
                            <select type="text"   name="is_total" id="dp_is_total" class="form-control" >
                                <option value="1">لا</option>
                                <option value="2">نعم</option>
                            </select>

                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-3"> تكوين إجمالي بعده</label>
                        <div  class="col-sm-5">
                            <select type="text"   name="is_collection" id="dp_is_collection" class="form-control" >
                                <option value="1">لا</option>
                                <option value="2">نعم</option>
                            </select>

                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-3"> رقم الإيضاح</label>
                        <div  class="col-sm-5">
                            <input type="text"  name="the_clear" id="txt_the_clear" class="form-control" >


                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-3"> مستوي الإيضاح</label>
                        <div  class="col-sm-5">
                            <select type="text"   name="the_level" id="dp_the_level" class="form-control" >
                                <option value="0">0</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                            </select>
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

<div class="modal fade" id="reportFilterModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">محددات التقرير</h4>
            </div>
            <div class="form-horizontal">
                <div class="modal-body">

                    <div class="form-group" id="branch">
                        <label class="col-sm-3 control-label">المقــر</label>

                        <div class="col-sm-3">
                            <select name="report" class="form-control" id="dp_branch">
                                <option></option>
                                <?php foreach ($branches as $row) : ?>
                                    <option data-dept="<?= $row['NO'] ?>" value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group" id="date1" >
                        <label class="col-sm-3 control-label"> التاريخ </label>
                        <div class="col-sm-4">
                            <input type="text" data-type="date" data-date-format="DD/MM/YYYY" id="txt_date_1" class="form-control"/>
                        </div>
                    </div>

                    <div class="form-group" id="date2" >
                        <label class="col-sm-3 control-label"> حتى</label>
                        <div class="col-sm-4">
                            <input type="text" data-type="date" data-date-format="DD/MM/YYYY" id="txt_date_2" class="form-control"/>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" data-action="report" class="btn btn-primary">عرض التقرير </button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>

                    </div>
                </div>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php


$scripts = <<<SCRIPT


<script>
    $(function () {

        $('#financial_centerModal').on('shown.bs.modal', function () {
            $('#txt_financia_postion').focus();
        })

        $('#financial_center').tree();
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

        $('#txt_account_parent_id').val(parentId);
        $('#txt_account_id').val(account_withRoot_id($.fn.tree.level(),productionId,parentId));
        $('#txt_account_parent_id_name').val(parentName);




        $('button[name="adapt"]').val(0);

        $('#account_from').attr('action','{$create_url}');
        $('#financial_centerModal').modal();

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
                $('#txt_account_parent_id').val(item.ACCOUNT_PARENT_ID);
                $('#txt_account_id').val( item.ACCOUNT_ID);
                $('#txt_account_parent_id_name').val($.fn.tree.nodeText(item.ACCOUNT_PARENT_ID));
                $('#txt_financia_postion').val(item.FINANCIA_POSTION);


                $('#txt_financial_cash_flow').val(item.FINANCIAL_CASH_FLOW);

                $('#dp_financial_center_type').val(item.FINANCIAL_CENTER_TYPE);
                $('#dp_is_total').val(item.IS_TOTAL);
                $('#dp_is_collection').val(item.IS_COLLECTION);

                $('#txt_the_clear').val(item.THE_CLEAR);

                $('#dp_the_level').val(item.THE_LEVEL);

                $('#account_from').attr('action','{$edit_url}');

                $('#financial_centerModal').modal();

            });
        });
    }

    $('button#btn_adapt').click(function(){

        var adapt_val = $(this).val();

        account_update_adapt($('#txt_account_id').val(),adapt_val);
    });

    function account_update_adapt(id,adapt_val,all){
        get_data('{$adapt_url}',{account_id:id ,adapt:adapt_val},function(data){


            if(parseInt(data) == 1){
                $('#financial_centerModal').modal('hide');
                if(adapt_val == 1){
                    success_msg('رسالة','تم إعتماد الحساب بنجاح ..');
                    if(all)
                        $('span.adapt_0').removeClass('adapt_0');
                    else
                        $.fn.tree.selected().removeClass('adapt_0');
                }
                else {success_msg('رسالة','تم إلغاء الإعتماد الحساب بنجاح ..');
                    if(all)
                        $(' li > span[data-id]',$('#financial_center')).addClass('adapt_0');
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

                $.fn.tree.add(obj.id+':'+(form.find('input[name="financia_postion"]').val()),obj.id,"javascript:account_get('"+obj.id+"');");


            }else{ if(data == '1'){
                $.fn.tree.update(form.find('input[name="financia_postion"]').val());
            }}


            $('#financial_centerModal').modal('hide');

            success_msg('رسالة','تم حفظ البيانات بنجاح ..');
        },"json");

    });
    
    var acc= 0;
    
    $('.btn_print_acc').click(function(){
        acc =$(this).attr('data-id');
    });
    
    function getReportUrl(){
        var branch=$('#dp_branch').val();
        var from_date = $('#txt_date_1').val();
        var to_date = $('#txt_date_2').val();
        
        var repUrl;
        repUrl = '{$report_url}/financial&report_type=pdf&report=financial_center_accounts_balance&p_date_from='+from_date+'&p_date_to='+to_date+'&p_branch='+branch+'&p_financial_center_id='+acc+'';
        
        return repUrl;
    }

    $('button[data-action="report"]').click(function(){
        var rep_url = getReportUrl();
        _showReport(rep_url);
    });


</script>
SCRIPT;

sec_scripts($scripts);



?>

