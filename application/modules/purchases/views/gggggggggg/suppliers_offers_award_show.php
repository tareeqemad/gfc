<?php
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 23/03/15
 * Time: 10:11 ص
 */
$MODULE_NAME= 'purchases';
$TB_NAME1= 'purchase_order';
$TB_NAME= 'suppliers_offers';

$print_url =  base_url('/reports');
$get_purchase_url= base_url("$MODULE_NAME/$TB_NAME1/get");

/*mtaqia*/
$report_url = base_url("JsperReport/showreport?sys=purchases");
$report_sn= report_sn();
/* ----- */

$rs= $purchase_data[0]; echo $rs['IS_ASSISTENT_MANAGER']."jhjj";
$curr_id=isset($rs['CURR_ID'])? $rs['CURR_ID'] :0;
$curr_id_name=isset($rs['CURR_ID_NAME'])? $rs['CURR_ID_NAME'] :'';
$purchase_order_id=isset($rs['PURCHASE_ORDER_ID'])? $rs['PURCHASE_ORDER_ID'] :'';
$order_purpose=isset($rs['ORDER_PURPOSE'])? $rs['ORDER_PURPOSE'] :'';
$committee_case=isset($rs['COMMITTEE_CASE'])? $rs['COMMITTEE_CASE'] :0;
$purchase_notes=isset($rs['PURCHASE_NOTES'])? $rs['PURCHASE_NOTES'] :'';
$purchase_type_name=isset($rs['PURCHASE_TYPE_NAME'])? $rs['PURCHASE_TYPE_NAME'] :'';
$purchase_order_num=isset($rs['PURCHASE_ORDER_NUM'])? $rs['PURCHASE_ORDER_NUM'] :'';
$cancel_decigion=isset($rs['CANCEL_DECIGION'])? $rs['CANCEL_DECIGION'] :0;
$back_url=base_url("$MODULE_NAME/purchase_order/index/1/1/4?order_purpose=$order_purpose");
$print_url = 'http://itdev:801/gfc.aspx?data='.get_report_folder().'&' ;
$submit_url=base_url("$MODULE_NAME/$TB_NAME1/edit_award_$order_purpose");
$adoptc6_url=base_url("$MODULE_NAME/$TB_NAME1/committee_adoptc6_$order_purpose");
$adopt6_url=base_url("$MODULE_NAME/$TB_NAME1/committee_adopt6_$order_purpose");
$adopt5_url=base_url("$MODULE_NAME/$TB_NAME1/committee_adopt5_$order_purpose");
$adopt4_url=base_url("$MODULE_NAME/$TB_NAME1/committee_adopt4_$order_purpose");
$get_award_url=base_url("$MODULE_NAME/$TB_NAME/award$order_purpose");
if($order_purpose==1)
    $DET_TB_NAME= 'public_get_award_details';
elseif($order_purpose==2)
    $DET_TB_NAME= 'public_get_award_items';
else
    die('show');
