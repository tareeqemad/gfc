<?php
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 20/10/15
 * Time: 02:03 م
 */


$count = 0;

?>

<div class="tbl_container">
    <table class="table" id="stores_class_input_detailTbl" data-container="container">
        <thead>
        <tr>
            <th   > #</th>
            <th style="width:150px "   >  رقم الصنف</th>
            <th  style="width: 500px">  اسم الصنف</th>
            <th style="width: 200px">الوحدة</th>
            <th style="width: 70px"  >حالة الصنف</th>
            <th  style="width: 100px"> الكمية </th>
            <th   style="width: 150px" > السعر </th>
            <th   style="width: 150px" > السعر المتوسط </th>
            <th >إجمالي (غ.ش.ض)</th>
        </tr>
        </thead>
        <tbody>


        <?php

        foreach($rec_details as $row) :?>
            <?php $count++; ?>
            <tr>
                <td> <?=($count)?>
                    <input type="hidden" id="h_ser_<?= $count ?>" name="h_ser[]" value="<?= $row['SER'] ?>">
                    <input type="hidden" name="donation_file_ser[]" id="h_donation_file_ser_<?= $count ?>" value="<?= $row['SER'] ?>" >
                    <input type="hidden" name="h_class_price_no_vat[]" id="h_class_price_no_vat_<?= $count ?>" value="<?= $row['CLASS_PRICE_NO_VAT'] ?>"  class="form-control col-sm-3">


                </td>
                <td>

                    <input  readonly type="text" name="h_class_id[]" data-val="true" value="<?= $row['CALSS_ID'] ?>"  data-val-required="حقل مطلوب"   id="h_class_id_<?= $count ?>"  class="form-control col-sm-2">

                </td>
                <td>

                    <input name="class_id[]"  value='<?=$row['DONATION_CLASS_ID_NAME'] ?>'  id="class_id_<?= $count ?>"  readonly class="form-control col-sm-16">
                </td>


                <td>
                    <select name="unit_class_id[]" class="form-control  col-sm-10" id="unit_class_id_<?=$count?>" data-val="<?=$row['CLASS_UNIT']?>" >
                    <option> </option>
                   <?php foreach($class_unit as $row1) :?>
                       <option value="<?=$row1['CON_NO']?>" <?php if($row1['CON_NO']==$row['CLASS_UNIT']) echo "selected";?>><?=$row1['CON_NAME']?> </option>
                   <?php endforeach;?>
                    </select>

                </td>
                <td id="class_type_class_id_<?=$count?>"><?= $row['CLASS_TYPE_NAME'] ?></td>
                <td><input onchange="calcTotal();" min="0"   name="amount[]"  data-val="true"  id="amount_<?= $count ?>"    data-val-required="حقل مطلوب"   data-val-regex="المدخل غير صحيح!"   class="form-control" value="0" max="<?= $row['REM_AMOUNT'] ?>" ondblclick="alert('<?= $row['REM_AMOUNT'] ?>'); return false ;">
                    <input name="price_class_id[]" type="hidden"  data-val="false"  id="price_class_id_<?= $count ?>"    data-val-required="حقل مطلوب"   data-val-regex="المدخل غير صحيح!"   class="form-control" value="<?= $row['CLASS_PRICE'] ?>">
                    <input name="taxable_det[]" type="hidden"  data-val="false"  id="taxable_det_<?= $count ?>"    data-val-required="حقل مطلوب"   data-val-regex="المدخل غير صحيح!"   class="form-control" value="<?= $row['TAXABLE_DET'] ?>">

                </td>
                <td> <input name="price[]"  readonly  type="number"  id="price_<?= $count ?>"  class="form-control" value="<?= $row['CLASS_PRICE_NO_VAT'] ?>">

                </td>
                <td> <input name="avg_price[]"  readonly  type="number"  id="avg_price_<?= $count ?>"  class="form-control" value="0">

                </td>

                <td ><input name="total[]"   type="number"  id="total_<?= $count ?>"  class="form-control" value="0">
                </td>
                <td> </td>
            </tr>
        <?php endforeach;?>

        </tbody>
        <tfoot>
        <tr>
        <tr>
            <th colspan="8">إجمالي (غ.ش.ض)</th>
            <th id="inv_without_tax"></th>

        </tr>
        <tr>
            <th colspan="8">قيمة الضريبة</th>
            <th id="inv_tax"></th>

        </tr>
        <tr>
            <th colspan="8">المجموع</th>
            <th id="inv_total"></th>

        </tr>

        </tr>

        </tfoot>
    </table>

</div>


<script>
    if (typeof initFunctions == 'function') {
        initFunctions();
    }


</script>