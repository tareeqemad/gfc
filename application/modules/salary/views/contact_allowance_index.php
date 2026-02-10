<?php
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 22/02/23
 * Time: 12:00 م
 */
$MODULE_NAME = 'salary';
$TB_NAME = 'Contact_allowance';
$TB_NAME1 = 'Contact_allowance_adopt';
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$trans_url = base_url("$MODULE_NAME/$TB_NAME/trans_data");
$get_emp_data_1 = base_url("$MODULE_NAME/$TB_NAME/public_get_emp_data_1");
$get_emp_data_2 = base_url("$MODULE_NAME/$TB_NAME/public_get_emp_data_2");
$get_emp_data_exception = base_url("$MODULE_NAME/$TB_NAME/public_get_emp_data_exception");
$store_url = base_url("$MODULE_NAME/$TB_NAME/store");
$update_url = base_url("$MODULE_NAME/$TB_NAME/update_data");
$delete_url = base_url("$MODULE_NAME/$TB_NAME/delete_data");
//سبب الارجاع
$reason_detail_url = base_url("$MODULE_NAME/$TB_NAME/public_get_reason_detail");
//اعتماد مرحلة لاعداد
$supplies_services = base_url("$MODULE_NAME/$TB_NAME/Supplies_services"); //صلاحية اعتماد المعد
$adopt_all_url = base_url("$MODULE_NAME/$TB_NAME1/public_adopt");

$get_excel_url = base_url("$MODULE_NAME/$TB_NAME/excel_report");
$report_url = base_url("JsperReport/showreport?sys=hr/salaries");
?>
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="left-content">
        <span class="main-content-title mg-b-0 mg-b-lg-1">كشف بدل الاتصال</span>
    </div>
    <div class="justify-content-center mt-2">
        <ol class="breadcrumb">
            <li class="breadcrumb-item tx-15"><a href="javascript:void(0);">الرواتب</a></li>
            <li class="breadcrumb-item active" aria-current="page"> كشف بدل الاتصال</li>
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

                    <?php if (HaveAccess($store_url)): ?>
                        <a class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#addModal"><i class="fa fa-plus-circle"></i>اضافة موظف للكشف</a>
                    <?php endif; ?>

                </div>
            </div><!-- end card header -->
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
                            <input type="text" placeholder="الشهر" name="month" id="txt_month" class="form-control" value="<?= date('Ym') ?>">
                        </div>

                        <div class="form-group col-md-2">
                            <label>نوع الرصيد</label>
                            <select class="form-control sel2" name="status_type" id="dl_status_type">
                                <option value="">--اختر-----</option>
                                <?php foreach ($status_type as $row) { ?>
                                    <option  value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php } ?>
                            </select>
                        </div>

                    </div><!--end row -->
                    <hr>
                    <div class="flex-shrink-0">
                        <button type="button" onclick="javascript:search();" class="btn btn-primary"><i class="fa fa-search"></i> إستعلام</button>
                        <?php if ( HaveAccess($supplies_services)) { ?>
                            <button type="button" id="prepare_risk_ma" onclick="javascript:adopt(10);" class="btn btn-indigo" style="display: none"><i class="fa fa-check"></i>اعتماد قسم اللوازم والخدمات</button>
                        <?php } ?>
                        <?php if (HaveAccess($trans_url)) { ?>
                            <button type="button" onclick="javascript:trans_data();" class="btn btn-warning"><i class="fa fa-angle-double-left"></i>ترحيل كشف الشهر السابق</button>
                        <?php } ?>
                        <button type="button" onclick="ExcelData()" class="btn btn-success"><i class="fas fa-file-excel"></i>إكسل</button>
                        <button type="button" onclick="javascript:print_report();" class="btn btn-blue"><i class="fa fa-print"></i>طباعة</button>
                        <button type="button" onclick="javascript:clear_form();" class="btn btn-cyan-light"><i class="fas fa-eraser"></i>تفريغ الحقول</button>
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

