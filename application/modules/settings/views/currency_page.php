<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 31/08/14
 * Time: 10:57 ص
 */

$MODULE_NAME= 'settings';
$TB_NAME= 'currency';
$get_url =base_url("$MODULE_NAME/$TB_NAME/get_id");
$edit_url =base_url("$MODULE_NAME/$TB_NAME/edit");
?>

<table class="table" id="currency_tb" data-container="container">
    <thead>
    <tr>
        <th  >#</th>
        <th>م</th>
        <th>العملة</th>
        <th>الرمز</th>
    </tr>
    </thead>

    <tbody>
    <?php
    $count=0;
foreach ($get_all as $row){
$count++;
echo "
<tr data-id='{$row['CURR_ID']}' ";
if(HaveAccess($get_url) and HaveAccess($edit_url)) echo "ondblclick='javascript:currency_get({$row['CURR_ID']});'";
echo ">
<td><input type='checkbox' class='checkboxes' value='{$row['CURR_ID']}' /></td>
<td>$count</td>
<td>{$row['CURR_NAME']}</td>
<td>{$row['CURR_CODE']}</td>
</tr>
";
}
?>
</tbody>

</table>

<script type="text/javascript">
    $(document).ready(function() {
        $('#currency_tb').dataTable({
            "lengthMenu": [ [10,20,30,40,50,100, -1], [10,20,30,40,50,100, "الكل"] ],
            "sPaginationType": "full_numbers"
        });
    });

    if (typeof initFunctions == 'function') {
        initFunctions();
    }
</script>
