<?php
/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 27/07/2019
 * Time: 10:30 ص
 */
$MODULE_NAME = 'maintenance';
$TB_NAME = 'maintenance';
$backs_url = base_url("$MODULE_NAME/$TB_NAME/index"); //$action
$post_url = base_url("$MODULE_NAME/$TB_NAME/" . ($action == 'index' ? 'create' : $action));
$go_to_id_request = base_url("$MODULE_NAME/$TB_NAME/get");
$get_pledges_url = base_url("$MODULE_NAME/$TB_NAME/public_get_pledges");
if (!isset($result))
    $result = array();
$HaveRs = count($result) > 0;
$rs = $HaveRs ? $result[0] : $result;
$technical_support_url = base_url("$MODULE_NAME/$TB_NAME/technical_support");
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
                <h3 class="card-title"><?= $title ?></h3>
                <div class="card-options">
                    <a class="btn btn-secondary" href="<?= $backs_url ?>"><i class="fa fa-reply"></i>
                        العودة
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form id="<?= $TB_NAME ?>_form" method="post" action="<?= $post_url ?>" role="form"
                      novalidate="novalidate">
                    <div class="row">
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
                                        <option <?= $this->user->emp_no ==  $row['EMP_NO'] ? 'selected' : '' ?>  value="<?= $row['EMP_NO'] ?>"><?= $row['EMP_NO'] . " : " . $row['USER_NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                        <?php } ?>
                        <div class="form-group  col-md-2">
                            <label for="dl_service_type">نوع الخدمة </label>
                            <select name="service_type" id="dl_service_type" class="form-control">
                                <option value="">-------</option>
                                <?php foreach ($service_type as $row) : ?>
                                    <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group  col-md-2">
                            <label for="dl_service_property">الأهمية</label>
                            <select type="text" name="service_property" id="dl_service_property" class="form-control">
                                <option value="">-------</option>
                                <?php foreach ($service_property as $row) : ?>
                                    <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group  col-md-12">
                            <label for="desc_problem">وصف العطل</label>
                            <input type="text" value="" name="desc_problem" id="desc_problem" class="form-control" autocomplete="off">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="expanel expanel-info" id="pledges_div" style="display: none;">
                                <div class="expanel-heading">
                                    <h3 class="expanel-title">اختيار العهدة المطلوب اصلاحها</h3>
                                </div>
                                <div class="expanel-body" id="resultDiv">

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php
$technical_support_access = 0;
if (HaveAccess($technical_support_url)) {
    $technical_support_access = 1;
}
$scripts = <<<SCRIPT
<script>
    var permission_technical_support = '{$technical_support_access}';
    if (permission_technical_support == 0) {
      var emp_no = $("#dl_emp_no").val();
      $('#dl_emp_no option:not(:selected)').attr('disabled', true);
      get_pledges(emp_no);
    }
    $('#dl_emp_no').select2();
    function get_pledges(emp_no){
        if (emp_no){
             get_data('$get_pledges_url',{emp_no:emp_no },function(data){
                $('#pledges_div').show();
                $('#resultDiv').html(data);
             },'html'); //END GET_DATA 
        }
             
    }
    $('#dl_emp_no').change(function() {
        var emp_no = $(this).val();
        get_data('$get_pledges_url',{emp_no:emp_no },function(data){
                $('#pledges_div').show();
                $('#resultDiv').html(data);
        },'html'); //END GET_DATA 
    });  
       
        //FOR INSERT DATA
    $('button[data-action="submit"]').click(function(e){           
        e.preventDefault();
        if(confirm('هل تريد الحفظ  ؟!')){
          $(this).attr('disabled','disabled');
            var form = $(this).closest('form');
            ajax_insert_update(form,function(data){
               
                if(parseInt(data)>=1){
                 success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                  get_to_link('$go_to_id_request'+'/'+data);
                }else{
                      danger_msg('تحذير..',data);
                }
            },'html');
        }
        setTimeout(function() {
            $('button[data-action="submit"]').removeAttr('disabled');
        }, 3000);
    });
   /////END INSERT DATA

</script>
SCRIPT;
sec_scripts($scripts);
?>
