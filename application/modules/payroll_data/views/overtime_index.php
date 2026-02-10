<?php
/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 09/02/2020
 * Time: 12:41 م
 */
$MODULE_NAME = 'payroll_data';
$TB_NAME = 'overtime';
$gfc_domain = gh_gfc_domain();
//الرئيسية
$index_url = base_url("$MODULE_NAME/$TB_NAME/index");
//تعديل قيمة الساعات المتجاوزة
$update_calculated_hours_url = base_url("$MODULE_NAME/$TB_NAME/update_calculated_hours");
/***********حذف الساعات الاضافية******/
$delete_hours_url = base_url("$MODULE_NAME/$TB_NAME/delete_hours");
//حالة الاعتماد
$agree_ma = intval($agree_ma);
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$adopt_all_url = base_url("$MODULE_NAME/$TB_NAME/public_adopt");
$unadopt_all_url = base_url("$MODULE_NAME/$TB_NAME/public_unadopt");
$adopt_detail_url = base_url("$MODULE_NAME/$TB_NAME/public_get_adopt_detail");
$edit_detail_url = base_url("$MODULE_NAME/$TB_NAME/public_get_edit_detail");
//تعديل قيمة الساعات المتجاوزة
$update_calculated_hours_url = base_url("$MODULE_NAME/$TB_NAME/update_calculated_hours");

$budget_overtime_url = base_url("$MODULE_NAME/$TB_NAME/budget_overtime_detail");
$budget_overtime_interval_url = base_url("$MODULE_NAME/$TB_NAME/public_get_budget_interval");
$budget_overtime_salary_url = base_url("$MODULE_NAME/$TB_NAME/public_get_budget_salary");
$hr_time = base_url("$MODULE_NAME/$TB_NAME/hr_time");
//اعتماد المدير المالي
$ChiefFinancial = base_url("$MODULE_NAME/$TB_NAME/ChiefFinancial_time");
//اعتماد المراقب الداخلي
$InternalObserver = base_url("$MODULE_NAME/$TB_NAME/InternalObserver_time");
//اعتماد مدير المقر
$HeadOffice = base_url("$MODULE_NAME/$TB_NAME/HeadOffice_time");
//اعتماد المدير العام
$GeneralDirector = base_url("$MODULE_NAME/$TB_NAME/GeneralDirector_time");
//اعتماد المالية للصرف
$FinancialAdopt = base_url("$MODULE_NAME/$TB_NAME/FinancialToPay");
//عرض السجلات المتكررة
$view_recurring_records_url = base_url("$MODULE_NAME/$TB_NAME/public_view_recurring_records");
//ارجاع الى مرحلة الاعداد
$CancelAdopt = base_url("$MODULE_NAME/$TB_NAME/return_adopt1");
$get_excel_url = base_url("$MODULE_NAME/$TB_NAME/excel_report");
$report_url = base_url("JsperReport/showreport?sys=hr/salaries");
?>
<!-- PAGE-HEADER -->
<div class="page-header">
    <div>
        <h1 class="page-title">اعتماد | كشف الوقت الاضافي</h1>
    </div>
    <div class="ms-auto pageheader-btn">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">الحركات</a></li>
            <li class="breadcrumb-item active" aria-current="page">الوقت الاضافي</li>
        </ol>
    </div>
