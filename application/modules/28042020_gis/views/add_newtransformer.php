<?php
/**
 * Created by PhpStorm.
 * User: ljasser
 * Date: 03/02/19
 * Time: 11:24 ص
 */
$MODULE_NAME='gis';
$TB_NAME="transformer_controller";
$rs=($isCreate)? array(): count($transformer_data) > 0 ? $transformer_data[0] : array() ;
//$rs=($isCreate)? array() : $transformer_data[0];
$back_url=base_url("$MODULE_NAME/$TB_NAME/index");
$post_url= base_url("$MODULE_NAME/$TB_NAME/".($action == 'index'?'create':$action));
$date_attr= " data-type='date' data-date-format='DD/MM/YYYY' data-val='true'
                  data-val-regex-pattern='".date_format_exp()."' data-val-regex='Error' ";
?>
<!----------------------css code-------------------------->
<style>
    fieldset{
        border-color: cornflowerblue;
        font-family: bold 'Open Sans', sans-serif;
    }
    legend{
        background: #2493EB;
    }
</style>
<!-------------------end css code---------------------->
<div class="row">
    <div class="toolbar">
        <div class="caption"><?=$title?></div>
        <ul>
            <li><a  href="<?= $back_url ?>"><i class="icon icon-reply"></i> </a> </li>
        </ul>
    </div>
