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
    <table class="table" id="projectTbl" data-container="container">
        <thead>
        <tr>

            <th>#</th>
            <th style="width: 100px" >رقم الأمر</th>
            <th style="width: 120px" >نوع أمر العمل</th>
            <th>البيان </th>
            <th style="width: 80px" > تاريخ البداية</th>
            <th style="width: 80px" >تاريخ النهاية</th>
            <th style="width: 70px" >يحتاج اذن</th>
            <th>المهمة
            </th>
            <th>التعليمات</th>
            <th style="width: 70px" > التكلفة</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php if ($page > 1): ?>
            <tr>
                <td colspan="11" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>

            </tr>
        <?php endif; ?>
        <?php foreach ($rows as $row) : ?>
            <tr ondblclick="javascript:get_to_link('<?= base_url("technical/WorkOrder/{$action}/{$row['WORK_ORDER_ID']}") ?>');"
                class="case_<?=  $row['WORK_ORDER_CASE'] ?>">

                <td><?= $count ?></td>
                <td><?= $row['WORK_ORDER_CODE'] ?></td>
                <td><?= $row['REQUEST_TYPE_NAME'] ?></td>

                <td><?= $row['WORKORDER_TITLE'] ?></td>

                <td><?= $row['WORK_ORDER_START_DATE'] ?></td>
                <td><?= $row['WORK_ORDER_END_DATE'] ?></td>
                <td><?= $row['WORK_PERMIT'] == 1 ? '<i class="icon icon-close"></i>' : ($row['WORK_PERMIT'] == 2 ? '<i class="icon icon-check"></i>' : '') ?></td>
                <td><?= $row['JOB_ID_NAME'] ?></td>


                <td><?= $row['INSTRUCTIONS'] ?></td>
                <td class="orange"><?= $row['TOTAL_COST'] ?></td>

                <td>
                    <?php if (HaveAccess(base_url('technical/WorkOrder/cancel')) && $row['WORK_ORDER_CASE'] != 0): ?>
                        <a href="javascript:;" onclick="cancel_Order(this, <?=$row['WORK_ORDER_ID'] ?>);"
                           class="btn btn-xs red"> الغاء</a>
                    <?php endif; ?>
                </td
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