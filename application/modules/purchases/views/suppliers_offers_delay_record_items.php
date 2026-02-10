<?php
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 06/09/15
 * Time: 10:59 ص
 */

$count = 0;
$count1=0;
function class_name($amount,$p_amount){
    if ($amount<$p_amount)  return '#ffe1b2';
    else return  '#FFFFFF';

}
$sum_cust=array();
$asum_cust=array();
?>
<style>
    .percent-input{
        padding-left: 12px;
        background-image: url('<?=base_url('assets/images/percent-sign.gif')?>');
        background-size: 10px; background-repeat: no-repeat; background-position: center left;
    }
</style>
<div class="tbl_container" >
    <input type="hidden" name="counter" value="<?=count($suppliers_offers_data)?>" id="counter">

    <table class="table" id="suppliers_offers_detTbl" data-container="container">
        <thead>
        <tr>
            <th style="width: 50px"   > #</th>
            <th style="width: 300px" class="form-control col-sm-2"  >  اسم البند</th>
            <th style="width: 80px"  >الوحدة</th>
            <th style="width: 80px"  >قرار اللجنة للصنف</th>
            <th style="width: 100px"  >ملاحظات على تاجيل الصنف</th>
            <th style="width: 80px"  >كمية طلب الشراء</th>
            <?php foreach($suppliers_offers_data as $row) :?>
                <th colspan="9" style="width: 300px"  ><?= $row['CUST_NAME'] ?></th>
            <?php endforeach;?>
            <th ></th>
        </tr>
        <tr>
            <th style="width: 50px"   > </th>
             <th style="width: 300px" class="form-control col-sm-2"  > </th>
            <th style="width: 80px"  ></th>
            <th style="width: 80px"  ></th>
            <th style="width: 100px"  >   </th>
            <th style="width: 80px"  >  </th>

            <?php foreach($suppliers_offers_data as $row) :?>
                <th style="width: 80px" colspan="3" > نسبة الخصم للمورد </th>
                <th >
                    <input style="width: 40px"  type="text" name="suppliers_discount[<?= $row['SUPPLIERS_OFFERS_ID'] ?>]"  min="0"   value="<?= $row['SUPPLIERS_DISCOUNT'] ?>" id="suppliers_discount_<?= $row['SUPPLIERS_OFFERS_ID'] ?>"   data-val-required="حقل مطلوب"   data-val-regex="المدخل غير صحيح!"   class="form-control percent-input">
                    <input style="width: 40px"  type="hidden" name="suppliers_offers_id[]"  min="0"   value="<?= $row['SUPPLIERS_OFFERS_ID'] ?>" id="suppliers_offers_id_<?= $row['SUPPLIERS_OFFERS_ID'] ?>"   data-val-required="حقل مطلوب"   data-val-regex="المدخل غير صحيح!"   class="form-control">

                </th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            <?php endforeach;?>
            <th ></th>
        </tr>
        <tr>
            <th style="width: 50px"   > </th>
            <th style="width: 300px" class="form-control col-sm-2"  > </th>
            <th style="width: 80px"  ></th>
            <th style="width: 80px"  ></th>
            <th style="width: 100px"  >   </th>
            <th style="width: 80px"  >  </th>

            <?php foreach($suppliers_offers_data as $row) :?>
                <th style="width: 80px" colspan="3" > قيمة الخصم للمورد بالعملة الموحدة </th>
                <th ></th>
                <th>
                    <input style="width: 40px"  type="text" name="discount_value[<?= $row['SUPPLIERS_OFFERS_ID'] ?>]"  min="0"   value="<?= $row['SUPPLIERS_DISCOUNT_VALUE'] ?>" id="discount_value_<?= $row['SUPPLIERS_OFFERS_ID'] ?>"   data-val-required="حقل مطلوب"   data-val-regex="المدخل غير صحيح!"   class="form-control">

                    <input style="width: 40px"  type="hidden" name="suppliers_offers_ids[]"  min="0"   value="<?= $row['SUPPLIERS_OFFERS_ID'] ?>" id="suppliers_offers_ids_<?= $row['SUPPLIERS_OFFERS_ID'] ?>"   data-val-required="حقل مطلوب"   data-val-regex="المدخل غير صحيح!"   class="form-control">

                </th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            <?php endforeach;?>
            <th ></th>
        </tr>
        <tr>
            <th   ></th>
            <th    style="width: 300px"  ></th>
            <th  ></th>
            <th  ></th>
            <th  ></th>
            <th   >  </th>
            <?php foreach($suppliers_offers_data as $row) : $sum_cust[$row['SUPPLIERS_OFFERS_ID']]=0; $asum_cust[$row['SUPPLIERS_OFFERS_ID']]=0; ?>
                <th style="width: 75px"  >الكمية</th>
                <!--    <th style="width: 75px"  ><?="السعر"."(".$row['CUST_CURR_ID'].")"."(".$row['CURR_VALUE'].")"?> </th>-->
                <th style="width: 75px"  >السعر </th>
                <th style="width: 75px"  >إجمالي السعر </th>
                <th style="width: 150px"  >نسبة الخصم  </th>
                <th style="width: 150px"  >قيمة الخصم  </th>
                <th style="width: 75px"  >سعر الترسية  </th>
                <th style="width: 75px"  >إجمالي سعر الترسية</th>
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

                <td  style="width: 300px">
                    <?= $row1['ITEM'] ?>
                </td>
                <td>
                    <?= $row1['CLASS_UNIT_NAME']?>
                </td>
                <td>
                    <select id="dp_award_delay_decision_<?=$row1['SER']?>" name="award_delay_decision[<?=$row1['SER']?>]"  class="form-control col-sm-1 award_decision" >
                        <?php //foreach($award_delay_decisions as $rowx) :?>
                        <?php //if($rowx['CON_NO']!=3 and $row1['COMMITEE_CASE']!=6) ?>
                        <!-- <option <?=($row1['AWARD_DELAY_DECISION']==$rowx['CON_NO'])?'selected':''?> value="<?=$rowx['CON_NO']?>" ><?=$rowx['CON_NAME']?></option>-->
                        <?php //endforeach; ?>
                        <?php if($row1['AWARD_DELAY_DECISION']!=3 ) { ?>
                            <option <?=($row1['AWARD_DELAY_DECISION']==2)?'selected':''?> value="2" >مؤجل</option>
                            <option <?=($row1['AWARD_DELAY_DECISION']==3)?'selected':''?> value="3" >تنفيذ</option>
                            <option <?=($row1['AWARD_DELAY_DECISION']==3)?'selected':''?> value="0" >إلغاء</option>
                        <?php } else { ?>
                            <option  value="3" >منفذ</option>
                        <?php   }    ?>  </select>
                </td>
                <td>
                    <textarea rows="2" name="award_delay_decision_hint[<?=$row1['SER']?>]" id="award_delay_decision_hint_<?=$row1['SER']?>" class="form-control" style="width: 169px; height: 45px;" ><?=$row1['AWARD_DELAY_DECISION_HINT'] ?></textarea>

                    <!--  <input type="text" name="award_delay_decision_hint[<?=$row1['SER']?>]" data-val="true"   id="award_delay_decision_hint_<?=$row1['SER']?>"  value="<?=$row1['AWARD_DELAY_DECISION_HINT'] ?>"  class="form-control col-sm-2"> -->

                </td>
                <td> <input type="hidden" name="purchase_amount[]"    value="<?= $row1['APPROVED'] ?>" id="purchase_amount_<?= $count1 ?>"   data-val-required="حقل مطلوب"   data-val-regex="المدخل غير صحيح!"   class="form-control p_amount">
                    <?= $row1['APPROVED'] ?>
                </td>
                <?php

                foreach($suppliers_offers_data as $row) : ?>
                    <td id="amount_<?=$row['SUPPLIERS_OFFERS_ID']?>_<?=$row1['SER']?>" style="background:<?=class_name($cust_amount[$row['SUPPLIERS_OFFERS_ID']][$row1['SER']],$row1['APPROVED'])?>" class="amountxx" > <?=$cust_amount[$row['SUPPLIERS_OFFERS_ID']][$row1['SER']] ?></td>
                    <!--   <td  > <?=$cust_cust_price[$row['SUPPLIERS_OFFERS_ID']][$row1['SER']] ?></td>-->
                    <td  class="price"  > <?=$cust_price[$row['SUPPLIERS_OFFERS_ID']][$row1['SER']] ?></td>
                    <td  > <?php $val=$cust_price[$row['SUPPLIERS_OFFERS_ID']][$row1['SER']]*$cust_amount[$row['SUPPLIERS_OFFERS_ID']][$row1['SER']] ;
                        echo $val;
                        $sum_cust[$row['SUPPLIERS_OFFERS_ID']]=$sum_cust[$row['SUPPLIERS_OFFERS_ID']]+$val;
                        ?></td>
                    <td>
                        <input  type="text"  min="0"  max="100" name="class_discount[<?=$row['SUPPLIERS_OFFERS_ID']?>][<?=$row1['SER']?>]" data-val="true"   id="class_discount_<?=$row['SUPPLIERS_OFFERS_ID']?>_<?=$row1['SER']?>"   value="<?=$cust_class_discount[$row['SUPPLIERS_OFFERS_ID']][$row1['SER']] ?>"  class="form-control percent-input">
                    </td>
                    <td>
                        <input  type="text"  min="0"   name="c_discount_value[<?=$row['SUPPLIERS_OFFERS_ID']?>][<?=$row1['SER']?>]" data-val="true"   id="c_discount_value_<?=$row['SUPPLIERS_OFFERS_ID']?>_<?=$row1['SER']?>"   value="<?=$cust_class_discount_value[$row['SUPPLIERS_OFFERS_ID']][$row1['SER']] ?>"  class="form-control">
                    </td>
                    <td>  <input type="text"   name="approved_price[<?=$row['SUPPLIERS_OFFERS_ID']?>][<?=$row1['SER']?>]" data-val="true"   id="approved_price_<?=$row['SUPPLIERS_OFFERS_ID']?>_<?=$row1['SER']?>"   value="<?=$cust_approved_price[$row['SUPPLIERS_OFFERS_ID']][$row1['SER']] ?>"  class="form-control ">
                    </td>
                    <td  class="total_<?=$row['SUPPLIERS_OFFERS_ID']?>">
                        <?php $aval=$cust_approved_price[$row['SUPPLIERS_OFFERS_ID']][$row1['SER']]*$cust_amount[$row['SUPPLIERS_OFFERS_ID']][$row1['SER']]-($cust_approved_price[$row['SUPPLIERS_OFFERS_ID']][$row1['SER']]*$cust_amount[$row['SUPPLIERS_OFFERS_ID']][$row1['SER']]*$cust_class_discount[$row['SUPPLIERS_OFFERS_ID']][$row1['SER']]/100) ;
                        echo $aval;
                        $asum_cust[$row['SUPPLIERS_OFFERS_ID']]=$asum_cust[$row['SUPPLIERS_OFFERS_ID']]+$aval;
                        ?>

                    </td>
                    <td>
                        <input type="text"  max="<?= $cust_amount[$row['SUPPLIERS_OFFERS_ID']][$row1['SER']] ?>"  name="approved_amount[<?=$row['SUPPLIERS_OFFERS_ID']?>][<?=$row1['SER']?>]" data-val="true"   id="approved_amount_<?=$row['SUPPLIERS_OFFERS_ID']?>_<?=$row1['SER']?>"   value="<?=$approved_amount[$row['SUPPLIERS_OFFERS_ID']][$row1['SER']] ?>"  class="form-control balance">

                    </td>
                    <td>
                        <textarea rows="2" name="award_hints[<?=$row['SUPPLIERS_OFFERS_ID']?>][<?=$row1['SER']?>]" id="award_hints_<?=$row['SUPPLIERS_OFFERS_ID']?>_<?=$row1['SER']?>" class="form-control" style="width: 169px; height: 45px;" ><?=$award_hints[$row['SUPPLIERS_OFFERS_ID']][$row1['SER']] ?></textarea>

                        <!-- <input type="text" name="award_hints[<?=$row['SUPPLIERS_OFFERS_ID']?>][<?=$row1['SER']?>]" data-val="true"   id="award_hints_<?=$row['SUPPLIERS_OFFERS_ID']?>_<?=$row1['SER']?>"  value="<?=$award_hints[$row['SUPPLIERS_OFFERS_ID']][$row1['SER']] ?>"  class="form-control col-sm-2"> -->

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

            <th colspan="4">المجموع</th>
            <?php foreach($suppliers_offers_data as $row) :?>
                <th colspan="2" ></th>
                <th ></th>
                <th ></th>
                <th  ><?=$sum_cust[$row['SUPPLIERS_OFFERS_ID']]?></th>
                <th ></th>   <th ></th>
                <th ></th>
                <th class="sumtotal_<?=$row['SUPPLIERS_OFFERS_ID']?>" id="sumtotal_<?=$row['SUPPLIERS_OFFERS_ID']?>" ><?=$asum_cust[$row['SUPPLIERS_OFFERS_ID']]?></th>

            <?php endforeach;?>
            <th></th>

        </tr>
        <tr>


            <td colspan="6">ملاحظات اللجنة على المورد</td>
            <?php foreach($suppliers_offers_data as $row) :?>

                <td colspan="9" >
                    <!-- <input type="text" name="award_notes[<?=$row['SUPPLIERS_OFFERS_ID']?>]" data-val="true"   id="award_notes_<?=$row['SUPPLIERS_OFFERS_ID']?>"  value="<?=$row['AWARD_NOTES'] ?>"  class="form-control col-sm-11">-->
                    <textarea name="award_notes[<?=$row['SUPPLIERS_OFFERS_ID']?>]" id="award_notes_<?=$row['SUPPLIERS_OFFERS_ID']?>"><?=$row['AWARD_NOTES'] ?></textarea>

                </td>
            <?php endforeach;?>
            <td></td>

        </tr>
        </tfoot>
    </table>

</div>

