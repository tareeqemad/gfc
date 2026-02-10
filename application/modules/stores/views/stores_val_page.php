<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 25/04/15
 * Time: 12:37 م
 */

$total=0;
$cnt=0;

?>

<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th style="width: 2%">م</th>
            <th style="width: 10%">رقم الصنف</th>
            <th style="width: 30%">اسم الصنف</th>
            <th style="width: 10%">حالة الصنف</th>
            <th style="width: 10%">الوحدة</th>
            <th style="width: 10%">الرصيد</th>
            <th style="width: 10%">سعر الوحدة</th>
            <th style="width: 10%">الاجمالي</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($get_list as $row) :
            $total+=$row['TOTAL'];
            $cnt++;
        ?>
            <tr>
                <td><?=$cnt?></td>
                <td><?=$row['CLASS_ID']?></td>
                <td><?=$row['CLASS_ID_NAME']?></td>
                <td><?=$row['CLASS_TYPE_NAME']?></td>
                <td><?=$row['UNIT_NAME']?></td>
                <td><?=number_format($row['CAMOUNT'],2)?></td>
                <td><?=number_format($row['CPRICE'],2)?></td>
                <td><?=number_format($row['TOTAL'],2)?></td>
            </tr>
        <?php endforeach;?>
        </tbody>
        <tfoot>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th><?=number_format($total,2)?></th>
        </tfoot>
    </table>
</div>

<script>
    if (typeof initFunctions == 'function') {
        initFunctions();
    }
</script>
