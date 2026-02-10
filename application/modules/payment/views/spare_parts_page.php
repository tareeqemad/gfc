<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 17/03/22
 * Time: 09:35 ص
 */

$MODULE_NAME= 'payment';
$TB_NAME= 'Spare_parts';
$get_url =base_url("$MODULE_NAME/$TB_NAME/get_id");
$edit_url =base_url("$MODULE_NAME/$TB_NAME/edit");

?>

<table class="table" id="Spare_parts_tb" data-container="container">
    <thead>
    <tr>
        <th>#</th>
        <th>رقم الصنف</th>
        <th>اسم الصنف</th>
        <th>حالة الصنف</th>
        <th>الوحدة</th>
    </tr>
    </thead>

    <tbody>
    <?php

    foreach ($get_final as $items){

        echo "
<tr data-id='{$items['CLASS_NO']}' ";

        if(HaveAccess($get_url) and HaveAccess($edit_url)) echo "ondblclick='javascript:{$TB_NAME}_get({$items['CLASS_NO']});'";

        echo ">
<td><input type='checkbox' class='checkboxes' value='{$items['CLASS_NO']}' /></td>

<td>{$items['CLASS_NO']}</td>
<td>{$items['CLASS_NAME']}</td>
<td>{$items['CLASS_STATUS_NAME']}</td>
<td>{$items['CLASS_UNIT_NAME']}</td>

</tr>
";
    }
    ?>
    </tbody>

</table>

<script type="text/javascript">
    $(document).ready(function() {
        $('#Spare_parts_tb').dataTable({
            "lengthMenu": [ [10,20,30,40,50,100, -1], [10,20,30,40,50,100, "الكل"] ],
            "sPaginationType": "full_numbers"
        });
    });


    if (typeof initFunctions == 'function') {
        initFunctions();
    }

</script>
