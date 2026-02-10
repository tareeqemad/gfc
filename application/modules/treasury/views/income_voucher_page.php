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
            <th  ><input type="checkbox"  class="group-checkable" data-set="#voucherTbl .checkboxes"/></th>
            <th >الرقم التسلسلي</th>
            <th > كود الإيصال </th>
            <th  >تاريخ الإيصال </th>
            <?php if($type == 'service') : ?>
                <th>نوع الخدمة</th>
            <?php endif; ?>
            <th > نوع القبض </th>
            <th > العملة </th>
            <th  class="price"> المبلغ </th>
            <th  > سعر العملة </th>
            <th > حالة السند </th>
            <th > رقم فيشة التحويل للخزينة </th>
            <?php if($user != ((count($vouchers) > 0)? $vouchers[0]['ENTRY_USER']:0 )) : ?>
                <th >المدخل</th>
            <?php endif; ?>


            <th  > حساب الصندوق</th>
            <th ></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($vouchers as $voucher) :?>
            <tr ondblclick="javascript:get_to_link('<?= base_url("treasury/income_voucher/get/{$voucher['VOUCHER_ID']}") ?>');"   class="case_<?= $voucher['VOUCHER_CASE'] ?>">

                <td><?= $count ?></td>
                <td><input type="checkbox" class="checkboxes" value="<?= $voucher['VOUCHER_ID'] ?>"/></td>
                <td><?= $voucher['ENTRY_SER'] ?></td>
                <td><?= $voucher['VOUCHER_ID'] ?></td>
                <td><?= $voucher['VOUCHER_DATE'] ?></td>
                <?php if($type == 'service') : ?>
                    <td><?= $voucher['SERVICE_TYPE_NAME'] ?></td>
                <?php endif; ?>
                <td><?= $voucher['INCOME_TYPE_NAME'] ?></td>
                <td><?= $voucher['CURRENCY_ID_NAME'] ?></td>
                <td class="price"><?= n_format($voucher['TOTAL']) ?></td>
                <td  ><?= $voucher['CURR_VALUE'] ?></td>
                <td><?= $voucher['VOUCHER_CASE_NAME'] ?></td>
                <td><?= $voucher['CONVERT_CASH_ID'] ?></td>
                <?php if($user != $voucher['ENTRY_USER'] ) : ?>
                    <td><?= $voucher['ENTRY_USER_NAME'] ?></td>
                <?php endif; ?>
                <td><?= $voucher['DEBIT_ACCOUNT_ID_NAME'] ?></td>
                <td>
<!--                    <a onclick="javascript:_showReport('<?/*=base_url('/reports') */?>?report=MAIN_VI&params[]=<?/*=$voucher['VOUCHER_ID'] */?>&params[]=<?/*= $branch */?>');" href="javascript:;"><i class="icon icon-print print-action"></i> </a>
-->                </td>
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