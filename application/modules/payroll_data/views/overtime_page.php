<?php
/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 09/02/2020
 * Time: 12:42 م
 */
function tr_color($actual_hours)
{
    if ($actual_hours > 45) {
        return '#fff7f4';
    }
}
$MODULE_NAME = 'payroll_data';
$TB_NAME = 'overtime';
$count = $offset;
//تعديل قيمة الساعات المتجاوزة
$update_calculated_hours_url = base_url("$MODULE_NAME/$TB_NAME/update_calculated_hours");
/***********حذف الساعات الاضافية******/
$delete_hours_url = base_url("$MODULE_NAME/$TB_NAME/delete_hours");
//ارجاع الى مرحلة الاعداد
$CancelAdopt = base_url("$MODULE_NAME/$TB_NAME/return_adopt1");
//اعتماد الشؤون الادارية
$hr_time = base_url("$MODULE_NAME/$TB_NAME/hr_time");
//اعتماد المدير المالي
$ChiefFinancial = base_url("$MODULE_NAME/$TB_NAME/ChiefFinancial_time");
//اعتماد المراقب الداخلي
$InternalObserver = base_url("$MODULE_NAME/$TB_NAME/InternalObserver_time");
//اعتماد مدير المقر
$HeadOffice = base_url("$MODULE_NAME/$TB_NAME/HeadOffice_time");
//اعتماد مدير العام
$GeneralDirector = base_url("$MODULE_NAME/$TB_NAME/GeneralDirector_time");
//اعتماد المالية للصرف
$FinancialAdopt = base_url("$MODULE_NAME/$TB_NAME/FinancialToPay");
//تكاليف العمل
$MODULE_NAME1 = 'hr_attendance';
$TB_NAME1 = 'assigning_work';
$assignwork = base_url("$MODULE_NAME1/$TB_NAME1/");
?>
<hr>
<div class="row">
    <div class="col-md-4">
        <div class="alert alert-info" role="alert">
            <strong>تنويه</strong> يتم عرض الاجماليات بناء على محددات البحث
        </div>
    </div>
    <?php if ($offset == 1) { ?>
        <div class="col-md-6 table-responsive">
            <table class="table table-bordered" id="Detail_tb">
                <thead class="table-light">
                <tr>
                    <th class="text-center"><b>عدد السجلات</b></th>
                    <th class="text-center text-danger"><b>المتكرر لهم اضافي</b></th>
                    <th class="text-center"><b>اجمالي المبلغ من المقر</b></th>
                    <th class="text-center"><b>عدد ساعات المقر</b></th>
                    <th class="text-center"><b>اجمالي المبلغ الفعلي المعتمد</b></th>
                    <th class="text-center"><b>عدد الساعات المعتمدة</b></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="text-primary text-center h4"><b><?= $count_row ?></b></td>
                    <td class="text-danger text-center h4" onclick="show_all_recurring_records();"><b><?= $distinct_emp_val ?></b></td>
                    <td class="text-primary text-center h4"><b><?= number_format($total_val_branch, 2) ?></b></td>
                    <td class="text-primary text-center h4"><b><?= $total_actual_hours_branch ?></b></td>
                    <td class="text-primary text-center h4"><b><?= number_format($total_val_adopt, 2) ?></b></td>
                    <td class="text-primary text-center h4"><b><?= $total_calculated_hours_adopt ?></b></td>
                </tr>
                </tbody>
            </table>
            <input type="hidden" value="<?=$where_sql_?>" id="txt_where_sql">
        </div>
    <?php } ?>
    <div class="col-md-4"></div>
