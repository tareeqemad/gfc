<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mtaqia
 * Date: 17/09/16
 * Time: 08:58 ص
 */
$report_url = base_url("JsperReport/showreport?sys=hr/Employees_Evaluation");
$report_sn= report_sn();

$MODULE_NAME= 'hr';
$TB_NAME= 'reports';
$form_url= base_url("$MODULE_NAME/$TB_NAME/public_get_questions");

$fill_url= base_url("$MODULE_NAME/$TB_NAME/public_fill");
?>
<?= AntiForgeryToken() ?>
<div class="row">
    <div class="toolbar">
        <div class="caption"><?= $title ?></div>
    </div>
    <div class="form-body">
        <fieldset>
            <legend>تقارير تقييم الأداء</legend>
            <ul class="report-menu cpanel">

                <li><a class="btn blue" data-report-source="1"
                       data-option="#order_id" data-type="report"
                       href="javascript:;">الإحصائيات العامة</a></li>
                <li><a class="btn red" data-report-source="2"
                       data-option="#order_id,#branch_id" data-type="report"
                       href="javascript:;">تقرير حسب المقرات</a></li>
                <li><a class="btn green" data-report-source="3"
                       data-option="#order_id,#departments_id" data-type="report"
                       href="javascript:;">تقرير حسب الإدارات</a></li>
                <li><a class="btn purple" data-report-source="4"
                       data-option="#order_id,#jobs_id" data-type="report"
                       href="javascript:;">تقرير حسب المسميات الوظيفية</a></li>
                <li><a class="btn red" data-report-source="5"
                       data-option="#order_id,#departments_id" data-type="report"
                       href="javascript:;">تقييم الموظفين حسب الإدارات</a></li>
                <li><a class="btn purple" data-report-source="6"
                       data-option="#order_id,#branch_id" data-type="report"
                       href="javascript:;">تقرير الموظفين حسب المقرات</a></li>
                <li><a class="btn yellow" data-report-source="7"
                       data-option="#order_id,#jobs_id,#grade,#form_id,#question_id" data-type="report"
                       href="javascript:;">تقرير حسب الأسئلة</a></li>

            </ul>
        </fieldset>

        <div class="modal fade" id="reportModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span
                                class="sr-only">Close</span>
                        </button>
                        <h4 class="modal-title">معايير البحث</h4>
                    </div>
                    <div class="form-horizontal">
                        <div class="modal-body">
                            <div class="form-group rp_prm" id="order_id" style="display:none ;">
                                <label class="col-sm-3 control-label">أمر التقييم</label>
                                <div class="col-sm-5">
                                    <select name="order_id" class="form-control" id="dp_order_id">
                                        <option value=""></option>
                                        <?php foreach ($order_id as $row) : ?>
                                            <option data-dept="<?= $row['EVALUATION_ORDER_ID'] ?>"
                                                value="<?= $row['EVALUATION_ORDER_ID'] ?>"><?= $row['EVALUATION_ORDER_ID'].' - '.substr($row['ORDER_START'],-4).' - '?><?=($row['ORDER_TYPE']==1) ? 'سنوي' : 'نصفي' ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group rp_prm" id="branch_id" style="display:none ;">
                                <label class="col-sm-3 control-label">المقر</label>
                                <div class="col-sm-5">
                                    <select name="branch_id" class="form-control" id="dp_branch_id">
                                        <option value="">جميع المقرات</option>
                                        <?php foreach ($branches as $row) : ?>
                                            <option data-dept="<?= $row['NO'] ?>"
                                             value="<?= $row['NO'] ?>"><?= $row['NO'].' : '.$row['NAME'];?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group rp_prm" id="departments_id" style="display:none ;">
                                <label class="col-sm-3 control-label">الإدارة</label>
                                <div class="col-sm-5">
                                    <select name="departments_id" class="form-control" id="dp_departments_id">
                                        <option value="">جميع الإدارات</option>
                                        <?php foreach ($departments as $row) : ?>
                                            <?php if($row['ST_ID']!='010414'){?>
                                                <option data-dept="<?= $row['ST_ID'] ?>"
                                                        value="<?= $row['ST_ID'] ?>"><?= $row['ST_ID'].' : '.$row['ST_NAME'];?>
                                                </option>
                                            <?php } ?>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group rp_prm" id="jobs_id" style="display: none ;">
                                <label class="col-sm-3 control-label">الوظيفة</label>
                                <div class="col-sm-5">
                                    <select name="jobs_id" class="form-control" id="dp_job_id">
                                        <option value="">جميع الوظائف</option>
                                        <?php foreach ($jobs as $row) : ?>
                                                <option data-dept="<?= $row['NO'] ?>"
                                                        value="<?= $row['NO'] ?>"><?= $row['NO'].' : '.$row['NAME_J'];?>
                                                </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group rp_prm" id="employees_id" style="display: none;">
                                <label class="col-sm-3 control-label">الإسم</label>
                                <div class="col-sm-5">
                                    <select name="employees_id" class="form-control" id="dp_employees_id">
                                        <option value="">جميع الموظفين</option>
                                        <?php foreach ($all_employees as $row) : ?>
                                            <option data-dept="<?= $row['NO'] ?>"
                                                    value="<?= $row['NO'] ?>"><?= $row['NO'].' : '.$row['NAME'];?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group rp_prm" id="form_id" style="display:none ;">
                                <label class="col-sm-3 control-label">النموذج</label>
                                <div class="col-sm-5">
                                    <select name="form_id" class="form-control" id="dp_form_id">
                                        <option value=""></option>
                                        <?php foreach ($all_forms as $row) : ?>
                                            <option data-dept="<?= $row['EV_PK'] ?>"
                                                    value="<?= $row['EV_PK'] ?>"><?= $row['EV_PK'].' : '.$row['EV_NAME'];?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group rp_prm" id="question_id" style="display:none ;">
                                <label class="col-sm-3 control-label">السؤال</label>
                                <div class="col-sm-8">
                                    <select name='question_id' class='form-control' id='dp_question_id'>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group  rp_prm" id="grade" style="display: none;">
                                <label class="col-sm-3 control-label">الدرجة بين</label>

                                <div class="col-sm-1">
                                    <input type="text" id="txt_grade_1" class="form-control"/>
                                </div>
                                <div class="col-sm-1">
                                    <input type="text" id="between" readonly class="form-control"/>
                                </div>
                                <div class="col-sm-1">
                                    <input type="text" id="txt_grade_2" class="form-control"/>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" data-action="report" data-report_type="pdf" class="btn btn-danger">عرض التقرير PDF</button>
                                <button type="button" data-action="report" data-report_type="doc" class="btn btn-primary">عرض التقرير DOC</button>
                                <button type="button" data-action="report" data-report_type="xls" class="btn btn-success">عرض التقرير XLS</button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->

    </div>
