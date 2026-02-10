<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

$count = 0;
$select_accounts_url = base_url('financial/accounts/public_select_accounts');
$customer_url = base_url('payment/customers/public_index');
$project_accounts_url = base_url('projects/projects/public_select_civil_project_accounts');
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
            <th> #</th>
            <th>اسم البند</th>
            <th>الوحدة</th>
            <th class="hidden">نوع المستفيد</th>
            <th style="width:15%">اسم المشروع</th>
            <th>رقم المشروع</th>
            <th>
                التخطيط


            </th>

            <th>
                المصروف حاليا


            </th>

            <th>
                فرق الكمية


            </th>

            <th>
                المصروف سابقا


            </th>

            <th>
                الإجمالي المصروف


            </th>


            <th>تاريخ الصب</th>
            <th>تاريخ الفحص</th>
            <th>نتيجة الفحص</th>
            <th>رقم الارسالية</th>
            <th>سعر الوحدة /<span style='font-size:10px;'>&#8362;</span></th>
            <th>الإجمالي/<span style='font-size:10px;'>&#8362;</span></th>
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
                        <input type="hidden" name="count[]"
                               id="h_count<?= $count ?>"
                               value="<?= $count ?>">


                    </td>
                    <td>
                        <?= $row['ITEM'] . ":" . $row['CLASS_NAME'] ?>

                        <input type="hidden" name="h_item[]" id="h_item<?= $count ?>"
                               value="<?= $row['ITEM'] ?>" readonly class="form-control ">
                    </td>
                    <td>
                        <?= $row['CLASS_UNIT_NAME'] ?>

                    </td>
                    <td class="hidden">
                        <div class="form-group col-sm-2">

                            <select name="customer_type[]" id="customer_type<?= $count ?>" class="form-control">

                                <?php foreach ($account_type as $_row) :
                                    if ($_row['CON_NO'] == 3) {
                                        ?>
                                        <option value="<?= $_row['CON_NO'] ?>"><?= $_row['CON_NAME'] ?></option>
                                    <?php } endforeach; ?>
                            </select>

                        </div>
                    </td>
                    <td>
                        <input name="customer_accounts_name[]" data-val="false" readonly data-val-required="حقل مطلوب"
                               class="form-control" readonly id="txt_civil_customer_accounts_name<?= $count ?>"
                               value="">
                        <input type="hidden" name="h_civil_customer_accounts_name[]"
                               id="h_civil_customer_accounts_name<?= $count ?>"
                               value=""/>

                        <span class="field-validation-valid" data-valmsg-for="h_civil_customer_accounts_name"
                              data-valmsg-replace="true"></span>
                    </td>
                    <td>
                        <input name="txt_civil_project_tec_code[]" data-val="false" readonly
                               data-val-required="حقل مطلوب"
                               class="form-control" readonly id="txt_civil_project_tec_code<?= $count ?>"
                               value="">
                        <span class="field-validation-valid" data-valmsg-for="txt_civil_project_tec_code"
                              data-valmsg-replace="true"></span>
                    </td>
                    <td><input name="planning_mount[]" data-val="true" id="planning_mount<?= $count ?>"
                               data-val-required="حقل مطلوب"
                               value="0"
                               data-val-regex="المدخل غير صحيح!" class="form-control"></td>


                    <td><input name="item_recipt_mount[]" data-val="false" id="item_recipt_mount<?= $count ?>"
                               data-val-required="حقل مطلوب" value="0"
                               data-val-regex="المدخل غير صحيح!" class="form-control"></td>

                    <td><input name="diff_mount[]" data-val="false" id="diff_mount<?= $count ?>"
                               data-val-required="حقل مطلوب" value="0"
                               data-val-regex="المدخل غير صحيح!" class="form-control" readonly></td>

                    <td><input name="prev_mount[]" data-val="false" id="prev_mount<?= $count ?>"
                               data-val-required="حقل مطلوب"  value="0"
                               data-val-regex="المدخل غير صحيح!" class="form-control"></td>

                    <td><input name="total_mount[]" data-val="false" id="total_mount<?= $count ?>"
                               data-val-required="حقل مطلوب"  value="0"
                               data-val-regex="المدخل غير صحيح!" class="form-control" readonly></td>

                    <td><input name="to_pour_date[]" data-val="false" id="to_pour_date<?= $count ?>"
                               data-val-required="حقل مطلوب" value="" data-type="date"  data-date-format="DD/MM/YYYY"  data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?= date_format_exp() ?>" data-val="false"
                               data-val-regex="المدخل غير صحيح!" class="form-control datepicker"></td>

                    <td><input name="check_date[]" data-val="false" id="check_date<?= $count ?>"
                               data-val-required="حقل مطلوب" value="" data-type="date"  data-date-format="DD/MM/YYYY"  data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?= date_format_exp() ?>" data-val="false"
                               data-val-regex="المدخل غير صحيح!" class="form-control datepicker"></td>

                    <td>     <select name="check_result[]" id="check_result<?= $count ?>" class="form-control">
                            <?php foreach ($check_result as $_row) :

                                ?>
                                <option
                                    value="<?= $_row['CON_NO'] ?>"><?= $_row['CON_NAME'] ?></option>
                            <?php  endforeach; ?>
                        </select></td>

                    <td><input name="item_recipt_no[]" data-val="false" id="item_recipt_no<?= $count ?>"
                               data-val-required="حقل مطلوب" value=""
                               data-val-regex="المدخل غير صحيح!" class="form-control"></td>

                    <td><input name="item_price[]" data-val="false" id="item_price<?= $count ?>"
                               data-val-required="حقل مطلوب" value="<?= $row['CUSTOMER_PRICE']?>"
                               data-val-regex="المدخل غير صحيح!" class="form-control" readonly></td>

                    <td><input name="total[]" data-val="false" id="total<?= $count ?>"
                               data-val-required="حقل مطلوب" value="0"
                               data-val-regex="المدخل غير صحيح!" class="form-control" readonly></td>

                </tr>
            <?php endforeach; ?>
        <?php else:  ?>


        <?php foreach ($rec_logistic_details as $row) : ?>
            <?php $count++; ?>
            <tr>
                <td> <?= ($count) ?>
                    <input type="hidden" id="h_ser_<?= $count ?>" name="h_ser[]" value="<?= $row['SER'] ?>">
                    <input type="hidden" name="h_receipt_class_input_id_ser[]"
                           id="h_receipt_class_input_id_ser<?= $count ?>"
                           value="<?= $row['RECEIPT_CLASS_INPUT_ID_SER'] ?>">
                    <input type="hidden" name="count[]"
                           id="h_count<?= $count ?>"
                           value="<?= $count ?>">

                </td>
                <td>
                    <?= $row['ITEM_ID'] . ":" . $row['ITEM_ID_NAME'] ?>
                    <input type="hidden" name="h_item[]" id="h_item<?= $count ?>"
                           value="<?= $row['ITEM_ID'] ?>" readonly class="form-control ">

                </td>
                <td>
                    <?= $row['CLASS_UNIT_NAME'] ?>

                </td>


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
                <td>
                    <input name="customer_accounts_name[]" data-val="false" readonly data-val-required="حقل مطلوب"
                           class="form-control" readonly id="txt_civil_customer_accounts_name<?= $count ?>"
                           value="<?= $row['CUSTOMER_ID_NAME'] ?>">
                    <input type="hidden" name="h_civil_customer_accounts_name[]" id="h_civil_customer_accounts_name<?= $count ?>"
                           value="<?= $row['CUSTOMER_ID'] ?>"/>

                    <span class="field-validation-valid" data-valmsg-for="h_civil_customer_accounts_name"
                          data-valmsg-replace="true"></span>
                </td>

                <td>
                    <input name="txt_civil_project_tec_code[]" data-val="false" readonly
                           data-val-required="حقل مطلوب"
                           class="form-control" readonly id="txt_civil_project_tec_code<?= $count ?>"
                           value="<?= $row['PROJECT_TEC_CODE'] ?>">
                    <span class="field-validation-valid" data-valmsg-for="txt_civil_project_tec_code"
                          data-valmsg-replace="true"></span>
                </td>
                <td><input name="planning_mount[]" data-val="true" id="planning_mount<?= $count ?>" data-val-required="حقل مطلوب"
                           value="<?= $row['PLANNING_MOUNT'] ?>"
                           data-val-regex="المدخل غير صحيح!" class="form-control"></td>

                <td><input name="item_recipt_mount[]" data-val="false" id="item_recipt_mount<?= $count ?>"
                           data-val-required="حقل مطلوب" value="<?= $row['ITEM_RECIPT_MOUNT'] ?>"
                           data-val-regex="المدخل غير صحيح!" class="form-control"></td>

                <td><input name="diff_mount[]" data-val="false" id="diff_mount<?= $count ?>"
                           data-val-required="حقل مطلوب" value="<?= $row['DIFF_MOUNT'] ?>"
                           data-val-regex="المدخل غير صحيح!" class="form-control" readonly></td>

                <td><input name="prev_mount[]" data-val="false" id="prev_mount<?= $count ?>"
                           data-val-required="حقل مطلوب" value="<?= $row['PREV_MOUNT'] ?>"
                           data-val-regex="المدخل غير صحيح!" class="form-control"></td>

                <td><input name="total_mount[]" data-val="false" id="total_mount<?= $count ?>"
                           data-val-required="حقل مطلوب" value="<?= $row['TOTAL_MOUNT'] ?>"
                           data-val-regex="المدخل غير صحيح!" class="form-control" readonly></td>

                <td><input name="to_pour_date[]" data-val="false" id="to_pour_date<?= $count ?>"
                           data-val-required="حقل مطلوب" value="<?= $row['TO_POUR_DATE'] ?>" data-type="date"  data-date-format="DD/MM/YYYY"  data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?= date_format_exp() ?>" data-val="false"
                           data-val-regex="المدخل غير صحيح!" class="form-control datepicker"></td>

                <td><input name="check_date[]" data-val="false" id="check_date<?= $count ?>"
                           data-val-required="حقل مطلوب" value="<?= $row['CHECK_DATE'] ?>" data-type="date"  data-date-format="DD/MM/YYYY"  data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?= date_format_exp() ?>" data-val="false"
                           data-val-regex="المدخل غير صحيح!" class="form-control datepicker"></td>

                <td>

                  <select name="check_result[]" id="check_result<?= $count ?>" class="form-control">
                        <?php foreach ($check_result as $_row) :

                                ?>
                                <option
                                    value="<?= $_row['CON_NO'] ?>" <?php if ($row['CHECK_RESULT'] == $_row['CON_NO']) echo 'selected'; else ''; ?>><?= $_row['CON_NAME'] ?></option>
                            <?php  endforeach; ?>
                    </select>

                </td>


                <td><input name="item_recipt_no[]" data-val="false" id="item_recipt_no<?= $count ?>"
                           data-val-required="حقل مطلوب" value="<?= $row['ITEM_RECIPT_NO'] ?>"
                           data-val-regex="المدخل غير صحيح!" class="form-control" ></td>

                <td><input name="item_price[]" data-val="false" id="item_price<?= $count ?>"
                           data-val-required="حقل مطلوب" value="<?= $row['ITEM_PRICE'] ?>"
                           data-val-regex="المدخل غير صحيح!" class="form-control" readonly ></td>

                <td><input name="total[]" data-val="false" id="total<?= $count ?>"
                           data-val-required="حقل مطلوب" value="<?= $row['TOTAL'] ?>"
                           data-val-regex="المدخل غير صحيح!" class="form-control" readonly></td>

            </tr>
        <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
        <tfoot>
        <tr>
            <th rowspan="1" class="align-right" colspan="16">

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
        $('.datepicker').datepicker({dateFormat:'dd/mm/yy'});


        $('input[name="customer_accounts_name[]').bind("click", function (e) {
            var _type = $(this).closest('tr').find('select[name="customer_type[]"]').val();
            var count = $(this).closest('tr').find('input[name="count[]"]').val();

            alert(count)

            selectAccount($(this), _type, count);
        });

        $('select[name="customer_type[]').bind("change", function (e) {

            $(this).closest('tr').find('input[name="customer_accounts_name[]"]').val('');

        });

        $('input[name="planning_mount[]"],input[name="item_recipt_mount[]"]').bind("change", function (e) {

            var planning_mount=$(this).closest('tr').find('input[name="planning_mount[]"]').val();
            var item_recipt_mount=$(this).closest('tr').find('input[name="item_recipt_mount[]"]').val();
            var diff_mount=$(this).closest('tr').find('input[name="diff_mount[]"]').val(planning_mount-item_recipt_mount);

        });

        $('input[name="prev_mount[]"],input[name="item_recipt_mount[]"]').bind("change", function (e) {

            var prev_mount=$(this).closest('tr').find('input[name="prev_mount[]"]').val();
            var item_recipt_mount=$(this).closest('tr').find('input[name="item_recipt_mount[]"]').val();
            var total_mount=$(this).closest('tr').find('input[name="total_mount[]"]').val(parseFloat(prev_mount)+parseFloat(item_recipt_mount));

        });

        $('input[name="item_recipt_mount[]"],input[name="item_price[]"]').bind("change", function (e) {

            var item_price=$(this).closest('tr').find('input[name="item_price[]"]').val();
            var item_recipt_mount=$(this).closest('tr').find('input[name="item_recipt_mount[]"]').val();
            var total=$(this).closest('tr').find('input[name="total[]"]').val(parseFloat(item_price)*parseFloat(item_recipt_mount));

        });

    }

    function selectAccount(obj, _type, count) {


        var select_accounts_url = $('#select_accounts_url').val();
        var customer_url = $('#customer_url').val();
        var project_accounts_url = $('#project_accounts_url').val();

        if (_type == 1)
            _showReport(select_accounts_url + '/' + $(obj).attr('id'));
        if (_type == 2)
            _showReport(customer_url + '/' + $(obj).attr('id') + '/');
        if (_type == 3)
            _showReport(project_accounts_url + '/' + count);
        if (_type == 5)
            _showReport(customer_url + '/' + $(obj).attr('id') + '/4');
    }


</script>