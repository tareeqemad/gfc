<?php
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 29/12/22
 * Time: 13:00 ص
 */

$MODULE_NAME= 'reports_ebllc';
$TB_NAME= 'Payment_status';

$count= $offset;
?>
<div class="table-responsive tableRoundedCorner">
    <table class="table mg-b-0 text-md-nowrap  table-hover table-bordered roundedTable" id="payment_status_tb">
        <thead class="table-light">
        <tr>
            <th>#</th>
            <th>رقم نوع البنك</th>
            <th>اسم نوع البنك</th>
            <th>عدد المشتركين</th>
            <th>التحصيل النقدي</th>
            <th>الاجراءات</th>
        </tr>
        </thead>

        <tbody>
        <?php foreach ($page_rows as $row) : ?>

            <tr>
                <td><?= $count++ ?></td>
                <td><?= $row['B_TYPE']?></td>
                <td><?= $row['BANK_TYPE_NAME']?></td>
                <td><?= $row['CACHIER_BY_BANK_COUNT']?></td>
                <td><?= $row['CACHIER_BY_BANK_VALUE']?></td>

                <td class='text-center'>
                    <?php if (1) { ?>
                        <a onclick="show_detail_row(<?= $row['B_TYPE'] ?>);" class="modal-effect"  data-bs-effect="effect-rotate-left" data-bs-toggle="modal" href="#DetailModal" title="تفاصيل"
                           style="color: #2075f8"><i class="glyphicon glyphicon-eye-open"></i> </a>
                    <?php } ?>
                </td>

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
