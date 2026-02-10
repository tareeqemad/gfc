<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mtaqia
 * Date: 24/01/18
 * Time: 11:31 ص
 */
$report_url = base_url("JsperReport/showreport?sys=technical/loadFlow");
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
            <legend>Report</legend>
            <div class="modal-body inline_form">
                <div class="form-group rp col-sm-2" id="branch_id">
                    <label class="control-label">Report Name</label>
                    <div>
                        <select name="rep_id" class="form-control" id="dp_rep_id">

                            <option value="0">_______________</option>

                            <optgroup label="Purchased Energy">
                                <option value="1">Israel Feeders</option>
                                <option value="2">Power Plant Feeders</option>
                                <option value="3">Egyptian Feeders</option>
                            </optgroup>
                            <option value="0">_______________</option>
                            <option value="4" style="font-weight: bold;">Sold Energy</option>
                            <option value="5" style="font-weight: bold;">Energy Losses</option>
                            <option value="0">_______________</option>
                            <option value="6" style="font-weight: bold;">Projects</option>
                            <option value="7" style="font-weight: bold;">Technical Efficiency & Development Projects</option>
                        </select>
                    </div>
                </div>
            </div>
        </fieldset>

        <fieldset>
            <legend>Search criteria</legend>

            <div class="modal-body inline_form">

                <div class="form-group rp col-sm-2 op" id="study_type" style="display:none ;">
                    <label class="control-label">Study Type</label>
                    <div>
                        <select name="study_type" id="op_study_type" class="form-control"   data-val-required="required"
                                data-val="true">
                            <option></option>
                            <?php foreach ($study_type as $row) : ?>
                                <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group rp col-sm-1 op" id="gov_area" style="display:none ;">
                    <label class="control-label">Gov - Area</label>
                    <div>
                        <select name="gov_area" id="op_gov_area" class="form-control"   data-val-required="required"
                                data-val="true">
                            <option></option>
                            <?php foreach ($branches as $row) : ?>
                                <option value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group rp col-sm-1 op" id="donation" style="display:none ;">
                    <label class="control-label">Donation Name</label>
                    <div>
                        <select name="donation" id="op_donation" class="form-control"   data-val-required="required"
                                data-val="true">
                            <option></option>
                            <?php foreach ($donation_name as $row) : ?>
                                <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>


                <div class="form-group rp col-sm-1 op" id="budget_year" style="display:none ;">
                    <label class="control-label">Budget Year</label>
                    <div>
                        <select name="budget_year" id="op_budget_year" class="form-control">
                            <option></option>
                            <?php for ($i = 2018 ; $i < 2029 ; $i++) : ?>
                                <option value="<?= $i ?>"><?= $i ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group rp col-sm-1 op" id="from_month" style="display:none ;">
                    <label class="control-label">From Month</label>
                    <div>
                        <input type="text" name="from_month" id="txt_from_month" class="form-control">
                    </div>
                </div>

                <div class="form-group rp col-sm-1 op" id="to_month" style="display:none ;">
                    <label class="control-label">To Month</label>
                    <div>
                        <input type="text" name="to_month" id="txt_to_month" class="form-control">
                    </div>
                </div>

                <div class="form-group rp col-sm-1 op" id="from_month_2" style="display:none ;">
                    <label class="control-label">From Month</label>
                    <div>
                        <input type="text" data-type="date" name="from_month_2" data-date-format="DD/MM/YYYY" id="txt_from_month_2" class="form-control">
                    </div>
                </div>

                <div class="form-group rp col-sm-1 op" id="to_month_2" style="display:none ;">
                    <label class="control-label">To Month</label>
                    <div>
                        <input type="text" data-type="date" name="to_month_2" data-date-format="DD/MM/YYYY" id="txt_to_month_2" class="form-control">

                    </div>
                </div>

                <div class="form-group rp col-sm-1 op" id="consumption_curves" style="display:none ;">
                    <label class="control-label">Consumption Curves</label>
                    <div>
                        <input type="checkbox" name="consumption_curves" id="ck_consumption_curves" style="width: 15px; height: 15px;">
                    </div>
                </div>

                <div class="form-group rp col-sm-1 op" id="show_table" style="display:none ;">
                    <label class="control-label">Show Table</label>
                    <div>
                        <input type="checkbox" name="show_table" id="ck_show_table" style="width: 15px; height: 15px;">
                    </div>
                </div>

                <br/><br/>

                <div class="form-group rp col-sm-2 op" id="rep_type" style="display:none ;">
                    <label class="control-label">Report Type</label>
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
            <button type="button" onclick="javascript:print_report();" class="btn btn-success">Show Report <span class="glyphicon glyphicon-print"></span></button>
            <button type="button" onclick="javascript:clear_form();"  class="btn btn-default">Clear</button>
        </div>

    </div>

