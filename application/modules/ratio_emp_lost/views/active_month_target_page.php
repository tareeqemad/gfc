<?php

/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 01/03/23
 * Time: 09:30 ص
 */

$MODULE_NAME= 'ratio_emp_lost';
$TB_NAME= 'Active_month_target';

$count= $offset;
?>
<div class="table-responsive tableRoundedCorner">
    <table class="table mg-b-0 text-md-nowrap  table-hover table-bordered roundedTable" id="active_month_target_tb">
        <thead class="table-light">
        <tr>
            <th>#</th>
            <th>المقر</th>
            <th>الشهر</th>
            <th>النشاط</th>
            <th>نوع النشاط</th>
            <th>مستهدف المقر</th>
            <th>عدد الفرق</th>
            <th>مستهدف الفرقة</th>
            <th>قيمة الخصم</th>
            <th>قيمة الاضافة</th>
            <th>تاريخ الادخال</th>
            <th>اسم المدخل</th>
        </tr>
        </thead>

        <tbody>
        <?php foreach ($page_rows as $row) : ?>

            <tr data-target_no="<?=$row['TARGET_NO']?>" ondblclick="javascript:show_row_details('<?=$row['BRANCH_NO']?>' ,'<?=$row['THE_MONTH']?>'  );">
                <td><?= $count++ ?></td>
                <td><?= $row['BRANCH_ID_NAME'] ?></td>
                <td><?= $row['THE_MONTH'] ?></td>
                <td><?= $row['ACTIVITY_NO_NAME'] ?></td>
                <td><?= $row['ACTIVITY_TYPE_NAME'] ?></td>
                <td><?= $row['MONTHLY_TARGET'] ?></td>
                <td><?= $row['TEAMS_CNT'] ?></td>
                <td><?= $row['TEAMS_TARGET'] ?></td>
                <td><?= $row['DISCOUNT_VALUE'] ?></td>
                <td><?= $row['ADDED_VALUE'] ?></td>
                <td><?= $row['ENTRY_DATE'] ?></td>
                <td><?= $row['ENTRY_USER_NAME'] ?></td>
            </tr>

        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php echo $this->pagination->create_links(); ?>
<script>
    if (typeof ajax_pager == 'function') {
        ajax_pager();
    }
</script>