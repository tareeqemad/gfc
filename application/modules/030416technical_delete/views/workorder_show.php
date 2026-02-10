<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 11/01/15
 * Time: 11:28 ص
 */
$back_url = $action == 'permit' ? base_url('technical/WorkOrder/permit_index') : base_url('technical/WorkOrder');
$get_url = base_url('technical/WorkOrder/get/');

if (!isset($result))
    $result = array();
$HaveRs = count($result) > 0;

$rs = $HaveRs ? $result[0] : $result;

$plans = isset($plans) ? $plans : array();

$source_id = $request_data != null ? (in_array($request_data[0]['REQUEST_TYPE'], array(2, 3, 5)) ? $request_data[0]['ADAPTER_SERIAL'] : ($request_data[0]['REQUEST_TYPE'] == 4 ? $request_data[0]['PROJECT_SERIAL'] : ($request_data[0]['REQUEST_TYPE'] == 6 ? $request_data[0]['CAR_ID'] : ($request_data[0]['REQUEST_TYPE'] == 7 ? $request_data[0]['COMPUTER_ID'] : '')))) : ($HaveRs ? $rs['SOURCE_ID'] : "");

$TEAM_COST = 0;
$TOOLS_COST = 0;
$CARS_COST = 0;

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
    <form class="form-form-vertical" id="WorkOrder_form" method="post"
          action="<?= base_url('technical/WorkOrder/' . ($HaveRs ? ($action == 'permit' ? 'permit' : 'edit') : 'create')) ?>"
          role="form" novalidate="novalidate">
    <div class="modal-body inline_form">
    <div class="<?= isset($TEAM_COST_ROWS) ? 'col-md-9' : '' ?>">
    <ul id="myTab" class="nav nav-tabs">
        <li class="active"><a href="#home" data-toggle="tab">
                بيانات أمر العمل</a>
        </li>
        <li><a href="#workers" data-toggle="tab">فريق العمل</a></li>
        <li><a href="#tools" data-toggle="tab">المواد </a></li>
        <li><a href="#cars" data-toggle="tab"> الأليات</a></li>

    </ul>
    <div id="myTabContent" class="tab-content">
    <div class="tab-pane fade in active" id="home">
    <div class="form-group col-sm-1">
        <label class="control-label"> الرقم </label>

        <div>
            <input type="text" readonly value="<?= $HaveRs ? $rs['WORK_ORDER_ID'] : "" ?>" name="work_order_id"
                   id="txt_work_order_id" class="form-control">
        </div>
    </div>

    <div class="form-group col-sm-1">
        <label class="control-label"> رقم الأمر </label>

        <div>
            <input type="text" readonly value="<?= $HaveRs ? $rs['WORK_ORDER_CODE'] : "" ?>"
                   name="request_id" id="txt_request_id" class="form-control">
        </div>
    </div>

    <div class="form-group col-sm-2">
        <label class="control-label"> الطلب </label>

        <div>
            <input type="hidden" name="request_id" readonly
                   value="<?= $request_data != null ? $request_data[0]['REQUEST_ID'] : ($HaveRs ? $rs['REQUEST_ID'] : "") ?>"
                   class="form-control">
            <input type="text" readonly
                   value="<?= $request_data != null ? $request_data[0]['REQUEST_TYPE_NAME'] : ($HaveRs ? $rs['REQUEST_TYPE_NAME'] : "") ?>"
                   class="form-control">
        </div>
    </div>
    <div class="form-group col-sm-4">
        <label class="control-label"> البيان </label>

        <div>
            <input type="text" data-val="true" data-val-required="حقل مطلوب"
                   value="<?= $request_data != null ? $request_data[0]['PURPOSE_DESCRIPTION'] : ($HaveRs ? $rs['WORKORDER_TITLE'] : "") ?>"
                   name="workorder_title" id="txt_workorder_title" class="form-control">
        </div>
    </div>

    <div class="form-group col-sm-2">
        <label id="source_title" class="control-label">

            <?php
            if (($request_data != null && (in_array($request_data[0]['REQUEST_TYPE'], array(2, 3, 5)))) || ($HaveRs && in_array($rs['REQUEST_TYPE'], array(2, 3, 5)))) {

                echo 'المحول';
            } else if (($request_data != null && $request_data[0]['REQUEST_TYPE'] == 6) || ($HaveRs && $rs['REQUEST_TYPE'] == 6)) {
                echo 'السيارة';
            } else if (($request_data != null && $request_data[0]['REQUEST_TYPE'] == 7) || ($HaveRs && $rs['REQUEST_TYPE'] == 7)) {
                echo 'الحاسوب';
            } else if (($request_data != null && $request_data[0]['REQUEST_TYPE'] == 4) || ($HaveRs && $rs['REQUEST_TYPE'] == 4)) {
                echo 'رقم المشروع';
            }

            ?>

        </label>

        <div>
            <?php

            /*
             *
             *  PROJECT_SERIAL
                CAR_ID
                COMPUTER_ID
                ADAPTER_SERIAL
             *
             */

            ?>
            <input type="hidden" data-val="true" readonly data-val-required="حقل مطلوب"
                   value="<?= $source_id ?>"
                   name="source_id" id="h_txt_source_id" class="form-control">
            <input type="text" data-val="true" readonly data-val-required="حقل مطلوب"
                   value="<?= $request_data != null ? (in_array($request_data[0]['REQUEST_TYPE'], array(2, 3, 5)) ? $request_data[0]['ADAPTER_SERIAL'] : ($request_data[0]['REQUEST_TYPE'] == 4 ? $request_data[0]['PROJECT_SERIAL'] : ($request_data[0]['REQUEST_TYPE'] == 6 ? $request_data[0]['CAR_ID'] : ($request_data[0]['REQUEST_TYPE'] == 7 ? $request_data[0]['COMPUTER_ID'] : '')))) : ($HaveRs ? $rs['SOURCE_ID_NAME'] : "") ?>"
                   id="txt_source_id" class="form-control">
        </div>
    </div>


    <hr>

    <?php if (($request_data != null && $request_data[0]['REQUEST_TYPE'] == 3) || ($HaveRs && $rs['REQUEST_TYPE'] == 3)): ?>
        <div class="form-group  col-sm-1">
            <label class="control-label">التكرار</label>

            <div>

                <select type="text" name="cycle_case" id="dp_cycle_case" class="form-control">
                    <option <?= $HaveRs ? ($rs['CYCLE_CASE'] == 1 ? 'selected' : '') : '' ?> value="1"> لمرة
                        واحدة
                    </option>
                    <option <?= $HaveRs ? ($rs['CYCLE_CASE'] == 2 ? 'selected' : '') : '' ?> value="2"> متكرر
                    </option>
                </select>
            </div>
        </div>

        <div class="form-group  col-sm-1">
            <label class="control-label">دورة التكرار</label>

            <div>

                <select type="text" name="cycle_type" id="dp_cycle_type" class="form-control">
                    <option <?= $HaveRs ? ($rs['CYCLE_TYPE'] == 1 ? 'selected' : '') : '' ?> value="1"> يوم
                    </option>
                    <option <?= $HaveRs ? ($rs['CYCLE_TYPE'] == 2 ? 'selected' : '') : '' ?> value="2"> شهر
                    </option>
                </select>
            </div>
        </div>

        <div class="form-group  col-sm-1">
            <label class="control-label"> التبيه قب (يوم)</label>

            <div>

                <input name="alarm_date_count" value="<?= $HaveRs ? $rs['ALARM_DATE_COUNT'] : "" ?>"
                       id="txt_alarm_date_count" class="form-control">

            </div>
        </div>

    <?php endif; ?>

    <div class="form-group  col-sm-2">
        <label class="control-label"> تاريخ البداية
        </label>

        <div>
            <input name="work_order_start_date" data-val="true"
                   value="<?= $HaveRs ? $rs['WORK_ORDER_START_DATE'] : "" ?>" data-val-required="حقل مطلوب"
                   id="txt_work_order_start_date" data-type="date" data-date-format="DD/MM/YYYY"
                   data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?= date_format_exp() ?>"
                   class="form-control">


        </div>

    </div>


    <div class="form-group  col-sm-2">
        <label class="control-label"> تاريخ النهاية
        </label>

        <div>
            <input name="work_order_end_date" data-val="true"
                   value="<?= $HaveRs ? $rs['WORK_ORDER_END_DATE'] : "" ?>" data-val-required="حقل مطلوب"
                   id="txt_work_order_end_date" data-type="date" data-date-format="DD/MM/YYYY"
                   data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?= date_format_exp() ?>"
                   class="form-control">


        </div>

    </div>


    <hr>
    <div class="form-group col-sm-4">
        <label class="control-label"> المهمة المراد تنفيذها </label>

        <div>
            <input type="text" data-val="true" data-val-required="يجب إدخال البيان "
                   value="<?= $HaveRs ? $rs['JOB_ID'] : "" ?>" name="job_id" id="h_txt_job_id"
                   class="form-control col-md-4">
            <input readonly value="<?= $HaveRs ? $rs['JOB_ID_NAME'] : "" ?>" class="form-control  col-md-8"
                   id="txt_job_id"/>
        </div>
    </div>


    <div class="form-group  col-sm-2">
        <label class="control-label">(دقيقة) الوقت المتوقع للانجاز
        </label>

        <div>
            <input name="job_time_expected" readonly data-val="true"
                   value="<?= $HaveRs ? $rs['JOB_TIME_EXPECTED'] : "" ?>" data-val-required="حقل مطلوب"
                   id="t_txt_job_id" class="form-control">


        </div>

    </div>
    <hr>
    <div id="task-plans" class="bs-callout bs-callout-info">

        <h4>الإجراءات</h4>

        <p>
            <?php if (count($plans) > 0) : ?>
        <ul>
            <?php foreach ($plans as $row) : ?>
                <li><span class="label label-success"><?= $row['TIME_ESTIMATED'] ?></span> <?= $row['PLANE_STEP'] ?>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php endif; ?>
        </p>
    </div>
    <hr>
    <?php if ($action == 'permit'): ?>
        <div class="checks_div" style="display: block;">
            <div class="form-group  col-sm-1">
                <label class="control-label">يحتاج اذن </label>

                <div>

                    <select type="text" name="work_permit" id="dp_work_permit" class="form-control">
                        <option <?= $HaveRs ? ($rs['WORK_PERMIT'] == 1 ? 'selected' : '') : '' ?> value="1">
                            لايحتاج
                        </option>
                        <option <?= $HaveRs ? ($rs['WORK_PERMIT'] == 2 ? 'selected' : '') : '' ?> value="2">
                            يحتاج
                        </option>
                    </select>
                </div>
            </div>
            <div class="form-group  col-sm-7">
                <label class="control-label">توجيهات</label>

                <div>

                    <input name="work_permit_conditions"
                           value="<?= $HaveRs ? $rs['WORK_PERMIT_CONDITIONS'] : "" ?>"
                           id="txt_work_permit_conditions" class="form-control">

                </div>
            </div>
        </div>
        <hr>
    <?php endif; ?>

    <div class="form-group  col-sm-12">
        <label class="control-label">تعليمات أمر العمل
        </label>

        <div>

            <textarea name="instructions" rows="5" id="txt_instructions"
                      class="form-control"><?= $HaveRs ? $rs['INSTRUCTIONS'] : "" ?></textarea>

        </div>
    </div>

    <div class="form-group  col-sm-12">
        <label class="control-label">ملاحظات
        </label>

        <div>

            <textarea name="hints" id="txt_hints"
                      class="form-control"><?= $HaveRs ? $rs['HINTS'] : "" ?></textarea>

        </div>
    </div>


    </div>
    <div class="tab-pane fade" id="workers">
        <?php echo modules::run('technical/WorkOrder/public_get_team', $HaveRs ? $rs['WORK_ORDER_ID'] : 0); ?>

    </div>
    <div class="tab-pane fade" id="tools">
        <?php echo modules::run('technical/WorkOrder/public_get_tools', $HaveRs ? $rs['WORK_ORDER_ID'] : 0); ?>
    </div>
    <div class="tab-pane fade" id="cars">
        <?php echo modules::run('technical/WorkOrder/public_get_cars', $HaveRs ? $rs['WORK_ORDER_ID'] : 0); ?>
    </div>
    </div>
    </div>

    <?php if (isset($TEAM_COST_ROWS)) : ?>
        <div class="col-md-3">

            <div class="alert alert-success" role="alert"><strong>تكلفة الأيدي العاملة</strong>

                <p>
                <table class="table" id="teamTbl" data-container="container">
                    <thead>
                    <tr>

                        <th>نوع الوظيفة</th>
                        <th>العدد</th>
                        <th>الوقت / ساعة</th>
                        <th>التكلفة</th>

                    </tr>
                    </thead>

                    <tbody>
                    <?php foreach ($TEAM_COST_ROWS as $row) : ?>
                        <tr>
                            <?php $TEAM_COST = $TEAM_COST + $row['COST_TOTAL']; ?>
                            <td><?= $row['JOB_NAME'] ?></td>
                            <td><?= $row['WORKER_COUNT'] ?></td>
                            <td><?= round($row['JOB_TOTAL_TIME'] / 60, 2) ?> </td>
                            <td><?= $row['COST_TOTAL'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="3"></td>
                        <td><?= $TEAM_COST ?></td>

                    </tr>
                    </tfoot>
                </table>
                </p>
            </div>


            <div class="alert alert-warning" role="alert"><strong>تكلفة المواد المطلوبة</strong>

                <p>
                <table class="table" id="teamTbl" data-container="container">
                    <thead>
                    <tr>


                        <th>العدد</th>

                        <th>التكلفة</th>

                    </tr>
                    </thead>

                    <tbody>
                    <?php foreach ($TOOLS_COST_ROWS as $row) : ?>
                        <tr>
                            <?php $TOOLS_COST = $TOOLS_COST + $row['COST_TOTAL']; ?>
                            <td><?= $row['TOTAL_COUNT'] ?></td>

                            <td><?= $row['COST_TOTAL'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td></td>
                        <td><?= $TOOLS_COST ?></td>

                    </tr>
                    </tfoot>
                </table>
                </p>
            </div>


            <div class="alert alert-danger" role="alert"><strong>تكلفة الاليات المطلوبة</strong>

                <p>
                <table class="table" id="teamTbl" data-container="container">
                    <thead>
                    <tr>

                        <th>الألية</th>
                        <th>العدد</th>

                        <th>التكلفة</th>

                    </tr>
                    </thead>

                    <tbody>
                    <?php foreach ($CARS_COST_ROWS as $row) : ?>
                        <tr>
                            <?php $CARS_COST = $CARS_COST + $row['COST_TOTAL']; ?>
                            <td><?= $row['CAR_NAME'] ?></td>
                            <td><?= $row['CAR_COUNT'] ?></td>

                            <td><?= $row['COST_TOTAL'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="2"></td>
                        <td><?= $CARS_COST ?></td>

                    </tr>
                    </tfoot>
                </table>
                </p>
            </div>

            <div class="alert alert-info" role="alert"><strong>إجمالي التكلفة </strong>

                <p>
                <table class="table" id="teamTbl" data-container="container">
                    <thead>
                    <tr>

                        <th>الأيدي العاملة</th>
                        <th>المواد</th>

                        <th>الأليات</th>

                    </tr>
                    </thead>

                    <tbody>

                    <tr>

                        <td><?= $TEAM_COST ?></td>
                        <td><?= $TOOLS_COST ?></td>
                        <td><?= $CARS_COST ?></td>
                    </tr>

                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="2"></td>
                        <td><?= $TEAM_COST + $TOOLS_COST + $CARS_COST ?></td>

                    </tr>
                    </tfoot>
                </table>
                </p>
            </div>
        </div>
    <?php endif; ?>
    </div>
    <div class="modal-footer">

        <?php if (($HaveRs && $rs['WORK_PERMIT'] == null) || !$HaveRs): ?>
            <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
        <?php endif; ?>

    </div>
    </form>

    </div>

    </div>
    </div>

<?php
$delete_tools_url = base_url('technical/WorkOrder/delete_tools');
$delete_works_url = base_url('technical/WorkOrder/delete_works');
$adapters_url = base_url('projects/adapter/public_index');
$WorkOrder_url = base_url('technical/HighPowerPartition/public_index');
$select_items_url = (($request_data != null && $request_data[0]['REQUEST_TYPE'] == 4) || ($HaveRs && $rs['REQUEST_TYPE'] == 4)) ? base_url("projects/projects/public_get_project_index/{$source_id}") : base_url("stores/classes/public_index");

$customer_url = base_url('payment/customers/public_index');

$get_Tjob_url = base_url('technical/Technical_jobs/public_index');

$public_select_project_url = base_url('projects/projects/public_select_project');
$public_select_Argent_Maintenance_url = base_url('technical/Argent_Maintenance/public_index');

$public_get_all_details_url = base_url('technical/Technical_jobs/public_get_all_details');

$create_url = base_url('');

$adapter_url = base_url('projects/adapter/public_index');

$HasPlan = count($plans) > 0;

$shared_script = <<<SCRIPT

      $('#txt_project_tec_code,#txt_argent_maintenance_id').click(function(){

        var type = $('#dp_work_order_type').val();

        if(type == 1){
          _showReport('$public_select_project_url/'+$(this).attr('id'));
        }else if(type == 2){

   _showReport('$public_select_Argent_Maintenance_url/'+$(this).attr('id'));
        }

      });

        $('#txt_source_id').click(function(){
            _showReport('{$adapter_url}/'+$(this).attr('id')+'/');
        });

      $('#dp_work_order_type').change(function(){

            var type = $(this).val();

            if(type != 1 && type != 2){
                $('#project_div input,#agent_maintenace_div').val('');
                $('#project_div,#agent_maintenace_div').hide();
            }else if(type == 1){
                $('#agent_maintenace_div').val('');
                $('#agent_maintenace_div').hide();
            }else if(type == 2){
                $('#project_div input').val('');
                $('#project_div').hide();
            }


            if(type == 1){
                $('#project_div').show();
            }

            if(type == 2){
                $('#agent_maintenace_div').show();
            }


      });

      $('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        if(confirm('هل تريد حفظ السند ؟!')){
            var form = $(this).closest('form');
            ajax_insert_update(form,function(data){

                success_msg('رسالة','تم حفظ البيانات بنجاح ..');

                //reload_Page();
                 get_to_link('{$get_url}/'+data);

            },'html');
        }
    });

    $('#txt_job_id').click(function(){
        _showReport('$get_Tjob_url/'+$(this).attr('id'));

    });

reBind();

function reBind(){
        $('input[name="class_name[]"]').click("focus",function(e){
        _showReport('$select_items_url/'+$(this).attr('id'));

    });

     $('input[id*="txt_d_employee_id"]').click(function(){

_showReport('$customer_url/'+$(this).attr('id')+'/3');

            });

    }



 $('#h_txt_class_id,#h_txt_base_class_id').keyup(function(){
    $('#'+$(this).attr('id').replace('h_','')).val('');
 });


  function AddRowWithDataWork(id,name_ar,price,sale_price,unit,unit_name,count){

        if($('input[name="class_id[]"][value="'+id+'"]').length <= 0){

               if($('input[name="class_id[]"]', $('#toolsTbl > tbody > tr:last')).val() != '')
                add_row($('#toolsTbl tfoot a'),'input',false);



                $('input[name="class_id[]"]', $('#toolsTbl > tbody > tr:last')).val(id);

                $('input[ id^="txt_class_id_"]', $('#toolsTbl > tbody > tr:last')).val(name_ar);

                $('input[ id^="unit_txt_class_id_"]', $('#toolsTbl > tbody > tr:last')).val(unit);
                $('input[ id^="unit_name_txt_class_id_"]', $('#toolsTbl > tbody > tr:last')).val(unit_name);
                $('input[ id^="count_txt_class_id_"]', $('#toolsTbl > tbody > tr:last')).val(count);

            }
    }


 function delete_details(a,id,url){
             if(confirm('هل تريد حذف البند ؟!')){

                  get_data(url,{id:id},function(data){
                             if(data == '1'){

                                restInputs($(a).closest('tr'));

                               }else{
                                     danger_msg( 'تحذير','فشل في العملية');
                               }
                        });
                 }
         }


         function get_jobs_data(){
                  get_data('{$public_get_all_details_url}',{id:$('#h_txt_job_id').val()},function(data){
                             var cars = data.CARS;
                             var works = data.WORKERS;
                             var tools = data.TOOLS;

                            $.each(cars,function(i){

                               $('#txt_car_id_'+i,$('#carsTbl')).val(cars[i].CAR_ID);
                               $('#txt_car_count_'+i,$('#carsTbl')).val(cars[i].CAR_COUNT);
                               $('#txt_need_description_'+i,$('#carsTbl')).val(cars[i].NEED_DESCRIPTION);
                               if(cars.length > i+1)
                                    add_row('#carsTbl tfoot a','input',false);
                            });

                             $.each(works,function(i){

                               $('#txt_worker_job_id_'+i,$('#teamTbl')).val(works[i].WORKER_JOB_ID);
                               $('#txt_worker_count_'+i,$('#teamTbl')).val(works[i].WORKER_COUNT);
                               $('#txt_task_'+i,$('#teamTbl')).val(works[i].TASK);
                               if(works.length > i+1)
                                    add_row('#teamTbl tfoot a','input',false);

                            });

                             $.each(tools,function(i){
                                    console.log('',tools[i]);
                               $('#h_txt_class_id_'+i,$('#toolsTbl')).val(tools[i].CLASS_ID);
                               $('#txt_class_id_'+i,$('#toolsTbl')).val(tools[i].CLASS_ID_NAME);
                               $('#txt_class_count_'+i,$('#toolsTbl')).val(tools[i].CLASS_COUNT);
                               $('#unit_txt_d_class_id_'+i,$('#toolsTbl')).val(tools[i].CLASS_UNIT);
                               $('#unit_name_txt_d_class_id_'+i,$('#toolsTbl')).val(tools[i].CLASS_UNIT_NAME);
                               if(tools.length > i+1)
                                    add_row('#toolsTbl tfoot a','input',false);
                            });


                            if(data.PLANE.length > 0 ){

                                var html = '<ul>';
                                     $.each(data.PLANE,function(i){

                                            html = html + ' <li> <span class="label label-success">'+data.PLANE[i].TIME_ESTIMATED+'</span> '+data.PLANE[i].PLANE_STEP+' </li>';

                                     });


                                html = html + '</ul>';
                                $('#task-plans p').html(html);
                                $('#task-plans').slideDown();
                            }else {
                                $('#task-plans p').text('');
                                $('#task-plans').slideUp();
                            }


                        });

         }




SCRIPT;


$create_script = <<<SCRIPT
    <script>
        {$shared_script}
    </script>
SCRIPT;


$edit_script = <<<SCRIPT
    <script>
          var permit = '$action';
          if(permit == 'permit'){

                $('#WorkOrder_form input,#WorkOrder_form select,#WorkOrder_form textarea').not('#txt_work_order_id,#dp_work_permit,#txt_work_permit_conditions').prop('disabled',true);
          }
          if({$HasPlan} == 1 ){
          $('#task-plans').slideDown();
    }

        {$shared_script}
    </script>
SCRIPT;

if ($HaveRs)
    sec_scripts($edit_script);
else
    sec_scripts($create_script);

?>