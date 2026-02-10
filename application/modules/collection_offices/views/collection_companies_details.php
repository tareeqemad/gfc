<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 21/12/19
 * Time: 10:21 ص
 */

$MODULE_NAME= 'collection_offices';
$TB_NAME= 'Collection_companies';
$count = 1;
?>

<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th>#</th>
            <th>رقم الاشتراك</th>
            <th>رقم الموقع</th>
            <th>الاسم</th>
            <th>الفاتورة الشهرية</th>
            <th>غير مسدد منذ</th>
            <th>الية التسديد</th>
            <th>متأخرات</th>
            <th>القيمة المطلوبة</th>
            <th>قسط</th>
            <th>الدفعة</th>
            <th>رقم الكشف</th>
            <?php
            $count++;
            ?>
        </tr>
        </thead>
        <tbody>
        <?php if(count($details) > 0): ?>
            <tr >
                <td colspan="12"  class="page-sector"></td>
            </tr>
        <?php endif; ?>
        <?php foreach ($details as $row) : ?>
            <tr id="tr_<?=$row['SER']?>" >
                <td><?=$count-1?></td>
                <td><?=$row['SUBSCRIBER']?></td>
                <td><?=$row['LOCATION_NO']?></td>
                <td><?=$row['NAME']?></td>
                <td><?=$row['FOR_MONTH']?></td>
                <td><?=$row['LAST_MONTH_PAID']?></td>
                <td><?=$row['IS_ALY']?></td>
                <td><?=$row['REMAINDER']?></td>
                <td><?=$row['NET_TO_PAY']?></td>
                <td><?=$row['COMMIT_VALUE']?></td>
                <td><?=$row['LAST_PAID']?></td>
                <td><?=$row['DISCLOSURE_SER']?></td>
                <?php
                $count++; ?>

            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>
<script>

</script>

