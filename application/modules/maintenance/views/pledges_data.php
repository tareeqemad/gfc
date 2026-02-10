<div class="table-responsive">
    <table class="table table-bordered">
        <thead class="table-light">
        <tr>
            <th>اختر</th>
            <th>رقم الصنف</th>
            <th>
                العهدة
            </th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($rows as $row) { ?>
            <tr>
                <td>
                    <input type="checkbox" value="<?= $row['CLASS_ID'] ?>" name="class_id[]"
                           data-id="<?= $row['CLASS_ID'] ?>">
                </td>
                <td>
                    <?= $row['CLASS_ID'] ?>
                </td>
                <td>
                    <?= $row['CLASS_ID_NAME'] ?>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
