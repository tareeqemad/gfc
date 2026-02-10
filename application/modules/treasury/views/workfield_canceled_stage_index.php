<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$get_page_url = base_url('treasury/workfield/canceled_stage_get_page');
?>

<?= AntiForgeryToken() ?>
<div class="row">
    <div class="toolbar">
        <div class="caption"><?= $title ?></div>
    </div>

    <div class="form-body">

        <div id="msg_container"></div>
        <fieldset>
            <legend>بحـث</legend>
            <div class="modal-body inline_form">
                <div class="form-group col-sm-2">
                    <label class="control-label"> تاريخ التحصيل</label>
                    <div class="">
                        <input type="text" data-val-required="حقل مطلوب" data-type="date"
                               data-date-format="YYYY/MM/DD"
                               value="<?=date('Y/m/d')?>"
                               name="txt_date" id="txt_date" class="form-control valid">
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" onclick="javascript:do_search();" class="btn btn-success"> إستعلام</button>
                <button type="button" onclick="javascript:clearForm_any($('fieldset'));do_search();" class="btn btn-default"> تفريغ الحقول</button>
            </div>

        </fieldset>

        <div id="container">
            <?php //echo modules::run('treasury/workfield/adoptSupervision',$page); ?>
        </div>

    </div>

</div>
<?php

$scripts = <<<SCRIPT

<script>
    function do_search(){
        get_data('{$get_page_url}',{page: 1 ,collect_date : $('#txt_date').val()},function(data){
            $('#container').html(data);
        },'html');
    }
</script>
SCRIPT;

sec_scripts($scripts);

?>

