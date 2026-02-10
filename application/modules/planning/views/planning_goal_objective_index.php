<?php
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 01/04/18
 * Time: 08:47 ص
 */

$MODULE_NAME= 'planning';
$TB_NAME= 'planning_unit';
$manage_follow_branch =base_url("$MODULE_NAME/$TB_NAME/public_get_mange");
$get_url =base_url("$MODULE_NAME/$TB_NAME/get_id");
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");

$edit_url =base_url("$MODULE_NAME/$TB_NAME/edit");
$delete_url =base_url("$MODULE_NAME/$TB_NAME/delete");
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$get_page_url2 = base_url("$MODULE_NAME/$TB_NAME/get_page2");
$back_budget_tech_url=base_url("budget/Projects/archive");
$page=1;
echo AntiForgeryToken();
?>

    <div class="row">
    <div class="toolbar">

        <div class="caption"><?=$title?></div>

        <ul>

            <?php if( HaveAccess($create_url)):  ?><li><a  href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>جديد</a> </li><?php endif; ?>
            <li><a  href="<?=base_url('uploads/statgic_plan_user_manual.pdf')?>" target="_blank"><i class="icon icon-question-circle"></i>دليل المستخدم</a>


        </ul>

    </div>



    <div class="form-body">
        <div class="modal-body inline_form">
        </div>

        <form class="form-vertical"  id="<?=$TB_NAME?>_form" >
            <div class="modal-body inline_form">
                <div class="form-group col-sm-4" >

                    <label class="control-label">الخطة</label>
                    <div>
                        <select  name="dp_plan" id="dp_plan"  data-curr="false"  class="form-control"  >
                           <?php foreach($stratgic_plan as $row) :?>
                                <option value="<?= $row['SER'] ?>">
                                    <?= $row['TITLE_DP'] ?>
                                </option>


                            <?php endforeach; ?>
                        </select>

                    </div>
                </div>


            </div>


        </form>


        <div class="modal-footer">


         </div>
        <div id="msg_container"></div>

        <div id="container">
            <?=modules::run($get_page_url);?>
        </div>
    </div>

    </div>

<?php
$scripts = <<<SCRIPT
<script type="text/javascript">

 $(document).ready(function() {

          $('#dp_plan').select2().on('change',function(){
                  get_data('{$get_page_url2}',{page: $(this).val()} ,function(data){

            $('#container').html(data);
        },'html');

        });





});



</script>

SCRIPT;

sec_scripts($scripts);

?>