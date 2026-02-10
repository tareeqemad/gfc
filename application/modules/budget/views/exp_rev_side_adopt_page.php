<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 15/09/15
 * Time: 01:27 م
 */

?>

<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th><input class="group-checkable" data-set="#page_tb .checkboxes" type="checkbox"></th>
            <th>البند</th>
            <th>الكمية الاجمالية 	</th>
            <th>الوحدة</th>
            <th>السعر الاجمالي</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($get_list as $row) :?>
            <tr>
                <td><input type="checkbox" class="checkboxes" value="<?=$row['ITEM_NO']?>" /></td>
                <td><?=$row['ITEM_NAME']?></td>
                <td><?=$row['COUNT_SUM']?></td>
                <td><?=$row['ITEM_UNIT']?></td>
                <td><?=number_format($row['TOTAL'],2)?></td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>

<script>
    if (typeof initFunctions == 'function') {
        initFunctions();
    }
</script>
