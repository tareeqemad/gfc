<?php
$base_url;
 $countsubtask=0;
$report_url = base_url("JsperReport/showreport?sys=tasks");
?>
<style>
    .field_set{
        border-color:#81BEF7;

    }
    #cloo{
        background-color: green;
    }
</style>


<div class="col-md-12 col-sm-8 task-details" id="taskdet_MK">

<div class="form-horizontal">
<!-- TASK HEAD -->
<div class="form">
    <div class="form-group">
        <div class="col-md-6 col-sm-6">
            <div class="todo-taskbody-user">

                <input type="hidden" value="<?= $task['TASK_NO'] ?>" id="task_id">
                <span class="todo-username"> <?= $task['EMP_NO_NAME'] ?> </span>
                <span class="caption-helper"> <?= $task['DEPT_NO_NAME'] ?></span>

            </div>
        </div>

        <div class="col-md-6 col-sm-6">
            <div class="row">
                <div class="todo-taskbody-date pull-left">

                    <?php if ($task['STATUS'] == 1 || $task['STATUS'] == 4 || $task['STATUS'] == 3 || $task['STATUS'] == 2 ): ?>

                    <div class="btn-group">
                        <div class="row pull-right">
                            <div style="clear: both;">
                                <?php echo modules::run('attachments/attachment/index', $task['ATTACHMENT_SER'], 'tasks'); ?>
                            </div>

                            <div style="text-align: center; padding-top: 5px">
                                <i title="طباعة المهمة" style="color: green; cursor: pointer; font-size: x-large" class="glyphicon glyphicon-print" onclick="javascript:print_report(<?=$task['TASK_NO']?>, '<?=report_sn()?>', <?=$task['TASK_NO'].$task['EMP_NO'].$task['ENTRY_USER']?>);" > </i>
                            </div>

                        </div>
                        <br>
                        <button class="btn red dropdown-toggle" data-toggle="dropdown"> اتخاذ اجراء <i
                                class="fa fa-angle-down"></i></button>
                        <ul class="dropdown-menu actions">
                            <li>
                                <a href="javascript:task_actions('close');">اغلاق / تم الانجاز</a>

                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="javascript:task_actions('delay');">تأجيل</a>

                            </li>
                            <li class="divider"></li>
                            <li >
                                <a href="javascript:task_actions('transfer');"> تحويل </a>

                            </li>
                            <li class="divider"></li>
                            <li style="display: none; mk2021: hide" >

                                <a href="javascript:subtask(<?=$task['TASK_NO']?>)"> مهمة فرعية </a>

                            </li>

                            <li>
                                <a href="javascript:task_actions('extent');"> تمديد </a>

                            </li>

                            <li class="divider"></li>
                            <li>
                                <a href="javascript:task_actions('reopen');"> اعادة فتح </a>
                            </li>



                        </ul>
                    </div>
                </div>
                <!------------------------------------------------attachment-------------->
      
                <!------------------------------------------------attachment------------------>


                <?php endif; ?>

            </div>

        </div>

    </div>
    <!-- END TASK HEAD -->
    <!-- TASK TITLE -->
    <div class="form-group">
        <div class="col-md-12">
            <fieldset  class="field_set">
                <legend style="background:#28a4c9;">عنوان المهمة</legend>

                <h1 class="todo-taskbody-tasktitle font-red-sunglo"> <?= $task['TASK_TITLE'] ?></h1>

                <div class="todo-tasklist-controls pull-left">
                    <span class="todo-tasklist-date"> <i class="icon icon-calendar"></i> <?= $task['ENTRY_DATE_2'] ?> </span>

                    <?php if ($task['PRIORITY_NAME'] == "عادية"){ ?>
                        <span class="todo-tasklist-badge badge badge-roundless badge-primary"><?= $task['PRIORITY_NAME'] ?></span>
                    <?php
                    } else { ?>
                        <span class="todo-tasklist-badge badge badge-roundless badge-danger"><?= $task['PRIORITY_NAME'] ?> </span>

                    <?php    }

                    ?>



                    <?php if ($task['STATUS_NAME'] == "جديدة"){ ?>
                        <span class="todo-tasklist-badge badge badge-roundless badge-success"> <?= $task['STATUS_NAME'] ?></span>
                    <?php
                    } elseif ($task['STATUS_NAME'] == "منجزة / مغلقة"){ ?>

                        <span id="cloo" class="todo-tasklist-badge badge badge-roundless badge-info"> <?= $task['STATUS_NAME'] ?></span>
                    <?php }elseif ($task['STATUS_NAME'] == "مؤجلة"){  ?>
                        <span class="todo-tasklist-badge badge badge-roundless badge-warning"> <?= $task['STATUS_NAME'] ?></span>
                    <?php  }elseif ($task['STATUS_NAME'] == "متأخرة"){  ?>
                        <span class="badge badge-danger"> <?= $task['STATUS_NAME'] ?></span>

                    <?php  } ?>
                </div>
                <br><br>
            </fieldset>
        </div>
    </div>
    <hr>

    <!-- TASK DESC -->
    <div class="form-group">

        <div class="col-md-12">
            <div style="direction: ltr; padding-left: 20px">
                <button class="btn-default" onclick="change_font_size( $('#p_task_desc'), '+')">A+</button>
                <button class="btn-default" onclick="change_font_size( $('#p_task_desc'), '-')">A-</button>
            </div>
            <fieldset class="field_set">
                <legend style="background:#28a4c9;">نص المهمة</legend>
                <br>

                <p id="p_task_desc" class="todo-taskbody-taskdesc"> <?= str_replace("\n", "<br/>", $task['TASK_TEXT']) ?></p>

                <br><br></fieldset>

        </div>
    </div>

    <ul>
        <?php foreach ($ItemsTasks as $row): ?>

            <li>
                <input type="checkbox" name="task_item[]"
                    <?= get_curr_user()->emp_no == $row['ITEM_EMP_NO'] ? '' : 'disabled checked' ?>
                    <?= $row['STATUS'] != 1 ? '' : 'checked' ?>
                       value="<?= $row['ITEM_NO'] ?>"> <span> <?= $row['ITEM_DESC'] ?></span>

                <span class="badge badge-success" style="color: #fff;"><?= $row['EMP_NO_NAME'] ?></span>


            </li>
        <?php endforeach; ?>

        <!-- <?php if ($task['STATUS'] == 1): ?>
            <li>
                <button type="button" onclick="javascript:applySubTaskAction();" class="pull-left btn blue"> &nbsp;
                    اعتماد
                    الاجراءات &nbsp; </button>

            </li>
        <?php endif; ?> -->
    </ul>


    <hr>
    <!-- END TASK DESC -->
    <!-- TASK DUE DATE -->

    <?php if ($task['STATUS'] == 2): ?>

    <div  class="form-group">
        <div class="col-md-12">
            <div style="padding-right: 30px" class="input-icon">
                أنجزت في
            <span class="todo-tasklist-date">
                <i class="fa fa-calendar"></i><?= $task['STATUS_DATE_2']?>
            </span>
                بواسطة
            <span class="todo-tasklist-date">
                <i class="fa fa-user"></i><?= $task['STATUS_USER_NAME']?>
            </span>

            </div>
        </div>
    </div>
    <hr>
    <?php endif; ?>

    <!-- TASK TAGS -->
