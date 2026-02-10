<?php
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 19/09/22
 * Time: 12:30 م
 */

$MODULE_NAME= 'payroll_statement';
$TB_NAME= 'Bank_statements';

$count= $offset;
?>
<div class="table-responsive tableRoundedCorner">
    <table class="table mg-b-0 text-md-nowrap  table-hover table-bordered roundedTable" id="bank_statements_tb">
        <thead class="table-light">
        <tr>
            <th>#</th>
            <th>رقم الموظف</th>
            <th>اسم الموظف</th>
            <th>رقم الحساب</th>
            <th>رقم الهوية</th>
            <th>صافي الراتب</th>
            <th>IBAN</th>
            <th>البنك</th>
            <th>الشهر</th>
        </tr>
        </thead>

        <tbody>
        <?php foreach ($page_rows as $row) : ?>

            <tr>
                <td><?= $count++ ?></td>
                <td><?= $row['EMP_NO'] ?></td>
                <td><?= $row['EMP_NAME'] ?></td>
                <td><?= $row['ACCOUNT'] ?></td>
                <td><?= $row['ID'] ?></td>
                <td><?= $row['NET_SALARY'] ?></td>
                <td><?= $row['IBAN'] ?></td>
                <td><?= $row['BANK_NAME'] ?></td>
                <td><?= $row['MONTH'] ?></td>
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
