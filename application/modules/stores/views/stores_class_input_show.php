<?php
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 08/12/14
 * Time: 12:41 م
 */

$MODULE_NAME = 'stores';
$TB_NAME = 'stores_class_input';
$TB_NAME2 = 'stores_class_input_detail';

$report_url = base_url("JsperReport/showreport?sys=financial/stores");
$get_url = base_url("$MODULE_NAME/$TB_NAME/get_id");
$create_url = base_url("$MODULE_NAME/$TB_NAME/create");
$edit_url = base_url("$MODULE_NAME/$TB_NAME/get_id");
$delete_url = base_url("$MODULE_NAME/$TB_NAME/delete");
$adopt_url = base_url("$MODULE_NAME/$TB_NAME/adopt");
//$transfer_url=base_url("$MODULE_NAME/$TB_NAME/transfer");
$transfer_url = base_url("$MODULE_NAME/invoice_class_input/create");
$invoice_edit = base_url('stores/invoice_class_input/get_id');
/*$record_url=base_url("$MODULE_NAME/$TB_NAME/record");
$return_url=base_url("$MODULE_NAME/$TB_NAME/returnp");*/
//$get_details_url =base_url("$MODULE_NAME/$TB_NAME2/get_page");
$customer_url = base_url('payment/customers/public_index');
$orders_url = base_url('stores/receipt_class_input/public_index');
$delete_details_url = base_url("$MODULE_NAME/$TB_NAME2/delete");
//$select_items_url=base_url("$MODULE_NAME/classes/public_index");
$select_accounts_url = base_url('financial/accounts/public_select_accounts');
$adopt_0_url = base_url("$MODULE_NAME/$TB_NAME/adopt0");
//$back_url=base_url('payment/financial_payment/'.$action);
//$back_url=base_url("$MODULE_NAME/$TB_NAME/$action");
$donation_url = base_url('donations/donation/public_get_donation_by_store_input');
$donation_details_url = base_url('donations/donation/public_get_donation_details');

$back_url = base_url("$MODULE_NAME/$TB_NAME/index");
$financial_chain_url = base_url("financial/financial_chains/get");
$invoice_url = base_url("stores/invoice_class_input/get_id");
$get_class_url = base_url('stores/classes/public_get_id');
$print_codes_url = base_url("pledges/class_codes/index");
$print_url = 'https://itdev.gedco.ps/gfc.aspx?data=' . get_report_folder() . '&';
$get_don_curr_val = base_url("$MODULE_NAME/$TB_NAME/public_get_don_curr_val");

$donation_view_url = base_url("donations/donation/get");
if ($action == 'index') {
    $receipt_data = array();

    $vat_account_id = $vat_account_id_c;
    $vat_value = $vat_value_c;
    $class_input_case = 1;
    $stores_class_input_id = 0;
    $financial_chain = 0;
    $is_convert = 0;
    $is_extened = 0;
    $is_converted_r = 0;
    $input_seq_t = '';
    $class_input_type1 = 0;
    $receipt_class_input_date = '';
    $invoice_class_seq = 0;
    $count_codes = 0;
    $class_input_id = 0;
    $donation_file_id = 0;
    $donation_curr_value = 1;
} else if ($action == 'edit') {
    $vat_account_id = (isset($receipt_data['VAT_ACCOUNT_ID']) && ($receipt_data['VAT_ACCOUNT_ID'] != '')) ? $receipt_data['VAT_ACCOUNT_ID'] : $vat_account_id_c;
    $vat_value = (isset($receipt_data['VAT_VALUE']) && ($receipt_data['VAT_VALUE'] != '')) ? $receipt_data['VAT_VALUE'] : $vat_value_c;
    $class_input_case = (isset($receipt_data['CLASS_INPUT_CASE'])) ? $receipt_data['CLASS_INPUT_CASE'] : 1;
    $stores_class_input_id = (isset($receipt_data['CLASS_INPUT_ID'])) ? $receipt_data['CLASS_INPUT_ID'] : 0;
    $financial_chain = (isset($receipt_data['FINANCIAL_CHAIN'])) ? $receipt_data['FINANCIAL_CHAIN'] : 0;
    $is_convert = (isset($receipt_data['IS_CONVERT'])) ? $receipt_data['IS_CONVERT'] : 0;
    $is_extened = (isset($receipt_data['IS_EXTENED'])) ? $receipt_data['IS_EXTENED'] : 0;
    $is_converted_r = (isset($receipt_data['IS_CONVERTED'])) ? $receipt_data['IS_CONVERTED'] : 0;
    $donation_file_id = (isset($receipt_data['DONATION_FILE_ID'])) ? $receipt_data['DONATION_FILE_ID'] : 0;
    $input_seq_t = (isset($receipt_data['INPUT_SEQ_T'])) ? $receipt_data['INPUT_SEQ_T'] : '';
    $class_input_type1 = (isset($receipt_data['CLASS_INPUT_TYPE'])) ? $receipt_data['CLASS_INPUT_TYPE'] : 0;
    $receipt_class_input_date = (isset($receipt_data['RECEIPT_CLASS_INPUT_DATE'])) ? $receipt_data['RECEIPT_CLASS_INPUT_DATE'] : '';
    $invoice_class_seq = (isset($receipt_data['INVOICE_CLASS_SEQ'])) ? $receipt_data['INVOICE_CLASS_SEQ'] : 0;
    $count_codes = (isset($receipt_data['COUNT_CODES'])) ? $receipt_data['COUNT_CODES'] : 0;
    $class_input_id = (isset($receipt_data['CLASS_INPUT_ID'])) ? $receipt_data['CLASS_INPUT_ID'] : 0;
    $donation_curr_value = (isset($receipt_data['DONATION_CURR_VALUE'])) ? $receipt_data['DONATION_CURR_VALUE'] : 1;


}


