<?php
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 08/12/14
 * Time: 12:41 م
 */
$MODULE_NAME= 'stores';
$TB_NAME= 'invoice_class_input';
$TB_NAME2= 'invoice_class_input_detail';

$get_url =base_url("$MODULE_NAME/$TB_NAME/get_id");
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$edit_url =base_url("$MODULE_NAME/$TB_NAME/get_id");
$delete_url =base_url("$MODULE_NAME/$TB_NAME/delete");
$update_case_url=base_url("$MODULE_NAME/$TB_NAME/update_case");
$case_url=base_url("$MODULE_NAME/$TB_NAME/update_case");
$back_case_url=base_url("$MODULE_NAME/$TB_NAME/back_case");
/*$record_url=base_url("$MODULE_NAME/$TB_NAME/record");
$return_url=base_url("$MODULE_NAME/$TB_NAME/returnp");*/
//$get_details_url =base_url("$MODULE_NAME/$TB_NAME2/get_page");
$customer_url =base_url('payment/customers/public_index');
$orders_url =base_url('stores/receipt_class_input/public_index');
$delete_details_url=base_url("$MODULE_NAME/$TB_NAME2/delete");
$select_items_url=base_url("$MODULE_NAME/classes/public_index");
$get_class_url =base_url('stores/classes/public_get_id');
$select_accounts_url =base_url('financial/accounts/public_select_accounts');
//$back_url=base_url('payment/financial_payment/'.$action);
//$back_url=base_url("$MODULE_NAME/$TB_NAME/$action");
$back_url=base_url("$MODULE_NAME/$TB_NAME/index");
$financial_chain_url=base_url("financial/financial_chains/get");
$stores_page= base_url("$MODULE_NAME/stores_class_input/public_index_invoice");
$has_other_invoice_url= base_url("$MODULE_NAME/$TB_NAME/public_has_other_invoice");

