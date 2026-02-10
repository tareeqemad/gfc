<div class="row">
    <div class="col-md-2">
    </div>
    <div class="col-md-8">
        <h3 class="text-center text-bold text-info"> اجمالي جميع المقرات</h3>
        <hr>
        <div class="table-responsive">
            <table class="table table-bordered" id="total_emp_tb">
                <thead class="table-info">
                <tr>
                    <th class="text-center">اجمالي موظفين شهر <?= $curr_month ?></th>
                    <th class="text-center">اجمالي موظفين شهر <?= $prev_month ?></th>
                    <th class="text-center">الزيادة <?= $prev_month ?></th>
                    <th class="text-center">النقص <?= $prev_month ?></th>
                    <th class="text-center">المبلغ المرسل للبنك <?= $curr_month ?></th>
                    <th class="text-center">المبلغ المرسل للبنك <?= $prev_month ?></th>
                    <th class="text-center">الزيادة</th>
                    <th class="text-center">النقص</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($Total_Emp_arr as $t) { ?>
                    <tr onclick="get_diff_emp(<?= $curr_month ?>)">
                        <td class="text-center"><?= $t['EMP_CNT_CURR_MONTH'] ?></td>
                        <td class="text-center"><?= $t['EMP_CNT_PREV_MONTH'] ?></td>
                        <td class="text-center text-success"><?= $t['EMP_CNT_POSTIVE'] ?></td>
                        <td class="text-center text-danger"><?= $t['EMP_CNT_MINUS'] ?></td>
                        <td class="text-center"><?= number_format($t['TOTAL_NET_CURR_MONTH'] ,2)?></td>
                        <td class="text-center"><?= number_format($t['TOTAL_NET_PREV_MONTH'],2) ?></td>
                        <td class="text-center text-success"><?= number_format($t['NET_POSTIVE'],2) ?></td>
                        <td class="text-center text-danger"><?= number_format($t['NET_MINUS'],2) ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-2">
    </div>
</div>
<br>
<div class="row">
    <div class="col-md-6" id="add_body">
        <h4 class="text-success text-center text-bold">بنود الاستحقاق</h4>
        <div class="table-responsive">
            <table class="table  table-bordered table-striped" id="add_clauses_tb">
                <thead style="background-color: #c7f1da;">
                <tr>
                    <th>م</th>
                    <th>البند المالي</th>
                    <th>اجمالي شهر <?= $curr_month ?></th>
                    <th>اجمالي شهر <?= $prev_month ?></th>
                    <th>الزيادة</th>
                    <th>النقص</th>
                </tr>
                </thead>
                <tbody>
                <?php $counter_add = 1;
                $total_curr_add = 0;
                $total_prev_add = 0;
                $total_add_p = 0;
                $total_add_m = 0;
                foreach ($Add_Clauses_arr as $a) {
                    $total_curr_add += $a['TOTAL_CURR_ADD'];
                    $total_prev_add += $a['TOTAL_PREV_ADD'];
                    $total_add_p += $a['DIFF_ADD_POSTIVE'];
                    $total_add_m += $a['DIFF_ADD_MINUS'];
                    ?>
                    <tr>
                        <td><?= $counter_add++; ?></td>
                        <td><?= $a['C_NAME'] ?></td>
                        <td><?= number_format($a['TOTAL_CURR_ADD'], 2) ?></td>
                        <td><?= number_format($a['TOTAL_PREV_ADD'], 2) ?></td>
                        <td <?php if ($a['DIFF_ADD_POSTIVE']) { echo 'class="text-success"' ;} else { echo 'class="text-black"';}?>><?= number_format($a['DIFF_ADD_POSTIVE'], 2) ?></td>
                        <td <?php if ($a['DIFF_ADD_MINUS']) { echo 'class="text-danger"' ;} else { echo 'class="text-black"';}?>><?= number_format($a['DIFF_ADD_MINUS'], 2) ?></td>
                    </tr>
                <?php } ?>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="2"></td>
                    <td><?= number_format($total_curr_add, 2) ?></td>
                    <td><?= number_format($total_prev_add, 2) ?></td>
                    <td class="text-success"><?=number_format($total_add_p,2)?></td>
                    <td class="text-danger"><?=number_format($total_add_m,2)?></td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div class="col-md-6" id="ded_body">
        <h4 class="text-danger text-center text-bold">بنود الاستقطاع</h4>
        <div class="table-responsive">
            <table class="table table-bordered" id="ded_clauses_tb">
                <thead style="background-color: #ffdbdb;">
                <tr>
                    <th>م</th>
                    <th>البند</th>
                    <th>اجمالي شهر <?= $curr_month ?></th>
                    <th>اجمالي شهر <?= $prev_month ?></th>
                    <th>الزيادة</th>
                    <th>النقص</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $counter_ded = 1;
                $total_curr_ded = 0;
                $total_ded_p = 0;
                $tota_ded_m = 0;
                foreach ($Ded_Clauses_arr as $d) {
                    $total_curr_ded += $d['TOTAL_CURR_DED'];
                    $total_prev_ded += $d['TOTAL_PREV_DED'];
                    $total_ded_p += $d['DIFF_DED_POSTIVE'];
                    $tota_ded_m += $d['DIFF_DED_MINUS'];
                    ?>
                    <tr>
                        <td><?= $counter_ded++; ?></td>
                        <td><?= $d['C_NAME'] ?></td>
                        <td><?= number_format($d['TOTAL_CURR_DED'], 2) ?></td>
                        <td><?= number_format($d['TOTAL_PREV_DED'], 2) ?></td>
                        <td <?php if ($d['DIFF_DED_POSTIVE']) { echo 'class="text-success"' ;} else { echo 'class="text-black"';}?>><?= number_format($d['DIFF_DED_POSTIVE'], 2) ?></td>
                        <td <?php if ($d['DIFF_DED_MINUS']) { echo 'class="text-danger"' ;} else { echo 'class="text-black"';}?>><?= number_format($d['DIFF_DED_MINUS'], 2) ?></td>
                    </tr>
                <?php } ?>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="2"></td>
                    <td><?= number_format($total_curr_ded, 2) ?></td>
                    <td><?= number_format($total_prev_ded, 2) ?></td>
                    <td class="text-success"><?=number_format($total_ded_p,2)?></td>
                    <td class="text-danger"><?=number_format($tota_ded_m,2)?></td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<script>
    if (typeof initFunctions == 'function') {
        initFunctions();
    }
</script>