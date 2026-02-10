<?php
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 09/07/23
 * Time: 12:30 م
 */
$MODULE_NAME= 'salary';
$TB_NAME= 'Paid_fonts';
$get_url =base_url("$MODULE_NAME/$TB_NAME/get");

$count= $offset;
?>
<div class="table-responsive tableRoundedCorner">
    <table class="table mg-b-0 text-md-nowrap  table-hover table-bordered roundedTable" id="paid_fonts_tb">
        <thead class="table-light">
        <tr>
            <th>#</th>
            <th>المقر</th>
            <th>رقم الموظف</th>
            <th>اسم الموظف</th>
            <th>رقم الجوال</th>
            <th>المبلغ المعتمد</th>
            <th>قيمة الفاتورة</th>
            <th>فرق المبلغ</th>
            <th>الجهة المستفيدة</th>
            <th>نوع الشريحة</th>
            <th>حالة الخط</th>
            <th>ملاحظة</th>
        </tr>
        </thead>

        <tbody>
        <?php foreach ($page_rows as $row) : ?>
            <tr ondblclick="javascript:show_row_details('<?=$row['SER']?>');">
                <td><?= $count++ ?></td>
                <td><?= $row['BRANCH_NAME']?></td>
                <td><?= $row['EMP_NO']?></td>
                <td><?= $row['EMP_NAME']?></td>
                <td><?= $row['JAWAL_NO']?></td>
                <td><?= $row['APPROVED_AMOUNT']?></td>
                <td><?= $row['BILL_VALUE']?></td>
                <td><?= $row['AMOUNT_DIFFERENCE']?></td>
                <td><?= $row['BENEFICIARY_NAME']?></td>
                <td><?= $row['SLIDE_TYPE_NAME']?></td>
                <td><?= $row['LINE_STATUS_NAME']?></td>
                <td><?= $row['NOTE']?></td>
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
        document.getElementById("paid_fonts_tb").style.display="none";
        document.getElementsByClassName("pagination")[0].style.display="none";
    }
</script>
