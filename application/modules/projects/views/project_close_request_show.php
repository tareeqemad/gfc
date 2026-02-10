<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 11/01/15
 * Time: 11:28 ص
 */
$back_url = base_url('projects/project_close_request');


if (!isset($result))
    $result = array();
$HaveRs = count($result) > 0;

$rs = $HaveRs ? $result[0] : $result;

$wol = base_url('technical/Worker_Order_Loads/public_index/' . ($HaveRs ? $rs['SER'] : -1));


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
    <form class="form-form-vertical" id="project_close_form" method="post"
          action="<?= base_url('projects/project_close_request/' . ($action == 'delivery' ? 'delivery' : ($HaveRs ? 'edit' : 'create'))) ?>"
          role="form"
          novalidate="novalidate">
    <div class="modal-body inline_form">

        <div class="form-group col-sm-1">
            <label class="control-label"> الرقم </label>

            <div>
                <input type="text" readonly value="<?= $HaveRs ? $rs['SER'] : "" ?>"
                       name="ser" id="txt_ser" class="form-control">
            </div>
        </div>


        <div class="form-group  col-sm-2">
            <label class="control-label">المشروع
            </label>

            <div>
                <input name="project_serial" type="hidden"
                       value="<?= $HaveRs ? $rs['PROJECT_SERIAL'] : "" ?>" id="IDh_txt_project_serial"
                       class="form-control">
                <input name="project_serial_name" onclick="javascript:select_project();"
                       value="<?= $HaveRs ? $rs['PROJECT_SERIAL_NAME'] : "" ?>"
                       readonly id="txt_project_serial" class="form-control">
            </div>
        </div>


        <div class="form-group col-sm-1">

            <label class="control-label"> التاريخ </label>

            <div>
                <input <?= !$HaveRs || ($HaveRs && $rs['PROJECT_CLOSE_CASE'] < 1) ? '' : 'readonly' ?> type="text"
                                                                                                       name="request_date"
                                                                                                       value="<?= $HaveRs ? $rs['REQUEST_DATE'] : "" ?>"
                                                                                                       data-val="true"
                                                                                                       data-val-required="حقل مطلوب"
                                                                                                       id="txt_request_date"
                                                                                                       data-type="date"
                                                                                                       data-date-format="DD/MM/YYYY"
                                                                                                       data-val-regex="التاريخ غير صحيح!"
                                                                                                       data-val-regex-pattern="<?= date_format_exp() ?>"
                                                                                                       class="form-control">

            </div>
        </div>
        <div class="form-group col-sm-3">
            <label class="control-label">عنوان الطلب</label>

            <div>
                <input <?= !$HaveRs || ($HaveRs && $rs['PROJECT_CLOSE_CASE'] < 1) ? '' : 'readonly' ?> type="text"
                                                                                                       value="<?= $HaveRs ? $rs['TITLES'] : "" ?>"
                                                                                                       name="titles"
                                                                                                       id="txt_titles"
                                                                                                       class="form-control">

            </div>

        </div>


        <hr>

        <div class="form-group  col-sm-7">
            <label class="control-label">الملاحظات</label>

            <div>
                <textarea <?= !$HaveRs || ($HaveRs && $rs['PROJECT_CLOSE_CASE'] < 1) ? '' : 'readonly' ?> type="text"
                                                                                                          name="hints"
                                                                                                          rows="5"
                                                                                                          id="txt_hints"
                                                                                                          class="form-control"><?= $HaveRs ? $rs['NOTES'] : "" ?></textarea>
            </div>
        </div>

        <div class="form-group  col-sm-7">
            <label class="control-label">المرفقات</label>


            <div>
                <div><input
                        type="checkbox" <?= $HaveRs && strpos($rs['ATTCH'], '1') !== false ? 'checked' : '' ?>
                        name="attach[]" value="1"><span style="margin-right: 10px">طلب استلام</span>
                </div>
                <div><input
                        type="checkbox" <?= $HaveRs && strpos($rs['ATTCH'], '2') !== false ? 'checked' : '' ?>
                        name="attach[]" value="2"><span
                        style="margin-right: 10px">مخطط التنفيذ</span></div>
                <div><input
                        type="checkbox" <?= $HaveRs && strpos($rs['ATTCH'], '3') !== false ? 'checked' : '' ?>
                        name="attach[]" value="3"><span
                        style="margin-right: 10px">كشف بالمواد المضافة</span>
                </div>
                <div><input
                        type="checkbox" <?= $HaveRs && strpos($rs['ATTCH'], '4') !== false ? 'checked' : '' ?>
                        name="attach[]" value="4"><span
                        style="margin-right: 10px">كشف بالمواد المرجعة</span>
                </div>

            </div>
        </div>


        <?php if ($HaveRs && $rs['PROJECT_CLOSE_CASE'] >= 1): ?>
            <div class="col-lg-12">
                <div class=" alert alert-info" style="overflow: hidden">

                    <div class="form-group col-sm-1">
                        <label class="control-label">نوع التسليم</label>

                        <div>


                            <select class="form-control" name="close_type" id="dp_close_type">

                                <option <?= $HaveRs ? ($rs['CLOSE_TYPE'] == 1 ? 'selected' : '') : '' ?>
                                    value="1">ابتدائي
                                </option>
                                <option <?= $HaveRs ? ($rs['CLOSE_TYPE'] == 2 ? 'selected' : '') : '' ?>
                                    value="2">نهائي
                                </option>

                            </select>
                        </div>

                    </div>

                    <div class="form-group  col-sm-3">
                        <label class="control-label"> الشركة </label>

                        <div>
                            <input type="text" name="company_name"
                                   id="txt_company_name"
                                   class="form-control" value="<?= $HaveRs ? $rs['COMPANY_NAME'] : "" ?>">
                        </div>
                    </div>

                    <div class="form-group  col-sm-1">
                        <label class="control-label"> تاريخ الزيارة </label>

                        <div>
                            <input type="text" name="visit_date"
                                   id="txt_visit_date"
                                   data-type="date"
                                   data-date-format="DD/MM/YYYY"
                                   data-val-regex="التاريخ غير صحيح!"
                                   class="form-control" value="<?= $HaveRs ? $rs['VISIT_DATE'] : "" ?>">
                        </div>
                    </div>


                    <div class="col-lg-12"></div>

                    <div class="form-group  col-sm-7">
                        <label class="control-label">ملاحظات لجنة الاستلام</label>

                        <div>
                            <textarea type="text" name="hints" rows="8"
                                      id="txt_hints"
                                      class="form-control"><?= $HaveRs ? $rs['HINTS'] : "" ?></textarea>
                        </div>
                    </div>


                </div>

            </div>
        <?php endif; ?>
    </div>


    <div class="modal-footer">

        <?php if ((($HaveRs && $rs['PROJECT_CLOSE_CASE'] == -1 && $can_edit) || !$HaveRs) || ($HaveRs && $rs['PROJECT_CLOSE_CASE'] == 1 && ($action == 'delivery'))): ?>
            <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
        <?php endif; ?>

        <?php if ($HaveRs && $rs['PROJECT_CLOSE_CASE'] == -1 && (isset($can_edit) && $can_edit) && ($action == 'index')): ?>
            <button type="button" onclick="javascript:return_adopt(1);" class="btn btn-success"> إعتمد
            </button>
        <?php endif; ?>

        <?php if ($HaveRs && $rs['PROJECT_CLOSE_CASE'] == 1 && ($action == 'delivery')): ?>
            <button type="button" onclick="javascript:return_adopt(2);" class="btn btn-success"> إعتمد
            </button>
        <?php endif; ?>

        <?php if ($HaveRs && $rs['PROJECT_CLOSE_CASE'] == 2 && ($action == 'tec')): ?>
            <button type="button" onclick="javascript:return_adopt(3);" class="btn btn-success"> إعتمد
            </button>
        <?php endif; ?>
        <?php if ($HaveRs && (($rs['PROJECT_CLOSE_CASE'] == 1 && $action == 'delivery') || ($rs['PROJECT_CLOSE_CASE'] == 2 && $action == 'tec'))): ?>
            <button type="button" onclick="javascript:return_adopt(0);" class="btn btn-danger">إرجاع
            </button>
        <?php endif; ?>

        <?php if ($HaveRs && $rs['PROJECT_CLOSE_CASE'] >= 2): ?>
            <?php


            $report_file = $HaveRs && $rs['HINTS'] == '' ? 'rep1_tech_project' : 'rep2_tech_project';
            $report2_file = $HaveRs && $rs['HINTS'] == '' ? 'rep4_tech_project' : 'rep3_tech_project';

            ?>
            <button type="button"
                    onclick="javascript:_showReport('<?= base_url("JsperReport/showreport?sys=technical&report={$report_file}&ser={$rs['SER']}") ?>');"
                    class="btn btn-default"> تقرير الاستلام
            </button>

            <button type="button"
                    onclick="javascript:_showReport('<?= base_url("JsperReport/showreport?sys=technical&report={$report2_file}&ser={$rs['SER']}") ?>');"
                    class="btn btn-default"> تقرير الملاحظات
            </button>
        <?php endif; ?>

    </div>


    </form>

    </div>

    <?php
    echo modules::run('Project_close_request/project_close_hinst_list', (count($rs)) ? $rs['SER'] : 0);
    ?>
    <hr/>
    <div style="clear: both;">
        <?php echo modules::run('settings/notes/public_get_page', (count($rs)) ? $rs['SER'] : 0, 'project_close_request'); ?>
        <?php echo (count($rs)) ? modules::run('attachments/attachment/index', $rs['SER'], 'project_close_request') : ''; ?>
    </div>
    </div>

    </div>


