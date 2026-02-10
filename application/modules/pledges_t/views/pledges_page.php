<?php
/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 30/01/2019
 * Time: 11:20 ص
 */
$count = $offset;
?>

<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th>#</th>
            <th>رقم الصنف</th>
            <th>اسم الصنف</th>
            <th>الباركود</th>
            <th>ملاحظات</th>
            <th>تاريخ الجرد</th>
            <?php
            $count++;
            ?>
        </tr>
        </thead>
        <tbody>
        <?php if($page > 1): ?>
            <tr>
                <td colspan="9" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
        <?php foreach($page_rows as $row) :?>

            <tr >
                <td><?=$count?></td>
                <td><?=$row['CLASS_ID']?></td>
                <td><?=$row['CLASS_NAME']?></td>
                <td><?=$row['BARCODE']?></td>
                <td><?=$row['V_NOTE']?></td>
                <td><?=$row['PP_DATE']?></td>
                <?php
                $count++;
                ?>
            </tr>
        <?php endforeach;?>
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
        document.getElementById("page_tb").style.display="none";
        document.getElementsByClassName("pagination")[0].style.display="none";
    }



</script>