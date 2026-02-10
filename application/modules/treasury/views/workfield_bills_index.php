<?php
/**
 * Created by PhpStorm.
 * User: abarakat
 * Date: 9/9/2021
 * Time: 1:30 PM
 */

$sum = count($bills) <= 0 ? 0 : array_sum(array_column($bills, 'THE_VALUE'));

$MODULE_NAME= 'treasury';
$TB_NAME= 'workfield';
$cancel_bills_url= base_url("$MODULE_NAME/$TB_NAME/cancel_bills");
$edit_bills_url= base_url("$MODULE_NAME/$TB_NAME/edit_bills");
$close_bills_url= base_url("$MODULE_NAME/$TB_NAME/close_bills");
$cancel_adopt_bills_url= base_url("$MODULE_NAME/$TB_NAME/cancel_adopt");
$report_bills_url= base_url("$MODULE_NAME/$TB_NAME/report_bills");
?>


<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?> <strong><?= isset($user[0]['NAME']) ? $user[0]['NAME'] : '' ?></strong></div>

        <ul>
            <li><a href="<?= $back_url ?>"><i class="icon icon-reply"></i> </a></li>
        </ul>

    </div>

    <div class="form-body">

        <div class="alert alert-info"> سندات التحصيل الميداني الخاصة للموظف /
            <strong><?= isset($user[0]['NAME']) ? $user[0]['NAME'] : '' ?></strong></div>

        <div class="container">
            <div class="info">
                <span class="alert alert-info box floatLeft  mr-20">
                    الاجمالي
                     <strong class=""><?= $sum ?></strong>
                </span>

                <span class="alert alert-danger box floatLeft">
                    اجمالي محدد
                     <strong class="" id="total-selected">0</strong>
                </span>


                <?php //if( HaveAccess($close_bills_url)):  ?>
                <!--<button class="btn btn-success" style="line-height: 95px;" onclick="javascript:close_selected_bills();">
                    اغلاق المحدد
                </button> -->
                <?php //endif; ?>
            </div>


        </div>

        <table class="table info" id="Tbl">
            <thead>
            <tr>
                <th>#
                </td>
                <th><input type="checkbox" class="group-checkable" data-set="#Tbl .checkboxes"/></th>
                <th>الاشتراك</th>
                <th>الاسم</th>
                <th>العنوان</th>
                <th>الجوال</th>
                <th>الشهر</th>
                <th>المبلغ</th>
                <th>التاريخ</th>
                <th>حالة الإيصال</th>

                <th style="width: 170px;">
                </td>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($bills as $row) : ?>

                <tr class="<?= $row['SOURCE'] == 2 ? 'case_3' : '' ?>">
                    <td><?= $row['SER'] ?></td>
                    <td> <?php if ($row['SOURCE'] != 2): ?>
                            <input type="checkbox" class="checkboxes"
                                   data-value="<?= $row['THE_VALUE'] ?>"
                                   value="<?= $row['SER'] ?>"/>
                        <?php endif; ?>
                    </td>
                    <td><?= $row['SUBSCRIBE'] ?></td>
                    <td><?= $row['NAME'] ?></td>
                    <td><?= $row['ADDRESS'] ?></td>
                    <td><?= $row['MOBILE'] ?></td>
                    <td><?= $row['MONTH'] ?></td>
                    <td><strong><?= $row['THE_VALUE'] ?></strong></td>
                    <td><?= $row['CDATE'] ?></td>
                    <td><?= $row['STATUS_NAME'] ?></td>
                    <td class="text-right">
                        <?php if( HaveAccess($cancel_adopt_bills_url)):  ?>
                        <button type="button"
                                class="btn btn-xs btn-danger"
                                onclick="javascript:cancel_workfield_adopt_bill(this,<?= $row['SER'] ?>);">الغاء الاعتماد
                        </button>
                        <?php endif; ?>



                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="7"><strong>المجموع</strong></td>
                <td><strong><?= $sum ?></strong></td>
                <td colspan="2"></td>
            </tr>
            </tfoot>
        </table>

        <?php //if( HaveAccess($report_bills_url)):  ?>
            <!--<div class="modal-footer">
                <button type="button" onclick="javascript:print_report(<?=isset($user[0]['NO']) ? $user[0]['NO'] : '' ?>,<?=$this->user->id?>,2);" class="btn btn-success"><span class="glyphicon glyphicon-print"></span>عرض التقرير</button>
            </div> -->
        <?php //endif; ?>

    </div>

