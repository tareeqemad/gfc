<?php
/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 15/02/22
 * Time: 09:08 ص
 */
$count = 1;
$total = 0;
$total_cash_count = 0;
$total_cash_val = 0;
$total_holley_count = 0;
$total_holley_val = 0;
$total_dexen_count = 0;
$total_dexen_val = 0;
$final_total = 0;
?>
<div class="alert alert-info">
        <h4 class="text-center"><?= $desc ?></h4>
</div>
<div class="row">
        <div class="col-md-6">
            <div class="tbl_container">
                <table class="table" id="day_collection_tb" data-container="container">
                    <thead>
                    <tr class="alert alert-info">
                        <td colspan="2"></td>
                        <td colspan="2">
                            <a href="javascript:;" class="prints btn-lg" h_title="طباعة عدد وقيمة الفواتير" onclick="javascript:print_report(6,1,2);">
                                <span class="glyphicon glyphicon-print" style="color: #0a8800"></span>
                            </a>
                        </td>
                        <td colspan="2">
                            <a href="javascript:;" class="prints btn-lg" h_title="طباعة عدد وقيمة شحنات الهولي" onclick="javascript:print_report(6,2,2);">
                                <span class="glyphicon glyphicon-print" style="color: #0a8800"></span>
                            </a>
                        </td>
                        <td colspan="3">
                            <a href="javascript:;" class="prints btn-lg" h_title="طباعة عدد وقيمة شحنات الدكسن" onclick="javascript:print_report(6,3,2);">
                                <span class="glyphicon glyphicon-print" style="color: #0a8800"></span>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <th>م</th>
                        <th>المقر</th>
                        <th>عدد الفواتير</th>
                        <th>قيمة الفواتير</th>
                        <th>عدد شحنات الهولي</th>
                        <th>قيمة شحنات الهولي</th>
                        <th>عدد شحنات الدكسن</th>
                        <th>قيمة شحنات الدكسن</th>
                        <th>اجمالي المبالغ</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($result as $row) :
                            $total +=  $row['CASH_VAL'] + $row['HOLLEY_VAL'] + $row['DEXEN_VAL'];
                            $total_cash_count += $row['CASH_COUNT'];
                            $total_cash_val += $row['CASH_VAL'];
                            $total_holley_count += $row['HOLLEY_COUNT'];
                            $total_holley_val += $row['HOLLEY_VAL'];
                            $total_dexen_count += $row['DEXEN_COUNT'];
                            $total_dexen_val += $row['DEXEN_VAL'];
                            $final_total += $total;
                        ?>
                        <tr>
                            <td><?=$count++?></td>
                            <td><?=$row['BRANCH_NAME']?></td>
                            <td><?=number_format($row['CASH_COUNT'],0)?></td>
                            <td><?=number_format($row['CASH_VAL'],0)?></td>
                            <td><?=number_format($row['HOLLEY_COUNT'],0)?></td>
                            <td><?=number_format($row['HOLLEY_VAL'],0)?></td>
                            <td><?=number_format($row['DEXEN_COUNT'],0)?></td>
                            <td><?=number_format($row['DEXEN_VAL'],0)?></td>
                            <td><?=number_format($total,0)?></td>
                        </tr>
                    <?php
                        $total=0;
                    endforeach;?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2">الاجمالي</td>
                            <td class="text-center"><?=number_format($total_cash_count,0)?></td>
                            <td class="text-center"><?=number_format($total_cash_val,0)?></td>
                            <td class="text-center"><?=number_format($total_holley_count,0)?></td>
                            <td class="text-center"><?=number_format($total_holley_val,0)?></td>
                            <td class="text-center"><?=number_format($total_dexen_count,0)?></td>
                            <td class="text-center"><?=number_format($total_dexen_val,0)?></td>
                            <td class="text-center"><?=number_format($final_total,0)?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div><!--first col-md-6 --->
        <div class="col-md-6">
            <div class="day_collection" id="day_collection">
                <div id="chart_day_collection"></div>
            </div>
        </div><!--second col-md-6-->
</div>