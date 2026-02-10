<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 21/12/15
 * Time: 12:09 م
 */

$count = $offset;
?>

<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th>#</th>
            <th>رقم المجموعة</th>
            <th>مسلسل المجموعة</th>
            <th>اسم المجموعة</th>
            <th>الفرع</th>
            <th>الخط المغذي</th>
            <th>تاريخ التعديل</th>
        </tr>
        </thead>
        <tbody>
        <?php if($page > 1): ?>
            <tr>
                <td colspan="7" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
        <?php foreach($page_rows as $row) :?>
            <tr ondblclick="javascript:show_row_details('<?=$row['GROUP_ID']?>');">
                <td><?=$count?></td>
                <td><?=$row['GROUP_ID']?></td>
                <td><?=$row['GROUP_SER']?></td>
                <td><?=$row['GROUP_NAME']?></td>
                <td><?=$row['BRANCH_NAME']?></td>
                <td><?=$row['LINE_FEEDER_NAME']?></td>
                <td><?=$row['UPDATE_DATE']?></td>
                <?php $count++; ?>
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
    if (typeof ajax_pager == 'function') {
        ajax_pager();
    }
    if (typeof show_page == 'undefined'){
        document.getElementById("page_tb").style.display="none";
        document.getElementsByClassName("pagination")[0].style.display="none";
    }
</script>
