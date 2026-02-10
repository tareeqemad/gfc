<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 8/27/14
 * Time: 8:46 AM
 */
$delete_url =base_url('technical/HighPowerPartition/delete');
$get_url =base_url('technical/HighPowerPartition/get_id');
$edit_url =base_url('technical/HighPowerPartition/edit');
$create_url =base_url('technical/HighPowerPartition/create');
$get_page_url = base_url('technical/HighPowerPartition/get_page');

?>
<?= AntiForgeryToken() ?>
<div class="row">


    <div class="form-body">
        <fieldset>
            <legend>بحـث</legend>


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
            <?php echo modules::run('technical/HighPowerPartition/get_page',$page,true); ?>
        </div>

    </div>

</div>




<?php


$scripts = <<<SCRIPT

<script>


   function adapter_select(id , name ){
            parent.$('#$txt').val(name);
            parent.$('#h_$txt').val(id);


            parent.$('#report').modal('hide');
    }


    $(function(){
        reBind();
    });

    function reBind(){

    ajax_pager({project_tec_type:$('#dp_project_tec_type').val(),tec_num : $('#txt_tec_num').val(),project_name:$('#txt_project_name').val(),from_date:$('#txt_from_date').val(),to_date:$('#txt_to_date').val(),branch:$('#dp_branch').val()});

    }

    function LoadingData(){

    ajax_pager_data('#projectTbl > tbody',{project_tec_type:$('#dp_project_tec_type').val(),tec_num : $('#txt_tec_num').val(),project_name:$('#txt_project_name').val(),from_date:$('#txt_from_date').val(),to_date:$('#txt_to_date').val(),branch:$('#dp_branch').val()});

    }


   function do_search(){

        get_data('{$get_page_url}',{page: 1,project_tec_type:$('#dp_project_tec_type').val(),tec_num : $('#txt_tec_num').val(),project_name:$('#txt_project_name').val(),from_date:$('#txt_from_date').val(),to_date:$('#txt_to_date').val(),branch:$('#dp_branch').val()},function(data){
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
