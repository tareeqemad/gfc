<?php
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 27/08/15
 * Time: 11:32 ص
 */


if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$count = 0; ?>

<div class="tbl_container">
    <table class="table" id="suppliers_offers_detTbl" data-container="container">
        <thead>
        <tr>
            <th style="width: 120px"   > #</th>
            <th style="width: 450px"   >  اسم البند</th>
            <th style="width: 200px"  >الوحدة</th>
            <th style="width: 200px"  >كمية طلب الشراء</th>
            <th style="width: 100px"  >الكمية من المورد</th>
            <th style="width: 100px"  > السعر من المورد </th>
            <th  style="width: 100px"  > السعر الموحد</th>
            <th  style="width: 100px"  > إجمالي السعر الموحد </th>
            <th style="width: 450px"  >ملاحظات على الصنف</th>

            <th ></th>
        </tr>
        </thead>
        <tbody>


        <?php foreach($rec_details as $row) :?>

            <tr>
                <td> <?=($count+1)?>
                    <input type="hidden" id="h_ser_<?= $count ?>" name="h_ser[]" value="0">


                </td>
                <td>
                    <input type="hidden" name="purchase_ser[]" data-val="true"   id="purchase_ser_<?= $count ?>"   value="<?= $row['SER'] ?>"  class="form-control col-sm-2">
                    <input type="hidden" name="h_class_id[]" data-val="true"   id="h_class_id_<?= $count ?>"   value="<?= $row['ITEM'] ?>"  class="form-control col-sm-2">

                    <?= $row['CLASS_NAME'] ?>
                </td>
                   <td>
                    <input type="hidden" data-val="true" name="unit_class_id[]" value="<?= $row['CLASS_UNIT']?>" class="form-control" id="unit_class_id_<?=$count?>"  class="form-control col-sm-2" />
                    <?= $row['CLASS_UNIT_NAME']?>
                </td>
                <td> <input type="hidden" name="purchase_amount[]"  disabled  value="<?= $row['APPROVED'] ?>" id="purchase_amount_<?= $count ?>"   data-val-required="حقل مطلوب"   data-val-regex="المدخل غير صحيح!"   class="form-control">
                    <?= $row['APPROVED'] ?>
                </td>
                <td>
                <input name="amount[]"  max="<?= $row['APPROVED'] ?>"   id="amount_<?= $count ?>" value="<?= $row['APPROVED'] ?>"  data-val-required="حقل مطلوب"   data-val-regex="المدخل غير صحيح!"   class="form-control">
                </td>

                </td>

                <td><input name="customer_price[]"   id="customer_price_<?= $count ?>"      class="form-control" value="0">

                </td>
                <td><input name="price[]"    id="price_<?= $count ?>"    class="form-control" value="0">

                </td>
                <td><input readonly name="total_price[]"    id="total_price_<?= $count ?>"    class="form-control" value="0">
                </td >
                <td >
                    <textarea rows="2" name="note[]" id="note_<?= $count ?>" class="form-control" ></textarea>

                    <!--   <input name="note[]"  type="text" id="note_<?= $count ?>" value="" class="form-control">-->
                </td>

                <td>

                </td>
            </tr>
            <?php $count++; ?>
        <?php endforeach;?>

        </tbody>
        <tfoot>
        <tr>
            <!--   <th rowspan="1" class="align-right" colspan="4">
                <?php //if (count($rec_details) <=0 || $action=='edit') ://if (count($chains_details) <=0 || ( isset($can_edit)?$can_edit:false)) : ?>
                   <a onclick="javascript:addRow();" onfocus="javascript:addRow();" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>
                <?php //endif; ?>
            </th>-->

            <th colspan="7">المجموع</th>
            <th id="inv_total"></th>
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