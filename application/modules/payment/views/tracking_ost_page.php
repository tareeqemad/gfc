<?php

$MODULE_NAME = 'payment';
$TB_NAME = 'carMovements';

$get_speed_url = base_url("$MODULE_NAME/$TB_NAME/public_get_speed");
$get_movement_url = base_url("$MODULE_NAME/$TB_NAME/public_get_movment");
$get_langAndlat_url = base_url("$MODULE_NAME/$TB_NAME/public_get_langAndlat");
$image = base_url("assets/images/car_logo.png");

if (!isset($result))
    $result = array();
$HaveRs = count($result) > 0;

$rs = $HaveRs ? $result[0] : $result;

?>
<script src="https://cdn.jsdelivr.net/timepicker.js/latest/timepicker.min.js"></script>
<link href="https://cdn.jsdelivr.net/timepicker.js/latest/timepicker.min.css" rel="stylesheet"/>


<div class="toolbar">

    <div class="caption"><?= $title ?></div>


</div>

<div class="form-body" style="overflow: hidden">
    <div class="col-md-2">
        <div style="padding: 0 10px">
            <label class="control-label">اسم السائق</label>
            <div>
                <select name="driver_name" id="dp_driver_name" class="form-control sel2">
                    <option></option>
                    <?php foreach ($driver as $row) : ?>
                        <option  <?= $HaveRs ? ($rs['DRIVER_ID'] == $row['NO'] ? "selected" : "") : "" ?> value="<?= $row['NO'] ?>" ><?= $row['NAME'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div id="traching_div" style="padding: 10px 0">
                <label class="control-label">تاريخ الحركة</label>
                <div>
                    <input type="text" data-type="date" placeholder="تاريخ الحركة" data-date-format="DD/MM/YYYY" name="movment_date" id="txt_movment_date" class="form-control" value="<?= $HaveRs ? $rs['ACTUAL_LEAVE_TIME'] : "" ?>">
                </div>
                <br>
                <div class="form-group col-sm-2">
                    <label class="control-label"> وقت  الحركة من </label>
                    <div>
                        <input type="text" data-val="true" data-val-required="حقل مطلوب" placeholder="24:00" name="movment_time_from" id="txt_movment_time_from" data-val="true"  class="form-control" value="<?= $HaveRs ? $rs['ACTUAL_LEAVE_TIME_T'] : "" ?>" >
                        <span class="field-validation-valid" data-valmsg-for="movment_time_from" data-valmsg-replace="true"></span>
                    </div>
                </div>


                <div class="form-group col-sm-2">
                    <label class="control-label">الى</label>
                    <div>
                        <input type="text"  data-val="true" data-val-required="حقل مطلوب" placeholder="24:00" name="movment_time_to" id="txt_movment_time_to" data-val="true"  class="form-control" value="<?= $HaveRs ? $rs['ACTUAL_ARRIVAL_TIME_T'] : "" ?>" >
                        <span class="field-validation-valid" data-valmsg-for="movment_time_to" data-valmsg-replace="true">
                    </div>
                </div>
                <br>

            </div>

            <br>
            <button type="button" id="showTrackingBtn" onclick='return_data();' class="btn btn-primary">تتبع</button>
            <button type="button" class="btn btn-danger" id="update_form_tracking" data-dismiss="modal">الغاء</button>

        </div>
    </div>
    <div id="movements_map" class="col-md-10" style="height: 490px;"></div>
</div>

<script type="text/javascript"
        src="https://maps.google.com/maps/api/js?libraries=geometry&language=ar&sensor=true&key=AIzaSyB3V-XUnQii5hBErN1ntpmHOXiT9lFbY08"></script>

<?php

$edit_script = <<<SCRIPT
 <script type="text/javascript">
 
    $('.sel2').select2();

    function return_data (){
        $('#showTrackingBtn').prop('disabled', true); 
    
        get_data('{$get_movement_url}', {driver_name: $('#dp_driver_name').val(), movment_date: $('#txt_movment_date').val(), movment_time_from: $('#txt_movment_time_from').val() ,movment_time_to: $('#txt_movment_time_to').val()}, 
        function (data) {
            startHRout(data,$('#dp_driver_name').val());
        });
    }

</script>
SCRIPT;

sec_scripts($edit_script);

?>
