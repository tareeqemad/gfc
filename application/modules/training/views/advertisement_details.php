<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 29/01/20
 * Time: 09:52 ص
 */

$MODULE_NAME = 'training';
$TB_NAME = 'advertisement';

$isCreate =isset($details) && count($details)  > 0 ? false:true;
$count=0;

?>
<div class="tb_container">
    <table class="table" id="details_tb1" data-container="container"  align="center">
        <thead>
        <tr>
            <th style="width: 8%">رقم المسلسل</th>
            <th>المؤهل</th>
            <th>التخصص</th>
        </tr>
        </thead>
        <tbody>
        <?php if(count($details) <= 0) {  // ادخال ?>

            <tr>
                <td>
                    <input type="text" readonly name="" id="txt_ser"
                           class="form-control">
                    <input  type="hidden" name="h_txt_adver_id[]"   id="h_txt_adver_id_<?=$count?>"
                            data-val="false" data-val-required="required" >
                    <input type="hidden" name="seq1[]" id="seq1_id[]" value="0"  />
                    <input type="hidden" name="h_count[]" id="h_count_<?=$count?>"  />
                </td>
                <td>
                    <select name="qif[]" id="dp_qif_<?=$count?>" class="form-control">
                        <option value="0">_____</option>
                        <?php foreach($qif as $row) :?>
                            <option  value="<?= $row['CON_NO'] ?>">
                                <?php echo $row['CON_NAME']  ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <select name="spes[]" id="dp_spes_<?=$count?>" class="form-control">
                        <option value="0">_______</option>
                        <?php foreach($spes as $row) :?>
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
                        <input  type="hidden" name="h_txt_adver_id[]" value="<?=$row1['ADVER_ID']?>"
                                id="h_txt_adver_id_<?=$count?>"
                                data-val="false" data-val-required="required" >

                        <input type="hidden" name="seq1[]" value="<?=$row1['SER']?>"
                               id="seq1_id[]"  />
                        <input type="hidden" name="h_count[]" id="h_count_<?=$count?>"  />
                    </td>

                    <td>
                        <select name="qif[]" id="dp_qif_<?=$count?>" class="form-control">
                            <?php foreach($qif as $row) :?>
                                <option  value="<?= $row['CON_NO'] ?>"
                                    <?PHP if ($row['CON_NO']==((count(@$details)>0)?@$row1['QIF']:0)){ echo " selected"; } ?> ><?php echo $row['CON_NAME']  ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <select name="spes[]" id="dp_spes_<?=$count?>" class="form-control">
                            <?php foreach($spes as $row) :?>
                                <option  value="<?= $row['CON_NO'] ?>"
                                    <?PHP if ($row['CON_NO']==((count(@$details)>0)?@$row1['SPES']:0)){ echo " selected"; } ?> ><?php echo $row['CON_NAME']  ?></option>
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


        </tr>
        <tr>
            <th>
                <a   onclick="javascript:add_row(this,'input:text,input:hidden[name^=seq1],select,select',false);"  href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>


            </th>
            <th></th>
            <th></th>

        </tr>

        </tfoot>
    </table>
</div>
