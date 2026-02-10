<?php
/*
 * Created by PhpStorm.
 * User: lanakh
 * Date: 15/09/18
 * Time: 12:26 م
 */
$MODULE_NAME= 'indicator';//folder
$TB_NAME= 'indicatior';//controller
$TB_NAME1='indecator_info';//controller
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$backs_url=base_url("$MODULE_NAME/$TB_NAME"); //$action
$page=1;
$rs=($isCreate)? array() : $indicatior_data[0];
$manage_follow_id =modules::run("planning/planning/public_get_mange_b", (count($rs)>0)?$rs['BRANCH']:0);
$DET_TB_NAME = 'public_get_details';
$DET_TB_NAME2 = 'public_get_target_details';

$cycle_follow=modules::run("planning/planning/public_get_cycle_b", (count($rs)>0)?$rs['MANAGE_FOLLOW_ID']:-1);
echo AntiForgeryToken();
$manage_follow_branch = base_url("planning/planning/public_get_mange");
$cycle_follow_branch = base_url("planning/planning/public_get_cycle");
$cycle_exe_branch = base_url("planning/planning/public_get_cycle");
$indicator=base_url("$MODULE_NAME/$TB_NAME/public_get_sector");
$post_url= base_url("$MODULE_NAME/$TB_NAME1/".($action == 'index'?'create':$action));
$get_url=base_url("$MODULE_NAME/$TB_NAME1/get_indecator_info");
$adopt_url= base_url("$MODULE_NAME/$TB_NAME1/adopt");
$un_adopt_url= base_url("$MODULE_NAME/$TB_NAME1/unadopt");
$flag_url= base_url("$MODULE_NAME/$TB_NAME1/flag");
$un_flag_url= base_url("$MODULE_NAME/$TB_NAME1/unflag");
$manage_select_exe=(count($rs)>0)?$rs['MANAGE_FOLLOW_ID']:0;
$cycle_select_exe=(count($rs)>0)?$rs['CYCLE_FOLLOW_ID']:0;
$permmition_url=base_url("$MODULE_NAME/$TB_NAME1/public_permition");


?>
<div class="row">
    <div class="toolbar">
        <div class="caption"><?= $title ?></div>
        <ul>
            <?php  if( HaveAccess ($backs_url)): ?> <li> <a  href="<?= $backs_url ?>"><i class="icon icon-reply"></i> </a> </li><?php  endif; ?>
            <?php  if( HaveAccess($create_url)): ?> <li> <a  href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>جديد</a> </li><?php endif; ?>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
        </ul>
    </div>
</div>
<div class="form-body">
<div id="msg_container"></div>
<div id="container">
<form class="form-vertical" id="<?=$TB_NAME?>_form" method="post" action="<?=$post_url?>" role="form" novalidate="novalidate">
<div class="modal-body inline_form">
<fieldset>
    <!----------------------------------  بيانات المؤشر  ---------------------------------------------->
    <legend> بيانات المؤشر </legend>
    <div class="form-group col-sm-1">
        <label class="col-sm-1 control-label">مسلسل</label>
        <div>
            <input type="text"    name="indecator_ser" id="txt_indecator_ser" class="form-control" dir="rtl"
                   value="<?php echo (count($rs)>0)? $rs['INDECATOR_SER']: '' ;?>"
                   readonly>
            <span class="field-validation-valid" data-valmsg-for="indecator_ser" data-valmsg-replace="true"></span>
        </div>
    </div>
    <!-----------------------------------------  القطاع  ---------------------------------------------->
    <div class="form-group col-sm-2">
        <label class="col-sm-2 control-label"> القطاع </label>
        <div>
            <select name="sector"  id="dp_sector" class="form-control">
                <option></option>
                <?php foreach($sector as $row) :?>
                    <option value="<?= $row['CON_NO'] ?>" <?PHP if ($row['CON_NO']==((count($rs)>0)?$rs['SECTOR']:0)){ echo " selected"; } ?>>
                        <?= $row['CON_NAME'] ?></option>
                <?php endforeach; ?>
            </select>
            <span class="field-validation-valid" data-valmsg-for="sector" data-valmsg-replace="true"></span>
        </div>
    </div>
    <!------------------------------------التصنيف-------------------------------------------->
    <div class="form-group col-sm-2">
        <label class="col-sm-1 control-label">التصنيف</label>
        <div>
            <select name="class"   id="dp_class" class="form-control">
                <option></option>
                <?php if (count($rs)>0){?>
                    <?php foreach($class as $row) :?>
                        <option value="<?= $row['CON_NO'] ?>" <?PHP if ($row['CON_NO']==((count($rs)>0)?$rs['CLASS']:0)){ echo " selected"; } ?> ><?= $row['CON_NAME'] ?></option>
                    <?php endforeach; ?>
                <?php } ?>
            </select>
            <span class="field-validation-valid" data-valmsg-for="class" data-valmsg-replace="true"></span>
        </div>
    </div>

    <div class="form-group col-sm-2">
        <label class="col-sm-1 control-label">التصنيف الثاني</label>
        <div>
            <select name="second_class"   id="dp_second_class" class="form-control">
                <option></option>
                <?php if (count($rs)>0){?>
                    <?php foreach($class as $row) :?>
                        <option value="<?= $row['CON_NO'] ?>" <?PHP if ($row['CON_NO']==((count($rs)>0)?$rs['CLASS']:0)){ echo " selected"; } ?> ><?= $row['CON_NAME'] ?></option>
                    <?php endforeach; ?>
                <?php } ?>
            </select>
            <span class="field-validation-valid" data-valmsg-for="class" data-valmsg-replace="true"></span>
        </div>
    </div>


    <div class="form-group col-sm-2">
        <label class="col-sm-1 control-label">النظام</label>
        <div>
            <select name="sys_info"   id="dp_sys_info" class="form-control">
                <option value="1">معلومة</option>
                <option value="2">مؤشر</option>
                <!-- <option value="3">استيراد معلومة الى مؤشر</option> -->

            </select>
            <span class="field-validation-valid" data-valmsg-for="class" data-valmsg-replace="true"></span>
        </div>
    </div>

