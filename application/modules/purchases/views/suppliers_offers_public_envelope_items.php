<?php
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 13/04/16
 * Time: 12:06 م
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
            <th style="width: 300px"   >  اسم البند</th>
            <th style="width: 80px"  >الوحدة</th>
            <th style="width: 80px"  >كمية طلب الشراء</th>
            <?php foreach($suppliers_offers_data as $row) :?>
                <th colspan="4" style="width: 300px"  ><?= $row['CUST_NAME']."(".$row['CUST_CURR_ID'].")" ?></th>
            <?php endforeach;?>
            <th ></th>
        </tr>
        <tr>
            <th   ></th>

            <th  style="width: 300px"   ></th>
            <th  ></th>
            <th   >  </th>

            <?php foreach($suppliers_offers_data as $row) : $sum_cust[$row['SUPPLIERS_OFFERS_ID']]=0;?>
                <th style="width: 75px"  >الكمية</th>
                <!--  <th style="width: 75px"  ><?="السعر"."(".$row['CUST_CURR_ID'].")"."(".$row['CURR_VALUE'].")"?> </th>-->
                <th style="width: 75px"  >السعر </th>
                <th style="width: 75px"  >إجمالي السعر </th>
                <th style="width: 75px"  > الملاحظات على الصنف </th>
            <?php endforeach;?>
            <th ></th>
        </tr>
        </thead>
        <tbody>


        <?php foreach($rec_details as $row1) :?>
            <?php $count1++; ?>
            <tr>
                <td> <?=($count1)?>
                    <input type="hidden" id="h_ser_<?= $count ?>" name="h_ser[]" value="<?= $row1['SER'] ?>">


                </td>

                <td style="width: 300px" >
                    <?= $row1['CLASS_NAME'] ?>
                </td>
                <td>
                    <?= $row1['CLASS_UNIT_NAME']?>
                </td>
                <td> <input type="hidden" name="purchase_amount[]"  disabled  value="<?= $row1['APPROVED'] ?>" id="purchase_amount_<?= $count1 ?>"   data-val-required="حقل مطلوب"   data-val-regex="المدخل غير صحيح!"   class="form-control">
                    <?= $row1['APPROVED'] ?>
                </td>
                <?php

                foreach($suppliers_offers_data as $row) : ?>
                    <td style="background:<?=class_name($cust_amount[$row['SUPPLIERS_OFFERS_ID']][$row1['SER']],$row1['APPROVED'])?>" > <?=$cust_amount[$row['SUPPLIERS_OFFERS_ID']][$row1['SER']] ?></td>
                    <!--   <td  > <?=$cust_cust_price[$row['SUPPLIERS_OFFERS_ID']][$row1['SER']] ?></td>-->
                    <td  > <?=$cust_price[$row['SUPPLIERS_OFFERS_ID']][$row1['SER']] ?></td>
                    <td  > <?php //$val=$cust_price[$row['SUPPLIERS_OFFERS_ID']][$row1['CLASS_ID']]*$row['CURR_VALUE'] ;
                        $val=$cust_price[$row['SUPPLIERS_OFFERS_ID']][$row1['SER']]*$cust_amount[$row['SUPPLIERS_OFFERS_ID']][$row1['SER']] ;
                        echo $val;
                        $sum_cust[$row['SUPPLIERS_OFFERS_ID']]=$sum_cust[$row['SUPPLIERS_OFFERS_ID']]+$val;
                        ?></td>
                    <td  > <?=$note[$row['SUPPLIERS_OFFERS_ID']][$row1['SER']] ?></td>
                <?php endforeach;?>
                <td>

                </td>
            </tr>
        <?php endforeach;?>

        </tbody>
        <tfoot>
        <tr>


            <th colspan="4">المجموع</th>
            <?php foreach($suppliers_offers_data as $row) :?>

                <th colspan="2" ></th>

                <th  ><?=$sum_cust[$row['SUPPLIERS_OFFERS_ID']]?></th>
                <th  ></th>
            <?php endforeach;?>
            <th></th>

        </tr>

        </tfoot>
    </table>

</div>

