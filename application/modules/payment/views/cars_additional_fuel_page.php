<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Ahmed barakat
 * Date: 16/11/16
 * Time: 09:06 ص
 */


?>

<table class="table" id="cars_fuel_price_tb" data-container="container">
    <thead>
    <tr>
        <th style="width: 80px;">#</th>
        <th style="width: 100px;">رقم الطلب</th>
        <th style="width: 100px;">الفرع</th>
        <th>البيان</th>
        <th style="width: 100px;">التاريخ</th>

        <th style="width: 180px;">المدخل</th>
        <th style="width: 100px;">الحالة</th>
        <th style="width: 100px;"></th>
    </tr>
    </thead>

    <tbody>
    <?php
    $count = 1;
    foreach ($rows as $row): ?>
        <tr ondblclick="javascript:get_to_link('<?= base_url("payment/cars_additional_fuel/get/{$row['CARS_ADDITIONAL_FUEL_ID']}/{$action}") ?>')">
            <td><?= $count ?></td>
            <td><?= $row['CARS_ADDITIONAL_FUEL_ID'] ?></td>
            <td><?= $row['BRANCH_ID_NAME'] ?></td>
            <td><?= $row['DECLARATION'] ?></td>
            <td><?= $row['ENTRY_DATE'] ?></td>
            <td><?= $row['ENTRY_USER'] ?></td>
            <td><?= $row['ADDITIONAL_FUEL_NAME'] ?></td>
            <td></td>
        </tr>
        <?php $count++;  endforeach; ?>
    </tbody>

</table>