?>
    <div class="row">
        <div class="toolbar">

            <div class="caption"><?= $title ?></div>

            <ul>
                <?php //HaveAccess($back_url)
                if( TRUE):  ?><li><a  href="<?= $back_url ?>"><i class="icon icon-reply"></i> </a> </li><?php  endif; ?>
                <!--  <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li> -->
            </ul>

        </div>
    </div>
    <div class="form-body">

        <div id="msg_container"></div>
        <div id="container">
            <form class="form-vertical" id="<?=$TB_NAME?>_form" method="post" action="<?=$submit_url?>" role="form" novalidate="novalidate">
                <div class="modal-body inline_form">
                    <fieldset  >
                        <legend>  بيانات طلب الشراء </legend>

                        <div class="form-group col-sm-1">
                            <label class="control-label">رقم المسلسل </label>
                            <div>
                                <input type="text" readonly  value="<?=$purchase_order_id?>"  name="purchase_order_id"  id="txt_purchase_order_id" class="form-control" />
                                <input type="hidden" name="order_purpose" value="<?=$order_purpose?>"  id="dp_order_purpose">
                            </div>
                        </div>
                        <div class="form-group col-sm-1">
                            <label class="control-label">رقم طلب الشراء </label>
                            <div>
                                <input type="text" readonly  value="<?=$purchase_order_num?>"  name="purchase_order_num"  id="txt_purchase_order_num" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group col-sm-1">
                            <label class="control-label"> عملة عرض السعر  </label>
                            <div >
                                <input type="hidden" name="curr_id" value="<?=$curr_id?>"  id="dp_curr_id">
                                <input type="text" readonly value="<?=$curr_id_name?>"  name="curr_id_name"  id="txt_curr_id_name" class="form-control" />

                            </div>
                        </div>
                        <div class="form-group col-sm-1">
                            <label class="control-label">نوع الطلب</label>
                            <div >
                                <input type="text" readonly value="<?=$purchase_type_name?>"  name="purchase_type_name"  id="txt_purchase_type_name" class="form-control" />

                            </div>
                        </div>
                        <div class="form-group col-sm-9">
                            <label class="control-label">بيان الطلب</label>
                            <div >

                                <input type="text" readonly value="<?=$purchase_notes?>"  name="purchase_notes"  id="txt_purchase_notes" class="form-control" />
                            </div>
                        </div>
                        <hr>
                        <div style="clear: both;">
                             <?php echo   modules::run('attachments/attachment/index',$purchase_order_id,'purchase_order') ; ?>
                        </div>
                    </fieldset><hr/>
                    <fieldset  >
                        <legend> كشوف التفريغ  </legend>
                        <div style="width:1200px;clear: both;overflow: auto" class="div_width" id="classes" > <!-- <input type="hidden" id="h_data_search" />-->
                            <?php
                            echo  modules::run("$MODULE_NAME/$TB_NAME/$DET_TB_NAME", $purchase_order_id ); ?>

                        </div>
                    </fieldset>
                    <hr/>
                    <fieldset  >
                        <legend>بيانات الأعضاء</legend>
                        <div style="clear: both" id="classes"> <!-- <input type="hidden" id="h_data_search" />-->
                            <div style="..."   >
                                <?php
                                echo modules::run("$MODULE_NAME/purchase_order/public_get_group_receipt",$purchase_order_id);
                                // echo modules::run('stores/receipt_class_input/public_get_group_details',0);
                                ?>

                            </div>
                        </div>
                    </fieldset>
                </div>
                <hr/>

                <div class="modal-footer">

                    <?php
                      if(( HaveAccess($submit_url)) and  ($committee_case==4 ) )
                    echo "<button type='submit' id='sub'  data-action='submit' class='btn btn-primary'>حفظ البيانات</button>";
                     if(HaveAccess($adopt5_url) and  ($committee_case==4 ) )
                    echo " <button type='button' id='recordd' onclick='javascript:adopt5();' class='btn btn-primary' data-dismiss='modal'>اعتماد</button>";
                    if(HaveAccess($adopt4_url) and   ($committee_case==5  ))
                    echo " <button type='button' id='recordd2' onclick='javascript:adopt4();' class='btn btn-primary' data-dismiss='modal'>إلغاء اعتماد</button>";
                    if(HaveAccess($adopt6_url) and   ($committee_case==5  ) and ($rs['IS_ASSISTENT_MANAGER']==1))
                        echo " <button type='button' id='recordd2' onclick='javascript:adopt6();' class='btn btn-primary' data-dismiss='modal'>اعتماد المدير العام</button>";
                    if(HaveAccess($adoptc6_url) and   ($committee_case==6  ) and ($rs['IS_ASSISTENT_MANAGER']==1))
                        echo " <button type='button' id='recordd2' onclick='javascript:adoptc6();' class='btn btn-primary' data-dismiss='modal'>إلغاء اعتماد المدير العام</button>";

                    ?>
                <!--<button type="button" id="print_rep" onclick="javascript:print_rep();" class="btn btn-success"> <i class="glyphicon glyphicon-print"></i> طباعة </button>  -->
                    <button type="button" id="print_rep_jasper" class="btn btn-success"> <i class="glyphicon glyphicon-print"></i> طباعة </button>

                    <button class="btn btn-warning dropdown-toggle" onclick="$('#suppliers_offers_detTbl').tableExport({type:'excel',escape:'false'});" data-toggle="dropdown"><i class="fa fa-bars"></i> تصدير</button>
                    <button class="btn btn-warning dropdown-toggle" onclick="$('#receipt_class_input_groupTbl').tableExport({type:'excel',escape:'false'});" data-toggle="dropdown"><i class="fa fa-bars"></i> تصدير بيانات الأعضاء</button>

                </div>
            </form>
        </div>
    </div>

<?php

$shared_js = <<<SCRIPT
// mtaqia
 $('#print_rep_jasper').click(function(){
    _showReport('{$report_url}&type=pdf&report=Purchase_Decide_Committee&p_purchase_order_id={$purchase_order_id}&sn={$report_sn}',true);
    });

 //$('#print_rep').click(function(){
 //       _showReport('$print_url'+'report=purchases/PURCHASE_ORDER_REP_INFO1&params[]={$purchase_order_id}');
 //   });

var count1 = 0;
   count1 = $('input[name="h_group_ser[]"]').length;

