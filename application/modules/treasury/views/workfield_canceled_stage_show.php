<?php
/**
 * Created by PhpStorm.
 * User: abarakat
 * Date: 9/9/2021
 * Time: 1:30 PM
 */

$sum = count($bills) <= 0 ? 0 : array_sum(array_column($bills, 'THE_VALUE'));
?>

<div class="row">
    <div class="toolbar">
        <div class="caption"><?= $title ?> <strong><?= isset($user[0]['NAME']) ? $user[0]['NAME'] : '' ?></strong></div>
        <ul>
            <li><a href="<?= $back_url ?>"><i class="icon icon-reply"></i> </a></li>
        </ul>
    </div>

    <div class="form-body">
        <div class="alert alert-danger"> سندات التحصيل الميداني الخاصة للموظف /
            <strong><?= isset($user[0]['NAME']) ? $user[0]['NAME'] : '' ?></strong></div>

        <div class="container">
            <div class="info">
                <span class="alert alert-danger box floatLeft  mr-20">
                    الاجمالي
                     <strong class=""><?= $sum ?></strong>
                </span>
            </div>
        </div>

        <table class="table info" id="Tbl">
            <thead>
            <tr>
                <th>#</th>
                <th>الاشتراك</th>
                <th>الاسم</th>
                <th>العنوان</th>
                <th>الجوال</th>
                <th>الشهر</th>
                <th>المبلغ</th>
                <th>التاريخ</th>
                <th> سبب الالغاء</th>
                <th>الحالة </th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($bills as $row) : ?>

                <tr>
                    <td><?= $row['SER'] ?></td>
                    <td><?= $row['SUBSCRIBE'] ?></td>
                    <td><?= $row['NAME'] ?></td>
                    <td><?= $row['ADDRESS'] ?></td>
                    <td><?= $row['MOBILE'] ?></td>
                    <td><?= $row['MONTH'] ?></td>
                    <td><strong><?= $row['THE_VALUE'] ?></strong></td>
                    <td><?= $row['CDATE'] ?></td>
                    <td><?= $row['CANCEL_NOTE'] ?></td>
                    <td><?= $row['STATUS_NAME'] ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="6"><strong>المجموع</strong></td>
                <td><strong><?= $sum ?></strong></td>
                <td colspan="3"></td>
            </tr>
            </tfoot>
        </table>
    </div>
</div>