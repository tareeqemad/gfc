<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 02/02/16
 * Time: 08:33 ص
 */
$back_url = $action == 'permit' ? base_url('technical/WorkOrderAssignment/permit_index') : base_url('technical/WorkOrderAssignment');
$report_url = 'http://itdev:801/gfc.aspx?data=' . get_report_folder() . '&';

if (!isset($result))
    $result = array();
$HaveRs = count($result) > 0;
$loads_url = base_url('technical/Worker_Order_Loads/index/');
$rs = $HaveRs ? $result[0] : $result;
$CanAdopt = $HaveRs ? ($rs['WORK_ORDER_CASE'] == 3 && $action == 'feedbackadopt' ? true : false) : false;

 

?>

<div class="row">
<div class="toolbar">

    <div class="caption"><?= $title ?></div>
    <ul>
        <?php if (HaveAccess($back_url)): ?>
            <li><a href="<?= $back_url ?>"><i class="icon icon-reply"></i> </a> </li><?php endif; ?>
        <li><a onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a>
        </li>
    </ul>


</div>

<div class="form-body">

<div id="msg_container"></div>
<div id="container">
<form class="form-form-vertical" id="WorkOrderAssignment_form" method="post"
      action="<?= base_url('technical/WorkOrderAssignment/feedback') ?>"
      role="form" novalidate="novalidate">
<div class="modal-body inline_form">

<div id="myTabContent" class="tab-content">
<fieldset class="tab-pane fade in active" id="home">
    <legend> البيانات الأساسية</legend>
    <div class="form-group col-sm-1">
        <label class="control-label"> م. </label>

        <div>
            <div
                class="form-control"><?= $HaveRs ? $rs['WORK_ORDER_ASSIGNMENT_ID'] : "" ?>
                <input type="hidden" value="<?= $HaveRs ? $rs['WORK_ORDER_ASSIGNMENT_ID'] : "" ?>"
                       name="work_order_assignment_id">
            </div>
        </div>
    </div>

    <div class="form-group col-sm-1">
        <label class="control-label"> رقم التكليف </label>

        <div>
            <div
                class="form-control"><?= $HaveRs ? $rs['WORK_ASSIGNMENT_CODE'] : "" ?>

            </div>
        </div>
    </div>

    <div class="form-group col-sm-5">
        <label class="control-label"> عنوان التكليف </label>

        <div>
            <div class="form-control"> <?= $HaveRs ? $rs['TITLE'] : "" ?></div>
        </div>
    </div>
    <hr>

    <div class="form-group  col-sm-1">
        <label class="control-label">القسم</label>

        <div>
            <div
                class="form-control"><?= $HaveRs ? $rs['WORK_ORDER_DEPARTMENT_NAME'] : '' ?> </div>
        </div>
    </div>

    <div class="form-group col-sm-4">
        <label class="control-label"> الفرقة الفنية </label>

        <div>
            <div class="form-control"><?= $HaveRs ? $rs['TEAM_ID_NAME'] : "" ?></div>

        </div>
    </div>

    <div class="form-group col-sm-1">
        <label class="control-label"> ساعة الخروج</label>

        <div>
            <div class="form-control ltr"><?= $HaveRs ? $rs['TIME_OUT'] : "" ?> <input
                    value="<?= $HaveRs ? $rs['TIME_OUT'] : "" ?>" name="time_out" type="hidden"></div>
        </div>
    </div>

    <div class="form-group col-sm-1">
        <label class="control-label"> ساعة العودة</label>

        <div>
            <input name="time_return" type="text" data-type="datetime" data-date-format="DD/MM/YYYY HH:mm" readonly
                   class="form-control ltr" value="<?= $HaveRs ? $rs['TIME_RETURN'] : "" ?>">
        </div>
    </div>


    <hr>
    <div class="form-group  col-sm-12">
        <label class="control-label">المشروحات </label>

        <div>
            <div class="form-control"><?= $HaveRs ? $rs['MANAGER_EXPLAIN'] : "" ?></div>
        </div>
    </div>

    <div class="form-group  col-sm-12">
        <label class="control-label">ملاحظات
        </label>

        <div>
            <textarea name="hints" data-val="true" data-val-required="يجب إدخال البيان "
                      id="txt_hints"
                      class="form-control"><?= $HaveRs ? $rs['HINTS'] : "" ?></textarea>
        </div>
    </div>


