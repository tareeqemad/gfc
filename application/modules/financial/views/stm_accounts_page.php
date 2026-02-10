<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 03/02/15
 * Time: 08:05 ص
 */
$count = $offset;
$balance = $page_balance;
$c_balance =  $page_c_balance;
$chain_price =0.0;


?>
<div class="tbl_container">
    <table class="table" id="accountsTbl" data-container="container">
        <thead>
        <tr>

            <th  >#</th>
            <th  >رقم القيد</th>
            <th  >التاريخ</th>
            <th  >المدين</th>
            <th  >الدائن</th>
            <th>عملة القيد</th>
            <th>سعر العملة</th>
            <th>مبلغ القيد</th>
            <th style=" min-width: 120px;"   >الرصيد</th>
            <th style=" min-width: 120px;" >مبلغ الحساب</th>
            <th style=" min-width: 120px;" >الرصيد بعملته</th>
            <th  >البيان</th>

        </tr>
        </thead>
        <tbody>
        <?php foreach($rows as $row) :?>
            <?php $balance += ((floatval($row['DDEBIT']) - floatval($row['DCREDIT'])) * $row['CURR_VALUE']) ; ?>
            <tr>
                <td><?= $count ?> </td>
                <td><?= $row['FINANCIAL_CHAINS_ID'] ?></td>
                <td><?= $row['FINANCIAL_CHAINS_DATE'] ?></td>
                <td><?= $row['DDEBIT'] ?></td>
                <td><?= $row['DCREDIT'] ?></td>
                <td><?= $row['CURR_ID_NAME'] ?></td>
                <td><?= $row['CURR_VALUE'] ?></td>
                <td><?php $chain_price = (floatval($row['DDEBIT']) - floatval($row['DCREDIT'])) * $row['CURR_VALUE'];$chain_price = $chain_price <0 ?$chain_price*-1:$chain_price;echo $chain_price; ?></td>
                <td class="balance"> <?= check_credit($balance)  ?> </td>
                <td><?php  $chain_price = (floatval($row['DDEBIT']) - floatval($row['DCREDIT'])) /$row['D_CURR_VALUE'];$chain_price = $chain_price <0 ?$chain_price*-1:$chain_price;  echo  n_format($chain_price); ?></td>
                <td><?php $chain_price = (floatval($row['DDEBIT']) - floatval($row['DCREDIT']))/$row['D_CURR_VALUE']; $c_balance +=$chain_price; echo check_credit($c_balance,2); ?></td>
                <td><?= $row['HINTS'] ?></td>

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

    if (typeof resetBalance == 'function') {
        resetBalance(<?=$balance ?>,<?=$c_balance ?>);

    }
    var stm_balance =<?=$balance ?>;
    var stm_c_balance=<?=$c_balance ?>;
    /* $('#balance').val(<?=$balance ?>);
     $('#c_balance').val(<?=$c_balance ?>);*/
</script>