</div>
<div class="form-body">
    <div id="msg_container"></div>
    <div id="container" >
        <form class="form-vertical" id="<?=$TB_NAME?>_form" method="post" action="<?=@$post_url?>" role="form" novalidate="novalidate">
            <div class="modal-body inline_form">
                <fieldset >
                    <legend>بيانات المحول الـأساسية</legend>
                    <div class="row">
                        <div class="form-group col-sm-1">
                            <label class="control-label">مسلسل</label>
                            <input type="text" id="ID_id" name="ID" readonly class="form-control"
                                   value="<?php echo @$rs['ID'] ;?>" >
                            <span id="id_validate"> </span>
                        </div>
                        <!------------------------------------قدرة المحول------------------------------------>
                        <div class="form-group col-sm-2">
                            <label class="control-label">قدرة المحول</label>
                            <input type="text" id="TR_RATING_KVA_dp" name="TR_RATING_KVA" class="form-control"
                                   value="<?php echo @$rs['TR_RATING_KVA'] ;?>">
                            <span id="id_validate"> </span>
                        </div>

                        <!-------------------------------------------كود المحول-------------------------------------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">كود المحول</label>
                            <input type="text" id="TRANS_MATERIAL_ID_dp" name="TRANS_MATERIAL_ID" class="form-control"
                                   value="<?php echo @$rs['TRANS_MATERIAL_ID'] ;?>">
                            <span id="id_validate"> </span>
                        </div>

                        <!------------------------------------------------كود المحول------------------------------------------------>
                        <div class="form-group col-sm-3">
                            <label class="control-label">كود المحول</label>
                            <input type="text" id="TRANSFORMER_CODE_dp" name="TRANSFORMER_CODE" class="form-control"
                                   value="<?php echo @$rs['TRANSFORMER_CODE'] ;?>">
                            <span id="id_validate"> </span>
                        </div>

                        <!----------------------------------------اسم المحول/ المحولات باللغة العربية--------------------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">اسم المحول/ المحولات باللغة العربية</label>
                            <input type="text" id="TRANSFORMER_NAME_AR_dp" name="TRANSFORMER_NAME_AR" class="form-control"
                                   value="<?php echo @$rs['TRANSFORMER_NAME_AR'] ;?>">
                            <span id="id_validate"> </span>
                        </div>
                        <!--------------------------------------------------اسم المحول/ المحولات باللغة الإنجليزية------------------------------------>
                        <div class="form-group col-sm-3">
                            <label class="control-label">اسم المحول/ المحولات باللغة الإنجليزية</label>
                            <input type="text" id="TRANSFORMER_NAME_EN_dp" name="TRANSFORMER_NAME_EN" class="form-control"
                                   value="<?php echo @$rs['TRANSFORMER_NAME_EN'] ;?>">
                            <span id="id_validate"> </span>
                        </div>

                        <!-----------------------------------------أقصى نسبة تحميل على المحول--------------------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">أقصى نسبة تحميل على المحول </label>
                            <input type="text" id="MAX_LOAD_PERCENTAGE_dp" name="MAX_LOAD_PERCENTAGE" class="form-control"
                                   value="<?php echo @$rs['MAX_LOAD_PERCENTAGE'] ;?>">
                            <span id="id_validate"> </span>
                        </div>
                        <!---------------------------------------------نسبة التحميل السكانية-------------------------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">نسبة التحميل السكانية</label>
                            <input type="text" id="HOUSEHOLD_PERCENTAGE_dp" name="HOUSEHOLD_PERCENTAGE" class="form-control"
                                   value="<?php echo @$rs['HOUSEHOLD_PERCENTAGE'] ;?>">
                            <span id="id_validate"> </span>
                        </div>
                        <!------------------------------------------نسبة التحميل التجارية--------------------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label"> نسبة التحميل التجارية </label>
                            <input type="text" id="COMMERCIAL_PERCENTAGE_dp" name="COMMERCIAL_PERCENTAGE" class="form-control"
                                   value="<?php echo @$rs['COMMERCIAL_PERCENTAGE'] ;?>">
                            <span id="id_validate"> </span>
                        </div>
                        <!-------------------------------------نسبة التحميل الصناعية--------------------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">نسبة التحميل الصناعية</label>
                            <input type="text" id="INDUSTRIAL_PERCENTAGE_dp" name="INDUSTRIAL_PERCENTAGE" class="form-control"
                                   value="<?php echo @$rs['INDUSTRIAL_PERCENTAGE'] ;?>">
                            <span id="id_validate"> </span>
                        </div>
                        <!-------------------------------------------نسبة التحميل الزراعية----------------------------------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">نسبة التحميل الزراعية</label>
                            <input type="text" id="AGRICULTURE_PERCENTAGE_dp" name="AGRICULTURE_PERCENTAGE" class="form-control"
                                   value="<?php echo @$rs['AGRICULTURE_PERCENTAGE'] ;?>">
                            <span id="id_validate"> </span>
                        </div>
                        <!-----------------------------------------نسبة تحميل المؤسسات---------------------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">نسبة تحميل المؤسسات</label>
                            <input type="text" id="INTITUTION_PERCENTAGE_dp" name="INTITUTION_PERCENTAGE" class="form-control"
                                   value="<?php echo @$rs['INTITUTION_PERCENTAGE'] ;?>">
                            <span id="id_validate"> </span>
                        </div>
                        <!----------------------------------------------نسبة عدد اللفات------------------------------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">نسبة عدد اللفات</label>
                            <input type="text" id="TURNS_RATIO_dp" name="TURNS_RATIO" class="form-control"
                                   value="<?php echo @$rs['TURNS_RATIO'] ;?>">
                            <span id="id_validate"> </span>
                        </div>

                        <!--------------------------------------عدد طقات المحول---------------------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">عدد طقات المحول</label>
                            <input type="text" id="TR_CHANGER_COUNT_dp" name="TR_CHANGER_COUNT" class="form-control"
                                   value="<?php echo @$rs['TR_CHANGER_COUNT'] ;?>">
                            <span id="id_validate"> </span>
                        </div>
                        <!--------------------------------------الشركة المصنعة-------------------------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label"> الشركة المصنعة </label>
                            <input type="text" id="TR_MANUFACTURER_dp" name="TR_MANUFACTURER" class="form-control"
                                   value="<?php echo @$rs['TR_MANUFACTURER'] ;?>">
                            <span id="id_validate"> </span>
                        </div>
                        <!---------------------------------------------رقم المحول التسلسلي---------------------------------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label"> رقم المحول التسلسلي  </label>
                            <input type="text" id="TR_SERIAL_NO_dp" name="TR_SERIAL_NO" class="form-control"
                                   value="<?php echo @$rs['TR_SERIAL_NO'] ;?>">
                            <span id="id_validate"> </span>
                        </div>
                        <!----------------------------------------تاريخ التصنيع---------------------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">تاريخ التصنيع</label>
                            <div>
                                <input type="text"  <?=$date_attr?>   name="TR_DATE_OF_MANUFACTURE" id="TR_DATE_OF_MANUFACTURE_dp" class="form-control"
                                       value="<?php echo @$rs['DOCUMENTATION_DATE'] ;?>">
                            </div>
                        </div>
                        <!----------------------------------------البلد المصنع-------------------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">البلد المصنع</label>
                            <input type="text" id="TR_COUNTRY_OF_ORIGIN_dp" name="TR_COUNTRY_OF_ORIGIN" class="form-control"
                                   value="<?php echo @$rs['TR_SERIAL_NO'] ;?>">
                            <span id="id_validate"> </span>
                        </div>
                        <!-------------------------------------------مكان المحول----------------------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label"> مكان المحول</label>
                            <input type="text" id="TR_POSITION_dp" name="TR_POSITION" class="form-control"
                                   value="<?php echo @$rs['TR_POSITION'] ;?>">
                            <span id="id_validate"> </span>
                        </div>
                        <!--------------------------------------اتجاه المحول--------------------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">  اتجاه المحول</label>
                            <input type="text" id="TR_DIRECTION_dp" name="TR_DIRECTION" class="form-control"
                                   value="<?php echo @$rs['TR_DIRECTION'] ;?>">
                            <span id="id_validate"> </span>
                        </div>
                        <!----------------------------------------نوع زيت المحول------------------------------------------>
                        <div class="form-group col-sm-3">
                            <label class="control-label">   نوع زيت المحول</label>
                            <input type="text" id="TR_OIL_TYPE_dp" name="TR_OIL_TYPE" class="form-control"
                                   value="<?php echo @$rs['TR_OIL_TYPE'] ;?>">
                            <span id="id_validate"> </span>
                        </div>
                        <!--------------------------------------------ملكية المحول--------------------------------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">   ملكية المحول  </label>
                            <input type="text" id="TR_PROPERTY_dp" name="TR_PROPERTY" class="form-control"
                                   value="<?php echo @$rs['TR_PROPERTY'] ;?>">
                            <span id="id_validate"> </span>
                        </div>
                        <!-----------------------------------نوع الكابل أو السلك المغذي للمحول------------------------------------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">نوع الكابل أو السلك المغذى للمحول</label>
                            <input type="text" id="TYPE_OF_FEEDING_CONDUCTOR_dp" name="TYPE_OF_FEEDING_CONDUCTOR" class="form-control"
                                   value="<?php echo @$rs['TYPE_OF_FEEDING_CONDUCTOR'] ;?>">
                            <span id="id_validate"> </span>
                        </div>
                      <!----------------------------------حالة جسم المحول------------------------------>
                        <div class="form-group col-sm-3">
                            <label class="control-label">حالة جسم المحول</label>
                            <input type="text" id="TR_BODY_CONDITION_dp" name="TR_BODY_CONDITION" class="form-control"
                                   value="<?php echo @$rs['TR_BODY_CONDITION'] ;?>">
                            <span id="id_validate"> </span>
                        </div>
                        <!-------------------------------------------تاريخ تركيب المحول-------------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">تاريخ تركيب المحول </label>
                            <div>
                                <input type="text"  <?=$date_attr?>   name="INSTALLATION_DATE" id="INSTALLATION_DATE_dp" class="form-control"
                                       value="<?php echo @$rs['INSTALLATION_DATE'] ;?>">
                            </div>
                        </div>
                        <!---------------------------------تأريض جسم المحول-------------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">تأريض جسم المحول</label>
                            <input type="text" id="TR_BODY_EARTHING_dp" name="TR_BODY_EARTHING" class="form-control"
                                   value="<?php echo @$rs['TR_BODY_EARTHING'] ;?>">
                            <span id="id_validate"> </span>
                        </div>
                        <!--------------------------------------------------------------------------------->
                        </div>
                        </fieldset>
                        <hr/>
                        <!---------------------------------------------------------------------------------->
                <fieldset>
                    <legend> البيانات الفرعية للمحول</legend>
                    <div class="row">
                        <!-------------------------------------كود الصنف للعامود---------------------------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">كود الصنف للعامود </label>
                            <input type="text" id="POLE_MATERIAL_ID_dp" name="POLE_MATERIAL_ID" class="form-control"
                                   value="<?php echo @$rs['POLE_MATERIAL_ID'] ;?>">
                            <span id="id_validate"> </span>
                        </div>
                        <!-------------------------------------------كود عامود ض.ع------------------------------------------>
                        <div class="form-group col-sm-3">
                            <label class="control-label"> كود عامود ض.ع</label>
                            <input type="text" id="MV_POLE_CODE_dp" name="MV_POLE_CODE" class="form-control"
                                   value="<?php echo @$rs['MV_POLE_CODE'] ;?>">
                            <span id="id_validate"> </span>
                        </div>
                        <!------------------------------------------------كود قاطع RMU--------------------------------------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">كود قاطع RMU</label>
                            <input type="text" id="RMU_CODE_dp" name="RMU_CODE" class="form-control"
                                   value="<?php echo @$rs['RMU_CODE'] ;?>">
                            <span id="id_validate"> </span>
                        </div>
                        <!-------------------------------------كود سكينة ض.ع----------------------------------------------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">كود سكينة ض.ع</label>
                            <input type="text" id="MV_SWITCH_CODE_dp" name="MV_SWITCH_CODE" class="form-control"
                                   value="<?php echo @$rs['MV_SWITCH_CODE'] ;?>">
                            <span id="id_validate"> </span>
                        </div>

                        <!---------------------------------------Impedance Percentage------------------------------------------>
                        <div class="form-group col-sm-3">
                            <label class="control-label">Impedance Percentage</label>
                            <input type="text" id="IMPEDANCE_PERCENTAGE_dp" name="IMPEDANCE_PERCENTAGE" class="form-control"
                                   value="<?php echo @$rs['IMPEDANCE_PERCENTAGE'] ;?>">
                            <span id="id_validate"> </span>
                        </div>
                        <!---------------------------------------XR Ratio------------------------------>
                        <div class="form-group col-sm-3">
                            <label class="control-label">X_R_RATIO </label>
                            <input type="text" id="X_R_RATIO_dp" name="X_R_RATIO" class="form-control"
                                   value="<?php echo @$rs['X_R_RATIO'] ;?>">
                            <span id="id_validate"> </span>
                        </div>
                        <!------------------------------------الخط المغذي---------------------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">الخط المغذي </label>
                            <input type="text" id="FEEDER_PLAN_dp" name="FEEDER_PLAN" class="form-control"
                                   value="<?php echo @$rs['FEEDER_PLAN'] ;?>">
                            <span id="id_validate"> </span>
                        </div>
                        <!-----------------------------مغير الجهد +---------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label"> مغير الجهد + </label>
                            <input type="text" id="TAP_CHANGER_PLUS_dp" name="TAP_CHANGER_PLUS" class="form-control"
                                   value="<?php echo @$rs['TAP_CHANGER_PLUS'] ;?>">
                            <span id="id_validate"> </span>
                        </div>
                        <!----------------------------------مغير الجهد ---------------------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label"> مغير الجهد -   </label>
                            <input type="text" id="TAP_CHANGER_MINUS_dp" name="TAP_CHANGER_MINUS" class="form-control"
                                   value="<?php echo @$rs['TAP_CHANGER_MINUS'] ;?>">
                            <span id="id_validate"> </span>
                        </div>
                        <!-----------------------------------------مغير الجهد الحالي---------------------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">  مغير الجهد الحالي    </label>
                            <input type="text" id="CURRENT_TAP_CHANGER_dp" name="CURRENT_TAP_CHANGER" class="form-control"
                                   value="<?php echo @$rs['CURRENT_TAP_CHANGER'] ;?>">
                            <span id="id_validate"> </span>
                        </div>
                        <!----------------------------------------نوع حامل الفلزات---------------------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">نوع حامل الفلزات</label>
                            <input type="text" id="FUSE_HOLDER_TYPE_dp" name="FUSE_HOLDER_TYPE" class="form-control"
                                   value="<?php echo @$rs['FUSE_HOLDER_TYPE'] ;?>">
                            <span id="id_validate"> </span>
                        </div>
                        <!----------------------------------نوع عوازل حامل الفلزات----------------------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">نوع عوازل حامل الفلزات</label>
                            <input type="text" id="FUSE_HOLDER_INSULATOR_TYP_dp" name="FUSE_HOLDER_INSULATOR_TYP" class="form-control"
                                   value="<?php echo @$rs['FUSE_HOLDER_INSULATOR_TYP'] ;?>">
                            <span id="id_validate"> </span>
                        </div>
                        <!-----------------------------------Drop Out------------------------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">Drop Out</label>
                            <input type="text" id="DROP_OUT_dp" name="DROP_OUT" class="form-control"
                                   value="<?php echo @$rs['DROP_OUT'] ;?>">
                            <span id="id_validate"> </span>
                        </div>
                        <!------------------------------------نوع ومقاس فيوزات ض.ع-------------------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label"> نوع ومقاس فيوزات ض.ع</label>
                            <input type="text" id="MV_FUSES_TYPE_dp" name="MV_FUSES_TYPE" class="form-control"
                                   value="<?php echo @$rs['MV_FUSES_TYPE'] ;?>">
                            <span id="id_validate"> </span>
                        </div>
                        <!----------------------------------جهد ض.ع---------------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">جهد ض.ع</label>
                            <input type="text" id="TR_PRIMARY_VOLTAGE_dp" name="TR_PRIMARY_VOLTAGE" class="form-control"
                                   value="<?php echo @$rs['TR_PRIMARY_VOLTAGE'] ;?>">
                            <span id="id_validate"> </span>
                        </div>
                        <!------------------------------جهد ض.م---------------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label"> جهد ض.م</label>
                            <input type="text" id="TR_SECONDARY_VOLTAGE_dp" name="TR_SECONDARY_VOLTAGE" class="form-control"
                                   value="<?php echo @$rs['TR_SECONDARY_VOLTAGE'] ;?>">
                            <span id="id_validate"> </span>
                        </div>
                        <!-----------------------------قياس جهد الإبتدائي------------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label"> قياس جهد الإبتدائي </label>
                            <input type="text" id="MEASURED_PRIMARY_VOLTAGE_dp" name="MEASURED_PRIMARY_VOLTAGE" class="form-control"
                                   value="<?php echo @$rs['MEASURED_PRIMARY_VOLTAGE'] ;?>">
                            <span id="id_validate"> </span>
                        </div>
                        <!--------------------------------قياس جهد الثانوي---------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label"> قياس جهد الثانوي   </label>
                            <input type="text" id="MEASURED_SECONDARY_VOLTAGE_dp" name="MEASURED_SECONDARY_VOLTAGE" class="form-control"
                                   value="<?php echo @$rs['MEASURED_SECONDARY_VOLTAGE'] ;?>">
                            <span id="id_validate"> </span>
                        </div>
                        <!---------------------------------معامل القدرة------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">معامل القدرة</label>
                            <input type="text" id="MEASURED_POWER_FACTOR_dp" name="MEASURED_POWER_FACTOR" class="form-control"
                                   value="<?php echo @$rs['MEASURED_POWER_FACTOR'] ;?>">
                            <span id="id_validate"> </span>
                        </div>
                        <!---------------------------مانعات الصواعق---------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">مانعات الصواعق </label>
                            <input type="text" id="ACTIVE_SURGE_ARRESTERS_dp" name="ACTIVE_SURGE_ARRESTERS" class="form-control"
                                   value="<?php echo @$rs['ACTIVE_SURGE_ARRESTERS'] ;?>">
                            <span id="id_validate"> </span>
                        </div>
                        <!---------------------------رقم الصيانة------------------>
                        <div class="form-group col-sm-3">
                            <label class="control-label">رقم الصيانة</label>
                            <input type="text" id="MAINTAINANCE_NUMBER_dp" name="MAINTAINANCE_NUMBER" class="form-control"
                                   value="<?php echo @$rs['MAINTAINANCE_NUMBER'] ;?>">
                            <span id="id_validate"> </span>
                        </div>
                        <!----------------------------تأريض النول----------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">تأريض النول </label>
                            <input type="text" id="TR_NEUTRAL_EARTHING_dp" name="TR_NEUTRAL_EARTHING" class="form-control"
                                   value="<?php echo @$rs['TR_NEUTRAL_EARTHING'] ;?>">
                            <span id="id_validate"> </span>
                        </div>
                        <!--------------------------------مقاومة الأرضي------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">مقاومة الأرضي</label>
                            <input type="text" id="MEASURED_ERTHING_RESISTANCE_dp" name="MEASURED_ERTHING_RESISTANCE" class="form-control"
                                   value="<?php echo @$rs['MEASURED_ERTHING_RESISTANCE'] ;?>">
                            <span id="id_validate"> </span>
                        </div>
                        <!-------------------------تاريخ قياس مقاومة الأرضي------------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">تاريخ قياس مقاومة الأرضي </label>
                            <div>
                                <input type="text"  <?=$date_attr?>   name="EARTHING_RESIS_MEASUR_DATE" id="EARTHING_RESIS_MEASUR_DATE_dp"
                                       class="form-control" value="<?php echo @$rs['EARTHING_RESIS_MEASUR_DATE'] ;?>">
                            </div>
                        </div>
                        <!--------------------------------------------------------------------------------->
                    </div>
                </fieldset>
                <hr/>
                <!---------------------------------------------------------------------------------->
                <fieldset>
                    <legend> بيانات فريق الوثيق  </legend>
                    <div class="row">
                        <!---------------------------------اسم فريق التوثيق------------------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">اسم فريق التوثيق </label>
                            <input type="text" id="DATA_DOCUMENTATION_TEAM_dp" name="DATA_DOCUMENTATION_TEAM" class="form-control"
                                   value="<?php echo @$rs['DATA_DOCUMENTATION_TEAM'] ;?>">
                            <span id="id_validate"> </span>
                        </div>
                        <!--------------------------------------اسم فريق ادخال البيانات--------------------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">اسم فريق ادخال البيانات </label>
                            <input type="text" id="DATA_ENTRY_TEAM_dp" name="DATA_ENTRY_TEAM" class="form-control"
                                   value="<?php echo @$rs['DATA_ENTRY_TEAM'] ;?>">
                            <span id="id_validate"> </span>
                        </div>
                        <!-----------------------------------اسم المستخدم------------------------------------>
                        <div class="form-group col-sm-3">
                            <label class="control-label">اسم المستخدم</label>
                            <input type="text" id="ENTRY_USER_dp" name="ENTRY_USER" class="form-control"
                                   value="<?php echo @$rs['ENTRY_USER'] ;?>">
                            <span id="id_validate"> </span>
                        </div>
                        <!---------------------------------تاريخ التوثيق--------------------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">تاريخ التوثيق</label>
                            <div>
                                <input type="text"  <?=$date_attr?>   name="DOCUMENTATION_DATE" id="txt_DOCUMENTATION_DATE"
                                       class="form-control" value="<?php echo @$rs['DOCUMENTATION_DATE'] ;?>">
                            </div>
                        </div>
                        <!------------------------------كود أمر العمل---------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">كود أمر العمل</label>
                            <input type="text" id="WORK_ORDER_ID_dp" name="WORK_ORDER_ID" class="form-control"
                                   value="<?php echo @$rs['WORK_ORDER_ID'] ;?>">
                            <span id="id_validate"> </span>
                        </div>
                        <!---------------------------------------------------اسم المحافظة--------------------------------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">اسم المحافظة</label>
                            <input type="text" id="GOVERNORATE_NAME_dp" name="GOVERNORATE_NAME" class="form-control"
                                   value="<?php echo @$rs['GOVERNORATE_NAME'] ;?>">
                            <span id="id_validate"> </span>
                        </div>
                        <!-------------------------------تاريخ أخر أمر صيانة--------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">تاريخ أخر أمر صيانة </label>
                            <div>
                                <input type="text"  <?=$date_attr?>   name="REGISTRATON_DATA" id="txt_REGISTRATON_DATA"
                                       class="form-control" value="<?php echo @$rs['REGISTRATON_DATA'] ;?>">
                            </div>
                        </div>
                        <!------------------------------تاريخ آخر أمر عمل------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">تاريخ آخر امر عمل</label>
                            <div>
                                <input type="text"  <?=$date_attr?>   name="WORK_ORDER_DATA" id="txt_WORK_ORDER_DATA"
                                       class="form-control" value="<?php echo @$rs['WORK_ORDER_DATA'] ;?>">
                            </div>
                        </div>
                        <!--------------------------------اسم الشارع----------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">اسم الشارع </label>
                            <input type="text" id="STREET_NAME_dp" name="STREET_NAME" class="form-control"
                                   value="<?php echo @$rs['STREET_NAME'] ;?>">
                            <span id="id_validate"> </span>
                        </div>
                        <!--------------------------------رقم المنطقة---------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">رقم المنطقة</label>
                            <input type="text" id="DISTRICT_NO_dp" name="DISTRICT_NO" class="form-control"
                                   value="<?php echo @$rs['DISTRICT_NO'] ;?>">
                            <span id="id_validate"> </span>
                        </div>
                        <!---------------------------------------الملاحظات------------------------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">الملاحظات</label>
                            <textarea type="text" rows="1" class="form-control" name="NOTES"
                                      value="<?php echo @$rs['NOTES'] ;?>"> </textarea>
                            <span id="id_validate"></span>
                        </div>
                        <!------------------------------------------------------------------------------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">المرفقات</label>
                            <input type="file" class="form-control" name="PHOTO_PATH" value="<?php echo @$rs['PHOTO_PATH'] ;?>" >
                            <div class="row pull-right">
                            </div>
                            <!------------------------------------------->


                        </div>

                    </div>
                </fieldset>
                <div class="modal-footer">
                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                </div>
            </div>
        </form>

    </div>




    <!-------------------------------java script code------------------------>


    <?php
    $scripts = <<<SCRIPT
<script type="text/javascript">

$('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        if(confirm('هل تريد الحفظ  ؟!')){
            $(this).attr('disabled','disabled');
            var form = $(this).closest('form');
            ajax_insert_update(form,function(data){
                if(parseInt(data)>=1){
                    success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                    //get_to_link(window.location.href);
                }else{
                      danger_msg('تحذير..',data);
                }
            },'html');
        }
        setTimeout(function() {
            $('button[data-action="submit"]').removeAttr('disabled');
        }, 5000);
    });





</script>
SCRIPT;
    sec_scripts($scripts);
    ?>