</fieldset>
<div class="padding-5">
<fieldset id="workOrder">
    <legend> أوامر العمل</legend>


    <table class="table" data-container="container">
        <thead>
        <tr>
            <th style="width: 20px">#</th>
            <th style="width: 8%">رقم امر العمل</th>
            <th style="width: 350px"> أمر العمل</th>
            <th style="width: 300px">الإجراء</th>

            <th style="width: 150px"> وقت النهاية</th>
            <th style="width: 80px"> الحالة</th>
            <th style=""> الملاحظات</th>

            <th>
                المحول

            </th>

            <th style="width: 90px"></th>

        </tr>
        </thead>

        <tbody>

        <?php
        $count = -1;
        foreach ($details_orders as $row) {
            ?>
            <tr>
                <td><?= ++$count + 1 ?></td>
                <td><?= $row['WORK_ORDER_CODE'] ?></td>

                <td>
                    <input type="hidden" name="w_ser[]" value="<?= $row['SER'] ?>"/>

                    <div class="form-control"><?= $row['WORKORDER_TITLE'] ?></div>
                </td>
                <td>
                    <div class="form-control">
                        <?= $row['ACTION_PROCEDURE'] ?>
                    </div>
                </td>
                <td class="btn-danger">
                    <input name="action_end[]" value="<?= $row['ACTION_END_TIME'] ?>" readonly
                           data-type="datetime" data-date-format="DD/MM/YYYY HH:mm" class="form-control ltr"
                           id="action_start_txt_work_order_id_<?= $count ?>"/>
                </td>

                <td><select name="not_done[]">
                        <option value="1">منفذ</option>
                        <option value="2">غير منفذ</option>
                    </select>

                <td>
                    <input name="w_hints[]" value="<?= $row['HINTS'] ?>" class="form-control"
                           id="txt_hints_<?= $count ?>"/>
                </td>

                <?php if (in_array($row['REQUEST_TYPE'], array(1, 2, 3, 5, 8))) : ?>
                    <td>
                        <input type="hidden" data-val="true" readonly="" data-val-required="حقل مطلوب"
                               value="<?= $row['SOURCE_ID'] ?>" name="source_id[]"
                               id="h_txt_source_id_<?= $count ?>" class="form-control col-md-3 valid">
                        <input type="text" data-val="true" readonly="" data-val-required="حقل مطلوب"
                               value="<?= $row['SOURCE_ID_NAME'] ?>" name="source_id_name[]"
                               id="txt_source_id_<?= $count ?>" class="form-control">

                    </td>
                <?php endif; ?>

                <td>
                    <a onclick="javascript:<?= $row['REQUEST_TYPE'] == 5 ? "_showReport('{$loads_url}/{$row['WORK_ORDER_ID']}')" : 'display: none;' ?>;"
                       class="_loads btn-xs btn-success"
                       style="<?= $row['REQUEST_TYPE'] == 5 ? '' : 'display: none;' ?>"
                       href="javascript:;">قياس الأحمال</a>
                </td>


            </tr>
        <?php

        }
        ?>

        </tbody>

    </table>


</fieldset>
<fieldset id="tools">
    <legend>المواد</legend>

    <?php


    $work_codes = array_unique(array_column($details_tools, 'WORK_ORDER_CODE'));

    foreach ($work_codes as $code):
        $this->code = $code;
        $filtered = array_filter(
            $details_tools, function ($k) {

                return $k['WORK_ORDER_CODE'] == $this->code;
            }
        );



        ?>
        <div class="alert alert-warning"> أمر عمل :
            <?= $code ?>
        </div>
        <table class="table" data-container="container">
            <thead>
            <tr>
                <th style="width: 2%">#</th>
                <th style="width: 8%">رقم امر العمل</th>
                <th style="width: 8%">رقم الصنف</th>
                <th style="width: 25%">اسم الصنف</th>
                <th style="width: 7%">الوحدة</th>
                <th style="width: 80px;">الحالة</th>
                <th style="width: 8%">الكمية المستلمة</th>
                <th style="width: 8%">الكمية المستخدمة</th>

                <th></th>
                <th style="width: 50px;"></th>
            </tr>
            </thead>

            <tbody>

            <?php
            $count = -1;
            foreach ($filtered as $row) {
                ?>
                <tr>
                    <td><?= ++$count + 1 ?></td>
                    <td><?= $row['WORK_ORDER_CODE'] ?></td>
                    <td>
                        <input type="hidden" name="tl_ser[]" value="<?= $row['SER'] ?>"/>

                        <div class="form-control"><?= $row['CLASS_ID'] ?></div>
                    </td>

                    <td>
                        <div class="form-control"><?= $row['CLASS_ID_NAME'] ?></div>
                    </td>

                    <td>
                        <div class="form-control"><?= $row['CLASS_UNIT_NAME'] ?></div>

                    </td>

                    <td>
                        <?= $row['CLASS_TYPE'] == 1 ? 'جديد' : 'مستعمل' ?>
                    </td>

                    <td>
                        <div class="form-control"><?= $row['CLASS_COUNT'] ?></div>
                    </td>
                    <td class="btn-danger">
                        <input name="class_count_used[]" value="<?= $row['CLASS_COUNT_USED'] ?>"
                               class="form-control"
                               id="txt_class_count_used_<?= $count ?>"/>
                    </td>

                    <td></td>

                </tr>
            <?php

            }
            ?>

            </tbody>

        </table>

    <?php endforeach; ?>

