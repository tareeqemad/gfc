<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 15/10/14
 * Time: 09:11 ص
 */

$get_service_url =base_url('treasury/income_voucher/get_service');
$get_account_url = base_url('financial/accounts_permission/public_get_user_accounts');
$back_url=base_url('treasury/convert_cash_bank/index');
$adopt_url =base_url('treasury/convert_cash_bank/adopt');
$delete_url = base_url('treasury/convert_cash_bank/delete');


$get_balance_url=base_url('financial/financial_chains/public_get_balance');
$isCreate =isset($cash_bank_data)?false:true;
$isAdopt = HaveAccess($adopt_url) && (!$isCreate)?$cash_bank_data[0]['CONVERT_CASH_BANK_CASE'] != 2 && $cash_bank_data[0]['CONVERT_CASH_BANK_CASE'] !=0 :false;

?>
<?= AntiForgeryToken() ?>
    <div class="row">
        <div class="toolbar">

            <div class="caption"><?= $title ?></div>

            <ul>
                <?php  if( HaveAccess($back_url)):  ?><li><a  href="<?= $back_url ?>"><i class="icon icon-reply"></i> </a> </li><?php  endif; ?>
                <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
            </ul>

        </div>

        <div class="form-body">

            <div id="msg_container"></div>
            <div id="container">
                <form class="form-vertical" id="cash_bank_from" method="post" action="<?=base_url('treasury/convert_cash_bank/').((isset($can_edit) && $can_edit)?'/edit':'/create')?>" role="form" novalidate="novalidate">
                    <div class="modal-body inline_form">
                        <div class="form-group col-sm-2">
                            <label class="control-label">  نوع سند الإيداع </label>
                            <div class="">
                                <select name="convert_cash_bank_type" id="dp_convert_cash_bank_type" class="form-control">

                                    <option value="1">نقدا</option>

                                </select>
                            </div>
                        </div>
                        <div class="form-group col-sm-3">
                            <label class="control-label"> حساب الخزينة  </label>
                            <div class="">
                                <input type="text" name="treasury_account_id" data-val="true"  data-val-required="حقل مطلوب"  id="txt_treasury_account_id"  class="form-control  <?= $isCreate?'easyui-combotree':'' ?> " data-options=" animate:true,lines:true,required:true">
                                <span class="field-validation-valid" data-valmsg-for="treasury_account_id" data-valmsg-replace="true"></span>

                            </div>
                        </div>

                        <div class="form-group col-sm-1">
                            <label class="control-label"> الرصيد  </label>
                            <div class="">
                                <input type="text"  readonly  id="txt_balance"  class="form-control"    >

                            </div>
                        </div>
                        <hr>


                        <div class="form-group col-sm-2">
                            <label class="control-label">رقم السند </label>
                            <div class="">
                                <input type="text" readonly name="convert_cash_bank_id" id="txt_convert_cash_bank_id" class="form-control ">


                            </div>
                        </div>

                        <div class="form-group col-sm-2">
                            <label class="control-label"> تاريخ  السند </label>
                            <div class="">
                                <input type="text" name="convert_cash_bank_date"    data-date-format="DD/MM/YYYY"  data-type="date"  value="<?= date('d/m/Y') ?>" id="txt_convert_cash_bank_date" class="form-control ">
                            </div>
                        </div>

                        <div class="form-group col-sm-2">
                            <label class="control-label">رقم فيشة الإيداع </label>
                            <div class="">
                                <input type="text" name="convert_cash_bank_one"  data-val="true"  data-val-required="حقل مطلوب"   data-val-regex="المدخل غير صحيح!" data-val-regex-pattern="\d{1,}" id="txt_convert_cash_bank_one" class="form-control ">
                                <span class="field-validation-valid" data-valmsg-for="convert_cash_bank_one" data-valmsg-replace="true"></span>

                            </div>
                        </div>

                        <hr>
                        <div class="form-group col-sm-4">
                            <label class="control-label" id="cash_bankTitle" >حساب البنك</label>
                            <div class="">
                                <input type="text" name="convert_cash_bank_account_id" id="txt_convert_cash_bank_account_id" class="form-control   easyui-combotree " data-options="animate:true,lines:true,required:true">
                                <span class="field-validation-valid" data-valmsg-for="convert_cash_bank_account_id" data-valmsg-replace="true"></span>

                            </div>
                        </div>

                        <div class="form-group col-sm-2">
                            <label class="control-label"> مجموع النقود المودعة   </label>
                            <div class="">
                                <input type="text" name="convert_cash_bank_total"  data-val-regex="المدخل غير صحيح " data-val-regex-pattern="<?= float_format_exp() ?>" data-val="true"  data-val-required="حقل مطلوب"  id="txt_convert_cash_bank_total" class="form-control ">
                                <span class="field-validation-valid" data-valmsg-for="convert_cash_bank_total" data-valmsg-replace="true"></span>
                            </div>
                        </div>


                        <div class="form-group col-sm-2">
                            <label class="control-label"> المقبوضات و المصروفات</label>
                            <div class="">


                                <select name="bk_fin_id" id="dp_bk_fin_id" class="form-control">
                                    <option></option>
                                    <?php foreach($BkFin as $_row) :?>
                                        <option   value="<?= $_row['ID'] ?>"><?= str_repeat('-' , strlen($_row['ID'])) . ' '. $_row['TITLE'] ?></option>
                                    <?php endforeach; ?>
                                </select>

                            </div>
                        </div>

                        <div class="form-group col-sm-12">
                            <label class="control-label"> البيان   </label>
                            <div class="">
                                <input type="text" name="convert_cash_bank_hints"  data-val="true"  data-val-required="حقل مطلوب"  id="txt_convert_cash_bank_hints" class="form-control ">
                                <span class="field-validation-valid" data-valmsg-for="convert_cash_bank_hints" data-valmsg-replace="true"></span>
                            </div>
                        </div>






                    </div>



                    <div class="modal-footer">
                        <?php if ($isCreate || (isset($can_edit) && $can_edit)) : ?>
                            <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>

                        <?php endif; ?>

                        <?php if (isset($can_edit) && $can_edit) : ?>

                            <button type="button" onclick="javascript:delete_cash();" class="btn btn-danger">إلغاء السند</button>
                        <?php endif; ?>


                        <?php if (!$isCreate && $isAdopt) : ?>

                            <button type="button" onclick="javascript:adopt_cash();" class="btn btn-primary">إعتماد</button>
                        <?php endif; ?>

                    </div>
                </form>

            </div>

        </div>

    </div>
