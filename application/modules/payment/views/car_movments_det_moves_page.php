<?php

$count = 1;
?>

<div class="movements_container">
    <table class="table" id="moveTbl" data-container="container">
        <thead>
            <tr>
                <th>وقت المغادرة المتوقع</th>
                <th>وقت الوصول المتوقع</th>
                <th>عدد الاتجاهات</th>
                <th>عدد الركاب</th>
                <th>المدة المتوقعة للعمل</th>
                <th>نوع المهمة</th>
                <th>المحافظة</th>
                <th>نوع الوجهة</th>
                <th>عنوان الانطلاق</th>
                <th>عنوان الوجهة (1)</th>
                <th>عنوان الوجهة (2)</th>
                <th>عنوان الوجهة (3)</th>
                <th>عنوان الوجهة (4)</th>
                <th>عنوان الوجهة (5)</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($rows as $row) :?>

                <tr data-leave-time="<?=$row['EXPECTED_LEAVE_TIME']?>" data-arrival-time="<?=$row['EXPECTED_ARRIVAL_TIME']?>" data-from-address="<?=$row['FROM_ADDRESS']?>" data-address-1="<?=$row['DESTINATION_ADDRESS_1']?>"  data-address-2="<?=$row['DESTINATION_ADDRESS_2']?>" data-address-3="<?=$row['DESTINATION_ADDRESS_3']?>" data-address-4="<?=$row['DESTINATION_ADDRESS_4']?>" data-address-5="<?=$row['DESTINATION_ADDRESS_5']?>"  data-start-gps="<?=$row['START_GPS']?> "  data-GPS-1="<?=$row['DESTINATION_GPS_1']?> " data-GPS-2="<?=$row['DESTINATION_GPS_2']?> " data-GPS-3="<?=$row['DESTINATION_GPS_3']?> " data-GPS-4="<?=$row['DESTINATION_GPS_4']?> " data-GPS-5="<?=$row['DESTINATION_GPS_5']?> " >

                    <td style="width: 7%"><?= $row['EXPECTED_LEAVE_TIME'] ?></td>
                    <td style="width: 7%"><?= $row['EXPECTED_ARRIVAL_TIME'] ?></td>
                    <td ><?= $row['DIRECTIONS_NO'] ?></td>
                    <td><?= $row['PASSENGERS_NO'] ?></td>
                    <td><?= $row['EXPECTED_DURATION'] ?></td>
                    <td style="width: 7%"><?= $row['TASK_TYPE_NAME'] ?></td>
                    <td><?= $row['GOVERNORATE_ID_NAME'] ?></td>
                    <td style="width: 7%"><?= $row['DESTINATION_TYPE_NAME'] ?></td>

                    <td style="width: 12%"><?= $row['FROM_ADDRESS'] ?>
                        <?php if ( $row['DESTINATION_ADDRESS_1'] != null  ) :  ?>
                        <i  id="move_location" class="glyphicon glyphicon-plus" style="color:cornflowerblue;"></i>
                        <?php endif; ?>
                    </td>

                    <td style="width: 12%"><?= $row['DESTINATION_ADDRESS_1'] ?>
                        <?php if ( $row['DESTINATION_ADDRESS_2'] != null  ) :  ?>
                        <i  id="move_location_1" class="glyphicon glyphicon-plus" style="color:cornflowerblue;"></i>
                        <?php endif; ?>
                    </td>

                    <td style="width: 12%"><?= $row['DESTINATION_ADDRESS_2'] ?>
                        <?php if ( $row['DESTINATION_ADDRESS_3'] != null  ) :  ?>
                        <i id="move_location_2" class="glyphicon glyphicon-plus" style="color:cornflowerblue; "></i>
                        <?php endif; ?>
                    </td>

                    <td style="width: 12%"><?= $row['DESTINATION_ADDRESS_3'] ?>
                        <?php if ( $row['DESTINATION_ADDRESS_4'] != null  ) :  ?>
                        <i id="move_location_3" class="glyphicon glyphicon-plus" style="color:cornflowerblue;"></i>
                        <?php endif; ?>
                    </td>

                    <td style="width: 12%"><?= $row['DESTINATION_ADDRESS_4'] ?>
                        <?php if ( $row['DESTINATION_ADDRESS_5'] != null  ) :  ?>
                        <i id="move_location_4" class="glyphicon glyphicon-plus" style="color:cornflowerblue;"></i>
                        <?php endif; ?>
                    </td>

                    <td style="width: 12%"><?= $row['DESTINATION_ADDRESS_5'] ?></td>

                </tr>

            <?php endforeach; ?>
        </tbody>
    </table>
</div>

