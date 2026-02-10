<?php
/**
 * Created by PhpStorm.
 * User: mbadawi
 * Date: 19/07/16
 * Time: 01:05 م
 */ 
 $count = 0;
// $row['COUNT_DET']
 ?>
 <!------------------>
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
                            <th>مدير الإدارة/المقر</th>
                            <th>النتيجة قبل التدقيق</th>
                            <th>التقدير قبل التدقيق</th>
                            <th>النتيجة بعد التدقيق</th>
                            <th>التقدير بعد التدقيق</th>
                            <th>طباعة</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($result as $row) :?>
                
                            <tr ondblclick="javascript:show_rows_details('<?=$row['EVALUATION_ORDER_SERIAL']?>');">
                                <td><?= ($count+1); ?></td>
                                <td><?=$row['EVALUATION_ORDER_SERIAL']?></td>
                                <td><?=$row['EVALUATION_ORDER_ID']?></td>
                                <td><?=$row['EVAL_FROM_ID_NAME']?></td>
                                <td><?=$row['EMP_NO']?></td>
                                <td><?=$row['EMP_NO_NAME']?></td>
                                <td><?=$row['EMP_MANAGER_ID']?></td>
                                <td><?=$row['EMP_MANAGER_ID_NAME']?></td>
                                <td><?=$row['MANAGEMENT_MANAGER_NAME']?></td>
                                <td><?=$row['FINAL_MARK_BEFORE_AUDIT']?></td>
                                <td><?=$row['MARK_BEFORE_AUD_NAME']?></td>
                                <td><?=$row['FINAL_MARK_BEFORE_OBJECTION']?></td>
                                <td><?=$row['MARK_BEFORE_OBJ_NAME']?></td>
                                <td><li><a onclick="javascript:print_report(<?=$row['EVALUATION_ORDER_SERIAL']?>,<?=$row['GRANDSON_ORDER_SERIAL']?>,<?=$row['EVALUATION_ORDER_ID']?>,<?=$row['EMP_NO']?>);" href="javascript:;"><i class="glyphicon glyphicon-print" style="font-size: 15px; color:coral"></i></a></td>
                                <?php $count++ ?>
                            </tr>
                        <?php endforeach;?>
                        </tbody>
                    </table>
               </div>
              <!------------------>