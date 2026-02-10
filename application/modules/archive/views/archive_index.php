<?php
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 14/02/15
 * Time: 12:07 م
 */

$MODULE_NAME= 'archive';
$TB_NAME= 'archive';


$get_url =base_url("$MODULE_NAME/$TB_NAME/get_id");
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$edit_url =base_url("$MODULE_NAME/$TB_NAME/edit");
$delete_url =base_url("$MODULE_NAME/$TB_NAME/delete");
$count = 1;



echo AntiForgeryToken();
?>

<div class="row">
    <div class="toolbar">

        <div class="caption"> أرشيف  </div>

        <ul><?php

      if(HaveAccess($create_url))

                echo "<li><a  href='{$create_url} '><i class='glyphicon glyphicon-plus'></i>جديد </a> </li>";

           if(HaveAccess($delete_url))
                echo "<li><a onclick='javascript:{$TB_NAME}_delete();' href='javascript:;'><i class='glyphicon glyphicon-remove'></i>حذف</a> </li>";
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
                //alert(val);
                if (!isNaN(data)){
                    success_msg('رسالة','تم حذف السجلات بنجاح ..');
                    container.html(data);
                    }
                    else
                    {
                   danger_msg('تحذير لم يتم حذف الارشيف الذي يحتوي على مرفقات لابد من خذف المرفقات اولا');
                    container.html(data);
                     }
                 });

            }


        }else
         alert('يجب تحديد السجلات المراد حذفها');
    }

</script>

SCRIPT;

sec_scripts($scripts);

?>
