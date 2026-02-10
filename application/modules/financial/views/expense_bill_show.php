<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 06/11/14
 * Time: 09:09 ص
 */

$back_url = base_url('financial/expense_bill');
$get_id_url = base_url('financial/accounts/public_get_id');

if (!isset($result))
    $result = array();

$HaveRs = count($result) > 0;

$rs = $HaveRs ? $result[0] : array();

$isCreate = !$HaveRs && $action == 'copy';


?>
<?= AntiForgeryToken() ?>
    <div class="row">
        <div class="toolbar">

            <div class="caption"><?= $title ?></div>

            <ul>
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
            <form class="form-vertical" id="expense_from" method="post"
                  action="<?= base_url('financial/expense_bill/' . ($action == 'copy' ? 'create' : $action)) ?>"
                  role="form"
                  novalidate="novalidate">
                <div class="modal-body inline_form">

                    <div class="form-group col-sm-1">
                        <label class="control-label"> رقم السند </label>

                        <div class="">
                            <input type="text" name="expense_bill_id" readonly
                                   value="<?= $HaveRs && $action != 'copy' ? $rs['EXPENSE_BILL_ID'] : '' ?>"
                                   id="txt_expense_bill_id" class="form-control ">

                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">تاريخ السند</label>

                        <div class="">
                            <input type="text" name="expense_bill_date"
                                   value="<?= $HaveRs ? $rs['EXPENSE_BILL_DATE'] : '' ?>"
                                   data-date-format="DD/MM/YYYY" data-type="date" data-val-regex="المدخل غير صحيح!"
                                   data-val-regex-pattern="<?= date_format_exp() ?>" data-val="true"
                                   data-val-required="حقل مطلوب"
                                   id="txt_expense_bill_date" class="form-control ">
                            <span class="field-validation-valid" data-valmsg-for="expense_bill_date"
                                  data-valmsg-replace="true"></span>
                        </div>
                    </div>

                    <div class="form-group col-sm-1">
                        <label class="control-label">نوع المستفيد</label>

                        <div class="">
                            <select name="account_type"
                                    id="dp_account_type"

                                    class="form-control">
                                <?php foreach ($customer_type as $row) : ?>
                                    <option <?= $HaveRs ? ($rs['ACCOUNT_TYPE'] == $row['CON_NO'] ? 'selected' : '') : '' ?>
                                            value="<?= $row['CON_NO'] ?>"
                                    ><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>


                    <div class="form-group col-sm-3">
                        <label class="control-label"> المستفيد </label>

                        <div class="">
                            <input data-val="true" readonly data-val-required="حقل مطلوب" class="form-control" readonly
                                   id="txt_account_id" value="<?= $HaveRs ? $rs['ACCOUNT_ID_NAME'] : '' ?>">
                            <input type="hidden" name="account_id" value="<?= $HaveRs ? $rs['ACCOUNT_ID'] : '' ?>"
                                   id="h_txt_account_id">

                        </div>
                    </div>

                    <div class="form-group col-sm-1"
                         style="display: <?= $HaveRs ? ($rs['ACCOUNT_TYPE'] == 1 ? 'none' : 'block') : 'none'  ?>"
                         id="customer_account_type_div">
                        <label class="control-label"> نوع حساب المستفيد </label>
                        <select class="form-control"
                                id="db_cat_txt_account_id"
                                data-val="true"

                                data-val-required="حقل مطلوب"
                                name="m_customer_account_type">
                            <option></option>
                            <?php foreach ($ACCOUNT_TYPES as $_row) : ?>

                                <option <?= $HaveRs ? ($rs['CUSTOMER_ACCOUNT_TYPE'] == $_row['ACCCOUNT_TYPE'] ? 'selected' : '') : '' ?>
                                        style="display: none;"
                                        value="<?= $_row['ACCCOUNT_TYPE'] ?>"><?= $_row['ACCCOUNT_NO_NAME'] ?></option>

                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php if ($HaveRs && $action != 'copy'): ?>

                        <div class="form-group col-sm-2" style="padding-top: 42px;padding-right: 30px;">

                            <div class="">
                                <a id="source_url"
                                   href="<?= base_url("financial/financial_chains/get/{$rs['QEED_NO']}/index?type=4") ?>"
                                   class="btn-xs btn-success" target="_blank"> عرض القيد</a>
                            </div>
                        </div>

                    <?php endif; ?>

                    <hr>
                    <div class="form-group col-sm-1">
                        <label class="control-label"> رقم الفاتورة </label>

                        <div class="">
                            <input type="text" name="invoice_id" data-val="true"
                                   value="<?= $HaveRs && $action != 'copy' ? $rs['INVOICE_ID'] : '' ?>"
                                   data-val-required="حقل مطلوب" id="txt_invoice_id" class="form-control ">
                            <span class="field-validation-valid" data-valmsg-for="invoice_id"
                                  data-valmsg-replace="true"></span>
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label"> تاريخ الفاتورة</label>

                        <div class="">
                            <input type="text" name="invoice_date" data-date-format="DD/MM/YYYY" data-type="date"
                                   data-val-regex="المدخل غير صحيح!" data-val-regex-pattern="<?= date_format_exp() ?>"
                                   data-val="true"
                                   value="<?= $HaveRs && $action != 'copy' ? $rs['INVOICE_DATE'] : '' ?>"
                                   data-val-required="حقل مطلوب"
                                   id="txt_invoice_date" class="form-control ">
                            <span class="field-validation-valid" data-valmsg-for="invoice_date"
                                  data-valmsg-replace="true"></span>
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label"> التاريخ الثاني </label>

                        <div class="">
                            <input type="text" name="second_date" data-date-format="DD/MM/YYYY" data-type="date"
                                   data-val-regex="المدخل غير صحيح!" data-val-regex-pattern="<?= date_format_exp() ?>"
                                   value="<?= $HaveRs ? $rs['SECOND_DATE'] : '' ?>" id="txt_second_date"
                                   class="form-control ">
                            <span class="field-validation-valid" data-valmsg-for="second_date"
                                  data-valmsg-replace="true"></span>
                        </div>
                    </div>


                    <div class="form-group col-sm-1">
                        <label class="control-label"> حالة الفاتورة </label>

                        <div class="">
                            <select name="expense_bill_close" id="dp_expense_bill_close" class="form-control">

                                <option <?= $HaveRs ? ($rs['EXPENSE_BILL_CLOSE'] == 1 ? 'selected' : '') : '' ?>
                                        value="1">غير
                                    مدفوعة
                                </option>
                                <option <?= $HaveRs ? ($rs['EXPENSE_BILL_CLOSE'] == 2 ? 'selected' : '') : '' ?>
                                        value="2">مدفوعة
                                </option>

                            </select>
                        </div>
                    </div>


                    <div class="form-group col-sm-1">
                        <label class="control-label"> العملة </label>

                        <div class="">

                            <select name="curr_id" id="dp_curr_id" data-curr="false" class="form-control">
                                <?php foreach ($currency as $row) : ?>

                                    <option <?= $HaveRs ? (intval($rs['CURR_ID']) == intval($row['CURR_ID']) ? 'selected' : '') : '' ?>
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
                                   id="txt_curr_value"
                                   class="form-control ">
                            <span class="field-validation-valid" data-valmsg-for="curr_value"
                                  data-valmsg-replace="true"></span>
                        </div>
                    </div>
                    <hr>

                    <div class="form-group col-sm-1">
                        <label class="control-label"> الخصم المكتسب </label>

                        <div class="">
                            <select name="discount_type" id="dp_discount_type" class="form-control">

                                <option <?= $HaveRs ? ($rs['DISCOUNT_TYPE'] == 1 ? 'selected' : '') : '' ?> value="1">
                                    مبلغ
                                </option>
                                <option <?= $HaveRs ? ($rs['DISCOUNT_TYPE'] == 2 ? 'selected' : '') : '' ?> value="2">
                                    نسبة
                                </option>

                            </select>
                        </div>
                    </div>


                    <div class="form-group col-sm-1">
                        <label class="control-label"> قيم الخصم</label>

                        <div class="">
                            <input type="text" name="discount_value" data-val-regex="المدخل غير صحيح!"
                                   data-val-regex-pattern="<?= float_format_exp() ?>"
                                   value="<?= $HaveRs ? $rs['DISCOUNT_VALUE'] : '' ?>" id="txt_discount_value"
                                   class="form-control ">
                            <span class="field-validation-valid" data-valmsg-for="discount_value"
                                  data-valmsg-replace="true"></span>
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">الأسعار تشمل ض.ق.م</label>

                        <div class="">
                            <select name="vat_type" id="dp_vat_type" data-curr="false" class="form-control">

                                <option <?= $HaveRs ? ($rs['VAT_TYPE'] == 1 ? 'selected' : '') : '' ?> value="1"> يشمل
                                </option>
                                <option <?= $HaveRs ? ($rs['VAT_TYPE'] == 2 ? 'selected' : '') : '' ?> value="2">لا
                                    يشمل
                                </option>

                            </select>
                        </div>
                    </div>
                    <div class="form-group col-sm-3">
                        <label class="control-label"> رقم حساب الضريبة </label>

                        <div class="">
                            <input type="text" readonly data-val="true"
                                   value="<?= $HaveRs ? $rs['VAT_ACCOUNT_ID_NAME'] : $vat_account_id ?>"
                                   data-val-required="حقل مطلوب" id="txt_vat_account_id" class="form-control ">
                            <input type="hidden" name="vat_account_id"
                                   value="<?= $HaveRs ? $rs['VAT_ACCOUNT_ID'] : $vat_account_id ?>"
                                   id="h_txt_vat_account_id">
                            <span class="field-validation-valid" data-valmsg-for="vat_account_id"
                                  data-valmsg-replace="true"></span>
                        </div>
                    </div>
                    <div class="form-group col-sm-1">
                        <label class="control-label"> نسبة الضريبة </label>

                        <div class="">
                            <input type="text" name="vat_value" value="<?= $HaveRs ? $rs['VAT_VALUE'] : $vat_value ?>"
                                   data-val-regex="المدخل غير صحيح!" data-val-regex-pattern="<?= float_format_exp() ?>"
                                   data-val="true" data-val-required="حقل مطلوب" id="txt_vat_value"
                                   class="form-control ">
                            <span class="field-validation-valid" data-valmsg-for="vat_value"
                                  data-valmsg-replace="true"></span>
                        </div>
                    </div>

                    <hr>
                    <div class="form-group col-sm-12">
                        <label class="control-label"> البيان </label>

                        <div class="">
                            <input type="text" name="declaration" data-val="true"
                                   value="<?= $HaveRs ? $rs['DECLARATION'] : '' ?>"
                                   data-val-required="حقل مطلوب" id="txt_declaration" class="form-control ">
                            <span class="field-validation-valid" data-valmsg-for="declaration"
                                  data-valmsg-replace="true"></span>
                        </div>
                    </div>

                    <hr>

                    <div style="clear: both;">
                        <?php echo modules::run('financial/expense_bill/public_get_details', $HaveRs ? $rs['EXPENSE_BILL_ID'] : 0, (isset($can_edit) ? $can_edit : false), $action == 'copy'); ?>
                    </div>

                </div>

                <hr/>

                <div class="modal-footer">


                    <?php if (!$HaveRs || (isset($can_edit) && $can_edit) || $action == 'copy'): ?>
                        <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                        <button type="button" onclick="clear_form();" class="btn btn-default"> تفريغ الحقول</button>
                    <?php endif; ?>

                </div>
            </form>
        </div>

    </div>
    <div class="modal fade" id="notesModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span
                                aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title"> ملاحظات</h4>
                </div>
                <div id="msg_container_alt"></div>

                <div class="form-group col-sm-12">

                    <div class="">
                        <textarea type="text" data-val="true" rows="5" id="txt_g_notes"
                                  class="form-control "></textarea>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" onclick="apply_action();" class="btn btn-primary">حفظ البيانات
                    </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>

                </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div><!-- /.modal -->

