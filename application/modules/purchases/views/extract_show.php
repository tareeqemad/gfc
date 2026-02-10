<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

$MODULE_NAME = 'purchases';
$TB_NAME = 'Extract';
$DET_TB_NAME = '';
$gfc_domain = gh_gfc_domain();
$orders_details_url = base_url("$MODULE_NAME/$TB_NAME/public_get_order_extract_details");
$prev_extract_url = base_url("$MODULE_NAME/$TB_NAME/public_get_prev_extract_details");
$get_url = base_url("$MODULE_NAME/$TB_NAME/get");
$post_url = base_url("$MODULE_NAME/$TB_NAME/" . ($action == 'index' ? 'create' : $action));
$create_url = base_url("$MODULE_NAME/$TB_NAME/create");
$edit_url = base_url("$MODULE_NAME/$TB_NAME/edit");
$adopt_url = base_url("$MODULE_NAME/$TB_NAME/adopt_");
$prepare_payment_url = base_url("payment/payment_cover/create");
$get_prepare_payment_url= base_url("payment/payment_cover/get");
$eadopt_url = base_url("supplier_evaluation/supplier_evaluation_marks/adopt_");
$eval_url = base_url("supplier_evaluation/supplier_evaluation_marks/create");
$get_eval_url = base_url("supplier_evaluation/supplier_evaluation_marks/get");
$report_jasper_url = base_url("JsperReport/showreport?sys=financial/purchases");
$report_sn = report_sn();
if (!isset($result)) $result = array();
$HaveRs = count($result) > 0;
$rs = $HaveRs ? $result[0] : $result;
$msg_note = '';
if ($action == 'index') {

    $ser = "";
    $class_input_id = $this->uri->segment(4);
    $extract_ser = "";
    $extract_note = "";
    $extract_curr_id = $HaveRs ? $rs['CUSTOMER_CURR_ID'] : "";
    $extract_curr_id_name = $HaveRs ? $rs['CUSTOMER_CURR_ID_NAME'] : "";
    $purchase_id = $HaveRs ? $rs['PURCHASE_ORDER_ID'] : "";
    $purchase_text = $HaveRs ? $rs['PURCHASE_ORDER_NUM'] : "";
    $purchase_curr_id = $HaveRs ? $rs['CURR_ID'] : "";
    $purchase_curr_id_name = $HaveRs ? $rs['CURR_ID_NAME'] : "";
    $purchase_type_name = $HaveRs ? $rs['PURCHASE_TYPE_NAME'] : "";
    $purchase_notes = $HaveRs ? $rs['PURCHASE_NOTES'] : "";
    $order_id = $HaveRs ? $rs['ORDER_ID'] : "";
    $order_id_text = $HaveRs ? $rs['ORDER_TEXT_T'] : "";
    $customer_id = $HaveRs ? $rs['CUSTOMER_ID'] : "";
    $customer_id_name = $HaveRs ? $rs['CUST_NAME'] : "";
    $cust_curr_id_name = $HaveRs ? $rs['CUSTOMER_CURR_ID_NAME'] : "";
    $cust_curr_id = $HaveRs ? $rs['CUSTOMER_CURR_ID'] : "";
    $prev_paid = $HaveRs ? $rs['PREV_DISCOUNT'] : "";
    $real_order_id = $HaveRs ? $rs['REAL_ORDER_ID'] : "";
    $eval_no = $HaveRs ? $rs['EVAL_NO'] : -1;
    if ($prev_paid == "")
        $prev_paid = 0;
    $discount = 0;
    $adopt = 1;
    $back_url = base_url("$MODULE_NAME/$TB_NAME/create");

} else {

    $ser = $HaveRs ? $rs['SER'] : "";
    $class_input_id = $HaveRs ? $rs['CLASS_INPUT_ID'] : "";
    $extract_ser = $HaveRs ? $rs['EXTRACT_SER_TXT'] : "";
    $extract_curr_id = $HaveRs ? $rs['EXTRACT_CURR_ID'] : "";
    $extract_curr_id_name = $HaveRs ? $rs['EXTRACT_CURR_ID_NAME'] : "";
    $purchase_id = $HaveRs ? $rs['PURCHASE_ID'] : "";
    $purchase_text = $HaveRs ? $rs['PURCHASE_TEXT'] : "";
    $purchase_curr_id = $HaveRs ? $rs['PURCHASE_CURR_ID'] : "";
    $purchase_curr_id_name = $HaveRs ? $rs['PURCHASE_CURR_ID_NAME'] : "";
    $purchase_type_name = $HaveRs ? $rs['PURCHASE_TYPE_NAME'] : "";
    $purchase_notes = $HaveRs ? $rs['PURCHASE_NOTES'] : "";
    $order_id = $HaveRs ? $rs['ORDER_ID'] : "";
    $order_id_text = $HaveRs ? $rs['ORDER_ID_TEXT'] : "";
    $customer_id = $HaveRs ? $rs['CUSTOMER_ID'] : "";
    $customer_id_name = $HaveRs ? $rs['CUSTOMER_ID_NAME'] : "";
    $cust_curr_id_name = $HaveRs ? $rs['CUST_CURR_ID_NAME'] : "";
    $cust_curr_id = $HaveRs ? $rs['CUST_CURR_ID'] : "";
    $discount = $HaveRs ? $rs['DISCOUNT'] : 0;
    $back_url = base_url("$MODULE_NAME/$TB_NAME");
    $adopt = $HaveRs ? $rs['ADOPT'] : "";
    $prev_paid = $HaveRs ? $rs['PREV_DISCOUNT'] : "";
    $real_order_id = $HaveRs ? $rs['REAL_ORDER_ID'] : "";
    $extract_note = $HaveRs ? $rs['EXTRACT_NOTE'] : "";
    $eval_no = $HaveRs ? $rs['EVAL_NO'] : -1;
    if ($eval_no == '')
        $eval_no = -1;
    $back_url = base_url("$MODULE_NAME/$TB_NAME");

}

