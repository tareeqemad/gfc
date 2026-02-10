<?php
/**
 * Created by PhpStorm.
 * User: mbadawi
 * Date: 17/07/16
 * Time: 11:14 ص
 */ 
 $count = 0;
 ?>
<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th>#</th>
            <th>مسلسل التقييم</th>
            <th>أمر التقييم</th>
            <th>نموذج التقييم</th>
            <th>رقم الموظف</th>
            <th>اسم الموظف</th>
            <th>رقم مسؤول التقييم</th>
            <th>اسم مسؤول التقييم</th>
            <th>مدير الادارة/المقر</th>
            <th>اعتماد مدير الادارة</th>
            <th>درجة التقييم</th>
            <th>التقييم</th>
            
        </tr>
        </thead>
        <tbody>
        <?php foreach($page_rows as $row) :?>

            <tr ondblclick="javascript:show_row_details('<?=$row['EVALUATION_ORDER_SERIAL']?>');">
                <td><?= ($count+1); ?></td>
                <td><?=$row['EVALUATION_ORDER_SERIAL']?></td>
                <td><?=$row['EVALUATION_ORDER_ID']?></td>
                <td><?=$row['EVAL_FROM_ID_NAME']?></td>
                <td><?=$row['EMP_NO']?></td>
                <td><?=$row['EMP_NO_NAME']?></td>
                <td><?=$row['EMP_MANAGER_ID']?></td>
                <td><?=$row['EMP_MANAGER_ID_NAME']?></td>
                <td><?=$row['MANAGEMENT_MANAGER_NAME']?></td>
                <td><?=$row['OPINION_NAME']?></td>
                <td><?=$row['MARK_SUM']?></td>
                <td><?=$row['MARK_ID_NAME']?></td>
                
              <?php $count++ ?>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>