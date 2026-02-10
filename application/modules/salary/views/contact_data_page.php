<?php
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 16/02/23
 * Time: 12:30 م
 */
$MODULE_NAME= 'salary';
$TB_NAME= 'Contact_data';
$get_url =base_url("$MODULE_NAME/$TB_NAME/get");

$count= $offset;
?>
<div class="table-responsive tableRoundedCorner">
    <table class="table mg-b-0 text-md-nowrap  table-hover table-bordered roundedTable" id="contact_data_tb">
        <thead class="table-light">
        <tr>
            <th>#</th>
            <th>المقر</th>
            <th>رقم الموظف</th>
            <th>اسم الموظف</th>
            <th>المسمى الوظيفي</th>
            <th>المسمى المهني</th>
            <th>الادارة</th>
            <th>رقم الجوال</th>
            <?php if (HaveAccess($get_url)  ) : ?>
                <th>عنوان السكن</th>
                <th>رقم الجوال البديل</th>
                <th>مقاس الحذاء</th>
                <th>مقاس القميص</th>
                <th>مقاس البنطلون</th>
                <th>مقاس الجاكيت</th>
            <?php endif; ?>
            <th>رقم الهاتف الارضي</th>
            <th>الايميل</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($page_rows as $row) : ?>
            <?php if (HaveAccess($get_url)  ) : ?>
                <tr ondblclick="javascript:show_row_details('<?=$row['SER']?>');">
            <?php else: ?>
            <tr>
            <?php endif; ?>
            <td><?= $count++ ?></td>
            <td><?= $row['BRANCH_NAME']?></td>
            <td><?= $row['EMP_NO']?></td>
            <td><?= $row['EMP_NAME']?></td>
            <td><?= $row['JOB_TITLE']?></td>
            <td><?= $row['VOCATIONAL_TITLE']?></td>
            <td><?= $row['HEAD_DEPARTMENT']?></td>
            <td><?= $row['JAWAL_NO']?></td>
            <?php if (HaveAccess($get_url)  ) : ?>
                <td><?= $row['ADDRESS']?></td>
                <td><?= $row['JAWAL_NO_2']?></td>
                <td><?= $row['SHOES_MEASURE_NAME']?></td>
                <td><?= $row['TSHIRT_MEASURE_NAME']?></td>
                <td><?= $row['PANTS_MEASURE_NAME']?></td>
                <td><?= $row['JACKET_MEASURE_NAME']?></td>
            <?php endif; ?>
            <td><?= $row['TEL_NO']?></td>
            <td>
                <a style="color: #0a7ffb ;text-decoration: underline;" href="mailto:<?= $row['EMAIL']?>"><?= $row['EMAIL']?></a>
            </td>
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
        document.getElementById("contact_data_tb").style.display="none";
        document.getElementsByClassName("pagination")[0].style.display="none";
    }
</script>
