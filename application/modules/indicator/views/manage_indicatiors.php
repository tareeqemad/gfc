<?php

/*
 * Created by PhpStorm.
 * User: lanakh
 * Date: 15/09/18
 * Time: 12:26 م
 */
$MODULE_NAME= 'indicator';//folder
$TB_NAME= 'indicatior';//controller
$TB_NAME1='manage_indecatior_info';//controller
$TB_NAME2='indecator_info';//controller
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$create_url =base_url("$MODULE_NAME/$TB_NAME1/create");
$backs_url=base_url("$MODULE_NAME/$TB_NAME1"); //$action
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
$go_to_get_url=base_url("$MODULE_NAME/$TB_NAME2/get_indecator_info");
$adopt_url= base_url("$MODULE_NAME/$TB_NAME1/adopt");
$un_adopt_url= base_url("$MODULE_NAME/$TB_NAME1/unadopt");
$flag_url= base_url("$MODULE_NAME/$TB_NAME1/flag");
$un_flag_url= base_url("$MODULE_NAME/$TB_NAME1/unflag");
$manage_select_exe=(count($rs)>0)?$rs['MANAGE_FOLLOW_ID']:0;
$cycle_select_exe=(count($rs)>0)?$rs['CYCLE_FOLLOW_ID']:0;
$permmition_url=base_url("$MODULE_NAME/$TB_NAME1/permition");
$convert_url=base_url("$MODULE_NAME/$TB_NAME2/update_indecator_info_status");
$class_list=modules::run("$MODULE_NAME/$TB_NAME2/public_class_list_p", (count($rs)>0)?$rs['SECTOR']:-1);
$second_class_list=modules::run("$MODULE_NAME/$TB_NAME2/public_class_list_p", (count($rs)>0)?$rs['CLASS']:-1);
$get_all_sectors =base_url("$MODULE_NAME/$TB_NAME2/public_get_sectors");
 if(count($rs)>0){ if ($rs['ENTERY_VALUE_WAY']==2) $hidden_show_calc=''; else $hidden_show_calc='hidden';  }  else $hidden_show_calc='hidden';
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
<div id="msg_container"> <?php

    if (  HaveAccess($post_url)  && ($isCreate || ( $rs['ADOPT']==1   and  ( (count($rs)>0)? $rs['EFFECT']==2 : '' ))))  : ?>
        <?php
        if(@$rs['HAVE_DATA']!=0){

            ?>
            <div class="alert alert-danger" role="alert">
                تنويه نظرا لانه تم احتساب المعلومة: الحقول المسموح التعديل عليها هي( القطاع , التصنيف الاول , التصنيف الثاني , الترتيب , الحالة , مسؤولية المتابعة , ادارة عرض المعلومة و التقرير)     </div>
        <?php }
        ?>
    <?php endif; ?>
