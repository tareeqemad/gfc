<?php
$count = $offset;
?>
<div class="table-responsive">
    <table class="table table-bordered" id="page_tb">
        <thead class="table-light">
        <tr>
            <th style="width: 5%;">م</th>
            <th style="width: 5%;">المقر</th>
            <th style="width: 5%;">رقم الموظف</th>
            <th style="width: 15%;">الموظف</th>
            <th style="width: 10%;" class="text-center">الراتب الاساسي في شهر الاحتساب</th>
            <th style="width: 10%;" class="text-center">من شهر</th>
            <th style="width: 10%;" class="text-center">الى شهر</th>
            <th style="width: 10%;" class="text-center">القيمة</th>
            <th style="width: 10%;"></th>
        </tr>
        </thead>
        <tbody>
        <?php if($page > 1): ?>
            <tr>
                <td colspan="9" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
        <?php foreach($page_rows as $row) :?>
            <tr>
                <td><?=$count?></td>
                <td><?=$row['BRANCH_NAME']?></td>
                <td><?=$row['EMP_NO'] ?></td>
                <td><?=$row['EMP_NAME']?></td>
                <td class="text-center text-primary"><?= number_format($row['B_SALARY'],2)?></td>
                <td class="text-center"><?=$row['FROM_MONTH']?></td>
                <td class="text-center"><?=$row['TO_MONTH']?></td>
                <td class="text-center text-danger"><?= number_format($row['VALUE'],2)?></td>
                <td></td>
                <?php $count++; ?>
            </tr>
        <?php endforeach;?>
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