if ($action=='index')
{

    if ($class_input_seqs =='') $receipt_data= array();
    $class_input_id='';
    $class_input_id_val=0;

    $vat_account_id=$vat_account_id_c;
    $vat_value=$vat_value_c;
    $invoice_case=1;
    $case=0;
    $descount_type=1;
    $count_stores=0;
    $financial_chain=0;
    $descount_value=0;
    $descount_value=0;

    $class_input_typec=isset($receipt_data['CLASS_INPUT_TYPE'])? $receipt_data['CLASS_INPUT_TYPE']:'';
    $class_input_date=isset($receipt_data['CLASS_INPUT_DATE'])? $receipt_data['CLASS_INPUT_DATE']:'';
    $store_id=isset($receipt_data['STORE_ID'])? $receipt_data['STORE_ID']:'';
    $customer_resource_id=isset($receipt_data['CUSTOMER_RESOURCE_ID'])? $receipt_data['CUSTOMER_RESOURCE_ID']:'';
    $cust_name=isset($receipt_data['CUST_NAME'])? $receipt_data['CUST_NAME']:'';
    $customer_account_type=isset($receipt_data['CUSTOMER_ACCOUNT_TYPE'])? $receipt_data['CUSTOMER_ACCOUNT_TYPE']:'';
    $account_type_list=isset($receipt_data['ACCOUNT_TYPE_LIST'])? $receipt_data['ACCOUNT_TYPE_LIST']:-1;
    $declaration=isset($receipt_data['DECLARATION'])? $receipt_data['DECLARATION']:'';
    $account_type=isset($receipt_data['ACCOUNT_TYPE'])? $receipt_data['ACCOUNT_TYPE']:'';
 $class_input_typex =isset($receipt_data['CLASS_INPUT_TYPE'])? $receipt_data['CLASS_INPUT_TYPE']:'';





} else if ($action=='edit'){
    $class_input_seqs='';
    $class_input_id=isset($receipt_data['CLASS_INPUT_ID'])? $receipt_data['CLASS_INPUT_ID']:'';
    $class_input_id_val=isset($receipt_data['CLASS_INPUT_ID'])? $receipt_data['CLASS_INPUT_ID']:0;
    $vat_account_id=(isset($receipt_data['VAT_ACCOUNT_ID'])&&($receipt_data['VAT_ACCOUNT_ID']!=''))? $receipt_data['VAT_ACCOUNT_ID'] :$vat_account_id_c;
    $vat_value=(isset($receipt_data['VAT_VALUE'])&&($receipt_data['VAT_VALUE']!=''))? $receipt_data['VAT_VALUE'] :$vat_value_c;
    $vat_type=isset($receipt_data['VAT_TYPE'])? $receipt_data['VAT_TYPE']:1;
    $descount_type=isset($receipt_data['DESCOUNT_TYPE'])? $receipt_data['DESCOUNT_TYPE']:1;
    $invoice_case=isset($receipt_data['INVOICE_CASE'])? $receipt_data['INVOICE_CASE']:1;
    $count_stores=isset($receipt_data['COUNT_STORES'])? $receipt_data['COUNT_STORES']:0;
    $financial_chain=(isset($receipt_data['FINANCIAL_CHAIN']) )? $receipt_data['FINANCIAL_CHAIN'] :0;
    $descount_value=(isset($receipt_data['DESCOUNT_VALUE']) )? $receipt_data['DESCOUNT_VALUE'] :0;
    $account_type=isset($receipt_data['ACCOUNT_TYPE'])? $receipt_data['ACCOUNT_TYPE']:'';
    $class_input_typex =isset($receipt_data['CLASS_INPUT_TYPE'])? $receipt_data['CLASS_INPUT_TYPE']:'';
    $store_id=isset($receipt_data['STORE_ID'])? $receipt_data['STORE_ID']:'';
    $customer_account_type=isset($receipt_data['CUSTOMER_ACCOUNT_TYPE'])? $receipt_data['CUSTOMER_ACCOUNT_TYPE']:'';





}
$HaveRs = count($receipt_data) > 0;
if ($class_input_seqs !='' || $count_stores>0)
$fromstorec=true;
else
    $fromstorec=false;
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
    <?php  if  ($count_stores==0) { ?>
        <div style="color: #ff0000"> ملاحظة: عند إضافة فاتورة شراء مباشر..عليك عمل تسوية صادر للأصناف</div>
    <?php } ?>
    <form class="form-vertical" id="<?=$TB_NAME?>_form" method="post" action="<?=base_url("$MODULE_NAME/$TB_NAME/".($action == 'index'?'create':$action))?>" role="form" novalidate="novalidate">
    <div class="modal-body inline_form" >

    <fieldset  >
        <legend>  بيانات السند </legend>

        <div class="form-group col-sm-4">
            <label class="col-sm-5 control-label"> تاريخ سند الإدخال </label>
            <div class="col-sm-6">
                <input type="hidden" name="class_input_seqs"   value="<?=$class_input_seqs?>"   id="txt_class_input_seqs" >

                <input <?php if ($fromstorec==true) echo "readonly"; ?> type="text" name="class_input_date"  data-type="date"  data-date-format="DD/MM/YYYY"  data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?= date_format_exp() ?>" data-val="false"  data-val-required="حقل مطلوب"  id="txt_class_input_date" class="form-control ">
                <span class="field-validation-valid" data-valmsg-for="class_input_date" data-valmsg-replace="true"></span>
                <input type="hidden" data-val="true"    data-val-required="حقل مطلوب" name="class_input_id" id="txt_class_input_id" class="form-control">

            </div></div>
       <!-- <div class="form-group col-sm-4">
            <?php  if ($action=='edit'){  ?> <label class="col-sm-5 control-label">رقم محضر الفحص و الاستلام</label> <?php } ?>
            <div class="col-sm-6">
                <input type="<?php  if ($action=='index') echo 'hidden'; else  if ($action=='edit') echo 'text';?>" data-val="true"   name="order_id_text" id="txt_order_id_text" class="form-control" dir="rtl">
            </div>
        </div>-->
      <!--  <div class="form-group col-sm-4">
            <label class="col-sm-5 control-label"> رقم أمر التوريد </label>
            <div class="col-sm-6">
                <input type="text" data-val="true"   name="order_id" id="txt_order_id" class="form-control" dir="rtl">
                <span class="field-validation-valid" data-valmsg-for="order_id" data-valmsg-replace="true"></span>

            </div>
        </div>-->
        <div class="form-group col-sm-4">
            <label class="col-sm-5 control-label">المخزن</label>
            <div class="col-sm-6">
                <input type="hidden" data-val="true"   name="order_id_text" id="txt_order_id_text" class="form-control" dir="rtl">

                <input type="hidden" data-val="true"   name="order_id" id="txt_order_id" class="form-control" dir="rtl">

                <input type="hidden" data-val="true"  name="record_id" id="txt_record_id" class="form-control" dir="rtl">
                <input type="hidden" data-val="true"  name="year_order" id="txt_year_order" class="form-control" dir="rtl">
                <input type="hidden" data-val="true" name="invoice_inbox" id="txt_invoice_inbox" class="form-control" dir="rtl">

                <select name="store_id" style="width: 250px" id="dp_store_id" data-val="true"  data-val-required="حقل مطلوب" >
                    <?php if  ($fromstorec){
                        foreach($stores as $row) :
                            if ($store_id ==$row['STORE_ID']){?>
                                <option value="<?= $row['STORE_ID'] ?>"><?= $row['STORE_NO'].":".$row['STORE_NAME'] ?></option>
                            <?php }
                        endforeach;
                    } else{?>
                        <option></option>
                        <?php foreach($stores as $row) :?>
                            <option value="<?= $row['STORE_ID'] ?>"><?= $row['STORE_NO'].":".$row['STORE_NAME'] ?></option>
                        <?php endforeach; ?> <?php } ?>

                </select>
                <span class="field-validation-valid" data-valmsg-for="store_id" data-valmsg-replace="true"></span>

            </div></div>

        <div class="form-group col-sm-4">
            <label class="col-sm-5 control-label">نوع المستفيد</label>
            <div class="col-sm-6">
                <select name="account_type"  id="dp_account_type" class="form-control" data-val="true"  data-val-required="حقل مطلوب">
                    <?php if  ($fromstorec){
                        foreach($customer_type as $row) :
                            if ($account_type ==$row['CON_NO']){?>
                                <option  value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>";
                            <?php }
                        endforeach;
                    } else{ ?>
                        <?php foreach($customer_type as $row) :?>
                           <option <?= $HaveRs?($receipt_data['ACCOUNT_TYPE'] ==$row['CON_NO']?'selected':''):'' ?> value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option> -->
                        <?php endforeach; ?> <?php } ?>
                     <!--  <option  value="2"><?= "مستفيد" ?></option>-->

                </select>
                <span class="field-validation-valid" data-valmsg-for="account_type" data-valmsg-replace="true"></span>

            </div>

        </div>
        <div class="form-group col-sm-4">
            <label class="col-sm-5 control-label">المورد</label>
            <div class="col-sm-6">
                <input name="customer_name" data-val="true"     class="form-control" readonly id="txt_customer_name" value=""   >
                <input type="hidden" name="customer_resource_id" value="" id="h_txt_customer_name">
                <input type="hidden" name="customer_id_type" value="" id="h_txt_customer_id_type">
                <span class="field-validation-valid" data-valmsg-for="customer_name" data-valmsg-replace="true"></span>
                <input type="hidden" name="class_input_case" value="" id="txt_class_input_case">
                <input type="hidden" name="invoice_case" value="" id="txt_invoice_case">
                <input type="hidden" name="count_stores" value="<?=$count_stores?>" id="txt_count_stores">
                <input type="hidden" name="financial" value="<?=$financial_chain?>" id="txt_financial">

            </div>

        </div>

        <div class="form-group col-sm-4" style="display: <?php  if ($customer_account_type!='') echo "block"; else echo "none"; ?>" id="div_customer_account_type">
            <label class="col-sm-5 control-label"> نوع حساب المستفيد </label>
            <div class="col-sm-6">
                <select class="form-control" id="db_customer_account_type"  name="customer_account_type">
                    <option></option>
                    <?php foreach ($ACCOUNT_TYPES as $_row) : ?>

                        <option <?= $HaveRs ? ($customer_account_type == $_row['ACCCOUNT_TYPE'] ? 'selected' : '') : '' ?>
                            style="display: none;"
                            value="<?= $_row['ACCCOUNT_TYPE'] ?>"><?= $_row['ACCCOUNT_NO_NAME'] ?></option>

                    <?php endforeach; ?>
                </select>
            </div>
        </div>


        <div class="form-group col-sm-4">
            <label class="col-sm-5 control-label"> نوع سند الإدخال </label>
            <div class="col-sm-6">
                <select name="class_input_type"  id="dp_class_input_type" class="form-control" data-val="false"  data-val-required="حقل مطلوب">
                    <?php if  ($fromstorec){
                        foreach($class_input_type as $row) :
                            if ($class_input_typex ==$row['CON_NO']){?>
                                <option  value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>";
                            <?php }
                        endforeach;
                    } else{?>
                        <option></option>
                        <?php foreach($class_input_type as $row) :?>
                            <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                        <?php endforeach; ?> <?php } ?>
                </select>
                <span class="field-validation-valid" data-valmsg-for="class_input_type" data-valmsg-replace="true"></span>
            </div>
        </div>

         <?php if ($financial_chain !=0) { ?>
            <div class="form-group col-sm-4">
                <label class="col-sm-5 control-label">  رقم القيد المالي  </label>
                <div class="col-sm-6">
                     <a target="_blank" href="<?php //if (HaveAccess($financial_chain_url))
                     echo $financial_chain_url."/".$financial_chain."/index?type=4"; ?>"><?=$financial_chain?></a>
                </div>
            </div>
        <?php } ?>