</div>
<div id="container">
<form class="form-vertical" id="<?=$TB_NAME?>_form" method="post" action="<?=$post_url?>" role="form" novalidate="novalidate">
<div class="modal-body inline_form">
<fieldset>
    <!----------------------------------  المعلومة المؤشر  ---------------------------------------------->
    <legend> بيانات المعلومة </legend>
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
                <?php foreach($all_sectors as $row) :?>
                    <option value="<?= $row['ID'] ?>" <?PHP if ($row['ID']==((count($rs)>0)?$rs['SECTOR']:0)){ echo " selected"; } ?>>
                        <?= $row['ID_NAME'] ?></option>
                <?php endforeach; ?>
            </select>
            <span class="field-validation-valid" data-valmsg-for="sector" data-valmsg-replace="true"></span>
        </div>
    </div>
    <!------------------------------------التصنيف-------------------------------------------->
    <div class="form-group col-sm-2">
        <label class="col-sm-1 control-label">التصنيف الاول</label>
        <div>
            <select name="class"   id="dp_class" class="form-control">
                <option></option>
                <?php if (count($rs)>0){?>
                    <?php foreach($class_list as $row) :?>
                        <option value="<?= $row['ID'] ?>" <?PHP if ($row['ID']==((count($rs)>0)?$rs['CLASS']:0)){ echo " selected"; } ?> ><?= $row['ID_NAME'] ?></option>
                    <?php endforeach; ?>
                <?php } ?>
            </select>
            <span class="field-validation-valid" data-valmsg-for="class" data-valmsg-replace="true"></span>
        </div>
    </div>
    <!------------------------------------التصنيف-------------------------------------------->
    <div class="form-group col-sm-2">
        <label class="col-sm-1 control-label">التصنيف الثاني</label>
        <div>
            <select name="second_class"   id="dp_second_class" class="form-control">
                <option></option>
                <?php if (count($rs)>0){?>
                    <?php foreach($second_class_list as $row) :?>
                        <option value="<?= $row['ID'] ?>" <?PHP if ($row['ID']==((count($rs)>0)?$rs['SECOND_CLASS']:0)){ echo " selected"; } ?> ><?= $row['ID_NAME'] ?></option>
                    <?php endforeach; ?>
                <?php } ?>
            </select>
            <span class="field-validation-valid" data-valmsg-for="second_class" data-valmsg-replace="true"></span>
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
        <label class="col-sm-1 control-label">اسم المعلومة</label>
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
    <!---------------------------------- ملاحظات ----------------------------------------->
    <div class="form-group col-sm-10">
        <label class="col-sm-1 control-label">ملاحظات</label>
        <div>
            <input type="text"    name="note" id="txt_note" class="form-control" dir="rtl" value="<?php echo (count($rs)>0)? $rs['NOTE']: '' ;?>" <!---readonly-->
            <span class="field-validation-valid" data-valmsg-for="note" data-valmsg-replace="true"></span>
        </div>
    </div>
</fieldset>
<hr/>
<!-----------------------------------مسؤولية المتابعة------------------------------>
<fieldset>
    <legend>مسئولية المتابعة</legend>
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

<hr/>






<!--------------------------------------------معلومات المحقق من المؤشر-------------------------------------------->

<fieldset>
    <legend>طريقة الاحتساب</legend>
    <!------------------------------------------------طريقة الحساب--------------------------------------------------->
    <div class="form-group col-sm-1">
        <label class="col-sm-1 control-label">طريقة الحساب</label>
        <div>
            <select name="entery_value_way" data-val="true"  data-val-required="حقل مطلوب"  id="dp_entery_value_way" class="form-control">
                <option></option>
                <?php   foreach($entry_target_way as $row) :?>
                    <option value="<?= $row['CON_NO']   ?>" <?PHP if ($row['CON_NO']==((count($rs)>0)?$rs['ENTERY_VALUE_WAY']:0)){ echo " selected"; } ?> ><?= $row['CON_NAME'] ?></option>
                <?php  endforeach; ?>
            </select>
            <span class="field-validation-valid" data-valmsg-for="entery_value_way" data-valmsg-replace="true"></span>
        </div>
    </div>

    <!---------------------------------------طريقة حساب المحقق------------------------------------------->
    <div class="form-group col-sm-5 " >
        <label class="col-sm-4 control-label">طريقة الاحتساب</label>
        <div>
            <input type="text"    name="equation_value" id="txt_equation_value" class="form-control" dir="rtl" value="<?php echo (count($rs)>0)? $rs['EQUATION_VALUE']: '' ;?>" <!---readonly-->
            <span class="field-validation-valid" data-valmsg-for="equation_value" data-valmsg-replace="true"></span>
        </div>
    </div>
</fieldset>
<hr/>

<div <?php echo $hidden_show_calc;?>  id="ver_indecators">

    <fieldset  class="field_set">
        <legend>احتساب المحقق</legend>
        <div>
            <?php echo modules::run("$MODULE_NAME/$TB_NAME1/$DET_TB_NAME" ,(count($rs)>0)?$rs['INDECATOR_SER']:0,((count(@$rs)>0)? @$rs['ADOPT'] : '' )); ?>
        </div>
    </fieldset>

</div>

