<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 03/03/15
 * Time: 12:56 م
 */
$MODULE_NAME= 'purchases';
$TB_NAME= 'orders';

$print_url=  'https://itdev.gedco.ps/gfc.aspx?data='.get_report_folder().'&' ;
$print_url1=  'https://itdev.gedco.ps/gfc.aspx?data=&' ;
$back_url=base_url("$MODULE_NAME/$TB_NAME/index");

$report_url = base_url("JsperReport/showreport?sys=purchases");
$print_rep1_url = base_url("JsperReport/showreport?sys=financial");
$report_sn= report_sn();

$customer_url =base_url('payment/customers/public_index');
$select_items_url=base_url("stores/classes/public_index");
$get_class_url =base_url('stores/classes/public_get_id');
$select_order_url = base_url("$MODULE_NAME/purchase_order/public_index");


$edit_url =base_url("$MODULE_NAME/$TB_NAME/get");
$purchase_order_url=base_url("$MODULE_NAME/purchase_order/public_get_purchase_order");
$curr_val_url= base_url("$MODULE_NAME/suppliers_offers/public_getCurVal");

if ($action=='index') {

    $order_id='';
    $order_id_val=0;
    $purchase_order_id='';
    $entry_date='';
    $customer_id='';
    $note='';
    $customer_name='';
    $notes='';
    $curr_id=0;
    $customer_curr_id=0;
    $adopt=0;
    $curr_value=1;
    $curr_id_name='';
    $purchase_notes='';
    $purchase_type_name='';
    $order_text_t='';
    $account_id='';
    $bank_id='';
    $purchase_order_num='';
    $transform_date='';
    $order_stat='';
    $real_order_id='';
}else{

    $rs= $orders_data[0];
    $order_id=isset($rs['ORDER_ID'])? $rs['ORDER_ID'] :'';
    $order_id_val=isset($rs['ORDER_ID'])? $rs['ORDER_ID'] :0;
    $purchase_order_id=isset($rs['PURCHASE_ORDER_ID'])? $rs['PURCHASE_ORDER_ID'] :'';
    $notes=isset($rs['NOTES'])? $rs['NOTES'] :'';
    $order_purpose =isset($rs['ORDER_PURPOSE'])? $rs['ORDER_PURPOSE'] :$order_purpose;
    $customer_id =isset($rs['CUSTOMER_ID'])? $rs['CUSTOMER_ID'] :'';
    $customer_name =isset($rs['CUST_NAME'])? $rs['CUST_NAME'] :'';
    $notes=isset($rs['NOTES'])? $rs['NOTES'] :'';
    $curr_id=isset($rs['CURR_ID'])? $rs['CURR_ID'] :0;
    $curr_value=isset($rs['CURR_VALUE'])? $rs['CURR_VALUE'] :1;
    $curr_id_name=isset($rs['CURR_ID_NAME'])? $rs['CURR_ID_NAME'] :'';
    $adopt=isset($rs['ADOPT'])? $rs['ADOPT'] :1;
    $purchase_notes=isset($rs['PURCHASE_NOTES'])? $rs['PURCHASE_NOTES'] :'';
    $purchase_type_name=isset($rs['PURCHASE_TYPE_NAME'])? $rs['PURCHASE_TYPE_NAME'] :'';
    $order_text_t=isset($rs['ORDER_TEXT_T'])? $rs['ORDER_TEXT_T'] :'';
    $entry_date=isset($rs['ENTRY_DATE'])? $rs['ENTRY_DATE'] :'';
    $account_id=isset($rs['ACCOUNT_ID'])? $rs['ACCOUNT_ID'] :'';
    $bank_id=isset($rs['BANK_ID'])? $rs['BANK_ID'] :'';
    $purchase_order_num=isset($rs['PURCHASE_ORDER_NUM'])? $rs['PURCHASE_ORDER_NUM'] :'';
    $transform_date=isset($rs['TRANSFORM_DATE'])? $rs['TRANSFORM_DATE'] :'';
    $order_stat=isset($rs['ORDER_STAT'])? $rs['ORDER_STAT'] :'';
    $customer_curr_id=isset($rs['CUSTOMER_CURR_ID'])? $rs['CUSTOMER_CURR_ID'] :0;
    $real_order_id=isset($rs['REAL_ORDER_ID'])? $rs['REAL_ORDER_ID'] :'';

}
$create_url =base_url("$MODULE_NAME/$TB_NAME/create?order_purpose=".$order_purpose);
$post_url= base_url("$MODULE_NAME/$TB_NAME/".($action == 'index'?'create?order_purpose='.$order_purpose:$action));

