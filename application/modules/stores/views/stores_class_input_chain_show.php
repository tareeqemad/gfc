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

$report_url = base_url("JsperReport/showreport?sys=financial/stores");
$get_url =base_url("$MODULE_NAME/$TB_NAME/get_id");
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$edit_url =base_url("$MODULE_NAME/$TB_NAME/get_id");
$delete_url =base_url("$MODULE_NAME/$TB_NAME/delete");
$adopt_url=base_url("$MODULE_NAME/$TB_NAME/adopt");
//$transfer_url=base_url("$MODULE_NAME/$TB_NAME/transfer");
$transfer_url =base_url("$MODULE_NAME/invoice_class_input/create");
$transfer_chain_url=base_url("$MODULE_NAME/$TB_NAME/transfer_chain");
$transfer_chain3_url=base_url("$MODULE_NAME/$TB_NAME/transfer_chain3");
$update_chain_url=base_url("$MODULE_NAME/$TB_NAME/update_transfer_chain");
$invoice_edit=base_url('stores/invoice_class_input/get_id');
/*$record_url=base_url("$MODULE_NAME/$TB_NAME/record");
$return_url=base_url("$MODULE_NAME/$TB_NAME/returnp");*/
//$get_details_url =base_url("$MODULE_NAME/$TB_NAME2/get_page");
$customer_url =base_url('payment/customers/public_index');
$orders_url =base_url('stores/receipt_class_input/public_index');
$delete_details_url=base_url("$MODULE_NAME/$TB_NAME2/delete");
$select_items_url=base_url("$MODULE_NAME/classes/public_index");
$select_accounts_url =base_url('financial/accounts/public_select_accounts');
//$back_url=base_url('payment/financial_payment/'.$action);
$financial_chain_url=base_url("financial/financial_chains/get");
//$back_url=base_url("$MODULE_NAME/$TB_NAME/$action");
$back_url=base_url("$MODULE_NAME/$TB_NAME/index_mk?type=$type");
$cancel_chain_url=base_url("$MODULE_NAME/$TB_NAME/cancel_transfer_chain");
$cancel_chain3_url=base_url("$MODULE_NAME/$TB_NAME/cancel_transfer_chain3");
$get_class_url =base_url('stores/classes/public_get_id');
$print_url = 'https://itdev.gedco.ps/gfc.aspx?data='.get_report_folder().'&' ;
$invoice_url=base_url("stores/invoice_class_input/get_id");
$donation_view_url=base_url("donations/donation/get");
if ($action=='index')
{$receipt_data= array();

    $vat_account_id=$vat_account_id_c;
    $vat_value=$vat_value_c;
    $class_input_case=1;
    $stores_class_input_id= 0;
    $financial_chain=0;
    $is_convert=0;
    $vat_type=2;
    $input_seq_t='';
    $donation_file_id=0;
    $invoice_class_seq=0;
    $donation_curr_value=1;
    $customer_account_type='';
    $account_type='';
} else if ($action=='edit'){
    $vat_account_id=(isset($receipt_data['VAT_ACCOUNT_ID'])&&($receipt_data['VAT_ACCOUNT_ID']!=''))? $receipt_data['VAT_ACCOUNT_ID'] :$vat_account_id_c;
    $vat_value=(isset($receipt_data['VAT_VALUE'])&&($receipt_data['VAT_VALUE']!=''))? $receipt_data['VAT_VALUE'] :$vat_value_c;
    $class_input_case=(isset($receipt_data['CLASS_INPUT_CASE']))? $receipt_data['CLASS_INPUT_CASE'] :1;
    $stores_class_input_id= (isset($receipt_data['CLASS_INPUT_ID']) )? $receipt_data['CLASS_INPUT_ID'] :0;
    $financial_chain=(isset($receipt_data['FINANCIAL_CHAIN']) )? $receipt_data['FINANCIAL_CHAIN'] :0;
    $is_convert=(isset($receipt_data['IS_CONVERT']) )? $receipt_data['IS_CONVERT'] :0;
     $input_seq_t=(isset($receipt_data['INPUT_SEQ_T']) )? $receipt_data['INPUT_SEQ_T'] :'';
    $vat_type=(isset($receipt_data['VAT_TYPE']) )? $receipt_data['VAT_TYPE'] :2;
    $donation_file_id=(isset($receipt_data['DONATION_FILE_ID']) )? $receipt_data['DONATION_FILE_ID'] :0;
    $invoice_class_seq=(isset($receipt_data['INVOICE_CLASS_SEQ']) )? $receipt_data['INVOICE_CLASS_SEQ'] :0;
    $donation_curr_value=(isset($receipt_data['DONATION_CURR_VALUE']) )? $receipt_data['DONATION_CURR_VALUE'] :1;
    $customer_account_type=isset($receipt_data['CUSTOMER_ACCOUNT_TYPE'])? $receipt_data['CUSTOMER_ACCOUNT_TYPE']:'';
    $account_type=isset($receipt_data['ACCOUNT_TYPE'])? $receipt_data['ACCOUNT_TYPE']:'';
}

