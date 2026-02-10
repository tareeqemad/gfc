<?php
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 27/06/22
 * Time: 11:30 م
 */
$MODULE_NAME= 'payroll_data';
$TB_NAME= 'Salary_benef';

$count = $offset;
?>
<div class="table-responsive tableRoundedCorner">
    <table class="table mg-b-0 text-md-nowrap roundedTable table-bordered" id="page_tb">
        <thead class="table-primary">
        <tr>
            <th>م</th>
            <th>رقم الطلب</th>
<!--            <th>الرقم التسلسلي</th>-->
<!--            <th>المقر</th>-->
<!--            <th> الموظف</th>-->
            <th>نوع البدل</th>
            <th>البند</th>
<!--            <th>آلية الاحتساب</th>-->
<!--            <th>القيمة</th>-->
<!--            <th>عدد الاقساط</th>-->
<!--            <th>من تاريخ</th>-->
<!--            <th>الى</th>-->
            <th>خاضع للضريبة</th>
            <th>اسم المدخل</th>
            <th>حالة الطلب</th>
            <th>الإجراء</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($page > 1): ?>
            <tr>
                <td colspan="10" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
        <?php foreach ($page_rows as $row) : ?>

            <tr ondblclick="javascript:show_row_details('<?=$row['SER']?>');">

                <td><?= $count++ ?></td>
                <td><?= $row['SER'] ?></td>
<!--                <td>--><?//= $row['D_SER'] ?><!--</td>-->
<!--                <td>--><?//= $row['BRANCH_NAME'] ?><!--</td>-->
<!--                <td>--><?//= $row['EMP_NO']?><!-- - --><?//=$row['EMP_NAME'] ?><!--</td>-->
                <td><?= $row['BADL_NAME'] ?></td>
                <td><?= $row['BAND_NAME'] ?></td>
<!--                <td>--><?//= $row['CALCULATION_TYPE_NAME'] ?><!--</td>-->
<!--                <td>--><?//= number_format($row['INSTALL_VALUE'],0) ?><!--</td>-->
<!--                <td>--><?//= $row['NUMBER_INST'] ?><!--</td>-->
<!--                <td>--><?//= $row['INST_MONTH'] ?><!--</td>-->
<!--                <td>--><?//= $row['DATE_TO_MONTH'] ?><!--</td>-->
                <td><?= $row['IS_TAXED_NAME'] ?></td>
                <td><?= $row['ENTRY_USER_NAME'] ?></td>
                <td><?= $row['ADOPT_NAME'] ?></td>
                <td class='text-center'>
                    <?php if ($row['ADOPT'] >= 1) { ?>
                        <a onclick="show_detail_row(<?= $row['SER'] ?>);" class="modal-effect"   data-bs-effect="effect-rotate-left" data-bs-toggle="modal" href="#DetailModal" title="تفاصيل"
                           style="color: #2075f8"><i class="glyphicon glyphicon-eye-open"></i> </a>
                    <?php } ?>
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