<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 04/12/17
 * Time: 11:34 ص
 */



$MODULE_NAME= 'planning';
$TB_NAME= 'strategic_planning';
$DET_TB_NAME2='public_get_stratgic_projects';
$backs_url=base_url("$MODULE_NAME/$TB_NAME"); //$action
$create =base_url("$MODULE_NAME/$TB_NAME/create");
$manage_exe_branch =base_url("$MODULE_NAME/$TB_NAME/public_get_mange");
$cycle_exe_branch =base_url("$MODULE_NAME/$TB_NAME/public_get_cycle");
$dep_exe_branch =base_url("$MODULE_NAME/$TB_NAME/public_get_dep");
$get_all_goal =base_url("$MODULE_NAME/$TB_NAME/public_get_goal");
$post_url= base_url("$MODULE_NAME/$TB_NAME/".($action == 'index'?'create':$action));
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$rs=($isCreate)? array() : $planning_data;
$branch_exe_id= (count($rs)>0)?$rs['BRANCH_EXE_ID']:0;
$objective_id= (count($rs)>0)?$rs['OBJECTIVE']:0;
$stratgic_year=(count($rs)>0)?$rs['YEAR']:date('Y');
$stratgic_from_year=(count($rs)>0)?$rs['FROM_YEAR']:'';
$stratgic_to_year=(count($rs)>0)?$rs['TO_YEAR']:'';
$type_id = (count($rs)>0)?$rs['TYPE']:0;
$type_project_name=(count($rs)>0)?$rs['TYPE_PROJECT']:2;
$manage_select_exe=(count($rs)>0)?$rs['MANAGE_EXE_ID']:0;
$cycle_select_exe=(count($rs)>0)?$rs['CYCLE_EXE_ID']:0;
$cycle_select_follow=(count($rs)>0)?$rs['CYCLE_FOLLOW_ID']:0;
$dep_select_follow=(count($rs)>0)?$rs['DEPARTMENT_FOLLOW_ID']:0;
$dep_select_exe=(count($rs)>0)?$rs['DEPARTMENT_EXE_ID']:0;
$goal_select=(count($rs)>0)?$rs['GOAL']:0;
$goal_t_select=(count($rs)>0)?$rs['GOAL_T']:0;
$goal_select1=(count($rs)>0)?$rs['OBJECTIVE']:-1;
$goal_t_select1=(count($rs)>0)?$rs['GOAL']:-1;
$year_=(count($rs)>0)?$rs['YEAR']:date('Y')+1;
$goal_list=modules::run("$MODULE_NAME/$TB_NAME/public_get_goal_p", $goal_select1,$year_);
$goal_t_list=modules::run("$MODULE_NAME/$TB_NAME/public_get_goal_p", $goal_t_select1,$year_);
$b=modules::run("$MODULE_NAME/$TB_NAME/public_get_mange_b", $branch_exe_id);
$cycle_exe1=(count($rs)>0)?$rs['MANAGE_EXE_ID']:-1;
$cycle_exe=modules::run("$MODULE_NAME/$TB_NAME/public_get_cycle_b", $cycle_exe1);
$dep_exe1=(count($rs)>0)?$rs['CYCLE_EXE_ID']:-1;
$dep_exe=modules::run("$MODULE_NAME/$TB_NAME/public_get_dep_p", $dep_exe1);
$get_url=base_url("$MODULE_NAME/$TB_NAME/get");
$adopt_url = base_url("$MODULE_NAME/$TB_NAME/adopt");
$hasdata=(count($rs)>0)?1:0;
$no_edit=1;
$readonly="";
$hidden="hidden";
if(!$isCreate)
{


    $readonly="readonly";
	$hidden="hidden";




}


$FROM_YEAR=(count($rs)>0)?$rs['FROM_YEAR']:$year_paln[0]['FROM_YEAR'];
$TO_YEAR=(count($rs)>0)?$rs['TO_YEAR']:$year_paln[0]['TO_YEAR'];