<?php echo modules::run('settings/notes/index'); ?>

<?php
$delete_url = base_url('projects/project_close_request/delete_partition');
$adapter_address_url = base_url('technical/project_close_request_address/public_index');
$public_select_project_url = base_url('projects/projects/public_select_project');
$notes_url = notes_url();
$SER_ID = $HaveRs ? $rs['SER'] : 0;

$get_url = base_url('projects/project_close_request/get/');

$shared_script = <<<SCRIPT


function select_project(){

_showReport('$public_select_project_url/txt_project_serial');
}

        $('#txt_PROJECT_SERIAL').click(function(){
            _showReport('$adapter_address_url/'+$(this).attr('id')+'/');
        });


      $('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        if(confirm('هل تريد حفظ السند ؟!')){
            var form = $(this).closest('form');
            ajax_insert_update(form,function(data){

                success_msg('رسالة','تم حفظ البيانات بنجاح ..');

                //reload_Page();
                get_to_link('{$get_url}/'+data+'/$action');

            },'html');
        }
    });

    function delete_details(a,id){
             if(confirm('هل تريد حذف البند ؟!')){

                  get_data('{$delete_url}',{id:id},function(data){
                             if(data == '1'){
                                $(a).closest('tr').remove();

                               }else{
                                     danger_msg( 'تحذير','فشل في العملية');
                               }
                        });
                 }
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


         function return_adopt(type){

            if((type == 1 || type == 2) && ! confirm('هل تريد إعتماد السند ؟!')){
                return;
            }
            action_type = type;

            $('#notesModal').modal();

       }

        function apply_action(){


                if(action_type == 1 || action_type == 2 || action_type == 3){

                     get_data($('#project_close_form').attr('action').replace('edit','{$action}'),{id:{$SER_ID}, action : 'adopt' },function(data){
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
                    get_data($('#project_close_form').attr('action').replace('edit','{$action}'),{id:{$SER_ID}, action : 'cancel' },function(data){
                        if(data =='1')
                            success_msg('رسالة','تم  إرجاع السند بنجاح ..');
                             reload_Page();
                    },'html');
                }

                get_data('{$notes_url}',{source_id:{$SER_ID},source:'project_close_request',notes:$('#txt_g_notes').val()},function(data){
                    $('#txt_g_notes').val('');
                },'html');

                $('#notesModal').modal('hide');


        }

    </script>
SCRIPT;

if ($HaveRs)
    sec_scripts($edit_script);
else
    sec_scripts($create_script);

?>