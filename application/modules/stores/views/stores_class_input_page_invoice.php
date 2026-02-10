<?php
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 25/02/15
 * Time: 10:57 ص
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$MODULE_NAME= 'stores';
$TB_NAME= 'stores_class_input';
$get_url =base_url("$MODULE_NAME/$TB_NAME/get_id");
$order_url=base_url("purchases/orders/get");
$count = $offset;

?>
<div class="tbl_container">
    <table class="table" id="stores_class_input_tb" data-container="container">
        <thead>
        <tr>

            <th>م</th>
            <th> رقم محضر الفحص و الاستلام </th>
            <th>تاريخ السند </th>
            <th> رقم أمر التوريد</th>
            <th> اسم المخزن </th>
            <th>اسم المورد </th>
        </tr>
        </thead>
        <tbody>
        <?php if($page > 1): ?>
            <tr>
                <td colspan="10" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
        <?php foreach($page_rows as $row) :?>
<!--ondblclick="javascript:show_row_details('<?=$row['CLASS_INPUT_ID']?>');" -->
            <tr >
                  <td><a  target="_blank" href="<?=$get_url?>/<?=$row['CLASS_INPUT_ID']?>/edit/1/1"><?=$count?></a></td>
                <td><?=$row['RECEORD_ID_TEXT']?></td>
                <td><?=$row['CLASS_INPUT_DATE']?></td>
                <td><?php if ($row['ORDER_ID_SEQ']==0) echo $row['ORDER_ID'];
                    else echo '<a  target="_blank" href="'.$order_url.'/'.$row['ORDER_ID_SEQ'].'/1">'.$row['ORDER_ID'].'</a>';
                    ?></td>
                <td><?=$row['STORE_NAME']?></td>
                <td><?=$row['CUST_NAME']?></td>
                <?php $count++ ?>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>



