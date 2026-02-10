<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 13/10/15
 * Time: 01:43 م
 */

$MODULE_NAME = 'donations';
$TB_NAME = 'donation';
$DET_TB_NAME = 'public_get_details';
$DET_TB_NAME1 = 'public_get_details_dept';
$isCreate = isset($donation_data) && count($donation_data) > 0 ? false : true;
$isCreate2 = isset($donation_dept) && count($donation_dept) > 0 ? false : true;
$HaveRs = (!$isCreate) ? true : false;
$rs = $isCreate ? array() : $donation_data[0];
$rs2 = $isCreate2 ? array() : $donation_dept[0];

$change_items_url=base_url("$MODULE_NAME/$TB_NAME/change_items");
if (count($rs) > 0 && ($rs['DONATION_FILE_CASE'] == 2 ))
{
if (HaveAccess($change_items_url) && ($isCreate || ($rs['DONATION_FILE_CASE'] == 2)))
{
    $post_url=base_url("$MODULE_NAME/$TB_NAME/change_items");

}
    else
        $post_url = base_url("$MODULE_NAME/$TB_NAME/" . ($action == 'index' ? 'create' : $action));
}
/*else if ((count($rs) <= 0 ) || ($rs['DONATION_FILE_CASE'] == 1) )
{
    $post_url = base_url("$MODULE_NAME/$TB_NAME/" . ($action == 'index' ? 'create' : $action));
}*/
else
    $post_url = base_url("$MODULE_NAME/$TB_NAME/" . ($action == 'index' ? 'create' : $action));

$back_url = base_url("$MODULE_NAME/$TB_NAME"); //$action
$class_output_url = base_url("stores/stores_class_output/get");
$select_accounts_url = base_url('financial/accounts/public_select_accounts');

$select_items_url = base_url("stores/classes/public_index");
$get_class_url = base_url('stores/classes/public_get_id');
$get_url = base_url("$MODULE_NAME/$TB_NAME/get");
$adopt_url = base_url("$MODULE_NAME/$TB_NAME/adopt");
$adopt_url_close = base_url("$MODULE_NAME/$TB_NAME/adopt_close");
$adopt_url_un_close = base_url("$MODULE_NAME/$TB_NAME/adopt_close");
$department_url= base_url("$MODULE_NAME/$TB_NAME/save_dept");
$addtinal_lots_url= base_url("$MODULE_NAME/$TB_NAME/save_dept_addtinal");
$delete_url_details = base_url("$MODULE_NAME/$TB_NAME/delete_details");
$delete_url_dept = base_url("$MODULE_NAME/$TB_NAME/delete_details_dept");
$get_store_donation = base_url("$MODULE_NAME/$TB_NAME/get_store_donation");
$un_do_store_donation = base_url("$MODULE_NAME/$TB_NAME/un_adopt");
$fid = (count($rs) > 0) ? $rs['DONATION_FILE_ID'] : 0;
$count_donation_file_det=(count($rs) > 0) ? $rs['COUNT_DONATION_FILE_DET'] : 0;
$dopt=($HaveRs) ? $rs['DONATION_FILE_CASE'] : 0;
$order_id_val=($HaveRs) ? $rs['DONATION_FILE_ID'] : 0;
$f_id_val=($HaveRs) ? $rs['DONATION_TYPE'] : 0;
$show_details_url =base_url("$MODULE_NAME/$TB_NAME/$DET_TB_NAME1");
$show_det_class_url=base_url("$MODULE_NAME/$TB_NAME/$DET_TB_NAME");
$get_account_name_url =base_url('financial/accounts/public_get_id');
    //die('show');
echo AntiForgeryToken();
?>

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
<form class="form-vertical" id="<?= $TB_NAME ?>_form" method="post" action="<?= $post_url ?>" role="form"
      novalidate="novalidate">
<div class="modal-body inline_form">

<div class="form-group col-sm-1">
    <label class="control-label">رقم المسلسل</label>

    <div>
        <input type="text" name="donation_file_id"
               value="<?php if (count($rs) > 0) echo $rs['DONATION_FILE_ID']; ?>" readonly data-type="text"
               id="txt_donation_file_id" class="form-control ">
    </div>
</div>

<div class="form-group col-sm-2">
    <label class="control-label">رقم المنحة - من الجهة المانحة</label>

    <div>
        <input type="text" name="donation_id" value="<?php if (count($rs) > 0) echo $rs['DONATION_ID']; ?>"
               data-type="text" id="txt_donation_id" class="form-control " data-val="true"
               data-val-required="حقل مطلوب">
    </div>
</div>

<div class="form-group col-sm-2">
    <label class="control-label">الجهة المانحة</label>

    <div>
        <input type="text" name="donation_name" value="<?php if (count($rs) > 0) echo $rs['DONATION_NAME']; ?>"
               data-type="text" id="txt_donation_name" class="form-control " data-val="true"
               data-val-required="حقل مطلوب">
    </div>
</div>
<div class="form-group col-sm-1">
    <label class="control-label">تاريخ اعتماد المنحة</label>

    <div>
        <input type="text" name="donation_approved_date" data-type="date" data-date-format="DD/MM/YYYY"
               data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?= date_format_exp() ?>"
               data-val="true" data-val-required="حقل مطلوب" id="txt_donation_approved_date"
               class="form-control " value="<?php if (count($rs) > 0) echo $rs['DONATION_APPROVED_DATE']; ?>">
                <span class="field-validation-valid" data-valmsg-for="donation_approved_date"
                      data-valmsg-replace="true"></span>
    </div>
</div>

<div class="form-group col-sm-1">
    <label class="control-label">تاريخ نهاية المنحة</label>

    <div>
        <input type="text" name="donation_end_date" data-type="date" data-date-format="DD/MM/YYYY"
               data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?= date_format_exp() ?>"
               id="txt_donation_end_date" class="form-control "
               value="<?php if (count($rs) > 0) echo $rs['DONATION_END_DATE']; ?>">
                <span class="field-validation-valid" data-valmsg-for="donation_end_date"
                      data-valmsg-replace="true"></span>
    </div>
</div>

<div class="form-group col-sm-2">
    <label class="control-label">اسم المنحة</label>

    <div>
        <input type="text" name="donation_label" class="form-control"
               value="<?php if (count($rs) > 0) echo $rs['DONATION_LABEL']; ?>" data-type="text"
               id="txt_donation_label" class="form-control " data-val="true" data-val-required="حقل مطلوب">
    </div>
</div>
<div class="form-group col-sm-1">

    <label class="control-label">حساب المنحة</label>
    <div id="div_show_donation_account">
        <?php if($dopt=='4')
        {

        ?>
        <input type="text" name="donation_account" class="form-control"
               value="<?php if (count($rs) > 0) echo $rs['DONATION_ACCOUNT']; ?>" id="h_txt_donation_account"
               data-val="true" data-val-required="حقل مطلوب"/>
                <span class="field-validation-valid" data-valmsg-for="donation_account" data-valmsg-replace="true"
                      data-val="true" data-val-required="حقل مطلوب"></span>
        <?php
        }
else
{
        ?>
    <input type="text" name="donation_account" class="form-control" readonly
           value="<?php if (count($rs) > 0) echo $rs['DONATION_ACCOUNT']; ?>" id="h_txt_donation_account"/>
    <span class="field-validation-valid" data-valmsg-for="donation_account" data-valmsg-replace="true"></span>

           <?php
        }
        ?>
        </div>

</div>

<div class="form-group col-sm-2">
    <label class="control-label">حساب المنحة</label>

    <div id="div_donation_account">
        <input type="text" data-val="false"
               value="<?php if (count($rs) > 0) echo $rs['DONATION_ACCOUNT_NAME']; ?>" readonly
               data-val-required="required" class="form-control" id="txt_donation_account" data-val="true"
              />

    </div>
</div>


<div class="form-group col-sm-2">
    <label class="control-label">الجهة الممولة</label>

    <div>
        <input type="text" name="donor_name" value="<?php if (count($rs) > 0) echo $rs['DONOR_NAME']; ?>"
               data-type="text" id="txt_donor_name" class="form-control "
               >
    </div>
</div>

<div class="form-group col-sm-1">
    <label class="control-label">نوع المنحة</label>

    <div>
        <select name="donation_type" id="dp_donation_type" data-curr="false" class="form-control"
                data-val="true" data-val-required="حقل مطلوب">
            <option value="">-</option>
            <?php foreach ($donation_types as $row) : ?>
                <option value="<?= $row['CON_NO'] ?>" <?PHP if (count($rs) > 0) {
                    if ($row['CON_NO'] == $rs['DONATION_TYPE']) {
                        echo " selected";
                    }
                } ?> ><?php echo $row['CON_NAME'] ?></option>
            <?php endforeach; ?>
        </select>
                        <span class="field-validation-valid" data-valmsg-for="donation_type" data-valmsg-replace="true">
    </div>
</div>

<div class="form-group col-sm-1">
    <label class="control-label">طبيعة المنحة</label>

    <div>
        <select name="donation_kind" id="dp_donation_kind" data-curr="false" class="form-control"
                data-val="true" data-val-required="حقل مطلوب">
            <option value="">-</option>
            <?php foreach ($donation_kinds as $row) : ?>
                <option value="<?= $row['CON_NO'] ?>" <?PHP if (count($rs) > 0) {
                    if ($row['CON_NO'] == $rs['DONATION_KIND']) {
                        echo " selected";
                    }
                } ?> ><?php echo $row['CON_NAME'] ?></option>
            <?php endforeach; ?>
        </select>
                        <span class="field-validation-valid" data-valmsg-for="donation_kind" data-valmsg-replace="true">
    </div>
</div>



<div class="form-group col-sm-1">
    <label class="control-label"> تاريخ العقد</label>

    <div class="">
        <input type="text" name="curr_date" data-type="date" data-date-format="DD/MM/YYYY" value="<?= date('d/m/Y') ?>"  id="txt_curr_date" class="form-control "data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?= date_format_exp() ?>"
               data-val="true" data-val-required="حقل مطلوب"
                value="<?php if (count($rs) > 0) echo $rs['CURR_DATE']; ?>">
                <span class="field-validation-valid" data-valmsg-for="curr_date"
                      data-valmsg-replace="true"></span>
    </div>
</div>