if($donation_file_id>0)
    $select_items_url=base_url("donations/donation/public_index");//."?did=$donation_file_id&";
else
    $select_items_url=base_url("$MODULE_NAME/classes/public_index");


$HaveRs = count($receipt_data) > 0;

?>

    <div class="row">
        <div class="toolbar">

            <div class="caption"><?= $title ?></div>

            <ul>
              <li>

                    <a   href="<?= $back_url ?>"><i  class="icon icon-reply"></i> </a> </li>
                <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
            </ul>

        </div>
    </div>
    <div class="form-body">

    <div id="msg_container"></div>
    <div id="container">
    <form class="form-vertical" id="<?=$TB_NAME?>_form" method="post" action="<?=base_url("$MODULE_NAME/$TB_NAME/".($action == 'index'?'create':$action))?>" role="form" novalidate="novalidate">
    <div class="modal-body inline_form">
    <fieldset id="invoice_tb" hidden="hidden">
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
                            <option  ></option>
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

    </fieldset>



    <hr/>
    <fieldset>
        <legend>  بيانات السند </legend>
        <div class="form-group col-sm-4">
            <label class="col-sm-5 control-label"> مسلسل الإدخال </label>
            <div class="col-sm-6">
                <input type="text" name="input_seq_t" value="<?=$input_seq_t?>"  readonly  data-type="text"   id="txt_input_seq_t" class="form-control ">
                <input type="hidden"   name="donation_file_id" id="txt_donation_file_id" class="form-control">

            </div></div>
        <div class="form-group col-sm-4">
            <label class="col-sm-4 control-label"> تاريخ سند الإدخال </label>
            <div class="col-sm-6">
                <input type="text" name="class_input_date" readonly  data-date-format="DD/MM/YYYY"  data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?= date_format_exp() ?>" data-val="true"  data-val-required="حقل مطلوب"  id="txt_class_input_date" class="form-control ">
                <span class="field-validation-valid" data-valmsg-for="class_input_date" data-valmsg-replace="true"></span>
                <input type="hidden" data-val="true"    data-val-required="حقل مطلوب" name="class_input_id" id="txt_class_input_id" class="form-control">
                    </div></div>
        <div class="form-group col-sm-4">
            <label class="col-sm-5 control-label">رقم محضر الفحص و الاستلام</label>
            <div class="col-sm-6">
                <input type="text" data-val="true" readonly   name="order_id_text" id="txt_order_id_text" class="form-control" dir="rtl">
            </div>
        </div>
        <div class="form-group col-sm-4">
            <label class="col-sm-5 control-label"> رقم أمر التوريد </label>
            <div class="col-sm-6">
                <input type="text" data-val="true"  readonly  name="order_id" id="txt_order_id" class="form-control" dir="rtl">
                <span class="field-validation-valid" data-valmsg-for="order_id" data-valmsg-replace="false"></span>
                <input type="hidden" data-val="true"  name="record_id" id="txt_record_id" class="form-control" dir="rtl">
                <input type="hidden" data-val="true"  name="year_order" id="txt_year_order" class="form-control" dir="rtl">
                <input type="hidden" data-val="true" name="invoice_inbox" id="txt_invoice_inbox" class="form-control" dir="rtl">

            </div>
        </div>
        <div class="form-group col-sm-4">
            <label class="col-sm-4 control-label">المخزن</label>
            <div class="col-sm-6">
                <select name="store_id"  style="width: 250px"  id="dp_store_id" class="form-control" data-val="true"  data-val-required="حقل مطلوب">
                    <?php
                        foreach($stores as $row) :
                            if ($receipt_data['STORE_ID'] ==$row['STORE_ID']){?>
                                <option  value="<?=$row['STORE_ID']?>"><?= $row['STORE_NO'].":".$row['STORE_NAME'] ?></option>";
                            <?php }
                        endforeach;
                   ?>
                </select>


                <span class="field-validation-valid" data-valmsg-for="store_id" data-valmsg-replace="true"></span>

            </div></div>
        <div class="form-group col-sm-4">
            <label class="col-sm-5 control-label"> نوع سند الإدخال </label>
            <div class="col-sm-6">
                <select name="class_input_type"  id="dp_class_input_type" class="form-control" data-val="true"  data-val-required="حقل مطلوب">
                    <?php foreach($class_input_type as $row) :?>
                        <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                    <?php endforeach; ?>


                </select>


                <span class="field-validation-valid" data-valmsg-for="class_input_type" data-valmsg-replace="true"></span>

            </div>
        </div>
        <div class="form-group col-sm-4">
            <label class="col-sm-5 control-label">نوع المستفيد</label>
            <div class="col-sm-6">
                <select name="account_type"  id="dp_account_type" class="form-control">
                    <?php
                        foreach($customer_type as $row) :
                            if ($receipt_data['ACCOUNT_TYPE'] ==$row['CON_NO']){?>
                                <option  value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>";
                            <?php }
                        endforeach;
                    ?>
                </select>
            </div>

        </div>


        <div class="form-group col-sm-4">
            <label class="col-sm-4 control-label">رقم الحساب</label>
            <div class="col-sm-2">
                <input type="text"  data-val="true"     class="form-control" readonly name="customer_resource_id" value="" id="h_txt_customer_name">
                <span class="field-validation-valid" data-valmsg-for="customer_resource_id" data-valmsg-replace="true"></span>
            </div>

        </div>
        <div class="form-group col-sm-4">
            <label class="col-sm-3 control-label">اسم الحساب</label>
            <div class="col-sm-8">
                <input name="customer_name" data-val="true"     class="form-control" readonly id="txt_customer_name" value=""   >
                 <span class="field-validation-valid" data-valmsg-for="customer_name" data-valmsg-replace="true"></span>
                <input type="hidden" name="class_input_case" value="" id="txt_class_input_case">
                <input type="hidden" name="invoice_case" value="" id="txt_invoice_case">
            </div>

        </div>

        <div class="form-group col-sm-4" style="display: <?php  if ($account_type==2) echo "block"; else echo "none"; ?>" id="div_customer_account_type">
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

        <div class="form-group col-sm-12">
            <label class="col-sm-4 control-label">ملاحظات سند الإدخال</label>
            <div class="col-sm-8">
                <input type="text" data-val="true"  name="declaration" id="txt_declaration" class="form-control" dir="rtl">

            </div>
        </div>


        <div class="form-group col-sm-4">
            <label class="col-sm-4 control-label">نوع اللجنة</label>
            <div class="col-sm-6">
                <select name="class_input_class_type"  style="width: 250px"  id="dp_class_input_class_type" class="form-control">
                    <?php
                        foreach($class_input_class_type as $row) :
                            if ($receipt_data['CLASS_INPUT_CLASS_TYPE'] ==$row['COMMITTEES_ID']){?>
                                <option  value="<?=$row['COMMITTEES_ID']?>"><?=$row['COMMITTEES_NAME']?></option>";
                            <?php }
                        endforeach;
                    ?>
                </select>



            </div></div>
        <div class="form-group col-sm-4">
            <label class="col-sm-4 control-label">رقم الإرسالية</label>
            <div class="col-sm-6">
                <input type="text" data-val="true" readonly  name="send_id2" id="txt_send_id2" class="form-control" dir="rtl">
            </div>
        </div>

        <div class="form-group col-sm-4">
            <?php if ($type !=3) { ?>
            <label class="col-sm-5 control-label">تاريخ الهبة</label>
            <div class="col-sm-6">
                <input type="text" name="grant_date"  data-type="date"  data-date-format="DD/MM/YYYY"  data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?= date_format_exp() ?>" data-val="true"  data-val-required="حقل مطلوب"  id="txt_grant_date" class="form-control ">
                <span class="field-validation-valid" data-valmsg-for="grant_date" data-valmsg-replace="true"></span>
            </div>
    <?php } ?>
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



        <?php if ($invoice_class_seq > 0) { ?>
        <div class="form-group col-sm-4">
            <div class="form-group col-sm-5">
                <div class="col-sm-6">
                    <a target="_blank" href="<?php
                    echo $invoice_url."/".$invoice_class_seq."/edit/4"; ?>">
                        <label class="col-sm-5 control-label"> فاتورة الشراء</label></a>
                </div>
            </div>
          </div>
        <?php } ?>