</div>
<div class="tabbable-line" id="actions" style="margin-top: 40px">


    <!-- TASK COMMENT FORM -->

    <!-- END TASK COMMENT FORM -->
    <?php if ($task['STATUS'] == 1): ?>
        <div class="form-group">
            <div class="col-md-12">
                <ul class="media-list">
                    <li class="media">

                        <div class="media-body">
                            <form id="REPLYFORM" action="#">
                                <div id="alert_container1"></div>
                                <input type="hidden" id="R_TASK_NO" value="506">

                                <div class="col-md-8">
                                    <div class="form-group">
                                                <textarea style="margin: 1px 20px" class="form-control todo-taskbody-taskdesc" rows="4"
                                                          id="reply_text" name="reply_text"
                                                          placeholder="اكتب رد..."></textarea>
                                    </div>
                                </div>
                                <div class="form-group col-md-1"></div>
                                <div class="form-group col-md-3">
                                    <select name="replay_to_emps" multiple id="dp_replay_to_emps" class="form-control sel2" placeholder="الرد الى.. [ اختياري | متعدد ]"  >
                                        <option value="<?=$task['EMP_NO']?>"><?=$task['EMP_NO'].': '.$task['EMP_NO_NAME']?></option>
                                        <?php foreach($RedirectTasks as $row) :?>
                                            <option value="<?=$row['DIRECT_EMP_NO']?>"><?=$row['DIRECT_EMP_NO'].': '.$row['DIRECT_EMP_NO_NAME']?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div style="display: none" class="radio-list col-md-12">
                                    <div class="form-group">

                                    </div>
                                </div>

                                <table style="display: none " id="file_tb">
                                    <tbody id="files_no">
                                    </tbody>
                                </table>
                            </form>

                        </div>
                    </li>
                </ul>

                <button type="button" onclick="javascript:insertReply();" style="background:#28a4c9;" class="btn btn-info btn-md">
                    ارسل الرد
                </button>

            </div>
        </div> <?php endif; ?>
    <!-- END TASK COMMENT FORM -->

    <!-- TASK COMMENTS -->
    <div class="form-group">
        <div class="col-md-12">
            <ul class="media-list">
                <li class="media">


                    <?php foreach ($ReplyTasks as $row) : ?>
                        <div style="border-color: #81BEF7;" class="media-body todo-comment">
                            <p class="todo-comment-head">
                                <span class="todo-comment-username"><?= $row['EMP_NO_NAME'] ?> </span> &nbsp;
                                <span class="todo-comment-date"><?= $row['REPLY_DATE_2'] ?> </span>

                            </p>
                            <?php
                            if( strlen( $row['REPLAY_TO_EMPS_NAMES']) > 1 ){
                                echo "<p> <span>الى </span> <span style='color: #1C728C'> {$row['REPLAY_TO_EMPS_NAMES']} </span> </p>";
                            }
                            ?>

                            <p  class="todo-text-color"><?= str_replace("\n", "<br/>", $row['REPLY_TEXT']) ?></p>

                            <!-- Nested media object -->
                            <div class="media">
                                <div class="media-body">
                                </div>
                            </div>

                        </div>
                    <?php endforeach; ?>
                </li>
            </ul>
        </div>
    </div>

    <hr>

    <ul class="nav nav-tabs ">
        <li class="tab1 active">
            <a href="#tab_1" data-toggle="tab"> التحويلات </a>
        </li>

        <li style="display: none; mk2021: hide" class="tab4">
            <a href="#tab_4" data-toggle="tab"> المهام الفرعية </a>
        </li>

    </ul>
    <div class="tab-content">
        <div class="tab-pane active " id="tab_1">



            <!-- TASK COMMENTS -->
            <div class="form-group">
                <div class="col-md-12">
                    <div class="table-scrollable">
                        <table class="table table-striped table-bordered table-advance table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>الاجراء</th>

                                <th>
                                    <i class="fa fa-user"></i> من
                                </th>
                                <th>
                                     التحويل
                                </th>
                                <th>
                                    <i class="fa fa-user"></i> الى
                                </th>

                                <th>
                                    <i class="fa fa-calendar"></i> التاريخ
                                </th>

                            </tr>
                            </thead>
                            <tbody>

                            <?php foreach ($RedirectTasks as $row) : ?>
                                <tr>
                                    <td><?= $row['RN'] ?></td>
                                    <td><?= $row['SEND_TRANS_NAME'] ?></td>
                                    <td><?= $row['FROM_EMP_NO_NAME'] ?></td>
                                    <td><?= $row['DIRECT_TYPE_NAME'] ?></td>
                                    <td><?= $row['DIRECT_EMP_NO_NAME'] ?></td>
                                    <td><?= $row['RECEPTION_DATE_2'] ?></td>

                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- END TASK COMMENTS -->

        </div>

        <div class="tab-pane" id="tab_4">
            <div class="form-group">
                <div class="col-md-12">
                    <div class="table-scrollable">

                        <?php foreach ($subTasks as $row) :
                            $countsubtask++;
                        endforeach;
                        if ($countsubtask > 0){ ?>

                        <table class="table table-striped table-bordered table-advance table-hover">
                            <thead>
                            <tr>
                                <th>
                                    رقم المهمة
                                </th>
                              
                              <!--  <th>
                                    <i class="fa fa-user"></i> المكلف
                                </th>-->
                                <th>
                                    <i class="fa fa-user"></i> نص الرسالة
                                </th>
                                <th>
                                    <i class="fa fa-user"></i> المرسل
                                </th>
                                <th>
                                    <i class="fa fa-calendar"></i> تاريخ الارسال
                                </th>
                            <th>
                                <i class="glyphicon glyphicon-hand-left"></i> عرض
                                </th>
                            </tr>
                            </thead>
                            <tbody id="subtask">
                            <?php foreach ($subTasks as $row) : ?>
                        
                                <tr>
                                <td onclick="javascript:get_task_details(<?= $row['TASK_NO'] ?>)"><?= $row['TASK_NO'] ?></td>
                                    <td><?= $row['TASK_TITLE'] ?></td>
                                    <td><?= $row['EMP_NAME'] ?></td>
                                    <td><?= $row['ENTRY_DATE'] ?></td>
                                    <td onclick="javascript:get_task_details(<?= $row['TASK_NO'] ?>)" class="glyphicon glyphicon-hand-left "> </td>

                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>

                    <?php  }
                        else
                        echo "<br><br> لا يوجد مهام فرعية ..";?>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
