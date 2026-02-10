<?php
/**
 * Created by PhpStorm.
 * User: mtaqia
 * Date: 26/09/16
 * Time: 11:57 ص
 */

$MODULE_NAME= 'hr';
$TB_NAME= 'gedco_grading_policy';
$get_page_url =base_url("$MODULE_NAME/$TB_NAME/get_page");

echo AntiForgeryToken();
?>

<div class="row">
    <div class="toolbar">
        <div class="caption"><i class="glyphicon glyphicon-check"></i><?= $title ?></div>
    </div><!-- /.toolbar -->

    <div class="form-body">
        <form class="form-vertical" id="<?=$TB_NAME?>_form" >
            <div class="modal-body inline_form">

                <div class="form-group col-sm-2">
                    <label class="control-label">أمر التقييم</label>
                    <div>
                        <select name="evaluation_order_id" class="form-control" id="dp_evaluation_order_id">
                            <option value=""></option>
                            <?php foreach ($order_id as $row) : ?>
                                <option data-dept="<?= $row['EVALUATION_ORDER_ID'] ?>"
                                        value="<?= $row['EVALUATION_ORDER_ID'] ?>"><?= $row['EVALUATION_ORDER_ID'].' - '.substr($row['ORDER_START'],-4).' - '?><?=($row['ORDER_TYPE']==1) ? 'سنوي' : 'نصفي' ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2" style="display: none ;">
                    <label class="control-label">الإدارة</label>
                    <div>
                        <select name="admin_id" class="form-control" id="dp_admin_id">
                            <option value="">جميع المقرات</option>
                            <?php foreach ($branches as $row) : ?>
                                <option data-dept="<?= $row['NO'] ?>"
                                        value="<?= $row['NO'] ?>"><?= $row['NO'].' : '.$row['NAME'];?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

            </div>
        </form>

        <div class="modal-footer">
            <button type="button" onclick="javascript:search();" class="btn btn-success"> إستعلام</button>
            <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>
        </div>

        <div id="msg_container"></div>

        <div id="container">
            <?=modules::run($get_page_url);?>
        </div>

    </div>
</div>

<?php

$scripts = <<<SCRIPT
<script type="text/javascript">

    $('#dp_evaluation_order_id').select2();
    $('#dp_admin_id').select2();

    function search(){
        $('#container').text('');
        var values= {evaluation_order_id: $('#dp_evaluation_order_id').select2('val'), admin_id: $('#txt_admin_id').val() };
        get_data('{$get_page_url}',values ,function(data){
            $('#container').html(data);
        },'html');
    }

    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
        $('#dp_evaluation_order_id').select2('val',0);
        $('#dp_admin_id').select2('val',0);
    }

</script>

SCRIPT;

sec_scripts($scripts);

?>
