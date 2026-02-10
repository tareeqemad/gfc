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
            <th>رقم القيد</th>
            <th> نوع القيد </th>
            <th>بيان القيد</th>
            <th> تاريخ القيد </th>
            <th>المبلغ </th>
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
                <td hidden=""><?= $row['d_ser'] ?></td>
                <td><?= $row['BOND_SER'] ?></td>
                <td><?= $row['BOND_TYPE'] ?></td>
                <td><?= $row['BOND_BODY'] ?></td>
                <td><?= $row['BOND_DATE'] ?></td>
                <td><?= $row['AMOUNT'] ?></td>

                <td>
                    <a href="<?= base_url("Destruction/Bonds/get_id/{$row['SER']}") ?>" class="btn btn-info btn-xs">تحرير</a>
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