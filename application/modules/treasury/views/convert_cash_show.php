<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 15/10/14
 * Time: 09:11 ص
 */


$get_account_url = base_url('financial/accounts_permission/public_get_user_accounts');
$get_sum_url = base_url('treasury/convert_cash/public_get_sum');
$get_constant_details_url = base_url('settings/constant_details/public_get_id');
$back_url=base_url('treasury/convert_cash/'.(isset($action)?$action:'index'));
$adopt_url =base_url('treasury/convert_cash/adopt');
$delete_url = base_url('treasury/convert_cash/delete');

$cancel_url = base_url('treasury/convert_cash/cancel');

$checks_url = base_url('treasury/convert_cash/public_check_list');
$voucher_credits_url = base_url('treasury/convert_cash/public_income_voucher_income_type_get');

$isCreate =isset($cash_data)?false:true;
$isAdopt = HaveAccess($adopt_url) && (!$isCreate)?$cash_data[0]['CONVERT_CASH_CASE'] != 2 && $cash_data[0]['CONVERT_CASH_CASE'] !=0 :false;
?>
<?= AntiForgeryToken() ?>
<div class="row">
<div class="toolbar">

    <div class="caption"><?= $title ?></div>

    <ul>
        <?php  if( HaveAccess($back_url)):  ?><li><a  href="<?= $back_url ?>"><i class="icon icon-reply"></i> </a> </li><?php  endif; ?>
        <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>

        <!--     <li><a href="#">بحث</a> </li>-->
    </ul>

</div>

