<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 15/10/14
 * Time: 09:11 ص
 */


$get_account_url = base_url('financial/accounts_permission/public_get_user_accounts');
$get_details_account_url  = base_url('settings/constant_details/public_get_id_com');
$back_url= !HaveAccess(base_url('treasury/checks_processing/adopt')) && get_curr_user()->branch != 1 ? base_url('payment/checks_portfolio/index?type=1') : base_url('treasury/checks_processing');
$adopt_url =base_url('treasury/checks_processing/adopt');
$public_get_bank_account_id_url =base_url('treasury/convert_cash_bank/public_get_bank_account_id');
$isCreate =isset($checks_data)?false:true;


$data =isset($data)?$data:'';


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
            <form class="form-vertical" id="cash_bank_from" action="<?= base_url('treasury/checks_processing/'.((isset($can_edit) && $can_edit)?'edit':'create')) ?>" method="post"  role="form" novalidate="novalidate">
                <div class="modal-body inline_form">
                    <div class="form-group col-sm-2">
                        <label class="control-label">  نوع سند الإيداع </label>
                        <div class="">
                            <input type="hidden" name="check_portfolio">
                            <select name="checks_processing_type" id="dp_checks_processing_type" class="form-control">
                                <option></option>
                                <?php foreach($checks_processing_type as $row) :?>
                                    <option data-account="<?= $row['ACCOUNT_ID'] ?>" value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-sm-1">
                        <label class="control-label">رقم السند </label>
                        <div class="">
                            <input type="text" readonly name="checks_processing_id" id="txt_checks_processing_id" class="form-control ">


                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label"> تاريخ  السند </label>
                        <div class="">
                            <input type="text" name="convert_cash_bank_date"  data-type="date" data-date-format="DD/MM/YYYY" value="<?= date('d/m/Y') ?>" id="txt_convert_cash_bank_date" class="form-control ">
                        </div>
                    </div>

                    <div class="form-group col-sm-1">
                        <label class="control-label">رقم فيشة الإيداع</label>
                        <div class="">
                            <input type="text"  name="bank_cash_no" id="txt_bank_cash_no"   class="form-control ">
                            <span class="field-validation-valid" data-valmsg-for="bank_cash_no" data-valmsg-replace="true"></span>
                        </div>
                    </div>

                    <div class="form-group col-sm-2" style="padding-top: 42px;padding-right: 30px;">

                        <div class="">
                            <a id="source_url" class="btn-xs btn-success" target="_blank"  > عرض المصدر</a>
                        </div>
                    </div>

                    <hr/>
                    <div class="form-group col-sm-2">
                        <label class="control-label">  البنك  </label>
                        <div class="">
                            <select name="bank_id" id="dp_bank_id" class="form-control">

                                <?php foreach($banks as $row) :?>
                                    <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-sm-1">
                        <label class="control-label">رقم الشيك</label>
                        <div class="">

                            <input type="text" name="check_id" readonly data-val-regex="المدخل غير صحيح!" data-val-regex-pattern="\d{1,}" data-val="true"  data-val-required="حقل مطلوب"   id="txt_check_id" class="form-control ">
                            <span class="field-validation-valid" data-valmsg-for="check_id" data-valmsg-replace="true"></span>
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">تاريخ حركة الشيك</label>
                        <div class="">

                            <input type="text" readonly name="convert_cash_bank_one" data-type="date" data-date-format="DD/MM/YYYY"   data-val-regex="المدخل غير صحيح!" data-val-regex-pattern="<?= date_format_exp() ?>" data-val="true"  data-val-required="حقل مطلوب"   id="txt_convert_cash_bank_one" class="form-control ">
                            <span class="field-validation-valid" data-valmsg-for="convert_cash_bank_one" data-valmsg-replace="true"></span>

                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label"> تاريخ حق الشيك </label>
                        <div class="">
                            <input type="text" name="convert_cash_true_date"  data-type="date" data-date-format="DD/MM/YYYY" value="<?= date('d/m/Y') ?>" id="txt_convert_cash_true_date" class="form-control ">
                        </div>
                    </div>

                    <div class="form-group col-sm-1">
                        <label class="control-label">قيمة الشيك</label>
                        <div class="">
                            <input type="text" name="check_value" readonly  data-val-regex="المدخل غير صحيح " data-val-regex-pattern="<?= float_format_exp() ?>" data-val="true"  data-val-required="حقل مطلوب"  id="txt_check_value" class="form-control ">
                            <span class="field-validation-valid" data-valmsg-for="check_value" data-valmsg-replace="true"></span>
                        </div>
                    </div>

                    <div class="form-group col-sm-1">
                        <label class="control-label"> العملة</label>
                        <div class="">
                            <input type="text"   readonly   id="txt_check_currency_name" class="form-control ">
                            <input type="hidden"  name="check_currency" class="form-control ">
                        </div>
                    </div>


                    <div class="form-group col-sm-2">
                        <label class="control-label"> المقبوضات و المصروفات</label>
                        <div class="">


                            <select name="bk_fin_id"
                                    id="dp_bk_fin_id"
                                    data-val-required="required"
                                    data-val="true"
                                    class="form-control">
                                <option></option>
                                <?php foreach($BkFin as $_row) :?>
                                    <option   value="<?= $_row['ID'] ?>"><?= str_repeat('-' , strlen($_row['ID'])) . ' '. $_row['TITLE'] ?></option>
                                <?php endforeach; ?>
                            </select>

                        </div>
                    </div>




                    <hr>
                    <div class="form-group col-sm-4">
                        <label class="control-label"> حساب الدائن</label>
                        <div class="">
                            <input type="text" readonly name="checks_processing_credit" id="txt_checks_processing_credit" class="form-control   <?= $isCreate?'easyui-combotree':'' ?>" data-options="animate:true,lines:true,required:true">
                            <span class="field-validation-valid" data-valmsg-for="checks_processing_credit" data-valmsg-replace="true"></span>

                        </div>
                    </div>


                    <div class="form-group col-sm-4">
                        <label class="control-label"> حساب المدين  </label>
                        <div class="">
                            <input type="text" name="checks_processing_debit" data-val="true"  data-val-required="حقل مطلوب"  id="txt_checks_processing_debit"  class="form-control easyui-combotree" data-options=" animate:true,lines:true,required:true">
                            <span class="field-validation-valid" data-valmsg-for="checks_processing_debit" data-valmsg-replace="true"></span>

                        </div>
                    </div>




                    <div class="form-group col-sm-12">
                        <label class="control-label"> البيان   </label>
                        <div class="">
                            <input type="text" name="checks_processing_hints"  data-val="true"  data-val-required="حقل مطلوب"  id="txt_checks_processing_hints" class="form-control ">
                            <span class="field-validation-valid" data-valmsg-for="checks_processing_hints" data-valmsg-replace="true"></span>
                        </div>
                    </div>






                </div>



                <div class="modal-footer">
                    <?php if ($isCreate ||  (isset($can_edit) && $can_edit)) : ?>
                        <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                    <?php endif; ?>

                    <?php if (!$isCreate && HaveAccess($adopt_url) &&(isset($checks_data) && $checks_data[0]['CHECKS_PROCESSING_CASE'] <=1)) : ?>

                        <button type="button" onclick="javascript:adopt_cash();" class="btn btn-primary">إعتماد</button>
                    <?php endif; ?>

                </div>
            </form>

        </div>

    </div>

