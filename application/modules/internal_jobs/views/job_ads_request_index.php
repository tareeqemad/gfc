<?php
/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 18/10/2022
 * Time: 11:40 ص
 */
$MODULE_NAME = 'internal_jobs';
$TB_NAME = 'job_ads_request';
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$export_excel_url = base_url("$MODULE_NAME/$TB_NAME/export_excel");
$get_entry_grievance_url = base_url("$MODULE_NAME/$TB_NAME/public_get_entry_grievance");
$entry_grievance_url = base_url("$MODULE_NAME/$TB_NAME/public_entry_grievance");
$edit_status_url = base_url("$MODULE_NAME/$TB_NAME/edit_status");//تعديل الحالة
$get_request_data_url = base_url("$MODULE_NAME/$TB_NAME/public_get_request_data");//تعديل
$report_url = base_url("JsperReport/showreport?sys=hr/internal_job");
$report_sn = report_sn();
?>
<!-- PAGE-HEADER -->
<div class="page-header">
    <div>
        <h1 class="page-title"><?= $title ?></h1>
    </div>
    <div class="ms-auto pageheader-btn">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">الاداري</a></li>
            <li class="breadcrumb-item active" aria-current="page">طلبات الوظائف</li>
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
                    <?php /*if( HaveAccess($delay_sum_url)): */ ?>
                    <a class="btn btn-secondary" href="<? /*= $delay_sum_url*/ ?>"><i class="fa fa-plus"></i>
                        جديد
                    </a>
                    <?php /*endif;*/ ?>
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
                                        <option value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
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
                                    <?php if ($action == 0) { ?>
                                        <option <?php if ($this->user->emp_no == $row['EMP_NO']) {
                                            echo 'selected';
                                        } ?> value="<?= $row['EMP_NO'] ?>"><?= $row['EMP_NO'] . ': ' . $row['EMP_NAME'] ?></option>
                                    <?php } else { ?>
                                        <option value="<?= $row['EMP_NO'] ?>"><?= $row['EMP_NO'] . ': ' . $row['EMP_NAME'] ?></option>
                                    <?php } ?>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group col-md-2">
                            <label> الوظيفة</label>
                            <select name="ads_ser" id="dp_ads_ser" class="form-control sel2">
                                <option value="">_______</option>
                                <?php foreach ($ads_arr as $row) : ?>
                                    <option value="<?= $row['SER'] ?>"><?= $row['ADS_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>


                        <div class="form-group col-md-2">
                            <label> الحالة</label>
                            <select name="status" id="dp_status" class="form-control sel2">
                                <option value="">_______</option>
                                <?php foreach ($status_cons as $row) : ?>
                                    <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group col-md-2">
                            <label> الموظفين المدخلين تظلم</label>
                            <select name="grievance_status" id="dp_grievance_status" class="form-control sel2">
                                <option value="">_______</option>
                                <option value="1">عرض</option>
                            </select>
                        </div>


                    </div>
                    <div class="row">
                        <div class="flex-shrink-0">
                            <button type="button" onclick="javascript:search();" class="btn btn-primary">
                                <i class="fa fa-search"></i>
                                بحث
                            </button>
                            <?php if (HaveAccess('internal_jobs/job_ads_request/show_report')) { ?>
                                <button type="button" onclick="javascript:print_pdf();" class="btn btn-blue">
                                    <i class="far fa-file-pdf"></i>
                                    عرض التقرير
                                </button>
                            <?php } ?>

                            <button type="button" onclick="$('#page_tb').tableExport({type:'excel',escape:'false'});"
                                    class="btn btn-success">
                                <i class="fas fa-file-excel"></i>
                                كشف اكسل
                            </button>
                        </div>
                    </div>
                    <hr>
                    <div id="container">
                        <?php echo modules::run($get_page_url, $page, $action); ?>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Extra-large  modal -->
    <div class="modal fade" id="grievance">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">بيانات</h6>
                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span
                                aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body" id="public_grievance_body">

                </div>
                <div class="modal-footer">
                    <button class="btn ripple btn-primary" id="btn_add_grievance" type="button">حفظ</button>
                    <button class="btn ripple btn-secondary" data-bs-dismiss="modal" type="button">اغلاق</button>
                </div>
            </div>
        </div>
    </div>
    <!--End Extra-large modal -->



    <!--every_modal modal -->
    <div class="modal fade" id="every_modal">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">تعديل الحالة والسبب</h6>
                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span
                                aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body" id="every_body">


                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!--End every_modal modal -->
</div>
<?php
$scripts = <<<SCRIPT
<script type="text/javascript">

   var table = '{$TB_NAME}';
   var action = '{$action}';
   var count = 0;
       
   $('.sel2:not("[id^=\'s2\']")').select2();
    
   

   if (action == 0){
        $('.sel2').select2({
          disabled: true
        });
   }
    
   $(function(){
       reBind();
   });

   function reBind(){
       ajax_pager({
          branch_no:$('#dp_branch_no').val(),emp_no:$('#dp_emp_no').val(),ads_ser:$('#dp_ads_ser').val(),
          status:$('#dp_status').val(),grievance_status:$('#dp_grievance_status').val()
        });
   }
 
   function LoadingData(){
      ajax_pager_data('#page_tb ',{
           branch_no:$('#dp_branch_no').val(),emp_no:$('#dp_emp_no').val(),ads_ser:$('#dp_ads_ser').val(),
           status:$('#dp_status').val(),grievance_status:$('#dp_grievance_status').val()
      });
    }
    
   function search(){
       get_data('{$get_page_url}',{page: 1,
          branch_no:$('#dp_branch_no').val(),emp_no:$('#dp_emp_no').val(),ads_ser:$('#dp_ads_ser').val(),
          status:$('#dp_status').val(),grievance_status:$('#dp_grievance_status').val()
       },function(data){
                $('#container').html(data);
                reBind();
       },'html');
   }
   
   function export_excel(){
        var fewSeconds = 10;
        var branch_no = $('#dp_branch_no').val();
        var emp_no = $('#dp_emp_no').val();
        var ads_ser = $('#dp_ads_ser').val();
        var status = $('#dp_status').val();
        var grievance_status = $('#dp_grievance_status').val();
        info_msg('تنبيه','جاري تجهيز ملف الاكسل ..'); 
            location.href = '{$export_excel_url}?branch_no='+branch_no+'&emp_no='+emp_no+'&ads_ser='+ads_ser+'&status='+status+'&grievance_status='+grievance_status;
            setTimeout(function(){
                info_msg('تنبيه','جاري تجهيز ملف الاكسل ..'); 
        },  fewSeconds*1000);
   }
     
     
   function entry_grievance(ser){
       showLoading();
      // Display Modal
      $('#grievance').modal('show');
        $.ajax({
            url: '{$get_entry_grievance_url}',
            type: 'post',
            data: {ser: ser},
            success: function(response){
                // Add response in Modal body
                $('#public_grievance_body').html(response);
                HideLoading();
            }
        });
   } 
   
   $('#btn_add_grievance').click(function(e){
	  e.preventDefault();
      if (confirm('هل انت متأكد من حفظ البيانات')){
            var values= {grievance_ser: $('#h_grievance_ser').val() , grievance_note: $('#txt_grievance_note').val() };
            get_data('{$entry_grievance_url}', values, function(data){
                 if (data == 1){
                     success_msg('تم اضافة التظلم بنجاح');
                     $('#grievance').modal('hide');
                 }else{
                     danger_msg(data);
                 }     
           }); //end get data function
      }
   });
   
   
    function print_pdf(){
        var emp_no = $('#dp_emp_no').val();
        var ads_ser = $('#dp_ads_ser').val();
        var status = $('#dp_status').val();
        var grievance_status = $('#dp_grievance_status').val();
        _showReport('{$report_url}&report_type=pdf&report=internal_job_applicants&p_emp_no='+have_no_val(emp_no)+'&p_ads_ser='+have_no_val(ads_ser)+'&p_status='+have_no_val(status)+'&p_grievance_status='+have_no_val(grievance_status)+'&sn={$report_sn}');
    }
    
     // check if var have value or null //
    function have_no_val(v) {
        if(v == null || v == 0) {
            return v = '';
        }else {
            return v;
        }
    }
  


    $('.edit_status').change(function(){
        var this_select= $(this);
        this_select.attr('disabled','disabled');
        var ser= this_select.attr('data-ser');
        var status= this_select.val();
        get_data('{$edit_status_url}', {ser:ser, status:status} ,function(data){
            if(data==1){
                success_msg('رسالة','تم حفظ البيانات بنجاح ..');
            }else{
                danger_msg('تحذير..',data);
            }
        },'html');
    });

    
    function update_detail(id){
         showLoading();
           // Display Modal
        $('#every_modal').modal('show');
        $.ajax({
            url: '{$get_request_data_url}',
            type: 'post',
            data: {id: id},
            success: function(response){
                // Add response in Modal body
                $('#every_body').html(response);
                $('#txt_interview_date').datetimepicker({
                    format: 'DD/MM/YYYY',
                    pickTime: false,
            
                 });
            },
             complete: function() {
               HideLoading();
            }
        });
    }
  
  function update_request_data(){
     $.post($('#job_form').attr('action'),$('#job_form').serialize(), function(data){
         if (data >=1){
                success_msg('تم حفظ الببيانات بنجاح');
                search();
                $('#every_modal').modal('hide');
         }else{
             danger_msg(data);
             return -1;
         }
     });
  }
  

</script>
SCRIPT;
sec_scripts($scripts);
?>
