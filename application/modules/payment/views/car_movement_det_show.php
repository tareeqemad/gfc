<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 25/04/19
 * Time: 01:09 م
 */

$MODULE_NAME = 'payment';
$TB_NAME = 'carMovements';
$create_url = base_url("$MODULE_NAME/$TB_NAME/create");

if (!isset($result))
    $result = array();
$HaveRs = count($result) > 0;

$rs = $HaveRs ? $result[0] : $result;


?>
<div id="SSS" class="modal-content">
    <div class="modal-header">
        <h3 id="title" class="modal-title">اضافة حركة جديدة</h3>
    </div>
    <div class="modal-body">
        <div class="modal-body">
            <form id="<?= $TB_NAME ?>_form" method="post" action="<?= $create_url ?>">

                <div class="row">
                    <!-------------------------------------الغرض من الحركة----------------------------------------->
                    <div class="form-group col-sm-4">
                        <label class="control-label">الغرض من الحركة </label>
                        <div>
                            <input type="text" value="<?= $HaveRs ? $rs['PURPOSE_TYPE'] : "" ?>" name="purpose_type" id="txt_purpose_type" class="form-control ltr">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-------------------------وقت المغادرة المتوقع---------------------->
                    <div class="form-group col-sm-4">
                        <label class="control-label"> وقت المغادرة المتوقع </label>
                        <div>
                            <input placeholder="24:00" type="text" value="<?= $HaveRs ? $rs['EXPECTED_LEAVE_TIME'] : "" ?>" name="Expected_Leave_Time" id="txt_expected_leave_time" class="form-control ltr">
                        </div>
                    </div>
                    <!---------------------------------وقت الوصول المتوقع----------------------------->
                    <div class="form-group col-sm-4">
                        <label class="control-label">وقت الوصول المتوقع</label>
                        <div>
                            <input placeholder="24:00" type="text" value="<?= $HaveRs ? $rs['EXPECTED_ARRIVAL_TIME'] : "" ?>" name="expected_arrival_time" id="txt_expected_arrival_time" class="form-control ltr">
                        </div>
                    </div>
                </div>


                <div class="row">
                    <!----------------------------عنوان الانطلاق--------------------------------->
                    <div class="form-group col-sm-12">
                        <label class="control-label">عنوان الانطلاق</label>
                        <div>
                            <input type="text" value="<?= $HaveRs ? $rs['PREDEFINE_START_GPS'] : "" ?>" name="predefine_start_gps" id="txt_predefine_finished_gps" class="form-control ltr">
                        </div>
                    </div>
                </div>
                <div class="row">

                    <!----------------------------------عنوان الهدف---------------------------->
                    <div class="form-group col-sm-12">
                        <label class="control-label">عنوان الهدف</label>
                        <div>
                            <input type="text" value="<?= $HaveRs ? $rs['PREDEFINE_FINISHED_GPS'] : "" ?>" name="predefine_finished_gps" id="txt_predefine_finished_gps" class="form-control ltr">
                        </div>
                    </div>

                </div>

                <div class="row">
                    <!-------------------------الملاحظات--------------------------->
                    <div class="form-group col-sm-12">
                        <label class="control-label">الملاحظات</label>
                        <div>
                            <input type="text" name="notes" value="<?= $HaveRs ? $rs['NOTES'] : "" ?>" id="txt_notes" class="form-control ltr">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!------------------------------موقع الانهاء المحدد مسبقا------------------------------------------------->
                    <div class="form-group col-sm-4">
                        <label class="control-label">موقع الانطلاق </label>
                        <div>
                            <input type="hidden" name="start_gps_location" value="<?= $HaveRs ? $rs['START_GPS_LOCATION'] : "" ?>" id="txt_predefine_start_gps" class="form-control ltr">
                            <button type="button" class="btn green" onclick="_showReport('<?= base_url('maps') ?>/public_map_location/txt_predefine_start_gps');">
                                <i class="icon icon-map-marker"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-group col-sm-4">
                        <label class="control-label">موقع الهدف </label>
                        <div>
                            <input type="hidden" value="<?= $HaveRs ? $rs['FINISHED_GPS_LOCATION'] : "" ?>" name="finished_gps_location" id="txt_finished_gps_location" class="form-control ltr">
                            <button type="button" class="btn green" onclick="_showReport('<?= base_url('maps') ?>/public_map_location/txt_predefine_finished_gps');">
                                <i class="icon icon-map-marker"></i>
                            </button>
                        </div>
                    </div>

                </div>


                <!---------------------------------------------------------->
                <div class="modal-footer">
                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">اغلاق</button>
                </div>

            </form>
            <!---->
        </div>
    </div>
</div>
