<?php
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 15/01/23
 * Time: 11:10 م
 */
$MODULE_NAME= 'reports_ebllc';
$TB_NAME= 'Tariff';

$count= $offset;
?>
<div class="table-responsive tableRoundedCorner">
    <table class="table mg-b-0 text-md-nowrap  table-hover table-bordered roundedTable" id="tariff_tb">
        <thead class="table-light">
        <tr>
            <th>#</th>
            <th>المقر</th>
            <th>رقم الاشتراك</th>
            <th>اسم المشترك</th>
            <th>نوع الاشتراك</th>
            <th>التصنيف الفرعي</th>
            <th>المحافظة</th>
            <th>المنطقة</th>
            <th>نوع الفاز</th>
            <th>قوة الامبير</th>
            <th>العنوان</th>
            <th>تعرفة KW</th>
            <th>تعرفة الهولي</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($page_rows as $row) : ?>

            <tr>
                <td><?= $count++ ?></td>
                <td><?= $row['BRNAME'] ?></td>
                <td><?= $row['NO'] ?></td>
                <td><?= $row['NAME'] ?></td>
                <td><?= $row['TYPE_NAME'] ?></td>
                <td><?= $row['ORG_NAME'] ?></td>
                <td><?= $row['DISTRICT_NAME'] ?></td>
                <td><?= $row['REGION_NAME'] ?></td>
                <td><?= $row['PHASE_TYPE_NAME'] ?></td>
                <td><?= $row['AMBIR_NAME'] ?></td>
                <td><?= $row['ADDRESS'] ?></td>
                <td><?= $row['KW_PRICE'] ?></td>
                <td><?= $row['HOLLY_PRICE'] ?></td>
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