function addRowGroup(){
//if($('input:text',$('#receipt_class_input_groupTbl')).filter(function() { return this.value == ""; }).length <= 0){


    var html ='<tr><td >'+( count1+1)+' <input type="hidden" value="0" name="h_group_ser[]" id="h_group_ser_'+count1+'>"  class="form-control col-sm-3"> </td>'+
    '<td><input type="text" name="emp_no[]" data-val="true"  id="emp_no_'+count1+'"  class="form-control col-sm-8"> </td>'+
     '<td> <input type="text" name="group_person_id[]" data-val="true"   id="group_person_id_'+count1+'"  class="form-control col-sm-8">  </td>'+
      '<td><input type="text" name="group_person_date[]"  data-val="true"   id="group_person_date_'+count1+'>"   class="form-control">  </td>'+
    '<td><input type="text" name="member_note[]"  data-val="true"   id="member_note_'+count1+'>"   class="form-control">  </td>'+
     '<td><input type="checkbox" name="status['+count1+']" checked      id="status_'+count1+'"   class="form-control"></td></tr>';
    $('#receipt_class_input_groupTbl tbody').append(html);
      count1 = count1+1;
//}
}

$(document).ready(function() {

    var c_w= $(window).width() - 80 ;
    $('div.div_width').css('width',c_w+'px');



});

$('.award_decision').change(function(){
 var a=$(this).closest('tr').find('.balance');
  var t=$(this).val();

if (t==2 || t==0){
 a.each(function(i,j){
j.value=0;
    });
    }
});

$('input[name^="suppliers_discount"]').change(function(){
var y=$(this).val();
var x=$(this).closest('th').find('input[name="suppliers_offers_id[]"]').val();
var m=$('input[name^="class_discount['+x+']"]');
  m.each(function(i,j){
 j.value=y;
calc_discount($(j));
    });
     totals('total_'+x);

});

$('input[name^="discount_value"]').change(function(){
var y=$(this).val();
var x=$(this).closest('th').find('input[name="suppliers_offers_ids[]"]').val();
var m=$('input[name^="c_discount_value['+x+']"]');
  m.each(function(i,j){
 j.value=y;
calc_discount_value($(j));
    });
     totals('total_'+x);

});

$('input[name^="class_discount"]').change(function(){
var sumapproved=$(this).closest('td').next().next().next().attr('class');
 calc_discount($(this));
  totals(sumapproved);
});
$('input[name^="c_discount_value"]').change(function(){
 calc_discount_value($(this));
var sumapproved=$(this).closest('td').next().next().attr('class');
  totals(sumapproved);
});

$('input[name^="approved_price"]').change(function(){
var sumapproved=$(this).closest('td').next().attr('class');
 calc_discount_value($(this).closest('td').prev().find('input'));
 totals(sumapproved);

});
function  calc_discount(obj){
//alert('a');
var x=parseFloat(obj.val());
if (x>100){
obj.val('0');
x=0;
}
var approved_price=parseFloat(obj.closest('td').next().next().find('input').val());
var count=parseFloat(obj.closest('td').prev().prev().prev().text());
var dis=(x*count*approved_price)/100;
if (isNaN(dis))  dis=0;
var valu=(count*approved_price)-dis;
if (isNaN(valu))  valu=0;
obj.closest('td').next().find('input').val(parseFloat(dis,2));
obj.closest('td').next().next().next().text(parseFloat(valu));

}
function  calc_discount_value(obj){
//alert('b');
var x=parseFloat(obj.val());
var approved_price=parseFloat(obj.closest('td').next().find('input').val());
var count=parseFloat(obj.closest('td').prev().prev().prev().prev().text());
var dis_p=parseFloat((x*100)/(count*approved_price)).toFixed(5);
if (isNaN(dis_p))  dis_p=0;
if (dis_p>100){
obj.val('0');
x=0;
dis_p=0;
}
var valu=(count*approved_price)-x;
if (isNaN(valu))  valu=0;
obj.closest('td').prev().find('input').val(parseFloat(dis_p));
obj.closest('td').next().next().text(parseFloat(valu,2));

}
/*
function calc_totals(){
var m=$('td[class^="total_"]');
console.log(m);
var c;
  m.each(function(i,j){

c=j;
    });
    alert($('#counter').val());
    for (var x=1;x<=$('#counter').val();x++){
  console.log('ggg',$(m[x]).attr('class'));
$('#sum'+$(m[x]).attr('class')).text(totals($(m[x]).attr('class')));}
}
*/
function totals(totaln){

    var total = 0;
    $('#suppliers_offers_detTbl tbody .'+totaln).each(function() {
        total += + parseFloat($(this).text().replace(/,/g , ''));
    });
    // alert(total);
 //  alert($('th[class^="sum'+totaln+'"]').text()) ; //($('#sum'+totaln).text());
//  $('#sum'+totaln).text(num_format(total));
 $('#sum'+totaln).text(total);
//return total ;
}




