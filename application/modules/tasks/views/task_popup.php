<?php
$MODULE_NAME='tasks';
$TB_NAME='task';
$create_url= base_url("$MODULE_NAME/$TB_NAME/create");
$delete_url=base_url("$MODULE_NAME/$TB_NAME/delete");
$isCreate= 1;
$back_url='';
$t=time();
$r =rand(10,99);
$c=$t.$r;
?>
<style>
.modal-title{
    color: black ;
}
    .block {
        display: block;
    }

   

    h5, .h5 {
        font-size: 18px;
        color: white;
    }
    .inbox .inbox-nav > li.active > a {
        border-right: 4px solid #ed6b75;
        background: #f1f4f7;
        color: green;
    }

    .inbox .inbox-nav {
        margin: 30px 0;
        padding: 0;
        list-style: none;
        padding: 10px;
        border: 1px solid #e7ecf1;
        border-radius: 4px;
    }

    .btn-sm, .btn-xs {
        padding: 5px 10px;
        font-size: 12px;
        line-height: 1.5;
        margin-top: 10px;
    }

    .glyphicon {
        font-weight: 600;
    }
 
    .ui-datepicker {
width: 17em;

padding: .2em .2em 0;
display: none;
background:#2F99B4;
}
.ui-datepicker .ui-datepicker-title {
margin: 0 2.3em;
line-height: 1.8em;
text-align: center;
color:#FFFFFF;
background:#2F99B4;
}
.ui-datepicker table {
width: 100%;
font-size: .7em;
border-collapse: collapse;
font-family:verdana;
margin: 0 0 .4em;
color:#000000;
background:#9EEFEE;
}
/*This is date TD */
.ui-datepicker td {
 
border: 0;
padding: 1px;
 
 
}
.ui-datepicker td span,
.ui-datepicker td a {
display: block;
padding: .8em;
text-align: right;
text-decoration: underline;
}
</style>
<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
            <li style="display: none"><a  href="<?= $back_url ?>"><i class="icon icon-reply"></i> </a> </li>
            <li title="شرح النظام بالفيديو"><a target="_blank" href="https://gs.gedco.ps/gfc/tutorial/tutorial_show.php?vm=07_task_explain"><i class="glyphicon glyphicon-play-circle"></i> </a> </li>
        </ul>

    </div>

</div>
<div class="container">
<div class="portlet light">
<div class="portlet-body">
<div class="row inbox">
<div class="col-md-2">

   

    <ul class="inbox-nav">

 
        <div class="portlet-title">
          <div class="caption"  data-toggle="collapse" data-target=".todo-project-list-content-tags">
                <span class="caption-subject font-red bold uppercase"><h3> <?= $title ?> </h3></span>
                 <span
                     class="caption-helper visible-sm-inline-block visible-xs-inline-block">اضغط لرؤية القائمة</span>

            </div>
        </div>
        <hr>
        <div class="portlet-body todo-project-list-content todo-project-list-content-tags"
                               style="height: auto;">
                        <div class="todo-project-list" id="ref_taskstatus">
                            <ul class="inbox-nav">
                                <li class="active">
                                    <a href="javascript:;" value="1" class="mytask_btn">
                                        <span class="badge purple">0</span> الوارد </a>
                                </li>
                                
                    <li>
                        <a href="javascript:;" value="2" class="mytaskassign_btn">
                            <span class="badge badge-primary">0</span> الصادر </a>
                    </li>



                </ul>
            </div>
        </div>
        <!---->
</div>

<div class="col-md-10">
    <div class="inbox-loading">
        <!-- BEGIN SAMPLE TABLE PORTLET-->
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-cogs font-green-sharp"></i>
                    <span class="caption-subject font-green-sharp bold uppercase">صندوق الوارد</span>
                </div>
                <div class="tools">
                    <a href="javascript:;" class="collapse">
                    </a>
                    <a href="javascript:;" class="remove">
                    </a>
                </div>
            </div>
            <div class="portlet-body">
            </div>
        </div>
    </div>
    
    <div class="inbox-content">
        <!------------------------------------------------------------>
        <!-- BEGIN TODO CONTENT -->
        <div class="col-md-12">
            <div class="todo-content">
                <div class="portlet light bordered">
                    <!-- PROJECT HEAD -->
                    <div class="portlet-title" id="test">
                        <div class="caption">
                            <div class="col-sm-10">
                            <i style="font-size: 30px; color:#1c757d;" class=""></i>
                            </span>
                            </div>
                        </div>
                    </div>
                       <!-- Modal -->
                       <div id="showmsgrec" class="modal fade" role="dialog"  >
                                <div class="modal-dialog">

                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h6 class="modal-title"></h6>
                                        </div>
                                        <div class="modal-body" id="vi">

                                            <!------------------------------------------->



                                            <!------------------------------------------->

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">اغلاق</button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                         <!-- Modal -->
                                 <!-- Modal -->
                        <div id="show_transaction" class="modal fade" role="dialog" >
                                <div class="modal-dialog">

                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h6 class="modal-title"></h6>
                                        </div>
                                        <div class="modal-body" id="vi_transaction">

                                            <!------------------------------------------->



                                            <!------------------------------------------->

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">اغلاق</button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                           <!-- Modal ---------->

                    <!-- end PROJECT HEAD -->
                    <div class="portlet-body">
                        <input id="first_task" type="hidden" value="">
                        <input id="target_class" type="hidden" value="viewaction">
                        <div class="row">
                            <div id="ref_tasklist">

                            </div>
                            <div id="ref_details">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- END TODO CONTENT -->
        <!------------------------------------------------------------>
    </div>
