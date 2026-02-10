<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 08/11/14
 * Time: 09:08 ص
 */

$TB_NAME= 'financial_payment';
$get_url=base_url('payment/financial_payment/delivery');

$select_accounts_url =base_url('financial/accounts/public_select_accounts');
$customer_url =base_url('payment/customers/public_index');

?>

<?= AntiForgeryToken() ?>
<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
        </ul>

    </div>

    <div class="form-body">

        <div class="modal-body inline_form">
            <form  id="<?=$TB_NAME?>_form" method="post" action="<?=$get_url?>" role="form" novalidate="novalidate">
                <input type="hidden" name="action" id="h_action" value="get">

                <div class="form-group col-sm-1">
                    <label class="control-label">م</label>
                    <div>
                        <input type="text" readonly id="txt_count" value="" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">رقم السند</label>
                    <div>
                        <input type="text" name="fid" id="txt_id" value="" class="form-control">
                        <input type="hidden"  name="id" id="txt_f_id" value="" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-3">
                    <label class="control-label"> المستفيد </label>
                    <div>
                        <input name="customer_name" data-balance="false" data-val="true" readonly title="اضغط هنا" data-val-required="حقل مطلوب"   class="form-control" readonly id="txt_customer_name" value="">
                        <input type="hidden" name="customer_id" value="" id="h_txt_customer_name">
                    </div>
                </div>

                <div id="receipt_data" style="display: none" >

                    <div class="form-group col-sm-2">
                        <label class="control-label">هوية المستلم</label>
                        <div>
                            <input type="text" maxlength="9" name="receipt_customer_id" id="txt_receipt_customer_id" value="" class="form-control">
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">اسم المستلم</label>
                        <div>
                            <input type="text" maxlength="50"  name="receipt_customer_name" id="txt_receipt_customer_name" value="" class="form-control">
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label"> تاريخ التسليم </label>
                        <div>
                            <input type="text" name="receipt_date" data-type="date" data-date-format="DD/MM/YYYY" data-val="true" id="txt_receipt_date" class="form-control" data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?=date_format_exp()?>"  />
                            <span class="field-validation-valid" data-valmsg-for="receipt_date" data-valmsg-replace="true"></span>
                        </div>
                    </div>
                </div>

            </form>
        </div>

        <div id="msg_container"></div>

        <div id="container">

            <div id="payment_data" class="checks_div">
                <div class="form-group col-sm-2">
                    <label class="control-label" >اجمالي المبلغ</label>
                    <div id="total">
                    </div>
                </div>

                <div class="form-group col-sm-9">
                    <label class="control-label" >البيان</label>
                    <div id="hints">
                    </div>
                </div>
            </div>

            <div id="for_checks" class="checks_div">
                <div class="form-group col-sm-2">
                    <label class="control-label" id="lb_check_id">رقم الشيك</label>
                    <div id="check_id">
                    </div>
                </div>

                <div class="form-group col-sm-3">
                    <label class="control-label" id="lb_check_customer">اسم صاحب الشيك</label>
                    <div id="check_customer">
                    </div>
                </div>
                <div class="form-group col-sm-2">
                    <label class="control-label" id="lb_check_bank_id">البنك المسحوب عليه</label>
                    <div id="check_bank_id">
                    </div>
                </div>
                <div class="form-group col-sm-2">
                    <label class="control-label" id="lb_check_date">تاريخ استحقاق الشيك</label>
                    <div id="check_date">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">العملة</label>
                    <div id="check_curr">
                    </div>
                </div>
            </div>

        </div>

    </div>

    <div class="modal-footer">
        <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
        <button type="reset"  onclick="javascript:<?=$TB_NAME?>_clear();"  class="btn btn-default"> تفريغ الحقول</button>
        <button type="button" id="btn_search" onclick="javascript:<?=$TB_NAME?>_search();" class="btn btn-success"> إستعلام</button>
        |
        <button type="button" id="btn_next" onclick="javascript:<?=$TB_NAME?>_show(payment_page+1);" class="btn btn-default"> التالي</button>
        <button type="button" id="btn_prev" onclick="javascript:<?=$TB_NAME?>_show(payment_page-1);" class="btn btn-default"> السابق</button>

    </div>

</div>

