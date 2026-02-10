<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 8/27/14
 * Time: 8:46 AM
 */
$cancel_url = base_url('technical/WorkOrderAssignment/cancel');
$get_url = base_url('technical/WorkOrderAssignment/get_id');
$edit_url = base_url('technical/WorkOrderAssignment/edit');
$create_url = base_url('technical/WorkOrderAssignment/create');
$get_page_url = base_url('technical/WorkOrderAssignment/get_page_archive');

$public_select_adapter_url = base_url('projects/adapter/public_index');

?>
<?= AntiForgeryToken() ?>
<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
            <?php if (HaveAccess($create_url)): ?>
                <li><a href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>جديد </a> </li><?php endif; ?>

            <!--     <li><a href="#">بحث</a> </li>-->
        </ul>

    </div>

    <div class="form-body">
        <fieldset>
            <legend>بحـث</legend>

            <div class="modal-body inline_form">

                <div class="form-group  col-sm-1">
                    <label class="control-label">الفرع </label>

                    <div>
                        <select name="branch" id="dp_branch" class="form-control">
                            <option></option>
                            <?php foreach ($branches as $row) : ?>
                                <option value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">رقم التكليف</label>

                    <div>
                        <input type="text" name="work_assignment_code" id="txt_work_assignment_code"
                               class="form-control">
                    </div>
                </div>


                <div class="form-group col-sm-2">
                    <label class="control-label">تكليف العمل</label>

                    <div>
                        <input type="text" name="title" id="txt_title" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">الفرقة</label>

                    <div>
                        <input type="text" name="team_id" id="txt_team_id" class="form-control">
                    </div>
                </div>
                <div class="form-group  col-sm-1">
                    <label class="control-label">نوع التكليف</label>
                    <div>

                        <select type="text"   name="work_order_type" id="dp_work_order_type" class="form-control" >
                            <option></option>
                            <?php foreach($WORK_ORDER_TYPE as $row) :?>
                                <option  value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group col-sm-2">
                    <label class="control-label">المهمة</label>
                    <div>
                        <input type="text"  name="task" id="txt_task"   class="form-control">
                    </div>
                </div>
                <hr>

                <div class="form-group col-sm-1">
                    <label class="control-label">رقم الطلب</label>

                    <div>
                        <input type="text" name="request_code" id="txt_request_code" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">رقم المشروع</label>

                    <div>
                        <input type="text" name="project_tec" id="txt_project_tec" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label"> المشروع</label>

                    <div>
                        <input type="text" name="project_name" id="txt_project_name" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">من تاريخ</label>

                    <div>
                        <input type="text" data-type="date" data-date-format="DD/MM/YYYY" name="from_date"
                               id="txt_from_date" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label"> الي تاريخ</label>

                    <div>
                        <input type="text" data-type="date" data-date-format="DD/MM/YYYY" name="t_date" id="txt_to_date"
                               class="form-control">
                    </div>
                </div>
                <hr>

                <div>
                    <div class="form-group col-sm-3">
                        <label class="control-label">رقم المحول</label>

                        <div>
                            <input type="text" readonly name="adapter_serial" id="h_adapter_serial" class="form-control"
                                   style="width: 30%;display: inline;">

                            <input type="text" readonly
                                   name="adapter_serial_name" id="adapter_serial" class="form-control"
                                   style="width: 50%;display: inline;">

                            <button type="button" style="width: 18%" onclick="javascript:select_adapter();"
                                    class="btn blue"><i class="icon icon-search"> </i></button>
                        </div>
                    </div>

                </div>

                <div class="form-group col-sm-4">
                    <label class="col-sm-12 control-label">الموقع (خريطة) في محيط كيلو</label>

                    <div class="col-sm-6">
                        <input type="text" name="x" id="txt_x" class="form-control">

                    </div>
                    <div class="col-sm-6">
                        <input type="text" name="y" id="txt_y" class="form-control">

                    </div>
                </div>
                <div class="form-group col-sm-1">
                    <label class="col-sm-12 control-label" style="height: 25px;"> </label>
                    <button type="button" class="btn green"
                            onclick="javascript:_showReport('<?= base_url("maps/public_map/txt_x/txt_y") ?>');">
                        <i class="icon icon-map-marker"></i>
                    </button>

                </div>

            </div>

            <div class="modal-footer">

                <button type="button" onclick="javascript:do_search();" class="btn btn-success">بحث</button>
                <div class="btn-group">
                    <button class="btn btn-warning dropdown-toggle"
                            onclick="$('#projectTbl').tableExport({type:'excel',escape:'false'});"
                            data-toggle="dropdown"><i class="fa fa-bars"></i> تصدير
                    </button>
                </div>
                <button type="button" onclick="javascript:clearForm_any($('fieldset'));do_search();"
                        class="btn btn-default">تفريغ الحقول
                </button>

            </div>
        </fieldset>

        <div id="msg_container"></div>

        <div id="container">
            <?php echo modules::run('technical/WorkOrderAssignment/get_page_archive', $page, $case, $action); ?>
        </div>

    </div>

