<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 22/08/19
 * Time: 10:34 ص
 */

$MODULE_NAME= 'indicator';//folder
$TB_NAME='manage_indecatior_info';//controller
//$delete_url_details= base_url("$MODULE_NAME/$TB_NAME/delete_sub_details");
$isCreate =isset($details) && count($details)  > 0 ?false:true;
$count=0;


?>
<div class="tb_container">
    <table class="table" id="details_tb1" data-container="container"  align="center">
        <thead>
        <tr>
            <th>مؤشر</th>
            <th>فترة</th>
            <th>ملخص</th>
            <th>عملية</th>
            <!-- <th>عملية على نسبة أو قيمة</th> -->
            <!-- <th>نسبة</th> -->
            <th>نسبة/قيمة</th>
            <th>رقم القيمة / النسبة</th>
            <th>عملية</th>
        </tr>
        </thead>
        <tbody>
        <?php if(count(@$details) <= 0) {  // ادخال ?>
            <tr>
                <td>
                    <select name="indecator_ser_val[]"  id="dp_indecator_ser_val_<?=$count?>" class="form-control">
                        <option value="" >-----</option>
                        <?php foreach($indecator as $row) :?>
                            <option value="<?= $row['INDECATOR_SER'] ?>" ><?= $row['INDECATOR_NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input  type="hidden" name="indecator_calc[]"   id="h_indecator_calc_<?=$count?>"  data-val="true" data-val-required="required">

                    <input  type="hidden" name="calc_type[]"   id="h_calc_type_<?=$count?>" data-val="true" value="1" data-val-required="required" >
                    <input type="hidden" name="ser_calc[]" id="h_ser_calc[]" value="0"  />
                    <input type="hidden" name="h_count[]" id="h_count_<?=$count?>"  />
                </td>

                <td>
                    <select  name="for_month_calc[]"  id="dp_for_month_calc_<?=$count?>" class="form-control">
                        <option value="" >-----</option>
                        <?php foreach($for_month_calc as $row) :?>
                            <option value="<?= $row['CON_NO'] ?>" ><?= $row['CON_NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td  class="summary_indecator_<?=$count?>" >
                    <select name="sumarize[]"  id="dp_sumarize_<?=$count?>" class="form-control">
                        <option value="" >-----</option>
                        <?php foreach($sumarize as $row) :?>
                            <option value="<?= $row['CON_NAME'] ?>" ><?= $row['CON_NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>

                </td>
                <td>

                    <select name="oper1[]"   id="dp_oper1_<?=$count?>" class="form-control">
                        <option value="" >-----</option>
                        <?php foreach($oper as $row) :?>
                            <option value="<?= $row['CON_NAME'] ?>" ><?= $row['CON_NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>

                </td>

                <td  class="summary_indecator_<?=$count?>">
                    <select name="is_value[]"  id="dp_is_value_<?=$count?>" class="form-control">
                        <option value="" >-----</option>
                        <?php foreach($is_value as $row) :?>
                            <option value="<?= $row['CON_NO'] ?>" ><?= $row['CON_NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>

                <td  class="secon_indecator_<?=$count?>">
                    <input type="text"  name="value_calc[]" id="txt_value_calc_<?=$count?>" class="form-control">
                </td>

                <td  class="secon_indecator_<?=$count?>">
                    <select name="oper2[]"   id="dp_oper2_<?=$count?>" class="form-control">
                        <option value="" >-----</option>
                        <?php foreach($oper as $row) :?>
                            <option value="<?= $row['CON_NAME'] ?>" ><?= $row['CON_NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>


            </tr>
            <?php
            $count++;

        }else if(count(@$details) > 0) { // تعديل

            $count = -1;
            foreach($details as $row1) {
                ++$count+1;



                ?>
                <tr>
                    <td>
                        <select name="indecator_ser_val[]"  id="dp_indecator_ser_val_<?=$count?>" class="form-control">
                            <option value="" >-----</option>
                            <?php foreach($indecator as $row) :?>
                                <option value="<?= $row['INDECATOR_SER'] ?>" <?PHP if ($row['INDECATOR_SER']==$row1['INDECATOR_SER']){ echo " selected"; } ?>><?= $row['INDECATOR_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                        <input  type="hidden" name="indecator_calc[]"   id="h_indecator_calc_<?=$count?>" value="<?=$row1['INDECATOR_CALC']?>" data-val="true" data-val-required="required" >
                        <input  type="hidden" name="calc_type[]"   id="h_calc_type_<?=$count?>" value="<?=$row1['CALC_TYPE']?>" data-val="true" data-val-required="required" >
                        <input type="hidden" name="ser_calc[]" id="h_ser_calc[]" value="<?=$row1['SER']?>"  />
                        <input type="hidden" name="h_count[]" id="h_count_<?=$count?>"  />
                    </td>

                    <td>
                        <select  name="for_month_calc[]"  id="dp_for_month_calc_<?=$count?>" class="form-control">
                            <option value="" >-----</option>
                            <?php foreach($for_month_calc as $row) :?>
                                <option value="<?= $row['CON_NO'] ?>" <?PHP if ($row['CON_NO']==$row1['FOR_MONTH']){ echo " selected"; } ?>><?= $row['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td  class="summary_indecator_<?=$count?>" >
                        <select name="sumarize[]"  id="dp_sumarize_<?=$count?>" class="form-control">
                            <option value="" >-----</option>
                            <?php foreach($sumarize as $row) :?>
                                <option value="<?= $row['CON_NAME'] ?>" <?PHP if ($row['CON_NAME']==$row1['SUMARIZE']){ echo " selected"; } ?>><?= $row['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>

                    </td>
                    <td>

                        <select name="oper1[]"   id="dp_oper1_<?=$count?>" class="form-control">
                            <option value="" >-----</option>
                            <?php foreach($oper as $row) :?>
                                <option value="<?= $row['CON_NAME'] ?>" <?PHP if ($row['CON_NAME']==$row1['OPER1']){ echo " selected"; } ?>><?= $row['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>

                    </td>

                    <td  class="summary_indecator_<?=$count?>">
                        <select name="is_value[]" id="dp_is_value_<?=$count?>" class="form-control">
                            <option value="" >-----</option>
                            <?php foreach($is_value as $row) :?>
                                <option value="<?= $row['CON_NO'] ?>" <?PHP if ($row['CON_NO']==$row1['IS_VALUE']){ echo " selected"; } ?>><?= $row['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>

                    <td  class="secon_indecator_<?=$count?>">
                        <input type="text"  name="value_calc[]" id="txt_value_calc_<?=$count?>" value="<?=$row1['VALUE']?>" class="form-control">
                    </td>

                    <td  class="secon_indecator_<?=$count?>">
                        <select name="oper2[]"   id="dp_oper2_<?=$count?>" class="form-control">
                            <option value="" >-----</option>
                            <?php foreach($oper as $row) :?>
                                <option value="<?= $row['CON_NAME'] ?>" <?PHP if ($row['CON_NAME']==$row1['OPER2']){ echo " selected"; } ?> ><?= $row['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
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
            <th></th>
            <th></th>

        </tr>
        <tr>
            <th>
                <a hidden  id="new_row"  onclick="javascript:add_row(this,'select,input:hidden[name^=ser_calc],select,select,select,select,select,input:text,select',false);"  href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>

            </th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>

        </tfoot>


    </table></div>
