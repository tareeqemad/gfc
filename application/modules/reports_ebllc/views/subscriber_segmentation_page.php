<?php

/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 17/05/23
 * Time: 09:00 ص
 */

$MODULE_NAME= 'reports_ebllc';
$TB_NAME= 'Subscriber_segmentation';

$count= $offset;
?>
<div class="table-responsive tableRoundedCorner">
    <table class="table mg-b-0 text-md-nowrap  table-hover table-bordered roundedTable" id="subscriber_segmentation_tb">
        <thead class="table-light">
        <tr>
            <th>#</th>
            <th>المقر</th>
            <th>رقم الطلب</th>
            <th>رقم الاشتراك الرئيسي</th>
            <th>اسم المشترك الرئيسي</th>
            <th>المديونية</th>
            <th>رقم الاشتراك الفرعي</th>
            <th>اسم المشترك الفرعي</th>
            <th>نوع الفاز</th>
            <th>نوع الاشتراك</th>
            <th>الامبير</th>
            <th>التصنيف الفرعي</th>
            <th>التصنيف الرئيسي</th>
            <th>الحصة</th>
            <th>الحصة بدون غرامة</th>
            <th>متوسط الاستهلاك قبل التركيب - 3 شهور</th>
            <th>متوسط الاستهلاك بعد التركيب - 3 شهور</th>
            <th>إجمالي الدفعات النقدية قبل التركيب - 3 شهور</th>
            <th>عدد الشهور المدفوع قبل التركيب - 3 شهور</th>
            <th>إجمالي الدفعات النقدية بعد التركيب - 3 شهور</th>
            <th>عدد الشهور المدفوع بعد التركيب - 3 شهور</th>
            <th>التسديدات النقدية</th>
            <th>الشحنات بعد التركيب</th>
        </tr>
        </thead>

        <tbody>
        <?php foreach ($page_rows as $row) : ?>

            <tr>
                <td><?= $count++ ?></td>
                <td><?= $row['BRANCH_NAME'] ?></td>
                <td><?= $row['REQUEST_APP_SERIAL'] ?></td>
                <td><?= $row['MAIN_SUB'] ?></td>
                <td><?= $row['MAIN_SUB_NAME'] ?></td>
                <td><?= $row['SHARE_VAL_ALL'] ?></td>
                <td><?= $row['SUB_NO'] ?></td>
                <td><?= $row['NAME_D'] ?></td>
                <td><?= $row['PHASE_TYPE_NAME'] ?></td>
                <td><?= $row['TYPE_NAME'] ?></td>
                <td><?= $row['AMBIR_NAME'] ?></td>
                <td><?= $row['ORG_NAME'] ?></td>
                <td><?= $row['USE_TYPE_NAME'] ?></td>
                <td><?= $row['FINAL_SHAR'] ?></td>
                <td><?= $row['SHARE_VAULE_NO_DELAY'] ?></td>
                <td><?=number_format($row['KW_CONSUME_B'],2)?></td>
                <td><?=number_format($row['KW_CONSUME_A'],2)?></td>
                <td><?=number_format($row['SUM_PAID_VALUE_B'],2)?></td>
                <td><?=number_format($row['COUNT_PAID_VALUE_B'],2)?></td>
                <td><?=number_format($row['SUM_PAID_VALUE_A'],2)?></td>
                <td><?=number_format($row['COUNT_PAID_VALUE_A'],2)?></td>
                <td><?= $row['PAID_VALUE_AFTER'] ?></td>
                <td><?= $row['CHARGE_VALUE_AFTER'] ?></td>
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
    if (typeof initFunctions == 'function') {
        initFunctions();
    }
</script>