<div hidden class="form-group col-sm-2 import_info">
        <label class="col-sm-7 control-label">استيراد معلومة الى مؤشر</label>
        <div>
            <select name="sys_info"   id="dp_sys_info" class="form-control">
                <option value="1">معلومة1</option>
                <option value="2">معلومة2</option>
                <!-- <option value="3">استيراد معلومة الى مؤشر</option> -->

            </select>
            <span class="field-validation-valid" data-valmsg-for="class" data-valmsg-replace="true"></span>
        </div>
    </div>
    <!----------------------------------الترتيب----------------------------------------->
    <div class="form-group col-sm-1">
        <label class="col-sm-1 control-label">الترتيب</label>
        <div>
            <input type="text"    name="indecator_order" id="txt_indecator_order" class="form-control" dir="rtl" value="<?php echo (count($rs)>0)? $rs['INDECATOR_ORDER']: '' ;?>" <!---readonly-->
            <span class="field-validation-valid" data-valmsg-for="indecator_order" data-valmsg-replace="true"></span>
        </div>
    </div>
    <!----------------------------------اسم المؤشر----------------------------------------->
    <div class="form-group col-sm-2">
        <label id="lbl_indecator_name" class="col-sm-1 control-label">اسم المعلومة</label>
        <div>
            <input type="text"  data-val="true"  data-val-required="حقل مطلوب"  name="indecator_name" id="txt_indecator_name" class="form-control" dir="rtl" value="<?php echo (count($rs)>0)? $rs['INDECATOR_NAME']: '' ;?>" <!---readonly-->
            <span class="field-validation-valid" data-valmsg-for="indecator_name" data-valmsg-replace="true"></span>
        </div>
    </div>

    <!----------------------------------- الحالة ------------------------------------------>
    <div class="form-group col-sm-1">
        <label class="col-sm-1 control-label">الحالة</label>
        <div>
            <select name="indecator_flag" data-val="true"  data-val-required="حقل مطلوب"  id="dp_indecator_flag" class="form-control">
                <option></option>
                <?php foreach($indecator_flag as $row) :?>
                    <option value="<?= $row['CON_NO'] ?>" <?PHP if ($row['CON_NO']==((count($rs)>0)?$rs['INDECATOR_FLAGE']:0)){ echo " selected"; } ?> >
                        <?= $row['CON_NAME'] ?></option>
                <?php endforeach; ?>
            </select>
            <span class="field-validation-valid" data-valmsg-for="indecator_flag" data-valmsg-replace="true"></span>
        </div>
    </div>
    <!----------------------------------- الوحدة ----------------------------------------->
    <div class="form-group col-sm-1">
        <label class="col-sm-1 control-label">الوحدة</label>
        <div>
            <select name="unit" data-val="true"  data-val-required="حقل مطلوب"  id="dp_unit" class="form-control">
                <option></option>
                <?php foreach($unit as $row) :?>
                    <option value="<?= $row['CON_NO'] ?>" <?PHP if ($row['CON_NO']==((count($rs)>0)?$rs['UNIT']:0)){ echo " selected"; } ?> >
                        <?= $row['CON_NAME'] ?></option>
                <?php endforeach; ?>
            </select>
            <span class="field-validation-valid" data-valmsg-for="unit" data-valmsg-replace="true"></span>
        </div>
    </div>
    <!----------------------------------- الصيغة --------------------------------------->
    <div class="form-group col-sm-1">
        <label class="col-sm-1 control-label">الصيغة</label>
        <div>
            <select name="scale" data-val="true"  data-val-required="حقل مطلوب"  id="dp_scale" class="form-control">
                <option></option>
                <?php foreach($scale as $row) :?>
                    <option value="<?= $row['CON_NO'] ?>" <?PHP if ($row['CON_NO']==((count($rs)>0)?$rs['SCALE']:0)){ echo " selected"; } ?> >
                        <?= $row['CON_NAME'] ?></option>
                <?php endforeach; ?>
            </select>
            <span class="field-validation-valid" data-valmsg-for="scale" data-valmsg-replace="true"></span>
        </div>
    </div>
    <!--------------------------------- سترة القياس ---------------------------------------->
    <div class="form-group col-sm-2">
        <label class="col-sm-1 control-label">فترة القياس</label>
        <div>
            <select name="period" data-val="true"  data-val-required="حقل مطلوب"  id="dp_period" class="form-control">
                <option></option>
                <?php foreach($period as $row) :?>
                    <option value="<?= $row['CON_NO'] ?>" <?PHP if ($row['CON_NO']==((count($rs)>0)?$rs['PERIOD']:0)){ echo " selected"; } ?> ><?= $row['CON_NAME'] ?></option>
                <?php endforeach; ?>
            </select>
            <span class="field-validation-valid" data-valmsg-for="period" data-valmsg-replace="true"></span>
        </div>
    </div>




    <div class="form-group col-sm-3 info_rep" >
        <label class="col-sm-7 control-label">عرض المعلومة في الصفحة الرئيسية</label>
        <div>
            <select name="show_info" data-val="true"  data-val-required="حقل مطلوب"  id="dp_show_info" class="form-control">
                <option value="1">نعم</option>
                <option value="2">لا</option>

            </select>
            <span class="field-validation-valid" data-valmsg-for="how_info" data-valmsg-replace="true"></span>
        </div>
    </div>

    <div class="form-group col-sm-2 info_rep">
        <label class="col-sm-1 control-label">التقارير الشهرية</label>
        <div>
            <select name="report_ind" data-val="true"  data-val-required="حقل مطلوب"  id="dp_report_ind" class="form-control">
                <option value="1">نعم</option>
                <option value="2">لا</option>

            </select>
            <span class="field-validation-valid" data-valmsg-for="report_ind" data-valmsg-replace="true"></span>
        </div>
    </div>


    <!---------------------------------- ملاحظات ----------------------------------------->
    <div class="form-group col-sm-10">
        <label class="col-sm-1 control-label">الوصف</label>
        <div>
            <input type="text"    name="note" id="txt_note" class="form-control" dir="rtl" value="<?php echo (count($rs)>0)? $rs['NOTE']: '' ;?>" <!---readonly-->
            <span class="field-validation-valid" data-valmsg-for="note" data-valmsg-replace="true"></span>
        </div>
    </div>
