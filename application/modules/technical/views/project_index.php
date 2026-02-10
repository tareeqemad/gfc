<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 5/28/2017
 * Time: 9:14 AM
 */

?>

<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

    </div>

    <div class="form-body">
        <form method="post">
            <fieldset>
                <legend>بحـث</legend>

                <div class="modal-body inline_form">


                    <div class="form-group  col-sm-1">
                        <label class="control-label">رقم المشروع </label>

                        <div>
                            <input type="text" name="project_tec" value="<?= $project_tec ?>" class="form-control"/>
                        </div>
                    </div>

                    <div class="form-group  col-sm-1">
                        <label class="control-label">رقم الطلب </label>

                        <div>
                            <input type="text" name="request_id" value="<?= $request_id ?>" class="form-control"/>
                        </div>
                    </div>

                    <div class="form-group  col-sm-1">
                        <label class="control-label">رقم امر العمل </label>

                        <div>
                            <input type="text" id="work_order_id" value="<?= $work_order_id ?>" class="form-control"/>
                        </div>
                    </div>

                    <div class="form-group  col-sm-1">
                        <label class="control-label">رقم التكليف </label>

                        <div>
                            <input type="text" id="ass_id" value="<?= $ass_id ?>" class="form-control"/>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">

                    <button type="submit" class="btn btn-success">بحث</button>

                    <button type="button" onclick="javascript:clearForm_any($('fieldset'));do_search();"
                            class="btn btn-default">تفريغ الحقول
                    </button>

                </div>
            </fieldset>
        </form>
        <div id="msg_container">

            <table class="table table-striped table-bordered table-hover" id="tbl">
                <thead>
                <tr>

                    <th style="width:15%">رقم المشروع</th>
                    <th> العنوان</th>
                    <th style="width:15%">رقم الطلب</th>
                    <th style="width:15%"> رقم امر العمل</th>
                    <th style="width:15%"> رقم التكليف</th>
                    <th style="width:15%"> المدخل</th>

                </tr>
                </thead>
                <tbody>

                <?php foreach ($rows as $row) : ?>
                    <tr>
                        <td><?= $row['PROJECT_SERIAL'] ?></td>
                        <td><?= $row['WORKORDER_TITLE'] ?></td>
                        <td><a href="<?= base_url('technical/requests/get/'.$row['RREQUEST_ID']) ?>" target="_blank" ><?= $row['REQUEST_CODE'] ?></a></td>
                        <td><a href="<?= base_url('technical/WorkOrder/get/'.$row['RREQUEST_ID']) ?>" target="_blank"><?= $row['WORK_ORDER_CODE'] ?></a></td>
                        <td><a href="<?= base_url('technical/WorkOrderAssignment/get/'.$row['RREQUEST_ID']) ?>" target="_blank"><?= $row['WORK_ASSIGNMENT_CODE'] ?></a></td>
                        <td><?= $row['ENTRY_USER_NAME'] ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>

            </table>
        </div>

    </div>

</div>



