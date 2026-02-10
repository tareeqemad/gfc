<br>
<h4 style="text-align: center">البيانات القديمة</h4>
<br>
<div class="table-responsive tableRoundedCorner">
    <table class="table table-bordered roundedTable" id="page_old_tb">
        <thead class="table-light">
        <tr>
            <th>م</th>
            <th>الاسم</th>
            <th>صلة القرابة</th>
            <th>تاريخ الميلاد</th>
            <th>من تاريخ</th>
            <th>حتى</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($page_relative as $rows) : ?>
            <tr>
                <td><?= $rows['SERIAL'] ?></td>
                <td><?= $rows['NAME'] ?></td>
                <td><?= $rows['RELATION_NAME'] ?></td>
                <td><?= $rows['BIRTH_DATE'] ?></td>
                <? //= $rows['WORTH_ALLOWNCE']?>
                <td><?= $rows['FROM_MONTH'] ?></td>
                <td><?= $rows['UP_TO_MONTH'] ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>