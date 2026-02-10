<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * project: Ahmed Barakat
 * Date: 8/28/14
 * Time: 8:46 AM
 */

$count = $offset;

?>

<div class="tbl_container">
    <table class="table" id="EnergyTbl" data-container="container">
        <thead>
        <tr>

            <th>#</th>
            <th> الشهر</th>
            <th> فاتورة العدادات المكيكانيكية (ك.و.س) </th>
            <th> فاتورة العدادات مسبقة الدفع (ك.و.س)</th>


            <th> التاريخ</th>
            <th> المستخدم</th>
            <th></th>
            <th style="width: 80px"></th>
        </tr>
        </thead>
        <tbody>
        <?php if ($page > 1): ?>
            <tr>
                <td colspan="11" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>

            </tr>
        <?php endif; ?>
        <?php foreach ($rows as $row) : ?>
            <tr ondblclick="javascript:get_to_link('<?= base_url("technical/SoldEnergy/get/{$row['SER']}/{$action}") ?>');">

                <td><?= $count ?></td>
                <td><?= $row['MONTH'] ?></td>
                <td><?= $row['PAID'] ?></td>
                <td><?= $row['PREPAID'] ?></td>
                <td><?= $row['ENTERY_DATE'] ?></td>
                <td><?= $row['ENTERY_USER_NAME'] ?></td>
                <td></td>

                <td></td>
                <?php $count++ ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php echo $this->pagination->create_links(); ?>

<script>
    if (typeof initFunctions == 'function') {
        initFunctions();
    }
    if (typeof ajax_pager == 'function') {
        ajax_pager();
    }


</script>