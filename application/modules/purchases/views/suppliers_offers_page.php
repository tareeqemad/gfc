<?php
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 11/03/15
 * Time: 10:13 ص
 */
$count = $offset;

function class_name($offer_case){
    if ($offer_case==2)  return 'case_0';
    else if ($offer_case==1)   return 'case_1';
   // else    return 'case_1';
}
?>
<div class="tbl_container">
    <table class="table" id="suppliers_offers_tb" data-container="container">
        <thead>
        <tr>
            <th  >#</th>
            <th>م</th>
            <th> رقم مسلسل الشراء </th>
            <th>   رقم طلب الشراء</th>
            <th> اسم المورد  </th>
            <th> عملة عرض السعر</th>
            <th>  عملة المورد</th>
          <!--  <th>الحالة</th>-->
        </tr>
        </thead>
        <tbody>
        <?php if($page > 1): ?>
            <tr>
                <td colspan="10" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
        <?php foreach($page_rows as $row) :?>

            <tr class="<?=class_name($row['OFFER_CASE'])?>" ondblclick="javascript:show_row_details('<?=$row['SUPPLIERS_OFFERS_ID']?>');">
                <td><input type="checkbox" class="checkboxes" value="<?=$row['SUPPLIERS_OFFERS_ID'] ?>" /></td>
                <td><?=$count?></td>
                <td><?=$row['SUPPLIERS_OFFERS_ID']?></td>
                <td><?=$row['PURCHASE_ORDER_ID']?></td>
                <td><?=$row['CUST_NAME']?></td>
                <td><?=$row['CURR_ID_NAME']?></td>
                <td><?=$row['CUST_CURR_ID']?></td>
           <!--     <td><?php if($row['OFFER_CASE']==0) echo "ملغي"; else if($row['OFFER_CASE']==1) echo "مدخل"; else if($row['OFFER_CASE']==2) echo "معتمد"; ?></td>
-->
                <?php $count++ ?>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>
<?php echo $this->pagination->create_links(); ?>

<script>
    if (typeof ajax_pager == 'function') {
        ajax_pager();
    }

</script>