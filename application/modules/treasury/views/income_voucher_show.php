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
$back_url=base_url('treasury/income_voucher?type=service');
$print_url =  base_url('greport/reports/public_income_voucher/');



$isCreate =isset($income_voucher)?false:true;

$rs=($isCreate || count($income_voucher) <= 0)?array() : $income_voucher[0];

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

        <?php if(count($rs) > 0 && $rs['VOUCHER_CASE'] == 0):?>
            <span class="canceled">
السند ملغي
                </span>
        <?php endif; ?>

        <div id="msg_container"></div>
        <div id="container">
            <form class="form-vertical" id="voucher_from" method="post" action="<?=base_url('treasury/income_voucher/create')?>" role="form" novalidate="novalidate">
                <div class="modal-body inline_form">
                    <div class="form-group col-sm-2">
                        <label class="control-label">رقم سند الخدمة</label>
                        <div class="">
                            <input type="text" name="service_id"  data-val-regex="المدخل غير صحيح!" data-val-regex-pattern="\d{1,}" data-val="true"  data-val-required="حقل مطلوب"  id="txt_service_id" class="form-control ">
                            <span class="field-validation-valid" data-valmsg-for="service_id" data-valmsg-replace="true"></span>

                        </div>
                    </div>
                    <div class="form-group col-sm-2">
                        <label class="control-label">  نوع الخدمة </label>
                        <div class="">
                            <input type="text" readonly name="server_type_name" id="txt_server_type_name"  data-val="true"  data-val-required="حقل مطلوب" class="form-control "/>
                            <input type="hidden" name="server_type" id="txt_server_type" />

                        </div>
                    </div>


                    <div class="form-group col-sm-2">
                        <label class="control-label"> رقم الإشتراك</label>
                        <div class="">
                            <input type="text" name="supscriper_id" data-val="true"  data-val-required="حقل مطلوب" readonly id="txt_supscriper_id" class="form-control ">

                        </div>
                    </div>

                    <div class="form-group col-sm-3">
                        <label class="control-label"> اسم المشترك </label>
                        <div class="">
                            <input type="text"  name="cust_name"  data-val="true"  data-val-required="حقل مطلوب" readonly id="txt_supscriper_id_name" class="form-control ">

                        </div>
                    </div>
                    <hr/>
                    <div class="form-group col-sm-2">
                        <label class="control-label"> رقم الإيصال </label>
                        <div class="">
                            <input type="text" name="voucher_id" readonly  id="txt_voucher_id" class="form-control ">
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label"> تاريخ الإيصال </label>
                        <div class="">
                            <input type="text" name="voucher_date" readonly  value="<?= date('d/m/Y') ?>"  data-date-format="DD/MM/YYYY"   data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?= date_format_exp() ?>" data-val="true"  data-val-required="حقل مطلوب"  id="txt_voucher_date" class="form-control ">
                            <span class="field-validation-valid" data-valmsg-for="voucher_date" data-valmsg-replace="true"></span>
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">  العملة  </label>
                        <div class="">
                            <input type="hidden" name="currency_id"/>
                            <select disabled  id="dp_currency_id" class="form-control">

                                <?php foreach($currency as $row) :?>
                                    <option data-val="<?= $row['VAL'] ?>" value="<?= $row['CURR_ID'] ?>"><?= $row['CURR_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-sm-1">
                        <label class="control-label"> سعر العملة  </label>
                        <div class="">
                            <input type="text" name="curr_value" readonly       data-val="true"  data-val-required="حقل مطلوب"  id="txt_curr_value" class="form-control ">
                            <span class="field-validation-valid" data-valmsg-for="curr_value" data-valmsg-replace="true"></span>
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">  نوع القبض </label>
                        <div class="">
                            <select name="income_type" id="dp_income_type" class="form-control">

                                <?php  foreach($income_type as $row) : ?>
                                    <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-sm-3">
                        <label class="control-label"> حساب الصندوق  </label>
                        <div class="">
                            <input type="text" name="debit_account_id"  data-val="true"  data-val-required="حقل مطلوب"  id="txt_debit_account_id" class="form-control  <?= $isCreate?'easyui-combotree':'' ?>" data-options="animate:true,lines:true,required:true">
                            <span class="field-validation-valid" data-valmsg-for="debit_account_id" data-valmsg-replace="true"></span>
                        </div>
                    </div>

                    <div class="form-group col-sm-12">
                        <label class="control-label"> البيان   </label>
                        <div class="">
                            <input type="text" name="hints" value="إيصال قبض خدمة"  data-val="true"  data-val-required="حقل مطلوب"  id="txt_hints" class="form-control ">
                            <span class="field-validation-valid" data-valmsg-for="hints" data-valmsg-replace="true"></span>
                        </div>
                    </div>



                    <hr/>
                    <div id="for_checks" style="display: none;">
                        <div class="form-group col-sm-2">
                            <label class="control-label"> رقم الشيك   </label>
                            <div class="">
                                <input type="text" name="check_id"  data-val-regex="المدخل غير صحيح" data-val-regex-pattern="\d{6,}" data-val="true"  data-val-required="حقل مطلوب"  id="txt_check_id" class="form-control ">
                                <span class="field-validation-valid" data-valmsg-for="check_id" data-valmsg-replace="true"></span>
                            </div>
                        </div>

                        <div class="form-group col-sm-3">
                            <label class="control-label">اسم صاحب الشيك</label>
                            <div class="">
                                <input type="text" name="check_customer"  data-val="true"  data-val-required="حقل مطلوب"  id="txt_check_customer" class="form-control ">
                                <span class="field-validation-valid" data-valmsg-for="check_customer" data-valmsg-replace="true"></span>
                            </div>
                        </div>
                        <div class="form-group col-sm-2">
                            <label class="control-label">  البنك  </label>
                            <div class="">
                                <select name="check_bank_id" id="dp_check_bank_id" class="form-control">

                                    <?php foreach($banks as $row) :?>
                                        <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-sm-2">
                            <label class="control-label"> تاريخ الإستحقاق</label>
                            <div class="">
                                <input type="text" name="check_date"  data-type="date"  data-date-format="DD/MM/YYYY"  data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?= date_format_exp() ?>" data-val="true"  data-val-required="حقل مطلوب"  id="txt_check_date" class="form-control ">
                                <span class="field-validation-valid" data-valmsg-for="check_date" data-valmsg-replace="true"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="voucher_details">
                    <?php if (!$isCreate) : ?>
                        <?php echo modules::run('treasury/income_voucher/public_get_details',(count($rs))?$rs['VOUCHER_ID']:0); ?>
                    <?php endif; ?>

                </div>

                <?php if(count($rs) > 0 && $rs['VOUCHER_TYPE'] == 6) : ?>
                    <div class="form-group col-sm-12">
                        <label class="control-label">الملاحظات</label>
                        <div class="">
                            <textarea type="text" name="notes" id="txt_notes" class="form-control "></textarea>
                            <span class="field-validation-valid" data-valmsg-for="notes" data-valmsg-replace="true"></span>
                        </div>
                    </div>
                <?php endif ?>

                <?php if ($isCreate) : ?>
                    <div class="modal-footer">
                        <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                        <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>
                        <button type="button" onclick="javascript:voucher_get();" class="btn btn-success"> إستعلام</button>

                    </div>
                <?php endif; ?>
            </form>

        </div>

    </div>

</div>
<?php

$today = date('d/m/Y');

$rs = preg_replace("/(\r\n|\n|\r)/", "\\n", $rs);

$scripts = <<<SCRIPT

<script>
    $(function () {

        
        if ('{$request_app_serial}' != 0){
            $('#txt_service_id').val('{$request_app_serial}');
            voucher_get();
        }
        
        $('#dp_service_type').select2().on('change',function(){
        });

        $('#txt_curr_value').val($(dp_currency_id).find(':selected').attr('data-val'));
        $('#dp_currency_id').change(function(){

            $('#txt_curr_value').val($(this).find(':selected').attr('data-val'));

        });

        $('#dp_income_type,#dp_currency_id').change(function(){
            var  _income_type =  $('#dp_income_type').val();
            if(_income_type !=1)
                $('#for_checks').slideDown();
            else  $('#for_checks').slideUp();
            get_debit_account();
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
        get_debit_account();

    });


    function clear_form(){
        clearForm($('#voucher_from'));
        get_debit_account();
        get_service();

         $('#txt_curr_value').val($(dp_currency_id).find(':selected').attr('data-val'));
        $('#txt_hints').val('إيصال قبض خدمة');
        $('#txt_voucher_date').val('$today');
    }
    function get_debit_account(){
        get_data('{$get_account_url}',{perfix:'$fund_prefix',type: $('#dp_income_type').val(),curr_id: $('#dp_currency_id').val()},function(data){
            if(data.length > 0){
                $('#txt_debit_account_id').combotree('loadData',data);
                $('#txt_debit_account_id').combotree('setValue', data[0].id);
                $('#txt_debit_account_id').combotree('setText',data[0].text);
            } else{
                $('#txt_debit_account_id').combotree('loadData',[]);
                $('#txt_debit_account_id').combotree('clear');
            }
        });
    }

    function voucher_get(){
        if($('input[name="voucher_id"]').val()=='' && $('input[name="service_id"]').val()!=''){
            get_service();
        }
    }
    function get_service(){
        get_data('{$get_service_url}',{id: $('input[name="service_id"]').val()},function(data){
            $('#voucher_details').html(data);
        },'html');

    }
    $('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        if(confirm('هل تريد حفظ السند ؟!')){
            var form = $(this).closest('form');
            ajax_insert_update(form,function(data){

                clear_form();
                success_msg('رسالة','تم حفظ البيانات بنجاح ..');

                 //_showReport('$print_url?report=INCOME_VOUCHER&params[]='+data+'&params[]=$branch');
_showReport('$print_url/'+data);
            },'html');
        }
    });


</script>

SCRIPT;




if($isCreate)
    sec_scripts($scripts);
else if(count($rs) > 0){
    $edit_script = <<<SCRIPT
<script>

    $('#txt_service_id').val('{$rs['SERVICE_ID']}');
    $('#txt_server_type_name').val('{$rs['SERVICE_TYPE_NAME']}');
    $('#txt_supscriper_id').val('{$rs['SUPSCRIPER_ID']}');
    $('#txt_supscriper_id_name').val('{$rs['CUST_NAME']}');
    $('#txt_voucher_id').val('{$rs['VOUCHER_ID']}');
    $('#txt_voucher_date').val('{$rs['VOUCHER_DATE']}');
    $('#dp_currency_id').val('{$rs['CURRENCY_ID']}');
    $('#txt_curr_value').val('{$rs['CURR_VALUE']}');
    $('#txt_curr_value').val('{$rs['CURR_VALUE']}');
    $('#txt_debit_account_id').val('{$rs['DEBIT_ACCOUNT_ID_NAME']}');
    $('#txt_hints').val('{$rs['HINTS']}');
    $('#txt_notes').val('{$rs['NOTES']}');
    $('#dp_income_type').val('{$rs['INCOME_TYPE']}');

      $('#txt_check_id').val('{$rs['CHECK_ID']}');
              $('#txt_check_customer').val('{$rs['CHECK_CUSTOMER']}');
          $('#dp_check_bank_id').val('{$rs['CHECK_BANK_ID']}');
            $('#txt_check_date').val('{$rs['CHECK_DATE']}');

    $('#voucher_from input,#voucher_from select,#voucher_from textarea').prop('disabled',true);

    if({$rs['VOUCHER_TYPE']} != 1){
        $('#txt_service_id,#txt_server_type_name,#txt_supscriper_id').closest('.form-group').hide();
    }

            if($('#dp_income_type').val() !=1)
                $('#for_checks').slideDown();
            else  $('#for_checks').slideUp();

</script>
SCRIPT;
    sec_scripts($edit_script);
}
else{

    $empty_script = <<<SCRIPT
    <script>

$('#container').html('<div class="alert alert-danger"><strong>تحذير</strong> : لا يوجد بيانات ...</div>');

    </script>

SCRIPT;
    sec_scripts($empty_script);
}




?>
