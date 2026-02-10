<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$MODULE_NAME= 'payroll_data';
$TB_NAME= 'Salary_benef';
$count=1;
$d_count=0;
$total_inst_value = 0;
$total_install_value = 0;
?>

<div class="table-responsive">
    <table class="table table-bordered " id="details_tb" data-container="container">
        <thead class="table-primary">
        <tr>
            <th style="width: 5%">م</th>
            <th style="width: 30%">الموظف</th>
            <th style="width: 15%">آلية الإحتساب</th>
            <th style="width: 10%">المبلغ</th>
            <th style="width: 10%">عدد الأقساط</th>
            <th style="width: 10%">من شهر</th>
            <th style="width: 10%">إلى شهر</th>
            <th style="width: 10%"> قيمة القسط</th>
            <th style="width: 5%">الإجراء</th>

        </tr>
        </thead>

        <tbody>

        <?php if(count($details) <= 0) {  // ادخال ?>
            <tr>
                <td>
                    <input name="txt_bill_ser_det[]" id="txt_bill_ser_det<?=$count?>" class="form-control" value="0"  style="text-align: center" readonly>
                </td>

                <td>
                    <select name="emp_no_det[]" id="emp_no_det<?=$count?>" class="form-control sel2" >
                        <option value="0">_________</option>
                        <?php foreach($emp_no_cons as $row) :?>
                            <option value="<?= $row['EMP_NO'] ?>"><?= $row['EMP_NO'] . ': ' . $row['EMP_NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>

                <td>
                    <select name="calculation_type[]" id="calculation_type<?=$count?>" class="form-control" >
                        <option value="">_________</option>
                        <?php foreach ($calculation_type as $row) : ?>
                            <option value="<?= $row['CON_NO'] ?>"> <?= $row['CON_NAME'] ?> </option>
                        <?php endforeach; ?>
                    </select>
                </td>

                <td class="install_val">
                <input  name="txt_install_value[]" class="form-control " id="txt_install_value<?=$count?>" style="text-align: center" onchange="total_install_val()" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))"/>
                </td>

                <td>
                    <input  name="number_inst[]"  class="form-control" id="number_inst<?=$count?>" style="text-align: center"  onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))" />
                </td>

                <td>
                    <input  name="txt_inst_month[]"  class="form-control" id="txt_inst_month<?=$count?>" value="<?=$current_date?>" style="text-align: center" maxlength="6"  readonly/>
                </td>

                <td>
                    <input  name="txt_to_month[]"  class="form-control" id="txt_to_month<?=$count?>" style="text-align: center"  maxlength="6" readonly/>
                </td>

                <td class="inst_val">
                    <input  name="txt_price_part[]"  class="form-control" id="txt_price_part<?=$count?>" onchange="total_instalment_val()"  style="text-align: center" readonly/>
                </td>

                <td></td>
            </tr>

            <?php
        }

        else if(count($details) > 0) { // تعديل
            $count = -1;

            foreach($details as $row)    {
                $count++;
                $d_count++;
                ?>

                <tr <?=$total_inst_value += $row['PRICE_PART'];?>  <?=$total_install_value += $row['INSTALL_VALUE'];?> >
                    <td>
                        <input type="hidden" name="txt_bill_ser_det[]" id="txt_bill_ser_det<?=$count?>" class="form-control" value="<?=$row['SER']?>"  style="text-align: center" readonly>
                        <input name="bill_ser_det[]" id="bill_ser_det<?=$d_count?>" class="form-control" value="<?=$d_count?>"  style="text-align: center" readonly>
                    </td>

                    <td>
                        <input type="hidden" name="emp_no_det[]" value="<?=$row['EMP_NO']?>" />
                        <input  class="form-control" id="emp_no_det<?=$count?>"  value="<?=$row['EMP_NAME']?>" style="text-align: center" readonly />
                    </td>

                    <td>
                        <select name="calculation_type[]" id="calculation_type<?=$count?>" data-curr="false" class="form-control" data-val="false" data-val-required="required">
                            <option value="0">-</option>
                            <?php foreach ($calculation_type as $row2) : ?>
                                <option value="<?= $row2['CON_NO'] ?>" <?PHP  if ($row['CALCULATION_TYPE'] == $row2['CON_NO']) echo " selected"; ?> ><?= $row2['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>

                    <td class="install_val">
                        <input  name="txt_install_value[]" class="form-control" id="txt_install_value<?=$count?>" value="<?=$row['INSTALL_VALUE']?>" onchange="total_install_val()" style="text-align: center" onkeyup="$('#calculation_type<?=$count?>').change();" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))"/>
                    </td>

                    <td>
                        <?php if ( $row['CALCULATION_TYPE'] == 4  ) :  ?>
                            <input  name="number_inst[]"  class="form-control" id="number_inst<?=$count?>" value="<?=$row['NUMBER_INST']?>" style="text-align: center" readonly />
                        <?php else : ?>
                            <input  name="number_inst[]"  class="form-control" id="number_inst<?=$count?>" value="<?=$row['NUMBER_INST']?>" style="text-align: center" onkeyup="$('#calculation_type<?=$count?>').change();" />
                        <?php endif; ?>
                    </td>

                    <td>
                        <?php if ( $row['CALCULATION_TYPE'] == 4  ) :  ?>
                            <input  name="txt_inst_month[]"  class="form-control" id="txt_inst_month<?=$count?>" value="<?=$row['INST_MONTH']?>" onkeyup="$('#calculation_type<?=$count?>').change();" maxlength="6" style="text-align: center"/>
                        <?php else : ?>
                            <input  name="txt_inst_month[]"  class="form-control" id="txt_inst_month<?=$count?>" value="<?=$row['INST_MONTH']?>" maxlength="6" minlength="6" style="text-align: center" readonly />
                        <?php endif; ?>
                    </td>

                    <td>
                        <input  name="txt_to_month[]"  class="form-control" id="txt_to_month<?=$count?>" value="<?=$row['TO_MONTH']?>"  maxlength="6" style="text-align: center" readonly />
                    </td>

                    <td class="inst_val">
                        <?php if ( $row['CALCULATION_TYPE'] == 4  ) :  ?>
                            <input  name="txt_price_part[]"  class="form-control" id="txt_price_part<?=$count?>" value="<?=$row['PRICE_PART']?>"  onchange="total_instalment_val()" style="text-align: center" onkeyup="$('#calculation_type<?=$count?>').change();" />
                        <?php else : ?>
                            <input  name="txt_price_part[]"  class="form-control" id="txt_price_part<?=$count?>" value="<?=$row['PRICE_PART']?>"  onchange="total_instalment_val()" style="text-align: center" readonly />
                        <?php endif; ?>
                    </td>

                    <td class="text-center">
                    <?php if ($adopt_det <= 1 ) { ?>
                        <a onclick="javascript:delete_row(this,<?= $row['SER'] ?>);" href="javascript:;" ><i class="fa fa-trash"></i></a>
                    <?php } ?>
                    </td>

                </tr>
                <?php
            }
        }
        ?>

        </tbody>
        <tfoot>
        <tr>
            <?php if (count($details) >= 0 and  $adopt_det <= 1 ) { ?>
                <th>
                    <a onclick="javascript:addRow();" href="javascript:;" ><i class="glyphicon glyphicon-plus"></i>جديد</a>
                </th>
            <?php } ?>
            <?php if ( $adopt_det >= 2 ) :  ?>
                <th></th>
            <?php endif; ?>
                <th></th>
                <th></th>
                <th class="text-center" id="sum_total_install">
                    <span class="btn btn-info">
                        <?= number_format($total_install_value, 2) ?>
                    </span>
                </th>
                <th></th>
                <th></th>
                <th></th>
                <th class="text-center" id="sum_total_instalment">
                    <span class="btn btn-info">
                        <?= number_format($total_inst_value, 2) ?>
                    </span>
                </th>
                <th></th>

        </tr>
        </tfoot>
    </table>
</div>

<script type="text/javascript">

</script>