$select_donation_items_url = base_url("donations/donation/public_index");//."?did=$donation_file_id&";

$select_items_url = base_url("$MODULE_NAME/classes/public_index");

//echo $select_items_url ;
$HaveRs = count($receipt_data) > 0;
?>

    <div class="row">
        <div class="toolbar">

            <div class="caption"><?= $title ?></div>

            <ul>
                <?php //HaveAccess($back_url)
                if (TRUE): ?>
                    <li><a href="<?= $back_url ?>"><i class="icon icon-reply"></i> </a> </li><?php endif; ?>
                <li><a onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a>
                </li>
            </ul>

        </div>
    </div>
    <div class="form-body">

        <div id="msg_container"></div>
        <div id="container">
            <form class="form-vertical" id="<?= $TB_NAME ?>_form" method="post"
                  action="<?= base_url("$MODULE_NAME/$TB_NAME/" . ($action == 'index' ? 'create' : $action)) ?>"
                  role="form" novalidate="novalidate">
                <div class="modal-body inline_form">
                    <fieldset id="invoice_tb" hidden="hidden">
                        <legend>بيانات الفاتورة</legend>

                        <div style="clear: both; ">
                            <hr/>
                            <div class="form-group col-sm-3">
                                <label class="col-sm-5 control-label">رقم فاتورة الشراء</label>
                                <div class="col-sm-6">
                                    <input type="text" data-val="true" data-val-required="حقل مطلوب" name="invoice_id"
                                           id="txt_invoice_id" class="form-control" dir="rtl">
                                    <span class="field-validation-valid" data-valmsg-for="invoice_id"
                                          data-valmsg-replace="true"></span>

                                </div>
                            </div>

                            <div class="form-group col-sm-3">
                                <label class="col-sm-5 control-label">تاريخ فاتورة الشراء</label>
                                <div class="col-sm-5">
                                    <input type="text" name="invoice_date" data-type="date"
                                           data-date-format="DD/MM/YYYY" data-val-regex="التاريخ غير صحيح!"
                                           data-val-regex-pattern="<?= date_format_exp() ?>" data-val="true"
                                           data-val-required="حقل مطلوب" id="txt_invoice_date" class="form-control ">
                                    <span class="field-validation-valid" data-valmsg-for="invoice_date"
                                          data-valmsg-replace="true"></span>

                                </div>
                            </div>

                            <div class="form-group col-sm-3">
                                <label class="col-sm-4 control-label"> العملة </label>
                                <div class="col-sm-3">
                                    <select name="curr_id" id="dp_curr_id" data-curr="false" class="form-control">
                                        <?php foreach ($currency as $row) : ?>
                                            <option data-val="<?= $row['VAL'] ?>"
                                                    value="<?= $row['CURR_ID'] ?>"><?= $row['CURR_NAME'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group col-sm-3">
                                <label class="col-sm-3 control-label"> سعر العملة</label>
                                <div class="col-sm-3">
                                    <input type="text" name="curr_value" readonly data-val-regex="المدخل غير صحيح!"
                                           data-val-regex-pattern="<?= float_format_exp() ?>" data-val="true" value=""
                                           data-val-required="حقل مطلوب" id="txt_curr_value" class="form-control ">
                                    <span class="field-validation-valid" data-valmsg-for="curr_value"
                                          data-valmsg-replace="true"></span>
                                </div>
                            </div>


                        </div>

                        <hr/>

                        <fieldset>
                            <legend> الخصم المكتسب</legend>

                            <div style="clear: both; ">
                                <hr/>
                                <div class="form-group col-sm-3">
                                    <label class="col-sm-4 control-label"> نوع الخصم </label>
                                    <div class="col-sm-3">
                                        <select name="descount_type" id="dp_descount_type" data-curr="false"
                                                class="form-control">
                                            <option></option>
                                            <option value="1">مبلغ</option>
                                            <option value="2">نسبة</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group col-sm-3">
                                    <label class="col-sm-5 control-label">قيمة الخصم </label>
                                    <div class="col-sm-5">
                                        <input type="text" value="0" data-val="true" data-val-required="حقل مطلوب"
                                               name="descount_value" id="txt_descount_value" class="form-control"
                                               dir="rtl">
                                        <span class="field-validation-valid" data-valmsg-for="descount_value"
                                              data-valmsg-replace="true"></span>

                                    </div>
                                </div>


                            </div>
                        </fieldset>
                        <hr/>

                        <fieldset>
                            <legend> الضريبة</legend>

                            <div style="clear: both; ">
                                <hr/>
                                <div class="form-group col-sm-3">
                                    <label class="col-sm-4 control-label">يشمل الضريبة</label>
                                    <div class="col-sm-3">
                                        <select name="vat_type" id="dp_vat_type" data-curr="false" class="form-control">
                                            <option value="2">لا</option>
                                            <option value="1">نعم</option>

                                        </select>
                                    </div>
                                </div>

                                <div class="form-group col-sm-3">
                                    <label class="col-sm-5 control-label">رقم حساب الضريبة </label>
                                    <div class="col-sm-5">
                                        <input type="text" value="<?= $vat_account_id ?>" data-val="true"
                                               name="vat_account_id" id="txt_vat_account_id" class="form-control"
                                               dir="rtl" readonly>

                                    </div>
                                </div>

                                <div class="form-group col-sm-3">
                                    <label class="col-sm-5 control-label">قيمة الضريبة</label>
                                    <div class="col-sm-5">
                                        <input type="text" value="<?= $vat_value ?>" data-val="true" name="vat_value"
                                               id="txt_vat_value" class="form-control" dir="rtl" readonly>

                                    </div>
                                </div>
                        </fieldset>
                    </fieldset>

                    <hr/>
                    <fieldset>
                        <legend> بيانات السند</legend>
                        <div class="form-group col-sm-4">
                            <label class="col-sm-5 control-label">المسلسل</label>
                            <div class="col-sm-6">
                                <input type="text" readonly name="class_input_id" id="txt_class_input_id"
                                       class="form-control">
                                <input type="hidden" name="donation_file_id" id="txt_donation_file_id"
                                       class="form-control">
                                <input type="hidden" name="donation_curr_value" id="txt_donation_curr_value"
                                       class="form-control" value="<?= $donation_curr_value ?>" dir="rtl">

                            </div>
                        </div>

                        <div class="form-group col-sm-4">
                            <label class="col-sm-5 control-label"> مسلسل الإدخال </label>
                            <div class="col-sm-6">
                                <input type="text" name="input_seq_t" value="<?= $input_seq_t ?>" readonly
                                       data-type="text" id="txt_input_seq_t" class="form-control ">

                            </div>
                        </div>
                        <div class="form-group col-sm-4">
                            <label class="col-sm-4 control-label"> تاريخ سند الإدخال </label>
                            <div class="col-sm-6">
                                <input type="text" name="class_input_date" data-type="date"
                                       data-date-format="DD/MM/YYYY" data-val-regex="التاريخ غير صحيح!"
                                       data-val-regex-pattern="<?= date_format_exp() ?>" data-val="true"
                                       data-val-required="حقل مطلوب" id="txt_class_input_date" class="form-control ">
                                <span class="field-validation-valid" data-valmsg-for="class_input_date"
                                      data-valmsg-replace="true"></span>

                            </div>
                        </div>
                        <div class="form-group col-sm-4">
                            <label class="col-sm-5 control-label">رقم محضر الفحص و الاستلام</label>
                            <div class="col-sm-6">
                                <input type="text" data-val="true" name="order_id_text" id="txt_order_id_text"
                                       class="form-control" dir="rtl">
                            </div>
                        </div>
                        <div class="form-group col-sm-4">
                            <label class="col-sm-5 control-label"> رقم أمر التوريد </label>
                            <div class="col-sm-6">
                                <input type="text" data-val="true" name="order_id" id="txt_order_id"
                                       class="form-control" dir="rtl">
                                <span class="field-validation-valid" data-valmsg-for="order_id"
                                      data-valmsg-replace="false"></span>
                                <input type="hidden" data-val="true" name="record_id" id="txt_record_id"
                                       class="form-control" dir="rtl">
                                <input type="hidden" data-val="true" name="year_order" id="txt_year_order"
                                       class="form-control" dir="rtl">
                                <input type="hidden" data-val="true" name="invoice_inbox" id="txt_invoice_inbox"
                                       class="form-control" dir="rtl">

                            </div>
                        </div>
                        <div class="form-group col-sm-4">
                            <label class="col-sm-4 control-label">المخزن</label>
                            <div class="col-sm-6">
                                <select name="store_id" style="width: 250px" id="dp_store_id" class="form-control"
                                        data-val="true" data-val-required="حقل مطلوب">
                                    <?php if ($is_converted_r == '1') {
                                        foreach ($stores as $row) :
                                            if ($receipt_data['STORE_ID'] == $row['STORE_ID']) {
                                                ?>
                                                <option data-isdonation="<?= $row['ISDONATION'] ?>"
                                                        value="<?= $row['STORE_ID'] ?>"><?= $row['STORE_NO'] . ":" . $row['STORE_NAME'] ?></option>";
                                            <?php }
                                        endforeach;
                                    } else { ?>
                                        <option></option>
                                        <?php foreach ($stores as $row) : ?>
                                            <option data-isdonation="<?= $row['ISDONATION'] ?>"
                                                    value="<?= $row['STORE_ID'] ?>"><?= $row['STORE_NO'] . ":" . $row['STORE_NAME'] ?></option>
                                        <?php endforeach; ?><?php } ?>
                                </select>


                                <span class="field-validation-valid" data-valmsg-for="store_id"
                                      data-valmsg-replace="true"></span>

                            </div>
                        </div>
                        <div class="form-group col-sm-4">
                            <label class="col-sm-5 control-label"> نوع سند الإدخال </label>
                            <div class="col-sm-6">
                                <select name="class_input_type" id="dp_class_input_type" class="form-control"
                                        data-val="true" data-val-required="حقل مطلوب">
                                    <?php if ($is_converted_r == '1') {
                                        foreach ($class_input_type as $row) :
                                            if ($receipt_data['CLASS_INPUT_TYPE'] == $row['CON_NO']) {
                                                ?>
                                                <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>";
                                            <?php }
                                        endforeach;
                                    } else { ?>
                                        <option></option>
                                        <?php foreach ($class_input_type as $row) : ?>
                                            <?php if ($row['CON_NO'] != 3) { ?>
                                                <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                            <?php } endforeach; ?><?php } ?>
                                </select>


                                <span class="field-validation-valid" data-valmsg-for="class_input_type"
                                      data-valmsg-replace="true"></span>

                            </div>
                        </div>
                        <div class="form-group col-sm-4">
                            <label class="col-sm-5 control-label">نوع المستفيد</label>
                            <div class="col-sm-6">
                                <select name="account_type" id="dp_account_type" class="form-control" data-val="true"
                                        data-val-required="حقل مطلوب">
                                    <?php if ($is_converted_r == '1') {
                                        foreach ($customer_type as $row) :
                                            if ($receipt_data['ACCOUNT_TYPE'] == $row['CON_NO']) {
                                                ?>
                                                <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>";
                                            <?php }
                                        endforeach;
                                    } else { ?>
                                        <?php foreach ($customer_type as $row) : ?>
                                            <?php if ($row['CON_NO'] != 3) { ?>
                                                <option <?= $HaveRs ? ($receipt_data['ACCOUNT_TYPE'] == $row['CON_NO'] ? 'selected' : '') : '' ?>
                                                        value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                            <?php } endforeach; ?><?php } ?>
                                </select>
                                <span class="field-validation-valid" data-valmsg-for="account_type"
                                      data-valmsg-replace="true"></span>

                            </div>

                        </div>
                        <div class="form-group col-sm-4">
                            <label class="col-sm-4 control-label">رقم الحساب</label>
                            <div class="col-sm-2">
                                <input type="text" data-val="true" class="form-control" readonly
                                       name="customer_resource_id" value="" id="h_txt_customer_name">
                                <span class="field-validation-valid" data-valmsg-for="customer_resource_id"
                                      data-valmsg-replace="true"></span>
                            </div>

                        </div>
                        <div class="form-group col-sm-8">
                            <label class="col-sm-6 control-label">اسم الحساب</label>
                            <div class="col-sm-6">
                                <input name="customer_name" data-val="true" class="form-control" readonly
                                       id="txt_customer_name" value="">
                                <span class="field-validation-valid" data-valmsg-for="customer_name"
                                      data-valmsg-replace="true"></span>
                                <input type="hidden" name="class_input_case" value="" id="txt_class_input_case">
                                <input type="hidden" name="invoice_case" value="" id="txt_invoice_case">
                            </div>

                        </div>

                        <div class="form-group col-sm-4" style="display: none;" id="div_customer_account_type">
                            <label class="col-sm-4 control-label"> نوع حساب المستفيد </label>
                            <div class="col-sm-6">
                                <select class="form-control" id="db_customer_account_type" name="customer_account_type">
                                    <option></option>
                                    <?php foreach ($ACCOUNT_TYPES as $_row) : ?>

                                        <option <?= $HaveRs ? ($receipt_data['CUSTOMER_ACCOUNT_TYPE'] == $_row['ACCCOUNT_TYPE'] ? 'selected' : '') : '' ?>
                                                style="display: none;"
                                                value="<?= $_row['ACCCOUNT_TYPE'] ?>"><?= $_row['ACCCOUNT_NO_NAME'] ?></option>

                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-sm-12">
                            <label class="col-sm-4 control-label">ملاحظات سند الإدخال</label>
                            <div class="col-sm-8">
                                <input type="text" data-val="true" name="declaration" id="txt_declaration"
                                       class="form-control" dir="rtl">
                                <input type="hidden" data-val="true" name="financial_declaration"
                                       id="txt_financial_declaration" class="form-control" dir="rtl">

                            </div>
                        </div>

                        <div class="form-group col-sm-4">
                            <label class="col-sm-4 control-label">نوع اللجنة</label>
                            <div class="col-sm-6">
                                <select name="class_input_class_type" style="width: 250px"
                                        id="dp_class_input_class_type" class="form-control">
                                    <?php if ($is_converted_r == '1') {
                                        foreach ($class_input_class_type as $row) :
                                            if ($receipt_data['CLASS_INPUT_CLASS_TYPE'] == $row['COMMITTEES_ID']) {
                                                ?>
                                                <option value="<?= $row['COMMITTEES_ID'] ?>"><?= $row['COMMITTEES_NAME'] ?></option>";
                                            <?php }
                                        endforeach;
                                    } else { ?>
                                        <option></option>
                                        <?php foreach ($class_input_class_type as $row) : ?>
                                            <option value="<?= $row['COMMITTEES_ID'] ?>"><?= $row['COMMITTEES_NAME'] ?></option>
                                        <?php endforeach; ?><?php } ?>
                                </select>


                            </div>
                        </div>

                        <div class="form-group col-sm-4">
                            <label class="col-sm-4 control-label">رقم الإرسالية</label>
                            <div class="col-sm-6">
                                <input type="text" data-val="true" name="send_id2" id="txt_send_id2"
                                       class="form-control" dir="ltr">
                            </div>
                        </div>

                        <div class="form-group col-sm-4">
                            <label class="col-sm-5 control-label">تاريخ الهبة</label>
                            <div class="col-sm-6">
                                <input type="text" name="grant_date" data-type="date" data-date-format="DD/MM/YYYY"
                                       data-val-regex="التاريخ غير صحيح!"
                                       data-val-regex-pattern="<?= date_format_exp() ?>" data-val="false"
                                       data-val-required="حقل مطلوب" id="txt_grant_date" class="form-control ">
                                <span class="field-validation-valid" data-valmsg-for="grant_date"
                                      data-valmsg-replace="true"></span>

                            </div>
                        </div>

                        <?php if ($financial_chain != 0) { ?>
                            <div class="form-group col-sm-4">
                                <label class="col-sm-5 control-label"> رقم القيد المالي </label>
                                <div class="col-sm-6">
                                    <a target="_blank" href="<?php //if (HaveAccess($financial_chain_url))
                                    echo $financial_chain_url . "/" . $financial_chain . "/index?type=4"; ?>"><?= $financial_chain ?>
                                        <a>

                                </div>
                            </div>
                        <?php } ?>
                        <?php if ($receipt_class_input_date != '') { ?>
                            <div class="form-group col-sm-4">
                                <label class="col-sm-5 control-label"> تاريخ استلام المواد :</label>
                                <label class="col-sm-6"> <?= $receipt_class_input_date ?></label>
                            </div> <?php } ?>
                        <?php if ($invoice_class_seq > 0) { ?>
                            <div class="form-group col-sm-4">
                                <div class="col-sm-6">
                                    <a target="_blank" href="<?php
                                    echo $invoice_url . "/" . $invoice_class_seq . "/edit/4"; ?>">
                                        <label class="col-sm-5 control-label"> فاتورة الشراء</label></a>
                                </div>
                            </div>
                        <?php } ?>

                        <?php if ($count_codes > 0) { ?>
                            <div class="form-group col-sm-4">
                                <div class="col-sm-6">
                                    <a target="_blank" href="<?php
                                    echo $print_codes_url . "/" . $class_input_id; ?>">
                                        <label class="col-sm-5 control-label">طباعة الباركود</label></a>
                                </div>
                            </div>
                        <?php } ?>

                        <?php if ($donation_file_id > 0) { ?>
                            <div class="form-group col-sm-4">
                                <div class="col-sm-6">

                                    <a target="_blank" href="<?php
                                    echo $donation_view_url . "/" . $donation_file_id . "/1"; ?>">
                                        <label class="col-sm-5 control-label"> عرض المنحة</label></a>

                                </div>
                            </div>
                        <?php } ?>

                    </fieldset>
                    <hr/>
                    <fieldset>
                        <legend> بيانات الأصناف</legend>


                        <div style="clear: both;" id="classes">
                            <input type="hidden" id="h_data_search"/>
                            <?php
                            echo modules::run('stores/stores_class_input/public_get_details', (count($receipt_data)) ? $receipt_data['CLASS_INPUT_ID'] : 0);


                            ?>

                        </div>
                    </fieldset>

                    <hr/>
                    <hr/>

                    <div class="modal-footer">

                        <?php

                        if ((HaveAccess($create_url) || (HaveAccess($edit_url) && $can_edit)) && ($class_input_case == 1)) echo "<button type='submit' id='sub' data-action='submit' class='btn btn-primary'>حفظ البيانات</button>";
                        if (($action == 'edit') && (HaveAccess($adopt_url)) && ($class_input_case == 1)) echo "<button type='button' id='adopt' onclick='{$TB_NAME}_adopt(this);' class='btn btn-primary' data-dismiss='modal'>اعتماد </button> ";
                        if (($action == 'edit') && (HaveAccess($transfer_url)) && ($class_input_case == 2) && ($class_input_type1 == 1)) {
                            echo "<button type='button' id='transfer' onclick='{$TB_NAME}_transfer(this);' class='btn btn-primary' data-dismiss='modal'/>";
                            if ($is_convert == 1) echo "محول لفاتورة شراء"; else echo "تحويل لفاتورة شراء";
                        }
                        if (($action == 'edit')) { ?>
                            <button type="button" id="print_rep" onclick="javascript:print_rep();"
                                    class="btn btn-success"><i class="glyphicon glyphicon-print"></i> طباعة
                            </button>
                        <?php }


                        /*   if(HaveAccess($record_url)) echo " <button type='button' id='recordd' onclick='{$TB_NAME}_record(this);' class='btn btn-primary' data-dismiss='modal'>حفظ محضر الفحص والاستلام</button>";
                           if(HaveAccess($return_url)) echo "<button type='button' id='returnn' onclick='{$TB_NAME}_return(1);' class='btn btn-primary' data-dismiss='modal'>  إرجاع  </button> "; */ ?>
                        <?php if (($action == 'edit') and $donation_file_id > 0) { ?>
                            <button type="button" id="print_repd" onclick="javascript:print_repd();"
                                    class="btn btn-success"><i class="glyphicon glyphicon-print"></i> مقارنة المنحة بسند
                                الإدخال
                            </button>
                        <?php } ?>
                        <?php if (HaveAccess($adopt_0_url) and ($action == 'edit') and $class_input_case < 3 and $class_input_case != 0) : ?>
                            <button type="button" id="btn_0_adopt" onclick='javascript:cancel_request(this);'
                                    class="btn btn-danger">الغاء الطلب
                            </button>
                        <?php endif; ?>
                        <?php if ((HaveAccess($create_url)) and $action == 'index'): ?>
                            <button type="button" onclick="javascript:clear_form();" class="btn btn-default"> تفريغ
                                الحقول
                            </button>
                        <?php endif; ?>

                    </div>
                    <div style="clear: both;">
                        <?php echo $HaveRs ? modules::run('attachments/attachment/index', $stores_class_input_id, 'stores_input') : ''; ?>
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

   $('input[name="price_class_id[]"]').prop('readonly',true);
    $('input[name="taxable_det[]"]').prop('readonly',true);
    $('input[name="price[]"]').prop('readonly',true);

   $.each(class_unit_json, function(i,item){
        select_class_unit += "<option value='"+item.CON_NO+"' >"+item.CON_NAME+"</option>";
    });

    $('select[name="unit_class_id[]"]').append(select_class_unit);
    $('select[name="unit_class_id[]"]').each(function(){
        $(this).val($(this).attr('data-val'));
    });
   $('select[name="unit_class_id[]"]').select2();


   ////////////////////////
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
       chk_customer_account_type() ;
         var isdonation= $('#dp_store_id').find(':selected').attr('data-isdonation');
         var _type =$('#dp_account_type').val();

            if(_type == 1){
              if(isdonation==1)
              _showReport('$select_accounts_url/'+$(obj).attr('id')+'/-1/-1/3/'+$('#dp_store_id').val()  );
              else  if(isdonation==0)
                _showReport('$select_accounts_url/'+$(obj).attr('id') );
                }

            if(_type == 2)
                 _showReport('$customer_url/'+$(obj).attr('id')+'/1');


    }
 ///////////////////////////

