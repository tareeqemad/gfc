<?php
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 20/11/14
 * Time: 09:37 ص
 */


$MODULE_NAME= 'stores';
$TB_NAME= 'stores';
$TB_NAME2= 'store_usres';
$get_url =base_url("$MODULE_NAME/$TB_NAME/get_id");
$edit_url =base_url("$MODULE_NAME/$TB_NAME/edit");
$get_details_url =base_url("$MODULE_NAME/$TB_NAME/get_page_users");
?>

<table class="table" id="stores_tb" data-container="container">
    <thead>
    <tr>
        <th  >#</th>
        <th>م</th>
        <th>الرقم</th>
        <th>رقم المخزن الجديد</th>
        <th>المخزن</th>
        <th>رقم الحساب</th>
        <th>اسم الحساب</th>
        <th>رقم دفتر إدخال </th>
        <?php if(HaveAccess($get_details_url)) echo "<th>المستخدمون</th>"; ?>
    </tr>
    </thead>

    <tbody>
    <?php
    $count=0;
    foreach ($get_all as $row){
        $count++;
        echo "<tr data-id='{$row['STORE_ID']}' ";
        if(HaveAccess($get_url) and HaveAccess($edit_url))
            echo "ondblclick='javascript:stores_get({$row['STORE_ID']});'";
        echo ">
                <td><input type='checkbox' class='checkboxes' value='{$row['STORE_ID']}' /></td>
                <td>$count</td>
                   <td>{$row['STORE_ID']}</td>
                <td>{$row['STORE_NO']}</td>
                <td>{$row['STORE_NAME']}</td>
                  <td>{$row['ACCOUNT_ID']}</td>
                    <td>{$row['ACCOUNT_ID_NAME']}</td>
                     <td>{$row['ENTER_BOOK']}</td>";
        if(HaveAccess($get_details_url))
            echo "<td><a onclick='javascript:{$TB_NAME2}_get({$row['STORE_ID']});' href='javascript:;'><i class='glyphicon glyphicon-th-list'></i>عرض التفاصيل</a> </td>";

        echo "</tr>";
    }
    ?>
    </tbody>

</table>

<script type="text/javascript">
    $(document).ready(function() {
        $('#stores_tb').dataTable({
            "lengthMenu": [ [10,20,30,40,50,100, -1], [10,20,30,40,50,100, "الكل"] ],
            "sPaginationType": "full_numbers"
        });
    });

    if (typeof initFunctions == 'function') {
        initFunctions();
    }
</script>
