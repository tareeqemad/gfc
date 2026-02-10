<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 19/06/22
 * Time: 13:10 ص
 */
$MODULE_NAME = 'payroll_data';
$TB_NAME = 'Bonuses';
$get_url = base_url("$MODULE_NAME/$TB_NAME/get_id");
$edit_url = base_url("$MODULE_NAME/$TB_NAME/edit");
?>
<div class="table-responsive">
    <table id="Bonuses_tb" class="table table-bordered" data-container="container">
        <thead class="table-light">
        <tr>
            <th>#</th>
            <th>الرقم التسلسلي</th>
            <th>الدرجة</th>
            <th>العلاوة</th>
            <th>ملاحظات</th>
            <th>نوع العلاوة</th>
        </tr>
        </thead>

        <tbody>
        <?php

        foreach ($get_final as $items) {
            echo "
                    <tr data-id='{$items['W_NO']}' ";

            if (1) echo "ondblclick='javascript:{$TB_NAME}_get({$items['W_NO']});'";

            echo ">
                    <td><input type='checkbox' class='checkboxes' value='{$items['W_NO']}' /></td>
                    
                    <td>{$items['W_NO']}</td>
                    <td>{$items['W_NAME']}</td>
                    <td>{$items['ALLOWNCE']}</td>
                    <td>{$items['NOTES']}</td>
                    <td>{$items['BONUS_TYPE']}</td>
                    
                    </tr>
                 ";
        }
        ?>
        </tbody>

    </table>
</div>
<script type="text/javascript">
    if (typeof initFunctions == 'function') {
        initFunctions();
    }
</script>
