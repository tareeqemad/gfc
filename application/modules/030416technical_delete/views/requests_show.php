<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 11/01/15
 * Time: 11:28 ص
 */
$back_url = base_url('technical/Requests/archive');
$get_url = base_url('technical/requests/get/');
$create_url = base_url('technical/Requests/create');
$public_select_project_url = base_url('projects/projects/public_select_project');

if (!isset($result))
    $result = array();
$HaveRs = count($result) > 0;

$rs = $HaveRs ? $result[0] : $result;

?>

    <div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>
        <ul>
            <?php if (HaveAccess($create_url)): ?>
                <li><a href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>جديد </a>
                </li><?php endif; ?>
            <?php if (HaveAccess($back_url)): ?>
                <li><a href="<?= $back_url ?>"><i class="icon icon-reply"></i> </a> </li><?php endif; ?>
            <li><a onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a>
            </li>
        </ul>


    </div>

    <div class="form-body">

    <div id="msg_container"></div>
    <div id="container">
    <form class="form-form-vertical" id="hpp_form" method="post"
          action="<?= base_url('technical/Requests/' . ($HaveRs ? ($action == 'feedback_workorder' ? 'feedback_workorder' : 'edit') : 'create')) ?>"
          role="form"
          novalidate="novalidate">
    <div class="modal-body inline_form">

    <div class="form-group col-sm-1">
        <label class="control-label"> م. </label>

        <div>
            <input type="text" readonly value="<?= $HaveRs ? $rs['REQUEST_ID'] : "" ?>"
                   name="request_id" id="txt_request_id" class="form-control">
        </div>
    </div>


    <div class="form-group col-sm-1">
        <label class="control-label"> رقم الطلب </label>

        <div>
            <input type="text" readonly value="<?= $HaveRs ? $rs['REQUEST_CODE'] : "" ?>"
                   name="request_code" id="txt_request_code" class="form-control">
        </div>
    </div>


    <div class="form-group col-sm-1">
        <label class="control-label"> تاريخ </label>

        <div class="">
            <input type="text" readonly name="request_date"
                   value="<?= $HaveRs ? $rs['REQUEST_DATE_H'] : date('d/m/Y') ?>"
                     data-val-regex="التاريخ غير صحيح!"
                    data-val="true"
                   data-val-required="حقل مطلوب" id="txt_request_date" class="form-control ltr ">
        </div>
    </div>

    <div class="form-group  col-sm-2">
        <label class="control-label">نوع الطلب</label>

        <div>
            <select type="text" <?= $action != 'index' && $HaveRs ? 'disabled' : '' ?>
                    name="requests_type" id="dp_requests_type" class="form-control">
                <option></option>
                <?php foreach ($REQUESTS_TYPE as $row) : ?>
                    <option <?= $HaveRs ? ($row['CON_NO'] == $rs['REQUEST_TYPE'] ? 'selected' : '') : '' ?>
                        value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="form-group col-sm-4">
        <label class="control-label"> البيان </label>

        <div>
            <input type="text" value="<?= $HaveRs ? $rs['PURPOSE_DESCRIPTION'] : "" ?>"
                   name="purpose_description" id="txt_purpose_description" class="form-control">
        </div>
    </div>

    <div class="form-group col-sm-12">
        <label class="control-label"> الملاحظات </label>

        <div>
            <textarea name="nots" rows="5" data-val="true" data-val-required="يجب إدخال البيان "
                      id="txt_nots"
                      class="form-control"><?= $HaveRs ? $rs['NOTS'] : "" ?></textarea>

        </div>
    </div>

    <?php if ($HaveRs  ) : ?>
        <div class="form-group col-sm-12">
            <label class="control-label"> التغذية الراجعة </label>

            <div>
                <textarea name="action_hints" data-val="true" data-val-required="يجب إدخال البيان "
                          id="txt_action_hints"
                          class="form-control"><?= $HaveRs ? $rs['ACTION_HINTS'] : "" ?></textarea>

            </div>
        </div>
    <?php endif; ?>

    <hr>

    <div id="type-1" style="display: none;" class="checks_div">
        <div class="form-group col-sm-2">
            <label class="control-label"> اسم المواطن </label>

            <div>
                <input name="citizen_name" data-val="true" data-val-required="يجب إدخال اسم المواطن"
                       value="<?= $HaveRs ? $rs['CITIZEN_NAME'] : "" ?>" class="form-control"
                       id="txt_citizen_name"/>
            </div>
        </div>

        <div class="form-group col-sm-3">
            <label class="control-label">العنوان</label>

            <div>
                <input name="addrrss" data-val="true" data-val-required="يجب إدخال العنوان"
                       value="<?= $HaveRs ? $rs['ADDRRSS'] : "" ?>" class="form-control"
                       id="txt_addrrss"/>
            </div>
        </div>


        <div class="form-group col-sm-1">
            <label class="control-label"> الجوال </label>

            <div>
                <input type="text" value="<?= $HaveRs ? $rs['JAWAL_NO'] : "" ?>" name="jawal_no"
                       id="txt_jawal_no" class="form-control">
            </div>
        </div>


        <div class="form-group col-sm-1">
            <label class="control-label"> الهاتف </label>

            <div>
                <input type="text" value="<?= $HaveRs ? $rs['TEL_NO'] : "" ?>" name="tel_no"
                       id="txt_tel_no" class="form-control">
            </div>
        </div>

    </div>

    <div id="type-4" style="display: none;" class="checks_div">
        <div class="form-group col-sm-2">
            <label class="control-label"> الرقم الفني للمشروع </label>

            <div>
                <input type="text" value="<?= $HaveRs ? $rs['PROJECT_SERIAL'] : "" ?>"
                       name="project_serial" id="h_txt_project_serial" class="form-control"
                       style="width: 80%;display: inline;">

                <button type="button" style="width: 18%" onclick="javascript:select_project();"
                        class="btn blue"><i class="icon icon-search"> </i></button>
            </div>
        </div>

    </div>
    <div id="type-5" style="display: none;" class="checks_div">

    </div>
    <div id="type-6" style="display: none;" class="checks_div">
        <div class="form-group col-sm-2">
            <label class="control-label"> رقم السيارة أو المعدة </label>

            <div>
                <input type="text" value="<?= $HaveRs ? $rs['CAR_ID'] : "" ?>" name="car_id"
                       id="txt_car_id" class="form-control">
            </div>
        </div>
    </div>
    <div id="type-7" style="display: none;" class="checks_div">
        <div class="form-group col-sm-2">
            <label class="control-label">رقم الحاسوب</label>

            <div>
                <input type="text" value="<?= $HaveRs ? $rs['COMPUTER_ID'] : "" ?>"
                       name="computer_id" id="txt_computer_id" class="form-control">
            </div>
        </div>
    </div>
    <hr/>

    <?php if (($HaveRs && HaveAccess(base_url('technical/requests/feedback_workorder')))&&  ($rs['REQUEST_TYPE'] == 1 || $rs['REQUEST_TYPE'] == 2)): ?>
        <div class="bg-warning padding-5">
            <fieldset id="workers">
                <legend>الموظفون</legend>
                <?php echo modules::run('technical/requests/public_get_team', $HaveRs ? $rs['REQUEST_ID'] : 0); ?>

            </fieldset>
            <fieldset id="tools">
                <legend>المواد</legend>
                <?php echo modules::run('technical/requests/public_get_tools', $HaveRs ? $rs['REQUEST_ID'] : 0); ?>
            </fieldset>
        </div>
    <?php endif; ?>

    <div class="modal-footer">
        <?php if ((!$HaveRs || (isset($can_edit) && $can_edit)) && $action == 'index'): ?>
            <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
        <?php endif; ?>

        <?php if (($HaveRs && HaveAccess(base_url('technical/requests/feedback_workorder')))&& $rs['REQUEST_CASE'] == 1 &&  ($rs['REQUEST_TYPE'] == 1 || $rs['REQUEST_TYPE'] == 2) ): ?>
            <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات , تحويل لصيانة تصحيحة
            </button>
        <?php endif; ?>

        <?php if ($HaveRs && $rs['REQUEST_TYPE'] == 1 && $rs['REQUEST_CASE'] == 1 && HaveAccess(base_url('technical/requests/turn_Corrective_maintenance'))): ?>
            <button type="button" onclick="javascript:turn_corrective();" class="btn btn-success">
                تحويل لصيانة تصحيحية
            </button>
        <?php endif; ?>

        <?php if ($HaveRs && $rs['REQUEST_TYPE'] != 1 && $rs['REQUEST_CASE'] == 1 && HaveAccess(base_url('technical/WorkOrder/create'))): ?>
            <a href="<?= base_url("technical/WorkOrder/create/{$rs['REQUEST_ID']}") ?>"
               class="btn btn-success"> تحويل لأمر عمل </a>
        <?php endif; ?>

        <?php if ($HaveRs && $rs['REQUEST_CASE'] == 1 && $rs['REQUEST_TYPE'] == 1 && HaveAccess(base_url('technical/requests/feedback'))): ?>
            <button type="button" onclick="javascript:$('#feedbackModal').modal();"
                    class="btn btn-info">التغذية الراجعة
            </button>
        <?php endif; ?>

    </div>
    </form>

    </div>

    </div>
    </div>

    <div class="modal fade" id="feedbackModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span
                            aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title"> التغذيةالراحعة </h4>
                </div>
                <div id="msg_container_alt"></div>
                <div class="modal-body inline_form">

                    <div class="form-group col-sm-12">
                        <div class="form-group col-sm-8" style="color: #ff0000;">
                            التغذية
                        </div>
                        <div class="col-sm-12">
                            <textarea id="txt_g_notes" class="form-control"></textarea>
                        </div>
                    </div>


                </div>
                <br>

                <div class="modal-footer">
                    <button type="button" onclick="javascript:request_feedback();" class="btn btn-primary">حفظ البيانات
                        (لا يتطلب أمر عمل)
                    </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>

                </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div><!-- /.modal -->



