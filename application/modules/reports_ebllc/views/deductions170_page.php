<?php
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 07/12/22
 * Time: 12:00 ص
 */

$MODULE_NAME= 'reports_ebllc';
$TB_NAME= 'Deductions170';

$count= $offset;
?>
<div class="table-responsive tableRoundedCorner">
    <table class="table mg-b-0 text-md-nowrap  table-hover table-bordered roundedTable" id="deductions_tb">
        <thead class="table-light">
        <tr>
            <th>#</th>
            <th>رقم الاشتراك</th>
            <th>اسم المشترك</th>
            <th>رقم هوية الموظف</th>
            <th>اسم الموظف</th>
            <th>الشهر</th>
            <th>الدفعة</th>
            <th>الحكومة</th>
        </tr>
        </thead>

        <tbody>
        <?php foreach ($page_rows as $row) : ?>

            <tr>
                <td><?= $count++ ?></td>
                <td><?= $row['SUBSCRIBER'] ?></td>
                <td><?= $row['NAME'] ?></td>
                <td><?= $row['ID'] ?></td>
                <td><?= $row['E_NAME'] ?></td>
                <td><?= $row['FOR_MONTH'] ?></td>
                <td><?= $row['E_DISCOUNT'] ?></td>
                <td><?= $row['GOV_NAME'] ?></td>
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
