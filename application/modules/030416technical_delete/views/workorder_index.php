<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 8/27/14
 * Time: 8:46 AM
 */
$delete_url =base_url('technical/WorkOrder/delete');
$get_url =base_url('technical/WorkOrder/get_id');
$edit_url =base_url('technical/WorkOrder/edit');
$create_url =base_url('technical/WorkOrder/create');
$get_page_url = base_url('technical/WorkOrder/get_page');
$adapter_url = base_url('projects/adapter/public_index');

$cancel_url =base_url('technical/WorkOrder/cancel');

?>
<?= AntiForgeryToken() ?>
<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>

        </ul>

    </div>

    <div class="form-body">
        <fieldset>
            <legend>بحـث</legend>

            <div class="modal-body inline_form">
                <div class="form-group  col-sm-1">
                    <label class="control-label">نوع أمر العمل </label>
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
                    <label class="control-label">البيان</label>
                    <div>
                        <input type="text"  name="workorder_title" id="txt_workorder_title"   class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">من تاريخ</label>

                    <div>
                        <input type="text" data-type="date" data-date-format="DD/MM/YYYY" name="from_date"
                               id="txt_from_date" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label"> الي تاريخ</label>

                    <div>
                        <input type="text" data-type="date" data-date-format="DD/MM/YYYY" name="t_date" id="txt_to_date"
                               class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label"> المحول </label>
                    <div>
                        <input type="text" class="form-control" readonly id="txt_source_id" />
                        <input type="hidden" name="source_id" id="h_txt_source_id" >
                    </div>
                </div>

            </div>

            <div class="modal-footer">

                <button type="button" onclick="javascript:do_search();" class="btn btn-success">بحث</button>
                <div class="btn-group">
                    <button class="btn btn-warning dropdown-toggle" onclick="$('#projectTbl').tableExport({type:'excel',escape:'false'});" data-toggle="dropdown"><i class="fa fa-bars"></i> تصدير</button>
                </div>
                <button type="button" onclick="javascript:clearForm_any($('fieldset'));do_search();" class="btn btn-default">تفريغ الحقول</button>

            </div>
        </fieldset>

        <div id="msg_container"></div>

        <div id="container">
            <?php echo modules::run('technical/WorkOrder/get_page',$page,$action,$source_id); ?>
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

    ajax_pager({action :'$action' ,work_order_type:$('#dp_work_order_type').val() , workorder_title:$('#txt_workorder_title').val() ,from_date:$('#txt_from_date').val(),to_date:$('#txt_to_date').val(), source_id:$('#h_txt_source_id').val() });

    }

    function LoadingData(){

    ajax_pager_data('#projectTbl > tbody',{action :'$action' ,work_order_type:$('#dp_work_order_type').val() , workorder_title:$('#txt_workorder_title').val() ,from_date:$('#txt_from_date').val(),to_date:$('#txt_to_date').val() ,source_id:$('#h_txt_source_id').val() });

    }

    $('#txt_source_id').click(function(){
      _showReport('{$adapter_url}/'+$(this).attr('id')+'/');
    });


   function do_search(){

        get_data('{$get_page_url}',{page: 1,action :'$action' ,work_order_type:$('#dp_work_order_type').val() , workorder_title:$('#txt_workorder_title').val() ,from_date:$('#txt_from_date').val(),to_date:$('#txt_to_date').val() ,source_id:$('#h_txt_source_id').val() },function(data){
            $('#container').html(data);

            reBind();

        },'html');
    }

       function cancel_Order(a,id){
     if(confirm('هل تريد الغاء امر العمل ؟')){
           get_data('{$cancel_url}',{id:id},function(data){

                if(data == '1'){
               success_msg('رسالة','تم الغاء التكليف بنجاح ..');

                }

            },'html');
        }
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
