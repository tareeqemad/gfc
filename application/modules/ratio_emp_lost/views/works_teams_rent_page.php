<?php
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 09/03/23
 * Time: 10:30 ص
 */
$MODULE_NAME= 'ratio_emp_lost';
$TB_NAME= 'Works_teams_rent';
$get_url =base_url("$MODULE_NAME/$TB_NAME/get");

$count= $offset;
?>
<div class="table-responsive tableRoundedCorner">
    <table class="table mg-b-0 text-md-nowrap  table-hover table-bordered roundedTable" id="works_teams_rent_tb">
        <thead class="table-light">
        <tr>
            <th>#</th>
            <th>الرقم التسلسلي</th>
            <th>المقر</th>
            <th>النشاط</th>
            <th>الشهر</th>
            <th>الفرقة</th>
            <th>نسبة الانجاز %</th>
            <th>إجمالي الأجرة</th>
            <th>إجمالي الخصم</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($page_rows as $row) : ?>
            <tr>
                <td><?= $count++ ?></td>
                <td><?= $row['SER']?></td>
                <td><?= $row['BRANCH_ID_NAME']?></td>
                <td><?= $row['ACTIVITY_NO_NAME']?></td>
                <td><?= $row['THE_MONTH']?></td>
                <td><?= $row['TEAM']?></td>
                <td><?= $row['COMPLETION_RATE']?></td>
                <td><?= $row['ADDED_VAL']?></td>
                <td><?= $row['DISCOUNT_VAL']?></td>
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
        document.getElementById("works_teams_tb").style.display="none";
        document.getElementsByClassName("pagination")[0].style.display="none";
    }
</script>
