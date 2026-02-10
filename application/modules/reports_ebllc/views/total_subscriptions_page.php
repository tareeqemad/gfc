<?php

/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 01/12/22
 * Time: 10:20 ص
 */

$MODULE_NAME= 'reports_ebllc';
$TB_NAME= 'Total_subscriptions';

?>
<div class="table-responsive tableRoundedCorner">
    <table class="table mg-b-0 text-md-nowrap  table-hover table-bordered roundedTable" id="total_subscriptions_tb">
        <thead class="table-light">
        <tr>
            <th>الشهر</th>
            <th>المقر</th>
            <th>عدد المشتركين</th>
            <th>الاستهلاك الشهري</th>
            <th>المتأخرات</th>
            <th>عدد حالات التحصيل</th>
            <th>إجمالي التحصيل النقدي</th>
            <th>عدد مرات الشحن</th>
            <th>مبلغ الشحن</th>
        </tr>
        </thead>

        <tbody>
        <?php foreach ($page_rows as $row) : ?>

            <tr>
                <td><?= $row['FOR_MONTH'] ?></td>
                <td><?= $row['NAME'] ?></td>
                <td><?= $row['SUBSCRIBER_COUNT'] ?></td>
                <td><?= $row['SUM_KW_CONSUMES'] ?></td>
                <td><?= $row['SUM_REMAINDER'] ?></td>
                <td><?= $row['PAID_COUNT_CASH'] ?></td>
                <td><?= $row['PAID_CASH'] ?></td>
                <td><?= $row['COUNT_PREPAID'] ?></td>
                <td><?= $row['SUM_VAL_PREPAID'] ?></td>
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
