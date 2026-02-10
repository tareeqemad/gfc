<?php
/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 15/02/22
 * Time: 09:08 ص
 */
$count = 1;
$sub_total = 0;
$total_no_signal = 0;
$total_processing = 0;
$total_entry = 0;
$total_repeated = 0;
$final_total = 0;
?>
<div class="row">
    <div class="alert alert-info text-center h4">
        <?= $desc ?>
    </div>
    <div style="padding-right: 40px;">
        <a href="javascript:;" class="prints btn-lg" h_title="طباعة احصائيات الاشارات" onclick="javascript:print_report(2,1,1);">
            <span class="glyphicon glyphicon-print" style="color: #0a8800"></span>
        </a>
    </div>
    <div class="col-md-5">
        <div class="tbl_container">
            <table class="table" id="emergency_tb" data-container="container">
                <thead>
                <tr>
                    <th>م</th>
                    <th>المقر</th>
                    <th>عدد الاشارات الاجمالي</th>
                    <th>المعالجة</th>
                    <th>قيد المعالجة</th>
                    <th>المتكررة</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($result as $row) :
                    $sub_total += $row['NO_SIGNAL'] + $row['PROCESSING'] + $row['ENTRY'] + $row['REPEATED'];
                    $total_no_signal += $row['NO_SIGNAL'];
                    $total_processing += $row['PROCESSING'];
                    $total_entry += $row['ENTRY'];
                    $total_repeated += $row['REPEATED'];
                    ?>
                    <tr>
                        <td><?=$count++?></td>
                        <td><?=$row['BRANCH_NAME']?></td>
                        <td><?=$row['NO_SIGNAL']?></td>
                        <td><?=$row['PROCESSING']?></td>
                        <td><?=$row['ENTRY']?></td>
                        <td><?=$row['REPEATED']?></td>
                    </tr>
                    <?php endforeach;?>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="2">الاجمالي</td>
                    <td><?=$total_no_signal?></td>
                    <td><?=$total_processing?></td>
                    <td><?=$total_entry?></td>
                    <td><?=$total_repeated?></td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div class="col-md-7">
        <div class="signals" id="signals">
            <div id="chart_emergency"></div>
        </div>
    </div>
</div>

