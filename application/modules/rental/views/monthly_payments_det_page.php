<?php
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 26/09/17
 * Time: 01:10 م
 */

$count = $offset;

?>

<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th>#</th>
            <th>م</th>
            <th>رقم المطالبة الشهرية</th>
            <th>رقم سند ملف التعاقد</th>
            <th>رقم هوية المتعاقد</th>
            <th>اسم المتعاقد</th>
            <th>رقم السيارة</th>
            <th>صافي الراتب</th>
        </tr>
        </thead>
        <tbody>
        <?php if($page > 1): ?>
            <tr>
                <td colspan="9" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
        <?php foreach($page_rows as $row) :?>

            <tr ondblclick="javascript:show_row_details('<?=$row['SER']?>');" >
                <td><?=$count?></td>
                <td><?=$row['SER']?></td>
                <td><?=$row['MONTHLY_CPAYMENTS_ID']?></td>
                <td><?=$row['CONTRACTOR_FILE_ID']?></td>
                <td><?=$row['CONTRACTOR_ID']?></td>
                <td><?=$row['CONTRACTOR_NAME']?></td>
                <td><?=$row['CAR_NUM']?></td>
                <td><?=$row['NET_SALARY']?></td>
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
