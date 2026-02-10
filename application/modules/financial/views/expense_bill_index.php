<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 08/11/14
 * Time: 09:08 ص
 */
$create_url=base_url('financial/expense_bill/create');
$get_page_url = base_url('financial/expense_bill/get_page');
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
                    <label class="control-label">رقم الفاتورة </label>
                    <div>
                        <input type="text"  name="entry_ser" id="txt_entry_ser"   class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label"> المستفيد</label>
                    <div>
                        <input type="text"  name="customer_name"    id="txt_customer_name" class="form-control">
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
            </div>
            <div class="modal-footer">

                <button type="button" onclick="javascript:do_search();" class="btn btn-success">بحث</button>
                <button type="button" onclick="$('#expenseTbl').tableExport({type:'excel',escape:'false'});" class="btn btn-success">  اكسل </button>
                <button type="button" onclick="javascript:clearForm_any($('fieldset'));do_search();" class="btn btn-default">تفريغ الحقول</button>

            </div>
        </fieldset>
        <div id="msg_container"></div>

        <div id="container">
            <?=modules::run('financial/expense_bill/get_page',$page,$case);?>
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

    ajax_pager({action:'{$action}',id : $('#txt_entry_ser').val(),name:$('#txt_customer_name').val(),from_date:$('#txt_from_date').val(),to_date:$('#txt_to_date').val()});

    }

    function LoadingData(){

    ajax_pager_data('#expenseTbl > tbody',{action:'{$action}',id : $('#txt_entry_ser').val(),name:$('#txt_customer_name').val(),from_date:$('#txt_from_date').val(),to_date:$('#txt_to_date').val()});

    }


   function do_search(){

        get_data('{$get_page_url}',{page: 1,action:'{$action}',id : $('#txt_entry_ser').val(),name:$('#txt_customer_name').val(),from_date:$('#txt_from_date').val(),to_date:$('#txt_to_date').val()},function(data){
            $('#container').html(data);

            reBind();

        },'html');
    }
    
    //طباعة تقرير السند الجديد
   function print_financial_payment_report(financial_payment_id){
        var rep_url = '{$report_jasper_url}&report_type=pdf&report=financial_payment_rep&P_FINANCIAL_PAYMENT_ID='+financial_payment_id+'&sn={$report_sn}';
        _showReport(rep_url);
    }
</script>
SCRIPT;

sec_scripts($scripts);



?>

