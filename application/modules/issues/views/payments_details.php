<?php
$MODULE_NAME= 'issues';
$TB_NAME= 'issues';
$isCreate =isset($details) && count($details)  > 0 ?false:true;
$isCreate2=isset($details_ins) && count($details_ins)  > 0 ?false:true;
$delete_url_details= base_url("$MODULE_NAME/$TB_NAME/delete_sub_details");
$count_pay=0;

?>
<div class="tb_container">
<table class="table" id="details_ins_tb1" data-container="container"  align="center">
<thead>
<tr>
    <th  style="width: 16%">تاريخ القسط</th>
    <th style="width: 16%" >قيمة القسط</th>
    <th style="width: 16%">حالة القسط</th>
    <th  style="width: 16%">القيمة المدفوعة</th>
</tr>
</thead>
<tbody>
<?php if(count($details_ins) <= 0) {  // ادخال ?>

<tr>
                <td>
                    <input type="text" data-val="true" data-type="date" data-val-required="حقل مطلوب" placeholder="تاريخ القسط" data-date-format="DD/MM/YYYY"   data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?= date_format_exp() ?>"    name="pay_date[]" id="txt_pay_date_<?=$count_pay?>" class="form-control datepicker">
                    <input  type="hidden" name="h_txt_issue_ser2[]"  id="h_txt_issue_ser2_<?=$count_pay?>" data-val="false" data-val-required="required" >
                    <input type="hidden" name="seq12[]" id="seq1_id12[]" value="0"  />
                    <input type="hidden" name="h_count2[]" id="h_count2_<?=$count_pay?>"  />
                </td>

                <td>
                    <input type="text" data-val="true" placeholder="قيمة القسط"  name="pay_value[]" id="txt_pay_value_<?=$count_pay?>" class="form-control">
                </td>
                <td>
                    <select name="type_pay[]" id="dp_type_pay_<?=$count_pay?>" class="form-control">

                        <?php foreach($paid as $row) :?>
                            <option value="<?= $row['CON_NO'] ?>" ><?= $row['CON_NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <input type="text" readonly  data-val="true" placeholder="القيمة المدفوعة"   data-val-required="حقل مطلوب" name="value_pay[]" id="txt_value_pay_<?=$count_pay?>" class="form-control">
                </td>

            </tr>



<?php
    $count_pay++;
}else if(count($details_ins) > 0) { // تعديل
    $count_pay = -1;
    foreach($details_ins as $row2) {
        ++$count_pay+1


        ?>

        <tr>
            <td>
                <input type="text" data-val="true" value="<?=$row2['PAY_DATE']?>" data-type="date" placeholder="تاريخ القسط" data-date-format="DD/MM/YYYY"  data-val-required="حقل مطلوب"  data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?= date_format_exp() ?>"   data-val-required="حقل مطلوب" name="pay_date[]" id="txt_pay_date_<?=$count_pay?>" class="form-control">
              <input  type="hidden" name="h_txt_issue_ser2[]" value="<?=$row2['ISSUE_SER']?>"  id="h_txt_issue_ser2_<?=$count_pay?>" data-val="false" data-val-required="required" >
              <input type="hidden" name="seq12[]" value="<?=$row2['SER']?>" id="seq1_id12[]"  />
                <input type="hidden" name="seq13[]" value="<?=$row2['ACTION_SER']?>" id="seq1_id13[]"  />

                <input type="hidden" name="h_count2[]" id="h_count2_<?=$count_pay?>"  />
            </td>

            <td>
                <input type="text" data-val="true" value="<?=$row2['PAY_VALUE']?>" placeholder="قيمة القسط" data-val-required="حقل مطلوب" name="pay_value[]" id="txt_pay_value_<?=$count_pay?>" class="form-control">
            </td>
            <td>
                <select name="type_pay[]" id="dp_type_pay_<?=$count_pay?>" class="form-control">

                    <?php foreach($paid as $row) :?>
                        <option value="<?= $row['CON_NO'] ?>" <?PHP if ($row['CON_NO']==$row2['TYPE_PAY']){ echo " selected"; } ?> ><?= $row['CON_NAME'] ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td>
                <input type="text" value="<?=$row2['VALUE_PAY']?>" readonly  data-val="true" placeholder="القيمة المدفوعة"   data-val-required="حقل مطلوب" name="value_pay[]" id="txt_value_pay_<?=$count_pay?>" class="form-control">
            </td>


  

        </tr>
    <?php

    }
}

?>
</tbody>

<tfoot>
<tr>
    <th>مجموع قيم الأقساط</th>
    <th  id="sum_ins" ></th>
    <th>مجموع القيم المدفوعة</th>
    <th id="sum_paid" ></th>
    

    
</tr>
<tr>
    <th>
     <a id="add_new_row" onclick="javascript:add_row(this,'input:text,input:hidden[name^=seq12],textarea,select',false);"  href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>
    </th>
    <th></th>
    <th></th>
    <th></th>
   

    
</tr>

</tfoot>
</table></div>
