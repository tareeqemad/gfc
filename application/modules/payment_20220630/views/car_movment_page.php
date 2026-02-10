<?php
/**
 * Created by PhpStorm.
 * User: ljasser
 * Date: 24/04/19
 * Time: 11:12 ص
 */
$count = 1;
?>

<div class="tb2_container">
    <table class="table" id="chainsTbl" data-container="container">
        <thead>
        <tr>

            <th>#</th>
            <th> اسم صاحب العهدة </th>
            <th>رقم السائق</th>
            <th>نوع الحركة</th>
            <th> تاريخ الحركة</th>
            <th> تاريخ الادخال</th>
            <th>اسم المدخل</th>
            <th>الحالة</th>
            <th>عدد الحركات</th>


            <th style="width: 85px;"></th>


        </tr>
        </thead>
        <tbody>
        <?php foreach ($rows as $row) : ?>
            <tr>
                <td><?= $count ?></td>
                <td><?= $row['CAR_ID'] . " - " . $row['CAR_ID_NAME'] ?></td>
                <td><?= $row['DRIVER_ID'] ?></td>
                <td><?= $row['MOVMENT_TYPE_NAME'] ?></td>
                <td><?= $row['THE_DATE'] ?></td>
                <td><?= $row['ENTRY_DATE'] ?></td>
                <td><?= $row['ENTRY_USER_NAME'] ?></td>
                <td><span class="badge badge-<?= $row['STATUS'] ?>"><?= $row['STATUS_NAME'] ?></span></td>
                <td><?= $row['DETAILS_COUNT'] ?></td>


                <td>
                    <a href="<?= base_url("payment/carMovements/get/{$row['SER']}") ?>" class="btn btn-info btn-xs">تحرير</a>
                    <button type="button" onclick="changeStatus_(0,'<?=$row['SER']?>');" class="btn btn-danger btn-xs"> الغاء</button>
                </td>
                <?php ?>
                <?php $count++ ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>


<script>
    if (typeof initFunctions == 'function') {
        initFunctions();
    }
</script>