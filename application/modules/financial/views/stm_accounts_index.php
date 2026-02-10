<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 08/11/14
 * Time: 09:08 ص
 */

$get_page_url = base_url("financial/accounts/get_page_stm_accounts");
?>

<?= AntiForgeryToken() ?>
<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>

        </ul>

    </div>

    <div class="form-body">
        <fieldset>
            <legend>بحـث</legend>
            <div class="modal-body inline_form">
                <div class="form-group col-sm-1">
                    <label class="control-label">رقم الحساب </label>
                    <div>
                        <input type="hidden" id="balance"  >
                        <input type="hidden" id="c_balance"  >
                        <input type="text"  name="account" id="txt_account" value="<?= $account ?>"  class="form-control">
                    </div>
                </div>


                <div class="form-group col-sm-2">
                    <label class="control-label"> التاريخ من</label>
                    <div>
                        <input type="text" data-type="date" data-date-format="DD/MM/YYYY" name="from_date"    id="txt_from_date" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label"> التاريخ الي</label>
                    <div>
                        <input type="text" data-type="date" data-date-format="DD/MM/YYYY" name="to_date"    id="txt_to_date" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-3">
                    <label class="control-label">المبلغ</label>
                    <div>
                        <select id="price_op" class="form-control col-sm-1">
                            <option>=</option>
                            <option><=</option>
                            <option>>=</option>
                            <option><</option>
                            <option>></option>
                        </select>

                        <input type="text"  name="price"   id="txt_price" class="form-control col-sm-5">
                    </div>
                </div>

            </div>
            <div class="modal-footer">

                <button type="button" onclick="javascript:do_search();" class="btn btn-success">بحث</button>
                <div class="btn-group">
                    <button class="btn btn-warning dropdown-toggle" onclick="$('#accountsTbl').tableExport({type:'excel',escape:'false'});" data-toggle="dropdown"><i class="fa fa-bars"></i> تصدير</button>
                </div>
                <button type="button" onclick="javascript:$('#txt_to_date,#txt_from_date,#txt_price,#balance,#c_balance').val('');do_search();" class="btn btn-default">تفريغ الحقول</button>

            </div>
        </fieldset>
        <div id="msg_container"></div>

        <div id="container">
            <?=modules::run('financial/accounts/get_page_stm_accounts',$page,$account);?>
        </div>

    </div>

</div>

<?php


$scripts = <<<SCRIPT
<script>

    $(function(){
        reBind();
        ApplyOtherAction();
        $('#balance').val(stm_balance);
        $('#c_balance').val(stm_c_balance);
    });

    function resetBalance(balance,c_balance){
        $('#balance').val(balance);
        $('#c_balance').val(c_balance);
    }


    function reBind(){
        ajax_pager({account : $('#txt_account').val(),from_date:$('#txt_from_date').val(),to_date:$('#txt_to_date').val(),balance:$('#balance').val(),c_balance:$('#c_balance').val(),price:$('#txt_price').val(),price_op:$('#price_op').val()});
    }

    function LoadingData(){
        ajax_pager_data('#accountsTbl > tbody',{account : $('#txt_account').val(),from_date:$('#txt_from_date').val(),to_date:$('#txt_to_date').val(),balance:$('#balance').val(),c_balance:$('#c_balance').val(),price:$('#txt_price').val(),price_op:$('#price_op').val()});
    }

    function ApplyOtherAction(data){
        $('#accountsTbl > tbody > tr >td.balance:contains("0.00")').not(':contains("م"),:contains("د")').closest('tr').find('td').css({'background-color':'#F24D26','color':'#fff'});

        $('#balance').val($('#stm_balance',$(data)).val());
        $('#c_balance').val($('#stm_c_balance',$(data)).val());
    }

    function do_search(){

        get_data('{$get_page_url}',{page: 1,account : $('#txt_account').val(),from_date:$('#txt_from_date').val(),to_date:$('#txt_to_date').val(),balance:$('#balance').val(),c_balance:$('#c_balance').val(),price:$('#txt_price').val(),price_op:$('#price_op').val()},function(data){
            $('#container').html(data);
            reBind();
        },'html');
    }
</script>

SCRIPT;

sec_scripts($scripts);



?>