</fieldset>
<hr/>
<!-----------------------------------مسؤولية المتابعة------------------------------>
<fieldset>
    <legend>الجهة المسؤولة</legend>
    <div class="form-group col-sm-3">
        <label class="col-sm-2 control-label">الفرع</label>
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
                <?php foreach($manage_follow_id as $row) :?>
                    <option value="<?= $row['ST_ID'] ?>" <?PHP if ($row['ST_ID']==$manage_select_exe){ echo " selected"; } ?>><?= $row['ST_NAME'] ?></option>
                <?php endforeach; ?>
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
                <?php foreach($cycle_follow as $row) :?>
                    <option value="<?= $row['ST_ID'] ?>" <?PHP if ($row['ST_ID']==$cycle_select_exe){ echo " selected"; } ?>><?= $row['ST_NAME'] ?></option>
                <?php endforeach; ?>
            </select>
            <span class="field-validation-valid" data-valmsg-for="cycle_follow_id" data-valmsg-replace="true"></span>
        </div>
    </div>
</fieldset>
<hr/>

<fieldset>
    <legend>جهة تحديث البيانات</legend>
    <div class="form-group col-sm-3">
        <label class="col-sm-2 control-label">الفرع</label>
        <div>
            <select name="branches1" data-val="true"  data-val-required="حقل مطلوب"  id="dp_branches1" class="form-control">
                <option></option>
                <?php foreach($branches as $row) :?>
                    <option value="<?= $row['NO'] ?>" <?PHP if ($row['NO']==((count($rs)>0)?$rs['BRANCH']:0)){ echo " selected"; } ?> ><?= $row['NAME'] ?></option>
                <?php endforeach; ?>
            </select>
            <span class="field-validation-valid" data-valmsg-for="branches1" data-valmsg-replace="true"></span>
        </div>
    </div>
    <!-------------------------------------الادارة------------------------------------------>
    <div class="form-group col-sm-3">
        <label class="col-sm-2 control-label">الادارة</label>
        <div>
            <select name="manage_follow_id1" data-val="true"  data-val-required="حقل مطلوب"  id="dp_manage_follow_id1" class="form-control">
                <option></option>
                <?php foreach($manage_follow_id as $row) :?>
                    <option value="<?= $row['ST_ID'] ?>" <?PHP if ($row['ST_ID']==$manage_select_exe){ echo " selected"; } ?>><?= $row['ST_NAME'] ?></option>
                <?php endforeach; ?>
            </select>
            <span class="field-validation-valid" data-valmsg-for="manage_follow_id1" data-valmsg-replace="true"></span>
        </div>
    </div>
    <!-------------------------------------------الدائرة------------------------->
    <div class="form-group col-sm-3">
        <label class="col-sm-2 control-label">الدائرة</label>
        <div>
            <select name="cycle_follow_id1" data-val="true"  data-val-required="حقل مطلوب" id="dp_cycle_follow_id1" class="form-control">
                <option></option>
                <?php foreach($cycle_follow as $row) :?>
                    <option value="<?= $row['ST_ID'] ?>" <?PHP if ($row['ST_ID']==$cycle_select_exe){ echo " selected"; } ?>><?= $row['ST_NAME'] ?></option>
                <?php endforeach; ?>
            </select>
            <span class="field-validation-valid" data-valmsg-for="cycle_follow_id1" data-valmsg-replace="true"></span>
        </div>
    </div>
