<?php
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 29/06/19
 * Time: 10:36 ص
 */

$MODULE_NAME = 'salary';
$TB_NAME = 'employees';
$back_url = base_url("$MODULE_NAME/$TB_NAME/index");

$post_url = base_url("$MODULE_NAME/$TB_NAME/" . ($action == 'index' ? 'create' : $action));
$edit_url = base_url("$MODULE_NAME/$TB_NAME/edit");

$get_url = base_url("$MODULE_NAME/$TB_NAME/get");

$isCreate = isset($master_tb_data) && count($master_tb_data) > 0 ? false : true;
$HaveRs = (!$isCreate) ? true : false;
$rs = $isCreate ? array() : $master_tb_data[0];
?>
<style>
    .inline_form .required {
        color: red;
    }
</style>
<!-- PAGE-HEADER -->
<div class="page-header">
    <div>
        <h1 class="page-title"><?= $title . (($HaveRs) ? ' - ' . $rs['NAME'] : '') ?></h1>
    </div>
    <div class="ms-auto pageheader-btn">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">الرواتب</a></li>
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
                <h3 class="card-title">
                    <?= $title . (($HaveRs) ? ' - ' . $rs['NAME'] : '') ?>
                </h3>
                <div class="card-options">
                    <?php if (HaveAccess($back_url)): ?>
                        <a href="<?= $back_url ?>" class="btn btn-secondary"><i class="fa fa-reply-all"></i></a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-body">
                <form class="form-vertical inline_form" id="<?= $TB_NAME ?>_form" method="post"
                      action="<?= $post_url ?>" role="form" novalidate="novalidate">
                    <div class="panel panel-primary">
                        <div class="tab-menu-heading border-bottom-0">
                            <div class="tabs-menu4 border-bottomo-sm">
                                <!-- Tabs -->
                                <nav class="nav d-sm-flex d-block">
                                    <a class="nav-link border border-bottom-0 br-sm-5 me-2 active" data-bs-toggle="tab" href="#tab25">
                                        بيانات شخصية
                                    </a>
                                    <a class="nav-link border border-bottom-0 br-sm-5 me-2" data-bs-toggle="tab" href="#tab26">
                                        بيانات ادارية
                                    </a>
                                    <a class="nav-link border border-bottom-0 br-sm-5 me-2" data-bs-toggle="tab" href="#tab27">
                                        بيانات مالية
                                    </a>
                                </nav>
                            </div>
                        </div>
                        <div class="panel-body tabs-menu-body">
                            <div class="tab-content">
                                <div class="tab-pane active " id="tab25">
                                    <div class="row">
                                        <div class="col-xl-12">

                                            <div class="row">
                                                <div class="form-group col-md-2">
                                                    <label>رقم الموظف
                                                        <span class="required">*</span>
                                                    </label>
                                                    <input type="text"
                                                           value="<?= $HaveRs ? $rs['NO'] : $emp_no[0]['MAX_EMP_NO'] ?>"
                                                           name="no" id="txt_no"
                                                           class="form-control" autocomplete="off"/>
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label>رقم الهوية
                                                        <span class="required">*</span>
                                                    </label>
                                                    <input type="text" value="<?= $HaveRs ? $rs['ID'] : "" ?>" name="id"
                                                           id="txt_id" class="form-control"/>
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label>اسم الموظف
                                                        <span class="required">*</span>
                                                    </label>
                                                    <input type="text" value="<?= $HaveRs ? $rs['NAME'] : "" ?>" name="name"
                                                           id="txt_name" class="form-control"/>
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label>الجوال</label>
                                                    <input type="text" value="<?= $HaveRs ? $rs['TEL'] : "" ?>" name="tel"
                                                           id="txt_tel" class="form-control"/>
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label>مكان الميلاد</label>
                                                    <input type="text" value="<?= $HaveRs ? $rs['BIRTH_PLACE'] : "" ?>"
                                                           name="birth_place" id="txt_birth_place" class="form-control"/>
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label>الحالة الاجتماعية <span class="required">*</span></label>
                                                    <select name="status" id="dp_status" class="form-control sel2">
                                                        <option value="">_________</option>
                                                        <?php foreach ($status_cons as $row) : ?>
                                                            <option <?= $HaveRs ? ($rs['STATUS'] == $row['CON_NO'] ? 'selected' : '') : '' ?>
                                                                    value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>  <!--end first row-->
                                            <div class="row">
                                                <div class="form-group col-md-2">
                                                    <label>تاريخ الميلاد<span class="required">*</span></label>
                                                    <input type="text" value="<?= $HaveRs ? $rs['BIRTH_DATE'] : "" ?>"
                                                           name="birth_date" id="txt_birth_date" class="form-control"
                                                           data-type="date" data-date-format="DD/MM/YYYY" autocomplete="off"/>
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label>الديانة</label>
                                                    <select name="religion" id="dp_religion" class="form-control sel2">
                                                        <option value="">_________</option>
                                                        <?php foreach ($religion_cons as $row) : ?>
                                                            <option <?= $HaveRs ? ($rs['RELIGION'] == $row['CON_NO'] ? 'selected' : '') : '' ?>
                                                                    value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label>الجنس<span class="required">*</span></label>
                                                    <select name="sex" id="dp_sex" class="form-control sel2">
                                                        <option value="">_________</option>
                                                        <?php foreach ($sex_cons as $row) : ?>
                                                            <option <?= $HaveRs ? ($rs['SEX'] == $row['CON_NO'] ? 'selected' : '') : '' ?>
                                                                    value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label>العنوان</label>
                                                    <input type="text" value="<?= $HaveRs ? $rs['ADDRESS'] : "" ?>"
                                                           name="address" id="txt_address" class="form-control"/>
                                                </div>
                                            </div> <!--end second row-->

                                        </div><!--end col-xl-12--->
                                    </div><!--end row--->
                                </div>
                                <div class="tab-pane" id="tab26">
                                    <div class="row">
                                        <div class="col-xl-12">

                                            <div class="row">
                                                <div class="form-group col-md-2">
                                                    <label>نوع التعيين<span class="required">*</span></label>
                                                    <select name="emp_type" id="dp_emp_type" class="form-control sel2">
                                                        <option value="">_________</option>
                                                        <?php foreach ($emp_type_cons as $row) : ?>
                                                            <option <?= $HaveRs ? ($rs['EMP_TYPE'] == $row['CON_NO'] ? 'selected' : '') : '' ?>
                                                                    value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label> تاريخ التعيين<span class="required">*</span></label>
                                                    <input type="text" value="<?= $HaveRs ? $rs['HIRE_DATE'] : "" ?>"
                                                           name="hire_date" id="txt_hire_date" class="form-control"
                                                           data-type="date" data-date-format="DD/MM/YYYY" autocomplete="off"/>
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label>تاريخ التثبيت</label>
                                                    <input type="text" value="<?= $HaveRs ? $rs['FEX_DATE'] : "" ?>"
                                                           name="fex_date" id="txt_fex_date" class="form-control"
                                                           data-type="date" data-date-format="DD/MM/YYYY" autocomplete="off"/>
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label>المقر الرئيسي<span class="required">*</span></label>
                                                    <select name="bran" id="dp_bran" class="form-control sel2">
                                                        <option value="">_________</option>
                                                        <?php foreach ($bran_cons as $row) : ?>
                                                            <option <?= $HaveRs ? ($rs['BRAN'] == $row['CON_NO'] ? 'selected' : '') : '' ?>
                                                                    value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label>الهيكلية</label>
                                                    <input type="text" value="<?= $HaveRs ? $rs['GCC_NO'] : "" ?>" name="gcc_no"
                                                           id="txt_gcc_no" class="form-control"/>
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label>الادارة</label>
                                                    <select name="head_department" id="dp_head_department"
                                                            class="form-control sel2">
                                                        <option value="">_________</option>
                                                        <?php foreach ($head_department_cons as $row) : ?>
                                                            <option <?= $HaveRs ? ($rs['HEAD_DEPARTMENT'] == $row['CON_NO'] ? 'selected' : '') : '' ?>
                                                                    value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>

                                            </div>
                                            <div class="row">

                                                <div class="form-group col-md-2">
                                                    <label>سنة التخرج</label>
                                                    <input type="text" value="<?= $HaveRs ? $rs['Q_DATE'] : "" ?>" name="q_date"
                                                           id="txt_q_date" class="form-control" autocomplete="off"/>
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label>المؤهل العلمي <span class="required">*</span> </label>
                                                    <select name="q_no" id="dp_q_no" class="form-control sel2">
                                                        <option value="">_________</option>
                                                        <?php foreach ($q_no_cons as $row) : ?>
                                                            <option <?= $HaveRs ? ($rs['Q_NO'] == $row['CON_NO'] ? 'selected' : '') : '' ?>
                                                                    value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>

                                                <div class="form-group col-md-2">
                                                    <label>الفئة<span class="required">*</span> </label>
                                                    <select name="sp_no" id="dp_sp_no" class="form-control sel2">
                                                        <option value="">_________</option>
                                                        <?php foreach ($sp_no_cons as $row) : ?>
                                                            <option <?= $HaveRs ? ($rs['SP_NO'] == $row['CON_NO'] ? 'selected' : '') : '' ?>
                                                                    value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>


                                                <div class="form-group col-md-2">
                                                    <label> التدرج الوظيفي<span class="required">*</span> </label>
                                                    <select name="kad_no_n" id="dp_kad_no_n" class="form-control sel2">
                                                        <option value="">_________</option>
                                                        <?php foreach ($kad_no_n_cons as $row) : ?>
                                                            <option <?= $HaveRs ? ($rs['KAD_NO_N'] == $row['CON_NO'] ? 'selected' : '') : '' ?>
                                                                    value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>

                                                <div class="form-group col-md-2">
                                                    <label> العلاوات الفعلية<span class="required">*</span> </label>
                                                    <input type="text" value="<?= $HaveRs ? $rs['PERIODICAL_ALLOWNCES'] : "" ?>"
                                                           name="periodical_allownces" id="txt_periodical_allownces"
                                                           class="form-control"/>
                                                </div>

                                                <div class="form-group col-md-2">
                                                    <label>الدرجة<span class="required">*</span> </label>
                                                    <select name="degree" id="dp_degree" class="form-control sel2">
                                                        <option value="">_________</option>
                                                        <?php foreach ($degree_cons as $row) : ?>
                                                            <option <?= $HaveRs ? ($rs['DEGREE'] == $row['CON_NO'] ? 'selected' : '') : '' ?>
                                                                    value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row">

                                                <div class="form-group col-md-2">
                                                    <label>العلاوة الدورية<span class="required">*</span> </label>
                                                    <input type="text" value="<?= $HaveRs ? $rs['HIRE_YEARS'] : "" ?>"
                                                           name="hire_years" id="txt_hire_years" class="form-control"/>
                                                </div>

                                                <div class="form-group col-md-2">
                                                    <label>المسمى المهني</label>
                                                    <select name="w_no" id="dp_w_no" class="form-control sel2">
                                                        <option value="">_________</option>
                                                        <?php foreach ($w_no_cons as $row) : ?>
                                                            <option <?= $HaveRs ? ($rs['W_NO'] == $row['CON_NO'] ? 'selected' : '') : '' ?>
                                                                    value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>


                                                <div class="form-group col-md-2">
                                                    <label>المسمى الوظيفي</label>
                                                    <select name="w_no_admin" id="dp_w_no_admin" class="form-control sel2">
                                                        <option value="">_________</option>
                                                        <?php foreach ($w_no_admin_cons as $row) : ?>
                                                            <option <?= $HaveRs ? ($rs['W_NO_ADMIN'] == $row['CON_NO'] ? 'selected' : '') : '' ?>
                                                                    value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>

                                                <div class="form-group col-md-2">
                                                    <label>الخبرات</label>
                                                    <input type="text" value="<?= $HaveRs ? $rs['EXPERIENCES'] : "" ?>"
                                                           name="experiences" id="txt_experiences" class="form-control"/>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tab27">
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <div class="row">
                                                <div class="form-group col-md-2">
                                                    <label>البنك</label>
                                                    <select name="bank" id="dp_bank" class="form-control sel2">
                                                        <option value="">_________</option>
                                                        <?php foreach ($bank_cons as $row) : ?>
                                                            <option <?= $HaveRs ? ($rs['BANK'] == $row['CON_NO'] ? 'selected' : '') : '' ?>
                                                                    value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>

                                                <div class="form-group col-md-2">
                                                    <label>رقم الحساب</label>
                                                    <input type="text" value="<?= $HaveRs ? $rs['ACCOUNT'] : "" ?>"
                                                           name="account"
                                                           id="txt_account" class="form-control"/>

                                                </div>
                                                <div class="col-md-3">
                                                    <label>IBAN</label>
                                                    <input type="text" value="<?= $HaveRs ? $rs['IBAN'] : "" ?>" name="iban"
                                                           id="txt_iban"
                                                           class="form-control"/>

                                                </div>

                                                <div class="form-group col-md-2">
                                                    <label>فعال
                                                        <span class="required">*</span>
                                                    </label>
                                                    <select name="is_active" id="dp_is_active" class="form-control sel2">
                                                        <option value="">_________</option>
                                                        <?php foreach ($is_active_cons as $row) : ?>
                                                            <option <?= $HaveRs ? ($rs['IS_ACTIVE'] == $row['CON_NO'] ? 'selected' : '') : '' ?>
                                                                    value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                                        <?php endforeach; ?>
                                                    </select>

                                                </div>

                                                <div class="form-group col-md-2">
                                                    <label>خاضع للضريبة
                                                        <span class="required">*</span>
                                                    </label>
                                                    <select name="is_taxed" id="dp_is_taxed" class="form-control sel2">
                                                        <option value="">_________</option>
                                                        <?php foreach ($is_taxed_cons as $row) : ?>
                                                            <option <?= $HaveRs ? ($rs['IS_TAXED'] == $row['CON_NO'] ? 'selected' : '') : '' ?>
                                                                    value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                                        <?php endforeach; ?>
                                                    </select>

                                                </div>

                                                <div class="form-group col-md-2">
                                                    <label>
                                                        خاضع للتأمين الصحي
                                                        <span class="required">*</span>
                                                    </label>
                                                    <select name="insuranced" id="dp_insuranced" class="form-control sel2">
                                                        <option value="">_________</option>
                                                        <?php foreach ($insuranced_cons as $row) : ?>
                                                            <option <?= $HaveRs ? ($rs['INSURANCED'] == $row['CON_NO'] ? 'selected' : '') : '' ?>
                                                                    value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                                        <?php endforeach; ?>
                                                    </select>

                                                </div>

                                                <div class="form-group col-md-2">
                                                    <label>رقم التأمين الصحي</label>
                                                    <input type="text" value="<?= $HaveRs ? $rs['INSURANCE_NO'] : "" ?>"
                                                           name="insurance_no"
                                                           id="txt_insurance_no" class="form-control"/>

                                                </div>

                                                <div class="form-group col-md-2">
                                                    <label>البنك</label>
                                                    <select name="master_banks_email" id="dp_master_banks_email"
                                                            class="form-control sel2">
                                                        <option value="">_________</option>
                                                        <?php foreach ($master_banks_email_cons as $row) : ?>
                                                            <option <?= $HaveRs ? ($rs['MASTER_BANKS_EMAIL'] == $row['CON_NO'] ? 'selected' : '') : '' ?>
                                                                    value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>

                                                <div class="form-group col-md-2">
                                                    <label>فرع البنك</label>
                                                    <select name="bank_branch_email" id="dp_bank_branch_email"
                                                            class="form-control sel2">
                                                        <option value="">_________</option>
                                                        <?php foreach ($bank_branch_email_cons as $row) : ?>
                                                            <option <?= $HaveRs ? ($rs['BANK_BRANCH_EMAIL'] == $row['CON_NO'] ? 'selected' : '') : '' ?>
                                                                    value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>

                                                <div class="col-sm-2">
                                                    <label>الحساب</label>
                                                    <input type="text"
                                                           value="<?= $HaveRs ? $rs['ACCOUNT_BANK_EMAIL'] : "" ?>"
                                                           name="account_bank_email" id="txt_account_bank_email"
                                                           class="form-control"/>
                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        <?php if (HaveAccess($post_url) && ($isCreate || (isset($can_edit) ? $can_edit : false))) : ?>
                            <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php
