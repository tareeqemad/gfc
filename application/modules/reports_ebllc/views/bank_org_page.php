<?php
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 15/01/23
 * Time: 11:10 م
 */
$MODULE_NAME= 'reports_ebllc';
$TB_NAME= 'Bank_org';

$count= $offset;
?>

<div class="table-responsive tableRoundedCorner">
    <table class="table mg-b-0 text-md-nowrap  table-hover table-bordered roundedTable" id="bank_org_tb">
        <thead class="table-light">
        <tr>
            <th>#</th>
            <th>رقم البنك</th>
            <th>اسم البنك</th>
            <th>نوع البنك</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($page_rows as $row) : ?>

            <tr ondblclick="javascript:show_row_details('<?=$row['BANK_NO']?>');">
                <td><?= $count++ ?></td>
                <td><?= $row['BANK_NO']?></td>
                <td><?= $row['BANK_NAME']?></td>
                <td><?= $row['BANK_TYPE_NAME']?></td>
            </tr>

        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php echo $this->pagination->create_links(); ?>
<script>
    if (typeof initFunctions == 'function') {
        initFunctions();
    }
    if (typeof ajax_pager == 'function') {
        ajax_pager();
    }
    if (typeof show_page == 'undefined'){
        document.getElementById("bank_org_tb").style.display="none";
        document.getElementsByClassName("pagination")[0].style.display="none";
    }
</script>
