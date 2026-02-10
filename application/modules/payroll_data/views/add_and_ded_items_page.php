<?php
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 22/06/22
 * Time: 10:30 م
 */
$MODULE_NAME= 'payroll_data';
$TB_NAME= 'Add_and_ded_items';

$count= $offset;
?>

<div class="table-responsive">
    <table class="table  table-bordered" id="add_and_ded_items_tb">
        <thead class="table-light">
        <tr>

            <th>#</th>
            <th>رقم البند</th>
            <th>اسم البند</th>
            <th>الفئة</th>
            <th>نوع البند</th>
            <th>نوع الاستحقاق أو الاستقطاع</th>
            <th>القيمة</th>
            <th>المجموعة</th>
            <th>خصم الإجازة</th>
            <th>خصم الغياب</th>
            <th>حالة البند</th>

        </tr>
        </thead>
        <tbody>
        <?php foreach ($page_rows as $row) : ?>

            <tr ondblclick="javascript:show_row_details('<?=$row['NO']?>');">
                <td><?= $count++ ?></td>
                <td><?= $row['NO']?></td>
                <td><?= $row['NAME']?></td>
                <td><?= $row['IS_SPECIAL_NAME']?></td>
                <td><?= $row['IS_ADD_NAME']?></td>
                <td><?= $row['IS_CONSTANT_NAME']?></td>
                <td><?= $row['VAL']?></td>
                <td><?= $row['CON_GROUP_NAME']?></td>
                <td><?= $row['VACANCY_DED_NAME']?></td>
                <td><?= $row['IS_ABS_NAME']?></td>
                <td><?= $row['IS_UPDATE_NAME']?></td>
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
        document.getElementById("add_and_ded_items_tb").style.display="none";
        document.getElementsByClassName("pagination")[0].style.display="none";
    }
</script>
