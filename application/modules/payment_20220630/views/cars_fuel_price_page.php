<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Ahmed barakat
 * Date: 16/11/16
 * Time: 09:06 ص
 */

$MODULE_NAME = 'payment';
$TB_NAME = 'cars_fuel_price';

$get_url = base_url("$MODULE_NAME/$TB_NAME/get_id");
$edit_url = base_url("$MODULE_NAME/$TB_NAME/edit");
$adopt_url = base_url("$MODULE_NAME/$TB_NAME/adopt");

?>

<table class="table" id="cars_fuel_price_tb" data-container="container">
    <thead>
    <tr>
        <th>#</th>
        <th>التاريخ</th>
        <th>الوقود</th>
        <th>السعر</th>
        <th> اسم المورد</th>
        <th>المدخل</th>
        <th></th>
    </tr>
    </thead>

    <tbody>
    <?php
    $count = 1;
    foreach ($get_all as $row): ?>
        <tr ondblclick="javascript:cars_fuel_price_get(<?= $row['PRFUEL_SER'] ?>);">
            <td><?= $count ?></td>
            <td><?= $row['FUEL_MONTH_PRICE'] ?></td>
            <td><?= $row['GASOLINE_ID_NAME'] ?></td>
            <td><?= $row['GASOLINE_PRICE'] ?></td>
            <td><?= $row['SUPPLIER_NAME'] ?></td>
            <td><?= $row['ENTRY_USER_NAME'] ?></td>
            <td> <?php if (HaveAccess($adopt_url) &&  $row['CARS_ADDITIONAL_FUEL'] <= 1): ?><a
                    onclick='javascript:cars_fuel_price_adopt(<?= $row['PRFUEL_SER'] ?>);' href='javascript:;'><i
                            class='glyphicon glyphicon-check'></i>اعتماد </a>  <?php endif; ?></td>
        </tr>
        <?php $count++;  endforeach; ?>
    </tbody>

</table>

<script type="text/javascript">
    try {

        $(document).ready(function () {
            $('#cars_fuel_price_tb').dataTable({
                "lengthMenu": [
                    [10, 20, 30, 40, 50, 100, -1],
                    [10, 20, 30, 40, 50, 100, "الكل"]
                ],
                "sPaginationType": "full_numbers"
            });
        });
    } catch (err) {
    }

    if (typeof initFunctions == 'function') {
        initFunctions();
    }
</script>
