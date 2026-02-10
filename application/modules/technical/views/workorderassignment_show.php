<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 11/01/15
 * Time: 11:28 ص
 */
$back_url = $action == 'permit' ? base_url('technical/WorkOrderAssignment/permit_index') : base_url('technical/WorkOrderAssignment');
$get_url = base_url('technical/WorkOrderAssignment/get/');
$report_url = 'http://itdev:801/gfc.aspx?data=' . get_report_folder() . '&';
if (!isset($result))
    $result = array();
$HaveRs = count($result) > 0;

$rs = $HaveRs ? $result[0] : $result;
$CanAdopt = $HaveRs ? ($rs['WORK_ORDER_CASE'] == 1 && $action == 'adopt' ? true : ($rs['WORK_ORDER_CASE'] == 2 && $action == 'feedbackRegisterAdopt') ? true : ($rs['WORK_ORDER_CASE'] == 3 && $action == 'feedbackadopt') ? true : false) : false;


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
<form class="form-form-vertical" id="WorkOrderAssignment_form" method="post"
      action="<?= base_url('technical/WorkOrderAssignment/' . ($HaveRs ? ($action == 'index' ? 'edit' : $action) : 'create')) ?>"
      role="form" novalidate="novalidate">
<div class="modal-body inline_form">
<div class="<?= isset($TEAM_COST_ROWS) ? 'col-md-9' : '' ?>">
    <ul id="myTab" class="nav nav-tabs">
        <li class="active"><a href="#home" data-toggle="tab">
                بيانات تكليف العمل</a>
        </li>
        <li><a href="#workOrder" data-toggle="tab">أوامر العمل</a></li>
        <li><a href="#workers" data-toggle="tab">فريق العمل</a></li>
        <li><a href="#tools" data-toggle="tab">المواد </a></li>
        <li><a href="#cars" data-toggle="tab"> الأليات</a></li>

    </ul>
    <div id="myTabContent" class="tab-content">
        <div class="tab-pane fade in active" id="home">
            <div class="form-group col-sm-1">
                <label class="control-label"> م. </label>

                <div>
                    <input type="text" readonly
                           value="<?= $HaveRs ? $rs['WORK_ORDER_ASSIGNMENT_ID'] : "" ?>"
                           name="work_order_assignment_id" id="txt_work_order_assignment_id"
                           class="form-control">
                </div>
            </div>

            <div class="form-group col-sm-1">
                <label class="control-label"> رقم التكليف </label>

                <div>
                    <input type="text" readonly value="<?= $HaveRs ? $rs['WORK_ASSIGNMENT_CODE'] : "" ?>"
                           name="request_id" id="txt_request_id" class="form-control">
                </div>
            </div>

            <div class="form-group col-sm-5">
                <label class="control-label"> عنوان التكليف </label>

                <div>
                    <input type="text" data-val="true" data-val-required="يجب إدخال البيان "
                           value="<?= $HaveRs ? $rs['TITLE'] : "" ?>" name="title" id="txt_title"
                           class="form-control">
                </div>
            </div>
            <hr>

            <div class="form-group  col-sm-1">
                <label class="control-label">القسم</label>

                <div>

                    <select type="text" name="work_order_department" id="dp_work_order_department"
                            class="form-control">

                        <?php foreach ($WORK_ORDER_DEPARTMENT as $row) : ?>
                            <option <?= $HaveRs ? ($rs['WORK_ORDER_DEPARTMENT'] == $row['CON_NO'] ? 'selected' : '') : '' ?>
                                value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group col-sm-4">
                <label class="control-label"> الفرقة الفنية </label>

                <div>
                    <input type="text" data-val="true" data-val-required="يجب إدخال البيان "
                           value="<?= $HaveRs ? $rs['TEAM_ID'] : "" ?>" name="team_id"
                           id="h_txt_team_id" class="form-control col-md-4">
                    <input type="text" readonly data-val="true"
                           data-val-required="يجب إدخال البيان "
                           value="<?= $HaveRs ? $rs['TEAM_ID_NAME'] : "" ?>" id="txt_team_id"
                           class="form-control col-md-8">

                </div>
            </div>

            <div class="form-group col-sm-2">
                <label class="control-label"> ساعة الخروج</label>

                <div>
                    <!--                                    <input type="text" data-val="true" data-val-required="يجب إدخال البيان "    value="-->
                    <? //= $HaveRs? $rs['TIME_OUT'] : "" ?><!--"  name="time_out" id="txt_time_out" data-type="datetime"  data-date-format="DD/MM/YYYY HH:mm"   data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="-->
                    <? //= datetime_format_exp() ?><!--" class="form-control ltr">-->
                    <input type="text" data-val="true" data-val-required="يجب إدخال البيان "
                           value="<?= $HaveRs ? $rs['TIME_OUT'] : "" ?>" name="time_out"
                           id="txt_time_out" data-type="datetime"
                           data-date-format="DD/MM/YYYY HH:mm" readonly class="form-control ltr">


                </div>
            </div>

            <!--<div class="form-group  col-sm-2">
                                    <label class="control-label">ساعة العودة</label>
                                    <div>
                                        <input type="text" data-val="true" data-val-required="يجب إدخال البيان "    value="<? /*= $HaveRs? $rs['TIME_RETURN'] : "" */ ?>"  name="time_return" id="txt_time_return" data-type="datetime"  data-date-format="DD/MM/YYYY HH:mm""   data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<? /*= datetime_format_exp() */ ?>" class="form-control ltr">

                                    </div>
                                </div>-->
            <hr>
            <div class="form-group  col-sm-12">
                <label class="control-label">المشروحات </label>

                <div>
                    <textarea name="manager_explain" data-val="true"
                              data-val-required="يجب إدخال البيان " id="txt_manager_explain"
                              class="form-control"><?= $HaveRs ? $rs['MANAGER_EXPLAIN'] : "" ?></textarea>
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


        </div>
        <div class="tab-pane fade" id="workOrder">
            <?php echo modules::run('technical/WorkOrderAssignment/public_get_WOrder', $HaveRs ? $rs['WORK_ORDER_ASSIGNMENT_ID'] : 0); ?>

        </div>
        <div class="tab-pane fade" id="workers">
            <?php echo modules::run('technical/WorkOrderAssignment/public_get_team', $HaveRs ? $rs['WORK_ORDER_ASSIGNMENT_ID'] : 0); ?>

        </div>
        <div class="tab-pane fade" id="tools">
            <?php echo modules::run('technical/WorkOrderAssignment/public_get_tools', $HaveRs ? $rs['WORK_ORDER_ASSIGNMENT_ID'] : 0); ?>
        </div>
        <div class="tab-pane fade" id="cars">
            <?php echo modules::run('technical/WorkOrderAssignment/public_get_cars', $HaveRs ? $rs['WORK_ORDER_ASSIGNMENT_ID'] : 0); ?>
        </div>

    </div>