$curr_plan=(count($rs)>0)?modules::run("$MODULE_NAME/$TB_NAME/public_get_plan_ser_id",$FROM_YEAR,$TO_YEAR):modules::run("$MODULE_NAME/$TB_NAME/public_get_plan_id",$year_paln[0]['SER']);;
echo AntiForgeryToken();
?>

<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
            <?php  if( HaveAccess($backs_url)):  ?><li><a  href="<?= $backs_url ?>"><i class="icon icon-reply"></i> </a> </li><?php  endif; ?>
           <!-- <?php if( HaveAccess($create_url)):  ?><li><a  href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>اضافة مشروع فني</a> </li><?php endif; ?>-->
            <?php if( HaveAccess($create_url)):  ?><li><a  href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>جديد</a> </li><?php endif; ?>
            <li><a  href="<?=base_url('uploads/statgic_plan_user_manual.pdf')?>" target="_blank"><i class="icon icon-question-circle"></i>دليل المستخدم</a>


        </ul>

    </div>
</div>
<div class="form-body">

<div id="msg_container"></div>
<div id="container">
<form class="form-vertical" id="<?=$TB_NAME?>_form" method="post" action="<?=$post_url?>" role="form" novalidate="novalidate">
<div class="modal-body inline_form">
<fieldset>
    <legend>الخطة</legend>

    <div class="form-group col-sm-1 <?=$hidden;?>">
        <label class="col-sm-2 control-label">عام الخطة</label>
        <div>
	
		
		<select name="year" data-val="true"  data-val-required="حقل مطلوب" id="txt_year"  class="form-control" >
                <?php for($years=$FROM_YEAR; $years<=$TO_YEAR; $years++) {?>
		 	<?php
		if(count($rs)>0)
		{
	if ($years==$stratgic_year)
	{
		?>
                <option value="<?= $years ?>" <?php if ($years==$stratgic_year){ echo " selected"; } ?> ><?= $years ?></option>
<?php }}
else
{
?>
<option value="<?= $years ?>" <?php if ($years==$stratgic_year){ echo " selected"; } ?> ><?= $years ?></option>
                <?php }} ?>
        </select>
	
		
			
            <input type="hidden" data-val="true"    data-val-required="حقل مطلوب" name="seq" id="txt_seq" value="<?php echo (count($rs)>0)? $rs['SEQ']: '' ;?>" class="form-control">

        </div>
    </div>

                               <div class="form-group col-sm-4" >

                    <label class="control-label">الخطة</label>
                    <div>
				        <select  name="dp_plan" id="dp_plan"  data-curr="false"  class="form-control"  >
						
                           <?php foreach($curr_plan as $row) :?>
                                <option value="<?= $row['SER'] ?>">
                                    <?= $row['TITLE'].': '.$row['FROM_YEAR'].' - '.$row['TO_YEAR'] ?>
                                </option>


                            <?php endforeach; ?>
                        </select>

                    </div>
                </div>
  


