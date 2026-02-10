<?php
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 20/09/17
 * Time: 11:26 ص
 */

$count = $offset;

?>

<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th>#</th>
            <th>رقم المطالبة الشهرية</th>
            <th>الفرع</th>
            <th>الشهر</th>
            <th>من</th>
            <th>الى</th>
            <th>حالة السند</th>
            <th>تاريخ الادخال</th>
            <th>المدخل</th>
        </tr>
        </thead>
        <tbody>
        <?php if($page > 1): ?>
            <tr>
                <td colspan="8" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
        <?php foreach($page_rows as $row) :?>

            <tr ondblclick="javascript:show_row_details('<?=$row['MONTHLY_CPAYMENTS_ID']?>');" >
                <td><?=$count?></td>
                <td><?=$row['MONTHLY_CPAYMENTS_ID']?></td>
                <td><?=$row['BRANCH_ID_NAME']?></td>
                <td><?=$row['PAYROLL_DATE_']?></td>
                <td><?=$row['DATE_FROM']?></td>
                <td><?=$row['DATE_TO']?></td>
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
