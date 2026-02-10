<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 25/02/15
 * Time: 09:53 ص
 */

$MODULE_NAME= 'stores';
$TB_NAME= 'class_amount';
$get_page_url =base_url("$MODULE_NAME/$TB_NAME/get_page");
$select_items_url=base_url("$MODULE_NAME/classes/public_index");
$print_url = 'https://itdev.gedco.ps/gfc.aspx?data='.get_report_folder().'&' ;
$trial_balance_url = base_url('stores/reports/class_amount_report');
$professor_account_url = base_url('stores/reports/professor_account');
$report_url = base_url("JsperReport/showreport?sys=financial/archives");
$report_url2 = base_url("JsperReport/showreport?sys=financial/stores");

echo AntiForgeryToken();
?>

<div class="row">
    <div class="toolbar">
        <div class="caption">تقارير المخازن</div>
    </div>

    <div class="form-body">
        <ul class="cpanel">

            <?php /*if( HaveAccess($professor_account_url)): */?> <li><a onclick="javascript:account_create();" href="javascript:;">أرصدة الاصناف</a> </li><?php /*endif;*/?>

            <li><a onclick="javascript:account_create();" href="javascript:;">ميزان المراجعة </a> </li>


        </ul>
        <form class="form-vertical" id="<?=$TB_NAME?>_form" >
            <div class="modal-body inline_form">


                <div class="form-group col-sm-3 rp_prm">
                    <label class="col-sm-3 control-label">الصنف من</label>
                    <div class="col-sm-9">
                        <input readonly class="form-control"  id="txt_class_id" />
                        <input type="hidden" name="class_id" id="h_txt_class_id" />
                    </div>
                </div>

                <div class="form-group col-sm-3 rp_prm">
                    <label class="col-sm-3 control-label">الى</label>
                    <div class="col-sm-9">
                        <input readonly class="form-control"  id="txt_class_id2" />
                        <input type="hidden" name="class_id2" id="h_txt_class_id2" />
                    </div>
                </div>
                <div style="clear: both"></div>

                <div class="form-group col-sm-3 rp_prm"  >
                    <label class="col-sm-3 control-label"> التاريخ </label>
                    <div class="col-sm-9">
                        <input type="text" data-type="date" data-date-format="DD/MM/YYYY" id="txt_date1" class="form-control"/>
                    </div>
                </div>

                <div class="form-group col-sm-3 rp_prm" >
                    <label class="col-sm-3 control-label"> حتى</label>
                    <div class="col-sm-9">
                        <input type="text" data-type="date" data-date-format="DD/MM/YYYY" id="txt_date2" class="form-control"/>
                    </div>
                </div>
                <div style="clear: both"></div>
                <div class="form-group col-sm-3 rp_prm" >
                    <label class="col-sm-5 control-label">حالة الصنف في المخازن</label>
                    <div class="col-sm-7">
                        <select name="report_class_type" class="form-control"   id="dp_class_type">
                            <?php foreach($items_type as $row) :?>
                                <option data-dept="<?= $row['CON_NO'] ?>" value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div style="clear: both"></div>
                <div class="form-group col-sm-3 rp_prm" >
                    <label class="col-sm-5 control-label">حالة ثبات الصنف</label>
                    <div class="col-sm-7">
                        <select name="report_account_type" class="form-control"   id="dp_account_type">
                            <option value="0"></option>
                            <?php foreach($account_type as $row) :?>
                                <option data-dept="<?= $row['CON_NO'] ?>" value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div style="clear: both"></div>
                <div class="form-group col-sm-3 rp_prm" >
                    <label class="col-sm-4 control-label">سعر</label>
                    <div class="col-sm-8">
                        <input type="radio" name="parchaces"  id="parchaces_1" value="1" checked>سعر الشراء
                        <input type="radio" name="parchaces" id="parchaces_2" value="2" >سعر البيع
                        <input type="radio" name="parchaces" id="parchaces_3" value="3" >بدون سعر
                    </div>
                </div>
                <div style="clear: both"></div>
                <div class="form-group col-sm-3 rp_prm" >
                    <label class="col-sm-4 control-label">نوع التقرير</label>
                    <div class="col-sm-8">
                        <input type="radio" name="rep_types" id="rep_types_1" value="1" checked>شامل المحجوز
                        <input type="radio" name="rep_types" id="rep_types_2" value="2" >غير شامل المحجوز

                    </div>
                </div>
            </div>
        </form>

        <div class="modal-footer">
            <button type="button" onclick="javascript:showReport(31);" class="btn btn-success">PDF عرض التقرير</button>
            <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>
        </div>

        <div id="msg_container"></div>

        <div id="container">

        </div>

    </div>

</div>

<?php

