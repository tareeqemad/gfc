<?php
/**
 * Created by PhpStorm.
 * User: ljasser
 * Date: 17/09/19
 * Time: 09:53 ص
 */

$count = 0;

?>
<div class="tbl_container">
    <table class="table" id="carsTbl" data-container="container">
        <thead>
        <tr>

            <th  >#</th>
            <th>رقم المستند</th>
            <th>تاريخ المستند</th>
            <th>نسخة المستند</th>
            <th>بيان المستند</th>
            <th>المبلغ</th>
            <th>تحرير <th>

                <?php
                $count++;
                ?>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($rows as $row) : ?>
            <tr>
                <td><?= $count ?></td>
                <td><?= $row['DOCUMENT_NUMBER'] ?></td>
                <td><?= $row['DOCUMENT_DATE'] ?></td>
                <td><?= $row['COPY_THE_DOCUMENT'] ?></td>
                <td><?= $row['DOCUMENT_STATEMENT'] ?></td>
                <td><?= $row['AMOUNT'] ?></td>

                <td>
                    <a href="<?= base_url("Destruction/INPUT_DOCUMENTS/get_id/{$row['SER']}") ?>" class="btn btn-info btn-xs">تحرير</a>
                </td>
                <?php ?>
                <?php $count++ ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>


<script>
    if (typeof initFunctions == 'function') {ا
        initFunctions();
    }
</script>