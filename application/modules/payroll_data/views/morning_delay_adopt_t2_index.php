<?php

/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 27/04/20
 * Time: 10:01 ص
 */
$MODULE_NAME = 'payroll_data';
$TB_NAME = 'morning_delay_adopt';
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page"); //جلب التاخير الصباحي

$adopt_all_url = base_url("$MODULE_NAME/$TB_NAME/public_adopt");
$unadopt_all_url = base_url("$MODULE_NAME/$TB_NAME/public_unadopt");
$adopt_detail_url = base_url("$MODULE_NAME/$TB_NAME/public_get_adopt_detail");
//اعتماد المدير المالي
$ChiefFinancial = base_url("$MODULE_NAME/$TB_NAME/ChiefFinancial");
//اعتماد المراقب الداخلي
$InternalObserver = base_url("$MODULE_NAME/$TB_NAME/InternalObserver");
//اعتماد مدير المقر
$HeadOffice = base_url("$MODULE_NAME/$TB_NAME/HeadOffice");
//اعتماد المالية للصرف
$FinancialAdopt = base_url("$MODULE_NAME/$TB_NAME/FinancialToPay");
//الغاء لااعتماد
$CancelAdopt = base_url("$MODULE_NAME/$TB_NAME/Return_to_hr");
//حالة الاعتماد
$agree_ma = intval($agree_ma);

$adopt_detail_url = base_url("$MODULE_NAME/morning_delay/public_get_adopt_detail");


$dealyemp_list_url = base_url("$MODULE_NAME/$TB_NAME/public_get_page_dealyemp");

$get_excel_adopt_financial_url = base_url("$MODULE_NAME/$TB_NAME/excel_adopt_financial_report");
$report_url = base_url("JsperReport/showreport?sys=hr/salaries");
?>
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="left-content">
        <span class="main-content-title mg-b-0 mg-b-lg-1">كشف التأخير الصباحي المعتمد مالياَ للخصم</span>
    </div>
    <div class="justify-content-center mt-2">
        <ol class="breadcrumb">
            <li class="breadcrumb-item tx-15"><a href="javascript:void(0);">الحركات</a></li>
            <li class="breadcrumb-item active" aria-current="page">التاخير الصباحي المعتمد مالياً</li>
        </ol>
    </div>
