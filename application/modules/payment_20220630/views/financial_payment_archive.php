<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 08/11/14
 * Time: 09:08 ص
 */
$create_url=base_url('payment/financial_payment/create');
$get_page_url = base_url('payment/financial_payment/get_page_archive');

$report_jasper_url = base_url("JsperReport/showreport?sys=financial/archives");
$report_sn= report_sn();

?>

<?= AntiForgeryToken() ?>
<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
            <?php if( HaveAccess($create_url)):  ?><li><a  href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>جديد </a> </li><?php endif; ?>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>

        </ul>

    </div>

    <div class="form-body">
        <fieldset>
            <legend>بحـث</legend>
            <div class="modal-body inline_form">
                <div class="form-group col-sm-1">
                    <label class="control-label">رقم السند </label>
                    <div>
                        <input type="text"  name="entry_ser" id="txt_entry_ser"   class="form-control">
                    </div>
                </div>
                <div class="form-group col-sm-1">
                    <label class="control-label">رقم النموذج </label>
                    <div>
                        <input type="text"  name="cover_id" id="txt_cover_id"   class="form-control">
                    </div>
                </div>
                <div class="form-group col-sm-2">
                    <label class="control-label"> المستفيد</label>
                    <div>
                        <input type="text"  name="customer_name"    id="txt_customer_name" class="form-control">
                    </div>
                </div>

				
                <div class="form-group col-sm-1">
                    <label class="control-label">  نوع الصرف  </label>
                    <div class="">

                        <select   id="dp_payment_type" class="form-control">
                            <option></option>
                            <?php foreach($payment_type as $row) :?>
                                <option   value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
				
                <div class="form-group col-sm-1">
                    <label class="control-label">  الشيك /الحوالة</label>
                    <div>
                        <input type="text"  name="check_id"    id="txt_check_id" class="form-control">
                    </div>
                </div>
                <div class="form-group col-sm-1">
                    <label class="control-label"> التاريخ من</label>
                    <div>
                        <input type="text" data-type="date" data-date-format="DD/MM/YYYY" name="from_date"    id="txt_from_date" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label"> التاريخ الي</label>
                    <div>
                        <input type="text" data-type="date" data-date-format="DD/MM/YYYY" name="to_date"    id="txt_to_date" class="form-control">
                    </div>
                </div>


                <div class="form-group col-sm-3">
                    <label class="control-label">المبلغ</label>
                    <div>
                        <select id="price_op" class="form-control col-sm-1">
                            <option>=</option>
                            <option><=</option>
                            <option>>=</option>
                            <option><</option>
                            <option>></option>
                        </select>

                        <input type="text"  name="price"   id="txt_price" class="form-control col-sm-5">
                    </div>
                </div>

                <div class="form-group col-sm-2" style="clear: both;">
                    <label class="control-label">البيان</label>
                    <div>
                        <input type="text" name="hints"    id="hints" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">  العملة  </label>
                    <div class="">

                        <select   id="dp_currency_id" class="form-control">
                            <option></option>
                            <?php foreach($currency as $row) :?>
                                <option data-val="<?= $row['VAL'] ?>" value="<?= $row['CURR_ID'] ?>"><?= $row['CURR_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">البنك</label>
                    <div>
                        <select name="check_bank_id" id="dp_check_bank_id" class="form-control">
                            <option></option>
                            <?php foreach($banks as $row) :?>
                                <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>


                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">الحالة</label>
                    <div>
                        <select id="dp_status" class="form-control">
                            <option  >  </option>
                            <option value="2"> الخزينة</option>
                            <option value="3">الإدارة المالية </option>
                            <option value="4">الرقابة </option>
                            <option value="0"> ملغي</option>
                            <option value="6"> منجز</option>
                            <option value="5"> غير مستلم</option>
                            <option value="7">الغير مؤرشفة</option>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">رقم الفاتورة</label>
                    <div>
                        <input type="text" name="invoice_id"    id="invoice_id" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label"> تاريخ التسليم من</label>
                    <div>
                        <input type="text" data-type="date" data-date-format="DD/MM/YYYY" name="from_date_receipt_date"    id="txt_from_date_receipt_date" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label"> التاريخ الي</label>
                    <div>
                        <input type="text" data-type="date" data-date-format="DD/MM/YYYY" name="to_date_receipt_date"    id="txt_to_date_receipt_date" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2" style="clear: both;">
                    <label class="control-label">المدخل</label>
                    <div>
                        <input type="text" name="entry_user"    id="entry_user" class="form-control">
                    </div>
                </div>

				  <div class="form-group col-sm-2">
                    <label class="control-label">نوع حساب المستفيد</label>
                    <select class="form-control" id="db_customer_account_type"    >
                        <option></option>
                        <?php foreach ($ACCOUNT_TYPES as $row) : ?>
                            <option value="<?= $row['CON_NO'] ?>" ><?= $row['CON_NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

            </div>
            <div class="modal-footer">

                <button type="button" onclick="javascript:do_search();" class="btn btn-success">بحث</button>
                <div class="btn-group">
                    <button class="btn btn-warning dropdown-toggle" onclick="$('#chainsTbl').tableExport({type:'excel',escape:'false'});" data-toggle="dropdown"><i class="fa fa-bars"></i> تصدير</button>
                </div>
                <button type="button" onclick="javascript:clearForm_any($('fieldset'));do_search();" class="btn btn-default">تفريغ الحقول</button>

            </div>
        </fieldset>

        <div id="msg_container"></div>

        <div id="container">
            <?=modules::run('payment/financial_payment/get_page_archive',$page);?>
        </div>

    </div>

</div>



<?php


$scripts = <<<SCRIPT

<script>
  $(function(){
        reBind();
    });

    function reBind(){

    ajax_pager({customer_account_type :$('#db_customer_account_type').val(),payment_type : $('#dp_payment_type').val(),id : $('#txt_entry_ser').val(),entry_user:$('#entry_user').val(),status:$('#dp_status').val(),name:$('#txt_customer_name').val(),check_id:$('#txt_check_id').val(),from_date:$('#txt_from_date').val(),to_date:$('#txt_to_date').val(),price:$('#txt_price').val(),price_op:$('#price_op').val(),cover:$('#txt_cover_id').val(),hints:$('#hints').val(),bank:$('#dp_check_bank_id').val(),currency:$('#dp_currency_id').val(),invoice_id:$('#invoice_id').val(),from_date_receipt_date:$('#txt_from_date_receipt_date').val(),to_date_receipt_date:$('#txt_to_date_receipt_date').val()});

    }

    function LoadingData(){

    ajax_pager_data('#chainsTbl > tbody',{customer_account_type :$('#db_customer_account_type').val(),payment_type : $('#dp_payment_type').val(),id : $('#txt_entry_ser').val(),entry_user:$('#entry_user').val(),status:$('#dp_status').val(),name:$('#txt_customer_name').val(),check_id:$('#txt_check_id').val(),from_date:$('#txt_from_date').val(),to_date:$('#txt_to_date').val(),price:$('#txt_price').val(),price_op:$('#price_op').val(),cover:$('#txt_cover_id').val(),hints:$('#hints').val(),bank:$('#dp_check_bank_id').val(),currency:$('#dp_currency_id').val(),invoice_id:$('#invoice_id').val(),from_date_receipt_date:$('#txt_from_date_receipt_date').val(),to_date_receipt_date:$('#txt_to_date_receipt_date').val()});

    }


   function do_search(){

        get_data('{$get_page_url}',{page: 1,customer_account_type :$('#db_customer_account_type').val(),payment_type : $('#dp_payment_type').val(),id : $('#txt_entry_ser').val(),entry_user:$('#entry_user').val(),status:$('#dp_status').val(),name:$('#txt_customer_name').val(),check_id:$('#txt_check_id').val(),from_date:$('#txt_from_date').val(),to_date:$('#txt_to_date').val(),price:$('#txt_price').val(),price_op:$('#price_op').val(),cover:$('#txt_cover_id').val(),hints:$('#hints').val(),bank:$('#dp_check_bank_id').val(),currency:$('#dp_currency_id').val(),invoice_id:$('#invoice_id').val(),from_date_receipt_date:$('#txt_from_date_receipt_date').val(),to_date_receipt_date:$('#txt_to_date_receipt_date').val()},function(data){
            $('#container').html(data);

            reBind();

        },'html');
    }
    
     function print_hewalah_report(financial_payment_id){
        var rep_url = '{$report_jasper_url}&report_type=pdf&report=HEWALAH&p_FINANCIAL_PAYMENT_ID='+financial_payment_id+'&sn={$report_sn}';
        _showReport(rep_url);

    }
</script>
SCRIPT;

sec_scripts($scripts);



?>

