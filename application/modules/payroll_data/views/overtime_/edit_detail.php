<?php
$MODULE_NAME = 'payroll_data';
$TB_NAME = 'overtime';
//تعديل قيمة الساعات المتجاوزة
$update_calculated_hours_url = base_url("$MODULE_NAME/$TB_NAME/update_calculated_hours");
?>

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
<div class="row">
    <div class="form-group  col-md-2">
        <label>الساعات للتعديل </label>
        <input type="text" name="calculated_hours_ee" id="txt_calculated_hours_ee" class="form-control">
    </div>
</div>
<div class="modal-footer">
    <div class="flex-shrink-0">
        <?php if (HaveAccess($update_calculated_hours_url)) { ?>
            <button type="button" class="btn btn-primary" onclick="update_calculated_hours('<?= $id ?>')">
                تعديل القيمة
            </button>
        <?php } ?>
        <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">اغلاق</button>

    </div>
</div>
