<?php
/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 09/02/2020
 * Time: 11:26 ص
 */
$MODULE_NAME = 'payroll_data';
$TB_NAME = 'no_reson_name_hr';
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$trans_url = base_url("$MODULE_NAME/$TB_NAME/trans_data");
$date_attr = " data-type='date' data-date-format='DD/MM/YYYY' data-val='true' data-val-regex-pattern='" . date_format_exp() . "' data-val-regex='Error' ";
$calc_deduction_hr_url = base_url("$MODULE_NAME/$TB_NAME/calc_deduction_hr"); //صلاحية احتساب الخصم ادارياً
$get_excel_url = base_url("$MODULE_NAME/$TB_NAME/excel_report");
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
            <li class="breadcrumb-item active" aria-current="page">الغير ملتزمين بالانصراف</li>
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
                <form id="<?= $TB_NAME ?>_form">
                    <div class="row">
                        <?php if ($this->user->branch == 1) { ?>
                            <div class="form-group col-md-2">
                                <label> المقر</label>
                                <select name="branch_no" id="dp_branch_no" class="form-control sel2">
                                    <option value="0">_______</option>
                                    <?php foreach ($branches as $row) : ?>
                                        <option <?= ($this->user->branch == $row['NO'] ? 'selected="selected"' : '') ?>
                                                value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <?php } else { ?>
                            <input type="hidden" name="branch_no" id="dp_branch_no" value="<?= $this->user->branch ?>">
                        <?php } ?>

                        <div class="form-group col-md-2">
                            <label>الموظف</label>
                            <select class="form-control sel2" name="emp_no" id="dl_emp_no">
                                <option value="">--اختر-----</option>
                                <?php foreach ($emp_no_cons as $emp) { ?>
                                    <option value="<?= $emp['EMP_NO'] ?>"><?= $emp['EMP_NO'] . ': ' . $emp['EMP_NAME'] ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="form-group col-md-2">
                            <label>شهر عدم الالتزام</label>
                            <input type="text" placeholder="الشهر"
                                   name="month"
                                   id="txt_month" class="form-control"
                                   value="<?= date('Ym', strtotime('last month')) ?>">
                        </div>

                    </div><!--end row -->
                    <hr>
                    <div class="flex-shrink-0">

                        <button type="button" onclick="javascript:search();" class="btn btn-primary"><i
                                    class="fa fa-search"></i> إستعلام
                        </button>

                        <?php if (HaveAccess($calc_deduction_hr_url)) { ?>
                            <button class="btn btn-secondary" type="button" onclick="calc_deduction_hr()">
                                <i class="fa fa-recycle"></i>
                                اعتماد احتساب الخصم الاداري
                            </button>
                        <?php } ?>


                        <button type="button" onclick="javascript:print_collect_report();"
                                class="btn btn-blue">
                            <i class="fa fa-print"></i>
                            طباعة كشف احتساب الخصم
                        </button>

                        <button type="button" onclick="ExcelData()"
                                class="btn btn-success">
                            <i class="fa fa-file-excel-o"></i>
                            إكسل
                        </button>

                        <button type="button" onclick="javascript:clear_form();" class="btn btn-cyan-light"><i
                                    class="fa fa-eraser"></i>تفريغ الحقول
                        </button>
                    </div>
                    <hr>
                    <div id="container">

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php
$scripts = <<<SCRIPT
<script type="text/javascript">

       var table = '{$TB_NAME}';
       var count = 0;
       
       $('.sel2:not("[id^=\'s2\']")').select2();

       
       $('#txt_month').datetimepicker({
            format: 'YYYYMM',
            minViewMode: 'months',
            pickTime: false,
        });
      
   
      function clear_form(){
           clearForm($('#{$TB_NAME}_form'));
           $('.sel2').select2('val',0);
     }
     
        
      $(function(){
            reBind();
      });

      function reBind(){
        ajax_pager({
           branch_no:$('#dp_branch_no').val(),month:$('#txt_month').val(),emp_no:$('#dl_emp_no').val()
         });
       }

        function LoadingData(){
          ajax_pager_data('#page_tb > tbody',{
            branch_no:$('#dp_branch_no').val(),month:$('#txt_month').val(),emp_no:$('#dl_emp_no').val()
          });
       }


      function search(){
        var month = $('#txt_month').val();
        var branch_no = $('#dp_branch_no').val();
        if (month == '') {
             danger_msg('يرجى  ادخال الشهر');
             return -1;
        }else  if (branch_no == 0){
             danger_msg('يرجى  ادخال المقر');
             return -1;
        }else{
           get_data('{$get_page_url}',{page: 1,
                branch_no:$('#dp_branch_no').val(),month:$('#txt_month').val(),emp_no:$('#dl_emp_no').val()
            },function(data){
                $('#container').html(data);
                reBind();
           },'html');
        }   
     }
     
      function ExcelData(){
        var fewSeconds = 10;
        var branch_no = $('#dp_branch_no').val();
        var month = $('#txt_month').val();
        var emp_no = $('#dl_emp_no').val();
        info_msg('تنبيه','جاري تجهيز ملف الاكسل ..'); 
            location.href = '{$get_excel_url}?branch_no='+branch_no+'&month='+month+'&emp_no='+emp_no;
            setTimeout(function(){
                info_msg('تنبيه','جاري تجهيز ملف الاكسل ..'); 
        }, fewSeconds*1000);
    }
     
      function print_collect_report(){
        var branch_no = $('#dp_branch_no').val();
        var emp_no = $('#dl_emp_no').val();
        var the_month = $('#txt_month').val();
        var month_sal = '';
        var post = '';
        var group_by_branch = '';
        _showReport('{$report_url}&report_type=pdf&report=no_leave_fp&p_branch='+branch_no+'&p_emp_no='+emp_no+'&p_month='+the_month+'&p_sal_month='+month_sal+'&p_agree_ma='+post+'&p_group_by_branch='+group_by_branch+'');
    }
     
     function calc_deduction_hr(){
        var month = $('#txt_month').val();
        var branch_no = $('#dp_branch_no').val();
        if (month == '') {
             danger_msg('يرجى  ادخال الشهر');
             return -1;
        }else  if (branch_no == 0){
             danger_msg('يرجى  ادخال المقر');
             return -1;
        }else{
              if(confirm('هل تريد بالتأكيد احتساب الخصم ادارياً ')){
                   get_data('{$calc_deduction_hr_url}', {month:month,branch_no:branch_no} , function(data){    
                    if (data == 1){
                      success_msg('رسالة','تم اعتماد الكشف بنجاح ..');
                      get_to_link(window.location.href);
                    }else {
                        danger_msg('تحذير..', data);
                        return -1;
                    }
                 }, 'html');   //end get data
       
             } //end if confirm
        }
     } //end calc_deduction_hr

</script>
SCRIPT;
sec_scripts($scripts);
?>
