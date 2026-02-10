<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 02/11/14
 * Time: 09:54 ص
 */

$count = 0;
$delete_details_url = base_url('financial/financial_chains/delete?type=4');
?>

<div class="tbl_container responsive ">
    <table class="table responsive" id="chains_detailsTbl" data-container="container">
        <thead>
        <tr>
            <th style="width:145px;"> نوع الحساب</th>
            <th style="width:400px;">رقم الحساب / اسم الحساب</th>
            <th style="width: 150px;">نوع حساب المستفيد</th>
            <th style="width: 90px">مدين</th>
            <th style="width: 90px"> دائن</th>
            <th style="width: 200px"> البيان</th>

            <th></th>
            <th></th>
        </tr>
        </thead>
        <tbody>

        <?php if (count($chains_details) <= 0) : ?>
            <tr>
                <td>
                 
                    <input type="hidden" name="financial_chains_seq[]">
                    <select
                            name="account_type[]"
                            id="dp_account_type_<?= $count ?>"
                            class="form-control"
                    >
                        <?php foreach ($account_type as $row) : ?>
                            <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>

                    <select name="bk_fin_id[]"
                            id="dp_bk_fin_id<?= $count ?>"
                            class="form-control"
                            style="display: none;"
                    >
                        <option></option>
                        <?php foreach ($BkFin as $row) : ?>
                            <option value="<?= $row['ID'] ?>"><?= str_repeat('-', strlen($row['ID'])) . ' ' . $row['TITLE'] ?></option>
                        <?php endforeach; ?>
                    </select>

                </td>

                <td>
                    <input type="text" name="account_id[]" id="h_account_<?= $count ?>" class="form-control col-sm-4">
                    <input name="account_id_name[]" type="text" data-val="true" data-val-required="حقل مطلوب"
                           id="account_<?= $count ?>" readonly class="form-control col-sm-7">

                </td>

                <td>
                    <select class="form-control" style="display: none;" id="db_customer_account_type_<?= $count ?>"
                            name="customer_account_type[]">
                        <?php foreach ($ACCOUNT_TYPES as $row) : ?>

                            <option value="<?= $row['ACCCOUNT_TYPE'] ?>"><?= $row['ACCCOUNT_NO_NAME'] ?></option>

                        <?php endforeach; ?>
                    </select>
                </td>
                <td><input type="number" name="debit[]" data-type="decimal" data-val="true" id="debit_<?= $count ?>"
                           data-val-required="حقل مطلوب" data-val-regex="المدخل غير صحيح!"
                           data-val-regex-pattern="<?= float_format_exp() ?>" class="form-control"></td>
                <td><input type="number" name="credit[]" data-type="decimal" data-val="true" id="credit_<?= $count ?>"
                           data-val-required="حقل مطلوب" data-val-regex="المدخل غير صحيح!"
                           data-val-regex-pattern="<?= float_format_exp() ?>" class="form-control"></td>
                <td><input name="dhints[]" type="text" class="form-control"></td>

                <td></td>
                <td></td>
            </tr>
        <?php else: $count = -1; ?>
        <?php endif; ?>

        <?php foreach ($chains_details as $row) : ?>
            <?php $count++; ?>
            <tr>
                <td>
                   
                    <input type="hidden" name="financial_chains_seq[]" value="<?= $row['FINANCIAL_CHAINS_SEQ'] ?>">
                    <!-- <input type="hidden" name="account_type[]" value="<? /*= $row['ACCOUNT_TYPE'] */ ?>">-->
                    <select name="account_type[]" id="dp_account_type_<?= $count ?>" class="form-control">
                        <?php foreach ($account_type as $_row) : ?>
                            <option <?= $row['ACCOUNT_TYPE'] == $_row['CON_NO'] ? 'selected' : '' ?>
                                    value="<?= $_row['CON_NO'] ?>"><?= $_row['CON_NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>

                    <select
                            name="bk_fin_id[]"
                            id="dp_bk_fin_id<?= $count ?>"
                            class="form-control"
                            style="display: <?= $row['CASH_FLOW'] != 5 ? 'none' : 'block' ?>;">
                        <option></option>
                        <?php foreach ($BkFin as $_row) : ?>
                            <option <?= $row['BK_FIN_ID'] == $_row['ID'] ? 'selected' : '' ?>
                                    value="<?= $_row['ID'] ?>"><?= str_repeat('-', strlen($_row['ID'])) . ' ' . $_row['TITLE'] ?></option>
                        <?php endforeach; ?>
                    </select>

                </td>
                <td>
                    <input type="text" name="account_id[]" value="<?= $row['ACCOUNT_ID'] ?>"
                           id="h_account_<?= $count ?>" class="form-control col-sm-4">

                    <input name="account_id_name[]" data-val="true" readonly data-val-required="حقل مطلوب"
                           class="form-control col-sm-8" readonly id="account_<?= $count ?>"
                           value="<?= $row['ACCOUNT_ID_NAME'] ?>">
                </td>

                <td>
                    <select class="form-control" style="display: <?= $row['ACCOUNT_TYPE'] == 2 ? 'block' : 'none' ?> ;"
                            id="db_customer_account_type_<?= $count ?>" name="customer_account_type[]">
                        <?php foreach ($ACCOUNT_TYPES as $_row) : ?>

                            <option <?= $row['CUSTOMER_ACCOUNT_TYPE'] == $_row['ACCCOUNT_TYPE'] ? 'selected' : 'style="display:none;"' ?>
                                    value="<?= $_row['ACCCOUNT_TYPE'] ?>"><?= $_row['ACCCOUNT_NO_NAME'] ?></option>

                        <?php endforeach; ?>
                    </select>
                </td>

                <td><input type="number" name="debit[]" data-type="decimal" data-val="true" id="debit_<?= $count ?>"
                           data-val-required="حقل مطلوب" data-val-regex="المدخل غير صحيح!"
                           data-val-regex-pattern="<?= float_format_exp() ?>" class="form-control"
                           value="<?= $row['DEBIT'] ?>"></td>
                <td><input type="number" name="credit[]" data-type="decimal" data-val="true" id="credit_<?= $count ?>"
                           data-val-required="حقل مطلوب" data-val-regex="المدخل غير صحيح!"
                           data-val-regex-pattern="<?= float_format_exp() ?>" class="form-control"
                           value="<?= $row['CREDIT'] ?>"></td>
                <td><input name="dhints[]" type="text" value="<?= $row['HINTS'] ?>" class="form-control"></td>
                <td><?= $row['ROOT_ACCOUNT_ID_NAME'] ?></td>

                <td><?php if (HaveAccess($delete_details_url)): ?>
                        <?php if ((isset($can_edit) ? $can_edit : false)) : ?>
                            <a onclick="javascript:financial_chains_detail_tb_delete(this,<?= $row['FINANCIAL_CHAINS_SEQ'] ?>);"
                               href="javascript:;"><i class="icon icon-trash delete-action"></i> </a>
                        <?php endif; ?>
                    <?php endif; ?></td>
            </tr>
        <?php endforeach; ?>

        </tbody>
        <tfoot>
        <tr>
            <th rowspan="1" class="align-right" colspan="3">
                <?php if (count($chains_details) <= 0 || (isset($can_edit) ? $can_edit : false)) : ?>
                    <a onclick="javascript:add_row(this,'input',false);" href="javascript:;"><i
                                class="glyphicon glyphicon-plus"></i>جديد</a>
                <?php endif; ?>
            </th>

            <th id="debit_val"></th>
            <th id="credit_val"></th>
            <th rowspan="1" colspan="4"></th>

        </tr>
        </tfoot>
    </table>

</div>


<script>
    if (typeof initFunctions == 'function') {
        initFunctions();
    }


</script>