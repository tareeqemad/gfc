<?php
/**
 * Created by PhpStorm.
 * User: ljasser
 * Date: 09/02/19
 * Time: 10:58 ص
 */
$MODULE_NAME='gis';
$TB_NAME="Customer_cables_Controller";
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/public_get_page");
$edit_url =base_url("$MODULE_NAME/$TB_NAME/edit");

$get_url =base_url("$MODULE_NAME/$TB_NAME/get_id");
?>

<script> var show_page=true; </script>
<div class="row">
    <div class="toolbar">
        <div class="caption"><?= $title ?></div>

        <ul>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
        </ul>

    </div>
</div>
<div class="form-body">
    <form class="form-vertical" id="<?=$TB_NAME?>_form" method="post">
        <div class="modal-body inline_form">
            <div class="form-group col-md-5">
                <label  class="control-label1">نوع ومساحة مقطع كابل المشترك </label>
                <div>
                    <input  type="text" name="CABLE_TYPE" placeholder= "عن طريق نوع ومساحة مقطع كابل المشترك"id="CABLE_TYPE_dp" class="form-control ">
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" onclick="javascript:search();" class="btn btn-success"> إستعلام</button>
            <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>
        </div>
</div>

</form>




<!--------------------------------------- >
<!-------------------------------------->
<div id="msg_container"> </div>
<div id="container">
    <?=modules::run($get_page_url,$page,$CABLE_TYPE); ?>

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
        {page:1, CABLE_TYPE:$('#CABLE_TYPE_dp').val() };
        if(add_page==0)
            delete values.page;
        return values;
    }

  function search(){
        var values= values_search(1);
        get_data('{$get_page_url}',values ,function(data){
            $('#container').html(data);
        },'html');
    }



</script>
SCRIPT;
sec_scripts($scripts);
?>
<!----------------End java script code--------------->