$adopt1_url=base_url("$MODULE_NAME/$TB_NAME/adopt1_$order_purpose");
$adopt2_url=base_url("$MODULE_NAME/$TB_NAME/adopt2_$order_purpose");
$adopt3_url=base_url("$MODULE_NAME/$TB_NAME/adopt3_$order_purpose");
$adopt4_url=base_url("$MODULE_NAME/$TB_NAME/adopt4_$order_purpose");
$adopt5_url=base_url("$MODULE_NAME/$TB_NAME/adopt5_$order_purpose");
$cancel_adopt2_url=base_url("$MODULE_NAME/$TB_NAME/cancel_adopt2_$order_purpose");
$cancel_adopt3_url=base_url("$MODULE_NAME/$TB_NAME/cancel_adopt3_$order_purpose");
$cancel_adopt4_url=base_url("$MODULE_NAME/$TB_NAME/cancel_adopt4_$order_purpose");
$cancel_adopt5_url=base_url("$MODULE_NAME/$TB_NAME/cancel_adopt5_$order_purpose");

//$return_adopt1_url=base_url("$MODULE_NAME/$TB_NAME/return_adopt1_$order_purpose");
$return_adopt2_url=base_url("$MODULE_NAME/$TB_NAME/return_adopt2_$order_purpose");
$return_adopt3_url=base_url("$MODULE_NAME/$TB_NAME/return_adopt3_$order_purpose");
$return_adopt4_url=base_url("$MODULE_NAME/$TB_NAME/return_adopt4_$order_purpose");


$update_real_order_id=base_url("$MODULE_NAME/$TB_NAME/update_real_order_id");
if($order_purpose==1)
    $DET_TB_NAME= 'public_get_details';
elseif($order_purpose==2)
    $DET_TB_NAME= 'public_get_det_items';
else
    die('show');


$details_url =base_url("$MODULE_NAME/$TB_NAME/$DET_TB_NAME");



echo AntiForgeryToken();
?>