<?php


$today = date('d/m/Y');
$isAdopt = (!$isCreate && $isAdopt)? 1 : 0;
$shared_script =<<<SCRIPT



 var B_prefix = '{$bank_prefix}';

    $(function () {


        $('#dp_convert_cash_bank_type').change(function(){
         changeCash_bankTitle($(this).val());
            get_treasury_account();


        });
        get_treasury_account();

    $('#txt_treasury_account_id').combotree({
            onSelect:function(account) {

                 get_data('{$get_balance_url}',{id: account.id},function(data){
                            $('#txt_balance').val(data);
                 },'html');

            }
        });
    });


    function get_treasury_account(){
        $('#txt_debit_total').val('0');
        get_data('{$get_account_url}',{perfix:'$treasure_prefix',type: $('#dp_convert_cash_bank_type').val(),case:2},function(data){

            if(data.length > 0){
                $('#txt_treasury_account_id').combotree('loadData',data);
                if(treasury_account_id == ''){
                    $('#txt_treasury_account_id').combotree('setValue', data[0].id);
                    $('#txt_treasury_account_id').combotree('setText',data[0].text);
                }else{
                    $('#txt_treasury_account_id').combotree('setValue', treasury_account_id);
                    $('#txt_treasury_account_id').combotree('setText',treasury_account_id_name);
                }
            } else{

                $('#txt_treasury_account_id').combotree('loadData',[]);
                $('#txt_treasury_account_id').combotree('clear');

            }
        });
        get_account_id();
    }


