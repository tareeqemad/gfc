<?php
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 02/08/23
 * Time: 08:30 ص
 */
$MODULE_NAME= 'hr';
$TB_NAME= 'Meeting_lecturer';
$get_url =base_url("$MODULE_NAME/$TB_NAME/get");

$count= $offset;
?>
<div class="table-responsive tableRoundedCorner">
    <table class="table mg-b-0 text-md-nowrap  table-hover table-bordered roundedTable" id="meeting_lecturer_tb">
        <thead class="table-light">
        <tr>
            <th>#</th>
            <th>م</th>
            <th>رقم الاجتماع</th>
            <th>عنوان الاجتماع</th>
            <th>تاريخ الاجتماع</th>
            <th>تصنيف الاجتماع</th>
            <th>حالة المحضر</th>
            <th>اسم المدخل</th>
            <th>تاريخ الادخال</th>
            <th>الاجراء</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($page_rows as $row) : ?>

            <tr ondblclick="javascript:show_row_details('<?=$row['SER']?>');">
                <td><?= $count++ ?></td>
                <td><?= $row['SER']?></td>
                <td><?= $row['MEETING_NO']?></td>
                <td><?= $row['MEETING_TITLE']?></td>
                <td><?= $row['MEETING_DATE']?></td>
                <td><?= $row['MEETING_TYPE_NAME']?></td>
                <td><?= $row['ADOPT_NAME']?></td>
                <td><?= $row['ENTRY_USER_NAME']?></td>
                <td><?= $row['ENTRY_DATE']?></td>
                <td class='text-center'>
                    <a href="javascript:;" onclick="javascript:print_report_pdf(<?= $row['SER'] ?>);" class="modal-effect" data-bs-effect="effect-rotate-left"  title="طباعة المحضر"
                       style="color: #2075f8"><i class="glyphicon glyphicon-print"></i> </a>
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
        document.getElementById("meeting_lecturer_tb").style.display="none";
        document.getElementsByClassName("pagination")[0].style.display="none";
    }
</script>
