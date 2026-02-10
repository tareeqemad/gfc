<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 8/27/14
 * Time: 8:46 AM
 */
$delete_url = base_url('technical/WorkOrder/delete');
$get_url = base_url('technical/WorkOrder/get_id');
$edit_url = base_url('technical/WorkOrder/edit');
$create_url = base_url('technical/WorkOrder/create');
$get_page_url = base_url('technical/WorkOrder/public_get_page');

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
                    <label class="control-label">نوع أمر العمل </label>

                    <div>

                        <select type="text" name="work_order_type" id="dp_work_order_type" class="form-control">
                            <option></option>
                            <?php foreach ($WORK_ORDER_TYPE as $row) : ?>
                                <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">المشروع</label>

                    <div>
                        <input type="text" name="project_name" id="txt_project_name" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">الصيانة الطارئة</label>

                    <div>
                        <input type="text" name="agent_mint" id="txt_agent_mint" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">المهمة</label>

                    <div>
                        <input type="text" name="task" id="txt_task" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">التعليمات</label>

                    <div>
                        <input type="text" name="instructions" id="txt_instructions" class="form-control">
                    </div>
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
        <div>
            <a class="btn-xs btn-danger" onclick="javascript:select_choose();" href="javascript:;"><i
                    class="icon icon-download"></i> إدراج المختار </a>
        </div>
        <div id="container">
            <?php echo modules::run('technical/WorkOrder/public_get_page', $txt, $page, $action); ?>
        </div>
        <div>
            <a class="btn-xs btn-danger" onclick="javascript:select_choose();" href="javascript:;"><i
                    class="icon icon-download"></i> إدراج المختار </a>
        </div>
    </div>

</div>




<?php


$scripts = <<<SCRIPT
<script>


    function select_WorkOrder(id , name ,start_date,end_date,type,multi){
        parent.$('#$txt').val(name);
        parent.$('#$txt').attr('data-type',type);
        parent.$('#h_$txt').val(id);
        parent.$('#h_$txt').attr('value',id);
        parent.$('#action_start_$txt').val(start_date);
        parent.$('#action_end_$txt').val(end_date);


        parent.$('#report').modal('hide');

        if (typeof parent.afterSelect == 'function' && !multi) {
            parent.afterSelect( parent.$('#$txt').closest('tr'),id,type);
        }

    }

    function select_choose(){

        $('.checkboxes:checked').each(function(i){
            var obj = jQuery.parseJSON($(this).attr('data-val'));
            if($('#{$txt}').val() == ''){
                select_WorkOrder(obj.WORK_ORDER_ID,obj.WORKORDER_TITLE,obj.WORK_ORDER_START_DATE,obj.WORK_ORDER_END_DATE,obj.REQUEST_TYPE,false);
            }else{
                parent.AddRowWithData(obj.WORK_ORDER_ID,obj.WORKORDER_TITLE,obj.WORK_ORDER_START_DATE,obj.WORK_ORDER_END_DATE,obj.REQUEST_TYPE);
            }
        });


        parent.afterSelect( null,null,null);

        $('.checkboxes:checked').prop('checked',false);

    }

    $(function(){
        reBind();
    });

    function reBind(){

        ajax_pager({action :'$action' ,work_order_type:$('#dp_work_order_type').val() , project_name:$('#txt_project_name').val() , agent_mint: $('#txt_agent_mint').val() , task:$('#txt_task').val() , instructions:$('#txt_instructions').val() });

    }

    function LoadingData(){

        ajax_pager_data('#projectTbl > tbody',{action :'$action' ,work_order_type:$('#dp_work_order_type').val() , project_name:$('#txt_project_name').val() , agent_mint: $('#txt_agent_mint').val() , task:$('#txt_task').val() , instructions:$('#txt_instructions').val()});

    }


    function do_search(){

        get_data('{$get_page_url}',{page: 1,action :'$action' ,work_order_type:$('#dp_work_order_type').val() , project_name:$('#txt_project_name').val() , agent_mint: $('#txt_agent_mint').val() , task:$('#txt_task').val() , instructions:$('#txt_instructions').val()},function(data){
            $('#container').html(data);

            reBind();

        },'html');
    }

    function delete_project(a,id){
        if(confirm('هل تريد حذف المشروع ؟')){
            get_data('{$delete_url}',{id:id},function(data){
                $('#container').html(data);
                if(data == '1'){
                    success_msg('رسالة','تم حذف المشروع بنجاح ..');
                    $(a).closest('tr').remove();
                }

            },'html');
        }
    }



</script>

SCRIPT;

sec_scripts($scripts);



?>
