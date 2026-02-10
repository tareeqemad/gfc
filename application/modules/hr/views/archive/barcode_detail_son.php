<div class="card mb-4">
    <div class="card-header">
        <h3 class="card-title">
            التصنيفات التابعة
        </h3>
        <div class="card-options">

        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead class="table-light">
            <tr>
                <th>الرمز</th>
                <th>التصنيف</th>
                <th>عدد الورق</th>

            </tr>
            </thead>
            <tbody>
            <?php foreach ($result_son as $row) : ?>
                <tr onclick="showChild(<?=$row['TYPE_NO']?>,<?=$row['CNT_M']?>)">
                    <td><?=$row['TYPE_CODE']?></td>
                    <td><?=$row['TYPE_NAME']?>(<?=$row['CNT_M']?>)</td>
                    <td><?= $row['CNT_EMP'] ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    initFunctions();
</script>