</div>
<?php if (isset($TEAM_COST_ROWS)) : ?>
    <div class="col-md-3">

        <div class="alert alert-success" role="alert"><strong>تكلفة المال و الوقت</strong>

            <p>
            <table class="table" id="teamTbl" data-container="container">
                <thead>
                <tr>

                    <th>الموظف </th>

                    <th>الوقت / ساعة</th>
                    <th>التكلفة</th>

                </tr>
                </thead>

                <tbody>
                <?php foreach ($TEAM_COST_ROWS as $row) : ?>
                    <tr>
                        <?php $TEAM_COST = $TEAM_COST + $row['COST_TOTAL']; ?>
                        <td><?= $row['EMP_NAME'] ?></td>

                        <td><?=  round($row['TOTAL_TIME'] /60) ?>  </td>
                        <td><?= $row['COST_TOTAL'] ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="2"></td>
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


        <div class="alert alert-danger" role="alert"><strong>تكلفة الأليات المطلوبة</strong>

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
<hr>
<div style="clear: both;">
    <?php echo modules::run('settings/notes/public_get_page', $HaveRs ? $rs['WORK_ORDER_ASSIGNMENT_ID'] : 0, 'work_order_assignment'); ?>
    <?php echo $HaveRs ? modules::run('attachments/attachment/index', $rs['WORK_ORDER_ASSIGNMENT_ID'], 'work_order_assignment') : ''; ?>
</div>

