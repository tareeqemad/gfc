<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mtaqia
 * Date: 2018-11-14
 * Time: 12:12 PM
 */
$report_url = base_url("JsperReport/showreport?sys=financial");
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
                <div class="form-group rp col-sm-2" id="rep_id">
                    <label class="control-label">التقرير</label>
                    <div>
                        <select name="rep_id" class="form-control" id="dp_rep_id">
                            <option value="0">_______________</option>
                            <option value="1">1- حركة النقدية الواردة والصادرة</option>
                            <option value="2">2- حسابات ليس لها مركز مالي</option>
                            <option value="3">3- حسابات غير متبعة</option>
                            <option value="4">4- قيود غير متزنة</option>
                        </select>
                    </div>
                </div>
            </div>
        </fieldset>

        <fieldset>
            <legend>معايير البحث</legend>

            <div class="modal-body inline_form">

                <div class="form-group rp col-sm-1 op" id="from_date" style="display:none ;">
                    <label class="control-label">من تاريخ</label>
                    <div>
                        <input type="text" data-type="date" name="from_date" data-date-format="DD/MM/YYYY" id="txt_from_date" class="form-control">
                    </div>
                </div>

                <div class="form-group rp col-sm-1 op" id="to_date" style="display:none ;">
                    <label class="control-label">إلى تاريخ</label>
                    <div>
                        <input type="text" data-type="date" name="to_date" data-date-format="DD/MM/YYYY" id="txt_to_date" class="form-control">

                    </div>
                </div>

                <br/><br/>

                <div class="form-group rp col-sm-2 op" id="rep_type" style="display:none ;">
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
            <button type="button" onclick="javascript:print_report();" class="btn btn-success">عرض التقرير<span class="glyphicon glyphicon-print"></span></button>
            <button type="button" onclick="javascript:clear_form();"  class="btn btn-default">إفراغ الحقول</button>
        </div>

    </div>

</div>

<?php
$scripts = <<<SCRIPT
<script>


    function clear_form(){
        clearForm_any('.row');
    }


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
            $("#to_date,#from_date,#rep_type").fadeIn();
            break;
        case "2":
            $(".op").fadeOut();
            $("#rep_type").fadeIn();
            break;
        case "3":
            $(".op").fadeOut();
            $("#rep_type").fadeIn();
            break;
        case "4":
            $(".op").fadeOut();
            $("#rep_type").fadeIn();
            break;
        }
    }

    function getReportUrl(){
        var id=$('#dp_rep_id').val();
        var from_date = $('#txt_from_date').val();
        var to_date = $('#txt_to_date').val();
        var rep_type = $('input[name=rep_type_id]:checked').val();
        var prev_day = AddDays(from_date,-1);

        var repUrl;
        switch(id) {
            case "1":
                repUrl = '{$report_url}/financial&report_type='+rep_type+'&report=cash_movement_inbound_outbound&p_date_from='+from_date+'&p_date_to='+to_date+'&p_date_prev_from='+prev_day+'';
                break;
            case "2":
                repUrl = '{$report_url}/accounts&report_type='+rep_type+'&report=accounts_with_no_financial_position';
                break;
            case "3":
                repUrl = '{$report_url}/accounts&report_type='+rep_type+'&report=accounts_not_followed';
                break;
            case "4":
                repUrl = '{$report_url}/accounts&report_type='+rep_type+'&report=unbalanced_constraints';
                break;
        }
        return repUrl;
    }


    function print_report(){
            var rep_url = getReportUrl();
            _showReport(rep_url);
    }


</script>
SCRIPT;

sec_scripts($scripts);

?>
