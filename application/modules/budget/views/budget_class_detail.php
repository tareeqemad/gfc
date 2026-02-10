<?php
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 17/10/16
 * Time: 12:18 م
 */

$count = 0;
$count1=0;

$sum_col=array();
$sum_row=array();
$sum_sum=0;

//print_r($exp_rev);
?>

<div class="tbl_container" >
    <table class="table" id="budget_class_detTbl" data-container="container">
        <thead>
        <tr>
        <th style="width: 200px"   > #</th>
            <?php foreach($col as $row) :
                $sum_col[$row['NO']]=0;?>
            <th colspan="2"><?=$row['NAME']?></th>
            <?php endforeach;?>
            <th colspan="2"></th>
        </tr>
        <tr>
            <th > </th>
            <?php foreach($col as $row) :?>
                <th>الكمية</th>
                <th>السعر</th>
            <?php endforeach;?>
            <th>إجمالي كمية</th>
            <th>إجمالي سعر</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($rows as $row1) :
            $sum_row[$row1['ITEM_NO']]=0;
            $sum_count[$row1['ITEM_NO']]=0;?>
        <tr>
        <td><?=$row1['NAME']?></td>
            <?php foreach($col as $row) :?>
                <td><?php $count=isset($sum_ccount[$row['NO']][$row1['ITEM_NO']])? $sum_ccount[$row['NO']][$row1['ITEM_NO']] :0;
                    echo number_format($count,2) ;
                    $sum_count[$row1['ITEM_NO']]= $sum_count[$row1['ITEM_NO']]+$count;

                    ?></td>
                <td><?php $price=isset($sum_price[$row['NO']][$row1['ITEM_NO']])? $sum_price[$row['NO']][$row1['ITEM_NO']] :0;
                    echo number_format($price,2) ;
                    $sum_col[$row['NO']]=$sum_col[$row['NO']]+$price;
                    $sum_row[$row1['ITEM_NO']]= $sum_row[$row1['ITEM_NO']]+$price;

                    ?></td>
            <?php endforeach;?>
            <td><?=number_format($sum_count[$row1['ITEM_NO']],2)?></td>
            <td><?=number_format($sum_row[$row1['ITEM_NO']],2)?></td>
        </tr>
        <?php endforeach;?>
        </tbody>
        <tfoot>
        <tr>
            <th style="width: 200px"   > الإجمالي</th>
            <?php foreach($col as $row) : $sum_sum=$sum_sum+$sum_col[$row['NO']];
          ?>
                <th ></th>
                <th ><?=number_format($sum_col[$row['NO']],2)?></th>
            <?php endforeach;?>
            <th></th>
            <th><?=number_format($sum_sum,2)?></th>
        </tr>
        </tfoot>
        </table>
    </div>