</div>

<?php
$scripts = <<<SCRIPT
<script>
    var data_report_source ='';

    $(document).ready(function() {
		$('#dp_order_id, #dp_branch_id, #dp_departments_id, #dp_job_id, #dp_employees_id, #dp_form_id, #dp_question_id').select2();
    });

    $("#dp_form_id").change(function(){
        $("#dp_question_id").select2('val','');
        get_data('{$form_url}', {parent: $('#dp_form_id').val()}, function(ret){
             $('#dp_question_id').html(ret);
        }, 'html');
    });

    /* -------------------- */
    $('a[data-type="report"]').click(function(){
        $("#dp_order_id,#dp_branch_id,#dp_departments_id,#dp_job_id,#dp_employees_id,#dp_form_id,#dp_question_id").select2('val','');
        $('.rp_prm').hide();
        clearForm_any('#reportModal');
        $('#between').val('و');

        data_report_source = $(this).attr('data-report-source');
        $($(this).attr('data-option')).show();

        $('#reportModal').modal();
    });
    /* -------------------- */



    $('button[data-action="report"]').click(function(){

        if((data_report_source!=7 && $('#dp_order_id').val()!='') || (data_report_source==7 && $('#dp_question_id').val()!=='' && $('#txt_grade_1').val()!=='' && $('#txt_grade_2').val()!=='') && parseInt($('#txt_grade_1').val()) <= parseInt($('#txt_grade_2').val())) {

        /* Disable the button, after time enables it */
        $('button[data-action="report"]').attr("disabled", true);
        setTimeout(function(){
            $('button[data-action="report"]').attr("disabled",false);
        }, 5000);

        var url='{$report_url}&type='+$(this).attr('data-type')+'&report=';

        switch(data_report_source){

            case '1':
                get_data('{$fill_url}',{order_id:$('#dp_order_id').val()} ,function(data){
                    if(data == '1' ){
                        url +='Hr_Eval_General_Statistics&p_order_id='+$('#dp_order_id').val()+'&sn='+'{$report_sn}';
                        _showReport(url,true);
                    }
                },'html');

                break;

            case '2':
                url +='Hr_Eval_Headquarters_Ratios&p_order_id='+$('#dp_order_id').val()+'&p_branch_id='+$('#dp_branch_id').val()+'&sn={$report_sn}';
                break;

            case '3':
                url +='Hr_Eval_Departments_Ratios&p_order_id='+$('#dp_order_id').val()+'&p_departments_id='+$('#dp_departments_id').val()+'&sn={$report_sn}';
                break;

            case '4':
                url +='Hr_Eval_Jops_Ratios&p_order_id='+$('#dp_order_id').val()+'&p_job_id='+$('#dp_job_id').val()+'&sn={$report_sn}';
                break;

            case '5':
                url +='Hr_Eval_Emps_Departments_Rations&p_order_id='+$('#dp_order_id').val()+'&p_departments_id='+$('#dp_departments_id').val()+'&sn={$report_sn}';
                break;

            case '6':
                url +='Hr_Eval_Emps_Headquarters_Rations&p_order_id='+$('#dp_order_id').val()+'&p_branch_id='+$('#dp_branch_id').val()+'&sn={$report_sn}';
                break;

            case '7':
                url +='Hr_Eval_Questions_Report&p_order_id='+$('#dp_order_id').val()+'&p_job_id='+$('#dp_job_id').val()+'&p_question_id='+$('#dp_question_id').val()+'&p_grade_1='+$('#txt_grade_1').val()+'&p_grade_2='+$('#txt_grade_2').val()+'&sn={$report_sn}';
                break;
        }

        if(data_report_source !=1)
            _showReport(url,true);

        } else danger_msg('تحذير..','اختر معايير بحث صحيحة');
    });
</script>

SCRIPT;

sec_scripts($scripts);

?>
