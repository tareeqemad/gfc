<?php
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 14/12/14
 * Time: 09:34 ص
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
            <th  style="width:500px ">  اسم الصنف</th>
            <th  style="width:100px " >الوحدة</th>
            <th  style="width:100px "> الكمية </th>
            <th   style="width:100px "> سعر الصنف </th>
            <th   style="width:80px " >   الضريبة </th>
            <th  class="price v_balance" style="width:200px "  > إجمالي (غ.ش.ض)</th>
            <th ></th>
        </tr>
        </thead>
        <tbody>

        <?php  if(count($rec_details) <= 0) : ?>
            <tr>
                <td >
                    <i class="glyphicon glyphicon-sort" /></i>
                    <input type="hidden" name="h_ser[]" id="h_ser_<?= $count ?>" value="0"  class="form-control col-sm-3">


                </td>
                <td>

                    <input  type="text" name="h_class_id[]" data-val="true"  data-val-required="حقل مطلوب"   id="h_class_id_<?= $count ?>"  class="form-control col-sm-2">

                </td>
                <td>

                    <input name="class_id[]" data-val="true"  data-val-required="حقل مطلوب"   id="class_id_<?= $count ?>" readonly class="form-control col-sm-16">

                </td>
                <td>
                    <select name="unit_class_id[]" class="form-control" id="unit_class_id_<?=$count?>" /><option></option></select>


                </td>
                <td><input name="amount[]"  data-val="true"  id="amount_<?= $count ?>"   data-val-required="حقل مطلوب"   data-val-regex="المدخل غير صحيح!"   class="form-control"> </td>
                <td><input name="price_class_id[]"  data-val="false"  id="price_class_id_<?= $count ?>"    data-val-required="حقل مطلوب"   data-val-regex="المدخل غير صحيح!"   class="form-control"> </td>


                <td>
                    <div class="">
                        <select  name="taxable_det[]" id="taxable_det_<?= $count ?>"  data-curr="false"  class="form-control">

                            <option  value="1"> يخضع</option>
                            <option  value="2">لا يخضع </option>

                        </select>
                    </div>

                </td>
                <td class="price v_balance" ><input name="price[]" readonly  id="price_<?= $count ?>" type="text" class="form-control"> </td>

                <td></td>
            </tr>
        <?php else: $count = -1; ?>
        <?php endif; ?>

        <?php foreach($rec_details as $row) :?>
            <?php $count++; ?>
            <tr>
                <td> <?=($count+1)?>
                    <input type="hidden" id="h_ser_<?= $count ?>" name="h_ser[]" value="<?= $row['SER'] ?>">


                </td>
                <td>
                    <input type="text" name="h_class_id[]"    id="h_class_id_<?= $count ?>"   value="<?= $row['CLASS_ID'] ?>"  class="form-control col-sm-2">
                </td>
                <td>
                    <input name="class_id[]"  value='<?= $row['CLASS_ID_NAME'] ?>'  id="class_id_<?= $count ?>"  readonly class="form-control col-sm-16">
                         </td>


                <td>
                    <select name="unit_class_id[]" class="form-control  col-sm-10" id="unit_class_id_<?=$count?>" data-val="<?=$row['CLASS_UNIT']?>" /><option></option></select>

                </td>
                <td><input name="amount[]"  data-val="true"  id="amount_<?= $count ?>"    data-val-required="حقل مطلوب"   data-val-regex="المدخل غير صحيح!"   class="form-control" value="<?= $row['AMOUNT'] ?>"> </td>
                <td><input name="price_class_id[]"  data-val="false"  id="price_class_id_<?= $count ?>"    data-val-required="حقل مطلوب"   data-val-regex="المدخل غير صحيح!"   class="form-control" value="<?= $row['CLASS_PRICE'] ?>"> </td>

                <td>
                    <div class="">
                        <select  name="taxable_det[]" id="taxable_det_<?= $count ?>"  data-curr="false"  class="form-control">

                            <option    <?= $row['TAXABLE_DET']==1?'selected':'' ?>   value="1"> يخضع</option>
                            <option    <?= $row['TAXABLE_DET']==2?'selected':'' ?>  value="2">لا يخضع </option>

                        </select>
                    </div>

                </td>
                <td class="price v_balance"  style="width:100px"><input name="price[]"  readonly id="price_<?= $count ?>" type="text" class="form-control" value="<?= $row['PRICE'] ?>" > </td>



                <td><?php  if( HaveAccess($delete_details_url)):  ?>

                        <a onclick="javascript:stores_class_input_detail_tb_delete(this,<?= $row['SER'] ?>);" href="javascript:;"><i class="icon icon-trash delete-action"></i> </a>

                    <?php endif; ?></td>
            </tr>
        <?php endforeach;?>

        </tbody>
        <tfoot>
        <tr>
            <th rowspan="1" class="align-right" colspan="4">
                <?php if (count($rec_details) <=0 || $action=='edit') ://if (count($chains_details) <=0 || ( isset($can_edit)?$can_edit:false)) : ?>
                    <a onclick="javascript:addRow();" onfocus="javascript:addRow();" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>
                <?php endif; ?>
            </th>

            <th colspan="2">المجموع</th>
            <th id="inv_total"></th>
            <th></th>

        </tr>


        <tr>
            <th rowspan="4" class="align-right" colspan="4">

            </th>

            <th id="inv_discount_per" colspan="2">نسبة الخصم </th>
            <th id="inv_discount"></th>
            <th></th>

        </tr>
        <tr>


            <th colspan="2">المبلغ بعد الخصم </th>
            <th  id="inv_after_discount" ></th>
            <th></th>

        </tr>
        <tr>


            <th id="inv_tax_per" colspan="2"> نسبة الضريبة</th>
            <th id="inv_tax"></th>
            <th></th>

        </tr>

        <tr>


            <th colspan="2">مبلغ الفاتورة </th>
            <th id="inv_nettotal"></th>
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