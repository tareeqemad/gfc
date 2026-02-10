<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 15/07/20
 * Time: 08:52 ص
 */


$MODULE_NAME= 'training';
$TB_NAME= 'employeeTraining';
$save_url = base_url("$MODULE_NAME/$TB_NAME/updateAttendance");

$count=0;
if (!isset($result))
    $result = array();
$HaveRs = count($result) > 0;
$rs = $HaveRs ? $result[0] : $result;
if (!isset($details_date))
    $details_date = array();
$HaveRs_date = count($details_date) > 0;
$rs_date = $HaveRs_date ? $details_date[0] : $details_date;

?>

<div class="row">
        <div class="toolbar">
            <div class="caption"><?= $title ?></div>
            <ul>
            </ul>
        </div>
</div>

 <div class="modal-body inline_form">
	<fieldset class="field_set">
		<legend>بيانات الدورة</legend>
		<br>
		<input type="hidden" name="date_no_" id="txt_date_no_" value="<?=$id?>" >
		<input type="hidden" name="course_no_" id="txt_course_no_" value="<?=$course_no?>" >
		
		<div class="form-group">
			<label class="col-sm-1 control-label">رقم الدورة</label>
			<div class="col-sm-2">
				<input type="text" readonly name="course_no"
					value="<?= $HaveRs ? $rs['COURSE_NO'] : "" ?>"
					id="txt_course_no" class="form-control">
			</div>
		</div>
		<br><br>

		<div class="form-group">
			<label class="col-sm-1 control-label">اسم الدورة/ اللغة العربية</label>
			<div class="col-sm-2">
				<input type="text"  name="course_name"
					value="<?= $HaveRs ? $rs['COURSE_NAME'] : "" ?>"
					id="txt_course_name" class="form-control">
			</div>
			
			<label class="col-sm-1 control-label">اسم الدورة/ اللغة الانجليزية</label>
			<div class="col-sm-2">
				<input type="text"  name="course_name_eng"
					value="<?= $HaveRs ? $rs['COURSE_NAME_ENG'] : "" ?>"
					id="txt_course_name_eng" class="form-control">
			</div>
			
			<label class="col-sm-1 control-label">تاريخ لقاء الدورة</label>
			<div class="col-sm-2">
				<input type="text"  name="day"
					value="<?= $HaveRs_date ? $rs_date['DAY'] : "" ?>"
					id="txt_day" class="form-control">
			</div>
		</div>
		<br><br>
	</fieldset>
	
<fieldset class="field_set">
	<legend>كشف الحضور والانصراف</legend>	
	<br><br>
<div class="tb_container">
    <table class="table" id="details_tb2" data-container="container"  align="center">
        <thead>
        <tr>
            <th style="width: 3%">رقم المسلسل</th>
            <th style="width: 8%">الرقم الوظيفي</th>
            <th style="width: 15%">اسم الموظف</th>
            <th style="width: 15%">اسم الموظف / الانجليزية</th>
            <th style="width: 10%">الادارة</th>
            <th style="width: 8%">المقر</th>
            <th>حضور/ غياب</th>
            <th style="width: 10%">ساعة الحضور</th>
            <th style="width: 10%">ساعة الانصراف</th>
            <th style="width: 20%">ملاحظات</th>
            <th style="width: 2%"></th>


        </tr>
        </thead>
        <tbody>
        <?php  if(count($details_trainee) > 0) {
            $count = -1;
            foreach($details_trainee as $row_trainee) {
                ++$count+1;
                ?>
                <tr>
                    <td>
                        <input type="text" readonly name="" id="txt_ser8_<?=$count?>"
                               class="form-control" value="<?=$count+1?>">
                        <input  type="hidden" name="h_course_ser4[]"   id="h_txt_course_ser4_<?=$count?>"
                                value="<?=$row_trainee['COURSE_SER']?>"
                                data-val="false" data-val-required="required" >

                        <input type="hidden" name="seq14[]" value="<?=$row_trainee['SER']?>"
                               id="seq14_id_<?=$count?>"  />

                        <input type="hidden" name="h_count4[]" id="h_count4_<?=$count?>"  />
                    </td>

                    <td>
                        <input type="text" readonly name="emp_no" id="txt_emp_no_<?=$count?>"
                               class="form-control" value="<?=$row_trainee['EMP_NO']?>">
                    </td>
                    <td>
                        <input type="text" data-val="true" readonly
                               value="<?=$row_trainee['USER_NAME']?>"
                               name="user_name[]" id="txt_user_name_<?=$count?>"
                               class="form-control" >
                    </td>

                    <td>
                        <input type="text" data-val="true" readonly
                               value="<?=$row_trainee['EMP_NAME_ENG']?>"
                               name="emp_name_eng[]" id="txt_emp_name_eng_<?=$count?>"
                               class="form-control" >
                    </td>

                    <td >
                        <input type="text" data-val="true" readonly
                               value="<?=$row_trainee['STRUCTURE_NAME']?>"
                               name="structure_name[]" id="txt_structure_name_<?=$count?>"
                               class="form-control " >
                    </td>

                    <td >
                        <input type="text" data-val="true" readonly
                               value="<?=$row_trainee['BRANCH_NAME']?>"
                               name="branch_name[]" id="txt_branch_name_<?=$count?>"
                               class="form-control" >
                    </td>

                    <td><input type="checkbox" value="1" class="checkbox emp-train" <?=$row_trainee['ATTENDANCE'] == 1 || $row_trainee['ATTENDANCE']== null ? "checked" : "" ?>
                               name="attendance_check" id="btn_attendance_<?=$count?>">
                    </td>

					<td>
                        <input type="text" data-val="true" 
                               value="<?php if($row_trainee['START_HOUR'] == null){ echo $rs_date['START_HOUR']; }else{ echo $row_trainee['START_HOUR']; }?>"
                               name="start_time[]" id="txt_start_time_<?=$count?>"
                               class="form-control" >
                        <span  name="errorSec[]" class="hidden" id="errorSec_<?=$count?>" style="color: #dd0601">قم بادخال ساعة الحضور</span>
                    </td>

					<td >
                        <input type="text" data-val="true" 
                               value="<?php if($row_trainee['END_HOUR'] == null){ echo $rs_date['END_HOUR']; }else{ echo $row_trainee['END_HOUR']; }?>"
                               name="end_time[]" id="txt_end_time_<?=$count?>"
                               class="form-control" >
                        <span  name="errorSec[]" class="hidden" id="errorSec_<?=$count?>" style="color: #dd0601">قم بادخال ساعة الانصراف</span>
                    </td>

					<td >
                        <input type="text" data-val="true" 
                               value="<?=$row_trainee['NOTES']?>"
                               name="notes[]" id="txt_notes_<?=$count?>"
                               class="form-control" >
                    </td>

                    <td >
                        <?php if (HaveAccess($save_url)): ?>
                            <button type="button"
                                onclick="javascript:saveEmpAttendance(<?php if($row_trainee['SER_ATT'] == null){echo 0;}else{echo $row_trainee['SER_ATT'];}?> , <?= $count?> ,<?=$row_trainee['EMP_NO']?>);"
                                id="showTrainee-btn" class="btn btn-success btn-xs">حفظ </button>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php
            }
        }
        ?>
        </tbody>
    </table>
</div>

</fieldset>
</div>