</fieldset>
<hr/>
<fieldset>
    <legend>المحاور و الأهداف</legend>

   <!-- <div class="form-group col-sm-1 <?=$hidden;?>">
        <label class="col-sm-2 control-label">عام الخطة</label>
        <div>
            <input type="text" data-val="true"  data-val-required="حقل مطلوب" name="year" id="txt_year" class="form-control" dir="rtl" <?= $readonly;?> value="<?php echo (count($rs)>0)? $rs['YEAR']:$year_ ;/*$year_paln;*/?>">
            <span class="field-validation-valid" data-valmsg-for="year" data-valmsg-replace="true"></span>
            <input type="hidden" data-val="true"    data-val-required="حقل مطلوب" name="seq" id="txt_seq" value="<?php echo (count($rs)>0)? $rs['SEQ']: '' ;?>" class="form-control">

        </div>
    </div> -->

    <div class="form-group col-sm-3">
        <label class="col-sm-2 control-label">المحور</label>
        <div>

            <select name="objective" data-val="true"  data-val-required="حقل مطلوب" id="dp_objective"  class="form-control">

                <option></option>
                <?php foreach($all_objective as $row) :?>
                <option value="<?= $row['ID'] ?>" <?php if ($row['ID']==$objective_id){ echo " selected"; } ?> ><?= $row['ID_NAME'] ?></option>

                <?php endforeach; ?>






            </select>

            <span class="field-validation-valid" data-valmsg-for="objective" data-valmsg-replace="true"></span>





        </div>
    </div>


    <div class="form-group col-sm-3">
        <label class="col-sm-6 control-label">الهدف الاستراتيجي(العام)</label>
        <div>

            <select name="goal" data-val="true"  data-val-required="حقل مطلوب"  id="dp_goal" class="form-control">

                <option></option>
                <?php foreach($goal_list as $row) :?>
                    <option value="<?= $row['ID'] ?>" <?php if ($row['ID']==$goal_select){ echo " selected"; } ?> ><?= $row['ID_NAME'] ?></option>
                <?php endforeach; ?>
            </select>

            <span class="field-validation-valid" data-valmsg-for="goal" data-valmsg-replace="true"></span>

        </div>
    </div>


    <div class="form-group col-sm-3">
        <label class="col-sm-6 control-label">الهدف الاستراتيجي(الخاص)</label>
        <div>

            <select name="goal_t" id="dp_goal_t" data-val="true"  data-val-required="حقل مطلوب"  class="form-control">

                <option></option>
                <?php foreach($goal_t_list as $row) :?>
                    <option value="<?= $row['ID'] ?>" <?php if ($row['ID']==$goal_t_select){ echo " selected"; } ?> ><?= $row['ID_NAME'] ?></option>
                <?php endforeach; ?>
            </select>

            <span class="field-validation-valid" data-valmsg-for="goal_t" data-valmsg-replace="true"></span>

        </div>
    </div>



</fieldset>
<hr/>
<fieldset>
    <legend>  بيانات المشروع </legend>

    <div class="form-group col-sm-1 <?=$hidden;?>">
          <label class="col-sm-2 control-label">رقم المشروع</label>
        <div>
            <input type="text"    name="activity_no" id="txt_activity_no" class="form-control" dir="rtl" value="<?php echo (count($rs)>0)? $rs['ACTIVITY_NO']: '' ;?>" readonly>
            <span class="field-validation-valid" data-valmsg-for="activity_no" data-valmsg-replace="true"></span>
            <input type="hidden"  name="project_id" class="form-control" dir="rtl" value="">
        </div>
    </div>



    <div class="form-group col-sm-1">
               <label class="col-sm-2 control-label">طبيعة المشروع</label>

        <div>

            <select name="type_project" data-val="true"  data-val-required="حقل مطلوب"  id="dp_type_project" class="form-control" >
                <!--<option></option>-->
                <?php foreach($type_project as $row) :?>
                    <?php  if ($row['CON_NO'] == $type_project_name){?>
                    <option value="<?= $row['CON_NO'] ?>" <?PHP if ($row['CON_NO']==$type_project_name){ echo " selected"; } ?> ><?= $row['CON_NAME'] ?></option>
<?php }?>
                <?php endforeach; ?>

            </select>
            <span class="field-validation-valid" data-valmsg-for="class_name" data-valmsg-replace="true"></span>



        </div>
    </div>


    <div class="form-group col-sm-1">
       <label class="col-sm-2 control-label">نوع المشروع</label>
        <div>

            <select name="type" data-val="true"  data-val-required="حقل مطلوب"  id="dp_type" class="form-control">
                <!-- <option></option> -->
                <?php foreach($activity_type as $row) :?>
				<?php if($row['CON_NO']==1){?>
                    <option value="<?= $row['CON_NO'] ?>" <?PHP if ($row['CON_NO']==$type_id){ echo " selected"; } ?> ><?= $row['CON_NAME'] ?></option>
<?php }?>
                <?php endforeach; ?>

            </select>
            <span class="field-validation-valid" data-valmsg-for="type" data-valmsg-replace="true"></span>



        </div>
    </div>

    <div class="form-group col-sm-2">
         <label class="col-sm-2 control-label">اسم المشروع</label>
        <div>
            <input type="text" data-val="true"  data-val-required="حقل مطلوب" name="activity_name1" id="txt_activity_name" class="form-control" dir="rtl" value="<?php echo (count($rs)>0)? $rs['ACTIVITY_NAME']: '' ;?>" >
            <span class="field-validation-valid" data-valmsg-for="activity_name" data-valmsg-replace="true"></span>
        </div>
    </div>

    <div class="form-group col-sm-1">
        
		 <label class="control-label">إجمالي تكلفة المشروع</label>
        <div>
            <input  data-val="true"  data-val-required="حقل مطلوب"  class="form-control"  name="stratgic_total_price" id="stratgic_total_price_id" value="<?php echo (count($rs)>0)? $rs['STRATGIC_TOTAL_PRICE']:0 ;?>" />
            <span class="field-validation-valid" data-valmsg-for="stratgic_total_price" data-valmsg-replace="true"></span>
        </div>
    </div>
	

    <div class="form-group col-sm-1">
        <label class="control-label">إجمالي ايراد المشروع</label>
        <div>
            <input  data-val="true"  data-val-required="حقل مطلوب"  class="form-control"  name="stratgic_income" id="stratgic_income" value="<?php echo (count($rs)>0)? $rs['STRATGIC_INCOME']:0 ;?>" />
            <span class="field-validation-valid" data-valmsg-for="stratgic_income" data-valmsg-replace="true"></span>
        </div>
    </div>
	

