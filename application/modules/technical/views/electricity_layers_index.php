<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 03/12/15
 * Time: 10:23 ص
 */

$MODULE_NAME= 'technical';
$TB_NAME= 'electricity_layers';

$get_url =base_url("$MODULE_NAME/$TB_NAME/get");
$get_page_url =base_url("$MODULE_NAME/$TB_NAME/get_page");

echo AntiForgeryToken();
?>

<div class="row">
    <div class="toolbar">
        <div class="caption"><?= $title ?></div>
        <ul>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
        </ul>
    </div>

    <div class="form-body">

        <form class="form-vertical" id="<?=$TB_NAME?>_form" >
            <div class="modal-body inline_form">

                <div class="form-group col-sm-2">
                    <label class="control-label"> نظام الاحمال </label>
                    <div>
                        <select name="electricity_load_system" id="dp_electricity_load_system" class="form-control" />
                        <option value=""> الكل </option>
                        <?php foreach($electricity_load_system_all as $row) :?>
                            <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                        <?php endforeach; ?>
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
            <?=modules::run($get_page_url, $electricity_load_system);?>
        </div>

    </div>
</div>

<?php
$scripts = <<<SCRIPT
<script>

    function show_row_details(id){
        get_to_link('{$get_url}/'+id);
    }

    function search(){
        var values= { electricity_load_system: $('#dp_electricity_load_system').val() };
        get_data('{$get_page_url}',values ,function(data){
            $('#container').html(data);
        },'html');
    }

</script>
SCRIPT;
sec_scripts($scripts);
?>