$('.balance').bind('keydown', 'space', function() {
    var thiss=$(this);
    var tr=$(this).closest('tr');

    var a=tr.find('.balance');
    var p=tr.find('input[name="purchase_amount[]"]').val();
    var t=tr.find('.award_decision').val();
    var x;

    var m=$(this).closest('td').prev().prev().prev().prev().prev().prev().text();

    var sum=0;
    a.each(function(i,j){

        if (t==2 || t==0){
            j.value=0;
        }else{
            sum=sum+parseFloat($(this).val());

        }
    });
    sum=parseFloat(sum)- thiss.val();
   v=parseFloat(p)-parseFloat(sum);
   // alert(v);
    if (parseFloat(m)<=parseFloat(v))
    thiss.val(m);
    else
    thiss.val(v);

  return false;
});

$('.balance').change(function(){
    var a=$(this).closest('tr').find('.balance');
    var p=$(this).closest('tr').find('input[name="purchase_amount[]"]').val();
    var t=$(this).closest('tr').find('.award_decision').val();

    var sum=0;
    a.each(function(i,j){
if (t==2 || t==0)
j.value=0;
else
 sum=sum+parseFloat($(this).val());

    });

    if (sum>p){
    $('#sub').prop('disabled',true);
     danger_msg('تحذير..','يجب أن يكون مجموع كميات الترسية أقل او يساوي كمية طلب الشراء');
    }
    else {
    $('#sub').prop('disabled',false);
    }
});

function calc_sum_approved_amount(){}
$('button[data-action="submit"]').click(function(e){
    e.preventDefault();
    var tbl = '#{$TB_NAME}_tb';
    var container = $('#' + $(tbl).attr('data-container'));
    var form = $(this).closest('form');
 $(form).attr('action','{$submit_url}');
    ajax_insert_update(form,function(data){
    if(data==1){
            success_msg('رسالة','تم حفظ البيانات بنجاح ..');
            get_to_link(window.location.href);
        }else{
            danger_msg('تحذير..',data);
        }
    },"html");
});


function adopt4(){

    if(confirm('هل تريد إتمام العملية ؟')){

    get_data('{$adopt4_url}',{id:{$purchase_order_id}},function(data){
      if(data =='1')
      console.log(data);
                      success_msg('رسالة','تمت العملية بنجاح');
                      setTimeout(function(){

                  window.location.reload();

                }, 1000);
            },'html');


}


}

function adopt5(){

    if(confirm('هل تريد إتمام العملية ؟')){


    get_data('{$adopt5_url}',{id:{$purchase_order_id}},function(data){
      if(data =='1')
      console.log(data);
                      success_msg('رسالة','تمت العملية بنجاح');
 /////////////////////

  var sub= 'اعتماد لجنة البت والترسية لطلب شراء {$purchase_order_id}';
                        var text= 'تم اعتماد لجنة البت والترسية لطلب شراء رقم {$rs['PURCHASE_ORDER_ID']}';
                        text+= '<br>للاطلاع افتح الرابط';
                        text+= '<br>http://gs{$get_purchase_url}/{$purchase_order_id}';
                        text+= '<br>http://gs{$get_award_url}/{$purchase_order_id}';
                        _send_mail('{$purchase_emails}',sub,text);
////////////////////
////////////////////////
if('{$cancel_decigion}' != '0'){
  var sub= 'إلغاء أصناف من قبل لجنة البت والترسية لطلب شراء {$purchase_order_id} ';
                        var text= 'تم اعتماد لجنة البت والترسية لطلب شراء رقم {$rs['PURCHASE_ORDER_ID']}';
                         text+= '<br>وتم إلغاء طلب أصناف بهذا الطلب';
                        text+= '<br>للاطلاع افتح الرابط';
                        text+= '<br>http://gs{$get_purchase_url}/{$purchase_order_id}';
                        text+= '<br>http://gs{$get_award_url}/{$purchase_order_id}';
                        _send_mail('{$purchase_cancel_emails}',sub,text);

}
/////////////////////////////////
                      setTimeout(function(){

                window.location.reload();

                }, 1000);
            },'html');


}


}

function adopt6(){

    if(confirm('هل تريد إتمام العملية ؟')){


    get_data('{$adopt6_url}',{id:{$purchase_order_id}},function(data){
      if(data =='1')
      console.log(data);
                      success_msg('رسالة','تمت العملية بنجاح');
                      setTimeout(function(){

                window.location.reload();

                }, 1000);
            },'html');


}


}

function adoptc6(){

    if(confirm('هل تريد إتمام العملية ؟')){


    get_data('{$adoptc6_url}',{id:{$purchase_order_id}},function(data){
      if(data =='1')
      console.log(data);
                      success_msg('رسالة','تمت العملية بنجاح');
                      setTimeout(function(){

                window.location.reload();

                }, 1000);
            },'html');


}


}
SCRIPT;

$edit_script = <<<SCRIPT


<script>
  {$shared_js}
  </script>
SCRIPT;
sec_scripts($edit_script);