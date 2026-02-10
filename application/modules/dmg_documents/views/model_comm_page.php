<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 11/07/19
 * Time: 10:14 ص
 */

$MODULE_NAME= 'dmg_documents';
$TB_NAME= 'documents';
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$count = $offset;
?>



<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th>#</th>
            <th>رقم النموذج</th>
            <th>نوع النموذج</th>
            <th>التاريخ</th>
            <th>الحالة</th>
            <th>تحرير</th>
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
                <td><?=$row['SER']?></td>
                <td><?=$row['MODEL_TYPE_NAME']?></td>
                <td><?=$row['MODEL_DATE']?></td>
                <td><?php if($row['ADOPT'] == 4 && $row['DESICION_DMG'] == 2){ ?>
                    <span class="badge badge-<?= $row['ADOPT'] ?>">غير مُتلف</span>
                <?php }elseif($row['ADOPT'] == 4 && $row['DESICION_DMG'] == 1){ ?>
                    <span class="badge badge-info">مُتلف </span>
                    <?php }elseif($row['ADOPT'] == 3){ ?>
                        <span class="badge badge-<?= $row['ADOPT'] ?>">مُحول الى اللجنة</span>
                    <?php }elseif($row['ADOPT'] == 2){ ?>
                        <span class="badge badge-<?= $row['ADOPT'] ?>">مُعتمد</span>
                    <?php }elseif($row['ADOPT'] == 1){ ?>
                        <span class="badge badge-<?= $row['ADOPT'] ?>">غير مُعتمد</span>
                    <?php }?>
                </td>

                <?php
                $count++;
                if($row['MODEL_TYPE']== 1){
                 ?>
                 <td> <a href="<?=base_url("$MODULE_NAME/documents/get_model_com/{$row['SER']}" )?>"><i class='glyphicon glyphicon-share'></i></a> </td>
                <?php } elseif($row['MODEL_TYPE']== 4) {?>
                    <td> <a href="<?=base_url("$MODULE_NAME/mails/get_model_mails_com/{$row['SER']}" )?>"><i class='glyphicon glyphicon-share'></i></a> </td>
                <?php }  elseif($row['MODEL_TYPE']== 5) {?>
                    <td> <a href="<?=base_url("$MODULE_NAME/various_doc/get_model_var_doc_com/{$row['SER']}" )?>"><i class='glyphicon glyphicon-share'></i></a> </td>
                 <?php }  elseif($row['MODEL_TYPE']== 2) {?>
                    <td> <a href="<?=base_url("$MODULE_NAME/bond/get_model_bond_com/{$row['SER']}" )?>"><i class='glyphicon glyphicon-share'></i></a> </td>
                <?php }  elseif($row['MODEL_TYPE']== 3) {?>
                    <td> <a href="<?=base_url("$MODULE_NAME/receipts_disbursements/get_model_res_dis_com/{$row['SER']}" )?>"><i class='glyphicon glyphicon-share'></i></a> </td>
                <?php }  elseif($row['MODEL_TYPE']== 6) {?>
                    <td> <a href="<?=base_url("$MODULE_NAME/input_doc/get_model_input_doc_com/{$row['SER']}" )?>"><i class='glyphicon glyphicon-share'></i></a> </td>
                <?php } ?>

            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>
<?php echo $this->pagination->create_links();?>
<script>

</script>
