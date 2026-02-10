<?php

/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 04/03/23
 * Time: 11:30 ص
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');

$MODULE_NAME= 'reports_ebllc';
$TB_NAME= 'Company_drive';

$isCreate =isset($master_tb_data) && count($master_tb_data)  > 0 ?false:true;
$HaveRs = (!$isCreate)? true:false;
$rs=$isCreate ?array() : $master_tb_data[0];

$date_attr= " data-type='date' data-date-format='DD/MM/YYYY' data-val='true' data-val-regex-pattern='".date_format_exp()."' data-val-regex='Error' ";

$count = 1;
$sub_total = 0;
$total_count_subscriber = 0;
$total_paid_value = 0;
$final_total = 0;

?>

<?= AntiForgeryToken(); ?>

<div class="row">

    <div class="modal-body inline_form">

        <div style="clear: both"></div>
    </div>
</div>

<div class="col-md-5">
    <div class="tbl_container" id="hidden_tb">
        <table class="table" id="campaigns_first_tb" data-container="container">
            <thead>
            <tr>
                <th>م</th>
                <th>المقر</th>
                <th>عدد المشتركين </th>
                <th>المبلغ الفوري</th>
                <th>المبلغ المحصل نقداً</th>
                <th>قيمة القسط</th>
                <th>المتبقي من المجدول</th>
                <th>المجدول </th>

            </tr>
            </thead>
            <tbody>
            <?php foreach($result as $row) :
                $total_cnt_sub += $row['CNT_SUB'];
                $total_instant_pay += $row['INSTANT_PAY'];
                $total_act_paid += $row['ACT_PAID'];
                $total_qest_val += $row['QEST_VAL'];
                $total_sum_all_qest1 += $row['SUM_ALL_QEST1'];
                $total_sum_all_qest += $row['SUM_ALL_QEST'];
                ?>
                <tr>
                    <td><?=$count++?></td>
                    <td><?=$row['BRANCH_NAME']?></td>
                    <td><?=$row['CNT_SUB']?></td>
                    <td><?=number_format($row['INSTANT_PAY'],0)?></td>
                    <td><?=number_format($row['ACT_PAID'],0)?></td>
                    <td><?=number_format($row['QEST_VAL'],0)?></td>
                    <td><?=number_format($row['SUM_ALL_QEST1'],0)?></td>
                    <td><?=number_format($row['SUM_ALL_QEST'],0)?></td>
                </tr>
            <?php endforeach;?>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="2">الاجمالي</td>
                <td><?=number_format($total_cnt_sub,0)?></td>
                <td><?=number_format($total_instant_pay,0)?></td>
                <td><?=number_format($total_act_paid,0)?></td>
                <td><?=number_format($total_qest_val,0)?></td>
                <td><?=number_format($total_sum_all_qest1,0)?></td>
                <td><?=number_format($total_sum_all_qest,0)?></td>
            </tr>
            </tfoot>
        </table>
    </div>
</div>
<div class="col-md-7">
    <div class="signals_first" id="signals_first">
        <div id="chart_campaigns_first"></div>
    </div>
</div>

</div>

<script>

    $(function(){
        $('#txt_from_date_1,#txt_to_date_1').datetimepicker({
            format: 'dd/MM/YYYY',
            pickTime: false
        });
    });

</script>

