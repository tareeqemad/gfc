<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 21/06/15
 * Time: 09:59 ص
 */
?>

<style>
    @media print {
        #export_tools{
            display: none;
        }
    }
</style>

<div id="export_tools">

    <button type="button" onclick="javascript:print()" class="btn btn-default"> طباعة </button>
    <button class="btn btn-warning dropdown-toggle" onclick="$('#tbl').tableExport({type:'excel',escape:'false'});" data-toggle="dropdown"><i class="fa fa-bars"></i> تصدير</button>
</div>

<table id="tbl" class="table" style="width: 100%">
    <thead>
    <tr>
        <th>رقم الشيك</th>
        <th>تاريخ الشيك</th>
        <th>المبلغ</th>
        <th>العملة</th>
        <th>اسم صاحب الشيك</th>
        <th>البيان</th>
        <th>اسم البنك</th>
        <th>رقم الحساب</th>
        <th>اسم الحساب</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($rows as $row) : ?>
        <tr>
            <td><?= $row['CHECK_ID'] ?></td>
            <td><?= $row['CHECK_DATE'] ?></td>
            <td><?=n_format($row['CRIDET']) ?></td>
            <td><?= $row['CURR_ID_NAME'] ?></td>
            <td><?= $row['CHECK_CUSTOMER'] ?></td>
            <td>إستحقاق منذ<?= round($row['DIFF_DATE'])?> يوم </td>
            <td><?= $row['CHECK_BANK_ID_NAME'] ?></td>
            <td><?= $row['CHECKS_PROCESSING_DEBIT'] ?></td>
            <td><?= $row['CHECKS_PROCESSING_DEBIT_NAME'] ?></td>
        </tr>
    <?php endforeach;?>
    </tbody>
</table>