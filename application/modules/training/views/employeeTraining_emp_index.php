<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 13/07/20
 * Time: 09:57 ص
 */



$MODULE_NAME= 'training';
$TB_NAME= 'employeeTraining';
$get_page = base_url("$MODULE_NAME/$TB_NAME/get_emp_page");
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
                    <legend >الاستعلام عن دورات الموظف </legend>

                    <div class="modal-body inline_form">

                        <div class="form-group col-sm-2">
                            <label class="control-label"> الموظف</label>
                            <select name="emp_no" id="dp_emp_no" class="form-control sel2">
                                <option value="">_________</option>
                                <?php foreach($emp_no_cons as $row) :?>
                                    <option  value="<?=$row['EMP_NO']?>" >
                                        <?= $row['EMP_NO'].": ".$row['EMP_NAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>


                        <div class="form-group col-sm-2">
                            <label class="control-label">التاريخ / من</label>
                            <div>
                                <input type="text" data-type="date" placeholder="<?=date('Y/m/d'); ?>"
                                       data-date-format="DD/MM/YYYY" name="start_date"
                                       id="txt_start_date" class="form-control" >
                            </div>
                        </div>

                        <div class="form-group col-sm-2">
                            <label class="control-label">التاريخ / الى</label>
                            <div>
                                <input type="text"  data-type="date"
                                       data-date-format="DD/MM/YYYY" placeholder="<?=date('Y/m/d'); ?>" name="end_date"
                                       id="txt_end_date" class="form-control" >
                            </div>
                        </div>


                        <br>
                    </div>


                </fieldset>


            </form>

            <div class="modal-footer">
                <button type="button" onclick="javascript:emp_courses_search();" class="btn btn-success"> إستعلام</button>
                <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>
            </div>
            <div id="msg_container"></div>

            <div id="container">
                <?php  //echo  modules::run($get_page,$page); ?>

            </div>
        </div>

    </div>


<?php
$scripts = <<<SCRIPT
<script type="text/javascript">


</script>

SCRIPT;
sec_scripts($scripts);
?>