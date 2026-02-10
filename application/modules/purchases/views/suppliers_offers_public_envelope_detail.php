<?php
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 13/04/16
 * Time: 12:05 م
 */


$count = 0;
$count1=0;
function class_name($amount,$p_amount){
    if ($amount<$p_amount)  return '#ffe1b2';
    else return  '#FFFFFF';

}
$sum_cust=array();
?>

<div class="tbl_container" >
    <table class="table" id="suppliers_offers_detTbl" data-container="container">
        <thead>
        <tr>
            <th style="width: 50px"   > #</th>
            <th style="width:90px "   >  رقم الصنف</th>
            <th style="width: 300px"   >  اسم الصنف</th>
            <th style="width: 80px"  >الوحدة</th>
            <th style="width: 80px"  >كمية طلب الشراء</th>
            <?php foreach($suppliers_offers_data as $row) :?>
                <th colspan="3" style="width: 300px"  ><?= $row['CUST_NAME']."(".$row['CUST_CURR_ID'].")" ?></th>
            <?php endforeach;?>

        </tr>
        <tr>
            <th   ></th>
            <th   ></th>
            <th  style="width: 300px"   ></th>
            <th  ></th>
            <th   >  </th>

            <?php foreach($suppliers_offers_data as $row) : $sum_cust[$row['SUPPLIERS_OFFERS_ID']]=0;?>
                <th style="width: 75px"  >الكمية</th>
                <!--  <th style="width: 75px"  ><?="السعر"."(".$row['CUST_CURR_ID'].")"."(".$row['CURR_VALUE'].")"?> </th>-->
                <th style="width: 75px"  >السعر </th>
                <th style="width: 75px"  >إجمالي السعر </th>
             <?php endforeach;?>

        </tr>
        </thead>
        <tbody>


        <?php foreach($rec_details as $row1) :?>
            <?php $count1++; ?>
            <tr>
                <td> <?=($count1)?>
                    <input type="hidden" id="h_ser_<?= $count ?>" name="h_ser[]" value="<?= $row1['SER'] ?>">


                </td>
                <td >
                    <input type="hidden" name="h_class_id[]" data-val="true"   id="h_class_id_<?= $count1 ?>"   value="<?= $row1['CLASS_ID'] ?>"  class="form-control col-sm-2">
                    <?= $row1['CLASS_ID'] ?>
                </td>
                <td style="width: 300px" >
                    <?= $row1['CLASS_ID_NAME'] ?>
                </td>
                <td>
                    <?= $row1['CLASS_UNIT_NAME']?>
                </td>
                <td> <input type="hidden" name="purchase_amount[]"  disabled  value="<?= $row1['APPROVED'] ?>" id="purchase_amount_<?= $count1 ?>"   data-val-required="حقل مطلوب"   data-val-regex="المدخل غير صحيح!"   class="form-control">
                    <?= $row1['APPROVED'] ?>
                </td>
                <?php

                foreach($suppliers_offers_data as $row) : ?>
                    <td style="background:<?=class_name($cust_amount[$row['SUPPLIERS_OFFERS_ID']][$row1['CLASS_ID']],$row1['APPROVED'])?>" > <?=$cust_amount[$row['SUPPLIERS_OFFERS_ID']][$row1['CLASS_ID']] ?></td>
                    <!--   <td  > <?=$cust_cust_price[$row['SUPPLIERS_OFFERS_ID']][$row1['CLASS_ID']] ?></td>-->
                    <td  > <?=$cust_price[$row['SUPPLIERS_OFFERS_ID']][$row1['CLASS_ID']] ?></td>
                    <td  > <?php //$val=$cust_price[$row['SUPPLIERS_OFFERS_ID']][$row1['CLASS_ID']]*$row['CURR_VALUE'] ;
                        $val=$cust_price[$row['SUPPLIERS_OFFERS_ID']][$row1['CLASS_ID']]*$cust_amount[$row['SUPPLIERS_OFFERS_ID']][$row1['CLASS_ID']] ;
                        echo $val;
                        $sum_cust[$row['SUPPLIERS_OFFERS_ID']]=$sum_cust[$row['SUPPLIERS_OFFERS_ID']]+$val;
                        ?></td>
                 <?php endforeach;?>

            </tr>
        <?php endforeach;?>



        <tr>
            <!--   <th rowspan="1" class="align-right" colspan="4">
                <?php //if (count($rec_details) <=0 || $action=='edit') ://if (count($chains_details) <=0 || ( isset($can_edit)?$can_edit:false)) : ?>
                   <a onclick="javascript:addRow();" onfocus="javascript:addRow();" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>
                <?php //endif; ?>
            </th>-->
            <td >المجموع</td>
            <td ></td><td ></td><td ></td><td ></td>
            <?php foreach($suppliers_offers_data as $row) :?>

                <td  ></td> <td  ></td>

                <td  ><?=$sum_cust[$row['SUPPLIERS_OFFERS_ID']]?></td>

            <?php endforeach;?>

        </tr>

        </tbody>
    </table>

</div>
