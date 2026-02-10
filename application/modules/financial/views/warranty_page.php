<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 06/11/14
 * Time: 09:08 ص
 */
$count = $offset;
?>
<div class="tbl_container">
    <table class="table" id="warrantyTbl" data-container="container">
        <thead>
        <tr>
            <th  >#</th>
           <!-- <th > رقم السند </th>-->
            <th >تاريخ الكفالة</th>
            <th >تاريخ النهاية</th>
            <th >معاملة البنك </th>
            <th>البنك</th>
            <th>رقم الشيك / الكفالة</th>
            <th >نوع الكفالة </th>
            <th >حالة الكفالة </th>
            <th >صاحب الكفالة</th>
            <th > العملة</th>
            <th style="width: 60px">سعر العملة</th>
            <th  class="price" >المبلغ </th>

            <th >البيان</th>
            <th>رقم القيد</th>
            <th style="width: 80px;"></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($rows as $row) :?>
            <tr ondblclick="javascript:get_to_link('<?= base_url('financial/warranty/get/').'/'.$row['BAIL_ID'].'/'.( isset($action)?$action:'')?>');" >

                <td><?= $count ?></td>
              <!--  <td><?/*= $row['BAIL_ID'] */?></td>-->
                <td><?= $row['BAIL_BANK_DATE'] ?></td>
                <td><?= $row['CHECK_DATE'] ?></td>
                <td><?= $row['BAIL_BANK_ID'] ?></td>
                <td><?= $row['CHECK_BANK_ID_NAME'] ?></td>
                <td><?= $row['CHECK_ID'] ?></td>
                <td><?= $row['BAIL_TYPE2_NAME'] ?></td>
                <td><?= $row['BAIL_TYPE_NAME'] ?></td>
                <td><?= $row['CHECK_CUSTOMER'] ?></td>
                <td><?= $row['CURRENCY_ID_NAME'] ?></td>
                <td><?=$row['CURR_VALUE'] ?></td>
                <td class="price"><?= n_format($row['AMOUNT']) ?> </td>
                <td><?= $row['HINTS'] ?></td>
                <td> <a id="source_url" href="<?= base_url("financial/financial_chains/get/{$row['QEED_NO']}/index?type=4") ?>"  target="_blank"><?= $row['QEED_NO'] ?></a></td>
                <td>
                    <?php if($row['BAIL_TYPE'] == 1): ?>
                        <a href="javascript:;" class="btn-xs btn-success" onclick="javascript:select_warranty(<?= $row['BAIL_ID'] ?>)"><i class="icon icon-cog"></i> الإجراء</a>
                    <?php endif; ?>
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