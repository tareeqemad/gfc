<?php
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 23/03/23
 * Time: 10:00 ص
 */
$MODULE_NAME= 'salary';
$TB_NAME= 'Exceptional_credit';
$get_url =base_url("$MODULE_NAME/$TB_NAME/get");

$count= $offset;
?>
<div class="table-responsive tableRoundedCorner">
    <table class="table mg-b-0 text-md-nowrap  table-hover table-bordered roundedTable" id="exceptional_credit_tb">
        <thead class="table-light">
        <tr>
            <th>#</th>
            <th>الرقم التسلسلي</th>
            <th>المقر</th>
            <th>الشهر</th>
            <th>الرصيد</th>
            <th>المتبقي</th>
            <th>فئة الاتصال</th>
            <th>حالة الاعتماد</th>
            <th style="width: 10%;">تاريخ الادخال</th>
            <th style="width: 10%;">اسم المدخل</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($page_rows as $row) : ?>
            <tr ondblclick="javascript:show_row_details('<?=$row['SER']?>');">
                <td><?= $count++ ?></td>
                <td><?= $row['SER']?></td>
                <td><?= $row['BRANCH_NAME']?></td>
                <td><?= $row['THE_MONTH']?></td>
                <td><?= $row['BALANCE']?></td>
                <td><?= $row['RESIDUAL']?></td>
                <td><?= $row['CATEGORY_NAME'] ?></td>
                <td><?= $row['ADOPT_NAME'] ?></td>
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
        document.getElementById("exceptional_credit_tb").style.display="none";
        document.getElementsByClassName("pagination")[0].style.display="none";
    }
</script>
