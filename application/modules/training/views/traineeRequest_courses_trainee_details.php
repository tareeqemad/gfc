<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 27/01/20
 * Time: 09:51 ص
 */



$MODULE_NAME= 'training';
$TB_NAME= 'employeeTraining';

$isCreate =isset($details_trainee) && count($details_trainee)  > 0 ?false:true;
$count=0;


?>
<div class="tb_container">
    <table class="table" id="details_tb2" data-container="container"  align="center">
        <thead>
        <tr>
            <th style="width: 8%">رقم المسلسل</th>
            <th style="width: 8%">الرقم الوظيفي</th>
            <th style="width: 15%">اسم الموظف</th>
            <th style="width: 15%">اسم الموظف / الانجليزية</th>
            <th style="width: 15%">الادارة</th>
            <th style="width: 15%">المقر</th>
            <th style="width: 2%"></th>


        </tr>
        </thead>
        <tbody>
        <?php  if(count($details_trainee) > 0) { // تعديل
            $count = -1;
            foreach($details_trainee as $row_trainee) {
                ++$count+1;

                ?>

                <tr>
                    <td>
                        <input type="text" readonly name="" id="txt_ser8_<?=$count?>"
                               class="form-control" value="<?=$count+1?>">
                        <input  type="hidden" name="h_course_ser4[]"   id="h_txt_course_ser4_<?=$count?>"
                                value="<?=$row_trainee['COURSE_SER']?>"
                                data-val="false" data-val-required="required" >

                        <input type="hidden" name="seq14[]" value="<?=$row_trainee['SER']?>"
                               id="seq14_id_<?=$count?>"  />

                        <input type="hidden" name="h_count4[]" id="h_count4_<?=$count?>"  />
                    </td>

                    <td>
                        <input type="text" readonly name="emp_no" id="txt_emp_no_<?=$count?>"
                               class="form-control" value="<?=$row_trainee['EMP_NO']?>">
                    </td>
                    <td>
                        <input type="text" data-val="true" readonly
                               value="<?=$row_trainee['USER_NAME']?>"
                               name="user_name[]" id="txt_user_name_<?=$count?>"
                               class="form-control" >
                    </td>
                    <td>
                        <input type="text" data-val="true" 
                               value="<?=$row_trainee['EMP_NAME_ENG']?>"
                               name="emp_name_eng[]" id="txt_emp_name_eng_<?=$count?>"
                               class="form-control" >
                    </td>
                    <td >
                        <input type="text" data-val="true" readonly
                               value="<?=$row_trainee['STRUCTURE_NAME']?>"
                               name="structure_name[]" id="txt_structure_name_<?=$count?>"
                               class="form-control" >
                    </td>

                    <td >
                        <input type="text" data-val="true" readonly
                               value="<?=$row_trainee['BRANCH_NAME']?>"
                               name="branch_name[]" id="txt_branch_name_<?=$count?>"
                               class="form-control" >
                    </td>

                    <td >
                        <button type="button" 
                                onclick="javascript:saveEmpName(<?=$row_trainee['SER']?>, <?= $count ?>);"
                                id="showTrainee-btn" class="btn btn-success btn-xs">حفظ </button>
                    </td>

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

        </tr>
        <tr>
            <th>
            </th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>

        </tr>

        </tfoot>
    </table></div>
