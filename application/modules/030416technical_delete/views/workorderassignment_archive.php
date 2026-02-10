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

                <div class="form-group col-sm-1">
                    <label class="control-label">رقم التكليف</label>

                    <div>
                        <input type="text" name="work_assignment_code" id="txt_work_assignment_code" class="form-control">
                    </div>
                </div>


                <div class="form-group col-sm-2">
                    <label class="control-label">تكليف العمل</label>
                    <div>
                        <input type="text"  name="title" id="txt_title"   class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">الفرقة</label>
                    <div>
                        <input type="text"  name="team_id" id="txt_team_id"   class="form-control">
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

    ajax_pager({action :'$action' , title:$('#txt_title').val() , team_id: $('#txt_team_id').val()  ,work_assignment_code : $('#txt_work_assignment_code').val()});

    }

    function LoadingData(){

    ajax_pager_data('#projectTbl > tbody',{action :'$action' , title:$('#txt_title').val() , team_id: $('#txt_team_id').val()  ,work_assignment_code : $('#txt_work_assignment_code').val()});

    }


   function do_search(){

        get_data('{$get_page_url}',{page: 1,action :'$action', title:$('#txt_title').val() , team_id: $('#txt_team_id').val()  ,work_assignment_code : $('#txt_work_assignment_code').val()},function(data){
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
