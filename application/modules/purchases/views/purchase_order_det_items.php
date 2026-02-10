<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 26/08/15
 * Time: 01:50 م
 */

$count = 0;
$Total_items=0;
?>

<div class="tb_container">
    <table class="table civil_class_det" id="details_items_tb" data-container="container">
        <thead>
        <tr>
            <th style="width: 2%">#</th>
            <th style="width: 20%">البند</th>
            <th style="width: 6%">الوحدة</th>
            <th style="width: 7%">الكمية</th>
            <th style="width: 7%">سعر الوحدة المتوقع</th>
            <th style="width: 7%">سعر الوحدة</th>
            <th style="width: 7%">العملة</th>
            <th style="width: 7%">الاجمالي</th>
            <th style="width: 20%">ملاحظات</th>
        </tr>
        </thead>

        <tbody>
        <?php
        if (count($details) <= 0) {  // ادخال
        foreach ($show_civil_items as $row) {
            ?>
            <tr>
                <td><?=++$count?></td>

                <td>
                    <input type="hidden" name="service_ser[]" value="0" />
                    <input type="hidden" name="service_item_id[]" value="<?=$row['CLASS_ID']?>" />
                   <?=$row['CLASS_NAME']?>

                </td>

                <td>
                    <?=$row['CLASS_UNIT_NAME']?>
                </td>

                <td>
                    <input name="service_amount[]" data-val="true"  data-val-required="حقل مطلوب"  class="form-control text-center" id="txt_service_amount<?=$count?>"  onchange="javascript:calc('<?=$count?>')" />
                    <span class="field-validation-valid" data-valmsg-for="service_amount[]" data-valmsg-replace="true"></span>
                </td>
                <td>
                    <?=$row['PRICE_SUGGEST']?>
                </td>
                <td>
                    <input name="service_price[]" data-val="true"  data-val-required="حقل مطلوب"  class="form-control text-center" id="txt_service_price<?=$count?>"  onchange="javascript:calc('<?=$count?>')" />
                    <span class="field-validation-valid" data-valmsg-for="service_price[]" data-valmsg-replace="true"></span>
                </td>
                <td>
                    <?=$row['CURR_ID_NAME']?>
                </td>
                <td><input name="service_total[]" class="form-control text-center" id="txt_service_total<?=$count?>" value="0.00" readonly /></td>
                <td>
                    <input name="service_note[]" class="form-control" id="txt_service_note<?=$count?>"  />
                </td>

            </tr>
            <?php
        }
        }else if(count($details) > 0) { // تعديل
            foreach ($details as $row) {
            $Total_items+=$row['AMOUNT']*$row['PRICE'];
          ?>
                <tr>
                    <td><?=++$count?></td>

                    <td>
                        <input type="hidden" name="service_ser[]" value="<?=$row['SER']?>" />
                        <input type="hidden" name="service_item_id[]" value="<?=$row['ITEM']?>" />
                        <?=$row['CLASS_NAME']?>
                    </td>

                    <td>
                        <?=$row['CLASS_UNIT_NAME']?>
                    </td>

                    <td>
                        <input name="service_amount[]" data-val="true"  data-val-required="حقل مطلوب"  class="form-control" id="txt_service_amount<?=$count?>" value="<?=$row['AMOUNT']?>" onchange="javascript:calc('<?=$count?>')" />
                        <span class="field-validation-valid" data-valmsg-for="service_amount[]" data-valmsg-replace="true"></span>
                    </td>
                    <td>
                        <?=$row['EXPECTE_PRICE']?>
                    </td>
                    <td>
                        <input name="service_price[]" data-val="true"  data-val-required="حقل مطلوب"  class="form-control" id="txt_service_price<?=$count?>" value="<?=$row['PRICE']?>" onchange="javascript:calc('<?=$count?>')" />
                        <span class="field-validation-valid" data-valmsg-for="service_price[]" data-valmsg-replace="true"></span>
                    </td>
                    <td>
                        <?=$row['CURR_ID_NAME']?>
                    </td>
                    <td><input name="service_total[]" class="form-control text-center" id="txt_service_total<?=$count?>" value="<?=number_format($row['AMOUNT']*$row['PRICE'],2)?>" readonly /></td>
                    <td>
                        <input name="service_note[]" class="form-control" id="txt_service_note<?=$count?>" value="<?=$row['NOTE']?>" />
                    </td>

                </tr>
        <?php
        }
        }
        ?>
        </tbody>
        <tfoot>
        <tr>
            <th></th>
            <th>

            </th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th>الإجمالي</th>
            <th id="total_calc_items"><?=number_format($Total_items,2);?></th>
            <th></th>
        </tr>

        </tfoot>
    </table>
</div>

<script>
    function calc(cntr)
    {


        var service_total = 0;
        $('#txt_service_total'+cntr).val(isNaNVal(Number(Number($('#txt_service_amount'+cntr).val())*Number($('#txt_service_price'+cntr).val()))));
        $('input[name="service_total[]"]').each(function () {

            var service_amount = $(this).closest('tr').find('input[name="service_amount[]"]').val();
            var service_price = $(this).closest('tr').find('input[name="service_price[]"]').val();
            service_total += Number(service_amount)*Number(service_price);


            $('#total_calc_items').text(isNaNVal(Number(service_total)));
        });


    }

    // calc_total();
     /*function calc_total()
     {
         var service_total = document.getElementsByName("service_total[]");
         var total_all=0;

         for (var i = 0; i < service_total.length; i++)
             total_all=parseFloat(total_all)+parseFloat(service_total[i].value);

         document.getElementById('total_calc_items').textContent=total_all.toFixed(2);
     }*/
    /* function calc(cntr)
     {
         var service_amount=document.getElementById('txt_service_amount'+cntr).value;
         var service_price=document.getElementById('txt_service_price'+cntr).value;

         if(service_amount=='')
             service_amount=0;

         if(service_price=='')
             service_price=0;

         var service_total=(parseFloat(service_amount)*parseFloat(service_price)).toFixed(2);
         document.getElementById('txt_service_total'+cntr).value=service_total;
         calc_total();

     }*/


</script>