<div class="form-group col-sm-1">
    <label class="control-label"> عملة المنحة </label>

    <div class="">
        <select name="curr_id" id="dp_curr_id" data-curr="false" class="form-control" data-val="true"
                data-val-required="حقل مطلوب">
            <option value="">-</option>
            <?php foreach ($currency as $row) : ?>
                <option data-val="<?= $row['VAL'] ?>" value="<?= $row['CURR_ID'] ?>" <?PHP if (count($rs) > 0) {
                    if ($row['CURR_ID'] == $rs['CURR_ID']) {
                        echo " selected";
                    }
                } ?> ><?php echo $row['CURR_NAME'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>

<div class="form-group col-sm-1">
    <label class="control-label"> سعر العملة</label>

    <div class="">
        <input type="text" name="curr_value" readonly data-val-regex="المدخل غير صحيح!"
               data-val-regex-pattern="<?= float_format_exp() ?>" data-val="true"
               value="<?PHP if (count($rs) > 0) echo $rs['CURR_VALUE']; ?>" data-val-required="حقل مطلوب"
               id="txt_curr_value" class="form-control ">
        <span class="field-validation-valid" data-valmsg-for="curr_value" data-valmsg-replace="true"></span>
    </div>
</div>

<div class="form-group col-sm-1">
    <label class="control-label">قيمة المنحة</label>

    <div>
        <input type="text" name="donation_value"
               value="<?php if (count($rs) > 0) echo $rs['DONATION_VALUE']; ?>" data-type="text"
               id="txt_donation_value" class="form-control " data-val="true" data-val-required="حقل مطلوب">
    </div>
</div>

<div class="form-group col-sm-1">

    <label class="control-label">شروط المنحة</label>

    <div>
        <select name="conditions" id="dp_conditions" data-curr="false" class="form-control" data-val="true"
                data-val-required="حقل مطلوب">
            <option value="">-</option>
            <?php foreach ($conditions as $row) : ?>
                <option value="<?= $row['CON_NO'] ?>" <?PHP if (count($rs) > 0) {
                    if ($row['CON_NO'] == $rs['CONDITIONS']) {
                        echo " selected";
                    }
                } ?> ><?php echo $row['CON_NAME'] ?></option>
            <?php endforeach; ?>
        </select>
                        <span class="field-validation-valid" data-valmsg-for="conditions" data-valmsg-replace="true">
    </div>
</div>

<div class="form-group col-sm-2">

    <label class="control-label">مخزن المنحة</label>

    <div>

        <select name="store_id" id="dp_store_id" data-curr="false" class="form-control">
            <option value="">-</option>
            <?php foreach ($stores_all as $row) : ?>
                <option value="<?= $row['STORE_ID'] ?>" <?PHP if (count($rs) > 0) {
                    if ($row['STORE_ID'] == $rs['STORE_ID']) {
                        echo " selected";
                    }
                } ?> ><?= $row['STORE_NO'] . ': ' . $row['STORE_NAME'] ?></option>
            <?php endforeach; ?>
        </select>
                        <span class="field-validation-valid" data-valmsg-for="store_id" data-valmsg-replace="true">


    </div>
</div>

<div class="form-group col-sm-10">
    <label class="control-label"> البيان </label>

    <div>
        <input type="text"  name="notes" id="txt_notes" class="form-control"
               dir="rtl" value="<?php echo (count($rs) > 0) ? $rs['NOTES'] : ''; ?>" data-val="true"
               >


    </div>
</div>
<div style="clear: both"></div>
<div class="form-group col-sm-2">
    <label class="control-label">قيمة التسويات</label>

    <div>
        <input type="text" name="other_expenses"
               value="<?php if (count($rs) > 0) echo $rs['OTHER_EXPENSES']; else echo 0; ?>" data-type="text"
               id="txt_other_expenses" class="form-control " data-val="true" data-val-required="حقل مطلوب">
    </div>
</div>
<div class="form-group col-sm-8">
    <label class="control-label"> بيان التسويات</label>

    <div>
        <input type="text" data-val="true" data-val-required="حقل مطلوب" name="other_expenses_note"
               id="txt_other_expenses_note" class="form-control" dir="rtl"
               value="<?php echo (count($rs) > 0) ? $rs['OTHER_EXPENSES_NOTE'] : ''; ?>" data-val="true"
               data-val-required="حقل مطلوب">


    </div>
</div>
<div style="clear: both"></div>


<div style="clear: both"></div>
<input type="hidden" id="h_data_search"/>



<div id="classes" style="width:1200px;clear: both;overflow: auto" class="div_width">
    <div   >
    <?php echo modules::run("$MODULE_NAME/$TB_NAME/$DET_TB_NAME1", ($HaveRs) ? $rs['DONATION_FILE_ID'] : 0, ($HaveRs) ? $rs['DONATION_FILE_CASE'] : 0,($HaveRs) ? $rs['DONATION_TYPE'] : 0); ?>
   </div>
    <div class="modal-footer">
        <?php //echo ( $rs['ADOPT']==1 and isset($can_edit)?$can_edit:false) ;
        if (HaveAccess($department_url) &&  (!$isCreate and ((count($rs) > 0) ? $rs['DONATION_FILE_CASE'] == 1 : '')  and isset($can_edit) ? $can_edit : false)) : ?>
            <button type="button" data-action="button"  onclick="dept_save(this);" class="btn btn-primary">حفظ</button>
        <?php endif; ?>

        <?php //echo ( $rs['ADOPT']==1 and isset($can_edit)?$can_edit:false) ;
        if (HaveAccess($addtinal_lots_url) &&  (!$isCreate and ((count($rs) > 0) ? $rs['DONATION_FILE_CASE'] == 2 : '')  and isset($can_edit) ? $can_edit : false)) : ?>
            <button type="button" data-action="button"  onclick="dept_save_add(this);" class="btn btn-danger">حفظ</button>
        <?php endif; ?>
    </div>

</div>
<div id="lana" style="width:1200px;clear: both;overflow: auto" class="div_width">
    <div style="width:1200px;clear: both;overflow: auto" class="div_width"  >
    <?php if (count($rs) > 0) {
        if (count($rs2) > 0){

            if ($rs2['DONATION_DEPT_CODE'] != '') {

                echo  modules::run("$MODULE_NAME/$TB_NAME/$DET_TB_NAME", ($HaveRs) ? $rs['DONATION_FILE_ID'] : 0, ($HaveRs) ? $rs['DONATION_FILE_CASE'] : 0,($HaveRs) ? $rs['DONATION_TYPE'] : 0);
            }

        }
    } ?>
    </div>
</div>

<div class="modal-footer">
    <?php //echo ( $rs['ADOPT']==1 and isset($can_edit)?$can_edit:false) ;
    if (HaveAccess($post_url) && ($isCreate || ($rs['DONATION_FILE_CASE'] == 1))) : ?>
        <button type="submit" data-action="submit" class="btn btn-primary" id="save1" name="save1">حفظ</button>
    <?php endif; ?>

    <?php //echo ( $rs['ADOPT']==1 and isset($can_edit)?$can_edit:false) ;
     if (count($rs) > 0)

    if (HaveAccess($change_items_url) && ($isCreate || ($rs['DONATION_FILE_CASE'] == 2))) :  ?>
        <button type="submit" data-action="submit" class="btn btn-primary" id="save" name="save">حفظ</button>

    <?php  endif; ?>

    <?php if (!$isCreate and $rs['DONATION_FILE_CASE'] != 0) : ?>
        <button type="button" id="print_rep" onclick="javascript:print_rep();" class="btn btn-success"><i
                class="glyphicon glyphicon-print"></i> طباعة
        </button>
    <?php endif; ?>

    <button class="btn btn-warning dropdown-toggle" onclick="$('#details_tb').tableExport({type:'excel',escape:'false'});" data-toggle="dropdown"><i class="fa fa-bars"></i> تصدير الاصناف</button>
    <button class="btn btn-warning dropdown-toggle" onclick="$('#details_tb1').tableExport({type:'excel',escape:'false'});" data-toggle="dropdown"><i class="fa fa-bars"></i>LOTS تصدير</button>


    <?php if (HaveAccess($adopt_url) && (!$isCreate and ((count($rs) > 0) ? $rs['DONATION_FILE_CASE'] == 1 : '')) && $count_donation_file_det>0) : ?>
        <button type="button" id="btn_adopt" onclick='javascript:return_adopt(1);' class="btn btn-success">اعتماد و تحويل الى المالية
        </button>
    <?php endif; ?>

    <?php if (HaveAccess($get_store_donation) && (!$isCreate and ((count($rs) > 0) ? $rs['DONATION_FILE_CASE'] == 4 : '')) && $count_donation_file_det>0) : ?>
        <button type="button" id="btn_adopt" onclick='javascript:return_adopt(4);' class="btn btn-success">حفظ بيانات المنحة
        </button>
    <?php endif; ?>

    <?php if (HaveAccess($un_do_store_donation) && (!$isCreate and ((count($rs) > 0) ? $rs['DONATION_FILE_CASE'] == 2 : '')) && $count_donation_file_det>0) : ?>
        <button type="button" id="btn_adopt" onclick='javascript:return_adopt(5);' class="btn btn-danger">تحديث بيانات المنحة
        </button>
    <?php endif; ?>


    <?php if (HaveAccess($adopt_url_close) && (!$isCreate and ((count($rs) > 0) ? $rs['DONATION_FILE_CASE'] == 2 : '')) && $count_donation_file_det>0) : ?>
        <button type="button" id="btn_adopt" onclick='javascript:return_adopt(2);' class="btn btn-success">اغلاق منحة
        </button>
    <?php endif; ?>

    <?php if (HaveAccess($adopt_url_un_close) && (!$isCreate and ((count($rs) > 0) ? $rs['DONATION_FILE_CASE'] == 3 : '')) && $count_donation_file_det>0) : ?>
        <button type="button" id="btn_adopt" onclick='javascript:return_adopt(3);' class="btn btn-danger">فك اغلاق المنحة
        </button>
    <?php endif; ?>


</div>
<div style="clear: both;">
    <input type="hidden" id="h_data_search"/>
</div>
<div style="clear: both;">
    <?php echo modules::run('settings/notes/public_get_page', (count($rs) > 0) ? $rs['DONATION_FILE_ID'] : 0, 'donation_file'); ?>
    <?php echo (count($rs) > 0) ? modules::run('attachments/attachment/index', $rs['DONATION_FILE_ID'], 'donation_file') : ''; ?>
</div>
</form>
</div>
</div>
<?php echo modules::run('settings/notes/index'); ?>

</form>
</div>
</div>
<?php


$notes_url = notes_url();
$currency_date_url =base_url('settings/currency/public_get_currency_date');
$scripts = <<<SCRIPT

<script type="text/javascript">
$(document).ready(function() {

    var c_w= $(window).width() - 80 ;
    $('div.div_width').css('width',c_w+'px');


});

var count = 0;
var dopt_is={$dopt};
if ($('#dp_donation_type').val() == '1') {
    if(dopt_is=='4')
    {
        $('#dp_store_id').prop('readonly', false);//jQuery <1.9
        $('#dp_store_id').attr('readonly', false);//jQuery 1.9+
    }
    else
    {
        $('#dp_store_id').prop('readonly', true);//jQuery <1.9
        $('#dp_store_id').attr('readonly', true);//jQuery 1.9+
    }
    $('#txt_other_expenses').prop('readonly', true);//jQuery <1.9
    $('#txt_other_expenses').attr('readonly', true);//jQuery 1.9+
    $('#txt_other_expenses').val('0');


}
else  if ($('#dp_donation_type').val() == '3') {
    $('#dp_store_id').prop('readonly', true);//jQuery <1.9
    $('#dp_store_id').attr('readonly', true);//jQuery 1.9+
    $('#txt_other_expenses').prop('readonly', true);//jQuery <1.9
    $('#txt_other_expenses').attr('readonly', true);//jQuery 1.9+
    $('#txt_other_expenses').val('0');


}
else {
    $('#dp_store_id').prop('readonly', true);//jQuery <1.9
    $('#dp_store_id').attr('readonly', true);//jQuery 1.9+
    $('#dp_store_id').val('');
    $('#txt_other_expenses').prop('readonly', false);//jQuery <1.9
    $('#txt_other_expenses').attr('readonly', false);//jQuery 1.9+
    $('#txt_other_expenses').val('0');

}
calcall();

function calcall() {
    var total_value_donation = 0;
    var total_value_install_donation=0;
    var total_expenses = 0;
    var total_total_total = 0;
    var total_vat_value = 0;

    var total_vat_total = 0;
    var total_discount_value = 0;
    var total_discount_value_install=0;
    var total_discount_total = 0;
    var total_discount_total_install=0;
    var total_expenses_transport = 0;
    var total_expenses_transport_total = 0;
    var total_expenses_adjustments = 0;
    var total_expenses_adjustments_install = 0;
    var total_all_total = 0;
    var total_all_total_install = 0;
    $('input[name="donation_dept_value[]"]').each(function () {
        var donation_dept_value = $(this).closest('tr').find('input[name="donation_dept_value[]"]').val();
        total_value_donation += Number(donation_dept_value);
        $('#total_donation_dept_value').text(isNaNVal(Number(total_value_donation)));
    });
//install input[data-rest]  data-group="Companies"
    $('input[data-name="1"]').each(function () {
        var total_donation_dept_value_1 = $(this).closest('tr').find('input[data-name="1"]').val();
        total_value_install_donation += Number(total_donation_dept_value_1);
        $('#total_donation_dept_value_1').text(isNaNVal(Number(total_value_install_donation)));
    });

    $('input[name="expenses[]"]').each(function () {
        var expenses = $(this).closest('tr').find('input[name="expenses[]"]').val();
        total_expenses += Number(expenses);
        $('#total_expenses').text(isNaNVal(Number(total_expenses)));
    });

    $('input[name="total[]"]').each(function () {
        var total = $(this).closest('tr').find('input[name="total[]"]').val();
        total_total_total += Number(total);
        $('#total_total').text(isNaNVal(Number(total_total_total)));
    });

    $('input[name="vat_value[]"]').each(function () {
        var vat_value = $(this).closest('tr').find('input[name="vat_value[]"]').val();
        total_vat_value += Number(vat_value);

        $('#total_vat_value').text(isNaNVal(Number(total_vat_value)));
    });

    $('input[name="vat_total[]"]').each(function () {
        var vat_total = $(this).closest('tr').find('input[name="vat_total[]"]').val();
        total_vat_total += Number(vat_total);

        $('#total_vat_total').text(isNaNVal(Number(total_vat_total)));
    });


    $('input[name="discount_value[]"]').each(function () {
        var discount_value = $(this).closest('tr').find('input[name="discount_value[]"]').val();
        total_discount_value += Number(discount_value);

        $('#total_discount_value').text(isNaNVal(Number(total_discount_value)));
    });

//install input[data-rest]  data-group="Companies"
    $('input[data-name="2"]').each(function () {
        var total_discount_value_1 = $(this).closest('tr').find('input[data-name="2"]').val();
        total_discount_value_install += Number(total_discount_value_1);
        $('#total_discount_value_1').text(isNaNVal(Number(total_discount_value_install)));
    });


    //install input[data-rest]  data-group="Companies"
    $('input[data-name="3"]').each(function () {
        var total_discount_total_1 = $(this).closest('tr').find('input[data-name="3"]').val();
        total_discount_total_install += Number(total_discount_total_1);
        $('#total_discount_total_1').text(isNaNVal(Number(total_discount_total_install)));
    });


    $('input[name="discount_total[]"]').each(function () {
        var discount_total = $(this).closest('tr').find('input[name="discount_total[]"]').val();
        total_discount_total += Number(discount_total);

        $('#total_discount_total').text(isNaNVal(Number(total_discount_total)));
    });


    $('input[name="expenses_transport[]"]').each(function () {
        var expenses_transport = $(this).closest('tr').find('input[name="expenses_transport[]"]').val();
        total_expenses_transport += Number(expenses_transport);

        $('#total_expenses_transport').text(isNaNVal(Number(total_expenses_transport)));
    });

    /*$('input[name="expenses_transport[]"]').each(function () {

     var expenses_transport = $(this).closest('tr').find('input[name="expenses_transport[]"]').val();
     total_expenses_transport += Number(expenses_transport);

     $('#total_expenses_transport').text(isNaNVal(Number(total_expenses_transport)));
     $('#txt_other_expenses').val(isNaNVal(Number(total_expenses_transport)));
     });*/

    $('input[name="expenses_transport_total[]"]').each(function () {
        var expenses_transport_total = $(this).closest('tr').find('input[name="expenses_transport_total[]"]').val();
        total_expenses_transport_total += Number(expenses_transport_total);

        $('#total_expenses_transport_total').text(isNaNVal(Number(total_expenses_transport_total)));

    });

    $('input[name="expenses_adjustments[]"]').each(function () {
        var expenses_adjustments = $(this).closest('tr').find('input[name="expenses_adjustments[]"]').val();
        total_expenses_adjustments += Number(expenses_adjustments);

        $('#total_expenses_adjustments').text(isNaNVal(Number(total_expenses_adjustments)));
        $('#txt_other_expenses').val(isNaNVal(Number(total_expenses_adjustments)));

    });


    //install input[data-rest]  data-group="Companies"
    $('input[data-name="4"]').each(function () {
        var total_expenses_adjustments_1 = $(this).closest('tr').find('input[data-name="4"]').val();
        total_expenses_adjustments_install += Number(total_expenses_adjustments_1);
        $('#total_expenses_adjustments_1').text(isNaNVal(Number(total_expenses_adjustments_install)));
        $('#txt_other_expenses').val(isNaNVal(Number(total_expenses_adjustments_install)));
    });


    $('input[name="all_total[]"]').each(function () {
        var all_total = $(this).closest('tr').find('input[name="all_total[]"]').val();
        total_all_total += Number(all_total);

        $('#total_all_total').text(isNaNVal(Number(total_all_total)));

    });

    //install input[data-rest]  data-group="Companies"
    $('input[data-name="5"]').each(function () {
        var total_all_total_1 = $(this).closest('tr').find('input[data-name="5"]').val();
        total_all_total_install += Number(total_all_total_1);
        $('#total_discount_total_1').text(isNaNVal(Number(total_all_total_install)));
    });


}


function calc_all_det() {

    var total_d_expenses = 0;
    var total_total_with_expenses = 0;
    var total_d_other_expenses = 0;
    var total_last_total = 0;
    var total_last_price=0;
    var id_amount_total=0;
    var price_without_discount_total=0;
    var tax_value_total=0;
    var price_with_trans_expenses_total=0;
    var price_without_vat_total=0;
    var n_total_other_expenses=0;
    var n_total_last_total=0;
    var n_total_price_without_discount=0;
    var n_total_total_with_expenses=0;
    var n_amount_total=0;
    $('input[name="d_expenses[]"]').each(function () {
        var d_expenses = $(this).closest('tr').find('input[name="d_expenses[]"]').val();
        total_d_expenses += Number(d_expenses);
        $('#total_d_expenses').text(isNaNVal(Number(total_d_expenses)));
    });

    $('input[name="amount[]"]').each(function () {
        var n_amount = $(this).closest('tr').find('input[name="amount[]"]').val();
        n_amount_total += Number(n_amount);
        $('#n_amount_total').text(isNaNVal(Number(n_amount_total)));
    });

    $('input[name="total_with_expenses[]"]').each(function () {
        var n_total_with_expenses = $(this).closest('tr').find('input[name="total_with_expenses[]"]').val();
        n_total_total_with_expenses += Number(n_total_with_expenses);
        $('#n_total_total_with_expenses').text(isNaNVal(Number(n_total_total_with_expenses)));
    });

    $('input[name="last_price[]"]').each(function () {
        var last_price = $(this).closest('tr').find('input[name="last_price[]"]').val();
        total_last_price += Number(last_price);
        $('#total_last_price').text(isNaNVal(Number(total_last_price)));
    });
    $('input[name="amount[]"]').each(function () {
        var amount = $(this).closest('tr').find('input[name="amount[]"]').val();
        id_amount_total += Number(amount);
        $('#id_amount_total').text(isNaNVal(Number(id_amount_total)));
    });
    $('input[name="total_with_expenses[]"]').each(function () {
        var total_with_expenses = $(this).closest('tr').find('input[name="total_with_expenses[]"]').val();
        total_total_with_expenses += Number(total_with_expenses);
        $('#total_total_with_expenses').text(isNaNVal(Number(total_total_with_expenses)));
    });

    $('input[name="price_without_vat[]"]').each(function () {
        var price_without_vat = $(this).closest('tr').find('input[name="price_without_vat[]"]').val();
        price_without_vat_total += Number(price_without_vat);
        $('#price_without_vat_total').text(isNaNVal(Number(price_without_vat_total)));
    });

    $('input[name="price_without_discount[]"]').each(function () {
        var price_without_discount = $(this).closest('tr').find('input[name="price_without_discount[]"]').val();
        price_without_discount_total += Number(price_without_discount);
        $('#price_without_discount_total').text(isNaNVal(Number(price_without_discount_total)));
    });

    $('input[name="tax_value[]"]').each(function () {
        var tax_value = $(this).closest('tr').find('input[name="tax_value[]"]').val();
        tax_value_total += Number(tax_value);
        $('#tax_value_total').text(isNaNVal(Number(tax_value_total)));
    });

    $('input[name="price_with_trans_expenses[]"]').each(function () {
        var price_with_trans_expenses = $(this).closest('tr').find('input[name="price_with_trans_expenses[]"]').val();
        price_with_trans_expenses_total += Number(price_with_trans_expenses);
        $('#price_with_trans_expenses_total').text(isNaNVal(Number(price_with_trans_expenses_total)));
    });

    $('input[name="d_other_expenses[]"]').each(function () {
        var d_other_expenses = $(this).closest('tr').find('input[name="d_other_expenses[]"]').val();
        total_d_other_expenses += Number(d_other_expenses);
        $('#total_d_other_expenses').text(isNaNVal(Number(total_d_other_expenses)));
    });

    $('input[name="last_total[]"]').each(function () {
        var last_total = $(this).closest('tr').find('input[name="last_total[]"]').val();
        total_last_total += Number(last_total);
        $('#total_last_total').text(isNaNVal(Number(total_last_total)));
    });

    $('input[data-name="d_other_expenses"]').each(function () {
        var total_d_other_expenses_1 = $(this).closest('tr').find('input[data-name="d_other_expenses"]').val();
        n_total_other_expenses += Number(total_d_other_expenses_1);
        $('#n_total_other_expenses').text(isNaNVal(Number(n_total_other_expenses)));
    });
    $('input[data-name="last_total"]').each(function () {
        var n_last_total= $(this).closest('tr').find('input[data-name="last_total"]').val();

        n_total_last_total += Number(n_last_total);

        $('#n_total_last_total').text(isNaNVal(Number(n_total_last_total)));
    });

    $('input[data-name="price_without_discount"]').each(function () {
        var n_price_without_discount= $(this).closest('tr').find('input[data-name="price_without_discount"]').val();

        n_total_price_without_discount += Number(n_price_without_discount);

        $('#n_total_price_without_discount').text(isNaNVal(Number(n_total_price_without_discount)));
    });
}
function recalc_classes(ser,vat_percent,discount_percentage,expenses_trans_percentage,vat_total,expenses_adjustments){

    var m=$('select[name="donation_dept_ser[]"]');
    m.each(function(i,j){
        if(j.value==ser){

            console.log($(m[i]).find(':selected'));
            $(m[i]).find(':selected').attr('data-vat_percent',vat_percent);
            $(m[i]).find(':selected').attr('data-discount_percentage',discount_percentage);
            $(m[i]).find(':selected').attr('data-expenses_trans_percentage',expenses_trans_percentage);
            $(m[i]).find(':selected').attr('data-vat_total',vat_total);
            $(m[i]).find(':selected').attr('data-expenses_adjustments',expenses_adjustments);

            calc_det($(m[i]));
        }
        calc_all_det();
    });
}
//recalc_classes(23,9,7,8);
function reBind() {

    $('input[name="class_name[]"]').click("focus", function (e) {
        _showReport('$select_items_url/' + $(this).attr('id') + $('#h_data_search').val());
    });

    $('input[name="class[]"]').bind("focusout", function (e) {
        var id = $(this).val();
        var class_id = $(this).closest('tr').find('input[name="class_id[]"]');
        var name = $(this).closest('tr').find('input[name="class_name[]"]');
       var name1 = $(this).closest('tr').find('input[name="en_class_id[]"]');
        var unit = $(this).closest('tr').find('input[name="class_unit[]"]');
        var unit_name = $(this).closest('tr').find('input[name="class_unit_id[]"]');
        var amount = $(this).closest('tr').find('input[name="donation_class_id[]"]');
        var price = $(this).closest('tr').find('input[name="price1[]"]');
        if (id == '') {
            class_id.val('');
            name.val('');
            unit_name.val('');
            unit.val('');
            price.val('');
            return 0;
        }
        get_data('{$get_class_url}', {id: id, type: 1}, function (data) {
            if (data.length == 1) {
                var item = data[0];
                class_id.val(item.CLASS_ID);
                name.val(item.CLASS_NAME_AR);
                name1.val(item.CLASS_NAME_EN);
                unit_name.val(item.UNIT_NAME);
                unit.val(item.CLASS_UNIT_SUB);
                //price.val(item.CLASS_PURCHASING);
                price.val('');
                amount.focus();
            } else {
                class_id.val('');
                name.val('');
                unit_name.val('');
                unit.val('');
                price.val('');
            }
        });
    });

    $('input[name="class[]"]').bind('keyup', '+', function (e) {
        $(this).val('');
        var class_name = $(this).closest('tr').find('input[name="class_name[]"]');
        actuateLink(class_name);
    });



    $('input[name="donation_dept_value[]"] , input[name="expenses[]"]').keyup(function () {
        if ($('#dp_donation_type').val() == '1')
        {
            var donation_dept_value = $(this).closest('tr').find('input[name="donation_dept_value[]"]').val();
            var expenses = $(this).closest('tr').find('input[name="expenses[]"]').val();
            var discount_percentage = $(this).closest('tr').find('input[name="discount_percentage[]"]').val();

            $(this).closest('tr').find('input[name="total[]"]').val(Number(donation_dept_value) + Number(expenses));
            var total = $(this).closest('tr').find('input[name="total[]"]').val();




            $(this).closest('tr').find('input[name="discount_value[]"]').val(parseFloat(Number(total)*Number(discount_percentage)).toFixed(2));
            var discount_value =$(this).closest('tr').find('input[name="discount_value[]"]').val();
            var dx = parseFloat(Number(discount_value) / (Number(total))).toFixed(2);

            $(this).closest('tr').find('input[name="discount_percentage[]"]').val(isNaNVal(dx));
            $(this).closest('tr').find('input[name="discount_total[]"]').val(parseFloat(Number(total)-Number( discount_value)).toFixed(2));
            calcall();
        }
        else if ($('#dp_donation_type').val() == '3')
        {

            var donation_dept_value = $(this).closest('tr').find('input[name="donation_dept_value[]"]').val();
            var discount_percentage = $(this).closest('tr').find('input[name="discount_percentage[]"]').val();

            $(this).closest('tr').find('input[name="discount_value[]"]').val(parseFloat(Number(donation_dept_value)*Number(discount_percentage)).toFixed(2));
            var discount_value =$(this).closest('tr').find('input[name="discount_value[]"]').val();
            var dx = parseFloat(Number(discount_value) / (Number(donation_dept_value))).toFixed(2);

            $(this).closest('tr').find('input[name="discount_percentage[]"]').val(isNaNVal(dx));
            $(this).closest('tr').find('input[name="discount_total[]"]').val(parseFloat(Number(donation_dept_value)-Number( discount_value)).toFixed(2));
            var discount_total = $(this).closest('tr').find('input[name="discount_total[]"]').val();
            var expenses_adjustments = $(this).closest('tr').find('input[name="expenses_adjustments[]"]').val();


            $(this).closest('tr').find('input[name="all_total[]"]').val(Number(discount_total) + Number(expenses_adjustments));
            calcall();





        }

    });

    $('input[name="discount_percentage[]"]').keyup(function () {

        if ($('#dp_donation_type').val() == '1')
        {
            var discount_percentage = $(this).closest('tr').find('input[name="discount_percentage[]"]').val();
            var total = $(this).closest('tr').find('input[name="total[]"]').val();



            $(this).closest('tr').find('input[name="discount_value[]"]').val(Number(discount_percentage) * Number(total));


            var discount_value = $(this).closest('tr').find('input[name="discount_value[]"]').val();

            $(this).closest('tr').find('input[name="discount_total[]"]').val(Number(total) - Number(parseFloat(discount_value)).toFixed(2));





            calcall();
            var vat_percent = $(this).closest('tr').find('input[name="vat_percent[]"]').val();
            // var discount_percentage = $(this).closest('tr').find('input[name="discount_percentage[]"]').val();
            var expenses_trans_percentage = $(this).closest('tr').find('input[name="expenses_trans_percentage[]"]').val();
            var ser_dept = $(this).closest('tr').find('input[name="ser_dept[]"]').val();

            var vat_total = $(this).closest('tr').find('input[name="vat_total[]"]').val();
            var expenses_adjustments= $(this).closest('tr').find('input[name="expenses_adjustments[]"]').val();

            recalc_classes(ser_dept,vat_percent,discount_percentage,expenses_trans_percentage,vat_total,expenses_adjustments);

        }


        else if ($('#dp_donation_type').val() == '3')
        {

            var discount_percentage = $(this).closest('tr').find('input[name="discount_percentage[]"]').val();
            var donation_dept_value = $(this).closest('tr').find('input[name="donation_dept_value[]"]').val();



            $(this).closest('tr').find('input[name="discount_value[]"]').val(Number(discount_percentage) * Number(donation_dept_value));


            var discount_value = $(this).closest('tr').find('input[name="discount_value[]"]').val();

            $(this).closest('tr').find('input[name="discount_total[]"]').val(Number(donation_dept_value) - Number(parseFloat(discount_value)).toFixed(2));
            var discount_total = $(this).closest('tr').find('input[name="discount_total[]"]').val();
            var expenses_adjustments = $(this).closest('tr').find('input[name="expenses_adjustments[]"]').val();


            $(this).closest('tr').find('input[name="all_total[]"]').val(Number(discount_total) + Number(expenses_adjustments));




            calcall();
            var vat_percent = $(this).closest('tr').find('input[name="vat_percent[]"]').val();
            // var discount_percentage = $(this).closest('tr').find('input[name="discount_percentage[]"]').val();
            var expenses_trans_percentage = $(this).closest('tr').find('input[name="expenses_trans_percentage[]"]').val();
            var ser_dept = $(this).closest('tr').find('input[name="ser_dept[]"]').val();

            var vat_total = $(this).closest('tr').find('input[name="vat_total[]"]').val();
            var expenses_adjustments= $(this).closest('tr').find('input[name="expenses_adjustments[]"]').val();

            recalc_classes(ser_dept,vat_percent,discount_percentage,expenses_trans_percentage,vat_total,expenses_adjustments);




        }


    });




    $('input[name="discount_percentage[]"]').change(function () {

        if ($('#dp_donation_type').val() == '1')
        {
            var discount_percentage = $(this).closest('tr').find('input[name="discount_percentage[]"]').val();
            var total = $(this).closest('tr').find('input[name="total[]"]').val();



            $(this).closest('tr').find('input[name="discount_value[]"]').val(Number(discount_percentage) * Number(total));


            var discount_value = $(this).closest('tr').find('input[name="discount_value[]"]').val();

            $(this).closest('tr').find('input[name="discount_total[]"]').val(Number(total) - Number(parseFloat(discount_value)).toFixed(2));





            calcall();
            var vat_percent = $(this).closest('tr').find('input[name="vat_percent[]"]').val();
            // var discount_percentage = $(this).closest('tr').find('input[name="discount_percentage[]"]').val();
            var expenses_trans_percentage = $(this).closest('tr').find('input[name="expenses_trans_percentage[]"]').val();
            var ser_dept = $(this).closest('tr').find('input[name="ser_dept[]"]').val();

            var vat_total = $(this).closest('tr').find('input[name="vat_total[]"]').val();
            var expenses_adjustments= $(this).closest('tr').find('input[name="expenses_adjustments[]"]').val();

            recalc_classes(ser_dept,vat_percent,discount_percentage,expenses_trans_percentage,vat_total,expenses_adjustments);

        }


        else if ($('#dp_donation_type').val() == '3')
        {

            var discount_percentage = $(this).closest('tr').find('input[name="discount_percentage[]"]').val();
            var donation_dept_value = $(this).closest('tr').find('input[name="donation_dept_value[]"]').val();



            $(this).closest('tr').find('input[name="discount_value[]"]').val(Number(discount_percentage) * Number(donation_dept_value));


            var discount_value = $(this).closest('tr').find('input[name="discount_value[]"]').val();

            $(this).closest('tr').find('input[name="discount_total[]"]').val(Number(donation_dept_value) - Number(parseFloat(discount_value)).toFixed(2));
            var discount_total = $(this).closest('tr').find('input[name="discount_total[]"]').val();
            var expenses_adjustments = $(this).closest('tr').find('input[name="expenses_adjustments[]"]').val();


            $(this).closest('tr').find('input[name="all_total[]"]').val(Number(discount_total) + Number(expenses_adjustments));





            calcall();
            var vat_percent = $(this).closest('tr').find('input[name="vat_percent[]"]').val();
            // var discount_percentage = $(this).closest('tr').find('input[name="discount_percentage[]"]').val();
            var expenses_trans_percentage = $(this).closest('tr').find('input[name="expenses_trans_percentage[]"]').val();
            var ser_dept = $(this).closest('tr').find('input[name="ser_dept[]"]').val();

            var vat_total = $(this).closest('tr').find('input[name="vat_total[]"]').val();
            var expenses_adjustments= $(this).closest('tr').find('input[name="expenses_adjustments[]"]').val();

            recalc_classes(ser_dept,vat_percent,discount_percentage,expenses_trans_percentage,vat_total,expenses_adjustments);




        }


    });


    $('input[name="discount_value[]"]').keyup(function () {

        if ($('#dp_donation_type').val() == '1')
        {
            var discount_value = $(this).closest('tr').find('input[name="discount_value[]"]').val();


            var total = $(this).closest('tr').find('input[name="total[]"]').val();


            var dx = parseFloat(Number(discount_value) / (Number(total))).toFixed(2);

            $(this).closest('tr').find('input[name="discount_percentage[]"]').val(isNaNVal(dx));


            var discount_value = $(this).closest('tr').find('input[name="discount_value[]"]').val();
            $(this).closest('tr').find('input[name="discount_total[]"]').val(Number(total) - Number(discount_value));

            calcall();
            var vat_percent = $(this).closest('tr').find('input[name="vat_percent[]"]').val();
            var discount_percentage = $(this).closest('tr').find('input[name="discount_percentage[]"]').val();
            var expenses_trans_percentage = $(this).closest('tr').find('input[name="expenses_trans_percentage[]"]').val();
            var ser_dept = $(this).closest('tr').find('input[name="ser_dept[]"]').val();

            var vat_total = $(this).closest('tr').find('input[name="vat_total[]"]').val();
            var expenses_adjustments= $(this).closest('tr').find('input[name="expenses_adjustments[]"]').val();

            recalc_classes(ser_dept,vat_percent,discount_percentage,expenses_trans_percentage,vat_total,expenses_adjustments);

        }
        else if ($('#dp_donation_type').val() == '3')
        {

            var discount_value = $(this).closest('tr').find('input[name="discount_value[]"]').val();


            var donation_dept_value = $(this).closest('tr').find('input[name="donation_dept_value[]"]').val();


            var dx = parseFloat(Number(discount_value) / (Number(donation_dept_value))).toFixed(2);

            $(this).closest('tr').find('input[name="discount_percentage[]"]').val(isNaNVal(dx));


            var discount_value = $(this).closest('tr').find('input[name="discount_value[]"]').val();
            $(this).closest('tr').find('input[name="discount_total[]"]').val(Number(donation_dept_value) - Number(discount_value));
            var discount_total = $(this).closest('tr').find('input[name="discount_total[]"]').val();
            var expenses_adjustments = $(this).closest('tr').find('input[name="expenses_adjustments[]"]').val();


            $(this).closest('tr').find('input[name="all_total[]"]').val(Number(discount_total) + Number(expenses_adjustments));

            calcall();
            var vat_percent = $(this).closest('tr').find('input[name="vat_percent[]"]').val();
            var discount_percentage = $(this).closest('tr').find('input[name="discount_percentage[]"]').val();
            var expenses_trans_percentage = $(this).closest('tr').find('input[name="expenses_trans_percentage[]"]').val();
            var ser_dept = $(this).closest('tr').find('input[name="ser_dept[]"]').val();

            var vat_total = $(this).closest('tr').find('input[name="vat_total[]"]').val();
            var expenses_adjustments= $(this).closest('tr').find('input[name="expenses_adjustments[]"]').val();

            recalc_classes(ser_dept,vat_percent,discount_percentage,expenses_trans_percentage,vat_total,expenses_adjustments);
        }
    });

    $('input[name="discount_value[]"]').change(function (){

        if ($('#dp_donation_type').val() == '1')
        {
            var discount_value = $(this).closest('tr').find('input[name="discount_value[]"]').val();


            var total = $(this).closest('tr').find('input[name="total[]"]').val();


            var dx = parseFloat(Number(discount_value) / (Number(total))).toFixed(2);

            $(this).closest('tr').find('input[name="discount_percentage[]"]').val(isNaNVal(dx));


            var discount_value = $(this).closest('tr').find('input[name="discount_value[]"]').val();
            $(this).closest('tr').find('input[name="discount_total[]"]').val(Number(total) - Number(discount_value));

            calcall();
            var vat_percent = $(this).closest('tr').find('input[name="vat_percent[]"]').val();
            var discount_percentage = $(this).closest('tr').find('input[name="discount_percentage[]"]').val();
            var expenses_trans_percentage = $(this).closest('tr').find('input[name="expenses_trans_percentage[]"]').val();
            var ser_dept = $(this).closest('tr').find('input[name="ser_dept[]"]').val();

            var vat_total = $(this).closest('tr').find('input[name="vat_total[]"]').val();
            var expenses_adjustments= $(this).closest('tr').find('input[name="expenses_adjustments[]"]').val();

            recalc_classes(ser_dept,vat_percent,discount_percentage,expenses_trans_percentage,vat_total,expenses_adjustments);

        }
        else if ($('#dp_donation_type').val() == '3')
        {
            var discount_value = $(this).closest('tr').find('input[name="discount_value[]"]').val();


            var donation_dept_value = $(this).closest('tr').find('input[name="donation_dept_value[]"]').val();


            var dx = parseFloat(Number(discount_value) / (Number(donation_dept_value))).toFixed(2);

            $(this).closest('tr').find('input[name="discount_percentage[]"]').val(isNaNVal(dx));


            var discount_value = $(this).closest('tr').find('input[name="discount_value[]"]').val();
            $(this).closest('tr').find('input[name="discount_total[]"]').val(Number(donation_dept_value) - Number(discount_value));
            var discount_total = $(this).closest('tr').find('input[name="discount_total[]"]').val();
            var expenses_adjustments = $(this).closest('tr').find('input[name="expenses_adjustments[]"]').val();


            $(this).closest('tr').find('input[name="all_total[]"]').val(Number(discount_total) + Number(expenses_adjustments));

            calcall();
            var vat_percent = $(this).closest('tr').find('input[name="vat_percent[]"]').val();
            var discount_percentage = $(this).closest('tr').find('input[name="discount_percentage[]"]').val();
            var expenses_trans_percentage = $(this).closest('tr').find('input[name="expenses_trans_percentage[]"]').val();
            var ser_dept = $(this).closest('tr').find('input[name="ser_dept[]"]').val();

            var vat_total = $(this).closest('tr').find('input[name="vat_total[]"]').val();
            var expenses_adjustments= $(this).closest('tr').find('input[name="expenses_adjustments[]"]').val();

            recalc_classes(ser_dept,vat_percent,discount_percentage,expenses_trans_percentage,vat_total,expenses_adjustments);
        }


    });


    $('input[name="expenses_transport[]"]').keyup(function () {


        var expenses_transport = $(this).closest('tr').find('input[name="expenses_transport[]"]').val();


        var discount_total = $(this).closest('tr').find('input[name="discount_total[]"]').val();



        var dx = parseFloat(Number(expenses_transport) / (Number(discount_total))).toFixed(2);

        $(this).closest('tr').find('input[name="expenses_trans_percentage[]"]').val(isNaNVal(dx));
        var expenses_transport = $(this).closest('tr').find('input[name="expenses_transport[]"]').val();


        $(this).closest('tr').find('input[name="expenses_transport_total[]"]').val(Number(discount_total) + Number(expenses_transport));





        calcall();

        var vat_percent = $(this).closest('tr').find('input[name="vat_percent[]"]').val();
        var discount_percentage = $(this).closest('tr').find('input[name="discount_percentage[]"]').val();
        var expenses_trans_percentage = $(this).closest('tr').find('input[name="expenses_trans_percentage[]"]').val();
        var ser_dept = $(this).closest('tr').find('input[name="ser_dept[]"]').val();

        var vat_total = $(this).closest('tr').find('input[name="vat_total[]"]').val();
        var expenses_adjustments= $(this).closest('tr').find('input[name="expenses_adjustments[]"]').val();

        recalc_classes(ser_dept,vat_percent,discount_percentage,expenses_trans_percentage,vat_total,expenses_adjustments);

    });

    $('input[name="expenses_transport[]"]').change(function () {


        var expenses_transport = $(this).closest('tr').find('input[name="expenses_transport[]"]').val();


        var discount_total = $(this).closest('tr').find('input[name="discount_total[]"]').val();



        var dx = parseFloat(Number(expenses_transport) / (Number(discount_total))).toFixed(2);

        $(this).closest('tr').find('input[name="expenses_trans_percentage[]"]').val(isNaNVal(dx));
        var expenses_transport = $(this).closest('tr').find('input[name="expenses_transport[]"]').val();


        $(this).closest('tr').find('input[name="expenses_transport_total[]"]').val(Number(discount_total) + Number(expenses_transport));





        calcall();

        var vat_percent = $(this).closest('tr').find('input[name="vat_percent[]"]').val();
        var discount_percentage = $(this).closest('tr').find('input[name="discount_percentage[]"]').val();
        var expenses_trans_percentage = $(this).closest('tr').find('input[name="expenses_trans_percentage[]"]').val();
        var ser_dept = $(this).closest('tr').find('input[name="ser_dept[]"]').val();

        var vat_total = $(this).closest('tr').find('input[name="vat_total[]"]').val();
        var expenses_adjustments= $(this).closest('tr').find('input[name="expenses_adjustments[]"]').val();

        recalc_classes(ser_dept,vat_percent,discount_percentage,expenses_trans_percentage,vat_total,expenses_adjustments);

    });

    $('input[name="expenses_trans_percentage[]"]').keyup(function () {


        var discount_total = $(this).closest('tr').find('input[name="discount_total[]"]').val();
        var expenses_trans_percentage = $(this).closest('tr').find('input[name="expenses_trans_percentage[]"]').val();


        $(this).closest('tr').find('input[name="expenses_transport[]"]').val(parseFloat(Number(expenses_trans_percentage) * Number(discount_total)).toFixed(2));


        var expenses_transport = $(this).closest('tr').find('input[name="expenses_transport[]"]').val();
        $(this).closest('tr').find('input[name="expenses_transport_total[]"]').val(parseFloat(Number(discount_total) + Number(expenses_transport)).toFixed(2));

        var expenses_transport_total = $(this).closest('tr').find('input[name="expenses_transport_total[]"]').val();

        calcall();
        var vat_percent = $(this).closest('tr').find('input[name="vat_percent[]"]').val();
        var discount_percentage = $(this).closest('tr').find('input[name="discount_percentage[]"]').val();
        //  var expenses_trans_percentage = $(this).closest('tr').find('input[name="expenses_trans_percentage[]"]').val();
        var ser_dept = $(this).closest('tr').find('input[name="ser_dept[]"]').val();

        var vat_total = $(this).closest('tr').find('input[name="vat_total[]"]').val();
        var expenses_adjustments= $(this).closest('tr').find('input[name="expenses_adjustments[]"]').val();

        recalc_classes(ser_dept,vat_percent,discount_percentage,expenses_trans_percentage,vat_total,expenses_adjustments);


    });

    $('input[name="expenses_trans_percentage[]"]').change(function () {


        var discount_total = $(this).closest('tr').find('input[name="discount_total[]"]').val();
        var expenses_trans_percentage = $(this).closest('tr').find('input[name="expenses_trans_percentage[]"]').val();


        $(this).closest('tr').find('input[name="expenses_transport[]"]').val(parseFloat(Number(expenses_trans_percentage) * Number(discount_total)).toFixed(2));


        var expenses_transport = $(this).closest('tr').find('input[name="expenses_transport[]"]').val();
        $(this).closest('tr').find('input[name="expenses_transport_total[]"]').val(parseFloat(Number(discount_total) + Number(expenses_transport)).toFixed(2));

        var expenses_transport_total = $(this).closest('tr').find('input[name="expenses_transport_total[]"]').val();

        calcall();
        var vat_percent = $(this).closest('tr').find('input[name="vat_percent[]"]').val();
        var discount_percentage = $(this).closest('tr').find('input[name="discount_percentage[]"]').val();
        //  var expenses_trans_percentage = $(this).closest('tr').find('input[name="expenses_trans_percentage[]"]').val();
        var ser_dept = $(this).closest('tr').find('input[name="ser_dept[]"]').val();

        var vat_total = $(this).closest('tr').find('input[name="vat_total[]"]').val();
        var expenses_adjustments= $(this).closest('tr').find('input[name="expenses_adjustments[]"]').val();

        recalc_classes(ser_dept,vat_percent,discount_percentage,expenses_trans_percentage,vat_total,expenses_adjustments);

    });

    $('input[name="vat_percent[]"]').change(function () {


        var vat_percent = $(this).closest('tr').find('input[name="vat_percent[]"]').val();

        var expenses_transport_total= $(this).closest('tr').find('input[name="expenses_transport_total[]"]').val();


        $(this).closest('tr').find('input[name="vat_value[]"]').val(parseFloat(Number(vat_percent) * Number(expenses_transport_total)).toFixed(2));
        var vat_value = $(this).closest('tr').find('input[name="vat_value[]"]').val();
        $(this).closest('tr').find('input[name="vat_total[]"]').val(parseFloat(Number(vat_value) + Number(expenses_transport_total)).toFixed(2));
        var vat_total = $(this).closest('tr').find('input[name="vat_total[]"]').val();
        var expenses_adjustments = $(this).closest('tr').find('input[name="expenses_adjustments[]"]').val();
        $(this).closest('tr').find('input[name="all_total[]"]').val(Number(vat_total) + Number(expenses_adjustments));


        calcall();

        // var vat_percent = $(this).closest('tr').find('input[name="vat_percent[]"]').val();
        var discount_percentage = $(this).closest('tr').find('input[name="discount_percentage[]"]').val();
        var expenses_trans_percentage = $(this).closest('tr').find('input[name="expenses_trans_percentage[]"]').val();
        var ser_dept = $(this).closest('tr').find('input[name="ser_dept[]"]').val();

        var vat_total = $(this).closest('tr').find('input[name="vat_total[]"]').val();
        var expenses_adjustments= $(this).closest('tr').find('input[name="expenses_adjustments[]"]').val();

        recalc_classes(ser_dept,vat_percent,discount_percentage,expenses_trans_percentage,vat_total,expenses_adjustments);

    });

    $('input[name="vat_percent[]"]').keyup(function () {


        var vat_percent = $(this).closest('tr').find('input[name="vat_percent[]"]').val();

        var expenses_transport_total= $(this).closest('tr').find('input[name="expenses_transport_total[]"]').val();


        $(this).closest('tr').find('input[name="vat_value[]"]').val(parseFloat(Number(vat_percent) * Number(expenses_transport_total)).toFixed(2));
        var vat_value = $(this).closest('tr').find('input[name="vat_value[]"]').val();
        $(this).closest('tr').find('input[name="vat_total[]"]').val(parseFloat(Number(vat_value) + Number(expenses_transport_total)).toFixed(2));
        var vat_total = $(this).closest('tr').find('input[name="vat_total[]"]').val();
        var expenses_adjustments = $(this).closest('tr').find('input[name="expenses_adjustments[]"]').val();
        $(this).closest('tr').find('input[name="all_total[]"]').val(Number(vat_total) + Number(expenses_adjustments));


        calcall();

        // var vat_percent = $(this).closest('tr').find('input[name="vat_percent[]"]').val();
        var discount_percentage = $(this).closest('tr').find('input[name="discount_percentage[]"]').val();
        var expenses_trans_percentage = $(this).closest('tr').find('input[name="expenses_trans_percentage[]"]').val();
        var ser_dept = $(this).closest('tr').find('input[name="ser_dept[]"]').val();

        var vat_total = $(this).closest('tr').find('input[name="vat_total[]"]').val();
        var expenses_adjustments= $(this).closest('tr').find('input[name="expenses_adjustments[]"]').val();

        recalc_classes(ser_dept,vat_percent,discount_percentage,expenses_trans_percentage,vat_total,expenses_adjustments);

    });

    $('input[name="vat_value[]"]').keyup(function () {


        var vat_value = $(this).closest('tr').find('input[name="vat_value[]"]').val();

        var expenses_transport_total= $(this).closest('tr').find('input[name="expenses_transport_total[]"]').val();


        $(this).closest('tr').find('input[name="vat_percent[]"]').val(parseFloat(Number(vat_value) / Number(expenses_transport_total)).toFixed(2));
        var vat_percent = $(this).closest('tr').find('input[name="vat_percent[]"]').val();
        $(this).closest('tr').find('input[name="vat_total[]"]').val(parseFloat(Number(vat_value) + Number(expenses_transport_total)).toFixed(2));
        var vat_total = $(this).closest('tr').find('input[name="vat_total[]"]').val();
        var expenses_adjustments = $(this).closest('tr').find('input[name="expenses_adjustments[]"]').val();
        $(this).closest('tr').find('input[name="all_total[]"]').val(Number(vat_total) + Number(expenses_adjustments));


        calcall();

        // var vat_percent = $(this).closest('tr').find('input[name="vat_percent[]"]').val();
        var discount_percentage = $(this).closest('tr').find('input[name="discount_percentage[]"]').val();
        var expenses_trans_percentage = $(this).closest('tr').find('input[name="expenses_trans_percentage[]"]').val();
        var ser_dept = $(this).closest('tr').find('input[name="ser_dept[]"]').val();

        var vat_total = $(this).closest('tr').find('input[name="vat_total[]"]').val();
        //alert(vat_total);
        var expenses_adjustments= $(this).closest('tr').find('input[name="expenses_adjustments[]"]').val();

        recalc_classes(ser_dept,vat_percent,discount_percentage,expenses_trans_percentage,vat_total,expenses_adjustments);

    });

    $('input[name="vat_value[]"]').change(function () {


        var vat_value = $(this).closest('tr').find('input[name="vat_value[]"]').val();

        var expenses_transport_total= $(this).closest('tr').find('input[name="expenses_transport_total[]"]').val();


        $(this).closest('tr').find('input[name="vat_percent[]"]').val(parseFloat(Number(vat_value) / Number(expenses_transport_total)).toFixed(2));
        var vat_percent = $(this).closest('tr').find('input[name="vat_percent[]"]').val();
        $(this).closest('tr').find('input[name="vat_total[]"]').val(parseFloat(Number(vat_value) + Number(expenses_transport_total)).toFixed(2));
        var vat_total = $(this).closest('tr').find('input[name="vat_total[]"]').val();
        var expenses_adjustments = $(this).closest('tr').find('input[name="expenses_adjustments[]"]').val();
        $(this).closest('tr').find('input[name="all_total[]"]').val(Number(vat_total) + Number(expenses_adjustments));


        calcall();

        // var vat_percent = $(this).closest('tr').find('input[name="vat_percent[]"]').val();
        var discount_percentage = $(this).closest('tr').find('input[name="discount_percentage[]"]').val();
        var expenses_trans_percentage = $(this).closest('tr').find('input[name="expenses_trans_percentage[]"]').val();
        var ser_dept = $(this).closest('tr').find('input[name="ser_dept[]"]').val();

        var vat_total = $(this).closest('tr').find('input[name="vat_total[]"]').val();
        //alert(vat_total);
        var expenses_adjustments= $(this).closest('tr').find('input[name="expenses_adjustments[]"]').val();

        recalc_classes(ser_dept,vat_percent,discount_percentage,expenses_trans_percentage,vat_total,expenses_adjustments);

    });
    $('input[name="expenses_adjustments[]"]').keyup(function () {

        if ($('#dp_donation_type').val() == '1')
        {
            var vat_total = $(this).closest('tr').find('input[name="vat_total[]"]').val();
            var expenses_adjustments = $(this).closest('tr').find('input[name="expenses_adjustments[]"]').val();


            $(this).closest('tr').find('input[name="all_total[]"]').val(Number(vat_total) + Number(expenses_adjustments));



            calcall();
            var vat_percent = $(this).closest('tr').find('input[name="vat_percent[]"]').val();
            var discount_percentage = $(this).closest('tr').find('input[name="discount_percentage[]"]').val();
            var expenses_trans_percentage = $(this).closest('tr').find('input[name="expenses_trans_percentage[]"]').val();
            var ser_dept = $(this).closest('tr').find('input[name="ser_dept[]"]').val();


            var vat_total = $(this).closest('tr').find('input[name="vat_total[]"]').val();
            var expenses_adjustments= $(this).closest('tr').find('input[name="expenses_adjustments[]"]').val();

            recalc_classes(ser_dept,vat_percent,discount_percentage,expenses_trans_percentage,vat_total,expenses_adjustments);
        }
        else
        if ($('#dp_donation_type').val() == '3')
        {

            var discount_total = $(this).closest('tr').find('input[name="discount_total[]"]').val();
            var expenses_adjustments = $(this).closest('tr').find('input[name="expenses_adjustments[]"]').val();


            $(this).closest('tr').find('input[name="all_total[]"]').val(Number(discount_total) + Number(expenses_adjustments));


            calcall();
            var vat_percent = $(this).closest('tr').find('input[name="vat_percent[]"]').val();
            var discount_percentage = $(this).closest('tr').find('input[name="discount_percentage[]"]').val();
            //  var expenses_trans_percentage = $(this).closest('tr').find('input[name="expenses_trans_percentage[]"]').val();
            var ser_dept = $(this).closest('tr').find('input[name="ser_dept[]"]').val();

            var vat_total = $(this).closest('tr').find('input[name="vat_total[]"]').val();
            var expenses_adjustments= $(this).closest('tr').find('input[name="expenses_adjustments[]"]').val();

            recalc_classes(ser_dept,vat_percent,discount_percentage,expenses_trans_percentage,vat_total,expenses_adjustments);

        }

    });


    $('input[name="expenses_adjustments[]"]').change(function () {

        if ($('#dp_donation_type').val() == '1')
        {

            var vat_total = $(this).closest('tr').find('input[name="vat_total[]"]').val();
            var expenses_adjustments = $(this).closest('tr').find('input[name="expenses_adjustments[]"]').val();


            $(this).closest('tr').find('input[name="all_total[]"]').val(Number(vat_total) + Number(expenses_adjustments));


            calcall();
            var vat_percent = $(this).closest('tr').find('input[name="vat_percent[]"]').val();
            var discount_percentage = $(this).closest('tr').find('input[name="discount_percentage[]"]').val();
            var expenses_trans_percentage = $(this).closest('tr').find('input[name="expenses_trans_percentage[]"]').val();
            var ser_dept = $(this).closest('tr').find('input[name="ser_dept[]"]').val();

            var vat_total = $(this).closest('tr').find('input[name="vat_total[]"]').val();
//var expenses_adjustments= $(this).closest('tr').find('input[name="expenses_adjustments[]"]').val();

            recalc_classes(ser_dept,vat_percent,discount_percentage,expenses_trans_percentage,vat_total,expenses_adjustments);
        }
        else
        if ($('#dp_donation_type').val() == '3')
        {

            var discount_total = $(this).closest('tr').find('input[name="discount_total[]"]').val();
            var expenses_adjustments = $(this).closest('tr').find('input[name="expenses_adjustments[]"]').val();


            $(this).closest('tr').find('input[name="all_total[]"]').val(Number(discount_total) + Number(expenses_adjustments));


            calcall();
            var vat_percent = $(this).closest('tr').find('input[name="vat_percent[]"]').val();
            var discount_percentage = $(this).closest('tr').find('input[name="discount_percentage[]"]').val();
            //  var expenses_trans_percentage = $(this).closest('tr').find('input[name="expenses_trans_percentage[]"]').val();
            var ser_dept = $(this).closest('tr').find('input[name="ser_dept[]"]').val();

            var vat_total = $(this).closest('tr').find('input[name="vat_total[]"]').val();
            var expenses_adjustments= $(this).closest('tr').find('input[name="expenses_adjustments[]"]').val();

            recalc_classes(ser_dept,vat_percent,discount_percentage,expenses_trans_percentage,vat_total,expenses_adjustments);

        }

    });
/////////////////////////////////////////////////////////////////////////
///HERE CODE JS

    //////////////////////////////////////////////////////////
    $('input[name="d_other_expenses[]"],input[name="d_expenses[]"],input[name="price1[]"],input[name="amount[]"],select[name="vat_type[]"],select[name="donation_dept_ser[]"],input[name="tax_persent[]"],input[name="tax_value[]"]').change(function () {
        if( $('#dp_donation_type').val()=='1')
        {
        var checked = $(this).closest('tr').find('select[name="donation_dept_ser[]"]').find(':selected').attr('data-checked');


if(checked!=1)
{
            var amount = $(this).closest('tr').find('input[name="amount[]"]').val();
            var price = $(this).closest('tr').find('input[name="price1[]"]').val();
            var d_expenses = $(this).closest('tr').find('input[name="d_expenses[]"]').val();
            var vat_type = $(this).closest('tr').find('select[name="vat_type[]"]').val();
            var tax_value = $(this).closest('tr').find('input[name="tax_value[]"]').val();
            var tax_persent = $(this).closest('tr').find('input[name="tax_persent[]"]').val();

            var total_with_expenses = calc_total_with_expenses(Number(amount),Number(price),Number(d_expenses)) ;


            var vat_percent = $(this).closest('tr').find('select[name="donation_dept_ser[]"]').find(':selected').attr('data-vat_percent');
            var discount_percentage = $(this).closest('tr').find('select[name="donation_dept_ser[]"]').find(':selected').attr('data-discount_percentage');
            var expenses_trans_percentage = $(this).closest('tr').find('select[name="donation_dept_ser[]"]').find(':selected').attr('data-expenses_trans_percentage');
            var vat_total = $(this).closest('tr').find('select[name="donation_dept_ser[]"]').find(':selected').attr('data-vat_total');
            var expenses_adjustments = $(this).closest('tr').find('select[name="donation_dept_ser[]"]').find(':selected').attr('data-expenses_adjustments');

            var price_without_discount=clac_price_without_discount(Number(total_with_expenses),Number(discount_percentage));


            var price_with_trans_expenses=clac_price_with_trans_expenses(Number(tax_value),Number(price_without_discount));
            var price_without_vat=calc_price_without_vat(Number(price_with_trans_expenses),vat_type,amount,Number(vat_percent));



            var tax_persent =calc_tax_persent(Number(price_without_discount),Number(tax_value));

            var d_other_expenses= calc_d_other_expenses(Number(expenses_adjustments),Number(vat_total),Number(price_without_vat));
            $(this).closest('tr').find('input[name="d_other_expenses[]"]').val(d_other_expenses);
            var last_price=clac_last_price(Number(price_without_vat),Number(d_other_expenses));
            var last_total=clac_last_total(Number(last_price),Number(amount));
            var class_price_no_vat=clac_class_price_no_vat(Number(price_with_trans_expenses),Number(d_other_expenses),Number(amount));
            $(this).closest('tr').find('input[name="total_with_expenses[]"]').val( total_with_expenses);
            $(this).closest('tr').find('input[name="price_without_vat[]"]').val(price_without_vat);
            //$(this).closest('tr').find('input[name="tax_value[]"]').val(tax_value);
            $(this).closest('tr').find('input[name="tax_persent[]"]').val(tax_persent);
            $(this).closest('tr').find('input[name="price_without_discount[]"]').val(price_without_discount);
            $(this).closest('tr').find('input[name="price_with_trans_expenses[]"]').val(price_with_trans_expenses);
            $(this).closest('tr').find('input[name="last_price[]"]').val(last_price);
            $(this).closest('tr').find('input[name="last_total[]"]').val(last_total);
            $(this).closest('tr').find('input[name="class_price_no_vat[]"]').val(class_price_no_vat);
            calc_all_det();
        }
        }
        else if( $('#dp_donation_type').val()=='3')
        {
            var amount = $(this).closest('tr').find('input[name="amount[]"]').val();
            var price = $(this).closest('tr').find('input[name="price1[]"]').val();

            var total_with_expenses = calc_total_with_expenses_install(Number(amount),Number(price)) ;
            var discount_percentage = $(this).closest('tr').find('select[name="donation_dept_ser[]"]').find(':selected').attr('data-discount_percentage');

            var price_without_discount=clac_price_without_discount_install(Number(total_with_expenses),Number(discount_percentage));
            var d_other_expenses=  $(this).closest('tr').find('input[name="d_other_expenses[]"]').val();
            var last_total=clac_last_total_install(Number(d_other_expenses),Number(price_without_discount));



            $(this).closest('tr').find('input[name="total_with_expenses[]"]').val(total_with_expenses);
            $(this).closest('tr').find('input[name="price_without_discount[]"]').val(price_without_discount);
            $(this).closest('tr').find('input[name="last_total[]"]').val(last_total);

        }
    });

/////////////////////////////////
}

function reBindAfterInsert(tr){
 /*$('input[name="donation_dept_name[]"]',tr).prop('disabled',false);
 $('input[name="donation_dept_name[]"]',tr).prop('readonly',false);*/

 $('input,select,textarea',tr).not($('input[name="total[]"],input[name="discount_total[]"],input[name="vat_total[]"],input[name="expenses_transport_total[]"],input[name="all_total[]"],input[name="class_name[]"],input[name="class_unit_id[]"],input[name="total_with_expenses[]"],input[name="price_without_discount[]"],input[name="price_with_trans_expenses[]"],input[name="price_without_vat[]"],input[name="d_other_expenses[]"],input[name="last_price[]"],input[name="last_total[]"],input[name="class_price_no_vat[]"]',tr)).prop('readonly',false);

}
function return_adopt(type) {

    action_type = type;
    $('#notesModal').modal();

}

function apply_action() {

    if (action_type == 1) {

        get_data('{$adopt_url}', {id: {$fid},adopts:1}, function (data) {

            if (data == '1')
            {
                success_msg('رسالة', 'تم العملية بنجاح ..');
                reload_Page();
            }



            else
            {
                danger_msg('المنحة غير متزنة');
            }

        }, 'html');






    }

    else if (action_type == 2) {

        get_data('{$adopt_url_close}', {id: {$fid},adopt:3}, function (data) {

            if (data == '1')
            {
                success_msg('رسالة', 'تم العملية بنجاح ..');
                reload_Page();
            }





        }, 'html');






    }

    else if (action_type == 3) {

        get_data('{$adopt_url_un_close}', {id: {$fid},adopt:2}, function (data) {

            if (data == '1')
            {
                success_msg('رسالة', 'تم العملية بنجاح ..');
                reload_Page();
            }





        }, 'html');






    }

    else if (action_type == 4) {

        get_data('{$get_store_donation}', {id: {$fid},acount_don:$('#h_txt_donation_account').val(),store_don:$('#dp_store_id').val(),adopt:4}, function (data) {

            if (data == '1')
            {
                success_msg('رسالة', 'تم العملية بنجاح ..');
                reload_Page();
            }





        }, 'html');






    }

    else if (action_type == 5) {

        get_data('{$un_do_store_donation}', {id: {$fid},adopt:2}, function (data) {

            if (data == '1')
            {
                success_msg('رسالة', 'تم العملية بنجاح ..');
                reload_Page();
            }
            else
            {  danger_msg('يوجد حركة على المنحة'); }





        }, 'html');






    }

    get_data('{$notes_url}', {source_id: {$fid}, source: 'donation_file', notes: $('#txt_g_notes').val()}, function (data) {

        $('#txt_g_notes').val('');
    }, 'html');
    $('#notesModal').modal('hide');


}

function delete_row_del(id, donation_id) {
    if (confirm('هل تريد بالتأكيد حذف الصنف ؟!!')) {

        var values = {id: id, donation_id: donation_id};
        get_data('{$delete_url_details}', values, function (data) {

            if (data == '1') {

                success_msg('تمت عملية الحذف بنجاح');
                get_to_link(window.location.href);
            }
            else if (data == '-1') {

                danger_msg('لم يتم الحذف لابد من وجود صنف واحد على الاقل');
            }
            else {
                danger_msg('لم يتم الحذف', data);
            }

        }, 'html');

    }

}

function delete_row_del_dept(id, donation_id) {
    if (confirm('هل تريد بالتأكيد حذف هذا الفسم ؟!!')) {

        var values = {id: id, donation_id: donation_id};
        get_data('{$delete_url_dept}', values, function (data) {

            if (data == '1') {

                success_msg('تمت عملية الحذف بنجاح');
                get_to_link(window.location.href);
            }
            else if (data == '-1') {

                danger_msg('لم يتم الحذف لابد من وجود قسم واحد على الاقل');
            }
            else {
                danger_msg('لم يتم الحذف', data);
            }

        }, 'html');

    }

}

$(document).ready(function () {


    curr_changed();
    reBind();
    $('#txt_curr_date').change(function(){
        get_data('{$currency_date_url}',{date:$(this).val()},function(data){

            if(data.length > 0){
                $('#dp_curr_id').html('');

                $.each(data,function(i,item){
                    $('#dp_curr_id').append('<option data-val="'+item.VAL+'"   value="'+item.CURR_ID+'">'+item.CURR_NAME+'</option>');

                });

                curr_changed();
            }
        });
    });







    $('#dp_store_id').select2().on('change', function () {

    });

    $('#txt_donation_account').click(function (e) {

       if(dopt_is=='4')
       {

        _showReport('$select_accounts_url/' + $(this).attr('id'));
        }
        else
        return;

    });


$('#h_txt_donation_account').bind('keyup', '+', function(e) {
if(dopt_is=='4')
       {
   $(this).val( $(this).val().replace('+', ''));
  _showReport('$select_accounts_url/'+'txt_donation_account'+ $('#h_data_search').val());
get_account_name($(this));
}
else
        return;
           });

$('#h_txt_donation_account').change(function(e) {
if( dopt_is=='4')
       {
get_account_name($(this));
}
else
        return;
           });

function get_account_name(obj){

            if($(obj).val().length >6  || $(obj).val().match("^60")){
                get_data('{$get_account_name_url}',{id:$(obj).val()},function(data){

                    if((data.length > 0)){
$('#txt_donation_account').val(data[0].ACOUNT_NAME);
                    }else{
  $(obj).val('');
  $('#txt_donation_account').val('');
                    }
                });
            }
    }
    function curr_changed() {
        $('#txt_curr_value').val($(dp_curr_id).find(':selected').attr('data-val'));
        $('#dp_curr_id').change(function () {

            $('#txt_curr_value').val($(this).find(':selected').attr('data-val'));

        });
    }

    $('#dp_donation_type').on('change', function () {



        if ($('#dp_donation_type').val() == '1') {
            if(dopt_is=='4')
            {
                $('#dp_store_id').prop('readonly', false);//jQuery <1.9
                $('#dp_store_id').attr('readonly', false);//jQuery 1.9+
            }
            else
            {
                $('#dp_store_id').prop('readonly', true);//jQuery <1.9
                $('#dp_store_id').attr('readonly', true);//jQuery 1.9+
            }
            $('#txt_other_expenses').prop('readonly', true);//jQuery <1.9
            $('#txt_other_expenses').attr('readonly', true);//jQuery 1.9+
            $('#txt_other_expenses').val('0');
            var type=$('#dp_donation_type').val();
            var isfound={$order_id_val};


            get_data('{$show_details_url}',{id:{$order_id_val}, adopt:{$dopt},is_install:type},function(data){
                $('#classes').html(data);
                reBind();
            },'html');

            if(isfound!=0){
                get_data('{$show_det_class_url}',{id:{$order_id_val}, adopt:{$dopt},type_donation_det:type},function(data){

                    $('#lana').html(data);
                    reBind();
                },'html');}
        }
        else if ($('#dp_donation_type').val() == '3') {

            $('#dp_store_id').prop('readonly', true);//jQuery <1.9
            $('#dp_store_id').attr('readonly', true);//jQuery 1.9+
            $('#txt_other_expenses').prop('readonly', true);//jQuery <1.9
            $('#txt_other_expenses').attr('readonly', true);//jQuery 1.9+
            $('#txt_other_expenses').val('0');
            var type=$('#dp_donation_type').val();
            var isfound={$order_id_val};
            get_data('{$show_details_url}',{id:{$order_id_val}, adopt:{$dopt},is_install:type},function(data){

                $('#classes').html(data);
                reBind();
            },'html');
            if(isfound!=0){
                get_data('{$show_det_class_url}',{id:{$order_id_val}, adopt:{$dopt},type_donation_det:type},function(data){

                    $('#lana').html(data);
                    reBind();
                },'html');
            }

        }
        else {
            $('#dp_store_id').prop('readonly', true);//jQuery <1.9
            $('#dp_store_id').attr('readonly', true);//jQuery 1.9+
            $('#dp_store_id').val('');
            $('#txt_other_expenses').prop('readonly', false);//jQuery <1.9
            $('#txt_other_expenses').attr('readonly', false);//jQuery 1.9+
            $('#txt_other_expenses').val('0');

            var type=$('#dp_donation_type').val();
            var isfound={$order_id_val};
            get_data('{$show_details_url}',{id:{$order_id_val}, adopt:{$dopt},is_install:type},function(data){

                $('#classes').html(data);
                reBind();
            },'html');

        }
        if(isfound!=0){
            get_data('{$show_det_class_url}',{id:{$order_id_val}, adopt:{$dopt},type_donation_det:type},function(data){

                $('#lana').html(data);
                reBind();
            },'html');
        }
    });



    $('button[data-action="submit"]').click(function (e) {
        e.preventDefault();

        var type=$('#dp_donation_type').val();


        if (confirm('هل تريد حفظ السند ؟!')) {

            $(this).attr('disabled', 'disabled');
            var form = $(this).closest('form');

            ajax_insert_update(form, function (data) {

                if (parseInt(data) > 1) {
                    success_msg('رسالة', 'تم حفظ البيانات بنجاح ..');
                    get_to_link('{$get_url}/' + parseInt(data)+'/' + parseInt(type));
                } else if (data == 1) {
                    success_msg('رسالة', 'تم حفظ البيانات بنجاح ..');
                    get_to_link(window.location.href);
                } else {
                    danger_msg('تحذير..', data);
                }
            }, 'html');
        }



        setTimeout(function () {
            $('button[data-action="submit"]').removeAttr('disabled');
        }, 3000);
    });



});

function calc_total_with_expenses(amount,price,d_expenses){
    return parseFloat(((amount*price)+d_expenses)).toFixed(2);

}

function calc_total_with_expenses_install(amount,price){
    return parseFloat((amount*price)).toFixed(2);

}

function calc_price_without_vat(total_with_expenses,vat_type,amount,vat_percent){
    if (vat_type==1)
        return parseFloat(total_with_expenses/(1+vat_percent)/amount).toFixed(2);
    else if (vat_type==2)
        return parseFloat(total_with_expenses/amount).toFixed(2);
    else
        return parseFloat(total_with_expenses+(total_with_expenses*vat_percent)).toFixed(2);
}


function clac_price_without_discount(total_with_expenses,discount_percentage){
    return parseFloat(total_with_expenses-(total_with_expenses*discount_percentage)).toFixed(2);
}


function clac_price_without_discount_install(total_with_expenses,discount_percentage){
    return parseFloat(total_with_expenses-(total_with_expenses*discount_percentage)).toFixed(2);
}

function clac_price_with_trans_expenses(price_without_discount,expenses_trans_percentage){
    return parseFloat(price_without_discount+expenses_trans_percentage).toFixed(2);
}

function calc_d_other_expenses(expenses_adjustments,vat_total,price_without_vat){
    return parseFloat((expenses_adjustments/vat_total)*price_without_vat).toFixed(2);
}

function clac_last_price(price_without_vat,d_other_expenses){
    return parseFloat(price_without_vat+d_other_expenses).toFixed(2);
}
function clac_last_total(last_price,amount){
    return parseFloat(last_price/amount).toFixed(2);
}

function clac_last_total_install(d_other_expenses,price_without_discount){
    return parseFloat(price_without_discount+d_other_expenses).toFixed(2);
}

function clac_class_price_no_vat(price_with_trans_expenses,d_other_expenses,amount){
    return parseFloat((price_with_trans_expenses+d_other_expenses)/amount).toFixed(2);
}

function calc_tax_value(price_without_discount,tax_persent){
    return parseFloat(price_without_discount*tax_persent).toFixed(2);
}


function calc_tax_persent(price_without_discount,tax_value){
    return parseFloat(tax_value/price_without_discount).toFixed(2);
}


function calc_det(thiss){
    if( $('#dp_donation_type').val()=='1')
    {
      var checked = $(thiss).closest('tr').find('select[name="donation_dept_ser[]"]').find(':selected').attr('data-checked');



if(checked!=1)
{
        var amount = $(thiss).closest('tr').find('input[name="amount[]"]').val();
        var price = $(thiss).closest('tr').find('input[name="price1[]"]').val();
        var d_expenses = $(thiss).closest('tr').find('input[name="d_expenses[]"]').val();
        var vat_type = $(thiss).closest('tr').find('select[name="vat_type[]"]').val();
        var total_with_expenses = calc_total_with_expenses(Number(amount),Number(price),Number(d_expenses)) ;
        var vat_percent = $(thiss).closest('tr').find('select[name="donation_dept_ser[]"]').find(':selected').attr('data-vat_percent');
        var discount_percentage = $(thiss).closest('tr').find('select[name="donation_dept_ser[]"]').find(':selected').attr('data-discount_percentage');
        var expenses_trans_percentage = $(thiss).closest('tr').find('select[name="donation_dept_ser[]"]').find(':selected').attr('data-expenses_trans_percentage');

        var vat_total = $(thiss).closest('tr').find('select[name="donation_dept_ser[]"]').find(':selected').attr('data-vat_total');
        var expenses_adjustments = $(thiss).closest('tr').find('select[name="donation_dept_ser[]"]').find(':selected').attr('data-expenses_adjustments');

        var tax_persent = $(this).closest('tr').find('input[name="tax_persent[]"]').val();
        var tax_value = $(this).closest('tr').find('input[name="tax_value[]"]').val();

        var price_without_discount=clac_price_without_discount(Number(total_with_expenses),Number(discount_percentage));

        var price_with_trans_expenses=clac_price_with_trans_expenses(isNaNVal(Number(tax_value)),Number(price_without_discount));
        var price_without_vat=calc_price_without_vat(Number(price_with_trans_expenses),vat_type,amount,Number(vat_percent));
        var d_other_expenses= calc_d_other_expenses(Number(expenses_adjustments),Number(vat_total),Number(price_without_vat));
        var tax_persent =calc_tax_persent(Number(price_without_discount),Number(tax_value));




        $(this).closest('tr').find('input[name="tax_persent[]"]').val(tax_persent);
        $(thiss).closest('tr').find('input[name="d_other_expenses[]"]').val(d_other_expenses);
        var last_price=clac_last_price(Number(price_without_vat),Number(d_other_expenses));
        var last_total=clac_last_total(Number(last_price),Number(amount));
        var class_price_no_vat=clac_class_price_no_vat(Number(price_with_trans_expenses),Number(d_other_expenses),Number(amount));
        $(thiss).closest('tr').find('input[name="total_with_expenses[]"]').val( total_with_expenses);
        $(thiss).closest('tr').find('input[name="price_without_vat[]"]').val(price_without_vat);

        $(thiss).closest('tr').find('input[name="price_without_discount[]"]').val(price_without_discount);
        $(thiss).closest('tr').find('input[name="price_with_trans_expenses[]"]').val(price_with_trans_expenses);
        $(thiss).closest('tr').find('input[name="last_price[]"]').val(last_price);
        $(thiss).closest('tr').find('input[name="last_total[]"]').val(last_total);
        $(thiss).closest('tr').find('input[name="class_price_no_vat[]"]').val(class_price_no_vat);
    }
    }
    else if( $('#dp_donation_type').val()=='3')
    {
        var amount = $(thiss).closest('tr').find('input[name="amount[]"]').val();
        var price = $(thiss).closest('tr').find('input[name="price1[]"]').val();

        var total_with_expenses = calc_total_with_expenses_install(Number(amount),Number(price)) ;
        var discount_percentage = $(thiss).closest('tr').find('select[name="donation_dept_ser[]"]').find(':selected').attr('data-discount_percentage');

        var price_without_discount=clac_price_without_discount_install(Number(total_with_expenses),Number(discount_percentage));
        var d_other_expenses=  $(thiss).closest('tr').find('input[name="d_other_expenses[]"]').val();
        var last_total=clac_last_total_install(Number(d_other_expenses),Number(price_without_discount));



        $(thiss).closest('tr').find('input[name="total_with_expenses[]"]').val(total_with_expenses);
        $(thiss).closest('tr').find('input[name="price_without_discount[]"]').val(price_without_discount);
        $(thiss).closest('tr').find('input[name="last_total[]"]').val(last_total);

    }
}


//calc_total_with_expenses(amount,price,d_expenses)
//calc_price_without_vat(total_with_expenses,amount,vat_type,vat_percent)
//clac_price_without_discount(price_without_vat,discount_percentage)
//clac_price_with_trans_expenses(price_without_discount,expenses_trans_percentage)
//clac_last_price(price_with_trans_expenses,d_other_expenses)
// clac_last_total(last_price,amount)
$('input[name="d_other_expenses[]"],input[name="d_expenses[]"],input[name="price1[]"],input[name="amount[]"],select[name="vat_type[]"],select[name="donation_dept_ser[]"]').change(function () {
    if( $('#dp_donation_type').val()=='1')
    {
      var checked = $(this).closest('tr').find('select[name="donation_dept_ser[]"]').find(':selected').attr('data-checked');



if(checked!=1)
{
        var amount = $(this).closest('tr').find('input[name="amount[]"]').val();
        var price = $(this).closest('tr').find('input[name="price1[]"]').val();
        var d_expenses = $(this).closest('tr').find('input[name="d_expenses[]"]').val();
        var vat_type = $(this).closest('tr').find('select[name="vat_type[]"]').val();
        var tax_persent = $(this).closest('tr').find('input[name="tax_persent[]"]').val();
        var tax_value = $(this).closest('tr').find('input[name="tax_value[]"]').val();
        var total_with_expenses = calc_total_with_expenses(Number(amount),Number(price),Number(d_expenses)) ;
        var vat_percent = $(this).closest('tr').find('select[name="donation_dept_ser[]"]').find(':selected').attr('data-vat_percent');
        var discount_percentage = $(this).closest('tr').find('select[name="donation_dept_ser[]"]').find(':selected').attr('data-discount_percentage');
        //  alert(Number(discount_percentage));
        var expenses_trans_percentage = $(this).closest('tr').find('select[name="donation_dept_ser[]"]').find(':selected').attr('data-expenses_trans_percentage');
        var vat_total = $(this).closest('tr').find('select[name="donation_dept_ser[]"]').find(':selected').attr('data-vat_total');
        var expenses_adjustments = $(this).closest('tr').find('select[name="donation_dept_ser[]"]').find(':selected').attr('data-expenses_adjustments');

        var price_without_discount=clac_price_without_discount(Number(total_with_expenses),Number(discount_percentage));

        var price_with_trans_expenses=clac_price_with_trans_expenses(Number(tax_value),Number(price_without_discount));
        var price_without_vat=calc_price_without_vat(Number(price_with_trans_expenses),vat_type,amount,Number(vat_percent));
        var d_other_expenses= calc_d_other_expenses(Number(expenses_adjustments),Number(vat_total),Number(price_without_vat));
        //alert(d_other_expenses);
        $(this).closest('tr').find('input[name="d_other_expenses[]"]').val(d_other_expenses);
        var last_price=clac_last_price(Number(price_without_vat),Number(d_other_expenses));
        var last_total=clac_last_total(Number(last_price),Number(amount));
        var class_price_no_vat=clac_class_price_no_vat(Number(price_with_trans_expenses),Number(d_other_expenses),Number(amount));
        var tax_persent =calc_tax_persent(Number(price_without_discount),Number(tax_value));



        $(this).closest('tr').find('input[name="tax_value[]"]').val(tax_value);
        $(this).closest('tr').find('input[name="tax_persent[]"]').val(tax_persent);
        $(this).closest('tr').find('input[name="total_with_expenses[]"]').val( total_with_expenses);
        $(this).closest('tr').find('input[name="price_without_vat[]"]').val(price_without_vat);

        $(this).closest('tr').find('input[name="price_without_discount[]"]').val(price_without_discount);
        $(this).closest('tr').find('input[name="price_with_trans_expenses[]"]').val(price_with_trans_expenses);
        $(this).closest('tr').find('input[name="last_price[]"]').val(last_price);
        $(this).closest('tr').find('input[name="last_total[]"]').val(last_total);
        $(this).closest('tr').find('input[name="class_price_no_vat[]"]').val(class_price_no_vat);
        calc_all_det();
    }
}
    else if( $('#dp_donation_type').val()=='3')
    {
        var amount = $(this).closest('tr').find('input[name="amount[]"]').val();
        var price = $(this).closest('tr').find('input[name="price1[]"]').val();

        var total_with_expenses = calc_total_with_expenses_install(Number(amount),Number(price)) ;
        var discount_percentage = $(this).closest('tr').find('select[name="donation_dept_ser[]"]').find(':selected').attr('data-discount_percentage');

        var price_without_discount=clac_price_without_discount_install(Number(total_with_expenses),Number(discount_percentage));
        var d_other_expenses=  $(this).closest('tr').find('input[name="d_other_expenses[]"]').val();
        var last_total=clac_last_total_install(Number(d_other_expenses),Number(price_without_discount));



        $(this).closest('tr').find('input[name="total_with_expenses[]"]').val(total_with_expenses);
        $(this).closest('tr').find('input[name="price_without_discount[]"]').val(price_without_discount);
        $(this).closest('tr').find('input[name="last_total[]"]').val(last_total);

    }
});

calc_all_det();

function dept_save(obj){
    if(confirm('هل تريد إتمام العملية ؟')){
        var tbl = '#{$TB_NAME}_tb';
        var container = $('#' + $(tbl).attr('data-container'));
        var form =  $(obj).closest('form');
        $(form).attr('action','{$department_url}');
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
    function dept_save_add(obj){
        if(confirm('هل تريد إتمام العملية ؟')){
            var tbl = '#{$TB_NAME}_tb';
            var container = $('#' + $(tbl).attr('data-container'));
            var form =  $(obj).closest('form');
            $(form).attr('action','{$addtinal_lots_url}');
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
</script>

SCRIPT;

sec_scripts($scripts);

?>

