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

            <th>نوع أمر العمل</th>
            <th>المشروع/صيانة طارئة</th>
            <th> تاريخ البداية</th>
            <th>تاريخ النهاية</th>
            <th>يحتاج اذن            </th>
            <th>المهمة
            </th>
            <th>التعليمات</th>

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
            <tr  ondblclick="javascript:select_WorkOrder(<?= $row['WORK_ORDER_ID'] ?>,'<?= preg_replace("/(\r\n|\n|\r)/", "\\n", $row['WORKORDER_TITLE'] )?>','<?= $row['WORK_ORDER_START_DATE'] ?>','<?= $row['WORK_ORDER_END_DATE'] ?>',<?= $row['REQUEST_TYPE'] ?>,false);"  class="<?= $row['WORK_PERMIT'] != null ? 'case_2':'' ?>" >
                <td><input type="checkbox" class="checkboxes" data-val=' <?=json_encode($row)?>' value="<?= $row['WORK_ORDER_ID'] ?>"/></td>

                <td><?= $count ?></td>

                <td><?= $row['REQUEST_TYPE_NAME'] ?></td>

                <td><?= $row['WORKORDER_TITLE'] ?></td>

                <td><?= $row['WORK_ORDER_START_DATE'] ?></td>
                <td><?= $row['WORK_ORDER_END_DATE']  ?></td>
                <td><?= $row['WORK_PERMIT'] == 1? 'لا' : ($row['WORK_PERMIT'] == 2 ? 'نعم' : '' ) ?></td>
                <td><?= $row['JOB_ID_NAME']  ?></td>


                <td><?= $row['INSTRUCTIONS'] ?></td>
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