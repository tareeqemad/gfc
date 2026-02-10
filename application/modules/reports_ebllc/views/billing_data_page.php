<?php
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 13/12/22
 * Time: 10:20 ص
 */

$MODULE_NAME= 'reports_ebllc';
$TB_NAME= 'Billing_data';

$count= $offset;
?>
<div class="table-responsive tableRoundedCorner">
    <table class="table mg-b-0 text-md-nowrap  table-hover table-bordered roundedTable" id="billing_data_tb">
        <thead class="table-light">
        <tr>
            <th>#</th>
            <th>رقم الاشتراك</th>
            <th>الشهر</th>
            <th>المقر</th>
            <th>رقم الموقع</th>
            <th>الاسم</th>
            <th>نوع الفاز</th>
            <th>متوسط 6kmhاشهر</th>
            <th>الاستهلاكkmh </th>
            <th>النسبة من متوسط %</th>
            <th>الفاتورة الشهرية</th>
            <th>متأخرات</th>
            <th>القيمة المطلوبة</th>
            <th>قسط</th>
            <th>الدفعة</th>
            <th>المنطقة</th>
            <th>البلدية</th>
        </tr>
        </thead>

        <tbody>
        <?php foreach ($page_rows as $row) : ?>

            <tr>
                <td><?= $count++ ?></td>
                <td><?= $row['SUBSCRIBER'] ?></td>
                <td><?= $row['FOR_MONTH'] ?></td>
                <td><?= $row['CENTER'] ?></td>
                <td><?= $row['LOCATION_NO'] ?></td>
                <td><?= $row['NAME'] ?></td>
                <td><?= $row['PHASE_TYPE_NAME'] ?></td>
                <td><?= number_format($row['KW_AVG'] ,2) ?></td>
                <td><?= $row['KW_CONSUMES'] ?></td>

                <?php if ($row['KW_CONSUMES'] == 0 ) :  ?>
                    <td>0</td>
                <?php else : ?>
                    <td><?= number_format(($row['KW_CONSUMES']/$row['KW_AVG'] )*100,2)?></td>
                <?php endif; ?>

                <td><?= $row['CONS'] ?></td>
                <td><?= $row['REMAINDER'] ?></td>
                <td><?= $row['NET_TO_PAY'] ?></td>
                <td><?= $row['CCOMIT'] ?></td>
                <td><?= $row['P_VALUE'] ?></td>
                <td><?= $row['REGION_NAME'] ?></td>
                <td><?= $row['DISTRICT_NAME'] ?></td>
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
