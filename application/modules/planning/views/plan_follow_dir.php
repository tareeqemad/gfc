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
$count_fllow=0;
$save_active_url= base_url("$MODULE_NAME/$TB_NAME/create_edit_sub_activites");
$manage_follow_f_branch =base_url("$MODULE_NAME/$TB_NAME/public_get_mange");
$cycle_exe_f_branch =base_url("$MODULE_NAME/$TB_NAME/public_get_cycle");
$dep_exe_f_branch =base_url("$MODULE_NAME/$TB_NAME/public_get_dep");
$b = modules::run("$MODULE_NAME/$TB_NAME/public_get_mange_b_", $user_info[0]['EMP_ID'], $user_info[0]['BRANCH']); //modules::run("$MODULE_NAME/$TB_NAME/public_get_mange_b", $branch_exe_id);
 $cycle_exe = modules::run("$MODULE_NAME/$TB_NAME/public_get_cycle_b_", $user_info[0]['EMP_ID'], $user_info[0]['BRANCH']);
 if(count($details)==0)
 {
$details=array(array( "F_SEQ"=>"0",
                            "ACTIVITIES_PLAN_SER"=>"",
                            "PLANNING_DIR"=> "1",
                            "BRANCH"=> $user_info[0]['BRANCH'],
                            "MANAGE_ID"=>$b[0]['ST_ID'],
                            "CYCLE_ID"=> $cycle_exe[0]['ST_ID'],
                            "DEPARTMENT_ID"=>"" ,
                            "ENTRY_FLAG"=>"1",
                            "BRANCH_NAME"=>"",
							"WEIGHT"=>""
                           ));
						  }
						  
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
	<th style="width: 3%">الوزن النسبي</th>
  
   
    <?php /*if ( HaveAccess($delete_url_details) && (!$isCreate )) :
    if($adopt==1)
    {
    ?>
        <th style="width: 3%">حذف</th>
    <?php }
    endif;*/ ?>
</tr>
</thead>

<tbody>

