<?php
/**
 * Created by PhpStorm.
 * User: abarakat
 * Date: 5/4/2020
 * Time: 11:02 AM
 */
$count = 1;
?>

<div class="row">
    <div class="toolbar">
        <div class="caption"><?= $title ?></div>
        <ul>
            <li><a onclick="javascript:create_dxn_employee();" href="javascript:;"><i
                            class="glyphicon glyphicon-plus"></i>جديد </a></li>
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
                    <th>الجوال</th>
                    <th>الحالة</th>
                    <th style="width: 90px;"></th>
                </tr>
                </thead>

                <tbody>
                <?php foreach ($rows as $row): ?>
                    <tr class="<?= $row['IS_ACTIVE'] == 1 ? "" :"case_0" ?>">
                        <td><?= $count ?></td>
                        <td><?= $row['EMP_NO'] ?></td>
                        <td><?= $row['EMP_NAME'] ?></td>
                        <td><?= $row['SUB_NO'] ?></td>
                        <td><?= $row['JWAL_NO'] ?></td>
                        <td><?= $row['IS_ACTIVE'] == 1 ? "فعال" : "موقوف" ?></td>
                        <td>
                            <?php if ($row['IS_ACTIVE'] == 1) : ?>
                                <a href="javascript:;" onclick="javascript:update_employee_status(this,<?= $row['SER'] ?>,0)"
                                   class="btn btn-danger btn-xs">ايقاف</a>
                            <?php else: ?>
                                <a href="javascript:;" onclick="javascript:update_employee_status(this,<?= $row['SER'] ?>,1)"
                                   class="btn btn-success btn-xs">تفعيل</a>
                            <?php endif; ?>

                            <a href="javascript:;" onclick="javascript:edit_dxn_employee(<?= $row['SER'] ?>)"
                               class="btn btn-info btn-xs">تحرير</a>

                        </td>
                    </tr>

                <?php $count ++; endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
