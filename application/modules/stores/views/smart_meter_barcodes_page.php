<?php

/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 07/05/23
 * Time: 10:30 ص
 */

$MODULE_NAME = 'stores';
$TB_NAME = 'Smart_meter_barcodes';

$count= $offset;
?>
<div class="table-responsive tableRoundedCorner">
    <table class="table mg-b-0 text-md-nowrap  table-hover table-bordered roundedTable" id="smart_meter_barcodes_tb">
        <thead class="table-light">
        <tr>
            <th>#</th>
            <th>رقم الكشف</th>
            <th>الرقم التسلسلي</th>
            <th>المقر</th>
            <th>الجهة المستلمة</th>
            <th>رقم سند الصرف</th>
            <th>الباركود</th>
            <th>تاريخ الادخال</th>
            <th>اسم المدخل</th>
        </tr>
        </thead>

        <tbody>
        <?php foreach ($page_rows as $row) : ?>

            <tr ondblclick="javascript:show_row_details('<?=$row['SER']?>');">
                <td><?= $count++ ?></td>
                <td><?= $row['SER'] ?></td>
                <td><?= $row['SER_D'] ?></td>
                <td><?= $row['BRANCH_ID_NAME'] ?></td>
                <td><?= $row['RECEIVING_PARTY_NAME'] ?></td>
                <td><?= $row['CLASS_OUTPUT_ID'] ?></td>
                <td><?= $row['BARCODE'] ?></td>
                <td><?= $row['ENTRY_DATE'] ?></td>
                <td><?= $row['ENTRY_USER_NAME'] ?></td>
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