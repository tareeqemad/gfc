<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 08/01/15
 * Time: 02:09 م
 */

$MODULE_NAME= 'stores';
$TB_NAME= 'class_amount';

$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_actions_page");
$select_items_url=base_url("$MODULE_NAME/classes/public_index");

//$select_accounts_url =base_url('financial/accounts/public_select_accounts');
$select_accounts_url =base_url('financial/accounts/public_select_parent');
$customer_url =base_url('payment/customers/public_index');
$project_accounts_url =base_url('projects/projects/public_select_project_accounts');

$rep_url = base_url('greport/reports/public_class_amount_actions');

echo AntiForgeryToken();
?>

<script> var show_page=true; </script>

<style type="text/css">
    .bg_blue{background-color: #c4e3f3}
</style>

<div class="row">
    <div class="toolbar">
        <div class="caption"><?= $title ?></div>
    </div>

    <div class="form-body">
        <form class="form-vertical" id="<?=$TB_NAME?>_form" >
            <div class="modal-body inline_form">

                <div class="form-group col-sm-2">
                    <label class="control-label">المخزن </label>
                    <div>
                        <select name="store_id" multiple id="dp_store_id" class="form-control"  >
                         <!--   <option value="0">جميع المخازن</option> --->
                            <?php foreach($stores_all as $row) :?>
                                <option value="<?=$row['STORE_ID']?>"><?=$row['STORE_NO'].': '.$row['STORE_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2" style="display: none">
                    <label class="control-label">الى</label>
                    <div>
                        <select name="store_id2" id="dp_store_id2" class="form-control"  >
                            <option value="0">جميع المخازن</option>
                            <?php foreach($stores_all as $row) :?>
                                <option value="<?=$row['STORE_ID']?>"><?=$row['STORE_NO'].': '.$row['STORE_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">رقم الصنف</label>
                    <div>
                        <input class="form-control" type="text" name="class_id" value="<?=($class_id!=-1)?$class_id:''?>" id="h_txt_class_id" >
                    </div>
                </div>

                <div class="form-group col-sm-3">
                    <label class="control-label">الصنف</label>
                    <div>
                        <input readonly class="form-control"  id="txt_class_id" />
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label"> الحالة </label>
                    <div>
                        <select name="class_type" id="dp_class_type" class="form-control" />
                        <option value="">الكل</option>
                        <?php foreach($class_type_all as $row) :?>
                            <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">الحركة</label>
                    <div>
                        <select name="action" id="dp_action" class="form-control"  >
                            <option value="0">جميع الحركات</option>
                            <option value="1">وارد</option>
                            <option value="2">صادر</option>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">عرض الحجوزات</label>
                    <div>
                        <select name="reserve" id="dp_reserve" class="form-control"  >
                            <option selected >نعم</option>
                            <option value="2">نعم عدا الطلبات</option>
                            <option value="1">لا</option>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">المصدر</label>
                    <div>
                        <select name="source" id="dp_source" class="form-control" />
                        <option value="">__________</option>
                        <?php foreach($source_all as $row) :?>
                            <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">رقم المصدر</label>
                    <div>
                        <input type="text"  name="pk" id="txt_pk" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">الحركة من</label>
                    <div>
                        <input type="text" name="from_date" data-type="date" data-date-format="DD/MM/YYYY" data-val="true" id="txt_from_date" class="form-control" data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?=date_format_exp()?>"  />
                        <span class="field-validation-valid" data-valmsg-for="from_date" data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">الى</label>
                    <div>
                        <input type="text" name="to_date" data-type="date" data-date-format="DD/MM/YYYY" data-val="true" id="txt_to_date" class="form-control" data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?=date_format_exp()?>" />
                        <span class="field-validation-valid" data-valmsg-for="to_date" data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group col-sm-1 bg_blue">
                    <label class="control-label"> المصدر </label>
                    <div>
                        <select name="account_source" id="dp_account_source" class="form-control" />
                            <option value="1"> القيود </option>
                            <option value="2"> السندات </option>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-1 bg_blue">
                    <label class="control-label"> نوع الحساب </label>
                    <div>
                        <select name="account_type" id="dp_account_type" class="form-control" />
                        <option value="">__________</option>
                        <?php foreach($account_type_all as $row) :?>
                            <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2 bg_blue">
                    <label class="control-label">  الحساب </label>
                    <div>
                        <input type="text" readonly class="form-control" id="txt_account_id" />
                        <input type="hidden" name="account_id" id="h_txt_account_id" />
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">الترتيب حسب</label>
                    <div>
                        <select name="order" id="dp_order" class="form-control" />
                            <option value="1">التاريخ تنازلي</option>
                            <option value="2">التاريخ تصاعدي</option>
                            <option value="3">الصنف</option>
                            <option value="4">تاريخ السعر تصاعدي</option>
                        </select>
                    </div>
                </div>

            </div>
        </form>

        <div class="modal-footer">
            <button type="button" onclick="javascript:v_print_rep=0;search();" class="btn btn-success"> إستعلام</button>
            <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>
            <button id="btn_print" onclick="javascript:print_rep();" type="button" class="btn btn-success">  طباعة </button>
            <div class="btn-group">
                <button class="btn btn-warning btn-sm dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bars"></i> تصدير</button>
                <ul class="dropdown-menu " role="menu">
                    <li><a href="#" onclick="$('#page_tb').tableExport({type:'excel',escape:'false'});">  XLS</a></li>
                    <li><a href="#" onclick="$('#page_tb').tableExport({type:'doc',escape:'false'});">  Word</a></li>
                </ul>
            </div>
        </div>

        <div id="msg_container"></div>

        <div id="container">
            <?=modules::run($get_page_url, $page, $store_id, $store_id2, $class_id, $class_type, $action, $reserve, $source, $pk, $from_date, $to_date, $account_source, $account_type, $account_id, $order);?>
        </div>

    </div>

</div>

<?php
$scripts = <<<SCRIPT
<script>

    var v_print_rep=0;

    $('.pagination li').click(function(e){
        e.preventDefault();
    });

    $('#dp_store_id, #dp_store_id2, #dp_action, #dp_reserve, #dp_source, #dp_order, #dp_class_type, #dp_account_type, #dp_account_source').select2();

    $('#txt_class_id').click("focus",function(e){
        _showReport('$select_items_url/'+$(this).attr('id'));
    });

    $('#h_txt_class_id').change(function(){
        $('#txt_class_id').val('');
        if( isNaN( $('#h_txt_class_id').val() ) ) {
            $('#h_txt_class_id').val('');
        }
    });

    $('#dp_account_type').change(function(){
        $('#txt_account_id').val('');
        $('#h_txt_account_id').val('');
    });

    $('#txt_account_id').click(function(e){
        var _type = $('#dp_account_type').val();
        if(_type == 1)
            _showReport('$select_accounts_url/'+$(this).attr('id')+'/-1' );
        else if(_type == 2)
            _showReport('$customer_url/'+$(this).attr('id')+'/1');
        else if(_type == 3)
            _showReport('$project_accounts_url/'+$(this).attr('id')+'/1');
    });

    function search(){
        var values= {page:1, print_rep:v_print_rep , store_id:$('#dp_store_id').val(), store_id2:$('#dp_store_id2').val(), class_id:$('#h_txt_class_id').val(), class_type:$('#dp_class_type').val(), action:$('#dp_action').val(), reserve:$('#dp_reserve').val(), source:$('#dp_source').val(), pk:$('#txt_pk').val(), from_date:$('#txt_from_date').val(), to_date:$('#txt_to_date').val(), account_source:$('#dp_account_source').val(), account_type:$('#dp_account_type').val(), account_id:$('#h_txt_account_id').val(), order:$('#dp_order').val() };
        get_data('{$get_page_url}',values ,function(data){
            if(v_print_rep==0)
                $('#container').html(data);
            //$('#btn_print').prop('disabled',0);
        },'html');
    }

    function print_rep(){
        var msg= 'هل تريد بالتأكيد الطباعة ؟!';
        if(1 || confirm(msg)){
            v_print_rep= 1;
            search();
            setTimeout(function(){
                _showReport('$rep_url');
            }, 1100);
        }
    }

    function LoadingData(){
        var values= {store_id:$('#dp_store_id').val(), store_id2:$('#dp_store_id2').val(), class_id:$('#h_txt_class_id').val(), class_type:$('#dp_class_type').val(), action:$('#dp_action').val(), reserve:$('#dp_reserve').val(), source:$('#dp_source').val(), pk:$('#txt_pk').val(), from_date:$('#txt_from_date').val(), to_date:$('#txt_to_date').val(), account_source:$('#dp_account_source').val(), account_type:$('#dp_account_type').val(), account_id:$('#h_txt_account_id').val(), order:$('#dp_order').val() };
        ajax_pager_data('#page_tb > tbody',values);
    }

    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
        $('#dp_store_id, #dp_store_id2, #dp_action, #dp_reserve, #dp_source, #dp_order, #dp_class_type, #dp_account_type').select2('val','');
        $('#dp_account_source').select2('val','1');
    }

</script>
SCRIPT;
sec_scripts($scripts);
?>
