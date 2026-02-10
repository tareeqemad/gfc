<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 28/01/20
 * Time: 08:53 ص
 */



$MODULE_NAME= 'training';
$TB_NAME= 'Paidtraining';
$get_url = base_url("$MODULE_NAME/$TB_NAME/get/");
$create_url = base_url("$MODULE_NAME/$TB_NAME/create_interview");
$count = 1;
?>


<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th>#</th>
            <th>رقم الهوية</th>
            <th>الاسم</th>
            <th>تاريخ التقديم</th>
            <th>السيرة الذاتية</th>
            <th>ترشيح للمقابلة</th>
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
            $status = $row['STATUS_INTERVIEW'] == 1 ? 1 : 0 ;?>
            <tr <?php if($status == 1){ ?> style="background-color: #ddd7a2;" <?php } ?>  id="tr_<?=$row['SER']?>" >
                <td><?=$count?></td>
                <td><?=$row['ID']?></td>
                <td><?=$row['NAME_']?></td>
                <td><?=$row['ENTERY_DATE']?></td>
                <td>
                    <?php if (HaveAccess($get_url)): ?>
                        <a target="_blank" href="<?=base_url("$MODULE_NAME/$TB_NAME/get/{$row['SER']}" )?>"><i class='glyphicon glyphicon-share'></i></a>
                    <?php endif; ?>
                </td>
                <td><?php if($status != 1 /*&& (HaveAccess($create_url))*/){ ?>
                    <a  onclick="adopt_trainee(<?= $row['SER'] ?> ,<?= $row['ID'] ?> );"
                        data-toggle="modal"
                        data-target="#showmsgrec"  class="btn btn-info btn-xs">ترشيح</a>
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



