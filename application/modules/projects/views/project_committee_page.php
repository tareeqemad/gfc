<?php
/**
 * Created by PhpStorm.
 * User: mbadawi
 * Date: 28/07/16
 * Time: 11:10 ص
 */ 
$count = $offset;
?>
<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th>#</th>
            <th>رقم اللجنة</th>
            <th>مسمى اللجنة</th>
            <th>نوع اللجنة</th>
            <th>المقر</th>
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

            <tr ondblclick="javascript:show_row_details('<?=$row['COMMITTEE_SER']?>');">
                <td><?=$count?></td>
                <td><?=$row['COMMITTEE_SER']?></td>
                <td><?=$row['TITLES']?></td>
                <td><?=$row['THE_TYPE']?></td>
                <td><?=$row['BRANCH']?></td>
                <td><?=get_user_name($row['ENTRY_USER'])?></td>
                <?php $count++ ?>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>
<?php echo $this->pagination->create_links(); ?>