</div>
<!-- /breadcrumb -->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex py-3">
                <div class="mb-0 flex-grow-1 card-title">
                    <?= $title ?>
                </div>
                <div class="flex-shrink-0">

                </div>
            </div><!-- end card header -->
            <div class="card-body">

                <form id="<?= $TB_NAME ?>_t2_form">
                    <div class="row">
                        <?php if ($this->user->branch == 1) { ?>
                            <div class="form-group col-md-2">
                                <label> المقر</label>
                                <select name="t2_branch_no" id="dp_t2_branch_no" class="form-control sel2">
                                    <option value="">_______</option>
                                    <?php foreach ($branches as $row) : ?>
                                        <option <?= ($this->user->branch == $row['NO'] ? 'selected="selected"' : '') ?> value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <?php } else { ?>
                            <input type="hidden" name="t2_branch_no" id="dp_t2_branch_no"
                                   value="<?= $this->user->branch ?>">
                        <?php } ?>
                        <div class="form-group col-md-3">
                            <label>الموظف</label>
                            <select name="t2_emp_no" id="dp_t2_emp_no" class="form-control sel2">
                                <option value="">_________</option>
                                <?php foreach ($emp_no_cons as $row) : ?>
                                    <option value="<?= $row['EMP_NO'] ?>"><?= $row['EMP_NO'] . ': ' . $row['EMP_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label>شهر الاحتساب</label>
                            <input type="text" placeholder="شهر الاحتساب" name="t2_month" id="t2_month"
                                   class="form-control"
                                   value="<?= date('Ym') ?>">
                        </div>

                        <?php if ($this->user->branch == 1) { ?>
                            <div class="form-group col-md-1">
                                <label>تجميع حسب</label>
                                <div class="checkbox">
                                    <div class="custom-checkbox custom-control">
                                        <input type="checkbox" data-checkboxes="mygroup" class="custom-control-input"
                                               id="checkbox-1" name="group_by_branch" value="1">
                                        <label for="checkbox-1" class="custom-control-label mt-1">المقر</label>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>


                    </div>
                    <div class="flex-shrink-0">
                        <button type="button" onclick="javascript:searchDealyemp();" class="btn btn-primary">
                            <i class="fa fa-search"></i> إستعلام
                        </button>
                        <button type="button" onclick="ExcelData()"
                                class="btn btn-success">
                            <i class="fa fa-file-excel-o"></i>
                            إكسل
                        </button>
                        <button type="button" onclick="javascript:print_report();"
                                class="btn btn-blue">
                            <i class="fa fa-print"></i>
                            طباعة
                        </button>
                        <button type="button" onclick="javascript:clear_form();" class="btn btn-cyan-light"><i
                                    class="fa fa-eraser"></i>تفريغ الحقول
                        </button>

                    </div>

                </form>
                <br>
                <div id="t2_container">
                    <?php /*echo modules::run($get_page, $page);*/ ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$scripts = <<<SCRIPT
<script>

      var table = '{$TB_NAME}';
      var count = 0;

     $('#txt_month_act_sal,#t2_month').datetimepicker({
            format: 'YYYYMM',
            minViewMode: 'months',
            pickTime: false,
            
    });
     
    $('.sel2:not("[id^=\'s2\']")').select2();  
       
    function reBindT2(){
        ajax_pager({
            branch_no:$('#dp_t2_branch_no').val(),month:$('#t2_month').val(),emp_no:$('#dp_t2_emp_no').val()
         });
    }

    function LoadingData(){
       ajax_pager_data('#page_tb > tbody',{
        branch_no:$('#dp_t2_branch_no').val(),month:$('#t2_month').val(),emp_no:$('#dp_t2_emp_no').val()
       });
    }

    function searchDealyemp(){
         get_data('{$dealyemp_list_url}',{page: 1,
             branch_no:$('#dp_t2_branch_no').val(),month:$('#t2_month').val(),emp_no:$('#dp_t2_emp_no').val()
         },function(data){
            $('#t2_container').html(data);
            reBindT2();
        },'html');
     }
      
      
    function clear_form(){
        $('#dp_t2_emp_no').select2('val',0); 
        $('#t2_month').val();
      }
   
     
    function ExcelData(){
        var fewSeconds = 10;
        var branch_no = $('#dp_t2_branch_no').val();
        var month = $('#t2_month').val();
        var emp_no = $('#dp_t2_emp_no').val();
         info_msg('تنبيه','جاري تجهيز ملف الاكسل ..'); 
            location.href = '{$get_excel_adopt_financial_url}?branch_no='+branch_no+'&month='+month+'&emp_no='+emp_no;
            setTimeout(function(){
                info_msg('تنبيه','جاري تجهيز ملف الاكسل ..'); 
        }, fewSeconds*1000);
    }
    
    function print_report(){
       var branch_no = $('#dp_t2_branch_no').val();
       var month = $('#t2_month').val();
       var emp_no = $('#dp_t2_emp_no').val();
       var group_by_branch = have_no_val($('input[name=group_by_branch]:checked').val());
       _showReport('{$report_url}&report_type=pdf&report=attendance_delay_fin&p_branch='+branch_no+'&p_emp_no='+emp_no+'&p_month='+month+'&p_group_by_branch='+group_by_branch+'');
     }
     // check if var have value or null //
    function have_no_val(v) {
       if(v == null) {
           return v = '';
       }else {
           return v;
       }
     }
 

</script>
SCRIPT;
sec_scripts($scripts);
?>



