<?php
/**
 * Created by PhpStorm.
 * User: ljasser
 * Date: 07/02/19
 * Time: 08:38 ص
 */
$MODULE_NAME='gis';
$TB_NAME="LV_Network_controller";
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
                    <legend>بيانات الشبكة الأساسية </legend>
                    <div class="row">
                        <!--------------------------------------------->
                        <div class="form-group col-sm-2">
                            <label class="control-label">مسلسل</label>
                            <input type="text" id="ID_id" name="ID" readonly class="form-control"
                                   value="<?php echo @$rs['ID'] ;?>" >
                            <span id="id_validate"> </span>
                        </div>
                        <!--------------------------ID_PK-------------------------->
                        <div class="form-group col-sm-2">
                            <label class="control-label">ID_PK</label>
                            <input type="text" id="ID_PK_id" name="ID_PK" readonly class="form-control"
                                   value="<?php echo @$rs['ID_PK'] ;?>" >
                            <span id="id_validate"> </span>
                        </div>
                        <!--------------------------NETWORK_ID_SER------------------------>
                        <div class="form-group col-sm-2">
                            <label class="control-label">NETWORK_ID_SER</label>
                            <input type="text" id="NETWORK_ID_SER_id" name="NETWORK_ID_SER"  class="form-control"
                                   value="<?php echo @$rs['NETWORK_ID_SER'] ;?>" >
                            <span id="id_validate"> </span>
                        </div>
                        <!-----------------------كود الشبكة - خاص ببرنامج GIS----------------------------->
                        <div class="form-group col-sm-2">
                            <label class="control-label">كود الشبكة - خاص ببرنامج GIS</label>
                            <input type="text" id="NETWORK_CODE_id" name="NETWORK_CODE"  class="form-control"
                                   value="<?php echo @$rs['NETWORK_CODE'] ;?>" >
                            <span id="id_validate"> </span>
                        </div>
                        <!----------------------------------نوع الشبكة-------------------------------->
                        <div class="form-group col-sm-2">
                            <label class="control-label">نوع الشبكة</label>
                            <input type="text" id="NETWORK_TYPE_id" name="NETWORK_TYPE"  class="form-control"
                                   value="<?php echo @$rs['NETWORK_TYPE'] ;?>" >
                            <span id="id_validate"> </span>
                        </div>
                        <br>
                        <!------------------------------نوع مادة الشبكة----------------------------->
                        <div class="form-group col-sm-2">
                            <label class="control-label">نوع مادة الشبكة </label>
                            <input type="text" id="PHASES_CONDUCTORS_MATERIAL_id" name="PHASES_CONDUCTORS_MATERIAL"  class="form-control"
                                   value="<?php echo @$rs['PHASES_CONDUCTORS_MATERIAL'] ;?>" >
                            <span id="id_validate"> </span>
                        </div>
                        <!------------------------------مساحة مقطع ونوع الشبكة--------------->
                        <div class="form-group col-sm-2">
                            <label class="control-label">مساحة مقطع ونوع الشبكة</label>
                            <input type="text" id="LV_NETWORK_TYPE_id" name="LV_NETWORK_TYPE"  class="form-control"
                                   value="<?php echo @$rs['LV_NETWORK_TYPE'] ;?>" >
                            <span id="id_validate"> </span>
                        </div>
                        <!-----------------------------ملكية الشبكة---------------------->
                        <div class="form-group col-sm-2">
                            <label class="control-label">ملكية الشبكة</label>
                            <input type="text" id="NETWORK_PROPERTY_id" name="NETWORK_PROPERTY"  class="form-control"
                                   value="<?php echo @$rs['NETWORK_PROPERTY'] ;?>" >
                            <span id="id_validate"> </span>
                        </div>
                        <!--------------------------طول الشبكة------------------------->
                        <div class="form-group col-sm-2">
                            <label class="control-label">طول الشبكة </label>
                            <input type="text" id="LV_NETWORK_LENGTH_M_id" name="LV_NETWORK_LENGTH_M"  class="form-control"
                                   value="<?php echo @$rs['LV_NETWORK_LENGTH_M'] ;?>" >
                            <span id="id_validate"> </span>
                        </div>


                    </div>
                </fieldset>
                <hr/>
                <!---------------------------------------------------------------->
                <fieldset>
                    <legend>  بيانات الفرعية للشبكة</legend>
                    <div class="row">
                        <!-------------------كود الصنف - خاص بالشركة لا يتم كتابته------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">كود الصنف - خاص بالشركة لا يتم كتابته</label>
                            <input type="text" id="OBJECT_ID_id" name="OBJECT_ID"  class="form-control"
                                   value="<?php echo @$rs['OBJECT_ID'] ;?>" >
                            <span id="id_validate"> </span>
                        </div>
                        <!-------------------------داخل الخدمة / خارج الخدمة--------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">داخل الخدمة / خارج الخدمة</label>
                            <input type="text" id="SERVICE_id" name="SERVICE"  class="form-control"
                                   value="<?php echo @$rs['SERVICE'] ;?>" >
                            <span id="id_validate"> </span>
                        </div>
                        <!----------------------------قياس الجهد------------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">قياس الجهد</label>
                            <input type="text" id="LV_PHASE_id" name="LV_PHASE"  class="form-control"
                                   value="<?php echo @$rs['LV_PHASE'] ;?>" >
                            <span id="id_validate"> </span>
                        </div>
                        <!----------------------------جهد التشغيل------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">جهد التشغيل</label>
                            <input type="text" id="OPRATING_VOLT_id" name="OPRATING_VOLT"  class="form-control"
                                   value="<?php echo @$rs['OPRATING_VOLT'] ;?>" >
                            <span id="id_validate"> </span>
                        </div>
                        <!------------------------رقم سكينة البداية - خاص ببرنامج GIS---------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">رقم سكينة البداية - خاص ببرنامج GIS </label>
                            <input type="text" id=START_LTL_SWITCH_CODE_id" name="START_LTL_SWITCH_CODE"  class="form-control"
                                   value="<?php echo @$rs['START_LTL_SWITCH_CODE'] ;?>" >
                            <span id="id_validate"> </span>
                        </div>
                        <!------------------------------رقم عامود البداية - خاص ببرنامج GIS--------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">رقم عامود البداية - خاص ببرنامج GIS</label>
                            <input type="text" id=START_POLE_CODE_id" name="START_POLE_CODE"  class="form-control"
                                   value="<?php echo @$rs['START_POLE_CODE'] ;?>" >
                            <span id="id_validate"> </span>
                        </div>
                        <!---------------------------رقم عامود النهاية - خاص ببرنامج GIS---------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">رقم عامود النهاية - خاص ببرنامج GIS</label>
                            <input type="text" id="END_POLE_CODE_id" name="END_POLE_CODE"  class="form-control"
                                   value="<?php echo @$rs['END_POLE_CODE'] ;?>" >
                            <span id="id_validate"> </span>
                        </div>
                        <!------------------------------رقم الفيدر--------------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">رقم الفيدر</label>
                            <input type="text" id="FEEDER_CODE_id" name="FEEDER_CODE"  class="form-control"
                                   value="<?php echo @$rs['FEEDER_CODE'] ;?>" >
                            <span id="id_validate"> </span>
                        </div>
                        <!--------------------------------كود المحول--------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">كود المحول </label>
                            <input type="text" id="TRANSFORMER_CODE_id" name="TRANSFORMER_CODE"  class="form-control"
                                   value="<?php echo @$rs['TRANSFORMER_CODE'] ;?>" >
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
                            <input type="text" id="DISTRICT_NUMBER_ID"  name="DISTRICT_NUMBER"class="form-control"
                                   value="<?php echo @$rs['DISTRICT_NUMBER'] ;?>">
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
                            <input type="text" id="ID"  name="DATA_DOCUMENTED_BY"class="form-control"
                                   value="<?php echo @$rs['DATA_DOCUMENTED_BY'] ;?>">
                            <span id="id_validate"></span>
                        </div>
                        <!------------------------------------------------------------------------>
                        <div class="form-group col-sm-3">
                            <label class="control-label">اسم فريق الادخال</label>
                            <input type="text" id="DATA_ENTRY_BY_ID"  name="DATA_ENTRY_BY"class="form-control"
                                   value="<?php echo @$rs['DATA_ENTRY_BY'] ;?>">
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
                        <!------------------------احداثيات X-------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">احداثيات X </label>
                            <input type="text" id="X_COORDINATE_ID"  name="X_COORDINATE"class="form-control"
                                   value="<?php echo @$rs['X_COORDINATE'] ;?>">
                            <span id="id_validate"></span>
                        </div>
                        <!---------------------------احداثيات Y----------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">احداثيات Y </label>
                            <input type="text" id="Y_COORDINATE_ID"  name="Y_COORDINATE"class="form-control"
                                   value="<?php echo @$rs['Y_COORDINATE'] ;?>">
                            <span id="id_validate"></span>
                        </div>
                        <!-------------------------------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">المرفقات</label>
                            <input type="file" class="form-control" name="PHOTO_PATH" value="<?php echo @$rs['PHOTO_PATH'] ;?>" >
                            <div class="row pull-right">
                            </div>
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



