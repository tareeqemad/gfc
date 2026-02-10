<?php
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 17/03/18
 * Time: 10:37 ص
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
            <th>السنة</th>
            <th>مجموع الدقائق</th>
            <th>عدد الايام المخصومة من الاجازات </th>
            <!--       <th>عدد الايام التى سترحل لاجازة</th>
                   <th>عدد الايام التى ترحلت للاجازات</th>-->
            <th>المقر</th>
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
                <td><?=$row['PERM_YEAR']?></td>
                <td><?=$row['SUM_MINUTES']?></td>
                <td><?=$row['SUM_DAYS']?></td>
                <!--            <td><?=$row['SUM_DAYS_VACATION']?></td>     -->
                <td><?=$row['BRANCH_ID_NAME']?></td>
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
