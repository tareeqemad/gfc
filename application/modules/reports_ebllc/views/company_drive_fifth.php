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
        <table class="table" id="campaigns_fifth_tb" data-container="container">
            <thead>
            <tr>
                <th>م</th>
                <th>المقر</th>
                <th>عدد المنتسبين</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($result as $row) :
                $total_count_subscriber += $row['COUNT_SUB'];
                ?>
                <tr>
                    <td><?=$count++?></td>
                    <td><?=$row['BRANCH_NAME']?></td>
                    <td><?=$row['COUNT_SUB']?></td>
                </tr>
            <?php endforeach;?>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="2">الاجمالي</td>
                <td><?=$total_count_subscriber?></td>
            </tr>
            </tfoot>
        </table>
    </div>
</div>
<div class="col-md-7">
    <div class="signals_fifth" id="signals_fifth">
        <div id="chart_campaigns_fifth"></div>
    </div>
</div>

</div>

<script>

    $(function(){
        $('#txt_from_date_5,#txt_to_date_5').datetimepicker({
            format: 'dd/MM/YYYY',
            pickTime: false
        });
    });

</script>

