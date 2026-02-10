<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 06/11/14
 * Time: 09:09 ص
 */

$get_invoices_url = base_url('payment/financial_payment/public_get_invoices');

$get_account_url = base_url('financial/accounts_permission/public_get_user_accounts');
$back_url = base_url('payment/financial_payment/' . $action);
$adopt_url = base_url('payment/financial_payment/' . $action);
$select_accounts_url = base_url('financial/accounts/public_select_accounts');
$get_id_url = base_url('financial/accounts/public_get_id');
$get_balance_url = base_url('financial/financial_chains/public_get_balance');
$print_url = base_url('/reports');

$report_url = base_url("JsperReport/showreport?sys=financial/stores");

$delete_details_url = base_url('payment/financial_payment/delete_details');
$notes_url = base_url('settings/notes/public_create');
$public_return_url = base_url('payment/financial_payment/public_return');
$public_get_banks_url = base_url('settings/banks_info/public_get_banks');

$review_doc = base_url('payment/financial_payment/review_doc');
$review_rest = base_url('payment/financial_payment/review_rest');
$isCreate = isset($payments_data) && count($payments_data) > 0 ? false : true;
$rs = $isCreate ? array() : $payments_data[0];


$isAdopt = (count($rs) > 0 && $rs['FINANCIAL_PAYMENT_CASE'] > 0 && $rs['FINANCIAL_PAYMENT_CASE'] < $case && $rs['FINANCIAL_PAYMENT_CASE'] >= $case - 1) ? true : false;
$isAdopt = $isAdopt && $action == 'review' ? $rs['B_REVIEW_HINTS_USER'] != null && $rs['AUDIT_DEP_USER'] != null : $isAdopt;
$isAdopt = $isAdopt && $action == 'breview' ? $rs['B_REVIEW_HINTS_USER'] == null && $rs['AUDIT_DEP_USER'] != null : $isAdopt;
$isAdopt = $isAdopt && $action == 'audit_dep' ? $rs['B_REVIEW_HINTS_USER'] == null && $rs['AUDIT_DEP_USER'] == null : $isAdopt;
$ReturnToAudit = $action == 'review' ? $rs['B_REVIEW_HINTS_USER'] != null && $rs['AUDIT_DEP_USER'] != null : false;


$rs = $isCreate ? array() : $payments_data[0];
$print_per_url = base_url('payment/financial_payment/print_per');

$project_accounts_url = base_url('projects/projects/public_select_project_accounts');

$report_jasper_url = base_url("JsperReport/showreport?sys=financial/archives");
$report_sn= report_sn();
?>
<?= AntiForgeryToken() ?>
<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
            <?php if (HaveAccess($back_url)): ?>
                <li><a href="<?= $back_url ?>"><i class="icon icon-reply"></i> </a> </li><?php endif; ?>
            <li><a onclick="<?= $help ?>" href="javascript:" class="help"><i class="icon icon-question-circle"></i></a>
            </li>
        </ul>

    </div>
</div>
<div class="form-body">
<?php if (count($rs) > 0 && $rs['FINANCIAL_PAYMENT_CASE'] == 0): ?>
    <span class="canceled">
السند ملغي
                </span>
<?php endif; ?>
<div id="msg_container"></div>
<div id="container">
<form class="form-vertical" id="payment_from" method="post"
      action="<?= base_url('payment/financial_payment/' . ($action == 'index' ? 'create' : $action)) ?>" role="form"
      novalidate="novalidate">
<div class="modal-body inline_form">

<div class="form-group col-sm-1">
    <label class="control-label"> رقم السند </label>

    <div class="">
        <input type="text" name="entry_ser" value="<?= count($rs) > 0 ? $rs['ENTRY_SER'] : '' ?> " readonly
               class="form-control ">
        <input type="hidden" name="financial_payment_id"
               value="<?= count($rs) > 0 ? $rs['FINANCIAL_PAYMENT_ID'] : '' ?> " readonly class="form-control ">

    </div>
</div>

