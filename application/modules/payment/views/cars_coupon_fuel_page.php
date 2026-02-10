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
        <th>رقم الكوبون</th>
        <th>رقم الألية</th>
        <th>اسم الألية</th>
        <th>نوع الوقود</th>
        <th>الكمية</th>
        <th>التاريخ</th>

        <th>المستلم</th>
        <th>المدخل</th>
        <th>الحالة</th>
        <th style="width:280px;"></th>
    </tr>
    </thead>

    <tbody>
    <?php
    $count = 1;
    foreach ($rows as $row): ?>
        <tr ondblclick="javascript:get_to_link('<?= base_url("payment/cars_coupon_fuel/get/{$row['SER']}") ?>')"
            class="case_<?= $row['COUPON_FUEL_CASE'] ?>">
            <td><?= $count ?></td>
            <td><?= $row['COUPON_FUEL_ID'] ?></td>
            <td><?= $row['CAR_NUM'] ?></td>
            <td><?= $row['OWNER'] ?></td>
            <td><?= $row['FUEL_TYPE_NAME'] ?></td>
            <td><?= $row['COUPON_FUEL_AMOUNT'] ?></td>
            <td><?= $row['COUPON_FUEL_DATE'] ?></td>
            <td><?= $row['EMP_ID_NAME'] ?></td>
            <td><?= $row['ENTRY_USER_NAME'] ?></td>
            <td><?= $row['COUPON_FUEL_CASE_NAME'] ?></td>
            <td><?php if (HaveAccess($paid_url) && $row['COUPON_FUEL_CASE'] == 1): ?><a
                    onclick='javascript:cars_coupon_paid(<?= $row['SER'] ?>);' class="btn-xs btn-success"
                    href='javascript:;'><i
                            class=''></i>تم الصرف </a> |
                  <a type="button"
                       onclick="javascript:_showReport('<?= base_url("JsperReport/showreport?sys=financial&report=coupon&id={$row['SER']}") ?>');"
                       class="btn-xs btn-default"> طباعة</a>
                    |
                   <!--   <a type="button"
                       onclick="javascript:_showReport('<?/*= base_url("JsperReport/showreport?sys=financial&report=coupon_1&id={$row['SER']}") */?>');"
                       class="btn-xs btn-default"> الحلو</a>
                    |-->
                    <a type="button"
                       onclick="javascript:_showReport('<?= base_url("JsperReport/showreport?sys=financial&report=coupon_request&id={$row['SER']}") ?>');"
                       class="btn-xs btn-default"> الطلب</a>
                <?php endif; ?> </td>
        </tr>
        <?php $count++;  endforeach; ?>
    </tbody>

</table>
