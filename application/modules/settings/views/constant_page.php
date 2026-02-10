<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 03/09/14
 * Time: 10:05 ص
 */

$MODULE_NAME= 'settings';
$TB_NAME= 'constant';
$TB_NAME2= 'constant_details';
$get_url =base_url("$MODULE_NAME/$TB_NAME/get_id");
$edit_url =base_url("$MODULE_NAME/$TB_NAME/edit");
$get_details_url =base_url("$MODULE_NAME/$TB_NAME2/get_page");

?>

<table class="table" id="constant_tb" data-container="container">
    <thead>
    <tr>
        <th  >#</th>
        <th>م</th>
        <th>الرقم</th>
        <th>الثابت</th>
        <?php if(HaveAccess($get_details_url)) echo "<th>التفاصيل</th>"; ?>
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
                if(HaveAccess($get_details_url))
                    echo "<td><a onclick='javascript:constant_details_get({$row['TB_NO']}, \"{$row['TB_NAME']}\");' href='javascript:;'><i class='glyphicon glyphicon-th-list'></i>عرض التفاصيل</a> </td>";
            echo "</tr>";
    }
    ?>
    </tbody>

</table>

<script type="text/javascript">
    $(document).ready(function() {
        $('#constant_tb').dataTable({
            "lengthMenu": [ [10,20,30,40,50,100, -1], [10,20,30,40,50,100, "الكل"] ],
            "sPaginationType": "full_numbers"
        });
    });

    if (typeof initFunctions == 'function') {
        initFunctions();
    }
</script>
