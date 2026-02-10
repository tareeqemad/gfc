<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 20/12/14
 * Time: 10:09 ص
 */

$MODULE_NAME= 'stores';
$TB_NAME= 'stores_payment_request';

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
                    <label class="control-label"> م </label>
                    <div>
                        <input type="text"  name="request_no" id="txt_request_no" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">رقم الطلب</label>
                    <div>
                        <input type="text" name="book_no" id="txt_book_no" class="form-control">
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
                    <label class="control-label">حساب الجهة الطالبة</label>
                    <div>
                        <input type="text"  readonly  class="form-control" id="txt_request_side_account" />
                        <input type="hidden" name="request_side_account" id="h_txt_request_side_account" />
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">الجهة المطلوب منها</label>
                    <div>
                        <select name="request_store_from" id="dp_request_store_from" class="form-control" />
                            <option></option>
                            <?php foreach($stores_all as $row) :?>
                                <option value="<?=$row['STORE_ID']?>"><?=$row['STORE_NO'].': '.$row['STORE_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">نوع الطلب</label>
                    <div>
                        <select name="request_type" id="dp_request_type" class="form-control" />
                            <option></option>
                            <?php foreach($request_type_all as $row) :?>
                                <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2" id="project" style="display: none">
                    <label class="control-label">رقم المشروع</label>
                    <div>
                        <input type="text"  name="project_id" id="txt_project_id" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label"> رقم طلبية الخدمات </label>
                    <div>
                        <input type="text" name="store_serv_req" id="txt_store_serv_req" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-3">
                    <label class="control-label">البيان</label>
                    <div>
                        <input type="text"  name="notes" id="txt_notes" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">حالة الاعتماد </label>
                    <div>
                        <select name="adopt" id="dp_adopt" class="form-control" />
                        <option></option>
                        <?php foreach($adopt_all as $row) :?>
                            <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <?php if(isset($adopt_all2)) { ?>
                <div class="form-group col-sm-2">
                    <label class="control-label">حالة الاعتماد للفروع</label>
                    <div>
                        <select name="adopt2" id="dp_adopt2" class="form-control" />
                        <option></option>
                        <?php foreach($adopt_all2 as $row) :?>
                            <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <?php } ?>

                <div class="form-group col-sm-2">
                    <label class="control-label">حالة الصرف </label>
                    <div>
                        <select name="request_case" id="dp_request_case" class="form-control" />
                        <option></option>
                        <?php foreach($request_case_all as $row) :?>
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
            <?php  if($this->user->id==788){ ?>
            <button type="button" onclick="javascript:session_all_branches();" class="btn btn-info"> كل المقرات</button>
            <?php } ?>
            <button type="button" class="btn btn-warning" onclick="$('#page_tb').tableExport({type:'excel',escape:'false'});"> تصدير لاكسل </button>
        </div>

        <div id="msg_container"></div>

        <div id="container">
            <?=modules::run($get_page_url, $page, $archive, $request_no, $book_no, $request_side, $request_side_account, $request_store_from, $request_type, $project_id, $store_serv_req, $notes, $request_case, $adopt, $adopt2, $entry_user);?>
        </div>

    </div>

</div>


<?php
$scripts = <<<SCRIPT
<script>

    var archive= $archive;

    auto_restart_search();

    $('.pagination li').click(function(e){
        e.preventDefault();
    });

    function show_row_details(id){
        get_to_link('{$get_url}/'+id+'/{$edit}');
    }

    $('#dp_request_side').change(function(){
        $('#txt_request_side_account').val('');
        $('#h_txt_request_side_account').val('');
    });

    $('#dp_request_type').change(function(){
        chk_request_type();
    });

    function chk_request_type(){
        var typ= $('#dp_request_type').val();
        if(typ ==1)
            $('div#project').fadeIn(300);
        else{
            $('div#project').fadeOut(300);
            $('#txt_project_id').val('');
        }
    }

    $('#txt_request_side_account').click(function(e){
        var _type = $('#dp_request_side').val();
        if(_type == 1)
            _showReport('$select_accounts_url/'+$(this).attr('id') );
        else if(_type == 2)
            _showReport('$customer_url/'+$(this).attr('id')+'/1');
    });

    function search(){
        var values= {page:1, archive:archive, request_no:$('#txt_request_no').val(), book_no:$('#txt_book_no').val(), request_side:$('#dp_request_side').val(), request_side_account:$('#h_txt_request_side_account').val(), request_store_from:$('#dp_request_store_from').val(), request_type:$('#dp_request_type').val(), project_id:$('#txt_project_id').val(), store_serv_req:$('#txt_store_serv_req').val(), notes:$('#txt_notes').val(), request_case:$('#dp_request_case').val(), adopt:$('#dp_adopt').val(), adopt2:$('#dp_adopt2').val(), entry_user:$('#dp_entry_user').val() };
        get_data('{$get_page_url}',values ,function(data){
            $('#container').html(data);
        },'html');
    }
    
    function session_all_branches(){
        get_data('{$get_page_url}',{'session_all_branches':1} ,function(data){
            get_to_link(window.location.href);
        },'html');
    }

    function LoadingData(){
        var values= {archive:archive, request_no:$('#txt_request_no').val(), book_no:$('#txt_book_no').val(), request_side:$('#dp_request_side').val(), request_side_account:$('#h_txt_request_side_account').val(), request_store_from:$('#dp_request_store_from').val(), request_type:$('#dp_request_type').val(), project_id:$('#txt_project_id').val(), store_serv_req:$('#txt_store_serv_req').val(), notes:$('#txt_notes').val(), request_case:$('#dp_request_case').val(), adopt:$('#dp_adopt').val(), adopt2:$('#dp_adopt2').val(), entry_user:$('#dp_entry_user').val() };
        ajax_pager_data('#page_tb > tbody',values);
    }

    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
        chk_request_type();
    }

</script>
SCRIPT;
sec_scripts($scripts);
?>
