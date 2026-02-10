<?php
/**
 * Created by PhpStorm.
 * User: mbadawi
 * Date: 13/06/16
 * Time: 11:15 ص
 */ 
$count = $offset;
?>
<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th>#</th>
            <th>رقم التقييم</th>
            <th>نوع التقييم</th>
            <th>المسمى الإشرافي</th>
            <th>بيان محور التقييم</th>
            <th>الوزن النسبي</th>
            <th>الوزن النسبي الإشرافي</th>
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

            <tr ondblclick="javascript:show_row_details('<?=$row['EEXTRA_ID']?>');">
                <td><?=$count?></td>
                <td><?=$row['EEXTRA_ID']?></td>
                <td><?=$row['EEXTRA_FORM_ID_NAME']?></td>
                <td><?=$row['SUPERVISION_NAME']?></td>
                <td><?=$row['NOTE']?></td>
                <td><?=$row['RELATIVE_WEIGHT']?></td>
                <td><?=$row['SUPERVISION_WEIGHT']?></td>
                <td><?=get_user_name($row['ENTRY_USER'])?></td>
                <?php $count++ ?>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>
<?php echo $this->pagination->create_links(); ?>