<?php

$turn_Corrective_maintenance_url = base_url('technical/requests/turn_Corrective_maintenance');
$convert_workOrder_url = base_url('technical/requests/convert_workOrder');
$feedback_url = base_url('technical/requests/feedback');
$select_team_url = base_url("technical/branches_teams/public_team_index");
$select_items_url = base_url("stores/classes/public_index");

$shared_script = <<<SCRIPT
      $('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        if(confirm('هل تريد حفظ السند ؟!')){
            var form = $(this).closest('form');
            ajax_insert_update(form,function(data){



                success_msg('رسالة','تم حفظ البيانات بنجاح ..');

                //reload_Page();

                  get_to_link('{$get_url}/'+data+'/index');

            },'html');
        }
    });

reBind();

$('#dp_requests_type').change(function(){

    $('div[id^="type-"]').hide();
    $('div#type-'+$(this).val()).show();

    if($(this).val() == 2) {
        $('div#type-1').show();
    }

});

function reBind(){
   $('input[id^="txt_class_id"]').click("focus",function(e){
        _showReport('$select_items_url/'+$(this).attr('id'));

    });



       $('input[id^="txt_worker_job_id"]').click("focus",function(e){
        _showReport('$select_team_url/'+$(this).attr('id'));

    });

    }



var count = 0;
$(function(){restCount();});
function restCount(){ count = $('input[name="t_worker_job_id[]"]').length;}
function AddRowWithData(id , name){


        if($('input[name="t_worker_job_id[]"][value="'+id+'"]').length <= 0){

            if($('input[name="t_worker_job_id[]"]', $('#teamTbl > tbody > tr:last')).val() != '')
                add_row($('#teamTbl tfoot a'),'input',false);



            $('input[name="t_worker_job_id[]"]', $('#teamTbl > tbody > tr:last')).val(id);
            $('input[name="t_worker_job_id[]"]', $('#teamTbl > tbody > tr:last')).attr('value',id);
            $('input[id ^="txt_worker_job_id"]', $('#teamTbl > tbody > tr:last')).val(name);

            count++;

    }
}

