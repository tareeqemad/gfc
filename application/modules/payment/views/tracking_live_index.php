<?php
/**
 * Created by PhpStorm.
 * User: abarakat
 * Date: 7/4/2019
 * Time: 11:38 AM
 */

$MODULE_NAME = 'payment';
$TB_NAME = 'carMovements';

$get_speed_url = base_url("$MODULE_NAME/$TB_NAME/public_get_speed");
$get_movement_url = base_url("$MODULE_NAME/$TB_NAME/public_get_movment");
$get_movement_live_url = base_url("$MODULE_NAME/$TB_NAME/public_get_movment_live");
$get_langAndlat_url = base_url("$MODULE_NAME/$TB_NAME/public_get_langAndlat");
$image = base_url("assets/images/car_logo.png");

?>

<div class="toolbar">

    <div class="caption"><?= $title ?></div>


</div>

<div class="form-body" style="overflow: hidden">
    <div class="col-md-2">
        <div style="padding: 0 10px">
            <label class="control-label">اسم السائق</label>
            <div>
                <select name="driver_name" id="dp_driver_name" class="form-control sel2">
                    <option>  </option>
                    <?php foreach ($driver as $row) : ?>
                        <option value="<?= $row['NO'] ?>"><?php echo $row['NAME'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

        </div>
    </div>
    <div id="movements_map" class="col-md-10" style="height: 690px;"></div>
</div>

<script type="text/javascript"
        src="https://maps.google.com/maps/api/js?libraries=geometry&language=ar&sensor=true&key=AIzaSyB3V-XUnQii5hBErN1ntpmHOXiT9lFbY08"></script>

<?php

$edit_script = <<<SCRIPT
 <script type="text/javascript">
     
    $('.sel2').select2();
    
    setInterval(
        function ()
        {
            get_dataWithOutLoading('{$get_movement_live_url}',{driver_name: $('#dp_driver_name').val()},
            function (data) {      
                startHRout_live(data);
             });
        }, 3000);

</script>

SCRIPT;
sec_scripts($edit_script);
?>
