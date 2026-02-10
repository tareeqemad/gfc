<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 04/12/17
 * Time: 11:34 ص
 */



$MODULE_NAME= 'planning';
$TB_NAME= 'planning';
$DET_TB_NAME='public_get_details';
$ACHIVE_TB_NAME='public_get_achive';
$backs_url=base_url("$MODULE_NAME/$TB_NAME"); //$action
$create_without_cost =base_url("$MODULE_NAME/$TB_NAME/create_without_cost");
$manage_follow_branch =base_url("$MODULE_NAME/$TB_NAME/public_get_mange");
$manage_exe_branch =base_url("$MODULE_NAME/$TB_NAME/public_get_mange");
$cycle_follow_branch =base_url("$MODULE_NAME/$TB_NAME/public_get_cycle");
$cycle_exe_branch =base_url("$MODULE_NAME/$TB_NAME/public_get_cycle");
$dep_follow_branch =base_url("$MODULE_NAME/$TB_NAME/public_get_dep");
$dep_exe_branch =base_url("$MODULE_NAME/$TB_NAME/public_get_dep");
$get_all_goal =base_url("$MODULE_NAME/$TB_NAME/public_get_goal");
$delete_url_details = base_url("$MODULE_NAME/$TB_NAME/delete_details");
$post_url= base_url("$MODULE_NAME/$TB_NAME/".($action == 'index'?'create_without_cost':$action));
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$create_without_cost_url=base_url("$MODULE_NAME/$TB_NAME/create_without_cost");
$rs=($isCreate)? array() : $planning_data;
$achive=count($achive_data);
$branch_follow_id= (count($rs)>0)?$rs['BRANCH_FOLLOW_ID']:0;
$branch_exe_id= (count($rs)>0)?$rs['BRANCH_EXE_ID']:0;
$cycle_follow_branch1= (count($rs)>0)?$rs['CYCLE_FOLLOW_ID']:0;
//$cycle_follow_branch1= (count($rs)>0)?$rs['CYCLE_FOLLOW_ID']:0;
$objective_id= (count($rs)>0)?$rs['OBJECTIVE']:0;
$type_id = (count($rs)>0)?$rs['TYPE']:0;
$fin_name = (count($rs)>0)?$rs['FINANCE_NAME']:'';
$month_id= (count($rs)>0)?$rs['FROM_MONTH']:01;

$manage_select_follow=(count($rs)>0)?$rs['MANAGE_FOLLOW_ID']:0;
$manage_select_exe=(count($rs)>0)?$rs['MANAGE_EXE_ID']:0;
$cycle_select_exe=(count($rs)>0)?$rs['CYCLE_EXE_ID']:0;
$cycle_select_follow=(count($rs)>0)?$rs['CYCLE_FOLLOW_ID']:0;
$dep_select_follow=(count($rs)>0)?$rs['DEPARTMENT_FOLLOW_ID']:0;
$dep_select_exe=(count($rs)>0)?$rs['DEPARTMENT_EXE_ID']:0;
$goal_select=(count($rs)>0)?$rs['GOAL']:0;
$goal_t_select=(count($rs)>0)?$rs['GOAL_T']:0;
$goal_select1=(count($rs)>0)?$rs['OBJECTIVE']:-1;
$goal_t_select1=(count($rs)>0)?$rs['GOAL']:-1;
$goal_list=modules::run("$MODULE_NAME/$TB_NAME/public_get_goal_p", $goal_select1);
$goal_t_list=modules::run("$MODULE_NAME/$TB_NAME/public_get_goal_p", $goal_t_select1);
 $b=modules::run("$MODULE_NAME/$TB_NAME/public_get_mange_b", $branch_exe_id);
$b_follow=modules::run("$MODULE_NAME/$TB_NAME/public_get_mange_b", $branch_follow_id);


$cycle_follow1=(count($rs)>0)?$rs['MANAGE_FOLLOW_ID']:-1;
$cycle_follow=modules::run("$MODULE_NAME/$TB_NAME/public_get_cycle_b", $cycle_follow1);

