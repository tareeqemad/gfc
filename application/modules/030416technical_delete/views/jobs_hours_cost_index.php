<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 18/11/15
 * Time: 12:20 م
 */

$save_url =base_url('technical/jobs_hours_cost/save');
echo AntiForgeryToken();

?>

<div class="row">
    <div class="toolbar">
        <div class="caption"><?= $title ?> </div>
        <ul>
            <?php if( HaveAccess($save_url)): ?>  <li><a  onclick="javascript:save_rows();" href="javascript:;"><i class="glyphicon glyphicon-saved"></i>حفظ</a> </li><?php endif;?>
        </ul>
    </div>

    <div class="form-body">

        <div id="msg_container"></div>

        <div id="container">
            <form class="form-horizontal" id="jobs_hours_cost_form" method="post" action="<?=$save_url?>" role="form" novalidate="novalidate">
                <?=modules::run("technical/jobs_hours_cost/get_page",$jobs_cost_type)?>
                <input type="hidden" name="jobs_cost_type" value="<?=$jobs_cost_type?>" />
            </form>
        </div>
    </div>
</div>

<?php
$scripts = <<<SCRIPT
<script>

    function save_rows(){
        if(confirm('هل تريد بالتأكيد حفظ جميع السجلات')){
            var form = $("#jobs_hours_cost_form");
            ajax_insert_update(form,function(data){
                $("#jobs_hours_cost_form").html(data+' <input type="hidden" name="jobs_cost_type" value="{$jobs_cost_type}" /> ');
                success_msg('رسالة','تمت العملية..');
            },"html");
        }
    }

</script>
SCRIPT;
sec_scripts($scripts);
?>
