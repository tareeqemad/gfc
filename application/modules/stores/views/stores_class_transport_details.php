<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 21/12/14
 * Time: 08:35 ص
 */

$count = 0;
$details= $transport_details;
$dont_adopt= 0;
$total = 0;
$set_barcode=0;
$class_codes_url = base_url("pledges/class_codes/index");
$barcode_tlp1_url = base_url("greport/reports/public_stores_barcode_tlp1");

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

function cnt_barcode($codes){
    if(trim($codes)==null or trim($codes)=='')
        return 0;
    else{
        $codes= explode(",",$codes);
        return count($codes);
    }
}

?>

<div class="tb_container">

    <table class="table" id="details_tb" data-container="container">
        <thead>
        <tr>
            <th style="width: 8%">رقم الصنف</th>
            <th style="width: 17%">اسم الصنف</th>
            <th style="width: 10%">الكمية المطلوبة</th>
            <th style="width: 11%">الكمية المتبقية في المناقلة</th>
            <th style="width: 8%">الكمية المتاحة</th>
            <th style="width: 6%">الوحدة</th>
            <th style="width: 15%">باركود العهد</th>
            <th style="width: 6%">السعر</th>
            <th style="width: 6%">حالة الصنف</th>
            <th style="width: 7%">الاجمالي </th>
        </tr>
        </thead>
        <tbody>
        <?php if(count($details) <= 0 and $transport_type==1 and count($request_details) > 0) {
        foreach($request_details as $row) {
            if($row['LEFT_AMOUNT']>0) {
                if( $row['CLASS_ACOUNT_TYPE']==1 and $row['LEFT_AMOUNT'] > cnt_barcode($row['CLASS_CODES_SERS']) ){
                    $set_barcode= 1;
                }
        ?>
            <tr>
                <td> <?=$row['CLASS_ID']?>
                    <input type="hidden" name="ser[]" value="0" />
                    <input type="hidden" name="class_id[]" value="<?=$row['CLASS_ID']?>" />
                </td>

                <td><?=$row['CLASS_NAME']?></td>

                <td>
                    <input name="amount[]" class="form-control" max="<?=$row['LEFT_AMOUNT']?>" value="<?=$row['LEFT_AMOUNT']?>" />
                </td>

                <td></td>
                <td></td>
                <td><?=$row['CLASS_UNIT_NAME']?></td>
                <td><?=($row['CLASS_ACOUNT_TYPE']==1)?select_codes_ser($row['CLASS_CODES_SERS']):''?> <input type="hidden" name="class_code_ser[]" value="" /> <input type="hidden" id="h_class_acount_type" value="<?=$row['CLASS_ACOUNT_TYPE']?>" /> <input type="hidden" id="h_class_unit" value="<?=$row['CLASS_UNIT']?>" /> </td>
                <td><?=@$row['UPDATE_PRICE']?></td>
                <td>
                    <?=$row['CLASS_TYPE_NAME']?>
                    <input type="hidden" name="class_type[]" value="<?=$row['CLASS_TYPE']?>" />
                </td>
                <td></td>
            </tr>

        <?php
        }
        }

        }else if(count($details) <= 0 and $transport_type==2) {
        ?>
            <tr>
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

                <td></td>
                <td></td>
                <td>
                    <select name="class_unit[]" class="form-control" id="unit_txt_class_id<?=$count?>" /><option></option></select>
                </td>
                <td><?=''?></td>
                <td></td>
                <td>
                    <select name="class_type[]" data-val="1" class="form-control" id="txt_class_type<?=$count?>" /><option></option></select>
                </td>
                <td></td>
            </tr>
        <?php
        }else if($transport_type==1) {

        foreach($details as $row) {
            if($row['AMOUNT'] > $row['AVAILABLE_AMOUNT'] + $row['REQUEST_RESERVE_AMOUNT']){
                $dont_adopt= 1;
            }
            $total+=$row['AMOUNT']*$row['UPDATE_PRICE'];
            ?>
            <tr>
                <td> <?=$row['CLASS_ID']?>
                    <input type="hidden" name="ser[]" value="<?=$row['SER']?>" />
                    <input  type="hidden" name="class_id[]" value="<?=$row['CLASS_ID']?>" />
                </td>

                <td><?=$row['CLASS_NAME']?></td>

                <td>
                    <input name="amount[]" class="form-control" value="<?=$row['AMOUNT']?>" />
                </td>

                <td><?=$row['LEFT_AMOUNT']?></td>
                <td><?=$row['AVAILABLE_AMOUNT']+ $row['REQUEST_RESERVE_AMOUNT']?></td>
                <td><?=$row['CLASS_UNIT_NAME']?></td>
                <td title="old:<?=$row['CLASS_CODE_SER']?>" ><?=($row['CLASS_ACOUNT_TYPE']==1)?select_codes_ser($row['CLASS_CODE_SERS'],$row['STORES_BARCODES']) . '<a class="class_codes_url" target="_blank" style="cursor: pointer" href="'.$class_codes_url.'/0/url_store/'.$row['CLASS_ID'].'" ><i class="glyphicon glyphicon-print"></i></a>' . '<i title="طباعة الباركودات" class="glyphicon glyphicon-barcode" onclick="javascript:print_barcode_tlp1(\''.$row['STORES_BARCODES'].'\')"></i>' :''?> <input type="hidden" name="class_code_ser[]" value="<?=$row['STORES_BARCODES']?>" /> <input type="hidden" id="h_class_acount_type" value="<?=$row['CLASS_ACOUNT_TYPE']?>" /> <input type="hidden" id="h_class_unit" value="<?=$row['CLASS_UNIT']?>" /> </td>
                <td><?=$row['UPDATE_PRICE']?></td>
                <td>
                    <?=$row['CLASS_TYPE_NAME']?>
                    <input  type="hidden" name="class_type[]" value="<?=$row['CLASS_TYPE']?>" />
                </td>
                <td><?=number_format($row['AMOUNT']*$row['UPDATE_PRICE'],2)?></td>
            </tr>
        <?php
        }
        }else if($transport_type==2){
        $count = -1;
        foreach($details as $row) {
        $count++;
        if($row['AMOUNT'] > $row['AVAILABLE_AMOUNT']){
            $dont_adopt= 1;
        }
        $total+=$row['AMOUNT']*$row['UPDATE_PRICE'];
        ?>
            <tr>
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

                <td><?=$row['LEFT_AMOUNT']?></td>
                <td><?=$row['AVAILABLE_AMOUNT']?></td>
                <td>
                    <select name="class_unit[]" class="form-control" id="unit_txt_class_id<?=$count?>" data-val="<?=$row['CLASS_UNIT']?>" /><option></option></select>
                </td>
                <td><?=''?></td>
                <td><?=$row['UPDATE_PRICE']?></td>
                <td>
                    <select name="class_type[]" class="form-control" id="txt_class_type<?=$count?>" data-val="<?=$row['CLASS_TYPE']?>" /><option></option></select>
                </td>
                <td><?=number_format($row['AMOUNT']*$row['UPDATE_PRICE'],2)?></td>
            </tr>
        <?php
        }

        }

        ?>

        </tbody>

        <tfoot>
        <tr>
            <th>
                <?php if ( $transport_type==2 and (count($details) <=0 || ( (isset($can_edit)?$can_edit:false) and  $adopt==1 ))) { ?>
                    <a onclick="javascript:addRow();" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>
                <?php } ?>
            </th>
            <th></th>
            <th></th>
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

<script>
    var dont_adopt= <?=$dont_adopt?> ;
    var set_barcode= <?=$set_barcode?> ;

    function print_barcode_tlp1(barcodes) {
        barcodes= barcodes.replaceAll(',', '_:_');
        _showReport('<?=$barcode_tlp1_url?>/'+barcodes);
    }
</script>