<?php
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 15/02/20
 * Time: 10:30 ص
 */

$count = $offset;

function class_name($offer_case){
    if ($offer_case==2)  return 'case_4';
    else if ($offer_case==1)   return 'case_1';
    else if ($offer_case==3)   return 'case_2';
}
?>
<div class="tbl_container">
    <table class="table" id="c_prices_tb" data-container="container">
        <thead>
        <tr>

            <th>م</th>
            <th> رقم المسلسل </th>
            <th>نوع الحركة </th>
            <th>   رقم الصنف  </th>
            <th> اسم الصنف  </th>
            <th>  تاريخ الحركة </th>
            <th>   السعر المتوسط </th>
            <th>   سعر الشراء </th>
            <th>   سعر البيع </th>
            <th>   نسبة سعر المستعمل(%) </th>
            <th>   سعر الشراء المستعمل </th>
            <th>   سعر بيع المستعمل </th>
            <th>  عرض أمر  التوريد </th>
        </tr>
        </thead>
        <tbody>
        <?php if($page > 1): ?>
            <tr>
                <td colspan="11" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
        <?php foreach($page_rows as $row) :?>

            <tr class="<?=class_name($row['TYPE'])?>" ondblclick="javascript:show_row_details('<?=$row['SER']?>');">
                <td><?=$count?></td>
                <td><?=$row['SER']?></td>
                <td><?=$row['TYPE_T']?></td>
                <td><?=$row['CLASS_ID']?></td>
                <td><?=$row['CLASS_NAME_AR']?></td>
                <td><?=$row['PRICE_DATE']?></td>
                <td><?=$row['CLASS_PURCHASING']?></td>
                <td><?=$row['BUY_PRICE']?></td>
                <td><?=$row['SELL_PRICE']?></td>
                <td><?=$row['USED_PERCENT']?></td>
                <td><?=$row['USED_BUY_PRICE']?></td>
                <td><?=$row['USED_SELL_PRICE']?></td>
                 <td>
                    <?php if($row['ORDER_ID']!='') { ?>
                        <a  target="_blank" href="<?=base_url("purchases/orders/get/{$row['ORDER_ID']}/1")?>" ><i class='glyphicon glyphicon-share'></i></a>
                    <?php } ?>
                </td>

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