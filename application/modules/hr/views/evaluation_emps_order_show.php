<?php
/**
 * Created by PhpStorm.
 * User: mtaqia
 * Date: 13/07/16
 * Time: 12:50 م
 * **********************
 */

$MODULE_NAME='hr';
$TB_NAME='evaluation_emp_order';

$edit_url= base_url("$MODULE_NAME/$TB_NAME/edit");
$get_ask_mark_url=base_url("$MODULE_NAME/$TB_NAME/get_ask_mark");
$get_url= base_url("$MODULE_NAME/$TB_NAME/get");
$objection_url= base_url("$MODULE_NAME/$TB_NAME/objec_req");
$audit_url= base_url("$MODULE_NAME/$TB_NAME/edit_audit_mark");
$edit_objec_mark= base_url("$MODULE_NAME/$TB_NAME/edit_objec_mark");
$adopt_url= base_url("$MODULE_NAME/$TB_NAME/adopt");
$cancel_adopt_url= base_url("$MODULE_NAME/$TB_NAME/cancel_adopt");
$adopt_admin_manager_url= base_url("$MODULE_NAME/$TB_NAME/adopt_admin_manager");
$get_courses_url  = base_url("$MODULE_NAME/$TB_NAME/get_courses");
$save_courses_url  = base_url("$MODULE_NAME/$TB_NAME/save_courses");
$ev_start_url  = base_url("$MODULE_NAME/evaluation_emps_start/index");

// range style depend on brwoser
if($browser=='Firefox')
    $range_class='form-control';
else
    $range_class='';

if(count($result_details)==0 and $action=='obj' )
    die();

//select first element from array
$master_res = $result[0];
$details_res = $result_details[0];
$res_order = $result_order[0];
$count = 0;

?>

