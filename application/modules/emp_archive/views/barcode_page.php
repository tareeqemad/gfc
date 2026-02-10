<?php
$count= $offset;
?>
<div class="table-responsive">
    <table class="table table-bordered" id="barcode_tb">
        <thead class="table-light">
        <tr>
            <th>#</th>
            <th>الموظف</th>
            <th>التصنيف</th>
            <th>الباركود</th>
            <th colspan="2" class="text-center">بيانات الادخال</th>
            <th>الاجراء</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($page_rows as $row) : ?>
            <tr ondblclick="javascript:show_row_details('<?=$row['SER']?>');">
                <td><?= $count++ ?></td>
                <td><?= $row['EMP_NO_NAME']?></td>
                <td><?= $row['TYPE_NAME']?></td>
                <td><?= $row['BARCODE']?></td>
                <td><?= $row['ENTRY_USER_NAME']?></td>
                <td><?= $row['ENTRY_DATE_TIME']?></td>
                <td class='text-center'>
                    <a href="javascript:;" onclick="javascript:print_report_pdf(<?= $row['SER'] ?>);" class="modal-effect" data-bs-effect="effect-rotate-left"  title="طباعة القرار"
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
</script>
