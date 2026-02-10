<?php
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 29/06/19
 * Time: 01:53 م
 */
$count = $offset;
?>
<div class="row">
    <div class="table-responsive">
        <table class="table table-bordered" id="page_tb">
            <thead class="table-light">
            <tr>
                <th>#</th>
                <th>رقم الموظف</th>
                <th>اسم الموظف</th>
                <th>رقم الهوية</th>
                <th>الحالة الاجتماعية</th>
                <th>تاريخ الميلاد</th>
                <th>نوع التعيين</th>
                <th>المقر الرئيسي</th>
                <th>المسمى الوظيفي</th>
            </tr>
            </thead>
            <tbody>
            <?php if ($page > 1): ?>
                <tr>
                    <td colspan="12" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
                </tr>
            <?php endif; ?>
            <?php foreach ($page_rows as $row) : ?>

                <tr ondblclick="javascript:show_row_details('<?= $row['NO'] ?>');">
                    <td><?= $count ?></td>
                    <td><?= $row['NO'] ?></td>
                    <td><?= $row['NAME'] ?></td>
                    <td><?= $row['ID'] ?></td>
                    <td><?= $row['STATUS_NAME'] ?></td>
                    <td><?= $row['BIRTH_DATE'] ?></td>
                    <td><?= $row['EMP_TYPE_NAME'] ?></td>
                    <td><?= $row['BRAN_NAME'] ?></td>
                    <td><?= $row['W_NO_ADMIN_NAME'] ?></td>
                    <?php
                    $count++;
                    ?>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
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
