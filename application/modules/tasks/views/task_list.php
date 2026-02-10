<?php
/**
 * Created by PhpStorm.
 * User: abarakat
 * Date: 10/04/18
 * Time: 08:42 ص
 */
?>

<div id="tasklist_MK" class="col-md-5 col-sm-4 ">
    <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 1000px;">

        <?php foreach ($tasks as $row) : ?>
            <div class="todo-tasklist scroller "
                 data-initialized="1">
                <input id="first_task" type="hidden" value="506">

                <div class="todo-tasklist-item todo-tasklist-item-border-blue" onclick="javascript:get_task_details(<?= $row['TASK_NO'] ?>)"
                     style="border-right: #36c6d3 2px solid">
                    <div class="todo-tasklist-item-title"><?= $row['TASK_TITLE'] ?></div>
                    <div class="todo-tasklist-item-text"><?= $row['TASK_TEXT'] ?></div>
                    <div class="todo-tasklist-controls pull-left">
                        <span class="todo-tasklist-date"> <i
                                class="fa fa-calendar"></i> <?= $row['ENTRY_DATE'] ?></span>
                        <span
                            class="todo-tasklist-badge badge badge-roundless badge-danger"><?= $row['PRIORITY_NAME'] ?></span>
                    <span
                        class="todo-tasklist-badge badge badge-roundless badge-success"> <?= $row['STATUS_NAME'] ?></span>
                    </div>
                </div>

            </div>
        <?php endforeach; ?>

    </div>
</div>
