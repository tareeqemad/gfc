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
<table class="table" id="cashTbl" data-container="container">
    <thead>
    <tr>

        <th  >#</th>
        <th >  رقم سند</th>
        <th  > تاريخ السند
        </th>
        <th  > حساب الخزينة</th>
        <th >حساب الصندوق</th>
        <th class="price" > مجموع النقود المستلمة </th>
        <th >حالة النقود المقبوضة        </th>
        <th >رقم سند إيداع البنك</th>
        <?php if($adopt == 'adopt') : ?>
            <th style="width: 150px"> المدخل </th>
        <?php endif; ?>
    </tr>
    </thead>
    <tbody>
    <?php foreach($cashes as $cash) :?>



        <tr ondblclick="javascript:get_to_link('<?= base_url('treasury/convert_cash/get/').'/'.$cash['CONVERT_CASH_ID'].'/'.$adopt ?>');" class="case_<?= $cash['CONVERT_CASH_CASE'] ?>">


            <td><?= $count ?></td>
            <td><?= $cash['CONVERT_CASH_ID'] ?></td>
            <td><?= $cash['CONVERT_CASH_DATE'] ?></td>
            <td><?= $cash['TREASURY_ACCOUNT_ID_NAME'] ?></td>
            <td><?= $cash['DEBIT_ACCOUNT_ID_NAME'] ?></td>
            <td class="price"><?= n_format($cash['CONVERT_CASH_TOTAL']) ?></td>
            <td><?= $cash['MONEY_RECEIVED_ID_NAME'] ?></td>
            <td><?= $cash['CONVERT_CASH_BANK_ID'] ?></td>
            <?php if($adopt == 'adopt') : ?>
                <td><?= $cash['TREASURY_USER_ID_NAME'] ?></td>
            <?php endif; ?>
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