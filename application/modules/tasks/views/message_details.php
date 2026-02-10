<?php

?>


<div class="col-md-10 col-sm-8 task-details" id="taskdet_MK">

<div class="form-horizontal">
<!-- TASK HEAD -->
<div class="form">
    <div class="form-group">
        <div class="col-md-6 col-sm-6">
            <div class="todo-taskbody-user">

                <input type="hidden" value="" id="task_id">
                <span class="todo-username">  </span>
                <span class="caption-helper"> </span>
            </div>
        </div>

        <div class="col-md-6 col-sm-6">
            <div class="todo-taskbody-date pull-left">

                
                    <div class="btn-group">
                        <button class="btn red dropdown-toggle" data-toggle="dropdown"> اتخاذ اجراء <i
                                class="fa fa-angle-down"></i></button>
                        <ul class="dropdown-menu actions">
                            <li>
                                <a href="javascript:task_actions('close');">الاغلاق</a>

                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="javascript:task_actions('delay');">تأجيل</a>

                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="javascript:task_actions('transfer');"> تحويل </a>

                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href=""> مهمة فرعية </a>

                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="javascript:task_actions('extent');"> تمديد </a>

                            </li>

                        </ul>
                    </div>
             

            </div>
        </div>

    </div>
    <!-- END TASK HEAD -->
    <!-- TASK TITLE -->
    <div class="form-group">
        <div class="col-md-12">
            <h1 class="todo-taskbody-tasktitle font-red-sunglo"> </h1>

            <div class="todo-tasklist-controls pull-left">
                <span class="todo-tasklist-date"> <i class="icon icon-calendar"></i>  </span>
                <span
                    class="todo-tasklist-badge badge badge-roundless badge-danger"> </span> <span
                    class="todo-tasklist-badge badge badge-roundless badge-success">  </span>
            </div>
        </div>
    </div>
    <hr>

    <!-- TASK DESC -->
    <div class="form-group">
        <div class="col-md-12">
            <p class="todo-taskbody-taskdesc"> </p>
        </div>
    </div>
    <hr>


    <ul class="task-items">
     
            <li>
                <input type="checkbox" name="task_item[]"
                    
                       value=""> <span> </span>

                <span class="badge badge-success" style="color: #fff;"></span>


            </li>
      

      
            <li>
                <button type="button" onclick="javascript:applySubTaskAction();" class="pull-left btn blue"> &nbsp;
                    اعتماد
                    الاجراءات &nbsp; </button>

            </li>
     
    </ul>


    <hr>
    <!-- END TASK DESC -->
    <!-- TASK DUE DATE -->

    <div class="form-group">
        <div class="col-md-12">
            <div class="input-icon">
                أنجزت في:
                 <span class="todo-tasklist-date">
                                  <i class="fa fa-calendar"></i></span>

            </div>
        </div>
    </div>
    <hr>

    <div style="clear: both;">

      
    </div>
    <!-- TASK TAGS -->
</div>
<div class="tabbable-line" id="actions" style="margin-top: 40px">
    <ul class="nav nav-tabs ">
        <li class="tab1 active">
            <a href="#tab_1" data-toggle="tab"> الإجراءات </a>
        </li>
        <li class="tab2">
            <a href="#tab_2" data-toggle="tab"> التحويلات </a>
        </li>

        <li class="tab4">
            <a href="#tab_4" data-toggle="tab"> المهام الفرعية </a>
        </li>

    </ul>
    <div class="tab-content">
        <div class="tab-pane active " id="tab_1">

            <!-- TASK COMMENT FORM -->
         
                <div class="form-group">
                    <div class="col-md-12">
                        <ul class="media-list">
                            <li class="media">

                                <div class="media-body">
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
                                        </div>

                                        <table id="file_tb">
                                            <tbody id="files_no">
                                            </tbody>
                                        </table>
                                    </form>

                                </div>
                            </li>
                        </ul>


                        <button type="button" onclick="javascript:insertReply();" class="pull-right btn green"> &nbsp;
                            ارسال
                            &nbsp; </button>


                    </div>
                </div> 
            <!-- END TASK COMMENT FORM -->

            <hr>
            <!-- TASK COMMENTS -->
            <div class="form-group">
                <div class="col-md-12">
                    <ul class="media-list">
                        <li class="media">


                           
                                <div class="media-body todo-comment">
                                    <p class="todo-comment-head">
                                        <span class="todo-comment-username"></span> &nbsp;
                                        <span class="todo-comment-date"> </span>

                                       

                                            <span class="badge badge-danger">يطلب تمديد </span>
                      

                                    </p>

                                    <p class="todo-text-color"></p>

                                    <!-- Nested media object -->
                                    <div class="media">
                                        <div class="media-body">
                                        </div>
                                    </div>

                                </div>
                            
                        </li>
                    </ul>
                </div>
            </div>
            <!-- END TASK COMMENTS -->

        </div>
        <div class="tab-pane" id="tab_2">

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
                                    <i class="fa fa-text-height"></i> ملاحظة
                                </th>

                            </tr>
                            </thead>
                            <tbody>

                            
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                           
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- END TASK COMMENTS -->
            <!-- TASK COMMENT FORM -->

            <!-- END TASK COMMENT FORM -->
        </div>
        <div class="tab-pane" id="tab_4">
            <div class="form-group">
                <div class="col-md-12">
                    <div class="table-scrollable">
                        <table class="table table-striped table-bordered table-advance table-hover">
                            <thead>
                            <tr>
                                <th>
                                    رقم المهمة
                                </th>
                                <th>
                                    <i class="fa fa-user"></i> المرسل
                                </th>
                                <th>
                                    <i class="fa fa-user"></i> المكلف
                                </th>
                                <th>
                                    <i class="fa fa-calendar"></i> تاريخ المهمة
                                </th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody id="subtask">
                            
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                           
                            </tbody>
                        </table>
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
            


               


            </div>
            <div class="modal-footer">
                <button onclick="javascript:sendTransfer();" class="btn btn-success">ارسال</button>
                <button class="btn" data-dismiss="modal" aria-hidden="true">اغلاق</button>
            </div>
        </div>
    </div>
</div>
