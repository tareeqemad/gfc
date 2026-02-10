<?php
/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 16/02/22
 * Time: 07:59 ص
 */
$count = 1;
$total_type = 0;
$total_gaza = 0; //2
$total_north = 0 ; //3
$total_middle = 0; //4
$total_khan = 0; //6
$total_rafah = 0; //7
$final_total = 0;
?>
<div class="row">
    <div class="alert alert-info text-center h4">
        <?= $desc ?>
    </div>
    <div style="padding-right: 40px;">
        <a href="javascript:;" class="prints btn-lg" h_title="طباعة اجمالي تحصيل المعاملات" onclick="javascript:print_report(5,1,2);">
            <span class="glyphicon glyphicon-print" style="color: #0a8800"></span>
        </a>
    </div>
    <div class="col-md-6">
        <div class="tbl_container">
            <table class="table" id="day_trading_statistics2_tb" data-container="container">
                <thead>
                <tr>
                    <th>م</th>
                    <th>المعاملة</th>
                    <th>غزة</th>
                    <th>الشمال</th>
                    <th>الوسطى</th>
                    <th>خانيونس</th>
                    <th>رفح</th>
                    <th>الاجمالي</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($result as $row) :
                    $total_type   += $row['2'] + $row['3'] + $row['4'] + $row['6'] + $row['7'];
                    $total_gaza   += $row['2'];
                    $total_north  += $row['3'];
                    $total_middle += $row['4'];
                    $total_khan   += $row['6'];
                    $total_rafah  += $row['7'];
                    $final_total  += $total_type;
                    ?>
                    <tr>
                        <td><?=$count++?></td>
                        <td><?=$row['TYPE_NAME']?></td>
                        <td><?=isset($row['2']) ? number_format($row['2'],0) : 0?></td>
                        <td><?=isset($row['3']) ? number_format($row['3'],0) : 0?></td>
                        <td><?=isset($row['4']) ? number_format($row['4'],0) : 0?></td>
                        <td><?=isset($row['6']) ? number_format($row['6'],0) : 0?></td>
                        <td><?=isset($row['7']) ? number_format($row['7'],0) : 0?></td>
                        <td><?=number_format($total_type,0)?></td>
                    </tr>
                <?php $total_type=0; endforeach;?>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="2" class="text-center">الاجمالي</td>
                    <td class="text-center"><?=number_format($total_gaza,0)?></td>
                    <td class="text-center"><?=number_format($total_north,0)?></td>
                    <td class="text-center"><?=number_format($total_middle,0)?></td>
                    <td class="text-center"><?=number_format($total_khan,0)?></td>
                    <td class="text-center"><?=number_format($total_rafah,0)?></td>
                    <td class="text-center"><?= number_format($final_total,0) ?></td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div> <!--first col -md-6 -->
    <div class="col-md-6">
        <div class="day_trading_statistics2" id="day_trading_statistics2">
            <div id="chart_day_trading_statistics2"></div>
        </div>
    </div><!--second col-md-6-->
</div>