<div class="row">
    <div class="toolbar">

        <div class="caption"><?=$title?></div>
        <ul>
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
                            <input type="text" value="<?=$purchase_order_id?>"  name="purchase_order_id"  id="txt_purchase_order_id" class="form-control" />
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
                </fieldset><hr/>
                <fieldset  >
                    <legend>بيانات أمر التوريد</legend>
                    <div class="form-group col-sm-1">
                        <label class="control-label">رقم أمر التوريد(s)</label>
                        <div>
                            <input type="text" readonly value="<?=$order_id?>" name="order_id"  id="txt_order_id" class="form-control" />
                            <?php if (( isset($can_edit)?$can_edit:false)) : ?>
                                <input type="hidden" value="<?=$order_id?>"   name="order_id" id="h_order_id">
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="form-group col-sm-1">
                        <label class="control-label"> مسلسل التوريد </label>
                        <div>
                            <input type="text" readonly value="<?=$order_text_t?>" name="order_text_t"  id="txt_order_text_t" class="form-control" />

                        </div>
                    </div>
                    <div class="form-group col-sm-2">
                        <label class="control-label"> تاريخ السند  </label>
                        <div >
                            <input type="text" readonly value="<?=$entry_date?>" name="entry_date"  data-type="date"  data-date-format="DD/MM/YYYY"  data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?= date_format_exp() ?>" data-val="false"  data-val-required="حقل مطلوب"  id="txt_entry_date" class="form-control ">

                        </div></div>
                    <div class="form-group col-sm-2">
                        <label class="control-label">رقم هوية أو المشتغل المرخص للمورد</label>
                        <div>
                            <input type="text" name="customer_id" value="<?=$customer_id?>"  id="h_txt_customer_name" readonly data-val-required="حقل مطلوب"   class="form-control">
                            <span class="field-validation-valid" data-valmsg-for="customer_id" data-valmsg-replace="true"></span>
                        </div></div>

                    <div class="form-group col-sm-3">
                        <label class="control-label">المورد</label>
                        <div>
                            <input name="customer_name" data-val="true" readonly data-val-required="حقل مطلوب"   class="form-control"  id="txt_customer_name" value="<?=$customer_name?>"   >
                            <!-- <input type="hidden" name="customer_id" value="<?=$customer_id?>"  id="h_txt_customer_name"> -->
                            <span class="field-validation-valid" data-valmsg-for="customer_name" data-valmsg-replace="true"></span>
                        </div></div>


                    <div class="form-group col-sm-1">
                        <label class="control-label">  عملة المورد  </label>
                        <div >
                            <select  name="customer_curr_id" id="dp_customer_curr_id"  data-curr="false"  class="form-control">
                                <?php foreach($currency as $row) :?>
                                    <option  data-val="<?= $row['VAL'] ?>" <?php if($customer_curr_id==$row['CURR_ID']) echo "selected"; ?>  value="<?= $row['CURR_ID'] ?>"><?= $row['CURR_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-sm-1">
                        <label class="control-label">رقم أمر التوريد</label>
                        <div>
                            <input type="text"  value="<?=$real_order_id?>" name="real_order_id"  id="txt_real_order_id" class="form-control" />

                        </div>
                    </div>
                    <?php if  ( HaveAccess($update_real_order_id) and ($action=='edit') )   : ?>
                    <div class="form-group col-sm-1">
                        <label class="control-label">حفظ رقم امر التوريد </label>
                        <div>
                            <button type="button" onclick='javascript:real_order_no();' class="btn btn-primary">حفظ رقم امر التوريد </button>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="form-group col-sm-2">
                        <label class="control-label">سعر العملة بالنسبة لعرض السعر</label>
                        <div >
                            <input type="text"  value="<?=$curr_value?>"  name="curr_value"  id="txt_curr_value" class="form-control" />

                        </div>
                    </div>
                    <div class="form-group col-sm-6">
                        <label class="control-label">ملاحظات</label>
                        <div>
                            <textarea rows="3" name="notes" id="txt_notes" class="form-control" ><?=$notes?></textarea>

                            <!--  <input type="text" value="<?=$notes?>" name="notes" id="txt_notes" class="form-control" />-->
                        </div>
                    </div>

                    <div class="form-group col-sm-9">
                        <label class="control-label">بيان المعاملة</label>
                        <div >
                            <input type="text"  value="<?=$order_stat?>"  name="order_stat"  id="txt_order_stat" class="form-control" />
                        </div>
                    </div>

                </fieldset>
                <hr/>
                <fieldset  >
                    <legend>  البيانات الخاصة بحوالة الحق </legend>
                    <div class="form-group col-sm-3">
                        <label class="control-label"> البنك</label>
                        <div >
                            <select name="bank_id"  id="dp_bank_id" class="form-control" data-val="false"  data-val-required="حقل مطلوب">
                                <option></option>
                                <?php foreach($banks as $row) :?>
                                    <option <?php if ($bank_id==$row['CON_NO']) echo "selected"; ?> value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <span class="field-validation-valid" data-valmsg-for="bank_id" data-valmsg-replace="true"></span>

                        </div>

                    </div>
                    <div class="form-group col-sm-2">
                        <label class="control-label">رقم الحساب</label>
                        <div>
                            <input name="account_id" data-val="false"  data-val-required="حقل مطلوب"   class="form-control"  id="txt_account_id" value="<?=$account_id?>"   >
                            <span class="field-validation-valid" data-valmsg-for="account_id" data-valmsg-replace="true"></span>
                        </div></div>

                    <div class="form-group col-sm-3">
                        <label class="control-label">تاريخ الحوالة</label>
                        <div >
                            <input type="text" readonly value="<?=$transform_date?>" name="transform_date"  data-type="date"  data-date-format="DD/MM/YYYY"  data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?= date_format_exp() ?>" data-val="false"  data-val-required="حقل مطلوب"  id="txt_transform_date" class="form-control ">
                        </div></div>
                </fieldset>
                <hr/>
                <fieldset  >
                    <legend> بيانات الأصناف </legend>
                    <div style="clear: both" id="classes"> <!--<input type="hidden" id="h_data_search" />-->
                        <?php
                        //echo $DET_TB_NAME;
                        echo  modules::run("$MODULE_NAME/$TB_NAME/$DET_TB_NAME", $order_id_val, $purchase_order_id ); ?>


                        <?php //echo modules::run("$MODULE_NAME/$TB_NAME/public_get_details", $order_id ); ?>
                    </div>
                </fieldset>

            </div>

            <div class="modal-footer">
                <?php if ( ( HaveAccess($create_url)||HaveAccess($edit_url)) && (($adopt==1  )|| ($adopt==0  ))  ) : ?>
                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                <?php endif; ?>

                <?php if (  (HaveAccess($adopt2_url)) and ($action=='edit') && ($adopt==1  )  ) : ?>
                    <button type="button" onclick='javascript:adopt2();' class="btn btn-primary">اعتماد</button>
                <?php endif; ?>
                <?php if (  (HaveAccess($cancel_adopt2_url)) and ($action=='edit') && ($adopt==2  )  ) : ?>
                    <button type="button" onclick='javascript:cancel_adopt2();' class="btn btn-primary">إلغاء اعتماد</button>
                <?php endif; ?>

                <?php //
                if (  (HaveAccess($return_adopt2_url)) and ($action=='edit') && ( $adopt==2  )  ) : ?>
                    <button type="button" onclick='javascript:return_adopt(2);' class="btn btn-danger">إرجاع للمشتريات </button>
                <?php endif; ?>

                <?php if (  (HaveAccess($adopt3_url)) and ($action=='edit') && ($adopt==2  )  ) : ?>
                    <button type="button" onclick='javascript:adopt3();' class="btn btn-primary">اعتماد  اللوازم و الخدمات</button>
                <?php endif; ?>
                <?php if (  (HaveAccess($cancel_adopt3_url)) and ($action=='edit') && ($adopt==3  )  ) : ?>
                    <button type="button" onclick='javascript:cancel_adopt3();' class="btn btn-primary">إلغاء اعتماد اللوازم و الخدمات </button>
                <?php endif; ?>

                <?php if (  (HaveAccess($return_adopt3_url)) and ($action=='edit') && ( $adopt==3  )  ) : ?>
                    <button type="button" onclick='javascript:return_adopt(3);' class="btn btn-danger">إرجاع للوازم و الخدمات </button>
                <?php endif; ?>

                <?php if (  (HaveAccess($adopt4_url)) and ($action=='edit') && ($adopt==3 )  ) : ?>
                    <button type="button" onclick='javascript:adopt4();' class="btn btn-primary">اعتماد الرقابة المالية</button>
                <?php endif; ?>
                <?php if (  (HaveAccess($cancel_adopt4_url)) and ($action=='edit') && ($adopt==4  )  ) : ?>
                    <button type="button" onclick='javascript:cancel_adopt4();' class="btn btn-primary">إلغاء اعتماد الرقابة المالية</button>
                <?php endif; ?>
                <?php if (  (HaveAccess($return_adopt4_url)) and ($action=='edit') && ( $adopt==4  )  ) : ?>
                    <button type="button" onclick='javascript:return_adopt(4);' class="btn btn-danger">إرجاع  للرقابة المالية  </button>
                <?php endif; ?>

                <?php if (  (HaveAccess($adopt5_url)) and ($action=='edit') && ($adopt==4  )  ) : ?>
                    <button type="button" onclick='javascript:adopt5();' class="btn btn-primary">اعتماد المدير العام</button>
                <?php endif; ?>
                <?php if ( (HaveAccess($cancel_adopt5_url)) and ($action=='edit') && ($adopt==5  )  ) : ?>
                    <button type="button" onclick='javascript:cancel_adopt5();' class="btn btn-primary">إلغاء اعتماد المدير العام</button>
                <?php endif; ?>

                <?php if ( $action=='edit'  and $adopt !=0 ) : ?>
                    <button type="button" id="print_rep" onclick="javascript:print_rep();" class="btn btn-success"> <i class="glyphicon glyphicon-print"></i> طباعة </button>
                <?php endif; ?>
                <?php if ( $action=='edit'  and $adopt !=0 ) : ?>
                    <button type="button" id="print_rep1" onclick="javascript:print_rep1();" class="btn btn-success"> <i class="glyphicon glyphicon-print"></i> طباعة حوالة الحق </button>
                <?php endif; ?>
                <?php if ( $action=='edit'  and $adopt !=0 ) : ?>
                    <button type="button" id="print_repo" onclick="javascript:print_repo();" class="btn btn-success"> <i class="glyphicon glyphicon-print"></i>تقرير إجماليات أمر التوريد  </button>
                <?php endif; ?>
                <?php if ( $action=='edit'  and $adopt !=0 ) : ?>
                    <button type="button" id="print_repod" onclick="javascript:print_repod();" class="btn btn-success"> <i class="glyphicon glyphicon-print"></i>التقرير التفصيلي لإدخالات أمر التوريد</button>
                <?php endif; ?>
                <?php if ($isCreate): ?>
                    <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>
                <?php   endif; ?>
                <button class="btn btn-warning dropdown-toggle" onclick="$('#orders_detailTbl').tableExport({type:'excel',escape:'false'});" data-toggle="dropdown"><i class="fa fa-bars"></i> تصدير</button>

            </div>

        </form>
    </div>
</div>



<?php
$shared_js = <<<SCRIPT
  var count = 0;
  var order_purpose=1;
  count = $('input[name="h_ser[]"]').length;
  

 calcTotal();

    function selectAccount(obj){

    _showReport('$customer_url/'+$(obj).attr('id')+'/1');
    }




if ({$adopt}!=2){
     $('#txt_customer_name').bind("focus",function(e){

        selectAccount(this);

        });



        $('#txt_customer_name').click(function(e){

            selectAccount(this);

        });

         $('#txt_customer_id').bind("focus",function(e){

        selectAccount(this);

        });



        $('#txt_customer_id').click(function(e){

            selectAccount(this);

        });
}

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




$('button[data-action="submit"]').click(function(e){
    e.preventDefault();
    var tbl = '#{$TB_NAME}_tb';
    var container = $('#' + $(tbl).attr('data-container'));
    var form = $(this).closest('form');
    ajax_insert_update(form,function(data){

    if(parseInt(data)>=1){
            success_msg('رسالة','تم حفظ البيانات بنجاح ..');
            get_to_link(window.location.href);
        }else{
            danger_msg('تحذير..',data);
        }

    },"html");
});

function reBind(){

$('input[name="approved_amount[]"]').bind("change",function(e){
 calcTotal();

            });
$('input[name="customer_price[]"]').bind("change",function(e){
 calcTotal();

            });
$('input[name="curr_value"]').bind("change",function(e){
 calcTotal();
            });

reBindDateTime();
}
function getDetails(){

 var id_v=$('#txt_purchase_order_id').val();

        get_data('{$details_url}',{id:{$order_id_val}, purchase_order_id:id_v},function(data){
$('#classes').html(data);
  reBind();
},'html');

}
 $('#txt_purchase_order_id').bind("change",function(e){
 var id_v=$(this).val();
        get_data('{$details_url}',{id:{$order_id_val}, purchase_order_id:id_v},function(data){
$('#classes').html(data);
  reBind();
},'html');
 get_data('{$purchase_order_url}',{purchase_order_id:id_v},function(data){
                 if (data.length == 1){
                    var item= data[0];

             $('#dp_curr_id').val(item.QUOTE_CURR_ID);
               $('#txt_curr_id_name').val(item.CURR_ID_NAME);
                $('#txt_purchase_type_name').val(item.PURCHASE_TYPE_NAME);
                 $('#txt_purchase_notes').val(item.NOTES);
                   $('#txt_purchase_order_num').val(item.PURCHASE_ORDER_NUM);

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

$('input[name="customer_price[]"]').bind("change",function(e){
 calcTotal();

            });

$('input[name="curr_value"]').bind("change",function(e){
 calcTotal();
            });
  reBind();


 function calcTotal(){
    var curr_value = $('#txt_curr_value').val();
    var ATotal=0;
 var CTotal=0;
 var UTotal=0;
             $('input[name="customer_price[]"]').each(function(i){
                      var customer_price = $(this).val();
                     var approved_amount= $(this).closest('tr').find('input[name="approved_amount[]"]').val();

                      var total =isNaNVal( customer_price * curr_value,4);
                        $(this).closest('tr').find('input[name="price[]"]').val(total);
                      ATotal +=total;
                   var total_customer =isNaNVal( customer_price * approved_amount,4);
                     $(this).closest('tr').find('input[name="total_customer_price[]"]').val(total_customer);
                   CTotal +=total_customer;

                   var total_price =isNaNVal( total * approved_amount,4);
                     $(this).closest('tr').find('input[name="total_price[]"]').val(total_price);
                   UTotal +=total_price;

                });
   $('#inv_customer_total').text( isNaNVal(CTotal));
                $('#inv_total').text( isNaNVal(UTotal));


        }
         $('input[name="purchase_order_id"]').bind('keyup', '+', function(e) {
   $(this).val( $(this).val().replace('+', ''));
           _showReport('$select_order_url/'+$(this).attr('id')+'?order_purpose=$order_purpose' );
    });

   $('#txt_purchase_order_id').bind("change",function(e){
 var id_v=$(this).val();

 get_data('{$purchase_order_url}',{purchase_order_id:id_v},function(data){
                 if (data.length == 1){
                    var item= data[0];

             $('#dp_curr_id').val(item.QUOTE_CURR_ID);
               $('#txt_curr_id_name').val(item.CURR_ID_NAME);
  reBind();
          }else{
 $('#dp_curr_id').val(0);
   $('#txt_curr_id_name').val('');
                }

         });


        });

reBind();
function addRow(){
 count = count+1;
    var html =' <tr> <td>'+ (count+1)+' <input type="hidden" value="0" name="h_ser[]" id="h_ser_'+count+'" > </td> <td> <input class="form-control" name="class[]" id="i_txt_class_id'+count+'" /> <input type="hidden" name="h_class_id[]" id="h_txt_class_id'+count+'" > </td> <td> <input name="class_name[]" readonly data-val="true" data-val-required="required" class="form-control" id="txt_class_id'+count+'" /> </td> <td> <input name="unit_class[]" disabled class="form-control" id="unit_name_txt_class_id'+count+'" /><input name="unit_class_id[]" type="hidden" class="form-control" id="unit_txt_class_id'+count+'" /> </td> <td> <input name="amount[]"  class="form-control" id="txt_amount'+count+'" /> </td> </td> <td > <input name="approved_amount[]" type="text" id="approved_amount_'+count+'" class="form-control"> </td> <td><input name="customer_price[]" id="customer_price_'+count+'" class="form-control" > </td> <td><input readonly name="total_customer_price[]" id="total_customer_price_'+count+'" data-val-required="حقل مطلوب" data-val-regex="المدخل غير صحيح!" class="form-control"> <td><input name="price[]" id="price_'+count+'" class="form-control"> </td> <td><input readonly name="total_price[]" id="total_price_'+count+'" data-val-required="حقل مطلوب" data-val-regex="المدخل غير صحيح!" class="form-control"> <td> <input type="text" name="order_date[]" id="order_date_'+count+'" class="form-control col-sm-2" data-type="date" data-date-format="DD/MM/YYYY" data-val-regex="التاريخ غير صحيح!" data-val="false" data-val-required="حقل مطلوب" > <span class="field-validation-valid" data-valmsg-for="order_date[]" data-valmsg-replace="true"></span> </td> <td > <textarea rows="2" name="note[]" id="note_'+count+'" class="form-control" ></textarea> </td> </tr>';

   $('#orders_detailTbl tbody').append(html);




    


reBind(1);
 

  
}

 function reBind(s){
        if(s==undefined)
            s=0;
        if(order_purpose==1){
            $('input[name="class_name[]"]').click("focus",function(e){
                _showReport('$select_items_url/'+$(this).attr('id')+'/1' );
            });

            $('input[name="class[]"]').bind("focusout",function(e){
                var id= $(this).val();
                var class_id= $(this).closest('tr').find('input[name="class_id[]"]');
                var name= $(this).closest('tr').find('input[name="class_name[]"]');
                var unit_name= $(this).closest('tr').find('input[name="class_unit[]"]');
                var unit= $(this).closest('tr').find('input[name="unit_class_id[]"]');
                var amount= $(this).closest('tr').find('input[name="amount[]"]');
                var buy_price= $(this).closest('tr').find('input[name="buy_price[]"]');
                var class_price= $(this).closest('tr').find('input[name="class_price[]"]');
              
                if(id==''){
                    class_id.val('');
                    name.val('');
                    unit_name.val('');
                    unit.val('');
                    buy_price.val('');
                    class_price.val('');
                   
                    
                    return 0;
                }
                get_data('{$get_class_url}',{id:id, type:1},function(data){
                    if (data.length == 1){
                        var item= data[0];
                        class_id.val(item.CLASS_ID);
                        name.val(item.CLASS_NAME_AR);
                        unit_name.val(item.UNIT_NAME);
                        unit.val(item.UNIT);
                        //unit_class_id.val(CLASS_UNIT);
                        buy_price.val(item.BUY_PRICE);
                        class_price.val(item.CLASS_PURCHASING);
                        
                        amount.focus();
                    }else{
                        class_id.val('');
                        name.val('');
                        unit_name.val('');
                        unit.val('');
                        buy_price.val('');
                        class_price.val('');
                     
                    }
                });
            });

            $('input[name="class[]"]').bind('keyup', '+', function(e) {
                $(this).val('');
                var class_name= $(this).closest('tr').find('input[name="class_name[]"]');
                actuateLink(class_name);
            });
                    }else if(order_purpose==2){
            if(s){
                $('select#txt_class_unit'+count).append('<option></option>'+select_class_unit).select2();
            }
        }
        
 $('input[name="approved_amount[]"]').bind("change",function(e){
 calcTotal();

            });
$('input[name="customer_price[]"]').bind("change",function(e){
 calcTotal();

            });
$('input[name="curr_value"]').bind("change",function(e){
 calcTotal();
            });

reBindDateTime();
    }

SCRIPT;



if(HaveAccess($create_url) and $action=='index' ){
    $scripts = <<<SCRIPT
<script>

 // $(function() {
  //      $( "#orders_detailTbl tbody" ).sortable();
 //   });
  {$shared_js}


    function clear_form(){
        clearForm($('#{$TB_NAME}_from'));

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
        var url='{$report_url}&report_type=pdf&report=ordered_supply&p_id={$order_id}&sn={$report_sn}';
        _showReport(url,true);
    });

     $('#print_rep1').click(function(){
        var repUrl = '{$print_rep1_url}/archives&report_type=pdf&report=TRANSFORM_REP&p_ORDER_ID={$order_id}';
        _showReport(repUrl,true);
    });

    $('#print_repo').click(function(){
        _showReport('$print_url1'+'report=PURCHASES_STORES_GENERAL_REP&params[]=&params[]={$order_id}');
    });
          $('#print_repod').click(function(){
          _showReport('$print_url1'+'report=PURCHASES_STORES_DETAILES_REP&params[]={$order_text_t}');
    });


function adopt2(){
//if ('{$bank_id}'!='' && '{$account_id}' !=''){
    if(confirm('هل تريد إتمام العملية ؟')){


    get_data('{$adopt2_url}',{id:{$order_id}},function(data){
      if(data =='1')
      console.log(data);
                      success_msg('رسالة','تمت العملية بنجاح');
                      setTimeout(function(){

                  window.location.reload();

                }, 1000);
            },'html');


}
//}else
//{
 //danger_msg('تحذير..','يجب اختيار البنك و إدخال رقم الحساب');
//}

}


function adopt3(){

    if(confirm('هل تريد إتمام العملية ؟')){


    get_data('{$adopt3_url}',{id:{$order_id}},function(data){
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


    get_data('{$adopt5_url}',{id:{$order_id}},function(data){
      if(data =='1')
      console.log(data);
                      success_msg('رسالة','تمت العملية بنجاح');
                      setTimeout(function(){

                  window.location.reload();

                }, 1000);
            },'html');


}


}
function adopt4(){

    if(confirm('هل تريد إتمام العملية ؟')){


    get_data('{$adopt4_url}',{id:{$order_id}},function(data){
      if(data =='1')
      console.log(data);
                      success_msg('رسالة','تمت العملية بنجاح');
                      setTimeout(function(){

                  window.location.reload();

                }, 1000);
            },'html');


}


}

function return_adopt(ad){ 
var url ;
    if(confirm('هل تريد إتمام العملية ؟')){
if (ad==4) url ='{$return_adopt4_url}';
else if (ad==3) url ='{$return_adopt3_url}';
else if (ad==2) url ='{$return_adopt2_url}';

    get_data(url,{id:{$order_id}},function(data){
      if(data =='1')
      console.log(data);
                      success_msg('رسالة','تمت العملية بنجاح');
                      setTimeout(function(){

                  window.location.reload();

                }, 1000);
            },'html');


}
}
function cancel_adopt2(){

    if(confirm('هل تريد إتمام العملية ؟')){


    get_data('{$cancel_adopt2_url}',{id:{$order_id}},function(data){
      if(data =='1')
      console.log(data);
                      success_msg('رسالة','تمت العملية بنجاح');
                      setTimeout(function(){

                  window.location.reload();

                }, 1000);
            },'html');


}
}

function cancel_adopt3(){

    if(confirm('هل تريد إتمام العملية ؟')){


    get_data('{$cancel_adopt3_url}',{id:{$order_id}},function(data){
      if(data =='1')
      console.log(data);
                      success_msg('رسالة','تمت العملية بنجاح');
                      setTimeout(function(){

                  window.location.reload();

                }, 1000);
            },'html');


}
}

function cancel_adopt4(){

    if(confirm('هل تريد إتمام العملية ؟')){


    get_data('{$cancel_adopt4_url}',{id:{$order_id}},function(data){
      if(data =='1')
      console.log(data);
                      success_msg('رسالة','تمت العملية بنجاح');
                      setTimeout(function(){

                  window.location.reload();

                }, 1000);
            },'html');


}
}

function cancel_adopt5(){

    if(confirm('هل تريد إتمام العملية ؟')){


    get_data('{$cancel_adopt5_url}',{id:{$order_id}},function(data){
      if(data =='1')
      console.log(data);
                      success_msg('رسالة','تمت العملية بنجاح');
                      setTimeout(function(){

                  window.location.reload();

                }, 1000);
            },'html');


}
}
////////////////////////
function real_order_no(){

    if(confirm('هل تريد إتمام العملية ؟')){


    get_data('{$update_real_order_id}',{id:{$order_id},real_order:$('#txt_real_order_id').val()},function(data){
      if(data =='1')
       success_msg('رسالة','تمت العملية بنجاح');

   
            },'html');


}

}

</script>
SCRIPT;
        sec_scripts($edit_script);

    }
?>
