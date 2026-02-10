<?php
$count = $offset;
?>
<div class="table-responsive">
    <table class="table table-bordered" id="page_tb">
        <thead class="table-light">
        <tr>
            <th>م</th>
            <th>المقر</th>
            <th>الموظف</th>
            <th>تاريخ الترحيل</th>
            <th>شهر الراتب</th>
            <th>الراتب الاساسي</th>
            <th>عدد الساعات المخصومة</th>
            <th>عدد ايام الخصم المعتمدة</th>
            <th>قيمة الاستقطاع</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($page > 1): ?>
            <tr>
                <td colspan="9" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
        <?php foreach ($dealyemp_rows as $row) : ?>
            <tr>
                <td><?= $count ?></td>
                <td><?= $row['BRANCH_NAME'] ?></td>
                <td><?= $row['EMP_NO'] ?> - <?= $row['EMP_NAME'] ?></td>
                <td><?= $row['DATE_R'] ?></td>
                <td><?= $row['MONTH'] ?></td>
                <td><?= $row['BASIC_SAL'] ?></td>
                <td><?= $row['CALCULATED_HOURS'] ?></td>
                <td><?= $row['DAY'] ?></td>
                <td class="text-danger"><?= number_format($row['TOTAL_DEDUCTION'],2) ?></td>
                <?php $count++; ?>
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
