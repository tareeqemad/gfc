<?php
/**
 * Created by PhpStorm.
 * User: ljasser
 * Date: 09/12/18
 * Time: 12:11 م
 */
$MODULE_NAME='gis';
$TB_NAME="main";
$rs=($isCreate)? array() : $poles_data[0];
$t=time();
$r =rand(10,99);
$c=$t.$r;
$back_url=base_url("$MODULE_NAME/$TB_NAME/index");
$get_url =base_url("$MODULE_NAME/$TB_NAME/get_poles_info");
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
                 <legend> بيانات العمود </legend>
                    <div class="row">

                        <div class="form-group col-sm-2">
                            <label class="control-label">مسلسل</label>
                            <input type="text" id="ID_id" name="ID" readonly class="form-control"
                                   value="<?php echo @$rs['ID'] ;?>" >
                            <span id="id_validate"> </span>
                        </div>

                        <!------------------------رقم العامود----------------------------->
                <div class="form-group col-sm-2">
                    <label class="control-label">رقم العمود</label>
                        <input type="text" id="POLE_MATERIAL_ID_dp" name="POLE_MATERIAL_ID" class="form-control"
                               value="<?php echo @$rs['POLE_MATERIAL_ID'] ;?>" >
                             <span id="id_validate"> </span>
                             </div>

                        <!--------------------------------------نوع العامود------------------------------->
                 <div class="form-group col-sm-2">
                     <label class="control-label">تسلسل العمود</label>
                         <input type="text" class="form-control" name="POLE_ID_SER" id="MV_POLE_TYPE_dp"
                                 value="<?php echo @$rs['POLE_ID_SER'] ;?>" >
                                <span id="id_validate"> </span>
                           </div>
                        <!---------------------------------- ؟؟؟؟؟----------------------------------->
                       <!--<div class="form-group col-sm-1">
                            <label class="control-label"> ؟؟؟؟؟؟</label>
                            <input type="text" class="form-control" name="POLE_COMPONENT_ID" id="MV_POLE_SIZE_dp"
                                   value="<?php echo @$rs['POLE_COMPONENT_ID'] ;?>" >
                            <span id="id_validate"> </span>
                        </div>
                                                        <!-----------------------------------ارتفاع العامود---------------------------------->
                                    <div class="form-group col-sm-2">
                                        <label class="control-label">ارتفاع العمود</label>
                                        <input type="text" class="form-control" name="MV_POLE_HEIGHT" id="MV_POLE_HEIGHT_dp"
                                                value="<?php echo @$rs['MV_POLE_HEIGHT'] ;?>" >
                                        <span id="id_validate"> </span>
                                    </div>
                        <!-------------------------------------حالة العامود-------------------------------->
                                    <div class="col-md-2">
                                        <label class="control-label">كود العمود </label>
                                        <input type="text" class="form-control" name="MV_POLE_CODE"id="MV_POLE_CODE_dp"
                                                value="<?php echo @$rs['MV_POLE_CODE'] ;?>" >
                                        <span id="id_validate"> </span>
                                    </div>
                        <!--------------------------------------نوع القاعدة-------------------------->
                                    <div class="col-md-2">
                                        <label class="control-label">نوع العمود</label>
                                        <input type="text" class="form-control" name="MV_POLE_TYPE" id="dp_BASE_TYPE"
                                                value="<?php echo @$rs['MV_POLE_TYPE'] ;?>" >
                                        <span id="id_validate"> </span>
                                    </div>
                        <br>
                        <!-------------------------------------حالة القاعدة--------------------------->
                                    <div class="col-md-2">
                                        <label class="control-label">مقاس العمود </label>
                                        <input type="text" class="form-control" name="MV_POLE_SIZE"id="MV_POLE_SIZE_dp"
                                                value="<?php echo @$rs['BASE_CONDITION'] ;?>" >
                                        <span id="id_validate"> </span>
                                    </div>



                       <!----------------------------------حالة العمود--------------------------------->
                        <div class="col-md-2">
                            <label class="control-label">حالة العمود </label>
                            <input type="text" class="form-control" name="MV_POLE_CONDITION"id="MV_POLE_CONDITION_dp"
                                   value="<?php echo @$rs['MV_POLE_CONDITION'] ;?>" >
                            <span id="id_validate"> </span>
                        </div>

                        <!---------------------------------نوع القاعدة---------------------------------->
                        <div class="col-md-2">
                            <label class="control-label">نوع القاعدة</label>
                            <input type="text" class="form-control" name="BASE_TYPE"id="BASE_CONDITION_dp"
                                   value="<?php echo @$rs['BASE_TYPE'] ;?>" >
                            <span id="id_validate"> </span>
                        </div>
                        <!---------------------------------حالة القاعدة---------------------------------->
                        <div class="col-md-2">
                            <label class="control-label">حالة القاعدة </label>
                            <input type="text" class="form-control" name="BASE_CONDITION"id="BASE_CONDITION_dp"
                                   value="<?php echo @$rs['BASE_CONDITION'] ;?>" >
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
                    <!------------------------------الأذرع الناقصة---------------------------->
                    <div class="form-group col-sm-3">
                        <label class="control-label">الاذرع الناقصة</label>
                        <input type="text" id="MISSED_ARMS_ID"  name="MISSED_ARMS"class="form-control"
                               value="<?php echo @$rs['MISSED_ARMS'] ;?>" >
                        <span id="id_validate"> </span>
                    </div>
                    <!-------------------------------تأريض العمود---------------------------->
                    <div class="form-group col-sm-3">
                        <label class="control-label">تأريض العمود</label>
                        <input type="text" class="form-control" name="POLE_EARTHING" id="POLE_EARTHING_ID"
                                value="<?php echo @$rs['POLE_EARTHING'] ;?>">
                        <span id="id_validate"> </span>
                    </div>
                    <br>
                   <!-----------------------------------عدد مانعات الصواعق المستخدمة-------------------------->
                    <div class="form-group col-sm-3">
                        <label class="control-label">عدد مانعات الصواعق المستخدمة</label>
                        <input type="text" id="SURGE_ARRESTORS_NO_ID"  name="SURGE_ARRESTORS_NO"class="form-control"
                               value="<?php echo @$rs['SURGE_ARRESTORS_NO'] ;?>">
                        <span id="id_validate"> </span>
                    </div>

                    <!----------------------------عدد مانعات الصواعق التالفة----------->
                    <div class="form-group col-sm-3">
                        <label class="control-label">عدد مانعات الصواعق التالفة</label>
                        <input type="text" id="ID"  name="D_SURGE_ARRESTORS_NO"class="form-control"
                               value="<?php echo @$rs['D_SURGE_ARRESTORS_NO'] ;?>">
                        <span id="id_validate"> </span>
                    </div>
                    <!----------------------------------------->
                    <div class="form-group col-sm-3">
                        <label class="control-label">عدد مانعات الأوميجا المستخدمة</label>
                        <input type="text" id="USED_OMEGA_NO_ID"  name="USED_OMEGA_NO"class="form-control"
                               value="<?php echo @$rs['USED_OMEGA_NO'] ;?>">
                        <span id="id_validate"> </span>
                    </div>
                    <!----------------------------------------->
                    <div class="form-group col-sm-3">
                        <label class="control-label">عدد سكاكين الضغط العالي</label>
                        <input type="text" id="ISOLATING_SWITCHES_NO_ID"  name="ISOLATING_SWITCHES_NO"class="form-control"
                               value="<?php echo @$rs['ISOLATING_SWITCHES_NO'] ;?>">
                        <span id="id_validate"> </span>
                    </div>
                    <!----------------------------------------->
                    <div class="form-group col-sm-3">
                        <label class="control-label">عدد عوازل التعليق المكسورة </label>
                        <input type="text" id="B_PIN_INSULATORS_NO_ID"  name="B_PIN_INSULATORS_NO"class="form-control"
                               value="<?php echo @$rs['B_PIN_INSULATORS_NO'] ;?>">
                        <span id="id_validate"> </span>
                    </div>
                    <!-------------------------------------------->
                    <div class="form-group col-sm-3">
                        <label class="control-label">عدد عوازل البروسولان المستخدمة</label>
                        <input type="text" id="U_P_PORCELAIN_INSULATORS_NO_ID"  name="U_P_POLYMER_INSULATORS_NO"class="form-control"
                               value="<?php echo @$rs['U_P_POLYMER_INSULATORS_NO'] ;?>">
                        <span id="id_validate"> </span>
                    </div>
                    <!----------------------------------------->
                    <div class="form-group col-sm-3">
                        <label class="control-label">عدد عوازل التعليق البروسولان الغير مستخدمة</label>
                        <input type="text" id="U_PIN_INSULATORS_NO|_ID"  name="UN_P_INSULATORS_NO"class="form-control"
                               value="<?php echo @$rs['UN_P_INSULATORS_NO'] ;?>">
                        <span id="id_validate"> </span>
                    </div>
                    <!------------------------------------------------------------------------>
                    <div class="form-group col-sm-3">
                        <label class="control-label">عدد عوازل الشد البورسلان المستخدم</label>
                        <input type="text" id="U_T_PORCELAIN_INSULATORS_NO_ID"  name="B_P_INSULATORS_NO"class="form-control"
                               value="<?php echo @$rs['B_P_INSULATORS_NO'] ;?>">
                        <span id="id_validate"> </span>
                    </div>
                    <!-------------------------------------------------------------------------->
                    <div class="form-group col-sm-3">
                        <label class="control-label">عدد عوازل الشد البورسلان المكسورة</label>
                        <input type="text" id="B_T_PORCELAIN_INSULATORS_NO_ID"  name="U_T_GLASS_INSULATORS_NO"class="form-control"
                               value="<?php echo @$rs['U_T_GLASS_INSULATORS_NO'] ;?>">
                        <span id="id_validate"></span>
                    </div>
                    <!----------------------------------------------------------------------------->
                    <div class="form-group col-sm-3">
                        <label class="control-label">عدد عوازل الشد البورسلان الغير مستخدمة</label>
                        <input type="text" id="N_T_PORCELAIN_INSULATORS_NO_ID"  name="U_T_POLYMER_INSULATORS_NO"class="form-control"
                               value="<?php echo @$rs['U_T_POLYMER_INSULATORS_NO'] ;?>">
                        <span id="id_validate"> </span>
                    </div>
                    <!---------------------------------------------------------------------------------->
                    <div class="form-group col-sm-3">
                        <label class="control-label">عدد عوازل التعليق البولمير المستخدم</label>
                        <input type="text" id="U_P_POLYMER_INSULATORS_NO_ID"  name="U_T_PORCELAIN_INSULATORS_NO"class="form-control"
                               value="<?php echo @$rs['U_T_PORCELAIN_INSULATORS_NO'] ;?>">
                        <span id="id_validate"> </span>
                    </div>
                    <!---------------------------------------------------------------------------------->
                    <div class="form-group col-sm-3">
                        <label class="control-label">عدد عوازل الشد  البولمير المستخدم</label>
                        <input type="text" id="U_T_POLYMER_INSULATORS_NO_ID"  name="B_T_GLASS_INSULATORS_NO"class="form-control"
                               value="<?php echo @$rs['B_T_GLASS_INSULATORS_NO'] ;?>">
                        <span id="id_validate"> </span>
                    </div>
                    <!------------------------------------عدد عوازل الشد البوليمر التالفة----------------------->
                    <div class="form-group col-sm-3">
                        <label class="control-label">عدد عوازل الشد  البولمير التالفة</label>
                        <input type="text" id="ID"  name="D_T_POLYMER_INSULATORS_NO"class="form-control"
                               value="<?php echo @$rs['D_T_POLYMER_INSULATORS_NO'] ;?>">
                        <span id="id_validate"> </span>
                    </div>
                    <!-------------------------------------------عدد عوازل الشد الزجاجية المستخدمة----------->
                    <div class="form-group col-sm-3">
                        <label class="control-label">عدد عوازل الشد الزجاجية مستخدمة</label>
                        <input type="text" id="ID"  name="B_T_PORCELAIN_INSULATORS_NO"class="form-control"
                               value="<?php echo @$rs['B_T_PORCELAIN_INSULATORS_NO'] ;?>">
                        <span id="id_validate"> </span>
                    </div>
                    <!----------------------------------عدد عوازل الشد الزجاجية المكسورة-------------------------------->
                    <div class="form-group col-sm-3">
                        <label class="control-label">عدد عوازل الشد الزجاجية المكسورة</label>
                        <input type="text" id="ID"  name="B_T_GLASS_INSULATORS_NO"class="form-control"
                               value="<?php echo @$rs['B_T_GLASS_INSULATORS_NO'] ;?>">
                        <span id="id_validate"> </span>
                    </div>
                    <!----------------------------------------عدد عوازل الشد الزجاجية الغير مستخدمة----------->
                    <div class="form-group col-sm-3">
                        <label class="control-label">عدد عوازل الشد الزجاجية الغير مستخدمة</label>
                        <input type="text" id="ID"  name="UN_T_POLYMER_INSULATORS_NO"class="form-control"
                               value="<?php echo @$rs['UN_T_POLYMER_INSULATORS_NO'] ;?>">
                        <span id="id_validate"> </span>
                    </div>
                    <!---------------------------------ستاي العمود---------------------------->
                    <div class="form-group col-sm-3">
                        <label class="control-label">ستاي العمود</label>
                        <input type="text" class="form-control" name="MV_POLE_STAY"
                               value="<?php echo @$rs['MV_POLE_STAY'] ;?>">
                            <span id="id_validate"> </span>
                    </div>
    <!---------------------------------------------------------------------------------------->
                    <div class="form-group col-sm-3">
                        <label class="control-label">دعمة عمود</label>
                        <input type="text" class="form-control" name="SUPPORT_POLE" value="<?php echo @$rs['SUPPORT_POLE'] ;?>" >
                        <span id="id_validate"> </span>
                    </div>
    <!----------------------------------------------------------------------------------------->
                    <div class="form-group col-sm-3">
                        <label class="control-label">نوع سحب السلك</label>
                        <input type="text" class="form-control" name="TYPE_OF_WIRE_PULLING" value="<?php echo @$rs['TYPE_OF_WIRE_PULLING'] ;?>" >
                            <span id="id_validate"> </span>
                    </div>
        <!----------------------------------------------------------------------->
                    <div class="form-group col-sm-3">
                        <label class="control-label">عدد وحدات الشد</label>
                        <input type="text" id="ID"  name="TENSION_UNIT_NO"class="form-control"
                               value="<?php echo @$rs['TENSION_UNIT_NO'] ;?>" >
                        <span id="id_validate"> </span>
                    </div>
                    <!-------------------------------------------------------------------------------->
                    <div class="form-group col-sm-3">
                        <label class="control-label">عدد الفيدرات الهوائية على العامود</label>
                        <input type="text" id="ID"  name="TENSION_UNIT_NO"class="form-control"
                               value="<?php echo @$rs['TENSION_UNIT_NO'] ;?>" >
                        <span id="id_validate"> </span>
                    </div>
                  <!-------------------------------------------------------------------------------->
                    <div class="form-group col-sm-3">
                        <label class="control-label">عدد فيدرات الكوابل الأرضية</label>
                        <input type="text" id="ID" name="MV_OH_FEEDERS_NO" class="form-control"
                               value="<?php echo @$rs['MV_OH_FEEDERS_NO'] ;?>">
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
               <!-------------------------------------------------------------------------------->
                    <div class="form-group col-sm-3">
                          <label class="control-label">رقم المشروع</label>
                          <input type="text" id="ID"  name="MV_UG_FEEDERS_NO"class="form-control"
                                 value="<?php echo @$rs['MV_UG_FEEDERS_NO'] ;?>">
                          <span id="id_validate"></span>
                    </div>
        <!------------------------------------------------------------------------------------------------->
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
                        <!------------------------------------------------------------------------>


       <!------------------------------------------------------------------------------------------------>
                        <div class="form-group col-sm-3">
                            <label class="control-label">رقم الشارع </label>
                            <input type="text" id="DISTRICT_NAME_dp"  name="STREET_NO"class="form-control"
                                   value="<?php echo @$rs['STREET_NO'] ;?>">
                            <span id="id_validate"></span>
                        </div>
                        </br>
                        <!-------------------------------------------------------------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">اسم الشارع</label>
                            <input type="text" id="ID"  name="STREET_NAME"class="form-control"
                                   value="<?php echo @$rs['STREET_NAME'] ;?>">
                            <span id="id_validate"></span>
                        </div>

                        <!-------------------------------------------------------------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">المنطقة </label>
                            <input type="text" id="ID"  name="DISTRICT_NAME"class="form-control"
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
                            <input type="text" id="ID"  name="TRANSFORMERS_NAMES_AR"class="form-control"
                                   value="<?php echo @$rs['TRANSFORMERS_NAMES_AR'] ;?>">
                            <span id="id_validate"></span>
                        </div>
                        <!------------------------------------------------------------------------------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">اسم فريق الادخال</label>
                            <input type="text" id="ID"  name="PROJECT_NO"class="form-control"
                                   value="<?php echo @$rs['PROJECT_NO'] ;?>">
                            <span id="id_validate"></span>
                        </div>

                        <!---------------------------------------الملاحظات------------------------------------------->
                        <div class="form-group col-sm-9">
                            <label class="control-label">الملاحظات</label>
                            <textarea type="text" rows="1" class="form-control" name="NOTES"
                                      value="<?php echo @$rs['NOTES'] ;?>"> </textarea>
                            <span id="id_validate"></span>
                        </div>
                        <!------------------------------------------------------------------------------------------------->
                        <div class="form-group col-sm-9">
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






