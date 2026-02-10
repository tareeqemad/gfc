<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 27/07/20
 * Time: 12:22 م
 */

$MODULE_NAME= 'training';
$TB_NAME= 'traineeRequest';
$count = 1 ;
?>

<div class="tbl_container">
        <table class="table" id="page_tb" data-container="container">
            <thead>
            <tr>
                <th>#</th>
                <th>رقم الهوية</th>
                <th>الاسم</th>
                <th>تاريخ الميلاد</th>
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
        <td>
            <a class="btn btn-info btn-xs" href="<?=base_url("$MODULE_NAME/$TB_NAME/get_trainee/{$row['SER']}" )?>"> عرض<i class='glyphicon glyphicon-share'></i></a>
        </td>
        <?php
        $count++; ?>

    </tr>
<?php endforeach;?>
</tbody>
</table>
</div>