function after_selected(){
var id_v=$('#h_txt_customer_name').val();

  get_data('{$donation_url}',{id:id_v},function(data){

           $.each(data, function(i,item){
              $('#txt_donation_file_id').val(item.DONATION_FILE_ID);

              $('select[name="store_id"]').select2("val",item.STORE_ID);
           
              $('select[name="store_id"]').select2("readonly",true);
           });

              if ( $('#txt_donation_file_id').val()>0){
                  var x= $('#txt_donation_file_id').val();
                  get_data('{$donation_details_url}',{id:x},function(data){
                  $('#classes').html(data);
                  $('select[name="unit_class_id[]"]').select2();

                   // calc_values_with_donation()
                    calcTotal();
                    reBind();
                  },'html');
////////////////
              }
});

}

$(document).ready(function() {

    $('#dp_store_id').select2().on('change',function(){

        //     checkBoxChanged();

          var isdonation= $('#dp_store_id').find(':selected').attr('data-isdonation');
          if (isdonation==1){
         $('#txt_customer_name').val('') ;
         $('#h_txt_customer_name').val('') ;
           selectAccount($('#txt_customer_name'));

          }
    });

if  ('{$is_converted_r}'!='1' &&  $('#txt_donation_file_id').val()==0){
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
      $('#dp_class_input_type').change(function(e){

         if ($('#dp_class_input_type').val()!=2)
$('input[name="price[]"]').prop("readonly",true);
else
$('input[name="price[]"]').prop("readonly",false);


        });

 $('#print_rep').click(function(){
        //_showReport('$print_url'+'report=STORES_CLASS_INPUT_SEND&params[]={$stores_class_input_id}');
        _showReport('$report_url'+'&report=stores_class_input_send&p_class_input_id={$stores_class_input_id}');
    });
     $('#print_repd').click(function(){
        _showReport('$print_url'+'report=STORES_CLASS_INPUT_DON&params[]={$stores_class_input_id}');
    });
});



$('button[data-action="submit"]').click(function(e){
	 $(this).attr('disabled','disabled');
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
         if ($('#txt_donation_file_id').val()==0){
        _showReport('$select_items_url/'+$(this).attr('id')+'/1'+ $('#h_data_search').val() );
}else{
  _showReport('$select_donation_items_url/'+$('#txt_donation_file_id').val()+'/'+$(this).attr('id')+ $('#h_data_search').val() );
}

    });

  if ($('#txt_donation_file_id').val()==0){
         $('input[name="h_class_id[]"]').bind('keyup', '+', function(e) {
   $(this).val( $(this).val().replace('+', ''));
    var id_v=$(this).closest('tr').find('input[name="class_id[]"]').attr('id');
  _showReport('$select_items_url/'+id_v+'/1'+ $('#h_data_search').val());
           });
}

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
                     if(item.CLASS_STATUS==1){
                    name.val(item.CLASS_NAME_AR);
                      p.val(item.CLASS_PURCHASING);
                unit.select2("val", item.CLASS_UNIT_SUB);
                am.focus();} else{   d.val('');
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
if ($('#dp_class_input_type').val()!=2)
$('input[name="price[]"]').prop("readonly",true);
else
$('input[name="price[]"]').prop("readonly",false);
}





function addRow(){

//if($('input',$('#stores_class_input_detailTbl')).filter(function() { return this.value == ""; }).length <= 0){

    var html ='<tr><td><i class="glyphicon glyphicon-sort" /></li> <input type="hidden" value="0" name="h_ser[]" id="h_ser_'+count+'" class="form-control col-sm-16" > <input type="hidden" name="donation_file_ser[]" id="h_donation_file_ser_'+count+'" value="0"  class="form-control col-sm-3"> <input type="hidden" name="h_class_price_no_vat[]" id="h_class_price_no_vat_'+count+'" value="0"  ></td>'+
      '<td><input type="text" name="h_class_id[]"    data-val="true"  data-val-required="حقل مطلوب"   id="h_class_id_'+count+'"   class="form-control col-sm-2"></td>'+
     '<td><input name="class_id[]"   data-val="true"  data-val-required="حقل مطلوب"   id="class_id_'+count+'" readonly class="form-control col-sm-16"></td>'+
     '<td><select name="unit_class_id[]" class="form-control" id="unit_class_id_'+count+'" /></select></td>'+
       '<td  id="class_type_class_id_'+count+'" >جديد</td>'+
     '<td><input name="amount[]"   data-val="true"  id="amount_'+count+'"    data-val-required="حقل مطلوب"   data-val-regex="المدخل غير صحيح!"   class="form-control" > </td>'+
     '<td><input name="price_class_id[]" type="hidden"  data-val="false"  readonly id="price_class_id_'+count+'"    data-val-required="حقل مطلوب"   data-val-regex="المدخل غير صحيح!"   class="form-control" > '+
       '  <input name="taxable_det[]" type="hidden"  value="2" data-val="false"  id="taxable_det_'+count+'"    data-val-required="حقل مطلوب"   data-val-regex="المدخل غير صحيح!"   class="form-control"> '+
    '<input name="price[]" type="number"  value="0"  id="price_'+count+'"  class="form-control" ></td>'+
     '<td><input name="avg_price[]" readonly type="number"  value="0"  id="avg_price_'+count+'"  class="form-control" ></td>'+
  '<td><input name="total[]" type="number"  value="0"  id="total_'+count+'"  class="form-control" ></td></tr>';

   $('#stores_class_input_detailTbl tbody').append(html);




    reBind(1);
$('input[id="amount_'+count+'"]').bind("change",function(e){
 calcTotal();

            });
          if ($('#txt_donation_file_id').val() != 0){
    $('input[id="h_class_id_'+count+'"]').prop("readonly",true);
           }
 count = count+1;


//  }
}

   function AddRowWithData(id,name_ar,unit,price,unit_name){
        addRow();



        $('#class_id_'+(count -1)).val(id+': '+name_ar);
        $('#h_class_id_'+(count -1)).val(id);

         $('#unit_class_id_'+(count -1)).select2("val", unit);

        $('#price_class_id_'+(count -1)).val(price);
$('#price_'+(count -1)).val(price);

        $('#report').modal('hide');
    }
/*
$('input[name="amount[]"]').bind("change",function(e){
 calcTotal();
            });*/

$('input[name="price[]"]').bind("change",function(e){
 calcTotal();
            });

  /* function calcTotal(){

    var ATotal=0;

             $('input[name="amount[]"]').each(function(i){
                      var amount = $(this).val();
                     var price= $(this).closest('tr').find('input[name="price[]"]').val();

                      var total =isNaNVal( amount * price,2);
                        $(this).closest('tr').find('input[name="total[]"]').val(total);
                      ATotal +=total;


                });
                $('#inv_total').text( isNaNVal(ATotal));


        }*/


function calc_values_with_donation(){
//alert('calc_values_with_donation');
var donation_file_id_v=$('#txt_donation_file_id').val();
var class_input_date_v=$('#txt_class_input_date').val();
   if(donation_file_id_v>0){
  // alert('x');
      get_data('{$get_don_curr_val}',{donation_file_id:donation_file_id_v,class_input_date:class_input_date_v},function(data){
            //   alert(data);
               $('#txt_donation_curr_value').val(Number(data));
                    $('input[name="h_class_price_no_vat[]"]').each(function(i){

                      var class_price_no_vat = $(this).val();
                      var price = class_price_no_vat * Number(data);
//alert('P'+class_price_no_vat);
                      $(this).closest('tr').find('input[name="price[]"]').val(price);
                  });
                    setTimeout(function(){}, 1000);

      });

    }

}

 function calcTotal(){
 //alert('a');
      calc_values_with_donation();
   //  alert('b');
   setTimeout(function(){

      var tax = $('#txt_vat_value').val();

             var vat_type = $('#dp_vat_type').val();

             var AllTotal = 0;
             var ATotal = 0;
             var TotalWithVat = 0;
             var TotalWithOutVat = 0;

             var TotalWithVat_tax = 0;
             var TotalWithOutVat_tax = 0

             $('input[name="taxable_det[]"]').each(function(i){
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

     /*   function calcTotal(){
     //   alert('calcTotal');
        calc_values_with_donation();

      var tax = $('#txt_vat_value').val();
             var vat_type = $('#dp_vat_type').val();
             var AllTotal = 0;
             var ATotal = 0;
             var TotalWithVat = 0;
             var TotalWithOutVat = 0;

             var TotalWithVat_tax = 0;
             var TotalWithOutVat_tax = 0

             $('input[name="taxable_det[]"]').each(function(i){
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


$('#txt_donation_file_id,#txt_class_input_date,input[name="amount[]"]').change(function(e){
//calc_values_with_donation();
calcTotal();
  reBind();
});



 // calc_values_with_donation();
  //  calcTotal();
  //reBind();

SCRIPT;


if (HaveAccess($create_url) and $action == 'index') {

    $scripts = <<<SCRIPT
<script>
  $(function() {
        $( "#stores_class_input_detailTbl tbody" ).sortable();
    });
  {$shared_js}
reBind();

    function clear_form(){
        clearForm($('#{$TB_NAME}_from'));

    }



</script>

SCRIPT;
    sec_scripts($scripts);

} else
    if (HaveAccess($edit_url) and $action == 'edit') {


        $edit_script = <<<SCRIPT


<script>
  {$shared_js}

  $('#txt_class_input_id').val('{$receipt_data['CLASS_INPUT_ID']}');
    $('#txt_donation_file_id').val('{$receipt_data['DONATION_FILE_ID']}');
    if ('{$receipt_data['DONATION_FILE_ID']}'>0) {
    $('input[name="h_class_id[]"]').prop("readonly",true);
    }
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
$('#dp_vat_type').val( '{$receipt_data['VAT_TYPE']}');
$('#txt_vat_account_id').val( '{$receipt_data['VAT_ACCOUNT_ID']}');
$('#txt_vat_value').val( '{$receipt_data['VAT_VALUE']}');

$('#txt_year_order').val( '{$receipt_data['YEAR_ORDER']}');
$('#txt_invoice_inbox').val( '{$receipt_data['INVOICE_INBOX']}');
$('#txt_grant_date').val( '{$receipt_data['GRANT_DATE']}');
$('#txt_send_id2').val( '{$receipt_data['SEND_ID2']}');
 $('#db_customer_account_type').val('{$receipt_data['CUSTOMER_ACCOUNT_TYPE']}');
    chk_customer_account_type();
if  ($('#txt_class_input_case').val()==1){}

function {$TB_NAME}_adopt(obj){
if ($('#txt_class_input_case').val()==2){
  success_msg('الطلب معتمد','تحذير');
}else{
	if (isDoubleClicked($(obj))) return;
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

function cancel_request(obj){
if  ('{$is_convert}'=='1'){
  danger_msg('تحذير','الطلب محول لفواتورة أو قيد مالي ولا يمكن إلغاؤه');
}
if('{$is_extened}'=='1'){
  danger_msg('تحذير','الطلب له مستخلص ولا يمكن إلغاؤه');
}
    
else{
	 if (isDoubleClicked($(obj))) return;
    if(confirm('هل تريد إتمام العملية ؟')){
   

    get_data('{$adopt_0_url}',{id:{$receipt_data['CLASS_INPUT_ID']}},function(data){

     if(data ==1){
     console.log(data);
                      success_msg('رسالة','تمت العملية بنجاح');
                      setTimeout(function(){

                  window.location.reload();

                }, 1000);
                }else
              danger_msg('تحذير..','لا يمكن الغاء سند يتسبب برصيد سالب');
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
/*
    get_data('{$transfer_url}',{id:{$receipt_data['CLASS_INPUT_ID']}},function(data){
      if(data )
                     console.log(data);
                       setTimeout(function(){

                       get_to_link('{$invoice_edit}/'+data+'/edit/1');

                    }, 1000);

                },'html');
                */

}
}else{
 danger_msg('تحذير..','يجب اعتماد الطلب أولا');

}
}
}

if  ('{$is_converted_r}'=='1'){

$('input[name="order_id"]').prop("readonly",true);
//account_type
//class_input_type
//class_input_class_type
$('input[name="ORDER_ID_TEXT"]').prop("readonly",true);
$('input[name="grant_date"]').prop("readonly",true);
 //$('#dp_store_id').select2("readonly",true);

$('input[name="amount[]"]').prop("readonly",true);


}



   // calc_values_with_donation();

   // calcTotal();
    reBind();


 if ($('#txt_donation_file_id').val()>0){
   $('select[name="store_id"]').prop('readonly',true);
}
</script>
SCRIPT;
        sec_scripts($edit_script);

    }
?>