echo AntiForgeryToken();
?>


<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>
        <ul>
            <?php //HaveAccess($back_url)
            if (TRUE): ?>
                <li><a href="<?= $back_url ?>"><i class="icon icon-reply"></i> </a>
                </li><?php endif; ?>
            <li><a onclick="<?= $help ?>" href="javascript:" class="help"><i class="icon icon-question-circle"></i></a>
            </li>
        </ul>


    </div>
</div>
<div class="form-body">

    <div id="msg_container"></div>
    <div id="container">

        <form class="form-vertical" id="<?= $TB_NAME ?>_form" method="post" action="<?= $post_url ?>" role="form"
              novalidate="novalidate">
            <div class="modal-body inline_form">
                <fieldset>
                    <legend>بيانات المسخلص</legend>
                    <div class="form-group col-sm-1">
                        <label class="control-label">المسلسل </label>
                        <div>
                            <input type="text" value="<?= $ser; ?>" name="extract_id"
                                   id="txt_extract_id" class="form-control" readonly/>

                            <input type="hidden" value="<?= $HaveRs ? $class_input_id : "" ?>" name="class_input_id"
                                   id="txt_class_input_id" class="form-control" readonly/>
                        </div>
                    </div>
                    <div class="form-group col-sm-1">
                        <label class="control-label">مستخلص رقم</label>
                        <div>
                            <input type="text" value="<?= $extract_ser; ?>" name="extract_id_cntr"
                                   id="txt_extract_id_cntr" class="form-control" readonly/>
                        </div>
                    </div>
                    <div class="form-group col-sm-1">
                        <label class="control-label">عملة المستخلص</label>
                        <div>
                            <input type="hidden" name="extract_curr_id"
                                   value="<?= $extract_curr_id; ?>"
                                   id="txt_extract_curr_id">
                            <input type="text" readonly value="<?= $extract_curr_id_name; ?>"
                                   name="extract_curr_id_name"
                                   id="txt_extract_curr_id_name" class="form-control"/>

                        </div>
                    </div>

                    <div class="form-group col-sm-5">
                        <label class="control-label">بيان المستخلص</label>
                        <div>
                            <input type="text" value="<?= $extract_note; ?>" name="extract_note"
                                   id="txt_extract_note" class="form-control"/>
                        </div>
                    </div>

                </fieldset>
                <hr/>

                <fieldset>
                    <legend> بيانات طلب الشراء</legend>
                    <div class="form-group col-sm-1">
                        <label class="control-label">رقم المسلسل </label>
                        <div>
                            <input type="text" value="<?= $purchase_id; ?>"
                                   name="purchase_order_id"
                                   id="txt_purchase_order_id" class="form-control"/>
                        </div>
                    </div>
                    <div class="form-group col-sm-1">
                        <label class="control-label">رقم طلب الشراء </label>
                        <div>
                            <input type="text" readonly value="<?= $purchase_text; ?>"
                                   name="purchase_order_num"
                                   id="txt_purchase_order_num" class="form-control"/>
                        </div>
                    </div>
                    <div class="form-group col-sm-1">
                        <label class="control-label"> عملة عرض السعر </label>
                        <div>
                            <input type="hidden" name="curr_id" value="<?= $purchase_curr_id; ?>"
                                   id="dp_curr_id">
                            <input type="text" readonly value="<?= $purchase_curr_id_name; ?>"
                                   name="curr_id_name"
                                   id="txt_curr_id_name" class="form-control"/>

                        </div>
                    </div>
                    <div class="form-group col-sm-1">
                        <label class="control-label">نوع الطلب</label>
                        <div>
                            <input type="text" readonly value="<?= $purchase_type_name; ?>"
                                   name="purchase_type_name"
                                   id="txt_purchase_type_name" class="form-control"/>

                        </div>
                    </div>
                    <div class="form-group col-sm-9">
                        <label class="control-label">بيان الطلب</label>
                        <div>
                            <input type="text" readonly value="<?= $purchase_notes; ?>"
                                   name="purchase_notes"
                                   id="txt_purchase_notes" class="form-control"/>
                        </div>
                    </div>
                </fieldset>
                <hr/>
                <fieldset>
                    <legend>بيانات أمر التوريد</legend>

                    <div class="form-group col-sm-1">
                        <label class="control-label">رقم أمر التوريد</label>
                        <div>
                            <input type="text" readonly value="<?= $real_order_id; ?>" name="real_order_id"
                                   id="txt_real_order_id" class="form-control"/>

                        </div>
                    </div>

                    <div class="form-group col-sm-1">
                        <label class="control-label">رقم أمر التوريد(s)</label>
                        <div>
                            <input type="text" readonly value="<?= $order_id; ?>" name="order_id"
                                   id="txt_order_id" class="form-control"/>

                        </div>
                    </div>

                    <div class="form-group col-sm-1">
                        <label class="control-label"> مسلسل التوريد </label>
                        <div>
                            <input type="text" readonly value="<?= $order_id_text; ?>"
                                   name="order_text_t"
                                   id="txt_order_text_t" class="form-control"/>

                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">رقم هوية أو المشتغل المرخص للمورد</label>
                        <div>
                            <input type="text" name="customer_id" value="<?= $customer_id; ?>"
                                   id="h_txt_customer_name"
                                   readonly data-val-required="حقل مطلوب" class="form-control">
                            <span class="field-validation-valid" data-valmsg-for="customer_id"
                                  data-valmsg-replace="true"></span>
                        </div>
                    </div>

                    <div class="form-group col-sm-4">
                        <label class="control-label">المورد</label>
                        <div>
                            <input name="customer_name" data-val="true" readonly data-val-required="حقل مطلوب"
                                   class="form-control" id="txt_customer_name"
                                   value="<?= $customer_id_name; ?>">

                            <span class="field-validation-valid" data-valmsg-for="customer_name"
                                  data-valmsg-replace="true"></span>
                        </div>
                    </div>
                    <div class="form-group  col-md-2 hidden">
                        <label class="control-label">اسم المورد</label>
                        <select name="cust_id" id="dp_cust_id" class="form-control">
                            <option value="">...</option>
                        </select>
                    </div>

                    <div class="form-group col-sm-1">
                        <label class="control-label"> عملة المورد </label>
                        <div>
                            <input type="text" name="cust_curr_id"
                                   value="<?= $cust_curr_id_name; ?>" id="txt_cust_curr_id"
                                   readonly
                                   data-val-required="حقل مطلوب" class="form-control">
                            <input type="hidden" name="cust_cur" value="<?= $cust_curr_id; ?>"
                                   id="cust_cur">
                            <span class="field-validation-valid" data-valmsg-for="cust_curr_id"
                                  data-valmsg-replace="true"></span>
                        </div>
                    </div>

                </fieldset>

                <hr/>
                <fieldset>
                    <legend> بيانات الأصناف</legend>
                    <div style="clear: both" id="classes">
                        <input type="hidden" id="h_data_search"/>
                        <?php
                        echo modules::run("$MODULE_NAME/$TB_NAME/$orders_details_url", $ser, $order_id, $class_input_id, $cust_curr_id_name, $discount, $prev_paid);
                        ?>


                    </div>
                </fieldset>

                <hr/>


                <fieldset>
                    <legend>اجمالي ما تم تنفيذه</legend>
                    <?php
                    echo modules::run("$MODULE_NAME/$TB_NAME/$prev_extract_url", $ser, $order_id, $customer_id);
                    ?>

                </fieldset>


            </div>

            <div class="modal-footer">
                <?php if ((HaveAccess($create_url) || HaveAccess($edit_url)) && (($adopt == 1))) : ?>
                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                <?php endif; ?>
                <?php if ((HaveAccess($eadopt_url . '10') or HaveAccess($eadopt_url . '20') or HaveAccess($eadopt_url . '30')) and !$isCreate and $ser != "" and (($adopt != 0)) and (($adopt >= 1)) and $rs['TOTAL_AMOUNT_ORDERS'] == $rs['TOTAL_AMOUNT_EXTRACT']) : ?>
                    <button type="button" id="evalue" class="btn btn-warning">
                        تقييم المورد
                    </button>
                <?php endif; ?>


                <?php if (HaveAccess($adopt_url . '10') and !$isCreate and $adopt == 1 and $ser != "") : ?>
                    <button type="button" id="btn_adopt_10" onclick='adopt_(10);'
                            class="btn btn-success btn_adopt_class">
                        اعتماد المعد
                    </button>
                <?php endif; ?>
                <?php if (HaveAccess($adopt_url . '0') and !$isCreate and $rs['ADOPT'] == 1) : ?>
                    <button type="button" id="btn_adopt_0" onclick='adopt_(0);'
                            class="btn btn-danger btn_adopt_class ">إلغاء
                        مسخلص
                    </button>
                <?php endif; ?>
                <?php if (HaveAccess($adopt_url . '_10') and !$isCreate and $rs['ADOPT'] == 10 and $ser != "") : ?>
                    <button type="button" id="btn_adopt__10" onclick='adopt_("_10");'
                            class="btn btn-danger btn_adopt_class">
                        الغاء
                        الاعتماد
                    </button>
                <?php endif; ?>
                <?php if (HaveAccess($adopt_url . '20') and !$isCreate and $adopt == 10 and $ser != "") : ?>
                    <button type="button" id="btn_adopt_20" onclick='adopt_(20);'
                            class="btn btn-success btn_adopt_class">
                        اعتماد
                        رئيس قسم العطاءات و الممارسات
                    </button>
                <?php endif; ?>


                <?php if (HaveAccess($adopt_url . '_20') and !$isCreate and $rs['ADOPT'] == 20 and $ser != "") : ?>
                    <button type="button" id="btn_adopt__20" onclick='adopt_("_20");'
                            class="btn btn-danger btn_adopt_class">
                        الغاء
                        الاعتماد
                    </button>
                <?php endif; ?>
                <?php if (HaveAccess($adopt_url . '30') and !$isCreate and $adopt == 20 and $ser != "") : ?>
                    <button type="button" id="btn_adopt_30" onclick='adopt_(30);'
                            class="btn btn-warning btn_adopt_class">
                        اعتماد
                        مدير دائرة المشتريات
                    </button>
                <?php endif; ?>
                <?php if (HaveAccess($adopt_url . '_30') and !$isCreate and $rs['ADOPT'] == 30 and $ser != "") : ?>
                    <button type="button" id="btn_adopt__30" onclick='adopt_("_30");'
                            class="btn btn-danger btn_adopt_class">
                        الغاء
                        الاعتماد
                    </button>
                <?php endif; ?>
                <?php if (!$isCreate and $adopt == 30 and $ser != "") : ?>
                    <a onclick="print_extract_report(<?= $ser ?>);" class="btn btn-default"
                       href="javascript:">طباعة مستخلص</a>
                <?php endif; ?>
                <?php if (!$isCreate and $adopt == 30 and $ser != "" and (HaveAccess($prepare_payment_url)) and $rs['PAYMENT_COVER_SER']=='')  : ?>


                <a  class="btn btn-info"
                    href="<?=$prepare_payment_url.'/'.$ser?>" target="_blank"> نموذج تجهيز معاملة صرف</a>



                   <?php endif; ?>

                <?php if (!$isCreate and $adopt == 30 and $ser != "" and HaveAccess($get_prepare_payment_url) and $rs['PAYMENT_COVER_SER']!='') : ?>


                        <a  class="btn btn-info"
                            href="<?=$get_prepare_payment_url.'/'.$rs['PAYMENT_COVER_SER']?>" target="_blank"> عرض نوذج تجهيز معاملة صرف</a>


                <?php endif; ?>

            </div>

        </form>
    </div>
