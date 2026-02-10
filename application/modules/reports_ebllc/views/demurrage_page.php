<?php
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 15/01/23
 * Time: 11:10 م
 */
$MODULE_NAME= 'reports_ebllc';
$TB_NAME= 'Demurrage';

$count= $offset;
?>

<div class="table-responsive tableRoundedCorner">
    <table class="table mg-b-0 text-md-nowrap  table-hover table-bordered roundedTable" id="demurrage_tb">
        <thead class="table-light">
        <tr>
            <th>#</th>
            <th>المقر</th>
            <th>رقم الاشتراك</th>
            <th>اسم المشترك</th>
            <th>نوع الاشتراك</th>
            <th>المتأخرات</th>
            <th>المبلغ المطلوب</th>
            <th>غرامة التأخير</th>

        </tr>
        </thead>
        <tbody>
        <?php foreach ($page_rows as $row) : ?>

            <tr>
                <td><?= $count++ ?></td>
                <td><?= $row['BRANCH_NAME']?></td>
                <td><?= $row['SUBSCRIBER']?></td>
                <td><?= $row['NAME']?></td>
                <td><?= $row['TYPE_NAME']?></td>
                <td><?= $row['REMAINDER']?></td>
                <td><?= $row['NET_TO_PAY']?></td>
                <td><?= $row['DELAY']?></td>
            </tr>

        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php echo $this->pagination->create_links(); ?>
<script>
    if (typeof ajax_pager == 'function') {
        ajax_pager();
    }
</script>
