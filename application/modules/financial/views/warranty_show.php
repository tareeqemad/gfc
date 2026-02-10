<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 06/11/14
 * Time: 09:09 ص
 */

$back_url = base_url('financial/warranty');
$get_id_url = base_url('financial/accounts/public_get_id');
$get_account_url = base_url('financial/accounts_permission/public_get_user_accounts');
if (!isset($result))
    $result = array();

$HaveRs = count($result) > 0;

$rs = $HaveRs ? $result[0] : array();

$isCreate = !$HaveRs;

?>
<?= AntiForgeryToken() ?>
    <div class="row">
        <div class="toolbar">

            <div class="caption"><?= $title ?></div>

            <ul>
                <?php if (HaveAccess($back_url)): ?>
                    <li><a href="<?= $back_url ?>"><i class="icon icon-reply"></i> </a> </li><?php endif; ?>
                <li><a onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a>
                </li>
            </ul>

        </div>
    </div>
    <div class="form-body">

        <div id="msg_container"></div>
        <div id="container">
            <form class="form-vertical" id="warrant_from" method="post"
                  action="<?= base_url('financial/warranty/' . $action) ?>" role="form" novalidate="novalidate">
                <div class="modal-body inline_form">

                    <div class="form-group col-sm-1">
                        <label class="control-label"> رقم السند </label>
                        <div class="">
                            <input type="text" name="bail_id" readonly value="<?= $HaveRs ? $rs['BAIL_ID'] : '' ?>"
                                   id="txt_bail_id" class="form-control ">

                        </div>
                    </div>

                    <div class="form-group col-sm-1">
                        <label class="control-label">تاريخ السند</label>
                        <div class="">
                            <input type="text" name="bail_date" value="<?= $HaveRs ? $rs['BAIL_DATE'] : '' ?>"
                                   data-date-format="DD/MM/YYYY" data-type="date" data-val-regex="المدخل غير صحيح!"
                                   data-val-regex-pattern="<?= date_format_exp() ?>" data-val="true"
                                   data-val-required="حقل مطلوب" id="txt_bail_date" class="form-control ">
                            <span class="field-validation-valid" data-valmsg-for="bail_date"
                                  data-valmsg-replace="true"></span>
                        </div>
                    </div>

                    <div class="form-group col-sm-1">
                        <label class="control-label">معاملة البنك</label>
                        <div class="">
                            <input type="text" name="bail_bank_id" data-val="true"
                                   value="<?= $HaveRs ? $rs['BAIL_BANK_ID'] : '' ?>" data-val-required="حقل مطلوب"
                                   id="txt_bail_bank_id" class="form-control ">
                            <span class="field-validation-valid" data-valmsg-for="bail_bank_id"
                                  data-valmsg-replace="true"></span>
                        </div>
                    </div>

                    <div class="form-group col-sm-1">
                        <label class="control-label"> تاريخ الكفالة</label>
                        <div class="">
                            <input type="text" name="bail_bank_date" data-date-format="DD/MM/YYYY" data-type="date"
                                   data-val-regex="المدخل غير صحيح!" data-val-regex-pattern="<?= date_format_exp() ?>"
                                   data-val="true" value="<?= $HaveRs ? $rs['BAIL_BANK_DATE'] : '' ?>"
                                   data-val-required="حقل مطلوب" id="txt_bail_bank_date" class="form-control ">
                            <span class="field-validation-valid" data-valmsg-for="bail_bank_date"
                                  data-valmsg-replace="true"></span>
                        </div>
                    </div>
                    <div class="form-group col-sm-1">
                        <label class="control-label"> تاريخ نهاية الكفالة</label>
                        <div class="">
                            <input type="text" name="check_date" <?= $HaveRs ? 'readonly' : '' ?>
                                   value="<?= $HaveRs ? $rs['CHECK_DATE'] : '' ?>" data-type="date"
                                   data-date-format="DD/MM/YYYY" data-val-regex="التاريخ غير صحيح!"
                                   data-val-regex-pattern="<?= date_format_exp() ?>" data-val="true"
                                   data-val-required="حقل مطلوب" id="txt_check_date" class="form-control ">
                            <span class="field-validation-valid" data-valmsg-for="check_date"
                                  data-valmsg-replace="true"></span>
                        </div>
                    </div>

                    <div class="form-group col-sm-1">
                        <label class="control-label"> نوع الكفالة </label>
                        <div class="">
                            <select name="bail_type2" id="dp_bail_type_id" class="form-control">
                                <?php foreach ($bail_type as $row): ?>
                                    <option <?= $HaveRs ? (intval($rs['BAIL_TYPE2']) == intval($row['CON_NO']) ? 'selected' : '') : '' ?>
                                            value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-sm-1">
                        <label class="control-label"> العملة </label>
                        <div class="">

                            <select name="currency_id" id="dp_currency_id" data-curr="false" class="form-control">
                                <?php foreach ($currency as $row) : ?>

                                    <option <?= $HaveRs ? (intval($rs['CURRENCY_ID']) == intval($row['CURR_ID']) ? 'selected' : '') : '' ?>
                                            data-val="<?= $row['VAL'] ?>"
                                            value="<?= $row['CURR_ID'] ?>"><?= $row['CURR_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-sm-1">
                        <label class="control-label"> سعر العملة</label>
                        <div class="">
                            <input type="text" name="curr_value" readonly data-val-regex="المدخل غير صحيح!"
                                   data-val-regex-pattern="<?= float_format_exp() ?>" data-val="true"
                                   value="<?= $HaveRs ? $rs['CURR_VALUE'] : '' ?>" data-val-required="حقل مطلوب"
                                   id="txt_curr_value" class="form-control ">
                            <span class="field-validation-valid" data-valmsg-for="curr_value"
                                  data-valmsg-replace="true"></span>
                        </div>
                    </div>

                    <div class="form-group col-sm-3">
                        <label class="control-label"> المستفيد </label>
                        <div class="">
                            <input readonly class="form-control" readonly id="txt_cust_id"
                                   value="<?= $HaveRs ? $rs['CUST_ID_NAME'] : '' ?>">
                            <input type="hidden" name="cust_id" value="<?= $HaveRs ? $rs['CUST_ID'] : '' ?>"
                                   id="h_txt_cust_id">

                        </div>
                    </div>

                    <hr>
                    <div class="form-group col-sm-3">
                        <label class="control-label"> حساب الخزينة - أمانات </label>
                        <div class="">
                            <?php if (!$HaveRs or (isset($can_edit) && $can_edit)) : ?>
                                
									   
									     <select name="treasury_account_id"
                                          data-val="true"
                                          data-val-required="حقل مطلوب"
                                          class="form-control"
                                          id="txt_treasury_account_id">

                                </select>
								
                                <span class="field-validation-valid" data-valmsg-for="treasury_account_id"
                                      data-valmsg-replace="true"></span>
                            <?php else: ?>
                                <input type="text" name="treasury_account_id" data-val="true"
                                       value="<?= $HaveRs ? $rs['TREASURY_ACCOUNT_ID_NAME'] : '' ?>"
                                       data-val-required="حقل مطلوب" id="txt_treasury_account_id" class="form-control">

                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="form-group col-sm-3">
                        <label class="control-label"> حساب الأمانات - كفالات للغير </label>
                        <div class="">
                            <?php if (!$HaveRs or (isset($can_edit) && $can_edit)) : ?>
                                <!--                                <input type="text" name="sec_account_id"    data-val="true" value="<? /*= $HaveRs?$rs['SEC_ACCOUNT_ID']:'' */ ?>"  data-val-required="حقل مطلوب"  id="txt_sec_account_id" class="form-control  easyui-combotree" data-options="animate:true,lines:true,required:true">
-->                               <select name="sec_account_id"
                                          data-val="true"
                                          data-val-required="حقل مطلوب"
                                          class="form-control"
                                          id="txt_sec_account_id">

                                </select>
                                <span class="field-validation-valid" data-valmsg-for="sec_account_id"
                                      data-valmsg-replace="true"></span>
                            <?php else: ?>
                                <input type="text" name="sec_account_id" data-val="true"
                                       value="<?= $HaveRs ? $rs['SEC_ACCOUNT_ID_NAME'] : '' ?>"
                                       data-val-required="حقل مطلوب" id="txt_sec_account_id" class="form-control">

                            <?php endif; ?>
                        </div>
                    </div>

                    <hr>
                    <div id="for_checks">
                        <div class="form-group col-sm-2">
                            <label class="control-label"> رقم الشيك / الكفالة </label>
                            <div class="">
                                <input type="text" name="check_id" value="<?= $HaveRs ? $rs['CHECK_ID'] : '' ?>"
                                       data-val="true" data-val-required="حقل مطلوب" id="txt_check_id"
                                       class="form-control ">
                                <span class="field-validation-valid" data-valmsg-for="check_id"
                                      data-valmsg-replace="true"></span>
                            </div>
                        </div>

                        <div class="form-group col-sm-3">
                            <label class="control-label">اسم صاحب الشيك / الكفالة</label>
                            <div class="">
                                <input type="text" name="check_customer" data-val="true"
                                       value="<?= $HaveRs ? $rs['CHECK_CUSTOMER'] : '' ?>" data-val-required="حقل مطلوب"
                                       id="txt_check_customer" class="form-control ">
                                <span class="field-validation-valid" data-valmsg-for="check_customer"
                                      data-valmsg-replace="true"></span>
                            </div>
                        </div>
                        <div class="form-group col-sm-2">
                            <label class="control-label"> البنك </label>
                            <div class="">
                                <select name="check_bank_id" id="dp_check_bank_id" class="form-control">

                                    <?php foreach ($banks as $row) : ?>
                                        <option <?= $HaveRs ? (intval($rs['CHECK_BANK_ID']) == intval($row['CON_NO']) ? 'selected' : '') : '' ?>
                                                value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-sm-1">
                            <label class="control-label">الملبغ</label>
                            <div class="">
                                <input type="text" name="amount" value="<?= $HaveRs ? $rs['AMOUNT'] : '' ?>"
                                       data-val-regex="المدخل غير صحيح"
                                       data-val-regex-pattern="<?= float_format_exp() ?>" data-val="true"
                                       data-val-required="حقل مطلوب" id="txt_amount" class="form-control ">
                                <span class="field-validation-valid" data-valmsg-for="amount"
                                      data-valmsg-replace="true"></span>
                            </div>
                        </div>
                    </div>


                    <hr>

                    <div class="form-group col-sm-12">
                        <label class="control-label"> البيان </label>
                        <div class="">
                            <input type="text" name="hints" data-val="true" value="<?= $HaveRs ? $rs['HINTS'] : '' ?>"
                                   data-val-required="حقل مطلوب" id="txt_declaration" class="form-control ">
                            <span class="field-validation-valid" data-valmsg-for="declaration"
                                  data-valmsg-replace="true"></span>
                        </div>
                    </div>


                </div>

                <hr/>

                <div class="modal-footer">


                    <?php if (!$HaveRs || (isset($can_edit) && $can_edit)): ?>
                        <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                        <button type="button" onclick="javascript:clear_form();" class="btn btn-default"> تفريغ الحقول
                        </button>
                    <?php endif; ?>

                </div>

                <div>
                    <?php echo modules::run('financial/warranty/public_get_details', $HaveRs ? $rs['BAIL_ID'] : 0); ?>

                </div>
            </form>
        </div>

    </div>

<?php
$exp = float_format_exp();
$select_accounts_url = base_url('financial/accounts/public_select_accounts');
$customer_url = base_url('payment/customers/public_index');
$project_accounts_url = base_url('projects/projects/public_select_project_accounts');
$cars_url = base_url('payment/cars/public_select_car');
$currency_date_url = base_url('settings/currency/public_get_currency_date');
$delete_url = base_url('financial/warrant_bill/delete');


$TREASURY_ACCOUNT_ID = $HaveRs ? $rs['TREASURY_ACCOUNT_ID'] : '';
$SEC_ACCOUNT_ID = $HaveRs ? $rs['SEC_ACCOUNT_ID'] : '';

$shared_js = <<<SCRIPT


 $('#txt_check_customer').focus(function(){

        if( $('#txt_check_customer').val() == '')
        $('#txt_check_customer').val($('#txt_cust_id').val());
    });


  $('input[name="account1"]').click(function(e){
        _showReport('$select_accounts_url/'+$(this).attr('id')+'/-1/' );
    });

    $('input[name="account2"]').click(function(e){
        _showReport('$select_accounts_url/'+$(this).attr('id')+'/-1/' );
    });


            $('#dp_currency_id').change(function(){currency_value();});

            $('#txt_cust_id').click(function(){

                        _showReport('$customer_url/'+$(this).attr('id'));

            });

currency_value();

       function currency_value(){
              $('#txt_curr_value').val($('#dp_currency_id').find(':selected').attr('data-val'));
              get_accounts();
       }

  $('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        if(confirm('هل تريد حفظ السند ؟!')){
            var form = $(this).closest('form');
            ajax_insert_update(form,function(data){
                if(data==1){
                    success_msg('رسالة','تم حفظ البيانات بنجاح ..');

                        setTimeout(function(){

                window.location.reload();

                }, 1000);
                }else{
                    danger_msg('تحذير..',data);
                }
            },'html');
        }
    });
