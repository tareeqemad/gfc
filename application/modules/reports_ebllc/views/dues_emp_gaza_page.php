<?php
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 26/12/22
 * Time: 13:00 ص
 */

$MODULE_NAME= 'reports_ebllc';
$TB_NAME= 'Dues_emp_gaza';

$count= $offset;
?>
<div class="table-responsive tableRoundedCorner">
    <table class="table mg-b-0 text-md-nowrap  table-hover table-bordered roundedTable" id="dues_emp_gaza_tb">
        <thead class="table-light">
        <tr>
            <th>#</th>
            <th>المقر</th>
            <th>رقم الهوية</th>
            <th>اسم المشترك</th>
            <th>رقم الاشتراك</th>
            <th>نوع الاشتراك</th>
            <th>المبلغ</th>
            <th>الوظيفة</th>
            <th>غرامة التأخير</th>
            <th>نوع الاستحقاق</th>
            <th>حالة السداد</th>
            <th>شهر السداد</th>
            <th>ملاحظات</th>
        </tr>
        </thead>

        <tbody>
        <?php foreach ($page_rows as $row) : ?>

            <tr>
                <td><?= $count++ ?></td>
                <td><?= $row['BRANCH_NAME'] ?></td>
                <td><?= $row['ID'] ?></td>
                <td><?= $row['NAME'] ?></td>
                <td><?= $row['SUBSCRIBER'] ?></td>
                <td><?= $row['TYPE'] ?></td>
                <td><?= $row['VALUE'] ?></td>
                <td><?= $row['EMP_TYPE_NAME'] ?></td>
                <td><?= $row['DELAY_DUES'] ?></td>
                <td><?= $row['DUES_TYPE_NAME'] ?></td>
                <td><?= $row['PAID_NAME'] ?></td>
                <td><?= $row['PPOSTING_MONTH'] ?></td>
                <td><?= $row['NOTE'] ?></td>
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
