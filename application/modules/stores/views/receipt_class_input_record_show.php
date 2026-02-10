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
$get_url = base_url("$MODULE_NAME/$TB_NAME/get_record_id");
$committee_emails_url = base_url("$MODULE_NAME/$TB_NAME/public_committee_emails");
$committee_emails = modules::run("$committee_emails_url", $this->uri->segment(4));
$SUB = $committee_emails['SUB'];
$TXT = $committee_emails['TXT'];
$EMAIL = $committee_emails['EMAIL'];
$USERNAME = $committee_emails['USERNAME'];
$create_url = base_url("$MODULE_NAME/$TB_NAME/create");
$edit_url = base_url("$MODULE_NAME/$TB_NAME/get_record_id");
$delete_url = base_url("$MODULE_NAME/$TB_NAME/delete");
$adopt_url = base_url("$MODULE_NAME/$TB_NAME/adopt");
$record_url = base_url("$MODULE_NAME/$TB_NAME/record_record");
$record_member_url = base_url("$MODULE_NAME/$TB_NAME/record_record_member");
//$c_manager_emails_url= base_url("$MODULE_NAME/$TB_NAME/public_committee_manager_emails");
//$c_manager_emails= modules::run("$c_manager_emails_url",$this->uri->segment(4),0);
$record2_url = base_url("$MODULE_NAME/$TB_NAME/record_return");
$record3_url = base_url("$MODULE_NAME/$TB_NAME/record_cancel");
$return_url = base_url("$MODULE_NAME/$TB_NAME/returnp");
$transform_url = base_url("$MODULE_NAME/$TB_NAME/transform");
//$get_details_url =base_url("$MODULE_NAME/$TB_NAME2/get_page");
$customer_url = base_url('payment/customers/public_index');
$delete_details_url = base_url("$MODULE_NAME/$TB_NAME2/delete");
$delete_groups_url = base_url("$MODULE_NAME/$TB_NAME3/delete");
$select_items_url = base_url("$MODULE_NAME/classes/public_index");

//$back_url=base_url('payment/financial_payment/'.$action);
//$back_url=base_url("$MODULE_NAME/$TB_NAME/$action");
$back_url = base_url("$MODULE_NAME/$TB_NAME?case=1&type=" . $type);
$store_send_link = base_url('stores/stores_class_input/get_id');
$print_url = 'https://itdev.gedco.ps/gfc.aspx?data=' . get_report_folder() . '&';
$print_url1 = 'https://itdev.gedco.ps/gfc.aspx?data=&';

$report_url = base_url("JsperReport/showreport?sys=financial/stores");
$report_sn = report_sn();

//tareq اعتماد المدير العام
$gfc_domain = gh_gfc_domain();
$manager_adopt_url = base_url("$MODULE_NAME/$TB_NAME/general_manager_adopt");
$manager_adopt_process_url = base_url("$MODULE_NAME/$TB_NAME/general_manager_adopt_process");
$cancel_manager_adopt_url = base_url("$MODULE_NAME/$TB_NAME/general_manager_return_process");
$manager_adopt_email = 'mtastal@gedco.ps';

//اعتماد ادارة اللوازم والخدمات
$services_adopt_url = base_url("$MODULE_NAME/$TB_NAME/SuppliesServicesManagment");
$services_adopt_email = 'aregb@gedco.ps';
$get_order_id_url = base_url("$MODULE_NAME1/$TB_NAME4/get");

//record_case
$send_to_store_users = 0;

if ($action == 'index') {
    $receipt_data = array();
    $send_case = 1;
    $record_case = 0;
    $return_case = 0;
    $receipt_class_input_id = 0;
    $is_convert = 0;
    $count_refused_amount = 0;
    $account_type = 0;
    $return_number_t = '';
    $old_class_input_class_type = 0;
    $order_id = '';
    $head_emp_adopt = 0;
    $head_emp_adopt_name=0;
    $all_emp_adopt = 4;
    $store_id = '';
    $type_matters = '';
    $type_matters_name ='';
    $order_id_ser = '';
    $real_order_id='';
} else {
    $send_case = isset($receipt_data['SEND_CASE']) ? $receipt_data['SEND_CASE'] : 1;
    $record_case = (isset($receipt_data['RECORD_CASE']) && ($receipt_data['RECORD_CASE'] != '')) ? $receipt_data['RECORD_CASE'] : 0;
    $return_case = (isset($receipt_data['RETURN_CASE']) && ($receipt_data['RETURN_CASE'] != '')) ? $receipt_data['RETURN_CASE'] : 0;
    $receipt_class_input_id = (isset($receipt_data['RECEIPT_CLASS_INPUT_ID'])) ? $receipt_data['RECEIPT_CLASS_INPUT_ID'] : 0;
    $is_convert = (isset($receipt_data['IS_CONVERT']) && ($receipt_data['IS_CONVERT'] != '')) ? $receipt_data['IS_CONVERT'] : 0;

    $count_refused_amount = (isset($receipt_data['COUNT_REFUSED_AMOUNT'])) ? $receipt_data['COUNT_REFUSED_AMOUNT'] : 0;
    $account_type = (isset($receipt_data['ACCOUNT_TYPE'])) ? $receipt_data['ACCOUNT_TYPE'] : 0;
    $return_number_t = (isset($receipt_data['RETURN_NUMBER_T'])) ? $receipt_data['RETURN_NUMBER_T'] : '';
    $old_class_input_class_type = (isset($receipt_data['CLASS_INPUT_CLASS_TYPE']) && ($receipt_data['CLASS_INPUT_CLASS_TYPE'] != '')) ? $receipt_data['CLASS_INPUT_CLASS_TYPE'] : 0;
    $order_id = (isset($receipt_data['ORDER_ID'])) ? $receipt_data['ORDER_ID'] : '';
    $head_emp_adopt = (isset($receipt_data['HEAD_EMP_ADOPT'])) ? $receipt_data['HEAD_EMP_ADOPT'] : 0;
    $head_emp_adopt_name = (isset($receipt_data['HEAD_EMP_ADOPT_NAME'])) ? $receipt_data['HEAD_EMP_ADOPT_NAME'] : 0;
    $all_emp_adopt = (isset($receipt_data['ALL_EMP_ADOPT'])) ? $receipt_data['ALL_EMP_ADOPT'] : 4;
    $store_id = (isset($receipt_data['STORE_ID'])) ? $receipt_data['STORE_ID'] : '';
    $type_matters =$receipt_data['TYPE_MATTER'];
    $type_matters_name =$receipt_data['TYPE_MATTER_NAME'];
    $order_id_ser = $receipt_data['ORDER_ID_SER'];
    $store_array = array(71, 198, 9, 10, 244);
    $real_order_id=$receipt_data['REAL_ORDER_ID'];
    if (in_array($store_id, $store_array)) {
        $send_to_store_users = 1;
    } else {
        $send_to_store_users = 0;

    }


}

