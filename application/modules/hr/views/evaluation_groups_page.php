<?php
/**
 * Created by PhpStorm.
 * User: mbadawi
 * Date: 27/06/16
 * Time: 11:51 ص
 */ 
$count = $offset;
?>
<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th>#</th>
            <th>رقم اللجنة</th>
            <th>أمر التقييم</th>
            <th>نوع اللجنة</th>
            <th>بيان تشكيل اللجنة</th>
            <th>تاريخ تشكيل اللجنة</th>
            <th>المدخل</th>
        </tr>
        </thead>
        <tbody>
         <?php  if($page > 1): ?>
            <tr>
                <td colspan="8" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
        <?php foreach($page_rows as $row) :?>

            <tr ondblclick="javascript:show_row_details('<?=$row['EGROUPS_SERIAL']?>');">
                <td><?=$count?></td>
                <td><?=$row['EGROUPS_SERIAL']?></td>
                <td><?=$row['EVALUATION_ORDER_ID']?></td>
                <td><?=$row['EVALUATION_GR_TYPE_NAME']?></td>
                <td><?=$row['EMP_MANAGER_ID']?></td>
                <td><?=$row['EVALUATION_ORDER_DATE']?></td>
                <td><?=get_user_name($row['ENTRY_USER'])?></td>
                <?php $count++ ?>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>
<?php echo $this->pagination->create_links(); ?>