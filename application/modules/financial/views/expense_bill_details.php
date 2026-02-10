<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 27/12/14
 * Time: 01:42 م
 */

$count = 0;
$delete_details_url = base_url('financial/expense_bill/delete_details');
?>

<div class="tbl_container">
<table class="table" id="expense_detailsTbl" data-container="container">
<thead>
<tr>
    <th>نوع الحساب</th>
    <th> رقم الحساب</th>
    <th style="width: 150px;">نوع حساب المستفيد</th>
    <th style="width: 250px">المستفيد من الخدمة</th>
    <th>البيان</th>
    <th style="width: 100px"> الكمية</th>
    <th style="width: 100px" class="price"> السعر</th>
    <th style="width: 80px"> االضريبة</th>
    <th class="price v_balance">الإجمالي</th>
    <th></th>
</tr>
</thead>

<tbody>

<?php if (count($details) <= 0) : ?>
    <tr>
        <td>
            <select name="d_account_type[]" id="dp_account_type_<?= $count ?>" class="form-control">
                <?php foreach ($account_type as $row) : ?>
                    <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                <?php endforeach; ?>
            </select>
        </td>
        <td>
            <input type="hidden" name="SER[]" value="0">
            <input type="text" name="d_account_id[]" id="h_account_<?= $count ?>" class="form-control col-sm-3">
            <input name="account_id_name[]" data-val="true" readonly data-val-required="حقل مطلوب"
                   class="form-control col-sm-9" readonly id="account_<?= $count ?>">
        </td>

        <td data-root="true">
            <select class="form-control"
                    style="display: none;"
                    id="db_customer_account_type_<?= $count ?>"
                    data-val="true" data-val-required="حقل مطلوب"
                    name="customer_account_type[]">
                <option></option>
                <?php foreach ($ACCOUNT_TYPES as $_row) : ?>
                    <option value="<?= $_row['ACCCOUNT_TYPE'] ?>"
                            style="display: none;"><?= $_row['ACCCOUNT_NO_NAME'] ?></option>
                <?php endforeach; ?>
            </select>
        </td>
        <td>

            <select name="customer_type[]" class="form-control col-sm-3" id="dp_customer_type_<?= $count ?>">
                <option></option>
                <option value="2">سيارة</option>
                <option value="1">مستفيد</option>
            </select>
            <input type="hidden" name="customer_id[]" id="h_customer_id_<?= $count ?>" class="form-control col-sm-6">

            <input name="customer_id_name[]" class="form-control col-sm-9" readonly id="customer_id_<?= $count ?>">

        </td>
        <td>
            <input name="service_hints[]" class="form-control" id="service_hints_<?= $count ?>">


        </td>

        <td>
            <input name="amount[]" data-val="true" data-val-required="حقل مطلوب" class="form-control"
                   id="amount_<?= $count ?>">
        </td>
        <td class="price">
            <input name="unit_price[]" data-val="true" data-val-required="حقل مطلوب" class="form-control"
                   id="unit_price_<?= $count ?>">


        </td>
        <td>
            <div class="">
                <select name="vat_done[]" id="dp_vat_done_<?= $count ?>" data-curr="false" class="form-control">

                    <option value="1"> يخضع</option>
                    <option value="2">لا يخضع</option>

                </select>
            </div>

        </td>
        <td class="price v_balance"></td>

        <td></td>
    </tr>

<?php else: $count = -1; ?>
<?php endif; ?>

