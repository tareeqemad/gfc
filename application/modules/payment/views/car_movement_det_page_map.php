<?php

$count = 1;
$preDefineGPS = array();
$actaulGPS = array();

?>

<div class="movements_container"  >

        <tbody>
        <?php foreach ($rows as $row) :

            array_push($preDefineGPS, "{$row['PREDEFINE_START_GPS']}~{$row['PREDEFINE_FINISHED_GPS']}~{$row['FROM_ADDRESS']}~{$row['TO_ADDRESS']}");
            array_push($actaulGPS, "{$row['START_GPS_LOCATION']}~{$row['FINISHED_GPS_LOCATION']}"); ?>
        <?php endforeach; ?>

</div>

<fieldset class="field_set">
    <legend>الخريطة</legend>
    <div id="movements_map" style="height: 500px;"></div>
</fieldset>


<script>

    if (typeof initFunctions == 'function') {
        initFunctions();
    }

    <?php
    $preDefineGPS_json = json_encode($preDefineGPS);
    $actaulGPS_json = json_encode($actaulGPS);
    echo "var preDefinedGPS = '{$preDefineGPS_json}'; var actualGPS = '{$actaulGPS_json}'; ";
    ?>

</script>
