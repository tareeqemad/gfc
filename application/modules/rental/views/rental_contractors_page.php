<?php
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 28/08/17
 * Time: 11:50 ص
 */


$count = $offset;

?>

<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th>#</th>
            <th>رقم سند التعاقد</th>
            <th>نوع الخدمة</th>
            <th>الفرع التابع له</th>
            <th>رقم هويه المتعاقد</th>
            <th>اسم المتعاقد</th>
            <th>حالة التعاقد</th>
            <th>تاريخ الادخال</th>
            <th>المدخل</th>
            <th>حالة الطلب</th>
        </tr>
        </thead>
        <tbody>
        <?php if($page > 1): ?>
            <tr>
                <td colspan="11" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
        <?php foreach($page_rows as $row) :?>

            <tr class="<?=($row['EXPIRED'])?'case_0':''?>" ondblclick="javascript:show_row_details('<?=$row['CONTRACTOR_FILE_ID']?>');" >
                <td><?=$count?></td>
                <td><?=$row['CONTRACTOR_FILE_ID']?></td>
                <td><?=$row['SERVICE_TYPE_NAME']?></td>
                <td><?=$row['BRANCH_NAME']?></td>
                <td><?=$row['CONTRACTOR_ID']?></td>
                <td><?=$row['CONTRACTOR_NAME']?></td>
                <td title="<?=$row['CASE_DATE']?>"><?=$row['CONTRACT_CASE_NAME']?></td>
                <td><?=$row['ENTRY_DATE']?></td>
                <td title="<?=$row['ENTRY_USER_NAME']?>"><?=get_short_user_name($row['ENTRY_USER_NAME'])?></td>
                <td><?=$row['ADOPT_NAME']?></td>
                <?php
                $count++;
                ?>
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
