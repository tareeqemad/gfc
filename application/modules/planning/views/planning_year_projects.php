<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 04/12/17
 * Time: 11:34 ص
 */

$MODULE_NAME = 'planning';
$TB_NAME = 'planning';
$DET_TB_NAME = 'public_get_details';
$DET_TB_NAME1 = 'public_get_activities_details';
$DET_TB_NAME2 = 'public_get_follow_details';
$DET_TB_NAME3 = 'public_get_all_follow_exe';
$DET_TB_NAME4 = 'public_get_target';
$DET_TB_NAME5 = 'public_get_class_details';
$ACHIVE_TB_NAME = 'public_get_achive';
$monthes_url = 'public_get_monthes';
$backs_url = base_url("$MODULE_NAME/$TB_NAME"); //$action
$get_class_url = base_url('stores/classes/public_get_id');
$select_items_url = base_url('stores/classes/public_index');
$create_without_cost = base_url("$MODULE_NAME/$TB_NAME/create_without_cost");
$manage_follow_branch = base_url("$MODULE_NAME/$TB_NAME/public_get_mange");
$manage_exe_branch = base_url("$MODULE_NAME/$TB_NAME/public_get_mange_exe"); //base_url("$MODULE_NAME/$TB_NAME/public_get_mange");
$cycle_follow_branch = base_url("$MODULE_NAME/$TB_NAME/public_get_cycle");
$cycle_exe_branch = base_url("$MODULE_NAME/$TB_NAME/public_get_cycle_exe"); //base_url("$MODULE_NAME/$TB_NAME/public_get_cycle");
$dep_follow_branch = base_url("$MODULE_NAME/$TB_NAME/public_get_dep");
$dep_exe_branch = base_url("$MODULE_NAME/$TB_NAME/public_get_dep");
$get_all_goal = base_url("$MODULE_NAME/$TB_NAME/public_get_goal");
$delete_url_details = base_url("$MODULE_NAME/$TB_NAME/delete_details");
$post_url = base_url("$MODULE_NAME/$TB_NAME/" . ($action == 'index' ? 'create_without_cost' : $action));
$create_url = base_url("$MODULE_NAME/$TB_NAME/create");
$create_without_cost_url = base_url("$MODULE_NAME/$TB_NAME/create_without_cost");
$rs = ($isCreate) ? array() : $planning_data;
$achive = count($achive_data);
$branch_follow_id = (count($rs) > 0) ? $rs['BRANCH_FOLLOW_ID'] : 0;
$branch_exe_id = (count($rs) > 0) ? $rs['BRANCH_EXE_ID'] : 0;
$cycle_follow_branch1 = (count($rs) > 0) ? $rs['CYCLE_FOLLOW_ID'] : 0;
//$cycle_follow_branch1= (count($rs)>0)?$rs['CYCLE_FOLLOW_ID']:0;
$objective_id = (count($rs) > 0) ? $rs['OBJECTIVE'] : 0;
$type_id = (count($rs) > 0) ? $rs['TYPE'] : 0;
$type_emp= (count($rs) > 0) ? $rs['TYPE_EMP'] : 1;
$class_name = (count($rs) > 0) ? $rs['CLASS'] : 0;
$type_project_name = (count($rs) > 0) ? $rs['TYPE_PROJECT'] : 1;
$fin_name = (count($rs) > 0) ? $rs['FINANCE_NAME'] : 'شركة توزيع كهرباء محافظات غزة';
$month_id = (count($rs) > 0) ? $rs['FROM_MONTH'] : 01;

$manage_select_follow = (count($rs) > 0) ? $rs['MANAGE_FOLLOW_ID'] : 0;
$manage_select_exe = (count($rs) > 0) ? $rs['MANAGE_EXE_ID'] : 0;
$cycle_select_exe = (count($rs) > 0) ? $rs['CYCLE_EXE_ID'] : 0;
$cycle_select_follow = (count($rs) > 0) ? $rs['CYCLE_FOLLOW_ID'] : 0;
$dep_select_follow = (count($rs) > 0) ? $rs['DEPARTMENT_FOLLOW_ID'] : 0;
$dep_select_exe = (count($rs) > 0) ? $rs['DEPARTMENT_EXE_ID'] : 0;
$goal_select = (count($rs) > 0) ? $rs['GOAL'] : 0;
$goal_t_select = (count($rs) > 0) ? $rs['GOAL_T'] : 0;
$goal_select1 = (count($rs) > 0) ? $rs['OBJECTIVE'] : -1;
$goal_t_select1 = (count($rs) > 0) ? $rs['GOAL'] : -1;
$year_ = (count($rs) > 0) ? $rs['YEAR'] : date('Y')+1;
$goal_list = modules::run("$MODULE_NAME/$TB_NAME/public_get_goal_p", $goal_select1, $year_);
$goal_t_list = modules::run("$MODULE_NAME/$TB_NAME/public_get_goal_p", $goal_t_select1, $year_);
$b = modules::run("$MODULE_NAME/$TB_NAME/public_get_mange_b_", $user_info[0]['EMP_ID'], $user_info[0]['BRANCH']); //modules::run("$MODULE_NAME/$TB_NAME/public_get_mange_b", $branch_exe_id);
$b_follow = modules::run("$MODULE_NAME/$TB_NAME/public_get_mange_b", $branch_follow_id);

$cycle_follow1 = (count($rs) > 0) ? $rs['MANAGE_FOLLOW_ID'] : -1;
$cycle_follow = modules::run("$MODULE_NAME/$TB_NAME/public_get_cycle_b", $cycle_follow1);

$cycle_exe1 = (count($rs) > 0) ? $rs['MANAGE_EXE_ID'] : -1;
if ($b[0]['TYPE'] == 1) {
    $cycle_exe = modules::run("$MODULE_NAME/$TB_NAME/public_get_cycle_b", $cycle_exe1);
} else {
    $cycle_exe = modules::run("$MODULE_NAME/$TB_NAME/public_get_cycle_b_", $user_info[0]['EMP_ID'], $user_info[0]['BRANCH']); //modules::run("$MODULE_NAME/$TB_NAME/public_get_mange_b", $branch_exe_id);

}

$dep_follow1 = (count($rs) > 0) ? $rs['CYCLE_FOLLOW_ID'] : -1;

$dep_follow = modules::run("$MODULE_NAME/$TB_NAME/public_get_dep_p", $dep_follow1);

$dep_exe1 = (count($rs) > 0) ? $rs['CYCLE_EXE_ID'] : -1;
$dep_exe = modules::run("$MODULE_NAME/$TB_NAME/public_get_dep_p", $dep_exe1);

$get_url = base_url("$MODULE_NAME/$TB_NAME/get_tech_cost");
$adopt_url = base_url("$MODULE_NAME/$TB_NAME/adopt");
$fid = (count($rs) > 0) ? $rs['SEQ'] : 0;
$back_budget_tech_url = base_url("budget/Projects/archive");
$delete_url_act_details = base_url("$MODULE_NAME/$TB_NAME/delete_sub_details");
$active_get_url = base_url("$MODULE_NAME/$TB_NAME/public_get_sub_activities");
$fin_id = (count($rs) > 0) ? $rs['FINANCE'] : 0;
if ($have_project_ser) $project_info = ($isCreate) ? $project[0] : array();
else $project_info = ($isCreate) ? $project : array();
$project_id = (count($rs) > 0) ? $rs['PROJECT_ID'] : 0;

$get_project_url = base_url("$MODULE_NAME/$TB_NAME/public_get_id");
$public_get_projects_url = base_url("$MODULE_NAME/$TB_NAME/public_get_projects_by_year");
$select_tasks_url = base_url("$MODULE_NAME/$TB_NAME/public_get_tasks");
//echo $month_id.'kk';
//echo $project_info['MONTH'];
//ACTIVE_TYPE_NAME
//var_dump($b);
$no_edit = 1;
$readonly = "";
$class_no_edit = 1;
$class_readonly = "";
$hidden = "hidden";
$no_edit_year = 1;
$hidden_class = "";
$year_plan = "";
$hidden_item_class = "";

if ($type_project_name == 2) {
    if ($fin_id == "") {
        $rs['FINANCE_NAME'] = "شركة توزيع كهرباء محافظات غزة";
    }
    $year_plan = "readonly";
    $hidden = "";
    $readonly = "readonly";
    $no_edit = 2;
    $hidden_class = "hidden";

    if ($class_name != 1) {
        $class_no_edit = 1;
        $class_readonly = "";
    } else {
        $class_no_edit = 2;
        $class_readonly = "readonly";
    }

} else if ($type_project_name == 1) {

    $hidden = "hidden";
    $hidden_class = "";
    if ($class_name != 1) {
        $class_no_edit = 1;
        $class_readonly = "";
        $year_plan = "readonly";

    } else {
        $class_no_edit = 2;
        $class_readonly = "readonly";
        $no_edit_year = 2;
        $year_plan = "readonly";
    }

}
$fin_hidden = "";

if ($class_name == 3) {
    $fin_hidden = "hidden";
    $year_plan = "readonly";
    $hidden_item_class = "hidden";

}
if (count($rs) > 0) {
    $year_plan = "readonly";
} else {
    $year_plan = "";
}
/*echo $b[0]['ST_ID'].'<br>';
 echo $b[0]['TYPE'];*/

$date3 = "2023-09-06";
$date4 = date('Y-m-d');
$date5 = "2023-09-07";

