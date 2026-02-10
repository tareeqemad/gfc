<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

$MODULE_NAME = 'purchases';
$TB_NAME = 'Receipt_batun_contracts';
$TB_NAME_ORDER = 'orders';
$items_recept = 'public_items_recept_adopt';
$gfc_domain = gh_gfc_domain();
$back_url = base_url("$MODULE_NAME/$TB_NAME");
$get_url = base_url("$MODULE_NAME/$TB_NAME/get_adopt");
$get_url_order = base_url("$MODULE_NAME/$TB_NAME_ORDER/get");
$details_logistic_items_url = base_url("$MODULE_NAME/$TB_NAME/$items_recept");
$adopt_url = base_url("$MODULE_NAME/$TB_NAME/adopt_");
$isCreate = isset($result) && count($result) > 0 ? false : true;
$HaveRs = (!$isCreate) ? true : false;
$rs = $isCreate ? array() : $result[0];
$ser = $HaveRs ? $rs['SER_ADOPT'] : '';
$report_jasper_url = base_url("JsperReport/showreport?sys=newTechnical");
$report_sn = report_sn();
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

        <form class="form-vertical" id="<?= $TB_NAME ?>_form" method="post" action="" role="form"
              novalidate="novalidate">
            <div class="modal-body inline_form">
                <fieldset>
                    <legend>بيانات محضر الفحص و الاستلام</legend>
                    <div class="row">
                        <div class="form-group col-sm-2">
                            <a href="<?=$get_url_order.'/'.$rs['ORDER_ID'].'/2'?>"><label class="control-label">رقم أمر التوريد (s)</label></a>
                            <div>

                                <input type="text" value="<?= $HaveRs ? $rs['ORDER_ID_TEXT'] : '' ?>"
                                       data-val="true" data-val-required="حقل مطلوب"
                                       name="order_id"
                                       id="txt_order_id" class="form-control" readonly/>

                                <input type="hidden" name="ser_adopt" id="h_txt_ser_adopt"
                                       value="<?= $HaveRs ? $rs['SER_ADOPT'] : '' ?>">

                                <input type="hidden" name="order_id_ser"
                                       value="<?= $HaveRs ? $rs['ORDER_ID'] : '' ?>"
                                       id="txt_order_id_ser" class="form-control" dir="rtl" readonly>
                                <span class="field-validation-valid" data-valmsg-for="order_id"
                                      data-valmsg-replace="true"></span>
                            </div>
                        </div>
                        <div class="form-group col-sm-2">
                            <a href="<?=$get_url_order.'/'.$rs['ORDER_ID'].'/2'?>"><label class="control-label">رقم أمر التوريد (الفعلي)</label></a>
                            <div>
                                <input type="text" data-val="true" data-val-required="حقل مطلوب"
                                       name="real_order_id"
                                       value="<?= $HaveRs ? $rs['REAL_ORDER_ID'] : '' ?>"
                                       id="txt_real_order_id" class="form-control" dir="rtl" readonly>
                                <span class="field-validation-valid" data-valmsg-for="real_order_id"
                                      data-valmsg-replace="true"></span>
                            </div>
                        </div>
                        <div class="form-group col-sm-2">
                            <label class="control-label">المورد</label>
                            <div>
                                <input name="customer_name" data-val="true" data-val-required="حقل مطلوب"
                                       class="form-control" readonly id="txt_customer_name"
                                       value="<?= $HaveRs ? $rs['CUSTOMER_ID_NAME'] : '' ?>">
                                <input type="hidden" name="customer_resource_id" id="h_txt_customer_name"
                                       value="<?= $HaveRs ? $rs['CUSTOMER_RESOURCE_ID'] : '' ?>">
                                <span class="field-validation-valid" data-valmsg-for="customer_name"
                                      data-valmsg-replace="true"></span>
                            </div>
                        </div>
                        <div class="form-group col-sm-2">
                            <label class="control-label">تصنيف الأعمال</label>
                            <div>
                                <select name="class_type" id="dp_class_type" class="form-control sel2">
                                    <?php foreach ($class_type as $row) : ?>
                                        <?php if ($row['CON_NO'] == 3) {
                                            ?>
                                            <option <?= $HaveRs ? ($rs['CLASS_TYPE'] == $row['CON_NO'] ? 'selected' : '') : '' ?>
                                                value="<?= $row['CON_NO'] ?>"><?= $row['CON_NO'] . ': ' . $row['CON_NAME'] ?></option>
                                        <?php } ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-sm-2">
                            <label class="control-label">تاريخ الترحيل للإعتماد</label>
                            <div>
                                <input type="text" value="<?= $HaveRs ? $rs['SEND_DATE_ADOPT'] : '' ?>"
                                       name="adopt_date"
                                       id="txt_adopt_date" class="form-control" readonly/>
                            </div>
                        </div>
                    </div>

                </fieldset>

                <hr/>

                <fieldset>
                    <legend> بيانات المواد</legend>
                    <div style="clear: both" id="classes">
                        <input type="hidden" id="h_data_search"/>
                        <?php
                        echo modules::run("$MODULE_NAME/$TB_NAME/$items_recept", $HaveRs ? $rs['SER_ADOPT'] : 0);
                        ?>
                </fieldset>
                <hr/>


            </div>

            <div class="modal-footer">
                <?php if (HaveAccess($adopt_url . '30') and  $rs['ADOPT'] == 20 and $rs['SER_ADOPT'] != "") : ?>
                    <button type="button" id="btn_adopt_30" onclick="adopt_(30);"
                            class="btn btn-success btn_adopt_class">اعتماد مدير دائرة المشاريع
                    </button>
                <?php endif; ?>

                <?php if (HaveAccess($adopt_url . '40') and !$isCreate and $rs['ADOPT'] == 30 and $rs['SER_ADOPT'] != "") : ?>
                    <button type="button" id="btn_adopt_40" onclick="adopt_(40);"
                            class="btn btn-success btn_adopt_class">اعتماد مدير الإدارة الفنية
                    </button>
                <?php endif; ?>

                <?php if (HaveAccess($adopt_url . '50') and !$isCreate and $rs['ADOPT'] == 40 and $rs['SER_ADOPT'] != "") : ?>
                    <button type="button" id="btn_adopt_50" onclick="adopt_(50);"
                            class="btn btn-success btn_adopt_class">اعتماد المدير العام
                    </button>
                <?php endif; ?>

                <?php if (HaveAccess($adopt_url . '_20') and !$isCreate and $rs['ADOPT'] == 20 and $ser != "") : ?>
                    <button type="button" id="btn_adopt__20" onclick='adopt_("_20");'
                            class="btn btn-danger btn_adopt_class">
                       ارجاع للمعد
                    </button>
                <?php endif; ?>

                <?php if (HaveAccess($adopt_url . '_30') and !$isCreate and $rs['ADOPT'] == 30 and $ser != "") : ?>
                    <button type="button" id="btn_adopt__30" onclick='adopt_("_30");'
                            class="btn btn-danger btn_adopt_class">
                        ارجاع للمعد
                    </button>
                <?php endif; ?>

                <?php if (HaveAccess($adopt_url . '_40') and !$isCreate and ($rs['ADOPT'] == 40 || $rs['ADOPT'] == 50) and $ser != "") : ?>
                    <button type="button" id="btn_adopt__40" onclick='adopt_("_40");'
                            class="btn btn-danger btn_adopt_class">
                        ارجاع للمعد
                    </button>
                <?php endif; ?>
                <?php if (!$isCreate and ($rs['ADOPT'] == 50) and $ser != "") : ?>

           <a onclick="print_receipt_report(<?= $ser ?>);" class="btn btn-default"
                   href="javascript:">طباعة</a>
                <?php endif; ?>
            </div>

        </form>
    </div>
