<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 27/07/20
 * Time: 11:25 ص
 */


$MODULE_NAME= 'training';
$TB_NAME= 'traineeRequest';
$create_url = base_url("$MODULE_NAME/$TB_NAME/create");
$page=1;
echo AntiForgeryToken();
?>

    <div class="row">

        <div class="toolbar">

            <div class="caption"><?= $title ?></div>

            <ul>
                <?php if (HaveAccess($create_url)): ?>
                    <li><a href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>جديد</a> </li>
                <?php endif; ?>
                <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
            </ul>

        </div>


        <div class="form-body">
            <div class="modal-body inline_form">
            </div>


            <form class="form-vertical"  id="<?=$TB_NAME?>_form" >
                <fieldset  class="field_set">
                    <legend ><?=$title?> </legend>
                    <div class="modal-body inline_form">
                        <div class="form-group col-sm-2">
                            <label class="control-label">رقم الهوية</label>
                            <div>
                                <input type="text"  placeholder="رقم الهوية"
                                       name="id_number"
                                       id="txt_id_number" class="form-control"  >
                            </div>
                        </div>

                        <div class="form-group col-sm-2">
                            <label class="control-label">الاسم</label>
                            <div>
                                <input type="text"  placeholder="الاسم"
                                       name="name"
                                       id="txt_name" class="form-control"  >
                            </div>
                        </div>
                        <br>
                    </div>
                </fieldset>
            </form>

            <div class="modal-footer">
                <button type="button" onclick="javascript:search_gedco();" class="btn btn-success"> إستعلام</button>
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
<script type="text/javascript">

 $(document).ready(function() {


   });

</script>

SCRIPT;
sec_scripts($scripts);
?>