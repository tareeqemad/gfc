<?php
/**
 * Created by PhpStorm.
 * User: abarakat
 * Date: 5/2/2020
 * Time: 9:52 AM
 */

$count = 1;
?>

<div class="row">
    <div class="toolbar">
        <div class="caption"><?= $title ?></div>
        <ul>
           <!-- <li><a onclick="javascript:charge_dxn();" href="javascript:;"><i
                            class="glyphicon glyphicon-plus"></i>شحن </a></li> -->
        </ul>
    </div>

    <div class="container">

        <div class="form-body">

            <div class="form-group">
                <div class="input-group col-sm-4">
                    <span class="input-group-addon">  <i class="icon icon-search"></i></span>

                    <input type="text" id="search-tbl" data-set="dxn_employee" class="form-control" placeholder="بحث">
                </div>
            </div>

            <table class="table" id="dxn_employee">
                <thead>
                <tr>
                    <th>#</th>
                    <th>رقم الموظف</th>
                    <th>اسم الموظف</th>
                    <th>رقم الاشتراك</th>
                    <th>الشهر</th>
                    <th>القيمة</th>
                    <th>Token</th>
                    <th style="width: 90px;"></th>
                </tr>
                </thead>

                <tbody>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><?= $count ?></td>
                        <td><?= $row['EMP_NO'] ?></td>
                        <td><?= $row['EMP_NAME'] ?></td>
                        <td><?= $row['SUB_NO'] ?></td>
                        <td><?= $row['FOR_MONTH'] ?></td>
                        <td><?= $row['THE_VALUE'] ?></td>
                        <td><?= $row['TOKEN'] ?></td>
                        <td></td>
                    </tr>

                    <?php $count++; endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

