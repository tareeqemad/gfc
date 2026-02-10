<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Ahmed barakat
 * Date: 16/11/16
 * Time: 09:06 ص
 */

$paid_url = base_url("payment/cars_coupon_fuel/paid");

?>

<table class="table" id="cars_coupon_fuel_tb" data-container="container">
    <thead>
    <tr>
        <th>#</th>
        <th>من سيارة</th>
        <th>الي سيارة</th>

        <th>الكمية</th>
        <th>التاريخ</th>

        <th>المدخل</th>
        <th>الحالة</th>
        <th style="width:280px;"></th>
    </tr>
    </thead>

    <tbody>
    <?php
    $count = 1;
    foreach ($rows as $row): ?>
        <tr ondblclick="javascript:get_to_link('<?= base_url("payment/cars_fuel_transfer/get/{$row['SER']}") ?>')"
            class="case_<?= $row['STATUS'] ?>">
            <td><?= $count ?></td>
            <td><?= $row['FROM_FILE_ID_NAME'] ?></td>
            <td><?= $row['TO_FILE_ID_NAME'] ?></td>
            <td><?= $row['THE_COUNT'] ?></td>
            <td><?= $row['ENTRY_DATE'] ?></td>

            <td><?= $row['ENTRY_USER_NAME'] ?></td>
            <td><?= $row['STATUS'] == 1 ? '' : 'ملغي' ?></td>
            <td>
        </tr>
        <?php $count++;  endforeach; ?>
    </tbody>

</table>
