<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

$MODULE_NAME = 'purchases';
$TB_NAME = 'Achievement_follow_up';
$TB_NAME2 = 'Achievement_follow_up_details';
$get_url = base_url("$MODULE_NAME/$TB_NAME/get_id");
$edit_url = base_url("$MODULE_NAME/$TB_NAME/edit");
$get_details_url = base_url("$MODULE_NAME/$TB_NAME2/get_page");

?>

<div class="row">
    <div class="table-responsive">
        <table class="table table-bordered roundedTable" id="constantss_tb">
            <thead class="table-info">
            <tr>
                <th hidden>#</th>
                <th>م</th>
                <th hidden>الرقم</th>
                <th>الثابت</th>
                <th>الترتيب</th>
                <th>الفاعلية</th>
                <?php if (HaveAccess($get_details_url)) echo "<th>التفاصيل</th>"; ?>
            </tr>
            </thead>
            <tbody>
            <?php
            $count = 0;
            foreach ($get_all as $row) {
                $count++;
                echo "<tr data-id='{$row['SER']}' ";
                if (HaveAccess($get_url) and HaveAccess($edit_url))
                    echo "ondblclick='javascript:Achievement_follow_up_get({$row['SER']});'";
                echo ">
                <td hidden><input type='checkbox' class='checkboxes' value='{$row['SER']}' /></td>
                <td>$count</td>
                <td hidden>{$row['SER']}</td>
                <td>{$row['ACTIVITY_NAME']}</td>
                <td>{$row['PROIORTY']}</td>
                <td>{$row['STATUS_NAME']}</td>
         
                ";

                if (HaveAccess($get_details_url))
                    echo "<td><a onclick='javascript:constant_details_get({$row['SER']}, \"{$row['ACTIVITY_NAME']}\");' href='javascript:;'><i class='glyphicon glyphicon-th-list'></i>عرض التفاصيل</a> </td>";
                echo "</tr>";
            }
            ?>
            </tbody>
        </table>
    </div>

</div>
<script type="text/javascript">
    /*$(document).ready(function() {
        $('#constant_tb').dataTable({
            "lengthMenu": [ [10,20,30,40,50,100, -1], [10,20,30,40,50,100, "الكل"] ],
            "sPaginationType": "full_numbers"
        });
    });*/

    if (typeof initFunctions == 'function') {
        initFunctions();
    }

</script>
