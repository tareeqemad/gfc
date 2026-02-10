<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 11/03/15
 * Time: 10:12 ص
 */
$MODULE_NAME= 'purchases';
$TB_NAME= 'suppliers_offers';

$get_url =base_url("$MODULE_NAME/$TB_NAME/get");
$back_url=base_url("$MODULE_NAME/$TB_NAME/index");
$print_url=  'https://itdev.gedco.ps/gfc.aspx?data='.get_report_folder().'&' ;
$purchase_order_url=base_url("$MODULE_NAME/purchase_order/public_get_purchase_order");
$select_order_url = base_url("$MODULE_NAME/purchase_order/public_index");

$customer_url =base_url('payment/customers/public_index');
//$select_items_url=base_url("stores/classes/public_index");
//$get_class_url =base_url('stores/classes/public_get_id');
$envelopement_view_url=base_url("$MODULE_NAME/suppliers_offers/public_envelopment");
$curr_val_url= base_url("$MODULE_NAME/suppliers_offers/public_getCurVal");

$edit_url =base_url("$MODULE_NAME/$TB_NAME/get");

$adopt1_url=base_url("$MODULE_NAME/$TB_NAME/adopt1_$order_purpose");
$adopt2_url=base_url("$MODULE_NAME/$TB_NAME/adopt2_$order_purpose");

if($order_purpose==1)
    $DET_TB_NAME= 'public_get_details';
elseif($order_purpose==2)
    $DET_TB_NAME= 'public_get_det_items';
else
    die('show');

$details_url =base_url("$MODULE_NAME/$TB_NAME/$DET_TB_NAME");

if ($action=='index') {
    $user_in_date='';
    $suppliers_offers_id='';
    $suppliers_offers_id_val=0;
    $curr_id=0;
    $curr_id_name=isset($purchase_data['CURR_ID_NAME'])? $purchase_data['CURR_ID_NAME'] :'';
    $purchase_order_id=isset($purchase_order_id)? $purchase_order_id :'';;
    $entry_date='';
    $customer_id='';
    $customer_name='';
    $customer_curr_id=0;
     $offer_notes='';
    $adopt=1;
    $curr_value=1;
    $offers_case=1;
    $offers_note='';
    $style="display: none;";
    $purchase_notes=isset($purchase_data['PURCHASE_NOTES'])? $purchase_data['PURCHASE_NOTES'] :'';
    $quote_condition=isset($purchase_data['QUOTE_CONDITION'])? $purchase_data['QUOTE_CONDITION'] :'';
    $purchase_type_name=isset($purchase_data['PURCHASE_TYPE_NAME'])? $purchase_data['PURCHASE_TYPE_NAME'] :'';
    $purchase_order_num=isset($purchase_data['PURCHASE_ORDER_NUM'])? $purchase_data['PURCHASE_ORDER_NUM'] :'';;
    $next_suppliers_offers_id=0;
    $prev_suppliers_offers_id=0;
}else{
    $rs= $orders_data[0];
    $user_in_date=isset($rs['USER_IN_DATE'])? $rs['USER_IN_DATE'] :'';
    $suppliers_offers_id=isset($rs['SUPPLIERS_OFFERS_ID'])? $rs['SUPPLIERS_OFFERS_ID'] :'';
    $suppliers_offers_id_val=isset($rs['SUPPLIERS_OFFERS_ID'])? $rs['SUPPLIERS_OFFERS_ID'] :0;
    $curr_id=isset($rs['CURR_ID'])? $rs['CURR_ID'] :0;
    $curr_id_name=isset($rs['CURR_ID_NAME'])? $rs['CURR_ID_NAME'] :'';
    $purchase_order_id=isset($rs['PURCHASE_ORDER_ID'])? $rs['PURCHASE_ORDER_ID'] :'';
    $entry_date=isset($rs['ENTRY_DATE'])? $rs['ENTRY_DATE'] :'';
    $customer_id =isset($rs['CUSTOMER_ID'])? $rs['CUSTOMER_ID'] :'';
    $customer_name =isset($rs['CUST_NAME'])? $rs['CUST_NAME'] :'';
    $customer_curr_id=isset($rs['CUSTOMER_CURR_ID'])? $rs['CUSTOMER_CURR_ID'] :'';
    $offer_notes=isset($rs['OFFER_NOTES'])? $rs['OFFER_NOTES'] :'';
    $adopt=isset($rs['OFFER_CASE'])? $rs['OFFER_CASE'] :1;
    $curr_value=isset($rs['CURR_VALUE'])? $rs['CURR_VALUE'] :1;
    $offers_case=isset($rs['OFFERS_CASE'])? $rs['OFFERS_CASE'] :1;
    $offers_note=isset($rs['OFFERS_NOTE'])? $rs['OFFERS_NOTE'] :'';
    $purchase_notes=isset($rs['PURCHASE_NOTES'])? $rs['PURCHASE_NOTES'] :'';
    $quote_condition=isset($rs['QUOTE_CONDITION'])? $rs['QUOTE_CONDITION'] :'';
    $purchase_type_name=isset($rs['PURCHASE_TYPE_NAME'])? $rs['PURCHASE_TYPE_NAME'] :'';
    $purchase_order_num=isset($rs['PURCHASE_ORDER_NUM'])? $rs['PURCHASE_ORDER_NUM'] :'';
    $next_suppliers_offers_id=isset($rs['NEXT_SUPPLIERS_OFFERS_ID'])? $rs['NEXT_SUPPLIERS_OFFERS_ID'] :0;
    $prev_suppliers_offers_id=isset($rs['PREV_SUPPLIERS_OFFERS_ID'])? $rs['PREV_SUPPLIERS_OFFERS_ID'] :0;
    $style=($offers_case==1)? "display: none;" : " ";
}

