<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * project: Ahmed Barakat
 * Date: 8/28/14
 * Time: 8:46 AM
 */

$count = $offset;

?>

<div class="tbl_container">
    <table class="table" id="projectTbl" data-container="container">
        <thead>
        <tr>

            <th  >#</th>

            <th>الفرع</th>
            <th>البيان</th>
            <th>الشكرة المصنعة</th>
            <th>الإتجاه</th>
            <th>حالة السكينة
            </th>
            <th>الاستخدام</th>
            <th>الخط المغذي</th>
            <th>المحول</th>
            <th>  </th>
        </tr>
        </thead>
        <tbody>
        <?php if($page > 1): ?>
            <tr>
                <td colspan="11" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>

            </tr>
        <?php endif; ?>
        <?php foreach($rows as $row) :?>
            <tr  ondblclick="javascript:get_to_link('<?=  base_url("technical/HighPowerPartition/get/{$row['HIGH_POWER_PARTITION_SERIAL']}")  ?>');">

                <td><?= $count ?></td>

                <td><?= $row['BRANCH_NAME'] ?></td>
                <td><?= $row['NOTES'] ?></td>
                <td><?= $row['HPARTITION_ID'] ?></td>
                <td><?= $row['HPARTITION_DIRECTION'] == 1? 'رأسي' : 'جانبي' ?></td>
                <td><?= $row['HPARTITION_CASE_NAME'] ?></td>
                <td><?= $row['HPARTITION_ACTIVIATION'] ==1 ?'مستخدمة' : 'غير مستخدمة ' ?></td>
                <td><?= $row['FEEDER_LINE_NAME'] ?></td>
                <td><?= $row['ADAPTER_SERIAL_NAME'] ?></td>
                <td>
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
    if (typeof ajax_pager == 'function') {
        ajax_pager();
    }



</script>