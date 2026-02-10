<?php
$MODULE_NAME= 'issues';
$TB_NAME= 'issues';
$back_url=base_url("$MODULE_NAME/$TB_NAME");

?>

<div class="row">
    <div class="toolbar">
        <div class="caption"><?= $title ?></div>
        <ul>
            <li><a  href="<?= $back_url ?>"><i class="icon icon-reply"></i> </a> </li>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
        </ul>
    </div>
</div>

<div class="form-body">
<div id="container">
<div class="modal-body inline_form">

<!------------------------------------------------------------ الشيكات المرجعة ----------------------------------->
<fieldset  class="field_set">
    <legend >الشيكات المرجعة</legend>

    <div class="tb_container">
        <table class="table"  id="myTable" data-container="container">
            <thead>
            <tr>

                <th  style="width: 16%">رقم الشيك المرجع</th>
                <th style="width: 16%" >مرفق عن صورة الشيك</th>
                <th  style="width: 4%"></th>
            </tr>
            </thead>

            <tbody>
            <tr>
                <td>
                    111
                </td>
                <td>
                    111
                </td>


                <td> <a target="_blank" href="<?=base_url("issues/checks/get_check_info/1" )?>"><i class='glyphicon glyphicon-share'></i></a> </td>


            </tr>
            </tbody>
        </table>
    </div>
</fieldset>


    <!-------------------------------------------------- بيانات الشيك------------------------------------------->


</div>
</div>
</div>

</div>




<?php
$scripts = <<<SCRIPT
<script type="text/javascript">
 $(document).ready(function() {
    $('#instalment_div').hide();
          $('#dp_check_action').on('change',function(){
        if($(this).val()=='instalments'){
            $('#instalment_div').show();
            }else{
                $('#instalment_div').hide();
            }
        });




});
</script>

SCRIPT;
sec_scripts($scripts);
?>

