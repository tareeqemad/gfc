<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 11/04/18
 * Time: 09:22 ص
 */
$MODULE_NAME= 'planning';
$TB_NAME= 'planning';
$isCreate =isset($details) && count($details)  > 0 ?false:true;
$delete_url_details= base_url("$MODULE_NAME/$TB_NAME/delete_sub_details");
$count_activity=0;
$save_active_url= base_url("$MODULE_NAME/$TB_NAME/create_edit_sub_activites");
$manage_follow_branch =base_url("$MODULE_NAME/$TB_NAME/public_get_mange");
$cycle_exe_branch =base_url("$MODULE_NAME/$TB_NAME/public_get_cycle");
$dep_exe_branch =base_url("$MODULE_NAME/$TB_NAME/public_get_dep");

//var_dump($details);
//echo $id;
?>
<div class="tb_container">
<table class="table" id="details_tb1" data-container="container"  align="center">
<thead>
<tr>
    <th style="width: 8%">الجهة</th>
    <th style="width: 8%">المقر</th>
    <th style="width: 15%">الادارة</th>
    <th style="width: 15%">الدائرة</th>
    <th style="width: 15%">القسم</th>
    <th >اسم المشروع/نشاط</th>
    <th style="width: 8%">من الشهر</th>
    <th style="width: 8%">الى شهر</th>
    <th style="width: 8%">الوزن النسبي</th>
    <?php if ( HaveAccess($delete_url_details) && (!$isCreate )) :
    if($adopt==1)
    {
    ?>
        <th style="width: 3%">حذف</th>
    <?php }
    endif; ?>
</tr>
</thead>