<div class="form-body">

    <div id="msg_container"></div>
    <div id="container">
        <form class="form-vertical" id="cash_from" method="post" action="<?=base_url('treasury/convert_cash/create')?>" role="form" novalidate="novalidate">
            <div class="modal-body inline_form">

                <div class="form-group col-sm-3">
                    <label class="control-label"> المحصل </label>
                    <div class="">

                        <?php if ($isCreate) : ?>
                            <select name="telar_id"  class="form-control" id="dp_telar_id">
                                <option></option>
                                <?php foreach($users as $row) :?>
                                    <option value="<?= $row['ID'] ?>"><?= $row['USER_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        <?php endif;?>

                        <?php if (!$isCreate) : ?>
                            <input type="text" id="txt_telar_id"  class="form-control"/ >
                            <?php endif;?>


                    </div>
                </div>
                <div class="form-group col-sm-2">
                    <label class="control-label">  نوع سند الإستلام </label>
                    <div class="">
                        <select name="convert_cash_type" id="dp_convert_cash_type" class="form-control">
                            <?php foreach($income_type as $row) :?>
                                <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group col-sm-3">
                    <label class="control-label"> حساب الصندوق  </label>
                    <div class="">
                        <input type="text" name="debit_account_id" data-val="true"  data-val-required="حقل مطلوب"  id="txt_debit_account_id"  class="form-control <?= $isCreate?'easyui-combotree':'' ?> " data-options="url:'<?= $get_account_url ?>',method:'get',animate:true,lines:true,required:true">
                        <span class="field-validation-valid" data-valmsg-for="debit_account_id" data-valmsg-replace="true"></span>

                    </div>
                </div>

                <?php if ($isCreate) : ?>
                    <div class="form-group col-sm-2">
                        <label class="control-label"> مجموع النقود المقبوضة   </label>
                        <div class="">
                            <input type="text"  readonly id="txt_debit_total" class="form-control ">

                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label"> مجموع النقود غير مغلقة </label>
                        <div class="">
                            <input type="text"  readonly id="txt_debit_total_2" class="form-control ">

                        </div>
                    </div>

                <?php endif; ?>
                <hr/>


                <div class="form-group col-sm-1">
                    <label class="control-label">رقم سند </label>


                    <div >
                        <input type="text" name="convert_cash_id" readonly data-val-regex="المدخل غير صحيح!" data-val-regex-pattern="\d{1,}"   id="txt_convert_cash_id" class="form-control ">

                    </div>
                </div>
                <div class="form-group col-sm-2">


                    <label class="control-label"> تاريخ  القبض </label>
                    <div class="">
                        <input type="text" name="voucher_date"    data-type="date"  value="<?= date('d/m/Y') ?>"  data-date-format="DD/MM/YYYY"   data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?= date_format_exp() ?>" data-val="true"  data-val-required="حقل مطلوب"  id="txt_voucher_date" class="form-control ">
                        <span class="field-validation-valid" data-valmsg-for="voucher_date" data-valmsg-replace="true"></span>
                    </div>
                </div>


                <div class="form-group col-sm-2">
                    <label class="control-label"> تاريخ  السند </label>
                    <div class="">
                        <input type="text" name="convert_cash_date" readonly   value="<?= date('d/m/Y') ?>"  data-date-format="DD/MM/YYYY"   data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?= date_format_exp() ?>" data-val="true"  data-val-required="حقل مطلوب"  id="txt_convert_cash_date" class="form-control ">
                        <span class="field-validation-valid" data-valmsg-for="convert_cash_date" data-valmsg-replace="true"></span>
                    </div>
                </div>



                <div class="form-group col-sm-3">
                    <label class="control-label"> حساب الخزينة  </label>
                    <div class="">
                        <input type="text" name="treasury_account_id" data-val="true"  data-val-required="حقل مطلوب"  id="txt_treasury_account_id"  class="form-control  <?= $isCreate?'easyui-combotree':'' ?>" data-options="url:'<?= $get_account_url ?>',method:'get',animate:true,lines:true,required:true">
                        <span class="field-validation-valid" data-valmsg-for="treasury_account_id" data-valmsg-replace="true"></span>

                    </div>
                </div>
                <div class="form-group col-sm-12">
                    <label class="control-label"> البيان   </label>
                    <div class="">
                        <input type="text" name="convert_cash_hint"   data-val="true"  data-val-required="حقل مطلوب"  id="txt_convert_cash_hint" class="form-control ">
                        <span class="field-validation-valid" data-valmsg-for="convert_cash_hint" data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label"> مجموع النقود المستلمة   </label>
                    <div class="">
                        <input type="text" name="convert_cash_total"  data-val-regex="المدخل غير صحيح " data-val-regex-pattern="<?= float_format_exp()?>" data-val="true"  data-val-required="حقل مطلوب"  id="txt_convert_cash_total" class="form-control ">
                        <span class="field-validation-valid" data-valmsg-for="convert_cash_total" data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">  حالة النقود المقبوضة </label>
                    <div class="">
                        <input type="hidden" name="money_received_id"/>
                        <select  disabled id="dp_money_received_id" class="form-control">

                            <?php foreach($money_received as $row) :?>
                                <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>





                <div class="form-group col-sm-4" id="convert_cash_total_account_id_div" style="display: none;">
                    <label class="control-label"> رقم حساب العجز أو الفائض    </label>
                    <div class="">
                        <input type="hidden"   value="<?= count($deficit_surplus)>0?$deficit_surplus[0]['ACCOUNT_ID']:'' ?>" name="convert_cash_total_account_id" id="txt_convert_cash_total_account_id" />
                        <input type="text" readonly  value="<?= count($deficit_surplus)>0?$deficit_surplus[0]['ACCOUNT_ID_NAME']:'' ?>"   id="txt_convert_cash_total_account_id_name" class="form-control ">
                    </div>
                </div>

                <div class="form-group col-sm-2" id="convert_cash_total_dc_div" style="display: none;">
                    <label class="control-label">مجموع النقود العجز أو الفائض  </label>
                    <div class="">
                        <input type="text" readonly name="convert_cash_total_dc"  data-val-regex="المدخل غير صحيح!" data-val-regex-pattern="<?= float_format_exp() ?>" data-val="true"  data-val-required="حقل مطلوب"  id="txt_convert_cash_total_dc" class="form-control ">
                        <span class="field-validation-valid" data-valmsg-for="convert_cash_total_dc" data-valmsg-replace="true"></span>
                    </div>
                </div>

                <!--  <div class="form-group col-sm-2">
                      <label class="control-label"> رقم سند إيداع البنك</label>
                      <div class="">
                          <input type="text" name="convert_cash_bank_id"   id="txt_convert_cash_bank_id" class="form-control ">
                           </div>
                  </div>-->

            </div>



            <div class="modal-footer">
                <?php if ($isCreate) : ?>
                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                    <button type="button" onclick="javascript:clear_form()"  class="btn btn-default"> تفريغ الحقول</button>
                    <!--  <button type="button" onclick="javascript:voucher_get();" class="btn btn-success"> إستعلام</button>-->
                <?php endif; ?>

                <?php if (isset($can_delete) && $can_delete) : ?>

                    <button type="button" onclick="javascript:delete_cash();" class="btn btn-danger">إلغاء السند</button>
                <?php endif; ?>



                <?php if ( HaveAccess($cancel_url) && isset($cash_data) && count($cash_data) > 0 && $cash_data[0]['CONVERT_CASH_CASE']>=2 ) : ?>

                    <button type="button" onclick="javascript:cancel_cash();" class="btn btn-danger">إلغاء السند</button>
                <?php endif; ?>


                <?php if (!$isCreate && $isAdopt) : ?>

                    <button type="button" onclick="javascript:adopt_cash();" class="btn btn-primary">إعتماد</button>
                <?php endif; ?>

            </div>

            <div id="voucher_credits">

            </div>

            <div id="checks_details">

            </div>

        </form>

    </div>

</div>

</div>
<?php

$rs=$isCreate ?array() : $cash_data[0];

$today = date('d/m/Y');

$scripts = <<<SCRIPT
<script>

    var account_id = 0;
    $(function () {

        $('#dp_telar_id').select2().on('change',function(){

            get_debit_account();
        });
        $('#dp_money_received_id').change(function(){
            dp_money_changed();
        });

        $('#txt_voucher_date').change(function(){
             get_sum();
        });

        $('input[type="text"],body').bind('keydown', 'f3', function() {
            voucher_get();
            return false;
        });


        $('input[type="text"],body').bind('keydown', 'f2', function() {
            clear_form();
            return false;
        });

         $('input[type="text"],body').bind('keydown', 'ctrl+s', function() {
             $('button[data-action="submit"]').click();
            return false;
        });

        $('#dp_convert_cash_type').change(function(){
            var  _income_type =  $('#dp_convert_cash_type').val();
                $('#txt_debit_total,#txt_debit_total_2').val('0');
            get_debit_account();
        });
        $('#txt_debit_account_id').combotree({
            onSelect:function(account) {

                account_id = account.id;

                get_sum();

                get_treasury_account(account.id);

            }
        });

        $('#txt_convert_cash_total').keyup(function(){

            var debit_sum =$('#txt_debit_total').val();
            var val =( $(this).val() !='')? parseFloat( $(this).val()) : 0;

            var selected = (debit_sum == val)?1:(debit_sum < val)?3:2;

            var _sub = debit_sum - val;
            _sub = _sub < 0? (_sub * -1) :_sub;

            $('#txt_convert_cash_total_dc').val(_sub);

            $('#dp_money_received_id').val(selected);


            dp_money_changed();

        });



    });

    function get_sum(){
     get_data('{$get_sum_url}',{id:account_id,usid:$('#dp_telar_id').val(),date:$('#txt_voucher_date').val()},function(data){

                    if(data.length > 0){
                        $('#txt_debit_total').val(data[0].SM2);
                            $('#txt_debit_total_2').val(data[0].SM1);
                    } else{
                        $('#txt_debit_total,#txt_debit_total_2').val('');

                    }
                });

     checks_list(account_id);
     vouchers_credits(account_id);
    }

    function clear_form(){
        clearForm($('#cash_from'));
        $('#dp_telar_id').select2({setVal:0});

        $('#txt_debit_account_id').combotree('loadData',[]);
        $('#txt_debit_account_id').combotree('clear');
        $('#txt_treasury_account_id').combotree('loadData',[]);
        $('#txt_treasury_account_id').combotree('clear');

        $('#txt_convert_cash_date').val('$today');
    }


    function get_debit_account(){

        $('#txt_debit_total').val('0');
        get_data('{$get_account_url}',{perfix:'$fund_prefix',type: $('#dp_convert_cash_type').val(),user:$('#dp_telar_id').val(),case:1},function(data){
            if(data.length > 0){
                $('#txt_debit_account_id').combotree('loadData',data);
                $('#txt_debit_account_id').combotree('setValue', data[0].id);
                $('#txt_debit_account_id').combotree('setText',data[0].text);
            } else{
                $('#txt_debit_account_id').combotree('loadData',[]);
                $('#txt_debit_account_id').combotree('clear');
            }
            //get_treasury_account();
        });


    }


    function get_treasury_account(id){
        var account_id = $('input[name="debit_account_id"]').val();
        account_id = (account_id == '')?'00':account_id;
        if(id !='')
         account_id= id;



        get_data('{$get_account_url}',{perfix:'$treasure_prefix',type: $('#dp_convert_cash_type').val(),account_id:account_id},function(data){
            if(data.length > 0){
                $('#txt_treasury_account_id').combotree('loadData',data);
                $('#txt_treasury_account_id').combotree('setValue', data[0].id);
                $('#txt_treasury_account_id').combotree('setText',data[0].text);
            } else{
                $('#txt_treasury_account_id').combotree('loadData',[]);
                $('#txt_treasury_account_id').combotree('clear');
            }
        });
    }

    function c_t_account(id){

    }

      function checks_list(id){
        get_data('{$checks_url}',{account_id:id,entry_user:$('#dp_telar_id').val(),date:$('#txt_voucher_date').val()},function(data){

           $('#checks_details').html(data);
        },'html');
    }

     function vouchers_credits(id){
        get_data('{$voucher_credits_url}',{debit_account_id:id,telar_id:$('#dp_telar_id').val(),voucher_date:$('#txt_voucher_date').val()},function(data){

           $('#voucher_credits').html(data);
        },'html');
    }



    $('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        if(confirm('هل تريد حفظ السند ؟!')){
            $(this).attr('disabled','disabled');
            var form = $(this).closest('form');
            ajax_insert_update(form,function(data){
                clear_form();
                success_msg('رسالة','تم حفظ البيانات بنجاح ..');
            },'html');
        }
        setTimeout(function() {
            $('button[data-action="submit"]').removeAttr('disabled');
        }, 5000);
    });

