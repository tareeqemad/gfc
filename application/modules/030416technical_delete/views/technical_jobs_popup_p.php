<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 20/08/15
 * Time: 10:21 ص
 */

$count = $offset;

?>

<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th>#</th>
            <th>رقم المهمة</th>
            <th>اسم  المهمة</th>
            <th>توصيف  المهمة</th>
            <th> مدة الاجراءات (دقيقة) </th>
        </tr>
        </thead>
        <tbody>
        <?php if($page > 1): ?>
            <tr>
                <td colspan="5" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
        <?php foreach($page_rows as $row) :?>
            <tr ondblclick="javascript:show_row_details('<?=$row['JOB_ID']?>','<?=$row['JOB_NAME']?>','<?=$row['JOB_TIME_SUM']?>');">
                <td><?=$count?></td>
                <td><?=$row['JOB_ID']?></td>
                <td><?=$row['JOB_NAME']?></td>
                <td><?=$row['JOB_DESCRIPTION']?></td>
                <td><?=$row['JOB_TIME_SUM']?></td>
                <?php $count++ ?>
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