<tbody>
<?php if(count($details) <= 0) {  // ادخال ?>
    <tr>
        <td>
            <select name="planning_dir[]" data-val="true"  data-val-required="حقل مطلوب"  id="dp_planning_dir_<?=$count_activity?>" class="form-control">
                <option></option>
                <?php foreach($planning_dir as $row) :?>
                    <option value="<?= $row['CON_NO'] ?>"  ><?= $row['CON_NAME'] ?></option>

                <?php endforeach; ?>

            </select>
            <input  type="hidden" name="h_txt_activities_plan_ser[]"  id="h_txt_activities_plan_ser_<?=$count_activity?>" data-val="false" data-val-required="required" >
            <input type="hidden" name="seq1[]" id="seq1_id[]" value="0"  />
            <input type="hidden" name="h_count[]" id="h_count_<?=$count_activity?>" />

            <span class="field-validation-valid" data-valmsg-for="planning_dir[]" data-valmsg-replace="true"></span>
        </td>
        <td>
            <select name="branch[]" data-val="true"  data-val-required="حقل مطلوب"  id="dp_branch_<?=$count_activity?>" class="form-control">
                <option></option>
                <?php foreach($branches as $row) :?>
                    <option value="<?= $row['NO'] ?>" ><?= $row['NAME'] ?></option>


                <?php endforeach; ?>


            </select>
             <span class="field-validation-valid" data-valmsg-for="branch[]" data-valmsg-replace="true"></span>
        </td>
        <td>
            <select name="manage_id[]" data-val="true"  data-val-required="حقل مطلوب"  id="dp_manage_id_<?= $count_activity ?>" class="form-control">
                <option></option>

            </select>
         <span class="field-validation-valid" data-valmsg-for="manage_id[]" data-valmsg-replace="true"></span>
        </td>
        <td>
            <select name="cycle_id[]" data-val="true"  data-val-required="حقل مطلوب"  id="dp_cycle_id_<?= $count_activity ?>" class="form-control">
                <option></option>


            </select>
            <span class="field-validation-valid" data-valmsg-for="cycle_id[]" data-valmsg-replace="true"></span>
        </td>
        <td>
            <select name="department_id[]"  id="dp_department_id_<?= $count_activity ?>" class="form-control">
                <option></option>


            </select>

        </td>

        <td>
            <input class="form-control" name="activity_name[]" id="txt_activity_name_<?=$count_activity?>" data-val="true"  data-val-required="حقل مطلوب"  />
            <span class="field-validation-valid" data-valmsg-for="activity_name[]" data-valmsg-replace="true"></span>

        </td>
        <td>

            <select name="main_from_month[]" id="dp_main_from_month_<?=$count_activity?>" data-curr="false" class="form-control" data-val="true"  data-val-required="حقل مطلوب" >
                <option  value="">-</option>
                <?php for($i = 1; $i <= 12 ;$i++) :?>
                    <option value="<?= $i ?>"><?= months($i) ?></option>
                <?php endfor; ?>

            </select>
            <span class="field-validation-valid" data-valmsg-for="main_from_month[]" data-valmsg-replace="true"></span>

        </td>
        <td>

            <select name="main_to_month[]" id="dp_main_to_month_<?=$count_activity?>" data-curr="false" class="form-control" data-val="true"  data-val-required="حقل مطلوب" >
                <option  value="">-</option>
                <?php for($i = 1; $i <= 12 ;$i++) :?>
                    <option value="<?= $i ?>"><?= months($i) ?></option>
                <?php endfor; ?>

            </select>
            <span class="field-validation-valid" data-valmsg-for="main_to_month[]" data-valmsg-replace="true"></span>

        </td>
        <td>
            <input class="form-control" name="weight[]" id="txt_weight_<?=$count_activity?>" data-val="true"  data-val-required="حقل مطلوب"  />
            <span class="field-validation-valid" data-valmsg-for="weight[]" data-valmsg-replace="true"></span>

        </td>

        <?php if ( HaveAccess($delete_url_details) && (!$isCreate )) :
        if($adopt==1)
        {
        ?>
            <td>


            </td>
        <?php }
        endif; ?>
    </tr>
<?php
}else if(count($details) > 0) { // تعديل
    $count_activity = -1;
    foreach($details as $row) {
        ++$count_activity+1;

        $b=modules::run("$MODULE_NAME/$TB_NAME/public_get_mange_b",$row['BRANCH']);
        $cycle_exe=modules::run("$MODULE_NAME/$TB_NAME/public_get_cycle_b",$row['MANAGE_ID']);
        $dep_exe=modules::run("$MODULE_NAME/$TB_NAME/public_get_dep_p", $row['CYCLE_ID']);

        ?>

        <tr>
            <td>
                <select name="planning_dir[]" data-val="true"  data-val-required="حقل مطلوب"  id="dp_planning_dir_<?=$count_activity?>" class="form-control">
                    <option></option>
                    <?php foreach($planning_dir as $row1) :?>
                        <option value="<?= $row1['CON_NO'] ?>" <?PHP if ($row1['CON_NO']==$row['PLANNING_DIR']){ echo " selected"; } ?> ><?= $row1['CON_NAME'] ?></option>

                    <?php endforeach; ?>

                </select>
                <input  type="hidden" name="h_txt_activities_plan_ser[]"  id="h_txt_activities_plan_ser_<?=$count_activity?>" value="<?=$row['ACTIVITIES_PLAN_SER']?>" data-val="false" data-val-required="required" >
                <input type="hidden" name="seq1[]" id="seq1_id[]" value="<?=$row['SEQ1']?>"  />
                <input type="hidden" name="h_count[]" id="h_count_<?=$count_activity?>" />
                 <span class="field-validation-valid" data-valmsg-for="planning_dir[]" data-valmsg-replace="true"></span>
            </td>
            <td>
                <select name="branch[]" data-val="true"  data-val-required="حقل مطلوب"  id="dp_branch_<?=$count_activity?>" class="form-control">
                    <option></option>
                    <?php foreach($branches as $row2) :?>
                        <option value="<?= $row2['NO'] ?>" <?PHP if ($row2['NO']==$row['BRANCH']){ echo " selected"; } ?>><?= $row2['NAME'] ?></option>


                    <?php endforeach; ?>


                </select>
                <span class="field-validation-valid" data-valmsg-for="branch[]" data-valmsg-replace="true"></span>
            </td>
            <td>
                <select name="manage_id[]" data-val="true"  data-val-required="حقل مطلوب"  id="dp_manage_id_<?= $count_activity ?>" class="form-control">
                    <option></option>
                    <?php foreach($b as $row3) :?>
                        <option value="<?= $row3['ST_ID'] ?>" <?PHP if ($row3['ST_ID']==$row['MANAGE_ID']){ echo " selected"; } ?>><?= $row3['ST_NAME'] ?></option>


                    <?php endforeach; ?>

                </select>
                 <span class="field-validation-valid" data-valmsg-for="manage_id[]" data-valmsg-replace="true"></span>
            </td>
            <td>
                <select name="cycle_id[]" data-val="true"  data-val-required="حقل مطلوب"  id="dp_cycle_id_<?= $count_activity ?>" class="form-control">
                    <option></option>
                    <?php foreach($cycle_exe as $row4) :?>
                        <option value="<?= $row4['ST_ID'] ?>" <?PHP if ($row4['ST_ID']==$row['CYCLE_ID']){ echo " selected"; } ?>><?= $row4['ST_NAME'] ?></option>


                    <?php endforeach; ?>

                </select>
                 <span class="field-validation-valid" data-valmsg-for="cycle_id[]" data-valmsg-replace="true"></span>
            </td>
            <td>
                <select name="department_id[]"  id="dp_department_id_<?= $count_activity ?>" class="form-control">
                    <option></option>
                    <?php foreach($dep_exe as $row5) :?>
                        <option value="<?= $row5['ST_ID'] ?>" <?PHP if ($row5['ST_ID']==$row['DEPARTMENT_ID']){ echo " selected"; } ?>><?= $row5['ST_NAME'] ?></option>


                    <?php endforeach; ?>

                </select>

            </td>

            <td>
                <input class="form-control" name="activity_name[]" id="txt_activity_name_<?=$count_activity?>"  value="<?=$row['ACTIVITY_NAME']?>" data-val="true"  data-val-required="حقل مطلوب"  />


            </td>
            <td>

                <select name="main_from_month[]" id="dp_main_from_month_<?=$count_activity?>" data-curr="false" class="form-control" data-val="true"  data-val-required="حقل مطلوب" >
                    <option  value="">-</option>
                    <?php for($i = 1; $i <= 12 ;$i++) :?>
                        <option value="<?= $i ?>" <?PHP if ($i==$row['FROM_MONTH']){ echo " selected"; } ?>><?= months($i) ?></option>
                    <?php endfor; ?>

                </select>
                <span class="field-validation-valid" data-valmsg-for="main_from_month[]" data-valmsg-replace="true"></span>

            </td>
            <td>

                <select name="main_to_month[]" id="dp_main_to_month_<?=$count_activity?>" data-curr="false" class="form-control" data-val="true"  data-val-required="حقل مطلوب" >
                    <option  value="">-</option>
                    <?php for($i = 1; $i <= 12 ;$i++) :?>
                        <option value="<?= $i ?>" <?PHP if ($i==$row['TO_MONTH']){ echo " selected"; } ?>><?= months($i) ?></option>
                    <?php endfor; ?>

                </select>
                <span class="field-validation-valid" data-valmsg-for="main_to_month[]" data-valmsg-replace="true"></span>

            </td>
            <td>
                <input class="form-control" name="weight[]" id="txt_weight_<?=$count_activity?>" value="<?=$row['WEIGHT']?>" data-val="true"  data-val-required="حقل مطلوب"  />
                <span class="field-validation-valid" data-valmsg-for="weight[]" data-valmsg-replace="true"></span>
            </td>

            <?php if ( HaveAccess($delete_url_details) && (!$isCreate ) )  :
                if($adopt==1)
                {
                ?>

                <td>
                    <a onclick="javascript:delete_row_del('<?=$row['SEQ1']?>','<?=$row['ACTIVITY_NAME']?>');"  href='javascript:;'><i class='glyphicon glyphicon-remove'></i></a>


                </td>
            <?php } endif; ?>
        </tr>
    <?php

    }
}

?>
</tbody>

<tfoot>
<tr>
    <th></th>
    <th></th>
    <th></th>
    <th></th>
    <th></th>


    <th></th>
    <th></th>
    <th>
        الاجمالي الكلي لاوزان
    </th>
    <th id="total_weight"></th>
    <?php if ( HaveAccess($delete_url_details) && (!$isCreate )) : ?>
        <th></th>
    <?php endif; ?>
</tr>
<tr>
    <th>
        <a onclick="javascript:add_row(this,'input:text,input:hidden[name^=seq1],textarea,select',false);"  href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>
    </th>
    <th></th>
    <th></th>
    <th></th>
    <th></th>
    <th></th>
    <th></th>
    <th></th>
    <th></th>
    <?php if ( HaveAccess($delete_url_details) && (!$isCreate )) : ?>
        <th></th>
    <?php endif; ?>
</tr>

</tfoot>
</table></div>