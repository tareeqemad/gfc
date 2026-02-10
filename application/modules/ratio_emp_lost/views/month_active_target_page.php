<?php
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 16/02/23
 * Time: 12:30 م
 */
$MODULE_NAME= 'ratio_emp_lost';
$TB_NAME= 'Month_active_target';
$get_url =base_url("$MODULE_NAME/$TB_NAME/get");

$count= $offset;
?>
<div class="table-responsive tableRoundedCorner">
    <table class="table mg-b-0 text-md-nowrap  table-hover table-bordered roundedTable" id="month_active_target_tb">
        <thead class="table-light">
        <tr>
            <th>#</th>
            <th>الرقم التسلسلي</th>
            <th>المقر</th>
            <th>النشاط</th>
            <th>الشهر</th>
            <th>المستهدف الشهري</th>
            <th>قيمة الخصم</th>
            <th>الاعتماد</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($page_rows as $row) : ?>
            <tr ondblclick="javascript:show_row_details('<?=$row['TARGET_NO']?>');">
                <td><?= $count++ ?></td>
                <td><?= $row['TARGET_NO']?></td>
                <td><?= $row['BRANCH_ID_NAME']?></td>
                <td><?= $row['ACTIVITY_NO_NAME']?></td>
                <td><?= $row['THE_MONTH']?></td>
                <td><?= $row['MONTHLY_TARGET']?></td>
                <td><?= $row['DISCOUNT_VALUE']?></td>
                <td><?= $row['ADOPT_NAME']?></td>
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
        document.getElementById("month_active_target_tb").style.display="none";
        document.getElementsByClassName("pagination")[0].style.display="none";
    }
</script>