$dateTimestamp3 = strtotime($date3);
$dateTimestamp4 = strtotime($date4);
$dateTimestamp5 = strtotime($date5);


 if($dateTimestamp3 ==  $dateTimestamp4 || $dateTimestamp5 ==  $dateTimestamp4 )
{
 if($this->user->id == 589|| $this->user->id == 135 )
$year_ = date('Y');
}

echo AntiForgeryToken();
?>
<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title
            ?></div>

        <ul>
            <?php if (HaveAccess($backs_url)): ?>
                <li><a href="<?= $backs_url ?>"><i class="icon icon-reply"></i> </a> </li><?php
            endif; ?>
            <!-- <?php if (HaveAccess($create_url)): ?><li><a  href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>اضافة مشروع فني</a> </li><?php
            endif; ?>-->
            <?php if (HaveAccess($create_without_cost_url)): ?>
                <li><a href="<?= $create_without_cost_url ?>"><i class="glyphicon glyphicon-plus"></i>جديد</a>
                </li><?php
            endif; ?>
            <li><a  href="<?=base_url('uploads/statgic_plan_user_manual.pdf')?>" target="_blank"><i class="icon icon-question-circle"></i>دليل المستخدم</a>


        </ul>

    </div>
</div>
<div class="form-body">

    <div id="msg_container"></div>
    <div id="container">
        <form class="form-vertical" id="<?= $TB_NAME
        ?>_form" method="post" action="<?= $post_url
        ?>" role="form" novalidate="novalidate">
            <div class="modal-body inline_form">
                <fieldset>
                    <legend>المحاور و الأهداف</legend>

                    <div class="form-group col-sm-1">
                        <label class="col-sm-2 control-label">عام الخطة</label>
                        <div>
                            <input type="text" data-val="true" data-val-required="حقل مطلوب" name="year" id="txt_year"
                                   class="form-control" dir="rtl" <?= $year_plan; ?>
                                   value="<?php echo (count($rs) > 0) ? $rs['YEAR'] : $year_; /*$year_paln;*/ ?>"
                                   readonly>
                            <span class="field-validation-valid" data-valmsg-for="year"
                                  data-valmsg-replace="true"></span>
                            <input type="hidden" data-val="true" data-val-required="حقل مطلوب" name="seq" id="txt_seq"
                                   value="<?php echo (count($rs) > 0) ? $rs['SEQ'] : ''; ?>" class="form-control">

                        </div>
                    </div>

                    <div class="form-group col-sm-3">
                        <label class="col-sm-2 control-label">المحور</label>
                        <div>

                            <select name="objective" data-val="true" data-val-required="حقل مطلوب" id="dp_objective"
                                    class="form-control">

                                <option></option>
                                <?php foreach ($all_objective as $row): ?>
                                    <?php if ($no_edit == 2) {
                                        if ($row['ID'] == $objective_id) { ?>
                                            <option value="<?= $row['ID'] ?>" <?PHP if ($row['ID'] == $objective_id) {
                                                echo " selected";
                                            } ?> ><?= $row['ID_NAME'] ?></option>
                                            <?php
                                        }
                                    } else { ?>
                                        <option value="<?= $row['ID'] ?>" <?php if ($row['ID'] == $objective_id) {
                                            echo " selected";
                                        } ?> ><?= $row['ID_NAME'] ?></option>
                                        <?php
                                    } ?>


                                <?php
                                endforeach; ?>


                            </select>

                            <span class="field-validation-valid" data-valmsg-for="objective"
                                  data-valmsg-replace="true"></span>


                        </div>
                    </div>


                    <div class="form-group col-sm-3">
                        <label class="col-sm-6 control-label">الهدف الاستراتيجي(العام)</label>
                        <div>

                            <select name="goal" data-val="true" data-val-required="حقل مطلوب" id="dp_goal"
                                    class="form-control">

                                <option></option>
                                <?php foreach ($goal_list as $row): ?>
                                    <?php if ($no_edit == 2) {
                                        if ($row['ID'] == $goal_select) { ?>
                                            <option value="<?= $row['ID'] ?>" <?PHP if ($row['ID'] == $goal_select) {
                                                echo " selected";
                                            } ?> ><?= $row['ID_NAME'] ?></option>
                                            <?php
                                        }
                                    } else { ?>
                                        <option value="<?= $row['ID'] ?>" <?php if ($row['ID'] == $goal_select) {
                                            echo " selected";
                                        } ?> ><?= $row['ID_NAME'] ?></option>
                                        <?php
                                    } ?>
                                <?php
                                endforeach; ?>
                            </select>

                            <span class="field-validation-valid" data-valmsg-for="goal"
                                  data-valmsg-replace="true"></span>

                        </div>
                    </div>


                    <div class="form-group col-sm-3">
                        <label class="col-sm-6 control-label">الهدف الاستراتيجي(الخاص)</label>
                        <div>

                            <select name="goal_t" id="dp_goal_t" data-val="true" data-val-required="حقل مطلوب"
                                    class="form-control">

                                <option></option>
                                <?php foreach ($goal_t_list as $row): ?>
                                    <?php if ($no_edit == 2) {
                                        if ($row['ID'] == $goal_t_select) { ?>
                                            <option value="<?= $row['ID'] ?>" <?PHP if ($row['ID'] == $goal_t_select) {
                                                echo " selected";
                                            } ?> ><?= $row['ID_NAME'] ?></option>
                                            <?php
                                        }
                                    } else { ?>
                                        <option value="<?= $row['ID'] ?>" <?php if ($row['ID'] == $goal_t_select) {
                                            echo " selected";
                                        } ?> ><?= $row['ID_NAME'] ?></option>
                                        <?php
                                    } ?>

                                <?php
                                endforeach; ?>
                            </select>

                            <span class="field-validation-valid" data-valmsg-for="goal_t"
                                  data-valmsg-replace="true"></span>

                        </div>
                    </div>


                </fieldset>
                <hr/>
                <fieldset>
                    <legend> بيانات المشروع</legend>

                    <div class="form-group col-sm-1">
                        <label class="col-sm-2 control-label">رقم المشروع</label>
                        <div>
                            <input type="text" name="activity_no" id="txt_activity_no" class="form-control" dir="rtl"
                                   value="<?php echo (count($rs) > 0) ? $rs['ACTIVITY_NO'] : ''; ?>" readonly>
                            <span class="field-validation-valid" data-valmsg-for="activity_no"
                                  data-valmsg-replace="true"></span>
                            <input type="hidden" name="project_id" class="form-control" dir="rtl" value="">
                        </div>
                    </div>


                    <div class="form-group col-sm-1">
                        <label class="col-sm-2 control-label">طبيعة المشروع</label>
                        <div>

                            <select name="type_project" data-val="true" data-val-required="حقل مطلوب"
                                    id="dp_type_project" class="form-control">
                                <!--<option></option>-->
                                <?php foreach ($type_project as $row): ?>
                                    <?php if ($row['CON_NO'] == $type_project_name) { ?>
                                        <option value="<?= $row['CON_NO'] ?>" <?PHP if ($row['CON_NO'] == $type_project_name) {
                                            echo " selected";
                                        } ?> ><?= $row['CON_NAME'] ?></option>
                                        <?php
                                    } ?>
                                <?php
                                endforeach; ?>

                            </select>
                            <span class="field-validation-valid" data-valmsg-for="type_project"
                                  data-valmsg-replace="true"></span>


                        </div>
                    </div>



                    <div class="form-group col-sm-1 hidden">
                        <label class="col-sm-2 control-label">نوع المشروع</label>
                        <div>

                            <select name="type" data-val="true" data-val-required="حقل مطلوب" id="dp_type"
                                    class="form-control">
                                <!-- <option></option> -->

                                <?php foreach ($activity_type as $row): ?>
                                    <?php if ($row['CON_NO'] == 1) { ?>
                                        <?php if ($no_edit == 2) {
                                            if ($row['CON_NO'] == $type_id) { ?>
                                                <option value="<?= $row['CON_NO'] ?>" <?PHP if ($row['CON_NO'] == $type_id) {
                                                    echo " selected";
                                                } ?> ><?= $row['CON_NAME'] ?></option>
                                                <?php
                                            }
                                        } else { ?>
                                            <option value="<?= $row['CON_NO'] ?>" <?PHP if ($row['CON_NO'] == $type_id) {
                                                echo " selected";
                                            } ?> ><?= $row['CON_NAME'] ?></option>
                                            <?php
                                        } ?>

                                        <?php
                                    }
                                endforeach; ?>

                            </select>
                            <span class="field-validation-valid" data-valmsg-for="type"
                                  data-valmsg-replace="true"></span>


                        </div>
                    </div>

                    <div class="form-group col-sm-2 hidden project_class_div">
                        <label class="col-sm-2 control-label">اسم المشروع</label>
                        <div>

                            <select name="project_id" data-val="true" data-val-required="حقل مطلوب" id="dp_project_id"
                                    class="form-control">
                                <option></option>
                                <?php foreach ($all_project as $row): ?>
                                    <?php if ($no_edit == 2) {
                                        if ($row['PROJECT_SERIAL'] == $project_id) { ?>
                                            <option value="<?= $row['PROJECT_SERIAL'] ?>" <?PHP if ($row['PROJECT_SERIAL'] == $project_id) {
                                                echo " selected";
                                            } ?> ><?= $row['PROJECT_NAME'] ?></option>
                                            <?php
                                        }
                                    } else { ?>
                                        <option value="<?= $row['PROJECT_SERIAL'] ?>" <?PHP if ($row['PROJECT_SERIAL'] == $project_id) {
                                            echo " selected";
                                        } ?> ><?= $row['PROJECT_NAME'] ?></option>
                                        <?php
                                    } ?>


                                <?php
                                endforeach; ?>

                            </select>
                            <span class="field-validation-valid" data-valmsg-for="project_id"
                                  data-valmsg-replace="true"></span>


                        </div>
                    </div>

                    <div class="form-group col-sm-4">
                        <label class="col-sm-2 control-label">اسم المشروع</label>
                        <div>
                            <input type="text" data-val="true" data-val-required="حقل مطلوب" name="activity_name1"
                                   id="txt_activity_name" class="form-control" dir="rtl"
                                   value="<?php echo (count($rs) > 0) ? $rs['ACTIVITY_NAME'] : ''; ?>" <?php if ($no_edit == 2) echo $readonly; ?> >
                            <span class="field-validation-valid" data-valmsg-for="activity_name"
                                  data-valmsg-replace="true"></span>
                        </div>
                    </div>
                    <div class="form-group col-sm-1 <?= $hidden_class; ?>">
                        <label class="col-sm-2 control-label">التكاليف الإضافية</label>
                        <div>

                            <select name="class_name_id" data-val="true" data-val-required="حقل مطلوب"
                                    id="dp_class_name" class="form-control">
                                <option></option>
                                <?php foreach ($activity_class as $row): ?>
                                    <?php /*if ($no_edit == 2)
                {
                    if ($row['CON_NO'] == $class_name)
                    { ?>
                        <option value="<?=$row['CON_NO'] ?>" <?PHP if ($row['CON_NO'] == $class_name)
                        {
                            echo " selected";
                        } ?> ><?=$row['CON_NAME'] ?></option>
                    <?php
                    }
                }
                else
                {*/
                                    if ($row['CON_NO'] != 1) {
                                        ?>
                                        <option value="<?= $row['CON_NO'] ?>" <?PHP if ($row['CON_NO'] == $class_name) {
                                            echo " selected";
                                        } ?> ><?= $row['CON_NAME'] ?></option>
                                        <?php
                                        //}
                                    } ?>

                                <?php
                                endforeach; ?>

                            </select>
                            <span class="field-validation-valid" data-valmsg-for="class_name_id"
                                  data-valmsg-replace="true"></span>


                        </div>
                    </div>
                    <div class="form-group col-sm-1">
                        <label class="col-sm-2 control-label">طبيعة الخطة</label>
                        <div>

                            <select name="type_emp" data-val="true" data-val-required="حقل مطلوب"
                                    id="dp_type_emp" class="form-control">
                                <!--<option></option>-->
                                <?php foreach ($dp_type_emp as $row): ?>

                                        <option value="<?= $row['CON_NO'] ?>" <?php if ($row['CON_NO'] == $type_emp) {
                                            echo " selected";
                                        } ?> ><?= $row['CON_NAME'] ?></option>

                                <?php
                                endforeach; ?>

                            </select>
                            <span class="field-validation-valid" data-valmsg-for="type_emp"
                                  data-valmsg-replace="true"></span>


                        </div>
                    </div>
                    <br>
                    <div class="form-group col-sm-2 <?= $hidden; ?>">
                        <label class="control-label">إجمالي تكلفة المشروع التقديرية</label>
                        <?php
                        if (count($rs) > 0) {
                            if ($rs['STRATGIC_TOTAL_PRICE'] == '') $STRATGIC_TOTAL_PRICE = 0;
                            else $STRATGIC_TOTAL_PRICE = $rs['STRATGIC_TOTAL_PRICE'];
                        }

                        ?>
                        <div>
                            <input data-val="true" data-val-required="حقل مطلوب" class="form-control"
                                   name="stratgic_total_price" id="stratgic_total_price_id"
                                   value="<?php echo (count($rs) > 0) ? $STRATGIC_TOTAL_PRICE : 0; ?>" <?php echo $readonly; ?>/>
                            <span class="field-validation-valid" data-valmsg-for="stratgic_total_price"
                                  data-valmsg-replace="true"></span>
                        </div>
                    </div>

                    <div class="form-group col-sm-2 <?= $hidden; ?>">
                        <label class="control-label">تكلفة المشروع السنوية التقديرية</label>
                        <?php
                        if (count($rs) > 0) {
                            if ($rs['STRATGIC_BADGET_PRICE'] == '') $STRATGIC_BADGET_PRICE = 0;
                            else $STRATGIC_BADGET_PRICE = $rs['STRATGIC_BADGET_PRICE'];
                        }

                        ?>
                        <div>
                            <input data-val="true" data-val-required="حقل مطلوب" class="form-control"
                                   name="stratgic_badget_price" id="stratgic_badget_price_id"
                                   value="<?php echo (count($rs) > 0) ? $STRATGIC_BADGET_PRICE : 0; ?>" <?php echo $readonly; ?>/>
                            <span class="field-validation-valid" data-valmsg-for="stratgic_badget_price"
                                  data-valmsg-replace="true"></span>
                        </div>
                    </div>

                    <div class="form-group col-sm-1">
                        <label class="control-label">تكلفة المشروع السنوية</label>
                        <?php
                        if (count($rs) > 0) {

                            if ($rs['TOTAL_PRICE_BUDGET'] == '') $PRINT_TOTAL_PRICE_BUDGET = 0;
                            else $PRINT_TOTAL_PRICE_BUDGET = $rs['TOTAL_PRICE_BUDGET'];

                        }

                        ?>
                        <div>
                            <input data-val="true" data-val-required="حقل مطلوب" class="form-control" name="total_price"
                                   id="total_price_id"
                                   value="<?php echo (count($rs) > 0) ? $PRINT_TOTAL_PRICE_BUDGET : 0; ?>" <?php echo $readonly; ?>/>
                            <span class="field-validation-valid" data-valmsg-for="total_price"
                                  data-valmsg-replace="true"></span>
                        </div>
                    </div>

                    <div class="form-group col-sm-1">
                        <label class="control-label">ايراد المشروع السنوية</label>
                        <?php
                        if (count($rs) > 0) {
                            if ($rs['INCOME'] == '') $PRINT_INCOME = 0;
                            else $PRINT_INCOME = $rs['INCOME'];
                        }

                        ?>
                        <div>
                            <input data-val="true" data-val-required="حقل مطلوب" class="form-control" name="income"
                                   style="text-align:center;font-weight: bold;" id="income"
                                   value="<?php echo (count($rs) > 0) ? $PRINT_INCOME : 0; ?>" <?php echo $readonly; ?>/>
                            <span class="field-validation-valid" data-valmsg-for="income"
                                  data-valmsg-replace="true"></span>
                        </div>
                    </div>


                    <div class="form-group col-sm-2 <?= $hidden; ?>">
                        <label class="control-label">إجمالي ايراد المشروع</label>
                        <div>
                            <input data-val="true" data-val-required="حقل مطلوب" class="form-control"
                                   name="stratgic_income" id="stratgic_income"
                                   value="<?php echo (count($rs) > 0) ? $rs['STRATGIC_INCOME'] : 0; ?>" <?php echo $readonly; ?>/>
                            <span class="field-validation-valid" data-valmsg-for="stratgic_income"
                                  data-valmsg-replace="true"></span>
                        </div>
                    </div>


                </fieldset>
                <hr/>
                <fieldset class="hidden">
                    <legend>بيانات المستهدف</legend>
                    <div class="details">
                        <?php echo modules::run("$MODULE_NAME/$TB_NAME/$DET_TB_NAME4", (count($rs) > 0) ? $rs['SEQ'] : 0); ?>
                    </div>
                    <!-- <div class="form-group col-sm-1">
        <label class="col-sm-2 control-label">المستهدف</label>
        <div>
            <input type="text"  data-val="true"  data-val-required="حقل مطلوب"   name="target" id="txt_target" class="form-control" dir="rtl" value="<?php echo (count($rs) > 0) ? $rs['TARGET'] : ''; ?>" >
            <span class="field-validation-valid" data-valmsg-for="target" data-valmsg-replace="true"></span>

        </div>
    </div> -->

                    <!-- <div class="form-group col-sm-1">
        <label class="col-sm-2 control-label">الوحدة</label>
        <div>

            <select name="unit" data-val="true"  data-val-required="حقل مطلوب"  id="dp_unit" class="form-control" >
                <option></option>
                <?php foreach ($unit as $row): ?>
                    <option value="<?= $row['CON_NO'] ?>" <?PHP if ($row['CON_NO'] == ((count($rs) > 0) ? $rs['UNIT'] : 0)) {
                        echo " selected";
                    } ?> >
                        <?= $row['CON_NAME'] ?></option>
                <?php
                    endforeach; ?>

            </select>
            <span class="field-validation-valid" data-valmsg-for="unit" data-valmsg-replace="true"></span>



        </div>
    </div> -->

                    <!-- <div class="form-group col-sm-1">
        <label class="col-sm-2 control-label">الصيغة</label>
        <div>

            <select name="scale" data-val="true"  data-val-required="حقل مطلوب"  id="dp_scale" class="form-control" >
                <option></option>
                <?php foreach ($scale as $row): ?>
                    <option value="<?= $row['CON_NO'] ?>" <?PHP if ($row['CON_NO'] == ((count($rs) > 0) ? $rs['SCALE'] : 0)) {
                        echo " selected";
                    } ?> >
                        <?= $row['CON_NAME'] ?></option>
                <?php
                    endforeach; ?>

            </select>
            <span class="field-validation-valid" data-valmsg-for="scale" data-valmsg-replace="true"></span>



        </div>
    </div> -->


                </fieldset>
                <hr/>
                <fieldset>

                    <!----------------------------------  ملاحظات المعتمد ---------------------------------------------->
                    <legend>وصف المشروع/الملاحظات</legend>
                    <div class="form-group col-sm-11">
                        <label class="control-label">ملاحظات</label>
                        <div>
                            <input type="text" data-val="false" data-val-required="حقل مطلوب" name="notes"
                                   value="<?php echo (count($rs) > 0) ? $rs['NOTES'] : ''; ?>" id="txt_notes"
                                   class="form-control valid" <?php if ($no_edit == 2) echo $readonly; ?>>
                            <span class="field-validation-valid" data-valmsg-for="notes"
                                  data-valmsg-replace="false"></span>
                        </div>
                    </div>

                </fieldset>
                <hr/>
                <fieldset class="fin_class <?= $fin_hidden; ?>">
                    <legend> مصدر التمويل</legend>


                    <div class="form-group col-sm-1">
                        <label class="col-sm-2 control-label">مصدر التمويل</label>
                        <div>

                            <select name="finance" data-val="true" data-val-required="حقل مطلوب" id="dp_finance"
                                    class="form-control">
                                <!-- <option></option> -->
                                <?php foreach ($finance_type as $row): ?>
                                    <?php if ($class_no_edit == 2) {
                                        if ($row['CON_NO'] == $fin_id) {
                                            ?>

                                            <option value="<?= $row['CON_NO'] ?>" <?PHP if ($row['CON_NO'] == $fin_id) {
                                                echo " selected";
                                            } ?> ><?= $row['CON_NAME'] ?></option>
                                            <?php
                                        }
                                    } else { ?>
                                        <option value="<?= $row['CON_NO'] ?>" <?PHP if ($row['CON_NO'] == $fin_id) {
                                            echo " selected";
                                        } ?> ><?= $row['CON_NAME'] ?></option>
                                        <?php
                                    } ?>
                                <?php
                                endforeach; ?>

                            </select>
                            <span class="field-validation-valid" data-valmsg-for="finance"
                                  data-valmsg-replace="true"></span>


                        </div>
                    </div>


                    <div class="form-group col-sm-3 project_class_div hidden">
                        <label class="control-label">التصنيف</label>
                        <div>
                            <input readonly data-val="true" data-val-required="حقل مطلوب" class="form-control"
                                   name="finance_class" id="finance_class_id"
                                   value="<?php echo (count($rs) > 0) ? $rs['PROJECT_TEC_TYPE_NAME'] : @$project_info['PROJECT_TEC_TYPE_NAME']; ?>" <?php if ($class_no_edit == 2) echo $readonly; ?>/>
                            <span class="field-validation-valid" data-valmsg-for="finance_class"
                                  data-valmsg-replace="true"></span>
                        </div>
                    </div>

                    <div class="form-group col-sm-3">
                        <label class="control-label">اسم مصدر التمويل</label>
                        <div>
                            <input data-val="true" data-val-required="حقل مطلوب" class="form-control"
                                   name="finance_name" id="finance_name_id"
                                   value="<?php echo (count($rs) > 0) ? $rs['FINANCE_NAME'] : $fin_name; ?>" <?php if ($class_no_edit == 2) echo $readonly; ?> />
                            <span class="field-validation-valid" data-valmsg-for="finance_name"
                                  data-valmsg-replace="true"></span>
                        </div>
                    </div>

                </fieldset>
                <hr/>

                <?php
                if ($type_project_name == 1) {

                    ?>
                    <fieldset class="hidden">
                        <legend>جهة التنفيذ الرئيسية (مدخل الخطة)</legend>

                        <div class="form-group col-sm-2">
                            <label class="col-sm-2 control-label">الفرع</label>
                            <div>
                                <select name="branch_exe_id" data-val="true" data-val-required="حقل مطلوب"
                                        id="dp_branch_exe_id" class="form-control">

                                    <?php foreach ($exe_branches as $row): ?>
                                        <?php if ($no_edit == 2 || $no_edit_year == 2) {
                                            if ($row['NO'] == $branch_exe_id) { ?>
                                                <option value="<?= $row['NO'] ?>" <?PHP if ($row['NO'] == $branch_exe_id) {
                                                    echo " selected";
                                                } ?> ><?= $row['NAME'] ?></option>
                                                <?php
                                            }
                                        } else { ?>
                                            <option value="<?= $row['NO'] ?>" <?PHP if ($row['NO'] == $branch_exe_id) {
                                                echo " selected";
                                            } ?> ><?= $row['NAME'] ?></option>
                                            <?php
                                        } ?>


                                    <?php
                                    endforeach; ?>

                                </select>

                                <span class="field-validation-valid" data-valmsg-for="branch_exe_id"
                                      data-valmsg-replace="true"></span>


                            </div>
                        </div>

                        <div class="form-group col-sm-2">
                            <label class="col-sm-2 control-label">الادارة</label>
                            <div>

                                <select name="manage_exe_id" data-val="true" data-val-required="حقل مطلوب"
                                        id="dp_manage_exe_id" class="form-control">

                                    <?php foreach ($b as $row): ?>
                                        <?php if ($no_edit == 2) {
                                            if ($row['ST_ID'] == $manage_select_exe) { ?>
                                                <option value="<?= $row['ST_ID'] ?>" <?PHP if ($row['ST_ID'] == $manage_select_exe) {
                                                    echo " selected";
                                                } ?> ><?= $row['ST_NAME'] ?></option>
                                                <            <?php
                                            }
                                        } else { ?>
                                            <option value="<?= $row['ST_ID'] ?>" <?PHP if ($row['ST_ID'] == $manage_select_exe) {
                                                echo " selected";
                                            } ?> ><?= $row['ST_NAME'] ?></option>
                                            <?php
                                        } ?>


                                    <?php
                                    endforeach; ?>


                                </select>

                                <span class="field-validation-valid" data-valmsg-for="manage_exe_id"
                                      data-valmsg-replace="true"></span>

                            </div>
                        </div>

                        <div class="form-group col-sm-2">
                            <label class="col-sm-2 control-label">الدائرة</label>
                            <div>

                                <select name="cycle_exe_id" id="dp_cycle_exe_id" class="form-control">


                                    <?php foreach ($cycle_exe as $row): ?>
                                        <?php if ($no_edit == 2) {
                                            if ($row['ST_ID'] == $cycle_select_exe) { ?>
                                                <option value="<?= $row['ST_ID'] ?>" <?PHP if ($row['ST_ID'] == $cycle_select_exe) {
                                                    echo " selected";
                                                } ?> ><?= $row['ST_NAME'] ?></option>
                                                <?php
                                            }
                                        } else { ?>
                                            <option value="<?= $row['ST_ID'] ?>" <?PHP if ($row['ST_ID'] == $cycle_select_exe) {
                                                echo " selected";
                                            } ?> ><?= $row['ST_NAME'] ?></option>
                                            <?php
                                        } ?>


                                    <?php
                                    endforeach; ?>
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
                                    <?php foreach ($dep_exe as $row): ?>
                                        <?php if ($no_edit == 2) {
                                            if ($row['ST_ID'] == $dep_select_exe) { ?>
                                                <option value="<?= $row['ST_ID'] ?>" <?PHP if ($row['ST_ID'] == $dep_select_exe) {
                                                    echo " selected";
                                                } ?> ><?= $row['ST_NAME'] ?></option>
                                                <?php
                                            }
                                        } else { ?>
                                            <option value="<?= $row['ST_ID'] ?>" <?PHP if ($row['ST_ID'] == $dep_select_exe) {
                                                echo " selected";
                                            } ?> ><?= $row['ST_NAME'] ?></option>
                                            <?php
                                        } ?>


                                    <?php
                                    endforeach; ?>
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
                <?php } ?>

                <!-- <fieldset>
    <legend>جهة التفيذ المساندة</legend>
    <div class="form-group col-sm-2">
        <label class="col-sm-2 control-label">الفرع</label>
        <div>

            <select name="branch_follow_id" data-val="true"  data-val-required="حقل مطلوب"  id="dp_branch_follow_id" class="form-control">
                <option></option>
                <?php foreach ($branches_follow as $row): ?>
                    <option value="<?= $row['NO'] ?>" <?PHP if ($row['NO'] == $branch_follow_id) {
                    echo " selected";
                } ?> ><?= $row['NAME'] ?></option>


                <?php
                endforeach; ?>

            </select>
            <span class="field-validation-valid" data-valmsg-for="branch_follow_id" data-valmsg-replace="true"></span>


        </div>
    </div>

    <div class="form-group col-sm-2">
        <label class="col-sm-2 control-label">الادارة</label>
        <div>

            <select name="manage_follow_id" data-val="true"  data-val-required="حقل مطلوب"  id="dp_manage_follow_id" class="form-control">
                <option></option>
                <?php foreach ($b_follow as $row): ?>
                    <option value="<?= $row['ST_ID'] ?>" <?PHP if ($row['ST_ID'] == $manage_select_follow) {
                    echo " selected";
                } ?>><?= $row['ST_NAME'] ?></option>


                <?php
                endforeach; ?>


            </select>
            <span class="field-validation-valid" data-valmsg-for="manage_follow_id" data-valmsg-replace="true"></span>


        </div>
    </div>

    <div class="form-group col-sm-2">
        <label class="col-sm-2 control-label">الدائرة</label>
        <div>

            <select name="cycle_follow_id" id="dp_cycle_follow_id" class="form-control">

                <option></option>
                <?php foreach ($cycle_follow as $row): ?>
                    <option value="<?= $row['ST_ID'] ?>" <?PHP if ($row['ST_ID'] == $cycle_select_follow) {
                    echo " selected";
                } ?>><?= $row['ST_NAME'] ?></option>


                <?php
                endforeach; ?>
            </select>

        </div>
    </div> -->

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
                <!-- <div class="form-group col-sm-2">
        <label class="col-sm-2 control-label">القسم</label>
        <div>

            <select name="department_follow_id" id="dp_department_follow_id" class="form-control">
                <option></option>
                <?php foreach ($dep_follow as $row): ?>
					<?php if ($no_edit == 2) {
                    if ($row['ST_ID'] == $dep_select_follow) { ?>
                        <option value="<?= $row['ST_ID'] ?>" <?PHP if ($row['ST_ID'] == $dep_select_follow) {
                        echo " selected";
                    } ?> ><?= $row['ST_NAME'] ?></option>
                    <?php
                    }
                } else { ?>
                    <option value="<?= $row['ST_ID'] ?>" <?PHP if ($row['ST_ID'] == $dep_select_follow) {
                    echo " selected";
                } ?> ><?= $row['ST_NAME'] ?></option>
                <?php
                } ?>

                <?php
                endforeach; ?>

            </select>

        </div>
    </div> -->

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
                <!--

                </fieldset>-->


                <hr/>
                <fieldset <?php echo $hidden; ?>>
                    <legend>مدة تنفيذ الخطة على مدار الخطة الإستراتيجية</legend>

                    <div class="form-group col-sm-1">
                        <label class="col-sm-2 control-label">من عام</label>
                        <div>
                            <input type="text" data-val="true" data-val-required="حقل مطلوب" name="from_year"
                                   id="txt_from_year" class="form-control" dir="rtl"
                                   value="<?php echo (count($rs) > 0) ? $rs['FROM_YEAR'] : ''; ?>" <?php if ($no_edit == 2) echo $readonly; ?>>
                            <span class="field-validation-valid" data-valmsg-for="from_year"
                                  data-valmsg-replace="true"></span>

                        </div>
                    </div>

                    <div class="form-group col-sm-1">
                        <label class="col-sm-2 control-label">إلى عام</label>
                        <div>
                            <input type="text" data-val="true" data-val-required="حقل مطلوب" name="to_year"
                                   id="txt_to_year" class="form-control" dir="rtl"
                                   value="<?php echo (count($rs) > 0) ? $rs['TO_YEAR'] : ''; ?>" <?php if ($no_edit == 2) echo $readonly; ?> >
                            <span class="field-validation-valid" data-valmsg-for="to_year"
                                  data-valmsg-replace="true"></span>

                        </div>
                    </div>

                </fieldset>

                <hr/>
                <fieldset>
                    <legend>مدة التنفيذ علىى 12 شهر</legend>

                    <div class="form-group col-sm-2">
                        <label class="control-label">مدة تنفيذ المشروع بالأشهر</label>
                        <div>
                            <input data-val="true" data-val-required="حقل مطلوب" class="form-control" name="month_count"
                                   id="month_count" value="0" readonly/>
                            <span class="field-validation-valid" data-valmsg-for="month_count"
                                  data-valmsg-replace="true"></span>
                        </div>
                    </div>

                    <div class="form-group col-sm-12">

                        <div>


                            <?php for ($i = 1; $i <= 12; $i++): ?>
                                <?php
                                $monthes = Modules::run("$MODULE_NAME/$TB_NAME/$monthes_url", (count($rs) > 0) ? $rs['SEQ'] : 0, $i);
                                $m_array = array();
                                $check_month = 0;
                                foreach ($monthes as $value) {
                                    if (count($monthes) <= $i) {
                                        $check_month = $value["MONTH"];
                                    } else $check_month = 0;
                                }

                                ?>

                                <label class="checkbox-inline">
                                    <input type="checkbox" data-val="true" class="checkboxAll"
                                           data-val-required="حقل مطلوب" name="exe_time[]" id="txt_exe_time_<?= $i
                                    ?>" value="<?= $i
                                    ?>" <?PHP if ($i == $check_month) {
                                        echo " checked";
                                    } ?>><?= months($i) ?>
                                    <span class="field-validation-valid" data-valmsg-for="exe_time[]"
                                          data-valmsg-replace="true"></span>
                                </label>

                            <?php
                            endfor; ?>
                            <input type="checkbox" id="selectAll" class="css-checkbox" name="selectAll"/>
                            اختر الكل<br>
                            <span class="field-validation-valid" data-valmsg-for="to_month"
                                  data-valmsg-replace="true"></span>
                        </div>
                    </div>


                </fieldset>
                <?php if ($achive > 0) { ?>
                    <hr/>
                    <fieldset>
                        <legend>متابعة الانجاز</legend>
                        <div class="form-group col-sm-12">
                            <?php echo modules::run("$MODULE_NAME/$TB_NAME/$ACHIVE_TB_NAME", (count($rs) > 0) ? $rs['SEQ'] : 0); ?>
                        </div>

                    </fieldset>
                    <?php
                } ?>
                <hr/>
                <fieldset>
                    <legend>جهة التنفيذ الرئيسية و الشريكة</legend>

                    <div class="details">
                        <?php echo modules::run("$MODULE_NAME/$TB_NAME/$DET_TB_NAME2", (count($rs) > 0) ? $rs['SEQ'] : 0); ?>
                    </div>

                </fieldset>
                <hr/>
                <?php if (!$isCreate) { ?>
                    <fieldset>
                        <legend>المهام الفرعية</legend>

                        <div class="details">
                            <?php echo modules::run("$MODULE_NAME/$TB_NAME/$DET_TB_NAME3", (count($rs) > 0) ? $rs['SEQ'] : 0, ((count($rs) > 0) ? $rs['ADOPT'] : '')); /*modules::run("$MODULE_NAME/$TB_NAME/$DET_TB_NAME1", (count($rs)>0)?$rs['SEQ']:0,((count($rs)>0)? $rs['ADOPT'] : '' ));*/ ?>
                        </div>

                    </fieldset>
                    <div style="clear: both"></div>

                    <input type="hidden" id="h_data_search"/>

                    <hr/>
                    <fieldset class="items_class <?= $hidden_item_class ?> hidden">
                        <legend>نفقات و ايرادات المشروع</legend>

                        <?php echo modules::run("$MODULE_NAME/$TB_NAME/$DET_TB_NAME5", (count($rs) > 0) ? $rs['SEQ'] : 0); /*modules::run("$MODULE_NAME/$TB_NAME/$DET_TB_NAME1", (count($rs)>0)?$rs['SEQ']:0,((count($rs)>0)? $rs['ADOPT'] : '' ));*/ ?>
                    </fieldset>


                    <hr>
                    <div style="clear: both;">
                        <span id="quote"><?= modules::run('attachments/attachment/index', $rs['SEQ'], 'ACTIVITIES_PLAN'); ?></span>
                    </div>
                    <?php
                } ?>
                <div class="modal-footer">
                    <?php
                    if (HaveAccess($post_url)) {

                        if ($isCreate) {

                            /*if (  HaveAccess($post_url)  && ($isCreate || ( $rs['ADOPT']==1 ) )  )*/
                            ?>
                            <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                            <?php
                        } else {
                            if ($rs['ADOPT'] == 1) {
                                if ($rs['TYPE_PROJECT'] == 1) {
                                    if ((($rs['INSERT_USER'] == $this
                                                ->user
                                                ->id) ? 1 : 0) || (($rs['UPDATE_USER'] == $this
                                                ->user
                                                ->id) ? 1 : 0)) {
                                        ?>
                                        <button type="submit" data-action="submit" class="btn btn-primary">حفظ
                                            البيانات
                                        </button>
                                        <?php
                                        if (HaveAccess($adopt_url) && (!$isCreate and ((count($rs) > 0) ? $rs['ADOPT'] == 1 : '')) && $rs['YEAR'] == 2022) {
                                            ?>
                                            <button type="button" id="btn_adopt" onclick='javascript:return_adopt(1);'
                                                    class="btn btn-success">اعتماد
                                            </button>
                                        <?php }
                                    }
                                } else {

                                    if ($b[0]['TYPE'] == 1) {
                                        if ($b[0]['ST_ID'] == $rs['MANAGE_EXE_ID']) {
                                            ?>
                                            <button type="submit" data-action="submit" class="btn btn-primary">حفظ
                                                البيانات
                                            </button>
                                            <?php
                                            if (HaveAccess($adopt_url) && (!$isCreate and ((count($rs) > 0) ? $rs['ADOPT'] == 1 : '')) && $rs['YEAR'] == 2022) {
                                                ?>
                                                <button type="button" id="btn_adopt"
                                                        onclick='javascript:return_adopt(1);' class="btn btn-success">
                                                    اعتماد
                                                </button>
                                            <?php }
                                        }
                                    } else {

                                        if ($cycle_exe[0]['ST_ID'] == $rs['CYCLE_EXE_ID']) {
                                            ?>
                                            <button type="submit" data-action="submit" class="btn btn-primary">حفظ
                                                البيانات
                                            </button>
                                            <?php
                                            if (HaveAccess($adopt_url) && (!$isCreate and ((count($rs) > 0) ? $rs['ADOPT'] == 1 : '')) && $rs['YEAR'] == 2022) {
                                                ?>
                                                <button type="button" id="btn_adopt"
                                                        onclick='javascript:return_adopt(1);' class="btn btn-success">
                                                    اعتماد
                                                </button>
                                            <?php }
                                        }
                                    }

                                }

                            }
                        }
                    } ?>

                    <?php
                    /*
                    if (HaveAccess($adopt_url) && (!$isCreate and ((count($rs) > 0) ? $rs['ADOPT'] == 1 : ''))){
                        ?>
                        <button type="button" id="btn_adopt" onclick='javascript:return_adopt(1);' class="btn btn-success">اعتماد  </button>
                    <?php }
                    */
                    ?>

                    <?php
                    /*
                    if (HaveAccess($adopt_url) && (!$isCreate and in_array($rs['ADOPT_USER'], array_column($HAVE_EMP, 'EMPLOYEE_NO')) )){

                        ?>
                        <button type="button" id="btn_adopt" onclick='javascript:return_adopt(1);' class="btn btn-success">اعتماد  </button>
                    <?php }
                    */
                    ?>

                </div>

            </div>


        </form>
    </div>


    <?php
    $scripts = <<<SCRIPT
