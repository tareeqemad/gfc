<?php

/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 28/03/22
 * Time: 09:20 ص
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');

$MODULE_NAME= 'statistics';
$TB_NAME= 'Company_campaigns';

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
            <table class="table" id="campaigns_tb" data-container="container">
                <thead>
                <tr>
                    <th>م</th>
                    <th>المقر</th>
                    <th>عدد المشتركين </th>
                    <th>إجمالي المدفوع </th>

                </tr>
                </thead>
                <tbody>
                <?php foreach($result as $row) :
                    $sub_total += $row['COUNT_SUBSCRIBER'] + $row['SUM_PAID_VALUE'];
                    $total_count_subscriber += $row['COUNT_SUBSCRIBER'];
                    $total_paid_value += $row['SUM_PAID_VALUE'];
                    ?>
                    <tr>
                        <td><?=$count++?></td>
                        <td><?=$row['BRANCH_NAME']?></td>
                        <td><?=$row['COUNT_SUBSCRIBER']?></td>
                        <td><?=number_format($row['SUM_PAID_VALUE'],0)?></td>
                    </tr>
                <?php endforeach;?>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="2">الاجمالي</td>
                    <td><?=$total_count_subscriber?></td>
                    <td><?=number_format($total_paid_value,0)?></td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div class="col-md-7">
        <div class="signals" id="signals">
            <div id="chart_campaigns"></div>
        </div>
    </div>

</div>

<script>

    $(function(){
        $('#txt_from_date_3,#txt_to_date_3').datetimepicker({
            format: 'dd/MM/YYYY',
            pickTime: false
        });
    });

</script>

