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
$DET_TB_NAME='public_get_details_branch';
$ACHIVE_TB_NAME='public_get_achive';
$backs_url=base_url("$MODULE_NAME/$TB_NAME"); //$action
$create_without_cost =base_url("$MODULE_NAME/$TB_NAME/create_without_cost");
$manage_follow_branch =base_url("$MODULE_NAME/$TB_NAME/public_get_mange");
$manage_exe_branch =base_url("$MODULE_NAME/$TB_NAME/public_get_mange");
$cycle_follow_branch =base_url("$MODULE_NAME/$TB_NAME/public_get_cycle");
$cycle_exe_branch =base_url("$MODULE_NAME/$TB_NAME/public_get_cycle");
$dep_follow_branch =base_url("$MODULE_NAME/$TB_NAME/public_get_dep");
$dep_exe_branch =base_url("$MODULE_NAME/$TB_NAME/public_get_dep");
$get_code_url =base_url("$MODULE_NAME/$TB_NAME/public_get_id_json");
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
$count=0;
$count_distrbute=0;
$create_edit_plan = base_url("$MODULE_NAME/$TB_NAME/create_edit_plan");
$save_part_url= base_url("$MODULE_NAME/$TB_NAME/create_edit_part");
$select_items_url =base_url("$MODULE_NAME/$TB_NAME/public_get_details_branch");
$select_active_url =base_url("$MODULE_NAME/$TB_NAME/public_follow_project");
$active_get_url =base_url("$MODULE_NAME/$TB_NAME/public_get_sub_activities");

echo AntiForgeryToken();
?>

<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
            <?php  if( HaveAccess($backs_url)):  ?><li><a  href="<?= $backs_url ?>"><i class="icon icon-reply"></i> </a> </li><?php  endif; ?>
            <?php if( HaveAccess($create_url)):  ?><li><a  href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>اضافة مشروع فني</a> </li><?php endif; ?>
            <?php if( HaveAccess($create_without_cost_url)):  ?><li><a  href="<?= $create_without_cost_url ?>"><i class="glyphicon glyphicon-plus"></i>اضافة مشروع غير فني</a> </li><?php endif; ?>
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
    <legend>الاهداف و الغايات</legend>

    <div class="form-group col-sm-1">
        <label class="col-sm-2 control-label">تعريف النشاط لعام</label>
        <div>
            <input type="text" data-val="true"  data-val-required="حقل مطلوب" name="year" id="txt_year" class="form-control" dir="rtl" value="<?php echo (count($rs)>0)? $rs['YEAR']: /*date('Y')+1 */$year_paln;?>" readonly>
            <span class="field-validation-valid" data-valmsg-for="year" data-valmsg-replace="true"></span>

        </div>
    </div>

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

    <div class="form-group col-sm-1">
        <label class="col-sm-1 control-label">مقر التنفيذ</label>
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
        <label class="col-sm-2 control-label">جهة التنفيذ/ادارة</label>
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
        <label class="col-sm-2 control-label">جهة التنفيذ/دائرة</label>
        <div>

            <select name="cycle_exe_id" id="dp_cycle_exe_id" class="form-control">

                <option></option>
                <?php foreach($cycle_exe as $row) :?>
                    <option value="<?= $row['ST_ID'] ?>" <?PHP if ($row['ST_ID']==$cycle_select_exe){ echo " selected"; } ?>><?= $row['ST_NAME'] ?></option>


                <?php endforeach; ?>
            </select>


        </div>
    </div>

    <div class="form-group col-sm-1 hidden">
        <label class="col-sm-2 control-label">تعريف المشروع لعام</label>
        <div>
            <input type="text" data-val="true"  data-val-required="حقل مطلوب" name="year" id="txt_year" class="form-control" dir="rtl" value="<?php echo (count($rs)>0)? $rs['YEAR']: /*date('Y')+1 */$year_paln;?>" readonly>
            <span class="field-validation-valid" data-valmsg-for="year" data-valmsg-replace="true"></span>


        </div>
    </div>

</fieldset>
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

<legend>  بيانات المشروع </legend>