</div>

<?php
$scripts = <<<SCRIPT
<script type="text/javascript">

    calcall();
////////////////////////////////////////////////////////
    function calcall() {
        var total_total_price_class = $('#txt_total_total_price_class').text();
        var discount = $('#txt_discount').val();
        $('#txt_net_total').text(Number(total_total_price_class)-Number(discount));
        var net_total = $('#txt_net_total').text();
        var prev_extract = $('#txt_prev_extract').text();
        $('#txt_pay').text(Number(net_total)-Number(prev_extract));
    
    }
///////////////////////////////////////////////////////////////////   
       
    $('#txt_discount').change(function (e) {
                calcall();                
    });
 /////////////////////////////////////////////////////////////
 $('#evalue').click(function (e) {

     if('{$eval_no}'==-1)
    location.href = '{$eval_url}'+'/'+$('#txt_order_id').val()+'/'+$('#txt_extract_id').val();
     else
    location.href = '{$get_eval_url}'+'/{$eval_no}';     
                  
    });
////////////////////////////////////////////////////////////////// 
    $('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        var tbl = '#{$TB_NAME}_tb';
        var container = $('#' + $(tbl).attr('data-container'));
        var form = $(this).closest('form');
        ajax_insert_update(form,function(data){

            if(parseInt(data)>=1){
                success_msg('رسالة','تم حفظ البيانات بنجاح ..');
               get_to_link('{$get_url}/'+parseInt(data));
            }else{
                danger_msg('تحذير..',data);
            }

        },"html");
    });
 ///////////////////////////////////////////////////////////////////
 function show_adopts(){
    $('#adopts_Modal').modal();
}   
///////////////////////////////////////////////////////////////////
    var btn__= '';
    $('#btn_adopt_0,#btn_adopt_10,#btn_adopt__10,#btn_adopt_20,#btn_adopt__20,#btn_adopt_30').click( function(){
        btn__ = $(this);
    });
