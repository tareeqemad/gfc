<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 22/01/20
 * Time: 11:47 ص
 */

$MODULE_NAME = 'training';
$TB_NAME = 'employeeTraining';
$back_url = base_url("$MODULE_NAME/$TB_NAME");
$create_url = base_url("$MODULE_NAME/$TB_NAME/create");
$adopt_url = base_url("$MODULE_NAME/$TB_NAME/adopt");
$un_adopt_url = base_url("$MODULE_NAME/$TB_NAME/unadopt");
$post_url = base_url("$MODULE_NAME/$TB_NAME/" . ($action == 'index' ? 'create' : $action));
$post_dates_url = base_url("$MODULE_NAME/$TB_NAME/" . ($action == 'index' ? 'saveDates' : 'saveDates_update'));
$post_trainee_url = base_url("$MODULE_NAME/$TB_NAME/saveTrainee");
$post_trainees_url = base_url("$MODULE_NAME/$TB_NAME/saveTrainee");
$get_url =base_url("$MODULE_NAME/$TB_NAME/get");
$updateCourse = base_url("$MODULE_NAME/traineeRequest/updateCourse");
$TRAIN_COURSES_DATES = 'public_get_trainee_courses_dates';
$TRAIN_TRAINEES = 'public_get_trainee_courses';
$TRAIN_COURSES_TRAINEE = 'public_get_trainee_courses_trainee';
$get2_url = base_url("$MODULE_NAME/$TB_NAME/public_get_trainee_courses_trainee");
$save_emp_train_url = base_url("$MODULE_NAME/$TB_NAME/saveEmpTrain");
$select_url= base_url("$MODULE_NAME/$TB_NAME/public_select_emp");

if (!isset($result))
    $result = array();
$HaveRs = count($result) > 0;
$rs = $HaveRs ? $result[0] : $result;
$HaveRs_trainee = count($result_trainee) > 0;
$rs_trainee = $HaveRs_trainee ? $result_trainee[0] : $result_trainee;
$HaveRs_com = count($result_com) > 0;
$rs_com = $HaveRs_com ? $result_com[0] : $result_com;
$HaveRs_per = count($result_per) > 0;
$rs_per = $HaveRs_per ? $result_per[0] : $result_per;
$HaveRs_gedco = count($result_gedco) > 0;
$rs_gedco = $HaveRs_gedco ? $result_gedco[0] : $result_gedco;


