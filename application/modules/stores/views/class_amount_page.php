<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 03/01/15
 * Time: 08:02 م
 */

?>

<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th style="width: 17%">المخزن</th>
            <th style="width: 10%">رقم الصنف</th>
            <th style="width: 23%">الصنف</th>
            <th style="width: 10%">حالة الصنف</th>
            <th style="width: 10%">الرصيد</th>
            <th style="width: 10%">الحد الادنى</th>
            <th style="width: 10%">حد اعادة الطلب </th>
            <th style="width: 10%">الوحدة</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($get_list as $row) :?>
            <tr>
                <td><?=$row['STORE_NO'].': '.$row['STORE_NAME']?></td>
                <td> <input readonly style="height: 25px; width: 100px; text-align: center" type="text" onclick='txt_copy(this)' value="<?=$row['CLASS_ID']?>" /> </td>
                <td><?=$row['CLASS_NAME']?></td>
                <td><?=$row['CLASS_TYPE_NAME']?></td>
                <td><?=number_format($row['AMOUNT'],2)?></td>
                <td><?=number_format($row['CLASS_MIN'],2)?></td>
                <td><?=number_format($row['CLASS_MIN_REQUEST'],2)?></td>
                <td><?=$row['CLASS_UNIT_NAME']?></td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>

<script>
    if (typeof initFunctions == 'function') {
        initFunctions();
    }
    function txt_copy(txtarea){
        txtarea.select();
        document.execCommand('copy');
    }
</script>
