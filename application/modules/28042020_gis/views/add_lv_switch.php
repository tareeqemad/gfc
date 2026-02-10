<?php
/**
 * Created by PhpStorm.
 * User: ljasser
 * Date: 07/02/19
 * Time: 11:56 ص
 */
$MODULE_NAME='gis';
$TB_NAME="LV_Switches_controller";
$rs=($isCreate)? array() : $LV_Network_data[0];

$back_url=base_url("$MODULE_NAME/$TB_NAME/index");
$get_url =base_url("$MODULE_NAME/$TB_NAME/get_LV_Network_info");
$edit_url =base_url("$MODULE_NAME/$TB_NAME/edit");
$post_url= base_url("$MODULE_NAME/$TB_NAME/".($action == 'index'?'create':$action));
$date_attr= "data-type='date' data-date-format='DD/MM/YYYY' data-val='true'
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

<form class="form-vertical" id="<?=$TB_NAME?>_form" method="post" action="<?=$post_url?>" role="form" novalidate="novalidate">
<div class="modal-body inline_form">
<fieldset >
    <legend>بيانات القاطع الأساسية </legend>
    <div class="row">
        <!--------------------------------------------->
        <div class="form-group col-sm-2">
            <label class="control-label">مسلسل</label>
            <input type="text" id="ID_id" name="ID" readonly class="form-control"
                   value="<?php echo @$rs['ID'] ;?>" >
            <span id="id_validate"> </span>
        </div>
        <!--------------------------كود الصنف - خاص بالشركة يتم كتابته على السكينة-------------------------->
        <div class="form-group col-sm-2">
            <label class="control-label">كود الصنف - خاص بالشركة يتم كتابته على السكينة</label>
            <input type="text" id="OBJECT_ID_id" name="OBJECT_ID" readonly class="form-control"
                   value="<?php echo @$rs['OBJECT_ID'] ;?>" >
            <span id="id_validate"> </span>
        </div>
        <!--------------------------كود عامود الضغط العالي------------------------>
        <div class="form-group col-sm-2">
            <label class="control-label">كود عامود الضغط العالي</label>
            <input type="text" id="MV_POLE_CODE_id" name="MV_POLE_CODE"  class="form-control"
                   value="<?php echo @$rs['MV_POLE_CODE'] ;?>" >
            <span id="id_validate"> </span>
        </div>
        <!----------------------------------كود سكينة ض.م -->
        <div class="form-group col-sm-2">
            <label class="control-label">كود سكينة ض.م </label>
            <input type="text" id="LTL_SWITCH_CODE_id" name="LTL_SWITCH_CODE"  class="form-control"
                   value="<?php echo @$rs['LTL_SWITCH_CODE'] ;?>" >
            <span id="id_validate"> </span>
        </div>
        <!-------------------------------كود العامود------------------------------->
        <div class="form-group col-sm-2">
            <label class="control-label">كود العامود </label>
            <input type="text" id="POLE_MATERIAL_ID_id" name="POLE_MATERIAL_ID"  class="form-control"
                   value="<?php echo @$rs['POLE_MATERIAL_ID'] ;?>" >
            <span id="id_validate"> </span>
        </div>
        <br>
        <!---------------------------كود السكينة----------------------------->
        <div class="form-group col-sm-2">
            <label class="control-label">كود السكينة</label>
            <input type="text" id="SWITCH_MATERIAL_ID_id" name="SWITCH_MATERIAL_ID"  class="form-control"
                   value="<?php echo @$rs['SWITCH_MATERIAL_ID'] ;?>" >
            <span id="id_validate"> </span>
        </div>
        <!-----------------------------اتجاه السكينة ------------------->
        <div class="form-group col-sm-2">
            <label class="control-label">اتجاه السكينة </label>
            <input type="text" id="LTL_SWITCH_DIRECTION_id" name="LTL_SWITCH_DIRECTION"  class="form-control"
                   value="<?php echo @$rs['LTL_SWITCH_DIRECTION'] ;?>" >
            <span id="id_validate"> </span>
        </div>
        <!------------------------------ملكية السكينة -------------------------------->
        <div class="form-group col-sm-2">
            <label class="control-label">ملكية السكينة </label>
            <input type="text" id="LTL_SWITCH_PROPERTY_id" name="LTL_SWITCH_PROPERTY"  class="form-control"
                   value="<?php echo @$rs['LTL_SWITCH_PROPERTY'] ;?>" >
            <span id="id_validate"> </span>
        </div>
        <!------------------------حالة السكينة ------------------------->
        <div class="form-group col-sm-2">
            <label class="control-label">حالة السكينة </label>
            <input type="text" id="LTL_SWITCH_CONDITION_id" name="LTL_SWITCH_CONDITION"  class="form-control"
                   value="<?php echo @$rs['LTL_SWITCH_CONDITION'] ;?>" >
            <span id="id_validate"> </span>
        </div>
        <!-----------------------------نوع ومقطع الكابل الخارج من السكينة ----------------------------->
        <div class="form-group col-sm-2">
            <label class="control-label">نوع ومقطع الكابل الخارج من السكينة </label>
            <input type="text" id="LTL_SWITCH_OUTER_CABLE_SIZE_id" name="LTL_SWITCH_OUTER_CABLE_SIZE"  class="form-control"
                   value="<?php echo @$rs['LTL_SWITCH_OUTER_CABLE_SIZE'] ;?>" >
            <span id="id_validate"> </span>
        </div>
        <!------------------------------نوع ومقطع الكابل المغذي للسكينة ---------------------------->
        <div class="form-group col-sm-2">
            <label class="control-label">نوع ومقطع الكابل المغذي للسكينة </label>
            <input type="text" id="LTL_SWITCH_INNER_CABLE_SIZE_id" name="LTL_SWITCH_INNER_CABLE_SIZE"  class="form-control"
                   value="<?php echo @$rs['LTL_SWITCH_INNER_CABLE_SIZE'] ;?>" >
            <span id="id_validate"> </span>
        </div>
        <!-------------------------------------نوع السكينة ----------------------------------->
        <div class="form-group col-sm-2">
            <label class="control-label">نوع السكينة </label>
            <input type="text" id="LTL_SWITCH_TYPE_id" name="LTL_SWITCH_TYPE"  class="form-control"
                   value="<?php echo @$rs['LTL_SWITCH_TYPE'] ;?>" >
            <span id="id_validate"> </span>
        </div>
        <!-------------------------------------صندوق السكينة --------------------------------------->
        <div class="form-group col-sm-2">
            <label class="control-label">نوع السكينة</label>
            <input type="text" id="LTL_OUTDOOR_CABINET_id" name="LTL_OUTDOOR_CABINET"  class="form-control"
                   value="<?php echo @$rs['LTL_OUTDOOR_CABINET'] ;?>" >
            <span id="id_validate"> </span>
        </div>
        <!--------------------------------------------تيار قلب السكينة------------------------------------------>
        <div class="form-group col-sm-2">
            <label class="control-label">تيار قلب السكينة </label>
            <input type="text" id="LTL_SWITCH_BASE_id" name="LTL_SWITCH_BASE"  class="form-control"
                   value="<?php echo @$rs['LTL_SWITCH_BASE'] ;?>" >
            <span id="id_validate"> </span>
        </div>
        <!-----------------------------------تيار فيوزات السكينة ----------------------------------->
        <div class="form-group col-sm-2">
            <label class="control-label">تيار فيوزات السكينة </label>
            <input type="text" id="LTL_FUSES_RATE_id" name="LTL_FUSES_RATE"  class="form-control"
                   value="<?php echo @$rs['LTL_FUSES_RATE'] ;?>" >
            <span id="id_validate"> </span>
        </div>
        <!------------------------------------------كود العامود أو الغرفة - خاص ببرنامج GIS--------------------------------------->
        <div class="form-group col-sm-2">
            <label class="control-label">كود العامود أو الغرفة - خاص ببرنامج GIS</label>
            <input type="text" id="MV_POLE_OR_ROOM_CODE_id" name="MV_POLE_OR_ROOM_CODE"  class="form-control"
                   value="<?php echo @$rs['MV_POLE_OR_ROOM_CODE'] ;?>" >
            <span id="id_validate"> </span>
        </div>
        <!--------------------------------تاريخ تركيب السكينة--------------------------------->
        <div class="form-group col-sm-3">
            <label class="control-label">تاريخ تركيب السكينة </label>
            <input type="text" id="LTL_SWITCH_INSTALLATION_DATE_ID"  name="LTL_SWITCH_INSTALLATION_DATE"class="form-control"
                   value="<?php echo @$rs['LTL_SWITCH_INSTALLATION_DATE'] ;?>">
            <span id="id_validate"></span>
        </div>


    </div>