</div>
<!-- Modal content-->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button"  class="close"  data-dismiss="modal">&times;</button>

               <h4 class="modal-title" id="dropzoneModalTitle">مهمة جديدة</h4>

            </div>
            <div class="modal-body">
                <div class="modal-body">
                    <form class="form-horizontal" id="<?=$TB_NAME?>_form" method="post" action="<?=$create_url?>" role="form" novalidate="novalidate">

                        <!--بيانات المرسلين-->
                        <?php foreach($direct_type_cons as $type) :?>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"> <?=$type['CON_NAME']?> </label>
                                <div class="col-sm-8">
                                    <select multiple id="dp_direct_<?=$type['CON_NO']?>" class="form-control sel2"  >
                                        <?php foreach($emps_direct as $row) :?>
                                            <option value="<?=$row['EMP_NO']?>"><?=$row['EMP_NO'].': '.$row['EMP_NAME']?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <input type="hidden" class="task_directs" data-con="<?=$type['CON_NO']?>" id="h_direct_<?=$type['CON_NO']?>" name="direct_<?=$type['CON_NO']?>" value="" >
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <input type="hidden" id="h_parent_task" name="parent_task" value="<?=$parent_task?>" />
                        <input type="hidden" name="attachment_ser" value="<?php echo $c;?>" />

                        <!--بيانات المرسلين-->                 
                           <br>
                        <!--نوع الرسالة ن-->

                        <div class="form-group" style="display: none; mk2021: hide">
                            <label class="control-label col-md-3"> نوع الرسالة &nbsp;&nbsp; </label>
                            <div class="col-sm-8">
                                <?php foreach($task_direction_type_cons as $row) :?>
                                    <label>
                                        <input type="radio" name="task" id="dp_task_direction_type_<?=$row['CON_NO']?>" <?=($row['CON_NO']==2)?'checked':''?>  value="<?=$row['CON_NO']?>" />
                                        <?=$row['CON_NAME']?>&nbsp;&nbsp;&nbsp;&nbsp;
                                        <br>
                                    </label>
                                <?php endforeach; ?>

                            </div>
                        </div>
                        <!--نوع الرسالة ن-->

                        <div class="form-group">
                            <label class="control-label col-md-3"> درجة الأولوية &nbsp;&nbsp;</label>
                            <div class="col-sm-8">
                                <?php foreach($priority_cons as $row) :?>
                                    <label>
                                        <input type="radio" name="priority" id="priority_cons_<?=$row['CON_NO']?>" <?=($row['CON_NO']==1)?'checked':''?> value="<?=$row['CON_NO']?>" />
                                        <?=$row['CON_NAME']?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>
                                    </label>
                                <?php endforeach; ?>

                            </div>
                        </div>



                     
                        <div class="form-group">

<label  class="col-sm-2 control-label ">تاريخ الانجاز </label>

<div class="col-sm-8">

    <input  type="text"   data-type="date" data-date-format="DD/MM/YYYY" data-val="true" data-val-regex-pattern="<?=date_format_exp()?>" data-val-regex="Error" readonly name="done_date" id="txt_done_date" class="form-control">
</div>

</div>
         
                        <!--عنوان المهمة-->
                        <div class="form-group">
                            <label class="col-sm-2 control-label"> عنوان المهمة 
                            
                            </label>
                            <div class="col-sm-8">
                                <input style="font-size: 16px" type="text" name="task_title" id="txt_task_title" class="form-control">
                            </div>
                        </div>
                        <!--عنوان المهمة-->
                        <br>
                        <!--نص المهمة-->
                        <div class="form-group">
                            <label class="col-sm-2 control-label"> نص المهمة </label>
                            <div class="col-sm-8">
                                <textarea style="font-size: 20px" class="form-control" name="task_text" id="txt_task_text" rows="7"></textarea>
                            </div>
                        </div>
                        <!--نص المهمة-->

               <!------------------------------------------------attachment-------------->
                  
               <div class="row pull-right">
                    <div style="clear: both;">

            <?php echo modules::run('attachments/attachment/index', $c, 'tasks'); ?>
                </div>
                                        </div>
                    <!------------------------------------------------attachment------------------>
                        <!----------------->

                
                        <!--الأزرار-->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">اغلاق</button>
                                <button type="submit"  data-action="submit"  class="btn btn-success ">
                                    <span class="glyphicon glyphicon-send"></span> ارسل
                                </button>
                        </div>
                        <!--الأزرار-->
                    </form>
                    <!---->
                </div>
            </div>

        </div>
    </div>
</div>
</div>
</div>
      




