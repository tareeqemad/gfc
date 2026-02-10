<?php
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 05/01/23
 * Time: 10:30 م
 */
$MODULE_NAME= 'reports_ebllc';
$TB_NAME= 'Subscribers_types';

$count= $offset;
?>

<div class="table-responsive tableRoundedCorner">
    <table class="table mg-b-0 text-md-nowrap  table-hover table-bordered roundedTable" id="subscribers_types_tb">
        <thead class="table-light">
        <tr>
            <th>#</th>
            <th>رقم الثابت</th>
            <th>اسم الثابت</th>
            <th>قيمة الرسم</th>
            <th>نوع الاشتراك</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($page_rows as $row) : ?>

            <tr ondblclick="javascript:show_row_details('<?=$row['NO']?>');">
                <td><?= $count++ ?></td>
                <td><?= $row['NO']?></td>
                <td><?= $row['SUBSCRIBER_TYPE']?></td>
                <td><?= $row['SUBSCRIBE_FEES']?></td>
                <td><?= $row['BILL_TYPE_NAME']?></td>
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
        document.getElementById("subscribers_types_tb").style.display="none";
        document.getElementsByClassName("pagination")[0].style.display="none";
    }
</script>