</div>




<?php


$scripts = <<<SCRIPT

<script>

    $(function(){
        reBind();
    });

    function reBind(){

    ajax_pager({action :'$action', task : $('#txt_task').val(),from_date:$('#txt_from_date').val(),to_date:$('#txt_to_date').val(),request_code:$('#txt_request_code').val(),project_tec:$('#txt_project_tec').val(),project_name:$('#txt_project_name').val(),work_order_type:$('#dp_work_order_type').val(),adapter_serial:$('#h_adapter_serial').val() , x:$('#txt_x').val() , y:$('#txt_y').val() ,branch : $('#dp_branch').val(), title:$('#txt_title').val() , team_id: $('#txt_team_id').val()  ,work_assignment_code : $('#txt_work_assignment_code').val()});

    }

    function LoadingData(){

    ajax_pager_data('#projectTbl > tbody',{action :'$action', task : $('#txt_task').val(),from_date:$('#txt_from_date').val(),to_date:$('#txt_to_date').val(),request_code:$('#txt_request_code').val(),project_tec:$('#txt_project_tec').val(),project_name:$('#txt_project_name').val(),work_order_type:$('#dp_work_order_type').val() ,adapter_serial:$('#h_adapter_serial').val() , x:$('#txt_x').val() , y:$('#txt_y').val(),branch : $('#dp_branch').val() , title:$('#txt_title').val() , team_id: $('#txt_team_id').val()  ,work_assignment_code : $('#txt_work_assignment_code').val()});

    }


function select_adapter(){
_showReport('$public_select_adapter_url/adapter_serial');
}


   function do_search(){

        get_data('{$get_page_url}',{page: 1,action :'$action', task : $('#txt_task').val(),from_date:$('#txt_from_date').val(),to_date:$('#txt_to_date').val(),request_code:$('#txt_request_code').val(),project_tec:$('#txt_project_tec').val(),project_name:$('#txt_project_name').val(),work_order_type:$('#dp_work_order_type').val(),adapter_serial:$('#h_adapter_serial').val() , x:$('#txt_x').val() , y:$('#txt_y').val() ,branch : $('#dp_branch').val(), title:$('#txt_title').val() , team_id: $('#txt_team_id').val()  ,work_assignment_code : $('#txt_work_assignment_code').val()},function(data){
            $('#container').html(data);

            reBind();

        },'html');
    }

    function cancel_Assignment(a,id){
     if(confirm('هل تريد الغاء التكليف ؟')){
           get_data('{$cancel_url}',{id:id},function(data){

                if(data == '1'){
               success_msg('رسالة','تم الغاء التكليف بنجاح ..');

                }

            },'html');
        }
    }
</script>
SCRIPT;

sec_scripts($scripts);



?>
