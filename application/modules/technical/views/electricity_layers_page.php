<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 03/12/15
 * Time: 10:33 ص
 */

function class_name($case){
    if($case==0){
        return 'case_0';
    }elseif($case==1){
        return 'case_4';
    }else{
        return 'case_1';
    }
}

?>

<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th>م</th>
            <th>نظام توزيع الأحمال</th>
            <th>اسم الشريحة </th>
            <th>حالة الشريحة</th>
            <th>تاريخ التعديل</th>
            <th>اسم المستخدم</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($page_rows as $row) :?>
            <tr class="<?=class_name($row['LAYER_CASE'])?>" ondblclick="javascript:show_row_details('<?=$row['LAYER_ID']?>');">
                <td><?=$row['LAYER_ID']?></td>
                <td><?=$row['ELECTRICITY_LOAD_SYSTEM_NAME']?></td>
                <td><?=$row['LAYER_NAME']?></td>
                <td><?=$row['LAYER_CASE_NAME']?></td>
                <td><?=$row['UPDATE_DATE']?></td>
                <td title="<?=$row['UPDATE_USER_NAME']?>"><?=get_user_name($row['UPDATE_USER'])?></td>
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
