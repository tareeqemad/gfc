<?php
$count = $offset;
$MODULE_NAME = 'payroll_data';
$TB_NAME = 'no_reson_name_adopt';
//اعتماد المدير المالي
$ChiefFinancial = base_url("$MODULE_NAME/$TB_NAME/ChiefFinancial"); //11
//اعتماد مدير المقر
$HeadOffice = base_url("$MODULE_NAME/$TB_NAME/HeadOffice"); //13
//اعتماد المراقب الداخلي
$InternalObserver = base_url("$MODULE_NAME/$TB_NAME/InternalObserver"); //14
$GeneralDirector = base_url("$MODULE_NAME/$TB_NAME/GeneralDirector"); //اعتماد المدير العام15
//اعتماد المالية للصرف
$FinancialToPay = base_url("$MODULE_NAME/$TB_NAME/FinancialToPay"); //20
//الغاء لااعتماد
$CancelAdopt = base_url("$MODULE_NAME/$TB_NAME/ret_to_hr");
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
                    <th class="text-center"><b>اجمالي المبلغ المستقطع</b></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="text-primary text-center h4"><b><?= $v_count_row ?></b></td>
                    <td class="text-primary text-center h4"><b><?= $v_total_deduction ?></b></td>
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
            <th class="text-center">شهر عدم الالتزام</th>
            <th class="text-center">عدد ايام التوقيع</th>
            <th class="text-center">عدد مرات الاعفاء</th>
            <th class="text-center">عدد مرات الخصم</th>
            <th class="text-center">عدد ايام الخصم</th>
            <th class="text-center">قيمة الاستقطاع</th>
            <th class="text-center">شهر الراتب</th>
            <th>حالة الاعتماد</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php if ($page > 1): ?>
            <tr>
                <td colspan="12" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
        <?php
        foreach ($page_rows as $row) :?>

            <tr id="tr_<?= $row['THE_MONTH'] ?>">

            <tr id="tr_<?= $row['P_SER'] ?>">
                <?php
                if (HaveAccess($ChiefFinancial) and $row['POST'] == 10 ) {
                    $check = "<input name='adopt_no[]' type='checkbox' class='checkboxes' checked  disabled=\"disabled\" value='{$row['P_SER']}'  />";
                }elseif (HaveAccess($HeadOffice) and $row['POST'] == 11 ) {
                    $check = "<input name='adopt_no[]' type='checkbox' class='checkboxes' checked  disabled=\"disabled\" value='{$row['P_SER']}' />";
                } elseif (HaveAccess($InternalObserver) and $row['POST'] == 13 ) {
                    $check = "<input name='adopt_no[]' type='checkbox' class='checkboxes' checked  disabled=\"disabled\" value='{$row['P_SER']}' />";
                }elseif (HaveAccess($GeneralDirector) and $row['POST'] == 14 && $param_branch == 1) {
                    $check = "<input name='adopt_no[]' type='checkbox' class='checkboxes' checked disabled=\"disabled\" value='{$row['P_SER']}' />";
                }elseif (HaveAccess($FinancialToPay) and $row['POST'] == 15 && $param_branch == 1) {
                    $check = "<input name='adopt_no[]' type='checkbox' class='checkboxes' checked disabled=\"disabled\" value='{$row['P_SER']}' />";
                } elseif (HaveAccess($FinancialToPay) and $row['POST'] == 14 && $param_branch != 1) {
                    $check = "<input name='adopt_no[]' type='checkbox' class='checkboxes' checked disabled=\"disabled\" value='{$row['P_SER']}' />";
                }elseif (HaveAccess($CancelAdopt) and $row['POST'] == 20 ) {
                    $check = "<input name='adopt_no[]' type='checkbox' class='checkboxes' checked disabled=\"disabled\" value='{$row['P_SER']}' />";
                }elseif (HaveAccess($CancelAdopt) and $row['POST'] == 10 ) {
                    $check = "<input name='adopt_no[]' type='checkbox' class='checkboxes' checked disabled=\"disabled\" value='{$row['P_SER']}' />";
                }else{
                    $check = '';
                }
                ?>
                <td><?= $check ?></td>
                <td><?= $count ?></td>
                <td><?= $row['BRANCH_NAME'] ?></td>
                <td><?= $row['NO']?> - <?= $row['EMP_NAME'] ?></td>
                <td><?= $row['THE_MONTH'] ?></td>
                <td class="text-center"><?= $row['COUNT_NO'] ?></td>
                <td class="text-center"><?= $row['COUNT_PERMISSION'] ?></td>
                <td class="text-center"><?= $row['COUNT_MINUS'] ?></td>
                <td class="text-center" ><?= $row['COUNT_DAY'] ?></td>
                <td class="text-danger text-center"><?= $row['TOTAL_DEDUCTION'] ?></td>
                <td class="text-center"><?= $row['MONTH_SAL'] ?></td>
                <td class="text-center"><?= $row['ADOPT_STATUS_NAME'] ?></td>
                <td class="text-center">
                    <a onclick="show_detail_adopt(<?= $row['P_SER'] ?>);" class="modal-effect"   data-bs-effect="effect-rotate-left" data-bs-toggle="modal" href="#DetailModal" title="تفاصيل"
                       style="color: #2075f8"><i class="fa fa-eye"></i> </a>
                </td>
                <?php
                $count++; ?>
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