</fieldset>
<hr/>
<fieldset>
    <legend>جهة التنفيذ الرئيسية</legend>

    <div class="form-group col-sm-2">
        <label class="col-sm-2 control-label">الفرع</label>
        <div>
            <select name="branch_exe_id" data-val="true"  data-val-required="حقل مطلوب"  id="dp_branch_exe_id" class="form-control">
                <option></option>
                <?php foreach($branches as $row) :?>
                <?php if($no_edit==2){
                    if($row['NO']==$branch_exe_id){?>
                        <option value="<?= $row['NO'] ?>" <?PHP if ($row['NO']==$branch_exe_id){ echo " selected"; } ?> ><?= $row['NAME'] ?></option>
                    <?php }}
                else
                {?>
                    <option value="<?= $row['NO'] ?>" <?PHP if ($row['NO']==$branch_exe_id){ echo " selected"; } ?> ><?= $row['NAME'] ?></option>
                <?php }?>






                <?php endforeach; ?>

            </select>

            <span class="field-validation-valid" data-valmsg-for="branch_exe_id" data-valmsg-replace="true"></span>




        </div>
    </div>

    <div class="form-group col-sm-2">
        <label class="col-sm-2 control-label">الادارة</label>
        <div>

            <select name="manage_exe_id" data-val="true"  data-val-required="حقل مطلوب"  id="dp_manage_exe_id" class="form-control">
                <option></option>
                <?php foreach($b as $row) :?>
                    <option value="<?= $row['ST_ID'] ?>" <?PHP if ($row['ST_ID']==$manage_select_exe){ echo " selected"; } ?>><?= $row['ST_NAME'] ?></option>


                <?php endforeach; ?>


            </select>

            <span class="field-validation-valid" data-valmsg-for="manage_exe_id" data-valmsg-replace="true"></span>

        </div>
    </div>

    <div class="form-group col-sm-2">
        <label class="col-sm-2 control-label">الدائرة</label>
        <div>

            <select name="cycle_exe_id" id="dp_cycle_exe_id" class="form-control">

                <option></option>
                <?php foreach($cycle_exe as $row) :?>
                    <option value="<?= $row['ST_ID'] ?>" <?PHP if ($row['ST_ID']==$cycle_select_exe){ echo " selected"; } ?>><?= $row['ST_NAME'] ?></option>


                <?php endforeach; ?>
            </select>


        </div>
    </div>

    <!--

     <div class="form-group col-sm-2">
        <label class="col-sm-2 control-label">الدائرة</label>
        <div>

            <select name="cycle_exe_id" data-val="true"  data-val-required="حقل مطلوب"  id="dp_cycle_exe_id" class="form-control">

                <option></option>
            </select>
            <span class="field-validation-valid" data-valmsg-for="cycle_exe_id" data-valmsg-replace="true"></span>


        </div>
    </div>

    -->

    <div class="form-group col-sm-2">
        <label class="col-sm-2 control-label">القسم</label>
        <div>

            <select name="department_exe_id" id="dp_department_exe_id" class="form-control">

                <option></option>
                <?php foreach($dep_exe as $row) :?>
                    <option value="<?= $row['ST_ID'] ?>" <?PHP if ($row['ST_ID']==$dep_select_exe){ echo " selected"; } ?>><?= $row['ST_NAME'] ?></option>


                <?php endforeach; ?>
            </select>

        </div>
    </div>

    <!--

     <div class="form-group col-sm-2">
        <label class="col-sm-2 control-label">القسم</label>
        <div>

            <select name="department_exe_id" data-val="true"  data-val-required="حقل مطلوب"  id="dp_department_exe_id" class="form-control">

             <option></option>
            </select>

            <span class="field-validation-valid" data-valmsg-for="department_exe_id" data-valmsg-replace="true"></span>

        </div>
    </div>

    -->
