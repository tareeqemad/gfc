<?php

/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 24/04/19
 * Time: 10:51 ص
 */

$MODULE_NAME = 'payment';
$TB_NAME = 'CarMovements';
$back_url = base_url("$MODULE_NAME/$TB_NAME");
$create_url = base_url("$MODULE_NAME/$TB_NAME/create_details");
$creates_url = base_url("$MODULE_NAME/$TB_NAME/create");
$edit_url = base_url("$MODULE_NAME/$TB_NAME/edit");
$select_url= base_url('payment/cars/public_select_car');
$car_movements_det_url= base_url("$MODULE_NAME/$TB_NAME/public_list_car_movements_det");
$car_movements_det_after_url= base_url("$MODULE_NAME/$TB_NAME/public_list_car_movements_det_after");
$get_tracking_live_url= base_url("$MODULE_NAME/$TB_NAME/tracking_live");
$get_url= base_url("$MODULE_NAME/$TB_NAME/get_track");


if (!isset($result))
    $result = array();
$HaveRs = count($result) > 0;

$rs = $HaveRs ? $result[0] : $result;

$post_url = base_url("payment/CarMovements/" . ($action == 'index' ? 'create' : $action));
$ser = isset($rs['SER'])? $rs['SER'] :'';
?>


<script type="text/javascript" src="https://maps.google.com/maps/api/js?libraries=geometry&language=ar&sensor=true&key=AIzaSyA0qdwV2hXgZOr-TfvZDWLa-Uzt-5aRXFs&map_ids=181777b9bef0ea0c"></script>


<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
            <?php if (HaveAccess($creates_url)): ?>
                <li><a href="<?= $creates_url ?>"><i class="glyphicon glyphicon-plus"></i>جديد</a> </li><?php endif; ?>
            <li><a href="<?= $get_tracking_live_url ?>"><i style="font-size: 15px;" class="icon icon-map-marker"></i> </a></li>
            <li><a  href="<?= $back_url ?>"><i  class="icon icon-reply"></i> </a></li>
        </ul>

    </div>

</div>


