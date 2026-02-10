<?php
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 21/09/22
 * Time: 12:30 م
 */

$MODULE_NAME= 'payroll_statement';
$TB_NAME= 'Insurance_and_pensions';

$count= $offset;
?>
<div class="table-responsive tableRoundedCorner">
    <table class="table mg-b-0 text-md-nowrap  table-hover table-bordered roundedTable" id="insurance_and_pensions_tb">
        <thead class="table-info">
        <tr>
            <th>#</th>
            <th>البيان</th>
            <th>اجمالي المعاملات</th>
            <th>عدد المعاملات المنفذة و المنجزة</th>
            <th>عدد المعاملات الغير منجزة</th>
            <th>عدد المعاملات التي تجاوزت المدة</th>
            <th>النسبة المئوية لتقييم اللجان%</th>
            <th>التقدير</th>
            <th>ارسال  اشعار لغير ملتزمين</th>
            <th>التقييم السنوي للجان</th>
            <th>المكافأة</th>
        </tr>
        </thead>

        <tbody>
        <?php foreach ($page_rows as $row) : ?>

            <tr>
                <td><?= $count++ ?></td>
                <td><?= $row['EMP_NO'] ?></td>
                <td><?= $row['EMP_NAME'] ?></td>
                <td><?= $row['MONTH'] ?></td>
                <td><?= $row['AGE'] ?></td>
                <td><?= $row['GRD_NAME'] ?></td>
                <td><?= $row['PERIODICAL_ALLOWNCES'] ?></td>
                <td><?= $row['BS_VALUE'] ?></td>
                <td><?= $row['VALUE'] ?></td>
                <td><?= $row['VALUECOM1'] ?></td>
                <td><?= $row['VALUECOM2'] ?></td>

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