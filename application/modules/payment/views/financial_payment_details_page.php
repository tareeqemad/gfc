<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 02/11/14
 * Time: 09:54 ص
 */

$count = 0;
$delete_details_url=base_url('payment/financial_payment/delete_details');

?>

<div class="tbl_container">
    <table class="table" id="chains_detailsTbl" data-container="container">
        <thead>
        <tr>
            <th id="payment_account_th"  >  حساب المصروف</th>
            <th style="width: 100px;" data-type="supplier"  > رقم الفاتورة </th>
            <th data-type="supplier"  style="width: 100px;"   >تاريخ الفاتورة</th>

            <th style="width: 100px;"  > المبلغ </th>
            <th   > البيان </th>
            <th >بالعملة الدفترية </th>
            <th   >الرصيد </th>


            <th ></th>
        </tr>
        </thead>

        <tbody>

        <?php if(count($payment_details) <= 0) : ?>
            <tr>
                <td>
                    <input type="hidden" name="SER[]" value="0">
                    <input type="number" name="payment_account_id[]"  id="h_account_<?= $count ?>"  class="form-control col-sm-3">

                    <input name="payment_account_id_name[]" readonly  class="form-control col-sm-8" readonly id="account_<?= $count ?>"  >

                </td>
                <td data-type="supplier">
                    <input name="bill_number[]" data-val="true"  data-val-required="حقل مطلوب"     class="form-control"  id="bill_number_<?= $count ?>" >
                </td>
                <td data-type="supplier">
                    <input name="bill_date[]"  data-val="true"  data-val-required="حقل مطلوب"    data-type="date"    data-date-format="DD/MM/YYYY"  class="form-control"  id="bill_date_<?= $count ?>" >


                </td>


                <td>
                    <input name="payment_value[]"   data-val-regex="المدخل غير صحيح!" data-val-regex-pattern="<?= float_format_exp() ?>"  class="form-control"  id="payment_value_<?= $count ?>" >


                </td>
                <td>
                    <input name="dt_hints[]" type="text" class="form-control"  id="dt_hints_<?= $count ?>"  >
                </td>
                <td class="v_balance"></td>
                <td class="balance"></td>
                <td></td>
            </tr>

        <?php else: $count = -1; ?>
        <?php endif; ?>

        <?php foreach($payment_details as $row) :?>
            <?php $count++; ?>
            <tr>
                <td>
                    <input type="hidden" name="SER[]" value="<?= $row['SER'] ?>">
                    <input type="number" name="payment_account_id[]" value="<?= $row['PAYMENT_ACCOUNT_ID'] ?>" id="h_account_<?= $count ?>"  class="form-control col-sm-3">

                    <input name="payment_account_id_name[]"   readonly    class="form-control col-sm-8" readonly id="account_<?= $count ?>" value="<?= $row['PAYMENT_ACCOUNT_ID_NAME'] ?>" title="<?= $row['PAYMENT_ACCOUNT_ID_NAME'] ?>">

                </td>
                <td data-type="supplier">
                    <input name="bill_number[]"   class="form-control" data-val="true"  data-val-required="حقل مطلوب"    id="bill_number_<?= $count ?>" value="<?= $row['BILL_NUMBER'] ?>">
                </td>
                <td data-type="supplier">
                    <input name="bill_date[]"   class="form-control"  data-type="date"  data-val="true"  data-val-required="حقل مطلوب"     data-date-format="DD/MM/YYYY"   id="bill_date_<?= $count ?>" value="<?= $row['BILL_DATE'] ?>">


                </td>

                <td>
                    <input name="payment_value[]"   class="form-control"   id="payment_value_<?= $count ?>" value="<?= $row['PAYMENT_VALUE'] ?>">


                </td>
                <td>
                    <input name="dt_hints[]" class="form-control"  value="<?= $row['HINTS'] ?>" type="text"  id="dt_hints_<?= $count ?>"  >
                </td>
                <td class="v_balance"></td>
                <td class="balance"><?= $row['BALANCE'] ?></td>
                <td><?php  if( HaveAccess($delete_details_url)):  ?>
                        <?php if ( ( isset($can_edit)?$can_edit:false)) : ?>
                            <a onclick="javascript:payment_detail_tb_delete(this,<?= $row['SER'] ?>,1);" href="javascript:;"><i class="icon icon-trash delete-action"></i> </a>
                        <?php endif; ?>
                    <?php endif; ?></td>
            </tr>

        <?php endforeach;?>

        </tbody>
        <tfoot>
        <tr>
            <th rowspan="1" class="align-right" colspan="3">
                <?php if (count($payment_details) <=0 || ( isset($can_edit)?$can_edit:false)) : ?>
                    <a onclick="javascript:addRow();" onfocus="javascript:addRow();" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>

                    <a onclick="javascript:addInvoice();"  data-type="supplier" onfocus="javascript:addInvoice();" href="javascript:;"><i class="glyphicon glyphicon-check"></i>إختر فاتورة</a>
                <?php endif; ?>
            </th>
            <th id="total"></th>
            <th rowspan="1" class="align-right" colspan="5"></th>




        </tr></tfoot>
    </table>