</div>
<br>
<div class="row">
    <div class="table-responsive">
        <table class="table  table-bordered" id="page_tb">
            <thead class="table-light">
            <tr>
                <th colspan="11"><h4 class="text-primary text-center text-bold">بيانات الموظف</h4></th>
                <th colspan="3"><h4 class="text-primary text-center text-bold">من التكاليف</h4></th>
                <th colspan="3"><h4 class="text-success text-center text-bold">المعتمد في الراتب</h4></th>
                <th colspan="4"><h4 class="text-primary text-center text-bold">ملاحظات</h4></th>
            </tr>
            <tr class="table-light">
                <th></th>
                <th>م</th>
                <th>المقر</th>
                <th>الموظف</th>
                <th>نوع التعين</th>
                <th>الادارة</th>
                <th> طبيعة العمل</th>
                <th>المسمى الوظيفي</th>
                <th class="text-center">الراتب الأساسي</th>
                <th class="text-center">عن شهر</th>
                <th class="text-center">المبلغ المسموح</th>
                <th class="text-center">ساعات المقر</th>
                <th class="text-center">الأيام المقر</th>
                <th class="text-center">المبلغ من المقر</th>
                <th class="text-center">عدد الساعات</th>
                <th class="text-center">عدد الأيام</th>
                <th class="text-center">المبلغ الفعلي</th>
                <th>ملاحظات</th>
                <th>اعتماد</th>
                <th> المالية</th>
                <th> اجراءات</th>
            </tr>
            </thead>
            <tbody>
            <?php if ($page > 1): ?>
                <tr>
                    <td colspan="21" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
                </tr>
            <?php endif; ?>
            <?php foreach ($page_rows as $row) : ?>
                <tr id="tr_<?= $row['EMP_NO'] ?>" style="background-color: <?= tr_color($row['ACTUAL_HOURS']) ?>" ondblclick="show_detail_row(<?= $row['P_SER'] ?>);">
                    <?php
                    if (HaveAccess($hr_time) && $row['AGREE_MA'] == 0 && $row['IS_ACTIVE'] == 0) {
                        $check = "<input name='adopt_no[]' type='checkbox' class='checkboxes' checked  disabled=\"disabled\" value='{$row['P_SER']}'  />";
                    }elseif (HaveAccess($ChiefFinancial) && $row['AGREE_MA'] == 1 && $row['IS_ACTIVE'] == 0) {
                        $check = "<input name='adopt_no[]' type='checkbox' class='checkboxes' checked  disabled=\"disabled\" value='{$row['P_SER']}'  />";
                    }elseif (HaveAccess($HeadOffice) && $row['AGREE_MA'] == 10 && $row['IS_ACTIVE'] == 0) {
                        $check = "<input name='adopt_no[]' type='checkbox' class='checkboxes' checked  disabled=\"disabled\" value='{$row['P_SER']}' />";
                    }elseif (HaveAccess($InternalObserver) && $row['AGREE_MA'] == 30 && $row['IS_ACTIVE'] == 0) {
                        $check = "<input name='adopt_no[]' type='checkbox' class='checkboxes' checked  disabled=\"disabled\" value='{$row['P_SER']}' />";
                    }elseif (HaveAccess($GeneralDirector) and $row['AGREE_MA'] == 31 && $row['IS_ACTIVE'] == 0 && $param_branch == 1) {
                        $check = "<input name='adopt_no[]' type='checkbox' class='checkboxes' checked  disabled=\"disabled\" value='{$row['P_SER']}' />";
                    }elseif (HaveAccess($FinancialAdopt) && $row['AGREE_MA'] == 33 && $row['IS_ACTIVE'] == 0 && $param_branch == 1) {
                        $check = "<input name='adopt_no[]' type='checkbox' class='checkboxes' checked disabled=\"disabled\" value='{$row['P_SER']}' />";
                    }elseif (HaveAccess($FinancialAdopt) && $row['AGREE_MA'] == 31 && $row['IS_ACTIVE'] == 0 && $param_branch != 1) {
                        $check = "<input name='adopt_no[]' type='checkbox' class='checkboxes' checked disabled=\"disabled\" value='{$row['P_SER']}' />";
                    }elseif (HaveAccess($CancelAdopt)  && $row['IS_ACTIVE'] == 0 && $row['AGREE_MA'] >= 1) {
                        $check = "<input name='adopt_no[]' type='checkbox' class='checkboxes' checked  disabled=\"disabled\" value='{$row['P_SER']}' />";
                    } else {
                        $check = "";
                    }
                    ?>
                    <td><?= $check ?></td>
                    <td><?= $count ?></td>
                    <td><?= $row['BRANCH_NAME'] ?></td>
                    <td><?= $row['EMP_NO'] ?> - <?= $row['EMP_NAME'] ?></td>
                    <td><?= $row['EMP_TYPE_NAME'] ?></td>
                    <td><?= $row['HEAD_DEPARTMENT_NAME'] ?></td>
                    <td><?= $row['W_NO_NAME'] ?></td>
                    <td><?= $row['W_NO_ADMIN_NAME'] ?></td>
                    <td><?= number_format($row['B_SALARY'], 2) ?></td>
                    <td class="text-center"><?= $row['MONTH'] ?></td>
                    <td class="text-center"><?= $row['BASIC_SAL_Q'] ?></td>
                    <td class="text-center"><?= $row['ACTUAL_HOURS'] ?></td>
                    <td class="text-center"><?= $row['ACTUAL_DAY'] ?></td>
                    <td class="text-center"><?= $row['VAL_BRANCH'] ?></td>
                    <td class="text-center"><?= $row['CALCULATED_HOURS'] ?></td>
                    <td class="text-center"> <?= $row['DAY'] ?></td>
                    <td class="text-center"><?= $row['VAL_ADOPT_BRANCH'] ?></td>
                    <td><?= $row['NOTES'] ?></td>
                    <td><?= $row['AGREE_MA_NAME'] ?></td>
                    <td><?= $row['IS_ACTIVE_NAME'] ?></td>
                    <td>
                        <?php if (HaveAccess($delete_hours_url) && $row['AGREE_MA'] == 0 && $row['IS_ACTIVE'] == 0 ) { ?>
                            <a href="javascript:;" onclick="javascript:delete_row(this,<?= $row['P_SER'] ?>);"><i
                                        class="fa fa-trash" style="color: red;"></i> </a>
                        <?php } ?>
                        <?php if (HaveAccess($update_calculated_hours_url) && $row['AGREE_MA'] == 0 && $row['IS_ACTIVE'] == 0/*&& $row['CALCULATED_HOURS'] > 45*/) { ?>
                            <a onclick="edit_detail_row(<?= $row['P_SER'] ?>);"
                               title="تعديل قيمة الوقت المسموح به"
                               style="color: #6bd7aa"><i class="fa fa-paint-brush"></i> </a> |
                        <?php } ?>
                        <a target="_blank"
                           href="<?= base_url("$MODULE_NAME1/$TB_NAME1/index/1/move_admin/-1/{$row['EMP_NO']}/-1/-1/-1/-1/-1/40") ?>"
                           title="تكاليف العمل" style="color: #5126ef">
                            <i class="fa fa-hourglass-half"></i>
                        </a> |
                        <?= modules::run("$MODULE_NAME/$TB_NAME/indexInlineno", $row['P_SER'], 'overtime_' . $row['P_SER']); ?>
                    </td>
                    <?php $count++; ?>
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



