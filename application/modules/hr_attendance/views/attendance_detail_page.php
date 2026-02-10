<?php
$count = 1;

function clockalize($in){

    $h = intval($in);
    $m = round((((($in - $h) / 100.0) * 60.0) * 100), 0);
    if ($m == 60)
    {
        $h++;
        $m = 0;
    }
    $retval = sprintf("%02d:%02d", $h, $m);
    return $retval;
}
?>
<div class="table-responsive tableRoundedCorner">
<table class="table mg-b-0 text-md-nowrap  table-hover table-bordered roundedTable" id="page_tb">
    <thead class="table-light">
        <tr>
            <th>#</th>
            <th>م</th>
            <th> الموظف</th>
            <th>اليوم</th>
            <th>التاريخ</th>
            <th>وقت الحضور</th>
            <th>وقت الانصراف</th>
            <th>مدة الحضور</th>
            <th>ملاحظات </th>

        </tr>
    </thead>
    <tbody>
    <?php foreach ($result as $row) : ?>
        <tr>
            <td><?= $count++ ?></td>
            <td><?= $row['ROW_SER'] ?></td>
            <td><?= $row['EMP_NO'] ?> - <?= $row['EMP_NAME'] ?></td>
            <td><?= $row['DAY_AR'] ?></td>
            <td><?= $row['DT'] ?></td>
            <td><?= $row['ENTRY_TIME'] ?></td>
            <td><?= $row['LEAVE_TIME'] ?></td>
            <td title="<?=$row['ATTENDANCE_TIME']?>"><?= clockalize($row['ATTENDANCE_TIME']) ?></td>
            <td>
                <ul>
                    <li></li>
                </ul>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>
