<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 30/05/20
 * Time: 01:12 م
 */


$MODULE_NAME= 'collection_offices';
$TB_NAME= 'Follow_notification';
$get_page = base_url("$MODULE_NAME/$TB_NAME/get_page");
$adopt_url = base_url("$MODULE_NAME/$TB_NAME/adopt");
$page=1;
echo AntiForgeryToken();
?>

    <div class="row">

        <div class="toolbar">

            <div class="caption"><?= $title ?></div>

            <ul>
                <li><a onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
            </ul>

        </div>


        <div class="form-body">
            <div class="modal-body inline_form">
            </div>


            <form class="form-vertical"  id="<?=$TB_NAME?>_form" >
                <fieldset  class="field_set">
                    <legend >ارسال الاخطارات</legend>


                    <div class="modal-body inline_form">
                        <input type="hidden" value="<?php //echo $this->user->branch;?>" name="branch_no" id="txt_branch_no">

                        <div class="form-group col-sm-2">
                            <label class="control-label">رقم الاشتراك</label>
                            <div>
                                <input type="text" data-val="true"  placeholder="رقم الاشتراك"
                                       name="sub_no"
                                       id="txt_sub_no" class="form-control"  >
                            </div>
                        </div>

                        <br>
                    </div>


                </fieldset>


            </form>

            <div class="modal-footer">

                <button type="button" onclick="javascript:search_trading();" class="btn btn-success"> إستعلام</button>
                <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>


            </div>
            <div id="msg_container"></div>

            <div id="container">
                <?php // echo  modules::run($get_page,$page); ?>

            </div>
        </div>







    </div>

<?php


$scripts = <<<SCRIPT

<script>



</script>

SCRIPT;

sec_scripts($scripts);
?>