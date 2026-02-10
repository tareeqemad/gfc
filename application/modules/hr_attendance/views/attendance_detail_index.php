<?php
$MODULE_NAME = 'hr_attendance';
$TB_NAME = 'attendance_detail';
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$date_attr = " data-type='date' data-date-format='DD/MM/YYYY' data-val='true' data-val-regex-pattern='" . date_format_exp() . "' data-val-regex='Error' ";
$user_emp_no= $this->user->emp_no;
echo AntiForgeryToken();
?>
    <!-- PAGE-HEADER -->
    <div class="page-header">
        <div>
            <h1 class="page-title"><?= $title ?></h1>
        </div>
        <div class="ms-auto pageheader-btn">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0);">القائمة</a></li>
                <li class="breadcrumb-item active" aria-current="page">اسم الشاشة</li>
            </ol>
        </div>
    </div>
    <!-- PAGE-HEADER END -->

    <!-- Row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <div class="mb-0 flex-grow-1 card-title">
                        استعلام
                    </div>
                    <div class="flex-shrink-0">

                    </div>
                </div><!-- end card header -->
                <div class="card-body">
                    <form id="<?= $TB_NAME ?>_form">
                        <div class="row">
                            <?php if ($this->user->branch == 1 and  false) { ?>
                                <div class="form-group col-md-2">
                                    <label> المقر</label>
                                    <select name="branch_id" id="dp_branch_id" class="form-control sel2">
                                        <option value="">_______</option>
                                        <?php foreach ($branches as $row) : ?>
                                            <option value="<?= $row['NO'] ?>"> <?= $row['NAME'] ?> </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            <?php } else { ?>
                                <input type="hidden" name="branch_id" id="dp_branch_id" value="<?= $this->user->branch ?>">
                            <?php } ?>


                            <div class="form-group col-md-2">
                                <label for="dp_emp_no" class="form-label">الموظف</label>
                                <select name="emp_no" id="dp_emp_no" class="form-control sel2">
                                    <option value="">_________</option>
                                    <?php foreach ($emp_no_cons as $row) : ?>
                                        <option value="<?= $row['EMP_NO'] ?>"><?= $row['EMP_NO'] . ': ' . $row['EMP_NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>


                            <div class="form-group col-md-2">
                                <label>الشهر</label>
                                <input type="text" placeholder="الشهر" name="month" id="txt_month" class="form-control"
                                       value="<?= date('Ym') ?>">
                            </div>


                        </div>

                        <div class="flex-shrink-0">
                            <button type="button" onclick="javascript:search();" class="btn btn-primary"><i
                                        class="glyphicon glyphicon-search"></i> إستعلام
                            </button>
                            <button type="button" onclick="$('#page_tb').tableExport({type:'excel',escape:'false'});"
                                    class="btn btn-success"><i class="fas fa-file-excel"></i>إكسل
                            </button>
                            <button type="button" onclick="javascript:clear_form();" class="btn btn-cyan-light"><i
                                        class="fas fa-eraser"></i>تفريغ الحقول
                            </button>
                        </div>
                    </form>
                    <hr>
                    <div id="container">

                    </div>
                </div><!--end card body-->
            </div><!--end card --->
        </div><!--end col lg-12--->


    </div><!--end row--->

<?php
$scripts = <<<SCRIPT
<script>

    if('{$page_act}'=='my'){
        $('#dp_emp_no').val('{$user_emp_no}');
        //$('#dp_emp_no').select2({ disabled:'readonly' });
    }
    
    $('.sel2:not("[id^=\'s2\']")').select2();
   
    $('#txt_month').datetimepicker({
            format: 'YYYYMM',
            minViewMode: "months",
            pickTime: false
    });

    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
        $('.sel2').val('').trigger('change')
    }

    function search(){
       var emp_no =  $('#dp_emp_no').val();
       var month = $('#txt_month').val();
       if (emp_no == '' || month == ''  ){
           danger_msg('يرجى ادخال جميع البيانات');
           return -1;
       }else{
        get_data('{$get_page_url}',{emp_no:emp_no, month:month, page_act:'$page_act'},function(data){
            $('#container').html(data);
        },'html');
       }
    }
    
</script>
SCRIPT;
sec_scripts($scripts);
?>