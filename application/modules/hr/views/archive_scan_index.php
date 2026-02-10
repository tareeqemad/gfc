<?php
$MODULE_NAME = 'hr';
$TB_NAME = 'Archive_scan';
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$show_attachment_file_url = base_url("$MODULE_NAME/$TB_NAME/public_show_scan_files");
$show_folder_name_url = base_url("$MODULE_NAME/$TB_NAME/public_get_folder_name");
$edit_folder_name_url = base_url("$MODULE_NAME/$TB_NAME/edit_folder_name");

$edit_detail_url = base_url("$MODULE_NAME/$TB_NAME/public_get_edit_detail");
$update_detail_url = base_url("$MODULE_NAME/$TB_NAME/public_update_detail");
$change_barcode_type_url = base_url("$MODULE_NAME/$TB_NAME/change_barcode_type");
$delete_row_url = base_url("$MODULE_NAME/$TB_NAME/delete_row");
$get_id_url = base_url("$MODULE_NAME/$TB_NAME/public_get_details");


$generate_barcode_url = base_url("$MODULE_NAME/$TB_NAME/generate_barcode_index");
?>
    <!-- PAGE-HEADER -->
    <div class="page-header">
        <div>
            <h1 class="page-title"><?= $title ?></h1>
        </div>
        <div class="ms-auto pageheader-btn">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= site_url('cpanel'); ?>">النظام الاداري</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= $title ?></li>
            </ol>
        </div>
    </div>
    <!-- PAGE-HEADER END -->
    <!-- ROW OPEN -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                       استعلام | أرشيف الموظفين
                    </h3>
                    <div class="card-options">
                        <a href="<?= $generate_barcode_url?>" class="btn btn-secondary">
                            <i class="fa fa-barcode"></i>
                            توليد باركود
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form id="<?= $TB_NAME ?>_form">
                        <div class="form-row">
                            <div class="form-group col-md-2">
                                <label class="text-blue fs-18 fw-bold">الموظف</label>
                                <select name="emp_no" id="dp_emp_no" class="form-control sel2">
                                    <option value="">_________</option>
                                    <?php foreach ($emp_no_cons as $row) : ?>
                                        <option value="<?= $row['EMP_NO'] ?>"><?= $row['EMP_NO'] . ': ' . $row['EMP_NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group col-md-2">
                                <label class="text-primary fs-18 fw-bold">الباركود</label>
                                <input type="text" name="barcode" id="txt_barcode" class="form-control">
                            </div>
                            <div class="form-group col-md-2">
                                <label class="text-primary fs-18 fw-bold">التصنيفات الرئيسية</label>
                                <select name="scan_type_grand" id="dp_scan_type_grand" class="form-control sel2">
                                    <option value="">_________</option>
                                    <?php foreach ($scan_type_arr as $row) : ?>
                                        <option value="<?= $row['TYPE_NO'] ?>"><?= $row['TYPE_CODE']. ': ' . $row['TYPE_NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group col-md-2">
                                <label class="text-secondary fs-18 fw-bold">التصنيف الأب</label>
                                <select name="scan_type_parent" id="dp_scan_type_parent" class="form-control sel2">
                                    <option value="">_________</option>
                                </select>
                            </div>
                            <div class="form-group col-md-2">
                                <label class="text-success fs-18 fw-bold">التصنيف الابن</label>
                                <select name="scan_type_son" id="dp_scan_type_son" class="form-control sel2">
                                    <option value="">_________</option>
                                </select>
                            </div>
                            <div class="form-group col-md-2">
                                <label class="text-primary fs-18 fw-bold">اصدار الملف</label>
                                <select name="version_no" id="dp_version_no" class="form-control">
                                    <option value="2">جديد</option>
                                    <option value="1">قديم</option>
                                    <option value="">_________</option>

                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-2">
                                <label for="txt_folder" class="text-primary fs-18 fw-bold">اسم الملف</label>
                                <input type="text" name="folder" id="txt_folder" class="form-control"
                                       autocomplete="off">
                            </div>
                            <div class="form-group  col-md-2">
                                <label for="txt_from_month" class="text-primary fs-18 fw-bold"> من شهر </label>
                                <input type="text" class="form-control" id="txt_from_month" name="from_month"
                                       autocomplete="off">
                            </div>
                            <div class="form-group  col-md-2">
                                <label for="txt_to_month" class="text-primary fs-17 fw-bold"> من شهر </label>
                                <input type="text" class="form-control" id="txt_to_month" name="to_month"
                                       autocomplete="off">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="flex-shrink-0">
                                <button type="button" onclick="javascript:search();" class="btn btn-primary me-2">
                                    <i class="fa fa-search"></i>
                                    إستعلام
                                </button>
                                <button type="button" onclick="javascript:clear_form();" class="btn btn-cyan-light"><i
                                            class="fa fa-eraser"></i>
                                    تفريغ الحقول
                                </button>
                            </div>
                        </div>
                    </form>
                    <hr>
                    <div id="container">
                        <?php /*echo modules::run($get_page, $page);*/ ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- DetailModal Adopt -->
    <div class="modal fade" id="DetailModal">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">بيانات </h6>
                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span
                                aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body" id="public_body">

                </div>
                <div class="modal-footer">
                    <button class="btn ripple btn-secondary" data-bs-dismiss="modal" type="button">اغلاق</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End DetailModal Adopt -->


    <!--Start EditModal Adopt -->
    <div class="modal fade" id="EditModal">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">بيانات</h6>
                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span
                                aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="edit-modal-body">

                </div>
            </div>
        </div>
    </div>
    <!--End EditModal Adopt -->

<?php
$scripts = <<<SCRIPT
<script>

      $('.sel2:not("[id^=\'s2\']")').select2();

      $('#txt_month_des,#txt_from_month,#txt_to_month').datetimepicker({
            format: 'YYYYMM',
            minViewMode: 'months',
            pickTime: false,
      });
      
      $("#dp_scan_type_grand").change(function(){
          NewVal = $(this).val();
          setTimeout(function() {
             $.ajax({
                type: "GET",
                url: '{$get_id_url}/ARCH_FILE/SCAN_TYPE_CHILD_GET/'+NewVal,
                success: function (data) { 
                  if(data.length ==0){
                      $('#dp_scan_type_parent option').remove();
                      $('#dp_scan_type_parent').append($('<option/>').attr("value", '').text(''));
                      $('#dp_scan_type_parent').prop("selected",true);
                                            
                      /*$("#dp_scan_type_parent").change();*/
                   }else{
                        $('#dp_scan_type_parent option').remove();
                        $('#dp_scan_type_parent').append($('<option/>').attr("value", '').text('---اختر---'));
                        $.each(data,function (i,option) {
                           $('#dp_scan_type_parent').append($('<option/>').attr("value", option.TYPE_NO).text(option.TYPE_NAME));
                        })
                        /*$("#dp_scan_type_parent").change();*/
                   }
                }
            });
      }, 500);
    });
      
      
    $("#dp_scan_type_parent").change(function(){
          NewVal = $(this).val();
          setTimeout(function() {
             $.ajax({
                type: "GET",
                url: '{$get_id_url}/ARCH_FILE/SCAN_TYPE_CHILD_GET/'+NewVal,
                success: function (data) { 
                  if(data.length ==0){
                      $('#dp_scan_type_son option').remove();
                      $('#dp_scan_type_son').append($('<option/>').attr("value", 0).text('---اختر---'));
                      $('#dp_scan_type_son').prop("selected",true);
 
                   }else{
                        $('#dp_scan_type_son option').remove();
                        $('#dp_scan_type_son').append($('<option/>').attr("value", '').text('---اختر---'));
                        $.each(data,function (i,option) {
                           $('#dp_scan_type_son').append($('<option/>').attr("value", option.TYPE_NO).text(option.TYPE_NAME));
                        })
                        $("#dp_scan_type_son").change();
                   }
                }
            });
      }, 500);
    });
      
     $(function(){
            reBind();
      });
      
     function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
        $('.sel2').select2('val',0);
      }
     function reBind(){
         ajax_pager({
             emp_no:$('#dp_emp_no').val(), barcode:$('#txt_barcode').val(),scan_type_grand:$('#dp_scan_type_grand').val(),
             scan_type_parent:$('#dp_scan_type_parent').val(),scan_type_son:$('#dp_scan_type_son').val(),
             folder_name_s :$('#txt_folder').val(),from_month :$('#txt_from_month').val(),to_month :$('#txt_to_month').val(),version_no :$('#dp_version_no').val()
         });
       }
     function LoadingData(){
        ajax_pager_data('#page_tb > tbody',{
             emp_no:$('#dp_emp_no').val(), barcode:$('#txt_barcode').val(),scan_type_grand:$('#dp_scan_type_grand').val(),
             scan_type_parent:$('#dp_scan_type_parent').val(),scan_type_son:$('#dp_scan_type_son').val(),
             folder_name_s :$('#txt_folder').val(),from_month :$('#txt_from_month').val(),to_month :$('#txt_to_month').val(),version_no :$('#dp_version_no').val()
        });
      }
     function search(){
          if( $('#txt_ser').val()== '' && $('#dp_emp_no').val()== '' && $('#txt_barcode').val()== '' ){
            warning_msg('تنويه','يجب اختيار محدد بحث');
            return 0;
        }else {
            var values= {page:1, 
             emp_no:$('#dp_emp_no').val(), barcode:$('#txt_barcode').val(),scan_type_grand:$('#dp_scan_type_grand').val(),
             scan_type_parent:$('#dp_scan_type_parent').val(),scan_type_son:$('#dp_scan_type_son').val(),
             folder_name_s :$('#txt_folder').val(),from_month :$('#txt_from_month').val(),to_month :$('#txt_to_month').val(),version_no :$('#dp_version_no').val()
            };
            get_data('{$get_page_url}',values ,function(data){
                    $('#container').html(data);
                    reBind();
            },'html');
        }
      } //end search
    
    
 
    
     function show_attachment_files(barcode) {
        showLoading();
       // Display Modal
        $('#DetailModal').modal('show');
        $.ajax({
            url: '{$show_attachment_file_url}',
            type: 'post',
            data: {barcode: barcode},
            success: function(response){
                // Add response in Modal body
                $('#public_body').html(response);
            },
            complete: function() {
                HideLoading();
            }
        });
     }
     
     function  update_folder_name(emp_no,barcode){
         showLoading();
         $('#DetailModal').modal('show');
        $.ajax({
            url: '{$show_folder_name_url}',
            type: 'post',
            data: {emp_no:emp_no,barcode: barcode},
            success: function(response){
                // Add response in Modal body
                $('#public_body').html(response);
                
            },
            complete: function() {
                HideLoading();
            }
        });
     }
     
     function saveFolderName(){
        get_data('{$edit_folder_name_url}',{
           no:$('#txt_no').val(), barcode:$('#txt_barcode_e').val(), folder_name:$('#txt_folder_name').val(),notes:$('#txt_notes').val(),month_des:$('#txt_month_des').val(),h_action:$('#txt_h_action').val()
       }, function (data) {
           if (parseInt(data) >= 1){
                success_msg('رسالة','تم حفظ البيانات بنجاج ..');
                $('#DetailModal').modal('hide');
                search()
            } else {
                danger_msg('تحذير..', data);
            }
          }, 'html');   
     }
  
     function download_files(files) {
        function download_next(i) {
         if (i >= files.length) {
           return;
         }
         var a = document.createElement('a');
         a.href = files[i].download;
         a.target = '_blank';
        
         if ('download' in a) {
           a.download = files[i].download;
         }
        
         (document.body || document.documentElement).appendChild(a);
         if (a.click) {
           a.click(); // The click method is supported by most browsers.
         }
         else {
            window.open(files[i].download);
         }
         console.log('1');
         a.parentNode.removeChild(a);
         setTimeout(function() {
           download_next(i + 1);
         }, 5000);
        }
        // Initiate the first download.
        download_next(0);
     }

     function do_dl() {
          var FileListArray = [];
          $('input[name^="txt_h_url"]').each( function() {
              FileListArray.push({
                    'url_':this.value,
                });
           });
          FileListArray.forEach((report, index) => {
              download_files([
               { download: report.url_},
             ]);
          });
    }
    
    
    
    function edit_detail_row(barcode) {
        showLoading();
       // Display Modal
        $('#EditModal').modal('show');
        $.ajax({
            url: '{$edit_detail_url}',
            type: 'post',
            data: {barcode: barcode},
            success: function(response){
                // Add response in Modal body
                $('#edit-modal-body').html(response);
            },
            complete: function() {
                HideLoading();
            }
        });
     }
     
    function update_records(barcode){
        if(confirm('هل تريد تعديل  ؟!')){
            var scan_type =  $('#dp_scan_type_m').val();
            var version_no =  $('#dp_version_no_m').val();
                   get_data('{$update_detail_url}', {barcode:barcode,scan_type:scan_type,version_no:version_no} , function(ret){
                     if(ret>= 1){
                         success_msg('رسالة','تم تعديل البيانات ..');
                         $('#EditModal').modal('hide');
                         search();
                    }else{
                        warning_msg('تحذير..',ret);
                        return -1;
                    }
                  }, 'html');
                
          } //end if else 
     } //end update_calculated_hours*/
    
    
     function delete_row(a,barcode){
        if(confirm('هل تريد حذف السجل ؟')){
           get_data('{$delete_row_url}',{barcode:barcode},function(data){
                if(parseInt(data) >= 1){
                    success_msg('رسالة','تم حذف السجل بنجاح ..');
                    $(a).closest('tr').remove();
                }
            },'html');
        }
     }
    
</script>
SCRIPT;
sec_scripts($scripts);
?>