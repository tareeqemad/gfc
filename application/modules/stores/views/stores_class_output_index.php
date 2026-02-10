<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 29/12/14
 * Time: 09:29 ص
 */

$MODULE_NAME= 'stores';
$TB_NAME= 'stores_class_output';

$get_url =base_url("$MODULE_NAME/$TB_NAME/get");
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$edit_url =base_url("$MODULE_NAME/$TB_NAME/edit");
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");

$select_accounts_url =base_url('financial/accounts/public_select_accounts');
$customer_url =base_url('payment/customers/public_index');

$report_url = base_url("JsperReport/showreport?sys=financial/purchases");
$report_sn= report_sn();


if(HaveAccess($edit_url))
    $edit= 'edit';
else
    $edit= '';

echo AntiForgeryToken();
?>

<script> var show_page=true; </script>

<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
        </ul>

    </div>

    <div class="form-body">
        <form class="form-vertical" id="<?=$TB_NAME?>_form" >
            <div class="modal-body inline_form">
                
                <div class="form-group col-sm-1">
                    <label class="control-label">رقم سند الصرف</label>
                    <div>
                        <input type="text"  name="class_output_id" id="txt_class_output_id" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">المصدر </label>
                    <div>
                        <select name="source" id="dp_source" class="form-control" />
                            <option></option>
                            <option value="1">طلب صرف</option>
                            <option value="2">سند مناقلة</option>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">رقم المصدر</label>
                    <div>
                        <input type="text"  name="request_no" id="txt_request_no" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">المخزن المصروف منه</label>
                    <div>
                        <select name="from_store_id" id="dp_from_store_id" class="form-control" />
                        <option></option>
                        <?php foreach($stores_all as $row) :?>
                            <option value="<?=$row['STORE_ID']?>"><?=$row['STORE_NO'].': '.$row['STORE_NAME']?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label"> نوع سند الصرف</label>
                    <div>
                        <select name="class_output_type" id="dp_class_output_type" class="form-control" />
                        <option></option>
                        <?php foreach($request_side_all as $row) :?>
                            <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">الحساب المصروف إليه</label>
                    <div>
                        <input type="text"  readonly  class="form-control" id="txt_class_output_account_id" />
                        <input type="hidden" name="class_output_account_id" id="h_txt_class_output_account_id" />
                    </div>
                </div>

                <div class="form-group col-sm-3">
                    <label class="control-label">البيان</label>
                    <div>
                        <input type="text"  name="note" id="txt_note" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">حالة الطلب</label>
                    <div>
                        <select name="adopt" id="dp_adopt" class="form-control" />
                        <option></option>
                        <?php foreach($adopt_all as $row) :?>
                            <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">المدخل</label>
                    <div>
                        <select name="entry_user" id="dp_entry_user" class="form-control" />
                        <option></option>
                        <?php foreach($entry_user_all as $row) :?>
                            <option value="<?=$row['ID']?>"><?=$row['USER_NAME']?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
                </div>

            </div>
        </form>

        <div class="modal-footer">
            <button type="button" onclick="javascript:search();" class="btn btn-success"> إستعلام</button>
            <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>
            <button type="button" class="btn btn-warning" onclick="$('#page_tb').tableExport({type:'excel',escape:'false'});"> تصدير لاكسل </button>
            <button type="button" id="print_rep_list" onclick="javascript:print_rep_list();" class="btn btn-success"> <i class="glyphicon glyphicon-print"></i> طباعة </button>

        </div>

        <div id="msg_container"></div>

        <div id="container">
            <?=modules::run($get_page_url, $page, $class_output_id, $source, $request_no, $from_store_id, $class_output_type, $class_output_account_id, $note, $adopt, $entry_user);?>
        </div>

    </div>

</div>


<?php
$scripts = <<<SCRIPT
<script>

    $('.pagination li').click(function(e){
        e.preventDefault();
    });

    function show_row_details(id){
        get_to_link('{$get_url}/'+id+'/{$edit}');
    }

    $('#dp_class_output_type').change(function(){
        $('#txt_class_output_account_id').val('');
        $('#h_txt_class_output_account_id').val('');
    });

    $('#txt_class_output_account_id').click(function(e){
        var _type = $('#dp_class_output_type').val();
        if(_type == 1)
            _showReport('$select_accounts_url/'+$(this).attr('id') );
        else if(_type == 2)
            _showReport('$customer_url/'+$(this).attr('id')+'/1');
    });

    function search(){
        var values= {page:1, class_output_id:$('#txt_class_output_id').val(), source:$('#dp_source').val(), request_no:$('#txt_request_no').val(), from_store_id:$('#dp_from_store_id').val(), class_output_type:$('#dp_class_output_type').val(), class_output_account_id:$('#h_txt_class_output_account_id').val(), note:$('#txt_note').val(), adopt:$('#dp_adopt').val(), entry_user:$('#dp_entry_user').val() };
        get_data('{$get_page_url}',values ,function(data){
            $('#container').html(data);
        },'html');
    }

    function LoadingData(){
        var values= {class_output_id:$('#txt_class_output_id').val(), source:$('#dp_source').val(), request_no:$('#txt_request_no').val(), from_store_id:$('#dp_from_store_id').val(), class_output_type:$('#dp_class_output_type').val(), class_output_account_id:$('#h_txt_class_output_account_id').val(), note:$('#txt_note').val(), adopt:$('#dp_adopt').val(), entry_user:$('#dp_entry_user').val() };
        ajax_pager_data('#page_tb > tbody',values);
    }

    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
    }

    $('#print_rep_list').click(function(){
        _showReport('{$report_url}&report_type=pdf&report=Stores_Class_Output_List&p_from_store_id='+$('#dp_from_store_id').val()+'&sn={$report_sn}');
    });

</script>
SCRIPT;
sec_scripts($scripts);
?>
