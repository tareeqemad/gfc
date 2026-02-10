<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
$count = $offset;
?>
<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th>#</th>
            <th>رقم الهوية أو مشتغل المرخص</th>
            <th>اسم المورد</th>
            <th>رقم الجوال</th>
            <th>الإيميل</th>
            <th>النشاط</th>

        </tr>
        </thead>
        <tbody>
        <?php if ($page > 1): ?>
            <tr>
                <td colspan="10" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
        <?php foreach ($page_rows as $row) : ?>


            <tr ondblclick="javascript:show_row_details('<?= $row['CUSTOMER_ID'] ?>');">
                <td><?= $count ?></td>
                <td><?= $row['CUSTOMER_ID'] ?></td>
                <td><?= $row['CUSTOMER_NAME'] ?></td>
                <td><?= $row['MOBIL'] ?></td>
                <td><?= $row['EMAIL'] ?></td>
                <td><?= $row['ACTIVITY_NAME'] ?></td>

                <?php $count++ ?>
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
