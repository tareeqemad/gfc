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
        <div class="caption"><?=$title?> </div>
        <ul>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
        </ul>
    </div>
    <div class="form-body">
        <div class="row">
            <div class="col-md-6" style="padding-right: 120px;">
                <div class="alert alert-info">
                    <h4 class="text-center">
                        يتم جلب المحاضر المعتمدة من قبل لجنة اللوزام
                    </h4>
                </div>
            </div>
        </div>
        <div id="container">
            <?=modules::run("$MODULE_NAME/$TB_NAME/get_page_SuppliesServices"); ?>
        </div>
    </div>

</div>


<?php

$scripts = <<<SCRIPT
<script type="text/javascript">
 
    $(document).ready(function() {
        $('#{$TB_NAME}_tb').dataTable({
            "lengthMenu": [ [10,20,30,40,50,100, -1], [10,20,30,40,50,100, "الكل"] ],
            "sPaginationType": "full_numbers"
        });
    });

</script>

SCRIPT;

sec_scripts($scripts);

?>
