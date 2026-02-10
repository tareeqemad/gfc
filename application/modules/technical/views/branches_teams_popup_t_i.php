<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 28/02/16
 * Time: 10:50 ص
 */

$MODULE_NAME = 'technical';
$TB_NAME = 'branches_teams';
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/public_team_get_page");

?>

<div class="form-body">

    <div class="modal-body inline_form">
        <form id="<?= $TB_NAME ?>_form" method="get" action="<?= $get_page_url ?>" role="form" novalidate="novalidate">

            <div class="form-group col-sm-5">
                <label class="control-label">الفريق</label>

                <div>
                    <select name="team_ser" id="dp_team_ser" class="form-control"/>
                    <option></option>
                    <?php foreach ($team_all as $row) : ?>
                        <option value="<?= $row['TEAM_SER'] ?>"><?= $row['TEAM_NAME'] ?></option>
                    <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group col-sm-1">
                <label class="control-label">&nbsp;</label>

                <div>
                    <button type="button" onclick="javascript:<?= $TB_NAME ?>_search();" class="btn btn-success">بحث
                    </button>
                </div>
            </div>

        </form>
    </div>
    <div>
        <a class="btn-xs btn-danger" onclick="javascript:select_choose();" href="javascript:;"><i
                class="icon icon-download"></i> إدراج المختار </a>
    </div>

    <div id="container">
        <?= modules::run("$MODULE_NAME/$TB_NAME/public_team_get_page", $text, $team_ser); ?>
    </div>
    <div>
        <a class="btn-xs btn-danger" onclick="javascript:select_choose();" href="javascript:;"><i
                class="icon icon-download"></i> إدراج المختار </a>
    </div>

</div>

<?php
$scripts = <<<SCRIPT
<script>

    $('#dp_team_ser').select2();

        if (typeof parent.restCount == 'function' ) {
           // parent.restCount();
        }


    function show_row_details(id , name,mob,multi){
        parent.$('#h_$text').val(id);
        parent.$('#$text').val(name);
        parent.$('#mob_$text').val(mob);

        parent.$('#report').modal('hide');


        if (typeof parent.afterSelect == 'function' && !multi) {
            parent.afterSelect( parent.$('#$text').closest('tr'),id,name);
        }
    }


    function select_choose(){

        $('.checkboxes:checked').each(function(i){
            var obj = jQuery.parseJSON($(this).attr('data-val'));
            if($('#{$text}').val() == ''){
                show_row_details(obj.CUSTOMER_ID,obj.CUSTOMER_ID_NAME,obj.JAWWAL,false);
            }else{
                parent.AddRowWithData(obj.CUSTOMER_ID,obj.CUSTOMER_ID_NAME,obj.JAWWAL,'team');
            }
        });

        $('.checkboxes:checked').prop('checked',false);

    }

    function {$TB_NAME}_search(){
        get_data('{$get_page_url}/$text/'+check_vars($('#dp_team_ser').val()),{},function(data){
            $('#container').html(data);
        },'html');
    }

    function check_vars(val){
        if(val==null || val=='' || val==undefined)
            return -1;
        else
            return val;
    }

</script>
SCRIPT;
sec_scripts($scripts);
?>
