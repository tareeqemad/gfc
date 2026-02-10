<?php

/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 06/12/22
 * Time: 11:30 ص
 */

$MODULE_NAME= 'reports_ebllc';
$TB_NAME= 'Districts_bills';

$count= $offset;
?>
<div class="table-responsive tableRoundedCorner">
    <table class="table mg-b-0 text-md-nowrap  table-hover table-bordered roundedTable" id="districts_bills_tb">
        <thead class="table-light">
        <tr>
            <th>#</th>
            <th>المقر</th>
            <th>رقم الاشتراك</th>
            <th>اسم البلدية</th>
            <th>نوع الاشتراك</th>
            <th>تصنيف الاشتراك</th>
            <th>الشهر</th>
            <th>المحافظة</th>
            <th>الفاتورة الشهرية</th>
            <th>المتفرقات</th>
            <th>المتأخرات</th>
            <th>المبلغ المطلوب</th>
            <th>الغرامة</th>
        </tr>
        </thead>

        <tbody>
        <?php foreach ($page_rows as $row) : ?>

            <tr>
                <td><?= $count++ ?></td>
                <td><?= $row['BRNAME'] ?></td>
                <td><?= $row['SUBSCRIBER'] ?></td>
                <td><?= $row['NAME'] ?></td>
                <td><?= $row['TYPE_NAME'] ?></td>
                <td><?= $row['ORG_NAME'] ?></td>
                <td><?= $row['FOR_MONTH'] ?></td>
                <td><?= $row['DISTRICS_NAME'] ?></td>
                <td><?= $row['CONS'] ?></td>
                <td><?= $row['SERVICES'] ?></td>
                <td><?= $row['REM'] ?></td>
                <td><?= $row['NET'] ?></td>
                <td><?= $row['DELAY_CALC'] ?></td>
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
