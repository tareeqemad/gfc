<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 21/12/15
 * Time: 10:07 ص
 */

$MODULE_NAME= 'technical';
$TB_NAME= 'technical_jobs';
$count=0;
$details= $details_4;
$delete_url_plane = base_url("$MODULE_NAME/$TB_NAME/delete_details_plane");
?>

<div class="tb_container">
    <table class="table" id="details_tb_4" data-container="container">
        <thead>
        <tr>
            <th style="width: 2%">#</th>
            <th style="width: 8%">رقم الإجراء</th>
            <th style="width: 20%">الإجراء </th>
           <th style="width: 8%">الوقت المحدد للتنفيذ بالدقائق</th>
            <th style="width: 2%">حذف</th>
        </tr>
        </thead>

        <tbody>

        <?php if(count($details) <= 0) {  // ادخال ?>
            <tr>
                <td><i class="glyphicon glyphicon-sort" /></i>
                    <input type="hidden" name="p_ser[]" value="0" />

                </td>
                <td>
                    <input name="plane_step_ser[]" value="<?=($count+1)?>" data-val00="true" data-val-required="required" class="form-control"  id="txt_plane_step_ser_<?=$count?>" />
                </td>
                <td>
                    <input name="plane_step[]"  data-val00="true" data-val-required="required" class="form-control"  id="txt_plane_step_<?=$count?>" />
                </td>
                <td>
                    <input name="time_estimated[]" class="form-control" id="txt_time_estimated_<?=$count?>" />
                </td>
                <td></td>
            </tr>

        <?php
        }else if(count($details) > 0) { // تعديل
            $count = -1;
            foreach($details as $row) {
                ?>
                <tr>
                    <td><?=++$count+1?></td>
                    <td>
                        <input type="hidden" name="p_ser[]" value="<?=$row['P_SER']?>" />

                        <input name="plane_step_ser[]" value="<?=$row['PLANE_STEP_SER']?>"  data-val00="true" data-val-required="required" class="form-control"  id="txt_plane_step_ser_<?=$count?>" />
                    </td>

                    <td>
                        <input name="plane_step[]"   value="<?=$row['PLANE_STEP']?>" data-val00="true" data-val-required="required" class="form-control"  id="txt_plane_step_<?=$count?>" />
                    </td>

                    <td>
                        <input name="time_estimated[]" value="<?=$row['TIME_ESTIMATED']?>" class="form-control" id="txt_time_estimated_<?=$count?>" />
                    </td>
                    <td><?php if ( HaveAccess($delete_url_plane)) { ?>
                        <a onclick="javascript:delete_row_det_plane('<?=$row['P_SER']?>');"  href='javascript:;'><i class='glyphicon glyphicon-remove'></i></a></td>
              <?php } ?>
                </tr>
            <?php
            }
        }
        ?>

        </tbody>
        <tfoot>
        <tr>
            <th></th>
            <th>
                <?php if (count($details) <=0 || ( (isset($can_edit)?$can_edit:false) and  $adopt==1 )) { ?>
                    <a onclick="javascript:addRow_4();" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>
                <?php } ?>
            </th>
            <th></th>
            <th id="total_time_estimated"></th>
            <th></th>
        </tr>
        </tfoot>
    </table>
</div>
