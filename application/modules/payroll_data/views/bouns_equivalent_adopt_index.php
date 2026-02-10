<?php
/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 20/02/2020
 * Time: 08:40 ص
 */
$MODULE_NAME = 'payroll_data';
$TB_NAME = 'bouns_equivalent_adopt';
$create_url = base_url("$MODULE_NAME/$TB_NAME/create");
$get_page = base_url("$MODULE_NAME/$TB_NAME/get_page");
$branch_query_url = base_url("$MODULE_NAME/$TB_NAME/select_branch_query");
$adopt_all_url_eq = base_url("$MODULE_NAME/$TB_NAME/adopt");
$unadopt_all_url_eq = base_url("$MODULE_NAME/$TB_NAME/unadopt");

$adopt_detail_eq = base_url("$MODULE_NAME/$TB_NAME/public_get_adopt_detail");
/*payroll_data/bouns_risk/CancelAdopt
/* U_BOUNS_RISK_MA,I_BOUNS_RISK_MA_AOPT */
//ارجاع الى مرحلة الاعداد2
$CancelAdopt_eq = base_url("$MODULE_NAME/$TB_NAME/return_adopt1");
//10//اعتماد المدير العام
$ManagerAdopt_eq = base_url("$MODULE_NAME/$TB_NAME/ManagerAdopt_eq");
//اعتماد المراقب الداخلي/20/
$InternalObserver_eq = base_url("$MODULE_NAME/$TB_NAME/InternalObserver_eq");
//اعتماد المالية/30/
$FinicalDepart_eq = base_url("$MODULE_NAME/$TB_NAME/FinicalDepart_eq");

