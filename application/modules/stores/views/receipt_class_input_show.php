<?php
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 08/12/14
 * Time: 12:41 م
 */
$MODULE_NAME = 'stores';
$MODULE_NAME1 = 'purchases';
$TB_NAME = 'receipt_class_input';
$TB_NAME2 = 'receipt_class_input_detail';
$TB_NAME3 = 'receipt_class_input_group';
$TB_NAME4 = 'orders';
$get_url = base_url("$MODULE_NAME/$TB_NAME/get_id");
$create_url = base_url("$MODULE_NAME/$TB_NAME/create");
$edit_url = base_url("$MODULE_NAME/$TB_NAME/get_id");
$delete_url = base_url("$MODULE_NAME/$TB_NAME/delete");
$adopt_url = base_url("$MODULE_NAME/$TB_NAME/adopt");
$record_url = base_url("$MODULE_NAME/$TB_NAME/record");
$return_url = base_url("$MODULE_NAME/$TB_NAME/returnp");
$donation_url = base_url('donations/donation/public_get_donation_by_store_input');
$donation_details_url = base_url('donations/donation/public_get_donation_receipt_details');
$orders_details_url = base_url('purchases/orders/public_get_order_receipt_details');

$get_items_tb_url = base_url("$MODULE_NAME/$TB_NAME/public_get_details");


$donation_view_url = base_url("donations/donation/get");
$customer_url = base_url('payment/customers/public_index');

$delete_details_url = base_url("$MODULE_NAME/$TB_NAME2/delete");
$delete_groups_url = base_url("$MODULE_NAME/$TB_NAME3/delete");

$transform_url = base_url("$MODULE_NAME/$TB_NAME/transform");
$select_accounts_url = base_url('financial/accounts/public_select_accounts');

$print_url = 'https://itdev.gedco.ps/gfc.aspx?data=' . get_report_folder() . '&';
$print_url1 = 'https://itdev.gedco.ps/gfc.aspx?data=&';

$report_url = base_url("JsperReport/showreport?sys=financial/stores");

$back_url = base_url("$MODULE_NAME/$TB_NAME/index");
$get_class_url = base_url('stores/classes/public_get_id');
$stores_class_input_view_url = base_url('stores/stores_class_input/get_id');
$get_receipt_record_url = base_url("$MODULE_NAME/$TB_NAME/get_record_id");
$project_accounts_url = base_url('projects/projects/public_select_project_accounts');

if ($action == 'index') {
    $receipt_data = array();
    $send_case = 1;
    $record_case = 0;
    $return_case = 0;
    $receipt_class_input_id = 0;
    $old_class_input_class_type = 0;
    $donation_file_id = 0;
    $stores_class_seq = 0;
    $order_id = '';
    $type_matters = '';
    $order_id_ser = '';
    $type_matters_check=0;
    $real_order_id='';
    $purchase_date='';
} else {
    $send_case = isset($receipt_data['SEND_CASE']) ? $receipt_data['SEND_CASE'] : 1;
    $record_case = (isset($receipt_data['RECORD_CASE']) && ($receipt_data['RECORD_CASE'] != '')) ? $receipt_data['RECORD_CASE'] : 0;
    $return_case = (isset($receipt_data['RETURN_CASE']) && ($receipt_data['RETURN_CASE'] != '')) ? $receipt_data['RETURN_CASE'] : 0;
    $receipt_class_input_id = (isset($receipt_data['RECEIPT_CLASS_INPUT_ID'])) ? $receipt_data['RECEIPT_CLASS_INPUT_ID'] : 0;
    $old_class_input_class_type = (isset($receipt_data['CLASS_INPUT_CLASS_TYPE']) && ($receipt_data['CLASS_INPUT_CLASS_TYPE'] != '')) ? $receipt_data['CLASS_INPUT_CLASS_TYPE'] : 0;
    $donation_file_id = (isset($receipt_data['DONATION_FILE_ID'])) ? $receipt_data['DONATION_FILE_ID'] : 0;
    $stores_class_seq = (isset($receipt_data['STORES_CLASS_SEQ'])) ? $receipt_data['STORES_CLASS_SEQ'] : 0;
    $order_id = $receipt_data['ORDER_ID'];//(isset($receipt_data['ORDER_ID'])) ? $receipt_data['ORDER_ID'] : '';
    $type_matters =$receipt_data['TYPE_MATTER']; //(isset($receipt_data['TYPE_MATTERS'])) ? $receipt_data['TYPE_MATTERS'] : '';
    $order_id_ser = $receipt_data['ORDER_ID_SER'];//(isset($receipt_data['ORDER_ID_SER'])) ? $receipt_data['ORDER_ID_SER'] : '';
    $real_order_id=$receipt_data['REAL_ORDER_ID'];
    $purchase_date=$receipt_data['PURCHASE_DATE'];

    if($type_matters=='')
        $type_matters_check=0;
    else
        $type_matters_check=$type_matters;
}
$select_orders_url = base_url("purchases/orders/public_index_modify");
$HaveRs = count($receipt_data) > 0;
$select_donation_items_url = base_url("donations/donation/public_index");//."?did=$donation_file_id&";

$select_items_url = base_url("$MODULE_NAME/classes/public_index");
$project_data_url = base_url("$MODULE_NAME/$TB_NAME/public_get_projects");
$project_details_url = base_url("$MODULE_NAME/$TB_NAME/public_get_projects_details");
$get_order_id_url = base_url("$MODULE_NAME1/$TB_NAME4/get");


