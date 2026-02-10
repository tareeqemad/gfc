<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 26/05/2019
 * Time: 12:06 م
 */
//this
$MODULE_NAME = 'purchases';
$TB_NAME = 'suppliers_offers';
$orders_page = base_url("purchases/orders/public_index_orders");
$countDetComp = 1;
$count = 0;
$count1 = 0;
$countDet = 1;
function class_name($amount, $p_amount)
{
    if ($amount < $p_amount) return '#ffe1b2';
    else return '#FFFFFF';

}
$sum_cust = array();
$asum_cust = array();
?>
<style>
    .details-table thead tr th {
        background-color: #428bca;
        padding: 10px 3px;
        text-shadow: 0 0 2px #000;
        color: #FFF;
    }
    .percent-input {
        padding-left: 12px;
        background-image: url('<?=base_url('assets/images/percent-sign.gif')?>');
        background-size: 10px;
        background-repeat: no-repeat;
        background-position: center left;
    }
</style>
<!--Start First Table -->
<div class="tbl_container1">
    <input type="hidden" name="counter" value="<?= count($suppliers_offers_data) ?>" id="counter">
    <table class="table" id="suppliers_offers" data-container="container">
        <thead>
        <tr>
            <th>#</th>
            <th>الشركات</th>
            <th> نسبة الخصم للمورد</th>
            <th> قيمة الخصم للمورد بالعملة الموحدة</th>
            <th>ملاحظات اللجنة على المورد</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($suppliers_offers_data as $row) :
            $sum_cust[$row['SUPPLIERS_OFFERS_ID']] = 0;
            $asum_cust[$row['SUPPLIERS_OFFERS_ID']] = 0;
            ?>
            <tr data-val="<?= $row['SUPPLIERS_OFFERS_ID'] ?>" data-sub-id="<?= $row['SUPPLIERS_OFFERS_ID'] ?>">
                <td><?= $countDetComp ?></td>
                <td>
                    <a href="javascript:;"
                       onclick="_showReport('<?= $orders_page ?>?purchase_order_id=<?= $row['PURCHASE_ORDER_ID'] ?>&customer_id=<?= $row['CUSTOMER_ID'] ?>&customer_curr_id=<?= $row['CUSTOMER_CURR_ID'] ?>' );"> <?= $row['SUPPLIERS_OFFERS_ID'] ?>
                        -<?= $row['CUST_NAME'] ?></a>
                    <span style="display: block;"
                          data-type="suppliers-totals" data-val="<?= $row['SUPPLIERS_OFFERS_ID'] ?>"
                    >
                    </span>
                </td>
                <td>
                    <input data-val="<?= $row['SUPPLIERS_OFFERS_ID'] ?>"

                           type="text" name="suppliers_discount[<?= $row['SUPPLIERS_OFFERS_ID'] ?>]"
                           min="0" value="<?= $row['SUPPLIERS_DISCOUNT'] ?>"
                           id="suppliers_discount_<?= $row['SUPPLIERS_OFFERS_ID'] ?>" data-val-required="حقل مطلوب"
                           data-val-regex="المدخل غير صحيح!"
                           class="form-control percent-input change_suppliers_discount">
                    <input style="width: 40px" type="hidden" name="suppliers_offers_id[]" min="0"
                           value="<?= $row['SUPPLIERS_OFFERS_ID'] ?>"
                           id="suppliers_offers_id_<?= $row['SUPPLIERS_OFFERS_ID'] ?>" data-val-required="حقل مطلوب"
                           data-val-regex="المدخل غير صحيح!" class="form-control">
                </td>
                <td>
                    <input
                            data-val="<?= $row['SUPPLIERS_OFFERS_ID'] ?>"
                            type="text" name="c_discount_value[<?= $row['SUPPLIERS_OFFERS_ID'] ?>]"
                            min="0" value="<?= $row['SUPPLIERS_DISCOUNT_VALUE'] ?>"
                            id="discount_value_<?= $row['SUPPLIERS_OFFERS_ID'] ?>" data-val-required="حقل مطلوب"
                            data-val-regex="المدخل غير صحيح!" class="form-control change_suppliers_discount_val">

                    <input style="width: 40px" type="hidden" name="suppliers_offers_ids[]" min="0"
                           value="<?= $row['SUPPLIERS_OFFERS_ID'] ?>"
                           id="suppliers_offers_ids_<?= $row['SUPPLIERS_OFFERS_ID'] ?>" data-val-required="حقل مطلوب"
                           data-val-regex="المدخل غير صحيح!" class="form-control">
                </td>
                <td>
                    <!--   <input type="text" name="award_notes[<?= $row['SUPPLIERS_OFFERS_ID'] ?>]" data-val="true"   id="award_notes_<?= $row['SUPPLIERS_OFFERS_ID'] ?>"  value="<?= $row['AWARD_NOTES'] ?>"  class="form-control col-sm-11">-->
                    <textarea class="form-control" style="width: 100%; "
                              name="award_notes[<?= $row['SUPPLIERS_OFFERS_ID'] ?>]"
                              id="award_notes_<?= $row['SUPPLIERS_OFFERS_ID'] ?>"><?= $row['AWARD_NOTES'] ?></textarea>
                </td>
                <?php
                $countDetComp++;
                ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
        <tr>
            <td colspan="4"></td>
            <td>
                <button type='button' onclick="addDetailCmp(this);" class='btn btn-success'>
                    حفظ بيانات الشركة
                </button>
            </td>
        </tr>
        </tfoot>
    </table>