<script type="text/javascript">
var count=[];
var f_count=[];
var exe_time =0;
var array=[];

if($("#dp_class_name").val()==1)
{

$("#dp_class_name").select2("readonly", true);
$("#total_price_id").prop("readonly", true);
$("#dp_finance").select2("readonly", true);
$("#finance_class_id").prop("readonly", true);
$("#finance_name_id").prop("readonly", true);
$("#dp_branch_exe_id").select2("readonly", true);

$('input[name="exe_time[]"]').each(function (i) {
$(this).attr('disabled', 'disabled');
});


$("#txt_activity_name").prop("readonly", true);




}
$('#dp_type_project,#dp_unit,#dp_scale,#dp_type_emp').select2().on('change',function(){

       //  checkBoxChanged();

        });
$('#dp_type').select2().on('change',function(){
/*$('select[name="planning_dir[]"],select[name="branch[]"],select[name="manage_id[]"],select[name="cycle_id[]"],input[name="activity_name[]"],select[name="main_from_month[]"],select[name="main_to_month[]"],input[name="weight[]"]').each(function (i) {

if($('#dp_type').val()!=3)
{
alert($('#dp_type').val());
$(this).attr('data-val','false');

}
else
{
$(this).attr('data-val','true');
}

});*/

        });

   $('#dp_project_id').select2().on('change',function(){
 var project_id_ser =$(this).val();


       //  alert(ser_v);
       if (project_id_ser!='')
       {
       //  checkBoxChanged();
       get_data('{$get_project_url}',{id:project_id_ser},function(data){
    if (data.length == 1){
    var item= data[0];
$("#dp_class_name").select2('val',item.ACTIVE_CLASS);
$("#dp_class_name").select2("readonly", true);
$("#total_price_id").val(item.TOTAL_PRICE);
$("#total_price_id").prop("readonly", true);
$("#dp_finance").select2('val',item.PROJECT_TYPE_DON);
$("#dp_finance").select2("readonly", true);
$("#finance_class_id").val(item.PROJECT_TEC_TYPE_NAME);
$("#finance_class_id").prop("readonly", true);
if(item.DONOR_NAME==null)
item.DONOR_NAME='';
$("#finance_name_id").val('('+item.PROJECT_TYPE_DON_NAME+')'+' '+item.DONOR_NAME);
$("#finance_name_id").prop("readonly", true);
$("#dp_branch_exe_id").select2('val',item.BRANCH);
$("#dp_branch_exe_id").select2("readonly", true);
$('input[name="exe_time[]"]').each(function (i) {
$(this).prop('checked', false);
$(this).attr('disabled', 'disabled');
});

$('input[name="exe_time[]"]').each(function (i) {
i=i+1;
if(i==item.MONTH)
{
$(this).prop('checked', true);
$(this).attr('disabled', false);
exe_time++;
display_month(exe_time);
}
});
$("#txt_activity_name").val(item.PROJECT_NAME);
$("#txt_activity_name").prop("readonly", true);


 get_data('{$manage_exe_branch}',{no: $('#dp_branch_exe_id').val()},function(data){
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





                }else{
$("#dp_class_name").select2('val','');
$("#total_price_id").val('');
$("#dp_finance").select2('val','');
$("#finance_class_id").val('');
$("#finance_name_id").val('()');
$("#dp_branch_exe_id").select2('val','');
$('input[name="exe_time[]"]').each(function (i) {
$(this).prop('checked', false);
});
$("#txt_activity_name").val('');
                }
        });
        }

        });
         $('#dp_class_name').select2().on('change',function(){

       //  checkBoxChanged();
       if($(this).val()==3 )
       {
       $('#total_price_id').val(0);
       $(".project_class_div").addClass("hidden");

       }
       else
       {
       if($(this).val()==1)
       {

$(".project_class_div").removeClass("hidden");

       }
       else
       {

$(".project_class_div").addClass("hidden");
if($(this).val()==2)
{
       $('#total_price_id').val($('#total_price').text());
	   $(".items_class").removeClass("hidden");
}

       }

       }

        });






$('#total_price_id').change(function () {
    if($('#dp_class_name').val()==3)

        {
        $(this).val(0);
		$(".items_class").addClass("hidden");
		//$(".items_class_tb").empty();
        }
        else
        {
        if($(this).val()==0)
        {
         danger_msg('لابد من ادخال تكلفة المشروع أكبر من صفر!!');
            $('#total_price_id').val($('#total_price').text());
	   $(".items_class").removeClass("hidden");
}
        }


         });

     $('#total_price_id').keyup(function () {
 if($('#dp_class_name').val()==3)

        {
        $(this).val(0);
		$(".items_class").addClass("hidden");
		//$(".items_class_tb").empty();
        }
        else
        {
        if($(this).val()==0)
        {
         danger_msg('لابد من ادخال تكلفة المشروع أكبر من صفر!!');
            $('#total_price_id').val($('#total_price').text());
	   $(".items_class").removeClass("hidden");
}
        }

      });

	  $('#dp_class_name').change(function () {
    if($('#dp_class_name').val()==3)

        {

		$( ".fin_class" ).addClass( "hidden" );
		$("#dp_finance").select2('val','');
		$('#finance_name_id').val('');
		$(".items_class").addClass("hidden");
		//$(".items_class_tb").empty();
        }
        else
        {
       $( ".fin_class" ).removeClass( "hidden" );
	   $(".items_class").removeClass("hidden");
	       $('#total_price_id').val($('#total_price').text());
	  
        }


         });




         $('#txt_year').on('change',function(){


             get_data('{$get_all_goal}',{no:0,year:$('#txt_year').val()},function(data){

            $('#dp_objective').html('');
             $('#dp_objective').append('<option></option>');
             $("#dp_objective").select2('val','');
             $("#dp_goal").select2('val','');
             $("#dp_goal_t").select2('val','');
            $.each(data,function(index, item)
            {
            $('#dp_objective').append('<option value=' + item.ID + '>' + item.ID_NAME + '</option>');
            });
            });


             get_data('{$public_get_projects_url}',{year:$('#txt_year').val()},function(data){

            $('#dp_project_id').html('');
             $('#dp_project_id').append('<option></option>');
             $("#dp_project_id").select2('val','');
            $.each(data,function(index, item)
            {
            $('#dp_project_id').append('<option value=' + item.PROJECT_SERIAL + '>' + item.PROJECT_NAME + '</option>');
            });
            });





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


   $('#dp_finance').select2().on('change',function(){

       //  checkBoxChanged();
	   if($(this).val()==1)

        {
        		$('#finance_name_id').val('شركة توزيع كهرباء محافظات غزة');

        }
        else
        {
				$('#finance_name_id').val('');

        }

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
var flag=1;
        /*var flag=0;
        if($('#dp_type').val()==3)
        {

if($('#total_weight').text()!=100)
{
 danger_msg('لم يتم الحفظ يجب ان يكون المجوع الكلي للاوزان = 100');
 flag=0;
}
else
{
flag=1;
}
}
else
{*/
if($('#dir_total_weghit').text()!=0)
{
if($('#dir_total_weghit').text()!=100)
{
 danger_msg('لم يتم الحفظ يجب ان يكون المجوع الكلي للاوزان = 100');
 flag=0;
}
else
{
flag=1;
}
}
/*else
{
flag=1;
}

/*}
*/



if(flag)
{

        if(confirm('هل تريد الحفظ  ؟!')){
       /* $('select[name="planning_dir[]"],select[name="branch[]"],select[name="manage_id[]"],select[name="cycle_id[]"],input[name="activity_name[]"],select[name="main_from_month[]"],select[name="main_to_month[]"],input[name="weight[]"]').each(function (i) {

if($('#dp_type').val()!=3)
{
$(this).attr('data-val','false');
$('span').attr('data-valmsg-replace','false');
$('span').removeClass( "field-validation-valid" );

}
else
{
$(this).attr('data-val','true');
$('span').attr('data-valmsg-replace','true');
$('span').addClass( "field-validation-valid" );
}

});*/
           // $(this).attr('disabled','disabled');
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
       
}
});

/*function delete_row_del(id, branch_name) {
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

}*/
function delete_row_del(id, branch_name) {
  if (confirm(' هل تريد بالتأكيد حذف المشروع '+ branch_name +' ؟!!')) {

        var values = {id: id};
        get_data('{$delete_url_act_details}', values, function (data) {

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

/*calcall();

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




*/
/*$('select[name="planning_dir[]"],select[name="branch[]"],select[name="manage_id[]"],select[name="cycle_id[]"],input[name="activity_name[]"],select[name="main_from_month[]"],select[name="main_to_month[]"],input[name="weight[]"]').each(function (i) {

if($('#dp_type').val()!=3)
{

$(this).attr('data-val','false');

}
else
{
$(this).attr('data-val','true');
}

});*/
 calcall();
 //calcallPrice();
 calcalldir();

reBind_pram(0);

function calcalldir() {

    var dir_total_weghit = 0;

    $('input[name="dir_weight[]"]').each(function () {

        var dir_weight = $(this).closest('tr').find('input[name="dir_weight[]"]').val();
        dir_total_weghit += Number(dir_weight);
        if(Number(dir_total_weghit)>100)
                {
                    danger_msg('لقد تجاوزت الحد المسموح');
                    $(this).closest('tr').find('input[name="dir_weight[]"]').val(0);
                }
                else

        $('#dir_total_weghit').text(isNaNVal(Number(dir_total_weghit)));
    });



}

function calcall() {

    var total_weight = 0;

    $('input[name="weight[]"]').each(function () {

        var weight = $(this).closest('tr').find('input[name="weight[]"]').val();
        total_weight += Number(weight);
        if(Number(total_weight)>100)
                {
                    danger_msg('لقد تجاوزت الحد المسموح');
                    $(this).closest('tr').find('input[name="weight[]"]').val(0);
                }
                else

        $('#total_weight').text(isNaNVal(Number(total_weight)));
    });



}
function calcallPrice() {

    var total_price = 0;

    $('input[name="price[]"]').each(function () {
        var price = $(this).closest('tr').find('input[name="price[]"]').val();
		var request_amount=$(this).closest('tr').find('input[name="request_amount[]"]').val();
        total_price += Number(price)*Number(request_amount);


    });
$('#total_price').text(isNaNVal(Number(total_price)));
$('#total_price_id').val(isNaNVal(Number(total_price)));



}
function reBind_pram(cnt){

$('table tr td .select2-container').remove();


		        $('input[name="class_name[]"]').click("focus",function(e){
var amount= $(this).closest('tr').find('input[name="request_amount[]"]');
				amount.val('');
                _showReport('$select_items_url/'+$(this).attr('id')+ $('#h_data_search').val() );
				
				 calcallPrice();
                    });

					$('input[name="class[]"]').bind("focusout",function(e){
            var id= $(this).val();
            var class_id= $(this).closest('tr').find('input[name="class_id[]"]');
            var name= $(this).closest('tr').find('input[name="class_name[]"]');
            var unit= $(this).closest('tr').find('select[name="class_unit[]"]');
            var amount= $(this).closest('tr').find('input[name="request_amount[]"]');
			var price= $(this).closest('tr').find('input[name="price[]"]');
            if(id==''){
                class_id.val('');
                name.val('');
                unit.select2("val", '');
                return 0;
            }else if( id.search("-")!= -1 ){
                return 0;
            }
            get_data('{$get_class_url}',{id:id, type:1},function(data){
                if (data.length == 1){
                    var item= data[0];
                    class_id.val(item.CLASS_ID);
                    name.val(item.CLASS_NAME_AR);
                    unit.select2("val", item.CLASS_UNIT_SUB);
					price.val(item.CLASS_PURCHASING);
					amount.val('');
					calcallPrice();
                    amount.focus();
                }else{
                    class_id.val('');
                    name.val('');
					price.val('');
					amount.val('');
                    unit.select2("val", '');
                }
            });

			  calcallPrice();
        });

					$('select[name="class_unit[]"] ,select[name="class_type[]"]').select2();
            $('select[name="class_unit[]"]').select2("readonly",true);

$('select[name="planning_dir[]"]').each(function (i) {


           count[i]=$(this).closest('tr').find('input[name="h_count[]"]').val(i);






    });
	$('select[name="f_branch[]"]').each(function (i) {


           f_count[i]=$(this).closest('tr').find('input[name="h_f_count[]"]').val(i);






    });


 $('input[name="weight[]"]').keyup(function (e) {

//e.preventDefault();


  calcall();
    });

	 $('input[name="request_amount[]"]').keyup(function (e) {

//e.preventDefault();


  calcallPrice();
    });
	
	 $('input[name="dir_weight[]"]').keyup(function (e) {

//e.preventDefault();


  calcalldir();
    });

		 $('input[name="request_amount[]"],input[name="price[]"]').change(function (e) {

//e.preventDefault();


  calcallPrice();

    });

    		 $('input[name="request_amount[]"],input[name="price[]"]').keyup(function (e) {

//e.preventDefault();


  calcallPrice();

    });


$('select[name="planning_dir[]"]').select2().on('change',function(){

       //  checkBoxChanged();

        });

		$('select[name="unit[]"]').select2().on('change',function(){

       //  checkBoxChanged();

        });
		$('select[name="scale[]"]').select2().on('change',function(){

       //  checkBoxChanged();

        });


      $('select[name="branch[]"]').select2().on('change',function(){

//$('select[name="branch[]"]').on('change', function () {
var cnt_tr=$(this).closest('tr').find('input[name="h_count[]"]').val();
       //alert('loop==>'+cnt_tr);


            get_data('{$manage_follow_branch}',{no: $(this).val()},function(data){
             $('#dp_manage_id_'+cnt_tr).html('');
             $('#dp_manage_id_'+cnt_tr).append('<option></option>');
             $('#dp_manage_id_'+cnt_tr).select2('val','');
             $('#dp_cycle_id_'+cnt_tr).select2('val','');

            $.each(data,function(index, item)
            {

             $('#dp_manage_id_'+cnt_tr).append('<option value=' + item.ST_ID + '>' + item.ST_NAME + '</option>');
            });
            });



        });
       $('select[name="manage_id[]"]').select2().on('change',function(){
     var cnt_tr=$(this).closest('tr').find('input[name="h_count[]"]').val();
      // alert('loop==>'+cnt_tr);

        //  checkBoxChanged();
             get_data('{$cycle_exe_branch}',{no: $(this).val()},function(data){
               $('#dp_cycle_id_'+cnt_tr).html('');
             $('#dp_cycle_id_'+cnt_tr).append('<option></option>');
             $('#dp_cycle_id_'+cnt_tr).select2('val','');
             $('#dp_department_id_'+cnt_tr).select2('val','');
            $.each(data,function(index, item)
            {
            $('#dp_cycle_id_'+cnt_tr).append('<option value=' + item.ST_ID + '>' + item.ST_NAME + '</option>');
            });
            });

        });


         $('select[name="cycle_id[]"]').select2().on('change',function(){
         var cnt_tr=$(this).closest('tr').find('input[name="h_count[]"]').val();
      // alert('loop==>'+cnt_tr);
       //  checkBoxChanged();

            get_data('{$dep_exe_branch}',{no: $(this).val()},function(data){
               $('#dp_department_id_'+cnt_tr).html('');
             $('#dp_department_id_'+cnt_tr).append('<option></option>');
             $('#dp_department_id_'+cnt_tr).select2('val','');

            $.each(data,function(index, item)
            {
            $('#dp_department_id_'+cnt_tr).append('<option value=' + item.ST_ID + '>' + item.ST_NAME + '</option>');
            });
            });


        });

        $('select[name="department_id[]"]').select2().on('change',function(){




       //  checkBoxChanged();


        });


  $('select[name="main_from_month[]"]').select2().on('change',function(){
  var select_from_month=$(this).val();
  var monthes_select=[];
$('input[name="exe_time[]"]').each(function (i) {
if($(this).is(":checked")){
monthes_select[i]=$(this).val();
}
});

if( jQuery.inArray($(this).val(), monthes_select)!== -1)
{

if(select_from_month>= $(this).val()  && select_from_month<=$(this).val() )
{

}
}
else
{
 var cnt_tr=$(this).closest('tr').find('input[name="h_count[]"]').val();
      // alert('loop==>'+cnt_tr);
 if(monthes_select.length >0)
 {
danger_msg('ادخال خاطئ للشهر يتوجب ان يكون ما بين شهر '+ monthes_select);
}
else
{

danger_msg('ادخال خاطئ للشهر يتوجب تحديد أشهر التنفيذ أولا ');
}

$('#dp_main_from_month_'+cnt_tr).select2('val','');
}


        });
 $('select[name="main_to_month[]"]').select2().on('change',function(){
 var select_to_month=$(this).val();
  var monthes_select=[];
$('input[name="exe_time[]"]').each(function (i) {
if($(this).is(":checked")){
monthes_select[i]=$(this).val();
}
});
if( jQuery.inArray($(this).val(), monthes_select)!== -1)
{

if(select_to_month>= $(this).val()  && select_to_month<=$(this).val() )
{

}
}

else
{
 var cnt_tr=$(this).closest('tr').find('input[name="h_count[]"]').val();
       //alert('loop==>'+cnt_tr);
 if(monthes_select.length >0)
 {
danger_msg('ادخال خاطئ للشهر يتوجب ان يكون ما بين شهر '+ monthes_select);
}
else
{

danger_msg('ادخال خاطئ للشهر يتوجب تحديد أشهر التنفيذ أولا ');
}
$('#dp_main_to_month_'+cnt_tr).select2('val','');
}

        });
		$('select[name="f_planning_dir[]"]').select2().on('change',function(){

       //  checkBoxChanged();

        });
		      $('select[name="f_branch[]"]').select2().on('change',function(){

//$('select[name="branch[]"]').on('change', function () {
var cnt_tr=$(this).closest('tr').find('input[name="h_f_count[]"]').val();
       //alert('loop==>'+cnt_tr);


            get_data('{$manage_follow_branch}',{no: $(this).val()},function(data){
             $('#dp_f_manage_id_'+cnt_tr).html('');
             $('#dp_f_manage_id_'+cnt_tr).append('<option></option>');
             $('#dp_f_manage_id_'+cnt_tr).select2('val','');
             $('#dp_f_cycle_id_'+cnt_tr).select2('val','');

            $.each(data,function(index, item)
            {

             $('#dp_f_manage_id_'+cnt_tr).append('<option value=' + item.ST_ID + '>' + item.ST_NAME + '</option>');
            });
            });



        });
		$('select[name="f_manage_id[]"]').select2().on('change',function(){
    var cnt_tr=$(this).closest('tr').find('input[name="h_f_count[]"]').val();
      // alert('loop==>'+cnt_tr);

        //  checkBoxChanged();
             get_data('{$cycle_follow_branch}',{no: $(this).val()},function(data){
               $('#dp_f_cycle_id_'+cnt_tr).html('');
             $('#dp_f_cycle_id_'+cnt_tr).append('<option></option>');
             $('#dp_f_cycle_id_'+cnt_tr).select2('val','');
             $('#dp_f_department_id_'+cnt_tr).select2('val','');
            $.each(data,function(index, item)
            {
            $('#dp_f_cycle_id_'+cnt_tr).append('<option value=' + item.ST_ID + '>' + item.ST_NAME + '</option>');
            });
            });

        });

		   $('select[name="f_cycle_id[]"]').select2().on('change',function(){
         var cnt_tr=$(this).closest('tr').find('input[name="h_f_count[]"]').val();
      // alert('loop==>'+cnt_tr);
       //  checkBoxChanged();

            get_data('{$dep_follow_branch}',{no: $(this).val()},function(data){
               $('#dp_f_department_id_'+cnt_tr).html('');
             $('#dp_f_department_id_'+cnt_tr).append('<option></option>');
             $('#dp_f_department_id_'+cnt_tr).select2('val','');

            $.each(data,function(index, item)
            {
            $('#dp_f_department_id_'+cnt_tr).append('<option value=' + item.ST_ID + '>' + item.ST_NAME + '</option>');
            });
            });


        });

        $('select[name="f_department_id[]"]').select2().on('change',function(){




       //  checkBoxChanged();


        });

}



function return_adopt(type) {

var flag=1;
        /*var flag=0;
        if($('#dp_type').val()==3)
        {

if($('#total_weight').text()!=100)
{
 danger_msg('لم يتم الحفظ يجب ان يكون المجوع الكلي للاوزان = 100');
 flag=0;
}
else
{
flag=1;
}
}
else
{*/
if($('#dir_total_weghit').text()!=0)
{
if($('#dir_total_weghit').text()!=100)
{
 danger_msg('لم يتم الحفظ يجب ان يكون المجوع الكلي للاوزان = 100');
 flag=0;
}
else
{
flag=1;
}
}
/*else
{
flag=1;
}

/*}
*/



if(flag)
{

/*var sum_weight=0;

          get_data('{$active_get_url}',{no: $('#txt_seq').val()},function(data){
           $.each(data,function(index, item)
            {

            sum_weight = parseInt(sum_weight)+parseInt(item.WEIGHT);
            });
          // alert(sum_weight);
           if(parseInt(sum_weight)==100)
          {*/
 get_data('{$adopt_url}', {id: {$fid},adopts:70}, function (data) {

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


/*}
else
{
danger_msg('لم يتم الاعتماد ,, يتوجب عليك حفظ البيانات اولا');
}

});*/





}




    }





/*function return_adopt(type) {

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




}*/
$("#selectAll").click(function(){

        if(this.checked){
            $('.checkboxAll').each(function(){
                $(".checkboxAll").prop('checked', true);
				//exe_time++;
array.push($(this).val());
$('#month_count').val('إثنا عشر شهراً');

            })
			exe_time=12;

        }else{
            $('.checkboxAll').each(function(){
                $(".checkboxAll").prop('checked', false);
				exe_time=0;
            })


        }
    });

$('input[type="checkbox"]').click(function(){

if(exe_time<=-1)
exe_time=0;

if($(this).is(":checked")){
exe_time++;
array.push($(this).val());
       // alert(exe_time);


        }
        else if(!$(this).is(":checked")){
        exe_time--;
array.splice($.inArray($(this).val(), array), 1);

        //alert(exe_time);
        }
if(exe_time <= 0)
{
exe_time=0;
$('#month_count').val(0);

}
else if(exe_time == 1)
{
$('#month_count').val('شهر واحد');
}
else if(exe_time == 2)
{
$('#month_count').val('شهرين');
}
else if(exe_time == 3)
{
$('#month_count').val('ثلاث أشهر');
}
else if(exe_time == 4)
{
$('#month_count').val('أربع أشهر');
}
else if(exe_time == 5)
{
$('#month_count').val('خمس أشهر');
}
else if(exe_time == 6)
{
$('#month_count').val('ستة أشهر');
}
else if(exe_time == 7)
{
$('#month_count').val('سبعة أشهر');
}
else if(exe_time == 8)
{
$('#month_count').val('ثمانية أشهر');;
}
else if(exe_time == 9)
{
$('#month_count').val('تسعة أشهر');
}
else if(exe_time == 10)
{
$('#month_count').val('عشرة أشهر');
}
else if(exe_time == 11)
{
$('#month_count').val('إحدى عشر شهراً');
}
else if(exe_time == 12)
{
$('#month_count').val('إثنا عشر شهراً');
}


        });

function display_month(month_count)
{
if(month_count <= 0)
{
month_count=0;
$('#month_count').val(0);

}
else if(month_count == 1)
{
$('#month_count').val('شهر واحد');
}
else if(month_count == 2)
{
$('#month_count').val('شهرين');
}
else if(month_count == 3)
{
$('#month_count').val('ثلاث أشهر');
}
else if(month_count == 4)
{
$('#month_count').val('أربع أشهر');
}
else if(month_count == 5)
{
$('#month_count').val('خمس أشهر');
}
else if(month_count == 6)
{
$('#month_count').val('ستة أشهر');
}
else if(month_count == 7)
{
$('#month_count').val('سبعة أشهر');
}
else if(month_count == 8)
{
$('#month_count').val('ثمانية أشهر');;
}
else if(month_count == 9)
{
$('#month_count').val('تسعة أشهر');
}
else if(month_count == 10)
{
$('#month_count').val('عشرة أشهر');
}
else if(month_count == 11)
{
$('#month_count').val('إحدى عشر شهراً');
}
else if(month_count == 12)
{
$('#month_count').val('إثنا عشر شهراً');
}


}

 $('input[type="checkbox"]').each(function (i) {

if($(this).prop("checked") == true){

exe_time++;

        }

if(exe_time <= 0)
{
exe_time=0;
$('#month_count').val(0);

}
else if(exe_time == 1)
{
$('#month_count').val('شهر واحد');
}
else if(exe_time == 2)
{
$('#month_count').val('شهرين');
}
else if(exe_time == 3)
{
$('#month_count').val('ثلاث أشهر');
}
else if(exe_time == 4)
{
$('#month_count').val('أربع أشهر');
}
else if(exe_time == 5)
{
$('#month_count').val('خمس أشهر');
}
else if(exe_time == 6)
{
$('#month_count').val('ستة أشهر');
}
else if(exe_time == 7)
{
$('#month_count').val('سبعة أشهر');
}
else if(exe_time == 8)
{
$('#month_count').val('ثمانية أشهر');;
}
else if(exe_time == 9)
{
$('#month_count').val('تسعة أشهر');
}
else if(exe_time == 10)
{
$('#month_count').val('عشرة أشهر');
}
else if(exe_time == 11)
{
$('#month_count').val('إحدى عشر شهراً');
}
else if(exe_time == 12)
{
$('#month_count').val('إثنا عشر شهراً');
}




    });
	//////////////////////////////////
function SHOW_TASKS(id,plan_no,adopt){
if(plan_no ==0 || plan_no=='' )
 {
  danger_msg('يتوجب عليك حفظ الخطة التشغيلية أولا!!');
  }
  else
  {
_showReport('$select_tasks_url/'+id+'/'+plan_no+'/'+adopt);
//alert(id);

}
}
</script>

SCRIPT;


    sec_scripts($scripts);

    ?>
