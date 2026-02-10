<?php
/**
 * Created by PhpStorm.
 * User: abarakat
 * Date: 03/07/17
 * Time: 10:14 ص
 */

$count = 1;

?>

<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>


    </div>

    <div class="form-body">

        <form class="form-form-vertical" id="WorkOrderAssignment_form" method="post"
              action="<?= base_url('settings/CustomerAccountInterface/create') ?>"
              role="form" novalidate="novalidate">
            <div class="modal-body inline_form">

                <div id="myTabContent" class="tab-content">
                    <fieldset class="tab-pane fade in active" id="home">
                        <legend> البيانات</legend>


                        <div class="form-group col-sm-2">
                            <label class="control-label"> الشاشة </label>

                            <div>

                                <select name="interface" class="form-control" id="dp_interface">
                                    <option></option>
                                    <?php foreach ($INTERFACEs as $row) : ?>
                                        <option
                                            value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select>

                            </div>
                        </div>


                        <div class="form-group col-sm-2">
                            <label class="control-label"> نوع الحساب </label>

                            <div>
                                <select name="acccount" class="form-control" id="dp_acccount">
                                    <option></option>
                                    <?php foreach ($ACCCOUNTs as $row) : ?>
                                        <option
                                            value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">


                            <button type="submit" data-action="submit" data-action-submit="save" class="btn btn-danger">
                                حفظ
                                البيانات
                            </button>


                        </div>


                    </fieldset>


                </div>


            </div>
        </form>

        <div>
            <?php if ($message == 'error'): ?>
                <div class="alert alert-danger">
                    فشل في إتمام العملية !
                </div>
            <?php endif; ?>
        </div>
        <div class="tbl_container">
            <table class="table" id="projectTbl" data-container="container">
                <thead>
                <tr>

                    <th style="width: 10px;">#</th>
                    <th>الشاشة</th>
                    <th>نوع الحساب</th>
                    <th style="width: 50px;"></th>
                </tr>
                </thead>
                <tbody>

                <?php foreach ($rows as $row) : ?>
                    <tr>
                        <td><?= $count ?></td>
                        <td><?= $row['INTERFACE_NO_NAME'] ?></td>
                        <td><?= $row['ACCCOUNT_NO_NAME'] ?></td>
                        <td>
                            <a href="<?= base_url("settings/CustomerAccountInterface/delete/{$row['SER']}") ?>"
                               class="btn btn-xs red"> حذف</a>
                        </td
                        <?php $count++ ?>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>
<script>
    if (typeof initFunctions == 'function') {
        initFunctions();
    }


</script>
