<?php
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 12/04/16
 * Time: 10:09 ص
 */
$MODULE_NAME= 'purchases';
$TB_NAME1= 'purchase_order';
$TB_NAME= 'suppliers_offers';

$print_url =  base_url('/reports');


$rs= $purchase_data[0];
$curr_id=isset($rs['CURR_ID'])? $rs['CURR_ID'] :0;
$curr_id_name=isset($rs['CURR_ID_NAME'])? $rs['CURR_ID_NAME'] :'';
$purchase_order_id=isset($rs['PURCHASE_ORDER_ID'])? $rs['PURCHASE_ORDER_ID'] :'';
$order_purpose=isset($rs['ORDER_PURPOSE'])? $rs['ORDER_PURPOSE'] :'';
$committee_case=isset($rs['COMMITTEE_CASE'])? $rs['COMMITTEE_CASE'] :0;
$purchase_notes=isset($rs['PURCHASE_NOTES'])? $rs['PURCHASE_NOTES'] :'';
$purchase_type_name=isset($rs['PURCHASE_TYPE_NAME'])? $rs['PURCHASE_TYPE_NAME'] :'';
$purchase_order_num=isset($rs['PURCHASE_ORDER_NUM'])? $rs['PURCHASE_ORDER_NUM'] :'';

$adopt4_url=base_url("$MODULE_NAME/$TB_NAME1/committee_adopt4_$order_purpose");
$adopt3_url=base_url("$MODULE_NAME/$TB_NAME1/committee_adopt3_$order_purpose");
$adopt2_url=base_url("$MODULE_NAME/$TB_NAME1/committee_adopt2_$order_purpose");
$submit_url=base_url("$MODULE_NAME/$TB_NAME1/edit_envelope_$order_purpose");

$back_url=base_url("$MODULE_NAME/purchase_order/index/1/1/2?order_purpose=$order_purpose");

if($order_purpose==1)
    $DET_TB_NAME= 'public_get_all_envelope_details';
elseif($order_purpose==2)
    $DET_TB_NAME= 'public_get_all_envelope_items';
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


                    </fieldset><hr/>
                    <fieldset  >
                        <legend> كشوف التفريغ  </legend>
                        <div style="width:1200px;clear: both;overflow: auto"  class="div_width" id="classes" > <!-- <input type="hidden" id="h_data_search" />-->
                            <?php
                            echo  modules::run("$MODULE_NAME/$TB_NAME/$DET_TB_NAME", $purchase_order_id ); ?>

                        </div>
                    </fieldset>

                    <hr/>
                    <fieldset  >
                        <legend>الملاحظات على الموردين</legend>
                        <div style="width:1200px;clear: both;overflow: auto"  > <!-- <input type="hidden" id="h_data_search" />-->
                            <?php
                            echo  modules::run("$MODULE_NAME/$TB_NAME/public_get_offer_notes", $purchase_order_id ); ?>

                        </div>
                    </fieldset>
                </div>
                <hr/>

                <div class="modal-footer">



                    <button class="btn btn-warning dropdown-toggle" onclick="$('#suppliers_offers_detTbl').tableExport({type:'excel',escape:'false'});" data-toggle="dropdown"><i class="fa fa-bars"></i> تصدير</button>
                    <button class="btn btn-warning dropdown-toggle" onclick="$('#suppliers_offers_notesTbl').tableExport({type:'excel',escape:'false'});" data-toggle="dropdown"><i class="fa fa-bars"></i> تصدير الملاحظات</button>

                </div>
            </form>
        </div>
    </div>

<?php

$shared_js = <<<SCRIPT



$(document).ready(function() {
var c_w= $(window).width() - 80 ;
    $('div.div_width').css('width',c_w+'px');

});






SCRIPT;

$edit_script = <<<SCRIPT


<script>
  {$shared_js}
  </script>
SCRIPT;
sec_scripts($edit_script);