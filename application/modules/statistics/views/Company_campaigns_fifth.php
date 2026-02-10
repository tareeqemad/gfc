<?php

/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 22/05/22
 * Time: 09:45 ص
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');

$MODULE_NAME= 'statistics';
$TB_NAME= 'Company_campaigns';

$isCreate =isset($master_tb_data) && count($master_tb_data)  > 0 ?false:true;
$HaveRs = (!$isCreate)? true:false;
$rs=$isCreate ?array() : $master_tb_data[0];

?>

<?= AntiForgeryToken(); ?>

<div class="row">

    <div class="form-body">


        <fieldset>
            <legend>معايير البحث</legend>

            <div class="modal-body inline_form">

                <div class="form-group rp col-sm-1">
                    <label class="control-label">رقم الاشتراك</label>
                    <div>
                        <input type="text" name="sub_no_1" id="txt_sub_no_1" class="form-control" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))">
                    </div>
                </div>

                <div class="form-group rp col-sm-2" id="from_date" >
                    <label class="control-label">من تاريخ</label>
                    <div>
                        <input type="text" name="date" id="txt_from_date_4"  data-type="date" data-date-format="YYYYMM" class="form-control">
                    </div>
                </div>

                <div class="form-group rp col-sm-2" id="to_date" >
                    <label class="control-label">إلى تاريخ</label>
                    <div>
                        <input type="text" name="date" id="txt_to_date_4"  data-type="date" data-date-format="YYYYMM" class="form-control">
                    </div>
                </div>

                <br/><br/>

                <div class="form-group rp col-sm-2 op" id="rep_type">
                    <label class="control-label">نوع التقرير</label>
                    <div>
                        <input type="radio"  name="rep_type_id" value="pdf" checked="checked">
                        <i class="fa fa-file-pdf-o" style="font-size:28px;color:#e2574c"></i>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="radio"  name="rep_type_id" value="xls">
                        <i class="fa fa-file-excel-o" style="font-size:28px;color:#1d7044"></i>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                    </div>
                </div>

            </div>

        </fieldset>

        <div class="modal-footer">
            <button type="button" onclick="javascript:print_report_6();" class="btn btn-success">عرض التقرير<span class="glyphicon glyphicon-print"></span></button>
            <button type="button" onclick="javascript:clear_form();"  class="btn btn-default">إفراغ الحقول</button>
        </div>

    </div>

</div>

<script>

    $(function(){

        $('#txt_from_date_4,#txt_to_date_4').datetimepicker({
            format: 'YYYYMM',
            minViewMode: "months",
            pickTime: false
        });

    });

</script>

