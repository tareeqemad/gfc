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
            <th>عدد ايام التوقيع</th>
            <th>عدد مرات الاعفاء</th>
            <th>عدد مرات الخصم</th>
            <th>عدد ايام الخصم</th>
            <th>شهر عدم الالتزام</th>
            <th>قيمة الاستقطاع</th>
            <th>حالة الاعتماد</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($page > 1): ?>
            <tr>
                <td colspan="9" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
        <?php
        foreach ($page_rows as $row) :?>

            <tr id="tr_<?= $row['THE_MONTH'] ?>">
                <td><?= $count ?></td>
                <td><?= $row['BRANCH_NAME'] ?></td>
                <td><?= $row['NO']?> - <?= $row['EMP_NAME'] ?></td>
                <td><?= $row['COUNT_NO'] ?></td>
                <td><?= $row['COUNT_PERMISSION'] ?></td>
                <td><?= $row['COUNT_MINUS'] ?></td>
                <td><?= $row['COUNT_DAY'] ?></td>
                <td><?= $row['THE_MONTH'] ?></td>
                <td class="text-danger text-center"><?= $row['TOTAL_DEDUCTION'] ?></td>
                <td><?= $row['ADOPT_STATUS_NAME'] ?></td>
                <?php
                $count++; ?>
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