</fieldset>
<hr/>
<!------------------------------------------بيانات المؤشر---------------------------------------------------->
<fieldset hidden id="div_target">
    <legend> معلومات المستهدف المؤشر </legend>
    <!-------------------------------------------المستهدف--------------------------------------------------------->
    <div class="form-group col-sm-1">
        <label class="col-sm-2 control-label">المستهدف</label>
        <div>
            <select name="is_target" data-val="true"  data-val-required="حقل مطلوب"  id="dp_is_target" class="form-control">
                <option></option>
                <?php foreach($is_target as $row) :?>
                    <option value="<?= $row['CON_NO'] ?>" <?PHP if ($row['CON_NO']==((count($rs)>0)?$rs['IS_TARGET']:0)){ echo " selected"; } ?> ><?= $row['CON_NAME'] ?></option>
                <?php endforeach; ?>
            </select>
            <span class="field-validation-valid" data-valmsg-for="is_target" data-valmsg-replace="true"></span>
        </div>
    </div>
    <!--------------------------------------- مؤثر --------------------------------------->
    <div class="form-group col-sm-1">
        <label class="col-sm-1 control-label">تقييم</label>
        <div>
            <select name="effect" data-val="true"  data-val-required="حقل مطلوب"  id="dp_effect" class="form-control">
                <option></option>
                <?php foreach($effect as $row) :?>
                    <option value="<?= $row['CON_NO'] ?>" <?PHP if ($row['CON_NO']==((count($rs)>0)?$rs['EFFECT']:0)){ echo " selected"; } ?> ><?= $row['CON_NAME'] ?></option>
                <?php endforeach; ?>
            </select>
            <span class="field-validation-valid" data-valmsg-for="effect" data-valmsg-replace="true"></span>
        </div>
    </div>
    <!------------------------------------------------طريقة الحساب--------------------------------------------------->
    <div class="form-group col-sm-1">
        <label class="col-sm-1 control-label">طريقة الحساب</label>
        <div>
            <select name="entry_target_way" data-val="true"  data-val-required="حقل مطلوب"  id="dp_entry_target_way" class="form-control">
                <option></option>

                <?php   foreach($entry_target_way as $row) :?>
                    <?php //if ($row['CON_NO'] != 2 ){?>
                        <option value="<?= $row['CON_NO']   ?>" <?PHP if ($row['CON_NO']==((count($rs)>0)?$rs['ENTERY_TARGET_WAY']:0)){ echo " selected"; } ?> ><?= $row['CON_NAME'] ?></option>
                    <?php  endforeach; ?>
            </select>
            <span class="field-validation-valid" data-valmsg-for="entry_target_way" data-valmsg-replace="true"></span>
        </div>
    </div>
    <!-----------------------------------------موعد الادخال----------------------------------------------->
    <div class="form-group col-sm-1 hidden">
        <label class="col-sm-1 control-label">موعد الادخال</label>
        <div>
            <select name="entry_target_time" data-val="true"  data-val-required="حقل مطلوب"  id="dp_entry_target_time" class="form-control">
                <option></option>
                <?php foreach($entry_target_time as $row) :?>
                    <option value="<?= $row['CON_NO'] ?>" <?PHP if ($row['CON_NO']==((count($rs)>0)?$rs['ENTERY_TARGET_TIME']:0)){ echo " selected"; } ?> ><?= $row['CON_NAME'] ?></option>
                <?php endforeach; ?>
            </select>
            <span class="field-validation-valid" data-valmsg-for="entry_target_time" data-valmsg-replace="true"></span>
        </div>
    </div>
    <!-------------------------------------تأثير المؤشر-------------------------------------------------->
    <div class="form-group col-sm-1">
        <label class="col-sm-1 control-label">تأثير المؤشر</label>
        <div>
            <select name="effect_flag" data-val="true"  data-val-required="حقل مطلوب"  id="dp_effect_flag" class="form-control">
                <option></option>
                <?php foreach($effect_flag as $row) :?>

                    <option value="<?= $row['CON_NO'] ?>" <?PHP if ($row['CON_NO']==((count($rs)>0)?$rs['EFFECT_FLAG']:0)){ echo " selected"; } ?> ><?= $row['CON_NAME'] ?></option>
                <?php endforeach; ?>
            </select>
            <span class="field-validation-valid" data-valmsg-for="effect_flag" data-valmsg-replace="true"></span>
        </div>
    </div>
    <!------------------------- الفارق المؤثر--------------------------------->
    <div class="form-group col-sm-1">
        <label class="col-sm-1 control-label">الفارق المؤثر % </label>
        <div>
            <input type="text"    data-val="true"  data-val-required="حقل مطلوب"  name="effect_value" id="txt_effect_value" class="form-control" dir="rtl" value="<?php echo (count($rs)>0)? $rs['EFFECT_VALUE']: '' ;?>" <!---readonly-->
            <span class="field-validation-valid" data-valmsg-for="effect_value" data-valmsg-replace="true"></span>
        </div>
    </div>
    <!----------------------------------الوزن النسبي----------------------------------------->
    <div class="form-group col-sm-1">
        <label class="col-sm-1 control-label">الوزن النسبي</label>
        <div>
            <input type="text" data-val="true"  data-val-required="حقل مطلوب"  name="weight" id="txt_weight" class="form-control" dir="rtl" value="<?php echo (count($rs)>0)? $rs['WEIGHT']: '' ;?>" <!---readonly-->
        </div>
    </div>
    <!-----------------------------------طريقة حساب المستهدف------------------------------->
    <div class="form-group col-sm-5 " >
        <label class="col-sm-4 control-label">طريقة حساب المستهدف</label>
        <div>
            <input type="text"    name="equation_target" id="txt_equation_target" class="form-control" dir="rtl" value="<?php echo (count($rs)>0)? $rs['EQUATION_TARGET']: '' ;?>" <!---readonly-->
            <span class="field-validation-valid" data-valmsg-for="equation_target" data-valmsg-replace="true"></span>
        </div>
    </div>
