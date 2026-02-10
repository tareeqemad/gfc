<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 20/04/19
 * Time: 12:05 م
 */

$count = 1;
?>

<h3>التغير في بيانات التأمين</h3>
<div class="tbl_container">
    <table class="table" id="chainsTbl" data-container="container">
        <thead>
        <tr>

            <th>#</th>
            <th>رقم التأمين</th>
            <th>نوع التأمين</th>
            <th>تاريخ التأمين</th>
            <th>تاريخ بداية التأمين</th>
            <th>تاريخ نهاية التامين</th>
            <th>شركة التأمين</th>
            <th>مبلغ التأمين</th>


        </tr>
        </thead>
        <tbody>
        <?php foreach ($rows as $row) : ?>
            <tr>

                <td><?= $count ?></td>
                <td><?= $row['INSURANCE_NUMBER'] ?></td>
                <td><?= $row['INSURANCE_TYPE_NAME'] ?></td>
                <td><?= $row['INSURANCE_DATE'] ?></td>
                <td><?= $row['INSURANCE_START_DATE'] ?></td>
                <td><?= $row['INSURANCE_END_DATE'] ?></td>
                <td><?= $row['LICENSE_COMBANY_NAME'] ?></td>
                <td><?= $row['INSURANCE_VALUE'] ?></td>
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