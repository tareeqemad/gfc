<?php
/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 26/01/2019
 * Time: 11:24 ص
 */
$MODULE_NAME = 'empvacancey';
$TB_NAME = "vacancey";
//$post_url= base_url("$MODULE_NAME/$TB_NAME/".($action == 'index'?'create':$action));
$date_attr = "data-type='date' data-date-format='DD/MM/YYYY' data-val='true'
                  data-val-regex-pattern='" . date_format_exp() . "' data-val-regex='Error' ";
$isCreate = isset($get_data) && count($get_data) > 0 ? false : true;
$rs = $isCreate ? array() : $get_data[0];
$back_url = '';
$get_url = base_url("$MODULE_NAME/$TB_NAME/status_create");
?>
<!-- PAGE-HEADER -->
<div class="page-header">
    <div>
        <h1 class="page-title">اضافة طلب خلو طرف</h1>
    </div>
    <div class="ms-auto pageheader-btn">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">النظام الاداري</a></li>
            <li class="breadcrumb-item active" aria-current="page">اضافة طلب خلو طرف</li>
        </ol>
    </div>
</div>
<!-- PAGE-HEADER END -->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><?= $title ?></h3>
                <div class="card-options">
                    <a class="btn btn-secondary" href="<?= $index_request_url ?>">
                        <i class="fa fa-inbox"></i>
                        متابعة طلبات خلو الطرف
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form id="<?= $TB_NAME ?>_form" method="post" action="" role="form"
                      novalidate="novalidate">

                    <div class="row mt-12">
                        <div class="col-lg-12">
                            <div class="expanel expanel-primary">
                                <div class="expanel-heading">
                                    <h3 class="expanel-title fs-20 text-bold">
                                        بيانات الموظف-اضافة شهادة خلو طرف
                                    </h3>
                                </div>
                                <div class="expanel-body">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <!--start no -->
                                            <div class="form-group  col-md-2">
                                                <label class="control-label">الرقم الوظيفي</label>
                                                <input type="text" value="<?php echo $rs['NO']; ?>" name="emp_no"
                                                       id="txt_emp_no" readonly class="form-control ">
                                            </div>
                                            <!--end no -->
                                            <!--start name -->
                                            <div class="form-group  col-md-2">
                                                <label class="control-label"> الموظف </label>
                                                <input type="text" value="<?php echo $rs['NAME']; ?>" name="emp_name"
                                                       id="txt_emp_name" readonly class="form-control">
                                            </div>
                                            <!--end name -->
                                            <!--start id -->
                                            <div class="form-group  col-md-2">
                                                <label class="control-label"> رقم الهوية </label>
                                                <input type="text" value="<?php echo $rs['ID']; ?>" name="emp_id"
                                                       id="txt_emp_id" readonly class="form-control ">
                                            </div>
                                            <!--end id -->
                                            <!--start job -->
                                            <div class="form-group  col-md-2">
                                                <label class="control-label">الوظيفة</label>
                                                <input type="text" value="<?php echo $rs['ST_NAME'] ?>" name="emp_job"
                                                       id="txt_emp_job" readonly class="form-control">
                                            </div>
                                            <!--end job -->

                                            <!--start branch-->
                                            <div class="form-group  col-md-2">
                                                <label class="control-label">الفرع</label>
                                                <input type="hidden" value="<?php echo $rs['BRANCH']; ?>" name="branch"
                                                       id="txt_branch" readonly class="form-control ">
                                                <input type="text" value="<?php echo $rs['BRANCH_NAME']; ?>" readonly
                                                       class="form-control ">
                                            </div>
                                            <!--end branch -->
                                            <div class="form-group  col-md-3">
                                                <label class="control-label">ملاحظة</label>
                                                <input type="text" value="" name="v_note" id="txt_v_note"
                                                       class="form-control" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-12">
                        <div class="col-lg-12">
                            <div class="expanel expanel-primary">
                                <div class="expanel-heading">
                                    <h3 class="expanel-title fs-20 text-bold">
                                        قد أصبح خالي الطرف لدينا
                                    </h3>
                                </div>
                                <div class="expanel-body">
                                    <h5 class="text-on-pannel text-primary"><strong class="text-uppercase">قد أصبح خالي
                                            الطرف لدينا</strong></h5>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="form-group col-md-5 py-3">
                                                    <h5>علما بأن خدمته انتهت في شركة توزيع الكهرباء اعتبار من</h5>

                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label class="control-label">تاريخ </label>
                                                    <input type="text" <?= $date_attr ?> name="emp_end_date"
                                                           id="txt_emp_end_date"
                                                           class="form-control" autocomplete="off">
                                                </div>

                                                <div class="form-group  col-md-3">
                                                    <label class="control-label">السبب</label>
                                                    <input type="text" value="" name="emp_end_reason"
                                                           id="txt_emp_end_reason"
                                                           class="form-control" autocomplete="off">

                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <!------------------>
                    <div class="modal-footer">
                        <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
$scripts = <<<SCRIPT
<script type="text/javascript">

     $(document).ready(function() {
            $('#dp_branch').select2();
     });
 
    $('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        var v_note = $('#txt_v_note').val();
        var emp_end_date = $('#txt_emp_end_date').val();
        var emp_end_reason = $('#txt_emp_end_reason').val();
        if (v_note == '' || emp_end_date == '' || emp_end_reason == '' ){
            danger_msg('يرجى ادخال جميع البيانات');
            return -1;
        }else{
            if(confirm('هل تريد الحفظ  ؟!')){
              $(this).attr('disabled','disabled');
                var form = $(this).closest('form');
                ajax_insert_update(form,function(data){
                    if(parseInt(data)>=1){
                        success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                        get_to_link('{$get_url}/'+parseInt(data)+'/');
                    }else{
                          danger_msg('تحذير..',data);
                    }
                },'html');
            }
              setTimeout(function() {
                    $('button[data-action="submit"]').removeAttr('disabled');
              }, 3000);
        }
    });

    
</script>
SCRIPT;
sec_scripts($scripts);
?>

