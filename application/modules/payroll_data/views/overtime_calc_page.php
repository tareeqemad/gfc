<?php
/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 25/02/2020
 * Time: 11:53 ص
 */
$MODULE_NAME= 'payroll_data';
$TB_NAME= 'overtime_calc';
$count = 0;
?>
<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th>#</th>
            <th>المقر</th>
            <th>الشهر</th>
            <th>الاجمالي الساعات</th>
            <th>اجمالي المبلغ الفعلي للمقر</th>
        </tr>
        </thead>
        <tbody>
        <?php if($page > 1): ?>
            <tr>
                <td colspan="4" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
        <?php
        foreach($page_rows as $row) :?>
            <tr id="tr_<?=$row['EMP_BRANCH']?>" >
                <td><?=$count + 1?></td>
                <td><?=$row['BRANCH_NAME']?></td>
                <td><?=$row['MONTH']?></td>
                <td><?=$row['TOTAL_CALCULATED_HOURS']?></td>
                <td><?=$row['VAL_ADOPT_BRANCH']?></td>
                <?php
                $count++; ?>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>
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


