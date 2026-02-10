<?php
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 10/08/15
 * Time: 11:55 ص
 */

$count = 0;
$delete_details_url=base_url('stores/stores_class_input_detail/delete');

if ($action=='index')
{$receipt_data= array();


    $vat_value=$vat_value_c;

} else if ($action=='edit'){

    $vat_value=$receipt_data['VAT_VALUE'];

}

?>

<div class="tbl_container">
    <table class="table" id="stores_class_input_detailTbl" data-container="container">
        <thead>
        <tr>
            <th   > #</th>
            <th style="width:150px "   >  رقم الصنف</th>
            <th  style="width: 500px">  اسم الصنف</th>
            <th style="width: 100px">الوحدة</th>
            <th style="width: 70px"  >حالة الصنف</th>
            <th  style="width: 100px"> الكمية </th>
            <th   style="width: 150px" > السعر </th>
            <th   style="width: 150px" > السعر المتوسط </th>
            <th   style="width:80px " >   الضريبة </th>
            <th >إجمالي (غ.ش.ض)</th>
        </tr>
        </thead>
        <tbody>

        <?php

        if(count($rec_details) <= 0) : ?>
            <tr>
                <td >
                    <i class="glyphicon glyphicon-sort" >
                    <input type="hidden" name="h_ser[]" id="h_ser_<?= $count ?>" value="0"  class="form-control col-sm-3">
                    <input type="hidden" name="donation_file_ser[]" id="h_donation_file_ser_<?= $count ?>" value="0"  class="form-control col-sm-3">
                       <input type="hidden" name="h_class_price_no_vat[]" id="h_class_price_no_vat_<?= $count ?>" value="0"  class="form-control col-sm-3">


                </td>
                <td>

                    <input  type="text" name="h_class_id[]" data-val="true"  data-val-required="حقل مطلوب"   id="h_class_id_<?= $count ?>"  class="form-control col-sm-2">

                </td>
                <td>

                    <input name="class_id[]" data-val="true"  data-val-required="حقل مطلوب"   id="class_id_<?= $count ?>" readonly class="form-control col-sm-16">

                </td>
                <td>
                    <select name="unit_class_id[]" class="form-control" id="unit_class_id_<?=$count?>" ><option></option></select>


                </td>
                <td id="class_type_class_id_<?=$count?>">جديد</td>
                <td><input name="amount[]"  data-val="true"  id="amount_<?= $count ?>"   data-val-required="حقل مطلوب"   data-val-regex="المدخل غير صحيح!"   class="form-control">
                    <input name="price_class_id[]" type="hidden"  data-val="false"  id="price_class_id_<?= $count ?>"    data-val-required="حقل مطلوب"   data-val-regex="المدخل غير صحيح!"   class="form-control">
                </td>
                <td>
                    <input name="price[]"  value="0"  type="number"  id="price_<?= $count ?>" class="form-control"> </td>
                <td>
                    <input name="avg_price[]"  value="0"  readonly type="number"  id="avg_price_<?= $count ?>" class="form-control"> </td>

                <td>
                    <div class="">
                        <select  name="taxable_det[]" id="taxable_det_<?= $count ?>"  data-curr="false"  class="form-control">

                            <!--  <option  value="1"> يخضع</option>-->
                            <option  value="2">لا يخضع </option>

                        </select>
                    </div>

                </td>


                <td ><input name="total[]"   type="number"  id="total_<?= $count ?>"  class="form-control" value="0">
                </td>
            </tr>
        <?php else: $count = -1; ?>
        <?php endif; ?>

        <?php foreach($rec_details as $row) :?>
            <?php $count++; ?>
            <tr>
                <td> <?=($count+1)?>
                    <input type="hidden" id="h_ser_<?= $count ?>" name="h_ser[]" value="<?= $row['SER'] ?>">
                    <input type="hidden" name="donation_file_ser[]" id="h_donation_file_ser_<?= $count ?>" value="<?= $row['DONATION_FILE_SER'] ?>"  class="form-control col-sm-3">
                    <input type="hidden" name="h_class_price_no_vat[]" id="h_class_price_no_vat_<?= $count ?>" value="<?= $row['CLASS_PRICE_NO_VAT'] ?>"  class="form-control col-sm-3">

                </td>
                <td>

                    <input  type="text" name="h_class_id[]" data-val="true" value="<?= $row['CLASS_ID'] ?>"  data-val-required="حقل مطلوب"   id="h_class_id_<?= $count ?>"  class="form-control col-sm-2">

                </td>
                <td>

                    <input name="class_id[]"  value='<?=$row['CLASS_ID_NAME'] ?>'  id="class_id_<?= $count ?>"  readonly class="form-control col-sm-16">
                </td>


                <td>
                    <select name="unit_class_id[]" class="form-control  col-sm-10" id="unit_class_id_<?=$count?>" data-val="<?=$row['CLASS_UNIT']?>" ><option></option></select>

                </td>
                <td id="class_type_class_id_<?=$count?>"><?= $row['CLASS_TYPE_NAME'] ?></td>
                <td><input name="amount[]"  data-val="true"  id="amount_<?= $count ?>"    data-val-required="حقل مطلوب"   data-val-regex="المدخل غير صحيح!"   class="form-control" value="<?= $row['AMOUNT'] ?>">
                    <input name="price_class_id[]" type="hidden"  data-val="false"  id="price_class_id_<?= $count ?>"    data-val-required="حقل مطلوب"   data-val-regex="المدخل غير صحيح!"   class="form-control" value="<?= $row['CLASS_PRICE'] ?>">
                </td>
                <td> <input name="price[]"   type="number"  id="price_<?= $count ?>"  class="form-control" value="<?= $row['PRICE'] ?>">

                </td>
                <td> <input name="avg_price[]"  readonly  type="number"  id="avg_price_<?= $count ?>"  class="form-control" value="<?= $row['AVG_PRICE'] ?>">

                </td>
                <td>
                    <div class="">
                        <select  name="taxable_det[]" id="taxable_det_<?= $count ?>"  data-curr="false"  class="form-control">

                            <option    <?= $row['TAXABLE_DET']==1?'selected':'' ?>   value="1"> يخضع</option>
                            <option    <?= $row['TAXABLE_DET']==2?'selected':'' ?>  value="2">لا يخضع </option>

                        </select>
                    </div>

                </td>
                <td ><input name="total[]"   type="number"  id="total_<?= $count ?>"  class="form-control" value="<?=($row['AMOUNT']*$row['PRICE']);  ?>">
                </td>
                <td><?php  if( HaveAccess($delete_details_url)):  ?>

                        <a onclick="javascript:stores_class_input_detail_tb_delete(this,<?= $row['SER'] ?>);" href="javascript:;"><i class="icon icon-trash delete-action"></i> </a>

                    <?php endif; ?></td>
            </tr>
        <?php endforeach;?>

        </tbody>
        <tfoot>
        <tr>
            <th colspan="9">إجمالي (غ.ش.ض)</th>
            <th id="inv_without_tax"></th>

        </tr>
        <tr>
            <th colspan="9">قيمة الضريبة</th>
            <th id="inv_tax"></th>

        </tr>
        <tr>
            <th colspan="9">المجموع</th>
            <th id="inv_total"></th>

        </tr>



        </tfoot>
    </table>

</div>


<script>
    if (typeof initFunctions == 'function') {
        initFunctions();
    }


</script>