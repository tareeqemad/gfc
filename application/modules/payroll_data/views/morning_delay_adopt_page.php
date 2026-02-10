<?php
$count = $offset;
$MODULE_NAME= 'payroll_data';
$TB_NAME= 'morning_delay_adopt';
//اعتماد المدير المالي
$ChiefFinancial = base_url("$MODULE_NAME/$TB_NAME/ChiefFinancial");
//اعتماد المراقب الداخلي
$InternalObserver = base_url("$MODULE_NAME/$TB_NAME/InternalObserver");
//اعتماد مدير المقر
$HeadOffice = base_url("$MODULE_NAME/$TB_NAME/HeadOffice");
//اعتماد المدير العام
$GeneralDirector = base_url("$MODULE_NAME/$TB_NAME/GeneralDirector");
//اعتماد المالية للصرف
$FinancialAdopt = base_url("$MODULE_NAME/$TB_NAME/FinancialToPay");
//الغاء لااعتماد
$CancelAdopt = base_url("$MODULE_NAME/$TB_NAME/Return_to_hr");
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
            <table class="table  table-bordered" id="Detail_tb">
                <thead class="table-light">
                <tr>
                    <th class="text-center"><b>عدد السجلات</b></th>
                    <th class="text-center"><b>عدد الساعات</b></th>
                    <th class="text-center"><b>عدد الأيام</b></th>
                    <th class="text-center"><b>اجمالي المبلغ المستقطع</b></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="text-primary text-center h4"><b><?= $v_count_row ?></b></td>
                    <td class="text-primary text-center h4"><b><?= number_format($v_total_hours, 2) ?></b></td>
                    <td class="text-primary text-center h4"><b><?= number_format($v_total_days, 2) ?></b></td>
                    <td class="text-primary text-center h4"><b><?= $v_hour_calculated_sal + $v_day_calculated_sal ?></b></td>
                </tr>
                </tbody>
            </table>
        </div>
    <?php } ?>
    <div class="col-md-4"></div>
</div>
<br>
<div class="table-responsive">
    <table class="table table-bordered" id="page_tb">
        <thead class="table-light">
        <tr>
            <th></th>
            <th>م</th>
            <th>المقر</th>
            <th>الموظف</th>
            <th>شهر التأخير</th>
            <th>عدد أيام التأخير الكلية</th>
            <th>عدد أيام الاعفاء حسب القانون</th>
            <th>عدد أيام الاعفاء بعذر</th>
            <th>عدد أيام الغياب</th>
            <th class="text-center">عدد ايام الخصم المعتمدة</th>
            <th class="text-center">عدد الساعات المخصومة</th>
            <th class="text-center">الراتب الاساسي </th>
            <th class="text-center">قيمة الاستقطاع</th>
            <th>شهر الاحتساب</th>
            <th>الاعتماد الاداري</th>
            <th>الاعتماد المالي</th>
            <th>اجراء</th>
        </tr>
        </thead>
        <tbody>
        <?php if($page > 1): ?>
            <tr>
                <td colspan="17" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
        <?php foreach($page_rows as $row) :?>
            <tr>

                <?php
                if(HaveAccess($ChiefFinancial) and $row['IS_ACTIVE'] == 0) {
                    $check = "<input name='adopt_no[]' type='checkbox' class='checkboxes' checked  disabled=\"disabled\" value='{$row['P_SER']}'  />";
                }elseif (HaveAccess($ChiefFinancial) && HaveAccess($CancelAdopt)  && $row['IS_ACTIVE'] == 1 ) {
                    $check = "<input name='adopt_no[]' type='checkbox' class='checkboxes' checked  disabled=\"disabled\" value='{$row['P_SER']}'  />";
                }elseif (HaveAccess($HeadOffice) and $row['IS_ACTIVE'] == 1 ) {
                    $check = "<input name='adopt_no[]' type='checkbox' class='checkboxes' checked  disabled=\"disabled\" value='{$row['P_SER']}' />";
                }elseif (HaveAccess($InternalObserver) and $row['IS_ACTIVE'] == 3) {
                    $check = "<input name='adopt_no[]' type='checkbox' class='checkboxes' checked  disabled=\"disabled\" value='{$row['P_SER']}' />";
                }elseif (HaveAccess($GeneralDirector) and $row['IS_ACTIVE'] == 4 && $param_branch == 1 ) {
                    $check = "<input name='adopt_no[]' type='checkbox' class='checkboxes' checked disabled=\"disabled\" value='{$row['P_SER']}' />";
                }elseif (HaveAccess($FinancialAdopt) and $row['IS_ACTIVE'] == 10 && $param_branch == 1 ) {
                    $check = "<input name='adopt_no[]' type='checkbox' class='checkboxes' checked disabled=\"disabled\" value='{$row['P_SER']}' />";
                } elseif (HaveAccess($FinancialAdopt) and $row['IS_ACTIVE'] == 4 && $param_branch != 1 ) {
                    $check = "<input name='adopt_no[]' type='checkbox' class='checkboxes' checked disabled=\"disabled\" value='{$row['P_SER']}' />";
                }elseif (HaveAccess($CancelAdopt) and $row['IS_ACTIVE'] > 1  && $row['CHK_STATUS'] == 0) {
                    $check = "<input name='adopt_no[]' type='checkbox' class='checkboxes' checked disabled=\"disabled\" value='{$row['P_SER']}' />";
                }else{
                    $check = '';
                }
                ?>
                <td><?=$check?></td>
                <td><?=$count?></td>
                <td><?=$row['BRANCH_NAME']?></td>
                <td><?=$row['EMP_NO'] ?> - <?=$row['EMP_NAME']?></td>
                <td><?=$row['THE_MONTH']?></td>
                <td><?=$row['COUNT_TOTAL_ALL']?></td>
                <td><?=$row['COUNT_LAW']?></td>
                <td><?=$row['COUNT_EXCUSE']?></td>
                <td><?=$row['VAC']?></td>
                <td class="text-danger text-center"><?=$row['DAY']?></td>
                <td class="text-danger text-center"><?=$row['TOTAL']?></td>
                <td class="text-center text-center"><?=$row['BASIC_SAL']?></td>
                <td class="text-danger text-center"><?=number_format($row['HOUR_CALCULATED_SAL'] + $row['DAY_CALCULATED_SAL'],2)?></td>
                <td class="text-center"><?=$row['MONTH_ACT']?></td>
                <td><?=$row['IS_ACTIVE_NAME']?></td>
                <td>
                    <?php if ( $row['CHK_STATUS'] >= 1){?>
                        معتمد مالي
                    <?php } else if ($row['CHK_STATUS'] == 0) { ?>
                        غير معتمد مالي
                    <?php } else { ?>

                    <?php  } ?>
                </td>
                <td>
                    <a onclick="show_detail_adopt(<?= $row['EMP_NO'] ?>,<?= $row['THE_MONTH'] ?>);" class="modal-effect"   data-bs-effect="effect-rotate-left" data-bs-toggle="modal" href="#DetailModal" title="تفاصيل"
                       style="color: #2075f8"><i class="fa fa-eye"></i> </a>

                </td>
                <?php $count++; ?>
            </tr>
        <?php endforeach;?>
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