<div class="modal-footer">

    <?php if (((!$HaveRs || (isset($can_edit) && $can_edit)) && ($action == 'index'))): ?>
        <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>

    <?php endif; ?>

    <?php if ($HaveRs && $rs['WORK_ORDER_CASE'] == -1 && $action == 'index' && (isset($can_edit) && $can_edit)): ?>
        <button type="button" onclick="javascript:return_adopt(1);" class="btn btn-success"> إعتمد
        </button>
    <?php endif; ?>
    <?php if ($CanAdopt): ?>
        <button type="button" onclick="javascript:return_adopt(1);" class="btn btn-success"> إعتمد
        </button>
        <button type="button" onclick="javascript:return_adopt(0);" class="btn btn-danger">إرجاع
        </button>
    <?php endif; ?>
    <?php if ($HaveRs): ?>
        <button type="button"
                onclick="javascript:_showReport('<?= base_url("JsperReport/showreport?sys=technical&report=tec_workorder_ass_report&id={$rs['WORK_ORDER_ASSIGNMENT_ID']}") ?>');"
                class="btn btn-default"> طباعة
        </button>
    <?php endif; ?>

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


$get_Tjob_url = base_url('technical/Technical_jobs/public_index');

$public_select_project_url = base_url('projects/projects/public_select_project');

$public_select_team_url = base_url('technical/branches_teams/public_index');

$public_get_all_details_url = base_url('technical/branches_teams/public_get_details_json');

$public_workorder_data_url = base_url('technical/WorkOrder/public_get_workorder_data_json');

$public_WorkOrder_url = base_url('technical/WorkOrder/public_index');

$customer_url = base_url('technical/branches_teams/public_team_index');

$loads_url = base_url('technical/Worker_Order_Loads/index/');

$shared_script = <<<SCRIPT

   $('#txt_team_id').click(function(){
    _showReport('$public_select_team_url/'+$(this).attr('id'));
});


function afterSelect(tr,id,type){

    if(type == 4){
        $('a._loads',tr).show();
        $('a._loads',tr).attr('href',"javascript:_showReport('{$loads_url}/"+id+"')");
    } else {
        $('a._loads',tr).hide();
        $('a._loads',tr).attr('href',"javascript:;");
    }

      var ids = [];

    if(tr == null )
        {
                $('input[name="work_order_id[]"]').each(function(i){
                if($(this).val() != '')
                    ids.push($(this).val());
                });
        }else {
        ids.push(id);
        }


    get_data('{$public_workorder_data_url}',{id: ids },function(data){


        var cars = data.CARS;
        var jobs = data.JOBS;
        var tools = data.TOOLS;
        var teams = data.TEAMS;

        //$('#teamTbl tbody tr').slice(1).remove();


        $.each(cars,function(i){
            var ix = i;
            if($('select[name="car_id[]"] option[value="'+cars[i].CAR_ID+'"][selected]').length <= 0){


             if($('input[name="car_count[]"]', $('#carsTbl > tbody > tr:last')).val() != '')
                add_row($('#carsTbl tfoot a'),'input',false);


                $('select[id^="txt_car_id_"]',$('#carsTbl > tbody > tr:last')).val(cars[i].CAR_ID);
                $('select[id^="txt_car_id_"] option[value="'+cars[i].CAR_ID+'"]',$('#carsTbl > tbody > tr:last')).attr('selected',true);
                $('input[id^="txt_car_count_"]',$('#carsTbl > tbody > tr:last')).val(cars[i].CAR_COUNT);
                $('input[id^="txt_need_description_"]',$('#carsTbl > tbody > tr:last')).val(cars[i].NEED_DESCRIPTION);
            }


        });

         $.each(teams,function(i){
            var ix = i;
            if($('input[name="t_worker_job_id[]"][value="'+teams[i].WORKER_JOB_ID+'"]').length <= 0){


             if($('input[name="t_worker_job_id[]"]', $('#teamTbl > tbody > tr:last')).val() != '')
                add_row($('#teamTbl tfoot a'),'input',false);

                $('input[id^="h_txt_worker_job_id_"]',$('#teamTbl > tbody > tr:last')).val(teams[i].WORKER_JOB_ID);
                $('input[id^="txt_worker_job_id_"]',$('#teamTbl > tbody > tr:last')).val(teams[i].WORKER_JOB_ID_NAME);

            }


        });

         $.each(jobs,function(i){

                $('input[name="work_order_id[]"][value="'+jobs[i].id+'"]').closest('tr').find('textarea[name="action_procedure[]"]').append(jobs[i].PLANE_STEP+'\\n');

         });

        $.each(tools,function(i){
            var ix = i;
            if($('input[name="class_id[]"][value="'+tools[i].CLASS_ID+'"]').length <= 0){

               if($('input[name="class_id[]"]', $('#toolsTbl > tbody > tr:last')).val() != '')
                add_row($('#toolsTbl tfoot a'),'input',false);



                $('input[name="class_id[]"]', $('#toolsTbl > tbody > tr:last')).val(tools[i].CLASS_ID);

                $('input[ id^="txt_class_id_"]', $('#toolsTbl > tbody > tr:last')).val(tools[i].CLASS_ID_NAME);
                $('input[ id^="txt_class_count_"]', $('#toolsTbl > tbody > tr:last')).val(tools[i].CLASS_COUNT);
                $('input[ id^="unit_txt_class_id_"]', $('#toolsTbl > tbody > tr:last')).val(tools[i].CLASS_UNIT);
                $('input[ id^="unit_name_txt_class_id_"]', $('#toolsTbl > tbody > tr:last')).val(tools[i].CLASS_UNIT_NAME);
                $('input[ name="tl_work_order[]"]', $('#toolsTbl > tbody > tr:last')).val(tools[i].WORK_ORDER_ID);
                $('input[ name="work_order_id_name[]"]', $('#toolsTbl > tbody > tr:last')).val(tools[i].WORK_ORDER_CODE);
                $('select[ name="class_type[]"]', $('#toolsTbl > tbody > tr:last')).val(tools[i].CLASS_TYPE);


            }
        });


    });

}

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
    $('input[id^="txt_class_id"]').click("focus",function(e){
        _showReport('$select_items_url/'+$(this).attr('id'));

    });

    $('input[id^="txt_worker_job_id"]').click(function(){

        _showReport('$customer_url/'+$(this).attr('id')+'/3');

    });

    $('input[id^="txt_work_order_id"]').click(function(){
        _showReport('$public_WorkOrder_url/'+$(this).attr('id'));
    });


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