</div>

<?php
$scripts = <<<SCRIPT

<script type="text/javascript">
     
     $(document).ready(function() {
        $('.sel2').select2(); 
        ///////////////////////////////////////////////////////////////////

 
     });
///////////////////////////////////////////////////////////////////
    var btn__= '';
    $('#btn_adopt_30,#btn_adopt_40,#btn_adopt_50,#btn_adopt__20,#btn_adopt__30,#btn_adopt__40').click( function(){
        btn__ = $(this);
    });
////////////////////////////////////////////////////////////////////    
    function adopt_(no){
       
        var msg= 'هل تريد الاعتماد ؟!';
      
        if(no==0) msg= '!!هل تريد بالتأكيد الغاء المستخلص؟لا يمكن التراجع عن هذه العملية';
        if(no=='_20' || no=='_30' || no=='_40') msg= 'هل تريد بالتأكيد الغاء الاعتماد؟!';
        
        if(confirm(msg)){
            var values= {ser: "{$ser}"};
            
            get_data('{$adopt_url}'+no, values, function(ret){
                if(!isNaN(ret)){
                    success_msg('رسالة','تمت العملية بنجاح..');
                    $('button').attr('disabled','disabled');
                        var sub= ' اعتماد محضر الفحص و الاستلام للأعمال المدنية-(باطون) رقم '+'{$ser}';
                        var text= 'يرجى اعتماد محضر الفحص و الاستلام للأعمال المدنية-(باطون) رقم '+'{$ser}';
                        text+= '<br>للاطلاع افتح الرابط';
                        text+= ' <br>{$gfc_domain}{$get_url}/{$ser} ';
                        if(no=='30' || no=='40' || no=='50')
                            {
                              
                                 _send_mail(btn__,'{$next_adopt_email}',sub,text);
                            }
                        if(no=='_20' || no=='_30' || no=='_40')
                            {
                                alert('{$prev_adopt_email}');
                                sub=sub+" (مرجع) "
                                 _send_mail(btn__,'{$prev_adopt_email}',sub,text);
                            }
                       
                        btn__ = '';

                         
                    setTimeout(function(){
                        if(no=='_20' || no=='_30' || no=='_40')
                            get_to_link('{$back_url}');
                         else
                        get_to_link(window.location.href);
                    },1000);
                }else{
                    danger_msg('تحذير..',ret);
                }
            }, 'html');
        }
    }


    

/////////////////////////////////////////
     function print_receipt_report(ser){
        
           var rep_url = '{$report_jasper_url}&report_type=pdf&report=dues_payment_concrete_suppliers&p_id='+ser+'&sn={$report_sn}';
           _showReport(rep_url);
     }




    </script>
SCRIPT;
sec_scripts($scripts);
?>
