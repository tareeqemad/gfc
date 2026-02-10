<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 13/05/19
 * Time: 11:51 ص
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
                    <th> رقم السيارة</th>
                    <th>اسم صاحب السيارة</th>
                    <th>المقر</th>


                </tr>
                </thead>
                <tbody>

                <?php foreach ($rows as $row) : ?>
                    <tr ondblclick="javascript:select_account('<?= $row['NO']?>','<?= $row['NO'] . ' :  ' . $row['NAME']  ?>');">

                        <td><?= $row['NO'] ?></td>
                        <td><?= $row['NAME'] ?></td>
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

    function select_account(id,name){
            parent.$('#$txt').val(name);
            parent.$('#h_$txt').val(id);
            parent.$('#report').modal('hide');

        if (typeof parent.afterSelect == 'function') {
            parent.afterSelect();
        }

    }

</script>

SCRIPT;

sec_scripts($scripts);



?>

