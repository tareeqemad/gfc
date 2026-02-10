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
    <table class="table" id="return_items_detailsTbl" data-container="container">
        <thead>
        <tr>
            <th style="width: 500px"><a href="javascript:;" style="color: #F0E34E;" onclick="changeLang()">تغير اللغة</a> رقم الصنف</th>
            <th style="width: 100px"> الحالة</th>
            <th style="width: 100px"> الكمية</th>
            <th style="width: 100px"> السعر</th>
            <th></th>
        </tr>
        </thead>

        <tbody>

        <?php if (count($details) <= 0) : ?>
            <tr>
                <td>
                    <input type="hidden" name="SER[]" value="0">
                    <input type="text" name="class_id[]" id="h_return_class_id_<?= $count ?>" class="form-control col-sm-3">
                    <input name="class_id_name[]" data-val="true" readonly data-val-required="حقل مطلوب"
                           class="form-control col-sm-9" readonly id="return_class_id_<?= $count ?>">
                </td>
                <td>
                    <select name="class_type[]" class="form-control">
                       <!-- <option value="1">جديد</option>-->
                        <option value="2" selected>مستعمل</option>
                    </select>
                </td>
                <td>
                    <input name="count[]" data-val="true" data-val-required="حقل مطلوب" class="form-control"
                           id="count_<?= $count ?>">
                </td>
                <td>
                    <input type="hidden" name="befor_up_sal_price[]" id="bu_price_return_class_id_<?= $count ?>">
                    <input type="hidden" name="used_price[]" id="xused_price_return_class_id_<?= $count ?>">
                    <input type="hidden" name="used_buy_price[]" id="used_buy_price_return_class_id_<?= $count ?>">
                    <input name="price[]" readonly data-val="true" data-val-required="حقل مطلوب" class="form-control"
                           id="used_price_return_class_id_<?= $count ?>">
                </td>

                <td data-empty="true">
                    <a href="javascript:;" onclick="javascript:delete_return_details(this,0);"><i class="icon icon-trash delete-action"></i> </a>

                </td>

            </tr>

        <?php else: $count = -1; ?>
        <?php endif; ?>

        <?php foreach ($details as $row) : ?>

            <?php $count++; ?>
            <tr>
                <td>
                    <input type="hidden" name="SER[]" value="<?= $row['SER'] ?>">

                    <input type="text" name="class_id[]" value="<?= $row['CLASS_ID'] ?>" id="h_return_class_id_<?= $count ?>"
                           class="form-control col-sm-3">
                    <input name="class_id_name[]" data-val="true" value='<?= $row['CLASS_NAME_AR'] ?>'
                           data-arabic="<?= $row['CLASS_NAME_AR'] ?>"
                           readonly data-val-required="حقل مطلوب" class="form-control col-sm-9" readonly
                           id="return_class_id_<?= $count ?>">
                </td>

                <td>
                    <select name="class_type[]" class="form-control">
                       <!-- <option <?/*= $row['CLASS_TYPE'] == 1 ? 'selected' : '' */?> value="1">جديد</option>-->
                        <option <?= $row['CLASS_TYPE'] == 2 ? 'selected' : '' ?> value="2">مستعمل</option>
                    </select>

                </td>
                <td>
                    <input name="count[]" value="<?= $row['COUNT'] ?>" data-val="true"
                           data-val-required="حقل مطلوب"
                           class="form-control" id="count_id_<?= $count ?>">
                </td>

                <td>

                    <input type="hidden" name="befor_up_sal_price[]" id="bu_price_return_class_id_<?= $count ?>">
                    <input type="hidden" name="used_price[]" id="xused_price_return_class_id_<?= $count ?>">
                    <input type="hidden" name="used_buy_price[]" id="used_buy_price_return_class_id_<?= $count ?>">
                    <input name="price[]"  value="<?= $row['PRICE'] ?>" readonly data-val="true" data-val-required="حقل مطلوب" class="form-control"
                           id="used_price_return_class_id_<?= $count ?>">
                </td>
                <td>
                    <a href="javascript:;" onclick="javascript:delete_return_details(this,<?= $row['SER'] ?>,<?= $row['PROJECT_SER'] ?>);"><i class="icon icon-trash delete-action"></i> </a>

                </td>
            </tr>

        <?php endforeach; ?>

        </tbody>

        <tfoot>
        <tr>
            <th rowspan="1" class="align-right" colspan="4">
                <a onclick="javascript:addRow();" onfocus="javascript:add_row(this,'input,select');" href="javascript:;"><i
                            class="glyphicon glyphicon-plus"></i>جديد</a>
            </th>
            <th></th>
        </tr>
        </tfoot>

    </table>
</div>

<script>
    if (typeof initFunctions == 'function') {
        initFunctions();
    }


</script>