<?php
/**
 * Created by PhpStorm.
 * User: mtaqia
 * Date: 28/09/16
 * Time: 06:22 م
 */

$count = $offset;

?>

<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th>#</th>
            <th>أمر التقييم</th>
            <th>الموظف</th>
            <th>نسبة الخصم</th>
            <th>الحالة</th>
            <th>تاريخ الإدخال</th>
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
            <tr class="" ondblclick="javascript:show_row_details('<?=$row['SER']?>');">
                <td><?=$count?></td>
                <td><?=$row['EVALUATION_ORDER_ID']?></td>
                <td><?=$row['EMP_ID_NAME']?></td>
                <td><?=$row['DISCOUNT']?></td>
                <td><?=$row['ADOPT_NAME']?></td>
                <td><?=$row['ENTRY_DATE']?></td>
                <td><?=$row['ENTRY_USER_NAME']?></td>
            </tr>
            <?php
            $count++;
        endforeach;
        ?>
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