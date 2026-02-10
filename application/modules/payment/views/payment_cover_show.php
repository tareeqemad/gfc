<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 06/11/14
 * Time: 09:09 ص
 */

$create_url = base_url('payment/financial_payment/');
$create_cover_url = base_url('payment/payment_cover/create');
$select_accounts_url = base_url('financial/accounts/public_select_accounts');
$back_url = base_url('payment/payment_cover');
$print_url = base_url('/reports');
$select_invoice_url= base_url("stores/invoice_class_input/public_invoice_popup");
$customer_balance_url= base_url("payment/customers/public_get_customer_balance");

$isCreate = isset($payments_data) && count($payments_data) > 0 ? false : true;
$rs = $isCreate ? array() : $payments_data[0];
$extract_info = $is_extract ? $extract_data[0] : array();
$customer_type = '';
$customer_type2 = '';
$customer_id = '';
$customer_id_name = '';
$cust_curr_id = 1;
$purchase_type = '';
$real_order_id = '';
$purchase_text = '';
$total_extract = '';
$extract_ser = '';
$have_extract = false;
if ($is_extract and $isCreate) {
    $customer_type = 2;
    $customer_type2 = 1;
    $customer_id = $extract_info['CUSTOMER_ID'];
    $customer_id_name = $extract_info['CUSTOMER_ID_NAME'];
    $cust_curr_id = $extract_info['CUST_CURR_ID'];
    $purchase_type = $extract_info['PURCHASE_TYPE'];
    $real_order_id = $extract_info['REAL_ORDER_ID'];
    $purchase_text = $extract_info['PURCHASE_TEXT'];
    $total_extract = $extract_info['NET'] - $extract_info['PREV_DISCOUNT'];
    $extract_ser = $extract_info['SER'];
}
if (!$isCreate) {
    $customer_type = $rs['CUSTOMER_TYPE'];
    $customer_type2 = $rs['CUSTOMER_ACCOUNT_TYPE'];
    $customer_id = $rs['CUSTOMER_ID'];
    $customer_id_name = $rs['CUSTOMER_ID_NAME'];
    $cust_curr_id = $rs['CURR_ID'];
    $purchase_type = $rs['PURCHESES_TYPE'];
    $real_order_id = $rs['SUPPLY_ID'];
    $purchase_text = $rs['REQUEST_ID'];
    $total_extract = $rs['INVOICE_VALUE'];
    $extract_ser = $rs['EXTRACT_SER'];

}
if ($extract_ser != '') {
    $have_extract = true;
}

$customer_url = base_url('payment/customers/public_index');
$project_accounts_url = base_url('projects/projects/public_select_project_accounts');
$get_cover_url = base_url('payment/payment_cover/get/');
$report_url = base_url("JsperReport/showreport?sys=financial/archives");

?>
<?= AntiForgeryToken() ?>
<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
            <?php if (HaveAccess($create_cover_url)): ?>
                <li><a href="<?= $create_cover_url ?>"><i class="icon icon-plus"></i> جديد </a> </li><?php endif; ?>

            <?php if (HaveAccess($back_url)): ?>
                <li><a href="<?= $back_url ?>"><i class="icon icon-reply"></i> </a> </li><?php endif; ?>
            <li><a onclick="<?= $help ?>" href="javascript:" class="help"><i class="icon icon-question-circle"></i></a>
            </li>
        </ul>

    </div>
