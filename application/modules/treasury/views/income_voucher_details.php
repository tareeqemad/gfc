<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 15/10/14
 * Time: 12:14 م
 */
$count = 0;

$SUBSCRIBER = '';
$REQ_TYPE = '';
$CURRENCY_ID = '1';
$SERVER_TYPE = '';
$SUBSCRIBER_NAME = '';
?>
<?php if (count($vouchers_details) || $isPublic) : ?>
    <div class="tbl_container">
        <table class="table" id="voucher_detailsTbl" data-container="container">
            <thead>
            <tr>

                <th style="width: 20px;">#</th>
                <?php if (!$isPublic): ?>
                    <th>الرسوم</th>
                <?php endif;
                if ($isPublic): ?>
                    <th style="width: 150px;">نوع الحساب</th>
                <?php endif; ?>


                <th id="accountTitle" style="width: <?= $isPublic ? '600' : '200' ?>px"> حساب الإيراد</th>

                <?php if ($isPublic): ?>
                    <th style="width: 600px;">نوع حساب المستفيد</th>
                <?php endif; ?>


                <th style="width: 120px;">المبلغ</th>
                <th></th>

            </tr>
            </thead>
            <tbody>
            <?php if ($isPublic): ?>
                <tr>

                    <td><?= $count + 1 ?></td>
                    <td>
                        <select name="account_type[]" id="dp_account_type_<?= $count ?>" class="form-control">
                            <?php foreach ($account_type as $row) : ?>
                                <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>

                    </td>


                    <td>
                        <input type="hidden" name="fees_type[]">
                        <input type="text" class="form-control col-sm-3" data-val="true" data-val-required="حقل مطلوب"
                               name="credit_account_id[]" id="h_txt_credit_account_id_<?= $count ?>"/>
                        <input type="text" name="credit_account_id_name[]" readonly
                               id="txt_credit_account_id_<?= $count ?>" class="form-control col-sm-8"/>

                    </td>

                    <?php if ($isPublic): ?>
                        <td>
                            <select class="form-control"
                                    style="display: none;"
                                    data-val="true" data-val-required="حقل مطلوب"
                                    id="db_customer_account_type_<?= $count ?>"
                                    name="customer_account_type[]">
                                <option></option>
                                <?php foreach ($ACCOUNT_TYPES as $row) : ?>

                                    <option value="<?= $row['ACCCOUNT_TYPE'] ?>" style="display: none;"><?= $row['ACCCOUNT_NO_NAME'] ?></option>

                                <?php endforeach; ?>
                            </select>
                        </td>
                    <?php endif; ?>


                    <td>
                        <input type="text" name="credit[]" id="txt_credit" data-val="true" data-val-required="حقل مطلوب"
                               class="form-control "/>

                    </td>

                    <td data-action="delete"></td>
                    <?php $count++ ?>
                </tr>
            <?php endif; ?>
            <?php foreach ($vouchers_details as $voucher) : ?>
                <tr>

                    <td><?= $count + 1 ?></td>
                    <td><input type="text" readonly value="<?= $voucher['SERVICE_NAME'] ?>" class="form-control "/></td>
                    <td>
                        <input type="hidden" name="credit_account_id[]" value="<?= $voucher['ACCOUNT_ID'] ?>"
                               id="h_txt_credit_account_id_<?= $count ?>"/>
                        <input type="hidden" name="fees_type[]" value="<?= $voucher['FEES_TYPE'] ?>">
                        <input type="text" value="<?= $voucher['ACOUNT_NAME'] ?>" readonly
                               name="credit_account_id_name[]" id="txt_credit_account_id_<?= $count ?>"
                               class="form-control "/>
                    </td>
                    <td>
                        <input type="text" value="<?= $voucher['VALUE'] ?>" readonly name="credit[]" id="txt_credit"
                               class="form-control "/>

                    </td>

                    <td></td>
                    <?php $count++ ?>
                </tr>
            <?php endforeach; ?>
            </tbody>

            <?php if ($isPublic): ?>
                <tfoot>
                <tr>
                    <th rowspan="1" colspan="2" class="align-right">

                        <a onclick="javascript:add_row(this,'input',true);"
                           onfocus="javascript:add_row(this,'input',true);" href="javascript:;"><i
                                class="glyphicon glyphicon-plus"></i>جديد</a>

                    </th>
                    <th id="total"></th>
                    <th rowspan="1" colspan="2"></th>


                </tr>
                </tfoot>
            <?php endif; ?>
        </table>
        <hr>
        <!-- <table class="table" id="voucher_SubdetailsTbl" data-container="container">
            <thead>
            <tr>

                <th  >#</th>
                <th style="width: 400px">رقم الاشتراك </th>
                <th style="width: 400px" >المبلغ</th>
                <th>الملاحظات</th>

            </tr>
            </thead>
            <tbody>

            <?php foreach ($vouchers_details as $voucher) : ?>
                <tr>

                    <td><?= $count ?></td>

                    <td>

                        <input type="text" value="<?= $voucher['CREDIT_ACCOUNT_ID_NAME'] ?>" readonly  class="form-control "/>
                    </td>
                    <td>
                        <input type="text" value="<?= $voucher['CREDIT'] ?>" readonly    class="form-control "/>

                    </td>

                    <td> </td>
                    <?php $count++ ?>
                </tr>
            <?php endforeach; ?>
            </tbody>


        </table> -->
    </div>
    <?php
    if (count($vouchers_details)) {
        $SUBSCRIBER = $vouchers_details[0]["SUBSCRIBER"];
        $REQ_TYPE = $vouchers_details[0]['CON_NAME'];
        $CURRENCY_ID = $vouchers_details[0]['VALUE_TYPE'];
        $SERVER_TYPE = $vouchers_details[0]['REQ_TYPE'];
        $SUBSCRIBER_NAME = $vouchers_details[0]['SUBSCRIBER_NAME'];
        $NOTES = $vouchers_details[0]['NOTES'];
    }

    ?>

