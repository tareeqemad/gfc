<div class="form-row">
    <div class="form-group  col-md-2">
        <label>الموظف </label>
        <input type="text" readonly class="form-control" id="txt_no" name="no"
               value="<?= $emp_no ?>">
    </div>
    <div class="form-group  col-md-2">
        <label> رقم الباركود </label>
        <input type="text" readonly class="form-control" id="txt_barcode_e" name="barcode"
               value="<?= $barcode ?>">
    </div>
    <div class="form-group  col-md-3">
        <label> شهر القرار </label>
        <input type="text" class="form-control" id="txt_month_des" name="month_des"
               value="<?= $data_folder[0]['MONTH_DES'] ?>" autocomplete="off">
    </div>
    <div class="form-group  col-md-5">
        <label> اسم الملف </label>
        <input type="text" class="form-control" id="txt_folder_name" name="folder_name"
               value="<?= $data_folder[0]['FOLDER_NAME'] ?>">
    </div>
</div>
<input type="hidden" name="h_action" id="txt_h_action" value="<?= $action ?>">
<div class="form-row">

    <div class="form-group  col-md-3">
        <label> ملاحظة </label>
        <input type="text" class="form-control" id="txt_notes" name="notes"
               value="<?= $data_folder[0]['NOTES'] ?>">
    </div>
</div>
<div class="form-row">
        <button type="button" class="btn btn-primary" onclick="javascript:saveFolderName()">
            حفظ البيانات
        </button>
</div>
<script>
    $('#txt_month_des').datetimepicker({
        format: 'YYYYMM',
        minViewMode: 'months',
        pickTime: false,
    });
</script>

