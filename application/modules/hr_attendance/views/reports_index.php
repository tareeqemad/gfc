<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mtaqia
 * Date: 2018-09-15
 * Time: 1:18 PM
 */
$report_url = base_url("JsperReport/showreport?sys=hr_attendance/vacation");
$admin_url= base_url("hr_attendance/vacation_request/index/1/hr_admin");
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
                            <option value="1">تفصيل إجازات الموظفين</option>
                            <option value="2">إجازات مكررة</option>
                            <option value="3">رصيد الإجازات سالب</option>
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

                <div class="form-group rp col-sm-1 op" id="year_id" style="display:none;">
                    <label class="control-label">السنة</label>
                    <div>
                        <input type="text" name="year" id="txt_year" value="<?=date('Y');?>" class="form-control">
                    </div>
                </div>

                <div class="form-group rp col-sm-1 op" id="vac_type_id" style="display:none;">
                    <label class="control-label">نوع الإجازة</label>
                    <div>
                        <select name="vac_type" class="form-control" id="dp_vac_type">
                            <option value="">الجميع</option>
                            <?php foreach($vac_type_cons as $row) :?>
                                <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
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
                <?php }else { ?>
                    <input type="hidden" class="branch" value="<?=$user_branch?>" />
                <?php } ?>

                <div class="form-group rp col-sm-1 op" id="adopt_id" style="display:none;">
                    <label class="control-label">الحالة</label>
                    <div>
                        <select name="adopt" class="form-control" id="dp_adopt">
                            <option value="99">قيد الإعتماد</option>
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
                        <input type="radio"  name="rep_type" value="html" checked="checked">
                        <i class="fa fa-file-text-o" style="font-size:28px;color:#070707;"></i>&nbsp;

                        <input type="radio"  name="rep_type" value="pdf">
                        <i class="fa fa-file-pdf-o" style="font-size:28px;color:#e2574c;"></i>
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
                <div class="form-group rp col-sm-3 op" id="group_by" style="display:none;">
                    <label class="control-label">تجميع حسب</label>
                    <div>
                        <?php if(HaveAccess($admin_url)) { ?>
                            <input type="checkbox" name="group_by_branch" value="1" style="transform: scale(1.5)">&nbsp;
                            <span style="font-size: 13px"> المقر</span>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                        <?php } ?>
                            <input type="checkbox" name="group_by_emp_no" value="2" style="transform: scale(1.5)">&nbsp;
                            <span style="font-size: 13px"> الموظف</span>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <input type="checkbox" name="group_by_v_type" value="3" style="transform: scale(1.5)">&nbsp;
                            <span style="font-size: 13px"> نوع الإجازة</span>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <input type="checkbox" name="group_by_v_status" value="4" style="transform: scale(1.5)">&nbsp;
                            <span style="font-size: 13px"> حالة الإجازة</span>

                    </div>
                </div>

                <div class="form-group rp col-sm-3 op" id="group_by2" style="display:none;">
                    <label class="control-label">تجميع حسب</label>
                    <div>
                        <?php if(HaveAccess($admin_url)) { ?>
                            <input type="checkbox" name="group_by_branch" value="1" style="transform: scale(1.5)">&nbsp;
                            <span style="font-size: 13px"> المقر</span>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                        <?php } ?>
                        <input type="checkbox" name="group_by_emp_no" value="2" style="transform: scale(1.5)">&nbsp;
                        <span style="font-size: 13px"> الموظف</span>
                    </div>
                </div>


            </div>

        </fieldset>

        <div class="modal-footer">
            <button type="button" onclick="javascript:print_report();" class="btn btn-success">عرض التقرير<span class="glyphicon glyphicon-print"></span></button>
            <button type="button" onclick="javascript:clear_form();"  class="btn btn-default">تفريغ الحقول</button>
        </div>

        <div class="htmlrep" style="display: none; border: 1px solid #000; border-radius: 6px; width: 100%;">
        </div>

    </div>

</div>


<?php
$scripts = <<<SCRIPT
<script>

    function clear_form(){
        clearForm_any('.row');
        $('#dp_employees, #dp_vac_type, #dp_adopt').select2('val','');
    }

    $('#dp_rep_id').change(function() {
        showOptions();
    });

    $(document).ready(function() {
		$('#dp_employees, #dp_vac_type, #dp_adopt').select2();
    });

    function showOptions(){
        var id=$('#dp_rep_id').val();
        switch(id) {
        case "0":
            $(".op").fadeOut();
            break;
        case "1":
            $(".op").fadeOut();
            $("#employees_id,#vac_type_id,#branch_id,#rep_type,#from_month_id,#to_month_id,#adopt_id,#group_by").fadeIn();
            break;
        case "2":
            $(".op").fadeOut();
            $("#employees_id,#branch_id,#rep_type,#group_by2").fadeIn();
            break;
        case "3":
            $(".op").fadeOut();
            $("#year_id,#employees_id,#branch_id,#rep_type,#group_by2").fadeIn();
            break;
        }
    }

    function getReportUrl(){
        var id=$('#dp_rep_id').val();
        var from_month = $('#txt_from_month').val();
        var to_month = $('#txt_to_month').val();
        var year = $('#txt_year').val();
        var rep_type = $('input[name=rep_type]:checked').val();
        var group_by_branch = have_no_val($('input[name=group_by_branch]:checked').val());
        var group_by_v_type = have_no_val($('input[name=group_by_v_type]:checked').val());
        var group_by_v_status = have_no_val($('input[name=group_by_v_status]:checked').val());
        var group_by_emp_no = have_no_val($('input[name=group_by_emp_no]:checked').val());
        var group_by = have_no_val($('input[name=group_by]:checked').val());
        var employees = $('#dp_employees').val();
        var vac_type = $('#dp_vac_type').val();
        var branch = $('.branch').val();
		
		if($('#dp_adopt').val() == 99 ) {
			var adopt = '';
			var adopt2=10;
		}else {
			adopt = $('#dp_adopt').val();
			adopt2 = '';
		}

        var repUrl;
        switch(id) {
            case "1":
                repUrl = '{$report_url}&report_type='+rep_type+'&report=employees_vacations_'+rep_type+'&p_date_from='+from_month+'&p_date_to='+to_month+'&p_emp_id='+employees+'&p_vac_type='+vac_type+'&p_branch='+branch+'&p_group_by_branch='+group_by_branch+'&p_group_by_v_type='+group_by_v_type+'&p_group_by_v_status='+group_by_v_status+'&p_group_by_emp_no='+group_by_emp_no+'&p_adopt2='+adopt2+'&p_adopt='+adopt+'';
                break;
            case "2":
                repUrl = '{$report_url}&report_type='+rep_type+'&report=duplicate_vacations_'+rep_type+'&p_emp_id='+employees+'&p_branch='+branch+'&p_group_by_branch='+group_by_branch+'&p_group_by_emp_no='+group_by_emp_no+'';
                break;
            case "3":
                repUrl = '{$report_url}&report_type='+rep_type+'&report=minus_vacations_'+rep_type+'&p_emp_id='+employees+'&p_branch='+branch+'&p_year='+year+'&p_group_by_branch='+group_by_branch+'&p_group_by_emp_no='+group_by_emp_no+'';
                break;
        }
        return repUrl;
    }


    function print_report(){
        var rep_type = $('input[name=rep_type]:checked').val();
        var rep_url = getReportUrl();
        if($('#dp_rep_id').val()==0){
            danger_msg('تنبيه ..','اختر التقرير');
        }else {
            if(rep_type == "html") {
                _showHtmlRep(rep_url);
            }else {
                _showReport(rep_url);
            }
        }
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