$cycle_exe1=(count($rs)>0)?$rs['MANAGE_EXE_ID']:-1;
$cycle_exe=modules::run("$MODULE_NAME/$TB_NAME/public_get_cycle_b", $cycle_exe1);

$dep_follow1=(count($rs)>0)?$rs['CYCLE_FOLLOW_ID']:-1;

$dep_follow=modules::run("$MODULE_NAME/$TB_NAME/public_get_dep_p", $dep_follow1);

$dep_exe1=(count($rs)>0)?$rs['CYCLE_EXE_ID']:-1;
$dep_exe=modules::run("$MODULE_NAME/$TB_NAME/public_get_dep_p", $dep_exe1);

$get_url=base_url("$MODULE_NAME/$TB_NAME/get_without_cost");
$adopt_url = base_url("$MODULE_NAME/$TB_NAME/adopt");
$fid = (count($rs) > 0) ? $rs['SEQ'] : 0;
$back_budget_tech_url=base_url("budget/Projects/archive");
//echo $month_id.'kk';

//echo $project_info['MONTH'];
//ACTIVE_TYPE_NAME
//var_dump($b);



echo AntiForgeryToken();
?>

<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
            <?php  if( HaveAccess($backs_url)):  ?><li><a  href="<?= $backs_url ?>"><i class="icon icon-reply"></i> </a> </li><?php  endif; ?>
            <?php if( HaveAccess($create_url)):  ?><li><a  href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>اضافة مشروع فني</a> </li><?php endif; ?>
            <?php if( HaveAccess($create_without_cost_url)):  ?><li><a  href="<?= $create_without_cost_url ?>"><i class="glyphicon glyphicon-plus"></i>اضافة مشروع بدون تكلفة</a> </li><?php endif; ?>
             <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>


        </ul>

    </div>
</div>
<div class="form-body">

<div id="msg_container"></div>
<div id="container">
<form class="form-vertical" id="<?=$TB_NAME?>_form" method="post" action="<?=$post_url?>" role="form" novalidate="novalidate">
<div class="modal-body inline_form">

<fieldset>
    <legend>  بيانات المشروع </legend>

    <div class="form-group col-sm-1">
        <label class="col-sm-2 control-label">رقم النشاط</label>
        <div>
            <input type="text"    name="activity_no" id="txt_activity_no" class="form-control" dir="rtl" value="<?php echo (count($rs)>0)? $rs['ACTIVITY_NO']: '' ;?>" readonly>
            <span class="field-validation-valid" data-valmsg-for="activity_no" data-valmsg-replace="true"></span>

        </div>
    </div>


    <!--

     <div class="form-group col-sm-1">
        <label class="col-sm-2 control-label">رقم النشاط</label>
        <div>
            <input type="text" data-val="true"  data-val-required="حقل مطلوب"  name="activity_no" id="txt_activity_no" class="form-control" dir="rtl" value="<?php echo (count($rs)>0)? $rs['ACTIVITY_NO']: '' ;?>" readonly>
            <span class="field-validation-valid" data-valmsg-for="activity_no" data-valmsg-replace="true"></span>

        </div>
    </div>


    -->
    <div class="form-group col-sm-1">
        <label class="col-sm-2 control-label">تعريف النشاط لعام</label>
        <div>
            <input type="text" data-val="true"  data-val-required="حقل مطلوب" name="year" id="txt_year" class="form-control" dir="rtl" value="<?php echo (count($rs)>0)? $rs['YEAR']: /*date('Y')+1 */$year_paln;?>" readonly>
            <span class="field-validation-valid" data-valmsg-for="year" data-valmsg-replace="true"></span>
            <input type="hidden" data-val="true"    data-val-required="حقل مطلوب" name="seq" id="txt_seq" value="<?php echo (count($rs)>0)? $rs['SEQ']: '' ;?>" class="form-control">

        </div>
    </div>