///////////////////////////////////////////////////////////////////
    function adopt_(no){
       
        var msg= 'هل تريد الاعتماد ؟!';
      
        if(no==0) msg= '!!هل تريد بالتأكيد الغاء المستخلص؟لا يمكن التراجع عن هذه العملية';
        if(no=='_10' || no=='_20' || no=='_30') msg= 'هل تريد بالتأكيد الغاء الاعتماد؟!';
        
        if(confirm(msg)){
            var values= {ser: "{$ser}"};
            
            get_data('{$adopt_url}'+no, values, function(ret){
                if(ret==1){
                    success_msg('رسالة','تمت العملية بنجاح..');
                    $('button').attr('disabled','disabled');

                       var sub= 'اعتماد مستخلص {$purchase_text}/'+'{$order_id}/'+'{$extract_ser}';
                        var text= 'يرجى اعتماد مستخلص رقم {$purchase_text}/'+'{$order_id}/'+'{$extract_ser}';
                        text+= '<br>للاطلاع افتح الرابط';
                        text+= ' <br>{$gfc_domain}{$get_url}/{$ser} ';
                        if(no=='10' || no=='20' || no=='30')
                            {
                                 _send_mail(btn__,'{$next_adopt_email}',sub,text);
                            }
                        if(no=='_10' || no=='_20' || no=='_30')
                            {
                                sub=sub+" (مرجع) "
                                 _send_mail(btn__,'{$prev_adopt_email}',sub,text);
                            }
                       
                        btn__ = '';

                    setTimeout(function(){
                        get_to_link(window.location.href);
                    },1000);
                }else{
                    danger_msg('تحذير..',data);
                }
            }, 'html');
        }
    }
/////////////////////////////////////////
     function print_extract_report(ser){
        
           var rep_url = '{$report_jasper_url}&report_type=pdf&report=extract_preparation&p_ser='+ser+'&sn={$report_sn}';
           _showReport(rep_url);
     }
</script>
SCRIPT;
sec_scripts($scripts);
?>
