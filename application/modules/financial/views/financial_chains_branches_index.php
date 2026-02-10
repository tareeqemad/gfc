<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 20/12/14
 * Time: 10:09 ص
 */

$MODULE_NAME= 'financial';
$TB_NAME= 'financial_chains_branches';

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
                <div class="form-group col-sm-2">
                    <label class="control-label">نوع التقرير</label>
                    <div>
                        <select name="report_name" id="dp_report_name" class="form-control" />
                        <option></option>
                        <?php foreach($report_name_all as $row) :?>
                            <option value="<?=$row['SER']?>"><?=$row['NAME']?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-3">
                    <label class="control-label">بيان التقرير</label>
                    <div>
                        <input type="text"  name="report_hints" id="txt_report_hints" class="form-control">
                    </div>
                </div>

               <!-- <div class="form-group col-sm-6">

                    <div class="form-group col-sm-6 rp_prm" id="parent">
                        <label class="col-sm-1 control-label">رقم الحساب</label>
                        <div class="col-sm-12">
                            <input type="number" id="h_txt_parent"  class="form-control col-sm-4" />
                            <input type="text" readonly name="parent" id="txt_parent" class="form-control col-sm-8" />

                        </div>
                    </div>
                </div>-->




            </div>
        </form>

        <div class="modal-footer">
            <button type="button" onclick="javascript:search();" class="btn btn-success"> إستعلام</button>
            <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>
        </div>

        <div id="msg_container"></div>

        <div id="container">

        </div>

    </div>

</div>


<?php
$select_parent_url =base_url('financial/accounts/public_select_parent');
$scripts = <<<SCRIPT
<script>

$(function(){
            $('input[name="parent"]').click(function(e){
            _showReport('$select_parent_url/'+$(this).attr('id')+'/-1/' );
        });
    });
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
        var values= {page:1, request_no:$('#txt_request_no').val(), request_side:$('#dp_request_side').val(), request_side_account:$('#h_txt_request_side_account').val(), request_type:$('#dp_request_type').val(), project_id:$('#txt_project_id').val(), adopt:$('#dp_adopt').val()};
        get_data('{$get_page_url}',values ,function(data){
            $('#container').html(data);
        },'html');
    }

    function LoadingData(){
        var values= {request_no:$('#txt_request_no').val(), request_side:$('#dp_request_side').val(), request_side_account:$('#h_txt_request_side_account').val(), request_type:$('#dp_request_type').val(), project_id:$('#txt_project_id').val(), adopt:$('#dp_adopt').val()};
        ajax_pager_data('#page_tb > tbody',values);
    }

    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
        chk_request_type();
    }
 _showReport(url);
</script>
SCRIPT;
sec_scripts($scripts);
?>
