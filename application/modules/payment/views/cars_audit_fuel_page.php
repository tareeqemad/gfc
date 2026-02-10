<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 19/11/16
 * Time: 09:08 ص
 */
$count = 1;

?>
<div class="tbl_container">
    <table class="table" id="chainsTbl" data-container="container">
        <thead>
        <tr>

            <th>#</th>
            <th> نوع الوقود</th>
            <th> المخصص الشهري</th>
            <th>تاريخ التخصص</th>
            <th> تاريخ الغاء التخصيص</th>
            <th>المدخل</th>


        </tr>
        </thead>
        <tbody>
        <?php foreach ($rows as $row) : ?>
            <tr >

                <td><?= $count ?></td>
                <td><?= $row['FUEL_TYPE_NAME'] ?></td>
                <td><?= $row['MONTHLY_FUEL_ALLOCATED'] ?></td>
                <td><?= $row['MONTHLY_ALLOCATED_FUEL_DATE'] ?></td>
                <td><?= $row['AUDIT_FUEL_DATE'] ?></td>
                <td><?= $row['AUDIT_USER_NAME'] ?></td>
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