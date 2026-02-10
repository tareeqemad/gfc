<div class="row">
    <div class="col-md-2">
    </div>
    <div class="col-md-8">
        <h3 class="text-center text-bold text-info"> اجمالي جميع المقرات</h3>
        <hr>
        <div class="table-responsive">
            <table class="table  table-bordered" id="total_emp_tb">
                <thead class="table-info">
                <tr>
                    <th class="text-center">اجمالي موظفين شهر <?= $curr_month ?></th>
                    <th class="text-center">اجمالي موظفين شهر <?= $prev_month ?></th>
                    <th class="text-center">الزيادة </th>
                    <th class="text-center">النقص </th>
                    <th class="text-center">المبلغ المرسل للبنك <?= $curr_month ?></th>
                    <th class="text-center">المبلغ المرسل للبنك <?= $prev_month ?></th>
                    <th class="text-center">الزيادة</th>
                    <th class="text-center">النقص</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($Total_Emp_arr as $t) { ?>
                    <tr ondblclick="get_diff_emp(<?= $curr_month ?>)">
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
    <div class="col-md-4" id="add_fixed_body">
        <h4 class="text-success text-center text-bold">بنود الاستحقاق الثابت</h4>
        <div class="table-responsive">
            <table class="table table-bordered" id="fixed_add_clauses_tb">
                <thead style="background-color: #c7f1da;">
                <tr>
                    <th style="width: 2%">م</th>
                    <th style="width: 8%">البند</th>
                    <th style="width: 8%"><?= $curr_month ?></th>
                    <th style="width: 8%"><?= $prev_month ?></th>
                    <th style="width: 4%">الزيادة</th>
                    <th style="width: 4%">النقص</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $counter_add = 1;
                $total_f_curr_add = 0;
                $total_f_prev_add = 0;
                $total_f_add_p = 0;
                $total_f_add_m = 0;
                foreach ($Sal_Fixed_arr as $f) {

                    $total_f_curr_add += $f['SUM_FIXED_ADD'];
                    $total_f_prev_add += $f['TOTAL_PREV_FIXED'];
                    $total_f_add_p += $f['DIFF_FIXED_POSTIVE'];
                    $total_f_add_m += $f['DIFF_FIXED_MINUS'];
                    ?>
                    <tr ondblclick="javascript:get_diff_sal_val('<?=$curr_month?>','<?=$f['CON_NO']?>')">
                        <td><?= $counter_add++; ?></td>
                        <td><?= $f['NAME'] ?></td>
                        <td><?= number_format($f['SUM_FIXED_ADD'], 2) ?></td>
                        <td><?= number_format($f['TOTAL_PREV_FIXED'], 2) ?></td>
                        <td <?php if ($f['DIFF_FIXED_POSTIVE']) { echo 'class="text-success"' ;} else { echo 'class="text-black"';}?>><?= number_format($f['DIFF_FIXED_POSTIVE'] ,2)?></td>
                        <td <?php if ($f['DIFF_FIXED_MINUS']) { echo 'class="text-danger"' ;} else { echo 'class="text-black"';}?>><?= number_format($f['DIFF_FIXED_MINUS'],2) ?></td>
                    </tr>
                <?php } ?>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="2"></td>
                    <td><?= number_format($total_f_curr_add, 2) ?></td>
                    <td><?= number_format($total_f_prev_add, 2) ?></td>
                    <td class="text-success"><?= number_format($total_f_add_p, 2) ?></td>
                    <td class="text-danger"><?= number_format($total_f_add_m, 2) ?></td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div class="col-md-4" id="add_change_body">
        <h4 class="text-center text-bold" style="color: #4f6dcb;">بنود الاستحقاق المتغير</h4>
        <div class="table-responsive">
            <table class="table  table-bordered" id="change_add_clauses_tb">
                <thead class="table-light">
                <tr>
                    <th style="width: 2%">م</th>
                    <th style="width: 8%">البند</th>
                    <th style="width: 8%"><?= $curr_month ?></th>
                    <th style="width: 8%"><?= $prev_month ?></th>
                    <th style="width: 4%">الزيادة</th>
                    <th style="width: 4%">النقص</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $counter_c_add = 1;
                $total_c_curr_add = 0;
                $total_c_prev_add = 0;
                $total_c_add_p = 0;
                $total_c_add_m = 0;
                foreach ($Sal_Change_arr as $c) {
                    $total_c_curr_add += $c['SUM_CHANGE_ADD'];
                    $total_c_prev_add += $c['TOTAL_PREV_CHANGE'];
                    $total_c_add_p += $c['DIFF_CHANGE_POSTIVE'];
                    $total_c_add_m += $c['DIFF_CHANGE_MINUS'];
                    ?>
                    <tr  ondblclick="javascript:get_diff_sal_val('<?=$curr_month?>','<?=$c['CON_NO']?>')">
                        <td><?= $counter_c_add++; ?></td>
                        <td><?= $c['NAME'] ?></td>
                        <td><?= number_format($c['SUM_CHANGE_ADD'], 2) ?></td>
                        <td><?= number_format($c['TOTAL_PREV_CHANGE'], 2) ?></td>
                        <td <?php if ($c['DIFF_CHANGE_POSTIVE']) { echo 'class="text-success"' ;} else { echo 'class="text-black"';}?>><?= number_format($c['DIFF_CHANGE_POSTIVE'],2) ?></td>
                        <td <?php if ($c['DIFF_CHANGE_MINUS']) { echo 'class="text-danger"' ;} else { echo 'class="text-black"';}?>><?= number_format($c['DIFF_CHANGE_MINUS'],2) ?></td>
                    </tr>
                <?php } ?>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="2"></td>
                    <td><?= number_format($total_c_curr_add, 2) ?></td>
                    <td><?= number_format($total_c_prev_add, 2) ?></td>
                    <td class="text-success"><?=number_format($total_c_add_p,2)?></td>
                    <td class="text-danger"><?=number_format($total_c_add_m,2)?></td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div class="col-md-4">
        <h5 class="text-danger text-center text-bold">بنود الاستقطاع</h5>
        <div class="table-responsive">
            <table class="table table-bordered" id="ded_clauses_tb">
                <thead style="background-color: #ffdbdb;">
                <tr>
                    <th style="width: 2%">م</th>
                    <th style="width: 8%">البند</th>
                    <th style="width: 8%"><?= $curr_month ?></th>
                    <th style="width: 8%"><?= $prev_month ?></th>
                    <th style="width: 4%">الزيادة</th>
                    <th style="width: 4%">النقص</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $counter_ded = 1;
                $total_curr_ded = 0;
                $total_prev_ded = 0;
                $total_ded_p = 0;
                $total_ded_m = 0;
                foreach ($Sal_Ded_arr as $d) {
                    $total_curr_ded += $d['SUM_DEDUCTION'];
                    $total_prev_ded += $d['TOTAL_PREV_DEDUCTION'];
                    $total_ded_p += $d['DIFF_DED_POSTIVE'];
                    $total_ded_m += $d['DIFF_DED_MINUS'];
                    ?>
                    <tr ondblclick="javascript:get_diff_sal_val('<?=$curr_month?>','<?=$d['CON_NO']?>')">
                        <td><?= $counter_ded++; ?></td>
                        <td><?= $d['NAME'] ?></td>
                        <td><?= number_format($d['SUM_DEDUCTION'], 2) ?></td>
                        <td><?= number_format($d['TOTAL_PREV_DEDUCTION'], 2) ?></td>
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
                    <td class="text-danger"><?=number_format($total_ded_m,2)?></td>
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