<?php

/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 05/09/2022
 * Time: 10:57
 */
$MODULE_NAME = 'payroll_statement';
$TB_NAME = 'By_con_monthly_stmt';
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$get_get_diff_emp_url = base_url("$MODULE_NAME/$TB_NAME/public_get_diff_emp");
$get_export_diff_emp_url = base_url("$MODULE_NAME/$TB_NAME/public_get_export_diff_emp");

$get_diff_sal_url = base_url("$MODULE_NAME/$TB_NAME/public_get_diff_sal");
$get_exportDiffdata_url = base_url("$MODULE_NAME/$TB_NAME/public_get_exportDiffdata");
//for con overtime
$get_diff_sal_overtime_url = base_url("$MODULE_NAME/$TB_NAME/public_get_diff_sal_overtime");
$get_exportDiffOvertime_url = base_url("$MODULE_NAME/$TB_NAME/public_get_exportDiffOvertime");

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
                        <label> المقر</label>
                        <select name="branch_no" id="dp_branch_no" class="form-control">
                            <option value="">جميع المقرات</option>
                            <?php foreach ($branches as $row) : ?>
                                <option value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                        <input type="hidden" id="h_branch_no" value="">
                    </div>
                    <div class="form-group col-md-2">
                        <label>الشهر</label>
                        <input type="text" placeholder="الشهر" name="month" id="txt_month" class="form-control"
                               value="<?= date('Ym', strtotime('last month')) ?>">
                    </div>
                    <div class="form-group col-md-8 py-6">
                        <button type="button" onclick="javascript:search();" class="btn btn-primary">
                            <i class="fa fa-search"></i>
                            إستعلام
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

    <!-- Modal -->
    <div class="modal fade"  id="DetailModal" tabindex="-1" role="dialog" >
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">بيانات</h5>
                    <button  class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body" id="public_modal_body">

                </div>
                <div class="modal-footer">
                    <button  class="btn btn-secondary" data-bs-dismiss="modal">اغلاق</button>
                </div>
            </div>
        </div>
    </div>
    <!--Start DetailDiffModal -->
    <div class="modal fade"  id="DetailDiffModal" tabindex="-1" role="dialog" >
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">بيانات</h5>
                    <button  class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body" id="diff_emp_body">

                </div>
                <div class="modal-footer">
                    <button  class="btn btn-secondary" data-bs-dismiss="modal">اغلاق</button>
                </div>
            </div>
        </div>
    </div>
    <!--End DetailDiffModal -->
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
        var branch_no  = $('#dp_branch_no').val();
        $('#h_branch_no').val('');
        if (month == ''){
            warning_msg('يرجى ادخال الشهر لاكمال عملية الاستعمال');
            return -1;
        }else {
            $('#h_branch_no').val(branch_no);
             get_data('{$get_page_url}', {month: month,branch_no:branch_no}, function (data) {
                   $('#container').html(data);
             }, 'html');
        }
   } //search
   
   function  get_diff_sal_val(month,con_no){
       var branch_no = $('#h_branch_no').val();
       if (con_no != 10){
           showLoading();
          // Display Modal
          $('#DetailModal').modal('show');
          $.ajax({
             url: '{$get_diff_sal_url}',
             type: 'post',
             data: {month: month,con_no:con_no,branch_no:branch_no},
             success: function(response){
                 // Add response in Modal body
                 $('#public_modal_body').html(response);
             },
              complete: function() {
                  HideLoading();
             }
         });
       } else{
           showLoading();
             // Display Modal
          $('#DetailModal').modal('show');
          $.ajax({
             url: '{$get_diff_sal_overtime_url}',
             type: 'post',
             data: {month: month,con_no:con_no,branch_no:branch_no},
             success: function(response){
                 // Add response in Modal body
                 $('#public_modal_body').html(response);
             },
             complete: function() {
                  HideLoading();
             }
         });
       }
   }
   
   //تقرير البنود
   function exportDiffSalaryItemXlsx(month,con_no,branch_no){
        var fewSeconds = 15;
        $('#btn_diff_xlsx').prop('disabled', true);
            info_msg('تنبيه','جاري تجهيز ملف الاكسل ..'); 
            location.href = '{$get_exportDiffdata_url}?month='+have_no_val(month)+'&con_no='+have_no_val(con_no)+'&branch_no='+have_no_val(branch_no);
            setTimeout(function(){
                $('#btn_diff_xlsx').prop('disabled', false);
                info_msg('تنبيه','جاري تجهيز ملف الاكسل ..'); 
        }, fewSeconds*1000);
   }
   function exportDiffSalaryItemPdf(curr_month,prev_month,con_no,branch_no){
       _showReport('{$report_url}&report_type=pdf&report=diff_salary_items&p_month='+have_no_val(curr_month)+'&p_prev_month='+have_no_val(prev_month)+'&p_con_no='+have_no_val(con_no)+'&p_branch='+have_no_val(branch_no)+'');
   }
   
    //تقرير الفرق في الوفت الاضافي
   function exportDiffOverTimeXlsx(month,con_no,branch_no){
         var fewSeconds = 15;
        $('#btn_export_overtime_diff_').prop('disabled', true);
            info_msg('تنبيه','جاري تجهيز ملف الاكسل ..'); 
            location.href = '{$get_exportDiffOvertime_url}?month='+have_no_val(month)+'&con_no='+have_no_val(con_no)+'&branch_no='+have_no_val(branch_no);
            setTimeout(function(){
                $('#btn_export_overtime_diff').prop('disabled', false);
                info_msg('تنبيه','جاري تجهيز ملف الاكسل ..'); 
        }, fewSeconds*1000);
   }
   function exportDiffOverItemPdf(curr_month,prev_month,con_no,branch_no){
       _showReport('{$report_url}&report_type=pdf&report=diff_salary_overtime&p_month='+have_no_val(curr_month)+'&p_prev_month='+have_no_val(prev_month)+'&p_branch='+have_no_val(branch_no)+'&p_con_no='+have_no_val(con_no)+'');
   }
 
   function  get_diff_emp(month){
        var branch_no  = $('#dp_branch_no').val();
        showLoading();
        $('#DetailDiffModal').modal('show');
        $.ajax({
           url: '{$get_get_diff_emp_url}',
           type: 'post',
           data: {month: month,branch_no:branch_no},
           success: function(response){
               // Add response in Modal body
               $('#diff_emp_body').html(response);
           },
           complete: function() {
                 HideLoading();
            }
       });
   }
   
   function exportDiffEmpDataXlsx(month,branch){
         var fewSeconds = 15;
        $('#btn_export_emp_diff').prop('disabled', true);
            info_msg('تنبيه','جاري تجهيز ملف الاكسل ..'); 
            location.href = '{$get_export_diff_emp_url}?month='+have_no_val(month)+'&branch='+have_no_val(branch);
            setTimeout(function(){
                $('#btn_export_emp_diff').prop('disabled', false);
                info_msg('تنبيه','جاري تجهيز ملف الاكسل ..'); 
        }, fewSeconds*1000);
   }
   function exportDiffEmpDataPdf(month,branch){
       _showReport('{$report_url}&report_type=pdf&report=diff_emp&p_month='+have_no_val(month)+'&p_branch='+have_no_val(branch)+'');
   }
   
       //طباعة التقرير عرضي
     function print_width_rep(){
       var branch_no = have_no_val($('#dp_branch_no').val());
       var curr_month = have_no_val($('#txt_month').val());
       var prev_month = get_prev_month($('#txt_month').val());
       var type_rep = $("input:radio[name='type_rep']:checked").val();
       if (type_rep == 'pdf'){
           _showReport('{$report_url}&report_type='+type_rep+'&report=all_salary_items_ls&p_month='+curr_month+'&p_prev_month='+prev_month+'&p_branch='+branch_no+'');
       }else if (type_rep == 'xls') {
          _showReport('{$report_url}&report_type='+type_rep+'&report=all_salary_items_ls_xls&p_month='+curr_month+'&p_prev_month='+prev_month+'&p_branch='+branch_no+'');
       }
     }
      //طباعة التقرير طولي
     function print_height_rep(){
       var branch_no = have_no_val($('#dp_branch_no').val());
       var curr_month = have_no_val($('#txt_month').val());
       var prev_month = get_prev_month($('#txt_month').val());
       var type_rep = $("input:radio[name='type_rep']:checked").val();
       if (type_rep == 'pdf'){
         _showReport('{$report_url}&report_type='+type_rep+'&report=all_salary_items_pt&p_month='+curr_month+'&p_prev_month='+prev_month+'&p_branch='+branch_no+'');
       }else if (type_rep == 'xls') {
         _showReport('{$report_url}&report_type='+type_rep+'&report=all_salary_items_ls_xls&p_month='+curr_month+'&p_prev_month='+prev_month+'&p_branch='+branch_no+'');
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
     
</script>
SCRIPT;
sec_scripts($scripts);
?>
