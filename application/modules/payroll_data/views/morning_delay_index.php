<?php

/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 13/04/18
 * Time: 10:01 ص
 */
$MODULE_NAME = 'payroll_data';
$TB_NAME = 'morning_delay';
$TB_NAME_SUM = 'morning_delay_sum';
$edit_url = base_url("$MODULE_NAME/$TB_NAME/edit");
$delay_sum_url = base_url("$MODULE_NAME/$TB_NAME_SUM/index");
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$trans_delay_index_url = base_url("$MODULE_NAME/$TB_NAME/public_index_trans");
$trans_delay_emp_url = base_url("$MODULE_NAME/$TB_NAME/trans_delay_emp");
$update_is_active_url = base_url("$MODULE_NAME/$TB_NAME/update_is_active");
$get_statstic_url = base_url("$MODULE_NAME/$TB_NAME/public_get_statstic_data");
$date_attr = " data-type='date' data-date-format='DD/MM/YYYY' data-val='true' data-val-regex-pattern='" . date_format_exp() . "' data-val-regex='Error' ";
$get_page_calculated_url = base_url("$MODULE_NAME/$TB_NAME/get_page_calculated"); //عرض ساعات الاحتساب
$get_discount_calculation_url = base_url("$MODULE_NAME/$TB_NAME/discount_calculation");  /// اعتماد اداري// احتساب الخصم ادارياً
$adopt_detail_url = base_url("$MODULE_NAME/$TB_NAME/public_get_adopt_detail");
$get_excel_employee_morning_url = base_url("$MODULE_NAME/$TB_NAME/excel_employee_morning_delay"); //كشف التأخير الصباحي
$get_excel_collect_discount_hours_url = base_url("$MODULE_NAME/$TB_NAME/excel_collect_discount_hours"); //تجميع ساعات الخصم
$report_url = base_url("JsperReport/showreport?sys=hr/salaries");
?>
<!-- PAGE-HEADER -->
<div class="page-header">
    <div>
        <h1 class="page-title">اعداد كشف التأخير الصباحي | الادارية</h1>
    </div>
    <div class="ms-auto pageheader-btn">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">الحركات</a></li>
            <li class="breadcrumb-item active" aria-current="page">التأخير الصباحي</li>
        </ol>
    </div>
