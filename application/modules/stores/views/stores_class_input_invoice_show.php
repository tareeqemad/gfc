<?php
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 08/12/14
 * Time: 12:41 م
 */
$MODULE_NAME= 'stores';
$TB_NAME= 'stores_class_input';
$TB_NAME2= 'stores_class_input_detail';

$get_url =base_url("$MODULE_NAME/$TB_NAME/get_id");
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$edit_url =base_url("$MODULE_NAME/$TB_NAME/get_id");
$delete_url =base_url("$MODULE_NAME/$TB_NAME/delete");
$case_url=base_url("$MODULE_NAME/$TB_NAME/update_case");
/*$record_url=base_url("$MODULE_NAME/$TB_NAME/record");
$return_url=base_url("$MODULE_NAME/$TB_NAME/returnp");*/
//$get_details_url =base_url("$MODULE_NAME/$TB_NAME2/get_page");
$customer_url =base_url('payment/customers/public_index');
$orders_url =base_url('stores/receipt_class_input/public_index');
$delete_details_url=base_url("$MODULE_NAME/$TB_NAME2/delete");
$select_items_url=base_url("$MODULE_NAME/classes/public_index");

//$back_url=base_url('payment/financial_payment/'.$action);
//$back_url=base_url("$MODULE_NAME/$TB_NAME/$action");
$back_url=base_url("$MODULE_NAME/$TB_NAME/index");

 if ($action=='edit'){
    $vat_account_id=(isset($receipt_data['VAT_ACCOUNT_ID'])&&($receipt_data['VAT_ACCOUNT_ID']!=''))? $receipt_data['VAT_ACCOUNT_ID'] :$vat_account_id_c;
    $vat_value=(isset($receipt_data['VAT_VALUE'])&&($receipt_data['VAT_VALUE']!=''))? $receipt_data['VAT_VALUE'] :$vat_value_c;
    $vat_type=isset($receipt_data['VAT_TYPE'])? $receipt_data['VAT_TYPE']:1;
    $descount_type=isset($receipt_data['DESCOUNT_TYPE'])? $receipt_data['DESCOUNT_TYPE']:1;
 }