</fieldset>


<div hidden  id="target_indecators">

    <fieldset  class="field_set">
        <legend>احتساب المستهدف</legend>
        <div>
            <?php echo modules::run("$MODULE_NAME/$TB_NAME1/$DET_TB_NAME2" ,(count(@$rs)>0)?@$rs['SER']:0,((count(@$rs)>0)? @$rs['ADOPT'] : '' )); ?>
        </div>
    </fieldset>

</div>


<hr/>



<!--------------------------------------------معلومات المحقق من المؤشر-------------------------------------------->

<fieldset>
    <legend>معلومات المحقق</legend>
    <!------------------------------------------------طريقة الحساب--------------------------------------------------->
    <div class="form-group col-sm-1">
        <label class="col-sm-1 control-label">طريقة الحساب</label>
        <div>
            <select name="ver_entry_target_way" data-val="true"  data-val-required="حقل مطلوب"  id="dp_ver_entry_target_way" class="form-control">
                <option></option>
                <?php   foreach($entry_target_way as $row) :?>
                        <option value="<?= $row['CON_NO']   ?>" <?PHP if ($row['CON_NO']==((count($rs)>0)?$rs['ENTERY_TARGET_WAY']:0)){ echo " selected"; } ?> ><?= $row['CON_NAME'] ?></option>
               <?php  endforeach; ?>
            </select>
            <span class="field-validation-valid" data-valmsg-for="ver_entry_target_way" data-valmsg-replace="true"></span>
        </div>
    </div>

    <!---------------------------------------طريقة حساب المحقق------------------------------------------->
    <div class="form-group col-sm-5 " >
        <label class="col-sm-4 control-label">طريقة حساب المحقق</label>
        <div>
            <input type="text"    name="ver_equation_target" id="txt_ver_equation_target" class="form-control" dir="rtl" value="<?php echo (count($rs)>0)? $rs['EQUATION_TARGET']: '' ;?>" <!---readonly-->
            <span class="field-validation-valid" data-valmsg-for="ver_equation_target" data-valmsg-replace="true"></span>
        </div>
    </div>