<!--
    <div class="form-group col-sm-1">
        <label class="col-sm-2 control-label">تصنيف النشاط</label>
        <div>
            <input readonly data-val="true"  data-val-required="حقل مطلوب"  class="form-control"  name="class_name" id="class_name_id" value="<?php echo (count($rs)>0)? $rs['ACTIVE_CLASS_NAME']:'' ;?>" />
            <input type="hidden" data-val="true"  data-val-required="حقل مطلوب" name="class" id="txt_class" class="form-control" dir="rtl" value="<?php echo (count($rs)>0)? $rs['CLASS']: '' ;?>" >
            <span class="field-validation-valid" data-valmsg-for="class" data-valmsg-replace="true"></span>
        </div>
    </div>

    -->


    <div class="form-group col-sm-1">
        <label class="col-sm-2 control-label">نوع النشاط</label>
        <div>

            <select name="class_name" data-val="true"  data-val-required="حقل مطلوب"  id="dp_class_name" class="form-control" disabled>
                <option></option>
                <?php foreach($activity_class as $row) :?>
                    <option value="<?= $row['CON_NO'] ?>" <?PHP if ($row['CON_NO']==3){ echo " selected"; } ?> ><?= $row['CON_NAME'] ?></option>

                 <?php endforeach; ?>

            </select>
            <span class="field-validation-valid" data-valmsg-for="class_name" data-valmsg-replace="true"></span>



        </div>
    </div>


  
  <!--  <div class="form-group col-sm-3">
        <label class="col-sm-2 control-label">اسم المشروع</label>
        <div>
            <input readonly data-val="true"  data-val-required="حقل مطلوب"  class="form-control"  name="project_name" id="project_name_id" value="<?php echo (count($rs)>0)? $rs['PROJECT_NAME']: '' ;?>" />
            <input type="hidden" data-val="true"  data-val-required="حقل مطلوب" name="project_id" id="txt_project_id" class="form-control" dir="rtl" value="<?php echo (count($rs)>0)? $rs['PROJECT_ID']: '' ;?>" >
            <span class="field-validation-valid" data-valmsg-for="project_id" data-valmsg-replace="true"></span>
        </div>
    </div>

    -->

    <div class="form-group col-sm-2">
        <label class="col-sm-2 control-label">اسم النشاط</label>
        <div>
            <input type="text" data-val="true"  data-val-required="حقل مطلوب" name="activity_name" id="txt_activity_name" class="form-control" dir="rtl" value="<?php echo (count($rs)>0)? $rs['ACTIVITY_NAME']: '' ;?>" >
            <span class="field-validation-valid" data-valmsg-for="activity_name" data-valmsg-replace="true"></span>
        </div>
    </div>

    <div class="form-group col-sm-1">
        <label class="control-label">تكلفة المشروع</label>
        <div>
            <input readonly data-val="true"  data-val-required="حقل مطلوب"  class="form-control"  name="total_price" id="total_price_id" value="<?php echo (count($rs)>0)? 0:0 ;?>" />
            <span class="field-validation-valid" data-valmsg-for="total_price" data-valmsg-replace="true"></span>
        </div>
    </div>



    <div class="form-group col-sm-1">
        <label class="col-sm-2 control-label">نوع النشاط</label>
        <div>

            <select name="type" data-val="true"  data-val-required="حقل مطلوب"  id="dp_type" class="form-control">
                <option></option>
                <?php foreach($activity_type as $row) :?>
                    <option value="<?= $row['CON_NO'] ?>" <?PHP if ($row['CON_NO']==$type_id){ echo " selected"; } ?> ><?= $row['CON_NAME'] ?></option>

                <?php endforeach; ?>

            </select>
            <span class="field-validation-valid" data-valmsg-for="type" data-valmsg-replace="true"></span>



        </div>
    </div>





</fieldset><hr/>
 
