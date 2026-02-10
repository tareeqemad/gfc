<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 13/07/19
 * Time: 12:17 م
 */


$MODULE_NAME= 'dmg_documents';
$TB_NAME= 'mails';
$isCreate =isset($details) && count($details)  > 0 ?false:true;
$count=0;

?>
<div class="tb_container">
    <table class="table" id="details_tb1" data-container="container"  align="center">
        <thead>
        <tr>

            <th>رقم المسلسل</th>
            <th>الجهة المصدرة</th>
            <th style="width: 8%">نوع البريد</th>
            <th>التاريخ</th>
            <th>جهة الحيازة</th>
            <th>موضوع البريد</th>
            <th>مضمون البريد</th>
            <th style="width: 8%">طريقة الارسال</th>
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
                    <input type="text" data-val="true" placeholder="الجهة المصدرة"
                           name="issuer[]" id="txt_issuer_<?=$count?>"
                           class="form-control" >
                </td>
                <td>
                    <select name="mails_type[]" id="dp_mails_type_<?=$count?>" class="form-control sel2">
                        <option></option>

                        <?php foreach($mails_type as $row) :?>
                            <option  value="<?= $row['CON_NO'] ?>">
                                <?php echo $row['CON_NAME']  ?></option>
                        <?php endforeach; ?>
                    </select>

                </td>
                <td >
                    <input type="text" data-val-required="حقل مطلوب" data-type="date"
                           data-date-format="DD/MM/YYYY" name="mail_date[]"
                           id="txt_mail_date_<?=$count?>" class="form-control"
                           >
                </td>
                <td >
                    <input type="text" data-val="true" placeholder="جهة الحيازة"
                           name="possession_side[]" id="txt_possession_side_<?=$count?>"
                           class="form-control" >
                </td>

                <td >
                    <input type="text" data-val="true" placeholder="موضوع البريد"
                           name="mail_subject[]" id="txt_mail_subject_<?=$count?>"
                           class="form-control" >
                </td>
                <td >
                    <input type="text" data-val="true" placeholder="مضمون البريد"
                           name="mail_body[]" id="txt_mail_body_<?=$count?>"
                           class="form-control" >
                </td>
                <td>
                    <select name="transmitter_method[]" id="dp_transmitter_method_<?=$count?>" class="form-control sel2">
                        <option></option>

                        <?php foreach($transmitter_method as $row) :?>
                            <option  value="<?= $row['CON_NO'] ?>">
                                <?php echo $row['CON_NAME']  ?></option>
                        <?php endforeach; ?>
                    </select>
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
                        <input type="text" data-val="true" placeholder="الجهة المصدرة"
                               name="issuer[]" id="txt_issuer_<?=$count?>"
                               class="form-control" value="<?=$row1['ISSUER']?>" >
                    </td>
                    <td>
                        <select name="mails_type[]" id="dp_mails_type_<?=$count?>" class="form-control sel2">
                            <option></option>

                            <?php foreach($mails_type as $row) :?>
                                <option  value="<?= $row['CON_NO'] ?>"
                                    <?PHP if ($row['CON_NO']==((count(@$details)>0)?@$row1['MAILS_TYPE']:0)){ echo " selected"; } ?> ><?php echo $row['CON_NAME']  ?></option>
                            <?php endforeach; ?>
                        </select>

                    </td>
                    <td >
                        <input type="text" data-val-required="حقل مطلوب" data-type="date"
                               data-date-format="DD/MM/YYYY" name="mail_date[]"
                               id="txt_mail_date_<?=$count?>" class="form-control" value="<?=$row1['MAIL_DATE']?>"
                            >
                    </td>
                    <td >
                        <input type="text" data-val="true" placeholder="جهة الحيازة"
                               name="possession_side[]" id="txt_possession_side_<?=$count?>"
                               class="form-control" value="<?=$row1['POSSESSION_SIDE']?>" >
                    </td>

                    <td >
                        <input type="text" data-val="true" placeholder="موضوع البريد"
                               name="mail_subject[]" id="txt_mail_subject_<?=$count?>"
                               class="form-control" value="<?=$row1['MAIL_SUBJECT']?>" >
                    </td>
                    <td >
                        <input type="text" data-val="true" placeholder="مضمون البريد"
                               name="mail_body[]" id="txt_mail_body_<?=$count?>"
                               class="form-control" value="<?=$row1['MAIL_BODY']?>" >
                    </td>
                    <td>


                        <select name="transmitter_method[]" id="dp_transmitter_method_<?=$count?>" class="form-control sel2">
                            <option></option>

                            <?php foreach($transmitter_method as $row) :?>
                                <option  value="<?= $row['CON_NO'] ?>"
                                    <?PHP if ($row['CON_NO']==((count(@$details)>0)?@$row1['TRANSMITTER_METHOD']:0)){ echo " selected"; } ?> ><?php echo $row['CON_NAME']  ?></option>
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
            <th></th>
            <th></th>

        </tr>

        </tfoot>
    </table></div>
