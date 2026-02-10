<?php
/**
 * Created by PhpStorm.
 * User: abarakat
 * Date: 9/9/2021
 * Time: 12:43 PM
 */
$MODULE_NAME= 'treasury';
$TB_NAME= 'workfield';
$canceled_bills= base_url("$MODULE_NAME/$TB_NAME/canceled_bills");

?>


<div class="form-body">
    <div class="alert alert-danger col-md-10 col-md-offset-1" >
        اجماليات التحصيل  الملغى الخاصة بمندوبي التحصيل الميداني
    </div>
    <table class="table info">
        <thead>
        <tr>
            <th>الرقم الوظيفي</th>
            <th>الاسم</th>
            <th>التكلفة</th>
            <th style="width: 100px;"></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $row) : ?>
            <tr>
                <td><?= $row['NO'] ?></td>
                <td><?= $row['NAME'] ?></td>
                <td><strong><?= $row['TOTAL'] ?></strong></td>
                <td>
                    <?php if(HaveAccess($canceled_bills)){ ?>
                        <a class="btn btn-xs btn-primary"
                           href="<?= base_url('treasury/workfield/canceled_bills/'.$row['NO'].'/'.$date) ?>">عرض السند</a>
                    <?php } ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
