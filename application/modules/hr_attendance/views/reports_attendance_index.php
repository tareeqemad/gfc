<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mtaqia
 * Date: 2018-10-11
 * Time: 9:30 AM
 */
$report_url = base_url("JsperReport/showreport?sys=hr_attendance/attendance_and_departure");
$admin_url= base_url("hr_attendance/clock_data/index/1/hr_admin");

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
                            <option value="1">1- لديه بصمة حضور بدون خروج</option>
                            <option value="2">2- له بصمة انصراف وليس له حضور</option>
                            <option value="3">3- أيام الغياب</option>
                            <option value="4">4- البصمات المدخلة يدوياً</option>
                            <option value="5">5- حضور متأخر أو بدون بصمة حضور</option>
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

                <div class="form-group rp col-md-2 op" id="w_no_admin" style="display:none;">
                    <label class="control-label">المسمى الوظيفي</label>
                    <select name="w_no_admin[]" id="dp_w_no_admin" class="form-control " maxlength = "4" multiple>
                        <option value="">_________</option>
                        <?php foreach ($w_no_admin_cons as $row) : ?>
                            <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>
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

                <div class="form-group rp col-sm-1 op" id="the_month" style="display:none;">
                    <label class="control-label">الشهر</label>
                    <div>
                        <input type="text" data-type="date" name="the_month" data-date-format="YYYYMM"  value="<?= date('Ym') ?>" id="txt_the_month" class="form-control">
                    </div>
                </div>

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
                <!--
                                <div class="form-group rp col-sm-2 op" id="group_by" style="display:none;">
                                    <label class="control-label">تجميع حسب</label>
                                    <div>
                                        <input type="radio"  name="group_by" value="">
                                        <i class="fa fa-remove" style="font-size:28px;color:#2a5696"></i><span style="font-size: 13px"> بدون</span>
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="radio"  name="group_by" value="1">
                                        <i class="fa fa-building-o" style="font-size:28px;color:#e2574c"></i><span style="font-size: 13px"> المقر</span>
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="radio"  name="group_by" value="2">
                                        <i class="fa fa-user" style="font-size:30px;color:#1d7044"></i><span style="font-size: 13px"> الموظف</span>
                                    </div>
                                </div>
                -->
                <div class="form-group rp col-sm-2 op" id="group_by" style="display:none;">
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
                    </div>
                </div>

                <div class="form-group rp col-sm-2 op" id="exit_type" style="display:none;">
                    <div>
                        <input type="radio"  name="exit_type" value="0" checked="checked">
                        <label class="control-label">الجميع</label>

                        <input type="radio"  name="exit_type" value="1">
                        <label class="control-label">ليس له اذن</label>

                        <input type="radio"  name="exit_type" value="2">
                        <label class="control-label">له اذن</label>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                    </div>
                </div>

                <div class="form-group rp col-sm-2 op" id="view_type" style="display:none;">
                    <label class="control-label">عرض</label>
                    <div>
                        <input type="radio"  name="view_type_id" value="1" checked="checked">
                        <span style="font-size: 13px"> تفاصيل</span>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="radio"  name="view_type_id" value="2">
                        <span style="font-size: 13px"> إجمالي</span>
                        &nbsp;&nbsp;&nbsp;&nbsp;
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
        $('#dp_employees, #dp_adopt ,#dp_w_no_admin').select2('val','');
    }
    
    $('#txt_the_month').datetimepicker({
        format: 'MM/YYYY',
        minViewMode: "months",
        pickTime: false
    });
    

    $('#dp_rep_id').change(function() {
        showOptions();
    });

    $(document).ready(function() {
		$('#dp_employees, #dp_adopt ,#dp_w_no_admin').select2();
    });

    $("#dp_w_no_admin").change(function () {
        if($("#dp_w_no_admin option:selected").length > 5) {
            danger_msg(' تحذير..','الرجاء تحديد 6 مسميات وظيفية كحد أقصى');
        }
    });

    function showOptions(){
        var id=$('#dp_rep_id').val();
        switch(id) {
        case "0":
            $(".op").fadeOut();
            break;
            
        case "1":
            $(".op").fadeOut();
            $("#employees_id,#branch_id,#rep_type,#from_month_id,#to_month_id,#group_by,#branch_group,#emp_no_group,#w_no_admin,#view_type").fadeIn();
            break;
            
        case "2":
            $(".op").fadeOut();
            $("#branch_id,#rep_type,#from_month_id,#to_month_id,#group_by,#branch_group,#w_no_admin,#exit_type").fadeIn();
            break;
            
        case "3":
            $(".op").fadeOut();
            $("#employees_id,#branch_id,#rep_type,#from_month_id,#to_month_id,#group_by,#emp_no_group,#branch_group,#w_no_admin,#view_type").fadeIn();
            break;
            
        case "4":
            $(".op").fadeOut();
            $("#from_month_id,#employees_id,#branch_id,#rep_type,#to_month_id,#w_no_admin").fadeIn();
            break;
            
        case "5":
            $(".op").fadeOut();
            $("#employees_id,#branch_id,#rep_type,#w_no_admin,#from_month_id,#to_month_id").fadeIn();
            break;
        }
    }

    function getReportUrl(){
        var id=$('#dp_rep_id').val();
        var from_month = $('#txt_from_month').val();
        var to_month = $('#txt_to_month').val();
        var rep_type = $('input[name=rep_type]:checked').val();
        var exit_type = $('input[name=exit_type]:checked').val();
        var group_by_status = have_no_val($('input[name=group_by_status]:checked').val());
        var group_by_emp_no = have_no_val($('input[name=group_by_emp_no]:checked').val());
        var group_by_branch = have_no_val($('input[name=group_by_branch]:checked').val());
        var group_by_branch = have_no_val($('input[name=group_by_branch]:checked').val());
        var group_by = have_no_val($('input[name=group_by]:checked').val());
        var employees = $('#dp_employees').val();
        var adopt = $('#dp_adopt').val();
        var branch = $('.branch').val();
        var the_month = $('#txt_the_month').val();
        var w_no_admin = $('#dp_w_no_admin').val();
        var view_type = $('input[name=view_type_id]:checked').val();

        var op_1, op_2;
        if (view_type == 1){
            op_1 = 1; op_2 = '';
        }else if (view_type == 2){
            op_1 = ''; op_2 = 1;  
            $('input[name=group_by_emp_no][value=2]').attr('checked', true); 
            group_by_emp_no = 2;
        }
        
        
        var ex1, ex2;
        if (exit_type  == 1 ){
            ex1 = 1; ex2 = '';
        }else if (exit_type == 2){
            ex1 = ''; ex2 = 1;
        }else {ex1 = ''; ex2 = '';}

        var repUrl;
        switch(id) {
            case "1":
                repUrl = '{$report_url}&report_type='+rep_type+'&report=Fingerprint_attendance_without_exit&p_date_from='+from_month+'&p_date_to='+to_month+'&p_emp_id='+employees+'&p_branch='+branch+'&p_group_by_branch='+group_by_branch+'&p_group_by_emp_no='+group_by_emp_no+''+'&p_show_details='+op_1+'&p_show_totals='+op_2+'&p_job_1='+have_no_val2(0,w_no_admin)+'&p_job_2='+have_no_val2(1,w_no_admin)+'&p_job_3='+have_no_val2(2,w_no_admin)+'&p_job_4='+have_no_val2(3,w_no_admin)+'&p_job_5='+have_no_val2(4,w_no_admin)+'&p_job_6='+have_no_val2(5,w_no_admin);
                break;
                
            case "2":
                if(from_month != '' && to_month != '') {
                    $('#txt_from_month').removeClass("rep_warning");
                    $('#txt_to_month').removeClass("rep_warning");
                    repUrl = '{$report_url}&report_type='+rep_type+'&report=has_leave_fingerprint_no_attend&p_date_from='+from_month+'&p_date_to='+to_month+'&p_branch_id='+branch+'&p_has_exit_perm='+ex2+'&p_no_exit_perm='+ex1+'&p_group_by_branch='+group_by_branch+''+'&p_job_1='+have_no_val2(0,w_no_admin)+'&p_job_2='+have_no_val2(1,w_no_admin)+'&p_job_3='+have_no_val2(2,w_no_admin)+'&p_job_4='+have_no_val2(3,w_no_admin)+'&p_job_5='+have_no_val2(4,w_no_admin)+'&p_job_6='+have_no_val2(5,w_no_admin);
                }else {
                    $('#txt_from_month').addClass("rep_warning");
                    $('#txt_to_month').addClass("rep_warning");
                    danger_msg('تحذير..','ادخل التاريخ');
                }
                break;
                
            case "3":
                if(from_month != '' && to_month != '') {
                    $('#txt_from_month').removeClass("rep_warning");
                    $('#txt_to_month').removeClass("rep_warning");
                    repUrl = '{$report_url}&report_type='+rep_type+'&report=absence_days&p_date_from='+from_month+'&p_date_to='+to_month+'&p_emp_id='+employees+'&p_branch='+branch+'&p_group_by_branch='+group_by_branch+'&p_group_by_emp_no='+group_by_emp_no+''+'&p_job_1='+have_no_val2(0,w_no_admin)+'&p_job_2='+have_no_val2(1,w_no_admin)+'&p_job_3='+have_no_val2(2,w_no_admin)+'&p_job_4='+have_no_val2(3,w_no_admin)+'&p_job_5='+have_no_val2(4,w_no_admin)+'&p_job_6='+have_no_val2(5,w_no_admin)+'&p_show_details='+op_1+'&p_show_totals='+op_2;
                }else {
                    $('#txt_from_month').addClass("rep_warning");
                    $('#txt_to_month').addClass("rep_warning");
                    danger_msg('تحذير..','ادخل التاريخ');
                }
                break;
                
            case "4":
                if(from_month != '' && to_month != '') {
                    $('#txt_from_month').removeClass("rep_warning");
                    $('#txt_to_month').removeClass("rep_warning");
                    repUrl = '{$report_url}&report_type='+rep_type+'&report=manually_insert_fingerprints&p_date_from='+from_month+'&p_date_to='+to_month+'&p_branch='+branch+'&p_emp_no='+employees+'&p_job_1='+have_no_val2(0,w_no_admin)+'&p_job_2='+have_no_val2(1,w_no_admin)+'&p_job_3='+have_no_val2(2,w_no_admin)+'&p_job_4='+have_no_val2(3,w_no_admin)+'&p_job_5='+have_no_val2(4,w_no_admin)+'&p_job_6='+have_no_val2(5,w_no_admin);
                }else {
                    $('#txt_from_month').addClass("rep_warning");
                    $('#txt_to_month').addClass("rep_warning");
                    danger_msg('تحذير..','ادخل التاريخ');
                }
                break;
                
            case "5":
                if(the_month != '') {
                    $('#txt_the_month').removeClass("rep_warning");
                    repUrl = '{$report_url}&report_type='+rep_type+'&report=late_attend_without_fp&p_branch='+branch+'&p_emp_no='+employees+'&p_job_1='+have_no_val2(0,w_no_admin)+'&p_job_2='+have_no_val2(1,w_no_admin)+'&p_job_3='+have_no_val2(2,w_no_admin)+'&p_job_4='+have_no_val2(3,w_no_admin)+'&p_job_5='+have_no_val2(4,w_no_admin)+'&p_job_6='+have_no_val2(5,w_no_admin)+'&p_date_from='+from_month+'&p_date_to='+to_month;
                }else {
                    $('#txt_the_month').addClass("rep_warning");
                    danger_msg('تحذير..','ادخل الشهر');
                }
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

    function have_no_val2(v,s) {
        if( s != undefined) {
            var v2 = s[v];
            if(v2 == 0 || v2 == undefined) {
            return v2 = '';
            }else {
            return v2;
            }
        }else{
            return v = '';                        
        }
    }
    
</script>
SCRIPT;

sec_scripts($scripts);

?>