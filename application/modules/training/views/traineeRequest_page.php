<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 23/01/20
 * Time: 11:33 ص
 */



$MODULE_NAME= 'training';
$TB_NAME= 'traineeRequest';
$count = 1;

if($trainee_source == 2){ ?>

    <div class="tbl_container">
        <table class="table" id="page_tb" data-container="container">
            <thead>
            <tr>
                <th>#</th>
                <th>رقم الهوية</th>
                <th>الاسم</th>
                <th>تاريخ الميلاد</th>
                <th>رقم الاعلان</th>
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
                    <td><?=$row['ID']?></td>
                    <td><?=$row['NAME']?></td>
                    <td><?=$row['BIRTH_DATE']?></td>
                    <td><?=$row['ADVER_ID']?></td>
                    <td>
                        <a href="<?=base_url("$MODULE_NAME/$TB_NAME/get_person/{$row['SER']}" )?>"><i class='glyphicon glyphicon-share'></i></a>
                    </td>
                    <?php
                    $count++; ?>

                </tr>
            <?php endforeach;?>
            </tbody>
        </table>
    </div>


<?php } else { ?>

    <div class="tbl_container">
        <table class="table" id="page_tb" data-container="container">
            <thead>
            <tr>
                <th>#</th>
                <th>رقم الترخيص</th>
                <th>اسم الشركة</th>
                <th>نوع الشركة</th>
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
                    <td><?=$row['LICENSE_NUM']?></td>
                    <td><?=$row['COMPANY_NAME']?></td>
                    <td><?=$row['COMPANY_TYPE']?></td>
                    <td>
                        <a href="<?=base_url("$MODULE_NAME/$TB_NAME/get_company/{$row['SER']}" )?>"><i class='glyphicon glyphicon-share'></i></a>
                    </td>
                    <?php
                    $count++; ?>

                </tr>
            <?php endforeach;?>
            </tbody>
        </table>
    </div>


<?php }?>







<script>

</script>



