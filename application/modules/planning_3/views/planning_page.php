<?php
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 01/04/18
 * Time: 01:00 م
 */
$MODULE_NAME= 'planning';
$TB_NAME= 'planning';
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$create_without_cost_url=base_url("$MODULE_NAME/$TB_NAME/create_without_cost");

?>

<!-- END ACCORDION PORTLET-->
<!-- BEGIN ACCORDION PORTLET-->
<div class="portlet box" style="background-color: #117BC2; background-color: #24A1F5 ; font-size: 20px;">
    <div class="portlet-title">
        <div class="caption">

           <i class="glyphicon glyphicon-folder-open text-right"></i>عنوان الخطة : الخطة التشغلية للعام
            <?=$year_paln;?>
            <div style="display: inline-block;position: relative;  left: -30%; /* or right 50% */ text-align: left;">
                <i class="fa fa-gift"></i>نسبة الانجاز : 84.85 %
            </div>

        </div>

        <div class="tools">
            <a href="javascript:;" class="collapse">
            </a>
            <a href="#portlet-config" data-toggle="modal" class="config">
            </a>
            <a href="javascript:;" class="reload">
            </a>
            <a href="javascript:;" class="remove">
            </a>
        </div>
    </div>

    <div class="portlet-body">
        <div class="panel-group accordion" id="accordion_objective_goal">
            <?php foreach($all_objective as $row) :?>
                <div class="panel panel-default">
                    <div class="panel-heading text-right">
                        <h4 class="panel-title">
                            <i class="glyphicon glyphicon-folder-open text-right"></i><a class="accordion-toggle accordion-toggle-styled collapsed" data-toggle="collapse" data-parent="#accordion_objective_goal" href="#collapse_3_<?=$row['ID'];?>">
                                <?= $row['ID_NAME'] ?> </a>

                        </h4>
                    </div>
                    <div id="collapse_3_<?=$row['ID'];?>" class="panel-collapse collapse">
                        <div class="panel-body">


                            <div class="panel-group" style="margin-right:10px; "id="accordion_objective">
                <?php
                $goal_list=modules::run("$MODULE_NAME/$TB_NAME/public_get_goal_p",$row['ID']);
                foreach($goal_list as $row2) :?>
                    <!-- Here we insert another nested accordion -->


                        <div class="panel panel-default">
                            <div class="panel-heading text-right">
                                <h4 class="panel-title">
                                    <i class="glyphicon glyphicon-folder-open text-right"></i> <a class="accordion-toggle accordion-toggle-styled collapsed" data-toggle="collapse" data-parent="#accordion_objective" href="#collapse_goal_<?=$row2['ID'];?>">
                                        <?= $row2['ID_NAME'] ?> </a>
                                    <div style="display: inline-block; float: left;">
                                        <a onclick='javascript:goal_add(<?=$row2['ID']?>);' class="refresh_goal"><i class="glyphicon glyphicon-plus"></i>اضافة هدف تشغيلي</a> </li></a>
                                        <a onclick='javascript:goal_refrech(<?=$row2['ID']?>);' class="refresh_goal"><i class="glyphicon glyphicon-refresh"></i>تحديث</a> </li></a>
                                    </div>
                                </h4>
                            </div>
                            <div id="collapse_goal_<?=$row2['ID'];?>" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <div class="panel-group" style="margin-right:15px; "id="accordion_goal">
                    <?php
                    $goal_t_list=modules::run("$MODULE_NAME/$TB_NAME/public_get_goal_p",$row2['ID']);
                    foreach($goal_t_list as $row3) :?>
                                        <div class="panel panel-default">
                                            <div class="panel-heading text-right">
                                                <h4 class="panel-title">
                                                    <i class="glyphicon glyphicon-folder-open text-right"></i> <a class="accordion-toggle accordion-toggle-styled collapsed" data-toggle="collapse" data-parent="#accordion_goal" href="#collapse_obj_<?=$row3['ID'];?>">
                                                        <?= $row3['ID_NAME'] ?> </a>
                                                    <div style="display: inline-block; float: left;">
                                                        <a onclick='javascript:goal_update(<?=$row2['ID']?>);' class="goal_update"><i class="glyphicon glyphicon-edit"></i>تعديل الهدف تشغيلي</a> </li></a>
                                                        <a onclick='javascript:goal_delete(<?=$row2['ID']?>);' class="goal_delete"><i class="glyphicon glyphicon-trash
