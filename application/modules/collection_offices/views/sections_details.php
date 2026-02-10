<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 16/05/20
 * Time: 09:54 ص
 */


$MODULE_NAME= 'collection_offices';
$TB_NAME= 'Collection_companies';
$isCreate =isset($details) && count($details)  > 0 ?false:true;
$count=0;


?>
<div class="tb_container">
    <table class="table" id="details_tb1" data-container="container"  align="center">
        <thead>
        <tr>
            <th style="width: 3%">رقم المسلسل</th>
            <th style="width: 70%">القطاع</th>
            <th style="width: 3%"></th>
        </tr>
        </thead>
        <tbody>
        <?php if(count($details) <= 0) {  // ادخال ?>

            <tr>
                <td>
                    <input type="text" readonly name="" id="txt_ser_section"
                           class="form-control">
                    <input  type="hidden" name="h_txt_emp_no[]"   id="h_txt_emp_no_<?=$count?>"
                            data-val="false" data-val-required="required" >
                    <input type="hidden" name="seq1[]" id="seq1_id[]" value="0"  />
                    <input type="hidden" name="h_count[]" id="h_count_<?=$count?>"  />
                </td>

                <td>
                    <select name="section[]" id="dp_section_<?=$count?>" class="form-control">
                        <option></option>

                        <?php foreach($section as $row) :?>
                            <option  value="<?= $row['CON_NO'] ?>">
                                <?php echo $row['CON_NAME']  ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>

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
                        <input type="text" readonly name="ser_section" id="txt_ser_section"
                               class="form-control" value="<?=$row1['SER']?>">
                        <input  type="hidden" name="h_txt_emp_no[]" value="<?=$row1['EMP_SER']?>"
                                id="h_txt_emp_no_<?=$count?>"
                                data-val="false" data-val-required="required" >

                        <input type="hidden" name="seq1[]" value="<?=$row1['SER']?>"
                               id="seq1_id[]"  />

                        <input type="hidden" name="h_count[]" id="h_count_<?=$count?>"  />
                    </td>

                    <td>
                        <select name="section[]" id="dp_section_<?=$count?>" class="form-control">
                            <option></option>

                            <?php foreach($section as $row) :?>
                                <option  value="<?= $row['CON_NO'] ?>"
                                    <?php if ($row['CON_NO']==((count(@$details)>0)?@$row1['SECTION']:0)){ echo " selected"; } ?> ><?php echo $row['CON_NAME']  ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <a  class="btn default btn-xs red">
                            <i class="fa fa-close" onclick="javascript:deleteSection(<?=$row1['SER'] ?>)" data-pk="<?=$row1['SER']?>">حذف القطاع</i>
                        </a>
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
                <a   onclick="javascript:add_row(this,'input:text,input:hidden[name^=seq1],select',false);"  href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>
            </th>
            <th></th>
            <th></th>

        </tr>

        </tfoot>
    </table></div>