<?php if(count($details) <= 0) {  // ادخال ?>
    <tr>
        <td>
            <select name="f_planning_dir[]"  data-val ="false"   data-val-required="حقل مطلوب"  id="dp_f_planning_dir_<?=$count_fllow?>" class="form-control">
                <!--<option></option>-->
                <?php foreach($planning_dir as $row) :?>
				<?php /*if ($row['CON_NO'] == 2){*/?>
                    <option value="<?= $row['CON_NO'] ?>"  ><?= $row['CON_NAME'] ?></option>
<?php /*}*/?>
                <?php endforeach; ?>

            </select>
            <input  type="hidden" name="h_f_activities_plan_ser[]"  id="h_f_activities_plan_ser_<?=$count_fllow?>" data-val="true" data-val-required="required"  >
            <input type="hidden" name="f_seq1[]" id="f_seq1_id[]" value="0"  />
            <input type="hidden" name="h_f_count[]" id="h_f_count_<?=$count_fllow?>" />

             <span class="field-validation-valid" data-valmsg-for="f_planning_dir[]" data-valmsg-replace="true"></span>
        </td>
        <td>
            <select name="f_branch[]"  data-val ="true"   data-val-required="حقل مطلوب"  id="dp_f_branch_<?=$count_fllow?>" class="form-control">
                <option></option>
                <?php foreach($branches as $row) :?>
                    <option value="<?= $row['NO'] ?>" ><?= $row['NAME'] ?></option>


                <?php endforeach; ?>


            </select>
            <span class="field-validation-valid" data-valmsg-for="f_branch[]" data-valmsg-replace="true"> </span>
        </td>
        <td>
            <select name="f_manage_id[]"  data-val ="true"   data-val-required="حقل مطلوب"  id="dp_f_manage_id_<?= $count_fllow ?>" class="form-control">
                <option></option>

            </select>
            <span class="field-validation-valid" data-valmsg-for="f_manage_id[]" data-valmsg-replace="true"></span>
        </td>
        <td>
            <select name="f_cycle_id[]"  data-val ="true"   data-val-required="حقل مطلوب"  id="dp_f_cycle_id_<?= $count_fllow ?>" class="form-control">
                <option></option>


            </select>
            <span class="field-validation-valid" data-valmsg-for="f_cycle_id[]" data-valmsg-replace="true"></span>
        </td>
        <td>
            <!--
            <select name="f_department_id[]" data-val ="true"   data-val-required="حقل مطلوب" id="dp_f_department_id_<?= $count_fllow ?>" class="form-control">
            -->
            <select name="f_department_id[]"  id="dp_f_department_id_<?= $count_fllow ?>" class="form-control">

            <option></option>


            </select>
            <!--
            <span class="field-validation-valid" data-valmsg-for="f_department_id[]" data-valmsg-replace="true"></span>
            -->

        </td>
                   <td>
					 <input class="form-control" name="dir_weight[]" id="txt_dir_weight_<?=$count_fllow?>"  data-val ="true"   data-val-required="حقل مطلوب"  />
                 <span class="field-validation-valid" data-valmsg-for="dir_weight[]" data-valmsg-replace="true"></span>


                    </td>
       

        <?php /*if ( HaveAccess($delete_url_details) && (!$isCreate )) :
        if($adopt==1)
        {
        ?>
            <td>


            </td>
        <?php }
        endif; */?>
    </tr>
<?php
}else if(count($details) > 0) { // تعديل
    $count_fllow = -1;
    foreach($details as $row) {
        ++$count_fllow+1;

        $b=modules::run("$MODULE_NAME/$TB_NAME/public_get_mange_b",$row['BRANCH']);
        $cycle_exe=modules::run("$MODULE_NAME/$TB_NAME/public_get_cycle_b",$row['MANAGE_ID']);
        $dep_exe=modules::run("$MODULE_NAME/$TB_NAME/public_get_dep_p", $row['CYCLE_ID']);

        ?>

        <tr>
            <td>
                <select name="f_planning_dir[]"  data-val ="true"   data-val-required="حقل مطلوب"  id="dp_f_planning_dir_<?=$count_fllow?>" class="form-control">
                    <!--<option></option>-->
                    <?php foreach($planning_dir as $row1) :?>
					
					<?php /*if ($row1['CON_NO'] == 2){*/?>
                 <option value="<?= $row1['CON_NO'] ?>" <?PHP if ($row1['CON_NO']==$row['PLANNING_DIR']){ echo " selected"; } ?> ><?= $row1['CON_NAME'] ?></option>
<?php /*}*/?>
                       

                    <?php endforeach; ?>

                </select>
                <input  type="hidden" name="h_f_activities_plan_ser[]"  id="h_f_activities_plan_ser_<?=$count_fllow?>" value="<?=$row['ACTIVITIES_PLAN_SER']?>" data-val="false" data-val-required="required" >
                <input type="hidden" name="f_seq1[]" id="f_seq1_id[]" value="<?=$row['F_SEQ']?>"  />
                <input type="hidden" name="h_f_count[]" id="h_f_count_<?=$count_fllow?>" />
               <span class="field-validation-valid" data-valmsg-for="f_planning_dir[]" data-valmsg-replace="true"></span>
            </td>
            <td>
                <select name="f_branch[]"  data-val ="true"   data-val-required="حقل مطلوب"  id="dp_f_branch_<?=$count_fllow?>" class="form-control">
                    <option></option>
                    <?php foreach($branches as $row2) :?>
                        <option value="<?= $row2['NO'] ?>" <?PHP if ($row2['NO']==$row['BRANCH']){ echo " selected"; } ?>><?= $row2['NAME'] ?></option>


                    <?php endforeach; ?>


                </select>
                <span class="field-validation-valid" data-valmsg-for="f_branch[]" data-valmsg-replace="true"></span>
            </td>
            <td>
                <select name="f_manage_id[]"  data-val ="true"   data-val-required="حقل مطلوب"  id="dp_f_manage_id_<?= $count_fllow ?>" class="form-control">
                    <option></option>
                    <?php foreach($b as $row3) :?>
                        <option value="<?= $row3['ST_ID'] ?>" <?PHP if ($row3['ST_ID']==$row['MANAGE_ID']){ echo " selected"; } ?>><?= $row3['ST_NAME'] ?></option>


                    <?php endforeach; ?>

                </select>
                <span class="field-validation-valid" data-valmsg-for="f_manage_id[]" data-valmsg-replace="true"></span>
            </td>
            <td>
                <select name="f_cycle_id[]"  data-val ="true"   data-val-required="حقل مطلوب"  id="dp_f_cycle_id_<?= $count_fllow ?>" class="form-control">
                    <option></option>
                    <?php foreach($cycle_exe as $row4) :?>
                        <option value="<?= $row4['ST_ID'] ?>" <?PHP if ($row4['ST_ID']==$row['CYCLE_ID']){ echo " selected"; } ?>><?= $row4['ST_NAME'] ?></option>


                    <?php endforeach; ?>

                </select>
                <span class="field-validation-valid" data-valmsg-for="f_cycle_id[]" data-valmsg-replace="true"></span>
            </td>
            <td>
                <!--
                <select name="f_department_id[]" data-val ="true"   data-val-required="حقل مطلوب" id="dp_f_department_id_<?= $count_fllow ?>" class="form-control">
                -->
                <select name="f_department_id[]" id="dp_f_department_id_<?= $count_fllow ?>" class="form-control">

                <option></option>
                    <?php foreach($dep_exe as $row5) :?>
                        <option value="<?= $row5['ST_ID'] ?>" <?PHP if ($row5['ST_ID']==$row['DEPARTMENT_ID']){ echo " selected"; } ?>><?= $row5['ST_NAME'] ?></option>


                    <?php endforeach; ?>

                </select>
                <!--
                <span class="field-validation-valid" data-valmsg-for="f_department_id[]" data-valmsg-replace="true"></span>
                -->


            </td>


                        <td>
                          <input class="form-control" name="dir_weight[]" id="txt_dir_weight_<?=$count_fllow?>" value="<?=$row['WEIGHT']?>"  data-val ="true"   data-val-required="حقل مطلوب"  />
                          <span class="field-validation-valid" data-valmsg-for="dir_weight[]" data-valmsg-replace="true"></span>
                        </td>


            <?php /*if ( HaveAccess($delete_url_details) && (!$isCreate ) )  :
                if($adopt==1)
                {
                ?>

                <td>
                    <a onclick="javascript:delete_row_del('<?=$row['f_seq1']?>','<?=$row['ACTIVITY_NAME']?>');"  href='javascript:;'><i class='glyphicon glyphicon-remove'></i></a>


                </td>
            <?php } endif; */?>
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

 <th>
                   الإجمالي الكلي
                </th>
  
    <?php /*if ( HaveAccess($delete_url_details) && (!$isCreate )) : ?>
        <th></th>
    <?php endif;*/ ?>
</tr>
<tr>
    <th>
        <a onclick="javascript:add_row(this,'input:text,input:hidden[name^=f_seq1],textarea,select',false);"  href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>
    </th>
    <th></th>
    <th></th>
    <th></th>
    <th></th>
	<th id="dir_total_weghit"></th>
    
    <?php /*if ( HaveAccess($delete_url_details) && (!$isCreate )) : ?>
        <th></th>
    <?php endif;*/ ?>
</tr>

</tfoot>
</table></div>