<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 15/10/14
 * Time: 08:25 ص
 */
$count = 1;
?>
<div class="tbl_container">
<table class="table" id="voucherTbl" data-container="container">
    <thead>
    <tr>

        <th  >#</th>
        <th >نوع السند </th>
        <th >رقم سند</th>
        <th  >تاريخ السند</th>

        <th>رقم الشيك</th>
        <th >تاريخ حركة الشيك</th>
        <th  class="price"> قيمة الشيك </th>
        <th > حساب المدين </th>
        <th >  حساب الدائن </th>
        <th >المدخل</th>

    </tr>
    </thead>
    <tbody>
    <?php foreach($checks as $check) :?>
        <tr ondblclick="javascript:get_to_link('<?= base_url('treasury/checks_processing/get/').'/'.$check['CHECKS_PROCESSING_ID'] ?>');"  class="case_<?= $check['CHECKS_PROCESSING_CASE'] ?>">

        <td><?= $count ?></td>
            <td><?= $check['CHECKS_PROCESSING_TYPE_NAME'] ?></td>
            <td><?= $check['CHECKS_PROCESSING_ID'] ?></td>
            <td><?= $check['CONVERT_CASH_BANK_DATE'] ?></td>

            <td><?= $check['CHECK_ID'] ?></td>
            <td><?= $check['CONVERT_CASH_BANK_ONE'] ?></td>

            <td class="price"><?= $check['CHECK_VALUE'] ?></td>

            <td><?= $check['CHECKS_PROCESSING_DEBIT_NAME'] ?></td>
            <td><?= $check['CHECKS_PROCESSING_CREDIT_NAME'] ?></td>
            <td><?= $check['TREASURY_USER_ID_NAME'] ?></td>
            <?php $count++ ?>
        </tr>
    <?php endforeach;?>
    </tbody>
</table>
    </div>
<?php echo $this->pagination->create_links(); ?>

<script>
    if (typeof initFunctions == 'function') {
        initFunctions();
    }
</script>