<?php foreach ($details as $row) : ?>
    <?php $count++; ?>

    <tr>
        <td>
            <select name="d_account_type[]" id="dp_account_type_<?= $count ?>" class="form-control">
                <?php foreach ($account_type as $drow) : ?>
                    <option <?= $drow['CON_NO'] == $row['ACCOUNT_TYPE'] ? 'selected' : '' ?>
                        value="<?= $drow['CON_NO'] ?>"><?= $drow['CON_NAME'] ?></option>
                <?php endforeach; ?>
            </select>
        </td>
        <td>
            <input type="hidden" name="SER[]" value="<?= !$is_copy  ? $row['SER'] : 0?>">
            <input type="text" name="d_account_id[]" value="<?= $row['ACCOUNT_ID'] ?>" id="h_account_<?= $count ?>"
                   class="form-control col-sm-3">

            <input name="account_id_name[]" data-val="true" value="<?= $row['ACCOUNT_ID_NAME'] ?>" readonly
                   data-val-required="حقل مطلوب" class="form-control col-sm-9" readonly id="account_<?= $count ?>">

        </td>

        <td data-root="true">
            <select class="form-control" style="display: <?= $row['ACCOUNT_TYPE'] == 2 ? 'block' : 'none' ?> ;"
                    id="db_customer_account_type_<?= $count ?>" name="customer_account_type[]">
                <?php foreach ($ACCOUNT_TYPES as $_row) : ?>

                    <option  <?= $row['CUSTOMER_ACCOUNT_TYPE'] == $_row['ACCCOUNT_TYPE'] ? 'selected' : '' ?>
                        value="<?= $_row['ACCCOUNT_TYPE'] ?>"><?= $_row['ACCCOUNT_NO_NAME'] ?></option>

                <?php endforeach; ?>
            </select>
        </td>

        <td>

            <select name="customer_type[]" class="form-control col-sm-3" id="dp_customer_type_<?= $count ?>">
                <option></option>
                <option <?= $row['CUSTOMER_TYPE'] == 2 ? 'selected' : '' ?> value="2">سيارة</option>
                <option <?= $row['CUSTOMER_TYPE'] == 1 ? 'selected' : '' ?> value="1">مستفيد</option>
            </select>

            <input type="hidden" name="customer_id[]" value="<?= $row['CUSTOMER_ID'] ?>"
                   id="h_customer_id_<?= $count ?>" class="form-control col-sm-6">

            <input name="customer_id_name[]" value="<?= $row['CUSTOMER_ID_NAME'] ?>" readonly
                   class="form-control col-sm-9" readonly id="customer_id_<?= $count ?>">

        </td>
        <td>
            <input name="service_hints[]" value="<?= $row['SERVICE_HINTS'] ?>" class="form-control"
                   id="service_hints_<?= $count ?>">


        </td>

        <td>
            <input name="amount[]" value="<?= $row['AMOUNT'] ?>" data-val="true" data-val-required="حقل مطلوب"
                   class="form-control" id="amount_<?= $count ?>">
        </td>
        <td class="price">
            <input name="unit_price[]" value="<?= $row['UNIT_PRICE'] ?>" data-val="true" data-val-required="حقل مطلوب"
                   class="form-control" id="unit_price_<?= $count ?>">


        </td>
        <td>
            <div class="">
                <select name="vat_done[]" id="dp_vat_done_<?= $count ?>" data-curr="false" class="form-control">

                    <option    <?= $row['VAT_DONE'] == 1 ? 'selected' : '' ?>   value="1"> يخضع</option>
                    <option    <?= $row['VAT_DONE'] == 2 ? 'selected' : '' ?>  value="2">لا يخضع</option>

                </select>
            </div>

        </td>
        <td class="price v_balance"><?= $row['AMOUNT'] * $row['UNIT_PRICE'] ?></td>

        <td>
            <?php if ((isset($can_edit) ? $can_edit : false) ) : ?>
                <a href="javascript:;"
                   onclick="javascript:delete_details(this,<?= !$is_copy ? $row['SER'] : 0 ?>,<?= !$is_copy ? $row['EXPENSE_BILL_ID'] : 0 ?>);"><i
                        class="icon icon-trash delete-action"></i> </a>
            <?php endif; ?>
        </td>
    </tr>

<?php endforeach; ?>

</tbody>
<tfoot>
<tr>
    <th rowspan="1" class="align-right" colspan="4">
        <?php if (count($details) <= 0 || (isset($can_edit) ? $can_edit : false)) : ?>
            <a  onclick="javascript:addRow();" onfocus="javascript:addRow();"  href="javascript:;"><i
                    class="glyphicon glyphicon-plus"></i>جديد</a>
        <?php endif; ?>
    </th>

    <th colspan="2">المجموع</th>
    <th id="inv_total"></th>
    <th></th>

</tr>


<tr>
    <th rowspan="4" class="align-right" colspan="4">

    </th>

    <th id="inv_discount_per" colspan="2">نسبة الخصم</th>
    <th id="inv_discount"></th>
    <th></th>

</tr>
<tr>


    <th colspan="2">المبلغ بعد الخصم</th>
    <th id="inv_after_discount"></th>
    <th></th>

</tr>
<tr>


    <th id="inv_tax_per" colspan="2"> نسبة الضريبة</th>
    <th id="inv_tax"></th>
    <th></th>

</tr>

<tr>


    <th colspan="2">مبلغ الفاتورة</th>
    <th id="inv_nettotal"></th>
    <th></th>

</tr>

</tfoot>
</table>
</div>

<script>
    if (typeof initFunctions == 'function') {
        initFunctions();
    }


</script>