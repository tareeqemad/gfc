<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 24/04/19
 * Time: 11:27 ص
 */
$count = 1;
$preDefineGPS = array();
$actaulGPS = array();
?>



<div class="movements_container">
    <table class="table" id="movementsTbl" data-container="container">
        <thead>
        <tr>

            <th>#</th>
            <th>وقت المغادرة المتوقع</th>
            <th>وقت الوصول المتوقع</th>
            <th>موقع الانطلاق</th>
            <th>موقع انهاء الحركة</th>
            <th>موقع الانطلاق المحدد مسبقاً</th>
            <th>موقع الانهاء المحدد مسبقاً</th>
            <th>الغرض من الحركة</th>
            <th>الحالة</th>

        </tr>
        </thead>
        <tbody>
        <?php foreach ($rows as $row) :

            array_push($preDefineGPS, "{$row['PREDEFINE_START_GPS']}~{$row['PREDEFINE_FINISHED_GPS']}~{$row['FROM_ADDRESS']}~{$row['TO_ADDRESS']}");
            array_push($actaulGPS, "{$row['START_GPS_LOCATION']}~{$row['FINISHED_GPS_LOCATION']}");

            ?>
            <tr ondblclick="get_car_movement_det(<?= $row['SER'] ?>);"
                data-toggle="modal"
                      data-target="#showmsgrec">

                <td><?= $count ?></td>
                <td><?= $row['EXPECTED_LEAVE_TIME_T'] ?></td>
                <td><?= $row['EXPECTED_ARRIVAL_TIME_T'] ?></td>
                <td><?= $row['FROM_ADDRESS'] ?></td>
                <td><?= $row['TO_ADDRESS'] ?></td>
                <td><?= $row['FROM_ACTUAL_ADDRESS'] ?></td>
                <td><?= $row['TO_ACTUAL_ADDRESS'] ?></td>
                <td><?= $row['PURPOSE_TYPE_NAME'] ?></td>
                <td><span class="badge badge-<?= $row['STATUS'] ?>"><?= $row['STATUS_NAME'] ?></span></td>
                <?php $count++ ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
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