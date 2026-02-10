<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mtaqia
 * Date: 2021-12-27
 * Time: 10:05 PM
 */
$report_url = base_url("JsperReport/showreport?sys=financial/prepaid_charges");

$MODULE_NAME= 'financial';
$TABLE_NAME= 'prepaid_agents_reports2';

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
                            <option value="1">1- حركات palpay وموجودة بقاعدة بيانات holley</option>
                            <option value="2">2- حركات palpay وغير موجودة بقاعدة بيانات holley</option>
                            <optgroup label="فروقات نظام المحطات ونظام الهولي">
                                <option value="3">3- حركات صحيحة ومثبتة ببرنامج المحطات وبرنامج الهولي</option>
                                <option value="4">4- حركات مثبتة ببرنامج المحطات وغير موجودة ببرنامج الهولي</option>
                                <option value="5">5- حركات مثبتة ببرنامج الهولي وغير موجودة ببرنامج المحطات</option>
                                <option value="6">6- حركات ببرنامج المحطات لم تتم وتحتاج إلى معالجة</option>
                            </optgroup>
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
                        <!-- <input type="radio"  name="rep_type" value="doc">
                        <i class="fa fa-file-word-o" style="font-size:28px;color:#2a5696"></i> -->
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
       $('').select2('val','');
    }

    $('#dp_rep_id').change(function() {
        showOptions();
    });

    $(document).ready(function() {
		$('#dp_rep_id').select2();
    });
    
    function showOptions(){
        var id=$('#dp_rep_id').val();
        switch(id) {
        case "0":
            $(".op").fadeOut();
            break;
        case "1":
            $(".op").fadeOut();
            $("#rep_type,#from_date_id,#to_date_id").fadeIn();
            break;
        case "2":
            $(".op").fadeOut();
            $("#rep_type,#from_date_id,#to_date_id").fadeIn();
            break;
        case "3":
            $(".op").fadeOut();
            $("#rep_type,#from_date_id,#to_date_id,#charge_company_id").fadeIn();
            break;
        case "4":
            $(".op").fadeOut();
            $("#rep_type,#from_date_id,#to_date_id,#charge_company_id").fadeIn();
            break;
        case "5":
            $(".op").fadeOut();
            $("#rep_type,#from_date_id,#to_date_id,#charge_company_id").fadeIn();
            break;
        case "6":
            $(".op").fadeOut();
            $("#rep_type,#from_date_id,#to_date_id,#charge_company_id").fadeIn();
            break;
        }
    }

    function getReportUrl(){
        var id=$('#dp_rep_id').val();
        var from_date = $('#txt_from_date_id').val();
        var to_date = $('#txt_to_date_id').val();
        var charge_company = have_no_val($('#dp_charge_company_id').val());
        var rep_type = $('input[name=rep_type]:checked').val();

        var repUrl;
        switch(id) {
            case "1":
                repUrl = '{$report_url}&report_type='+rep_type+'&report=in_palpay_in_holley_'+rep_type+'&p_from_date='+from_date+'&p_to_date='+to_date+'';
                break;
            case "2":
                repUrl = '{$report_url}&report_type='+rep_type+'&report=in_palpay_not_in_holley_'+rep_type+'&p_from_date='+from_date+'&p_to_date='+to_date+'';
                break;
            case "3":
                repUrl = '{$report_url}&report_type='+rep_type+'&report=holley_stations_transactions_exist_'+rep_type+'&p_company_id='+charge_company+'&p_from_date='+from_date+'&p_to_date='+to_date+'';
                break;
            case "4":
                repUrl = '{$report_url}&report_type='+rep_type+'&report=stations_transactions_exist_'+rep_type+'&p_company_id='+charge_company+'&p_from_date='+from_date+'&p_to_date='+to_date+'';
                break;
            case "5":
                repUrl = '{$report_url}&report_type='+rep_type+'&report=holley_transactions_exist_'+rep_type+'&p_company_id='+charge_company+'&p_from_date='+from_date+'&p_to_date='+to_date+'';
                break;
            case "6":
                repUrl = '{$report_url}&report_type='+rep_type+'&report=company_uncompleted_trans&p_company_id='+charge_company+'&p_from_date='+from_date+'&p_to_date='+to_date+'';
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

  
</script>
SCRIPT;

sec_scripts($scripts);

?>

