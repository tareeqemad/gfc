<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mtaqia
 * Date: 2019-09-21
 * Time: 10:05 PM
 */
$report_url = base_url("JsperReport/showreport?sys=financial/prepaid_charges");
$report_url2 = base_url("JsperReport/showreport?sys=online_rep/prepaid");

$MODULE_NAME= 'financial';
$TABLE_NAME= 'prepaid_agents_reports';
$get_where_url = base_url("$MODULE_NAME/$TABLE_NAME/public_get_where_collect");

?>
<?= AntiForgeryToken(); ?>

<div class="row">

    <div class="toolbar">
        <div class="caption"><?= $title ?></div>
    </div>

    <div class="form-body">

        <fieldset>
            <legend>التقارير</legend>
            <div class="modal-body inline_form">
                <div class="form-group rp col-sm-3" id="rep_id">
                    <label class="control-label">اسم التقرير</label>
                    <div>
                        <select name="rep_id" class="form-control" id="dp_rep_id">
                            <option value="0">_______________</option>
                            <option value="1">1- وكلاء الشحن المسبق</option>
                            <option value="2">2- تقرير الشحن اليومي - Holley</option>
                            <option value="3">3- حركات شحن خارجية</option>
                            <option value="4">4- حركات شحن Palpay</option>
                            <option value="5">5- الشحنات حسب رقم الإشتراك</option>
                            <option value="6">6- الشحنات حسب شركة الشحن</option>
                            <option value="7">7- معاملات غير نقدية</option>
                            <option value="8">8- دفعات محطات الشحن</option>
                            <option value="9">9- شحنات ديكسن منحة الشركة 200 ك.و.</option>
                            <option value="10">10- مبالغ الشحنات</option>
                            <option value="11">11- متابعة سندات الشحن</option>
                            <option value="12">12- أرصدة الفوترة للشركات الخارجية</option>
                            <option value="13">13- أرصدة محطات الشحن</option>
                            <option value="14">14- تحليل المقبوضات النقدية قبل نقل الذمم</option>
                            <option value="15">15- تحليل المقبوضات النقدية بعد نقل الذمم</option>
                            <option value="16">16- كشف يومي لتحصيلات العدادات مسبقة الدفع</option>
                        </select>
                    </div>
                </div>
            </div>
        </fieldset>

        <fieldset>
            <legend>معايير البحث</legend>

            <div class="modal-body inline_form">

                <div class="form-group rp col-sm-1 op" id="from_date_id" style="display:none;">
                    <label class="control-label">من تاريخ</label>
                    <div>
                        <input type="text" data-type="date" name="from_date" data-date-format="DD/MM/YYYY" id="txt_from_date_id" class="form-control">
                    </div>
                </div>

                <div class="form-group rp col-sm-1 op" id="to_date_id" style="display:none;">
                    <label class="control-label">إلى تاريخ</label>
                    <div>
                        <input type="text" data-type="date" name="to_date" data-date-format="DD/MM/YYYY" id="txt_to_date_id" class="form-control">
                    </div>
                </div>

                <div class="form-group rp col-sm-1 op" id="from_date_id2" style="display:none;">
                    <label class="control-label">من تاريخ</label>
                    <div>
                        <input type="text" data-type="date" name="from_date2" data-date-format="YYYY.MM.DD" id="txt_from_date_id2" class="form-control">
                    </div>
                </div>

                <div class="form-group rp col-sm-1 op" id="to_date_id2" style="display:none;">
                    <label class="control-label">إلى تاريخ</label>
                    <div>
                        <input type="text" data-type="date" name="to_date2" data-date-format="YYYY.MM.DD" id="txt_to_date_id2" class="form-control">
                    </div>
                </div>

                <div class="form-group rp col-sm-1 op" id="collect_branch_id_2" style="display:none;">
                    <label class="control-label">مقر التحصيل</label>
                    <div>
                        <select name="collect_branch_id_2" class="form-control" id="dp_collect_branch_id_2">
                            <option value="">------- الجميـــع -------</option>
                            <option value="1">غزة</option>
                            <option value="2">الشمال</option>
                            <option value="3">الوسطى</option>
                            <option value="4">خانيونس</option>
                            <option value="5">رفح</option>
                        </select>
                    </div>
                </div>

                <div class="form-group rp col-sm-1 op" id="sub_branch_id_2" style="display:none;">
                    <label class="control-label">مقر المشترك</label>
                    <div>
                        <select name="sub_branch_id_2" class="form-control" id="dp_sub_branch_id_2">
                            <option value="">------- الجميـــع -------</option>
                            <option value="1">غزة</option>
                            <option value="2">الشمال</option>
                            <option value="3">الوسطى</option>
                            <option value="4">خانيونس</option>
                            <option value="5">رفح</option>
                        </select>
                    </div>
                </div>

                <div class="form-group rp col-sm-1 op" id="from_date_id3" style="display:none;">
                    <label class="control-label">من تاريخ</label>
                    <div>
                        <input type="text" data-type="date" name="from_date3" data-date-format="YYYYMMDD" id="txt_from_date_id3" class="form-control">
                    </div>
                </div>

                <div class="form-group rp col-sm-1 op" id="to_date_id3" style="display:none;">
                    <label class="control-label">إلى تاريخ</label>
                    <div>
                        <input type="text" data-type="date" name="to_date3" data-date-format="YYYYMMDD" id="txt_to_date_id3" class="form-control">
                    </div>
                </div>

                <div class="form-group rp col-sm-1 op" id="sub_no_id" style="display:none ;">
                    <label class="control-label">رقم الإشتراك</label>
                    <div>
                        <input type="text" name="sub_no" id="txt_sub_no_id" value="" class="form-control">
                    </div>
                </div>
				
				<div class="form-group rp col-sm-1 op" id="serial_id" style="display:none ;">
                    <label class="control-label">مسلسل الشحنة</label>
                    <div>
                        <input type="text" name="serial" id="txt_serial_id" value="" class="form-control">
                    </div>
                </div>
				
                <div class="form-group rp col-sm-1 op" id="meter_type_id" style="display:none;">
                    <label class="control-label">نوع العداد</label>
                    <div>
                        <select name="meter_type_id" class="form-control" id="dp_meter_type_id" onchange="get_where();">
                            <option value="">------- الجميـــع -------</option>
                            <option value="1">دكســــن</option>
                            <option value="2">هولـــي</option>
                        </select>
                    </div>
                </div>

                <div class="form-group rp col-sm-1 op" id="payment_amount_id" style="display:none ;">
                    <label class="control-label">قيمة الدفعة</label>
                    <div>
                        <input type="text" name="payment_amount" id="txt_payment_amount_id" value="" class="form-control">
                    </div>
                </div>

                <div class="form-group rp col-sm-1 op" id="collect_branch_id" style="display:none ;">
                    <label class="control-label">مقر الشحن</label>
                    <div>
                        <select name="collect_branch_id" class="form-control" id="dp_collect_branch_id">
                            <option value="">جميع المقرات</option>
                            <?php foreach ($branches as $row) : ?>
                                <option data-dept="<?= $row['NO'] ?>"
                                        value="<?= $row['NO'] ?>"><?= $row['NO'].' : '.$row['NAME'];?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group rp col-sm-1 op" id="sub_branch_id" style="display:none;">
                    <label class="control-label">مقر المشترك</label>
                    <div>
                        <select name="sub_branch_id" class="form-control" id="dp_sub_branch_id" onchange="get_where();">
                            <option value="">جميع المقرات</option>
                            <?php foreach ($branches as $row) : ?>
                                <option data-dept="<?= $row['NO'] ?>"
                                        value="<?= $row['NO'] ?>"><?= $row['NO'].' : '.$row['NAME'];?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group rp col-sm-1 op" id="agent_branch_id" style="display:none;">
                    <label class="control-label">مقر محطة الشحن</label>
                    <div>
                        <select name="agent_branch_id" class="form-control" id="dp_agent_branch_id">
                            <option value="">جميع المقرات</option>
                            <?php foreach ($branches as $row) : ?>
                                <option data-dept="<?= $row['NO'] ?>"
                                        value="<?= $row['NO'] ?>"><?= $row['NO'].' : '.$row['NAME'];?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group rp col-sm-1 op" id="branch_id" style="display:none;">
                    <label class="control-label">مقر المشترك</label>
                    <div>
                        <select name="branch_id" class="form-control" id="dp_branch_id">
                            <option value="">جميع المقرات</option>
                            <?php foreach ($branches as $row) : ?>
                                <option data-dept="<?= $row['NO'] ?>"
                                        value="<?= $row['NO'] ?>"><?= $row['NO'].' : '.$row['NAME'];?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group rp col-sm-2 op" id="charge_company_id" style="display:none;">
                    <label class="control-label">شركة الشحن</label>
                    <div>
                        <select name="charge_company_id" class="form-control" id="dp_charge_company_id" onchange="get_where();">
                            <option value="">جميع الشركات</option>
                            <?php foreach ($charge_company as $row) : ?>
                                <option data-dept="<?= $row['CON_NO'] ?>"
                                        value="<?= $row['CON_NO'] ?>"><?= $row['CON_NO'].' : '.$row['CON_NAME'];?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group rp col-sm-2 op" id="charge_company_id_2" style="display:none;">
                    <label class="control-label">شركة الشحن</label>
                    <div>
                        <select name="charge_company_id_2" class="form-control" id="dp_charge_company_id_2" >
                            <option value="">جميع الشركات</option>
                            <option value="0">شركة الكهرباء</option>
                            <option value="1">مرتجى</option>
                            <option value="2">القدوة</option>
                            <option value="3">أيوب</option>
                            <option value="4">البدرساوي</option>
                            <option value="5">بال باي</option>
                            <option value="6">محمد عيد</option>
                        </select>
                    </div>
                </div>

                <div class="form-group rp col-sm-1 op" id="branch_id_2" style="display:none;">
                    <label class="control-label">المقر</label>
                    <div>
                        <select name="branch_id_2" class="form-control" id="dp_branch_id_2">
                            <option value="">جميع المقرات</option>
                            <option value="1">مقر غزة</option>
                            <option value="2">مقر الشمال</option>
                            <option value="3">مقر الوسطى</option>
                            <option value="4">مقر خانيونس</option>
                            <option value="5">مقر رفح</option>
                        </select>
                    </div>
                </div>
				
                <div class="form-group rp col-sm-2 op" id="charge_type_id" style="display:none;">
                    <label class="control-label">نوع الشحنة</label>
                    <div>
                        <select name="charge_type_id" class="form-control" id="dp_charge_type_id">
                            <option value="">----- الجميع -----</option>
                            <?php foreach ($charge_type as $row) : ?>
                                <option data-dept="<?= $row['CON_NO'] ?>"
                                        value="<?= $row['CON_NO'] ?>"><?= $row['CON_NO'].' : '.$row['CON_NAME'];?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group rp col-sm-3 op" id="charge_agent_id" style="display:none;">
                    <label class="control-label">وكلاء الشحن</label>
                    <div>
                        <select name="charge_agent_id" class="form-control" id="dp_charge_agent_id">
                            <option value="">جميع وكلاء الشحن</option>
                            <?php foreach ($agent as $row) : ?>
                                <option data-dept="<?= $row['SER'] ?>"
                                        value="<?= $row['SER'] ?>"><?= $row['SER'].' : '.$row['NAME'];?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group rp col-sm-3 op" id="status_id" style="display:none;">
                    <label class="control-label">حالة الترحيل للفواتير</label>
                    <div>
                        <span>
                            <input type="checkbox" name="status_2" value="2" style="transform: scale(1.5)">&nbsp;
                            <span style="font-size: 13px">غير مرحل</span>
                        </span>
                        <span>
                            <input type="checkbox" name="status_3" value="3" style="transform: scale(1.5)">&nbsp;
                            <span style="font-size: 13px">مرحل للفواتير</span>
                        </span>
                    </div>
                </div>

                <div class="form-group rp col-sm-1 op" id="paid_type_id" style="display:none;">
                    <label class="control-label">نوع الدفعة</label>
                    <div>
                        <select name="paid_type_id" class="form-control" id="dp_paid_type_id">
                            <option value="">--- الجميع ---</option>
                            <?php foreach ($paid_type as $row) : ?>
                                <option data-dept="<?= $row['CON_NO'] ?>"
                                        value="<?= $row['CON_NO'] ?>"><?= $row['CON_NO'].' : '.$row['CON_NAME'];?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group rp col-sm-1 op" id="balance_type_id" style="display:none;">
                    <label class="control-label">نوع الشحنة</label>
                    <div>
                        <select name="balance_type_id" class="form-control" id="dp_balance_type_id">
                            <option value="">--- الجميع ---</option>
                            <?php foreach ($balance_type as $row) : ?>
                                <option data-dept="<?= $row['CON_NO'] ?>"
                                        value="<?= $row['CON_NO'] ?>"><?= $row['CON_NO'].' : '.$row['CON_NAME'];?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
				
				<div class="form-group rp col-sm-1 op" id="is_cancel_id" style="display:none;">
                    <label class="control-label">الحالة</label>
                    <div>
                        <select name="is_cancel_id" class="form-control" id="dp_is_cancel_id">
                            <option value="">--- الجميع ---</option>
                            <?php foreach ($is_cancel as $row) : ?>
                                <option data-dept="<?= $row['CON_NO'] ?>"
                                        value="<?= $row['CON_NO'] ?>"><?= $row['CON_NO'].' : '.$row['CON_NAME'];?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group rp col-sm-1 op" id="from_month_id" style="display:none ;">
                    <label class="control-label">من شهر</label>
                    <div>
                        <input type="text" name="from_month" id="txt_from_month_id" value="<?=date('Ym')?>" class="form-control">
                    </div>
                </div>

                <div class="form-group rp col-sm-1 op" id="to_month_id" style="display:none ;">
                    <label class="control-label">إلى شهر</label>
                    <div>
                        <input type="text" name="to_month" id="txt_to_month_id" value="<?=date('Ym')?>" class="form-control">
                    </div>
                </div>

                <br/><br/>

                <div class="form-group rp col-sm-2 op" id="rep_type" style="display:none;">
                    <label class="control-label">نوع المستند</label>
                    <div>
                        <input type="radio"  name="rep_type" value="pdf" checked="checked">
                        <i class="fa fa-file-pdf-o" style="font-size:28px;color:#e2574c"></i>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="radio"  name="rep_type" value="xls">
                        <i class="fa fa-file-excel-o" style="font-size:28px;color:#1d7044"></i>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="radio"  name="rep_type" value="doc">
                        <i class="fa fa-file-word-o" style="font-size:28px;color:#2a5696"></i>
                    </div>
                </div>

                <div class="form-group rp col-sm-3 op" id="group_by" style="display:none;">
                    <label class="control-label">تجميع حسب</label>
                    <div>
                        <span id="group_by_1" class="op" style="display:none;" >
                            <input type="checkbox" name="group_by_charge_company" value="1" style="transform: scale(1.5)">&nbsp;
                            <span style="font-size: 13px">شركة الشحن</span>
                        </span>
                        <span id="group_by_2" class="op" style="display:none;" >
                            <input type="checkbox" name="group_by_charge_agent" value="2" style="transform: scale(1.5)">&nbsp;
                            <span style="font-size: 13px">وكيل الشحن</span>
                        </span>
                    </div>
                </div>

                <div class="form-group rp col-sm-3 op" id="order_by" style="display:none;">
                    <label class="control-label">ترتيب حسب</label>
                    <div>
                        <span id="order_by_entry_date" class="op" style="display:none;" >
                            <input type="checkbox" name="order_by_entry_date" value="1" style="transform: scale(1.5)">&nbsp;
                            <span style="font-size: 13px">تاريخ الإدخال</span>
                        </span>
                    </div>
                </div>
				
				<div class="form-group rp col-sm-1 op" id="show_table" style="display:none;">
                    <label class="control-label">عرض البيانات</label>
                    <div>
						<input type="checkbox" name="show_table" id="ck_show_table" style="width: 15px; height: 15px;" checked>
                    </div>
                </div>
				
				<div class="form-group rp col-sm-1 op" id="show_page_total" style="display:none;">
                    <label class="control-label">عرض إجمالي الصفحة</label>
                    <div>
						<input type="checkbox" name="show_page_total" id="ck_show_page_total" style="width: 15px; height: 15px;">
                    </div>
                </div>
				
				<div class="form-group rp col-sm-1 op" id="show_report_total" style="display:none;">
                    <label class="control-label">عرض الإجمالي الكلي</label>
                    <div>
						<input type="checkbox" name="show_report_total" id="ck_show_report_total" style="width: 15px; height: 15px;">
                    </div>
                </div>

            </div>

        </fieldset>

        <div class="modal-footer">
            <button type="button" onclick="javascript:print_report();" class="btn btn-success">عرض التقرير<span class="glyphicon glyphicon-print"></span></button>
            <button type="button" onclick="javascript:clear_form();"  class="btn btn-default">تفريغ الحقول</button>
        </div>

    </div>

