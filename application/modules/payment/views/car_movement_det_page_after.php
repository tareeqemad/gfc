<?php

$count = 1;

?>

<div class="movements_container"  >
    <table class="table" id="movementsTbl" data-container="container">
        <thead>
        <tr>

            <th>#</th>
            <th>رقم الحركة</th>
            <th>وقت المغادرة المتوقع</th>
            <th>وقت الوصول المتوقع</th>
            <th>المسافة المتوقعة بالكيلو متر</th>
            <th>المسافة الحقيقية بالكيلو متر</th>
            <th>موقع الانطلاق</th>
            <th>موقع انهاء الحركة</th>
            <th>وقت المغادرة الحقيقي</th>
            <th>وقت الوصول الحقيقي</th>
            <th>الوقت الكلي للحركة بالدقيقة</th>
            <th>عدد جولات الإنتظار</th>
            <th>المدة الزمنية للإنتظار</th>
            <th>الغرض من الحركة</th>
            <th>سبب الالغاء</th>
            <th>الحالة</th>
            <th>تتبع</th>
            <th>إلغاء</th>

        </tr>
        </thead>
        <tbody>
        <?php foreach ($rows as $row) : ?>

            <tr ondblclick="get_car_movement_det(<?= $row['SER'] ?>);"  data-start-location="<?=$row['START_GPS_LOCATION']?>"  data-finished-location="<?=$row['FINISHED_GPS_LOCATION']?>"  data-actual-leave-time="<?=$row['ACTUAL_LEAVE_TIME_T']?>" data-actual-arrival-time="<?=$row['ACTUAL_ARRIVAL_TIME_T']?>"
                data-toggle="modal"
                data-target="#showmsgrec">

                <td><?= $count ?></td>
                <td><?= $row['SER'] ?></td>
                <td><?= $row['EXPECTED_LEAVE_TIME_T'] ?></td>
                <td><?= $row['EXPECTED_ARRIVAL_TIME_T'] ?></td>
                <td><?= $row['EXPECTED_DISTANCE'] ?></td>
                <?php if ( $row['ACTUAL_DISTANCE'] > ($row['EXPECTED_DISTANCE']+0.500)  ) :  ?>
                    <td style="color:red;background-color:#f3dfdf;"><?= $row['ACTUAL_DISTANCE'] ?></td>
                <?php else : ?>
                    <td><?= $row['ACTUAL_DISTANCE'] ?></td>
                <?php endif; ?>
                <td><?= $row['FROM_ADDRESS'] ?></td>
                <td><?= $row['TO_ADDRESS'] ?></td>
                <td><?= $row['ACTUAL_LEAVE_TIME_T'] ?></td>
                <td><?= $row['ACTUAL_ARRIVAL_TIME_T'] ?></td>
                <td><?= $row['MINS'] ?></td>
                <td><?= $row['MOVE_ID'] ?></td>
                <td><?= $row['WAITING_MINS'] ?></td>
                <td><?= $row['PURPOSE_TYPE_NAME'] ?></td>

                <?php if (  $row['STATUS'] == 0  ) :  ?>
                    <td><?= $row['NOTES'] ?></td>
                <?php else : ?>
                    <td> <h1 > </h1></td>
                <?php endif; ?>
                
                <td><span class="badge badge-<?= $row['STATUS'] ?>"><?= $row['STATUS_NAME'] ?></span></td>

                <?php if (  $row['STATUS'] >= 4  ) :  ?>
                    <td> <button type="button" onclick="show_row_details('<?=$row['SER']?>');" class="btn btn-success btn-xs">تتبع</button></td>
                <?php else : ?>
                    <td> <h1 > </h1></td>
                <?php endif; ?>

                <?php if ( $row['STATUS'] != 0 && $row['STATUS'] < 4  ) :  ?>
                    <td> <button type="button" onclick="changeStatus_move_det_(0,'<?=$row['SER']?>');" class="btn btn-danger btn-xs">إلغاء</button></td>
                <?php else : ?>
                    <td> <h1 > </h1></td>
                <?php endif; ?>

                <?php $count++ ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    if (typeof initFunctions == 'function') {
        initFunctions();
    }
</script>