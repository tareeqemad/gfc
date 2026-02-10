<?php
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 24/11/14
 * Time: 10:03 ص
 */
$TB_NAME= 'class';
//print_r($get_list);
?>
<?php echo $this->pagination->create_links();

?>
<table class="table" id="<?=$TB_NAME?>_tb" data-container="container">
    <thead>
    <tr>
        <th  ><input type="checkbox"  class="group-checkable" data-set="#class_tb .checkboxes"/></th>

        <th>#</th>
        <th>رقم الصنف</th>
        <th>اسم الصنف باللغة العربية</th>
        <th>اسم الصنف باللغة الانجليزية</th>
        <th>الوحدة الفرعية</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $count=0;
    foreach ($get_list as $row){
        $count++;
        ?>
        <tr ondblclick="javascript:select_class(<?=$row['CLASS_ID']?>, '<?=($row['CLASS_NAME_AR'])?> ', '<?=$row['CLASS_UNIT_SUB']?>', '<?=$row['CLASS_PURCHASING']?>', '<?=$row['UNIT_NAME']?>',
            '<?=$row['EXP_ACCOUNT_CUST']?>','<?=($row['EXP_ACCOUNT_CUST_NAME'])?>','<?=$row['DESTRUCTION_TYPE_NAME']?>','<?=$row['DESTRUCTION_TYPE']?>','<?=$row['DESTRUCTION_PERCENT']?>',
            '<?=$row['DESTRUCTION_ACCOUNT_ID']?>','<?=$row['DESTRUCTION_ACCOUNT_ID_NAME']?>','<?=$row['AVERAGE_LIFE_SPAN']?>','<?=$row['AVERAGE_LIFE_SPAN_TYPE']?>',
            '<?=$row['AVERAGE_LIFE_SPAN_TYPE_NAME']?>','<?=$row['CALSS_DESCRIPTION']?>','<?=($row['CLASS_NAME_EN'])?>','<?=$row['SECTION_NO']?>',
            '<?=$row['CLASSES_PRICES_SER']?>','<?=$row['BUY_PRICE']?>','<?=$row['SELL_PRICE']?>','<?=$row['USED_SELL_PRICE']?>','<?=$row['PERSONAL_CUSTODY_TYPE']?>')" >
        <td><input type="checkbox" class="checkboxes" data-val=' <?=json_encode($row)?>' value="<?= $row['CLASS_ID'] ?>"/></td>
            <td><?=$count?></td>
            <td><?=$row['CLASS_ID']?></td>
            <td><?=$row['CLASS_NAME_AR']?></td>
            <td><?=$row['CLASS_NAME_EN']?></td>
            <td><?=$row['UNIT_NAME']?></td>
                 </tr>
    <?php } ?>
    </tbody>
</table>
<?php echo $this->pagination->create_links();

?>

