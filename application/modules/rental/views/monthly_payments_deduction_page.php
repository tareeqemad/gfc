<?php
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 21/02/18
 * Time: 12:47 م
 */

$count = $offset;

?>

<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th>#</th>
            <th>رقم مسلسل الحركة</th>
            <th>رقم ملف الراتب</th>
            <th>رقم المطالبة الشهرية</th>
            <th>رقم سند ملف التعاقد</th>
            <th>اسم المتعاقد</th>
            <th>الاستقطاع</th>
            <th>رقم الاشتراك </th>
            <th>قيمة الاستقطاع</th>
            <th>تاريخ الادخال</th>
            <th>المدخل</th>
        </tr>
        </thead>
        <tbody>
        <?php if($page > 1): ?>
            <tr>
                <td colspan="12" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
        <?php foreach($page_rows as $row) :?>

            <tr ondblclick="javascript:show_row_details('<?=$row['D_SER']?>');" >
                <td><?=$count?></td>
                <td><?=$row['D_SER']?></td>
                <td><?=$row['SER']?></td>
                <td><?=$row['MONTHLY_CPAYMENTS_ID']?></td>
                <td><?=$row['CONTRACTOR_FILE_ID']?></td>
                <td><?=$row['CONTRACTOR_NAME']?></td>
                <td><?=$row['DEDUCTION_BILL_ID_NAME']?></td>
                <td><?=$row['SUBSCRIBER_ID']?></td>
                <td><?=$row['DEDUCTION_VALUE']?></td>
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
