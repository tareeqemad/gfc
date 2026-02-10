<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 21/01/20
 * Time: 09:51 ص
 */



$MODULE_NAME= 'dmg_documents';
$TB_NAME= 'receipts_disbursements';
$isCreate =isset($details) && count($details)  > 0 ?false:true;
$count=0;

?>
<div class="tb_container">
    <table class="table" id="details_tb1" data-container="container"  align="center">
        <thead>
        <tr>
            <th>رقم المسلسل</th>
            <th>رقم السند </th>
            <th>  تاريخ السند </th>
            <th>   نوع السند</th>
            <th> بيان السند</th>
            <th> المبلغ </th>
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
                    <input type="text"  name="re_dis_ser[]" id="txt_re_dis_ser_<?=$count?>"
                           class="form-control">
                </td>
                <td>
                    <input type="text"  data-type="date" data-date-format="DD/MM/YYYY"
                           name="re_dis_date[]"
                           id="txt_re_dis_date_<?=$count?>"class="form-control ltr">
                </td>
                <td>
                    <select name="type[]" id="dp_type_<?=$count?>" class="form-control sel2">
                        <option></option>

                        <?php foreach($receipts_disbursements_type as $row) :?>
                            <option  value="<?= $row['CON_NO'] ?>">
                                <?php echo $row['CON_NAME']  ?></option>
                        <?php endforeach; ?>
                    </select>

                </td>

                <td>
                    <input type="text" data-val="true" placeholder="بيان السند"
                           name="body[]" id="txt_body_<?=$count?>"
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
                        <input type="text"  name="re_dis_ser[]" id="txt_re_dis_ser_<?=$count?>"
                               value="<?=$row1['RE_DIS_SER']?>"
                               class="form-control">
                    </td>
                    <td>
                        <input type="text"  data-type="date" data-date-format="DD/MM/YYYY"
                               name="re_dis_date[]"
                               value="<?=$row1['RE_DIS_DATE']?>"
                               id="txt_re_dis_date_<?=$count?>"class="form-control ltr">
                    </td>
                    <td>
                        <select name="type[]" id="dp_type_<?=$count?>" class="form-control sel2">
                            <option></option>
                            <?php foreach($receipts_disbursements_type as $row) :?>
                                <option  value="<?= $row['CON_NO'] ?>"
                                    <?PHP if ($row['CON_NO']==((count(@$details)>0)?@$row1['TYPE']:0)){ echo " selected"; } ?> ><?php echo $row['CON_NAME']  ?></option>
                            <?php endforeach; ?>

                        </select>

                    </td>

                    <td>
                        <input type="text" data-val="true" placeholder="بيان السند"
                               name="body[]" id="txt_body_<?=$count?>"
                               value="<?=$row1['BODY']?>"
                               class="form-control" >
                    </td>

                    <td>
                        <input type="text"  name="amount[]" id="txt_amount_<?=$count?>"
                               value="<?=$row1['AMOUNT']?>"
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
