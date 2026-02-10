<?php
$MODULE_NAME = 'hr';
$TB_NAME = 'Archive_scan';
$edit_detail_url = base_url("$MODULE_NAME/$TB_NAME/public_get_edit_detail");
$scan_type_con = $this->rmodel->getList('SCAN_TYPE_LIST', '', 0, 200);
?>
<div class="card-border">
    <div class="row">
        <div class="form-group  col-md-2">
            <label class="control-label"> رقم الموظف </label>
            <input type="text" readonly name="emp_no_m" id="txt_emp_no_m" class="form-control"
                   value="<?= $rertMain[0]['EMP_NO'] ?>">
        </div>
        <div class="form-group col-md-4">
            <label>نوع الملف</label>
            <select name="scan_type_m" id="dp_scan_type_m" class="form-control sel2">
                <?php foreach ($scan_type_con as $row) : ?>
                    <option <?php if ($rertMain[0]['TYPE'] == $row['TYPE_CODE']) echo 'selected' ?>

                            value="<?= $row['TYPE_CODE'] ?>"><?= $row['TYPE_NAME'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group col-md-4">
            <label>اصدار الملف</label>
            <select name="version_no_m" id="dp_version_no_m" class="form-control">
                <option <?php if ($rertMain[0]['VERSION_NO'] == 1) echo 'selected' ?> value="1">قديم</option>
                <option <?php if ($rertMain[0]['VERSION_NO'] == 2) echo 'selected' ?> value="2">جديد</option>
            </select>
        </div>

    </div>
    <div class="modal-footer">
        <div class="flex-shrink-0">
            <button type="button" class="btn  btn-primary" onclick="update_records('<?= $barcode ?>')">
                تعديل
            </button>
            <button type="button" class="btn ripple btn-secondary" data-bs-dismiss="modal" >اغلاق</button>

        </div>
    </div>
</div>