<?php if ($donation_file_id > 0) { ?>
        <div class="form-group col-sm-4">
            <div class="col-sm-6">
                <a target="_blank" href="<?php
                    echo $donation_view_url."/".$donation_file_id."/1"; ?>">
                        <label class="col-sm-5 control-label"> عرض المنحة</label></a>
            </div>
        </div>

    <div class="form-group col-sm-4">
        <label class="col-sm-4 control-label">سعر التحويل من عملة المنحة لشيكل</label>
        <div class="col-sm-6">
            <input type="text"  data-val="true"  data-val-required="حقل مطلوب"   name="donation_curr_value" min="1" id="txt_donation_curr_value" class="form-control" value="<?=$donation_curr_value?>" dir="rtl">
            <span class="field-validation-valid" data-valmsg-for="donation_curr_value" data-valmsg-replace="true"></span>

        </div></div>


<?php } ?>
        <div class="form-group col-sm-12">
            <label class="col-sm-4 control-label">ملاحظات القيد المالي </label>
            <div class="col-sm-8">
                <input type="text" data-val="true"  name="financial_declaration" id="txt_financial_declaration" class="form-control" dir="rtl">

            </div>
        </div>

    </fieldset>

    <hr/>

    <fieldset>
        <legend> الضريبة</legend>

                <div class="form-group col-sm-3">
                    <label class="col-sm-4 control-label">يشمل الضريبة</label>
                    <div class="col-sm-3">
                        <select  name="vat_type" id="dp_vat_type"  data-curr="false"  class="form-control">
                            <option   value="2">لا</option>
                            <option   value="1">نعم</option>

                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-3" hidden="true">
                    <label class="col-sm-5 control-label">رقم حساب الضريبة </label>
                    <div class="col-sm-5">
                        <input type="text"  value="<?=$vat_account_id?>" data-val="true"  name="vat_account_id" id="txt_vat_account_id" class="form-control" dir="rtl" readonly>

                    </div>
                </div>

                <div class="form-group col-sm-3">
                    <label class="col-sm-5 control-label">نسبة الضريبة</label>
                    <span style="color: #FF0000;bold">%</span>
                    <div class="col-sm-5">
                        <input type="text"  value="<?=$vat_value?>" data-val="true" name="vat_value" id="txt_vat_value" class="form-control" dir="rtl" readonly>

                    </div>
                </div>

    </fieldset>




    <hr/>
    <fieldset>
        <legend> بيانات الأصناف </legend>


        <div style="clear: both;">
            <input type="hidden" id="h_data_search" />
            <?php
            echo modules::run('stores/stores_class_input/public_get_details_chain',(count($receipt_data))?$receipt_data['CLASS_INPUT_ID']:0);


            ?>

        </div>
    </fieldset>

    <hr/> <hr/>

    <div class="modal-footer">

        <?php
