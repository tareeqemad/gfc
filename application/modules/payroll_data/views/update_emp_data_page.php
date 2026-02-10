<?php
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 11/12/22
 * Time: 09:30 ص
 */

$MODULE_NAME= 'payroll_data';
$TB_NAME= 'Update_emp_data';

$count= $offset;
?>
<div class="table-responsive tableRoundedCorner">
    <table class="table mg-b-0 text-md-nowrap  table-hover table-bordered roundedTable" id="update_emp_data_tb">
        <thead class="table-light">
        <tr>
            <th>#</th>
            <th>المقر</th>
            <th>رقم الموظف</th>
            <th>اسم الموظف</th>
            <th>رقم الهوية</th>
            <th>رقم الجوال</th>

        </tr>
        </thead>

        <tbody>
        <?php foreach ($page_rows as $row) : ?>

            <tr>
                <td><?= $count++ ?></td>
                <td><?= $row['BRANCH_NAME'] ?></td>
                <td><?= $row['EMP_NO'] ?></td>
                <td><?= $row['EMP_NAME'] ?></td>
                <td><?= $row['EMP_ID'] ?></td>
                <td><?= $row['JAWAL_NO'] ?></td>
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
