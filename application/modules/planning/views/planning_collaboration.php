<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 18/03/18
 * Time: 08:46 ص
 */
$MODULE_NAME= 'planning';
$TB_NAME= 'planning';
$backs_url=base_url("$MODULE_NAME/$TB_NAME"); //$action
$get_url =base_url("$MODULE_NAME/$TB_NAME/get_id");
$create_url =base_url("$MODULE_NAME/$TB_NAME/create_without_cost");
$edit_url =base_url("$MODULE_NAME/$TB_NAME/edit");
$delete_url =base_url("$MODULE_NAME/$TB_NAME/delete");
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page_collaboration");
$save_all_url = base_url("$MODULE_NAME/$TB_NAME/save_all_evaluate");
$adopt_all_url= base_url("$MODULE_NAME/$TB_NAME/adopt_all_evaluate");
$post_url= base_url("$MODULE_NAME/$TB_NAME/save_all_evaluate");
$select_items_url =base_url("$MODULE_NAME/$TB_NAME/public_collobration_project");
$page=1;
echo AntiForgeryToken();
?>

<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>

            <?php  if( HaveAccess($backs_url)):  ?><li><a  href="<?= $backs_url ?>"><i class="icon icon-reply"></i> </a> </li><?php  endif; ?>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>

        </ul>

    </div>



    <div class="form-body">
        <div class="modal-body inline_form">
        </div>

        <form class="form-vertical" id="<?=$TB_NAME?>_form" method="post" action="<?=$post_url?>" role="form" novalidate="novalidate">



            <div id="msg_container"></div>

            <div id="container">
                <?=modules::run($get_page_url,$page);?>
            </div>
        </form>



    </div>

</div>



<?php
$scripts = <<<SCRIPT
<script type="text/javascript">

     $('#planning_tb tbody').on('click', function () {
     id= ( $("tbody>[class~='selected']").attr('data-id') );
//alert(id);
_showReport('$select_items_url/'+id);
        } );
</script>

SCRIPT;

sec_scripts($scripts);

?>
