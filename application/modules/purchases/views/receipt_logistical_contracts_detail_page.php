<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

$count = 0;
$select_accounts_url = base_url('financial/accounts/public_select_accounts');
$customer_url = base_url('payment/customers/public_index');
$project_accounts_url = base_url('projects/projects/public_select_project_accounts');
if (count($rec_details) <= 0) $show_amounts = false; else
    $show_amounts = true;

?>
<input type="hidden" value="<?= $select_accounts_url ?>" id="select_accounts_url">
<input type="hidden" value="<?= $customer_url ?>" id="customer_url">
<input type="hidden" value="<?= $project_accounts_url ?>" id="project_accounts_url">
<div class="tbl_container">
    <table class="table" id="receipt_class_input_detailTbl" data-container="container">
        <thead>
        <tr>
            <th style="width: 2%"> #</th>
            <th style="width: 15%">اسم البند</th>
            <th style="width: 2%">الوحدة</th>
            <th style="width: 10%"> الكمية المستلمة</th>
            <th class="hidden">نوع المستفيد</th>
            <th class="hidden">اسم المستفيد</th>
            <th class="hidden">الإرسالية</th>
        </tr>
        </thead>
        <tbody>

        <?php if (count($rec_logistic_details) <= 0) : ?>
            <?php foreach ($rec_details as $row) : ?>
                <?php $count++; ?>
                <tr>
                    <td> <?= ($count) ?>
                        <input type="hidden" id="h_ser_<?= $count ?>" name="h_ser[]" value="0">
                        <input type="hidden" name="h_receipt_class_input_id_ser[]"
                               id="h_receipt_class_input_id_ser<?= $count ?>"
                               value="<?= $rec_ser ?>">


                    </td>
                    <td>
                        <?= $row['ITEM'] . ":" . $row['CLASS_NAME'] ?>

                        <input type="hidden" name="h_item[]" id="h_item<?= $count ?>"
                               value="<?= $row['ITEM'] ?>" readonly class="form-control ">
                    </td>
                    <td>
                        <?= $row['CLASS_UNIT_NAME'] ?>

                    </td>
                    <td><input name="amount[]" data-val="true" id="amount<?= $count ?>" data-val-required="حقل مطلوب"
                               value="0"
                               data-val-regex="المدخل غير صحيح!" class="form-control"></td>

                    <td class="hidden">
                        <div class="form-group col-sm-2">

                            <select name="customer_type[]" id="customer_type<?= $count ?>" class="form-control">
                                <option value=""></option>
                                <?php foreach ($account_type as $_row) :
                                    if ($_row['CON_NO'] == 1 || $_row['CON_NO'] == 3) {
                                        ?>
                                        <option value="<?= $_row['CON_NO'] ?>"><?= $_row['CON_NAME'] ?></option>
                                    <?php } endforeach; ?>
                            </select>

                        </div>
                    </td>
                    <td class="hidden">
                        <input name="customer_accounts_name[]" data-val="false" readonly data-val-required="حقل مطلوب"
                               class="form-control" readonly id="txt_customer_accounts_id<?= $count ?>"
                               value="">
                        <input type="hidden" name="customer_accounts_id[]" id="h_txt_customer_accounts_id<?= $count ?>"
                               value=""/>

                        <span class="field-validation-valid" data-valmsg-for="customer_id"
                              data-valmsg-replace="true"></span>
                    </td>


                    <td class="hidden"><input name="item_recipt_no[]" data-val="false" id="item_recipt_no<?= $count ?>"
                               data-val-required="حقل مطلوب" value=""
                               data-val-regex="المدخل غير صحيح!" class="form-control" <?= $readonly ?>></td>

                </tr>
            <?php endforeach; ?>
        <?php else: $count = -1; ?>
        <?php endif; ?>

        <?php foreach ($rec_logistic_details as $row) : ?>
            <?php $count++; ?>
            <tr>
                <td> <?= ($count + 1) ?>
                    <input type="hidden" id="h_ser_<?= $count ?>" name="h_ser[]" value="<?= $row['SER'] ?>">
                    <input type="hidden" name="h_receipt_class_input_id_ser[]"
                           id="h_receipt_class_input_id_ser<?= $count ?>"
                           value="<?= $row['RECEIPT_CLASS_INPUT_ID_SER'] ?>">


                </td>
                <td>
                    <?= $row['ITEM_ID'] . ":" . $row['ITEM_ID_NAME'] ?>
                    <input type="hidden" name="h_item[]" id="h_item<?= $count ?>"
                           value="<?= $row['ITEM_ID'] ?>" readonly class="form-control ">

                </td>
                <td>
                    <?= $row['CLASS_UNIT_NAME'] ?>

                </td>
                <td><input name="amount[]" data-val="true" id="amount<?= $count ?>" data-val-required="حقل مطلوب"
                           value="<?= $row['AMOUNT'] ?>"
                           data-val-regex="المدخل غير صحيح!" class="form-control"></td>

                <td class="hidden">
                    <div class="form-group col-sm-2">

                        <select name="customer_type[]" id="customer_type<?= $count ?>" class="form-control">
                            <option value=""></option>
                            <?php foreach ($account_type as $_row) :
                                if ($_row['CON_NO'] == 1 || $_row['CON_NO'] == 3) {
                                    ?>
                                    <option
                                        value="<?= $_row['CON_NO'] ?>" <?php if ($row['CUSTOMER_ACCOUNT_TYPE'] == $_row['CON_NO']) echo 'selected'; else ''; ?>><?= $_row['CON_NAME'] ?></option>
                                <?php } endforeach; ?>
                        </select>

                    </div>
                </td>
                <td class="hidden">
                    <input name="customer_accounts_name[]" data-val="false" readonly data-val-required="حقل مطلوب"
                           class="form-control" readonly id="txt_customer_accounts_id<?= $count ?>"
                           value="<?= $row['CUSTOMER_ID_NAME'] ?>">
                    <input type="hidden" name="customer_accounts_id[]" id="h_txt_customer_accounts_id<?= $count ?>"
                           value="<?= $row['CUSTOMER_ID'] ?>"/>

                    <span class="field-validation-valid" data-valmsg-for="customer_id"
                          data-valmsg-replace="true"></span>
                </td>


                <td class="hidden"><input name="item_recipt_no[]" data-val="false" id="item_recipt_no<?= $count ?>"
                           data-val-required="حقل مطلوب" value="<?= $row['ITEM_RECIPT_NO'] ?>"
                           data-val-regex="المدخل غير صحيح!" class="form-control" <?= $readonly ?>></td>

            </tr>
        <?php endforeach; ?>

        </tbody>
        <tfoot>
        <tr>
            <th rowspan="1" class="align-right" colspan="8">

            </th>


        </tr>
        </tfoot>
    </table>

</div>


<script>
    if (typeof initFunctions == 'function') {
        initFunctions();
    }
    reBind(1);



    function reBind(s) {
        if (s == undefined)
            s = 0;

        $('input[name="customer_accounts_name[]').bind("click", function (e) {
            var _type = $(this).closest('tr').find('select[name="customer_type[]"]').val();
            selectAccount($(this), _type);
        });

        $('select[name="customer_type[]').bind("change", function (e) {

            $(this).closest('tr').find('input[name="customer_accounts_name[]"]').val('');

        });

    }

    function selectAccount(obj, _type) {


        var select_accounts_url = $('#select_accounts_url').val();
        var customer_url = $('#customer_url').val();
        var project_accounts_url = $('#project_accounts_url').val();

        if (_type == 1)
            _showReport(select_accounts_url + '/' + $(obj).attr('id'));
        if (_type == 2)
            _showReport(customer_url + '/' + $(obj).attr('id') + '/');
        if (_type == 3)
            _showReport(project_accounts_url + '/' + $(obj).attr('id') + '/');
        if (_type == 5)
            _showReport(customer_url + '/' + $(obj).attr('id') + '/4');
    }


</script>