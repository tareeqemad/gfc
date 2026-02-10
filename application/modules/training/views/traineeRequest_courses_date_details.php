<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 26/01/20
 * Time: 11:10 ص
 */


$MODULE_NAME= 'training';
$TB_NAME= 'employeeTraining';

$isCreate =isset($details_date) && count($details_date)  > 0 ?false:true;
$count=0;
$HaveRs = count($result) > 0;
$rs = $HaveRs ? $result[0] : $result;
?>
<div class="tb_container">
    <table class="table" id="details_tb1" data-container="container"  align="center">
        <thead>
        <tr>
            <th style="width: 8%">رقم المسلسل</th>
            <th style="width: 8%">التاريخ</th>
            <th style="width: 15%">ساعة البدء</th>
            <th style="width: 15%">ساعة الانتهاء</th>
            <th style="width: 3%"></th>
        </tr>
        </thead>
        <tbody>
        <?php if(count($details_date) <= 0) {  // ادخال ?>

            <tr>
                <td>
                    <input type="text" readonly name="" id="txt_ser_1"
                           class="form-control">
                    <input  type="hidden" name="h_course_ser[]"   id="h_txt_course_ser_<?=$count?>"
                            value="<?=$rs['SER']?>"
                            data-val="false" data-val-required="required" >
                    <input type="hidden" name="seq1[]" id="seq1_id[]" value="0"  />
                    <input type="hidden" name="h_count[]" id="h_count_<?=$count?>"  />
                </td>

                <td>
                    <input type="text"  data-type="date" data-date-format="DD/MM/YYYY"
                           name="day[]"
                           id="txt_day_<?=$count?>" class="form-control ltr">

                </td>
                <td>
                    <input type="text" data-val="true" placeholder="12:00"
                           name="start_hour[]" id="txt_start_hour_<?=$count?>"
                           class="form-control" >
                </td>
                <td >
                    <input type="text" data-val="true"
                           name="end_hour[]" id="txt_end_hour_<?=$count?>"
                           class="form-control" >
                </td>
                <td >
                </td>
            </tr>



            <?php
            $count++;

        }else if(count($details_date) > 0) { // تعديل
            $count = -1;
            foreach($details_date as $row_date) {
                ++$count+1;

                ?>

                <tr>
                    <td>
                        <input type="text" readonly name="" id="txt_ser_<?=$count?>"
                               class="form-control" value="<?=$count+1?>">
                        <input  type="hidden" name="h_course_ser[]"   id="h_txt_course_ser_<?=$count?>"
                                value="<?=$row_date['COURSE_SER']?>"
                                data-val="false" data-val-required="required" >

                        <input type="hidden" name="seq1[]" value="<?=$row_date['SER']?>"
                               id="seq1_id_<?=$count?>"  />

                        <input type="hidden" name="h_count[]" id="h_count_<?=$count?>"  />
                    </td>

                    <td>
                        <input type="text"  data-type="date" data-date-format="DD/MM/YYYY"
                               name="day[]" value="<?=$row_date['DAY']?>"
                               id="txt_day_<?=$count?>" class="form-control ltr">
                    </td>
                    <td>
                        <input type="text" data-val="true"
                               value="<?=$row_date['START_HOUR']?>"
                               name="start_hour[]" id="txt_start_hour_<?=$count?>"
                               class="form-control" >
                    </td>
                    <td >
                        <input type="text" data-val="true"
                               value="<?=$row_date['END_HOUR']?>"
                               name="end_hour[]" id="txt_end_hour_<?=$count?>"
                               class="form-control" >
                    </td>

                    <td>
                        <a style="background-color: #8edd31; color: #ffffff" class="btn btn-xs"
                           target="_blank" href="<?=base_url("$MODULE_NAME/$TB_NAME/get_attendance/{$row_date['SER']}/{$rs['SER']}" )?>">سجل الحضور والانصراف<i class="glyphicon glyphicon-share"></i></a>
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

        </tr>

        </tfoot>
    </table></div>
