<?php

/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 04/04/23
 * Time: 13:00 م
 */

$MODULE_NAME= 'salary';
$TB_NAME= 'Pull_billing_data';

$count= $offset;
?>
<div class="table-responsive tableRoundedCorner">
    <table class="table mg-b-0 text-md-nowrap  table-hover table-bordered roundedTable" id="pull_billing_data_tb">
        <thead class="table-light">
        <tr>
            <th>#</th>
            <th>المقر</th>
            <th>رقم الموظف</th>
            <th>اسم الموظف</th>
            <th>رقم الهوية</th>
            <th>رقم الفاتورة</th>
            <th>الفئة</th>
            <th>المبلغ المعتمد للفئة</th>
            <th>مبلغ الفاتورة</th>
            <th>اسم البرنامج</th>
            <th>التكلفة المعتمدة</th>
            <th>الحالة</th>
            <th>حالة الاعتماد</th>
            <th>اسم المدخل</th>
            <th>تاريخ الادخال</th>
        </tr>
        </thead>

        <tbody>
        <?php foreach ($page_rows as $row) : ?>

            <tr ondblclick="javascript:show_row_details('<?=$row['THE_MONTH']?>'  );">
                <td><?= $count++ ?></td>
                <td><?= $row['BRANCH_NAME'] ?></td>
                <td><?= $row['EMP_NO'] ?></td>
                <td><?= $row['EMP_NO_NAME'] ?></td>
                <td><?= $row['EMP_ID'] ?></td>
                <td><?= $row['BILL_NO'] ?></td>
                <td><?= $row['CATEGORY'] ?></td>
                <td><?= $row['CATEGORY_AMOUNT'] ?></td>
                <td><?= $row['BILL_AMOUNT'] ?></td>
                <td><?= $row['PROGRAM_NAME'] ?></td>
                <td><?= $row['APPROVED_COST'] ?></td>
                <td><?= $row['STATUS'] ?></td>
                <td><?= $row['ADOPT_NAME'] ?></td>
                <td><?= $row['ENTRY_USER_NAME'] ?></td>
                <td><?= $row['ENTRY_DATE'] ?></td>
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