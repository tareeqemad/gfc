<?php
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 22/02/23
 * Time: 10:30 ص
 */

$MODULE_NAME = 'salary';
$TB_NAME = 'Contact_allowance_adopt';
$count = $offset;

//اعتماد المدير المالي
$ChiefFinancial = base_url("$MODULE_NAME/$TB_NAME/ChiefFinancial");
//اعتماد مدير المقر
$HeadOffice = base_url("$MODULE_NAME/$TB_NAME/HeadOffice");
//اعتماد المراقب الداخلي
$InternalObserver = base_url("$MODULE_NAME/$TB_NAME/InternalObserver");
//اعتماد المالية للصرف
$FinancialAdopt = base_url("$MODULE_NAME/$TB_NAME/FinancialToPay");
//الغاء الاعتماد
$CancelAdopt = base_url("$MODULE_NAME/$TB_NAME/CancelAdopt");

?>
<div class="row">
    <div class="col-md-4">
        <div class="alert alert-primary" role="alert">
            <strong>تنويه</strong> يتم عرض الاجماليات بناء على محددات البحث
        </div>
    </div>
    <?php if ($offset == 1) { ?>
        <div class="col-md-4 tableRoundedCorner">
            <table class="table roundedTable table-bordered" id="sumation_app">
                <thead class="table-light">
                <tr>
                    <th class="text-center"><b>عدد السجلات</b></th>
                    <th class="text-center text-danger"><b>المتكرر لهم بدل الاتصال</b></th>
                    <th class="text-center"><b>اجمالي مبلغ بدل الاتصال المعتمد</b></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="text-primary text-center h4"><b><?= $count_row ?></b></td>
                    <td class="text-danger text-center h4" onclick="show_all_recurring_records();"><b><?= $distinct_emp_val ?></b></td>
                    <td class="text-primary text-center h4"><b><?= $total_value_ma ?></b></td>
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
    <div class="table-responsive tableRoundedCorner">
        <table class="table table-bordered roundedTable" id="page_tb">
            <thead class="table-light">
            <tr>
                <th>
                    <input type="checkbox" class="group-checkable" checked data-set="#page_tb .checkboxes"/>
                </th>
                <th>م</th>
                <th>الرقم التسلسلي</th>
                <th>المقر</th>
                <th>الرقم الوظيفي</th>
                <th>اسم الموظف</th>
                <th>المسمى الوظيفي</th>
                <th>الشهر</th>
                <th>نوع الرصيد</th>
                <th>الفئة</th>
                <th>مبلغ الفئة</th>
                <th>المبلغ المستحق</th>
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

                <tr id="tr_<?= $row['EMP_NO'] ?>">
                    <?php
                    if ( HaveAccess($ChiefFinancial) and $row['ADOPT'] == 10 ) {
                        $check = "<input name='adopt_no[]' type='checkbox' class='checkboxes' checked   value='{$row['SER']}'  />";
                    }elseif ( HaveAccess($HeadOffice) and $row['ADOPT'] == 20 ) {
                        $check = "<input name='adopt_no[]' type='checkbox' class='checkboxes' checked   value='{$row['SER']}' />";
                    }elseif ( HaveAccess($InternalObserver) and  $row['ADOPT'] == 30) {
                        $check = "<input name='adopt_no[]' type='checkbox' class='checkboxes' checked   value='{$row['SER']}' />";
                    }elseif ( HaveAccess($FinancialAdopt) and $row['ADOPT'] == 40 ) {
                        $check = "<input name='adopt_no[]' type='checkbox' class='checkboxes' checked  value='{$row['SER']}' />";
                    }elseif ( HaveAccess($FinancialAdopt) and $row['ADOPT'] == 50 ) {
                        $check = "<input name='adopt_no[]' type='checkbox' class='checkboxes' checked  value='{$row['SER']}' />";
                    } else {
                        $check = '';
                    }
                    ?>

                    <td><?= $check ?></td>
                    <input type="hidden" id="pp_ser" name="pp_ser" value="<?= $row['SER'] ?>">
                    <input type="hidden" id="emp_id" name="no" value="<?= $row['EMP_NO'] ?>">
                    <input type="hidden" id="emp_name" name="emp_name" value="<?= $row['EMP_NAME'] ?>">
                    <input type="hidden" id="month" name="month" value="<?= $row['THE_MONTH'] ?>">
                    <input type="hidden" id="agree_ma" name="agree_ma" value="<?= $row['ADOPT'] ?>">
                    <input type="hidden" id="category_name" name="category_name" value="<?= $row['CATEGORY'] ?>">
                    <input type="hidden" id="admin_name" name="admin_name" value="<?= $row['JOB_TITLE_L'] ?>">
                    <?php if ( $row['STATUS'] == 1 ) :  ?>
                        <input type="hidden" id="category_amount" name="category_amount" value="<?= $row['CATEGORY_AMOUNT'] ?>">
                    <?php else : ?>
                        <input type="hidden" id="category_amount" name="category_amount" value="<?= $row['REMAINING_BALANCE'] ?>">
                    <?php endif; ?>
                    <input type="hidden" id="deserved_amount" name="deserved_amount" value="<?= $row['DESERVED_AMOUNT'] ?>">
                    <td><?= $count ?></td>
                    <td><?= $row['SER'] ?></td>
                    <td><?= $row['BRANCH_NAME'] ?></td>
                    <td><?= $row['EMP_NO'] ?> </td>
                    <td><?= $row['EMP_NAME'] ?></td>
                    <td><?= $row['JOB_TITLE_L'] ?></td>
                    <td><?= $row['THE_MONTH'] ?></td>
                    <td><?= $row['STATUS_NAME'] ?></td>
                    <td><?= $row['CATEGORY'] ?></td>
                    <?php if ( $row['STATUS'] == 1 ) :  ?>
                        <td><?= $row['CATEGORY_AMOUNT'] ?></td>
                    <?php else : ?>
                        <td><?= $row['BALANCE'] ?></td>
                    <?php endif; ?>
                    <td><?= $row['DESERVED_AMOUNT'] ?></td>
                    <td class="text-center">
                        <?php if ($row['ADOPT'] >= 1) { ?>
                            <a onclick="show_detail_row(<?= $row['SER'] ?>);" class="modal-effect"
                               data-bs-effect="effect-rotate-left" data-bs-toggle="modal" href="#DetailModal"
                               title="تفاصيل"
                               style="color: #2075f8"><i class="si si-layers"></i></a>
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





