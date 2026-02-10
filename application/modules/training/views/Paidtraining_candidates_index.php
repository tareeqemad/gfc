<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 11/07/20
 * Time: 09:15 ص
 */


$MODULE_NAME= 'training';
$TB_NAME= 'Paidtraining';
$get_page = base_url("$MODULE_NAME/$TB_NAME/public_candidate_get_page");
$page=1;
$post_url = base_url("$MODULE_NAME/$TB_NAME/" . ($action == 'index' ? 'create' : $action));
$attach = uniqid();

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
                    <legend >الاستعلام عن المتدربين المرشحين للمقابلات </legend>



                    <div class="modal-body inline_form">

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

                            <label class="control-label"> المجال</label>
                            <input type="text" name="field" id="txt_field"
                                   placeholder="اسم المجال" class="form-control">

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
                    </div>


                </fieldset>


            </form>

            <div class="modal-footer">


                <button type="button" onclick="javascript:search_candidate();" class="btn btn-success"> إستعلام</button>
                <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>


            </div>
            <div id="msg_container"></div>

            <div id="container">
                <?php // echo  modules::run($get_page,$page); ?>

            </div>
        </div>

    </div>
     <div class="modal fade" id="myModal">
        <div id="myModal" role="dialog">
            <div class="modal-dialog">

                <div id="SSS" class="modal-content">
                    <div class="modal-header">
                        <h3 id="title" class="modal-title">اضافة متدرب</h3>
                    </div>
                    <div class="modal-body">
                        <div class="modal-body">
                            <form id="<?= $TB_NAME ?>_form" method="post" action="<?php echo $post_url ?>">


                                <div class="row">

                                    <div class="form-group col-sm-6">
                                            <label class="control-label"> المقر</label>
                                            <select name="branch" id="dp_branch" class="form-control sel2">
                                                <option value="0">_______</option>
                                                <?php foreach($branches as $row) :?>
                                                    <?php
        if($row['NO']<>1)
        { ?> <option value="<?= $row['NO'] ?>" > <?= $row['NAME'] ?> </option>
                                                    <?php
        }
        ?>
                                                <?php endforeach; ?>
                                            </select>
                                    </div>

                                    <div class="form-group col-sm-6">
                                        <label class="control-label">الادارة</label>
                                        <div>
                                            <select name="manage" id="dp_manage" class="form-control sel2">
                                                <option value="0">_______</option>
                                                <?php foreach($manage as $row) :?>
                                                    <option value="<?= $row['ST_ID'] ?>" > <?= $row['ST_NAME'] ?> </option>

                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                    </div>

                                    <div class="form-group col-sm-12">
                                        <label class="control-label">قيمة الحافز</label>
                                        <div>
                                            <input type="text"
                                                   placeholder="500 شيكل"
                                                   name="incentive_value"
                                                   id="txt_incentive_value"
                                                   data-val-required="حقل مطلوب"
                                                   data-val="true"
                                                   class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-6">
                                        <label class="control-label">مدة التدريب - شهر</label>
                                        <div>
                                            <input type="text"
                                                   name="training_period"
                                                   placeholder="3"
                                                   id="txt_training_period"
                                                   data-val-required="حقل مطلوب"
                                                   data-val="true"
                                                   class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-6">
                                        <label class="control-label">تاريخ بداية التدريب</label>
                                        <div>
                                            <input type="text"
                                                   name="start_date"
                                                   data-type="date" data-date-format="DD/MM/YYYY"
                                                   id="txt_start_date" class="form-control"  >
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-12">
                                        <label class="control-label">مشرف التدريب</label>
                                        <div>
                                            <select name="responsible_emp" id="dp_responsible_emp" class="form-control sel2" >
                                                <option value="">_________</option>
                                                <?php foreach($emp_no_cons as $row) :?>
                                                    <option value="<?=$row['EMP_NO']?>" >
                                                        <?= $row['EMP_NO'].": ".$row['EMP_NAME']?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <input type="hidden" name="id_nu" id="txt_id_nu" >
                                    <input type="hidden" name="id_name" id="txt_id_name" >

                                    <div class="form-group col-sm-12">
                                        <label class="control-label">الملاحظات</label>
                                        <div>
                                            <textarea name="notes"
                                                      id="txt_notes"
                                                      class="form-control "></textarea>

                                        </div>
                                    </div>
                                </div>



                                <div class="modal-footer">
                                    <?php if (  HaveAccess($post_url)   ) : ?>
                                        <button type="submit" data-action="submit" id="save_trainee" class="btn btn-primary">حفظ البيانات</button>
                                    <?php endif; ?>
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">اغلاق</button>

                                </div>


                            </form>

                        </div>
                    </div>
                </div>
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