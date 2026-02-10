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
    <table class="table" id="expenseTbl" data-container="container">
        <thead>
        <tr>

            <th  >#</th>  <th >تاريخ السند</th>
            <th > رقم الفاتورة </th>

            <th >تاريخ الفاتورة</th>
            <th >المستفيد</th>
            <th >حالة الفاتورة</th>
            <th > العملة</th>
            <th style="width: 60px">سعر العملة</th>
            <th  class="price" >المبلغ </th>
            <th >الخصم</th>

            <th >المدخل</th>
            <th>رقم القيد</th>
            <th style="width: 160px"><i class="icon icon-print"></i> </th>

        </tr>
        </thead>
        <tbody>
        <?php foreach($rows as $row) :?>
            <tr ondblclick="javascript:get_to_link('<?= base_url('financial/expense_bill/get/').'/'.$row['EXPENSE_BILL_ID'].'/'.( isset($action)?$action.'/':'').(isset($case)?$case:'') ?>');"  class="case_<?= $row['EXPENSE_BILL_CLOSE'] ?>">

                <td><?= $count ?></td>
                <td><?= $row['EXPENSE_BILL_DATE'] ?></td>
                <td><?= $row['INVOICE_ID'] ?></td>

                <td><?= $row['INVOICE_DATE'] ?></td>
                <td><?= $row['ACCOUNT_ID_NAME'] ?></td>

                <td><?= $row['EXPENSE_BILL_CLOSE']==1?'غير مدفوعة':'مدفوعة' ?></td>
                <td><?= $row['CURR_ID_NAME'] ?></td>
                <td><?= $row['CURR_VALUE'] ?></td>
                <td class="price"><?= $row['TOTAL'] ?> </td>
                <td><?= $row['DISCOUNT_VALUE'] ?></td>

                <td><?= $row['EXPENSE_BILLT_USER_NAME'] ?></td>
                <td> <a id="source_url" href="<?= base_url("financial/financial_chains/get/{$row['QEED_NO']}/index?type=4") ?>"  target="_blank"><?= $row['QEED_NO'] ?></a></td>
                <td>   <?php if ( $row['EXPENSE_BILL_CLOSE'] >= 4): ?>
                        <a onclick="javascript:print_financial_payment_report(<?=$row['EXPENSE_BILL_ID'] ?>);" class="btn btn-default" href="javascript:;">طباعة السند</a>
                        <!--<a onclick="javascript:_showReport('<?=base_url('/reports') ?>?report=financial_payment_rep&params[]=<?=$row['EXPENSE_BILL_ID'] ?>');" class="btn-xs btn-default" href="javascript:;"> طباعة السند</a>-->
                    <?php   endif; ?>
                    <?php if ( $row['EXPENSE_BILL_CLOSE'] >= 4 && $row['CHECK_ID'] != ''): ?>
                        <a onclick="javascript:_showReport('<?=base_url('/reports') ?>?report=PALESTAIN_CHECK_FORMS10&params[]=<?=$row['EXPENSE_BILL_ID'] ?>');" class="btn-xs btn-success" href="javascript:;"> طباعة الشيك</a>
                    <?php   endif; ?>
					
					      <a href="<?= base_url('financial/expense_bill/get/') . '/' . $row['EXPENSE_BILL_ID'] . '/copy' ?>"
                       class="btn-xs btn-success">نسخ</a>
                </td>
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