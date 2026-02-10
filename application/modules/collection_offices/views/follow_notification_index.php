<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 12/12/19
 * Time: 12:18 م
 */


$MODULE_NAME= 'collection_offices';
$TB_NAME= 'Follow_notification';
$get_page = base_url("$MODULE_NAME/$TB_NAME/get_page");

$page=1;
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
            <div class="modal-body inline_form">
            </div>


            <form class="form-vertical"  id="<?=$TB_NAME?>_form" >
                <fieldset  class="field_set">
                    <legend >متابعة الاخطارات</legend>


                    <div class="form-group col-sm-2">
                        <label class="control-label">رقم الكشف</label>
                        <div>
                            <input type="text" data-val="true"  placeholder="رقم الكشف "
                                   name="disclosure_ser"
                                   id="txt_disclosure_ser" class="form-control"  >
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">رقم الاشتراك</label>
                        <div>
                            <input type="text" data-val="true"  placeholder="رقم الاشتراك"
                                   name="sub_no"
                                   id="txt_sub_no" class="form-control"  >
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">حالة الاخطار</label>
                        <div>
                            <select name="status" id="dp_status" class="form-control sel2">
                                <option value="0">_______________</option>
                                <?php foreach ($status as $row) : ?>
                                    <option value="<?= $row['CON_NO'] ?>"  ><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>


                </fieldset>


            </form>

            <div class="modal-footer">

                <button type="button" onclick="javascript:search();" class="btn btn-success"> إستعلام</button>
                <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>


            </div>


            <div id="container">
                <?php // echo  modules::run($get_page,$page); ?>

            </div>



            <div id="msg_container"></div>



        </div>

    </div>

<?php


$scripts = <<<SCRIPT

<script>



</script>

SCRIPT;

sec_scripts($scripts);
?>