<?php
$exp = float_format_exp();
$select_accounts_url = base_url('financial/accounts/public_select_accounts');
$customer_url = base_url('payment/customers/public_index');
$project_accounts_url = base_url('projects/projects/public_select_project_accounts');
$cars_url = base_url('payment/cars/public_select_car');
$currency_date_url = base_url('settings/currency/public_get_currency_date');
$delete_url = base_url('financial/expense_bill/delete');
$curr_id = count($rs) > 0 ? $rs['CURR_ID'] : 1;
$shared_js = <<<SCRIPT

        var count = 1;

       $(function(){

          count = $('input[name="d_account_id[]"]').length;

         reBind();

        $('#txt_vat_value,#txt_discount_value').keyup(function(){

            calcTotalwithTax();

        });

        $('#dp_discount_type').change(function(){
            calcTotalwithTax();
        });

         currency_value();
         $('#txt_account_id').click(function(e){
            selectAccount($(this));
         });
         
         $('#dp_account_type').change(function(){
                var _type = $('#dp_account_type').val();
                 if(_type == 1) {
                    $('#db_cat_txt_account_id').closest('div.form-group').hide();            
                 } else {
                    $('#db_cat_txt_account_id').closest('div.form-group').show();
                 }   
         });

         $('#txt_declaration').change(function(){


            $('#expense_detailsTbl input[name="service_hints[]"]').each(function(i) {

                if($(this).val() == ''){
                    $(this).val( $('#txt_declaration').val());
                }
            });



        });


       });

         $('#txt_invoice_date').change(function(){
                get_data('{$currency_date_url}',{date:$(this).val()},function(data){

                    if(data.length > 0){
                         $('#dp_curr_id').html('');

                         $.each(data,function(i,item){
                            $('#dp_curr_id').append('<option data-val="'+item.VAL+'"   value="'+item.CURR_ID+'">'+item.CURR_NAME+'</option>');

                        });

                        setCurrValue();
                    }
                });
        });


 function setCurrValue(){
        $('#dp_curr_id').val({$curr_id});
        $('#txt_curr_value').val($('#dp_curr_id').find(':selected').attr('data-val'));

    }
       function calcPrice(){

           $('#dp_vat_type,select[name="vat_done[]"]').change(function(){

                calcTotalwithTax();

           });


       }


        function calcTotalwithTax(){
             var tax = $('#txt_vat_value').val();
             var vat_type = $('#dp_vat_type').val();
             var AllTotal = 0;
             var ATotal = 0;
             var TotalWithVat = 0;
             var TotalWithOutVat = 0;

             var TotalWithVat_tax = 0;
             var TotalWithOutVat_tax = 0

             $('select[name="vat_done[]"]').each(function(i){
                      var vat_done = $(this).val();

                      var count = $(this).closest('tr').find('input[name="amount[]"]').val();
                      var price = $(this).closest('tr').find('input[name="unit_price[]"]').val();

                      var total =isNaNVal( count * price,8);
                      ATotal +=total;

                      if(vat_type == 1 && vat_done == 1){
                        total = isNaNVal(total/(1+(tax/100)),2)

                       // TotalWithVat +=total;
                      }
                      else if(vat_type == 2 && vat_done == 1)
                      {
                       // TotalWithVat +=total;
                        total =total;

                      }
                      else{
                       // TotalWithOutVat +=total;
                      }

                     if( vat_done == 1){
                        TotalWithVat  += VAL_TX_VALID( (count * price),tax,vat_done,vat_type);
                        TotalWithVat_tax  +=(count * price) - VAL_TX_VALID( (count * price),tax,vat_done,vat_type);
                     }else{
                        TotalWithOutVat  += VAL_TX_VALID( (count * price),tax,vat_done,vat_type);
                     }

                      AllTotal += total;

                     $(this).closest('tr').find('td.v_balance').text(total);

                });

                 $('#inv_total').text( isNaNVal(AllTotal));

                     var discount = GET_DISCOUNT(TotalWithVat+TotalWithOutVat,
                                                 isNaNVal($('#txt_discount_value').val()),
                                                 isNaNVal($('#dp_discount_type').val())
                                                 );


                 var TotalTax =  GET_TAX_VAL_WT_DESC(TotalWithVat,TotalWithOutVat,discount,tax);


                 if (isNaNVal(TotalWithOutVat)<= 0 && isNaNVal(discount) <= 0 && vat_type == 1)
                      TotalTax = ATotal - TotalWithVat;


                 var discount_tax = 0;

                 if(discount > 0)
                    discount_tax = isNaNVal((TotalWithOutVat /AllTotal * discount ) * tax /100,2);


                 if (isNaNVal(TotalWithOutVat) > 0 && isNaNVal(discount) <= 0 && vat_type == 1)
                      TotalTax =TotalWithVat_tax;

                 //TotalTax = TotalWithVat_tax+discount_tax-TotalWithOutVat_tax;


                 $('#inv_tax').text(isNaNValMath(TotalTax,5));

                 if(discount > 0)
                      $('#inv_discount').text(isNaNVal(discount,2));
                 else
                     $('#inv_discount').text('');

                 $('#inv_after_discount').text(isNaNVal((AllTotal - discount),2));
                 $('#inv_nettotal').text(isNaNValMath((AllTotal+TotalTax-discount),5));
        }


        function setDiscountTitle(val){
            $('#inv_discount_per').text('قيمة الخصم '+val);
        }


   function reBindAfterInsert(tr){
        $('select[name="customer_account_type[]"]',$(tr)).hide();
    }


       function reBind(){



        $('select[name="d_account_type[]"]').change(function(){

            if($(this).val() == 2 ){
                $('select[name="customer_account_type[]"]',$(this).closest('tr')).show();
            } else {
                $('select[name="customer_account_type[]"]',$(this).closest('tr')).hide();
            }
        });

            delete_action();

            $('#dp_curr_id').change(function(){currency_value();});

            $('input[name="account_id_name[]"]').click(function(){

                     var _type = $(this).closest('tr').find('select[name="d_account_type[]"]').val();

                    if(_type == 1)
                        _showReport('$select_accounts_url/'+$(this).attr('id') );
                    if(_type == 2)
                        _showReport('$customer_url/'+$(this).attr('id'));
                    if(_type == 3)
                        _showReport('$project_accounts_url/'+$(this).attr('id'));
            });

             $('input[name="customer_id_name[]"]').click(function(){
                  var type = $(this).closest('tr').find('select[name="customer_type[]"]').val();

                  if(type == 1)
                  _showReport('$customer_url/'+$(this).attr('id')+'/');
                  else if(type == 2)
                  _showReport('$cars_url/'+$(this).attr('id')+'/');
            });

            $('input[name="amount[]"] , input[name="unit_price[]"]').keyup(function(){

                var amount  = $(this).closest('tr').find('input[name="amount[]"]').val();
                var price  = $(this).closest('tr').find('input[name="unit_price[]"]').val();

                $(this).closest('tr').find('td.price.v_balance').text(amount * price);

                calcTotalwithTax();

            });

            $('select[name="customer_type[]"]').change(function(){
                $(this).closest('tr').find('input[name="customer_id[]"]').val('');
                $(this).closest('tr').find('input[name="customer_id_name[]"]').val('');
            });

            $('input[name="d_account_id[]"]').keyup(function(){
                    var type = $(this).closest('tr').find('select[name="d_account_type[]"]').val();
                    if(type == 1)
                     get_account_name($(this));
                     else
                     getCustomerData($(this).closest('tr').find('input[name="account_id_name[]"]'),$(this).val());

            });

            calcPrice();
       }

       function currency_value(){
              $('#txt_curr_value').val($('#dp_curr_id').find(':selected').attr('data-val'));
       }

      function selectAccount(obj){
             var _type = $('#dp_account_type').val();

                if(_type == 1)
                    _showReport('$select_accounts_url/'+$(obj).attr('id') );
                if(_type == 2)
                    _showReport('$customer_url/'+$(obj).attr('id')+'/1');
                if(_type == 3)
                    _showReport('$customer_url/'+$(obj).attr('id')+'/2');
                 
        }

   function get_account_name(obj){
        $(obj).closest('tr').find('input[name$="_name[]"]').val('');



            if($(obj).val().length >6 ){
                get_data('{$get_id_url}',{id:$(obj).val()},function(data){

                    if(data.length > 0){

                        $(obj).closest('tr').find('input[name$="account_id_name[]"]').val(data[0].ACOUNT_NAME+' ('+data[0].CURR_NAME+')');
                    }
                });
            }
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

    function addRow(){

    if($('input:text:visible',$('#expense_detailsTbl')).not('input[name="service_hints[]"],input[name="customer_id[]"],input[name="customer_id_name[]"]' ).filter(function() { return this.value == ""; }).length <= 0){

        var tr = $('#expense_detailsTbl > tbody tr:first');
        var html  = tr.html();

        html =replaceAll('_0','_'+count,html);
        $('#expense_detailsTbl tbody').append('<tr>'+html+'</tr>');



        tr = $('#expense_detailsTbl > tbody tr:last');
        $('input',$(tr)).val('');
        $('.v_balance',$(tr)).text('');
        $('input[name="SER[]"]',$(tr)).val(0);
        $('td:last',$(tr)).html('<a data-action="delete" href="javascript:;"><i class="icon icon-trash delete-action"></i> </a>');
        reBind();
        count++;
        }
    }


SCRIPT;


$scripts = <<<SCRIPT
<script>

 function clear_form(){

        clearForm($('#expense_from'));



        }

  {$shared_js}
 </script>
SCRIPT;

if ($isCreate)
    sec_scripts($scripts);

else {
    $edit_script = <<<SCRIPT
    <script>
 {$shared_js};
     function delete_details(a,id,exid){
		 
	 if(exid == 0) { $(a).closest('tr').remove(); return; }
		  
     if(confirm('هل تريد حذف البند ؟!')){

          get_data('{$delete_url}',{id:id,expense_bill_id:exid},function(data){
                     if(data == '1'){
                        $(a).closest('tr').remove();
                       calcTotalwithTax();
                       }else{
                             danger_msg( 'تحذير','فشل في العملية');
                       }
                });
         }
     }
 calcTotalwithTax();
  </script>
SCRIPT;

    sec_scripts($edit_script);


}

?>