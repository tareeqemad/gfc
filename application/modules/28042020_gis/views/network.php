<?php
/**
 * Created by PhpStorm.
 * User: ljasser
 * Date: 03/01/19
 * Time: 09:14 ص
 */

$MODULE_NAME='gis';
$TB_NAME="net_controller";
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$get_page_network_url= base_url("$MODULE_NAME/$TB_NAME/public_get_list_network");
$edit_url =base_url("$MODULE_NAME/$TB_NAME/edit");
$delete_url =base_url("$MODULE_NAME/$TB_NAME/delete");
$get_url =base_url("$MODULE_NAME/$TB_NAME/get_id");

?>

<script> var show_page=true; </script>
<div class="row">
    <div class="toolbar">
        <div class="caption"><?=$title ?></div>

        <ul>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
        </ul>

    </div>
</div>
<div class="form-body">
    <form class="form-vertical" id="<?=$TB_NAME?>_form" method="post">
        <div class="modal-body inline_form">
            <div class="form-group col-md-3">
                <label class="control-label1"> رقم عامود/غرفة البداية </label>
                <div>
                    <input type="text" name="S_MV_POLE_ROOM_CODE" placeholder="عن طريق عمود البداية"    id="Start_MV_Pole_or_Room_Code_id" class="form-control ">
                </div>
            </div>
            <!------------------------------------------------------------------->
            <div class="form-group col-md-3">
            <label class="control-label1">رقم عامود/غرفة النهاية</label>
            <div>
                <input type="text" name="E_MV_POLE_ROOM_CODE" placeholder="عن طريق عمود النهاية"  id="E_MV_POLE_ROOM_CODE_id" class="form-control ">
            </div>
        </div>
            <!------------------------------------------------------------------->
            <div class="form-group col-md-3">
                <label class="control-label1"> نوع التركيب</label>
                <div>
                    <input type="text" name="NETWORK_INST_TYPE" placeholder="عن طريق نوع التركيب" id="Network_Installation_Type_id" class="form-control ">
                </div>
            </div>
            <!----------------------------------------------------------->

        </div>
        <div class="modal-footer">
            <button type="button" onclick="javascript:search();" class="btn btn-success"> إستعلام</button>
            <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>
        </div>


</form>

<div id="msg_container"></div>
<div id="container">
    <?=modules::run($get_page_network_url,$page,$S_MV_POLE_ROOM_CODE,$E_MV_POLE_ROOM_CODE,$NETWORK_INST_TYPE); ?>
</div>
</div>
<!----------------java script code--------------->
<?php
$scripts = <<<SCRIPT
<script>


 function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
        $('.sel2').select2('val','');
    }



 function values_search(add_page){
        var values=
        {page:1, S_MV_POLE_ROOM_CODE:$('#Start_MV_Pole_or_Room_Code_id').val(),E_MV_POLE_ROOM_CODE:$('#E_MV_POLE_ROOM_CODE_id').val(),
         NETWORK_INST_TYPE:$('#Network_Installation_Type_id').val()};
        if(add_page==0)
            delete values.page;
        return values;
    }

  function search(){
        var values= values_search(1);
        get_data('{$get_page_network_url}',values ,function(data){
            $('#container').html(data);
        },'html');
    }



</script>
SCRIPT;
sec_scripts($scripts);
?>
<!----------------End java script code--------------->