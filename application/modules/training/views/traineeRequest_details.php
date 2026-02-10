<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 23/01/20
 * Time: 12:48 م
 */



$MODULE_NAME= 'training';
$TB_NAME= 'traineeRequest';
$isCreate =isset($details) && count($details)  > 0 ?false:true;
$count=0;

?>
<div class="tb_container">
    <table class="table" id="details_tb1" data-container="container"  align="center">
        <thead>
        <tr>
            <th>رقم المسلسل</th>
            <th>التخصص</th>
            <th>المؤهل العلمي</th>
            <th>مكان الدراسة</th>
            <th>تاريخ التخرج</th>
            <th>المعدل التراكمي </th>
        </tr>
        </thead>
        <tbody>
        <?php
            foreach($details as $row1) {
                ++$count+1;

                ?>
                <tr>
                    <td>
                        <input type="text" readonly name="" id="txt_ser2"
                               class="form-control" value="<?=$count?>">
                    </td>

                    <td>
                        <input type="text" readonly   value="<?=$row1['QUAL_FIELD_NAME']?>"
                               name="specialization[]" id="txt_specialization_<?=$count?>"
                               class="form-control">
                    </td>
                    <td>
                        <input type="text" readonly data-val="true"
                               name="academic_qualifications[]" id="txt_academic_qualifications_<?=$count?>"
                               class="form-control" value="<?=$row1['QUAL_TYPE_NAME']?>" >
                    </td>

                    <td>
                        <input type="text" readonly data-val="true"
                               name="study_place[]" id="txt_study_place_<?=$count?>"
                               class="form-control" value="<?=$row1['UNIVERSITY']?>" >

                    </td>


                    <td>
                        <input type="text" readonly data-val="true"
                               value="<?=$row1['GRAD_DATE']?>"
                               name="graduate_date[]" id="txt_graduate_date_<?=$count?>"
                               class="form-control" >
                    </td>

                    <td>
                        <input type="text" readonly  value="<?=$row1['GPA']?>"
                               name="cgpa[]" id="txt_cgpa_<?=$count?>"
                               class="form-control">
                    </td>

                </tr>


            <?php

        }

        ?>
        </tbody>
        <tfoot>

        </tfoot>
    </table></div>
