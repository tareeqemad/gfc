<?php
/**
 * Created by PhpStorm.
 * User: abarakat
 * Date: 20/12/16
 * Time: 09:18 ص
 */

?>



<div class="alert alert-info" role="alert"><strong>بيانات الوقود </strong>

    <p>
    <table class="table" id="teamTbl" data-container="container">
        <thead>
        <tr>

            <th> المخصص الشهري</th>
            <th>الإضافي</th>
            <th>المحول</th>
            <th>المصروف</th>
            <th>الرصيد</th>
            <th>آخر قراءة</th>

        </tr>
        </thead>

        <tbody>

        <?php foreach ($rows as $row): ?>
            <tr>
                <td><?= $row['MONTHLY_ALLOCATED'] ?></td>
                <td><?= $row['ADDITION_AMOUNT'] ?></td>
                <td><?= $row['CARS_FUEL_TRANSFER'] ?></td>
                <td><?= $row['COUPON_FUEL_AMOUNT'] ?></td>
                <td><?= ($row['MONTHLY_ALLOCATED'] + $row['ADDITION_AMOUNT'] + $row['CARS_FUEL_TRANSFER']) - $row['COUPON_FUEL_AMOUNT'] ?> </td>
                <td><?= $row['METAR_COUNT'] ?></td>
            </tr>

        <?php endforeach; ?>

        </tbody>

    </table>
    </p>
</div>