<?php if ($count_stores>0) { ?>
        <div class="form-group col-sm-4">

            <div class="col-sm-6">
               <label class="col-sm-5 control-label"> <a href="javascript:;"  onclick="_showReport('<?=$stores_page?>?invoice_seq='+$('#txt_class_input_id').val() );"> سندات الإدخال</a></label>
            </div>
        </div>
<?php } ?>
        <div class="form-group col-sm-12">
            <label class="col-sm-4 control-label">ملاحظات الفاتورة </label>
            <div class="col-sm-8">
                <input type="text" data-val="true"  name="declaration" id="txt_declaration" class="form-control" dir="rtl">

            </div>
        </div>




    </fieldset><hr/>
    <fieldset id="invoice_tb" >
        <legend>بيانات الفاتورة</legend>


        <div style="clear: both; ">
            <hr/>
            <div class="form-group col-sm-3">
                <label class="col-sm-5 control-label">رقم فاتورة الشراء</label>
                <div class="col-sm-6">
                    <input type="text"  data-val="true"  data-val-required="حقل مطلوب" name="invoice_id" id="txt_invoice_id" class="form-control" dir="rtl">
                    <span class="field-validation-valid" data-valmsg-for="invoice_id" data-valmsg-replace="true"></span>
                </div>
            </div>

            <div class="form-group col-sm-4">
                <label class="col-sm-5 control-label">تاريخ فاتورة الشراء</label>
                <div class="col-sm-5">
                    <input type="text" name="invoice_date"  data-type="date"  data-date-format="DD/MM/YYYY"  data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?= date_format_exp() ?>" data-val="true"  data-val-required="حقل مطلوب"  id="txt_invoice_date" class="form-control ">
                    <span class="field-validation-valid" data-valmsg-for="invoice_date" data-valmsg-replace="true"></span>

                </div>
            </div>
            <div class="form-group col-sm-4">
                <label class="col-sm-5 control-label"> التاريخ الثاني </label>
                <div class="col-sm-5">
                    <input type="text" name="invoice_date2"  data-type="date"  data-date-format="DD/MM/YYYY"  data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?= date_format_exp() ?>" data-val="false"  data-val-required="حقل مطلوب"  id="txt_invoice_date2" class="form-control ">
                    <span class="field-validation-valid" data-valmsg-for="invoice_date2" data-valmsg-replace="true"></span>

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
                    <input type="text" name="curr_value" readonly  data-val-regex="المدخل غير صحيح!" data-val-regex-pattern="<?= float_format_exp() ?>" data-val="true" value=""  data-val-required="حقل مطلوب"  id="txt_curr_value" class="form-control ">
                    <span class="field-validation-valid" data-valmsg-for="curr_value" data-valmsg-replace="true"></span>
                </div>
            </div>



        </div>

        <hr/>

        <fieldset>
            <legend> الخصم المكتسب</legend>


            <div style="clear: both; ">
                <hr/>
                <div class="form-group col-sm-3">
                    <label class="col-sm-4 control-label">  نوع الخصم  </label>
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
                        <input type="text"  value="0" data-val="false"  data-val-required="حقل مطلوب" name="descount_value" id="txt_descount_value" class="form-control" dir="rtl">
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
                    <div class="form-group col-sm-3">
                        <label class="col-sm-4 control-label"> الأسعار تشمل ض.ق.م </label>
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
                        <label class="col-sm-5 control-label">نسبة الضريبة</label>
                        <span style="color: #FF0000;bold">%</span>
                        <div class="col-sm-3">
                            <input type="text"  max="100"  value="<?=$vat_value?>" data-val="true"   data-val-required="حقل مطلوب"  name="vat_value" id="txt_vat_value" class="form-control" dir="rtl" >
                            <span class="field-validation-valid" data-valmsg-for="vat_value" data-valmsg-replace="true"></span>
                        </div>
                    </div>

        </fieldset>
    </fieldset>




    <hr/>

    <fieldset>
        <legend> بيانات الأصناف </legend>


        <div style="clear: both;">
            <input type="hidden" id="h_data_search" />
            <?php
            if ($action=='edit')
            echo modules::run('stores/invoice_class_input/public_get_details_invoice',(count($receipt_data))?$receipt_data['CLASS_INPUT_ID']:0);
