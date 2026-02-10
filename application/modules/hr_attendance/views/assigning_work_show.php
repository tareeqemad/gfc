<?php

/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 05/06/18
 * Time: 01:52 م
 */

$MODULE_NAME= 'hr_attendance';
$TB_NAME= 'assigning_work';
$back_url=base_url("$MODULE_NAME/$TB_NAME/index");
$post_url= base_url("$MODULE_NAME/$TB_NAME/".($action == 'index'?'create':$action));
$get_url= base_url("$MODULE_NAME/$TB_NAME/get");
$adopt_url= base_url("$MODULE_NAME/$TB_NAME/adopt_");
$manager_url= base_url("$MODULE_NAME/$TB_NAME/index/1/manager");

$gfc_domain= gh_gfc_domain();


$isCreate =isset($master_tb_data) && count($master_tb_data)  > 0 ?false:true;
$HaveRs = (!$isCreate)? true:false;
$rs=$isCreate ?array() : $master_tb_data[0];

$date_attr= " data-type='date' data-date-format='DD/MM/YYYY' data-val='true' data-val-regex-pattern='".date_format_exp()."' data-val-regex='Error' ";

// اذا استخدم الموظف العادي شاشة المدير يتم منعه
if($isCreate && count($emp_no_cons) <= 1 && $page_act=='manager'){
    $emp_no_cons= array();
}

$directions_number = array(1, 2, 3, 4, 5);
/*
if($page_act!='move_admin' and $isCreate){
    echo "<div style='font-size: x-large; padding: 30px; color: #CC0000'>";
    echo "ادخال التكاليف طرف الشؤون الادارية فقط.."; die;
}
*/
?>

