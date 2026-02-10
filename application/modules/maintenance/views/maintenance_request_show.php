<?php
/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 29/07/2019
 * Time: 11:38 ص
 */
$MODULE_NAME = 'maintenance';
$TB_NAME = 'maintenance';
$isCreate = isset($get_data) && count($get_data) > 0 ? false : true;
$rs = $isCreate ? array() : $get_data[0];
if (!isset($result))
    $result = array();
$HaveRs = count($result) > 0;

$isCreate2 = isset($get_data_class) && count($get_data_class) > 0 ? false : true;
$rs_class_id = $isCreate2 ? array() : $get_data_class[0];
$backs_url = base_url("$MODULE_NAME/$TB_NAME/index"); //$action
$adopt_url = base_url("$MODULE_NAME/$TB_NAME/adopt_");
//ADD SOLVE
$addProcedure = base_url("$MODULE_NAME/$TB_NAME/addProcedure");
//for date
$date_attr = " data-type='date' data-date-format='DD/MM/YYYY' data-val='true' data-val-regex-pattern='" . date_format_exp() . "' data-val-regex='Error' ";
$get_url = base_url("$MODULE_NAME/$TB_NAME/get");
//
$get_page_store = base_url("stores/stores_payment_request/create");

$deleteClassid = base_url("$MODULE_NAME/$TB_NAME/delete_class_id");


$store_payment = base_url("$MODULE_NAME/$TB_NAME/store_payment");


$process_request_url = base_url("$MODULE_NAME/$TB_NAME/public_process_request");




