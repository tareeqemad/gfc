<?php
$MODULE_NAME= 'payroll_data';
$TB_NAME= 'withnopresence';
$adopt_finical_url = base_url("$MODULE_NAME/$TB_NAME/adopt_finical");
$adopt_finical = HaveAccess($adopt_finical_url);
$count = $offset;
?>
<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th>#</th>
            <th>  رقم الموظف</th>
            <th>اسم الموظف</th>
            <th>الشهر</th>
            <th>عدد أيام الخصم</th>
            <th>المقر</th>
            <th>اعتماد الخصم مالياً</th>
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
                <td><?=$row['EMP_NO']?></td>
                <td><?=$row['EMP_NAME']?></td>
                <td><?=$row['THE_MONTH'] ?></td>
                <td><?=$row['COUNT_DAY']?></td>
                <td><?=$row['BRANCH_NAME']?></td>
                <td id="td_ser_<?=$row['EMP_NO']?>">
                    <?php if($adopt_finical){
                        if($row['STATUS_CHECK']==1){
                            echo "<i class='fa fa-check' title='مخصوم' style='color: #0a8800;font-size: large'></i>";
                        }else {
                            echo "<i class='glyphicon glyphicon-circle-arrow-left' style='color: #F47B0E; font-size: large' type='button' onclick=\"javascript:adopt_finical_dis( {$row['EMP_NO']}, {$row['THE_MONTH']} , '{$row['COUNT_DAY']}' );\" ></i> ";

                        }
                    }elseif (0){
                        echo "";
                    }else{
                        echo "";
                    } ?>
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


