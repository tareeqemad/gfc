<?php

$MODULE_NAME = 'supplier_evaluation';
$TB_NAME = 'supplier_evaluation_marks';

$gfc_domain= gh_gfc_domain();
$back_url = base_url("$MODULE_NAME/$TB_NAME");
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/public_get_items");
$select_orders_url = base_url("purchases/orders/public_index_modify");
$purchase_order_url = base_url("$MODULE_NAME/$TB_NAME/public_get_purchase_order");
$post_url = base_url("$MODULE_NAME/$TB_NAME/" . ($action == 'index' ? 'create' : $action));
$create_url = base_url("$MODULE_NAME/$TB_NAME/create");
$edit_url = base_url("$MODULE_NAME/$TB_NAME/edit");
$adopt_url = base_url("$MODULE_NAME/$TB_NAME/adopt_");
$report_jasper_url= base_url("JsperReport/showreport?sys=financial/supplier_evaluation");
$report_sn= report_sn();
$get_url = base_url("$MODULE_NAME/$TB_NAME/get");
$total_weight = 0;
$total_items_weight = 0;
if (!isset($result)) $result = array();
$HaveRs = count($result) > 0;
$rs = $HaveRs ? $result[0] : $result;
$msg_note='';
if ($action == 'index') {

    $ser = "";
    $customer_id_name ="";
    $adopt = 1;


} else {

    $ser = $HaveRs ? $rs['SER'] : "";
    $customer_id_name = $HaveRs ? $rs['CUSTOMER_ID_NAME'] : "";
    $adopt = $HaveRs ? $rs['ADOPT'] : "";


}
?>