</fieldset>
<hr/>

<div hidden  id="ver_indecators">

    <fieldset  class="field_set">
        <legend>احتساب المحقق</legend>
        <div>
            <?php echo modules::run("$MODULE_NAME/$TB_NAME1/$DET_TB_NAME" ,(count(@$rs)>0)?@$rs['SER']:0,((count(@$rs)>0)? @$rs['ADOPT'] : '' )); ?>
        </div>
    </fieldset>

</div>

<!-----------------------------------------حفظ البيانات والاعتماد ------------------------------------>
<div class="modal-footer">
    <?php
    if (  HaveAccess($post_url)  && ($isCreate || ( $rs['ADOPT']==1 ) )  ) : ?>
        <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
    <?php endif; ?>
    <?php if ( HaveAccess($adopt_url) && (!$isCreate and ( (count($rs)>0)? $rs['ADOPT']==1 : '' ) and  (count($rs)>0)? $rs['HAVE_TARGET']==0 : '' )) : ?>
        <button type="button" id="btn_adopt" onclick='javascript:return_adopt(1);' class="btn btn-success">اعتماد  </button>
    <?php endif; ?>
    <?php if ( HaveAccess($un_adopt_url) && (!$isCreate and ( (count($rs)>0)? $rs['ADOPT']==2 : '' ) and  (count($rs)>0)? $rs['HAVE_TARGET']==0 : '' )) : ?>
        <button type="button"  id="btn_unadopt" onclick="javascript:return_adopt(2);" class="btn btn-danger">الغاء الاعتماد</button>
    <?php endif; ?>
    <?php if ( HaveAccess($flag_url) && (!$isCreate and ( (count($rs)>0)? $rs['ADOPT']==2 : ''  and  (count($rs)>0)? $rs['HAVE_TARGET']>=1 : '' ) and  (count($rs)>0)? $rs['INDECATOR_FLAGE']==2 : '' )) : ?>
        <button type="button" id="btn_indector_Flag" name ="btn_indector_Flag"  onclick='javascript:return_flag(1);' class="btn btn-success">تفعيل المؤشر  </button>
    <?php endif; ?>
    <?php if ( HaveAccess($un_flag_url) && (!$isCreate and ( (count($rs)>0)? $rs['ADOPT']==2 : ''  and  (count($rs)>0)? $rs['HAVE_TARGET']>=1 : '' ) and  (count($rs)>0)? $rs['INDECATOR_FLAGE']==1 : '' )) : ?>
        <button type="button" id="btn_unindector_Flag" name="btn_unindector_Flag" onclick='javascript:return_flag(2);'    class="btn btn-danger" >الغاء فعالية المؤشر</button>
    <?php endif; ?>
	<button type="button" id="btn_convert_to_indecatior" name="btn_convert_to_indecatior" onclick='javascript:return_premmission();' class="btn btn-danger">ادارة الصلاحيات</button>
</div>

