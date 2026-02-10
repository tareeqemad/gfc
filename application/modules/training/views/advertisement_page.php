<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 29/01/20
 * Time: 09:50 ص
 */

$MODULE_NAME= 'training';
$TB_NAME= 'advertisement';
$count = 1;


?>



<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th>#</th>
            <th>رقم الاعلان</th>
            <th>المسمى الوظيفي</th>
            <th>نوع الاعلان</th>
            <th>بداية الاعلان</th>
            <th>نهاية الاعلان</th>
            <th></th>
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
                <td><?=$row['ADVER_ID']?></td>
                <td><?=$row['ADVER_TITLE']?></td>
                <td><?=$row['ADVER_TYPE']?></td>
                <td><?=$row['START_DATE']?></td>
                <td><?=$row['END_DATE']?></td>
                <td>
                    <a href="<?=base_url("$MODULE_NAME/$TB_NAME/get/{$row['SER']}" )?>"><i class='glyphicon glyphicon-share'></i></a>
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



