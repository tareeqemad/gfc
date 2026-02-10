<?php
$MODULE_NAME = "empvacancey";
$TB_NAME = 'vacancey';
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page_d");
$report_url = base_url("JsperReport/showreport?sys=hr/hr_retirement_discharge");
$report_sn = report_sn();
$delete_request_url = base_url("$MODULE_NAME/$TB_NAME/delete_request");
?>
    <!-- PAGE-HEADER -->
    <div class="page-header">
        <div>
            <h1 class="page-title">استعلام</h1>
        </div>
        <div class="ms-auto pageheader-btn">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0);">النظام الاداري</a></li>
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
                    <h3 class="card-title"> طلبات خلو الطرف للموظفين</h3>
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
                            <div class="form-group col-md-2">
                                <label>رقم الطلب</label>
                                <input type="text" placeholder="رقم الطلب"
                                       name="id_vacancy"
                                       id="txt_id_vacancy" class="form-control" value="" autocomplete="OFF">
                            </div>
                            <?php if ($this->user->branch == 1) { ?>
                                <div class="form-group col-md-2">
                                    <label> المقر</label>
                                    <select name="branch_no" id="dp_branch_no" class="form-control">
                                        <option value="">_______</option>
                                        <?php foreach ($branches as $row) : ?>
                                            <option   value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
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
                                <label>مسار الاعتماد</label>
                                <select name="type_adopt" id="dp_type_adopt" class="form-control sel2">
                                    <option value="">_________</option>
                                    <option value="2">الجديد</option>
                                    <option value="1">القديم</option>
                                </select>
                            </div>

                            <div class="form-group col-md-2">
                                <label>المدخل</label>
                                <select name="entry_user" id="dp_entry_user" class="form-control sel2">
                                    <option value="">_________</option>
                                    <?php foreach($entry_user_all as $row) :?>
                                        <option value="<?=$row['ID']?>"><?=$row['USER_NAME']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                        </div>
                        <div class="flex-shrink-0">
                            <button type="button" onclick="javascript:search();" class="btn btn-primary">
                                <i class="fa fa-search"></i> إستعلام
                            </button>
                            <button type="button" onclick="javascript:clear_form();" class="btn btn-cyan-light"><i
                                        class="fa fa-eraser"></i>تفريغ الحقول
                            </button>
                        </div>
                    </form>
                    <hr>
                    <div class="form-group col-md-3" id="search_div" style="display: none;">
                        <input type="text" class="search form-control" placeholder="البحث عن الطلبات">
                    </div>
                    <div id="container">
                        <!---رسم الجدول--->
                        <?= modules::run($get_page_url, $page); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!----------JS------------>
<?php
$scripts = <<<SCRIPT
<script>
 
     $('.sel2:not("[id^=\'s2\']")').select2();

     $('.search').on('keyup', function () {
             var searchTerm = $(this).val().toLowerCase();
             $('#page_tb tbody tr').each(function () {
                 var lineStr = $(this).text().toLowerCase();
                 if (lineStr.indexOf(searchTerm) === -1) {
                     $(this).hide();
                 } else {
                     $(this).show();
                 }
             });
    });
/***********تفريغ الحقول****************/     
     function clear_form(){
            clearForm($('#{$TB_NAME}_form'));
            $('.sel2').select2('val','');
     }
     
     function reBind(){
        ajax_pager({
        id_vacancy:$('#txt_id_vacancy').val(),branch_no:$('#dp_branch_no').val(),emp_no:$('#dp_emp_no').val(),type_adopt:$('#dp_type_adopt').val(),entry_user:$('#dp_entry_user').val()
        });
      }

    function LoadingData(){
        ajax_pager_data('#page_tb > tbody',{ 
           id_vacancy:$('#txt_id_vacancy').val(),branch_no:$('#dp_branch_no').val(),emp_no:$('#dp_emp_no').val(),type_adopt:$('#dp_type_adopt').val(),entry_user:$('#dp_entry_user').val()
        });
    }
    
    function search(){
       $('#search_div').show(); 
       get_data('{$get_page_url}',{ page: 1,
          id_vacancy:$('#txt_id_vacancy').val(),branch_no:$('#dp_branch_no').val(),emp_no:$('#dp_emp_no').val(),type_adopt:$('#dp_type_adopt').val(),entry_user:$('#dp_entry_user').val()
       },function(data){
            $('#container').html(data);
            reBind();
       },'html');
    }
    
    function print_report(ID_VACANCY){

        var rep_url = '{$report_url}&report_type=pdf&report=retirement_discharge&p_id='+ID_VACANCY+'&sn={$report_sn}';
        _showReport(rep_url);
    }

    function delete_vacancy(a,id){
        if(confirm('هل تريد حذف السجل ؟!')){
            get_data('{$delete_request_url}',{id:id},function(data){
                 if(data == '1'){
                    success_msg('رسالة','تم حذف الطلب بنجاح ..');
                    $(a).closest('tr').remove();
                }else{
                    danger_msg( data);
                }
            },'html');
        }
    }
    
</script>
SCRIPT;
sec_scripts($scripts);
?>