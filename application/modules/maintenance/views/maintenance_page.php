<?php
/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 27/07/2019
 * Time: 10:30 ص
 */
$count = $offset;
$get_page_req = base_url('maintenance/maintenance/get');
?>
<style>
    .ST_2 {
        background-color: #f0ad4e;
    }

    .ST_7 {
        background-color: #0fdb8b;
    }

    .ST_12 {
        background-color: #db430a;
    }
</style>
<div class="table-responsive">
    <table class="table table-bordered" id="page_tb" data-container="container">
        <thead class="table-light">
        <tr>
            <th>
                <input type="checkbox" class="group-checkable" data-set="#page_tb .checkboxes"/>
            </th>
            <th>#</th>
            <th>رقم الطلب</th>
            <th>المقر</th>
            <th>الاجهزة</th>
            <th>البيان</th>
            <th>الموظف</th>
            <th>تاريخ الادخال</th>
            <th>حالة الطلب</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($page > 1): ?>
            <tr>
                <td colspan="9" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
        <?php foreach ($page_rows as $row) : ?>
            <tr ondblclick="javascript:get_to_link('<?= $get_page_req . '/' . $row['SER'] ?>')" title="اضغط هنا">
                <?php
                echo "<td>";
                if ($row['STATUS'] == 1)
                    echo '<input type="checkbox" class="checkboxes" value="' . $row['SER'] . '" />';
                echo "</td>";
                ?>
                <td><?= $count ?></td>
                <td><?= $row['REQUEST_SERIAL'] ?></td>
                <td><?= $row['BRANCH_NAME'] ?></td>
                <?php
                $res_arr = $this->rmodel->getID('MAINTENANCE_PKG', 'MAINTENANCE_REQ_CLAS_TB_GET', $row['SER']);
                ?>
                <td>
                    <ul>
                        <?php foreach ($res_arr as $res ) { ?>
                            <li><?=$res['CLASS_ID']?>-<?=$res['CLASS_ID_NAME']?></li>
                        <?php } ?>
                    </ul>
                </td>
                <td><?= $row['NOTE_PROBLEM'] ?></td>
                <td><?= $row['EMP_NAME'] ?></td>
                <td><?= $row['ENTRY_DATE'] ?></td>
                <td><?= $row['STATUS_NAME'] ?></td>
                <?php
                $count++;
                ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