$scripts = <<<SCRIPT
<script type="text/javascript">
var val= $('input:radio[name=parchaces]:checked').val();
var val2= $('input:radio[name=rep_types]:checked').val();
$(document).ready(function(){
   $("#class_amount_form").hide();
});
$('input:radio[name=parchaces]').click(function() {
   val= $('input:radio[name=parchaces]:checked').val();

});
$('input:radio[name=rep_types]').click(function() {
   val2= $('input:radio[name=rep_types]:checked').val();

});
    $('#dp_store_id').select2();

    $('#txt_class_id, #txt_class_id2').click("focus",function(e){
        _showReport('$select_items_url/'+$(this).attr('id'));
    });

    function search(){
        $('#container').text('');
        var values= {store_id: $('#dp_store_id').val(), class_id: $('#h_txt_class_id').val(), class_id2: $('#h_txt_class_id2').val() };
        get_data('{$get_page_url}',values ,function(data){
            $('#container').html(data);
        },'html');
    }

    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
        $('#dp_store_id').select2('val',0);
        $("#parchaces_1").prop("checked", true);
        $("#rep_types_1").prop("checked", true);
    }

    $('#print_class_amount_rep').click(function(){
        _showReport("$print_url"+"report=CLASS_AMOUNT&params[]="+$('#h_txt_class_id').val()+"&params[]="+$('#h_txt_class_id2').val()+"&params[]="+$('#dp_store_id').val());
    });
    $('#print_class_prcie_rep').click(function(){
        _showReport("$report_url2"+"&report_type=pdf&report=CLASS_PRCIE_MOVMENT&p_class_id="+$('#h_txt_class_id').val());
    });
   function account_create()
   {
   $("#class_amount_form").show();

   }
function showReport(type){
 var url = '$print_url';

if (val2==1)
{
if(val==1)
{
var url = '$report_url';
url  = url +'&report_type=pdf&report=ITEMS_TOTAL_REP_PRICE_PARCHAISE&p_from_date='+$('#txt_date1').val()+'&p_to_date='+$('#txt_date2').val()+'&p_from_class_id='+$('#h_txt_class_id').val()+
'&p_to_class_id='+$('#h_txt_class_id2').val()+'&p_class_type='+$('#dp_class_type').val()+'&p_class_acount_type='+
 $('#dp_account_type').val()+'&p_from_store='+$('#dp_store_id1').val();
}
if(val==2)
{
var url = '$report_url';
url  = url +'&report_type=pdf&report=ITEMS_TOTAL_REP_PRICE_SALE&p_from_date='+$('#txt_date1').val()+'&p_to_date='+$('#txt_date2').val()+'&p_from_class_id='+$('#h_txt_class_id').val()+
'&p_to_class_id='+$('#h_txt_class_id2').val()+'&p_class_type='+$('#dp_class_type').val()+'&p_class_acount_type='+
 $('#dp_account_type').val()+'&p_from_store='+$('#dp_store_id1').val();
}
if(val==3)
{
var url = '$report_url';
url  = url +'&report_type=pdf&report=ITEMS_TOTAL_REP&p_from_date='+$('#txt_date1').val()+'&p_to_date='+$('#txt_date2').val()+'&p_from_class_id='+$('#h_txt_class_id').val()+
'&p_to_class_id='+$('#h_txt_class_id2').val()+'&p_class_type='+$('#dp_class_type').val()+'&p_class_acount_type='+
 $('#dp_account_type').val()+'&p_from_store='+$('#dp_store_id1').val();
}

}
else if (val2==2)
{
if(val==1)
{
url  = url +'?type='+type+'&report=ITEMS_TOTAL_REP_WITHOUT_RES_PARCHAISE&params[]='+$('#txt_date1').val()+'&params[]='+$('#txt_date2').val()+'&params[]='+$('#h_txt_class_id').val()+
'&params[]='+$('#h_txt_class_id2').val()+'&params[]='+$('#dp_class_type').val()+'&params[]='+
 $('#dp_account_type').val()+'&params[]=&params[]=&params[]=';
}
if(val==2)
{
url  = url +'?type='+type+'&report=ITEMS_TOTAL_REP_WITHOUT_RES_SALE&params[]='+$('#txt_date1').val()+'&params[]='+$('#txt_date2').val()+'&params[]='+$('#h_txt_class_id').val()+
'&params[]='+$('#h_txt_class_id2').val()+'&params[]='+$('#dp_class_type').val()+'&params[]='+
 $('#dp_account_type').val()+'&params[]=&params[]=&params[]=';
}
if(val==3)
{
url  = url +'?type='+type+'&report=ITEMS_TOTAL_REP_WITHOUT_RES&params[]='+$('#txt_date1').val()+'&params[]='+$('#txt_date2').val()+'&params[]='+$('#h_txt_class_id').val()+
'&params[]='+$('#h_txt_class_id2').val()+'&params[]='+$('#dp_class_type').val()+'&params[]='+
 $('#dp_account_type').val()+'&params[]=&params[]=&params[]=';
}
}

_showReport(url);
}
</script>

SCRIPT;

sec_scripts($scripts);

?>
