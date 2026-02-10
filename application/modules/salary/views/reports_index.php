<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mtaqia
 * Date: 2022-05-17
 * Time: 09:46 AM
 */
$report_url = base_url("JsperReport/showreport?sys=hr/salaries");

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
                            <option value="1">1- كشف الإستقطاعات</option>
                            <option value="2">2- كشف الإستحقاقات الثابتة</option>
                            <option value="3">3- كشف الإستحقاقات غير الثابتة</option>
                            <option value="4">4- كشف الإستقطاعات - اجمالي</option>
                        </select>
                    </div>
                </div>
            </div>
        </fieldset>

        <fieldset>
            <legend>معايير البحث</legend>

            <div class="modal-body inline_form">

                <div class="form-group rp col-sm-1 op" id="month_id" style="display:none;">
                    <label class="control-label">عن شهر</label>
                    <div>
                        <input type="text" name="month" id="txt_month" value="<?=date('Ym');?>" class="form-control">
                    </div>
                </div>

                <div class="form-group rp col-sm-2 op" id="from_employees_id" style="display:none;">
                    <label class="control-label">من الموظف</label>
                    <div>
                        <select name="from_employees" class="form-control" id="dp_from_employees">
                            <option value="">جميع الموظفين</option>
                            <?php foreach ($all_employees as $row) : ?>
                                <option data-dept="<?= $row['NO'] ?>"
                                        value="<?= $row['NO'] ?>"><?= $row['NO'].' : '.$row['NAME'];?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group rp col-sm-2 op" id="employee_id" style="display:none;">
                    <label class="control-label">الموظف</label>
                    <div>
                        <select name="employee_id" class="form-control" id="dp_employee_id">
                            <option value="">_______________</option>
                            <?php foreach ($all_employees as $row) : ?>
                                <option data-dept="<?= $row['NO'] ?>"
                                        value="<?= $row['NO'] ?>"><?= $row['NO'].' : '.$row['NAME'];?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group rp col-sm-2 op" id="to_employees_id" style="display:none;">
                    <label class="control-label">إلى الموظف</label>
                    <div>
                        <select name="to_employees" class="form-control" id="dp_to_employees">
                            <option value="">جميع الموظفين</option>
                            <?php foreach ($all_employees as $row) : ?>
                                <option data-dept="<?= $row['NO'] ?>"
                                        value="<?= $row['NO'] ?>"><?= $row['NO'].' : '.$row['NAME'];?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>


                <div class="form-group rp col-sm-1 op" id="type_id" style="display:none;">
                    <label class="control-label">نوع التعيين</label>
                    <div>
                        <select name="type" class="form-control" id="dp_type">
                            <option value="">_______________</option>
                            <?php foreach ($emp_type as $row) : ?>
                                <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group rp col-sm-1 op" id="branch_id" style="display:none;">
                    <label class="control-label">المقر</label>
                    <div>
                        <select name="branch" class="form-control" id="dp_branch">
                            <option value="">جميع المقرات</option>
                            <?php foreach ($branches as $row) : ?>
                                <option value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group rp col-sm-2 op" id="extras_discounts_id" style="display:none;">
                    <label class="control-label">اسم البدل</label>
                    <div>
                        <select name="extras_discounts" class="form-control" id="dp_extras_discounts">
                            <option value="">_______________</option>
                            <?php foreach ($extras_discounts as $row) : ?>
                                <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NO'].' : '.$row['CON_NAME'];?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group rp col-sm-1 op" id="curr_id" style="display:none;">
                    <label class="control-label">العملة</label>
                    <div>
                        <select name="curr" class="form-control" id="dp_curr">
                            <option value="">_______________</option>
                            <?php foreach ($curr as $row) : ?>
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

                        <input type="radio"  name="rep_type"  value="doc">
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
        $('#dp_from_employees,#dp_to_employees,#dp_curr,#dp_extras_discounts,#dp_type, #dp_employee_id').select2('val','');
    }

    $('#dp_rep_id').change(function() {
        showOptions();
    });

    $(document).ready(function() {
		$('#dp_from_employees, #dp_to_employees,#dp_curr,#dp_extras_discounts,#dp_type, #dp_employee_id').select2();
    });

    function showOptions(){
        var id=$('#dp_rep_id').val();
        switch(id) {
        case "0":
            $(".op").fadeOut();
            break;
        case "1":
            $(".op").fadeOut();
            $("#from_employees_id,#to_employees_id,#branch_id,#rep_type,#month_id,#extras_discounts_id,#type_id").fadeIn();
            break;
        case "2":
            $(".op").fadeOut();
            $("#from_employees_id,#to_employees_id,#branch_id,#rep_type,#month_id,#extras_discounts_id,#type_id").fadeIn();
            break;
        case "3":
            $(".op").fadeOut();
            $("#from_employees_id,#to_employees_id,#branch_id,#rep_type,#month_id,#extras_discounts_id,#type_id").fadeIn();
            break;
        case "4":
            $(".op").fadeOut();
            $("#employee_id,#rep_type,#month_id,#extras_discounts_id").fadeIn();
            break;
        }
    }

    function getReportUrl(){

        var id=$('#dp_rep_id').val();
        var rep_type = $('input[name=rep_type]:checked').val();
        var month = $('#txt_month').val();
        var from_employees = $('#dp_from_employees').val();
        var to_employees = $('#dp_to_employees').val();
        var employee_no = $('#dp_employee_id').val();
        var type = $('#dp_type').val();
        var branch = $('#dp_branch').val();
        var extras_discounts = $('#dp_extras_discounts').val();
        var curr = $('#dp_curr').val();
		        
        var repUrl;
        switch(id) {
            case "1":
                repUrl = '{$report_url}&report_type='+rep_type+'&report=deductions_statement&p_from_emp_id='+from_employees+'&p_to_emp_id='+to_employees+'&p_month='+month+'&p_branch='+branch+'&p_con_no='+extras_discounts+'&p_emp_type='+type+'';
                break;
            case "2":
                repUrl = '{$report_url}&report_type='+rep_type+'&report=constant_add_statement&p_from_emp_id='+from_employees+'&p_to_emp_id='+to_employees+'&p_month='+month+'&p_branch='+branch+'&p_con_no='+extras_discounts+'&p_emp_type='+type+'';
                break;
            case "3":
                repUrl = '{$report_url}&report_type='+rep_type+'&report=unconstant_add_statement&p_from_emp_id='+from_employees+'&p_to_emp_id='+to_employees+'&p_month='+month+'&p_branch='+branch+'&p_con_no='+extras_discounts+'&p_emp_type='+type+'';
                break;
            case "4":
                if(rep_type == 'pdf'){
                    repUrl = '{$report_url}&report=deductions_statement_new_pdf&&p_emp_no='+employee_no+'&p_month='+month+'&p_con_no='+extras_discounts+'';
		        } else if(rep_type == 'xls'){
		            repUrl = '{$report_url}&report_type=xls&report=deductions_statement_new_xls&p_emp_no='+employee_no+'&p_month='+month+'&p_con_no='+extras_discounts+'';
		        } else {
                    alert('غير متاح');
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

</script>
SCRIPT;

sec_scripts($scripts);

?>
