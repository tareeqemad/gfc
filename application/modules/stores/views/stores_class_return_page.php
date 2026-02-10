<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 05/01/15
 * Time: 02:06 م
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
            <th>المخزن المرجع إليه</th>
            <th>نوع سند الإرجاع</th>
            <th>حساب الإرجاع</th>
            <th>البيان</th>
            <th>رقم الطلبية</th>
            <th>حالة الطلب</th>
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

            <tr class="<?=class_name($row['ADOPT'])?>" ondblclick="javascript:show_row_details('<?=$row['CLASS_RETURN_ID']?>');">
                <td><?=$count?></td>
                <td><?=$row['CLASS_RETURN_ID']?></td>
                <td><?=$row['ENTRY_DATE']?></td>
                <td><?=$row['TO_STORE_ID_NAME']?></td>
                <td><?=$row['CLASS_RETURN_TYPE_NAME']?></td>
                <td><?=$row['CLASS_RETURN_ACCOUNT_NAME']?></td>
                <td><?=$row['NOTES']?></td>
                <td><?=$row['STORE_SERV_REQ']?></td>
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
