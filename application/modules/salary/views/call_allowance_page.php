<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 15/02/23
 * Time: 13:00 ص
 */

$MODULE_NAME= 'salary';
$TB_NAME= 'Call_allowance';
$TB_NAME2= 'Call_allowance_details';
$get_url =base_url("$MODULE_NAME/$TB_NAME/public_get_id");
$edit_url =base_url("$MODULE_NAME/$TB_NAME/edit");
$get_details_url =base_url("$MODULE_NAME/$TB_NAME2/get_page");

?>
<div class="table-responsive tableRoundedCorner">
    <table class="table mg-b-0 text-md-nowrap roundedTable table-bordered" id="Call_allowance_tb" data-container="container">
        <thead class="table-light">
        <tr>
            <th>#</th>
            <th>م</th>
            <th>رقم الفئة</th>
            <th>اسم الفئة</th>
            <th>مبلغ الفئة</th>
            <?php if(HaveAccess($get_details_url) ) echo "<th class='text-center' style='width: 10%'>التفاصيل</th>"; ?>
        </tr>
        </thead>

        <tbody>
        <?php
        $count=0;
        foreach ($get_all as $row){
            $count++;
            echo "<tr data-id='{$row['TB_NO']}' ";
            if(HaveAccess($get_url) and HaveAccess($edit_url))
                echo "ondblclick='javascript:constant_get({$row['TB_NO']});'";
            echo ">
                <td><input type='checkbox' class='checkboxes' value='{$row['TB_NO']}' /></td>
                <td>$count</td>
                <td>{$row['TB_NO']}</td>
                <td>{$row['TB_NAME']}</td>
                <td>{$row['TB_AMOUNT']}</td>";
            if(HaveAccess($get_details_url) )
                echo "<td class='text-center'><a onclick='javascript:Call_allowance_details_get({$row['TB_NO']}, \"{$row['TB_NAME']}\", \"{$row['TB_AMOUNT']}\");' href='javascript:;'><i class='fa fa-list'></i></a> </td>";
            echo "</tr>";
        }
        ?>
        </tbody>

    </table>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('#Call_allowance_tb').dataTable({
            "lengthMenu": [ [10,20,30,40,50,100, -1], [10,20,30,40,50,100, "الكل"] ],
            "sPaginationType": "full_numbers"
        });
    });

    if (typeof initFunctions == 'function') {
        initFunctions();
    }
</script>
