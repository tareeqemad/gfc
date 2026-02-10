<?php
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 17/09/15
 * Time: 10:34 ص
 */
$TB_NAME= 'class';

?>

<table class="table" id="<?=$TB_NAME?>_tb" data-container="container">
    <thead>
    <tr>
        <th>#</th>

        <th>رقم الصنف</th>
        <th>اسم الصنف باللغة العربية</th>
        <th>اسم الصنف باللغة الانجليزية</th>
        <th>الوحدة الفرعية</th>
        <th>سعر الشراء</th>
        <th>سعر البيع</th>
        <th>الرصيد</th>
        <th>رصيد الطوارئ</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $count=0;
    foreach ($get_list as $row){
        $count++;
        ?>
        <tr  >
           <td><?=$count?></td>
            <td><?=$row['CLASS_ID']?></td>
            <td><?=$row['CLASS_NAME_AR']?></td>
            <td><?=$row['CLASS_NAME_EN']?></td>
            <td><?=$row['UNIT_NAME']?></td>
            <td><?=$row['CLASS_PURCHASING']?></td>
            <td><?=$row['SALE_PRICE']?></td>
            <td><?=$row['TOTAL_AMOUNT']?></td>
            <td><?=$row['EMERGENCY_AMOUNT']?></td>
        </tr>
    <?php } ?>
    </tbody>
</table>
<?php echo $this->pagination->create_links();

?>

<script>
    if (typeof initFunctions == 'function') {
        initFunctions();
    }
    if (typeof ajax_pager == 'function') {
        ajax_pager();
    }



</script>