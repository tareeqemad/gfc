<?php
$MODULE_NAME = 'payroll_data';
$TB_NAME = 'salary_benefits';
$branch_query_url = base_url("$MODULE_NAME/$TB_NAME/select_branch_query");
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$edit_url = base_url("$MODULE_NAME/$TB_NAME/edit");
$get_badl_type = base_url("$MODULE_NAME/$TB_NAME/public_get_badl_type");
$create_url = base_url("$MODULE_NAME/$TB_NAME/create");
$get_excel_url = base_url("$MODULE_NAME/$TB_NAME/excel_report");
?>
<!-- PAGE-HEADER -->
<div class="page-header">
    <div>
        <h1 class="page-title">كشف | الاستحقاقات المالية</h1>
    </div>
    <div class="ms-auto pageheader-btn">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">الحركات</a></li>
            <li class="breadcrumb-item active" aria-current="page">الاستحقاقات المالية</li>
        </ol>
    </div>
</div>
<!-- PAGE-HEADER END -->
<!-- Row -->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"> استعلام</h3>
                <div class="card-options">

                </div>
            </div>
            <div class="card-body">
                <form class="form-vertical" id="<?= $TB_NAME ?>_form">
                    <div class="row">

                        <?php if ($this->user->branch == 1) { ?>
                            <div class="form-group col-md-2">
                                <label> المقر</label>
                                <select name="branch_id" id="dp_branch_id" class="form-control sel2">
                                    <option value="">_______</option>
                                    <?php foreach ($branches as $row) : ?>
                                        <option <?= ($this->user->branch == $row['NO'] ? 'selected="selected"' : '') ?>
                                                value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <?php } else { ?>
                            <input type="hidden" name="branch_id" id="dp_branch_id"
                                   value="<?= $this->user->branch ?>">
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
                            <label>نوع البدل</label>
                            <select name="badl_typ" id="dp_badl_typ" class="form-control sel2">
                                <option value="">_________</option>
                                <?php foreach ($badl_typ_cons as $row) : ?>
                                    <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NO'] . ': ' . $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group col-md-2">
                            <label>البند</label>
                            <select name="con_no" id="dp_con_no" class="form-control">
                                <option value="">_________</option>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label>خاضع للضريبة</label>
                            <select name="is_taxed" id="dp_is_taxed" class="form-control">
                                <option value="">_________</option>
                                <?php foreach ($is_taxed_cons as $row) : ?>
                                    <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NO'] . ': ' . $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>


                        <div class="form-group col-md-1">
                            <label for="dl_op">النسبة</label>
                            <select class="form-control" id="dl_op" name="dl_op">
                                <option value="">---</option>
                                <option value=">=">>=</option>
                                <option value="<="><=</option>
                                <option value="=">=</option>
                            </select>
                        </div>

                        <div class="form-group col-md-2">
                            <label for="txt_value">المبلغ </label>
                            <input type="text" class="form-control" id="txt_value" name="txt_value"/>
                        </div>


                        <div class="form-group col-md-2">
                            <label> من شهر</label>
                            <input type="text" placeholder=" من شهر" name="from_month" id="txt_from_month"
                                   class="form-control" value="<?= date('Ym') ?>">
                        </div>

                        <div class="form-group col-md-2">
                            <label> الى شهر</label>
                            <input type="text" placeholder=" من شهر" name="to_month" id="txt_to_month"
                                   class="form-control" value="<?= date('Ym') ?>">

                        </div>
                    </div>

                    <div class="flex-shrink-0">
                        <button type="button" onclick="javascript:searchs();" class="btn btn-primary"><i
                                    class="fa fa-search"></i> إستعلام
                        </button>
                        <button type="button" onclick="ExcelData()"
                                class="btn btn-success">
                            <i class="fa fa-file-excel-o"></i>
                            إكسل
                        </button>
                        <button type="button" onclick="javascript:clear_form();" class="btn btn-cyan-light">
                            <i class="fa fa-eraser"></i>
                            تفريغ الحقول
                        </button>
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
$scripts = <<<SCRIPT
<script>

       $('.sel2:not("[id^=\'s2\']")').select2();
      
        $(function(){
            reBind();
         });

  
         $('select[name="con_no"]').attr('readonly','readonly');

         $('#txt_from_month,#txt_to_month').datetimepicker({
                format: 'YYYYMM',
                minViewMode: 'months',
                pickTime: false,
                
         });
     
          $('#dp_badl_typ').change(function(){
            change_con_no()
          });
      
         function change_con_no(){
             $('select[name="con_no"]').empty();
             $('select[name="con_no"]').removeAttr('readonly');
             $('select[name="con_no"]').select2();
             var badl_typ =  $('#dp_badl_typ').val();
             if (badl_typ == '') {
                    return -1;
             } else
             get_data('{$get_badl_type}', {badl_typ: badl_typ}, function (data) {
                 $('select[name="con_no"]').append($('<option/>').attr("value", '').text('_______'));
                 $.each(data, function (i, option) {
                     var options = '';
                     options += '<option value="' + option.NO + '">' + option.NO + ": " +option.NAME + '</option>';
                     $('select[name="con_no"]').append(options);
                 });
             });
         }
         

 
        function clear_form(){
            clearForm($('#{$TB_NAME}_form'));
            $('.sel2').select2('val',0);
        }
      
        function reBind(){
             ajax_pager({
                 emp_no:$('#dp_emp_no').val(),con_no:$('#dp_con_no').val()
                ,from_month:$('#txt_from_month').val(),to_month:$('#txt_to_month').val(),badl_typ:$('#dp_badl_typ').val(),branch_id:$('#dp_branch_id').val(),op:$('#dl_op').val(),
                 value:$('#txt_value').val(), is_taxed:$('#dp_is_taxed').val() 
             });
        }
    
        function LoadingData(){
             ajax_pager_data('#page_tb > tbody',{
                 emp_no:$('#dp_emp_no').val(),con_no:$('#dp_con_no').val()
                ,from_month:$('#txt_from_month').val(),to_month:$('#txt_to_month').val(),badl_typ:$('#dp_badl_typ').val(),branch_id:$('#dp_branch_id').val(),op:$('#dl_op').val(),
                 value:$('#txt_value').val() ,is_taxed:$('#dp_is_taxed').val() 
             });
        }
    
        function searchs(){
            get_data('{$get_page_url}',{ page: 1,
                 emp_no:$('#dp_emp_no').val(),con_no:$('#dp_con_no').val()
                ,from_month:$('#txt_from_month').val(),to_month:$('#txt_to_month').val(),badl_typ:$('#dp_badl_typ').val(),branch_id:$('#dp_branch_id').val(),op:$('#dl_op').val(),
                 value:$('#txt_value').val(),is_taxed:$('#dp_is_taxed').val() 
            },function(data){
                $('#container').html(data);
                reBind();
            },'html');
        }
        
        
    

    function ExcelData(){
        var fewSeconds = 10;
        var emp_no = $('#dp_emp_no').val();
        var con_no = $('#dp_con_no').val();
        var from_month = $('#txt_from_month').val();
        var to_month = $('#txt_to_month').val();
        var badl_typ = $('#dp_badl_typ').val();
        var branch_id = $('#dp_branch_id').val();
        var op = $('#dl_op').val();
        var value = $('#txt_value').val();
        var is_taxed = $('#dp_is_taxed').val();
        info_msg('تنبيه','جاري تجهيز ملف الاكسل ..'); 
            location.href = '{$get_excel_url}?emp_no='+emp_no+'&con_no='+con_no+'&from_month='+from_month+'&to_month='+to_month+'&badl_typ='+badl_typ+'&branch_id='+branch_id +'&op='+op+'&value='+value+'&is_taxed='+is_taxed;
            setTimeout(function(){
                info_msg('تنبيه','جاري تجهيز ملف الاكسل ..'); 
        }, fewSeconds*1000);
    }
</script>
SCRIPT;
sec_scripts($scripts);
?>
