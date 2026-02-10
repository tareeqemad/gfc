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
        <div id="msg_container"></div>

        <div id="container">
            <?=modules::run($get_page_url);?>
        </div>

    </div>
</div>

<?php
$scripts = <<<SCRIPT
<script>
    function show_row_details(id){
        get_to_link('{$get_url}/'+id);
    }
</script>
SCRIPT;
sec_scripts($scripts);
?>
