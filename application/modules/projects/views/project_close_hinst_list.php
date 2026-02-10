<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * project: Ahmed Barakat
 * Date: 8/28/14
 * Time: 8:46 AM
 */

$count = 0;


?>

<?php if(count($rows)) : ?>
<div class="tbl_container">
    <table class="table" id="projectTbl" data-container="container">
        <thead>
        <tr>

            <th style="width: 20px;">#</th>
            <th style="width: 120px;">رقم الملاحظات</th>
            <th> البيان</th>
            <th style="width: 80px;">  التاريخ</th>


        </tr>
        </thead>
        <tbody>

        <?php foreach ($rows as $row) : ?>
            <tr>

                <td><?= $count ?></td>
                <td><?= $row['SER'] ?></td>
                <td><?= $row['HINTS'] ?></td>
                <td><?= $row['HINTS_DATE'] ?></td>

                <?php $count++ ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif;?>