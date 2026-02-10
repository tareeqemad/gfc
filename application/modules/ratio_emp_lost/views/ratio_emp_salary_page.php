<?php
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 18/07/23
 * Time: 10:30 ص
 */
$MODULE_NAME= 'ratio_emp_lost';
$TB_NAME= 'Ratio_emp_salary';
$get_url =base_url("$MODULE_NAME/$TB_NAME/get");

$count= $offset;
?>
<div class="table-responsive tableRoundedCorner">
    <table class="table mg-b-0 text-md-nowrap  table-hover table-bordered roundedTable" id="ratio_emp_salary_tb">
        <thead class="table-light">
        <tr>
            <th>#</th>
            <th>الرقم التسلسلي</th>
            <th>رقم الفرقة</th>
            <th>الشهر</th>
            <th>المقر</th>
            <th>رقم الموظف</th>
            <th>اسم الموظف</th>
            <th>نوع الموظف</th>
            <th>المبلغ المقطوع</th>
            <th>المبلغ الاضافي</th>
            <th>المبلغ التشجيعي</th>
            <th>الراتب النهائي</th>
            <th>المدخل</th>

        </tr>
        </thead>
        <tbody>
        <?php foreach ($page_rows as $row) : ?>
            <tr>
                <td><?= $count++ ?></td>
                <td><?= $row['SER']?></td>
                <td><?= $row['TEAM']?></td>
                <td><?= $row['THE_MONTH']?></td>
                <td><?= $row['BRANCH_ID_NAME']?></td>
                <td><?= $row['EMP_NO']?></td>
                <td><?= $row['EMP_NAME']?></td>
                <td><?= $row['EMP_TYPE_NAME']?></td>
                <td><?= $row['BASIC_PAID']?></td>
                <td><?= $row['ADDITIONAL_PAID']?></td>
                <td><?= $row['INCENTIVE_PAID']?></td>
                <td><?= $row['TOTAL_SAL']?></td>
                <td><?= $row['ENTRY_USER_NAME']?></td>
            </tr>
        <?php endforeach; ?>
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
        document.getElementById("ratio_emp_salary_tb").style.display="none";
        document.getElementsByClassName("pagination")[0].style.display="none";
    }
</script>
