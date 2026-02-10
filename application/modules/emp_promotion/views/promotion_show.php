<?php
/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 30/05/2020
 * Time: 9:54 ص
 */
$MODULE_NAME = 'emp_promotion';
$TB_NAME = 'promotion';
$back_url = base_url("$MODULE_NAME/$TB_NAME/index");
$create_url = base_url("$MODULE_NAME/$TB_NAME/create");
$post_url = base_url("$MODULE_NAME/$TB_NAME/" . ($action == 'index' ? 'create' : $action));
$get_url = base_url("$MODULE_NAME/$TB_NAME/get");
$get_efficient_url = base_url("$MODULE_NAME/$TB_NAME/get_efficient_performance");
$adopt_url = base_url("$MODULE_NAME/$TB_NAME/adopt_");
$adopt_detail_url = base_url("$MODULE_NAME/$TB_NAME/public_get_adopt_detail");
$adopt_path_url = base_url("$MODULE_NAME/$TB_NAME/public_get_path_adopt");
$date_attr = "data-type='date' data-date-format='DD/MM/YYYY' data-val='true' data-val-regex-pattern='" . date_format_exp() . "' data-val-regex='Error' ";
$get_emp_data = base_url("$MODULE_NAME/$TB_NAME/public_get_emp_data");
$isCreate = isset($master_tb_data) && count($master_tb_data) > 0 ? false : true;
$HaveRs = (!$isCreate) ? true : false;
$rs = $isCreate ? array() : $master_tb_data[0];
$ser = isset($rs['SER']) ? $rs['SER'] : '';
$status = isset($rs['STATUS']) ? $rs['STATUS'] : '';
$degree_adopt = isset($rs['DEGREE_ADOPT_NAME']) ? $rs['DEGREE_ADOPT_NAME'] : '';
$date_degree = isset($rs['DATE_DEGREE']) ? $rs['DATE_DEGREE'] : '';
$emp_no = isset($rs['EMP_NO']) ? $rs['EMP_NO'] : '';
$yyears = isset($rs['YYEARS']) ? $rs['YYEARS'] : '';
$curr_years = isset($rs['CURR_YEARS']) ? $rs['CURR_YEARS'] : '';


$curr_sum_deg = (int)empty($rs['CURRENT_DEGREE']) ? 0 : $rs['CURRENT_DEGREE'];
$prev_sum_deg = (int)empty($rs['PREVIOUS_YEARS_DEGREE']) ? 0 : $rs['PREVIOUS_YEARS_DEGREE'];

$last_sum_deg = (int)empty($rs['PREVIOUS_LAST_YEARS_DEGREE']) ? 0 : $rs['PREVIOUS_LAST_YEARS_DEGREE'];
$sumation = ($curr_sum_deg + $prev_sum_deg + $last_sum_deg) / 3;


$adopt = isset($rs['ADOPT']) ? $rs['ADOPT'] : '';

$previous_years = isset($rs['PREVIOUS_YEARS']) ? $rs['PREVIOUS_YEARS'] : '';
$last_years = isset($rs['LAST_YEARS']) ? $rs['LAST_YEARS'] : '';

if ($status == 1) {
    $txt = " نوصي بالموافقة على الترقية الى الدرجة ";
    $date = " اعتباراً من تاريخ ";
    $ress = $txt . "  " . $degree_adopt . " " . $date . " " . $date_degree;
} else {
    $txt = "   لا نوصي بالموافقة على الترقية الى الدرجة ";
    $ress = $txt . "  " . $degree_adopt;
}
// $email = isset($rs['EMAIL_SEND'])? $rs['EMAIL_SEND'] :'';
// 
$report_url = base_url("JsperReport/showreport?sys=administrative/promotion");
$email = isset($rs['EMAIL_SEND']) ? $rs['EMAIL_SEND'] : '';

$report_sn = report_sn();
if ($adopt == 20) {
    $email = 'ashaheen@gedco.ps';
}
if ($adopt == 30) {
    $email = 'kaltorok@gedco.ps';
}
?>
<!-- PAGE-HEADER -->
<div class="page-header">
    <div>
        <h1 class="page-title">نموذج ترقية</h1>
    </div>
    <div class="ms-auto pageheader-btn">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">الاداري</a></li>
            <li class="breadcrumb-item active" aria-current="page">الترقيات</li>
        </ol>
    </div>
