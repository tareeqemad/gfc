<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 11/01/15
 * Time: 11:28 ص
 */
$back_url = base_url('technical/Fast_workorder/archive');
$get_url = base_url('technical/Fast_workorder/get/');
$create_url = base_url('technical/Fast_workorder/create');

$public_select_adapter_url = base_url('projects/adapter/public_index');

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
                      action="<?= base_url('technical/Fast_workorder/' . ($HaveRs ? ($action == 'feedback_workorder' ? 'feedback_workorder' : 'edit') : 'create')) ?>"
                      role="form"
                      novalidate="novalidate">
                    <div class="modal-body inline_form">

                        <div class="form-group col-sm-1">
                            <label class="control-label"> م. </label>

                            <div>
                                <input type="text" readonly value="<?= $HaveRs ? $rs['SER'] : "" ?>"
                                       name="ser" id="txt_ser" class="form-control">
                            </div>
                        </div>


                        <div class="form-group col-sm-4">
                            <label class="control-label">العنوان</label>

                            <div>
                                <input type="text" value="<?= $HaveRs ? $rs['TITLE'] : "" ?>" data-val="true"
                                       data-val-required="حقل مطلوب"
                                       name="title" id="txt_title" class="form-control">
                            </div>
                        </div>

                        <hr>

                        <div class="form-group col-sm-1">
                            <label class="control-label"> تاريخ </label>

                            <div class="">
                                <input type="text" readonly name="from_date"
                                       value="<?= $HaveRs ? $rs['FROM_DATE'] : date('d/m/Y') ?>"
                                       data-val-regex="التاريخ غير صحيح!"
                                       data-val="true"
                                       data-val-required="حقل مطلوب"
                                       data-type="date"
                                       data-date-format="DD/MM/YYYY"
                                       id="txt_from_date" class="form-control ltr ">
                            </div>
                        </div>


                        <div class="form-group col-sm-1">
                            <label class="control-label"> الي تاريخ </label>

                            <div class="">
                                <input type="text" readonly name="to_date"
                                       value="<?= $HaveRs ? $rs['TO_DATE'] : date('d/m/Y') ?>"
                                       data-val-regex="التاريخ غير صحيح!"
                                       data-val="true"
                                       data-type="date"
                                       data-date-format="DD/MM/YYYY"
                                       data-val-required="حقل مطلوب" id="txt_to_date" class="form-control ltr ">
                            </div>
                        </div>

                        <div class="form-group col-sm-3">
                            <label class="control-label"> المهمة المراد تنفيذها </label>

                            <div>
                                <input type="text" data-val="true" data-val-required="يجب إدخال البيان "
                                       value="<?= $HaveRs ? $rs['JOB_ID'] : "" ?>" name="job_id" id="h_txt_job_id"
                                       class="form-control col-md-4">
                                <input readonly value="<?= $HaveRs ? $rs['JOB_ID_NAME'] : "" ?>"
                                       class="form-control  col-md-8"
                                       id="txt_job_id"/>
                            </div>
                        </div>

                        <hr>
                        <div class="form-group  col-sm-2">
                            <button type="button" style="width: 100%" onclick="javascript:select_adapter();"
                                    class="btn blue"><i class="icon icon-search"> </i> تحديد المحولات
                            </button>
                        </div>

                        <div class="form-group col-sm-12">
                            <h3 class="">المحولات</h3>

                            <table id="adapterList" class="table">

                                <thead>
                                <tr>
                                    <th class="">المحول</th>
                                    <th style="width:50px;"></th>
                                </tr>
                                </thead>
                                <tbody>

                                <?php


                                if ($HaveRs):
                                    $data = json_decode("[{$rs['ADAPTER_SERS']}]");
                                    if ($data):
                                        foreach ($data as $rw):
                                            ?>

                                            <tr>
                                                <td class=""><input type="hidden" name="adapter[]"
                                                                    value="<?= $rw->id ?>"/>
                                                    <input type="hidden" name="adapter_name[]" value ='{"id" : <?= $rw->id ?> ,"name" : "<?= $rw->name ?>" }'/>
                                                    <?= $rw->name ?></td>

                                                <td data-action="delete">
                                                    <a data-action="delete" href="javascript:;"
                                                       onclick="javascript:$(this).closest('tr').remove();">
                                                        <i class="icon icon-trash delete-action"></i> </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; endif; endif; ?>
                                </tbody>
                            </table>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <?php if ((!$HaveRs || (isset($can_edit) && $can_edit)) && $action == 'index'): ?>
                            <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                        <?php endif; ?>

                        <?php if ((  $HaveRs && (isset($can_edit) && $can_edit)) && $action == 'index'): ?>
                            <button type="submit" data-action="generate" class="btn btn-success">أنشاء أوامر العمل </button>
                        <?php endif; ?>

                    </div>
                </form>

            </div>

        </div>
    </div>

<?php

$turn_Corrective_maintenance_url = base_url('technical/Fast_workorder/turn_Corrective_maintenance');
$convert_workOrder_url = base_url('technical/Fast_workorder/convert_workOrder');
$feedback_url = base_url('technical/Fast_workorder/feedback');
$select_team_url = base_url("technical/branches_teams/public_team_index");
$select_items_url = base_url("stores/classes/public_index");
$get_Tjob_url = base_url('technical/Technical_jobs/public_index');
$public_select_project_url = base_url('projects/projects/public_select_project');

$shared_script = <<<SCRIPT



    $('button[data-action="submit"],button[data-action="generate"]').click(function(e){
        e.preventDefault();
        if(confirm('هل تريد حفظ السند ؟!')){
            var form = $(this).closest('form');

console.log('',$(this).attr('data-action'));
            if($(this).attr('data-action') == 'generate' )
                 $(form).attr('action',$(form).attr('action').replace('edit','generate'));

            ajax_insert_update(form,function(data){



                success_msg('رسالة','تم حفظ البيانات بنجاح ..');

                //reload_Page();

                 get_to_link('{$get_url}/'+data+'/index');

            },'html');
        }
    });

    $('#txt_job_id').click(function(){
        _showReport('$get_Tjob_url/'+$(this).attr('id'));

    });


    function select_adapter(){

         _showReport('{$public_select_adapter_url}/adapterList');
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



    </script>
SCRIPT;

if ($HaveRs)
    sec_scripts($edit_script);
else
    sec_scripts($create_script);

?>