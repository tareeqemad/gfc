<?php

/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 06/03/23
 * Time: 10:00 ص
 */
$MODULE_NAME= 'reports_ebllc';
$TB_NAME= 'All_services_master';

$count= $offset;
?>
<div class="table-responsive tableRoundedCorner">
    <table class="table mg-b-0 text-md-nowrap  table-hover table-bordered roundedTable" id="all_services_master_tb">
        <thead class="table-light">
        <tr>
            <th>#</th>
            <th>المقر</th>
            <th>رقم الاشتراك</th>
            <th>اسم المشترك</th>
            <th>المبلغ المطلوب</th>
            <th>مبلغ المتأخرات</th>
            <th>المبلغ</th>
            <th>الشهر</th>
            <th>رقم الجوال</th>
            <th> الحالة</th>
            <th>نوع الحالة</th>
            <th>نوع الاشتراك</th>
            <th>آلية السداد</th>
            <th>نوع الاستخدام</th>
            <th>الموقع</th>
            <th>المنطقة</th>
            <th>تسديد المبالغ (شحنات)</th>
            <th>تسديد المبلغ (المتأخرات)</th>
        </tr>
        </thead>

        <tbody>
        <?php foreach ($page_rows as $row) : ?>

            <tr>
                <td><?= $count++ ?></td>
                <td><?= $row['BRANCH_NAME'] ?></td>
                <td><?= $row['SUBSCRIBER'] ?></td>
                <td><?= $row['NAME'] ?></td>
                <td><?= $row['NET_TO_PAY'] ?></td>
                <td><?= $row['REMAINDER'] ?></td>
                <td><?= $row['VALUE'] ?></td>
                <td><?= $row['FOR_MONTH'] ?></td>
                <td><?= $row['TEL'] ?></td>
                <td><?= $row['SERVICE_NO_NAME'] ?></td>
                <td><?= $row['SERVICES_TYPES_NAME'] ?></td>
                <td><?= $row['TYPE_NAME'] ?></td>
                <td><?= $row['IS_ALY_NAME'] ?></td>
                <td><?= $row['USE_TYPE_NAME'] ?></td>
                <td><?= $row['LOCATION_NO'] ?></td>
                <td><?= $row['REGION_NAME'] ?></td>
                <td><?= $row['CHARGE_SMART_HOLLY'] ?></td>
                <td><?= $row['PAID_REM_SMART_HOLLY'] ?></td>
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