</div>
</form>

</div>


<?php
$scripts = <<<SCRIPT
<script type="text/javascript">

var count=[];
var count2=[];
var val=[];
var val2=[];
var val3=[];
var val4=[];
var val5=[];
function return_premmission()
{
_showReport('$permmition_url/55');
var windowHeight = $(window).height();
var windowWidth = $(window).width();

var boxHeight = $('.modal-dialog').height();
$('.modal-dialog').css('width','1500px');
var boxWidth = $('.modal-dialog').width();
/*
alert(windowHeight);
alert(windowWidth);
alert(boxHeight);
alert(boxWidth);
*/
//$('.modal-dialog').css({'center' : ((windowWidth - boxWidth)/2), 'top' : ((windowHeight - boxHeight)/2)});
$('.modal-dialog').css({'center' : ((windowWidth - boxWidth)/2)});
//$('.modal-content').css('width','1500px');

}
function return_adopt (type){

                if(type == 1){

					   get_data('{$adopt_url}',{id: $('#txt_indecator_ser').val()},function(data){

           if(data =='1')
                            success_msg('رسالة','تم اعتماد بنجاح ..');
                           reload_Page();
                    },'html');

				                }
                            if(type == 2){
                    get_data('{$un_adopt_url }',{id:$('#txt_indecator_ser').val()},function(data){
                    if(data =='1')
                            success_msg('رسالة','تم  الغاء الاعتماد بنجاح ..');
                            reload_Page();
                    },'html');

                    }


                }



       function return_flag (type){

                if(type == 1){

					   get_data('{$flag_url}',{id: $('#txt_indecator_ser').val()},function(data){

           if(data =='1')
                             success_msg('رسالة','تم تفعيل بنجاح ..');

                           reload_Page();
                    },'html');

				                }
                            if(type == 2){

                    get_data('{$un_flag_url}',{id:$('#txt_indecator_ser').val()},function(data){
                    if(data =='1')
                                    success_msg('رسالة','تم الغاء التفعيل بنجاح ..');
                            reload_Page();
                    },'html');

                    }


                }









$('#dp_branches').select2().on('change',function(){
       get_data('{$manage_follow_branch}',{no: $('#dp_branches').val()},function(data){
            $('#dp_manage_follow_id').html('');
			$('#dp_cycle_follow_id').html('');
            $('#dp_manage_follow_id').append('<option></option>');
            $("#dp_manage_follow_id").select2('val','');
            $("#dp_cycle_follow_id").select2('val','');
            $.each(data,function(index, item)
            {
            $('#dp_manage_follow_id').append('<option value=' + item.ST_ID + '>' + item.ST_NAME + '</option>');
            });
            });
            });

$('#dp_manage_follow_id').select2().on('change',function(){
        get_data('{$cycle_exe_branch}',{no: $('#dp_manage_follow_id').val()},function(data){
            $('#dp_cycle_follow_id').html('');
            $('#dp_manage_follow_id').append('<option></option>');
            $("#dp_cycle_follow_id").select2('val','');
            $.each(data,function(index, item)
            {
            $('#dp_cycle_follow_id').append('<option value=' + item.ST_ID + '>' + item.ST_NAME + '</option>');
            });
            });
            });

   $('#dp_cycle_follow_id').select2();
   $('#dp_class').select2();
   $('#dp_second_class').select2();
   $('#dp_report_ind').select2();
   $('#dp_show_info').select2();
   
   $('#dp_sys_info').select2().on('change',function(){

    if($(this).val() == 1){
        if(confirm('عند تحويل المؤشر الى معلومة, ستفقد بيانات المستهدف')){
            $('#div_target').hide();
            $('#lbl_indecator_name').text('اسم المعلومة');
            $('.info_rep').show();
			$('.import_info').hide();
        }
    }else{
        $('#div_target').show();
		$('.import_info').show();
		$('#lbl_indecator_name').text('اسم المؤشر');
         $('.info_rep').hide();
        }

    });

   $('#dp_indecator_flag').select2();
   $('#dp_unit').select2();
   $('#dp_effect').select2();
   $('#dp_scale').select2();
   $('#dp_period').select2();
   $('#dp_effect_flag').select2();
   $('#dp_is_target').select2();
   $('#dp_entry_target_way').select2();
   $('#dp_entry_target_time').select2();
$('#dp_cycle_follow_id1').select2();
$('#dp_manage_follow_id1').select2();
$('#dp_branches1').select2();

