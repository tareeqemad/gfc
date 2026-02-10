<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 05/01/15
 * Time: 03:54 م
 */

$MODULE_NAME= 'stores';
$TB_NAME= 'stores_class_return';

$get_url =base_url("$MODULE_NAME/$TB_NAME/get");
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$edit_url =base_url("$MODULE_NAME/$TB_NAME/edit");
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");

$select_accounts_url =base_url('financial/accounts/public_select_accounts');

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
            <?php if( HaveAccess($create_url)):  ?><li><a  href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>جديد </a> </li><?php endif; ?>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
        </ul>

    </div>

    <div class="form-body">
        <form class="form-vertical" id="<?=$TB_NAME?>_form" >
            <div class="modal-body inline_form">

                <div class="form-group col-sm-1">
                    <label class="control-label">رقم سند الارجاع</label>
                    <div>
                        <input type="text"  name="class_return_id" id="txt_class_return_id" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label"> المخزن المرجع إليه </label>
                    <div>
                        <select name="to_store_id" id="dp_to_store_id" class="form-control" />
                        <option></option>
                        <?php foreach($stores_all as $row) :?>
                            <option value="<?=$row['STORE_ID']?>"><?=$row['STORE_NO'].': '.$row['STORE_NAME']?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label"> نوع سند الإرجاع </label>
                    <div>
                        <select name="class_return_type" id="dp_class_return_type" class="form-control" />
                        <option></option>
                        <?php foreach($request_type_all as $row) :?>
                            <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label"> حساب الإرجاع </label>
                    <div>
                        <input type="text"  readonly  class="form-control" id="txt_class_return_account_id" />
                        <input type="hidden" name="class_return_account_id" id="h_txt_class_return_account_id" />
                    </div>
                </div>

                <div class="form-group col-sm-3">
                    <label class="control-label"> البيان </label>
                    <div>
                        <input type="text" name="notes" id="txt_notes" class="form-control">
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
        </div>

        <div id="msg_container"></div>

        <div id="container">
            <?=modules::run($get_page_url, $page, $class_return_id, $to_store_id, $class_return_type, $class_return_account_id, $notes, $adopt, $entry_user );?>
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

    $('#txt_class_return_account_id').click(function(e){
        _showReport('$select_accounts_url/'+$(this).attr('id') );
    });

    function search(){
        var values= {page:1, class_return_id:$('#txt_class_return_id').val(), to_store_id:$('#dp_to_store_id').val(), class_return_type:$('#dp_class_return_type').val(), class_return_account_id:$('#h_txt_class_return_account_id').val(), notes:$('#txt_notes').val(), adopt:$('#dp_adopt').val(), entry_user:$('#dp_entry_user').val() };
        get_data('{$get_page_url}',values ,function(data){
            $('#container').html(data);
        },'html');
    }

    function LoadingData(){
        var values= { class_return_id:$('#txt_class_return_id').val(), to_store_id:$('#dp_to_store_id').val(), class_return_type:$('#dp_class_return_type').val(), class_return_account_id:$('#h_txt_class_return_account_id').val(), notes:$('#txt_notes').val(), adopt:$('#dp_adopt').val(), entry_user:$('#dp_entry_user').val() };
        ajax_pager_data('#page_tb > tbody',values);
    }

    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
    }

</script>
SCRIPT;
sec_scripts($scripts);
?>
