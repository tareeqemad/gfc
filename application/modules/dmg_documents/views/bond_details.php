<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 20/01/20
 * Time: 01:44 م
 */


$MODULE_NAME= 'dmg_documents';
$TB_NAME= 'bond';
$isCreate =isset($details) && count($details)  > 0 ?false:true;
$count=0;

?>
<div class="tb_container">
    <table class="table" id="details_tb1" data-container="container"  align="center">
        <thead>
        <tr>
            <th>رقم المسلسل</th>
            <th>رقم القيد</th>
            <th> تاريخ القيد </th>
            <th> نوع القيد </th>
            <th>بيان القيد</th>
            <th>المبلغ </th>
        </tr>
        </thead>
        <tbody>
        <?php if(count($details) <= 0) {  // ادخال ?>

            <tr>
                <td>
                    <input type="text" readonly name="" id="txt_ser"
                           class="form-control">
                    <input  type="hidden" name="h_txt_model_no[]"   id="h_txt_model_no_<?=$count?>"
                            data-val="false" data-val-required="required" >
                    <input type="hidden" name="seq1[]" id="seq1_id[]" value="0"  />
                    <input type="hidden" name="h_count[]" id="h_count_<?=$count?>"  />
                </td>
                <td>
                    <input type="text"  name="bond_ser[]" id="txt_bond_ser_<?=$count?>"
                           class="form-control">
                </td>
                <td>
                    <input type="text"  data-type="date" data-date-format="DD/MM/YYYY"
                           name="bond_date[]"
                           id="txt_bond_date_<?=$count?>"class="form-control ltr">
                </td>
                <td>
                    <select name="bond_type[]" id="dp_bond_type_<?=$count?>" class="form-control sel2">
                        <option></option>

                        <?php foreach($bond_type as $row) :?>
                            <option  value="<?= $row['CON_NO'] ?>">
                                <?php echo $row['CON_NAME']  ?></option>
                        <?php endforeach; ?>
                    </select>

                </td>

                <td>
                    <input type="text" data-val="true" placeholder="بيان القيد"
                           name="bond_body[]" id="txt_bond_body_<?=$count?>"
                           class="form-control" >
                </td>

                <td>
                    <input type="text"  name="amount[]" id="txt_amount_<?=$count?>"
                           class="form-control">
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
                        <input type="text" readonly name="" id="txt_ser"
                               class="form-control" value="<?=$row1['SER']?>">
                        <input  type="hidden" name="h_txt_model_no[]" value="<?=$row1['MODEL_NO']?>"
                                id="h_txt_model_no_<?=$count?>"
                                data-val="false" data-val-required="required" >

                        <input type="hidden" name="seq1[]" value="<?=$row1['SER']?>"
                               id="seq1_id[]"  />
                        <input type="hidden" name="h_count[]" id="h_count_<?=$count?>"  />
                    </td>


                    <td>
                        <input type="text"   value="<?=$row1['BOND_SER']?>"
                               name="bond_ser[]" id="txt_bond_ser_<?=$count?>"
                               class="form-control">
                    </td>
                    <td>
                        <input type="text"  data-type="date" data-date-format="DD/MM/YYYY"
                               name="bond_date[]"  value="<?=$row1['BOND_DATE']?>"
                               id="txt_bond_date_<?=$count?>"class="form-control ltr">
                    </td>
                    <td>
                        <select name="bond_type[]" id="dp_bond_type_<?=$count?>" class="form-control sel2">
                            <option></option>

                            <?php foreach($bond_type as $row) :?>
                                <option  value="<?= $row['CON_NO'] ?>"
                                    <?PHP if ($row['CON_NO']==((count(@$details)>0)?@$row1['BOND_TYPE']:0)){ echo " selected"; } ?> ><?php echo $row['CON_NAME']  ?></option>
                            <?php endforeach; ?>
                        </select>

                    </td>

                    <td>
                        <input type="text" data-val="true" placeholder="بيان القيد"
                               value="<?=$row1['BOND_BODY']?>"
                               name="bond_body[]" id="txt_bond_body_<?=$count?>"
                               class="form-control" >
                    </td>

                    <td>
                        <input type="text"  value="<?=$row1['AMOUNT']?>"
                               name="amount[]" id="txt_amount_<?=$count?>"
                               class="form-control">
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


        </tr>
        <tr>
            <th>
                <a   onclick="javascript:add_row(this,'input:text,input:hidden[name^=seq1],input:text,input:text',false);"
                     href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>

            </th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>

        </tr>

        </tfoot>
    </table>
</div>
