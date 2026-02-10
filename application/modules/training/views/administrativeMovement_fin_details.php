<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 16/07/20
 * Time: 11:11 ص
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
            <th>عدد أيام الاجازات/ بإذن</th>
            <th>عدد أيام الاجازات/ بدون اذن</th>
            <th>ملاحظات</th>
            <th></th>
        </tr>
        </thead>
        <tbody>

            <?php
            $count = -1;
            foreach($details as $row1) {
                ++$count+1;
                $status =  $row1['ADOPT'];
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
                        <input type="text"  readonly value="<?=$row1['ATT_MONTH']?>"
                               name="att_month[]" id="txt_att_month_<?=$count?>"
                               class="form-control" >
                    </td>

                    <td>
                        <input type="text"  value="<?=$row1['FIN_ATTENDEES_DAYS']?>"
                               name="fin_attendees_days[]" id="txt_fin_attendees_days_<?=$count?>"
                               placeholder="عدد الأيام - الحضور"
                               <?=$status != 2 ? "" : "readonly"?>
                               class="form-control" >
                    </td>

                    <td>
                        <input type="text" value="<?=$row1['FIN_ABSENCE_DAYS']?>"
                               name="fin_absence_days[]" id="txt_fin_absence_days_<?=$count?>"
                               placeholder="اجازات باذن"
                               <?=$status != 2 ? "" : "readonly"?>
                               class="form-control" >
                    </td>
                    <td>
                        <input type="text"  name="fin_absenceNoPermission_days[]"
                               value="<?=$row1['FIN_ABSENCE_DAYS_NO_PERMISSION']?>"
                               id="txt_fin_absenceNoPermission_days_<?=$count?>"
                               placeholder="بدون اذن"
                               <?=$status != 2 ? "" : "readonly"?>
                               class="form-control" >
                    </td>

                    <td>
                        <input type="text"  name="fin_notes[]" value="<?=$row1['FIN_NOTES']?>"
                               id="txt_fin_notes_<?=$count?>" placeholder="ملاحظات"
                               <?=$status != 2 ? "" : "readonly"?>
                               class="form-control" >
                    </td>
                    <td>
                        <?php if($status != 2){ ?>
                        <a  onclick="adoptAttendance(<?= $row1['SER'] ?>);" class="btn btn-success btn-xs">اعتماد</a>
                        <?php } ?>
                    </td>

                </tr>

            <?php
            }
        ?>
        </tbody>
        <tfoot>
        </tfoot>
    </table>
</div>
