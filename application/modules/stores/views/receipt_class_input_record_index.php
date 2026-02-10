<?php
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 27/11/14
 * Time: 10:33 ص
 */

$MODULE_NAME= 'stores';
$TB_NAME= 'receipt_class_input';
$TB_NAME2= 'receipt_class_input_detail';

$get_url =base_url("$MODULE_NAME/$TB_NAME/get_id");
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$edit_url =base_url("$MODULE_NAME/$TB_NAME/edit");
$delete_url =base_url("$MODULE_NAME/$TB_NAME/delete");
$adopt_url=base_url("$MODULE_NAME/$TB_NAME/adopt");
$record_url=base_url("$MODULE_NAME/$TB_NAME/record");
$return_url=base_url("$MODULE_NAME/$TB_NAME/returnp");
//$get_details_url =base_url("$MODULE_NAME/$TB_NAME2/get_page");
$customer_url =base_url('payment/customers/public_index');
$delete_details_url=base_url("$MODULE_NAME/$TB_NAME2/delete");

$select_items_url=base_url("$MODULE_NAME/classes/public_index");
echo AntiForgeryToken();
?>
<div class="row">
    <div class="toolbar">

        <div class="caption"> محاضر الفحص والاستلام </div>

        <ul>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>

        </ul>

    </div>

    <div class="form-body">
        <div id="msg_container"></div>

        <div id="container">
            <?=modules::run("$MODULE_NAME/$TB_NAME/get_page"); ?>
        </div>
    </div>

</div>


<?php

$scripts = <<<SCRIPT
<script type="text/javascript">
    var count = 0;
     var count1 = 0;
    $(document).ready(function() {
        $('#{$TB_NAME}_tb').dataTable({
            "lengthMenu": [ [10,20,30,40,50,100, -1], [10,20,30,40,50,100, "الكل"] ],
            "sPaginationType": "full_numbers"
        });
    });


    function get_val(id){
    alert(id);
//get_to_link('( {$get_url}).'/'.id.'/'.( isset($action)?$action.'/':''));
   //{$TB_NAME}_get(get_id());
    }



</script>

SCRIPT;

sec_scripts($scripts);

?>
