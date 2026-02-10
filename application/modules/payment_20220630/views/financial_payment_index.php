<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 08/11/14
 * Time: 09:08 ص
 */
$create_url=base_url('payment/payment_cover');
$get_page_url = base_url('payment/financial_payment/get_page');

$report_jasper_url = base_url("JsperReport/showreport?sys=financial/archives");
$report_sn= report_sn();
?>

<?= AntiForgeryToken() ?>
<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
<!--            <?php /*if( HaveAccess($create_url)):  */?><li><a  href="<?/*= $create_url */?>"><i class="glyphicon glyphicon-plus"></i>جديد </a> </li>--><?php /*endif; */?>
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
                    <label class="control-label">  الشيك /الحوالة</label>
                    <div>
                        <input type="text"  name="check_id"    id="txt_check_id" class="form-control">
                    </div>
                </div>
                <div class="form-group col-sm-2">
                    <label class="control-label"> التاريخ من</label>
                    <div>
                        <input type="text" data-type="date" data-date-format="DD/MM/YYYY" name="from_date"    id="txt_from_date" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
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

            </div>
            <div class="modal-footer">

                <button type="button" onclick="javascript:do_search();" class="btn btn-success">بحث</button>

                <button type="button" onclick="javascript:clearForm_any($('fieldset'));do_search();" class="btn btn-default">تفريغ الحقول</button>

            </div>
        </fieldset>

        <div id="msg_container"></div>

        <div id="container">
            <?=modules::run('payment/financial_payment/get_page',$page,$case,$action);?>
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

    ajax_pager({case :{$case} ,action:'{$action}' ,id : $('#txt_entry_ser').val(),name:$('#txt_customer_name').val(),check_id:$('#txt_check_id').val(),from_date:$('#txt_from_date').val(),to_date:$('#txt_to_date').val(),price:$('#txt_price').val(),price_op:$('#price_op').val(),cover:$('#txt_cover_id').val()});

    }

    function LoadingData(){

    ajax_pager_data('#chainsTbl > tbody',{case :{$case},action:'{$action}' ,id : $('#txt_entry_ser').val(),name:$('#txt_customer_name').val(),check_id:$('#txt_check_id').val(),from_date:$('#txt_from_date').val(),to_date:$('#txt_to_date').val(),price:$('#txt_price').val(),price_op:$('#price_op').val(),cover:$('#txt_cover_id').val()});

    }


   function do_search(){

        get_data('{$get_page_url}',{page: 1,case :{$case},action:'{$action}' ,id : $('#txt_entry_ser').val(),name:$('#txt_customer_name').val(),check_id:$('#txt_check_id').val(),from_date:$('#txt_from_date').val(),to_date:$('#txt_to_date').val(),price:$('#txt_price').val(),price_op:$('#price_op').val(),cover:$('#txt_cover_id').val()},function(data){
            $('#container').html(data);

            reBind();

        },'html');
    }
    //طباعة تقرير الحوالة
    function print_hewalah_report(financial_payment_id){
        var rep_url = '{$report_jasper_url}&report_type=pdf&report=HEWALAH&p_FINANCIAL_PAYMENT_ID='+financial_payment_id+'&sn={$report_sn}';
        _showReport(rep_url);
    }
    //طباعة تقرير السند الجديد
   function print_financial_payment_report(financial_payment_id){
        var rep_url = '{$report_jasper_url}&report_type=pdf&report=financial_payment_rep&P_FINANCIAL_PAYMENT_ID='+financial_payment_id+'&sn={$report_sn}';
        _showReport(rep_url);
    }
    
    //طباعة تقرير نقدا
     function print_cash_report(financial_payment_id){ 
            var rep_url = '{$report_jasper_url}&report_type=pdf&report=transfer_cash_payment&p_FINANCIAL_PAYMENT_ID='+financial_payment_id+'&sn={$report_sn}';
            _showReport(rep_url);
     }
    
    
</script>
SCRIPT;

sec_scripts($scripts);



?>

