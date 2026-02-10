<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

$count = 0;
$total_approved_amount = 0;
$total_total_price_class = 0;
?>

<div class="tbl_container">
    <table class="table" id="receipt_class_input_detailTbl" data-container="container">
        <thead>
        <tr>
            <th style="width: 120px"> #</th>
            <th style="width:150px "> رقم الصنف</th>
            <th style="width: 450px"> اسم الصنف</th>
            <th style="width: 450px">الكمية حسب امر التوريد</th>
            <th style="width: 450px">الكمية المنفذة سابقا</th>
            <th style="width: 450px">الكمية المنفذة حاليا</th>
            <th style="width: 450px">اجمالي الكميات المنفذة</th>
            <th style="width: 450px">الكمية المتبقية</th>
            <th style="width: 200px">الوحدة</th>
            <th style="width: 200px">سعر الوحدة/<?= $curr_name ?></th>
            <th style="width: 200px">الإجمالي/<?= $curr_name ?></th>
        </tr>
        </thead>
        <tbody>

        <?php foreach ($rec_details as $row) : ?>
            <?php $count++;
            $total_approved_amount = $total_approved_amount + $row['APPROVED_AMOUNT'];
            $total_total_price_class = $total_total_price_class + ($row['PREV_AMOUNT_RECEIPT'] + $row['CURR_AMOUNT_RECEIPT']) * $row['CUSTOMER_PRICE'];

            ?>
            <tr>
                <td> <?= ($count) ?>
                    <input type="hidden" id="h_ser_<?= $count ?>" name="h_ser[]" value="<?= $row['SER'] ?>">
                </td>
                <td>
                    <input type="text" name="h_class_id[]" id="h_class_id_<?= $count ?>" value="<?= $row['CLASS_ID'] ?>"
                           class="form-control col-sm-2 text-center">
                </td>
                <td>
                    <input name="class_id[]" value='<?= $row['CLASS_ID_NAME'] ?>' id="class_id_<?= $count ?>" readonly
                           class="form-control col-sm-16 text-center">
                </td>
                <td>
                    <input max="<?= $row['PURCHASE_AMOUNT'] ?>" name="approved_amount[]" type="text"
                           id="approved_amount_<?= $count ?>"
                           value="<?= isset($row['APPROVED_AMOUNT']) ? $row['APPROVED_AMOUNT'] : '' ?>"
                           class="form-control text-center">
                </td>
                <td>
                    <input name="prev_amount[]" value='<?= $row['PREV_AMOUNT_RECEIPT'] ?>'
                           id="prev_amount_<?= $count ?>" readonly
                           class="form-control col-sm-16 text-center">
                </td>
                <td>
                    <input name="curr_amount[]" value='<?= $row['CURR_AMOUNT_RECEIPT'] ?>'
                           id="curr_amount_<?= $count ?>" readonly
                           class="form-control col-sm-16 text-center">
                </td>
                <td>
                    <input name="total_amount[]"
                           value='<?= $row['PREV_AMOUNT_RECEIPT'] + $row['CURR_AMOUNT_RECEIPT'] ?>'
                           id="total_amount_<?= $count ?>" readonly
                           class="form-control col-sm-16 text-center">
                </td>
                <td>
                    <input name="reminder_amount[]"
                           value='<?= $row['APPROVED_AMOUNT'] - ($row['PREV_AMOUNT_RECEIPT'] + $row['CURR_AMOUNT_RECEIPT']) ?>'
                           id="reminder_amount_<?= $count ?>" readonly
                           class="form-control col-sm-16 text-center">
                </td>
                <td>
                    <input type="hidden" data-val="true" name="unit_class_id[]" value="<?= $row['CLASS_UNIT'] ?>"
                           class="form-control text-center" id="unit_class_id_<?= $count ?>"
                           class="form-control col-sm-2"/>
                    <?= $row['CLASS_UNIT_NAME'] ?>

                </td>
                <td><input name="total_price[]" id="total_price_<?= $count ?>" class="form-control text-center"
                           value="<?= isset($row['CUSTOMER_PRICE']) ? $row['CUSTOMER_PRICE'] : '' ?>"></td>
                <td><input name="total_price_class[]" id="total_price_class_<?= $count ?>"
                           class="form-control text-center"
                           value="<?= ($row['PREV_AMOUNT_RECEIPT'] + $row['CURR_AMOUNT_RECEIPT']) * $row['CUSTOMER_PRICE'] ?>">
                </td>

            </tr>
        <?php endforeach; ?>

        </tbody>
        <tfoot>
        <tr>

            <th rowspan="1" class="align-right" colspan="2">

            </th>
            <th>
                اجمالي قيمة امر التوريد
            </th>
            <th>
                <?= $total_approved_amount ?>
            </th>
            <th rowspan="1" class="align-right" colspan="4">

            </th>
            <th rowspan="1" class="align-center" colspan="2">
                المجموع
            </th>
            <th id="txt_total_total_price_class">
                <?= $total_total_price_class ?>
            </th>


        </tr>
        <tr>

            <th rowspan="1" class="align-right" colspan="8">

            </th>


            <th rowspan="1" class="align-center" colspan="2">
                خصم مكتسب
            </th>
            <th>
                <input name="discount" value='<?= $discount ?>' id="txt_discount"
                       class="form-control col-sm-16 text-center">
            </th>


        </tr>
        <tr>

            <th rowspan="1" class="align-right" colspan="8">

            </th>


            <th rowspan="1" class="align-center" colspan="2">
                الصافي
            </th>
            <th id="txt_net_total">
                <?= $total_total_price_class - $row['DISCOUNT'] ?>
            </th>


        </tr>
        <tr>

            <th rowspan="1" class="align-right" colspan="8">

            </th>


            <th rowspan="1" class="align-center" colspan="2">
                يخصم - الدفعات السابقة
            </th>
            <th id="txt_prev_extract">
                <?= $prev_paid ?>
            </th>


        </tr>
        <tr>

            <th rowspan="1" class="align-right" colspan="8">

            </th>


            <th rowspan="1" class="align-center" colspan="2">
                المبلغ المستحق للصرف و قدره
            </th>
            <th id="txt_pay">
                <?= ($total_total_price_class - $row['DISCOUNT']) - $row['PREV_EXTRACT'] ?>
            </th>


        </tr>
        </tfoot>
    </table>

</div>


<script>
    if (typeof initFunctions == 'function') {
        initFunctions();
    }


</script>