$scripts = <<<SCRIPT
<script>

    reBind();
    var action = '{$action}';
    
    if (action == 'my_data'){
        $('.inline_form input').prop('readonly',1);
        $('.sel2').select2({ disabled:'readonly' });
    }
    
    function reBind(){
        $('.sel2:not("[id^=\'s2\']")').select2({
            width: 'auto'
	    });
    } // reBind
    
     $('#txt_q_date').datetimepicker({
            format: 'YYYY',
            minViewMode: 'years',
            pickTime: false,
            
     });
SCRIPT;

if ($isCreate) {
    $scripts = <<<SCRIPT
    {$scripts}

    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
    }
    
      $('button[data-action="submit"]').click(function(e){
            e.preventDefault();
            var msg= 'هل تريد حفظ بيانات الموظف ؟!';
            if(confirm(msg)){
                $(this).attr('disabled','disabled');
                var form = $(this).closest('form');
                ajax_insert_update(form,function(data){
                    if(parseInt(data)>1){
                        success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                        get_to_link('{$get_url}/'+parseInt(data));
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
} else { // get or edit
    $scripts = <<<SCRIPT
    {$scripts}

  $('button[data-action="submit"]').click(function(e){
            e.preventDefault();
            var msg= 'هل تريد حفظ بيانات الموظف ؟!';
            if(confirm(msg)){
                $(this).attr('disabled','disabled');
                var form = $(this).closest('form');
                ajax_insert_update(form,function(data){
                   if(data==1){
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
}

sec_scripts($scripts);

?>