<hr/>
<fieldset>
    <legend>الاهداف و الغايات</legend>

    <div class="form-group col-sm-2">
        <label class="col-sm-2 control-label"> الغاية  </label>
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


    <div class="form-group col-sm-2">
        <label class="col-sm-2 control-label">الهدف الاستراتيجي</label>
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


    <div class="form-group col-sm-2">
        <label class="col-sm-2 control-label">الهدف التشغيلي</label>
        <div>

            <select name="goal_t"  id="dp_goal_t" class="form-control">

                <option></option>
                <?php foreach($goal_t_list as $row) :?>
                    <option value="<?= $row['ID'] ?>" <?php if ($row['ID']==$goal_t_select){ echo " selected"; } ?> ><?= $row['ID_NAME'] ?></option>
                <?php endforeach; ?>
            </select>



        </div>
    </div>

    <!--

     <div class="form-group col-sm-2">
        <label class="col-sm-2 control-label">الهدف التشغيلي</label>
        <div>

            <select name="goal_t" data-val="true"  data-val-required="حقل مطلوب"  id="dp_goal_t" class="form-control">

                <option></option>
                <?php foreach($goal_t_list as $row) :?>
                    <option value="<?= $row['ID'] ?>" <?php if ($row['ID']==$goal_t_select){ echo " selected"; } ?> ><?= $row['ID_NAME'] ?></option>
                <?php endforeach; ?>
            </select>

            <span class="field-validation-valid" data-valmsg-for="goal_t" data-valmsg-replace="true"></span>

        </div>
    </div>

    -->

</fieldset>
<hr/>
<hr/>
<fieldset class="hidden">
    <legend> مصدر التمويل </legend>


    <div class="form-group col-sm-1">
        <label class="col-sm-2 control-label">مصدر التمويل</label>
        <div>
            <input readonly data-val="true"  data-val-required="حقل مطلوب"  class="form-control"  name="finance_name2" id="finance_name2_id" value="<?php echo (count($rs)>0)? $rs['PROJECT_TYPE_DON_NAME']:'' ;?>" />
            <input type="hidden" data-val="true"  data-val-required="حقل مطلوب" name="finance" id="txt_finance" class="form-control" dir="rtl" value="<?php echo (count($rs)>0)? $rs['FINANCE']:'' ;?>" >
            <span class="field-validation-valid" data-valmsg-for="finance" data-valmsg-replace="true"></span>
        </div>
    </div>

    <!--

    <div class="form-group col-sm-1">
        <label class="col-sm-2 control-label">مصدر التمويل</label>
        <div>

            <select name="finance" data-val="true"  data-val-required="حقل مطلوب"  id="dp_finance" class="form-control">
                <option></option>
                <?php foreach($finance_type as $row) :?>
                    <option value="<?= $row['CON_NO'] ?>" <?PHP if ($row['CON_NO']==$fin_id){ echo " selected"; } ?> ><?= $row['CON_NAME'] ?></option>

                <?php endforeach; ?>

            </select>
            <span class="field-validation-valid" data-valmsg-for="finance" data-valmsg-replace="true"></span>



        </div>
    </div>

    -->

    <div class="form-group col-sm-3">
        <label class="control-label">التصنيف</label>
        <div>
            <input readonly data-val="true"  data-val-required="حقل مطلوب" class="form-control"  name="finance_class" id="finance_class_id" value="<?php echo (count($rs)>0)? $rs['PROJECT_TEC_TYPE_NAME']: '' ;?>" />
            <span class="field-validation-valid" data-valmsg-for="finance_class" data-valmsg-replace="true"></span>
        </div>
    </div>

    <div class="form-group col-sm-3">
        <label class="control-label">اسم مصدر التمويل</label>
        <div>
            <input  data-val="true"  data-val-required="حقل مطلوب" class="form-control"  name="finance_name" id="finance_name_id" value="<?php echo (count($rs)>0)? $rs['FINANCE_NAME']: $fin_name ;?>" />
            <span class="field-validation-valid" data-valmsg-for="finance_name" data-valmsg-replace="true"></span>
        </div>
    </div>