<!--Start addModal--اضافة موظف للكشف-->
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

                            <div class="form-group col-md-2">
                                <label>الشهر</label>
                                <input type="text" id="the_month" name="the_month" class="form-control" value="<?php echo date('Ym') ?>" readonly>
                            </div>

                            <div class="form-group col-md-2">
                                <label>المقر</label>
                                <input type="text" id="txt_branch_name" class="form-control" readonly>
                                <input type="hidden" id="txt_branch_no" name="emp_branch">
                            </div>

                            <div class="form-group col-md-2">
                                <label>المسمى الوظيفي</label>
                                <input type="text" id="txt_w_no_admin_name" class="form-control" readonly>
                            </div>

                            <div class="form-group col-md-2">
                                <label>فئة الاتصال</label>
                                <input type="text" id="category" class="form-control" readonly>
                            </div>

                            <div class="form-group col-md-2">
                                <label>مبلغ الفئة</label>
                                <input type="text" id="txt_category_amount" class="form-control" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))" readonly>
                            </div>

                            <div class="form-group col-md-2">
                                <label>الفاتورة الشهرية</label>
                                <input type="text" id="txt_billing_value" class="form-control" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))" readonly>
                            </div>

                            <div class="form-group col-md-2">
                                <label>المبلغ المستحق</label>
                                <input type="text" id="txt_deserved_amount" class="form-control" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))">
                            </div>

                            <input type="hidden" id="txt_status" name="status" class="form-control">

                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">

                <?php if (HaveAccess($store_url)): ?>
                    <button class="btn ripple btn-primary" type="button" id="insert_data">حفظ البيانات</button>
                    <button class="btn ripple btn-warning" type="button" onclick="get_emp_data_exception()">رصيد استثنائي</button>
                <?php endif; ?>
                <button class="btn ripple btn-secondary" data-bs-dismiss="modal" type="button">اغلاق</button>
            </div>
        </div>
    </div>
</div>
<!--End addModal--اضافة موظف للكشف-->

<!--Start  EditModal تعديل موظف للكشف-->
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
                                <input type="text" readonly name="emp_name_m" id="txt_emp_name_m" class="form-control">
                            </div>

                            <div class="form-group  col-md-2">
                                <label>الشهر </label>
                                <input type="text" readonly name="month_m" id="txt_month_m" class="form-control">
                            </div>

                            <div class="form-group col-md-2">
                                <label>المسمى الوظيفي</label>
                                <input type="text" readonly name="no_admin_name_m" id="txt_w_no_admin_name_m" class="form-control" >
                            </div>

                            <div class="form-group col-md-2">
                                <label>فئة الاتصال</label>
                                <input type="text" readonly name="category_m" id="txt_category_m" class="form-control" >
                            </div>

                            <div class="form-group col-md-2">
                                <label>مبلغ الفئة</label>
                                <input type="text" readonly name="category_amount_m"  id="txt_category_amount_m" class="form-control" >
                            </div>

                            <div class="form-group col-md-2">
                                <label>الفاتورة الشهرية</label>
                                <input type="text" readonly name="billing_value_m"  id="txt_billing_value_m" class="form-control" >
                            </div>

                            <div class="form-group col-md-2">
                                <label>المبلغ المستحق</label>
                                <input type="text" name="deserved_amount_m"  id="txt_deserved_amount_m" class="form-control">
                            </div>

                        </div>
                    </div>

                </form>

            </div>
            <div class="modal-footer">

                <?php if (HaveAccess($update_url)): ?>
                    <button class="btn ripple btn-primary" type="button" onclick="update_data()">
                        <i class="fa fa-recycle"></i> اعادة احتساب
                <?php endif; ?>

                </button>
                <button class="btn ripple btn-secondary" data-bs-dismiss="modal" type="button">اغلاق</button>
            </div>
        </div>
    </div>