//echo "ddd".$is_convert ;
        if((HaveAccess($create_url) ||HaveAccess($edit_url)) && ($class_input_case ==2) && ($type ==1) && ($is_convert==0) ) echo "<button type='submit' id='sub' data-action='submit' class='btn btn-primary'>حفظ البيانات</button>";
     //   if (($action=='edit') && (HaveAccess($adopt_url)) && ($class_input_case ==1))  echo "<button type='button' id='adopt' onclick='{$TB_NAME}_adopt();' class='btn btn-primary' data-dismiss='modal'>اعتماد </button> ";
      if (($action=='edit') && (HaveAccess($transfer_url))  && ($class_input_case ==2)&& ($type ==0)&& ($is_convert==0) ) { echo "<button type='button' id='transfer' onclick='{$TB_NAME}_transfer(this);' class='btn btn-primary' data-dismiss='modal'/>"; if($is_convert==1) echo "محول لفاتورة شراء"; else echo "تحويل لفاتورة شراء"; }
        if (($action=='edit') && (HaveAccess($transfer_chain_url))  && ($class_input_case ==2)&& ($type ==1) && ($is_convert==0) ) { echo "<button type='button' id='transfer_chain' onclick='{$TB_NAME}_transfer_chain(this);' class='btn btn-primary' data-dismiss='modal'/>"; if($is_convert==1) echo "محول لقيد مالي "; else echo "تحويل لقيد مالي "; }
 if (($action=='edit') && (HaveAccess($update_chain_url))  &&($financial_chain !=0) && ($is_convert==1) && ($type !=3)) {  echo "<button type='button' id='update_transfer_chain' onclick='update_transfer_chainx(this);' class='btn btn-primary' data-dismiss='modal'/>"; echo "حفظ و تحديث القيد";  }
        if (($action=='edit') && (HaveAccess($cancel_chain_url))  &&($financial_chain !=0) && ($is_convert==1) && ($type !=3)) {  echo "<button type='button' id='cancel_transfer_chain' onclick='cancel_transfer_chainx(this);' class='btn btn-primary' data-dismiss='modal'/>"; echo "إلغاء تحويل لقيد مالي";  }



        if (($action=='edit') && (HaveAccess($transfer_chain3_url))  && ($class_input_case ==2)&& ($type ==3) && ($is_convert==0) ) { echo "<button type='button' id='transfer_chainy' onclick='{$TB_NAME}_transfer_chainy(this);' class='btn btn-primary' data-dismiss='modal'/>"; if($is_convert==1) echo "معتمد من الرقابة "; else echo "اعتماد الرقابة"; }
        if (($action=='edit') && (HaveAccess($cancel_chain3_url))  &&($financial_chain !=0) && ($is_convert==1)&& ($type ==3)) {  echo "<button type='button' id='cancel_transfer_chain' onclick='cancel_transfer_chain3(this);' class='btn btn-primary' data-dismiss='modal'/>"; echo "إلغاء اعتماد الرقابة";  }

        ?>
        <?php  if (($action=='edit') and $donation_file_id>0  ) { ?>
            <button type="button" id="print_repd" onclick="javascript:print_repd();" class="btn btn-success"> <i class="glyphicon glyphicon-print"></i> مقارنة المنحة بسند الإدخال </button>
        <?php }?>
        <button type="button" id="print_rep" onclick="javascript:print_rep();" class="btn btn-success"> <i class="glyphicon glyphicon-print"></i> طباعة </button>

        <button class="btn btn-warning dropdown-toggle" onclick="$('#stores_class_input_detailTbl').tableExport({type:'excel',escape:'false'});" data-toggle="dropdown"><i class="fa fa-bars"></i> تصدير</button>

    </div>
    <div style="clear: both;">
        <?php echo $HaveRs?  modules::run('attachments/attachment/index',$stores_class_input_id,'stores_input') : ''; ?>
    </div>

    </div>
    </form>
    </div>

    </div> <!-- /.modal-content -->


