<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 09/07/19
 * Time: 08:28 ص
 */

$MODULE_NAME= 'dmg_documents';
$TB_NAME= 'documents';
$isCreate =isset($details) && count($details)  > 0 ?false:true;
$count=0;


?>
<div class="tb_container">
    <table class="table" id="details_tb1" data-container="container"  align="center">
        <thead>
        <tr>
            <th style="width: 8%">رقم المسلسل</th>
            <th style="width: 8%">رقم الدفتر</th>
            <th style="width: 15%">من رقم ايصال</th>
            <th style="width: 15%">الى رقم ايصال</th>
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
                    <input type="text" data-val="true" placeholder="رقم الدفتر"
                           name="document_ser[]" id="txt_document_ser_<?=$count?>"
                           class="form-control" >
                </td>
                <td>
                    <input type="text" data-val="true" placeholder="من رقم ايصال"
                           name="from_receipt_no[]" id="txt_from_receipt_no_<?=$count?>"
                           class="form-control" >
                </td>
                <td >
                    <input type="text" data-val="true" placeholder="الى رقم ايصال"
                           name="to_receipt_no[]" id="txt_to_receipt_no_<?=$count?>"
                           class="form-control" >
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
                        <input type="text" data-val="true" placeholder="رقم الدفتر"
                               name="document_ser[]" value="<?=$row1['DOCUMENT_SER']?>"
                               id="txt_document_ser_<?=$count?>" class="form-control" >
                    </td>
                    <td>
                        <input type="text" data-val="true" placeholder="من رقم ايصال"
                               name="from_receipt_no[]" value="<?=$row1['FROM_RECEIPT_NO']?>"
                               id="txt_from_receipt_no_<?=$count?>" class="form-control" >
                    </td>
                    <td >
                        <input type="text" data-val="true" placeholder="الى رقم ايصال"
                               name="to_receipt_no[]" value="<?=$row1['TO_RECEIPT_NO']?>"
                               id="txt_to_receipt_no_<?=$count?>" class="form-control" >
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

        </tr>
        <tr>
            <th>
                <a   onclick="javascript:add_row(this,'input:text,input:hidden[name^=seq1],input:text,input:text',false);"
                     href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>

            </th>
            <th></th>
            <th></th>
            <th></th>

        </tr>

        </tfoot>
    </table></div>
