<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 21/01/20
 * Time: 10:22 ص
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
        <?php  if(count($details) > 0) { // تعديل
            $count = -1;
            foreach($details as $row1) {
                ++$count+1;

                ?>
                <tr>
                    <td>
                        <input type="text" readonly name="" id="txt_ser"
                               class="form-control" value="<?=$row1['SER']?>" readonly >
                        <input  type="hidden" name="h_txt_model_no[]" value="<?=$row1['MODEL_NO']?>"
                                id="h_txt_model_no_<?=$count?>" readonly
                                data-val="false" data-val-required="required" >

                        <input type="hidden" name="seq1[]" value="<?=$row1['SER']?>"
                               id="seq1_id[]" readonly  />
                        <input type="hidden" name="h_count[]" readonly id="h_count_<?=$count?>"  />
                    </td>


                    <td>
                        <input type="text"  name="re_dis_ser[]" id="txt_re_dis_ser_<?=$count?>"
                               value="<?=$row1['RE_DIS_SER']?>" readonly
                               class="form-control">
                    </td>
                    <td>
                        <input type="text" readonly data-val="true" placeholder="التاريخ"
                               name="re_dis_date[]" id="txt_re_dis_date_<?=$count?>"
                               class="form-control" value="<?=$row1['RE_DIS_DATE']?>" >

                    </td>
                    <td>
                        <input type="text" readonly data-val="true"
                               name="bond_type[]" id="txt_bond_type_<?=$count?>"
                               class="form-control" value="<?=$row1['DIS_RES_TYPE_NAME']?>" >

                    </td>

                    <td>
                        <input type="text" data-val="true" placeholder="بيان السند"
                               name="body[]" id="txt_body_<?=$count?>"
                               value="<?=$row1['BODY']?>" readonly
                               class="form-control" >
                    </td>

                    <td>
                        <input type="text" readonly  name="amount[]" id="txt_amount_<?=$count?>"
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
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>

        </tr>

        </tfoot>
    </table></div>
