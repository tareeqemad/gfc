<?php
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 26/02/23
 * Time: 08:30 ص
 */

$MODULE_NAME = 'salary';
$TB_NAME = 'Contact_allowance_adopt';
$gfc_domain= gh_gfc_domain();
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
//اعتماد المالية للصرف
$FinancialAdopt = base_url("$MODULE_NAME/$TB_NAME/FinancialToPay");
//الغاء الاعتماد
$CancelAdopt = base_url("$MODULE_NAME/$TB_NAME/CancelAdopt");
//حالة الاعتماد
$agree_ma = intval($agree_ma);
//عرض السجلات المتكررة
$view_recurring_records_url = base_url("$MODULE_NAME/$TB_NAME/public_view_recurring_records");

$get_excel_url = base_url("$MODULE_NAME/$TB_NAME/excel_report");
$report_url = base_url("JsperReport/showreport?sys=hr/salaries");
?>
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="left-content">
        <span class="main-content-title mg-b-0 mg-b-lg-1">اعتماد | كشف بدل الاتصال</span>
    </div>
    <div class="justify-content-center mt-2">
        <ol class="breadcrumb">
            <li class="breadcrumb-item tx-15"><a href="javascript:void(0);">الرواتب</a></li>
            <li class="breadcrumb-item active" aria-current="page">كشف بدل الاتصال</li>
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
                        <?php if ($this->user->branch == 1) { ?>
                            <div class="form-group col-md-2">
                                <label> المقر</label>
                                <select name="branch_no" id="dp_branch_no" class="form-control sel2">
                                    <option value="">_______</option>
                                    <?php foreach ($branches as $row) : ?>
                                        <option <?= ($this->user->branch == $row['NO'] ? 'selected="selected"' : '') ?> value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <?php } else { ?>
                            <input type="hidden" name="branch_no" id="dp_branch_no" value="<?= $this->user->branch ?>">
                        <?php } ?>

                        <div class="form-group col-md-2">
                            <label>الشهر</label>
                            <input type="text" placeholder="الشهر" name="month" id="txt_month" class="form-control" value="<?= date('Ym') ?>">
                        </div>

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
                            <label>المسمى الوظيفي</label>
                            <select name="w_no" id="dp_w_no" class="form-control sel2">
                                <option value="">_________</option>
                                <?php foreach ($w_no_admin_cons as $row) : ?>
                                    <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group col-md-1">
                            <label for="dl_op">النسبة</label>
                            <select class="form-control" id="dl_op" name="dl_op">
                                <option value="">---اختر---</option>
                                <option value=">=">>=</option>
                                <option value="<="><=</option>
                                <option value="=">=</option>
                            </select>
                        </div>

                        <div class="form-group col-md-2">
                            <label for="txt_value_ma">المبلغ المستحق</label>
                            <input type="text" class="form-control" id="txt_value_ma" name="txt_value_ma"/>
                        </div>

                        <div class="form-group col-md-2">
                            <label>مرحلة الاعتماد</label>
                            <select name="adopt_stage" id="dp_adopt_stage" class="form-control sel2">
                                <option value="">---اختر---</option>
                                <?php foreach ($adopt_cons as $row) : ?>
                                    <option <?= ($agree_ma == $row['CON_NO'] ? 'selected="selected"' : '') ?>
                                        value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group col-md-2">
                            <label>نوع الرصيد</label>
                            <select class="form-control sel2" name="status_type" id="dl_status_type">
                                <option value="">--اختر-----</option>
                                <?php foreach ($status_type as $row) { ?>
                                    <option  value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php } ?>
                            </select>
                        </div>

                    </div>
                    <hr>
                    <div class="flex-shrink-0">

                        <button type="button" onclick="javascript:search();" class="btn btn-primary">
                            <i class="fa fa-search"></i> إستعلام
                        </button>

                        <?php if (HaveAccess($ChiefFinancial)) { ?>
                        <button type="button" id="ChiefFinancial" onclick="javascript:open_ChiefFinancial_20();"
                                class="btn btn-indigo" style="display: none">
                            <i class="fa fa-check"></i>
                            اعتماد المدير المالي والاداري
                        </button>
                        <?php } ?>

                        <?php if (HaveAccess($HeadOffice)) { ?>
                            <button type="button" id="HeadOffice" onclick="javascript:open_HeadOffice_30();"
                                    class="btn btn-indigo" style="display: none">
                                <i class="fa fa-check"></i>
                                اعتماد مدير المقر
                            </button>
                        <?php } ?>

                        <?php if (HaveAccess($InternalObserver)) { ?>
                            <button type="button" id="InternalObserver" onclick="javascript:open_InternalObserver_40();"
                                    class="btn btn-indigo" style="display: none">
                                <i class="fa fa-check"></i>
                                اعتماد المدقق (الرقابة)
                            </button>
                        <?php } ?>

                        <?php if (HaveAccess($FinancialAdopt)) { ?>
                            <button type="button" id="Financialpay" onclick="javascript:open_Financial_pay_50();"
                                    class="btn btn-indigo" style="display: none">
                                <i class="fa fa-check"></i>
                                اعتماد الادارة المالية
                            </button>
                        <?php } ?>

                        <?php if (HaveAccess($CancelAdopt)) { ?>
                            <button type="button" id="CancelAdopt" onclick="javascript:open_CancelAdopt_1();"
                                    class="btn btn-danger" style="display: none">
                                <i class="fa fa-angle-double-left"></i>
                                ارجاع الكشف لقسم اللوازم والخدمات
                            </button>
                        <?php } ?>

                        <button type="button" onclick="ExcelData()" class="btn btn-success"><i class="fas fa-file-excel"></i>إكسل</button>
                        <button type="button" onclick="javascript:show_all_recurring_records();" id="btn_recurring_records" class="btn btn-danger"><i class="fa fa-rss"></i>عرض السجلات المتكررة</button>
                        <button type="button" onclick="javascript:print_report();" class="btn btn-blue"><i class="fa fa-print"></i>طباعة</button>
                        <button type="button" onclick="javascript:clear_form();" class="btn btn-cyan-light"><i class="fas fa-eraser"></i>تفريغ الحقول</button>

                    </div>
                </form>
                <br>
                <div id="container">
                </div>
            </div>
        </div>
    </div>
