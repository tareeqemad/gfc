<?php
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 30/08/17
 * Time: 12:56 م
 */

$count = $offset;

?>

<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th>#</th>
            <th>م</th>
            <th>رقم سند التعاقد</th>
            <th>رقم هوية السائق</th>
            <th>اسم السائق</th>
            <th>نوع رخصة السائق</th>
            <th>تاريخ انتهاء الرخصة</th>
            <th>حالة عمل السائق</th>
            <th>تاريخ الادخال</th>
            <th>المدخل</th>
        </tr>
        </thead>
        <tbody>
        <?php if($page > 1): ?>
            <tr>
                <td colspan="11" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
        <?php foreach($page_rows as $row) :?>

            <tr ondblclick="javascript:show_row_details('<?=$row['SER']?>');" >
                <td><?=$count?></td>
                <td><?=$row['SER']?></td>
                <td><?=$row['CONTRACTOR_FILE_ID']?></td>
                <td><?=$row['DRIVER_ID']?></td>
                <td><?=$row['DRIVER_NAME']?></td>
                <td><?=$row['LICENSE_TYPE_NAME']?></td>
                <td><?=$row['LICENSE_END_DATE']?></td>
                <td><?=$row['DRIVER_WORK_CASE_NAME']?></td>
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
