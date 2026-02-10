<?php
$MODULE_NAME = 'empvacancey';
$TB_NAME = 'vacancey';
$get_emp_data_url = base_url("$MODULE_NAME/$TB_NAME/get");
?>
<div class="row">
    <div class="table-responsive">
        <table class="table  table-bordered" id="page_tb">
            <thead class="table-light">
            <tr>
                <th>الرقم الوظيفي</th>
                <th>رقم الهوية</th>
                <th>الاسم</th>
                <th>الفرع</th>
                <th>المسمى الوظيفي</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($page_rows as $emp) :?>
                <tr ondblclick="javascript:get_to_link('<?= $get_emp_data_url.'/'.$emp['NO']?>')">
                    <td><?= $emp['NO'] ?></td>
                    <td><?= $emp['ID'] ?></td>
                    <td><?= $emp['NAME'] ?></td>
                    <td><?= $emp['BRANCH_NAME'] ?></td>
                    <td><?= $emp['ST_NAME'] ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
