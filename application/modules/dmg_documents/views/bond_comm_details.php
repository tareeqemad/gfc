<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 21/01/20
 * Time: 08:33 ص
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
        <?php  if(count($details) > 0) { // تعديل
            $count = -1;
                foreach($details as $row1) {
                    ++$count+1;

                    ?>
                    <tr>
                        <td>
                            <input type="text" readonly name="" id="txt_ser"
                                   class="form-control" value="<?=$row1['SER']?>">
                            <input  type="hidden" readonly name="h_txt_model_no[]" value="<?=$row1['MODEL_NO']?>"
                                    id="h_txt_model_no_<?=$count?>"
                                    data-val="false" data-val-required="required" >

                            <input type="hidden" readonly name="seq1[]" value="<?=$row1['SER']?>"
                                   id="seq1_id[]"  />
                            <input type="hidden" readonly name="h_count[]" id="h_count_<?=$count?>"  />
                        </td>


                        <td>
                            <input type="text" readonly   value="<?=$row1['BOND_SER']?>"
                                   name="bond_ser[]" id="txt_bond_ser_<?=$count?>"
                                   class="form-control">
                        </td>
                        <td>
                            <input type="text" readonly data-val="true" placeholder="التاريخ"
                                   name="bond_date[]" id="txt_bond_date_<?=$count?>"
                                   class="form-control" value="<?=$row1['BOND_DATE']?>" >
                        </td>

                        <td>
                            <input type="text" readonly data-val="true" placeholder="نوع القيد"
                                   name="bond_type[]" id="txt_bond_type_<?=$count?>"
                                   class="form-control" value="<?=$row1['BOND_TYPE_NAME']?>" >

                        </td>


                        <td>
                            <input type="text" readonly data-val="true" placeholder="بيان القيد"
                                   value="<?=$row1['BOND_BODY']?>"
                                   name="bond_body[]" id="txt_bond_body_<?=$count?>"
                                   class="form-control" >
                        </td>

                        <td>
                            <input type="text" readonly  value="<?=$row1['AMOUNT']?>"
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
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>

        </tr>

        </tfoot>
    </table></div>
