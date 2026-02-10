<?php
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 23/06/22
 * Time: 12:00 م
 */
$MODULE_NAME= 'payroll_data';
$TB_NAME= 'Salarys_grades';

$count=1;
?>

<div class="table-responsive">
    <table class="table table-bordered" id="salarys_grades_archives_tb" data-container="container">
        <thead class="table-primary">
        <tr>
            <th>#</th>
            <th>الرقم التسلسلي</th>
            <th>الدرجة</th>
            <th>الراتب الاساسي</th>
            <th>نوع الموظف</th>
            <th>تاريخ التعديل</th>
            <th>معدل البيانات</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($page_rows as $row) : ?>

            <tr >
                <td><?=$count?></td>
                <td><?= $row['NO']?></td>
                <td><?= $row['NAME']?></td>
                <td><?= $row['BASIC_SALARY']?></td>
                <td><?= $row['EMP_TYPE']?></td>
                <td><?= $row['GFC_REF_DATE']?></td>
                <td><?= $row['GFC_REF_USER']?></td>
                <?php
                $count++;
                ?>
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
        document.getElementById("salarys_grades_archives_tb").style.display="none";
        document.getElementsByClassName("pagination")[0].style.display="none";
    }
</script>
