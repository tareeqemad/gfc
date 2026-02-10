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
            <th>المسمى الوظيفي</th>
            <th>مكان العمل</th>
            <th>تاريخ البدء</th>
            <th>تاريخ الانتهاء</th>
        </tr>
        </thead>
        <tbody>
        <?php
            foreach($details as $row1) {
                ++$count+1;

                ?>
                <tr>
                    <td>
                        <input type="text" readonly name="" id="txt_ser3"
                               class="form-control" value="<?=$count?>">

                    </td>


                    <td>
                        <input type="text" readonly   value="<?=$row1['JOB_TITLE']?>"
                               name="job_title[]" id="txt_job_title_<?=$count?>"
                               class="form-control">
                    </td>
                    <td>
                        <input type="text" readonly data-val="true"
                               name="job_place[]" id="txt_job_place_<?=$count?>"
                               class="form-control" value="<?=$row1['JOB_PLACE']?>" >
                    </td>

                    <td>
                        <input type="text" readonly data-val="true"
                               name="start_date[]" id="txt_start_date_<?=$count?>"
                               class="form-control" value="<?=$row1['START_DATE']?>" >

                    </td>


                    <td>
                        <input type="text" readonly data-val="true"
                               value="<?=$row1['END_DATE']?>"
                               name="end_date[]" id="txt_end_date_<?=$count?>"
                               class="form-control" >
                    </td>


                </tr>


            <?php

        }

        ?>
        </tbody>

        <tfoot>


        </tfoot>
    </table></div>
