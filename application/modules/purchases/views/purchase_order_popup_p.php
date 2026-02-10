<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 09/04/15
 * Time: 10:00 ص
 */

$count = $offset;

function class_name($adopt, $count_left){
    if($adopt==0 or $count_left==0){
        return 'case_0';
    }elseif($adopt==5 and $count_left>0){
        return 'case_1'; // case_3
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
            <th>رقم المسلسل</th>
            <th>رقم طلب الشراء</th>
            <th>تاريخ الادخال</th>
            <th>نوع الطلب</th>
            <th>حالة الطلب</th>
            <th>المدخل</th>
        </tr>
        </thead>
        <tbody>
        <?php if($page > 1): ?>
            <tr>
                <td colspan="7" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
        <?php foreach($page_rows as $row) :?>

            <tr class="<?=class_name($row['ADOPT'], 1)?>" ondblclick="javascript:show_row_details('<?=$row['PURCHASE_ORDER_ID']?>','<?=$row['QUOTE_CURR_ID']?>','<?=$row['QUOTE_CURR_ID_NAME']?>','<?=$row['PURCHASE_TYPE_NAME']?>','<?=$row['NOTES']?>','<?=$row['PURCHASE_ORDER_NUM']?>','<?=trim(preg_replace('/\s\s+/','\n', $row['QUOTE_CONDITION']))?>' );">
                <td><?=$count?></td>
                <td><?=$row['PURCHASE_ORDER_ID']?></td>
                <td><?=$row['PURCHASE_ORDER_NUM']?></td>
                <td><?=$row['ENTRY_DATE']?></td>
                <td><?=$row['PURCHASE_ORDER_CLASS_TYPE_NAME']?></td>
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

    var paging_url =document.getElementsByClassName("pagination")[0].getElementsByTagName('a');
    for (var i = 0; i < paging_url.length; ++i) {
        paging_url[i].href += '?order_purpose=<?=$order_purpose?>';
    }

</script>
