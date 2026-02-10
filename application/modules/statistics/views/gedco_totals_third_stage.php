<?php
/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 15/02/22
 * Time: 09:08 ص
 */
$count = 1;
$final_total = 0;
?>
<div class="row">
    <div class="alert alert-info text-center h4">
        <?= $desc ?>
    </div>
    <div style="padding-right: 40px;">
        <a href="javascript:;" class="prints btn-lg" h_title="طباعة احصائيات مركز الاتصالات" onclick="javascript:print_report(3,1,1);">
            <span class="glyphicon glyphicon-print" style="color: #0a8800"></span>
        </a>
    </div>
    <div class="col-md-6">
        <div class="tbl_container">
            <table class="table" id="call_center_tb" data-container="container">
                <thead>
                <tr>
                    <th>م</th>
                    <th> الحركات</th>
                    <th>العدد</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($result as $row) : $final_total +=  $row['30']; ?>
                    <tr>
                        <td><?=$count++?></td>
                        <td><?=$row['TYPE_Q_NAME']?></td>
                        <td><?=isset($row['30']) ? $row['30'] : 0?></td>
                    </tr>
                <?php endforeach;?>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="2">الاجمالي</td>
                    <td><?=number_format($final_total,0)?></td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div> <!--first col-md- 6 -->
    <div class="col-md-6">
        <div class="callCenter" id="callCenter">
            <div id="chart_callCenter"></div>
        </div>
    </div>
</div><!--first row --->

