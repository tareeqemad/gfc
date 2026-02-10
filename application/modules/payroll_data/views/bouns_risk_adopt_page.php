<?php
/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 09/02/2020
 * Time: 11:27 ص
 */
$MODULE_NAME = 'payroll_data';
$TB_NAME = 'bouns_risk_adopt';
$count = $offset;
//اعتماد المدير المالي
$ChiefFinancial = base_url("$MODULE_NAME/$TB_NAME/ChiefFinancial");
//اعتماد مدير المقر
$HeadOffice = base_url("$MODULE_NAME/$TB_NAME/HeadOffice");
//اعتماد المراقب الداخلي
$InternalObserver = base_url("$MODULE_NAME/$TB_NAME/InternalObserver");
//33المدير العام في المقر الرئيسي
$GeneralDirector = base_url("$MODULE_NAME/$TB_NAME/GeneralDirector");
//اعتماد المالية للصرف
$FinancialAdopt = base_url("$MODULE_NAME/$TB_NAME/FinancialToPay");
?>
<div class="row">
    <div class="col-md-4">
        <div class="alert alert-info" role="alert">
            <strong>تنويه</strong> يتم عرض الاجماليات بناء على محددات البحث
        </div>
    </div>
    <?php if ($offset == 1) { ?>
        <div class="col-md-4">
            <table class="table table-bordered" id="sumation_app">
                <thead class="table-light">
                <tr>
                    <th class="text-center"><b>عدد السجلات</b></th>
                    <th class="text-center text-danger"><b>المتكرر لهم علاوة مخاطرة</b></th>
                    <th class="text-center"><b>اجمالي مبلغ المخاطرة المعتمد</b></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="text-primary text-center"><?= $count_row ?></td>
                    <td class="text-danger text-center" onclick="show_all_recurring_records();"><?= $distinct_emp_val ?></td>
                    <td class="text-primary text-center"><?= $total_value_ma ?></td>
                </tr>
                </tbody>
            </table>
            <input type="hidden" value="<?=$where_sql_?>" id="txt_where_sql">
        </div>
    <?php } ?>
    <div class="col-md-4"></div>
    <hr>
