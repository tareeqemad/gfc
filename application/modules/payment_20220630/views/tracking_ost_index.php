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
?>

<div class="toolbar">

    <div class="caption"><?= $title ?></div>


</div>

<div class="form-body" style="overflow: hidden">
    <div class="col-md-2">
        <div style="padding: 0 10px">
            <div>
                <select name="driver_name" id="dp_driver_name" class="form-control">
                    <option></option>
                    <?php foreach ($driver as $row) : ?>
                        <option value="<?= $row['NO'] ?>"><?php echo $row['NAME'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div id="traching_div" style="padding: 10px 0">
                <div>
                    <input type="text" data-type="date" placeholder="يوم الحركة"
                           data-date-format="DD/MM/YYYY" name="movment_date"
                           id="txt_movment_date" class="form-control">
                </div>
                <br>
                <div>
                    <input type="text" readonly placeholder="متوسط السرعة"
                           name="avg_speed"
                           id="txt_avg_speed"
                           data-val="true"
                           class="form-control ">
                </div>

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
     function return_data (){

         $('#showTrackingBtn').prop('disabled', true);
         
        get_data('{$get_speed_url}', {driver_name: $('#dp_driver_name').val(), movment_date: $('#txt_movment_date').val()}, function (data) {
            if (data.length == 1) {
                var item = data[0];
                var round_speed=  item.SPEED;
                $('#txt_avg_speed').val(round_speed);
            }
        });

        get_data('{$get_movement_url}', {driver_name: $('#dp_driver_name').val(), movment_date: $('#txt_movment_date').val()}, 
        function (data) {
                 
                startHRout(data,$('#dp_driver_name').val());
                
        });
     }

    $(document).ready(function(){
 
		
		$('#traching_div').hide();

		 $('#dp_driver_name').on('change',function(){
			 if($(this).val() >= 1){
				 $('#traching_div').show();
			 }
			 else{
                 $('#traching_div').hide();
			 }
        });
		


    });
 
</script>
SCRIPT;

sec_scripts($edit_script);

?>
