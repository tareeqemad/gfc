<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 07/07/15
 * Time: 11:36 ص
 */

$count=0;
$details= $details_1;

?>

<div class="tb_container">
    <table class="table" id="details_tb_1" data-container="container">
        <thead>
        <tr>
            <th style="width: 2%">#</th>
            <th style="width: 15%">نوع الوظيفة</th>
            <th style="width: 15%">العدد</th>
            <th style="width: 60%">دوره في المهمة</th>
        </tr>
        </thead>

        <tbody>

        <?php if(count($details) <= 0) {  // ادخال ?>
            <tr>
                <td><i class="glyphicon glyphicon-sort" /></i></td>
                <td>
                    <input type="hidden" name="w_ser[]" value="0" />
                    <select name="worker_job_id[]" class="form-control" id="txt_worker_job_id<?=$count?>" /><option></option></select>
                </td>
                <td>
                    <input name="worker_count[]" class="form-control" id="txt_worker_count<?=$count?>" />
                </td>
                <td>
                    <input name="task[]" class="form-control" id="txt_task<?=$count?>" />
                </td>
            </tr>

        <?php
        }else if(count($details) > 0) { // تعديل
            $count = -1;
            foreach($details as $row) {
                ?>
                <tr>
                    <td><?=++$count+1?></td>
                    <td>
                        <input type="hidden" name="w_ser[]" value="<?=$row['W_SER']?>" />
                        <select name="worker_job_id[]" class="form-control" id="txt_worker_job_id<?=$count?>" data-val="<?=$row['WORKER_JOB_ID']?>" /><option></option></select>
                    </td>
                    <td>
                        <input name="worker_count[]" class="form-control" id="txt_worker_count<?=$count?>" value="<?=$row['WORKER_COUNT']?>" />
                    </td>
                    <td>
                        <input name="task[]" class="form-control" id="txt_task<?=$count?>" value="<?=$row['TASK']?>" />
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
            <th>
                <?php if (count($details) <=0 || ( (isset($can_edit)?$can_edit:false) and  $adopt==1 )) { ?>
                    <a onclick="javascript:addRow_1();" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>
                <?php } ?>
            </th>
            <th></th>
            <th></th>
        </tr>
        </tfoot>
    </table>
</div>
