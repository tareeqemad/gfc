<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 08/09/19
 * Time: 01:46 م
 */
$TB_NAME = 'indecator_permissions';
?>
<div class="row">

        <div class="toolbar"><div class="caption"><?=$title;?></div>
                    <ul>
                        <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
                    </ul>

        </div>

    <div class="form-body">
        <div class="modal-body inline_form">
        </div>


        <form class="form-vertical"  id="<?=$TB_NAME?>_form" >
            <div class="modal-body inline_form">



                <div class="form-group col-sm-3">
                    <label class="col-sm-2 control-label">المقر</label>
                    <div>
                        <select name="branches" data-val="true"  data-val-required="حقل مطلوب"  id="dp_branches" class="form-control">
                            <option></option>
                            <?php foreach($branches as $row) :?>
                                <option value="<?= $row['NO'] ?>" <?PHP if ($row['NO']==((count($rs)>0)?$rs['BRANCH']:0)){ echo " selected"; } ?> ><?= $row['NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="field-validation-valid" data-valmsg-for="branches" data-valmsg-replace="true"></span>
                    </div>
                </div>
                <!-------------------------------------الادارة------------------------------------------>
                <div class="form-group col-sm-3">
                    <label class="col-sm-2 control-label">الادارة</label>
                    <div>
                        <select name="manage_follow_id" data-val="true"  data-val-required="حقل مطلوب"  id="dp_manage_follow_id" class="form-control">
                            <option></option>

                        </select>
                        <span class="field-validation-valid" data-valmsg-for="manage_follow_id" data-valmsg-replace="true"></span>
                    </div>
                </div>
                <!-------------------------------------------الدائرة------------------------->
                <div class="form-group col-sm-3">
                    <label class="col-sm-2 control-label">الدائرة</label>
                    <div>
                        <select name="cycle_follow_id" data-val="true"  data-val-required="حقل مطلوب" id="dp_cycle_follow_id" class="form-control">
                            <option></option>

                        </select>
                        <span class="field-validation-valid" data-valmsg-for="cycle_follow_id" data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group col-sm-3">
                    <label class="col-sm-2 control-label">المسؤولية</label>
                    <div>
                        <select name="cycle_follow_id" data-val="true"  data-val-required="حقل مطلوب" id="dp_cycle_follow_id" class="form-control">
                            <option></option>

                        </select>
                        <span class="field-validation-valid" data-valmsg-for="cycle_follow_id" data-valmsg-replace="true"></span>
                    </div>
                </div>


                <div class="form-group col-sm-3">
                    <label class="col-sm-2 control-label">الموظف</label>
                    <div>
                        <select name="cycle_follow_id" data-val="true"  data-val-required="حقل مطلوب" id="dp_cycle_follow_id" class="form-control">
                            <option></option>

                        </select>
                        <span class="field-validation-valid" data-valmsg-for="cycle_follow_id" data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group col-sm-3">
                    <label class="col-sm-2 control-label">الفاعلية</label>
                    <div>
                        <select name="cycle_follow_id" data-val="true"  data-val-required="حقل مطلوب" id="dp_cycle_follow_id" class="form-control">
                            <option></option>

                        </select>
                        <span class="field-validation-valid" data-valmsg-for="cycle_follow_id" data-valmsg-replace="true"></span>
                    </div>
                </div>



                <br>

            </div>


        </form>


        <div class="modal-footer">

            <button type="button" onclick="javascript:search();" class="btn btn-success"> حفظ</button>
            <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>


        </div>

    </div>

</div>


<?php
$scripts = <<<SCRIPT
<script type="text/javascript">


   $('#dp_branches').select2();


</script>
SCRIPT;
sec_scripts($scripts);
?>