<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>
        <ul>
         <!--   <?php if( HaveAccess($create_url)):  ?><li><a  href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>جديد </a> </li><?php endif; ?> -->
            <?php if( HaveAccess($back_url)):?>
                <li><a href="<?= $back_url ?>"><i class="icon icon-reply"></i> </a> </li><?php endif; ?>
        </ul>
    </div>

    <div class="form-body">

        <div id="msg_container"></div>
        <div id="container">
            <form class="form-vertical" id="<?= $TB_NAME ?>_form" method="post" action="<?= $post_url ?>" role="form">
                <div class="modal-body inline_form">

                    <div class="col-sm-10">
                        <div class="form-group  col-md-2" id="order_id"  >
                            <label class="control-label"> رقم أمر التوريد(s)</label>
                            <input type="text" data-val="false" data-val-required="حقل مطلوب" name="order_id"
                                   id="txt_order_id" class="form-control" value="<?= $HaveRs ? $rs['ORDER_ID'] : $orderinfo[0]['ORDER_TEXT_T'] ?>"
                                   dir="rtl" readonly>
                            <input type="hidden" name="order_id_ser"
                                   id="txt_order_id_ser" class="form-control" dir="rtl"
                                   value="<?= $HaveRs ? $rs['ORDER_ID_SER'] : $orderinfo[0]['ORDER_ID'] ?>" readonly>

                            <input type="hidden" name="ext_no"
                                   id="txt_ext_no" class="form-control" dir="rtl"
                                   value="<?= $HaveRs ? $rs['EXT_NO'] : $extract_id[0]['SER'] ?>" readonly>

                            <input type="hidden" name="ser"
                                   id="txt_ser" class="form-control" dir="rtl" value="<?= $HaveRs ? $rs['SER'] : "" ?>"
                                   readonly>
                            <span class="field-validation-valid" data-valmsg-for="order_id"
                                  data-valmsg-replace="true"></span>
                        </div>
                        <div class="form-group col-sm-1">
                            <label class="control-label">رقم أمر التوريد</label>
                            <div>
                                <input type="text" value="<?= $HaveRs ? $rs['REAL_ORDER_ID'] : $orderinfo[0]['REAL_ORDER_ID'] ?>"
                                       name="real_order_id" id="txt_real_order_id" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="form-group col-sm-2">
                            <label class="control-label">أمر الشراء</label>
                            <div>
                                <input type="text" value="<?= $HaveRs ? $rs['PURCHASE_ORDER_NUM'] : $orderinfo[0]['PURCHASE_ORDER_NUM'] ?>"
                                       name="purchase_order_num" id="txt_purchase_order_num" data-val="false"
                                       data-val-required="حقل مطلوب"
                                       class="form-control" readonly/>
                            </div>
                        </div>

                        <div class="form-group  col-md-2" hidden>
                            <label class="control-label">المورد</label>
                            <input name="customer_name" data-val="false" data-val-required="حقل مطلوب"
                                   class="form-control" readonly id="txt_customer_name"
                                   value="<?= $HaveRs ? $rs['CUSTOMER_ID_NAME'] : "" ?>">
                            <input type="hidden" name="customer_resource_id"
                                   value="<?= $HaveRs ? $rs['CUSTOMER_ID'] : "" ?>" id="h_txt_customer_name"
                                   data-val="false" data-val-required="حقل مطلوب">
                            <span class="field-validation-valid" data-valmsg-for="customer_name"
                                  data-valmsg-replace="true"></span>
                        </div>

                        <div class="form-group  col-md-2">
                            <label class="control-label">اسم المورد</label>
                            <select name="cust_id" id="dp_cust_id" class="form-control" data-val="true"
                                    data-val-required="حقل مطلوب">
                                <option value="">...</option>
                                <?php foreach ($customer_ids as $row) : ?>
                                    <option <?php if (($HaveRs ? $rs['CUSTOMER_ID'] :$orderinfo[0]['CUSTOMER_ID']) == $row['CUSTOMER_ID']) echo "selected"; ?>
                                            value="<?= $row['CUSTOMER_ID'] ?>"><?= $row['CUSTOMER_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group col-sm-1">
                            <label class="control-label">تاريخ التقييم</label>
                            <div>
                                <input readonly type="text" value="<?= $HaveRs ? $rs['ENTRY_DATE'] : date('d/m/Y') ?>"
                                       name="perm_year"
                                       id="txt_perm_year" class="form-control" data-val="true"
                                       data-val-required="حقل مطلوب"/>

                            </div>
                        </div>


                        <div class="col-md-2" id="div_emp_balance">
                        </div>


                    </div>
                    <br><br><br><br>

                    <?php foreach ($AXIS_Form as $row) : $f_id = $row['ID']; ?>
                        <fieldset>
                            <legend>
                                <?= $row['F_LABEL'] . ":- " . $row['ID_NAME'] ?>

                            </legend>

                            <table class="table table-bordered">

                                <thead>
                                <tr>
                                    <th style="width: 5%"><?= $row['C_LABEL'] ?></th>
                                    <th><?= $row['ID_NAME'] ?></th>
                                    <th style="width: 10%">الوزن</th>
                                    <th style="width: 10%">الدرجة</th>

                                </tr>
                                </thead>

                                <tbody>
                                <?php
                                if ($HaveRs) {
                                    $ITEMS_Form = modules::run($get_page_url, $rs['SER'], $row['ID'], 1);

                                } else
                                    $ITEMS_Form = modules::run($get_page_url, 0, $row['ID'], 0);


                                $total_weight = 0;


                                ?>
                                <?php foreach ($ITEMS_Form

                                as $row) : ?>

                                <tr>
                                    <td><?= $row['C_LABEL'] ?></td>
                                    <td style="text-align: right ">
                                        <input type="hidden" name="child_ser[]" id="txt_child_ser" class="form-control"
                                               dir="rtl" value="<?= $HaveRs ? $row['CHILD_SER'] : "" ?>" readonly>
                                        <input type="hidden" name="f_id[]" id="txt_f_id" class="form-control"
                                               value="<?= $f_id ?>" dir="rtl" readonly><?= $row['ID_NAME'] ?>
                                        <input type="hidden" name="c_id[]" id="txt_c_id" value="<?= $row['ID'] ?>"
                                               class="form-control" dir="rtl" data-val="true"
                                               data-val-required="حقل مطلوب" readonly>
                                    </td>
                                    <td class="weight">
                                        <input type='text' class="form-control " name="weight[]" data-val="true"
                                               data-val-required="حقل مطلوب" value='<?= $row['WEIGHT'] ?>'
                                               style="text-align: center " readonly/></td>
                                    <td class="col-sm-1">
                                        <input type='text' class="form-control " name="ev_weight[]" data-val="true"
                                               data-val-required="حقل مطلوب" value='<?= @$row['ITEM_MARK'] ?>'
                                               max="<?= $row['WEIGHT'] ?>"
                                               style="text-align: center "/>
                                    </td>


                                    <?php
                                    $total_weight += $row['WEIGHT'];
                                    $total_items_weight += $row['WEIGHT'];
                                    endforeach;
                                    ?>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td style="text-align: right "></td>
                                    <td class="weight"><b><?= $total_weight . '%'; ?></b></td>
                                    <td class="col-sm-1">

                                    </td>
                                </tr>


                                <tbody>

                            </table>

                        </fieldset>
                    <?php endforeach; ?>

                    <table class="table table-bordered">

                        <thead>
                        <tr>
                            <th colspan="2">الإجمالي</th>

                            <th style="width: 10%">الوزن</th>
                            <th style="width: 10%">الدرجة</th>

                        </tr>
                        </thead>
                        <tbody>
                        <td colspan="2"></td>
                        <td style="width: 10%"><b><?= $total_items_weight . '%'; ?></b></td>
                        <td style="width: 10%" id="total_ev_weight"><b>0</b></td>
                        </tbody>

                    </table>


                    <br><br>


                    <br><br>


                </div>


                <div class="modal-footer">

                    <?php if ((HaveAccess($create_url) || HaveAccess($edit_url)) && ((($HaveRs ? $rs['ADOPT'] : 1) == 1))) : ?>
                        <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                    <?php endif; ?>
                    <?php if (HaveAccess($adopt_url . '10') and !$isCreate and ($HaveRs ? $rs['ADOPT'] : 1) == 1 and ($HaveRs ? $rs['SER'] : "") != "") : ?>
                        <button type="button" id="btn_adopt_10" onclick='javascript:adopt_(10);' class="btn btn-success btn_adopt_class">
                            اعتماد المعد
                        </button>
                    <?php endif; ?>
                    <?php if (HaveAccess($adopt_url . '0') and !$isCreate and $rs['ADOPT'] == 1) : ?>
                        <button type="button" id="btn_adopt_0" onclick='javascript:adopt_(0);' class="btn btn-danger btn_adopt_class ">إلغاء
                            التقييم
                        </button>
                    <?php endif; ?>
                    <?php if (HaveAccess($adopt_url . '_10') and !$isCreate and $rs['ADOPT'] == 10 and ($HaveRs ? $rs['SER'] : "") != "") : ?>
                        <button type="button" id="btn_adopt__10"onclick='javascript:adopt_("_10");' class="btn btn-danger btn_adopt_class">
                            الغاء
                            الاعتماد
                        </button>
                    <?php endif; ?>
                    <?php if (HaveAccess($adopt_url . '20') and !$isCreate and ($HaveRs ? $rs['ADOPT'] : 1) == 10 and ($HaveRs ? $rs['SER'] : "") != "") : ?>
                        <button type="button" id="btn_adopt_20" onclick='javascript:adopt_(20);' class="btn btn-success btn_adopt_class">
                            اعتماد
                            رئيس قسم العطاءات و الممارسات
                        </button>
                    <?php endif; ?>
                    <?php if (HaveAccess($adopt_url . '_20') and !$isCreate and $rs['ADOPT'] == 20 and ($HaveRs ? $rs['SER'] : "") != "") : ?>
                        <button type="button" id="btn_adopt__20"onclick='javascript:adopt_("_20");' class="btn btn-danger btn_adopt_class">
                            الغاء
                            الاعتماد
                        </button>
                    <?php endif; ?>
                    <?php if (HaveAccess($adopt_url . '30') and !$isCreate and ($HaveRs ? $rs['ADOPT'] : 1) == 20 and ($HaveRs ? $rs['SER'] : "") != "") : ?>
                        <button type="button" id="btn_adopt_30" onclick='javascript:adopt_(30);' class="btn btn-warning btn_adopt_class">
                            اعتماد
                            مدير دائرة المشتريات
                        </button>
                    <?php endif; ?>
                    <?php if (!$isCreate and ($HaveRs ? $rs['ADOPT'] : 1) == 30 and ($HaveRs ? $rs['SER'] : "") != "") : ?>
                        <a onclick="javascript:print_extract_report(<?=($HaveRs ? $rs['SER'] : "")?>);" class="btn btn-default" href="javascript:;">طباعة التقييم</a>
                    <?php endif; ?>


                </div>
            </form>
        </div>
    </div>


    <?php
    $scripts = <<<SCRIPT
<script>
$(document).ready(function() {
    $('#dp_cust_id').select2();
    reBind();
     $('input[name="ev_weight[]"]').change();
     function reBind() {


     $('input[name="ev_weight[]"]').change(function () {
  
         var total_ev_weight=0;
           if (isNaN($(this).closest('tr').find('input[name="ev_weight[]"]').val()))
             {
                 $(this).closest('tr').find('input[name="ev_weight[]"]').val('');
                 danger_msg('تحذير..','الوزن يجب ان يكون رقم و ليس نص!!!');
             }
         else if (Number($(this).closest('tr').find('input[name="ev_weight[]"]').val())>Number($(this).closest('tr').find('input[name="weight[]"]').val()))
             {
                 $(this).closest('tr').find('input[name="ev_weight[]"]').val('');
                 danger_msg('تحذير..','الوزن لابد ان يكون اقل من او يساوي بند وزن التققيم!!!');
             }
         else
             {
         $('input[name="ev_weight[]"]').each(function (i) {

           var ev_weight = $(this).closest('tr').find('input[name="ev_weight[]"]').val();
           total_ev_weight =Number(ev_weight)+Number(total_ev_weight);
           

    });
         $('#total_ev_weight').text(Number(total_ev_weight));

     }
         });
     }
 /*$('#txt_order_id').dblclick(function(e){
              
                 _showReport('$select_orders_url' );
               
                 
            });*/
     
 $('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        var tbl = '#{$TB_NAME}_tb';
        var container = $('#' + $(tbl).attr('data-container'));
        var form = $(this).closest('form');
        ajax_insert_update(form,function(data){

            if(parseInt(data)>=1){
                success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                 if('{$this->uri->segment(3)}' =='create' )
                get_to_link('{$get_url}/'+data);
                else
                    get_to_link('{$get_url}/'+$('#txt_ser').val());
                
            }else{
               danger_msg('تحذير..',data);
            }

        },"html");
    });
 
     });