</div>
<div class="form-body">

    <div id="msg_container"></div>
    <div id="container">
        <form class="form-vertical" id="payment_from" method="post"
              action="<?= base_url('payment/payment_cover/' . $action) ?>" role="form" novalidate="novalidate">
            <div class="modal-body inline_form">

                <div class="form-group col-sm-2">
                    <label class="control-label">التاريخ</label>
                    <div class="">
                        <input type="hidden" name="cover_seq">
                        <input type="hidden" name="extract_ser" value="<?= $extract_ser; ?>">
                        <input type="text" readonly value="<?= date('d/m/Y') ?>" name="entery_date" id="txt_entery_date"
                               class="form-control ">
                        <span class="field-validation-valid" data-valmsg-for="entery_date"
                              data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">نوع الحساب</label>
                    <div class="">
                        <select name="customer_type" id="dp_customer_type" class="form-control">
                            <?php foreach ($account_type as $_row) : ?>
                                <option value="<?= $_row['CON_NO'] ?>" <?php if ($customer_type == $_row['CON_NO']) echo 'selected' ?>><?= $_row['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>


                <div class="form-group col-sm-2">
                    <label class="control-label"> المستفيد</label>
                    <div class="">
                        <input name="customer_name_id" data-val="true" readonly data-val-required="حقل مطلوب"
                               class="form-control" readonly id="txt_customer_name" value="<?= $customer_id_name ?>">
                        <input type="hidden" name="customer_id" value="<?= $customer_id ?>" id="h_txt_customer_name">

                        <span class="field-validation-valid" data-valmsg-for="customer_id"
                              data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div id="db_customer_account_type_div" style="display: none">

                    <div class="form-group col-sm-1">
                        <label class="control-label">الرصيد</label>
                        <div class="">
                            <input readonly class="form-control" readonly id="txt_customer_balance" />
                        </div>
                    </div>

                <div class="form-group col-sm-1">
                    <label class="control-label"> نوع الحساب </label>
                    <div class="">
                        <select class="form-control" id="db_customer_account_type" data-val="true" data-val-required="حقل مطلوب"  name="customer_account_type">
                            <option></option>
                            <?php foreach ($ACCOUNT_TYPES as $row) : ?>
                                <option value="<?= $row['ACCCOUNT_TYPE'] ?>"
                                    <?php if ($is_extract and $isCreate and $row['ACCCOUNT_TYPE'] == 1) {
                                        echo '';
                                        if ($customer_type2 == $_row['ACCCOUNT_TYPE']) echo 'selected';
                                    } else echo 'style="display: none;"' ?> ><?= $row['ACCCOUNT_NO_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label"> اسم المستفيد</label>
                    <div class="">
                        <input name="customer_name" class="form-control" id="txt_db_customer_name"
                               value="<?= $customer_id_name ?>">

                    </div>
                </div>
                <div class="form-group col-sm-1">
                    <label class="control-label">العملة </label>
                    <div class="">
                        <select name="curr_id" id="dp_curr_id" data-curr="false" class="form-control">
                            <?php foreach ($currency as $row) : ?>
                                <option data-val="<?= $row['VAL'] ?>"
                                        value="<?= $row['CURR_ID'] ?>" <?php if ($cust_curr_id == $_row['CURR_ID']) echo 'selected' ?>><?= $row['CURR_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group col-sm-1">
                    <label class="control-label">قيمة المعاملة </label>
                    <div class="">
                        <input type="text" name="invoice_value" data-val-regex="المدخل غير صحيح ؟!"
                               data-val-regex-pattern="<?= float_format_exp() ?>" data-val="true"
                               data-val-required="حقل مطلوب" id="txt_invoice_value" class="form-control "
                               value="<?= $total_extract ?>">
                        <span class="field-validation-valid" data-valmsg-for="invoice_value"
                              data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">أرقام الفواتير</label>
                    <div class="">
                        <input type="text" name="invoice_id" id="txt_invoice_id" class="form-control ">
                        <span class="field-validation-valid" data-valmsg-for="invoice_id"
                              data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">تاريخ الفواتير / المعاملة </label>
                    <div class="">
                        <input type="text" name="invoice_date" id="txt_invoice_date" class="form-control ">
                        <span class="field-validation-valid" data-valmsg-for="invoice_date"
                              data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">رقم أمر شراء </label>
                    <div class="">
                        <input type="text" name="request_id" id="txt_request_id" class="form-control "
                               value="<?= $purchase_text ?>">
                        <span class="field-validation-valid" data-valmsg-for="request_id"
                              data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label"> رقم أمر توريد</label>
                    <div class="">
                        <input type="text" name="supply_id" id="txt_supply_id" class="form-control "
                               value="<?= $real_order_id ?>">
                        <span class="field-validation-valid" data-valmsg-for="supply_id"
                              data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">رقم القيد</label>
                    <div class="">
                        <input type="text" name="financial_chains_id" id="txt_financial_chains_id"
                               class="form-control ">
                        <span class="field-validation-valid" data-valmsg-for="financial_chains_id"
                              data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">نوع الشراء </label>
                    <div class="">
                        <select name="purcheses_type" id="dp_purcheses_type" class="form-control">
                            <?php foreach ($purcheses_type as $_row) : ?>
                                <option value="<?= $_row['CON_NO'] ?>" <?php if ($purchase_type == $_row['CON_NO']) echo 'selected' ?>><?= $_row['CON_NAME'] ?></option>

                            <?php endforeach; ?>
                            <option value="4"> صرف</option>
                        </select>
                    </div>
                </div>


                <hr>
                <div class="form-group col-sm-6">
                    <label class="control-label">نوع المعاملة </label>
                    <div class="">
                        <textarea type="text" data-val="true" data-val-required="حقل مطلوب" name="cover_type"
                                  id="txt_cover_type" class="form-control "></textarea>
                        <span class="field-validation-valid" data-valmsg-for="cover_type"
                              data-valmsg-replace="true"></span>
                    </div>
                </div>


                <div class="form-group col-sm-6">
                    <label class="control-label">دائرة الحسابات العامة </label>
                    <div class="">
                        <textarea type="text" name="finanical_statement" id="txt_finanical_statement"
                                  class="form-control "></textarea>
                        <span class="field-validation-valid" data-valmsg-for="finanical_statement"
                              data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group col-sm-6">
                    <label class="control-label">مستحقات الكهرباء </label>
                    <div class="">
                        <textarea type="text" name="elec_wants" id="txt_elec_wants" class="form-control "></textarea>
                        <span class="field-validation-valid" data-valmsg-for="elec_wants"
                              data-valmsg-replace="true"></span>
                    </div>
                </div>
                <div class="form-group col-sm-6">
                    <label class="control-label">مراقبة المخازن </label>
                    <div class="">
                        <textarea type="text" name="invontry_moniter" id="txt_invontry_moniter"
                                  class="form-control "></textarea>
                        <span class="field-validation-valid" data-valmsg-for="invontry_moniter"
                              data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group col-sm-6">
                    <label class="control-label">دائرة الخزينة </label>
                    <div class="">
                        <textarea type="text" name="treasury_dep" id="txt_treasury_dep"
                                  class="form-control "></textarea>
                        <span class="field-validation-valid" data-valmsg-for="treasury_dep"
                              data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group col-sm-6">
                    <label class="control-label">مدير التدقيق المالي </label>
                    <div class="">
                        <textarea type="text" name="eduit_dep" id="txt_eduit_dep" class="form-control "></textarea>
                        <span class="field-validation-valid" data-valmsg-for="eduit_dep"
                              data-valmsg-replace="true"></span>
                    </div>
                </div>
                <div class="form-group col-sm-6">
                    <label class="control-label">دائرة المشتريات</label>
                    <div class="">
                        <textarea type="text" name="selling_dep" id="txt_selling_dep" class="form-control "></textarea>
                        <span class="field-validation-valid" data-valmsg-for="selling_dep"
                              data-valmsg-replace="true"></span>
                    </div>
                </div>
                <hr>
                <div class="form-group col-sm-12">
                    <label class="control-label">المرفقات </label>
                    <div class="">
                        <?php foreach ($attachments as $row) : ?>


                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="attachments[]"
                                           value="<?= $row['CON_NO'] ?>"> <?= $row['CON_NAME'] ?>
                                </label>
                            </div>

                        <?php endforeach; ?>

                    </div>
                </div>


            </div>

            <hr/>

            <div class="modal-footer">


                <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>

                <?php if (count($rs) > 0): ?>

                    <?php if (HaveAccess($create_url . (isset($rs['FINANCIAL_PAYMENT_ID']) ? '/get' : '/create'))): ?>
                        <a href="<?= $create_url . (($rs['FINANCIAL_PAYMENT_ID'] > 1 ? '/get/' . $rs['FINANCIAL_PAYMENT_ID'] . '/edit/1' : '/create/' . $rs['COVER_SEQ'])) ?>"
                           class="btn green"><i class="glyphicon glyphicon-plus"></i>سند صرف </a>  <?php endif; ?>

                    <button type="button"
                            onclick="_showReport('<?= $report_url ?>&report_type=pdf&report=payment_cover&p_cover_seq=<?= $rs['COVER_SEQ'] ?>');"
                            class="btn btn-default">طباعة
                    </button>
                <?php endif; ?>
            </div>
        </form>
    </div>

</div>


<?php
$exp = float_format_exp();


$rs = preg_replace("/(\r\n|\n|\r)/", "\\n", $rs);

$shared_js = <<<SCRIPT
showCustomerTypeAccount($( "#dp_customer_type" ));

  $('#txt_customer_name').click(function(e){
  selectAccount($(this));
      // _showReport('$customer_url/'+$(this).attr('id')+'/');
    });

            $('#txt_invoice_id').dblclick(function(e){
                 if( '{$have_extract}'){
                   _showReport('$select_invoice_url'+'/'+'$customer_id' );
                 }
             
            });


    $('#txt_db_customer_name').focus(function(){

        if( $('#txt_db_customer_name').val() == '')
        $('#txt_db_customer_name').val($('#txt_customer_name').val());
    });

 $('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        if(confirm('هل تريد حفظ السند ؟!')){
            var form = $(this).closest('form');
            ajax_insert_update(form,function(data){
                if(parseInt(data) > 0 && '{$action}' == 'create'){
                    success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                 setTimeout(function(){

                   get_to_link('{$get_cover_url}/'+data);

                }, 300);



                }else if(parseInt(data) > 0 && '{$action}' == 'edit'){
                    success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                }
            },'html');
        }
    });

     function selectAccount(obj){
         var _type = $('#dp_customer_type').val();

            if(_type == 1)
                _showReport('$select_accounts_url/'+$(obj).attr('id') );
            if(_type == 2)
                _showReport('$customer_url/'+$(obj).attr('id')+'/');
            if(_type == 3)
                _showReport('$project_accounts_url/'+$(obj).attr('id')+'/');
           if(_type == 5)
                _showReport('$customer_url/'+$(obj).attr('id')+'/4');
    }

    $('#dp_customer_type').change(function(){
        $('#txt_customer_name,#h_txt_customer_name').val('');
        showCustomerTypeAccount($(this));
    });
    
    
    function showCustomerTypeAccount(obj){
  
        if($(obj).val() == 2){ 
            //$('#db_customer_account_type').closest('div.form-group').show();
            $('#db_customer_account_type_div').show();
            setDefaultCustomerAccount ();
            if( '{$is_extract}' && '{$isCreate}' ){
                $('#db_customer_account_type').val(1);
            }
        }else {
            //$('#db_customer_account_type').closest('div.form-group').hide();
            $('#db_customer_account_type_div').hide();
        }
    }
    
    function setDefaultCustomerAccount(){
        var customer_id= $('#h_txt_customer_name').val();
        if(customer_id.length < 7) return 0;
        get_data('{$customer_balance_url}',{customer_id:customer_id} ,function(data){
            $('#txt_customer_balance').val(data);
        },'html');
    }

SCRIPT;


$today = date('d/m/Y');

$scripts = <<<SCRIPT
<script>


  {$shared_js}


    function clear_form(){
        clearForm($('#payment_from'));
     $('#txt_entery_date').val('$today');
     $('#dp_attachments').val('');
    }



</script>
SCRIPT;

if ($isCreate)
    sec_scripts($scripts);

else {


    $edit_script = <<<SCRIPT

<script>
    {$shared_js}


    $('input[name="cover_seq"]').val('{$rs['COVER_SEQ']}');
    $('#txt_entery_date').val('{$rs['ENTERY_DATE']}');
    $('#h_txt_customer_name').val('{$rs['CUSTOMER_ID']}');
    $('#txt_customer_name').val('{$rs['CUSTOMER_ID_NAME']}');
    $('#dp_curr_id').val('{$rs['CURR_ID']}');
    $('#txt_invoice_value').val('{$rs['INVOICE_VALUE']}');
    $('#txt_invoice_id').val('{$rs['INVOICE_ID']}');
    $('#txt_invoice_date').val('{$rs['INVOICE_DATE']}');

    $('#txt_request_id').val('{$rs['REQUEST_ID']}');
    $('#txt_supply_id').val('{$rs['SUPPLY_ID']}');
    $('#txt_financial_chains_id').val('{$rs['FINANCIAL_CHAINS_ID']}');
    $('#txt_cover_type').val('{$rs['COVER_TYPE']}');
    $('#txt_finanical_statement').val('{$rs['FINANICAL_STATEMENT']}');
    $('#txt_elec_wants').val('{$rs['ELEC_WANTS']}');
    $('#txt_invontry_moniter').val('{$rs['INVONTRY_MONITER']}');
    $('#txt_treasury_dep').val('{$rs['TREASURY_DEP']}');
    $('#txt_eduit_dep').val('{$rs['EDUIT_DEP']}');
    $('#dp_customer_type').val('{$rs['CUSTOMER_TYPE']}');
    $('#txt_db_customer_name').val('{$rs['CUSTOMER_NAME']}');
    $('#txt_selling_dep').val('{$rs['SELLING_DEP']}');
    $('#dp_purcheses_type').val('{$rs['PURCHESES_TYPE']}');
    $('#db_customer_account_type').val('{$rs['CUSTOMER_ACCOUNT_TYPE']}');

    var details = jQuery.parseJSON('{$details}');
    $.each(details, function(i,e){
        $('input[name="attachments[]"][value="' + e.CONSTANT_ID + '"]').prop("checked", true);
    });
    
     showCustomerTypeAccount($('#dp_customer_type'));

</script>
SCRIPT;
    if (isset($can_edit) ? $can_edit : false)
        $edit_script = $edit_script;


    sec_scripts($edit_script);


}

?>