</div>

<!--Modal NOTE Bootstrap -10- اعتماد المدير المالي-->
<div class="modal fade" id="ChiefFinancial_note">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">اعتماد المدير المالي والاداري</h6>
                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <div class="row">
                        <div class="form-group  col-md-8">
                            <label> الملاحظة </label>
                            <input type="text" name="note" value="-" id="txt_note_ChiefFinancial" class="form-control" autocomplete="off">
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <?php if (HaveAccess($ChiefFinancial)) { ?>
                    <button type="button" onclick="javascript:adopt(20);" class="btn btn-indigo" id="btn_click_adopt_20"><i class="fa fa-check"></i>اعتماد المدير المالي والادراي في المقر</button>
                <?php } ?>
                <button class="btn ripple btn-secondary" data-bs-dismiss="modal" type="button">اغلاق</button>
            </div>
        </div>
    </div>
</div>
<!--END Modal Bootstrap -->

<!--Modal NOTE Bootstrap -30- اعتماد مدير المقر-->
<div class="modal fade" id="HeadOffice_note">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">اعتماد مدير المقر</h6>
                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <div class="row">
                        <div class="form-group  col-md-6">
                            <label> الملاحظة </label>
                            <input type="text" name="note" value="-" id="txt_note_HeadOffice" class="form-control" autocomplete="off">
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <?php if (HaveAccess($HeadOffice)) { ?>
                    <button type="button" onclick="javascript:adopt(30);" class="btn btn-indigo" id="btn_click_adopt_30"><i class="fa fa-check"></i>اعتماد مدير المقر</button>
                <?php } ?>
                <button class="btn ripple btn-secondary" data-bs-dismiss="modal" type="button">اغلاق</button>
            </div>
        </div>
    </div>