</div>
<!-- PAGE-HEADER END -->
<!-- Row -->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><?= $title ?></h3>
                <div class="card-options">

                </div>
            </div>
            <div class="card-body">
                <form id="<?= $TB_NAME ?>_form">
                    <div class="row">
                        <?php if ($this->user->branch == 1) { ?>
                            <div class="form-group col-md-2">
                                <label> المقر</label>
                                <select name="branch_no" id="dp_branch_no" class="form-control sel2">
                                    <option value="">_______</option>
                                    <?php foreach ($branches as $row) : ?>
                                        <option <?= ($this->user->branch == $row['NO'] ? 'selected="selected"' : '') ?>
                                                value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <?php } else { ?>
                            <input type="hidden" name="branch_no" id="dp_branch_no" value="<?= $this->user->branch ?>">
                        <?php } ?>
                        <div class="form-group col-md-2">
                            <label>الموظف</label>
                            <select name="emp_no" id="dp_emp_no" class="form-control sel2">
                                <option value="">_________</option>
                                <?php foreach ($emp_no_cons as $row) : ?>
                                    <option value="<?= $row['EMP_NO'] ?>"><?= $row['EMP_NO'] . ': ' . $row['EMP_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label>الدائرة</label>
                            <select name="head_department" id="dp_head_department" class="form-control sel2">
                                <option value="">_________</option>
                                <?php foreach ($head_department_cons as $row) : ?>
                                    <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label>نوع التعيين</label>
                            <select name="emp_type" id="dp_emp_type" class="form-control sel2">
                                <option value="">_________</option>
                                <?php foreach ($emp_type_cons as $row) : ?>
                                    <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NO'] . '-' . $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <input type="hidden" id="account_id" name="account_id" value="" class="form-control">
                        <input type="hidden" id="section_no" name="section_no" value="" class="form-control">
                        <div class="form-group col-md-2">
                            <label>طبيعة العمل</label>
                            <select name="w_no" id="dp_w_no" class="form-control sel2">
                                <option value="">_________</option>
                                <?php foreach ($w_no_cons as $row) : ?>
                                    <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label>مرحلة الاعتماد</label>
                            <select name="adopt_stage" id="dp_adopt_stage" class="form-control sel2">
                                <option value="">_________</option>
                                <?php foreach ($adopt_cons as $row) : ?>
                                    <option <?= ($agree_ma == $row['CON_NO'] ? 'selected="selected"' : '') ?>
                                            value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label>اعتماد المالية</label>
                            <select name="is_active" id="dp_is_active" class="form-control sel2">
                                <option value="">-----------</option>
                                <option value="0">غير معتمد</option>
                                <option value="1"> معتمد</option>
                            </select>
                        </div>
                        <div class="form-group col-md-1">
                            <label>من شهر</label>
                            <input type="text" placeholder="الشهر" name="month" id="txt_month" class="form-control"
                                   value="<?= date('Ym') ?>">
                        </div>
                        <!--<div class="form-group col-md-1">
                            <label>الى </label>
                            <input type="text" placeholder="الى" name="to_month" id="txt_to_month"
                                   class="form-control" value="<?= date('Ym') ?>">

                        </div>-->
                        <div class="form-group col-md-1">
                            <label for="dl_op">النسبة</label>
                            <select class="form-control" id="dl_op" name="dl_op">
                                <option value="">---</option>
                                <option value=">">></option>
                                <option value=">=">>=</option>
                                <option value="<="><=</option>
                                <option value="<"><</option>
                                <option value="=">=</option>
                            </select>
                        </div>
                        <div class="form-group col-md-1">
                            <label> ساعات المقر</label>
                            <input type="text" placeholder=" ساعات المقر" name="actual_hours" id="txt_actual_hours"
                                   class="form-control" value="">
                        </div>
                        <div class="form-group col-md-2">
                            <label> الساعات المعتمدة</label>
                            <input type="text" placeholder="الساعات المعتمدة في الراتب" name="calculated_hours"
                                   id="txt_calculated_hours" class="form-control" value="">
                        </div>
                        <?php if ($this->user->branch == 1) { ?>
                            <div class="form-group col-md-1">
                                <label>تجميع حسب</label>
                                <div class="checkbox">
                                    <div class="custom-checkbox custom-control">
                                        <input type="checkbox" data-checkboxes="mygroup" class="custom-control-input"
                                               id="checkbox-1" name="group_by_branch" value="1">
                                        <label for="checkbox-1" class="custom-control-label mt-1">المقر</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-1">
                                <label>عرض </label>
                                <div class="checkbox">
                                    <div class="custom-checkbox custom-control">
                                        <input type="checkbox" data-checkboxes="mygroup" class="custom-control-input"
                                               id="checkbox-1" name="show_det" value="1" checked>
                                        <label for="checkbox-1" class="custom-control-label mt-1">التفاصيل</label>
                                    </div>
                                </div>
                            </div>
                        <?php } else { ?>
                            <input type="checkbox" name="show_det" value="1" checked style="display: none;">&nbsp;
                        <?php } ?>
                    </div>
                    <div class="flex-shrink-0">
                        <button type="button" onclick="javascript:search();" class="btn btn-primary">
                            <i class="fa fa-search"></i>
                            إستعلام
                        </button>
                        <?php if (HaveAccess($hr_time)) { ?>
                            <button type="button" id="btn_hr_adopt" onclick="javascript:open_hr_0();"
                                    class="btn btn-indigo" style="display: none">
                                <i class="fa fa-check"></i>
                                اعتماد الشؤون الادرية في المقر
                            </button>
                        <?php } ?>

                        <?php if (HaveAccess($ChiefFinancial)) { ?>
                            <button type="button" id="ChiefFinancial" onclick="javascript:open_ChiefFinancial_10();"
                                    class="btn btn-indigo" style="display: none">
                                <i class="fa fa-check"></i>
                                اعتماد المدير المالي في المقر
                            </button>
                        <?php } ?>
                        <?php if (HaveAccess($HeadOffice)) { ?>
                            <button type="button" id="HeadOffice" onclick="javascript:open_HeadOffice_30();"
                                    class="btn btn-indigo"
                                    style="display: none">
                                <i class="fa fa-check"></i>
                                اعتماد مدير المقر
                            </button>
                        <?php } ?>
                        <?php if (HaveAccess($InternalObserver)) { ?>
                            <button type="button" id="InternalObserver" onclick="javascript:open_InternalObserver_31();"
                                    class="btn btn-indigo" style="display: none">
                                <i class="fa fa-check"></i>
                                اعتماد المراقب الداخلي
                            </button>
                        <?php } ?>
                        <?php if (HaveAccess($GeneralDirector)) { ?>
                            <button type="button" id="GeneralDirector" onclick="javascript:open_GeneralDirector_33();"
                                    class="btn btn-indigo" style="display: none">
                                <i class="fa fa-check"></i>
                                اعتماد المدير العام
                            </button>
                        <?php } ?>
                        <?php if (HaveAccess($FinancialAdopt)) { ?>
                            <button type="button" id="Financialpay" onclick="javascript:open_Financial_pay_35();"
                                    class="btn btn-indigo"
                                    style="display: none">
                                <i class="fa fa-check"></i>
                                اعتماد المالية
                            </button>
                        <?php } ?>

                        <?php if (HaveAccess($CancelAdopt)) { ?>
                            <button type="button" id="CancelAdopt" onclick="javascript:open_CancelAdopt_0();"
                                    class="btn btn-danger"
                                    style="display: none">
                                <i class="fa fa-angle-double-left"></i>
                                ارجاع للشؤون الادارية
                            </button>
                        <?php } ?>

                        <?php if (HaveAccess($budget_overtime_url)) { ?>
                           <!-- <button type="button" onclick="javascript:check_budget_overtime_detail();"
                                    class="btn btn-orange">
                                <i class="fa fa-shekel"></i>
                                فحص على الموازنة
                            </button>-->
                        <?php } ?>

                        <button type="button" onclick="javascript:show_all_recurring_records();"
                                id="btn_recurring_records" class="btn btn-danger" style="display: none;">
                            <i class="fa fa-rss"></i>
                            عرض السجلات المتكررة
                        </button>

                        <button type="button" onclick="ExcelData()"
                                class="btn btn-success">
                            <i class="fa fa-file-excel-o"></i>
                            إكسل
                        </button>

                        <button type="button" onclick="javascript:print_report();"
                                class="btn btn-blue">
                            <i class="fa fa-print"></i>
                            طباعة
                        </button>

                        <button type="button" onclick="javascript:clear_form();" class="btn btn-cyan-light">
                            <i class="fa fa-eraser"></i>
                            تفريغ الحقول
                        </button>
                    </div>
                </form>
                <hr>
                <div id="container">
                    <?php //echo modules::run($get_page,$page); ?>
                </div>

            </div>
        </div>
    </div>
</div>

<!--Modal NOTE Bootstrap -1- اعتماد الشؤون الادارية-->
<div class="modal fade" id="hr_note">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">اعتماد الشؤون الادارية</h6>
                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span
                            aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <div class="row">
                        <div class="form-group  col-md-6">
                            <label> الملاحظة </label>
                            <input type="text" name="note" value="-" id="txt_hr" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?php if (HaveAccess($hr_time)) { ?>
                    <button type="button" onclick="javascript:adopt(1);" id="btn_click_adopt_1"
                            class="btn btn-indigo">
                        <i class="fa fa-check"></i>
                        اعتماد الشؤون الادرية في المقر
                    </button>
                <?php } ?>
                <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">اغلاق</button>
            </div>
        </div>
    </div>
</div>
<!--End Modal NOTE Bootstrap -1- اعتماد الشؤون الادارية-->


<!--Modal NOTE Bootstrap -10- اعتماد المدير المالي-->
<div class="modal fade" id="ChiefFinancial_note">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">اعتماد المدير المالي</h6>
                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span
                            aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <div class="row">
                        <div class="form-group  col-md-6">
                            <label> الملاحظة </label><input type="text" name="note" value="-"
                                                            id="txt_note_ChiefFinancial" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?php if (HaveAccess($ChiefFinancial)) { ?>
                    <button type="button" onclick="javascript:adopt(10);" class="btn btn-indigo"
                            id="btn_click_adopt_10">
                        <i class="fa fa-check-square-o"></i>
                        اعتماد المدير المالي في المقر
                    </button>
                <?php } ?>
                <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">اغلاق</button>
            </div>
        </div>
    </div>
</div>
<!--End Modal NOTE Bootstrap -10- اعتماد المدير المالي-->


<!--Modal NOTE Bootstrap -30- اعتماد مدير المقر-->
<div class="modal fade" id="HeadOffice_note">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">اعتماد مدير المقر</h6>
                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span
                            aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <div class="row">
                        <div class="form-group  col-md-6">
                            <label> الملاحظة </label>
                            <input type="text" name="note" value="-" id="txt_note_HeadOffice" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?php if (HaveAccess($HeadOffice)) { ?>
                    <button type="button" onclick="javascript:adopt(30);" class="btn btn-indigo"
                            id="btn_click_adopt_30">
                        <i class="fa fa-check-square-o"></i>
                        اعتماد مدير المقر
                    </button>
                <?php } ?>
                <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">اغلاق</button>
            </div>
        </div>
    </div>
</div>
<!--End Modal NOTE Bootstrap -30- اعتماد مدير المقر-->


<!-- Start Modal NOTE Bootstrap -31- اعتماد المراقب الداخلي-->
<div class="modal fade" id="InternalObserver_note">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">اعتماد المراقب الداخلي</h6>
                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span
                            aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <div class="row">
                        <div class="form-group  col-md-6">
                            <label> الملاحظة </label>
                            <input type="text" name="note" value="-" id="txt_note_InternalObserver"
                                   class="form-control">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?php if (HaveAccess($InternalObserver)) { ?>
                    <button type="button" onclick="javascript:adopt(31);" class="btn btn-indigo"
                            id="btn_click_adopt_31">
                        <i class="fa fa-check-square-o"></i>
                        اعتماد المراقب الداخلي
                    </button>
                <?php } ?>
                <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">اغلاق</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal NOTE Bootstrap -31- اعتماد المراقب الداخلي-->


<!-- Start Modal NOTE Bootstrap -33- اعتماد المدير العام-->
<div class="modal fade" id="GeneralDirector_note">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">اعتماد المدير العام</h6>
                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span
                            aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <div class="row">
                        <div class="form-group  col-md-6">
                            <label> الملاحظة </label>
                            <input type="text" name="note" value="-" id="txt_note_GeneralDirector" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?php if (HaveAccess($GeneralDirector)) { ?>
                    <button type="button" onclick="javascript:adopt(33);" class="btn btn-indigo"
                            id="btn_click_adopt_33">
                        <i class="fa fa-check-square-o"></i>
                        اعتماد المدير العام
                    </button>
                <?php } ?>
                <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">اغلاق</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal NOTE Bootstrap -33- اعتماد المدير العام-->


<!--Modal NOTE Bootstrap -35للصرف  اعتماد  المالية-->
<div class="modal fade" id="finical_pay_note">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">اعتماد المالية للصرف</h6>
                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span
                            aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <div class="row">
                        <div class="form-group  col-md-6">
                            <label> الملاحظة </label>
                            <input type="text" name="note" value="-" id="txt_note_finical_pay" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?php if (HaveAccess($FinancialAdopt)) { ?>
                    <button type="button" onclick="javascript:adopt(35);" class="btn btn-indigo"
                            id="btn_click_adopt_35">
                        <i class="fa fa-check-square-o"></i>
                        اعتماد المالية
                    </button>
                <?php } ?>
                <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">اغلاق</button>
            </div>
        </div>
    </div>
</div>
<!--End Modal NOTE Bootstrap -35للصرف  اعتماد  المالية-->

<!--Modal NOTE Bootstrap  الغاء الاعتماد الارجاع الى المعد-->
<div class="modal fade" id="CancelAdopt_note">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">ارجاع الكشف للشؤون الادارية</h6>
                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span
                            aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <div class="row">
                        <div class="form-group  col-md-6">
                            <label> سبب الارجاع </label>
                            <input type="text" name="note" value="-" id="txt_CancelAdopt" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?php if (HaveAccess($CancelAdopt)) { ?>
                    <button type="button" onclick="javascript:unadopt(0);" class="btn btn-danger">
                        <i class="fa fa-angle-double-left"></i>
                        ارجاع الى الادارية
                    </button>
                <?php } ?>
                <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">اغلاق</button>
            </div>
        </div>
    </div>
</div>
<!--End Modal NOTE Bootstrap  الغاء الاعتماد الارجاع الى المعد-->

<!-- DetailModal Adopt -->
<div class="modal fade" id="DetailModal">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">بيانات الاعتماد</h6>
                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span
                            aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" id="public-modal-body">

            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">اغلاق</button>
            </div>
        </div>
    </div>
</div>


<!-- EditModal Adopt -->
<div class="modal fade" id="EditModal">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">بيانات</h6>
                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span
                            aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="edit-modal-body">

            </div>
        </div>
    </div>
</div>


<!-- recurring_records Modal -->
<div class="modal fade" id="recurring_records_modal">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">بيانات التكرار</h6>
                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span
                            aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="recurring_body">

            </div>
        </div>
    </div>
</div>


<!--Modal Bootstrap بيانات الموزانة-->
<div class="modal fade" id="Budget_modal">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">بيانات الموازنة</h6>
                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span
                            aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" id="Budget-body">
                <div class="tr_border">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="result1" id="result1">

                            </div>
                        </div>
                    </div>

                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="result2" id="result2">

                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="h4 text-center">المبلغ المحقق من المالية</div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="result3" id="result3">

                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <u>المبلغ المتبقي للموازنة</u>
                        <div id="remain_value" class="h4 text-center">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">اغلاق</button>
            </div>
        </div>
    </div>
</div>


<?php
$scripts = <<<SCRIPT
<script type="text/javascript">
 
       var table = '{$TB_NAME}';
       var count = 0;
        
      $('.sel2:not("[id^=\'s2\']")').select2();  
       $('#page_tb .checkboxes').prop("disabled", true);
 
 
      $('#txt_month').datetimepicker({
                format: 'YYYYMM',
                minViewMode: 'months',
                pickTime: false,
         });
        
      $('#txt_to_month').datetimepicker({
                format: 'YYYYMM',
                minViewMode: 'months',
                pickTime: false,
      });
 
       
     $('#dp_emp_type').on('change', function() {
           var emp_type = $(this).find(":selected").val() ;
           if(emp_type == 1) {
            $('#account_id').val('502010207');
            $('#section_no').val('173');
            } else if (emp_type == 10) {
                  $('#account_id').val('502020201');
                  $('#section_no').val('488');
              } 
              else if (emp_type == 9) {
                  $('#account_id').val('502020201');
                  $('#section_no').val('488');
              } else {
                  $('#account_id').val('');
                  $('#section_no').val('');
            }  
      });
       
     function clear_form(){
       $('#txt_month').val('');
       $('#dp_emp_no').select2('val',0);
       $('#dp_head_department').select2('val',0);
       $('#dp_w_no').select2('val',0);
       $('#dp_emp_type').select2('val',0);
       $('#dp_adopt_stage').select2('val',0);
       $('#dp_is_active').select2('val',0);
        $('.sel2').select2('val',0);
    }
       
     $(function(){
            reBind();
       });

     function reBind(){
        ajax_pager({
              branch_no:$('#dp_branch_no').val(),month:$('#txt_month').val(),emp_no:$('#dp_emp_no').val(),head_department:$('#dp_head_department').val(),w_no:$('#dp_w_no').val(),
              emp_type:$('#dp_emp_type').val(),agree_ma:$('#dp_adopt_stage').val(),is_active:$('#dp_is_active').val(),op:$('#dl_op').val(),actual_hours:$('#txt_actual_hours').val(),calculated_hours:$('#txt_calculated_hours').val()
         });
    }

     function LoadingData(){
          ajax_pager_data('#page_tb > tbody',{
             branch_no:$('#dp_branch_no').val(),month:$('#txt_month').val(),emp_no:$('#dp_emp_no').val(),head_department:$('#dp_head_department').val(),w_no:$('#dp_w_no').val(),
             emp_type:$('#dp_emp_type').val(),agree_ma:$('#dp_adopt_stage').val(),is_active:$('#dp_is_active').val(),op:$('#dl_op').val(),actual_hours:$('#txt_actual_hours').val(),calculated_hours:$('#txt_calculated_hours').val()
          });
       }
 
     function search(){
        var branch_no = $('#dp_branch_no').val();
        var month = $('#txt_month').val();
        var adopt_stage = $('#dp_adopt_stage').val();
        if (branch_no == ''){
             danger_msg('يرجى  ادخال المقر');
             return -1;
        }
        if (month == '') {
             danger_msg('يرجى  ادخال الشهر');
             return -1;
        }else{
            $('#page_tb .checkboxes').prop("disabled", true);    
            $('#btn_hr_adopt').hide();  //اعتماد الشؤون الادرية للمقر 1
            $('#ChiefFinancial').hide();  //اعتماد المدير المالي للمقر 10
            $('#HeadOffice').hide(); //اعتماد مدير المقر 30
            $('#InternalObserver').hide(); //اعتماد المراقب الداخلي 31
            $('#GeneralDirector').hide(); //اعتماد المدير العام 33
            $('#Financialpay').hide(); //اعتماد المالية للصرف 35
            $('#CancelAdopt').hide();
            $('#btn_recurring_records').hide();
             if (adopt_stage == 0) {
                 $('#btn_hr_adopt').show();  
             }else if (adopt_stage == 1) {
               $('#ChiefFinancial').show();  
                 $('#CancelAdopt').show();
             } else if (adopt_stage == 10) {
                 $('#HeadOffice').show();
                 $('#CancelAdopt').show();
             } else if (adopt_stage == 30) {
               $('#InternalObserver').show();
                $('#CancelAdopt').show();
             }else if (adopt_stage == 31 && branch_no == 1) {
                  $('#GeneralDirector').show();
             }else if (adopt_stage == 33 && branch_no == 1) {
                  $('#Financialpay').show();  
                  $('#CancelAdopt').show(); 
             }else if (adopt_stage == 31 && branch_no != 1) {
                  $('#Financialpay').show();  
                  $('#CancelAdopt').show(); 
             }else if (adopt_stage == 35) {
                  $('#CancelAdopt').show();
             }
             $('#btn_recurring_records').show();
             get_data('{$get_page_url}',{page: 1,
                branch_no:$('#dp_branch_no').val(),month:$('#txt_month').val(),emp_no:$('#dp_emp_no').val(),head_department:$('#dp_head_department').val(),w_no:$('#dp_w_no').val(),
                emp_type:$('#dp_emp_type').val(),agree_ma:$('#dp_adopt_stage').val(),is_active:$('#dp_is_active').val(),op:$('#dl_op').val(),actual_hours:$('#txt_actual_hours').val(),calculated_hours:$('#txt_calculated_hours').val()
             },function(data){
                $('#container').html(data);
                reBind();
            },'html');
        }
     }           
      
 
     function ExcelData(){
        var fewSeconds = 10;
        var branch_no = $('#dp_branch_no').val();
        var month = $('#txt_month').val();
        var emp_no = $('#dp_emp_no').val();
        var head_department = $('#dp_head_department').val();
        var w_no = $('#dp_w_no').val();
        var emp_type = $('#dp_emp_type').val();
        var agree_ma = $('#dp_adopt_stage').val();
        var is_active = $('#dp_is_active').val();
        var op = $('#dl_op').val();
        var actual_hours = $('#txt_actual_hours').val();
        var calculated_hours = $('#txt_calculated_hours').val();
        info_msg('تنبيه','جاري تجهيز ملف الاكسل ..'); 
            location.href = '{$get_excel_url}?branch_no='+branch_no+'&month='+month+'&emp_no='+emp_no+'&head_department='+head_department+'&w_no='+w_no+'&emp_type='+emp_type+'&agree_ma='+agree_ma+'&is_active='+is_active+'&op='+op+'&actual_hours='+actual_hours+'&calculated_hours='+calculated_hours;
            setTimeout(function(){
                info_msg('تنبيه','جاري تجهيز ملف الاكسل ..'); 
        }, fewSeconds*1000);
    }
    
    
     function print_report(){
        var branch_no = $('#dp_branch_no').val();
        var from_month = $('#txt_month').val();
        var to_month = $('#txt_month').val();
        var emp_no = $('#dp_emp_no').val();
        var head_department = $('#dp_head_department').val();
        var w_no = $('#dp_w_no').val();
        var emp_type = $('#dp_emp_type').val();
        var agree_ma = '';
        var is_active = '';
        var group_by_branch = have_no_val($('input[name=group_by_branch]:checked').val());
        var show_det = have_no_val($('input[name=show_det]:checked').val()); 
        if(from_month == ''){
            danger_msg('يجب ادخال من شهر ');
            return -1;
        }else if(to_month == ''){
           danger_msg('يجب ادخال الى شهر ');
            return -1;
        }else{    
            _showReport('{$report_url}&report_type=pdf&report=overtime&p_branch='+branch_no+'&p_emp_no='+emp_no+'&p_head_dep='+head_department+'&p_emp_type='+emp_type+'&p_w_no_admin='+w_no+'&p_agree_ma='+agree_ma+'&p_is_active='+is_active+'&p_month_from='+from_month+'&p_month_to='+to_month+'&p_show_det='+show_det+'&p_group_by_branch='+group_by_branch+'');
        }
    }
    
      // check if var have value or null //
     function have_no_val(v) {
        if(v == null) {
            return v = '';
        }else {
            return v;
        }
    }
     
     function open_hr_0(){
        $('#hr_note').modal('show');
      }
     
     function open_ChiefFinancial_10(){
        $('#ChiefFinancial_note').modal('show');
     }
     
     function open_HeadOffice_30(){
         $('#HeadOffice_note').modal('show');
     }
     
     function open_InternalObserver_31(){
         $('#InternalObserver_note').modal('show');
     }
     
     //اعتماد المدير العام في المقر الرئيسي 33
     function open_GeneralDirector_33(){
         $('#GeneralDirector_note').modal('show');
     }
     
     //اعتماد المالية للصرف 35
     function open_Financial_pay_35(){
         $('#finical_pay_note').modal('show');
     }
      //Cancel Adopt Modalالارجاع للمعد  ---//
     function open_CancelAdopt_0(){
      $('#CancelAdopt_note').modal('show');
     }
     
      var btn__= '';
     $('#btn_click_adopt_1,#btn_click_adopt_10,#btn_click_adopt_30,#btn_click_adopt_31,#btn_click_adopt_33,#btn_click_adopt_35').click( function(){
        btn__ = $(this);
     });
   
     function adopt(no){
       var action_desc= 'اعتماد';
       var from_month = $('#txt_month').val();
       var val = [];
       if (no == 10) {
          var note = $('#txt_note_ChiefFinancial').val();
       } else if (no == 30) {
          var note = $('#txt_note_HeadOffice').val();
        } else if (no == 31) {
          var note = $('#txt_note_InternalObserver').val();
       }else if (no == 33) {
          var note = $('#txt_note_GeneralDirector').val();
       }else if (no == 35) {
          var note = $('#txt_note_finical_pay').val();
       } else {
          var note = '';
       }
       $('#page_tb .checkboxes:checked').each(function(i){
            val[i] = $(this).val();
       });
       if(val.length > 0){
            if(confirm('هل تريد بالتأكيد '+action_desc+' '+val.length+' بنود')){
              get_data('{$adopt_all_url}', {pp_ser:val,agree_ma:no,note:note} , function(ret){
                     if(ret==1){
                         success_msg('رسالة','تمت عملية الاعتماد بنجاح ');
                         $('#btn_click_adopt_'+no+'').attr('disabled','disabled');
                         if (no == 31 && branch_no == 1){
                            var sub= 'اعتماد كشف الوقت الاضافي ';
                            var text= 'يرجى اعتماد كشف الوقت الاضافي لشهر ';
                            text+= '<br>'+from_month+'';
                            text+= '<br>للاطلاع افتح الرابط';
                            text+= '<br> {$gfc_domain}{$index_url}';
                            _send_mail(btn__,'telbawab@gedco.ps',sub,text);
                            _send_mail(btn__,'mtastal@gedco.ps',sub,text);
                            btn__ = '';
                         }
                        reload_Page();                    
                    }else{
                        danger_msg('تحذير..',ret);
                    }
               }, 'html');
            }
        }else{
              danger_msg('يرجى تحديد السجلات لاعتمادها');
              return -1;
       }
    }
    
     function unadopt(no){
       var action_desc= 'ارجاع الكشف للادارية';
       var val = [];
       var note = $('#txt_CancelAdopt').val();
       var month = $('#txt_month').val();
       if (note == '') {
           danger_msg('يرجى ادخال سبب الارجاع');
           return -1;     
       }else {
        $('#page_tb .checkboxes:checked').each(function(i){
            val[i] = $(this).val();
        });
          if(val.length > 0){
            if(confirm('هل تريد بالتأكيد '+action_desc+' '+val.length+' بنود')){
             get_data('{$unadopt_all_url}', {pp_ser:val,agree_ma:no,note:note} , function(ret){
                     if(parseInt(ret) >= 1){
                        success_msg('رسالة','تمت عملية الارجاع للشؤون الادارية بنجاح ');
                        reload_Page();
                    }else{
                        danger_msg('تحذير..',ret);
                    }
             }, 'html');
            }
        }else{
              danger_msg('يرجى تحديد السجلات');
              return  -1;
        }
      } //end else if 
    } //end unadopt
    
    function show_detail_row(pp_ser) {
       // Display Modal
        $('#DetailModal').modal('show');
        $.ajax({
            url: '{$adopt_detail_url}',
            type: 'post',
            data: {pp_ser: pp_ser},
            success: function(response){
                // Add response in Modal body
                $('#public-modal-body').html(response);
               
            }
        });
    }
    
     function edit_detail_row(pp_ser) {
        showLoading();
       // Display Modal
        $('#EditModal').modal('show');
        $.ajax({
            url: '{$edit_detail_url}',
            type: 'post',
            data: {pp_ser: pp_ser},
            success: function(response){
                // Add response in Modal body
                $('#edit-modal-body').html(response);
            },
            complete: function() {
                HideLoading();
            }
        });
     }
     
     function update_calculated_hours(p_ser){
        if(confirm('هل تريد تعديل القيمة ؟!')){
          var calculated_hours = $('#txt_calculated_hours_ee').val();
          if(calculated_hours == '' ) {
            warning_msg('لا يمكن ان تكون القيمة فارغة او القيمة صفر او اكبر من عدد الساعات المسموح');
            return -1;
          }else{
                   get_data('{$update_calculated_hours_url}', {pp_ser:p_ser,calculated_hours:calculated_hours} , function(ret){
                     if(ret>= 1){
                         success_msg('رسالة','تم تعديل البيانات ..');
                         $('#EditModal').modal('hide');
                         search();
                    }else{
                        warning_msg('تحذير..',ret);
                        return -1;
                    }
                  }, 'html');
                }
          } //end if else 
     } //end update_calculated_hours
     
     
     
      function check_budget_overtime_detail() {
         var branch_no  = $('select[name="branch_no"]').val();
         var month  = $('input[name="month"]').val();
         var to_month  = $('input[name="to_month"]').val();
         var emp_type = $('select[name="emp_type"]').val();
         var account_id = $('input[name="account_id"]').val();
         var section_no = $('input[name="section_no"]').val();
         var url = '{$budget_overtime_url}';
         var url_interval = '{$budget_overtime_interval_url}';
         var url_salary = '{$budget_overtime_salary_url}';
         var result1;
         var result2;
         var result3;  
         if (branch_no == ''){
                 warning_msg('تنبيه..', 'يجب تحديد المقر');
                 return -1;
         } else if (emp_type == '') {
                 warning_msg('تنبيه..', 'يجب تحديد نوع التعين');
                 return -1;
          } else {
              $.when(
              // AJAX request
              $.ajax({
                  url: url,
                  type: 'post',
                  data: {branch_no: branch_no,section_no:section_no},
                 success: function(response){
                     // Add response in Modal body
                     result1 = response;                  
                 }
               }),
               $.ajax({
                  url: url_interval,
                  type: 'post',
                  data: {section_no:section_no,branch_no: branch_no,month:month,to_month:to_month,emp_type:emp_type},
                 success: function(response){
                  // Add response in Modal body
                     result2 = response;     
                  }
               }),
               $.ajax({
                      url: url_salary,
                      type: 'post',
                      data: {branch_no: branch_no,month:month,to_month:to_month,emp_type:emp_type,account_id:account_id},
                      success: function(response){
                      // Add response in Modal body
                         result3 = response;     
                      }
               }),
              ).then(function() {
                $('#result1').html(result1);
                $('#result2').html(result2);
                $('#result3').html(result3);
                var budget = $("#budget_tb #total_update").text();
                var budget_finical_adopt = $("#budget_salary_tb #budget_salary_val").text();
                var sumation =  budget - budget_finical_adopt ;
                $('#Budget_modal').modal('show');  
              });
          }
    } //end check_budget_function
    
    
    function show_all_recurring_records(){
       var where_sql = $('#txt_where_sql').val();
       showLoading();
       // Display Modal
       $('#recurring_records_modal').modal('show');
       $.ajax({
            url: '{$view_recurring_records_url}',
            type: 'post',
            data: {month: where_sql},
            success: function(response){
                // Add response in Modal body
                $('#recurring_body').html(response);
            },
            complete: function() {
                HideLoading();
            }
       });
    }
    
    function delete_row(a,id){
        if(confirm('هل تريد حذف السجل ؟')){
           get_data('{$delete_hours_url}',{id:id},function(data){
                if(parseInt(data) >= 1){
                    success_msg('رسالة','تم حذف السجل بنجاح ..');
                    $(a).closest('tr').remove();
                }
            },'html');
        }
    }
</script>
SCRIPT;
sec_scripts($scripts);
?>