<?php
$scripts = <<<SCRIPT
<script>

    var payment_page= 0;
    var count=0;
    var data=[];
    var deleted=[];

    $(function(){
        $('input[type="text"],body').bind('keydown', 'f3', function() {
            {$TB_NAME}_search();
            return false;
        });

        $('input[type="text"],body').bind('keydown', 'f2', function() {
            {$TB_NAME}_clear();
            return false;
        });

        $('input[type="text"],body').bind('keydown', 'down', function() {
            {$TB_NAME}_show(payment_page+1);
            return false;
        });

        $('input[type="text"],body').bind('keydown', 'up', function() {
            {$TB_NAME}_show(payment_page-1);
            return false;
        });
    });

    function {$TB_NAME}_search(){
        var values= $('#{$TB_NAME}_form').serialize();
        if( ($('#txt_id').val()!='' || $('#h_txt_customer_name').val()!='') && $('#h_action').val()=='get' ){
            get_data('{$get_url}',values,function(ret){
                data= ret;
                count= data.length;
                if(count == 0){
                    alert('لم يتم العثور على اي نتائج');
                    return;
                }
                $('#h_action').val('');
                $('#btn_search').attr('disabled', 'disabled');
                {$TB_NAME}_show(0);
            });
        }else
            alert('ادخل رقم الايصال او المستفيد');
    }

    $('button[data-action="submit"]').click(function(e){
        e.preventDefault();

        var receipt_id= $('#txt_receipt_customer_id').val();
        var receipt_name= $('#txt_receipt_customer_name').val();
        var receipt_date= $('#txt_receipt_date').val();
        var msg= '';

        if(receipt_id.length!=9 || isNaN(receipt_id))
            msg= 'هوية المستلم لا تساوي 9 ارقام،';
        if(receipt_name.length < 15)
            msg+= ' اسم المستلم اقل من 15 حرف،';

        if(receipt_id!='' && receipt_name!='' && receipt_date!=''){
            if(confirm('هل تريد بالتأكيد تسليم المبلغ؟!')){
                if(msg=='' || ( msg!='' && confirm(msg+' هل تريد حفظ البيانات على أي حال؟! ') )){
                    $('#h_action').val('receipt');
                    var form = $('#{$TB_NAME}_form');
                    ajax_insert_update(form,function(res){
                        if(res==1){
                            $('button[data-action="submit"]').attr('disabled', 'disabled');
                            delete data[payment_page];
                            deleted.push(payment_page);
                            $('#h_action').val('');
                            success_msg('رسالة','تم تسليم المبلغ بنجاح ..');
                        }
                        else
                            warning_msg('تنبيه','لم يتم تسليم المبلغ ..');
                    },"html");
                }
            }
        }else
            alert('ادخل بيانات المستلم');
    });

    function {$TB_NAME}_show(i){
        $('#btn_prev, #btn_next').removeAttr('disabled');
        if(!(i>=0 && i<count)){
            if(i > payment_page){// next
                $('#btn_next').attr('disabled', 'disabled');
            }else{ // prev
                $('#btn_prev').attr('disabled', 'disabled');
            }
            return;
        }
        if($.inArray(i,deleted)!=-1){
            if(i > payment_page)// next
                {$TB_NAME}_show(i+1);
            else // prev
                {$TB_NAME}_show(i-1);
            return;
        }
        payment_page= i;
        var item= data[i];
        $('#txt_count').val(i+1);
        $('button[data-action="submit"]').removeAttr('disabled');
        $('#txt_receipt_customer_id, #txt_receipt_customer_name, #txt_receipt_date').val('');
        $('#payment_data, #receipt_data').show();
        $('#txt_receipt_customer_id').focus();

        if(item.PAYMENT_TYPE==3){ // حوالة
            $('label#lb_check_id').text('رقم الحوالة');
            $('label#lb_check_bank_id').text('البنك المحول اليه');
            $('label#lb_check_date').text('تاريخ الحوالة');
            $('label#lb_check_customer').text('اسم صاحب الحوالة');
            $('#for_checks').show();
        }else if(item.PAYMENT_TYPE==2){ // شيك
            $('label#lb_check_id').text('رقم الشيك');
            $('label#lb_check_bank_id').text('البنك المسحوب عليه');
            $('label#lb_check_date').text('تاريخ استحقاق الشيك');
            $('label#lb_check_customer').text('اسم صاحب الشيك');
            $('#for_checks').show();
        }else{
            $('#for_checks').hide();
        }

        $('#txt_id').prop('readonly',true);
        $('#txt_f_id').val(item.FINANCIAL_PAYMENT_ID);
        $('#txt_customer_name').val(item.CUSTOMER_ID_NAME);

        $('#total').text(item.TOTAL);
        if(item.TOTAL== null)
            $('#total').text(0);
        $('#hints').text(item.HINTS);

        $('#check_id').text(item.CHECK_ID);
        $('#check_customer').text(item.CHECK_CUSTOMER);
        $('#check_bank_id').text(item.CHECK_BANK_ID_NAME);
        $('#check_date').text(item.CHECK_DATE);
        $('#check_curr').text(item.CURR_ID_NAME);
        $('#txt_receipt_customer_id').val(item.RECEIPT_CUSTOMER_ID);
        $('#txt_receipt_customer_name').val(item.RECEIPT_CUSTOMER_NAME);
        $('#txt_receipt_date').val(item.RECEIPT_DATE);

    }

    function {$TB_NAME}_clear(){
        clearForm($('#{$TB_NAME}_form'));
        $('#h_action').val('get');
        $('#txt_id').prop('readonly',false);
        $('#payment_data, #receipt_data, #for_checks').hide();
        $('button[data-action="submit"], #btn_search, #btn_prev, #btn_next').removeAttr('disabled');
        payment_page= 0;
        count=0;
        data=[];
        deleted=[];
    }

    $('#txt_customer_name').bind("focus",function(e){
        if($('#txt_id').val()==''){
            var _type = 2;
            if(_type == 1)
                _showReport('$select_accounts_url/'+$(this).attr('id') );
            else if(_type == 2)
                _showReport('$customer_url/'+$(this).attr('id')+'/1');
            else if(_type == 3)
                _showReport('$customer_url/'+$(this).attr('id')+'/2');
        }else
            alert('للبحث بواسطة المستفيد، امسح رقم الايصال');
    });

    $('#txt_customer_name').click(function(e){
        if($('#txt_id').val()==''){
            var _type = 2;
            if(_type == 1)
                _showReport('$select_accounts_url/'+$(this).attr('id') );
            else if(_type == 2)
                _showReport('$customer_url/'+$(this).attr('id')+'/1');
            else if(_type == 3)
                _showReport('$customer_url/'+$(this).attr('id')+'/2');
        }else
            alert('للبحث بواسطة المستفيد، امسح رقم الايصال');
    });

</script>
SCRIPT;
sec_scripts($scripts);

?>

