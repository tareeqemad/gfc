<?php
/**
 * Created by PhpStorm.
 * User: ljasser
 * Date: 09/02/19
 * Time: 09:23 ص
 */
$MODULE_NAME='gis';
$TB_NAME="Customers_Controller";
$rs=($isCreate)? array() : $Customers_data[0];

$back_url=base_url("$MODULE_NAME/$TB_NAME/index");
$get_url =base_url("$MODULE_NAME/$TB_NAME/get_Customers_info");
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
    <legend>بيانات المشترك الأساسية  </legend>
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
        <!--------------------------رقم المشروع------------------------>
        <div class="form-group col-sm-2">
            <label class="control-label">رقم المشروع</label>
            <input type="text" id="OBJECT_ID_id" name="OBJECT_ID"  class="form-control"
                   value="<?php echo @$rs['OBJECT_ID'] ;?>" >
            <span id="id_validate"> </span>
        </div>
        <!-------------------------------رقم العداد---------------------------->
        <div class="form-group col-sm-2">
            <label class="control-label">رقم العداد</label>
            <input type="text" id="METER_NO_id" name="METER_NO"  class="form-control"
                   value="<?php echo @$rs['METER_NO'] ;?>" >
            <span id="id_validate"> </span>
        </div>
        <!----------------------------------رقم الاشتراك------------------------------->
        <div class="form-group col-sm-2">
            <label class="control-label">رقم الاشتراك</label>
            <input type="text" id="CUSTOMER_SUBSCRIPTION_NO_id" name="CUSTOMER_SUBSCRIPTION_NO"  class="form-control"
                   value="<?php echo @$rs['CUSTOMER_SUBSCRIPTION_NO'] ;?>" >
            <span id="id_validate"> </span>
        </div>
        <br>
        <!--------------------------اسم المشترك----------------------->
        <div class="form-group col-sm-2">
            <label class="control-label">اسم المشترك</label>
            <input type="text" id="CUSTOMER_NAME_id" name="CUSTOMER_NAME"  class="form-control"
                   value="<?php echo @$rs['CUSTOMER_NAME'] ;?>" >
            <span id="id_validate"> </span>
        </div>
        <!---------------------------نوع الاشتراك ---------------------------->
        <div class="form-group col-sm-2">
            <label class="control-label">نوع الاشتراك </label>
            <input type="text" id="SUBSCRIPTION_TYPE_id" name="SUBSCRIPTION_TYPE"  class="form-control"
                   value="<?php echo @$rs['SUBSCRIPTION_TYPE'] ;?>" >
            <span id="id_validate"> </span>
        </div>
        <!------------------------------نوع عداد المشترك--------------------------------->
        <div class="form-group col-sm-2">
            <label class="control-label">نوع عداد المشترك </label>
            <input type="text" id="SUBSCRIPTION_METER_TYPE_id" name="SUBSCRIPTION_METER_TYPE"  class="form-control"
                   value="<?php echo @$rs['SUBSCRIPTION_METER_TYPE'] ;?>" >
            <span id="id_validate"> </span>
        </div>
        <!------------------------نوع تغذية الاشتراك------------------------->
        <div class="form-group col-sm-2">
            <label class="control-label">نوع تغذية الاشتراك</label>
            <input type="text" id="SUBSCRIPTION_FEEDING_TYPE_id" name="SUBSCRIPTION_FEEDING_TYPE"  class="form-control"
                   value="<?php echo @$rs['SUBSCRIPTION_FEEDING_TYPE'] ;?>" >
            <span id="id_validate"> </span>
        </div>
        <!-------------------------------رقم منزل الاشتراك----------------------------->
        <div class="form-group col-sm-2">
            <label class="control-label">رقم منزل الاشتراك</label>
            <input type="text" id="SUBSCRIPTION_BUILDING_NO_id" name="SUBSCRIPTION_BUILDING_NO"  class="form-control"
                   value="<?php echo @$rs['SUBSCRIPTION_BUILDING_NO'] ;?>" >
            <span id="id_validate"> </span>
        </div>
        <!------------------------------وصف عقار الاشتراك------------------------------------>
        <div class="form-group col-sm-2">
            <label class="control-label">وصف عقار الاشتراك</label>
            <input type="text" id="CUSTOMER_PROPERTY_DESCRIPTION_id" name="CUSTOMER_PROPERTY_DESCRIPTION"  class="form-control"
                   value="<?php echo @$rs['CUSTOMER_PROPERTY_DESCRIPTION'] ;?>" >
            <span id="id_validate"> </span>
        </div>
        <!--------------------------------رقم كابل المشترك---------------------------------->
        <div class="form-group col-sm-2">
            <label class="control-label">رقم كابل المشترك</label>
            <input type="text" id="CUSTOMER_CABLE_CODE_id" name="CUSTOMER_CABLE_CODE"  class="form-control"
                   value="<?php echo @$rs['CUSTOMER_CABLE_CODE'] ;?>" >
            <span id="id_validate"> </span>
        </div>
        <!----------------------------حالة المشترك---------------------------->
        <div class="form-group col-sm-2">
            <label class="control-label">حالة المشترك</label>
            <input type="text" id="CUSTOMER_STATUS_id" name="CUSTOMER_STATUS"  class="form-control"
                   value="<?php echo @$rs['CUSTOMER_STATUS'] ;?>" >
            <span id="id_validate"> </span>
        </div>
        <!--------------------------------------------------------------------->



    </div>
</fieldset>
<hr/>
<!---------------------------------------------------------------->
<fieldset>
    <legend>  بيانات اضافية   </legend>
    <div class="row">
        <!------------------------------رقم العمود----------------------------------->
        <div class="form-group col-sm-3">
            <label class="control-label">رقم العمود</label>
            <input type="text" id="POLE_CODE_id" name="POLE_CODE"  class="form-control"
                   value="<?php echo @$rs['POLE_CODE'] ;?>" >
            <span id="id_validate"> </span>
        </div>

        <!---------------------كود سكينة ض.م المغذية ------------------->
        <div class="form-group col-sm-3">
            <label class="control-label">كود سكينة ض.م المغذية </label>
            <input type="text" id=LTL_SWITCH_CODE_id" name="LTL_SWITCH_CODE"  class="form-control"
                   value="<?php echo @$rs['LTL_SWITCH_CODE'] ;?>" >
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

        <!---------------------------------------الملاحظات------------------------------------------->
        <div class="form-group col-sm-3">
            <label class="control-label">الملاحظات</label>
            <textarea type="text" rows="1" class="form-control" name="NOTES"
                      value="<?php echo @$rs['NOTES'] ;?>"> </textarea>
            <span id="id_validate"></span>
        </div>
        <!------------------------فاز الربط------------------------>
        <div class="form-group col-sm-3">
            <label class="control-label"> فاز الربط </label>
            <input type="text" id="SUBSCRIPTION_PHASE_CONNECTION_ID"  name="SUBSCRIPTION_PHASE_CONNECTION"class="form-control"
                   value="<?php echo @$rs['SUBSCRIPTION_PHASE_CONNECTION'] ;?>">
            <span id="id_validate"></span>
        </div>
        <!--------------------------كود المحول--------------------->
        <div class="form-group col-sm-3">
            <label class="control-label">كود المحول  </label>
            <input type="text" id="TRANSFORMER_CODE_ID"  name="TRANSFORMER_CODE"class="form-control"
                   value="<?php echo @$rs['TRANSFORMER_CODE'] ;?>">
            <span id="id_validate"></span>
        </div>
        <!-------------------------------------------------->

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



