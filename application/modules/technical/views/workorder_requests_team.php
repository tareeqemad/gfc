<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 07/07/15
 * Time: 11:36 ص
 */

$count = 0;


?>

<div class="tb_container">
    <table class="table" id="teamTbl" data-container="container">
        <thead>
        <tr>
            <th style="width: 20px">#</th>
            <th style="width: 120px">نوع الوظيفة</th>
            <th style="width: 80px">العدد</th>
            <th >دوره في المهمة</th>
            <th style="width: 50px"></th>
        </tr>
        </thead>

        <tbody>

        <?php if (count($details) <= 0) { // ادخال ?>
            <tr>
                <td><i class="glyphicon glyphicon-sort"/></i></td>
                <td>
                    <input type="hidden" name="w_ser[]" value="0"/>
                    <select name="w_worker_job_id[]" class="form-control" id="txt_worker_job_id_<?= $count ?>"/>
                    <option></option>
                    <?php foreach ($worker_jobs as $crow) : ?>
                        <option value="<?= $crow['CON_NO'] ?>"><?= $crow['CON_NAME'] ?></option>
                    <?php endforeach; ?>

                    </select>
                </td>
                <td>
                    <input name="w_worker_count[]" class="form-control" id="txt_worker_count_<?= $count ?>"/>
                </td>
                <td>
                    <input name="w_task[]" class="form-control" id="txt_task_<?= $count ?>"/>
                </td>
                <td data-action="delete"></td>
            </tr>

        <?php
        } else if (count($details) > 0) { // تعديل
            $count = -1;
            foreach ($details as $row) {
                ?>
                <tr>
                    <td><?= ++$count + 1 ?></td>
                    <td>
                        <input type="hidden" name="w_ser[]" value=""/>
                        <select name="w_worker_job_id[]" class="form-control" id="txt_worker_job_id_<?= $count ?>"
                                data-val="<?= $row['WORKER_JOB_ID'] ?>"/>
                        <option></option>

                        <?php foreach ($worker_jobs as $crow) : ?>
                            <option  <?= $row['WORKER_JOB_ID'] == $crow['CON_NO'] ? 'selected' : '' ?>
                                value="<?= $crow['CON_NO'] ?>"><?= $crow['CON_NAME'] ?></option>
                        <?php endforeach; ?>

                        </select>
                    </td>
                    <td>
                        <input name="w_worker_count[]" class="form-control" id="txt_worker_count_<?= $count ?>"
                               value="<?= $row['WORKER_COUNT'] ?>"/>
                    </td>
                    <td>
                        <input name="w_task[]" class="form-control" id="txt_task_<?= $count ?>"
                               value="<?= $row['TASK'] ?>"/>
                    </td>
                    <td data-action="delete">

                        <a href="javascript:;"  onclick="javascript:restInputs($(this).closest('tr'));"><i class="icon icon-trash delete-action"></i> </a>

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

                    <a onclick="javascript:add_row(this,'input',false);" href="javascript:;"><i
                            class="glyphicon glyphicon-plus"></i>جديد</a>

            </th>
            <th></th>
            <th></th>
        </tr>
        </tfoot>
    </table>
</div>