<?php

$shared_js = <<<SCRIPT

var count = 0;

  var class_unit_json= {$class_unit};
    var select_class_unit= '';

  count = $('input[name="h_class_id[]"]').length;

   $('input[name="amount[]"]').prop('readonly',true);
   $('input[name="price_class_id[]"]').prop('readonly',true);

    $('input[name="taxable_det[]"]').prop('readonly',true);
$('input[name="price[]"]').prop("readonly",false);

   $.each(class_unit_json, function(i,item){
        select_class_unit += "<option value='"+item.CON_NO+"' >"+item.CON_NAME+"</option>";
    });

    $('select[name="unit_class_id[]"]').append(select_class_unit);
    $('select[name="unit_class_id[]"]').each(function(){
        $(this).val($(this).attr('data-val'));
    });
   $('select[name="unit_class_id[]"]').select2();


    function selectAccount(obj){
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

if ('{$donation_file_id}'!='0'){
  $('#txt_donation_curr_value').on('change',function(){
 calc_values_with_donation();
calcTotal();
           });
}


 $('#print_rep').click(function(){
        //_showReport('$print_url'+'report=STORES_CLASS_INPUT_SEND&params[]={$stores_class_input_id}');
        _showReport('$report_url'+'&report=stores_class_input_send&p_class_input_id={$stores_class_input_id}');
    });
});

