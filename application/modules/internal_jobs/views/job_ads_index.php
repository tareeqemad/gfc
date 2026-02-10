<?php
/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 18/10/2022
 * Time: 11:40 ص
 */
$MODULE_NAME = 'internal_jobs';
$TB_NAME = 'job_ads';
$TB_NAME_REQUEST = 'job_ads_request';
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$post_url = base_url("$MODULE_NAME/$TB_NAME/create");
$edit_url = base_url("$MODULE_NAME/$TB_NAME/edit");
$delete_url = base_url("$MODULE_NAME/$TB_NAME/delete");
$join_in_job_url = base_url("$MODULE_NAME/$TB_NAME_REQUEST/create");
$get_request_job_url = base_url("$MODULE_NAME/$TB_NAME_REQUEST/my_request_job");
$gfc_domain = gh_gfc_domain();
?>
<!-- PAGE-HEADER -->
<div class="page-header">
    <div>
        <h1 class="page-title">اعلانات الوظائف الداخلية</h1>
    </div>
    <div class="ms-auto pageheader-btn">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">النظام الاداري</a></li>
            <li class="breadcrumb-item active" aria-current="page">الوظائف الداخلية</li>
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
                    <?php if (HaveAccess($post_url)) { ?>
                        <a href="<?= $post_url ?>" class="btn btn-secondary">
                            <i class="fa fa-plus"></i>
                            جديد
                        </a>
                    <?php } ?>
                </div>
            </div>
            <div class="card-body">
                <form id="<?= $TB_NAME ?>_form">
                    <div class="row">
                        <div class="form-group col-md-3">
                            <input type="text" placeholder="البحث عن الوظيفة"
                                   name="ads_name"
                                   id="txt_ads_name" class="form-control" value="" autocomplete="off">
                        </div>
                        <input type="hidden" name="h_email" id="h_email" value="<?= $emp_email ?>">
                        <div class="form-group col-md-2">
                            <button type="button" onclick="javascript:search();" class="btn btn-primary">
                                <i class="fa fa-search"></i>
                                بحث
                            </button>
                        </div>
                    </div>
                    <div id="container">
                        <?php echo modules::run($get_page_url, $page); ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php
$scripts = <<<SCRIPT
<script type="text/javascript">

   var table = '{$TB_NAME}';
   var count = 0;
       
   $('.sel2:not("[id^=\'s2\']")').select2();
     

   $(function(){
       reBind();
   });

   function reBind(){
       ajax_pager({
          ads_name:$('#txt_ads_name').val()
        });
   }

   function LoadingData(){
      ajax_pager_data('#accordion_list ',{
           ads_name:$('#txt_ads_name').val()
      });
    }
    
   function search(){
       get_data('{$get_page_url}',{page: 1,ads_name:$('#txt_ads_name').val() },function(data){
                $('#container').html(data);
                reBind();
       },'html');
   }
   
   
    var btn__= '';
      $('#btn_join').click( function(){
         btn__ = $(this);
   });
   
   function CreateRequest(emp_no){
       var cnt= 0;
       cnt= $('#accordion_list .checkboxes:checked').length;
        var msg= 'هل تريد اعتماد جميع السجلات المحددة؟؟ #'+cnt;
        if(cnt == 0){
            danger_msg('يجب تحديد السجلات المراد التقديم عليها5 اولا..');
            return 0;
        }else if (cnt > 2 ){
            danger_msg('لا يمكن التقديم لاكثر من وظيفتين');
            return 0;
        }else {
             if(confirm(msg)){
                  var val = [];
                  $('#accordion_list .checkboxes:checked').each(function (i) { 
                      val[i] = $(this).val();
                  });
                  get_data('{$join_in_job_url}', {ads_ser:val,emp_no:emp_no} , function(data){
                    if(data >= 1){
                        success_msg('رسالة','تمت العملية بنجاح ');
                          var sub= 'تقديم على اعلان طلب وظيفة داخلية';
                            var text= 'تم تقديم الطلب بنجاح';
                            text += '<br>يرجى ادخال المرفقات اللازمة ';
                            text += '<br>من خلال الاطلاع على  الرابط التالي ';
                            text += ' <br>{$gfc_domain}{$get_request_job_url}} ';
                            _send_mail(btn__,'{$emp_email}',sub,text);
                            btn__ = '';
                       get_to_link(window.location.href);
                    }else{
                        danger_msg('تحذير..',data);
                    }
                  }, 'html'); 
             }
        }
   }   
   
 
</script>
SCRIPT;
sec_scripts($scripts);
?>
