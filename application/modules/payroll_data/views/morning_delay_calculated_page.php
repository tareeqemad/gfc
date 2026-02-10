<?php
$count = $offset;
$MODULE_NAME = 'payroll_data';
$TB_NAME = 'morning_delay';
$get_discount_calculation_url = base_url("$MODULE_NAME/$TB_NAME/discount_calculation"); // احتساب الخصم ادارياً
?>
<div class="table-responsive">
    <table class="table table-bordered" id="page_cal_tb">
        <thead class="table-light">
            <tr>
                <th></th>
                <th>م</th>
                <th>المقر</th>
                <th>رقم الموظف</th>
                <th>الموظف</th>
                <th class="text-center">شهر التأخير</th>
                <th class="text-center">عدد ايام التاخير الكلية</th>
                <th class="text-center">عدد ايام الاعفاء حسب القانون</th>
                <th class="text-center">عدد ايام الاعفاء بعذر</th>
                <th class="text-center">عدد ايام الغياب</th>
                <th class="text-center">عدد ايام الخصم المعتمدة</th>
                <th class="text-center">عدد الساعات المخصومة</th>
                <th> الاعتماد</th>
            </tr>
        </thead>
        <?php if ($page > 1): ?>
            <tr>
                <td colspan="12" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
        <tbody>
        <?php foreach ($page_rows as $row) :
            if (HaveAccess($get_discount_calculation_url) and $row['ADOPT_STATUS'] == -1) {
                $check = "<input  type='checkbox' name='emp_no[]' class='checkboxes' checked  disabled=\"disabled\" value='{$row['EMP_NO']}'  /><input type='hidden' name='the_month[]'  value='{$row['THE_MONTH']}'  />";
            } elseif (HaveAccess($get_discount_calculation_url) and $row['ADOPT_STATUS'] == '') {
                $check = "<input  type='checkbox' name='emp_no[]' class='checkboxes' checked  disabled=\"disabled\" value='{$row['EMP_NO']}'  /><input type='hidden' name='the_month[]'  value='{$row['THE_MONTH']}'  />";
            } else {
                $check = '';
            }
            ?>
            <tr>
                <td> <?= $check ?></td>
                <td> <?= $count ?></td>
                <td><?= $row['BRANCH_NAME'] ?></td>
                <td><?= $row['EMP_NO']?></td>
                <td><?= $row['EMP_NAME'] ?></td>
                <td class="text-center"><?= $row['THE_MONTH']?> </td>
                <td class="text-center"><?= $row['COUNT_TOTAL_ALL'] ?></td>
                <td class="text-center"><?= $row['COUNT_LAW'] ?></td>
                <td class="text-center"><?= $row['COUNT_EXCUSE'] ?></td>
                <td class="text-center"><?= $row['VAC'] ?></td>
                <td class="text-center"><?= $row['DAY'] ?></td>
                <td class="text-center"><?= $row['TOTAL'] ?></td>
                <td class="text-center"> <?= $row['IS_ACTIVE_NAME'] ?></td>
                <?php $count++; ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php echo $this->pagination->create_links(); ?>
<script>
    if (typeof initFunctions == 'function') {
        initFunctions();
    }
    if (typeof ajax_pager == 'function') {
        ajax_pager();
    }
</script>
