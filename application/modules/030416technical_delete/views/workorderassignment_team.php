<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 07/07/15
 * Time: 11:36 ص
 */

$count=0;


?>

<div class="tb_container">
    <table class="table" id="teamTbl" data-container="container">
        <thead>
        <tr>
            <th style="width: 2%">#</th>
            <th style="width: 25%">الموظف</th>
            <th style="width: 15%">الوظيفة</th>
            <th style="width: 60%">العمل المكلف به</th>
            <th></th>
        </tr>
        </thead>

        <tbody>

        <?php if(count($details) <= 0) {  // ادخال ?>
            <tr>
                <td><i class="glyphicon glyphicon-sort" /></i></td>
                <td>
                    <input type="hidden" name="t_ser[]" value="0" />
                    <input name="t_worker_job_id[]" class="form-control col-md-4" id="h_txt_worker_job_id_<?=$count?>" />
                    <input readonly  class="form-control  col-md-8"  id="txt_worker_job_id_<?=$count?>" />
                </td>
                <td>


                    <select name="t_worker_job[]" class="form-control" id="h_txt_worker_job_<?=$count?>" />

                    <?php foreach($worker_jobs as $WJRow) :?>
                        <option value="<?= $WJRow['CON_NO'] ?>"><?= $WJRow['CON_NAME'] ?></option>
                    <?php endforeach; ?>
                    </select>

                </td>
                <td>
                    <input name="t_task[]" class="form-control" id="txt_task_<?=$count?>" />
                </td>
                <td data-action="delete"></td>
            </tr>

        <?php
        }else if(count($details) > 0) { // تعديل
            $count = -1;
            foreach($details as $row) {
                ?>
                <tr>
                    <td><?=++$count+1?></td>
                    <td>
                        <input type="hidden" name="t_ser[]" value="<?=$row['SER']?>" />

                        <input name="t_worker_job_id[]" value="<?=$row['WORKER_JOB_ID']?>" class="form-control col-md-4" id="h_txt_worker_job_id_<?=$count?>" />
                        <input readonly value="<?=$row['WORKER_JOB_ID_NAME']?>"   class="form-control  col-md-8"  id="txt_worker_job_id_<?=$count?>" />
                    </td>
                    <td>

                        <select name="t_worker_job[]" class="form-control" id="h_txt_worker_job_<?=$count?>" />

                        <?php foreach($worker_jobs as $WJRow) :?>
                            <option "<?=$row['WORKER_JOB'] == $WJRow['CON_NO'] ? 'selected':'' ?> value="<?= $WJRow['CON_NO'] ?>"><?= $WJRow['CON_NAME'] ?></option>
                        <?php endforeach; ?>
                        </select>

                    </td>
                    <td>
                        <input name="t_task[]"  value="<?=$row['TASK']?>" class="form-control" id="txt_task_<?=$count?>" />
                    </td>
                    <td data-action="delete">
                        <?php if ( $can_edit && HaveAccess(base_url('technical/WorkOrderAssignment/delete_team')) ) : ?>
                            <a href="javascript:;" onclick="javascript:delete_details(this,<?= $row['SER'] ?>,'<?= base_url('technical/WorkOrderAssignment/delete_team') ?>');"><i class="icon icon-trash delete-action"></i> </a>
                        <?php endif; ?>
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
                <?php if (count($details) <=0 || ( (isset($can_edit)?$can_edit:false)  )) { ?>
                    <a onclick="javascript:add_row(this,'input',false);" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>
                <?php } ?>
            </th>
            <th></th>
            <th></th>
        </tr>
        </tfoot>
    </table>
</div>