<div class="form-group col-sm-2">
    <label class="control-label"> العملة </label>

    <div class="">
        <input type="hidden" name="cover_id" value="<?= (isset($cover_id)) ? $cover_id : 0 ?>">
        <select name="curr_id" id="dp_curr_id" data-curr="false" class="form-control">
            <?php foreach ($currency as $row) : ?>
                <option data-val="<?= $row['VAL'] ?>" value="<?= $row['CURR_ID'] ?>"><?= $row['CURR_NAME'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>

<div class="form-group col-sm-1">
    <label class="control-label"> سعر العملة</label>

    <div class="">
        <input type="text" name="curr_value" data-val-regex="المدخل غير صحيح!"
               data-val-regex-pattern="<?= float_format_exp() ?>" data-val="true" value="" data-val-required="حقل مطلوب"
               id="txt_curr_value" class="form-control ">
        <span class="field-validation-valid" data-valmsg-for="curr_value" data-valmsg-replace="true"></span>
    </div>
</div>


<div class="form-group col-sm-1">
    <label class="control-label"> نوع الصرف </label>

    <div class="">
        <select name="payment_type" id="dp_payment_type" class="form-control">
            <?php foreach ($payment_type as $row) : ?>
                <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>

<div class="form-group col-sm-2">
    <label class="control-label">تصنيف المستفيد</label>

    <div class="">
        <select name="financial_payment_type" id="dp_financial_payment_type" class="form-control">
            <?php foreach ($financial_payment_type as $row) : ?>
                <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>

<div class="form-group col-sm-5">
    <label class="control-label"> البيان </label>

    <div class="">
        <input type="text" name="hints" data-val="true" data-val-required="حقل مطلوب" id="txt_hints"
               class="form-control ">
        <span class="field-validation-valid" data-valmsg-for="hints" data-valmsg-replace="true"></span>
    </div>
</div>

<div class="form-group col-sm-3">
    <label class="control-label"> حساب الصرف </label>

    <div class="">
        <input type="hidden" name="financial_payment_account_id" data-val="true" data-val-required="حقل مطلوب"
               id="txt_financial_payment_account_id" class="form-control">
        <input type="text" readonly data-val="true"
               data-val-required="حقل مطلوب"
               id="txt_financial_payment_account_id_name"

               class="form-control">
        <span class="field-validation-valid" data-valmsg-for="financial_payment_account_id"
              data-valmsg-replace="true"></span>
    </div>
</div>


<div class="form-group col-sm-3">
    <label class="control-label"> المستفيد </label>

    <div class="">
        <input name="customer_name"
               data-val="true" readonly
               data-val-required="حقل مطلوب"
               class="form-control" readonly

               id="txt_customer_name" value="">
        <input type="hidden" name="customer_id" value=""
               data-cash-flow="<?= isset($CASH_FLOW)? $CASH_FLOW : 0 ?>"
               id="h_txt_customer_name">

    </div>
</div>

    <div class="form-group col-sm-2" style="display: none;">
        <label class="control-label"> المقبوضات و المصروفات</label>
        <div class="">


            <select
                    name="bk_fin_id"
                    id="dp_bk_fin_id"
                    data-val-required="required"
                    data-val="true"
                    class="form-control">
                <option></option>
                <?php foreach($BkFin as $_row) :?>
                    <option   value="<?= $_row['ID'] ?>"><?= str_repeat('-' , strlen($_row['ID'])) . ' '. $_row['TITLE'] ?></option>
                <?php endforeach; ?>
            </select>

        </div>
    </div>

<div class="form-group col-sm-1">
    <label class="control-label"> نوع المستفيد </label>

    <div class="">
        <input type="hidden" name="customer_type" id="txt_customer_type">
        <select disabled id="dp_customer_type" class="form-control">
            <?php foreach ($customer_type as $row) : ?>
                <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>





    <div class="form-group col-sm-1" id="db_customer_account_type_div">
    <label class="control-label"> نوع الحساب </label>
    <div class="">
        <input type="hidden" name="customer_account_type" id="txt_customer_account_type">
        <select class="form-control" id="db_customer_account_type" disabled  >
            <?php foreach ($ACCOUNT_TYPES as $row) : ?>
                <option value="<?= $row['ACCCOUNT_TYPE'] ?>" ><?= $row['ACCCOUNT_NO_NAME'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>

<?php if (count($rs) > 0 && $rs['QEED_NO'] != '' && $rs['QEED_NO'] > 0): ?>

    <div class="form-group col-sm-2" style="padding-top: 42px;padding-right: 30px;">

        <div class="">
            <a id="source_url" href="<?= base_url("financial/financial_chains/get/{$rs['QEED_NO']}/index?type=4") ?>"
               class="btn-xs btn-success" target="_blank"> عرض القيد</a>
        </div>
    </div>

<?php endif; ?>

<div id="for_checks" class="checks_div">

    <div class="form-group col-sm-2">
        <label class="control-label" id="lb_check_bank_id">البنك المسحوب عليه</label>

        <div class="">
            <select name="check_bank_id" id="dp_check_bank_id" class="form-control">

                <!--  <?php /*foreach($banks as $row) :*/ ?>
                                        <option value="<? /*= $row['CON_NO'] */ ?>"><? /*= $row['CON_NAME'] */ ?></option>
                                    --><?php /*endforeach; */ ?>
            </select>
        </div>
    </div>

    <div class="form-group col-sm-1">
        <label class="control-label">رقم الحساب</label>

        <div class="">
            <input type="text" name="bank_acounts_number" readonly data-val="true" data-val-required="حقل مطلوب"
                   id="txt_bank_acounts_number" class="form-control ">
            <span class="field-validation-valid" data-valmsg-for="bank_acounts_number"
                  data-valmsg-replace="true"></span>

        </div>
    </div>

    <div class="form-group col-sm-2">
        <label class="control-label" id="lb_check_date">تاريخ استحقاق الشيك</label>

        <div class="">
            <input type="text" name="check_date" data-type="date" data-date-format="DD/MM/YYYY"
                   data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?= date_format_exp() ?>"
                   id="txt_check_date" class="form-control ">
            <span class="field-validation-valid" data-valmsg-for="check_date" data-valmsg-replace="true"></span>
        </div>
    </div>


    <div class="form-group col-sm-2">
        <label class="control-label">عملة الحساب المحول منه </label>

        <div>
            <select name="trnaser_curr_id" id="dp_trnaser_curr_id" class="form-control">
                <?php foreach ($currency as $row) : ?>
                    <option value="<?= $row['CURR_ID'] ?>"><?= $row['CURR_NAME'] ?></option>
                <?php endforeach; ?>
            </select>

        </div>
    </div>


    <hr/>
    <div class="form-group col-sm-2">
        <label class="control-label" id="lb_check_id">رقم الشيك</label>

        <div class="">
            <input type="text" name="check_id" id="txt_check_id" class="form-control ">
            <span class="field-validation-valid" data-valmsg-for="check_id" data-valmsg-replace="true"></span>
        </div>
    </div>

    <div class="form-group col-sm-3">
        <label class="control-label" id="lb_check_customer">اسم صاحب الشيك</label>

        <div class="">
            <input type="text" name="check_customer" data-val="true" data-val-required="حقل مطلوب"
                   id="txt_check_customer" class="form-control ">
            <span class="field-validation-valid" data-valmsg-for="check_customer" data-valmsg-replace="true"></span>
        </div>
    </div>
    <div class="form-group col-sm-2" id="div_convert_bank_transfer">
        <label class="control-label">البنك المحول إليه </label>

        <div class="">
            <input type="text" name="convert_bank_transfer" id="txt_convert_bank_transfer" class="form-control ">
        </div>
    </div>
    <div class="form-group col-sm-1">
        <label class="control-label">العملة</label>

        <div id="check_curr">


        </div>
    </div>
    <div class="form-group col-sm-3" id="div_iban">
        <label class="control-label"> IBAN </label>

        <div class="">
            <input type="text" name="iban" id="txt_iban" class="form-control ">
        </div>
    </div>
</div>
<br/>
<hr/>
<div id="invoices"></div>
<hr>
<div style="clear: both;">
    <?php echo modules::run('payment/financial_payment/public_get_details', (count($rs)) ? $rs['FINANCIAL_PAYMENT_ID'] : 0); ?>
</div>

<hr/>
<div style="clear: both;">
    <?php echo modules::run('settings/notes/public_get_page', (count($rs)) ? $rs['FINANCIAL_PAYMENT_ID'] : 0, 'financial_payment'); ?>
    <?php echo (count($rs)) ? modules::run('attachments/attachment/index', $rs['FINANCIAL_PAYMENT_ID'], 'financial_payment') : ''; ?>
</div>


</div>

<hr>
<div class="form-group col-sm-12">
    <label class="control-label">الملاحظات</label>

    <div class="">
        <input type="text" name="note" id="txt_note" class="form-control ">
        <span class="field-validation-valid" data-valmsg-for="note" data-valmsg-replace="true"></span>
    </div>
</div>
<div class="form-group col-sm-12">
    <label class="control-label">ملاحظة الرقابة</label> - (<?= (count($rs) > 0 ? $rs['REVIEW_HINTS_USER_NAME'] : "") ?>)
    <div class="">
        <input type="text" readonly value="<?= (count($rs) > 0 ? $rs['REVIEW_HINTS'] : "") ?>" class="form-control ">

    </div>
</div>
<hr/>

<div class="modal-footer">
    <?php if ((isset($can_edit) ? $can_edit : false)) : ?>
        <input type="hidden" name="financial_payment_id" id="txt_financial_payment_id">
        <input type="hidden" name="financial_payment_type" id="txt_financial_payment_type">
    <?php endif; ?>
    <?php if (($isCreate || (isset($can_edit) ? $can_edit : false))) : ?>
        <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
        <button type="button" onclick="apply_caver_change();" class="btn btn-danger">تحديث البيانات حسب
            النموذج
        </button>
    <?php endif; ?>

    <?php if ($isAdopt && $case != 5) : ?>
        <button type="button" onclick="return_mqasah(1);" class="btn btn-primary">إعتماد</button>
    <?php endif; ?>

    <?php if ($isAdopt && $case != 5) : ?>
        <button type="button" onclick="return_mqasah(0);" class="btn btn-danger">إرجاع</button>
    <?php endif; ?>

    <?php if (HaveAccess($review_rest) && $ReturnToAudit && $isAdopt && $rs['FINANCIAL_PAYMENT_CASE'] == 3): ?>
        <button type="button" onclick="review_rest();" class="btn btn-danger"> إرجاع لمدير الرقابة</button>
    <?php endif; ?>

    <?php if ($isCreate): ?>
        <button type="button" onclick="clear_form();" class="btn btn-default"> تفريغ الحقول</button>
    <?php endif; ?>

    <?php if (HaveAccess($review_doc) && (count($rs) > 0 && $rs['FINANCIAL_PAYMENT_CASE'] == 3)): ?>
        <button type="button" onclick="$('#reviewModal').modal();" class="btn btn-success"> مذكرة رقابة
        </button>
    <?php endif; ?>


    <?php if ((count($rs) > 0 && (($rs['REVIEW_HINTS_USER_NAME'] != '' && $rs['REVIEW_HINTS_USER_NAME'] != null)))): ?>
        <button type="button"
                onclick="_showReport('<?= $report_url ?>&report_type=pdf&report=supervision_note&p_id=<?= $rs['FINANCIAL_PAYMENT_ID'] ?>');"
                class="btn btn-default"> طباعة مذكرة الرقابة
        </button>
    <?php endif; ?>

    <?php if (count($rs) > 0 && $rs['FINANCIAL_PAYMENT_CASE'] >= 4 && HaveAccess($print_per_url)): ?>
      <!--  <a onclick="_showReport('<?= base_url('/reports') ?>?report=financial_payment_rep&params[]=<?= $rs['FINANCIAL_PAYMENT_ID'] ?>');"
           class="btn btn-default" href="javascript:"> طباعة السند</a>-->
        <a onclick="javascript:print_financial_payment_report(<?=$rs['FINANCIAL_PAYMENT_ID']?>);" class="btn btn-default" href="javascript:;">طباعة السند</a>
    <?php endif; ?>
    <?php if (count($rs) > 0 && $rs['FINANCIAL_PAYMENT_CASE'] >= 4 && ($rs['CHECK_ID'] != '' || $rs['PAYMENT_TYPE'] == 3) && HaveAccess($print_per_url)): ?>
        <!--<a onclick="_showReport('<?= base_url('/reports') ?>?report=<?= $rs['PAYMENT_TYPE'] == 2 ? check_reports($rs['CHECK_ID'], $rs['CURR_ID'], $rs['CHECK_BANK_ID']) : ($rs['PAYMENT_TYPE'] == 3 ? 'HEWALAH' : '') ?>&params[]=<?= $rs['FINANCIAL_PAYMENT_ID'] ?>');"
           class="btn btn-success" href="javascript:"> طباعة الشيك</a>-->
       <?php if ($rs['PAYMENT_TYPE'] == 2) { ?>
            <a onclick="_showReport('<?= base_url('/reports') ?>?report=<?= $rs['PAYMENT_TYPE'] == 2 ? check_reports($rs['CHECK_ID'], $rs['CURR_ID'], $rs['CHECK_BANK_ID']) : '' ?>&params[]=<?= $rs['FINANCIAL_PAYMENT_ID'] ?>');"
               class="btn btn-success" href="javascript:"> طباعة الشيك</a>
       <?php } else if ($rs['PAYMENT_TYPE'] == 3) { ?>
            <a onclick="javascript:print_hewalah_report(<?=$rs['FINANCIAL_PAYMENT_ID']?>);" class="btn btn-success" href="javascript:;">طباعة الشيك</a>
    
        <?php } else if ($rs['PAYMENT_TYPE'] == 1) { ?>
            <a onclick="javascript:print_cash_report(<?=$rs['FINANCIAL_PAYMENT_ID']?>);" class="btn btn-success" href="javascript:;">طباعة نقدا</a>
        <?php } ?>


    <?php endif; ?>


</div>
</form>
</div>

</div>
<div class="modal fade" id="notesModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                        class="sr-only">Close</span></button>
                <h4 class="modal-title"> ملاحظات</h4>
            </div>
            <div id="msg_container_alt"></div>

            <div class="form-group col-sm-12">

                <div class="">
                    <textarea type="text" data-val="true" rows="5" id="txt_g_notes" class="form-control "></textarea>

                </div>
            </div>

            <div class="modal-footer">
                <button type="button" onclick="apply_action();" class="btn btn-primary">حفظ البيانات</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>

            </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php if (HaveAccess($review_doc)): ?>
    <div class="modal fade" id="reviewModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span
                            aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title"> مذكرة التدقيق</h4>
                </div>
                <div id="msg_container_alt"></div>

                <div class="form-group col-sm-12">

                    <div class="">
                        <textarea type="text" data-val="true" rows="5" id="txt_review_doc"
                                  class="form-control "></textarea>

                    </div>
                </div>

                <div class="form-group col-sm-12">

                    <label class="control-label col-sm-2">الحالة</label>

                    <div class="col-sm-4">

                        <select name="review_type" id="dp_review_type" class="form-control">
                            <option value="">لا يوجد</option>
                            <?php foreach ($review_hints_type as $row) : ?>
                                <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>


                <div class="modal-footer">
                    <button type="button" onclick="save_review_doc();" class="btn btn-primary">حفظ البيانات
                    </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>

                </div>

            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div><!-- /.modal -->
<?php endif; ?>

<div>
    <?php if (count($rs)): ?>
        <table class="table info">
            <thead>
            <tr>
                <th>المدخل</th>
                <th>اعتماد لخزينة</th>
                <th>اعتماد المدير المالي</th>
                <th>اعتماد الرقابة</th>
            </tr>
            </thead>
            <tbody>

            <tr>
                <td><?= $rs['ENTRY_USER_NAME'] ?></td>
                <td><?= $rs['TRANS_USER_NAME'] ?></td>
                <td><?= $rs['FINANCE_USER_NAME'] ?></td>
                <td><?= $rs['AUDIT_USER_NAME'] ?></td>

            </tr>

            <tr>
                <td><?= $rs['FINANCIAL_PAYMENT_DATE'] ?></td>
                <td><?= $rs['TRANS_DATE'] ?></td>
                <td><?= $rs['FINANCE_DATE'] ?></td>
                <td><?= $rs['AUDIT_DATE'] ?></td>

            </tr>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php
$exp = float_format_exp();
$d_exp = date_format_exp();

$select_accounts_url = base_url('financial/accounts/public_select_accounts');
$customer_url = base_url('payment/customers/public_index');

$CUST_TYPE = isset($CUST_TYPE) ? $CUST_TYPE : 1;
$CHECK_BANK_ID = count($rs) > 0 ? $rs['CHECK_BANK_ID'] : -1;

$rs = preg_replace("/(\r\n|\n|\r)/", "\\n", $rs);

$shared_js = <<<SCRIPT

var selected_financial_payment_account_id = '';
var count = 0;
var d_count = 0;
dp_bk_fin_id
function get_debit_account(){

}

 function goBackPage(){
        setTimeout(function(){

            window.location='{$back_url}';

        }, 1000);
    }
function payment_type(){

    var  _income_type =  $('#dp_payment_type').val();
    if(_income_type !=1)
        $('#for_checks').slideDown();
    else
        $('#for_checks').slideUp();



}

/* MK - test
if($('#dp_payment_type').val() == 1){
    $('#txt_financial_payment_account_id_name').val( 'الخزينة نقدا - دينار' );
    $('#txt_financial_payment_account_id').val( '101010503' );
}
*/

function finacial_payment_type(){
    if($('#dp_financial_payment_type').val() == '1'){
        $('th[data-type="supplier"],td[data-type="supplier"],a[data-type="supplier"]').slideDown();
    }else{
        $('th[data-type="supplier"],td[data-type="supplier"],a[data-type="supplier"]').slideUp();
    }

    if($('#dp_customer_type').val() == 2 ){
                $('#db_customer_account_type_div').show();
            } else {
                $('#db_customer_account_type_div').hide();
            }

    }

$(function(){

    //$('#dp_customer_type,#txt_customer_type').val(2);


    reBind();

    $('#check_curr').text($('#dp_curr_id').find(':selected').text());


});

function addInvoice(){
    _showReport('$get_invoices_url/'+$('input[name="customer_id"]').val()+'/'+$('#dp_curr_id').val());
}


function AddInvoiceToList(bill_number,bill_date,payment_value,declaration){

    if($('input[name="bill_number[]"][value="'+bill_number+'"]').length <=0 ){

        if($('input:text:visible',$('#chains_detailsTbl')).not('input[name="payment_account_id[]"],input[name="payment_account_id_name[]"]').filter(function() { return this.value == ""; }).length <= 0){
            count = count+1;

            var  SupplierHtml='<td data-type="supplier"> <input name="bill_number[]" value="'+bill_number+'" data-val="true" data-val-required="حقل مطلوب" class="form-control" id="bill_number_'+count+'" > </td><td data-type="supplier"> <input name="bill_date[]"  value="'+bill_date+'"  data-val="true" data-val-required="حقل مطلوب" data-type="date" data-date-format="DD/MM/YYYY" class="form-control" id="bill_date_'+count+'" > </td>';

            var html ='<tr> <td><input type="hidden" name="SER[]" value="0"><input type="number" name="payment_account_id[]" id="h_account_'+count+'" class="form-control col-sm-3"> <input name="payment_account_id_name[]"  readonly class="form-control col-sm-8" readonly id="account_'+count+'" > </td>'+SupplierHtml+'<td> <input name="payment_value[]"  value="'+payment_value+'"  data-val-regex="المدخل غير صحيح!" data-val-regex-pattern="$exp" class="form-control" id="payment_value_'+count+'" > </td>  <td><input name="dt_hints[]"   value="'+declaration+'"  type="text" class="form-control" id="dt_hints_'+count+'"  >  </td>  <td class="v_balance"></td><td class="balance"></td><td><a data-action="delete" href="javascript:;"><i class="icon icon-trash delete-action"></i> </a> </td></tr>';
            $('#chains_detailsTbl tbody').append(html);




            reBind();



        }else{
            $('input[name="bill_number[]"]:first').val(bill_number);
            $('input[name="bill_date[]"]:first').val(bill_date);
            $('input[name="payment_value[]"]:first').val(payment_value);
            $('input[name="dt_hints[]"]:first').val(declaration);
            reBind();
        }

    }

    TotalSum();
}

function payment_value(obj){

    var val =parseFloat($(obj).val());
    val = (isNaN(val)?0:val) * parseFloat( $('#txt_curr_value').val());
    var balance =  parseFloat($(obj).closest('tr').find('.balance').text());
    var v_balance =   $(obj).closest('tr').find('.v_balance');
    $(v_balance).text(val.toFixed(2));

    balance = (isNaN(balance)?0:balance);



    if(val > balance){
        alert('تحذير الملبغ المطلوب يجب ألا يتجاوز الرصيد المتاح!!');
        $(obj).val(0);
        $(v_balance).text(0);
    }
}
function TotalSum(){

    var sum = 0;

    $('input[name="payment_value[]"]').each(function(i){


        sum +=isNaNVal($(this).val());
    });

    $('#total').text(sum.toFixed(2));

    var dtotal = isNaNVal($('#dtotal').text());

    $('#nettotal').text((sum - dtotal).toFixed(2) );

}

function TotalDSum(){

    var sum = 0;

    $('input[name="deduction_value[]"]').each(function(i){
        sum +=isNaNVal( $(this).val());
    });

    $('#dtotal').text(sum.toFixed(2));

    var total = isNaNVal($('#total').text());


    $('#nettotal').text((total - sum).toFixed(2));
}

function reBind(){

    $('input[name="bill_number[]"]').keyup(function(){

        $(this).closest('tr').find('input[name="dt_hints[]"]').val('فاتورة رقم : '+$(this).val());

    });

    $('input[name="deduction_value[]"]').keyup(function(){

        TotalDSum();

    });



    delete_action();

    $('input[name="payment_account_id_name[]"]').click(function(e){
        var _type = $('#dp_customer_type').val();
        if(_type != 1)
            _showReport('$select_accounts_url/'+$(this).attr('id')+'/-1/5|4|2|1|3' );
    });

    $('input[name="deduction_account_id_name[]"]').click(function(e){

        selectAccount($(this));

    });

    $('input[name="account_id[]"]').bind('keyup', '+', function(e) {

        $(this).val( $(this).val().replace('+', ''));

        selectAccount($(this).closest('tr').find('input[name$="_name[]"]'));
    });


    $('#dp_curr_id').change(function(){
        $('#txt_curr_value').val($(this).find(':selected').attr('data-val'));
        $('#check_curr').text($(this).find(':selected').text());
        get_payment_account();
        $('input[name="payment_value[]"]').each(function(index){
            payment_value(this);
        });
    });

    $('#dp_financial_payment_type').change(function(){
        finacial_payment_type();
    });

    count = $('input[name="payment_account_id[]"]').length;
     d_count = $('input[name="deduction_account_id[]"]').length;
    get_debit_account();

    $('input[name="payment_value[]"]').keyup(function(){
        TotalSum();
    });

    $('input[data-type="date"]').datetimepicker({
        pickTime: false

    });

    $('input[name="payment_account_id[]"],input[name="deduction_account_id[]"]').keyup(function(){
        get_account_name($(this));
    });
    $('input[name="payment_account_id[]"],input[name="deduction_account_id[]"]').change(function(){
        get_account_name($(this));
    });
    $('#dp_check_bank_id').change(function(){
        check_bank_change();
    });
    get_payment_account();
    change_payment_type($('#dp_payment_type'));
    dp_customer_type_change();
}

function check_bank_change(){
      
    if($('#dp_payment_type').val() == 2){
    
        $('#txt_financial_payment_account_id_name').val($('#dp_check_bank_id').find(':selected').attr('data-account-name'));
        $('#txt_financial_payment_account_id').val($('#dp_check_bank_id').find(':selected').attr('data-account'));
        $('#txt_bank_acounts_number').val($('#dp_check_bank_id').find(':selected').attr('data-bank'));
        
        $('#txt_financial_payment_account_id').attr('data-cash-flow',$('#dp_check_bank_id').find(':selected').attr('data-cash-flow'));
        
    }else if($('#dp_payment_type').val() == 3){
        $('#txt_financial_payment_account_id_name').val($('#dp_check_bank_id').find(':selected').attr('data-transfer-name'));
        $('#txt_financial_payment_account_id').val($('#dp_check_bank_id').find(':selected').attr('data-transfer'));
        $('#txt_bank_acounts_number').val($('#dp_check_bank_id').find(':selected').attr('data-bank'));
        
          $('#txt_financial_payment_account_id').attr('data-cash-flow',$('#dp_check_bank_id').find(':selected').attr('data-trans-cash-flow'));
    }
}

function get_account_name(obj){
    $(obj).closest('tr').find('input[name$="_name[]"]').val('');
    update_balance(obj);
    if($(obj).val().length >6 ){
        get_data('{$get_id_url}',{id:$(obj).val()},function(data){

            if(data.length > 0){

                $(obj).closest('tr').find('input[name$="_name[]"]').val(data[0].ACOUNT_NAME );
            }
        });
    }
}

function update_balance(obj){
    get_data('{$get_balance_url}',{id: $(obj).val()},function(data){
        $(obj).closest('tr').find('.balance').text(data);
    },'html');
}

function addRow(){

    if($('input:text:visible',$('#chains_detailsTbl')).not('input[name="payment_account_id[]"],input[name="payment_account_id_name[]"]').filter(function() { return this.value == ""; }).length <= 0){
        count = count+1;
        var isSupplier = $('th[data-type="supplier"]:visible').length > 0;
        var SupplierHtml = '';
        if(isSupplier)
            SupplierHtml='<td data-type="supplier"> <input name="bill_number[]"  data-val="true" data-val-required="حقل مطلوب" class="form-control" id="bill_number_'+count+'" > </td><td data-type="supplier"> <input name="bill_date[]"   data-val="true" data-val-required="حقل مطلوب" data-type="date" data-date-format="DD/MM/YYYY" class="form-control" id="bill_date_'+count+'" > </td>';
        else
            SupplierHtml='<td style="display:none;"  data-type="supplier"> <input name="bill_number[]" data-val="true" data-val-required="حقل مطلوب" class="form-control" id="bill_number_'+count+'" > </td><td style="display:none;"  data-type="supplier"> <input name="bill_date[]"  data-val="true" data-val-required="حقل مطلوب" data-type="date" data-date-format="DD/MM/YYYY" class="form-control" id="bill_date_'+count+'" > </td>';

        var html ='<tr> <td><input type="hidden" name="SER[]" value="0"><input type="number" name="payment_account_id[]" id="h_account_'+count+'" class="form-control col-sm-3"> <input name="payment_account_id_name[]"  readonly class="form-control col-sm-8" readonly id="account_'+count+'" > </td>'+SupplierHtml+'<td> <input name="payment_value[]" data-val-regex="المدخل غير صحيح!" data-val-regex-pattern="$exp" class="form-control" id="payment_value_'+count+'" > </td>  <td><input name="dt_hints[]" type="text" class="form-control" id="dt_hints_'+count+'"  >  </td>  <td class="v_balance"></td><td class="balance"></td><td><a data-action="delete" href="javascript:;"><i class="icon icon-trash delete-action"></i> </a> </td></tr>';
        $('#chains_detailsTbl tbody').append(html);




        reBind();
    }
}
function addDedRow(){

    var aTypes = $('#dp_account_type_0').html();
    var aDTypes = $('#dp_deduction_type_0').html();

    if($('input:text',$('#deduction_detailsTbl')).not('input[name="deduction_date[]"],select[name="deduction_type[]"]').filter(function() { return this.value == ""; }).length <= 0){
        d_count = d_count+1;
        var html ='<tr><td>   <select name="deduction_account_type[]" id="dp_account_type_'+count+'" class="form-control"> '+aTypes+' </select></td>'+

        ' <td><input type="hidden" name="D_SER[]" value="0"> <input type="number" name="deduction_account_id[]" id="h_deduction_account_id_'+d_count+'" class="form-control col-sm-3" > <input name="deduction_account_id_name[]" data-balance="false" class="form-control col-sm-8" readonly id="deduction_account_id_'+d_count+'" > </td><td> <input name="deduction_value[]" data-val-regex="المدخل غير صحيح!" data-val-regex-pattern="$exp" class="form-control" id="deduction_value_'+d_count+'" > </td> <td>   <select name="deduction_type[]" id="dp_deduction_type_'+count+'" class="form-control"> '+aDTypes+' </select></td>  <td>  <input name="deduction_date[]"   data-type="date"  data-date-format="DD/MM/YYYY"  data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="$d_exp"  class="form-control"  id="deduction_date_'+count+'" > </td> <td>    <input name="d_hints[]"   class="form-control"  id="hints_'+d_count+'" ></td><td><a data-action="delete" href="javascript:;"><i class="icon icon-trash delete-action"></i> </a>  </td></tr>';
        $('#deduction_detailsTbl tbody').append(html);
        reBind();
    }
}

$('button[data-action="submit"]').click(function(e){
    e.preventDefault();
    if(confirm('هل تريد حفظ السند ؟!')){
		  if($('#txt_check_date').val() == '')
              if(!confirm('السند لا يحتوي علي تاريخ شيك')) {
                return;
              }
			       
          if($('select[name="d_customer_account_type[]"]:visible').filter(function(i){ return $(this).val() == '';}).length > 0){
          
                alert('يجب ادخال نوع المستفيد في خانة الاستقطاعات');
                return;
          }
              
			  
        var form = $(this).closest('form');
        ajax_insert_update(form,function(data){
            if(data==1){
                success_msg('رسالة','تم حفظ البيانات بنجاح ..');

                if (typeof clear_form == 'function') {
                    goBackPage();
                }else{
                     reload_Page();
                }
            }else{
                danger_msg('تحذير..',data);
            }
        },'html');
    }
});

$('#dp_payment_type').change(function(){
    change_payment_type($(this));
});

function change_payment_type(obj){
    if($(obj).val()==3){ // حوالة
        $('label#lb_check_id').text('رقم الحساب المحول اليه');
        $('label#lb_check_bank_id').text('البنك المحول منه');
        $('label#lb_check_date').text('تاريخ الحوالة');
        $('label#lb_check_customer').text('الحساب المحول اليه');

        $('#div_convert_bank_transfer,#div_iban').show();

    }else{ // شيك
        $('label#lb_check_id').text('رقم الشيك');
        $('label#lb_check_bank_id').text('البنك المسحوب عليه');
        $('label#lb_check_date').text('تاريخ استحقاق الشيك');
        $('label#lb_check_customer').text('اسم صاحب الشيك');

        $('#div_convert_bank_transfer,#div_iban').hide();
    }

    check_bank_change();
}

$('#dp_payment_type,#dp_curr_id').change(function(){
    payment_type();
    get_debit_account();
});


$('#dp_customer_type').change(function(){
    $('#txt_customer_name').val('');
    $('#h_txt_customer_name').val('');
});



$('#txt_customer_name').click(function(e){
   /* var _type = $('#dp_customer_type').val();
    if(_type == 1)
        _showReport('$select_accounts_url/'+$(this).attr('id') );
    else if(_type == 2)
        _showReport('$customer_url/'+$(this).attr('id')+'/1');
    else if(_type == 3)
        _showReport('$project_accounts_url/'+$(this).attr('id')+'/');*/
});

function selectAccount(obj){
    var _type = $(obj).closest('tr').find('select').val();

    if(_type == 1)
        _showReport('$select_accounts_url/'+$(obj).attr('id') );
    if(_type == 2)
        _showReport('$customer_url/'+$(obj).attr('id')+'/1');
    if(_type == 3)
        _showReport('$project_accounts_url/'+$(obj).attr('id')+'/');

    dp_customer_type_change();
}
function dp_customer_type_change(){

    if({$CUST_TYPE} != 5 && {$CUST_TYPE} != 3){
        $('input[name="payment_account_id[]"],input[name="payment_account_id_name[]"]').hide();
        $('#payment_account_th').text('');
    }else{
        $('input[name="payment_account_id[]"],input[name="payment_account_id_name[]"]').show();
        $('#payment_account_th').text('حساب المصروف');
    }

}
function get_payment_account(){
    get_data('{$public_get_banks_url}',{curr_id: $('#dp_curr_id').val()},function(data){
        $('#dp_check_bank_id').html('');
        $('#txt_bank_acounts_number').val('');
        if(data.length > 0){
            $.each(data,function(i,item){
                $('#dp_check_bank_id').append('<option data-account="'+item.ACOUNT_ID+'" data-transfer="'+item.TRANSER_ACCOUNT_ID+'"  data-bank="'+item.BANK_ACOUNT_ID+'" data-account-name="'+item.ACOUNT_ID_NAME+'" data-transfer-name="'+item.TRANSER_ACCOUNT_ID_NAME+'" data-cash-flow="'+item.CASH_FLOW+'" data-trans-cash-flow="'+item.TRANSER_CASH_FLOW+'" value="'+item.BANK_ID+'">'+item.BANK_ID_NAME+'</option>');

            });
        }
        if('{$CHECK_BANK_ID}' > 0)
        $('#dp_check_bank_id').val('{$CHECK_BANK_ID}');
        check_bank_change();

    });
}

SCRIPT;

$customer_js = '';

$c_cover_js = '';

if (isset($C_CUSTOMER_ID)) {

    $C_COVER_TYPE = preg_replace("/(\r\n|\n|\r)/", "\\n", $C_COVER_TYPE);
    $c_cover_js = <<<SCRIPT
  function apply_caver_change(){
        $('#h_txt_customer_name').val('{$C_CUSTOMER_ID}');
        $('#txt_customer_name').val('{$C_CUSTOMER_ID_NAME}');
        $('#dp_curr_id').val('{$C_CURR_ID}');
        $('#txt_hints').val('{$C_COVER_TYPE}');
        $('#dp_customer_type,#txt_customer_type').val({$C_CUSTOMER_TYPE});
        $('#db_customer_account_type,#txt_customer_account_type').val({$C_CUSTOMER_ACCOUNT_TYPE});


            if({$C_CUST_TYPE} == 1)
                 $('#dp_financial_payment_type,#txt_financial_payment_type').val(1);
             else
                 $('#dp_financial_payment_type,#txt_financial_payment_type').val('2');

            finacial_payment_type();
            
        if({$C_CUSTOMER_TYPE} != 1){
           
           $('#dp_bk_fin_id').closest('div.form-group').hide();
        }
    }
SCRIPT;


}

if (isset($CUSTOMER_ID)) {
    $customer_js = <<<SCRIPT

         $('#h_txt_customer_name').val('{$CUSTOMER_ID}');
            $('#txt_customer_name').val('{$CUSTOMER_ID_NAME}');
            $('#dp_curr_id').val('{$CURR_ID}');
            $('#txt_hints').val('{$COVER_TYPE}');
            $('#dp_customer_type,#txt_customer_type').val({$CUSTOMER_TYPE});
            $('#db_customer_account_type,#txt_customer_account_type').val({$CUSTOMER_ACCOUNT_TYPE});
         

            if({$CUST_TYPE} == 1)
                 $('#dp_financial_payment_type').val(1);
             else
                 $('#dp_financial_payment_type').val('2');

                 finacial_payment_type();
                 
          if({$CUSTOMER_TYPE} != 1){
           
           $('#dp_bk_fin_id').closest('div.form-group').hide();
          }

         $('#txt_curr_value').val($('#dp_curr_id').find(':selected').attr('data-val'));

   get_customer_invoices();

   function get_customer_invoices(){

   }

SCRIPT;
}

if (count($rs) > 0 && ($rs['FINANCIAL_PAYMENT_CASE'] == 0 || $rs['FINANCIAL_PAYMENT_CASE'] > 1)) {
    $customer_js = <<<SCRIPT

    $('#payment_from input').prop('readonly',true);
     $('#payment_from select').prop('disabled',true);

SCRIPT;
}

$scripts = <<<SCRIPT
<script>


  {$shared_js};

   $('#txt_curr_value').val($('#dp_curr_id').find(':selected').attr('data-val'));

    function clear_form(){
        clearForm($('#payment_from'));
        $('#txt_curr_value').val($('#dp_curr_id').find(':selected').attr('data-val'));
        $('#check_curr').text($('#dp_curr_id').find(':selected').text());
        $('#for_checks').slideUp();
          $('#dp_customer_type,#txt_customer_type').val(2);
    }

     $('#dp_payment_type').val(2);
     payment_type();

     $('#dp_curr_id').change(function(){
        $('#dp_trnaser_curr_id').val($(this).val());
     });

 {$customer_js}
</script>
SCRIPT;

if ($isCreate)
    sec_scripts($scripts);

else {
    $edit_script = <<<SCRIPT
<script>
    {$shared_js};
    var action_type;
    $('#txt_financial_payment_id').val('{$rs['FINANCIAL_PAYMENT_ID']}');
    $('#dp_curr_id').val('{$rs['CURR_ID']}');
    $('#txt_curr_value').val('{$rs['CURR_VALUE']}');
    $('#dp_payment_type').val('{$rs['PAYMENT_TYPE']}');
    $('#dp_financial_payment_type').val('{$rs['FINANCIAL_PAYMENT_TYPE']}');
    $('#txt_hints').val('{$rs['HINTS']}');

    $('#dp_customer_type,#txt_customer_type').val('{$rs['CUSTOMER_TYPE']}');
    $('#dp_bk_fin_id').val('{$rs['BK_FIN_ID']}');
    $('#txt_customer_name').val('[{$rs['CUSTOMER_ID']}] {$rs['CUSTOMER_ID_NAME']}');
    $('#h_txt_customer_name').val('{$rs['CUSTOMER_ID']}');
    $('#dp_financial_payment_type,#txt_financial_payment_type').val('{$rs['FINANCIAL_PAYMENT_TYPE']}');
    $('#txt_check_customer').val('{$rs['CHECK_CUSTOMER']}');

    $('#txt_check_id').val('{$rs['CHECK_ID']}');
    $('#dp_check_bank_id').val('{$rs['CHECK_BANK_ID']}');
    $('#txt_check_date').val('{$rs['CHECK_DATE']}');
    $('#txt_note').val('{$rs['NOTE']}');
    $('#txt_convert_bank_transfer').val('{$rs['CONVERT_BANK_TRANSFER']}');

    $('#txt_bank_acounts_number').val('{$rs['BANK_ACOUNTS_NUMBER']}');
    $('#txt_iban').val('{$rs['IBAN']}');
    $('#dp_trnaser_curr_id').val('{$rs['TRNASER_CURR_ID']}');

    $('#txt_review_doc').val('{$rs['REVIEW_HINTS']}');
    $('#dp_review_type').val('{$rs['REVIEW_HINTS_TYPE']}');
    $('#db_customer_account_type,#txt_customer_account_type').val('{$rs['CUSTOMER_ACCOUNT_TYPE']}');

    selected_financial_payment_account_id = '{$rs['FIANANCIAL_PAYMENT_ACCOUNT_ID']}';



    if({$rs['FINANCIAL_PAYMENT_CASE']} <= 2){
        $('#dp_financial_payment_type,#dp_customer_type').prop('disabled',true);
    }else {
        $('#payment_from select').prop('disabled',true);
		$('#payment_from input').prop('readonly',true);
    }

    $('#check_curr').text($('#dp_curr_id').find(':selected').text());
    //$('#txt_curr_value').val($('#dp_curr_id').find(':selected').attr('data-val'));

    $('#print_payment').show();

    function update_case(){
        if(confirm('هل تريد اعتماد السند ؟!')){
            get_data('{$adopt_url}',{financial_payment_id:{$rs['FINANCIAL_PAYMENT_ID']},rnotes:$('#txt_g_notes').val(),hints:'{$rs['HINTS']}'},function(data){
                if(data =='1')
                    success_msg('رسالة','تم إعتماد السند بنجاح ..');
                reload_Page();
            },'html');
        }
    }

    payment_type();
    finacial_payment_type();
    function print_payments(){
        if(confirm('هل تريد طباعة السند ؟!')){
            var val = $('#dp_payment_type').val();
            if(val == 2)
                _showReport('$print_url?report=PALESTAIN_CHECK_FORMS10&params[]={$rs['FINANCIAL_PAYMENT_ID']}');
        else
            _showReport('$print_url?report=financial_payment_rep&params[]={$rs['FINANCIAL_PAYMENT_ID']}');
            get_data('{$adopt_url}',{financial_payment_id:{$rs['FINANCIAL_PAYMENT_ID']}},function(data){
                if(data =='1')
                    success_msg('رسالة','تم إعتماد السند بنجاح ..');
            },'html');
        }
    }


    function payment_detail_tb_delete(td,id){
        if(confirm('هل تريد حذف القيد؟!')){
            ajax_delete_any('$delete_details_url',{id:id},function(data){
                if(data == '1'){
                    $(td).closest('tr').remove();
                    success_msg('رسالة','تم حذف القيد بنجاح ..');
                }
            });
        }
    }

    function payment_detail_tb_delete(td,id,type){
        if(confirm('هل تريد حذف القيد؟!')){
            ajax_delete_any('$delete_details_url',{id:id,type:type},function(data){
                if(data == '1'){
                    $(td).closest('tr').remove();
                success_msg('رسالة','تم حذف البند بنجاح ..');
                }
            });
        }
    }


    function return_mqasah(type){

        if(type == 1 && ! confirm('هل تريد إعتماد السند ؟!')){
            return;
        }
        action_type = type;

        $('#notesModal').modal();

    }


    function apply_action(){

        if(action_type == 1){
            update_case();
        }
        else{

            if($('#txt_g_notes').val() =='' ){
                alert('تحذير : لم تذكر سبب الإرجاع ؟!!');
                return;console.log('',action_type);
            }

            var url = action_type == 0? '{$public_return_url}': '{$review_rest}';
            get_data(url,{financial_payment_id:{$rs['FINANCIAL_PAYMENT_ID']},hints:$('#txt_hints').val()},function(data){
                if(data =='1')
                    success_msg('رسالة','تم  إرجاع السند بنجاح ..');
            },'html');
        }

        get_data('{$notes_url}',{source_id:{$rs['FINANCIAL_PAYMENT_ID']},source:'financial_payment',notes:$('#txt_g_notes').val()},function(data){
            //$('#txt_g_notes').val('');
        },'html');

        $('#notesModal').modal('hide');

        reload_Page();
    }
    {$customer_js};
    TotalSum();
    TotalDSum();

    function save_review_doc(){
        if($('#txt_review_doc').val().length > 0){
               get_data('{$review_doc}',{id:{$rs['FINANCIAL_PAYMENT_ID']},notes:$('#txt_review_doc').val(),review_type: $('#dp_review_type').val()},function(data){
                success_msg('رسالة','تم حفظ بيانات المذكرة بنجاح ..');
                $('#reviewModal').modal('hide');
                   _showReport('$print_url?report=FINANCIAL_PAYMENT_TB_TEDQEEQ&params[]={$rs['FINANCIAL_PAYMENT_ID']}');
                },'html');
        }
    }

 function  review_rest(){

        return_mqasah(2);
    }
    
     //طباعى تقرير السند   
     function print_financial_payment_report(financial_payment_id){
           var rep_url = '{$report_jasper_url}&report_type=pdf&report=financial_payment_rep&P_FINANCIAL_PAYMENT_ID='+financial_payment_id+'&sn={$report_sn}';
           _showReport(rep_url);
     }
        //طباعة تقرير الحوالة
     function print_hewalah_report(financial_payment_id){
            var rep_url = '{$report_jasper_url}&report_type=pdf&report=HEWALAH&p_FINANCIAL_PAYMENT_ID='+financial_payment_id+'&sn={$report_sn}';
            _showReport(rep_url);
     }
          
     //طباعة تقرير نقدا
     function print_cash_report(financial_payment_id){ 
            var rep_url = '{$report_jasper_url}&report_type=pdf&report=transfer_cash_payment&p_FINANCIAL_PAYMENT_ID='+financial_payment_id+'&sn={$report_sn}';
            _showReport(rep_url);
     }

   {$c_cover_js}
</script>
SCRIPT;
    if (isset($can_edit) ? $can_edit : false)
        $edit_script = $edit_script;
    sec_scripts($edit_script);


}

?>
