<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$get_page_url = base_url('treasury/workfield/supervision_stage_get_page');
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
                    <label class="control-label"> شركة خارجية / موظف </label>
                    <div class="">
                        <select name="user_type" id="dp_user_type" class="form-control select2" >
                            <option value="">----------</option>
                            <?php foreach($user_type as $row) :?>
                                <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2 hidden" id="div_date">
                    <label class="control-label"> تاريخ التحصيل</label>
                    <div class="">
                        <input type="text" data-val-required="حقل مطلوب" data-type="date"
                               data-date-format="YYYY/MM/DD"
                               value="<?=date('Y/m/d')?>"
                               name="txt_date" id="txt_date" class="form-control valid">
                    </div>
                </div>


                <div class="form-group col-sm-2 hidden " id="div_date_company">
                    <label class="control-label"> شهر التحصيل</label>
                    <div class="">
                        <input type="text" name="month_company" id="txt_month_company" value="<?=date('Y/m')?>" class="form-control">
                    </div>
                </div>

            </div>


            <div class="modal-footer">
                <button type="button" onclick="javascript:do_search();" class="btn btn-success"> إستعلام</button>
                <button type="button" onclick="javascript:clearForm_any($('fieldset'));do_search();" class="btn btn-default"> تفريغ الحقول</button>
            </div>

        </fieldset>


        <div id="container">
            <?php //echo modules::run('treasury/workfield/supervision_stage_get_page',$page); ?>
        </div>

    </div>

</div>
<?php

$scripts = <<<SCRIPT

<script>

    $('#txt_month_company').datetimepicker({
        format: 'YYYY/MM',
        minViewMode: "months",
        pickTime: false
    });


    function do_search(){
        get_data('{$get_page_url}',{page: 1 ,collect_date : $('#txt_date').val(), user_type: $('#dp_user_type').val(), company_date: $('#txt_month_company').val()+"/01" },function(data){
            $('#container').html(data);
        },'html');
    }
    
    
    
</script>
SCRIPT;

sec_scripts($scripts);



?>

