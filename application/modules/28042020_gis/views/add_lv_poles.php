<?php
/**
 * Created by PhpStorm.
 * User: ljasser
 * Date: 06/02/19
 * Time: 09:58 ص
 */
$MODULE_NAME='gis';
$TB_NAME="LV_POLE_controller";
$rs=($isCreate)? array() : $LV_Poles_data[0];

$back_url=base_url("$MODULE_NAME/$TB_NAME/index");
$get_url =base_url("$MODULE_NAME/$TB_NAME/get_LV_Poles_info");
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
                    <legend>بيانات العمود الأساسية</legend>
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
                            <input type="text" id="ID_PK_id" name="ID_PK"  class="form-control"
                                   value="<?php echo @$rs['ID_PK'] ;?>" >
                            <span id="id_validate"> </span>
                        </div>
                        <!-----------------------------ID_M-------------------------------->
                        <div class="form-group col-sm-2">
                            <label class="control-label">ID_M</label>
                            <input type="text" id="ID_M_id" name="ID_M"  class="form-control"
                                   value="<?php echo @$rs['ID_M'] ;?>" >
                            <span id="id_validate"> </span>
                        </div>
                        <!---------------------------كود الصنف - خاص بالشركة---------------------------->
                        <div class="form-group col-sm-2">
                            <label class="control-label">كود الصنف - خاص بالشركة</label>
                            <input type="text" id="OBJECT_ID_id" name="OBJECT_ID"  class="form-control"
                                   value="<?php echo @$rs['OBJECT_ID'] ;?>" >
                            <span id="id_validate"> </span>
                        </div>
                        <!------------------------رقم العامود----------------------------->
                        <div class="form-group col-sm-2">
                            <label class="control-label">رقم العامود</label>
                            <input type="text" id="POLE_MATERIAL_ID_dp" name="POLE_MATERIAL_ID" class="form-control"
                                   value="<?php echo @$rs['POLE_MATERIAL_ID'] ;?>" >
                            <span id="id_validate"> </span>
                        </div>
                        <!-----------------------------POLE_ID_SER----------------------->
                        <div class="form-group col-sm-2">
                            <label class="control-label">POLE_ID_SER</label>
                            <input type="text" id="POLE_ID_SER_dp" name="POLE_ID_SER" class="form-control"
                                   value="<?php echo @$rs['POLE_ID_SER'] ;?>" >
                            <span id="id_validate"> </span>
                        </div>
                        <!-------------------------كود العامود--------------------->
                        <div class="form-group col-sm-2">
                            <label class="control-label">كود العامود</label>
                            <input type="text" id="POLE_CODE_dp" name="POLE_CODE" class="form-control"
                                   value="<?php echo @$rs['POLE_CODE'] ;?>" >
                            <span id="id_validate"> </span>
                        </div>
                        <!---------------------------نوع العامود---------------->
                        <div class="form-group col-sm-2">
                            <label class="control-label">نوع العامود </label>
                            <input type="text" id="POLE_TYPE_dp" name="POLE_TYPE" class="form-control"
                                   value="<?php echo @$rs['POLE_TYPE'] ;?>" >
                            <span id="id_validate"> </span>
                        </div>
                        <!----------------------------مقاس العامود-------------------------->
                        <div class="form-group col-sm-2">
                            <label class="control-label">مقاس العامود</label>
                            <input type="text" id="POLE_SIZE_dp" name="POLE_SIZE" class="form-control"
                                   value="<?php echo @$rs['POLE_SIZE'] ;?>" >
                            <span id="id_validate"> </span>
                        </div>
                        <!----------------------------ارتفاع العامود------------------------>
                        <div class="form-group col-sm-2">
                            <label class="control-label">ارتفاع العامود</label>
                            <input type="text" id="POLE_HEIGHT_dp" name="POLE_HEIGHT" class="form-control"
                                   value="<?php echo @$rs['POLE_HEIGHT'] ;?>" >
                            <span id="id_validate"> </span>
                        </div>
                        <!---------------------ملكية العامود----------------------->
                        <div class="form-group col-sm-2">
                            <label class="control-label">ملكية العامود</label>
                            <input type="text" id="POLE_PROPERTY_dp" name="POLE_PROPERTY" class="form-control"
                                   value="<?php echo @$rs['POLE_PROPERTY'] ;?>" >
                            <span id="id_validate"> </span>
                        </div>
                        <!--------------------------حالة العامود------------------>
                        <div class="form-group col-sm-2">
                            <label class="control-label">حالة العامود </label>
                            <input type="text" id="POLE_CONDITION_dp" name="POLE_CONDITION" class="form-control"
                                   value="<?php echo @$rs['POLE_CONDITION'] ;?>" >
                            <span id="id_validate"> </span>
                        </div>
                        <!-----------------------------تأريض العامود------------------------------->
                        <div class="form-group col-sm-2">
                            <label class="control-label">تأريض العامود</label>
                            <input type="text" id="POLE_EARTH_dp" name="POLE_EARTH" class="form-control"
                                   value="<?php echo @$rs['POLE_EARTH'] ;?>" >
                            <span id="id_validate"> </span>
                        </div>
                        <!-----------------------------دعمة العامود-------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">دعمة العامود </label>
                            <input type="text" id="SUPPORT_POLE_ID"  name="SUPPORT_POLE"class="form-control"
                                   value="<?php echo @$rs['SUPPORT_POLE'] ;?>" >
                            <span id="id_validate"> </span>
                        </div>
                        <!-------------------------ستاي العامود------------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">ستاي العامود</label>
                            <input type="text" id="STAY_ID"  name="STAY"class="form-control"
                                   value="<?php echo @$rs['STAY'] ;?>" >
                            <span id="id_validate"> </span>
                        </div>
                        <!------------------------------------------------------------------->


                    </div>
                </fieldset>
                <hr/>
                <!---------------------------------------------------------------->
                <fieldset>
                    <legend>بيانات الاذرع المستخدمة</legend>
                    <div class="row">

                        <!------------------------------الأذرع المستخدمة-------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">الاذرع مستخدمة</label>
                            <input type="text" id="USED_ARMS_ID"  name="USED_ARMS"  class="form-control"
                                   value="<?php echo @$rs['USED_ARMS'] ;?>">
                            <span id="id_validate"> </span>
                        </div>
                        <!--------------------------------الأذرع الغير مستخدمة------------------------>
                        <div class="form-group col-sm-3">
                            <label class="control-label">الاذرع الغير مستخدمة</label>
                            <input type="text" id="UNUSED_ARMS_ID"  name="UNUSED_ARMS"class="form-control"
                                   value="<?php echo @$rs['UNUSED_ARMS'] ;?>" >
                            <span id="id_validate"> </span>
                        </div>
                        <!------------------------------عدد عوازل ض.م المستخدمة-------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">عدد عوازل ض.م المستخدمة  </label>
                            <input type="text" id="USED_LV_PIN_INSULATORS_NO_ID"  name="USED_LV_PIN_INSULATORS_NO"class="form-control"
                                   value="<?php echo @$rs['USED_LV_PIN_INSULATORS_NO'] ;?>" >
                            <span id="id_validate"> </span>
                        </div>
                        <!-------------------------------عدد عوازل ض.م الغير المستخدمة--------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">عدد عوازل ض.م الغير المستخدمة</label>
                            <input type="text" id="UNUSED_LV_PIN_INSULATORS_NO_ID"  name="UNUSED_LV_PIN_INSULATORS_NO"class="form-control"
                                   value="<?php echo @$rs['UNUSED_LV_PIN_INSULATORS_NO'] ;?>" >
                            <span id="id_validate"> </span>
                        </div>
                        <!-------------------------------------عدد عوازل ض.م المكسرة-------------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">عدد عوازل ض.م المكسرة</label>
                            <input type="text" id="BROKEN_LV_PIN_INSULATORS_NO_ID"  name="BROKEN_LV_PIN_INSULATORS_NO"class="form-control"
                                   value="<?php echo @$rs['BROKEN_LV_PIN_INSULATORS_NO'] ;?>" >
                            <span id="id_validate"> </span>
                        </div>
                        <!---------------------------عدد مجاري الصاج------------------------------>
                        <div class="form-group col-sm-3">
                            <label class="control-label">عدد مجاري الصاج</label>
                            <input type="text" id="CABLE_GUARDS_NO_ID"  name="CABLE_GUARDS_NO"class="form-control"
                                   value="<?php echo @$rs['CABLE_GUARDS_NO'] ;?>" >
                            <span id="id_validate"> </span>
                        </div>
                        <!--------------------------عدد المغذيات ----------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">عدد المغذيات</label>
                            <input type="text" id="LV_OVERHEAD_FEEDERS_SOURCES_NO_ID"  name="LV_OVERHEAD_FEEDERS_SOURCES_NO"class="form-control"
                                   value="<?php echo @$rs['LV_OVERHEAD_FEEDERS_SOURCES_NO'] ;?>" >
                            <span id="id_validate"> </span>
                        </div>
                        <!-----------------------------عدد كوابل ض.م الارضية----------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">عدد كوابل ض.م الارضية </label>
                            <input type="text" id="NO_OF_LV_UNDERGROUND_FEEDERS_ID"  name="NO_OF_LV_UNDERGROUND_FEEDERS"class="form-control"
                                   value="<?php echo @$rs['NO_OF_LV_UNDERGROUND_FEEDERS'] ;?>" >
                            <span id="id_validate"> </span>
                        </div>
                        <!----------------------------------عدد مشتركين الفاز ونول على العامود--------------------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">عدد مشتركين الفاز ونول على العامود</label>
                            <input type="text" id="NO_OF_SINGLE_PHASE_CUSTOMERS_ID"  name="NO_OF_SINGLE_PHASE_CUSTOMERS"class="form-control"
                                   value="<?php echo @$rs['NO_OF_SINGLE_PHASE_CUSTOMERS'] ;?>" >
                            <span id="id_validate"> </span>
                        </div>
                        <!------------------------------عدد مشتركين 3 فاز على العامود------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">عدد مشتركين 3 فاز على العامود</label>
                            <input type="text" id="NO_OF_THREE_PHASE_CUSTOMERS_ID"  name="NO_OF_THREE_PHASE_CUSTOMERS"class="form-control"
                                   value="<?php echo @$rs['NO_OF_THREE_PHASE_CUSTOMERS'] ;?>" >
                            <span id="id_validate"> </span>
                        </div>

                        <!-------------------------------------------------------------------------------->
                    </div>
                </fieldset>
                <hr/>
                <!-------------------------------------------------------------------------------->
                <fieldset>
                    <legend>بيانات المكان والتوثيق</legend>
                    <div class="row">
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
                        <!----------------------------------------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">داخل/خارج الخدمة</label>
                            <input type="text" class="form-control" name="SERVICE"
                                   value="<?php echo @$rs['SERVICE'] ;?>">
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
                            <input type="text" id="ID"  name="DATA_DOCUMENTATION_TEAM"class="form-control"
                                   value="<?php echo @$rs['DATA_DOCUMENTATION_TEAM'] ;?>">
                            <span id="id_validate"></span>
                        </div>
                        <!------------------------------------------------------------------------>
                        <div class="form-group col-sm-3">
                            <label class="control-label">اسم فريق الادخال</label>
                            <input type="text" id="DATA_ENTRY_TEAM_ID"  name="DATA_ENTRY_TEAM"class="form-control"
                                   value="<?php echo @$rs['DATA_ENTRY_TEAM'] ;?>">
                            <span id="id_validate"></span>
                        </div>
                        <!-----------------------كود المحول-------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">كود المحول</label>
                            <input type="text" id="TRANSFORMER_CODE_ID"  name="TRANSFORMER_CODE"class="form-control"
                                   value="<?php echo @$rs['TRANSFORMER_CODE'] ;?>">
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
                        <div class="form-group col-sm-9">
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
                        <div class="form-group col-sm-9">
                            <label class="control-label">المرفقات</label>
                            <input type="file" class="form-control" name="PHOTO_PAH" value="<?php echo @$rs['PHOTO_PAH'] ;?>" >
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











