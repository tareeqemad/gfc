<?php
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 06/03/23
 * Time: 11:50 ص
 */
$MODULE_NAME= 'ratio_emp_lost';
$TB_NAME= 'Works_teams_data';
$get_url =base_url("$MODULE_NAME/$TB_NAME/get");

$count= $offset;
?>
<div class="table-responsive tableRoundedCorner">
    <table class="table mg-b-0 text-md-nowrap  table-hover table-bordered roundedTable" id="works_teams_data_tb">
        <thead class="table-light">
        <tr>
            <th>#</th>
            <th>الرقم التسلسلي</th>
            <th>المقر</th>
            <th>الشهر</th>
            <th>الاعتماد</th>
            <th>تاريخ الادخال</th>
            <th>اسم المدخل</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($page_rows as $row) : ?>
            <tr ondblclick="javascript:show_row_details('<?=$row['SER']?>');">
                <td><?= $count++ ?></td>
                <td><?= $row['SER']?></td>
                <td><?= $row['BRANCH_ID_NAME']?></td>
                <td><?= $row['THE_MONTH']?></td>
                <td><?= $row['ADOPT_NAME']?></td>
                <td><?= $row['ENTRY_DATE'] ?></td>
                <td><?= $row['ENTRY_USER_NAME'] ?></td>
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
        document.getElementById("works_teams_data_tb").style.display="none";
        document.getElementsByClassName("pagination")[0].style.display="none";
    }
</script>
