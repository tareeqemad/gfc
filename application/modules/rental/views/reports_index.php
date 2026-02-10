<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mtaqia
 * Date: 29/11/17
 * Time: 11:28 ص
 */

$MODULE_NAME= 'rental';
$TB_NAME= 'monthly_payments_det';

$report_url = base_url("JsperReport/showreport?sys=financial/rental");
$select_payments_url= ("$MODULE_NAME/monthly_cpayments/public_get_all_for_select");


?>
<?= AntiForgeryToken(); ?>
<style>
    .rep_warning { border: 2px solid red;}
</style>
<div class="row">

    <div class="toolbar">
        <div class="caption"><?= $title ?></div>
    </div>

    <div class="form-body">

    <fieldset>
        <legend>التقارير</legend>
        <div class="modal-body inline_form">
            <div class="form-group rp col-sm-2" id="branch_id">
                <label class="control-label">اسم التقرير</label>
                <div>
                    <select name="rep_id" class="form-control" id="dp_rep_id">
                        <option value="0">_______________</option>
                        <option value="1">1- أجور السيارات المستأجرة - إداري</option>
                        <option value="2">2- أجور السيارات المستأجرة - مالي</option>
                        <option value="3">3- أجور الوقت الإضافي</option>
                        <option value="4">4- تكلفة السيارات المستأجرة - إداري</option>
                        <option value="5">5- تكلفة السيارات المستأجرة - مالي</option>
                        <option value="6">6- استقطاع فاتورة الكهرباء</option>
                        <option value="7">7- الحضور اليومي</option>
                        <option value="8">8- إجماليات الحضور اليومي</option>
                        <option value="9">9- حســابات البنـــوك</option>
                        <option value="10">10- حــوالات البنـــوك</option>
                    </select>
                </div>
            </div>
        </div>
    </fieldset>

    <fieldset>
        <legend>معايير البحث</legend>

            <div class="modal-body inline_form">

                <div class="form-group rp col-sm-1 op" id="branch_id" style="display:none ;">
                    <label class="control-label">المقر</label>
                    <div>
                        <select name="branch_id" class="form-control" id="dp_branch_id">
                            <option value="">جميع المقرات</option>
                            <?php foreach ($branches as $row) : ?>
                                <option value="<?= $row['NO'] ?>"><?= $row['NO'].' : '.$row['NAME'];?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group rp col-sm-2 op" id="monthly_cpayments_id" style="display:none ;">
                    <label class="control-label">رقم المطالبة الشهرية</label>
                    <div>
                        <select name="monthly_cpayments_id" id="dp_monthly_cpayments_id" class="form-control" >
                            <?=modules::run($select_payments_url);?>
                        </select>
                    </div>
                </div>

                <div class="form-group rp col-sm-2 op" id="monthly_cpayments_id_from" style="display:none ;">
                    <label class="control-label">من رقم مطالبة</label>
                    <div>
                        <select name="monthly_cpayments_id_from" id="dp_monthly_cpayments_id_from" class="form-control" >
                            <?=modules::run($select_payments_url);?>
                        </select>
                    </div>
                </div>

                <div class="form-group rp col-sm-2 op" id="monthly_cpayments_id_to" style="display:none ;">
                    <label class="control-label">إلى رقم مطالبة</label>
                    <div>
                        <select name="monthly_cpayments_id_to" id="dp_monthly_cpayments_id_to" class="form-control" >
                            <?=modules::run($select_payments_url);?>
                        </select>
                    </div>
                </div>

                <div class="form-group rp col-sm-1 op" id="from_date" style="display:none ;">
                    <label class="control-label">من تاريخ</label>
                    <div>
                            <input type="text" name="from_date" id="txt_from_date" class="form-control">
                    </div>
                </div>

                <div class="form-group rp col-sm-1 op" id="to_date" style="display:none ;">
                    <label class="control-label">إلى تاريخ</label>
                    <div>
                        <input type="text" name="to_date" id="txt_to_date" class="form-control">
                    </div>
                </div>


                <div class="form-group rp col-sm-2 op" id="bank_id" style="display:none ;">
                    <label class="control-label">البنــــك</label>
                    <div>
                        <select name="bank_id" class="form-control" id="dp_bank_id">
                            <option value="">جميع البنوك</option>
                            <?php foreach ($bank as $row) : ?>
                                <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NO'].' : '.$row['CON_NAME'];?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group rp col-sm-2 op" id="bank_branch_id" style="display:none ;">
                    <label class="control-label">فــرع البنــك</label>
                    <div>
                        <select name="bank_branch_id" class="form-control" id="dp_bank_branch_id">
                            <option value="">جميع الفروع</option>
                            <?php foreach ($bank_branch as $row) : ?>
                                <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NO'].' : '.$row['CON_NAME'];?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group rp col-sm-1 op" id="rent_date" style="display:none ;">
                    <label class="control-label">الشهر</label>
                    <div>
                        <input type="text" name="rent_date" id="txt_rent_date" class="form-control">
                    </div>
                </div>

                <div class="form-group rp col-sm-2 op" id="deduction_bill_id" style="display:none ;">
                    <label class="control-label">الإستقطاع</label>
                    <div>
                        <select name="deduction_bill_id" class="form-control" id="dp_deduction_bill_id">
                            <option value="">جميع الإستقطاعات</option>
                            <?php foreach ($deduction_bill_id_cons as $row) : ?>
                                <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NO'].' : '.$row['CON_NAME'];?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group rp col-sm-2 op" id="contract_type_id" style="display:none ;">
                    <label class="control-label">نوع التعاقد</label>
                    <div>
                        <select name="contract_type_id" class="form-control" id="dp_contract_type_id">
                            <option value="">جميع التعاقدات</option>
                            <?php foreach ($contract_type as $row) : ?>
                                <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NO'].' : '.$row['CON_NAME'];?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div> </br></br>


                <div class="form-group rp col-sm-2 op" id="rep_type_id" style="display:none ;">
                    <label class="control-label">نوع التقرير</label>
                    <div>
                        <input type="radio"  name="rep_type_id" value="pdf" checked="checked">
                        <i class="fa fa-file-pdf-o" style="font-size:28px;color:#e2574c"></i>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="radio"  name="rep_type_id" value="xls">
                        <i class="fa fa-file-excel-o" style="font-size:28px;color:#1d7044"></i>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="radio"  name="rep_type_id" value="doc">
                        <i class="fa fa-file-word-o" style="font-size:28px;color:#2a5696"></i>
                    </div>
                </div>



            </div>

    </fieldset>

    <div class="modal-footer">
        <button type="button" onclick="javascript:print_report();" class="btn btn-success"><span class="glyphicon glyphicon-print"></span>عرض التقرير</button>
        <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>
    </div>