</div>


<?php
$scripts = <<<SCRIPT
<script>

    function clear_form(){
        clearForm_any('.row');
       $('#dp_collect_branch_id,#dp_sub_branch_id,#dp_charge_company_id,#dp_charge_agent_id,#dp_paid_type_id,#dp_branch_id,#dp_balance_type_id,#dp_meter_type_id,#dp_charge_type_id,#dp_is_cancel_id,#dp_charge_company_id_2,#dp_branch_id_2,#dp_agent_branch_id').select2('val','');
    }

    $('#dp_rep_id').change(function() {
        showOptions();
    });

    $(document).ready(function() {
		$('#dp_collect_branch_id,#dp_sub_branch_id,#dp_charge_company_id,#dp_charge_agent_id,#dp_paid_type_id,#dp_branch_id,#dp_balance_type_id,#dp_meter_type_id,#dp_charge_type_id,#dp_is_cancel_id,#dp_charge_company_id_2,#dp_branch_id_2,#dp_collect_branch_id_2,#dp_sub_branch_id_2,#dp_agent_branch_id').select2();
    });
    
    function showOptions(){
        var id=$('#dp_rep_id').val();
        switch(id) {
        case "0":
            $(".op").fadeOut();
            break;
        case "1":
            $(".op").fadeOut();
            $("#rep_type,#from_month_id,#to_month_id").fadeIn();
            break;
        case "2":
            $(".op").fadeOut();
            $("#rep_type,#from_date_id,#to_date_id").fadeIn();
            break;
        case "3":
            $(".op").fadeOut();
            $("#rep_type,#from_date_id,#to_date_id").fadeIn();
            break;
        case "4":
            $(".op").fadeOut();
            $("#rep_type,#from_date_id,#to_date_id").fadeIn();
            break;
        case "5":
            $(".op").fadeOut();
            $("#rep_type,#order_by,#order_by_entry_date,#from_date_id,#to_date_id,#meter_type_id,#sub_branch_id,#sub_no_id,#payment_amount_id,#charge_company_id,#charge_agent_id,#agent_branch_id").fadeIn();
            break;
        case "6":
            $(".op").fadeOut();
            $("#rep_type,#from_date_id,#to_date_id,#meter_type_id,#payment_amount_id,#sub_branch_id,#charge_company_id,#charge_agent_id").fadeIn();
            break;
        case "7":
            $(".op").fadeOut();
            $("#rep_type,#from_date_id,#to_date_id,#branch_id,#sub_no_id").fadeIn();
            break;
        case "8":
            $(".op").fadeOut();
            $("#rep_type,#from_date_id,#to_date_id,#charge_company_id,#balance_type_id,#group_by,#group_by_1").fadeIn();
            break;
        case "9":
            $(".op").fadeOut();
            $("#rep_type,#from_date_id,#to_date_id,#sub_branch_id").fadeIn();
            break;
        case "10":
            $(".op").fadeOut();
            $("#rep_type,#from_date_id,#to_date_id,#branch_id,#sub_no_id,#serial_id,#meter_type_id").fadeIn();
            break;
        case "11":
            $(".op").fadeOut();
            $("#rep_type,#from_date_id,#to_date_id,#charge_type_id,#charge_company_id,#is_cancel_id,#show_table,#show_page_total,#show_report_total").fadeIn();
            break;
        case "12":
            $(".op").fadeOut();
            $("#rep_type,#from_date_id,#to_date_id").fadeIn();
            break;
        case "13":
            $(".op").fadeOut();
            $("#rep_type,#from_date_id3,#to_date_id3,#charge_company_id_2,#branch_id_2").fadeIn();
            break;
        case "14":
            $(".op").fadeOut();
            $("#rep_type,#from_date_id2,#to_date_id2,#collect_branch_id_2,#sub_branch_id_2").fadeIn();
            break;
        case "15":
            $(".op").fadeOut();
            $("#rep_type,#from_date_id2,#to_date_id2,#collect_branch_id_2").fadeIn();
            break;
        case "16":
            $(".op").fadeOut();
            $("#rep_type,#from_date_id,#to_date_id,#branch_id,#charge_company_id,#meter_type_id,#charge_agent_id,#collect_branch_id").fadeIn();
            break;
        }
    }

    function getReportUrl(){
        var id=$('#dp_rep_id').val();
        var from_date = $('#txt_from_date_id').val();
        var to_date = $('#txt_to_date_id').val();
        var from_date2 = $('#txt_from_date_id2').val();
        var to_date2 = $('#txt_to_date_id2').val();
        var from_date3 = $('#txt_from_date_id3').val();
        var to_date3 = $('#txt_to_date_id3').val();
        var from_month = $('#txt_from_month_id').val();
        var to_month = $('#txt_to_month_id').val();
        var sub_no = $('#txt_sub_no_id').val();
        var serial = $('#txt_serial_id').val();
		var meter_type = have_no_val($('#dp_meter_type_id').val());
		var charge_type = have_no_val($('#dp_charge_type_id').val());
        var payment_amount = $('#txt_payment_amount_id').val();
        var collect_branch = have_no_val($('#dp_collect_branch_id').val());
        var sub_branch = have_no_val($('#dp_sub_branch_id').val());
        var collect_branch_2 = have_no_val($('#dp_collect_branch_id_2').val());
        var sub_branch_2 = have_no_val($('#dp_sub_branch_id_2').val());
        var is_cancel = have_no_val($('#dp_is_cancel_id').val());
        var branch_id = have_no_val($('#dp_branch_id').val());
        var agent_branch_id = have_no_val($('#dp_agent_branch_id').val());
        var charge_company = have_no_val($('#dp_charge_company_id').val());
        var charge_agent = have_no_val($('#dp_charge_agent_id').val());
        var paid_type = have_no_val($('#dp_paid_type_id').val());
        var balance_type = have_no_val($('#dp_balance_type_id').val());
        var status_2 = have_no_val($('input[name=status_2]:checked').val());
        var status_3 = have_no_val($('input[name=status_3]:checked').val());
        var branch_id_2 = have_no_val($('#dp_branch_id_2').val());
        var charge_company_2 = have_no_val($('#dp_charge_company_id_2').val());
        var group_by_charge_company = have_no_val($('input[name=group_by_charge_company]:checked').val());
        var group_by_charge_agent = have_no_val($('input[name=group_by_charge_agent]:checked').val());
        var order_by_entry_date = have_no_val($('input[name=order_by_entry_date]:checked').val());
        var rep_type = $('input[name=rep_type]:checked').val();
		
		var show_table;
            if ($('#ck_show_table').is(':checked')) {
                show_table='show';
            }
		var show_page_total;
            if ($('#ck_show_page_total').is(':checked')) {
                show_page_total='show';
            }
		var show_report_total;
            if ($('#ck_show_report_total').is(':checked')) {
                show_report_total='show';
            }

        var repUrl;
        switch(id) {
            case "1":
                repUrl = '{$report_url}&report_type='+rep_type+'&report=prepaid_agent&p_from_month='+from_month+'&p_to_month='+to_month+'';
                break;
            case "2":
                repUrl = '{$report_url}&report_type='+rep_type+'&report=daily_charges_holley&p_date_from='+from_date+'&p_date_to='+to_date+'';
                break;
            case "3":
                repUrl = '{$report_url}&report_type='+rep_type+'&report=external_charges&p_date_from='+from_date+'&p_date_to='+to_date+'';
                break;
            case "4":
                repUrl = '{$report_url}&report_type='+rep_type+'&report=palpay_charges&p_date_from='+from_date+'&p_date_to='+to_date+'';
                break;
            case "5":
                repUrl = '{$report_url}&report_type='+rep_type+'&report=dexcen_holley_sub_no&p_meter_type='+meter_type+'&p_sub_branch='+sub_branch+'&p_agent_branch='+agent_branch_id+'&p_charge_company='+charge_company+'&p_charge_agent='+charge_agent+'&p_sub_no='+sub_no+'&p_payment_amount='+payment_amount+'&p_order_by_entry_date='+order_by_entry_date+'&p_group_by_charge_company='+group_by_charge_company+'&p_group_by_charge_agent='+group_by_charge_agent+'&p_from_date='+from_date+'&p_to_date='+to_date+'';
                break;
            case "6":
                repUrl = '{$report_url}&report_type='+rep_type+'&report=dexcen_holley_charge_comp&p_meter_type='+meter_type+'&p_sub_branch='+sub_branch+'&p_charge_company='+charge_company+'&p_payment_amount='+payment_amount+'&p_from_date='+from_date+'&p_to_date='+to_date+'';
                break;
            case "7":
                repUrl = '{$report_url}&report_type='+rep_type+'&report=non_cash_transactions_item_20&p_branch='+branch_id+'&p_subscriber='+sub_no+'&p_from_date='+from_date+'&p_to_date='+to_date+'';
                break;
            case "8":
                repUrl = '{$report_url}&report_type='+rep_type+'&report=charge_comp_payment&p_charge_company='+charge_company+'&p_balance_type='+balance_type+'&p_group_by_charge_company='+group_by_charge_company+'&p_from_date='+from_date+'&p_to_date='+to_date+'';
                break;
            case "9":
                repUrl = '{$report_url}&report_type='+rep_type+'&report=dexcen_200_kw_emp&p_branch='+sub_branch+'&p_from_date='+from_date+'&p_to_date='+to_date+'';
                break;
            case "10":
                repUrl = '{$report_url}&report_type='+rep_type+'&report=dexcen_holley_charges&p_branch='+branch_id+'&p_sub_no='+sub_no+'&p_serial='+serial+'&p_meter_type='+meter_type+'&p_from_date='+from_date+'&p_to_date='+to_date+'';
                break;
            case "11":
                repUrl = '{$report_url2}&report_type='+rep_type+'&report=prepaid_balance'+rep_type+'&p_comp_ser='+charge_company+'&p_show_table='+show_table+'&p_show_page_total='+show_page_total+'&p_show_report_totals='+show_report_total+'&p_charge_type='+charge_type+'&p_is_cancel='+is_cancel+'&p_date_from='+from_date+'&p_date_to='+to_date+'';
                break;
            case "12":
                repUrl = '{$report_url}&report_type='+rep_type+'&report=charge_company_billing_account&p_date_from='+from_date+'&p_date_to='+to_date+'';
                break;
            case "13":
                repUrl = '{$report_url}&report_type='+rep_type+'&report=agent_charging_balances&p_date_from='+from_date3+'&p_date_to='+to_date3+'&p_branch='+branch_id_2+'&p_charge_company='+charge_company_2+'';
                break;
            case "14":
                repUrl = '{$report_url}&report_type='+rep_type+'&report=analyze_cash_receipts_before_transferring_receivables&p_gdsid='+collect_branch_2+'&p_gdsidcust='+sub_branch_2+'&p_date_from='+from_date2+'&p_date_to='+to_date2+'';
                break;
            case "15":
                repUrl = '{$report_url}&report_type='+rep_type+'&report=analyze_cash_receipts_after_transferring_receivables&p_gdsid='+collect_branch_2+'&p_date_from='+from_date2+'&p_date_to='+to_date2+'';
                break;
            case "16":
                repUrl = '{$report_url}&report_type='+rep_type+'&report=prepaid_daily_collections_'+rep_type+'&p_charge_agent='+charge_agent+'&p_branch='+branch_id+'&p_charge_branch='+collect_branch+'&p_comp='+charge_company+'&p_meter_type='+meter_type+'&p_from_date='+from_date+'&p_to_date='+to_date+'';
                break;
        }
        return repUrl;
    }

    function print_report(){
            var rep_url = getReportUrl();
            _showReport(rep_url);
    }

    // check if var have value or null //
    function have_no_val(v) {
        if(v == null || v == 0) {
            return v = '';
        }else {
            return v;
        }
    }
    

    function get_where(){
        var meter_type = have_no_val($('#dp_meter_type_id').val());
        var sub_branch = have_no_val($('#dp_sub_branch_id').val());
        var charge_company = have_no_val($('#dp_charge_company_id').val());
        
        $.ajax({
            type: 'POST',
            url: '{$get_where_url}',
            data: {meter_type: meter_type , sub_branch: sub_branch , charge_company: charge_company},
            datatype: 'json',
            success: function(data) {
                if(data.length == 0){
                  
                }else{
                    $('#dp_charge_agent_id option').remove();
                    $('#dp_charge_agent_id').append($('<option/>').attr("value", '0').text('---اختر---'));
                    $.each(data,function (i,option) {
                        $('#dp_charge_agent_id').append($('<option/>').attr("value", option.SER).text(option.SER +' : ' + option.NAME));
                    })
                    $("#dp_charge_agent_id").change();
                }
            }
        });

    }
    
    

</script>
SCRIPT;

sec_scripts($scripts);

?>