</div>
<div class="tbl_container">
    <table class="table" id="deduction_detailsTbl">
        <thead>
        <tr>
            <th>نوع الحساب</th>
            <th> حساب الإستقطاع</th>
            <th>نوع المستفيد</th>
            <th style="width: 100px;">مبلغ الإستقطاع</th>
            <!--    <th  style="width: 100px;" > نوع الاستقطاع</th>
                <th  style="width: 100px;" > التاريخ </th>-->
            <th>البيان</th>
            <th style="width: 80px;"></th>


        </tr>
        </thead>
        <tbody>

        <?php if (count($deduction) <= 0) : ?>

            <tr>
                <td>
                    <select name="deduction_account_type[]" id="dp_account_type_<?= $count ?>" class="form-control">
                        <?php foreach ($account_type as $row) : ?>
                            <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>

                </td>

                <td>
                    <input type="hidden" name="D_SER[]" value="0">
                    <input type="text" name="deduction_account_id[]" id="h_deduction_account_id_<?= $count ?>"
                           class="form-control col-sm-3">

                    <input name="deduction_account_id_name[]" data-balance="false" class="form-control col-sm-8"
                           readonly id="deduction_account_id_<?= $count ?>">

                </td>

                <td>
                    <select class="form-control" id="db_d_customer_account_type" name="d_customer_account_type[]">
                        <option></option>
                        <?php foreach ($ACCOUNT_TYPES as $row) : ?>
                            <option value="<?= $row['ACCCOUNT_TYPE'] ?>" style="display: none;"><?= $row['ACCCOUNT_NO_NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>

                <td><input name="deduction_value[]" data-val-regex="المدخل غير صحيح!"
                           data-val-regex-pattern="<?= float_format_exp() ?>" class="form-control"
                           id="deduction_value_<?= $count ?>">
                </td>

                <!--    <td>

                    <select name="deduction_type[]" id="dp_deduction_type_<? /*= $count */ ?>" class="form-control">
                        <?php /*foreach($deduction_type as $row) :*/ ?>
                            <option   value="<? /*= $row['CON_NO'] */ ?>"><? /*= $row['CON_NAME'] */ ?></option>
                        <?php /*endforeach; */ ?>
                    </select>
                </td>
                <td>
                    <input name="deduction_date[]"   data-type="date"  data-date-format="DD/MM/YYYY"  data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<? /*= date_format_exp() */ ?>"  class="form-control"  id="deduction_date_<? /*= $count */ ?>" >
                </td>-->
                <td><input name="d_hints[]" class="form-control" id="hints_<?= $count ?>">
                </td>
                <td data-action="delete"></td>

            </tr>
        <?php else: $count = -1; ?>
        <?php endif; ?>

        <?php foreach ($deduction as $row) : ?>
            <?php $count++; ?>

            <tr>
                <td>
                    <select name="deduction_account_type[]" id="dp_account_type_<?= $count ?>" class="form-control">
                        <?php foreach ($account_type as $_row) : ?>
                            <option   <?= $row['DEDUCTION_ACCOUNT_TYPE'] == $_row['CON_NO'] ? 'selected' : '' ?>
                                value="<?= $_row['CON_NO'] ?>"><?= $_row['CON_NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>

                </td>



                <td>
                    <input type="hidden" name="D_SER[]" value="<?= $row['SER'] ?>">

                    <input type="text" name="deduction_account_id[]" id="h_deduction_account_id_<?= $count ?>"
                           value="<?= $row['DEDUCTION_ACCOUNT_ID'] ?>" class="form-control col-sm-3">

                    <input name="deduction_account_id_name[]" data-balance="false" class="form-control col-sm-8"
                           readonly id="deduction_account_id_<?= $count ?>"
                           value="<?= $row['DEDUCTION_ACCOUNT_ID_NAME'] ?>"
                           title="<?= $row['DEDUCTION_ACCOUNT_ID_NAME'] ?>">


                </td>

                <td>
                    <select class="form-control" id="db_d_customer_account_type" name="d_customer_account_type[]">
                        <option></option>
                        <?php foreach ($ACCOUNT_TYPES as $_row) : ?>
                            <option
                                value="<?= $_row['ACCCOUNT_TYPE'] ?>"
                                <?= $_row['ACCCOUNT_TYPE'] == $row['CUSTOMER_ACCOUNT_TYPE'] ? 'selected' : '' ?>
                                style=" <?= $row['DEDUCTION_ACCOUNT_TYPE'] !=2 ? 'display:none' : '' ?>"
                                ><?= $_row['ACCCOUNT_NO_NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>

                <td><input name="deduction_value[]" class="form-control" id="deduction_value_<?= $count ?>"
                           value="<?= $row['DEDUCTION_VALUE'] ?>">
                </td>
                <!-- <td>

                    <select name="deduction_type[]" id="dp_deduction_type_<? /*= $count */ ?>" class="form-control">
                        <?php /*foreach($deduction_type as $_row) :*/ ?>
                            <option   <? /*= $row['DEDUCTION_TYPE'] ==$_row['CON_NO'] ? 'selected':''  */ ?>   value="<? /*= $_row['CON_NO'] */ ?>"><? /*= $_row['CON_NAME'] */ ?></option>
                        <?php /*endforeach; */ ?>
                    </select>
                </td>
                <td>
                    <input name="deduction_date[]" value="<? /*= $row['DEDUCTION_DATE'] */ ?>"  data-type="date"  data-date-format="DD/MM/YYYY"  data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<? /*= date_format_exp() */ ?>"   class="form-control"  id="deduction_date_<? /*= $count */ ?>" >
                </td>-->

                <td><input name="d_hints[]" class="form-control" id="hints_<?= $count ?>" value="<?= $row['HINTS'] ?>">
                </td>
                <td data-action="delete"><?php if (HaveAccess($delete_details_url)): ?>
                        <?php if ((isset($can_edit) ? $can_edit : false)) : ?>
                            <a onclick="javascript:payment_detail_tb_delete(this,<?= $row['SER'] ?>,2);"
                               href="javascript:;"><i class="icon icon-trash delete-action"></i> </a>
                        <?php endif; ?>
                    <?php endif; ?></td>

            </tr>
        <?php endforeach; ?>

        </tbody>
        <tfoot>
        <tr>
            <th rowspan="1" class="align-right" colspan="2">
                <?php if (count($payment_details) <= 0 || (isset($can_edit) ? $can_edit : false)) : ?>
                    <a onclick="javascript:add_row(this,'input',false);"
                       onfocus="javascript:add_row(this,'input',false);" href="javascript:;"><i
                            class="glyphicon glyphicon-plus"></i>جديد</a>
                    <!--<a onclick="javascript:addDedRow();" onfocus="javascript:addDedRow();" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>-->
                <?php endif; ?>
            </th>
            <th id="dtotal"></th>
            <th rowspan="1" class="align-right" colspan="4"></th>


        </tr>
        <tr>
            <th rowspan="1" class="align-left" colspan="2">
                الصافي للدفع
            </th>
            <th id="nettotal"></th>
            <th rowspan="1" class="align-right" colspan="4"></th>


        </tr>
        </tfoot>
    </table>
</div>


<script>
    if (typeof initFunctions == 'function') {
        initFunctions();
    }


</script>