<?php
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 22/02/23
 * Time: 10:30 ص
 */
$count = $offset;
$MODULE_NAME = 'salary';
$TB_NAME = 'Contact_allowance';
$update_url = base_url("$MODULE_NAME/$TB_NAME/update_data");
$delete_url = base_url("$MODULE_NAME/$TB_NAME/delete_data");
$reason_detail_url = base_url("$MODULE_NAME/$TB_NAME/public_get_reason_detail");
?>

<div class="row">
    <div class="col-md-3">
        <div class="alert alert-primary" role="alert">
            <strong>تنويه</strong> يتم عرض الاجماليات بناء على محددات البحث
        </div>
    </div>
    <?php if ($offset == 1) { ?>
        <div class="col-md-4 tableRoundedCorner">
            <table class="table table-bordered roundedTable" id="sumation_app">
                <thead class="table-light">
                <tr>
                    <th class="text-center"><b>عدد السجلات</b></th>
                    <th class="text-center"><b>اجمالي مبلغ بدل الاتصال</b></th>
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
    <div class="table-responsive tableRoundedCorner">
        <table class="table table-bordered roundedTable" id="page_tb">
            <thead class="table-light">
            <tr>
                <th>
                    <input type="checkbox" class="group-checkable" data-set="#page_tb .checkboxes"/>
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
                <th>الفاتورة الشهرية</th>
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
                <tr id="tr_<?= $row['SER'] ?>">
                    <?php if ($row['ADOPT'] == 1) {
                        $check = "<input name='adopt_no[]' type='checkbox' class='checkboxes'  value='{$row['SER']}'  />";
                        ?>
                    <?php } else {
                        $check = '';
                    } ?>
                    <td><?= $check ?></td>
                    <input type="hidden" id="pp_ser" name="pp_ser" value="<?= $row['SER'] ?>">
                    <input type="hidden" id="emp_id" name="no" value="<?= $row['EMP_NO'] ?>">
                    <input type="hidden" id="emp_name" name="emp_name" value="<?= $row['EMP_NAME'] ?>">
                    <input type="hidden" id="month" name="month" value="<?= $row['THE_MONTH'] ?>">
                    <input type="hidden" id="agree_ma" name="agree_ma" value="<?= $row['ADOPT'] ?>">
                    <input type="hidden" id="category_name" name="category_name" value="<?= $row['CATEGORY'] ?>">
                    <input type="hidden" id="billing_value_ma" name="billing_value_ma" value="<?= $row['BILL_AMOUNT'] ?>">
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
                    <td><?= $row['BILL_AMOUNT'] ?></td>
                    <td><?= $row['DESERVED_AMOUNT'] ?></td>
                    <td>

                        <?php if ($row['ADOPT'] >= 1 && HaveAccess($reason_detail_url)) : ?>
                            <a onclick="show_reason_row(<?= $row['SER'] ?>);" class="modal-effect"
                               data-bs-effect="effect-rotate-left" data-bs-toggle="modal" href="#DetailModal"
                               title="سلسلة الاعتمادات"
                               style="color: #f8b020"><i class="si si-layers"></i></a>
                        <?php endif; ?>

                        <?php if ($row['ADOPT'] == 1 && HaveAccess($update_url)) : ?>
                            | <a onclick="show_detail_row(this);" data-id="<?= $row['SER'] ?>" title="تعديل"
                               style="color: #2075f8"><i class="fa fa-edit"></i> </a>  |
                        <?php endif; ?>

                        <?php if ($row['ADOPT'] == 1 && HaveAccess($delete_url)) : ?>
                            <a href="javascript:;" onclick="javascript:delete_row(this,<?= $row['SER'] ?>);" title="حذف"><i
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
