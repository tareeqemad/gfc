<?php
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 13/04/16
 * Time: 12:43 م
 */
$count1=0;

?>

<div class="tbl_container" >
    <table class="table" id="suppliers_offers_notesTbl" data-container="container">
        <thead>
        <tr>
            <th style="width:90px "   > # </th>
            <th style="width:90px "   >   اسم المورد</th>
            <th style="width: 300px"   > الملاحظات</th>

        </tr>

        </thead>
        <tbody>


        <?php foreach($suppliers_offers_data as $row) :?>
            <?php $count1++; ?>
            <tr>


                    <td ><?=$count1?></td>
                    <td  > <?=$row['CUST_NAME']."(".$row['CUST_CURR_ID'].")" ?></td>
                    <td  > <?=$row['OFFER_NOTES'] ?></td>


            </tr>
        <?php endforeach;?>
       </tbody>
    </table>

</div>
