
<?php
$MODULE_NAME= 'issues';
$TB_NAME= 'checks';
$isCreate =isset($details) && count($details)  > 0 ?false:true;
$count=0;


?>

<div class="tb_container">
    <table class="table" id="details_tb1" data-container="container"  align="center">
        <thead>
        <tr>
            <th  style="width: 16%">تاريخ القسط</th>
            <th style="width: 16%" >قيمة القسط</th>
        </tr>
        </thead>
        <tbody>
        <?php if(count($details) <= 0) {  // ادخال ?>
            <tr>
                <td>
                    <input type="text" data-val="true" data-type="date" data-val-required="حقل مطلوب" placeholder="تاريخ القسط" data-date-format="DD/MM/YYYY"   data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?= date_format_exp() ?>"    name="pay_date[]" id="txt_pay_date_<?=$count?>" class="form-control datepicker">
                    <input  type="hidden" name="h_txt_issue_ser[]"   id="h_txt_issue_ser_<?=$count?>" data-val="false" data-val-required="required" >
                    <input type="hidden" name="seq1[]" id="seq1_id[]" value="0"  />
                    <input type="hidden" name="h_count[]" id="h_count_<?=$count?>"  />
                </td>

                <td>
                    <input type="text" data-val="true" placeholder="قيمة القسط"  name="pay_value[]" id="txt_pay_value_<?=$count?>" class="form-control">
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
                        <input type="text" data-val="true" data-type="date" value="<?=$row1['PAY_DATE']?>" data-val-required="حقل مطلوب" placeholder="تاريخ القسط" data-date-format="DD/MM/YYYY"   data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?= date_format_exp() ?>"    name="pay_date[]" id="txt_pay_date_<?=$count?>" class="form-control datepicker">
                        <input  type="hidden" name="h_txt_issue_ser[]"  value="<?=$row1['CHECK_SER']?>"  id="h_txt_issue_ser_<?=$count?>" data-val="false" data-val-required="required" >
                        <input type="hidden" name="seq1[]" id="seq1_id[]" value="<?=$row1['SER']?>"  />
                        <input type="hidden" name="h_count[]" id="h_count_<?=$count?>"  />
                    </td>
                    <td>
                        <input type="text" data-val="true" value="<?=$row1['PAY_VALUE']?>" placeholder="قيمة القسط"  name="pay_value[]" id="txt_pay_value_<?=$count?>" class="form-control">
                    </td>
                </tr>
            <?php
            }
        }
        ?>
        </tbody>
        <tfoot>
        <tr>
            <th> </th>
            <th id=""></th>
        </tr>
        <tr>
            <th>
                <a id="add_new_row" onclick="javascript:add_row(this,'input:text,input:hidden[name^=seq1],textarea,select',false);"  href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>
            </th>
            <th></th>
        </tr>
        </tfoot>
    </table>
</div>
