<?php
$count = $offset;

?>

<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th>#</th>
            <th>رقم السند</th>
            <th>رقم الصنف</th>
            <th>اسم الصنف</th>
            <th>سعر الشراء / السوق</th>
            <th>فعالية الصنف</th>
            <th>حالة الطلب</th>
            <th>تاريخ الادخال</th>
            <th>المدخل</th>
        </tr>
        </thead>
        <tbody>
        <?php if($page > 1): ?>
            <tr>
                <td colspan="10" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
        <?php foreach($page_rows as $row) :?>

        <tr ondblclick="javascript:show_row_details('<?=$row['SER']?>');" >
            <td><?=$count?></td>
            <td><?=$row['SER']?></td>
            <td> <input readonly style="height: 25px; width: 100px; text-align: center" type="text" onclick='txt_copy(this)' value="<?=$row['NEW_CLASS_ID']?>" /> </td>
            <td><?=$row['CLASS_NAME_AR']?></td>
            <td><?=$row['BUY_PRICE']?></td>
            <td><?=$row['CLASS_STATUS']?></td>
            <td><?=$row['ADOPT_NAME']?></td>
            <td><?=$row['ENTRY_DATE_10']?></td>
            <td title="<?=$row['ENTRY_USER_NAME']?>"><?=get_short_user_name($row['ENTRY_USER_NAME'])?></td>
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
    function txt_copy(txtarea){
        txtarea.select();
        document.execCommand('copy');
    }
</script>