<fieldset>
    <legend>ادارة عرض المعلومة و التقرير</legend>
    <div class="form-group col-sm-3 info_rep" >
        <label class="col-sm-7 control-label">عرض المعلومة في الصفحة الرئيسية</label>
        <div>
            <select name="display_info" data-val="true"  data-val-required="حقل مطلوب"  id="dp_display_info" class="form-control">
                <?php   foreach($display_rep_info as $row) :?>
                    <option value="<?= $row['CON_NO']   ?>" <?PHP if ($row['CON_NO']==((count($rs)>0)?$rs['DISPLAY_INFO']:0)){ echo " selected"; } ?> ><?= $row['CON_NAME'] ?></option>
                <?php  endforeach; ?>

            </select>
            <span class="field-validation-valid" data-valmsg-for="display_info" data-valmsg-replace="true"></span>
        </div>
    </div>

    <div class="form-group col-sm-2 info_rep">
        <label class="col-sm-1 control-label">التقارير الشهرية</label>
        <div>
            <select name="display_rep" data-val="true"  data-val-required="حقل مطلوب"  id="dp_display_rep" class="form-control">
                <?php   foreach($display_rep_info as $row) :?>
                    <option value="<?= $row['CON_NO']   ?>" <?PHP if ($row['CON_NO']==((count($rs)>0)?$rs['DISPLAY_REP']:0)){ echo " selected"; } ?> ><?= $row['CON_NAME'] ?></option>
                <?php  endforeach; ?>

            </select>
            <span class="field-validation-valid" data-valmsg-for="display_rep" data-valmsg-replace="true"></span>
        </div>
    </div>
</fieldset>
<hr/>
<!-----------------------------------------حفظ البيانات والاعتماد ------------------------------------>
<div class="modal-footer">
    <?php

    if (  HaveAccess($post_url)  && ($isCreate || ( $rs['ADOPT']==1   and  ( (count($rs)>0)? $rs['EFFECT']==2 : '' ))))  : ?>
        <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
    <?php endif; ?>

    <?php if ( HaveAccess($adopt_url) && (!$isCreate and ( (count($rs)>0)? $rs['ADOPT']==1 : '' ) /*and  (count($rs)>0)? $rs['HAVE_TARGET']==0 : ''*/ and( (count($rs)>0)? $rs['EFFECT']==2 : '' ) )  ) : ?>
        <button type="button" id="btn_adopt" onclick='javascript:return_adopt(1);' class="btn btn-success">اعتماد  </button>
    <?php endif; ?>
    <?php if ( HaveAccess($un_adopt_url) && (!$isCreate and ( (count($rs)>0)? $rs['ADOPT']==2 : '' ) /*and  (count($rs)>0)? $rs['HAVE_TARGET']==0 : '' */ and( (count($rs)>0)? $rs['EFFECT']==2 : '' ) )  ) : ?>
        <button type="button"  id="btn_unadopt" onclick="javascript:return_adopt(2);" class="btn btn-danger">الغاء الاعتماد</button>
    <?php endif; ?>
    <?php echo '';/*if ( HaveAccess($flag_url) && (!$isCreate and ( (count($rs)>0)? $rs['ADOPT']==2 : ''  and  (count($rs)>0)? $rs['HAVE_TARGET']>=1 : '' ) and  (count($rs)>0)? $rs['INDECATOR_FLAGE']==2 : '' and( (count($rs)>0)? $rs['EFFECT']==2 : '' ) )  ) : ?>
        <button type="button" id="btn_indector_Flag" name ="btn_indector_Flag"  onclick='javascript:return_flag(1);' class="btn btn-success">تفعيل المعلومة  </button>
    <?php endif;*/ ?>
    <?php echo '';/* if ( HaveAccess($un_flag_url) && (!$isCreate and ( (count($rs)>0)? $rs['ADOPT']==2 : ''  and  (count($rs)>0)? $rs['HAVE_TARGET']>=1 : '' ) and  (count($rs)>0)? $rs['INDECATOR_FLAGE']==1 : '' and( (count($rs)>0)? $rs['EFFECT']==2 : '' ) )  ) : ?>
        <button type="button" id="btn_unindector_Flag" name="btn_unindector_Flag" onclick='javascript:return_flag(2);'    class="btn btn-danger" >الغاء فعالية المعلومة</button>
    <?php endif;*/ ?>
    <?php if ( HaveAccess($convert_url) && (!$isCreate and ( (count($rs)>0)? $rs['ADOPT']==2 : '' ) and  ( (count($rs)>0)? $rs['EFFECT']==2 : '' )) ) : ?>
        <button type="button" id="btn_convert_to_indecatior" name="btn_convert_to_indecatior" onclick='javascript:return_convert_to_indecatior();' class="btn btn-info">استورد المعلومة كمؤشر</button>
    <?php endif; ?>
    <button type="button" id="btn_convert_to_indecatior" name="btn_convert_to_indecatior" onclick='javascript:return_premmission();' class="btn btn-danger hidden">ادارة الصلاحيات</button>
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

