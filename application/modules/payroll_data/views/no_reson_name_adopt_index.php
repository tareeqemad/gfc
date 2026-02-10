<?php

/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 25/07/22
 * Time: 08:58 ص
 */
$MODULE_NAME = 'payroll_data';
$TB_NAME = 'no_reson_name_adopt';
$gfc_domain = gh_gfc_domain();
$index_url = base_url("$MODULE_NAME/$TB_NAME/index");
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");

$adopt_all_url = base_url("$MODULE_NAME/$TB_NAME/public_adopt");
$unadopt_all_url = base_url("$MODULE_NAME/$TB_NAME/public_unadopt");
$adopt_detail_url = base_url("$MODULE_NAME/$TB_NAME/public_get_adopt_detail");

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
$CancelAdopt = base_url("$MODULE_NAME/$TB_NAME/ret_to_hr");
//حالة الاعتماد
$post = intval($post);
$get_excel_url = base_url("$MODULE_NAME/$TB_NAME/excel_report");
$report_url = base_url("JsperReport/showreport?sys=hr/salaries");
?>
<!-- PAGE-HEADER -->
<div class="page-header">
    <div>
        <h1 class="page-title">كشف الغير ملتزمين بالانصراف | المعتمد ادارياً</h1>
    </div>
    <div class="ms-auto pageheader-btn">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">الرواتب</a></li>
            <li class="breadcrumb-item active" aria-current="page">الغير ملتزمين بالانصراف</li>
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

                </div>
            </div>
            <div class="card-body">
                <form id="<?= $TB_NAME ?>_form">
                    <div class="row">
                        <?php if ($this->user->branch == 1) { ?>
                            <div class="form-group col-md-2">
                                <label> المقر</label>
                                <select name="branch_no" id="dp_branch_no" class="form-control sel2">
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
                        <input type="hidden" name="h_branch_no" id="h_branch_no" value="">

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
                            <label>شهر عدم الالتزام </label>
                            <input type="text" placeholder="الشهر" name="the_month" id="txt_the_month"
                                   class="form-control"
                                   value="<?= date('Ym', strtotime('last month')) ?>">
                        </div>


                        <div class="form-group col-md-1">
                            <label>شهر الاحتساب </label>
                            <input type="text" placeholder="شهر الاحتساب " name="month_sal"
                                   id="txt_month_sal" class="form-control" value="">
                        </div>

                        <div class="form-group col-md-2">
                            <label>مرحلة الاعتماد الاداري</label>
                            <select name="post" id="dp_post" class="form-control">
                                <option value="">_______</option>
                                <?php foreach ($adopt_cons as $row) : ?>
                                    <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <?php if ($this->user->branch == 1) { ?>
                            <div class="form-group col-md-3">
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
                                    onclick="javascript:open_ChiefFinancial_11();"
                                    class="btn btn-indigo" style="display: none">
                                <i class="fa fa-check"></i>
                                اعتماد المدير المالي في المقر
                            </button>
                        <?php } ?>

                        <?php if (HaveAccess($HeadOffice)) { ?>
                            <button type="button" id="HeadOffice" onclick="javascript:open_HeadOffice_13();"
                                    class="btn btn-indigo" style="display: none">
                                <i class="fa fa-check"></i>
                                اعتماد مدير المقر
                            </button>
                        <?php } ?>
                        <?php if (HaveAccess($InternalObserver)) { ?>
                            <button type="button" id="InternalObserver"
                                    onclick="javascript:open_InternalObserver_14();"
                                    class="btn btn-indigo" style="display: none">
                                <i class="fa fa-check"></i>
                                اعتماد المراقب الداخلي
                            </button>
                        <?php } ?>
                        <?php if (HaveAccess($GeneralDirector)) { ?>
                            <button type="button" id="GeneralDirector"
                                    onclick="javascript:open_GeneralDirector_15();"
                                    class="btn btn-indigo" style="display: none">
                                <i class="fa fa-check"></i>
                                اعتماد المدير العام
                            </button>
                        <?php } ?>
                        <?php if (HaveAccess($FinancialAdopt)) { ?>
                            <button type="button" id="Financialpay" onclick="javascript:open_Financial_pay_20();"
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
                <div id="container">
                    <?php /*echo modules::run($get_page, $page);*/ ?>
                </div>

            </div>
        </div>
    </div>
</div>


<!--Modal NOTE Bootstrap -11- اعتماد المدير المالي-->
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
                        <div class="form-group  col-md-6">
                            <label> الملاحظة </label>
                            <input type="text" name="note" value="-" id="txt_note_ChiefFinancial" class="form-control"
                                   autocomplete="off">

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?php if (HaveAccess($ChiefFinancial)) { ?>
                    <button type="button" onclick="javascript:adopt(11);" id="btn_click_adopt_11"
                            class="btn btn-indigo">
                        <i class="fa fa-check"></i>
                        اعتماد المدير المالي والاداري في المقر
                    </button>
                <?php } ?>
                <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">اغلاق</button>
            </div>
        </div>
    </div>
</div>
<!--END Modal Bootstrap -->


<!--Modal NOTE Bootstrap -13- اعتماد مدير المقر-->
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
                    <button type="button" onclick="javascript:adopt(13);" id="btn_click_adopt_13"
                            class="btn btn-indigo">
                        <i class="fa fa-check"></i>اعتماد مدير المقر
                    </button>
                <?php } ?>
                <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">اغلاق</button>
            </div>
        </div>
    </div>
</div>
<!--END Modal Bootstrap -->


<!--Modal NOTE Bootstrap -14- اعتماد المراقب الداخلي-->
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
                    <button type="button" onclick="javascript:adopt(14);" id="btn_click_adopt_14"
                            class="btn btn-indigo">
                        <i class="fa fa-check"></i>اعتماد المراقب الداخلي
                    </button>
                <?php } ?>
                <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">اغلاق</button>
            </div>
        </div>
    </div>
