<?php

/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 27/04/20
 * Time: 10:01 ص
 */
$MODULE_NAME = 'payroll_data';
$TB_NAME = 'morning_delay_adopt';
$gfc_domain = gh_gfc_domain();
$index_url = base_url("$MODULE_NAME/$TB_NAME/index");
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page"); //جلب كشف تجميع ساعات الخصم
$adopt_all_url = base_url("$MODULE_NAME/$TB_NAME/public_adopt"); //الاعتماد
$unadopt_all_url = base_url("$MODULE_NAME/$TB_NAME/public_unadopt"); //الغاء الاعتماد بالارجاع لمرحلة الاعداد
$adopt_detail_url = base_url("$MODULE_NAME/$TB_NAME/public_get_adopt_detail"); //بيانات الاعتماد
//اعتماد المدير المالي
$ChiefFinancial = base_url("$MODULE_NAME/$TB_NAME/ChiefFinancial");
//اعتماد مدير المقر
$HeadOffice = base_url("$MODULE_NAME/$TB_NAME/HeadOffice");
//اعتماد المراقب الداخلي
$InternalObserver = base_url("$MODULE_NAME/$TB_NAME/InternalObserver");
//اعتماد المدير العام
$GeneralDirector = base_url("$MODULE_NAME/$TB_NAME/GeneralDirector");
//اعتماد المالية للصرف
$FinancialAdopt = base_url("$MODULE_NAME/$TB_NAME/FinancialToPay");
//الغاء لااعتماد
$CancelAdopt = base_url("$MODULE_NAME/$TB_NAME/Return_to_hr"); //الغاء الاعتماد والارجاع الى الشؤون الادارية
//حالة الاعتماد
$is_active = intval($is_active);
$adopt_detail_url = base_url("$MODULE_NAME/$TB_NAME/public_get_adopt_detail");
$dealyemp_index_url = base_url("$MODULE_NAME/$TB_NAME/public_dealyemp_index");
$dealyemp_list_url = base_url("$MODULE_NAME/$TB_NAME/public_get_page_dealyemp");
$get_excel_adopt_hr_url = base_url("$MODULE_NAME/$TB_NAME/excel_adopt_hr_report");
$report_url = base_url("JsperReport/showreport?sys=hr/salaries");
?>
<!-- PAGE-HEADER -->
<div class="page-header">
    <div>
        <h1 class="page-title">اعتمادات | كشف التأخير الصباحي المعتمد اداريا</h1>
    </div>
    <div class="ms-auto pageheader-btn">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">الحركات</a></li>
            <li class="breadcrumb-item active" aria-current="page">التأخير الصباحي</li>
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
                    <?php if (HaveAccess($FinancialAdopt)) { ?>
                        <a href="<?= $dealyemp_index_url ?>" class="btn btn-secondary">
                            <i class="fa fa-money"></i>
                            الكشف المعتمد مالياً
                        </a>
                    <?php } ?>
                </div>
            </div>
            <div class="card-body">
                <form id="<?= $TB_NAME ?>_form">
                    <div class="row">
                        <?php if ($this->user->branch == 1) { ?>
                            <div class="form-group col-md-2">
                                <label> المقر</label>
                                <select name="branch_no" id="dp_branch_no" class="form-control">
                                    <option value="">_______</option>
                                    <?php foreach ($branches as $row) : ?>
                                        <option <?= ($this->user->branch == $row['NO'] ? 'selected="selected"' : '') ?>
                                                value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <?php } else { ?>
                            <input type="hidden" name="branch_no" id="dp_branch_no"
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

                        <div class="form-group col-md-1">
                            <label>شهر التأخير</label>
                            <input type="text" placeholder="الشهر" name="month" id="txt_month"
                                   class="form-control"
                                   value="<?= date('Ym', strtotime('last month')) ?>">
                            <input type="hidden" id="txt_h_month" class="form-control" value="">
                        </div>


                        <div class="form-group col-md-1">
                            <label>شهر الاحتساب </label>
                            <input type="text" placeholder="شهر الاحتساب" name="month_act"
                                   id="txt_month_act" class="form-control" value="">
                        </div>


                        <div class="form-group col-md-2">
                            <label>مرحلة الاعتماد الاداري</label>
                            <select name="adopt_stage" id="dp_adopt_stage" class="form-control sel2">
                                <option value="">_______</option>
                                <?php foreach ($adopt_cons as $row) : ?>
                                    <option <?= ($is_active == $row['CON_NO'] ? 'selected="selected"' : '') ?>
                                            value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group col-md-1">
                            <label> الاعتماد المالي</label>
                            <select name="agree_fi" id="dp_agree_fi" class="form-control">
                                <option value="">_______</option>
                                <option value="1"> معتمد</option>
                                <option value="0"> غير معتمد</option>
                            </select>
                        </div>

                        <?php if ($this->user->branch == 1) { ?>
                            <div class="form-group col-md-1">
                                <label>تجميع حسب</label>
                                <div class="checkbox">
                                    <div class="custom-checkbox custom-control">
                                        <input type="checkbox" data-checkboxes="mygroup" class="custom-control-input"
                                               id="checkbox-1" name="group_by_branch" value="1">
                                        <label for="checkbox-1" class="custom-control-label mt-1">المقر</label>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

                    </div>
                    <div class="flex-shrink-0">
                        <button type="button" onclick="javascript:search();" class="btn btn-primary">
                            <i class="fa fa-search"></i> إستعلام
                        </button>

                        <?php if (HaveAccess($ChiefFinancial)) { ?>
                            <button type="button" id="ChiefFinancial"
                                    onclick="javascript:open_ChiefFinancial_1();"
                                    class="btn btn-indigo" style="display: none">
                                <i class="fa fa-check"></i>
                                اعتماد المدير المالي في المقر
                            </button>
                        <?php } ?>

                        <?php if (HaveAccess($HeadOffice)) { ?>
                            <button type="button" id="HeadOffice" onclick="javascript:open_HeadOffice_3();"
                                    class="btn btn-indigo" style="display: none">
                                <i class="fa fa-check"></i>
                                اعتماد مدير المقر
                            </button>
                        <?php } ?>

                        <?php if (HaveAccess($InternalObserver)) { ?>
                            <button type="button" id="InternalObserver"
                                    onclick="javascript:open_InternalObserver_4();"
                                    class="btn btn-indigo" style="display: none">
                                <i class="fa fa-check"></i>
                                اعتماد المراقب الداخلي
                            </button>
                        <?php } ?>

                        <?php if (HaveAccess($GeneralDirector)) { ?>
                            <button type="button" id="GeneralDirector"
                                    onclick="javascript:open_GeneralDirector_10();"
                                    class="btn btn-indigo" style="display: none">
                                <i class="fa fa-check"></i>
                                اعتماد المدير العام
                            </button>
                        <?php } ?>


                        <?php if (HaveAccess($FinancialAdopt)) { ?>
                            <button type="button" id="Financialpay" onclick="javascript:open_Financial_pay_15();"
                                    class="btn btn-indigo" style="display: none">
                                <i class="fa fa-check"></i>
                                اعتماد المالية
                            </button>
                        <?php } ?>
                        <?php if (HaveAccess($CancelAdopt)) { ?>
                            <button type="button" id="CancelAdopt" onclick="javascript:open_CancelAdopt_0();"
                                    class="btn btn-danger" style="display: none">
                                <i class="fa fa-angle-double-left"></i>
                                ارجاع الكشف للادارية
                            </button>
                        <?php } ?>

                        <button type="button" onclick="ExcelData()"
                                class="btn btn-success">
                            <i class="fa fa-file-excel-o"></i>
                            إكسل
                        </button>

                        <button type="button" onclick="javascript:print_report();"
                                class="btn btn-blue">
                            <i class="fa fa-print"></i>
                            طباعة
                        </button>

                        <button type="button" onclick="javascript:clear_form();" class="btn btn-cyan-light"><i
                                    class="fa fa-eraser"></i>تفريغ الحقول
                        </button>
                    </div>
                </form>
                <br>
                <div id="t1_container">
                    <?php /*echo modules::run($get_page, $page);*/ ?>
                </div>

            </div>
        </div>
    </div>
</div>


<!--Modal NOTE Bootstrap -1- اعتماد المدير المالي-->
<div class="modal fade" id="ChiefFinancial_note">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">اعتماد المدير المالي</h6>
                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span
                            aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <div class="row">
                        <div class="form-group  col-md-5">
                            <label> الملاحظة </label>
                            <input type="text" name="note" value="-"
                                   id="txt_note_ChiefFinancial" class="form-control">

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?php if (HaveAccess($ChiefFinancial)) { ?>
                    <button type="button" onclick="javascript:adopt(1);" class="btn btn-indigo" id="btn_click_adopt_1">
                        <i class="fa fa-check"></i>
                        اعتماد المدير المالي في المقر
                    </button>
                <?php } ?>
                <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">اغلاق</button>
            </div>
        </div>
    </div>
</div>
<!--END Modal Bootstrap -->

<!--Modal NOTE Bootstrap -3- اعتماد مدير المقر-->
<div class="modal fade" id="HeadOffice_note">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">اعتماد مدير المقر</h6>
                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span
                            aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <div class="row">
                        <div class="form-group  col-md-6">
                            <label> الملاحظة </label>
                            <input type="text" name="note" value="-" id="txt_note_HeadOffice" class="form-control"
                                   autocomplete="off">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?php if (HaveAccess($HeadOffice)) { ?>
                    <button type="button" onclick="javascript:adopt(3);" class="btn btn-indigo" id="btn_click_adopt_3">
                        <i class="fa fa-check"></i>اعتماد مدير المقر
                    </button>
                <?php } ?>
                <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">اغلاق</button>
            </div>
        </div>
    </div>
</div>
<!--END Modal Bootstrap -->


<!--Modal NOTE Bootstrap -4- اعتماد المراقب الداخلي-->
<div class="modal fade" id="InternalObserver_note">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">اعتماد المراقب الداخلي</h6>
                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span
                            aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <div class="row">
                        <div class="form-group  col-md-6">
                            <label> الملاحظة </label>
                            <input type="text" name="note" value="-" id="txt_note_InternalObserver" class="form-control"
                                   autocomplete="off">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?php if (HaveAccess($InternalObserver)) { ?>
                    <button type="button" onclick="javascript:adopt(4);" class="btn btn-indigo" id="btn_click_adopt_4">
                        <i class="fa fa-check"></i>اعتماد المراقب الداخلي
                    </button>
                <?php } ?>
                <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">اغلاق</button>
            </div>
        </div>
    </div>
</div>
<!--END Modal Bootstrap -->


<!--Modal NOTE Bootstrap -10- اعتماد المدير العام-->
<div class="modal fade" id="GeneralDirector_note">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">اعتماد المدير العام</h6>
                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span
                            aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <div class="row">
                        <div class="form-group  col-md-6">
                            <label> الملاحظة </label>
                            <input type="text" name="note" value="-" id="txt_note_GeneralDirector" class="form-control"
                                   autocomplete="off">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?php if (HaveAccess($GeneralDirector)) { ?>
                    <button type="button" onclick="javascript:adopt(10);" class="btn btn-indigo"
                            id="btn_click_adopt_10">
                        <i class="fa fa-check"></i>اعتماد المدير العام
                    </button>
                <?php } ?>
                <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">اغلاق</button>
            </div>
        </div>
    </div>
</div>
<!--END Modal Bootstrap -->


<!--Modal NOTE Bootstrap -15للصرف  اعتماد  المالية-->
<div class="modal fade" id="finical_pay_note">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">اعتماد المالية للصرف</h6>
                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span
                            aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <div class="alert alert-info" role="alert">
                        <button aria-label="Close" class="close" data-bs-dismiss="alert" type="button">
                            <span aria-hidden="true">×</span>
                        </button>
                        <strong>تنويه</strong>
                        يرجى ادخال شهر احتساب الراتب قبل الترحيل
                    </div>

                    <div class="row">
                        <div class="form-group  col-md-6">
                            <label> الملاحظة </label>
                            <input type="text" name="note" value="-" id="txt_note_finical_pay" class="form-control"
                                   autocomplete="off">
                        </div>

                        <div class="form-group  col-md-3">
                            <label> شهر احتساب الراتب </label>
                            <input type="text" name="month_act_sal" value="<?= date('Ym') ?>" id="txt_month_act_sal"
                                   class="form-control" autocomplete="off">
                        </div>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?php if (HaveAccess($FinancialAdopt)) { ?>
                    <button type="button" onclick="javascript:adopt(15);" class="btn btn-indigo"
                            id="btn_click_adopt_15">
                        <i class="fa fa-check"></i>
                        اعتماد المالية للصرف
                    </button>
                <?php } ?>
                <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">اغلاق</button>
            </div>
        </div>
    </div>
</div>
<!--END Modal Bootstrap -->


<!--Modal NOTE Bootstrap -1 الغاء الاعتماد الارجاع الى المعد-->
<div class="modal fade" id="CancelAdopt_note">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">ارجاع الكشف للشؤون الادارية لاعادة الاحتساب</h6>
                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span
                            aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">

                <div class="col-md-12">
                    <div class="alert alert-info" role="alert">
                        <button aria-label="Close" class="close" data-bs-dismiss="alert" type="button">
                            <span aria-hidden="true">×</span>
                        </button>
                        <strong>تنويه</strong>
                        عند الارجاع يتم ارجاع الكشف للشؤون الادارية لمراجعة الكشف والاحتساب مرة اخرى
                    </div>
                    <div class="row">
                        <div class="form-group  col-md-8">
                            <label> سبب الارجاع </label>
                            <input type="text" name="note" value="-" id="txt_CancelAdopt" class="form-control"
                                   autocomplete="off">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?php if (HaveAccess($CancelAdopt)) { ?>
                    <button type="button" onclick="javascript:unadopt(-1);" class="btn btn-danger"
                            id="btn_click_un_adopt"><span
                                class="fa fa-angle-double-left"></span>
                        ارجاع
                    </button>
                <?php } ?>
                <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">اغلاق</button>
            </div>
        </div>
    </div>
</div>
<!--END Modal Bootstrap -->

<!-- Modal -->
<div class="modal fade" id="DetailModal">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">بيانات الاعتماد</h6>
                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span
                            aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" id="public-modal-body">

            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">اغلاق</button>
            </div>
        </div>
    </div>
</div>


<?php
$scripts = <<<SCRIPT
<script>
 
     
    $('#txt_month,#txt_month_act,#txt_month_act_sal').datetimepicker({
            format: 'YYYYMM',
            minViewMode: 'months',
            pickTime: false,
            
    });
     
    $('.sel2:not("[id^=\'s2\']")').select2();  
        
   $('#dp_adopt_stage').select2('val','{$is_active}');
 
   function reBind(){
            ajax_pager({
                branch_no:$('#dp_branch_no').val(),month:$('#txt_month').val(),month_act:$('#txt_month_act').val(),emp_no:$('#dp_emp_no').val(),adopt_stage:$('#dp_adopt_stage').val(),agree_fi:$('#dp_agree_fi').val()
             });
        }

   function LoadingData(){
       ajax_pager_data('#page_tb > tbody',{
             branch_no:$('#dp_branch_no').val(),month:$('#txt_month').val(),month_act:$('#txt_month_act').val(),emp_no:$('#dp_emp_no').val(),adopt_stage:$('#dp_adopt_stage').val(),agree_fi:$('#dp_agree_fi').val()
       });
     }
 
   function search(){
        var month = $('#txt_month').val();
        var branch_no = $('#dp_branch_no').val();
        var adopt_stage = $('#dp_adopt_stage').val();
        $('#ChiefFinancial').hide();  //اعتماد المدير المالي للمقر 1
        $('#HeadOffice').hide(); //اعتماد مدير المقر 3
        $('#InternalObserver').hide(); //اعتماد المراقب الداخلي 4
        $('#GeneralDirector').hide(); //اعتماد المدير العام 10
        $('#Financialpay').hide(); //1اعتماد المالية للصرف 5
        $('#CancelAdopt').hide();
        $('#page_tb .checkboxes').prop("disabled", true);  
        if (month == '') {
             danger_msg('يرجى  ادخال الشهر');
             return -1;
        }else if (branch_no == ''){
              danger_msg('يرجى  ادخال المقر');
             return -1;
        }else{    
             if (adopt_stage == 0) {
                $('#ChiefFinancial').show();
            } else if (adopt_stage == 1) {
               $('#HeadOffice').show(); 
               $('#CancelAdopt').show();
            } else if (adopt_stage == 3) {
               $('#InternalObserver').show();
               $('#CancelAdopt').show();
            }else if (adopt_stage == 4 && branch_no == 1 ) {
               $('#GeneralDirector').show();
            }else if (adopt_stage == 10 && branch_no == 1 ) {
               $('#Financialpay').show();
               $('#CancelAdopt').show();
            }else if (adopt_stage == 4 && branch_no != 1 ) {
               $('#Financialpay').show();
               $('#CancelAdopt').show();
            }else if (adopt_stage == 4) {
               $('#CancelAdopt').show();
             }
             $('#txt_h_month').val(month);
             get_data('{$get_page_url}',{page: 1,
                 branch_no:$('#dp_branch_no').val(),month:$('#txt_month').val(),month_act:$('#txt_month_act').val(),emp_no:$('#dp_emp_no').val(),adopt_stage:$('#dp_adopt_stage').val(),agree_fi:$('#dp_agree_fi').val()
             },function(data){
                $('#t1_container').html(data);
                reBind();
            },'html');
        }
     } 

   function ExcelData(){
        var fewSeconds = 10;
        var branch_no = $('#dp_branch_no').val();
        var month = $('#txt_month').val();
        var month_act = $('#txt_month_act').val();
        var emp_no = $('#dp_emp_no').val();
        var adopt_stage = $('#dp_adopt_stage').val();
        var agree_fi = $('#dp_agree_fi').val();
        info_msg('تنبيه','جاري تجهيز ملف الاكسل ..'); 
            location.href = '{$get_excel_adopt_hr_url}?branch_no='+branch_no+'&month='+month+'&month_act='+month_act+'&emp_no='+emp_no+'&adopt_stage='+adopt_stage+'&agree_fi='+agree_fi;
            setTimeout(function(){
                info_msg('تنبيه','جاري تجهيز ملف الاكسل ..'); 
        }, fewSeconds*1000);
    }

   function print_report(){
        var branch_no = have_no_val($('#dp_branch_no').val());
        var month = have_no_val($('#txt_month').val());
        var month_act = have_no_val($('#txt_month_act').val());
        var emp_no = have_no_val($('#dp_emp_no').val());
        var adopt_stage = have_no_val($('#dp_adopt_stage').val());
        var agree_fi  = have_no_val($('#dp_agree_fi').val());
        var group_by_branch = have_no_val($('input[name=group_by_branch]:checked').val());
        _showReport('{$report_url}&report_type=pdf&report=attendance_delay_mng&p_branch='+branch_no+'&p_emp_no='+emp_no+'&p_month='+month+'&p_sal_month='+month_act+'&p_agree_ma='+adopt_stage+'&p_agree_fa='+agree_fi+'&p_group_by_branch='+group_by_branch+'');
       }
  
    // check if var have value or null //
   function have_no_val(v) {
             if(v == null) {
                 return v = '';
             }else {
                 return v;
             }
         }
 
  //اعتماد المدير المالي للمقر 1
  function open_ChiefFinancial_1(){
     $('#ChiefFinancial_note').modal('show');
  }
  //اعتماد مدير المقر 3
  function open_HeadOffice_3(){
      $('#HeadOffice_note').modal('show');
  }
  //اعتماد المراقب الداخلي 4
  function open_InternalObserver_4(){
        $('#InternalObserver_note').modal('show');
   }  
   //اعتماد المدير العام 10
  function open_GeneralDirector_10(){
       $('#GeneralDirector_note').modal('show');
   }  
  /***اعتماد المالية للصرف*******/
  function open_Financial_pay_15(){
       $('#finical_pay_note').modal('show');
  }
             
    //Cancel Adopt Modalالارجاع للمعد  ---//
  function open_CancelAdopt_0(){
    $('#CancelAdopt_note').modal('show');
  }

  var btn__= '';
    $('#btn_click_adopt_1,#btn_click_adopt_3,#btn_click_adopt_4,#btn_click_adopt_10,#btn_click_adopt_15').click( function(){
       btn__ = $(this);
  });         
 
        
  function adopt(no){
           var month = $('#txt_month').val();
           var branch_no = $('#dp_branch_no').val();
           var month_act_sal = $('#txt_month_act_sal').val(); //شهر احتساب الراتب في المالية
           var action_desc= 'اعتماد';
           var val = [];
           if (no == 1) {
                 var note = $('#txt_note_ChiefFinancial').val();
            }else if (no == 3) {
               var note = $('#txt_note_HeadOffice').val();
            }else if (no == 4) {
               var note = $('#txt_note_InternalObserver').val();
            }else if (no == 10) {
               var note = $('#txt_note_GeneralDirector').val();
            } else if (no == 15 && month_act_sal == '') {
                var note = $('#txt_note_finical_pay').val();
                warning_msg('يرجى ادخال شهر احتساب الراتب');
               return  -1;
            } else { 
                 var note = '';
            }
            $('#page_tb .checkboxes:checked').each(function(i){
                val[i] = $(this).val();
            });
            if(val.length > 0){
                if(confirm('هل تريد بالتأكيد '+action_desc+' '+val.length+' بنود')){
                    get_data('{$adopt_all_url}', {pp_ser:val,is_active:no,month_act_sal:month_act_sal,note:note} , function(ret){
                     if(ret >= 1){
                         success_msg('رسالة','تمت عملية الاعتماد بنجاح ');
                         $('#btn_click_adopt_'+no+'').attr('disabled','disabled');
                         if (no == 4 && branch_no == 1){
                            var sub= 'اعتماد كشف التأخير الصباحي ';
                            var text= 'يرجى اعتماد كشف التأخير الصباحي لشهر ';
                            text+= '<br>'+month+'';
                            text+= '<br>للاطلاع افتح الرابط';
                            text+= '<br> {$gfc_domain}{$index_url}';
                            _send_mail(btn__,'telbawab@gedco.ps',sub,text)
                            btn__ = '';
                         }
                        
                    }else{
                        danger_msg('تحذير..',ret);
                    }
               
                   }, 'html');
                }
          }else{
              danger_msg('يرجى تحديد السجلات');
              return -1;
        }
      }  

  function unadopt(no){
           var action_desc= 'ارجاع الكشف للشؤون الادارية لاعادة احتساب الخصم الاداري';
           var val = [];
           var note = $('#txt_CancelAdopt').val();
           if (note == '') {
                    danger_msg('يرجى ادخال سبب الارجاع');
                    return  -1;
            }else {
            $('#page_tb .checkboxes:checked').each(function(i){
                val[i] = $(this).val();
            });
            if(val.length > 0){
                if(confirm('هل تريد بالتأكيد '+action_desc+' '+val.length+' بنود')){
                 get_data('{$unadopt_all_url}', {pp_ser:val,is_active:no,note:note} , function(ret){
                         if(ret >= 1){
                            success_msg('رسالة','تمت عملية الارجاع للمعد بنجاح ');
                            $('#btn_click_un_adopt').attr('disabled','disabled');
                            $('.modal').hide();
                            $('.modal-backdrop').hide();
                            search();         
                        }else{
                            danger_msg('تحذير..',ret);
                            return -1;
                        }
                   
                }, 'html');
                
                }
            }else{
                  danger_msg('يرجى تحديد السجلات');
                  return -1;
            }
           } //end else if 
  } //end unadopt
         
  function show_detail_adopt(emp_no,month){
             showLoading();
             // Display Modal
            $('#DetailModal').modal('show');
            $.ajax({
                url: '{$adopt_detail_url}',
                type: 'post',
                data: {emp_no: emp_no,month:month},
                success: function(response){
                    // Add response in Modal body
                    $('#public-modal-body').html(response);
                },
                complete: function() {
                    HideLoading();
                }
            });
   }
       
  function clear_form(){
       $('#txt_month').val('');
       $('#txt_month_act').val();
       $('.sel2').select2('val',0);
  }
   
      
</script>
SCRIPT;
sec_scripts($scripts);
?>



