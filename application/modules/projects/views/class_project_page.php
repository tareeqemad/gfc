<?php
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 24/11/14
 * Time: 10:03 ص
 */
$TB_NAME= 'class';

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

        <th>الكمية</th>

    </tr>
    </thead>
    <tbody>
    <?php
    $count=0;
    foreach ($get_list as $row){
        $count++;
        ?>
        <tr ondblclick='javascript:select_class(<?=$row['CLASS_ID']?>, <?=json_encode($row['CLASS_ID_NAME'])?> ,  "<?=$row['SAL_PRICE']?>",<?=$row['SAL_PRICE']?>,<?= $row['CLASS_UNIT'] ?>, "<?=$row['UNIT_NAME']?>",<?=$row['LEFT_AMOUNT']?>,<?=$row['LEFT_AMOUNT']?>);' >
            <td><input type="checkbox" class="checkboxes" data-val=' <?=json_encode($row)?>' value="<?= $row['CLASS_ID'] ?>"/></td>
            <td><?=$count?></td>
            <td><?=$row['CLASS_ID']?></td>
            <td><?=$row['CLASS_ID_NAME']?></td>
            <td><?=$row['CLASS_ID_NAME_EN']?></td>
            <td><?=$row['UNIT_NAME']?></td>

            <td><?=$row['LEFT_AMOUNT']?></td>

        </tr>
    <?php } ?>
    </tbody>
</table>


<script>
    if (typeof initFunctions == 'function') {
        initFunctions();
    }
    if (typeof ajax_pager == 'function') {
        ajax_pager();
    }



</script>