</div>

</div>

<?php
$scripts = <<<SCRIPT
<script>

    $('#dp_deduction_bill_id,#dp_bank_id,#dp_bank_branch_id,#dp_contract_type_id,#dp_monthly_cpayments_id,#dp_monthly_cpayments_id_from,#dp_monthly_cpayments_id_to').select2();

    function clear_form(){
        clearForm_any('.row');
        $('#dp_deduction_bill_id,#dp_bank_id,#dp_bank_branch_id,#dp_contract_type_id,#dp_monthly_cpayments_id,#dp_monthly_cpayments_id_from,#dp_monthly_cpayments_id_to').select2('val','');
    }

    $('#txt_rent_date').datetimepicker({
        format: 'MM/YYYY',
        minViewMode: "months",
        pickTime: false
    });

        $('#txt_from_date,#txt_to_date').datetimepicker({
        format: 'DD/MM/YYYY',
        minViewMode: "date",
        pickTime: false
    });

    $('#dp_rep_id').change(function() {
        showOptions();
    });

    function showOptions(){
        var id=$('#dp_rep_id').val();
        switch(id) {
        case "0":
            $(".op").fadeOut();
            break;
        case "1":
            $(".op").fadeOut();
            $("#rep_type_id,#monthly_cpayments_id").fadeIn();
            break;
        case "2":
            $(".op").fadeOut();
            $("#monthly_cpayments_id,#rep_type_id").fadeIn();
            break;
        case "3":
            $(".op").fadeOut();
            $("#monthly_cpayments_id,#rep_type_id").fadeIn();
            break;
        case "4":
            $(".op").fadeOut();
            $("#monthly_cpayments_id,#rep_type_id").fadeIn();
            break;
        case "5":
            $(".op").fadeOut();
            $("#monthly_cpayments_id_from,#monthly_cpayments_id_to,#rep_type_id").fadeIn();
            break;
        case "6":
            $(".op").fadeOut();
            $("#monthly_cpayments_id_from,#monthly_cpayments_id_to,#rep_type_id").fadeIn();
            break;
        case "7":
            $(".op").fadeOut();
            $("#branch_id,#rep_type_id,#from_date,#to_date").fadeIn();
            break;
        case "8":
            $(".op").fadeOut();
			$("#branch_id,#rep_type_id,#contract_type_id,#from_date,#to_date").fadeIn();
            break;
        case "9":
            $(".op").fadeOut();
			$("#monthly_cpayments_id_from,#monthly_cpayments_id_to,#bank_id,#bank_branch_id,#rep_type_id").fadeIn();
            break;
        case "10":
            $(".op").fadeOut();
			$("#monthly_cpayments_id_from,#monthly_cpayments_id_to,#bank_id,#bank_branch_id,#rep_type_id").fadeIn();
            break;
        }
    }

    function getReportUrl(){
        var id=$('#dp_rep_id').val();
        var branch = $('#dp_branch_id').val();
        var date = $('#txt_rent_date').val();
        var ded_type = $('#dp_deduction_bill_id').val();
        var bank = $('#dp_bank_id').val();
        var bank_branch = $('#dp_bank_branch_id').val();
        var contract_type = $('#dp_contract_type_id').val();
        var monthly_cpayments = $('#dp_monthly_cpayments_id').val();
        var monthly_cpayments_from = $('#dp_monthly_cpayments_id_from').val();
        var monthly_cpayments_to = $('#dp_monthly_cpayments_id_to').val();
        var from_date = $('#txt_from_date').val();
        var to_date = $('#txt_to_date').val();
        var rep_type = $('input[name=rep_type_id]:checked').val();
        var repUrl;

        switch(id) {
            case "1":
                repUrl = '{$report_url}&report_type='+rep_type+'&report=rented_car_hire&p_monthly_cpayments_id='+monthly_cpayments+'&p_rep_type='+rep_type+'';
                break;
            case "2":
                repUrl = '{$report_url}&report_type='+rep_type+'&report=rented_car_hire_fin&p_monthly_cpayments_id='+monthly_cpayments+'&p_rep_type='+rep_type+'';
                break;
            case "3":
                repUrl = '{$report_url}&report_type='+rep_type+'&report=extra_time_rented_car_hire&p_monthly_cpayments_id='+monthly_cpayments+'&p_rep_type='+rep_type+'';
                break;
            case "4":
                repUrl = '{$report_url}&report_type='+rep_type+'&report=cost_rented_cars&p_monthly_cpayments_id='+monthly_cpayments+'&p_rep_type='+rep_type+'';
                break;
            case "5":
                repUrl = '{$report_url}&report_type='+rep_type+'&report=cost_rented_cars_fin&p_monthly_cpayments_id_from='+monthly_cpayments_from+'&p_monthly_cpayments_id_to='+monthly_cpayments_to+'&p_rep_type='+rep_type+'';
                break;
            case "6":
                repUrl = '{$report_url}&report_type='+rep_type+'&report=deductions_total&p_monthly_cpayments_id_from='+monthly_cpayments_from+'&p_monthly_cpayments_id_to='+monthly_cpayments_to+'&p_rep_type='+rep_type+'';
                break;
            case "7":
                repUrl = '{$report_url}&report_type='+rep_type+'&report=drivers_attendance&p_branch='+branch+'&p_date_from='+from_date+'&p_date_to='+to_date+'&p_rep_type='+rep_type+'';
                break;
            case "8":
                repUrl = '{$report_url}&report_type='+rep_type+'&report=drivers_attendance_total&p_branch='+branch+'&p_contract_type='+contract_type+'&p_date_from='+from_date+'&p_date_to='+to_date+'&p_rep_type='+rep_type+'';
                break;
            case "9":
                repUrl = '{$report_url}&report_type='+rep_type+'&report=bank_accounts&p_bank='+bank+'&p_bank_branch='+bank_branch+'&p_monthly_cpayments_id_from='+monthly_cpayments_from+'&p_monthly_cpayments_id_to='+monthly_cpayments_to+'&p_rep_type='+rep_type+'';
                break;
            case "10":
                repUrl = '{$report_url}&report_type='+rep_type+'&report=bank_transfer&p_bank='+bank+'&p_bank_branch='+bank_branch+'&p_monthly_cpayments_id_from='+monthly_cpayments_from+'&p_monthly_cpayments_id_to='+monthly_cpayments_to+'&p_rep_type='+rep_type+'';
                break;
        }
        return repUrl;
    }

    function print_report(){
        if($('#dp_rep_id').val()==0){
            $('#dp_rep_id').addClass("rep_warning");
            danger_msg('تنبيه ..','اختر التقرير');
        }else{
                $('#dp_rep_id,#txt_rent_date').removeClass("rep_warning");
                var rep_url = getReportUrl();
                _showReport(rep_url);
        }
    }

</script>
SCRIPT;

sec_scripts($scripts);

?>
