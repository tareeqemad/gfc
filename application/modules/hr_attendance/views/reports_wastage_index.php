<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mtaqia
 * Date: 2021-12-20
 * Time: 9:59 AM
 */
$report_url = base_url("JsperReport/showreport?sys=hr_attendance/wastage");

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
                            <option value="1">1- حضور وانصراف فنيي مشروع الفاقد</option>
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
                        <input type="text" data-type="date" name="from_date" data-date-format="DD/MM/YYYY" id="txt_from_date" class="form-control">
                    </div>
                </div>

                <div class="form-group rp col-sm-1 op" id="to_date_id" style="display:none;">
                    <label class="control-label">إلى تاريخ</label>
                    <div>
                        <input type="text" data-type="date" name="to_date" data-date-format="DD/MM/YYYY" id="txt_to_date" class="form-control">
                    </div>
                </div>

                <div class="form-group rp col-sm-2 op" id="employees_id" style="display:none;">
                    <label class="control-label">الموظف</label>
                    <div>
                        <select name="employees" class="form-control" id="dp_employees">
                            <option value="">جميع الموظفين</option>
                            <?php foreach ($all_employees as $row) : ?>
                                <option data-dept="<?= $row['NO'] ?>"
                                        value="<?= $row['NO'] ?>"><?= $row['NO'].' : '.$row['NAME'];?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group rp col-sm-1 op" id="branch_id" style="display:none;">
                    <label class="control-label">المقر</label>
                    <div>
                        <select name="branch" class="form-control branch" id="dp_branch">
                            <option value="">جميع المقرات</option>
                            <?php foreach ($branches as $row) : ?>
                                <option value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
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

                <div class="form-group rp col-sm-3 op" id="itemshow" style="display:none;">
                    <label class="control-label">عـــرض</label>
                    <div>
                            <span>
                            <input type="checkbox" name="detailsshow" value="1" style="transform: scale(1.5)">&nbsp;
                                <span style="font-size: 13px">التفاصيل</span>
                            </span>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <span>
                                <input type="checkbox" name="totalsshow" value="1" style="transform: scale(1.5)">&nbsp;
                                <span style="font-size: 13px">إجماليات</span>
                            </span>&nbsp;&nbsp;&nbsp;&nbsp;
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
        $('#dp_employees').select2('val','');
    }

    $('#dp_rep_id').change(function() {
        showOptions();
    });

    $(document).ready(function() {
		$('#dp_employees').select2();
    });

    function showOptions(){
        var id=$('#dp_rep_id').val();
        switch(id) {
        case "0":
            $(".op").fadeOut();
            break;
        case "1":
            $(".op").fadeOut();
            $("#employees_id,#branch_id,#rep_type,#from_date_id,#to_date_id,#itemshow").fadeIn();
            break;

        }
    }

    function getReportUrl(){
        var id=$('#dp_rep_id').val();
        var from_date= $('#txt_from_date').val();
        var to_date = $('#txt_to_date').val();
        var emp_no = $('#dp_employees').val();
        var branch = $('.branch').val();
        var rep_type = $('input[name=rep_type]:checked').val();
        var detailsshow = have_no_val($('input[name=detailsshow]:checked').val());
        var totalsshow = have_no_val($('input[name=totalsshow]:checked').val());
        
        if(detailsshow != 1) { detailsshow = 2;}

        var repUrl;
        switch(id) {
            case "1":
                repUrl = '{$report_url}&report_type='+rep_type+'&report=att_wastage_'+rep_type+'&p_date_from='+from_date+'&p_date_to='+to_date+'&p_details='+detailsshow+'&p_totals='+totalsshow+'&p_emp_id='+emp_no+'&p_branch='+branch+'';
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
