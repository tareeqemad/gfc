<?php
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 29/06/19
 * Time: 01:52 م
 */

$MODULE_NAME = 'salary';
$TB_NAME = 'employees';

$get_url = base_url("$MODULE_NAME/$TB_NAME/get");
$create_url = base_url("$MODULE_NAME/$TB_NAME/create");
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$edit = '';
$date_attr = " data-type='date' data-date-format='DD/MM/YYYY' data-val='true' data-val-regex-pattern='" . date_format_exp() . "' data-val-regex='Error' ";
echo AntiForgeryToken();
?>
<!-- PAGE-HEADER -->
<div class="page-header">
    <div>
        <h1 class="page-title"><?= $title ?></h1>
    </div>
    <div class="ms-auto pageheader-btn">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">الرواتب</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= $title ?></li>
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
                    <?php if (HaveAccess($create_url)): ?>
                        <a href="<?= $create_url ?>" class="btn btn-secondary">
                            <i class="fa fa-plus"></i>
                            جديد
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-body">
                <form id="<?= $TB_NAME ?>_form">
                    <div class="row">
                        <div class="form-group col-md-2">
                            <label> الموظف</label>
                            <select name="employee_no" id="dp_employee_no" class="form-control sel2">
                                <option value="">_______</option>
                                <?php foreach ($emp_no_cons as $row) : ?>
                                    <option value="<?= $row['EMP_NO'] ?>"><?= $row['EMP_NO'] . ': ' . $row['EMP_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>


                        <div class="form-group col-md-2">
                            <label>رقم الهوية</label>
                            <input type="text" name="id" id="txt_id" class="form-control">
                        </div>

                        <div class="form-group col-md-2">
                            <label>الاسم</label>
                            <input type="text" name="name" id="txt_name" class="form-control">
                        </div>

                        <div class="form-group col-md-2">
                            <label> الحالة الاجتماعية</label>
                            <select name="tatus" id="dp_status" class="form-control sel2">
                                <option value="">_______</option>
                                <?php foreach ($status_cons as $row) : ?>
                                    <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group col-md-2">
                            <label> الجنس</label>
                            <select name="sex" id="dp_sex" class="form-control sel2">
                                <option value="">_______</option>
                                <?php foreach ($sex_cons as $row) : ?>
                                    <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">


                        <div class="form-group col-md-2">
                            <label> نوع التعيين</label>
                            <select name="emp_type" id="dp_emp_type" class="form-control sel2">
                                <option value="">_________</option>
                                <?php foreach ($emp_type_cons as $row) : ?>
                                    <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group col-md-2">
                            <label>المقر الرئيسي</label>
                            <select name="bran" id="dp_bran" class="form-control sel2">
                                <option value="">_________</option>
                                <?php foreach ($bran_cons as $row) : ?>
                                    <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group col-md-2">
                            <label>المؤهل العلمي</label>
                            <select name="q_no" id="dp_q_no" class="form-control sel2">
                                <option value="">_________</option>
                                <?php foreach ($q_no_cons as $row) : ?>
                                    <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group col-md-2">
                            <label>الفئة</label>
                            <select name="sp_no" id="dp_sp_no" class="form-control sel2">
                                <option value="">_________</option>
                                <?php foreach ($sp_no_cons as $row) : ?>
                                    <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">

                        <div class="form-group col-md-2">
                            <label>المسمى الوظيفي</label>
                            <select name="w_no_admin" id="dp_w_no_admin" class="form-control sel2">
                                <option value="">_________</option>
                                <?php foreach ($w_no_admin_cons as $row) : ?>
                                    <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group col-md-2">
                            <label>البنك</label>
                            <select name="master_banks_email" id="dp_master_banks_email" class="form-control sel2">
                                <option value="">_________</option>
                                <?php foreach ($master_banks_email_cons as $row) : ?>
                                    <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </form>
                <div class="flex-shrink-0">
                    <button type="button" onclick="javascript:search();" class="btn btn-primary"><i
                                class="fa fa-search"></i> إستعلام
                    </button>
                    <button type="button" onclick="$('#page_tb').tableExport({type:'excel',escape:'false'});"
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

            </div>
        </div>
    </div>
</div>
<!-- Row -->


<?php
$scripts = <<<SCRIPT
<script>

   $('.sel2:not("[id^=\'s2\']")').select2();  
 
    function show_row_details(id){
        get_to_link('{$get_url}/'+id);
    }

    
    
      $(function(){
         reBind();
      });

      function reBind(){
        ajax_pager({
               employee_no:$('#dp_employee_no').val(), name:$('#txt_name').val(), id:$('#txt_id').val(), status:$('#dp_status').val(), sex:$('#dp_sex').val(), 
               emp_type:$('#dp_emp_type').val(),  bran:$('#dp_bran').val(), q_no:$('#dp_q_no').val(), sp_no:$('#dp_sp_no').val(),w_no_admin:$('#dp_w_no_admin').val(), master_banks_email:$('#dp_master_banks_email').val()
         });
       }

        function LoadingData(){
          ajax_pager_data('#page_tb > tbody',{
               employee_no:$('#dp_employee_no').val(), name:$('#txt_name').val(), id:$('#txt_id').val(), status:$('#dp_status').val(), sex:$('#dp_sex').val(), 
               emp_type:$('#dp_emp_type').val(),  bran:$('#dp_bran').val(), q_no:$('#dp_q_no').val(), sp_no:$('#dp_sp_no').val(),w_no_admin:$('#dp_w_no_admin').val(), master_banks_email:$('#dp_master_banks_email').val()
          });
       }


      function search(){
        get_data('{$get_page_url}',{page: 1,
               employee_no:$('#dp_employee_no').val(), name:$('#txt_name').val(), id:$('#txt_id').val(), status:$('#dp_status').val(), sex:$('#dp_sex').val(), 
               emp_type:$('#dp_emp_type').val(),  bran:$('#dp_bran').val(), q_no:$('#dp_q_no').val(), sp_no:$('#dp_sp_no').val(),w_no_admin:$('#dp_w_no_admin').val(), master_banks_email:$('#dp_master_banks_email').val()
             },function(data){
                console.log(data);
                $('#container').html(data);
                reBind();
            },'html');
      }
     
     
     function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
        $('.sel2').select2('val',0);
    }

  
  
</script>
SCRIPT;
sec_scripts($scripts);
?>