</div>
<?php


$today = date('d/m/Y');

$seq_id = isset($seq_id) ?$seq_id : '';

$create = base_url('treasury/checks_processing/create');
$edit = base_url('treasury/checks_processing/edit');
$source_url = base_url('treasury/income_voucher/get/');
$shared_script =<<<SCRIPT

    function get_checks_processing_debit(){

        get_data('{$get_account_url}',{perfix: $('#dp_checks_processing_type').find(':selected').attr('data-account'),curr_id:$('input[name="check_currency"]').val()},function(data){

            if(data.length > 0 && data[0].id != null){

                $('#txt_checks_processing_debit').combotree('loadData',data);

                if(checks_processing_debit ==''){
                    $('#txt_checks_processing_debit').combotree('setValue', data[0].id);
                    $('#txt_checks_processing_debit').combotree('setText',data[0].text);
                }else{
                    $('#txt_checks_processing_debit').combotree('setValue', checks_processing_debit);
                    $('#txt_checks_processing_debit').combotree('setText',checks_processing_debit_name);
                }



            } else{
             if(checks_processing_debit ==''){
                $('#txt_checks_processing_debit').combotree('loadData',[]);
                $('#txt_checks_processing_debit').combotree('clear');
             }
            }
        });
        //get_account_id();
    }


    function get_account_id(){

        get_data('{$public_get_bank_account_id_url}',{id:seq_id},function(data){

            if(data.length > 0){
                $('#txt_checks_processing_credit').combotree('loadData',data);
                $('#txt_checks_processing_credit').combotree('setText',data[0].text);
                $('#txt_checks_processing_credit').combotree('setValue', data[0].id);


            } else{
                $('#txt_checks_processing_credit').combotree('loadData',[]);
                $('#txt_checks_processing_credit').combotree('clear');


              resetCheck();
            }
        });
    }

     $('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        var form = $(this).closest('form');
        ajax_insert_update(form,function(data){
    if (typeof clear_form == 'function') {
            clear_form();

            }
            success_msg('رسالة','تم حفظ البيانات بنجاح ..');

               setTimeout(function(){

                      window.location = '{$back_url}';

                }, 1000);

        },'html');
    });

