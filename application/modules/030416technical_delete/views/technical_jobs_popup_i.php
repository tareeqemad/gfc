<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 20/08/15
 * Time: 10:12 ص
 */

$MODULE_NAME= 'technical';
$TB_NAME= 'technical_jobs';
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/public_get_page");

?>
<script> var show_page=true; </script>

<div class="form-body">

    <div class="modal-body inline_form">
        <form  id="<?=$TB_NAME?>_form" method="get" action="<?=$get_page_url?>" role="form" novalidate="novalidate">

            <div class="form-group col-sm-1">
                <label class="control-label">رقم المهمة</label>
                <div>
                    <input type="text" name="job_id" id="txt_job_id" class="form-control">
                </div>
            </div>

            <div class="form-group col-sm-2">
                <label class="control-label"> اسم المهمة </label>
                <div>
                    <input type="text" name="job_name" id="txt_job_name" class="form-control">
                </div>
            </div>

            <div class="form-group col-sm-1">
                <label class="control-label">&nbsp;</label>
                <div>
                    <button type="button" onclick="javascript:<?=$TB_NAME?>_search();" class="btn btn-success">بحث</button>
                </div>
            </div>

        </form>
    </div>

    <div id="container">
        <?=modules::run("$MODULE_NAME/$TB_NAME/public_get_page",$text, $page, $job_id, $job_name); ?>
    </div>

</div>

<?php
$scripts = <<<SCRIPT
<script>

    function show_row_details(id, job_name, job_time_sum){
        parent.$('#h_$text').val(id);
        parent.$('#$text').val(job_name);
        parent.$('#t_$text').val(job_time_sum);

        parent.$('#report').modal('hide');

        if (typeof parent.get_jobs_data == 'function') {
            parent.get_jobs_data();
        }
    }

    function {$TB_NAME}_search(){
        get_data('{$get_page_url}/$text/1/'+check_vars($('#txt_job_id').val())+'/'+check_vars($('#txt_job_name').val()),{},function(data){
            $('#container').html(data);
        },'html');
    }

    function check_vars(val){
        if(val==null || val=='' || val==undefined)
            return -1;
        else
            return val;
    }

    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
    }

</script>
SCRIPT;
sec_scripts($scripts);
?>