</fieldset>
<hr/>
<fieldset>
    <legend>مسئولية المتابعة</legend>
    <div class="form-group col-sm-2">
        <label class="col-sm-2 control-label">الفرع</label>
        <div>

            <select name="branch_follow_id" data-val="true"  data-val-required="حقل مطلوب"  id="dp_branch_follow_id" class="form-control">
                <option></option>
                <?php foreach($branches_follow as $row) :?>
                    <option value="<?= $row['NO'] ?>" <?PHP if ($row['NO']==$branch_follow_id){ echo " selected"; } ?> ><?= $row['NAME'] ?></option>


                <?php endforeach; ?>

            </select>
            <span class="field-validation-valid" data-valmsg-for="branch_follow_id" data-valmsg-replace="true"></span>


        </div>
    </div>

    <div class="form-group col-sm-2">
        <label class="col-sm-2 control-label">الادارة</label>
        <div>

            <select name="manage_follow_id" data-val="true"  data-val-required="حقل مطلوب"  id="dp_manage_follow_id" class="form-control">
                <option></option>
                <?php foreach($b_follow as $row) :?>
                    <option value="<?= $row['ST_ID'] ?>" <?PHP if ($row['ST_ID']==$manage_select_follow){ echo " selected"; } ?>><?= $row['ST_NAME'] ?></option>


                <?php endforeach; ?>


            </select>
            <span class="field-validation-valid" data-valmsg-for="manage_follow_id" data-valmsg-replace="true"></span>


        </div>
    </div>

    <div class="form-group col-sm-2">
        <label class="col-sm-2 control-label">الدائرة</label>
        <div>

            <select name="cycle_follow_id" id="dp_cycle_follow_id" class="form-control">

                <option></option>
                <?php foreach($cycle_follow as $row) :?>
                    <option value="<?= $row['ST_ID'] ?>" <?PHP if ($row['ST_ID']==$cycle_select_follow){ echo " selected"; } ?>><?= $row['ST_NAME'] ?></option>


                <?php endforeach; ?>
            </select>

        </div>
    </div>

    <!--
    <div class="form-group col-sm-2">
        <label class="col-sm-2 control-label">الدائرة</label>
        <div>

            <select name="cycle_follow_id" data-val="true"  data-val-required="حقل مطلوب"  id="dp_cycle_follow_id" class="form-control">

                <option></option>
            </select>

            <span class="field-validation-valid" data-valmsg-for="cycle_follow_id" data-valmsg-replace="true"></span>

        </div>
    </div>
    -->
    <div class="form-group col-sm-2">
        <label class="col-sm-2 control-label">القسم</label>
        <div>

            <select name="department_follow_id" id="dp_department_follow_id" class="form-control">
                <option></option>
                <?php foreach($dep_follow as $row) :?>
                    <option value="<?= $row['ST_ID'] ?>" <?PHP if ($row['ST_ID']==$dep_select_follow){ echo " selected"; } ?>><?= $row['ST_NAME'] ?></option>


                <?php endforeach; ?>

            </select>

        </div>
    </div>

    <!--

     <div class="form-group col-sm-2">
        <label class="col-sm-2 control-label">القسم</label>
        <div>

            <select name="department_follow_id" data-val="true"  data-val-required="حقل مطلوب"  id="dp_department_follow_id" class="form-control">
                <option></option>

            </select>

            <span class="field-validation-valid" data-valmsg-for="department_follow_id" data-valmsg-replace="true"></span>

        </div>
    </div>

    -->


