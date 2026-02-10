<?php
/**
 * Created by PhpStorm.
 * User: ljasser
 * Date: 09/02/19
 * Time: 01:19 م
 */

$MODULE_NAME='gis';
$TB_NAME="Room_Controller";
$rs=($isCreate)? array() : $Room_data[0];

$back_url=base_url("$MODULE_NAME/$TB_NAME/index");
$get_url =base_url("$MODULE_NAME/$TB_NAME/get_Room_info");
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
    <legend>بيانات الغرفة الأساسية</legend>
    <div class="row">
        <!--------------------------------------------->
        <div class="form-group col-sm-1">
            <label class="control-label">مسلسل</label>
            <input type="text" id="ID_id" name="ID" readonly class="form-control"
                   value="<?php echo @$rs['ID'] ;?>" >
            <span id="id_validate"> </span>
        </div>
        <!--------------------------رقم المشروع-------------------------->
        <div class="form-group col-sm-2">
            <label class="control-label">رقم المشروع</label>
            <input type="text" id="OBJECTID_id" name="OBJECTID"  class="form-control"
                   value="<?php echo @$rs['OBJECTID'] ;?>" >
            <span id="id_validate"> </span>
        </div>
        <!-------------------------- رقم الغرفة------------------------>
        <div class="form-group col-sm-3">
            <label class="control-label">رقم الغرفة </label>
            <input type="text" id="ROOM__CODE_id" name="ROOM__CODE"  class="form-control"
                   value="<?php echo @$rs['ROOM__CODE'] ;?>" >
            <span id="id_validate"> </span>
        </div>
        <!-----------------------------ID------------------------>
        <div class="form-group col-sm-3">
            <label class="control-label">ID </label>
            <input type="text" id="ID_id" name="ID1"  class="form-control"
                   value="<?php echo @$rs['ID1'] ;?>" >
            <span id="id_validate"> </span>
        </div>
        <!----------------------------------ROOM_COMPO--------------------------------->
        <div class="form-group col-sm-3">
            <label class="control-label">ROOM_COMPO </label>
            <input type="text" id="ROOM_COMPO_id" name="ROOM_COMPO"  class="form-control"
                   value="<?php echo @$rs['ROOM_COMPO'] ;?>" >
            <span id="id_validate"> </span>
        </div>
        <br>
        <!---------------------اسم المحافظة----------------->
        <div class="form-group col-sm-3">
            <label class="control-label">اسم المحافظة </label>
            <input type="text" id="GOVERNORAT_id" name="GOVERNORAT"  class="form-control"
                   value="<?php echo @$rs['GOVERNORAT'] ;?>" >
            <span id="id_validate"> </span>
        </div>
        <!--------------------------رقم الشارع---------------------->
        <div class="form-group col-sm-3">
            <label class="control-label">رقم الشارع</label>
            <input type="text" id="STREET_NO_id" name="STREET_NO"  class="form-control"
                   value="<?php echo @$rs['STREET_NO'] ;?>" >
            <span id="id_validate"> </span>
        </div>
        <!----------------------------اسم الشارع--------------------------->
        <div class="form-group col-sm-3">
            <label class="control-label">اسم الشارع</label>
            <input type="text" id="STREET_NAM_id" name="STREET_NAM"  class="form-control"
                   value="<?php echo @$rs['STREET_NAM'] ;?>" >
            <span id="id_validate"> </span>
        </div>
        <!------------------------اسم المنطقة-------------------->
        <div class="form-group col-sm-3">
            <label class="control-label">اسم المنطقة</label>
            <input type="text" id="DISTRICT_N_id" name="DISTRICT_N"  class="form-control"
                   value="<?php echo @$rs['DISTRICT_N'] ;?>" >
            <span id="id_validate"> </span>
        </div>
        <!---------------------------AREA_NO---------------------------->
        <div class="form-group col-sm-3">
            <label class="control-label">AREA_NO</label>
            <input type="text" id="AREA_NO_id" name="AREA_NO"  class="form-control"
                   value="<?php echo @$rs['AREA_NO'] ;?>" >
            <span id="id_validate"> </span>
        </div>
        <!-----------------------------رقم المشروع--------------------->
        <div class="form-group col-sm-3">
            <label class="control-label">رقم المشروع</label>
            <input type="text" id="PROJECT_NO_id" name="PROJECT_NO"  class="form-control"
                   value="<?php echo @$rs['PROJECT_NO'] ;?>" >
            <span id="id_validate"> </span>
        </div>
        <!----------------------------تاريخ انشاء الغرفة----------------------------->
        <div class="form-group col-sm-3">
            <label class="control-label">تاريخ انشاء الغرفة  </label>
            <input type="text" <?=$date_attr?> id="ROOM_CONST_id" name="ROOM_CONST"  class="form-control"
                   value="<?php echo @$rs['ROOM_CONST'] ;?>" >
            <span id="id_validate"> </span>
        </div>
        <!---------------------------- نوع الغرفة---------------------------->
        <div class="form-group col-sm-3">
            <label class="control-label">نوع الغرفة</label>
            <input type="text" id="ROOM_TYPE_id" name="ROOM_TYPE"  class="form-control"
                   value="<?php echo @$rs['ROOM_TYPE'] ;?>" >
            <span id="id_validate"> </span>
        </div>
        <!------------------------------اسم المبنى--------------------------------------->

        <div class="form-group col-sm-3">
            <label class="control-label">اسم المبنى</label>
            <input type="text" id="BUILDING_N_id" name="BUILDING_N"  class="form-control"
                   value="<?php echo @$rs['BUILDING_N'] ;?>" >
            <span id="id_validate"> </span>
        </div>
        <!-----------------------عدد المحولات---------------->
        <div class="form-group col-sm-3">
            <label class="control-label">عدد المحولات </label>
            <input type="text" id="TRANSFORME_id" name="TRANSFORME"  class="form-control"
                   value="<?php echo @$rs['TRANSFORME'] ;?>" >
            <span id="id_validate"> </span>
        </div>
        <!------------------------------اسماء المحولات--------------------------->
        <div class="form-group col-sm-3">
            <label class="control-label">اسماء المحولات</label>
            <input type="text" id=TRANSFORM1_id" name="TRANSFORM1"  class="form-control"
                   value="<?php echo @$rs['TRANSFORM1'] ;?>" >
            <span id="id_validate"> </span>
        </div>
        <!--------------------------عدد القواطع الأرضية ------------------------>
        <div class="form-group col-sm-3">
            <label class="control-label">عدد القواطع الأرضية </label>
            <input type="text" id=RMU_NO_id" name="RMU_NO"  class="form-control"
                   value="<?php echo @$rs['RMU_NO'] ;?>" >
            <span id="id_validate"> </span>
        </div>
        <!----------------------عدد لوحات التوزيع------------------->
        <div class="form-group col-sm-3">
            <label class="control-label">عدد لوحات التوزيع</label>
            <input type="text" id=DISTRIBUTI_id" name="DISTRIBUTI"  class="form-control"
                   value="<?php echo @$rs['DISTRIBUTI'] ;?>" >
            <span id="id_validate"> </span>
        </div>
        <!------------------عدد سكاكين ض.م ------------------>
        <div class="form-group col-sm-3">
            <label class="control-label">عدد سكاكين ض.م </label>
            <input type="text" id=LTL_SWITCH_id" name="LTL_SWITCH"  class="form-control"
                   value="<?php echo @$rs['LTL_SWITCH'] ;?>" >
            <span id="id_validate"> </span>
        </div>
        <!------------------حامل الكوابل---------------->
        <div class="form-group col-sm-3">
            <label class="control-label">حامل الكوابل</label>
            <input type="text" id=CABLES_HOL_id" name="CABLES_HOL"  class="form-control"
                   value="<?php echo @$rs['CABLES_HOL'] ;?>" >
            <span id="id_validate"> </span>
        </div>
        <!---------------اسم الشبكات المغذية------------->
        <div class="form-group col-sm-3">
            <label class="control-label">اسم الشبكات المغذية</label>
            <input type="text" id=LINE1_NAME_id" name="LINE1_NAME"  class="form-control"
                   value="<?php echo @$rs['LINE1_NAME'] ;?>" >
            <span id="id_validate"> </span>
        </div>
        <!-----------داخل الخدمة / خارج الخدمة--------->
        <div class="form-group col-sm-3">
            <label class="control-label">داخل الخدمة / خارج الخدمة</label>
            <input type="text" id=SERVICE_id" name="SERVICE"  class="form-control"
                   value="<?php echo @$rs['SERVICE'] ;?>" >
            <span id="id_validate"> </span>
        </div>
        <!--------مقاس الغرفة----->
        <div class="form-group col-sm-3">
            <label class="control-label">مقاس الغرفة</label>
            <input type="text" id=ROOM_SIZES_id" name="ROOM_SIZE"  class="form-control"
                   value="<?php echo @$rs['ROOM_SIZE'] ;?>" >
            <span id="id_validate"> </span>
        </div>
        <!--------------------------ROOM_MV_RI-------------------->
        <div class="form-group col-sm-3">
            <label class="control-label">ROOM_MV_RI </label>
            <input type="text" id=ROOM_MV_RI_id" name="ROOM_MV_RI"  class="form-control"
                   value="<?php echo @$rs['ROOM_MV_RI'] ;?>" >
            <span id="id_validate"> </span>
        </div>
        <!--------------------------ROOM_LV_RI-------------------->
        <div class="form-group col-sm-3">
            <label class="control-label">ROOM_LV_RI </label>
            <input type="text" id=ROOM_LV_RI_id" name="ROOM_LV_RI"  class="form-control"
                   value="<?php echo @$rs['ROOM_LV_RI'] ;?>" >
            <span id="id_validate"> </span>
        </div>
        <!--------------------------حالة البناء-------------------->
        <div class="form-group col-sm-3">
            <label class="control-label">حالة البناء </label>
            <input type="text" id=CONSTRUCTI_id" name="CONSTRUCTI"  class="form-control"
                   value="<?php echo @$rs['CONSTRUCTI'] ;?>" >
            <span id="id_validate"> </span>
        </div>
        <!-------------تهوية الغرفة------------------>
        <div class="form-group col-sm-3">
            <label class="control-label">تهوية الغرفة</label>
            <input type="text" id=ROOM_VENTI_id" name="ROOM_VENTI"  class="form-control"
                   value="<?php echo @$rs['ROOM_VENTI'] ;?>" >
            <span id="id_validate"> </span>
        </div>
        <!---------------------------دهان الغرفة--------------------------->
        <div class="form-group col-sm-3">
            <label class="control-label">دهان الغرفة</label>
            <input type="text" id=ROOM_PAINT_id" name="ROOM_PAINT"  class="form-control"
                   value="<?php echo @$rs['ROOM_PAINT'] ;?>" >
            <span id="id_validate"> </span>
        </div>
        <!-------------------------نظافة الغرفة------------------------>
        <div class="form-group col-sm-3">
            <label class="control-label">نظافة الغرفة </label>
            <input type="text" id=ROOM_CLEAN_id" name="ROOM_CLEAN"  class="form-control"
                   value="<?php echo @$rs['ROOM_CLEAN'] ;?>" >
            <span id="id_validate"> </span>
        </div>
        <!------------------------- اسطوانة الاطفاء------------------------>
        <div class="form-group col-sm-3">
            <label class="control-label">اسطوانة الاطفاء</label>
            <input type="text" id=FIRE_EXTIN_id" name="FIRE_EXTIN"  class="form-control"
                   value="<?php echo @$rs['FIRE_EXTIN'] ;?>" >
            <span id="id_validate"> </span>
        </div>
        <!----------------------إنارة الغرفة----------------------->
        <div class="form-group col-sm-3">
            <label class="control-label">إنارة الغرفة </label>
            <input type="text" id=ROOM_LIGHT_id" name="ROOM_LIGHT"  class="form-control"
                   value="<?php echo @$rs['ROOM_LIGHT'] ;?>" >
            <span id="id_validate"> </span>
        </div>
        <!--------------------تأريض شبك الحماية-------------------->
        <div class="form-group col-sm-3">
            <label class="control-label"> تأريض شبك الحماية </label>
            <input type="text" id=SEPARATION_id" name="SEPARATION"  class="form-control"
                   value="<?php echo @$rs['SEPARATION'] ;?>" >
            <span id="id_validate"> </span>
        </div>
        <!------------------الشبابيك---------->
        <div class="form-group col-sm-3">
            <label class="control-label"> الشبابيك   </label>
            <input type="text" id=WINDOWS_id" name="WINDOWS"  class="form-control"
                   value="<?php echo @$rs['WINDOWS'] ;?>" >
            <span id="id_validate"> </span>
        </div>
        <!----------------------نوع الابواب------------------------>
        <div class="form-group col-sm-3">
            <label class="control-label">نوع الابواب</label>
            <input type="text" id=DOORS_TYPE_id" name="DOORS_TYPE"  class="form-control"
                   value="<?php echo @$rs['DOORS_TYPE'] ;?>" >
            <span id="id_validate"> </span>
        </div>
    <!-----------------------------نوع الاقفال------------------------------->
        <div class="form-group col-sm-3">
            <label class="control-label">نوع الاقفال </label>
            <input type="text" id=DOORS_LOCK_id" name="DOORS_LOCK"  class="form-control"
                   value="<?php echo @$rs['DOORS_LOCK'] ;?>" >
            <span id="id_validate"> </span>
        </div>
        <!-----------------------------تأريض الأبواب---------------------------->
        <div class="form-group col-sm-3">
            <label class="control-label">تأريض الأبواب</label>
            <input type="text" id=DOORS_EART_id" name="DOORS_EART"  class="form-control"
                   value="<?php echo @$rs['DOORS_EART'] ;?>" >
            <span id="id_validate"> </span>
        </div>
        <!-------------------------شبك الحماية------------------------->
        <div class="form-group col-sm-3">
            <label class="control-label">شبك الحماية </label>
            <input type="text" id=SEPARATIO1_id" name="SEPARATIO1"  class="form-control"
                   value="<?php echo @$rs['SEPARATIO1'] ;?>" >
            <span id="id_validate"> </span>
        </div>
        <!----------------------تأريض الشبابيك------------------>
        <div class="form-group col-sm-3">
            <label class="control-label"> تأريض الشبابيك </label>
            <input type="text" id=WINDOWS_EA_id" name="WINDOWS_EA"  class="form-control"
                   value="<?php echo @$rs['WINDOWS_EA'] ;?>" >
            <span id="id_validate"> </span>
        </div>
        <!------------------تاريخ التوثيق----------------->
        <div class="form-group col-sm-3">
            <label class="control-label"> تاريخ التوثيق  </label>
            <input type="text" <?=$date_attr?>id=DOCUMENTAT_id" name="DOCUMENTAT"  class="form-control"
                   value="<?php echo @$rs['DOCUMENTAT'] ;?>" >
            <span id="id_validate"> </span>
        </div>
        <!-----------------MODIFIED_D----------------->
        <div class="form-group col-sm-3">
            <label class="control-label">  MODIFIED_D </label>
            <input type="text" id=MODIFIED_D_id" name="MODIFIED_D"  class="form-control"
                   value="<?php echo @$rs['MODIFIED_D'] ;?>" >
            <span id="id_validate"> </span>
        </div>
        <!-----------------اسم فريق التوثيق----------------->
        <div class="form-group col-sm-3">
            <label class="control-label">  اسم فريق التوثيق </label>
            <input type="text" id=DATA_DOCUM_id" name="DATA_DOCUM"  class="form-control"
                   value="<?php echo @$rs['DATA_DOCUM'] ;?>" >
            <span id="id_validate"> </span>
        </div>
        <!-----------------اسم فريق ادخال البيانات--------------->
        <div class="form-group col-sm-3">
            <label class="control-label">   اسم فريق ادخال البيانات  </label>
            <input type="text" id=DATA_ENTRY_id" name="DATA_ENTRY"  class="form-control"
                   value="<?php echo @$rs['DATA_ENTRY'] ;?>" >
            <span id="id_validate"> </span>
        </div>
        <!--------------SHAPE_AREA------------>
        <div class="form-group col-sm-3">
            <label class="control-label">SHAPE_AREA</label>
            <input type="text" id=SHAPE_AREA_id" name="SHAPE_AREA"  class="form-control"
                   value="<?php echo @$rs['SHAPE_AREA'] ;?>" >
            <span id="id_validate"> </span>
        </div>
        <!---------------------------------------الملاحظات------------------------------------------->
        <div class="form-group col-sm-3">
            <label class="control-label">الملاحظات</label>
            <textarea type="text" rows="1" class="form-control" name="NOTES"
                      value="<?php echo @$rs['NOTES'] ;?>"> </textarea>
            <span id="id_validate"></span>
        </div>
        <!-------------------------------------------------->
        <div class="form-group col-sm-3">
            <label class="control-label">المرفقات</label>
            <input type="file" class="form-control" name="PHOTO1_PAT" value="<?php echo @$rs['PHOTO1_PAT'] ;?>" >
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



