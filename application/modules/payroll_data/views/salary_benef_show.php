<?php
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 14/07/22
 * Time: 13:00 ص
 */

$MODULE_NAME = 'payroll_data';
$TB_NAME = 'Salary_benef';
$DET_TB_NAME= 'public_get_details';

$post_url = base_url("$MODULE_NAME/$TB_NAME/" . ($action == 'index' ? 'create' : $action));
$get_url = base_url("$MODULE_NAME/$TB_NAME/get");
$adopt_url = base_url("$MODULE_NAME/$TB_NAME/public_adopt_");
$unadopt_url = base_url("$MODULE_NAME/$TB_NAME/public_unadopt");
$back_url = base_url("$MODULE_NAME/$TB_NAME/index");
$get_badl_type = base_url("$MODULE_NAME/$TB_NAME/public_get_badl_type");
$delete_url = base_url("$MODULE_NAME/$TB_NAME/delete");
$public_get_val_constant_url = base_url("$MODULE_NAME/$TB_NAME/public_get_val_constant");
$public_get_retirement_date_url = base_url("$MODULE_NAME/$TB_NAME/public_get_retirement_date");

$count = 1;
$isCreate = isset($master_tb_data) && count($master_tb_data) > 0 ? false : true;
$HaveRs = (!$isCreate) ? true : false;
$rs = $isCreate ? array() : $master_tb_data[0];
if ($HaveRs){
    $edit = 1;
    $badl_types = $rs['BADL_TYPE'];
    $con_no_arr = $this->rmodel->get('BADL_TYPE_GET', $badl_types);
}else{
    $edit = 0;
}

