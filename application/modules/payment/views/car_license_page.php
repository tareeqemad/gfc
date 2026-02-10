<?php
/**
 * Created by PhpStorm.
 * User: ljasser
 * Date: 20/04/19
 * Time: 11:55 ص
 */

$count = 1;



?>


<h3>التغير في بيانات الترخيص</h3>
<div class="tb2_container">
    <table class="table" id="chainsTbl" data-container="container">
        <thead>
        <tr>
            <th>#</th>
            <th>  رقم السيارة</th>
            <th> رقم الترخيص </th>
            <th>تاريخ بداية الترخيص </th>
            <th>  تاريخ نهاية الترخيص </th>
            <th>قيمة الترخيص</th>
            <th>تاريخ الترخيص </th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($rows as $row) : ?>
            <tr>


                <td><?= $count ?></td>
                <td><?= $row['CAR_ID'] ?></td>
                <td><?= $row['CAR_LICENSE_NUMBER'] ?></td>
                <td><?= $row['CAR_LICENSE_START'] ?></td>
                <td><?= $row['CAR_LICENSE_END'] ?></td>
                <td><?= $row['CAR_LICENSE_VALUE'] ?></td>
                <td><?= $row['DATE_OF_LICENSE'] ?></td>
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