</div>
<!--END Modal Bootstrap -->

<!--Modal NOTE Bootstrap -31- اعتماد المراقب الداخلي-->
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
                            <input type="text" name="note" value="-" id="txt_note_InternalObserver" class="form-control" autocomplete="off">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?php if (HaveAccess($InternalObserver)) { ?>
                    <button type="button" onclick="javascript:adopt(40);" class="btn btn-indigo" id="btn_click_adopt_40"><i class="fa fa-check"></i>اعتماد المراقب الداخلي</button>
                <?php } ?>
                <button class="btn ripple btn-secondary" data-bs-dismiss="modal" type="button">اغلاق</button>
            </div>
        </div>
    </div>
</div>
<!--END Modal Bootstrap -->

<!--Modal NOTE Bootstrap -35للصرف  اعتماد  المالية-->
<div class="modal fade" id="finical_pay_note">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">اعتماد المالية للصرف</h6>
                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <div class="row">
                        <div class="form-group  col-md-6">
                            <label> الملاحظة </label>
                            <input type="text" name="note" value="-" id="txt_note_finical_pay" class="form-control" autocomplete="off">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?php if (HaveAccess($FinancialAdopt)) { ?>
                    <button type="button" onclick="javascript:adopt(50);" class="btn btn-indigo" id="btn_click_adopt_50"><i class="fa fa-check"></i>اعتماد المالية</button>
                <?php } ?>
                <button class="btn ripple btn-secondary" data-bs-dismiss="modal" type="button">اغلاق</button>
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
                <h6 class="modal-title">ارجاع الكشف لقسم اللوازم والخدمات</h6>
                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <div class="row">
                        <div class="form-group  col-md-6">
                            <label> سبب الارجاع </label>
                            <input type="text" name="note" value="-" id="txt_CancelAdopt" class="form-control" autocomplete="off">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?php if (HaveAccess($CancelAdopt)) { ?>
                    <button type="button" onclick="javascript:unadopt(1);" class="btn btn-danger" id="btn_cancel_adopt"><span class="fa fa-angle-double-left"></span>ارجاع</button>
                <?php } ?>
                <button class="btn ripple btn-secondary" data-bs-dismiss="modal" type="button">اغلاق</button>
            </div>
        </div>
    </div>
</div>
<!--END Modal Bootstrap -->

<!-- DetailModal -->
<div class="modal fade" id="DetailModal">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">بيانات الاعتماد</h6>
                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" id="public-modal-body">
            </div>
            <div class="modal-footer">
                <button class="btn ripple btn-secondary" data-bs-dismiss="modal" type="button">اغلاق</button>
            </div>
        </div>
    </div>
</div>
<!-- recurring_records Modal -->
<div class="modal fade" id="recurring_records_modal">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">بيانات التكرار</h6>
                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="recurring_body">

            </div>
        </div>
    </div>
