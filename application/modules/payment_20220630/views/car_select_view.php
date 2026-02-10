<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 01/12/14
 * Time: 09:19 ص
 */
?>

<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>

        </ul>

    </div>

    <div class="form-body">

        <div id="msg_container"></div>
        <div class="form-group">
            <div class="input-group col-sm-4">
                <span class="input-group-addon">  <i class="icon icon-search"></i></span>

                <input type="text" id="search-tbl" data-set="accountsTbl" class="form-control" placeholder="بحث">
            </div>
        </div>
        <div id="container">
            <table class="table selected-red" id="accountsTbl" data-container="container">
                <thead>
                <tr>
                    <th>رقم الملف</th>
                    <th> رقم السيارة</th>
                    <th> صاحب العهدة</th>
                    <th>نوع السيارة</th>
                    <th>المقر</th>


                </tr>
                </thead>
                <tbody>

                <?php foreach ($rows as $row) : ?>
                    <tr ondblclick="javascript:select_account('<?= $row['CAR_FILE_ID'] ?>','<?= $row['CAR_NUM']  . '  :  ' . $row['CAR_OWNER'] ?>','<?= $row['FUEL_TYPE'] ?>','<?= $row['FUEL_TYPE_NAME'] ?>','<?= $row['CAR_CASE'] ?>');">

                        <td><?= $row['CAR_FILE_ID'] ?></td>
                        <td><?= $row['CAR_NUM'] ?></td>
                        <td><?= $row['CAR_OWNER'] ?></td>
                        <td><?= $row['CAR_CLASS_NAME'] ?></td>
                        <td><?= $row['BRANCH_ID_NAME'] ?></td>
                    </tr>



                <?php endforeach; ?>

                </tbody>

            </table>
        </div>

    </div>
</div>

<?php


$scripts = <<<SCRIPT

<script>
    $(function () {

        $('#accountsModal').on('shown.bs.modal', function () {
            $('#txt_acount_name').focus();
        });


    });

    function select_account(id,name,type,type_name,car_case){
            var text = name;
            parent.$('#$txt').val('سيارة رقم '+text);
            parent.$('#h_$txt').val(id);
            parent.$('#fuel_type_$txt').val(type);
            parent.$('#fuel_type_name_$txt').val(type_name);
			parent.$('#car_case_id').val(car_case);
            parent.$('#report').modal('hide');

        if (typeof parent.afterSelect == 'function') {
            parent.afterSelect();
        }

    }

</script>

SCRIPT;

sec_scripts($scripts);



?>

