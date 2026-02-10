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
            <th  ><input type="checkbox"  class="group-checkable" data-set="#projectTbl .checkboxes"/></th>
            <th  >#</th>

            <th style="width: 100px" >رقم الأمر</th>
            <th style="width: 120px" >نوع أمر العمل</th>
            <th>البيان </th>
            <th style="width: 80px" > تاريخ البداية</th>
            <th style="width: 80px" >تاريخ النهاية</th>
            <th style="width: 70px" >يحتاج اذن</th>

        </tr>
        </thead>
        <tbody>
        <?php if($page > 1): ?>
            <tr>
                <td colspan="11" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>

            </tr>
        <?php endif; ?>
        <?php foreach($rows as $row) :?>
            <tr  ondblclick="javascript:select_WorkOrder(<?= $row['WORK_ORDER_ID'] ?>,'<?= preg_replace("/(\r\n|\n|\r)/", "\\n", $row['WORKORDER_TITLE'] )?>','<?= $row['WORK_ORDER_START_DATE'] ?>','<?= $row['WORK_ORDER_END_DATE'] ?>',<?= $row['REQUEST_TYPE'] ?>,'<?= $row['WORK_ORDER_CODE'] ?>',false);"  class="<?= $row['WORK_PERMIT'] != null ? 'case_2':'' ?>" >
                <td><input type="checkbox" class="checkboxes" data-val=' <?= preg_replace("/(\r\n|\n|\r|')/","",json_encode($row))?>' value="<?= $row['WORK_ORDER_ID'] ?>"/></td>

                <td><?= $count ?></td>

                <td><?= $row['WORK_ORDER_CODE'] ?></td>
                <td><?= $row['REQUEST_TYPE_NAME'] ?></td>

                <td><?= $row['WORKORDER_TITLE'] ?></td>

                <td><?= $row['WORK_ORDER_START_DATE'] ?></td>
                <td><?= $row['WORK_ORDER_END_DATE'] ?></td>
                <td><?= $row['WORK_PERMIT'] == 1 ? '<i class="icon icon-close"></i>' : ($row['WORK_PERMIT'] == 2 ? '<i class="icon icon-check"></i>' : '') ?></td>
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