<?php
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 16/03/22
 * Time: 12:05 م
 */

$count= $offset;
?>

<div class="tbl_container">
    <table class="table" id="car_maintenance_workshop_tb" data-container="container">
        <thead>
        <tr>

            <th>#</th>
            <th>رقم الطلب</th>
            <th>رقم السيارة</th>
            <th>نوع السيارة</th>
            <th>صاحب العهدة</th>
            <th>ملكية السيارة</th>
            <th>تاريخ الطلب</th>
            <th>مدخل الطلب</th>
            <th>حالة الطلب </th>
            <th>تاريخ اعتماد الورشة</th>
            <th>تاريخ انجاز الصيانة</th>
            <th>مدة الصيانة - ايام</th>
            <th>المقر</th>

        </tr>
        </thead>
        <tbody>
        <?php foreach ($page_rows as $row) : ?>

            <tr ondblclick="javascript:show_row_details('<?=$row['SER']?>');">
                <td><?= $count++ ?></td>
                <td><?= $row['SER']?></td>
                <td><?= $row['CAR_ID']?></td>
                <td><?= $row['CAR_TYPE_NAME']?></td>
                <td><?= $row['CAR_ID_NAME'] ?></td>
                <td><?= $row['CAR_OWNERSHIP_NAME'] ?></td>
                <td><?= $row['ENTRY_DATE']?></td>
                <td><?= $row['ENTRY_USER_NAME']?></td>
                <td><?= $row['ADOPT_NAME']?></td>
                <td><?= $row['ADOPT_DATE_20']?></td>
                <td><?= $row['ADOPT_DATE_30']?></td>
                <td><?= $row['MAINTENANCE_DAYS']?></td>
                <td><?= $row['BRANCH_ID_NAME']?></td>
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
        document.getElementById("car_maintenance_tb").style.display="none";
        document.getElementsByClassName("pagination")[0].style.display="none";
    }
</script>