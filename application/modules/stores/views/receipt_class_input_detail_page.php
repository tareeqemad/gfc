<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 03/12/14
 * Time: 11:21 ص
 */
$count = 0;
$delete_details_url = base_url('stores/receipt_class_input_detail/delete');
$hidden = "hidden";
if ($action == 'index') {
    $receipt_data = array();

    $donation_file_id = 0;

} else if ($action == 'edit') {

    $donation_file_id = (isset($receipt_data['DONATION_FILE_ID'])) ? $receipt_data['DONATION_FILE_ID'] : 0;
    $account_type = (isset($receipt_data['ACCOUNT_TYPE'])) ? $receipt_data['ACCOUNT_TYPE'] : 0;
//echo $account_type;
    $order_id = (isset($receipt_data['ORDER_ID'])) ? $receipt_data['ORDER_ID'] : '';
    $type_matter = (isset($receipt_data['TYPE_MATTER'])) ? $receipt_data['TYPE_MATTER'] : '';
    if ($type_matter != 1)
        $hidden = "hidden";
    else
        $hidden = "";
}


?>

<div class="tbl_container">
    <table class="table" id="receipt_class_input_detailTbl" data-container="container">
        <thead>
        <tr>
            <th style="width: 120px"> #</th>
            <th style="width:150px "> رقم الصنف</th>
            <th style="width: 450px"> اسم الصنف</th>
            <th style="width: 200px">الوحدة</th>
            <th style="width: 70px">حالة الصنف</th>
            <!--
            <th style="width: 100px" class="<?= $hidden ?>">كمية أمر التوريد</th>
            -->
            <th style="width: 100px"> الكمية المستلمة</th>
            <!--
            <th style="width: 100px" class="<?= $hidden ?>">الكمية المتبقية</th>
            -->
            <th style="width: 100px"> الكمية المطابقة للمواصفات</th>
            <th style="width: 100px">الكمية المرفوضة</th>


            <th></th>
        </tr>
        </thead>
        <tbody>

        <?php if (count($rec_details) <= 0) : ?>
            <tr>

                <td>
                    <? //=($count+1)?> <i class="glyphicon glyphicon-sort"/></i>

                    <input type="hidden" name="h_ser[]" id="h_ser_<?= $count ?>" value="0"
                           class="form-control col-sm-3">
                    <input type="hidden" name="donation_file_ser[]" id="h_donation_file_ser_<?= $count ?>" value="0">

                </td>
                <td>

                    <input type="text" name="h_class_id[]" data-val="true" data-val-required="حقل مطلوب"
                           id="h_class_id_<?= $count ?>" class="form-control col-sm-2">

                </td>
                <td>

                    <input name="class_id[]" data-val="true" data-val-required="حقل مطلوب" id="class_id_<?= $count ?>"
                           readonly class="form-control col-sm-16">

                </td>
                <td>
                    <select name="unit_class_id[]" class="form-control" id="unit_class_id_<?= $count ?>">
                        <option></option>
                    </select>
                </td>
                <td id="class_type_class_id_<?= $count ?>">جديد</td>
                <!--
                <td class="<?= $hidden ?>">
                    <input name="approved_amount_order[]" data-val="true" id="approved_amount_order_<?= $count ?>"
                           value="" class="form-control col-sm-16" readonly>
                </td>
                -->
                <td><input name="amount[]" data-val="true" id="amount_<?= $count ?>" data-val-required="حقل مطلوب"
                           data-val-regex="المدخل غير صحيح!" class="form-control">

                </td>
                <!--
                <td class="<?= $hidden ?>">
                    <input name="remainder[]" data-val="true" id="remainder_<?= $count ?>" value=""
                           class="form-control col-sm-16" readonly>
                </td>
                -->
                <td><input name="approved_amount[]" readonly id="approved_amount_<?= $count ?>" type="text"
                           class="form-control"></td>

                <td><input name="refused_amount[]" readonly id="refused_amount_<?= $count ?>" type="text"
                           class="form-control"></td>


            </tr>
        <?php else: $count = -1; ?>
        <?php endif; ?>

        <?php foreach ($rec_details as $row) : ?>
            <?php $count++; ?>
            <tr>
                <td> <?= ($count + 1) ?>
                    <input type="hidden" id="h_ser_<?= $count ?>" name="h_ser[]" value="<?= $row['SER'] ?>">
                    <input type="hidden" name="donation_file_ser[]" id="h_donation_file_ser_class_id_<?= $count ?>"
                           value="<?= $row['DONATION_FILE_SER'] ?>">

                </td>
                <td>
                    <input type="text" name="h_class_id[]" id="h_class_id_<?= $count ?>" value="<?= $row['CLASS_ID'] ?>"
                           class="form-control col-sm-2">
                </td>
                <td>
                    <input name="class_id[]" value='<?= $row['CLASS_ID_NAME'] ?>' id="class_id_<?= $count ?>" readonly
                           class="form-control col-sm-16">
                </td>
                <td>
                    <select name="unit_class_id[]" class="form-control  col-sm-10" id="unit_class_id_<?= $count ?>"
                            data-val="<?= $row['CLASS_UNIT'] ?>">
                        <option></option>
                    </select>

                </td>
                <td id="class_type_class_id_<?= $count ?>"><?= $row['CLASS_TYPE_NAME'] ?></td>
                <!--
                <td class="<?= $hidden ?>"><input name="approved_amount_order[]" data-val="true"
                                                  id="approved_amount_order_<?= $count ?>"
                                                  value="<?= $row['APPROVED_AMOUNT_ORDER'] ?>"
                                                  class="form-control col-sm-16" readonly></td>
                                                  -->
                <td>

                    <input name="amount[]" <?php if ($donation_file_id > 0) { ?>   ondblclick="alert('<?= $row['REM_AMOUNT'] ?>'); return false ;" <?php } else if ($account_type == 2 and $order_id != '' and $row['ORDER_AMOUNT'] != -1) { ?>  max="<?= $row['ORDER_AMOUNT'] ?>" ondblclick="alert('<?= $row['ORDER_AMOUNT'] ?>'); return false ;" <?php } ?>
                           data-val="true" id="amount_<?= $count ?>" data-val-required="حقل مطلوب"
                           data-val-regex="المدخل غير صحيح!" class="form-control" value="<?= $row['AMOUNT'] ?>">

                </td>
                <!--
                <td class="<?= $hidden ?>"><input name="remainder[]" data-val="true" id="remainder_<?= $count ?>"
                                                  value="<?= $row['APPROVED_AMOUNT_ORDER'] - $row['AMOUNT'] ?>"
                                                  class="form-control col-sm-16" readonly></td>
                                                  -->
                <td>
                    <input readonly name="approved_amount[]" type="text" id="approved_amount_<?= $count ?>"
                           value="<?= $row['APPROVED_AMOUNT'] ?>" class="form-control">
                </td>
                <td>
                    <input name="refused_amount[]" readonly type="text" id="refused_amount_<?= $count ?>"
                           value="<?= ($row['AMOUNT'] - $row['APPROVED_AMOUNT']) ?>" class="form-control">
                </td>

                <td><?php if (HaveAccess($delete_details_url)): ?>
                        <?php if ((isset($can_edit) ? $can_edit : false)) : ?>
                            <a onclick="receipt_class_input_detail_tb_delete(this,<?= $row['SER'] ?>);"
                               href="javascript:"><i class="icon icon-trash delete-action"></i> </a>
                        <?php endif; ?>
                    <?php endif; ?>

                </td>
            </tr>
        <?php endforeach; ?>

        </tbody>
        <tfoot>
        <tr>
            <?php
            if ($hidden == '') {
                $colspan = 12;
            } else {
                $colspan = 10;
            }
            ?>
            <th rowspan="1" class="align-right" colspan="<?= $colspan ?>">
                <?php if (count($rec_details) <= 0 || ($action == 'edit')) ://if (count($chains_details) <=0 || ( isset($can_edit)?$can_edit:false)) : ?>
                    <a onclick="addRow();" onfocus="addRow();" href="javascript:"><i
                                class="glyphicon glyphicon-plus"></i>جديد</a>
                <?php endif; ?>
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