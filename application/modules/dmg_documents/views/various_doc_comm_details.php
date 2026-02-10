<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 14/07/19
 * Time: 10:19 ص
 */



$MODULE_NAME= 'dmg_documents';
$TB_NAME= 'various_doc';
$isCreate =isset($details) && count($details)  > 0 ?false:true;
$count=0;

?>
<div class="tb_container">
    <table class="table" id="details_tb1" data-container="container"  align="center">
        <thead>
        <tr>

            <th>رقم المسلسل</th>
            <th>البيان</th>
            <th style="width: 8%">نوع المستند</th>
            <th>التاريخ</th>
            <th>العدد</th>
            <th style="width: 16%">ملاحظات</th>
        </tr>
        </thead>
        <tbody>
        <?php if(count($details) <= 0) {  // ادخال ?>

            <tr>
                <td>
                    <input type="text" readonly name="" id="txt_ser"
                           class="form-control">
                    <input readonly  type="hidden" name="h_txt_model_no[]"   id="h_txt_model_no_<?=$count?>"
                            data-val="false" data-val-required="required" >
                    <input readonly type="hidden" name="seq1[]" id="seq1_id[]" value="0"  />
                    <input readonly type="hidden" name="h_count[]" id="h_count_<?=$count?>"  />
                </td>

                <td>
                    <input readonly type="text" data-val="true" placeholder="البيان"
                           name="body[]" id="txt_body_<?=$count?>"
                           class="form-control" >
                </td>
                <td>

                    <input type="text" readonly data-val="true" placeholder="نوع المستند "
                           name="doc_type[]" id="txt_doc_type_<?=$count?>"
                           class="form-control"  >

                </td>
                <td >
                    <input type="text" readonly data-val="true" placeholder="التاريخ"
                           name="doc_date[]" id="txt_doc_date_<?=$count?>"
                           class="form-control"  >
                </td>
                <td >
                    <input readonly type="text" data-val="true" placeholder="العدد"
                           name="doc_number[]" id="txt_doc_number_<?=$count?>"
                           class="form-control" >
                </td>

                <td >
                    <input  readonly type="text" data-val="true" placeholder="ملاحظات"
                           name="notes[]" id="txt_notes_<?=$count?>"
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
                        <input  type="text" readonly name="" id="txt_ser"
                               class="form-control" value="<?=$row1['SER']?>">
                        <input readonly type="hidden" name="h_txt_model_no[]" value="<?=$row1['MODEL_NO']?>"
                                id="h_txt_model_no_<?=$count?>"
                                data-val="false" data-val-required="required" >

                        <input readonly type="hidden" name="seq1[]" value="<?=$row1['SER']?>"
                               id="seq1_id[]"  />
                        <input readonly type="hidden" name="h_count[]" id="h_count_<?=$count?>"  />
                    </td>

                    <td>
                        <input readonly type="text" data-val="true" placeholder="البيان"
                               name="body[]" id="txt_body_<?=$count?>"
                               class="form-control" value="<?=$row1['BODY']?>"  >
                    </td>
                    <td>
                        <input type="text" readonly data-val="true" placeholder="نوع المستند "
                               name="doc_type[]" id="txt_doc_type_<?=$count?>"
                               class="form-control" value="<?=$row1['DOC_TYPE_NAME']?>" >

                    </td>
                    <td >

                        <input type="text" readonly data-val="true" placeholder="التاريخ"
                               name="doc_date[]" id="txt_doc_date_<?=$count?>"
                               class="form-control" value="<?=$row1['DOC_DATE']?>" >
                    </td>
                    <td >
                        <input readonly type="text" data-val="true" placeholder="العدد"
                               name="doc_number[]" id="txt_doc_number_<?=$count?>"
                               class="form-control" value="<?=$row1['DOC_NUMBER']?>" >
                    </td>

                    <td >
                        <input readonly type="text" data-val="true" placeholder="ملاحظات"
                               name="notes[]" id="txt_notes_<?=$count?>"
                               class="form-control" value="<?=$row1['NOTES']?>" >
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
