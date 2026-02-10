<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 29/01/20
 * Time: 09:43 ص
 */

$MODULE_NAME= 'training';
$TB_NAME= 'advertisement';
$get_page = base_url("$MODULE_NAME/$TB_NAME/get_page");
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
                    <legend >  الاستعلام عن الاعلانات</legend>



                    <div class="modal-body inline_form">

                        <div class="form-group col-sm-2">

                            <label class="control-label"> نوع الاعلان</label>
                            <select name="adver_type" id="dp_adver_type" class="form-control sel2">
                                <option value="0">_______</option>
                                <?php foreach($adver_type as $row) :?>
                                    <option value="<?= $row['CON_NO'] ?>" > <?= $row['CON_NAME'] ?> </option>

                                <?php endforeach; ?>
                            </select>

                        </div>

                        <div class="form-group col-sm-2">

                            <label class="control-label">رقم الاعلان</label>
                            <input type="text" name="adver_id" id="txt_adver_id"
                                   placeholder="<?= date('Y')/1; ?> / 1" class="form-control">
                        </div>

                        <div class="form-group col-sm-2">

                            <label class="control-label">المسمى الوظيفي</label>
                            <input type="text" name="adver_title" id="txt_adver_title"
                                   placeholder="المسمى الوظيفي" class="form-control">

                        </div>

                        <div class="form-group col-sm-2">
                            <label class="control-label">بداية الاعلان</label>
                            <div>
                                <input type="text"  placeholder="تاريخ بداية الاعلان"
                                       name="start_date"
                                       data-type="date" data-date-format="DD/MM/YYYY"
                                       id="txt_start_date" class="form-control"  >
                            </div>
                        </div>

                        <div class="form-group col-sm-2">
                            <label class="control-label">نهاية الاعلان</label>
                            <div>
                                <input type="text" placeholder="تاريخ نهاية الاعلان"
                                       data-type="date" data-date-format="DD/MM/YYYY"
                                       name="end_date"
                                       id="txt_end_date" class="form-control"  >
                            </div>
                        </div>


                        <br>
                    </div>


                </fieldset>


            </form>

            <div class="modal-footer">

                <button type="button" onclick="javascript:search();" class="btn btn-success"> إستعلام</button>
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