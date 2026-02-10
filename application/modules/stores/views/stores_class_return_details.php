<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 05/01/15
 * Time: 01:48 م
 */

$details= $return_details;
$count = 0;
$total = 0;

$class_codes_url = base_url("pledges/class_codes/index");

function select_codes_ser($codes,$selected=''){
    //codes text convert to array
    $codes= explode(",",$codes);
    $selected= explode(",",$selected);
    //remove duplicate
    $codes = array_diff($codes, $selected);

    $select= '<select multiple id="dp_codes_ser" class="form-control codes_ser" style="width: 80%" > ';
    foreach($selected as $val){
        if($val!='')    $select.= '<option value="'.$val.'" selected >'.$val.'</option>';
    }
    foreach($codes as $val){
        if($val!='')    $select.= '<option value="'.$val.'">'.$val.'</option>';
    }
    $select.= '</select>';

    return $select;
}

/*
function cnt_barcode($codes){
    if(trim($codes)==null or trim($codes)=='')
        return 0;
    else{
        $codes= explode(",",$codes);
        return count($codes);
    }
}
*/

?>

<div class="tb_container">
    <table class="table" id="details_tb" data-container="container">
        <thead>
        <tr>
            <th style="width: 2%">#</th>
            <th style="width: 10%">رقم الصنف</th>
            <th style="width: 30%">اسم الصنف</th>
            <th style="width: 11%">الكمية المرجعة</th>
            <th style="width: 11%">الوحدة</th>
            <th style="width: 15%">باركود العهد</th>
            <th style="width: 11%">حالة الصنف</th>
            <th style="width: 11%">السعر</th>
            <th style="width: 11%">الاجمالي</th>
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
                    <input type="hidden" name="personal_custody_type[]" readonly class="form-control" id="txt_personal_custody_type<?=$count?>" />
                </td>
                <td>
                    <input name="amount[]" class="form-control" id="txt_amount<?=$count?>" />
                </td>
                <td>
                    <select name="class_unit[]" class="form-control" id="unit_txt_class_id<?=$count?>" /><option></option></select>
                </td>


                <td><?//=($row['CLASS_ACOUNT_TYPE']==1)?select_codes_ser($row['CLASS_CODES_SERS']):''?> <input type="hidden" name="class_code_ser[]" value="" /> <input type="hidden" id="h_class_acount_type" value="<?//=$row['CLASS_ACOUNT_TYPE']?>" /> <input type="hidden" id="h_class_unit" value="<?//=$row['CLASS_UNIT']?>" /> </td>


                <td>
                    <select name="class_type[]" data-val="1" class="form-control" id="txt_class_type<?=$count?>" /><option></option></select>
                </td>
                <td></td>
                <td></td>
            </tr>

        <?php
        }else
            $count = -1;
        ?>

        <?php
        foreach($details as $row) {
            $count++;
            $total+=$row['AMOUNT']*$row['PRICE_USED'];
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
                    <input type="hidden" name="personal_custody_type[]" value="<?=$row['PERSONAL_CUSTODY_TYPE']?>"  class="form-control" id="txt_personal_custody_type<?=$count?>" />
                </td>

                <td>
                    <input name="amount[]" class="form-control" id="txt_amount<?=$count?>" value="<?=$row['AMOUNT']?>" />
                </td>

                <td>
                    <select name="class_unit[]" class="form-control" id="unit_txt_class_id<?=$count?>" data-val="<?=$row['CLASS_UNIT']?>" /><option></option></select>
                </td>

                <td title="old:<?=$row['CLASS_CODE_SER']?>" ><?=($show_code_sers)?select_codes_ser($row['CLASS_CODE_SERS'],$row['STORES_BARCODES']) . '<a class="class_codes_url" target="_blank" style="display: none;cursor: pointer" href="'.$class_codes_url.'/0/url_store/'.$row['CLASS_ID'].'" ><i class="glyphicon glyphicon-print"></i></a>' :''?> <input type="hidden" name="class_code_ser[]" value="<?=$row['STORES_BARCODES']?>" /> <input type="hidden" id="h_class_acount_type" value="<?=$row['CLASS_ACOUNT_TYPE']?>" /> <input type="hidden" id="h_class_unit" value="<?=$row['CLASS_UNIT']?>" /> </td>

                <td>
                    <select name="class_type[]" class="form-control" id="txt_class_type<?=$count?>" data-val="<?=$row['CLASS_TYPE']?>" /><option></option></select>
                </td>
                <td><?=$row['PRICE_USED']?></td>
                <td><?=number_format($row['AMOUNT']*$row['PRICE_USED'],2)?></td>

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
            <th></th>
            <th><?=number_format($total,2)?></th>
        </tr>
        </tfoot>
    </table>
</div>