?>
    <div class="row">
        <div class="toolbar">
            <div class="caption"><?= $title ?></div>
            <ul>
                <?php if (HaveAccess($create_url)): ?>
                    <li><a href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>جديد</a> </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>

    <div class="form-body">
        <div id="container">
            <form class="form-horizontal" id="<?= $TB_NAME ?>_form" method="post" action="<?php echo $post_url ?>" role="form"
                  novalidate="novalidate">
                <div class="modal-body inline_form">
                    <fieldset class="field_set">
                        <legend>تدريب موظفين - دورة</legend>
                        <br>
                        <div class="form-group">
                            <label class="col-sm-1 control-label">رقم مسلسل</label>
                            <div class="col-sm-2">
                                <input type="text" readonly name="ser"
                                       value="<?= $HaveRs ? $rs['SER'] : "" ?>"
                                       id="txt_ser" class="form-control">
                            </div>

                            <label class="col-sm-1 control-label">رقم الدورة</label>
                            <div class="col-sm-2">
                                <input type="text" readonly name="course_no"
                                       value="<?= $HaveRs ? $rs['COURSE_NO'] : "" ?>"
                                       id="txt_course_no" class="form-control">
                            </div>


                        </div>

                        <div class="form-group">
                            <label class="col-sm-1 control-label">اسم الدورة/ اللغة العربية</label>
                            <div class="col-sm-2">
                                <input type="text"  name="course_name"
                                       value="<?= $HaveRs ? $rs['COURSE_NAME'] : "" ?>"
                                       id="txt_course_name" class="form-control">
                            </div>

                            <label class="col-sm-1 control-label">اسم الدورة/ اللغة الانجليزية</label>
                            <div class="col-sm-2">
                                <input type="text"  name="course_name_eng"
                                       value="<?= $HaveRs ? $rs['COURSE_NAME_ENG'] : "" ?>"
                                       id="txt_course_name_eng" class="form-control">
                            </div>

                            <label class="col-sm-1 control-label">الجهة الطالبة</label>
                            <div class="col-sm-2">
                                <input type="text"  name="request_side"
                                       value="<?= $HaveRs ? $rs['REQUEST_SIDE'] : "" ?>"
                                       id="txt_request_side" class="form-control">
                            </div>

                        </div>

                        <div class="form-group">

                            <label class="col-sm-1 control-label">تاريخ بداية الدورة</label>
                            <div class="col-sm-2">
                                <input type="text" data-val-required="حقل مطلوب" data-type="date"
                                       data-date-format="DD/MM/YYYY" name="course_date"
                                       id="txt_course_date" class="form-control"
                                       value="<?= $HaveRs ? $rs['COURSE_DATE'] : "" ?>">
                            </div>

                            <label class="col-sm-1 control-label">عدد ساعات الدورة</label>
                            <div class="col-sm-2">
                                <input type="text"  name="course_hour"
                                       value="<?= $HaveRs ? $rs['COURSE_HOUR'] : "" ?>"
                                       id="txt_course_hour" class="form-control">
                            </div>

                            <label class="col-sm-1 control-label">الفئة المستهدفة</label>
                            <div class="col-sm-2">
                                <input type="text"  name="target_group"
                                       value="<?= $HaveRs ? $rs['TARGET_GROUP'] : "" ?>"
                                       id="txt_target_group" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-1 control-label">ملاحظات</label>
                            <div class="col-sm-9">
                                <textarea data-val-required="حقل مطلوب"
                                          class="form-control" name="notes" rows="3"
                                          id="txt_notes"><?= $HaveRs ? $rs['NOTES'] : "" ?></textarea>
                            </div>
                        </div>

                        <?php if(!$isCreate) { ?>
                            <div class="form-group">
                                <label class="col-sm-1 control-label">المرفقات</label>
                                <div class="col-sm-2">
                                    <?php echo modules::run('attachments/attachment/index',$rs['SER'],'training'); ?>
                                </div>
                            </div>
                        <?php  } ?>

                        <div class="modal-footer">
                            <?php
                            if (  HaveAccess($post_url)  && ($isCreate || ( $rs['ADOPT']==1 ) ) AND  ( $isCreate || @$rs['ENTRY_USER']?$this->user->id : false )  ) : ?>
                                <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                            <?php endif; ?>

                            <?php  if ( HaveAccess($adopt_url) && (!$isCreate and ( (count($rs)>0)? $rs['ADOPT']==1 : '' ) and  $this->user->branch == $rs['BRANCH_NO'] )) : ?>
                                <button type="button" id="btn_adopt" onclick='javascript:return_adopt(1);' class="btn btn-success">اعتماد  </button>
                            <?php  endif; ?>

                            <?php  if ( HaveAccess($un_adopt_url)  && (!$isCreate and ( count($rs_trainee) <= 0 ) and ( (count($rs)>0)? $rs['ADOPT']==2 : '' ) and  $this->user->branch == $rs['BRANCH_NO'] )) : ?>
                                <button type="button"  id="btn_unadopt" onclick="javascript:return_adopt(2);" class="btn btn-danger">الغاء الاعتماد</button>
                            <?php  endif; ?>

                            <?php  if ((!$isCreate and ( (count($rs)>0)? $rs['ADOPT']==2 : '' ) and ( count($rs_trainee) <= 0 ) and  $this->user->branch == $rs['BRANCH_NO'] )) : ?>
                                <button type="button"  id="btn_unadopt" onclick="javascript:assign_(<?= $rs['SER'] ?>);" class="btn btn-warning">اسناد لمدرب</button>
                            <?php  endif; ?>

                        </div>

                    </fieldset>
                </div>
            </form>
        </div>
        <?php if(count($result_trainee) > 0 ){ ?>
            <fieldset class="field_set" >
                <legend>بيانات التدريب</legend>
                <br>
                <div class="modal-body inline_form">
                <fieldset class="field_set" >
                    <legend>المُدرب </legend>
                    <br>
                    <div class="modal-footer">
                        <?php  if ((!$isCreate and ( (count($rs)>0)? $rs['ADOPT']==2 : '' ) and  $this->user->branch == $rs['BRANCH_NO'] )) : ?>
                            <button style="background-color: #9b4add; color: #ffffff" type="button"  id="btn_unadopt" onclick="javascript:assign_(<?= $rs['SER'] ?>);" class="btn">اسناد لمدرب</button>
                        <?php  endif; ?>
                    </div>
                    <form class="form-horizontal" id="traineeCourse_form" method="post" action="<?php echo $post_trainees_url ?>" role="form"
                          novalidate="novalidate">
                        <?php echo modules::run("$MODULE_NAME/$TB_NAME/$TRAIN_TRAINEES",
                            $HaveRs ? $rs['SER'] : 0  ); ?>
                        <div class="modal-footer">
                        </div>
                    </form>
                </fieldset>

                    <fieldset class="field_set" >
                        <legend>مواعيد الدورة</legend>
                        <form class="form-horizontal" id="datesCourse_form" method="post" action="<?php echo $post_dates_url ?>" role="form"
                              novalidate="novalidate">
                            <?php echo modules::run("$MODULE_NAME/$TB_NAME/$TRAIN_COURSES_DATES",
                                $HaveRs ? $rs['SER'] : 0  ); ?>
                            <div class="modal-footer">
                                <?php  if (HaveAccess($post_dates_url)) : ?>
                                <button type="submit" id="saveDates-btn" class="btn btn-primary">حفظ المواعيد</button>
                                <?php endif; ?>
                            </div>
                        </form>
                    </fieldset>
                    <fieldset class="field_set" >
                        <legend>المتدربين</legend>
                        <div class="modal-body">
                            <button  type="button" style="float: left"
                                    onclick="javascript:showEmpsModal(<?= $rs['SER']?>);"
                                    id="showTrainee-btn" class="btn btn-warning">عرض الموظفين</button>
                            <br><br>
                            <div id="container_emp">
                                <?php echo modules::run("$MODULE_NAME/$TB_NAME/$TRAIN_COURSES_TRAINEE",
                                    $HaveRs ? $rs['SER'] : 0  ); ?>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </fieldset>
        <?php } ?>
    </div>

    <!----------------------------------->
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog" style="width: 900px; height: 600px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 id="title" class="modal-title">اسناد لمدرب</h3>
                </div>
                <form class="form-horizontal" id="<?= $TB_NAME ?>_form_det_com" method="post" action="" role="form" >

                    <input type="hidden" name="course_ser" id="txt_course_ser">

                    <div class="modal-body">
                        <?php $count =1; ?>
                        <div class="row">
                            <select id='filterText'class="form-control" style='display:inline-block' >
                                <option></option>
                                <?php foreach($trainee_type_con as $row) :?>
                                    <option  value="<?= $row['CON_NO'] ?>" ><?php echo $row['CON_NAME']  ?></option>
                                <?php endforeach; ?>
                            </select>

                            <br><br><br>
                            <div hidden id="company">
                                <table class="table" id="page_tb" data-container="container">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>رقم الترخيص</th>
                                        <th>اسم الشركة</th>
                                        <th>تفاصيل</th>
                                        <th>اسناد</th>
                                        <?php
                                        $count++;
                                        ?>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if(count($details_company) > 0): ?>
                                        <tr >
                                            <td colspan="12"  class="page-sector"></td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php foreach ($details_company as $row_det_com) : ?>
                                        <tr id="tr_<?=$row_det_com['SER']?>" >
                                            <td><?=$count?></td>
                                            <td><?=$row_det_com['LICENSE_NUM']?></td>
                                            <td><?=$row_det_com['COMPANY_NAME']?></td>
                                            <td>
                                                <a target="_blank" href="<?=base_url("$MODULE_NAME/traineeRequest/get_company/{$row_det_com['SER']}" )?>"><i class='glyphicon glyphicon-share'></i></a>
                                            </td>
                                            <?php if(HaveAccess($updateCourse)) {?>
                                                <td><a class="bt btn-success btn-xs" onclick="javascript:save_('<?=$row_det_com['SER']?>',1);" href='javascript:;'>حفظ<i class='glyphicon glyphicon-ok'></i> </a> </td>
                                            <?php }
                                            $count++;
                                            ?>
                                        </tr>
                                    <?php endforeach;?>
                                    </tbody>
                                </table>

                            </div>
                            <div hidden id="person">
                                <table class="table" id="page_tb" data-container="container">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>رقم الهوية</th>
                                        <th>الاسم</th>
                                        <th>السيرة الذاتية</th>
                                        <th>اسناد</th>
                                        <?php
                                        $count++;
                                        ?>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if(count($details) > 0): ?>
                                        <tr >
                                            <td colspan="12"  class="page-sector"></td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php foreach ($details as $row_det) : ?>
                                        <tr id="tr_<?=$row_det['SER']?>" >
                                            <td><?=$count?></td>
                                            <td><?=$row_det['ID']?></td>
                                            <td><?=$row_det['NAME']?></td>
                                            <td>
                                                <a target="_blank" href="<?=base_url("$MODULE_NAME/traineeRequest/get_person/{$row_det['SER']}" )?>"><i class='glyphicon glyphicon-share'></i></a>
                                            </td>
                                            <?php if(HaveAccess($updateCourse)) {?>
                                                <td><a class="bt btn-success btn-xs" onclick="javascript:save_('<?=$row_det['SER']?>' , 2);" href='javascript:;'>حفظ<i class='glyphicon glyphicon-ok'></i> </a> </td>
                                            <?php }
                                            $count++;
                                            ?>
                                        </tr>
                                    <?php endforeach;?>
                                    </tbody>
                                </table>

                            </div>
							<div hidden id="empGedco">
								<div class="row">
									<div class="form-body">
										<div id="msg_container"></div>
										<div class="inliform-group">
											<div style="float:right" class="input-group col-sm-4">
												<span class="input-group-addon">  <i class="icon icon-search"></i></span>
												<input type="text" id="search-tbl" data-set="accountsTbl" class="form-control" placeholder="بحث">

											</div>
										</div>
										<br><br>
											<div id="container">
												<form class="form-horizontal" id="_emps_form" method="post" action="<?= $save_emp_train_url ?>" role="form" >
													<table class="table selected-red" id="accountsTbl" data-container="container">
														<input type="hidden" name="emps_ser" id="h_txt_emps_ser">
														<input type="hidden" name="course_ser" value="<?= $rs['SER']?>" id="h_txt_course_ser">
														<thead>
														<tr>
															<th>الرقم الوظيفي</th>
															<th>الاسم</th>
															<th>الادارة</th>
															<th>المقر</th>
															<th></th>

														</tr>
														</thead>
														<tbody>

														<?php foreach ($rows as $row) : ?>
															<tr  id="tr_<?=$row['ID']?>"  >
																<td><?= $row['EMP_NO'] ?></td>
																<td><?= $row['USER_NAME'] ?></td>
																<td><?= $row['STRUCTURE_NAME'] ?></td>
																<td><?= $row['BRANCH_NAME'] ?></td>
																<td>
																<?php if(HaveAccess($updateCourse)) {?>
																	<a onclick="javascript:save_('<?=$row['EMP_NO']?>' , 3);" class="btn btn-success btn-xs" href='javascript:;'>
																	<i class='glyphicon glyphicon-ok'></i>  حفظ</a>
																<?php } ?>																		
																</td>
															</tr>

														<?php endforeach; ?>
														</tbody>
													</table>
												</form>
											</div>
									</div>
								</div>
                            </div>
                            <div hidden id="traineeGedco">
                                <table class="table" id="page_tb" data-container="container">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>رقم الهوية</th>
                                        <th>الاسم</th>
                                        <th>التفاصيل</th>
                                        <th>اسناد</th>
                                        <?php
                                        $count++;
                                        ?>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if(count($trainee_gedco) > 0): ?>
                                        <tr >
                                            <td colspan="12"  class="page-sector"></td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php foreach ($trainee_gedco as $row_ged) : ?>
                                        <tr id="tr_<?=$row_ged['SER']?>" >
                                            <td><?=$count?></td>
                                            <td><?=$row_ged['ID']?></td>
                                            <td><?=$row_ged['NAME']?></td>
                                            <td>
                                                <a  target="_blank"
                                                   href="<?=base_url("$MODULE_NAME/traineeRequest/get_trainee/{$row_ged['SER']}" )?>"><i class='glyphicon glyphicon-share'></i></a>
                                            </td>
                                            <?php if(HaveAccess($updateCourse)) {?>
                                                <td><a class="bt btn-success btn-xs"  onclick="javascript:save_('<?=$row_ged['SER']?>' , 4);" href='javascript:;'>حفظ<i class='glyphicon glyphicon-ok'></i> </a> </td>
                                            <?php }
                                            $count++;
                                            ?>
                                        </tr>
                                    <?php endforeach;?>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                </form>
            </div>
        </div>
    </div>
    <!-------------------------------------------->



