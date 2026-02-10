<?php

$MODULE_NAME= 'hr_attendance';
$TB_NAME= 'Assigning_work_car';
$back_url=base_url("$MODULE_NAME/$TB_NAME/index");
$post_url= base_url("$MODULE_NAME/$TB_NAME/".($action == 'index'?'create':$action));
$get_url= base_url("$MODULE_NAME/$TB_NAME/get");
$car_adopt_url= base_url("$MODULE_NAME/$TB_NAME/car_adopt_");
$adopt_url= base_url("$MODULE_NAME/$TB_NAME/adopt_");
$manager_url= base_url("$MODULE_NAME/$TB_NAME/index/1/manager");

$gfc_domain= gh_gfc_domain();


$isCreate =isset($master_tb_data) && count($master_tb_data)  > 0 ?false:true;
$HaveRs = (!$isCreate)? true:false;
$rs=$isCreate ?array() : $master_tb_data[0];

$date_attr= " data-type='date' data-date-format='DD/MM/YYYY' data-val='true' data-val-regex-pattern='".date_format_exp()."' data-val-regex='Error' ";

$directions_number = array(1, 2, 3, 4, 5);
?>

<div class="row">
    <div class="toolbar">
        <div class="caption"><?=$title.(($HaveRs)?' - '.$rs['EMP_NO_NAME']:'')?></div>
        <ul>
            <?php if( 1 ):  ?><li><a  href="<?= $back_url ?>"><i class="icon icon-reply"></i> </a> </li><?php  endif; ?>
        </ul>
    </div>
</div>