</fieldset>
<hr/>
<!---------------------------------------------------------------->
<fieldset>
    <legend>    بيانات القاطع الفرعية</legend>
    <div class="row">

        <!--------------------داخل الخدمة / خارج الخدمة لسكينة ض.م -------------------->
        <div class="form-group col-sm-3">
            <label class="control-label">داخل الخدمة / خارج الخدمة لسكينة ض.م </label>
            <input type="text" id="LTL_SERVICE_id" name="LTL_SERVICE"  class="form-control"
                   value="<?php echo @$rs['LTL_SERVICE'] ;?>" >
            <span id="id_validate"> </span>
        </div>

        <!---------------------------كود لوحة التوزيع ض.م.------------------------------------->
        <div class="form-group col-sm-3">
            <label class="control-label">كود لوحة التوزيع ض.م.</label>
            <input type="text" id=START_POLE_CODE_id" name="START_POLE_CODE"  class="form-control"
                   value="<?php echo @$rs['START_POLE_CODE'] ;?>" >
            <span id="id_validate"> </span>
        </div>

        <!-----------------------------اسم المحول بالعربية-------------------------------------->
        <div class="form-group col-sm-3">
            <label class="control-label">اسم المحول بالعربية</label>
            <input type="text" id="TRANSFORMER_NAME_AR_ID"  name="TRANSFORMER_NAME_AR"class="form-control"
                   value="<?php echo @$rs['TRANSFORMER_NAME_AR'] ;?>" >
            <span id="id_validate"> </span>
        </div>
        <!---------------------------------اسم المحول بالانجليزية  ----------------------------------->
        <div class="form-group col-sm-3">
            <label class="control-label">اسم المحول بالانجليزية</label>
            <input type="text" id="TRANSFORMER_NAME_EN_ID"  name="TRANSFORMER_NAME_EN"class="form-control"
                   value="<?php echo @$rs['TRANSFORMER_NAME_EN'] ;?>" >
            <span id="id_validate"> </span>
        </div>
        <!-------------------------------------------------------------------------------->
        <div class="form-group col-sm-3">
            <label class="control-label">رقم المشروع</label>
            <input type="text" id="ID"  name="PROJECT_NO"class="form-control"
                   value="<?php echo @$rs['PROJECT_NO'] ;?>">
            <span id="id_validate"></span>
        </div>
        <!-------------------------------------------------------------------------------------------------->
        <div class="form-group col-sm-3">
            <label class="control-label">اسم المحافظة </label>
            <input type="text" class="form-control"   name="GOVERNORATE_NAME" id="GOVERNORATE_NAME_ID"
                   value="<?php echo @$rs['GOVERNORATE_NAME'] ;?>">
            <span id="id_validate"></span>
        </div>
        <!------------------------------------------------->

        <div class="form-group col-sm-3">
            <label class="control-label">اسم الشارع</label>
            <input type="text" id="STREET_NAME_ID"  name="STREET_NAME"class="form-control"
                   value="<?php echo @$rs['STREET_NAME'] ;?>">
            <span id="id_validate"></span>
        </div>

        <!-------------------------------------------------------------------------------->
        <div class="form-group col-sm-3">
            <label class="control-label">المنطقة </label>
            <input type="text" id="DISTRICT_NUMBER_ID"  name="DISTRICT_NAME"class="form-control"
                   value="<?php echo @$rs['DISTRICT_NAME'] ;?>">
            <span id="id_validate"></span>
        </div>
        <!----------------------------------------------------------------------------------------------->
        <div class="form-group col-sm-3">
            <label class="control-label">تاريخ التوثيق</label>
            <div>
                <input type="text"  <?=$date_attr?>   name="DOCUMENTATION_DATE" id="txt_DOCUMENTATION_DATE" class="form-control"
                       value="<?php echo @$rs['DOCUMENTATION_DATE'] ;?>">
            </div>
        </div>
        <!------------------------------------------------------------------------------------------------->
        <div class="form-group col-sm-3">
            <label class="control-label">اسم فريق التوثيق</label>
            <input type="text" id="ID"  name="DATA_DOCUMENTATION_TEAM"class="form-control"
                   value="<?php echo @$rs['DATA_DOCUMENTATION_TEAM'] ;?>">
            <span id="id_validate"></span>
        </div>
        <!------------------------------------------------------------------------>
        <div class="form-group col-sm-3">
            <label class="control-label">اسم فريق الادخال</label>
            <input type="text" id="DATA_ENTRY_BY_ID"  name="DATA_ENTRY_TEAM"class="form-control"
                   value="<?php echo @$rs['DATA_ENTRY_TEAM'] ;?>">
            <span id="id_validate"></span>
        </div>
        <!---------------------------تاريخ التركيب--------------------------->
        <div class="form-group col-sm-3">
            <label class="control-label">تاريخ التركيب</label>
            <input type="text" id="INSTALLATION_DATE_ID"  name="INSTALLATION_DATE"class="form-control"
                   value="<?php echo @$rs['INSTALLATION_DATE'] ;?>">
            <span id="id_validate"></span>
        </div>
        <!---------------------------------------الملاحظات------------------------------------------->
        <div class="form-group col-sm-3">
            <label class="control-label">الملاحظات</label>
            <textarea type="text" rows="1" class="form-control" name="NOTES"
                      value="<?php echo @$rs['NOTES'] ;?>"> </textarea>
            <span id="id_validate"></span>
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



