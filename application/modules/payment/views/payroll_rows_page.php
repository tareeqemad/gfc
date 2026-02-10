<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 26/11/14
 * Time: 08:50 ص
 */
$count = $offset;
?>
<div class="tbl_container">
    <table class="table" id="voucherTbl" data-container="container">
        <thead>
        <tr>

            <th  >#</th>

            <th > رقم المطالبة  </th>
            <th >تاريخ المطالبة </th>
            <th  > راتب عن شهر</th>
            <th>رقم الحوالة</th>
            <th >  تاريخ التحويل</th>
            <th >  العملة </th>
            <th >  سعر العملة </th>

        </tr>
        </thead>
        <tbody>
        <?php foreach($rows as $row) :?>
            <tr ondblclick="javascript:get_to_link('<?= base_url('payment/payroll/get/').'/'.$row['PAYROLL_PAYMENT_ID'].'/'.$action ?>');"  class="case_<?= $row['PAYROLL_PAYMENT_CASE'] ?>">

                <td><?= $count ?></td>
                <td><?= $row['PAYROLL_PAYMENT_ID'] ?></td>
                <td><?= $row['PAYROLL_PAYMENT_DATE'] ?></td>
                <td><?= $row['PAYROLL_PAYMENT_MONTH'] ?></td>

                <td><?= $row['TRANSFER_NUMBER'] ?></td>
                <td><?= $row['TRANSFER_DATE'] ?></td>

                <td><?= $row['CURR_ID_NAME'] ?></td>

                <td><?= $row['CURR_VALUE'] ?></td>

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