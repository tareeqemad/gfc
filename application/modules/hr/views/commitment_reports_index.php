<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mtaqia
 * Date: 2023-07-19
 * Time: 1:18 PM
 */
$report_url = base_url("JsperReport/showreport?sys=hr/commitments");
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
                            <option value="1">إقرار نظام تضارب المصالح</option>
                        </select>
                    </div>
                </div>
            </div>
        </fieldset>

        <fieldset>
            <legend>معايير البحث</legend>


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


                <br/><br/>

            <div class="form-group rp col-sm-2 op" id="rep_type" style="display:none;">
                <label class="control-label">نوع المستند</label>
                <div>
                    <input type="radio"  name="rep_type" value="pdf" checked="checked">
                    <i class="fa fa-file-pdf-o" style="font-size:28px;color:#e2574c;"></i>

                    <input type="radio"  name="rep_type" value="html">
                    <i class="fa fa-file-text-o" style="font-size:28px;color:#070707;"></i>&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio"  name="rep_type" value="xls">
                    <i class="fa fa-file-excel-o" style="font-size:28px;color:#1d7044"></i>

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
        $('#dp_employees, #dp_branch').select2('val','');
    }

    $('#dp_rep_id').change(function() {
        showOptions();
    });
    
    $(document).ready(function() {
		$('#dp_employees,#dp_branch').select2();
    });

    function showOptions(){
        var id=$('#dp_rep_id').val();
        switch(id) {
        case "0":
            $(".op").fadeOut();
            break;
        case "1":
            $(".op").fadeOut();
            $("#branch_id,#employees_id,#rep_type").fadeIn();
            break;
        }
    }

    function getReportUrl(){
        var id=$('#dp_rep_id').val();
        var rep_type = $('input[name=rep_type]:checked').val();
        var employees = $('#dp_employees').val();
        var branch = $('#dp_branch').val();
        
	    var repUrl;
        switch(id) {
            case "1":
                repUrl = '{$report_url}&report_type='+rep_type+'&report=acknowledgment_commitment&p_form_id=1&p_branch='+branch+'&p_emp_no='+employees+'';
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

</script>
SCRIPT;

sec_scripts($scripts);

?>