</div>
<div class="row">
    <div class="table-responsive">
        <table class="table table-bordered" id="page_tb">
            <thead class="table-light">
            <tr>
                <th></th>
                <th>م</th>
                <th>المقر</th>
                <th>الموظف</th>
                <th>نوع التعين</th>
                <th> المسمى المهني</th>
                <th>المسمى الوظيفي</th>
                <th>الراتب الأساسي</th>
                <th>النسبة / المبلغ</th>
                <th>عن شهر</th>
                <th>المخاطرة المقترحة</th>
                <th>المخاطرة المعتمدة</th>
                <th>فرق المخاطرة</th>
                <th>الاعتماد الاداري</th>
                <th>اعتماد المالية</th>
                <th>
                    الاجراء
                </th>
            </tr>
            </thead>
            <tbody>
            <?php if ($page > 1): ?>
                <tr>
                    <td colspan="16" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
                </tr>
            <?php endif; ?>
            <?php
            foreach ($page_rows as $row) :?>
                <tr id="tr_<?= $row['NO'] ?>">
                    <?php
                    if (HaveAccess($ChiefFinancial) and $row['AGREE_MA'] == 1 && $row['AGREE_FI'] == 0) {
                        $check = "<input name='adopt_no[]' type='checkbox' class='checkboxes' checked  disabled=\"disabled\" value='{$row['P_SER']}'  />";
                    }elseif (HaveAccess($HeadOffice) and $row['AGREE_MA'] == 10 && $row['AGREE_FI'] == 0) {
                        $check = "<input name='adopt_no[]' type='checkbox' class='checkboxes' checked  disabled=\"disabled\" value='{$row['P_SER']}' />";
                    }elseif (HaveAccess($InternalObserver) and $row['AGREE_MA'] == 30 && $row['AGREE_FI'] == 0) {
                        $check = "<input name='adopt_no[]' type='checkbox' class='checkboxes' checked  disabled=\"disabled\" value='{$row['P_SER']}' />";
                    }elseif (HaveAccess($GeneralDirector) and $row['AGREE_MA'] == 31 && $row['AGREE_FI'] == 0 && $param_branch == 1) {
                        $check = "<input name='adopt_no[]' type='checkbox' class='checkboxes' checked  disabled=\"disabled\" value='{$row['P_SER']}' />";
                    }elseif (HaveAccess($FinancialAdopt) and $row['AGREE_MA'] == 33 && $row['AGREE_FI'] == 0 && $param_branch == 1) {
                        $check = "<input name='adopt_no[]' type='checkbox' class='checkboxes' checked disabled=\"disabled\" value='{$row['P_SER']}' />";
                    } elseif (HaveAccess($FinancialAdopt) and $row['AGREE_MA'] == 31 && $row['AGREE_FI'] == 0) {
                        $check = "<input name='adopt_no[]' type='checkbox' class='checkboxes' checked disabled=\"disabled\" value='{$row['P_SER']}' />";
                    } elseif (HaveAccess($FinancialAdopt) and $row['AGREE_MA'] == 35 && $row['AGREE_FI'] == 1) {
                        $check = "<input name='adopt_no[]' type='checkbox' class='checkboxes' checked disabled=\"disabled\" value='{$row['P_SER']}' />";
                    }elseif (HaveAccess($CancelAdopt) and $row['AGREE_MA'] >= 10 && $row['AGREE_FI'] == 0) {
                        $check = "<input name='adopt_no[]' type='checkbox' class='checkboxes' checked  disabled=\"disabled\" value='{$row['P_SER']}' />";
                    }elseif ($row['AGREE_MA'] == 1 && $row['AGREE_FI'] >= 1) {
                        $check = 'معتمد مالياً وادارياً';
                    } else {
                        $check = '';
                    }
                    ?>
                    <td><?= $check ?></td>
                    <input type="hidden" id="pp_ser" name="pp_ser" value="<?= $row['P_SER'] ?>">
                    <input type="hidden" id="emp_id" name="no" value="<?= $row['NO'] ?>">
                    <input type="hidden" id="emp_name" name="emp_name" value="<?= $row['EMP_NAME'] ?>">
                    <input type="hidden" id="month" name="month" value="<?= $row['MONTH'] ?>">
                    <input type="hidden" id="agree_ma" name="agree_ma" value="<?= $row['AGREE_MA'] ?>">
                    <input type="hidden" id="value_ma" name="value_ma" value="<?= $row['VALUE_MA'] ?>">
                    <td><?= $count ?></td>
                    <td><?= $row['BRANCH_NAME'] ?></td>
                    <td><?= $row['NO'] ?> - <?= $row['EMP_NAME'] ?></td>
                    <td><?= $row['EMP_TYPE_NAME'] ?></td>
                    <td><?= $row['JOB_TYPE_NAME'] ?></td>
                    <td><?= $row['W_NO_ADMIN_NAME'] ?></td>
                    <td><?= number_format($row['B_SALARY'], 2) ?></td>
                    <td><?= number_format($row['JOB_TYPE_RATIO'], 2) ?></td>
                    <td><?= $row['MONTH'] ?></td>
                    <td><?= $row['VALUE'] ?></td>
                    <td><?= $row['VALUE_MA'] ?></td>
                    <td><?= $row['VALUE_MA'] - $row['VALUE'] ?></td>
                    <td><?= $row['AGREE_MA_NAME'] ?></td>
                    <td><?= $row['AGREE_FI_NAME'] ?> </td>
                    <td class="text-center">
                        <?php if ($row['AGREE_MA'] >= 1) { ?>
                            <a onclick="show_detail_row(<?= $row['P_SER'] ?>);"
                               title="تفاصيل"
                               style="color: #2075f8"><i class="fa fa-eye"></i> </a>
                        <?php } ?>
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




