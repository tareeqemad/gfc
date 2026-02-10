<?php
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 01/04/15
 * Time: 09:37 ص
 */
$count = 0;
$count1=0;

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
                <th style="width: 300px"  ><?= $row['CUST_NAME'] ?></th>
            <?php endforeach;?>
            <th ></th>
        </tr>
        <tr>
            <th   ></th>
            <th   ></th>
            <th  style="width: 300px"   ></th>
            <th  ></th>
            <th  ></th> <?php foreach($suppliers_offers_data as $row) : $sum_cust[$row['CUSTOMER_ID']]=0;?>
      <th><table class="table" data-container="container"><tr>
                        <th style="width: 75px"  >الكمية</th>
                        <!--  <th style="width: 75px"  ><?="السعر"."(".$row['CUST_CURR_ID'].")"."(".$row['CURR_VALUE'].")"?> </th>-->
                        <th style="width: 75px"  >السعر </th>
                        <th style="width: 75px"  >إجمالي السعر </th>
                  </tr></table></th><?php endforeach;?>
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
                <td> <input type="hidden" name="purchase_amount[]"  disabled  value="<?= $row1['AMOUNT'] ?>" id="purchase_amount_<?= $count1 ?>"   data-val-required="حقل مطلوب"   data-val-regex="المدخل غير صحيح!"   class="form-control">
                    <?= $row1['AMOUNT'] ?>
                </td>
                <?php foreach($suppliers_offers_data as $row) :?>
                    <td><table class="table" id="<?=$row['CUSTOMER_ID']?>_<?=$row1['CLASS_ID']?>" data-container="container"><tr>
                            <td  > <?=$cust_amount[$row['CUSTOMER_ID']][$row1['CLASS_ID']] ?></td>
                            <!--   <td  > <?=$cust_cust_price[$row['CUSTOMER_ID']][$row1['CLASS_ID']] ?></td>-->
                            <td  > <?=$cust_price[$row['CUSTOMER_ID']][$row1['CLASS_ID']] ?></td>
                            <td  > <?php $val=$cust_price[$row['CUSTOMER_ID']][$row1['CLASS_ID']]*$row['CURR_VALUE'] ;
                                echo $val;
                               $sum_cust[$row['CUSTOMER_ID']]=$sum_cust[$row['CUSTOMER_ID']]+$val;
                                ?></td>
                        </tr></table></td><?php endforeach;?>
                <td>

                </td>
            </tr>
        <?php endforeach;?>

        </tbody>

    </table>

</div>