$notes_url = notes_url();
$ser = isset($rs['SER']) ? $rs['SER'] : '';
$status = isset($rs['STATUS']) ? $rs['STATUS'] : '';
?>
<!-- PAGE-HEADER -->
<div class="page-header">
    <div>
        <h1 class="page-title">بيانات طلب الصيانة</h1>
    </div>
    <div class="ms-auto pageheader-btn">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">الدعم الفني</a></li>
            <li class="breadcrumb-item active" aria-current="page">طلبات الصيانة</li>
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
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-row">
                                <!--start no -->
                                <div class="form-group col-md-1">
                                    <label>رقم الطلب</label>
                                    <input type="text" value="<?= $rs['REQUEST_SERIAL']; ?>" name="ser"
                                           id="txt_ser" readonly class="form-control ">
                                </div>
                                <!--end no -->
                                <!--service_type -->
                                <div class="form-group col-md-2">
                                    <label> نوع الخدمة </label>
                                    <input type="text" value="<?php echo $rs['SERVICE_TYPE_NAME']; ?>" id="txt_service_type" readonly
                                           class="form-control ">
                                </div>
                                <!--service_type -->
                                <!--service_property -->
                                <div class="form-group col-md-2">
                                    <label> الأهمية </label>
                                    <input type="text" value="<?= $rs['SERVICE_PROPERTY_NAME']; ?>" id="txt_service_property" readonly
                                           class="form-control ">

                                </div>
                                <!--service_property -->

                                <!--STATUS_NAME -->
                                <div class="form-group col-md-2">
                                    <label class="text-danger"> حالة الطلب </label>
                                    <input type="text" value="<?= $rs['STATUS_NAME']; ?>" id="txt_status" readonly class="form-control ">
                                </div>
                                <!--STATUS_NAME -->


                                <!--entry_date -->
                                <div class="form-group col-md-2">
                                    <label> تاريخ الطلب </label>
                                    <input type="text" value="<?php echo $rs['ENTRY_DATE']; ?>" id="txt_entry_date" readonly
                                           class="form-control ">
                                </div>
                                <!--entry_date -->
                                <!--entry_user -->
                                <div class="form-group col-md-2">
                                    <label> المدخل</label>
                                    <input type="text" value="<?php echo $rs['ENTRY_USER_NAME']; ?>" id="txt_entry_user_name" readonly
                                           class="form-control ">
                                </div>
                                <!--entry_user -->
                            </div>
                            <div class="form-row">
                                <!--NOTE_PROBLEM -->
                                <div class="form-group col-md-12">
                                    <label class="text-danger">وصف العطل</label>
                                    <input type="text" value="<?php echo $rs['NOTE_PROBLEM']; ?>" name="desc_problem"
                                           id="txt_desc_problem" readonly class="form-control ">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card-border">
                                <div class="card-header" style="background-color: #414d84;">
                                    <h3 class="card-title"> بيانات صاحب العهدة </h3>
                                </div>
                                <div class="card-body">
                                    <div class="form-row">
                                        <!--BRANCH -->
                                        <div class="form-group col-md-2">
                                            <label> الفرع </label>
                                            <input type="text"
                                                   value="<?= $rs['BRANCH_NAME']; ?>"
                                                   name="branch" id="txt_branch" readonly
                                                   class="form-control ">
                                        </div>
                                        <!--BRANCH -->
                                        <!--name of EMP_NO -->
                                        <div class="form-group col-md-4">
                                            <label> اسم صاحب العهدة </label>
                                            <input type="text"
                                                   value="<?= $rs['EMP_NO'] . '-' . $rs['EMP_NAME']; ?>"
                                                   name="emp_no" id="txt_emp_no" readonly
                                                   class="form-control ">
                                        </div>
                                        <!--name of EMP_NO-->

                                        <!--name of jobs -->
                                        <div class="form-group col-md-3">
                                            <label>المسمى الوظيفي </label>
                                            <input type="text"
                                                   value="<?php echo $rs['W_NO_ADMIN_NAME']; ?>"
                                                   name="emp_no" id="txt_emp_no" readonly
                                                   class="form-control ">
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card-border">
                                <div class="card-header" style="background-color: #5373af;">
                                    <h3 class="card-title"> الاجهزة المطلوب صيانتها </h3>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead class="table-light">
                                            <tr>
                                                <th>رقم الجهاز</th>
                                                <th>اسم الجهاز</th>
                                                <th>الحالة</th>
                                                <th style="width: 35%">حل المشكلة</th>
                                                <th>اسم الشركة</th>
                                                <th>تكلفة الصيانة</th>
                                                <th>الاجراءات</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach ($get_data_class as $row2) : ?>
                                                <tr data-class-id="<?= $row2['CLASS_ID'] ?>">
                                                    <input type="hidden" value="<?= $row2['SER_CLASS_ID'] ?>"
                                                           name="ser_class_id">
                                                    <input type="hidden" value="<?= $row2['CLASS_ID'] ?>"
                                                           name="class_id">
                                                    <td><?= $row2['CLASS_ID'] ?></td>
                                                    <td><?= $row2['CLASS_ID_NAME'] ?></td>
                                                    <td><select name="status_class_id[]" id="status_class_id"
                                                                class="form-control ">
                                                            <?php foreach ($status_class_type as $row) : ?>
                                                                <option <?= ($row2['STATUS_CLASS_ID'] == $row['CON_NO'] ? 'selected' : '') ?>
                                                                        value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                                            <?php endforeach; ?>
                                                        </select></td>
                                                    <td>
                                                        <input type="text"
                                                               value="<?= $row2['SOLVE_PROBLEM']; ?>"
                                                               name="solve_problem" id="txt_solve_problem"
                                                               class="form-control">
                                                    </td>
                                                    <td>
                                                        <input type="text" value="<?= $row2['COMP_NAME']; ?>"
                                                               name="comp_name" id="txt_comp_name"
                                                               class="form-control ">
                                                    </td>

                                                    <td>
                                                        <input type="number" value="<?= $row2['COST_MAIN']; ?>"
                                                               name="cost_main" id="txt_cost_main"
                                                               class="form-control ">
                                                    </td>
                                                    <td>
                                                        <?php if (HaveAccess($addProcedure) && $status == 2) { ?>
                                                            <a href="javascript:;" onclick="addProcedure(this)"
                                                               title="اضافة"><span
                                                                        class="glyphicon glyphicon-ok"></span></a> |
                                                        <?php } ?>
                                                        <?php if (HaveAccess($deleteClassid) && $status == 2) { ?>
                                                            <!-- <a href="javascript:;" onclick="deleteProcedure_(this)"
                                                                title="حذف صنف"><span class="fa fa-trash"></span></a> |-->
                                                        <?php } ?>
                                                        <?php if (HaveAccess($addProcedure) && $status == 2) { ?>
                                                            <?= modules::run("$MODULE_NAME/$TB_NAME/indexInline", $row2['CLASS_ID'], 'MAINTENACE_REQ_TB_' . $row2['SER_CLASS_ID'], 1); ?>
                                                        <?php } ?>
                                                    </td>

                                                </tr>
                                            <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if ($status >= 2) { ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card-border">
                                    <div class="card-header" style="background-color: #5373af;">
                                        <h3 class="card-title">تقرير الفني المختص</h3>
                                    </div>
                                    <div class="card-body">
                                        <?php if ($status == 20) { ?>
                                        <div class="row">
                                            <div class="form-group col-md-4">
                                                <label>حالة الطلب</label>
                                                <select class="form-control" disabled >
                                                    <?php foreach($status_type_con as $row)   {?>
                                                        <option <?=($rs['STATUS']==$row['CON_NO']?'selected':'')?> value="<?=$row['CON_NO']?>"> <?=  $row['CON_NAME']?> </option>
                                                    <?php }  ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label for="txt_solve_problem_final1">
                                                    التقرير الفني
                                                </label>
                                                <textarea class="form-control" id="txt_solve_problem_final1" name="txt_solve_problem_final1" rows="4" readonly>
                                                     <?= $rs['SOLVE_PROBLEM']; ?>
                                                </textarea>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label>مستلم الجهاز</label>
                                                <select class="form-control" disabled>
                                                    <option value="">---------</option>
                                                    <?php foreach ($employee_arr as $row) : ?>
                                                        <option <?=($rs['RECEIPT_USER']==$row['EMP_NO']?'selected':'')?> value="<?= $row['EMP_NO'] ?>"><?= $row['EMP_NO'] . " : " . $row['USER_NAME'] ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label>تاريخ الاستلام</label>
                                                <input type="text"  value="<?= $rs['RECEIPT_DATE']; ?>" class="form-control" readonly>
                                            </div>
                                            <div class="form-group col-md-5">
                                                <label>ملاحظة المستلم </label>
                                                <input type="text" value="<?= $rs['RECEIPT_NOTE']; ?>"  class="form-control"  readonly>
                                            </div>

                                            <div class="form-group col-md-2">
                                                <label>  معالج الطلب </label>
                                                <input type="text" value="<?=  $rs['CLOSED_USER_NAME']; ?>"   readonly  class="form-control" >
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label>تاريخ المعالجة</label>
                                                <input type="text" value="<?=  $rs['CLOSED_DATE']; ?>"   readonly  class="form-control" >
                                            </div>
                                        </div>
                                        <?php } ?>
                                        <div class="row">
                                            <div class="row pull-right">
                                                <div style="clear: both;">
                                                    <?php echo modules::run("$MODULE_NAME/$TB_NAME/public_get_notes", $ser, 'MAINTENANCE_REQ_TB'); ?>
                                                    <?php echo modules::run("$MODULE_NAME/$TB_NAME/indexLine", $rs['SER'], 'MAINTENANCE_REQ_TB'); ?>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="row">
                        <div class="flex-shrink-0">
                            <?php if (HaveAccess($adopt_url . '2') && !$isCreate && $status == 1) : ?>
                                <button type="button" onclick='javascript:adopt_(2);' class="btn btn-success">
                                    <i class="fa fa-check"></i>
                                    تحويل لقسم الدعم الفني
                                </button>
                            <?php endif; ?>
                            <?php if (HaveAccess($adopt_url . '20') && $status >= 2 && $status != 20) : ?>
                                <button type="button" onclick="javascript:openProcessRequest();"
                                        class="btn btn-success">
                                    <i class="fa fa-check"></i>
                                    معالجة الطلب
                                </button>
                            <?php endif; ?>

                            <button type="button" class="btn btn-info" onclick="javascript:openNoteModal();">
                                <i class="fa fa-comment"></i>
                                ادخال ملاحظة
                            </button>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="public_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">معالجة الطلب</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
               <div class="form-row">
                   <input type="hidden" value="<?= $rs['SER']; ?>" name="h_ser" id="txt_h_ser">
                   <div class="form-group col-md-4">
                       <label>حالة الطلب</label>
                       <select name="status" id="dl_status" class="form-control sel2" >
                           <?php foreach($status_type_con as $row)   {?>
                              <option <?=($rs['STATUS']==$row['CON_NO']?'selected':'')?> value="<?=$row['CON_NO']?>"> <?=  $row['CON_NAME']?> </option>
                           <?php }  ?>
                       </select>
                   </div>
                   <div class="form-group col-md-12">
                       <label for="txt_solve_problem_final">
                           التقرير الفني
                       </label>
                       <textarea class="form-control" id="txt_solve_problem_final" name="txt_solve_problem_final" rows="4">
                                <?= $rs['SOLVE_PROBLEM']; ?>
                       </textarea>
                   </div>
                   <div class="form-group col-md-3">
                       <label>مستلم الجهاز</label>
                       <select name="receipt_user" id="dl_receipt_user" class="form-control">
                           <option value="">---------</option>
                           <?php foreach ($employee_arr as $row) : ?>
                               <option <?=($rs['RECEIPT_USER']==$row['EMP_NO']?'selected':'')?> value="<?= $row['EMP_NO'] ?>"><?= $row['EMP_NO'] . " : " . $row['USER_NAME'] ?></option>
                           <?php endforeach; ?>
                       </select>
                   </div>
                   <div class="form-group col-md-2">
                       <label>تاريخ الاستلام</label>
                       <input type="text" <?=$date_attr?> value="<?= $rs['RECEIPT_DATE']; ?>" name="receipt_date" id="txt_receipt_date" class="form-control">
                   </div>
                   <div class="form-group col-md-5">
                       <label>ملاحظة المستلم </label>
                       <input type="text" value="<?= $rs['RECEIPT_NOTE']; ?>"  name="receipt_note" id="txt_receipt_note"   class="form-control " >
                   </div>
               </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">اغلاق</button>
                <button class="btn btn-primary" onclick="process_request();">حفظ البيانات</button>
            </div>
        </div>
    </div>
</div>

<!-- notesModal -->
<div class="modal fade"  id="notesModal" tabindex="-1" role="dialog" >
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">ملاحظات</h5>
                <button  class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="txt_g_notes" class="form-label">ملاحظات</label>
                    <textarea class="form-control" id="txt_g_notes" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button  class="btn btn-primary" onclick="javascript:apply_action();">حفظ البيانات</button>
                <button  class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>

            </div>
        </div>
    </div>
</div>

<?php
$scripts = <<<SCRIPT
<script>
 
    ///الاعتماد//////////////////
    function adopt_(no){
        var msg= 'هل تريد الاعتماد ؟!';
        if(confirm(msg)){
            var values= {ser: "{$rs['SER']}"};
            get_data('{$adopt_url}'+no, values, function(ret){
                if(parseInt(ret)>=1){
                    success_msg('رسالة','تم اعتماد الطلب بنجاح ..');
                    $('button').attr('disabled','disabled');
                    setTimeout(function(){
                        get_to_link(window.location.href);
                    },2000);
                }else{
                    danger_msg('تحذير..',ret);
                    return -1;
                }
            }, 'html');
        }
    }
    
    function openNoteModal(){
         $('#notesModal').modal('show');
    }
    function apply_action(){
       get_data('{$notes_url}',{source_id:{$ser},source:'MAINTENANCE_REQ_TB',notes:$('#txt_g_notes').val()},function(data){
                $('#txt_g_notes').val('');
       },'html');
       $('#notesModal').modal('hide');
    }
    
    function openProcessRequest(){
        $('#dl_receipt_user').select2({
            dropdownParent: $('#public_modal')
        });
        $('#public_modal').modal('show');
    }
    function process_request(){
         if(confirm('هل تريد حفظ البيانات  ؟!')){
          var h_ser = $('#txt_h_ser').val();
          var status = $('#dl_status').val();
          var solve_problem_final = $('#txt_solve_problem_final').val();
          var receipt_user = $('#dl_receipt_user').val();
          var receipt_date = $('#txt_receipt_date').val();
          var receipt_note = $('#txt_receipt_note').val();
          if(status == ''){
            danger_msg('يجب ادخال حالة الطلب');
            return -1;
          }else if ($.trim(solve_problem_final) == ''){
               danger_msg('يجب ادخال وصف التقرير الفني بشكل سليم');
                return -1;
           }else{
             get_data('{$process_request_url}', {h_ser:h_ser,status:status,solve_problem_final:solve_problem_final,receipt_user:receipt_user,receipt_date:receipt_date,receipt_note:receipt_note} , function(ret){
                 if(parseInt(ret) >= 1){
                    success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                    get_to_link(window.location.href);
                }else{
                    danger_msg('تحذير..',ret);
                    return -1;
                }
             }, 'html');
          }  
         } //END IF CONFIRM
    }
    
</script>
SCRIPT;
sec_scripts($scripts);
?>