<div class="form-body">

    <div id="container aaa">
        <form class="form-horizontal" id="<?= $TB_NAME ?>_form" method="post" action="<?= $post_url ?>" role="form" novalidate="novalidate">
            <div class="modal-body inline_form">

                <fieldset class="field_set">
                    <legend>بيانات الحركة</legend>
                    <div class="form-group">

                        <label class="col-sm-1 control-label">رقم المسلسل</label>
                        <div class="col-sm-2">
                            <input type="text" readonly name="ser" id="txt_SER" class="form-control" value="<?= $HaveRs ? $rs['SER'] : "" ?>">
                        </div>

                    </div>

                    <div class="form-group">

                        <div class="h_car_owner">
                        <label class="col-sm-1 control-label h_car_owner">صاحب العهدة </label>
                        <div class="col-sm-2 h_car_owner">

                            <select data-val="true" name="car_owner" id="dp_car_id_name" class="form-control sel2" required>
                                <option></option>
                                <?php foreach ($car_owner as $rows) : ?>
                                    <option <?=$HaveRs?($rs['CAR_ID']==$rows['CAR_FILE_ID']?'selected':''):''?> value="<?= $rows['CAR_FILE_ID'] ?>" ><?= $rows['CAR_OWNER'] ?></option>
                                <?php endforeach; ?>
                            </select>

                        </div>
                        </div>

                        <div style="clear: both"></div>
                        <br>
                        <input type="hidden" name="emp_name" id="h_text_emp_name" value="<?= $HaveRs ? $rs['EMP_NAME'] : "" ?>">
                        <div class="row driver_company">
                            <br>
                            <label class="col-sm-1 control-label"> السائق</label>
                            <div class="col-sm-2">

                                <select data-val="true" name="driver_id" id="dp_driver_name" class="form-control sel2" required></select>

                            </div>
                        </div>

                        <div style="clear: both"></div>
                        <br>

                        <label class="col-sm-1 control-label h_cost">تكلفة المهمة</label>
                        <div class="col-sm-2 h_cost">
                            <input type="text" data-val-required="حقل مطلوب" class="form-control" name="task_cost" rows="1" id="txt_task_cost" value="<?= $HaveRs ? $rs['TASK_COST'] : "" ?>">
                        </div>

                        <label class="col-sm-1 control-label h_office">اسم المكتب</label>
                        <div class="col-sm-2 h_office">

                        <select data-val="true" name="office_id" id="dp_office_id" class="form-control" required>
                            <option></option>
                            <?php foreach ($office_name as $row) : ?>
                                <option <?=$HaveRs?($rs['OFFICE_ID']==$row['CON_NO']?'selected':''):''?> value="<?= $row['CON_NO'] ?>" ><?= $row['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>

                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-1 control-label">نوع الحركة</label>
                        <div class="col-sm-2">

                            <select data-val-required="حقل مطلوب" data-val="true" name="movment_type" id="dp_movement_type" class="form-control">
                                <option></option>
                                <?php foreach ($movement_type as $row) : ?>
                                    <option value="<?= $row['CON_NO'] ?>" <?= $HaveRs ? ($rs['MOVMENT_TYPE'] == $row['CON_NO'] ? "selected" : "") : "" ?> ><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>

                        </div>

                        <label class="col-sm-1 control-label">تاريخ الحركة</label>
                        <div class="col-sm-2">

                            <input type="text" data-val-required="حقل مطلوب" data-type="date" data-date-format="DD/MM/YYYY" name="the_date" value="<?= $HaveRs ? $rs['DATE_MOVE'] : "" ?>" id="txt_the_date" class="form-control">

                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-1 control-label">ملاحظات</label>
                        <div class="col-sm-5">
                            <textarea data-val-required="حقل مطلوب" class="form-control" name="notes" rows="5" id="txt_notes"><?= $HaveRs ? $rs['NOTES'] : "" ?></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">

                        <?php if ( HaveAccess($edit_url) ) :  ?>
                            <button type="submit" data-action="submit" id="update_mov_det" class="btn btn-primary">حفظ البيانات</button>
                        <?php endif; ?>

                    </div>

                </fieldset>
                <hr/>

                <fieldset class="field_set">
                    <legend>الحركات المطلوبة</legend>

                    <div class="details" id="mov_details_container">

                        <?php echo modules::run('payment/CarMovements/public_movements_det', $HaveRs ? $rs['ORDER_NO'] : ""); ?>

                    </div>

                </fieldset>

                <hr/>

                <?php if ($HaveRs) { ?>

                    <fieldset class="field_set">
                        <legend>تفاصيل حركة السيارة</legend>

                        <br>

                        <div class="modal-footer">

                            <?php if ( HaveAccess($create_url) ) :  ?>
                                <button id="modal_movment_det" type="button" class="btn btn-primary">اضافة حركة</button>
                            <?php endif; ?>
                                <button type="button" onclick="$('#movementsTbl').tableExport({type:'excel',escape:'false'});" class="btn btn-success">  اكسل </button>
                        </div>

                        <div class="details" id="movements_details_container">

                            <?=modules::run('payment/CarMovements/public_list_car_movements_det_after', $HaveRs ? $rs['SER'] : ""); ?>

                        </div>
                        <?=modules::run('payment/CarMovements/public_list_car_movements_det_map', $HaveRs ? $rs['SER'] : ""); ?>
                    </fieldset>


                    <hr/>

                <?php } ?>


            </div>

        </form>


    </div>

</div>

</div>


<div class="modal fade" id="myModal">
    <div id="myModal" role="dialog">
        <div class="modal-dialog">

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

                                        <input type="hidden" name="car_mov_id" value="<?= $HaveRs ? $rs['SER'] : "" ?>" id="txt_car_mov_id">

                                        <select data-val-required="حقل مطلوب" data-val="true" name="purpose_type" id="txt_purpose_type" class="form-control">
                                            <option></option>
                                            <?php foreach ($movement_purpose as $row) : ?>
                                                <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                            <?php endforeach; ?>
                                        </select>

                                    </div>


                                </div>
                            </div>

                            <div class="row">
                                <!-------------------------وقت المغادرة المتوقع---------------------->
                                <div class="form-group col-sm-4">
                                    <label class="control-label"> وقت المغادرة المتوقع </label>
                                    <div>
                                        <input type="text" placeholder="24:00" name="expected_leave_time" id="txt_expected_leave_time" data-val-required="حقل مطلوب" data-val="true" class="form-control">
                                    </div>
                                </div>
                                <!---------------------------------وقت الوصول المتوقع----------------------------->
                                <div class="form-group col-sm-4">
                                    <label class="control-label">وقت الوصول المتوقع</label>
                                    <div>
                                        <input type="text" placeholder="24:00" name="expected_arrival_time" id="txt_expected_arrival_time" data-val-required="حقل مطلوب" data-val="true" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!----------------------------عنوان الانطلاق--------------------------------->
                                <div class="form-group col-sm-12">
                                    <label class="control-label">عنوان الانطلاق</label>
                                    <div>
                                        <input type="text" name="from_address" id="txt_from_address" data-val-required="حقل مطلوب" data-val="true" class="form-control ">
                                    </div>
                                </div>
                            </div>
                            <div class="row">

                                <!----------------------------------عنوان الهدف---------------------------->
                                <div class="form-group col-sm-12">
                                    <label class="control-label">عنوان الهدف</label>
                                    <div>
                                        <input type="text" name="to_address" id="txt_to_address" data-val-required="حقل مطلوب" data-val="true" class="form-control">
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <!-------------------------الملاحظات--------------------------->
                                <div class="form-group col-sm-12">
                                    <label class="control-label">الملاحظات</label>
                                    <div>
                                        <input type="text" name="notes" id="txt_notes" class="form-control ">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!------------------------------موقع الانهاء المحدد مسبقا------------------------------------------------->
                                <div class="form-group col-sm-4">
                                    <label class="control-label">موقع الانطلاق </label>
                                    <div>
                                        <input type="hidden" name="predefine_start_gps" id="txt_predefine_start_gps" class="form-control ">
                                        <button type="button" class="btn green"
                                                onclick="_showReport('<?= base_url('maps') ?>/public_map_location/txt_predefine_start_gps');reloadReport();">
                                            <i class="icon icon-map-marker"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="form-group col-sm-4">
                                    <label class="control-label">موقع الهدف </label>
                                    <div>
                                        <input type="hidden" name="predefine_finished_gps" id="txt_predefine_finished_gps" class="form-control ">
                                        <button type="button" class="btn green"
                                                onclick="_showReport('<?= base_url('maps') ?>/public_map_location/txt_predefine_finished_gps');reloadReport();">
                                            <i class="icon icon-map-marker"></i>
                                        </button>
                                    </div>
                                </div>
                                <input type="hidden" name="distance" id="h_txt_distance">
                                <input type="hidden" name="token" id="h_token">
                                <input type="hidden" name="mobile_no_company" id="h_mobile_no_company">
                                <input type="hidden" name="mobile_no_rented" id="h_mobile_no_rented">
                                <input type="hidden" name="car_type" id="h_car_type">
                            </div>

                            <!---------------------------------------------------------->
                            <div class="modal-footer">
                                <button type="submit" id="add_mov_det" onclick="Length_distance();" data-action="submit" class="btn btn-primary">حفظ
                                    البيانات
                                </button>
                                <button type="button" class="btn btn-danger" data-dismiss="modal">اغلاق</button>

                            </div>


                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php


$scripts = <<<SCRIPT

<script>

    $('.sel2').select2();
    reBind();

    function reloadReport(){
        setTimeout(function () {
            reloadIframeOfReport();
        }, 200);
    }


    function reBind(){
        
        if("{$rs['CAR_TYPE']}"==1){
            $('.h_cost,.h_office,.h_driver').hide();
            $('#txt_task_cost,#dp_office_id').val('');
            
            var drive_name_company_json= {$car_drive_name_company};
            var drive_name_company= '<option></option>';
            
            $.each(drive_name_company_json, function(i,item){
                
            drive_name_company += "<option value='"+item.NO+"' data-token='"+item.ANDROID+"' data-mobile-company='"+item.TEL+"' >"+item.NAME+"</option>";
               
            });
            
            $('select[name="driver_id"]').html(drive_name_company);
            $('select[name="driver_id"]').select2('val',"{$rs['DRIVER_ID']}");
            $('#h_car_type').val(1);
        }
        else if ("{$rs['CAR_TYPE']}"==2){
            $('.h_car_owner,.h_cost,.h_office,.h_driver_company').hide();
            $('#h_txt_car_id_name,#txt_car_id_name,#txt_task_cost,#dp_office_id').val('');
            
            var drive_name_rented_json= {$car_drive_name_rented};
            var drive_name_rented= '<option></option>';
            
            $.each(drive_name_rented_json, function(i,item){
                drive_name_rented += "<option value='"+item.CONTRACTOR_FILE_ID+"' data-token='"+item.ANDROID+"' data-emp-no='"+item.CONTRACTOR_FILE_ID+"' data-mobile-rented='"+item.MOBILE_NO+"' >"+item.CONTRACTOR_FILE_ID+ ' : ' +item.DRIVER_NAME+"</option>";
            });
            
            $('select[name="driver_id"]').html(drive_name_rented);
            $('select[name="driver_id"]').select2('val',"{$rs['DRIVER_ID']}");
            $('#h_car_type').val(2);
            
        }else {
           $('.h_car_owner,.h_driver,.h_driver_company,.driver_company,.h_cost').hide(); 
           $('#h_txt_car_id_name,#txt_car_id_name,#h_txt_driver_name,#txt_driver_name').val('');
        }
    }


    function Length_distance(){
                
        var string_start = $('#txt_predefine_start_gps').val();
        var string_finish = $('#txt_predefine_finished_gps').val();
            
            var start_gps  = string_start.split("|");
            var finish_gps = string_finish.split("|");
            
            var latLngA = new google.maps.LatLng(start_gps[0],start_gps[1]);
            var latLngB = new google.maps.LatLng(finish_gps[0], finish_gps[1]);
            var distance = google.maps.geometry.spherical.computeDistanceBetween(latLngA, latLngB);
            var result = (distance.toFixed(0)/1000);
            
            $('#h_txt_distance').val(result);
            
            var token = $('#dp_driver_name').find(':selected').attr("data-token");
            $('#h_token').val(token);
            
            var mobile_no_company = '0'+$('#dp_driver_name').find(':selected').attr("data-mobile-company");
            $('#h_mobile_no_company').val(mobile_no_company);
            
            var mobile_no_rented = $('#dp_driver_name').find(':selected').attr("data-mobile-rented");
            $('#h_mobile_no_rented').val(mobile_no_rented);
    }
    
    function show_row_details(id){
        get_to_link('{$get_url}/'+id);
    }
      
    setInterval(
        function ()
        {
            updateMaps();
        }, 10000);

    function updateMaps(){
        get_dataWithOutLoading('{$car_movements_det_after_url}',{id : $('#txt_SER').val() },function(data){
            $('#movements_details_container').html(data);
            // initMap();
        },'html');
    }
 
</script>

SCRIPT;

sec_scripts($scripts);
?>
