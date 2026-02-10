<div class="row">
    <div class="form-group  col-md-2">
        <label> رقم الموظف </label>
        <input type="text" readonly name="emp_no_m" id="txt_emp_no_m" class="form-control"
               value="<?= $rertMain[0]['EMP_NO'] ?>">
    </div>
    <div class="form-group  col-md-3">
        <label> اسم الموظف </label>
        <input type="text" readonly name="emp_name_m" id="txt_emp_name_m" class="form-control"
               value="<?= $rertMain[0]['EMP_NAME'] ?>">
    </div>
    <div class="form-group  col-md-2">
        <label>الشهر </label>
        <input type="text" readonly name="month_m" id="txt_month_m" class="form-control"
               value="<?= $rertMain[0]['MONTH'] ?>">
    </div>
    <div class="form-group  col-md-2">
        <label>الساعات </label>
        <input type="text" readonly name="calculated_hours_m" id="txt_calculated_hours_m" class="form-control"
               value="<?= $rertMain[0]['CALCULATED_HOURS'] ?>">
    </div>
    <div class="form-group  col-md-2">
        <label>المبلغ الفعلي </label>
        <input type="text" readonly name="val_adopt_branch_m" id="txt_val_adopt_branch_m" class="form-control"
               value="<?= $rertMain[0]['VAL_ADOPT_BRANCH'] ?>">
    </div>
</div>
<div class="table-responsive">
    <table class="table table-bordered" id="adopt_page_tb">
        <thead class="table-light">
        <tr>
            <th>الجهة المعتمدة</th>
            <th>اسم المعتمد</th>
            <th>تاريخ الاعتماد</th>
            <th>ملاحظة الاعتماد</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($rertMainAdopt as $rows) { ?>
            <tr>
                <td><?= $rows['ADOPT_NAME'] ?></td>
                <td><?= $rows['ADOPT_USER_NAME'] ?></td>
                <td><?= $rows['ADOPT_DATE'] ?></td>
                <td><?= $rows['NOTE'] ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
