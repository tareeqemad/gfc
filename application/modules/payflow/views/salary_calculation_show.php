<?php

$MODULE_NAME = 'payflow';
$TB_NAME = 'Salarycalculation';
$index_url = base_url("$MODULE_NAME/$TB_NAME/public_index");
$get_url = base_url("$MODULE_NAME/$TB_NAME/get");
$get_next_prev_employee_url = base_url("$MODULE_NAME/$TB_NAME/get_next_prev_employee");

$isCreate = isset($master_tb_data) && count($master_tb_data) > 0 ? false : true;
$HaveRs = !$isCreate;
$rs = $isCreate ? [] : $master_tb_data[0];

$tax_con = [0 => 'لا', 1 => 'نعم', 2 => ''];

if ($HaveRs) {
    $this_date = $rs['MONTH'];
    $this_month = substr($this_date, -2);

}

// ✅ جلب جميع أرقام الموظفين النشطين لاحتساب عدد الاستمارات المتبقية
$active_employees = $this->rmodel->getAll('SALARYFORM', 'GET_ALL_ACTIVE_EMPLOYEES');
$total_employees = count($active_employees);
$current_employee_index = array_search($rs['EMP_NO'], array_column($active_employees, 'EMP_NO')) + 1;
$remaining_forms = $total_employees - $current_employee_index;

?>
<style>
    /* صندوق الراتب */
    .salary-box {
        background: linear-gradient(135deg, #007bff, #00c6ff);
        font-size: 1.5rem;
        min-width: 230px;
        border-radius: 12px;
        padding: 20px;
        color: white;
        text-align: center;
        box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.2);
        border: 2px solid #0056b3;
        margin: 15px;
    }

    .salary-box .salary-amount {
        font-size: 1.5rem;
        font-weight: bold;
    }

    .salary-words {
        background: rgba(255, 255, 255, 0.20);
        padding: 7px 10px;
        border-radius: 6px;
        font-size: 1rem;
        font-weight: bold;
        color: white;
        text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.4);
        text-align: center;
    }

    /* أيقونة الشيكل */
    .shekel-icon {
        font-size: 2rem;
        display: block;
        margin-bottom: 5px;
    }

    /* فاصل الأقسام */
    .section-divider {
        border-bottom: 2px solid #ddd;
        margin-bottom: 15px;
        padding-bottom: 10px;
    }

    /* أيقونة المبلغ */
    .salary-amount i {
        vertical-align: middle;
        font-size: 1.6rem;
    }

    /* الجداول وتصميمها */
    .table-container {
        margin-top: 15px;
    }

    .table-bordered {
        border: 2px solid #dee2e6 !important;
        border-radius: 8px;
        overflow: hidden;
    }

    .table-bordered th, .table-bordered td {
        border: 1px solid #dee2e6 !important;
        padding: 10px;
        text-align: center;
    }

    .table-striped tbody tr:nth-child(odd) {
        background-color: #f9f9f9;
    }

    .table-striped tbody tr:nth-child(even) {
        background-color: #f1f1f1;
    }

    /* تحسين شكل الفوتر */
    .footer {
        font-size: 14px;
        color: #6c757d;
        text-align: center;
        padding: 10px;
        background: #f8f9fa;
        border-top: 1px solid #dee2e6;
        box-shadow: 0px -2px 5px rgba(0, 0, 0, 0.1);
    }

    /* تحسين مظهر البطاقات */
    .card {
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border: 1px solid #e0e0e0;
        margin-bottom: 20px;
    }

    .card-header {
        background-color: #f8f9fa;
        font-weight: bold;
        border-bottom: none !important;
    }

    /* تخصيص شكل الجداول */
    .tfoot tr {
        background: linear-gradient(90deg, #f8f9fa, #e9ecef) !important;
        font-weight: bold;
    }

    /* تنسيق فوتر جدول الاستحقاقات */
    #additions_data_tb tfoot tr {
        background: #28a745 !important; /* نفس لون الهيدر */
        color: white !important; /* نص أبيض ليكون واضحًا */
        font-weight: bold;
    }

    /* تنسيق فوتر جدول الاستقطاعات */
    #discounts_data_tb tfoot tr {
        background: #dc3545 !important; /* نفس لون الهيدر */
        color: white !important;
        font-weight: bold;
    }
    #additions_data_tb tfoot tr, #discounts_data_tb tfoot tr {
        box-shadow: 0px -2px 5px rgba(0, 0, 0, 0.2);
    }
    /* تصغير حجم فوتر جدول الاستحقاقات والاستقطاعات */
    #additions_data_tb tfoot tr td,
    #discounts_data_tb tfoot tr td {
        font-size: 0.85rem !important; /* تصغير الخط أكثر */
        padding: 6px !important; /* تقليل التباعد */
    }


    .table td, .table th {
        padding: 15px;
        text-align: center;
    }
    #home-btn {
        position: fixed;
        bottom: 80px; /* رفعه للأعلى فوق زر العودة */
        left: 20px;
        background-color: #007bff;
        color: white;
        border: none;
        padding: 12px 20px;
        font-size: 1rem;
        border-radius: 50px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        cursor: pointer;
        transition: 0.3s;
        z-index: 1000;
    }

    #home-btn:hover {
        background-color: #0056b3;
        transform: scale(1.1);
    }

    #back-to-top {
        position: fixed;
        bottom: 20px;
        right: 20px; /* جعله في الجهة اليمنى */
        background-color: #28a745;
        color: white;
        border: none;
        padding: 10px 15px;
        font-size: 1rem;
        border-radius: 50px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        cursor: pointer;
        transition: 0.3s;
        z-index: 1000;
    }

    #back-to-top:hover {
        background-color: #218838;
        transform: scale(1.1);
    }




