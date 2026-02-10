<?php
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 06/02/20
 * Time: 10:21 ص
 */

$count = $offset;

?>

<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th>#</th>
            <th>رقم السند</th>
            <th>رقم الموظف</th>
            <th>اسم الموظف</th>
            <th>مرحل من</th>
            <th>الى</th>
            <th>الرصيد المرحل</th>
            <th>حالة السجل</th>
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

        <tr ondblclick="javascript:show_row_details('<?=$row['SER']?>');" >
            <td><?=$count?></td>
            <td><?=$row['SER']?></td>
            <td><?=$row['EMP_NO']?></td>
            <td><?=$row['EMP_NO_NAME']?></td>
            <td><?=$row['YEAR_FROM']?></td>
            <td><?=$row['YEAR_TO']?></td>
            <td><?=$row['BALANCE_VAL']?></td>
            <td><?=$row['ADOPT_NAME']?></td>
            <td title="<?=$row['ENTRY_DATE_TIME']?>"><?=$row['ENTRY_DATE']?></td>
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
