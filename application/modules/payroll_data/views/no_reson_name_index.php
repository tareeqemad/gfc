<?php
/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 09/02/2020
 * Time: 11:26 ص
 */
$MODULE_NAME = 'payroll_data';
$TB_NAME = 'no_reson_name';
$TB_NAME_HR = 'No_reson_name_hr';
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$trans_url = base_url("$MODULE_NAME/$TB_NAME/trans_data");
$date_attr = " data-type='date' data-date-format='DD/MM/YYYY' data-val='true' data-val-regex-pattern='" . date_format_exp() . "' data-val-regex='Error' ";
$update_is_active_url = base_url("$MODULE_NAME/$TB_NAME/update_is_active");
$get_excel_url = base_url("$MODULE_NAME/$TB_NAME_HR/excel_report");
$no_reson_name_trans_url = base_url("$MODULE_NAME/$TB_NAME/public_no_reson_name_trans");
$report_url = base_url("JsperReport/showreport?sys=hr/salaries");

$index_hr = base_url("$MODULE_NAME/$TB_NAME_HR/index"); //احتساب الخصم الاداري
?>
<!-- PAGE-HEADER -->
<div class="page-header">
    <div>
        <h1 class="page-title">مرحلة اعداد الكشف | الشؤون الادرية في المقر</h1>
    </div>
    <div class="ms-auto pageheader-btn">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">الحركات</a></li>
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
                    <?php if (HaveAccess($index_hr)) {?>
                        <a href="<?= $index_hr ?>" class="btn btn-secondary">
                            <i class="fa fa-calculator"></i>
                            احتساب الخصم الاداري
                        </a>
                    <?php }?>
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
                                        <option <?= ($this->user->branch == $row['NO'] ? 'selected="selected"' : '') ?> value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
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
                            <label>شهر الغير ملتزمين</label>
                            <input type="text" placeholder="الشهر" name="month" id="txt_month" class="form-control" value="<?=  date('Ym', strtotime('last month'))?>">
                        </div>

                        <div class="form-group col-md-2">
                            <label>من تاريخ </label>
                            <input type="text" <?= $date_attr ?> name="from_the_date" id="txt_from_the_date"
                                   class="form-control">
                        </div>

                        <div class="form-group col-md-2">
                            <label>الى </label>
                            <input type="text" <?= $date_attr ?> name="to_the_date" id="txt_to_the_date"
                                   class="form-control">
                        </div>

                        <div class="form-group col-md-2">
                            <label>سبب الخصم</label>
                            <select class="form-control" name="s_reson_name" id="dp_s_reson_name">
                                <option value="">_________</option>
                                <option class="text-danger" value="IS_NULL">عرض القيم الغير معبئة</option>
                                <?php foreach ($no_signed_reson_con as $row) : ?>
                                    <option value="<?= $row['NO'] ?>"><?= $row['RESON'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>




                        <div class="form-group col-md-2">
                            <label>حالة الخصم</label>
                            <select class="form-control" name="is_active" id="dp_is_active">
                                <option value="">_________</option>
                                <?php foreach ($is_active_cons as $row) : ?>
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
                            <div class="form-group col-md-3">
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

                    </div><!--end row -->
                    <hr>
                    <div class="flex-shrink-0">
                        <button type="button" onclick="javascript:search();" class="btn btn-primary"><i
                                    class="fa fa-search"></i> إستعلام
                        </button>

                        <button type="button" onclick="no_reson_name_trans()" class="btn btn-secondary">
                            <i class="fe fe-corner-down-left"></i>
                            ترحيل الموظفين بدون بصمة
                        </button>


                        <button type="button" onclick="javascript:print_attendance_not_obligated();"
                                class="btn btn-blue">
                            <i class="fa fa-print"></i>
                            طباعة كشف الموظفين الغير ملتزمين
                        </button>
                        <button type="button" onclick="javascript:print_disc_calc_not_obligated();"
                                class="btn btn-blue">
                            <i class="fa fa-print"></i>
                            طباعة كشف احتساب الخصم
                        </button>


                        <button type="button" onclick="javascript:clear_form();"  class="btn btn-cyan-light"><i class="fa fa-eraser"></i>تفريغ الحقول</button>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-5">
                            <div class="alert alert-info" role="alert">
                                <strong>تنويه</strong> عند الترحيل يتم فحص اذا كان اذن معتمد للموظف ويتم جلبه بالشاشة , مع امكانية عرض الاذن

                            </div>
                        </div>
                    </div>

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
     $('.reson:not("[id^=\'s2\']")').select2();
       
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
           branch_no:$('#dp_branch_no').val(),month:$('#txt_month').val(),emp_no:$('#dl_emp_no').val(),from_the_date:$('#txt_from_the_date').val(),to_the_date:$('#txt_to_the_date').val(),
           reson_name:$('#dp_s_reson_name').val(), is_active:$('#dp_is_active').val(),is_shift_emp:$('#dp_is_shift_emp').val()
        });
       
       
    }

    function LoadingData(){
 
          ajax_pager_data('#page_tb > tbody',{
            branch_no:$('#dp_branch_no').val(),month:$('#txt_month').val(),emp_no:$('#dl_emp_no').val(),from_the_date:$('#txt_from_the_date').val(),to_the_date:$('#txt_to_the_date').val(),
           reson_name:$('#dp_s_reson_name').val(), is_active:$('#dp_is_active').val(),is_shift_emp:$('#dp_is_shift_emp').val()
          });
          
     }
     
    /* function  initFunctions(){
        reson_select2();
     }
     
    function  reson_select2(){
        setTimeout(function(){ 
            $('.reson:not("[id^=\'s2\']")').select2();
        },500);
    }   */

    function search(){
        var month = $('#txt_month').val();
        var branch_no = $('#dp_branch_no').val();
        if (month == '') {
             danger_msg('يرجى  ادخال الشهر');
             return -1;
        }else if (branch_no == ''){
            danger_msg('يرجى  ادخال المقر');
             return -1;
        }else{
             get_data('{$get_page_url}',{page: 1,
                branch_no:$('#dp_branch_no').val(),month:$('#txt_month').val(),emp_no:$('#dl_emp_no').val(),from_the_date:$('#txt_from_the_date').val(),to_the_date:$('#txt_to_the_date').val(),
                reson_name:$('#dp_s_reson_name').val(), is_active:$('#dp_is_active').val(),is_shift_emp:$('#dp_is_shift_emp').val()
            },function(data){
                $('#container').html(data);
                 reBind();
                $('.reson:not("[id^=\'s2\']")').select2();
            },'html');
          }
    }
    
    function print_attendance_not_obligated(){
        var branch_no = have_no_val($('#dp_branch_no').val());
        var emp_no = have_no_val($('#dl_emp_no').val());
        var month = have_no_val($('#txt_month').val());
        var group_by_branch = have_no_val($('input[name=group_by_branch]:checked').val());
        var from_the_date = have_no_val($('#txt_from_the_date').val());
        var to_the_date = have_no_val($('#txt_to_the_date').val());
        var is_active = have_no_val($('#dp_is_active').val());
        _showReport('{$report_url}&report_type=pdf&report=attendance_not_obligated&p_branch='+branch_no+'&p_emp_no='+emp_no+'&p_month='+month+'&p_group_by_branch='+group_by_branch+'&p_date_from='+from_the_date+'&p_date_to='+to_the_date+'&p_is_active='+is_active+'');
    } 
     
    function print_disc_calc_not_obligated(){
        var branch_no = have_no_val($('#dp_branch_no').val());
        var emp_no = have_no_val($('#dl_emp_no').val());
        var month = have_no_val($('#txt_month').val());
        var group_by_branch = have_no_val($('input[name=group_by_branch]:checked').val());
        _showReport('{$report_url}&report_type=pdf&report=attendance_not_obligated_mng_disc_calc&p_branch='+branch_no+'&p_emp_no='+emp_no+'&p_month='+month+'&p_group_by_branch='+group_by_branch+'');
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
    
    //ترحيل المتاخرين عن الدوام
    function no_reson_name_trans(){
            var from_the_date = $('#txt_from_the_date').val();
            var to_the_date = $('#txt_to_the_date').val();
            var branch_no = $('#dp_branch_no').val();
            if (from_the_date == ''){
                danger_msg('يجب ادخال من تاريخ');
                return -1;
            }else  if (to_the_date == ''){
                 danger_msg('يجب ادخال الى تاريخ');
                 return -1;
            }else  if (branch_no == ''){
                 danger_msg('يجب ادخال المقر');
                 return -1;
            }else  if(process(from_the_date) > process(to_the_date)){
                 warning_msg('يجب ان تكون قيمة الى تاريخ اكبر من تاريخ البداية');
                 return -1;
             }else   {
                if(confirm('هل تريد ترحيل الموظفين الغير ملتزمين ')){
                   get_data('{$no_reson_name_trans_url}', {from_the_date:from_the_date,to_the_date:to_the_date,branch_no:branch_no} , function(data){    
                    if (data >= 1){
                      success_msg('رسالة','تم ترحيل الموظفين المتأخرين بنجاح ..');
                      get_to_link(window.location.href);
                    }else {
                        danger_msg('تحذير..', data);
                        return -1;
                    }
                 }, 'html');   //end get data
       
             } //end if confirm
          }
    }
      
    function is_reson_name_change(obj){
         var tr = $(obj).closest('tr');
         var p_ser  = $('#h_p_ser',tr).val();
         var emp_no = $('#h_emp_no', tr).val();
         var the_month = $('#h_the_month', tr).val();
         var reson_in = $(obj).val();
         if (reson_in == ''){
             warning_msg('يرجى التعديل بسبب واضح');
             return -1
         }else{
           get_data('{$update_is_active_url}', {p_ser:p_ser,emp_no:emp_no,the_month:the_month,reson_in:reson_in} , function(data){
             var obj = jQuery.parseJSON(data);
             var msg = obj.msg;
             var is_active = obj.is_active;
             if (msg == 1) {
                    success_msg('رسالة','تم تعديل حركة السجل بنجاح ..');
             }else if (msg == 0) {
                 danger_msg('رسالة','تحذير حدث خطأ ما  ..');
                 return -1;
             } 
             
              if(is_active == 1){
                 $(tr).find('.far_val',tr).find("i[id='"+p_ser+"']").removeClass('.fa fa-smile-o fa-lg').addClass('fa fa-meh-o').css('color', 'red').addClass("text-center");
              }else if(is_active == 0) {
                 $(tr).find('.far_val',tr).find("i[id='"+p_ser+"']").removeClass('.fa fa-meh-o').addClass('fa fa-smile-o fa-lg').css('color', 'green').addClass("text-center");
              }
              
          },'html');
        }
     }
     // cehck two date process 
    function process(date){
           var parts = date.split("/");
           var date = new Date(parts[1] + "/" + parts[0] + "/" + parts[2]);
           return date.getTime();
      }
    // check if var have value or null //
    function have_no_val(v) {
        if(v == null) {
            return v = '';
        }else {
            return v;
        }
    }
    function clear_form(){
      $('#dl_emp_no').select2('val', 0);
      $('#txt_month').val('');
      $('#txt_from_the_date').val('');
      $('#txt_to_the_date').val('');
      $('#dp_s_reson_name').val('');
      $('#dp_is_active').val('');
      $('#dp_is_shift_emp').val('');
    }
</script>
SCRIPT;
sec_scripts($scripts);
?>
