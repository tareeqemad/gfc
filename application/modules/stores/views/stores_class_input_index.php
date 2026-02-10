<?php
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 14/12/14
 * Time: 09:40 ص
 */

$MODULE_NAME= 'stores';
$TB_NAME= 'stores_class_input';
$TB_NAME2= 'stores_class_input_detail';

$get_url =base_url("$MODULE_NAME/$TB_NAME/get_id");
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$edit_url =base_url("$MODULE_NAME/$TB_NAME/edit");
$delete_url =base_url("$MODULE_NAME/$TB_NAME/delete");
//$adopt_url=base_url("$MODULE_NAME/$TB_NAME/adopt");


$customer_url =base_url('payment/customers/public_index');
$delete_details_url=base_url("$MODULE_NAME/$TB_NAME2/delete");

$select_items_url=base_url("$MODULE_NAME/classes/public_index");
echo AntiForgeryToken();
?>

<div class="row">
    <div class="toolbar">

        <div class="caption">   إدخال جديد للمخازن  </div>

        <ul><?php
            if(HaveAccess($create_url))
                echo "<li><a  href='{$create_url} '><i class='glyphicon glyphicon-plus'></i>جديد </a> </li>";
        /*    if(HaveAccess($get_url) || HaveAccess($edit_url)) echo "<li><a
  onclick='javascript:get_val(get_id());'
  href='javascript:;'><i class='glyphicon glyphicon-edit'></i>تحرير</a> </li>";*/
            if(HaveAccess($delete_url)) echo "<li><a onclick='javascript:{$TB_NAME}_delete();' href='javascript:;'><i class='glyphicon glyphicon-remove'></i>حذف</a> </li>";
            ?>
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

    function {$TB_NAME}_delete(){
        var url = '{$delete_url}';
        var tbl = '#{$TB_NAME}_tb';
        var container = $('#' + $(tbl).attr('data-container'));
        var val = [];
        $(tbl + ' .checkboxes:checked').each(function (i) {
            val[i] = $(this).val();
        });

        if(val.length > 0){
            if(confirm('هل تريد بالتأكيد حذف '+val.length+' سجلات وحذف تفاصيلها ؟!!')){
                ajax_delete(url, val ,function(data){
                    success_msg('رسالة','تم حذف السجلات بنجاح ..');
                    container.html(data);
                });
            }
        }else
            alert('يجب تحديد السجلات المراد حذفها');
    }
    function get_val(id){
    alert(id);
//get_to_link('( {$get_url}).'/'.id.'/'.( isset($action)?$action.'/':''));
   //{$TB_NAME}_get(get_id());
    }



</script>

SCRIPT;

sec_scripts($scripts);

?>
