<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 06/11/14
 * Time: 09:08 ص
 */
$count = 1;
?>
<div class="tbl_container">
    <table class="table" id="warrantyTbl" data-container="container">
        <thead>
        <tr>
            <th  >#</th>
            <th > تاريخ التجديد  </th>
            <th >سبب التجديد </th>
            <th >المستخدم </th>
            <th > تاريخ الإدخال </th>


        </tr>
        </thead>
        <tbody>
        <?php foreach($rows as $row) :?>
            <tr>

                <td><?= $count ?></td>
                <td><?= $row['EXPAND_DATE'] ?></td>
                <td><?= $row['EXPAND_CONDITION'] ?></td>
                <td><?= $row['ENTRY_USER_NAME'] ?></td>
                <td><?= $row['ENTRY_DATE'] ?></td>

                <?php $count++ ?>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>

<script>
    if (typeof initFunctions == 'function') {
        initFunctions();
    }

</script>