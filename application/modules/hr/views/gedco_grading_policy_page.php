<?php
/**
 * Created by PhpStorm.
 * User: mtaqia
 * Date: 26/09/16
 * Time: 12:56 م
 */
$count=0;
?>

<div class="tbl_container">
    <table class="table" style="width: 99%" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th style="width: 1%">م</th>
            <th style="width: 7%">أمر التقييم</th>
            <th style="width: 5%">الإدارة</th>
            <th style="width: 5%">النطاق</th>
            <th style="width: 5%">العدد المسموح به</th>
            <th style="width: 5%">البيان</th>
            <th style="width: 5%">تاريخ الإدخال</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($get_list as $row) :
            $count++;
            ?>
            <tr>
                <td><?=$count?></td>
                <td><?=$row['EVALUATION_ORDER_ID']?></td>
                <td><?=$row['ADMIN_ID_NAME']?></td>
                <td><?=$row['RANGE_ORDER_NAME']?></td>
                <td><?=$row['RATING_NUMBER']?></td>
                <td><?=$row['NOTE']?></td>
                <td><?=$row['ENTRY_DATE']?></td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>