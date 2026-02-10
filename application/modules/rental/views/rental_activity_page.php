<?php
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 24/09/17
 * Time: 09:28 ص
 */

$count = $offset;

?>

<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th>#</th>
            <th>رقم الحركة</th>
            <th>رقم سند ملف التعاقد</th>
            <th>نوع الحركة</th>
            <th>تاريخ البداية الجديد</th>
            <th>تاريخ النهاية الجديد</th>
            <th>حالة السند</th>
            <th>تاريخ الادخال</th>
            <th>المدخل</th>
        </tr>
        </thead>
        <tbody>
        <?php if($page > 1): ?>
            <tr>
                <td colspan="10" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
        <?php foreach($page_rows as $row) :?>

            <tr ondblclick="javascript:show_row_details('<?=$row['RENTAL_ACTIVITY_ID']?>');" >
                <td><?=$count?></td>
                <td><?=$row['RENTAL_ACTIVITY_ID']?></td>
                <td><?=$row['CONTRACTOR_FILE_ID']?></td>
                <td><?=$row['ACTIVITY_TYPE_NAME']?></td>
                <td><?=$row['NEW_START_DATE']?></td>
                <td><?=$row['NEW_END_DATE']?></td>
                <td><?=$row['ADOPT_NAME']?></td>
                <td><?=$row['ENTRY_DATE']?></td>
                <td title="<?=$row['ENTRY_USER_NAME']?>"><?=get_short_user_name($row['ENTRY_USER_NAME'])?></td>
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