<div class="row">
    <div class="toolbar">
        <div class="caption"><?=$title.(($HaveRs)?' - '.$rs['EMP_NO_NAME']:'')?></div>
        <ul>
            <?php if( 0 and HaveAccess($back_url)):  ?><li><a  href="<?= $back_url ?>"><i class="icon icon-reply"></i> </a> </li><?php  endif; ?>
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

                    <input type="hidden" name="page_act" value="<?=$page_act?>" />

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

                <div class="form-group col-sm-2">
                    <label class="control-label">تاريخ نهاية التكليف</label>
                    <div>
                        <input readonly type="text" <?=($page_act=='move_admin')?$date_attr:''?> value="<?=$HaveRs?$rs['ASS_END_DATE']:""?>" name="ass_end_date" id="txt_ass_end_date" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">ساعة نهاية التكليف</label>
                    <div>
                        <input readonly type="text" value="<?=$HaveRs?$rs['ASS_END_TIME']:""?>" name="ass_end_time" id="txt_ass_end_time" class="form-control" />
                    </div>
                </div>

                <div style="clear: both"></div>

                <div class="form-group col-sm-2">
                    <label class="control-label">المدة المتوقعة للعمل</label>
                    <div>
                        <input type="text" placeholder="بالساعة" value="<?=$HaveRs?$rs['EXPECTED_DURATION']:""?>" name="expected_duration" id="txt_expected_duration" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">مدة العمل الفعلية</label>
                    <div>
                        <input readonly type="text" value="<?=$HaveRs?$rs['ASS_DURATION']:""?>" name="ass_duration" id="txt_ass_duration" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">مدة العمل (الساعات) المحتسبة</label>
                    <div>
                        <input readonly type="text" value="<?=$HaveRs?$rs['CALC_DURATION']:""?>" name="calc_duration" id="txt_calc_duration" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">بالساعة والدقائق</label>
                    <div>
                        <input readonly type="text" value="" id="txt_calc_duration_hhmm" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">الوجبات</label>
                    <div>
                        <select name="food_no" id="dp_food_no" class="form-control sel2" >
                            <?php foreach($food_no_cons as $row) :?>
                                <option <?=$HaveRs?($rs['FOOD_NO']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-4">
                    <label class="control-label">الأعمال المطلوب انجازها</label>
                    <div>
                        <input type="text" value="<?=$HaveRs?$rs['WORK_REQUIRED']:""?>" name="work_required" id="txt_work_required" class="form-control" />
                    </div>
                </div>

                <div style="clear: both"></div>

                <div class="form-group col-sm-1">
                    <label class="control-label">طبيعة العمل</label>
                    <div>
                        <input type="text" disabled value="<?=$HaveRs?$rs['EMP_WORK_NAME']:""?>" name="emp_work" id="txt_emp_work" class="form-control" />
                    </div>
                </div>

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
                                <input type="text" data-val="true" data-val-required="حقل مطلوب" placeholder="24:00" name="expected_leave_time" id="txt_expected_leave_time" data-val="true" value="<?=$HaveRs?$rs['EXPECTED_LEAVE_TIME']:""?>" class="form-control" required>
                                <span class="field-validation-valid" data-valmsg-for="expected_leave_time" data-valmsg-replace="true"></span>

                            </div>
                        </div>


                            <div class="form-group col-sm-2">
                                <label class="control-label">وقت الوصول المتوقع</label>
                                <div>
                                    <input type="text"  data-val="true" data-val-required="حقل مطلوب" placeholder="24:00" name="expected_arrival_time" id="txt_expected_arrival_time" data-val="true" value="<?=$HaveRs?$rs['EXPECTED_ARRIVAL_TIME']:""?>" class="form-control" required>
                                    <span class="field-validation-valid" data-valmsg-for="expected_arrival_time" data-valmsg-replace="true">
                                </div>
                            </div>

                            <div style="clear: both"></div>


                            <div class="form-group col-sm-2">
                                <label class="control-label">عنوان الانطلاق</label>
                                <div>
                                    <input type="text" data-val="true" data-val-required="حقل مطلوب" name="from_address" id="txt_from_address" data-val="true" value="<?=$HaveRs?$rs['FROM_ADDRESS']:""?>" class="form-control "required>
                                    <span class="field-validation-valid" data-valmsg-for="from_address" data-valmsg-replace="true"></span>

                                </div>
                            </div>

                                <div class="form-group col-sm-1">
                                    <label class="control-label">موقع الانطلاق </label>
                                    <div>
                                        <input type="hidden" name="start_gps" id="txt_start_gps" class="form-control " value="<?=$HaveRs?$rs['START_GPS']:""?>" >
                                        <button type="button" class="btn green load" onclick="_showReport('<?= base_url('maps') ?>/public_map_location/txt_start_gps');reloadReport();" >
                                            <i class="icon icon-map-marker"></i>
                                        </button>
                                    </div>
                                </div>


                            <div style="clear: both"></div>


                    <div class="form-group col-sm-2 destinations destination_1">
                            <label class="control-label">عنوان الوجهة (1)</label>
                            <div>
                                <input type="text" data-val="true" data-val-required="حقل مطلوب" name="destination_address_1" id="txt_destination_address_1"  value="<?=$HaveRs?$rs['DESTINATION_ADDRESS_1']:""?>" class="form-control"  required>
                                <span class="field-validation-valid" data-valmsg-for="destination_address_1" data-valmsg-replace="true"></span>
                            </div>
                        </div>

                        <div class="form-group col-sm-1 destinations destination_1" >
                            <label class="control-label">موقع الوجهة </label>
                            <div>
                                <input type="hidden" name="destination_gps_1"  id="txt_destination_gps_1" value="<?=$HaveRs?$rs['DESTINATION_GPS_1']:""?>" class="form-control " >
                                <button type="button" class="btn green load" onclick="_showReport('<?= base_url('maps') ?>/public_map_location/txt_destination_gps_1');reloadReport();">
                                    <i class="icon icon-map-marker"></i>
                                </button>

                            </div>
                        </div>


                    <div style="clear: both"></div>

                    <div class="form-group col-sm-2 destinations destination_2">
                            <label class="control-label">عنوان الوجهة (2)</label>
                            <div>
                                <input type="text" data-val="true" data-val-required="حقل مطلوب" name="destination_address_2" id="txt_destination_address_2" data-val="true" value="<?=$HaveRs?$rs['DESTINATION_ADDRESS_2']:""?>" class="form-control" required>
                                <span class="field-validation-valid" data-valmsg-for="destination_address_2" data-valmsg-replace="true"></span>

                            </div>
                        </div>

                        <div class="form-group col-sm-1 destinations destination_2">
                            <label class="control-label">موقع الوجهة </label>
                            <div>
                                <input type="hidden" name="destination_gps_2" id="txt_destination_gps_2" value="<?=$HaveRs?$rs['DESTINATION_GPS_2']:""?>" class="form-control ">
                                <button type="button" class="btn green load" onclick="_showReport('<?= base_url('maps') ?>/public_map_location/txt_destination_gps_2');reloadReport();">
                                    <i class="icon icon-map-marker"></i>
                                </button>
                            </div>
                        </div>

                    <div style="clear: both"></div>


                    <div class="form-group col-sm-2 destinations destination_3">
                            <label class="control-label">عنوان الوجهة (3)</label>
                            <div>
                                <input type="text" data-val="true" data-val-required="حقل مطلوب" name="destination_address_3" id="txt_destination_address_3" data-val="true" value="<?=$HaveRs?$rs['DESTINATION_ADDRESS_3']:""?>" class="form-control" required>
                                <span class="field-validation-valid" data-valmsg-for="destination_address_3" data-valmsg-replace="true"></span>

                            </div>
                        </div>

                        <div class="form-group col-sm-1 destinations destination_3">
                            <label class="control-label">موقع الوجهة </label>
                            <div>
                                <input type="hidden" name="destination_gps_3" id="txt_destination_gps_3" value="<?=$HaveRs?$rs['DESTINATION_GPS_3']:""?>" class="form-control ">
                                <button type="button" class="btn green load" onclick="_showReport('<?= base_url('maps') ?>/public_map_location/txt_destination_gps_3');reloadReport();">
                                    <i class="icon icon-map-marker"></i>
                                </button>
                            </div>
                        </div>

                    <div style="clear: both"></div>


                    <div class="form-group col-sm-2 destinations destination_4">
                            <label class="control-label">عنوان الوجهة (4)</label>
                            <div>
                                <input type="text" data-val="true" data-val-required="حقل مطلوب" name="destination_address_4" id="txt_destination_address_4" data-val="true" value="<?=$HaveRs?$rs['DESTINATION_ADDRESS_4']:""?>" class="form-control" required>
                                <span class="field-validation-valid" data-valmsg-for="destination_address_4" data-valmsg-replace="true"></span>

                            </div>
                        </div>

                        <div class="form-group col-sm-1 destinations destination_4">
                            <label class="control-label">موقع الوجهة </label>
                            <div>
                                <input type="hidden" name="destination_gps_4" id="txt_destination_gps_4" value="<?=$HaveRs?$rs['DESTINATION_GPS_4']:""?>" class="form-control ">
                                <button type="button" class="btn green load" onclick="_showReport('<?= base_url('maps') ?>/public_map_location/txt_destination_gps_4');reloadReport();">
                                    <i class="icon icon-map-marker"></i>
                                </button>
                            </div>
                        </div>

                    <div style="clear: both"></div>


                    <div class="form-group col-sm-2 destinations destination_5">
                            <label class="control-label">عنوان الوجهة (5)</label>
                            <div>
                                <input type="text" data-val="true" data-val-required="حقل مطلوب" name="destination_address_5" id="txt_destination_address_5" data-val="true" value="<?=$HaveRs?$rs['DESTINATION_ADDRESS_5']:""?>" class="form-control" required>
                                <span class="field-validation-valid" data-valmsg-for="destination_address_5" data-valmsg-replace="true"></span>

                            </div>
                        </div>

                        <div class="form-group col-sm-1 destinations destination_5">
                            <label class="control-label">موقع الوجهة </label>
                            <div>
                                <input type="hidden" name="destination_gps_5" id="txt_destination_gps_5" value="<?=$HaveRs?$rs['DESTINATION_GPS_5']:""?>" class="form-control ">
                                <button type="button" class="btn green load" onclick="_showReport('<?= base_url('maps') ?>/public_map_location/txt_destination_gps_5');reloadReport();">
                                    <i class="icon icon-map-marker"></i>
                                </button>
                            </div>
                        </div>


                    </div>
                    <!-------------------------------------------------------------->
                </div>



                <!-------------------------------------------------------------->



            <div class="modal-footer">

                <?php if ( HaveAccess($post_url) && $page_act!='hr_admin' && ($isCreate || ( $rs['ADOPT']==1 and isset($can_edit)?$can_edit:false) )  ) : ?>
                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                <?php endif; ?>

                <?php if ( HaveAccess($post_url) && $page_act=='move_admin' && !$isCreate && $rs['ADOPT']==30 ) : ?>
                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ التعديل </button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_url.'10') and !$isCreate and $rs['ADOPT']==1 ) : ?>
                    <button type="button" id="btn_adopt_10" onclick='javascript:adopt_(10);' class="btn btn-success">اعتماد المدخل</button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_url.'20') and !$isCreate and $rs['ADOPT']==10 && $page_act=='manager' ) : ?>
                    <button type="button" onclick='javascript:adopt_(20);' class="btn btn-success">اعتماد المدير</button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_url.'30') and !$isCreate and $rs['ADOPT']==20 ) : ?>
                    <button type="button" onclick='javascript:adopt_(30);' class="btn btn-warning"> نهاية العمل</button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_url.'40') and !$isCreate and $rs['ADOPT']==30 ) : ?>
                    <button type="button" onclick='javascript:adopt_(40);' class="btn btn-success">اعتماد الشؤون الادارية </button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_url.'0') and !$isCreate and $rs['ADOPT']==1 ) : ?>
                    <button type="button" onclick='javascript:adopt_(0);' class="btn btn-danger">الغاء </button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_url.'_20') and !$isCreate and $rs['ADOPT']==20 && $page_act=='manager' ) : ?>
                    <button type="button" onclick='javascript:adopt_("_20");' class="btn btn-danger">الغاء التكليف </button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_url.'_40') and !$isCreate and $rs['ADOPT']==40 ) : ?>
                    <button type="button" onclick='javascript:adopt_("_40");' class="btn btn-danger">الغاء الاعتماد </button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_url.'_30') and !$isCreate and ($rs['ADOPT']==30 or $rs['ADOPT']==10) && $page_act=='move_admin' ) : ?>
                    <button type="button" onclick='javascript:adopt_("_30");' class="btn btn-danger">الغاء التكليف نهائيا</button>
                <?php endif; ?>

            </div>

        </form>
    </div>
