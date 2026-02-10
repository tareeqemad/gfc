<?php
$MODULE_NAME = 'internal_jobs';
$TB_NAME = 'Job_ads_request';
$update_status_url = base_url("$MODULE_NAME/$TB_NAME/public_update_status");
$date_attr = " data-type='date' data-date-format='DD/MM/YYYY' data-val='true' data-val-regex-pattern='" . date_format_exp() . "' data-val-regex='Error' ";
?>
<form id="job_form" name="job_form" action="<?= $update_status_url ?>" method="post" role="form"
      novalidate="novalidate">
    <div class="row">
        <input type="hidden" id="h_ser" name="h_ser" value="<?= $rertMain[0]['SER'] ?>">
        <div class="form-group col-md-2">
            <label>الحالة</label>
            <select id="dl_status" name="dl_status" class="form-control">
                <?php foreach ($status_cons as $r) : ?>
                    <option <?= 1 ? ($rertMain[0]['STATUS'] == $r['CON_NO'] ? 'selected' : '') : '' ?>
                            value="<?= $r['CON_NO'] ?>"><?= $r['CON_NAME'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group col-md-6">
            <label>سبب الرفض</label>
            <input type="text" class="form-control" id="txt_notes" name="txt_notes"
                   value="<?= $rertMain[0]['NOTES'] ?>"/>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-12">
            <div class="tr_border">
                <h5 class="text-primary">
                    <u>موعد ونتيجة المقابلة</u>
                </h5>
                <div class="row">
                    <div class="form-group col-md-2">
                        <label>تاريخ المقابلة</label>
                        <input type="text" <?= $date_attr ?> name="interview_date" id="txt_interview_date"
                               value="<?= $rertMain[0]['INTERVIEW_DATE'] ?>"
                               class="form-control">
                    </div>
                    <div class="form-group col-md-2">
                        <label>موعد المقابلة</label>
                        <input type="text" name="interview_hour" id="txt_interview_hour" autocomplete="off"
                               value="<?= $rertMain[0]['INTERVIEW_HOUR'] ?>"
                               class="form-control">
                    </div>
                    <div class="form-group col-md-6">
                        <label>ملاحظة </label>
                        <input type="text" name="interview_notes" id="txt_interview_notes" autocomplete="off"
                               value="<?= $rertMain[0]['INTERVIEW_NOTES'] ?>"
                               class="form-control">
                    </div>
                    <div class="form-group col-md-2">
                        <label>نتيجة المقابلة </label>
                        <input type="text" name="interview_result" id="txt_interview_result" autocomplete="off"
                               value="<?= $rertMain[0]['INTERVIEW_RESULT'] ?>"
                               class="form-control">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn ripple btn-primary" onclick="update_request_data()" id="btn_update_status_note"
                type="button">حفظ
        </button>
        <button class="btn ripple btn-secondary" data-bs-dismiss="modal" type="button">اغلاق</button>
    </div>
</form>