</fieldset>

<hr/>
<fieldset>

    <!----------------------------------  ملاحظات المعتمد ---------------------------------------------->
    <legend>ملاحظات</legend>
    <div class="form-group col-sm-11">
        <label class="control-label">ملاحظات</label>
        <div>
            <input type="text" data-val="false"  data-val-required="حقل مطلوب"  name="notes" value="<?php echo (count($rs)>0)? $rs['NOTES']: '' ;?>" id="txt_notes" class="form-control valid">
            <span class="field-validation-valid" data-valmsg-for="notes" data-valmsg-replace="false"></span>
        </div>
    </div>

</fieldset>

<hr/>
<fieldset>
    <legend>مدة تنفيذ الخطة على مدار الخطة الإستراتيجية</legend>

    <div class="form-group col-sm-1">
        <label class="col-sm-2 control-label">من عام</label>
        <div>
		
		
		<select name="from_year" data-val="true"  data-val-required="حقل مطلوب" id="txt_from_year"  class="form-control">
                <?php for($years=$FROM_YEAR; $years<=$TO_YEAR; $years++) {?>
		 	<?php
		if(count($rs)>0)
		{
	if ($years==$stratgic_from_year)
	{
		?>
                             <option value="<?= $years ?>" <?php if ($years==$stratgic_from_year){ echo " selected"; } ?> ><?= $years ?></option>

<?php }}
else
{
?>
                <option value="<?= $years ?>" <?php if ($years==$stratgic_from_year){ echo " selected"; } ?> ><?= $years ?></option>

                <?php }} ?>
        </select>
		
		
		
            <span class="field-validation-valid" data-valmsg-for="from_year" data-valmsg-replace="true"></span>

        </div>
    </div>

        <div class="form-group col-sm-1">
        <label class="col-sm-2 control-label">إلى عام</label>
        <div>
<select name="to_year" data-val="true"  data-val-required="حقل مطلوب" id="txt_to_year"  class="form-control">
                <?php for($years=$FROM_YEAR; $years<=$TO_YEAR; $years++) {?>
		 	<?php
		if(count($rs)>0)
		{
	if ($years==$stratgic_to_year)
	{
		?>
                           <option value="<?= $years ?>" <?php if ($years==$stratgic_to_year){ echo " selected"; } ?> ><?= $years ?></option>

<?php }}
else
{
?>
                 <option value="<?= $years ?>" <?php if ($years==$stratgic_to_year){ echo " selected"; } ?> ><?= $years ?></option>

                <?php }} ?>
        </select>
				
		
            <span class="field-validation-valid" data-valmsg-for="to_year" data-valmsg-replace="true"></span>

        </div>
    </div>

