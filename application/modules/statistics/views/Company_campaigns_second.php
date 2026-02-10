<?php

/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 28/03/22
 * Time: 09:30 ص
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');

$MODULE_NAME= 'statistics';
$TB_NAME= 'Company_campaigns';

$isCreate =isset($master_tb_data) && count($master_tb_data)  > 0 ?false:true;
$HaveRs = (!$isCreate)? true:false;
$rs=$isCreate ?array() : $master_tb_data[0];

$report_url = base_url("JsperReport/showreport?sys=financial");
$date_attr= " data-type='date' data-date-format='DD/MM/YYYY' data-val='true' data-val-regex-pattern='".date_format_exp()."' data-val-regex='Error' ";

?>

<?= AntiForgeryToken(); ?>

<div class="row">

    <div class="form-body">


        <fieldset>
            <legend>معايير البحث</legend>

            <div class="modal-body inline_form">

                <div class="form-group rp col-sm-2 op" id="dp_branch_id_div"  >
                    <label class="control-label">المقر </label>
                    <div>
                        <select name="branch_id" id="dp_branch_id_2" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($branches as $row) :?>
                                <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>

                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">نوع الحملة</label>
                    <div>
                        <select name="campaign_type" id="dp_campaign_type" class="form-control " required>
                            <option value="">_________</option>
                            <?php foreach($campaign_type as $row) :?>
                                <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">نوع السداد</label>
                    <div>
                        <select name="repayment_type" id="dp_repayment_type" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($repayment_type as $row) :?>
                                <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group rp col-sm-1 op" id="from_file_id" >
                    <label class="control-label">رقم الهوية</label>
                    <div>
                        <input type="text"  name="identification_no"  id="txt_identification_no" class="form-control" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))">
                    </div>
                </div>

                <div class="form-group rp col-sm-1 op" id="from_file_id" style="display:none;" >
                    <label class="control-label">رقم الاشتراك</label>
                    <div>
                        <input type="text"  name="subscribe_no"  id="txt_subscribe_no" class="form-control">
                    </div>
                </div>


                <div class="form-group rp col-sm-2 op" id="from_file_id" >
                    <label class="control-label">رقم الاشتراك الذي سيمنح عليه الخصم</label>
                    <div>
                        <input type="text"  name="subscribe_no_disc"  id="txt_subscribe_no_disc" class="form-control">
                    </div>
                </div>

                <div class="form-group rp col-sm-1 op" id="from_file_id" >
                    <label class="control-label">نسبة الخصم %</label>
                    <div>
                        <input type="text" name="discount"  id="txt_discount"  class="form-control " maxlength="3" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))">
                    </div>
                </div>

                <div class="form-group rp col-sm-1 op" id="from_month_id" >
                    <label class="control-label">الشهر</label>
                    <div>
                        <input type="text" name="date" id="txt_month" value="202203" class="form-control">
                    </div>
                </div>

                <div style="clear: both"></div>

                <div class="form-group rp col-sm-2 op" id="from_date" >
                    <label class="control-label">من تاريخ</label>
                    <div>
                        <input type="text" data-type="date" name="from_date" data-date-format="DD/MM/YYYY" id="txt_from_date_2" class="form-control" required>
                    </div>
                </div>

                <div class="form-group rp col-sm-2 op" id="to_date" >
                    <label class="control-label">إلى تاريخ</label>
                    <div>
                        <input type="text" data-type="date" name="to_date" data-date-format="DD/MM/YYYY" id="txt_to_date_2" class="form-control" required>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">انتهاء طلب معاملة خصم</label>
                    <div>
                        <select name="finish_status" id="dp_finish_status" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($finish_status as $row) :?>
                                <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">معتمد للتنفيذ؟</label>
                    <div>
                        <select name="posting_status" id="dp_posting_status" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($posting_status as $row) :?>
                                <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>


                <div class="form-group col-sm-2">
                    <label class="control-label">شروط العرض</label>
                    <div>
                        <select name="conditions_type" id="dp_conditions_type" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($conditions_type as $row) :?>
                                <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">طريقة الدفع</label>
                    <div>
                        <select name="payment_method" id="dp_payment_method" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($payment_method as $row) :?>
                                <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div style="clear: both"></div>

                <div class="form-group rp col-sm-2 op" id="rep_type">
                    <label class="control-label">نوع التقرير</label>
                    <div>
                        <input type="radio"  name="rep_type_id_2" value="pdf" checked="checked">
                        <i class="fa fa-file-pdf-o" style="font-size:28px;color:#e2574c"></i>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="radio"  name="rep_type_id_2" value="xls">
                        <i class="fa fa-file-excel-o" style="font-size:28px;color:#1d7044"></i>
                    </div>
                </div>

            </div>
        </fieldset>

        <div class="modal-footer">
            <button type="button" onclick="javascript:print_report_2();" class="btn btn-success">عرض التقرير التفصيلي <span class="glyphicon glyphicon-print"></span></button>
            <button type="button" onclick="javascript:print_report_3();" class="btn btn-success">عرض التقرير الاجمالي<span class="glyphicon glyphicon-print"></span></button>
            <button type="button" onclick="javascript:clear_form();"  class="btn btn-default">إفراغ الحقول</button>
        </div>

    </div>

</div>

<script>
    $(function(){

    $('#txt_from_date_2,#txt_to_date_2').datetimepicker({
        format: 'dd/MM/YYYY',
        pickTime: false
    });

});
</script>