?>

    <div class="row">
        <div class="toolbar">

            <div class="caption"><?= $title ?></div>

            <ul>
                <?php //HaveAccess($back_url)
                if( TRUE):  ?><li><a  href="<?= $back_url ?>"><i class="icon icon-reply"></i> </a> </li><?php  endif; ?>
                <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
            </ul>

        </div>
    </div>
    <div class="form-body">

    <div id="msg_container"></div>
    <div id="container">
    <form class="form-vertical" id="<?=$TB_NAME?>_form" method="post" action="<?=base_url("$MODULE_NAME/$TB_NAME/".($action == 'index'?'create':$action))?>" role="form" novalidate="novalidate">
    <div class="modal-body inline_form">

    <fieldset>
        <legend>  بيانات السند </legend>

        <div class="form-group col-sm-4">
            <label class="col-sm-5 control-label"> تاريخ السند </label>
            <div class="col-sm-6">
                <input type="text" name="class_input_date"  data-type="date"  data-date-format="DD/MM/YYYY"  data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?= date_format_exp() ?>" data-val="true"  data-val-required="حقل مطلوب"  id="txt_class_input_date" class="form-control ">
                <span class="field-validation-valid" data-valmsg-for="class_input_date" data-valmsg-replace="true"></span>
                <input type="hidden" data-val="true"    data-val-required="حقل مطلوب" name="class_input_id" id="txt_class_input_id" class="form-control">

            </div></div>
        <div class="form-group col-sm-4">
            <label class="col-sm-5 control-label">رقم محضر الفحص و الاستلام</label>
            <div class="col-sm-6">
                <input type="text" data-val="true"   name="order_id_text" id="txt_order_id_text" class="form-control" dir="rtl">
            </div>
        </div>
        <div class="form-group col-sm-4">
            <label class="col-sm-5 control-label"> رقم أمر التوريد </label>
            <div class="col-sm-6">
                <input type="text" data-val="true"   name="order_id" id="txt_order_id" class="form-control" dir="rtl">
                <span class="field-validation-valid" data-valmsg-for="order_id" data-valmsg-replace="true"></span>
                <input type="hidden" data-val="true"  name="record_id" id="txt_record_id" class="form-control" dir="rtl">
                <input type="hidden" data-val="true"  name="year_order" id="txt_year_order" class="form-control" dir="rtl">
                <input type="hidden" data-val="true" name="invoice_inbox" id="txt_invoice_inbox" class="form-control" dir="rtl">

            </div>
        </div>
        <div class="form-group col-sm-4">
            <label class="col-sm-5 control-label">المخزن</label>
            <div class="col-sm-6">
                <select name="store_id" style="width: 250px" id="dp_store_id">
                    <option></option>
                    <?php foreach($stores as $row) :?>
                        <option value="<?= $row['STORE_ID'] ?>"><?= $row['STORE_NO'].":".$row['STORE_NAME'] ?></option>
                    <?php endforeach; ?>
                </select>
                <span class="field-validation-valid" data-valmsg-for="store_id" data-valmsg-replace="true"></span>

            </div></div>
        <div class="form-group col-sm-4">
            <label class="col-sm-5 control-label">المورد</label>
            <div class="col-sm-6">


                    <input name="customer_name" data-val="true" readonly    class="form-control" readonly id="txt_customer_name" value=""   >
                    <input type="hidden" name="customer_resource_id" value="" id="h_txt_customer_name">
                    <span class="field-validation-valid" data-valmsg-for="customer_name" data-valmsg-replace="true"></span>
                    <input type="hidden" name="class_input_case" value="" id="txt_class_input_case">
                    <input type="hidden" name="invoice_case" value="" id="txt_invoice_case">
                </div>

        </div>
        <div class="form-group col-sm-4">
            <label class="col-sm-5 control-label"> نوع سند الإدخال </label>
            <div class="col-sm-6">
                <select name="class_input_type" style="width: 250px" id="dp_store_id">
                    <option></option>
                    <?php foreach($class_input_type as $row) :?>
                        <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                    <?php endforeach; ?>
                </select>
                <span class="field-validation-valid" data-valmsg-for="store_id" data-valmsg-replace="true"></span>

            </div></div>



        <div class="form-group col-sm-12">
            <label class="col-sm-4 control-label">بيان سند الإدخال</label>
            <div class="col-sm-8">
                <input type="text" data-val="true"  name="declaration" id="txt_declaration" class="form-control" dir="rtl">

            </div>
        </div>



    </fieldset>
    <hr/>
    <fieldset id="invoice_tb">
        <legend>بيانات الفاتورة</legend>


        <div style="clear: both; ">
            <hr/>
            <div class="form-group col-sm-3">
                <label class="col-sm-5 control-label">رقم فاتورة الشراء</label>
                <div class="col-sm-6">
                    <input type="text" data-val="true"  data-val-required="حقل مطلوب" name="invoice_id" id="txt_invoice_id" class="form-control" dir="rtl">
                    <span class="field-validation-valid" data-valmsg-for="invoice_id" data-valmsg-replace="true"></span>
                </div>
            </div>

            <div class="form-group col-sm-3">
                <label class="col-sm-5 control-label">تاريخ فاتورة الشراء</label>
                <div class="col-sm-5">
                    <input type="text" name="invoice_date"  data-type="date"  data-date-format="DD/MM/YYYY"  data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?= date_format_exp() ?>" data-val="true"  data-val-required="حقل مطلوب"  id="txt_invoice_date" class="form-control ">
                    <span class="field-validation-valid" data-valmsg-for="invoice_date" data-valmsg-replace="true"></span>

                </div>
            </div>

            <div class="form-group col-sm-3">
                <label class="col-sm-4 control-label">  العملة  </label>
                <div class="col-sm-3">
                    <select  name="curr_id" id="dp_curr_id"  data-curr="false"  class="form-control">
                        <?php foreach($currency as $row) :?>
                            <option  data-val="<?= $row['VAL'] ?>"  value="<?= $row['CURR_ID'] ?>"><?= $row['CURR_NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group col-sm-3">
                <label class="col-sm-3 control-label"> سعر العملة</label>
                <div class="col-sm-3">
                    <input type="text" name="curr_value" readonly  data-val-regex="المدخل غير صحيح!" data-val-regex-pattern="<?= float_format_exp() ?>" data-val="true" value=""   id="txt_curr_value" class="form-control ">

                </div>
            </div>



        </div>

        <hr/>

        <fieldset>
            <legend> الخصم المكتسب</legend>


            <div style="clear: both; ">
                <hr/>
                <div class="form-group col-sm-3">
                    <label  class="col-sm-4 control-label">   نوع الخصم  </label>
                    <div class="col-sm-3">
                        <select  name="descount_type" id="dp_descount_type"  data-curr="false"  class="form-control">
                            <option   value="1">مبلغ</option>
                            <option   value="2">نسبة</option>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-3">
                    <label class="col-sm-5 control-label">قيمة الخصم  </label>
                    <div class="col-sm-5">
                        <input type="text"  value="0" data-val="true"  data-val-required="حقل مطلوب" name="descount_value" id="txt_descount_value" class="form-control" dir="rtl">
                        <span class="field-validation-valid" data-valmsg-for="descount_value" data-valmsg-replace="true"></span>

                    </div>
                </div>



        </fieldset>
        <hr/>

        <fieldset>
            <legend> الضريبة</legend>


            <div style="clear: both; ">
                <hr/>

                <div style="clear: both; ">
                    <hr/>
                    <div class="form-group col-sm-5">
                        <label class="col-sm-4 control-label">الأسعار تشمل ض.ق.م </label>
                        <div class="col-sm-3">
                            <select  name="vat_type" id="dp_vat_type"  data-curr="false"  class="form-control">
                                <option   value="1">نعم</option>
                                <option   value="2">لا</option>


                            </select>
                        </div>
                    </div>

                    <div class="form-group col-sm-3">
                        <label class="col-sm-5 control-label">رقم حساب الضريبة </label>
                        <div class="col-sm-5">
                            <input type="text"  value="<?=$vat_account_id?>" data-val="true"  name="vat_account_id" id="txt_vat_account_id" class="form-control" dir="rtl" readonly>

                        </div>
                    </div>

                    <div class="form-group col-sm-3">
                        <label class="col-sm-5 control-label">قيمة الضريبة</label>
                        <div class="col-sm-5">
                            <input type="text"  value="<?=$vat_value?>" data-val="true" name="vat_value" id="txt_vat_value" class="form-control" dir="rtl" readonly>

                        </div>
                    </div>

        </fieldset>
        <hr/>
    <fieldset>
        <legend> بيانات الأصناف </legend>


        <div style="clear: both;">
            <?php
            echo modules::run('stores/stores_class_input/public_get_details_invoice',(count($receipt_data))?$receipt_data['CLASS_INPUT_ID']:0);

            ?>

        </div>
    </fieldset>

    <hr/>
    </fieldset>




    <hr/>
    <div class="modal-footer">

        <?php if((HaveAccess($create_url)) ||( HaveAccess($edit_url)) ) echo "<button type='submit' id='sub' data-action='submit' class='btn btn-primary'>حفظ البيانات</button>";
        /*   if(HaveAccess($adopt_url))  echo "<button type='button' id='adopt' onclick='{$TB_NAME}_adopt();' class='btn btn-primary' data-dismiss='modal'>اعتماد الإرسالية</button> ";
           if(HaveAccess($record_url)) echo " <button type='button' id='recordd' onclick='{$TB_NAME}_record(this);' class='btn btn-primary' data-dismiss='modal'>حفظ محضر الفحص والاستلام</button>";
           if(HaveAccess($return_url)) echo "<button type='button' id='returnn' onclick='{$TB_NAME}_return(1);' class='btn btn-primary' data-dismiss='modal'>  إرجاع  </button> "; */?>
        <?php if ((HaveAccess($create_url)) and $action=='index'): ?>
            <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>
        <?php   endif; ?>

    </div></div>
    </form>
    </div>

    </div> <!-- /.modal-content -->


<?php
$shared_js = <<<SCRIPT
currency_value();
var count = 0;

  var class_unit_json= {$class_unit};
    var select_class_unit= '';

  count = $('input[name="h_class_id[]"]').length;

   $.each(class_unit_json, function(i,item){
        select_class_unit += "<option value='"+item.CON_NO+"' >"+item.CON_NAME+"</option>";
    });

    $('select[name="unit_class_id[]"]').append(select_class_unit);
    $('select[name="unit_class_id[]"]').each(function(){
        $(this).val($(this).attr('data-val'));
    });
   $('select[name="unit_class_id[]"]').select2();



$(document).ready(function() {

    $('#dp_store_id').select2().on('change',function(){

        //     checkBoxChanged();
    });

if ($('#txt_class_input_case').val()!=2){
        $('#txt_order_id_text').bind("focus",function(e){

            _showReport('$orders_url/'+$(this).attr('id')+'/1');

        });

        $('#txt_order_id_text').click(function(e){

            _showReport('$orders_url/'+$(this).attr('id')+'/1');

        });

    } //end if
   $('#txt_vat_value,#txt_descount_value').keyup(function(){

            calcTotalwithTax();

        });

        $('#dp_descount_type').change(function(){
            calcTotalwithTax();
        });
$('input[name="price_class_id[]"]').bind("keyup",function(e){
 calcTotalwithTax();
            });


$('input[name="taxable_det[]"]').bind("change",function(e){
 calcTotalwithTax();
            });


  $('#dp_curr_id').change(function(){currency_value();});

});


       function currency_value(){
              $('#txt_curr_value').val($('#dp_curr_id').find(':selected').attr('data-val'));
       }

     function calcPrice(){

           $('#dp_vat_type,select[name="taxable_det[]"]').change(function(){

                calcTotalwithTax();

           });


       }
   function calcTotalwithTax(){
                var vat_type = $('#dp_vat_type').val();
                var tax = $('#txt_vat_value').val();
                var TotalTax = 0;
                var AllTotal = 0;

                var TotalWithVat = 0;
                var TotalWithOutVat = 0;

                $('select[name="taxable_det[]"]').each(function(i){
                      var taxable_det = $(this).val();

                      var count = $(this).closest('tr').find('input[name="amount[]"]').val();
                      var price = $(this).closest('tr').find('input[name="price_class_id[]"]').val();

                      var total = count * price;


                      if(vat_type == 1 && taxable_det == 1){
                        TotalTax += (total * (tax/100))/(1+ (tax /100));
                        total = total - (total * (tax/100))/(1+ (tax /100));

                        TotalWithVat += total;

                      }else if(vat_type == 2 && taxable_det == 1)
                      {

                        TotalTax += (total * (tax/100));
                      }else{
                             TotalWithOutVat +=total;
                      }

                      AllTotal +=total;

                     $(this).closest('tr').find('input[name="price[]"]').val(parseFloat(total).toFixed(2));

                });




                 var discount = $('#txt_descount_value').val();
                 discount = discount == '' ?0 : discount;

                 if($('#dp_descount_type').val() == 2){
                    setDiscountTitle(discount+'%');
                    discount  = AllTotal * (discount/100);

                 }else {
                    setDiscountTitle(discount);
                 }

                 discount=parseFloat(discount).toFixed(2)

                if(discount > 0)
                 TotalTax =TotalTax -((TotalWithVat/AllTotal/100) *  discount * tax );


                 $('#inv_total').text(parseFloat(AllTotal).toFixed(2))
                 $('#inv_tax').text(parseFloat(TotalTax).toFixed(2));
                 $('#inv_nettotal').text(parseFloat((AllTotal+TotalTax-discount)).toFixed(2));
                 if(discount > 0)
                      $('#inv_discount').text(parseFloat(discount).toFixed(2));
                 else
                     $('#inv_discount').text('');

                      $('#inv_after_discount').text(parseFloat((AllTotal - discount)).toFixed(2));

       }

        function setDiscountTitle(val){
           $('#inv_discount_per').text('نسبة الخصم '+val);
        }



$('button[data-action="submit"]').click(function(e){
    e.preventDefault();
    var tbl = '#{$TB_NAME}_tb';
    var container = $('#' + $(tbl).attr('data-container'));
    var form = $(this).closest('form');
    ajax_insert_update(form,function(data){
    if(data==1){
            success_msg('رسالة','تم حفظ البيانات بنجاح ..');
            get_to_link(window.location.href);
        }else{
            danger_msg('تحذير..',data);
        }



    },"html");
});

function stores_class_input_detail_tb_delete(td,id){
    if(confirm('هل تريد الحذف ؟')){
        ajax_delete_any('$delete_details_url',{id:id},function(data){
            if(data == '1'){
                $(td).closest('tr').remove();
                success_msg('رسالة','تم حذف القيد بنجاح ..');
            }
        });
    }
}
function reBind(s){
  $('#dp_curr_id').change(function(){currency_value();});

  if(s==undefined)
            s=0;
       $('input[name="class_id[]"]').bind("focus",function(e){

        _showReport('$select_items_url/'+$(this).attr('id'));
    });

        if(s){
            $('select#unit_class_id_'+count).append('<option></option>'+select_class_unit).select2();
        }

calcPrice();

}

reBind();



function addRow(){

//if($('input',$('#stores_class_input_detailTbl')).filter(function() { return this.value == ""; }).length <= 0){

    var html ='<tr><td>'+ (count+1)+' <input type="hidden" value="0" name="h_ser[]" id="h_ser_'+count+'" class="form-control col-sm-16" ></td>'+
    '<td><input name="class_id[]"   data-val="true"  data-val-required="حقل مطلوب"   id="class_id_'+count+'" readonly class="form-control col-sm-16">'+
    '<input type="hidden" name="h_class_id[]"   data-val="true"  data-val-required="حقل مطلوب"   id="h_class_id_'+count+'"   class="form-control col-sm-16"></td>'+
     '<td><select name="unit_class_id[]" class="form-control" id="unit_class_id_'+count+'" /></select></td>'+
     '<td><input name="amount[]"   data-val="true"  id="amount_'+count+'"    data-val-required="حقل مطلوب"   data-val-regex="المدخل غير صحيح!"   class="form-control" > </td>'+
     '<td><input name="price_class_id[]"   data-val="true"  id="price_class_id_'+count+'"    data-val-required="حقل مطلوب"   data-val-regex="المدخل غير صحيح!"   class="form-control" > </td>'+
         '<td ><input name="vat[]"value="0" readonly  type="text" id="vat_'+count+'"  class="form-control"></td>'+

      '<td><input name="price[]" type="text" value="0" readonly id="price_'+count+'"  class="form-control" readonly></td><td ></td></tr>';

   $('#stores_class_input_detailTbl tbody').append(html);




    reBind(1);

 count = count+1;


//  }
}


SCRIPT;







    if(HaveAccess($edit_url) and $action=='edit'){


            $edit_script = <<<SCRIPT


<script>
  {$shared_js}

  $('#txt_class_input_id').val('{$receipt_data['CLASS_INPUT_ID']}');
        $('#txt_class_input_date').val('{$receipt_data['CLASS_INPUT_DATE']}');
        $('#txt_order_id').val( '{$receipt_data['ORDER_ID']}');
        $('#dp_store_id').val( '{$receipt_data['STORE_ID']}');
        $('#h_txt_customer_name').val( '{$receipt_data['CUSTOMER_RESOURCE_ID']}');
        $('#txt_customer_name').val( '{$receipt_data['CUST_NAME']}');
        $('#txt_class_input_case').val( '{$receipt_data['CLASS_INPUT_CASE']}');


        $('#txt_record_id').val( '{$receipt_data['RECORD_ID']}');


         $('#txt_order_id_text').val( '{$receipt_data['RECEORD_ID_TEXT']}');




            $('#dp_class_input_class_type').val( '{$receipt_data['CLASS_INPUT_CLASS_TYPE']}');
  $('#dp_class_input_type').val( '{$receipt_data['CLASS_INPUT_TYPE']}');
  $('#txt_declaration').val( '{$receipt_data['DECLARATION']}');
  $('#txt_invoice_id').val( '{$receipt_data['INVOICE_ID']}');
$('#txt_invoice_date').val( '{$receipt_data['INVOICE_DATE']}');
$('#txt_invoice_date').val( '{$receipt_data['INVOICE_DATE']}');
$('#txt_invoice_case').val( '{$receipt_data['INVOICE_CASE']}');
$('#dp_curr_id').val( '{$receipt_data['CURR_ID']}');
$('#txt_curr_value').val( '{$receipt_data['CURR_VALUE']}');
$('#dp_descount_type').val( '{$descount_type}');
$('#txt_curr_value').val( '{$receipt_data['CURR_VALUE']}');
$('#txt_descount_value').val( '{$receipt_data['DESCOUNT_VALUE']}');
$('#dp_vat_type').val( '{$vat_type}');

//$('#txt_vat_account_id').val( '{$receipt_data['VAT_ACCOUNT_ID']}');
//$('#txt_vat_value').val( '{$receipt_data['VAT_VALUE']}');



$('#txt_year_order').val( '{$receipt_data['YEAR_ORDER']}');
$('#txt_invoice_inbox').val( '{$receipt_data['INVOICE_INBOX']}');


function {$TB_NAME}_case(){
if ($('#txt_invoice_case').val()==3){
  success_msg('الطلب معتمد','تحذير');
}else{
    if(confirm('هل تريد إتمام العملية ؟')){

var c=$('#txt_invoice_case').val();
    get_data('{$case_url}',{id:{$receipt_data['CLASS_INPUT_ID']},case:c},function(data){
      if(data =='1')
      console.log(data);
                      success_msg('رسالة','تمت العملية بنجاح');
                      setTimeout(function(){

                  window.location.reload();

                }, 1000);
            },'html');

}
}


}

calcTotalwithTax();


</script>
SCRIPT;
            sec_scripts($edit_script);

        }



?>