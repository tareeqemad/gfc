<?php
/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 03/03/2020
 * Time: 11:23 ص
 */
$count = $offset;
?>

<div class="row">
    <div class="col-md-3">
        <div class="alert alert-info" role="alert">
            <strong>تنويه</strong> يتم عرض الاجماليات بناء على محددات البحث
        </div>
    </div>
    <?php if ($offset == 1) { ?>
        <div class="col-md-4">
            <table class="table table-bordered" id="sumation_app">
                <thead class="table-light">
                <tr>
                    <th class="text-center">عدد السجلات</th>
                    <th class="text-center">اجمالي مبلغ المخاطرة المعتمد</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="text-primary text-center h4"><b><?= $count_row ?></b></td>
                    <td class="text-primary text-center h4"><b><?= $total_value_ma ?></b></td>
                </tr>
                </tbody>
            </table>
        </div>
    <?php } ?>
    <div class="col-md-4"></div>

</div>
<br>
<div class="row">
    <div class="table-responsive">
        <table class="table table-bordered" id="page_tb">
            <thead class="table-light">
            <tr>
                <th>
                    <input type="checkbox" class="group-checkable" data-set="#page_tb .checkboxes"/>
                </th>
                <th>م</th>
                <th>المقر</th>
                <th>الرقم</th>
                <th>الموظف</th>
                <th>نوع التعين</th>
                <th> طبيعة العمل</th>
                <th>المسمى الوظيفي</th>
                <th>الراتب الأساسي</th>
                <th>المبلغ</th>
                <th>تاريخ القرار</th>
                <th>عن شهر</th>
                <th class="text-center">المخاطرة المقترحة</th>
                <th class="text-center">المخاطرة المعتمدة</th>
                <th>فرق المخاطرة</th>
                <th> الاعتماد الاداري</th>
                <th> الاعتماد المالي</th>
                <th>تفاصيل</th>
            </tr>
            </thead>
            <tbody>
            <?php if ($page > 1): ?>
                <tr>
                    <td colspan="17" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
                </tr>
            <?php endif; ?>
            <?php
            foreach ($page_rows as $row) : ?>
                <tr id="tr_<?= $row['P_SER'] ?>">
                    <?php if ($row['AGREE_MA'] == 0) {
                        $check = "<input name='adopt_no[]' type='checkbox' class='checkboxes'  value='{$row['P_SER']}'  />";
                        ?>
                    <?php } else {
                        $check = '';
                    } ?>
                    <td><?= $check ?></td>
                    <input type="hidden" id="pp_ser" name="pp_ser" value="<?= $row['P_SER'] ?>">
                    <input type="hidden" id="emp_id" name="no" value="<?= $row['NO'] ?>">
                    <input type="hidden" id="emp_name" name="emp_name" value="<?= $row['EMP_NAME'] ?>">
                    <input type="hidden" id="month" name="month" value="<?= $row['MONTH'] ?>">
                    <input type="hidden" id="agree_ma" name="agree_ma" value="<?= $row['AGREE_MA'] ?>">
                    <input type="hidden" id="value_ma" name="value_ma" value="<?= $row['VALUE_MA'] ?>">
                    <input type="hidden" id="value_m" name="value_m" value="<?= $row['VALUE'] ?>">
                    <input type="hidden" id="job_type_ma" name="job_type_ma" value="<?= $row['JOB_TYPE'] ?>">
                    <td><?= $count ?></td>
                    <td><?= $row['BRANCH_NAME'] ?></td>
                    <td><?= $row['NO'] ?> </td>
                    <td><?= $row['EMP_NAME'] ?></td>
                    <td><?= $row['EMP_TYPE_NAME'] ?></td>
                    <td><?= $row['W_NO_NAME'] ?></td>
                    <td><?= $row['JOB_TYPE_NAME'] ?></td>
                    <td><?= number_format($row['B_SALARY'], 2) ?></td>
                    <td><?= number_format($row['JOB_TYPE_RATIO'], 2) ?></td>
                    <td><?= $row['DES_DATE'] ?></td>
                    <td><?= $row['MONTH'] ?></td>
                    <td><?= $row['VALUE'] ?></td>
                    <td><?= $row['VALUE_MA'] ?></td>
                    <td><?= $row['VALUE_MA'] - $row['VALUE'] ?></td>
                    <td><?= $row['AGREE_MA_NAME'] ?></td>
                    <td><?= $row['AGREE_FI_NAME'] ?> </td>
                    <td>

                        <?php if ($row['AGREE_MA'] == 0 && HaveAccess(base_url('payroll_data/bouns_risk/read_return_reason'))) : ?>
                            <a onclick="show_reason_row(<?= $row['P_SER'] ?>);"
                               title="عرض سبب الارجاع"
                               style="color: #f8b020"><i class="si si-layers"></i></a> |
                        <?php endif; ?>

                        <?php if ($row['AGREE_MA'] == 0 && HaveAccess(base_url('payroll_data/bouns_risk/update_data'))) : ?>
                            <a onclick="show_detail_row(this);" data-id="<?= $row['P_SER'] ?>" title="تعديل"
                               style="color: #2075f8"><i class="fa fa-edit"></i> </a>  |
                        <?php endif; ?>

                        <?php if ($row['AGREE_MA'] == 0 && HaveAccess(base_url('payroll_data/bouns_risk/delete_risk_ma'))) : ?>
                            <a href="javascript:;" onclick="javascript:delete_row(this,<?= $row['P_SER'] ?>);"><i
                                        class="fa fa-trash" style="color: red;"></i> </a>
                        <?php endif; ?>
                    </td>
                    <?php
                    $count++; ?>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

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





