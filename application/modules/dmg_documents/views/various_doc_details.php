<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 14/07/19
 * Time: 10:11 ص
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
                    <input  type="hidden" name="h_txt_model_no[]"   id="h_txt_model_no_<?=$count?>"
                            data-val="false" data-val-required="required" >
                    <input type="hidden" name="seq1[]" id="seq1_id[]" value="0"  />
                    <input type="hidden" name="h_count[]" id="h_count_<?=$count?>"  />
                </td>

                <td>
                    <input type="text" data-val="true" placeholder="البيان"
                           name="body[]" id="txt_body_<?=$count?>"
                           class="form-control" >
                </td>
                <td>
                    <select name="doc_type[]" id="dp_doc_type_<?=$count?>" class="form-control sel2">
                        <option></option>

                        <?php foreach($doc_type as $row) :?>
                            <option  value="<?= $row['CON_NO'] ?>">
                                <?php echo $row['CON_NAME']  ?></option>
                        <?php endforeach; ?>
                    </select>

                </td>
                <td >
                    <input type="text" data-val-required="حقل مطلوب" data-type="date"
                           data-date-format="DD/MM/YYYY" name="doc_date[]"
                           id="txt_doc_date_<?=$count?>" class="form-control"
                        >
                </td>
                <td >
                    <input type="text" data-val="true" placeholder="العدد"
                           name="doc_number[]" id="txt_doc_number_<?=$count?>"
                           class="form-control" >
                </td>

                <td >
                    <input type="text" data-val="true" placeholder="ملاحظات"
                           name="notes_doc[]" id="txt_notes_doc_<?=$count?>"
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
                        <input type="text" data-val="true" placeholder="البيان"
                               name="body[]" id="txt_body_<?=$count?>"
                               class="form-control" value="<?=$row1['BODY']?>"  >
                    </td>
                    <td>
                        <select name="doc_type[]" id="dp_doc_type_<?=$count?>" class="form-control sel2">
                            <option></option>

                            <?php foreach($doc_type as $row) :?>
                                <option  value="<?= $row['CON_NO'] ?>"
                                    <?PHP if ($row['CON_NO']==((count(@$details)>0)?@$row1['DOC_TYPE']:0)){ echo " selected"; } ?> ><?php echo $row['CON_NAME']  ?></option>
                            <?php endforeach; ?>
                        </select>

                    </td>
                    <td >
                        <input type="text" data-val-required="حقل مطلوب" data-type="date"
                               data-date-format="DD/MM/YYYY" name="doc_date[]" value="<?=$row1['DOC_DATE']?>"
                               id="txt_doc_date_<?=$count?>" class="form-control"
                            >
                    </td>
                    <td >
                        <input type="text" data-val="true" placeholder="العدد"
                               name="doc_number[]" id="txt_doc_number_<?=$count?>"
                               class="form-control" value="<?=$row1['DOC_NUMBER']?>" >
                    </td>

                    <td >
                        <input type="text" data-val="true" placeholder="ملاحظات"
                               name="notes_doc[]" id="txt_notes_doc_<?=$count?>"
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
    </table></div>
