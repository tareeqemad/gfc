<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * project: Ahmed Barakat
 * Date: 8/28/14
 * Time: 8:46 AM
 */

$count = $offset;
$report_url = 'http://itdev:801/gfc.aspx?data=' . get_report_folder() . '&';
?>

<div class="tbl_container">
    <table class="table" id="projectTbl" data-container="container">
        <thead>
        <tr>

            <th>#</th>
            <th style="width: 100px" >رقم التكليف</th>
            <th>تكليف العمل</th>

            <th style="width: 150px">القسم</th>
            <th style="width: 150px"> الفرقة</th>
            <th style="width: 130px"> ساعة الخروج</th>
            <th style="width: 130px">ساعة العودة</th>

            <th style="width: 100px">تاريخ الإعداد</th>
            <th style="width: 100px">المدخل</th>


            <th style="width: 100px"></th>
            <th style="width: 50px"></th>
            <th style="width: 50px"></th>
        </tr>
        </thead>
        <tbody>
        <?php if ($page > 1): ?>
            <tr>
                <td colspan="11" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>

            </tr>
        <?php endif; ?>
        <?php foreach ($rows as $row) : ?>
            <tr ondblclick="javascript:get_to_link('<?= base_url("technical/WorkOrderAssignment/get/{$row['WORK_ORDER_ASSIGNMENT_ID']}/{$action}") ?>');" class="case_<?=  $row['WORK_ORDER_CASE'] ?>">

                <td><?= $count ?></td>
                <td><?= $row['WORK_ASSIGNMENT_CODE'] ?></td>
                <td class="align-right" >
                    <span
                        class="btn btn-xs <?=  ($row['WORK_ORDER_CASE'] == 1 or $row['WORK_ORDER_CASE'] == -1) ? 'yellow' : ($row['WORK_ORDER_CASE'] == 2 ? 'green' : ($row['WORK_ORDER_CASE'] == 3) ? 'blue' : 'red') ?>"><?= $row['WORK_ORDER_CASE'] == -1? 'مدخل(غير معتمد)' : $row['WORK_ORDER_CASE_NAME'] ?> </span>
                    <?= $row['TITLE'] ?>

                </td>
                <td><?= $row['WORK_ORDER_DEPARTMENT_NAME'] ?></td>
                <td><?= $row['TEAM_ID_NAME'] ?></td>
                <td><?= $row['TIME_OUT_F'] ?></td>
                <td><?= $row['TIME_RETURN_F'] ?></td>
                <td><?= $row['ENTRY_DATE'] ?></td>
                <td><?= get_short_user_name($row['ENTRY_USER_NAME']) ?></td>

                <td>
                    <?php if (HaveAccess(base_url('technical/WorkOrderAssignment/feedback')) && $row['WORK_ORDER_CASE'] >= 2): ?>
                        <a href="<?= base_url('technical/WorkOrderAssignment/feedback') . '/' . $row['WORK_ORDER_ASSIGNMENT_ID'] ?>"
                           class="btn btn-xs blue">التغذية الراجعة</a>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if (HaveAccess(base_url('technical/WorkOrderAssignment/cancel')) && $row['WORK_ORDER_CASE'] <= 3): ?>
                        <a href="javascript:;" onclick="cancel_Assignment(this, <?=$row['WORK_ORDER_ASSIGNMENT_ID'] ?>);"
                           class="btn btn-xs red"> الغاء</a>
                    <?php endif; ?>
                </td>
                <td>

                    <a class="btn-xs btn-default" href="javascript:;"
                            onclick="javascript:_showReport('<?= base_url("JsperReport/showreport?sys=technical&report=tec_workorder_ass_report&id={$row['WORK_ORDER_ASSIGNMENT_ID']}") ?>');"
                            class="btn btn-default"> طباعة
                    </a>
                </td>
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