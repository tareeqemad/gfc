<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 22/01/15
 * Time: 03:19 م
 */

$details= $adjustment_details;
$count = 0;
$total = 0;

?>

<div class="tb_container">
    <table class="table" id="details_tb" data-container="container">
        <thead>
        <tr>
            <th style="width: 2%">#</th>
            <th style="width: 10%">رقم الصنف</th>
            <th style="width: 35%">اسم الصنف</th>
            <th style="width: 11%">الكمية </th>
            <th style="width: 9%">الوحدة</th>
            <th style="width: 9%">حالة الصنف</th>
            <th style="width: 9%">السعر</th>
            <th style="width: 9%">الاجمالي </th>
        </tr>
        </thead>

        <tbody>
        <?php if(count($details) <= 0) { ?>
            <tr>
                <td><i class="glyphicon glyphicon-sort" /></i></td>
                <td>
                    <input type="hidden" name="ser[]" value="0" />
                    <input class="form-control" name="class[]" id="i_txt_class_id<?=$count?>" />
                    <input  type="hidden" name="class_id[]"  id="h_txt_class_id<?= $count ?>" >
                </td>
                <td>
                    <input name="class_name[]" readonly data-val="true" data-val-required="required" class="form-control"  id="txt_class_id<?=$count?>" />
                </td>
                <td>
                    <input name="amount[]" class="form-control" id="txt_amount<?=$count?>" />
                </td>
                <td>
                    <select name="class_unit[]" class="form-control" id="unit_txt_class_id<?=$count?>" /><option></option></select>
                </td>
                <td>
                    <select name="class_type[]" data-val="1" class="form-control" id="txt_class_type<?=$count?>" /><option></option></select>
                </td>
                <td>
                    <input name="price[]" class="form-control" id="txt_price<?=$count?>" />
                </td>
                <td></td>
            </tr>

        <?php
        }else
            $count = -1;
        ?>

        <?php
        foreach($details as $row) {
            $count++;
            $total+=$row['AMOUNT']*$row['PRICE'];
            ?>
            <tr>
                <td><?=$count+1?></td>
                <td>
                    <input type="hidden" name="ser[]" value="<?=$row['SER']?>" />
                    <input name="class[]" value="<?=$row['CLASS_ID']?>" class="form-control"  id="i_txt_class_id<?=$count?>" />
                    <input  type="hidden" name="class_id[]" value="<?=$row['CLASS_ID']?>" id="h_txt_class_id<?= $count ?>" >
                </td>

                <td>
                    <input name="class_name[]" readonly value="<?=$row['CLASS_NAME']?>" class="form-control"  id="txt_class_id<?=$count?>" />
                </td>

                <td>
                    <input name="amount[]" class="form-control" id="txt_amount<?=$count?>" value="<?=$row['AMOUNT']?>" />
                </td>

                <td>
                    <select name="class_unit[]" class="form-control" id="unit_txt_class_id<?=$count?>" data-val="<?=$row['CLASS_UNIT']?>" /><option></option></select>
                </td>

                <td>
                    <select name="class_type[]" class="form-control" id="txt_class_type<?=$count?>" data-val="<?=$row['CLASS_TYPE']?>" /><option></option></select>
                </td>

                <td>
                    <input name="price[]" class="form-control" id="txt_price<?=$count?>" value="<?=$row['PRICE']?>" />
                </td>
                <td><?=number_format($row['AMOUNT']*$row['PRICE'],2)?></td>

            </tr>
        <?php } ?>

        </tbody>
        <tfoot>
        <tr>
            <th></th>
            <th>
                <?php if (count($details) <=0 || ( (isset($can_edit)?$can_edit:false) and  $adopt==1 )) { ?>
                    <a onclick="javascript:addRow();" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>
                <?php } ?>
            </th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th><?=number_format($total,2)?></th>
        </tr>
        </tfoot>
    </table>
</div>