</script>
SCRIPT;

//print_r($rs);

$shared_script = <<<SCRIPT
<script>
        function dp_money_changed(){

        $('input[name="money_received_id"]').val($('#dp_money_received_id').val());

        if($('#dp_money_received_id').val() !=1){
            $('#convert_cash_total_account_id_div,#convert_cash_total_dc_div').slideDown();
            if($('#dp_money_received_id').val() ==2){
                $('#convert_cash_total_account_id_div .control-label').text('رقم حساب العجز');
                $('#convert_cash_total_dc_div .control-label').text('مجموع حساب العجز');
            }else{
                $('#convert_cash_total_account_id_div .control-label').text('رقم حساب الفائض');
                $('#convert_cash_total_dc_div .control-label').text('مجموع حساب الفائض');
            }

             if (typeof c_t_account == 'function') {
                   c_t_account($('#dp_money_received_id').val());
                }


        }
        else {
            $('#convert_cash_total_account_id_div,#convert_cash_total_dc_div').slideUp();

        }
    }
    </script>
SCRIPT;



if($isCreate)
    sec_scripts($shared_script.$scripts);
else{

    $delete_script = <<<SCRIPT
<script>
function delete_cash(){
if(confirm('هل تريد إلغاء السند؟!')){
        get_data('{$delete_url}',{convert_cash_id:{$rs['CONVERT_CASH_ID']}},function(data){
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

    $cancel_script=<<<SC
        <script>
function cancel_cash(){
if(confirm('هل تريد إلغاء السند؟!')){
        get_data('{$cancel_url}',{convert_cash_id:{$rs['CONVERT_CASH_ID']}},function(data){
            if(data =='1')
           success_msg('رسالة','تم إلغاء السند بنجاح ..');

                    setTimeout(function(){

                     window.location = '$back_url';

                }, 1000);

        },'html');
        }
}
</script>
SC;


    if(isset($can_delete) && $can_delete){
        $shared_script = $shared_script.$delete_script;
    }


    if(HaveAccess($cancel_url)){
        $shared_script = $shared_script.$cancel_script;
    }


    $edit_script = <<<SCRIPT
<script>

    $('#dp_convert_cash_type').val({$rs['CONVERT_CASH_TYPE']});
    $('#txt_telar_id').val('{$rs['TELAR_ID_NAME']}');
    $('#txt_convert_cash_total_account_id_name').val('{$rs['CONVERT_CASH_TOTAL_ACID_NAME']}');
    $('#txt_debit_account_id').val('{$rs['DEBIT_ACCOUNT_ID_NAME']}');
    $('#txt_treasury_account_id').val('{$rs['TREASURY_ACCOUNT_ID_NAME']}');

    $('#txt_convert_cash_id').val({$rs['CONVERT_CASH_ID']});
    $('#txt_convert_cash_hint').val('{$rs['CONVERT_CASH_HINT']}');
    $('#txt_convert_cash_total').val({$rs['CONVERT_CASH_TOTAL']});
    $('#dp_money_received_id').val({$rs['MONEY_RECEIVED_ID']});
    $('#txt_convert_cash_total_dc').val('{$rs['CONVERT_CASH_TOTAL_DC']}');
    $('#txt_voucher_date').val('{$rs['VOUCHER_DATE']}');
    $('#txt_convert_cash_date').val('{$rs['CONVERT_CASH_DATE']}');

    dp_money_changed();

    $('#cash_from input,#cash_from select').prop('disabled',true);

    function adopt_cash(){
        if(confirm('هل تريد اعتماد السند ؟!')){
        get_data('{$adopt_url}',{convert_cash_id:{$rs['CONVERT_CASH_ID']}},function(data){
            if(data =='1')
                success_msg('رسالة','تم إعتماد السند بنجاح ..');

                    setTimeout(function(){

                    window.location.reload();

                }, 1000);

        },'html');
        }
    }

</script>
SCRIPT;
    sec_scripts($shared_script.$edit_script);
}



?>



