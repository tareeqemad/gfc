<?php
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 24/10/15
 * Time: 10:25 ص
 */



?>

<div class="tb_container">
    <table class="table" id="details_tb" data-container="container">
        <thead>
        <tr>
            <th style="width: 2%">#</th>
            <th style="width: 4%">رقم الصنف</th>
            <th style="width: 15%">اسم الصنف</th>
            <th style="width: 2%">الوحدة</th>
            <th style="width: 5%">حالة الصنف</th>
            <th style="width: 3%">رقم الصنف في المنحة/ المناقصة</th>
            <th style="width: 3%">الكمية</th>
            <th style="width: 3%">الكمية الموردة</th>
            <th style="width: 3%">الكمية المتبقية</th>
            <th style="width: 3%">السعر من المنحة</th>
            <th style="width: 5%">فعالية الصنف</th>
            <th style="width: 10%">بيان فعالية الصنف</th>
            <th style="width: 5%">رقم الصنف المستبدل</th>
            <th style="width: 18%">اسم الصنف المستبدل</th>
            <th style="width: 2%">وحدة الصنف المستبدل</th>
            <th style="width: 5%">حالة الصنف</th>
            <th style="width: 10%">بيان فعالية الصنف المستبدل</th>
        </tr>
        </thead>

        <tbody>
       <?php
    $count = -1;
    foreach($details as $row) {
        ?>
        <tr>
            <td><?=++$count+1?></td>
            <td>
                <input type="hidden" name="ser[]" value="<?=$row['SER']?>" />
                <?=$row['CALSS_ID']?>
                <input  type="hidden" name="class_id[]" value="<?=$row['CALSS_ID']?>" id="h_txt_class_id<?= $count ?>" >
            </td>

            <td><?=$row['CALSS_ID_NAME']?>
                       </td>
            <td>
                <?=$row['CLASS_UNIT_NAME']?>
                <input name="class_unit[]" type="hidden" value="<?=$row['CLASS_UNIT']?>" class="form-control" id="unit_name_txt_class_id_<?=$count?>" />
            </td>
            <td>
                <input name="class_type[]" type="hidden" value="<?=$row['CLASS_TYPE']?>" class="form-control" id="txt_class_type_<?=$count?>" />
                <?=$row['CLASS_TYPE_NAME']?>
            </td>
            <td><?=$row['DONATION_CLASS_ID']?>
                        </td>

            <td><?=$row['AMOUNT']?>
                 </td>
            <td><?=$row['ORDERDERD']?>
                <input name="orderderd[]" type="hidden" value="<?=$row['ORDERDERD']?>" class="form-control" id="txt_orderderd_<?=$count?>" />

            </td>
            <td><?=($row['AMOUNT']-$row['ORDERDERD'])?>
                <input name="rem_amount[]" type="hidden" value="<?=($row['AMOUNT']-$row['ORDERDERD'])?>" class="form-control" id="txt_rem_amount_<?=$count?>" />

            </td>
            <td><?=$row['PRICE']?>
                </td>
            <td>
                <input name="old_class_case[]" type="hidden" value="<?=$row['CLASS_CASE']?>" class="form-control" id="txt_old_class_case_<?=$count?>" />
<?php if($row['CLASS_CASE']==1) { ?>
                <select  name="class_case[]" id="dp_class_case_<?=$count?>"  data-curr="false"  class="form-control" >
                    <?php foreach($class_case as $rows) :?>
                        <option  <?php if ($row['CLASS_CASE']==$rows['CON_NO']) echo "selected"; ?> value="<?= $rows['CON_NO'] ?>"><?php echo $rows['CON_NAME']  ?></option>
                    <?php endforeach; ?>
                </select>
            <?php } else {?>
    <input name="class_case[]" type="hidden" value="<?=$row['CLASS_CASE']?>" class="form-control" id="dp_class_case_<?=$count?>" />
    <?=$row['CLASS_CASE_NAME']?>
  <?php
} ?>
            </td>

            <td>  <textarea rows="1" name="class_case_hints[]" id="class_case_hints_<?= $count ?>" class="form-control" ><?=$row['CLASS_CASE_HINTS']?></textarea>
                      </td>
            <td>
                <input readonly type="text" name="replace_class_id[]" value="<?=$row['REPLACE_CLASS_ID']?>" class="form-control"  id="h_txt_replace_class_id_<?=$count?>" />
            </td>
            <td> <input type="text" readonly name="replace_class_id_name[]" value="<?=$row['REPLACE_CLASS_ID_NAME']?>" class="form-control"  id="txt_replace_class_id_<?=$count?>" />
            </td>
            <td >
                <input type="hidden" readonly name="replace_class_unit[]" value="<?=$row['REPLACE_CLASS_UNIT']?>" class="form-control"  id="unit_txt_replace_class_id_<?=$count?>" />
                <input type="text" readonly name="replace_class_unit_name[]" value="<?=$row['REPLACE_CLASS_UNIT_NAME']?>" class="form-control"  id="unit_name_txt_replace_class_id_<?=$count?>" />

            </td>
            <td>
                <select  name="replace_class_type[]" id="dp_replace_class_type_<?=$count?>"  data-curr="false"  class="form-control" >
                    <?php foreach($items_type as $rows) :?>
                        <option  <?php if ($row['REPLACE_CLASS_TYPE']==$rows['CON_NO']) echo "selected"; ?> value="<?=$rows['CON_NO'] ?>"><?php echo $rows['CON_NAME']  ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td>  <textarea rows="1" name="replace_class_case_hints[]" id="replace_class_case_hints_<?= $count ?>" class="form-control" ><?=$row['REPLACE_CLASS_CASE_HINTS']?></textarea>
            </td>
        </tr>
    <?php

}
?>

</tbody>
<tfoot>

</tfoot>
</table>
</div>
