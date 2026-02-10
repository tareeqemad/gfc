<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

$count = 0;
$total_approved_amount = 0;
$total_total_price_class = 0;
$total_pay = 0;
?>

<div class="tbl_container">
    <table class="table" data-container="container">
        <thead>
        <tr>
            <th style="width: 120px">المستخلصات</th>
            <th style="width:150px "></th>

        </tr>
        </thead>
        <tbody>

        <?php foreach ($rec_details as $row) : ?>
            <?php $total_pay = $row['PAY'] + $total_pay;
            ?>
            <tr>
            <tr>
                <td>المستخلص <?= $row['EXTRACT_SER'] ?></td>
                <td><?= $row['PAY'] ?></td>
            </tr>
        <?php endforeach; ?>

        </tbody>
        <tfoot>
        <tr>

            <th rowspan="1">
                اجمالي الدفعات
            </th>
            <th rowspan="1">
                <?= $total_pay ?>
            </th>

        </tfoot>
    </table>

</div>


<script>
    if (typeof initFunctions == 'function') {
        initFunctions();
    }


</script>


