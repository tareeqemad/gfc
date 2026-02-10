<?php
$MODULE_NAME= 'issues';
$TB_NAME= 'gedco_issues';
$delete_url_details= base_url("$MODULE_NAME/$TB_NAME/delete_sub_details");
$isCreate =isset($details) && count($details)  > 0 ?false:true;
$count=0;

?>
<div class="tb_container">
<table class="table" id="details_tb2" data-container="container"  align="center">
<thead>
<tr>
    <th style="width: 8%">تاريخ الجلسة</th>
    <th style="width: 8%">الاجراء</th>
    <th style="width: 15%">تاريخ الحكم</th>
    <th style="width: 15%">نص الحكم</th>
    <th style="width: 8%">ملاحظات</th>




</tr>
</thead>
<tbody>
<?php if(count($details) <= 0) {  // ادخال ?>

<tr>
                <td>
             <input type="text" data-val="true"  placeholder="تاريخ الجلسة" data-type="date"  data-date-format="DD/MM/YYYY"   data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?= date_format_exp() ?>"  data-val-required="حقل مطلوب"  name="issue_date_action[]" id="txt_issue_date_action_<?=$count?>" class="form-control datepicker">
			<input  type="hidden" name="h_txt_issue_ser[]"   id="h_txt_issue_ser_<?=$count?>" data-val="false" data-val-required="required" >
            <input type="hidden" name="seq1[]" id="seq1_id_<?=$count?>" value="0"  />
            <input type="hidden" name="h_action_count[]" id="h_action_count_<?=$count?>"  />
                </td>

                <td>
                   <select name="type[]" data-val="true"  data-val-required="حقل مطلوب"  id="dp_type_<?=$count?>" class="form-control">
							<?php foreach($gedco_types as $row) :?>
							<option value="<?= $row['CON_NO'] ?>" ><?= $row['CON_NAME'] ?></option>
						<?php endforeach; ?>
					</select>
                </td>
                <td>
                    <input type="text" data-val="true" placeholder="تاريخ الحكم" data-type="date"   data-date-format="DD/MM/YYYY"   data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?= date_format_exp() ?>"    name="j_date[]" id="txt_j_date_<?=$count?>" class="form-control datepicker" >
                </td>
				<td >
                    <input type="text" data-val="true" placeholder="نص الحكم"     name="j_notes[]"       id="txt_j_notes_<?=$count?>" class="form-control" >
                </td>
				<td>
                    <input type="text" data-val="true" placeholder="ملاحظات"    name="hints[]" id="txt_hints_<?=$count?>" class="form-control">
                </td>


            </tr>



<?php
    $count++;

}else if(count($details) > 0) { // تعديل
    $count = -1;
    foreach($details as $row1) {
        ++$count+1;



        ?>

        <tr>
            <td>
                <input type="text" data-val="true"  placeholder="تاريخ الجلسة" data-type="date"  data-date-format="DD/MM/YYYY"   data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?= date_format_exp() ?>"  data-val-required="حقل مطلوب"  name="issue_date_action[]" id="txt_issue_date_action_<?=$count?>" class="form-control datepicker" value="<?=$row1['ISSUE_DATE_ACTION']?>">
                <input  type="hidden" name="h_txt_issue_ser[]"   id="h_txt_issue_ser_<?=$count?>" data-val="false" data-val-required="required" value="<?=$row1['ISSUE_SER']?>" >
                <input type="hidden" name="seq1[]" id="seq1_id_<?=$count?>" value="<?=$row1['SER']?>"  />
                <input type="hidden" name="h_action_count[]" id="h_action_count_<?=$count?>"  />
            </td>

            <td>
                <select name="type[]" data-val="true"  data-val-required="حقل مطلوب"  id="dp_type_<?=$count?>" class="form-control">
                    <?php foreach($gedco_types as $row) :?>
                        <option value="<?= $row['CON_NO'] ?>"  <?PHP if ($row['CON_NO']==$row1['TYPE']){ echo " selected"; } ?> ><?= $row['CON_NAME'] ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td>
                <input type="text" data-val="true" placeholder="تاريخ الحكم" data-type="date"   data-date-format="DD/MM/YYYY"   data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?= date_format_exp() ?>"    name="j_date[]" id="txt_j_date_<?=$count?>" class="form-control datepicker" value="<?=$row1['J_DATE']?>" >
            </td>
            <td >
                <input type="text" data-val="true" placeholder="نص الحكم"     name="j_notes[]"       id="txt_j_notes_<?=$count?>" class="form-control" value="<?=$row1['J_NOTES']?>" >
            </td>
            <td>
                <input type="text" data-val="true" placeholder="ملاحظات"    name="hints[]" id="txt_hints_<?=$count?>" class="form-control" value="<?=$row1['HINTS']?>">
            </td>



        </tr>
    <?php

    }
}

?>
</tbody>

<tfoot>
<tr>
    <th></th>
    <th></th>
    <th></th>
    <th></th>


    <th></th>
    
</tr>
<tr>
    <th>
        <a   onclick="javascript:add_row(this,'input:text,input:hidden[name^=seq1],textarea,select',false);"  href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>

    </th>
    <th></th>
    <th></th>
    <th></th>

    <th></th>
    
</tr>

</tfoot>
</table></div>