function changeCash_bankTitle(type){

    if(type == 1){
        $('#cash_bankTitle').text('حساب البنك');
         B_prefix = '{$bank_prefix}';
        }
    else if(type == 2){
      $('#cash_bankTitle').text('حساب شيكات برسم التحصيل');
       B_prefix = '{$back_ch_prefix}';
      }

}

    function get_account_id(){



        get_data('{$get_account_url}',{perfix:B_prefix,type: $('#dp_convert_cash_bank_type').val()},function(data){
            console.log('',data);
            if(data.length > 0){
                $('#txt_convert_cash_bank_account_id').combotree('loadData',data);
                if(convert_cash_bank_account_id == ''){
                    $('#txt_convert_cash_bank_account_id').combotree('setText',data[0].text);
                    $('#txt_convert_cash_bank_account_id').combotree('setValue', data[0].id);
                }else{
                    $('#txt_convert_cash_bank_account_id').combotree('setText',convert_cash_bank_account_id_name);
                    $('#txt_convert_cash_bank_account_id').combotree('setValue', convert_cash_bank_account_id);
                }

            } else{
             if({$isAdopt} != 1){
                $('#txt_convert_cash_bank_account_id').combotree('loadData',[]);
                $('#txt_convert_cash_bank_account_id').combotree('clear');
              }
            }
        });
    }


    $('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        if(confirm('هل تريد حفظ السند ؟!')){
			
            $(this).attr('disabled','disabled');
            var form = $(this).closest('form');
            ajax_insert_update(form,function(data){
                success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                setTimeout(function(){
                    window.location.reload();
                }, 1000);
            },'html');
        }
        setTimeout(function() {
            $('button[data-action="submit"]').removeAttr('disabled');
        }, 5000);
    });


SCRIPT;

$scripts = <<<SCRIPT
<script>

var treasury_account_id='';
var treasury_account_id_name='';

var convert_cash_bank_account_id='';
var convert_cash_bank_account_id_name='';

    {$shared_script}
    function clear_form(){
        clearForm($('#cash_bank_from'));
        $('#txt_convert_cash_bank_date').val('$today');
    }


</script>
SCRIPT;

$rs=$isCreate ?array() : $cash_bank_data[0];

$disabled_script =(isset($can_edit) && $can_edit)?"": "$('#cash_bank_from input,#cash_bank_from select').prop('disabled',true);";


if($isCreate)
    sec_scripts($scripts);
else{
    $delete_script = <<<SCRIPT
<script>
function delete_cash(){
if(confirm('هل تريد إلغاء السند؟!')){
        get_data('{$delete_url}',{id:{$rs['CONVERT_CASH_BANK_ID']}},function(data){
            if(data =='1')
           success_msg('رسالة','تم إلغاء السند بنجاح ..');

                    setTimeout(function(){

                     window.location = '$back_url';

                }, 1000);

        },'html');
        }
}
</script>
SCRIPT;
    $edit_script = <<<SCRIPT

<script>

var treasury_account_id='';
var treasury_account_id_name='';

var convert_cash_bank_account_id='';
var convert_cash_bank_account_id_name='';

    $('#dp_convert_cash_bank_type').val({$rs['CONVERT_CASH_BANK_TYPE']});
    $('#txt_treasury_account_id').val('{$rs['TREASURY_ACCOUNT_ID_NAME']}');
    $('#txt_convert_cash_bank_id').val({$rs['CONVERT_CASH_BANK_ID']});
    $('#txt_convert_cash_bank_date').val('{$rs['CONVERT_CASH_BANK_DATE']}');
    $('#txt_convert_cash_bank_one').val('{$rs['CONVERT_CASH_BANK_ONE']}');
    $('#txt_convert_cash_bank_account_id').val('{$rs['CONVERT_CASH_BANK_ACCOUNT_NAME']}');
    $('#txt_convert_cash_bank_total').val('{$rs['CONVERT_CASH_BANK_TOTAL']}');
    $('#txt_convert_cash_bank_hints').val('{$rs['CONVERT_CASH_BANK_HINTS']}');
    $('#dp_bk_fin_id').val('{$rs['BK_FIN_ID']}');

     treasury_account_id='{$rs['TREASURY_ACCOUNT_ID']}';
     treasury_account_id_name='{$rs['TREASURY_ACCOUNT_ID_NAME']}';

     convert_cash_bank_account_id='{$rs['CONVERT_CASH_BANK_ACCOUNT_ID']}';
     convert_cash_bank_account_id_name='{$rs['CONVERT_CASH_BANK_ACCOUNT_NAME']}';

    $disabled_script

    function adopt_cash(){
      if(confirm('هل تريد اعتماد السند ؟!')){
        get_data('{$adopt_url}',{convert_cash_bank_id:{$rs['CONVERT_CASH_BANK_ID']}},function(data){
            if(data =='1')
                success_msg('رسالة','تم إعتماد السند بنجاح ..');

                  setTimeout(function(){

                   window.location.reload();

                }, 1000);
        },'html');

        }
    }
    {$shared_script}


</script>
SCRIPT;
    if(isset($can_edit) && $can_edit){
        $edit_script = $edit_script.$delete_script;
    }

    sec_scripts($edit_script);
}


?>


