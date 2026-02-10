<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 20/12/14
 * Time: 12:44 م
 */

$details= $request_details;

$count = 0;
$dont_adopt= 0;
global $balance_msg;
$balance_msg= '';

global $balance_req_msg;
$balance_req_msg= '';

function class_name($case_min,$case_req_min){
    if($case_min==0){
        return 'case_0';
    }elseif($case_req_min==0){
        return 'case_5';
    }else{
        return 'case_1';
    }
}

function balance_msg($balance_case, $class_name){
    global $balance_msg;
    if($balance_case==0){
        $balance_msg.= '- '.$class_name.'<br>';
    }
}

function balance_req_msg($balance_req_case, $class_name){
    global $balance_req_msg;
    if($balance_req_case==0){
        $balance_req_msg.= '- '.$class_name.'<br>';
    }
}

if(count($details) <= 0)
    $available_amount= false;
else
    $available_amount= true;

?>

<div class="tb_container">
    <table class="table" id="details_tb" data-container="container">
        <thead>
        <tr>
            <th style="width: 2%">#</th>
            <th style="width: 10%">رقم الصنف</th>
            <th style="width: 30%">اسم الصنف</th>
            <th style="width: 11%">الكمية المطلوبة</th>
            <th style="width: 11%">الوحدة</th>
            <th style="width: 12%">حالة الصنف</th>
            <?php if($available_amount) echo "<th style='width: 13%'>الكمية المتبقية في الطلب</th>"; ?>
            <?php if($available_amount) echo "<th style='width: 13%'>الكمية المتاحة في المخزن</th>"; ?>
        </tr>
        </thead>

        <tbody>
        <?php if(count($details) <= 0 and $request_input==2 and count($project_details) > 0) {
        foreach($project_details as $row) {
        if($row['LEFT_AMOUNT']>0 ) {
            $count++;
        ?>
            <tr>
                <td><?=$count?></td>

                <td> <?=$row['CLASS_ID']?>
                    <input type="hidden" name="ser[]" value="0" />
                    <input  type="hidden" name="class_id[]" value="<?=$row['CLASS_ID']?>" />
                </td>

                <td><?=$row['CLASS_ID_NAME']?></td>

                <td>
                    <input name="request_amount[]" class="form-control" max="<?=$row['LEFT_AMOUNT']?>" value="<?=$row['LEFT_AMOUNT']?>" />
                </td>

                <td><?=$row['UNIT_NAME']?></td>

                <td>
                    <?=$row['CLASS_TYPE_NAME']?>
                    <input type="hidden" name="class_type[]" value="<?=$row['CLASS_TYPE']?>" />
                </td>
            </tr>

        <?php
        }
        }

        }else if(count($details) <= 0 and $request_input==3 and count($project_details) > 0) {
            foreach($project_details as $row) {
                if($row['CUSTOMER_LEFT_AMOUNT']>0 ) {
                    $count++;
                    ?>
                    <tr>
                        <td><?=$count?></td>

                        <td> <?=$row['CLASS_ID']?>
                            <input type="hidden" name="ser[]" value="0" />
                            <input  type="hidden" name="class_id[]" value="<?=$row['CLASS_ID']?>" />
                        </td>

                        <td><?=$row['CLASS_ID_NAME']?></td>

                        <td>
                            <input name="request_amount[]" class="form-control" max="<?=$row['CUSTOMER_LEFT_AMOUNT']?>" value="<?=$row['CUSTOMER_LEFT_AMOUNT']?>" />
                        </td>

                        <td><?=$row['UNIT_NAME']?></td>

                        <td>
                            <?=$row['CLASS_TYPE_NAME']?>
                            <input type="hidden" name="class_type[]" value="<?=$row['CLASS_TYPE']?>" />
                        </td>
                    </tr>

                <?php
                }
            }

        }else if(count($details) <= 0 and ($request_input==1 or $request_input==4)) { ?>
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
                    <input name="request_amount[]" class="form-control" id="txt_request_amount<?=$count?>" />
                </td>
                <td>
                    <select name="class_unit[]" class="form-control" id="unit_txt_class_id<?=$count?>" ><option></option></select>
                </td>
                <td>
                    <select name="class_type[]" data-val="1" class="form-control" id="txt_class_type<?=$count?>" ><option></option></select>
                </td>
            </tr>

        <?php

        }else if($request_input==2 or $request_input==3) {
        foreach($details as $row) {
            if($row['REQUEST_AMOUNT'] > $row['AVAILABLE_AMOUNT']){
                $dont_adopt= 1;
            }
            balance_msg($row['BALANCE_CASE'], $row['CLASS_NAME']);
            balance_req_msg($row['BALANCE_REQ_CASE'], $row['CLASS_NAME']);
            ?>
            <tr class="<?=class_name($row['BALANCE_CASE'],$row['BALANCE_REQ_CASE'])?>">
                <td><?=++$count?></td>

                <td> <?=$row['CLASS_ID']?>
                    <input type="hidden" name="ser[]" value="<?=$row['SER']?>" />
                    <input  type="hidden" name="class_id[]" value="<?=$row['CLASS_ID']?>" />
                </td>

                <td><?=$row['CLASS_NAME']?></td>

                <td>
                    <input name="request_amount[]" class="form-control" value="<?=$row['REQUEST_AMOUNT']?>" />
                </td>

                <td><?=$row['CLASS_UNIT_NAME']?></td>
                <td> <?=$row['CLASS_TYPE_NAME']?>
                    <input  type="hidden" name="class_type[]" value="<?=$row['CLASS_TYPE']?>" />
                </td>

                <?php if($available_amount) echo "<td>{$row['LEFT_AMOUNT']}</td>"; ?>
                <?php if($available_amount) echo "<td>{$row['AVAILABLE_AMOUNT']}</td>"; ?>

            </tr>
        <?php
        }

        }else if($request_input==1 or $request_input==4){
        $count = -1;

        foreach($details as $row) {
        $count++;
        if($row['REQUEST_AMOUNT'] > $row['AVAILABLE_AMOUNT']){
            $dont_adopt= 1;
        }
        if($row['AVAILABLE_AMOUNT']<0){
            $row['AVAILABLE_AMOUNT']=0;
        }
        balance_msg($row['BALANCE_CASE'], $row['CLASS_NAME']);
        balance_req_msg($row['BALANCE_REQ_CASE'], $row['CLASS_NAME']);
        ?>
            <tr class="<?=class_name($row['BALANCE_CASE'],$row['BALANCE_REQ_CASE'])?>">
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
                    <input name="request_amount[]" class="form-control" id="txt_request_amount<?=$count?>" value="<?=$row['REQUEST_AMOUNT']?>" />
                </td>

                <td>
                    <select name="class_unit[]" class="form-control" id="unit_txt_class_id<?=$count?>" data-val="<?=$row['CLASS_UNIT']?>" ><option></option></select>
                </td>

                <td>
                    <select name="class_type[]" class="form-control" id="txt_class_type<?=$count?>" data-val="<?=$row['CLASS_TYPE']?>" ><option></option></select>
                </td>

                <?php if($available_amount) echo "<td>{$row['LEFT_AMOUNT']}</td>"; ?>
                <?php if($available_amount) echo "<td>{$row['AVAILABLE_AMOUNT']}</td>"; ?>
            </tr>

        <?php } }else if( count($transport_details) > 0 and $request_input== -1){ // الاصناف من مناقلة
            $count = -1;
            foreach($transport_details as $row) {
            $count++;
        ?>
            <tr>
                <td><i class="glyphicon glyphicon-sort" /></i></td>
                <td>
                    <input type="hidden" name="ser[]" value="0" />
                    <input name="class[]" value="<?=$row['CLASS_ID']?>" class="form-control"  id="i_txt_class_id<?=$count?>" />
                    <input  type="hidden" name="class_id[]" value="<?=$row['CLASS_ID']?>" id="h_txt_class_id<?= $count ?>" >
                </td>

                <td>
                    <input name="class_name[]" readonly value="<?=$row['CLASS_NAME']?>" class="form-control"  id="txt_class_id<?=$count?>" />
                </td>

                <td>
                    <input name="request_amount[]" class="form-control" id="txt_request_amount<?=$count?>" value="<?=$row['AMOUNT']?>" />
                </td>

                <td>
                    <select name="class_unit[]" class="form-control" id="unit_txt_class_id<?=$count?>" data-val="<?=$row['CLASS_UNIT']?>" ><option></option></select>
                </td>

                <td>
                    <select name="class_type[]" class="form-control" id="txt_class_type<?=$count?>" data-val="<?=$row['CLASS_TYPE']?>" ><option></option></select>
                </td>
            </tr>

        <?php } }else if( count($donate_details) > 0 and $request_input== -2){ // الاصناف من المنح
            $count = -1;
            foreach($donate_details as $row) {
            $count++;
        ?>
            <tr>
                <td><i class="glyphicon glyphicon-sort" /></i></td>
                <td>
                    <input type="hidden" name="ser[]" value="0" />
                    <input name="class[]" readonly value="<?=$row['CALSS_ID']?>" class="form-control"  id="i_txt_class_id<?=$count?>" />
                    <input  type="hidden" name="class_id[]" value="<?=$row['CALSS_ID']?>" id="h_txt_class_id<?= $count ?>" >
                </td>

                <td>
                    <input name="class_name[]" readonly value="<?=$row['DONATION_CLASS_ID_NAME']?>" class="form-control"  id="txt_class_id<?=$count?>" />
                </td>

                <td>
                    <input name="request_amount[]" class="form-control" id="txt_request_amount<?=$count?>" value="" />
                </td>

                <td>
                    <select name="class_unit[]" class="form-control" id="unit_txt_class_id<?=$count?>" data-val="<?=$row['CLASS_UNIT']?>" ><option></option></select>
                </td>

                <td>
                    <select name="class_type[]" class="form-control" id="txt_class_type<?=$count?>" data-val="<?=$row['CLASS_TYPE']?>" ><option></option></select>
                </td>
            </tr>

        <?php } } ?>

        </tbody>
        <tfoot>
        <tr>
            <th></th>
            <th>
                <?php if (( ($request_input==1 or $request_input==4) and count($details) <=0 || ( (isset($can_edit)?$can_edit:false) and  $adopt==1 ) ) or $request_input==-1 ) { ?>
                    <a onclick="javascript:addRow();" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>
                <?php } ?>
            </th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <?php if($available_amount) echo "<th></th>"; ?>
            <?php if($available_amount) echo "<th></th>"; ?>
        </tr>
        </tfoot>
    </table>
</div>

<script>
    var dont_adopt= <?=$dont_adopt?> ;
    var balance_msg= '<?=$balance_msg?>' ;
    var balance_req_msg= '<?=$balance_req_msg?>' ;
</script>
