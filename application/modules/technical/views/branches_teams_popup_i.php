<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 31/08/15
 * Time: 10:47 ص
 */

$MODULE_NAME= 'technical';
$TB_NAME= 'branches_teams';
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/public_get_page");

?>
<script> var show_page=true; </script>

<div class="form-body">

    <div class="modal-body inline_form">
        <form  id="<?=$TB_NAME?>_form" method="get" action="<?=$get_page_url?>" role="form" novalidate="novalidate">

            <div class="form-group col-sm-1">
                <label class="control-label">م الفريق</label>
                <div>
                    <input type="text" name="team_ser" id="txt_team_ser" class="form-control">
                </div>
            </div>

            <div class="form-group col-sm-1" style="display: none">
                <label class="control-label"> رقم الفرع </label>
                <div>
                    <input type="text" name="branch_id" id="txt_branch_id" class="form-control">
                </div>
            </div>

            <div class="form-group col-sm-1" style="display: none">
                <label class="control-label">رقم الفريق</label>
                <div>
                    <input type="text" name="team_id" id="txt_team_id" class="form-control">
                </div>
            </div>

            <div class="form-group col-sm-3">
                <label class="control-label"> اسم الفريق </label>
                <div>
                    <input type="text" name="team_name" id="txt_team_name" class="form-control">
                </div>
            </div>

            <div class="form-group col-sm-1">
                <label class="control-label">&nbsp;</label>
                <div>
                    <button type="button" onclick="javascript:<?=$TB_NAME?>_search();" class="btn btn-success">بحث</button>
                </div>
            </div>

        </form>
    </div>

    <div id="container">
        <?=modules::run("$MODULE_NAME/$TB_NAME/public_get_page",$text, $page, $team_ser, $team_name); ?>
    </div>

</div>

<?php
$scripts = <<<SCRIPT
<script>

    function show_row_details(id, team_name){
        parent.$('#h_$text').val(id);
        parent.$('#$text').val(team_name);
        parent.$('#report').modal('hide');

        if (typeof parent.get_team_data == 'function') {
            parent.get_team_data();
        }

    }

    function {$TB_NAME}_search(){
        get_data('{$get_page_url}/$text/1/'+check_vars($('#txt_team_ser').val())+'/'+check_vars($('#txt_team_name').val()),{},function(data){
            $('#container').html(data);
        },'html');
    }

    function check_vars(val){
        if(val==null || val=='' || val==undefined)
            return -1;
        else
            return val;
    }

    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
    }

</script>
SCRIPT;
sec_scripts($scripts);
?>
