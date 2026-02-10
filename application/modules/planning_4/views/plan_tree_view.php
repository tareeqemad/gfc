<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 20/03/18
 * Time: 10:09 ص
 */
$MODULE_NAME= 'planning';
$TB_NAME= 'planning';
$get_goal_t_project_url= base_url("$MODULE_NAME/$TB_NAME/get_goal_t_project");

?>
<?= AntiForgeryToken() ?>
<div class="row">
    <div class="toolbar">

        <div class="caption"><?=$title;?></div>

        <ul>

            <li><a  onclick="$.fn.tree.expandAll()" href="javascript:;"><i class="glyphicon  glyphicon-sort-by-attributes-alt"></i>توسيع</a> </li>
            <li><a  onclick="$.fn.tree.collapseAll()" href="javascript:;"><i class="glyphicon  glyphicon-sort-by-attributes"></i>طي</a> </li>
             <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
        </ul>

    </div>

    <div class="form-body">

        <div id="msg_container"></div>
        <div class="form-group">
            <div class="input-group col-sm-3">
                <span class="input-group-addon">  <i class="icon icon-search"></i></span>

                <input type="text" id="search-tree" class="form-control" placeholder="بحث">
            </div>
        </div>
        <?= $tree ?>

    </div>

</div>

<?php

$scripts = <<<SCRIPT

<script>

    $(function () {
        $('#plan_tree').tree();
        $.each( $('#plan_tree .is_active') , function(i,item){

            if( parseInt($(this).attr('data-is-active')) == 0 ){
              $(this).html('<i class="icon  icon-share">غير مفعل</i>');
            $(this).css("color", "#B8AFAF");
            }


    });
        $('.is_active').on('click',  function (e) {

      if (confirm('هل تريد تفعيل هذا المشروع؟؟')) {


 $(this).html('<i class="icon  icon-share">مفعل</i>');
 $(this).css("color", "#24A1F5");

}






    });

});

</script>
SCRIPT;

sec_scripts($scripts);

?>