SCRIPT;

$scripts = <<<SCRIPT
<script>

    var seq_id = {$seq_id};
    var check_data = $.parseJSON('{$data}');

     var checks_processing_debit='';
    var checks_processing_debit_name='';


    $(function () {
        $('input[type="text"],body').bind('keydown', 'f3', function() {
            get_account_id();
            return false;
        });

        $('#dp_checks_processing_type').change(function(){
            get_checks_processing_debit();

        });
        setCheckInfo();
    });

    function setCheckInfo(){

        if(check_data.length > 0){
                $('input[name="check_currency"]').val(check_data[0].CURR_ID);
                if(check_data[0].CP_ID <= 0)
                {
                        $('#cash_bank_from').attr('action','{$create}');
                        $('#dp_checks_processing_type option[value!="1"]').remove();

                        $('#txt_checks_processing_credit').combotree('loadData',check_data);
                        $('#txt_checks_processing_credit').combotree('setText',check_data[0].text);
                        $('#txt_checks_processing_credit').combotree('setValue', check_data[0].id);

                        get_checks_processing_debit();
                }else{

                    if(check_data[0].CHECKS_PROCESSING_TYPE !=4)
                           if(check_data[0].CHECKS_PROCESSING_TYPE == -1)
                             $('#dp_checks_processing_type option[value!="1"]').remove();
                          else
                             $('#dp_checks_processing_type option[value!="'+(parseInt(check_data[0].CHECKS_PROCESSING_TYPE)+1)+'"]').remove();
                     else
                         $('#dp_checks_processing_type option[value="1"]').remove();

                   if(!isNaN(parseInt(check_data[0].BANK_CASH_NO))){
                         //$('#txt_bank_cash_no').prop('readonly',true);
                         $('#dp_bank_id').prop('disabled',true);
                    }else{
                         //$('#txt_bank_cash_no').prop('readonly',false);
                         $('#dp_bank_id').prop('disabled',false);
                    }

                    get_checks_processing_debit();

                   if((check_data[0].CHECKS_PROCESSING_ID >= 1 ) )
                      $('#cash_bank_from').attr('action','{$edit}/case');
                   else
                      $('#cash_bank_from').attr('action','{$create}/case');


                        $('#txt_checks_processing_credit').combotree('loadData',check_data);
                        $('#txt_checks_processing_credit').combotree('setText',check_data[0].text);
                        $('#txt_checks_processing_credit').combotree('setValue', check_data[0].id);

                }
          setCheckData(check_data[0]);

          $('input[name="check_portfolio"]').val(check_data[0].SEQ);
        }
         //get_account_id();

    }





    function setCheckData(data){

                $('#txt_convert_cash_bank_one').val(data.CHECK_DATE);
                $('#txt_check_id').val(data.CHECK_ID);
                $('#txt_check_value').val(data.CRIDET);
                $('#dp_bank_id').val(data.CHECK_BANK_ID);
                $('#txt_check_currency_name').val(data.CURR_ID_NAME);
                $('input[name="check_currency"]').val(data.CURR_ID);
                $('#txt_checks_processing_id').val(data.CHECKS_PROCESSING_ID);
                $('#txt_bank_cash_no').val(data.BANK_CASH_NO);
               
                 

                $('#txt_checks_processing_hints').val($('#dp_checks_processing_payment_type').text().trim()+' '+data.CHECK_ID);
                if(data.SOURCE_ID > 0)
                    $('#source_url').attr('href','{$source_url}/'+data.SOURCE_ID);
                else  $('#source_url').hide();
    }

    function resetCheck(){
                $('#txt_convert_cash_bank_one').val('');
                $('#txt_check_id').val('');
                $('#dp_bank_id').val('');
                $('#txt_check_value').val('');
                $('#txt_check_currency_name').val('');
                $('input[name="check_currency"]').val('');
    }

        {$shared_script}

    function clear_form(){
        clearForm($('#cash_bank_from'));
        $('#txt_checks_processing_date').val('$today');

        $('#txt_checks_processing_credit').combotree('loadData',[]);
        $('#txt_checks_processing_credit').combotree('clear');
        $('#txt_checks_processing_debit').combotree('loadData',[]);
        $('#txt_checks_processing_debit').combotree('clear');
    }


