<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

$MODULE_NAME= 'hr_attendance';
$TB_NAME= 'Assigning_work_car';

$isCreate =isset($master_tb_data) && count($master_tb_data)  > 0 ?false:true;
$HaveRs = (!$isCreate)? true:false;
$rs=$isCreate ?array() : $master_tb_data[0];

$report_url = base_url("JsperReport/showreport?sys=financial");
?>

<?= AntiForgeryToken(); ?>

<div class="row">

    <div class="toolbar">
        <div class="caption"><?= $title ?></div>
    </div>

    <div class="form-body">

        <fieldset>
            <legend>التقارير</legend>
            <div class="modal-body inline_form">
                <div class="form-group rp col-sm-3" id="rep_id">
                    <label class="control-label">التقرير</label>
                    <div>
                        <select name="rep_id" class="form-control" id="dp_rep_id">
                            <option value="0">_______________</option>
                            <option value="1">1- تكاليف بحاجة الى سيارة</option>
                            <option value="2">2- المهام التي طلبت من كل ادارة </option>
                            <option value="3">3- المهام الملغاه من قبل قسم الحركة </option>
                            <option value="4">4- المهام المسندة لكل سيارة </option>
                            <option value="5">5- تحركات السائقين </option>
                        </select>
                    </div>
                </div>
            </div>
        </fieldset>

        <fieldset>
            <legend>معايير البحث</legend>

            <div class="modal-body inline_form">

                <div class="form-group rp col-sm-2 op" id="dp_emp_no_div" style="display:none" >
                    <label class="control-label ">الموظف</label>
                    <div>
                        <select name="emp_no" id="dp_emp_no" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($emp_no_cons as $row) :?>
                                <option value="<?=$row['EMP_NO']?>"><?=$row['EMP_NO'].': '.$row['EMP_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group rp col-sm-2 op" id="txt_driver_id_div" style="display:none" >
                    <label class="control-label"> رقم السائق </label>
                    <div>
                        <input type="text"  name="driver_id"   id="txt_driver_id" class="form-control " "="">
                    </div>
                </div>

                <div class="form-group rp col-sm-2 op" id="dp_emp_department_div" style="display:none">
                    <label class="control-label">القسم/الدائرة</label>
                    <div>
                        <select name="emp_department" id="dp_emp_department" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($emp_department as $row) :?>
                                <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>


                <div class="form-group rp col-sm-2 op" id="dp_car_adopt_div" style="display:none">
                    <label class="control-label">حالة الحركة</label>
                    <div>
                        <select name="car_adopt" id="dp_car_adopt" class="form-control" >
                            <option value="">_________</option>
                            <?php foreach($car_adopt_cons as $row) :?>
                                <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>


                <div class="form-group rp col-sm-2 op" id="dp_branch_id_div" style="display:none" >
                    <label class="control-label">المقر </label>
                    <div>
                        <select name="branch_id" id="dp_branch_id" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($branches as $row) :?>
                                <option <?=$HaveRs?($rs['BRANCH_ID']==$row['NO']?'selected':''):''?> value="<?=$row['NO']?>" ><?=$row['NAME']?></option>
                            <?php endforeach; ?>

                        </select>
                    </div>
                </div>

                <div class="form-group rp col-sm-2 op" id="dp_driver_name_div" style="display:none">
                    <label class="control-label"> اسم السائق </label>
                    <div>
                        <select name="driver_name" id="dp_driver_name" class="form-control sel2">
                            <option></option>
                            <?php foreach ($driver as $row) : ?>
                                <option value="<?= $row['NO'] ?>"><?php echo $row['NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group rp col-sm-2 op" id="dp_car_type_div" style="display:none">
                    <label class="control-label"> نوع السيارة  </label>
                    <div>
                        <select data-val="true" name="car_type" id="dp_car_type" class="form-control">
                            <option></option>
                            <?php foreach ($car_type as $row) : ?>
                                <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group rp col-sm-2 op" id="from_date" style="display:none ;">
                    <label class="control-label">من تاريخ</label>
                    <div>
                        <input type="text" data-type="date" name="from_date" data-date-format="DD/MM/YYYY" id="txt_from_date" class="form-control" required>
                    </div>
                </div>

                <div class="form-group rp col-sm-2 op" id="to_date" style="display:none ;">
                    <label class="control-label">إلى تاريخ</label>
                    <div>
                        <input type="text" data-type="date" name="to_date" data-date-format="DD/MM/YYYY" id="txt_to_date" class="form-control" required>

                    </div>
                </div>


                <br/><br/>

                <div class="form-group rp col-sm-2 op" id="rep_type" style="display:none ;">
                    <label class="control-label">نوع التقرير</label>
                    <div>
                        <input type="radio"  name="rep_type_id" value="pdf" checked="checked">
                        <i class="fa fa-file-pdf-o" style="font-size:28px;color:#e2574c"></i>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="radio"  name="rep_type_id" value="xls">
                        <i class="fa fa-file-excel-o" style="font-size:28px;color:#1d7044"></i>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <input type="radio"  name="rep_type_id" value="doc">
                        <i class="fa fa-file-word-o" style="font-size:28px;color:#2a5696"></i>
                    </div>
                </div>

            </div>

        </fieldset>

        <div class="modal-footer">
            <button type="button" onclick="javascript:print_report();" class="btn btn-success">عرض التقرير<span class="glyphicon glyphicon-print"></span></button>
            <button type="button" onclick="javascript:clear_form();"  class="btn btn-default">إفراغ الحقول</button>
        </div>

    </div>

</div>

<?php
$scripts = <<<SCRIPT
<script>

    $('.sel2').select2();

    if ('{$emp_branch_selected}' != 1){
       $('#dp_branch_id').select2('val','{$emp_branch_selected}');
       $('#dp_branch_id').select2('readonly','{$emp_branch_selected}');
    }else { 
        $('#dp_branch_id').select2('val','{$emp_branch_selected}');
    }
    
    function clear_form(){
        clearForm_any('.row');
         $('.sel2').select2('val','');
    }


    $('#dp_rep_id').change(function() {
        showOptions();
    });

    function showOptions(){
        var id=$('#dp_rep_id').val();
        switch(id) {
        case "0":
            $(".op").fadeOut();
            break;
        case "1":
            $(".op").fadeOut();
            $("#to_date,#from_date,#rep_type,#dp_emp_no_div,#dp_branch_id_div,#dp_car_adopt_div").fadeIn();
            break;
        case "2":
            $(".op").fadeOut();
            $("#to_date,#from_date,#rep_type,#dp_emp_no_div,#dp_branch_id_div,#dp_emp_department_div").fadeIn();
        break;
        
        case "3":
            $(".op").fadeOut();
            $("#to_date,#from_date,#rep_type,#dp_emp_no_div,#dp_branch_id_div,#dp_emp_department_div").fadeIn();
        break; 
        
        case "4":
            $(".op").fadeOut();
            $("#to_date,#from_date,#rep_type,#dp_car_type_div,#txt_driver_id_div,#dp_emp_no_div").fadeIn();
        break;
        case "5":
            $(".op").fadeOut();
            $("#to_date,#from_date,#rep_type,#dp_driver_name_div,#dp_branch_id_div").fadeIn();
        break;        

        }
    }

    function getReportUrl(){
        
        var id=$('#dp_rep_id').val();
        var from_date = $('#txt_from_date').val();
        var to_date = $('#txt_to_date').val();
        var emp_no = $('#dp_emp_no').val();
        var emp_name = $('#txt_emp_name').val();
        var branch_id = $('#dp_branch_id').val();
        var car_adopt = $('#dp_car_adopt').val();
        
        var driver_id = $('#txt_driver_id').val();
        var car_type = $('#dp_car_type').val();
        var emp_department = $('#dp_emp_department').val();
        var driver_name = $('#dp_driver_name').val();
        
        var rep_type = $('input[name=rep_type_id]:checked').val();
        var prev_day = AddDays(from_date,-1);

        var repUrl;
        switch(id) {
            case "1":
                repUrl = '{$report_url}/cars_management&report_type='+rep_type+'&report=assigning_work_car&p_date_from='+from_date+'&p_date_to='+to_date+'&p_date_prev_from='+prev_day+''+'&p_emp_no='+emp_no+'&p_branch_id='+branch_id+'&p_car_adopt='+car_adopt;
                break;
                
            case "2":
                repUrl = '{$report_url}/cars_management&report_type='+rep_type+'&report=management_requests&p_date_prev_from='+prev_day+''+'&p_emp_no='+emp_no+'&p_branch_id='+branch_id+'&p_emp_department='+emp_department+'&p_date_from='+from_date+'&p_date_to='+to_date;
                break;
                                    
            case "3":
                repUrl = '{$report_url}/cars_management&report_type='+rep_type+'&report=cancel_reason&p_date_from='+from_date+'&p_date_to='+to_date+'&p_date_prev_from='+prev_day+''+'&p_emp_no='+emp_no+'&p_branch_id='+branch_id+'&p_emp_department='+emp_department;
                break;
                
            case "4":
                repUrl = '{$report_url}/cars_management&report_type='+rep_type+'&report=assigned_company_car&p_date_prev_from='+prev_day+''+'&p_emp_no='+emp_no+'&p_car_type='+car_type+'&p_driver_id='+driver_id+'&p_date_from='+from_date+'&p_date_to='+to_date;
                break;
                
            case "5":
                repUrl = '{$report_url}/cars_management&report_type='+rep_type+'&report=Cars_movement&p_date_prev_from='+prev_day+''+'&p_driver_name='+driver_name+'&p_date_from='+from_date+'&p_date_to='+to_date+'&p_branch_id='+branch_id;
                break;
        }
        return repUrl;
    }

    function print_report(){
            var rep_url = getReportUrl();
            _showReport(rep_url);
    }
    
</script>

SCRIPT;

sec_scripts($scripts);



?>
