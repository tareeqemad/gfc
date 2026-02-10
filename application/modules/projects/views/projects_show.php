<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 11/01/15
 * Time: 11:28 ص
 */
$back_url = base_url('projects/projects');
$back_archive_url = base_url('projects/projects/archive_last');
$return_project_preparid_url = base_url('projects/projects/return_project_preparid'); //ارجاع المشروع الى مرحلة الاعداد

$store_item_url = base_url('stores/classes/public_project_index');
$delete_url = base_url('projects/projects/delete_details');
$change_calculation_cable_url = base_url('projects/projects/change_calculation_cable');

$print_url = base_url('/reports');
$report_url = base_url("JsperReport/showreport?sys=technical");

$req_url = base_url("stores/stores_payment_request/create");
$price_url = base_url('stores/classes/project_item_price');
$transfer_url = base_url('projects/projects/transfer');

$select_request_url = base_url('technical/requests/public_index/');

$in_use_url = base_url('projects/projects/update_inUse');

if (!isset($result))
    $result = array();
$HaveRs = count($result) > 0;

$rs = $HaveRs ? $result[0] : $result;

$loadFlow = isset($loadFlow) ? $loadFlow : false;

$CanAdopt = $HaveRs ? ($rs['PROJECT_CASE'] == 1 && $action == 'tec' ? true :
    ($rs['PROJECT_CASE'] == 3 && ($action == 'plan' || $action == 'planSCADA') ? true :
        ($rs['PROJECT_CASE'] == 4 && ($action == 'plan_admin' || $action == 'plan_adminSCADA') ? true :
            ($rs['PROJECT_CASE'] == 5 && $action == 'com' ? true :
                ($rs['PROJECT_CASE'] == 6 && $action == 'branch_admin' ? true :
                    ($rs['PROJECT_CASE'] == 7 && $action == 'low' ? true :
                        ($rs['PROJECT_CASE'] == 8 && $action == 'Maintenance' ? true :
                            ($rs['PROJECT_CASE'] == 9 && $action == 'Maintenance_admin' ? true :
                                ($rs['PROJECT_CASE'] == 10 && $action == 'tec_admin' ? true : ($rs['PROJECT_CASE'] == 11 && $action == 'exchange' ? true : false)))))))))) : false;


//$CanAdopt = $CanAdopt && !$loadFlow;
// mkilani
$can_close = ($HaveRs and $action == 'close_project' and $rs['PROJECT_CASE'] != 16) ? true : false;
$can_adopt_gis = ($HaveRs and $action == 'gis' and $rs['PROJECT_CASE'] != 15) ? true : false;
$request_id = isset($rs['REQUEST_ID'])? $rs['REQUEST_ID'] :'';
$monitoring_cost  = isset($rs['MONITORING_COST '])? $rs['MONITORING_COST'] :'';
$rs_design_cost  = isset($rs['DESIGN_COST'])? $rs['DESIGN_COST'] :$design_cost;
$replace_request_code  = $HaveRs ? $rs['REPLACE_REQUEST_CODE'] : "";

$project_case  = $HaveRs ? $rs['PROJECT_CASE'] : "";
$project_tec_code  = $HaveRs ? $rs['PROJECT_TEC_CODE'] : "";

