<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mtaqia
 * Date: 2018-10-04
 * Time: 8:53 AM
 */
$report_url = base_url("JsperReport/showreport?sys=hr_attendance/assignment");
$admin_url= base_url("hr_attendance/assigning_work/index/1/hr_admin");
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
                    <label class="control-label">اسم التقرير</label>
                    <div>
                        <select name="rep_id" class="form-control" id="dp_rep_id">
                            <option value="0">_______________</option>
                            <option value="1">الساعات الإضافية</option>
                            <option value="2">متأخر في الدوام وليس له تكليف عمل</option>
                            <option value="3">تكاليف مكررة</option>
                            <option value="4">الوجبات</option>
                            <option value="5">إجمالي الوجبات المعتمدة</option>
                        </select>
                    </div>
                </div>
            </div>
        </fieldset>

        <fieldset>
            <legend>معايير البحث</legend>

            <div class="modal-body inline_form">

                <div class="form-group rp col-sm-1 op" id="from_month_id" style="display:none;">
                    <label class="control-label">من تاريخ</label>
                    <div>
                        <input type="text" data-type="date" name="from_month" data-date-format="DD/MM/YYYY" id="txt_from_month" class="form-control">
                    </div>
                </div>

                <div class="form-group rp col-sm-1 op" id="to_month_id" style="display:none;">
                    <label class="control-label">إلى تاريخ</label>
                    <div>
                        <input type="text" data-type="date" name="to_month" data-date-format="DD/MM/YYYY" id="txt_to_month" class="form-control">
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

                <div class="form-group rp col-sm-1 op" id="emp_type" style="display:none;">
                    <label class="control-label">نوع الموظفين </label>
                    <div>
                        <select name="emp_type" id="dp_emp_type" class="form-control sel2" >
                            <option selected value="1" >موظفي الشركة</option>
                            <option value="2" >فنيي مشروع الفاقد</option>
                        </select>
                    </div>
                </div>

                <?php if(HaveAccess($admin_url)) { ?>
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
                <?php } ?>

                <?php if(!HaveAccess($admin_url)) { ?>
                    <input type="hidden" class="branch" value="<?=$user_branch?>" />
                <?php } ?>

                <div class="form-group rp col-sm-1 op" id="adopt_id" style="display:none;">
                    <label class="control-label">الحالة</label>
                    <div>
                        <select name="adopt" class="form-control" id="dp_adopt">
                            <option value="">__________</option>
                            <?php foreach ($adopt as $row) : ?>
                                <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group rp col-sm-1 op" id="meal_id" style="display:none;">
                    <label class="control-label">وجبات الطعام</label>
                    <div>
                        <select name="meal" class="form-control" id="dp_meal">
                            <option value="">____________</option>
                            <?php foreach ($meal as $row) : ?>
                                <?php if( $row['CON_NO'] !=0 && $row['CON_NO'] !=5 ) {?>
                                <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                            <?php } endforeach; ?>
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

                <div class="form-group rp col-sm-3 op" id="group_by" style="display:none;">
                    <label class="control-label">تجميع حسب</label>
                    <div>
                        <?php if(HaveAccess($admin_url)) { ?>
                            <span style="display:none;" class="op" id="branch_group">
                                <input type="checkbox" name="group_by_branch" value="1" style="transform: scale(1.5)">&nbsp;
                                <span style="font-size: 13px"> المقر</span>
                            </span>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                        <?php } ?>
                            <span style="display:none;" class="op" id="emp_no_group">
                            <input type="checkbox" name="group_by_emp_no" value="2" style="transform: scale(1.5)">&nbsp;
                                <span style="font-size: 13px"> الموظف</span>
                            </span>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                            <span style="display:none;" class="op" id="status_group">
                                <input type="checkbox" name="group_by_status" value="3" style="transform: scale(1.5)">&nbsp;
                                <span style="font-size: 13px">حالة التكاليف</span>
                            </span>
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
        $('#dp_employees, #dp_adopt, #dp_meal').select2('val','');
    }

    $('#dp_rep_id').change(function() {
        showOptions();
    });

    $(document).ready(function() {
		$('#dp_employees, #dp_adopt, #dp_meal').select2();
    });

    function showOptions(){
        var id=$('#dp_rep_id').val();
        switch(id) {
        case "0":
            $(".op").fadeOut();
            break;
        case "1":
            $(".op").fadeOut();
            $("#employees_id,#branch_id,#rep_type,#from_month_id,#to_month_id,#adopt_id,#emp_type").fadeIn();
            break;
        case "2":
            $(".op").fadeOut();
            $("#employees_id,#branch_id,#rep_type,#from_month_id,#to_month_id,#group_by,#branch_group,#emp_no_group").fadeIn();
            break;
        case "3":
            $(".op").fadeOut();
            $("#employees_id,#branch_id,#rep_type,#from_month_id,#to_month_id,#group_by,#branch_group,#emp_no_group").fadeIn();
            break;
        case "4":
            $(".op").fadeOut();
            $("#employees_id,#branch_id,#rep_type,#from_month_id,#to_month_id,#adopt_id,#meal_id").fadeIn();
            break;
        case "5":
            $(".op").fadeOut();
            $("#employees_id,#branch_id,#rep_type,#from_month_id,#to_month_id,#emp_type,#group_by,#branch_group").fadeIn();
            break;
        }
    }

    function getReportUrl(){
        var id=$('#dp_rep_id').val();
        var from_month = $('#txt_from_month').val();
        var to_month = $('#txt_to_month').val();
        var rep_type = $('input[name=rep_type]:checked').val();
        var group_by_branch = have_no_val($('input[name=group_by_branch]:checked').val());
        var group_by_status = have_no_val($('input[name=group_by_status]:checked').val());
        var group_by_emp_no = have_no_val($('input[name=group_by_emp_no]:checked').val());
        var group_by = have_no_val($('input[name=group_by]:checked').val());
        var employees = $('#dp_employees').val();
        var adopt = $('#dp_adopt').val();
        var meal = $('#dp_meal').val();
        if($('#dp_meal').val() ==  3 || $('#dp_meal').val() ==  4) {
            var meal_5 = 5;
        }else { var meal_5 = ''; }
        
        var emp_type_1 = emp_type_2 = '';
        if($('#dp_emp_type').val() ==  1) { emp_type_1 = 1; }else { emp_type_2 = 2; }

        var branch = $('.branch').val();

        var repUrl;
        switch(id) {
            case "1":
                repUrl = '{$report_url}&report_type='+rep_type+'&report=over_time&p_date_from='+from_month+'&p_date_to='+to_month+'&p_emp_id='+employees+'&p_branch='+branch+'&p_emp_type_1='+emp_type_1+'&p_emp_type_2='+emp_type_2+'&p_group_by_branch='+group_by_branch+'&p_group_by_v_status='+group_by_status+'&p_group_by_emp_no='+group_by_emp_no+'&p_adopt='+adopt+'';
                break;
            case "2":
                repUrl = '{$report_url}&report_type='+rep_type+'&report=delay_with_out_assigning_work&p_date_from='+from_month+'&p_date_to='+to_month+'&p_emp_id='+employees+'&p_branch='+branch+'&p_group_by_branch='+group_by_branch+'&p_group_by_emp_no='+group_by_emp_no+'';
                break;
            case "3":
                repUrl = '{$report_url}&report_type='+rep_type+'&report=duplicate_assigning_work&p_date_from='+from_month+'&p_date_to='+to_month+'&p_emp_id='+employees+'&p_branch='+branch+'&p_group_by_branch='+group_by_branch+'&p_group_by_emp_no='+group_by_emp_no+'';
                break;
            case "4":
                if(meal != '') {
                    if(adopt != 40 ) {
                        if(confirm("لم تختر حالة اعتماد الشؤون الإدارية , هل تريد المتابعة ؟")) {
                            repUrl = '{$report_url}&report_type='+rep_type+'&report=meals_no&p_date_from='+from_month+'&p_date_to='+to_month+'&p_emp_id='+employees+'&p_adopt='+adopt+'&p_meal='+meal+'&p_meal_5='+meal_5+'&p_branch='+branch+'';
                        }
                    }else {
                        repUrl = '{$report_url}&report_type='+rep_type+'&report=meals_no&p_date_from='+from_month+'&p_date_to='+to_month+'&p_emp_id='+employees+'&p_adopt='+adopt+'&p_meal='+meal+'&p_meal_5='+meal_5+'&p_branch='+branch+'';
                    }
                }
                else {
                    $('#meal_id').addClass("rep_warning");
                    danger_msg('تحذير..','اختر نوع الوجبة');
                }
                break;
            case "5":
                repUrl = '{$report_url}&report_type='+rep_type+'&report=meals_details&p_date_from='+from_month+'&p_date_to='+to_month+'&p_emp_id='+employees+'&p_emp_type_1='+emp_type_1+'&p_emp_type_2='+emp_type_2+'&p_group_by_branch='+group_by_branch+'&p_branch='+branch+'';
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