</div>
<?php
$scripts = <<<SCRIPT
<script type="text/javascript">
 
    var table = '{$TB_NAME}';
    var count = 0;
    
    $('#txt_month').datetimepicker({
        format: 'YYYYMM',
        minViewMode: 'months',
        pickTime: false,
    });
     
    $('.sel2:not("[id^=\'s2\']")').select2();  
    
    $('#page_tb .checkboxes').prop("disabled", true);

    $(function(){
        reBind();
    });

    function reBind(){
        ajax_pager({
            branch_no:$('#dp_branch_no').val(),month:$('#txt_month').val(),emp_no:$('#dp_emp_no').val(),w_no:$('#dp_w_no').val(),
            agree_ma:$('#dp_adopt_stage').val(),op:$('#dl_op').val(),value_ma:$('#txt_value_ma').val() ,status_type:$('#dl_status_type').val()
        });
    }
    function LoadingData(){
            ajax_pager_data('#page_tb > tbody',{
            branch_no:$('#dp_branch_no').val(),month:$('#txt_month').val(),emp_no:$('#dp_emp_no').val(),w_no:$('#dp_w_no').val(),
            agree_ma:$('#dp_adopt_stage').val(),op:$('#dl_op').val(),value_ma:$('#txt_value_ma').val(),status_type:$('#dl_status_type').val()
        });
    }

    function search(){
        var month = $('#txt_month').val();
        var branch_no = $('#dp_branch_no').val();
        var adopt_stage = $('#dp_adopt_stage').val();
        $('#ChiefFinancial').hide();  //اعتماد المدير المالي والاداري للمقر 20
        $('#HeadOffice').hide(); //اعتماد مدير المقر 30
        $('#InternalObserver').hide(); //اعتماد المراقب الداخلي 40
        $('#Financialpay').hide(); //اعتماد المالية للصرف 50
        $('#CancelAdopt').hide(); // الارجاع
        $('#page_tb .checkboxes').prop("disabled", true);  
        if (month == '') {
            danger_msg('يرجى  ادخال الشهر');
            return -1;
        }else if (branch_no == ''){
            danger_msg('يرجى  ادخال المقر');
            return -1;
        }else if($('#txt_value_ma').val() != '' && $('#dl_op').val() == '' ){
            danger_msg('رسالة','يجب تحديد النسبة ..');
            return -1;
        }else{
            if (adopt_stage == 10) {
                $('#ChiefFinancial').show();
                $('#CancelAdopt').show();
            } else if (adopt_stage == 20) {
                $('#HeadOffice').show();
                $('#CancelAdopt').show();
            } else if (adopt_stage == 30) {
                $('#InternalObserver').show();  
                $('#CancelAdopt').show();
            } else if (adopt_stage == 40) {
                $('#Financialpay').show();  
                $('#CancelAdopt').show();
            } else if (adopt_stage == 50) {
                $('#CancelAdopt').show();
            }
        get_data('{$get_page_url}',{page: 1,
            branch_no:$('#dp_branch_no').val(),month:$('#txt_month').val(),emp_no:$('#dp_emp_no').val(),w_no:$('#dp_w_no').val(),
            agree_ma:$('#dp_adopt_stage').val(),op:$('#dl_op').val(),value_ma:$('#txt_value_ma').val(),status_type:$('#dl_status_type').val()
        },function(data){
            $('#container').html(data);
            reBind();
        },'html');
        }//end if else 
    } //end search

   function ExcelData(){
        var fewSeconds = 10;
        var branch_no = $('#dp_branch_no').val();
        var month = $('#txt_month').val();
        var emp_no = $('#dp_emp_no').val();
        var w_no = $('#dp_w_no').val();
        var agree_ma = $('#dp_adopt_stage').val();
        var status_type = $('#dl_status_type').val();
        var op = $('#dl_op').val();
        var value_ma = $('#txt_value_ma').val();
        
        info_msg('تنبيه','جاري تجهيز ملف الاكسل ..'); 
        location.href = '{$get_excel_url}?branch_no='+branch_no+'&month='+month+'&emp_no='+emp_no+'&w_no='+w_no+'&w_no='+w_no+'&agree_ma='+agree_ma+'&op='+op+'&value_ma='+value_ma+'&status_type='+status_type;
            setTimeout(function(){
                info_msg('تنبيه','جاري تجهيز ملف الاكسل ..'); 
        }, fewSeconds*1000);
   }
       
    //اعتماد المدير المالي والادراي للمقر 20
    function open_ChiefFinancial_20(){
        $('#ChiefFinancial_note').modal('show');
    }
    //اعتماد مدير المقر 30
    function open_HeadOffice_30(){
        $('#HeadOffice_note').modal('show');
    }
    //40 اعتماد المراقب الداخلي
    function open_InternalObserver_40(){
        $('#InternalObserver_note').modal('show');
    }
    
    //اعتماد المالية للصرف 50
    function open_Financial_pay_50(){
        $('#finical_pay_note').modal('show');
    }
    //Cancel Adopt Modal الارجاع  لقسم اللوازم والخدمات   ---//
    function open_CancelAdopt_1(){
        $('#CancelAdopt_note').modal('show');
    }

    var btn__= '';
        $('#btn_click_adopt_20,#btn_click_adopt_30,#btn_click_adopt_40,#btn_click_adopt_50').click( function(){
        btn__ = $(this);
    });   
        
    function adopt(no){
        var action_desc= 'اعتماد';
        var val = [];
        
        if (no == 20) {
            var note = $('#txt_note_ChiefFinancial').val();
        } else if (no == 30) {
            var note = $('#txt_note_HeadOffice').val();
        }else if (no == 40){
            var note = $('#txt_note_InternalObserver').val();
        }else if (no == 50) {
            var note = $('#txt_note_finical_pay').val();
        } else {
            var note = '';
        }
        
        $('#page_tb .checkboxes:checked').each(function(i){
            val[i] = $(this).val();
        });
        
        if(val.length > 0){
            if(confirm('هل تريد بالتأكيد '+action_desc+' '+val.length+' بنود')){
                get_data('{$adopt_all_url}', {pp_ser:val,agree_ma:no,note:note} , function(ret){
                    if(ret==1){
                        success_msg('رسالة','تمت عملية الاعتماد بنجاح ');
                        $('#btn_click_adopt_'+no+'').attr('disabled','disabled');
                        reload_Page();                        
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
        var action_desc= 'ارجاع لقسم اللوازم والخدمات';
        var val = [];
        var note = $('#txt_CancelAdopt').val();
        if (note == '') {
            danger_msg('يرجى ادخال سبب الارجاع');
        }else {
            $('#page_tb .checkboxes:checked').each(function(i){
                val[i] = $(this).val();
            });
            if(val.length > 0){
                if(confirm('هل تريد بالتأكيد '+action_desc+' '+val.length+' بنود')){
                    get_data('{$unadopt_all_url}', {pp_ser:val,agree_ma:no,note:note} , function(ret){
                        if(ret==1){
                            success_msg('رسالة','تمت عملية الارجاع للمعد بنجاح ');
                            $('#btn_cancel_adopt').attr('disabled','disabled');
                            reload_Page();
                        }else{
                            danger_msg('تحذير..',ret);
                        }
                    }, 'html');
                    
                }
            }else{
                danger_msg('يرجى تحديد السجلات');
                return -1;
            }
        } //end else if 
    } //end unadopt
    
    function show_detail_row(pp_ser){
        showLoading();
        // Display Modal
        $('#DetailModal').modal('show');
        $.ajax({
            url: '{$adopt_detail_url}',
            type: 'post',
            data: {pp_ser: pp_ser},
            success: function(response){
            // Add response in Modal body
            $('#public-modal-body').html(response);
            },
            complete: function() {
            HideLoading();
            }
        });
    } // show_detail_row
   
     function clear_form(){
        $('#dp_emp_no ,#dp_w_no ,#dl_status_type').select2('val',0);
        $('#txt_value_ma,#dl_op ').val('');
     }
    
    function show_all_recurring_records(){
        var where_sql = $('#txt_where_sql').val();
        showLoading();
        // Display Modal
        $('#recurring_records_modal').modal('show');
        $.ajax({
            url: '{$view_recurring_records_url}',
            type: 'post',
            data: {month: where_sql},
            success: function(response){
                // Add response in Modal body
                $('#recurring_body').html(response);
            },
                complete: function() {
                HideLoading();
            }
        });
    }

     function print_report(){
        var month = $('#txt_month').val();
        var branch_no = $('#dp_branch_no').val();
        var emp_no = $('#dp_emp_no').val();
        var status = $('#dl_status_type').val();

        _showReport('{$report_url}&report_type=pdf&report=call_pay_pdf&p_the_month='+month+'&p_branch_id='+branch_no+'&p_emp_no='+emp_no+'&p_status='+status);
    }
    
    
</script>
SCRIPT;
sec_scripts($scripts);
?>
