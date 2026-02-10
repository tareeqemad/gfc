<style>
</style>
<?php


$MODULE_NAME='tasks';
$TB_NAME='task';

$base_url;
$back_url='';
$base_url;
$countsubtask=0;
?>

<div class="portlet-body">

<div class="row">

   <!-- <div id="test_tasklist">

      <h4> <//?= //$task['TASK_TITLE'] ?></h4>

  
      </div>-->

      </div>

<div class="tabbable-line" id="actions" style="margin-top: 40px">
    <ul class="nav nav-tabs ">
        <li class="tab1 active">
            <a href="#tab_1" data-toggle="tab"> التحويلات والردود والمهام الفرعية </a>
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
                                <th>
                                    #
                                </th>

                                <th>
                                    <i class="fa fa-user"></i> من
                                </th>
                                <th>
                                    <i class="fa fa-user"></i> الى
                                </th>

                                <th>
                                    <i class="fa fa-calendar"></i> التاريخ
                                </th>
                                <th>
                                        عدد الردود
                                </th>
                                <th>
                                        عرض     
                                </th>
                           

                            </tr>
                            </thead>
                            <tbody>

                            <?php foreach ($RedirectTasks as $row) : ?>
                                <tr>
                                    <td><?= $row['RN'] ?></td>
                                    <td><?= $row['FROM_EMP_NO_NAME'] ?></td>
                                    <td><?= $row['DIRECT_EMP_NO_NAME'] ?></td>
                                    <td><?= $row['RECEPTION_DATE'] ?></td>
                                    <td><?= $row['REPLAY_CNT'] ?></td>
                                    <td onclick="javascript:get_replay(<?= $row['TASK_NO'] .','. $row['DIRECT_EMP_NO'] ?>)"><span  class="glyphicon glyphicon-edit" data-toggle="modal"  data-target="#test2" ></span>
                                </tr>
                         
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- END TASK COMMENTS -->
            <!-- TASK COMMENT FORM -->

            <!-- END TASK COMMENT FORM -->
           <?php if ($task['STATUS'] == 1): ?>
                <div class="form-group">
                    <div class="col-md-12">
                        <ul class="media-list">
                            <li class="media">

                             <!--  <div class="media-body">
                                    <form id="REPLYFORM" action="#">
                                        <div id="alert_container1"></div>
                                        <input type="hidden" id="R_TASK_NO" value="506">

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <textarea class="form-control todo-taskbody-taskdesc" rows="4"
                                                          id="reply_text" name="reply_text"
                                                          placeholder="اكتب رد..."></textarea>
                                            </div>
                                        </div>
                                        <div class="radio-list col-md-12">
                                            <div class="form-group">
                                                <label class="radio-inline">
                                                    <input type="checkbox" id="is_extent" value="1"> تمديد التاريخ
                                                </label>

                                            </div>
                                        </div> -->

                                    <!--    <table id="file_tb">
                                            <tbody id="files_no">
                                            </tbody>
                                        </table>
                                    </form>

                                </div>
                            </li>
                        </ul>

                        <hr>
                       <button type="button" onclick="javascript:insertReply();" style="background:#28a4c9;  " class="btn btn-info btn-md">
                            ارسال
                        </button>-->


                    </div>
                </div> <?php endif; ?>
                   <!-- TASK COMMENTS -->
            <div class="form-group" style="display: none;">
                <div class="col-md-12">
                    <ul class="media-list">
                        <li class="media">
                        <?php echo 'عدد الردود الاجمالي' ?>
                        <?php echo count($ReplyTasks); ?>
                            <?php foreach ($ReplyTasks as $row) : ?>
                        
                                <div style="border-color: #81BEF7;" class="media-body todo-comment">
                                    <p class="todo-comment-head">
                                        <span class="todo-comment-username"><?= $row['EMP_NO_NAME'] ?> </span> &nbsp;
                                        <span class="todo-comment-date"><?= $row['REPLY_DATE'] ?> </span>

                                    

                                    </p>


            
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
            </div></div>

                
                    <?php echo 'عدد المهام الفرعية' ?>
                        <?php echo count($subTasks); ?>
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
                                    <td onclick="javascript:get_task_details(<?= $row['TASK_NO'] ?>)" class="glyphicon glyphicon-hand-left"> </td>
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



<div id="test2" class="modal fade" role="dialog" style="z-index: 1600;">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      
      <div class="modal-body" id="show_replay_tr">
      	
        content
      	
      </div>      
    </div>
  </div>
</div>
  