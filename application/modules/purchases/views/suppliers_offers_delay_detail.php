<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 11/03/15
 * Time: 10:13 ص
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
            <th style="width: 300px" class="form-control col-sm-2"  >  اسم الصنف</th>
            <th style="width: 80px"  >الوحدة</th>
            <th style="width: 80px"  >قرار اللجنة للصنف</th>
            <th style="width: 100px"  >ملاحظات على تاجيل الصنف</th>
            <th style="width: 80px"  >كمية طلب الشراء</th>
            <?php foreach($suppliers_offers_data as $row) :?>
                <th colspan="5" style="width: 300px"  ><?= $row['CUST_NAME'] ?></th>
            <?php endforeach;?>
            <th ></th>
        </tr>
        <tr>
            <th   ></th>
            <th   ></th>
            <th    style="width: 300px"  ></th>
            <th  ></th>
            <th  ></th>
            <th  ></th>
            <th   >  </th>
            <?php foreach($suppliers_offers_data as $row) : $sum_cust[$row['SUPPLIERS_OFFERS_ID']]=0;?>
                <th style="width: 75px"  >الكمية</th>
                <!--    <th style="width: 75px"  ><?="السعر"."(".$row['CUST_CURR_ID'].")"."(".$row['CURR_VALUE'].")"?> </th>-->
                <th style="width: 75px"  >السعر </th>
                <th style="width: 75px"  >إجمالي السعر </th>
                <th style="width: 20px"  > كمية الترسية</th>
                <th style="width: 130px"  >ملاحظات على الصنف</th>
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
                <td>
                    <input type="hidden" name="h_class_id[]" data-val="true"   id="h_class_id_<?= $count1 ?>"   value="<?= $row1['CLASS_ID'] ?>"  class="form-control col-sm-2">
                    <?= $row1['CLASS_ID'] ?>
                </td>
                <td  style="width: 300px">
                    <?= $row1['CLASS_ID_NAME'] ?>
                </td>
                <td>
                    <?= $row1['CLASS_UNIT_NAME']?>
                </td>
                <td>
                    <select id="dp_award_delay_decision_<?=$row1['CLASS_ID']?>" name="award_delay_decision[<?=$row1['CLASS_ID']?>]"  class="form-control col-sm-1 award_decision" >
                        <?php //foreach($award_delay_decisions as $rowx) :?>
                        <?php //if($rowx['CON_NO']!=3 and $row1['COMMITEE_CASE']!=6) ?>
                        <!-- <option <?=($row1['AWARD_DELAY_DECISION']==$rowx['CON_NO'])?'selected':''?> value="<?=$rowx['CON_NO']?>" ><?=$rowx['CON_NAME']?></option>-->
                        <?php //endforeach; ?>
                        <?php if($row1['AWARD_DELAY_DECISION']!=3 ) { ?>
                            <option <?=($row1['AWARD_DELAY_DECISION']==1)?'selected':''?> value="1" >مقبول</option>
                            <option <?=($row1['AWARD_DELAY_DECISION']==2)?'selected':''?> value="2" >مؤجل</option>
                        <?php } else { ?>
                            <option  value="3" >تم تنفيذ التأجيل</option>
                        <?php   }    ?>  </select>
                </td>
                <td>
                    <textarea rows="2" name="award_delay_decision_hint[<?=$row1['CLASS_ID']?>]" id="award_delay_decision_hint_<?=$row1['CLASS_ID']?>" class="form-control" style="width: 169px; height: 45px;" ><?=$row1['AWARD_DELAY_DECISION_HINT'] ?></textarea>

                 <!--   <input type="text" name="award_delay_decision_hint[<?=$row1['CLASS_ID']?>]" data-val="true"   id="award_delay_decision_hint_<?=$row1['CLASS_ID']?>"  value="<?=$row1['AWARD_DELAY_DECISION_HINT'] ?>"  class="form-control col-sm-2">-->

                </td>
                <td> <input type="hidden" name="purchase_amount[]"    value="<?= $row1['APPROVED'] ?>" id="purchase_amount_<?= $count1 ?>"   data-val-required="حقل مطلوب"   data-val-regex="المدخل غير صحيح!"   class="form-control p_amount">
                    <?= $row1['APPROVED'] ?>
                </td>
                <?php

                foreach($suppliers_offers_data as $row) : ?>
                    <td id="amount_<?=$row['SUPPLIERS_OFFERS_ID']?>_<?=$row1['CLASS_ID']?>" style="background:<?=class_name($cust_amount[$row['SUPPLIERS_OFFERS_ID']][$row1['CLASS_ID']],$row1['APPROVED'])?>" class="amountxx" > <?=$cust_amount[$row['SUPPLIERS_OFFERS_ID']][$row1['CLASS_ID']] ?></td>
                    <!--   <td  > <?=$cust_cust_price[$row['SUPPLIERS_OFFERS_ID']][$row1['CLASS_ID']] ?></td>-->
                    <td  > <?=$cust_price[$row['SUPPLIERS_OFFERS_ID']][$row1['CLASS_ID']] ?></td>
                    <td  > <?php $val=$cust_price[$row['SUPPLIERS_OFFERS_ID']][$row1['CLASS_ID']]*$cust_amount[$row['SUPPLIERS_OFFERS_ID']][$row1['CLASS_ID']] ;
                        echo $val;
                        $sum_cust[$row['SUPPLIERS_OFFERS_ID']]=$sum_cust[$row['SUPPLIERS_OFFERS_ID']]+$val;
                        ?></td>
                    <td>
                        <input type="text"  max="<?= $cust_amount[$row['SUPPLIERS_OFFERS_ID']][$row1['CLASS_ID']] ?>"  name="approved_amount[<?=$row['SUPPLIERS_OFFERS_ID']?>][<?=$row1['CLASS_ID']?>]" data-val="true"   id="approved_amount_<?=$row['SUPPLIERS_OFFERS_ID']?>_<?=$row1['CLASS_ID']?>"   value="<?=$approved_amount[$row['SUPPLIERS_OFFERS_ID']][$row1['CLASS_ID']] ?>"  class="form-control balance">

                    </td>
                    <td>
                        <textarea rows="2" name="award_hints[<?=$row['SUPPLIERS_OFFERS_ID']?>][<?=$row1['CLASS_ID']?>]" id="award_hints_<?=$row['SUPPLIERS_OFFERS_ID']?>_<?=$row1['CLASS_ID']?>" class="form-control" style="width: 169px; height: 45px;" ><?=$award_hints[$row['SUPPLIERS_OFFERS_ID']][$row1['CLASS_ID']] ?></textarea>

                      <!--  <input type="text" name="award_hints[<?=$row['SUPPLIERS_OFFERS_ID']?>][<?=$row1['CLASS_ID']?>]" data-val="true"   id="award_hints_<?=$row['SUPPLIERS_OFFERS_ID']?>_<?=$row1['CLASS_ID']?>"  value="<?=$award_hints[$row['SUPPLIERS_OFFERS_ID']][$row1['CLASS_ID']] ?>"  class="form-control col-sm-2">-->

                    </td>
                <?php endforeach;?>
                <td>

                </td>
            </tr>
        <?php endforeach;?>

        </tbody>
        <tfoot>
        <tr>
            <!--   <th rowspan="1" class="align-right" colspan="4">
                <?php //if (count($rec_details) <=0 || $action=='edit') ://if (count($chains_details) <=0 || ( isset($can_edit)?$can_edit:false)) : ?>
                   <a onclick="javascript:addRow();" onfocus="javascript:addRow();" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>
                <?php //endif; ?>
            </th>-->

            <th colspan="7">المجموع</th>
            <?php foreach($suppliers_offers_data as $row) :?>

                <th colspan="2" ></th>

                <th  ><?=$sum_cust[$row['SUPPLIERS_OFFERS_ID']]?></th>
                <th ></th>
                <th ></th>
            <?php endforeach;?>
            <th></th>

        </tr>
        <tr>


            <td colspan="7">ملاحظات اللجنة على المورد</td>
            <?php foreach($suppliers_offers_data as $row) :?>

                <td colspan="5" >
                    <input type="text" name="award_notes[<?=$row['SUPPLIERS_OFFERS_ID']?>]" data-val="true"   id="award_notes_<?=$row['SUPPLIERS_OFFERS_ID']?>"  value="<?=$row['AWARD_NOTES'] ?>"  class="form-control col-sm-11">
                </td>
            <?php endforeach;?>
            <td></td>

        </tr>
        </tfoot>
    </table>

</div>

