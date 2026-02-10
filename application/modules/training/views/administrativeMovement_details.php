<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 12/02/20
 * Time: 11:02 ص
 */

$MODULE_NAME = 'training';
$TB_NAME = 'administrativeMovement';

$isCreate =isset($details) && count($details)  > 0 ? false:true;
$count=0;

?>
<div class="tb_container">
    <table class="table" id="details_tb1" data-container="container"  align="center">
        <thead>
        <tr>
            <th style="width: 8%">#</th>
            <th>الشهر</th>
            <th>عدد أيام الحضور</th>
            <th>عدد أيام الاجازات / باذن</th>
            <th>عدد أيام الاجازات / بدون اذن</th>
            <th>ملاحظات</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php if(count($details) <= 0) {  ?>

            <tr>
                <td>
                    <input type="text" readonly name="ser[]" id="txt_ser_<?=$count?>"
                           class="form-control">
                    <input  type="hidden" name="h_txt_trainee_ser[]"   id="h_txt_trainee_ser_<?=$count?>"
                            data-val="false" data-val-required="required" >
                    <input type="hidden" name="seq1[]"  value="0"  />
                    <input type="hidden" name="h_count[]" id="h_count_<?=$count?>"  />
                </td>

                <td>
                    <input type="text"  name="att_month[]" id="txt_att_month_<?=$count?>"
                           class="form-control" >
                </td>

                <td>
                    <input type="text"  name="attendees_days[]" id="txt_attendees_days_<?=$count?>" placeholder="عدد الأيام - الحضور"
                           class="form-control" >
                </td>

                <td>
                    <input type="text"  name="absence_days[]" id="txt_absence_days_<?=$count?>" placeholder="عدد الأيام - الاجازات"
                           class="form-control" >
                </td>

                <td>
                    <input type="text"  name="absence_no_permission_days[]" id="txt_absenceNoPermission_days_<?=$count?>" placeholder="بدون اذن"
                           class="form-control" >
                </td>

                <td>
                    <input type="text"  name="notes[]" id="txt_notes" placeholder="ملاحظات"
                           class="form-control" >
                </td>
                <td></td>
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
                        <input type="text" readonly value="<?=$count+1?>" name="ser[]" id="txt_ser_<?=$count?>"
                               class="form-control">
                        <input  type="hidden" name="h_txt_trainee_ser[]"   id="h_txt_trainee_ser_<?=$count?>"
                                data-val="false" data-val-required="required" >
                        <input type="hidden" name="seq1[]"  value="<?=$row1['SER']?>"  />
                        <input type="hidden" name="h_count[]" id="h_count_<?=$count?>"  />
                    </td>

                    <td>
                        <input type="text"  value="<?=$row1['ATT_MONTH']?>"
                               name="att_month[]" id="txt_att_month_<?=$count?>"
                               class="form-control" >
                    </td>

                    <td>
                        <input type="text"  value="<?=$row1['ATTENDEES_DAYS']?>"
                               name="attendees_days[]" id="txt_attendees_days_<?=$count?>" placeholder="عدد الأيام - الحضور"
                               class="form-control" >
                    </td>

                    <td>
                        <input type="text" value="<?=$row1['ABSENCE_DAYS']?>"
                               name="absence_days[]" id="txt_absence_days_<?=$count?>" placeholder="عدد الأيام - الاجازات"
                               class="form-control" >
                    </td>
                    <td>
                        <input type="text"  name="absence_no_permission_days[]" value="<?=$row1['ABSENCE_DAYS_NO_PERMISSION']?>" id="txt_absenceNoPermission_days_<?=$count?>" placeholder="بدون اذن"
                               class="form-control" >
                    </td>

                    <td>
                        <input type="text"  name="notes[]" value="<?=$row1['NOTES']?>"
                               id="txt_notes_<?=$count?>" placeholder="ملاحظات"
                               class="form-control" >
                    </td>

                    <td>
                        <?php if($row1['ADOPT'] == 1 ){ ?>
                            <a  onclick="deleteAttendance(<?= $row1['SER'] ?>);" class="btn btn-warning btn-xs">حذف</a>
                        <?php }elseif($row1['ADOPT'] == 2){
                            echo "<span class='badge badge-2'>معتمد مالياً</span>";
                        } ?>
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
                <a   onclick="javascript:add_row(this,'input:text,input:hidden[name^=seq1],select,select',false);"  href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>
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