</fieldset>

<fieldset id="cars">
    <legend> الأليات</legend>
    <table class="table" id="carsTbl" data-container="container">
        <thead>
        <tr>
            <th style="width: 2%">#</th>
            <th style="width: 15%">نوع الآلية</th>
            <th style="width: 15%">العدد</th>
            <th style="width: 100px;">مدة العمل</th>
            <th style="width: 60%"> وصف الاحتياج</th>

        </tr>
        </thead>

        <tbody>

        <?php
        $count = -1;
        foreach ($details_cars as $row) {
            ?>
            <tr>
                <td><?= ++$count + 1 ?></td>
                <td>
                    <input type="hidden" name="c_ser[]" value="<?= $row['SER'] ?>"/>

                    <div class="form-control"><?= $row['CAR_ID_NAME'] ?></div>
                </td>
                <td>
                    <div class="form-control"><?= $row['CAR_COUNT'] ?></div>
                </td>
                <td class="btn-danger">

                    <input name="the_time_minute[]" value="<?= $row['THE_TIME_MINUTE'] ?>"
                           class="form-control"/>

                </td>
                <td>


                    <div class="form-control"><?= $row['NEED_DESCRIPTION'] ?></div>
                </td>

            </tr>
        <?php

        }
        ?>

        </tbody>

    </table>
</fieldset>


