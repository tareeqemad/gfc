<?php
/**
 * Created by PhpStorm.
 * User: ljasser
 * Date: 27/07/19
 * Time: 09:20 ص
 */

$count = 0;

?>
<div class="tbl_container">
    <table class="table" id="carsTbl" data-container="container">
        <thead>
        <tr>

            <th  >#</th>
            <th>رقم السند </th>
            <th>  تاريخ السند </th>
            <th> نوع السند  </th>
            <th>بيان السند</th>
            <th>المبلغ </th>
            <th>الأرشفة الالكترونية<th>

            <?php
            $count++;
            ?>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($rows as $row) : ?>
            <tr>
                <td><?= $count ?></td>
                <td><?= $row['RE_DIS_SER'] ?></td>
                <td><?= $row['RE_DIS_DATE'] ?></td>
                <td><?= $row['TYPE'] ?></td>
                <td><?= $row['BODY'] ?></td>
                <td><?= $row['AMOUNT'] ?></td>
                <td><?= $row['ELECTRONIC_ARCHIVE'] ?></td>
                <td>
                    <a href="<?= base_url("Destruction/REC_DIS/get_id/{$row['SER']}") ?>" class="btn btn-info btn-xs">تحرير</a>
                </td>
                <?php ?>
                <?php $count++ ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>


<script>
    if (typeof initFunctions == 'function') {
        initFunctions();
    }
</script>