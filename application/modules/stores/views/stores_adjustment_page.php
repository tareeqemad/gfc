<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 22/01/15
 * Time: 03:18 م
 */

$count = $offset;

function class_name($adopt){
    if($adopt==2){
        return 'case_0';
    }else{
        return 'case_1';
    }
}

?>
<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th>#</th>
            <th>رقم السند</th>
            <th>تاريخ الادخال</th>
            <th>نوع السند</th>
            <th>المخزن</th>
            <th>الحساب</th>
            <th>تاريخ السند</th>
            <th>حالة السند</th>
            <th>المدخل</th>
        </tr>
        </thead>
        <tbody>
        <?php if($page > 1): ?>
            <tr>
                <td colspan="9" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
        <?php foreach($page_rows as $row) :?>

            <tr class="<?=class_name($row['ADOPT'])?>" ondblclick="javascript:show_row_details('<?=$row['STORES_ADJUSTMENT_ID']?>');">
                <td><?=$count?></td>
                <td><?=$row['STORES_ADJUSTMENT_ID']?></td>
                <td><?=$row['ENTRY_DATE']?></td>
                <td><?=$row['STORES_ADJUSTMENT_TYPE_NAME']?></td>
                <td><?=$row['STORE_ID_NAME']?></td>
                <td><?=$row['ACCOUNT_ID_NAME']?></td>
                <td><?=$row['ADJUSTMENT_DATE']?></td>
                <td><?=$row['ADOPT_NAME']?></td>
                <td><?=get_user_name($row['ENTRY_USER'])?></td>
                <?php $count++ ?>
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
