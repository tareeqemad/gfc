<?php

$MODULE_NAME= 'hr';
$TB_NAME= 'ev_audit_objection_mark';

$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");

echo AntiForgeryToken();
?>

<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

    </div>

    <?php
    if(($error_msg) != ''){
        echo "<br/><br/>
        <div style='text-align: center'>
       <b >
        $error_msg
        </b>
        </div>
        
        ";
        die();
    }
    ?>

    <div class="form-body">

        <form class="form-vertical" id="<?=$TB_NAME?>_form" >
            <input type="hidden" id="h_evaluation_order_id" value="<?=$get_active['EVALUATION_ORDER_ID']?>">
            <input type="hidden" id="h_order_level" value="<?=$get_active['LEVEL_ACTIVE']?>">
            <div class="modal-body inline_form">

                <div class="form-group col-sm-1">
                    <label class="control-label">امر التقييم</label>
                    <div>
                        <input readonly type="text" name="evaluation_order_id" class="form-control" value="<?=$get_active['EVALUATION_ORDER_ID']?>">
                    </div>
                </div>


                <div class="form-group col-sm-2">
                    <label class="control-label">مرحلة التقييم</label>
                    <div>
                        <select disabled name="order_level" class="form-control">
                            <option value=""> </option>
                            <option <?=($get_active['LEVEL_ACTIVE']==3)?'selected':''?> value="3">تدقيق</option>
                            <option <?=($get_active['LEVEL_ACTIVE']==5)?'selected':''?>  value="5">تظلم</option>
                        </select>
                    </div>
                </div>


            </div>
        </form>

        <div class="modal-footer">
            <button type="button" onclick="javascript:search();" class="btn btn-success"> إستعلام</button>
        </div>

        <div id="msg_container"></div>

        <div id="container">

        </div>

    </div>

</div>

<?php
$scripts = <<<SCRIPT
<script>

   function values_search(add_page){
        var values= { evaluation_order_id:$('#h_evaluation_order_id').val(), order_level:$('#h_order_level').val() };
        if(add_page==0)
            delete values.page;
        return values;
    }

    function search(){
        var values= values_search(1);
        get_data('{$get_page_url}', values ,function(data){
            $('#container').html(data);
        },'html');
    }

    function LoadingData(){
        var values= values_search(0);
        ajax_pager_data('#page_tb > tbody',values);
    }

   </script>
SCRIPT;
sec_scripts($scripts);
?>


