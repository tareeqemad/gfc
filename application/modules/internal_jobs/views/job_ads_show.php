<?php
/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 18/10/2022
 * Time: 12:12 pm
 */
$MODULE_NAME = 'internal_jobs';
$TB_NAME = 'job_ads';
$isCreate =isset($master_tb_data) && count($master_tb_data)  > 0 ?false:true;
$HaveRs = (!$isCreate)? true:false;
$rs=$isCreate ?array() : $master_tb_data[0];
$index_url = base_url("$MODULE_NAME/$TB_NAME/index");
$post_url = base_url("$MODULE_NAME/$TB_NAME/" . ($action == 'index' ? 'create' : $action));
$get_url = base_url("$MODULE_NAME/$TB_NAME/get");
$date_attr = "data-type='date' data-date-format='DD/MM/YYYY' data-val='true' data-val-regex-pattern='" . date_format_exp() . "' data-val-regex='Error' ";
$t=time();
$r =rand(10,99);
$c=$t.$r;
?>
<!-- PAGE-HEADER -->
<div class="page-header">
    <div>
        <h1 class="page-title"><?= $title ?></h1>
    </div>
    <div class="ms-auto pageheader-btn">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">الاداري</a></li>
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
                    <?php if (HaveAccess($index_url)) { ?>
                        <a href="<?= $index_url ?>" class="btn btn-secondary">
                            <i class="fa fa-backward"></i>
                            قائمة الاعلانات
                        </a>
                    <?php } ?>
                </div>
            </div>
            <div class="card-body">
                <form class="form-horizontal" id="<?= $TB_NAME ?>_form" method="post" action="<?= $post_url ?>"
                      role="form" novalidate="novalidate">

                    <div class="row">
                        <div class="form-group  col-md-2">
                            <label> رقم الاعلان </label>
                            <input type="text" readonly name="ser" id="txt_ser" value="<?= $HaveRs ? $rs['SER'] : '' ?>"
                                   class="form-control">
                            <input type="hidden" name="attachment_ser" value=" <?= $HaveRs ? $rs['ATTACHMENT_SER'] : $c ?>">
                        </div>

                        <div class="form-group  col-md-4">
                            <label> الوظيفة </label>
                            <input type="text" name="ads_name" id="txt_ads_name"
                                   value="<?= $HaveRs ? $rs['ADS_NAME'] : '' ?>" class="form-control" autocomplete="off">
                        </div>

                        <div class="form-group col-md-2">
                            <label class="text-danger">تاريخ نهاية التقديم </label>
                            <input type="text" <?= $date_attr ?> name="deadline" id="txt_deadline"
                                   value="<?= $HaveRs ? $rs['DEADLINE'] : '' ?>" autocomplete="off"
                                   class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-8">
                            <label for="txt_ads_description" class="text-primary">شروط الاعلان</label>
                            <textarea id="txt_ads_description" class="form-control" name="ads_description" cols="80" rows="5" autocomplete="off">
                                <?= $HaveRs ? trim($rs['ADS_DESCRIPTION']) : '' ?>
                            </textarea>
                        </div>
                        <div class="form-group">
                            <!------------------------------------------------attachment-------------->
                            <?=modules::run("$MODULE_NAME/$TB_NAME/indexUpload", $HaveRs ? $rs['ATTACHMENT_SER'] : $c, 'JOB_ADS_TB');  ?>
                            <!------------------------------------------------attachment------------------>
                        </div>
                    </div>
                    <div class="row">
                        <hr>
                        <div class="flex-shrink-0">
                            <?php if ( HaveAccess($post_url)) : ?>
                                <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
<!-- Row -->

<?php
$scripts = <<<SCRIPT
<script type="text/javascript">

       var table = '{$TB_NAME}';
       var count = 0;
        
        $('#txt_ads_description').prop('selectionStart');
      

       $('button[data-action="submit"]').click(function(e){
          e.preventDefault();
          var msg= 'هل تريد حفظ الطلب ؟!';
          if(confirm(msg)){
              $(this).attr('disabled','disabled');
              var form = $(this).closest('form');
              ajax_insert_update(form,function(data){
                  if(parseInt(data)>1){
                      success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                      get_to_link('{$get_url}/'+parseInt(data));
                  }else if(data == 1){
                      success_msg('رسالة','تم تعديل البيانات بنجاح ..');
                      get_to_link(window.location.href);
                  }else{
                      danger_msg('تحذير..',data);
                  }
              },'html');
          }
          setTimeout(function() {
              $('button[data-action="submit"]').removeAttr('disabled');
          }, 3000);
      });
       
</script>
SCRIPT;
sec_scripts($scripts);
?>
