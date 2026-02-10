<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 13/02/20
 * Time: 09:47 ص
 */



$MODULE_NAME= 'training';
$TB_NAME= 'unPaidTrain';
$get_page = base_url("$MODULE_NAME/$TB_NAME/get_page");
$page=1;
$post_url = base_url("$MODULE_NAME/$TB_NAME/" . ($action == 'index' ? 'create' : $action));
$attach = uniqid();

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
                    <legend >المتدربين غير مدفوع الأجر</legend>



                    <div class="modal-body inline_form">

                        <div class="form-group col-sm-2">
                                <label class="control-label"> المقر</label>
                                <select name="branch" id="dp_branch" class="form-control sel2">
                                    <option value="0">_______</option>
                                    <?php foreach($branches as $row) :?>
                                         <option value="<?= $row['NO'] ?>" > <?= $row['NAME'] ?> </option>
                                    <?php endforeach; ?>
                                </select>

                        </div>

                        <div class="form-group col-sm-2">

                            <label class="control-label"> الادارة</label>
                            <select name="manage" id="dp_manage" class="form-control sel2">
                                <option value="0">_______</option>
                                <?php foreach($manage as $row) :?>
                                    <option value="<?= $row['ST_ID'] ?>" > <?= $row['ST_NAME'] ?> </option>

                                <?php endforeach; ?>
                            </select>

                        </div>


                        <div class="form-group col-sm-2">

                            <label class="control-label">رقم الهوية</label>
                            <input type="text" name="id" id="txt_id"
                                   placeholder="رقم الهوية" class="form-control">
                        </div>

                        <div class="form-group col-sm-2">

                            <label class="control-label">الاسم</label>
                            <input type="text" name="name" id="txt_name"
                                   placeholder="الاسم" class="form-control">
                        </div>
                        <br>

                        <div class="form-group col-sm-2">

                            <label class="control-label">تاريخ بداية التدريب</label>
                            <input type="text" data-type="date" placeholder="<?=date('Y/m/d'); ?>"
                                   data-date-format="DD/MM/YYYY" name="start_date"
                                   id="txt_start_date" class="form-control" >
                        </div>


                        <div class="form-group col-sm-2">

                            <label class="control-label">تاريخ نهاية التدريب </label>
                            <input type="text"  data-type="date"
                                   data-date-format="DD/MM/YYYY" placeholder="<?=date('Y/m/d'); ?>" name="end_date"
                                   id="txt_end_date" class="form-control" >
                        </div>



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