<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 11/02/20
 * Time: 01:22 م
 */



$MODULE_NAME= 'training';
$TB_NAME= 'administrativeMovement';
$get_page = base_url("$MODULE_NAME/$TB_NAME/get_page");
$create_url = base_url("$MODULE_NAME/$TB_NAME/create");
$post_url = base_url("$MODULE_NAME/$TB_NAME/edit");
$post_fin_url = base_url("$MODULE_NAME/$TB_NAME/edit_fin");
$page=1;
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
                <fieldset  class="field_set">
                    <legend >الحركات الادارية</legend>
                    <form id="search_form">

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

                        <div class="form-group col-sm-2">
                        <label class="control-label"> حالة العقد</label>
                        <select name="contract_status" id="dp_contract_status" class="form-control sel2">
                            <?php foreach($contract_status as $row) :?>
                                <option value="<?= $row['CON_NO'] ?>" > <?= $row['CON_NAME'] ?> </option>
                            <?php endforeach; ?>
                        </select>
                        </div>
                        <br>
                    </div>
                    </form>

                </fieldset>

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


    <div class="modal fade" id="myModal">
        <div id="myModal" role="dialog">
            <div class="modal-dialog" style="width: 900px">
                <div id="SSS" class="modal-content">
                    <div class="modal-header">
                        <h3 id="title" class="modal-title">الحركات الادارية للمتدرب</h3>
                    </div>
                    <div class="modal-body">
                        <div class="modal-body">
                            <form id="test_form" method="post" action="<?php echo $post_url ?>">
                                <input type="hidden" name="trainee_ser" id="txt_trainee_ser" >
                                <div class="row" id="div_attendence">
                                </div>
                                <div class="modal-footer">
                                    <?php
                                   // if (  HaveAccess($post_url)   ) : ?>
                                        <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                                    <?php //endif; ?>
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">اغلاق</button>

                                </div>


                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="myModal_fin">
        <div id="myModal" role="dialog">
            <div class="modal-dialog" style="width: 900px">

                <div id="SSS" class="modal-content">
                    <div class="modal-header">
                        <h3 id="title" class="modal-title">الحركات الادارية للمتدرب / الادارة المالية</h3>
                    </div>
                    <div class="modal-body">
                        <div class="modal-body">
                            <form id="fin_form" method="post" action="<?php echo $post_fin_url ?>">
                                <input type="hidden" name="trainee_ser_fin" id="txt_trainee_ser_fin" >
                                <div class="row" id="div_attendence_fin">
                                </div>
                                <div class="modal-footer">
                                    <?php
                                   // if (  HaveAccess($post_url)   ) : ?>
                                    <a  style="background-color: #258cdd; color:#FFFFFF " onclick="saveFinAttendance();" class="btn"> حفظ البيانات</a>
                                    <?php //endif; ?>
                                    <button style="background-color: #dd8697; color: #FFFFFF" type="button" class="btn" data-dismiss="modal">اغلاق</button>
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