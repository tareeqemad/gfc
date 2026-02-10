<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 28/12/19
 * Time: 11:22 ص
 */



$MODULE_NAME= 'collection_offices';
$TB_NAME= 'Pay_order';
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
            <th>الشركة المسند لها</th>
            <th>الاجراء</th>
            <?php
            $count++;
            ?>
        </tr>
        </thead>
        <tbody>
        <?php if($page > 1): ?>
            <tr >
                <td colspan="12" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
        <?php foreach ($rows as $row) : ?>
            <tr id="tr_<?=$row['SER']?>" >

                <td><?=$count?></td>
                <td><?=$row['SUBSCRIBER']?></td>
                <td><?=$row['LOCATION_NO']?></td>
                <td><?=$row['NAME']?></td>
                <td><?=$row['FOR_MONTH']?></td>
                <td><?=$row['LAST_MONTH_PAID']?></td>
                <?php if($row['IS_ALY'] == 1){ ?>
                    <td>يتبع تسديد الي</td>
                <?php }else{ ?>
                    <td>لا يتبع تسديد الي</td>
                <?php }?>
                <td><?=$row['REMAINDER']?></td>
                <td><?=$row['NET_TO_PAY']?></td>
                <td><?=$row['COMMIT_VALUE']?></td>
                <td><?=$row['LAST_PAID']?></td>
                <td><?=$row['OFFICE_NAME']?></td>
                <td>
                    <button type="button" onclick="javascript:pay_('<?=$row['SER']?>','<?=$row['FOR_MONTH']?>' ,'<?=$row['NET_TO_PAY']?>' );" class="btn btn-primary">دفع</button>

                </td>

                <?php
                $count++; ?>

            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>


<?php echo $this->pagination->create_links(); ?>


<script>
    if (typeof initFunctions == 'function') {
        initFunctions();
    }
</script>



