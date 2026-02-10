<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

$MODULE_NAME = 'payroll_data';
$TB_NAME = 'Salarys_grades';
$get_url = base_url("$MODULE_NAME/$TB_NAME/get_id");
$edit_url = base_url("$MODULE_NAME/$TB_NAME/edit");

?>
<div class="col-md-12">
    <div class="table-responsive">
        <table id="Salarys_grades_tb" class="table table-bordered text-nowrap roundedTable border-bottom"
               data-container="container">
            <thead class="table-light">
            <tr>
                <th>#</th>
                <th>الرقم التسلسلي</th>
                <th>الدرجة</th>
                <th>الراتب الاساسي</th>
                <th>ملاحظات</th>
                <th>نوع الموظف</th>
            </tr>
            </thead>

            <tbody>
            <?php

            foreach ($get_final as $items) {

                echo "
<tr data-id='{$items['NO']}' ";

                if (1) echo "ondblclick='javascript:{$TB_NAME}_get({$items['NO']});'";

                echo ">
<td><input type='checkbox' class='checkboxes' value='{$items['NO']}' /></td>

<td>{$items['NO']}</td>
<td>{$items['NAME']}</td>
<td>{$items['BASIC_SALARY']}</td>
<td>{$items['NOTES']}</td>
<td>{$items['EMP_TYPE']}</td>

</tr>
";
            }
            ?>
            </tbody>

        </table>
    </div>
</div>
