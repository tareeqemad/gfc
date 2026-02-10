<?php
$count = $offset;
 function return_color($id){
     if ($id == 3) {
         return 'danger';
     }elseif ($id == 2){
         return  'success';
     }elseif($id == 1){
         return  'primary';
     }
 }
?>
<div class="table-responsive">
    <table class="table table-bordered" id="page_tb">
        <thead class="table-light">
        <tr>
            <th>م</th>
            <th>المقر</th>
            <th> رقم</th>
            <th> الموظف</th>
            <th>الراتب الاساسي</th>
            <th> البدل</th>
            <th> البند</th>
            <th>القيمة</th>
            <th>من شهر</th>
            <th>الى شهر</th>
            <th>خاضع للضريبة</th>
            <th>الملاحظة</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($page > 1): ?>
            <tr>
                <td colspan="12" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
        <?php foreach ($page_rows as $row) : ?>
            <tr>
                <td><?= $count++ ?></td>
                <td><?= $row['BRANCH_NAME'] ?></td>
                <td><?= $row['EMP_NO']?></td>
                <td><?= $row['EMP_NAME'] ?></td>
                <td class="text-center"><?= $row['BASIC_SAL']?></td>
                <td class="text-<?= return_color($row['BADL_TYPE'])?>"><?= $row['BADL_NAME'] ?></td>
                <td><?= $row['CON_NAME'] ?></td>
                <td class="text-center text-primary"><?= $row['REAL_VAL'] ?></td>
                <td><?= $row['FROM_MONTH'] ?></td>
                <td><?= $row['TO_MONTH'] ?></td>
                <td><?= $row['IS_TAXED_NAME'] ?></td>
                <td><?= $row['NOTES'] ?></td>
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