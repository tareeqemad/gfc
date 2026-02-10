<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 25/01/20
 * Time: 08:37 ص
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
            <th>عنوان الدورة</th>
            <th>عدد ساعات الدورة</th>
            <th>الاختصاص</th>
        </tr>
        </thead>
        <tbody>
        <?php
            foreach($details as $row1) {
                ++$count+1;

                ?>
                <tr>
                    <td>
                        <input type="text" readonly name="" id="txt_ser4"
                               class="form-control" value="<?=$count?>">

                    </td>


                    <td>
                        <input type="text" readonly   value="<?=$row1['CRS_NAME']?>"
                               name="course_title[]" id="txt_course_title_<?=$count?>"
                               class="form-control">
                    </td>
                    <td>
                        <input type="text" readonly data-val="true"
                               name="course_hour[]" id="txt_course_hour_<?=$count?>"
                               class="form-control" value="<?=$row1['HOURS']?>" >
                    </td>

                    <td>
                        <input type="text" readonly data-val="true"
                               name="specialization1[]" id="txt_specialization1_<?=$count?>"
                               class="form-control" value="<?=$row1['CRS_SPEC']?>" >

                    </td>


                </tr>


            <?php

        }

        ?>
        </tbody>

        <tfoot>

        </tfoot>
    </table></div>
