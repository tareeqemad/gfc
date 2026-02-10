<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 18/07/20
 * Time: 01:00 م
 */


$MODULE_NAME= 'training';
$TB_NAME= 'Paidtraining';
$page=1;
$post_url = base_url("$MODULE_NAME/$TB_NAME/create_added");
$adopt_url = base_url("$MODULE_NAME/$TB_NAME/adopt_sal");
$attach = uniqid();

echo AntiForgeryToken();
?>

    <div class="row">
        <div class="toolbar">
            <div class="caption"><?= $title ?></div>
            <ul>
                <?php if( HaveAccess($adopt_url)):  ?>
                    <li><a class="hidden" id="btn_adopt_sal"
                           href="<?= $adopt_url ?>">
                        <i class="glyphicon glyphicon-saved"></i>اعتماد</a>
                    </li>
                <?php endif; ?>
                <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
            </ul>
        </div>

        <div class="form-body">
            <div class="modal-body inline_form">
            </div>
            <input type="hidden" id="h_for_month" value="<?=date('Ym',strtotime("-1 month"))?>">
            <form class="form-vertical"  id="<?=$TB_NAME?>_form" action="<?= $adopt_url ?>" >
                <fieldset  class="field_set">
                    <input type="hidden" name="param_no" id="h_txt_param_no">
                    <legend ><?= $title ?></legend>
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
                            <label class="control-label">الشهر</label>
                            <input type="text" name="month_adopt" id="txt_for_month"
                                   value="<?=date('Ym',strtotime("-1 month"))?>" class="form-control">
                        </div>
                        <br>
                    </div>
                </fieldset>
            </form>
            <div class="modal-footer">
                <button type="button" onclick="javascript:search_salary();" class="btn btn-success"> إستعلام</button>
                <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>
            </div>

            <div id="msg_container"></div>
            <div id="container">
                <?php  //echo  modules::run($get_url,$page); ?>
            </div>
        </div>
    </div>

    <div class="modal fade" id="myModal">
        <div id="myModal" role="dialog">
            <div class="modal-dialog" style="width: 900px">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 id="title" class="modal-title">الاضافات/ الخصومات</h3>
                    </div>
                    <div class="modal-body">
                        <div class="modal-body">
                            <form id="paidTraining_added_form" method="post" action="<?php echo $post_url ?>">
                                <input type="hidden" name="trainee_ser_" id="txt_trainee_ser_" >
                                <input type="hidden" name="for_month_" id="txt_for_month_" >
                                <input type="hidden" name="contract_ser_" id="txt_contract_ser_" >
                                <div class="row" id="div_added_discounts">
                                </div>
                                <div class="modal-footer">
                                    <?php
                                    if (HaveAccess($post_url)) : ?>
                                        <button type="button" id="btn_save_added" class="btn btn-primary">حفظ البيانات</button>
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

</script>

SCRIPT;
sec_scripts($scripts);
?>