function return_convert_to_indecatior()
{

  get_data('{$convert_url}',{id: $('#txt_indecator_ser').val()},function(data){

           if(data =='1')
                             success_msg('رسالة','تم عملية الاستيراد بنجاح ..');
                            get_to_link('{$go_to_get_url}/'+$('#txt_indecator_ser').val());

                    },'html');



}
function return_premmission()
{
_showReport('$permmition_url/'+$('#txt_indecator_ser').val());
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
   $('#dp_indecator_flag').select2();
   $('#dp_unit').select2();
   $('#dp_effect').select2();
   $('#dp_scale').select2();
   $('#dp_period').select2();
   $('#dp_effect_flag').select2();
   $('#dp_is_target').select2();
   $('#dp_entry_target_way').select2();
 //  $('#dp_entery_value_way').select2();
   $('#dp_second_class').select2();
   $('#dp_display_rep').select2();
   $('#dp_display_info').select2();



$('#dp_sector').select2().on('change',function(){

       get_data('{$get_all_sectors}',{no: $('#dp_sector').val()},function(data){
            $('#dp_class').html('');
              $('#dp_class').append('<option></option>');
              $("#dp_class").select2('val','');
            $.each(data,function(index, item)
            {
            $('#dp_class').append('<option value=' + item.ID + '>' + item.ID_NAME + '</option>');
            });
            });
        });

$('#dp_class').select2().on('change',function(){

       get_data('{$get_all_sectors}',{no: $('#dp_class').val()},function(data){
            $('#dp_second_class').html('');
              $('#dp_second_class').append('<option></option>');
              $("#dp_second_class").select2('val','');
            $.each(data,function(index, item)
            {
            $('#dp_second_class').append('<option value=' + item.ID + '>' + item.ID_NAME + '</option>');
            });
            });
        });
$('button[data-action="submit"]').click(function(e){
     e.preventDefault();
     if(confirm('هل تريد الحفظ  ؟!')){
      $(this).attr('disabled','disabled');
      var form = $(this).closest('form');

      ajax_insert_update(form,function(data){

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

});




        $('#dp_entery_value_way').select2().on('change',function(){

			 if($(this).val()=='2'){
			    $('#ver_indecators').show();
			 }else {
			    $('#ver_indecators').hide();
			 }
        });

reBind_pram(0);

        function reBind_pram(cnt){
        $('table tr td .select2-container').remove();
         $('select[name="indecator_ser_val[]"]').select2().on('change',function(){

               });
                 $('select[name="for_month_calc[]"]').select2().on('change',function(){

               });
                                $('select[name="sumarize[]"]').select2().on('change',function(){

               });

       $('select[name="is_value[]"]').select2().on('change',function(){

               });
                    $('select[name="oper1[]"]').select2().on('change',function(){

               });
        $('select[name="oper2[]"]').each(function (i) {
           count[i]=$(this).closest('tr').find('input[name="h_count[]"]').val(i);
            if($(this).val()!=0){
                        $('#new_row').show();
                    } else{
                        $('#new_row').hide();
                    }
        });





                $('select[name="oper2[]"]').select2().on('change',function(){
                    var cnt_tr1=$(this).closest('tr').find('input[name="h_count[]"]').val();
                    if($(this).val()!=0){
                        $('#new_row').show();
                    }else{
                    $('#new_row').hide();
                    }
               });








        }




</script>
SCRIPT;
sec_scripts($scripts);
?>