</fieldset>

<?php if(!$isCreate){ ?>
<hr>
<fieldset>
    <legend>المشاريع الإستراتيجية</legend>

    <div   class="details" >
        <?php echo modules::run("$MODULE_NAME/$TB_NAME/$DET_TB_NAME2", (count($rs)>0)?$rs['STRATGIC_SER']:0 );?>
    </div>

</fieldset>
<?php } ?>
<?php if(!$isCreate){ ?>
    <hr>
    <div style="clear: both;">
        <span id="quote"><?=modules::run('attachments/attachment/index',$rs['SEQ'],'ACTIVITIES_PLAN');?></span>
    </div>
<?php } ?>
<div class="modal-footer">
    <?php
    if (  HaveAccess($post_url)  && ($isCreate || ( $rs['ADOPT']==1 ) )  ) : ?>
        <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
    <?php endif; ?>
    <!-- <?php if ( HaveAccess($adopt_url) && (!$isCreate and ( (count($rs)>0)? $rs['ADOPT']==1 : '' ))) : ?>
        <button type="button" id="btn_adopt" onclick='javascript:return_adopt(1);' class="btn btn-success">اعتماد  </button>
    <?php  endif; ?> -->


</div>

</div>


</form>
</div>


<?php
$scripts = <<<SCRIPT
<script type="text/javascript">
var count=[];
var exe_time =0;
var array=[];


$('#dp_type_project').select2().on('change',function(){

       //  checkBoxChanged();

        });
$('#dp_type').select2().on('change',function(){

        });
  $('#dp_plan').select2().on('change',function(){
  

        });
		  $('#txt_year,#txt_from_year,#txt_to_year').select2().on('change',function(){
  

        });
		
        
         $('#dp_objective').select2().on('change',function(){

     //  checkBoxChanged();
             get_data('{$get_all_goal}',{no: $(this).val(),year:$('#txt_year').val()},function(data){

            $('#dp_goal').html('');
             $('#dp_goal').append('<option></option>');
             $("#dp_goal").select2('val','');
             $("#dp_goal_t").select2('val','');
            $.each(data,function(index, item)
            {
            $('#dp_goal').append('<option value=' + item.ID + '>' + item.ID_NAME + '</option>');
            });
            });

        });

           $('#dp_goal').select2().on('change',function(){

       //  checkBoxChanged();
          get_data('{$get_all_goal}',{no: $(this).val(),year:$('#txt_year').val()},function(data){
            $('#dp_goal_t').html('');
             $('#dp_goal_t').append('<option></option>');
             $("#dp_goal_t").select2('val','');
            $.each(data,function(index, item)
            {
            $('#dp_goal_t').append('<option value=' + item.ID + '>' + item.ID_NAME + '</option>');
            });
            });

        });


  $('#dp_goal_t').select2().on('change',function(){

       //  checkBoxChanged();

        });