</div>

<?php
$scripts = <<<SCRIPT
<script>

    function clear_form(){
        clearForm_any('.row');
    }

    $('#txt_from_month,#txt_to_month').datetimepicker({
        format: 'YYYYMM',
        minViewMode: "months",
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
            $("#consumption_curves,#show_table,#to_month,#from_month,#rep_type").fadeIn();
            break;
        case "2":
            $(".op").fadeOut();
			$("#consumption_curves,#show_table,#to_month,#from_month,#rep_type").fadeIn();
            break;
        case "3":
            $(".op").fadeOut();
			$("#consumption_curves,#show_table,#to_month,#from_month,#rep_type").fadeIn();
            break;
        case "4":
            $(".op").fadeOut();
			$("#to_month,#from_month,#rep_type").fadeIn();
            break;
        case "5":
            $(".op").fadeOut();
			$("#consumption_curves,#show_table,#to_month,#from_month,#rep_type").fadeIn();
            break;
        case "6":
            $(".op").fadeOut();
			$("#to_month_2,#from_month_2,#rep_type,#study_type,#gov_area,#donation,#budget_year").fadeIn();
            break;
        case "7":
            $(".op").fadeOut();
			$("#to_month_2,#from_month_2,#rep_type,#gov_area,#donation,#budget_year").fadeIn();
            break;
        }
    }

    function getReportUrl(){
        var id=$('#dp_rep_id').val();
        var from_month = $('#txt_from_month').val();
        var to_month = $('#txt_to_month').val();
        var from_month_2 = $('#txt_from_month_2').val();
        var to_month_2 = $('#txt_to_month_2').val();
        var rep_type = $('input[name=rep_type_id]:checked').val();
        var study_type = $('#op_study_type').val();
        var gov_area = $('#op_gov_area').val();
        var donation = $('#op_donation').val();
        var budget_year = $('#op_budget_year').val();

        var consumption_curves;
            if ($('#ck_consumption_curves').is(':checked')) {
                consumption_curves='show';
            }

        var show_table;
            if ($('#ck_show_table').is(':checked')) {
                show_table='show';
            }

        var repUrl;
        switch(id) {
            case "1":
                repUrl = '{$report_url}&report_type='+rep_type+'&report=israel_feeders&p_date_from='+from_month+'&p_date_to='+to_month+'&p_consumption_curves='+consumption_curves+'&p_show_table='+show_table+'&p_rep_type='+rep_type+'';
                break;
            case "2":
                repUrl = '{$report_url}&report_type='+rep_type+'&report=power_plant_feeders&p_date_from='+from_month+'&p_date_to='+to_month+'&p_consumption_curves='+consumption_curves+'&p_show_table='+show_table+'&p_rep_type='+rep_type+'';
                break;
            case "3":
                repUrl = '{$report_url}&report_type='+rep_type+'&report=egyptian_feeders&p_date_from='+from_month+'&p_date_to='+to_month+'&p_consumption_curves='+consumption_curves+'&p_show_table='+show_table+'&p_rep_type='+rep_type+'';
                break;
            case "4":
                repUrl = '{$report_url}&report_type='+rep_type+'&report=sold_energy&p_date_from='+from_month+'&p_date_to='+to_month+'&p_rep_type='+rep_type+'';
                break;
            case "5":
                repUrl = '{$report_url}&report_type='+rep_type+'&report=energy_losses&p_date_from='+from_month+'&p_date_to='+to_month+'&p_consumption_curves='+consumption_curves+'&p_show_table='+show_table+'&p_rep_type='+rep_type+'';
                break;
			case "6":
                repUrl = '{$report_url}&report_type='+rep_type+'&report=load_flow_measure_master&p_from_date='+from_month_2+'&p_to_date='+to_month_2+'&p_branch='+gov_area+'&p_study_type='+study_type+'&p_donation='+donation+'&p_budget_year='+budget_year+'&p_rep_type='+rep_type+'';
                break;
            case "7":
                repUrl = '{$report_url}&report_type='+rep_type+'&report=technical_efficiency_developement_p&p_from_date='+from_month_2+'&p_to_date='+to_month_2+'&p_branch='+gov_area+'&p_donation='+donation+'&p_budget_year='+budget_year+'';
                break;
        }
        return repUrl;
    }

    function data_validation(){
        if($('#dp_rep_id').val()!=0 &&
            ($('#txt_from_month').is(':visible') && ($('#txt_from_month').val()!='' && $('#txt_to_month').val()!='') || ($('#dp_rep_id').val()==6)) &&
            ($('#txt_from_month_2').is(':visible') && ($('#txt_from_month_2').val()!='' && $('#txt_to_month_2').val()!='') || ($('#dp_rep_id').val()!=6)) &&
            ($('#show_table').is(':visible') && ($('#ck_show_table').is(':checked') || $('#consumption_curves').is(':visible') && $('#ck_consumption_curves').is(':checked')) || ($('#dp_rep_id').val()==4) || ($('#dp_rep_id').val()==6)) &&
            ($('input[name=rep_type_id]:checked').val() != null)) {
                $('#dp_rep_id,#consumption_curves,#show_table,#txt_from_month,#txt_from_month_2,#txt_to_month,#txt_to_month_2,#consumption_curves,#show_table,#rep_type').removeClass("rep_warning");
	            return true;
        }else {
            if($('#dp_rep_id').val()==0){
                danger_msg('Warning ..','Select the report');
                $('#dp_rep_id').addClass("rep_warning");
            }else{ $('#dp_rep_id').removeClass("rep_warning"); }

            if(($('#txt_from_month').val()=='' && $('#txt_from_month').is(':visible')) || ($('#txt_to_month').val()=='' && $('#txt_to_month').is(':visible'))){
                danger_msg('Warning ..','Select Month');
                if($('#txt_from_month').val()==''){
                     $('#txt_from_month').addClass("rep_warning");
                    if($('#txt_to_month').val()==''){
                        $('#txt_to_month').addClass("rep_warning");
                    }
                }else if($('#txt_to_month').val()==''){
                    $('#txt_to_month').addClass("rep_warning");
                }
            }else{ $('#txt_from_month,#txt_to_month').removeClass("rep_warning"); }

            if(($('#txt_from_month_2').val()=='' && $('#txt_from_month_2').is(':visible')) || ($('#txt_to_month_2').val()=='' && $('#txt_to_month_2').is(':visible'))){
                danger_msg('Warning ..','Select Date');
                if($('#txt_from_month_2').val()==''){
                     $('#txt_from_month_2').addClass("rep_warning");
                    if($('#txt_to_month_2').val()==''){
                        $('#txt_to_month_2').addClass("rep_warning");
                    }
                }else if($('#txt_to_month_2').val()==''){
                    $('#txt_to_month_2').addClass("rep_warning");
                }
            }else{ $('#txt_from_month_2,#txt_to_month_2').removeClass("rep_warning"); return true; }

            if($('#show_table').is(':visible') && !$('#ck_consumption_curves').is(':checked') && !$('#ck_show_table').is(':checked')){
                $('#consumption_curves,#show_table').addClass("rep_warning");
                danger_msg('Warning ..','Select items that will appear in the report');
            }else { $('#consumption_curves,#show_table').removeClass("rep_warning");}

            if($('input[name=rep_type_id]:checked').val() == null){
                $('#rep_type').addClass("rep_warning");
                danger_msg('Warning ..','Select report type');
            }else { $('#rep_type').removeClass("rep_warning"); }

            return false;
        }
    }

    function print_report(){
        if(data_validation() ) {
            var rep_url = getReportUrl();
            _showReport(rep_url);
        }
    }


</script>
SCRIPT;

sec_scripts($scripts);

?>