<div class="form-body">

    <div id="msg_container"></div>
    <div id="container">
        <form class="form-vertical" id="<?=$TB_NAME?>_form" method="post" action="<?=$post_url?>" role="form" novalidate="novalidate">

            <div class="modal-body inline_form">

                <div class="form-group col-sm-1">
                    <label class="control-label">رقم السند </label>
                    <div>
                        <input type="text" value="<?=$HaveRs?$rs['SER']:''?>" readonly id="txt_ser" class="form-control" />
                        <?php if (( isset($can_edit)?$can_edit:false)) : ?>
                            <input type="hidden" value="<?=$HaveRs?$rs['SER']:''?>" name="ser" id="h_ser" />
                        <?php endif; ?>
                    </div>
                </div>


                <div class="form-group col-sm-2">
                    <label class="control-label">الموظف</label>
                    <div>
                        <select name="emp_no" id="dp_emp_no" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($emp_no_cons as $row) :?>
                                <option <?=$HaveRs?($rs['EMP_NO']==$row['EMP_NO']?'selected':''):''?> value="<?=$row['EMP_NO']?>" ><?=$row['EMP_NO'].': '.$row['EMP_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">تاريخ بداية التكليف</label>
                    <div>
                        <input type="text" <?=$date_attr?> value="<?=$HaveRs?$rs['ASS_START_DATE']:date('d/m/Y')?>" name="ass_start_date" id="txt_ass_start_date" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">ساعة بداية التكليف</label>
                    <div>
                        <input type="text" placeholder="<?=(strtoupper(date('l'))=='THURSDAY')?'15:00':'15:00'?>" value="<?=$HaveRs?$rs['ASS_START_TIME']:""?>" name="ass_start_time" id="txt_ass_start_time" class="form-control" />
                    </div>
                </div>


                <div class="form-group col-sm-1">
                    <label class="control-label">طبيعة العمل</label>
                    <div>
                        <input type="text" disabled value="<?=$HaveRs?$rs['EMP_WORK_NAME']:""?>" name="emp_work" id="txt_emp_work" class="form-control" />
                    </div>
                </div>

                <div style="clear: both"></div>


                <div class="form-group col-sm-1">
                    <label class="control-label">المسمى الوظيفي</label>
                    <div>
                        <input type="text" disabled value="<?=$HaveRs?$rs['EMP_JOB_TITLE_NAME']:""?>" name="emp_job_title" id="txt_emp_job_title" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">القسم/الدائرة</label>
                    <div>
                        <input type="text" disabled  value="<?=$HaveRs?$rs['EMP_HEAD_DEPARTMENT_NAME']:""?>" name="emp_department" id="txt_emp_department" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">تاريخ الادخال</label>
                    <div>
                        <input disabled type="text" data-type="date" data-date-format="DD/MM/YYYY" data-val="true" data-val-regex-pattern="<?=date_format_exp()?>" data-val-regex="Error" value="<?=$HaveRs?$rs['ENTRY_DATE']:date('d/m/Y')?>" name="p_date" id="txt_p_date" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">حالة السند</label>
                    <div>
                        <select name="adopt" id="dp_adopt" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($adopt_cons as $row) :?>
                                <option <?=$HaveRs?($rs['ADOPT']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-1">
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

                <div class="form-group col-sm-4">
                    <label class="control-label">ملاحظات</label>
                    <div>
                        <input type="text" value="<?=$HaveRs?$rs['NOTES']:""?>" name="notes" id="txt_notes" class="form-control" />
                    </div>
                </div>
                <br>
                <hr>
                <br>


                <?php if($HaveRs?$rs['CAR_REQUEST']:0 == 1){
                    $hidden='';
                    $checked ='checked';
                }else{
                    $hidden='hidden';
                    $checked ='';
                }
                ?>

                <!----------------طلب سيارة------------------->

                <div class="row">
                    <div class="form-group col-sm-4">
                        <label>طلب سيارة: </label>
                        <label><input type="radio"  <?=$HaveRs?($rs['CAR_REQUEST']==1)?'checked':'':''?>   class="checkboxes" value="1" onclick="showCarRequest()" name="car_request" > نعم</label>
                        <label><input type="radio" class="checkboxes"  <?=$HaveRs?($rs['CAR_REQUEST']==0)?'checked':'':''?>   value="0" onclick="hideCarRequest()" name="car_request" > لا</label>
                    </div>
                </div>

                <!--------------------------divCarRequest----------------------->

                <div id="div_car_request" <?=$hidden?> >

                    <div class="row">

                        <div

                        <div class="form-group col-sm-1">
                            <label class="control-label">نوع المهمة</label>
                            <div>
                                <select name="task_type" id="dp_task_type" class="form-control " required >
                                    <option value="">_________</option>
                                    <?php foreach($task_type as $row) :?>
                                        <option <?=$HaveRs?($rs['TASK_TYPE']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>


                        <div class="form-group col-sm-3">
                            <label class="control-label">وصف المهمة</label>
                            <div>
                                <input type="text" value="<?=$HaveRs?$rs['TASK_DESC']:""?>" name="task_desc" id="txt_task_desc" class="form-control"  />
                            </div>
                        </div>


                        <div class="form-group col-sm-1">
                            <label class="control-label">المحافظة</label>
                            <div>
                                <select name="governorate_id" id="dp_governorate_id" class="form-control " required>
                                    <option value="">_________</option>
                                    <?php foreach($governorate as $row) :?>
                                        <option <?=$HaveRs?($rs['GOVERNORATE_ID']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>


                        <div class="form-group col-sm-1">
                            <label class="control-label">عدد الاتجاهات</label>
                            <div>

                                <select required name="directions_no" class="form-control" id="dp_directions_no" >
                                    <option value="" >_________</option>

                                    <?php foreach($directions_number as $row) :?>
                                        <option  <?=$HaveRs?($rs['DIRECTIONS_NO']==$row)?'selected':'':''?>  value="<?=$row?>"><?=$row?></option>
                                    <?php endforeach; ?>

                                </select>
                            </div>
                        </div>


                        <div class="form-group col-sm-1">
                            <label class="control-label">الوجهة</label>
                            <div>
                                <select name="destination_type" id="dp_destination_type" class="form-control" required >
                                    <option value="">_________</option>
                                    <?php foreach($destination_type as $row) :?>
                                        <option <?=$HaveRs?($rs['DESTINATION_TYPE']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>


                        <div class="form-group col-sm-1">
                            <label class="control-label">عدد الركاب</label>
                            <div>
                                <input required type="text" value="<?=$HaveRs?$rs['PASSENGERS_NO']:""?>" name="passengers_no" id="txt_passengers_no" class="form-control" />
                            </div>
                        </div>

                    </div>

                    <div style="clear: both"></div>
                    <!--------------------------directions_no----------------------->


                    <div class="form-group col-sm-2">
                        <label class="control-label"> وقت المغادرة المتوقع </label>
                        <div>
                            <input type="text" placeholder="24:00" name="expected_leave_time" id="txt_expected_leave_time" data-val="true" value="<?=$HaveRs?$rs['EXPECTED_LEAVE_TIME']:""?>" class="form-control">
                        </div>
                    </div>


                    <div class="form-group col-sm-2">
                        <label class="control-label">وقت الوصول المتوقع</label>
                        <div>
                            <input type="text" placeholder="24:00" name="expected_arrival_time" id="txt_expected_arrival_time" data-val="true" value="<?=$HaveRs?$rs['EXPECTED_ARRIVAL_TIME']:""?>" class="form-control">
                        </div>
                    </div>

                    <div style="clear: both"></div>


                    <div class="form-group col-sm-2">
                        <label class="control-label">عنوان الانطلاق</label>
                        <div>
                            <input type="text" name="from_address" id="txt_from_address" data-val="true" value="<?=$HaveRs?$rs['FROM_ADDRESS']:""?>" class="form-control ">
                        </div>
                    </div>

                    <div class="form-group col-sm-1">
                        <label class="control-label">موقع الانطلاق </label>
                        <div>
                            <input type="hidden" name="start_gps" id="txt_start_gps" class="form-control " value="<?=$HaveRs?$rs['START_GPS']:""?>" >
                            <button type="button" class="btn green" onclick="_showReport('<?= base_url('maps') ?>/public_map_location/txt_start_gps');reloadReport();">
                                <i class="icon icon-map-marker"></i>
                            </button>
                        </div>
                    </div>


                    <div style="clear: both"></div>


                    <div class="form-group col-sm-2 destinations destination_1">
                        <label class="control-label">عنوان الوجهة (1)</label>
                        <div>
                            <input type="text" name="destination_address_1" id="txt_destination_address_1" data-val="true" value="<?=$HaveRs?$rs['DESTINATION_ADDRESS_1']:""?>" class="form-control">
                        </div>
                    </div>

                    <div class="form-group col-sm-1 destinations destination_1" >
                        <label class="control-label">موقع الوجهة </label>
                        <div>
                            <input type="hidden" name="destination_gps_1" id="txt_destination_gps_1" value="<?=$HaveRs?$rs['DESTINATION_GPS_1']:""?>" class="form-control ">
                            <button type="button" class="btn green" onclick="_showReport('<?= base_url('maps') ?>/public_map_location/txt_destination_gps_1');reloadReport();">
                                <i class="icon icon-map-marker"></i>
                            </button>
                        </div>
                    </div>


                    <div style="clear: both"></div>

                    <div class="form-group col-sm-2 destinations destination_2">
                        <label class="control-label">عنوان الوجهة (2)</label>
                        <div>
                            <input type="text" name="destination_address_2" id="txt_destination_address_2" data-val="true" value="<?=$HaveRs?$rs['DESTINATION_ADDRESS_2']:""?>" class="form-control">
                        </div>
                    </div>

                    <div class="form-group col-sm-1 destinations destination_2">
                        <label class="control-label">موقع الوجهة </label>
                        <div>
                            <input type="hidden" name="destination_gps_2" id="txt_destination_gps_2" value="<?=$HaveRs?$rs['DESTINATION_GPS_2']:""?>" class="form-control ">
                            <button type="button" class="btn green" onclick="_showReport('<?= base_url('maps') ?>/public_map_location/txt_destination_gps_2');reloadReport();">
                                <i class="icon icon-map-marker"></i>
                            </button>
                        </div>
                    </div>

                    <div style="clear: both"></div>


                    <div class="form-group col-sm-2 destinations destination_3">
                        <label class="control-label">عنوان الوجهة (3)</label>
                        <div>
                            <input type="text" name="destination_address_3" id="txt_destination_address_3" data-val="true" value="<?=$HaveRs?$rs['DESTINATION_ADDRESS_3']:""?>" class="form-control">
                        </div>
                    </div>

                    <div class="form-group col-sm-1 destinations destination_3">
                        <label class="control-label">موقع الوجهة </label>
                        <div>
                            <input type="hidden" name="destination_gps_3" id="txt_destination_gps_3" value="<?=$HaveRs?$rs['DESTINATION_GPS_3']:""?>" class="form-control ">
                            <button type="button" class="btn green" onclick="_showReport('<?= base_url('maps') ?>/public_map_location/txt_destination_gps_3');reloadReport();">
                                <i class="icon icon-map-marker"></i>
                            </button>
                        </div>
                    </div>

                    <div style="clear: both"></div>


                    <div class="form-group col-sm-2 destinations destination_4">
                        <label class="control-label">عنوان الوجهة (4)</label>
                        <div>
                            <input type="text" name="destination_address_4" id="txt_destination_address_4" data-val="true" value="<?=$HaveRs?$rs['DESTINATION_ADDRESS_4']:""?>" class="form-control">
                        </div>
                    </div>

                    <div class="form-group col-sm-1 destinations destination_4">
                        <label class="control-label">موقع الوجهة </label>
                        <div>
                            <input type="hidden" name="destination_gps_4" id="txt_destination_gps_4" value="<?=$HaveRs?$rs['DESTINATION_GPS_4']:""?>" class="form-control ">
                            <button type="button" class="btn green" onclick="_showReport('<?= base_url('maps') ?>/public_map_location/txt_destination_gps_4');reloadReport();">
                                <i class="icon icon-map-marker"></i>
                            </button>
                        </div>
                    </div>

                    <div style="clear: both"></div>


                    <div class="form-group col-sm-2 destinations destination_5">
                        <label class="control-label">عنوان الوجهة (5)</label>
                        <div>
                            <input type="text" name="destination_address_5" id="txt_destination_address_5" data-val="true" value="<?=$HaveRs?$rs['DESTINATION_ADDRESS_5']:""?>" class="form-control">
                        </div>
                    </div>

                    <div class="form-group col-sm-1 destinations destination_5">
                        <label class="control-label">موقع الوجهة </label>
                        <div>
                            <input type="hidden" name="destination_gps_5" id="txt_destination_gps_5" value="<?=$HaveRs?$rs['DESTINATION_GPS_5']:""?>" class="form-control ">
                            <button type="button" class="btn green" onclick="_showReport('<?= base_url('maps') ?>/public_map_location/txt_destination_gps_5');reloadReport();">
                                <i class="icon icon-map-marker"></i>
                            </button>
                        </div>
                    </div>


                </div>
                <!-------------------------------------------------------------->
            </div>



            <!-------------------------------------------------------------->
            <div id="myModal" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 id="title" class="modal-title">سبب الغاءالمهمة</h3>
                        </div>

                            <form class="form-horizontal" id="<?= $TB_NAME ?>_form" method="post" action="<?=$car_adopt_url?>" role="form" >
                            <div class="modal-body">

                                <div class="row">
                                    <br>
                                    <label class="col-sm-1 control-label">سبب الالغاء</label>
                                    <div class="col-sm-9">

                                        <select data-val="true" name="cancel_reason" id="dp_cancel_reason" data-val-required="حقل مطلوب"  class="form-control" required>

                                            <option></option>
                                            <?php foreach ($cancel_reason as $row) : ?>
                                                <option value="<?= $row['CON_NO'] ?>"  ><?= $row['CON_NAME'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <span class="field-validation-valid" data-valmsg-for="cancel_reason" data-valmsg-replace="true"></span>
                                    </div>
                                </div>
                                <br>
                                <div class="modal-footer">
                                    <button type="button" id="btn_cancel_fun" onclick='javascript:cancel_fun();' class="btn btn-primary">حفظ البيانات</button>
                                    <button type="button"  onclick='javascript:close_fun();' class="btn btn-danger">إغلاق</button>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>


            <div class="modal-footer">

                <?php if ( HaveAccess($car_adopt_url.'10') and $rs['CAR_ADOPT']==null and $rs['ADOPT']>=10 ) : ?>
                    <button type="button" id="btn_car_adopt_10" onclick='javascript:car_adopt_(10);' class="btn btn-success">تأكيد المهمة</button>
                <?php endif; ?>

                <?php if ( HaveAccess($car_adopt_url.'0') and $rs['CAR_ADOPT']==null and $rs['ADOPT']>=10 ) :  ?>
                    <button type="button" onclick='javascript:cancel_modal();'  class="btn btn-danger">الغاء المهمة </button>
                <?php endif; ?>

            </div>

        </form>
    </div>
</div>


<?php
$scripts = <<<SCRIPT
<script>

    rebind();
    
    function reloadReport(){
    
        setTimeout(function () {
            reloadIframeOfReport();
        }, 200);
    }


    function rebind(){
        $('.sel2:not("[id^=\'s2\']")').select2();
        $('.destinations').hide();
        
    
        var directions_no= $('#dp_directions_no').val();
        if( directions_no > 0 ){
            for( var i=1; i<= directions_no ; i++ ){
                $('.destination_'+i).show();
            }
        }
       
        if(typeof hhmm_decimal_to_time !== 'undefined'){
            $('#txt_calc_duration_hhmm').val( hhmm_decimal_to_time( $('#txt_calc_duration').val() ) ) ;
        }
    } // rebind

    $('#dp_adopt, #dp_branch_id, #dp_food_no').select2('readonly',1);


    
    $('#txt_ass_start_time').change(function(){
        if($(this).val() < '12:30'){
            alert('ساعة بداية التكليف يتم ادخالها بنظام 24، اي ان الساعة 2 م يتم ادخالها 14:00 ');
        }
    });

    
    
    $('#txt_ass_start_time').change(function(){
        if($(this).val() < '12:30'){
            alert('ساعة بداية التكليف يتم ادخالها بنظام 24، اي ان الساعة 2 م يتم ادخالها 14:00 ');
        }
    });

      
    function cancel_modal(){     
       $('#myModal').modal();
    }
    
   function close_fun(){
       $('#myModal').modal('hide');
       $('#dp_cancel_reason').val('');
   }

   var btn__= '';
   
    function cancel_fun(){
        
       var cancel_reason_no = $('#dp_cancel_reason').val();
        if( cancel_reason_no >= 1 ){
			btn__ = $('#btn_cancel_fun');
            car_adopt_(0);
       }else {
            info_msg('ملاحظة','يجب تحديد سبب الغاء المهمة..');
       }
       
    }


    $('#dp_directions_no').change(function(){
    
        var directions_no = $(this).val();
            $('.destinations').hide(); 
        
        if( directions_no == 1 ){
            $('.destination_1').show();
            $('#txt_destination_address_2,#txt_destination_gps_2,#txt_destination_address_3,#txt_destination_gps_3,#txt_destination_address_4,#txt_destination_gps_4,#txt_destination_address_5,#txt_destination_gps_5').val('');
        }    
        else if(directions_no == 2){
            $('.destination_1,.destination_2').show();
            $('#txt_destination_address_3,#txt_destination_gps_3,#txt_destination_address_4,#txt_destination_gps_4,#txt_destination_address_5,#txt_destination_gps_5').val('');
        }   
        else if(directions_no == 3){
            $('.destination_1,.destination_2,.destination_3').show();
            $('#txt_destination_address_4,#txt_destination_gps_4,#txt_destination_address_5,#txt_destination_gps_5').val('');
        }   
        else if(directions_no == 4){
            $('.destination_1,.destination_2,.destination_3,.destination_4').show();
            $('#txt_destination_address_5,#txt_destination_gps_5').val('');
        }
        else if(directions_no == 5){
            $('.destination_1,.destination_2,.destination_3,.destination_4,.destination_5').show();
        }
        else {
            $('#txt_destination_address_1,#txt_destination_gps_1,#txt_destination_address_2,#txt_destination_gps_2,#txt_destination_address_3,#txt_destination_gps_3,#txt_destination_address_4,#txt_destination_gps_4,#txt_destination_address_5,#txt_destination_gps_5').val('');
        }
    
    });

    
    function showCarRequest(){
     $('#div_car_request').show();  
    }

    function hideCarRequest(){
        $('#div_car_request').hide();
        $('#txt_expected_leave_time').val('');
        $('#txt_expected_arrival_time').val('');
        $('#txt_from_address').val('');
        $('#txt_to_address').val('');
        $('#txt_start_gps').val('');
        $('#txt_finished_gps').val('');
        
        $('#dp_task_type').val('');
        $('#txt_task_desc').val('');
        $('#dp_governorate_id').val('');
        $('#dp_directions_no').val('');
        $('#dp_destination_type').val('');
        $('#txt_passengers_no').val('');
    }

  
SCRIPT;

if($isCreate){
    $scripts = <<<SCRIPT
    {$scripts}

    $('#dp_emp_no').select2('val','{$emp_no_selected}');

    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
    }


    </script>
SCRIPT;

}else{
    $scripts = <<<SCRIPT
    {$scripts}


    function car_adopt_(no){
    
        if(no==10 ) var msg= 'هل تريد تأكيد المهمة ؟!';
        if(no==0 ) msg= 'هل تريد الغاء المهمة بشكل نهائي ؟!';
       
        if(confirm(msg)){
            var values= {ser: "{$rs['SER']}", cancel_reason:$('#dp_cancel_reason').val() };
            get_data('{$car_adopt_url}'+no, values, function(ret){
                if(ret==1){
                    success_msg('رسالة','تمت العملية بنجاح..');
                    $('button').attr('disabled','disabled');
					
					var emp_email = '{$rs["EMP_EMAIL"]}';
					var sub= 'الغاء طلب سيارة';
					var text= 'تم الغاء طلب سيارة لمهمة عمل رقم '+'{$rs["SER"]}' + ' - ' + $('#dp_cancel_reason option:selected').text() ;
                    _send_mail(btn__, emp_email ,sub,text);
					btn__= '';
					
                    setTimeout(function(){
                        get_to_link(window.location.href);
                    },2000);
                }else{
                    danger_msg('تحذير..',data);
                }
            }, 'html');
        }else{
         $('#myModal').modal('hide');
        }
    }


    </script>
SCRIPT;
}

sec_scripts($scripts);

?>