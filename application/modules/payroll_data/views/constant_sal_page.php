<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 20/06/22
 * Time: 09:00 ص
 */

$MODULE_NAME= 'payroll_data';
$TB_NAME= 'Constants_sal';
$TB_NAME2= 'Constant_sal_details';
$get_url =base_url("$MODULE_NAME/$TB_NAME/get_id");
$edit_url =base_url("$MODULE_NAME/$TB_NAME/edit");
$get_details_url =base_url("$MODULE_NAME/$TB_NAME2/get_page");

?>
<div class="table-responsive tableRoundedCorner">
<table class="table mg-b-0 text-md-nowrap roundedTable table-bordered" id="Constants_sal_tb" data-container="container">
    <thead class="table-light">
    <tr>
        <th  >#</th>
        <th>م</th>
        <th>الرقم</th>
        <th>الثابت</th>
        <?php if(HaveAccess($get_details_url) || 1) echo "<th class='text-center' style='width: 10%'>التفاصيل</th>"; ?>
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
                <td>{$row['TB_NAME']}</td>";
        if(HaveAccess($get_details_url) || 1)
            echo "<td class='text-center'><a onclick='javascript:Constant_sal_details_get({$row['TB_NO']}, \"{$row['TB_NAME']}\");' href='javascript:;'><i class='fa fa-list'></i></a> </td>";
        echo "</tr>";
    }
    ?>
    </tbody>

</table>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('#Constants_sal_tb').dataTable({
            "lengthMenu": [ [10,20,30,40,50,100, -1], [10,20,30,40,50,100, "الكل"] ],
            "sPaginationType": "full_numbers"
        });
    });

    if (typeof initFunctions == 'function') {
        initFunctions();
    }
</script>
