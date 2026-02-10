<?php
/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 09/02/2020
 * Time: 11:26 ص
 */
$MODULE_NAME = 'payroll_data';
$TB_NAME = 'bouns_risk';
$TB_NAME1 = 'bouns_risk_adopt';
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$trans_url = base_url("$MODULE_NAME/$TB_NAME/trans_data");
$get_emp_data = base_url("$MODULE_NAME/$TB_NAME/public_get_emp_data");
$store_url = base_url("$MODULE_NAME/$TB_NAME/store");
$update_url = base_url("$MODULE_NAME/$TB_NAME/update_data");
$delete_url = base_url("$MODULE_NAME/$TB_NAME/delete_data");
//سبب الارجاع
$reason_detail_url = base_url("$MODULE_NAME/$TB_NAME/public_get_reason_detail");
//اعتماد مرحلة لاعداد
$prepare_risk_ma = base_url("$MODULE_NAME/$TB_NAME/prepare_risk_ma"); //صلاحية اعتماد المعد
$adopt_all_url = base_url("$MODULE_NAME/$TB_NAME1/public_adopt");

$get_excel_url = base_url("$MODULE_NAME/$TB_NAME/excel_report");
$report_url = base_url("JsperReport/showreport?sys=hr/salaries");
?>
<!-- PAGE-HEADER -->
<div class="page-header">
    <div>
        <h1 class="page-title">كشف علاوة المخاطرة | الشؤون الادارية</h1>
    </div>
    <div class="ms-auto pageheader-btn">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">الرواتب</a></li>
            <li class="breadcrumb-item active" aria-current="page">اعداد علاوة المخاطرة</li>
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
                    <?php if (HaveAccess($store_url)): ?>
                        <a class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#addModal"><i
                                    class="fa fa-plus-circle"></i>اضافة
                            موظف للكشف</a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-body">
                <form id="<?= $TB_NAME ?>_form">
                    <div class="row">
                        <?php if ($this->user->branch == 1) { ?>
                            <div class="form-group col-md-2">
                                <label> المقر</label>
                                <select name="branch_no" id="dp_branch_no" class="form-control">
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
                            <select class="form-control sel2" name="s_emp_no" id="dl_s_emp_no">
                                <option value="">--اختر-----</option>
                                <?php foreach ($emp_no_cons as $emp) { ?>
                                    <option value="<?= $emp['EMP_NO'] ?>"><?= $emp['EMP_NO'] . ': ' . $emp['EMP_NAME'] ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="form-group col-md-2">
                            <label>الشهر</label>
                            <input type="text" placeholder="الشهر"
                                   name="month"
                                   id="txt_month" class="form-control" value="<?= date('Ym') ?>">
                        </div>

                    </div><!--end row -->
                    <hr>
                    <div class="flex-shrink-0">
                        <button type="button" onclick="javascript:search();" class="btn btn-primary"><i
                                    class="fa fa-search"></i> إستعلام
                        </button>
                        <?php if (HaveAccess($prepare_risk_ma)) { ?>
                            <button type="button" id="prepare_risk_ma" onclick="javascript:adopt(1);"
                                    class="btn btn-indigo" style="display: none">
                                <i class="fa fa-check"></i>
                                اعتماد المعد
                            </button>
                        <?php } ?>
                        <?php if (HaveAccess($trans_url)) { ?>
                            <button type="button" onclick="javascript:trans_data();" class="btn btn-warning">
                                <i class="fa fa-long-arrow-left"></i>
                                ترحيل كشف الشهر السابق
                            </button>
                        <?php } ?>

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
                    <hr>
                    <div id="container">

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<input type="hidden" name="note" id="txt_note" value="اعتمادالمعد" style="display: none">


<!--Start addModal--اضافة علاوة ماخاطرة-->
<div class="modal fade" id="addModal" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">اضافة موظف للكشف</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="tr_border">
                    <form action="" method="POST" id="createBookForm">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label> الموظف</label>
                                <select name="no" id="dp_emp_no" class="form-control">
                                    <option value="">_________</option>
                                    <?php foreach ($emp_no_cons as $row) : ?>
                                        <option value="<?= $row['EMP_NO'] ?>"><?= $row['EMP_NO'] . ': ' . $row['EMP_NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group col-md-4">
                                <label> المسمى المهني</label>
                                <select name="job_type" id="dp_job_types" class="form-control">
                                    <option value="">_________</option>
                                    <?php foreach ($job_types as $row) : ?>
                                        <option value="<?= $row['NO'] ?>"><?= $row['NO'] . ': ' . $row['JOB_TYPE'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group col-md-2">
                                <label>الشهر</label>
                                <input type="text" id="txxt_month" class="form-control" value="<?php echo date('Ym') ?>"
                                       readonly>
                            </div>

                            <div class="form-group col-md-2">
                                <label>الراتب الأساسي</label>
                                <input type="text" id="txt_b_salary" class="form-control" readonly>
                            </div>

                            <div class="form-group col-md-2">
                                <label>المقر</label>
                                <input type="text" id="txt_branch_name" class="form-control" readonly>
                                <input type="hidden" id="txt_branch_no" name="emp_branch">
                            </div>


                            <div class="form-group col-md-2">
                                <label>المسمى الوظيفي</label>
                                <input type="text" id="txt_w_no_admin_name" class="form-control" readonly>
                                <input type="hidden" id="txt_job_type" name="job_type">
                            </div>

                            <div class="form-group col-md-2">
                                <label>العلاوة الاشرافية</label>
                                <input type="text" id="txt_supervisory_value" class="form-control" readonly>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
            <div class="modal-footer">
                <button class="btn  btn-primary" type="button" id="insert_data">
                    <i class="fa fa-recycle"></i>
                    احتساب قيمة علاوة المخاطرة
                </button>
                <button class="btn  btn-secondary" data-bs-dismiss="modal" type="button">اغلاق</button>

            </div>
        </div>
    </div>
</div>
<!--End addModal--اضافة علاوة ماخاطرة-->


<!--Start  EditModal تعديل علاوة مخاطرة-->
<div class="modal fade" id="EditModal">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">تعديل</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form action="" method="POST" id="createBookForm">
                    <div class="tr_border">
                        <div class="row">
                            <div class="form-group  col-md-2">
                                <label> رقم مسلسل </label>
                                <input type="text" readonly name="pp_ser_m" id="txt_pp_ser_m" class="form-control">
                            </div>
                            <div class="form-group  col-md-2">
                                <label> رقم الموظف </label>
                                <input type="text" readonly name="emp_no_m" id="txt_emp_no_m" class="form-control">
                            </div>
                            <div class="form-group  col-md-3">
                                <label> اسم الموظف </label>
                                <input type="text" readonly name="emp_name_m" id="txt_emp_name_m"
                                       class="form-control">
                            </div>
                            <div class="form-group  col-md-1">
                                <label>الشهر </label>
                                <input type="text" readonly name="month_m" id="txt_month_m" class="form-control">
                            </div>

                            <div class="form-group  col-md-2">
                                <label>المخاطرة المقترحة </label>
                                <input type="text" readonly name="value_m_sugest"
                                       id="txt_value_m_sugest" class="form-control" autocomplete="off">
                            </div>

                            <div class="form-group  col-md-2">
                                <label>المخاطرة المعتمدة </label>
                                <input type="text" readonly name="value_ma_m" id="txt_value_ma_m"
                                       class="form-control" autocomplete="off">
                            </div>

                            <div class="form-group col-md-4">
                                <label> المسمى الوظيفي</label>
                                <select name="job_type_u" id="dp_job_types_m" class="form-control">
                                    <option value="">_________</option>
                                    <?php foreach ($job_types as $row) : ?>
                                        <option value="<?= $row['NO'] ?>"><?= $row['NO'] . ': ' . $row['JOB_TYPE'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                        </div>

                    </div>


                </form>

            </div>
            <div class="modal-footer">
                <button class="btn  btn-primary" type="button" onclick="update_data()">
                    <i class="fa fa-recycle"></i>
                    اعادة احتساب قيمة علاوة المخاطرة
                </button>
                <button class="btn  btn-secondary" data-bs-dismiss="modal" type="button">اغلاق</button>
            </div>
        </div>
    </div>
</div>
<!--End EditModal تعديل علاوة مخاطرة-->


<!-- Modal -->
<div class="modal fade" id="DetailModal">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">بيانات الاعتماد</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" id="public-modal-body">

            </div>
            <div class="modal-footer">
                <button class="btn  btn-secondary" data-bs-dismiss="modal" type="button">اغلاق</button>
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
      
   
    $('#prepare_risk_ma').hide();
   
   function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
        $('.sel2').select2('val',0);
     }
   
    
   function trans_data(){
       var month =    $('#txt_month').val() ;
       var emp_branch = $('#dp_branch_no').val();
       if (month == '') {
          danger_msg('يرجى ادخال الشهر');
          return -1;
       }else if (emp_branch == '') {
          danger_msg('يرجى ادخال المقر');
          return -1;
      }else{
        get_data('{$trans_url}',{month: month,emp_branch: emp_branch}, function (data) {
            if(data>=1){
                success_msg('رسالة','تم ترحيل البيانات بنجاح ..');
                search();
            }else{
                danger_msg('تحذير..',data);
                return -1;
            }
        },'html');
      }
    }  
        
   $(function(){
            reBind();
        });

   function reBind(){
       ajax_pager({
           branch_no:$('#dp_branch_no').val(),month:$('#txt_month').val(),emp_no:$('#dl_s_emp_no').val() 
        });
       }

   function LoadingData(){
          ajax_pager_data('#page_tb > tbody',{
            branch_no:$('#dp_branch_no').val(),month:$('#txt_month').val(),emp_no:$('#dl_s_emp_no').val()    
          });
       }
   
   function search(){
        var month = $('#txt_month').val();
        if (month == '') {
             danger_msg('يرجى  ادخال الشهر');
        }else{
             get_data('{$get_page_url}',{page: 1,branch_no:$('#dp_branch_no').val(),month:$('#txt_month').val(),emp_no:$('#dl_s_emp_no').val() },function(data){
                $('#prepare_risk_ma').show();
                $('#container').html(data);
                reBind();
            },'html');
        }
     }
   
   function ExcelData(){
        var fewSeconds = 10;
        var branch_no = $('#dp_branch_no').val();
        var month = $('#txt_month').val();
        var emp_no = $('#dl_s_emp_no').val();
        info_msg('تنبيه','جاري تجهيز ملف الاكسل ..'); 
            location.href = '{$get_excel_url}?branch_no='+branch_no+'&month='+month+'&emp_no='+emp_no;
            setTimeout(function(){
                info_msg('تنبيه','جاري تجهيز ملف الاكسل ..'); 
        }, fewSeconds*1000);
    }
   
   function adopt(no){
       var action_desc= 'اعتماد';
       var val = [];
       var note = $('#txt_note').val();
        $('#page_tb .checkboxes:checked').each(function(i){
            val[i] = $(this).val();
        });
          if(val.length > 0){
            if(confirm('هل تريد بالتأكيد '+action_desc+' '+val.length+' بنود')){
                 get_data('{$adopt_all_url}', {pp_ser:val,agree_ma:no,note:note} , function(ret){
                         if(ret==1){
                            success_msg('رسالة','تمت عملية الاعتماد بنجاح ');
                            search();
                        }else{
                            danger_msg('تحذير..',ret);
                            return -1;
                        }
                  
                }, 'html');
            }
        }else{
              danger_msg('يرجى تحديد السجلات المراد اعتمادها');
              return -1;
        }
    } 
    
    
    $('#dp_emp_no').select2({
         dropdownParent: $('#addModal .modal-content')
    });

   $('#dp_job_types').select2({
        dropdownParent: $('#addModal .modal-content')
   });

   
   $('#dp_emp_no').select2({
         dropdownParent: $('#addModal .modal-content')
    });

   $('#dp_job_types').select2({
        dropdownParent: $('#addModal .modal-content')
   });
    
   $('#dp_job_types_m').select2({
        dropdownParent: $('#EditModal .modal-content')
   });
   
    $('#dp_emp_no').change(function(){
        get_emp_data(0)
     });    
  
   function get_emp_data(){
        var no =  $('#dp_emp_no').val();
        if (no == '') {
            return -1;
        } else {
              get_data('{$get_emp_data}', {no: no}, function (data) {
                 $.each(data, function (i, value) {
                    // console.log(option.NAME);
                    $('#txt_b_salary').val(value.B_SALARY);
                    $('#txt_branch_name').val(value.BRANCH_NAME);
                    $('#txt_branch_no').val(value.BRANCH_NO);
                    $('#txt_emp_type_name').val(value.EMP_TYPE_NAME);
                     $('#txt_w_no_admin_name').val(value.W_NO_ADMIN_NAME);
                     $('#txt_supervisory_value').val(value.SUPERVISORY_VALUE);
                  });
             });
        }
         
    } //end get emp data
    
   function delete_row(a,id){
        if(confirm('هل تريد حذف السجل ؟')){
           get_data('{$delete_url}',{id:id},function(data){
                if(data == '1'){
                    success_msg('رسالة','تم حذف السجل بنجاح ..');
                    $(a).closest('tr').remove();
                }
            },'html');
        }
    }
    
     //insert_data Data  event handler.
   $(document).on("click", "#insert_data", function(arg){
      arg.preventDefault();
       var no = $('#dp_emp_no').val();
       var month = $('#txxt_month').val();
       var emp_branch =$('#txt_branch_no').val();     
       var job_type = $('#dp_job_types').val();
       if (no == '') {
            danger_msg('يرجى ادخال رقم الموظف');
            return false;
       }else if (job_type == '') {
           danger_msg('يرجى ادخال المسمى الوظيفي');
            return false;
      }else{
           get_data('{$store_url}',{no:no,month:month,emp_branch:emp_branch,job_type:job_type}, function (data) {
           // console.log(data);
            var len = data.length;
            if (len > 0) {
                success_msg('رسالة','تم الاضافة للكشف بنجاح ..');
                get_to_link(window.location.href);
            } else {
                danger_msg('تحذير..', data);
            }
          }, 'html');   
           }
         });
     
   function show_detail_row(obj) {
  
        $('#txt_pp_ser_m').val('');
        $('#txt_emp_no_m').val('');
        $('#txt_emp_name_m').val('');
        $('#txt_branch_no').val('');
        $('#txt_month_m').val('');
        $('#txt_value_m_sugest').val('');
        $('#txt_value_ma_m').val('');
        $('#EditModal').modal('show');
        $("#EditModal").appendTo("body");
          var tr = obj.closest('tr');
          var pp_ser  = $('input[name="pp_ser"]',tr).val();
          var emp_no_m  = $('input[name="no"]',tr).val();
          var emp_name_m  = $('input[name="emp_name"]',tr).val();
          var month_m  = $('input[name="month"]',tr).val();
          var value_ma_m  = $('input[name="value_ma"]',tr).val();
          var value_m  = $('input[name="value_m"]',tr).val();
          var job_type  = $('input[name="job_type_ma"]',tr).val();
          $('input[name="pp_ser_m"]').val(pp_ser);
          $('input[name="emp_no_m"]').val(emp_no_m);
          $('input[name="emp_name_m"]').val(emp_name_m);
          $('input[name="month_m"]').val(month_m);
           //المخاطرة المعتمدة
          $('input[name="value_ma_m"]').val(value_ma_m);
          //المخاطرة المقترحة
           $('input[name="value_m_sugest"]').val(value_m);
           
          //المسمى المهني
           $('#dp_job_types_m').select2('val',job_type);
            
    }
    
        //تعديل علاوة مخاطرة
   function update_data(){
       var serial = $('input[name="pp_ser_m"]').val();
       var no = $('#txt_emp_no_m').val();
       var job_type = $('#dp_job_types_m').val();
       var month = $('#txt_month_m').val();
       if (job_type == '') {
            danger_msg('يرجى ادخال المسمى المهني');
            return -1;
        }else {
        if(confirm('هل تريد بالتأكيد اعادة احتساب علاوة المخاطرة ')){
           get_data('{$update_url}', {pp_ser:serial,no:no,job_type:job_type,month:month} , function(ret){    
            var len = ret.length;
            if (len > 0) {
                success_msg('رسالة','تم الاضافة للكشف بنجاح ..');
                get_to_link(window.location.href);
            } else {
                danger_msg('تحذير..', ret);
                return -1;
            }
                      
         }, 'html');   //end get data
       
       } //end if confirm
      } //end else 
    } //end function update 
    
    
      //سبب الارجاع
   function show_reason_row(pp_ser){
              // Display Modal
              $('#DetailModal').modal('show');
              $("#DetailModal").appendTo("body");
              $.ajax({
                    url: '{$reason_detail_url}',
                    type: 'post',
                    data: {pp_ser: pp_ser},
                    success: function(response){
                        // Add response in Modal body
                        $('#public-modal-body').html(response);
                       
                    }
                });
         } // show_detail_row
      
   
   function print_report(){
        var month = $('#txt_month').val();
        var branch_no = $('#dp_branch_no').val();
        var emp_no = $('#dl_s_emp_no').val();
        var emp_type = '';
        var w_no_admin  = '';
        var agree_ma = '';
        var agree_fi = '';
        var p_sign_1  = '';
        var p_sign_2  = '';
        var p_sign_3  = '';
        var value_ma = '';
        var group_by_branch = '';
        _showReport('{$report_url}&report_type=pdf&report=risk_bonus&p_month='+month+'&p_branch='+branch_no+'&p_emp_no='+emp_no+'&p_emp_type='+emp_type+'&p_w_no_admin='+w_no_admin+'&p_agree_ma='+agree_ma+'&p_agree_fi='+agree_fi+'&p_sign_1='+p_sign_1+'&p_sign_2='+p_sign_2+'&p_sign_3='+p_sign_3+'&p_value_ma='+value_ma+'&p_group_by_branch='+group_by_branch+'');
    }
    

</script>
SCRIPT;
sec_scripts($scripts);
?>