else
    echo modules::run('stores/invoice_class_input/public_get_details_transfer',$class_input_seqs);

            ?>

        </div>
    </fieldset>

    <hr/> <hr/>

    <div class="modal-footer">

        <?php
//echo "ffff".$case."dddd".$invoice_case;
        if(((HaveAccess($create_url)) ||( HaveAccess($edit_url)&& $can_edit)) &&($invoice_case ==1) ) echo "<button type='submit' id='sub' data-action='submit' class='btn btn-primary'>حفظ البيانات</button>";
        if(HaveAccess($update_case_url) && HaveAccess($edit_url)&&($action=='edit')&&($invoice_case ==($case-1)) )  { echo "<button type='button' id='b_case' onclick='{$TB_NAME}_case(this);' class='btn btn-primary' data-dismiss='modal'>"; if($invoice_case ==1) echo "اعتماد"; else if($invoice_case ==2) echo "تدقيق"; else if($invoice_case ==3) echo "مدقق";    echo " </button> "; }
        if( HaveAccess($back_case_url) && HaveAccess($edit_url)&&($action=='edit')&&($invoice_case == $case) && ($invoice_case !=1) )  { echo "<button type='button' id='b_case' onclick='{$TB_NAME}_back_case(this);' class='btn btn-primary' data-dismiss='modal'>";   if($invoice_case ==2) echo "إلغاء اعتماد"; else if($invoice_case ==3) echo "إلغاء تدقيق";    echo " </button> "; }

        /*   if(HaveAccess($record_url)) echo " <button type='button' id='recordd' onclick='{$TB_NAME}_record(this);' class='btn btn-primary' data-dismiss='modal'>حفظ محضر الفحص والاستلام</button>";
           if(HaveAccess($return_url)) echo "<button type='button' id='returnn' onclick='{$TB_NAME}_return(1);' class='btn btn-primary' data-dismiss='modal'>  إرجاع  </button> "; */?>
        <?php if ((HaveAccess($create_url)) and $action=='index'): ?>
            <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>
        <?php   endif; ?>

    </div>
    <div style="clear: both;">
        <?php echo $HaveRs?  modules::run('attachments/attachment/index',$class_input_id,'invoice') : ''; ?>
    </div>
    </div>
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
///////////////////////
function setDefaultCustomerAccount(){
$('#db_customer_account_type').val(1);
}
///////////////////
  ///////////////////////////////////
 function chk_customer_account_type(){
        if( $('#dp_account_type').val()==2 ){
            $('#div_customer_account_type').show();
        }else{
            $('#div_customer_account_type').hide();
        }
    }


   ////////////////////////
    function selectAccount(obj){
    chk_customer_account_type();
         var _type =$('#dp_account_type').val();

            if(_type == 1)
                _showReport('$select_accounts_url/'+$(obj).attr('id') );
            if(_type == 2)
                _showReport('$customer_url/'+$(obj).attr('id')+'/1');

    }


