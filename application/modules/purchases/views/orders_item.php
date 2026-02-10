<?php
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 08/09/15
 * Time: 10:23 ص
 */
$count = 0;
?>

<div class="tbl_container">
    <table class="table" id="orders_detailTbl" data-container="container">
        <thead>
        <tr>
            <th style="width: 120px"   > #</th>
            <th style="width: 450px"   >  اسم البند</th>
            <th style="width: 200px"  >الوحدة</th>
            <th style="width: 200px"  >كمية طلب الشراء</th>
            <th style="width: 100px"  >كمية الترسية   </th>
            <th style="width: 100px"  > السعر من المورد </th>
            <th style="width: 100px"  >   إجمالي سعر المورد </th>
            <th  style="width: 100px"  > السعر الموحد</th>
            <th  style="width: 100px"  > إجمالي السعر الموحد </th>
            <th >تاريخ التوريد</th>
            <th style="width: 450px"  >ملاحظات</th>
        </tr>
        </thead>
        <tbody>

        <?php  if(count($rec_details) <= 0) : ?>
            <tr>

                <td >
                    <? //=($count+1)?>      <i class="glyphicon glyphicon-sort" /></i>

                    <input type="hidden" name="h_ser[]" id="h_ser_<?= $count ?>" value="0"  class="form-control">


                </td>

                <td>

                    <!--  <input name="class_id[]" data-val="true"  id="class_id_<?= $count ?>" readonly class="form-control col-sm-16">-->

                </td>
                <td>
                    <input type="hidden" data-val="true" name="unit_class_id[]" class="form-control" id="unit_class_id_<?=$count?>"  class="form-control col-sm-2" />
                    <!--  <input readonly data-val="true" name="unit_name_class_id[]" class="form-control" id="unit_name_class_id_<?=$count?>"  class="form-control col-sm-2" /> -->
                </td>
                <td>
                </td>
                <td ><!--<input name="approved_amount[]"   id="approved_amount_<?= $count ?>" type="text" class="form-control" > --></td>

                <td><!--<input name="customer_price[]"    id="customer_price_<?= $count ?>"   data-val-required="حقل مطلوب"   data-val-regex="المدخل غير صحيح!"   class="form-control">-->

                </td>
                <td><!--<input readonly name="total_customer_price[]"    id="total_customer_price_<?= $count ?>"   data-val-required="حقل مطلوب"   data-val-regex="المدخل غير صحيح!"   class="form-control">-->

                </td>
                <td><!--<input name="price[]"     id="price_<?= $count ?>"   data-val-required="حقل مطلوب"   data-val-regex="المدخل غير صحيح!"   class="form-control">-->

                </td>
                <td><!--<input readonly name="total_price[]"    id="total_price_<?= $count ?>"   data-val-required="حقل مطلوب"   data-val-regex="المدخل غير صحيح!"   class="form-control">-->

                </td>
                <td ><!--<input name="note[]"   id="note_<?= $count ?>" type="text" class="form-control"> </td>-->



            </tr>
        <?php else: $count = -1; ?>
        <?php endif; ?>

        <?php foreach($rec_details as $row) :?>
            <?php $count++; ?>
            <tr>
                <td> <?=($count+1)?>
                    <input type="hidden" id="h_ser_<?= $count ?>" name="h_ser[]" value="<?= $row['SER'] ?>">
                    <input type="hidden" id="purchase_ser_<?= $count ?>" name="purchase_ser[]" value="<?= $row['PURCHASE_SER'] ?>">
                 </td>
               <td>
                   <input type="hidden" name="h_class_id[]" data-val="true"   id="h_class_id_<?= $count ?>"   value="<?= $row['ITEM'] ?>"  class="form-control col-sm-2">
                   <?= $row['CLASS_NAME'] ?> </td>
                <td>
                    <input type="hidden" data-val="true" name="unit_class_id[]" value="<?= $row['CLASS_UNIT']?>" class="form-control" id="unit_class_id_<?=$count?>"  class="form-control col-sm-2" />
                    <?= $row['CLASS_UNIT_NAME']?> </td>
                <td>
                    <input  name="amount[]" type="text" readonly
                            id="amount_<?= $count ?>"
                            value="<?= isset($row['PURCHASE_AMOUNT']) ? $row['PURCHASE_AMOUNT'] : $row['MOUNT'] ?>"
                            class="form-control">
                </td>
                <td >
                    <input  max="<?= $row['PURCHASE_AMOUNT'] ?>"  name="approved_amount[]" type="text" id="approved_amount_<?= $count ?>" value="<?= isset($row['APPROVED_AMOUNT'])? $row['APPROVED_AMOUNT'] : '' ?>" class="form-control">
                </td>
                <td><input name="customer_price[]"   id="customer_price_<?= $count ?>"      class="form-control" value="<?= isset($row['CUSTOMER_PRICE'])? $row['CUSTOMER_PRICE'] : '' ?>">

                </td>
                <td><input readonly name="total_customer_price[]"    id="total_customer_price_<?= $count ?>"   data-val-required="حقل مطلوب"   data-val-regex="المدخل غير صحيح!"   class="form-control">

                <td><input name="price[]"    id="price_<?= $count ?>"    class="form-control" value="<?= isset($row['ORDER_PRICE'])? $row['ORDER_PRICE']:'' ?>">

                </td>
                <td><input readonly name="total_price[]"    id="total_price_<?= $count ?>"   data-val-required="حقل مطلوب"   data-val-regex="المدخل غير صحيح!"   class="form-control">

                <td>
                    <input type="text" name="order_date[]"    id="order_date_<?= $count ?>"   value="<?= @$row['ORDER_DATE'] ?>"  class="form-control col-sm-2" data-type="date"  data-date-format="DD/MM/YYYY"  data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?= date_format_exp() ?>" data-val="false"  data-val-required="حقل مطلوب" >
                 <!--   <span class="field-validation-valid" data-valmsg-for="order_date[]" data-valmsg-replace="true"></span>
-->
                </td>
                <td >
                    <textarea rows="2" name="note[]" id="note_<?= $count ?>" class="form-control" ><?=$row['NOTE']?></textarea>

                    <!-- <input name="note[]"  type="text" id="note_<?= $count ?>" value="<?= $row['NOTE']?>" class="form-control">-->
                </td>

            </tr>
        <?php endforeach;?>

        </tbody>


        <tfoot>
        <!--  <tr>
            <th rowspan="1" class="align-right" colspan="10">
                <?php //if (count($rec_details) <=0 || ($action=='edit' )) ://if (count($chains_details) <=0 || ( isset($can_edit)?$can_edit:false)) : ?>
                    <a onclick="javascript:addRow();" onfocus="javascript:addRow();" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>
                <?php //endif; ?>
            </th>



        </tr> -->
        <tr>
            <th colspan="6">المجموع</th>
            <th id="inv_customer_total"></th>
            <th ></th>
            <th id="inv_total"></th>
            <th ></th>
            <th></th>
        </tr>    </tfoot>
    </table>

</div>


<script>
    if (typeof initFunctions == 'function') {
        initFunctions();
    }


</script>