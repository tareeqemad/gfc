<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 13/05/19
 * Time: 12:39 م
 */

$count = 1;



?>


<h3>التغير في الاهلاكات</h3>
<div class="tb2_container">
    <table class="table" id="chainsTbl" data-container="container">
        <thead>
        <tr>
            <th>#</th>
            <th>سعر السيارة</th>
            <th>نسبة الاهلاك</th>
            <th>نسبة الاهلاك الجديدة</th>
            <th>تاريخ الاهلاك</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($rows as $row) : ?>
            <tr>

                <td><?= $count ?></td>
                <td><?= $row['PRICE'] ?></td>
                <td><?= $row['DEPRECIATION_RATE'] ?></td>
                <td><?= $row['NEW_RATE'] ?></td>
                <td><?= $row['DEPRECIATION_DATE'] ?></td>
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