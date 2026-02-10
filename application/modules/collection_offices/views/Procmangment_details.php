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
            <th>رقم الشيك</th>
            <th>تاريخ استحقاق الشيك</th>
            <th>قيمة الشيك</th>
            <th>اسم محرر الشيك</th>
            <th>اسم البنك</th>
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
                <td><?=$row_det['CHECK_NO']?></td>
                <td><?=$row_det['CHECK_DATE']?></td>
                <td><?=$row_det['CHECK_VALUE']?></td>
                <td><?=$row_det['CHECK_EDITOR']?></td>
                <td><?=$row_det['BANK_NAME']?></td>
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