</div>
<!-- End First Table -->

<div class="tbl_container">
    <input type="hidden" name="counter" value="<?= count($suppliers_offers_data) ?>" id="counter">
    <table class="table" id="suppliers_offers_detTbl" data-container="container">
        <thead>
        <tr>
            <th>#</th>
            <th> رقم الصنف</th>
            <th> اسم الصنف</th>
            <th>الوحدة</th>
            <th>قرار اللجنة</th>
            <!--<th >ملاحظات على تاجيل الصنف</th>-->
            <th>كمية طلب الشراء</th>
            <!-- <th></th> -->
            <th width="65%;"></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($rec_details as $row1) : $count1++; ?>

        <?php if ($row1['AWARD_DELAY_DECISION'] == 0) { ?>
            <tr data-class-id="<?= $row1['CLASS_ID'] ?>" style="background: rgba(184, 50, 60, 0.27);">
        <?php } elseif ($row1['AWARD_DELAY_DECISION'] == 1) { ?>
            <tr data-class-id="<?= $row1['CLASS_ID'] ?>" style="background: rgba(104, 181, 2, 0.39);">
        <?php } else { ?>
        <tr data-class-id="<?= $row1['CLASS_ID'] ?>" style="background: rgba(245, 225, 66,11);">
        <?php } ?>
            <td>
                <?= ($count1) ?>
                <input type="hidden" id="h_ser_<?= $count ?>" name="h_ser[]" value="<?= $row1['SER'] ?>">
            </td>
            <td>
                <input type="hidden" name="h_class_id[]" data-val="true" id="h_class_id_<?= $count1 ?>"
                       value="<?= $row1['CLASS_ID'] ?>" class="form-control col-sm-2">
                <a href="javascript:;"
                   onclick="_showReport('<?= base_url("stores/class_amount/public_class_movements/" . $row1['CLASS_ID']) ?>' );"><?= $row1['CLASS_ID'] ?></a>
            </td>
            <td>
                <?= $row1['CLASS_ID_NAME'] ?>
                <input type="hidden" name="class_id_name[]" value="<?= $row1['CLASS_ID_NAME'] ?>">
            </td>
            <td>
                <?= $row1['CLASS_UNIT_NAME'] ?>
                <input type="hidden" name="class_unit[]" value="<?= $row1['CLASS_UNIT'] ?>">
            </td>
            <td>
                <select id="dp_award_delay_decision_<?= $row1['CLASS_ID'] ?>"
                        name="award_delay_decision[<?= $row1['CLASS_ID'] ?>]"
                        class="form-control col-sm-1 award_decision" onchange="approved_total(this);">

                    <?php if ($row1['AWARD_DELAY_DECISION'] != 3) { ?>
                        <option <?= ($row1['AWARD_DELAY_DECISION'] == 1) ? 'selected' : '' ?> value="1">مقبول
                        </option>
                        <option <?= ($row1['AWARD_DELAY_DECISION'] == 2) ? 'selected' : '' ?> value="2">مؤجل
                        </option>
                        <option <?= ($row1['AWARD_DELAY_DECISION'] == 0) ? 'selected' : '' ?> value="0">ملغي
                        </option>
                    <?php } else { ?>
                        <option value="3">تم تنفيذ التأجيل</option>
                    <?php } ?>  </select>
            </td>
            <td>
                <input type="hidden" name="purchase_amount[]" value="<?= $row1['APPROVED'] ?>"
                       id="purchase_amount_<?= $count1 ?>" data-val-required="حقل مطلوب"
                       data-val-regex="المدخل غير صحيح!" class="form-control p_amount">
                <?= $row1['APPROVED'] ?>
            </td>
            <?php
            if ($row1['AWARD_DELAY_DECISION'] == 0 || $row1['AWARD_DELAY_DECISION'] == 2) {
                $xhide_dsp = '';
                $table_dsp = 'none';
            } else {
                $xhide_dsp = 'none';
                $table_dsp = '';
            }
            ?>
            <td style="display: <?= $xhide_dsp ?>" id="xhide_table_<?= $row1['SER'] ?>">
                    <textarea rows="2" name="award_delay_decision_hint[<?= $row1['CLASS_ID'] ?>]"
                              id="award_delay_decision_hint_<?= $row1['CLASS_ID'] ?>" class="form-control"
                              placeholder="ملاحظة الالغاء"
                              style="width: 169px; height: 45px;"
                              onchange="approved_total(this);"><?= $row1['AWARD_DELAY_DECISION_HINT'] ?></textarea>

            </td>
            <td id="td_tb_<?= $row1['SER'] ?>" style="display: <?= $table_dsp ?>">
            <table style="display: <?= $table_dsp ?>" class="table table-bordered details-table" id="table_<?= $row1['SER'] ?>">
                    <thead>
                    <tr>
                        <th style="width:1%">#</th>
                        <th style="width:30%">الشركة</th>
                        <th style="width: 5% ">الكمية</th>
                        <th style="width: 5%">السعر</th>
                        <th style="width: 5%">إجمالي السعر</th>
                        <th style="width: 5%"> كمية الترسية</th>
                        <th style="width:  5%">نسبة الخصم</th>
                        <th style="width: 5%">قيمة الخصم للصنف</th>
                        <th style="width: 5%">قيمة الخصم الكلية</th>
                        <th style="width: 5%">سعر الترسية</th>
                        <th style="width: 5%">إجمالي سعر الترسية</th>
                        <th style="width: 15%">ملاحظات على الصنف</th>
                        <th style="width: 1%">اجراءات</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr data-class-ids="<?= $row1['CLASS_ID'] ?>" data-sup-ids="<?= $row['SUPPLIERS_OFFERS_ID'] ?>">
                        <td>
                            <input type="hidden" name="h_ser_[]" data-val="true" id="h_ser_<?= $count1 ?>"
                                   value="<?= $row1['CLASS_ID'] ?>" class="form-control col-sm-2">
                        </td>
                    </tr>
                        <?php $suppliers_details_retx = modules::run("$MODULE_NAME/$TB_NAME/public_get_award_detailsx", $purchase_order_id, $row1['CLASS_ID']); ?>
                        <?php foreach ($suppliers_details_retx as $row2) :
                        $sum_cust[$row2['SUPPLIERS_OFFERS_ID']] = 0;
                        $asum_cust[$row2['SUPPLIERS_OFFERS_ID']] = 0;
                        ?>
                    <tr>
                        <td>
                            <?= $countDet ?> <input type="hidden" name="classid" data-type="classid"
                                                    value="<?= $row2['CLASS_ID'] ?>"
                                                    data-val="<?= $row2['SUPPLIERS_OFFERS_ID'] ?>">
                            <input type="hidden" name="h_class_id[]" id="h_class_id_'<?= $countDet ?>>"
                                   class="form-control col-sm-2">
                        </td>
                        <td>
                            <input type="hidden" name="c_array[]" value="<?= $row2['SUPPLIERS_OFFERS_ID'] ?>">
                            <input type="hidden" name="ser" data-type="ser" value="<?= $row2['SER'] ?>"
                                   data-val="<?= $row2['SUPPLIERS_OFFERS_ID'] ?>">
                            <?= $row2['SUPPLIERS_OFFERS_ID'] ?> -
                            <?= $row2['CUST_NAME'] ?>

                        </td>
                        <td data-type="amount"
                            id="amount_<?= $row2['SUPPLIERS_OFFERS_ID'] ?>_<?= $row2['CLASS_ID'] ?>"
                            style="background:<?= class_name($cust_amount[$row2['SUPPLIERS_OFFERS_ID']][$row2['CLASS_ID']], $row1['APPROVED']) ?>"
                            class="amountxx">
                            <?= $cust_amount[$row2['SUPPLIERS_OFFERS_ID']][$row2['CLASS_ID']] ?>
                        </td>

                        <td class="price" data-type="price">
                            <?= $cust_price[$row2['SUPPLIERS_OFFERS_ID']][$row2['CLASS_ID']] ?>
                        </td>
                        <td class="totalP">
                            <?php $val = $cust_price[$row2['SUPPLIERS_OFFERS_ID']][$row2['CLASS_ID']] * $cust_amount[$row2['SUPPLIERS_OFFERS_ID']][$row2['CLASS_ID']];
                            echo $val;
                            ?>
                        </td>
                        <td>
                            <input data-type="approved-amount" onkeyup="Amount(this)"
                                   data-val="<?= $row2['SUPPLIERS_OFFERS_ID'] ?>"
                                   type="text"
                                   max="<?= $cust_amount[$row2['SUPPLIERS_OFFERS_ID']][$row2['CLASS_ID']] ?>"
                                   name="approved_amount[<?= $row2['SUPPLIERS_OFFERS_ID'] ?>][<?= $row2['CLASS_ID'] ?>]"
                                   id="approved_amount" data-class1="<?= $row2['CLASS_ID'] ?>"
                                   value="<?= $row2['APPROVED_AMOUNT'] ?>"
                                   class="form-control balance">
                        </td>
                        <td>
                            <input data-type="discount-percent"
                                   data-val="<?= $row2['SUPPLIERS_OFFERS_ID'] ?>"
                                   type="text" min="0" max="100"
                                   name="class_discount[<?= $row2['SUPPLIERS_OFFERS_ID'] ?>][<?= $row2['CLASS_ID'] ?>]"
                                   id="class_discount"
                                   value="<?= $cust_class_discount[$row2['SUPPLIERS_OFFERS_ID']][$row2['CLASS_ID']] ?>"
                                   class="form-control percent-input">
                        </td>
                        <td>
                            <input data-type="discountclass"
                                   data-val="<?= $row2['SUPPLIERS_OFFERS_ID'] ?>" type="text"
                                   min="0"
                                   value="<?= $cust_discount_value_class[$row2['SUPPLIERS_OFFERS_ID']][$row2['CLASS_ID']] ?>"
                                   class="form-control">
                        </td>
                        <td>
                            <input data-type="discount-value" readonly
                                   data-val="<?= $row2['SUPPLIERS_OFFERS_ID'] ?>"
                                   type="text" min="0"
                                   name="c_discount_value[<?= $row2['SUPPLIERS_OFFERS_ID'] ?>][<?= $row2['CLASS_ID'] ?>]"
                                   id="c_discount_value"
                                   value="<?= $cust_class_discount_value[$row2['SUPPLIERS_OFFERS_ID']][$row2['CLASS_ID']] ?>"
                                   class="form-control">
                        </td>
                        <td>
                            <input readonly data-type="approved-price"
                                   data-val="<?= $row2['SUPPLIERS_OFFERS_ID'] ?>"
                                   type="text"
                                   name="approved_price[<?= $row2['SUPPLIERS_OFFERS_ID'] ?>][<?= $row2['CLASS_ID'] ?>]"
                                   id="approved_price"
                                   value="<?= $cust_approved_price[$row2['SUPPLIERS_OFFERS_ID']][$row2['CLASS_ID']] ?>"
                                   class="form-control">
                            <input type="hidden" name="purchase_amount[]" value="<?= $row1['APPROVED'] ?>"
                                   id="purchase_amount_<?= $count1 ?>" data-val-required="حقل مطلوب"
                                   data-val-regex="المدخل غير صحيح!" class="form-control p_amount">

                        </td>
                        <td data-type="approved-total" id="approved_total"
                            data-val="<?= $row2['SUPPLIERS_OFFERS_ID'] ?>" data-class="<?= $row2['CLASS_ID'] ?>"
                            class="total_">
                            <?php $aval = $cust_approved_price[$row2['SUPPLIERS_OFFERS_ID']][$row2['CLASS_ID']] * $cust_amount[$row2['SUPPLIERS_OFFERS_ID']][$row2['CLASS_ID']] - ($cust_approved_price[$row2['SUPPLIERS_OFFERS_ID']][$row2['CLASS_ID']] * $cust_amount[$row2['SUPPLIERS_OFFERS_ID']][$row2['CLASS_ID']] * $cust_class_discount[$row2['SUPPLIERS_OFFERS_ID']][$row2['CLASS_ID']] / 100);
                            echo $aval;
                            ?>
                        </td>
                        <td>
                        <textarea rows="2"
                                  name="award_hints[<?= $row2['SUPPLIERS_OFFERS_ID'] ?>][<?= $row1['CLASS_ID'] ?>]"
                                  data-type="award-hints"
                                  id="award_hints" data-val="<?= $row2['SUPPLIERS_OFFERS_ID'] ?>"
                                  class="form-control" data-val="true"
                                  style="width: 169px; height: 45px;"><?= $row2['AWARD_HINTS'] ?></textarea>
                        </td>
                        <td>
                            <a data-val="<?= $row2['SUPPLIERS_OFFERS_ID'] ?>" href="javascript:;"
                               onclick="addDetail(this)"><span class="glyphicon glyphicon-plus"></span>  </a>
                        </td>
                        <?php
                        $countDet++;
                        ?>
                    </tr>
                    <?php endforeach; ?>
                    <tfoot>
                    <tr>
                        <input type="hidden" name="count_<?= $row1['SER'] ?>" id="count_<?= $row1['SER'] ?>" value="0">
                        <td>
                            <a href="javascript:;" onclick="addRow('<?= $row1['SER'] ?>','<?= $row1['CLASS_ID'] ?>')">
                                <i class="glyphicon glyphicon-plus"></i>
                                جديد
                            </a>
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="colsum">-</td>
                        <td class="amountapproved_<?= $row1['CLASS_ID'] ?>">-</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="totalApprove_">-</td>
                        <td></td>
                        <td></td>

                    </tr>
                    </tfoot>
                </table>
            </td>
        </tr>
        </tbody>
        <?php endforeach; ?>
    </table>
</div>

