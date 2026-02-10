<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

$MODULE_NAME = 'purchases';
$TB_NAME = 'Extract';
$DET_TB_NAME = '';

$orders_details_url = base_url("$MODULE_NAME/$TB_NAME/public_get_order_extract_details");
$prev_extract_url = base_url("$MODULE_NAME/$TB_NAME/public_get_prev_extract_details");

if (!isset($result)) $result = array();
$HaveRs = count($result) > 0;

$rs = $HaveRs ? $result[0] : $result;

$class_input_id = $this->uri->segment(4);

echo AntiForgeryToken();
?>


<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>
        <ul>
            <?php //HaveAccess($back_url)
            if (TRUE): ?>
                <li><a href="<?= $back_url ?>?order_purpose=<?= $order_purpose ?>"><i class="icon icon-reply"></i> </a>
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
                            <input type="text" value="<?= $HaveRs ? $rs['SER'] : "" ?>" name="extract_id"
                                   id="txt_extract_id" class="form-control" readonly/>
                        </div>
                    </div>
                    <div class="form-group col-sm-1">
                        <label class="control-label">مستخلص رقم</label>
                        <div>
                            <input type="text" value="<?= $HaveRs ? $rs['EXTRACT_SER'] : "" ?>" name="extract_id_cntr"
                                   id="txt_extract_id_cntr" class="form-control" readonly/>
                        </div>
                    </div>
                    <div class="form-group col-sm-1">
                        <label class="control-label">عملة المستخلص</label>
                        <div>
                            <input type="hidden" name="extract_curr_id"
                                   value="<?= $HaveRs ? $rs['EXTRACT_CURR_ID'] : "" ?>"
                                   id="txt_extract_curr_id">
                            <input type="text" readonly value="<?= $HaveRs ? $rs['EXTRACT_CURR_ID_NAME'] : "" ?>"
                                   name="extract_curr_id_name"
                                   id="txt_extract_curr_id_name" class="form-control"/>

                        </div>
                    </div>

                </fieldset>
                <hr/>

                <fieldset>
                    <legend> بيانات طلب الشراء</legend>
                    <div class="form-group col-sm-1">
                        <label class="control-label">رقم المسلسل </label>
                        <div>
                            <input type="text" value="<?= $HaveRs ? $rs['PURCHASE_ID'] : "" ?>"
                                   name="purchase_order_id"
                                   id="txt_purchase_order_id" class="form-control"/>
                        </div>
                    </div>
                    <div class="form-group col-sm-1">
                        <label class="control-label">رقم طلب الشراء </label>
                        <div>
                            <input type="text" readonly value="<?= $HaveRs ? $rs['PURCHASE_TEXT'] : "" ?>"
                                   name="purchase_order_num"
                                   id="txt_purchase_order_num" class="form-control"/>
                        </div>
                    </div>
                    <div class="form-group col-sm-1">
                        <label class="control-label"> عملة عرض السعر </label>
                        <div>
                            <input type="hidden" name="curr_id" value="<?= $HaveRs ? $rs['PURCHASE_CURR_ID'] : "" ?>"
                                   id="dp_curr_id">
                            <input type="text" readonly value="<?= $HaveRs ? $rs['PURCHASE_CURR_ID_NAME'] : "" ?>"
                                   name="curr_id_name"
                                   id="txt_curr_id_name" class="form-control"/>

                        </div>
                    </div>
                    <div class="form-group col-sm-1">
                        <label class="control-label">نوع الطلب</label>
                        <div>
                            <input type="text" readonly value="<?= $HaveRs ? $rs['PURCHASE_TYPE_NAME'] : "" ?>"
                                   name="purchase_type_name"
                                   id="txt_purchase_type_name" class="form-control"/>

                        </div>
                    </div>
                    <div class="form-group col-sm-9">
                        <label class="control-label">بيان الطلب</label>
                        <div>
                            <input type="text" readonly value="<?= $HaveRs ? $rs['PURCHASE_NOTES'] : "" ?>"
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
                            <input type="text" readonly value="<?= $HaveRs ? $rs['ORDER_ID'] : "" ?>" name="order_id"
                                   id="txt_order_id" class="form-control"/>

                            <input type="hidden" value="<?= $HaveRs ? $rs['ORDER_ID'] : "" ?>" name="order_id"
                                   id="h_order_id">

                        </div>
                    </div>

                    <div class="form-group col-sm-1">
                        <label class="control-label"> مسلسل التوريد </label>
                        <div>
                            <input type="text" readonly value="<?= $HaveRs ? $rs['ORDER_ID_TEXT'] : "" ?>"
                                   name="order_text_t"
                                   id="txt_order_text_t" class="form-control"/>

                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">رقم هوية أو المشتغل المرخص للمورد</label>
                        <div>
                            <input type="text" name="customer_id" value="<?= $HaveRs ? $rs['CUSTOMER_ID'] : "" ?>"
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
                                   value="<?= $HaveRs ? $rs['CUSTOMER_ID_NAME'] : "" ?>">

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
                                   value="<?= $HaveRs ? $rs['CUST_CURR_ID_NAME'] : "" ?>" id="txt_cust_curr_id"
                                   readonly
                                   data-val-required="حقل مطلوب" class="form-control">
                            <input type="hidden" name="cust_cur" value="<?= $HaveRs ? $rs['CUST_CURR_ID'] : "" ?>"
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
                        echo modules::run("$MODULE_NAME/$TB_NAME/$orders_details_url", $HaveRs ? $rs['ORDER_ID'] : "", $HaveRs ? $rs['CLASS_INPUT_ID'] : "", $HaveRs ? $rs['CUST_CURR_ID_NAME'] : "", $HaveRs ? $rs['DISCOUNT'] : 0);
                        ?>


                    </div>
                </fieldset>

                <hr/>


                <fieldset>
                    <legend>اجمالي ما تم تنفيذه</legend>
                    <?php
                    echo modules::run("$MODULE_NAME/$TB_NAME/$prev_extract_url", $HaveRs ? $rs['ORDER_ID'] : "", $HaveRs ? $rs['CLASS_INPUT_ID'] : "", $HaveRs ? $rs['CUSTOMER_CURR_ID_NAME'] : "");
                    ?>

                </fieldset>


            </div>

            <div class="modal-footer">
                <?php if ((HaveAccess($create_url) || HaveAccess($edit_url)) && (($adopt == 1) || ($adopt == 0))) : ?>
                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                <?php endif; ?>
                <?php if ($isCreate): ?>
                    <button type="button" onclick="clear_form();" class="btn btn-default"> تفريغ الحقول
                    </button>
                <?php endif; ?>
                <button class="btn btn-warning dropdown-toggle"
                        onclick="$('#orders_detailTbl').tableExport({type:'excel',escape:'false'});"
                        data-toggle="dropdown"><i class="fa fa-bars"></i> تصدير
                </button>

            </div>

        </form>
    </div>
</div>

<?php
$scripts = <<<SCRIPT
<script type="text/javascript">
calcall();   
function calcall() {
    var total_total_price_class = $('#txt_total_total_price_class').text();
    var discount = $('#txt_discount').val();
    $('#txt_net_total').text(Number(total_total_price_class)-Number(discount));
    var net_total = $('#txt_net_total').text();
    var prev_extract = $('#txt_prev_extract').text();
    $('#txt_pay').text(Number(net_total)-Number(prev_extract));
    
    
    
    }
    
       
            $('#txt_discount').change(function (e) {
                calcall();                
            });
  

         
       
</script>
SCRIPT;
sec_scripts($scripts);
?>