$('#dp_sector').select2().on('change',function(){
       get_data('{$indicator}',{no: $('#dp_sector').val()},function(data){
            $('#dp_class').html('');
              $('#dp_class').append('<option></option>');
              $("#dp_class").select2('val','');
            $.each(data,function(index, item)
            {
            $('#dp_class').append('<option value=' + item.CON_NO + '>' + item.CON_NAME + '</option>');
            });
            });
        });

$('button[data-action="submit"]').click(function(e){
     e.preventDefault();
     if(confirm('هل تريد الحفظ  ؟!')){
      $(this).attr('disabled','disabled');
      var form = $(this).closest('form');
if($('#dp_entry_target_way').val()==2)
{


$('select[name="first_period2[]"]').each(function (i) {
           val2[i]=$(this).val();
        });

 $('select[name="first_operation2[]"]').each(function (i) {
           val3[i]=$(this).val();
        });

  $('select[name="summary_period2[]"]').each(function (i) {
           val4[i]=$(this).val();
        });

   $('input[name="model_date_p[]"]').each(function (i) {
         val5[i]=$(this).val();
   });


$('select[name="first_indecator2[]"]').each(function (i) {
         val[i]=$(this).val();
         if(val.length == 1){
            if(val[0]=='')
                danger_msg('يتوجب عليك ادخال معلومات احتساب مستهدف المؤشر',);

            if(val2[0]=='')
                danger_msg('يجب ادخال فترة المؤشر');

            if(val3[0]!=''){
                if(val5[0]=='' ||val4[0]=='' )
                    danger_msg('يجب ادخال بيانات عملية النسبة والقيمة');
            }
          }else{
            if(i > 0){
                if(val[i]!=''){
                    if(val2[i]=='')
                        danger_msg('يجب ادخال فترة المؤشر');
                    if(val3[i]!=''){
                        if(val5[i]=='' ||val4[i]=='' )
                            danger_msg('يجب ادخال بيانات عملية النسبة والقيمة');
                    }
                }else{
                    if(val3[i]!=''){
                        if(val5[i]=='' ||val4[i]=='' )
                            danger_msg('يجب ادخال بيانات عملية النسبة والقيمة');
                    }else{
                        danger_msg('يجب اختيار عملية نسبة أو قيمة');
                    }
                }
            }
          }
    });
}
else
{
      ajax_insert_update(form,function(data){
      console.log(data);
      if(parseInt(data)>=1){
      success_msg('رسالة','تم حفظ البيانات بنجاح ..');
      get_to_link('{$get_url}/'+parseInt(data));
      }else{
       danger_msg('تحذير..',data);
       }
        },'html');
        }
        setTimeout(function() {
            $('button[data-action="submit"]').removeAttr('disabled');
        }, 3000);
        }
});



         $('#dp_ver_entry_target_way').select2().on('change',function(){
			 if($(this).val()=='2'){
			    $('#ver_indecators').show();
			 }else {
			    $('#ver_indecators').hide();
			 }
        });

        $('#dp_entry_target_way').select2().on('change',function(){
			 if($(this).val()=='2'){
			    $('#target_indecators').show();
			 }else {
			    $('#target_indecators').hide();
			 }
        });

reBind_pram(0);

        function reBind_pram(cnt){
        $('table tr td .select2-container').remove();
        $('select[name="second_operation[]"]').each(function (i) {
           count[i]=$(this).closest('tr').find('input[name="h_count[]"]').val(i);
            if($(this).val()!=0){
                        $('#new_row').show();
                    } else{
                        $('#new_row').hide();
                    }
        });

        $('select[name="second_operation2[]"]').each(function (i) {
           count2[i]=$(this).closest('tr').find('input[name="h_count2[]"]').val(i);
            if($(this).val()!=0){
                        $('#new_row2').show();
                    } else{
                        $('#new_row2').hide();
                    }
        });



                $('select[name="second_operation[]"]').select2().on('change',function(){
                    var cnt_tr1=$(this).closest('tr').find('input[name="h_count[]"]').val();
                    if($(this).val()!=0){
                        $('#new_row').show();
                    }else{
                    $('#new_row').hide();
                    }
               });

               $('select[name="second_operation2[]"]').select2().on('change',function(){
                    var cnt_tr2=$(this).closest('tr').find('input[name="h_count2[]"]').val();
                    if($(this).val()!=0){
                        $('#new_row2').show();
                    } else{
                    $('#new_row2').hide();
                    }
               });






        }




</script>
SCRIPT;
sec_scripts($scripts);
?>

