<?php
$MODULE_NAME = 'emp_archive';
$TB_NAME = 'Barcode';
$Archive_scan_NAME = 'Archive_scan';
$index_url = base_url("$MODULE_NAME/$Archive_scan_NAME/index");
$get_page_barcode_list_url = base_url("$MODULE_NAME/$TB_NAME/public_get_page_barcode_list");
$generate_barcode_index_url = base_url("$MODULE_NAME/$TB_NAME/generate_barcode_index");
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
                  استعلام
                </h3>
                <div class="card-options">
                    <a href="<?= $generate_barcode_index_url?>" class="btn btn-blue me-2">
                        <i class="fa fa-barcode"></i>
                        توليد باركود
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
                        <div class="form-group col-md-3">
                            <label class="text-blue fs-18 fw-bold">التصنيفات الرئيسية</label>
                            <select name="txt_type_no" id="txt_type_no" class="form-control sel2">
                                <option value="">_________</option>
                                <?php foreach ($scan_type_arr as $row) : ?>
                                    <option value="<?= $row['TYPE_NO'] ?>"><?= $row['TYPE_CODE'] . ': ' . $row['TYPE_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        <button type="button" class="btn btn-primary" onclick="searchs()">
                            <i class="fa fa-search"></i>
                            استعلام
                        </button>
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
<div class="modal fade" id="publicModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-fullscreen" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">بيانات</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
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
      
      function searchs(){
            var values= {page:1, 
               emp_no:$('#dp_emp_no').val(), barcode:$('#txt_barcode').val(),type_no:$('#txt_type_no').val()
            };
            get_data('{$get_page_barcode_list_url}',values ,function(data){
                    $('#container').html(data);
            },'html');
      }
      
      function print_report_pdf(ser) {
        _showReport('{$report_url}&report_type=pdf&report=barcode_hr_archiving&p_from_ser='+ser+'&p_to_ser='+ser);
      }
</script>
SCRIPT;
sec_scripts($scripts);
?>
