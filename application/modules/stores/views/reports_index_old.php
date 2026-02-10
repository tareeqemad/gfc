<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 03/01/15
 * Time: 07:37 م
 */

$MODULE_NAME= 'stores';
$TB_NAME= 'class_amount';
$get_page_url =base_url("$MODULE_NAME/$TB_NAME/get_page");
$select_items_url=base_url("$MODULE_NAME/classes/public_index");
$print_url = gh_itdev_rep_url().'/gfc.aspx';
$trial_balance_url = base_url('stores/reports/class_amount_report');
$professor_account_url = base_url('stores/reports/professor_movment');
$min_account_url = base_url('stores/reports/min_professor_movment');
$select_accounts_url =base_url('financial/accounts/public_select_accounts');
$customer_url =base_url('payment/customers/public_index');
$get_class_url =base_url('stores/classes/public_get_id');
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

            <?php if( HaveAccess($trial_balance_url)): ?> <li><a onclick="javascript:account_create();"  href="javascript:;" id="a1">أرصدة الاصناف</a> </li><?php endif;?>
            <?php if(0 and HaveAccess($professor_account_url)): ?> <li><a onclick="javascript:account_create();" href="javascript:;" id="a2"">حركات الاصناف في الحسابات</a> </li><?php endif;?>
            <?php if( HaveAccess($min_account_url)): ?> <li><a onclick="javascript:account_create();" href="javascript:;" id="a3"">الاصناف التي بلغت الحد الادنى</a> </li><?php endif;?>
        </ul>
        <form class="form-vertical" id="<?=$TB_NAME?>_form" >
            <div class="modal-body inline_form">

                <div class="form-group col-sm-3 rp_prm" >
                    <label class="col-sm-3 control-label">من صنف</label>
                    <div class="col-sm-9">
                        <input type="text" name="class_id" id="h_txt_class_id" class="form-control col-sm-2"/>
                        <div class="form-group col-sm-6 rp_prm"  >
                            <input readonly class="form-control"  id="txt_class_id" name="txt_class_id" />
                        </div>
                    </div>
                </div>

                <div class="form-group col-sm-3 rp_prm">
                    <label class="col-sm-3 control-label">الى</label>


                    <div class="col-sm-9">
                        <input type="text" name="class_id2" id="h_txt_class_id2" class="form-control col-sm-2"/>
                        <div class="form-group col-sm-6 rp_prm"  >
                            <input readonly class="form-control"  id="txt_class_id2" name="txt_class_id2" />
                        </div>
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
                    <label class="col-sm-3 control-label">من مخزن</label>
                    <div class="col-sm-7">
                        <select name="store_id1" id="dp_store_id1" class="form-control"  >
                            <option></option>
                            <?php foreach($stores as $row) :?>
                                <option value="<?=$row['STORE_ID']?>"><?=$row['STORE_NO'].': '.$row['STORE_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group col-sm-3 rp_prm" >
                    <label class="col-sm-3 control-label">الى</label>
                    <div class="col-sm-7">
                        <select name="store_id2" id="dp_store_id2" class="form-control"  >
                            <option></option>
                            <?php foreach($stores as $row) :?>
                                <option value="<?=$row['STORE_ID']?>"><?=$row['STORE_NO'].': '.$row['STORE_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
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
                            <option></option>
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
                        <input type="radio" name="rep_types" id="rep_types_2" value="2" >غير شامل المحجوز                </div>
                </div>
            </div>
        </form>
        <form class="form-vertical" id="<?=$TB_NAME?>_form1" >
            <div class="modal-body inline_form">

                <div class="form-group col-sm-3 rp_prm" >
                    <label class="col-sm-3 control-label">من صنف</label>
                    <div class="col-sm-9">
                        <input type="text" name="class_id1" id="h_txt_class_id1" class="form-control col-sm-2"/>
                        <div class="form-group col-sm-6 rp_prm"  >
                            <input readonly class="form-control"  id="txt_class_id1" name="txt_class_id1" />
                        </div>
                    </div>
                </div>

                <div class="form-group col-sm-3 rp_prm">
                    <label class="col-sm-3 control-label">الى</label>


                    <div class="col-sm-9">
                        <input type="text" name="class_id22" id="h_txt_class_id22" class="form-control col-sm-2"/>
                        <div class="form-group col-sm-6 rp_prm"  >
                            <input readonly class="form-control"  id="txt_class_id22" name="txt_class_id22" />
                        </div>
                    </div>

                </div>

                <div style="clear: both"></div>

                <div class="form-group col-sm-3 rp_prm"  >
                    <label class="col-sm-3 control-label"> التاريخ </label>
                    <div class="col-sm-9">
                        <input type="text" data-type="date" data-date-format="DD/MM/YYYY" id="txt_date11" class="form-control"/>
                    </div>
                </div>

                <div class="form-group col-sm-3 rp_prm" >
                    <label class="col-sm-3 control-label"> حتى</label>
                    <div class="col-sm-9">
                        <input type="text" data-type="date" data-date-format="DD/MM/YYYY" id="txt_date22" class="form-control"/>
                    </div>
                </div>
                <div style="clear: both"></div>
                <div class="form-group col-sm-3 rp_prm" >
                    <label class="col-sm-5 control-label">حالة الصنف في المخازن</label>
                    <div class="col-sm-5">
                        <select name="report_class_type1" class="form-control"   id="dp_class_type1">
                            <?php foreach($items_type as $row) :?>
                                <option data-dept="<?= $row['CON_NO'] ?>" value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div style="clear: both"></div>
                <div class="form-group col-sm-3 rp_prm" >
                    <label class="col-sm-3 control-label">نوع الحساب</label>
                    <div class="col-sm-7">
                        <select name="request_side" id="dp_request_side" class="form-control" />
                        <option></option>
                        <?php foreach($request_side_all as $row) :?>
                            <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>

                </div>

                <div class="form-group col-sm-3 rp_prm" >

                    <div class="col-sm-7">
                        <input type="text"  readonly  class="form-control" id="txt_class_output_account_id" />
                        <input type="hidden" name="class_output_account_id" id="h_txt_class_output_account_id" />
                    </div>
                </div>


                <div style="clear: both"></div>
                <div class="form-group col-sm-3 rp_prm" >
                    <label class="col-sm-3 control-label">سعر</label>
                    <div class="col-sm-8">
                        <input type="radio" name="parchaces1"  id="parchaces_4" value="1" checked>سعر الشراء
                        <input type="radio" name="parchaces1" id="parchaces_5" value="2" >سعر البيع

                    </div>
                </div>
                <div style="clear: both"></div>
                <div class="form-group col-sm-3 rp_prm" >
                    <label class="col-sm-3 control-label">الكميات</label>
                    <div class="col-sm-8">
                        <input type="radio" name="ammount" id="ammount_1" value="1" checked>كمية صادرة
                        <input type="radio" name="ammount" id="ammount_2" value="2" >كمية واردة
                        <input type="radio" name="ammount" id="ammount_3" value="3" >كمية صادرة و واردة معا

                    </div>
                </div>
            </div>
        </form>
        <form class="form-vertical" id="<?=$TB_NAME?>_form2" >
            <div class="modal-body inline_form">

                <div class="form-group col-sm-3 rp_prm" >
                    <label class="col-sm-3 control-label">من صنف</label>
                    <div class="col-sm-9">
                       <input type="text" name="class_id3" id="h_txt_class_id3" class="form-control col-sm-2"/>
                        <div class="form-group col-sm-6 rp_prm"  >
                        <input readonly class="form-control"  id="txt_class_id3" name="txt_class_id3" />
                    </div>
                        </div>
                </div>

                <div class="form-group col-sm-3 rp_prm">
                    <label class="col-sm-3 control-label">الى</label>


                    <div class="col-sm-9">
                        <input type="text" name="class_id3" id="h_txt_class_id4" class="form-control col-sm-2"/>
                        <div class="form-group col-sm-6 rp_prm"  >
                            <input readonly class="form-control"  id="txt_class_id4" name="txt_class_id4" />
                        </div>
                    </div>

                    </div>
                </div>
                <div style="clear: both"></div>

                <div class="form-group col-sm-3 rp_prm"  >
                    <label class="col-sm-3 control-label"> التاريخ </label>
                    <div class="col-sm-7">
                        <input type="text" data-type="date" data-date-format="DD/MM/YYYY" id="txt_date14" class="form-control"/>
                    </div>
                </div>

                <div class="form-group col-sm-3 rp_prm" >
                    <label class="col-sm-3 control-label"> حتى</label>
                    <div class="col-sm-7">
                        <input type="text" data-type="date" data-date-format="DD/MM/YYYY" id="txt_date24" class="form-control"/>
                    </div>
                </div>
                <div style="clear: both"></div>
                <div class="form-group col-sm-3 rp_prm" >
                    <label class="col-sm-3 control-label">المخزن</label>
                    <div class="col-sm-7">
                                    <select name="store_id" id="dp_store_id" class="form-control"  >
                                        <option></option>
                            <?php foreach($stores as $row) :?>
                                <option value="<?=$row['STORE_ID']?>"><?=$row['STORE_NO'].': '.$row['STORE_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div style="clear: both"></div>
                <div class="form-group col-sm-3 rp_prm" >
                    <label class="col-sm-3 control-label">حالة الصنف في المخازن</label>
                    <div class="col-sm-7">
                        <select name="report_class_type4" class="form-control"   id="dp_class_type4">
                            <?php foreach($items_type as $row) :?>
                                <option data-dept="<?= $row['CON_NO'] ?>" value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            <div style="clear: both"></div>
            <div class="form-group col-sm-3 rp_prm" >
                <label class="col-sm-3 control-label">نوع التقرير</label>
                <div class="col-sm-8">
                    <input type="radio" name="rep_types4" id="rep_types_14" value="1" checked>شامل الكمية المحجوزة
                    <input type="radio" name="rep_types4" id="rep_types_24" value="2" >غير شامل الكمية المحجوزة

                </div>

                </div>
            </div>
        </form>
    <div style="clear: both"></div>
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
var val3= $('input:radio[name=parchaces1]:checked').val();
var val4= $('input:radio[name=ammount]:checked').val();
var val5= $('input:radio[name=rep_types4]:checked').val();
$(document).ready(function(){
   $("#class_amount_form").hide();
   $("#class_amount_form1").hide();
   $("#class_amount_form2").hide();
});
reBind();

$('input:radio[name=parchaces]').click(function() {
   val= $('input:radio[name=parchaces]:checked').val();

});
$('input:radio[name=rep_types]').click(function() {
   val2= $('input:radio[name=rep_types]:checked').val();

});
$('input:radio[name=parchaces1]').click(function() {
   val3= $('input:radio[name=parchaces1]:checked').val();

});
$('input:radio[name=ammount]').click(function() {
   val4= $('input:radio[name=ammount]:checked').val();

});
$('input:radio[name=rep_types4]').click(function() {
   val5= $('input:radio[name=rep_types4]:checked').val();

});
    //$('#dp_store_id').select2();
    $("input[id^='dp_store_id']").select2();

    $("input[id^='txt_class_id']").click("focus",function(e){
        _showReport('$select_items_url/'+$(this).attr('id'));
    });
     $('#dp_request_side').change(function(){
        $('#txt_class_output_account_id').val('');
        $('#h_txt_class_output_account_id').val('');
    });
 $('#txt_class_output_account_id').click(function(e){
var _type = $('#dp_request_side').val();
        if(_type == 1)
            _showReport('$select_accounts_url/'+$(this).attr('id') );
        else if(_type == 2)
            _showReport('$customer_url/'+$(this).attr('id')+'/1');

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
        clearForm($('#{$TB_NAME}_form1'));
        clearForm($('#{$TB_NAME}_form2'));
        //$('#dp_store_id').select2('val',0);
          $("input[id^='dp_store_id']").select2('val',0);
        $("#parchaces_1").prop("checked", true);
        $("#parchaces_4").prop("checked", true);
        $("#rep_types_1").prop("checked", true);
        $("#ammount_1").prop("checked", true);
        $("#rep_types_14").prop("checked", true);
    }

    $('#print_class_amount_rep').click(function(){
        _showReport("$print_url?report=CLASS_AMOUNT&params[]="+$('#h_txt_class_id').val()+"&params[]="+$('#h_txt_class_id2').val()+"&params[]="+$('#dp_store_id').val());
    });
    $('#print_class_prcie_rep').click(function(){
        _showReport("$report_url2"+"&report_type=pdf&report=CLASS_PRCIE_MOVMENT&p_class_id="+$('#h_txt_class_id').val());
    });
   function account_create()
   {
   $("#a1").click(function(){
  $("#a1").data('clicked', true);
      $("#a2").data('clicked', false);
       $("#a3").data('clicked', false);
    $("#parchaces_1").prop("checked", true);
      $("#rep_types_1").prop("checked", true);
      $("#class_amount_form").show();
      $("#class_amount_form1").hide();
      $("#class_amount_form2").hide();
});
 $("#a2").click(function(){
      clearForm($('#{$TB_NAME}_form'));
      $("#a1").data('clicked', false);
      $("#a2").data('clicked', true);
       $("#a3").data('clicked', false);
      $("#class_amount_form1").show();
      $("#class_amount_form").hide();
      $("#class_amount_form2").hide();
});
$("#a3").click(function(){
      clearForm($('#{$TB_NAME}_form'));
      $("#a3").data('clicked', true);
      $("#a2").data('clicked', false);
       $("#a1").data('clicked', false);
      $("#class_amount_form2").show();
      $("#class_amount_form1").hide();
      $("#class_amount_form").hide();
});

   }
   function reBind(){
        $('input[id^="h_txt_class_id"]').change(function(e) {
        var id_v=$(this).val();

  var name=$(this).closest('div').find('input[name^="txt_class_id"]');

             get_data('{$get_class_url}',{id:id_v, type:1},function(data){
                if (data.length == 1){
                    var item= data[0];
                    name.val(item.CLASS_NAME_AR);


          }else{
                    name.val('');


                }
         });
           });
     $('input[id^="h_txt_class_id"]').bind('keyup', '+', function(e) {
   $(this).val( $(this).val().replace('+', ''));
    var id_v=$(this).closest('div').find('input[id^="txt_class_id"]').attr('id');
  _showReport('$select_items_url/'+id_v);
           });
           }
function showReport(type){
 var url = '$print_url';
 if($('#a1').data('clicked')) {

if (val2==1)
{
if(val==1)
{
if ($('#dp_store_id1').val()!=$('#dp_store_id2').val() && $('#dp_store_id2').val()!='')
{
alert('here');
}
else
{
var url = '$report_url';
url  = url +'&report_type=pdf&report=ITEMS_TOTAL_REP_PRICE_PARCHAISE&p_from_date='+$('#txt_date1').val()+'&p_to_date='+$('#txt_date2').val()+'&p_from_class_id='+$('#h_txt_class_id').val()+
'&p_to_class_id='+$('#h_txt_class_id2').val()+'&p_class_type='+$('#dp_class_type').val()+'&p_class_acount_type='+
 $('#dp_account_type').val()+'&p_from_store='+$('#dp_store_id1').val();
}
}
if(val==2)
{
if ($('#dp_store_id1').val()!=$('#dp_store_id2').val() && $('#dp_store_id2').val()!='')
{
alert('here');
}
else
{
var url = '$report_url';
url  = url +'&report_type=pdf&report=ITEMS_TOTAL_REP_PRICE_SALE&p_from_date='+$('#txt_date1').val()+'&p_to_date='+$('#txt_date2').val()+'&p_from_class_id='+$('#h_txt_class_id').val()+
'&p_to_class_id='+$('#h_txt_class_id2').val()+'&p_class_type='+$('#dp_class_type').val()+'&p_class_acount_type='+
 $('#dp_account_type').val()+'&p_from_store='+$('#dp_store_id1').val();
}
}
if(val==3)
{
if ($('#dp_store_id1').val()!=$('#dp_store_id2').val()  && $('#dp_store_id2').val()!='')
{
alert('here');
}
else
{
var url = '$report_url';
url  = url +'&report_type=pdf&report=ITEMS_TOTAL_REP&p_from_date='+$('#txt_date1').val()+'&p_to_date='+$('#txt_date2').val()+'&p_from_class_id='+$('#h_txt_class_id').val()+
'&p_to_class_id='+$('#h_txt_class_id2').val()+'&p_class_type='+$('#dp_class_type').val()+'&p_class_acount_type='+
 $('#dp_account_type').val()+'&p_from_store='+$('#dp_store_id1').val();
}
}
}
else if (val2==2)
{
if(val==1)
{
if ($('#dp_store_id1').val()!=$('#dp_store_id2').val() && $('#dp_store_id2').val()!='')
{
alert('here');
}
else
{
url  = url +'?type='+type+'&report=ITEMS_TOTAL_REP_WITHOUT_RES_PARCHAISE&params[]='+$('#txt_date1').val()+'&params[]='+$('#txt_date2').val()+'&params[]='+$('#h_txt_class_id').val()+
'&params[]='+$('#h_txt_class_id2').val()+'&params[]='+$('#dp_class_type').val()+'&params[]='+
 $('#dp_account_type').val()+'&params[]=&params[]=&params[]=&params[]='+$('#dp_store_id1').val();
}
}
if(val==2)
{
if ($('#dp_store_id1').val()!=$('#dp_store_id2').val() && $('#dp_store_id2').val()!='')
{
alert('here');
}
else
{
url  = url +'?type='+type+'&report=ITEMS_TOTAL_REP_WITHOUT_RES_SALE&params[]='+$('#txt_date1').val()+'&params[]='+$('#txt_date2').val()+'&params[]='+$('#h_txt_class_id').val()+
'&params[]='+$('#h_txt_class_id2').val()+'&params[]='+$('#dp_class_type').val()+'&params[]='+
 $('#dp_account_type').val()+'&params[]=&params[]=&params[]=&params[]='+$('#dp_store_id1').val();
}
}
if(val==3)
{
if ($('#dp_store_id1').val()!=$('#dp_store_id2').val() && $('#dp_store_id2').val()!='')
{
alert('here');
}
else
{
url  = url +'?type='+type+'&report=ITEMS_TOTAL_REP_WITHOUT_RES&params[]='+$('#txt_date1').val()+'&params[]='+$('#txt_date2').val()+'&params[]='+$('#h_txt_class_id').val()+
'&params[]='+$('#h_txt_class_id2').val()+'&params[]='+$('#dp_class_type').val()+'&params[]='+
 $('#dp_account_type').val()+'&params[]=&params[]=&params[]=&params[]='+$('#dp_store_id1').val();
}
}
}
}
else if($('#a2').data('clicked'))
{
if (val3==1)
{
if(val4==1)
{

url  = url +'?type='+type+'&report=account_AMOUNT_REP_PARCHES_SADER&params[]='+$('#h_txt_class_id1').val()+'&params[]='+$('#h_txt_class_id22').val()+'&params[]='+$('#txt_date11').val()+
'&params[]='+$('#txt_date22').val()+'&params[]='+$('#dp_class_type1').val()+'&params[]='+$('#h_txt_class_output_account_id').val();

//alert(url);
}
if(val4==2)
{
url  = url +'?type='+type+'&report=account_AMOUNT_REP_PARCHES_WARED&params[]='+$('#h_txt_class_id1').val()+'&params[]='+$('#h_txt_class_id22').val()+'&params[]='+$('#txt_date11').val()+
'&params[]='+$('#txt_date22').val()+'&params[]='+$('#dp_class_type1').val()+'&params[]='+
 $('#h_txt_class_output_account_id').val();
}
if(val4==3)
{
url  = url +'?type='+type+'&report=account_AMOUNT_REP_PARCHES&params[]='+$('#h_txt_class_id1').val()+'&params[]='+$('#h_txt_class_id22').val()+'&params[]='+$('#txt_date11').val()+
'&params[]='+$('#txt_date22').val()+'&params[]='+$('#dp_class_type1').val()+'&params[]='+
 $('#h_txt_class_output_account_id').val();
}
}
else if (val3==2)
{
if(val4==1)
{
url  = url +'?type='+type+'&report=account_AMOUNT_REP_SAL_SADER&params[]='+$('#h_txt_class_id1').val()+'&params[]='+$('#h_txt_class_id22').val()+'&params[]='+$('#txt_date11').val()+
'&params[]='+$('#txt_date22').val()+'&params[]='+$('#dp_class_type1').val()+'&params[]='+
 $('#h_txt_class_output_account_id').val();
}
if(val4==2)
{
url  = url +'?type='+type+'&report=account_AMOUNT_REP_SAL_WARED&params[]='+$('#h_txt_class_id1').val()+'&params[]='+$('#h_txt_class_id22').val()+'&params[]='+$('#txt_date11').val()+
'&params[]='+$('#txt_date22').val()+'&params[]='+$('#dp_class_type1').val()+'&params[]='+
 $('#h_txt_class_output_account_id').val();
}
if(val4==3)
{
url  = url +'?type='+type+'&report=account_AMOUNT_REP_SAL&params[]='+$('#h_txt_class_id1').val()+'&params[]='+$('#h_txt_class_id22').val()+'&params[]='+$('#txt_date11').val()+
'&params[]='+$('#txt_date22').val()+'&params[]='+$('#dp_class_type1').val()+'&params[]='+
 $('#h_txt_class_output_account_id').val();
}
}
}
else if($('#a3').data('clicked'))
{

if (val5==1)
{
url  = url +'?type='+type+'&report=min_STORE_AMOUNT_REP&params[]='+$('#txt_date14').val()+'&params[]='+$('#txt_date24').val()+'&params[]='+$('#h_txt_class_id3').val()+
'&params[]='+$('#h_txt_class_id4').val()+'&params[]='+$('#dp_store_id').val()+'&params[]='+
 $('#dp_class_type4').val();
}
else if (val5==2)
{
url  = url +'?type='+type+'&report=min_STORE_AMOUNT_REP_NO_RES&params[]='+$('#txt_date14').val()+'&params[]='+$('#txt_date24').val()+'&params[]='+$('#h_txt_class_id3').val()+
'&params[]='+$('#h_txt_class_id4').val()+'&params[]='+$('#dp_store_id').val()+'&params[]='+
 $('#dp_class_type4').val();
}
}

_showReport(url);
}
</script>

SCRIPT;

sec_scripts($scripts);

?>