$(document).ready(function() {
   $('#dp_store_id').select2().on('change',function(){

        //     checkBoxChanged();
    });

if( $('#txt_count_stores').val()>0){
$('input[name="h_class_id[]"]').prop("readonly",true);
$('input[name="amount[]"]').prop("readonly",true);
$('#txt_class_input_date').prop("readonly",true);
$('#txt_order_id').prop("readonly",true);
$('#txt_order_id_text').prop("readonly",true);
$('#dp_store_id').select2("readonly",true);
//$('#dp_class_input_type').prop("hidden",true);
//$('#class_input_type_label').prop("hidden",true);
}


if( $('#txt_count_stores').val()<=0){
     $('#txt_customer_name').bind("focus",function(e){

        selectAccount(this);

        });



        $('#txt_customer_name').click(function(e){

            selectAccount(this);

        });

if ($('#txt_class_input_case').val()!=2){
        $('#txt_order_id_text').bind("focus",function(e){

            _showReport('$orders_url/'+$(this).attr('id')+'/1');

        });

        $('#txt_order_id_text').click(function(e){

            _showReport('$orders_url/'+$(this).attr('id')+'/1');

        });

    } //end if
    }
   $('#txt_vat_value,#txt_descount_value').keyup(function(){

            calcTotalwithTax();

        });

        $('#dp_descount_type').change(function(){
            calcTotalwithTax();
        });
$('input[name="price_class_id[]"]').bind("keyup",function(e){
 calcTotalwithTax();
            });


$('input[name="taxable_det[]"],input[name="price_class_id[]"],input[name="amount[]"]').bind("change",function(e){
 calcTotalwithTax();
            });


  $('#dp_curr_id').change(function(){currency_value();});

});


       function currency_value(){
              $('#txt_curr_value').val($('#dp_curr_id').find(':selected').attr('data-val'));
       }


  $('#txt_invoice_id').change(
  function(){invoice_id_check();}
                                );




       function invoice_id_check(){

if ($('#txt_invoice_id').val()==0){
    danger_msg('تحذير..','لا يمكن إدراج رقم فاتورة صفر');
$('#txt_invoice_id').val('');
}else{
         if ($('#h_txt_customer_name').val()==''){
           danger_msg('تحذير..','يجب اختيار المورد أولا..');
$('#txt_invoice_id').val('');
             }else{

           get_data('{$has_other_invoice_url}',{customer_resource_id:$('#h_txt_customer_name').val(),invoice_id:$('#txt_invoice_id').val(),class_input_id:'{$class_input_id_val}'},function(data){
        if (data>0){
         if(!confirm(' يوجد فاتورة أخرى لهذا المورد بنفس رقم الفاتورة...هل تريد اكمال العملية؟')){
         $('#txt_invoice_id').val('');
         }
        }
           reBind();
            });
             }

}

       }


     function calcPrice(){

           $('#dp_vat_type,select[name="taxable_det[]"],input[name="price_class_id[]"],input[name="amount[],#txt_descount_value,#dp_descount_type"]').change(function(){

                calcTotalwithTax();

           });


       }
