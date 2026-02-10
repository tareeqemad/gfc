<?php
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 30/08/16
 * Time: 12:21 م
 */

$TB_NAME= 'budget_financial_ceil';
$sum_v=0;
?>
<table class="table" id="<?=$TB_NAME?>_tb" data-container="container">
    <thead>
    <tr>
        <th>م</th>
        <th>المقر</th>
        <th>الفصل</th>
        <th>المبلغ</th>
        <th>حالة الاعتماد</th>
     </tr>
    </thead>
<tbody>
<?php
$count=0;
foreach ($get_list as $row){

    $sum_v=$sum_v+$row['CEIL_VALUE'];
?>
<tr>
    <td><input type="hidden" name="ser[]" id="ser_<?=$count?>" value="<?=$row['SER']?>"><?=($count+1)?></td>
    <td><input type="hidden" name="branch[]" id="branch_<?=$count?>" value="<?=$row['BRANCH']?>"><?=$row['BRANCH_NAME']?></td>
    <td><input type="hidden" name="section[]" id="section_<?=$count?>" value="<?=$row['SECTION_NO']?>"><?=$row['T_SER']?>:<?=$row['SECTION_NAME']?></td>
    <td ><input type="hidden"  name="old_ceil_value[]" id="old_ceil_value_<?=$count?>"  value="<?=$row['CEIL_VALUE']?>">
        <input <?php //($row['COUNT_EXP_REV']>0)||
        if ( ($row['ADOPT']==2) ) echo "readonly"; ?> type="text"  name="ceil_value[]" id="ceil_value_<?=$count?>" min="0"   style="width:80px;"  value="<?=$row['CEIL_VALUE']?>" maxlength="15" class="balance"></td>
    <td><input type="hidden" name="old_adopt[]" id="old_adopt_<?=$count?>" value="<?=$row['ADOPT']?>">
        <input type="checkbox" name="adopt[<?=$count ?>]" id="adopt_<?=$count?>" <?php if ($row['ADOPT']==2) echo "checked"; ?> value="<?php if($row['ADOPT']==2) echo "2"; else echo "1"; ?>" >
    </td>


</tr>
<?php  $count++; } ?>
</tbody>

<tfoot>
<tr>
    <th></th>
    <th>الاجمالي</th>
    <th></th>
    <th id="txt_sum_ccount"><?=$sum_v?></th>
    <th></th>

</tr>
</tfoot>

</table>
