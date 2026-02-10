<?php
/**
 * Created by PhpStorm.
 * User: ljasser
 * Date: 17/12/18
 * Time: 08:58 ص
 */
$MODULE_NAME='gis';
$TB_NAME="net_controller";
$rs=($isCreate)? array() : $network_data;
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
        <legend>  بيانات الشبكة </legend>
        <div class="row">
            <div class="form-group col-sm-1">
                <label class="control-label">مسلسل</label>
                <input type="text" id="ID_id" name="ID1" readonly class="form-control"
                       value="<?php echo @$rs['ID1'] ;?>" >
                <span id="id_validate"> </span>
            </div>
            <!----------------------------------------كود الشبكة------------------------------------------>
           <!-- <div class="form-group col-sm-1">
                <label class="control-label">كود الشبكة</label>
                <input type="text" id="NETWORK_CO_id" name="NETWORK_CO" class="form-control"
                       value="<?php echo @$rs['NETWORK_CO'] ;?>" >
                <span id="id_validate"> </span>
            </div>
            <!---------------------------------نوع التركيب (أرضي أو هوائي)--------------------------------------->
            <div class="form-group col-sm-2">
                <label class="control-label">نوع التركيب (أرضي أو هوائي)</label>
                <input type="text" id="Network_Installation_Type_dp" name="NETWORK_INST_TYPE" class="form-control"
                       value="<?php echo @$rs['NETWORK_INST_TYPE'] ;?>">
                <span id="id_validate"> </span>
            </div>
            <!------------------------------نوع مادة الموصل للفازات----------------------------->
            <div class="form-group col-sm-2">
                <label class="control-label"> نوع مادة الموصل للفازات</label>
                <input type="text" id="Phases_Conductors_Material_dp" name="PHASES_COND_MATERIAL" class="form-control"
                       value="<?php echo @$rs['PHASES_COND_MATERIAL'] ;?>">
                <span id="id_validate"> </span>
            </div>
            <!-------------------مساحة مقطع ونوع الفازات------------------->
            <div class="form-group col-sm-2">
                <label class="control-label"> مساحة مقطع ونوع الفازات  </label>
                <input type="text" id="Phase_Conductors_Type_dp" name="PHASE_COND_TYPE" class="form-control" >
                <span id="id_validate"> </span>
            </div>
           <!-------------------------نوع مادة الموصل للارضي----------------------->
            <div class="form-group col-sm-2">
                <label class="control-label"> نوع مادة الموصل للارضي  </label>
                <input type="text" id="Earth_Line_Conductor_Material_dp" name="EARTH_L_COND_MATERIAL" class="form-control"
                       value="<?php echo @$rs['EARTH_L_COND_MATERIAL'] ;?>">
                <span id="id_validate"> </span>
            </div>
            <!--------------------------مساحة مقطع ونوع الارضي------------------------------->
            <div class="form-group col-sm-2">
                <label class="control-label">مساحة مقطع ونوع الارضي</label>
                <input type="text" id="Earth_Line_Conductor_Type_dp" name="EARTH_L_COND_TYPE" class="form-control"
                       value="<?php echo @$rs['EARTH_L_COND_TYPE'] ;?>">
                <span id="id_validate"> </span>
            </div>
            <br>
            <!------------------------------------عدد الوصلات في الشبكة الهوائية--------------->
            <div class="form-group col-sm-3">
                <label class="control-label">عدد الوصلات في الشبكة الهوائية</label>
                <input type="text" id="OHL_Total_of_Joints_No_dp" name="TOTAL_OF_JOINTS_NO" class="form-control"
                       value="<?php echo @$rs['TOTAL_OF_JOINTS_NO'] ;?>">
                <span id="id_validate"> </span>
            </div>
            <!----------------------------عدد المرابط في الشبكة الهوائية----------------------------------->
            <div class="form-group col-sm-3">
                <label class="control-label">عدد المرابط في الشبكة الهوائية</label>
                <input type="text" id="OHL_Total_of_Clamps_No_dp" name="TOTAL_OF_CLAMPS_NO" class="form-control"
                       value="<?php echo @$rs['TOTAL_OF_CLAMPS_NO'] ;?>">
                <span id="id_validate"> </span>
            </div>
            <!-------------------------------مكان الشبكة الهوائية-------------------------------------->
            <div class="form-group col-sm-3">
                <label class="control-label">مكان الشبكة الهوائية</label>
                <input type="text" id="OHL_Crossing_Area_dp" name="OHL_CROSSING_AREA" class="form-control"
                       value="<?php echo @$rs['OHL_CROSSING_AREA'] ;?>">
                <span id="id_validate"> </span>
            </div>
            <!------------------------------التدلى في الشبكة الهوائية---------------------------------------->
            <div class="form-group col-sm-3">
                <label class="control-label">التدلى في الشبكة الهوائية</label>
                <input type="text" id="OHL_Sag_dp" name="OHL_SAG" class="form-control"
                       value="<?php echo @$rs['OHL_SAG'] ;?>">
                <span id="id_validate"> </span>
            </div>
            <!-----------------------------------حالة السلك الارضي------------------------------------->
            <div class="form-group col-sm-3">
                <label class="control-label">حالة السلك الارضي</label>
                <input type="text" id="PROJECT_NO_dp" name="OHL_EARTH_L_CONDITION" class="form-control"
                       value="<?php echo @$rs['PROJECT_NO'] ;?>">
                <span id="id_validate"> </span>
            </div>


            <!-----------------------------------طول الشبكة الهوائية (متر طولي)-------------------------------------->
            <div class="form-group col-sm-3">
                <label class="control-label">  طول الشبكة الهوائية (متر طولي)  </label>
                <input type="text" id="OHL_Length_dp" name="OHL_LENGTH_M" class="form-control"
                       value="<?php echo @$rs['OHL_LENGTH_M'] ;?>">
                <span id="id_validate"> </span>
            </div>
            <!--------------------------------فولتية الكابل الارضي-------------------------------------------->
            <div class="form-group col-sm-3">
                <label class="control-label"> فولتية الكابل الارضي </label>
                <input type="text" id="UGC_Voltage_dp" name="UGC_VOLTAGE" class="form-control"
                       value="<?php echo @$rs['UGC_VOLTAGE'] ;?>">
                <span id="id_validate"> </span>
            </div>
            <!-------------------------------عدد الالمقاومة R لكل كم----------------------->
            <div class="form-group col-sm-3">
                <label class="control-label"> المقاومة R لكل كم</label>
                <input type="text" id="R_OHM_PER_KM_dp" name="R_OHM_PER_KM" class="form-control"
                       value="<?php echo @$rs['R_OHM_PER_KM'] ;?>">
                <span id="id_validate"> </span>
            </div>
            <!-------------------------------------عدالمقاومة X لكل كم------------------------------------------->
            <div class="form-group col-sm-3">
                <label class="control-label"> المقاومة X لكل كم</label>
                <input type="text" id="X_OHM_PER_KM_dp" name="X_OHM_PER_KM" class="form-control"
                       value="<?php echo @$rs['X_OHM_PER_KM'] ;?>">
                <span id="id_validate"> </span>
            </div>
            <!-------------------------------عدد الوصلات في الكابل الارضي------------------------------->
            <div class="form-group col-sm-3">
                <label class="control-label"> عدد الوصلات في الكابل الارضي</label>
                <input type="text" id="UGC_STRAIGHT_JOINT_NO_dp" name="UGC_STRAIGHT_JOINT_NO" class="form-control"
                       value="<?php echo @$rs['UGC_STRAIGHT_JOINT_NO'] ;?>">
                <span id="id_validate"> </span>
            </div>

            <!----------------------------------عدد وحدات الانهاء الخارجي في الكابل الارضي--------------------------------->
            <div class="form-group col-sm-3">
                <label class="control-label">عدد وحدات الانهاء الخارجي في الكابل الارضي</label>
                <input type="text" id="UGC_TERMINATION_KIT_NO_dp" name="UGC_TERMINATION_KIT_NO" class="form-control"
                       value="<?php echo @$rs['UGC_TERMINATION_KIT_NO'] ;?>">
                <span id="id_validate"> </span>
            </div>
            <!---------------------------حماية بداية الكابل الارضي من الصواعق-------------------------------------->
            <div class="form-group col-sm-3">
                <label class="control-label">حماية بداية الكابل الارضي من الصواعق</label>
                <input type="text" id="UGC_START_LIGHTNING_dp" name="UGC_START_LIGHTNING" class="form-control"
                       value="<?php echo @$rs['UGC_START_LIGHTNING'] ;?>">
                <span id="id_validate"> </span>
            </div>
            <!-------------------------------حماية نهاية الكابل الارضي من الصواعق---------------------------->
            <div class="form-group col-sm-3">
                <label class="control-label">حماية نهاية الكابل الارضي من الصواعق</label>
                <input type="text" id="UGC_END_LIGHTNING" name="UGC_END_LIGHTNING" class="form-control"
                       value="<?php echo @$rs['UGC_END_LIGHTNING'] ;?>">
                <span id="id_validate"> </span>
            </div>
            <!---------------------------------حماية مجري الصاج لبداية الكابل الارضي---------------------->
            <div class="form-group col-sm-3">
                <label class="control-label">حماية مجري الصاج لبداية الكابل الارضي</label>
                <input type="text" id="UGC_START_PROTECTION_dp" name="UGC_START_PROTECTION" class="form-control"
                       value="<?php echo @$rs['UGC_START_PROTECTION'] ;?>">
                <span id="id_validate"> </span>
            </div>
            <!------------------------------حماية مجري الصاج لنهاية الكابل الارضي-------------------------------->
            <div class="form-group col-sm-3">
                <label class="control-label"> حماية مجري الصاج لنهاية الكابل الارضي</label>
                <input type="text" id="UGC_END_PROTECTION_dp" name="UGC_END_PROTECTION" class="form-control"
                       value="<?php echo @$rs['UGC_END_PROTECTION'] ;?>">
                <span id="id_validate"> </span>
            </div>
            <!---------------------------------طول الكابل الارضي (متر طولى)---------------------------------->
            <div class="form-group col-sm-3">
                <label class="control-label"> طول الكابل الأرضي(متر طولي)</label>
                <input type="text" id="UGC_LENGTH_M_dp" name="UGC_LENGTH_M" class="form-control"
                       value="<?php echo @$rs['UGC_LENGTH_M'] ;?>">
                <span id="id_validate"> </span>
            </div>
            <!-----------------------تفصيل الشبكة----------------------------------->
            <div class="form-group col-sm-3">
                <label class="control-label"> تفصيل الشبكة</label>
                <input type="text" id="NETWORK_DESC_dp" name="NETWORK_DESC" class="form-control"
                       value="<?php echo @$rs['NETWORK_DESC'] ;?>">
                <span id="id_validate"> </span>
            </div>
            <!---------------------------------رقم عامود/غرفة البداية - خاص ببرنامج GIS------------------------------->
            <div class="form-group col-sm-3">
                <label class="control-label">رقم عامود/غرفة البداية - خاص ببرنامج GIS</label>
                <input type="text" id="S_MV_POLE_ROOM_CODE_dp" name="S_MV_POLE_ROOM_CODE" class="form-control"
                       value="<?php echo @$rs['S_MV_POLE_ROOM_CODE'] ;?>">
                <span id="id_validate"> </span>
            </div>
            <!-------------------------رقم عامود/غرفة النهاية - خاص ببرنامج GIS--------------------------->
            <div class="form-group col-sm-3">
                <label class="control-label"> رقم عامود/غرفة النهاية - خاص ببرنامج GIS</label>
                <input type="text" id="E_MV_POLE_ROOM_CODE_dp" name="E_MV_POLE_ROOM_CODE" class="form-control"
                       value="<?php echo @$rs['E_MV_POLE_ROOM_CODE'] ;?>">
                <span id="id_validate"> </span>
            </div>
            <!---------------------------------اسم المحافظة----------------------------------->
            <div class="form-group col-sm-3">
                <label class="control-label"> اسم المحافظة</label>
                <input type="text" id="GOVERNORAT_dp" name="GOVERNORAT" class="form-control"
                       value="<?php echo @$rs['GOVERNORAT'] ;?>">
                <span id="id_validate"> </span>
            </div>
            <!-----------------------------------اسم المنطقة--------------------------------->
            <div class="form-group col-sm-3">
                <label class="control-label"> اسم المنطقة </label>
                <input type="text" id="DISTRICT_NAME_dp" name="DISTRICT_NAME" class="form-control"
                       value="<?php echo @$rs['DISTRICT_NAME'] ;?>">
                <span id="id_validate"> </span>
            </div>
            <!-------------------------اسم الشارع---------------------------->
            <div class="form-group col-sm-3">
                <label class="control-label"> اسم الشارع  </label>
                <input type="text" id="STREET_NAME_dp" name="STREET_NAME" class="form-control"
                       value="<?php echo @$rs['STREET_NAME'] ;?>">
                <span id="id_validate"> </span>
            </div>
            <!----------------------------------رقم الشارع------------------------------->
            <div class="form-group col-sm-3">
                <label class="control-label">رقم الشارع</label>
                <input type="text" class="form-control" id="STREET_NO_dp" name="STREET_NO"
                       value="<?php echo @$rs['STREET_NO'] ;?>">
                <span id="id_validate"> </span>
            </div>
            <!----------------------------------تاريخ التوثيق----------------------------------->
           <div class="form-group col-sm-3">
                <label class="control-label">تاريخ التوثيق</label>
                <div>
                    <input type="text"  <?=$date_attr?>   name="DOC_DATE" id="DOC_DATE_dp" class="form-control"
                           value="<?php echo @$rs['DOC_DATE'] ;?>">
                </div>

            </div>
        <!-------------------------------------------اسم فريق التوثيق------------------------------->
            <div class="form-group col-sm-3">
                <label class="control-label">اسم فريق التوثيق </label>
                <input type="text" class="form-control" id="DATA_DOC_TEAM_dp" name="DATA_DOC_TEAM"
                       value="<?php echo @$rs['DATA_DOC_TEAM'] ;?>">
                <span id="id_validate"> </span>
            </div>
        <!--------------------------------------------اسم فريق ادخال البيانات----------------------------------->
            <div class="form-group col-sm-3">
                <label class="control-label">اسم فريق ادخال البيانات</label>
                <input type="text" class="form-control" id="DATA_ENTRY_TEAM_dp" name="DATA_ENTRY_TEAM"
                       value="<?php echo @$rs['DATA_ENTRY_TEAM'] ;?>">
                <span id="id_validate"> </span>
            </div>
            <!----------------------------------------داخل/خارج الخدمة---------------------------------------------------->
            <div class="form-group col-sm-3">
                <label class="control-label">داخل/خارج الخدمة</label>
                <input type="text" class="form-control" id="SERVICE_dp" name="SERVICE"
                       value="<?php echo @$rs['SERVICE'] ;?>">
                <span id="id_validate"> </span>
            </div>
            <!------------------------------------------اسم الخط المغذي---------------------------------------->
            <div class="form-group col-sm-3">
                <label class="control-label"> اسم الخط المغذي</label>
                <input type="text" class="form-control" id="LINE_NAME_dp" name="LINE_NAME"
                       value="<?php echo @$rs['LINE_NAME'] ;?>">
                <span id="id_validate"> </span>
            </div>
            <!---------------------------------------رقم المشروع-------------------------------------------------->
            <div class="form-group col-sm-3">
                <label class="control-label"> رقم المشروع</label>
                <input type="text" class="form-control" id="PROJECT_NO_dp" name="PROJECT_NO"
                       value="<?php echo @$rs['PROJECT_NO'] ;?>">
                <span id="id_validate"> </span>
            </div>
            <!-----------------------------------------ملاحظات----------------------------------------------->
            <div class="form-group col-sm-3">
                <label class="control-label">الملاحظات</label>
                <textarea type="text" rows="1" class="form-control" name="NOTES"
                          value="<?php echo @$rs['NOTES'] ;?>"> </textarea>
                <span id="id_validate"></span>
            </div>
            <!---------------------------------------المرفقات------------------------------------------------>
            <div class="form-group col-sm-3">
                <label class="control-label">المرفقات</label>
                <input type="file" class="form-control" name="PHOTO_PATH" value="<?php echo @$rs['PHOTO_PATH'] ;?>" >
                <div class="row pull-right">
                </div>
            </div>
        <!------------------------------------------------------------------------------------------>


        </div>
    </fieldset>
        <!----------------------------------------------------------------------------------------->
        <div class="modal-footer">
            <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
        </div>
        <!----------------------------------------------------------------------------------------->

        </div>
    </fieldset>

    </form>
    </div>
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
            console.log(data);
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


















