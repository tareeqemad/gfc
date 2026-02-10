<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 16/04/15
 * Time: 11:49 م
 */

$count = $offset;

?>

<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th>#</th>
            <th>رقم طلب التأجيل  </th>
            <th>رقم مسلسل الشراء  </th>
            <th>نوع طلب الشراء </th>
            <th>تاريخ الادخال</th>
            <th>مدخل التأجيل</th>
            <th>الحالة</th>
            <th>تحويل للتوريد</th>
        </tr>
        </thead>
        <tbody>
        <?php if($page > 1): ?>
            <tr>
                <td colspan="10" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
        <?php foreach($page_rows as $row) :?>

            <tr ondblclick="javascript:show_row_details('<?=$row['PURCHASE_ORDER_ID']?>','<?=$row['DELAY_ID']?>');">
                <td><?=$count?></td>
                <td><?=$row['DELAY_ID']?></td>
                <td><?=$row['PURCHASE_ORDER_ID']?></td>
                <td><?=$row['PURCHASE_TYPE_NAME']?></td>
                <td><?=$row['ENTRY_DATE']?></td>
                <td title="<?=$row['ENTRY_USER_NAME']?>"><?=get_user_name($row['ENTRY_USER'])?></td>
                <td><?=$row['AWARD_CASE_NAME']?></td>
                <?php if($row['HAS_ORDER']==2){ ?>
                    <td><a id="do_order_<?=$row['DELAY_ID']?>" href="javascript:;" onclick="javascript:do_order_delay('<?=$row['PURCHASE_ORDER_ID']?>','<?=$row['DELAY_ID']?>','<?=$row['ORDER_PURPOSE']?>');" ><i class='glyphicon glyphicon-share'></i></a></td>
                <?php }else echo "<td> </td>"; ?>
                <?php $count++; ?>
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
        //document.getElementById("page_tb").style.display="none";
        //document.getElementsByClassName("pagination")[0].style.display="none";
    }
</script>