$adopt = isset($rs['ADOPT']) ? $rs['ADOPT'] : '';
$ser = isset($rs['SER']) ? $rs['SER'] : '';
?>

    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="left-content">
            <span class="main-content-title mg-b-0 mg-b-lg-1"><?= $title ?></span>
        </div>
        <div class="justify-content-center mt-2">
            <ol class="breadcrumb">
                <li class="breadcrumb-item tx-15"><a href="javascript:void(0);">الرواتب</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= $title ?></li>
            </ol>
        </div>
    </div>
    <!-- /breadcrumb -->

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header align-items-center d-flex py-3">
                    <div class="mb-0 flex-grow-1 card-title">
                        الطلب
                    </div>
                    <div class="flex-shrink-0">
                        <a class="btn btn-secondary" href="<?= $back_url ?>"><i class="fa fa-reply"></i> </a>
                    </div>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="example">

                        <form class="form-vertical" id="<?= $TB_NAME ?>_form" method="post" role="form"
                              action="<?= $post_url ?>" novalidate="novalidate">
                            <div class="row">
                                <div class="col-md-8">

                                    <div class="form-row">
                                        <div class="form-group col-sm-1">
                                            <label>رقم الطلب</label>
                                            <input type="text" value="<?= $HaveRs ? $rs['SER'] : '' ?>" readonly id="txt_ser"
                                                   class="form-control"/>
                                            <?php if ((isset($can_edit) ? $can_edit : false)) : ?>
                                                <input type="hidden" value="<?= $HaveRs ? $rs['SER'] : '' ?>" name="ser"
                                                       id="h_ser"/>
                                            <?php endif; ?>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label>نوع البدل</label>
                                            <select name="badl_typ" id="dp_badl_typ" class="form-control">
                                                <option value="">_________</option>
                                                <?php foreach ($badl_typ_cons as $row) : ?>
                                                    <option <?= $HaveRs ? ($rs['BADL_TYPE'] == $row['CON_NO'] ? 'selected' : '') : '' ?>
                                                        value="<?= $row['CON_NO'] ?>"><?= $row['CON_NO'] . ': ' . $row['CON_NAME'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label>البند</label>
                                            <select name="con_no" id="dp_con_no" class="form-control">
                                                <?php if ($edit == 1) { ?>
                                                    <?php foreach ($con_no_arr as $row) : ?>
                                                        <option <?= $HaveRs ? ($rs['BAND'] == $row['NO'] ? 'selected' : '') : '' ?> value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                                                    <?php endforeach; ?>
                                                <?php } else if ($edit == 0) { ?>
                                                    <option value="">_________</option>
                                                <?php } ?>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label>خاضع للضريبة</label>
                                            <select name="is_taxed" id="dp_is_taxed" class="form-control">
                                                <option value="">_________</option>
                                                <?php foreach ($is_taxed_cons as $row) : ?>
                                                    <option <?= $HaveRs ? ($rs['IS_TAXED'] == $row['CON_NO'] ? 'selected' : '') : '' ?>
                                                        value="<?= $row['CON_NO'] ?>"><?= $row['CON_NO'] . ': ' . $row['CON_NAME'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <input type="hidden" value="2" name="get_case" id="h_get_case"/>

                                    </div>
                                </div>
                            </div>

                            <div class="row requests" style="display:none ;">
                                <div class="col-md-12">
                                    <div class="tr_border">
                                        <h4 class="text-primary">طلبات البنود</h4>
                                        <br>
                                        <?php echo modules::run("$MODULE_NAME/$TB_NAME/$DET_TB_NAME", ($HaveRs)?$rs['SER']:0,$adopt); ?>
                                    </div>
                                </div>
                            </div>

                            <br>
                            <div class="modal-footer">

                                <?php if (1 && ($isCreate || ($rs['ADOPT'] == 1 and isset($can_edit) ? $can_edit : false))) : ?>
                                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                                <?php endif; ?>

                                <?php if ( HaveAccess($adopt_url.'2') and $adopt == 1 ) { ?>
                                    <button type="button" id="adoptentrance" onclick="javascript:open_Entry_2();"
                                            class="btn btn-success" >
                                        اعتماد المدخل
                                    </button>
                                <?php } ?>

                                <?php if ( HaveAccess($adopt_url.'10') and $adopt  == 2) { ?>
                                    <button type="button" id="ChiefFinancial" onclick="javascript:open_ChiefFinancial_10();"
                                            class="btn btn-indigo">
                                        <i class="fa fa-check"></i>
                                        اعتماد المدير المالي في المقر
                                    </button>
                                <?php } ?>

                                <?php if ( HaveAccess($adopt_url.'20') and $adopt == 10) { ?>
                                    <button type="button" id="InternalObserver" onclick="javascript:open_InternalObserver_20();"
                                            class="btn btn-indigo" >
                                        <i class="fa fa-check"></i>
                                        اعتماد المراقب الداخلي
                                    </button>
                                <?php } ?>

                                <?php if ( HaveAccess($adopt_url.'30') and $adopt == 20) { ?>
                                    <button type="button" id="HeadOffice" onclick="javascript:open_HeadOffice_30();"
                                            class="btn btn-indigo" >
                                        <i class="fa fa-check"></i>
                                        اعتماد مدير المقر
                                    </button>
                                <?php } ?>

                                <?php if ( HaveAccess($adopt_url.'35') and $adopt == 30) { ?>
                                    <button type="button" id="Financialpay" onclick="javascript:open_Financial_pay_35();"
                                            class="btn btn-indigo" >
                                        <i class="fa fa-check"></i>
                                        اعتماد المالية
                                    </button>
                                <?php } ?>

                                <?php if ( HaveAccess($unadopt_url) and $adopt >= 2) { ?>
                                    <button type="button" id="CancelAdopt" onclick="javascript:open_CancelAdopt_0();"
                                            class="btn btn-secondary" >
                                        <i class="fa fa-angle-double-left"></i>
                                        إرجاع
                                    </button>
                                <?php } ?>

                            </div>

                        </form>

                        <div id="container">
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--Modal NOTE Bootstrap -2- اعتماد المدخل -->
    <!--Start Modal -->
    <div class="modal fade bd-example-modal-lg" id="Entry_note" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"> اعتماد المدخل </h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="form-group  col-sm-8">
                                <label> الملاحظة </label>
                                <input type="text" name="note" value="-" id="txt_note_entry" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <?php if (HaveAccess($adopt_url.'2') and $adopt == 1 ) { ?>
                        <button type="button" onclick="javascript:adopt(2);" class="btn btn-indigo">
                            <i class="fa fa-check"></i>
                            اعتماد المدخل
                        </button>
                    <?php } ?>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">اغلاق</button>
                </div>
            </div>
        </div>
    </div>
    <!--END Modal Bootstrap -->

    <!--Modal NOTE Bootstrap -10- اعتماد المدير المالي-->
    <!--Start Modal -->
    <div class="modal fade bd-example-modal-lg" id="ChiefFinancial_note" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"> اعتماد المدير المالي</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="form-group  col-sm-8">
                                <label> الملاحظة </label>
                                <input type="text" name="note" value="-" id="txt_note_ChiefFinancial" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <?php if (HaveAccess($adopt_url.'10') and $adopt  == 2) { ?>
                        <button type="button" onclick="javascript:adopt(10);" class="btn btn-indigo">
                            <i class="fa fa-check"></i>
                            اعتماد المدير المالي في المقر
                        </button>
                    <?php } ?>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">اغلاق</button>
                </div>
            </div>
        </div>
    </div>
    <!--END Modal Bootstrap -->

    <!--Modal NOTE Bootstrap -20- اعتماد المراقب الداخلي-->
    <!--Start Modal -->
    <div class="modal fade bd-example-modal-lg" id="InternalObserver_note" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">اعتماد المراقب الداخلي</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="form-group  col-sm-8">
                                <label> الملاحظة </label>
                                <input type="text" name="note" value="-" id="txt_note_InternalObserver" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <?php if (HaveAccess($adopt_url.'20') and $adopt == 10) { ?>
                        <button type="button" onclick="javascript:adopt(20);" class="btn btn-indigo">
                            <i class="fa fa-check"></i>اعتماد المراقب الداخلي
                        </button>
                    <?php } ?>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">اغلاق</button>
                </div>
            </div>
        </div>
    </div>
    <!--END Modal Bootstrap -->

    <!--Modal NOTE Bootstrap -30- اعتماد مدير المقر-->
    <!--Start Modal -->
    <div class="modal fade bd-example-modal-lg" id="HeadOffice_note" tabindex="-1" role="dialog"
         aria-labelledby="HeadOffice_noteLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">اعتماد مدير المقر</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="form-group  col-sm-8">
                                <label> الملاحظة </label>
                                <input type="text" name="note" value="-" id="txt_note_HeadOffice" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <?php if (HaveAccess($adopt_url.'30') and $adopt == 20) { ?>
                        <button type="button" id="HeadOffice" onclick="javascript:adopt(30);" class="btn btn-indigo">
                            <i class="fa fa-check"></i>اعتماد مدير المقر
                        </button>
                    <?php } ?>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">اغلاق</button>
                </div>
            </div>
        </div>
    </div>
    <!--END Modal Bootstrap -->

    <!--Modal NOTE Bootstrap -35للصرف  اعتماد  المالية-->
    <!--Start Modal -->
    <div class="modal fade bd-example-modal-lg" id="finical_pay_note" tabindex="-1" role="dialog"
         aria-labelledby="finical_pay_noteLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">اعتماد المالية</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="form-group  col-sm-8">
                                <label> الملاحظة </label>
                                <div>
                                    <input type="text" name="note" value="-" id="txt_note_finical_pay" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <?php if (HaveAccess($adopt_url.'35') and $adopt == 30) { ?>
                        <button type="button" onclick="javascript:adopt(35);" class="btn btn-indigo">
                            <i class="fa fa-check"></i>اعتماد المالية</button>
                    <?php } ?>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">اغلاق</button>
                </div>
            </div>
        </div>
    </div>
    <!--END Modal Bootstrap -->

    <!--Modal NOTE Bootstrap  الغاء الاعتماد الارجاع الى المعد-->
    <!--Start Modal -->
    <div class="modal fade bd-example-modal-lg" id="CancelAdopt_note" tabindex="-1" role="dialog"
         aria-labelledby="CancelAdopt_notelLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">ملاحظة الإرجاع</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="col-md-12">
                        <div class="row">

                            <div class="form-group  col-sm-6">
                                <label> سبب الإرجاع </label>
                                <input type="text" name="note" value="" id="txt_CancelAdopt" class="form-control">
                            </div>

                            <div class="form-group col-md-6">
                                <label>المرحلة المراد الإرجاع إليها</label>
                                <select name="adopt" id="dp_adopt" class="form-control sel2">
                                    <option value="">_________</option>
                                    <?php foreach ($unadopt_cons as $row) : ?>
                                        <option value="<?= $row['CON_NO'] ?>"> <?= $row['CON_NAME'] ?> </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <?php if (HaveAccess($unadopt_url) and $adopt >= 2) { ?>
                        <button type="button" onclick="javascript:unadopt();" class="btn btn-danger"><span class="fa fa-angle-double-left"></span>
                            ارجاع</button>
                    <?php } ?>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">اغلاق</button>
                </div>

            </div>
        </div>
    </div>
    <!--END Modal Bootstrap -->

<?php
$scripts = <<<SCRIPT

<script type="text/javascript">

    $('.sel2:not("[id^=\'s2\']")').select2();
    var count = 0;
    $(function(){
        reBind(1);
    });
    
    if($edit == 0 ) {
        $('select[name="con_no"]').attr('readonly','readonly');
    }else if($edit == 1){
        $('.requests').show();
    }
    
    $('.clock').datetimepicker({
        format: 'YYYYMM',
        minViewMode: 'months',
        pickTime: false,
    });

    $('#dp_badl_typ').change(function(){
        change_con_no()
    });
    
    function change_con_no(){
        $('select[name="con_no"]').empty();
        $('select[name="con_no"]').removeAttr('readonly');
        $('select[name="con_no"]').select2();
        
        var badl_typ =  $('#dp_badl_typ').val();
        if (badl_typ == '') {
            return -1;
        } else
        get_data('{$get_badl_type}', {badl_typ: badl_typ}, function (data) {
             $('select[name="con_no"]').append($('<option/>').attr("value", '').text('_______'));
             $.each(data, function (i, option) {
                 var options = '';
                 options += '<option value="' + option.NO + '">' + option.NO + ": " +option.NAME + '</option>';
                 $('select[name="con_no"]').append(options);
             });
        });
    }
    
     //لجلب قيمة البند
    function get_val_constant(value_in){
   
        if (value_in){
            var val_constant = false;
            $.ajax({
                async: false,
                url:  '{$public_get_val_constant_url}',
                type: 'POST',
                data : {value:value_in},
                success: function (data) {
                    val_constant = data;
                }
            });
            return val_constant;
        }
    }
    
//لجلب تاريخ التقاعد للموظف    
    function get_retirement_date(emp_no_in){
   
        if (emp_no_in){
            var val_constant = false;
            $.ajax({
                async: false,
                url:  '{$public_get_retirement_date_url}',
                type: 'POST',
                data : {emp_no:emp_no_in},
                success: function (data) {
                    retirement_date = data;
                }
            });
            return retirement_date;
        }
    }
    
    //مجموع قيم الاقساط
    function total_instalment_val(){
        var Total = 0;
        
        $('#details_tb tbody .inst_val').each(function () {
            //find the combat elements in the current row and sum it
            val = $(this).closest('tr').find("input[name='txt_price_part[]']").val();
            if (!isNaN(val) && val.length !== 0) {
                Total += parseFloat(val);
            }
        });
        
        var final_sumTotal = Total.toFixed(2);
        $('#sum_total_instalment').html('<span class="btn btn-info">'+final_sumTotal+'</span>');
    }
    
    //مجموع المبالغ المضافة
    function total_install_val(){
        var Total = 0;
        
        $('#details_tb tbody .install_val').each(function () {
            //find the combat elements in the current row and sum it
            val = $(this).closest('tr').find("input[name='txt_install_value[]']").val();
            if (!isNaN(val) && val.length !== 0) {
                Total += parseFloat(val);
            }
        });
        
        var sumTotal = Total.toFixed(2);
        $('#sum_total_install').html('<span class="btn btn-info">'+sumTotal+'</span>');
    }
    
    //فرق الاشهر 
    function getMonthDifference(startDate, endDate) {
        return (endDate.getMonth() -startDate.getMonth() +12 * (endDate.getFullYear() - startDate.getFullYear()));
    }
    
    function reBind(s) {
        if (s == undefined) {
            s = 0;
        }
        if (s) {

            $('.clock').datetimepicker({
                format: 'YYYYMM',
                minViewMode: 'months',
                pickTime: false,
            });
            
            $('.sel22:not("[id^=\'s2\']")').select2();
            
            $('select[name="con_no"]').change(function(){
                var id =$(this).val();   
                var price = $('input[name="txt_install_value[]"]').val(get_val_constant(id));
                $('input[name="txt_install_value[]"]').keyup().change();

                if(id != ''){
                    $('.requests').fadeIn();
                }else{
                    $('.requests').fadeOut();
                }

                total_install_val();
                total_instalment_val();
            }); 
            
            $('select[name="calculation_type[]"]').change(function(){
                var id =$(this).val();
                var tr = $(this).closest('tr');
                show(tr,id);
                total_install_val();
                total_instalment_val();
            });
            
        }
    }

    var emp_no_cons_options = '{$emp_no_cons_options}';
    var calculation_type_options = '{$calculation_type_options}';
    
    //اضافة سجل جديد
    function addRow(){
        var rowCount = $('#details_tb tbody tr').length;
        if(rowCount == 0){
            count = count+1;
        }else {
            count = rowCount+1
        }
        var html ='<tr><td><input name="txt_bill_ser_det[]" id="txt_bill_ser_det'+count+'" class="form-control" value="0" style="text-align: center" readonly> </td>' +
         ' <td><select name="emp_no_det[]" id="emp_no_det'+count+'" class="form-control sel22" >'+emp_no_cons_options+'</select></td>' +
         ' <td><select name="calculation_type[]" id="calculation_type'+count+'" class="form-control">'+calculation_type_options+'</select></td>' +
         ' <td><input  name="txt_install_value[]"  class="form-control install_val" id="txt_install_value'+count+'"  onchange="total_install_val()" style="text-align: center" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))" /></td>' +
         ' <td><input  name="number_inst[]"  class="form-control" id="number_inst'+count+'" style="text-align: center" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))" /></td>' +
         '<td><input  name="txt_inst_month[]"  class="form-control" id="txt_inst_month'+count+'" value ="{$current_date}" readonly style="text-align: center" maxlength="6" /></td>' +
         ' <td><input  name="txt_to_month[]"  class="form-control" id="txt_to_month'+count+'" style="text-align: center" readonly maxlength="6" /></td>' +
         ' <td><input  name="txt_price_part[]"  class="form-control inst_val" id="txt_price_part'+count+'" onchange="total_instalment_val()" style="text-align: center" readonly /></td>' +
         ' <td class="text-center"><a onclick="javascript:remove_tr(this);" href="javascript:;" ><i class="fa fa-trash"></i></a></td>' +
         '</tr>';
        $('#details_tb tbody').append(html);
        reBind(count);
        total_install_val();
        total_instalment_val();
    }
    
    //حذف السجل من php قبل الترحيل
    function  remove_tr(obj){
        var tr = obj.closest('tr');
        $(tr).closest('tr').css('background','tomato');
        $(tr).closest('tr').fadeOut(800,function(){
        $(this).remove();
        total_install_val();
        total_instalment_val();
        });
    }
    
    //حذف السجل من Database
    function delete_row(a,id){
        if(confirm('هل تريد حذف السجل ؟')){
            get_data('{$delete_url}',{id:id},function(data){
                if(data == '1'){
                    success_msg('رسالة','تم حذف السجل بنجاح ..');
                    $(a).closest('tr').remove();
                    total_install_val();
                    total_instalment_val();
                }
            },'html');
        }
    }
    
    function show(tr,id){
        var calculation_type= $('select[name="calculation_type[]"]',tr);
        var txt_install_value= $('input[name="txt_install_value[]"]',tr);
        var emp_no_det= $('select[name="emp_no_det[]"]',tr);
        var emp_no_det_inp= $('input[name="emp_no_det[]"]',tr);
        var number_inst= $('input[name="number_inst[]"]',tr);
        var txt_inst_month= $('input[name="txt_inst_month[]"]',tr);
        var txt_to_month= $('input[name="txt_to_month[]"]',tr);
        var price_part= $('input[name="txt_price_part[]"]',tr);
        
        
            if(txt_install_value.val() == ''){
                txt_install_value.val(get_val_constant($('#dp_con_no').val()));
            }

    
        $('input[name="number_inst[]"]',tr).change(function(){
            var no_inst = number_inst.val();
            var from_month = txt_inst_month.val();
            var newDate = moment(from_month, "YYYYMM").add(no_inst-1, 'months').format('YYYYMM');
            
            if(number_inst.val() <=0){
                var newDate = moment(from_month, "YYYYMM").add(no_inst, 'months').format('YYYYMM');
                price_part.val('');
                total_instalment_val();
                danger_msg('يجب ان يكون عدد الاقساط اكبر من صفر');
                return 0;
            }
            
            txt_to_month.val(newDate);
            
                var value = txt_install_value.val()/number_inst.val(); 
            
            
            if(txt_install_value.val() <= 0){
                danger_msg('يجب ادخال المبلغ');
                return 0;
            }
            
            price_part.val(value.toFixed(2));
            total_instalment_val();

        });
    
        $('input[name="txt_install_value[]"]',tr).change(function(){
        
            if(id != 3){
                var value = txt_install_value.val()/number_inst.val();
            }else{
                var value = txt_install_value.val()/1;
            }
            
            if(number_inst.val() <= 0){
                 danger_msg('يجب ادخال عدد الاقساط');
                 return 0;
            }
            
            if(txt_install_value.val() <= 0){
                danger_msg('يجب ان يكون المبلغ اكبر من صفر');
                price_part.val(0);
                return 0;
            }
            
            if(id == 4){
                var month_no = (txt_install_value.val()/price_part.val()).toFixed(0);  
                var to_month = moment(txt_inst_month.val(), "YYYYMM").add(month_no-1, 'months').format('YYYYMM');
                number_inst.val(month_no);
                txt_to_month.val(to_month);

            }else{
                price_part.val(value.toFixed(2));
            }
      
            total_instalment_val();
        });
        
        if(id == 1){
            txt_inst_month.prop('readonly', true);
            txt_to_month.prop('readonly', true);
            number_inst.prop('readonly', true);
            number_inst.val(1);
            txt_inst_month.val('{$current_date}');
            txt_to_month.val('{$current_date}');
            price_part.val(txt_install_value.val());

        } else if (id == 2) {
            txt_inst_month.prop('readonly', true);
            txt_to_month.prop('readonly', true);
            number_inst.prop('readonly', false);
            price_part.val((txt_install_value.val()/number_inst.val()).toFixed(2));
            
            if(number_inst.val() <= 0){
                price_part.val('');
            }
            
            if($edit == 0 ) {
                number_inst.val('');
                txt_to_month.val('');
                price_part.val('');
            }


        } else if (id == 3) {
            txt_inst_month.prop('readonly', true);
            txt_to_month.prop('readonly', true);
            number_inst.prop('readonly', true);
            txt_inst_month.val('{$current_date}');
            if($edit == 0 ) {
                number_inst.val('');
            }
            price_part.val(txt_install_value.val());

            if(emp_no_det.val() != 0 ){
                var retirement_date;
                if(emp_no_det.val() != null){
                    retirement_date = get_retirement_date(emp_no_det.val());
                }else{
                    retirement_date = get_retirement_date(emp_no_det_inp.val());
                }
                txt_to_month.val(retirement_date+'12');
            }else{
                danger_msg('يجب اختيار اسم الموظف');
            }
             
            if(emp_no_det.val() != 0){
                var str_from_momth = txt_inst_month.val();
                var str_to_momth = txt_to_month.val();
                var res_from_year = str_from_momth.substr(0,4);
                var res_from_month = str_from_momth.substr(4);
                var res_to_year = str_to_momth.substr(0,4);
                var res_to_month = str_to_momth.substr(4);
                number_inst.val(getMonthDifference(new Date(res_from_year, res_from_month), new Date(res_to_year, res_to_month))+1);
            }

            $('select[name="emp_no_det[]"]',tr).change(function(){
                if(id == 3){
                    var retirement_date = get_retirement_date(emp_no_det.val());
                    txt_to_month.val(retirement_date+'12');
                    
                    var str_from_momth = txt_inst_month.val();
                    var str_to_momth = txt_to_month.val();
                    var res_from_year = str_from_momth.substr(0,4);
                    var res_from_month = str_from_momth.substr(4);
                    var res_to_year = str_to_momth.substr(0,4);
                    var res_to_month = str_to_momth.substr(4);
                    number_inst.val(getMonthDifference(new Date(res_from_year, res_from_month), new Date(res_to_year, res_to_month))+1);
                }
            });

        }else if (id == 4){
            txt_inst_month.prop('readonly', false);
            txt_to_month.prop('readonly', true);
            number_inst.prop('readonly', true);
            price_part.prop('readonly', false);
            
            if($edit == 0 ) {
                number_inst.val('');
                txt_to_month.val('');
                price_part.val('');
            }
            
            $('input[name="txt_inst_month[]"]',tr).change(function(){
                var no_inst = number_inst.val();
                var to_month = moment(txt_inst_month.val(), "YYYYMM").add(no_inst-1, 'months').format('YYYYMM');
                var str_from_momth = txt_inst_month.val() ;
                var res_from_month = str_from_momth.substr(4);
                price_part.val((txt_install_value.val()/number_inst.val()).toFixed(2));  
                if(res_from_month > 12){
                    danger_msg('الرجاء التأكد من ادخال التاريخ بالشكل الصحيح');
                    txt_inst_month.val('');
                    txt_to_month.val('');
                    price_part.val('');
                    return 0;
                }
                
                if(txt_inst_month.val() == ''){
                    danger_msg('يجب ادخال تاريخ بداية الاضافة');
                    return 0;
                }

                if(no_inst != ''){
                    txt_to_month.val(to_month);
                }else{
                    danger_msg('يجب ادخال قيمة القسط');
                    return 0;
                }
            });
            
            $('input[name="txt_price_part[]"]',tr).change(function(){
                var month_no = (txt_install_value.val()/price_part.val()).toFixed(1);  
                var to_month = moment(txt_inst_month.val(), "YYYYMM").add(month_no-1, 'months').format('YYYYMM');
                number_inst.val(month_no);
                txt_to_month.val(to_month);
                
                if(txt_inst_month.val() == ''){
                    danger_msg('يجب ادخال تاريخ بداية الاضافة');
                    return 0;
                }
            });

        }else{
            txt_inst_month.prop('readonly', true);
            txt_to_month.prop('readonly', true);
            number_inst.prop('readonly', false);
            txt_install_value.val('');
            number_inst.val('');
            txt_to_month.val('');
            price_part.val('');
        }
    }
    
    $(function() {
        $( "#details_tb tbody" ).sortable();
    });
    
    //اعتماد المدخل 2
    function open_Entry_2(){
        $('#Entry_note').modal('show');
    }
    
    //اعتماد المدير المالي للمقر 10
    function open_ChiefFinancial_10(){
        $('#ChiefFinancial_note').modal('show');
    }
    
    //اعتماد المراقب الداخلي 20
    function open_InternalObserver_20(){
        $('#InternalObserver_note').modal('show');
    }
    
    //اعتماد مدير المقر 30
    function open_HeadOffice_30(){
        $('#HeadOffice_note').modal('show');
    }
    
    //اعتماد المالية للصرف 35
    function open_Financial_pay_35(){
        $('#finical_pay_note').modal('show');
    }
    
    //إرجاع للمعد
    function open_CancelAdopt_0(){
        $('#CancelAdopt_note').modal('show');
    }
    
    function adopt(no){
        var action_desc= 'اعتماد';
        if (no == 2) {
            var note = $('#txt_note_entry').val();
        } else if (no == 10) {
            var note = $('#txt_note_ChiefFinancial').val();
        } else if (no == 20) {
            var note = $('#txt_note_InternalObserver').val();
        } else if (no == 30) {
            var note = $('#txt_note_HeadOffice').val();
        } else if (no == 35) {
            var note = $('#txt_note_finical_pay').val();
        } else {
            var note = '';
        }
        
        if(confirm(' هل تريد بالتأكيد اعتماد الطلب ؟')){
            get_data('{$adopt_url}'+no, {ser:'{$ser}',case:no,note:note} , function(ret){
                if(ret==1){
                    success_msg('رسالة','تمت عملية الاعتماد بنجاح ');
                    reload_Page();                        
                }else{
                    danger_msg('تحذير..',ret);
                }
            }, 'html');
        }
    }
    
    // الارجاع
    function unadopt(){
        var action_desc= 'ارجاع';
        var note = $('#txt_CancelAdopt').val();
        var adopt = $('#dp_adopt').val();
        
        if (note == '') {
            danger_msg('يرجى ادخال سبب الارجاع');
        }else if (adopt == '') {
            danger_msg('يرجى اختيار المرحلة المراد الإرجاع لها');
        }else {
            if(confirm(' هل تريد بالتأكيد إرجاع الطلب ؟')){
                get_data('{$unadopt_url}', {ser:'{$ser}',case:adopt,note:note} , function(ret){
                    if(ret==1){
                        success_msg('رسالة','تمت عملية الارجاع بنجاح ');
                        reload_Page();
                    }else{
                        danger_msg('لا يمكن الإرجاع لهذه المرحلة',ret);
                    }
                }, 'html');
            }
        }
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
                    get_to_link('{$get_url }/'+parseInt(data));
                }else if(data==1){
                    success_msg('رسالة','تم حفظ البيانات بنجاح ..');
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