<fieldset>
    <legend>المواد المرجعة</legend>

    <table class="table" data-container="container">
        <thead>
        <tr>
            <th style="width: 2%">#</th>
            <th style="width: 150px;" >أمر العمل</th>
            <th style="width: 8%">رقم الصنف</th>
            <th style="width: 25%">اسم الصنف</th>
            <th style="width: 7%">الوحدة</th>
            <th style="width: 80px;">الحالة</th>
            <th style="width: 8%"> الكمية</th>


            <th>الملاحظات</th>
            <th style="width: 50px;"></th>
        </tr>
        </thead>

        <tbody>

        <?php if (count($RETURN_TOOLS) <= 0): ?>

            <tr>
                <td>1</td>
                <td>

                    <select name="return_workorder[]" class="form-control">
                        <?php foreach ($details_orders as $orow) : ?>
                            <option value="<?= $orow['SER'] ?>"><?= $orow['WORK_ORDER_CODE'] ?> - <?= $orow['WORKORDER_TITLE'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <input type="hidden" name="r_ser[]" value="0">
                    <input type="text" name="r_class_id[]" class="form-control" value="" id="h_txt_r_class_id_0">
                </td>

                <td>
                    <input name="r_class_name[]" readonly="" value="" class="form-control valid"
                           id="txt_r_class_id_0">
                </td>

                <td>
                    <input class="form-control" type="hidden" value="" id="unit_txt_r_class_id_0"
                           name="r_class_unit[]">
                    <input class="form-control" readonly="" value="" type="text" id="unit_name_txt_r_class_id_0">

                </td>


                <td>

                    <select name="r_class_type[]" class="form-control">
                        <option value="1">جديد</option>
                        <option value="2">مستعمل</option>
                        <option value="3">تالف</option>
                    </select>


                </td>
                <td>
                    <input name="r_class_count[]" class="form-control" id="r_count_txt_class_id_0" value="">
                </td>
                <td><input name="notes[]" class="form-control" id="notes_txt" value=""></td>
                <td data-action="delete">
                    <!--<a data-action="delete" href="javascript:;"><i class="icon icon-trash delete-action"></i> </a>-->

                </td>
            </tr>

        <?php

        endif;
        $count = -1;
        foreach ($RETURN_TOOLS as $row) {
            ?>
            <tr>
                <td><?= ++$count + 1 ?></td>
                <td>

                    <select name="return_workorder[]" class="form-control">
                        <?php foreach ($details_orders as $orow) : ?>
                            <option value="<?= $orow['SER'] ?>"><?= $orow['WORK_ORDER_CODE'] ?> - <?= $orow['WORKORDER_TITLE'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <input type="hidden" name="r_ser[]" value="<?= $row['SER'] ?>">
                    <input type="text" name="r_class_id[]" class="form-control" value="<?= $row['CLASS_ID'] ?>"
                           id="h_txt_r_class_id_<?= $count ?>">
                </td>

                <td>
                    <input name="r_class_name[]" readonly="" value="<?= $row['CLASS_ID_NAME'] ?>"
                           class="form-control valid"
                           id="txt_r_class_id_<?= $count ?>">
                </td>

                <td>
                    <input class="form-control" type="hidden" value="<?= $row['CLASS_UNIT'] ?>"
                           id="unit_txt_r_class_id_<?= $count ?>"
                           name="r_class_unit[]">
                    <input class="form-control" readonly="" value="<?= $row['CLASS_UNIT_NAME'] ?>" type="text"
                           id="unit_name_txt_r_class_id_<?= $count ?>">

                </td>


                <td>

                    <select name="r_class_type[]" class="form-control">
                        <option <?= $row['CLASS_TYPE'] == 1 ? 'selected' : '' ?> value="1">جديد</option>
                        <option <?= $row['CLASS_TYPE'] == 2 ? 'selected' : '' ?>  value="2">مستعمل</option>
                        <option <?= $row['CLASS_TYPE'] == 3 ? 'selected' : '' ?>  value="3">تالف</option>
                    </select>


                </td>
                <td>
                    <input name="r_class_count[]" class="form-control" id="r_count_txt_class_id_<?= $count ?>"
                           value="<?= $row['CLASS_COUNT_RET'] ?>">
                </td>
                <td><input name="notes[]" class="form-control" id="notes_txt" value="<?= $row['NOTES'] ?>"></td>
                <td data-action="delete">
                    <?php if (((!$HaveRs || (isset($can_edit) && $can_edit && $rs['WORK_ORDER_CASE'] == 2 && $action == 'feedbackRegisterAdopt')))): ?>
                        <a href="javascript:;"
                           onclick="javascript:delete_details(this,<?= $row['SER'] ?>,'<?= base_url('technical/WorkOrderAssignment/delete_return_item') ?>');"><i
                                class="icon icon-trash delete-action"></i> </a>
                    <?php endif; ?>

                </td>

            </tr>
        <?php

        }
        ?>

        </tbody>

        <tfoot>
        <tr>
            <th></th>
            <th>

                <a onclick="javascript:add_row(this,'input',false);" href="javascript:;"><i
                        class="glyphicon glyphicon-plus"></i>جديد</a>

            </th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        </tfoot>

    </table>

</fieldset>
</div>

<div class="modal-footer">

    <?php if (((!$HaveRs || (isset($can_edit) && $can_edit && $rs['WORK_ORDER_CASE'] == 2 && $action == 'feedbackregisteradopt')))): ?>
        <button type="submit" data-action="submit" data-action-submit="save" class="btn btn-danger">حفظ البيانات
        </button>

    <?php endif; ?>

    <?php if ($HaveRs && (($rs['WORK_ORDER_CASE'] == 2 && $action == 'feedbackregisteradopt'))): ?>
        <button type="submit" data-action="submit" data-action-submit="adopt" class="btn btn-success">
            حفظ و اعتماد
        </button>
    <?php endif; ?>

    <?php if ($CanAdopt): ?>
        <button type="button" onclick="javascript:return_adopt(1);" class="btn btn-success"> إعتمد</button>
        <button type="button" onclick="javascript:return_adopt(0);" class="btn btn-danger">إرجاع</button>
    <?php endif; ?>

    <?php if ($HaveRs): ?>
        <button type="button"
                onclick="javascript:_showReport('<?= $report_url ?>report=TECHNICAL/WORK_ORDER_ASSIGNMENT_REP&params[]=<?= $rs['WORK_ORDER_ASSIGNMENT_ID'] ?>');"
                class="btn btn-default"> طباعة
        </button>
    <?php endif; ?>

</div>
<hr>
<fieldset id="workers">
    <legend> فريق العمل</legend>


    <table class="table" data-container="container">
        <thead>
        <tr>
            <th style="width: 2%">#</th>
            <th style="width: 25%">الموظف</th>
            <th style="width: 15%">الوظيفة</th>
            <th style="width: 60%">العمل المكلف به</th>

        </tr>
        </thead>

        <tbody>

        <?php
        $count = -1;
        foreach ($details_teams as $row) {
            ?>
            <tr>
                <td><?= ++$count + 1 ?></td>
                <td>
                    <input type="hidden" name="t_ser[]" value="<?= $row['SER'] ?>"/>

                    <div class="form-control"><?= $row['WORKER_JOB_ID_NAME'] ?></div>
                </td>
                <td>
                    <div class="form-control"><?= $row['WORKER_JOB_NAME'] ?></div>

                </td>
                <td>
                    <div class="form-control"><?= $row['TASK'] ?></div>
                </td>

            </tr>
        <?php
        }

        ?>

        </tbody>

    </table>

</fieldset>


</div>

<hr>
<div style="clear: both;">
    <?php echo modules::run('settings/notes/public_get_page', $HaveRs ? $rs['WORK_ORDER_ASSIGNMENT_ID'] : 0, 'work_order_assignment'); ?>
    <?php echo $HaveRs ? modules::run('attachments/attachment/index', $rs['WORK_ORDER_ASSIGNMENT_ID'], 'work_order_assignment') : ''; ?>
</div>


</div>
</form>

</div>
<?php echo modules::run('settings/notes/index'); ?>
</div>
</div>
<?php
$delete_tools_url = base_url('technical/WorkOrderAssignment/delete_tools');
$delete_works_url = base_url('technical/WorkOrderAssignment/delete_works');
$adapters_url = base_url('projects/adapter/public_index');
$WorkOrderAssignment_url = base_url('technical/HighPowerPartition/public_index');
$select_items_url = base_url("stores/classes/public_index");

$customer_url = base_url('payment/customers/public_index');

$get_Tjob_url = base_url('technical/Technical_jobs/public_index');

$public_select_project_url = base_url('projects/projects/public_select_project');

$public_select_team_url = base_url('technical/branches_teams/public_index');

$public_get_all_details_url = base_url('technical/branches_teams/public_get_details_json');

$public_WorkOrder_url = base_url('technical/WorkOrder/public_index');

$customer_url = base_url('payment/customers/public_index');

$loads_url = base_url('technical/Worker_Order_Loads/index/');
$feedbackRegisterAdopt_url = base_url('technical/WorkOrderAssignment/feedbackRegisterAdopt/');
$adapter_url = base_url('projects/adapter/public_index');

$shared_script = <<<SCRIPT


    $('#txt_team_id').click(function () {
        _showReport('$public_select_team_url/' + $(this).attr('id'));
    });

    $('input[name="source_id_name[]"]').click(function () {
        _showReport('{$adapter_url}/' + $(this).attr('id') + '/');
    });

    function afterSelect(tr, id, type) {

        if (type == 4) {
            $('a._loads', tr).show();
            $('a._loads', tr).attr('href', "javascript:_showReport('{$loads_url}/" + id + "')");
        } else {
            $('a._loads', tr).hide();
            $('a._loads', tr).attr('href', "javascript:;");
        }
    }


    $('#dp_work_order_type').change(function () {

        var type = $(this).val();

        if (type != 1 && type != 2) {
            $('#project_div input,#agent_maintenace_div').val('');
            $('#project_div,#agent_maintenace_div').hide();
        } else if (type == 1) {
            $('#agent_maintenace_div').val('');
            $('#agent_maintenace_div').hide();
        } else if (type == 2) {
            $('#project_div input').val('');
            $('#project_div').hide();
        }


        if (type == 1) {
            $('#project_div').show();
        }

        if (type == 2) {
            $('#agent_maintenace_div').show();
        }


    });

    $('select[name="not_done[]"]').change(function () {

        if ($(this).val() == 2) {
            $(this).closest('tr').find('input[name="action_end[]"]').val('');
            $(this).closest('tr').find('input[name="action_end[]"]').prop('value', '');
        }
    });

    $('input[name="action_end[]"]').change(function () {

        if ($(this).val() != '')
            $(this).closest('tr').find('select[name="not_done[]"]').val('1');
    });

    $('button[data-action="submit"]').click(function (e) {
        e.preventDefault();
        if (confirm('هل تريد حفظ السند ؟!')) {

            var form = $(this).closest('form');

            if ($(this).attr('data-action-submit') == 'adopt') {
                $(form).attr('action', '$feedbackRegisterAdopt_url');

            }

            ajax_insert_update(form, function (data) {

                success_msg('رسالة', 'تم حفظ البيانات بنجاح ..');

                reload_Page();

            }, 'html');
        }
    });

    $('#txt_job_id').click(function () {
        _showReport('$get_Tjob_url/' + $(this).attr('id'));

    });

    reBind();

    function reBind() {
        $('input[id^="txt_class_id"]').click("focus", function (e) {
            _showReport('$select_items_url/' + $(this).attr('id'));


        });

        $('input[name="r_class_name[]"]').click("focus", function (e) {

            _showReport('$select_items_url/' + $(this).attr('id'));

        });

        $('input[id^="txt_worker_job_id"]').click(function () {

            _showReport('$customer_url/' + $(this).attr('id') + '/3');

        });

        $('input[id^="txt_work_order_id"]').click(function () {
            _showReport('$public_WorkOrder_url/' + $(this).attr('id'));
        });


    }


    function delete_details(a, id, url) {
        if (confirm('هل تريد حذف البند ؟!')) {

            get_data(url, {id: id}, function (data) {
                if (data == '1') {
                    $(a).closest('tr').remove();

                } else {
                    danger_msg('تحذير', 'فشل في العملية');
                }
            });
        }
    }



    function get_team_data() {
        get_data('{$public_get_all_details_url}', {id: $('#h_txt_team_id').val()}, function (data) {


            $.each(data, function (i) {

                $('#h_txt_worker_job_id_' + i, $('#teamTbl')).val(data[i].CUSTOMER_ID);
                $('#txt_worker_job_id_' + i, $('#teamTbl')).val(data[i].CUSTOMER_ID_NAME);

                $('#h_txt_worker_job_' + i, $('#teamTbl')).val(data[i].WORKER_JOB);

                if (data.length > i + 1)
                    add_row('#teamTbl tfoot a', 'input', false);

            });


        });

    }

SCRIPT;


$create_script = <<<SCRIPT
    <script>
        {$shared_script}
    </script>
SCRIPT;

$notes_url = notes_url();
$public_return_url = base_url('technical/WorkOrderAssignment/public_return_feedback');
$WORK_ORDER_ASSIGNMENT_ID = $HaveRs ? $rs['WORK_ORDER_ASSIGNMENT_ID'] : 0;
$edit_script = <<<SCRIPT
    <script>
          var permit = '$action';
          if(permit == 'permit'){

                $('#WorkOrderAssignment_form input,#WorkOrderAssignment_form select,#WorkOrderAssignment_form textarea').not('#txt_WORK_ORDER_ASSIGNMENT_ID,#dp_work_permit,#txt_work_permit_conditions').prop('disabled',true);
          }

           var action_type;
         var in_use_id;


       function return_adopt(type){

            if(type == 1 && ! confirm('هل تريد إعتماد السند ؟!')){
                return;
            }
            action_type = type;

            $('#notesModal').modal();

       }

        function apply_action(){

                if(action_type == 1){

                     get_data($('#WorkOrderAssignment_form').attr('action').replace('feedback','$action'),{id:{$WORK_ORDER_ASSIGNMENT_ID}},function(data){
                        if(data =='1')
                            success_msg('رسالة','تم اعتماد السند بنجاح ..');
                            reload_Page();
                    },'html');

                }
                else{

                    if($('#txt_g_notes').val() =='' ){
                        alert('تحذير : لم تذكر سبب الإرجاع ؟!!');
                        return;
                    }
                    get_data('{$public_return_url}',{id:{$WORK_ORDER_ASSIGNMENT_ID}},function(data){
                        if(data =='1')
                            success_msg('رسالة','تم  إرجاع السند بنجاح ..');
                            reload_Page();
                    },'html');
                }

                get_data('{$notes_url}',{source_id:{$WORK_ORDER_ASSIGNMENT_ID},source:'work_order_assignment',notes:$('#txt_g_notes').val()},function(data){
                    $('#txt_g_notes').val('');
                },'html');

                $('#notesModal').modal('hide');


        }


        {$shared_script}
    </script>
SCRIPT;

if ($HaveRs)
    sec_scripts($edit_script);
else
    sec_scripts($create_script);

?>

<script>

</script>