"></i> حذف الهدف تشغيلي</a> </li></a>
                                                        <a onclick='javascript:goal_adopt(<?=$row2['ID']?>);' class="goal_adopt"><i class="glyphicon glyphicon-ok

"></i> اعتماد الهدف تشغيلي</a> </li></a>
                                                        <a  href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>اضافة مشروع فني</a>
                                                       <a  href="<?= $create_without_cost_url ?>"><i class="glyphicon glyphicon-plus"></i>اضافة مشروع غير فني</a>

                                                    </div>
                                                </h4>
                                            </div>
                                            <div id="collapse_obj_<?=$row3['ID'];?>" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <div class="panel-group" style="margin-right:20px; "id="accordion_prog">

                                                        <div class="panel panel-default">
                                                            <table class="table"  data-container="container">
                                                                <thead>
                                                                <tr>
                                                                    <th  >#</th>
                                                                    <th>م</th>
                                                                    <th>العام</th>
                                                                    <th>المقر</th>
                                                                    <th>رقم النشاط</th>
                                                                    <th>الغاية</th>
                                                                    <th>الهدف الاستراتيجي</th>
                                                                    <th>الهدف التشغيلي</th>
                                                                    <th>التصنيف</th>
                                                                    <th>نوع النشاط</th>
                                                                    <th>مصدر التمويل</th>
                                                                    <th>اسم المشروع</th>
                                                                    <th>التكلفة</th>
                                                                    <th>مسؤولية المتابعة</th>
                                                                    <th>مسؤولية التنفيذ</th>
                                                                    <th>مدة التنفيذ</th>

                                                                    <th>نهاية المشروع</th>
                                                                    <th>بداية المشروع</th>
                                                                    <th>تحرير</th>







                                                                </tr>
                                                                </thead>

                                                                <tbody>
                                                                <?php
                                                                $goal_t_list=modules::run("$MODULE_NAME/$TB_NAME/public_get_goal_p",$row2['ID']);
                                                                foreach($goal_t_list as $row4) :?>
                                                                    <tr>

                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>
                                                                        <td></td>












                                                                    </tr>
                                                                <?php endforeach;?>

                                                                </tbody>
                                                            </table>
                                                            </div>



                                                        </div>
                                                    </div>
                                                </div>

                                        </div>

                    <?php endforeach; ?>


</div>

                                    </div>
                                </div>

                    </div>

                    <!-- Inner accordion ends here -->

                <?php endforeach; ?>

                            </div>
                            </div>

                    </div>
                </div>
            <?php endforeach; ?>

        </div>
    </div>
</div>
<!-- END ACCORDION PORTLET-->
<!-- BEGIN ACCORDION PORTLET-->
<div class="modal fade" id="planningModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">الهدف التشغيلي</h4>
            </div>
            <form class="form-horizontal" id="planning_from" method="post" action="#" role="form" novalidate="novalidate">
                <div class="modal-body">

                    <div class="form-group">
                        <!--  <label class="col-sm-3 control-label">رقم اللجنة</label> -->
                        <div class="col-sm-2">
                            <input type="hidden"  name="committees_id" readonly id="txt_committees_id" class="form-control ltr">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">اسم الهدف التشغيلي</label>
                        <div class="col-sm-7">
                            <input type="text" data-val="true"  data-val-required="حقل مطلوب" name="committees_name" id="txt_committees_name" class="form-control" maxlength="50">
                            <span class="field-validation-valid" data-valmsg-for="committees_name" data-valmsg-replace="true"></span>
                            <input type="hidden"  value="<?=@$type?>"  data-val-required="حقل مطلوب" name="comittess_type" id="txt_comittess_type" class="form-control" maxlength="50">

                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>

                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/javascript">
   function goal_refrech(id){

       alert('تم التحديث');
   }
   function goal_add(id){

      // alert('تم عملية الاضافة بنجاح');
       $('#planningModal').modal();
   }
   function goal_update(name){

      $('#txt_committees_name').val('here name');
       $('#planningModal').modal();
   }


</script>

