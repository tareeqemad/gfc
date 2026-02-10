<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 24/12/14
 * Time: 04:27 م
 */

$MODULE_NAME= 'stores';
$TB_NAME= 'stores_class_transport';

$get_url =base_url("$MODULE_NAME/$TB_NAME/get");
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$edit_url =base_url("$MODULE_NAME/$TB_NAME/edit");
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");

$select_accounts_url =base_url('financial/accounts/public_select_accounts');
$customer_url =base_url('payment/customers/public_index');

if(HaveAccess($edit_url))
    $edit= 'edit';
else
    $edit= '';

$archive= isset($archive)?$archive:-1;

if($archive==1)
    $edit= 'archive';

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
                    <label class="control-label">رقم المناقلة</label>
                    <div>
                        <input type="text"  name="class_transport_id" id="txt_class_transport_id" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">رقم الطلب</label>
                    <div>
                        <input type="text"  name="request_no" id="txt_request_no" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">المخزن المنقول منه</label>
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
                    <label class="control-label"> المخزن المنقول اليه</label>
                    <div>
                        <select name="to_store_id" id="dp_to_store_id" class="form-control" />
                        <option></option>
                        <?php foreach($stores_all as $row) :?>
                            <option value="<?=$row['STORE_ID']?>"><?=$row['STORE_NO'].': '.$row['STORE_NAME']?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-3">
                    <label class="control-label">البيان</label>
                    <div>
                        <input type="text"  name="note" id="txt_note" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">الجهة الطالبة</label>
                    <div>
                        <select name="request_side" id="dp_request_side" class="form-control" />
                        <option></option>
                        <?php foreach($request_side_all as $row) :?>
                            <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">الحساب المنقول اليه</label>
                    <div>
                        <input type="text"  readonly  class="form-control" id="txt_class_transport_account_id" />
                        <input type="hidden" name="class_transport_account_id" id="h_txt_class_transport_account_id" />
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">الغرض من النقل </label>
                    <div>
                        <select name="class_transport_type" id="dp_class_transport_type" class="form-control" />
                        <option></option>
                        <?php foreach($request_type_all as $row) :?>
                            <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                        <?php endforeach; ?>
                        </select>
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
        </div>

        <div id="msg_container"></div>

        <div id="container">
            <?=modules::run($get_page_url, $page, $archive, $class_transport_id, $request_no, $from_store_id, $to_store_id, $note, $request_side, $class_transport_account_id, $class_transport_type, $adopt, $entry_user)  ;?>
        </div>

    </div>

</div>


<?php
$scripts = <<<SCRIPT
<script>

    var archive= $archive;

    $('.pagination li').click(function(e){
        e.preventDefault();
    });

    function show_row_details(id){
        get_to_link('{$get_url}/'+id+'/{$edit}');
    }

    $('#dp_request_side').change(function(){
        $('#txt_class_transport_account_id').val('');
        $('#h_txt_class_transport_account_id').val('');
    });

    $('#txt_class_transport_account_id').click(function(e){
        var _type = $('#dp_request_side').val();
        if(_type == 1)
            _showReport('$select_accounts_url/'+$(this).attr('id') );
        else if(_type == 2)
            _showReport('$customer_url/'+$(this).attr('id')+'/1');
    });

    function search(){
        var values= {archive:archive ,page:1, class_transport_id:$('#txt_class_transport_id').val(), request_no:$('#txt_request_no').val(), from_store_id:$('#dp_from_store_id').val(), to_store_id :$('#dp_to_store_id').val(), note :$('#txt_note').val(), request_side:$('#dp_request_side').val(), class_transport_account_id:$('#h_txt_class_transport_account_id').val(), class_transport_type:$('#dp_class_transport_type').val(), adopt:$('#dp_adopt').val(), entry_user:$('#dp_entry_user').val() };
        get_data('{$get_page_url}',values ,function(data){
            $('#container').html(data);
        },'html');
    }

    function LoadingData(){
        var values= {archive:archive, class_transport_id:$('#txt_class_transport_id').val(), request_no:$('#txt_request_no').val(), from_store_id:$('#dp_from_store_id').val(), to_store_id :$('#dp_to_store_id').val(), note :$('#txt_note').val(), request_side:$('#dp_request_side').val(), class_transport_account_id:$('#h_txt_class_transport_account_id').val(), class_transport_type:$('#dp_class_transport_type').val(), adopt:$('#dp_adopt').val(), entry_user:$('#dp_entry_user').val() };
        ajax_pager_data('#page_tb > tbody', values);
    }

    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
    }

</script>
SCRIPT;
sec_scripts($scripts);
?>
