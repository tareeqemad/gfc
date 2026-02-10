<?php
/**
 * Created by PhpStorm.
 * User: abarakat
 * Date: 21/05/17
 * Time: 08:57 ص
 */

$count = 1;

?>

<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>


    </div>

    <div class="form-body">

        <div class="alert alert-info">
           يساعد إدراج الاصناف علي البنودفي اخراج التقارير بشكل صحيح , لذا قم بإختيار الاصناف المناسبة فقط
        </div>
        <div class="tbl_container">
            <table class="table" id="projectTbl" data-container="container">
                <thead>
                <tr>

                    <th style="width: 10px;">#</th>
                    <th  >التجميع</th>
                    <th style="width: 50px;"></th>
                </tr>
                </thead>
                <tbody>

                <?php foreach ($rows as $row) : ?>
                    <tr>
                        <td><?= $count ?></td>
                        <td><?= $row['TITLE'] ?></td>
                        <td>
                            <a href="<?= base_url("technical/ItemCollections/get/{$row['SER']}") ?>"
                               class="btn btn-xs red"> الاصناف</a>
                        </td
                        <?php $count++ ?>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>
<script>
    if (typeof initFunctions == 'function') {
        initFunctions();
    }


</script>
