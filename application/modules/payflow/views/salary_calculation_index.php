<?php
$MODULE_NAME = 'payflow';
$TB_NAME = 'Salarycalculation';

$start_calculation_url = base_url("$MODULE_NAME/$TB_NAME/calculate_salaries/");  // احتساب الرواتب
$get_progress_url = base_url("$MODULE_NAME/$TB_NAME/get_salary_progress");     // متابعة الاحتساب
$get_page_calculated_url = base_url("$MODULE_NAME/$TB_NAME/review_salary_calculation");  // مراجعة الاحتساب
$get_excel_url = base_url("$MODULE_NAME/$TB_NAME/excel_salary_calculation");
$get_url = base_url("$MODULE_NAME/$TB_NAME/get/");
$confirm_salaries_url = base_url("$MODULE_NAME/$TB_NAME/confirm_salaries");
?>
<!-- PAGE-HEADER -->
<div class="page-header">
    <div>
        <h1 class="page-title"><i class="fa fa-calculator"></i> احتساب الرواتب</h1>
    </div>
    <div class="ms-auto pageheader-btn">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">الرواتب</a></li>
            <li class="breadcrumb-item active" aria-current="page">احتساب الرواتب</li>
        </ol>
    </div>
</div>
<!-- PAGE-HEADER END -->
<!-- MAIN FORM -->
<div class="row">
    <div class="col-lg-12">
        <div class="card shadow">
            <div class="card-header text-white d-flex align-items-center justify-content-between">
                <h3 class="card-title"><i class="fa fa-calculator"></i> احتساب الرواتب - قانون الخدمة المدنية المعدلة</h3>
            </div>
            <div class="card-body">
                <form id="<?= $TB_NAME ?>_form">
                    <div class="row g-3">
                        <?php if ($this->user->branch == 1) { ?>
                            <div class="form-group col-md-2">
                                <label>المقر</label>
                                <select name="branch_no" id="dp_branch_no" class="form-control">
                                    <option value="">-- كل الفروع --</option>
                                    <?php foreach ($branches as $row) : ?>
                                        <option <?= ($this->user->branch == $row['NO'] ? 'selected="selected"' : '') ?>
                                                value="<?= $row['NO'] ?>">
                                            <?= $row['NAME'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <?php } else { ?>
                            <input type="hidden" name="branch_no" id="dp_branch_no" value="<?= $this->user->branch ?>">
                        <?php } ?>
                        <div class="col-md-3">
                            <label class="form-label"><i class="fa fa-user"></i> الموظفين الفعّالين</label>
                            <select name="emp_no" id="dp_emp_list" class="form-control sel2">
                                <option value="ALL">📢 كل الموظفين الفعّالين</option>
                                <?php foreach($emp_list as $row) :?>
                                    <option value="<?=$row['EMP_NO']?>"><?=$row['EMP_NO'].': '.$row['EMP_NAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label"><i class="fa fa-calendar"></i> من شهر</label>
                            <input type="text" id="txt_from_month" class="form-control" placeholder="YYYYMM"
                                   pattern="(19|20)\d{2}(0[1-9]|1[0-2])" title="يجب إدخال الشهر بالصيغة YYYYMM (مثل 202501 أو 202412)"
                                   value="<?= date('Ym', strtotime('last month')) ?>">
                        </div>
                    </div>
                </form>
                <!-- أزرار العمليات -->
                <div class="card-footer bg-light d-flex justify-content-between mt-3">
                    <div>
                        <button type="button" class="btn btn-secondary" onclick="review_salary_calculations();">
                            <i class="fa fa-search"></i> مراجعة الاحتساب
                        </button>
                        <button type="button" onclick="javascript:export_excel();" class="btn btn-success">
                            <i class="fa fa-file-excel-o"></i>
                            تصدير اكسل
                        </button>
                    </div>
                    <div class="btn-group">
                        <button type="button" onclick="start_salary_calculation('full');" class="btn btn-success">
                            <i class="fa fa-calculator"></i> احتساب كامل حسب قانون الخدمة المدنية
                        </button>
                        <button type="button" onclick="start_salary_calculation('ratio');" class="btn btn-warning">
                            <i class="fa fa-user"></i> احتساب جزئي لموظف معين
                        </button>
                        <button type="button" onclick="openPercentageModal();" class="btn btn-info">
                            <i class="fa fa-percent"></i> احتساب حسب النسبة
                        </button>
                        <?php if (HaveAccess($confirm_salaries_url)) { ?>
                        <button type="button" class="btn btn-primary" onclick="confirm_salaries();">
                            <i class="fa fa-check"></i> ترحيل الرواتب (اعتماد)
                        </button>
                        <?php } ?>
                    </div>
                </div>

                <hr>
                <!-- قسم عرض النتائج -->
                <div class="col-lg-12">
                    <div class="card shadow">
                        <div class="card-header text-white d-flex align-items-center justify-content-between">
                            <h3 class="card-title"><i class="fa fa-calculator"></i> النتائج</h3>
                        </div>
                        <div class="card-body" id="resultsCard">
                            <?= modules::run($get_page_calculated_url, $page); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Partial Days Modal -->
<div class="modal fade" id="partialDaysModal" tabindex="-1" aria-labelledby="partialDaysModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title"><i class="fa fa-calendar"></i> إدخال عدد الأيام - الاحتساب الجزئي</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <div class="modal-body">
                <p class="text-warning"><i class="fa fa-info-circle"></i> ملاحظة: يمكن الاحتساب الجزئي لموظف واحد فقط بعد أن تقوم باحتساب راتبه الكامل.</p>

                <form id="partialDaysForm">
                    <div class="mb-3">
                        <label for="partialRateInput" class="form-label">💯 نسبة الاحتساب (%)</label>
                        <input type="number" id="partialRateInput" class="form-control" placeholder="أدخل النسبة" min="1" max="100" required>
                    </div>
                    <div class="mb-3">
                        <label for="partialDaysInput" class="form-label">📅 أدخل عدد الأيام:</label>
                        <input type="number" id="partialDaysInput" class="form-control" placeholder="عدد الأيام" min="1" max="31" required>
                    </div>
                    <div class="mb-3">
                        <label for="partialLValueInput" class="form-label">📉 الحد الأدنى للقيمة</label>
                        <input type="number" id="partialLValueInput" class="form-control" placeholder="أدخل الحد الأدنى" min="1400" value="1400" required>
                    </div>
                    <div class="mb-3">
                        <label for="partialHValueInput" class="form-label">📈 الحد الأعلى للقيمة</label>
                        <input type="number" id="partialHValueInput" class="form-control" placeholder="أدخل الحد الأعلى" min="1400" value="3400" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-warning" onclick="submitPartialCalculation();">تأكيد الاحتساب الجزئي</button>
            </div>
        </div>
    </div>
</div>


<!-- Percentage Calculation Modal -->
<div class="modal fade" id="percentageModal" tabindex="-1" aria-labelledby="percentageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fa fa-percent"></i> احتساب حسب النسبة - إدخال تفاصيل الاحتساب</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <div class="modal-body">
                <p class="text-warning"><i class="fa fa-info-circle"></i> ملاحظة: الحد الأدنى للقيمة يجب أن يكون 1400 والحد الأعلى أكبر من الحد الأدنى.</p>

                <form id="percentageForm">
                    <div class="mb-3">
                        <label for="percentageRateInput" class="form-label">💯 نسبة الاحتساب (%)</label>
                        <div class="input-group">
                            <select class="form-select" onchange="handlePercentageChange($(this).val());" id="percentageSelector">
                                <option value="">اختر نسبة</option>
                                <option value="100">100%</option>
                                <option value="65">65%</option>
                                <option value="0">0%</option>
                            </select>
                            <input type="number" id="percentageRateInput" class="form-control" placeholder="أدخل النسبة" min="1" max="100" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="percentageLValueInput" class="form-label">📉 الحد الأدنى للقيمة</label>
                        <div class="input-group">
                            <input type="number" id="percentageLValueInput" class="form-control" placeholder="أدخل الحد الأدنى" min="1400" value="1400" required>
                            <button type="button" class="btn btn-outline-secondary" onclick="document.getElementById('percentageLValueInput').value = 0">صفر</button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="percentageHValueInput" class="form-label">📈 الحد الأعلى للقيمة</label>
                        <div class="input-group">
                            <input type="number" id="percentageHValueInput" class="form-control" placeholder="أدخل الحد الأعلى" min="1400" value="3400" required>
                            <button type="button" class="btn btn-outline-secondary" onclick="document.getElementById('percentageHValueInput').value = 10000">10000</button>
                        </div>
                    </div>
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-primary" onclick="submitPercentageCalculation();">تأكيد</button>
            </div>
        </div>
    </div>
</div>


<!-- Result Modal -->
<div class="modal fade" id="resultModal" tabindex="-1" aria-labelledby="resultModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fa fa-check-circle"></i> تم نجاح العملية</h5>
            </div>
            <div class="modal-body text-center">
                <h4 class="text-success">✅ تم احتساب الرواتب بنجاح!</h4>
                <p>💰 تمت معالجة جميع الموظفين المطلوبين.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">موافق</button>
            </div>
        </div>
    </div>
</div>

<!-- Progress Modal -->
<div class="modal fade" id="progressModal" tabindex="-1" aria-labelledby="progressModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title"><i class="fa fa-spinner fa-spin"></i> جاري معالجة البيانات...</h5>
            </div>
            <div class="modal-body text-center">
                <p><strong>🔢 عدد الموظفين الكلي:</strong> <span id="totalEmployees">0</span></p>
                <p><strong>⚡ تمت المعالجة لـ:</strong> <span id="processedEmployees">0</span> موظف</p>
                <div class="progress mt-3">
                    <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated bg-primary"
                         role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                        0%
                    </div>
                </div>
                <p class="text-danger mt-3" id="errorMessage"></p>
            </div>
        </div>
    </div>
</div>

<?php
$scripts = <<<SCRIPT
<script>
 
 $(document).ready(function () {
    // تهيئة Select2 لعناصر الاختيار المتعددة
    $('.sel2').select2({ 
        placeholder: "🔍 ابحث عن الموظف...",
        allowClear: false,
        width: '100%' 
    });

    // تهيئة DateTimePicker لإدخال الشهر المطلوب
    $('#txt_from_month').datetimepicker({ 
        format: 'YYYYMM',
        minViewMode: 'months',
        pickTime: false 
    });

    // التحقق عند اختيار الموظفين من القائمة
    $('#dp_emp_list').on('change', function () {
        let selected = $(this).val();
        
        if (!selected || selected.length === 0) {
            showErrorMsg('⚠️ يجب اختيار موظفين.');
        }

        if (selected && selected.includes('ALL') && selected.length > 1) {
            $(this).val(['ALL']).trigger('change');
            showErrorMsg('⚠️ لا يمكنك تحديد "كل الموظفين" مع موظفين آخرين!');
        }
    });

    // إعادة تعيين النماذج عند إغلاق أي `Modal`
    $('#partialDaysModal, #percentageModal').on('hidden.bs.modal', function () {
        $(this).find('form')[0].reset();
    });
});

/* =========================== */
/*      دوال الاحتساب الجزئي   */
/* =========================== */

function openPartialDaysModal() {
    $('#partialDaysModal').modal('show');
}

 

function submitPartialCalculation() {
    let days = parseInt($('#partialDaysInput').val());
    let rate = parseFloat($('#partialRateInput').val());
    let l_value = parseFloat($('#partialLValueInput').val());
    let h_value = parseFloat($('#partialHValueInput').val());

    if (isNaN(days) || days < 1 || days > 31) {
        showErrorMsg("⚠️ يرجى إدخال عدد أيام صالح بين 1 و 31.");
        return;
    }
    if (isNaN(rate) || rate < 1 || rate > 100) {
        showErrorMsg("⚠️ يرجى إدخال نسبة احتساب بين 1 و 100.");
        return;
    }
    if (isNaN(l_value) || l_value < 0) {
        showErrorMsg("⚠️ الحد الأدنى يجب أن يكون 0 أو أكثر.");
        return;
    }
    if (isNaN(h_value) || (h_value !== 0 && h_value <= l_value)) {
        showErrorMsg("⚠️ الحد الأعلى يجب أن يكون أكبر من الحد الأدنى، إلا إذا كان 0.");
        return;
    }

    $('#partialDaysModal').modal('hide');

    start_salary_calculation('ratio', rate, l_value, h_value, days);
}

/* =========================== */
/*   دوال الاحتساب بالنسبة    */
/* =========================== */

function openPercentageModal() {
    $('#percentageModal').modal('show');
}

function handlePercentageChange(value) {
    $('#percentageRateInput').val(value);

    if (value == "0") {
        $('#percentageLValueInput').val(0);
        $('#percentageHValueInput').val(0);
    }
}

function submitPercentageCalculation() {
    let rate = parseFloat($('#percentageRateInput').val());
    let l_value = parseFloat($('#percentageLValueInput').val());
    let h_value = parseFloat($('#percentageHValueInput').val());

    
    if (isNaN(l_value) || l_value < 0) {
        showErrorMsg("⚠️ الحد الأدنى يجب أن يكون 0 أو أكثر.");
        return;
    }
    if (isNaN(h_value) || (h_value !== 0 && h_value <= l_value)) {
        showErrorMsg("⚠️ الحد الأعلى يجب أن يكون أكبر من الحد الأدنى، إلا إذا كان 0.");
        return;
    }

    $('#percentageModal').modal('hide');

    start_salary_calculation('percentage', rate, l_value, h_value);
}


/* ================================= */
/*    دوال الاحتساب ومتابعة التقدم    */
/* ================================= */

function resetProgressUI() {
    clearInterval(processingInterval);
    $('#progressBar').css('width', '0%').text('0%');
    $('#processedEmployees, #totalEmployees').text('0');
    $('#errorMessage').html('');
}

function start_salary_calculation(type, rate = null, l_value = null, h_value = null, days = null, callback = null) {
    let from_month = $('#txt_from_month').val();
    let selectedEmployees = $('#dp_emp_list').val();

    // ✅ التحقق من صحة إدخال الشهر (YYYYMM)
    if (!from_month || !/^\d{6}$/.test(from_month)) {
        showErrorMsg('⚠️ يرجى إدخال الشهر بالصيغة الصحيحة (YYYYMM).');
        return;
    }

    // ✅ التأكد من اختيار الموظفين
    if (!selectedEmployees || selectedEmployees.length === 0) {
        showErrorMsg('⚠️ يجب اختيار موظفين.');
        return;
    }

    let emp_data = selectedEmployees.includes('ALL') ? 'ALL' : selectedEmployees;
    let url = "$start_calculation_url" + type;

    let requestData = {
        from_month: parseInt(from_month, 10), // تحويل الشهر إلى `Number`
        emp_no: emp_data
    };

    // ✅ التعامل مع الحساب الجزئي (Partial - Ratio)
    if (type === 'ratio') {
        if (days === null || rate === null || l_value === null || h_value === null) {
            openPartialDaysModal();
            return;
        }
        requestData.partial_days = parseInt(days, 10);
        requestData.partial_rate = parseFloat(rate);
        requestData.partial_l_value = parseFloat(l_value);
        requestData.partial_h_value = parseFloat(h_value);
    }

    // ✅ التعامل مع الحساب بالنسبة (Percentage)
    if (type === 'percentage') {
        if (rate === null || l_value === null || h_value === null) {
            openPercentageModal();
            return;
        }
        requestData.percentage_rate = parseFloat(rate);
        requestData.percentage_l_value = parseFloat(l_value);
        requestData.percentage_h_value = parseFloat(h_value);
    }

    // ✅ إعادة ضبط واجهة المستخدم وإظهار شريط التقدم
    resetProgressUI();
    $('#progressModal').modal('show');

    // ✅ إرسال الطلب إلى السيرفر والتحقق من الاستجابة
    get_datan(url, requestData, function (response) {
        if (response.status === 'success') {
            $('#totalEmployees').text(response.total);
            track_calculation_progress(from_month, response.total);
        } else {
            failProcess(response.message || "⚠️ فشل الاحتساب بدون تفاصيل.");
        }

        // ✅ تنفيذ `callback()` في حال كان موجودًا
        if (callback) callback();
    });
}



let processingInterval;
function track_calculation_progress(month, totalEmployees) {
    let maxDuration = 60000; // حد أقصى 60 ثانية
    let startTime = Date.now();

    clearInterval(processingInterval);

    processingInterval = setInterval(() => {
        if (Date.now() - startTime > maxDuration) {
            clearInterval(processingInterval);
            failProcess("⚠️ انتهت مهلة العملية. السيرفر لم يرد بالوقت المحدد.");
            return;
        }

        get_datan('$get_progress_url', { month }, function (response) {
            if (!response || response.status === 'error') {
                clearInterval(processingInterval); // 🛑 مهمة: وقف التكرار
                failProcess(response ? response.message : "❌ حدث خطأ غير معروف في التقدم.");
                return;
            }

            let processedEmployees = Number(response.processed) || 0;
            let percentage = ((processedEmployees / totalEmployees) * 100).toFixed(0);
            percentage = Math.min(100, percentage);

            $('#processedEmployees').text(processedEmployees);
            $('#progressBar').css('width', percentage + '%').text(percentage + '%');

            if (processedEmployees >= totalEmployees) {
                clearInterval(processingInterval);
                finalize_process();
            }
        });
    }, 2000);
}

function failProcess(message) {
    clearInterval(processingInterval);
    $('#progressModal').modal('hide');
    showErrorMsg(message);
}

function finalize_process() {
    $('#progressModal').modal('hide');
    setTimeout(() => {
        $('#resultModal').modal('show');
        review_salary_calculations();
    }, 500);
}
  

// دالة عرض نتائج الاحتساب
function review_salary_calculations() {
    get_datan('{$get_page_calculated_url}', {
        page: 1,
        month: $('#txt_from_month').val(),
        emp_no: $('#dp_emp_list').val()
    }, function (data) {
        $('#resultsCard').html(data);
    }, 'html');
}

function show_row_details(emp_no, month) {
    get_to_link('{$get_url}' + emp_no + '/' + month);
}


function export_excel() {
    var branch_no = $('#dp_branch_no').val();
    var month = $('#txt_from_month').val();
    var emp_no = $('#dp_emp_list').val();

    // ✅ التأكد من القيم المطلوبة فقط (الفرع اختياري)
    if (!month || !emp_no) {
        info_msg('خطأ', 'يرجى ملء الحقول المطلوبة قبل تصدير الملف.');
        return;
    }

    // ✅ ترميز القيم لمنع حدوث مشاكل في `URL`
    var export_url = '{$get_excel_url}?month=' + encodeURIComponent(month) +
                     '&emp_no=' + encodeURIComponent(emp_no);

    // ✅ إضافة الفرع فقط إذا كان له قيمة
    if (branch_no) {
        export_url += '&branch_no=' + encodeURIComponent(branch_no);
    }

    // ✅ إظهار رسالة التنبيه للمستخدم
    info_msg('تنبيه', 'جاري تجهيز ملف الاكسل ..');

    // ✅ تنفيذ الاستدعاء
    window.location.href = export_url;

    // ✅ تنبيه احتياطي في حال لم يبدأ التحميل
    setTimeout(function() {
        info_msg('تنبيه', 'إذا لم يبدأ التنزيل، الرجاء إعادة المحاولة.');
    }, 5000);
}



$(function(){
    reBind();
});

function reBind(){
    ajax_pager({
        month: $('#txt_from_month').val(),
        emp_no: $('#dp_emp_list').val()
    });
}

function LoadingData(){
    ajax_pager_data('#page_tb > tbody',{
        month: $('#txt_from_month').val(), emp_no: $('#dp_emp_list').val()
    });
}

function confirm_salaries() {
    let from_month = $('#txt_from_month').val();

    if (!from_month || !/^\d{6}$/.test(from_month)) {
        showErrorMsg('⚠️ يرجى إدخال الشهر بالصيغة الصحيحة (YYYYMM).');
        return;
    }

    let url = "$confirm_salaries_url";

    let requestData = {
        from_month: from_month
    };

    if (!confirm("هل أنت متأكد أنك تريد ترحيل الرواتب لهذا الشهر؟")) {
        return;
    }

    get_datan(url, requestData, function (response) {
        if (response.status === 'success') {
            showSuccessMsg('✅ تم ترحيل الرواتب بنجاح!');
            review_salary_calculations();
        } else {
            showErrorMsg(response.message || "⚠️ فشل ترحيل الرواتب!");
        }
    });
}

</script>
SCRIPT;
sec_scripts($scripts);
?>
