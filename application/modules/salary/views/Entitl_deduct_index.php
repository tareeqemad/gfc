<?php
$MODULE_NAME = 'salary';
$TB_NAME = 'Entitl_deduct';
$branch_query_url = base_url("$MODULE_NAME/$TB_NAME/select_branch_query");
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$edit_url = base_url("$MODULE_NAME/$TB_NAME/edit");
$get_badl_type = base_url("$MODULE_NAME/$TB_NAME/public_get_badl_type");
$create_url = base_url("$MODULE_NAME/$TB_NAME/create");
$get_excel_url = base_url("$MODULE_NAME/$TB_NAME/excel_report");

$get_url =base_url("$MODULE_NAME/$TB_NAME/get");

$hrAdmins = [
    1008 => 'hesahm',
    705 => 'akram',
    976 => 'fady',
    674 => 'moh_ashi',
    1022 => 'moh_zan',
    997 => 'tareq_dal',
    1015 => 'basam_mous',
    994 => 'morsed',
    708 => 'neveen_non',
    1500 => 'khaled_has',
    743 => 'khaled_shamia',
];

$role = in_array($this->user->emp_no, array_keys($hrAdmins)) 
    ? 'hr_admin' 
    : 'manager';
?>
<!-- PAGE-HEADER -->
<div class="page-header">
    <div>
        <h1 class="page-title">كشف | مراجعة البدلات بعد الاحتساب</h1>
    </div>
    <div class="ms-auto pageheader-btn">
        <ol class="breadcrumb">

        </ol>
    </div>
</div>
<!-- PAGE-HEADER END -->
<!-- Row -->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"> الاستعلام / مستحقات قيد المراجعة والتدقيق</h3>
                <div class="card-options">

                </div>
            </div>
            <div class="card-body">
                <form class="form-vertical" id="<?= $TB_NAME ?>_form">
                    <div class="row">

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
                            <label> من شهر</label>
                            <input type="text" placeholder=" من شهر" name="from_month" id="txt_from_month"
                                   class="form-control" value="<?php   echo('202404'); ?>">
                        </div>

                        <div class="form-group col-md-2">
                            <label> الى شهر</label>
                            <input type="text" placeholder=" الى شهر" name="to_month" id="txt_to_month"
                                   class="form-control" value="<?= date('Ym') ?>">

                        </div>

                    </div>

                    <div class="flex-shrink-0">
                        <button type="button" onclick="javascript:searchs();" class="btn btn-primary"><i
                                class="fa fa-search"></i> إستعلام
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
			if($('#dp_emp_no').val() != ''){
				get_data('{$get_page_url}',{ page: 1,
					 emp_no:$('#dp_emp_no').val(),con_no:$('#dp_con_no').val()
					,from_month:$('#txt_from_month').val(),to_month:$('#txt_to_month').val(),badl_typ:$('#dp_badl_typ').val(),branch_id:$('#dp_branch_id').val(),op:$('#dl_op').val(),
					 value:$('#txt_value').val(),is_taxed:$('#dp_is_taxed').val() 
				},function(data){
					$('#container').html(data);
					reBind();
				},'html');
			}else{
				danger_msg('تحذير..','يجب اختيار المقر والموظف معا');
			}
        }
        
        function show_row_details(emp_no,month){
            get_to_link('{$get_url}/'+emp_no+'/'+month+'/{$role}');
        }
    

     
</script>
SCRIPT;
sec_scripts($scripts);
?>