</div>
<!-- PAGE-HEADER END -->
<!-- Row -->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"> كشف التأخير الصباحي </h3>
                <div class="card-options">
                    <?php if (HaveAccess($trans_delay_emp_url)) { ?>
                        <a href="<?= $trans_delay_index_url ?>" class="btn btn-secondary"><i
                                    class="fe fe-corner-down-left"></i>
                            ترحيل كشف التأخير الصباحي
                        </a>
                    <?php } ?>
                </div>
            </div>
            <div class="card-body">
                <form id="<?= $TB_NAME ?>_form">
                    <div class="row">
                        <?php if ($this->user->branch == 1) { ?>
                            <div class="form-group col-md-2">
                                <label> المقر</label>
                                <select name="branch_no" id="dp_branch_no" class="form-control sel2">
                                    <option value="">_______</option>
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
                            <select name="emp_no" id="dp_emp_no" class="form-control sel2">
                                <option value="">_________</option>
                                <?php foreach ($emp_no_cons as $row) : ?>
                                    <option value="<?= $row['EMP_NO'] ?>"><?= $row['EMP_NO'] . ': ' . $row['EMP_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group col-md-2">
                            <label>الشهر</label>
                            <input type="text" placeholder="الشهر" name="month" id="txt_month" class="form-control"
                                   value="<?= date('Ym', strtotime('last month')) ?>">
                        </div>

                        <div class="form-group col-md-2">
                            <label>من تاريخ </label>
                            <input type="text" <?= $date_attr ?> name="from_the_date" id="txt_from_the_date"
                                   value="<?= date("d/m/Y", mktime(0, 0, 0, date("m") - 1, 1)); ?>"
                                   class="form-control">
                        </div>

                        <div class="form-group col-md-2">
                            <label>الى </label>
                            <input type="text" <?= $date_attr ?> name="to_the_date" id="txt_to_the_date"
                                   value="<?= date('d/m/Y', strtotime('-1 second', strtotime(date('m') . '/01/' . date('Y')))); ?>"
                                   class="form-control">
                        </div>

                        <div class="form-group col-md-2">
                            <label>الحركة</label>
                            <select name="is_active" id="dp_is_active" class="form-control">
                                <option value="">_________</option>
                                <?php foreach ($is_active_cons as $row) : ?>
                                    <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>


                        <div class="form-group col-md-2">
                            <label>الخصم المعتمد</label>
                            <select name="post" id="dp_the_post" class="form-control">
                                <option value="">_________</option>
                                <?php foreach ($the_post_cons as $row) : ?>
                                    <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group col-md-2">
                            <label>عرض موظفي الوردية</label>
                            <select class="form-control" name="is_shift_emp" id="dp_is_shift_emp">
                                <option value="">_________</option>
                                <option value="1">عرض</option>
                            </select>
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

                </form>
                <div class="flex-shrink-0">
                    <button type="button" onclick="javascript:search();" class="btn btn-primary"><i
                                class="fa fa-search"></i> إستعلام
                    </button>

                    <button type="button" onclick="javascript:collect_discount_hours();" class="btn btn-secondary"><i
                                class="fa fa-search"></i>
                        استعلام تجميع ساعات الخصم
                    </button>

                    <?php if (HaveAccess($get_discount_calculation_url)) { ?>
                        <button type="button" onclick="javascript:discount_calculation();" class="btn btn-indigo"
                                id="btn_adopt" style="display: none;">
                            <i class="fa fa-check"></i>
                            اعتماد الخصم ادارياً
                        </button>
                    <?php } ?>

                    <button type="button" onclick="javascript:print_morning_delay();"
                            class="btn btn-blue">
                        <i class="fa fa-print"></i>
                        طباعة كشف التأخير الصباحي الاداري
                    </button>

                    <button type="button" onclick="javascript:print_attendance_delay_total();"
                            class="btn btn-blue">
                        <i class="fa fa-print"></i>
                        طباعة كشف تجميع ساعات الخصم
                    </button>


                    <button type="button" onclick="javascript:excel_morning_delay();" class="btn btn-success">
                        <i class="fa fa-file-excel-o"></i>
                        كشف اكسل التأخير الصباحي
                    </button>

                    <button type="button" onclick="javascript:excel_collect_discount_hours();" class="btn btn-success">
                        <i class="fa fa-file-excel-o"></i>
                        كشف اكسل تجميع ساعات الخصم
                    </button>


                    <button type="button" onclick="javascript:clear_form();" class="btn btn-cyan-light">
                        <i class="fa fa-eraser"></i>
                        تفريغ الحقول
                    </button>
                </div>
                <hr>
                <div id="container">
                    <? //=modules::run($get_page_url, $page);?>
                </div>

            </div>
        </div>
    </div>
</div><!--end row--->
<div class="modal fade" id="DetailModal">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">بيانات التأخير الصباحي</h6>
                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span
                            aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" id="public-modal-body">

            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">اغلاق</button>
            </div>
        </div>
    </div>
</div>
<?php
$scripts = <<<SCRIPT
<script>

      var table = '{$TB_NAME}';
      
      $('.sel2:not("[id^=\'s2\']")').select2();  

      $('#txt_month').datetimepicker({
            format: 'YYYYMM',
            minViewMode: 'months',
            pickTime: false,
            
      });

      $(function(){
         reBind();
      });
      
      function reBind(){
        ajax_pager({
             branch_no:$('#dp_branch_no').val(), month:$('#txt_month').val(), emp_no:$('#dp_emp_no').val(), 
             from_the_date:$('#txt_from_the_date').val(),to_the_date:$('#txt_to_the_date').val(),is_active:$('#dp_is_active').val() ,the_post:$('#dp_the_post').val(),is_shift_emp:$('#dp_is_shift_emp').val() 
         });
       }

        function LoadingData(){
          ajax_pager_data('#page_tb > tbody',{
             branch_no:$('#dp_branch_no').val(), month:$('#txt_month').val(), emp_no:$('#dp_emp_no').val(), 
             from_the_date:$('#txt_from_the_date').val(),to_the_date:$('#txt_to_the_date').val(),is_active:$('#dp_is_active').val() ,the_post:$('#dp_the_post').val(),is_shift_emp:$('#dp_is_shift_emp').val() 
          });
       }


      function search(){
            var month = $('#txt_month').val();
            var branch_no = $('#dp_branch_no').val();
            if (month == '') {
                 warning_msg('يرجى  ادخال الشهر');
                 return -1;
            }else if (branch_no == ''){
                 warning_msg('يرجى  ادخال المقر');
                 return -1;
            }else {
                 $('#btn_adopt').hide();  
                 get_data('{$get_page_url}',{page: 1,
                     branch_no:$('#dp_branch_no').val(), month:$('#txt_month').val(), emp_no:$('#dp_emp_no').val(), 
                     from_the_date:$('#txt_from_the_date').val(),to_the_date:$('#txt_to_the_date').val(),is_active:$('#dp_is_active').val() ,the_post:$('#dp_the_post').val(),is_shift_emp:$('#dp_is_shift_emp').val() 
                 },function(data){
                    $('#container').html(data);
                    reBind();
                },'html');
            }
      } 
     
      function print_morning_delay(){
          var branch_no = $('#dp_branch_no').val();
          var month = have_no_val($('#txt_month').val());
          var emp_no = have_no_val($('#dp_emp_no').val());
          var from_the_date = have_no_val($('#txt_from_the_date').val());
          var to_the_date = have_no_val($('#txt_to_the_date').val());
          var is_active = have_no_val($('#dp_is_active').val());
          var the_post  = have_no_val($('#dp_the_post').val());
          var group_by_branch = have_no_val($('input[name=group_by_branch]:checked').val());
          _showReport('{$report_url}&report_type=pdf&report=attendance_delay&p_branch='+branch_no+'&p_emp_no='+emp_no+'&p_month='+month+'&p_group_by_branch='+group_by_branch+'&p_date_from='+from_the_date+'&p_date_to='+to_the_date+'&p_is_active='+is_active+'&p_post='+the_post+'');
     } 
  
     
     function print_attendance_delay_total(){
          var branch_no = have_no_val($('#dp_branch_no').val());
          var month = have_no_val($('#txt_month').val());
          var emp_no = have_no_val($('#dp_emp_no').val());
          var group_by_branch = have_no_val($('input[name=group_by_branch]:checked').val());
          _showReport('{$report_url}&report_type=pdf&report=attendance_delay_total&p_branch='+branch_no+'&p_emp_no='+emp_no+'&p_month='+month+'&p_group_by_branch='+group_by_branch+'');
     } 
  
           // check if var have value or null //
     function have_no_val(v) {
          if(v == null) {
              return v = '';
          }else {
              return v;
          }
      }
    
     function excel_morning_delay(){
       var fewSeconds = 10;
        var branch_no = $('#dp_branch_no').val();
        var month = $('#txt_month').val();
        var emp_no = $('#dp_emp_no').val();
        var from_the_date = $('#txt_from_the_date').val();
        var to_the_date = $('#txt_to_the_date').val();
        var is_active = $('#dp_is_active').val();
        var the_post = $('#dp_the_post').val();
        var is_shift_emp = $('#dp_is_shift_emp').val();
        info_msg('تنبيه','جاري تجهيز ملف الاكسل ..'); 
            location.href = '{$get_excel_employee_morning_url}?branch_no='+branch_no+'&month='+month+'&emp_no='+emp_no+'&from_the_date='+from_the_date+'&to_the_date='+to_the_date+'&is_active='+is_active+'&the_post='+the_post+'&is_shift_emp='+is_shift_emp;
            setTimeout(function(){
                info_msg('تنبيه','جاري تجهيز ملف الاكسل ..'); 
        }, fewSeconds*1000);
     }
     
     
     function excel_collect_discount_hours(){
        var fewSeconds = 10;
        var branch_no = $('#dp_branch_no').val();
        var month = $('#txt_month').val();
        var emp_no = $('#dp_emp_no').val();
        info_msg('تنبيه','جاري تجهيز ملف الاكسل ..'); 
            location.href = '{$get_excel_collect_discount_hours_url}?branch_no='+branch_no+'&month='+month+'&emp_no='+emp_no;
            setTimeout(function(){
                info_msg('تنبيه','جاري تجهيز ملف الاكسل ..'); 
        }, fewSeconds*1000);
     }
     
     
    
      function clear_form(){
         $('#dp_emp_no').select2('val',0);          
         $('#txt_month').val('');                
         $('#txt_from_the_date').val('');                 
         $('#txt_to_the_date').val('');               
         $('#dp_is_active').val('');              
         $('#dp_the_post').val('');             
         $('#dp_is_shift_emp').val('');                     
         $('#btn_adopt').hide();
      }
     
    
      function show_detail_row(pp_ser){
          // Display Modal
          $('#DetailModal').modal('show');
            $.ajax({
                url: '{$get_statstic_url}',
                type: 'post',
                data: {pp_ser: pp_ser},
                success: function(response){
                    // Add response in Modal body
                    $('#public-modal-body').html(response);
                }
            });
      } // show_detail_row
    
      function is_active_change(obj,p_ser){
        var tr = $(obj).closest('tr');
        var is_active = $(obj).val();
        var currentRow = $(tr).find("#dis_hour");
        var min_delay = $('input[name="min_delay"]', tr).val();
        var hour_discount = 10;
        get_data('{$update_is_active_url}', {p_ser:p_ser,is_active:is_active} , function(data){
            if (data == '1') {
               
                if(is_active == 1){
                   $(tr).find('.far_val',tr).find("i[id='"+p_ser+"']").removeClass('.fa fa-smile-o fa-lg').addClass('fa fa-meh-o').css('color', 'red').addClass("text-center");
                   if(inRange(min_delay, 16, 30)) {
                         currentRow.text('1.00');
                   }else if (inRange(min_delay, 31, 45)){
                       currentRow.text('2.00');
                   }else if (inRange(min_delay, 46, 60)){
                       currentRow.text('3.00');
                   } else if (min_delay >= 61)  {
                       currentRow.text(hour_discount);
                   } else {
                     currentRow.text('0.00');
                   }
                      // $(tr).css({background: 'rgba(104, 181, 2, 0.39)'});
                }else if(is_active == 0) {
                   //console.log( $(tr).find('.far_val',tr).find("i[id='"+p_ser+"']"));
                   $(tr).find('.far_val',tr).find("i[id='"+p_ser+"']").removeClass('.fa fa-meh-o').addClass('fa fa-smile-o fa-lg').css('color', 'green').addClass("text-center");
                    
                   currentRow.text('0.00');
                   //$(tr).css({background: 'rgba(184, 50, 60, 0.27)'});
                }
                else if(is_active == 9) {
                  //  $(tr).css({background: 'rgba(245, 225, 66,11)'}); 
                   $(tr).find('.far_val',tr).find("i[id='"+p_ser+"']").removeClass('.fa fa-meh-o').addClass('fa fa-smile-o fa-lg').css('color', 'green').addClass("text-center");
                   currentRow.text('0.00');

                }
                success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                //get_to_link(window.location.href);
            }else if (data == '0'){
                danger_msg('تحذير..','السجل معتمد ادارياً');
            }
        },'html');
      }
      
      function inRange(n, nStart, nEnd){
            if (n>=nStart && n<=nEnd) return true;
            else return false;
      }
       
      function reBind1(){
        ajax_pager({
            branch_no:$('#dp_branch_no').val(), month:$('#txt_month').val(), emp_no:$('#dp_emp_no').val()
         });
       }
      
      
      
      function collect_discount_hours(){
     
        var branch_no = $('#dp_branch_no').val();
        var month = $('#txt_month').val();
         if (branch_no == ''){
              warning_msg('يجب ادخال المقر');
              return -1;
          }else if (month == ''){
               warning_msg('يجب ادخال الشهر');
              return -1;
          }else {
              $('#btn_adopt').show();   
              get_data('{$get_page_calculated_url}',{page: 1,
                 branch_no:$('#dp_branch_no').val(), month:$('#txt_month').val(), emp_no:$('#dp_emp_no').val()
             },function(data){
                $('#container').html(data);
                reBind1();
            },'html');
          }
      }
      
      function discount_calculation(){
        var branch_no = $('#dp_branch_no').val();
        var month = $('#txt_month').val();
         if (branch_no == ''){
              warning_msg('يجب ادخال المقر');
              return -1;
          }else if (month == ''){
               warning_msg('يجب ادخال الشهر');
              return -1;
          }else {
              if(confirm('هل تريد اعتماد  احتساب الخصم الاداري  ؟!')){
                  get_data('{$get_discount_calculation_url}', {branch_no:branch_no,month:month} , function(ret){
                     if(ret>= 1){
                        success_msg('رسالة','تم اعتماد  البيانات بنجاح ..');
                        collect_discount_hours();
                    }else{
                        warning_msg('تحذير..',ret);
                        return -1;
                    }
                  }, 'html');
               }
          }
      }
      
      
      function show_detail_adopt(emp_no,month){
           // Display Modal
        $('#DetailModal').modal('show');
        $.ajax({
            url: '{$adopt_detail_url}',
            type: 'post',
            data: {emp_no: emp_no,month:month},
            success: function(response){
                // Add response in Modal body
                $('#public-modal-body').html(response);
            }
        });
      }
</script>
SCRIPT;
sec_scripts($scripts);
?>


