<?php
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 02/10/17
 * Time: 11:01 ص
 */

$MODULE_NAME= '';
$TB_NAME= '';
$get_url= base_url("$MODULE_NAME/$TB_NAME/get");
$count=0;
$sum_deduction_value=0;

?>

<div class="tb_container">
    <table class="table" id="details_tb" data-container="container">
        <thead>
        <tr>
            <th style="width: 2%">#</th>
            <th style="width: 10%"> رقم الاستقطاع</th>
            <th style="width: 25%">  الاستقطاع</th>
            <th style="width: 10%"> قيمة الاستقطاع </th>
            <th style="width: 10%"> رقم الاشتراك المنتفع منه  </th>
            <th style="width: 10%">رقم الفاتورة الشهرية</th>
        </tr>
        </thead>

        <tbody>
        <?php if(count($details) <= 0) {  // ادخال ?>
            <tr>

            </tr>
        <?php
        }else if(count($details) > 0) { // تعديل
            $count = -1;
            foreach($details as $row) {
                $sum_deduction_value+= $row['DEDUCTION_VALUE'];
        ?>
            <tr>
                <td><?=++$count+1?></td>
                <td><?=$row['DEDUCTION_BILL_ID']?></td>
                <td><?=$row['DEDUCTION_BILL_ID_NAME']?></td>
                <td><?=$row['DEDUCTION_VALUE']?></td>
                <td><?=$row['SUBSCRIBER_ID']?></td>
                <td><?=$row['BILL_NO']?></td>
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
            <th>المجموع</th>
            <th><?=$sum_deduction_value?></th>
            <th></th>
            <th></th>
        </tr>
        </tfoot>
    </table>
</div>