$post_url= base_url("$MODULE_NAME/$TB_NAME/".($action == 'index' ? 'create?order_purpose='.$order_purpose:$action));
$create_url =base_url("$MODULE_NAME/$TB_NAME/create?order_purpose=".$order_purpose);
$create2_url =base_url("$MODULE_NAME/$TB_NAME/create");

$create1_url =base_url("$MODULE_NAME/$TB_NAME/create/$purchase_order_id?order_purpose=".$order_purpose);
echo AntiForgeryToken();
?>


<div class="row">
    <div class="toolbar">

        <div class="caption"><?=$title?></div>
        <ul>
            <?php
            if (HaveAccess($create_url)):  ?><li><a  href="#" onclick="suppliers_offers_new();"><i class="glyphicon glyphicon-plus">جديد</i> </a> </li><?php  endif; ?>

            <?php //HaveAccess($back_url)
            if( TRUE):  ?><li><a  href="<?= $back_url ?>?order_purpose=<?=$order_purpose?>"><i class="icon icon-reply"></i> </a> </li><?php  endif; ?>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
        </ul>


    </div>
</div>
<div class="form-body">

    <div id="msg_container"></div>
    <div id="container">
        <form class="form-vertical" id="<?=$TB_NAME?>_form" method="post" action="<?=$post_url?>" role="form" novalidate="novalidate">
            <div class="modal-body inline_form">
                <fieldset  >
                    <legend>  بيانات طلب الشراء </legend>

                    <div class="form-group col-sm-1">
                        <label class="control-label">رقم المسلسل </label>
                        <div>
                            <input type="text" <?php if ($action=='edit') echo "readonly";?>  value="<?=$purchase_order_id?>"  name="purchase_order_id"  id="txt_purchase_order_id" class="form-control" />
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
                    <div class="form-group col-sm-9">
                        <label class="control-label"> شروط عرض السعر </label>
                        <div>
                            <textarea rows="3" name="quote_condition" id="txt_quote_condition" class="form-control" ><?=$quote_condition ?></textarea>
                        </div>
                    </div>
                    <?php if ($action=='edit') { ?>
                    <div class="form-group col-sm-4">
                        <div class="col-sm-6">

                            <a target="_blank" href="<?php
                            echo $envelopement_view_url."/".$purchase_order_id; ?>">
                                <label class="col-sm-5 control-label"> عرض الكشوف</label></a>

                        </div>
                    </div>
                    <?php } ?>
                  </fieldset><hr/>
                <fieldset  >
                    <legend>  بيانات كشف التفريغ </legend>
                <div class="form-group col-sm-1">
                    <label class="control-label">رقم كشف التفريغ </label>
                    <div>
                        <input type="text" readonly value="<?=$suppliers_offers_id?>" name="suppliers_offers_id"  id="txt_suppliers_offers_id" class="form-control" />
                        <?php if (( isset($can_edit)?$can_edit:false)) : ?>
                            <input type="hidden" value="<?=$suppliers_offers_id?>"   name="suppliers_offers_id" id="h_suppliers_offers_id">
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">تاريخ الإدخال</label>
                    <div >
                        <input type="text" readonly value="<?=$entry_date?>" name="entry_date"    id="txt_entry_date" class="form-control ">

                    </div></div>

                    <div class="form-group col-sm-1">
                        <label class="control-label"> تاريخ السند  </label>
                        <div>

                            <input type="text" name="user_in_date" value="<?=$user_in_date?>" data-type="date" data-date-format="DD/MM/YYYY" data-val="true" id="txt_user_in_date" class="form-control" data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?=date_format_exp()?>"  data-val-required="حقل مطلوب" />
                            <span class="field-validation-valid" data-valmsg-for="user_in_date" data-valmsg-replace="true"></span>
                        </div>
                    </div>

                <div class="form-group col-sm-4">
                    <label class="control-label">المورد</label>
                    <div>
                        <input name="customer_name" data-val="true" readonly data-val-required="حقل مطلوب"   class="form-control"  id="txt_customer_name" value="<?=$customer_name?>"   >
                        <input type="hidden" name="customer_id" value="<?=$customer_id?>"  id="h_txt_customer_name">
                        <span class="field-validation-valid" data-valmsg-for="customer_name" data-valmsg-replace="true"></span>
                    </div></div>

                <div class="form-group col-sm-1">
                    <label class="control-label">  عملة المورد  </label>
                    <div >
                        <select  name="customer_curr_id" id="dp_customer_curr_id"  data-curr="false"  class="form-control">
                            <?php foreach($currency as $row) :?>
                                <option  <?php if ($customer_curr_id==$row['CURR_ID']) echo "selected"; ?> data-val="<?= $row['VAL'] ?>"  value="<?= $row['CURR_ID'] ?>"><?= $row['CURR_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>

                    </div>
                </div>
                    <div class="form-group col-sm-1">
                        <label class="control-label">حالة الكشف</label>
                        <div >
                            <select  name="offers_case" id="dp_offers_case"  data-curr="false"  class="form-control">
                                <option  <?php if ($offers_case==1) echo "selected"; ?>   value="1">جديد</option>
                                <option  <?php if ($offers_case==2) echo "selected"; ?>   value="2">إعادة</option>

                            </select>

                        </div>
                    </div>
                    <div class="form-group col-sm-6" id="offers_notes_div"  style="<?=$style?>" >
                        <label class="control-label">ملاحظات على حالة الكشف</label>
                        <div>
                            <input type="text" value="<?=$offers_note?>" name="offers_note" id="txt_offers_note" class="form-control" />
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">سعر العملة بالنسبة لعرض السعر</label>
                        <div >
                        <input type="text"  value="<?=$curr_value?>"  name="curr_value"  id="txt_curr_value" class="form-control" />

                        </div>
                    </div>
                <div class="form-group col-sm-6">
                    <label class="control-label">ملاحظات</label>
                    <div>
                        <textarea rows="3" name="offer_notes" id="txt_offer_notes" class="form-control" ><?=$offer_notes?></textarea>

                          </div>
                </div>
                </fieldset><hr/>

                <fieldset  >
                    <legend>  بيانات الأصناف </legend>
               <div style="clear: both" id="classes"> <!-- <input type="hidden" id="h_data_search" />-->
                    <?php

                 echo  modules::run("$MODULE_NAME/$TB_NAME/$DET_TB_NAME", $suppliers_offers_id_val, $purchase_order_id ); ?>

                </div>
                </fieldset><hr/>
            </div>

            <div class="modal-footer">
                <?php if (  ( HaveAccess($create_url)||HaveAccess($edit_url)) && ($adopt==1  )  ) : ?>
                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                <?php endif; ?>
                <?php if (  (HaveAccess($adopt2_url)) and ($action=='edit') && ($adopt==1  )  ) : ?>
                    <button type="button" onclick='javascript:adopt2();' class="btn btn-primary">اعتماد</button>
                <?php endif; ?>
                <?php if (  (HaveAccess($adopt1_url)) and ($action=='edit') && ($adopt==2  )  ) : ?>
                    <button type="button" onclick='javascript:adopt1();' class="btn btn-primary">إلغاء اعتماد</button>
                <?php endif; ?>
                <?php //if ( ( true) and ($action=='edit') && ($adopt==2  )  ) : ?>
                   <!-- <button type="button" class="btn btn-primary">تحويل لأمر توريد </button>-->
                <?php //endif; ?>
                <?php if ( $action=='edit' ) : ?>
                    <button type="button" id="print_rep" onclick="javascript:print_rep();" class="btn btn-success"> <i class="glyphicon glyphicon-print"></i> طباعة </button>
                <?php endif; ?>
                <?php if ($isCreate): ?>
                    <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>
                <?php   endif; ?>
                |

                <button type="button" <?php if ($next_suppliers_offers_id==0) echo "disabled";?> onclick="javascript:suppliers_offers_get_next();" class="btn btn-default"> التالي</button>
                <button type="button" <?php if ($prev_suppliers_offers_id==0) echo "disabled";?> onclick="javascript:suppliers_offers_get_prev();" class="btn btn-default"> السابق</button>

            </div>

        </form>
    </div>
</div>



<?php
$shared_js = <<<SCRIPT
 calcTotal();
var count = 0;



  count = $('input[name="h_class_id[]"]').length;


function reBind(){


$('input[name="customer_price[]"]').bind("change",function(e){
 calcTotal();

            });

$('input[name="curr_value"]').bind("change",function(e){
 calcTotal();
            });


  $('#dp_offers_case').change(function(){
  if ( $('#dp_offers_case').val()==1)
 $('#offers_notes_div').style.display="none";
 else
$('#offers_notes_div').style.display=" ";
  });

}

  $('#dp_offers_case').change(function(){
  if ( $('#dp_offers_case').val()==1)
 $('#offers_notes_div').hide();
 else
$('#offers_notes_div').show();
  });

function getDetails(){

 var id_v=$('#txt_purchase_order_id').val();
        get_data('{$details_url}',{id:{$suppliers_offers_id_val}, purchase_order_id:id_v},function(data){

$('#classes').html(data);
  reBind();
},'html');



}
function suppliers_offers_get_next(){
   get_to_link('{$get_url}/{$next_suppliers_offers_id}/{$order_purpose}');
}
function suppliers_offers_get_prev(){
   get_to_link('{$get_url}/{$prev_suppliers_offers_id}/{$order_purpose}');
}
function suppliers_offers_new(){
   //get_to_link('{$create2_url}/'+$('#txt_purchase_order_id').val()+'?order_purpose=$order_purpose');
      get_to_link('{$create2_url}/?order_purpose=$order_purpose');

}
    function selectAccount(obj){

    _showReport('$customer_url/'+$(obj).attr('id')+'/1');
    }

  $('input[name="purchase_order_id"]').bind('keyup', '+', function(e) {
  var id_v;
   $(this).val( $(this).val().replace('+', ''));
           _showReport('$select_order_url/'+$(this).attr('id')+'?order_purpose=$order_purpose' );

    });

   $('#txt_purchase_order_id').bind("change",function(e){
 var id_v=$(this).val();
        get_data('{$details_url}',{id:{$suppliers_offers_id_val}, purchase_order_id:id_v},function(data){
$('#classes').html(data);
  reBind();
  calcTotal();
},'html');
 get_data('{$purchase_order_url}',{purchase_order_id:id_v},function(data){
                 if (data.length == 1){
                    var item= data[0];

             $('#dp_curr_id').val(item.QUOTE_CURR_ID);
               $('#txt_curr_id_name').val(item.CURR_ID_NAME);
                $('#txt_purchase_type_name').val(item.PURCHASE_TYPE_NAME);
                 $('#txt_purchase_notes').val(item.NOTES);
                  $('#txt_purchase_order_num').val(item.PURCHASE_ORDER_NUM);
                    $('#txt_quote_condition').val(item.QUOTE_CONDITION);

      reBind();
          }else{
    $('#dp_curr_id').val(0);
    $('#txt_curr_id_name').val('');
    $('#txt_purchase_type_name').val('');
    $('#txt_purchase_notes').val('');
       $('#txt_purchase_order_num').val('');
                }

         });


        });
  $('#dp_customer_curr_id').change(function(){
  currency_value();

  });



       function currency_value(){
            //  $('#txt_curr_value').val($('#dp_curr_id').find(':selected').attr('data-val'));
            var cid=$('#dp_curr_id').val();
           var ccid=$('#dp_customer_curr_id').val();

              get_data('{$curr_val_url}',{curr_id:cid,customer_curr_id:ccid},function(data){
             $('#txt_curr_value').val(data);
             calcTotal();

       });
}
if ({$adopt}!=2){
     $('#txt_customer_name').bind("focus",function(e){

        selectAccount(this);

        });



        $('#txt_customer_name').click(function(e){

            selectAccount(this);

        });
}
$('input[name="customer_price[]"]').bind("change",function(e){
 calcTotal();

            });

$('input[name="curr_value"]').bind("change",function(e){
 calcTotal();
            });


 function calcTotal(){
    var curr_value = $('#txt_curr_value').val();
    var ATotal=0;
var UTotal=0;
             $('input[name="customer_price[]"]').each(function(i){
                      var customer_price = $(this).val();
 var amount= $(this).closest('tr').find('input[name="amount[]"]').val();


                      var total =isNaNVal( customer_price * curr_value,4);
                      ATotal +=total;


                    $(this).closest('tr').find('input[name="price[]"]').val(total);

                   var total_price =isNaNVal( total * amount,4);
                     $(this).closest('tr').find('input[name="total_price[]"]').val(total_price);
                   UTotal +=total_price;

                });

               //  $('#inv_total').text( isNaNVal(ATotal));
  //$('#inv_customer_total').text( isNaNVal(CTotal));
                $('#inv_total').text( isNaNVal(UTotal));

        }




$('button[data-action="submit"]').click(function(e){
    e.preventDefault();
    var tbl = '#{$TB_NAME}_tb';
    var container = $('#' + $(tbl).attr('data-container'));
    var form = $(this).closest('form');
    ajax_insert_update(form,function(data){

    if(parseInt(data)>=1){
            success_msg('رسالة','تم حفظ البيانات بنجاح ..');
            get_to_link(window.location.href);
               //  window.location.reload();
        }else{
            danger_msg('تحذير..',data);
        }



    },"html");
});








SCRIPT;





if(HaveAccess($create_url) and $action=='index' ){

    $scripts = <<<SCRIPT
<script>
 // $(function() {
   //     $( "#suppliers_offers_detTbl tbody" ).sortable();
   // });
  {$shared_js}


    function clear_form(){
       // clearForm($('#{$TB_NAME}_from'));

    }



</script>

SCRIPT;
    sec_scripts($scripts);

}
else
    if(HaveAccess($edit_url) and $action=='edit'){


        $edit_script = <<<SCRIPT


<script>

  {$shared_js}

 $('#print_rep').click(function(){


            _showReport('$print_url'+'report=purchases/SUPPLIERS_OFFERS_TB_REP&params[]={$suppliers_offers_id}');
    });

function adopt1(){

    if(confirm('هل تريد إتمام العملية ؟')){

    get_data('{$adopt1_url}',{id:{$suppliers_offers_id}},function(data){
      if(data =='1')
      console.log(data);
                      success_msg('رسالة','تمت العملية بنجاح');
                      setTimeout(function(){

                  window.location.reload();

                }, 1000);
            },'html');


}


}

function adopt2(){

    if(confirm('هل تريد إتمام العملية ؟')){


    get_data('{$adopt2_url}',{id:{$suppliers_offers_id}},function(data){
      if(data =='1')
      console.log(data);
                      success_msg('رسالة','تمت العملية بنجاح');
                      setTimeout(function(){

                window.location.reload();

                }, 1000);
            },'html');


}


}




</script>
SCRIPT;
        sec_scripts($edit_script);

    }
?>
