<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 08/02/15
 * Time: 10:54 ص
 */

$count=0;
?>

<div class="tb_container">
    <table class="table" id="details_tb" data-container="container">
        <thead>
        <tr>
            <th style="width: 1%">#</th>
            <th style="width: 2%">رقم الطلب</th>
            <th style="width: 3%">رقم الاشتراك</th>
            <th style="width: 3%">المواد المطلوبة</th>
            <th style="width: 3%">رقم الايصال</th>
            <th style="width: 3%">تاريخ الايصال</th>
            <th style="width: 2%">الكمية</th>
            <th style="width: 10%">اسم المشترك</th>
            <th style="width: 10%">ملاحظات</th>
        </tr>
        </thead>

        <tbody>
        <?php
        foreach($details as $row) {
        ?>
        <tr>
            <td><?=++$count?></td>
            <td><?=$row['SHAR_NO']?></td>
            <td><?=$row['SUBSCRIBER']?></td>
            <td><?=$row['FEES_NO_NAME']?></td>
            <td><?=$row['VOUCHER_NO']?></td>
            <td><?=$row['VOUCHER_DATE']?></td>
            <td><?=$row['F_QUANTITY']?></td>
            <td><?=$row['SUB_NAME']?></td>
            <td><?=$row['NOTES']?></td>
        </tr>
        <?php } ?>

        </tbody>

    </table>
</div>
