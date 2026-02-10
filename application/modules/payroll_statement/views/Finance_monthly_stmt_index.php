<?php

/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 03/09/2022
 * Time: 13:55
 */
$MODULE_NAME = 'payroll_statement';
$TB_NAME = 'Finance_monthly_stmt';
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$public_get_prev_month_url = base_url("$MODULE_NAME/$TB_NAME/public_get_prev_month_val");
$report_url = base_url("JsperReport/showreport?sys=hr/salaries");
?>
<!-- PAGE-HEADER -->
<div class="page-header">
    <div>
        <h1 class="page-title"><?= $title ?></h1>
    </div>
    <div class="ms-auto pageheader-btn">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">الرواتب</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= $title ?></li>
        </ol>
    </div>
</div>
<!-- PAGE-HEADER END -->
<!-- Row -->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><?= $title ?></h3>
                <div class="card-options">

                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-md-2">
                        <label>الشهر</label>
                        <input type="text" placeholder="الشهر" name="month" id="txt_month" class="form-control"
                               value="<?= date('Ym', strtotime('last month')) ?>">
                    </div>
                    <div class="form-group col-md-10 py-6">
                        <button type="button" onclick="javascript:search();" class="btn btn-primary">
                            <i class="fa fa-search"></i> إستعلام
                        </button>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" id="radio_pdf" name="type_rep" value="pdf" checked>
                            <label class="form-check-label" for="inlineRadio1">PDF</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" id="radio_xlsx" name="type_rep" value="xls">
                            <label class="form-check-label" for="inlineRadio2">XLSX</label>
                        </div>
                        <button type="button" onclick="javascript:print_width_rep();" class="btn btn-secondary">
                            <i class="fa fa-print"></i>
                            طباعة التقرير عرضي
                        </button>

                        <button type="button" onclick="javascript:print_height_rep();" class="btn btn-success">
                            <i class="fa fa-print"></i>
                            طباعة التقرير طولي
                        </button>
                    </div>
                </div>
                <hr>
                <div id="container">

                </div>

            </div>
        </div>
    </div>
</div>
<?php
$scripts = <<<SCRIPT
<script type="text/javascript">
 
   
    
   $('#txt_month').datetimepicker({
            format: 'YYYYMM',
            minViewMode: 'months',
            pickTime: false,
    });
     
  
   function search(){
        var month  = $('#txt_month').val();
        if (month == ''){
            warning_msg('يرجى ادخال الشهر لاكمال عملية الاستعلام');
            return -1;
        }else {
             get_data('{$get_page_url}', {month: month}, function (data) {
                   $('#container').html(data);
             }, 'html');
        }
    } //search
    //طباعة التقرير عرضي
     function print_width_rep(){
       var curr_month = have_no_val($('#txt_month').val());
       var prev_month = get_prev_month($('#txt_month').val());
       var type_rep = $("input:radio[name='type_rep']:checked").val();
       if (type_rep == 'pdf'){
        _showReport('{$report_url}&report_type=pdf&report=finance_total_salary_ls&p_month='+curr_month+'&p_prev_month='+prev_month+'');
       }else if (type_rep == 'xls') {
        _showReport('{$report_url}&report_type='+type_rep+'&report=finance_total_salary_ls_xls&p_month='+curr_month+'&p_prev_month='+prev_month+'');    
       }
     }
      //طباعة التقرير طولي
     function print_height_rep(){
       var curr_month = have_no_val($('#txt_month').val());
       var prev_month = get_prev_month($('#txt_month').val());
       var type_rep = $("input:radio[name='type_rep']:checked").val();
       if (type_rep == 'pdf'){
          _showReport('{$report_url}&report_type=pdf&report=finance_total_salary_pt&p_month='+curr_month+'&p_prev_month='+prev_month+'');
        }else if (type_rep == 'xls') {
          _showReport('{$report_url}&report_type='+type_rep+'&report=finance_total_salary_pt_xls&p_month='+curr_month+'&p_prev_month='+prev_month+'');
        }
     }
     
     
 
     // check if var have value or null //
    function have_no_val(v) {
        if(v == null) {
            return v = '';
        }else if(v == undefined) {
            return v = '';
        }else {
            return v;
        }
    }   
     
   function get_prev_month(month_in){
        if (month_in){
            var prev_month = false;
             $.ajax({
                async: false,
                url: '{$public_get_prev_month_url}',
                type: 'POST',
                data : {month:month_in},
                success: function (data) {
                    prev_month = data;
                }

            });
            return prev_month;
        }
    }
   
     
     
</script>
SCRIPT;
sec_scripts($scripts);
?>