<div class="tb_container">
    <table class="table" id="details_tb2" data-container="container" style="width:100%" align="center">
        <thead>
        <tr>
            <th class="hidden" style="width:8%">رقم النشاط</th>
           <!-- <th class="hidden" style="width:4%">سنة</th>-->
            <th >تصنيف المشروع</th>
            <th >اسم المشروع</th>
            <th style="width:6%">التكلفة</th>
            <th >نوع المشروع</th>
            <th >من شهر</th>
            <th >الى شهر</th>
            <th >مقر الجهة المساندة</th>
            <th >جهة المساندة/ادارة</th>
            <th >جهة المساندة/دائرة</th>
            <th class="hidden" >مقر التنفيذ</th>
            <th class="hidden">جهة التنفيذ/ادارة</th>
            <th class="hidden">جهة التنفيذ/دائرة</th>
            <th >حفظ</th>
            <th >الأنشطة الفرعية</th>

            <th >اعتماد</th>



        </tr>
        </thead>

        <tbody>

            <tr>
                <td class="hidden">
                    <input type="text"  name="activity_no[]" id="txt_activity_no_<?= $count ?>" class="form-control" dir="rtl"  readonly>
                    <span class="field-validation-valid" data-valmsg-for="activity_no[]" data-valmsg-replace="true"></span>
                </td>
                <!--
                <td class="hidden">
                    <input type="text" data-val="true"  data-val-required="حقل مطلوب" name="year[]" id="txt_year_<?= $count ?>" class="form-control" dir="rtl"  readonly>
                    <span class="field-validation-valid" data-valmsg-for="year[]" data-valmsg-replace="true"></span>
                    <input type="hidden" data-val="true"    data-val-required="حقل مطلوب" name="seq[]" id="txt_seq_<?= $count ?>" value="0" class="form-control">
                </td>
                -->
                <td>

                    <select name="class_name[]" data-val="true"  data-val-required="حقل مطلوب"  id="dp_class_name_<?= $count ?>" class="form-control" >
                        <option></option>
                        <?php foreach($activity_class_no_tech as $row) :?>
                            <option value="<?= $row['CON_NO'] ?>"  ><?= $row['CON_NAME'] ?></option>

                        <?php endforeach; ?>

                    </select>
                    <input type="hidden" data-val="true"    data-val-required="حقل مطلوب" name="seq[]" id="txt_seq_<?= $count ?>" value="0" class="form-control">
                    <input type="hidden" name="h_count[]" id="h_count_<?=$count?>" />

                    <span class="field-validation-valid x" data-valmsg-for="class_name[]"  data-valmsg-replace="true"></span>
                </td>
                <td> <input type="text" data-val="true"  data-val-required="حقل مطلوب" name="activity_name[]" id="txt_activity_name_<?= $count ?>" class="form-control" dir="rtl" >
                    <span class="field-validation-valid" data-valmsg-for="activity_name[]" data-valmsg-replace="true"></span></td>
                <td><input  data-val="true"  data-val-required="حقل مطلوب"  class="form-control"  name="total_price[]" id="total_price_id_<?= $count ?>" />
                    <span class="field-validation-valid" data-valmsg-for="total_price[]" data-valmsg-replace="true"></span></td>
                <td>
                    <select name="type[]" data-val="true"  data-val-required="حقل مطلوب"  id="dp_type_<?= $count ?>" class="form-control">
                        <option></option>
                        <?php foreach($activity_type as $row) :?>
                            <option value="<?= $row['CON_NO'] ?>"  ><?= $row['CON_NAME'] ?></option>

                        <?php endforeach; ?>

                    </select>
                    <!-- <span class="field-validation-valid" data-valmsg-for="type[]" data-valmsg-replace="true"></span>-->
                </td>
                <td><select name="from_month[]" data-val="true"  data-val-required="حقل مطلوب"  id="dp_from_month_<?= $count ?>" class="form-control">

                        <?php for($i = 1; $i <= 12 ;$i++) :?>
                            <option  value="<?= $i ?>"><?= months($i) ?></option>
                        <?php endfor; ?>

                    </select>
                    <span class="field-validation-valid" data-valmsg-for="from_month[]" data-valmsg-replace="true"></span></td>
                <td><select name="to_month[]" data-val="true"  data-val-required="حقل مطلوب"  id="dp_to_month_<?= $count ?>" class="form-control">

                        <?php for($i = 1; $i <= 12 ;$i++) :?>
                            <option  value="<?= $i ?>"><?= months($i) ?></option>
                        <?php endfor; ?>
                    </select>
                    <span class="field-validation-valid" data-valmsg-for="to_month[]" data-valmsg-replace="true"></span></td>
                <td> <select name="branch_follow_id[]" data-val="true"  data-val-required="حقل مطلوب"  id="dp_branch_follow_id_<?= $count ?>" class="form-control">
                        <option></option>
                        <?php foreach($branches_follow as $row) :?>
                            <option value="<?= $row['NO'] ?>"  ><?= $row['NAME'] ?></option>


                        <?php endforeach; ?>

                    </select>
                    <span class="field-validation-valid" data-valmsg-for="branch_follow_id[]" data-valmsg-replace="true"></span></td>
                <td><select name="manage_follow_id[]" data-val="true"  data-val-required="حقل مطلوب"  id="dp_manage_follow_id_<?= $count ?>" class="form-control">
                        <option></option>
                        <?php foreach($b_follow as $row) :?>
                            <option value="<?= $row['ST_ID'] ?>" ><?= $row['ST_NAME'] ?></option>


                        <?php endforeach; ?>


                    </select>
                    <span class="field-validation-valid" data-valmsg-for="manage_follow_id[]" data-valmsg-replace="true"></span>
                </td>
                <td> <select name="cycle_follow_id[]" id="dp_cycle_follow_id_<?= $count ?>" class="form-control">

                        <option></option>
                        <?php foreach($cycle_follow as $row) :?>
                            <option value="<?= $row['ST_ID'] ?>" ><?= $row['ST_NAME'] ?></option>


                        <?php endforeach; ?>
                    </select></td>
                <td class="hidden">  <select name="branch_exe_id[]" data-val="true"  data-val-required="حقل مطلوب"  id="dp_branch_exe_id_<?= $count ?>" class="form-control">
                        <option></option>
                        <?php foreach($branches as $row) :?>
                            <option value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>


                        <?php endforeach; ?>

                    </select>

                    <span class="field-validation-valid" data-valmsg-for="branch_exe_id[]" data-valmsg-replace="true"></span></td>
                <td class="hidden"> <select name="manage_exe_id[]" data-val="true"  data-val-required="حقل مطلوب"  id="dp_manage_exe_id_<?= $count ?>" class="form-control">
                        <option></option>
                        <?php foreach($b as $row) :?>
                            <option value="<?= $row['ST_ID'] ?>" ><?= $row['ST_NAME'] ?></option>


                        <?php endforeach; ?>


                    </select>

                    <span class="field-validation-valid" data-valmsg-for="manage_exe_id[]" data-valmsg-replace="true"></span></td>
                <td class="hidden">
                    <select name="cycle_exe_id[]" id="dp_cycle_exe_id_<?= $count ?>" class="form-control">

                        <option></option>
                        <?php foreach($cycle_exe as $row) :?>
                            <option value="<?= $row['ST_ID'] ?>"><?= $row['ST_NAME'] ?></option>


                        <?php endforeach; ?>
                    </select>
                </td>
                <td>

                    <button type="button" id="btn_save_<?= $count ?>" class="btn btn-primary" name="save[]">حفظ</button>
                </td>
               <!-- <td>
                    <button type="button" id="btn_distrbute_<?= $count ?>" class="btn btn-warning hidden" name="distrbute[]">حصص</button>
                </td>
                -->
                <td>
                    <button type="button" id="btn_active_<?= $count ?>" class="btn btn-warning hidden"  name="active[]">الأنشطة الفرعية</button>
                </td>

                <td>
                    <button type="button" id="btn_adopt_<?= $count ?>" class="btn btn-info hidden" name="adopt[]">اعتماد</button>

                </td>

            </tr>

        </tbody>

        <tfoot>

        <tr>
            <th>
                <a onclick="return add_row(this,'input:text,input:hidden[name^=seq],textarea,select',false);" href="javascript:;" class="new"><i class="glyphicon glyphicon-plus"></i>جديد</a>
            </th>
            <th class="hidden"></th>
            <!-- <th class="hidden"></th> -->
            <th ></th>
            <th ></th>
            <th ></th>
            <th ></th>
            <th ></th>
            <th ></th>
            <th class="hidden" ></th>
            <th class="hidden"></th>
            <th class="hidden" ></th>
            <th ></th>
            <th ></th>
            <th ></th>



            <th ></th>
            <th ></th>
        </tr>

        </tfoot>
    </table></div>
