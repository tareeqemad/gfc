<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 19/10/14
 * Time: 11:14 ص
 */

$MODULE_NAME= 'settings';
$TB_NAME= 'service_feez_account';

$get_url =base_url("$MODULE_NAME/$TB_NAME/get_id");
$edit_url =base_url("$MODULE_NAME/$TB_NAME/edit");

?>

<table class="table" id="service_feez_account_tb" data-container="container">
    <thead>
    <tr>
        <th  >#</th>
        <th>م</th>
        <th>رقم الخدمة</th>
        <th>اسم الخدمة</th>
        <th>رقم الحساب</th>
        <th>اسم الحساب</th>
    </tr>
    </thead>

    <tbody>
<?php
$count=0;
foreach ($get_list as $row){
    $count++;
    echo "<tr data-id='{$row['SERVICE_NO']}' ";
    if(HaveAccess($get_url) and HaveAccess($edit_url))
        echo "ondblclick='javascript:service_feez_account_get({$row['SERVICE_NO']});'";
    echo ">
                <td><input type='checkbox' class='checkboxes' value='{$row['SERVICE_NO']}' /></td>
                <td>$count</td>
                <td>{$row['SERVICE_NO']}</td>
                <td>{$row['SERVICE_NAME']}</td>
                <td>{$row['ACCOUNT_ID']}</td>
                <td>{$row['ACCOUNT_NAME']}</td>";

    echo "</tr>";
}
?>
    </tbody>

</table>

<script type="text/javascript">
    $(document).ready(function() {
        $('#service_feez_account_tb').dataTable({
            "lengthMenu": [ [-1,10,20,30,40,50,100], ["الكل",10,20,30,40,50,100] ],
            "sPaginationType": "full_numbers"
        });
    });

    if (typeof initFunctions == 'function') {
        initFunctions();
    }
</script>