///////////////////////////////////////////////////////////////////
    var btn__= '';
    $('#btn_adopt_0,#btn_adopt_10,#btn_adopt__10,#btn_adopt_20,#btn_adopt__20,#btn_adopt_30').click( function(){
        btn__ = $(this);
    });
///////////////////////////////////////////////////////////////////
    function adopt_(no){
       
        var msg= 'هل تريد الاعتماد ؟!';
      
        if(no==0) msg= '!!هل تريد بالتأكيد الغاء التقييم؟لا يمكن التراجع عن هذه العملية';
        if(no=='_10' || no=='_20' || no=='_30') msg= 'هل تريد بالتأكيد الغاء الاعتماد؟!';
        
        if(confirm(msg)){
            var values= {ser: "{$ser}"};
            
            get_data('{$adopt_url}'+no, values, function(ret){
                if(ret==1){
                    success_msg('رسالة','تمت العملية بنجاح..');
                    $('button').attr('disabled','disabled');

                       var sub= 'اعتماد تقييم {$customer_id_name}';
                        var text= 'يرجى اعتماد تقييم رقم {$customer_id_name}';
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
        
           var rep_url = '{$report_jasper_url}&report_type=pdf&report=suppliers_evals_details&p_id='+ser+'&sn={$report_sn}';
           _showReport(rep_url);
     }

         
            

</script>
SCRIPT;
    sec_scripts($scripts);
    ?>



