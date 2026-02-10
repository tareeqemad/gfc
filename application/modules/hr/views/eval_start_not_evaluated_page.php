<?php
/**
 * Created by PhpStorm.
 * User: mtaqia
 * Date: 07/08/16
 * Time: 10:09 ص
 */

$count = 1;
?>
<div class="tbl_container00">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th class="form-group col-sm-1">#</th>
            <th class="form-group col-sm-1">الرقم الوظيفي</th>
            <th class="form-group col-sm-2">اسم الموظف</th>
            <th class="form-group col-sm-2">اسم المدير المباشر</th>
            <th class="form-group col-sm-2">اسم مدير الادارة / المقر</th>
            <th class="form-group col-sm-2">تقييم المدير المباشر</th>
            <th class="form-group col-sm-2">تقييم الزملاء</th>
            <th class="form-group col-sm-2">تقييم الرئيس الاعلى</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($page_rows as $row) :?>
            <tr>
                <td><?=$count?></td>
                <td><?=$row['EMPLOYEE_NO']?></td>
                <td><?=$row['EMPLOYEE_NAME']?></td>
                <td><?=$row['MANAGER_NAME']?></td>
                <td><?=$row['MANAGEMENT_MANAGER_NAME']?></td>
                <td><?=$row['EVAL_PRIMARY']?></td>
                <td><?=$row['EVAL_BROTHER']?></td>
                <td><?=$row['EVAL_GRANDSON']?></td>
                <?php $count++ ?>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#page_tb').dataTable({
            "lengthMenu": [ [50,100,200,300, -1], [50,100,200,300, "الكل"] ],
            "sPaginationType": "full_numbers"
        });
    });

    if (typeof initFunctions == 'function') {
        initFunctions();
    }
</script>