<?php
$scripts = <<<SCRIPT
<script type="text/javascript">

$('.sel2').select2();

 $(document).ready(function() {

		$('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        if(confirm('هل تريد الحفظ  ؟!')){
          $(this).attr('disabled','disabled');
            var form = $(this).closest('form');
            ajax_insert_update(form,function(data){
                if(parseInt(data)>=1){
                    success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                     get_to_link('{$get_url}/'+parseInt(data));
                }else{
                    danger_msg('تحذير..',data);
                }
            },'html');
        }
        setTimeout(function() {
            $('button[data-action="submit"]').removeAttr('disabled');
        }, 3000);
    });

    $('#saveDates-btn').click(function(e){
        e.preventDefault();
        if(confirm('هل تريد حفظ المواعيد')){
          $(this).attr('disabled','disabled');
            var form =  $('#datesCourse_form');
            ajax_insert_update(form,function(data){
            console.log(data);
                if(parseInt(data)>=1){
                    success_msg('رسالة','تم حفظ البيانات بنجاح ..');
					reload_Page();

                }else{
                    danger_msg('تحذير..',data);
                }
            },'html');
        }
        setTimeout(function() {
            $('#saveDates-btn').removeAttr('disabled');
        }, 3000);
    });


    $('#showTrainee-btn1').click(function (e) {
        _showReport('{$select_url}/'+$(this).attr('id')+'/');
    });
	
	




   });

</script>

SCRIPT;
sec_scripts($scripts);
?>