<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 14/12/14
 * Time: 12:15 م
 */

$report_path = base_url('/reports');
$report_url = 'https://itdev.gedco.ps/gfc.aspx?data=' . get_report_folder() . '&';
$report_jasper_url = base_url("JsperReport/showreport?sys=financial/archives").'&';

$trial_balance_url = base_url('financial/reports/trial_balance');
$professor_account_url = base_url('financial/reports/professor_account');
$cars_url = base_url('payment/cars/public_select_car');
$customer_url = base_url('payment/customers/public_index');
?>

<?= AntiForgeryToken() ?>
<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
            <!--     <li><a href="#">بحث</a> </li>-->
        </ul>

    </div>

    <div class="form-body">

        <fieldset>
            <legend>تقارير المخازن</legend>
            <ul class="report-menu">

                <li><a class="btn blue" data-report-source="11"
                       data-option="m_report,#branch,#account1,#account2,#currency,#fin_source,#ACCOUNT_currency,#financial_center_id_div,#chapters,#class_type,#class_acount_type,#reserve" data-type="report"
                       href="javascript:;">فائض وعجز المخازن</a></li>
            </ul>
        </fieldset>


        <fieldset>
            <legend>تقارير الحسابات</legend>
            <ul class="report-menu">

                <li><a class="btn blue" data-report-source="1"
                       data-option="#t_report,#branch,#account1,#account2,#currency,#fin_source" data-type="report"
                       href="javascript:;">حساب الأستاذ </a></li>
                <li><a class="btn green" data-report-source="2"
                       data-option="#m_report,#branch,#account1,#account2,#currency,#fin_source,#ACCOUNT_currency,#financial_center_id_div,#chapters"
                       data-type="report" href="javascript:;">ميزان المراجعة </a></li>

            </ul>
        </fieldset>

        <fieldset class="red">
            <legend>تقارير المستفيدين</legend>
            <ul class="report-menu">

                <li><a class="btn blue" data-report-source="6"
                       data-option="#t_report,#customer_report,#currency,#customer_div,#fin_source" data-type="report"
                       href="javascript:;">حساب الأستاذ </a></li>
                <li><a class="btn green" data-report-source="8"
                       data-option="#customer_report,#customer_type,#customer_div,#customer_div1,#account1" data-type="report"
                       href="javascript:;">تقرير الأرصدة</a></li>


            </ul>
        </fieldset>

        <fieldset class="blue">
            <legend>تقارير السيارات</legend>
            <ul class="report-menu">

                <li><a class="btn blue" data-report-source="7" data-option="#t_report,#car_report,#currency,#cars_div,#account1"
                       data-type="report" href="javascript:;">حساب الأستاذ </a></li>

            </ul>
        </fieldset>
        <fieldset class="purple">
            <legend>تقارير الخزينة</legend>
            <ul class="report-menu">

                <li><a class="btn yellow" data-report-source="3"
                       data-option="#voucher_report,#doc_num,#payment_type,#account1,#branch,#currency,#d_account" data-type="report"
                       href="javascript:;"> إيصالات القبض </a></li>
                <li><a class="btn red" data-report-source="4"
                       data-option="#payment_report,#doc_num,#payment_type,#branch,#currency" data-type="report"
                       href="javascript:;"> سندات الصرف </a></li>
                <li><a class="btn purple" data-report-source="5"
                       data-option="#dp_payment_report,#check_type_rp,#banks,#c_customer_div,#currency,#check_id_div,#account1,#check_status_in_rp"
                       data-type="report" href="javascript:;"> الشيكات </a></li>

                <li><a class="btn green" data-report-source="10"
                       data-option="#payment_id_div"
                       data-type="report" href="javascript:;"> تقرير الرقابة - سندات الصرف </a></li>


            </ul>
        </fieldset>

        <fieldset class="purple">
            <legend>تقارير المشاريع</legend>
            <ul class="report-menu">

                <li><a class="btn blue" data-report-source="9" data-option="#t_report,#project_div" data-type="report"
                       href="javascript:;">حساب الأستاذ </a></li>


            </ul>
        </fieldset>

        <div id="msg_container"></div>
        <div class="modal-body inline_form">
            <div class="form-group col-sm-8">
                <label class="col-sm-1 control-label"> التقرير</label>

                <div class="col-sm-9">
                    <select name="report" class="form-control" style="width: 250px" id="dp_report">
                        <option value="-1"></option>

                        <option data-op="#date1,#date2,#account1,#account2,#branch" value="1">حساب استاذ بسيط حسب المجموعة</option>
                        <option data-op="#date1,#date2,#account1,#account2,#branch" value="2">حساب استاذ بسيط حسب المجموعة عرض
                            الكل
                        </option>
                        <option data-op="#date1,#date2,#account1,#account2,#branch,#currency" value="3">حساب استاذ بسيط حسب العملة
                        </option>
                        <option data-op="#date1,#date2,#account1,#account2,#branch,#currency"
                        " value="4">حساب استاذ بسيط حسب العملة عرض الكل </option>
                        <option data-op="#date1,#date2,#account1,#account2,#branch,#currency" value="23">حساب استاذ بسيط حسب العملة
                            الحساب
                        </option>
                        <option data-op="#date1,#date2,#account1,#account2,#branch,#currency" value="24">حساب استاذ بسيط حسب العملة
                            الحساب عرض الكل
                        </option>
                        <option data-op="#date1,#date2,#parent,#branch" value="5">حساب استاذالحسابات و توابعها حسب المجموعة</option>
                        <option data-op="#date1,#date2,#parent,#branch" value="6">حساب استاذالحسابات و توابعها حسب المجموعة عرض
                            الكل
                        </option>
                        <option data-op="#date1,#date2,#parent,#branch,#currency" value="7">حساب استاذالحسابات و توابعها حسب
                            العملة
                        </option>
                        <option data-op="#date1,#date2,#parent,#branch,#currency" value="8">حساب استاذالحسابات و توابعها حسب العملة
                            عرض الكل
                        </option>
                        <option data-op="#date1,#date2,#parent,#branch,#currency" value="25">حساب استاذالحسابات و توابعها حسب عملة
                            الحساب
                        </option>
                        <option data-op="#date1,#date2,#parent,#branch,#currency" value="26">حساب استاذالحسابات و توابعها حسب عملة
                            الحساب عرض الكل
                        </option>
                        <option data-op="#date1,#date2,#account1,#account2,#branch" value="9">ميزان المراجعة بالمجاميع</option>
                        <option data-op="#date1,#date2,#account1,#account2,#branch" value="10">ميزان المراجعة بالأرصدة</option>
                        <option data-op="#date1,#date2,#account1,#account2,#branch" value="11">ميزان المراجعة بالأرصدة و المجاميع
                        </option>
                        <option data-op="#date1,#date2,#parent,#branch" value="12">ميزان مراجعه الحسابات وتوابعها بالمجاميع</option>
                        <option data-op="#date1,#date2,#parent,#branch" value="13">ميزان مراجعه الحسابات وتوابعها بالارصدة</option>
                        <option data-op="#date1,#date2,#parent,#branch" value="14">ميزان مراجعه الحسابات وتوابعها بالارصدة و
                            المجاميع
                        </option>
                        <option data-op="#date1,#date2,#branch,#parent,#parent1" value="15">ميزان مراجعه الحسابات وتوابعها بالمجاميع
                            حسب الفروع
                        </option>
                        <option data-op="#date1,#date2,#branch,#parent,#parent1" value="16">ميزان مراجعه الحسابات وتوابعها بالارصدة
                            حسب الفروع
                        </option>
                        <option data-op="#date1,#date2,#branch,#parent,#parent1" value="17">ميزان مراجعه الحسابات وتوابعها بالارصدة
                            و المجاميع حسب الفروع
                        </option>
                        <option data-op="#voucher_id,#branch,#date1,#date2,#account1,#types" value="18">ايصالات القبض حسب المجموعة
                        </option>
                        <option data-op="#voucher_id,#branch,#date1,#date2,#account1,#types,#currency" value="19">ايصالات القبض حسب
                            العملة
                        </option>
                        <option data-op="#payment_id,#branch,#date1,#date2,#types" value="20">سندات الصرف حسب المجموعة</option>
                        <option data-op="#payment_id,#branch,#date1,#date2,#types,#currency" value="21">سندات الصرف حسب العملة
                        </option>
                        <option data-op="#check_type,#date1,#date2" value="22">تقارير الشيكات الواردة و الصادرة</option>


                    </select>
                </div>
            </div>
            <hr/>

            <div class="form-group col-sm-2 rp_prm" id="branch" style="display: none;">
                <label class="col-sm-3 control-label"> الفرع</label>

                <div class="col-sm-9">
                    <select name="report" class="form-control" id="dp_branch">
                        <option></option>
                        <?php foreach ($branches as $row) : ?>
                            <option data-dept="<?= $row['NO'] ?>" value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>


            <div style="clear: both"></div>
            <div class="form-group col-sm-2 rp_prm" id="check_type" style="display: none;">
                <label class="col-sm-3 control-label">شيكات</label>

                <div class="col-sm-9">
                    <select name="check_report" class="form-control" id="dp_check">
                        <option></option>

                        <option data-dept="1" value="1">واردة</option>
                        <option data-dept="2" value="2">صادرة</option>

                    </select>
                </div>
            </div>

            <div class="form-group col-sm-4 rp_prm" id="list">
                <label class="col-sm-3 control-label"> حالة الشيك</label>

                <div class="col-sm-9">
                    <select name="report_list" class="form-control" id="dp_type_list">
                        <option></option>
                        <?php foreach ($type_list as $row) : ?>
                            <option data-dept="<?= $row['CON_NO'] ?>" value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <select name="reports_lists" class="form-control" id="dp_types_lists">
                        <option></option>
                        <?php foreach ($types_lists as $row) : ?>
                            <option data-dept="<?= $row['CON_NO'] ?>" value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group col-sm-2 rp_prm" id="year" style="display: none;">
                <label class="col-sm-3 control-label"> السنة</label>

                <div class="col-sm-9">
                    <input type="text" id="txt_year" class="form-control"/>
                </div>
            </div>
            <div style="clear: both"></div>

            <div class="form-group col-sm-3 rp_prm" id="voucher_id" style="display: none;">
                <label class="col-sm-3 control-label"> رقم الايصال </label>

                <div class="col-sm-9">
                    <input type="text" id="txt_voucher_id" class="form-control"/>
                </div>
            </div>

            <div style="clear: both"></div>

            <div class="form-group col-sm-3 rp_prm" id="payment_id" style="display: none;">
                <label class="col-sm-3 control-label"> سند الصرف</label>

                <div class="col-sm-9">
                    <input type="text" id="txt_payment_id" class="form-control"/>
                </div>
            </div>

            <div style="clear: both"></div>

            <div class="form-group col-sm-3 rp_prm" id="date1" style="display: none;">
                <label class="col-sm-3 control-label"> التاريخ </label>

                <div class="col-sm-9">
                    <input type="text" data-type="date" data-date-format="DD/MM/YYYY" id="txt_date1" class="form-control"/>
                </div>
            </div>

            <div class="form-group col-sm-3 rp_prm" id="date2" style="display: none;">
                <label class="col-sm-3 control-label"> حتى</label>

                <div class="col-sm-9">
                    <input type="text" data-type="date" data-date-format="DD/MM/YYYY" id="txt_date2" class="form-control"/>
                </div>
            </div>

            <div style="clear: both"></div>


            <div class="form-group col-sm-6 rp_prm" id="account1" style="display: none;">
                <label class="col-sm-1 control-label"> رقم الحساب </label>

                <div class="col-sm-9">
                    <input type="number" id="h_txt_account1" class="form-control col-sm-3"/>
                    <input type="text" readonly name="account1" id="txt_account1" class="form-control col-sm-8"/>

                </div>
            </div>
            <div class="clearfix"></div>
            <div class="form-group col-sm-6 rp_prm" id="account2" style="display: none;">
                <label class="col-sm-1 control-label"> حتى</label>

                <div class="col-sm-9">
                    <input type="number" id="h_txt_account2" class="form-control col-sm-3"/>
                    <input type="text" readonly name="account2" id="txt_account2" class="form-control col-sm-8"/>

                </div>
            </div>
            <div style="clear: both"></div>
            <div class="form-group col-sm-3 rp_prm" id="currency" style="display: none;">
                <label class="col-sm-3 control-label">العملة</label>

                <div class="col-sm-9">
                    <select name="report_curr" class="form-control" id="dp_currency">
                        <option value="0"></option>
                        <?php foreach ($currency as $row) : ?>
                            <option data-dept="<?= $row['CURR_ID'] ?>" value="<?= $row['CURR_ID'] ?>"><?= $row['NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div style="clear: both"></div>
            <div class="form-group col-sm-6 rp_prm" id="parent" style="display: none;">
                <label class="col-sm-1 control-label">رقم الحساب</label>

                <div class="col-sm-9">
                    <input type="number" id="h_txt_parent" class="form-control col-sm-3"/>
                    <input type="text" readonly name="parent" id="txt_parent" class="form-control col-sm-8"/>

                </div>
            </div>

            <div style="clear: both"></div>
            <div class="form-group col-sm-6 rp_prm" id="parent1" style="display: none;">
                <label class="col-sm-1 control-label">الى حساب رقم الحساب</label>

                <div class="col-sm-9">
                    <input type="number" id="h_txt_parent1" class="form-control col-sm-3"/>
                    <input type="text" readonly name="parent1" id="txt_parent1" class="form-control col-sm-8"/>

                </div>
            </div>



            <div style="clear: both"></div>
            <div class="form-group col-sm-3 rp_prm" id="branch_dep" style="display: none;">
                <label class="col-sm-3 control-label">الفروع</label>

                <div class="col-sm-9">
                    <select name="report_bran_dep" class="form-control" id="dp_branch_dep">
                        <option></option>
                        <?php foreach ($branch_dep as $row) : ?>
                            <option data-dept="<?= $row['REPORT_NUM'] ?>"
                                    value="<?= $row['REPORT_NUM'] ?>"><?= $row['REPORT_HINTS'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>


            <div style="clear: both"></div>
            <div class="form-group col-sm-3 rp_prm" id="types" style="display: none;">
                <label class="col-sm-3 control-label">النوع</label>

                <div class="col-sm-9">
                    <select name="report_type" class="form-control" id="dp_type">
                        <option></option>
                        <?php foreach ($type as $row) : ?>
                            <option data-dept="<?= $row['CON_NO'] ?>" value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div style="clear: both;"></div>


            <div class="modal fade" id="reportFilterModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span>
                            </button>
                            <h4 class="modal-title">معير البحث</h4>
                        </div>
                        <div class="form-horizontal">
                            <div class="modal-body">

                                <div class="form-group rp_prm" id="t_report" style="display: none;">
                                    <label class="col-sm-3 control-label"> نوع التقرير</label>

                                    <div class="col-sm-7">
                                        <select class="form-control" id="dp_t_report">
                                            <option value="1">حساب استاذ بسيط حسب المجموعة</option>
                                            <option value="101">حساباستاذ بسيط حسب المجموعة xls</option>
                                            <option value="2">حساب استاذ بسيط حسب المجموعة عرض الكل</option>
                                            <option value="3">حساب استاذ يشمل المتبوعين</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group rp_prm" id="m_report" style="display: none;">
                                    <label class="col-sm-3 control-label"> نوع التقرير</label>

                                    <div class="col-sm-7">
                                        <select class="form-control" id="dp_m_report">
                                            <option value="1">ميزان المراجعة بالمجاميع</option>
                                            <option value="2">ميزان المراجعة بالأرصدة</option>
                                            <option value="8">ميزان المراجعة بالأرصدة xls</option>
                                            <option value="3">ميزان المراجعة بالأرصدة و المجاميع</option>
                                            <option value="4">ميزان مراجعه الحسابات وتوابعها بالمجاميع</option>
                                            <option value="5">ميزان المراجعة للحسابات الاساسية</option>
                                            <option value="6">ميزان المراجعة للحسابات تشمل المتبوعين</option>
                                            <option value="7"> (خاص مشاريع) ميزان مراجعة يشمل المتبوعين</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group rp_prm" id="voucher_report" style="display: none;">
                                    <label class="col-sm-3 control-label"> نوع التقرير</label>

                                    <div class="col-sm-7">
                                        <select class="form-control" id="dp_voucher_report">
                                            <option value="1">ايصالات القبض حسب المجموعة</option>
                                            <option value="2">ايصالات القبض حسب العملة</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group rp_prm" id="payment_report" style="display: none;">
                                    <label class="col-sm-3 control-label"> نوع التقرير</label>

                                    <div class="col-sm-7">
                                        <select class="form-control" id="dp_payment_report">
                                            <option value="1">سندات الصرف حسب المجموعة</option>
                                            <option value="2">سندات الصرف حساب العملة</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group rp_prm" id="Checks_report" style="display: none;">
                                    <label class="col-sm-3 control-label"> نوع التقرير</label>

                                    <div class="col-sm-7">
                                        <select class="form-control" id="dp_payment_report">
                                            <option></option>
                                            <option value="1">وارد</option>
                                            <option value="2">صادر</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group rp_prm" id="doc_num" style="display: none;">
                                    <label class="col-sm-3 control-label"> رقم السند </label>

                                    <div class="col-sm-3">
                                        <input type="text" id="doc_id" class="form-control"/>
                                    </div>

                                </div>

                                <div class="form-group rp_prm" id="account1" style="display: none;">
                                    <label class="col-sm-3 control-label"> من حساب </label>

                                    <div class="col-sm-3">
                                        <input type="text" id="h_txt_account_1" class="form-control"/>
                                    </div>
                                    <div class="col-sm-5">
                                        <input type="text" readonly id="txt_account_1" class="form-control"/>
                                    </div>
                                </div>

                                <div class="form-group rp_prm" id="account2" style="display: none;">
                                    <label class="col-sm-3 control-label"> الي حساب</label>

                                    <div class="col-sm-3">
                                        <input type="text" id="h_txt_account_2" class="form-control"/>
                                    </div>
                                    <div class="col-sm-5">
                                        <input type="text" readonly id="txt_account_2" class="form-control"/>
                                    </div>
                                </div>
                                <div class="form-group  rp_prm" id="d_account" style="display: none;">
                                    <label class="col-sm-3 control-label">رقم حساب الايراد</label>

                                    <div class="col-sm-9">
                                        <input type="number" id="h_txt_d_account" class="form-control col-sm-3"/>
                                        <input type="text" readonly name="d_account" id="txt_d_account" class="form-control col-sm-8"/>

                                    </div>
                                </div>
                                <div class="form-group  rp_prm" id="customer_div" style="display: none;">
                                    <label class="col-sm-3 control-label">المستفيد</label>

                                    <div class="col-sm-3">
                                        <input type="text" id="h_txt_customer" class="form-control"/>
                                    </div>
                                    <div class="col-sm-5">
                                        <input type="text" readonly name="customer" id="txt_customer" class="form-control"/>
                                    </div>
                                </div>


                                <div class="form-group  rp_prm" id="customer_div1" style="display: none;">
                                    <label class="col-sm-3 control-label">الي مستفيد</label>

                                    <div class="col-sm-3">
                                        <input type="text" id="h_txt_customer1" class="form-control"/>
                                    </div>
                                    <div class="col-sm-5">
                                        <input type="text" readonly name="customer" id="txt_customer1" class="form-control"/>
                                    </div>
                                </div>

                                <div class="form-group  rp_prm" id="project_div" style="display: none;">
                                    <label class="col-sm-3 control-label">المشروع</label>

                                    <div class="col-sm-3">
                                        <input type="text" id="h_txt_project" class="form-control"/>
                                    </div>
                                    <div class="col-sm-5">
                                        <input type="text" readonly name="customer" id="txt_project" class="form-control"/>
                                    </div>
                                </div>

                                <div class="form-group rp_prm" id="customer_type" style="display: none;">
                                    <label class="col-sm-3 control-label">نوع المستفيد </label>

                                    <div class="col-sm-4">
                                        <select name="report_type" class="form-control" id="customer_type_1">

                                            <?php foreach ($customer_type as $row) : ?>
                                                <option data-dept="<?= $row['CON_NO'] ?>" value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group  rp_prm" id="cars_div" style="display: none;">
                                    <label class="col-sm-3 control-label">السيارة</label>

                                    <div class="col-sm-3">
                                        <input type="text" id="h_txt_car" class="form-control"/>
                                    </div>
                                    <div class="col-sm-5">
                                        <input type="text" readonly name="car" id="txt_car" class="form-control"/>
                                    </div>
                                </div>


                                <div class="form-group  rp_prm" id="payment_id_div" style="display: none;">
                                    <label class="col-sm-3 control-label"> سند الصرف</label>

                                    <div class="col-sm-4">
                                        <input type="text" id="txt_payment_id_1" class="form-control"/>
                                    </div>
                                </div>

                                <div class="form-group" id="date1">
                                    <label class="col-sm-3 control-label"> التاريخ </label>

                                    <div class="col-sm-4">
                                        <input type="text" data-type="date" data-date-format="DD/MM/YYYY" id="txt_date_1" class="form-control"/>
                                    </div>
                                </div>

                                <div class="form-group" id="date2">
                                    <label class="col-sm-3 control-label"> حتى</label>

                                    <div class="col-sm-4">
                                        <input type="text" data-type="date" data-date-format="DD/MM/YYYY" id="txt_date_2" class="form-control"/>
                                    </div>
                                </div>

                                <div class="form-group rp_prm" id="payment_type" style="display: none;">
                                    <label class="col-sm-3 control-label">نوع القبض</label>

                                    <div class="col-sm-4">
                                        <select name="report_type" class="form-control" id="payment_type_1">
                                            <option></option>
                                            <?php foreach ($type as $row) : ?>
                                                <option data-dept="<?= $row['CON_NO'] ?>" value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group rp_prm" id="currency" style="display: none;">
                                    <label class="col-sm-3 control-label"> العملة</label>

                                    <div class="col-sm-4">
                                        <select name="report_curr" class="form-control" id="dp_currency_1">
                                            <option></option>
                                            <?php foreach ($currency as $row) : ?>
                                                <option data-dept="<?= $row['CURR_ID'] ?>" value="<?= $row['CURR_ID'] ?>"><?= $row['NAME'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group rp_prm" id="ACCOUNT_currency" style="display: none;">
                                    <label class="col-sm-3 control-label"> عملة الحساب</label>

                                    <div class="col-sm-4">
                                        <select name="report_curr" class="form-control" id="dp_currency_2">
                                            <option></option>
                                            <?php foreach ($currency as $row) : ?>
                                                <option data-dept="<?= $row['CURR_ID'] ?>" value="<?= $row['CURR_ID'] ?>"><?= $row['NAME'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group rp_prm" id="branch" style="display: none;">
                                    <label class="col-sm-3 control-label"> الفرع</label>

                                    <div class="col-sm-4">
                                        <select name="report" class="form-control" id="dp_branch_1">
                                            <option></option>
                                            <?php foreach ($branches as $row) : ?>
                                                <option data-dept="<?= $row['NO'] ?>" value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group rp_prm" id="fin_source" style="display: none;">
                                    <label class="col-sm-3 control-label">المصدر</label>

                                    <div class="col-sm-4">
                                        <select name="report_fin_source" class="form-control" id="dp_fin_source">
                                            <option></option>
                                            <?php foreach ($fin_sources as $row) : ?>
                                                <option data-dept="<?= $row['CON_NO'] ?>" value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group  rp_prm" id="financial_center_id_div" style="display: none;">
                                    <label class="col-sm-3 control-label">المركز المالي </label>

                                    <div class="col-sm-5">
                                        <input type="text" name="financial_center_id" id="txt_financial_center_id" class="form-control"/>
                                    </div>
                                </div>

                                <div class="form-group rp_prm" id="chapters">
                                    <label class="col-sm-3 control-label"> الباب</label>

                                    <div class="col-sm-4">

                                        <select name="report" class="form-control" id="dp_chapters">
                                            <option value=""></option>
                                            <?php foreach ($chapters as $row) : ?>
                                                <option data-dept="<?= $row['NO'] ?>" value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                                            <?php endforeach; ?>
                                        </select>


                                    </div>
                                </div>

                                <div class="form-group  rp_prm" id="check_id_div" style="display: none;">
                                    <label class="col-sm-3 control-label">رقم الشيك</label>

                                    <div class="col-sm-5">
                                        <input type="text" name="check_id" id="txt_check_id" class="form-control"/>
                                    </div>
                                </div>


                                <div class="form-group  rp_prm" id="c_customer_div" style="display: none;">
                                    <label class="col-sm-3 control-label">العميل</label>

                                    <div class="col-sm-5">
                                        <input type="text" name="c_customer" id="txt_c_customer" class="form-control"/>
                                    </div>
                                </div>


                                <div class="form-group rp_prm" id="check_type_rp" style="display: none;">
                                    <label class="col-sm-3 control-label">نوع القبض</label>

                                    <div class="col-sm-4">
                                        <select name="check_type" class="form-control" id="check_type_1">

                                            <option data-dept="1" value="1">واردة</option>
                                            <option data-dept="2" value="2">صادرة</option>
                                        </select>
                                    </div>
                                </div>


                                <div class="form-group rp_prm" id="check_status_in_rp" style="display: none;">
                                    <label class="col-sm-3 control-label"> حالة الحركة</label>

                                    <div class="col-sm-4">
                                        <select name="check_type" class="form-control" id="check_status_1">
                                            <option value=""></option>
                                            <option value="-1">بدون حركة</option>
                                            <option value="1">إيداع</option>
                                            <option value="2">تحصيل شيكات</option>
                                            <option value="3">شيكات مرجعة</option>
                                            <option value="4">مرجع معاد تحصيله</option>

                                        </select>
                                    </div>
                                </div>

                                <div class="form-group rp_prm" id="check_status_out_rp" style="display: none;">
                                    <label class="col-sm-3 control-label"> حالة الحركة</label>

                                    <div class="col-sm-4">
                                        <select name="check_type" class="form-control" id="check_status_2">
                                            <option value=""></option>
                                            <option value="-1">بدون حركة</option>
                                            <option value="1">صرف شيكات</option>
                                            <option value="2"> إرجاع شيك من البنك</option>
                                            <option value="3"> إلغاء شيك وتسوية الحساب</option>

                                        </select>
                                    </div>
                                </div>

                                <div class="form-group rp_prm" id="banks" style="display: none;">
                                    <label class="col-sm-3 control-label">البنك</label>

                                    <div class="col-sm-4">
                                        <select name="banks" class="form-control" id="dp_banks">
                                            <option></option>
                                            <?php foreach ($banks as $row) : ?>
                                                <option data-dept="<?= $row['CON_NO'] ?>" value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group rp_prm" id="class_type" style="display: none;">
                                    <label class="col-sm-3 control-label"> حالة الاصناف </label>
                                    <div class="col-sm-4">
                                        <select name="class_type" id="dp_class_type" class="form-control" />
                                        <option value="">الكل</option>
                                        <?php foreach($class_type_all as $row) :?>
                                            <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                                        <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group rp_prm" id="class_acount_type" style="display: none;">
                                    <label class="col-sm-3 control-label">نوع الاصناف</label>
                                    <div class="col-sm-4">
                                        <select name="class_acount_type" id="dp_class_acount_type" class="form-control" >
                                            <option value="">الكل</option>
                                            <?php foreach($class_acount_type_all as $row) :?>
                                                <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group rp_prm" id="reserve" style="display: none;">
                                    <label class="col-sm-3 control-label">خصم الحجوزات</label>
                                    <div class="col-sm-4">
                                        <select name="reserve" id="dp_reserve" class="form-control"  >
                                            <option value="" selected >نعم</option>
                                            <option value="2">نعم عدا الطلبات</option>
                                            <option value="1">لا</option>
                                        </select>
                                    </div>
                                </div>


                                <div class="modal-footer">
                                    <button type="button" data-action="report" data-type="31" data-reptype="pdf" class="btn btn-primary">عرض التقرير</button>
                                    <button type="button" data-action="report" data-type="28" data-reptype="xls" class="btn btn-warning">عرض التقرير xls</button>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>

                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->

        </div>
        <div class="modal-footer">
            <button type="button" onclick="javascript:showReport(31);" class="btn btn-success">PDF عرض التقرير</button>

            <button type="button" onclick="javascript:showReport(36);" class="btn btn-success">XLS عرض التقرير</button>

        </div>

    </div>

</div>


<?php
$select_accounts_url = base_url('financial/accounts/public_select_accounts');
$select_parent_url = base_url('financial/accounts/public_select_parent');
$get_id_url = base_url('financial/accounts/public_get_id');
$project_accounts_url = base_url('projects/projects/public_select_project_accounts');
$jasper_report_url =  base_url("JsperReport/showreport?sys=financial&report=fin_pay_rp") ;
$jasper_report =  base_url("JsperReport/showreport?sys=financial") ;
$scripts = <<<SCRIPT
<script>

$(function(){
    $('#list').hide();
    $('#dp_check').change(function(e){

        if ($('#dp_check').val()==1){


            $('#list').show();
            $('#dp_type_list').show();
            $('#dp_types_lists').hide();

        }else  if ($('#dp_check').val()==2){

            $('#list').show();
            $('#dp_type_list').hide();
            $('#dp_types_lists').show();
        }else{

            $('#list').hide();
            $('#dp_type_list').hide();
            $('#dp_types_lists').hide();
        }

    });


    $('#dp_report').on('change',function(){

        var val =parseInt( $(this).val());

        $('.rp_prm').slideUp();

        $($(this).find(':selected').attr('data-op')).slideDown();

    });

    $('input[name="account1"]').click(function(e){
        _showReport('$select_accounts_url/'+$(this).attr('id')+'/-1/' );
    });

    $('input[name="account2"]').click(function(e){
        _showReport('$select_accounts_url/'+$(this).attr('id')+'/-1/' );
    });



    $('input[name="parent"]').click(function(e){
        _showReport('$select_parent_url/'+$(this).attr('id')+'/-1/' );
    });
    $('input[name="parent1"]').click(function(e){
        _showReport('$select_parent_url/'+$(this).attr('id')+'/-1/' );
    });

  $('#txt_project').click(function(e){
       _showReport('$project_accounts_url/'+$(this).attr('id'));
    });



});

function get_account_name(obj){
    $(obj).closest('tr').find('input[name="parent[]"]').val('');

}
function showReport(type){

    var val =parseInt( $('#dp_report').val());


    var url = '$report_path';
    var user_branch={$this->user->branch};

    if(val == 1)
    {

        url  = url +'?type='+type+'&report=FINANCIAL_BOOK_CHAINS&params[]='+$('#h_txt_account1').val()+'&params[]='+$('#txt_date1').val()+'&params[]='+$('#txt_date2').val()+'&params[]='+$('#h_txt_account2').val()+'&params[]='+$('#dp_branch').val()+'&params[]='+
            '&params[]=1&params[]={$this->user->id}' ;

    }
    else if(val == 2)
    {

        url  = url +'?type='+type+'&report=FINANCIAL_BOOK_CHAINS_ALL&params[]='+$('#h_txt_account1').val()+'&params[]='+$('#txt_date1').val()+'&params[]='+$('#txt_date2').val()+'&params[]='+$('#h_txt_account2').val()+'&params[]='+$('#dp_branch').val()+'&params[]='+
            '&params[]=1&params[]={$this->user->id}';

    }
    else if(val == 3)
    {
        if (parseInt($('#dp_currency').val())=='')
        {
            alert("يتوجب عليك تحديد العملة");
            return false;

        }
        else
        {

            url  = url +'?type='+type+'&report=FINANCIAL_BOOK_CHAINS&params[]='+$('#h_txt_account1').val()+'&params[]='+$('#txt_date1').val()+'&params[]='+$('#txt_date2').val()+'&params[]='+$('#h_txt_account2').val()+'&params[]='+$('#dp_branch').val()+'&params[]='+
                $('#dp_currency').val()+'&params[]=3&params[]={$this->user->id}';
        }
    }

    else if(val == 4)
    {
        if (parseInt($('#dp_currency').val())=='')
        {
            alert("يتوجب عليك تحديد العملة");
            return false;

        }
        else
        {

            url  = url +'?type='+type+'&report=FINANCIAL_BOOK_CHAINS_ALL&params[]='+$('#h_txt_account1').val()+'&params[]='+$('#txt_date1').val()+'&params[]='+$('#txt_date2').val()+'&params[]='+$('#h_txt_account2').val()+'&params[]='+$('#dp_branch').val()+'&params[]='+
                $('#dp_currency').val()+'&params[]=3&params[]={$this->user->id}';
        }
    }
    else if(val == 23)
    {
        if (parseInt($('#dp_currency').val())=='')
        {
            alert("يتوجب عليك تحديد العملة");
            return false;

        }
        else
        {

            url  = url +'?type='+type+'&report=FINANCIAL_BOOK_CHAINS&params[]='+$('#h_txt_account1').val()+'&params[]='+$('#txt_date1').val()+'&params[]='+$('#txt_date2').val()+'&params[]='+$('#h_txt_account2').val()+'&params[]='+$('#dp_branch').val()+'&params[]='+
                $('#dp_currency').val()+'&params[]=2&params[]={$this->user->id}';
        }
    }

    else if(val == 24)
    {
        if (parseInt($('#dp_currency').val())=='')
        {
            alert("يتوجب عليك تحديد العملة");
            return false;

        }
        else
        {

            url  = url +'?type='+type+'&report=FINANCIAL_BOOK_CHAINS_ALL&params[]='+$('#h_txt_account1').val()+'&params[]='+$('#txt_date1').val()+'&params[]='+$('#txt_date2').val()+'&params[]='+$('#h_txt_account2').val()+'&params[]='+$('#dp_branch').val()+'&params[]='+
                $('#dp_currency').val()+'&params[]=2&params[]={$this->user->id}';
        }
    }
    else  if(val == 5)
    {

        url  = url +'?type='+type+'&report=FINANCIAL_BOOK_PARENT_REP&params[]='+$('#txt_date1').val()+'&params[]='+$('#txt_date2').val()+'&params[]='+$('#dp_branch').val()+'&params[]=&params[]=1&params[]={$this->user->id}&params[]='+$('#h_txt_parent').val();

    }

    else  if(val == 6)
    {

        url  = url +'?type='+type+'&report=FINANCIAL_BOOK_PARENT_ALL&params[]='+$('#txt_date1').val()+'&params[]='+$('#txt_date2').val()+'&params[]='+$('#dp_branch').val()+'&params[]=&params[]=1&params[]={$this->user->id}&params[]='+$('#h_txt_parent').val();

    }

    else  if(val == 7)
    {
        if (parseInt($('#dp_currency').val())=='')
        {
            alert("يتوجب عليك تحديد العملة");
            return false;

        }
        else
        {

            url  = url +'?type='+type+'&report=FINANCIAL_BOOK_PARENT_REP&params[]='+$('#txt_date1').val()+'&params[]='+$('#txt_date2').val()+'&params[]='+$('#dp_branch').val()+'&params[]='+$('#dp_currency').val()+'&params[]=3 &params[]={$this->user->id}&params[]='+$('#h_txt_parent').val();

        }
    }

    else  if(val == 8)
    {
        if (parseInt($('#dp_currency').val())=='')
        {
            alert("يتوجب عليك تحديد العملة");
            return false;

        }
        else
        {

            url  = url +'?type='+type+'&report=FINANCIAL_BOOK_PARENT_ALL&params[]='+$('#txt_date1').val()+'&params[]='+$('#txt_date2').val()+'&params[]='+$('#dp_branch').val()+'&params[]='+$('#dp_currency').val()+'&params[]=3 &params[]={$this->user->id}&params[]='+$('#h_txt_parent').val();

        }
    }
    else  if(val == 25)
    {
        if (parseInt($('#dp_currency').val())=='')
        {
            alert("يتوجب عليك تحديد العملة");
            return false;

        }
        else
        {

            url  = url +'?type='+type+'&report=FINANCIAL_BOOK_PARENT_REP&params[]='+$('#txt_date1').val()+'&params[]='+$('#txt_date2').val()+'&params[]='+$('#dp_branch').val()+'&params[]='+$('#dp_currency').val()+'&params[]=2 &params[]={$this->user->id}&params[]='+$('#h_txt_parent').val();

        }
    }

    else  if(val == 26)
    {
        if (parseInt($('#dp_currency').val())=='')
        {
            alert("يتوجب عليك تحديد العملة");
            return false;

        }
        else
        {

            url  = url +'?type='+type+'&report=FINANCIAL_BOOK_PARENT_ALL&params[]='+$('#txt_date1').val()+'&params[]='+$('#txt_date2').val()+'&params[]='+$('#dp_branch').val()+'&params[]='+$('#dp_currency').val()+'&params[]=2 &params[]={$this->user->id}&params[]='+$('#h_txt_parent').val();

        }
    }

    else  if(val == 9)
    {

        url  = url +'?type='+type+'&report=ACCOUNT_NAME_SUM&params[]='+$('#h_txt_account1').val()+'&params[]='+$('#h_txt_account2').val()+'&params[]='+$('#txt_date1').val()+'&params[]='+$('#txt_date2').val()+'&params[]='+$('#dp_branch').val();

    }

    else  if(val == 10)
    {

        url  = url +'?type='+type+'&report=ACCOUNT_NAME_rased&params[]='+$('#h_txt_account1').val()+'&params[]='+$('#h_txt_account2').val()+'&params[]='+$('#txt_date1').val()+'&params[]='+$('#txt_date2').val()+'&params[]='+$('#dp_branch').val();

    }

    else  if(val == 11)
    {

        url  = url +'?type='+type+'&report=ACCOUNT_NAME_SUM_rased&params[]='+$('#h_txt_account1').val()+'&params[]='+$('#h_txt_account2').val()+'&params[]='+$('#txt_date1').val()+'&params[]='+$('#txt_date2').val()+'&params[]='+$('#dp_branch').val();

    }
    else  if(val == 12)
    {

        url  = url +'?type='+type+'&report=ACCOUNT_NAME_SUM_P&params[]='+$('#txt_date1').val()+'&params[]='+$('#txt_date2').val()+'&params[]='+$('#dp_branch').val()+'&params[]='+$('#h_txt_parent').val();

    }
    else  if(val == 13)
    {

        url  = url +'?type='+type+'&report=ACCOUNT_NAME_rased_P&params[]='+$('#txt_date1').val()+'&params[]='+$('#txt_date2').val()+'&params[]='+$('#dp_branch').val()+'&params[]='+$('#h_txt_parent').val();

    }
    else  if(val == 14)
    {

        url  = url +'?type='+type+'&report=ACCOUNT_NAME_SUM_rased_P&params[]='+$('#txt_date1').val()+'&params[]='+$('#txt_date2').val()+'&params[]='+$('#dp_branch').val()+'&params[]='+$('#h_txt_parent').val();

    }

    else  if(val == 15)
    {

        url  = url +'?type='+type+'&report=BRANCH_ACCOUNT_NAME_SUM&params[]='+$('#txt_date1').val()+'&params[]='+$('#txt_date2').val()+'&params[]='+$('#h_txt_parent').val()+'&params[]='+$('#h_txt_parent1').val()+'&params[]='+$('#dp_branch').val();
        ;

    }

    else  if(val == 16)
    {

        url  = url +'?type='+type+'&report=ACCOUNT_NAME_rased_BRANCH&params[]='+$('#txt_date1').val()+'&params[]='+$('#txt_date2').val()+'&params[]='+$('#h_txt_parent').val()+'&params[]='+$('#h_txt_parent1').val()+'&params[]='+$('#dp_branch').val();
        ;
    }

    else  if(val == 17)
    {

        url  = url +'?type='+type+'&report=ACCOUNT_NAME_SUM_rased_BRANCH&params[]='+$('#txt_date1').val()+'&params[]='+$('#txt_date2').val()+'&params[]='+$('#h_txt_parent').val()+'&params[]='+$('#h_txt_parent1').val()+'&params[]='+$('#dp_branch').val();
        ;
    }

    else  if(val == 18)
    {

        if (user_branch==1)
        {
            url  = url +'?type='+type+'&report=ESALAT_REP&params[]='+$('#txt_voucher_id').val()+
                '&params[]='+$('#dp_branch').val()+'&params[]='+$('#txt_date1').val()+
                '&params[]='+$('#txt_date2').val()+'&params[]='+$('#dp_type').val()+
                '&params[]='+$('#h_txt_account1').val()+'&params[]='+'&params[]=1&params[]=';
        }
        else
        {
            url  = url +'?type='+type+'&report=ESALAT_BRANCHES&params[]='+$('#txt_voucher_id').val()+
                '&params[]='+$('#dp_branch').val()+'&params[]='+$('#txt_date1').val()+
                '&params[]='+$('#txt_date2').val()+'&params[]='+$('#dp_type').val()+
                '&params[]='+$('#h_txt_account1').val()+'&params[]='+'&params[]=1';
        }



    }
    else  if(val == 19)
    {
        if (parseInt($('#dp_currency').val())=='')
        {
            alert("يتوجب عليك تحديد العملة");
            return false;

        }
        else
        {
            if (user_branch==1)
            {


                url  = url +'?type='+type+'&report=ESALAT_REP&params[]='+$('#txt_voucher_id').val()+
                    '&params[]='+$('#dp_branch').val()+'&params[]='+$('#txt_date1').val()+
                    '&params[]='+$('#txt_date2').val()+'&params[]='+$('#dp_type').val()+
                    '&params[]='+$('#h_txt_account1').val()+'&params[]='+$('#dp_currency').val()+'&params[]=2&params[]=';
            }
            else
            {
                url  = url +'?type='+type+'&report=ESALAT_BRANCHES&params[]='+$('#txt_voucher_id').val()+
                    '&params[]='+$('#dp_branch').val()+'&params[]='+$('#txt_date1').val()+
                    '&params[]='+$('#txt_date2').val()+'&params[]='+$('#dp_type').val()+
                    '&params[]='+$('#h_txt_account1').val()+'&params[]='+$('#dp_currency').val()+'&params[]=2';
            }

        }


    }
    else  if(val == 20)
    {
        url  = url +'?type='+type+'&report=sarf_REP&params[]='+$('#txt_payment_id').val()+
            '&params[]='+$('#dp_branch').val()+'&params[]='+$('#txt_date1').val()+
            '&params[]='+$('#txt_date2').val()+'&params[]='+$('#dp_type').val()+'&params[]='+'&params[]=1';


    }
    else  if(val == 21)
    {
        if (parseInt($('#dp_currency').val())=='')
        {
            alert("يتوجب عليك تحديد العملة");
            return false;

        }
        else
        {
            url  = url +'?type='+type+'&report=sarf_REP&params[]='+$('#txt_payment_id').val()+
                '&params[]='+$('#dp_branch').val()+'&params[]='+$('#txt_date1').val()+
                '&params[]='+$('#txt_date2').val()+'&params[]='+$('#dp_type').val()+
                '&params[]='+$('#dp_currency').val()+'&params[]=2';
        }


    }
    else  if(val == 22)
    {
        if($('#dp_type_list').val()!='')
        {
            url  = url +'?type='+type+'&report=CHICK_in_OUT&params[]='+$('#txt_date1').val()+
                '&params[]='+$('#txt_date2').val()+'&params[]='+$('#dp_check').val()+'&params[]='+$('#dp_type_list').val()
        }
        else if ($('#dp_types_lists').val()!='')
        {
            url  = url +'?type='+type+'&report=CHICK_in_OUT&params[]='+$('#txt_date1').val()+
                '&params[]='+$('#txt_date2').val()+'&params[]='+$('#dp_check').val()+'&params[]='+$('#dp_types_lists').val()
        }
        else
        {
            url  = url +'?type='+type+'&report=CHICK_in_OUT&params[]='+$('#txt_date1').val()+
                '&params[]='+$('#txt_date2').val()+'&params[]='+$('#dp_check').val()+'&params[]='
        }


    }



    _showReport(url);

}
 var selected = 0;
var data_report_source ='';

$(function () {



    $('#accountsModal').on('shown.bs.modal', function () {
        $('#txt_acount_name').focus();
    });

  $('#txt_customer,#txt_customer1').click(function(e){
        _showReport('$customer_url/'+$(this).attr('id') );
    });

    $('#txt_customer,#txt_customer1').click(function(e){
        _showReport('$customer_url/'+$(this).attr('id') );
    });
  $('#txt_car').click(function(e){
        _showReport('$cars_url/'+$(this).attr('id') );
    });

    $('#txt_car').click(function(e){
        _showReport('$cars_url/'+$(this).attr('id') );
    });


     $('#h_txt_account_1,#h_txt_account_2,#txt_d_account').keyup(function(){
            get_account_name($(this));
     });

       $('#txt_account_1,#txt_account_2,#txt_d_account').click(function(e){
             _showReport('$select_parent_url/'+$(this).attr('id')+'/-1' );
        });

    $('a[data-type="report"]').click(function(){

        $('.rp_prm').hide();
        clearForm_any('#reportFilterModal');

        data_report_source = $(this).attr('data-report-source');

        $($(this).attr('data-option')).show();
        $('#reportFilterModal').modal();
    });

$('#check_type_1').change(function(){

    if($(this).val() == 1){
            $('#check_status_in_rp').show();
            $('#check_status_out_rp').hide();
    }else {

        $('#check_status_in_rp').hide();
        $('#check_status_out_rp').show();
    }

});

    $('button[data-action="report"]').click(function(){
        var url='{$report_url}type='+$(this).attr('data-type');
        var f_date =$('#txt_date_1').val();
        var t_date =$('#txt_date_2').val();
        var branch =$('#dp_branch_1').val();
        var curr_id = $('#dp_currency_1').val();
        var account_1 = $('#h_txt_account_1').val();
        var account_2 = $('#h_txt_account_2').val();
        var customer_id = $('#h_txt_customer').val();
        var customer_id1 = $('#h_txt_customer1').val();
        var customer_type = $('#customer_type_1').val();
        var car_id = $('#h_txt_car').val();
        var payment_type =$('#payment_type_1').val();
        var doc_id =$('#doc_id').val();

        var project_account = $('#h_txt_project').val();
		
		var curr_id = have_no_val($('#dp_currency_1').val());
		
		// check if var have value or null //
        function have_no_val(v) {
            if(v == 0 || v == null) {
            return v = '';
        }else {
            return v;
            }
        }
    // End check if var have value or null //
		

        switch(data_report_source){

            case '1':
                var report_id = $('#dp_t_report').val();

                switch(report_id){
                    case '1':

                        url ='/gfc/jsperreport/showreport?sys=financial/archives&report_type=pdf&report=financial_book_chains&p_account_id='+account_1+'&p_from_date='+f_date+'&p_to_date='+t_date+'&p_to_account_id='+account_2+'&p_branches='+branch+'&p_curr_id='+curr_id+'&p_currtype=1&p_username={$this->user->id}&p_source='+$('#dp_fin_source').val();
                        break;

            case '101':

                        url ='/gfc/jsperreport/showreport?sys=financial/archives&report_type=xls&report=financial_book_chains_xls&p_account_id='+account_1+'&p_from_date='+f_date+'&p_to_date='+t_date+'&p_to_account_id='+account_2+'&p_branches='+branch+'&p_curr_id='+curr_id+'&p_currtype=1&p_username={$this->user->id}&p_source='+$('#dp_fin_source').val();
                        break;

                    case '2':

                        url +='&report=FINANCIAL_BOOK_CHAINS_ALL&params[]='+account_1+'&params[]='+f_date+'&params[]='+t_date+'&params[]='+account_2+'&params[]=&params[]='+curr_id+'&params[]=1&params[]={$this->user->id}&params[]='+$('#dp_fin_source').val();
                        break;

                     case '3':

                        url +='&report=FINANCIAL_BOOK_CHAINS_NOGroup&params[]='+account_1+'&params[]='+f_date+'&params[]='+t_date+'&params[]='+account_2+'&params[]=&params[]='+curr_id+'&params[]=1&params[]={$this->user->id}&params[]='+$('#dp_fin_source').val();
                        break;
                }


                break;

            case '2':
                var report_id = $('#dp_m_report').val();

                switch(report_id){
                    case '1':
                        url +='&report=ACCOUNT_NAME_SUM&params[]='+account_1+'&params[]='+account_2+'&params[]='+f_date+'&params[]='+t_date+'&params[]='+branch+'&params[]='+$('#dp_fin_source').val()+'&params[]='+$('#dp_currency_2').val()+'&params[]='+$('#txt_financial_center_id').val()+'&params[]='+$('#dp_currency_1').val()+'&params[]='+$('#dp_chapters').val();
                        break;
                    case '2':
                        url +='&report=ACCOUNT_NAME_rased&params[]='+account_1+'&params[]='+account_2+'&params[]='+f_date+'&params[]='+t_date+'&params[]='+branch+'&params[]='+$('#dp_fin_source').val()+'&params[]='+$('#dp_currency_2').val()+'&params[]='+$('#txt_financial_center_id').val()+'&params[]='+$('#dp_currency_1').val()+'&params[]='+$('#dp_chapters').val();
                        break;
                    case '3':
                        url +='&report=ACCOUNT_NAME_SUM_rased&params[]='+account_1+'&params[]='+account_2+'&params[]='+f_date+'&params[]='+t_date+'&params[]='+branch+'&params[]='+$('#dp_fin_source').val()+'&params[]='+$('#dp_currency_2').val()+'&params[]='+$('#txt_financial_center_id').val()+'&params[]='+$('#dp_currency_1').val()+'&params[]='+$('#dp_chapters').val();
                        break;
                    case '4':
                        url +='&report=ACCOUNT_NAME_rased_SumP&params[]='+account_1+'&params[]='+account_2+'&params[]='+f_date+'&params[]='+t_date+'&params[]='+branch+'&params[]='+$('#dp_currency_2').val()+'&params[]='+$('#dp_fin_source').val();//+'&params[]='+$('#txt_financial_center_id').val();
                        break;
                    case '5':
                        url +='&report=FINANCIAL_CHAINS_TB_REVIEW&params[]='+f_date+'&params[]='+t_date+'&params[]='+account_1+'&params[]='+account_2;
                        break;
                    case '6':
                        url +='&report=ACCOUNT_NAME_SUM_rased_WITHACC&params[]='+account_1+'&params[]='+account_2+'&params[]='+f_date+'&params[]='+t_date+'&params[]='+branch+'&params[]='+$('#dp_fin_source').val()+'&params[]='+$('#dp_currency_2').val()+'&params[]='+$('#txt_financial_center_id').val()+'&params[]='+$('#dp_currency_1').val();
                        break;
                    case '7':
                        url +='&report=PROJ_ACCOUNT_SUM_rased_WITHACC&params[]='+account_1+'&params[]='+account_2+'&params[]='+f_date+'&params[]='+t_date+'&params[]='+branch+'&params[]='+$('#dp_fin_source').val()+'&params[]='+$('#dp_currency_2').val()+'&params[]='+$('#txt_financial_center_id').val()+'&params[]='+$('#dp_currency_1').val();
                        break;
                    case '8':
                        url ='{$report_jasper_url}report_type=xls&report=account_name_rased_xls&p_account_id='+account_1+'&p_to_account_id='+account_2+'&p_from_date='+f_date+'&p_to_date='+t_date+'&p_branches='+branch+'&p_source='+$('#dp_fin_source').val()+'&p_curr_id='+$('#dp_currency_2').val()+'&p_fin_center='+$('#txt_financial_center_id').val()+'&p_curr_id_2='+$('#dp_currency_1').val()+'&params[]='+$('#dp_chapters').val();
                        break;
                }
                break;
            case '3':

                var report_id = $('#dp_voucher_report').val();

                    switch(report_id){
                        case '1':
                           // url +='&report=ESALAT_REP&params[]='+doc_id+'&params[]='+branch+'&params[]='+f_date+'&params[]='+t_date+'&params[]='+account_1+'&params[]='+payment_type+'&params[]='+curr_id+'&params[]=1&params[]='+$('#h_txt_d_account').val();
                            url +='&report=ESALAT_REP&params[]='+doc_id+'&params[]='+branch+'&params[]='+f_date+'&params[]='+t_date+'&params[]='+payment_type+'&params[]='+account_1+'&params[]='+curr_id+'&params[]=&params[]='+$('#h_txt_d_account').val();

                            break;

                        case '2':
                            url +='&report=ESALAT_BRANCHES&params[]='+doc_id+'&params[]='+branch+'&params[]='+f_date+'&params[]='+t_date+'&params[]='+payment_type+'&params[]='+account_1+'&params[]='+curr_id+'&params[]=&params[]='+$('#h_txt_d_account').val();
                            break;


                    }

                break;
            case '4':
                var report_id = $('#dp_payment_report').val();

                    switch(report_id){
                        case '1':
                            url +='&report=sarf_REP&params[]='+doc_id+'&params[]='+branch+'&params[]='+f_date+'&params[]='+t_date+'&params[]='+payment_type+'&params[]='+curr_id+'&params[]=1';
                            break;

                        case '2':
                            url +='&report=sarf_REP&params[]='+doc_id+'&params[]='+branch+'&params[]='+f_date+'&params[]='+t_date+'&params[]='+payment_type+'&params[]='+curr_id+'&params[]=2';
                            break;

                    }
                break;
            case '5':
                 if($('#check_type_1').val() == 1)
                    url  ='{$jasper_report}/accounts&report=Accounts_Checks_List_'+$(this).attr('data-reptype')+'&report_type='+$(this).attr('data-reptype')+'&p_convert_cash_bank_from='+f_date+'&p_convert_cash_bank_to='+t_date+'&p_check_id='+$('#txt_check_id').val()+'&p_check_bank_id='+$('#dp_banks').val()+'&p_check_customer='+$('#txt_c_customer').val()+'&p_checks_status='+$('#check_status_1').val()+'&p_checks_processing_debit='+$('#h_txt_account_1').val();
                   // url +='&report=CHECKS_PORTFOLIO_PROCESSING_LIST&params[]=&params[]=&params[]='+f_date+'&params[]='+t_date+'&params[]='+$('#txt_check_id').val()+'&params[]='+$('#dp_banks').val()+'&params[]='+$('#txt_c_customer').val()+'&params[]='+$('#h_txt_account_1').val()+'&params[]='+$('#check_status_1').val();
                 else
                    url  ='{$jasper_report}/accounts&report=Accounts_Checks_Pay_'+$(this).attr('data-reptype')+'&report_type='+$(this).attr('data-reptype')+'&p_convert_cash_bank_from='+f_date+'&p_convert_cash_bank_to='+t_date+'&p_check_id='+$('#txt_check_id').val()+'&p_check_bank_id='+$('#dp_banks').val()+'&p_check_customer='+$('#txt_c_customer').val()+'&p_checks_status='+$('#check_status_2').val()+'&p_check_currency='+curr_id+'&p_checks_processing_debit='+$('#h_txt_account_1').val();
                  //  url +='&report=CHECKS_PORTFOLIO_PROCESSING_PAY&params[]='+f_date+'&params[]='+t_date+'&params[]='+$('#txt_check_id').val()+'&params[]='+$('#dp_banks').val()+'&params[]='+$('#txt_c_customer').val()+'&params[]='+$('#h_txt_account_1').val()+'&params[]='+$('#check_status_2').val();


                break;


                 case '6':
                var report_id = $('#dp_t_report').val();

                switch(report_id){
                    case '1':

                        url +='&report=FINANCIAL_BOOK_CHAINS_CUSTOMER&params[]='+customer_id+'&params[]='+f_date+'&params[]='+t_date+'&params[]='+customer_id+'&params[]=&params[]='+curr_id+'&params[]=1&params[]={$this->user->id}';
                        break;
  			case '101':

                        url ='/gfc/jsperreport/showreport?sys=financial/archives&report_type=xls&report=financial_book_chains_customer_xls&p_account_id='+customer_id+'&p_from_date='+f_date+'&p_to_date='+t_date+'&p_to_account_id='+customer_id+'&params[]=&p_curr_id='+curr_id+'&p_currtype=1&p_username={$this->user->id}';
                        break;

                    case '2':

                        url +='&report=FINANCIAL_BOOK_CHAINS_ALL-CUSTOMER&params[]='+customer_id+'&params[]='+f_date+'&params[]='+t_date+'&params[]='+customer_id+'&params[]=&params[]='+curr_id+'&params[]=1&params[]={$this->user->id}';
                        break;
                }


                break;

                 case '7':
                var report_id = $('#dp_t_report').val();

                switch(report_id){
                    case '1':

                        url +='&report=FINANCIAL_BOOK_CHAINS_CAR&params[]='+account_1+'&params[]='+f_date+'&params[]='+t_date+'&params[]='+car_id+'&params[]=&params[]='+curr_id+'&params[]=1&params[]={$this->user->id}';
                        break;

                    case '2':

                        url +='&report=FINANCIAL_BOOK_CHAINS_ALL-CAR&params[]='+account_1+'&params[]='+f_date+'&params[]='+t_date+'&params[]='+car_id+'&params[]=&params[]='+curr_id+'&params[]=1&params[]={$this->user->id}';
                        break;
                }


                break;

        case '8':
                var report_id = $('#dp_t_report').val();

                switch(report_id){
                    case '1':

                        url +='&report=CUSTOMERS_BALANCES&params[]='+customer_id+'&params[]='+customer_id1+'&params[]='+f_date+'&params[]='+t_date+'&params[]='+customer_type+'&params[]='+account_1;
                        break;

                    case '2':

                        url +='&report=CUSTOMERS_BALANCES&params[]='+customer_id+'&params[]='+customer_id1+'&params[]='+f_date+'&params[]='+t_date+'&params[]='+customer_type+'&params[]='+account_1;
                        break;
                }


                break;
case '9':
 var report_id = $('#dp_t_report').val();

                switch(report_id){
                    case '1':

                        url +='&report=FINANCIAL_BOOK_CHAINS_projects&params[]='+project_account+'&params[]='+f_date+'&params[]='+t_date+'&params[]='+project_account+'&params[]=&params[]='+curr_id+'&params[]=1&params[]={$this->user->id}';
                        break;

                    case '2':

                        url +='&report=FINANCIAL_BOOK_CHAINS_ALLprojects&params[]='+project_account+'&params[]='+f_date+'&params[]='+t_date+'&params[]='+project_account+'&params[]=&params[]='+curr_id+'&params[]=1&params[]={$this->user->id}';
                        break;
                }
break;


case '10':

 url  ='{$jasper_report_url}&report_type=xls&Parameter1='+$('#txt_payment_id_1').val()+'&Parameter2='+f_date+'&Parameter3='+t_date;

break;


case '11':

url  ='{$jasper_report}/financial&report=stores_surplus_deficit'+'&report_type=pdf'+'&p_account_id='+$('#h_txt_account_1').val()+'&p_from_date='+$('#txt_date_1').val()+'&p_to_date='+$('#txt_date_2').val()+'&p_to_account_id='+$('#h_txt_account_2').val()+'&p_branches='+$('#dp_branch_1').val()+'&p_chapter='+$('#dp_chapters').val()+'&p_fin_center='+$('#txt_financial_center_id').val()+'&p_curr_id_2='+$('#dp_currency_2').val()+'&p_source='+$('#dp_fin_source').val()+'&p_class_acount_type='+$('#dp_class_acount_type').val()+'&p_class_type='+$('#dp_class_type').val()+'&p_reserve='+$('#dp_reserve').val()+'&p_currtype='+$('#dp_currency_1').val()+'';

break;





        }

        _showReport(url);

    });
});


  function get_account_name(obj){


                get_dataWithOutLoading('{$get_id_url}',{id:$(obj).val()},function(data){

                    if(data.length > 0){

                        $('#'+(obj.attr('id').replace('h_',''))).val(data[0].ACOUNT_NAME+' ('+data[0].CURR_NAME+')');
                    }else{
                        $('#'+(obj.attr('id').replace('h_',''))).val('');
                    }
                });



    }


function select_account(id,name,curr){
    selected = id;


    $('#menuModal').modal();
}

</script>

SCRIPT;

sec_scripts($scripts);

?>