?>

    <div class="row">
        <div class="toolbar">

            <div class="caption"><?= $title ?></div>

            <ul>
                <?php //HaveAccess($back_url)
                if (TRUE): ?>
                    <li><a href="<?= $back_url ?>"><i class="icon icon-reply"></i> </a> </li><?php endif; ?>
                <!--  <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li> -->
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

                    <fieldset>
                        <legend> بيانات محضر الفحص و الاستلام</legend>
                        <div class="form-group col-sm-4">
                            <label class="col-sm-5 control-label">رقم محضر الفحص و الاستلام</label>
                            <div class="col-sm-6">
                                <input type="hidden" data-val="true" name="record_id" id="txt_record_id"
                                       class="form-control" dir="rtl">
                                <input type="text" readonly data-val="true" name="record_id_text"
                                       id="txt_record_id_text" class="form-control" dir="rtl">

                                <input type="hidden" data-val="true" name="record_case" id="txt_record_case"
                                       class="form-control" dir="rtl">
                                <input type="hidden" name="donation_file_id" id="txt_donation_file_id"
                                       class="form-control">
                                <input type="hidden"
                                       value="<?= (isset($receipt_data['CUSTOMER_ACCOUNT_TYPE']) && ($receipt_data['CUSTOMER_ACCOUNT_TYPE'] != '')) ? $receipt_data['CUSTOMER_ACCOUNT_TYPE'] : 0 ?>"
                                       name="customer_account_type" id="db_customer_account_type"
                                       class="form-control">
                            </div>

                        </div>

                        <div class="form-group col-sm-4">
                            <label class="col-sm-5 control-label"> تاريخ تحويل المحضر </label>
                            <div class="col-sm-6">
                                <input type="text" readonly name="record_user_date" data-date-format="DD/MM/YYYY"
                                       id="txt_record_user_date" class="form-control ">

                            </div>
                        </div>

                        <div class="form-group col-sm-4">
                            <label class="col-sm-5 control-label"> تاريخ استلام المواد </label>
                            <div class="col-sm-6">
                                <input type="text" readonly name="receipt_class_input_date"
                                       data-date-format="DD/MM/YYYY" data-val="true" id="txt_receipt_class_input_date"
                                       class="form-control ">
                                <span class="field-validation-valid" data-valmsg-for="receipt_class_input_date"
                                      data-valmsg-replace="true"></span>
                                <input type="hidden" data-val="true" data-val-required="حقل مطلوب"
                                       name="receipt_class_input_id" id="txt_receipt_class_input_id"
                                       class="form-control">

                            </div>
                        </div>

                        <div class="form-group col-sm-4">
                            <label class="col-sm-5 control-label"> نوع
                                الأمر</label>
                            <div class="col-sm-6">
                                <input type="text" readonly data-val="true" name="txt_type_matter_name"
                                       id="txt_type_matter_name" class="form-control" dir="rtl" value="<?php echo $type_matters_name;?>">
                            </div>
                        </div>

                        <div class="form-group col-sm-4">
                            <label class="col-sm-5 control-label">رقم أمر التوريد(s) </label>
                            <div class="col-sm-6">
                                <input type="text" readonly data-val="false" data-val-required="حقل مطلوب"
                                       name="order_id" id="txt_order_id" class="form-control" dir="rtl" value="<?=$order_id?>">
                                <input type="hidden" name="order_id_ser" value="<?=$order_id_ser?>"
                                       id="txt_order_id_ser" class="form-control" dir="rtl" readonly>
                                <span class="field-validation-valid" data-valmsg-for="order_id"
                                      data-valmsg-replace="true"></span>
                            </div>
                        </div>

                        <div class="form-group col-sm-4">
                            <label class="col-sm-5 control-label"> رقم أمر التوريد(الفعلي) </label>
                            <div class="col-sm-6">
                                <input type="text" readonly data-val="false" data-val-required="حقل مطلوب"
                                       name="real_order_id" id="txt_real_order_id" class="form-control" value="<?=$real_order_id?>" dir="rtl">
                                <span class="field-validation-valid" data-valmsg-for="real_order_id"
                                      data-valmsg-replace="true"></span>
                                <?php if ($order_id_ser > 0) { ?>

                                    <a target="_blank" href="<?php
                                    echo $get_order_id_url . "/" . $order_id_ser . "/1"; ?>">
                                        <label class="col-sm-5 control-label">عرض أمر التوريد</label></a>

                                <?php } ?>
                            </div>
                        </div>

                        <div class="form-group col-sm-4">
                            <label class="col-sm-5 control-label">المخزن</label>
                            <div class="col-sm-6">
                                <input type="hidden" name="store_id" id="dp_store_id">
                                <select name="store_id_d" disabled style="width: 250px" id="dp_store_id_d">
                                    <option></option>
                                    <?php foreach ($stores as $row) : ?>
                                        <option value="<?= $row['STORE_ID'] ?>"><?= $row['STORE_NO'] . ":" . $row['STORE_NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="field-validation-valid" data-valmsg-for="store_id"
                                      data-valmsg-replace="true"></span>

                            </div>
                        </div>
                        <div class="form-group col-sm-4">

                            <label class="col-sm-5 control-label">المورد</label>
                            <div class="col-sm-6">
                                <input type="hidden" name="account_type" value="<?= $account_type ?>"
                                       id="dp_account_type">

                                <input name="customer_name" data-val="true" readonly data-val-required="حقل مطلوب"
                                       class="form-control" readonly id="txt_customer_name" value="">
                                <input type="hidden" name="customer_resource_id" value="" id="h_txt_customer_name">

                                <span class="field-validation-valid" data-valmsg-for="customer_name"
                                      data-valmsg-replace="true"></span>
                            </div>

                        </div>
                        <div class="form-group col-sm-4">
                            <label class="col-sm-5 control-label">رقم إرسالية المورد</label>
                            <div class="col-sm-6">
                                <input type="text" readonly data-val="false" data-val-required="حقل مطلوب"
                                       name="send_id" id="txt_send_id" class="form-control" dir="rtl">
                                <span class="field-validation-valid" data-valmsg-for="send_id"
                                      data-valmsg-replace="true"></span>

                            </div>
                        </div>

                        <div class="form-group col-sm-4">
                            <label class="col-sm-5 control-label">تاريخ إرسالية المورد</label>
                            <div class="col-sm-6">
                                <input type="text" readonly name="send_date" id="txt_send_date" class="form-control"
                                       dir="rtl">

                            </div>
                        </div>

                        <div class="form-group col-sm-4">
                            <label class="col-sm-5 control-label"> تاريخ اعتماد المحضر </label>
                            <div class="col-sm-6">
                                <input type="text" readonly name="record_trans_date" data-date-format="DD/MM/YYYY"
                                       id="txt_record_trans_date" class="form-control ">

                            </div>
                        </div>
                        <div class="form-group col-sm-12">
                            <label class="col-sm-4 control-label"> ملاحظات إرسالية المورد</label>
                            <div class="col-sm-8">
                                <input type="text" readonly data-val="true" name="send_hints" id="txt_send_hints"
                                       class="form-control" dir="rtl">
                                <input type="hidden" data-val="true" name="send_case" id="txt_send_case"
                                       class="form-control" dir="rtl">

                            </div>
                        </div>


                        <div class="form-group  col-sm-12">
                            <label class="col-sm-4 control-label">قائمة الملاحظات </label>
                            <div class="col-sm-8">


                                <select id="dp_record_declaration_list"
                                        name="record_declaration_list"
                                        class="form-control"
                                >
                                    <option></option>
                                    <option>قامت اللجنة بفحص المواد المذكورة اعلاه و قبلتها</option>
                                    <option>قامت اللجنة بفحص المواد المذكورة بملاحظات</option>
                                    <option>قامت اللجنة بفحص المواد المذكورة اعلاه و رفضتها</option>
                                    <option>قامت اللجنة بفحص المواد المذكورة اعلاه وقبلت البعض</option>
                                </select>

                            </div>

                        </div>

                        <div class="form-group  col-sm-12">
                            <label class="col-sm-4 control-label">ملاحظات أعضاء محضر الفحص والاستلام</label>
                            <div class="col-sm-8">
                                <input type="text" data-val="true" name="record_declaration" id="txt_record_declaration"
                                       class="form-control" dir="rtl">


                            </div>

                        </div>
                        <div class="form-group  col-sm-12">
                            <label class="col-sm-4 control-label"> مسلسل الإرجاع </label>
                            <div class="col-sm-3">
                                <input type="text" readonly data-val="true" value="<?= $return_number_t ?>"
                                       name="return_number" id="txt_return_number" class="form-control" dir="rtl">
                            </div>
                        </div>
                        <div class="form-group  col-sm-12">
                            <label class="col-sm-4 control-label">ملاحظات الإرجاع </label>
                            <div class="col-sm-8">
                                <input type="text" <?php if ($return_case == 1) echo "readonly"; ?> data-val="true"
                                       name="return_hint" id="txt_return_hint" class="form-control" dir="rtl">

                                <input type="hidden" data-val="true" name="return_case" id="txt_return_case"
                                       class="form-control" dir="rtl">


                            </div>

                        </div>
                        <div class="form-group col-sm-12">
                            <label class="col-sm-4 control-label">نوع اللجنة</label>
                            <div class="col-sm-6">
                                <input type="hidden" data-val="true" value="<?= $old_class_input_class_type ?>"
                                       name="old_class_input_class_type" id="dp_old_class_input_class_type"
                                       class="form-control" dir="rtl">
                                <input type="hidden" name="class_input_class_type" id="dp_class_input_class_type">
                                <select name="class_input_class_type_d" disabled style="width: 250px"
                                        id="dp_class_input_class_type_d">
                                    <option></option>
                                    <?php foreach ($class_input_class_type as $row) : ?>
                                        <option value="<?= $row['COMMITTEES_ID'] ?>"><?= $row['COMMITTEES_NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select></div>
                        </div>
                        <?php if ($receipt_data['ACCOUNT_TYPE'] == 3) { ?>
                            <div class="form-group col-sm-4">
                                <label class="col-sm-5 control-label">اسم المورد</label>
                                <div class="col-sm-6">
                                    <input type="hidden" name="cust_id" id="dp_cust_id" value="">
                                    <input type="text" readonly data-val="false" data-val-required="حقل مطلوب"
                                           name="cust_id_name" id="txt_cust_id_name" class="form-control" dir="rtl">

                                </div>
                            </div>

                            <div class="form-group col-sm-4">
                                <label class="col-sm-5 control-label">تاريخ الشراء</label>
                                <div class="col-sm-6">
                                    <input type="text" readonly name="purchase_date" id="txt_purchase_date"
                                           class="form-control" dir="rtl">

                                </div>
                            </div>
                        <?php } ?>


                    </fieldset>
                    <hr/>
                    <?php
                    if ($action == 'edit') echo modules::run('attachments/attachment/index', $receipt_data['RECEIPT_CLASS_INPUT_ID'], 'receipt_class_input');
                    ?>
                    <fieldset>
                        <legend> بيانات الأصناف</legend>


                        <div style="clear: both;">

                            <?php
                            echo modules::run('stores/receipt_class_input/public_get_details_records', (count($receipt_data)) ? $receipt_data['RECEIPT_CLASS_INPUT_ID'] : 0);
                            //echo modules::run('stores/receipt_class_input/public_get_details',0);
                            ?>

                        </div>
                    </fieldset>
                    <fieldset>
                        <legend> بيانات الأعضاء</legend>

                        <div style="...">
                            <?php
                            echo modules::run('stores/receipt_class_input/public_get_group_adopt_details', (count($receipt_data)) ? $receipt_data['RECEIPT_CLASS_INPUT_ID'] : 0, $record_case, $is_convert, $type);
                            //echo modules::run('stores/receipt_class_input/public_get_group_details', (count($receipt_data)) ? $receipt_data['RECEIPT_CLASS_INPUT_ID'] : 0);
                            // echo modules::run('stores/receipt_class_input/public_get_group_details',0);
                            ?>

                        </div>
                    </fieldset>
                    <hr/>

                    <div class="modal-footer">

                        <?php

                        if ((HaveAccess($edit_url)) and ($record_case == 1) and $is_convert == 0 and $type != 4 and $head_emp_adopt == 0 and $all_emp_adopt != 0) echo "<button type='submit' id='sub'  data-action='submit' class='btn btn-primary'>حفظ البيانات</button>";
                          if (HaveAccess($record_url) and ($record_case == 1 /*tareqor $record_case == 2*/) and $is_convert == 0 and $type != 4 and $head_emp_adopt == 1 and $all_emp_adopt == 0) echo " <button type='button' id='btn_recordd' onclick='{$TB_NAME}_record(this);' class='btn btn-primary' data-dismiss='modal'>اعتماد الفحص والاستلام</button>";
                          if (HaveAccess($record_url) and ($record_case == 1 /*tareqor $record_case == 2*/) and $is_convert == 0 and $type != 4 and $head_emp_adopt == 1 and $all_emp_adopt == 0) echo " <button type='button' id='btn_recordd' onclick='{$TB_NAME}_record(this);' class='btn btn-primary' data-dismiss='modal'>اعتماد الفحص والاستلام</button>";
                     
                        if (HaveAccess($record3_url) and ($record_case == 1 and $head_emp_adopt!=0 and $head_emp_adopt_name == $this->user->emp_no) and $is_convert == 0) echo " <button type='button' id='recordd30' onclick='{$TB_NAME}_record3(this);' class='btn btn-primary' data-dismiss='modal'>إلغاء اعتماد اللجنة  </button>";
                        if (HaveAccess($record3_url) and ($record_case == 2) and $is_convert == 0) echo " <button type='button' id='recordd3' onclick='{$TB_NAME}_record3(this);' class='btn btn-primary' data-dismiss='modal'>إلغاء اعتماد اللجنة  </button>";

                        //tareq اعتماد المدير العام
                        if (HaveAccess($manager_adopt_url) and ($record_case == 2) and $old_class_input_class_type != 123) echo " <button type='button' id='btn_adopt_manager' onclick='{$TB_NAME}_adopt_manager();' class='btn btn-primary btn_adopt_manager'>اعتماد المدير العام</button>";
                        if (HaveAccess($services_adopt_url) and ($record_case == 2) and $old_class_input_class_type == 123) echo " <button type='button' id='btn_adopt_services' onclick='{$TB_NAME}_adopt_manager();' class='btn btn-primary btn_adopt_manager'>اعتماد ادارة اللوازم والخدمات </button>";


                        if (HaveAccess($cancel_manager_adopt_url) and ($record_case == 3)) echo " <button type='button' id='btn_cancel_adopt_manager' onclick='{$TB_NAME}_cancel_adopt_manager();' class='btn btn-danger'>الغاء اعتماد المدير العام</button>";


                        if (HaveAccess($record2_url) and ($record_case == 1) and $is_convert == 0 and $type != 4 and $head_emp_adopt == 0 and $all_emp_adopt != 0) echo " <button type='button' id='recordd2' onclick='{$TB_NAME}_record2(this);' class='btn btn-primary' data-dismiss='modal'> إرجاع الطلب للمخزن</button>";
                        /*tareq$record_case == 2*/
                        if (HaveAccess($transform_url) and $record_case == 3 /*2*/ and $is_convert == 0 and $type != 4) echo " <button  type='button' id='trans' onclick='{$TB_NAME}_transform(this);' class='btn btn-primary' data-dismiss='modal'>تحويل لسند إدخال مخزني </button>";
                        /*tareq$record_case == 2*/
                        if (HaveAccess($return_url) && ($record_case == 3) && ($return_case == 0) && ($count_refused_amount > 0)) echo "<button type='button' id='returnn' onclick='{$TB_NAME}_return(1);' class='btn btn-primary' data-dismiss='modal'>  إشعار إرجاع مواد مرفوضة  </button> ";
                        /*tareq$record_case == 2*/
                        if (HaveAccess($return_url) && ($record_case == 3) && ($return_case == 1) && ($count_refused_amount > 0)) echo "<button type='button' id='returnn' onclick='{$TB_NAME}_return(0);' class='btn btn-primary' data-dismiss='modal'>إلغاء إشعار مواد مرفوضة </button> ";


                        ?>
                        <?php if (HaveAccess($return_url) && ($return_case == 1)) { ?>
                            <button type="button" id="print_rep1" onclick="javascript:print_rep1();"
                                    class="btn btn-success"><i class="glyphicon glyphicon-print"></i> طباعة إشعار مواد مرفوضة
                            </button>
                        <?php } ?>
                        <?php
                        if (/*$head_emp_adopt == 1 and $all_emp_adopt == 0 and */
                            $record_case == 1) { ?>
                            <button type="button" id="print_rep" onclick="javascript:print_rep();"
                                    class="btn btn-success">
                                <i class="glyphicon glyphicon-print"></i> طباعة
                            </button>
                        <?php } ?>


                        <?php
                        if (/*$head_emp_adopt == 1 and $all_emp_adopt == 0 and */
                            $record_case >= 2) { ?>
                            <button type="button" id="print_rep" onclick="javascript:print_rep();"
                                    class="btn btn-success">
                                <i class="glyphicon glyphicon-print"></i> طباعة
                            </button>
                        <?php } ?>

                        <?php if ($receipt_data['ACCOUNT_TYPE'] == 2) { ?>
                            <button type="button" id="print_repo" onclick="javascript:print_repo();"
                                    class="btn btn-success"><i class="glyphicon glyphicon-print"></i> تقرير إجماليات أمر التوريد
                            </button> <?php } ?>
                        <?php if ($receipt_data['ACCOUNT_TYPE'] == 2) { ?>
                            <button type="button" id="print_repod" onclick="javascript:print_repod();"
                                    class="btn btn-success"><i class="glyphicon glyphicon-print"></i>التقرير التفصيلي لإدخالات أمر التوريد
                            </button> <?php } ?>


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
 $('input[name="amount[]"]').prop('readonly',true);
  $('select[name="unit_class_id[]"]').prop('readonly',true);
   ////////////////////////


    $.each(class_unit_json, function(i,item){
        select_class_unit += "<option value='"+item.CON_NO+"' >"+item.CON_NAME+"</option>";
    });

    $('select[name="unit_class_id[]"]').append(select_class_unit);
    $('select[name="unit_class_id[]"]').each(function(){
        $(this).val($(this).attr('data-val'));
    });
   $('select[name="unit_class_id[]"]').select2();


  $('input[name="approved_amount[]"]').bind("keyup",function(e){

      calc_val($(this));

        });

function calc_val(OBj){
var m= $(OBj).closest('tr').find('input[name="amount[]"]');
var m_val=$(m).val();

var a= $(OBj).closest('tr').find('input[name="approved_amount[]"]');
var a_val=$(a).val();

var r= $(OBj).closest('tr').find('input[name="refused_amount[]"]');

$(r).val(parseFloat(m_val)-parseFloat(a_val));


}

 $('#refused_amount_0').prop('readonly',true);

   ////////////////////////

$(document).ready(function() {

    $('#dp_store_id_d').select2().on('change',function(){

        //     checkBoxChanged();
    });

  $('#print_rep').click(function(){
        _showReport('{$report_url}&report_type=pdf&report=Examination_Receipt_Record&p_id={$receipt_class_input_id}&sn={$report_sn}');
    });
     $('#print_rep1').click(function(){
        //_showReport('$ print_url'+'report=RECPIT_CLASS_INPUT_RETURN&params[]=$ receipt_class_input_id');
        _showReport('{$report_url}&report_type=pdf&report=recpit_class_input_return&p_receipt_class_input_id={$receipt_class_input_id}');
    });
     $('#print_repo').click(function(){
          //_showReport('$ print_url1'+'report=PURCHASES_STORES_GENERAL_REP&params[]=$ order_id&params[]=');
          _showReport('{$report_url}&report_type=pdf&report=PURCHASES_STORES_GENERAL_REP&p_order_text_in={$order_id}');
    });

      $('#print_repod').click(function(){
          //_showReport('$ print_url1'+'report=PURCHASES_STORES_DETAILES_REP&params[]=$ order_id');
          _showReport('{$report_url}&report_type=pdf&report=PURCHASES_STORES_DETAILES_REP&p_order_id_in={$order_id}');
    });

  $('input[name="status[]"]').on("click",function() {
  if($(this).is(':checked')) $(this).val(1);
  else  $(this).val(2);
  });
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

function reBind(s){
  if(s==undefined)
            s=0;
       $('input[name="class_id[]"]').bind("focus",function(e){

        _showReport('$select_items_url/'+$(this).attr('id'));
    });
    $('input[name="amount[]"]').bind("focus",function(e){

        $('input[name="refused_amount[]"]').val($('input[name="amount[]"]').val()-$('input[name="approved_amount[]"]').val());
    });


        if(s){
            $('select#unit_class_id_'+count).append('<option></option>'+select_class_unit).select2();
        }


}


reBind();
function addRow(){

if($('input',$('#receipt_class_input_detailTbl')).filter(function() { return this.value == ""; }).length <= 0){

    var html ='<tr><td>'+ (count+1)+' <input type="hidden" value="0" name="h_ser[]" id="h_ser_'+count+'" class="form-control col-sm-16" ></td>'+
    '<td><input name="class_id[]"   data-val="true"  data-val-required="حقل مطلوب"   id="class_id_'+count+'" readonly class="form-control col-sm-16">'+
    '<input type="hidden" name="h_class_id[]"   data-val="true"  data-val-required="حقل مطلوب"   id="h_class_id_'+count+'"   class="form-control col-sm-16"></td>'+
     '<td><select name="unit_class_id[]" class="form-control" id="unit_class_id_'+count+'" /></select></td>'+
     '<td><input name="amount[]"  data-val="true"  id="amount_'+count+'"    data-val-required="حقل مطلوب"   data-val-regex="المدخل غير صحيح!"   class="form-control" > </td>'+
      '<td ><input name="approved_amount[]" type="text" id="approved_amount_'+count+'"  class="form-control"></td>'+
      '<td><input name="refused_amount[]" type="text" id="refused_amount_'+count+'"  class="form-control" readonly></td><td ></td></tr>';

   $('#receipt_class_input_detailTbl tbody').append(html);




    reBind(1);

 count = count+1;


  }
}


function addRowGroup(){
//if($('input:text',$('#receipt_class_input_groupTbl')).filter(function() { return this.value == ""; }).length <= 0){


    var html ='<tr><td >'+( count1+1)+' <input type="hidden" value="0" name="h_group_ser[]" id="h_group_ser_'+count1+'>"  class="form-control col-sm-3"> </td>'+
    '<td><input type="text" name="emp_no[]" data-val="true"  id="emp_no_'+count1+'"  class="form-control col-sm-8"> </td>'+
     '<td> <input type="text" name="group_person_id[]" data-val="true"   id="group_person_id_'+count1+'"  class="form-control col-sm-8">  </td>'+
      '<td><input type="text" name="group_person_date[]"  data-val="true"   id="group_person_date_'+count1+'>"   class="form-control">  </td>'+
     '<td><input type="text" name="member_note[]"  data-val="true"   id="member_note_'+count1+'>"   class="form-control">  </td>'+
      '<td><input type="checkbox" name="status['+count1+']" checked  value="1" data-val="true"   id="status_'+count1+'>"   class="form-control">  </td></tr>';

  if($('#status_'+count1).is(':checked')) $(this).val(1);
  else  $(this).val(2);

    $('#receipt_class_input_groupTbl tbody').append(html);
  count1 = count1+1;
//}
}



SCRIPT;


if (/*HaveAccess($edit_url) and*/
    $action == 'edit') {

    $edit_script = <<<SCRIPT


<script>
  {$shared_js} 
     $('#txt_donation_file_id').val('{$receipt_data['DONATION_FILE_ID']}');
        $('#txt_receipt_class_input_id').val('{$receipt_data['RECEIPT_CLASS_INPUT_ID']}');
        $('#txt_receipt_class_input_date').val('{$receipt_data['RECEIPT_CLASS_INPUT_DATE']}');
        $('#txt_record_user_date').val('{$receipt_data['RECORD_USER_DATE']}');
        $('#txt_order_id').val( '{$receipt_data['ORDER_ID']}');
        $('#txt_real_order_id').val( '{$receipt_data['REAL_ORDER_ID']}');        
        $('#dp_store_id').val( '{$receipt_data['STORE_ID']}');
        $('#dp_store_id_d').val( '{$receipt_data['STORE_ID']}');
        $('#h_txt_customer_name').val( '{$receipt_data['CUSTOMER_RESOURCE_ID']}');
        $('#txt_customer_name').val( '{$receipt_data['CUST_NAME']}');
        $('#txt_send_id').val( '{$receipt_data['SEND_ID']}');
        $('#txt_send_case').val( '{$receipt_data['SEND_CASE']}');
        $('#txt_send_hints').val( '{$receipt_data['SEND_HINTS']}');
        $('#txt_record_id').val( '{$receipt_data['RECORD_ID']}');
        $('#txt_record_case').val( '{$receipt_data['RECORD_CASE']}');
        if ( $('#txt_record_case').val()==1 || $('#txt_record_case').val()==2)
                    $('#txt_record_id_text').val( '{$receipt_data['RECEORD_ID_TEXT']}');
         else
               $('#txt_record_id_text').val( '');

            $('#txt_record_declaration').val( '{$receipt_data['RECORD_DECLARATION']}');
            $('#dp_record_declaration_list').val('{$receipt_data['RECORD_DECLARATION_LIST']}');
            $('#txt_return_case').val( '{$receipt_data['RETURN_CASE']}');
            $('#txt_return_hint').val( '{$receipt_data['RETURN_HINT']}');
            $('#dp_class_input_class_type').val( '{$receipt_data['CLASS_INPUT_CLASS_TYPE']}');
            $('#dp_class_input_class_type_d').val( '{$receipt_data['CLASS_INPUT_CLASS_TYPE']}');
            $('#txt_record_trans_date').val( '{$receipt_data['RECORD_TRANS_DATE']}');
            $('#txt_send_date').val( '{$receipt_data['SEND_DATE']}');
            $('#dp_cust_id').val( '{$receipt_data['CUST_ID']}');
            $('#txt_cust_id_name').val('{$receipt_data['CUST_ID_NAME']}');
            $('#txt_purchase_date').val('{$receipt_data['PURCHASE_DATE']}');

            if  ( $('#txt_record_case').val()==2){
                $('#btn_recordd').text('إلغاء اعتماد');
         //     $('#sub').hide();
          //      $('#btn_recordd').show();
               $('#txt_record_declaration').prop('readonly',true);
               $('#dp_record_declaration_list').prop('disabled',true);

            }else if  ( $('#txt_record_case').val()==1){
                $('#btn_recordd').text('اعتماد سند فحص و استلام');
            $('#txt_return_hint').prop('readonly',true);
         //   $('#sub').show();
         //       $('#btn_recordd').show();
             $('#txt_record_declaration').prop('readonly',false);
             $('#dp_record_declaration_list').prop('disabled',false);

            }
if  ( $('#txt_return_case').val()==1){

            $('#sub').hide();
                $('#btn_recordd').hide();

            }


//for send email
  var btn__= '';
 $('#btn_recordd').click( function(){
      btn__ = $(this);
 });


function {$TB_NAME}_record(obj){
 if(confirm('هل تريد إتمام العملية ؟')){ 
    var tbl = '#{$TB_NAME}_tb';
    var container = $('#' + $(tbl).attr('data-container'));
    var form =  $(obj).closest('form');
    var type_committe = '{$old_class_input_class_type}';
    $(form).attr('action','{$record_url}');
    ajax_insert_update(form,function(data){
       if(data==1){
            success_msg('رسالة','تم حفظ البيانات بنجاح ..');
            $('button').attr('disabled','disabled');
            var sub= 'اعتماد المدير  لمحضر فحص واستلام';
            var text= 'يرجى اعتماد محضر فحص واستلام';
            text+= '<br>للاطلاع افتح الرابط';
            text+= ' <br>{$gfc_domain}{$get_url}/{$receipt_data['RECEIPT_CLASS_INPUT_ID']}/edit/1/1';
            if (type_committe == "123"){
              
               _send_mail(btn__,'{$services_adopt_email}',sub,text);
                 
            }else{
                _send_mail(btn__,'{$manager_adopt_email}',sub,text);
                _send_mail(btn__,'syaseen@gedco.ps',sub,text);
                _send_mail(btn__,'telbawab@gedco.ps',sub,text);
              
            }
            btn__ = ''; 
           get_to_link(window.location.href);
       }else{
          danger_msg('تحذير..',data);
        }
    },"html");
 }
}
//////////////////////////////////////////////////////////////////
  var btn___= '';
 $('.btn_recordd1').click( function(){
      btn___ = $(this);
 });
function {$TB_NAME}_memebers_record(obj){
 if(confirm('هل تريد إتمام العملية ؟')){ 
    var tbl = '#{$TB_NAME}_tb';
    var container = $('#' + $(tbl).attr('data-container'));
    var form =  $(obj).closest('form');
    var type_committe = '{$old_class_input_class_type}';
   
   
   
    
    $(form).attr('action','{$record_member_url}');
    ajax_insert_update(form,function(data){
       if(data==1){
            success_msg('رسالة','تم حفظ البيانات بنجاح ..');
            $('button').attr('disabled','disabled');

            var sub= '{$SUB}';
            var text= '{$TXT}';
           
            text+= '<br>للاطلاع افتح الرابط';
            text+= ' <br>{$gfc_domain}{$get_url}/{$receipt_data['RECEIPT_CLASS_INPUT_ID']}/edit/1/1';
            _send_mail(btn___,'{$EMAIL}',sub,text);
           btn___ = ''; 
          get_to_link(window.location.href);
       }else{
          danger_msg('تحذير..',data);
        }
    },"html");
 }
}
//////////////////////////////////////////////////////////////////
function {$TB_NAME}_record3(obj){


    if(confirm('هل تريد إتمام العملية ؟')){

 var tbl = '#{$TB_NAME}_tb';
    var container = $('#' + $(tbl).attr('data-container'));
    var form =  $(obj).closest('form');
    $(form).attr('action','{$record3_url}');
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
function {$TB_NAME}_record2(obj){

   if(confirm('هل تريد إتمام العملية ؟')){


 var tbl = '#{$TB_NAME}_tb';
    var container = $('#' + $(tbl).attr('data-container'));
    var form =  $(obj).closest('form');
    $(form).attr('action','{$record2_url}');
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

  function {$TB_NAME}_transform(){

            if(confirm('هل تريد إتمام العملية ؟')){
                var h= '';

                get_data('{$transform_url}',{id:{$receipt_data['RECEIPT_CLASS_INPUT_ID']} },function(data){
                    if(data )
                 

                    setTimeout(function(){

                        get_to_link('{$store_send_link}/'+data+'/edit/1');

                    }, 1000);

                },'html');

            }

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
            },'html');

    }
}


//tareq 
  var btn_mng= '';
 $('#btn_adopt_manager,#btn_adopt_services').click( function(){
      btn_mng = $(this);
 });
function {$TB_NAME}_adopt_manager(){
  if(confirm('هل تريد إتمام العملية ؟')){    
        get_data('{$manager_adopt_process_url}',{receipt_class_input_id:{$receipt_data['RECEIPT_CLASS_INPUT_ID']}},function(data){
              if(data==1){
                success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                $('button').attr('disabled','disabled');
                if({$send_to_store_users})
                    {
                    alert();
                    var sub="";
                    var text="";
                    var type_committe = '{$old_class_input_class_type}';
                        if (type_committe == '123')
                        {
              
                         sub= 'اعتماد مدير اللوزام و الخدمات لمحضر فحص واستلام';
                         text= 'تم اعتماد المحضر طرف مدير اللوزام و الخدمات يمكنكم تحويله لسند ادخال مخزني ';

                 
                        }
                       else 
                        {
                        sub= 'تم اعتماد المدير العام لمحضر فحص واستلام';
                        text= 'تم اعتماد المحضر طرف المدير العام يمكنكم تحويله لسند ادخال مخزني ';
                       

                      }
                         text+= '<br>للاطلاع افتح الرابط';
                         text+= ' <br>{$gfc_domain}{$get_url}/{$receipt_data['RECEIPT_CLASS_INPUT_ID']}/edit/1/1';
                         _send_mail(btn_mng,'malwadiya@gedco.ps',sub,text);
                           btn_mng = ''; 
  
                    }
                 setTimeout(function(){
                        get_to_link(window.location.href);
                },2000);
              } else {
                danger_msg('تحذير..',data);
              }
       },'html');
   }
}

  var btn__1= '';
 $('#btn_cancel_adopt_manager').click( function(){
      btn__1 = $(this);
 
 });
 
 
function {$TB_NAME}_cancel_adopt_manager(){
  if(confirm('هل تريد الغاء الاعتماد والارجاع للحنة؟')){    
     get_data('{$cancel_manager_adopt_url}',{receipt_class_input_id:{$receipt_data['RECEIPT_CLASS_INPUT_ID']}},function(data){
              if(data==1){
                success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                $('button').attr('disabled','disabled');
                
            var sub= 'تم الغاء محضر الفحص و الاستلام';
            var text= 'الغاء محضر الفحص و الاستلام';
           
            text+= '<br>للاطلاع افتح الرابط';
            text+= ' <br>{$gfc_domain}{$get_url}/{$receipt_data['RECEIPT_CLASS_INPUT_ID']}/edit/1/1';
            _send_mail(btn__1,'{$EMAIL}',sub,text);
            if({$send_to_store_users})
                    {
                    _send_mail(btn__1,'malwadiya@gedco.ps',sub,text);
                    }
           btn__1 = ''; 
           get_to_link(window.location.href);
              } else {
                danger_msg('تحذير..',data);
              }
     },'html');
  }
}

</script>
SCRIPT;
    sec_scripts($edit_script);

}
?>