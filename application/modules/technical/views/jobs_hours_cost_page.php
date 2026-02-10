<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 18/11/15
 * Time: 12:20 م
 */

?>

<table class="table" id="page_tb" data-container="container">
    <thead>
    <tr>
        <th>#</th>
        <th>المسمى </th>
        <th style="width: 120px">تكلفة الساعة</th>
        <th>البيان</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($get_list as $row) : ?>
    <tr>
        <td><?=$row['WORKER_JOB_ID']?>
            <input name='worker_job_id[]' type='hidden' value='<?=$row['WORKER_JOB_ID']?>' />
            <input name='is_exists[]' type='hidden' value='<?=$row['IS_EXISTS']?>' />
        </td>
        <td><?=$row['WORKER_JOB_NAME']?></td>
        <td><input class='form-control' name='worker_job_hour_cost[]' value='<?=$row['WORKER_JOB_HOUR_COST']?>' /></td>
        <td><input class='form-control' name='note[]' value='<?=$row['NOTE']?>' /></td>
    </tr>
    <?php endforeach;?>
    </tbody>
</table>