</div>



<?php
$scripts = <<<SCRIPT
<script>

    reBind();

    function reloadReport(){
    
        setTimeout(function () {
            reloadIframeOfReport();
        }, 200);
    }

    function reBind(){
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
    } // reBind

    $('#dp_adopt, #dp_branch_id, #dp_food_no').select2('readonly',1);
    
    if('{$page_act}'=='move_admin'){
        $('#txt_ass_end_date, #txt_ass_end_time, #txt_calc_duration').prop('readonly',0); // txt_ass_duration
        $('#dp_food_no').select2('readonly',0);
    }
    
    $('#txt_ass_start_time').change(function(){
        if($(this).val() < '12:00'){
            alert('ساعة بداية التكليف يتم ادخالها بنظام 24، اي ان الساعة 2 م يتم ادخالها 14:00 ');
        }
    });



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

    
    function validation_f(){
        var directions_no = $('#dp_directions_no').val();
        
        if( directions_no == 1 ){
            if ($('#txt_start_gps').val() == '' || $('#txt_destination_gps_1').val() == '') {
                alert('فضلا , قم بادخال احداثيات الحركة من خلال الخريطة');
            return;
            }
          }
        else if(directions_no == 2){
            if ($('#txt_start_gps').val() == '' || $('#txt_destination_gps_1').val() == '' || $('#txt_destination_gps_2').val() == '' ) {
                alert('فضلا , قم بادخال احداثيات الحركة من خلال الخريطة');
            return;
            }
        }
        else if(directions_no == 3){
            if ($('#txt_start_gps').val() == '' || $('#txt_destination_gps_1').val() == '' || $('#txt_destination_gps_2').val() == '' || $('#txt_destination_gps_3').val() == '') {
                alert('فضلا , قم بادخال احداثيات الحركة من خلال الخريطة');
            return;
            }
        }
          
        else if(directions_no == 4){
            if ($('#txt_start_gps').val() == '' || $('#txt_destination_gps_1').val() == '' || $('#txt_destination_gps_2').val() == '' || $('#txt_destination_gps_3').val() == '' || $('#txt_destination_gps_4').val() == '') {
                alert('فضلا , قم بادخال احداثيات الحركة من خلال الخريطة');
            return;
            }
        }
     
                  
        else if(directions_no == 5){
            if ($('#txt_start_gps').val() == '' || $('#txt_destination_gps_1').val() == '' || $('#txt_destination_gps_2').val() == '' || $('#txt_destination_gps_3').val() == '' || $('#txt_destination_gps_4').val() == '' || $('#txt_destination_gps_5').val() == '') {
                alert('فضلا , قم بادخال احداثيات الحركة من خلال الخريطة');
            return;
            }
        }
        
          
    }
    
    
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

    
    $('.load').click(function(e){
      reloadIframeOfReport();
      });

    
    $('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        var msg= 'هل تريد حفظ الطلب ؟!';
        if(confirm(msg)){
			
			var request = $("input:radio[name='car_request']:checked").val();
            if( request == 1){
                if (($('#txt_expected_leave_time').val() >= $('#txt_expected_arrival_time').val())) {
                    alert('يجب ان تكون ساعة الوصول بعد ساعة المغادرة');
                    return;
                }
              }
    
            var directions_no = $('#dp_directions_no').val();
            
            if( directions_no == 1 ){
                if ($('#txt_start_gps').val() == '' || $('#txt_destination_gps_1').val() == '') {
                    alert('فضلا , قم بادخال احداثيات الحركة من خلال الخريطة');
                return;
                }
              }
            
            else if(directions_no == 2){
                if ($('#txt_start_gps').val() == '' || $('#txt_destination_gps_1').val() == '' || $('#txt_destination_gps_2').val() == '' ) {
                    alert('فضلا , قم بادخال احداثيات الحركة من خلال الخريطة');
                return;
                }
            }
            
            else if(directions_no == 3){
                if ($('#txt_start_gps').val() == '' || $('#txt_destination_gps_1').val() == '' || $('#txt_destination_gps_2').val() == '' || $('#txt_destination_gps_3').val() == '') {
                    alert('فضلا , قم بادخال احداثيات الحركة من خلال الخريطة');
                return;
                }
            }
              
            else if(directions_no == 4){
                if ($('#txt_start_gps').val() == '' || $('#txt_destination_gps_1').val() == '' || $('#txt_destination_gps_2').val() == '' || $('#txt_destination_gps_3').val() == '' || $('#txt_destination_gps_4').val() == '') {
                    alert('فضلا , قم بادخال احداثيات الحركة من خلال الخريطة');
                return;
                }
            }
                  
            else if(directions_no == 5){
                if ($('#txt_start_gps').val() == '' || $('#txt_destination_gps_1').val() == '' || $('#txt_destination_gps_2').val() == '' || $('#txt_destination_gps_3').val() == '' || $('#txt_destination_gps_4').val() == '' || $('#txt_destination_gps_5').val() == '') {
                    alert('فضلا , قم بادخال احداثيات الحركة من خلال الخريطة');
                return;
                }
            }

            $(this).attr('disabled','disabled');
            var form = $(this).closest('form');
            ajax_insert_update(form,function(data){
                if(parseInt(data)>1){
                    success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                    get_to_link('{$get_url}/'+parseInt(data)+'/{$page_act}');
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

}else{ // get or edit
    $scripts = <<<SCRIPT
    {$scripts}


    if({$rs['ADOPT']}==1){
        warning_msg('تنويه','التكليف بحاجة لاعتماد الادخال..');
    }else if({$rs['ADOPT']}==10){
        warning_msg('تنويه','التكليف بحاجة لاعتماد المدير..');
    }
    
    $('#txt_calc_duration').change(function(){
        if(typeof hhmm_decimal_to_time !== 'undefined'){
            $('#txt_calc_duration_hhmm').val( hhmm_decimal_to_time( $('#txt_calc_duration').val() ) ) ;
        }
    });
    
    var btn__= '';
    $('#btn_adopt_10').click( function(){
        btn__ = $(this);
    });
    
    function adopt_(no){
        var msg= 'هل تريد الاعتماد ؟!';
        if(no=='_40') msg= 'هل تريد بالتأكيد الغاء اعتماد الشؤون الادارية؟';
        if(no==0 || no=='_20' || no=='_30') msg= 'هل تريد بالتأكيد الغاء التكليف بشكل نهائي؟!';
        if(no==40) msg= 'هل تريد بالتأكيد اعتماد التكليف بشكل نهائي؟ لا يمكن التراجع عن هذه العملية!';

        if(no==40 && $('#txt_calc_duration').val() == ''){
            info_msg('ملاحظة','في حالة عدم احتساب ساعات اضافية يتم ادخال المدة المحتسبة صفر [0]');
            danger_msg('تحذير..','يجب ادخال المدة المحتسبة ثم حفظ التعديل قبل الاعتماد..');
            return 0;
        }

        if(confirm(msg)){
        
            var values= {ser: "{$rs['SER']}"};
            get_data('{$adopt_url}'+no, values, function(ret){
                if(ret==1){
                    success_msg('رسالة','تمت العملية بنجاح..');
                    $('button').attr('disabled','disabled');

                    if( no==10 && '{$rs['MANAGER_EMAIL']}' != ''){
                        var sub= 'يرجى اعتماد التكليف {$rs['SER']}';
                        var text= 'يرجى اعتماد التكليف الخاص بالموظف {$rs['EMP_NO_NAME']}';
                        text+= '<br>للاطلاع افتح الرابط';
                        text+= ' <br>{$gfc_domain}{$manager_url} ';
                        _send_mail(btn__,'{$rs['MANAGER_EMAIL']}',sub,text);  
                    }
                
                    if( no==10 && '{$rs['CAR_REQUEST']}' == 1){
                        var sub= 'تكليف بحاجة الى سيارة {$rs['SER']}';
                        var text= 'الرجاء تأكيد التكليف الخاص بالموظف {$rs['EMP_NO_NAME']}';
                        text+= '<br>للاطلاع افتح الرابط';
                        text+= ' <br>{$gfc_domain}{$manager_url} ';
                        _send_mail(btn__,'{$move_emails}',sub,text);
                    }
                    btn__ = '';

                    setTimeout(function(){
                        get_to_link(window.location.href);
                    },1000);
                }else{
                    danger_msg('تحذير..',data);
                  
                }
            }, 'html');
        }
    }

    </script>
SCRIPT;
}

sec_scripts($scripts);

?>
