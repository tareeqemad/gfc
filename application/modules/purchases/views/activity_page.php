<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$MODULE_NAME= 'purchases';
$TB_NAME= 'activity';
$get_url =base_url("$MODULE_NAME/$TB_NAME/get_id");
$edit_url =base_url("$MODULE_NAME/$TB_NAME/edit");


?>

<table class="table" id="activity_tb" data-container="container">
    <thead>
    <tr>
        <th>#</th>
        <th>م</th>
        <th>الرقم</th>
        <th>النشاط</th>

    </tr>
    </thead>

    <tbody>
    <?php
    $count=0;
    foreach ($get_all as $row){
        $count++;
        echo "<tr data-id='{$row['SER']}' ";
        if(HaveAccess($get_url) and HaveAccess($edit_url))
            echo "ondblclick='javascript:activity_get({$row['SER']});'";
        echo ">
                <td><input type='checkbox' class='checkboxes' value='{$row['SER']}' /></td>
                <td>$count</td>
                <td>{$row['SER']}</td>
                <td>{$row['ACTIVITY_NAME']}</td>";
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
