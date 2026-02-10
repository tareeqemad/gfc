<?php
$MODULE_NAME= 'payroll_data';
$TB_NAME= 'withnopresence';
$count = $offset;
?>

<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th>#</th>
            <th>رقم مسلسل</th>
            <th>رقم الحركة المرحلة</th>
            <th>  رقم الموظف</th>
            <th>اسم الموظف</th>
            <th>اليوم</th>
            <th>التاريخ</th>
            <th>الوقت</th>
            <th>الحركة</th>
            <th>المقر</th>
            <th>اعتماد مالياً</th>
        </tr>
        </thead>
        <tbody>
        <?php if($page > 1): ?>
            <tr>
                <td colspan="12" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
        <?php foreach($page_rows as $row) :?>

            <tr>
                <td><?=$count?></td>
                <td><?=$row['SER']?></td>
                <td><?=$row['P_SER']?></td>
                <td><?=$row['EMP_NO']?></td>
                <td><?=$row['EMP_NAME']?></td>
                <td><?=$row['DAY_AR']?></td>
                <td><?=$row['ENTRY_DAY']?></td>
                <td><?=$row['ENTRY_TIME']?></td>
                <td><?=$row['STATUS_NAME']?></td>
                <td><?=$row['BRANCH_NAME']?></td>
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

