<?php

/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 09/01/2023
 * Time: 08:53 ص
 */
$MODULE_NAME = 'interviews';
$TB_NAME = 'Interviews_result';
$gfc_domain= gh_gfc_domain();

$code_verification = base_url("$MODULE_NAME/$TB_NAME/code_verification");
$query_verification = base_url("$MODULE_NAME/$TB_NAME/query");
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");

?>
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="left-content">
        <span id="title_verfication" class="main-content-title mg-b-0 mg-b-lg-1">التحقق من الصلاحيات</span>
    </div>
    <div class="justify-content-center mt-2">
        <ol class="breadcrumb">
            <li class="breadcrumb-item tx-15"><a href="javascript:void(0);">الوظائف الداخلية</a></li>
            <li class="breadcrumb-item active" aria-current="page">نتائج المقابلات</li>
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
            <div class="card-body" id="container_query">

            </div>
        </div>
    </div>


    <!--Modal NOTE Bootstrap -1-   code verification -->
    <div class="modal fade" id="code_verification_modal">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">التحقق من الصلاحيات</h6>
                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span
                                aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        <div class="row justify-content-center">
                            <div class="form-group  col-sm-5 text-center">
                                <label class=""> رقم التحقق </label>
                                <input type="number" name="code" value=""
                                       id="txt_code" class="form-control">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <?php if (HaveAccess($code_verification) /*&& HaveAccess($query_verification)*/) { ?>
                        <button type="button" onclick="javascript:adopt(<?= $this->user->id ?>);" class="btn btn-indigo" id="btn_click_adopt">
                            <i class="fa fa-check"></i>
                            ارسال
                        </button>
                    <?php } ?>
                    <button class="btn ripple btn-secondary" data-bs-dismiss="modal" type="button">اغلاق</button>
                </div>
            </div>
        </div>
    </div>
    <!--END Modal Bootstrap -->

</div>





<?php
$scripts = <<<SCRIPT
<script type="text/javascript">
    var table = '{$TB_NAME}';
   var count = 0;
   
   
 $(document).ready(function() {
    $('#code_verification_modal').modal('show');  
 });
 
 function adopt(user_id){
     var code = $('#txt_code').val();
     if (code == "") {
         warning_msg('يرجى ادخال كود التحقق');
         return  -1;
     } else { 
         get_data('{$code_verification}', { code:code, user_id:user_id } , function(ret){
             if(ret >= 1){
                 success_msg('رسالة','تمت عملية التحقق بنجاح ');
                 $('#code_verification_modal').modal('hide'); 
                 get_data('{$query_verification}',{ page:1, code:$('#txt_code').val() },function(data){
                    $('#container_query').html(data);
                    $('#title_verfication').html("");
                    innner_js();
                 },'html');
             } else {
                 danger_msg('تحذير..',ret);
             }   
         }, 'html');
     }     
 } 
 
 function innner_js(){
     $('.sel2:not("[id^=\'s2\']")').select2();
    
   $(function(){
       reBind();
   });

   function reBind(){
       ajax_pager({emp_no:$('#dp_emp_no').val(),ads_ser:$('#dp_ads_ser').val() });
   }
 
   function LoadingData(){
      ajax_pager_data('#page_tb ',{
           emp_no:$('#dp_emp_no').val(),ads_ser:$('#dp_ads_ser').val()
      });
    }
    
 }
 
 function search(){
     get_data('{$get_page_url}',{page: 1, emp_no:$('#dp_emp_no').val(),ads_ser:$('#dp_ads_ser').val(), code:$('#txt_code').val()},function(data){
        $('#container').html(data);
        reBind();
     },'html');
 }
 
 function reBind(){
     ajax_pager({emp_no:$('#dp_emp_no').val(),ads_ser:$('#dp_ads_ser').val() });
 }
   
   
    
   
</script>
SCRIPT;
sec_scripts($scripts);
?>