?>
<div class="row">
    <div class="toolbar">
        <div class="caption"><?= $title ?></div>
        <ul>
            <?php if (HaveAccess($back_url)): ?>
                <li><a href="<?= $back_url ?>"><i class="icon icon-reply"></i> </a> </li><?php endif; ?>
            <li><a onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a>
            </li>
        </ul>
    </div>
    <div class="form-body">
        <div id="msg_container"></div>
        <div id="container">
            <form class="form-form-vertical" id="projects_form" method="post"
                  action="<?= base_url('projects/projects/' . ($HaveRs ? ($action == 'index' ? 'edit' : $action) : 'create')) ?>"
                  role="form" novalidate="novalidate">
                <div class="modal-body inline_form">
                    <div class="form-group  col-sm-1">
                        <label class="control-label"> الرقم </label>
                        <div>
                            <input type="text" value="<?= $HaveRs ? $rs['PROJECT_SERIAL'] : "" ?>" readonly
                                   name="project_serial" id="txt_project_serial" class="form-control">
                        </div>
                    </div>
                    <div class="form-group  col-sm-1">
                        <label class="control-label"> رقم الطلب </label>
                        <div>

                            <input type="text" value="<?= $HaveRs ? $rs['REQUEST_CODE'] : "" ?>" readonly
                                   name="request_id" id="txt_request_id" class="form-control">
                            <input type="hidden" value="<?= $HaveRs ? $rs['REQUEST_ID'] : "" ?>" readonly
                                   name="request_id" id="h_txt_request_id" class="form-control">
                        </div>
                    </div>
                    <div class="form-group  col-sm-1">
                        <label class="control-label">الفرع </label>
                        <div>

                            <select type="text" name="branch" id="dp_branch" class="form-control">

                                <?php foreach ($branches as $row) : ?>
                                    <option <?= $HaveRs ? ($rs['BRANCH'] == $row['NO'] ? 'selected' : '') : '' ?>
                                            value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                                <?php endforeach; ?>
                            </select></div>
                    </div>
                    <div class="form-group  col-sm-2">
                        <label class="control-label"> التصنيف الفنى للمشروع

                        </label>
                        <div>
                            <select class="form-control" name="project_tec_type" id="dp_project_tec_type">
                                <?php foreach ($project_tec_type as $row) : ?>
                                    <option <?= $HaveRs ? ($rs['PROJECT_TEC_TYPE'] == $row['CON_NO'] ? 'selected' : '') : '' ?>
                                            value="<?= $row['CON_NO'] ?>"
                                            data-tec="<?= $row['ACCOUNT_ID'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group  col-sm-1">
                        <label class="control-label"> الرقم الفني </label>
                        <div>
                            <input type="text" value="<?= $HaveRs ? $rs['PROJECT_TEC_CODE'] : "" ?>" readonly
                                   name="project_tec_code" id="txt_project_tec_code" class="form-control">

                        </div>
                    </div>
                    <div class="form-group  col-sm-1">
                        <label class="control-label"> نوع المشروع
                        </label>
                        <div>
                            <select class="form-control" name="project_type" id="dp_project_type">
                                <?php foreach ($project_type as $row) : ?>
                                    <option <?= $HaveRs ? ($rs['PROJECT_TYPE'] == $row['CON_NO'] ? 'selected' : '') : '' ?>
                                            value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group  col-sm-2">
                        <label class="control-label">حساب المستفيد
                        </label>
                        <div>
                            <input name="customer_id" type="text" value="<?= $HaveRs ? $rs['CUSTOMER_ID'] : "" ?>"
                                   id="h_txt_customer_id" class="form-control">


                        </div>
                    </div>
                    <div class="form-group  col-sm-3">
                        <label class="control-label"> اسم المشروع </label>
                        <div>
                            <input type="text" name="project_name" value="<?= $HaveRs ? $rs['PROJECT_NAME'] : "" ?>"
                                   data-val="true" data-val-required="حقل مطلوب" id="txt_project_name"
                                   class="form-control">
                            <span class="field-validation-valid" data-valmsg-for="project_name"
                                  data-valmsg-replace="true"></span>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group  col-sm-2">
                        <label class="control-label">تاريخ إعداد المشروع
                        </label>
                        <div>
                            <input name="project_design_date" data-val="true"
                                   value="<?= $HaveRs ? $rs['PROJECT_DESIGN_DATE'] : "" ?>"
                                   data-val-required="حقل مطلوب" id="txt_project_design_date" data-type="date"
                                   data-date-format="DD/MM/YYYY" data-val-regex="التاريخ غير صحيح!"
                                   data-val-regex-pattern="<?= date_format_exp() ?>" class="form-control">
                            <span class="field-validation-valid" data-valmsg-for="project_design_date"
                                  data-valmsg-replace="true"></span>

                        </div>

                    </div>
                    <div class="form-group  col-sm-2">
                        <label class="control-label"> تاريخ صلاحية تصميم المشروع
                        </label>
                        <div>
                            <input name="project_design_valid_date" data-val="true"
                                   value="<?= $HaveRs ? $rs['PROJECT_DESIGN_VALID_DATE'] : "" ?>"
                                   data-val-required="حقل مطلوب" id="txt_project_design_valid_date" data-type="date"
                                   data-date-format="DD/MM/YYYY" data-val-regex="التاريخ غير صحيح!"
                                   data-val-regex-pattern="<?= date_format_exp() ?>" class="form-control">
                            <span class="field-validation-valid" data-valmsg-for="project_design_valid_date"
                                  data-valmsg-replace="true"></span>

                        </div>
                    </div>


                    <div class="form-group  col-sm-1">
                        <label class="control-label">المحول المقترح
                        </label>
                        <div>
                            <input name="power_adapter_name" type="hidden"
                                   value="<?= $HaveRs ? $rs['POWER_ADAPTER_NAME'] : "" ?>"
                                   id="h_txt_power_adapter_namee" class="form-control">
                            <input name="power_adapter_name_name" value="<?= $HaveRs ? $rs['PADAPTER_NAME'] : "" ?>"
                                   readonly id="txt_power_adapter_namee" class="form-control">
                        </div>
                    </div>

                    <div class="form-group  col-sm-1">
                        <label class="control-label"> اتجاه المغذى
                        </label>
                        <div>
                            <select class="form-control" name="power_adapter_direction" id="dp_power_adapter_direction">
                                <?php foreach ($power_adapter_direction as $row) : ?>
                                    <option <?= $HaveRs ? ($rs['POWER_ADAPTER_DIRECTION'] == $row['CON_NO'] ? 'selected' : '') : '' ?>
                                            value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group  col-sm-1">

                        <label class="control-label">نوع الشبكة
                        </label>
                        <div>
                            <select class="form-control" name="power_adapter_network" id="dp_power_adapter_network">
                                <?php foreach ($power_adapter_network as $row) : ?>
                                    <option <?= $HaveRs ? ($rs['POWER_ADAPTER_NETWORK'] == $row['CON_NO'] ? 'selected' : '') : '' ?>
                                            value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group  col-sm-2">
                        <label class="control-label"> العنوان
                        </label>
                        <div>
                            <input name="address" data-val="true" value="<?= $HaveRs ? $rs['ADDRESS'] : "" ?>"
                                   data-val-required="حقل مطلوب" id="txt_address" class="form-control">
                            <span class="field-validation-valid" data-valmsg-for="address"
                                  data-valmsg-replace="true"></span>

                        </div>
                    </div>
                    <div class="form-group  col-sm-1">

                        <label class="control-label">قوة الاشتراك
                        </label>
                        <div>
                            <select class="form-control" name="power_type" id="dp_power_type">
                                <?php foreach ($POWER_TYPE as $row) : ?>
                                    <option <?= $HaveRs ? ($rs['POWER_TYPE'] == $row['CON_NO'] ? 'selected' : '') : '' ?>
                                            value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group  col-sm-1">

                        <label class="control-label">القوة
                        </label>
                        <div>
                            <input name="power_connection_type" data-val-regex="المدخل غير صحيح؟!"
                                   data-val-regex-pattern="(\d*[.])?\d+"
                                   value="<?= $HaveRs ? $rs['POWER_CONNECTION_TYPE'] : "" ?>"
                                   id="txt_power_connection_type" class="form-control">
                            <span class="field-validation-valid" data-valmsg-for="power_connection_type"
                                  data-valmsg-replace="true"></span>

                        </div>
                    </div>
                    <hr>
                    <div class="form-group  col-sm-1">
                        <label class="control-label">العملة
                        </label>
                        <div>
                            <select class="form-control" name="curr_id" id="dp_curr_id">
                                <?php foreach ($currency as $row) : ?>

                                    <option <?= $HaveRs ? (intval($rs['CURR_ID']) == intval($row['CURR_ID']) ? 'selected' : '') : '' ?>
                                            data-val="<?= $row['VAL'] ?>"
                                            value="<?= $row['CURR_ID'] ?>"><?= $row['CURR_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>

                        </div>
                    </div>
                    <div class="form-group  col-sm-1">
                        <label class="control-label">سعر العملة
                        </label>
                        <div>
                            <input name="curr_value" value="<?= $HaveRs ? $rs['CURR_VALUE'] : "" ?>" readonly
                                   id="txt_curr_value" class="form-control">
                            <span class="field-validation-valid" data-valmsg-for="curr_value"
                                  data-valmsg-replace="true"></span>

                        </div>

                    </div>
                    <div class="form-group  col-sm-2">
                        <label class="control-label">نوع مساهمة الشركة
                        </label>
                        <div>
                            <select class="form-control" name="company_value_type" id="dp_company_value_type">
                                <?php foreach ($company_value_type as $row) : ?>
                                    <option <?= $HaveRs ? ($rs['COMPANY_VALUE_TYPE'] == $row['CON_NO'] ? 'selected' : '') : '' ?>
                                            value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group  col-sm-1">
                        <label class="control-label">قيمة المساهمة
                        </label>
                        <div>
                            <input name="company_value" data-val="true"
                                   value="<?= $HaveRs ? $rs['COMPANY_VALUE'] : "" ?>" data-val-regex="المدخل غير صحيح؟!"
                                   data-val-regex-pattern="<?= float_format_exp() ?>" id="txt_company_value"
                                   class="form-control">
                            <span class="field-validation-valid" data-valmsg-for="company_value"
                                  data-valmsg-replace="true"></span>

                        </div>
                    </div>
                    <div class="form-group  col-sm-2">
                        <label class="control-label">رسوم التخطيط</label>
                        <div>
                            <input name="design_cost" data-val="true"
                                   value="<?= $HaveRs ? $rs['DESIGN_COST'] : $design_cost ?>"
                                   data-val-regex="المدخل غير صحيح؟!" data-val-regex-pattern="<?= float_format_exp() ?>"
                                   id="txt_design_cost" class="form-control ltr">
                            <span class="field-validation-valid" data-valmsg-for="design_cost"
                                  data-valmsg-replace="true"></span>

                        </div>
                    </div>
                    <div class="form-group  col-sm-2">
                        <label class="control-label">رسوم الإشراف</label>
                        <div>
                            <input name="monitoring_cost" data-val="true"
                                   value="<?= $HaveRs ? $rs['MONITORING_COST'] : 0 ?>"
                                   data-val-regex="المدخل غير صحيح؟!" data-val-regex-pattern="<?= float_format_exp() ?>"
                                   id="txt_monitoring_cost" class="form-control" dir="ltr">
                            <span class="field-validation-valid" data-valmsg-for="monitoring_cost"
                                  data-valmsg-replace="true"></span>
                        </div>
                    </div>


                    <div class="form-group  col-sm-2">
                        <label class="control-label">رسوم التنفيذ و التركيب
                        </label>
                        <div>
                            <input name="supervision_cost" data-val="true"
                                   value="<?= $HaveRs ? $rs['SUPERVISION_COST'] : $supervision_cost ?>"
                                   data-val-regex="المدخل غير صحيح؟!" data-val-regex-pattern="<?= float_format_exp() ?>"
                                   id="txt_supervision_cost" class="form-control ltr">
                            <span class="field-validation-valid" data-valmsg-for="supervision_cost"
                                  data-valmsg-replace="true"></span>


                        </div>
                    </div>
                    <div class="form-group  col-sm-1">
                        <label class="control-label"> رسوم أخرى</label>
                        <div>
                            <input name="extra_cost" data-val="true" value="<?= $HaveRs ? $rs['EXTRA_COST'] : "" ?>"
                                   data-val-regex="المدخل غير صحيح؟!" data-val-regex-pattern="<?= float_format_exp() ?>"
                                   id="txt_extra_cost" class="form-control ltr">
                            <span class="field-validation-valid" data-valmsg-for="extra_cost"
                                  data-valmsg-replace="true"></span>
                        </div>

                    </div>
                    <div class="form-group  col-sm-1">
                        <label class="control-label"> رسوم مرجعة</label>
                        <div>
                            <input readonly name="return_cost" data-val="true" value="<?= $HaveRs ? $rs['RETURN_COST'] : "" ?>"
                                   data-val-regex="المدخل غير صحيح؟!" data-val-regex-pattern="<?= float_format_exp() ?>"
                                   id="txt_return_cost" class="form-control ltr">
                            <span class="field-validation-valid" data-valmsg-for="return_cost"
                                  data-valmsg-replace="true"></span>
                        </div>

                    </div>
                    <div class="form-group  col-sm-1">
                        <label class="control-label">اعمال مدانية</label>
                        <div>
                            <input name="civil_works" data-val="true" value="<?= $HaveRs ? $rs['CIVIL_WORKS'] : "" ?>"
                                   data-val-regex="المدخل غير صحيح؟!" data-val-regex-pattern="<?= float_format_exp() ?>"
                                   id="txt_civil_works" class="form-control ltr">
                            <span class="field-validation-valid" data-valmsg-for="civil_works"
                                  data-valmsg-replace="true"></span>
                        </div>

                    </div>
                    <hr>
                    <div class="form-group  col-sm-2">
                        <label class="control-label">
                            الجهة المانحة </label>
                        <div>
                            <input name="donor_name" value="<?= $HaveRs ? $rs['DONOR_NAME'] : "" ?>" id="txt_donor_name"
                                   class="form-control">
                        </div>
                    </div>
                    <div class="form-group  col-sm-2">
                        <label class="control-label"> رقم حساب المشروع-السابق

                        </label>
                        <div>
                            <input name="old_account_id" value="<?= $HaveRs ? $rs['OLD_ACCOUNT_ID'] : "" ?>"
                                   id="txt_old_account_id" class="form-control">
                            <span class="field-validation-valid" data-valmsg-for="old_account_id"
                                  data-valmsg-replace="true"></span>
                        </div>
                    </div>
                    <div class="form-group  col-sm-2">
                        <label class="control-label">
                            الرقم الفني القديم
                        </label>
                        <div>
                            <input name="old_project_tec_code" value="<?= $HaveRs ? $rs['OLD_PROJECT_TEC_CODE'] : "" ?>"
                                   id="txt_old_project_tec_code" class="form-control">
                            <span class="field-validation-valid" data-valmsg-for="old_project_tec_code"
                                  data-valmsg-replace="true"></span>
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">الأسعار تشمل ض.ق.م</label>
                        <div class="">
                            <input type="hidden" name="under_tax" value="<?= $HaveRs ? $rs['UNDER_TAX'] : '2' ?>"/>
                            <select disabled id="under_tax" data-curr="false" class="form-control">
                                <option></option>
                                <option <?= $HaveRs ? ($rs['UNDER_TAX'] == 1 ? 'selected' : '') : '' ?> value="1">
                                    يشمل
                                </option>
                                <option <?= $HaveRs ? ($rs['UNDER_TAX'] == 2 ? 'selected' : '') : '' ?> value="2">لا
                                    يشمل
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-sm-1">
                        <label class="control-label"> نسبة الضريبة </label>
                        <div class="">
                            <input type="text" name="tax"
                                   value="<?= $HaveRs && $rs['TAX'] != null ? $rs['TAX'] : "16" ?>"
                                   data-val-regex="المدخل غير صحيح!"
                                   readonly
                                   data-val-regex-pattern="^\$?([0-9]{1,3},([0-9]{3},)*[0-9]{3}|[0-9]+)(.[0-9]+)?$"
                                   data-val="true" data-val-required="حقل مطلوب" id="tax" class="form-control valid">
                        </div>
                    </div>

                    <?php if($HaveRs) { ?>
                        <div class="form-group col-sm-1">
                            <label class="control-label"> مرحلة المشروع </label>
                            <div class="">
                                <input type="text"
                                       value="<?= project_case($project_case,$project_tec_code) ?>"
                                       class="form-control" readonly>
                            </div>
                        </div>
                    <?php } ?>

                    <div class="form-group  col-sm-12">
                        <label class="control-label">البيان
                        </label>
                        <div>
                            <textarea name="hints" rows="5" id="txt_hints"
                                      class="form-control"><?= $HaveRs ? $rs['HINTS'] : "" ?></textarea>
                            <span class="field-validation-valid" data-valmsg-for="hints"
                                  data-valmsg-replace="true"></span>

                        </div>
                    </div>
                    <hr>
                    <div class="modal-footer">

                        <?php if (((!$HaveRs || (isset($can_edit) && $can_edit)) && ($action == 'index' || $action == 'Maintenance')) || ($action == 'update_items' && HaveAccess(base_url('projects/projects/update_items')))): ?>
                            <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>

                        <?php endif; ?>
                        <div class="btn-group">
                            <button class="btn btn-warning dropdown-toggle"
                                    onclick="$('#projects_detailsTbl').tableExport({type:'excel',escape:'false'});"
                                    data-toggle="dropdown"><i class="fa fa-bars"></i> تصدير
                            </button>
                        </div>
                        <?php if ($HaveRs && $rs['PROJECT_CASE'] == -1 && $action == 'index' && (isset($can_edit) && $can_edit)): ?>
                            <button type="button" onclick="javascript:return_adopt(1);" class="btn btn-success"> إعتمد
                            </button>
                        <?php endif; ?>
                        <?php if ($CanAdopt): ?>
                            <button type="button" onclick="javascript:return_adopt(1);" class="btn btn-success"> إعتمد
                            </button>
                            <button type="button" onclick="javascript:return_adopt(0);" class="btn btn-danger">إرجاع
                            </button>
                        <?php endif; ?>
                        <?php if ($HaveRs && $rs['PROJECT_CASE'] >= -1 && $action != 'archive_last'): ?>

                            <button type="button"
                                    onclick="javascript:print_project_ar(<?= $rs['PROJECT_SERIAL'] ?>,<?= $action == 'update_items' ? '1' : '2' ?>)"
                                    class="btn btn-default"> طباعة
                            </button>

                            <button type="button"
                                    onclick="javascript:print_project_en(<?= $rs['PROJECT_SERIAL'] ?>,<?= $action == 'update_items' ? '1' : '2' ?>)"
                                    class="btn btn-default"> Print
                            </button>

                            <button type="button"
                                    onclick="javascript:_showReport('<?= $report_url ?>&report=project_return_items_pdf&p_project_ser=<?= $rs['PROJECT_SERIAL'] ?>');"
                                    class="btn btn-default"> المواد المرجعة
                            </button>

                            <button type="button"
                                    onclick="javascript:_showReport('<?= $report_url ?>&report=project_return_items_xls&p_project_ser=<?= $rs['PROJECT_SERIAL'] ?>');"
                                    class="btn btn-default"> المواد المرجعة xls
                            </button>

                            <button type="button"
                                    onclick="javascript:_showReport('<?= $print_url ?>?report=PROJECT/PROJECTS_AMOUNT_REP&params[]=<?= $rs['PROJECT_SERIAL'] ?>');"
                                    class="btn btn-default"> الحركات المخزنية
                            </button>

                        <?php endif; ?>
                        <?php if (HaveAccess($req_url) and isset($rs['PROJECT_SERIAL']) and $rs['PROJECT_CASE'] != 15 && $action != 'archive_last') : ?>
                            <button type="button" onclick='javascript:store_request(2);' class="btn btn-success">تحويل
                                لطلب صرف
                            </button>
                        <?php endif; ?>
                        <?php if (HaveAccess($req_url) and isset($rs['PROJECT_SERIAL']) and $rs['PROJECT_CASE'] != 15 && $action != 'archive_last') : ?>
                            <button type="button" onclick='javascript:store_request(3);' class="btn btn-success">تحويل
                                لطلب صرف مواد المورد
                            </button>
                        <?php endif; ?>

                        <?php if ($can_close) : ?>
                            <button type="button" onclick='javascript:close_project();' class="btn btn-danger"> اغلاق
                                المشروع
                            </button>
                        <?php endif; ?>

                        <?php if ($can_adopt_gis) : ?>
                            <button type="button" onclick='javascript:close_project();' class="btn btn-danger">
                                اعتماد GIS
                            </button>
                        <?php endif; ?>

                        <?php if (((!$HaveRs || (isset($can_edit) && $can_edit)) && $action == 'index') && HaveAccess($price_url)): ?>
                            <button type="button" onclick='javascript:update_price();' class="btn btn-danger"> تحديث
                                الأسعار
                            </button>
                        <?php endif; ?>
                        <?php if ($action == 'archive_last'): ?>
                            <button type="button" onclick='javascript:transfer_project();' class="btn btn-danger"> تحويل
                                المشروع
                            </button>
                        <?php endif; ?>
                        <?php if ($HaveRs && $rs['PROJECT_CASE'] == 11 && HaveAccess($return_project_preparid_url)) { ?>
                            <button type="button" onclick='javascript:return_project_preparid(<?= $HaveRs ? $rs['PROJECT_SERIAL'] : "" ?>);'  id="btn_return_project_preparid" class="btn btn-danger">
                                ارجاع الى مرحلة الاعداد
                            </button>
                        <?php } ?>
                    </div>
                    <hr>
                    <div style="clear: both;">
                        <?php echo modules::run('settings/notes/public_get_page', $HaveRs ? $rs['PROJECT_SERIAL'] : 0, 'projects'); ?>
                        <?php echo $HaveRs ? modules::run('attachments/attachment/index', $rs['PROJECT_SERIAL'], 'projects') : ''; ?>
                    </div>

                    <div style="clear: both;">
                        <?php echo modules::run('projects/projects/public_get_details', $HaveRs ? $rs['PROJECT_SERIAL'] : 0, (isset($can_edit) ? $can_edit : false), $action); ?>
                    </div>
                    <hr>
                    <div class="modal-footer">

                        <?php if (((!$HaveRs || (isset($can_edit) && $can_edit)) && ($action == 'index' || $action == 'Maintenance')) || ($action == 'update_items' && HaveAccess(base_url('projects/projects/update_items')))): ?>
                            <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>

                        <?php endif; ?>

                        <?php if ($CanAdopt): ?>
                            <button type="button" onclick="javascript:return_adopt(1);" class="btn btn-success"> إعتمد
                            </button>
                            <button type="button" onclick="javascript:return_adopt(0);" class="btn btn-danger">إرجاع
                            </button>
                        <?php endif; ?>

                        <?php if ($HaveRs && $rs['PROJECT_CASE'] >= 0): ?>
                            <button type="button"
                                    onclick="javascript:print_project_ar(<?= $rs['PROJECT_SERIAL'] ?>,<?= $action == 'update_items' ? '1' : '2' ?>)"
                                    class="btn btn-default"> طباعة
                            </button>
                            <button type="button"
                                    onclick="javascript:print_project_en(<?= $rs['PROJECT_SERIAL'] ?>,<?= $action == 'update_items' ? '1' : '2' ?>)"
                                    class="btn btn-default"> Print
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>
<div class="modal fade" id="transferModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                            class="sr-only">Close</span></button>
                <h4 class="modal-title"> بيانات التحويل</h4>
            </div>
            <div id="msg_container_alt"></div>
            <div class="modal-body inline_form">

                <div class="form-group col-sm-12">
                    <div class="form-group col-sm-8" style="color: #ff0000;">
                        تنبيه : الرجاء تحديد نوع التحويل بدقة!!!
                    </div>
                    <div class="col-sm-5">
                        <select id="transfer_type" class="form-control">
                            <option value="0">غير معتمد قانونيا</option>
                            <option value="1">معتمد قانونيا</option>

                        </select>
                    </div>
                </div>

                <div class="form-group  col-sm-1">
                    <label class="control-label">المحول المقترح
                    </label>
                    <div>
                        <input type="hidden" id="h_txt_adopter_id" class="form-control">

                        <input readonly id="txt_adopter_id" class="form-control">


                    </div>
                </div>
            </div>
            <br>
            <div class="modal-footer">
                <button type="button" onclick="javascript:apply_transfer();" class="btn btn-primary">تحويل</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>

            </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="modal fade" id="inuseModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                            class="sr-only">Close</span></button>
                <h4 class="modal-title"> سبب الإستبدال </h4>
            </div>
            <div id="msg_container_alt"></div>
            <div class="modal-body inline_form">

                <div class="form-group col-sm-12">
                    <div class="form-group col-sm-8" style="color: #ff0000;">
                        السبب
                    </div>
                    <div class="col-sm-12">
                        <textarea id="in_use_note" class="form-control"></textarea>
                    </div>
                </div>


            </div>
            <br>
            <div class="modal-footer">
                <button type="button" onclick="javascript:apply_update_in_use();" class="btn btn-primary">إستبدال
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>

            </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php echo modules::run('settings/notes/index'); ?>

<?php
$customer_url = base_url('payment/customers/public_index');
$adapters_url = base_url('projects/adapter/public_index');
$class_url = base_url('stores/classes/public_get_id');
$return_items = $HaveRs ?  base_url('projects/projects/returnitems/'.$rs['PROJECT_SERIAL']) : '';
$shared_script = <<<SCRIPT
 var count = 1;
    
    $(function(){ count = $('input[name="class_id[]"]').length;});
    $('#dp_project_type').change(function(){project_type_change();});
    $('#under_tax').change(function(){currency_value();});
    $('#tax').keyup(function(){currency_value();});
     
    $('#txt_return_cost').click(function(){
    if('{$return_items}' != ''){
     _showNewModal('{$return_items}');
     }
    }); 
    
    
    
     function change_calculation_cable(ser,is_calculate){
         get_data('{$change_calculation_cable_url}', {ser:ser, is_calculate:is_calculate} ,function(data){
            if(data==1){
                success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                if(is_calculate == '1') {
                   $('#td_ser_'+ser).html("<i class='glyphicon glyphicon-plus' title='يحسب'  style='color: #0a8800;font-size: large' onclick='change_calculation_cable_html(this)'></i><input type='hidden'  name='h_is_calculate[]' value='1'>");
                  
                } else {
                   $('#td_ser_'+ser).html("<i class='glyphicon glyphicon-minus' title='لا يحسب' style='color: #F40E21FF;font-size: large' onclick='change_calculation_cable_html(this)'></i><input type='hidden'  name='h_is_calculate[]' value='2'>");
                
                }
                 CalcSum();
            }else{
                danger_msg('تحذير..',data);
            }
        },'html');
     }
     
     function change_calculation_cable_html(obj){
       var tr = obj.closest('tr');
       var ser = $('input[name="SER[]"]',tr).val();
       var is_calculate = $('input[name="h_is_calculate[]"]',tr).val();
       change_calculation_cable(ser,is_calculate)
     }
    
    
    
    
    $('#dp_project_tec_type').change(function(){
       var type =  $(this).find(':selected').attr('data-tec');

           if(type == 'T' || type == 'SP' || type =='RSP' || type == 'EISP' || type == 'PLP' )
           {
                $('#dp_project_type').val(2);
           }else {

                $('#dp_project_type').val(3);
           }

           currency_value();
    });

    $('#dp_company_value_type').change(function(){
        CalcSum();
    });

    $('#txt_company_value').keyup(function(){
        CalcSum();
    });

    CalcSum();

    function reBind(){

        delete_action();

        $('#dp_curr_id').change(function(){currency_value();});
        $('input[name="class_id_name[]"]').click(function(){
            _showReport('$store_item_url/'+$(this).attr('id')+'/');
        });


        $('#txt_power_adapter_namee,#txt_adopter_id').click(function(){
            _showReport('$adapters_url/'+$(this).attr('id')+'/');
        });

        $('input[name="sal_price[]"],input[name="amount[]"],#txt_extra_cost,#txt_civil_works,#txt_return_cost,#txt_design_cost,#txt_monitoring_cost,#txt_supervision_cost').keyup(function(){
             CalcSum();
        });

        $('select[name="class_type[]"]').change(function(){
            currency_value();
        });

       /* $('input[name="class_id[]"]').keyup(function(){

            var obj = $(this);

            if($(this).val().length > 6){
               get_data('{$class_url}',{id:$(this).val()},function(data){


                    if(data.length > 0) {
                          $('input[name="class_id_name[]"]',$(obj).closest('tr')).val(data[0].CLASS_NAME_AR);
                          $('input[name="class_unit_name[]"]',$(obj).closest('tr')).val(data[0].UNIT_NAME);
                          $('input[name="class_type[]"]',$(obj).closest('tr')).val(data[0].CALSS_TYPE);
                          $('input[name="amount[]"]',$(obj).closest('tr')).val('');
                          $('input[name="price[]"]',$(obj).closest('tr')).val(data[0].BUY_PRICE);
                          $('input[name="befor_up_sal_price[]"],input[name="sal_price[]"]',$(obj).closest('tr')).val(data[0].SELL_PRICE);
                          $('input[name="used_price[]"]',$(obj).closest('tr')).val(data[0].USED_SELL_PRICE);
                          $('input[name="class_id_name[]"]',$(obj).closest('tr')).val(data[0].CLASS_NAME_AR);
                    } else  $('input[name="class_id_name[]"]',$(obj).closest('tr')).val('');

               });
            } else  $('input[name="class_id_name[]"]',$(obj).closest('tr')).val('');
        });*/

    }

    function update_after_delete(){
            CalcSum();
    }

   function project_type_change(){

        var type = $('#dp_project_type').val();
        
        if(type != 3){
             $('#under_tax').val(1);
             $('input[name="under_tax"]').val(1);
        }else {
             $('#under_tax').val(2);
             $('input[name="under_tax"]').val(2);
        }
        
        $('input[name="price[]"][data-sale]').each(function(i){
            
            var class_type = $(this).closest('tr').find('select[name="class_type[]"]').val(); //حالة الصنف
            var used_price = $(this).closest('tr').find('input[name="used_price[]"]').val(); //سعر البيع المستخدم
            var used_buy_price = $(this).closest('tr').find('input[name="price[]"]').attr('data-used-buy'); //سعر الشراء المستخدم
      
     
            var tax = parseFloat($('#tax').val());
            
            var under_tax = parseInt($('#under_tax').val());
            
            if(type != 3){
              //$(this).closest('tr').find('input[name="sal_price[]"]').val($(this).attr('data-sale'));

              var l_buy_price =$(this).attr('data-buy'); //سعرالشراء الجديد
              l_buy_price = isNaNVal(class_type == 1? l_buy_price : used_buy_price);
              $(this).closest('tr').find('input[name="price[]"]').val(isNaNVal(l_buy_price,2));
   
   
              /*var n_buy_price = $(this).closest('tr').find('input[name="befor_up_buy_price[]"]').val();*/ 
              /*n_buy_price = isNaNVal(class_type == 1? n_buy_price : used_buy_price);
              $(this).closest('tr').find('input[name="price[]"]').val(isNaNVal(n_buy_price,2));*/



              var price =$(this).attr('data-sale');
              price = isNaNVal(class_type == 1? price : used_price);
              
              var price_tax = isNaNVal(under_tax == 1 ?  price * tax / 100 : 0);
              
              console.log(price,price_tax);

              $(this).closest('tr').find('input[name="sal_price[]"]').val(isNaNVal(price + price_tax ,2));
              $(this).closest('tr').find('input[name="sal_price[]"]').attr('value',price);

              $('#txt_design_cost').val({$rs_design_cost});
              $('#txt_monitoring_cost').val({$monitoring_cost});
              $('#txt_supervision_cost').val({$supervision_cost});
            }else{
              ///مشاريع غير مدفوعة التكاليف
              var buy_price =$(this).attr('data-buy'); //سعرالشراء الجديد
              buy_price = isNaNVal(class_type == 1? buy_price : used_buy_price);
              $(this).closest('tr').find('input[name="price[]"]').val(isNaNVal(buy_price,2));
              
              var price =$(this).val();
			  used_price=$(this).closest('tr').find('input[name="used_price[]"]').val();
              price =isNaNVal( class_type == 1? price : used_buy_price);
              
              var price_tax = isNaNVal(under_tax == 1 ?  price * tax / 100 : 0);
     
              $(this).closest('tr').find('input[name="sal_price[]"]').val(isNaNVal(price + price_tax ,2));
              $(this).closest('tr').find('input[name="sal_price[]"]').attr('value',price); 

              $('#txt_design_cost').val(0);
              $('#txt_monitoring_cost').val(0);
              $('#txt_supervision_cost').val(0);
            }
        });
    }


    function CalcSum(){
         var sum = 0;
         var sumWithOutCable = 0;
         $('input[name="sal_price[]"]').each(function(i){

            if($(this).attr('data-case') != '2'){

                var count = isNaNVal($(this).closest('tr').find('input[name="amount[]"]').val());
                    sum +=(isNaNVal($(this).val() , 4) * count);

            }
            
             var is_calculate = $(this).closest('tr').find('input[name="h_is_calculate[]"]').val();
          
            if( is_calculate == '2' && $(this).attr('data-case') != '2' ){

                var count = isNaNVal($(this).closest('tr').find('input[name="amount[]"]').val());
                    sumWithOutCable +=(isNaNVal($(this).val() , 4) * count);

            }
         });

         $('#inv_total').text(isNaNVal(sum,2));

        var costWithOutCable  = sum - sumWithOutCable;


         var design_cost = sum * isNaNVal($('#txt_design_cost').val()) / 100;
         var monitoring_cost = sum * isNaNVal($('#txt_monitoring_cost').val()) / 100;
         var supervision_cost = costWithOutCable * isNaNVal($('#txt_supervision_cost').val()) / 100;
         var extra_cost = isNaNVal($('#txt_extra_cost').val());
         var return_cost = isNaNVal($('#txt_return_cost').val());
         var civil_works = isNaNVal($('#txt_civil_works').val());
         var company_value  =  isNaNVal($('#txt_company_value').val());

         var total  = sum + design_cost + monitoring_cost + supervision_cost +extra_cost + civil_works - return_cost;
         company_value = $('#dp_company_value_type').val() == 1 ? total * company_value / 100 : company_value;

         total = total - company_value;

         $('#design_cost').text(isNaNVal(design_cost,2));
         $('#monitoring_cost').text(isNaNVal(monitoring_cost,2));
         $('#supervision_cost').text(isNaNVal(supervision_cost,2));
         $('#extra_cost').text(isNaNVal(extra_cost,2));
         $('#return_cost').text(isNaNVal(return_cost,2));
         $('#civil_works').text(isNaNVal(civil_works,2));
         $('#inv_nettotal').text(isNaNVal(total,2));
         $('#company_value').text(isNaNVal(company_value,2));

    }

    $('#txt_customer_id').click(function(){
        _showReport('$customer_url/'+$(this).attr('id')+'/');

    });

    $('#txt_request_id').click(function(){ _showReport('$select_request_url/'+$(this).attr('id')+'/8');});

    function currency_value(){
        $('#txt_curr_value').val($('#dp_curr_id').find(':selected').attr('data-val'));
        project_type_change();
        curr_change();
        CalcSum();
    }

    function curr_change(){
       var curr_value =isNaNVal( $('#txt_curr_value').val());

       /*$('input[name="sal_price[]"]').each(function(i){
            var price =$(this).val();//   isNaNVal($(this).closest('tr').find('input[name="price[]"]').attr('data-sale'));

           // $(this).val(isNaNVal((price / curr_value),4));
        });*/
        $('input[name="sal_price[]"]').each(function(i){
            var price =$(this).val(); // isNaNVal($(this).closest('tr').find('input[name="price[]"]').attr('data-sale'));

             $(this).val(isNaNVal((price / curr_value),4));
             $(this).attr('value',isNaNVal((price / curr_value),4));
        });

    }


    reBind();
    currency_value();

    $('button[data-action="submit"]').click(function(e){
        e.preventDefault();

        if($('input:text:visible',$('#projects_detailsTbl')).not('input[name="notes[]"]' ).filter(function() { return this.value == ""; }).length > 0){


            bootbox.dialog({
                  message: "يجب إدخال جميع البياتات في جدول الكميات",
                  title: "تحذير",
                  className:'danger',
                  buttons: {

                        main: {
                          label: "إغلاق",
                          className: "btn-default",
                          callback: function() {

                    }
             }
         }
    });

            return;
        }

        if(confirm('هل تريد حفظ السند ؟!')){
            var form = $(this).closest('form');

            if($('button[data-action="submit"]').length > 0)
                $(form).attr('action',$(form).attr('action').replace('Maintenance','edit'));


            ajax_insert_update(form,function(data){
                if(data >= 1){
                    success_msg('رسالة','تم حفظ البيانات بنجاح ..');

                    reload_Page();

                }else{
                    danger_msg('تحذير..',data);
                }
            },'html');
        }
    });

    function addRow(){
        if($('input:text:visible',$('#projects_detailsTbl')).not('input[name="notes[]"]' ).filter(function() { return this.value == ""; }).length <= 0){

            var tr = $('#projects_detailsTbl > tbody tr:first');
            var html  = tr.html();

            html =replaceAll('_0','_'+count,html);
            $('#projects_detailsTbl tbody').append('<tr>'+html+'</tr>');



            tr = $('#projects_detailsTbl > tbody tr:last');
            $('input',$(tr)).val('');
            $('.v_balance,td[data-empty="true"]',$(tr)).text('');
            $('input[name="SER[]"]',$(tr)).val(0);
            $('input[name="customer_amount[]"]',$(tr)).val(0);
            $('input[data-sale]',$(tr)).attr('data-sale','');
            $('td:last',$(tr)).html('<a data-action="delete" href="javascript:;"><i class="icon icon-trash delete-action"></i> </a>');

            $('input,select',$(tr)).prop('disabled',false);
 
            reBind();
            count++;
        }
    }

  function AddRowWithData(id,name_ar,price,sale_price,unit,unit_name,used_sell_price,used_buy_price){
    
        if($('input[name="class_id[]"][value="'+id+'"]').length <= 0){
            addRow();
            var curr_val =isNaNVal( $('#txt_curr_value').val());
            $('#class_id_'+(count -1)).val(id+': '+name_ar);
            $('#h_class_id_'+(count -1)).val(id); $('#h_class_id_'+(count -1)).attr('value',id);
            $('#h_unit_class_id_'+(count -1)).val(unit);
            $('#unit_class_id_'+(count -1)).val(unit_name);
            $('#price_class_id_'+(count -1)).val(price);
            $('#amount_class_id_'+(count -1)).val(1);
            $('#price_class_id_'+(count -1)).attr('data-buy',price);
            $('#price_class_id_'+(count -1)).attr('data-sale',sale_price);
            $('#price_class_id_'+(count -1)).attr('data-used-buy',used_buy_price);
            $('#bu_price_class_id_'+(count -1)).val(sale_price);
            $('#bu_buy_price_class_id_'+(count -1)).val(price);
            $('#used_price_class_id_'+(count -1)).val(used_sell_price);
            $('#used_buy_price_class_id_'+(count -1)).val(used_buy_price);
            $('#sal_price_class_id_'+(count -1)).val(isNaNVal(sale_price / curr_val));
        }
    }


SCRIPT;

$notes_url = notes_url();
$public_return_url = base_url('projects/projects/public_return');

$create_script = <<<SCRIPT
<script>
        {$shared_script}
</script>
SCRIPT;


if (!$HaveRs)
    sec_scripts($create_script);
else {

    $disabled_script = (($CanAdopt && !(isset($can_edit) && $can_edit)) || !((!$HaveRs || (isset($can_edit) && $can_edit)) && ($action == 'index' || $action == 'Maintenance'))) ? ($action == 'com' ? "$('#projects_form input , #projects_form select').not('#txt_company_value,#dp_company_value_type').prop('disabled',true);$('#txt_company_value,#dp_company_value_type').closest('.form-group').css({'background-color': '#FF6F4D'});" : " $('#projects_form input , #projects_form select').prop('disabled',true);") : "";
    $transfer = ($action == 'archive_last') ? "function transfer_project(){ $('#transferModal').modal(); } function apply_transfer(){    get_data('{$transfer_url}',{id:{$rs['PROJECT_SERIAL']},adopt:$('#transfer_type').val(),adopter_id:$('#h_txt_adopter_id').val()},function(data){if(data =='1') success_msg('رسالة','تم تحويل المشروع بنجاح ..'); window.location='$back_archive_url'; },'html'); }" : "";
    $edit_script = <<<SCRIPT
<script>
    {$shared_script}
    {$disabled_script}
    {$transfer}
    $('#txt_project_serial').prop('disabled',false);
     var action_type;
     var in_use_id;
     
    function delete_details(a,id){
             if(confirm('هل تريد حذف البند ؟!')){

                  get_data('{$delete_url}',{id:id},function(data){
                             if(data == '1'){
                                $(a).closest('tr').remove();
                                CalcSum();
                               }else{
                                     danger_msg( 'تحذير','فشل في العملية');
                               }
                        });
                 }
         }

    function return_adopt(type){

            if(type == 1 && ! confirm('هل تريد إعتماد السند ؟!')){
                return;
            }
            action_type = type;

            $('#notesModal').modal();

       }

     function apply_action(){
                if(action_type == 1){
                     get_data($('#projects_form').attr('action').replace('edit','index'),{id:{$rs['PROJECT_SERIAL']},company_value_type:$('#dp_company_value_type').val(),company_value:$('#txt_company_value').val(),title:$('#txt_project_name').val(),project_tec_type :$('#dp_project_tec_type').val() },function(data){
                         if(data >= 1 ){
                            success_msg('رسالة','تم اعتماد السند بنجاح ..');
                            reload_Page();
                         }else {
                             success_msg('رسالة','تم اعتماد السند بنجاح ..');
                             reload_Page();
                            // return -1;
                         }   
                    },'html');
                }
                else{
                    if($('#txt_g_notes').val() =='' ){
                        alert('تحذير : لم تذكر سبب الإرجاع ؟!!');
                        return;
                    }
                    get_data('{$public_return_url}',{id:{$rs['PROJECT_SERIAL']}},function(data){
                        if(data >= '1')
                            success_msg('رسالة','تم  إرجاع السند بنجاح ..');
                            reload_Page();
                    },'html');
                }
                get_data('{$notes_url}',{source_id:{$rs['PROJECT_SERIAL']},source:'projects',notes:$('#txt_g_notes').val()},function(data){
                    $('#txt_g_notes').val('');
                },'html');
                $('#notesModal').modal('hide');
     }

    function store_request(no){
        get_to_link('{$req_url}/{$rs['PROJECT_SERIAL']}/'+no);
    }

    // mkilani
    function close_project(){
        if( confirm('هل تريد اغلاق المشروع ؟!') ){
            get_data($('#projects_form').attr('action').replace('edit','index'),{id:{$rs['PROJECT_SERIAL']}},function(data){
                if(data =='1')
                    success_msg('رسالة','تم اغلاق المشروع بنجاح ..');
            },'html');
        }
    }

   function update_price(){
    
            $('input[name="class_id[]"]').each(function(i){
                    var obj =$(this);
                    get_data('{$price_url}',{id:$(this).val()},function(data){
                     if(data.length > 0){
                     //console.log('',data);
                            $(obj).closest('tr').find('input[name="sal_price[]"]').val(data[0].SALE_PRICE);
                            $(obj).closest('tr').find('input[name="price[]"]').attr('data-sale',data[0].SALE_PRICE);
                            $(obj).closest('tr').find('input[name="price[]"]').attr('data-buy',data[0].CLASS_PURCHASING);
                            $(obj).closest('tr').find('input[name="price[]"]').val(data[0].CLASS_PURCHASING);
                            $(obj).closest('tr').find('input[name="befor_up_sal_price[]"]').val(data[0].SALE_PRICE);
                            $(obj).closest('tr').find('input[name="befor_up_buy_price[]"]').val(data[0].CLASS_PURCHASING);
    
                         project_type_change();
                         curr_change();
                     }
                    });
            });
    }

    var in_use_obj;
    function update_in_use(a,id){
             in_use_id = id;
            $('#inuseModal').modal();

            in_use_obj = $(a);//.closest('tr');


    }

    function apply_update_in_use(id){

         if($('#in_use_note').val() =='' ){
          alert('تحذير : لم تذكر سبب الإستبدال ؟!!');
          return;
         }

            get_data('{$in_use_url}',{id:in_use_id,notes:$('#in_use_note').val()},function(data){
                if(data){
                  success_msg('رسالة','تم إستبدال الصنف بنجاح ..');
                  $('#inuseModal').modal('hide');
                  $(in_use_obj).closest('tr').attr('class','case_0');
                  $(in_use_obj).remove();

                }
           },'html');
    }

    $('tr[data-item-case="3"] input,tr[data-item-case="3"] select').prop('disabled',false);

    function changeLang(){

        $('input[name="class_id_name[]"]',$('#projects_detailsTbl')).each(function(i){

                if($(this).val() == $(this).attr('data-arabic'))
                    $(this).val($(this).attr('data-english'));
                else
                    $(this).val($(this).attr('data-arabic'));

        });
    }
    
    
     function print_project_ar(ser,is_update){
       _showReport('{$report_url}&report_type=pdf&report=projects_file_ar&p_project_serial='+ser+'&p_is_update='+is_update+'');
    }
   
     function print_project_en(ser,is_update){
       _showReport('{$report_url}&report_type=pdf&report=projects_file_en&p_project_serial='+ser+'&p_is_update='+is_update+'');
    }
   
   
    function return_project_preparid(project_serial){
       if(confirm('هل تريد ارجاع المشروع الى مرحلة الاعداد ؟!')){
          get_data('{$return_project_preparid_url}', {project_serial:project_serial}, function(data){
                if(data==1){
                    success_msg('رسالة','تم  ارجاع المشروع الى مرحلة الاعداد بنجاح ..');
                    $('#btn_return_project_preparid').attr('disabled','disabled');
                }else{
                    danger_msg('تحذير..',data);
                }
            }, 'html');  
       }
      setTimeout(function() {
            $('#btn_return_project_preparid').removeAttr('disabled');
      }, 3000);
    }

</script>
SCRIPT;
sec_scripts($edit_script);
}
?>

