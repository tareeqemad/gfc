<?php
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 07/03/15
 * Time: 01:01 م
 */

$count = $offset;

function class_name($offer_case){
    if ($offer_case==0 )  return 'case_0';
    else if ($offer_case==1)   return 'case_1';
    else if ($offer_case==2)   return 'case_2';
    else if ($offer_case==3)   return 'case_3';
    else if ($offer_case==4)   return 'case_4';
    else if ($offer_case==5)   return 'case_5';
    // else    return 'case_1';
}
?>
<div class="tbl_container">
    <table class="table" id="Orders_tb" data-container="container">
        <thead>
        <tr>
            <th  title="تحديد الكل"><input type="checkbox" id="select-all"></th>
            <th>م</th>
            <th> رقم المسلسل </th>
            <th>مسلسل التوريد</th>
            <th>   رقم طلب الشراء</th>
            <th> اسم المورد  </th>
            <th> عملة عرض السعر</th>
            <th>  عملة المورد</th>
            <th>  حالة الاعتماد</th>
            <!--  <th> الغرض من أمر التوريد</th>-->
        </tr>
        </thead>
        <tbody>
        <?php if($page > 1): ?>
            <tr>
                <td colspan="10" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
        <?php foreach($page_rows as $row) :?>

            <tr class="<?=class_name($row['ADOPT'])?>" ondblclick="javascript:show_row_details('<?=$row['ORDER_ID']?>');">
                <td><input type="checkbox" class="checkboxes" value="<?=$row['ORDER_ID'] ?>" /></td>
                <td><?=$count?></td>
                <td><?=$row['ORDER_ID']?></td>
                <td><?=$row['ORDER_TEXT_T']?></td>
                <td><?=$row['PURCHASE_ORDER_ID']?></td>
                <td><?=$row['CUST_NAME']?></td>
                <td><?=$row['CURR_ID_NAME']?></td>
                <td><?=$row['CUST_CURR_ID']?></td>
                <td><?=$row['ADOPT_T']?></td>
                <!--   <td><?=$row['ORDER_PURPOSE_NAME']?></td> -->
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