</fieldset><hr/>
<hr/>




<div class="modal-footer">

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
var count=[];




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
 $('#class_name').select2().on('change',function(){

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

        });











        $('.save_part').on('click',  function (e) {

        var url = '{$save_part_url}';
        var tbl = '#details_tb1';
        var container = $('#' + $(tbl).attr('data-container'));
         var branch = [];
         var part = [];
         var activity=[];
         var ser1=[];
         var act_ser;


        $('select[name="branch[]"]').each(function (i) {

           branch[i]=$(this).closest('tr').find('select[name="branch[]"]').val();
           part[i]=$(this).closest('tr').find('input[name="part[]"]').val();
           activity[i]=$(this).closest('tr').find('input[name="h_txt_activity_no_id[]"]').val();
           ser1[i]=$(this).closest('tr').find('input[name="ser1[]"]').val();
           act_ser=$(this).closest('tr').find('input[name="h_txt_activity_no_id[]"]').val();






    });



     if(branch.length > 0){
      var arr_data = [branch,part,activity,ser1];


              get_data(url,{ser1:ser1,activity_no_id: activity, branch: branch,part:part},function(data){
             if(data>1)
             {

             success_msg('رسالة','تم عملية الاعتماد بنجاح ..');


              $('#PlanModal').modal('hide');
             }

                 else
                  danger_msg('لم يتم الحذف', data);

            });


        }else
            alert('لايوجد سجلات ممكن اعتمادها');

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



reBind_pram(0);




function reBind_pram(cnt){


$('select[name="class_name[]"]').each(function (i) {


           count[i]=$(this).closest('tr').find('input[name="h_count[]"]').val(i);






    });

if(cnt>=1)
{
                   $('#btn_save_'+cnt).removeClass("hidden");
                  // $('#btn_distrbute_'+cnt).addClass("hidden");
                    $('#btn_active_'+cnt).addClass("hidden");
                   $('#btn_adopt_'+cnt).addClass("hidden");
                   //
                   }
$('button#btn_save_'+cnt).on('click',  function (e) {
var cnt_tr=$(this).closest('tr').find('input[name="h_count[]"]').val();
           if($('#dp_objective').val()=='')
           {
           danger_msg('الغاية غير مدخل!!');

           }

             else if($('#dp_goal').val()=='')
           {
            danger_msg('الهدف الاستراتيجي غير مدخل!!');

           }


             else if($('#dp_goal_t').val()=='')
           {
             danger_msg('الهدف التشغيلي غير مدخل!!');

           }

             else if($('#dp_branch_exe_id').val()=='')
           {
             danger_msg('مقر التنفيذ غير مدخل!!');
           }

             else if($('#dp_manage_exe_id').val()=='')
           {
            danger_msg('جهة التنفيذ/ادارة غير مدخل!!');

           }



           else if($('#dp_class_name_'+cnt_tr).val()=='')
           {
            danger_msg('تنصيف النشاط غير مدخل!!');

           }
           else if($('#txt_activity_name_'+cnt_tr).val()=='')
           {
            danger_msg('اسم النشاط غير مدخل!!');

           }
           else if($('#total_price_id_'+cnt_tr).val()=='')
           {
           if ($('#total_price_id_'+cnt_tr).val()=='')
           {
           danger_msg('التكلفة غير مدخل!!');
           }
         else if (isNaN($('#total_price_id_'+cnt_tr).val())){
         danger_msg('ادخال خاطئ لتكلفة');
         $('#total_price_id_'+cnt_tr).val('');
         $('#total_price_id_'+cnt_tr).focus();


        }
          else if($('#dp_type_'+cnt_tr).val()=='')
           {

           danger_msg('نوع النشاط غير مدخل!!');

           }
            else if($('#dp_from_month_'+cnt_tr).val()=='')
           {
           danger_msg('من شهر غير مدخل!!');

           }
             else if($('#dp_to_month_'+cnt_tr).val()=='')
           {
           danger_msg('الى شهر غير مدخل!!');

           }
           else if($('#dp_branch_follow_id_'+cnt_tr).val()=='')
           {
          danger_msg('مقر المتابعة غير مدخل!!');

           }
            else if($('#dp_manage_follow_id_'+cnt_tr).val()=='')
           {

          danger_msg('جهة المتابعة/ادارة غير مدخل!!');

           }
             else if($('#dp_cycle_follow_id_'+cnt_tr).val()=='')
           {

           danger_msg('جهة المتابعة/دائرة غير مدخل!!');

           }

           }
           else
           {
             var seq = $('#txt_seq_'+cnt_tr).val();
             var objective = $('#dp_objective').val();
             var goal =  $('#dp_goal').val();
             var goal_t = $('#dp_goal_t').val();
             var branch_exe_id = $('#dp_branch_exe_id').val();
             var manage_exe_id = $('#dp_manage_exe_id').val();
             var cycle_exe_id = $('#dp_cycle_exe_id').val();
             var class_name = $('#dp_class_name_'+cnt_tr).val();
             var activity_name = $(this).closest('tr').find('input[name="activity_name[]"]').val();
             var total_price = $(this).closest('tr').find('input[name="total_price[]"]').val();
             var type = $(this).closest('tr').find('select[name="type[]"]').val();
             var from_month = $(this).closest('tr').find('select[name="from_month[]"]').val();
             var to_month = $(this).closest('tr').find('select[name="to_month[]"]').val();
             var branch_follow_id = $(this).closest('tr').find('select[name="branch_follow_id[]"]').val();
             var manage_follow_id = $(this).closest('tr').find('select[name="manage_follow_id[]"]').val();
             var cycle_follow_id = $(this).closest('tr').find('select[name="cycle_follow_id[]"]').val();

            var arr_data = [seq,objective,goal,goal_t,branch_exe_id,manage_exe_id,cycle_exe_id,class_name,activity_name,total_price,type,from_month,to_month,branch_follow_id,manage_follow_id,cycle_follow_id];



            get_data('{$create_edit_plan}', {seq:$.trim(seq),year:{$year_paln},objective:objective,goal:goal,goal_t:goal_t,branch_exe_id:branch_exe_id,manage_exe_id:manage_exe_id,cycle_exe_id:cycle_exe_id,class_name:class_name,activity_name:activity_name,total_price:total_price,type :type ,from_month:from_month,to_month:to_month,branch_follow_id :branch_follow_id ,manage_follow_id :manage_follow_id ,cycle_follow_id :cycle_follow_id}, function (data) {
              if(parseInt(data)>1){
                   success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                  // $('#btn_distrbute_'+cnt).removeClass("hidden");
                   $('#btn_active_'+cnt).removeClass("hidden");
                   $('#btn_adopt_'+cnt).removeClass("hidden");
                  //  $(this).closest("tr").removeClass("selected");
                //  $(this).closest("tr").addClass("case_2");
                $('#txt_seq_'+cnt).val($.trim(data));
                //console.log(arr_data);






                }else{
                    danger_msg('تحذير..',data);
                  //   console.log(arr_data);
                }

            }, 'html');


           }
        });

        $('button#btn_adopt_'+cnt).on('click',  function (e) {
          var cnt_tr=$(this).closest('tr').find('input[name="h_count[]"]').val();
       //alert('loop==>'+cnt_tr);
          get_data('{$active_get_url}',{no: $('#txt_seq_'+cnt_tr).val()},function(data){

          if(parseInt(data.length)!=0)
          {
get_data('{$adopt_url}', {id: $.trim($('#txt_seq_'+cnt_tr).val()),adopts:2}, function (data) {

            if (data == 1)
            {
                success_msg('رسالة', 'تم العملية بنجاح ..');
                $('#btn_save_'+cnt_tr).addClass("hidden");
                //$('#btn_distrbute_'+cnt_tr).addClass("hidden");
                $('#btn_distrbute_'+cnt_tr).addClass("hidden");
                $('#btn_active_'+cnt_tr).addClass("hidden");
                $('#btn_adopt_'+cnt_tr).addClass("hidden");

                //reload_Page();
            }



            else
            {
               danger_msg('لم يتم الاعتماد');
            }

        }, 'html');


          }
          else
          {

danger_msg('لا يمكن الاعتماد يتوجب عليك ادخال نشاط فرعي واحد فقط !!','');
          }




});

        });

             /* $('button#btn_distrbute_'+cnt).on('click',  function (e) {

             /*var activity_name =$('#txt_activity_name_'+cnt).val();
             $('#h_txt_activity_no_id_0').val($('#txt_seq_'+cnt).val());
              $( "#Title_Name" ).text( activity_name ) ;
              $('#id_ser_plan').val($('#txt_seq_'+cnt).val());*/

//_showReport('$select_items_url/'+$('#txt_seq_'+cnt).val());



//$('#PlanModal').modal();
       /* });*/
        $('button#btn_active_'+cnt).on('click',  function (e) {


_showReport('$select_active_url/'+$('#txt_seq_'+cnt).val()+'/'+$('#dp_from_month_'+cnt).val()+'/'+$('#dp_to_month_'+cnt).val());
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





        });

$('.select2-container',$('#details_tb2')).remove();

$('input[name="year[]"]').val('{$year_paln}');



$('select[name="type[]"]').select2().on('change',function(){

       //  checkBoxChanged();

        });


        $('select[name="manage_follow_id[]"]').select2().on('change',function(){
var cnt_tr=$(this).closest('tr').find('input[name="h_count[]"]').val();
       //alert('loop==>'+cnt_tr);


       //  checkBoxChanged();
             get_data('{$cycle_follow_branch}',{no: $(this).val()},function(data){
            // alert('loop'+cnt);
              var cycle_follow_id = $(this).closest('tr').find('select[name="cycle_follow_id[]"]').val();

             $('#dp_cycle_follow_id_'+cnt_tr).html('');
             $('#dp_cycle_follow_id_'+cnt_tr).append('<option></option>');
           $('#dp_cycle_follow_id_'+cnt_tr).select2('val','');

            $.each(data,function(index, item)
            {

            $('#dp_cycle_follow_id_'+cnt_tr).append('<option value=' + item.ST_ID + '>' + item.ST_NAME + '</option>');
            });
            });

        });


         $('select[name="cycle_follow_id[]"]').select2().on('change',function(){
         var cnt_tr=$(this).closest('tr').find('input[name="h_count[]"]').val();
       //alert('loop==>'+cnt_tr);

       //  checkBoxChanged();

       //  checkBoxChanged();
             get_data('{$dep_follow_branch}',{no: $(this).val()},function(data){
            $('#dp_department_follow_id').html('');
              $('#dp_department_follow_id').append('<option></option>');
            //    $("#dp_department_follow_id").select2('val','');
            $.each(data,function(index, item)
            {
            $('#dp_department_follow_id').append('<option value=' + item.ST_ID + '>' + item.ST_NAME + '</option>');
            });
            });

        });


 $('select[name="from_month[]"]').select2().on('change',function(){

       //  checkBoxChanged();

        });

           $('select[name="to_month[]"]').select2().on('change',function(){

       //  checkBoxChanged();

        });

        $('select[name="class_name[]"]').select2().on('change',function(){

       //  checkBoxChanged();
        if($(this).val()==3)
        {
        $('#total_price_id_'+cnt).val(0);
        }
        else
      $('#total_price_id_'+cnt).val('');

        });


 $('select[name="branch_follow_id[]"]').select2().on('change',function(){
var cnt_tr=$(this).closest('tr').find('input[name="h_count[]"]').val();
     //  alert('loop==>'+cnt_tr);

            get_data('{$manage_follow_branch}',{no: $(this).val()},function(data){

             $('#dp_manage_follow_id_'+cnt_tr).html('');
             $('#dp_manage_follow_id_'+cnt_tr).append('<option></option>');
             $('#dp_manage_follow_id_'+cnt_tr).select2('val','');
             $('#dp_cycle_follow_id_'+cnt_tr).select2('val','');

            $.each(data,function(index, item)
            {

             $('#dp_manage_follow_id_'+cnt_tr).append('<option value=' + item.ST_ID + '>' + item.ST_NAME + '</option>');
            });
            });



        });


$('#total_price_id_'+cnt).keyup(function () {

if($('#dp_class_name_'+cnt).val()==2)
{
        if (isNaN($('#total_price_id_'+cnt).val())){
         danger_msg('ادخال خاطئ لتكلفة');
         $('#total_price_id_'+cnt).val('');
         $('#total_price_id_'+cnt).focus();


        }
        else
        {
        if($('#total_price_id_'+cnt).val()==0)
        {
         danger_msg('هذا مشروع غير فني بتكلفة يتوجب عليك ادخال التكلفة اكبر من 0');
          $('#total_price_id_'+cnt).val('');
        }

        }
        }
        else
        {
   if (isNaN($('#total_price_id_'+cnt).val())){
         danger_msg('ادخال خاطئ لتكلفة');
         $('#total_price_id_'+cnt).val(0);
         $('#total_price_id_'+cnt).focus();


        }
        else
        {
        if($('#total_price_id_'+cnt).val()!=0)
        {
         danger_msg('هذا مشروع غير فني  بدون تكلفة يتوجب عليك ادخال التكلفة  0');
         $('#total_price_id_'+cnt).val(0);
        }

        }
        }

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