?>
    <div class="row">
        <div class="toolbar">
            <div class="caption"><?= $title ?></div>
            <ul>
                <li><a href="<?= $back_url ?>"><i class="icon icon-reply"></i> </a></li>
            </ul>
        </div>
    </div>
    <div class="form-body">

        <div id="container">
            <form class="form-vertical" id="<?= $TB_NAME ?>_form" method="post"
                  action="<?= base_url("$MODULE_NAME/$TB_NAME/" . ($action == 'index' ? 'create' : $action)) ?>"
                  role="form" >
                <div class="modal-body inline_form">
                    <fieldset class="purchase_data hidden">
                        <legend> بيانات طلب الشراء</legend>
                        <div class="form-group col-sm-1">
                            <label class="control-label">رقم المسلسل </label>
                            <div>
                                <input type="text" value="<?= @$purchase_order_id ?>" name="purchase_order_id"
                                       id="txt_purchase_order_id" class="form-control" readonly/>
                                <input type="hidden" name="order_purpose" value="<?= @$order_purpose ?>"
                                       id="dp_order_purpose">
                            </div>
                        </div>
                        <div class="form-group col-sm-1">
                            <label class="control-label">رقم طلب الشراء </label>
                            <div>
                                <input type="text" readonly value="<?= @$purchase_order_num ?>" name="purchase_order_num"
                                       id="txt_purchase_order_num" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group col-sm-1">
                            <label class="control-label"> عملة عرض السعر </label>
                            <div>
                                <input type="hidden" name="curr_id" value="<?= @$curr_id ?>" id="dp_curr_id2">
                                <input type="text" readonly value="<?= @$curr_id_name ?>" name="curr_id_name"
                                       id="txt_curr_id_name" class="form-control"/>

                            </div>
                        </div>
                        <div class="form-group col-sm-1">
                            <label class="control-label">نوع الطلب</label>
                            <div>
                                <input type="text" readonly value="<?= @$purchase_type_name ?>" name="purchase_type_name"
                                       id="txt_purchase_type_name" class="form-control"/>

                            </div>
                        </div>
                        <div class="form-group col-sm-9">
                            <label class="control-label">بيان الطلب</label>
                            <div>
                                <input type="text" readonly value="<?= @$purchase_notes ?>" name="purchase_notes"
                                       id="txt_purchase_notes" class="form-control"/>
                            </div>
                        </div>
                    </fieldset>
                    <br/>
                    <fieldset>
                        <legend> بيانات الإرسالية</legend>

                        <div class="row">
                            <div class="form-group  col-md-2">
                                <label class="control-label">تاريخ الإدخال</label>
                                <input type="text" disabled name="entery_date" data-type="date"
                                       data-date-format="DD/MM/YYYY" data-val-regex="التاريخ غير صحيح!"
                                       data-val-regex-pattern="<?= date_format_exp() ?>" data-val="true"
                                       data-val-required="حقل مطلوب" id="txt_entery_date" class="form-control ">
                            </div>

                            <div class="form-group  col-md-2">
                                <label class="control-label">تاريخ استلام المواد</label>
                                <input type="text" name="receipt_class_input_date" data-type="date"
                                       data-date-format="DD/MM/YYYY" data-val-regex="التاريخ غير صحيح!"
                                       data-val-regex-pattern="<?= date_format_exp() ?>" data-val="true"
                                       data-val-required="حقل مطلوب" id="txt_receipt_class_input_date"
                                       class="form-control ">
                                <span class="field-validation-valid" data-valmsg-for="receipt_class_input_date"
                                      data-valmsg-replace="true"></span>
                                <input type="hidden" data-val="true" data-val-required="حقل مطلوب"
                                       name="receipt_class_input_id" id="txt_receipt_class_input_id"
                                       class="form-control">
                                <input type="hidden" name="donation_file_id" id="txt_donation_file_id"
                                       class="form-control">
                            </div>

                            <div class="form-group  col-md-2">
                                <label class="control-label" style="text-decoration: underline; font-size: 15px;"> نوع
                                    الأمر </label>
                                <select name="type_matter" class="form-control" data-val="true"
                                        data-val-required="حقل مطلوب" id="dp_type_matter">
                                    <option value="">----اختر----</option>
                                    <?php foreach (@$type_matters_cons as $row) : ?>
                                        <option value="<?= $row['CON_NO'] ?>" <?php if($type_matters == $row['CON_NO']) echo "selected";?>><?= $row['CON_NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="field-validation-valid" data-valmsg-for="type_matter"
                                      data-valmsg-replace="true"></span>
                            </div>


                            <div class="form-group  col-md-2" id="order_id">
                                <label class="control-label"> رقم أمر التوريد </label>
                                <input type="text" data-val="false" data-val-required="حقل مطلوب" name="order_id"
                                       id="txt_order_id" class="form-control" dir="rtl" value="<?=$order_id?>" readonly>
                                <input type="hidden" name="order_id_ser" value="<?=$order_id_ser?>"
                                       id="txt_order_id_ser" class="form-control" dir="rtl" readonly>
                                <span class="field-validation-valid" data-valmsg-for="order_id"
                                      data-valmsg-replace="true"></span>
                            </div>

                            <div class="form-group  col-md-2" id="real_order_id">
                                <label class="control-label"> رقم أمر التوريد الفعلي</label>
                                <input type="text" data-val="false" data-val-required="حقل مطلوب" name="real_order_id" value="<?=$real_order_id?>"
                                       id="txt_real_order_id" class="form-control" dir="rtl"  readonly>
                                <span class="field-validation-valid" data-valmsg-for="real_order_id"
                                      data-valmsg-replace="true"></span>
                            </div>

                            <div class="form-group  col-md-2">
                                <label class="control-label"> المخزن </label>
                                <select name="store_id" class="form-control" id="dp_store_id" data-val="true"
                                        data-val-required="حقل مطلوب">
                                    <option></option>
                                    <?php foreach (@$stores as $row) : ?>
                                        <option data-isdonation="<?= $row['ISDONATION'] ?>"
                                            <?= $HaveRs ? (@$receipt_data['STORE_ID'] == $row['STORE_ID'] ? 'selected' : '') : '' ?>
                                                value="<?= $row['STORE_ID'] ?>"><?= $row['STORE_NO'] . ":" . $row['STORE_NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="field-validation-valid" data-valmsg-for="store_id"
                                      data-valmsg-replace="true"></span>
                            </div>

                            <div class="form-group  col-md-2">
                                <label class="control-label"> نوع المستفيد </label>
                                <select name="account_type" id="dp_account_type" class="form-control">
                                    <?php foreach (@$customer_type as $row) : ?>
                                        <option <?= $HaveRs ? ($receipt_data['ACCOUNT_TYPE'] == $row['CON_NO'] ? 'selected' : '') : '' ?>
                                                value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>


                        </div> <!--first row -->

                        <div class="row">

                            <div class="form-group  col-md-2">
                                <label class="control-label">المورد</label>
                                <input name="customer_name" data-val="true" data-val-required="حقل مطلوب"
                                       class="form-control" readonly id="txt_customer_name" value="">
                                <input type="hidden" name="customer_resource_id" value="" id="h_txt_customer_name">
                                <span class="field-validation-valid" data-valmsg-for="customer_name"
                                      data-valmsg-replace="true"></span>
                            </div>


                            <div class="form-group  col-md-2" style="display: none;" id="div_customer_account_type">
                                <label class="control-label">نوع حساب المستفيد</label>
                                <select class="form-control" id="db_customer_account_type" name="customer_account_type">
                                    <option value="">-----------------</option>
                                    <?php foreach ($ACCOUNT_TYPES as $_row) : ?>

                                        <option <?= $HaveRs ? ($receipt_data['CUSTOMER_ACCOUNT_TYPE'] == $_row['ACCCOUNT_TYPE'] ? 'selected' : '') : '' ?>
                                                style="display: none;"
                                                value="<?= $_row['ACCCOUNT_TYPE'] ?>"><?= $_row['ACCCOUNT_NO_NAME'] ?></option>

                                    <?php endforeach; ?>
                                </select>
                            </div>


                            <!--  <div class="form-group col-sm-4" id="div_customer_account_type" style="display: none">
                            <label class="col-sm-5 control-label">نوع المستفيد</label>
                            <div class="col-sm-6">
                                <select name="customer_account_type" id="dp_customer_account_type" class="form-control" data-val="true"  data-val-required="حقل مطلوب"  >
                                    <option></option>
                                    <?php foreach ($ACCOUNT_TYPES as $row) ://$customer_account_type?>
                                        <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="field-validation-valid" data-valmsg-for="customer_account_type" data-valmsg-replace="true"></span>
                            </div>
                        </div>-->


                            <div class="form-group  col-md-2">
                                <label class="control-label">رقم إرسالية المورد</label>
                                <input type="text" data-val="true" data-val-required="حقل مطلوب" name="send_id"
                                       id="txt_send_id" class="form-control" dir="ltr">
                                <span class="field-validation-valid" data-valmsg-for="send_id"
                                      data-valmsg-replace="true"></span>
                            </div>


                            <div class="form-group  col-md-2">
                                <label class="control-label">تاريخ إرسالية المورد</label>
                                <input type="text" name="send_date" data-type="date" data-date-format="DD/MM/YYYY"
                                       data-val-regex="التاريخ غير صحيح!"
                                       data-val-regex-pattern="<?= date_format_exp() ?>" data-val="true"
                                       data-val-required="حقل مطلوب" id="txt_send_date" class="form-control ">
                                <span class="field-validation-valid" data-valmsg-for="send_date"
                                      data-valmsg-replace="true"></span>
                            </div>


                            <div class="form-group  col-md-4">
                                <label class="control-label">ملاحظات إرسالية المورد</label>
                                <input type="text" data-val="true" name="send_hints" id="txt_send_hints"
                                       class="form-control" dir="rtl">
                                <input type="hidden" data-val="true" name="send_case" id="txt_send_case"
                                       class="form-control" dir="rtl">

                            </div>


                        </div> <!--second row -->

                        <div class="row" id="records_data">

                            <div class="form-group  col-md-2">
                                <label class="control-label">رقم محضر الفحص و الاستلام</label>
                                <input type="text" data-val="true" name="record_id_text" id="txt_record_id_text"
                                       class="form-control" dir="rtl">
                                <input type="hidden" data-val="true" name="record_id" id="txt_record_id"
                                       class="form-control" dir="rtl">
                                <input type="hidden" data-val="true" name="record_case" id="txt_record_case"
                                       class="form-control" dir="rtl">
                            </div>

                            <div class="form-group  col-md-2">
                                <label class="control-label">قائمة الملاحظات</label>
                                <select id="dp_record_declaration_list" name="record_declaration_list"
                                        class="form-control">
                                    <option value="">-----------</option>
                                    <option>قامت اللجنة بفحص المواد المذكورة اعلاه و قبلتها</option>
                                    <option>قامت اللجنة بفحص المواد المذكورة بملاحظات</option>
                                    <option>قامت اللجنة بفحص المواد المذكورة اعلاه و رفضتها</option>
                                    <option>قامت اللجنة بفحص المواد المذكورة اعلاه وقبلت البعض</option>
                                </select>
                                <span class="field-validation-valid" data-valmsg-for="record_declaration_list"
                                      data-valmsg-replace="true"></span>

                            </div>

                            <div class="form-group  col-md-4">
                                <label class="control-label">ملاحظات أعضاء محضر الفحص والاستلام</label>
                                <input type="text" data-val="true" name="record_declaration"
                                       id="txt_record_declaration" class="form-control" dir="rtl">
                            </div>
                            <div class="form-group  col-md-2">
                                <label class="control-label">ملاحظات الإرجاع </label>
                                <input type="text" data-val="true" name="return_hint" id="txt_return_hint"
                                       class="form-control" dir="rtl">
                                <input type="hidden" data-val="true" name="return_case" id="txt_return_case"
                                       class="form-control" dir="rtl">
                            </div>

                        </div>


                        <div class="row">
                            <div class="form-group  col-md-2">
                                <label class="control-label">نوع اللجنة</label>
                                <input type="hidden" data-val="true" value="<?= $old_class_input_class_type ?>"
                                       name="old_class_input_class_type" id="dp_old_class_input_class_type"
                                       class="form-control" dir="rtl">

                                <select name="class_input_class_type" class="form-control"
                                        id="dp_class_input_class_type" data-val="true" data-val-required="حقل مطلوب">
                                    <option value="">----اختر----</option>
                                    <?php foreach ($class_input_class_type as $row) : ?>
                                        <option <?= $HaveRs ? ($receipt_data['CLASS_INPUT_CLASS_TYPE'] == $row['COMMITTEES_ID'] ? 'selected' : '') : '' ?>
                                                value="<?= $row['COMMITTEES_ID'] ?>"><?= $row['COMMITTEES_NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="field-validation-valid" data-valmsg-for="class_input_class_type"
                                      data-valmsg-replace="true"></span>
                            </div>

                            <div class="form-group  col-md-2">
                                <label class="control-label">اسم المورد</label>
                                <select name="cust_id" id="dp_cust_id" class="form-control">
                                    <option value="">...</option>
                                    <?php foreach ($customer_ids as $row) : ?>
                                        <option <?= $HaveRs ? ($receipt_data['CUST_ID'] == $row['CUSTOMER_ID'] ? 'selected' : '') : '' ?>
                                                value="<?= $row['CUSTOMER_ID'] ?>"><?= $row['CUSTOMER_NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group  col-md-2">
                                <label class="control-label">تاريخ الشراء</label>
                                <input type="text" name="purchase_date" data-type="date" data-date-format="DD/MM/YYYY"
                                       data-val-regex="التاريخ غير صحيح!"
                                       data-val-regex-pattern="<?= date_format_exp() ?>" id="txt_purchase_date" value="<?=$purchase_date?>"
                                       class="form-control">
                            </div>


                            <?php if ($donation_file_id > 0) { ?>
                                <div class="form-group col-sm-4">
                                    <div class="col-sm-6">
                                        <a target="_blank" href="<?php
                                        echo $donation_view_url . "/" . $donation_file_id . "/1"; ?>">
                                            <label class="col-sm-5 control-label"> عرض المنحة</label></a>
                                    </div>
                                </div>
                            <?php } ?>

                            <?php if ($stores_class_seq > 0) { ?>
                                <div class="form-group col-sm-4">
                                    <div class="col-sm-6">
                                        <a target="_blank" href="<?php
                                        echo $stores_class_input_view_url . "/" . $stores_class_seq . "/edit/1"; ?>">
                                            <label class="col-sm-5 control-label"> عرض سند الإدخال</label></a>
                                    </div>
                                </div>
                            <?php } ?>


                            <?php if ($order_id_ser > 0) { ?>
                                <div class="form-group col-sm-4">
                                    <div class="col-sm-6">
                                        <a target="_blank" href="<?php
                                        echo $get_order_id_url . "/" . $order_id_ser . "/1"; ?>">
                                            <label class="col-sm-5 control-label">عرض أمر التوريد</label></a>
                                    </div>
                                </div>
                            <?php } ?>


                        </div>


                    </fieldset>

                    <?php
                    if ($action == 'edit') echo modules::run('attachments/attachment/index', $receipt_data['RECEIPT_CLASS_INPUT_ID'], 'receipt_class_input');
                    ?>
                    <br>
                    <fieldset>
                        <legend> بيانات الأصناف</legend>


                        <div style="clear: both;" id="classes">
                            <input type="hidden" id="h_data_search"/>
                            <?php
                            echo modules::run('stores/receipt_class_input/public_get_details', (count($receipt_data)) ? $receipt_data['RECEIPT_CLASS_INPUT_ID'] : 0);
                            //echo modules::run('stores/receipt_class_input/public_get_details',0);
                            ?>

                        </div>
                    </fieldset>

                    <fieldset id="group_tb">
                        <legend> بيانات الأعضاء</legend>

                        <div style="...">
                            <?php

                            echo modules::run('stores/receipt_class_input/public_get_group_details', (count($receipt_data)) ? $receipt_data['RECEIPT_CLASS_INPUT_ID'] : 0);
                            // echo modules::run('stores/receipt_class_input/public_get_group_details',0);
                            ?>

                        </div>
                    </fieldset>
                    <hr/>

                    <div class="modal-footer">

                        <?php

                        if (((HaveAccess($create_url)) || (HaveAccess($edit_url)) && $can_edit) && ($return_case != 1) && ($send_case != 2)) echo "<button type='submit' id='sub' data-action='submit' class='btn btn-primary'>حفظ البيانات</button>";
                        if (HaveAccess($adopt_url) && ($record_case != 1) && ($record_case != 2) && ($return_case != 1)) {
                            echo "<button type='button' id='adopt' onclick='{$TB_NAME}_adopt();' class='btn btn-primary' data-dismiss='modal'>";
                            if ($send_case == 1) echo "اعتماد"; else echo "إلغاء اعتماد";
                            echo "</button> ";
                        }

                        if (HaveAccess($record_url) && ($send_case == 2) && ($return_case != 1)) {
                            echo " <button type='button' id='recordd' onclick='{$TB_NAME}_record(this);' class='btn btn-primary' data-dismiss='modal'>";
                            if ($record_case == 2) echo "معتمد من لجنة الفحص والاستلام"; else if ($record_case == 1) echo "محول للجنة الفحص و الاستلام"; else if ($record_case == 3) echo "معتمد من المدير العام"; else echo " تحويل للجنة الفحص و الاستلام";
                            echo "</button>";
                        }
                        if (HaveAccess($transform_url) and $record_case == 3 /*!= 1*/ and false) echo " <button  type='button' id='trans' onclick='{$TB_NAME}_transform(this);' class='btn btn-primary' data-dismiss='modal'>تحويل لسند إدخال مخزني </button>";
                        if (HaveAccess($return_url) && ($record_case == 3) && ($return_case == 0) and false) echo "<button type='button' id='returnn' onclick='{$TB_NAME}_return(1);' class='btn btn-primary' data-dismiss='modal'>  إرجاع  </button> "; ?>
                        <?php if (HaveAccess($edit_url) and $action == 'edit') { ?>
                            <button type="button" id="print_rep" onclick="print_rep();"
                                    class="btn btn-success"><i class="glyphicon glyphicon-print"></i> طباعة
                            </button> <?php } ?>
                        <?php if (HaveAccess($edit_url) and $action == 'edit' and $receipt_data['ACCOUNT_TYPE'] == 2) { ?>
                            <button type="button" id="print_repo" onclick="print_repo();"
                                    class="btn btn-success"><i class="glyphicon glyphicon-print"></i> تقرير إجماليات أمر
                                التوريد
                            </button> <?php } ?>
                        <?php if (HaveAccess($edit_url) and $action == 'edit' and $receipt_data['ACCOUNT_TYPE'] == 2) { ?>
                            <button type="button" id="print_repod" onclick="print_repod();"
                                    class="btn btn-success"><i class="glyphicon glyphicon-print"></i>التقرير التفصيلي
                                لإدخالات أمر التوريد
                            </button> <?php } ?>

                        <?php if ((HaveAccess($create_url)) and $action == 'index'): ?>
                            <button type="button" onclick="clear_form();" class="btn btn-default"> تفريغ
                                الحقول
                            </button>
                        <?php endif; ?>

                    </div>
                </div>
            </form>
        </div>

    </div> <!-- /.modal-content -->

<?php
$shared_js = <<<SCRIPT

 
        var count = 0;
        var count1 = 0;
       
        var class_unit_json= {$class_unit};
        var select_class_unit= '';
       
        count = $('input[name="h_class_id[]"]').length;
        count1 = $('input[name="h_group_ser[]"]').length;
        $('#records_data,#trans').hide();
        $('#group_tb').prop('hidden',true);
        
        //$('#approved_amount_0').val(0);
        //$('#approved_amount_0').prop('readonly',true);
        
        $('#dp_class_input_class_type').select2();
        
        $.each(class_unit_json, function(i,item){
            select_class_unit += "<option value='"+item.CON_NO+"' >"+item.CON_NAME+"</option>";
        });
        
       $('select[name="unit_class_id[]"]').append(select_class_unit);
            $('select[name="unit_class_id[]"]').each(function(){
                $(this).val($(this).attr('data-val'));
            });
         $('select[name="unit_class_id[]"]').select2();
         $('select[name="cust_id"]').select2();
    
         function setDefaultCustomerAccount(){
            $('#db_customer_account_type').val(1);
         }
         
          function chk_customer_account_type(){
            if( $('#dp_account_type').val()==2 ){
                $('#div_customer_account_type').show();
            }else{
                $('#div_customer_account_type').hide();
            }
        }
        
    
        function selectAccount(obj){
            var type_matter = $('#dp_type_matter').val();
            if(type_matter != 1){
                chk_customer_account_type() ;
            
                var isdonation= $('#dp_store_id').find(':selected').attr('data-isdonation');
                var _type =$('#dp_account_type').val();
                
                if(_type == 1 && isdonation!=2){
                  if(isdonation==1)
                  _showReport('$select_accounts_url/'+$(obj).attr('id')+'/-1/-1/3/'+$('#dp_store_id').val()  );
                 else  if(isdonation==3)
                    _showReport('$select_accounts_url/'+$(obj).attr('id') );
                 }
            
                if(_type == 2 && isdonation!=2)
                     _showReport('$customer_url/'+$(obj).attr('id')+'/1');
                if(_type == 3)
                    _showReport('$project_accounts_url/'+$(obj).attr('id')+'/3' );
            }
       }
       
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
                      $('select[name="unit_class_id[]"]').select2("readonly",true);
            
                  },'html');
              }
            });
        }
        
         $('#dp_store_id').select2().on('change',function(){
             var type_matter = $('#dp_type_matter').val();
             if(type_matter != 1){
                  var isdonation= $('#dp_store_id').find(':selected').attr('data-isdonation');
                  if (isdonation==1){
                     $('#dp_account_type').val(1) ;
                     $('#txt_customer_name').val('') ;
                     $('#h_txt_customer_name').val('') ;
                    selectAccount($('#txt_customer_name'));
                  } else  if (isdonation==2){
                     $('#dp_account_type').val(3) ;
                      $('#txt_order_id').val('') ;
                     $('#txt_customer_name').val('') ;
                     $('#h_txt_customer_name').val('') ;
                  } else  if (isdonation==3){
                      if ($('#dp_account_type').val()==3)
                      $('#dp_account_type').val(1);
                      $('#txt_customer_name').val('') ;
                      $('#h_txt_customer_name').val('') ;
                  }
              }
         });
        
         if ($('#txt_send_case').val()!=2 &&  $('#txt_donation_file_id').val()==0){
        
                $('#txt_customer_name').bind("focus",function(e){
        
                  selectAccount(this);
        
                });
        
                $('#txt_customer_name').click(function(e){
        
                 selectAccount(this);
        
                });
        
            } //end if
        
         $('#print_rep').click(function(){
                //_showReport('$print_url'+'report=RECPIT_CLASS_INPUT&params[]={$receipt_class_input_id}');
                _showReport('{$report_url}&report_type=pdf&report=recpit_class_input&p_receipt_class_input_id={$receipt_class_input_id}');
            });
        
         $('#print_repo').click(function(){
                  //_showReport('$print_url1'+'report=PURCHASES_STORES_GENERAL_REP&params[]={$order_id}&params[]=');
                  _showReport('{$report_url}&report_type=pdf&report=PURCHASES_STORES_GENERAL_REP&p_order_text_in={$order_id}');
            });
        
         $('#print_repod').click(function(){
                  //_showReport('$print_url1'+'report=PURCHASES_STORES_DETAILES_REP&params[]={$order_id}');
                  _showReport('{$report_url}&report_type=pdf&report=PURCHASES_STORES_DETAILES_REP&p_order_id_in={$order_id}');
            });
    
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

         function receipt_class_input_detail_tb_delete(td,id){
            if(confirm('هل تريد الحذف ؟')){
                ajax_delete_any('$delete_details_url',{id:id},function(data){
                    if(data == '1'){
                        $(td).closest('tr').remove();
                        success_msg('رسالة','تم حذف القيد بنجاح ..');
                    }
                });
            }
        }

         function receipt_class_input_group_tb_delete(td,id){
                if(confirm('هل تريد الحذف ؟')){
                    ajax_delete_any('$delete_groups_url',{id:id},function(data){
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
              $('input[name="class_id[]"]').bind("click",function(e){
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
                      _showReport('$select_items_url/'+id_v+'/1'+ $('#h_data_search').val() );
                });
               }
                 $('input[name="amount[]"]').change(function () {
                 if($('#dp_type_matter').val()==1)
                 {
            var approved_amount_order = $(this).closest('tr').find('input[name="approved_amount_order[]"]').val();
            var amount = $(this).closest('tr').find('input[name="amount[]"]').val();
            $(this).closest('tr').find('input[name="remainder[]"]').val(Number(approved_amount_order)-Number(amount));
            }
        });
            
              $('input[name="h_class_id[]"]').change(function(e){
                   var id_v=$(this).val();
                   var d=$(this).closest('tr').find('input[name="h_class_id[]"]');
                   var name=$(this).closest('tr').find('input[name="class_id[]"]');
                   var unit=$(this).closest('tr').find('select[name="unit_class_id[]"]');
                   var am=$(this).closest('tr').find('input[name="amount[]"]');
                     get_data('{$get_class_url}',{id:id_v, type:1},function(data){
                        if (data.length == 1){
                          var item= data[0];
                          if(item.CLASS_STATUS==1){
                            name.val(item.CLASS_NAME_AR);
                            unit.select2("val", item.CLASS_UNIT_SUB);
                            am.focus();
                           } else{
                              d.val('');
                              name.val('');
                             unit.select2("val", '');
                          }
                        }else{
                             d.val('');
                             name.val('');
                             unit.select2("val", '');
                            }
                    });
              });
            
              if(s){
                $('select#unit_class_id_'+count).append('<option></option>'+select_class_unit).select2();
               }
               $('select[name="unit_class_id[]"]').select2("readonly",true);
        }

         reBind();

         function addRow(){
          //if($('input',$('#receipt_class_input_detailTbl')).filter(function() { return this.value == ""; }).length <= 0){
          //'+ (count+1)+'
              var html ='<tr><td>  <i class="glyphicon glyphicon-sort" /></li><input type="hidden" value="0" name="h_ser[]" id="h_ser_'+count+'" class="form-control col-sm-16" >'+
               '<input type="hidden" name="donation_file_ser[]" id="h_donation_file_ser_class_id_'+count+'" value="0" ></td>'+
              '<td><input type="text" name="h_class_id[]"    data-val="true"  data-val-required="حقل مطلوب"   id="h_class_id_'+count+'"   class="form-control col-sm-2"></td>'+
             '<td><input name="class_id[]"   data-val="true"  data-val-required="حقل مطلوب"   id="class_id_'+count+'" readonly class="form-control col-sm-16"></td>'+
               '<td><select name="unit_class_id[]" class="form-control" id="unit_class_id_'+count+'" /></select></td>'+
              ' <td id="class_type_class_id_'+count+'">جديد</td>'+
              //'<td><input name="approved_amount_order[]"  data-val="true"  id="approved_amount_order_'+count+'"    value="" class="form-control col-sm-16" readonly ></td>'+
               '<td><input name="amount[]"   data-val="true"  id="amount_'+count+'"    data-val-required="حقل مطلوب"   data-val-regex="المدخل غير صحيح!"   class="form-control" ></td> '+
               //'<td><input name="remainder[]"  data-val="true"  id="remainder_'+count+'"    value="" class="form-control col-sm-16" readonly ></td>'+
                '<td><input readonly name="approved_amount[]" value="0" readonly  type="text" id="approved_amount_'+count+'"  class="form-control"></td>'+
                '<td><input name="refused_amount[]" type="text" value="0" readonly id="refused_amount_'+count+'"  class="form-control" readonly></td>'+
                '<td ></td></tr>';
             $('#receipt_class_input_detailTbl tbody').append(html);
             if ($('#txt_donation_file_id').val() != 0){
              $('input[name="h_class_id[]"]').prop("readonly",true);
                 }
                 reBind(1);
                 count = count+1;
             //  }
        }
            
            
           function AddRowWithData(id,name_ar,unit,price,unit_name){
                addRow();
                $('#class_id_'+(count -1)).val(id+': '+name_ar);
                $('#h_class_id_'+(count -1)).val(id);
        
                 $('#unit_class_id_'+(count -1)).select2("val", unit);
        
                $('#price_class_id_'+(count -1)).val(price);
        
        
                $('#report').modal('hide');
           }
    
            function addRowGroup(){
                //if($('input:text',$('#receipt_class_input_groupTbl')).filter(function() { return this.value == ""; }).length <= 0){
                    var html ='<tr><td >'+( count1+1)+' <input type="hidden" value="0" name="h_group_ser[]" id="h_group_ser_'+count1+'>"  class="form-control col-sm-3"> </td>'+
                    '<td><input type="text" name="emp_no[]" data-val="true"  id="emp_no_'+count1+'"  class="form-control col-sm-8"> </td>'+
                     '<td> <input type="text" name="group_person_id[]" data-val="true"   id="group_person_id_'+count1+'"  class="form-control col-sm-8">  </td>'+
                      '<td><input type="text" name="group_person_date[]"  data-val="true"   id="group_person_date_'+count1+'>"   class="form-control">  </td>'+
                     '<td><input type="text" name="member_note[]"  data-val="true"   id="member_note_'+count1+'>"   class="form-control">  </td>'+
                       '<td><input type="checkbox" name="status['+count1+']" checked      id="status_'+count1+'"   class="form-control"></td></tr>';
                    $('#receipt_class_input_groupTbl tbody').append(html);
                     count1 = count1+1;
                //}
            }


             $('#dp_account_type').change(function(e){
                  var isdonation= $('#dp_store_id').find(':selected').attr('data-isdonation');
                
                  if (isdonation==2 ){
                  $('#dp_account_type').val(3) ;
                         project_order_id(order_id);
                   }
             });
            
            
            $('#dp_type_matter').change(function(e){
              var type_matter =$(this).val();
              if(type_matter == 1){
                $('#txt_order_id').attr('readonly', true);
                $('#txt_order_id').val('');
                $('#dp_account_type').val(2);
                $('#div_customer_account_type').show();
              }else{
                 $( ".purchase_data" ).addClass("hidden");
                 $('#txt_order_id').attr('readonly', false);
                 $('#txt_order_id').val('');
                 $('#txt_order_id_ser').val('');
                 $('#txt_customer_name').val('');
                 $('#h_txt_customer_name').val('');
                 $('#div_customer_account_type').hide();
                 $('#db_customer_account_type').val('');
                 $('#dp_cust_id').select2("val",'');
                 $('input[name="amount[]"]').each(function(){
                     $('input[name="amount[]"]').val(0);
                  })
              }  
            });
            
            
            $('#txt_order_id').dblclick(function(e){
                var account_typ =  $('#dp_account_type').val();
                var type_matter =  $('#dp_type_matter').val();
                if(account_typ !=3 && type_matter == 1  ){
                 _showReport('$select_orders_url' );
               
                 }
            });
          
           $('#txt_order_id').change(function(e){
             var order_id= $('#txt_order_id').val();          
             var isdonation= $('#dp_store_id').find(':selected').attr('data-isdonation');
          
             if (isdonation==2 && ($('#dp_account_type').val()==3)){
                 project_order_id(order_id);
              }
           });

       function project_order_id(order_id){
            if('$action'!='edit'){
                get_data('{$project_data_url}',{id:order_id},function(data){
                //  var item=$.parseJSON(data);
                var item=data;
               
                if(item.length>0){
                $('#txt_customer_name').val(item[0].PROJECT_ACCOUNT_NAME);
                $('#h_txt_customer_name').val(item[0].PROJECT_ID);
                if (item[0].TYPE==1)
                danger_msg('تنبيه..','هذا المشروع يتبع لجنة رئيسية');
                        reBind();
                  } else { 
                    danger_msg(' تنبيه..','تأكد من رقم المشروع وتأكد أن المشروع له حساب دفعات-مواطنين    ');
                    }
                 });
              ///////////
                get_data('{$project_details_url}',{id:order_id},function(data){
                      $('#classes').html(data);
                 },'html');
             //////////////
            }
       }

        function after_selected_order(order_id){
            get_data('{$orders_details_url}',{id:order_id},function(data){
             $('#classes').html(data);
             $('select[name="unit_class_id[]"]').select2();
             $('select[name="unit_class_id[]"]').select2("readonly",true);
             //  calcTotal();
             //     reBind();
             },'html');
        }
        
       
 
SCRIPT;


if (HaveAccess($create_url) and $action == 'index') {

    $scripts = <<<SCRIPT
<script>


  {$shared_js}

              $(function() {
                    $( "#receipt_class_input_detailTbl tbody" ).sortable();
                });

                function clear_form(){
                   clearForm($('#{$TB_NAME}_form'));
            
                }
                
                $('#txt_record_id_text').prop('readonly',true);
                $('#txt_record_declaration').prop('readonly',true);
                $('#dp_record_declaration_list').prop('disabled',true);
                $('#txt_return_hint').prop('readonly',true);

                 $('#returnn').hide();
                $('#recordd').hide();
                $('#adopt').hide();

</script>

SCRIPT;
    sec_scripts($scripts);

} else
    if (HaveAccess($edit_url) and $action == 'edit') {


        $edit_script = <<<SCRIPT


<script>
  {$shared_js}
            $('#txt_receipt_class_input_id').val('{$receipt_data['RECEIPT_CLASS_INPUT_ID']}');
            $('#dp_type_matter').val('{$receipt_data['TYPE_MATTER']}');
            var type_meter_check={$type_matters_check};
            if(type_meter_check==1)
                {
                    $( ".purchase_data" ).removeClass("hidden");
                     $('#txt_real_order_id').val( '{$receipt_data['REAL_ORDER_ID']}');
                     $('#txt_order_id').val( '{$receipt_data['ORDER_ID']}');
                }
            else
                {
                    $( ".purchase_data" ).addClass("hidden");
                    $('#txt_order_id').val( '{$receipt_data['ORDER_ID']}');
                }
            $('#txt_purchase_order_id').val('{$receipt_data['PURCHASE_ORDER_ID']}');
            $('#txt_purchase_order_num').val('{$receipt_data['PURCHASE_ORDER_NUM']}');
            $('#txt_curr_id_name').val('{$receipt_data['CURR_ID_NAME']}');
            $('#txt_purchase_type_name').val('{$receipt_data['PURCHASE_TYPE_NAME']}');
            $('#txt_purchase_notes').val('{$receipt_data['PURCHASE_NOTES']}');
           
            $('#txt_order_id_ser').val('{$receipt_data['ORDER_ID_SER']}');
            
            $('#txt_donation_file_id').val('{$receipt_data['DONATION_FILE_ID']}');
        
            if ('{$receipt_data['DONATION_FILE_ID']}'>0) {
                $('input[name="h_class_id[]"]').prop("readonly",true);
            }
        
            $('#txt_receipt_class_input_date').val('{$receipt_data['RECEIPT_CLASS_INPUT_DATE']}');
       
            
            $('#dp_store_id').val( '{$receipt_data['STORE_ID']}');
            $('#h_txt_customer_name').val( '{$receipt_data['CUSTOMER_RESOURCE_ID']}');
            $('#txt_customer_name').val( '{$receipt_data['CUST_NAME']}');
            $('#txt_send_id').val( '{$receipt_data['SEND_ID']}');
            $('#txt_send_case').val( '{$receipt_data['SEND_CASE']}');
            $('#txt_send_hints').val( '{$receipt_data['SEND_HINTS']}');
            $('#txt_record_id').val( '{$receipt_data['RECORD_ID']}');
            $('#txt_record_case').val( '{$receipt_data['RECORD_CASE']}');
            if ( $('#txt_record_case').val()==1 || $('#txt_record_case').val()==2)
              // $('#txt_record_id_text').val( '{$receipt_data['ORDER_ID']}'+'/'+'{$receipt_data['RECORD_ID']}'+'/');
               $('#txt_record_id_text').val( '{$receipt_data['RECEORD_ID_TEXT']}');
             else
               $('#txt_record_id_text').val( '');

            $('#txt_record_declaration').val( '{$receipt_data['RECORD_DECLARATION']}');
            $('#dp_record_declaration_list').val('{$receipt_data['RECORD_DECLARATION_LIST']}');
            $('#txt_return_case').val( '{$receipt_data['RETURN_CASE']}');
            $('#txt_return_hint').val( '{$receipt_data['RETURN_HINT']}');
            $('#dp_cust_id').val( '{$receipt_data['CUST_ID']}');
            $('#txt_entery_date').val( '{$receipt_data['ENTERY_DATE']}');
            $('#txt_send_date').val( '{$receipt_data['SEND_DATE']}');
            $('select[name="cust_id"]').select2("val",'{$receipt_data['CUST_ID']}');
            $('#txt_purchase_date').val('{$receipt_data['PURCHASE_DATE']}');
            $('#db_customer_account_type').val('{$receipt_data['CUSTOMER_ACCOUNT_TYPE']}');
            chk_customer_account_type();
       
            $('#txt_record_id_text').prop('readonly',true);
            $('#txt_record_declaration').prop('readonly',true);
            $('#dp_record_declaration_list').prop('disabled',true);

            function {$TB_NAME}_adopt(){
                if ($('#txt_record_case').val()==2){
                  success_msg('تحذير','الطلب معتمد من لجنة الفحص و الاستلام');
                }else if ($('#txt_record_case').val()== 3){
                    danger_msg('تحذير','الطلب معتمد من قبل المدير العام');
                }else{
                  if(confirm('هل تريد إتمام العملية ؟')){
                        var h= $('#txt_send_hints').val();
                        if ({$receipt_data['SEND_CASE']}==1) c=2;
                        else
                            c=1;
                        get_data('{$adopt_url}',{id:{$receipt_data['RECEIPT_CLASS_INPUT_ID']},case:c,hints:h },function(data){
                          if(data =='1')
                               success_msg('رسالة','تمت العملية بنجاح');
                               setTimeout(function(){
                                   window.location.reload();
                                }, 1000);
                       },'html')
                   }
               }
            }


            function {$TB_NAME}_transform(){
                if ($('#txt_record_case').val()==3){
                    if(confirm('هل تريد إتمام العملية ؟')){
                        var h= $('#txt_send_hints').val();
                        get_data('{$transform_url}',{id:{$receipt_data['RECEIPT_CLASS_INPUT_ID']} },function(data){
                          if(data =='1')
                            success_msg('رسالة','تمت العملية بنجاح');
                            setTimeout(function(){
                               window.location.reload();
                    
                             }, 1000);
                         },'html')
                    }
              }else{
                danger_msg('يجب أن يكون معتمد من قبل المدير العام');
              }
            }

            function {$TB_NAME}_record(obj){
                if ($('#txt_record_case').val()==2 ){
                  success_msg('تحذير','الطلب معتمد من لجنة الفحص و الاستلام');
                }else if ($('#txt_record_case').val()==1 ){
                success_msg('تحذير','الطلب محول  لجنة الفحص و الاستلام');
                } else if ($('#txt_record_case').val()== 3){
                    danger_msg('تحذير الطلب معتمد من قبل المدير العام');   
                 }else{
                    if(confirm('هل تريد إتمام العملية ؟')){
                        var tbl = '#{$TB_NAME}_tb';
                        var container = $('#' + $(tbl).attr('data-container'));
                        var form =  $(obj).closest('form');
                        $(form).attr('action','{$record_url}');
                        ajax_insert_update(form,function(data){
                        success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                                 /////////////////////
                        /*
                          var sub= 'تحويل إشعار استلام مواد رقم {$receipt_class_input_id} للجنة الفحص و الاستلام';
                                                var text= ' تم تحويل اشعار استلام مواد  {$receipt_class_input_id} للجنة الفحص والاستلام';
                                                text+= '<br>للاطلاع افتح الرابط';
                                                text+= '<br>https://{$_SERVER['SERVER_NAME']}{$get_receipt_record_url}/{$receipt_class_input_id}/edit/1/1';
                                                _send_mail($(this),'{$receipt_record_emails}',sub,text);
                        */
                        ////////////////////
                    
                        container.html(data);
                        window.location.reload();
                   },"html");
                 } //end if confirm
              }//if
            }

            function {$TB_NAME}_return(c){
              if(confirm('هل تريد إتمام العملية ؟')){
                var h= $('#txt_return_hint').val();
                get_data('{$return_url}',{id:{$receipt_data['RECEIPT_CLASS_INPUT_ID']},case:c,hints:h },function(data){
                      if(data =='1')
                        success_msg('رسالة','تمت العملية بنجاح');
                        setTimeout(function(){
                              window.location.reload();
                    }, 1000);
                },'html')
             }
           }
            
           if ($('#txt_donation_file_id').val()>0){
               $('select[name="store_id"]').prop('readonly',true);
            }
           
           
           
           /*********************/
           

</script>
SCRIPT;
        sec_scripts($edit_script);

    }
?>