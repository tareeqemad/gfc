<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 03/09/15
 * Time: 11:20 ص
 */

?>

<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th><input class="group-checkable" data-set="#page_tb .checkboxes" type="checkbox"></th>
            <th>رقم الصنف</th>
            <th>اسم الصنف عربي </th>
            <th>اسم الصنف انجليزي </th>
            <th> السعر</th>
            <th>الوحدة</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($classes as $row) :?>
            <tr>
                <td><input type="checkbox" class="checkboxes" value="<?=$row['CLASS_ID']?>" /></td>
                <td><?=$row['CLASS_ID']?></td>
                <td><?=$row['CLASS_NAME_AR']?></td>
                <td><?=$row['CLASS_NAME_EN']?></td>
                <td><?=number_format($row['CLASS_PURCHASING'],2)?></td>
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
</script>