</style>

    <div class="container-fluid mt-4">
        <!-- ✅ عنوان استمارة الراتب -->
        <div class="section-divider">
            <h2 class="text-primary fw-bold"><i class="fa fa-file-invoice-dollar"></i> استمارة الراتب</h2>
            <p class="text-muted">تفاصيل الراتب الشهرية للموظف</p>
        </div>


        <div class="card shadow border-0">
            <div class="card-header bg-primary text-white text-center fw-bold d-flex justify-content-between align-items-center">
                <div>
                    <button class="btn btn-light btn-sm me-2" id="prev-btn">
                        <i class="fa fa-arrow-right"></i> السابق
                    </button>
                    <button class="btn btn-light btn-sm" id="next-btn">
                        التالي <i class="fa fa-arrow-left"></i>
                    </button>
                </div>
                <span>بيانات الراتب</span>
                <div>
                    <span class="badge bg-light text-dark"> استمارة <span id="current-form"><?= $current_employee_index ?></span> من <?= $total_employees ?></span>
                    <span class="badge bg-warning text-dark"> تبقى <span id="remaining-forms"><?= $remaining_forms ?></span> استمارة</span>
                </div>
            </div>

            <div class="card-body">
                <form id="<?= $TB_NAME ?>_form" method="post" action="<?=$post_url?>">
                    <input type="hidden" name="page_act" value="<?=$page_act?>" />

                    <div class="row g-2">
                        <!-- ✅ العمود الأول: بيانات الموظف -->
                        <div class="col-md-8">
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <label class="form-label">الموظف</label>
                                    <select name="emp_no" id="dp_emp_no" class="form-select sel2">
                                        <option value="">_________</option>
                                        <?php foreach($emp_no_cons as $row) :?>
                                            <option <?=$HaveRs?($rs['EMP_NO']==$row['EMP_NO']?'selected':''):''?>
                                                    value="<?=$row['EMP_NO']?>"><?=$row['EMP_NO'].': '.$row['EMP_NAME']?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">الحالة الاجتماعية</label>
                                    <select name="status" id="dp_status" class="form-select sel2">
                                        <option value="">_________</option>
                                        <?php foreach($status_cons as $row) :?>
                                            <option <?=$HaveRs?($rs['STATUS']==$row['CON_NO']?'selected':''):''?>
                                                    value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">الشهر</label>
                                    <input type="text" class="form-control" value="<?=$HaveRs?$rs['MONTH']:""?>" name="month" id="txt_month">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">المقر الرئيسي</label>
                                    <select name="bran" id="dp_bran" class="form-select sel2">
                                        <option value="">_________</option>
                                        <?php foreach($bran_cons as $row) :?>
                                            <option <?=$HaveRs?($rs['BRAN']==$row['CON_NO']?'selected':''):''?>
                                                    value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">المقر الفرعي</label>
                                    <select name="branch" id="dp_branch" class="form-select sel2">
                                        <option value="">_________</option>
                                        <?php foreach($branch_cons as $row) :?>
                                            <option <?=$HaveRs?($rs['BRANCH']==$row['CON_NO']?'selected':''):''?>
                                                    value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">الإدارة</label>
                                    <select name="department" id="dp_department" class="form-select sel2">
                                        <option value="">_________</option>
                                        <?php foreach($department_cons as $row) :?>
                                            <option <?=$HaveRs?($rs['DEPARTMENT']==$row['CON_NO']?'selected':''):''?>
                                                    value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">المسمى المهني</label>
                                    <select name="w_no" id="dp_w_no" class="form-select sel2">
                                        <option value="">_________</option>
                                        <?php foreach($w_no_cons as $row) :?>
                                            <option <?=$HaveRs?($rs['W_NO']==$row['CON_NO']?'selected':''):''?>
                                                    value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">العلاوة الدورية</label>
                                    <input type="text" class="form-control" value="<?=$HaveRs?$rs['PERIODICAL_ALLOWNCES']:""?>" name="periodical_allownces">
                                </div>
                            </div>
                        </div>

                        <!-- ✅ العمود الثاني: صافي الراتب -->
                        <div class="col-md-4 d-flex align-items-center justify-content-center">

                            <div class="salary-box">
                                <h6 class="mb-1 fw-bold" data-toggle="tooltip" title="صافي الراتب بعد كل الاستحقاقات والاستقطاعات">
                                    صافي الراتب
                                </h6>
                                <span class="salary-amount">
                                    <i class="fa fa-ils fa-lg" style="vertical-align: middle; margin-left: 5px;"></i>
                                    <strong><?= number_format($HaveRs ? $rs['NET_SALARY'] : 0, 2) ?></strong>
                                    <span style="font-size: 1rem;">شيكل</span>
                                </span>
                                <p class="mt-2 text-light salary-words">
                                    <?= convertNumberToArabicWords($HaveRs ? $rs['NET_SALARY'] : 0) ?>
                                </p>


                            </div>

                        </div>
                    </div>

                    <!-- ✅ بيانات الاستحقاقات والاستقطاعات -->
                    <hr>
                    <div class="row mt-4">
                        <!-- الاستحقاقات -->
                        <div class="col-md-6">
                            <div class="card shadow">
                                <div class="card-header text-white text-center fw-bold"
                                     style="background-color: #28a745;">
                                    الاستحقاقات
                                </div>
                                <div class="card-body">
                                    <div class="table-container">
                                        <table class="table table-striped table-bordered text-center" id="additions_data_tb">
                                        <thead class="table-success">
                                        <tr>
                                            <th>رقم البند</th>
                                            <th>البند</th>
                                            <th>القيمة</th>
                                            <th>خاضع للضريبة</th>
                                            <th>البيان</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $additions_sum= 0; foreach($additions_data as $row) {
                                            $additions_sum+= $row['VALUE']; ?>
                                            <tr>
                                                <td><?=$row['CON_NO']?></td>
                                                <td><?=$row['CON_NO_NAME']?></td>
                                                <td><?=$row['VALUE']?></td>
                                                <td><?=$tax_con[$row['IS_TAXED']]?></td>
                                                <td><?=$row['NOTES']?></td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                        <tfoot>
                                        <tr class="table-dark">
                                            <td colspan="2">المجموع</td>
                                            <td><?=$additions_sum?></td>
                                            <td colspan="2"></td>
                                        </tr>
                                        </tfoot>
                                    </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- الاستقطاعات -->
                        <div class="col-md-6">
                            <div class="card shadow">
                                <div class="card-header text-white text-center fw-bold"
                                     style="background-color: #dc3545;">
                                    الاستقطاعات
                                </div>
                                <div class="card-body">
                                    <div class="table-container">
                                    <table class="table table-striped table-bordered text-center" id="discounts_data_tb">
                                        <thead class="table-danger">
                                        <tr>
                                            <th>رقم البند</th>
                                            <th>البند</th>
                                            <th>القيمة</th>
                                            <th>خاضع للضريبة</th>
                                            <th>البيان</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $discounts_sum= 0; foreach($discounts_data as $row) {
                                            $discounts_sum+= $row['VALUE']; ?>
                                            <tr>
                                                <td><?=$row['CON_NO']?></td>
                                                <td><?=$row['CON_NO_NAME']?></td>
                                                <td><?=$row['VALUE']?></td>
                                                <td><?=$tax_con[$row['IS_TAXED']]?></td>
                                                <td><?=$row['NOTES']?></td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                        <tfoot>
                                        <tr class="fw-bold table-danger">
                                            <td colspan="2">المجموع</td>
                                            <td><?=$discounts_sum?></td>
                                            <td colspan="2"></td>
                                        </tr>
                                        </tfoot>
                                    </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <button id="home-btn" onclick="window.location.href='<?=$index_url?>';">
                    <i class="fa fa-home"></i> الرئيسية
                </button>

            </div>
        </div>
    </div>
<?php
$scripts = <<<SCRIPT
<script>
    $(document).ready(function () {

        $('input, select, textarea').prop('readonly', true).prop('disabled', true);

        function showLoading() {
            $("#loading-indicator").show();
        }

        function hideLoading() {
            $("#loading-indicator").hide();
        }

        function loadEmployeeData(direction) {
            let emp_no = $("#dp_emp_no").val();
            let month = $("#txt_month").val();

            if (!emp_no || !month) return; // ✅ منع تنفيذ الوظيفة بدون رقم موظف

            let requestData = { emp_no: emp_no, month: month, direction: direction };

            // ✅ إظهار مؤشر التحميل وتعطيل الأزرار
            showLoading();
            $("#prev-btn, #next-btn").prop("disabled", true);

            get_data("$get_next_prev_employee_url", requestData, function(response) {
                hideLoading();
                $("#prev-btn, #next-btn").prop("disabled", false);

                if (response.status === 'success' && response.salary_data) {
                    let data = response.salary_data;

                    if (data.EMP_NO) {
                        $("#dp_emp_no").val(data.EMP_NO).trigger("change");
                        $("#dp_status").val(data.STATUS || '').trigger("change");
                        $("#dp_bran").val(data.BRAN || '').trigger("change");
                        $("#dp_branch").val(data.BRANCH || '').trigger("change");
                        $("#dp_department").val(data.DEPARTMENT || '').trigger("change");
                        $("#dp_w_no").val(data.W_NO || '').trigger("change");
                        $("#txt_periodical_allownces").val(data.PERIODICAL_ALLOWNCES || '');

                        let netSalary = parseFloat(data.NET_SALARY || 0).toFixed(2);
                        $(".salary-amount strong").text(netSalary);
                        $(".salary-words").text(response.salary_words || '-');

                        $("#current-form").text(response.current_index || 0);
                        $("#remaining-forms").text(response.remaining_forms || 0);

                        $("#prev-btn").prop("disabled", response.is_first);
                        $("#next-btn").prop("disabled", response.is_last);
                    }

                    // ✅ تحديث جدول الاستحقاقات
                    let additionsTable = $("#additions_data_tb tbody");
                    additionsTable.empty();
                    let additions_sum = 0;
                    if (response.additions.length > 0) {
                        response.additions.forEach(row => {
                            let value = parseFloat(row.VALUE || 0);
                            additions_sum += value;
                            additionsTable.append("<tr>" +
                                "<td>" + (row.CON_NO || '') + "</td>" +
                                "<td>" + (row.CON_NO_NAME || '') + "</td>" +
                                "<td>" + value.toFixed(2) + "</td>" +
                                "<td>" + (row.IS_TAXED == 1 ? 'نعم' : 'لا') + "</td>" +
                                "<td>" + (row.NOTES || '') + "</td>" +
                                "</tr>");
                        });
                    } else {
                        additionsTable.append('<tr><td colspan="5" class="text-center text-muted">لا توجد بيانات</td></tr>');
                    }
                    $("#additions_data_tb tfoot td:eq(1)").text(additions_sum.toFixed(2));

                    // ✅ تحديث جدول الاستقطاعات
                    let discountsTable = $("#discounts_data_tb tbody");
                    discountsTable.empty();
                    let discounts_sum = 0;
                    if (response.discounts.length > 0) {
                        response.discounts.forEach(row => {
                            let value = parseFloat(row.VALUE || 0);
                            discounts_sum += value;
                            discountsTable.append("<tr>" +
                                "<td>" + (row.CON_NO || '') + "</td>" +
                                "<td>" + (row.CON_NO_NAME || '') + "</td>" +
                                "<td>" + value.toFixed(2) + "</td>" +
                                "<td>" + (row.IS_TAXED == 1 ? 'نعم' : 'لا') + "</td>" +
                                "<td>" + (row.NOTES || '') + "</td>" +
                                "</tr>");
                        });
                    } else {
                        discountsTable.append('<tr><td colspan="5" class="text-center text-muted">لا توجد بيانات</td></tr>');
                    }
                    $("#discounts_data_tb tfoot td:eq(1)").text(discounts_sum.toFixed(2));

                } else {
                    alert(response.message || "حدث خطأ أثناء جلب البيانات");
                }
            });
        }

        $("#prev-btn").click(function() {
            if (!$(this).prop("disabled")) {
                loadEmployeeData('prev');
            }
        });

        $("#next-btn").click(function() {
            if (!$(this).prop("disabled")) {
                loadEmployeeData('next');
            }
        });

    });
</script>
SCRIPT;

sec_scripts($scripts);
?>
