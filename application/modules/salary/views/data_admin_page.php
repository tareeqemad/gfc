<?php
$count = $offset;
$report_url = base_url("JsperReport/showreport?sys=hr/salaries");

if($sal_con!= null){
    $show_con=1;
}else{
    $show_con=0;
}

?>

<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th>#</th>
            <th>رقم الموظف</th>
            <th>اسم الموظف</th>
            <th>الشهر</th>
            <th>نوع التعيين</th>
            <th>المقر</th>
            <th>الادارة</th>
            <th>البنك</th>
            <th>الحساب</th>
            <th>المسمى المهني</th>
            <?=($show_con)?'<th>البند</th>':''?>
            <th>صافي الراتب</th>
            <th>طباعة </th>
        </tr>
        </thead>
        <tbody>
        <?php if($page > 1): ?>
            <tr>
                <td colspan="15" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>

        <?php foreach($page_rows as $row) :?>
        <tr ondblclick="javascript:show_row_details('<?=$row['EMP_NO']?>', '<?=$row['MONTH']?>');" >
            <td><?=$count?></td>
            <td><?=$row['EMP_NO']?></td>
            <td><?=$row['EMP_NO_NAME']?></td>
            <td><?=$row['MONTH']?></td>
            <td><?=$row['EMP_TYPE_NAME']?></td>
            <td><?=$row['BRANCH_NAME']?></td>
            <td><?=$row['DEPARTMENT_NAME']?></td>
            <td><?=$row['BANK_NAME']?></td>
            <td><?=$row['ACCOUNT']?></td>
            <td><?=$row['W_NO_NAME']?></td>
            <?=($show_con)?"<td>{$row['SUM_CON']}</td>":''?>
            <td><?=$row['NET_SALARY']?></td>
            <td> <i class="glyphicon glyphicon-print" onclick="javascript:print_report(<?=$row['EMP_NO']?>, <?=$row['MONTH']?>, <?=$row['STATUS'].$row['Q_NO'].$row['DEGREE']?>);" > </i> </td>
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

    function print_report(emp_, month_, prv_){
        var rep_url = '<?=$report_url?>&report_type=pdf'+'&report=salary_form&p_emp_id='+emp_+'&p_salary_month='+month_+'&p_prv='+prv_;
        _showReport(rep_url);
    }

</script>
