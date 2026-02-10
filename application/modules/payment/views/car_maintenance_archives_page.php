<?php
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 23/10/22
 * Time: 13:50 م
 */

$count= $offset;
?>

<div class="tbl_container">
    <table class="table" id="car_maintenance_archives_tb" data-container="container">
        <thead>
        <tr>

            <th>#</th>
            <th>رقم الطلب</th>
            <th>تاريخ الطلب</th>
            <th>رقم السيارة</th>
            <th>صاحب العهدة</th>
            <th>وصف العطل</th>
            <th>الوصف الفني للورشة</th>
            <th>اسم السائق</th>
            <th>المقر</th>
            <th>مدخل الطلب</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($page_rows as $row) : ?>

            <tr>
                <td><?= $count++ ?></td>
                <td><?= $row['MAINTAIN_NO']?></td>
                <td><?= $row['DATE_ORDER']?></td>
                <td><?= $row['CAR_ID']?></td>
                <td><?= $row['CAR_ID_NAME']?></td>
                <td><?= $row['MASTER_NOTE']?></td>
                <td><?= $row['TECHNICAL_NOTE']?></td>
                <td><?= $row['DRIVE_NAME']?></td>
                <td><?= $row['BRANCH_ID_NAME']?></td>
                <td><?= $row['EMP_NO_NAME']?></td>
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
    if (typeof show_page == 'undefined'){
        document.getElementById("car_maintenance_archives_tb").style.display="none";
        document.getElementsByClassName("pagination")[0].style.display="none";
    }
</script>
