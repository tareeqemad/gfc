<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 24/12/19
 * Time: 12:10 م
 */


$MODULE_NAME= 'collection_offices';
$TB_NAME= 'Branchs_adopt';
$count = 1;
?>



<div class="tbl_container">
    <input type="hidden" name="dis_ser" id="txt_dis_ser">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th>#</th>
            <th>رقم الاشتراك</th>
            <th>الاسم</th>
            <th>غير مسدد منذ</th>
            <th>القيمة المطلوبة</th>
            <th>اعتماد</th>
            <th>حالة الاعتماد</th>
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
        <?php foreach ($sub as $row_sub) : ?>
            <tr id="tr_<?=$row_sub['SER']?>" >

                <td><?=$count?></td>
                <td><?=$row_sub['SUBSCRIBER']?></td>
                <td><?=$row_sub['NAME']?></td>
                <td><?=$row_sub['LAST_MONTH_PAID']?></td>
                <td><?=$row_sub['NET_TO_PAY']?></td>
                <td>
                    <?php if($row_sub['ADOPT'] == 1 ){?>
                        <button type="button" onclick="javascript:changeSubStatus_('<?=$row_sub['SER']?>');"
                                class="btn btn-warning btn-xs" href='javascript:;'><i class='glyphicon glyphicon-share'>
                                </i>اعتماد</button>
                    <?php }?>
                </td>

                <td>
                    <?php if($row_sub['ADOPT'] == 2 ){?>
                        <span class="badge badge-2">معتمد</span>
                    <?php }?>
                </td>


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



