<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 02/02/17
 * Time: 12:39 م
 */
$count = 0;
$delete_details_url=base_url('stores/receipt_class_input_detail/delete');

?>

<div class="tbl_container">
    <table class="table" id="receipt_class_input_detailTbl" data-container="container">
        <thead>
        <tr>
            <th style="width: 120px"   > #</th>
            <th style="width:150px "   >  رقم الصنف</th>
            <th style="width: 450px"   >  اسم الصنف</th>
            <th style="width: 200px"  >الوحدة</th>
            <th style="width: 70px"  >حالة الصنف</th>
            <th style="width: 100px"  >كمية أمر التوريد</th>
            <th style="width: 100px"  > الكمية المستلمة </th>
            <th style="width: 100px"  >الكمية المتبقية</th>
            <th  style="width: 100px"  > الكمية المطابقة للمواصفات</th>
            <th style="width: 100px"  >الكمية المرفوضة  </th>


            <th ></th>
        </tr>
        </thead>
        <tbody>


        <?php foreach($rec_details as $row) :?>
            <?php $count++; ?>
            <tr>
                <td> <?=($count)?>
                    <input type="hidden" id="h_ser_<?= $count ?>" name="h_ser[]" value="0">
                    <input type="hidden" name="donation_file_ser[]" id="h_donation_file_ser_<?= $count ?>" value="0" >


                </td>
                <td>
                    <input type="text" name="h_class_id[]"  readonly   id="h_class_id_<?= $count ?>"   value="<?= $row['CLASS_ID'] ?>"  class="form-control col-sm-2">
                </td>
                <td>
                    <input name="class_id[]"  value='<?= $row['CLASS_ID_NAME'] ?>'  id="class_id_<?= $count ?>"  readonly class="form-control col-sm-16">
                </td>
                <td>
                    <select name="unit_class_id[]" class="form-control  col-sm-10" id="unit_class_id_<?=$count?>"  >
                        <option></option>
                        <?php foreach($class_unit as $row1) :?>
                            <option value="<?=$row1['CON_NO']?>" <?php if($row1['CON_NO']==$row['CLASS_UNIT']) echo "selected";?>><?=$row1['CON_NAME']?> </option>
                        <?php endforeach;?>
                    </select>

                </td>
                <td id="class_type_class_id_<?=$count?>">جديد</td>
                <td>
                    <input name="approved_amount_order[]"  data-val="true"  id="approved_amount_order_<?= $count ?>"    value="<?= $row['APPROVED_AMOUNT'] ?>" class="form-control col-sm-16" readonly >

                </td>
                <td><input name="amount[]"  data-val="true"  id="amount_<?= $count ?>"    data-val-required="حقل مطلوب"   data-val-regex="المدخل غير صحيح!"   class="form-control" value="<?= $row['ORDER_AMOUNT'] ?>" max="<?= $row['ORDER_AMOUNT'] ?>" >

                </td>
                <td>

                    <input name="remainder[]"  data-val="true"  id="remainder_<?= $count ?>"    value="<?= $row['APPROVED_AMOUNT']-$row['ORDER_AMOUNT'] ?>" class="form-control col-sm-16" readonly >

                </td>
                <td >
                    <input readonly name="approved_amount[]" type="text" id="approved_amount_<?= $count ?>" value="" class="form-control">
                </td>
                <td >
                    <input name="refused_amount[]" readonly type="text" id="refused_amount_<?= $count ?>" value="" class="form-control">
                </td>

                <td>

                </td>
            </tr>
        <?php endforeach;?>

        </tbody>

    </table>

</div>


<script>
    if (typeof initFunctions == 'function') {
        initFunctions();
    }
    reBind();
    function reBind() {
        $('input[name="amount[]"]').change(function () {
            var approved_amount_order = $(this).closest('tr').find('input[name="approved_amount_order[]"]').val();
            var amount = $(this).closest('tr').find('input[name="amount[]"]').val();
            $(this).closest('tr').find('input[name="remainder[]"]').val(Number(approved_amount_order)-Number(amount));
        });
    }


</script>