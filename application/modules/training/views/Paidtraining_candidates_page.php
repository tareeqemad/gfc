<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 11/07/20
 * Time: 10:00 ص
 */



$MODULE_NAME= 'training';
$TB_NAME= 'Paidtraining';
$count = 1;
?>


<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th>#</th>
            <th>رقم الهوية</th>
            <th>الاسم</th>
            <th>المجال</th>
            <th>الادارة</th>
            <th>تاريخ التقديم</th>
            <th>السيرة الذاتية</th>
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
        <?php foreach ($rows as $row) :
            $status = $row['STATUS'] == 1 ? 1 : 0 ;?>
            <tr <?php if($status == 1){ ?> style="background-color: #c3ddcd;" <?php } ?>  id="tr_<?=$row['SER']?>" >
                <td><?=$count?></td>
                <td><?=$row['ID']?></td>
                <td><?=$row['NAME']?></td>
                <td><?=$row['FIELD']?></td>
                <td><?=$row['MANAGE_NAME']?></td>
                <td><?=$row['ENTERY_DATE']?></td>
                <td>
                    <a target="_blank" href="<?=base_url("$MODULE_NAME/$TB_NAME/get/{$row['SER']}" )?>"><i class='glyphicon glyphicon-share'></i></a>
                </td>
                <td><?php if($status != 1){ ?>
                        <a  onclick="adopt_trainee(<?= $row['SER'] ?> ,<?= $row['ID'] ?> );"
                            data-toggle="modal"
                            data-target="#showmsgrec"  class="btn btn-success btn-xs">اعتماد</a>
                    <?php } ?>
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