</script>
SCRIPT;

$rs=$isCreate ?array() : $checks_data[0];

if($isCreate)
    sec_scripts($scripts);
else{
    $edit_script = <<<SCRIPT
<script>

    var checks_processing_debit='';
    var checks_processing_debit_name='';

    $('#dp_checks_processing_type').val({$rs['CHECKS_PROCESSING_TYPE']});

    $('#txt_checks_processing_id').val({$rs['CHECKS_PROCESSING_ID']});
    $('#txt_convert_cash_bank_date').val('{$rs['CONVERT_CASH_BANK_DATE']}');
    $('#txt_convert_cash_bank_one').val('{$rs['CONVERT_CASH_BANK_ONE']}');
    $('#txt_check_id').val('{$rs['CHECK_ID']}');
    $('#txt_check_value').val('{$rs['CHECK_VALUE']}');
    $('#txt_checks_processing_hints').val('{$rs['CHECKS_PROCESSING_HINTS']}');
    $('#txt_check_currency_name').val('{$rs['CHECK_CURRENCY_NAME']}');
    $('#txt_checks_processing_debit').val('{$rs['CHECKS_PROCESSING_DEBIT_NAME']}');
    $('#txt_checks_processing_credit').val('{$rs['CHECKS_PROCESSING_CREDIT_NAME']}');
    $('#txt_bank_cash_no').val('{$rs['BANK_CASH_NO']}');
    $('#dp_bank_id').val('{$rs['BANK_ID']}');
    $('#txt_convert_cash_true_date').val('{$rs['CONVERT_CASH_TRUE_DATE']}');
    $('#dp_bk_fin_id').val('{$rs['BK_FIN_ID']}');
 
    checks_processing_debit ='{$rs['CHECKS_PROCESSING_DEBIT']}';
    checks_processing_debit_name ='{$rs['CHECKS_PROCESSING_DEBIT_NAME']}';

    if({$rs['CHECKS_PROCESSING_CASE']} > 1)
    {
        $('#cash_bank_from input,#cash_bank_from select').prop('disabled',true);

    }
    $('#cash_bank_from select').not('#dp_bk_fin_id').prop('disabled',true);
    if({$rs['CHECKS_PROCESSING_TYPE']} > 1){

      //$('#txt_bank_cash_no').prop('readonly',true);
    }

    function adopt_cash(){
    if(confirm('هل تريد إعتماد السند ؟!')){
        get_data('{$adopt_url}',{checks_processing_id:{$rs['CHECKS_PROCESSING_ID']}},function(data){
            if(data =='1')
                success_msg('رسالة','تم إعتماد السند بنجاح ..');


               setTimeout(function(){

                     window.location = '{$back_url}';

                }, 1000);
        },'html');

        }
    }

 {$shared_script}

 get_checks_processing_debit();

</script>

SCRIPT;
    sec_scripts($edit_script);
}


?>


