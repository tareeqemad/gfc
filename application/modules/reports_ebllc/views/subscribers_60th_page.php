<?php

/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 28/02/23
 * Time: 10:00 ص
 */

$MODULE_NAME= 'reports_ebllc';
$TB_NAME= 'Subscribers_60th';

$count= $offset;
?>
<div class="table-responsive tableRoundedCorner">
    <table class="table mg-b-0 text-md-nowrap  table-hover table-bordered roundedTable" id="subscribers_60th_tb">
        <thead class="table-light">
        <tr>
            <th>#</th>
            <th>المقر</th>
            <th>رقم الاشتراك</th>
            <th>اسم المشترك</th>
            <th>رقم العداد</th>
            <th>نوع الفاز</th>
            <th>نوع الاشتراك</th>
            <th>الامبير</th>
            <th>التصنيف الفرعي</th>
            <th>التصنيف الرئيسي</th>
            <th>حالة الاستهداف</th>
            <th>حالة العداد</th>
            <th>حالة التركيب</th>
            <th>حالة التركيب(معاملة تغيير عداد)</th>
            <th>اول شهر لتغيير العداد لذكي</th>
            <th>شهرالفاتورة</th>
            <th>قيمة الفاتورة</th>
            <th>قيمة المتأخرات</th>
<!--            <th> KW الاستهلاك</th>-->
            <th>قيمة الدفعات المسبقة المترصدة</th>
            <th>متوسط الاستهلاك قبل التركيب - 3 شهور</th>
            <th>متوسط الاستهلاك بعد التركيب - 3 شهور</th>
            <th>إجمالي الدفعات النقدية قبل التركيب - 3 شهور</th>
            <th>عدد الشهور المدفوع قبل التركيب - 3 شهور</th>
            <th>إجمالي الدفعات النقدية بعد التركيب - 3 شهور</th>
            <th>عدد الشهور المدفوع بعد التركيب - 3 شهور</th>
<!--            <th>اجمالي دفعات - شيكل </th>-->
<!--            <th>اجمالي قيمة الشحنات - شيكل</th>-->
            <th>الاجراء</th>
        </tr>
        </thead>

        <tbody>
        <?php foreach ($page_rows as $row) : ?>

            <tr>
                <td><?= $count++ ?></td>
                <td><?= $row['BRANCH_NAME'] ?></td>
                <td><?= $row['SUB'] ?></td>
                <td><?= $row['NAME'] ?></td>
                <td><?= $row['KW_COUNTER_NO'] ?></td>
                <td><?= $row['PHASE_TYPE_NAME'] ?></td>
                <td><?= $row['TYPE_NAME'] ?></td>
                <td><?= $row['AMBIR_NAME'] ?></td>
                <td><?= $row['ORG_NAME'] ?></td>
                <td><?= $row['USE_TYPE_NAME'] ?></td>
                <td><?= $row['TARGETING_STATUS'] ?></td>
                <td><?= $row['COUNTER_STATUS'] ?></td>
                <td><?= $row['STATUS_NAME'] ?></td>
                <td><?= $row['INSTALLATION_STATUS'] ?></td>
                <td><?= $row['MIN_SMART_MON'] ?></td>
                <td><?= $row['FOR_MONTH'] ?></td>
                <td><?= $row['BILL_MON'] ?></td>
                <td><?= $row['REMAINDER'] ?></td>
<!--                <td>--><?//= $row['KW_SMART_CONSUME'] ?><!--</td>-->
                <td><?= $row['ADVANCE_PAYMENT'] ?></td>
                <td><?=number_format($row['KW_CONSUME_B'],2)?></td>
                <td><?=number_format($row['KW_CONSUME_A'],2)?></td>
                <td><?=number_format($row['SUM_PAID_VALUE_B'],2)?></td>
                <td><?= $row['COUNT_MONTHS_PAID_B'] ?></td>
                <td><?=number_format($row['SUM_PAID_VALUE_A'],2)?></td>
                <td><?= $row['COUNT_MONTHS_PAID_A'] ?></td>
<!--                <td>--><?//= $row['PAID_VALUE'] ?><!--</td>-->
<!--                <td>--><?//= $row['CHARGE_VALUE'] ?><!--</td>-->
                <td class='text-center'>
                        <a onclick="show_detail_row(<?= $row['SUB'] ?> , <?= $row['MIN_SMART_MON'] ?> );" class="modal-effect"   data-bs-effect="effect-rotate-left" data-bs-toggle="modal" href="#DetailModal" title="التحصيلات"
                           style="color: #2075f8"><i class="glyphicon glyphicon-eye-open"></i> </a>
                </td>
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
