<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 10/03/20
 * Time: 10:49 ص
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
            <th>عدد الساعات</th>
            <th>سعر الساعة</th>
            <th>التكلفة الاجمالية</th>
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
                        <input type="text" readonly   value="<?=$row1['COST_NO']?>"
                               name="cost_no[]" id="txt_cost_no_<?=$count?>"
                               class="form-control">
                    </td>
                    <td>
                        <input type="text" readonly data-val="true"
                               name="cost_hour[]" id="txt_cost_hour_<?=$count?>"
                               class="form-control" value="<?=$row1['COST_HOUR']?>" >
                    </td>

                    <td>
                        <input type="text" readonly data-val="true"
                               name="total[]" id="txt_total_<?=$count?>"
                               class="form-control" value="<?=$row1['TOTAL']?>" >

                    </td>
                </tr>


            <?php

            }


        ?>
        </tbody>

        <tfoot>
        </tfoot>
    </table></div>
