<div class="table-responsive tableRoundedCorner">
    <table class="table mg-b-0 text-md-nowrap roundedTable table-bordered" id="recurring_tb">
        <thead class="table-light">
        <tr>
            <th>م</th>
            <th>المقر</th>
            <th>الرقم الوظيفي</th>
            <th>الموظف</th>
            <th>عدد المرات</th>
        </tr>
        </thead>
        <tbody>
        <?php $count = 1 ; foreach ($total_count_arr as $row) {?>
            <tr>
                <td><?= $count?></td>
                <td><?=$row['BRANCH_NAME']?></td>
                <td><?=$row['ROW_DUPLICATE']?></td>
                <td><?=$row['EMP_NAME']?></td>
                <td><?=$row['CNT_ROW']?></td>
            </tr>
            <?php $count++; } ?>
        </tbody>
    </table>
</div>