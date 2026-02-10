<?php
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 26/06/16
 * Time: 01:49 م
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$MODULE_NAME= 'purchases';
$TB_NAME= 'orders';
$get_url =base_url("$MODULE_NAME/$TB_NAME/get");
//$order_url=base_url("purchases/orders/get");
$count = $offset;
$sums=0;
?>
<div class="tbl_container">
    <table class="table" id="orders_tb" data-container="container">
        <thead>
        <tr>

            <th>م</th>
           <th>تاريخ السند </th>
            <th> رقم أمر التوريد</th>
            <th>اسم المورد </th>
            <th>إجمالي المبلغ</th>
            <th>  عملة المورد </th>
        </tr>
        </thead>
        <tbody>
        <?php if($page > 1): ?>
            <tr>
                <td colspan="10" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
        <?php foreach($page_rows as $row) :?>
                <tr >
                <td><?=$count?></td>
                <td><?=$row['ENTRY_DATE']?></td>
                <td><?php
                     echo '<a  target="_blank" href="'.$get_url.'/'.$row['ORDER_ID'].'/1">'.$row['ORDER_TEXT_T'].'</a>';
                    ?></td>
                <td><?=$row['CUST_NAME']?></td>
                 <td><?php
                     $sums=$sums+$row['SUM_VALS'];
                     echo $row['SUM_VALS']?></td>
                <td><?=$row['CURR_ID_NAME']?></td>
                <?php $count++ ?>
            </tr>
        <?php endforeach;?>
        <tr >
            <td colspan="4">المجموع</td>
            <td><?=$sums?></td>
            <td></td>

        </tr>
        </tbody>
    </table>
</div>