</div>
<!--End EditModal تعديل موظف للكشف-->

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
                <button class="btn ripple btn-secondary" data-bs-dismiss="modal" type="button">اغلاق</button>
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
           branch_no:$('#dp_branch_no').val(),month:$('#txt_month').val(),emp_no:$('#dl_s_emp_no').val() ,status_type:$('#dl_status_type').val()
        });
    }

    function LoadingData(){
        ajax_pager_data('#page_tb > tbody',{
            branch_no:$('#dp_branch_no').val(),month:$('#txt_month').val(),emp_no:$('#dl_s_emp_no').val(),status_type:$('#dl_status_type').val()    
        });
    }

   function search(){
        var month = $('#txt_month').val();
        if (month == '') {
             danger_msg('يرجى  ادخال الشهر');
        }else{
             get_data('{$get_page_url}',{page: 1,branch_no:$('#dp_branch_no').val(),month:$('#txt_month').val(),emp_no:$('#dl_s_emp_no').val(),status_type:$('#dl_status_type').val() },function(data){
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
        var status_type = $('#dl_status_type').val();
        info_msg('تنبيه','جاري تجهيز ملف الاكسل ..'); 
            location.href = '{$get_excel_url}?branch_no='+branch_no+'&month='+month+'&emp_no='+emp_no+'&status_type='+status_type;
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
    
    $('#dp_emp_no').change(function(){
        get_emp_data(0)
    });    

    function get_emp_data(){
        var no =  $('#dp_emp_no').val();
        var the_month =  $('#the_month').val();
        if (no == '') {
            return -1;
        }else {
            get_data('{$get_emp_data_1}', {no: no ,the_month:the_month}, function (data) {

                if (data.length == 1){
                    $.each(data, function (i, value) {
                        $('#txt_branch_name').val(value.BRANCH_NAME);
                        $('#txt_branch_no').val(value.BRANCH_NO);
                        $('#txt_w_no_admin_name').val(value.JOB_TITLE);
                        $('#txt_category_amount').val(value.TB_AMOUNT);
                        $('#category').val(value.TB_NAME);
                        $('#txt_billing_value').val(value.BILL_AMOUNT);
                        $('#txt_deserved_amount').val(value.BILL_AMOUNT);
                        $('#txt_status').val(1);
                    });
                }else{
                    $('#txt_branch_name').val('');
                    $('#txt_branch_no').val('');
                    $('#txt_w_no_admin_name').val('');
                    $('#txt_category_amount').val('');
                    $('#txt_billing_value').val('');
                    $('#category').val('');
                    $('#txt_deserved_amount').val('');
                    $('#txt_status').val('');
                }
                
                if (data.length = 2 ){
                    get_data('{$get_emp_data_2}', {no: no ,the_month:the_month}, function (data) {
                    
                        $.each(data, function (i, value) {
                            $('#txt_branch_name').val(value.BRANCH_NAME);
                            $('#txt_branch_no').val(value.BRANCH_NO);
                            $('#txt_w_no_admin_name').val(value.JOB_TITLE);
                            $('#txt_category_amount').val(value.TB_AMOUNT);
                            $('#category').val(value.TB_NAME);
                            $('#txt_billing_value').val(value.BILL_AMOUNT);
                            $('#txt_deserved_amount').val(value.BILL_AMOUNT);
                            $('#txt_status').val(1);
                    });
                    
                });
                
                }else{
                        $('#txt_branch_name').val('');
                        $('#txt_branch_no').val('');
                        $('#txt_w_no_admin_name').val('');
                        $('#txt_category_amount').val('');
                        $('#txt_billing_value').val('');
                        $('#category').val('');
                        $('#txt_deserved_amount').val('');
                        $('#txt_status').val('');
                }
            
            });
        }
    } //end get emp data
    
    function get_emp_data_exception(){

        var no =  $('#dp_emp_no').val();
        var the_month =  $('#the_month').val();
        if (no == '') {
            return -1;
        }else {
            get_data('{$get_emp_data_exception}', {no: no ,the_month:the_month }, function (data) {

                if (data.length == 1){
                    $.each(data, function (i, value) {
                        $('#txt_branch_name').val(value.BRANCH_NAME);
                        $('#txt_branch_no').val(value.BRANCH_NO);
                        $('#txt_w_no_admin_name').val(value.JOB_TITLE);
                        $('#txt_category_amount').val(value.RESIDUAL);
                        $('#category').val(value.CATEGORY_NAME);
                        $('#txt_billing_value').val(value.BILL_AMOUNT);
                        $('#txt_deserved_amount').val(40);
                        $('#txt_status').val(2);
                    });
                }else{
                    $('#txt_branch_name').val('');
                    $('#txt_branch_no').val('');
                    $('#txt_w_no_admin_name').val('');
                    $('#txt_category_amount').val('');
                    $('#category').val('');
                    $('#txt_billing_value').val('');
                    $('#txt_deserved_amount').val('');
                    $('#txt_status').val('');
                }
            });
        }
    } //end get emp data
    
   function delete_row(a,id){
        if(confirm('هل تريد حذف السجل ؟')){
            get_data('{$delete_url}',{id:id},function(data){
                if(data == '1'){
                    success_msg('رسالة','تم حذف السجل بنجاح ..');
                    $(a).closest('tr').remove();
                    get_to_link(window.location.href);
                }
            },'html');
        }
   }

     //insert_data Data  event handler.
   $(document).on("click", "#insert_data", function(arg){
      arg.preventDefault();
       var emp_no = $('#dp_emp_no').val();
       var month = $('#the_month').val();
       var emp_branch =$('#txt_branch_no').val();
       var deserved_amount =$('#txt_deserved_amount').val();
       var category_amount =$('#txt_category_amount').val();
       var billing_value =$('#txt_billing_value').val();
       var category =$('#category').val();
       var status =$('#txt_status').val();
   
       if (emp_no == '') {
            danger_msg('الرجاء ادخال رقم الموظف');
            return false;
       }else if (deserved_amount == '') {
            danger_msg('الرجاء ادخال المبلغ المستحق');
            return false;
      }else if (billing_value == '') {
            danger_msg('الرجاء ادخال الفاتورة الشهرية');
            return false;
      }else if (parseFloat(deserved_amount) > parseFloat(billing_value)) {
            danger_msg('المبلغ المستحق اكبر من الفاتورة الشهرية');
            return false;
      }else if (parseFloat(deserved_amount) > parseFloat(category_amount)) {
            danger_msg('المبلغ المستحق اكبر من مبلغ الفئة');
            return false;
      }else{
           get_data('{$store_url}',{emp_no:emp_no,month:month,emp_branch:emp_branch,the_month:month,category:category,deserved_amount:deserved_amount ,category_amount:category_amount ,billing_value:billing_value ,status : status }, function (data) {
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
        $('#txt_w_no_admin_name_m').val('');
        $('#txt_category_amount_m').val('');
        $('#txt_deserved_amount_m').val('');
        $('#category_m').val('');
        $('#billing_value_m').val('');
        $('#EditModal').modal('show');
        $("#EditModal").appendTo("body");
        
        var tr = obj.closest('tr');
        var pp_ser  = $('input[name="pp_ser"]',tr).val();
        var emp_no_m  = $('input[name="no"]',tr).val();
        var emp_name_m  = $('input[name="emp_name"]',tr).val();
        var month_m  = $('input[name="month"]',tr).val();
        var category_name  = $('input[name="category_name"]',tr).val();
        var category_amount  = $('input[name="category_amount"]',tr).val();
        var deserved_amount  = $('input[name="deserved_amount"]',tr).val();
        var billing_value_ma  = $('input[name="billing_value_ma"]',tr).val();
        var admin_name  = $('input[name="admin_name"]',tr).val();
        
        var job_type  = $('input[name="job_type_ma"]',tr).val();
        $('input[name="pp_ser_m"]').val(pp_ser);
        $('input[name="emp_no_m"]').val(emp_no_m);
        $('input[name="emp_name_m"]').val(emp_name_m);
        $('input[name="month_m"]').val(month_m);
        $('input[name="no_admin_name_m"]').val(admin_name);
        $('input[name="category_m"]').val(category_name);
        $('input[name="category_amount_m"]').val(category_amount);
        $('input[name="deserved_amount_m"]').val(deserved_amount);
        $('input[name="billing_value_m"]').val(billing_value_ma);
   }
   //تعديل علاوة مخاطرة
   function update_data(){
       var serial = $('input[name="pp_ser_m"]').val();
       var no = $('#txt_emp_no_m').val();
       var month = $('#txt_month_m').val();
       
       var emp_no = $('#txt_emp_no_m').val();
       var month = $('#txt_month_m').val();
       var emp_branch =$('#txt_branch_no').val();
       var category_amount =$('#txt_category_amount_m').val();
       var category =$('#txt_category_m').val();
       var deserved_amount =$('#txt_deserved_amount_m').val();
       var billing_value =$('#txt_billing_value_m').val();

       if (emp_no == '') {
            danger_msg('يرجى ادخال اسم الموظف');
            return -1;
        }else if (deserved_amount == '') {
            danger_msg('الرجاء ادخال المبلغ المستحق');
            return false;
      }else if (billing_value == '') {
            danger_msg('الرجاء ادخال الفاتورة الشهرية');
            return false;
      }else if (parseFloat(deserved_amount) > parseFloat(billing_value)) {
            danger_msg('المبلغ المستحق اكبر من الفاتورة الشهرية');
            return false;
      }else if (parseFloat(deserved_amount) > parseFloat(category_amount)) {
           danger_msg('المبلغ المستحق اكبر من مبلغ الفئة');
            return false;
      }else {
       if(confirm('هل تريد بالتأكيد اعادة احتساب بدل الاتصال ')){
           get_data('{$update_url}', {pp_ser:serial,emp_no_m:emp_no,month_m:month,category_m:category,category_amount_m:category_amount ,deserved_amount_m:deserved_amount ,billing_value_m:billing_value} , function(ret){    
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
        var emp_no = $('#dp_emp_no').val();
        var status = $('#dl_status_type').val();

        _showReport('{$report_url}&report_type=pdf&report=call_pay_pdf&p_the_month='+month+'&p_branch_id='+branch_no+'&p_emp_no='+emp_no+'&p_status='+status);
    }
    
</script>
SCRIPT;
sec_scripts($scripts);
?>