$date_attr = " data-type='date' data-date-format='DD/MM/YYYY' data-val='true' data-val-regex-pattern='" . date_format_exp() . "' data-val-regex='Error' ";
echo AntiForgeryToken();
?>
<script> var show_page = true; </script>
<div class="row">
    <div class="toolbar">
        <div class="caption"><?= $title ?></div>
        <ul>
            <?php if (HaveAccess($create_url)): ?>
                <li><a href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>جديد</a>
                </li>
            <?php endif; ?>
            <li><a onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a>
            </li>
        </ul>
    </div>
    <div class="form-body">
        <form class="form-vertical" id="<?= $TB_NAME ?>_form">
            <div class="modal-body inline_form">

                <?php if (HaveAccess($branch_query_url)) { ?>
                    <div class="form-group col-sm-2">
                        <label> المقر</label>
                        <select name="branch_no" id="dp_branch_no" class="form-control sel2">
                            <option value="">_______</option>
                            <?php foreach ($branches as $row) : ?>
                                <option value="<?= $row['NO'] ?>"> <?= $row['NAME'] ?> </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php } elseif (!HaveAccess($branch_query_url)) { ?>
                    <input type="hidden" name="branch_no" id="dp_branch_no" value="<?= $this->user->branch ?>">
                <?php } ?>


                <div class="form-group col-sm-2">
                    <label>الشهر</label>
                    <div>
                        <input type="text" placeholder="الشهر"
                               name="month"
                               id="txt_month" class="form-control"
                               value="<?= date('Ym') ?>">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label>الموظف</label>
                    <div>
                        <select name="emp_no" id="dp_emp_no" class="form-control sel2">
                            <option value="">_________</option>
                            <?php foreach ($emp_no_cons as $row) : ?>
                                <option value="<?= $row['EMP_NO'] ?>"><?= $row['EMP_NO'] . ': ' . $row['EMP_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label>الدائرة</label>
                    <div>
                        <select name="head_department" id="dp_head_department" class="form-control sel2">
                            <option value="">_________</option>
                            <?php foreach ($head_department_cons as $row) : ?>
                                <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label>نوع التعيين</label>
                    <div>
                        <select name="emp_type" id="dp_emp_type" class="form-control sel2">
                            <option value="">_________</option>
                            <?php foreach ($emp_type_cons as $row) : ?>
                                <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label>طبيعة العمل</label>
                    <div>
                        <select name="w_no" id="dp_w_no" class="form-control sel2">
                            <option value="">_________</option>
                            <?php foreach ($w_no_cons as $row) : ?>
                                <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label>حالة الاعتماد</label>
                    <div>
                        <select name="adopt_stage" id="dp_adopt_stage" class="form-control sel2">
                            <option value="">_________</option>
                            <?php foreach ($adopt_cons as $row) : ?>
                                <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <br>
            </div>
        </form>
        <div class="modal-footer">
            <button type="button" onclick="javascript:search();" class="btn btn-success">
                <i class="fa fa-search"></i>
                إستعلام
            </button>

            <?php if (HaveAccess($ManagerAdopt_eq)) { ?>
                <button type="button" id="ManagerAdopt" onclick="javascript:open_ManagerAdopt_10();"
                        class="btn btn-info" style="display: none">
                    <i class="fa fa-check"></i>
                    اعتماد المدير العام
                </button>
            <?php } ?>
            <?php if (HaveAccess($InternalObserver_eq)) { ?>
                <button type="button" id="InternalObserver" onclick="javascript:open_InternalObserver_20();"
                        class="btn btn-info" style="display: none">
                    <i class="fa fa-check"></i>
                    اعتماد الرقابة
                </button>
            <?php } ?>

            <?php if (HaveAccess($FinicalDepart_eq)) { ?>
                <button type="button" id="FinicalDepart" onclick="javascript:open_FinicalDepart_30();"
                        class="btn btn-info" style="display: none">
                    <i class="fa fa-check"></i>
                    اعتماد المالية
                </button>
            <?php } ?>

            <?php if (HaveAccess($CancelAdopt_eq)) { ?>
                <button type="button" id="CancelAdopt" onclick="javascript:open_CancelAdopt_2();"
                        class="btn btn-warning" style="display: none">
                    <span class="fa fa-angle-double-left"></span>
                    ارجاع للجنة
                </button>
            <?php } ?>

            <button type="button" onclick="$('#page_tb').tableExport({type:'excel',escape:'false'});"
                    class="btn btn-success"> إكسل
                <i class="fa fa-file-excel-o"></i>
            </button>

            <button type="button" onclick="javascript:clear_form();" class="btn btn-default"> تفريغ الحقول</button>
        </div>
        <div id="msg_container"></div>

        <div id="container">
            <?php echo modules::run($get_page, $page); ?>

        </div>
    </div>
</div> <!--end row-->


<!--Modal NOTE Bootstrap -10- اعتماد  المدير العام-->
<!--Start Modal -->
<div class="modal fade bd-example-modal-lg" id="ManagerAdopt_note" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">اعتماد المدير العام</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <div class="row">
                        <div class="form-group  col-sm-4">
                            <label> الملاحظة </label>
                            <div>
                                <input type="text" name="note" value="-"
                                       id="txt_note_ManagerAdopt" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?php if (HaveAccess($ManagerAdopt_eq)) { ?>
                    <button type="button" onclick="javascript:adopt(10);" class="btn btn-info">
                        <i class="fa fa-check"></i>
                        اعتماد المدير العام
                    </button>
                <?php } ?>
                <button type="button" class="btn btn-primary" data-dismiss="modal">اغلاق</button>
            </div>
        </div>
    </div>
</div>
<!--END Modal Bootstrap -->


<!--Modal NOTE Bootstrap -20- اعتماد الرقابة-->
<!--Start Modal -->
<div class="modal fade bd-example-modal-lg" id="InternalObserver_note" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">اعتماد المراقب الداخلي</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <div class="row">
                        <div class="form-group  col-sm-4">
                            <label> الملاحظة </label>
                            <div>
                                <input type="text" name="note" value="-"
                                       id="txt_note_InternalObserver" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?php if (HaveAccess($InternalObserver_eq)) { ?>
                    <button type="button" onclick="javascript:adopt(20);" class="btn btn-info">
                        <i class="fa fa-check"></i>
                        اعتماد المراقب الداخلي
                    </button>
                <?php } ?>
                <button type="button" class="btn btn-primary" data-dismiss="modal">اغلاق</button>
            </div>
        </div>
    </div>
</div>
<!--END Modal Bootstrap -->


<!--Modal NOTE Bootstrap -30- اعتماد المالية-->
<!--Start Modal -->
<div class="modal fade bd-example-modal-lg" id="FinicalDepart_note" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">اعتماد المالية</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <div class="row">
                        <div class="form-group  col-sm-4">
                            <label> الملاحظة </label>
                            <div>
                                <input type="text" name="note" value="-"
                                       id="txt_note_FinicalDepart" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?php if (HaveAccess($FinicalDepart_eq)) { ?>
                    <button type="button" onclick="javascript:adopt(30);" class="btn btn-info">
                        <i class="fa fa-check"></i>
                        اعتماد المالية
                    </button>
                <?php } ?>
                <button type="button" class="btn btn-primary" data-dismiss="modal">اغلاق</button>
            </div>
        </div>
    </div>
</div>
<!--END Modal Bootstrap -->


<!--Modal NOTE Bootstrap  الغاء الاعتماد الارجاع الى المعد-->
<!--Start Modal -->
<div class="modal fade bd-example-modal-lg" id="CancelAdopt_note" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">ملاحظة الارجاع</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <div class="row">

                        <div class="form-group  col-sm-4">
                            <label> سبب الارجاع </label>
                            <div>
                                <input type="text" name="note" value="-"
                                       id="txt_CancelAdopt" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?php if (HaveAccess($CancelAdopt_eq)) { ?>
                    <button type="button" onclick="javascript:unadopt(2);" class="btn btn-warning">
                        <i class="fa fa-angle-double-left"></i>
                        ارجاع الى اللجنة
                    </button>
                <?php } ?>
                <button type="button" class="btn btn-primary" data-dismiss="modal">اغلاق</button>
            </div>
        </div>
    </div>
</div>
<!--END Modal Bootstrap -->

<!--Modal Bootstrap بيانات الاعتماد-->
<!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="exampleModal" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">بيانات الاعتماد</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <div class="row">
                        <div class="row">
                            <div class="form-group  col-sm-2">
                                <label> رقم الموظف </label>

                                <div>
                                    <input type="text" readonly name="emp_no_m"
                                           id="txt_emp_no_m" class="form-control">

                                </div>
                            </div>
                            <div class="form-group  col-sm-3">
                                <label> اسم الموظف </label>

                                <div>
                                    <input type="text" readonly name="emp_name_m"
                                           id="txt_emp_name_m" class="form-control">

                                </div>
                            </div>
                            <div class="form-group  col-sm-1">
                                <label>الشهر </label>

                                <div>
                                    <input type="text" readonly name="month_m"
                                           id="txt_month_m" class="form-control">
                                </div>
                            </div>

                            <div class="form-group  col-sm-1">
                                <label>المكافأة المعتمدة </label>

                                <div>
                                    <input type="text" readonly name="value_ma_m"
                                           id="txt_value_ma_m" class="form-control">
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="tbl_container <?= $TB_NAME; ?>_Detail_adopt"
                            ">
                            <table class="table">
                                <thead class="bg-primary">
                                <tr>
                                    <th>#</th>
                                    <th>الجهة المعتمدة</th>
                                    <th>اسم المعتمد</th>
                                    <th>تاريخ الاعتماد</th>
                                    <th>ملاحظة الاعتماد</th>
                                </tr>
                                </thead>
                                <tbody id="append_data">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal">اغلاق</button>
        </div>
    </div>
</div>
</div>
<!--Modal Bootstrap -->

<?php
$scripts = <<<SCRIPT
<script type="text/javascript">

    $('.sel2').select2();


    var table = 'bouns_equivalent_adopt';
    var count = 0;

  

    $('#txt_month').datetimepicker({
            format: 'YYYYMM',
            minViewMode: 'months',
            pickTime: false,
            
        });
    
    
       
          $('#dp_adopt_stage').select2('val','{$adopt_stage}');
          
          search();

  

    $('.pagination li').click(function(e){
        e.preventDefault();
    });
    
     // var StartAdopt   = $('#StartAdopt');
     // var buttonChiefFinancial = $('#ChiefFinancial');
      var ManagerAdopt = $('#ManagerAdopt');
      var InternalObserver = $('#InternalObserver');
      var FinicalDepart = $('#FinicalDepart');
      //cancel Adopt
      var CancelAdopt = $('#CancelAdopt');

      
   

    function values_search(add_page){
        var values=
            {page:1, branch_no:$('#dp_branch_no').val(),
                month:$('#txt_month').val(),
                emp_no:$('#dp_emp_no').val(),
                head_department:$('#dp_head_department').val(),
                w_no:$('#dp_w_no').val(),
                emp_type:$('#dp_emp_type').val(),
                adopt_stage:$('#dp_adopt_stage').val()
            };
        if(add_page==0)
            delete values.page;
        return values;
    }


    function search(){
        var values= values_search(1);
          var adopt_stage = $('#dp_adopt_stage').val();
         $('#page_tb .checkboxes').prop("disabled", true);    
            $('#ManagerAdopt').hide();
            $('#InternalObserver').hide();
            $('#FinicalDepart').hide();
           $('#CancelAdopt').hide();
            if (adopt_stage == 3) {
                $('#ManagerAdopt').show();  
               
            } else if (adopt_stage == 10) {
                 $('#InternalObserver').show();  
                  $('#CancelAdopt').show();
             
            } else if (adopt_stage == 20) {
                $('#FinicalDepart').show();
                 $('#CancelAdopt').show();
            }
        //console.log(values);
        get_data('{$get_page}',values ,function(data){
            $('#container').html(data);
        },'html');
    }


    function LoadingData(){
        var values= values_search(0);
        ajax_pager_data('#page_tb > tbody',values);
    }
    
            //اعتماد المدير العام
           function open_ManagerAdopt_10(){
                $('#ManagerAdopt_note').modal('show');
            }
    
            //اعتماد الرقابة
          function open_InternalObserver_20(){
             $('#InternalObserver_note').modal('show');
            }
         
            //اعتماد المالية
           function open_FinicalDepart_30(){
                 $('#FinicalDepart_note').modal('show');
                }
             
 
        //Cancel Adopt Modalالارجاع للمعد  ---//
        function open_CancelAdopt_2(){
            $('#CancelAdopt_note').modal('show');
        }
         
      function adopt(no){
       var action_desc= 'اعتماد';
       var val = [];
           // var note = $('#txt_note').val();
            if (no == 10){
                 var note = $('#txt_note_ManagerAdopt').val();
            } else if (no == 20) {
                 var note = $('#txt_note_InternalObserver').val();
            } else if (no == 30) {
               var note = $('#txt_note_FinicalDepart').val();

            }
            $('#page_tb .checkboxes:checked').each(function(i){
                val[i] = $(this).val();
            });
               if(val.length > 0){
            if(confirm('هل تريد بالتأكيد '+action_desc+' '+val.length+' بنود')){
             get_data('{$adopt_all_url_eq}', {ser:val,agree_ma:no,note:note} , function(ret){
                 /*alert(val);*/
                     if(ret==1){
                        success_msg('رسالة','تمت عملية الاعتماد بنجاح ');
                        reload_Page();
                    }else{
                        danger_msg('تحذير..',ret);
                    }
               
            }, 'html');
            }
         
        }
    }

     function unadopt(no){
       var action_desc= 'ارجاع للمعد';
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
             get_data('{$unadopt_all_url_eq}', {ser:val,agree_ma:no,note:note} , function(ret){
                 /*alert(val);*/
                     if(ret==1){
                        success_msg('رسالة','تمت عملية الارجاع للمعد بنجاح ');
                        search();
                         // $('#adopt_note').modal('hide');
                    }else{
                        danger_msg('تحذير..',ret);
                    }
               
            }, 'html');
            
            }
         
        }
          
          } //end else if 
     } //end unadopt
    
    function show_detail_row(obj) {
        $('#exampleModal').modal('show');
          var tr = obj.closest('tr');
          var ser  = $('input[name="ser"]',tr).val();
          var emp_no_m  = $('input[name="no"]',tr).val();
          var emp_name_m  = $('input[name="emp_name"]',tr).val();
          var month_m  = $('input[name="month"]',tr).val();
          var value_ma_m  = $('input[name="value_ma"]',tr).val();
          $('input[name="emp_no_m"]').val(emp_no_m);
          $('input[name="emp_name_m"]').val(emp_name_m);
          $('input[name="month_m"]').val(month_m);
          $('input[name="value_ma_m"]').val(value_ma_m);
          var html='';
         get_data('{$adopt_detail_eq}', {ser:ser} , function(ret){
            // console.log(ret);
         $('.'+table+'_Detail_adopt table tbody tr').remove();
         count1 = 1;
            var note_adopt = '';
        // console.log(ret);
         $(ret).each(function(key, value) {
         // if (value.NOTE == null) {
           //   note_adopt ='';
         // }
         html = html+'<tr><td>'+count1+'</td><td>'+value.ADOPT_NAME+'</td><td>'+value.ADOPT_USER_NAME+'</td><td>'+value.ADOPT_DATE+'</td><td>'+value.NOTE+'</td></tr>';
          count1 = count1 + 1;

         });
         $('#append_data').append(html);
        });
    }
 

    function clear_form(){
        clearForm($('#payroll_form'));
        $('.sel2').select2('val','');
    }
    
    
    

</script>
SCRIPT;
sec_scripts($scripts);
?>