function get_team_data(){

    /*if($('input[name="t_worker_job_id[]"]').length > 1)
        return;*/

    /*get_data('{$public_get_all_details_url}',{id:$('#h_txt_team_id').val()},function(data){

        $.each(data,function(i){

            $('#h_txt_worker_job_id_'+i,$('#teamTbl')).val(data[i].CUSTOMER_ID);
            $('#txt_worker_job_id_'+i,$('#teamTbl')).val(data[i].CUSTOMER_ID_NAME);

            $('#h_txt_worker_job_'+i,$('#teamTbl')).val(data[i].WORKER_JOB);

            if(data.length > i+1)
                add_row('#teamTbl tfoot a','input',false);

        });



    });*/

}


var count = 1;

$(function(){ count = $('input[name="work_order_id[]"]').length;});


function AddRowWithData(id , name ,arg0,arg1,type){


        if(id.length >= 9 || arg1 == 'team'){

            if($('input[name="t_worker_job_id[]"][value="'+id+'"]').length <= 0){

                if($('input[name="t_worker_job_id[]"]', $('#teamTbl > tbody > tr:last')).val() != '')
                    add_row($('#teamTbl tfoot a'),'input',false);



                $('input[name="t_worker_job_id[]"]', $('#teamTbl > tbody > tr:last')).val(id);
                $('input[name="t_worker_job_id[]"]', $('#teamTbl > tbody > tr:last')).attr('value',id);
                $('input[id ^="txt_worker_job_id"]', $('#teamTbl > tbody > tr:last')).val(name);
                $('input[id ^="mob_txt_worker_job_id"]', $('#teamTbl > tbody > tr:last')).val(arg0);
                count++;
            }
        } else {

                if($('input[name="work_order_id[]"][value="'+id+'"]').length <= 0){

                    if($('input[name="work_order_id[]"]', $('#workorderTbl > tbody > tr:last')).val() != '')
                        add_row($('#workorderTbl tfoot a'),'input',false);


                    $('#txt_work_order_id_'+(count -1)).val(name);
                    $('#txt_work_order_id_'+(count -1)).attr('data-type',type);
                    $('#h_txt_work_order_id_'+(count -1)).val(id);
                    $('#h_txt_work_order_id_'+(count -1)).attr('value',id);
                    $('#action_start_txt_work_order_id_'+(count -1)).val(arg0);
                    $('#action_end_txt_work_order_id_'+(count -1)).val(arg1);
                    count++;

                    //afterSelect($('tr:last'),id,type);
                }
        }
}


SCRIPT;


$create_script = <<<SCRIPT
    <script>
        {$shared_script}
    </script>
SCRIPT;

$notes_url = notes_url();
$public_return_url = base_url('technical/WorkOrderAssignment/public_return');
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

                     get_data($('#WorkOrderAssignment_form').attr('action').replace('edit','index'),{id:{$WORK_ORDER_ASSIGNMENT_ID}},function(data){
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