$('#dp_manage_exe_id').select2().on('change',function(){

       //  checkBoxChanged();
        //  checkBoxChanged();
             get_data('{$cycle_exe_branch}',{no: $(this).val()},function(data){
            $('#dp_cycle_exe_id').html('');
              $('#dp_cycle_exe_id').append('<option></option>');
              $("#dp_cycle_exe_id").select2('val','');
             $("#dp_department_exe_id").select2('val','');
            $.each(data,function(index, item)
            {
            $('#dp_cycle_exe_id').append('<option value=' + item.ST_ID + '>' + item.ST_NAME + '</option>');
            });
            });

        });


         $('#dp_cycle_exe_id').select2().on('change',function(){

       //  checkBoxChanged();
        //  checkBoxChanged();

       //  checkBoxChanged();
             get_data('{$dep_exe_branch}',{no: $(this).val()},function(data){
           $('#dp_department_exe_id').html('');
           $('#dp_department_exe_id').append('<option></option>');
            $("#dp_department_exe_id").select2('val','');

            $.each(data,function(index, item)
            {
            $('#dp_department_exe_id').append('<option value=' + item.ST_ID + '>' + item.ST_NAME + '</option>');
            });
            });

        });

         $('#dp_department_exe_id').select2().on('change',function(){

       //  checkBoxChanged();



        });



        $('#dp_branch_exe_id').select2().on('change',function(){

          get_data('{$manage_exe_branch}',{no: $(this).val()},function(data){
            $('#dp_manage_exe_id').html('');
              $('#dp_manage_exe_id').append('<option></option>');
              $("#dp_manage_exe_id").select2('val','');
                 $("#dp_cycle_exe_id").select2('val','');
                 $("#dp_department_exe_id").select2('val','');
            $.each(data,function(index, item)
            {
            $('#dp_manage_exe_id').append('<option value=' + item.ST_ID + '>' + item.ST_NAME + '</option>');
            });
            });

        });
 $('button[data-action="submit"]').click(function(e){
 var flag=1;
        e.preventDefault();
        if(confirm('هل تريد الحفظ  ؟!')){
		if({$hasdata})
		{
		if($('#total_price_calc').text()!=$('#stratgic_total_price_id').val())
		{
		 danger_msg(' لا يمكن الحفظ يحب ان يكون اجمالي تكلفة المشروع مساوي لاجمالي التكلفة السنوية!!','');
		flag=0;
		}
		
		if($('#total_incomes_calc').text()!=$('#stratgic_income').val())
		{
		 danger_msg(' لا يمكن الحفظ يحب ان يكون اجمالي إيراد المشروع مساوي لاجمالي الايراد السنوي!!','');
		flag=0;
		}
		}
		if(flag ==1)
		{
		
            $(this).attr('disabled','disabled');
            var form = $(this).closest('form');
            ajax_insert_update(form,function(data){

            console.log(data);
                if(parseInt(data)>1){
                   success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                   $('button[data-action="submit"]').remove();
                   //console.log(array);
              get_to_link('{$get_url}/'+parseInt(data));
                }else{
                    danger_msg('تحذير..',data);
                }
            },'html');
        }
        setTimeout(function() {
           // $('button[data-action="submit"]').removeAttr('disabled');
           $('button[data-action="submit"]').remove();
        }, 3000);
		}

});



price_calcall();
income_calcall();

reBind_pram(0);

function price_calcall() {

    var total_price_calc = 0;

    $('input[name="stratigic_price_det[]"]').each(function () {

        var stratigic_price_det = $(this).closest('tr').find('input[name="stratigic_price_det[]"]').val();
        total_price_calc += Number(stratigic_price_det);
        if(Number(total_price_calc)>$('#stratgic_total_price_id').val())
                {
                    danger_msg('لقد تجاوزت الحد المسموح');
                    $(this).closest('tr').find('input[name="stratigic_price_det[]"]').val(0);
                }
                else

        $('#total_price_calc').text(isNaNVal(Number(total_price_calc)));
    });



}
//////////////////////////
function income_calcall() {

    var total_incomes_calc = 0;

    $('input[name="stratigic_income_det[]"]').each(function () {

        var stratigic_income_det = $(this).closest('tr').find('input[name="stratigic_income_det[]"]').val();
        total_incomes_calc += Number(stratigic_income_det);
        if(Number(total_incomes_calc)>$('#stratgic_income').val())
                {
                    danger_msg('لقد تجاوزت الحد المسموح');
                    $(this).closest('tr').find('input[name="stratigic_income_det[]"]').val(0);
                }
                else

        $('#total_incomes_calc').text(isNaNVal(Number(total_incomes_calc)));
    });



}
////////////////////
function reBind_pram(cnt){

	
	 $('input[name="stratigic_price_det[]"]').keyup(function (e) {

//e.preventDefault();


  price_calcall();
    });
	
	 $('input[name="stratigic_income_det[]"]').keyup(function (e) {

//e.preventDefault();


  income_calcall();
    });
	
















 
}


</script>

SCRIPT;

sec_scripts($scripts);

?>

