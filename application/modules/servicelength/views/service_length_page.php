<?php
$MODULE_NAME = 'servicelength';
$TB_NAME = 'Service_length';
$get_emp_data_url = base_url("$MODULE_NAME/$TB_NAME/get");
print_r($emp_list);
?>
<style>

    .tooltip {
        position: relative;
        display: inline-block;
        border-bottom: 1px dotted black;
    }

    .tooltip .tooltiptext {
        visibility: hidden;
        width: 120px;
        background-color: black;
        color: #fff;
        text-align: center;
        border-radius: 6px;
        padding: 5px 0;

        /* Position the tooltip */
        position: absolute;
        z-index: 1;
    }

    .tooltip:hover .tooltiptext {
        visibility: visible;
    }
</style>
<div class="row">
    <div class="table-responsive tableRoundedCorner">
        <table class="table mg-b-0 text-md-nowrap roundedTable table-bordered" id="page_tb">
            <thead class="table-light">
            <tr>
                <th>الرقم الوظيفي</th>
                <th>رقم الهوية</th>
                <th>الاسم</th>
                <th>الفرع</th>
                <th>المسمى الوظيفي</th>
                <th>المبلغ التقاعدي </th>
                <th>حالة الاستمارة</th>
            </tr>
            </thead>
            <tbody>
            <?php  foreach($page_rows as $emp) :?>
                <tr ondblclick="javascript:get_to_link('<?= $get_emp_data_url.'/'.$emp['NO']?>')">
                    <td><?= $emp['NO'] ?></td>
                    <td><?= $emp['ID'] ?></td>
                    <td><?= $emp['NAME'] ?></td>
                    <td><?= $emp['BRANCH_NAME'] ?></td>
                    <td><?= $emp['W_NO_NAME'] ?></td>
                    <td>
<?= $emp['SALARY_DIFF']?>
                       <!---- <p><?= $emp['SALARY_DIFF'].'( '.$emp['TOTAL_SALARY_RETIREMENT_5'].' - '.$emp['TOTAL_EMP_SALARY_60'].' )' ?> </p>--->
















                        </td>
                    <td><?= $emp['ADOPT_NAME'] ?></td>
                </tr>


            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>