</div>

</div>

<!-- Modal extent date -->
<div class="modal fade" id="extentModel">
    <div class="modal-dialog" style="width: 500px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="myModalLabel">تاريخ التمديد </h3>
            </div>
            <div class="modal-body">
                <input type="text"
                       data-val="true"
                       data-type="date"
                       data-date-format="DD/MM/YYYY"
                       data-val-regex="التاريخ غير صحيح!"
                       data-val-regex-pattern="^(0[1-9]{1}|(1|2)\d{1}|(30|31))\/(0[1-9]{1}|1[0-2]{1})\/\d{4}"
                       data-val-required="حقل مطلوب"
                       id="extent_date"
                       readonly
                       class="form-control valid">
            </div>
            <div class="modal-footer">
                <button onclick="javascript:sendExtent();" class="btn btn-success">اعتماد</button>
                <button class="btn" data-dismiss="modal" aria-hidden="true">اغلاق</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal extent date -->
<div class="modal fade" id="transferModel">
    <div class="modal-dialog" style="width: 70%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="myModalLabel"> تحويل المهمة </h3>
            </div>
            <div class="modal-body">
                <?php foreach ($direct_type_cons as $type) : ?>

                    <div class="row form-group">
                        <label class="col-sm-2 control-label"> <?= $type['CON_NAME'] ?> </label>

                        <div class="col-sm-8">
                            <select multiple id="dp_direct_<?= $type['CON_NO'] ?>" class="form-control sel2"
                                    data-con="<?= $type['CON_NO'] ?>"
                            <?php foreach ($emps_direct as $row) : ?>
                                <option
                                    value="<?= $row['EMP_NO'] ?>"><?= $row['EMP_NO'] . ': ' . $row['EMP_NAME'] ?></option>
                            <?php endforeach; ?>
                            </select>
                            <input type="hidden" class="task_directs" data-con="<?= $type['CON_NO'] ?>"
                                   id="h_direct_<?= $type['CON_NO'] ?>" name="direct_<?= $type['CON_NO'] ?>" value=""/>
                        </div>
                    </div>

                <?php endforeach; ?>

                <!-- مؤشرات القياس -->
                <div class="tb_container" style="display: none; mk2021: hide" >
                    <table class="table" id="indicator_measurement" data-container="container">
                        <thead>
                        <tr>
                            <th style="width: 25%">الموظف</th>
                            <th style="width: 25%">التصنيف</th>
                            <th style="width: 40%">البند</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                <select name="item_emp_no[]" id="dp_item_emp_no_0" class="form-control item_emps" >
                                    <option value="">_________</option>
                                    <?php foreach($emps_direct as $row) :?>
                                        <option value="<?=$row['EMP_NO']?>"><?=$row['EMP_NO'].': '.$row['EMP_NAME']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>
                                <select name="cat_no[]" class="form-control" id="dp_cat_no_0" >
                                    <option value="">_________</option>
                                    <?php foreach($cats_list as $row) :?>
                                        <option value="<?=$row['CAT_NO']?>" ><?=$row['CAT_NAME']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>
                                <input name="item_desc[]" class="form-control" id="txt_item_desc_0" />
                            </td>
                        </tr>
                        </tbody>
                        <tfoot>
                        <tr>
                            <td> <a onclick="javascript:add_row(this,'input',false);item_emps_select();" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a> </td>
                            <td colspan="2"></td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                <!-- مؤشرات القياس -->


            </div>
            <div class="modal-footer">
                <button onclick="javascript:sendTransfer();" class="btn btn-success">ارسال</button>
                <button class="btn" data-dismiss="modal" aria-hidden="true">اغلاق</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">

    function change_font_size(obj, size){
        var fontSize = obj.css('font-size');
        if(size=='+'){
            var newFontSize = parseInt(fontSize)+1;
        }else if(size=='-'){
            var newFontSize = parseInt(fontSize)-1;
        }
        obj.css('font-size', newFontSize+'px');
    }

    function print_report(task_no_, sn_, key_){
        var rep_url = '<?=$report_url?>&report_type=pdf'+'&report=task_details&p_task_no='+task_no_+'&p_sn='+sn_+'&p_key='+key_;
        _showReport(rep_url);
    }

</script>