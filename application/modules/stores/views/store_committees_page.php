<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 21/12/14
 * Time: 01:44 م
 */

$MODULE_NAME= 'stores';
$TB_NAME= 'store_committees';
$TB_NAME2= 'store_members';
$get_url =base_url("$MODULE_NAME/$TB_NAME/get_id");
$edit_url =base_url("$MODULE_NAME/$TB_NAME/edit");
$get_details_url =base_url("$MODULE_NAME/$TB_NAME2/get_page");

?>

<table class="table" id="store_committees_tb" data-container="container">
    <thead>
    <tr>
        <th  >#</th>
        <th>م</th>
        <th>الرقم</th>
        <th>الاسم</th>
        <?php if(HaveAccess($get_details_url)) echo "<th>التفاصيل</th>"; ?>
    </tr>
    </thead>

    <tbody>
    <?php
    $count=0;
    foreach ($get_all as $row){
        $count++;
        echo "<tr data-id='{$row['COMMITTEES_ID']}' ";
        if(HaveAccess($get_url) and HaveAccess($edit_url))
            echo "ondblclick='javascript:store_committees_get({$row['COMMITTEES_ID']});'";
        echo ">
                <td><input type='checkbox' class='checkboxes' value='{$row['COMMITTEES_ID']}' /></td>
                <td>$count</td>
                <td>{$row['COMMITTEES_ID']}</td>
                <td>{$row['COMMITTEES_NAME']}</td>";
        if(HaveAccess($get_details_url))
            echo "<td><a onclick='javascript:store_members_get({$row['COMMITTEES_ID']}, \"{$row['COMMITTEES_NAME']}\");' href='javascript:;'><i class='glyphicon glyphicon-th-list'></i>عرض التفاصيل</a> </td>";
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