get_accounts();

 function get_accounts(){
        get_data('{$get_account_url}',{perfix:'$treasury_prefix',curr_id: $('#dp_currency_id').val()},function(data){
			 $('#txt_treasury_account_id').html('');
			   $(data).each(function(i){
			     $('#txt_treasury_account_id').append('<option value="'+data[i].id+'">'+data[i].text+'</option>');
                });
				
				if($('select#txt_treasury_account_id').length > 0){
				   $('#txt_treasury_account_id').select2();
                 $('#txt_treasury_account_id').val(TREASURY_ACCOUNT_ID);
				 $('#txt_treasury_account_id').trigger('change');
				}
				 
          /*  if(data.length > 0){
                $('#txt_treasury_account_id').combotree('loadData',data);
                if(TREASURY_ACCOUNT_ID == ''){
                    $('#txt_treasury_account_id').combotree('setValue', data[0].id);
                    $('#txt_treasury_account_id').combotree('setText',data[0].text);
                }else{
                    $('#txt_treasury_account_id').combotree('setText',TREASURY_ACCOUNT_ID);
                    $('#txt_treasury_account_id').combotree('setValue', TREASURY_ACCOUNT_ID);
                }
            } else{
                $('#txt_treasury_account_id').combotree('loadData',[]);
                $('#txt_treasury_account_id').combotree('clear');
            }*/
        });

          get_data('{$get_account_url}',{perfix:'$warranty_prefix',curr_id: $('#dp_currency_id').val()},function(data){
             $('#txt_sec_account_id').html('');
            if(data.length > 0){
                
                $(data).each(function(i){
                 $('#txt_sec_account_id').append('<option value="'+data[i].id+'">'+data[i].text+'</option>');
                });
                
				if($('select#txt_sec_account_id').length > 0){
                 $('#txt_sec_account_id').select2();
                 $('#txt_sec_account_id').val(SEC_ACCOUNT_ID);
				 $('#txt_sec_account_id').trigger('change');
				}
				
               /* $('#txt_sec_account_id').combotree('loadData',data);
                if(SEC_ACCOUNT_ID == ''){
                    $('#txt_sec_account_id').combotree('setValue', data[0].id);
                    $('#txt_sec_account_id').combotree('setText',data[0].text);
                }else{
                    $('#txt_sec_account_id').combotree('setText',SEC_ACCOUNT_ID);
                    $('#txt_sec_account_id').combotree('setValue', SEC_ACCOUNT_ID);
                }*/
            } else{
                /*$('#txt_sec_account_id').combotree('loadData',[]);
                $('#txt_sec_account_id').combotree('clear');*/
            }
        });
    }


  $('#txt_bail_date').change(function(){
                get_data('{$currency_date_url}',{date:$(this).val()},function(data){

                    if(data.length > 0){
                         $('#dp_currency_id').html('');

                         $.each(data,function(i,item){
                            $('#dp_currency_id').append('<option data-val="'+item.VAL+'"   value="'+item.CURR_ID+'">'+item.CURR_NAME+'</option>');

                        });

currency_value();
                    }
                });
        });

SCRIPT;

$disable = $HaveRs && isset($can_edit) && $can_edit ? "" : "$('input,select',$('#warrant_from')).prop('disabled',true);";

$scripts = <<<SCRIPT
<script>
var TREASURY_ACCOUNT_ID = '';
var SEC_ACCOUNT_ID = '';
 function clear_form(){

        clearForm($('#warrant_from'));



        }

  {$shared_js}
 </script>
SCRIPT;

if ($isCreate)
    sec_scripts($scripts);

else {
    $edit_script = <<<SCRIPT
    <script>

    var TREASURY_ACCOUNT_ID = '{$TREASURY_ACCOUNT_ID}';
    var SEC_ACCOUNT_ID = '{$SEC_ACCOUNT_ID}';

 {$shared_js}
{$disable}

     function delete_details(a,id,exid){
     if(confirm('هل تريد حذف البند ؟!')){

          get_data('{$delete_url}',{id:id,warrant_bill_id:exid},function(data){
                     if(data == '1'){
                        $(a).closest('tr').remove();

                       }else{
                             danger_msg( 'تحذير','فشل في العملية');
                       }
                });
         }
     }

  </script>
SCRIPT;

    sec_scripts($edit_script);


}

?>