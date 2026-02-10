<?php

$MODULE_NAME = "servicelength";
$TB_NAME = 'Service_length';
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$index_request_url = base_url("$MODULE_NAME/$TB_NAME/Create");
$entry_date=date('d/m/Y');
$date_attr = "data-type='date' data-date-format='DD/MM/YYYY' data-val='true'  data-val-regex-pattern='" . date_format_exp() . "' data-val-regex='Error' ";
$get_emps_list=base_url("$MODULE_NAME/$TB_NAME/get_list");
$report_url = base_url("JsperReport/showreport?sys=hr/hr_retirement_discharge");

?>
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="left-content">
        <span class="main-content-title mg-b-0 mg-b-lg-1">استعلام </span>
    </div>
    <div class="justify-content-center mt-2">
        <ol class="breadcrumb">
            <li class="breadcrumb-item tx-15"><a href="javascript:void(0);">النظام الاداري</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?=$title?></li>
        </ol>
    </div>
</div>
<!-- /breadcrumb -->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex py-3">
                <div class="mb-0 flex-grow-1 card-title">
                    <?= $title ?>
                </div>
                <div class="flex-shrink-0">

                </div>
            </div><!-- end card header -->
            <div class="card-body">
                <form id="<?= $TB_NAME ?>_form">
                    <div class="row">
                        <div class="form-group col-md-2">
                            <label>التاريخ</label>
                            <input type="text" value="<?=$entry_date?> " <?=$date_attr ?> name="txt_end_date"
                                   id="txt_end_date"   class="form-control "  >                        </div>


                        <div class="form-group col-md-2">
                            <label>الموظف</label>
                            <select name="emp_no" id="dp_emp_no" class="form-control sel2">
                                <option value="">_________</option>
                                <?php   foreach ($emp_list as $row) :  ?>
                                 <option value="<?=  $row['no'] ?>"><?=  $row['no'] . ': ' . $row['NAME'] ?></option>
                                <?php   endforeach;  ?>
                            </select>
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        <button type="button" onclick="javascript:get_emps();" class="btn btn-primary">
                            <i class="fa fa-search"></i> جلب البيانات
                        </button>

                        <button type="button" onclick="javascript:search();" class="btn btn-primary">
                            <i class="fa fa-search"></i> إستعلام
                        </button>
                        <button type="button" onclick="javascript:clear_form();" class="btn btn-cyan-light"><i
                                    class="fas fa-eraser"></i>تفريغ الحقول
                        </button>
                        <button type="button" onclick="javascript:print_report('xls','retirement_nominated_employees_');" class="btn btn-outline-cyan"><i
                                    class="fas fa-eraser"></i>اكسل
                        </button>
                    </div>
                </form>
                <br>
                <div id="container">
                    <?php /*echo modules::run($get_page, $page);*/ ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!----------JS------------>
<?php
$scripts = <<<SCRIPT
<script>
    $('#txt_entry_date').datetimepicker({
        format: 'dd/MM/YYYY' 
     });
  $('.sel2:not("[id^=\'s2\']")').select2();  
 
   function search(){
      get_data('{$get_page_url}',{
             emp_no:$('#dp_emp_no').val()  
           },function(data){
             $('#container').html(data);
             //console.log(data);
      },'html');
   } 
   
     function get_emps(){
       $('#dp_emp_no')
    .empty().append('<option value="">_________</option>');
             $.ajax({
         url: '{$get_emps_list}/'+$('#txt_end_date').val().replaceAll('/','-')
     }).then(function(data) {
         HideLoading();

         if(data.length ==0){}
         else{
             for(var i = 0; i <data.length ; i++) {
                            $('#dp_emp_no').append($('<option/>').attr("value", data[i].NO).text(data[i].NO+":"+data[i].NAME));

             }

 
         }
     });

       
       
       
       
       
       /*
       
      get_data('{$get_emps_list}',{
             emp_no:$('#dp_emp_no').val() ,
             end_date:$('#txt_end_date').val()
          },function(data){
             console.log(data[0]);
      },'html');*/
   } 
  function  print_report(rep_type,name){


       var repUrl = '<?=$report_url?>'+'&report_type='+rep_type+'&report='+name+rep_type+'&p_date='+$('#txt_end_date').val();
      _showReport(repUrl);

  }
  
  
  
  
  
       function get_emps(){
       $('#dp_emp_no')
    .empty().append('<option value="">_________</option>');
             $.ajax({
         url: '{$get_emps_list}/'+$('#txt_end_date').val().replaceAll('/','-')
     }).then(function(data) {
         HideLoading();

         if(data.length ==0){}
         else{
             for(var i = 0; i <data.length ; i++) {
                            $('#dp_emp_no').append($('<option/>').attr("value", data[i].NO).text(data[i].NO+":"+data[i].NAME));

             }

 
         }
     });

       
       
       
       
       
       /*
       
      get_data('{$get_emps_list}',{
             emp_no:$('#dp_emp_no').val() ,
             end_date:$('#txt_end_date').val()
          },function(data){
             console.log(data[0]);
      },'html');*/
   } 
  function  print_report(rep_type,name){


       var repUrl = '{$report_url}'+'&report_type='+rep_type+'&report='+name+rep_type+'&p_date='+$('#txt_end_date').val();
      _showReport(repUrl);

  }
  
  
  
  
  
  
  
  
</script>
SCRIPT;
sec_scripts($scripts);
?>
