<?php

/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 28/03/22
 * Time: 09:20 ص
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');

$MODULE_NAME= 'statistics';
$TB_NAME= 'Company_campaigns';

$isCreate =isset($master_tb_data) && count($master_tb_data)  > 0 ?false:true;
$HaveRs = (!$isCreate)? true:false;
$rs=$isCreate ?array() : $master_tb_data[0];

$date_attr= " data-type='date' data-date-format='DD/MM/YYYY' data-val='true' data-val-regex-pattern='".date_format_exp()."' data-val-regex='Error' ";


?>

<?= AntiForgeryToken(); ?>

<div class="row">

    <div class="form-body">

        <fieldset>
            <legend>معايير البحث</legend>

            <div class="modal-body inline_form">

                <div class="form-group rp col-sm-2" id="dp_branch_id_div">
                    <label class="control-label">المقر </label>
                    <div>
                        <select name="branch_id" id="dp_branch_id" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($branches as $row) :?>
                                <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group rp col-sm-2" id="dp_repayment_div">
                    <label class="control-label">آلية السداد</label>
                    <div>
                        <select data-val="true" name="repayment" id="dp_repayment" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach ($repayment as $row) : ?>
                                <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group rp col-sm-1" id="txt_bill_div" >
                    <label class="control-label">دورة فاتورة</label>
                    <div>
                        <input type="text" name="bill"  id="txt_bill" class="form-control" >
                    </div>
                </div>

                <div class="form-group rp col-sm-1" id="txt_subscribe_no_div">
                    <label class="control-label">رقم الاشتراك</label>
                    <div>
                        <input type="text"  name="subscribe_no_1"  id="txt_subscribe_no_1" class="form-control">
                    </div>
                </div>

                <div class="form-group rp col-sm-2" id="from_date" >
                    <label class="control-label">إحتساب الشحنات من تاريخ</label>
                    <div>
                        <input type="text" name="from_date" <?=$date_attr?> id="txt_from_date" class="form-control" required>
                    </div>
                </div>

                <div class="form-group rp col-sm-2" id="to_date" >
                    <label class="control-label">إلى تاريخ</label>
                    <div>
                        <input type="text" data-type='date' data-date-format='DD/MM/YYYY' data-val='true'   data-val-regex='Error'  name="to_date"  id="txt_to_date" class="form-control" required />
                    </div>
                </div>

                <br/><br/>

                <div class="form-group rp col-sm-2" id="rep_type">
                    <label class="control-label">نوع التقرير</label>
                    <div>
                        <input type="radio"  name="rep_type_id" value="pdf" checked="checked">
                        <i class="fa fa-file-pdf-o" style="font-size:28px;color:#e2574c"></i>

                        <input type="radio"  name="rep_type_id" value="xls">
                        <i class="fa fa-file-excel-o" style="font-size:28px;color:#1d7044"></i>
                    </div>
                </div>

            </div>

        </fieldset>

        <div class="modal-footer">
            <button type="button" onclick="javascript:print_report_totals_1();" class="btn btn-success">عرض التقرير الإجمالي<span class="glyphicon glyphicon-print"></span></button>
            <button type="button" onclick="javascript:print_report_details_1();" class="btn btn-success">عرض التقرير التفصيلي<span class="glyphicon glyphicon-print"></span></button>
            <button type="button" onclick="javascript:clear_form();"  class="btn btn-default">إفراغ الحقول</button>
        </div>

    </div>

</div>

<script>

$(function(){

    $('#txt_from_date,#txt_to_date').datetimepicker({
        format: 'dd/MM/YYYY',
        pickTime: false
    });

    $('#txt_bill').datetimepicker({
        format: 'YYYYMM',
        minViewMode: "months",
        pickTime: false
    });

});

</script>

