<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 23/11/14
 * Time: 08:20 ص
 */

$get_service_url =base_url('treasury/income_voucher/get_service');
$get_account_url = base_url('financial/accounts_permission/public_get_user_accounts');

$select_accounts_url =base_url('financial/accounts/public_select_accounts');
$customer_url =base_url('payment/customers/public_index');
$project_accounts_url =base_url('projects/projects/public_select_project_accounts');

$back_url=base_url('treasury/income_voucher?type=main_general');
//$print_url =  base_url('/reports');
$print_url =   base_url('greport/reports/public_income_voucher/');
$get_id_url =base_url('financial/accounts/public_get_id');

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
            <form class="form-vertical" id="voucher_from" method="post" action="<?=base_url('treasury/income_voucher/main_general')?>" role="form" novalidate="novalidate">
                <div class="modal-body inline_form">


                    <div class="form-group col-sm-3">
                        <label class="control-label">  الزبون </label>
                        <div class="">
                            <input type="text"  data-val="true" name="cust_name" data-val-required="حقل مطلوب"   id="txt_supscriper_id_name" class="form-control ">

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
                          
                            <select  name="currency_id" id="dp_currency_id" class="form-control">

                                <?php foreach($currency as $row) :?>
                                    <option data-val="<?= $row['VAL'] ?>" value="<?= $row['CURR_ID'] ?>"><?= $row['CURR_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-sm-1">
                        <label class="control-label"> سعر العملة  </label>
                        <div class="">
                            <input type="text" name="curr_value"        data-val="true"  data-val-required="حقل مطلوب"  id="txt_curr_value" class="form-control ">
                            <span class="field-validation-valid" data-valmsg-for="curr_value" data-valmsg-replace="true"></span>
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">  نوع القبض </label>
                        <div class="">
                            <select name="income_type" id="dp_income_type" class="form-control">

                                <?php foreach($income_type as $row) :?>
                                    <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-sm-3">
                        <label class="control-label"> حساب الصندوق  </label>
                        <div class="">
                            <input type="text" name="debit_account_id"  data-val="true"  data-val-required="حقل مطلوب"  id="txt_debit_account_id" class="form-control  easyui-combotree" data-options="animate:true,lines:true,required:true">
                            <span class="field-validation-valid" data-valmsg-for="debit_account_id" data-valmsg-replace="true"></span>
                        </div>
                    </div>

                    <div class="form-group col-sm-12">
                        <label class="control-label"> البيان   </label>
                        <div class="">
                            <input type="text" name="hints"   data-val="true"  data-val-required="حقل مطلوب"  id="txt_hints" class="form-control ">
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
                    <?php echo modules::run('treasury/income_voucher/get_service',0,true,true); ?>



                </div>
                <div class="form-group col-sm-12">
                    <label class="control-label">الملاحظات</label>
                    <div class="">
                        <textarea type="text" name="notes" id="txt_notes" class="form-control "></textarea>
                        <span class="field-validation-valid" data-valmsg-for="notes" data-valmsg-replace="true"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                    <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>
                    <button type="button" onclick="javascript:voucher_get();" class="btn btn-success"> إستعلام</button>

                </div>
            </form>

        </div>

    </div>

</div>
<?php

$today = date('d/m/Y');
$current_user = get_curr_user()->id;
$scripts = <<<SCRIPT

<script>

  var count = 1;
     var count2 = 1;
    $(function () {

        $('#dp_service_type').select2().on('change',function(){
        });

        $('#txt_curr_value').val($(dp_currency_id).find(':selected').attr('data-val'));
        $('#dp_currency_id').change(function(){

            $('#txt_curr_value').val($(this).find(':selected').attr('data-val'));

        });


          $('#dp_income_type,#dp_currency_id').change(function(){
            var  _income_type =  $('#dp_income_type').val();
            if(_income_type !=1 && _income_type !=4){
                $('#for_checks').slideDown();
                $('#tbl_container2').slideDown();
                $('#accountTitle').text('حساب إيرادات تحت التحصيل ');
            }else{
                $('#for_checks').slideUp();
                $('#tbl_container2').slideUp();
                 $('#accountTitle').text('حساب الإيراد ');
            }
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


            reBind();

    });


    function clear_form(){
        clearForm($('#voucher_from'));
        get_debit_account();
        //get_service();

         $('#txt_curr_value').val($(dp_currency_id).find(':selected').attr('data-val'));

        $('#txt_voucher_date').val('$today');
    }
    function get_debit_account(){
        get_data('{$get_account_url}',{perfix:'$treasure_prefix',type: $('#dp_income_type').val(),curr_id: $('#dp_currency_id').val()},function(data){
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
		
		   if($('select[name="customer_account_type[]"]:visible').filter(function(i){ return $(this).val() == '';}).length > 0){
          
                alert('يجب ادخال نوع المستفيد   ');
                return;
          }
             
			 
        if(confirm('هل تريد حفظ السند ؟!')){
            var form = $(this).closest('form');
            ajax_insert_update(form,function(data){

               // clear_form();
                success_msg('رسالة','تم حفظ البيانات بنجاح ..');

                 //_showReport('$print_url?report=MAIN_VI&params[]='+data+'&params[]=$branch');
_showReport('$print_url/'+data);
   $('#report').on('hidden.bs.modal', function () {
                    reload_Page();
                });
            },'html');
        }
    });


    function addRow(){
        count = count+1;
        var aTypes = $('#dp_account_type_1').html();

        var html ='<tr><td>'+count+'</td><td> <select name="account_type[]" id="dp_account_type_'+count+'" class="form-control"> '+aTypes+' </select></td><td> <input type="hidden" name="fees_type[]" ><input type="number" class="form-control col-sm-3" data-val="true" data-val-required="حقل مطلوب"  name="credit_account_id[]" id="h_txt_credit_account_id_'+count+'" /><input type="text"   name="credit_account_id_name[]" readonly id="txt_credit_account_id_'+count+'"  class="form-control col-sm-8"/></td><td> <input type="text"  data-val="true" data-val-required="حقل مطلوب"    name="credit[]" id="txt_credit"  class="form-control "/></td><td> <a data-action="delete" href="javascript:;"><i class="icon icon-trash delete-action"></i> </a> </td></tr>';
        $('#voucher_detailsTbl tbody').append(html);

        reBind();
    }


    function addRow2(){
        count2 = count2+1;
       var aTypes = $('#voucher_details2Tbl #dp_account_type_1').html();
        var html ='<tr><td>'+count2+'</td><td> <select name="d2_account_type[]" id="dp_account_type_'+count+'" class="form-control"> '+aTypes+' </select></td><td>  <input type="number" class="form-control col-sm-3" data-val="true" data-val-required="حقل مطلوب" name="d2_account_id[]" id="h_txt_d2_account_id_'+count2+'" />  <input type="text"   name="d2_account_id_name[]" readonly id="txt_d2_account_id_'+count2+'"  class="form-control col-sm-8"/></td><td> <input type="text"  data-val="true" data-val-required="حقل مطلوب"    name="d2_credit[]" id="txt_d2_credit"  class="form-control "/></td><td><a data-action="delete" href="javascript:;"><i class="icon icon-trash delete-action"></i> </a> </td></tr>';
        $('#voucher_details2Tbl tbody').append(html);

        reBind();
    }


 function selectAccount(obj,prefix){
         var _type = $(obj).closest('tr').find('select').val();


            if(_type == 1 || $(obj).attr('name') == 'root_account_id_name[]' )
                _showReport('$select_accounts_url/'+$(obj).attr('id')+prefix );
            else if(_type == 2)
                _showReport('$customer_url/'+$(obj).attr('id')+'/1');
            else if(_type == 3)
                _showReport('$project_accounts_url/'+$(obj).attr('id')+'/2');
    }


    function reBind(){
           $('input[name="credit_account_id_name[]"],input[name="root_account_id_name[]"],input[name="d2_account_id_name[]"]').click(function(e){
                 selectAccount($(this),'/-1/'+Account_Prefix());

        });

         $('input[name="d2_account_id_name[]"]').click(function(e){
                  selectAccount($(this).closest('tr').find('input[name$="_name[]"]'),'/-1/4|2');
        });


          $('input[name="credit_account_id[]"]').keyup(function(){
            get_account_name($(this));
        });
        $('input[name="credit_account_id[]"]').change(function(){
            get_account_name($(this));
        });

    $('input[name="credit[]"]').keyup(function(){
               sumOfValues();
        });
           delete_action();


        $('select[name="account_type[]"]').change(function(){
            if($(this).val() == 2 ){
                $('select[name="customer_account_type[]"]',$(this).closest('tr')).show();
            } else {
                $('select[name="customer_account_type[]"]',$(this).closest('tr')).hide();
            }
        });
    }

function sumOfValues(){
        var sumD= 0 ;
        $('input[name="credit[]"]').each(function(i) {



            sumD += Number($(this).val());
        });



        $('#total').text(sumD.toFixed(2));


    }

    function Account_Prefix(){
        if($('#dp_income_type').val() == 2)
            return '{$collection_prefix}?user={$current_user}';
         else  return '1|2|3|4|5';
    }

      function get_account_name(obj){
            $(obj).closest('tr').find('input[name$="_name[]"]').val('');

           if($(obj).val().length >6 ){
             get_data('{$get_id_url}',{id:$(obj).val()},function(data){

                if(data.length > 0){

                    $(obj).closest('tr').find('input[name$="_name[]"]').val(data[0].ACOUNT_NAME);
                }
            });
            }
    }



</script>

SCRIPT;

sec_scripts($scripts);



?>