</div>
<!-- PAGE-HEADER END -->
<!-- Row -->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><?= $title . (($HaveRs) ? ' - ' . $rs['EMP_NAME'] : '') ?></h3>
                <div class="card-options">
                    <a class="btn btn-secondary" href="<?= $back_url ?>"><i class="fa fa-backward"></i>
                        رجوع
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form id="<?= $TB_NAME ?>_form" method="post" action="<?php echo $post_url ?>" role="form"
                      novalidate="novalidate">
                    <div class="row">
                        <div class="form-group col-md-2">
                            <label>رقم النموذح</label>
                            <input type="text" value="<?= $HaveRs ? $rs['SER'] : '' ?>" readonly id="txt_ser"
                                   class="form-control"/>
                            <?php if ((isset($can_edit) ? $can_edit : false)) : ?>
                                <input type="hidden" value="<?= $HaveRs ? $rs['SER'] : '' ?>" name="ser" id="h_ser"/>
                            <?php endif; ?>
                        </div>
                        <div class="form-group col-md-2">
                            <label>رقم الموظف </label>
                            <select name="emp_no" id="dp_emp_no" class="form-control sel2">
                                <option value="">----------</option>
                                <?php foreach ($emp_no_cons as $row) : ?>
                                    <option <?= $HaveRs ? ($rs['EMP_NO'] == $row['EMP_NO'] ? 'selected' : '') : '' ?>
                                            value="<?= $row['EMP_NO'] ?>">
                                        <?= $row['EMP_NO'] . ":" . $row['EMP_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label>السنة</label>
                            <input type="text" placeholder="السنة"
                                   name="yyears"
                                   id="txt_yyears" class="form-control"
                                   value="<?= $HaveRs ? $rs['YYEARS'] : date('Y') ?>">
                        </div>
                        <div class="form-group col-md-2">
                            <label> المقر </label>
                            <input type="text" id="txt_branch"
                                   value="<?= $HaveRs ? $rs['BRANCH_NAME'] : "" ?>"
                                   class="form-control" readonly>
                        </div>
                    </div>
                    <div class="expanel expanel-info">
                        <div class="expanel-heading">
                            <h3 class="expanel-title fw-600 fs-18">بيانات الموظف</h3>
                        </div>
                        <div class="expanel-body">
                            <div class="row">
                                <div class="form-group col-md-2">
                                    <label> الادارة </label>
                                    <input type="text" id="txt_head_department"
                                           value="<?= $HaveRs ? $rs['HEAD_DEPARTMENT_NAME'] : "" ?>"
                                           class="form-control" readonly>
                                </div>
                                <div class="form-group col-md-2">
                                    <label>تاريخ الميلاد </label>
                                    <input type="text" id="txt_birthdate"
                                           value="<?= $HaveRs ? $rs['BIRTH_DATE'] : "" ?>"
                                           class="form-control" readonly>
                                </div>
                                <div class="form-group col-md-2">
                                    <label>رقم الهوية </label>
                                    <input type="text" id="txt_emp_id"
                                           value="<?= $HaveRs ? $rs['ID'] : "" ?>"
                                           class="form-control" readonly>
                                </div>
                                <div class="form-group col-md-2">
                                    <label>تاريخ التعيين </label>
                                    <input type="text" id="txt_date_hiring"
                                           value="<?= $HaveRs ? $rs['HIRE_DATE'] : "" ?>" data-val="true"
                                           class="form-control" readonly>
                                </div>
                                <div class="form-group col-md-2">
                                    <label> المؤهل العلمي </label>
                                    <input type="text" id="txt_qualification"
                                           value="<?= $HaveRs ? $rs['Q_NO_NAME'] : "" ?>" data-val="true"
                                           class="form-control" readonly>
                                </div>
                                <div class="form-group col-md-2">
                                    <label> الوظفية </label>
                                    <input type="text" id="txt_job_title"
                                           value="<?= $HaveRs ? $rs['W_NO_ADMIN_NAME'] : "" ?>" data-val="true"
                                           class="form-control" readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-2">
                                    <label> الفئة </label>
                                    <input type="text" id="txt_job_type"
                                           value="<?= $HaveRs ? $rs['SP_NO_NAME'] : "" ?>" data-val="true"
                                           class="form-control" readonly>
                                </div>
                                <div class="form-group col-md-2">
                                    <label> التدرج الوظيفي </label>
                                    <input type="text" id="txt_kad_no_n"
                                           value="<?= $HaveRs ? $rs['KAD_NO_N_NAME'] : "" ?>" data-val="true"
                                           class="form-control" readonly>
                                </div>
                                <div class="form-group col-md-2">
                                    <label> الدرجة الحالية </label>
                                    <input type="text" id="txt_degree_current" name="degree_current_name"
                                           value="<?= $HaveRs ? $rs['DEGREE_NAME'] : "" ?>" data-val="true"
                                           class="form-control" readonly>

                                    <input type="hidden" id="txt_degree_current_h" name="degree_current"
                                           value="<?= $HaveRs ? $rs['DEGREE'] : "" ?>">
                                </div>
                                <div class="form-group col-md-3">
                                    <label> تاريخ الحصول عليها </label>
                                    <input type="text" placeholder="التاريخ"
                                           name="get_date_degree" <?= $date_attr ?>
                                           id="txt_get_date_degree" class="form-control"
                                           value="<?= $HaveRs ? $rs['GET_DATE_DEGREE'] : "" ?>">

                                </div>
                                <div class="form-group col-md-3">
                                    <label> عدد الأقدميات على الدرجة </label>
                                    <input type="text" id="txt_count_degree"
                                           value="<?= $HaveRs ? $rs['HIRE_YEARS'] : "" ?>" data-val="true"
                                           class="form-control" readonly>

                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <h3 class="text-danger fw-600 fs-18">نوصي بترقية الموظف المذكور اعلاه الى
                                        الدرجة</h3>
                                    <div class="form-group col-md-4">
                                        <select name="degree_adopt" id="dp_degree_adopt" class="form-control sel2">
                                            <option value="">____اختر الدرجة___</option>
                                            <?php foreach ($degree_cons as $row) : ?>
                                                <option <?= $HaveRs ? ($rs['DEGREE_ADOPT'] == $row['CON_NO'] ? 'selected' : '') : '' ?>
                                                        value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <?php if (HaveAccess($get_efficient_url) && ($action == "edit") && $adopt >= 1) : ?>
                        <div class="expanel expanel-secondary">
                            <div class="expanel-heading">
                                <h3 class="expanel-title fw-600 fs-18">تقرير كفاءة الاداء لأخر ثلاث سنوات الخاص
                                    بالموظف</h3>
                            </div>
                            <div class="expanel-body">
                                <div class="row">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="page_tb" data-container="container">
                                            <thead class="table-light">
                                            <tr>
                                                <th>السنة <?= $curr_years ?> - التقدير</th>
                                                <th>السنة <?= $previous_years ?> - التقدير</th>
                                                <th>السنة <?= $last_years ?>- التقدير</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td><?= $HaveRs ? $rs['CURRENT_DEGREE'] : "" ?>
                                                    - <?= $HaveRs ? $rs['CURRENT_DEGREE_NAME'] : "" ?>  </td>
                                                <td><?= $HaveRs ? $rs['PREVIOUS_YEARS_DEGREE'] : "" ?>
                                                    - <?= $HaveRs ? $rs['PREVIOUS_YEARS_DEGREE_NAME'] : "" ?> </td>
                                                <td><?= $HaveRs ? $rs['PREVIOUS_LAST_YEARS_DEGREE'] : "" ?>
                                                    - <?= $HaveRs ? $rs['LAST_YEARS_DEGREE_NAME'] : "" ?></td>
                                            </tr>
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <td colspan='3'> المتوسط / <?= number_format($sumation, 2) ?></td>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <hr>
                    <?php if ($adopt > 1) { ?>
                        <div class="expanel expanel-info">
                            <div class="expanel-heading">
                                <h3 class="expanel-title fw-600 fs-18"> توصية الاعتماد</h3>
                            </div>
                            <div class="expanel-body">
                                <div class="row">
                                    <div class="form-group col-md-2">
                                        <label>التوصية</label>
                                        <select name="status" id="dp_status" class="form-control sel2">
                                            <option value="">_________</option>
                                            <?php foreach ($status_cons as $row) : ?>
                                                <option <?= $HaveRs ? ($rs['STATUS'] == $row['CON_NO'] ? 'selected' : '') : '' ?>
                                                        value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-5">
                                        <label> ملاحظة الاعتماد </label>
                                        <input type="text"
                                               id="txt_adopt_note" data-val="true"
                                               class="form-control" name="adopt_note">
                                    </div>
                                    <?php if ($adopt >= 30) { ?>
                                        <div class="form-group col-md-2">
                                            <label>التاريخ</label>
                                            <input type="text" placeholder="التاريخ"
                                                   name="date_degree" <?= $date_attr ?>
                                                   id="txt_date_degrees" class="form-control"
                                                   value="<?= $HaveRs ? $rs['DATE_DEGREE'] : "" ?>">
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <hr>
                    <?php  if ($adopt >= 40) { ?>
                        <div class="expanel expanel-info">
                            <div class="expanel-heading">
                                <h3 class="expanel-title fw-600 fs-18">استعمال دائرة شؤون الموظفين</h3>
                            </div>
                            <div class="expanel-body">
                                <h4>
                                    البيانات المبينة أعلاه مطابقة لملف المذكور

                                    <?php echo $ress . "\n"; ?>

                                </h4>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="row">
                        <div class="flex-shrink-0">
                            <?php if (HaveAccess($post_url) && ($isCreate || ($rs['ADOPT'] == 1 and isset($can_edit) ? $can_edit : false))) : ?>
                                <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                            <?php endif; ?>
                            <?php if (HaveAccess($adopt_url . '10') and !$isCreate and $rs['ADOPT'] == 1) : ?>
                                <button type="button" onclick='javascript:adopt_(10);' class="btn btn-success">اعتماد
                                    المدخل
                                </button>
                            <?php endif; ?>
                            <?php if (HaveAccess($adopt_url . '20') and !$isCreate and $rs['ADOPT'] == 10) : ?>
                                <button type="button" onclick='javascript:adopt_(20);' class="btn btn-success">اعتماد
                                    مدير الدائرة
                                </button>
                            <?php endif; ?>
                            <?php if (HaveAccess($adopt_url . '30') and !$isCreate and $rs['ADOPT'] == 20) : ?>
                                <button type="button" onclick='javascript:adopt_(30);' class="btn btn-success">اعتماد
                                    مدير المقر/ الادارة
                                </button>
                            <?php endif; ?>
                            <?php if (HaveAccess($adopt_url . '40') and !$isCreate and $rs['ADOPT'] == 30) : ?>
                                <button type="button" onclick='javascript:adopt_(40);' class="btn btn-success">اعتماد
                                    دائرة شؤون الموظفين
                                </button>
                            <?php endif; ?>
                            <?php if (HaveAccess($adopt_url . '50') and !$isCreate and $rs['ADOPT'] == 40) : ?>
                                <button type="button" onclick='javascript:adopt_(50);' class="btn btn-success">اعتماد
                                    ادارة الموارد البشرية
                                </button>
                            <?php endif; ?>
                            <?php if ($action == "edit") { ?>
                                <button type="button" onclick='javascript:get_path_adopt(<?= $emp_no ?>);'
                                        class="btn btn-warning">مسار الاعتماد
                                </button>
                            <?php } ?>
                            <?php if ($adopt > 1) { ?>
                                <button type="button" onclick='javascript:show_detail_row(<?= $ser ?>);'
                                        class="btn btn-secondary">بيانات الاعتماد
                                </button>
                            <?php } ?>
                            <?php if ($HaveRs) { ?>
                                <button type="button" id="rep_details"
                                        onclick='javascript:print_report(<?php echo $rs['SER'] ?>);'
                                        class="btn btn-blue">
                                    <i class="fa fa-print"></i>
                                    طباعة
                                </button>
                            <?php } ?>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!--Modal Bootstrap مسار الاعتماد-->
    <div class="modal fade"  id="PathModal" tabindex="-1" role="dialog" >
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">بيانات</h5>
                    <button  class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="table-responsive <?= $TB_NAME; ?>_path_adopt">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>المسمى الاشرافي</th>
                                    <th>الاسم</th>
                                </tr>
                                </thead>
                                <tbody id="append_path_data">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button  class="btn btn-secondary" data-bs-dismiss="modal">اغلاق</button>
                </div>
            </div>
        </div>
    </div>
    <!--Modal Bootstrap مسار الاعتماد-->


    <!--Modal Bootstrap بيانات الاعتماد-->
    <div class="modal fade"  id="exampleModal" tabindex="-1" role="dialog" >
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">بيانات الاعتماد</h5>
                    <button  class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="table-responsive <?=$TB_NAME;?>_Detail_adopt"">
                        <table class="table table-bordered">
                            <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>الجهة المعتمدة</th>
                                <th>اسم المعتمد</th>
                                <th>تاريخ الاعتماد</th>
                                <th>التوصية</th>
                                <th>ملاحظة الاعتماد</th>
                            </tr>
                            </thead>
                            <tbody id="append_data">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button  class="btn btn-secondary" data-bs-dismiss="modal">اغلاق</button>
                </div>
            </div>
        </div>
    </div>

    <!--Modal Bootstrap بيانات الاعتماد-->


</div>


<?php
$scripts = <<<SCRIPT
<script type="text/javascript">


     var table = '{$TB_NAME}';
    var count = 0;



   $('.sel2:not("[id^=\'s2\']")').select2();

   $('#txt_yyears').datetimepicker({
            format: 'YYYY',
            minViewMode: 'years',
            pickTime: false,
            
   });
    

   $('#dp_emp_no').change(function(){
        get_emp_data(0)
     });    
  
   
    function get_emp_data(){
        var no =  $('#dp_emp_no').val();
    
        if (no == '') {
            return -1;
        } else
             get_data('{$get_emp_data}', {no: no}, function (data) {
				      //console.log(data);
                 $.each(data, function (i, value) {
                // console.log(option.NAME);
				
				 //var my_res  =  value.DEGREE + ' ' + value.DEGREE_NAME;
				
                $('#txt_emp_id').val(value.ID);
                $('#txt_birthdate').val(value.BIRTH_DATE);
                $('#txt_date_hiring').val(value.HIRE_DATE);
				$('#txt_degree_current_h').val(value.DEGREE);
                $('#txt_degree_current').val(value.DEGREE_NAME);
                $('#txt_branch').val(value.BRANCH_NAME);
                $('#txt_head_department').val(value.HEAD_DEPARTMENT_NAME);
                $('#txt_job_title').val(value.W_NO_ADMIN_NAME);
                $('#txt_qualification').val(value.Q_NO_NAME);
                $('#txt_job_type').val(value.SP_NO_NAME);
                $('#txt_kad_no_n').val(value.KAD_NO_N_NAME);
				$(txt_count_degree).val(value.HIRE_YEARS);
				
                  });
             });
     }
        
      function adopt_(no){
        var msg= 'هل تريد الاعتماد ؟!';
        if(confirm(msg)){
            var values= {ser: $('#h_ser').val(),adopt_note: $('#txt_adopt_note').val(), status : $('#dp_status').val() , date_degree: $('#txt_date_degrees').val(),degree_adopt: $('#dp_degree_adopt').val()};
             if ($('#dp_status').val() == '') {
                danger_msg('يرجى ادخال التوصية');
            }else if ($('#dp_status').val() == 2 && $('#txt_adopt_note').val()== '' ) {
                 danger_msg('يرجى ادخال الملاحظة');
                 } else {
            get_data('{$adopt_url}'+no, values, function(ret){
                if(ret==1){
                    success_msg('رسالة','تم اعتماد الطلب بنجاح ..');
                    $('button').attr('disabled','disabled');
                     var sub= 'اعتماد طلب نموذج ترقية {$rs['SER']}';
                        var text= 'يرجى اعتماد  طلب نموذج ترقية {$rs['SER']}';
                        text+= '<br>{$rs['EMP_NAME']} - {$rs['SER']}';
                        text+= '<br>للاطلاع افتح الرابط';
                        text+= '<br>http://gs{$get_url}/{$rs['SER']}';
                        _send_mail('{$email}',sub,text);
                        setTimeout(function(){
                        get_to_link(window.location.href);
                    },2000);
                }else{
                    danger_msg('تحذير..',ret);
                }
            }, 'html');
        }
             
           }
    }
    
        function show_detail_row(ser)
        {
         $('#exampleModal').modal('show');
          var html='';
         get_data('{$adopt_detail_url}', {ser:ser} , function(ret){
         $('.'+table+'_Detail_adopt table tbody tr').remove();
         count1 = 1;
         $(ret).each(function(key, value) {
         html = html+'<tr><td>'+count1+'</td><td>'+value.ADOPT_NAME+'</td><td>'+value.ADOPT_USER_NAME+'</td><td>'+value.ADOPT_DATE+'</td><td>'+value.STATUS_NAME+'</td> <td>'+value.NOTE+'</td></tr>';
          count1 = count1 + 1;

         });
         $('#append_data').append(html);
         });
        }
        
        
        
        function get_path_adopt(emp_no)
        {
          $('#PathModal').modal('show');
          var html='';
         get_data('{$adopt_path_url}', {emp_no:emp_no} , function(ret){
         $('.'+table+'_path_adopt table tbody tr').remove();
         count1 = 1;
         $(ret).each(function(key, value) {
         html = html+'<tr><td>'+count1+'</td><td>'+value.SUPERVISION_NAME+'</td><td>'+value.MANAGER_NAME+'</td></tr>';
          count1 = count1 + 1;

         });
         $('#append_path_data').append(html);
         });
        }
        
          function print_report(ser){
            var rep_url = '{$report_url}&report_type=pdf&report=promotion&ser_in='+ser+'';
			_showReport(rep_url);
          }
  
     
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
                }else if(data==1){
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
