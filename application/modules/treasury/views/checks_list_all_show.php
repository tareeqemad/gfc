<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 29/12/14
 * Time: 09:37 ص
 */

$count = 1;
$create_url = base_url('treasury/checks_processing/create');

?>
<?php if(count($rows) > 0) : ?>
    <div class="tbl_container">
        <table class="table" id="cashTbl" data-container="container">
            <thead>
            <tr>

                <th  >#</th>
                <th >رقم الشيك</th>
                <th>اسم صاحب الشيك</th>
                <th  >البنك</th>
                <th  > تاريخ الاستحقاق </th>
                <th>نوع سند الإيداع</th>
                <th>المدخل</th>
                <th>الحساب</th>
                <th> </th>

            </tr>
            </thead>
            <tbody>
            <?php foreach($rows as $row) :?>



                <tr>


                    <td><?= $count ?></td>
                    <td><?= $row['CHECK_ID'] ?></td>
                    <td><?= $row['CHECK_CUSTOMER'] ?></td>
                    <td><?= $row['CHECK_BANK_ID_NAME'] ?></td>
                    <td><?= $row['CHECK_DATE'] ?></td>
                    <td><?= $row['CHECKS_PROCESSING_TYPE'] ?></td>
                    <td><?= $row['ENTRY_USER_NAME'] ?></td>
                    <td><?= $row['DEBIT_ACCOUNT_NAME'] ?></td>
                    <td><a href="<?= "{$create_url}/{$row['CHECK_ID']}/{$row['CHECK_BANK_ID']}" ?>" class="btn-xs btn-success" >معالجة</a> </td>
                </tr>

                <?php $count++; ?>

            <?php endforeach;?>
            </tbody>
        </table>
    </div>
<?php endif; ?>