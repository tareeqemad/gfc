<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 12/01/15
 * Time: 01:20 م
 */
$count = 0;
?>

<div class="tbl_container">
    <table class="table" id="projects_detailsTbl" data-container="container">
        <thead>
        <tr>
            <th><a href="javascript:;" style="color: #F0E34E;" onclick="changeLang()">تغير اللغة</a> رقم الصنف</th>
            <th style="width: 100px"> الوحدة</th>
            <th style="width: 100px"> الحالة</th>
            <th style="width: 100px"> الكمية</th>
            <th style="width: 100px"> كمية العميل</th>
            <th></th>
        </tr>
        </thead>

        <tbody>



        <?php foreach ($details

        as $row) : ?>


        <?php $count++; ?>
        <tr>
            <td>
                <input type="hidden" name="SER[]" value="<?= $row['SER'] ?>">
                <input type="hidden" name="ITEM_CASE[]" value="<?= $row['ITEM_CASE'] ?>">
                <input type="text" name="class_id[]" value="<?= $row['CLASS_ID'] ?>" id="h_class_id_<?= $count ?>"
                       class="form-control col-sm-3">
                <input name="class_id_name[]" data-val="true" value='<?= $row['CLASS_ID_NAME'] ?>'
                       data-arabic="<?= $row['CLASS_ID_NAME'] ?>" data-english="<?= $row['CLASS_ID_NAME_EN'] ?>"
                       readonly data-val-required="حقل مطلوب" class="form-control col-sm-9" readonly
                       id="class_id_<?= $count ?>">
            </td>
            <td>
                <input name="class_unit_name[]" value="<?= $row['UNIT_NAME'] ?>" readonly data-val="true"
                       data-val-required="حقل مطلوب" class="form-control" id="unit_class_id_<?= $count ?>">
                <input name="class_unit[]" value="<?= $row['CLASS_UNIT'] ?>" type="hidden"
                       id="h_unit_class_id_<?= $count ?>">

            </td>

            <td>

                <select disabled name="class_type[]" class="form-control">
                    <option <?= $row['CLASS_TYPE'] == 1 ? 'selected' : '' ?> value="1">جديد</option>
                    <option <?= $row['CLASS_TYPE'] == 2 ? 'selected' : '' ?> value="2">مستعمل</option>
                </select>


            </td>
            <td>
                <input readonly name="amount[]" value="<?= $row['AMOUNT'] ?>" data-val="true" data-val-required="حقل مطلوب"
                       class="form-control" id="amount_class_id_<?= $count ?>">
            </td>
            <td>
                <input name="customer_amount[]" value="<?= $row['CUSTOMER_AMOUNT'] ?>" data-val="true"
                       data-val-required="حقل مطلوب" class="form-control" id="customer_amount_id_<?= $count ?>">
            </td>


            <?php endforeach; ?>

        </tbody>

    </table>
</div>

<script>
    if (typeof initFunctions == 'function') {
        initFunctions();
    }


</script>