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
        <thead class="table-light">
        <tr>
            <th>#</th>
            <th>رقم الموظف</th>
            <th>اسم الموظف</th>
            <th>الشهر</th>
            <th>العمر</th>
            <th>الدرجة</th>
            <th>العلاوة الدورية</th>
            <th>الراتب الاساسي</th>
            <th>علاوة المهنة</th>
            <th>تكملة 1</th>
            <th>تكملة 2</th>
            <th>علاوة ترقية</th>
            <th>علاوة تخصص</th>
            <th>غلاء معيشة</th>
            <th>حصة الموظف 10</th>
            <th>منافع موظف 7%</th>
            <th>مساهمات موظف 3%</th>
            <th>حصة الشركة 12.5%</th>
            <th>منافع مشغل 9%</th>
            <th>مساهمات مشغل 3%</th>
            <th>الإجمالي</th>
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
                <td><?= $row['PR_VALUE'] ?></td>
                <td><?= $row['JOB_ALLOWNCE_PCT'] ?></td>
                <td><?= $row['LIFE_VALUE'] ?></td>
                <td><?= $row['INSUR10'] ?></td>
                <td><?= $row['EMP_07_VALUE'] ?></td>
                <td><?= $row['EMP_03_VALUE'] ?></td>
                <td><?= $row['INSUR125'] ?></td>
                <td><?= $row['CO_09_VALUE'] ?></td>
                <td><?= $row['CO_03_VALUE'] ?></td>
                <td><?= $row['TOTAL'] ?></td>
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