function select_project(){

_showReport('$public_select_project_url/txt_project_serial');
}


SCRIPT;


$create_script = <<<SCRIPT
    <script>
        {$shared_script}
    </script>
SCRIPT;


$edit_script = <<<SCRIPT
    <script>
        {$shared_script}

    $('div[id^="type-"]').hide();
    $('div#type-'+$('#dp_requests_type').val()).show();
    if($('#dp_requests_type').val() == 2) {
        $('div#type-1').show();
    }

    function turn_corrective(){
            if(confirm('هل تريد تحويل الطلب لصيانة تصحيحية ؟!')){

                  get_data('{$turn_Corrective_maintenance_url}',{id:$('#txt_request_id').val()},function(data){
                             if(data == '1'){
                            success_msg('رسالة','تم تحويل الطلب بنجاح ..');
                                    get_to_link('{$back_url}')
                               }else{
                                     danger_msg( 'تحذير','فشل في العملية');
                               }
                        },'html');
                }
    }

    function convert_workOrder(){
                if(confirm('هل تريد تحويل الطلب لأمر عمل ؟!')){
                  get_data('{$convert_workOrder_url}',{id:$('#txt_request_id').val()},function(data){
                             if(data == '1'){
                                    success_msg('رسالة','تم تحويل الطلب بنجاح ..');
                                    get_to_link('{$back_url}')
                               }else{
                                     danger_msg( 'تحذير','فشل في العملية');
                               }
                        },'html');
                }
    }

        function request_feedback(){

                if($('#txt_g_notes').val() =='' ){
                      alert('تحذير : لم تذكر بيانات التغذية الراجعة ؟!!');
                        return;
                    }
                    get_data('{$feedback_url}',{id:$('#txt_request_id').val(),notes:$('#txt_g_notes').val()},function(data){
                        if(data =='1')
                             success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                            reload_Page();
                    },'html');

    }

    function delete_details(a,id,url){
    if(confirm('هل تريد حذف البند ؟!')){

        get_data(url,{id:id},function(data){
            if(data == '1'){
                $(a).closest('tr').remove();

            }else{
                danger_msg( 'تحذير','فشل في العملية');
            }
        });
    }
}

    </script>
SCRIPT;

if ($HaveRs)
    sec_scripts($edit_script);
else
    sec_scripts($create_script);

?>