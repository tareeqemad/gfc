<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 02/11/14
 * Time: 01:13 م
 */
$count = $offset;
?>
<div class="tbl_container">
    <table class="table" id="chainsTbl" data-container="container">
        <thead>
        <tr>

            <th  >#</th>
            <th data-sort="FINANCIAL_CHAINS_ID" data-sort-dir="<?= sort_dir('FINANCIAL_CHAINS_ID',$sort) ?>" > رقم القيد </th>
            <th data-sort="PAYMENT_ID" data-sort-dir="<?= sort_dir('PAYMENT_ID',$sort) ?>" >  رقم المصدر </th>
            <th data-sort="FINANCIAL_CHAINS_DATE" data-sort-dir="<?= sort_dir('FINANCIAL_CHAINS_DATE',$sort) ?>" >التاريخ</th>
            <th  > المصدر</th>

            <th > العملة</th>
            <th >سعر العملة</th>
            <th  class="price"> الملبغ   </th>

            <th >   البيان </th>
            <th >المدخل</th>
            <th ></th>

        </tr>
        </thead>
        <tbody>
        <?php foreach($chains as $chain) :?>
            <tr data-id="<?= $chain['FINANCIAL_CHAINS_ID'] ?>" data-type="<?= $chain['FIANANCIAL_CHAINS_SOURCE'] ?>" ondblclick="javascript:get_to_link('<?= base_url('financial/financial_chains/get/').'/'.$chain['FINANCIAL_CHAINS_ID'].'/'.$action.'?type='.(isset($type)?$type:'4') ?>');"  class="case_<?= $chain['FINANCIAL_CHAINS_CASE'] ?> source_<?= $chain['FIANANCIAL_CHAINS_SOURCE'] ?>">

                <td><?= $count ?></td>
                <td><?= $chain['FINANCIAL_CHAINS_ID'] ?></td>
                <td><?= $chain['PAYMENT_ID'] ?></td>
                <td><?= $chain['FINANCIAL_CHAINS_DATE'] ?></td>
                <td><?= $chain['FIANANCIAL_CHAINS_SOURCE_NAME'] ?></td>

                <td><?= $chain['CURR_ID_NAME'] ?></td>
                <td><?= $chain['CURR_VALUE'] ?></td>

                <td class="price"><?= n_format($chain['DEBIT_SUM']) ?> </td>


                <td><?= $chain['HINTS'] ?></td>
                <td><?= $chain['ENTRY_USER_NAME'] ?></td>
                <td>
                    <a onclick="javascript:_showReport('<?=base_url('JsperReport/showreport?sys=financial/archives') ?>&report=financial_chains&p_financial_chains_id=<?=$chain['FINANCIAL_CHAINS_ID'] ?>');" href="javascript:;"><i class="icon icon-print print-action"></i> </a>
                    <a  href="<?= base_url('financial/financial_chains/public_copy/').'/'.$chain['FINANCIAL_CHAINS_ID'].'/index?type='.(isset($type)?$type:'4') ?>"><i class="icon icon-copy print-action"></i> نسخ</a>

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