</div>
<!--END Modal Bootstrap -->


<!--Modal NOTE Bootstrap -15- اعتماد المدير العام-->
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
                    <button type="button" onclick="javascript:adopt(15);" id="btn_click_adopt_15"
                            class="btn btn-indigo">
                        <i class="fa fa-check"></i>اعتماد المدير العام
                    </button>
                <?php } ?>
                <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">اغلاق</button>
            </div>
        </div>
    </div>
</div>
<!--END Modal Bootstrap -->

<!--Modal NOTE Bootstrap -20 للصرف  اعتماد  المالية-->
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
                            <input type="text" name="m_month_sal" value="<?= date('Ym') ?>" id="txt_m_month_sal"
                                   class="form-control" autocomplete="off">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?php if (HaveAccess($FinancialAdopt)) { ?>
                    <button type="button" onclick="javascript:adopt(20);" id="btn_click_adopt_20"
                            class="btn btn-indigo">
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


<!--Modal NOTE Bootstrap  الغاء الاعتماد الارجاع الى المعد-->
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
                    <div class="row">
                        <div class="form-group  col-md-6">
                            <label> سبب الارجاع </label>
                            <input type="text" name="note" value="-" id="txt_CancelAdopt" class="form-control"
                                   autocomplete="off">

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?php if (HaveAccess($CancelAdopt)) { ?>
                    <button type="button" onclick="javascript:unadopt(0);" class="btn btn-danger"
                            id="btn_un_adopt"><span
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

      var table = '{$TB_NAME}';
      var count = 0;
      
           
     $('#txt_the_month,#txt_month_sal,#txt_m_month_sal').datetimepicker({
            format: 'YYYYMM',
            minViewMode: 'months',
            pickTime: false,
            
    });
  
    $('.sel2:not("[id^=\'s2\']")').select2();  
    
    $("#dp_post").select2();
    $("#dp_post").val("{$post}").trigger("change");
    $('#page_tb .checkboxes').prop("disabled", true);
    

    function reBind(){
        ajax_pager({
            branch_no:$('#dp_branch_no').val(),emp_no:$('#dp_emp_no').val(),the_month:$('#txt_the_month').val(),month_sal:$('#txt_month_sal').val(),post:$('#dp_post').val()
         });
    }
 
    function LoadingData(){
       ajax_pager_data('#page_tb > tbody',{
          branch_no:$('#dp_branch_no').val(),emp_no:$('#dp_emp_no').val(),the_month:$('#txt_the_month').val(),month_sal:$('#txt_month_sal').val(),post:$('#dp_post').val()
       });
     }


      function search(){
        var branch_no = $('#dp_branch_no').val();
        var month = $('#txt_the_month').val();
        var post = $('#dp_post').val();
        $('#ChiefFinancial').hide();  //اعتماد المدير المالي للمقر 11
        $('#HeadOffice').hide(); //اعتماد مدير المقر 13
        $('#InternalObserver').hide(); //اعتماد المراقب الداخلي 14
        $('#GeneralDirector').hide(); // اعتماد المدير العام 15
        $('#Financialpay').hide(); //اعتماد المالية للصرف 20
        $('#CancelAdopt').hide();
        $('#page_tb .checkboxes').prop("disabled", true); 
        if (month == '') {
             danger_msg('يرجى  ادخال الشهر');
             return -1;
        }else{
             if (post == 10) {
                  $('#ChiefFinancial').show();
                  $('#CancelAdopt').show();
            } else if (post == 11) {
                 $('#HeadOffice').show();
                 $('#CancelAdopt').show();
            } else if (post == 13) {
                 $('#InternalObserver').show();
                 $('#CancelAdopt').show();
            }else if (post == 14 && branch_no == 1) {
               $('#GeneralDirector').show();
           }else if (post == 14 && branch_no != 1) {
               $('#Financialpay').show();
               $('#CancelAdopt').show();
           }else if (post == 15 && branch_no == 1) {
               $('#Financialpay').show();
               $('#CancelAdopt').show();
           }else if (post == 20) {
               $('#CancelAdopt').show();
            }
             $('#h_branch_no').val(branch_no);
             get_data('{$get_page_url}',{page: 1,
                 branch_no:$('#dp_branch_no').val(),emp_no:$('#dp_emp_no').val(),the_month:$('#txt_the_month').val(),month_sal:$('#txt_month_sal').val(),post:$('#dp_post').val()
             },function(data){
                $('#container').html(data);
                reBind();
            },'html');
        }
     } 


      function ExcelData(){
        var fewSeconds = 10;
        var branch_no = $('#dp_branch_no').val();
        var emp_no = $('#dp_emp_no').val();
        var the_month = $('#txt_the_month').val();
        var month_sal = $('#txt_month_sal').val();
        var post = $('#dp_post').val();
        info_msg('تنبيه','جاري تجهيز ملف الاكسل ..'); 
            location.href = '{$get_excel_url}?branch_no='+branch_no+'&emp_no='+emp_no+'&the_month='+the_month+'&month_sal='+month_sal+'&post='+post;
            setTimeout(function(){
                info_msg('تنبيه','جاري تجهيز ملف الاكسل ..'); 
        }, fewSeconds*1000);
    }
    
    
    function print_report(){
        var branch_no = have_no_val($('#dp_branch_no').val());
        var emp_no = have_no_val($('#dp_emp_no').val());
        var the_month = have_no_val($('#txt_the_month').val());
        var month_sal = have_no_val($('#txt_month_sal').val());
        var post = have_no_val($('#dp_post').val());
        var group_by_branch = have_no_val($('input[name=group_by_branch]:checked').val());
        _showReport('{$report_url}&report_type=pdf&report=no_leave_fp&p_branch='+branch_no+'&p_emp_no='+emp_no+'&p_month='+the_month+'&p_sal_month='+month_sal+'&p_agree_ma='+post+'&p_group_by_branch='+group_by_branch+'');
    }
    
     // check if var have value or null //
    function have_no_val(v) {
        if(v == null) {
            return v = '';
        }else {
            return v;
        }
    }
      
 
         //اعتماد المدير المالي للمقر 11
     function open_ChiefFinancial_11(){
         $('#ChiefFinancial_note').modal('show');
     }
 
       //اعتماد مدير المقر 13
     function open_HeadOffice_13(){
        $('#HeadOffice_note').modal('show');
    }
     
          //اعتماد المراقب الداخلي 14
     function open_InternalObserver_14(){
        $('#InternalObserver_note').modal('show');
    }
    
         //اعتماد المدير العام
     function open_GeneralDirector_15(){
        $('#GeneralDirector_note').modal('show');
     }
       
       //اعتماد المالية للصرف 20
     function open_Financial_pay_20(){
        $('#finical_pay_note').modal('show');
     }
       
           
      //Cancel Adopt Modalالارجاع للمعد  ---//
    function open_CancelAdopt_0(){
        $('#CancelAdopt_note').modal('show');
    }
       
     var btn__= '';
     $('#btn_click_adopt_11,#btn_click_adopt_13,#btn_click_adopt_14,#btn_click_adopt_15,#btn_click_adopt_20').click( function(){
        btn__ = $(this);
     });   
      
    function adopt(no){
         var branch_no = $('#dp_branch_no').val();
         var month = $('#txt_the_month').val();
         var month_sal = $('#txt_m_month_sal').val();
         var action_desc= 'اعتماد';
         var val = [];
         if (no == 11) {
                 var note = $('#txt_note_ChiefFinancial').val();
            } else if (no == 13) {
             var note = $('#txt_note_HeadOffice').val();
            } else if (no == 14) {
                var note = $('#txt_note_InternalObserver').val();
            } else if (no == 15) {
                var note = $('#txt_note_GeneralDirector').val();
            }else if (no == 20 && month_sal == '') {
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
              get_data('{$adopt_all_url}', {pp_ser:val,post:no,month_sal:month_sal,note:note} , function(ret){
                     if(ret >= 1){
                        success_msg('رسالة','تمت عملية الاعتماد بنجاح ');
                        $('#btn_click_adopt_'+no+'').attr('disabled','disabled');
                         if (no == 14 && branch_no == 1){
                            var sub= 'اعتمادكشف الغير ملتزمين بالانصراف ';
                            var text= 'يرجى اعتماد كشف الغير ملتزمين بالانصراف لشهر';
                            text+= '<br>'+month+'';
                            text+= '<br>للاطلاع افتح الرابط';
                            text+= '<br> {$gfc_domain}{$index_url}';
                            _send_mail(btn__,'telbawab@gedco.ps',sub,text)
                            btn__ = '';
                         }
                        reload_Page();     
                        search();                        
                    }else{
                        danger_msg('تحذير..',ret);
                        $('#btn_click_adopt_'+no+'').prop('disabled', false); // <-- Enable btn here
                    }
              }, 'html');
            }
          }else{
              danger_msg('يرجى تحديد السجلات');
              return -1;
         }
    }  
       
     
    function unadopt(no){
       var action_desc= 'ارجاع للمعد';
       var val = [];
       var note = $('#txt_CancelAdopt').val();
       var branch_no = $('#h_branch_no').val();
       if (note == '') {
                danger_msg('يرجى ادخال سبب الارجاع');
                return  -1;
        }else {
        $('#page_tb .checkboxes:checked').each(function(i){
            val[i] = $(this).val();
        });
          if(val.length > 0){
            if(confirm('هل تريد بالتأكيد '+action_desc+' '+val.length+' بنود')){
                 $('#btn_un_adopt').prop('disabled', true); // <-- Disable here
             get_data('{$unadopt_all_url}',{pp_ser:val,post:no,branch_no:branch_no,note:note} , function(ret){
                     if(ret >= 1){
                        success_msg('رسالة','تمت عملية الارجاع للمعد بنجاح ');
                        reload_Page();
                        search();
                    }else{
                        danger_msg('تحذير..',ret);
                        $('#btn_un_adopt').prop('disabled', false); // <-- Enable btn here
                        return -1;
                    }
               
             }, 'html');
          }//end if confirm
        }else{
              danger_msg('يرجى تحديد السجلات');
              return -1;
        }
       } //end else if 
     } //end unadopt
    
      
    function show_detail_adopt(p_ser){
         showLoading();
           // Display Modal
        $('#DetailModal').modal('show');
        $.ajax({
            url: '{$adopt_detail_url}',
            type: 'post',
            data: {p_ser: p_ser},
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
       clearForm($('#{$TB_NAME}_form'));
       $('.sel2').select2('val',0);
    }
   
      
</script>
SCRIPT;
sec_scripts($scripts);
?>



