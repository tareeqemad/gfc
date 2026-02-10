<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mtaqia
 * Date: 2019-10-16
 * Time: 10:05 PM
 */
$report_url = base_url("JsperReport/showreport?sys=financial");
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
                <div class="form-group rp col-sm-2" id="rep_id">
                    <label class="control-label">اسم التقرير</label>
                    <div>
                        <select name="rep_id" class="form-control" id="dp_rep_id">
                            <option value="0">_______________</option>
                            <option value="1">1- مخصص الوقود</option>
                            <option value="2">2- الوقود</option>
                            <option value="3">3- الكوبونات المصروفة</option>
                            <option value="4">4- تحويل الوقود</option>
                            <option value="5">5- مصاريف سيارات وآليات الشركة</option>
							<option value="6">6- متابعة عداد المسافة</option>
							<option value="7">7- متابعة عداد الساعة</option>
                        </select>
                    </div>
                </div>
            </div>
        </fieldset>

        <fieldset>
            <legend>معايير البحث</legend>

            <div class="modal-body inline_form">

                <div class="form-group rp col-sm-1 op" id="from_file_id" style="display:none;">
                    <label class="control-label">من سيارة</label>
                    <div>
                        <input type="text"  name="from_file"  id="txt_from_file" class="form-control">
                    </div>
                </div>

                <div class="form-group rp col-sm-1 op" id="to_file_id" style="display:none;">
                    <label class="control-label">إلى سيارة</label>
                    <div>
                        <input type="text" name="to_file"  id="txt_to_file" class="form-control">
                    </div>
                </div>

                <div class="form-group rp col-sm-1 op" id="from_month_id" style="display:none ;">
                    <label class="control-label">الشهر</label>
                    <div>
                        <input style="display:none" type="text" name="date000" id="txt_date000" value="<?=date('Ym')?>" class="form-control">
						<input type="text" name="date" id="txt_date"  data-type="date" data-date-format="MM/YYYY" value="<?=date('m/Y' , strtotime('-1 month'))?>" class="form-control">
                    </div>
                </div>

                <div class="form-group rp col-sm-1 op" id="from_date_id" style="display:none;">
                    <label class="control-label">من تاريخ</label>
                    <div>
                        <input type="text" data-type="date" name="from_date" data-date-format="DD/MM/YYYY" id="txt_from_date" class="form-control">
                    </div>
                </div>

                <div class="form-group rp col-sm-1 op" id="to_date_id" style="display:none;">
                    <label class="control-label">إلى تاريخ</label>
                    <div>
                        <input type="text" data-type="date" name="to_date" data-date-format="DD/MM/YYYY" id="txt_to_date" class="form-control">
                    </div>
                </div>

                <div class="form-group rp col-sm-1 op" id="fuel_type_id" style="display:none;">
                    <label class="control-label">نوع الوقود</label>
                    <div>
                        <select type="text" name="fuel_type" id="dp_fuel_type" class="form-control">
                            <option></option>
                            <?php foreach ($fuel_type as $row) : ?>
                                <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group rp col-sm-2 op" id="branch_id" style="display:none;">
                    <label class="control-label">الفرع</label>
                    <div>
                        <select type="text" name="branch" id="dp_branch" class="form-control">
                            <option></option>
                            <?php foreach ($branches as $row) : ?>
                                <option value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
				
				 <div class="form-group rp col-sm-3 op" id="suppliers_id" style="display:none;">
                    <label class="control-label">المورد</label>
                    <div>
                        <select type="text" name="suppliers" id="dp_suppliers" class="form-control">
                            <option></option>
                            <?php foreach ($suppliers as $row) : ?>
                                <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
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
                        <input type="radio"  name="rep_type" value="doc">
                        <i class="fa fa-file-word-o" style="font-size:28px;color:#2a5696"></i>
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
            $("#rep_type,#branch_id").fadeIn();
            break;
        case "2":
            $(".op").fadeOut();
            $("#rep_type,#branch_id,#fuel_type_id,#from_date_id,#to_date_id,#suppliers_id").fadeIn();
            break;
        case "3":
            $(".op").fadeOut();
            $("#rep_type,#branch_id,#fuel_type_id,#from_date_id,#to_date_id,#from_file_id,#to_file_id").fadeIn();
            break;
        case "4":
            $(".op").fadeOut();
            $("#rep_type,#from_file_id,#to_file_id,#from_date_id,#to_date_id").fadeIn();
            break;
        case "5":
            $(".op").fadeOut();
            $("#rep_type,#from_file_id,#to_file_id").fadeIn();
            break;
		case "6":
            $(".op").fadeOut();
            $("#rep_type,#branch_id,#from_month_id").fadeIn();
            break;
        case "7":
            $(".op").fadeOut();
            $("#rep_type,#branch_id,#from_month_id").fadeIn();
            break; 
        }
    }

    function getReportUrl(){
        var id=$('#dp_rep_id').val();
        var from_date = $('#txt_from_date').val();
        var to_date = $('#txt_to_date').val();
        var from_file = $('#txt_from_file').val();
        var to_file = $('#txt_to_file').val();
        var month = $('#txt_date').val();
        var fuel_type = have_no_val($('#dp_fuel_type').val());
        var branch = have_no_val($('#dp_branch').val());
        var suppliers = have_no_val($('#dp_suppliers').val());
        var rep_type = $('input[name=rep_type]:checked').val();

        var repUrl;
        switch(id) {
            case "1":
                repUrl = '{$report_url}&report_type='+rep_type+'&report=cars_fuel_dedicated&BRANCH='+branch+'';
                break;
            case "2":
                repUrl = '{$report_url}&report_type='+rep_type+'&report=CARS_FUEL_CONSUMER&FUEL_TYPE='+fuel_type+'&BRANCH='+branch+'&p_suppliers='+suppliers+'&p_coupon_fuel_date_from='+from_date+'&p_coupon_fuel_date_to='+to_date+'';
                break;
            case "3":
                repUrl = '{$report_url}&report_type='+rep_type+'&report=MONTHLY_FUAL_CPOUPONS&FUEL_TYPE='+fuel_type+'&BRANCH='+branch+'&p_from_car_file_id='+from_file+'&p_to_car_file_id='+to_file+'&p_coupon_fuel_date_from='+from_date+'&p_coupon_fuel_date_to='+to_date+'';
                break;
            case "4":
                repUrl = '{$report_url}&report_type='+rep_type+'&report=cars_fuel_transfer&p_from_file_id='+from_file+'&p_to_file_id='+to_file+'&p_date_from='+from_date+'&p_date_to='+to_date+'';
                break;
            case "5":
                repUrl = '{$report_url}&report_type='+rep_type+'&report=coupon_expenses_car_detail&p_from_car_file_id='+from_file+'&p_to_car_file_id='+to_file+'';
                break;
			case "6":
                repUrl = '{$report_url}&report_type='+rep_type+'&report=speedo_meter&p_month='+month+'&p_branch='+branch+'';
                break;
            case "7":
                repUrl = '{$report_url}&report_type='+rep_type+'&report=Hour_Counter&p_month='+month+'&p_branch='+branch+'';
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
        if(v == null) {
            return v = '';
        }else {
            return v;
        }
    }

</script>
SCRIPT;

sec_scripts($scripts);

?>

