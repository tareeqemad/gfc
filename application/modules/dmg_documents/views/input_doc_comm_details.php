<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 21/01/20
 * Time: 01:16 م
 */



$MODULE_NAME= 'dmg_documents';
$TB_NAME= 'input_doc';
$isCreate =isset($details) && count($details)  > 0 ?false:true;
$count=0;

?>
<div class="tb_container">
    <table class="table" id="details_tb1" data-container="container"  align="center">
        <thead>
        <tr>
            <th>رقم المسلسل</th>
            <th>رقم المستند</th>
            <th>تاريخ المستند</th>
            <th>نسخة المستند</th>
            <th>بيان المستند</th>
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
                        <input type="hidden"  readonly name="h_count[]" id="h_count_<?=$count?>"  />
                    </td>



                    <td>
                        <input type="text" readonly name="document_number[]"
                               id="txt_document_number_<?=$count?>" value="<?=$row1['DOCUMENT_NUMBER']?>"
                               class="form-control">
                    </td>
                    <td>

                        <input type="text" readonly data-val="true" placeholder="التاريخ"
                               name="document_date[]" id="txt_document_date_<?=$count?>"
                               class="form-control" value="<?=$row1['DOCUMENT_DATE']?>" >
                    </td>
                    <td>
                        <input type="text" readonly data-val="true"
                               name="copy_document[]" id="txt_copy_document_<?=$count?>"
                               class="form-control" value="<?=$row1['COPY_DOCUMENT_NAME']?>" >

                    </td>

                    <td>
                        <input type="text" data-val="true" placeholder="بيان المستند"
                               value="<?=$row1['DOCUMENT_BODY']?>" readonly
                               name="document_body[]" id="txt_document_body_<?=$count?>"
                               class="form-control" >
                    </td>

                    <td>
                        <input type="text"  readonly name="amount[]" id="txt_amount_<?=$count?>"
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