<?php endif; ?>
<hr>
<?php $count = 1 ?>
<div class="tbl_container2" id="tbl_container2" style="display: none;">
    <table class="table" id="voucher_details2Tbl" data-container="container">
        <thead>
        <tr>
            <th style="width: 20px;">#</th>

            <?php if ($isPublic): ?>
                <th style="width: 150px;">نوع الحساب</th>
            <?php endif; ?>

            <th style="width: 600px;"> حساب الإيراد</th>

            <th style="width: 120px;">المبلغ</th>
            <th></th>

        </tr>
        </thead>
        <tbody>

        <tr>

            <td><?= $count ?></td>
            <td>
                <select name="d2_account_type[]" id="dp_account_type_<?= $count ?>" class="form-control">
                    <?php foreach ($account_type as $row) : ?>
                        <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                    <?php endforeach; ?>
                </select>

            </td>
            <td>
                <input type="number" class="form-control col-sm-3" data-val="true" data-val-required="حقل مطلوب"
                       name="d2_account_id[]" id="h_txt_d2_account_id_<?= $count ?>"/>
                <input type="text" name="d2_account_id_name[]" readonly id="txt_d2_account_id_<?= $count ?>"
                       class="form-control col-sm-8"/>

            </td>
            <td>
                <input type="text" name="d2_credit[]" id="txt_d2_credit" data-val="true" data-val-required="حقل مطلوب"
                       class="form-control "/>

            </td>

            <td></td>
            <?php $count++ ?>
        </tr>


        </tbody>


        <tfoot>
        <tr>
            <th rowspan="1" colspan="4" class="align-right">

                <a onclick="javascript:addRow2();" onfocus="javascript:addRow2();" href="javascript:;"><i
                        class="glyphicon glyphicon-plus"></i>جديد</a>

            </th>


        </tr>
        </tfoot>

    </table>


</div>



<?php
if (count($vouchers_details)) {
    $scripts = <<<SCRIPT
    <script>
        $('#txt_supscriper_id').val('$SUBSCRIBER');
        $('#txt_server_type_name').val('$REQ_TYPE');
        $('#dp_currency_id').val('$CURRENCY_ID');
        $('input[name=currency_id]').val('$CURRENCY_ID');
        $('#txt_server_type').val('$SERVER_TYPE');
        $('#txt_supscriper_id_name').val('$SUBSCRIBER_NAME');
        $('#txt_hints').val(' سند قبض خدمة : $NOTES');
    </script>

SCRIPT;

    echo($scripts);
}
?>


<script>
    if (typeof initFunctions == 'function') {
        initFunctions();
    }
</script>