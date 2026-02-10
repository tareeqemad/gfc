<?php
$MODULE_NAME = 'hr';
$TB_NAME = 'Archive_scan';
$index_url = base_url("$MODULE_NAME/$TB_NAME/index");
$barcode_detail_grand_url = base_url("$MODULE_NAME/$TB_NAME/public_barcode_detail_grand");
$barcode_detail_parent_url = base_url("$MODULE_NAME/$TB_NAME/public_barcode_detail_parent");
$barcode_detail_son_url = base_url("$MODULE_NAME/$TB_NAME/public_barcode_detail_son");
$generate_barcode_url = base_url("$MODULE_NAME/$TB_NAME/public_generate_barcode");
$get_page_barcode_list_url = base_url("$MODULE_NAME/$TB_NAME/public_get_page_barcode_list");
$managment_barcode_index_url = base_url("$MODULE_NAME/$TB_NAME/managment_barcode_index");
$report_url = base_url("JsperReport/showreport?sys=hr");
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
                  توليد باركود للورقة
                </h3>
                <div class="card-options">
                    <a href="<?= $managment_barcode_index_url?>" class="btn btn-blue me-2">
                        <i class="fa fa-barcode"></i>
                        ادارة الباركود
                    </a>

                    <a href="<?= $index_url?>" class="btn btn-secondary">
                        <i class="fa fa-archive"></i>
                      أرشيف الموظفين
                    </a>

                </div>
            </div>
            <div class="card-body">
                <form id="<?= $TB_NAME ?>_form">
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label class="text-blue fs-18 fw-bold">الموظف</label>
                            <select name="emp_no" id="dp_emp_no" class="form-control sel2">
                                <option value="0">_________</option>
                                <?php foreach ($emp_no_cons as $row) : ?>
                                    <option value="<?= $row['EMP_NO'] ?>"><?= $row['EMP_NO'] . ': ' . $row['EMP_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <?php
                        foreach ($scan_type_arr as $item) { ?>
                            <div class="col-xl-2 col-md-4 col-sm-6">
                                <div class="card p-0 ">
                                    <br>
                                    <div class="card-body pt-0 text-center">
                                        <div class="file-manger-icon">
                                            <a href="#">
                                                <img src="<?= base_url() ?>assets-n/images/folder.png" alt="img" class="br-7"  onclick="showData(<?=$item['TYPE_NO']?>)">
                                            </a>
                                        </div>
                                        <h6 class="mb-1 fw-bold fs-18 text-primary"><?=$item['TYPE_NAME']?></h6>
                                        <span class="text-danger fs-15 fw-bold"><?=$item['TYPE_CODE']?></span>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

                    </div>
                    <hr>
                    <div id="container">
                        <?php /*echo modules::run($get_page, $page);*/ ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!--Start publicModal-->
<div class="modal fade"  id="publicModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-fullscreen" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">بيانات</h5>
                <button  class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" id="public_body">


            </div>
        </div>
    </div>
</div>
<!--End publicModal -->
<?php
$scripts = <<<SCRIPT
<script>

      $('.sel2:not("[id^=\'s2\']")').select2();
      
        
      
      function showData(type_no){
        var emp_no =$('#dp_emp_no').val();
        if (emp_no == 0){
            danger_msg('يرجى ادخال الموظف للاستعلام');
            return -1;
        }
        showLoading();
       // Display Modal
        $('#publicModal').modal('show');
        $.ajax({
            url: '{$barcode_detail_grand_url}',
            type: 'post',
            data: {type_no: type_no,emp_no:emp_no},
            success: function(response){
                // Add response in Modal body
                $('#public_body').html(response);
                $('#div_parent').empty();
                $('#div_son').empty();
            },
            complete: function() {
                HideLoading();
            }
        });
         
      }
      
      function showParent(type_no,cnt_m){
           var emp_no =$('#txt_h_emp_no').val();
           if (cnt_m == 0){
               success_msg('رسالة','لا يوجد أباء وتم اختياره لتوليد باركود');
               $('#txt_type_no').val(type_no);
               return -1;
           }else{
            $('#txt_type_no').val('');
            showLoading();
            $.ajax({
                url: '{$barcode_detail_parent_url}',
                type: 'post',
                data: {type_no: type_no,emp_no:emp_no},
                success: function(response){
                    // Add response in Modal body
                    $('#div_parent').html(response);
                    $('#div_son').empty();
                  
                },
                complete: function() {
                    HideLoading();
                }
            });  
          }
      }
      
      
      function showSon(type_no,cnt_m){
           var emp_no =$('#txt_h_emp_no').val();
           if (cnt_m == 0){
               success_msg('رسالة','لا يوجد أباء وتم اختياره لتوليد باركود');
               $('#txt_type_no').val(type_no);
               return -1;
           }else{
            $('#txt_type_no').val('');
            showLoading();
             $.ajax({
                    url: '{$barcode_detail_son_url}',
                    type: 'post',
                    data: {type_no: type_no,emp_no:emp_no},
                    success: function(response){
                        // Add response in Modal body
                        $('#div_son').html(response);
                    },
                    complete: function() {
                        HideLoading();
                    }
             });
           }
      }
      
      function showChild(type_no,cnt_m){
           if (cnt_m == 0){
               success_msg('رسالة','لا يوجد أباء وتم اختياره لتوليد باركود');
               $('#txt_type_no').val(type_no);
               return -1;
           }else{
             $('#txt_type_no').val('');
           } 
      }
      
      
      function generate_barcode(emp_no){
          if ($('#txt_type_no').val() == ''){
              danger_msg('يجب اختيار التصنيف');
              return -1;
          }
          if ($('#txt_ccount').val() == ''){
              danger_msg('يجب ادخال العدد');
              return -1;
          }
           var values= {emp_no:emp_no,type_no:$('#txt_type_no').val(),ccount:$('#txt_ccount').val() };
            get_data('{$generate_barcode_url}',values ,function(data){
               if (parseInt(data) >= 1){
                    $('#btn_generate_barcode').prop('disabled', true);
                    success_msg('رسالة','تم توليد الباركود بنجاح ..');
                    search_lsit(emp_no);
                    setTimeout(function() {
                        $('#btn_generate_barcode').prop('disabled', false);
                    }, 5000);
            } else {
                danger_msg('تحذير..', data);
            }
          }, 'html');   
      }
      
      function search_lsit(emp_no){
            var values= {page:1, 
               emp_no:emp_no, barcode:$('#txt_barcode').val(),type_no:$('#txt_type_no').val()
            };
            get_data('{$get_page_barcode_list_url}',values ,function(data){
                    $('#container-barcode').html(data);
            },'html');
      }
      
      function print_report_pdf(ser) {
        _showReport('{$report_url}&report_type=pdf&report=barcode_hr_archiving&p_from_ser='+ser+'&p_to_ser='+ser);
      }


      
</script>
SCRIPT;
sec_scripts($scripts);
?>
