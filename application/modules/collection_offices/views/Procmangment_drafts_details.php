<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 06/07/20
 * Time: 01:02 م
 */


$MODULE_NAME= 'collection_offices';
$TB_NAME= 'Procmangment';
$count = 1;
$page = 1; 

?>



<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th>#</th>
            <th>تاريخ استحقاق الكمبيالة</th>
            <th>قيمة الكمبيالة</th>
            <th>اسم محرر الكمبيالة</th>
            <th>ملاحظات</th>
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
        <?php foreach ($details as $row_det) : ?>
            <tr id="tr_<?=$row_det['SER']?>" >

                <td><?=$count?></td>
                <td><?=$row_det['CHECK_DATE']?></td>
                <td><?=$row_det['CHECK_VALUE']?></td>
                <td><?=$row_det['CHECK_EDITOR']?></td>
                <td><?=$row_det['NOTES']?></td>

                <?php
                $count++; ?>

            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>




<script>
    if (typeof initFunctions == 'function') {
        initFunctions();
    }
</script>



