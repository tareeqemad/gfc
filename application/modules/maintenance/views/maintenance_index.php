<?php
/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 27/07/2019
 * Time: 10:30 ص
 */
$MODULE_NAME = "maintenance";
$TB_NAME = "maintenance";
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$create_url = base_url("$MODULE_NAME/$TB_NAME/create");
$delete_url = base_url("$MODULE_NAME/$TB_NAME/delete");
$technical_support_url = base_url("$MODULE_NAME/$TB_NAME/technical_support");/***اعضاء دائرة الدعم الفني******/
$adopt_all_url = base_url("$MODULE_NAME/$TB_NAME/adopt_"); /****اعتماد وتحويل لطلب فني*********/
$date_attr = "data-type='date' data-date-format='DD/MM/YYYY' data-val='true' data-val-regex-pattern='" . date_format_exp() . "' data-val-regex='Error' ";
?>
<!-- PAGE-HEADER -->
<div class="page-header">
    <div>
        <h1 class="page-title"><?= $title ?></h1>
    </div>
    <div class="ms-auto pageheader-btn">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">الدعم الفني</a></li>
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
                <h3 class="card-title">استعلام | طلبات الصيانة</h3>
                <div class="card-options">
                    <?php if (HaveAccess($create_url)) { ?>
                        <a class="btn btn-secondary" href="<?= $create_url ?>"><i class="fa fa-plus"></i>
                            جديد
                        </a>
                    <?php } ?>
                </div>
            </div>
            <div class="card-body">
                <form id="<?= $TB_NAME ?>_form">
                    <div class="row">
                        <?php if ($this->user->branch == 1){ ?>
                            <div class="form-group col-md-2">
                                <label>المقر </label>
                                <select name="branch_id" id="dl_branch" class="form-control sel2">
                                    <option value="">_________</option>
                                    <?php foreach ($branches as $row) : ?>
                                        <option value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <?php } else {?>
                            <div class="form-group col-md-2">
                                <label>المقر</label>
                                <select class="form-control" id="dl_branch" name="dl_branch">
                                    <option <?= $this->user->branch == 1 ? 'selected' : '' ?> value="1">الرئيسي</option>
                                    <option <?= $this->user->branch == 2 ? 'selected' : '' ?> value="2">غزة</option>
                                    <option <?= $this->user->branch == 3 ? 'selected' : '' ?> value="3">الشمال</option>
                                    <option <?= $this->user->branch == 4 ? 'selected' : '' ?> value="4">الوسطى</option>
                                    <option <?= $this->user->branch == 6 ? 'selected' : '' ?> value="6">خانيونس</option>
                                    <option <?= $this->user->branch == 7 ? 'selected' : '' ?> value="7">رفح</option>
                                    <option <?= $this->user->branch == 8 ? 'selected' : '' ?> value="2">غزة</option>
                                </select>
                            </div>
                        <?php } ?>
                        <?php if (HaveAccess($technical_support_url)) {?>
                            <div class="form-group col-md-3">
                                <label>الموظف</label>
                                <select name="emp_no" id="dl_emp_no" class="form-control sel2">
                                    <option value="">---------</option>
                                    <?php foreach ($employee_arr as $row) : ?>
                                        <option value="<?= $row['EMP_NO'] ?>"><?= $row['EMP_NO'] . " : " . $row['USER_NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <?php } else { ?>
                            <div class="form-group col-md-3">
                                <label>الموظف</label>
                                <select name="emp_no" id="dl_emp_no" class="form-control sel2">
                                    <option value="">---------</option>
                                    <?php foreach ($employee_arr as $row) : ?>
                                        <option <?= $this->user->emp_no ==  $row['EMP_NO'] ? 'selected' : '' ?> value="<?= $row['EMP_NO'] ?>"><?= $row['EMP_NO'] . " : " . $row['USER_NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                        <?php } ?>

                        <div class="form-group col-md-2">
                            <label>رقم الطلب </label>
                            <input type="text"   name="ser" id="txt_ser" class="form-control" autocomplete="off">
                        </div>


                        <div class="form-group col-md-2">
                            <label>تاريخ من</label>
                            <input type="text" <?= $date_attr ?> name="entry_date" id="txt_entry_date"
                                   class="form-control">
                        </div>

                        <div class="form-group col-md-2">
                            <label> الى </label>
                            <input type="text" <?= $date_attr ?> name="end_entry_date" id="txt_end_entry_date"
                                   class="form-control">
                        </div>


                        <div class="form-group col-md-2">
                            <label>مدخل الطلب</label>
                            <select name="entry_user" id="dl_entry_user" class="form-control sel2">
                                <option value="">_________</option>
                                <?php foreach ($entry_user_all as $row) : ?>
                                    <option value="<?= $row['ID'] ?>"><?= $row['USER_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label>حالة الطلب</label>
                            <select name="status" id="dl_status" class="form-control sel2">
                                <option value="">_________</option>
                                <?php foreach ($status_type_con as $row) : ?>
                                    <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                            <button type="button" onclick="javascript:search();" class="btn btn-primary">
                                <i class="fa fa-search"></i>
                                إستعلام
                            </button>
                            <button type="button" id="btn_clear" onclick="clear_form()" class="btn btn-cyan-light">
                                <i class="fa fa-eraser"></i>
                                تفريغ الحقول
                            </button>
                            <?php  if ( HaveAccess($adopt_all_url.'2') ){ ?>
                                <button type="button" onclick="javascript:adopt_all();" class="btn btn-success">
                                    <i class="fa fa-check"></i>
                                    تحويل للدعم الفني
                                </button>
                            <?php } ?>
                   </div>
                </form>
                <hr>
                <div id="container">

                </div>
            </div>
        </div>
    </div>
</div>

<?php
$branch_access = 0;
if ($this->user->branch == 1) {
    $branch_access = 1;
}
$technical_support_access = 0;
if (HaveAccess($technical_support_url)) {
    $technical_support_access = 1;
}
$scripts = <<<SCRIPT
<script>

   var permission_branch = '{$branch_access}';
   var permission_technical_support = '{$technical_support_access}';
   
   if (permission_branch == 0) {
            var branch_id = $("#dl_branch").val();
            $('#dl_branch option:not(:selected)').attr('disabled', true);
   }
   
   if (permission_technical_support == 0) {
      var emp_no = $("#dl_emp_no").val();
      $('#dl_emp_no option:not(:selected)').attr('disabled', true);
   }

   $(function(){
          $('.sel2').select2();
          reBind();
     });

   function reBind(){
       ajax_pager({
           branch:$('#dl_branch').val(),emp_no:$('#dl_emp_no').val(),ser:$('#txt_ser').val(),
           entry_user:$('#dl_entry_user').val(),from_entry_date:$('#txt_entry_date').val(),to_entry_date:$('#txt_end_entry_date').val(),
           status:$('#dl_status').val()
        });
   }

   function LoadingData(){
          ajax_pager_data('#page_tb > tbody',{
            branch:$('#dl_branch').val(),emp_no:$('#dl_emp_no').val(),ser:$('#txt_ser').val(),
            entry_user:$('#dl_entry_user').val(),from_entry_date:$('#txt_entry_date').val(),to_entry_date:$('#txt_end_entry_date').val(),
            status:$('#dl_status').val()
          });
       }
   
   function search(){
             get_data('{$get_page_url}',{page: 1,
                branch:$('#dl_branch').val(),emp_no:$('#dl_emp_no').val(),ser:$('#txt_ser').val(),
                entry_user:$('#dl_entry_user').val(),from_entry_date:$('#txt_entry_date').val(),to_entry_date:$('#txt_end_entry_date').val(),
                status:$('#dl_status').val()
              },function(data){
                $('#container').html(data);
                reBind();
            },'html');
     }

   function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
        $('.sel2').select2('val',0);
    }
    
   function adopt_all(){
        var no= 2;
        var ser= 0;
        var cnt= 0;
        cnt= $('#page_tb .checkboxes:checked').length;
        var msg= 'هل تريد تحويل جميع السجلات المحددة؟؟ #'+cnt;
        if(cnt==0){
            alert('يجب تحديد السجلات المراد تحويلها اولا..');
            return 0;
        }
        if(confirm(msg)){ 
            $('#page_tb .checkboxes:checked').each(function(i){
                ser= $(this).val();
                get_data('{$adopt_all_url}'+no, {ser:ser} , function(ret){
                    if(ret==1){
                        success_msg('رسالة','تمت العملية بنجاح ');
                         search();
                        }else{
                        danger_msg('تحذير..',ret);
                        $('button').prop('disabled','disabled');
                        search();
                    }
                }, 'html');
            }); // each
        } // confirm msg
    }
       
</script>
SCRIPT;
sec_scripts($scripts);
?>
