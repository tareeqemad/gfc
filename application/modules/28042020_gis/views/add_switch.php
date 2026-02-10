<?php
/**
 * Created by PhpStorm.
 * User: ljasser
 * Date: 27/01/19
 * Time: 12:13 م
 */
$MODULE_NAME='gis';
$TB_NAME='switch_controller';
$rs=($isCreate)? array(): count($switch_data) > 0 ? $switch_data[0] : array() ;
$back_url=base_url("$MODULE_NAME/$TB_NAME/index");
$get_url =base_url("$MODULE_NAME/$TB_NAME/get_switch_info");
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
                    <legend> بيانات السكينة </legend>
                    <div class="row">

                        <div class="form-group col-sm-3">
                            <label class="control-label">مسلسل</label>
                            <input type="text" id="ID_id" name="ID" readonly class="form-control"
                                   value="<?php echo @$rs['ID'] ;?>" >
                            <span id="id_validate"> </span>
                        </div>

                        <!------------------------كود سكينة ---------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">كود سكينة </label>
                            <input type="text" id="ISOLATING_SWITCH_CODE_dp" name="ISOLATING_SWITCH_CODE" class="form-control"
                                   value="<?php echo @$rs['ISOLATING_SWITCH_CODE'] ;?>" >
                            <span id="id_validate"> </span>
                        </div>
                        <!-----------------------------كود العامود - خاص ببرنامج GIS--------------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">كود العامود - خاص ببرنامج GIS</label>
                            <input type="text" id="POLE_MATERIAL_ID_dp" name="POLE_MATERIAL_ID" class="form-control"
                                   value="<?php echo @$rs['POLE_MATERIAL_ID'] ;?>" >
                            <span id="id_validate"> </span>
                        </div>
                        <!----------------------------------------كود السكينة------------------------------------------>
                        <div class="form-group col-sm-3">
                            <label class="control-label">كود السكينة</label>
                            <input type="text" id="SWITCH_MATERIAL_ID_dp" name="SWITCH_MATERIAL_ID" class="form-control"
                                   value="<?php echo @$rs['SWITCH_MATERIAL_ID'] ;?>" >
                            <span id="id_validate"> </span>
                        </div>
                        <!----------------------------------------كود عامود ض.ع--------------------------------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">كود عامود ض.ع</label>
                            <input type="text" id="MV_POLE_CODE_dp" name="MV_POLE_CODE" class="form-control"
                                   value="<?php echo @$rs['MV_POLE_CODE'] ;?>" >
                            <span id="id_validate"> </span>
                        </div>
                        <!-----------------------------------------الشركة المصنعة لسكينة ض.ع ------------------------------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">الشركة المصنعة لسكينة ض.ع </label>
                            <input type="text" id="IS_MANUFACTURER_dp" name="IS_MANUFACTURER" class="form-control"
                                   value="<?php echo @$rs['IS_MANUFACTURER'] ;?>" >
                            <span id="id_validate"> </span>
                        </div>
                        <!--------------------------------داخل الخدمة / خارج الخدمة لسكينة ض.ع----------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">الشركة المصنعة لسكينة ض.ع </label>
                            <input type="text" id="IS_SERVICE_dp" name="IS_SERVICE" class="form-control"
                                   value="<?php echo @$rs['IS_SERVICE'] ;?>" >
                            <span id="id_validate"> </span>
                        </div>
                        <!-------------------------------------عملية التشغيل لسكينة ض.ع------------------------------------------------>
                        <div class="form-group col-sm-3">
                            <label class="control-label">عملية التشغيل لسكينة ض.ع</label>
                            <input type="text" id="IS_OPERATION_dp" name="IS_OPERATION" class="form-control"
                                   value="<?php echo @$rs['IS_OPERATION'] ;?>" >
                            <span id="id_validate"> </span>
                        </div>
                        <!----------------------------------نوع التحكم في سكينة ض.ع------------------------------------>
                        <div class="form-group col-sm-3">
                            <label class="control-label">نوع التحكم في سكينة ض.ع</label>
                            <input type="text" id="IS_CONTROL_dp" name="IS_CONTROL" class="form-control"
                                   value="<?php echo @$rs['IS_CONTROL'] ;?>" >
                            <span id="id_validate"> </span>
                        </div>
                        <!----------------------------------------نوع عازل سكينة ض.ع---------------------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">نوع عازل سكينة ض.ع</label>
                            <input type="text" id="IS_INSULATOR_dp" name="IS_INSULATOR" class="form-control"
                                   value="<?php echo @$rs['IS_INSULATOR'] ;?>" >
                            <span id="id_validate"> </span>
                        </div>
                        <!---------------------------------------اتجاه سكينة ض.ع------------------------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">اتجاه سكينة ض.ع</label>
                            <input type="text" id="IS_DIRECTION_dp" name="IS_DIRECTION" class="form-control"
                                   value="<?php echo @$rs['IS_DIRECTION'] ;?>" >
                            <span id="id_validate"> </span>
                        </div>
                        <!--------------------------------------------حالة سكينة ض.ع---------------------------------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">حالة سكينة ض.ع</label>
                            <input type="text" id="IS_CONDITION_dp" name="IS_CONDITION" class="form-control"
                                   value="<?php echo @$rs['IS_CONDITION'] ;?>" >
                            <span id="id_validate"> </span>
                        </div>
                        <!---------------------------------------تأريض يد سكينة ض.ع------------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">تأريض يد سكينة ض.ع</label>
                            <input type="text" id="IS_HAND_EARTHING_dp" name="IS_HAND_EARTHING" class="form-control"
                                   value="<?php echo @$rs['IS_HAND_EARTHING'] ;?>" >
                            <span id="id_validate"> </span>
                        </div>
                        <!---------------------------------------تاريخ تركيب سكينة ض.ع------------------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">تاريخ تركيب سكينة ض.ع</label>
                            <input type="text"<?=$date_attr?> id="IS_INSTALLATION_DATE_dp" name="IS_INSTALLATION_DATE" class="form-control"
                                   value="<?php echo @$rs['IS_INSTALLATION_DATE'] ;?>" >
                            <span id="id_validate"> </span>
                        </div>
                        <!----------------------------------------شبكة الدخل----------------------------------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">شبكة الدخل</label>
                            <input type="text" id="START_MV_NETWORK_CODE_dp" name="START_MV_NETWORK_CODE" class="form-control"
                                   value="<?php echo @$rs['START_MV_NETWORK_CODE'] ;?>" >
                            <span id="id_validate"> </span>
                        </div>
                        <!-----------------------------------------شبكة الخرج------------------------------------>
                        <div class="form-group col-sm-3">
                            <label class="control-label">شبكة الخرج </label>
                            <input type="text" id="END_MV_NETWORK_CODE_dp" name="END_MV_NETWORK_CODE" class="form-control"
                                   value="<?php echo @$rs['END_MV_NETWORK_CODE'] ;?>" >
                            <span id="id_validate"> </span>
                        </div>
                        <!--------------------------------------اسم الخط المغذي---------------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">اسم الخط المغذي</label>
                            <input type="text" id="LINE_NAME_dp" name="LINE_NAME" class="form-control"
                                   value="<?php echo @$rs['LINE_NAME'] ;?>" >
                            <span id="id_validate"> </span>
                        </div>
                        <!-------------------------------اسم المحافظة---------------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">اسم المحافظة</label>
                            <input type="text" id="GOVERNORATE_NAME_dp" name="GOVERNORATE_NAME" class="form-control"
                                   value="<?php echo @$rs['GOVERNORATE_NAME'] ;?>" >
                            <span id="id_validate"> </span>
                        </div>
                        <!---------------------------------رقم الشارع-------------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">رقم الشارع </label>
                            <input type="text" id="STREET_NO_dp" name="STREET_NO" class="form-control"
                                   value="<?php echo @$rs['STREET_NO'] ;?>" >
                            <span id="id_validate"> </span>
                        </div>
                        <!---------------------------------اسم الشارع-------------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">اسم الشارع</label>
                            <input type="text" id="STREET_NAME_dp" name="STREET_NAME" class="form-control"
                                   value="<?php echo @$rs['STREET_NAME'] ;?>" >
                            <span id="id_validate"> </span>
                        </div>
                        <!-------------------------------------اسم المنطقة----------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">اسم المنطقة</label>
                            <input type="text" id="DISTRICT_NAME_dp" name="DISTRICT_NAME" class="form-control"
                                   value="<?php echo @$rs['DISTRICT_NAME'] ;?>" >
                            <span id="id_validate"> </span>
                        </div>
                        <!-----------------------------------تاريخ التوثيق------------------------------>
                        <div class="form-group col-sm-3">
                            <label class="control-label">تاريخ التوثيق </label>
                            <input type="text"   <?=$date_attr?>    id="DOCUMENTATION_DATE_dp" name="DOCUMENTATION_DATE" class="form-control"
                                   value="<?php echo @$rs['DOCUMENTATION_DATE'] ;?>" >
                            <span id="id_validate"> </span>
                        </div>
                        <!----------------------------------اسم فريق ادخال البيانات----------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">اسم فريق ادخال البيانات</label>
                            <input type="text" id="DATA_ENTRY_TEAM_dp" name="DATA_ENTRY_TEAM" class="form-control"
                                   value="<?php echo @$rs['DATA_ENTRY_TEAM'] ;?>" >
                            <span id="id_validate"> </span>
                        </div>
                        <!--------------------------------ملاحظات--------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">الملاحظات</label>
                            <textarea type="text" rows="1" class="form-control" name="NOTES"
                                      value="<?php echo @$rs['NOTES'] ;?>"> </textarea>
                            <span id="id_validate"></span>
                        </div>
                        <!-----------------------------رقم المشروع----------------------------------->
                        <div class="form-group col-sm-3">
                            <label class="control-label">رقم المشروع</label>
                            <input type="text" id="OBJECTID_dp" name="OBJECTID" class="form-control"
                                   value="<?php echo @$rs['OBJECTID'] ;?>" >
                            <span id="id_validate"> </span>
                        </div>

                        <!------------------------------------------------------>
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