/*
  function calcTotalwithTax(){

             var tax = $('#txt_vat_value').val();
             var vat_type = $('#dp_vat_type').val();
             var AllTotal = 0;
             var ATotal = 0;
             var TotalWithVat = 0;
             var TotalWithOutVat = 0;
             $('select[name="taxable_det[]"]').each(function(i){
                      var vat_done = $(this).val();

                      var count = $(this).closest('tr').find('input[name="amount[]"]').val();

                   //   var price = $(this).closest('tr').find('input[name="unit_price[]"]').val();
                     var price = $(this).closest('tr').find('input[name="price_class_id[]"]').val();
                      var total =isNaNVal( count * price,2);

                      ATotal +=total;

                      if(vat_type == 1 && vat_done == 1){
                        total = isNaNVal(total/(1+(tax/100)),2)

                        TotalWithVat +=total;
                      }
                      else if(vat_type == 2 && vat_done == 1)
                      {
                        TotalWithVat +=total;
                        total =total;

                      }
                      else{
                        TotalWithOutVat +=total;
                      }

                    AllTotal += total;

                  //   $(this).closest('tr').find('td.v_balance').text(total);
                       $(this).closest('tr').find('input[name="price[]"]').val(parseFloat(total).toFixed(2));

                });

                 $('#inv_total').text( isNaNVal(AllTotal));

                     var discount = $('#txt_descount_value').val();
                     discount = discount == '' ?0 : discount;

                     if($('#dp_descount_type').val() == 2){
                        setDiscountTitle(discount+'%');
                        discount  = AllTotal * (discount/100);
                     }else {
                        setDiscountTitle(discount);
                     }


             if(vat_type == 1){
               if(discount <= 0)
                 TotalTax = ATotal - AllTotal;
               else
                 TotalTax =(ATotal - AllTotal) -  isNaNVal((discount) * tax / 100);


                console.log('',TotalTax);
                console.log('',ATotal);
                console.log('',AllTotal);

               if(TotalWithOutVat > 0){
                    TotalTax = isNaNVal((AllTotal - TotalWithOutVat ) * tax / 100);
                      console.log('',TotalTax);
                    TotalTax = TotalTax   - ( (discount * (1 - (TotalWithOutVat/AllTotal))) * tax /100 );

               }
             }else {
                  if(TotalWithVat > 0)
                    TotalTax =isNaNVal(((TotalWithVat) * tax / 100),2);
             }
                 $('#inv_tax').text(isNaNVal(TotalTax,2));

                 if(discount > 0)
                      $('#inv_discount').text(isNaNVal(discount,2));
                 else
                     $('#inv_discount').text('');

                 $('#inv_after_discount').text(isNaNVal((AllTotal - discount),2));
                 $('#inv_nettotal').text(isNaNVal((AllTotal+TotalTax-discount),2));

        }
*/
 function calcTotalwithTax(){
             var tax = $('#txt_vat_value').val();
             var vat_type = $('#dp_vat_type').val();
             var AllTotal = 0;
             var ATotal = 0;
             var TotalWithVat = 0;
             var TotalWithOutVat = 0;

             var TotalWithVat_tax = 0;
             var TotalWithOutVat_tax = 0

             $('select[name="taxable_det[]"]').each(function(i){
                      var vat_done = $(this).val();

                      var count = $(this).closest('tr').find('input[name="amount[]"]').val();
                    //  var price = $(this).closest('tr').find('input[name="unit_price[]"]').val();
                       var price = $(this).closest('tr').find('input[name="price_class_id[]"]').val();
                      var total =isNaNVal( count * price,2);
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

                   //  $(this).closest('tr').find('td.v_balance').text(total);
                    $(this).closest('tr').find('input[name="price[]"]').val(total);

                });

                 $('#inv_total').text( isNaNVal(AllTotal));

                     var discount = GET_DISCOUNT(TotalWithVat+TotalWithOutVat,
                                                 isNaNVal($('#txt_descount_value').val()),
                                                 isNaNVal($('#dp_descount_type').val())
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


                 $('#inv_tax').text(isNaNVal(TotalTax,2));

                 if(discount > 0)
                      $('#inv_discount').text(isNaNVal(discount,2));
                 else
                     $('#inv_discount').text('');

                 $('#inv_after_discount').text(isNaNVal((AllTotal - discount),2));
                 $('#inv_nettotal').text(isNaNVal((AllTotal+TotalTax-discount),2));
        }

        function setDiscountTitle(val){
           $('#inv_discount_per').text('نسبة الخصم '+val);
        }

$('button[data-action="submit"]').click(function(e){
    e.preventDefault();
    $(this).attr('disabled','disabled');
    var tbl = '#{$TB_NAME}_tb';
    var container = $('#' + $(tbl).attr('data-container'));
    var form = $(this).closest('form');
    ajax_insert_update(form,function(data){
    if(data==1){
           success_msg('رسالة','تم حفظ البيانات بنجاح ..');
           if('{$action}'=='edit')
            get_to_link(window.location.href);
            else
            get_to_link('{$create_url}');
        }else{
            danger_msg('تحذير..',data);
        }

    },"html");
    
    setTimeout(function() {
        $('button[data-action="submit"]').removeAttr('disabled');
    }, 10000);
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
if( $('#txt_count_stores').val()<=0){
  $('input[type="text"],body').bind('keydown', 'down', function() {

addRow();
            return false;
        });
 }
function reBind(s){
  if(s==undefined)
            s=0;
   if( $('#txt_count_stores').val()<=0){
       $('input[name="class_id[]"]').bind("focus",function(e){

        _showReport('$select_items_url/'+$(this).attr('id')+'/1'+ $('#h_data_search').val());
    });
         $('input[name="h_class_id[]"]').bind('keyup', '+', function(e) {
   $(this).val( $(this).val().replace('+', ''));
    var id_v=$(this).closest('tr').find('input[name="class_id[]"]').attr('id');
  _showReport('$select_items_url/'+id_v+'/1'+ $('#h_data_search').val());
           });

 $('input[name="h_class_id[]"]').change(function(e){
       var id_v=$(this).val();
       var d=$(this).closest('tr').find('input[name="h_class_id[]"]');
       var name=$(this).closest('tr').find('input[name="class_id[]"]');
       var unit=$(this).closest('tr').find('select[name="unit_class_id[]"]');
           var p=$(this).closest('tr').find('input[name="price_class_id[]"]');
 var am=$(this).closest('tr').find('input[name="amount[]"]');
         get_data('{$get_class_url}',{id:id_v, type:1},function(data){
                if (data.length == 1){
                    var item= data[0];
                      if(item.CLASS_STATUS==1){
                    name.val(item.CLASS_NAME_AR);
                           p.val(item.CLASS_PURCHASING);
                unit.select2("val", item.CLASS_UNIT_SUB);
                am.focus();} else{    d.val('');
                    name.val('');
                      p.val('0');
                    unit.select2("val", '');}


          }else{
                    d.val('');
                    name.val('');
                      p.val('0');
                    unit.select2("val", '');
                }
         });
            });
        if(s){
            $('select#unit_class_id_'+count).append('<option></option>'+select_class_unit).select2();
        }
$('select[name="unit_class_id[]"]').select2("readonly",true);
}
calcPrice();
}

reBind();






function addRow(){
if( $('#txt_count_stores').val()<=0){
//if($('input',$('#stores_class_input_detailTbl')).filter(function() { return this.value == ""; }).length <= 0){

    var html ='<tr><td><i class="glyphicon glyphicon-sort" /></li> <input type="hidden" value="0" name="h_ser[]" id="h_ser_'+count+'" class="form-control col-sm-16" ></td>'+
      '<td><input type="text" name="h_class_id[]"    data-val="true"  data-val-required="حقل مطلوب"   id="h_class_id_'+count+'"   class="form-control col-sm-2"></td>'+
       '<td><input name="class_id[]"   data-val="true"  data-val-required="حقل مطلوب"   id="class_id_'+count+'" readonly class="form-control col-sm-16"></td>'+
      '<td><select name="unit_class_id[]" class="form-control" id="unit_class_id_'+count+'" /></select></td>'+
     '<td><input name="amount[]"   data-val="true"  id="amount_'+count+'"    data-val-required="حقل مطلوب"   data-val-regex="المدخل غير صحيح!"   class="form-control" > </td>'+
     '<td><input name="price_class_id[]"   data-val="true"  id="price_class_id_'+count+'"    data-val-required="حقل مطلوب"   data-val-regex="المدخل غير صحيح!"   class="form-control" > </td>'+
         '<td> <div class=""><select  name="taxable_det[]" id="taxable_det_'+count+'"  data-curr="false"  class="form-control">  <option    value="1"> يخضع</option> <option    value="2"> لا يخضع</option>  </select></div></td> '+
      '<td><input name="price[]" type="text" value="0" readonly id="price_'+count+'"  class="form-control" readonly></td><td ></td></tr>';

   $('#stores_class_input_detailTbl tbody').append(html);




    reBind(1);

 count = count+1;


//  }
}

}

   function AddRowWithData(id,name_ar,unit,price,unit_name){
        addRow();



        $('#class_id_'+(count -1)).val(id+': '+name_ar);
        $('#h_class_id_'+(count -1)).val(id);

         $('#unit_class_id_'+(count -1)).select2("val", unit);

        $('#price_class_id_'+(count -1)).val(price);


        $('#report').modal('hide');
    }

SCRIPT;





if(HaveAccess($create_url) and $action=='index' ){

    $scripts = <<<SCRIPT
<script>
  $(function() {
        $( "#stores_class_input_detailTbl tbody" ).sortable();
    });
  {$shared_js}


    function clear_form(){
        clearForm($('#{$TB_NAME}_from'));

    }
 $('#dp_class_input_type').val( '{$class_input_typec}');
       $('#txt_class_input_date').val('{$class_input_date}');
        $('#dp_store_id').val( '{$store_id}');
        $('#h_txt_customer_name').val( '{$customer_resource_id}');
        $('#txt_customer_name').val( '{$cust_name}');
         $('#h_txt_customer_id_type').val( '{$account_type_list}');

  $('#txt_declaration').val( '{$declaration}');
    $('#dp_account_type').val( '{$account_type}');
     $('#dp_class_input_type').val( '{$class_input_typex}');

  if ('$class_input_seqs' !='') {
  $('input[name="h_class_id[]"]').prop("readonly",true);
$('input[name="amount[]"]').prop("readonly",true);
   calcTotalwithTax();
  }

  if($('#h_txt_customer_name').val() !=''){
 
  chk_customer_account_type();
   $('#db_customer_account_type option').each(function() {
                        if($('#h_txt_customer_id_type').val().indexOf($(this).val()) != -1 ){
                            $(this).show();
                             $(this).attr('selected','selected');
                        }
                        else
                         $(this).hide();
                    });


  }
</script>

SCRIPT;
    sec_scripts($scripts);

}
else
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
$('#txt_invoice_date2').val( '{$receipt_data['INVOICE_DATE2']}');
$('#txt_invoice_case').val( '{$receipt_data['INVOICE_CASE']}');
$('#dp_curr_id').val( '{$receipt_data['CURR_ID']}');
$('#txt_curr_value').val( '{$receipt_data['CURR_VALUE']}');
$('#dp_descount_type').val( '{$descount_type}');
$('#txt_curr_value').val( '{$receipt_data['CURR_VALUE']}');
$('#txt_descount_value').val( '{$descount_value}');
$('#dp_vat_type').val( '{$vat_type}');

//$('#txt_vat_account_id').val( '{$receipt_data['VAT_ACCOUNT_ID']}');
//$('#txt_vat_value').val( '{$receipt_data['VAT_VALUE']}');



$('#txt_year_order').val( '{$receipt_data['YEAR_ORDER']}');
$('#txt_invoice_inbox').val( '{$receipt_data['INVOICE_INBOX']}');
 $('#db_customer_account_type').val('{$receipt_data['CUSTOMER_ACCOUNT_TYPE']}');
chk_customer_account_type();

function {$TB_NAME}_case(obj){
if ($('#txt_invoice_case').val()==3){
  success_msg('الفاتورة مدققة','تحذير');
}else{
	 if (isDoubleClicked($(obj))) return;
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

function {$TB_NAME}_back_case(obj){
if ($('#txt_invoice_case').val()==1){
  success_msg('الفاتورة معدة ','تحذير');
}else{
	 if (isDoubleClicked($(obj))) return;
    if(confirm('هل تريد إتمام العملية ؟')){
 
var c=$('#txt_invoice_case').val();
    get_data('{$back_case_url}',{id:{$receipt_data['CLASS_INPUT_ID']},case:c},function(data){
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