<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 14/12/14
 * Time: 12:15 م
 */

$report_path = base_url('/reports');
$report_url ='http://itdev:801/gfc.aspx?data='.get_report_folder().'&';
$report_url_jasper = base_url("JsperReport/showreport?sys=financial");

?>

<?= AntiForgeryToken() ?>
<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title?></div>

        <ul>
            <!--     <li><a href="#">بحث</a> </li>-->
        </ul>

    </div>

    <div class="form-body">

        <fieldset>
            <legend>تقارير </legend>
            <ul class="report-menu">

                <li><a class="btn blue"  data-report-source="1" data-option="" data-type="report" href="javascript:;"> المركز المالي </a> </li>
                <li><a class="btn blue"  data-report-source="4" data-option="" data-type="report" href="javascript:;">المركز المالي 2</a> </li>
                <li><a class="btn green" data-report-source="2" data-option="" data-type="report" href="javascript:;"> قائمة الدخل </a> </li>
                <li><a class="btn green" data-report-source="5" data-option="" data-type="report" href="javascript:;"> قائمة الدخل 2</a> </li>
                <li><a class="btn yellow" data-report-source="3" data-option="" data-type="report" href="javascript:;"> التدفق النقدي  </a> </li>
            </ul>
        </fieldset>

        <div class="modal fade" id="reportFilterModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title">معير البحث</h4>
                    </div>
                    <div class="form-horizontal">
                        <div class="modal-body">
						
				            <div class="form-group" id="branch">
				                <label class="col-sm-3 control-label">المقــر</label>
				
				                <div class="col-sm-3">
				                    <select name="report" class="form-control" id="dp_branch">
				                        <option></option>
				                        <?php foreach ($branches as $row) : ?>
				                            <option data-dept="<?= $row['NO'] ?>" value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
				                        <?php endforeach; ?>
				                    </select>
				                </div>
				            </div>
						
                            <div class="form-group" id="date1" >
                                <label class="col-sm-3 control-label"> التاريخ </label>
                                <div class="col-sm-4">
                                    <input type="text" data-type="date" data-date-format="DD/MM/YYYY" id="txt_date_1" class="form-control"/>
                                </div>
                            </div>

                            <div class="form-group" id="date2" >
                                <label class="col-sm-3 control-label"> حتى</label>
                                <div class="col-sm-4">
                                    <input type="text" data-type="date" data-date-format="DD/MM/YYYY" id="txt_date_2" class="form-control"/>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" data-action="report" class="btn btn-primary">عرض التقرير </button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>

                            </div>
                        </div>
                    </div>

                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

    </div>


</div>




<?php

$scripts = <<<SCRIPT
<script>

var selected = 0;
var data_report_source ='';

$(function () {

    $('a[data-type="report"]').click(function(){

        $('.rp_prm').hide();
        clearForm_any('#reportFilterModal');

        data_report_source = $(this).attr('data-report-source');

        $($(this).attr('data-option')).show();
        $('#reportFilterModal').modal();
    });


    $('button[data-action="report"]').click(function(){

        var url='';
        var f_date =$('#txt_date_1').val();
        var t_date =$('#txt_date_2').val();
        var branch =$('#dp_branch').val();

        switch(data_report_source){

            case '1':
               //url ='$report_url&report=FINANCIAL_CENTER_2&params[]='+f_date+'&params[]='+t_date+'&params[]=1';
               //url ='$report_url_jasper&report=FINANCIAL_CENTER_2&params[]='+f_date+'&params[]='+t_date+'&params[]=1';
               url  ='{$report_url_jasper}/financial&report=financial_center_accounts'+'&report_type=pdf'+'&p_branch='+branch+'&p_from_date='+f_date+'&p_to_date='+t_date+'&p_account_type=1';


              break;
            case '2':
               //url ='$report_url&report=FINANCIAL_CENTER&params[]='+f_date+'&params[]='+t_date+'&params[]=2';
				url  ='{$report_url_jasper}/financial&report=financial_center_accounts3'+'&report_type=pdf'+'&p_branch='+branch+'&p_from_date='+f_date+'&p_to_date='+t_date+'&p_account_type=2';

              break;
            case '3':
               url ='$report_url&report=FINANCIAL_CASH_FLOW&params[]='+f_date+'&params[]='+t_date+'';
              break;
			  
			case '4':
   
               url  ='{$report_url_jasper}/financial&report=financial_center_accounts_son'+'&report_type=pdf'+'&p_branch='+branch+'&p_from_date='+f_date+'&p_to_date='+t_date+'&p_account_type=1';
				break;
				
			case '5':
   
               url  ='{$report_url_jasper}/financial&report=financial_center_accounts_3_son'+'&report_type=pdf'+'&p_branch='+branch+'&p_from_date='+f_date+'&p_to_date='+t_date+'&p_account_type=2';
				break;
            }

        _showReport(url);

    });
});


function select_account(id,name,curr){
    selected = id;


    $('#menuModal').modal();
}

</script>

SCRIPT;

sec_scripts($scripts);

?>