if ('{$donation_file_id}'=='0'){
    $('#dp_vat_type').on('change',function(){

      if ($('#dp_vat_type').val()==1){
     $('select[name="taxable_det[]"]').val(1);

      }
    });
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
  $('input[type="text"],body').bind('keydown', 'down', function() {

addRow();
            return false;
        });
function reBind(s){
  if(s==undefined)
            s=0;
       $('input[name="class_id[]"]').bind("focus",function(e){

        _showReport('$select_items_url/'+$(this).attr('id')+ $('#h_data_search').val());
    });
         $('input[name="h_class_id[]"]').bind('keyup', '+', function(e) {
   $(this).val( $(this).val().replace('+', ''));
    var id_v=$(this).closest('tr').find('input[name="class_id[]"]').attr('id');
  _showReport('$select_items_url/'+id_v+ $('#h_data_search').val());
           });

 $('input[name="h_class_id[]"]').change(function(e){
       var id_v=$(this).val();
       var d=$(this).closest('tr').find('input[name="h_class_id[]"]');
       var name=$(this).closest('tr').find('input[name="class_id[]"]');
        var p=$(this).closest('tr').find('input[name="price[]"]');
       var unit=$(this).closest('tr').find('select[name="unit_class_id[]"]');
 var am=$(this).closest('tr').find('input[name="amount[]"]');
         get_data('{$get_class_url}',{id:id_v, type:1},function(data){
                if (data.length == 1){
                    var item= data[0];
                    name.val(item.CLASS_NAME_AR);
                      p.val(item.CLASS_PURCHASING);
                unit.select2("val", item.CLASS_UNIT_SUB);
                am.focus();

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


if ('{$donation_file_id}'=='0'){
$('#dp_vat_type,select[name="taxable_det[]"],input[name="price[]"]').bind("change",function(e){
 calcTotal();
            });

}



reBind();

calcTotal();


function calc_values_with_donation(){
 var x=  $('#txt_donation_curr_value').val();
                    $('input[name="h_class_price_no_vat[]"]').each(function(i){
                      var class_price_no_vat = $(this).val();
                      var price = class_price_no_vat * Number(x);
                      $(this).closest('tr').find('input[name="price[]"]').val(price);
                  });
    }



function calcTotal(){

   setTimeout(function(){

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
                       var price = $(this).closest('tr').find('input[name="price[]"]').val();
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
                    $(this).closest('tr').find('input[name="total[]"]').val(total);

                });




                 var TotalTax =  GET_TAX_VAL_WT_DESC(TotalWithVat,TotalWithOutVat,0,tax);


                 if (isNaNVal(TotalWithOutVat)<= 0  && vat_type == 1)
                      TotalTax = ATotal - TotalWithVat;




                 if (isNaNVal(TotalWithOutVat) > 0  && vat_type == 1)
                      TotalTax =TotalWithVat_tax;

  $('#inv_without_tax').text(isNaNVal(AllTotal,2));

                 $('#inv_tax').text(isNaNVal(TotalTax,2));

               $('#inv_total').text(isNaNVal((AllTotal+TotalTax),2));
    }, 1700);
//alert('c');
    }

/*
    function calcTotal(){
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
                       var price = $(this).closest('tr').find('input[name="price[]"]').val();
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
                    $(this).closest('tr').find('input[name="total[]"]').val(total);

                });




                 var TotalTax =  GET_TAX_VAL_WT_DESC(TotalWithVat,TotalWithOutVat,0,tax);


                 if (isNaNVal(TotalWithOutVat)<= 0  && vat_type == 1)
                      TotalTax = ATotal - TotalWithVat;




                 if (isNaNVal(TotalWithOutVat) > 0  && vat_type == 1)
                      TotalTax =TotalWithVat_tax;

  $('#inv_without_tax').text(isNaNVal(AllTotal,2));

                 $('#inv_tax').text(isNaNVal(TotalTax,2));

               $('#inv_total').text(isNaNVal((AllTotal+TotalTax),2));

    }
*/
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
  $('#txt_donation_file_id').val('{$receipt_data['DONATION_FILE_ID']}');
        $('#h_txt_customer_name').val( '{$receipt_data['CUSTOMER_RESOURCE_ID']}');
        $('#txt_customer_name').val( '{$receipt_data['CUST_NAME']}');
        $('#txt_class_input_case').val( '{$receipt_data['CLASS_INPUT_CASE']}');


        $('#txt_record_id').val( '{$receipt_data['RECORD_ID']}');


           $('#txt_order_id_text').val( '{$receipt_data['RECEORD_ID_TEXT']}');



            $('#dp_class_input_class_type').val( '{$receipt_data['CLASS_INPUT_CLASS_TYPE']}');
  $('#dp_class_input_type').val( '{$receipt_data['CLASS_INPUT_TYPE']}');
  $('#txt_declaration').val( '{$receipt_data['DECLARATION']}');
    $('#txt_financial_declaration').val( '{$receipt_data['FINANCIAL_DECLARATION']}');
  $('#txt_invoice_id').val( '{$receipt_data['INVOICE_ID']}');
$('#txt_invoice_date').val( '{$receipt_data['INVOICE_DATE']}');
$('#txt_invoice_date').val( '{$receipt_data['INVOICE_DATE']}');
$('#txt_invoice_case').val( '{$receipt_data['INVOICE_CASE']}');
$('#dp_curr_id').val( '{$receipt_data['CURR_ID']}');
$('#txt_curr_value').val( '{$receipt_data['CURR_VALUE']}');
$('#dp_descount_type').val( '{$receipt_data['DESCOUNT_TYPE']}');
$('#txt_curr_value').val( '{$receipt_data['CURR_VALUE']}');
$('#txt_descount_value').val( '{$receipt_data['DESCOUNT_VALUE']}');
$('#dp_vat_type').val( '{$vat_type}');
//$('#txt_vat_account_id').val( '{$receipt_data['VAT_ACCOUNT_ID']}');
//$('#txt_vat_value').val( '{$receipt_data['VAT_VALUE']}');



$('#txt_year_order').val( '{$receipt_data['YEAR_ORDER']}');
$('#txt_invoice_inbox').val( '{$receipt_data['INVOICE_INBOX']}');
$('#txt_grant_date').val( '{$receipt_data['GRANT_DATE']}');
$('#txt_send_id2').val( '{$receipt_data['SEND_ID2']}');
  $('#txt_donation_curr_value').val('{$receipt_data['DONATION_CURR_VALUE']}');


if  ($('#txt_class_input_case').val()==1){}

function {$TB_NAME}_adopt(){
if ($('#txt_class_input_case').val()==2){
  success_msg('الطلب معتمد','تحذير');
}else{
    if(confirm('هل تريد إتمام العملية ؟')){


    get_data('{$adopt_url}',{id:{$receipt_data['CLASS_INPUT_ID']}},function(data){
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
function {$TB_NAME}_transfer(obj){
if  ('{$is_convert}'=='1'){
 danger_msg('تحذير..','الطلب محول لفاتورة شراء');
}else{
if ($('#txt_class_input_case').val()==2){
	 if (isDoubleClicked($(obj))) return;
  if(confirm('هل تريد إتمام العملية ؟')){

 get_to_link('{$transfer_url}/'+'{$receipt_data['CLASS_INPUT_ID']}');
    /*get_data('{$transfer_url}',{id:{$receipt_data['CLASS_INPUT_ID']}},function(data){
       if(data )
                     console.log(data);
                       setTimeout(function(){

                       get_to_link('{$invoice_edit}/'+data+'/edit/1');

                    }, 1000);

                },'html');*/

}
}else{
 danger_msg('تحذير..','يجب اعتماد الطلب أولا');

}
}
}
function {$TB_NAME}_transfer_chain(obj){
if  ('{$is_convert}'=='1'){
 danger_msg('تحذير..','الطلب محول  ');
}else{
if ($('#txt_class_input_case').val()==2){
	 if (isDoubleClicked($(obj))) return;
  if(confirm('هل تريد إتمام العملية ؟')){


    get_data('{$transfer_chain_url}',{id:{$receipt_data['CLASS_INPUT_ID']}},function(data){

     if(data ==1){
      console.log(data);
                      success_msg('رسالة','تمت العملية بنجاح');
                      setTimeout(function(){

                  window.location.reload();

                }, 1000);
       }else {
          danger_msg('تحذير',data);
       }               
            },'html');

}
}else{
 danger_msg('تحذير..','يجب اعتماد الطلب أولا');

}
}
}
///////////
function {$TB_NAME}_transfer_chainy(obj){
if  ('{$is_convert}'=='1'){
 danger_msg('تحذير..','الطلب محول  ');
}else{
if ($('#txt_class_input_case').val()==2){
	 if (isDoubleClicked($(obj))) return;
  if(confirm('هل تريد إتمام العملية ؟')){

//tasneem
    get_data('{$transfer_chain3_url}',{id:{$receipt_data['CLASS_INPUT_ID']}},function(data){

      if(data ==1){
      console.log(data);
                      success_msg('رسالة','تمت العملية بنجاح');
                      setTimeout(function(){

                  window.location.reload();

                }, 1000);
       }else {
          danger_msg('تحذير',data);
       }               
            },'html');

}
}else{
 danger_msg('تحذير..','يجب اعتماد الطلب أولا');

}
}
}
///////////
function update_transfer_chainx(obj){

if  (('{$is_convert}'=='1') && ($('#txt_class_input_case').val()==2)){
 if (isDoubleClicked($(obj))) return;
  if(confirm('هل تريد إتمام العملية ؟')){


    var tbl = '#{$TB_NAME}_tb';
    var container = $('#' + $(tbl).attr('data-container'));
    var form = $('#{$TB_NAME}_form');
    form.attr('action','{$update_chain_url}');
    ajax_insert_update(form,function(data){
     if(data==1){
            success_msg('رسالة','تم حفظ البيانات بنجاح ..');
            get_to_link(window.location.href);
        }else{
            danger_msg('تحذير..',data);
        }

 },"html");

}
}
}

/////////////////////

function cancel_transfer_chainx(obj){

if  (('{$is_convert}'=='1') && ($('#txt_class_input_case').val()==2)){
 if (isDoubleClicked($(obj))) return;
  if(confirm('هل تريد إتمام العملية ؟')){

    var tbl = '#{$TB_NAME}_tb';
    var container = $('#' + $(tbl).attr('data-container'));
    var form = $('#{$TB_NAME}_form');
    form.attr('action','{$cancel_chain_url}');
    ajax_insert_update(form,function(data){
     if(data==1){
            success_msg('رسالة','تم حفظ البيانات بنجاح ..');
            get_to_link(window.location.href);
        }else{
            danger_msg('تحذير..',data);
        }

 },"html");

}
}
}
/////////////////////

function cancel_transfer_chain3(obj){
if  (('{$is_convert}'=='1') && ($('#txt_class_input_case').val()==2)){
	 if (isDoubleClicked($(obj))) return;
  if(confirm('هل تريد إتمام العملية ؟')){
     get_data('{$cancel_chain3_url}',{id:{$receipt_data['CLASS_INPUT_ID']}},function(data){

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
//////////////////////////

if  ('{$type}'=='1'){

$('input[name="order_id"]').prop("readonly",true);
//account_type
//class_input_type
//class_input_class_type
$('input[name="ORDER_ID_TEXT"]').prop("readonly",true);
 //$('#dp_store_id').select2("readonly",true);

$('input[name="amount[]"]').prop("readonly",true);



}
calcTotal();

if ('{$donation_file_id}'!='0'){
$('input[name="h_class_id[]"]').prop("readonly",true);
$('input[name="price[]"]').prop("readonly",true);
$('input[name="total[]"]').prop("readonly",true);
$('#dp_vat_type').on('change',function(){
$('#dp_vat_type').val(2);
        });
 $('select[name="taxable_det[]"]').on('change',function(){
$(this).val(2);
        });
   $('#print_repd').click(function(){
        _showReport('$print_url'+'report=STORES_CLASS_INPUT_DON&params[]={$stores_class_input_id}');
    });
}
</script>

SCRIPT;
        sec_scripts($edit_script);

    }
?>