</fieldset>
<hr/>
<fieldset>
    <legend>مسئولية التنفيذ</legend>

    <div class="form-group col-sm-2">
        <label class="col-sm-2 control-label">الفرع</label>
        <div>
            <select name="branch_exe_id" data-val="true"  data-val-required="حقل مطلوب"  id="dp_branch_exe_id" class="form-control">
                <option></option>
                <?php foreach($branches as $row) :?>
                    <option value="<?= $row['NO'] ?>" <?PHP if ($row['NO']==$branch_exe_id){ echo " selected"; } ?> ><?= $row['NAME'] ?></option>


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
    <legend>مدة التنفيذ علىى 12 شهر</legend>
    <div class="form-group col-sm-1">
        <label class="control-label">من شهر</label>
        <div>
            <select name="from_month" data-val="true"  data-val-required="حقل مطلوب"  id="dp_from_month" class="form-control">

                <?php for($i = 1; $i <= 12 ;$i++) :?>
                    <option <?=   (count($rs)>0)?($month_id ==$i?'selected':''):'' ?> value="<?= $i ?>"><?= months($i) ?></option>
                <?php endfor; ?>

            </select>
            <span class="field-validation-valid" data-valmsg-for="from_month" data-valmsg-replace="true"></span>
        </div>
    </div>


    <div class="form-group col-sm-1">
        <label class="control-label">الى شهر</label>
        <div>
            <select name="to_month" data-val="true"  data-val-required="حقل مطلوب"  id="dp_to_month" class="form-control">

                <?php for($i = 1; $i <= 12 ;$i++) :?>
                    <option <?= (count($rs)>0)?($rs['TO_MONTH'] ==$i?'selected':''):'' ?> value="<?= $i ?>"><?= months($i) ?></option>
                <?php endfor; ?>
            </select>
            <span class="field-validation-valid" data-valmsg-for="to_month" data-valmsg-replace="true"></span>
        </div>

</fieldset>
<?php if($achive>0){?>
    <hr/>
    <fieldset>
        <legend>متابعة الانجاز</legend>
        <div class="form-group col-sm-12">
            <?php echo modules::run("$MODULE_NAME/$TB_NAME/$ACHIVE_TB_NAME", (count($rs)>0)?$rs['SEQ']:0); ?>
        </div>

    </fieldset>
<?php }?>
<hr/>
<fieldset>
    <legend>موزعة على المقرات</legend>

    <div   class="details">
        <?php echo modules::run("$MODULE_NAME/$TB_NAME/$DET_TB_NAME", (count($rs)>0)?$rs['SEQ']:0); ?>
    </div>

</fieldset>



<div class="modal-footer">
    <?php
    if (  HaveAccess($post_url)  && ($isCreate || ( $rs['ADOPT']==1 ) )  ) : ?>
        <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
    <?php endif; ?>
    <?php if ( HaveAccess($adopt_url) && (!$isCreate and ( (count($rs)>0)? $rs['ADOPT']==1 : '' ))) : ?>
        <button type="button" id="btn_adopt" onclick='javascript:return_adopt(1);' class="btn btn-success">اعتماد  </button>
    <?php  endif; ?>


</div>

</div>


</form>
</div>


<?php
$scripts = <<<SCRIPT
<script type="text/javascript">



