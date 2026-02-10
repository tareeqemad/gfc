<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 04/01/15
 * Time: 12:50 م
 */
$count = 0;
$sum = 0;
?>
<?php if(count($rows)> 0)  : ?>
    <div class="tbl_container">
        <div style="margin: 10px;"><a href="javascript:;" onclick="javascript:addToInvoice();" class="btn btn-success"><i class="icon icon-check"></i>إدراج للفاتورة</a> </div>
		        <input type="text" id="search-tbl" data-set="invoices_detailsTbl" class="form-control" placeholder="بحث">

        <table class="table" id="invoices_detailsTbl">
            <thead>
            <tr>
                <th style="width: 10px;"></th>

                <th style="width: 100px;" data-type="supplier"  > رقم الفاتورة </th>
                <th data-type="supplier"  style="width: 100px;"   >تاريخ الفاتورة</th>

                <th style="width: 100px;"  > المبلغ </th>
                <th style="width: 100px;">العملة</th>
                <th    > البيان </th>


            </tr>
            </thead>

            <tbody>


            <?php foreach($rows as $row) :?>
                <?php

                $count++;
                $sum += $row['TOTAL'] * $row['CURR_VALUE'];
                ?>
                <tr>
                    <td><input type="checkbox" name="isChecked[]" value="<?= $row['EXPENSE_BILL_ID'] ?>"></td>

                    <td data-type="supplier">
                        <input name="bill_number[]" readonly  class="form-control" data-val="true"  data-val-required="حقل مطلوب"    id="bill_number_<?= $count ?>" value="<?= $row['INVOICE_ID'] ?>">
                    </td>
                    <td data-type="supplier">
                        <input name="bill_date[]" readonly  class="form-control"  data-type="date"  data-val="true"  data-val-required="حقل مطلوب"     data-date-format="DD/MM/YYYY"   id="bill_date_<?= $count ?>" value="<?= $row['INVOICE_DATE'] ?>">

                    </td>
                    <td>
                        <input name="payment_value[]"  readonly class="form-control"   id="payment_value_<?= $count ?>" value="<?= $row['TOTAL'] ?>">
                    </td>
                    <td><?= $row['CURR_ID_NAME'] ?></td>
                    <td data-type="declaration"><?= $row['DECLARATION'] ?></td>

                </tr>

            <?php endforeach;?>

            </tbody>
            <tfoot>
            <th colspan="3">المجموع</th>
            <th><?= $sum ?></th>
            <th colspan="2"></th>
            </tfoot>

        </table>
        <div style="margin: 10px;"><a href="javascript:;" onclick="javascript:addToInvoice();" class="btn btn-success"><i class="icon icon-check"></i>إدراج للفاتورة</a> </div>

        <div></div>
    </div>

<?php endif; ?>
<script>
    if (typeof initFunctions == 'function') {
        initFunctions();
    }

    function addToInvoice(){

        $('input[name="isChecked[]"]:checked').each(function(i){

            var bill_number = $(this).closest('tr').find('input[name="bill_number[]"]').val();
            var bill_date = $(this).closest('tr').find('input[name="bill_date[]"]').val();
            var payment_value = $(this).closest('tr').find('input[name="payment_value[]"]').val();
            var declaration ='فاتورة رقم : ' +bill_number+ ' / '+ $(this).closest('tr').find('td[data-type="declaration"]').text();

            parent.AddInvoiceToList(bill_number,bill_date,payment_value,declaration);

        });

        parent.$('#report').modal('hide');

    }


</script>