<style type="text/css">
    .changeBorder {border-color: #ff0000; border-width: 2px;}
    .changeBorder::-webkit-input-placeholder {color:#b3b3b3;} /* chrome */
    .changeBorder::-moz-placeholder {color:#b3b3b3;}          /* firefox */
    .glyphicon-thumbs-down {font-size: 20px;}
    .glyphicon-check,.glyphicon-list-alt{font-size: 14px;}
</style>

<div class="row">
    <div class="toolbar">
        <div class="caption"><i class="glyphicon glyphicon-check"></i><?= $title.' / '.$master_res['EMP_NO_NAME'].' / '.$details_res['FORM_ID_NAME']; ?></div>
        <div class="caption" style="width: 50%">
            <?php if($master_res['ADOPT']==2 and $master_res['FORM_TYPE']==1): ?>
                <i class="glyphicon glyphicon-align-right"></i>درجة المسؤول المباشر : <span><?=$master_res['PRIMARY_MARK']; ?> </span>
                <i class="glyphicon glyphicon-list-alt"></i>الدرجة الاساسية :<span><?=$master_res['FINAL_MARK_BEFORE_AUDIT']; ?>  </span>
                <i class="glyphicon glyphicon-list-alt"></i>اخر درجة معتمدة :<span><?=$master_res['F_MARK']; ?> | التقدير : <?=$master_res['F_MARK_DEGREE_NAME']; ?></span>
            <?php endif; ?>
        </div>
        <ul>
            <?php if( HaveAccess($get_courses_url) and $master_res['ADOPT']==2 and $master_res['FORM_TYPE']==1 ): ?><li><a  onclick="javascript:get_courses();" href="javascript:;"><i class="glyphicon glyphicon-list"></i>الدورات التدريبية</a> </li> <?php endif;?>
             <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
        </ul>
    </div><!-- /.toolbar -->
</div>

<div class="form-body">
    <div id="msg_container"></div>
        <div id="container">
            <form class="form-vertical" id="<?=$TB_NAME?>_form" method="post" action="<?=($action=='obj')?$edit_objec_mark:(($action=='audit')?$audit_url:$edit_url)?>" role="form" novalidate="novalidate">
                <div class="modal-body inline_form">
                    <input type="hidden" name="old_mark_calc" value="<?=$master_res['FINAL_MARK_BEFORE_AUDIT']?>" />
                    <input type="hidden" name="evaluation_order_serial" value="<?=$master_res['EVALUATION_ORDER_SERIAL']?>" />
                    <?=($master_res['EXCELLENT_EDEPENDENCY']!= NULL)?'<fieldset><legend>مبررات الحصول على تقدير امتياز من قبل المسؤول المباشر</legend>'.$master_res['EXCELLENT_EDEPENDENCY'].'</fieldset>':''?>
                    <?=($master_res['MANAGER_NOTE']!= NULL)?'<fieldset><legend>تقييم وصفي (ملاحظات المدير المباشر)</legend>'.$master_res['MANAGER_NOTE'].'</fieldset>':''?>
                    <?=($master_res['EMP_TRANSFER']!= NULL)?'<fieldset><legend>توصية المدير المباشر بنقل الموظف</legend>'.$master_res['EMP_TRANSFER'].'</fieldset>':''?>
                    <div class="tbl_container">
                        <?php foreach($result_details as $row) :
                            if($count == 0 or $result_details[$count]['EFA_ID']!=$result_details[$count-1]['EFA_ID'] ){
                                $cnt=1;
                            ?>
                            <fieldset>
                                <legend><?=(0 and $row['EFA_ID_NAME']==' ') ? 'المحور الإشرافي' : $row['EFA_ID_NAME'] ?></legend>
                                <div id="container">
                                    <div class="tbl_container">
                                        <table class="table" id="page_tb" data-container="container">
                                            <thead>
                                            <tr>
                                                <th style="width: 3%">#</th>
                                                <th style="width: 20%">السؤال</th>
                                                <th style="width: 20%">التقييم</th>
                                                <th style="width: 20%">الوصف</th>
                                                <?=($action=='obj')?"<th style='width: 20%'>سبب التظلم</th>":''?>
                                                <?=($action=='me' and $res_order['LEVEL_ACTIVE']>=4)?"<th style='width: 4%'>تظلم</th>":''?>
                                                <?=($action=='obj' or $action=='audit')?"<th style='width: 20%'>مبررات التعديل</th>":''?>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php } ?>
                                                <tr data-marks='<?=($row['EVAL_FROM_TYPE']==1)? modules::run($get_ask_mark_url, $row['ELEMENT_ID']):''?>'>
                                                    <td><?=$cnt?>
                                                        <input type="hidden" name="ser[]" value="<?=$row['SER']?>" />
                                                        <input type="hidden" name="element_id[]" value="<?=$row['ELEMENT_ID']?>" />
                                                        <input type="hidden" id="txt_old_mark" name="old_mark[]" value="<?=$row['MARK']?>" />
                                                        <input type="hidden" id="txt_audit_mark" name="audit_mark[]" value="<?=$row['AUDIT_MARK']?>" />
                                                    </td>
                                                    <td style="text-align: right"><?=$row['ELEMENT_ID_NAME']?></td>
                                                    <td>
                                                        <input style="float: right; width: 80% " <?=($action=='me')?'disabled':'' ?> class="<?=$range_class?>" id="txt_new_mark" name="mark[]" type="range" min="0" max="100" step="1" value="<?=$row['MARK']?>" />
                                                        <input style="float: right; width: 16%; margin-right: 2%; text-align: center" class="form-control" readonly type="text" value="<?=$row['MARK']?>" />
                                                    </td>
                                                    <td><?=($row['EVAL_FROM_TYPE']==1) ? "<input class='form-control mark_desc' readonly type='text' value='' />" : ''?></td>
                                                    <?=($action=='obj') ? "<td><input class='form-control' readonly type='hidden' value='".$row['OBJECTION_HINT']."' /><div class='form-control'>".$row['OBJECTION_HINT']."</div></td>" : ''?>
                                                    <?php if($action=='me' and $res_order['LEVEL_ACTIVE']>=4){
                                                        if($row['OBJECTION']==1){
                                                    ?>
                                                    <td><a id="objection_url_icon_<?=$row['SER']?>" onclick="javascript:objec_req('<?=$row['SER']?>','<?=$row['OBJECTION']?>','<?=$row['OBJECTION_HINT']?>','<?=$row['ELEMENT_ID_NAME']?>','<?=$row['MARK_BEFORE_OBJ']?>');" href='javascript:;'><i style="color:red" class="glyphicon glyphicon-thumbs-down"></a></td>
                                                    <?php }else if($res_order['LEVEL_ACTIVE']==4){ ?>
                                                    <td><a id="objection_url_icon_<?=$row['SER']?>" onclick="javascript:objec_req('<?=$row['SER']?>','<?=$row['OBJECTION']?>','<?=$row['OBJECTION_HINT']?>','<?=$row['ELEMENT_ID_NAME']?>','<?=$row['MARK_BEFORE_OBJ']?>');" href='javascript:;'><i class="glyphicon glyphicon-thumbs-down"></a></td>
                                                    <?php } else echo '<td></td>'; } ?>
                                                    <?=($action=='obj') ? "<td><input class='form-control' id='txt_gr_objection_hint' name='gr_objection_hint[]' type='text' value='".$row['GR_OBJECTION_HINT']."' /></td>" : ''?>
                                                    <?=($action=='audit') ? "<td><input class='form-control' id='txt_audit_hint' name='audit_hint[]' type='text' value='".$row['AUDIT_HINT']."' /></td>" : ''?>
                                                </tr>

                            <?php $count++; $cnt++;
                            if( $count ==count($result_details) or $result_details[$count]['EFA_ID']!=$result_details[$count-1]['EFA_ID'] ){ ?>
                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </fieldset>
                            <?php } endforeach;?>

                        <?php if(count($brothers_details)>0 ) {
                        echo '<input type="hidden" name="eval_extra_order_serial[]" value="'.$brothers_details[0]['EVALUATION_ORDER_SERIAL'].'" />';
                        foreach($brothers_details as $key => $row) :
                        if($key==0 ){
                        $cnt=1;
                        ?>
                        <fieldset>
                            <legend><?=$row['EFA_ID_NAME']?></legend>
                            <div id="container">
                                <div class="tbl_container">
                                    <table class="table" id="page_tb" data-container="container">
                                        <thead>
                                        <tr>
                                            <th style="width: 3%">#</th>
                                            <th style="width: 20%">السؤال</th>
                                            <th style="width: 20%">التقييم</th>
                                            <th style="width: 20%">الوصف</th>
                                            <?=($action=='obj')?"<th style='width: 20%'>سبب التظلم</th>":''?>
                                            <?=($action=='me' and $res_order['LEVEL_ACTIVE']>=4)?"<th style='width: 4%'>تظلم</th>":''?>
                                            <?=($action=='obj' or $action=='audit')?"<th style='width: 20%'>مبررات التعديل</th>":''?>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php } ?>
                                        <tr data-marks=''>
                                            <td><?=$cnt?>
                                                <input type="hidden" name="ser[]" value="<?=$row['SER']?>" />
                                                <input type="hidden" name="element_id[]" value="<?=$row['ELEMENT_ID']?>" />
                                                <input type="hidden" id="txt_old_mark" name="old_mark[]" value="<?=$row['MARK']?>" />
                                                <input type="hidden" id="txt_audit_mark" name="audit_mark[]" value="<?=$row['AUDIT_MARK']?>" />
                                            </td>
                                            <td style="text-align: right"><?=$row['ELEMENT_ID_NAME']?></td>
                                            <td>
                                                <input style="float: right; width: 85%" <?=($action=='me')?'disabled':'' ?> class="<?=$range_class?>" id="txt_new_mark" name="mark[]" type="range" min="0" max="100" step="1" value="<?=$row['MARK']?>" />
                                                <input style="float: right; width: 11%; margin-right: 2%; text-align: center" class="form-control" readonly type="text" value="<?=$row['MARK']?>" />
                                            </td>
                                            <td><?=(0) ? "<input class='form-control mark_desc' readonly type='text' value='' />" : ''?></td>
                                            <?=($action=='obj') ? "<td><input class='form-control' readonly type='text' value='".$row['OBJECTION_HINT']."' /></td>" : ''?>
                                                <?php if($action=='me' and $res_order['LEVEL_ACTIVE']>=4){
                                                if($row['OBJECTION']==1){
                                                ?>
                                                    <td><a id="objection_url_icon_<?=str_replace(',','-',$row['SER'])?>" onclick="javascript:objec_req('<?=$row['SER']?>','<?=$row['OBJECTION']?>','<?=$row['OBJECTION_HINT']?>','<?=$row['ELEMENT_ID_NAME']?>','<?=$row['MARK_BEFORE_OBJ']?>');" href='javascript:;'><i style="color:red" class="glyphicon glyphicon-thumbs-down"></a></td>
                                                <?php }else if($res_order['LEVEL_ACTIVE']==4){ ?>
                                                    <td><a id="objection_url_icon_<?=str_replace(',','-',$row['SER'])?>" onclick="javascript:objec_req('<?=$row['SER']?>','<?=$row['OBJECTION']?>','<?=$row['OBJECTION_HINT']?>','<?=$row['ELEMENT_ID_NAME']?>','<?=$row['MARK_BEFORE_OBJ']?>');" href='javascript:;'><i class="glyphicon glyphicon-thumbs-down"></a></td>
                                                <?php } else echo '<td></td>'; } ?>
                                                    <?=($action=='obj') ? "<td><input class='form-control' id='txt_gr_objection_hint' name='gr_objection_hint[]' type='text' value='".$row['GR_OBJECTION_HINT']."' /></td>" : ''?>
                                                    <?=($action=='audit') ? "<td><input class='form-control' id='txt_audit_hint' name='audit_hint[]' type='text' value='".$row['AUDIT_HINT']."' /></td>" : ''?>
                                        </tr>

                                        <?php $count++; $cnt++;
                                        if( $key+1==count($brothers_details) ){ ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </fieldset>
                        <?php } endforeach; } ?>

                        <?php if(count($grandson_details)>0 ) {
                            echo '<input type="hidden" name="eval_extra_order_serial[]" value="'.$grandson_details[0]['EVALUATION_ORDER_SERIAL'].'" />';
                            foreach($grandson_details as $key => $row) :
                                if($key==0 ){
                                    $cnt=1;
                                    ?>
                                    <fieldset>
                                    <legend><?=$row['EFA_ID_NAME']?></legend>
                                    <div id="container">
                                    <div class="tbl_container">
                                    <table class="table" id="page_tb" data-container="container">
                                    <thead>
                                    <tr>
                                        <th style="width: 3%">#</th>
                                        <th style="width: 20%">السؤال</th>
                                        <th style="width: 20%">التقييم</th>
                                        <th style="width: 20%">الوصف</th>
                                        <?=($action=='obj')?"<th style='width: 20%'>سبب التظلم</th>":''?>
                                        <?=($action=='me' and $res_order['LEVEL_ACTIVE']>=4)?"<th style='width: 4%'>تظلم</th>":''?>
                                        <?=($action=='obj' or $action=='audit')?"<th style='width: 20%'>مبررات التعديل</th>":''?>
                                    </tr>
                                    </thead>
                                    <tbody>
                                <?php } ?>
                                <tr data-marks=''>
                                    <td><?=$cnt?>
                                        <input type="hidden" name="ser[]" value="<?=$row['SER']?>" />
                                        <input type="hidden" name="element_id[]" value="<?=$row['ELEMENT_ID']?>" />
                                        <input type="hidden" id="txt_old_mark" name="old_mark[]" value="<?=$row['MARK']?>" />
                                        <input type="hidden" id="txt_audit_mark" name="audit_mark[]" value="<?=$row['AUDIT_MARK']?>" />
                                    </td>
                                    <td style="text-align: right"><?=$row['ELEMENT_ID_NAME']?></td>
                                    <td>
                                        <input style="float: right; width: 85%" <?=($action=='me')?'disabled':'' ?> class="<?=$range_class?>" id="txt_new_mark" name="mark[]" type="range" min="0" max="100" step="1" value="<?=$row['MARK']?>" />
                                        <input style="float: right; width: 11%; margin-right: 2%; text-align: center" class="form-control" readonly type="text" value="<?=$row['MARK']?>" />
                                    </td>
                                    <td><?=(0) ? "<input class='form-control mark_desc' readonly type='text' value='' />" : ''?></td>
                                    <?=($action=='obj') ? "<td><input class='form-control' readonly type='text' value='".$row['OBJECTION_HINT']."' /></td>" : ''?>
                                    <?php if($action=='me' and $res_order['LEVEL_ACTIVE']>=4){
                                        if($row['OBJECTION']==1){
                                    ?>
                                        <td><a id="objection_url_icon_<?=$row['SER']?>" onclick="javascript:objec_req('<?=$row['SER']?>','<?=$row['OBJECTION']?>','<?=$row['OBJECTION_HINT']?>','<?=$row['ELEMENT_ID_NAME']?>','<?=$row['MARK_BEFORE_OBJ']?>');" href='javascript:;'><i style="color:red" class="glyphicon glyphicon-thumbs-down"></a></td>
                                    <?php }else if($res_order['LEVEL_ACTIVE']==4){ ?>
                                        <td><a id="objection_url_icon_<?=$row['SER']?>" onclick="javascript:objec_req('<?=$row['SER']?>','<?=$row['OBJECTION']?>','<?=$row['OBJECTION_HINT']?>','<?=$row['ELEMENT_ID_NAME']?>','<?=$row['MARK_BEFORE_OBJ']?>');" href='javascript:;'><i class="glyphicon glyphicon-thumbs-down"></a></td>
                                    <?php } else echo '<td></td>'; } ?>
                                    <?=($action=='obj') ? "<td><input class='form-control' id='txt_gr_objection_hint' name='gr_objection_hint[]' type='text' value='".$row['GR_OBJECTION_HINT']."' /></td>" : ''?>
                                    <?=($action=='audit') ? "<td><input class='form-control' id='txt_audit_hint' name='audit_hint[]' type='text' value='".$row['AUDIT_HINT']."' /></td>" : ''?>
                                </tr>

                                <?php $count++; $cnt++;
                                if( $key+1==count($grandson_details) ){ ?>
                                    </tbody>
                                    </table>
                                    </div>
                                    </div>
                                    </fieldset>
                                <?php } endforeach; } ?>

                    </div>
                </div>
                    <div class="modal-footer">
                        <?php if (HaveAccess($edit_url) and $action=='edit' and $master_res['ADOPT']==1)   : ?>
                            <button type="submit" data-action="submit" id='btn_update' class="btn btn-primary edit">تعديل التقييم</button>
                        <?php endif; ?>
                        <?php if (HaveAccess($adopt_url) and $action=='edit' and $master_res['ADOPT']==1) : ?>
                            <button type="button" onclick='javascript:adopt();' id='btn_adopt' class="btn btn-success">اعتماد</button>
                        <?php endif; ?>
                        <?php if (HaveAccess($cancel_adopt_url) and $action!='me' /* and $action=='show_before_end' */ and $master_res['ADOPT']==2) : ?>
                            <button type="button" onclick='javascript:cancel_adopt();' id='btn_cancel_adopt' class="btn btn-danger">الغاء الاعتماد</button>
                        <?php endif; ?>
                        <?php if (HaveAccess($objection_url) and $action=='obj') : ?>
                            <button type="submit" data-action="submit" class="btn btn-primary edit">حفظ</button>
                        <?php endif; ?>
                        <?php if (HaveAccess($audit_url) and $action=='audit') : ?>
                            <button type="submit" data-action="submit" class="btn btn-primary edit">حفظ</button>
                        <?php endif; ?>

                        <?php if ( (HaveAccess($adopt_admin_manager_url) or $master_res['OPINION']!=0) and $master_res['ADOPT']==2 and $master_res['FORM_TYPE']==1 ) :
                            if($master_res['OPINION_REASON']!=null){
                                $opinion_reason= 'ملاحظات مدير الادارة/المقر: '.$master_res['OPINION_REASON'];
                                $disabled= 'disabled';
                            }else{
                                $opinion_reason='';
                                $disabled= '';
                            }
                        ?>
                            <input type="text" name="opinion_reason" <?=$disabled?> value="<?=$opinion_reason?>" id="txt_opinion_reason" placeholder="سبب الاعتماد/التحفظ " class="form-control" />
                        <?php endif; ?>

                        <?php if (HaveAccess($adopt_admin_manager_url) and $action=='admin_manager' and $res_order['LEVEL_ACTIVE']==2 and $master_res['ADOPT']==2 and $master_res['OPINION']==0) : ?>
                            <button type="button" onclick='javascript:adopt_admin_manager(1);' id='btn_adopt' class="btn btn-success">اعتماد</button>
                            <button type="button" onclick='javascript:adopt_admin_manager(2);' id='btn_adopt' class="btn btn-info">تحفظ</button>
                        <?php endif; ?>

                        <?=modules::run('attachments/attachment/index',$master_res['EVALUATION_ORDER_SERIAL'],'evaluation_emp_order');?>
                    </div>
            </form>
        </div>
</div>

<!-- Modal For Objection Request -->
<div class="modal fade" id="<?=$TB_NAME?>Modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">طلب تظلم</h4>
            </div>
            <form class="form-horizontal" id="<?=$TB_NAME?>_modal_form" method="post" action="<?=$objection_url?>" role="form" novalidate="novalidate">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">السؤال</label>
                        <div class="col-sm-8">
                            <input type="text" readonly name="element_id_name" id="txt_element_id_name" value="" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">التقييم قبل التظلم</label>
                        <div class="col-sm-8">
                            <input type="text" readonly name="mark_before_obj" id="txt_mark_before_obj" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">سبب التظلم</label>
                        <div class="col-sm-8">
                            <input type="hidden" name="ser" id="txt_ser" value="" />
                            <input type="text" name="objection_hint"  id="txt_objection_hint" value="" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" data-action="submit" id="modal_submit" class="btn btn-primary">حفظ</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- END Modal For Objection Request -->


<!-- Modal For Excellent Edependency and Manager Note -->
<div class="modal fade" id="<?=$TB_NAME?>Excellent_Modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">ملاحظات</h4>
            </div>
            <form class="form-vertical" id="<?=$TB_NAME?>_excellent_modal_form" method="post" action="<?=$adopt_url?>" role="form" novalidate="novalidate">
                <div class="modal-body inline_form">
                    <div class="form-group col-sm-12" id="excellent_edependency" style="display:none ;">
                        <label class="control-label">لقد حصل الموظف على تقدير امتياز وهذا يتطلب توضيح أسباب الامتياز في ما لا يقل عن 20 حرف</label>
                        <div>
                            <input type="text" name="excellent_edependency"  id="txt_excellent_edependency" value="" class="form-control">
                        </div>
                    </div>

                    <div class="form-group col-sm-12">
                        <label class="control-label">تقييم وصفي (ملاحظات) في ما لا يقل عن 20 حرف</label>
                        <div>
                            <input type="hidden" name="evaluation_order_serial" value="<?=$master_res['EVALUATION_ORDER_SERIAL']?>" class="form-control">
                            <input type="text" name="manager_note"  id="txt_manager_note" value="" class="form-control">
                        </div>
                    </div>

                    <div class="form-group col-sm-12">
                        <label class="control-label">توصية بنقل الموظف الى </label>
                        <div>
                            <input type="text" name="emp_transfer" placeholder="الادخال اختياري" id="txt_emp_transfer" value="" class="form-control">
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" data-action="submit" id="excellent_modal_submit" class="btn btn-primary">حفظ</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- END Modal For Excellent Edependency and Manager Note -->

<!-- Emps Courses Modal -->
<div class="modal fade" id="courses_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">الدورات التدريبية</h4>
            </div>
            <form class="form-vertical" id="courses_form" method="post" action="<?=$save_courses_url?>" role="form" novalidate="novalidate">
                <div class="modal-body inline_form">
                    <input type="hidden" value="<?=$master_res['EVALUATION_ORDER_SERIAL']?>" name="evaluation_order_serial" />
                    <table class="table" id="courses_details_tb" data-container="container">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>الدورة</th>
                            <th>حذف</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                        <tr>
                            <th></th>
                            <th>
                                <?php if ( HaveAccess($save_courses_url) ) { ?>
                                    <a onclick="javascript:addRow();" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>
                                <?php } ?>
                            </th>
                            <th></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="modal-footer">
                    <?php if ( HaveAccess($save_courses_url) and $master_res['EMP_MANAGER_ID']==$curr_emp_no ) : ?>
                        <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                    <?php endif; ?>
                    <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Emps Courses Modal -->

<?php
$scripts = <<<SCRIPT
<script>

    $('table#page_tb tbody tr').each(function(){
        ask_mark_desc( $(this) );
    });

    $('input[type="range"]').on("change mousemove keypress", function() { // change mousemove keypress  mouseup keyup
        var ask_val= parseInt($(this).val());
        $(this).next().val(ask_val);
        var ask_tr= $(this).closest('tr');
        ask_mark_desc(ask_tr);
    });

    function ask_mark_desc(ask_tr){
        if (ask_tr.attr('data-marks')=='') return 0;

        var marks_json= JSON.parse( ask_tr.attr('data-marks') );
        var ask_val= parseInt( ask_tr.find('input[type="range"]').val() );
        $.each(marks_json, function( index, data ) {
            if(ask_val >= parseInt(data.MARK_FROM) && ask_val <= parseInt(data.MARK_TO) ){
                ask_tr.find('.mark_desc').val( marks_json[index].RANGE_ORDER_NAME+': '+ marks_json[index].MARK_RANGE_DESCRIPTION);
                ask_tr.find('.mark_desc').css('background-color',range_bk_color(parseInt(marks_json[index].RANGE_ORDER)));
            }
        });
    }

    function range_bk_color(bk_color) {
        switch (bk_color) {
        case 1:
            bk_color = '#F8696B';
            break;
        case 2:
            bk_color = '#FCBF7B';
            break;
        case 3:
            bk_color = '#E9E583';
            break;
        case 4:
            bk_color = '#B1D580';
            break;
        case 5:
            bk_color = '#63BE7B';
        }
        return bk_color;
    }

    function check_mark() {
        var ret=1;
        $('table#page_tb tbody tr').each(function(){
            var new_mark = $(this).find('#txt_new_mark').val();
            var old_mark = $(this).find('#txt_old_mark').val();
            var audit_mark = $(this).find('#txt_audit_mark').val();
            var audit_hint = $(this).find('#txt_audit_hint').val();
            $(this).find('#txt_audit_hint').removeClass('changeBorder').attr("placeholder", "");
            if((new_mark != old_mark && audit_hint.trim().length<30) || (audit_mark!='' && audit_hint.trim().length<30)){
                ret = 0;
                $(this).find('#txt_audit_hint').addClass('changeBorder').attr("placeholder", "ادخل رأي اللجنة");
            }
        });
        return ret;
    }

    function check_obj_hint() {
        var ret=1;
        $('table#page_tb tbody tr').each(function(){
            var gr_objection_hint = $(this).find('#txt_gr_objection_hint').val();
            var old_mark = $(this).find('#txt_old_mark').val();
            var new_mark = $(this).find('#txt_new_mark').val();
            $(this).find('#txt_gr_objection_hint').removeClass('changeBorder').attr("placeholder", "");
            if(gr_objection_hint.trim().length<30 && old_mark!=new_mark ){
                ret = 0;
                $(this).find('#txt_gr_objection_hint').addClass('changeBorder').attr("placeholder", "ادخل رأي اللجنة");
            }
        });
        return ret;
    }

    $('button[data-action="submit"].edit').click(function(e){
        e.preventDefault();
        if(confirm('هل تريد تعديل التقييم !')){

            if('{$action}'=='audit' && !check_mark() && 0 ){ // تم الغاء ادخال الرأي بناء على توصيحة لجنة التدقيق 5-6-2017
                danger_msg('تحذير..','ادخل رأي اللجنة للدرجات التي تم تعديلها');
            }else if('{$action}'=='obj' && !check_obj_hint()){
                danger_msg('تحذير..','ادخل رأي لجنة التظلم');
                }else{
                    $(this).attr('disabled','disabled');
                    var form = $(this).closest('form');
                    ajax_insert_update(form,function(data){
                        if(data>=1 || (data==0 && '{$action}'=='audit'  ) ){
                            success_msg('رسالة','تم تعديل التقييم بنجاح ...');
                            get_to_link(window.location.href);
                        }else if(data==-10 && '{$action}'=='audit'){
                            danger_msg('تحذير..','يجب الا تتجاوز قيمة التعديل 10% من النتيجة النهائية');
                        }else{
                            danger_msg('تحذير..',data);
                        }
                    },'html');
            }
        }
        setTimeout(function() {
            $('button[data-action="submit"]').removeAttr('disabled');
        }, 3000);
    });

    /* ADOPT And Excellence Modal */
     function adopt(){
        msg= ' هل تريد اعتماد التقييم ؟';
        if(confirm(msg)){
            var adopt_url;
                adopt_url= '{$adopt_url}';
            var values= {evaluation_order_serial: "{$master_res['EVALUATION_ORDER_SERIAL']}"};
            get_data(adopt_url, values, function(ret){
                if(ret==1){
                    success_msg('رسالة','تم اعتماد التقييم بنجاح ...');
                    $("#btn_adopt,#btn_update").attr('disabled','disabled');
                    if('{$master_res['FORM_TYPE']}'==1){
                        /* prevent browser from close */
                        window.addEventListener("beforeunload", function(event) {
                            event.returnValue = "You may have unsaved Data";
                        });
                        $('#{$TB_NAME}Excellent_Modal').modal();
                    }else{
                        get_to_link(window.location.href);
                    }
                }else if(ret==10){
                    /* prevent browser from close */
                    window.addEventListener("beforeunload", function(event) {
                        event.returnValue = "You may have unsaved Data";
                    });
                    success_msg('رسالة','تم اعتماد التقييم بنجاح , ادخل سبب الإمتياز');
                    $("#btn_adopt,#btn_update").attr('disabled','disabled');
                    $('#excellent_edependency').show();
                    $('#{$TB_NAME}Excellent_Modal').modal();
                }else if(ret== -100){
                    danger_msg('تحذير..','لم يتم الاعتماد بسبب تجاوز العدد المسموح به  ');
                }else{
                    danger_msg('تحذير..',ret);
                }
            }, 'html');
        }
    }

    function cancel_adopt(){
        msg= ' هل تريد الغاء اعتماد التقييم ؟';
        if(confirm(msg)){
            var values= {evaluation_order_serial: "{$master_res['EVALUATION_ORDER_SERIAL']}"};
            get_data('{$cancel_adopt_url}', values, function(ret){
                if(ret==1){
                    success_msg('رسالة','تم الغاء اعتماد التقييم بنجاح ...');
                    $("#btn_cancel_adopt").attr('disabled','disabled');
                }else{
                    danger_msg('تحذير..',ret);
                }
            }, 'html');
        }
    }

    function check_note(text_object) {
        var ret=1;
            if(text_object.val().trim().length<20){
                ret = 0;
                text_object.css({"border-color": "#ff0000", "border-width": "2px"});
            }
        return ret;
    }

    $('#{$TB_NAME}_excellent_modal_form button[data-action="submit"]').click(function(e){
        e.preventDefault();
        var form = $(this).closest('form');
		if($('#excellent_edependency').is(":visible") && !check_note( $('#txt_excellent_edependency') )){
			danger_msg('تحذير..','ادخل سبب التمّيز بحيث لا يقل عن 20 حرف');
		}else if(!check_note( $('#txt_manager_note') )){
		    danger_msg('تحذير..','ادخل التقييم الوصفي بحيث لا يقل عن 20 حرف');
		}else{
			ajax_insert_update(form,function(data){
				if(data==1){
					success_msg('تم الحفظ','   رسالة ..');
					/* allow browser to close */
					window.addEventListener("beforeunload", function(event) {
                      event.returnValue = "";
                    });
                    //get_to_link(window.location.href);
                    get_to_link('{$ev_start_url}');
				}else {
					danger_msg('تحذير..',data);
				}
				$('#{$TB_NAME}Excellent_Modal').modal('hide');
			},"html");
		}
    });

    $(function () {
        $('#{$TB_NAME}Excellent_Modal').on('shown.bs.modal', function () {
            $('#txt_excellent_edependency').focus();
        });
    });

    /* END ADOPT And Excellence Modal */


    /* Modal Objection Request */

    $(function () {
        $('#{$TB_NAME}Modal').on('shown.bs.modal', function () {
            $('#txt_objection_hint').focus();
        });
    });

    function objec_req(ser,objection,objection_hint,element_name,mark_before_obj){
        alert('الخاصية معطلة'); return 0;
        clearForm($('#{$TB_NAME}_modal_form'));
        $("#txt_objection_hint").prop('disabled', 0);
        $("#modal_submit").show();
        $('#{$TB_NAME}Modal').modal();
        $('#txt_ser').val(ser);
        $('#txt_objection_hint').val(objection_hint);
        $('#txt_element_id_name').val(element_name);
        $('#txt_mark_before_obj').val(mark_before_obj);
        if(parseInt(objection) ==1){
        $("#txt_objection_hint").prop('disabled', true);
        $("#modal_submit").hide();
        }
    }

    $('#{$TB_NAME}_modal_form button[data-action="submit"]').click(function(e){
        e.preventDefault();
        var form = $(this).closest('form');
        ajax_insert_update(form,function(data){
            if(data>=1){
                $("#objection_url_icon_"+ $('#txt_ser').val().replace(",", "-") ).hide();
                success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                $('#{$TB_NAME}Modal').modal('hide');
            }else {
                danger_msg('تحذير..',data);
            }
        },"html");
    });
    /* End Modal Objection Request */

    /* ------------------------  Emps courses ----------------------- */

    var courses_json= {$courses_all};
    var cat_courses_json= {$cat_courses_all};

    var select_courses= '<option value=""> _________ </option>';

    $.each(cat_courses_json, function(k,cat){
        select_courses += "<optgroup label='"+cat.CON_NAME+"'>";
        $.each(courses_json, function(i,item){
            if(cat.CON_NO==item.ACCOUNT_ID)
                select_courses += "<option value='"+item.CON_NO+"' >"+item.CON_NAME+"</option>";
        });
        select_courses += "</optgroup>";
    });

    function get_courses(){
        var cnt=0;
        get_data('{$get_courses_url}',{id:{$master_res['EVALUATION_ORDER_SERIAL']}},function(data){
            $('#courses_details_tb tbody').html('');
            $.each(data, function(i,item){
                cnt++;
                if(item.COURSE_ID == null) item.COURSE_ID= '';
                var row_html='<tr><td>'+cnt+'</td><td><input type="hidden" name="course_ser[]" id="h_course_ser_'+cnt+'" value="'+item.SER+'" /><select name="course_id[]" class="form-control" id="dp_course_id_'+cnt+'" data-val="'+item.COURSE_ID+'" ></select></td><td><i class="glyphicon glyphicon-remove delete_course"></i></td>';
                $('#courses_details_tb tbody').append(row_html);
            });
            count= cnt;

            $('select[name="course_id[]"]').append(select_courses);
            $('select[name="course_id[]"]').each(function(){
                $(this).val($(this).attr('data-val'));
            });
            $('select[name="course_id[]"]').select2().select2('readonly',1);

            $('#courses_modal').modal();

            $('.delete_course').click(function(){
                var tr = $(this).closest('tr');
                tr.find('select[name="course_id[]"]').select2('val','');
            });
        });
    }

    function addRow(){
        count = count+1;
        var html='<tr><td>'+count+'</td><td><input type="hidden" name="course_ser[]" id="h_course_ser_'+count+'" value="0" /><select name="course_id[]" class="form-control" id="dp_course_id_'+count+'" ></select></td><td><i class="glyphicon glyphicon-remove delete_course"></i></td>';
        $('#courses_details_tb tbody').append(html);
        reBind();
    }

    function reBind(){
        $('select#dp_course_id_'+count).append(select_courses).select2();
        $('.delete_course').click(function(){
            var tr = $(this).closest('tr');
            tr.find('select[name="course_id[]"]').select2('val','');
        });
    }

    $('#courses_form button[data-action="submit"]').click(function(e){
        e.preventDefault();
        var form = $(this).closest('form');
        ajax_insert_update(form,function(data){
            if(data==1){
                success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                $('#courses_modal').modal('hide');
            }else{
                danger_msg('تحذير..',data);
            }
        },"html");
    });

    /* ------------------------------ End Emps courses  ---------------------------- */


    /* ------------------------------ Start adopt admin manager ---------------------------- */

    function adopt_admin_manager(opinion){
        var opinion_reason= $('#txt_opinion_reason').val();
        if(opinion_reason.length < 50 && opinion==2){
            danger_msg('تنبيه..','يجب ادخال سبب التحفظ فيما لا يقل عن 50 حرف');
            return 0;
        }
        var msg= 'هل تريد بالتأكيد اجراء العملية؟!!';
        if(confirm(msg)){
            var adopt_url= '{$adopt_admin_manager_url}';
            var values= {evaluation_order_serial: "{$master_res['EVALUATION_ORDER_SERIAL']}", opinion:opinion, opinion_reason:opinion_reason };
            get_data(adopt_url, values, function(ret){
                if(ret==1){
                    success_msg('رسالة','تمت العملية بنجاح ...');
                    $("button").attr('disabled','disabled');
                }else{
                    danger_msg('تحذير..',ret);
                }
            }, 'html');
        }
    }

    /* ------------------------------ End adopt admin manager ---------------------------- */


</script>
SCRIPT;
sec_scripts($scripts);
?>