$('#dp_type').select2().on('change',function(){

       //  checkBoxChanged();

        });


  $('#dp_project_id').select2().on('change',function(){

       //  checkBoxChanged();

        });



         $('#dp_objective').select2().on('change',function(){

     //  checkBoxChanged();
             get_data('{$get_all_goal}',{no: $(this).val()},function(data){

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
          get_data('{$get_all_goal}',{no: $(this).val()},function(data){
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


   $('#dp_finance').select2().on('change',function(){

       //  checkBoxChanged();

        });


 $('#dp_manage_follow_id').select2().on('change',function(){

       //  checkBoxChanged();
             get_data('{$cycle_follow_branch}',{no: $(this).val()},function(data){
             $('#dp_cycle_follow_id').html('');
             $('#dp_cycle_follow_id').append('<option></option>');
             $("#dp_cycle_follow_id").select2('val','');
                 $("#dp_department_follow_id").select2('val','');
            $.each(data,function(index, item)
            {
            $('#dp_cycle_follow_id').append('<option value=' + item.ST_ID + '>' + item.ST_NAME + '</option>');
            });
            });

        });


         $('#dp_cycle_follow_id').select2().on('change',function(){

       //  checkBoxChanged();

       //  checkBoxChanged();
             get_data('{$dep_follow_branch}',{no: $(this).val()},function(data){
            $('#dp_department_follow_id').html('');
              $('#dp_department_follow_id').append('<option></option>');
                $("#dp_department_follow_id").select2('val','');
            $.each(data,function(index, item)
            {
            $('#dp_department_follow_id').append('<option value=' + item.ST_ID + '>' + item.ST_NAME + '</option>');
            });
            });

        });

         $('#dp_department_follow_id').select2().on('change',function(){

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

           $('#dp_from_month').select2().on('change',function(){

       //  checkBoxChanged();

        });

           $('#dp_to_month').select2().on('change',function(){

       //  checkBoxChanged();

        });

         $('#dp_branch_follow_id').select2().on('change',function(){


            get_data('{$manage_follow_branch}',{no: $(this).val()},function(data){
            $('#dp_manage_follow_id').html('');
              $('#dp_manage_follow_id').append('<option></option>');
              $("#dp_manage_follow_id").select2('val','');
                $("#dp_cycle_follow_id").select2('val','');
                 $("#dp_department_follow_id").select2('val','');
            $.each(data,function(index, item)
            {
            $('#dp_manage_follow_id').append('<option value=' + item.ST_ID + '>' + item.ST_NAME + '</option>');
            });
            });



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
        e.preventDefault();
        if(confirm('هل تريد الحفظ  ؟!')){
            $(this).attr('disabled','disabled');
            var form = $(this).closest('form');
            ajax_insert_update(form,function(data){
                if(parseInt(data)>1){
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

function delete_row_del(id, branch_name) {
  if (confirm(' هل تريد بالتأكيد حذف حصة '+ branch_name +' ؟!!')) {

        var values = {id: id};
        get_data('{$delete_url_details}', values, function (data) {

            if (data == 1) {

                success_msg('تمت عملية الحذف بنجاح');
                get_to_link(window.location.href);
            }

            else {
                danger_msg('لم يتم الحذف', data);
            }

        }, 'html');

    }

}

calcall();

  $('input[name="part[]"]').change(function () {
 // alert();
  calcall();
    });
 $('input[name="part[]"]').keyup(function () {
 //alert();
  calcall();
    });
function calcall() {
    var total_part_branches = 0;

    $('input[name="part[]"]').each(function () {
        var part = $(this).closest('tr').find('input[name="part[]"]').val();
        total_part_branches += Number(part);
        $('#total_part_branches').text(isNaNVal(Number(total_part_branches)));
    });



}

reBind();
calcall();

 $('input[name="part[]"]').change(function () {
 // alert();
  calcall();
    });

     $('input[name="part[]"]').keyup(function () {
 //alert();
  calcall();
    });


function calcall() {

    var total_part_branches = 0;

    $('input[name="part[]"]').each(function () {

        var part = $(this).closest('tr').find('input[name="part[]"]').val();
        total_part_branches += Number(part);
        $('#total_part_branches').text(isNaNVal(Number(total_part_branches)));
    });



}
function reBind(){

 $('input[name="part[]"]').change(function () {
 // alert();
  calcall();
    });

     $('input[name="part[]"]').keyup(function () {
 //alert();
  calcall();
    });


}


function return_adopt(type) {

    if (type == 1) {

    get_data('{$adopt_url}', {id: {$fid},adopts:2}, function (data) {

            if (data == 1)
            {
                success_msg('رسالة', 'تم العملية بنجاح ..');
                reload_Page();
            }



            else
            {
               danger_msg('لم يتم الاعتماد');
            }

        }, 'html');






    }




}
</script>

SCRIPT;

sec_scripts($scripts);

?>

