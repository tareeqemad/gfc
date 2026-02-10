<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 02/11/14
 * Time: 09:54 ص
 */

$count = 0;
$delete_details_url = base_url('payment/financial_payment/delete_details');



?>

<div class="tbl_container">
    <table class="table" id="chains_detailsTbl" data-container="container">
        <thead>
        <tr>
            <th style="width: 200px;"> رقم السيارة</th>
            <th style="width: 100px;">نوع الوقود</th>
            <th style="width: 100px;">كمية المقر</th>
            <th style="width: 200px;"> البيان</th>
            <?php if (count($details) > 0 && $case > 1) : ?>
                <th style="width: 100px;"> المدير المالي</th>
                <th> البيان</th>
                <?php if ($case > 2) : ?>
                    <th style="width: 100px;"> كمية مدير المقر</th>
                    <th> البيان</th>
                    <?php if ($case > 3) : ?>
                        <th style="width: 100px;"> كمية مدير الخدمات</th>
                        <th> البيان</th>
                        <?php if ($case > 4) : ?>
                            <th style="width: 100px;"> كمية مدير الشؤون الإدارية</th>
                            <th> البيان</th>
                            <?php if ($case > 5) : ?>
                                <th style="width: 100px;"> كمية إدارة اللوازم</th>
                                <th> البيان</th>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endif; ?>
            <th style="width: 80px;"></th>
            <th></th>
        </tr>
        </thead>

        <tbody>

        <?php if (count($details) <= 0) : ?>
            <tr>
                <td>
                    <input type="hidden" name="ser[]" value="">
                    <input type="hidden" name="car_num[]" id="h_car_num_0" value="">
                    <input type="text" readonly name="car_num_name[]" id="car_num_0" value="" class="form-control">
                </td>
                <td>

                    <!--  <select name="fuel_type[]" class="form-control">
                        <?php /*foreach ($fuel_type as $row): */ ?>
                            <option value="<? /*= $row['CON_NO'] */ ?>"><? /*= $row['CON_NAME'] */ ?></option>
                        <?php /*endforeach; */ ?>
                    </select>-->

                    <input type="text" id="fuel_type_name_car_num_0" class="form-control" readonly>
                    <input type="hidden" name="fuel_type[]" id="fuel_type_car_num_0">

                </td>
                <td><input type="text" name="branch_amount[]" value="" class="form-control"></td>
                <td><input type="text" name="hints[]" value="" class="form-control"></td>
                <?php if (count($details) > 0 && $case > 1) : ?>

                    <td><input type="text" name="financial_amount[]" value="" class="form-control"></td>
                    <td><input type="text" name="financial_note[]" value="" class="form-control"></td>

                    <td><input type="text" name="branch_approve_amount[]" value="" class="form-control"></td>
                    <td><input type="text" name="manager_note[]" value="" class="form-control"></td>

                    <td><input type="text" name="manager2_amount[]" value="" class="form-control"></td>
                    <td><input type="text" name="manager2_note[]" value="" class="form-control"></td>
                    <td><input type="text" name="manager3_amount[]" value="" class="form-control"></td>
                    <td><input type="text" name="manager4_note[]" value="" class="form-control"></td>
                    <td><input type="text" name="manager4_amount[]" value="" class="form-control"></td>
                    <td><input type="text" name="manager5_note[]" value="" class="form-control"></td>
                <?php endif; ?>
                <td><a onclick='javascript:show_car_balance(this);' class="btn-xs btn-info"
                       href='javascript:;'> الاحصاءات </a></td>
                <td></td>
            </tr>

        <?php else: $count = -1; ?>
        <?php endif; ?>

        <?php foreach ($details as $row) : ?>
            <?php $count++; ?>
            <tr>
                <td>
                    <input type="hidden" name="ser[]" value="<?= $row['SER'] ?>">
                    <input type="hidden" name="car_num[]" id="h_car_num_<?= $count ?>" value="<?= $row['CAR_NUM'] ?>">
                    <input type="text" readonly name="car_num_name[]" id="car_num_<?= $count ?>"
                           value="<?= $row['CAR_NUM_NAME'] ?>"
                           class="form-control">
                </td>
                <td>

                    <!--  <select name="fuel_type[]" class="form-control"  <? /*= $case > 1 ? 'readonly' : '' */ ?>>
                        <?php /*foreach ($fuel_type as $r): */ ?>
                            <option
                                value="<? /*= $r['CON_NO'] */ ?>" <? /*= $r['CON_NO'] == $row['FUEL_TYPE'] ? 'selected' : '' */ ?> ><? /*= $r['CON_NAME'] */ ?></option>
                        <?php /*endforeach; */ ?>
                    </select>-->


                    <input type="text" id="fuel_type_name_car_num_<?= $count ?>" value="<?= $row['FUEL_TYPE_NAME'] ?>"
                           class="form-control" readonly>
                    <input type="hidden" name="fuel_type[]" value="<?= $row['FUEL_TYPE'] ?>"
                           id="fuel_type_car_num_<?= $count ?>">

                </td>
                <td><input type="text" name="branch_amount[]"
                           value="<?= $row['BRANCH_AMOUNT'] ?>"   <?= $case > 1 ? 'readonly' : '' ?>
                           class="form-control">
                </td>
                <td><input type="text" name="hints[]" value="<?= $row['HINSTS'] ?>"  <?= $case > 1 ? 'readonly' : '' ?>
                           class="form-control"></td>
                <?php if (count($details) > 0 && $case > 1) : ?>

                    <td><input type="text" name="financial_amount[]"
                               value="<?= $row['FINANCIAL_AMOUNT'] ?>" <?= $case > 2 ? 'readonly' : '' ?>
                               class="form-control"></td>
                    <td><input type="text" name="financial_note[]"
                               value="<?= $row['FINANCIAL_NOTE'] ?>" <?= $case > 2 ? 'readonly' : '' ?>
                               class="form-control">
                    </td>
                    <?php if ($case > 2) : ?>
                        <td><input type="text" name="branch_approve_amount[]"
                                   value="<?= $row['BRANCH_APPROVE_AMOUNT'] ?>" <?= $case > 3 ? 'readonly' : '' ?>
                                   class="form-control"></td>
                        <td><input type="text" name="manager_note[]"
                                   value="<?= $row['MANAGER_NOTE'] ?>" <?= $case > 3 ? 'readonly' : '' ?>
                                   class="form-control">
                        </td>
                        <?php if ($case > 3) : ?>
                            <td><input type="text" name="manager2_amount[]"
                                       value="<?= $row['MANAGER2_AMOUNT'] ?>" <?= $case > 4 ? 'readonly' : '' ?>
                                       class="form-control"></td>
                            <td><input type="text" name="manager2_note[]"
                                       value="<?= $row['MANAGER2_NOTE'] ?>" <?= $case > 4 ? 'readonly' : '' ?>
                                       class="form-control">
                            </td>
                            <?php if ($case > 4) : ?>
                                <td><input type="text" name="manager3_amount[]"
                                           value="<?= $row['MANAGER3_AMOUNT'] ?>" <?= $case > 5 ? 'readonly' : '' ?>
                                           class="form-control"></td>
                                <td><input type="text" name="manager4_note[]"
                                           value="<?= $row['MANAGER4_NOTE'] ?>" <?= $case > 5 ? 'readonly' : '' ?>
                                           class="form-control">
                                </td>
                                <?php if ($case > 5) : ?>
                                    <td><input type="text" name="manager4_amount[]"
                                               value="<?= $row['MANAGER4_AMOUNT'] ?>"  <?= $case > 6 ? 'readonly' : '' ?>
                                               class="form-control"></td>
                                    <td><input type="text" name="manager5_note[]"
                                               value="<?= $row['MANAGER5_NOTE'] ?>"  <?= $case > 6 ? 'readonly' : '' ?>
                                               class="form-control">
                                    </td>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endif; ?>
                <td><a onclick='javascript:show_car_balance(this);' class="btn-xs btn-info"
                       href="javascript:"> الاحصاءات </a></td>
                <td></td>
            </tr>

        <?php endforeach; ?>

        </tbody>
        <tfoot>
        <tr>
            <th rowspan="1" class="align-right" colspan="14">
                <?php if (count($details) <= 0 || (isset($can_edit) ? $can_edit : false)) : ?>
                    <a onclick="add_row(this,'input,select');"
                       onfocus="add_row(this,'input,select');" href="javascript:"><i
                            class="glyphicon glyphicon-plus"></i>جديد</a>

                <?php endif; ?>
            </th>


        </tr>
        </tfoot>
    </table>
</div>


<script>
    if (typeof initFunctions == 'function') {
        initFunctions();
    }


</script>